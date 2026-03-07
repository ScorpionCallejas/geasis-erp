<?php 
	//ARCHIVO VIA AJAX PARA EDITAR EXAMEN
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_exa = $_POST['nom_exa'];
	$des_exa = $_POST['descripcionExamen'];
	$dur_exa = $_POST['dur_exa'];


	$ini_exa_cop = $_POST['ini_exa'];
	$fin_exa_cop = $_POST['fin_exa'];
	$id_exa_cop = $_POST['id_exa_cop'];

	$fila = obtenerDatosActividadGrupoServer( $id_exa_cop, 'Examen', 'arreglo' );
	$id_exa = $fila['identificador'];

	$sqlPuntaje = "
		SELECT SUM( pun_pre ) AS puntaje
		FROM pregunta
		INNER JOIN examen ON examen.id_exa = pregunta.id_exa2
		WHERE id_exa = '$id_exa'
	";

	$resultadoPuntaje = mysqli_query( $db, $sqlPuntaje );

	$filaPuntaje = mysqli_fetch_assoc( $resultadoPuntaje );

	$pun_exa = $filaPuntaje['puntaje'];

	$sql = "
		UPDATE examen 
		SET 
		pun_exa = '$pun_exa',
		nom_exa = '$nom_exa',
		des_exa = '$des_exa',
		dur_exa = '$dur_exa'
		WHERE
		id_exa = '$id_exa'
	";

	$resultado = mysqli_query($db, $sql);

	if ( $resultado ) {

		$sqlCopia = "
			UPDATE examen_copia
			SET
			ini_exa_cop = '$ini_exa_cop',
			fin_exa_cop = '$fin_exa_cop'
			WHERE
			id_exa_cop = '$id_exa_cop'
		";

		$resultadoCopia = mysqli_query( $db, $sqlCopia );

		if ( $resultadoCopia ) {
			

			$sqlAlumnos = "
				UPDATE cal_act
				SET 
				ini_cal_act = '$ini_exa_cop',
				fin_cal_act = '$fin_exa_cop'
				WHERE
				id_exa_cop2 = '$id_exa_cop'
			";

			$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

			if ( !$resultadoAlumnos ) {
				echo $sqlAlumnos;
			}

			// LOG
				$filaDatos = obtenerDatosActividadServer( 'Examen', $id_exa );
				$nombreRama = $filaDatos['nom_ram'];
				$nom_exa = $filaDatos['nom_exa'];

				$des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'editó', 'examen', $nom_exa, $nombreRama );

				logServer ( 'Cambio', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
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