<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CITA
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	// Añade el header para JSON
	// header('Content-Type: application/json');

	$accion = $_POST['accion'];
	$rub_met = $_POST['rub_met'];
	$can_met = $_POST['can_met'];
	
	if( $rub_met == 'Registro' ){
		if ( $accion == 'Agregar' ) {
			$id_eje = $_POST['id_eje'];
			$fec_met = $_POST['fec_met'];

			$sql = "
				INSERT INTO meta( id_eje5, can_met, fec_met ) VALUES( '$id_eje', '$can_met', '$fec_met' )
			";
		} else if( $accion == 'Cambio' ) {
			$id_met = $_POST['id_met'];
			$sql = "
				UPDATE meta 
				SET
				can_met = '$can_met'
				WHERE
				id_met = '$id_met'
			";
		}
	} else if( $rub_met == 'Cita' ){

	} else if( $rub_met == 'Contacto' ){

	} else if( $rub_met == 'Agendada' ){

	}

	$resultado = mysqli_query( $db, $sql );
	if( $resultado ){
		if( $accion == 'Agregar' ){
			$id_met = mysqli_insert_id($db);
			echo $id_met;
		} else {
			// echo $sql;
			echo 'Exito';
		}
	}else{
		echo $sql;
	}
	
?>