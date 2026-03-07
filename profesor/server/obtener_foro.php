<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE FORO
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_for_cop = $_POST['id_for_cop'];

	echo obtenerDatosActividadGrupoServer( $id_for_cop, 'Foro', 'json' );

?>