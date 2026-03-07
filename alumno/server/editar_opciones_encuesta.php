<?php 
	//ARCHIVO VIA AJAX PARA EDITAR RESPUESTAS DE EXAMEN
	//examen.php
	require('../inc/cabeceras.php');
	
	$id_enc = $_POST['id_enc'];
	

	if ( isset( $_POST['cerradas'] ) ) {
		$cerradas = $_POST['cerradas'];
		
		for( $i = 0; $i < sizeof( $cerradas ); $i++ ){
			$id_opc = $cerradas[$i];
			$sql = "
				UPDATE opcion 
				SET 
				con_opc = ( con_opc + 1 )
				WHERE 
				id_opc = '$id_opc'
			";

			//echo $sql;

			$resultado = mysqli_query($db, $sql);

			if (!$resultado) {
				
				echo $sql;

			}	
		}
	
	}


	if ( isset( $_POST['multiples'] ) ) {
		$multiples = $_POST['multiples'];
		
		for( $i = 0; $i < sizeof( $multiples ); $i++ ){
			$id_opc = $multiples[$i];
			$sql = "
				UPDATE opcion 
				SET 
				con_opc = ( con_opc + 1 )
				WHERE 
				id_opc = '$id_opc'
			";

			//echo $sql;

			$resultado = mysqli_query($db, $sql);

			if (!$resultado) {
				
				echo $sql;

			}	
		}
	
	}



	if ( isset( $_POST['abiertas'] ) ) {
		$abiertas = $_POST['abiertas'];

		for( $i = 0; $i < sizeof( $abiertas ); $i++ ){

			if ( $abiertas[$i] != '' ) {
				
				$opc_opc_abi = '<strong>'.$nombreCompleto.' - '.$nombrePlantel.'</strong><br>'.$abiertas[$i];

				$id_rea = $_POST['id_rea'];
				$id_rea = $id_rea[$i];


				$sql = "
					INSERT INTO opcion_abierta ( opc_opc_abi, id_rea2 )
					VALUES( '$opc_opc_abi', '$id_rea' )
				";

				$resultado = mysqli_query($db, $sql);

				if (!$resultado) {
					
					echo $sql;

				}
			
			}
			

		}
		
	}
	

	$sql = "
		INSERT INTO encuesta_alumno ( id_alu23, id_enc3 ) VALUES ('$id', '$id_enc')
	";

	$resultado = mysqli_query($db, $sql);

	if (!$resultado) {
		
		echo $sql;

	}

	echo 'Exito';
		

	
	
?>