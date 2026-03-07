<?php 
	//ARCHIVO VIA AJAX PARA EDITAR ALUMNO
	//index.php
	require('../inc/cabeceras.php');

	
	$cor1_alu = $_POST['cor1_alu'];

	$sql = "
		UPDATE alumno SET cor1_alu = '$cor1_alu' WHERE id_alu = '$id';
	";

	$resultado = mysqli_query( $db, $sql );

	if ( $resultado ) {

		echo "Exito";
	
	}else{
	
		echo "Error, verificar consulta";
		//echo $sql;
	}
?>