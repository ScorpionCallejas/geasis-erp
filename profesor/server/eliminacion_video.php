<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR VIDEOS
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_vid = $_POST['video'];


	// LOG
	$filaDatos = obtenerDatosActividadServer( 'Video', $id_vid );
    $nombreRama = $filaDatos['nom_ram'];
    $nom_vid = $filaDatos['nom_vid'];

    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'video', $nom_vid, $nombreRama );
   
    logServer ( 'Baja', $tipoUsuario, $id, 'Video', $des_log, $plantel );
    // FIN LOG


	$sqlConsulta = "SELECT vid_vid FROM video WHERE id_vid = '$id_vid'";
	$resultadoConsulta = mysqli_query($db, $sqlConsulta);
	$filaConsulta = mysqli_fetch_assoc($resultadoConsulta);

	$video = $filaConsulta['vid_vid'];

	if ($video != NULL) {
		unlink("../../uploads/$video");
	}

	

	$sql = "DELETE FROM video WHERE id_vid ='$id_vid'";
	$resultado = mysqli_query($db, $sql);


	if ($resultado) {

		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>