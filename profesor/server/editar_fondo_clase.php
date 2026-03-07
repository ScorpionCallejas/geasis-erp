<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO BLOQUE
	//bloques.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$img_blo = $_POST['img_blo'];
	$id_blo = $_POST['id_blo'];


	$sql = "
		UPDATE bloque
		SET
		img_blo = '$img_blo'
		WHERE
		id_blo = '$id_blo'	
	";

	$resultado = mysqli_query( $db, $sql );

	if ($resultado) {
		
		// LOG
		$filaDatos = obtenerDatosBloqueServer( $id_blo );
        $nombreRama = $filaDatos['nom_ram'];
        $nom_blo = $filaDatos['nom_blo'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó imagen', 'bloque', $nom_blo, $nombreRama );
       
        logServer( 'Cambio', $tipoUsuario, $id, 'Bloque', $des_log, $plantel );
        // FIN LOG

		echo 'true';

	}else{
		echo "false";
		//echo $sql;
	}
		
	
?>