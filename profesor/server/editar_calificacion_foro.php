<?php 
	//ARCHIVO VIA AJAX PARA EDITAR CALIFICACIONES DE ENTREGABLE
	//entregable.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_for_cop = $_GET['id_for_cop'];
	$id_alu_ram = $_POST['id_alu_ram'];
	

	if ( isset($_POST['puntos']) ) {
		$pun_cal_act = $_POST['puntos'];

		// $ret_cal_act = $_POST['ret_cal_act'];
		$id_pro = $id;

		//echo sizeof($id_alu_ram);

		$sql = "
			UPDATE cal_act SET pun_cal_act = '$pun_cal_act', id_pro2 = '$id_pro' WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop2 = '$id_for_cop'
		";



		$resultado = mysqli_query($db, $sql);

		//echo $ret_cal_act[$i];


		if ($resultado) {

			// LOG
			$filaDatos = obtenerDatosActividadAlumnoServer( $id_for_cop, 'Foro', $id_alu_ram );

	        $des_log =  obtenerDescripcionCalificacionActividadLogServer( $tipoUsuario, $nomResponsable, 'calificó', 'foro', $filaDatos['nom_act'], $filaDatos['nom_mat'], $filaDatos['nom_gru'], $filaDatos['nom_ram'], $filaDatos['nom_alu'] );
	       
	        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
	        // FIN LOG

			echo "Exito";
		}else{

			//echo "Error, verificar consulta";
			echo $sql;
		}
	}else if( isset($_POST['retroalimentacion']) ){

		$ret_cal_act = $_POST['retroalimentacion'];

		// $ret_cal_act = $_POST['ret_cal_act'];
		$id_pro = $id;

		//echo sizeof($id_alu_ram);

		$sql = "
			UPDATE cal_act SET ret_cal_act = '$ret_cal_act', id_pro2 = '$id_pro' WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop2 = '$id_for_cop'
		";



		$resultado = mysqli_query($db, $sql);

		//echo $ret_cal_act[$i];


		if ($resultado) {

			// LOG
			$filaDatos = obtenerDatosActividadAlumnoServer( $id_for_cop, 'Foro', $id_alu_ram );
			// echo $filaDatos['nom_act'];
			
	        $des_log =  obtenerDescripcionCalificacionActividadLogServer( $tipoUsuario, $nomResponsable, 'retroalimentó', 'foro', $filaDatos['nom_act'], $filaDatos['nom_mat'], $filaDatos['nom_gru'], $filaDatos['nom_ram'], $filaDatos['nom_alu'] );
	       
	        logServer ( 'Cambio', $tipoUsuario, $id, 'Calificación', $des_log, $plantel );
	        // FIN LOG
			echo "Exito";
		}else{

			//echo "Error, verificar consulta";
			echo $sql;
		}


	}
	

	
?>