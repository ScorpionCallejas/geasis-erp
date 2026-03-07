<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR CONDONACION DE PAGO
	//cobranza_alumno.php//server/obtener_pagos_alumno.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$id_pag = $_POST['id_pag2'];
	$mon_pag = $_POST['mon_pag'];
	
	// DATETIME PARA FILTRADO POR ANTIGUEDAD EN UNION DE obtener_notificaciones.php
	$fec_con_pag = date('Y-m-d H:i:s');

	$tip1_con_pag = $_POST['tip1_con_pag'];

	$tip_con_pag = 'Condonación';

	if ( isset($_POST['motivoCondonacion']) ) {

		if ( $_POST['motivoCondonacion'] == 'otros' ) {
			$mot_con_pag = $_POST['motivoCondonacionOtros'];
		}else{
			$mot_con_pag = $_POST['motivoCondonacion'];
		}
	}

	$mon_con_pag = $_POST['cantidadCondonacion'];


	if ( $tip1_con_pag == 'Porcentual' ) {
		// VARIABLES RELEVANTES
		$montoDescuento = ( $mon_con_pag / 100 ) * $mon_pag;
		$montoFinal = $mon_pag - $montoDescuento;

		$can_con_pag = $montoDescuento;

	}else if ( $tip1_con_pag == 'Monetario' ) {
		// VARIABLES RELEVANTES
		$montoDescuento = $mon_con_pag ;
		$montoFinal = $mon_pag - $montoDescuento;

		$can_con_pag = $mon_con_pag;
	}
	
	$est_con_pag = 'Pendiente';

	$res_con_pag = $nomResponsable;

	

	$sql = "

		INSERT INTO condonacion_pago ( fec_con_pag, tip_con_pag, tip1_con_pag, mot_con_pag, mon_con_pag, est_con_pag, res_con_pag, can_con_pag, id_pag2 )
		VALUES ( '$fec_con_pag', '$tip_con_pag', '$tip1_con_pag', '$mot_con_pag', '$mon_con_pag', '$est_con_pag', '$res_con_pag', '$can_con_pag', '$id_pag' )
	";


	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {

		if ( $tip1_con_pag == 'Porcentual' ) {
			// VARIABLES RELEVANTES


			$con_his_pag = "Solicitud de condonación porcentual del ".$mon_con_pag." %; descontando $".$montoDescuento."; al saldo de $".$mon_pag."; restando un total de $".$montoFinal;

			$men_his_pag = "Solicitud de condonación porcentual del ".$mon_con_pag." %; descontando $".$montoDescuento."; al saldo de $".$mon_pag."; restando un total de $".$montoFinal;

		}else if ( $tip1_con_pag == 'Monetario' ) {
			// VARIABLES RELEVANTES


			$con_his_pag = "Solicitud de condonación monetaria por $".$mon_con_pag."; con un saldo de $".$mon_pag."; restando un total de $".$montoFinal;

			$men_his_pag = "Solicitud de condonación monetaria por $".$mon_con_pag."; con un saldo de $".$mon_pag."; restando un total de $".$montoFinal;
		}


		$fec_his_pag = $fec_con_pag;

		$res_his_pag = $nomResponsable;

		$est_his_pag = 'Pendiente';

		$tip_his_pag = "Condonación";

		$med_his_pag = "Sistema";

		$id_pag4 = $id_pag;


		// INSERCION HISTORIAL
		$sqlInsercionHistorial = "
			INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
			VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
		";



		$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

		if ( !$resultadoInsercionHistorial ) {
			echo $sqlInsercionHistorial;
		}else{

			$sqlMaximo = "

			";

			$sqlAlumno = "
				SELECT *
				FROM pago
				INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
				INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
				WHERE id_pag = '$id_pag'
			";

			$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

			$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

			$nombreAlumno = $filaAlumno['nom_alu'].' '.$filaAlumno['app_alu'].' '.$filaAlumno['apm_alu'];
						
			// LOG 		
			// El Administrador: Juan Zarate, registro una condonacion ( $con_his_pag ). Para $nombreAlumno 
	        // $des_log =  obtenerDescripcionPeticionPagoLogServer( $tipoUsuario, $nomResponsable, 'registró', 'condonación', $con_his_pag, $nombreAlumno );
	       
	        // logServer ( 'Alta', $tipoUsuario, $id, 'Condonación', $des_log, $plantel );
	        // FIN LOG

	        // APROBACION DE LA PETICION
	        $identificador_peticion = obtenerUltimoIdentificadorServer( 'condonacion_pago', 'id_con_pag' );
			$tipo_peticion = "Condonación";
			$respuesta_peticion = "Aprobado";
			$motivo_peticion = 'N/A';
			
			procesarPeticionServer( $identificador_peticion, $tipo_peticion, $respuesta_peticion, $nomResponsable, $motivo_peticion );
	        // FIN DE APROBACION DE LA PETICION
			
		}

	}else{

		echo "Error, verificar consulta";
		// echo $sql;
	}


?>