<?php
	//ARCHIVO VIA AJAX PARA ACTUALIZAR TOTAL NOTIFICACIONES GRUPAL
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];

	echo obtenerTotalNotificacionesGrupoServer( $id, $id_sub_hor );
?>