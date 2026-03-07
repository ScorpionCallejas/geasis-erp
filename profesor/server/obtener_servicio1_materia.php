<?php
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];

	// $descarga = $_POST['descarga'];
 //    $subida = $_POST['subida'];
 //    $latencia = $_POST['latencia'];

    // $sqlUpdate = "
    //     UPDATE alumno
    //     SET
    //     dow_alu = '$descarga',
    //     upl_alu = '$subida',
    //     pin_alu = '$latencia'
    //     WHERE id_alu = '$id'
    // ";

    // $resultadoUpdate = mysqli_query( $db, $sqlUpdate );

    // if ( !$resultadoUpdate ) {
    //     echo $sqlUpdate;
    // } else {

        
    // }
	
	$sqlSala = "
		SELECT * 
		FROM sala
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = sala.id_sub_hor6
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
    	WHERE id_sub_hor = '$id_sub_hor'
	";

	// echo $sqlSala;

	$resultadoValidacionSala = mysqli_query($db, $sqlSala);

	$totalValidacionSala = mysqli_num_rows($resultadoValidacionSala);

	if ($totalValidacionSala == 0) {
		// NO EXISTE LA SALA
		//SE CREA LA SALA

		$sqlSubhor = "
			SELECT *
			FROM sub_hor
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			WHERE id_sub_hor = '$id_sub_hor'
		";


		$resultadoSubhor = mysqli_query($db, $sqlSubhor);

		if ($resultadoSubhor) {
			
			$filaSubhor = mysqli_fetch_assoc($resultadoSubhor);

			$nom_sal = "Sala de ".$filaSubhor['nom_mat'];
		
			$sqlInsercionSala = "
				INSERT INTO sala( nom_sal, id_sub_hor6, id_pla6 ) VALUES('$nom_sal', $id_sub_hor, '$plantel')
			";

			$resultadoInsercionSala = mysqli_query($db, $sqlInsercionSala);

			if ($resultadoInsercionSala) {
			// VALIDACION DE INSERCION
				$sqlMaximaSala = "
					SELECT MAX(id_sal) AS maxima 
					FROM sala
				";

				$resultadoMaximaSala = mysqli_query($db, $sqlMaximaSala);

				if ($resultadoMaximaSala) {
					// VALIDACION DE EXTRACCION DEL MAXIMO SALA
					$filaMaximaSala = mysqli_fetch_assoc($resultadoMaximaSala);

					$id_sal = $filaMaximaSala['maxima'];

					$sqlUltimaSala = "
						SELECT *
						FROM sala
						WHERE id_sal = '$id_sal'
					";

					$resultadoUltimaSala = mysqli_query($db, $sqlUltimaSala);

					if ($resultadoUltimaSala) {
						
						$filaUltimaSala = mysqli_fetch_assoc($resultadoUltimaSala);

						$nom_sal = $filaUltimaSala['nom_sal'];
					}


				}else{
					echo $sqlMaximaSala;
				}

				
			}else{
				echo $sqlInsercionSala;
			}



		}else{
			echo $sqlSubhor;
		}



	}else{
		
		$resultadoSalaMateria = mysqli_query($db, $sqlSala);

		$filaSalaMateria = mysqli_fetch_assoc($resultadoSalaMateria);

		//DATOS SALA
		$nom_sal = $filaSalaMateria['nom_sal'];
		$id_sal = $filaSalaMateria['id_sal'];


		//echo $sqlCompaneros;
	}

	$fechaHoy = date( 'Y-m-d H:i:s' );
	$des_log = "Registro de video-clase en ".$nom_sal." del profesor ".$nombreCompleto.". Registrado ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
    logServer( 'Alta', $tipoUsuario, $id, 'Video-clase', $des_log, $plantel );
	
?>



	<div class="row">
	    <div class="col-md-12 text-center">
			
	    	<div class="alert alert-dark alert-dismissible fade show font-weight-normal letraMediana" role="alert">
				<i class="fas fa-exclamation-circle warning-text fa-2x"></i>
	      		<br> 
	      		
				<strong>NOTA:</strong> 
				<!-- <p class="letraMediana grey-text "> -->
					Para evitar fallos en la transmisión es necesario cumplir con los siguientes requisitos mínimos: velocidad de bajada de 15 Mb; velocidad de subida de 5 Mb y una latencia menor a los 100 ms.

					<br>
				
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

	        <iframe src="https://letsmeet.no/<?php echo $nom_sal.$id_sub_hor.$nombrePlantel; ?>" frameborder="0" style="height: 600px; width: 100%;" allow="microphone; camera"></iframe>

	    </div>
	</div>