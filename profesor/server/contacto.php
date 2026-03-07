<?php  
	//ARCHIVO VIA AJAX PARA MANDAR MSJ A CONTACTO SELECCIONADO, VALIDACION DE USUARIO SI EXISTE SALA CREACION DE LA SALA
	//buscador_resultado.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	//***CODIGO COMPLETAMENTE REUTILIZABLE 
	//echo $id;

	$hora = date('Y-m-d H:i:s');


	if ( isset( $_FILES['arc_con']['name'] ) ) {

		// echo "arc_con";

		// ARCHIVO
		$ultimo = obtenerUltimoIdentificadorServer( 'con1', 'id_con1' );

        $arc_con = $_FILES['arc_con']['name'];
        $archivo = "archivo-adj-00".$ultimo.$id.".".end(explode(".", $arc_con));

        $carpeta_destino = '../../archivos/';
        move_uploaded_file($_FILES['arc_con']['tmp_name'], $carpeta_destino.$archivo);

        echo $archivo;

		
	} else {

		// echo "sin arc_con";
		$idDestino = $_POST['idDestino'];
		$tipoDestino = $_POST['tipoDestino'];
		$mensaje = $_POST['mensaje'];


		$sqlValidacion1 = "SELECT * FROM sala WHERE use1_sal = '$id' AND tip1_sal = '$tipo' AND use2_sal = '$idDestino'  AND tip2_sal = '$tipoDestino'  ";

		$resultadoValidacion1 = mysqli_query($db, $sqlValidacion1);
		$filaValidacion1 = mysqli_fetch_assoc($resultadoValidacion1);
		$id_sal2 = $filaValidacion1['id_sal'];

		$booleano = mysqli_num_rows($resultadoValidacion1);
		if ($booleano == 1) {


			if ( isset( $_POST['validador'] ) ) {

				$sqlCon1 = "INSERT INTO con1(use1_con1, tip1_con1, arc_con, hor_con1, id_sal2) VALUES('$id', '$tipo', '$mensaje', '$hora', '$id_sal2')";

			} else {
				$sqlCon1 = "INSERT INTO con1(use1_con1, tip1_con1, men_con1, hor_con1, id_sal2) VALUES('$id', '$tipo', '$mensaje', '$hora', '$id_sal2')";		
			}
		  
		  $resultadoCon1 = mysqli_query($db, $sqlCon1);

		  if ($resultadoCon1) {
		    echo $idDestino;
		  }else{
	        echo "Error en con1";
	      }
		}


		$sqlValidacion2 = "SELECT * FROM sala WHERE use1_sal = '$idDestino' AND tip1_sal = '$tipoDestino' AND use2_sal = '$id'  AND tip2_sal = '$tipo'  ";

		$resultadoValidacion2 = mysqli_query($db, $sqlValidacion2);  

		$filaValidacion2 = mysqli_fetch_assoc($resultadoValidacion2);
		$id_sal3 = $filaValidacion2['id_sal'];
		$booleano1 = mysqli_num_rows($resultadoValidacion2);

		if ($booleano1 == 1) {

			if ( isset( $_POST['validador'] ) ) {

				$sqlCon2 = "INSERT INTO con2(use2_con2, tip2_con2, arc_con, hor_con2, id_sal3) VALUES('$id', '$tipo', '$mensaje', '$hora', '$id_sal3')";

			} else {
				$sqlCon2 = "INSERT INTO con2(use2_con2, tip2_con2, men_con2, hor_con2, id_sal3) VALUES('$id', '$tipo', '$mensaje', '$hora', '$id_sal3')";
			}
			
		  	$resultadoCon2 = mysqli_query($db, $sqlCon2);

			if ($resultadoCon2) {
				echo $idDestino;
			}else{
				echo "Error en la con2";
			}
		}

		if ($booleano1 == 0 && $booleano == 0) {
			$sqlCrearSala = "INSERT INTO sala(use1_sal, tip1_sal, use2_sal, tip2_sal) VALUES('$id', '$tipo', '$idDestino', '$tipoDestino')";
			$resultadoCrearSala = mysqli_query($db, $sqlCrearSala);

			if ($resultadoCrearSala) {

			  $sqlMax = "SELECT MAX(id_sal) AS maximo FROM sala";
			  //echo $sqlMax;
			  $resultadoMax = mysqli_query($db, $sqlMax);

			  $filaMaximo = mysqli_fetch_assoc($resultadoMax);

			  $maximo = $filaMaximo['maximo'];


			  if ( isset( $_POST['validador'] ) ) {
			  	$sqlCon1 = "INSERT INTO con1(use1_con1, tip1_con1, arc_con, hor_con1, id_sal2) VALUES('$id', '$tipo', '$mensaje', '$hora', '$maximo')";
			  } else {
			  	$sqlCon1 = "INSERT INTO con1(use1_con1, tip1_con1, men_con1, hor_con1, id_sal2) VALUES('$id', '$tipo', '$mensaje', '$hora', '$maximo')";
			  }
			  
			  $resultadoCon1 = mysqli_query($db, $sqlCon1);

			  if ($resultadoCon1) {

			    echo "Sala creada con exito";
			  }else{
		        echo "Error en la sala";
		      }
			}



		}
	}
	














?>