<?php 
	//ARCHIVO AJAX PARA SUBIR IMAGEN DE ANUNCIO
	//alumno/index.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$descripcion = $_POST['descripcion'];
	$plantel = $_POST['plantel'];
	$usr = $_POST['usr'];
	$area = $_POST['link'];
	$extenciones_validas = array("jpg","jpeg","png");
	//var_dump($_POST);
	if (array_key_exists('imagen', $_FILES)) {
    	//echo "Si hay imagen";
    	$imagen2 = $_FILES['imagen']['size'];
		$nombre_imagen =  $_FILES['imagen']['name'];
		$tipo_imagen = explode('.', $nombre_imagen);
		$tipo_imagen = strtolower(end($tipo_imagen));
		$peso = $_FILES['imagen']['size'];
		$imagen_server_aux = fopen($_FILES['imagen']['tmp_name'], 'r');
		$imagen_server = fread($imagen_server_aux, $imagen2);
		$imagen_server = mysqli_escape_string($db, $imagen_server);
		if (in_array($tipo_imagen, $extenciones_validas)) {
			$update_aviso = "INSERT INTO `ticket` (`id`, `area`, `imagen`, `detalle`, `alumno`, `programa`, `grupo`, `plantel`, `folio`, `estatus`)  select NULL, '$area', '$imagen_server', '$descripcion', '$usr', '$rama', '999', '$plantel', concat('UAENDE',MAX(id)+1), 'Pendiente' FROM ticket";
		}
		else{
			return "imagen no valida";
		}
	}
	else{
		$update_aviso = "INSERT INTO `ticket` (`id`, `area`,  `detalle`, `alumno`, `programa`, `grupo`, `plantel`, `folio`, `estatus`)  select NULL, '$area', '$descripcion', '$usr', '$rama', '999', '$plantel', concat('UAENDE',MAX(id)+1), 'Pendiente' FROM ticket";
	}
	
	
	//echo $update_aviso;
	$try_upload = mysqli_query($db, $update_aviso);
	if (!$try_upload) {
		//echo "Ocurrió un error; inténtelo nuevamente ó notifique el problema.";
		echo $update_aviso;
	}
	else{
		echo "Done";	
	}
	
	
?>