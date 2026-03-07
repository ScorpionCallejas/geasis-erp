<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR ABONO A PAGO CON AUDITORÍA
	//agregar_abono_pago.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// 🔥 VALIDAR MODO DE OPERACIÓN PRIMERO
	$enviar_correo = isset($_POST['enviar_correo']) && $_POST['enviar_correo'] === 'true';

	// ===============================================
	// MODO CORREO - SOLO ENVIAR EMAIL
	// ===============================================
	// if ($enviar_correo) {
	// 	$id_pag = $_POST['id_pag'];
		
	// 	// Validar si el alumno tiene correo válido
	// 	$sqlAlumno = "
	// 		SELECT a.cor1_alu 
	// 		FROM pago p
	// 		JOIN alu_ram ar ON p.id_alu_ram10 = ar.id_alu_ram
	// 		JOIN alumno a ON ar.id_alu1 = a.id_alu
	// 		WHERE p.id_pag = '$id_pag'
	// 	";
		
	// 	$datosAlumno = obtener_datos_consulta($db, $sqlAlumno)['datos'];
	// 	$correo_enviado = false;
		
	// 	// Si tiene correo válido, enviar el ticket
	// 	if (!empty($datosAlumno['cor1_alu']) && filter_var($datosAlumno['cor1_alu'], FILTER_VALIDATE_EMAIL)) {
	// 		$correo_enviado = enviar_correo_ticket_pago($id_pag, $db);
			
	// 		if ($correo_enviado) {
	// 			error_log("✅ Correo enviado exitosamente para pago ID: $id_pag");
	// 		} else {
	// 			error_log("❌ Error al enviar correo para pago ID: $id_pag");
	// 		}
	// 	} else {
	// 		$correoAlumno = isset($datosAlumno['cor1_alu']) ? $datosAlumno['cor1_alu'] : 'NULL';
	// 		error_log("⚠️ No se envió correo para pago ID $id_pag - Correo inválido: " . $correoAlumno);
	// 	}
		
	// 	// Responder y TERMINAR
	// 	echo json_encode(array(
	// 		'success' => true,
	// 		'correo_enviado' => $correo_enviado,
	// 		'mensaje' => 'Correo procesado'
	// 	));
		
	// 	exit; // 🔥 SALIR AQUÍ - NO PROCESAR PAGO
	// }

	// ===============================================
	// MODO RÁPIDO - PROCESAR PAGO SIN CORREO
	// ===============================================
	$mon_abo_pag = $_POST['mon_abo_pag'];
	$tip_abo_pag = $_POST['tip2_abo_pag'];
	$id_pag = $_POST['id_pag'];
	$mon_pag = $_POST['mon_pag'];

	// Obtener datos completos del pago ANTES del abono
	$sqlPagoCompleto = "
		SELECT 
			p.id_pag,
			p.tip_pag,
			p.con_pag,
			p.mon_pag,
			p.mon_ori_pag,
			p.est_pag,
			p.id_alu_ram10,
			ar.id_alu_ram
		FROM pago p
		INNER JOIN alu_ram ar ON p.id_alu_ram10 = ar.id_alu_ram
		WHERE p.id_pag = '$id_pag'
	";

	$datosPago = obtener_datos_consulta($db, $sqlPagoCompleto)['datos'];
	$tip_pag = $datosPago['tip_pag'];
	$concepto_pago = $datosPago['con_pag'];
	$monto_antes = $datosPago['mon_pag'];
	$estado_antes = $datosPago['est_pag'];
	$id_alu_ram = $datosPago['id_alu_ram'];
	
	// Procesar el pago
	agregar_abono_pago_server($id_pag, $mon_pag, $tip_abo_pag, $mon_abo_pag, $nombreCompleto, $tip_pag);

	// Obtener el nuevo estado del pago DESPUÉS del abono
	$sqlEstadoNuevo = "SELECT mon_pag, est_pag FROM pago WHERE id_pag = '$id_pag'";
	$datosNuevos = obtener_datos_consulta($db, $sqlEstadoNuevo)['datos'];
	$monto_despues = $datosNuevos['mon_pag'];
	$estado_despues = $datosNuevos['est_pag'];

	// 📝 AUDITORÍA: Registrar el abono en observacion_alu_ram
	$tipo_abono_label = ($tip_abo_pag == 'Depósito') ? '💵 Depósito' : '💳 Efectivo';
	$monto_formateado = "$" . number_format($mon_abo_pag, 2);
	$saldo_anterior = "$" . number_format($monto_antes, 2);
	$saldo_nuevo = "$" . number_format($monto_despues, 2);
	
	$mensaje_auditoria = "$tipo_abono_label ABONO PAGO - Concepto: [$concepto_pago] | Abono: $monto_formateado | Saldo: $saldo_anterior → $saldo_nuevo | Estado: $estado_antes → $estado_despues";
	
	$sqlAuditoria = "
		INSERT INTO observacion_alu_ram (obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram)
		VALUES ('$mensaje_auditoria', '$id_alu_ram', '$nombreCompleto')
	";
	mysqli_query($db, $sqlAuditoria);

	// ✅ RESPONDER (MODO RÁPIDO)
	echo json_encode(array(
		'success' => true,
		'id_pag' => $id_pag,
		'mensaje' => 'Pago procesado correctamente'
	));
	
?>