<?php 
	//ARCHIVO VIA AJAX PARA EDITAR/ELIMINAR ACTIVIDADES DESDE HORARIOS
	//obtener_creacion_horario.php///horarios.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$tipos = $_POST['tipos'];
    $identificadores = $_POST['identificadores'];
    $inicios = $_POST['inicios'];
    $fines = $_POST['fines'];
    $estatus = $_POST['estatus'];


    for ( $i = 0 ;  $i < sizeof( $identificadores )  ;  $i++ ) {
		
		if ( $estatus[$i] == 'Verdadero' ) {
			
			if ( $tipos[$i] == 'Foro' ) {
			
				$sql = "
					UPDATE foro_copia
					SET
					ini_for_cop = '$inicios[$i]',
					fin_for_cop = '$fines[$i]'
					WHERE
					id_for_cop = '$identificadores[$i]'
				";

				$resultado = mysqli_query( $db, $sql );

				// LOG

				// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.	

			    // $datosActividadHorario = obtenerDatosActividadHorarioLogServer( $tipos[$i], $identificadores[$i] );
			    
			    // $actividad = $datosActividadHorario['nom_for'];
			    // $materia = $datosActividadHorario['nom_mat'];
			    // $clave = $datosActividadHorario['nom_sub_hor'];
			    // $programa = $datosActividadHorario['nom_ram'];


			    // $des_log = obtenerDescripcionActividadHorarioLogServer( $tipoUsuario, $nomResponsable, 'editó', $tipos[$i], $actividad, $materia, $clave, $programa );

			    // logServer ( 'Cambio', $tipoUsuario, $id, 'Foro Copia', $des_log, $plantel );
				// FIN LOG
				
				if ( !$resultado ) {
					echo $sql;
				}

			} else if ( $tipos[$i] == 'Entregable' ) {

				$sql = "
					UPDATE entregable_copia
					SET
					ini_ent_cop = '$inicios[$i]',
					fin_ent_cop = '$fines[$i]'
					WHERE
					id_ent_cop = '$identificadores[$i]'
				";

				$resultado = mysqli_query( $db, $sql );

				// LOG

				// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.	

			    // $datosActividadHorario = obtenerDatosActividadHorarioLogServer( $tipos[$i], $identificadores[$i] );
			    
			    // $actividad = $datosActividadHorario['nom_ent'];
			    // $materia = $datosActividadHorario['nom_mat'];
			    // $clave = $datosActividadHorario['nom_sub_hor'];
			    // $programa = $datosActividadHorario['nom_ram'];
			    

			    // $des_log = obtenerDescripcionActividadHorarioLogServer( $tipoUsuario, $nomResponsable, 'editó', $tipos[$i], $actividad, $materia, $clave, $programa );

			    // logServer ( 'Cambio', $tipoUsuario, $id, 'Entregable Copia', $des_log, $plantel );
				// FIN LOG


				if ( !$resultado ) {
					echo $sql;
				}

			} else if ( $tipos[$i] == 'Examen' ) {

				$sql = "
					UPDATE examen_copia
					SET
					ini_exa_cop = '$inicios[$i]',
					fin_exa_cop = '$fines[$i]'
					WHERE
					id_exa_cop = '$identificadores[$i]'
				";

				$resultado = mysqli_query( $db, $sql );

				// LOG

				// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.	

			    // $datosActividadHorario = obtenerDatosActividadHorarioLogServer( $tipos[$i], $identificadores[$i] );
			    
			    // $actividad = $datosActividadHorario['nom_exa'];
			    // $materia = $datosActividadHorario['nom_mat'];
			    // $clave = $datosActividadHorario['nom_sub_hor'];
			    // $programa = $datosActividadHorario['nom_ram'];
			    

			    // $des_log = obtenerDescripcionActividadHorarioLogServer( $tipoUsuario, $nomResponsable, 'editó', $tipos[$i], $actividad, $materia, $clave, $programa );

			    // logServer ( 'Cambio', $tipoUsuario, $id, 'Examen Copia', $des_log, $plantel );
				// FIN LOG

				if ( !$resultado ) {
					echo $sql;
				}
			}

		} else if ( $estatus[$i] == 'Falso' ) {

			if ( $tipos[$i] == 'Foro' ) {

				// LOG

				// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.	

			    // $datosActividadHorario = obtenerDatosActividadHorarioLogServer( $tipos[$i], $identificadores[$i] );
			    
			    // $actividad = $datosActividadHorario['nom_for'];
			    // $materia = $datosActividadHorario['nom_mat'];
			    // $clave = $datosActividadHorario['nom_sub_hor'];
			    // $programa = $datosActividadHorario['nom_ram'];
			    

			    // $des_log = obtenerDescripcionActividadHorarioLogServer( $tipoUsuario, $nomResponsable, 'eliminó', $tipos[$i], $actividad, $materia, $clave, $programa );

			    // logServer ( 'Baja', $tipoUsuario, $id, 'Foro Copia', $des_log, $plantel );
				// FIN LOG
			
				$sql = "
					DELETE FROM foro_copia WHERE id_for_cop = '$identificadores[$i]'
				";

				$resultado = mysqli_query( $db, $sql );

				
				

				if ( !$resultado ) {
					echo $sql;
				}

			} else if ( $tipos[$i] == 'Entregable' ) {


				// LOG

				// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.	

			    // $datosActividadHorario = obtenerDatosActividadHorarioLogServer( $tipos[$i], $identificadores[$i] );
			    
			    // $actividad = $datosActividadHorario['nom_ent'];
			    // $materia = $datosActividadHorario['nom_mat'];
			    // $clave = $datosActividadHorario['nom_sub_hor'];
			    // $programa = $datosActividadHorario['nom_ram'];
			    

			    // $des_log = obtenerDescripcionActividadHorarioLogServer( $tipoUsuario, $nomResponsable, 'eliminó', $tipos[$i], $actividad, $materia, $clave, $programa );

			    // logServer ( 'Baja', $tipoUsuario, $id, 'Entregable Copia', $des_log, $plantel );
				// FIN LOG


				$sql = "
					DELETE FROM entregable_copia WHERE id_ent_cop = '$identificadores[$i]'
				";

				$resultado = mysqli_query( $db, $sql );

				


				if ( !$resultado ) {
					echo $sql;
				}

			} else if ( $tipos[$i] == 'Examen' ) {


				// LOG

				// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.	

			    // $datosActividadHorario = obtenerDatosActividadHorarioLogServer( $tipos[$i], $identificadores[$i] );
			    
			    // $actividad = $datosActividadHorario['nom_exa'];
			    // $materia = $datosActividadHorario['nom_mat'];
			    // $clave = $datosActividadHorario['nom_sub_hor'];
			    // $programa = $datosActividadHorario['nom_ram'];
			    

			    // $des_log = obtenerDescripcionActividadHorarioLogServer( $tipoUsuario, $nomResponsable, 'eliminó', $tipos[$i], $actividad, $materia, $clave, $programa );

			    // logServer ( 'Baja', $tipoUsuario, $id, 'Examen Copia', $des_log, $plantel );
				// FIN LOG

				
				$sql = "
					DELETE FROM examen_copia WHERE id_exa_cop = '$identificadores[$i]'
				";

				$resultado = mysqli_query( $db, $sql );

				


				if ( !$resultado ) {
					echo $sql;
				}
			}
		}
	}

	echo "true";

	
	
		
	
?>