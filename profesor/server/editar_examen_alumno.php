<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR RESPUESTAS DE ALUMNO Y REINICIAR EXAMEN DE NUEVO
	//examen.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];
	$id_exa_cop = $_POST['id_exa_cop'];


	$sql = "
		UPDATE cal_act 
		SET 
		int_cal_act = int_cal_act + 1,
		fec_cal_act = NULL, 
		pun_cal_act = NULL 
		WHERE
		id_exa_cop2 = '$id_exa_cop' AND id_alu_ram4 = '$id_alu_ram'
	";


	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {
		
		$sqlEliminacionRespuestas = "
			DELETE 
			FROM respuesta_alumno
			WHERE id_exa_cop1 = '$id_exa_cop' AND id_alu_ram8 = '$id_alu_ram'
		";

		$resultadoEliminacionRespuestas = mysqli_query( $db, $sqlEliminacionRespuestas );

		if ( $resultadoEliminacionRespuestas ) {
		
			echo "Exito";

		} else {

			echo $sqlEliminacionRespuestas;
		
		}

		// logServer ( 'Cambio', $tipoUsuario, $id, 'Documento', $plantel );
		
	} else {

		// echo "Error, verificar consulta";
		echo $sql;
	}
?>