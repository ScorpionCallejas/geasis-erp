<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO BLOQUE
	//bloques.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	$fechaHoy = date('Y-m-d H:i:s');
	
	$id_gru = $_GET['id_gru'];

	$profesor = $_POST['profesor'];
	$materia = $_POST['materia'];
	$salon = $_POST['salon'];

	$nom_sub_hor = $_POST['nom_sub_hor'];


	$lunes = $_POST['lunes'];
	$ini_hor_lun = $_POST['ini_hor_lun'];
	$fin_hor_lun = $_POST['fin_hor_lun'];


	$martes = $_POST['martes'];
	$ini_hor_mar = $_POST['ini_hor_mar'];
	$fin_hor_mar = $_POST['fin_hor_mar'];


	$miercoles = $_POST['miercoles'];
	$ini_hor_mie = $_POST['ini_hor_mie'];
	$fin_hor_mie = $_POST['fin_hor_mie'];


	$jueves = $_POST['jueves'];
	$ini_hor_jue = $_POST['ini_hor_jue'];
	$fin_hor_jue = $_POST['fin_hor_jue'];


	$viernes = $_POST['viernes'];
	$ini_hor_vie = $_POST['ini_hor_vie'];
	$fin_hor_vie = $_POST['fin_hor_vie'];

	$sabado = $_POST['sabado'];
	$ini_hor_sab = $_POST['ini_hor_sab'];
	$fin_hor_sab = $_POST['fin_hor_sab'];

	$domingo = $_POST['domingo'];
	$ini_hor_dom = $_POST['ini_hor_dom'];
	$fin_hor_dom = $_POST['fin_hor_dom'];




	// CONSULTA A FECHA DE ARRANQUE DEL CICLO ASOCIADO AL HORARIO QUE PERTENECE AL GRUPO
	$sqlCiclo = "
		SELECT *
		FROM grupo
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		WHERE id_gru = '$id_gru'

		";

	$resultadoCiclo = mysqli_query($db, $sqlCiclo);

	$filaCiclo = mysqli_fetch_assoc($resultadoCiclo);
	$ini_cic = $filaCiclo['ini_cic'];

	//echo $ini_cic;




	for ($i = 0; $i < sizeof($profesor); $i++) {
		$sqlSub = "
			INSERT INTO sub_hor( id_gru1, id_mat1, id_pro1, nom_sub_hor, est_sub_hor, fec_sub_hor, id_sal1 ) 
			VALUES( '$id_gru', '$materia[$i]', '$profesor[$i]', '$nom_sub_hor[$i]', 'Activo', '$fechaHoy', '$salon[$i]' )
		";
		mysqli_query($db, $sqlSub);

		$sqlMax = "SELECT MAX(id_sub_hor) AS maximo FROM sub_hor";
		$resultadoMax = mysqli_query($db, $sqlMax);
		$filaMax = mysqli_fetch_assoc($resultadoMax);
		$maximo = $filaMax['maximo'];


		// LOG
		$filaDatos = obtenerDatosGrupalesServer( $maximo );
		$nombreMateria = $filaDatos['nom_mat'];
		$nombreClave = $filaDatos['nom_sub_hor'];
		$nombrePrograma = $filaDatos['nom_ram'];

		$des_log =  obtenerDescripcionHorarioLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreMateria, $nombreClave, $nombrePrograma );
	   

		logServer ( 'Alta', $tipoUsuario, $id, 'Grupo', $des_log, $plantel );
		// FIN LOG


		$sqlInsertHistorialProfesor = "
			INSERT INTO historial_profesor ( tip_his_pro, fec_his_pro, id_sub_hor8, id_pro5 ) 
			VALUES ( 'Alta', '$fechaHoy', '$maximo', '$profesor[$i]' )
		";

		$resultadoInsertHistorialProfesor = mysqli_query( $db, $sqlInsertHistorialProfesor );

		if ( $resultadoInsertHistorialProfesor ) {
			// EXITO EN ALTA DE HISTORIAL PROFESOR


			// AGREGADO DE SALA PARA MENSAJERIA GRUPAL DE MATERIA
			$sqlMateria = "
				SELECT *
				FROM materia
				WHERE id_mat = '$materia[$i]'
			";

			$resultadoMateria = mysqli_query($db, $sqlMateria);

			if (!$resultadoMateria) {
				echo $sqlMateria;
			}
			$filaMateria = mysqli_fetch_assoc($resultadoMateria);

			$nom_sal = "Sala de ".$filaMateria['nom_mat'];
			$sqlInsercionSala = "
				INSERT INTO sala( nom_sal, id_sub_hor6, id_pla6 ) VALUES('$nom_sal', $maximo, '$plantel')
			";

			$resultadoInsercionSala = mysqli_query($db, $sqlInsercionSala);

			if (!$resultadoInsercionSala) {
				echo $sqlInsercionSala;
			}



			// PROFESOR A SALA DE MENSAJERIA
			$sqlSala = "
				SELECT *
				FROM sala
				WHERE id_sub_hor6 = '$maximo'
			";

			$datosSala = obtener_datos_consulta( $db, $sqlSala );

			if ( $datosSala['total'] == 1 ) {

				$id_usuario = $profesor[$i];
				$tipo_usuario = 'Profesor';
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
			// FIN PROFESOR A SALA DE MENSAJERIA

			//VALIDAR SI EXISTEN LAS DOS FECHAS (SI SON DIFERENTES DE NULL RECORDAR QUE NO ES NECESARIO PARA CREAR NINGUNA ACT SINO NADAMAS EL TITULO)***
			//RECALENDARIZACION DE ACTIVIDADES
			// P1- EXTRACCION DE BLOQUES Y SUS IDS

			$sqlBloque = "SELECT * FROM bloque WHERE id_mat6 = '$materia[$i]'";
			$resultadoBloque = mysqli_query($db, $sqlBloque);

			
			//WHILE BLOQUES
			while($filaBloque = mysqli_fetch_assoc($resultadoBloque)){

				$id_blo = $filaBloque['id_blo'];

				// P2- FOROS ASOCIADOS AL BLOQUE
				$sqlForo = "SELECT * FROM foro WHERE id_blo4 = '$id_blo'";
				$resultadoForo = mysqli_query($db, $sqlForo);


				while($filaForo = mysqli_fetch_assoc($resultadoForo)){
					
					

					if ($filaForo['ini_for'] == "" OR $filaForo['fin_for'] == "") {
						
					}else{

						$ini_for_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaForo['ini_for'].' day' , strtotime ( $ini_cic )));
						$fin_for_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaForo['fin_for'].' day' , strtotime ( $ini_cic )));
						$id_for1     = $filaForo['id_for']; //EXTRACCION DE CLAVE FORANEA 
						$id_sub_hor2 = $maximo; //EXTRACCION DE LA OTRA CLAVE FORANEA

						$sqlForoCopia = "INSERT INTO foro_copia(ini_for_cop, fin_for_cop, id_for1, id_sub_hor2) VALUES('$ini_for_cop', '$fin_for_cop', '$id_for1', '$id_sub_hor2')";
						$resuladoForoCopia = mysqli_query($db, $sqlForoCopia);
					}
					
				}


				// P3- ENTREGABLES ASOCIADOS AL BLOQUE
				
				$sqlEntregable = "SELECT * FROM entregable WHERE id_blo5 = '$id_blo'";
				$resultadoEntregable = mysqli_query($db, $sqlEntregable);


				while($filaEntregable = mysqli_fetch_assoc($resultadoEntregable)){
					
					

					if ($filaEntregable['ini_ent'] == "" OR $filaEntregable['fin_ent'] == "") {
						
					}else{
				
						$ini_ent_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaEntregable['ini_ent'].' day' , strtotime ( $ini_cic )));
						$fin_ent_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaEntregable['fin_ent'].' day' , strtotime ( $ini_cic )));
						$id_ent1     = $filaEntregable['id_ent']; //EXTRACCION DE CLAVE FORANEA 
						$id_sub_hor3 = $maximo; //EXTRACCION DE LA OTRA CLAVE FORANEA

						$sqlEntregableCopia = "INSERT INTO entregable_copia(ini_ent_cop, fin_ent_cop, id_ent1, id_sub_hor3) VALUES('$ini_ent_cop', '$fin_ent_cop', '$id_ent1', '$id_sub_hor3')";

						//echo $sqlEntregableCopia;
						$resuladoEntregableCopia = mysqli_query($db, $sqlEntregableCopia);
					}
					


				}


				//P4- EXAMENES ASOCIADOS AL BLOQUE
				$sqlExamen = "SELECT * FROM examen WHERE id_blo6 = '$id_blo'";
				$resultadoExamen = mysqli_query($db, $sqlExamen);


				while($filaExamen = mysqli_fetch_assoc($resultadoExamen)){
					
					

					if ($filaExamen['ini_exa'] == "" OR $filaExamen['fin_exa'] == "") {
						
					}else{
						$ini_exa_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaExamen['ini_exa'].' day' , strtotime ( $ini_cic )));
						$fin_exa_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaExamen['fin_exa'].' day' , strtotime ( $ini_cic )));
						$id_exa1     = $filaExamen['id_exa']; //EXTRACCION DE CLAVE FORANEA 
						$id_sub_hor4 = $maximo; //EXTRACCION DE LA OTRA CLAVE FORANEA

						$sqlExamenCopia = "INSERT INTO examen_copia(ini_exa_cop, fin_exa_cop, id_exa1, id_sub_hor4) VALUES('$ini_exa_cop', '$fin_exa_cop', '$id_exa1', '$id_sub_hor4')";
						$resuladoExamenCopia = mysqli_query($db, $sqlExamenCopia);
					}

				}

			}
			//FIN WHILE BLOQUES


			

			if($lunes[$i] != "vacio"){
				$sqlLunes = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_lun[$i]', '$fin_hor_lun[$i]', '$lunes[$i]', '$maximo')";

				mysqli_query($db, $sqlLunes);


			}


			if($martes[$i] != "vacio"){
				$sqlMartes = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_mar[$i]', '$fin_hor_mar[$i]', '$martes[$i]', '$maximo')";

				mysqli_query($db, $sqlMartes);


			}
			

			if($miercoles[$i] != "vacio"){
				$sqlMiercoles = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_mie[$i]', '$fin_hor_mie[$i]', '$miercoles[$i]', '$maximo')";

				mysqli_query($db, $sqlMiercoles);


			}


			if($jueves[$i] != "vacio"){
				$sqlJueves = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_jue[$i]', '$fin_hor_jue[$i]', '$jueves[$i]', '$maximo')";

				mysqli_query($db, $sqlJueves);


			}	


			if($viernes[$i] != "vacio"){
				$sqlViernes = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_vie[$i]', '$fin_hor_vie[$i]', '$viernes[$i]', '$maximo')";

				mysqli_query($db, $sqlViernes);



			}	

			if($sabado[$i] != "vacio"){
				$sqlSabado = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_sab[$i]', '$fin_hor_sab[$i]', '$sabado[$i]', '$maximo')";

				mysqli_query($db, $sqlSabado);


			}	


			if($domingo[$i] != "vacio"){
				$sqlDomingo = "INSERT INTO horario(ini_hor, fin_hor, dia_hor, id_sub_hor1) VALUES('$ini_hor_dom[$i]', '$fin_hor_dom[$i]', '$domingo[$i]', '$maximo')";	

				mysqli_query($db, $sqlDomingo);

			}	


			// FIN EXITO EN ALTA DE HISTORIAL PROFESOR
		} else {
			echo $sqlInsertHistorialProfesor;
		}	
		
	}



	echo "true";
		
	
?>