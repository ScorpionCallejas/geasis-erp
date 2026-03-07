<?php  
	//ARCHIVO VIA AJAX PARA OTENER TODOS LOS EVENTOS ASOCIADOS AL CICLO
	//eventos.php/calendario.php
	require('../inc/cabeceras.php');

	header('Content-type: application/json');


	$id_cic = $_GET['id_cic'];

	$sql = "SELECT * FROM evento WHERE id_cic2 = '$id_cic'";
	$resultado = mysqli_query($db, $sql);


	$arreglo = array();


	while ($fila = mysqli_fetch_assoc($resultado)) {
		array_push($arreglo, [
		  'id'   => $fila['id_eve'],
	      'title'   => $fila['nom_eve'],
	      'descripcion'   => $fila['des_eve'],
	      'start'   => $fila['fec_eve'],
	      'color'   => $fila['col_eve'],
	      
	    ]);
	}

	echo json_encode($arreglo);

	//echo '[{"id":"45","title":null, "start":"2019-05-03 00:00:00","color":"#f44336"}]';


?>