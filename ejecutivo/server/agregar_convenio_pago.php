<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR CONVENIO DE FECHAS DEL PAGO
	//cobranza_alumno.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pag3 = $_POST['id_pag'];

	if ( isset( $_POST['mot_acu_pag'] ) ) {
		$mot_acu_pag = $_POST['mot_acu_pag'];
	}else{
		$mot_acu_pag = "Nulo";
	}


	// DATETIME PARA FILTRADO POR ANTIGUEDAD EN UNION DE obtener_notificaciones.php
	$fec_acu_pag = date('Y-m-d H:i:s');

	// ESTATUS PENDIENTE DE APROBACION DE DIRECCION
	$est_acu_pag = 'Pendiente';


	// FECHAS ORIGINALES
	$ini_acu_pag = $_POST['ini_pag'];
	$fin_acu_pag = $_POST['fin_pag'];


	// FECHAS NUEVAS
	$ini2_acu_pag = $_POST['inicio_convenio'];
	$fin2_acu_pag = $_POST['fin_convenio'];

	$tip_acu_pag = 'Convenio';

	$res_acu_pag = $nomResponsable;

	$sql = "

		INSERT INTO convenio_pago ( mot_acu_pag, tip_acu_pag, fec_acu_pag, ini_acu_pag, fin_acu_pag, ini2_acu_pag, fin2_acu_pag, est_acu_pag, res_acu_pag, id_pag3 )
		VALUES ( '$mot_acu_pag', '$tip_acu_pag', '$fec_acu_pag', '$ini_acu_pag', '$fin_acu_pag', '$ini2_acu_pag', '$fin2_acu_pag', '$est_acu_pag', '$res_acu_pag', '$id_pag3')
		
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		//echo "Exito";

		$con_his_pag = "Solicitud de convenio de fechas del: ".fechaFormateadaCompacta($ini_acu_pag)." al ".fechaFormateadaCompacta($fin_acu_pag).", modificadas del ".fechaFormateadaCompacta($ini2_acu_pag)." al ".fechaFormateadaCompacta($fin2_acu_pag);

		$men_his_pag = "Solicitud de convenio de fechas del: ".fechaFormateadaCompacta($ini_acu_pag)." al ".fechaFormateadaCompacta($fin_acu_pag).", modificadas del ".fechaFormateadaCompacta($ini2_acu_pag)." al ".fechaFormateadaCompacta($fin2_acu_pag);

		$fec_his_pag = $fec_acu_pag;

		$res_his_pag = $nomResponsable;

		$est_his_pag = 'Pendiente';

		$tip_his_pag = "Convenio";

		$med_his_pag = "Sistema";

		$id_pag4 = $id_pag3;


		// INSERCION HISTORIAL
		$sqlInsercionHistorial = "
			INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
			VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
		";



		$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

		if ( !$resultadoInsercionHistorial ) {
			echo $sqlInsercionHistorial;
		}else{
			
			
			$sqlAlumno = "
				SELECT *
				FROM pago
				INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
				INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
				WHERE id_pag = '$id_pag3'
			";

			$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

			$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

			$nombreAlumno = $filaAlumno['nom_alu'].' '.$filaAlumno['app_alu'].' '.$filaAlumno['apm_alu'];
						
		
			// APROBACION DE LA PETICION
	        $identificador_peticion = obtenerUltimoIdentificadorServer( 'convenio_pago', 'id_acu_pag' );
			$tipo_peticion = "Convenio";
			$respuesta_peticion = "Aprobado";
			$motivo_peticion = 'N/A';

			// echo 'identificador_peticion: '.$identificador_peticion;
			
			procesarPeticionServer( $identificador_peticion, $tipo_peticion, $respuesta_peticion, $nomResponsable, $motivo_peticion );
	        // FIN DE APROBACION DE LA PETICION
		}


	}else{

		//echo "Error, verificar consulta";
		echo $sql;
	}
?>