<?php 
	//ARCHIVO VIA AJAX PARA EDITAR BLOQUE
	//materias.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_blo = $_POST['nom_blo_edicion'];
	$des_blo = $_POST['des_blo_edicion'];
	$id_blo = $_POST['id_blo'];


	$sql = "
		UPDATE bloque SET nom_blo = '$nom_blo', des_blo = '$des_blo' WHERE id_blo = '$id_blo'
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		// LOG
		$filaDatos = obtenerDatosBloqueServer( $id_blo );
        $nombreRama = $filaDatos['nom_ram'];
        $nombreBloque = $filaDatos['nom_blo'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó', 'bloque', $nombreBloque, $nombreRama );
       
        logServer ( 'Cambio', $tipoUsuario, $id, 'Bloque', $des_log, $plantel );
        // FIN LOG

		echo "true";
	}else{

		echo "Error, verificar consulta";
		//echo $sql;
	}
?>