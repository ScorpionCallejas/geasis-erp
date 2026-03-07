<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR PAGOS DE ALUMNO
	//pagos_alumno.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pag = $_POST['pago'];


	$datos = obtenerDatosPagoAlumnoServer( $id_pag );
	$id_alu_ram = $datos['id_alu_ram10'];
	
	$con_pag = $datos['con_pag'];
	$mon_pag = $datos['mon_pag'];

	// LOG
	$nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );

	// el administrador juan zarate registro un cobro por concepto: colegiatura 2, por la cantidad de $1500, a Pedrito Sola. fecha...
	$des_log =  obtenerDescripcionPagoAlumnoLogServer( $tipoUsuario, $nomResponsable, 'eliminó', $con_pag, $mon_pag, $nombreAlumno );

	// el administrador juan zarate registro un cobro para el ciclo escolar : abril - julio por concepto: colegiatura abril y cantidad: $1500. Programa: evaluacion unica. fecha...
	logServer ( 'Baja', $tipoUsuario, $id, 'Cobro', $des_log, $plantel );
	// FIN LOG

	$sql = "DELETE FROM pago WHERE id_pag ='$id_pag'";
	$resultado = mysqli_query($db, $sql);


	if ($resultado) {
		// logServer ( 'Baja', $tipoUsuario, $id, 'Pago', $plantel );

		


		echo "true";
	}else{
		//echo $sql;
		echo "false";
	}


?>