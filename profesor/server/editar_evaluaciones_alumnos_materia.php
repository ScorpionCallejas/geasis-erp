<?php 
	//ARCHIVO VIA AJAX PARA EDITAR CALIFICACIONES DE TABLA calificacion
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// ARREGLOS DE CALIFICACIONES


	// TABLA CALIFICACION 

	$id_cal = $_POST['id_cal'];
	$fin_cal = $_POST['fin_cal'];
	$ext_cal = $_POST['ext_cal'];

	//echo sizeof($id_alu_ram);


	//PARCIALES
	if(isset($_POST['id_par'])){
		$id_par = $_POST['id_par'];
		$cal_par = $_POST['cal_par'];	

		for ($i=0; $i < sizeof($id_par); $i++) {

			if ($cal_par[$i] != "") {
				// CONDICIONANTE QUE CONTRINUYE A HACER LAS INSERCIONES EN INDICES DONDE HAY DATOS
				//DE LO CONTRARIO INSERTA CEROS, ESO FASTIDIARIA AL AUMNO
				$sql = "
					UPDATE parcial SET cal_par = '$cal_par[$i]' WHERE id_par = '$id_par[$i]';
				";


				$resultado = mysqli_query($db, $sql);
			}
		}
	}

	// var_dump( $id_cal );
	// var_dump( $fin_cal );

	for ($i=0; $i < sizeof($id_cal); $i++) {
		$datosAlumno = obtenerDatosAlumnoCalificacionServer( $id_cal[$i] );



		if ($fin_cal[$i] != "" && $ext_cal[$i] == "") {
			$sqlCalificacion = "
				UPDATE calificacion SET fin_cal = '$fin_cal[$i]'  WHERE id_cal = '$id_cal[$i]';
			";

			$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);

			// LOG

			// el administrador juan zarate edito calificacion (matematicas con 9 ) de pedrito de lic en mercadotecnia
	        $des_log =  obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $nomResponsable, 'editó', $datosAlumno['nom_mat'], $fin_cal[$i], $datosAlumno['nom_alu'].' '.$datosAlumno['app_alu'].' '.$datosAlumno['apm_alu'], $datosAlumno['nom_ram'] );
	       
	        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
	        // FIN LOG
		
			
		}else if($ext_cal[$i] != "" && $fin_cal[$i] == ""){
			$sqlCalificacion = "
				UPDATE calificacion SET ext_cal = '$ext_cal[$i]'  WHERE id_cal = '$id_cal[$i]';
			";

			$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);

			// LOG

			// el administrador juan zarate edito calificacion (matematicas con 9 ) de pedrito de lic en mercadotecnia
	        $des_log =  obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $nomResponsable, 'editó', $datosAlumno['nom_mat'], $ext_cal[$i], $datosAlumno['nom_alu'].' '.$datosAlumno['app_alu'].' '.$datosAlumno['apm_alu'], $datosAlumno['nom_ram'] );
	       
	        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
	        // FIN LOG


		}else if($ext_cal[$i] != "" && $fin_cal[$i] != ""){
			$sqlCalificacion = "
				UPDATE calificacion SET ext_cal = '$ext_cal[$i]', fin_cal = '$fin_cal[$i]'  WHERE id_cal = '$id_cal[$i]';
			";
			$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);


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
	
	

	if (isset($resultado) || isset($resultadoCalificacion)) {
		echo "Exito";
	}


?>