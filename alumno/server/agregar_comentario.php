<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO COMENTARIO
	//foro.php
	require('../inc/cabeceras.php');

	$com_com = $_POST['comentario'];
	$id_alu_ram5 = $_POST['id_alu_ram5'];
	$id_for_cop1 = $_POST['id_for_cop1'];
	$fec_com = date('Y-m-d H:i:s');

	$sql = "INSERT INTO comentario (com_com, id_alu_ram5, id_for_cop1, fec_com) VALUES ('$com_com', '$id_alu_ram5', '$id_for_cop1', '$fec_com')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		$sqlConsulta = "SELECT * FROM cal_act WHERE id_for_cop2 = '$id_for_cop1' AND id_alu_ram4 = '$id_alu_ram5'";
		$resultadoConsulta = mysqli_query($db, $sqlConsulta);

		$filaConsulta = mysqli_fetch_assoc($resultadoConsulta);

		if ($filaConsulta['fec_cal_act'] == NULL) {
			$sqlUpdate = "UPDATE cal_act SET fec_cal_act = '$fec_com' WHERE id_for_cop2 = '$id_for_cop1' AND id_alu_ram4 = '$id_alu_ram5'";

			$resultadoUpdate = mysqli_query($db, $sqlUpdate);

			if ($resultadoUpdate) {
				
				echo "Exito";


			}else{
				echo "Error en update";
			}
		}else{
			
			echo "Exito";
		}

		
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>