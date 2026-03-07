<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVA ACTIVIDAD COPIADA
	//clase_contenido.php > obtener_copiar_actividad.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	

	$id_blo = $_POST['id_blo'];
	$tipo = $_POST['tipo'];
	$id_sub_hor = $_POST['id_sub_hor'];
	
	$inicio_copia = $_POST['inicio_copia'];
	$fin_copia = $_POST['fin_copia'];

	$identificador = $_POST['identificador'];

	$datosActividad = obtenerDatosActividadServer( $tipo, $identificador );

	// echo $datosActividad['nom_for'];
	// echo $tipo;

	if ( $tipo == 'Foro' ) {
	// FORO

		$nom_for = $datosActividad['nom_for'];
		$pun_for = $datosActividad['pun_for'];
		
		$des_for = $datosActividad['des_for'];

		$ini_for_cop = $inicio_copia;
		$fin_for_cop = $fin_copia;

		$fec_for = date( 'Y-m-d H:i:s' );

		$datos = obtenerEnterosMasterServer( $id_sub_hor, $ini_for_cop, $fin_for_cop );

		$ini_for = $datos['inicio'];
		$fin_for = $datos['fin'];

		$sql = "
			INSERT INTO foro ( nom_for, pun_for, ini_for, fin_for, des_for, tip_for, fec_for, id_blo4) 
			VALUES (		  '$nom_for', '$pun_for', '$ini_for', '$fin_for', '$des_for', 'Foro', '$fec_for', '$id_blo')
			
		";

		// echo $sql;

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			// LOG
			$filaDatos = obtenerDatosBloqueServer( $id_blo );
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró (copiando y pegando)', 'foro', $nom_for, $nombreRama );
	       
	        logServer ( 'Alta', $tipoUsuario, $id, 'Foro', $des_log, $plantel );
	        // FIN LOG

	        // EXTRACCION DE SUBHORS
	        $sqlGrupos = "
	        	SELECT *
				FROM bloque
				INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
				WHERE id_blo = '$id_blo' AND est_sub_hor = 'Activo'
	        ";


	        $id_for1 = obtenerUltimoIdentificadorServer( 'foro', 'id_for' ); //EXTRACCION DE CLAVE FORANEA 

	        $resultadoGrupos = mysqli_query( $db, $sqlGrupos );

	        while( $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos ) ){
	    	
		    	// INSERCION EN COPIA Y GRUPO
		        
				$id_sub_hor2 = $filaGrupos['id_sub_hor'];

				$sqlForoCopia = "INSERT INTO foro_copia(ini_for_cop, fin_for_cop, id_for1, id_sub_hor2) VALUES('$ini_for_cop', '$fin_for_cop', '$id_for1', '$id_sub_hor2')";
				
				$resuladoForoCopia = mysqli_query($db, $sqlForoCopia);


				if ($resuladoForoCopia) {


					$sqlMaximoForoCopia = "
						SELECT MAX(id_for_cop) AS maximo
						FROM foro_copia
					";

					$resultadoMaximoForoCopia = mysqli_query($db, $sqlMaximoForoCopia);


					if ($resultadoMaximoForoCopia) {
						$filaMaximoForoCopia = mysqli_fetch_assoc($resultadoMaximoForoCopia);

						$maximoForoCopia = $filaMaximoForoCopia['maximo'];
						$id_for_cop = $maximoForoCopia;
						
						$sqlAlumnosActualizados = "

							SELECT *
							FROM alu_hor
							WHERE id_sub_hor5 = '$id_sub_hor2' AND est_alu_hor = 'Activo'
						";

						$resultadoAlumnosActualizados = mysqli_query($db, $sqlAlumnosActualizados);

						if ($resultadoAlumnosActualizados) {

							while($filaAlumnosActualizados = mysqli_fetch_assoc($resultadoAlumnosActualizados)){
								
								
								$id_alu_ram = $filaAlumnosActualizados['id_alu_ram1'];

								$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_for_cop', '$fin_for_cop' )";
								$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);

								if(!$resultadoInsercionForos){
									echo "error en insercion foros copias";
								}

							}

							//echo "Exito";
							
						}else{
							echo "error en consulta de alu_hor";
						}




					}else{
						echo "error en extraccion de maximo foro copia";
					}
					
					

				}else{
					echo "error en insercion de foro copia";
				}

		        // FIN INSERCION EN COPIA Y GRUPO



	        // FIN WHILE sub_hors
	        }

	        // echo 'Exito';



		} else {
			echo "error, verificar consulta!";
			//echo $sql;
		}

	// FIN FORO
	} else if ( $tipo == 'Entregable' ) {
	//  ENTREGABLE

		$nom_ent = $datosActividad['nom_ent'];
		$pun_ent = $datosActividad['pun_ent'];
		
		$des_ent = $datosActividad['des_ent'];

		$ini_ent_cop = $inicio_copia;
		$fin_ent_cop = $fin_copia;

		$fec_ent = date( 'Y-m-d H:i:s' );
		
		$datos = obtenerEnterosMasterServer( $id_sub_hor, $ini_ent_cop, $fin_ent_cop );

		$ini_ent = $datos['inicio'];
		$fin_ent = $datos['fin'];

		$sql = "
			INSERT INTO entregable ( nom_ent, pun_ent, ini_ent, fin_ent, des_ent, tip_ent, fec_ent, id_blo5) 
			VALUES (		  '$nom_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$des_ent', 'Entregable', '$fec_ent', '$id_blo')
		";

		// echo $sql;

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			// LOG
			$filaDatos = obtenerDatosBloqueServer( $id_blo );
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'entregable', $nom_ent, $nombreRama );
	       
	        logServer ( 'Alta', $tipoUsuario, $id, 'Entregable', $des_log, $plantel );
	        // FIN LOG





	        // INSERCION EN COPIA Y GRUPO
	        $id_ent1 = obtenerUltimoIdentificadorServer( 'entregable', 'id_ent' ); //EXTRACCION DE CLAVE FORANEA 
			

			// EXTRACCION DE SUBHORS
	        $sqlGrupos = "
	        	SELECT *
				FROM bloque
				INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
				WHERE id_blo = '$id_blo' AND est_sub_hor = 'Activo'
	        ";


	        $resultadoGrupos = mysqli_query( $db, $sqlGrupos );

	        while( $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos ) ){
	    	
		    	// INSERCION EN COPIA Y GRUPO
		        
				$id_sub_hor3 = $filaGrupos['id_sub_hor'];

				$sqlEntregableCopia = "INSERT INTO entregable_copia(ini_ent_cop, fin_ent_cop, id_ent1, id_sub_hor3) VALUES('$ini_ent_cop', '$fin_ent_cop', '$id_ent1', '$id_sub_hor3')";
				
				$resuladoEntregableCopia = mysqli_query($db, $sqlEntregableCopia);


				if ($resuladoEntregableCopia) {


					$sqlMaximoEntregableCopia = "
						SELECT MAX(id_ent_cop) AS maximo
						FROM entregable_copia
					";

					$resultadoMaximoEntregableCopia = mysqli_query($db, $sqlMaximoEntregableCopia);


					if ($resultadoMaximoEntregableCopia) {
						$filaMaximoEntregableCopia = mysqli_fetch_assoc($resultadoMaximoEntregableCopia);

						$maximoEntregableCopia = $filaMaximoEntregableCopia['maximo'];
						$id_ent_cop = $maximoEntregableCopia;

						$sqlAlumnosActualizados = "

							SELECT *
							FROM alu_hor
							WHERE id_sub_hor5 = '$id_sub_hor3' AND est_alu_hor = 'Activo'
						";

						$resultadoAlumnosActualizados = mysqli_query($db, $sqlAlumnosActualizados);

						if ($resultadoAlumnosActualizados) {

							while($filaAlumnosActualizados = mysqli_fetch_assoc($resultadoAlumnosActualizados)){
								
								
								$id_alu_ram = $filaAlumnosActualizados['id_alu_ram1'];

								$sqlInsercionEntregables = "INSERT INTO cal_act( id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_ent_cop', '$fin_ent_cop' )";
								$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);

								if(!$resultadoInsercionEntregables){
									echo "error en insercion entregables copias";
								}





							}

							//echo "Exito";
							
						}else{
							echo "error en consulta de alu_hor";
						}




					}else{
						echo "error en extraccion de maximo entregable copia";
					}
					
					

				}else{
					echo $sqlEntregableCopia;
					// echo "error en insercion de entregable copia";
				}

		        // FIN INSERCION EN COPIA Y GRUPO

			}


			// echo "Exito";
		}else{
			echo "error, verificar consulta!";
			//echo $sql;
		}
	// FIN ENTREGABLE
	} else if ( $tipo == 'Examen' ) {
	// EXAMEN
		
		$nom_exa = $datosActividad['nom_exa'];
		$pun_exa = 0;
		
		$des_exa = $datosActividad['des_exa'];

		$ini_exa_cop = $inicio_copia;
		$fin_exa_cop = $fin_copia;

		$fec_exa = date( 'Y-m-d H:i:s' );
		
		$datos = obtenerEnterosMasterServer( $id_sub_hor, $ini_exa_cop, $fin_exa_cop );

		$ini_exa = $datos['inicio'];
		$fin_exa = $datos['fin'];
		$dur_exa = $datosActividad['dur_exa'];


		$sql = "
			INSERT INTO examen ( nom_exa, pun_exa, ini_exa, fin_exa, des_exa, tip_exa, fec_exa, dur_exa, id_blo6) 
			VALUES (		  '$nom_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$des_exa', 'Examen', '$fec_exa', '$dur_exa', '$id_blo')
		";

		// echo $sql;

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {


			// PREGUNTAS Y RESPUESTAS
			$id_exa_max = obtenerUltimoIdentificadorServer( 'examen', 'id_exa' );

            // CONSULTA DE PREGUNTAS EN TABLA pregunta
            $sqlPreguntas = "
                SELECT *
                FROM pregunta
                WHERE id_exa2 = '$identificador'
            ";

            $resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

            while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
                // DATOS DE pregunta
                $id_pre = $filaPreguntas['id_pre'];
                $pre_pre = $filaPreguntas['pre_pre'];
                $pun_pre = $filaPreguntas['pun_pre'];


                $sqlInsercionPregunta = "
                  INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
                  VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
                ";

                $resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

                if ( $resultadoInsercionPregunta ) {
                  // OBTENCION DE RESPUESTAS EN TABLA respuesta
                  $sqlMaximoRespuesta = "
                    SELECT MAX( id_pre ) AS maximo FROM pregunta
                  ";

                  $resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

                  $filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

                  $id_pre_max = $filaMaximoRespuesta['maximo'];

                  // DATOS DE respuesta
                  $sqlRespuesta = "
                    SELECT *
                    FROM respuesta
                    WHERE id_pre1 = '$id_pre'
                  ";

                  $resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

                  while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
                    // CONSULTA
                    $res_res = $filaRespuesta['res_res'];
                    $val_res = $filaRespuesta['val_res'];

                    // INSERCION

                    $sqlInsercionRespuesta = "
                      INSERT INTO respuesta ( res_res, val_res, id_pre1 )
                      VALUES ( '$res_res', '$val_res', '$id_pre_max' )
                    ";

                    $resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

                  }

                }

            }

			// FIN PREGUNTAS Y RESPUESTAS
			
			// LOG
			$filaDatos = obtenerDatosBloqueServer( $id_blo );
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'examen', $nom_exa, $nombreRama );
	       
	        logServer ( 'Alta', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
	        // FIN LOG


	        // INSERCION EN COPIA Y GRUPO
	        $id_exa1 = obtenerUltimoIdentificadorServer( 'examen', 'id_exa' ); //EXTRACCION DE CLAVE FORANEA 
			
			// EXTRACCION DE SUBHORS
	        $sqlGrupos = "
	        	SELECT *
				FROM bloque
				INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
				WHERE id_blo = '$id_blo' AND est_sub_hor = 'Activo'
	        ";
	        

	        $resultadoGrupos = mysqli_query( $db, $sqlGrupos );

	        while( $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos ) ){
	    	
		    	// INSERCION EN COPIA Y GRUPO
		        
				$id_sub_hor4 = $filaGrupos['id_sub_hor'];

				$sqlExamenCopia = "INSERT INTO examen_copia(ini_exa_cop, fin_exa_cop, id_exa1, id_sub_hor4) VALUES('$ini_exa_cop', '$fin_exa_cop', '$id_exa1', '$id_sub_hor4')";
				
				$resuladoExamenCopia = mysqli_query($db, $sqlExamenCopia);


				if ($resuladoExamenCopia) {


					$sqlMaximoExamenCopia = "
						SELECT MAX(id_exa_cop) AS maximo
						FROM examen_copia
					";

					$resultadoMaximoExamenCopia = mysqli_query($db, $sqlMaximoExamenCopia);


					if ($resultadoMaximoExamenCopia) {
						$filaMaximoExamenCopia = mysqli_fetch_assoc($resultadoMaximoExamenCopia);

						$maximoExamenCopia = $filaMaximoExamenCopia['maximo'];
						$id_exa_cop = $maximoExamenCopia;

						$sqlAlumnosActualizados = "
							SELECT *
							FROM alu_hor
							WHERE id_sub_hor5 = '$id_sub_hor4' AND est_alu_hor = 'Activo'
						";

						$resultadoAlumnosActualizados = mysqli_query($db, $sqlAlumnosActualizados);

						if ($resultadoAlumnosActualizados) {

							while($filaAlumnosActualizados = mysqli_fetch_assoc($resultadoAlumnosActualizados)){
								
								
								$id_alu_ram = $filaAlumnosActualizados['id_alu_ram1'];

								$sqlInsercionExamens = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_exa_cop', '$fin_exa_cop'  )";
								$resultadoInsercionExamens = mysqli_query($db, $sqlInsercionExamens);

								if(!$resultadoInsercionExamens){
									echo $sqlInsercionExamens." error en insercion examens copias";
								}




							}

							//echo "Exito";
							
						}else{
							echo "error en consulta de alu_hor";
						}




					}else{
						echo "error en extraccion de maximo examen copia";
					}
					
					

				}else{
					echo $sqlExamenCopia;
					// echo "error en insercion de examen copia";
				}

		        // FIN INSERCION EN COPIA Y GRUPO
			}

			// echo $id_exa_cop;
		}else{
			echo "error, verificar consulta!";
			//echo $sql;
		}



	// FIN EXAMEN
	}
	
	
