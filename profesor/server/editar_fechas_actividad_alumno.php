<?php 
	//ARCHIVO VIA AJAX PARA EDITAR BLOQUE
	//materias.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$tipo = $_POST['tipo'];
	$id_cal_act = $_POST['id_cal_act'];
	$fecha = $_POST['fecha'];


	if ( $tipo == 'Inicio' ) {
	
		$sql = "
			UPDATE cal_act
			SET
			ini_cal_act = '$fecha' 
			WHERE
			id_cal_act = '$id_cal_act'
		";	
	
	} else if ( $tipo == 'Fin' ) {
		
		$sql = "
			UPDATE cal_act
			SET
			fin_cal_act = '$fecha' 
			WHERE
			id_cal_act = '$id_cal_act'
		";

	}

	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {

		

		echo "Exito";
	
	} else {

		echo "Error, verificar consulta";
		echo $sql;
	}
?>