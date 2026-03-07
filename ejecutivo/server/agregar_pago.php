<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO CONCEPTO DE PAGO DE ALUMNO
	//pagos_alumno.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// COBRO
	$id_alu_ram10 = $_GET['id_alu_ram'];

	$fec_pag = date('Y-m-d');

	$mon_ori_pag = $_POST['mon_ori_pag'];

	$mon_pag = $mon_ori_pag;

	$con_pag = $_POST['con_pag'];

	$est_pag = 'Pendiente';

	$res_pag = $nomResponsable;

	$ini_pag = $_POST['ini_pag'];

	$fin_pag = $_POST['fin_pag'];

	$pro_pag = $_POST['pro_pag'];

	$pri_pag = 1;

	$tip_pag = $_POST['tip_pag'];

	$tip1_pag = $_POST['tip1_pag'];

	$tip2_pag = $_POST['tip2_pag'];

	$car_pag = $_POST['car_pag'];

	$des_pag = $_POST['des_pag'];

	$int_pag = '';
	if ( isset( $_POST['id_gen_pag'] ) ) {
		
		if ( $_POST['id_gen_pag'] == 'Nulo' ) {
		
			$sqlInsercionPago = "
				INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, int_pag, id_alu_ram10, tip_pag ) 
				VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$int_pag', '$id_alu_ram10', '$tip_pag' )
			";
		
		} else {

			$id_gen_pag = $_POST['id_gen_pag'];
			$tip_pag = $_POST['tip_gen_pag'];

			$sqlInsercionPago = "
				INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, int_pag, id_alu_ram10, tip_pag, id_gen_pag2 ) 
				VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$int_pag', '$id_alu_ram10', '$tip_pag', '$id_gen_pag' )
			";
		}
	} else {

		if( $tip_pag == 'Varios' ){
			// 01/11/24 — (not_pag - NOTIFICACIÓN DE PAGO)ESTATUS DE RESPUESTA DE PRESIDENCIA, SI ES DE TIPO: ‘Varios’ SE DEBE MANDAR “Pendiente”, Y LA CUENTA DE JASSO (POR AHORA) DEBE MANDAR ‘Aprobado’ o ‘Declinado’.

			$not_pag = 'Pendiente';
			$sqlInsercionPago = "
				INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, int_pag, id_alu_ram10, tip_pag, not_pag ) 
				VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$int_pag', '$id_alu_ram10', '$tip_pag', '$not_pag' )
			";

		} else {
			$sqlInsercionPago = "
				INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, int_pag, id_alu_ram10, tip_pag ) 
				VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$int_pag', '$id_alu_ram10', '$tip_pag' )
			";
		}
		

	}
	
	$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
	
	if ( !$resultadoInsercionPago ) {
	
		echo $sqlInsercionPago;
	
	}else {
		// OBTENCION DE id MAXIMO DE PAGO
		// PARA INSERCION DE FOLIO 
		$sqlMaximoPago = "
			SELECT MAX(id_pag) AS maximo
			FROM pago
			WHERE id_alu_ram10 = '$id_alu_ram10'
		";

		$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

		if ( !$resultadoMaximoPago ) {
			
			echo $sqlMaximoPago;
		
		}else {

			$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
			$maximoPago = $filaMaximoPago['maximo'];
			// SQL UPDATE PARA AGREGAR FOLIO

			$fol_pag = $folioPlantel."00".$maximoPago;

			$sqlUpdatePago = "
				UPDATE pago
				SET 
				fol_pag = '$fol_pag'
				WHERE
				id_pag = '$maximoPago'
			";

			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

			if ( !$resultadoUpdatePago ) {
				echo $sqlMaximoPago;
			}else{

				// LOG
				//$nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram10 );

				// el administrador juan zarate registro un cobro por concepto: colegiatura 2, por la cantidad de $1500, a Pedrito Sola. fecha...
				// $des_log =  obtenerDescripcionPagoAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $con_pag, $mon_pag, $nombreAlumno );


				// logServer ( 'Alta', $tipoUsuario, $id, 'Cobro', $des_log, $plantel );
				// FIN LOG

				// logServer ( 'Alta', $tipoUsuario, $id, 'Pago', $plantel );
				
				//calendario_pagos( $id_alu_ram10, $db );
				
				if( $tip_pag == 'Varios' ){
					$sqlAlumno = "SELECT * FROM vista_alumnos WHERE id_alu_ram = $id_alu_ram10";
					$datosAlumno = obtener_datos_consulta($db, $sqlAlumno)['datos'];

					// ADICION DE notificacion_pago
						$mot_not_pag = strtoupper(fechaFormateadaCompacta3(date('Y-m-d')).' '.date('h:i').' - El usuario tipo '.$tipo.', '.$nombre.' de '.$nombrePlantel.' solicita realizar el pago de '.$con_pag.' por un importe de '.formatearDinero($mon_pag).'. Para el alumno, '.$datosAlumno['nom_alu'].', grupo '.$datosAlumno['nom_gen'].' del programa académico:'.$datosAlumno['nom_ram'].' con CDE: '.$datosAlumno['nom_pla']);
						$id_pag11 = $maximoPago;
					

						$sqlNotificacion = "
							INSERT INTO notificacion_pago ( mot_not_pag, id_pag11, est_not_pag )
							VALUES ( '$mot_not_pag', $id_pag11, 'Pendiente2' )
						";

						$resultadoNotificacion = mysqli_query( $db, $sqlNotificacion );
						if(!$resultadoNotificacion){
							echo $sqlNotificacion;
						}
					// F ADICION DE notificacion_pago
				}
				echo $id_alu_ram10;
			}
		}

	}
		
	
?>