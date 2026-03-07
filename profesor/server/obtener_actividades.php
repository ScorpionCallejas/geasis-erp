<?php  
	//EXAMEN VIA AJAX PARA OBTENER RECURSOS TEORICOS
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_blo = $_POST['id_blo'];
	$id_sub_hor = $_POST['id_sub_hor'];


	$totalRecursos = contadorRecursosPracticosServer( $id_blo, $id_sub_hor );
	// $totalRecursos = 0;
	if ( $totalRecursos > 0 ) {
		// HAY RECURSOS
?>
		
		<?php  
	        $sql = "
	        	SELECT id_for AS identificador, id_for_cop AS identificador_copia, nom_for AS titulo, des_for AS descripcion, fec_for AS fecha, tip_for AS tipo, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin
	        	FROM foro_copia
	        	INNER JOIN foro ON foro.id_for = foro_copia.id_for1
	        	WHERE id_sub_hor2 = '$id_sub_hor' AND id_blo4 = '$id_blo'
				UNION
				SELECT id_ent AS identificador, id_ent_cop AS identificador_copia, nom_ent AS titulo, des_ent AS descripcion, fec_ent AS fecha, tip_ent AS tipo, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin
				FROM entregable_copia
				INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
				WHERE id_sub_hor3 = '$id_sub_hor' AND id_blo5 = '$id_blo'
				UNION
				SELECT id_exa AS identificador, id_exa_cop AS identificador_copia, nom_exa AS titulo, des_exa AS descripcion, fec_exa AS fecha, tip_exa AS tipo, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin
				FROM examen_copia
				INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
				WHERE id_sub_hor4 = '$id_sub_hor' AND id_blo6 = '$id_blo'
	        	ORDER BY fecha DESC

	        ";


	        // echo $sql;
	        $resultado = mysqli_query( $db, $sql );
	        $i = 1;
	        while( $fila = mysqli_fetch_assoc( $resultado ) ){

	        	$identificador = $fila['identificador_copia'];
	    		$tipo = $fila['tipo'];

	    		$datos = obtenerPorcentajeParticipacionActividadServer( $tipo, $identificador );

	    		$zindex = 100;
	    ?>


	    	<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
			
				

			    <div class="body" >

			    	<div class="card-header bg-white"  style="border-radius: 20px 20px 0px 0px;">

				    	<div class="row">
							
							

				    		
							<?php  
			    				if ( $fila['tipo'] == 'Foro' ) {
			    					// $filaForo = obtenerDatosActividadServer( $fila['tipo'], $fila['identificador'] );
			    			?>
			    					<div class="col-md-10 ">

						    				<div class="row">
						    					<div class="col-md-2 text-left" >

						    						<i class="fas fa-comment-dots fa-2x grey-text"></i>
						    						<br>
						    						<p class="letraPequena grey-text">
											      		Foro
											      	</p>


						    						
						    					</div>

						    					<div class="col-md-4 text-left">
						    					
													

						    						<h6 class="font-weight-normal grey-text">
						    							<?php echo substr( $fila["titulo"], 0, 50 ); ?>
						    							
						    						</h6>
						    						
						    					</div>

						    					<div class="col-md-4 text-left">
						    						
						    						
						    						<h4 class="text-success font-weight-bold">
						    								+<?php echo $fila['puntaje']; ?> puntos
						    							
						    						</h4>

						    						
						    					</div>

						    					<div class="col-md-2">
						    						<span class="badge badge-pill badge-info">
														<?php echo $datos['alumnos_totales']; ?> alumnos
						    						</span>

						    						<br>

						    						<span class="badge badge-pill badge-info">
						    							<?php echo $datos['alumnos_responsables']; ?> cumplidos
						    						</span>

						    						<br>

						    						<span class="badge badge-pill badge-info">
						    							<?php echo $datos['alumnos_porcentaje']; ?> de participación
						    						</span>

						    					</div>

						    					<!-- <div class="col-md-2">
						    						
						    					</div> -->
						    				</div>
								          
								        <!-- </a> -->
						
						    		</div>


						    		<div class="col-md-2 text-right">

						    			<!--Dropdown primary-->
										<div class="dropdown clasePadreActividad">
										

											<?php
		            
								                if ( obtenerTotalNotificacionesActividadServer( $identificador, $id ) > 0 ) {
								            
								            ?>
								                	<span class="badge badge-danger claseHijoActividad rounded-circle" title="Tienes <?php echo obtenerTotalNotificacionesActividadServer( $identificador, $id ); ?> actividades pendientes por revisar">
								                		<?php 
								                		
								                			echo obtenerTotalNotificacionesActividadServer( $identificador, $id ); 
								                		?>
								                		
								                	</span>

								            <?php
								            
								                }
								            
								            ?>
										  <!--Trigger-->

											<a class="btn-floating white btn-sm waves-effect dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;" >
												<i class="fas fa-ellipsis-v grey-text"></i>
											

												
												
							    			</a>


										  <!--Menu-->
											<div class="dropdown-menu dropdown-info">
												
												<a class="dropdown-item waves-effect revisarActividadForo" id_for_cop="<?php echo $fila['identificador_copia']; ?>" nom_for="<?php echo $fila['titulo']; ?>" href="#">
													Revisar
												</a>

												
												<a class="dropdown-item waves-effect edicionForo" href="#" id_for_cop="<?php echo $fila['identificador_copia']; ?>">
													Editar
												</a>


												<a class="dropdown-item waves-effect copiarForo" href="#" id_for="<?php echo $fila['identificador']; ?>" titulo="<?php echo $fila['titulo']; ?>" inicio_copia="<?php echo $fila['inicio']; ?>" fin_copia="<?php echo $fila['fin']; ?>" title="Puedes copiar y pegar esta actividad en otra clase de otra o la misma materia">
													Copiar actividad
												</a>


												<a class="dropdown-item waves-effect  eliminacionForo" eliminacionForo="<?php echo $fila['identificador_copia']; ?>" foro="<?php echo $fila['titulo']; ?> " href="#">
													Eliminar
												</a>


												<a class="dropdown-item waves-effect  eliminacionForoRaiz" eliminacionForo="<?php echo $fila['identificador']; ?>" foro="<?php echo $fila['titulo']; ?> " href="#">
													Eliminar de raíz
												</a>

											</div>
										</div>
										<!--/Dropdown primary-->
										
										

						    		</div>

			    			<?php	
			    				} else if ( $fila['tipo'] == 'Entregable' ) {

			    					// $filaEntregable = obtenerDatosActividadServer( $fila['tipo'], $fila['identificador'] );
			    			?>
			    					<div class="col-md-10 ">

						    				<div class="row">
						    					<div class="col-md-2 text-left" >

						    						<i class="fas fa-file-alt fa-2x grey-text"></i>
						    						<br>
						    						<p class="letraPequena grey-text">
											      		Entregable
											      	</p>

						    						
						    					</div>

						    					<div class="col-md-4 text-left">
						    					
													

						    						<h6 class="font-weight-normal grey-text">
						    							<?php echo substr( $fila["titulo"], 0, 50 ); ?>
						    							
						    						</h6>
						    						
						    					</div>

						    					<div class="col-md-4 text-left">
						    						
						    						
						    						<h4 class="text-success font-weight-bold">
						    								+<?php echo $fila['puntaje']; ?> puntos
						    							
						    						</h4>

						    						
						    					</div>

						    					<div class="col-md-2">
						    						<span class="badge badge-pill badge-info">
														<?php echo $datos['alumnos_totales']; ?> alumnos
						    						</span>

						    						<br>

						    						<span class="badge badge-pill badge-info">
						    							<?php echo $datos['alumnos_responsables']; ?> cumplidos
						    						</span>

						    						<br>

						    						<span class="badge badge-pill badge-info">
						    							<?php echo $datos['alumnos_porcentaje']; ?> de participación
						    						</span>

						    					</div>
						    				</div>
								          
								        <!-- </a> -->
						
						    		</div>


						    		<div class="col-md-2 text-right">

						    			<!--Dropdown primary-->
										<div class="dropdown clasePadreActividad">
										

											<?php
		            
								                if ( obtenerTotalNotificacionesActividadServer( $identificador, $id ) > 0 ) {
								            
								            ?>
								                	<span class="badge badge-danger claseHijoActividad rounded-circle" title="Tienes <?php echo obtenerTotalNotificacionesActividadServer( $identificador, $id ); ?> actividades pendientes por revisar">
								                		<?php 
								                		
								                			echo obtenerTotalNotificacionesActividadServer( $identificador, $id ); 
								                		?>
								                		
								                	</span>

								            <?php
								            
								                }
								            
								            ?>

										  <!--Trigger-->

											<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
												<i class="fas fa-ellipsis-v grey-text"></i>
							    			</a>


										  <!--Menu-->
											<div class="dropdown-menu dropdown-info">
												
												<a class="dropdown-item waves-effect revisarActividadEntregable" id_ent_cop="<?php echo $fila['identificador_copia']; ?>" nom_ent="<?php echo $fila['titulo']; ?>" href="#">
													Revisar
												</a>
												
												<a class="dropdown-item waves-effect edicionEntregable" href="#" id_ent_cop="<?php echo $fila['identificador_copia']; ?>">
													Editar
												</a>


												<a class="dropdown-item waves-effect copiarEntregable" href="#" id_ent="<?php echo $fila['identificador']; ?>" titulo="<?php echo $fila['titulo']; ?>" inicio_copia="<?php echo $fila['inicio']; ?>" fin_copia="<?php echo $fila['fin']; ?>" title="Puedes copiar y pegar esta actividad en otra clase de otra o la misma materia">
													Copiar actividad
												</a>


												<a class="dropdown-item waves-effect  eliminacionEntregable" eliminacionEntregable="<?php echo $fila['identificador_copia']; ?>" entregable="<?php echo $fila['titulo']; ?> " href="#">
													Eliminar
												</a>



												<a class="dropdown-item waves-effect  eliminacionEntregableRaiz" eliminacionEntregable="<?php echo $fila['identificador']; ?>" entregable="<?php echo $fila['titulo']; ?> " href="#">
													Eliminar de raíz
												</a>

											</div>
										</div>
										<!--/Dropdown primary-->
										
										

						    		</div>
							<?php
			    				} else if ( $fila['tipo'] == 'Examen' ) {
			    					$filaExamen = obtenerDatosActividadServer( $fila['tipo'], $fila['identificador'] );
			    					
			    			?>

		    						<div class="col-md-10 ">

						    				<div class="row">
						    					<div class="col-md-2 text-left" >

						    						<i class="fas fa-diagnoses fa-2x grey-text"></i>
						    						<br>
						    						<p class="letraPequena grey-text">
											      		Cuestionario
											      	</p>

						    						
						    					</div>

						    					<div class="col-md-4 text-left">
						    					
													

						    						<h6 class="font-weight-normal grey-text">
						    							<?php echo substr( $fila["titulo"], 0, 50 ); ?>
						    							
						    						</h6>
						    						
						    					</div>

						    					<div class="col-md-4 text-left">
						    						
						    						
						    						<h4 class="text-success font-weight-bold">

						    								+<?php echo obtenerPuntajeExamenServer($fila['identificador']); ?> puntos
						    							
						    						</h4>

						    						
						    					</div>

						    					<div class="col-md-2">
						    						<span class="badge badge-pill badge-info">
														<?php echo $datos['alumnos_totales']; ?> alumnos
						    						</span>

						    						<br>

						    						<span class="badge badge-pill badge-info">
						    							<?php echo $datos['alumnos_responsables']; ?> cumplidos
						    						</span>

						    						<br>

						    						<span class="badge badge-pill badge-info">
						    							<?php echo $datos['alumnos_porcentaje']; ?> de participación
						    						</span>

						    					</div>
						    					
						    				</div>
								          
								        <!-- </a> -->
						
						    		</div>


						    		<div class="col-md-2 text-right">

						    			<!--Dropdown primary-->
										<div class="dropdown clasePadreActividad">
										

											<?php
		            
								                if ( obtenerTotalNotificacionesActividadServer( $identificador, $id ) > 0 ) {
								            
								            ?>
								                	<span class="badge badge-danger claseHijoActividad rounded-circle" title="Tienes <?php echo obtenerTotalNotificacionesActividadServer( $identificador, $id ); ?> actividades pendientes por revisar">
								                		<?php 
								                		
								                			echo obtenerTotalNotificacionesActividadServer( $identificador, $id ); 
								                		?>
								                		
								                	</span>

								            <?php
								            
								                }
								            
								            ?>

										  <!--Trigger-->

											<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
												<i class="fas fa-ellipsis-v grey-text"></i>
							    			</a>


										  <!--Menu-->
											<div class="dropdown-menu dropdown-info">
												
												<a class="dropdown-item waves-effect revisarActividadExamen" id_exa_cop="<?php echo $fila['identificador_copia']; ?>" nom_exa="<?php echo $fila['titulo']; ?>" href="#">
													Revisar
												</a>
												
												<a class="dropdown-item waves-effect edicionExamen" href="#" id_exa_cop="<?php echo $fila['identificador_copia']; ?>">
													Editar
												</a>


												<a class="dropdown-item waves-effect copiarExamen" href="#" id_exa="<?php echo $fila['identificador']; ?>" titulo="<?php echo $fila['titulo']; ?>" inicio_copia="<?php echo $fila['inicio']; ?>" fin_copia="<?php echo $fila['fin']; ?>" title="Puedes copiar y pegar esta actividad en otra clase de otra o la misma materia">
													Copiar actividad
												</a>


												<a class="dropdown-item waves-effect  eliminacionExamen" eliminacionExamen="<?php echo $fila['identificador_copia']; ?>" examen="<?php echo $fila['titulo']; ?> " href="#">
													Eliminar
												</a>

												<a class="dropdown-item waves-effect  eliminacionExamenRaiz" eliminacionExamen="<?php echo $fila['identificador']; ?>" examen="<?php echo $fila['titulo']; ?> " href="#">
													Eliminar de raíz
												</a>

											</div>
										</div>
										<!--/Dropdown primary-->
										
										

						    		</div>
						    				


			    			<?php	
			    				}
			    			?>
				    		

				    		
				    	</div>



				    </div>
			    	<div class="card-footer text-center">
			    	
				    	<label class="letraMediana grey-text font-weight-bold">
							<?php  
								echo "Del ".mb_strtolower( obtenerFechaGuapa( $fila['inicio'] ) )." al ".mb_strtolower( obtenerFechaGuapa( $fila['fin'] ) );
							?>
						</label>

				    </div>
			    </div>

			    



			
			</div>
	    	
			
			<hr>
	        

        <?php  
	    	}
	    ?>


	    

<?php
	} else {
?>

		<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
		
			<div class="card-header bg-white"  style="border-radius: 20px;">

				<div class="row animated fadeIn">

					<div class="col-md-12 text-center">
						
						<h4>
							<span class="badge badge-warning">
								¡No hay actividades!
							</span>
						</h4>
						
						<img src="../img/sentado.gif" width="15%" class="animated tada delay-3s">
						
						
						<br>
						<br>


						<h5>
							<span class="badge badge-warning">
								¡Agrega una!
							</span>
						</h5>

						<br>
						<br>

				
					</div>
				</div>
			</div>
		</div>


		<script>
			// setTimeout(function(){
			// 	introJs().start();
			// }, 1000);
		</script>
		

<?php
	}
