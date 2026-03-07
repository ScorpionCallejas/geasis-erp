<?php  
	//ARCHIVO VIA AJAX PARA OTENER DATOS DEL CONCEPTO AL CARGAR LA MODAL DE EDICION
	//pagos_alumno.php
	require('../inc/cabeceras.php');


	$id_pag = $_POST['id_pag'];

	$sql = "SELECT * FROM pago WHERE id_pag = '$id_pag'";
	$resultado = mysqli_query($db, $sql);

	$datos = mysqli_fetch_assoc($resultado);

	echo json_encode($datos);


?>