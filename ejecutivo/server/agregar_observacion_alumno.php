<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR OBSERVACION ALUMNO
	//alumnos.php  // server/obtener_observaciones_alumno.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$obs_obs_alu_ram = $_POST['obs_obs_alu_ram'];
	$id_alu_ram16 = $_POST['id_alu_ram'];

	$res_obs_alu_ram = $nomResponsable;

	$sql = "
		INSERT INTO observacion_alu_ram ( obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram ) 
		VALUES ( '$obs_obs_alu_ram', '$id_alu_ram16', '$res_obs_alu_ram' )
	";

	$resultado = mysqli_query( $db, $sql );

	if ( $resultado ) {

		echo "Exito";
	
	} else {
		
		// echo "error, verificar consulta!";
		echo $sql;
	}
		
	
?>