?>

<!-- EDITAR FORO -->
<!-- CONTENIDO MODAL AGREGAR FORO -->
<div class="modal fade text-left " id="editarForoModal">
  <div class="modal-dialog modal-lg" role="document">
    
  	<form >
      <div class="modal-content">
        <div class="modal-header text-center grey darken-1 white-text">
          
          	<h4 class="modal-title w-100 white-text">
	        	Editar foro
	        </h4>

          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            	<span aria-hidden="true">&times;</span>
          	</button>
        </div>
        
        <div class="modal-body mx-3">

        

			<p class="letraPequena grey-text">
				NOTA: Todos los campos con * son obligatorios
				<br>
				* Asigna un título
			</p>
	      		

	        <div class="md-form mb-5">

	            <i class="fas fa-info prefix grey-text"></i>
	            <input type="text" id="tituloForo_edicion" class="form-control validate">
	        	
	        	<input type="hidden" id="id_for_edicion">
	        </div>


          	<div class="row">
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna un puntaje
			      	</p>

					<div class="md-form mb-2">
						<i class="fas fa-award prefix grey-text"></i>
						<input type="number" id="pun_for_edicion" min="0" step=".1" class="form-control validate" value="">
					</div>

				</div>

				<div class="col-md-4">

					<p class="letraPequena grey-text">
			      		* Asigna una fecha de inicio
			      	</p>



					<div class="md-form mb-2">

						<i class="fas fa-minus-circle prefix grey-text"></i>
						<input type="date" id="ini_for_edicion" min="0" step="1" class="form-control validate" value="">
						
					</div>
			    
			  	</div>
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna una fecha de vencimiento
			      	</p>
			        
			        <div class="md-form mb-2">
				        <i class="fas fa-plus-circle prefix grey-text"></i> 
				        <input type="date" id="fin_for_edicion"  class="form-control validate" value="">
			        </div>
			    
			  	</div>
			
			</div>


			<div class="row">
				<div class="col-md-12">

				<br>
					<div id="boxForo_edicion">
						<p class="letraPequena grey-text">
				      		* Asigna las intrucciones
				      	</p>

						<div id="des_for_edicion">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							      
		        		</div>
						
					</div>
				
				</div>

			</div>





        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar archivo" id="editarForoFormulario">
	        	Guardar
	        </button>
	      	
	      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
	            Cancelar
	        </a>

        
        </div>

      </div>
  </form>

  </div>
