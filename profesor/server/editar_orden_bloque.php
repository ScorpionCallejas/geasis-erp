<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO BLOQUE
	//bloques.php
	require('../inc/cabeceras.php');

	
	$id_blo = $_POST['id_blo'];

	if ( isset( $_POST['posicion'] ) ) {
		
		$ord_blo = $_POST['posicion'];
		$sql = "
			UPDATE bloque 
			SET
			ord_blo = '$ord_blo'
			WHERE
			id_blo = '$id_blo' 
		";	

	}
	

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		echo "Exito";

	}else{
		
		// echo "error, verificar consulta!";
		echo $sql;
	}
		
	
?>