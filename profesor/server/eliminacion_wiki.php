<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR WIKIS ANEXADAS A BLOQUES
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_wik = $_POST['wiki'];

	// LOG
	$filaDatos = obtenerDatosActividadServer( 'Wiki', $id_wik );
    $nombreRama = $filaDatos['nom_ram'];
    $nom_wik = $filaDatos['nom_wik'];

    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'wiki', $nom_wik, $nombreRama );
   
    logServer ( 'Baja', $tipoUsuario, $id, 'Wiki', $des_log, $plantel );
    // FIN LOG
    

	$sql = "DELETE FROM wiki WHERE id_wik = '$id_wik'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		
		echo "true";
		
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>