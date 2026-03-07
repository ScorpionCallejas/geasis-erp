<?php
	require('../inc/cabeceras.php');

	$id_sal = $_POST['id_sal'];

	$sqlusuarios = "
		SELECT *
		FROM usuario_sala 
		WHERE id_sal6 = '$id_sal' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' )
	";

	$resultadoUsuarios = mysqli_query( $db, $sqlusuarios );

	$datos = array();

	$datos['id'] = '';
	$datos['tipo'] = '';
	$i = 0;

	while( $filaUsuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){

		// echo $filaUsuarios['usu_usu_sal'].' '.$filaUsuarios['tip_usu_sal'];
		$datos['id'][$i] = $filaUsuarios['usu_usu_sal'];
		$datos['tipo'][$i] = $filaUsuarios['tip_usu_sal'];

	}

	// $datos['id'][1] = 44;
	// $datos['tipo'][1] = 'Profesor';

	echo json_encode( $datos );
?>