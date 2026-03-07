<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_ram = $_POST['id_ram'];

	
  //var_dump($alumnos);

	$sqlGeneracion = "
		SELECT *
		FROM generacion
		WHERE id_ram5 = '$id_ram'
		ORDER BY id_gen DESC
	";

	$resultadoTotalGeneracion = mysqli_query( $db, $sqlGeneracion );

	$totalGeneracion = mysqli_num_rows( $resultadoTotalGeneracion );

	if ( $totalGeneracion > 0 ) {
?>
		<!-- SELECT  -->
		<select class="form-control seleccionGeneracion" style="font-size: 9px;" name="id_gen[]" required="">
			<?php

				$resultadoGeneracion = mysqli_query( $db, $sqlGeneracion );
				$contadorGeneracion = 0;

				while($filaGeneracion = mysqli_fetch_assoc($resultadoGeneracion)){



			?>
						<?php
							if ( $contadorGeneracion == 0 ) {
						?>
							
								<option value="<?php echo $filaGeneracion['id_gen']; ?>" selected=""><?php echo $filaGeneracion['nom_gen']; ?></option>

						<?php
							} else {
						?>

								<option value="<?php echo $filaGeneracion['id_gen']; ?>"><?php echo $filaGeneracion['nom_gen']; ?></option>

						<?php
							}
						?>
						
			<?php
					$contadorGeneracion++;
					
				}
			?>
		</select>
		<!-- FIN SELECT -->

<?php	
	} else {
?>
		<span class="text-bold">
			Sin generaciones
		</span>
<?php
	}
?>