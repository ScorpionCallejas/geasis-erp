<?php  
	//ARCHIVO VIA AJAX PARA CARGAR DATOS EN MODAL DE DESTINO DE MSJ
	//buscador_resultado.php
	require('../inc/cabeceras.php');


	$usuario = $_POST['usuario'];
	$tipoUsuario = $_POST['tipoUsuario'];

	// echo $usuario;
	// echo $tipoUsuario;

	switch ($tipoUsuario) {
		case 'Ejecutivo':
		  $sql = "SELECT nom_eje as nombre, fot_eje as foto, id_eje as destino, tip_eje as tipoDestino FROM ejecutivo WHERE id_eje = '$usuario'";
		  $resultado = mysqli_query($db, $sql);
		  
		  $datosDestino = mysqli_fetch_assoc($resultado);

		  echo json_encode($datosDestino);

		  break;

		case 'Admin':
		  $sql = "SELECT nom_adm as nombre, fot_adm as foto, id_adm as destino, tip_adm as tipoDestino FROM admin WHERE id_adm = '$usuario' ";
		  $resultado = mysqli_query($db, $sql);

		  $datosDestino = mysqli_fetch_assoc($resultado);

		  echo json_encode($datosDestino);

		  break;

		case 'Adminge':
		  $sql = "SELECT nom_adg as nombre, fot_adg as foto, id_adg as destino, tip_adg as tipoDestino FROM adminge WHERE id_adg = '$usuario' ";
		  $resultado = mysqli_query($db, $sql);

		  $datosDestino = mysqli_fetch_assoc($resultado);

		  echo json_encode($datosDestino);

		  break;

		case 'Adminco':
		  $sql = "SELECT nom_adc as nombre, fot_adc as foto, id_adc as destino, tip_adc as tipoDestino FROM adminco WHERE id_adc = '$usuario' ";
		  $resultado = mysqli_query($db, $sql);

		  $datosDestino = mysqli_fetch_assoc($resultado);

		  echo json_encode($datosDestino);

		  break;

		case 'Profesor':
		  $sql = "SELECT nom_pro as nombre, fot_pro as foto, id_pro as destino, tip_pro as tipoDestino FROM profesor WHERE id_pro = '$usuario' ";
		  $resultado = mysqli_query($db, $sql);

		  $datosDestino = mysqli_fetch_assoc($resultado);

		  echo json_encode($datosDestino);

		  break;

		case 'Alumno':
		  $sql = "SELECT nom_alu as nombre, fot_alu as foto, id_alu as destino, tip_alu as tipoDestino FROM alumno WHERE id_alu = '$usuario' ";
		  $resultado = mysqli_query($db, $sql);

		  $datosDestino = mysqli_fetch_assoc($resultado);

		  echo json_encode($datosDestino);

		  break;
	}


?>