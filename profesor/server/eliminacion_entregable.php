<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR ENTREGABLES ANEXADAS A BLOQUES
	//bloque_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	if ( isset( $_POST['id_ent'] ) ) {
		
		$id_ent = $_POST['id_ent'];

		// LOG
		$filaDatos = obtenerDatosActividadServer( 'Entregable', $id_ent );
	    $nombreRama = $filaDatos['nom_ram'];
	    $nom_ent = $filaDatos['nom_ent'];

	    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'entregable', $nom_ent, $nombreRama );
	   
	    logServer ( 'Baja', $tipoUsuario, $id, 'Entregable', $des_log, $plantel );
	    // FIN LOG

		$sql = "DELETE FROM entregable WHERE id_ent = '$id_ent'";
		$resultado = mysqli_query($db, $sql);

		if ($resultado) {

			echo "true";
			
		}else{
			echo "error, verificar consulta";
			//echo $sql;
		}


	} else {


		$id_ent_cop = $_POST['id_ent_cop'];

		$fila = obtenerDatosActividadGrupoServer( $id_ent_cop, 'Entregable', 'arreglo' );
		$id_ent = $fila['identificador'];
		
		// LOG
		$filaDatos = obtenerDatosActividadServer( 'Entregable', $id_ent );
	    $nombreRama = $filaDatos['nom_ram'];
	    $nom_ent = $filaDatos['nom_ent'];

	    $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'eliminó', 'entregable copia', $nom_ent, $nombreRama );
	   
	    logServer ( 'Baja', $tipoUsuario, $id, 'Entregable copia', $des_log, $plantel );
	    // FIN LOG

		$sql = "
			DELETE FROM entregable_copia WHERE id_ent_cop = '$id_ent_cop'
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