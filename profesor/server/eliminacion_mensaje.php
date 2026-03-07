<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR SALAS Y MENSAJES ASOCIADAS A SALA
	//mensajes.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_men = $_POST['id_men'];

	$sqlArchivo = "
		SELECT * FROM mensaje WHERE id_men = '$id_men'
	";

	$datos = obtener_datos_consulta( $db, $sqlArchivo );

	if ( $datos['datos']['arc_men'] != NULL ) {
		// ELIMINACION ARCHIVO

		unlink("../../archivos/".$datos['datos']['arc_men']);

		// FIN ELIMINACION ARCHIVO
	}


	$sql = "
		DELETE FROM mensaje WHERE id_men = '$id_men'
	";


	// -- $sql = "DELETE FROM sala WHERE id_men = '$id_men'";
	$resultado = mysqli_query( $db, $sql );

	if ( $resultado ) {
			echo "true";
	
	} else {
	
		echo "error, verificar consulta";
		//echo $sql;
	}


?>