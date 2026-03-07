<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR TRABAJO ESPECIAL
	//trabajos_especiales.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_pro = $_POST['nom_pro'];
	$des_pro = $_POST['des_pro'];

	$ini_pro = $_POST['ini_pro'];
	$fin_pro = $_POST['fin_pro'];

	$pun_pro = $_POST['pun_pro'];

	$id_gru2 = $_POST['id_gru'];

	$sql = "
		
		INSERT INTO proyecto ( nom_pro, des_pro, pun_pro, ini_pro, fin_pro, id_gru2 ) 
		VALUES ( '$nom_pro', '$des_pro', '$pun_pro', '$ini_pro', '$fin_pro', '$id_gru2' )

	";

	$resultado = mysqli_query( $db, $sql );

	if ( $resultado ) {

		//RENAME Y GUARDADO DE LA FOTO DEL ALUMNO

		//EXTRACCION DEL ULTIMO ID
		$sqlMax = "SELECT MAX(id_pro) AS ultimo FROM proyecto";
		$resultadoMax = mysqli_query($db, $sqlMax);

		$filaMax = mysqli_fetch_assoc($resultadoMax);
		$proyectoMax = $filaMax['ultimo'];


		$arc_pro = $_FILES['arc_pro']['name'];
		$proyecto = "proyecto-recurso-programa00".$proyectoMax.".".end(explode(".", $arc_pro));


		$carpeta_destino = '../../archivos/';
		move_uploaded_file($_FILES['arc_pro']['tmp_name'], $carpeta_destino.$proyecto);

		//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
		$sqlArchivo = "UPDATE proyecto SET arc_pro = '$proyecto' WHERE id_pro = '$proyectoMax'";

		$resultadoArchivo = mysqli_query($db, $sqlArchivo);

		if ($resultadoArchivo) {
			
			// LOG
			$filaDatos = obtenerDatosArchivoServer( $proyectoMax );
	        $nombreArchivo = $nom_pro;
	        $nombreRama = $filaDatos['nom_ram'];

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'proyecto', $nombreArchivo, $nombreRama );
	       

	        logServer ( 'Alta', $tipoUsuario, $id, 'Archivo', $des_log, $plantel );
	        // FIN LOG



	        // VALIDACION SI EXISTEN ALUMNOS PARA CARGA ATEMPORAL DE TRABAJOS ESPECIALES

	        $sqlAlumnos = "
	        	SELECT * 
				FROM alu_hor
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
				INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
				WHERE id_gru = '$id_gru2' AND est_alu_hor = 'Activo'
				GROUP BY id_alu_ram1
	        ";

	        $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

	        $ini_pro_alu_ram = $ini_pro;
			$fin_pro_alu_ram = $fin_pro;

			$id_pro = $proyectoMax;


	        while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

	        	$id_alu_ram = $filaAlumnos['id_alu_ram1'];

	        	$sqlInsercionProyectos = "
					
					INSERT INTO proyecto_alu_ram ( ini_pro_alu_ram, fin_pro_alu_ram, est_pro_alu_ram, pro_pro_alu_ram, id_alu_ram15, id_gru3, id_pro1 ) 
					VALUES ( '$ini_pro_alu_ram', '$fin_pro_alu_ram', 'Inactivo', 'Pendiente', '$id_alu_ram', '$id_gru2', '$id_pro' )
				
				";

				$resultadoInsercionProyectos = mysqli_query( $db, $sqlInsercionProyectos );

				if ( !$resultadoInsercionProyectos ) {
					
					echo $sqlInsercionProyectos;

				}

	        }


	        

			
	        // FIN VALIDACION SI EXISTEN ALUMNOS PARA CARGA ATEMPORAL DE TRABAJOS ESPECIALES 




			echo "Exito";

		}else{
			echo "error en update de proyecto, verificar consulta ";
		    echo $sqlArchivo;
		}
	
	}else{
		echo "error, verificar consulta!";
		echo $sql;
	}

?>