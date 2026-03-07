<?php  
	//ARCHIVO VIA AJAX PARA VALIDAR ALUMNO
	//consulta_alumno.php
	
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Si viene la validación de alumno
	if (isset($_POST['validar_alumno']) && $_POST['validar_alumno'] == 1) {
		
		$id_alu_ram = $_POST['id_alu_ram'];
		
		// Obtener datos del alumno para el log
		$datosAlumno = obtenerDatosAlumnoProgramaServer($id_alu_ram);
		$nombreAlumno = $datosAlumno['nom_alu'];

		// VALIDACIÓN DEL ALUMNO
		$sql = "
			UPDATE alu_ram 
			SET 
				val_alu_ram = 'Validado',
				fec_alu_ram = NOW(),
				eje_alu_ram = '$usuario - $nombreCompleto'
			WHERE 
				id_alu_ram = '$id_alu_ram'
		";
		
		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			
			// LOG      
	        $des_log = "El $tipoUsuario $nombreCompleto validó al alumno $nombreAlumno";
	       
	        logServer('Validación', $tipoUsuario, $id, 'Alumno', $des_log, $plantel);
	        // FIN LOG

			echo "Exito";
		
		} else {
		
			echo "Error al validar";
		
		}

	} else {
		// FUNCIONALIDAD ORIGINAL DEL ESTATUS
		$id_alu = $_POST['id_alu'];
		$estatus = $_POST['estatus'];

		$nombreAlumno = obtenerNombreTablaAlumnoServer( $id_alu );

		if ( $estatus == 'nulo' ) {

			//ACTIVACION  
			$sql = "
				UPDATE alumno 
				SET 
				est_alu = 'Activo' 
				WHERE 
				id_alu = '$id_alu'
			";
			
			$resultado = mysqli_query( $db, $sql );

			if ( $resultado ) {

				
				// LOG      
		        $des_log =  obtenerDescripcionActivacionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'activó', $nombreAlumno );
		       
		        logServer ( 'Cambio', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );
		        // FIN LOG

		        $sqlFecha = "
		        	UPDATE alu_ram
		        	SET
		        	act_alu_ram = '$fechaHoy',
		        	est2_alu_ram = '$nomResponsable'
		        	WHERE id_alu1 = '$id_alu'
		        ";

		        $resultadoFecha = mysqli_query( $db, $sqlFecha );

		        if ( !$resultadoFecha ) {
		        	echo $sqlFecha;
		        }

			
				echo "Exito";
			
			} else {
			
				echo "Error al activar";
			
			}

		} else {

			if ( $estatus == 'Inactivo' ) {
				//ACTIVACION  
				$sql = "
					UPDATE alumno 
					SET 
					est_alu = 'Activo' 
					WHERE 
					id_alu = '$id_alu'
				";
				
				$resultado = mysqli_query( $db, $sql );

				if ( $resultado ) {

					
					// LOG      
			        $des_log =  obtenerDescripcionActivacionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'activó', $nombreAlumno );
			       
			        logServer ( 'Cambio', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );
			        // FIN LOG

				
					echo "Exito";
				
				} else {
				
					echo "Error al activar";
				
				}

			}else if( $estatus == 'Activo' ) {
				//DESACTIVACION 
				$sql = "
					UPDATE alumno 
					SET 
					est_alu = 'Inactivo' 
					WHERE
					id_alu = '$id_alu'
				";

				$resultado = mysqli_query( $db, $sql );
				
				if ( $resultado ) {

					// LOG      
			        $des_log =  obtenerDescripcionActivacionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'desactivó', $nombreAlumno );
			       
			        logServer ( 'Cambio', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );
			        // FIN LOG
				
					echo "Exito";
				
				} else {
				
					echo "Error al desactivar";
				
				}

			}
		}
	}

?>