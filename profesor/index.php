<?php  

	include('inc/header.php');

?>
<!-- TITULO -->
<div class="row ">
	<div class="col-md-6">
		<span class="tituloPagina badge blue-grey darken-4 hoverable" title="Inicio"><i class="fas fa-bookmark"></i> Inicio</span>
		<br>
		<div class=" badge badge-warning text-white">
			<a href="index.php" title="Estás aquí"><span class="text-white">Inicio</span></a>

		</div>
		
	</div>

	<div class="col-md-6">
		
		<div class="alert alert-info animated fadeIn delay-1s" role="alert">
		  	<h6 class="alert-heading">Bienvenido, prof. <?php echo $nombreUsuario; ?>.</h6>
		  	<hr>
		  	<p class="mb-0">

			  	<?php
			  		// echo file_exists( '../img/usuario.jpg' );
			  		
			  		if ( ( $foto == NULL ) || ( file_exists( '../uploads/'.$foto ) != 1 )  ) {
			  	?>
				  		Recomendamos que coloques una foto tuya para mejorar tu experiencia en la plataforma haciendo click <a href="perfil.php" class="text-primary btn-link">aquí</a>.
			  	<?php	
			  		} else {
			  	?>
			  			Te recordamos que puedes cambiar algunos datos de tu cuenta haciendo click <a href="perfil.php" class="text-primary btn-link">aquí</a>, o yendo al apartado de "Ajustes".
			  	<?php
			  		}
			  	?>

		  	</p>
		</div>
	</div>
		
</div>
<!-- FIN TITULO -->


