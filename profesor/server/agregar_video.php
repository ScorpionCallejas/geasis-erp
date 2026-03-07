<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR VIDEO COMO RECURSO
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	//VALIDACION SI SE MANDA URL O ARCHIVO
	$fec_vid = date( 'Y-m-d H:i:s' );
	$tip_vid = 'Video';
	if (isset($_POST['url_vid'])) {
		$nom_vid = $_POST['nom_vid'];
		$des_vid = $_POST['des_vid'];
		$url_vid = $_POST['url_vid'];
		$id_blo = $_GET['id_blo'];

		

		$sql = "INSERT INTO video (nom_vid, des_vid, url_vid, fec_vid, tip_vid, id_blo1) VALUES ('$nom_vid', '$des_vid', '$url_vid', '$fec_vid', '$tip_vid', '$id_blo')";

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			// LOG
			$filaDatos = obtenerDatosBloqueServer( $id_blo );
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'video', $nom_vid, $nombreRama );
	       
	        logServer ( 'Alta', $tipoUsuario, $id, 'Video', $des_log, $plantel );
	        // FIN LOG

			echo "Exito";
		}else{
			echo "error, verificar consulta!";
			//echo $sql;
		}
	
	}else{
		$nom_vid = $_POST['nom_vid'];
		$des_vid = $_POST['des_vid'];
		$id_blo = $_GET['id_blo'];

		$sql = "INSERT INTO video (nom_vid, des_vid, fec_vid, tip_vid, id_blo1) VALUES ('$nom_vid', '$des_vid', '$fec_vid', '$tip_vid', '$id_blo')";

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {

			//RENAME Y GUARDADO DE LA FOTO DEL ALUMNO

			//EXTRACCION DEL ULTIMO ID
			$sqlMax = "SELECT MAX(id_vid) AS ultimo FROM video";
			$resultadoMax = mysqli_query($db, $sqlMax);

			$filaMax = mysqli_fetch_assoc($resultadoMax);
			$videoMax = $filaMax['ultimo'];


			$vid_vid = $_FILES['vid_vid']['name'];
			$video = "video-recurso-bloque00".$videoMax.".".end(explode(".", $vid_vid));


			$carpeta_destino = '../../uploads/';
			move_uploaded_file($_FILES['vid_vid']['tmp_name'], $carpeta_destino.$video);

			//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
			$sqlVideo = "UPDATE video SET vid_vid = '$video' WHERE id_vid = '$videoMax'";

			$resultadoVideo = mysqli_query($db, $sqlVideo);

			if ($resultadoVideo) {
				

				// LOG
				$filaDatos = obtenerDatosBloqueServer( $id_blo );
		        $nombreRama = $filaDatos['nom_ram'];

		        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'video', $nom_vid, $nombreRama );
		       
		        logServer ( 'Alta', $tipoUsuario, $id, 'Video', $des_log, $plantel );
		        // FIN LOG
		        
				echo "Exito";
			}else{
				echo "error en update de video, verificar consulta ";
				//	echo $sqlVideo;
			}
		
		}else{
			echo "error, verificar consulta!";
			//echo $sql;
		}
	}
	

?>