<?php  
    // CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    // header('Content-Type: application/json');
	if( isset( $_POST['id_cit'] ) ){
		$id_cit = $_POST['id_cit'];
		$sql = "
			DELETE FROM alumno WHERE id_cit1 = '$id_cit'
		";

		$resultado = mysqli_query( $db, $sql );

		$response = array();

		if ($resultado) {
			// La actualización se realizó con éxito
			$response['status'] = 200; // Código de estado HTTP 200 (OK)
			$response['message'] = "Actualización exitosa";
		} else {
			// Hubo un error en la actualización
			$response['status'] = 500; // Código de estado HTTP 500 (Error interno del servidor)
			$response['message'] = "Error en la actualización";
			$response['query'] = $sql; // Incluye la consulta SQL en caso de error
		}

		echo json_encode($response);

	}else if( isset( $_POST['obtener_alumno'] ) ){
		/// CONSULTA DE ALUMNO X id_alu_ram
		$id_alu_ram = $_POST['id_alu_ram'];

		$sql = "
			SELECT * 
			FROM alumno
			INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
			WHERE id_alu_ram = $id_alu_ram
		";
	
		$resultado = mysqli_query($db, $sql);

		$datos = mysqli_fetch_assoc($resultado);

		echo json_encode($datos);
		/// FIN CONSULTA DE ALUMNO X id_alu_ram
	} else if( isset( $_POST['eliminar_alumno'] ) ) {
		// ELIMINACION DE ALUMNO
		$id_alu_ram = $_POST['id_alu_ram'];

		$sqlAlumno = "SELECT * FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
		$id_alu = obtener_datos_consulta( $db, $sqlAlumno )['datos']['id_alu1'];

		$sql = "DELETE FROM alumno WHERE id_alu = '$id_alu' ";
		$resultado = mysqli_query($db, $sql);

		$response = array();

		if ($resultado) {
			// La actualización se realizó con éxito
			$response['status'] = 200; // Código de estado HTTP 200 (OK)
			$response['message'] = "Actualización exitosa";
		} else {
			// Hubo un error en la actualización
			$response['status'] = 500; // Código de estado HTTP 500 (Error interno del servidor)
			$response['message'] = "Error en la actualización";
			$response['query'] = $sql; // Incluye la consulta SQL en caso de error
		}

		echo json_encode($response);
		

		// FIN ELIMINACION DE ALUMNO
	} else if( isset( $_POST['editar_alumno'] ) ) {
		// EDICION DE ALUMNO
		$id_alu = $_POST['id_alu'];
		$nom_alu = $_POST['nom_alu'];
		$app_alu = $_POST['app_alu'];
		$apm_alu = $_POST['apm_alu'];
		$tel_alu = $_POST['tel_alu'];
		$gen_alu = $_POST['gen_alu'];
		$nac_alu = $_POST['nac_alu'];
		$cur_alu = $_POST['cur_alu'];
		$tut_alu = $_POST['tut_alu'];
		$tel2_alu = $_POST['tel2_alu'];
		$ocu_alu = $_POST['ocu_alu'];
		$dir_alu = $_POST['direccion'];
		$cp_alu = $_POST['cp_alu'];
		$cor_alu = $_POST['correo'];
		$pas_alu = $_POST['pas_alu'];
		$mon_alu_ram = $_POST['mon_alu_ram'];
		
		if( $_POST['tie_alu_ram'] == 0 ){
			$tie_alu_ram = NULL;
		} else {
			$tie_alu_ram = $_POST['tie_alu_ram'];
		}
		$id_alu_ram = $_POST['id_alu_ram'];

		// UPDATE DE id_alu_ram
		$sql2 = "
			UPDATE alu_ram
			SET
			tie_alu_ram = '$tie_alu_ram',
			mon_alu_ram = '$mon_alu_ram'
			WHERE id_alu_ram = '$id_alu_ram'
		";

		$resultado2 = mysqli_query( $db, $sql2 );
		// FIN UPDATE DE id_alu_ram
		

		// Actualiza los datos en la tabla "alumno"
		$sql = "
			UPDATE alumno
			SET
				nom_alu = '$nom_alu',
				app_alu = '$app_alu',
				apm_alu = '$apm_alu',
				tel_alu = '$tel_alu',
				gen_alu = '$gen_alu',
				nac_alu = '$nac_alu',
				cur_alu = '$cur_alu',
				tut_alu = '$tut_alu',
				tel2_alu = '$tel2_alu',
				ocu_alu = '$ocu_alu',
				dir_alu = '$dir_alu',
				cp_alu = '$cp_alu',
				cor_alu = '$cor_alu',
				pas_alu = '$pas_alu'
			WHERE
				id_alu = '$id_alu'
		";

		$resultado = mysqli_query( $db, $sql );

		$response = array();
		
		if ($resultado) {
			// La actualización se realizó con éxito
			$response['status'] = 200; // Código de estado HTTP 200 (OK)
			$response['message'] = "Actualización exitosa";
		} else {
			// Hubo un error en la actualización
			$response['status'] = 500; // Código de estado HTTP 500 (Error interno del servidor)
			$response['message'] = "Error en la actualización";
			$response['query'] = $sql; // Incluye la consulta SQL en caso de error
		}

		echo json_encode($response);

		// FIN EDICION DE ALUMNO
	} else {
		/// BUSQUEDA
		// Limpiar entrada de espacios múltiples
		$datosAlumno = trim(preg_replace('!\s+!', ' ', $_POST['datosAlumno']));
	
		$sql = "
			SELECT *, 
			obtener_documento_pendiente(id_alu_ram) AS documento_pendiente, 
			obtener_actividades_vencidas(id_alu_ram) AS actividades_vencidas 
			FROM vista_alumnos 
			WHERE id_pla8 = '$plantel'
		";
	
		if (isset($_POST['datosAlumno']) && $_POST['datosAlumno'] != '') {
			$sql .= " AND 
				( id_alu_ram LIKE '%$datosAlumno%' OR  
				  bol_alu LIKE '%$datosAlumno%' OR  
				  UPPER(REPLACE(nom_alu, '  ', ' ')) LIKE UPPER(_utf8 '%$datosAlumno%') COLLATE utf8_general_ci OR 
				  UPPER(nom_gen) LIKE UPPER(_utf8 '%$datosAlumno%') COLLATE utf8_general_ci OR 
				  tel_alu LIKE '%$datosAlumno%' OR  
				  UPPER(cor_alu) LIKE UPPER('%$datosAlumno%') ) 
			";
		}
	
		$sql .= ' ORDER BY id_alu_ram DESC';
	
		$resultado = mysqli_query($db, $sql);
		$alumnos = array();
	
		while ($fila = mysqli_fetch_assoc($resultado)) {
			$alumnos[] = array(
				"ID" => $fila['id_alu_ram'],
				"NOMBRE" => $fila['nom_alu'],
				"MATRICULA" => $fila['bol_alu'],
				"TELÉFONOS" => $fila['tel_alu'] . ' / ' . $fila['tel2_alu'],
				"GPO" => $fila['nom_gen'],
				"ADEUDOS" => formatearDinero($fila['adeudo_alumno']),
				"ESTATUS" => $fila['estatus_general'],
				"CORREO" => $fila['cor_alu'],
				"CONTRASEÑA" => $fila['pas_alu'],
				"EXPEDIENTE" => $fila['documento_pendiente'],
				"ACT VENCIDAS" => $fila['actividades_vencidas'],
				"PROMEDIO FINAL" => '######',
				"ID." => $fila['id_gen1'],
				"CARGA" => $fila['carga_alumno'],
				"CONSULTOR" => $fila['nom_eje']
			);
		}
		 
		echo json_encode($alumnos);
	}
	



?>