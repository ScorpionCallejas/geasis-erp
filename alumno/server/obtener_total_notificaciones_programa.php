<?php
	//ARCHIVO VIA AJAX PARA ACTUALIZAR TOTAL NOTIFICACIONES GRUPAL
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$id_alu_ram = $_POST['id_alu_ram'];

	echo obtenerTotalNotificacionesProgramaServer( $id_alu_ram );
?>