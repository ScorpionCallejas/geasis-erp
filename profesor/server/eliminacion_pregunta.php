<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR PREGUNTAS ANEXADAS A EXAMENES
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_pre = $_POST['id_pre'];

	$sqlPregunta = "
		SELECT *
		FROM pregunta
		WHERE id_pre = '$id_pre'
	";

	$resultadoPregunta = mysqli_query( $db, $sqlPregunta );

	$filaPregunta = mysqli_fetch_assoc( $resultadoPregunta );

	$id_exa = $filaPregunta['id_exa2'];



	$sql = "DELETE FROM pregunta WHERE id_pre = '$id_pre'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		// logServer ( 'Baja', $tipoUsuario, $id, 'Pregunta', $plantel );

		// LOG
		$filaDatos = obtenerDatosExamenServer( $id_exa );
		$nombreExamen = $filaDatos['nom_exa'];
		$nombrePrograma = $filaDatos['nom_ram'];

		$des_log =  obtenerDescripcionExamenLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'pregunta', $nombreExamen, $nombrePrograma );
	   
		logServer( 'Baja', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
		// FIN LOG


		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>