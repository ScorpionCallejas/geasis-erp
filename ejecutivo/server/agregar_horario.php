<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR HORARIO
	//inscripcion.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_alu_ram = $_GET['id_alu_ram'];

	$fechaHoy = date( 'Y-m-d H:i:s' );

	//echo obtenerEstatus1AlumnoRamaServer( $id_alu_ram );


	// logServer ( 'Alta', $tipoUsuario, $id, 'alu_hor', $plantel );
	$sub_hor = $_POST['sub_hor'];
	$estatus_cobros = $_POST['estatus_cobros'];
	$estatus_actividades = $_POST['estatus_actividades'];
	$tipo_inscripcion = $_POST['tipo_inscripcion']; // NUEVO: 'nueva' o 'adicionar'

	if ( $estatus_actividades == 'true' ) {

		// UPDATE DE ALU_HOR DE ALU_RAM
		$sqlUpdateAluHor = "
			UPDATE alu_hor SET est_alu_hor = 'Inactivo' WHERE id_alu_ram1 = '$id_alu_ram'
		";

		$resultadoUpdateAluHor = mysqli_query($db, $sqlUpdateAluHor);
		// FIN UPDATE DE ALU_HOR DE ALU_RAM


		$datosAlumno = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
					
		$id_usuario = $datosAlumno['id_alu'];
		$tipo_usuario = 'Alumno';

		// ELIMINACION DE SALAS PREVIAS
		$sqlDelete = "
			DELETE FROM usuario_sala WHERE tip_usu_sal = 'Alumno' AND usu_usu_sal = '$id_usuario'
		";

		$resultadoDelete = mysqli_query( $db, $sqlDelete );

		if ( !$resultadoDelete ) {
			echo $sqlDelete;
		}
		// FIN ELIMINACION DE SALAS PREVIAS


		// ORIGEN ENTREGABLE
		$sqlValidacion = "
			SELECT *
			FROM cal_act 
			INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
			WHERE id_alu_ram4 = '$id_alu_ram'
		";

		// echo $sqlValidacion;

		$totalValidacion = obtener_datos_consulta( $db, $sqlValidacion )['total'];
		$arreglo_entregable_origen = array();

		if ( $totalValidacion > 0 ) {
			
			$resultadoValidacion = mysqli_query( $db, $sqlValidacion );

			

			while ($datos_ent_cop = mysqli_fetch_assoc($resultadoValidacion)) {
			  // Crear un nuevo arreglo asociativo con los valores de cada fila
			  $nuevo_arreglo = array(
			    'id_ent' => $datos_ent_cop['id_ent1'],
			    'id_ent_cop' => $datos_ent_cop['id_ent_cop'],
			    'id_sub_hor' => $datos_ent_cop['id_sub_hor3'],
			    'fec_cal_act' => $datos_ent_cop['fec_cal_act']
			  );

			  // Agregar el nuevo arreglo al arreglo de entregables destino
			  array_push($arreglo_entregable_origen, $nuevo_arreglo);
			}
		}

		// FIN ORIGEN ENTREGABLE


		// ORIGEN FORO
		$sqlValidacion = "
			SELECT *
			FROM cal_act 
			INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
			WHERE id_alu_ram4 = '$id_alu_ram'
		";

		// echo $sqlValidacion;

		$totalValidacion = obtener_datos_consulta( $db, $sqlValidacion )['total'];
		$arreglo_foro_origen = array();

		if ( $totalValidacion > 0 ) {
			
			$resultadoValidacion = mysqli_query( $db, $sqlValidacion );

			

			while ($datos_for_cop = mysqli_fetch_assoc($resultadoValidacion)) {
			  // Crear un nuevo arreglo asociativo con los valores de cada fila
			  $nuevo_arreglo = array(
			    'id_for' => $datos_for_cop['id_for1'],
			    'id_for_cop' => $datos_for_cop['id_for_cop'],
			    'id_sub_hor' => $datos_for_cop['id_sub_hor2'],
			    'fec_cal_act' => $datos_for_cop['fec_cal_act']
			  );

			  // Agregar el nuevo arreglo al arreglo de foros destino
			  array_push($arreglo_foro_origen, $nuevo_arreglo);
			}
		}

		// FIN ORIGEN FORO



		// ORIGEN EXAMEN
		$sqlValidacion = "
			SELECT *
			FROM cal_act 
			INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
			WHERE id_alu_ram4 = '$id_alu_ram'
		";

		// echo $sqlValidacion;

		$totalValidacion = obtener_datos_consulta( $db, $sqlValidacion )['total'];
		$arreglo_examen_origen = array();

		if ( $totalValidacion > 0 ) {
			
			$resultadoValidacion = mysqli_query( $db, $sqlValidacion );

			

			while ($datos_exa_cop = mysqli_fetch_assoc($resultadoValidacion)) {
			  // Crear un nuevo arreglo asociativo con los valores de cada fila
			  $nuevo_arreglo = array(
			    'id_exa' => $datos_exa_cop['id_exa1'],
			    'id_exa_cop' => $datos_exa_cop['id_exa_cop'],
			    'id_sub_hor' => $datos_exa_cop['id_sub_hor4'],
			    'fec_cal_act' => $datos_exa_cop['fec_cal_act']
			  );

			  // Agregar el nuevo arreglo al arreglo de examenes destino
			  array_push($arreglo_examen_origen, $nuevo_arreglo);
			}
		}

		// FIN ORIGEN EXAMEN

		// DESTINO
		for( $contador = 0; $contador < sizeof( $sub_hor ); $contador++ ){
			// 

			// SALAS DE MENSAJERIA E INSERCION DE ALU_HOR
			//
			$sqlSala = "
				SELECT *
				FROM sala
				WHERE id_sub_hor6 = '$sub_hor[$contador]'
			";

			$datosSala = obtener_datos_consulta( $db, $sqlSala );

			if ( $datosSala['total'] == 1 ) {



				$id_sal = $datosSala['datos']['id_sal'];
				
				// USUARIOS
			    $sqlUsuarios = "
			      
			      INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
			      VALUES ( '$id_usuario',  '$tipo_usuario', '$id_sal' )

			    ";

			    $resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

			    if ( !$resultadoUsuarios ) {
			    
			      echo $sqlUsuarios;
			    
			    }
			    // USUARIOS

			}
			// FIN ALUMNO A SALA DE MENSAJERIA


			// LOG
	        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
			
			$filaDatos = obtenerDatosGrupalesServer( $sub_hor[$contador] );
			$nombreMateria = $filaDatos['nom_mat'];
			$nombreClave = $filaDatos['nom_sub_hor'];
			$nombrePrograma = $filaDatos['nom_ram'];

			
			// EL ADMINISTRADOR: JUAN ZARATE registró a pedrito sola en ciencias sociales ( clave_grupal ) del programa Evaluacion unica.		
			$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
		   

			logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
			// FIN LOG

			$sql2 = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$sub_hor[$contador]', '$fechaHoy', 'Activo' )";
			$resultado2 = mysqli_query($db, $sql2);
			//echo $sql;
			//
			// FIN SALAS DE MENSAJERIA E INSERCION DE ALU_HOR

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			// ENTREGABLE
			$sql_ent_cop = "
				SELECT *
				FROM entregable_copia
				WHERE id_sub_hor3 = '$sub_hor[$contador]'
			";

			$resultado_ent_cop = mysqli_query( $db, $sql_ent_cop );

			$arreglo_entregable_destino = array();

			while ($datos_ent_cop = mysqli_fetch_assoc($resultado_ent_cop)) {
			  // Crear un nuevo arreglo asociativo con los valores de cada fila
			  $nuevo_arreglo = array(
			    'id_ent' => $datos_ent_cop['id_ent1'],
			    'id_ent_cop' => $datos_ent_cop['id_ent_cop'],
			    'ini_cal_act' => $datos_ent_cop['ini_ent_cop'],
			    'fin_cal_act' => $datos_ent_cop['fin_ent_cop']
			  );

			  // Agregar el nuevo arreglo al arreglo de entregables destino
			  array_push($arreglo_entregable_destino, $nuevo_arreglo);
			}


			for( $i = 0; $i < sizeof( $arreglo_entregable_destino ); $i++ ) {

				$pos = array_search( $arreglo_entregable_destino[$i]['id_ent'], array_column($arreglo_entregable_origen, "id_ent") );

				if($pos !== false){

			    	$id_ent_cop_destino = $arreglo_entregable_destino[$i]['id_ent_cop'];
			    	$ini_cal_act = $arreglo_entregable_destino[$i]['ini_cal_act'];
			    	$fin_cal_act = $arreglo_entregable_destino[$i]['fin_cal_act'];
			    	$id_ent_cop_origen = $arreglo_entregable_origen[$pos]['id_ent_cop'];

			    	$fec_cal_act = $arreglo_entregable_origen[$pos]['fec_cal_act'];
			    	// UPDATE
			    	
			    	if( $fec_cal_act != null ){

			    		$sqlUpdate = "
				    		UPDATE cal_act
							SET ini_cal_act = curdate(),
							    fin_cal_act = curdate(), 
							    id_ent_cop2 = '$id_ent_cop_destino'
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop2 = '$id_ent_cop_origen'
				    	";	
			    	}
			    	else{
			    		$sqlUpdate = "
				    		UPDATE cal_act
							SET ini_cal_act = '$ini_cal_act',
							    fin_cal_act = '$fin_cal_act', 
							    id_ent_cop2 = '$id_ent_cop_destino'
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop2 = '$id_ent_cop_origen'
				    	";	
			    	}
			    	

			    	 // echo $sqlUpdate;

			    	$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

			    	// TAREA
			    	if ( $resultadoUpdate ) {
			    		
			    		$sqlTarea = "
			    			UPDATE tarea
							SET id_ent_cop1 = '$id_ent_cop_destino'
							WHERE id_alu_ram6 = '$id_alu_ram' AND id_ent_cop1 = '$id_ent_cop_origen'
			    		";

			    		$resultadoTarea = mysqli_query( $db, $sqlTarea );

			    	} else {
			    		echo $sqlUpdate;
			    	}
			    	// FIN TAREA

			    	// FIN UPDATE
			    } else {

			    	// INSERT
			    	$id_ent_cop_destino = $arreglo_entregable_destino[$i]['id_ent_cop'];
			    	$ini_cal_act = $arreglo_entregable_destino[$i]['ini_cal_act'];
			    	$fin_cal_act = $arreglo_entregable_destino[$i]['fin_cal_act'];
			    	$id_ent_cop_origen = $arreglo_entregable_origen[$pos]['id_ent_cop'];
			

			    	$sqlInsert = "
			    		INSERT INTO cal_act( ini_cal_act, fin_cal_act, id_ent_cop2, id_alu_ram4 ) VALUES ( '$ini_cal_act', '$fin_cal_act', '$id_ent_cop_destino', '$id_alu_ram' )
			    	";

			    	$resultadoInsert = mysqli_query( $db, $sqlInsert );

			    	// FIN INSERT
			    }
				
			}
			// FIN ENTREGABLE

			// FORO
			$sql_for_cop = "
				SELECT *
				FROM foro_copia
				WHERE id_sub_hor2 = '$sub_hor[$contador]'
			";

			$resultado_for_cop = mysqli_query( $db, $sql_for_cop );

			$arreglo_foro_destino = array();

			while ($datos_for_cop = mysqli_fetch_assoc($resultado_for_cop)) {
			  // Crear un nuevo arreglo asociativo con los valores de cada fila
			  $nuevo_arreglo = array(
			    'id_for' => $datos_for_cop['id_for1'],
			    'id_for_cop' => $datos_for_cop['id_for_cop'],
			    'ini_cal_act' => $datos_for_cop['ini_for_cop'],
			    'fin_cal_act' => $datos_for_cop['fin_for_cop']
			  );

			  // Agregar el nuevo arreglo al arreglo de foros destino
			  array_push($arreglo_foro_destino, $nuevo_arreglo);
			}


			for( $i = 0; $i < sizeof( $arreglo_foro_destino ); $i++ ) {

				$pos = array_search( $arreglo_foro_destino[$i]['id_for'], array_column($arreglo_foro_origen, "id_for") );

				if($pos !== false){

			    	$id_for_cop_destino = $arreglo_foro_destino[$i]['id_for_cop'];
			    	$ini_cal_act = $arreglo_foro_destino[$i]['ini_cal_act'];
			    	$fin_cal_act = $arreglo_foro_destino[$i]['fin_cal_act'];
			    	$id_for_cop_origen = $arreglo_foro_origen[$pos]['id_for_cop'];

			    	$fec_cal_act = $arreglo_foro_origen[$pos]['fec_cal_act'];
			    	// UPDATE
			    	
			    	if( $fec_cal_act != null ){

			    		$sqlUpdate = "
				    		UPDATE cal_act
							SET ini_cal_act = curdate(),
							    fin_cal_act = curdate(), 
							    id_for_cop2 = '$id_for_cop_destino'
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop2 = '$id_for_cop_origen'
				    	";	
			    	}
			    	else{
			    		$sqlUpdate = "
				    		UPDATE cal_act
							SET ini_cal_act = '$ini_cal_act',
							    fin_cal_act = '$fin_cal_act', 
							    id_for_cop2 = '$id_for_cop_destino'
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop2 = '$id_for_cop_origen'
				    	";	
			    	}
			    	

			    	// echo $sqlUpdate;

			    	$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

			    	// FIN UPDATE
			    } else {

			    	// INSERT
			    	$id_for_cop_destino = $arreglo_foro_destino[$i]['id_for_cop'];
			    	$ini_cal_act = $arreglo_foro_destino[$i]['ini_cal_act'];
			    	$fin_cal_act = $arreglo_foro_destino[$i]['fin_cal_act'];
			    	$id_for_cop_origen = $arreglo_foro_origen[$pos]['id_for_cop'];
			

			    	$sqlInsert = "
			    		INSERT INTO cal_act( ini_cal_act, fin_cal_act, id_for_cop2, id_alu_ram4 ) VALUES ( '$ini_cal_act', '$fin_cal_act', '$id_for_cop_destino', '$id_alu_ram' )
			    	";

			    	$resultadoInsert = mysqli_query( $db, $sqlInsert );

			    	// FIN INSERT
			    }
				
			}
			// FIN FORO


			// EXAMEN
			$sql_exa_cop = "
				SELECT *
				FROM examen_copia
				WHERE id_sub_hor4 = '$sub_hor[$contador]'
			";

			$resultado_exa_cop = mysqli_query( $db, $sql_exa_cop );

			$arreglo_examen_destino = array();

			while ($datos_exa_cop = mysqli_fetch_assoc($resultado_exa_cop)) {
			  // Crear un nuevo arreglo asociativo con los valores de cada fila
			  $nuevo_arreglo = array(
			    'id_exa' => $datos_exa_cop['id_exa1'],
			    'id_exa_cop' => $datos_exa_cop['id_exa_cop'],
			    'ini_cal_act' => $datos_exa_cop['ini_exa_cop'],
			    'fin_cal_act' => $datos_exa_cop['fin_exa_cop']
			  );

			  // Agregar el nuevo arreglo al arreglo de examenes destino
			  array_push($arreglo_examen_destino, $nuevo_arreglo);
			}


			for( $i = 0; $i < sizeof( $arreglo_examen_destino ); $i++ ) {

				$pos = array_search( $arreglo_examen_destino[$i]['id_exa'], array_column($arreglo_examen_origen, "id_exa") );

				if($pos !== false){

			    	$id_exa_cop_destino = $arreglo_examen_destino[$i]['id_exa_cop'];
			    	$ini_cal_act = $arreglo_examen_destino[$i]['ini_cal_act'];
			    	$fin_cal_act = $arreglo_examen_destino[$i]['fin_cal_act'];
			    	$id_exa_cop_origen = $arreglo_examen_origen[$pos]['id_exa_cop'];

			    	$fec_cal_act = $arreglo_examen_origen[$pos]['fec_cal_act'];
			    	// UPDATE
			    	
			    	if( $fec_cal_act != null ){

			    		$sqlUpdate = "
				    		UPDATE cal_act
							SET ini_cal_act = curdate(),
							    fin_cal_act = curdate(), 
							    id_exa_cop2 = '$id_exa_cop_destino'
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_exa_cop2 = '$id_exa_cop_origen'
				    	";	
			    	}
			    	else{
			    		$sqlUpdate = "
				    		UPDATE cal_act
							SET ini_cal_act = '$ini_cal_act',
							    fin_cal_act = '$fin_cal_act', 
							    id_exa_cop2 = '$id_exa_cop_destino'
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_exa_cop2 = '$id_exa_cop_origen'
				    	";	
			    	}
			    	

			    	// echo $sqlUpdate;

			    	$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

			    	// FIN UPDATE
			    } else {

			    	// INSERT
			    	$id_exa_cop_destino = $arreglo_examen_destino[$i]['id_exa_cop'];
			    	$ini_cal_act = $arreglo_examen_destino[$i]['ini_cal_act'];
			    	$fin_cal_act = $arreglo_examen_destino[$i]['fin_cal_act'];
			    	$id_exa_cop_origen = $arreglo_examen_origen[$pos]['id_exa_cop'];
			

			    	$sqlInsert = "
			    		INSERT INTO cal_act( ini_cal_act, fin_cal_act, id_exa_cop2, id_alu_ram4 ) VALUES ( '$ini_cal_act', '$fin_cal_act', '$id_exa_cop_destino', '$id_alu_ram' )
			    	";

			    	$resultadoInsert = mysqli_query( $db, $sqlInsert );

			    	// FIN INSERT
			    }
				
			}
			// FIN EXAMEN

		// FIN FOR sub_hor
		}

		// BORRADO DE cal_act ENTREGABLE
		for( $j = 0; $j < sizeof( $arreglo_entregable_origen ); $j++ ){

			$id_sub_hor = $arreglo_entregable_origen[$j]['id_sub_hor'];
			$sqlDelete = "
				DELETE FROM cal_act 
				INNER JOIN entregable_copia ON entregable.id_ent_cop = cal_act.ide_ent_cop2
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
				WHERE id_alu_ram4 = '$id_alu_ram' AND id_sub_hor = '$id_sub_hor'
			";

			$resultadoDelete = mysqli_query( $db, $sqlDelete );

			// echo $sqlDelete;
		}
		// FIN BORRADO DE cal_act ENTREGABLE


		// BORRADO DE cal_act FORO
		for( $j = 0; $j < sizeof( $arreglo_foro_origen ); $j++ ){

			$id_sub_hor = $arreglo_foro_origen[$j]['id_sub_hor'];
			$sqlDelete = "
				DELETE FROM cal_act 
				INNER JOIN foro_copia ON foro.id_for_cop = cal_act.ide_for_cop2
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
				WHERE id_alu_ram4 = '$id_alu_ram' AND id_sub_hor = '$id_sub_hor'
			";

			$resultadoDelete = mysqli_query( $db, $sqlDelete );

			// echo $sqlDelete;
		}
		// FIN BORRADO DE cal_act FORO


		// BORRADO DE cal_act EXAMEN
		for( $j = 0; $j < sizeof( $arreglo_examen_origen ); $j++ ){

			$id_sub_hor = $arreglo_examen_origen[$j]['id_sub_hor'];
			$sqlDelete = "
				DELETE FROM cal_act 
				INNER JOIN examen_copia ON examen.id_exa_cop = cal_act.ide_exa_cop2
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
				WHERE id_alu_ram4 = '$id_alu_ram' AND id_sub_hor = '$id_sub_hor'
			";

			$resultadoDelete = mysqli_query( $db, $sqlDelete );

			// echo $sqlDelete;
		}
		// FIN BORRADO DE cal_act EXAMEN

	} else {
		//
		//echo sizeof($sub_hor);

		// NUEVO: VERIFICAR TIPO DE INSCRIPCION
		if ( $tipo_inscripcion == 'adicionar' ) {
			
			// MODO ADICION: SOLO INSERTAR, NO BORRAR NADA
			
			$datosAlumno = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
			$id_usuario = $datosAlumno['id_alu'];
			$tipo_usuario = 'Alumno';

			for($i = 0; $i < sizeof($sub_hor); $i++){

				// VALIDACION DE MAS DE UN id_sub_hor
				$sqlValidacion = "
					SELECT *
					FROM sub_hor
					WHERE id_sub_hor = '$sub_hor[$i]'
				";

				$id_fus2 = obtener_datos_consulta( $db, $sqlValidacion )['datos']['id_fus2'];

				if ( ( $id_fus2 == null ) || ( $id_fus2 == '' )  ) {
					
					// ALUMNO A SALA DE MENSAJERIA
					$sqlSala = "
						SELECT *
						FROM sala
						WHERE id_sub_hor6 = '$sub_hor[$i]'
					";

					$datosSala = obtener_datos_consulta( $db, $sqlSala );

					if ( $datosSala['total'] == 1 ) {
						
						$id_sal = $datosSala['datos']['id_sal'];
						
						// USUARIOS
					    $sqlUsuarios = "
					      INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
					      VALUES ( '$id_usuario',  '$tipo_usuario', '$id_sal' )
					    ";

					    $resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

					    if ( !$resultadoUsuarios ) {
					      echo $sqlUsuarios;
					    }
					}

					// LOG
			        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
					
					$filaDatos = obtenerDatosGrupalesServer( $sub_hor[$i] );
					$nombreMateria = $filaDatos['nom_mat'];
					$nombreClave = $filaDatos['nom_sub_hor'];
					$nombrePrograma = $filaDatos['nom_ram'];
					
					$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'adicionó', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
				   
					logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
					// FIN LOG

					$sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$sub_hor[$i]', '$fechaHoy', 'Activo' )";
					$resultado = mysqli_query($db, $sql);

					//FOROS
					$sqlForos = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
						WHERE id_sub_hor = '$sub_hor[$i]'
					";

					$resultadoForos = mysqli_query($db, $sqlForos);

					while($filaForos = mysqli_fetch_assoc($resultadoForos)){
						$id_for_cop = $filaForos['id_for_cop'];
						$ini_cal_act = $filaForos['ini_for_cop'];
						$fin_cal_act = $filaForos['fin_for_cop'];

						$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);
					}

					//ENTREGABLES
					$sqlEntregables = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
						INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
						WHERE id_sub_hor = '$sub_hor[$i]'
					";
					
					$resultadoEntregables = mysqli_query($db, $sqlEntregables);

					while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
						$id_ent_cop = $filaEntregables['id_ent_cop'];
						$ini_cal_act = $filaEntregables['ini_ent_cop'];
						$fin_cal_act = $filaEntregables['fin_ent_cop'];

						$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);
					}

					//EXAMENES
					$sqlExamenes = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
						WHERE id_sub_hor = '$sub_hor[$i]'
					";

					$resultadoExamenes = mysqli_query($db, $sqlExamenes);

					while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
						$id_exa_cop = $filaExamenes['id_exa_cop'];
						$ini_cal_act = $filaExamenes['ini_exa_cop'];
						$fin_cal_act = $filaExamenes['fin_exa_cop'];

						$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);
					}

				} else {

					// CONTENIDO DOMINANTE DE FUSION
					$sqlValidacion2 = "
						SELECT *
						FROM sub_hor
						WHERE id_fus2 = '$id_fus2' AND id_sub_hor_nat IS NULL
					";

					$resultadoValidacion2 = mysqli_query( $db, $sqlValidacion2 );
					$filaValidacion2 = mysqli_fetch_assoc( $resultadoValidacion2 );
					$id_sub_hor = $filaValidacion2['id_sub_hor'];

					// ALUMNO A SALA DE MENSAJERIA
					$sqlSala = "
						SELECT *
						FROM sala
						WHERE id_sub_hor6 = '$id_sub_hor'
					";

					$datosSala = obtener_datos_consulta( $db, $sqlSala );

					if ( $datosSala['total'] == 1 ) {
						$id_sal = $datosSala['datos']['id_sal'];
						
						$sqlUsuarios = "
						  INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
						  VALUES ( '$id_usuario',  '$tipo_usuario', '$id_sal' )
						";

					    $resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

					    if ( !$resultadoUsuarios ) {
					      echo $sqlUsuarios;
					    }
					}

					// LOG
			        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
					
					$filaDatos = obtenerDatosGrupalesServer( $id_sub_hor );
					$nombreMateria = $filaDatos['nom_mat'];
					$nombreClave = $filaDatos['nom_sub_hor'];
					$nombrePrograma = $filaDatos['nom_ram'];

					$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'adicionó', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
				   
					logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
					// FIN LOG

					$sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$id_sub_hor', '$fechaHoy', 'Activo' )";
					$resultado = mysqli_query($db, $sql);

					//FOROS
					$sqlForos = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
						WHERE id_sub_hor = '$id_sub_hor'
					";

					$resultadoForos = mysqli_query($db, $sqlForos);

					while($filaForos = mysqli_fetch_assoc($resultadoForos)){
						$id_for_cop = $filaForos['id_for_cop'];
						$ini_cal_act = $filaForos['ini_for_cop'];
						$fin_cal_act = $filaForos['fin_for_cop'];

						$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);
					}

					//ENTREGABLES
					$sqlEntregables = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
						INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
						WHERE id_sub_hor = '$id_sub_hor'
					";
					
					$resultadoEntregables = mysqli_query($db, $sqlEntregables);

					while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
						$id_ent_cop = $filaEntregables['id_ent_cop'];
						$ini_cal_act = $filaEntregables['ini_ent_cop'];
						$fin_cal_act = $filaEntregables['fin_ent_cop'];

						$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);
					}

					//EXAMENES
					$sqlExamenes = "
						SELECT * 
						FROM sub_hor 
						INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
						WHERE id_sub_hor = '$id_sub_hor'
					";

					$resultadoExamenes = mysqli_query($db, $sqlExamenes);

					while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
						$id_exa_cop = $filaExamenes['id_exa_cop'];
						$ini_cal_act = $filaExamenes['ini_exa_cop'];
						$fin_cal_act = $filaExamenes['fin_exa_cop'];

						$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
						$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);
					}
				}
			}

			if ($resultado) {
				echo $id_alu_ram;
			}else{
				echo $sql;
			}

		} else {
			
			// MODO NUEVA CARGA: CODIGO ORIGINAL (BORRA TODO)

			$sqlDeleteCalAct = "DELETE FROM cal_act WHERE id_alu_ram4 = '$id_alu_ram'";
			$resultado_cal_act = mysqli_query($db, $sqlDeleteCalAct);

			if ($resultado_cal_act) {
				
				$sqlDeleteAluHor = "
					UPDATE alu_hor SET est_alu_hor = 'Inactivo' WHERE id_alu_ram1 = '$id_alu_ram'
				";

				$resultadoDeleteAluHor = mysqli_query($db, $sqlDeleteAluHor);
				
				if ($resultadoDeleteAluHor) {

					// ELIMINACION DE usuario_sala DE ALUMNO EN SALAS PREVIAS
					$datosAlumno = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
						
					$id_usuario = $datosAlumno['id_alu'];
					$tipo_usuario = 'Alumno';

					$sqlDelete = "
						DELETE FROM usuario_sala WHERE tip_usu_sal = 'Alumno' AND usu_usu_sal = '$id_usuario'
					";

					$resultadoDelete = mysqli_query( $db, $sqlDelete );

					if ( !$resultadoDelete ) {
						echo $sqlDelete;
					}
					// FIN ELIMINACION DE usuario
					
					for($i = 0; $i < sizeof($sub_hor); $i++){

						// VALIDACION DE MAS DE UN id_sub_hor
						$sqlValidacion = "
							SELECT *
							FROM sub_hor
							WHERE id_sub_hor = '$sub_hor[$i]'
						";

						$id_fus2 = obtener_datos_consulta( $db, $sqlValidacion )['datos']['id_fus2'];

						if ( ( $id_fus2 == null ) || ( $id_fus2 == '' )  ) {
							
							// ALUMNO A SALA DE MENSAJERIA
							$sqlSala = "
								SELECT *
								FROM sala
								WHERE id_sub_hor6 = '$sub_hor[$i]'
							";

							$datosSala = obtener_datos_consulta( $db, $sqlSala );

							if ( $datosSala['total'] == 1 ) {
								
								$id_sal = $datosSala['datos']['id_sal'];
								
								// USUARIOS
							    $sqlUsuarios = "
							      INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
							      VALUES ( '$id_usuario',  '$tipo_usuario', '$id_sal' )
							    ";

							    $resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

							    if ( !$resultadoUsuarios ) {
							      echo $sqlUsuarios;
							    }
							}

							// LOG
					        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
							
							$filaDatos = obtenerDatosGrupalesServer( $sub_hor[$i] );
							$nombreMateria = $filaDatos['nom_mat'];
							$nombreClave = $filaDatos['nom_sub_hor'];
							$nombrePrograma = $filaDatos['nom_ram'];
							
							$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
						   
							logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
							// FIN LOG

							$sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$sub_hor[$i]', '$fechaHoy', 'Activo' )";
							$resultado = mysqli_query($db, $sql);

							//FOROS
							$sqlForos = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$sub_hor[$i]'
							";

							$resultadoForos = mysqli_query($db, $sqlForos);

							while($filaForos = mysqli_fetch_assoc($resultadoForos)){
								$id_for_cop = $filaForos['id_for_cop'];
								$ini_cal_act = $filaForos['ini_for_cop'];
								$fin_cal_act = $filaForos['fin_for_cop'];

								$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);
							}

							//ENTREGABLES
							$sqlEntregables = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
								INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
								WHERE id_sub_hor = '$sub_hor[$i]'
							";
							
							$resultadoEntregables = mysqli_query($db, $sqlEntregables);

							while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
								$id_ent_cop = $filaEntregables['id_ent_cop'];
								$ini_cal_act = $filaEntregables['ini_ent_cop'];
								$fin_cal_act = $filaEntregables['fin_ent_cop'];

								$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);
							}

							//EXAMENES
							$sqlExamenes = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$sub_hor[$i]'
							";

							$resultadoExamenes = mysqli_query($db, $sqlExamenes);

							while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
								$id_exa_cop = $filaExamenes['id_exa_cop'];
								$ini_cal_act = $filaExamenes['ini_exa_cop'];
								$fin_cal_act = $filaExamenes['fin_exa_cop'];

								$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);
							}

						} else {

							// CONTENIDO DOMINANTE DE FUSION
							$sqlValidacion2 = "
								SELECT *
								FROM sub_hor
								WHERE id_fus2 = '$id_fus2' AND id_sub_hor_nat IS NULL
							";

							$resultadoValidacion2 = mysqli_query( $db, $sqlValidacion2 );
							$filaValidacion2 = mysqli_fetch_assoc( $resultadoValidacion2 );
							$id_sub_hor = $filaValidacion2['id_sub_hor'];

							// ALUMNO A SALA DE MENSAJERIA
							$sqlSala = "
								SELECT *
								FROM sala
								WHERE id_sub_hor6 = '$id_sub_hor'
							";

							$datosSala = obtener_datos_consulta( $db, $sqlSala );

							if ( $datosSala['total'] == 1 ) {
								$id_sal = $datosSala['datos']['id_sal'];
								
								$sqlUsuarios = "
								  INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
								  VALUES ( '$id_usuario',  '$tipo_usuario', '$id_sal' )
								";

							    $resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

							    if ( !$resultadoUsuarios ) {
							      echo $sqlUsuarios;
							    }
							}

							// LOG
					        $nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram );
							
							$filaDatos = obtenerDatosGrupalesServer( $id_sub_hor );
							$nombreMateria = $filaDatos['nom_mat'];
							$nombreClave = $filaDatos['nom_sub_hor'];
							$nombrePrograma = $filaDatos['nom_ram'];

							$des_log =  obtenerDescripcionInscripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno,  $nombreMateria, $nombreClave, $nombrePrograma );
						   
							logServer ( 'Alta', $tipoUsuario, $id, 'Inscripción', $des_log, $plantel );
							// FIN LOG

							$sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$id_sub_hor', '$fechaHoy', 'Activo' )";
							$resultado = mysqli_query($db, $sql);

							//FOROS
							$sqlForos = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$id_sub_hor'
							";

							$resultadoForos = mysqli_query($db, $sqlForos);

							while($filaForos = mysqli_fetch_assoc($resultadoForos)){
								$id_for_cop = $filaForos['id_for_cop'];
								$ini_cal_act = $filaForos['ini_for_cop'];
								$fin_cal_act = $filaForos['fin_for_cop'];

								$sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);
							}

							//ENTREGABLES
							$sqlEntregables = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
								INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
								WHERE id_sub_hor = '$id_sub_hor'
							";
							
							$resultadoEntregables = mysqli_query($db, $sqlEntregables);

							while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
								$id_ent_cop = $filaEntregables['id_ent_cop'];
								$ini_cal_act = $filaEntregables['ini_ent_cop'];
								$fin_cal_act = $filaEntregables['fin_ent_cop'];

								$sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);
							}

							//EXAMENES
							$sqlExamenes = "
								SELECT * 
								FROM sub_hor 
								INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
								WHERE id_sub_hor = '$id_sub_hor'
							";

							$resultadoExamenes = mysqli_query($db, $sqlExamenes);

							while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
								$id_exa_cop = $filaExamenes['id_exa_cop'];
								$ini_cal_act = $filaExamenes['ini_exa_cop'];
								$fin_cal_act = $filaExamenes['fin_exa_cop'];

								$sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
								$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);
							}
						}
					}
					
					if ($resultado) {
						
						if ( $estatus_cobros == 'true' ) {
							generarPagosRecurrentes($id_alu_ram, $folioPlantel);
						}
						
						echo $id_alu_ram;
					}else{
						echo $sql;
					}
				}else{
					echo "Error delete";
				}

			}else{
				echo $sqlDeleteCalAct;
			}

		} // FIN VERIFICACION TIPO DE INSCRIPCION
	}
?>