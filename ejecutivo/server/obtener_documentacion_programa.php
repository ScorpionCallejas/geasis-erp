<?php  
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_ram = $_POST['id_ram'];

	$sqlDoc = "
		SELECT *
		FROM documento_rama
		INNER JOIN rama ON rama.id_ram = documento_rama.id_ram6
		WHERE id_ram6 = '$id_ram'
	";

	$resultadoTotal = mysqli_query( $db, $sqlDoc );

	$total = mysqli_num_rows( $resultadoTotal );

	if ( $total > 0 ) {
?>
		<br>
		<table class="table">

			<tbody>
				<?php
					$resultadoDoc = mysqli_query( $db, $sqlDoc );
					$contador = 0;

					while( $filaDoc = mysqli_fetch_assoc( $resultadoDoc ) ){
				?>
						<tr>

							<td class="letraMediana">
								
								<div class="form-check form-check-inline">
		                        
		                            <input type="checkbox" class="form-check-input documentacionPrograma" id="documentacionPrograma<?php echo $contador; ?>" name="documentacion_alumno[]" value="<?php echo $filaDoc['id_doc_ram']; ?>">
		                            <label class="form-check-label" for="documentacionPrograma<?php echo $contador; ?>"><?php echo $filaDoc['nom_doc_ram']; ?></label>

		                        </div>
							</td>							
						</tr>
				<?php
						$contador++;
					}
				?>
				
			</tbody>
			
		</table>
<?php
	}
?>