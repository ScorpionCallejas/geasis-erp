<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');
	if ( isset( $_POST['proc'] ) ) {
	/// PAC
		if ( $_POST['estatus'] == 'Alta' ) {
			$nom_eje = $_POST['nom_eje'];
			$ran_eje = $_POST['ran_eje'];
			$cor_eje = $_POST['cor_eje'];
			$pas_eje = $_POST['pas_eje'];
			$tel_eje = $_POST['tel_eje'];
			$cor_eje = $_POST['cor_eje'];
			$pas_eje = $_POST['pas_eje'];

			$obs_eje = $_POST['obs_eje'];
			$id_pla = $_POST['id_pla'];

			if ( $_POST['proc'] == 'estructuras_comerciales' ) {
				$sql = "INSERT INTO ejecutivo ( nom_eje, ran_eje, cor_eje, pas_eje, tel_eje, obs_eje, id_pla, id_can1 ) VALUES ( '$nom_eje', '$ran_eje', '$cor_eje', '$pas_eje', '$tel_eje', '$obs_eje', '$id_pla', NULL )";
			} else {

				$id_can = $_POST['id_can'];

				$sql = "INSERT INTO ejecutivo ( nom_eje, ran_eje, cor_eje, pas_eje, tel_eje, obs_eje, id_pla, id_can1 ) VALUES ( '$nom_eje', '$ran_eje', '$cor_eje', '$pas_eje', '$tel_eje', '$obs_eje', '$id_pla', '$id_can' )";
				
			}

		} else if ( $_POST['estatus'] == 'Cambio' ) {

			$id_eje = $_POST['id_eje'];

			$nom_eje = $_POST['nom_eje'];
			$ran_eje = $_POST['ran_eje'];
			$cor_eje = $_POST['cor_eje'];
			$pas_eje = $_POST['pas_eje'];
			$tel_eje = $_POST['tel_eje'];
			$cor_eje = $_POST['cor_eje'];
			$pas_eje = $_POST['pas_eje'];

			$obs_eje = $_POST['obs_eje'];
			$id_pla = $_POST['id_pla'];
			
			$sql = "
				UPDATE ejecutivo 
				SET nom_eje = '$nom_eje', 
				    ran_eje = '$ran_eje', 
				    cor_eje = '$cor_eje', 
				    pas_eje = '$pas_eje', 
				    tel_eje = '$tel_eje', 
				    obs_eje = '$obs_eje',
				    id_pla = '$id_pla'
				WHERE id_eje = '$id_eje';
			";
		} else if ( $_POST['estatus'] == 'Despliegue' ) {

			$id_eje = $_POST['id_eje'];

			$sql = "SELECT * FROM ejecutivo WHERE id_eje = '$id_eje'";
		} else if ( $_POST['estatus'] == 'Switch' ) {
			$id_eje = $_POST['id_eje'];
			$est_eje = $_POST['est_eje'];
			
			$sql = "
				UPDATE ejecutivo 
				SET est_eje = '$est_eje'
				WHERE id_eje = '$id_eje';
			";
		}

		$resultado = mysqli_query( $db, $sql );

		if ( !$resultado ) {
			echo json_encode(['error' => $sql]);
		} else {
			if ( $_POST['estatus'] == 'Despliegue' ) {
				$datos = mysqli_fetch_assoc( $resultado );
				echo json_encode($datos);

			} else {
				echo json_encode(['success' => 200]);
			}
			
		}
	//// FIN PAC
	} else {
	////////
		if ( isset( $_POST['conteos'] )  ) {
			//// CONTEOS
			// $inicio = $_POST['inicio'];
			// $fin = $_POST['fin'];

			// //echo json_encode(['resultado' => 'conteo']);
			// $total = 0;

			// $sql = "SELECT obtener_total_informes_ejecutivo( '$inicio', '$fin', $id ) AS total";

		    // //echo $sql;
		    // $datos = obtener_datos_consulta($db, $sql);
		    // $total_informes = $datos['datos']['total'];
		    

		    // $sql2 = "SELECT obtener_total_citados_ejecutivo( '$inicio', '$fin', $id ) AS total";
		    // //echo $sql;
		    // $datos = obtener_datos_consulta($db, $sql2);
		    // $total_citados = $datos['datos']['total'];

		    // $sql3 = "SELECT obtener_total_entrevistados_ejecutivo( '$inicio', '$fin', $id ) AS total";
		    // //echo $sql;
		    // $datos = obtener_datos_consulta($db, $sql3);
		    // $total_entrevistados = $datos['datos']['total'];

		    // $sql4 = "SELECT obtener_total_procesos_ejecutivo( '$inicio', '$fin', $id ) AS total";
		    // //echo $sql;
		    // $datos = obtener_datos_consulta($db, $sql4);
		    // $total_procesos = $datos['datos']['total'];


		    // $sql5 = "SELECT obtener_total_regresos_ejecutivo( '$inicio', '$fin', $id ) AS total";
		    // //echo $sql;
		    // $datos = obtener_datos_consulta($db, $sql5);
		    // $total_regresos = $datos['datos']['total'];

		    // $total = $total_regresos + $total_procesos + $total_entrevistados + $total_citados + $total_informes;
		    
		    // echo json_encode([
			//     'total' => $total,
			//     'total_informes' => $total_informes,
			//     'total_regresos' => $total_regresos,
			//     'total_procesos' => $total_procesos,
			//     'total_entrevistados' => $total_entrevistados,
			//     'total_citados' => $total_citados
			// ]);
			////////
		} else {
			//// EXCEL
			$campo = $_POST['campo'];
			$valor = $_POST['valor'];
			$accion = $_POST['accion'];

			$id_pla = $_POST['id_pla'];

			if ( $accion == "Alta" ) {
				///////////////////////////////////////////////
				if ( $campo == 'tel_eje' || $campo == 'nom_eje' || $campo == 'obs_eje' || $campo == 'cor_eje' || $campo == 'pas_eje' ) {
					$sql = "INSERT INTO ejecutivo ( $campo, id_pla, ran_eje, tip_eje ) VALUES ( '$valor', '$id_pla', 'Dirección', 'Dirección' )";
				
				} else if ( $campo == 'ran_eje' ) {
					$sql = "INSERT INTO ejecutivo ( $campo, id_pla, tip_eje ) VALUES ( '$valor', '$id_pla', '$valor' )";

				}
				
				$resultado = mysqli_query( $db, $sql );

				if ( !$resultado ) {
					echo json_encode(['error' => $sql]);
				} else {
					//RETORNAR ULTIMO ID :D
					$id_eje = mysqli_insert_id($db);

					$sqlDatos = "SELECT * FROM ejecutivo WHERE id_eje = $id_eje";
					$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

					echo json_encode( [
						'id_eje' => $id_eje, 
						'ing_eje' => fechaFormateadaCompacta($datos['ing_eje']), 
						'ran_eje' => $datos['ran_eje'],
						'nom_eje' => $datos['nom_eje'],
						'tel_eje' => $datos['tel_eje'],
						'tel_eje' => $datos['cor_eje'],
						'tel_eje' => $datos['pas_eje'],
						'obs_eje' => $datos['obs_eje']
						//'sql' => $sql
					]);
					//echo json_encode(['ultimo_id' => $ultimo_id, 'mensaje' => $mensaje]);
					// MÁS DE 1 DATO
				}
				//////////////////////////////

			} else if ( $accion == "Cambio" ) {
				//////////////////////////////
				
				$id_eje = $_POST['id_eje'];

				//////////////////////////////////// EDICION
				if ( $campo == 'tel_eje' || $campo == 'nom_eje' || $campo == 'obs_eje' || $campo == 'cor_eje' || $campo == 'pas_eje' ) {

					$sql = "
						UPDATE ejecutivo
						SET
						$campo = '$valor'
						WHERE id_eje = '$id_eje'
					";
				
				} else if ( $campo == 'ran_eje' ) {
					$sql = "
						UPDATE ejecutivo
						SET
						$campo = '$valor',
						tip_eje = '$valor'
						WHERE id_eje = '$id_eje'
					";
				}

				$resultado = mysqli_query( $db, $sql );

				if ( !$resultado ) {
					echo json_encode(['resultado' => 'error']);
				} else {

					//echo 'eliminacion';
					/////////////////// ELIMINACION
					$sqlDatos = "SELECT * FROM ejecutivo WHERE id_eje = $id_eje";
					$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

					//echo $sqlDatos;

					if ( 
						$datos['nom_eje'] == '' &&
						$datos['ran_eje'] == '' &&
						$datos['tel_eje'] == '' &&
						$datos['cor_eje'] == '' &&
						$datos['pas_eje'] == '' &&
						$datos['obs_eje'] == ''
					) {

						//echo 'entré a condicion de eliminacion';
						///////////////////////////////////
						$sqlEliminar = "
							UPDATE ejecutivo
							SET 
							eli_eje = 'Inactivo'
							WHERE id_eje = '$id_eje'
						";

						$resultadoEliminar = mysqli_query( $db, $sqlEliminar );

						if ( !$resultadoEliminar ) {
							echo $sqlEliminar;
							echo json_encode(['resultado' => 'error query']);
						} else {
							//////RETORNA 'false', IMPLICA BORRAR EN FRONTEND
							echo json_encode(['resultado' => 'false']);
						}
						////////////////////////////////////
					} else {
						echo json_encode(['resultado' => 'exito']);
					}
					///////////////////FIN ELIMINACION

					
				}
				/////////////////////////////////// FIN EDICION
				
				/////////////////////////////////
			}
			////
		}
	////////
	}
	

	

	
?>