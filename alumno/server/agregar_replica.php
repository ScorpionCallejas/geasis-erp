<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVA REPLICA
	//foro.php
	require('../inc/cabeceras.php');

	$rep_rep = $_POST['rep_rep'];
	$id_alu_ram7 = $_GET['id_alu_ram'];
	$id_com1 = $_POST['id_com'];
	$id_for_cop = $_GET['id_for_cop'];
	$fec_rep = date('Y-m-d H:i:s');


	$sql = "INSERT INTO replica (rep_rep, id_alu_ram7, id_com1, fec_rep) VALUES ('$rep_rep', '$id_alu_ram7', '$id_com1', '$fec_rep')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {


		echo "Exito";

		
	}else{
		// echo "error, verificar consulta!";
		echo $sql;
	}
		
	
?>