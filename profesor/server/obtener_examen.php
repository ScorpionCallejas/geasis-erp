<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// echo 'echp';
	$id_exa_cop = $_POST['id_exa_cop'];

	echo obtenerDatosActividadGrupoServer( $id_exa_cop, 'Examen', 'json' );

?>