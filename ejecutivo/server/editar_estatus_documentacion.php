<?php 
	//ARCHIVO VIA AJAX PARA EDITAR ESTATUS DE ENTREGA DE DOCUMENTACION
	//alumnos_carrera.php///obtener_alumnos_generacion.php//obtener_documentacion_alumno.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$est_doc_alu_ram = $_POST['est_doc_alu_ram'];
	$id_doc_alu_ram = $_POST['id_doc_alu_ram'];

	$filaDatos = obtenerDatosDocumentacionAlumno( $id_doc_alu_ram );
	$nombreDocumento = $filaDatos['nom_doc_ram'];
	$nombreAlumno = $filaDatos['nom_alu']." ".$filaDatos['app_alu']." ".$filaDatos['apm_alu'];
	$nombrePrograma = $filaDatos['nom_ram']; 

	// LOG      
	if ( $est_doc_alu_ram == 'Entregado' ) {

		$des_log =  obtenerDescripcionDocumentacionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'recibió', $nombreDocumento, $nombreAlumno, $nombrePrograma );


	} else if ( $est_doc_alu_ram == 'Pendiente' ) {
		
		$des_log =  obtenerDescripcionDocumentacionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'devolvió', $nombreDocumento, $nombreAlumno, $nombrePrograma );	
	}

	logServer ( 'Cambio', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );
    // FIN LOG

	$sql = "
		UPDATE documento_alu_ram
		SET
		est_doc_alu_ram = '$est_doc_alu_ram'
		WHERE 
		id_doc_alu_ram = '$id_doc_alu_ram'
	";

	//echo $sql;

	$resultado = mysqli_query( $db, $sql );

	if ( $resultado ) {
		
		


		echo "Exito";
	} else {
		echo "Error, verificar consulta";
		//echo $sql;
	}
	
?>