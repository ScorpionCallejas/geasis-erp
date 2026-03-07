<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR ARCHIVO COMO RECURSO
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_arc = $_POST['nom_arc'];
	$des_arc = $_POST['des_arc'];
	$id_blo = $_GET['id_blo'];

	$fec_arc = date( 'Y-m-d H:i:s' );
	$tip_arc = 'Archivo';

	$sql = "INSERT INTO archivo (nom_arc, des_arc, fec_arc, tip_arc, id_blo3) VALUES ('$nom_arc', '$des_arc', '$fec_arc', '$tip_arc', '$id_blo')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		//RENAME Y GUARDADO DE LA FOTO DEL ALUMNO

		//EXTRACCION DEL ULTIMO ID
		$sqlMax = "SELECT MAX(id_arc) AS ultimo FROM archivo";
		$resultadoMax = mysqli_query($db, $sqlMax);

		$filaMax = mysqli_fetch_assoc($resultadoMax);
		$archivoMax = $filaMax['ultimo'];


		$arc_arc = $_FILES['arc_arc']['name'];
		$archivo = "archivo-recurso-bloque00".$archivoMax.".".end(explode(".", $arc_arc));


		$carpeta_destino = '../../uploads/';
		move_uploaded_file($_FILES['arc_arc']['tmp_name'], $carpeta_destino.$archivo);

		//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
		$sqlArchivo = "UPDATE archivo SET arc_arc = '$archivo' WHERE id_arc = '$archivoMax'";

		$resultadoArchivo = mysqli_query($db, $sqlArchivo);

		if ($resultadoArchivo) {
			
			// LOG
			$filaDatos = obtenerDatosArchivoServer( $archivoMax );
	        $nombreArchivo = $filaDatos['nom_arc'];
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'archivo', $nombreArchivo, $nombreRama );
	       

	        logServer ( 'Alta', $tipoUsuario, $id, 'Archivo', $des_log, $plantel );
	        // FIN LOG

			echo "Exito";

		}else{
			echo "error en update de archivo, verificar consulta ";
		    echo $sqlArchivo;
		}
	
	}else{
		echo "error, verificar consulta!";
		echo $sql;
	}

?>