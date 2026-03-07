<?php 
	//ARCHIVO VIA AJAX PARA EDITAR BLOQUE
	//materias.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$tipo_actividad = $_POST['tipo_actividad'];
	$id_actividad = $_POST['id_actividad'];
	$inicio_actividad = $_POST['inicio_actividad'];
	$fin_actividad = $_POST['fin_actividad'];


	if ( $tipo_actividad == 'Foro' ) {
	
		$sql = "
			UPDATE foro_copia
			SET
			ini_for_cop = '$inicio_actividad',
			fin_for_cop = '$fin_actividad' 
			WHERE
			id_for_cop = '$id_actividad'
		";


		$sqlAlumnos = "
			UPDATE cal_act
			SET 
			ini_cal_act = '$inicio_actividad',
			fin_cal_act = '$fin_actividad'
			WHERE
			id_for_cop2 = '$id_actividad'
		";
	
	} else if ( $tipo_actividad == 'Entregable' ) {
		
		$sql = "
			UPDATE entregable_copia
			SET
			ini_ent_cop = '$inicio_actividad',
			fin_ent_cop = '$fin_actividad' 
			WHERE
			id_ent_cop = '$id_actividad'
		";


		$sqlAlumnos = "
			UPDATE cal_act
			SET 
			ini_cal_act = '$inicio_actividad',
			fin_cal_act = '$fin_actividad'
			WHERE
			id_ent_cop2 = '$id_actividad'
		";

	} else if ( $tipo_actividad == 'Examen' ) {
		
		$sql = "
			UPDATE examen_copia
			SET
			ini_exa_cop = '$inicio_actividad',
			fin_exa_cop = '$fin_actividad' 
			WHERE
			id_exa_cop = '$id_actividad'
		";


		$sqlAlumnos = "
			UPDATE cal_act
			SET 
			ini_cal_act = '$inicio_actividad',
			fin_cal_act = '$fin_actividad'
			WHERE
			id_exa_cop2 = '$id_actividad'
		";
		
	}

	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {

		$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

		if ( !$resultadoAlumnos ) {
			echo $sqlAlumnos;
		} else {
			echo "Exito";
		}
		
	
	} else {

		echo "Error, verificar consulta";
		echo $sql;
	}
?>