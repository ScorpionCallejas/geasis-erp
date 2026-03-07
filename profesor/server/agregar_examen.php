<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO EXAMEN
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$nom_exa = $_POST['nom_exa'];
	$pun_exa = 0;
	$dur_exa = $_POST['dur_exa'];

	$des_exa = $_POST['descripcionExamen'];

	$id_blo = $_POST['id_blo'];

	$fec_exa = date( 'Y-m-d H:i:s' );


	$id_sub_hor = $_POST['id_sub_hor'];
	$ini_exa_cop = $_POST['ini_exa'];
	$fin_exa_cop = $_POST['fin_exa'];


	$datos = obtenerEnterosMasterServer( $id_sub_hor, $ini_exa_cop, $fin_exa_cop );

	$ini_exa = $datos['inicio'];
	$fin_exa = $datos['fin'];

	$sql = "
		INSERT INTO examen ( nom_exa, pun_exa, ini_exa, fin_exa, des_exa, tip_exa, fec_exa, dur_exa, id_blo6) 
		VALUES (		  '$nom_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$des_exa', 'Examen', '$fec_exa', '$dur_exa', '$id_blo')
	";

	// echo $sql;

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		
		// LOG
		$filaDatos = obtenerDatosBloqueServer( $id_blo );
        $nombreRama = $filaDatos['nom_ram'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'examen', $nom_exa, $nombreRama );
       
        logServer ( 'Alta', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
        // FIN LOG


        // INSERCION EN COPIA Y GRUPO
        $id_exa1 = obtenerUltimoIdentificadorServer( 'examen', 'id_exa' ); //EXTRACCION DE CLAVE FORANEA 
		$id_sub_hor4 = $id_sub_hor;

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


						// echo $sqlInsercionExamens;

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

		echo $id_exa_cop;
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>