<?php 
	//ARCHIVO VIA AJAX PARA EDITAR PREGUNTA
	//materias.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$pre_pre = $_POST['pre_pre'];
	$pun_pre = $_POST['puntaje'];
	$id_pre = $_POST['id_pre'];

	$id_exa = $_POST['id_exa'];


	$sql = "
		UPDATE pregunta SET pre_pre = '$pre_pre', pun_pre = '$pun_pre' WHERE id_pre = '$id_pre'
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		// LOG
		$filaDatos = obtenerDatosExamenServer( $id_exa );
		$nombreExamen = $filaDatos['nom_exa'];
		$nombrePrograma = $filaDatos['nom_ram'];

		$des_log =  obtenerDescripcionExamenLogServer( $tipoUsuario, $nomResponsable, 'editó', 'pregunta', $nombreExamen, $nombrePrograma );
	   
		logServer ( 'Cambio', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
		// FIN LOG

		echo "Exito";
	}else{

		// echo "Error, verificar consulta";
		//echo $sql;
	}
?>