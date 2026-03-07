<?php 
	//ARCHIVO VIA AJAX PARA OBTENER TRABAJOS ESPECIALES
	//trabajos_especiales.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];

	$fechaHoy = date( 'Y-m-d' );

	$sqlProyectos = "
		SELECT *
		FROM proyecto_alu_ram
		INNER JOIN proyecto ON proyecto.id_pro = proyecto_alu_ram.id_pro1
		WHERE ( id_alu_ram15 = '$id_alu_ram' ) AND ( ini_pro_alu_ram <= '$fechaHoy' )
		ORDER BY id_pro DESC
	";

	$resultadoTotalProyectos = mysqli_query( $db, $sqlProyectos );

	$totalProyectos = mysqli_num_rows( $resultadoTotalProyectos );

	if ( $totalProyectos == 0 ) {
?>
		
		<div class="row p-3">


			<div class="col-md-12 text-center">
				
				<h4>
					<span class="badge badge-warning">
						¡No hay trabajos especiales!
					</span>
				</h4>
				
				<img src="../img/pensativo.gif" width="10%" class="animated tada delay-2s">
				
				
				<br>
				<br>


				<h5>
					<span class="badge badge-warning">
						¡Notifícalo a la administración del plantel!
					</span>
				</h5>

				<br>
				<br>


			</div>
			
			

		</div>

<?php
	} else {

?>
	
	<div class="row p-3">

<?php 
		$i = 1;
		$resultadoProyectos = mysqli_query( $db, $sqlProyectos );

		while( $filaProyectos = mysqli_fetch_assoc( $resultadoProyectos ) ) {
			$id_pro = $filaProyectos['id_pro'];
			$id_pro_alu_ram = $filaProyectos['id_pro_alu_ram'];
			$estatus = obtenerEstatusProyectoAlumnoServer( $id_pro_alu_ram );
?>
		
			<div class="col-md-4">
				

				<div class="card" style="border-radius: 20px;">
				
					<div class="card-body" style="position: relative;">
						

						

						<span class="letraMediana" style="position: absolute; left: 20px; bottom: 1px;">
							<?php  
								echo $estatus;
							?>
						</span>
						

						<span class="badge badge-pill badge-light letraPequena font-weight-normal" style="position: absolute; right: 10px; top: 1px;" title="Este es un trabajo especial"> <i class="fas fa-star text-warning"></i> Trabajo especial</span>
		

						<?php  
							$formatoArchivo = obtenerFormatoArchivo( $filaProyectos['arc_pro'] );
						?>

						
						<div style="position: absolute; right: 20px; " class="text-right">
							
							<a href="../archivos/<?php echo $filaProyectos['arc_pro']; ?>" download class="btn-link" title="Descargar: <?php echo $filaProyectos['arc_pro']; ?>">
	                              
	                            <?php  
	                                	if ( $formatoArchivo == 'docx' ) {
	                            ?>
	                                  		<i class="fas fa-file-word fa-3x blue-text"></i>

	                            <?php
	                                	} else if ( $formatoArchivo == 'pptx' ) {
	                            ?>
	                                		<i class="fas fa-file-powerpoint fa-3x orange-text"></i>

	                            <?php 
	                                	} else if ( $formatoArchivo == 'pdf' ) {
	                            ?>
	                                  		<i class="fas fa-file-pdf fa-3x red-text"></i>

	                            <?php 
	                                	} else if ( ( $formatoArchivo == 'xls' ) || ( $formatoArchivo == 'xlsx' ) ){
	                            ?>

	                                  		<i class="fas fa-file-excel fa-3x green-text"></i>

	                            <?php
	                                	} else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
	                            ?>  

	                                  		<i class="fas fa-image fa-3x orange-text"></i>

	                            <?php
	                                	}
	                            ?>

	                            <br>
	                            <span class="letraMediana">
	                            	Descargar	
	                            </span>
	                            
	                        </a>

    						

						</div>
						
						<a href="#" class="btn-link text-primary obtenerProyecto" id_pro_alu_ram="<?php echo $filaProyectos['id_pro_alu_ram']; ?>" nom_pro="<?php echo $filaProyectos['nom_pro']; ?>" style="position: relative;">

							<?php  
		            
				                if ( $estatus == 'Activa' ) {
				            		// echo 'activa';
				            ?>
				                	
				            		<?php  
				            			if ( isset( $_POST['id_pro_alu_ram'] ) && ( $_POST['id_pro_alu_ram'] == $filaProyectos['id_pro_alu_ram'] ) ) {

				            				// echo 'if';
				            		?>

				            				<span class="badge badge-danger font-weight-normal animated wobble infinite" style="position: absolute; right: 0px; top: -5px; z-index: 2;" title="Tienes este trabajo especial por entregar, si ya lo llevaste, sugiere al profesor o responsable que te califique para eliminar la notificación">1</span>

				            		<?php
				            			} else {
				            				// echo 'else';
				            		?>
				            				<span class="badge badge-danger font-weight-normal" style="position: absolute; right: 0px; top: -5px; z-index: 2;" title="Tienes este trabajo especial por entregar, si ya lo llevaste, sugiere al profesor o responsable que te califique para eliminar la notificación">1</span>
				            		<?php
				            			}
				            		?>
				                	

				            <?php
				            
				                }
				            
				            ?>
							<h5><?php echo $filaProyectos['nom_pro']; ?></h5>
						</a>
						
						<hr>

						<h6>
							Puntos: <?php echo $filaProyectos['pun_pro']; ?> / 
							<?php  
								if ( $estatus == 'Entregada' ) {
									
									echo $filaProyectos['pun_pro_alu_ram'];

								} else {
									echo 'Sin entregar';
								}
							?>
						</h6>
						
						

						<span class="letraPequena grey-text">
							Del <?php echo fechaFormateadaCompacta2( $filaProyectos['ini_pro'] ).' al '.fechaFormateadaCompacta2( $filaProyectos['fin_pro'] ); ?>
						</span>

					</div>
				
				</div>

				
			</div>

			<?php  
				if ( $i % 3 == 0 ) {
			?>
					</div>

					<br>
					<div class="row p-3">
					

			<?php
				}
			?>


<?php
			$i++;
		}
		// FIN WHILE
?>


<?php
	}
?>

<script>
	$('.obtenerProyecto').off('click');
	$('.obtenerProyecto').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var id_pro_alu_ram = $(this).attr('id_pro_alu_ram');
		var nom_pro = $(this).attr('nom_pro');

		$.ajax({
			url: 'server/obtener_proyecto.php',
			type: 'POST',
			data: { id_pro_alu_ram },
			success: function( respuesta ){

				$('#modal_obtener_proyecto').modal('show');
				$('#contenedor_obtener_proyecto').html( respuesta );
				$('#titulo_obtener_proyecto').html( nom_pro );
			
			}
		
		});
		
		
	});
</script>