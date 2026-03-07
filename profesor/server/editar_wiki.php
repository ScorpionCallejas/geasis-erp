<?php 
	//ARCHIVO VIA AJAX PARA EDITAR WIKI
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_wik = $_POST['wikiTituloEdicion'];
	$des_wik = $_POST['wikiContenidoEdicion'];
	$id_wik = $_POST['id_wik'];


	$sql = "
		UPDATE wiki SET nom_wik = '$nom_wik', des_wik = '$des_wik' WHERE id_wik = '$id_wik'
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		
		// LOG

		$filaDatos = obtenerDatosActividadServer( 'Wiki', $id_wik );
        $nombreRama = $filaDatos['nom_ram'];
        $nom_wik = $filaDatos['nom_wik'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó', 'wiki', $nom_wik, $nombreRama );
       
        logServer ( 'Cambio', $tipoUsuario, $id, 'Wiki', $des_log, $plantel );
        // FIN LOG

		
		echo "Exito";
	}else{

		echo "Error, verificar consulta";
		//echo $sql;
	}
?>