<?php 
	//ARCHIVO VIA AJAX PARA EDITAR DOCUMENTO
	//editor.php
	require('../inc/cabeceras.php');

	$sql = "
		UPDATE alumno 
		SET 
		presentacion = CURDATE() 
		WHERE id_alu = '$id'
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
	
		echo "Exito";
	
	}else{

		echo "Error, verificar consulta";
		//echo $sql;
	}
?>