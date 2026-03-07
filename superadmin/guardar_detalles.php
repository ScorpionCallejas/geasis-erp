<?php
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVOS DETALES DE NOMINA Y ACTUALIZAR NOMINA
	//nomina_detalles.php
	require('../inc/cabeceras.php');

	$bruto = $_POST['bruto'];
	$descuento = $_POST['descuento'];
	$neto = $_POST['neto'];
	$id_nom = $_GET['id_nom'];

	$arregloResultadoPercepcion = $_POST['arregloResultadoPercepcion'];
	$idConceptoPercepcion = $_POST['idConceptoPercepcion'];
	$cantidadesPercepcion = $_POST['cantidadesPercepcion'];

	$arregloResultadoDeduccion = $_POST['arregloResultadoDeduccion'];
	$idConceptoDeduccion = $_POST['idConceptoDeduccion'];
	$cantidadesDeduccion = $_POST['cantidadesDeduccion'];

	// echo $arreglo[0];

	//PERCEPCION
	for ($i = 0; $i < sizeof($arregloResultadoPercepcion); $i++) { 
		$sqlPercepcion = "INSERT INTO detalle (num_det, res_det, id_con1, id_nom1) VALUES ('$cantidadesPercepcion[$i]', '$arregloResultadoPercepcion[$i]', '$idConceptoPercepcion[$i]', '$id_nom')";

		$resultadoPercepcion = mysqli_query($db, $sqlPercepcion);
		// if ($resultadoPercepcion) {
		// 	echo "consultas exitosas";
		// }else{
		// 	echo "consultas erroneas";
		// }

	}



	//DEDUCCION
	for ($i = 0; $i < sizeof($arregloResultadoDeduccion); $i++) { 
		$sqlDeduccion = "INSERT INTO detalle (num_det, res_det, id_con1, id_nom1) VALUES ('$cantidadesDeduccion[$i]', '$arregloResultadoDeduccion[$i]', '$idConceptoDeduccion[$i]', '$id_nom')";

		$resultadoDeduccion = mysqli_query($db, $sqlDeduccion);
		// if ($resultadoDeduccion) {
		// 	echo "consultas exitosas";
		// }else{
		// 	echo "consultas erroneas";
		// }

	}



	$sqlNomina = "UPDATE nomina SET bru_nom = '$bruto', des_nom = '$descuento', net_nom = '$neto', est_nom = 'Pagado' WHERE id_nom = '$id_nom'";
	$resultadoNomina = mysqli_query($db, $sqlNomina);


	if ($resultadoNomina) {
		echo "true";
	}else{
		echo "false";
	}




	// $sql = "INSERT INTO detalle (nom_mat, cic_mat, id_ram2) VALUES ('$nom_mat', '$cic_mat', '$id_ram2')";

	// $resultado = mysqli_query($db, $sql);

	// if ($resultado) {
	// 	echo "Exito";
	// }else{
	// 	echo "error, verificar consulta!";
	// 	//echo $sql;
	// }
		
	
?>