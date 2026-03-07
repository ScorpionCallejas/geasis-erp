<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR  RESPUESTA A PREGUNTA
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$res_res = addslashes( $_POST['res_res'] );
	$val_res = $_POST['val_res'];
	$id_pre = $_POST['id_pre'];

	// $reemplazoAcentos = array(    
	// 	"'"=>'`', '"'=>'`' 
 //    );
	
	// $res_res = strtr( $res_res, $reemplazoAcentos );


	$sql = "INSERT INTO respuesta (res_res, val_res, id_pre1) VALUES ('$res_res', '$val_res', '$id_pre')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		$sqlExamen = "
			SELECT *
			FROM respuesta
			INNER JOIN pregunta ON pregunta.id_pre = respuesta.id_pre1
			WHERE id_pre1 = '$id_pre'
		";

		$resultadoExamen = mysqli_query( $db, $sqlExamen );

		$filaExamen = mysqli_fetch_assoc( $resultadoExamen );

		$id_exa = $filaExamen['id_exa2'];

		// LOG
		$filaDatos = obtenerDatosExamenServer( $id_exa );
		$nombreExamen = $filaDatos['nom_exa'];
		$nombrePrograma = $filaDatos['nom_ram'];

		$des_log =  obtenerDescripcionExamenLogServer( $tipoUsuario, $nomResponsable, 'registró', 'respuesta', $nombreExamen, $nombrePrograma );
	   
		logServer ( 'Alta', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
		// FIN LOG


		echo "Exito";
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>