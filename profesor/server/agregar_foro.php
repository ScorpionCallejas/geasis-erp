<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO FORO
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$nom_for = $_POST['tituloForo'];
	$pun_for = $_POST['pun_for'];
	
	$des_for = $_POST['descripcionForo'];

	$id_blo = $_GET['id_blo'];

	$fec_for = date( 'Y-m-d H:i:s' );


	$id_sub_hor = $_POST['id_sub_hor'];
	$ini_for_cop = $_POST['ini_for'];
	$fin_for_cop = $_POST['fin_for'];

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

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'foro', $nom_for, $nombreRama );
       
        logServer ( 'Alta', $tipoUsuario, $id, 'Foro', $des_log, $plantel );
        // FIN LOG



        // INSERCION EN COPIA Y GRUPO
        $id_for1 = obtenerUltimoIdentificadorServer( 'foro', 'id_for' ); //EXTRACCION DE CLAVE FORANEA 
		$id_sub_hor2 = $id_sub_hor;

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



		echo "Exito";
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}
		
	
?>