</div>
<!-- FIN EDITAR FORO -->


<!-- EDITAR ENTREGABLE -->
<!-- CONTENIDO MODAL AGREGAR ENTREGABLE -->
<div class="modal fade text-left " id="editarEntregableModal">
  <div class="modal-dialog modal-lg" role="document">
    
  	<form >
      <div class="modal-content">
        <div class="modal-header text-center grey darken-1 white-text">
          
          	<h4 class="modal-title w-100 white-text">
	        	Editar tarea
	        </h4>

          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            	<span aria-hidden="true">&times;</span>
          	</button>
        </div>
        
        <div class="modal-body mx-3">

        

			<p class="letraPequena grey-text">
				NOTA: Todos los campos con * son obligatorios
				<br>
				* Asigna un título
			</p>
	      		

	        <div class="md-form mb-5">

	            <i class="fas fa-info prefix grey-text"></i>
	            <input type="text" id="tituloEntregable_edicion" class="form-control validate">
	        	
	        	<input type="hidden" id="id_ent_edicion">
	        </div>


          	<div class="row">
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna un puntaje
			      	</p>

					<div class="md-form mb-2">
						<i class="fas fa-award prefix grey-text"></i>
						<input type="number" id="pun_ent_edicion" min="0" step=".1" class="form-control validate" value="">
					</div>

				</div>

				<div class="col-md-4">

					<p class="letraPequena grey-text">
			      		* Asigna una fecha de inicio
			      	</p>



					<div class="md-form mb-2">

						<i class="fas fa-minus-circle prefix grey-text"></i>
						<input type="date" id="ini_ent_edicion" min="0" step="1" class="form-control validate" value="">
						
					</div>
			    
			  	</div>
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna una fecha de vencimiento
			      	</p>
			        
			        <div class="md-form mb-2">
				        <i class="fas fa-plus-circle prefix grey-text"></i> 
				        <input type="date" id="fin_ent_edicion"  class="form-control validate" value="">
			        </div>
			    
			  	</div>
			
			</div>


			<div class="row">
				<div class="col-md-12">

				<br>
					<div id="boxEntregable_edicion">
						<p class="letraPequena grey-text">
				      		* Asigna las intrucciones
				      	</p>

						<div id="des_ent_edicion">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							      
		        		</div>
						
					</div>
				
				</div>

			</div>





        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar tarea" id="editarEntregableFormulario">
	        	Guardar
	        </button>
	      	
	      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
	            Cancelar
	        </a>

        
        </div>

      </div>
  </form>

  </div>
