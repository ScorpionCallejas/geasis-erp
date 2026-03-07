<?php  
	// CONTROLADOR DE TEMPLATE PARA CTA DE USUARIO
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');
	$swi_eje = $_POST['swi_eje'];
	
	$sql = "
		UPDATE ejecutivo 
		SET swi_eje = '$swi_eje'
		WHERE id_eje = '$id';
	";

	$resultado = mysqli_query( $db, $sql );

	if ( !$resultado ) {
		echo json_encode(['error' => $sql]);
	} else {
		echo json_encode(['success' => 200]);
	}
	
?>