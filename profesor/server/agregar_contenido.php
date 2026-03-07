<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO CONCEPTO DE PAGO
	//empleado_conceptos.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$con_blo = $_POST['contenido'];
	$id_blo = $_GET['id_blo'];
	

	$sql = "UPDATE bloque SET con_blo = '$con_blo' WHERE id_blo = '$id_blo'";
	//echo $sql;
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		// LOG
		$filaDatos = obtenerDatosBloqueServer( $id_blo );
        $nombreRama = $filaDatos['nom_ram'];
        $nom_blo = $filaDatos['nom_blo'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó', 'contenido de bloque', $nom_blo, $nombreRama );
       
        logServer ( 'Cambio', $tipoUsuario, $id, 'Bloque', $des_log, $plantel );
        // FIN LOG

		echo "Exito";
	}else{
		echo $sql;
	}
		
	
?>