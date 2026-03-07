<?php 
	//ARCHIVO VIA AJAX PARA EDITAR DOCUMENTO
	//editor.php
	require('../inc/cabeceras.php');

	$nom_doc = $_POST['tituloDocumento'];
	$des_doc = $_POST['contenidoDocumento'];
	$id_doc = $_GET['id_doc'];


	$sql = "
		UPDATE documento SET nom_doc = '$nom_doc', des_doc = '$des_doc' WHERE id_doc = '$id_doc'
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		echo "Exito";
	}else{

		echo "Error, verificar consulta";
		//echo $sql;
	}
?>