</div>
<!-- FIN EDITAR ENTREGABLE -->


<!-- MODAL OBTENER ACTIVIDAD -->
<div class="modal fade text-left " id="modal_obtener_actividad">
  <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
        <div class="modal-header text-center grey darken-1 white-text">
          
          	<h4 class="modal-title w-100 white-text" id="titulo_modal_obtener_actividad">
	        	Editar foro
	        </h4>

          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            	<span aria-hidden="true">&times;</span>
          	</button>
        </div>
        
        <div class="modal-body mx-3" id="contenedor_modal_obtener_actividad">

        

			




        </div>

        <div class="modal-footer d-flex justify-content-center">
	      	
	      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
	            Cancelar
	        </a>

        
        </div>

      </div>

  </div>
</div>
<!-- FIN MODAL OBTENER ACTIVIDAD -->

<script>
	$( '.edicionExamen' ).on( 'click', function( event ){
		event.preventDefault();
		
		console.log("hi");
		var id_exa_cop = $( this ).attr( "id_exa_cop" );


		// console.log( id_exa_cop );

		$.ajax({
			url: 'server/obtener_examen.php',
			type: 'POST',
			dataType: 'json',
			data: { id_exa_cop },
			success: function( datos ){



				console.log( datos );
				$('#agregarExamenModal').modal('show');
		        // 
					setTimeout( function(){
						$( '#tituloExamen' ).focus();
					}, 700 );
					

					$("#agregarExamenFormulario").removeAttr( 'disabled' );
					$("#agregarPregunta").removeAttr( 'disabled' );
					$( '#agregarExamenModal' ).removeAttr( 'estatus_examen' ).attr( 'estatus_examen', 'verdadero' ).attr('id_exa', id_exa_cop );

					$( '#tituloExamen' ).val( datos.titulo );
					des_exa.value = datos.descripcion;

					$( '#pun_exa' ).val( datos.puntaje );
					$( '#dur_exa' ).val( datos.duracion );
					$( '#ini_exa' ).val( datos.inicio );
					$( '#fin_exa' ).val( datos.fin );

					pregunta.value = 'Tu pregunta va aquí...';

					obtener_preguntas_examen( id_exa_cop );
		        // 


				

			}
		});
	});
