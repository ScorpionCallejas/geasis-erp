<?php  
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];

	// $descarga = $_POST['descarga'];
 //    $subida = $_POST['subida'];
 //    $latencia = $_POST['latencia'];

 //    // $sqlUpdate = "
 //    //     UPDATE alumno
 //    //     SET
 //    //     dow_alu = '$descarga',
 //    //     upl_alu = '$subida',
 //    //     pin_alu = '$latencia'
 //    //     WHERE id_alu = '$id'
 //    // ";

 //    // $resultadoUpdate = mysqli_query( $db, $sqlUpdate );

 //    // if ( !$resultadoUpdate ) {
 //    //     echo $sqlUpdate;
 //    // } else {

 //        $des_log = obtenerDescripcionInternetUsuarioLogServer( $tipo, $nombreCompleto, $descarga, $subida, $latencia  );
 //        logServer( 'Cambio', $tipoUsuario, $id, 'Internet', $des_log, $plantel );
 //    // }
	
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
		<div class="col-md-12 text-center" style="width: 100%; height: 600px;">
			<div id="meet">
				
			</div>
		</div>
	</div>


<script>

	// console.log( 'js de video sala' );
	var sala = '<?php echo $nom_sal.$id_sub_hor.$nombrePlantel; ?>';

    var domain = '<?php echo $dominioVideo; ?>';
    var options = {
        roomName: sala,
        height: 600,
        parentNode: document.querySelector('#meet'),
        userInfo: {
            email: '<?php echo $correoUsuario; ?>',
            displayName: '<?php echo $nombreUsuario; ?>'
        },

        configOverwrite: { 

            defaultLanguage: 'es',
            remoteVideoMenu: {
                disableKick: true
            },
            // PROFESOR
            disableAudioLevels: true




        },

        interfaceConfigOverwrite: { 
     	
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            SHOW_BRAND_WATERMARK: false,
            DEFAULT_REMOTE_DISPLAY_NAME: 'Cargando...',
            DEFAULT_LOCAL_DISPLAY_NAME: 'Yo',
            SET_FILMSTRIP_ENABLED: false,
            DISABLE_FOCUS_INDICATOR: true,
            DISABLE_DOMINANT_SPEAKER_INDICATOR: true,
            DISABLE_VIDEO_BACKGROUND: true

            // JITSI_WATERMARK_LINK: 'https://google.com',
        }





    };

    var api = new JitsiMeetExternalAPI(domain, options);


    setTimeout( function(){

		$( '#meet' ).css({
			position: 'relative'
		});
        $( '#meet' ).append( '<img src="../uploads/<?php echo $fotoPlantel; ?>" width="10%" style="position: absolute;  right: 85%;  top: 3%; opacity: .3;">' );

        
    }, 7000);

</script>