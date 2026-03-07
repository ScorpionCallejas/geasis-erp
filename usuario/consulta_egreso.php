<?php  

	include('inc/header.php');

	
	$id_egr = $_GET['id_egr'];
	
	$sql = "
		SELECT *
		FROM vista_egresos
		INNER JOIN plantel ON plantel.id_pla = vista_egresos.id_pla13
		WHERE id_egr = '$id_egr'
	";

	$datos = obtener_datos_consulta( $db, $sql )['datos'];

	$json = array();

	$json[$datos['id_egr']] = array( 
		'monto' => $datos['mon_egr'],
		'concepto' => $datos['con_egr'],
		'tipo' => $datos['tip_egr'],
		'fecha' => $datos['fec_egr'],
		'solicitante' => $datos['res_egr'],
		'otorgante' => $datos['res2_egr'],
		'cde' => $datos['nom_pla']
	);

	


	
	echo json_encode( $json );


?>