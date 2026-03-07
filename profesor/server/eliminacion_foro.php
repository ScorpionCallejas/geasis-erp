<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR FOROS ANEXADAS A BLOQUES
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	if ( isset( $_POST['id_for'] ) ) {
		
		$id_for = $_POST['id_for'];

		// LOG
		$filaDatos = obtenerDatosActividadServer( 'Foro', $id_for );
	    $nombreRama = $filaDatos['nom_ram'];
	    $nom_for = $filaDatos['nom_for'];

	    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'foro', $nom_for, $nombreRama );
	   
	    logServer ( 'Baja', $tipoUsuario, $id, 'Foro', $des_log, $plantel );
	    // FIN LOG

		$sql = "DELETE FROM foro WHERE id_for = '$id_for'";
		$resultado = mysqli_query($db, $sql);

		if ($resultado) {




			echo "true";
		}else{
			echo "error, verificar consulta";
			//echo $sql;
		}

	} else {

		$id_for_cop = $_POST['id_for_cop'];

		$fila = obtenerDatosActividadGrupoServer( $id_for_cop, 'Foro', 'arreglo' );
		$id_for = $fila['identificador'];
		
		// LOG
		$filaDatos = obtenerDatosActividadServer( 'Foro', $id_for );
	    $nombreRama = $filaDatos['nom_ram'];
	    $nom_for = $filaDatos['nom_for'];

	    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'foro copia', $nom_for, $nombreRama );
	   
	    logServer ( 'Baja', $tipoUsuario, $id, 'Foro copia', $des_log, $plantel );
	    // FIN LOG

		$sql = "
			DELETE FROM foro_copia WHERE id_for_cop = '$id_for_cop'
		";
		$resultado = mysqli_query($db, $sql);

		if ( $resultado ) {

			echo "true";

		}else{
			
			echo "error, verificar consulta";
			//echo $sql;
		}

	}

	


?>