<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO ENTREGABLE
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$nom_ent = $_POST['tituloEntregable'];
	$pun_ent = $_POST['pun_ent'];
	
	$des_ent = $_POST['descripcionEntregable'];

	$id_blo = $_GET['id_blo'];

	$fec_ent = date( 'Y-m-d H:i:s' );


	$id_sub_hor = $_POST['id_sub_hor'];
	$ini_ent_cop = $_POST['ini_ent'];
	$fin_ent_cop = $_POST['fin_ent'];

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
		$id_sub_hor3 = $id_sub_hor;

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



		echo "Exito";
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>