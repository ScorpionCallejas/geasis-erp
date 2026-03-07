<?php 
	//ARCHIVO AJAX PARA SUBIR IMAGEN DE ANUNCIO
	//alumno/index.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	$imagen = $_POST['aviso'];
	$descripcion = $_POST['descripcion'];
	$plantel = $_POST['plantel'];
	//$usr = $_POST['usr'];
	if ( isset( $_POST['url'] ) ) {
		$liga = $_POST['url'];
	}else{
		$liga = null;
	}
	$imagen2 = $_FILES['imagen']['size'];
	$nombre_imagen =  $_FILES['imagen']['name'];
	$tipo_imagen = explode('.', $nombre_imagen);
	$tipo_imagen = strtolower(end($tipo_imagen));
	$peso = $_FILES['imagen']['size'];
	$imagen_server_aux = fopen($_FILES['imagen']['tmp_name'], 'r');
	$imagen_server = fread($imagen_server_aux, $imagen2);
	$imagen_server = mysqli_escape_string($db, $imagen_server);
	//echo $nombre_imagen;
	$comprobar_aviso_previo = "SELECT responsable FROM aviso WHERE plantel = '$plantel' AND responsable = '$tipo'";
	$try_aviso_previo = mysqli_query($db, $comprobar_aviso_previo);
	$get_aviso = mysqli_fetch_assoc($try_aviso_previo);
	$response = $get_aviso['responsable'];
	if ($get_aviso != null) {
		//echo "Existe reposnable";
		if ($tipo =='Adminge') {
			$update_aviso = "UPDATE aviso SET imagen = '$imagen_server', tipo_imagen = '$tipo_imagen', mensaje = '$descripcion',link = '$liga' WHERE plantel = '$plantel' AND responsable = '$tipo'";
		}
		else{
			$update_aviso = "UPDATE aviso SET imagen = '$imagen_server', tipo_imagen = '$tipo_imagen', mensaje = '$descripcion' WHERE plantel = '$plantel' AND responsable = 'Admin'";	
		}


	}
	else
	{
		if ($tipo == 'Adminge') {
			$update_aviso = "INSERT INTO aviso (responsable, mensaje,imagen,tipo_imagen,plantel,link ) VALUES ('$tipo','$descripcion','$imagen_server','$tipo_imagen','$plantel','$liga')";
		}
		else{
			$update_aviso = "INSERT INTO aviso (responsable, mensaje,imagen,tipo_imagen,plantel) VALUES ('Admin','$descripcion','$imagen_server','$tipo_imagen','$plantel')";
		}
		//echo "No hay aviso previo";
		
	}
	//echo $update_aviso;
	$try_upload = mysqli_query($db, $update_aviso);
	if (!$try_upload) {
		echo "Ocurrió un error; inténtelo nuevamente ó notifique el problema.";
	}
	else{
		echo "Done";	
	}
	
	
?>