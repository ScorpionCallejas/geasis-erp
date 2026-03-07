<?php 
	//ARCHIVO VIA AJAX PARA EDITAR CALIFICACIONES DE TABLA calificacion
	//alumnos.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// TABLA CALIFICACION 
	$id_cal = $_POST['id_cal'];
	$fin_cal = $_POST['fin_cal'];
	
	$sql = "
		UPDATE calificacion 
		SET 
		fin_cal = '$fin_cal'
		WHERE 
		id_cal = '$id_cal'
	";

	$resultado = mysqli_query( $db, $sql );

	if ( !$resultado ) {
		echo $sql;
	}
			


?>