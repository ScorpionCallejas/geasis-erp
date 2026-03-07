<?php 
	//ARCHIVO VIA AJAX PARA EDITAR FORO
	//foro_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_for = $_POST['nom_for'];
	$pun_for = $_POST['pun_for'];
	$des_for = $_POST['des_for'];

	$ini_for_cop = $_POST['ini_for_cop'];
	$fin_for_cop = $_POST['fin_for_cop'];
	$id_for_cop = $_POST['id_for_cop'];

	$fila = obtenerDatosActividadGrupoServer( $id_for_cop, 'Foro', 'arreglo' );
	$id_for = $fila['identificador'];

	$sql = "
		UPDATE foro 
		SET 
		pun_for = '$pun_for',
		nom_for = '$nom_for',
		des_for = '$des_for'
		WHERE 
		id_for = '$id_for'
	";

	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {

		$sqlCopia = "
			UPDATE foro_copia
			SET
			ini_for_cop = '$ini_for_cop',
			fin_for_cop = '$fin_for_cop'
			WHERE
			id_for_cop = '$id_for_cop'
		";

		$resultadoCopia = mysqli_query( $db, $sqlCopia );

		if ( $resultadoCopia ) {
			
			$sqlAlumnos = "
				UPDATE cal_act
				SET 
				ini_cal_act = '$ini_for_cop',
				fin_cal_act = '$fin_for_cop'
				WHERE
				id_for_cop2 = '$id_for_cop'
			";

			$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

			if ( !$resultadoAlumnos ) {
				echo $sqlAlumnos;
			}

			// LOG
				$filaDatos = obtenerDatosActividadServer( 'Foro', $id_for );
				$nombreRama = $filaDatos['nom_ram'];
				$nom_for = $filaDatos['nom_for'];

				$des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó', 'foro', $nom_for, $nombreRama );

				logServer ( 'Cambio', $tipoUsuario, $id, 'Foro', $des_log, $plantel );
			//FIN LOG
		
		} else {
			echo $sqlCopia;
		}
		

		echo "Exito";
	}else{

		echo "Error, verificar consulta";
		//echo $sql;
	}
?>