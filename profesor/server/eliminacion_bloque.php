<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR BLOQUES
	//bloques.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_blo = $_POST['id_blo'];

	// LOG
	$filaDatos = obtenerDatosBloqueServer( $id_blo );
    $nombreRama = $filaDatos['nom_ram'];
    $nombreBloque = $filaDatos['nom_blo'];

    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'bloque', $nombreBloque, $nombreRama );
   
    logServer ( 'Baja', $tipoUsuario, $id, 'Bloque', $des_log, $plantel );
    // FIN LOG


	$sql = "DELETE FROM bloque WHERE id_blo = '$id_blo'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {



		

		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>