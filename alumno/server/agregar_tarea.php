<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR TAREA
	//entregable.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$fec_tar = date('Y-m-d H:i:s');
	$id_ent_cop1 = $_GET['id_ent_cop'];
	$id_alu_ram6 = $_GET['id_alu_ram'];


	$sql = "INSERT INTO tarea (fec_tar, id_ent_cop1, id_alu_ram6) VALUES ('$fec_tar', '$id_ent_cop1', '$id_alu_ram6')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		//RENAME Y GUARDADO

		$sqlDatosActividad = "
			SELECT *
			FROM entregable_copia 
			INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
			INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
			INNER JOIN materia ON materia.id_mat = bloque.id_mat6
			WHERE id_ent_cop = '$id_ent_cop1'
		";

		$resultadoDatosActividad = mysqli_query($db, $sqlDatosActividad);

		$filaDatosActividad = mysqli_fetch_assoc($resultadoDatosActividad);

		$materia = $filaDatosActividad['nom_mat'];
		$bloque = $filaDatosActividad['nom_blo'];
		$entregable = $filaDatosActividad['nom_ent'];
		$grupo = $filaDatosActividad['nom_gru'];
		$alumno = $nombreCompleto; 


		//EXTRACCION DEL ULTIMO ID
		$sqlMax = "SELECT MAX(id_tar) AS ultimo FROM tarea";
		$resultadoMax = mysqli_query($db, $sqlMax);

		$filaMax = mysqli_fetch_assoc($resultadoMax);
		$tareaMax = $filaMax['ultimo'];


		$doc_tar = $_FILES['doc_tar']['name'];
		$tarea = $grupo."_".$alumno."_".$entregable."_".$bloque."_".$materia."_2023_".$tareaMax;

		// 

		$reemplazoAcentos = array(    
			'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' 
        );
		
		$tarea = strtr( $tarea, $reemplazoAcentos );
		
		$tarea = mysqli_real_escape_string( $db, $tarea );  
		$tarea = htmlentities( $tarea );
		

		function php_slug( $string ) {  
			$slug = preg_replace( '/[^a-z0-9-]+/', '-', trim( strtolower( $string ) ) );  
			return $slug;  
		}

		$tarea = php_slug( $tarea );

		$tarea = $tarea.".".end(explode(".", $doc_tar));

		$carpeta_destino = '../../uploads/';
		
		if (strlen($tarea) > 250 ) {
		    $tarea = substr($tarea, strlen($tarea) - 50, strlen($tarea) );
		}
		
		move_uploaded_file($_FILES['doc_tar']['tmp_name'], $carpeta_destino.$tarea);

		// if ( sizeof( $tarea ) > 256 ) {
				
		// } else {

		// }
		
		// FIN VALIDACION EXISTENCIA ARCHIVO

		//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
		$sqlArchivo = "UPDATE tarea SET doc_tar = '$tarea' WHERE id_tar = '$tareaMax'";

		$resultadoArchivo = mysqli_query($db, $sqlArchivo);

		if ($resultadoArchivo) {

			$sqlUpdate = "UPDATE cal_act SET fec_cal_act = NOW() WHERE id_ent_cop2 = '$id_ent_cop1' AND id_alu_ram4 = '$id_alu_ram6'";

			$resultadoUpdate = mysqli_query($db, $sqlUpdate);

			if ( $resultadoUpdate ) {
				// echo "Exito";

				$estatus_tarea = obtener_existencia_tarea_server( $tareaMax, $id_alu_ram6, $id_ent_cop1 );

				if ( $estatus_tarea == 'Error' ) {
				
					echo 'Error';
				
				} else {

					echo "Exito";	
				
				}
				

			}else{
				echo "Error en update";
			}
		

			
		}else{
			echo "error en update de tarea, verificar consulta ";
			//	echo $sqlArchivo;
		}
	
	}else{
		echo "error, verificar consulta!";
		//echo $sql;
	}

?>