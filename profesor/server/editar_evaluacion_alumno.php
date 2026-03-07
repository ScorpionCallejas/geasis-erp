<?php 
	//ARCHIVO VIA AJAX PARA EDITAR CALIFICACIONES DE TABLA calificacion
	//materias_horario.php
	require('../inc/cabeceras.php');

	$id_alu_ram = $_GET['id_alu_ram'];
	$id_mat = $_GET['id_mat'];

	
	

	// TABLA CALIFICACION 

	$id_cal = $_POST['id_cal'];
	$fin_cal = $_POST['fin_cal'];
	$ext_cal = $_POST['ext_cal'];

	//echo sizeof($id_alu_ram);

	//PARCIALES
	if(isset($_POST['id_par'])){
		$id_par = $_POST['id_par'];
		$cal_par = $_POST['cal_par'];	

		for ($i=0; $i < sizeof($id_par); $i++) {

			if ($cal_par[$i] != "") {
				// CONDICIONANTE QUE CONTRINUYE A HACER LAS INSERCIONES EN INDICES DONDE HAY DATOS
				//DE LO CONTRARIO INSERTA CEROS, ESO FASTIDIARIA AL AUMNO
				$sql = "
					UPDATE parcial SET cal_par = '$cal_par[$i]' WHERE id_alu_ram9 = '$id_alu_ram' AND id_mat3 = '$id_mat' AND id_par = '$id_par[$i]';
				";


				$resultado = mysqli_query($db, $sql);
			}
		}
	}
	

	


	if ($fin_cal != "" && $ext_cal == "") {
		$sqlCalificacion = "
			UPDATE calificacion SET fin_cal = '$fin_cal'  WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat' AND id_cal = '$id_cal';
		";

		$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);

	
		
	}else if($ext_cal != "" && $fin_cal == ""){
		$sqlCalificacion = "
			UPDATE calificacion SET ext_cal = '$ext_cal'  WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat' AND id_cal = '$id_cal';
		";

		$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);


	}else if($ext_cal != "" && $fin_cal != ""){
		$sqlCalificacion = "
			UPDATE calificacion SET ext_cal = '$ext_cal', fin_cal = '$fin_cal'  WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat' AND id_cal = '$id_cal';
		";

		$resultadoCalificacion = mysqli_query($db, $sqlCalificacion);


	}
	

	if (isset($resultado) || isset($resultadoCalificacion)) {
		echo "Exito";
	}


?>