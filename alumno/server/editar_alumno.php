<?php 
	//ARCHIVO VIA AJAX PARA EDITAR ALUMNO
	//perfil.php

	require('../inc/cabeceras.php');


	if ( !isset( $_FILES['fot_usu']['name'] ) ) {
		//VALIDACION SI NO MANDARON FOTO
		$pas_alu = $_POST['pas_alu'];
		$tel_alu = $_POST['tel_alu'];
		$dir_alu = $_POST['dir_alu'];
		$col_alu = $_POST['col_alu'];
		$del_alu = $_POST['del_alu'];
		$ent_alu = $_POST['ent_alu'];

		$sqlEdicionAlumno = "

			UPDATE alumno 
			SET 
			tel_alu = '$tel_alu', 
			pas_alu = '$pas_alu', 
			dir_alu = '$dir_alu', 
			col_alu = '$col_alu',
			del_alu = '$del_alu',
			ent_alu = '$ent_alu'
			WHERE 
			id_alu = '$id'
			
		";

		$resultadoEdicionAlumno = mysqli_query($db, $sqlEdicionAlumno);

		if ($resultadoEdicionAlumno) {
			echo "Exito";
		}else{
			echo "Error, verificar consulta";
			//echo $sqlEdicionAlumno;
		}
		
	}else {
 
		$fot_alu = $_FILES['fot_usu']['name'];
		//BORRADO DE FOTO PASADA
		//A CONTINUACION LA VALIDACION SENALA QUE SE MANDO FOTO, PUEDE SER LA MISMA O DIFERENTE, PARA CUALQUIER CASO
		//LA ACTUAL DEBE SER ELIMINADA
		$sqlConsulta = "SELECT fot_alu FROM alumno WHERE id_alu = '$id'";
		$resultadoConsulta = mysqli_query($db, $sqlConsulta);
		$filaConsulta = mysqli_fetch_assoc($resultadoConsulta);
		$fotoActual = $filaConsulta['fot_alu'];
		//echo $fotoActual;

		if ($fotoActual != NULL) {
		
			unlink("../../uploads/$fotoActual");

			$carpeta_destino = '../../uploads/';
			$guardado = move_uploaded_file($_FILES['fot_usu']['tmp_name'], $carpeta_destino.$fotoActual);

		} else {

			//RENAME Y GUARDADO DE LA FOTO DEL ALUMNO

			$fot_alu = $_FILES['fot_usu']['name'];
			$foto = "foto-alumno000".date('i-s').$id.".".end(explode(".", $fot_alu));


			$carpeta_destino = '../../uploads/';
			$guardado = move_uploaded_file($_FILES['fot_usu']['tmp_name'], $carpeta_destino.$foto);

			//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
			$sqlUpdateAlumno = "UPDATE alumno SET fot_alu = '$foto' WHERE id_alu = '$id'";

			mysqli_query($db, $sqlUpdateAlumno);

		}

		
		

		if ($guardado == 1) {
			
			echo "Exito";
			
			
		}else{
			echo "error al guardar la nueva foto";
		}
	}
?>