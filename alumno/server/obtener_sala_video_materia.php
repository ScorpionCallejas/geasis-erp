<?php
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['materia'];	
	$id_alu_ram = $_POST['id_alu_ram'];

	$descarga = $_POST['descarga'];
	$subida = $_POST['subida'];
	$latencia = $_POST['latencia'];

	// $sqlUpdate = "
	// 	UPDATE alumno
	// 	SET
	// 	dow_alu = '$descarga',
	// 	upl_alu = '$subida',
	// 	pin_alu = '$latencia'
	// 	WHERE id_alu = '$id'
	// ";

	// $resultadoUpdate = mysqli_query( $db, $sqlUpdate );


	$des_log = obtenerDescripcionInternetUsuarioLogServer( $tipo, $nombreCompleto, $descarga, $subida, $latencia  );
	logServer( 'Cambio', $tipoUsuario, $id, 'Internet', $des_log, $plantel );

	
	$sqlSubhor = "
		SELECT * 
		FROM alu_hor
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    	WHERE id_sub_hor = '$id_sub_hor' AND id_alu_ram1 = '$id_alu_ram'
	";

	//echo $sqlSubhor;

	$resultadoSubhor = mysqli_query($db, $sqlSubhor);

	$filaSubhor = mysqli_fetch_assoc($resultadoSubhor);

	$nom_mat = $filaSubhor['nom_mat'];


	$sqlSala = "
		SELECT * 
		FROM sala
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = sala.id_sub_hor6
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
    	WHERE id_sub_hor = '$id_sub_hor'
	";

	// echo $sqlSala;

	$resultadoValidacionSala = mysqli_query($db, $sqlSala);

	$totalValidacionSala = mysqli_num_rows($resultadoValidacionSala);

	if ($totalValidacionSala == 0) {
		// NO EXISTE LA SALA
		//SE CREA LA SALA
		$nom_sal = "Sala de ".$filaSubhor['nom_mat'];
		
		$sqlInsercionSala = "
			INSERT INTO sala( nom_sal, id_sub_hor6, id_pla6 ) VALUES('$nom_sal', $id_sub_hor, '$plantel')
		";

		$resultadoInsercionSala = mysqli_query($db, $sqlInsercionSala);

		if ($resultadoInsercionSala) {
		// VALIDACION DE INSERCION
			$sqlMaximaSala = "
				SELECT MAX(id_sal) AS maxima 
				FROM sala
			";

			$resultadoMaximaSala = mysqli_query($db, $sqlMaximaSala);

			if ($resultadoMaximaSala) {
				// VALIDACION DE EXTRACCION DEL MAXIMO SALA
				$filaMaximaSala = mysqli_fetch_assoc($resultadoMaximaSala);

				$id_sal = $filaMaximaSala['maxima'];

				$sqlUltimaSala = "
					SELECT *
					FROM sala
					WHERE id_sal = '$id_sal'
				";

				$resultadoUltimaSala = mysqli_query($db, $sqlUltimaSala);

				if ($resultadoUltimaSala) {
					
					$filaUltimaSala = mysqli_fetch_assoc($resultadoUltimaSala);

					$nom_sal = $filaUltimaSala['nom_sal'];
				}


			}else{
				echo $sqlMaximaSala;
			}

			
		}else{
			echo $sqlInsercionSala;
		}



	}else{
		
		$resultadoSalaMateria = mysqli_query($db, $sqlSala);

		$filaSalaMateria = mysqli_fetch_assoc($resultadoSalaMateria);

		//DATOS SALA
		$nom_sal = $filaSalaMateria['nom_sal'];
		$id_sal = $filaSalaMateria['id_sal'];


		//echo $sqlCompaneros;
	}

	
?>

<div class="container animated fadeIn">

	<div class="row grey text-center">
		<h4 class="white-text p-3">
			<i class="fas fa-comments"></i>
			<?php  
				echo $nom_sal;
			?>
		<h4/>
	</div>

	<br>

	<h1 id="contadorAlumnos">
		
	</h1>
	

	
	<!-- TABLA LISTADO HISTORICO PROFESORES -->

	<div class="row">
	    <div class="col-md-12 text-center">
	    	<p>Código:</p>
	        <h2 id="codigo" class="text-success">
	            <?php echo 'sala-'.$id_sub_hor; ?>
	        </h2>
	        
	        <p class="text-danger">Escribe MANUALMENTE (SIN COPIAR) el código y presiona "CONTINUAR". Ejemplo: "sala-<?php echo $id_sub_hor; ?>"</p>
			
	        <iframe id= "clase" src="https://letsmeet.no/" frameborder="0" height="1200" width="1050" allow="microphone; camera"></iframe>
	    </div>
	</div>
	<!-- FIN TABLA HISTORICO PROFESORES -->

	
	
</div>