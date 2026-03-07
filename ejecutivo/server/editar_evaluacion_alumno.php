<?php 
	//ARCHIVO VIA AJAX PARA EDITAR CALIFICACIONES DE TABLA calificacion
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_GET['id_alu_ram'];

	// TABLA CALIFICACION 
	$id_cal = $_POST['id_cal'];
	$fin_cal = $_POST['fin_cal'];
	$ext_cal = $_POST['ext_cal'];
	$fechaHoy = date( 'Y-m-d H:i:s' );

	for ($i=0; $i < sizeof($id_cal); $i++) {

		$datosAlumno = obtenerDatosAlumnoCalificacionServer( $id_cal[$i] );

		if ($fin_cal[$i] != "" && $ext_cal[$i] == "") {
			$sqlCalificacion = "
				UPDATE calificacion SET fin_cal = '$fin_cal[$i]'  WHERE id_alu_ram2 = '$id_alu_ram' AND id_cal = '$id_cal[$i]';
			";

			$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);


			$sqlInsercionKardex = "
				INSERT INTO  kardex  ( fec_kar ,  cal_kar ,  tip_kar , mod_kar,  res_kar ,  id_cal1 ) 
				VALUES ( '$fechaHoy', '$fin_cal[$i]', '$tipoUsuario',  'Ordinario',  '$nomResponsable', '$id_cal[$i]')

			";

			$resultadoInsercionKardex = mysqli_query( $db, $sqlInsercionKardex );


			
			// LOG

			// el administrador juan zarate edito calificacion (matematicas con 9 ) de pedrito de lic en mercadotecnia
	        $des_log =  obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $nomResponsable, 'editó', $datosAlumno['nom_mat'], $fin_cal[$i], $datosAlumno['nom_alu'].' '.$datosAlumno['app_alu'].' '.$datosAlumno['apm_alu'], $datosAlumno['nom_ram'] );
	       
	        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
	        // FIN LOG

		
			
		}else if($ext_cal[$i] != "" && $fin_cal[$i] == ""){
			$sqlCalificacion = "
				UPDATE calificacion SET ext_cal = '$ext_cal[$i]'  WHERE id_alu_ram2 = '$id_alu_ram' AND id_cal = '$id_cal[$i]';
			";

			$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);


			$sqlInsercionKardex = "
				INSERT INTO  kardex  ( fec_kar ,  cal_kar ,  tip_kar , mod_kar,  res_kar ,  id_cal1 ) 
				VALUES ( '$fechaHoy', '$ext_cal[$i]', '$tipoUsuario',  'Extraordinario',  '$nomResponsable', '$id_cal[$i]')

			";

			$resultadoInsercionKardex = mysqli_query( $db, $sqlInsercionKardex );


			// LOG

			// el administrador juan zarate edito calificacion (matematicas con 9 ) de pedrito de lic en mercadotecnia
	        $des_log =  obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $nomResponsable, 'editó', $datosAlumno['nom_mat'], $ext_cal[$i], $datosAlumno['nom_alu'].' '.$datosAlumno['app_alu'].' '.$datosAlumno['apm_alu'], $datosAlumno['nom_ram'] );
	       
	        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
	        // FIN LOG


		}else if($ext_cal[$i] != "" && $fin_cal[$i] != ""){
			$sqlCalificacion = "
				UPDATE calificacion SET ext_cal = '$ext_cal[$i]', fin_cal = '$fin_cal[$i]'  WHERE id_alu_ram2 = '$id_alu_ram' AND id_cal = '$id_cal[$i]';
			";

			$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);


			$sqlInsercionKardex = "
				INSERT INTO  kardex  ( fec_kar ,  cal_kar ,  tip_kar , mod_kar,  res_kar ,  id_cal1 ) 
				VALUES ( '$fechaHoy', '$ext_cal[$i]', '$tipoUsuario',  'Ordinario',  '$nomResponsable', '$id_cal[$i]')

			";

			$resultadoInsercionKardex = mysqli_query( $db, $sqlInsercionKardex );


			if ( $fin_cal[$i] > $ext_cal[$i] ) {
				// LOG

				// el administrador juan zarate edito calificacion (matematicas con 9 ) de pedrito de lic en mercadotecnia
		        $des_log =  obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $nomResponsable, 'editó', $datosAlumno['nom_mat'], $fin_cal[$i], $datosAlumno['nom_alu'].' '.$datosAlumno['app_alu'].' '.$datosAlumno['apm_alu'], $datosAlumno['nom_ram'] );
		       
		        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
		        // FIN LOG
			} else {

				// LOG

				// el administrador juan zarate edito calificacion (matematicas con 9 ) de pedrito de lic en mercadotecnia
		        $des_log =  obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $nomResponsable, 'editó', $datosAlumno['nom_mat'], $ext_cal[$i], $datosAlumno['nom_alu'].' '.$datosAlumno['app_alu'].' '.$datosAlumno['apm_alu'], $datosAlumno['nom_ram'] );
		       
		        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
		        // FIN LOG
			}

		}
	}
	

	if (isset($resultadoCalificacion)) {

		echo "Exito";
	}


?>