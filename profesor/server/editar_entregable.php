<?php 
	//ARCHIVO VIA AJAX PARA EDITAR ENTREGABLE
	//entregable_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_ent = $_POST['nom_ent'];
	$pun_ent = $_POST['pun_ent'];
	$des_ent = $_POST['des_ent'];

	$ini_ent_cop = $_POST['ini_ent_cop'];
	$fin_ent_cop = $_POST['fin_ent_cop'];
	$id_ent_cop = $_POST['id_ent_cop'];

	$fila = obtenerDatosActividadGrupoServer( $id_ent_cop, 'Entregable', 'arreglo' );
	$id_ent = $fila['identificador'];

	$sql = "
		UPDATE entregable 
		SET 
		pun_ent = '$pun_ent',
		nom_ent = '$nom_ent',
		des_ent = '$des_ent'
		WHERE 
		id_ent = '$id_ent'
	";

	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {

		$sqlCopia = "
			UPDATE entregable_copia
			SET
			ini_ent_cop = '$ini_ent_cop',
			fin_ent_cop = '$fin_ent_cop'
			WHERE
			id_ent_cop = '$id_ent_cop'
		";

		$resultadoCopia = mysqli_query( $db, $sqlCopia );

		if ( $resultadoCopia ) {


			$sqlAlumnos = "
				UPDATE cal_act
				SET 
				ini_cal_act = '$ini_ent_cop',
				fin_cal_act = '$fin_ent_cop'
				WHERE
				id_ent_cop2 = '$id_ent_cop'
			";

			$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

			if ( !$resultadoAlumnos ) {
				echo $sqlAlumnos;
			}
			
			// LOG
				$filaDatos = obtenerDatosActividadServer( 'Entregable', $id_ent );
				$nombreRama = $filaDatos['nom_ram'];
				$nom_ent = $filaDatos['nom_ent'];

				$des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó', 'entregable', $nom_ent, $nombreRama );

				logServer ( 'Cambio', $tipoUsuario, $id, 'Entregable', $des_log, $plantel );
			//FIN LOG
		
		} else {
			echo $sqlCopia;
		}
		
		// echo $sql;
		// echo $sqlCopia;

		echo "Exito";
	}else{

		echo "Error, verificar consulta";
		//echo $sql;
	}
?>