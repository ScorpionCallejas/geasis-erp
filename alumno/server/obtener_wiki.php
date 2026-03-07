<?php  
	//ARCHIVO VIA AJAX PARA OBTENER WIKI
	//bloque_contenido.php
	require('../inc/cabeceras.php');


	$id_wik = $_POST['edicionWiki'];

	$sql = "SELECT * FROM wiki WHERE id_wik = '$id_wik'";
	$resultado = mysqli_query($db, $sql);

	$datos = mysqli_fetch_assoc($resultado);

	echo json_encode($datos);



?>