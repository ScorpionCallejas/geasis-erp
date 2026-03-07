<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR REPLICA
	//foros.php
	require('../inc/cabeceras.php');


	$id_rep = $_POST['replica'];

	$sqlConsulta = "
		SELECT *
		FROM replica
		INNER JOIN comentario ON comentario.id_com = replica.id_com1
		WHERE id_rep = '$id_rep'
	";

	$resultadoConsulta = mysqli_query( $db, $sqlConsulta );

	if ( $resultadoConsulta ) {
		
		$filaConsulta = mysqli_fetch_assoc( $resultadoConsulta );

		// $id_alu_ram = $filaConsulta['id_alu_ram5'];
		$id_for_cop = $filaConsulta['id_for_cop1'];

		$sqlEliminacionNotificacionProfesor = "
			DELETE FROM notificacion_profesor
			WHERE id_for_cop3 = '$id_for_cop' AND id1_not_pro = '$id_rep' AND tip_not_pro = 'replica'
		";

		$resultadoEliminacionNotificacionProfesor = mysqli_query( $db, $sqlEliminacionNotificacionProfesor );

		if ( $resultadoEliminacionNotificacionProfesor ) {
			
			$sql = "DELETE FROM replica WHERE id_rep = '$id_rep' ";
			$resultado = mysqli_query($db, $sql);


			if ($resultado) {
				echo "true";
			}else{
				echo $sql;
			}

		} else {
			echo $sqlEliminacionNotificacionProfesor;
		}

	} else {
		echo $sqlConsulta;
	}


	


?>