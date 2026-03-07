<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR RESPUESTAS ANEXADAS A RESPUESTAS
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_res = $_POST['id_res'];

	$sqlRespuesta = "
		SELECT *
		FROM respuesta
		INNER JOIN pregunta ON pregunta.id_pre = respuesta.id_pre1
		WHERE id_res = '$id_res'
	";

	$resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

	$filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta );

	$id_exa = $filaRespuesta['id_exa2'];


	$sql = "DELETE FROM respuesta WHERE id_res = '$id_res'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		// logServer ( 'Baja', $tipoUsuario, $id, 'Respuesta', $plantel );

		// LOG
		$filaDatos = obtenerDatosExamenServer( $id_exa );
		$nombreExamen = $filaDatos['nom_exa'];
		$nombrePrograma = $filaDatos['nom_ram'];

		$des_log =  obtenerDescripcionExamenLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'respuesta', $nombreExamen, $nombrePrograma );
	   
		logServer ( 'Baja', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
		// FIN LOG

		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>