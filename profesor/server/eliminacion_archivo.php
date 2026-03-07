<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR ARCHIVOS
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_arc = $_POST['archivo'];


	// LOG

	$filaDatos = obtenerDatosActividadServer( 'Archivo', $id_arc );
    $nombreRama = $filaDatos['nom_ram'];
    $nom_arc = $filaDatos['nom_arc'];

    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'archivo', $nom_arc, $nombreRama );
   
    logServer ( 'Baja', $tipoUsuario, $id, 'Archivo', $des_log, $plantel );
    // FIN LOG


	$sqlConsulta = "SELECT arc_arc FROM archivo WHERE id_arc = '$id_arc'";
	$resultadoConsulta = mysqli_query($db, $sqlConsulta);
	$filaConsulta = mysqli_fetch_assoc($resultadoConsulta);

	$resultadoTotal = mysqli_query( $db, $sqlConsulta );

	$total = mysqli_num_rows( $resultadoTotal );

	$archivo = $filaConsulta['arc_arc'];

	if ( ( $archivo != NULL ) && ( $total == 1 ) ) {
		unlink("../../uploads/$archivo");
	}

	

	$sql = "DELETE FROM archivo WHERE id_arc ='$id_arc'";
	$resultado = mysqli_query($db, $sql);


	if ($resultado) {
		// logServer ( 'Baja', $tipoUsuario, $id, 'Archivo', $plantel );

		

		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>