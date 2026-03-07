<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE ENTREGABLES
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_ent_cop = $_POST['id_ent_cop'];

	echo obtenerDatosActividadGrupoServer( $id_ent_cop, 'Entregable', 'json' );

?>