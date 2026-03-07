<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR  PREGUNTA A EXAMEN
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$pre_pre = addslashes( $_POST['pre_pre'] );
	$pun_pre = $_POST['puntaje'];

	// $reemplazoAcentos = array(    
	// 	"'"=>'`', '"'=>'`' 
 //    );
	
	// $pre_pre = strtr( $pre_pre, $reemplazoAcentos );


	if ( isset( $_POST['estatus_examen'] ) ) {
		// EXAMEN NO CREADO
		
		$id_blo = $_POST['id_blo'];
		$id_sub_hor = $_POST['id_sub_hor'];
		$nom_exa = $_POST['nom_exa'];


		$ini_exa_cop = $_POST['ini_exa'];
	    $fin_exa_cop = $_POST['fin_exa'];
	    
	    $datos = obtenerEnterosMasterServer( $id_sub_hor, $ini_exa_cop, $fin_exa_cop );

		$ini_exa = $datos['inicio'];
		$fin_exa = $datos['fin'];

	    $dur_exa = $_POST['dur_exa'];
	    $des_exa = $_POST['descripcionExamen'];

	    $tip_exa = 'Examen';
	    $fec_exa = date( 'Y-m-d H:i:s' );

	    $sqlExamen = "
			INSERT INTO examen ( nom_exa, ini_exa, fin_exa, dur_exa, des_exa, tip_exa, fec_exa, id_blo6 )
			VALUES ( '$nom_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$des_exa', '$tip_exa', '$fec_exa', '$id_blo' )
	    ";

	    $resultadoExamen = mysqli_query( $db, $sqlExamen );

	    if ( $resultadoExamen ) {
	    	
	    	// LOG
			$filaDatos = obtenerDatosBloqueServer( $id_blo );
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'examen', $nom_exa, $nombreRama );
	       
	        logServer ( 'Alta', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
	        // FIN LOG


	        // CREACION PREGUNTA
		        $id_exa = obtenerUltimoIdentificadorServer( 'examen', 'id_exa' );

		        $sql = "INSERT INTO pregunta (pre_pre, pun_pre, id_exa2) VALUES ('$pre_pre', '$pun_pre', '$id_exa')";

				$resultado = mysqli_query($db, $sql);

				if ( $resultado ) {

					// LOG
					$filaDatos = obtenerDatosExamenServer( $id_exa );
					$nombreExamen = $filaDatos['nom_exa'];
					$nombrePrograma = $filaDatos['nom_ram'];

					$des_log =  obtenerDescripcionExamenLogServer( $tipoUsuario, $nomResponsable, 'registró', 'pregunta', $nombreExamen, $nombrePrograma );
				   
					logServer ( 'Alta', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
					// FIN LOG



					$sqlUpdatePuntaje = "
						UPDATE examen SET pun_exa = '$pun_pre' WHERE id_exa = '$id_exa';
					";

					$resultadoUpdatePuntaje = mysqli_query( $db, $sqlUpdatePuntaje );

					if ( $resultadoUpdatePuntaje ) {

						// INSERCION EN COPIA Y GRUPO
						$id_sub_hor4 = $id_sub_hor;
						$id_exa1 = $id_exa;

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


								$sqlAlumnosActualizados = "

									SELECT *
									FROM alu_hor
									WHERE id_sub_hor5 = '$id_sub_hor4'
								";

								$resultadoAlumnosActualizados = mysqli_query($db, $sqlAlumnosActualizados);

								if ($resultadoAlumnosActualizados) {

									while($filaAlumnosActualizados = mysqli_fetch_assoc($resultadoAlumnosActualizados)){
										
										$id_exa_cop = $maximoExamenCopia;
										$id_alu_ram = $filaAlumnosActualizados['id_alu_ram1'];

										$sqlInsercionExamens = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_exa_cop', '$fin_exa_cop')";
										$resultadoInsercionExamens = mysqli_query($db, $sqlInsercionExamens);

										if(!$resultadoInsercionExamens){
											echo "error en insercion examens copias";
										}

									}

									echo $id_exa_cop;
									
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
						
						

					} else {

						echo $sqlUpdatePuntaje;
					
					}

					

				} else {

					echo "error, verificar consulta!";
					//echo $sql;
				}
	        // FIN CREACION PREGUNTA

	    } else { 

	    	echo $sqlExamen;
	    
	    }


		// FIN EXAMEN NO CREADO
	} else {
		// EXAMEN CREADO

		if ( isset( $_POST['id_exa_cop'] ) ) {

			$id_exa_cop = $_POST['id_exa_cop'];
			
			$fila = obtenerDatosActividadGrupoServer( $id_exa_cop, 'Examen', 'arreglo' );
			$id_exa = $fila['identificador'];
		}
		

		$sql = "INSERT INTO pregunta (pre_pre, pun_pre, id_exa2) VALUES ('$pre_pre', '$pun_pre', '$id_exa')";

		$resultado = mysqli_query($db, $sql);

		if ( $resultado ) {

			$sqlTotalPuntaje = "
				SELECT SUM( pun_pre ) AS puntaje
				FROM pregunta
				WHERE id_exa2 = '$id_exa' 
			";

			$resultadoTotalPuntaje = mysqli_query( $db, $sqlTotalPuntaje );

			$filaTotalPuntaje = mysqli_fetch_assoc( $resultadoTotalPuntaje );

			if ( $resultadoTotalPuntaje ) {
				
				$puntaje = $filaTotalPuntaje['puntaje'];

				$sqlUpdatePuntaje = "
					UPDATE examen SET pun_exa = '$puntaje' WHERE id_exa = '$id_exa';
				";

				$resultadoUpdatePuntaje = mysqli_query( $db, $sqlUpdatePuntaje );

			} else {

				echo $sqlTotalPuntaje;
			
			}

			

			// LOG
			$filaDatos = obtenerDatosExamenServer( $id_exa );
			$nombreExamen = $filaDatos['nom_exa'];
			$nombrePrograma = $filaDatos['nom_ram'];

			$des_log =  obtenerDescripcionExamenLogServer( $tipoUsuario, $nomResponsable, 'registró', 'pregunta', $nombreExamen, $nombrePrograma );
		   
			logServer ( 'Alta', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
			// FIN LOG


			echo "Exito";
		} else {
			// echo "error, verificar consulta!";
			echo $sql;
		}

		// FIN EXAMEN CREADO
	}


	
?>