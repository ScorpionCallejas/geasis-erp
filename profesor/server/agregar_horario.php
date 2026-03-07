<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR HORARIO
	//inscripcion.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_alu_ram = $_GET['id_alu_ram'];

	echo obtenerEstatus1AlumnoRamaServer( $id_alu_ram );


	// logServer ( 'Alta', $tipoUsuario, $id, 'alu_hor', $plantel );

	if( ( isset( $_POST['tipos'] ) ) && ( isset( $_POST['identificadores'] ) ) ) {
		// INSCRIPCION AVANZADA (SELECCION DE ACTIVIDADES PREVIA)
		
		$sub_hor = $_POST['sub_hor'];
		$tipos = $_POST['tipos'];
		$identificadores = $_POST['identificadores'];

		$estatus_cobros = $_POST['estatus_cobros'];


		$sqlDeleteCalAct = "DELETE FROM cal_act WHERE id_alu_ram4 = '$id_alu_ram'";

		$resultado_cal_act = mysqli_query($db, $sqlDeleteCalAct);

		if ($resultado_cal_act) {
			

			$fechaHoy = date( 'Y-m-d H:i:s' );
			$sqlDeleteAluHor = "
				UPDATE alu_hor SET est_alu_hor = 'Inactivo' WHERE id_alu_ram1 = '$id_alu_ram'
			";

			$resultadoDeleteAluHor = mysqli_query($db, $sqlDeleteAluHor);
			


			if ($resultadoDeleteAluHor) {
				
				for($i = 0; $i < sizeof($sub_hor); $i++){

					// LOG
			        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
					
					$filaDatos = obtenerDatosGrupalesServer( $sub_hor );
					$nombreMateria = $filaDatos['nom_mat'];
					$nombreClave = $filaDatos['nom_sub_hor'];
					$nombrePrograma = $filaDatos['nom_ram'];

					

					// EL ADMINISTRADOR: JUAN ZARATE registró a pedrito sola en ciencias sociales ( clave_grupal ) del programa Evaluacion unica.		
					$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
				   

					logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
					// FIN LOG

					
					$sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$sub_hor[$i]', '$fechaHoy', 'Activo' )";

					$resultado = mysqli_query($db, $sql);

					//echo $sql;

					//CAL_ACT PARA REGISTROS DE ACTIVIDADES CON CALIFICACION PENDIENTE
					
					for ( $j = 0 ; $j < sizeof( $identificadores ) ; $j++ ) { 
						if ( $tipos[$j] == 'Foro' ) {

							//FOROS
							$sqlForos = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$sub_hor[$i]' AND id_for1 = '$identificadores[$j]'
							";

							//echo $sqlForos;

							$resultadoForos = mysqli_query($db, $sqlForos);


							while($filaForos = mysqli_fetch_assoc($resultadoForos)){
							
								$id_for_cop = $filaForos['id_for_cop'];
								
								$ini_cal_act = $filaForos['ini_for_cop'];
								$fin_cal_act = $filaForos['fin_for_cop'];

								$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);

								//echo $sqlInsercionForos;

							}
							
						}else if ( $tipos[$j] == 'Entregable' ) {

							//echo 'entre a entregable';
							//ENTREGABLES
							$sqlEntregables = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$sub_hor[$i]' AND id_ent1 = '$identificadores[$j]'
							";

							//echo $sqlEntregables;


							$resultadoEntregables = mysqli_query($db, $sqlEntregables);


							while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
								
								$id_ent_cop = $filaEntregables['id_ent_cop'];
								
								$ini_cal_act = $filaEntregables['ini_ent_cop'];
								$fin_cal_act = $filaEntregables['fin_ent_cop'];

								$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);

								//echo $sqlInsercionEntregables;
							}
						
						}else if ( $tipos[$j] == 'Examen' ) {
							
							//EXAMENES
							$sqlExamenes = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$sub_hor[$i]' AND id_exa1 = '$identificadores[$j]'
							";


							$resultadoExamenes = mysqli_query($db, $sqlExamenes);


							while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){

								$id_exa_cop = $filaExamenes['id_exa_cop'];
								$ini_cal_act = $filaExamenes['ini_exa_cop'];
								$fin_cal_act = $filaExamenes['fin_exa_cop'];

								$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);

								//echo $sqlInsercionExamenes;

							}						
						}
					}

				}
				
				if ($resultado) {
					
					
					if ( $estatus_cobros == 'true' ) {
						
						generarPagosRecurrentes($id_alu_ram, $folioPlantel);

					}

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


	}else{
		// INSCRIPCION NORMAL (TODAS LAS ACTIVIDADES)
		$sub_hor = $_POST['sub_hor'];
		$estatus_cobros = $_POST['estatus_cobros'];


		//echo sizeof($sub_hor);

		$sqlDeleteCalAct = "DELETE FROM cal_act WHERE id_alu_ram4 = '$id_alu_ram'";

		$resultado_cal_act = mysqli_query($db, $sqlDeleteCalAct);

		if ($resultado_cal_act) {
			
			$fechaHoy = date( 'Y-m-d H:i:s' );
			$sqlDeleteAluHor = "
				UPDATE alu_hor SET est_alu_hor = 'Inactivo' WHERE id_alu_ram1 = '$id_alu_ram'
			";


			$resultadoDeleteAluHor = mysqli_query($db, $sqlDeleteAluHor);
			


			if ($resultadoDeleteAluHor) {
				
				for($i = 0; $i < sizeof($sub_hor); $i++){


					// ALUMNO A SALA DE MENSAJERIA
					$sqlSala = "
						SELECT *
						FROM sala
						WHERE id_sub_hor6 = '$sub_hor[$i]'
					";

					$datosSala = obtener_datos_consulta( $db, $sqlSala );

					if ( $datosSala['total'] == 1 ) {

						$datosAlumno = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
						
						$id_usuario = $datosAlumno['id_alu'];
						$tipo_usuario = 'Alumno';
						$id_sal = $datosSala['datos']['id_sal'];
						
						// USUARIOS
					    $sqlUsuarios = "
					      
					      INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
					      VALUES ( '$id_usuario',  '$tipo_usuario', '$id_sal' )

					    ";

					    $resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

					    if ( !$resultadoUsuarios ) {
					    
					      echo $sqlUsuarios;
					    
					    }
					    // USUARIOS

					}
					// FIN ALUMNO A SALA DE MENSAJERIA


					// LOG
			        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
					
					$filaDatos = obtenerDatosGrupalesServer( $sub_hor[$i] );
					$nombreMateria = $filaDatos['nom_mat'];
					$nombreClave = $filaDatos['nom_sub_hor'];
					$nombrePrograma = $filaDatos['nom_ram'];

					

					// EL ADMINISTRADOR: JUAN ZARATE registró a pedrito sola en ciencias sociales ( clave_grupal ) del programa Evaluacion unica.		
					$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
				   

					logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
					// FIN LOG

					$sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$sub_hor[$i]', '$fechaHoy', 'Activo' )";
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
						$ini_cal_act = $filaForos['ini_for_cop'];
						$fin_cal_act = $filaForos['fin_for_cop'];

						$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);

						//echo $sqlInsercionForos;


					}

					//ENTREGABLES


					$sqlEntregables = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
						INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
						WHERE id_sub_hor = '$sub_hor[$i]'
					";



					// echo $sqlEntregables;
					
					$resultadoEntregables = mysqli_query($db, $sqlEntregables);


					while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
						$id_ent_cop = $filaEntregables['id_ent_cop'];
						// echo $filaEntregables['nom_ent'];

						$ini_cal_act = $filaEntregables['ini_ent_cop'];
						$fin_cal_act = $filaEntregables['fin_ent_cop'];

						$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";

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
						
						$ini_cal_act = $filaExamenes['ini_exa_cop'];
						$fin_cal_act = $filaExamenes['fin_exa_cop'];

						$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						
						$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);

						//echo $sqlInsercionExamenes;

					}
				}
				
				if ($resultado) {
					

					if ( $estatus_cobros == 'true' ) {
						
						generarPagosRecurrentes($id_alu_ram, $folioPlantel);

					}
					
					

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
	}

	


	
?>