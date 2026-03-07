<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO BLOQUE
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_wik = $_POST['tituloWiki'];
	$des_wik = $_POST['contenidoWiki'];
	$id_blo = $_GET['id_blo'];

	$fec_wik = date( 'Y-m-d H:i:s' );
	$tip_wik = 'Wiki';

	$sql = "INSERT INTO wiki (nom_wik, des_wik, fec_wik, tip_wik, id_blo2) VALUES ('$nom_wik', '$des_wik', '$fec_wik', '$tip_wik', '$id_blo')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		// LOG
		$filaDatos = obtenerDatosBloqueServer( $id_blo );
        $nombreRama = $filaDatos['nom_ram'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'wiki', $nom_wik, $nombreRama );
       
        logServer ( 'Alta', $tipoUsuario, $id, 'Wiki', $des_log, $plantel );
        // FIN LOG

		echo "Exito";
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>