</script>



<!--  -->
<script>




	// EDICION FORO

	var des_for_edicion = new Jodit("#des_for_edicion", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });
	
	var des_ent_edicion = new Jodit("#des_ent_edicion", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });


	$( '.edicionForo' ).on( 'click', function( event ){
		event.preventDefault();
		
		var id_for_cop = $( this ).attr( "id_for_cop" );
		


		$.ajax({
			url: 'server/obtener_foro.php',
			type: 'POST',
			dataType: 'json',
			data: { id_for_cop },
			success: function( datos ){


				// console.log( datos.titulo );
				$('#editarForoModal').modal('show');

				setTimeout( function(){
		            $( '#tituloForo_edicion' ).focus();
		        }, 1000 );


				des_for_edicion.value = datos.descripcion;
				$('#tituloForo_edicion').attr({value: datos.titulo});
				$('#pun_for_edicion').attr({value: datos.puntaje});
				$('#ini_for_edicion').attr({value: datos.inicio});
				$('#fin_for_edicion').attr({value: datos.fin});

				$('#id_for_edicion').attr({value: id_for_cop});

			}
		});
	});


	//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL ENTREGABLE
	$('#editarForoFormulario').on('click', function(event) {
		event.preventDefault();

		var nom_for = $("#tituloForo_edicion").val();
	    var pun_for = $( '#pun_for_edicion' ).val();
	    var des_for = des_for_edicion.value;

	    var ini_for_cop = $( '#ini_for_edicion' ).val();
	    var fin_for_cop = $( '#fin_for_edicion' ).val();

	    var id_for_cop = $( '#id_for_edicion' ).val();


		
		$.ajax({
		
			url: 'server/editar_foro.php',
			type: 'POST',
			data: { nom_for, pun_for, des_for, ini_for_cop, fin_for_cop, id_for_cop },
			success: function( respuesta ){
				console.log( respuesta );

				if ( respuesta == 'Exito' ) {
					swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {

						obtenerActividades();
						$(".modal-backdrop").removeClass('modal-backdrop');
						$( '#editarForoModal' ).modal( 'hide' );
						generarAlerta( 'Cambios guardados' );

					});
					
				}
			}
		});

	});
	// FIN EDICION FORO





	// ELIMINACION FORO
	//ELIMINACION DE FORO
	$('.eliminacionForo').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_for_cop = $(this).attr("eliminacionForo");
		var nombreForo = $(this).attr("foro");

		// console.log(FORO);

		swal({
		  title: "¿Deseas eliminar "+nombreForo+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
		  icon: "warning",
		  buttons: 	{
					  cancel: {
					    text: "Cancelar",
					    value: null,
					    visible: true,
					    className: "",
					    closeModal: true,
					  },
					  confirm: {
					    text: "Confirmar",
					    value: true,
					    visible: true,
					    className: "",
					    closeModal: true
					  }
					},
		  dangerMode: true,
		}).then((willDelete) => {
		  if (willDelete) {
		    //ELIMINACION ACEPTADA

		    $.ajax({
				url: 'server/eliminacion_foro.php',
				type: 'POST',
				data: { id_for_cop },
				success: function( respuesta ){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							
							obtenerActividades();
							generarAlerta( 'Cambios guardados' );
						
						});
					}else{
						// console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
	// FIN ELIMINACION FORO



	//ELIMINACION DE FORO
	$('.eliminacionForoRaiz').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_for = $(this).attr("eliminacionForo");
		var nombreForo = $(this).attr("foro");

		// console.log(FORO);

		swal({
		  title: "¿Deseas eliminar "+nombreForo+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
		  icon: "warning",
		  buttons: 	{
					  cancel: {
					  	text: "Cancelar",
					    value: null,
					    visible: true,
					    className: "",
					    closeModal: true,
					  },
					  confirm: {
					    text: "Confirmar",
					    value: true,
					    visible: true,
					    className: "",
					    closeModal: true
					  }
					},
		  dangerMode: true,
		}).then((willDelete) => {
		  if (willDelete) {
		    //ELIMINACION ACEPTADA

		    $.ajax({
				url: 'server/eliminacion_foro.php',
				type: 'POST',
				data: { id_for },
				success: function( respuesta ){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							
							obtenerActividades();
							generarAlerta( 'Cambios guardados' );
						
						});
					}else{
						// console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
	// FIN ELIMINACION FORO



	// COPIAR FORO

	$('.copiarForo').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var identificador = $(this).attr('id_for');
		var tipo = 'Foro';
		var tituloActividad = $(this).attr('titulo');
		var inicio_copia = $(this).attr('inicio_copia');
		var fin_copia = $(this).attr('fin_copia');

		var id_sub_hor = '<?php echo $_POST['id_sub_hor']; ?>';

		$.ajax({
			url: 'server/obtener_copiar_actividad.php',
			type: 'POST',
			data: { identificador, tipo, id_sub_hor, inicio_copia, fin_copia },
			success: function( respuesta ){

				// console.log( respuesta );

				$('#modal_copiar_actividad').modal('show');
				$('#contenedor_copiar_actividad').html( respuesta );
				$('#titulo_copiar_actividad').text( ' - '+tituloActividad );

			}
		});
	});
	// FIN COPIAR FORO



	// COPIAR ENTREGABLE

	$('.copiarEntregable').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var identificador = $(this).attr('id_ent');
		var tipo = 'Entregable';
		var tituloActividad = $(this).attr('titulo');
		var inicio_copia = $(this).attr('inicio_copia');
		var fin_copia = $(this).attr('fin_copia');

		var id_sub_hor = '<?php echo $_POST['id_sub_hor']; ?>';

		$.ajax({
			url: 'server/obtener_copiar_actividad.php',
			type: 'POST',
			data: { identificador, tipo, id_sub_hor, inicio_copia, fin_copia },
			success: function( respuesta ){

				// console.log( respuesta );

				$('#modal_copiar_actividad').modal('show');
				$('#contenedor_copiar_actividad').html( respuesta );
				$('#titulo_copiar_actividad').text( ' - '+tituloActividad );

			}
		});
	});
	// FIN COPIAR ENTREGABLE


	// COPIAR EXAMEN

	$('.copiarExamen').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var identificador = $(this).attr('id_exa');
		var tipo = 'Examen';
		var tituloActividad = $(this).attr('titulo');
		var inicio_copia = $(this).attr('inicio_copia');
		var fin_copia = $(this).attr('fin_copia');

		var id_sub_hor = '<?php echo $_POST['id_sub_hor']; ?>';

		$.ajax({
			url: 'server/obtener_copiar_actividad.php',
			type: 'POST',
			data: { identificador, tipo, id_sub_hor, inicio_copia, fin_copia },
			success: function( respuesta ){

				// console.log( respuesta );

				$('#modal_copiar_actividad').modal('show');
				$('#contenedor_copiar_actividad').html( respuesta );
				$('#titulo_copiar_actividad').text( ' - '+tituloActividad );

			}
		});
	});
	// FIN COPIAR EXAMEN
</script>



<script>
	$( '.edicionEntregable' ).on( 'click', function( event ){
		event.preventDefault();
		
		var id_ent_cop = $( this ).attr( "id_ent_cop" );
		
		$.ajax({
			url: 'server/obtener_entregable.php',
			type: 'POST',
			dataType: 'json',
			data: { id_ent_cop },
			success: function( datos ){

				console.log( datos.titulo );
				$('#editarEntregableModal').modal('show');

				setTimeout( function(){
		            $( '#tituloEntregable_edicion' ).focus();
		        }, 1000 );


				des_ent_edicion.value = datos.descripcion;
				$('#tituloEntregable_edicion').attr({value: datos.titulo});
				$('#pun_ent_edicion').attr({value: datos.puntaje});
				$('#ini_ent_edicion').attr({value: datos.inicio});
				$('#fin_ent_edicion').attr({value: datos.fin});
				$('#id_ent_edicion').attr({value: id_ent_cop});
				
			
			}
		});
	});

	//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL ENTREGABLE
	$('#editarEntregableFormulario').on('click', function(event) {
		event.preventDefault();

		var nom_ent = $("#tituloEntregable_edicion").val();
	    var pun_ent = $( '#pun_ent_edicion' ).val();
	    var des_ent = des_ent_edicion.value;

	    var ini_ent_cop = $( '#ini_ent_edicion' ).val();
	    var fin_ent_cop = $( '#fin_ent_edicion' ).val();
	    var id_ent_cop = $( '#id_ent_edicion' ).val();
		
		$.ajax({
		
			url: 'server/editar_entregable.php',
			type: 'POST',
			data: { nom_ent, pun_ent, des_ent, ini_ent_cop, fin_ent_cop, id_ent_cop },
			success: function( respuesta ){
				console.log( respuesta );

				if ( respuesta == 'Exito' ) {
					swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {

						obtenerActividades();
						$(".modal-backdrop").removeClass('modal-backdrop');
						$( '#editarEntregableModal' ).modal( 'hide' );
						generarAlerta( 'Cambios guardados' );

					});
					
				}
			}
		});

	});
	// FIN EDICION ENTREGABLE





	// ELIMINACION ENTREGABLE
	$('.eliminacionEntregable').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_ent_cop = $(this).attr("eliminacionEntregable");
		var nombreEntregable = $(this).attr("entregable");

		// console.log(ENTREGABLE);

		swal({
		  title: "¿Deseas eliminar "+nombreEntregable+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
		  icon: "warning",
		  buttons: 	{
					  cancel: {
					    text: "Cancelar",
					    value: null,
					    visible: true,
					    className: "",
					    closeModal: true,
					  },
					  confirm: {
					    text: "Confirmar",
					    value: true,
					    visible: true,
					    className: "",
					    closeModal: true
					  }
					},
		  dangerMode: true,
		}).then((willDelete) => {
		  if (willDelete) {
		    //ELIMINACION ACEPTADA

		    $.ajax({
				url: 'server/eliminacion_entregable.php',
				type: 'POST',
				data: { id_ent_cop },
				success: function( respuesta ){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							
							obtenerActividades();
							generarAlerta( 'Cambios guardados' );
						
						});
					}else{
						// console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
	// FIN ELIMINACION ENTREGABLE


	// ELIMINACION ENTREGABLE
	$('.eliminacionEntregableRaiz').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_ent = $(this).attr("eliminacionEntregable");
		var nombreEntregable = $(this).attr("entregable");

		// console.log(ENTREGABLE);

		swal({
		  title: "¿Deseas eliminar "+nombreEntregable+" de raíz?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
		  icon: "warning",
		  buttons: 	{
					  cancel: {
					    text: "Cancelar",
					    value: null,
					    visible: true,
					    className: "",
					    closeModal: true,
					  },
					  confirm: {
					    text: "Confirmar",
					    value: true,
					    visible: true,
					    className: "",
					    closeModal: true
					  }
					},
		  dangerMode: true,
		}).then((willDelete) => {
		  if (willDelete) {
		    //ELIMINACION ACEPTADA

		    $.ajax({
				url: 'server/eliminacion_entregable.php',
				type: 'POST',
				data: { id_ent },
				success: function( respuesta ){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							
							obtenerActividades();
							generarAlerta( 'Cambios guardados' );
						
						});
					}else{
						// console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
	// FIN ELIMINACION ENTREGABLE
</script>


<script>
	//EXAMEN
	//ELIMINACION DE EXAMEN
	  $('.eliminacionExamen').on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    var id_exa_cop = $(this).attr("eliminacionExamen");
	    var nombreExamen = $(this).attr("examen");

	    // console.log(EXAMEN);

	    swal({
	      title: "¿Deseas eliminar "+nombreExamen+"?",
	      text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
	      icon: "warning",
	      buttons:  {
	            cancel: {
	              text: "Cancelar",
	              value: null,
	              visible: true,
	              className: "",
	              closeModal: true,
	            },
	            confirm: {
	              text: "Confirmar",
	              value: true,
	              visible: true,
	              className: "",
	              closeModal: true
	            }
	          },
	      dangerMode: true,
	    }).then((willDelete) => {
	      if (willDelete) {
	        //ELIMINACION ACEPTADA

	        $.ajax({
	        url: 'server/eliminacion_examen.php',
	        type: 'POST',
	        data: { id_exa_cop },
	        success: function(respuesta){
	          
	          if (respuesta == "true") {
	            console.log("Exito en consulta");
	            swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
	            then((value) => {
	            	obtenerActividades();
					generarAlerta( 'Cambios guardados' );
	            });
	          }else{
	            // console.log(respuesta);

	          }

	        }
	      });
	        
	      }
	    });
	  });
	  

	  
	// FIN EXAMEN



	//ELIMINACION DE EXAMEN
	  $('.eliminacionExamenRaiz').on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    var id_exa = $(this).attr("eliminacionExamen");
	    var nombreExamen = $(this).attr("examen");

	    // console.log(EXAMEN);

	    swal({
	      title: "¿Deseas eliminar "+nombreExamen+"?",
	      text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
	      icon: "warning",
	      buttons:  {
	            cancel: {
	              text: "Cancelar",
	              value: null,
	              visible: true,
	              className: "",
	              closeModal: true,
	            },
	            confirm: {
	              text: "Confirmar",
	              value: true,
	              visible: true,
	              className: "",
	              closeModal: true
	            }
	          },
	      dangerMode: true,
	    }).then((willDelete) => {
	      if (willDelete) {
	        //ELIMINACION ACEPTADA

	        $.ajax({
	        url: 'server/eliminacion_examen.php',
	        type: 'POST',
	        data: { id_exa },
	        success: function(respuesta){
	          
	          if (respuesta == "true") {
	            console.log("Exito en consulta");
	            swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
	            then((value) => {
	            	obtenerActividades();
					generarAlerta( 'Cambios guardados' );
	            });
	          }else{
	            // console.log(respuesta);

	          }

	        }
	      });
	        
	      }
	    });
	  });
	  

	  
	// FIN EXAMEN
</script>


<script>
	$(".actividadPendiente").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */

	    console.log( 'click' );
	    var id_not_pro = $(this).attr("id_not_pro");

	    var tipo = $(this).attr("tipo");
	    var identificador = $(this).attr("identificador");

	    var id_actividad = $(this).attr("id_actividad");
	    var tipo_actividad = $(this).attr("tipo_actividad");

	    var id_alu_ram = $(this).attr("id_alu_ram");

	    console.log( id_not_pro + tipo + identificador + id_actividad + tipo_actividad + id_alu_ram );

	    $.ajax({
	      url: 'server/editar_estatus_notificacion.php',
	      type: 'POST',
	      data: { id_not_pro },
	      success: function ( respuesta ) {
	        //console.log( respuesta );
	        if ( respuesta == 'Exito' ) {
	          
	          if ( tipo_actividad == 'Examen' ) {

	            window.open("examen.php?id_exa_cop="+id_actividad+"&tipo="+tipo+"&identificador="+identificador+"&id_alu_ram="+id_alu_ram);

	          } else if ( tipo_actividad == 'Entregable' ) {

	            window.open("entregable.php?id_ent_cop="+id_actividad+"&tipo="+tipo+"&identificador="+identificador+"&id_alu_ram="+id_alu_ram);
	          
	          } else if ( tipo_actividad == 'Foro' ) {
	           
	            window.open("foro.php?id_for_cop="+id_actividad+"&tipo="+tipo+"&identificador="+identificador+"&id_alu_ram="+id_alu_ram);
	          
	          }
	        }
	      }
	    });

	});

</script>


<script>
	// REVISION DE ACTIVIDADES

	$( '.revisarActividadForo' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var id_for_cop = $( this ).attr( 'id_for_cop' );
		var nom_for = $( this ).attr( 'nom_for' );

		$.ajax({
	      	url: 'server/obtener_controlador_foro.php',
	      	type: 'POST',
	      	data: { id_for_cop },
	      	success: function ( respuesta ) {
	        	// console.log( respuesta );
	        
	        	$( '#modal_obtener_actividad' ).modal( 'show' );
	        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
	        	$( '#titulo_modal_obtener_actividad' ).html( nom_for );

	      	}

	    });


	});


	$( '.revisarActividadEntregable' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var id_ent_cop = $( this ).attr( 'id_ent_cop' );
		var nom_ent = $( this ).attr( 'nom_ent' );

		$.ajax({
	      	url: 'server/obtener_controlador_entregable.php',
	      	type: 'POST',
	      	data: { id_ent_cop },
	      	success: function ( respuesta ) {
	        	// console.log( respuesta );
	        
	        	$( '#modal_obtener_actividad' ).modal( 'show' );
	        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
	        	$( '#titulo_modal_obtener_actividad' ).html( nom_ent );

	      	}

	    });
	});



	$( '.revisarActividadExamen' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var id_exa_cop = $( this ).attr( 'id_exa_cop' );
		var nom_exa = $( this ).attr( 'nom_exa' );

		$.ajax({
	      	url: 'server/obtener_controlador_examen.php',
	      	type: 'POST',
	      	data: { id_exa_cop },
	      	success: function ( respuesta ) {
	        	// console.log( respuesta );
	        
	        	$( '#modal_obtener_actividad' ).modal( 'show' );
	        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
	        	$( '#titulo_modal_obtener_actividad' ).html( nom_exa );

	      	}

	    });
	});


	// GET

	<?php  
		if ( ( isset( $_POST['tipo_actividad'] ) ) && ( isset( $_POST['identificador_copia'] ) ) ) {
			
			if ( $_POST['tipo_actividad'] == 'Foro' ) {
	?>
				var id_for_cop = <?php echo $_POST['identificador_copia']; ?>;
				var nom_for = "<?php echo $_POST['titulo_actividad']; ?>";

				$.ajax({
			      	url: 'server/obtener_controlador_foro.php',
			      	type: 'POST',
			      	data: { id_for_cop },
			      	success: function ( respuesta ) {
			        	// console.log( respuesta );
			        
			        	$( '#modal_obtener_actividad' ).modal( 'show' );
			        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
			        	$( '#titulo_modal_obtener_actividad' ).html( nom_for );

			      	}

			    });

	<?php
			} else if ( $_POST['tipo_actividad'] == 'Entregable' ) {
	?>

				var id_ent_cop = <?php echo $_POST['identificador_copia']; ?>;
				var nom_ent = "<?php echo $_POST['titulo_actividad']; ?>";

				$.ajax({
			      	url: 'server/obtener_controlador_entregable.php',
			      	type: 'POST',
			      	data: { id_ent_cop },
			      	success: function ( respuesta ) {
			        	// console.log( respuesta );
			        
			        	$( '#modal_obtener_actividad' ).modal( 'show' );
			        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
			        	$( '#titulo_modal_obtener_actividad' ).html( nom_ent );

			      	}

			    });

	<?php
			} else if ( $_POST['tipo_actividad'] == 'Examen' ) {
	?>
				var id_exa_cop = <?php echo $_POST['identificador_copia']; ?>;
				var nom_exa = "<?php echo $_POST['titulo_actividad']; ?>";

				$.ajax({
			      	url: 'server/obtener_controlador_examen.php',
			      	type: 'POST',
			      	data: { id_exa_cop },
			      	success: function ( respuesta ) {
			        	// console.log( respuesta );
			        
			        	$( '#modal_obtener_actividad' ).modal( 'show' );
			        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
			        	$( '#titulo_modal_obtener_actividad' ).html( nom_exa );

			      	}

			    });

	<?php
			}
	?>


	<?php
		}
	?>

	// FIN GET

</script>