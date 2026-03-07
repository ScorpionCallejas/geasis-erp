<?php 
	//ARCHIVO VIA AJAX PARA EDITAR RESPUESTAS DE EXAMEN
	//examen.php
	require('../inc/cabeceras.php');

	$id_alu_ram = $_GET['id_alu_ram'];
	$id_exa_cop = $_GET['id_exa_cop'];

	if ( isset( $_POST['respuesta_alumno'] ) ) {
		// RESTAR INTENTOS
		$sqlUpdateCalificacionActividad = "
			UPDATE cal_act 
			SET 
			int_cal_act = 0
			WHERE 
			id_exa_cop2 = '$id_exa_cop' AND id_alu_ram4 = '$id_alu_ram'
		";

		//echo $sqlUpdateCalificacionActividad;

		$resultadoUpdateCalificacionActividad = mysqli_query($db, $sqlUpdateCalificacionActividad);

		if (!$resultadoUpdateCalificacionActividad) {
			
			echo $sqlUpdateCalificacionActividad;

		}
		// FIN RESTAR INTENTOS

	} else {
		$id_res = $_POST['respuesta'];
		

		$sqlRespuesta = "
			SELECT *
			FROM respuesta
			INNER JOIN pregunta ON pregunta.id_pre = respuesta.id_pre1
			INNER JOIN examen_copia ON examen_copia.id_exa1 = pregunta.id_exa2
			WHERE id_res = '$id_res' AND id_exa_cop = '$id_exa_cop'
		";

		//echo $sqlRespuesta;

		$resultadoRespuesta = mysqli_query($db, $sqlRespuesta);


		if ($resultadoRespuesta) {

			$filaRespuesta = mysqli_fetch_assoc($resultadoRespuesta);

			$id_pre = $filaRespuesta['id_pre'];

		 	//echo "id_exa_cop: ".$id_exa_cop;

			$sqlValidacion = "
				SELECT *
				FROM respuesta_alumno
				INNER JOIN pregunta ON pregunta.id_pre = respuesta_alumno.id_pre2
				WHERE id_pre2 = '$id_pre' AND id_alu_ram8 = '$id_alu_ram'
			";

			//echo $sqlValidacion;

			$resultadoValidacion = mysqli_query($db, $sqlValidacion);


			if ($resultadoValidacion) {

				$totalValidacion = mysqli_num_rows($resultadoValidacion);

				//echo $totalValidacion;


				// VALIDACION PARA INSERTAR RESPUESTA O CAMBIAR RESPECTO A UNA PREGUNTA
				if ($totalValidacion == 0) {

					$sqlInsercion = "
						INSERT INTO respuesta_alumno (id_res1, id_pre2, id_alu_ram8, id_exa_cop1) VALUES('$id_res', '$id_pre', '$id_alu_ram', '$id_exa_cop')
					";	

					$resultadoInsercion = mysqli_query($db, $sqlInsercion);

					if ($resultadoInsercion) {
							//echo "True";
							$sqlTotal = "	
								SELECT SUM(pun_pre) AS total
								FROM pregunta
								INNER JOIN respuesta_alumno ON respuesta_alumno.id_pre2=pregunta.id_pre
								INNER JOIN respuesta ON respuesta.id_res=respuesta_alumno.id_res1
								INNER JOIN examen_copia ON examen_copia.id_exa1 = pregunta.id_exa2
								WHERE id_alu_ram8 = '$id_alu_ram' AND val_res = 'Verdadero' AND id_exa_cop = '$id_exa_cop'

							";

							//echo $sqlTotal;

							$resultadoTotal = mysqli_query($db, $sqlTotal);

							if ($resultadoTotal) {

								$filaTotal = mysqli_fetch_assoc($resultadoTotal);


								
								$total = $filaTotal['total'];
								//echo "total: ".$total;

								$sqlUpdateCalificacionActividad = "
									UPDATE cal_act SET pun_cal_act = '$total' WHERE id_exa_cop2 = '$id_exa_cop' AND id_alu_ram4 = '$id_alu_ram'
								";

								//echo $sqlUpdateCalificacionActividad;

								$resultadoUpdateCalificacionActividad = mysqli_query($db, $sqlUpdateCalificacionActividad);

								if ($resultadoUpdateCalificacionActividad) {
									
									echo "Exito en todo";

								}else{
									echo $sqlUpdateCalificacionActividad;
								}

								
							}else{
								echo $sqlTotal;
							}

							


					}else{
						echo "False";
					}
				}else{

					
					$sqlUpdate = "
						UPDATE respuesta_alumno SET id_res1 = '$id_res' WHERE id_pre2 = '$id_pre' AND id_alu_ram8 = '$id_alu_ram'
					";	


					$resultadoUpdate = mysqli_query($db, $sqlUpdate);

					if ($resultadoUpdate) {
						//echo "True";
						$sqlTotal = "	
							SELECT SUM(pun_pre) AS total
							FROM pregunta
							INNER JOIN respuesta_alumno ON respuesta_alumno.id_pre2=pregunta.id_pre
							INNER JOIN respuesta ON respuesta.id_res=respuesta_alumno.id_res1
							INNER JOIN examen_copia ON examen_copia.id_exa1 = pregunta.id_exa2
							WHERE id_alu_ram8 = '$id_alu_ram' AND val_res = 'Verdadero' AND id_exa_cop = '$id_exa_cop'

						";

						//echo $sqlTotal;

						$resultadoTotal = mysqli_query($db, $sqlTotal);

						if ($resultadoTotal) {

							$filaTotal = mysqli_fetch_assoc($resultadoTotal);

							
							$total = $filaTotal['total'];
							//echo "total: ".$total;

							$sqlUpdateCalificacionActividad = "
								UPDATE cal_act SET pun_cal_act = '$total' WHERE id_exa_cop2 = '$id_exa_cop' AND id_alu_ram4 = '$id_alu_ram'
							";

							//echo $sqlUpdateCalificacionActividad;

							$resultadoUpdateCalificacionActividad = mysqli_query($db, $sqlUpdateCalificacionActividad);

							if ($resultadoUpdateCalificacionActividad) {
								
								echo "Exito en todo";

							}else{
								echo $sqlUpdateCalificacionActividad;
							}

							
						}else{
							echo $sqlTotal;
						}

						
					}else{
						echo "False";
					}
				}
				
			}else{
				echo $sqlValidacion;
			}

			
			
		}else{
			echo $sqlRespuesta;
		}
	}

	
	
?>