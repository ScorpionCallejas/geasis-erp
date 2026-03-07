<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR COMENTARIO
	//foros.php
	require('../inc/cabeceras.php');

	$id_com = $_POST['comentario'];
	$sqlConsulta = "
		SELECT *
		FROM comentario
		WHERE id_com = '$id_com'
	";

	$resultadoConsulta = mysqli_query( $db, $sqlConsulta );

	if ( $resultadoConsulta ) {
		
		$filaConsulta = mysqli_fetch_assoc( $resultadoConsulta );

		// $id_alu_ram = $filaConsulta['id_alu_ram5'];
		$id_for_cop = $filaConsulta['id_for_cop1'];

		$sqlReplicas = "
			SELECT *
			FROM replica
			WHERE id_com1 = '$id_com' 
		";

		$resultadoTotalReplicas = mysqli_query( $db, $sqlReplicas );

		if ( $resultadoTotalReplicas ) {

			$sql = "DELETE FROM comentario WHERE id_com = '$id_com' ";
			$resultado = mysqli_query($db, $sql);

			if ( $resultado ) {
				echo "true";
			} else {
				echo $sql;
			}


		} else {
			echo $sqlReplicas;
		}
			
		

	} else {
		echo $sqlConsulta;
	}

		

	


?>