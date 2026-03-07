<?php 
	//ARCHIVO VIA AJAX PARA EDITAR BLOQUE
	//materias.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$tipo = $_POST['tipo'];
	$id_cal_act = $_POST['id_cal_act'];
	

	if ( $tipo == 'Inicio' ) {
		
		$fecha = $_POST['fecha'];
		$sql = "
			UPDATE cal_act
			SET
			ini_cal_act = '$fecha' 
			WHERE
			id_cal_act = '$id_cal_act'
		";	
	
	} else if ( $tipo == 'Fin' ) {
		$fecha = $_POST['fecha'];
		$sql = "
			UPDATE cal_act
			SET
			fin_cal_act = '$fecha' 
			WHERE
			id_cal_act = '$id_cal_act'
		";

	} else if ( $tipo == 'Retroalimentacion' ){
		$retroalimentacion = $_POST['retroalimentacion'];
		
		$sql = "
			UPDATE cal_act
			SET
			ret_cal_act = '$retroalimentacion' 
			WHERE
			id_cal_act = '$id_cal_act'
		";
	} else if ( $tipo == 'Puntos' ){
		$puntos = $_POST['puntos'];
		
		$sql = "
			UPDATE cal_act
			SET
			pun_cal_act = '$puntos' 
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