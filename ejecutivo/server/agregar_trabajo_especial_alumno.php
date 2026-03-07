<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR HORARIO
	//inscripcion.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	// $id_alu_ram = $_GET['id_alu_ram'];
	// $id_sub_hor = $_POST['sub_hor'];

	// // PENDIENTE VALIDAR SI EXISTE MAS DE UN id_sub_hor ASOCIADO A MAS GRUPOS PARA TEMA DE TRABAJOS ESPECIALES
	// // echo 'heee';
	// $sql = "
	// 	SELECT *
	// 	FROM sub_hor
	// 	WHERE id_sub_hor = '$id_sub_hor[0]'
	// ";

	// // echo $sql;

	// $resultado = mysqli_query( $db, $sql );

	// $fila = mysqli_fetch_assoc( $resultado );

	// $id_gru = $fila['id_gru1'];



	// $sqlProyectos = "
	// 	SELECT *
	// 	FROM proyecto
	// 	WHERE id_gru2 = '$id_gru'
	// ";

	// // echo $sqlProyectos;

	// $resultadoProyectos = mysqli_query( $db, $sqlProyectos );

	// while( $filaProyectos = mysqli_fetch_assoc( $resultadoProyectos ) ) {
		
	// 	$ini_pro_alu_ram = $filaProyectos['ini_pro'];
	// 	$fin_pro_alu_ram = $filaProyectos['fin_pro'];

	// 	$id_pro = $filaProyectos['id_pro'];

	// 	$sqlInsercionProyectos = "
			
	// 		INSERT INTO proyecto_alu_ram ( ini_pro_alu_ram, fin_pro_alu_ram, est_pro_alu_ram, pro_pro_alu_ram, id_alu_ram15, id_gru3, id_pro1 ) 
	// 		VALUES ( '$ini_pro_alu_ram', '$fin_pro_alu_ram', 'Inactivo', 'Pendiente', '$id_alu_ram', '$id_gru', '$id_pro' )
		
	// 	";

	// 	$resultadoInsercionProyectos = mysqli_query( $db, $sqlInsercionProyectos );

	// 	if ( !$resultadoInsercionProyectos ) {
	// 		echo $sqlInsercionProyectos;
	// 	} else {
	// 		echo 'exito';
	// 	}
	
	// }



?>