?>

<hr>


<div class="row">
	<div class="col-md-12 text-center">
	<!--  -->
		<?php
			$sqlGrupos = "
		    	SELECT *
				FROM bloque
				INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
				WHERE id_blo = '$id_blo' AND est_sub_hor = 'Activo' AND id_pro1 = '$id'
		    ";


		    $resultadoGrupos = mysqli_query( $db, $sqlGrupos );

		    $resultadoTotal = mysqli_query( $db, $sqlGrupos );

		    $total = mysqli_num_rows( $resultadoTotal );

		    if ( $total == 0 ) {
		?>
				<p class="note note-success letraMediana">
					
					<i class="fas fa-check fa-2x delay-2s mb-3 animated rotateIn text-success"></i>
					
					<br>
					La actividad se ha copiado correctamente. Aparecerá con su respectiva re-calendarización cuando generes un grupo nuevo
					
				</p>
		<?php
		    } else {
		?>
				
				<p class="note note-success letraMediana">
					
					<i class="fas fa-check fa-2x delay-2s mb-3 animated rotateIn text-success"></i>
				
					<br>
					La actividad se ha copiado correctamente en el destino designado. Haz clic en las siguientes ligas si requieres modificar algunos datos ( opcional )
					
				</p>
		<?php
		    	 while( $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos ) ){

		?>

					

					<a href="clase_contenido.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>&id_blo=<?php echo $id_blo; ?>" title="<?php echo $filaGrupos['nom_gru'].' '.$filaGrupos['nom_blo'].' - '.$filaGrupos['nom_mat'].' - '.$filaGrupos['nom_ram']; ?>" target="_blank" class="btn-link">

						<?php echo $filaGrupos['nom_gru'].' '.$filaGrupos['nom_blo'].' - '.comprimirTexto( $filaGrupos['nom_mat'] ).' - '.comprimirTexto( $filaGrupos['nom_ram'] ); ?>
					
					</a>

					<br>
					<br>



		<?php
			    }

		    }

		?>
	<!--  -->
	</div>
</div>