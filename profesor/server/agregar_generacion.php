<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO GENERACION
	//alumnos_carrera.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	if ( isset( $_POST['id_ram'] ) ) {

		$nom_gen = $_POST['nom_gen'];
		$ini_gen = $_POST['ini_gen'];
		$fin_gen = $_POST['fin_gen'];
		$id_ram5 = $_POST['id_ram'];
		$val_gen_pag = $_POST['checkbox_pagos'];

		$sql = "INSERT INTO generacion (nom_gen, ini_gen, fin_gen, id_ram5, val_gen_pag) VALUES ('$nom_gen', '$ini_gen', '$fin_gen', '$id_ram5', '$val_gen_pag')";

		// echo $sql;

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			// LOG
			$nombreRama = obtenerNombreProgramaServer ( $id_ram5 );

	        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'generación académica', $nom_gen, $nombreRama );
	       
	        logServer ( 'Alta', $tipoUsuario, $id, 'Generación', $des_log, $plantel );
	        // FIN LOG

	        // ULTIMO id_gen
	        $id_gen2 = obtenerUltimoIdentificadorServer( 'generacion', 'id_gen' );

	        // ALTA DE MENSAJERIA DE GENERACION

	        $fec_men_sal = date('Y-m-d H:i:s');
	        $nom_sal = $nom_gen;

	        $sqlSala = "
	        	INSERT INTO sala ( nom_sal, fec_men_sal, id_gen3 ) 
	        	VALUES ( '$nom_sal', '$fec_men_sal', '$id_gen2' )
	        ";

	        $resultadoSala = mysqli_query( $db, $sqlSala );

	        if ( !$resultadoSala ) {
	        	
	        	echo $sqlSala;

	        }

	        $id_sal = obtenerUltimoIdentificadorServer( 'sala', 'id_sal' );

	        // ADMINISTRATIVOS A SALA DE GENERACION
	        $sqlDirectivos = "
				SELECT 
				id_pla3,
				id_emp7 AS identificador_empleado,
				id_adm AS identificador,
				nom_adm AS nombre, 
				app_adm AS apellido1, 
				apm_adm AS apellido2, 
				gen_adm AS genero, 
				tel_adm AS telefono, 
				cor_adm AS correo, 
				nac_adm AS nacimiento, 
				ing_adm AS ingreso, 
				fot_emp AS foto, 
				dir_adm AS direccion, 
				cp_adm AS cp,
				pas_adm AS password,
				tip_adm AS tipo
				FROM admin
				INNER JOIN empleado ON empleado.id_emp = admin.id_emp7
	            WHERE id_pla3 = '$plantel'
				UNION
				SELECT
	            id_pla4,
	            id_emp6 AS identificador_empleado,
	            id_adg AS identificador,
				nom_adg AS nombre, 
				app_adg AS apellido1, 
				apm_adg AS apellido2, 
				gen_adg AS genero, 
				tel_adg AS telefono, 
				cor_adg AS correo, 
				nac_adg AS nacimiento, 
				ing_adg AS ingreso, 
				fot_emp AS foto, 
				dir_adg AS direccion, 
				cp_adg AS cp,
				pas_adg AS password,
				tip_adg AS tipo
				FROM adminge
				INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6
				WHERE id_pla4 = '$plantel'
				UNION
				SELECT
	            id_pla9,
	            id_emp8 AS identificador_empleado,
	            id_cob AS identificador,
				nom_cob AS nombre, 
				app_cob AS apellido1, 
				apm_cob AS apellido2, 
				gen_cob AS genero, 
				tel_cob AS telefono, 
				cor_cob AS correo, 
				nac_cob AS nacimiento, 
				ing_cob AS ingreso, 
				fot_emp AS foto, 
				dir_cob AS direccion, 
				cp_cob AS cp,
				pas_cob AS password,
				tip_cob AS tipo
				FROM cobranza
				INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
	            WHERE id_pla9 = '$plantel'
	            ORDER BY ingreso DESC
			";

			$resultadoDirectivos = mysqli_query( $db, $sqlDirectivos );

			if ( !$resultadoDirectivos ) {
			
				echo $sqlDirectivos;
			
			}

			while( $filaDirectivos = mysqli_fetch_assoc( $resultadoDirectivos ) ){

				$id_usuario = $filaDirectivos['identificador'];
				$tipo_usuario = $filaDirectivos['tipo'];
				
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
	        // FIN ADMINISTRATIVOS A SALA DE GENERACION

	        // FIN ALTA DE MENSAJERIA DE GENERACION

	        if ( $_POST['checkbox_pagos'] == 'Activo' ) {
	        //
				// ALGORITMO generacion_pagos
	        	

	        	$datosGeneracion = obtenerDatosGeneracionProgramaLogServer( $id_gen2 );

	        	$pag_ram = $datosGeneracion['pag_ram'];
	        	
	        	for( $i = 0; $i < $pag_ram; $i++ ){

					// SQL

					if ( $i == 0 ) {
						
						$tip_gen_pag = 'Inscripción';
						generar_pago_generacion_server( $id_gen2, $tip_gen_pag );

					} else {

						$tip_gen_pag = 'Colegiatura';
						generar_pago_generacion_server( $id_gen2, $tip_gen_pag );
					}


				}
	        	// FIN ALGORITMO generacion_pagos



	        	echo $id_gen2;

	        	// GENERACION DE REGISTROS DE PAGO

	        //
	        } else if ( $_POST['checkbox_pagos'] == 'Inactivo' ) {
	        
	        	echo 'Exito';
	        
	        }
			
			// echo "Exito";

		}else{
			echo "error, verificar consulta!";
			//echo $sql;
		}		
		
	
	}

	
		
	
?>