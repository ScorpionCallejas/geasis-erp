<?php 
	//ARCHIVO VIA AJAX PARA EDITAR PROFESOR
	//perfil.php

	require('../inc/cabeceras.php');


	if ( !isset( $_FILES['fot_usu']['name'] ) ) {
		//VALIDACION SI NO MANDARON FOTO
		$pas_pro = $_POST['pas_pro'];
		$tel_pro = $_POST['tel_pro'];
		$dir_pro = $_POST['dir_pro'];

		$sqlEdicionProfesor = "

			UPDATE profesor 
			SET 
			tel_pro = '$tel_pro', 
			pas_pro = '$pas_pro', 
			dir_pro = '$dir_pro'
			WHERE 
			id_pro = '$id'
			
		";

		$resultadoEdicionProfesor = mysqli_query($db, $sqlEdicionProfesor);

		if ($resultadoEdicionProfesor) {
			echo "Exito";
		}else{
			echo "Error, verificar consulta";
			echo $sqlEdicionProfesor;
		}
		
	}else {
 
		$fot_emp = $_FILES['fot_usu']['name'];
		//BORRADO DE FOTO PASADA
		//A CONTINUACION LA VALIDACION SENALA QUE SE MANDO FOTO, PUEDE SER LA MISMA O DIFERENTE, PARA CUALQUIER CASO
		//LA ACTUAL DEBE SER ELIMINADA
		$sqlConsulta = "SELECT fot_emp FROM empleado WHERE id_emp = '$id_emp'";
		$resultadoConsulta = mysqli_query($db, $sqlConsulta);
		$filaConsulta = mysqli_fetch_assoc($resultadoConsulta);
		$fotoActual = $filaConsulta['fot_emp'];
		//echo $fotoActual;

		if ($fotoActual != NULL) {
		
			unlink("../../uploads/$fotoActual");

			$carpeta_destino = '../../uploads/';

			$fot_emp = $_FILES['fot_usu']['name'];
			$foto = "foto-profesor000".$id_emp.".".end(explode(".", $fot_emp));


			$carpeta_destino = '../../uploads/';
			$guardado = move_uploaded_file($_FILES['fot_usu']['tmp_name'], $carpeta_destino.$foto);

			//ACTUALIZACION EN EL CLIENTE DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
			$sqlUpdate = "UPDATE empleado SET fot_emp = '$foto' WHERE id_emp = '$id_emp'";

			$resultadoUpdate = mysqli_query($db, $sqlUpdate);


		} else {

			//RENAME Y GUARDADO DE LA FOTO DEL PROFESOR

			$fot_emp = $_FILES['fot_usu']['name'];
			$foto = "foto-profesor000".$id.".".end(explode(".", $fot_emp));


			$carpeta_destino = '../../uploads/';
			$guardado = move_uploaded_file($_FILES['fot_usu']['tmp_name'], $carpeta_destino.$foto);

			//ACTUALIZACION EN EL PROFESOR DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
			$sqlUpdateProfesor = "UPDATE empleado SET fot_emp = '$foto' WHERE id_emp = '$id_emp'";

			mysqli_query($db, $sqlUpdateProfesor);

		}

		
		

		if ($guardado == 1) {
			
			echo "Exito";
			
			
		}else{
			echo "error al guardar la nueva foto";
		}
	}
?>