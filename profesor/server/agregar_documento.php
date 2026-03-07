<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO BLOQUE
	//editor.php
	require('../inc/cabeceras.php');

	$nom_doc = $_POST['tituloDocumento'];
	$des_doc = $_POST['contenidoDocumento'];
	$fec_doc = date('Y-m-d');

	if ($nom_doc == "") {
		$nom_doc = "documento sin nombre";
	}


	$sql = "INSERT INTO documento (nom_doc, des_doc, fec_doc, id_pro3) VALUES ('$nom_doc', '$des_doc', '$fec_doc', '$id')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		echo "Exito";
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>