<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR TAREA
	//bloque_contenido.php
	require('../inc/cabeceras.php');


	$id_tar = $_POST['tarea'];

	$sqlConsulta = "
		SELECT * 
		FROM tarea WHERE 
		id_tar = '$id_tar'
	";

	$resultadoConsulta = mysqli_query($db, $sqlConsulta);
	$filaConsulta = mysqli_fetch_assoc($resultadoConsulta);

	$tarea = $filaConsulta['doc_tar'];

	if ( $tarea != NULL ) {
		unlink("../../uploads/$tarea");
	}

	$sql = "DELETE FROM tarea WHERE id_tar ='$id_tar'";
	$resultado = mysqli_query($db, $sql);


	if ($resultado) {

		$id_alu_ram = $filaConsulta['id_alu_ram6'];
		$id_ent_cop = $filaConsulta['id_ent_cop1'];

		$sqlUpdateCalact = "
			UPDATE cal_act
			SET
			fec_cal_act = NULL
			WHERE 
			id_alu_ram4 = '$id_alu_ram'
			AND
			id_ent_cop2 = '$id_ent_cop'
		";

		$resultadoUpdateCalact = mysqli_query( $db, $sqlUpdateCalact );

		if ( $resultadoUpdateCalact ) {
			
			echo "true";

		} else {

			echo $sqlUpdateCalact;
		
		}
		
	}else{
		
		echo "error, verificar consulta";
		//echo $sql;
	}


?>