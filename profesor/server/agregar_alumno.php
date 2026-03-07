<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO ALUMNO
	//alumnos_carrera.php///alumnos.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	// enviarCorreoBienvenidaAlumno( $_POST['correo'], $_POST['cor1_alu'], $_POST['pas_alu'], $_POST['nom_alu'], $nombrePlantel, $correo2Plantel, $ligaPlantel, $fotoPlantel );

	if ( ( isset( $_POST['id_ram'] ) && ( isset( $_POST['id_gen'] ) ) ) ) {
	
		$id_ram = $_POST['id_ram'];
		$id_gen = $_POST['id_gen'];
		$cor1_alu = $_POST['cor1_alu'];

		$correo = $_POST['correo'];
		$pas_alu = $_POST['pas_alu'];
		$nom_alu = $_POST['nom_alu'];
		$app_alu = $_POST['app_alu'];
		$apm_alu = $_POST['apm_alu'];
		$bol_alu = $_POST['bol_alu'];
		$gen_alu = $_POST['gen_alu'];
		$tel_alu = $_POST['tel_alu'];
		$cur_alu = $_POST['cur_alu'];
		$nac_alu = $_POST['nac_alu'];
		$ing_alu = date('Y-m-d');


		$dir_alu = $_POST['dir_alu'];
		$cp_alu  = $_POST['cp_alu'];
		$col_alu = $_POST['col_alu'];
		$del_alu = $_POST['del_alu'];
		$ent_alu = $_POST['ent_alu'];
		$tut_alu = 'Pendiente';
		$tel2_alu = $_POST['tel2_alu'];
		$pro_alu = $_POST['pro_alu'];
		$qr_alu = sha1($correo);

		$fot_alu = $_FILES['fot_alu']['name'];
		/////
		
		$bec_alu_ram = 0/100;
		$bec2_alu_ram = 0/100;

		$lug_alu = $_POST['lug_alu'];
		$civ_alu = $_POST['civ_alu'];
		$ocu_alu = $_POST['ocu_alu'];
		$lim_alu = $_POST['lim_alu'];
	
		$sqlInsercionAlumno = "INSERT INTO alumno ( cor_alu, pas_alu, nom_alu, app_alu, apm_alu, bol_alu, gen_alu, tel_alu, cur_alu, nac_alu, ing_alu, dir_alu, cp_alu, col_alu, del_alu, ent_alu, tut_alu, tel2_alu, pro_alu, tip_alu, qr_alu, id_pla8, est_alu, cor1_alu, lug_alu, civ_alu, ocu_alu, lim_alu ) VALUES ( '$correo', '$pas_alu', '$nom_alu', '$app_alu', '$apm_alu', '$bol_alu', '$gen_alu', '$tel_alu', '$cur_alu', '$nac_alu', '$ing_alu', '$dir_alu', '$cp_alu', '$col_alu', '$del_alu', '$ent_alu', '$tut_alu', '$tel2_alu', '$pro_alu', 'Alumno', '$qr_alu', '$plantel', 'Activo', '$cor1_alu', '$lug_alu', '$civ_alu', '$ocu_alu', '$lim_alu' )";


		$resultadoInsercionAlumno = mysqli_query( $db, $sqlInsercionAlumno );

		if ( $resultadoInsercionAlumno ) {
			//CARGAR MATERIAS ACORDE A RAMA EN TABLA CALIFICACION

			$sql = "SELECT MAX(id_alu) AS ultimo FROM alumno";
			$resultado = mysqli_query( $db, $sql );

			$fila = mysqli_fetch_assoc( $resultado );
			$maxAlumno = $fila['ultimo'];

			$sqlResponsable = "INSERT INTO alu_res (id_alu11, nom_res) VALUES ('$maxAlumno', '$nomResponsable')";

			mysqli_query($db, $sqlResponsable);

			if ( $fot_alu != "" ) {
				//RENAME Y GUARDADO DE LA FOTO DEL ALUMNO

				$fot_alu = $_FILES['fot_alu']['name'];
				$foto = "foto-alumno00".$maxAlumno.".".end(explode(".", $fot_alu));


				$carpeta_destino = '../../uploads/';
				move_uploaded_file($_FILES['fot_alu']['tmp_name'], $carpeta_destino.$foto);

				//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
				$sqlUpdateAlumno = "UPDATE alumno SET fot_alu = '$foto' WHERE id_alu = '$maxAlumno'";

				mysqli_query($db, $sqlUpdateAlumno);
			}

			for ( $contadorArreglo = 0 ;  $contadorArreglo < sizeof( $id_ram ) ;  $contadorArreglo++ ) { 


				// ALUMNO A SALA DE MENSAJERIA
				$sqlSala = "
					SELECT *
					FROM sala
					WHERE id_gen3 = '$id_gen[$contadorArreglo]'
				";

				$datosSala = obtener_datos_consulta( $db, $sqlSala );

				if ( $datosSala['total'] == 1 ) {
					

					$id_usuario = $maxAlumno;
					$tipo_usuario = 'Alumno';
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

				


				
				$sqlRama = "
					SELECT *
					FROM rama
					WHERE id_ram = '$id_ram[$contadorArreglo]'
				";

				$resultadoRama = mysqli_query( $db, $sqlRama );

				$filaRama = mysqli_fetch_assoc( $resultadoRama );

				$car_alu_ram = $filaRama['car_reg_ram'];


				$sqlAlumnoRama = "INSERT INTO alu_ram ( car_alu_ram, bec_alu_ram, bec2_alu_ram, id_gen1, id_alu1, id_ram3) VALUES ( '$car_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$id_gen[$contadorArreglo]', '$maxAlumno', '$id_ram[$contadorArreglo]')";


				// echo $sqlAlumnoRama;
				
				$resultadoAlumnoRama = mysqli_query( $db, $sqlAlumnoRama );

				if ($resultadoAlumnoRama) {

					// ADICION DE CALIFICACIONES Y PARCIALES

					$sqlAluRam = "SELECT MAX(id_alu_ram) AS ultimo FROM alu_ram";
					$resultadoAluRam = mysqli_query($db, $sqlAluRam);

					$filaAluRam = mysqli_fetch_assoc($resultadoAluRam);
					$maxAluRam = $filaAluRam['ultimo'];

				    //TOTAL DE MATERIAS Y ADICION
					$sqlMateria = "
			            SELECT * 
						FROM materia
						WHERE id_ram2 = '$id_ram[$contadorArreglo]'
		            ";


					$resultadoMateria = mysqli_query($db, $sqlMateria);



			        $temp = array();
			        $i = 0;

			        while ($filaMateriasAux = mysqli_fetch_array($resultadoMateria)) {
			             $temp[$i] = $filaMateriasAux["id_mat"];
			             $i++;

			        }

			        //var_dump($temp);

			        // EXTRACCION DE PARCIALES eva_ram DE rama
			        
			        $eva_ram = $filaRama['eva_ram'];
			        // SE NECESITA PARA FIJAR CONDICION DE LAS EVALUACIONES POR CICLO Y GENERAR LOS REGISTROS NULOS

			        //ADICION DE REGISTROS NULOS PARA EXISTENCIA DE CARGAS EN MATERIAS POR EVALUAR
			        for ($i = 0 ; $i < count($temp) ; $i++ ) { 
			        	$sqlInsercionCalificacion = "INSERT INTO calificacion (id_alu_ram2, id_mat4) VALUES($maxAluRam, $temp[$i])";
			        	//echo $sqlInsercionCalificacion;
			            mysqli_query($db, $sqlInsercionCalificacion);

			            for ($j = 0; $j < $eva_ram; $j++) { 
			            	// ADICION DE REGISTROS NULOS PARA PARCIALES ACORDE A MATERIAS
			            	$sqlInsercionParcial = "INSERT INTO parcial (id_alu_ram9, id_mat3) VALUES($maxAluRam, $temp[$i])";
			            	mysqli_query($db, $sqlInsercionParcial);
			            }

			        }


			        // ADICION DE PAGOS

			        // $datosGeneracion = obtenerDatosGeneracionProgramaLogServer( $id_gen[$contadorArreglo] );


			        // if ( $datosGeneracion['val_gen_pag'] == 'Activo' ) {
			        // 

			        	$id_gen_pag = $_POST['id_gen_pag'];
			        	$mon_gen_pag = $_POST['mon_gen_pag'];
			        	$con_gen_pag = $_POST['con_gen_pag'];

			        	$ini_gen_pag = $_POST['ini_gen_pag'];
			        	$fin_gen_pag = $_POST['fin_gen_pag'];
				        
				        for( $j = 0; $j < sizeof( $id_gen_pag ); $j++ ){
				        // 

				        	$id_gen_pag2 = $id_gen_pag[$j];

				        	$datosGeneracionPago = obtener_datos_generacion_pago_server( $id_gen_pag[$j] );

				        	$id_alu_ram10 = $maxAluRam;

							$fec_pag = date('Y-m-d');

							$mon_ori_pag = $mon_gen_pag[$j];

							$mon_pag = $mon_ori_pag;

							$con_pag = $con_gen_pag[$j];

							$est_pag = 'Pendiente';

							$res_pag = $nomResponsable;

							$ini_pag = $ini_gen_pag[$j];

							$fin_pag = $fin_gen_pag[$j];

							$pro_pag = date('Y-m-d');

							$pri_pag = 1;

							$tip1_pag = 'Monetario';

							$tip2_pag = '';

							$car_pag = $datosGeneracionPago['car_gen_pag'];

							$des_pag = 0;

							$int_pag = '';

							$tip_pag = $datosGeneracionPago['tip_gen_pag'];

							$sqlInsercionPago = "
								INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, int_pag, id_alu_ram10, id_gen_pag2, tip_pag ) 
								VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$int_pag', '$id_alu_ram10', '$id_gen_pag2', '$tip_pag' )
							";

							$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
							
							if ( !$resultadoInsercionPago ) {
							
								echo $sqlInsercionPago;
							
							}else {
								// OBTENCION DE id MAXIMO DE PAGO
								// PARA INSERCION DE FOLIO 
								$sqlMaximoPago = "
									SELECT MAX(id_pag) AS maximo
									FROM pago
									WHERE id_alu_ram10 = '$id_alu_ram10'
								";

								$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

								if ( !$resultadoMaximoPago ) {
									
									echo $sqlMaximoPago;
								
								}else {

									$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
									$maximoPago = $filaMaximoPago['maximo'];
									// SQL UPDATE PARA AGREGAR FOLIO

									$fol_pag = $folioPlantel."00".$maximoPago;

									$sqlUpdatePago = "
										UPDATE pago
										SET 
										fol_pag = '$fol_pag'
										WHERE
										id_pag = '$maximoPago'
									";

									$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

									if ( !$resultadoUpdatePago ) {
										echo $sqlMaximoPago;
									}else{

										// LOG
										$nombreAlumno = obtenerNombreAlumnoServer( $id_alu_ram10 );

										// el administrador juan zarate registro un cobro por concepto: colegiatura 2, por la cantidad de $1500, a Pedrito Sola. fecha...
										$des_log =  obtenerDescripcionPagoAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $con_pag, $mon_pag, $nombreAlumno );


										logServer ( 'Alta', $tipoUsuario, $id, 'Cobro', $des_log, $plantel );
										// FIN LOG

										// logServer ( 'Alta', $tipoUsuario, $id, 'Pago', $plantel );
										echo $id_alu_ram10;
									}
								}

							}

				        // FOR
				        }
			        	
			        // IF
			        // }
			        
			        // FIN ADICION DE PAGOS


			        // ADICION  DE CARGA DE DOCUMENTACION DE PROGRAMA
			        
			    //     if ( isset( $_POST['id_doc_ram1'] ) ) {
			        	
			    //     	$id_doc_ram1 = $_POST['id_doc_ram1'];
			    //     	$est_doc_alu_ram = $_POST['est_doc_alu_ram'];

			    //     	for( $k = 0; $k < sizeof( $id_doc_ram1 ); $k++ ){

				   //      	$sqlInsercionDocumento = "
							// 	INSERT INTO documento_alu_ram ( est_doc_alu_ram, id_doc_ram1, id_alu_ram11 )
							// 	VALUES ( '$est_doc_alu_ram[$k]', $id_doc_ram1[$k], $maxAluRam )
							// ";

				   //      	$resultadoInsercionDocumento = mysqli_query($db, $sqlInsercionDocumento);

				   //      	if ( !$resultadoInsercionDocumento ) {
				        		
				   //      		echo $sqlInsercionDocumento;
				        	
				   //      	}

			    //     	}

			    //     }
			        	
			        
			        // FIN ADICION DE CARGA DE DOCUMENTACION DE PROGRAMA



				}else{
					echo "Error en insercion de alumno-rama";
					echo $sqlAlumnoRama;

				}
				//echo "Exito";


			}

			
		}else{
			echo "Error en alta de alumno, verificar consulta";
			echo $sqlInsercionAlumno;
		}

		
		
		echo "Exito";




	
	} else if ( ( isset( $_GET['id_ram'] )  && ( isset( $_GET['id_gen'] ) ) ) ) {
	
		$id_ram = $_GET['id_ram'];
		$id_gen = $_GET['id_gen'];
	
		$sqlRama = "
			SELECT *
			FROM rama
			WHERE id_ram = '$id_ram'
		";

		$resultadoRama = mysqli_query($db, $sqlRama);

		$filaRama = mysqli_fetch_assoc($resultadoRama);

		$car_alu_ram = $filaRama['car_reg_ram'];

		$cor1_alu = $_POST['cor1_alu'];
		$correo = $_POST['correo'];
		$pas_alu = $_POST['pas_alu'];
		$nom_alu = $_POST['nom_alu'];
		$app_alu = $_POST['app_alu'];
		$apm_alu = $_POST['apm_alu'];
		$bol_alu = $_POST['bol_alu'];
		$gen_alu = $_POST['gen_alu'];
		$tel_alu = $_POST['tel_alu'];
		$cur_alu = $_POST['cur_alu'];
		$nac_alu = $_POST['nac_alu'];
		$ing_alu = date('Y-m-d');

		$bec_alu_ram = $_POST['bec_alu_ram']/100;
		$bec2_alu_ram = $_POST['bec2_alu_ram']/100;

		$dir_alu = $_POST['dir_alu'];
		$cp_alu  = $_POST['cp_alu'];
		$col_alu = $_POST['col_alu'];
		$del_alu = $_POST['del_alu'];
		$ent_alu = $_POST['ent_alu'];
		$tut_alu = $_POST['tut_alu'];
		$tel2_alu = $_POST['tel2_alu'];
		$pro_alu = $_POST['pro_alu'];
		$qr_alu = sha1($correo);

		$fot_alu = $_FILES['fot_alu']['name'];

		if ($fot_alu == "") {
			//VALIDACION SI NO MANDARON FOTO

			$sqlInsercionAlumno = "INSERT INTO alumno (cor_alu, pas_alu, nom_alu, app_alu, apm_alu, bol_alu, gen_alu, tel_alu, cur_alu, nac_alu, ing_alu, dir_alu, cp_alu, col_alu, del_alu, ent_alu, tut_alu, tel2_alu, pro_alu, tip_alu, qr_alu, id_pla8, est_alu, cor1_alu) VALUES ('$correo', '$pas_alu', '$nom_alu', '$app_alu', '$apm_alu', '$bol_alu', '$gen_alu', '$tel_alu', '$cur_alu', '$nac_alu', '$ing_alu', '$dir_alu', '$cp_alu', '$col_alu', '$del_alu', '$ent_alu', '$tut_alu', '$tel2_alu', '$pro_alu', 'Alumno', '$qr_alu', '$plantel', 'Activo', '$cor1_alu')";


			$resultadoInsercionAlumno = mysqli_query($db, $sqlInsercionAlumno);

			if ($resultadoInsercionAlumno) {
				//CARGAR MATERIAS ACORDE A RAMA EN TABLA CALIFICACION

				$sql = "SELECT MAX(id_alu) AS ultimo FROM alumno";
				$resultado = mysqli_query($db, $sql);

				$fila = mysqli_fetch_assoc($resultado);
				$maxAlumno = $fila['ultimo'];


				// FOTO VACIA
				$foto = "foto-alumno00".$maxAlumno.".jpg";


				$fichero = '../../img/usuario.jpg';
			    $nuevo_fichero = '../../uploads/'.$foto;


				if (!copy($fichero, $nuevo_fichero)) {
			        echo "Error al copiar $fichero...\n";
			    } else {

			    	//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
					$sqlUpdateAlumno = "UPDATE alumno SET fot_alu = '$foto' WHERE id_alu = '$maxAlumno'";

					mysqli_query($db, $sqlUpdateAlumno);

			    }
				// FIN FOTO VACIA


				$sqlResponsable = "INSERT INTO alu_res (id_alu11, nom_res) VALUES ('$maxAlumno', '$nomResponsable')";

				mysqli_query($db, $sqlResponsable);

				$sqlAlumnoRama = "INSERT INTO alu_ram ( car_alu_ram, bec_alu_ram, bec2_alu_ram, id_gen1, id_alu1, id_ram3) VALUES ( '$car_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$id_gen', '$maxAlumno', '$id_ram')";
				$resultadoAlumnoRama = mysqli_query($db, $sqlAlumnoRama);

				if ($resultadoAlumnoRama) {



					// ADICION DE CALIFICACIONES Y PARCIALES

					$sqlAluRam = "SELECT MAX(id_alu_ram) AS ultimo FROM alu_ram";
					$resultadoAluRam = mysqli_query($db, $sqlAluRam);

					$filaAluRam = mysqli_fetch_assoc($resultadoAluRam);
					$maxAluRam = $filaAluRam['ultimo'];


				    //TOTAL DE MATERIAS Y ADICION
					$sqlMateria = "
			            SELECT * 
						FROM materia
						WHERE id_ram2 = '$id_ram'
		            ";


					$resultadoMateria = mysqli_query($db, $sqlMateria);



			        $temp = array();
			        $i = 0;

			        while ($filaMateriasAux = mysqli_fetch_array($resultadoMateria)) {
			             $temp[$i] = $filaMateriasAux["id_mat"];
			             $i++;

			        }

			        //var_dump($temp);


			        // EXTRACCION DE PARCIALES eva_ram DE rama
			        
			        $eva_ram = $filaRama['eva_ram'];
			        // SE NECESITA PARA FIJAR CONDICION DE LAS EVALUACIONES POR CICLO Y GENERAR LOS REGISTROS NULOS

			        //ADICION DE REGISTROS NULOS PARA EXISTENCIA DE CARGAS EN MATERIAS POR EVALUAR
			        for ($i = 0 ; $i < count($temp) ; $i++ ) { 
			        	$sqlInsercionCalificacion = "INSERT INTO calificacion (id_alu_ram2, id_mat4) VALUES($maxAluRam, $temp[$i])";
			        	//echo $sqlInsercionCalificacion;
			            mysqli_query($db, $sqlInsercionCalificacion);

			            for ($j = 0; $j < $eva_ram; $j++) { 
			            	// ADICION DE REGISTROS NULOS PARA PARCIALES ACORDE A MATERIAS
			            	$sqlInsercionParcial = "INSERT INTO parcial (id_alu_ram9, id_mat3) VALUES($maxAluRam, $temp[$i])";
			            	mysqli_query($db, $sqlInsercionParcial);
			            }
			            

			        }


			        // ADICION DE PAGOS GLOBALES DE RAMA

			        $sqlPagosRama = "
						SELECT *
						FROM pago_rama
						WHERE id_ram4 = '$id_ram'
			        ";

			        $resultadoPagosRama = mysqli_query($db, $sqlPagosRama);

			        $fec_pag = date('Y-m-d');
			        while($filaPagosRama = mysqli_fetch_assoc($resultadoPagosRama)){
			        	$con_pag = $filaPagosRama['con_pag_ram'];
			        	$mon_pag = $filaPagosRama['mon_pag_ram'];
			        	$pri_pag = $filaPagosRama['pri_pag_ram'];

			        	$sqlInsercionPagoAlumno = "
							INSERT INTO pago(con_pag, mon_pag, pri_pag, fec_pag, est_pag, res_pag, id_alu_ram10) 
							VALUES('$con_pag', '$mon_pag', '$pri_pag', '$fec_pag', 'Pendiente', 'Sistema', '$maxAluRam')
			        	";

			        	mysqli_query($db, $sqlInsercionPagoAlumno);
			        }



			        // ADICION  DE CARGA DE DOCUMENTACION DE PROGRAMA
			        $sqlDocumentosRama = "
						SELECT *
						FROM documento_rama
						WHERE id_ram6 = '$id_ram'
					";

					$resultadoDocumentosRama = mysqli_query( $db, $sqlDocumentosRama );

					while( $filaDocumentosRama = mysqli_fetch_assoc( $resultadoDocumentosRama )){
			        	
			        	$id_doc_ram1 = $filaDocumentosRama['id_doc_ram'];

			        	$sqlInsercionDocumento = "
							INSERT INTO documento_alu_ram ( est_doc_alu_ram, id_doc_ram1, id_alu_ram11 )
							VALUES ( 'Pendiente', $id_doc_ram1, $maxAluRam )
						";

			        	$resultadoInsercionDocumento = mysqli_query($db, $sqlInsercionDocumento);

			        	if ( !$resultadoInsercionDocumento ) {
			        		
			        		echo $sqlInsercionDocumento;
			        	
			        	}
			        }

			        // LOG
			        $nombreAlumno = obtenerNombreAlumnoServer( $maxAluRam );
					$nombrePrograma = obtenerNombreProgramaServer( $id_ram);

					$des_log =  obtenerDescripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno, $nombrePrograma );
				   

					logServer ( 'Alta', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );
					// FIN LOG

					echo "Exito";

				}else{
					echo "Error en insercion de alumno-rama";
					echo $sqlAlumnoRama;

				}
				//echo "Exito";
			}else{
				echo "Error en alta de alumno, verificar consulta";
				echo $sqlInsercionAlumno;
			}

		}else{

			// tercera
			echo 'tercera';
			$sqlInsercionAlumno = "INSERT INTO alumno (cor_alu, pas_alu, nom_alu, app_alu, apm_alu, bol_alu, gen_alu, tel_alu, cur_alu, nac_alu, ing_alu, dir_alu, cp_alu, col_alu, del_alu, ent_alu, tut_alu, tel2_alu, pro_alu, tip_alu, qr_alu, id_pla8, est_alu, cor1_alu) VALUES ('$correo', '$pas_alu', '$nom_alu', '$app_alu', '$apm_alu', '$bol_alu', '$gen_alu', '$tel_alu', '$cur_alu', '$nac_alu', '$ing_alu', '$dir_alu', '$cp_alu', '$col_alu', '$del_alu', '$ent_alu', '$tut_alu', '$tel2_alu', '$pro_alu', 'Alumno', '$qr_alu', '$plantel', 'Activo', '$cor1_alu')";


			$resultadoInsercionAlumno = mysqli_query($db, $sqlInsercionAlumno);

			if ($resultadoInsercionAlumno) {
				//CARGAR MATERIAS ACORDE A RAMA EN TABLA CALIFICACION

				$sql = "SELECT MAX(id_alu) AS ultimo FROM alumno";
				$resultado = mysqli_query($db, $sql);

				$fila = mysqli_fetch_assoc($resultado);
				$maxAlumno = $fila['ultimo'];

				//INSERCION EN RESPONSABLE DEL ALUMNO, QUIEN LO DIO DE ALTA
				$sqlResponsable = "INSERT INTO alu_res (id_alu11, nom_res) VALUES ('$maxAlumno', '$nomResponsable')";

				mysqli_query($db, $sqlResponsable);



				//RENAME Y GUARDADO DE LA FOTO DEL ALUMNO

				$fot_alu = $_FILES['fot_alu']['name'];
				$foto = "foto-alumno00".$maxAlumno.".".end(explode(".", $fot_alu));


				$carpeta_destino = '../../uploads/';
				move_uploaded_file($_FILES['fot_alu']['tmp_name'], $carpeta_destino.$foto);

				//ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
				$sqlUpdateAlumno = "UPDATE alumno SET fot_alu = '$foto' WHERE id_alu = '$maxAlumno'";

				mysqli_query($db, $sqlUpdateAlumno);


				//INSERCION DEL ALUMNO EN SU RAMA CORRESPONDIENTE
				$sqlAlumnoRama = "INSERT INTO alu_ram ( car_alu_ram, bec_alu_ram, bec2_alu_ram, id_gen1, id_alu1, id_ram3) VALUES ( '$car_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$id_gen', '$maxAlumno', '$id_ram')";

				$resultadoAlumnoRama = mysqli_query($db, $sqlAlumnoRama);

				if ($resultadoAlumnoRama) {

					// ADICION DE CALIFICACIONES Y PARCIALES

					$sqlAluRam = "SELECT MAX(id_alu_ram) AS ultimo FROM alu_ram";
					$resultadoAluRam = mysqli_query($db, $sqlAluRam);

					$filaAluRam = mysqli_fetch_assoc($resultadoAluRam);
					$maxAluRam = $filaAluRam['ultimo'];


				    //TOTAL DE MATERIAS Y ADICION
					$sqlMateria = "
			            SELECT * 
						FROM materia
						WHERE id_ram2 = '$id_ram'
		            ";


					$resultadoMateria = mysqli_query($db, $sqlMateria);



			        $temp = array();
			        $i = 0;

			        while ($filaMateriasAux = mysqli_fetch_array($resultadoMateria)) {
			             $temp[$i] = $filaMateriasAux["id_mat"];
			             $i++;

			        }

			        //var_dump($temp);


			        // EXTRACCION DE PARCIALES eva_ram DE rama


			        $filaRama = mysqli_fetch_assoc($resultadoRama);
			        $eva_ram = $filaRama['eva_ram'];
			        // SE NECESITA PARA FIJAR CONDICION DE LAS EVALUACIONES POR CICLO Y GENERAR LOS REGISTROS NULOS

			        //ADICION DE REGISTROS NULOS PARA EXISTENCIA DE CARGAS EN MATERIAS POR EVALUAR
			        for ($i = 0 ; $i < count($temp) ; $i++ ) { 
			        	$sqlInsercionCalificacion = "INSERT INTO calificacion (id_alu_ram2, id_mat4) VALUES($maxAluRam, $temp[$i])";
			        	//echo $sqlInsercionCalificacion;
			            mysqli_query($db, $sqlInsercionCalificacion);

			            for ($j = 0; $j < $eva_ram; $j++) { 
			            	// ADICION DE REGISTROS NULOS PARA PARCIALES ACORDE A MATERIAS
			            	$sqlInsercionParcial = "INSERT INTO parcial (id_alu_ram9, id_mat3) VALUES($maxAluRam, $temp[$i])";
			            	mysqli_query($db, $sqlInsercionParcial);
			            }
			        }



			        // ADICION DE PAGOS GLOBALES DE RAMA

			        $sqlPagosRama = "
						SELECT *
						FROM pago_rama
						WHERE id_ram4 = '$id_ram'
			        ";

			        $resultadoPagosRama = mysqli_query($db, $sqlPagosRama);

			        $fec_pag = date('Y-m-d');
			        while($filaPagosRama = mysqli_fetch_assoc($resultadoPagosRama)){
			        	$con_pag = $filaPagosRama['con_pag_ram'];
			        	$mon_pag = $filaPagosRama['mon_pag_ram'];
			        	$pri_pag = $filaPagosRama['pri_pag_ram'];

			        	$sqlInsercionPagoAlumno = "
							INSERT INTO pago(con_pag, mon_pag, pri_pag, fec_pag, est_pag, res_pag, id_alu_ram10) 
							VALUES('$con_pag', '$mon_pag', '$pri_pag', '$fec_pag', 'Pendiente', 'Sistema', '$maxAluRam')
			        	";

			        	mysqli_query($db, $sqlInsercionPagoAlumno);
			        }



			        // ADICION  DE CARGA DE DOCUMENTACION DE PROGRAMA
			        $sqlDocumentosRama = "
						SELECT *
						FROM documento_rama
						WHERE id_ram6 = '$id_ram'
					";

					$resultadoDocumentosRama = mysqli_query( $db, $sqlDocumentosRama );

					while( $filaDocumentosRama = mysqli_fetch_assoc( $resultadoDocumentosRama )){
			        	
			        	$id_doc_ram1 = $filaDocumentosRama['id_doc_ram'];

			        	$sqlInsercionDocumento = "
							INSERT INTO documento_alu_ram ( est_doc_alu_ram, id_doc_ram1, id_alu_ram11 )
							VALUES ( 'Pendiente', $id_doc_ram1, $maxAluRam )
						";

			        	$resultadoInsercionDocumento = mysqli_query($db, $sqlInsercionDocumento);

			        	if ( !$resultadoInsercionDocumento ) {
			        		
			        		echo $sqlInsercionDocumento;
			        	
			        	}
			        }

			        
			        	
			       	// LOG
			        $nombreAlumno = obtenerNombreAlumnoServer( $maxAluRam );
					$nombrePrograma = obtenerNombreProgramaServer( $id_ram);

					$des_log =  obtenerDescripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno, $nombrePrograma );
				   

					logServer ( 'Alta', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );
					// FIN LOG

					echo "Exito";


				}else{
					echo "Error en insercion de alumno-rama";
					echo $sqlAlumnoRama;

				}
				//echo "Exito";
			}else{
				echo "Error en alta de alumno, verificar consulta";
				echo $sqlInsercionAlumno;
			}

		}

	}

?>