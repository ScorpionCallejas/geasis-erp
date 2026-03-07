<?php  
	//ARCHIVO VIA AJAX PARA MANDAR MSJ A CONTACTO SELECCIONADO, VALIDACION DE USUARIO SI EXISTE SALA CREACION DE LA SALA
	//foro.php/entregable.php/examen.php
	require('../inc/cabeceras.php');

	//***CODIGO COMPLETAMENTE REUTILIZABLE 
	//echo $id;

	$idDestino = $_POST['idDestino'];
	$tipoDestino = $_POST['tipoDestino'];


	$sqlValidacion1 = "SELECT * FROM sala WHERE use1_sal = '$id' AND tip1_sal = '$tipo' AND use2_sal = '$idDestino'  AND tip2_sal = '$tipoDestino'  ";

	$resultadoValidacion1 = mysqli_query($db, $sqlValidacion1);
	$filaValidacion1 = mysqli_fetch_assoc($resultadoValidacion1);
	$id_sal2 = $filaValidacion1['id_sal'];

	$booleano = mysqli_num_rows($resultadoValidacion1);



	$sqlValidacion2 = "SELECT * FROM sala WHERE use1_sal = '$idDestino' AND tip1_sal = '$tipoDestino' AND use2_sal = '$id'  AND tip2_sal = '$tipo'  ";

	$resultadoValidacion2 = mysqli_query($db, $sqlValidacion2);  

	$filaValidacion2 = mysqli_fetch_assoc($resultadoValidacion2);
	$id_sal3 = $filaValidacion2['id_sal'];
	$booleano1 = mysqli_num_rows($resultadoValidacion2);


	if ($booleano1 == 0 && $booleano == 0) {
		echo "falso";


	}else{

		if ($booleano == 1) {
			echo $id_sal2;
		}else{
			echo $id_sal3;
		}
		// echo "verdadero";

	}

?>