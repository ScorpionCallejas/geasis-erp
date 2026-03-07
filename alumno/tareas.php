<?php  
 
	include('inc/header.php');
	
	$sqlAlumno = "
		SELECT *
		FROM alu_ram
		WHERE id_alu1 = '$id'
	";

	$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

	$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

	$id_alu_ram = $filaAlumno['id_alu_ram'];
?>

	<!-- Central Modal Medium Danger -->
	 <div class="modal fade" id="modal_aviso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	   aria-hidden="true">
	   <div class="modal-dialog modal-frame modal-bottom modal-notify modal-danger" role="document">
	     <!--Content-->
	     <div class="modal-content">
	       <!--Header-->
	       <div class="modal-header">
	         <p class="heading lead">Aviso del equipo de PLATAFORMA</p>

	         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	           <span aria-hidden="true" class="white-text">&times;</span>
	         </button>
	       </div>

	       <!--Body-->
	       <div class="modal-body">
	         <div class="text-center">

	           <i class="fas fa-exclamation-triangle fa-4x mb-3 animated rotateIn"></i>
	           <p>
	           	Atención, estimado alumno. Te comentamos que puedes subir tus tareas después de las 7pm. Estamos trabajando en solucionar algunos errores que nos han reportado.
	           	
	           	<br>

	           	Por tu comprensión, gracias.
	           </p>
	         </div>
	       </div>

	       <!--Footer-->
	       <div class="modal-footer justify-content-center">
	         <a type="button" class="btn btn-sm btn-rounded btn-secondary waves-effect" data-dismiss="modal">Entendido</a>
	       </div>
	     </div>
	     <!--/.Content-->
	   </div>
	 </div>


	 
	 <!-- Central Modal Medium Danger-->
	
	<table class="table">
		
		<thead>
		
			<tr>
		
				<th>#</th>
				<th>Materia/Bloque</th>
				<th>Fecha</th>
				<th>Archivo</th>
				<th>Estatus</th>
				<th>Accion</th>
		
			</tr>
		
		</thead>

		<tbody>

			<?php

				$sql = "
					SELECT *
					FROM tarea
					INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = tarea.id_ent_cop1
					INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
					INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
					INNER JOIN materia ON materia.id_mat = bloque.id_mat6
					WHERE id_alu_ram6 = '$id_alu_ram'
				";

				$resultado = mysqli_query( $db, $sql );
				$i = 1;
				while( $fila = mysqli_fetch_assoc( $resultado ) ){
					$estatus_tarea = obtener_estatus_tarea( $fila['id_tar'] );

			?>
				<tr>
					
					<td><?php echo $i; ?></td>
					
					<td>
						<?php echo $fila['nom_mat'].' /// '.$fila['nom_blo']; ?>
					</td>

					<td><?php echo fechaFormateadaCompacta2( $fila['fec_tar'] ); ?></td>	
					
					<td>
						
						<?php  
							if ( $estatus_tarea == 1 ) {
						?>

								<a href="../uploads/<?php echo $fila['doc_tar']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['doc_tar']; ?>">
				            
						            <?php echo $fila['doc_tar']; ?>
						            
						        </a>
						<?php
							} else {
						?>
								<a href="../uploads/<?php echo $fila['doc_tar']; ?>" download class="btn-link text-danger" title="Descargar: <?php echo $fila['doc_tar']; ?>">
				            
						            <?php echo $fila['doc_tar']; ?>
						            
						        </a>
						<?php
							}
						?>
						
							
					</td>
					
					<td><?php echo $estatus_tarea; ?></td>

					<td>####</td>

				</tr>

			<?php
					obtener_existencia_tarea( $fila['id_tar'], $fila['id_alu_ram6'], $fila['id_ent_cop1'] );
					$i++;
				}

			?>
			
		</tbody>

	</table>
	
<?php
	
	include('inc/footer.php');

?>

<script>
 	$('#modal_aviso').modal('show');
 </script>