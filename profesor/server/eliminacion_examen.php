<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR EXAMENES ANEXADAS A BLOQUES
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	if ( isset( $_POST['id_exa'] ) ) {
		

		$id_exa = $_POST['id_exa'];
		// LOG


		$filaDatos = obtenerDatosActividadServer( 'Examen', $id_exa );
	    $nombreRama = $filaDatos['nom_ram'];
	    $nom_exa = $filaDatos['nom_exa'];

	    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'examen', $nom_exa, $nombreRama );
	   
	    logServer ( 'Baja', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
	    // FIN LOG


		$sql = "DELETE FROM examen WHERE id_exa = '$id_exa'";
		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			

			echo "true";
		}else{
			echo "error, verificar consulta";
			//echo $sql;
		}

	} else {

		$id_exa_cop = $_POST['id_exa_cop'];

		$fila = obtenerDatosActividadGrupoServer( $id_exa_cop, 'Examen', 'arreglo' );
		$id_exa = $fila['identificador'];

		// LOG
		$filaDatos = obtenerDatosActividadServer( 'Examen', $id_exa );
	    $nombreRama = $filaDatos['nom_ram'];
	    $nom_exa = $filaDatos['nom_exa'];

	    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'examen', $nom_exa, $nombreRama );
	   
	    logServer ( 'Baja', $tipoUsuario, $id, 'Examen', $des_log, $plantel );
	    // FIN LOG


		$sql = "DELETE FROM examen WHERE id_exa = '$id_exa'";
		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			

			echo "true";
		}else{
			echo "error, verificar consulta";
			//echo $sql;
		}

	}


	


?>