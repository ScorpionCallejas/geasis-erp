<?php  
	//ARCHIVO VIA AJAX PARA OTENER DATOS DE PREGUNTA
	//clase_contenido.php
	require('../inc/cabeceras.php');

	$id_pre = $_POST['id_pre'];

	$sql = "SELECT * FROM pregunta WHERE id_pre = '$id_pre'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		$datos = mysqli_fetch_assoc($resultado);

		echo json_encode($datos);
	}else{
		echo "error, verificar en consulta";
		//echo $sql;
	}


?>