<style>
	.claseSticky{

		position: -webkit-sticky;
		position: sticky;
		top: 50px;

	}

	.claseHijoDerecha {
		position: absolute;
		right: -40px;
		top: -20px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.clasePadre {
		position: relative;
	}


	.claseHijoClaseBoton {
	  position: absolute;
	  right: 0px;
	  top: 0px;
	  z-index: 2;
	}


	.claseHijoClaseLi {
	  position: absolute;
	  left: 0px;
	  top: 0px;
	}


	
</style>
         
<div class="row">

	<div class="col-md-12">
		
		
		<!-- CLASES -->

			<?php
				$sqlGrupos = "
					SELECT *
					FROM sub_hor
					INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
					INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
					INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
					INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
					WHERE id_pro = '$id' AND est_sub_hor = 'Activo' AND id_fus2 IS NULL
					UNION
					SELECT *
					FROM sub_hor
					INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
					INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
					INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
					INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
					WHERE id_pro = '$id' AND est_sub_hor = 'Activo' AND id_fus2 IS NOT NULL AND id_sub_hor_nat IS NULL
					ORDER BY nom_mat

				";

				// echo $sqlGrupos;
				$fechaHoy = date( 'Y-m-d' );

				$resultadoGrupos = mysqli_query( $db, $sqlGrupos );
				
				$contador = 1;
			?>
			
			<div class="row">
			
			<?php
				while( $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos ) ){

					$id_sub_hor = $filaGrupos['id_sub_hor'];

					$ini_cic = $filaGrupos['ini_cic'];
					$fin_cic = $filaGrupos['fin_cic'];
			
			?>


			<?php
	        	$diferenciaDias = obtenerDiferenciaFechas( $fechaHoy, $ini_cic );

				// echo floor( $diferenciaDias / 7 );

				$dias = obtenerDiferenciaFechas( $fin_cic, $ini_cic );

					if ( $dias == 0 ) {

						$estatusGeneracion = 'Finalizado';
						$porcentajeAvance = 100;

					} else {
						
						$estatusGeneracion = '';
						
						if ( $dias > 0 ) {
							// ACTIVO
							$estatusGeneracion = 'Activo';
							$porcentajeAvance = floor( ( ( $diferenciaDias * 100 ) / $dias ) );
							
							if ( $porcentajeAvance < 0 ) {
								$estatusGeneracion = 'Pendiente';
								// PENDIENTE 
								$porcentajeAvance = 0;
							} else if ( $porcentajeAvance > 100 ) {
								$estatusGeneracion = 'Finalizado';
								// FINALIZADO
								$porcentajeAvance = 100;
							}
						} else {
							// PENDIENTE
							$estatusGeneracion = 'Pendiente';
							$porcentajeAvance = 0;
						
						}
					}
				
				

	        ?>

				<div class="col-md-4">
					
					<div class="card" style="border-radius: 10px;">
						
						<div class="card-header border bg-info" style="border-radius: 10px;">
							
							

							<div class="row">
								<div class="col-md-9">
									<a class="white-text letraGrande font-weight-normal btn-link" href="clases_materia.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Crea, edita, elimina y revisa actividades para el grupo de <?php echo $filaGrupos['nom_mat']; ?>">
										<?php  
											echo $contador." - ".comprimirTexto( $filaGrupos['nom_mat'] );
										?>
									</a>
									<br>
									<span class="white-text letraMediana ">
										<?php  
											echo comprimirTexto( $filaGrupos['nom_gru'] );
										?>
									</span>
								</div>

								<div class="col-md-3">
									
									<!--Dropdown primary-->
									<div class="dropdown clasePadre">

										<?php  
	                                        if ( obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ) > 0 ) {
	                                      ?>
	                                          <span class="badge badge-danger claseHijoClaseBoton rounded" id_sub_hor="<?php echo $id_sub_hor; ?>" title="Tienes <?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?></span>

	                                      <?php
	                                        }
	                                      ?>

									  <!--Trigger-->

										<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
											<i class="fas fa-ellipsis-v grey-text"></i>
						    			</a>


									  <!--Menu-->
										<div class="dropdown-menu dropdown-info">
											
									

	                                        <a class="dropdown-item waves-effect clasePadre" href="clases_materia.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Crea, edita, elimina y revisa actividades para el grupo de <?php echo $filaGrupos['nom_mat']; ?>">
	                                          <i class="fas fa-chalkboard-teacher"></i>
	                                          Clases

	                                          <?php  
		                                        if ( obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ) > 0 ) {
		                                      ?>
		                                          <span class="badge badge-danger claseHijoClaseLi rounded" id_sub_hor="<?php echo $id_sub_hor; ?>" title="Tienes <?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?></span>

		                                      <?php
		                                        }
		                                      ?>

	                                          
	                                        </a>

	                                        

	                                        
	                                    


	                                    
	                                        <a class="dropdown-item waves-effect" href="alumnos_materia.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Consulta, imprime y descarga el listado de alumnos del grupo de <?php echo $filaGrupos['nom_mat']; ?>">
	                                          <i class="fas fa-user-graduate"></i>
	                                          
	                                          
	                                          Listado de alumnos
	                                        </a>
	                                    


	                                    
	                                        <a class="dropdown-item waves-effect" href="actividades_materia.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Califica el listado de alumnos del grupo de <?php echo $filaGrupos['nom_mat']; ?>">
	                                          <i class="fas fa-clipboard-list"></i>
	                                          Calificar actividades
	                                        </a>
	                                    


	                                    
	                                        <a class="dropdown-item waves-effect" href="video_clase.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Conecta a tus alumnos del grupo de <?php echo $filaGrupos['nom_mat']; ?> a través de una video-clase">
	                                          <i class="fas fa-video"></i>
	                                          Video-clase
	                                        </a>
	                                    

	                                    
	                                        <a class="dropdown-item waves-effect" href="mensajes2.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Envía y recibe mensajes en tiempo real con tu grupo de <?php echo $filaGrupos['nom_mat']; ?>">
	                                          <i class="fas fa-comments"></i>
	                                          Mensajería grupal
	                                        </a>


	                                        <?php  
	                                        	if ( $estatusUsuario == 'Activo' ) {
	                                        ?>
	                                        		<a class="dropdown-item waves-effect" href="reportes_actividades_grupo.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Genera un reporte de grupo de la asignatura de <?php echo $filaGrupos['nom_mat']; ?>">
	                                        			<i class="fas fa-file-alt"></i>
			                                          	Reportes grupales
			                                        </a>

	                                        <?php
	                                        	}
	                                        ?>
		                                    
											
											

										</div>


										<?php  
				            
							                if ( obtenerTotalNotificacionesBloqueGrupo( $id, $id_sub_hor, $id_sub_hor ) > 0 ) {
							            
							            ?>
							                	<span class="badge badge-danger claseHijoClaseMateria" title="Tienes <?php echo obtenerTotalNotificacionesBloqueGrupo( $id, $id_sub_hor, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesBloqueGrupo( $id, $id_sub_hor, $id_sub_hor ); ?></span>

							            <?php
							            
							                }
							            
							            ?>

									</div>
									<!--/Dropdown primary-->


								</div>
							</div>
							
							
							
						</div>

				      	<div class="card-body text-left clasePadre">
					        
							<?php
								if ( ( obtenerValidacionAlumnosCalificadosGrupo( $id_sub_hor ) == 'verdadero' ) && ( $porcentajeAvance > 85 ) ) {
							?>
								
									<p class="note note-danger letraMediana claseHijoDerecha" style="height: 100px; width: 200px;">
										<button type="button" class="close" aria-label="Close" title="Cerrar">
										  <span aria-hidden="true">×</span>
										</button>

										<strong>¡Aviso urgente!</strong>
										<br>
										Aún tienes alumnos que NO tienen calificación final y el ciclo está pronto a terminar, haz click <a href="alumnos_materia.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" class="btn-link text-primary">aquí</a> para evaluarlos.


									</p>
									

							<?php
								}
							?>

					        <div class="row">
					        	<div class="col-md-6 ">
					        		<a class="grey-text font-weight-normal letraGrande btn-link" href="alumnos_materia.php?id_sub_hor=<?php echo $filaGrupos['id_sub_hor']; ?>" title="Consulta, imprime y descarga el listado de alumnos del grupo de <?php echo $filaGrupos['nom_mat']; ?>">
										<i class="fas fa-user-graduate fa-lg"></i>
										

										<?php
											

											$totalAlumnos = obtenerTotalAlumnosGrupo( $id_sub_hor );
											echo $totalAlumnos;
										?> alumnos
									
									</a>

									<br>

									

									
					        	</div>

					        	<div class="col-md-6">

					        		
					        		

					        		<div class="clasePadre">

					        			
		
										<div class="progress md-progress" style="height: 20px">
										    

										    <?php  
										    	if ( $porcentajeAvance > 70 && $porcentajeAvance < 85 ) {
										    ?>
													<div class="progress-bar text-center  white-text bg-warning" role="progressbar" style="height: 20px; width: <?php echo $porcentajeAvance; ?>%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" title="El ciclo en amarillo significa que está por terminarse">
												    	<?php echo $porcentajeAvance; ?>%
												    </div>
										    <?php
										    	} else if ( $porcentajeAvance >= 85 ) {
										    ?>
													<div class="progress-bar text-center  white-text bg-danger" role="progressbar" style="height: 20px; width: <?php echo $porcentajeAvance; ?>%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" title="El ciclo en rojo significa que está casi concluido">
												    	<?php echo $porcentajeAvance; ?>%
												    </div>

										    <?php
										    	} else {
										    ?>
										    
													<div class="progress-bar text-center  white-text bg-info" role="progressbar" style="height: 20px; width: <?php echo $porcentajeAvance; ?>%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" title="Ciclo debajo del 70%">
												    	<?php echo $porcentajeAvance; ?>%
												    </div>
										    <?php
										    	}
										    ?>
										    
											
											
										</div>

										
									
									</div>


					        		
					        	</div>
					        </div>

					        <div class="row">
								
								<div class="col-md-6">
									<span class="letraMediana font-weight-normal grey-text">
										Semana <?php echo floor( $diferenciaDias / 7 ); ?> 
									</span>
									
								</div>

								<div class="col-md-6 text-right">
									<span class="letraPequena font-weight-normal grey-text">
										<?php echo fechaFormateadaCompacta2($ini_cic); ?> al
										<?php echo fechaFormateadaCompacta2($fin_cic); ?>
									</span>
									
								</div>
							</div>
					        

				      	</div>

				      	<div class="card-footer text-center" style="position: relative;">
							
							<?php  
								if ( $filaGrupos['id_fus2'] != null ) {
							?>
								<div style="position: absolute; bottom: 0px; left: 5px;">
									<i class="fas fa-gem text-primary"></i>
									<span class="letraPequena grey-text">
										Grupo Fusionado
									</span>
								</div>
									
									

							<?php
								}
							?>

							<small>	

								<?php  
									echo $filaGrupos['nom_ram'];
								?>
							</small>
							
							
						</div>

				    </div>

					
				</div>

			<?php  
				if ( $contador % 3 == 0 ) {
			?>

				</div>

				<hr>
				<div class="row">
			<?php
				}
			?>


		<?php
			$contador++;
			}

		?>

		<!-- FIN CLASES -->

	</div>


	


	

</div>






<?php  

	include('inc/footer.php');

?>



<script>
	$( '.close' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( this ).parent().remove();
	});
</script>