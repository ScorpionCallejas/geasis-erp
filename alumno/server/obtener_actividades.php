<?php  
	//EXAMEN VIA AJAX PARA OBTENER RECURSOS TEORICOS
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_blo = $_POST['id_blo'];
	$id_sub_hor = $_POST['id_sub_hor'];
	$id_alu_ram = $_POST['id_alu_ram'];

	$totalRecursos = contadorRecursosPracticosServer( $id_blo, $id_sub_hor, $id_alu_ram );
	// $totalRecursos = 0;
	if ( $totalRecursos > 0 ) {
		// HAY RECURSOS
?>
		
		<?php  
	        $sql = "
	        	SELECT id_for AS identificador, id_for_cop AS identificador_copia, nom_for AS titulo, des_for AS descripcion, fec_for AS fecha, tip_for AS tipo, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, fec_cal_act AS fec_cal_act, pun_cal_act AS pun_cal_act
	        	FROM cal_act
	        	INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
	        	INNER JOIN foro ON foro.id_for = foro_copia.id_for1
	        	WHERE id_sub_hor2 = '$id_sub_hor' AND id_blo4 = '$id_blo' AND id_alu_ram4 = '$id_alu_ram'
				UNION
				SELECT id_ent AS identificador, id_ent_cop AS identificador_copia, nom_ent AS titulo, des_ent AS descripcion, fec_ent AS fecha, tip_ent AS tipo, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, fec_cal_act AS fec_cal_act, pun_cal_act AS pun_cal_act
				FROM cal_act
	        	INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
				INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
				WHERE id_sub_hor3 = '$id_sub_hor' AND id_blo5 = '$id_blo' AND id_alu_ram4 = '$id_alu_ram'
				UNION
				SELECT id_exa AS identificador, id_exa_cop AS identificador_copia, nom_exa AS titulo, des_exa AS descripcion, fec_exa AS fecha, tip_exa AS tipo, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, fec_cal_act AS fec_cal_act, pun_cal_act AS pun_cal_act
				FROM cal_act
	        	INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
				INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
				WHERE id_sub_hor4 = '$id_sub_hor' AND id_blo6 = '$id_blo' AND id_alu_ram4 = '$id_alu_ram'
	        	ORDER BY fec_cal_act ASC

	        ";


	        // echo $sql;
	        $resultado = mysqli_query( $db, $sql );
	        $i = 1;
	        while( $fila = mysqli_fetch_assoc( $resultado ) ){

	        	$identificador = $fila['identificador_copia'];
	    		$tipo = $fila['tipo'];

	    		$datos = obtenerPorcentajeParticipacionActividadServer( $tipo, $identificador );

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
						    					
													

						    						<br>
						    						<a class="btn-link text-primary revisarActividadForo" id_for_cop="<?php echo $fila['identificador_copia']; ?>" nom_for="<?php echo $fila['titulo']; ?>" href="#" estatus="<?php echo obtenerEstatusActividadServer( $fila['fec_cal_act'], $fila['inicio'], $fila['fin'], $fila['pun_cal_act'] ); ?>">
														<h6 >
							    							<?php echo substr( $fila["titulo"], 0, 50 ); ?>
							    							
							    						</h6>
													</a>	
						    						
						    						
						    					</div>

						    					<div class="col-md-6 text-left grey-text">
						    						
						    						
							    						<?php
					    									if ( $fila['pun_cal_act'] == "" ) {
					    										
					    										$calificacion = 'Sin calificación';

					    									} else {
					    										
					    										$calificacion = $fila['pun_cal_act'];
					    									
					    									}
					    									
					    								?>
							    						
														<div class="row">
							    							Puntos totales: <?php echo $fila['puntaje']; ?>
							    						</div>
							    						
							    						<div class="row">
							    							Puntos obtenidos: <?php echo $calificacion; ?>
							    						</div>

							    						
						    						
						    							

						    						
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
		            
								                if ( obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ) > 0 ) {
								            
								            ?>
								                	<span class="badge badge-danger claseHijoActividad rounded-circle" title="Tienes <?php echo obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ); ?> actividades pendientes por revisar">
								                		<?php 
								                		
								                			echo obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ); 
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
												
												<a class="dropdown-item waves-effect revisarActividadForo" id_for_cop="<?php echo $fila['identificador_copia']; ?>" nom_for="<?php echo $fila['titulo']; ?>" href="#" estatus="<?php echo obtenerEstatusActividadServer( $fila['fec_cal_act'], $fila['inicio'], $fila['fin'], $fila['pun_cal_act'] ); ?>">
													Revisar
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
						    					
													
						    						<br>
						    						<a class="btn-link text-primary revisarActividadEntregable" id_ent_cop="<?php echo $fila['identificador_copia']; ?>" nom_ent="<?php echo $fila['titulo']; ?>" href="#" estatus="<?php echo obtenerEstatusActividadServer( $fila['fec_cal_act'], $fila['inicio'], $fila['fin'], $fila['pun_cal_act'] ); ?>">
														<h6>
							    							<?php echo substr( $fila["titulo"], 0, 50 ); ?>
							    							
							    						</h6>
													</a>
						    						
						    					</div>

						    					<div class="col-md-6 text-left grey-text">
						    						
						    						
						    						<?php
				    									if ( $fila['pun_cal_act'] == "" ) {
				    										
				    										$calificacion = 'Sin calificación';

				    									} else {
				    										
				    										$calificacion = $fila['pun_cal_act'];
				    									
				    									}
				    									
				    								?>
						    						<div class="row">
						    							Puntos totales: <?php echo $fila['puntaje']; ?>
						    						</div>
						    						
						    						<div class="row">
						    							Puntos obtenidos: <?php echo $calificacion; ?>
						    						</div>

						    						
						    					</div>

						    					
						    				</div>
								          
								        <!-- </a> -->
						
						    		</div>


						    		<div class="col-md-2 text-right">

						    			<!--Dropdown primary-->
										<div class="dropdown clasePadreActividad">
										

											<?php
		            
								                if ( obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ) > 0 ) {
								            
								            ?>
								                	<span class="badge badge-danger claseHijoActividad rounded-circle" title="Tienes <?php echo obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ); ?> actividades pendientes por revisar">
								                		<?php 
								                		
								                			echo obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ); 
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
												
												<a class="dropdown-item waves-effect revisarActividadEntregable" id_ent_cop="<?php echo $fila['identificador_copia']; ?>" nom_ent="<?php echo $fila['titulo']; ?>" href="#" estatus="<?php echo obtenerEstatusActividadServer( $fila['fec_cal_act'], $fila['inicio'], $fila['fin'], $fila['pun_cal_act'] ); ?>">
													Revisar
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
						    					
													
						    						<br>
						    						<a class="text-primary btn-link revisarActividadExamen" id_exa_cop="<?php echo $fila['identificador_copia']; ?>" nom_exa="<?php echo $fila['titulo']; ?>" href="#" estatus="<?php echo obtenerEstatusActividadServer( $fila['fec_cal_act'], $fila['inicio'], $fila['fin'], $fila['pun_cal_act'] ); ?>">
														
														<h6>
							    							<?php echo substr( $fila["titulo"], 0, 50 ); ?>
							    							
							    						</h6>

													</a>
						    						
						    						
						    					</div>

						    					<div class="col-md-6 text-left grey-text">
						    						
						    						
						    						<?php
				    									if ( $fila['pun_cal_act'] == "" ) {
				    										
				    										$calificacion = 'Sin calificación';

				    									} else {
				    										
				    										$calificacion = $fila['pun_cal_act'];
				    									
				    									}
				    									
				    								?>
						    						<div class="row">
						    							Puntos totales: <?php echo $fila['puntaje']; ?>
						    						</div>
						    						
						    						<div class="row">
						    							Puntos obtenidos: <?php echo $calificacion; ?>
						    						</div>

						    						
						    					</div>

						    				
						    					
						    				</div>
								          
								        <!-- </a> -->
						
						    		</div>


						    		<div class="col-md-2 text-right">

						    			<!--Dropdown primary-->
										<div class="dropdown clasePadreActividad">
										

											<?php
		            
								                if ( obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ) > 0 ) {
								            
								            ?>
								                	<span class="badge badge-danger claseHijoActividad rounded-circle" title="Tienes <?php echo obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ); ?> actividades pendientes por revisar">
								                		<?php 
								                		
								                			echo obtenerTotalNotificacionesActividadServer( $identificador, $id_alu_ram ); 
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
												
												<a class="dropdown-item waves-effect revisarActividadExamen" id_exa_cop="<?php echo $fila['identificador_copia']; ?>" nom_exa="<?php echo $fila['titulo']; ?>" href="#" estatus="<?php echo obtenerEstatusActividadServer( $fila['fec_cal_act'], $fila['inicio'], $fila['fin'], $fila['pun_cal_act'] ); ?>">
													Revisar
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
			    		
			    		<div class="row">
			    			
							<div class="col-md-3">
								
							</div>


							<div class="col-md-6">
								<label class="letraMediana grey-text font-weight-bold">
									<?php
										echo "Del ".mb_strtolower( obtenerFechaGuapa( $fila['inicio'] ) )." al ".mb_strtolower( obtenerFechaGuapa( $fila['fin'] ) );
									?>
								</label>
							</div>


							<div class="col-md-3 text-right">
								<?php
									echo obtenerBadgeEstatusActividadServer( $fila['fec_cal_act'], $fila['fin'], $fila['pun_cal_act'] );
								?>
							</div>

			    		</div>
				    	

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



<!-- MODAL OBTENER ACTIVIDAD -->
<div class="modal fade text-left " id="modal_obtener_actividad">
  <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
        <div class="modal-header text-center grey darken-1 white-text">
          
          	<h4 class="modal-title w-100 white-text" id="titulo_modal_obtener_actividad">
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
	// REVISION DE ACTIVIDADES

	$( '.revisarActividadForo' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var estatus = $( this ).attr( 'estatus' );
		

		if ( estatus == 'Vencida' ) {
			swal("Actividad vencida :(", "No realizaste esta actividad en tiempo y forma, comunícate con tu profesor...", "error", {button: "Aceptar",});

		} else if ( estatus == 'Por entregar' ) {

			swal("Actividad por entregar", "¡Esta actividad aun no se habilita!, todavía falta tiempo...", "info", {button: "Aceptar",});

		} else {

			var id_for_cop = $( this ).attr( 'id_for_cop' );
			var nom_for = $( this ).attr( 'nom_for' );
			var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';



			$.ajax({
		      	url: 'server/obtener_controlador_foro.php',
		      	type: 'POST',
		      	data: { id_for_cop, id_alu_ram },
		      	success: function ( respuesta ) {
		        	// console.log( respuesta );
		        
		        	$( '#modal_obtener_actividad' ).modal( 'show' );
		        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
		        	$( '#titulo_modal_obtener_actividad' ).html( nom_for );

		      	}

		    });

		}

		

	});


	$( '.revisarActividadEntregable' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var estatus = $( this ).attr( 'estatus' );
		

		if ( estatus == 'Vencida' ) {
			swal("Actividad vencida :(", "No realizaste esta actividad en tiempo y forma, comunícate con tu profesor...", "error", {button: "Aceptar",});

		} else if ( estatus == 'Por entregar' ) {

			swal("Actividad por entregar", "¡Esta actividad aun no se habilita!, todavía falta tiempo...", "info", {button: "Aceptar",});

		} else {

			var id_ent_cop = $( this ).attr( 'id_ent_cop' );
			var nom_ent = $( this ).attr( 'nom_ent' );
			var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';

			$.ajax({
		      	url: 'server/obtener_controlador_entregable.php',
		      	type: 'POST',
		      	data: { id_ent_cop, id_alu_ram },
		      	success: function ( respuesta ) {
		        	// console.log( respuesta );
		        
		        	$( '#modal_obtener_actividad' ).modal( 'show' );
		        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
		        	$( '#titulo_modal_obtener_actividad' ).html( nom_ent );

		      	}

		    });

		}


		
	});



	$( '.revisarActividadExamen' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var estatus = $( this ).attr( 'estatus' );
		
		if ( estatus == 'Vencida' ) {
			swal("Actividad vencida :(", "No realizaste esta actividad en tiempo y forma, comunícate con tu profesor...", "error", {button: "Aceptar",});

		}else if ( estatus == 'Por entregar' ) {

			swal("Actividad por entregar", "¡Esta actividad aun no se habilita!, todavía falta tiempo...", "info", {button: "Aceptar",});

		} else {

			var id_exa_cop = $( this ).attr( 'id_exa_cop' );
			var nom_exa = $( this ).attr( 'nom_exa' );

			$( '#titulo_modal_obtener_actividad' ).html( nom_exa );

			obtener_controlador_examen( id_exa_cop );

		}

		
	});


	function obtener_controlador_examen( id_exa_cop ){
		
		var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';

		$.ajax({
	      	url: 'server/obtener_controlador_examen.php',
	      	type: 'POST',
	      	data: { id_exa_cop, id_alu_ram },
	      	success: function ( respuesta ) {
	        	// console.log( respuesta );
	        
	        	$( '#modal_obtener_actividad' ).modal( 'show' );

	        	// CODIGO PARA CANCELAR EL CIERRE DE LA MODAL
	   //      	$('#modal_obtener_actividad').on('hide.bs.modal', function(e){
				
				//     e.preventDefault();
				//     e.stopImmediatePropagation();
				 
				
				// });

	        	$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
	        	

	      	}

	    });
	}



	// GET
	var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';
	<?php  
		if ( ( isset( $_POST['tipo_actividad'] ) ) && ( isset( $_POST['identificador_copia'] ) ) ) {
			if ( $_POST['tipo_actividad'] == 'Foro' ) {
	?>
				var id_for_cop = <?php echo $_POST['identificador_copia']; ?>;
				var nom_for = "<?php echo $_POST['titulo_actividad']; ?>";

				$.ajax({
			      	url: 'server/obtener_controlador_foro.php',
			      	type: 'POST',
			      	data: { id_for_cop, id_alu_ram },
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
			      	data: { id_ent_cop, id_alu_ram },
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
			      	data: { id_exa_cop, id_alu_ram },
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