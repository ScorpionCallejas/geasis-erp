<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR DOCUMENTOS
	//editor.php
	require('../inc/cabeceras.php');


	$id_doc = $_POST['documento'];


	$sql = "DELETE FROM documento WHERE id_doc = '$id_doc'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>