<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR BAJA ALUMNO
	//alumnos.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram_baja_alumno'];
	$mot_ing_alu_ram = $_POST['mot_ing_alu_ram'];
	$res_ing_alu_ram = $nomResponsable.' - '.$tipoUsuario;
	$datos = obtenerDatosAlumnoProgramaServer( $id_alu_ram );

	$ing_alu = $datos['ing_alu'];
	$ori_ing_alu_ram = $ing_alu;

	$tip_ing_alu_ram = $_POST['tip_ing_alu_ram'];

	if ( $tip_ing_alu_ram == 'Baja definitiva' ) {
	// BAJA DEFINITIVA

		$sql = "

			INSERT INTO ingreso_alu_ram ( tip_ing_alu_ram, id_alu_ram14, mot_ing_alu_ram, res_ing_alu_ram, ori_ing_alu_ram ) 
			VALUES ( 'Baja definitiva', '$id_alu_ram', '$mot_ing_alu_ram', '$res_ing_alu_ram', '$ori_ing_alu_ram'  )
		
		";


		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {


			$sqlUpdate2 = "
				UPDATE alu_ram
				SET
				est1_alu_ram = 'Baja definitiva'
				WHERE
				id_alu_ram = '$id_alu_ram'
			";

			$resultadoUpdate2 = mysqli_query( $db, $sqlUpdate2 );

			if ( $resultadoUpdate2 ) {
				
				echo 'Exito';

			} else  {
				
				$sqlUpdate2;

			}


		}else{
			echo "error, verificar consulta!";
			echo $sql;
		}

	// FIN BAJA DEFINITIVA
	} else {
	// REINGRESO

		$sql = "

			INSERT INTO ingreso_alu_ram ( tip_ing_alu_ram, id_alu_ram14, mot_ing_alu_ram, res_ing_alu_ram, ori_ing_alu_ram ) 
			VALUES ( 'Reingreso', '$id_alu_ram', '$mot_ing_alu_ram', '$res_ing_alu_ram', '$ori_ing_alu_ram'  )
		
		";


		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {


			$sqlUpdate2 = "
				UPDATE alu_ram
				SET
				est1_alu_ram = 'Reingreso'
				WHERE
				id_alu_ram = '$id_alu_ram'
			";

			$resultadoUpdate2 = mysqli_query( $db, $sqlUpdate2 );

			if ( $resultadoUpdate2 ) {

				
				$id_alu = $datos['id_alu'];
				$fechaHoy = date('Y-m-d');

				$sqlUpdate3 = "
					UPDATE alumno
					SET
					ing_alu = '$fechaHoy'
					WHERE
					id_alu = '$id_alu'
				";

				$resultadoUpdate3 = mysqli_query( $db, $sqlUpdate3 );

				if ( $resultadoUpdate3 ) {
				
					echo 'Exito';

				} else {

					echo $sqlUpdate3;
				
				}

			} else  {
				
				$sqlUpdate2;

			}


		}else{
			echo "error, verificar consulta!";
			echo $sql;
		}
	// FIN REINGRESO
	}
	
		
	
?>