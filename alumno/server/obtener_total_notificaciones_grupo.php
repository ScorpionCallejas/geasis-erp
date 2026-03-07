<?php
	//ARCHIVO VIA AJAX PARA ACTUALIZAR TOTAL NOTIFICACIONES GRUPAL
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];
	$id_alu_ram = $_POST['id_alu_ram'];

	echo obtenerTotalNotificacionesGrupoServer( $id_alu_ram, $id_sub_hor );
?>