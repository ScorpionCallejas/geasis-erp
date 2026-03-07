<?php  

	include('inc/header.php');


	if ( isset( $_POST['id_pag'] ) ) {
	
		$id_pag = $_POST['id_pag'];
		// echo 'parcial';
	} else {
	
		$id_pag = $_GET['id_pag'];
		// echo 'completo';
	}
	
	$pagos = [];
	$sqlPagos = "
		SELECT *
		FROM vista_pagos
		WHERE id_pag = '$id_pag'
	";

	$resultadoPagos = mysqli_query( $db, $sqlPagos );

	$i = 1;

	// $filaPagos = mysqli_fetch_assoc( $resultadoPagos );
	while( $filaPagos = mysqli_fetch_assoc( $resultadoPagos ) ){

		$id_pag = $filaPagos['id_pag'];
		$id_alu_ram = $filaPagos['id_alu_ram'];


		$sqlAlumnos = "
			SELECT *
			FROM vista_alumnos
			WHERE id_alu_ram = '$id_alu_ram'
		";

		$datosAlumno = obtener_datos_consulta( $db, $sqlAlumnos )['datos'];

		

		$pagos[$filaPagos['fol_pag']]['concepto'] = $filaPagos['con_pag'];
		$pagos[$filaPagos['fol_pag']]['tipo'] = $filaPagos['tip_pag'];
		$pagos[$filaPagos['fol_pag']]['adeudo'] = $filaPagos['mon_pag'];
		$pagos[$filaPagos['fol_pag']]['cantidad'] = $filaPagos['mon_ori_pag'];

		$pagos[$filaPagos['fol_pag']]['estatus'] = $filaPagos['est_pag'];
		$pagos[$filaPagos['fol_pag']]['vencimiento'] = $filaPagos['fin_pag'];
		$pagos[$filaPagos['fol_pag']]['cobrado'] = $filaPagos['cobrado_pago'];

		$pagos[$filaPagos['fol_pag']]['id_alu_ram'] = $datosAlumno['id_alu_ram'];
		$pagos[$filaPagos['fol_pag']]['correo'] = $datosAlumno['cor_alu'];
		$pagos[$filaPagos['fol_pag']]['alumno'] = $datosAlumno['nom_alu'];
		$pagos[$filaPagos['fol_pag']]['telefono'] = $datosAlumno['tel_alu'];
		$pagos[$filaPagos['fol_pag']]['cde'] = $datosAlumno['nom_pla'];
		$pagos[$filaPagos['fol_pag']]['programa'] = $datosAlumno['nom_ram'];
		$pagos[$filaPagos['fol_pag']]['grupo'] = $datosAlumno['nom_gen'];


		// echo $pagos['Concepto'];

		$sqlAbonos = "
			SELECT *
			FROM abono_pago
			WHERE id_pag1 = '$id_pag'
		";

		$resultadoAbonos = mysqli_query( $db, $sqlAbonos );

		while( $filaAbonos = mysqli_fetch_assoc( $resultadoAbonos ) ){

			$pagos[$filaPagos['fol_pag']]['abonos'][$filaAbonos['id_abo_pag']]['tipo_pago'] = $filaAbonos['tip_abo_pag'];
			$pagos[$filaPagos['fol_pag']]['abonos'][$filaAbonos['id_abo_pag']]['cantidad'] = $filaAbonos['mon_abo_pag'];			
			$pagos[$filaPagos['fol_pag']]['abonos'][$filaAbonos['id_abo_pag']]['responsable'] = $filaAbonos['res_abo_pag'];

			$pagos[$filaPagos['fol_pag']]['abonos'][$filaAbonos['id_abo_pag']]['fecha'] = $filaAbonos['fec_abo_pag'];			
		
		}
		$i++;
	}
	
	$json = $pagos;

	$resultadoPagos = mysqli_query( $db, $sqlPagos );
	echo json_encode( $json );


?>