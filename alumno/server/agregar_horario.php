<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR HORARIO
	//inscripcion.php
	require('../inc/cabeceras.php');
	//require('../inc/funciones.php');

	$sub_hor = $_POST['sub_hor'];

	//echo sizeof($sub_hor);

	$id_alu_ram = $_GET['id_alu_ram'];


	$sqlDeleteCalAct = "DELETE FROM cal_act WHERE id_alu_ram4 = '$id_alu_ram'";

	$resultado_cal_act = mysqli_query($db, $sqlDeleteCalAct);

	if ($resultado_cal_act) {
		

		$sqlDeleteAluHor = "
			DELETE FROM alu_hor WHERE id_alu_ram1 = '$id_alu_ram'
		";


		$resultadoDeleteAluHor = mysqli_query($db, $sqlDeleteAluHor);
		


		if ($resultadoDeleteAluHor) {
			
			for($i = 0; $i < sizeof($sub_hor); $i++){
				$sql = "INSERT INTO alu_hor (id_alu_ram1, id_sub_hor5, est_alu_hor ) VALUES ('$id_alu_ram', '$sub_hor[$i]', 'Activo')";
				$resultado = mysqli_query($db, $sql);
				//echo $sql;

				//CAL_ACT PARA REGISTROS DE ACTIVIDADES CON CALIFICACION PENDIENTE
				//FOROS
				$sqlForos = "
					SELECT * 
					FROM sub_hor 
					INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
					WHERE id_sub_hor = '$sub_hor[$i]'
				";


				$resultadoForos = mysqli_query($db, $sqlForos);


				while($filaForos = mysqli_fetch_assoc($resultadoForos)){
					$id_for_cop = $filaForos['id_for_cop'];
					$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4) VALUES('$id_for_cop', '$id_alu_ram')";
					$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);

					//echo $sqlInsercionForos;


				}

				//ENTREGABLES


				$sqlEntregables = "
					SELECT * 
					FROM sub_hor 
					INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
					WHERE id_sub_hor = '$sub_hor[$i]'
				";


				$resultadoEntregables = mysqli_query($db, $sqlEntregables);


				while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
					$id_ent_cop = $filaEntregables['id_ent_cop'];
					$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4) VALUES('$id_ent_cop', '$id_alu_ram')";
					$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);

					//echo $sqlInsercionEntregables;
				}



				//EXAMENES

				$sqlExamenes = "
					SELECT * 
					FROM sub_hor 
					INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
					WHERE id_sub_hor = '$sub_hor[$i]'
				";


				$resultadoExamenes = mysqli_query($db, $sqlExamenes);


				while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
					$id_exa_cop = $filaExamenes['id_exa_cop'];
					$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4) VALUES('$id_exa_cop', '$id_alu_ram')";
					$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);

					//echo $sqlInsercionExamenes;

				}
			}
			
			if ($resultado) {
				

				// GENERACION DE PAGOS RECURRENTES
				
				//generarPagosRecurrentes($id_alu_ram);



				echo $id_alu_ram;
			}else{
				//echo "Error";
				echo $sql;
			}
		}else{
			echo "Error delete";
		}








	}else{
		echo $sqlDeleteCalAct;
	}

	
?>