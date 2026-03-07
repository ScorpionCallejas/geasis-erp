<?php  
 
	include('inc/header.php');

?>
	
	<!-- CONTENIDO -->
	<!-- TITULO -->
	<div class="row ">
		<div class="col text-left">
			<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Alumnos">
				<i class="fas fa-bookmark"></i> Alumnos
			</span>
			<br>
			<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
				<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: black;" href="" title="Estás aquí">Alumnos</a>
			</div>
			
		</div>
		
	</div>
	<!-- FIN TITULO -->


	<style>
		.stickyChida{
			position: -webkit-sticky;
			  position: sticky;
			  top: 50px;
		}

	    .has-search .form-control {
	        padding-left: 2.375rem;
	    }

	    .has-search .form-control-feedback {
	        position: absolute;
	        z-index: 2;
	        display: block;
	        width: 2.375rem;
	        height: 2.375rem;
	        line-height: 2.375rem;
	        text-align: center;
	        pointer-events: none;
	        color: #aaa;
	    }

	    #tabla_alumnos>th:nth-child(0), #tabla_alumnos_wrapper    td:nth-child(0) {  
	      background-color:#E9E9E9;
	      font-size: 12px;
	      position: relative;
	      top: 17px;
	    }

	    #tabla_alumnos>th:nth-child(1), #tabla_alumnos_wrapper td:nth-child(1) {  
	      background-color:#E9E9E9;
	      font-size: 12px;
	      position: relative;
	      top: 17px;

	    }


	    #tabla_alumnos>th:nth-child(2), #tabla_alumnos_wrapper td:nth-child(2) {  
	      background-color:#E9E9E9;
	      font-size: 12px;
	      position: relative;
	      top: 17px;
	    }


	/*    #tabla_alumnos th:nth-child(2)  {  
	      background-color: red !important;
	      font-size: 12px;
	    }
	*/




	</style>

	<style>
	    .dropdown-menu {
	        max-height: 10vw;
	        overflow-y: auto;
	    }
	</style>

	<!-- MODAL PAGOS -->
	<div class="modal fade text-left" id="modal_reporte_iniciados">
	  	
	  	<div class="modal-dialog modal-lg" role="document">
	    
			    
			    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
			      	
			      	<div class="modal-header text-center" style="position: relative;">

			            <div class="row">
			                <div class="col-md-12">
			                  
			                	<span class=" letraGrande">  
				        			Reporte de iniciados
				        			<span id="titulo_reporte_iniciados"></span>
				                </span>

			                </div>
			            </div>
			              
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
			                <span aria-hidden="true">&times;</span>
			            </button>

			      	</div>
			      
			      	<div class="modal-body mx-3" id="contenedor_reporte_iniciados">			      		
			      	</div>

			    	<div class="modal-footer d-flex justify-content-center">
			    	

	                	<a class="btn btn-info btn-rounded waves-effect btn-sm" title="Aceptar y salir..." data-dismiss="modal">
	                    	Aceptar
	                	</a>    


			    	</div>

			    </div>

	  	</div>
	
	</div>

	<!-- FIN MODAL PAGOS -->

	<!-- MODAL PAGOS -->
	<div class="modal fade text-left" id="modal_generacion_pagos">
	  	
	  	<div class="modal-dialog modal-lg" role="document">
	    
			<form id="formulario_generacion_pagos">
			    
			    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
			      	
			      	<div class="modal-header text-center" style="position: relative;">

			            <div class="row">
			                <div class="col-md-12">
			                  
			                	<span class=" letraGrande">  
				        			Calendario de pagos - Grupo : 
				        			<span id="titulo_generacion_pagos"></span>
				                </span>

			                </div>
			            </div>
			              
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
			                <span aria-hidden="true">&times;</span>
			            </button>

			      	</div>
			      
			      	<div class="modal-body mx-3" id="contenedor_generacion_pagos">			      		
			      	</div>

			    	<div class="modal-footer d-flex justify-content-center">
			    	
			    		<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_generacion_pagos">
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

	<!-- FIN MODAL PAGOS -->

	<!-- BAJA ALUMNO -->
	<div class="modal fade text-left" id="modal_baja_alumno">
	  
	  <div class="modal-dialog modal-lg" role="document">
	    
		<form id="formulario_baja_alumno">
		    
		    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
		      
		      <div class="modal-header text-center">

		        <h5 class="modal-title grey-text" id="nombre_baja_alumno">
		        	
		        </h5>
		      
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      
		      </div>
		      
		      	<div class="modal-body mx-3">

		      		<div class="row">
		      			
		      			<div class="col-md-12">

		      				<div class="alert alert-warning alert-dismissible fade show letraPequena" role="alert" id="alerta_baja_alumno">
							</div>
		      				

		      				<div class="md-form">
							  	<textarea style="font-size: 10px;" id="mot_ing_alu_ram" name="mot_ing_alu_ram" class="md-textarea form-control" rows="3" required=""></textarea>
							  	<label for="mot_ing_alu_ram">Motivo de <span id="label_baja_alumno"></span></label>
							</div>

							<input type="hidden" id="id_alu_ram_baja_alumno" name="id_alu_ram_baja_alumno">
							<input type="hidden" id="tip_ing_alu_ram" name="tip_ing_alu_ram">
					        
		      			</div>

		      		</div>

		      	</div>

			    <div class="modal-footer d-flex justify-content-center">
			    	
			    	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_baja_alumno">
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
	<!-- FIN BAJA ALUMNO -->


	<!-- MODAL GENERACION -->

	<!-- AGREGAR -->
	<div class="modal fade text-left" id="modal_generacion">
	  <div class="modal-dialog modal-lg" role="document">
	    
		<form id="modal_generacion_formulario" enctype="multipart/form-data">
		    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
		      <div class="modal-header text-center">
		        <h5 class="modal-title"><i class="fas fa-graduation-cap"></i> Grupo: <span id="modal_generacion_titulo"></span></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      
		      	<div class="modal-body mx-3">

		      		<span class="grey-text">
                    	Programa académico
                    </span>


                    <hr>

		      		<div class="scrollspy-example" style=" height: 200px;">

                      <?php  
                        $sqlProgramas = "
                          SELECT *
                          FROM rama
                          WHERE id_pla1 = '$plantel'
                          ORDER BY id_ram ASC
                        ";

                        $resultadoProgramas = mysqli_query( $db, $sqlProgramas );


                        $contadorProgramas = 1;

                   
                          while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                        ?>

                          <?php  
                            if ( $contadorProgramas == 1 ) {
                          ?>


                              <div class="form-check p-1">
                                  <input type="radio" class="form-check-input programasGeneracion" id="programaGeneracionModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram" checked>
                                  <label class="form-check-label font-weight-normal" for="programaGeneracionModal<?php echo $contadorProgramas; ?>">
                                
                                    <?php echo $filaProgramas['nom_ram']; ?>

                                  </label>
                        
                              </div>

                          <?php
                            } else {
                          ?>
                              <div class="form-check p-1">
                                  <input type="radio" class="form-check-input programasGeneracion" id="programaGeneracionModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram">
                                  <label class="form-check-label font-weight-normal" for="programaGeneracionModal<?php echo $contadorProgramas; ?>">
                                
                                    <?php echo $filaProgramas['nom_ram']; ?>

                                  </label>
                        
                              </div>

                          <?php
                            }
                          ?>
                          

                      <?php
                          $contadorProgramas++;
                        }
                        // FIN while
                      ?>
                    </div>

                    <hr>

		      		<div class="row">
		      			<div class="col-md-12">
		      				<div class="md-form">
					          	<input type="text" id="nom_gen" name="nom_gen" class="form-control validate">
					          	<label  for="nom_gen">Grupo</label>
					        </div>
		      			</div>

		      			
		      		</div>

		      		<div class="row">
		      			<div class="col-md-6">
		      				<div class="md-form">
		      					<span class="letraMediana grey-text">
		      						Inicia
		      					</span>
		      					<br>
								<input type="date"  id="ini_gen" name="ini_gen" class="form-control validate programasGeneracion" value="<?php echo date( 'Y-m-d' ); ?>">
					        </div>
					        
		      			</div>

		      			<div class="col-md-6">
		      				<div class="md-form">
		      					<span class="letraMediana grey-text">
		      						Termina
		      					</span>
		      					<br>
								<input type="date" id="fin_gen" name="fin_gen" class="form-control validate programasGeneracion" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 120 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
					        </div>
					        
		      			</div>
		      		</div>

		      		<hr>

		      		<div class="row">
		      			<div class="col-md-12 text-center">
		
		      				<div class="form-check">
							  	<input type="checkbox" class="form-check-input" id="checkbox_pagos" checked>
							  	<label class="form-check-label" for="checkbox_pagos">
							  		<span class="">
							  			Gestionar calendario de pagos al guardar
							  		</span>
							  	</label>
							</div>

		      			</div>
		      		</div>
		      		
		      	</div>

		    <div class="modal-footer d-flex justify-content-center">
		    	
		    	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_submit_generacion">
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
	<!-- FIN AGREGAR -->
	<!-- FIN MODAL GENERACION -->



	<!-- MODAL GENERACION EDICION-->

	<!-- EDICION -->
	<div class="modal fade text-left" id="modal_generacion_edicion">
	  <div class="modal-dialog modal-lg" role="document">
	    
		<form id="modal_generacion_formulario_edicion" enctype="multipart/form-data">
		    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
		      <div class="modal-header text-center">
		        <h5 class="modal-title"><i class="fas fa-graduation-cap"></i> Grupo: <span id="modal_generacion_titulo_edicion"></span></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      
		      	<div class="modal-body mx-3">

		      		<input type="hidden" id="id_gen_edicion" name="id_gen_edicion">

		      		<div class="row">
		      			<div class="col-md-12">
		      				<div class="md-form">
					          	<input type="text" id="nom_gen_edicion" name="nom_gen_edicion" class="form-control validate programasGeneracion_edicion">
					          	<label  for="nom_gen_edicion">Grupo</label>
					        </div>
		      			</div>

		      			
		      		</div>

		      		<div class="row">
		      			<div class="col-md-6">
		      				<div class="md-form">
		      					<span class="letraMediana grey-text">
		      						Inicia
		      					</span>
		      					<br>
								<input type="date"  id="ini_gen_edicion" name="ini_gen_edicion" class="form-control validate programasGeneracion_edicion" value="<?php echo date( 'Y-m-d' ); ?>">
					        </div>
					        
		      			</div>

		      			<div class="col-md-6">
		      				<div class="md-form">
		      					<span class="letraMediana grey-text">
		      						Termina
		      					</span>
		      					<br>
								<input type="date" id="fin_gen_edicion" name="fin_gen_edicion" class="form-control validate programasGeneracion_edicion" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 120 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
					        </div>
					        
		      			</div>
		      		</div>

		      	</div>

		    <div class="modal-footer d-flex justify-content-center">
		    	
		    	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_submit_generacion_edicion">
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
	<!-- FIN EDICION -->
	<!-- FIN MODAL EDICION GENERACION -->
	
	
	<!-- MODAL CONSULTA ACTIVIDADES ALUMNO -->

	<div class="modal fade" id="modalActividadesAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h5 class="modal-title w-100" id="tituloActividadesAlumno"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      <div class="modal-body" id="contenedorActividadesAlumno">
	        
	        


	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- fin MODAL CONSULTA ACTIVIDADES ALUMNO -->

	
	<!-- DASHBOARD -->
	<div id="contenedor_dashboard" style="display: none; ">
		
		<div class="row">
		
			<!-- COL -->
			<div class="col-md-12">

				<!-- CARD -->
				<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>">
					<div class="card-body" >


						<div class="row">
							
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-6 col-6 col-sm-6">
										
										<!-- GENERAL -->
										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >
											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												General
											</span>

											<div class="card-body scrollspy-example" style=" height: 200px;">

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Certificado" href="#" style="width: 60px;" title="Alumnos que han concluido su programa completo">
														Certificados
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_cer">
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Graduado" href="#" style="width: 60px;" title="Alumnos que aprobaron las materias de su programa académico">
														Graduados
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_gra">
													</span>
												</h6>


												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Fin" href="#" style="width: 60px;" title="Alumnos finalizaron su curso">
														Fin curso
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_fin">
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Activo" href="#" style="width: 60px;" title="Alumnos que pagaron su primer colegiatura">
														Activos
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_act">
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Registro" href="#" style="width: 60px;" title="Alumnos que aun no paga su primera colegiatura">
														Registros
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_reg">
													</span>
												</h6>

												<hr>


												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Promocionado" href="#" style="width: 60px;" title="Alumnos que aun no paga nada">
														Promocionado
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_apa">
													</span>
												</h6>

												<hr>


												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Anticipado" href="#" style="width: 60px;" title="Alumnos que ya pagaron y no han comenzado">
														Anticipado
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_ant">
													</span>
												</h6>

												<hr>


												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="NP" href="#" style="width: 60px;" title="Alumnos que pagaron Insc. pero no colegiatura y ya arrancó el curso">
														NP
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_np">
													</span>
												</h6>

												<hr>



												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Bloqueado" href="#" style="width: 60px;" title="Alumnos que adeudan un mes">
														Bloqueado
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_blo">
													</span>
												</h6>

												<hr>


											


												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Suspendido" href="#" style="width: 60px;" title="Alumnos que adeudan más de un mes">
														Suspendido
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_sus">
													</span>
												</h6>

												<hr>



												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Reingreso" href="#" style="width: 60px;" title="Alumnos que reingresan">
														Reingreso
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_rei">
													</span>
												</h6>

												<hr>



												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Baja" href="#" style="width: 60px;" title="Alumnos con baja definitiva">
														Baja definitiva
													</a>
													<br>
													<span class="font-weight-bold" id="est_gen_baj">
													</span>
												</h6>

												<hr>
												

											</div>
											
										</div>
										<!-- FIN GENERAL -->
									</div>
									<div class="col-md-6 col-6 col-sm-6">
										<!-- ACADEMICO -->
						

										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >
											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Académico
											</span>
											
										
											<div class="card-body scrollspy-example" style=" height: 200px;">
												

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_academico" estatus="Activo" href="#" style="width: 60px;" title="Alumnos con una carga académica">
														Activos
													</a>
													<br>
													<span class="font-weight-bold" id="est_aca_act">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_academico" estatus="Inactivo" href="#" style="width: 60px;" title="Alumnos sin una carga académica">
														Inactivos
													</a>
													<br>
													<span class="font-weight-bold" id="est_aca_ina">
														
													</span>
												</h6>

												<hr>


												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_academico" estatus="Egresado" href="#" style="width: 60px;" title="Alumnos que aprobaron las materias de su programa académico">
														Egresados
													</a>
													<br>
													<span class="font-weight-bold" id="est_aca_egr">
														
													</span>
												</h6>




											</div>
										</div>
										<!-- FIN ACADEMICO -->
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<div class="col-md-6 col-6 col-sm-6">
										<!-- PAGOS -->
										
						

										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >
											
											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Pagos
											</span>
											

											<div class="card-body scrollspy-example" style=" height: 200px;">

												
												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_pago" estatus="Sin adeudo" href="#" style="width: 60px;" title="Alumnos que no adeudan pagos">
														Sin adeudo
													</a>
													<br>
													<span class="font-weight-bold" id="est_pag_sin">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_pago" estatus="Con adeudo" href="#" style="width: 60px;" title="Alumnos que adeudan pagos">
														Con adeudo
													</a>
													<br>
													<span class="font-weight-bold" id="est_pag_con">
														
													</span>
												</h6>

												

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="meses_adeudo" estatus="1 mes" href="#" style="width: 60px;" title="Alumnos que adeudan un mes de pagos">
														1 mes
													</a>
													<br>
													<span class="font-weight-bold" id="est_mes_1me">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="meses_adeudo" estatus="2 meses" href="#" style="width: 60px;" title="Alumnos que adeudan dos meses de pagos">
														2 meses
													</a>
													<br>
													<span class="font-weight-bold" id="est_mes_2me">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="meses_adeudo" estatus="Más de 2 meses" href="#" style="width: 60px;" title="Alumnos que adeudan más de dos meses de pagos">
														Más de 2 meses
													</a>
													<br>
													<span class="font-weight-bold" id="est_mes_mas">
														
													</span>
												</h6>



											</div>
										</div>
										<!-- FIN PAGOS -->
									</div>
									<div class="col-md-6 col-6 col-sm-6">
					

										<!-- COBRANZA -->
										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >

											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Cobranza
											</span>
											

											<div class="card-body scrollspy-example" style=" height: 200px;">

												
												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="pagado_alumno" estatus="pagado" href="#" style="width: 60px;" title="Total pagado">
														Pagado
													</a>
													<br>
													<span class="font-weight-bold" id="est_tot_pag">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="adeudo_alumno" estatus="adeudo" href="#" style="width: 60px;" title="Total adeudo">
														Adeudo
													</a>
													<br>
													<span class="font-weight-bold" id="est_tot_ade">
														
													</span>
												</h6>

												

											</div>
										</div>
										<!-- FIN COBRANZA -->
									</div>
								</div>
							</div>





							<!-- ESTATUS SECUNDARIOS -->

							<div class="col-md-3">
								<div class="row">
									<div class="col-md-6 col-6 col-sm-6">
								

										<!-- CUENTA -->
										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >

											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Cuenta
											</span>
											
										
											<div class="card-body scrollspy-example" style=" height: 200px;">

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="est_alu" estatus="Activo" href="#" style="width: 60px;" title="Alumnos con cuenta activa">
														Activas
													</a>
													<br>
													<span class="font-weight-bold" id="est_cue_act">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="est_alu" estatus="Inactivo" href="#" style="width: 60px;" title="Alumnos con cuenta suspendida por el administrador">
														Inactivas
													</a>
													<br>
													<span class="font-weight-bold" id="est_cue_ina">
														
													</span>
												</h6>


											</div>
										</div>
										<!-- FIN CUENTA -->
									</div>


									<div class="col-md-6 col-6 col-sm-6">
										<!-- ACTIVIDAD -->

										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >

											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Actividad
											</span>
											
											
											<div class="card-body scrollspy-example" style=" height: 200px;">

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_actividad" estatus="Adeudo" href="#" style="width: 60px;" title="Alumnos que adeudan actividades en modalidad en línea">
														Adeuda
													</a>
													<br>
													<span class="font-weight-bold" id="est_act_ade">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_actividad" estatus="N/A" href="#" style="width: 60px;" title="Alumnos que van al corriente en modalidad en línea">
														N/A
													</a>
													<br>
													<span class="font-weight-bold" id="est_act_na">
														
													</span>
												</h6>



											</div>
										</div>
										<!-- FIN ACTIVIDAD -->
									</div>

								</div>
							</div>

							<div class="col-md-3">
								<div class="row">


									<div class="col-md-6 col-6 col-sm-6">
										<!-- DOCUMENTACION -->

				

										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >

											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Docs. entreg.
											</span>
											
										
											<div class="card-body scrollspy-example" style=" height: 200px;">

												<h6 class="letraNumerica">
														
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_documentacion" estatus="Entregados" href="#" style="width: 60px;" title="Alumnos que ya entregaron todos sus documentos">
														Entregados
													</a>
													<br>
													<span class="font-weight-bold" id="est_doc_ent">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_documentacion" estatus="Pendiente" href="#" style="width: 60px;" title="Alumnos que adeudan documentación">
														Pendientes
													</a>
													<br>
													<span class="font-weight-bold" id="est_doc_pen">
														
													</span>
												</h6>

												


											</div>
										</div>
										<!-- FIN DOCUMENTACION -->
									</div>


									


									<div class="col-md-6 col-6 col-sm-6">
										<!-- DOCUMENTACION -->

				

										<div class="card  z-depth-1 <?php echo $estilos_modo['card']; ?>" >

											<span class="letraPequena p-2 font-weight-bold letraNumerica">
												Docs. recibidos
											</span>
											
										
											<div class="card-body scrollspy-example" style=" height: 200px;">

												<h6 class="letraNumerica">
														
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_documentacion2" estatus="Entregados" href="#" style="width: 60px;" title="Alumnos que ya entregaron todos sus documentos">
														Entregados
													</a>
													<br>
													<span class="font-weight-bold" id="est_doc_ent2">
														
													</span>
												</h6>

												<hr>

												<h6 class="letraNumerica">
													<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_documentacion2" estatus="Pendiente" href="#" style="width: 60px;" title="Alumnos que adeudan documentación">
														Pendientes
													</a>
													<br>
													<span class="font-weight-bold" id="est_doc_pen2">
														
													</span>
												</h6>

												


											</div>
										</div>
										<!-- FIN DOCUMENTACION -->
									</div>
								</div>
							</div>

							
						</div>
						
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- FIN DASHBOARD -->

	<div class="row">
		<div class="col-md-12" style="position: relative;">
		
			<hr>
			<a href="#" class="btn btn-link btn-sm letraMediana" title="Amplia/Reduce el espacio de alumnos" id="btn_espacio" estatus='Inactivo' style="position: absolute; top: 3px; left: -14px;">
				<i class="fas fa-angle-double-left"></i> Ampliar/Reducir
			</a>
		
		</div>
	</div>
	



	

	<!-- BUSCADOR Y FECHAS -->
	<div class="row">
		
		<!-- COL  FILTROS -->
		<div class="col-md-3" id="contenedor_col-3">

			
			<div class="stickyChida">
				<!-- CARD -->
				<div class="card z-depth-1 <?php echo $estilos_modo['card']; ?> scrollspy-example " style=" height: 600px; border-radius: 20px;">
					
					<div class="card-body">

						<div class=" scrollspy-example" style=" height: 200px; display: none;">
							<div class="row">
								<div class="col-md-12" id="contenedor_seleccion_alumnos">
									
								</div>


								<div>
									<div class="form-check"> <input type="checkbox" class="form-check-input" id="checkbox_generacion_pagos" value="0"> <label class="form-check-label" for="checkbox_generacion_pagos"> </label></div>
								</div>

							</div>
						</div>
		            	
		            	<span class="">
							Vista
						</span>


						<div class="row">
							<div class="col-md-6">
								<div class="form-check form-check-inline">
									<input type="radio" class="form-check-input radiosVisualizacion" id="radioGeneraciones" name="radiosVisualizacion" value="Generaciones" >
									<label class="form-check-label letraPequena" for="radioGeneraciones">Grupos</label>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-check form-check-inline">
									<input type="radio" class="form-check-input radiosVisualizacion" id="radioAlumnos" name="radiosVisualizacion" value="Alumnos" checked="">
									<label class="form-check-label letraPequena" for="radioAlumnos">Alumnos</label>
								</div>
							</div>

							<hr>
						</div>


						<hr>


						<!-- FILTROS -->
						<span class="">
							Filtros
						</span>


						<div class="row">
							<div class="col-md-12" id="contenedor_radios_fechas">
								<div class="form-check form-check-inline">
									<input type="radio" class="form-check-input radiosVisualizacion2" id="radioGeneraciones2" name="radiosVisualizacion2" value="Fechas" checked="">
									<label class="form-check-label letraPequena" for="radioGeneraciones2">Académicos</label>
								</div>
							</div>

							<div class="col-md-12" id="contenedor_radios_academicos">
								<div class="form-check form-check-inline">
									<input type="radio" class="form-check-input radiosVisualizacion2" id="radioAlumnos2" name="radiosVisualizacion2" value="Académicos">
									<label class="form-check-label letraPequena" for="radioAlumnos2">Fechas</label>
								</div>
							</div>

							<hr>
						</div>


						<hr>

						<!-- FILTROS FECHAS -->
						<div id="contenedor_filtros_fechas">
							<!--  -->
							
							<!-- FIN FILTROS -->

							<!-- FECHAS -->
							<div class="form-check form-check-inline">
							  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22" name="seleccionPeriodo" value="Mes" checked="">
							  	<label class="form-check-label letraPequena" for="materialGroupExample22">Mensual</label>
							</div>

							<br>
							<!-- Group of material radios - option 1 -->
							<div class="form-check form-check-inline">
							  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11" name="seleccionPeriodo"  value="Fecha">
							  	<label class="form-check-label letraPequena" for="materialGroupExample11">Día(s)</label>
							</div>
							<br>

							<!-- Group of material radios - option 2 -->
							<div class="form-check form-check-inline">
							  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample23" name="seleccionPeriodo" value="Semana">
							  	<label class="form-check-label letraPequena" for="materialGroupExample23">Semanal</label>
							</div>

							<hr>


							<!--  -->
				            <div class="row">

								<div class="col-md-12">
								<!--  -->

									<div id="contenedor_mes_annio" style="display: none;">
									<!--  -->
										<div class="row">
										
											<!--  -->
											<div class="col-md-12">

												<select class="browser-default custom-select letraPequena filtros" id="selectorMes">

												<!--  -->
												  	<?php
												  		
												  		$mesActualEntero = date('m');
												  		$mesActualTexto = getMonth( $mesActualEntero );

														$meses = 12;
														$i = 1;
													    
													    while( $i <= $meses ) {

													    	$j = $i+1;
													    	
													    	$final = date( 't', strtotime( date( 'Y-'.$j.'-0' ) ) );
													        

													        if ( $i < 10 ) {
													    		$i = "0".$i;
													    	}
													?>

														<?php  
															if ( $i == $mesActualEntero ) {
														?>
														
																<option selected value="<?php echo $i; ?>" inicio="1" fin="<?php echo $final; ?>"><?php echo getMonth( $i ); ?></option>
														
														<?php
															} else {
														?>

																<option value="<?php echo $i; ?>" inicio="1" fin="<?php echo $final; ?>"><?php echo getMonth( $i ); ?></option>

														<?php
															}
														?>

													                 

													<?php
													                    
										                    $i++;

													        
													    }
													?>
												<!--  -->
												</select>
												
											</div>


											
											<!--  -->
										</div>


										<div class="row">
											<div class="col-md-12">
												
												<select class="browser-default custom-select letraPequena filtros" id="selectorAnnio">

												<!--  -->
												  	<?php
												  		

														$annioActual = date('Y');
														$i = 2018;
														$annioFuturo = $annioActual+2;
													    
													    while( $i < $annioFuturo ) {

													        
													?>

														<?php  
															if ( $i == $annioActual ) {
														?>
														
																<option selected value="<?php echo $i; ?>"><?php echo $i; ?></option>
														
														<?php
															} else {
														?>

																<option value="<?php echo $i; ?>"><?php echo $i; ?></option>

														<?php
															}
														?>

													                 

													<?php
													                    
										                    $i++;

													        
													    }
													?>
												<!--  -->
												</select>

											</div>
										</div>
									<!--  -->
									</div>


									<!-- SEMANA Y LIBRE -->
									<div id="contenedor_fecha" style="display: none;">


										<div class="row">
											<div class="col-md-12">

										        	<input type="date" class="form-control filtros letraPequena" id="inicio" value="<?php echo date('Y-m-d'); ?>">
										        
										        
											</div>
											
										</div>

										<div class="row">
											<div class="col-md-12">
												
												<input type="date" class="form-control filtros letraPequena" id="fin" value="<?php echo date('Y-m-d'); ?>">
										        
											</div>
										</div>

									</div>

									<div id="contenedor_semana" style="display: none;">
										
										

										
										<select class="browser-default custom-select letraPequena filtros" id="selectorSemana">
										<!--  -->
										  	<?php
										  		$fechaHoy = date( 'Y-m-d' );
												$i = 0;
												$semanas = obtenerDiferenciaFechasSemanas( $fechaHoy, date('Y').'-01-01' );
												$lunes = date("j");
												$periodo = 6;
											    $periodicidad = $periodo+1;
											    
											    do {


											        if ( $i == 0 ) {

											            if ( $lunes != 6 ) {
											              //echo 'if';
											              $domingo_proximo =  $fechaHoy;
											              $lunes_proximo = date("N");
											              $lunes_proximo = $lunes_proximo-1;
											              $inicio = date('Y-m-d', strtotime($fechaHoy));
											              $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

											              // $semanas = $semanas + 1;

											            } else {
											              //echo 'else';

											                if ( $lunes == 6 ) {
											                    $domingo_proximo =  $fechaHoy;
											                    $lunes_proximo = date("N");
											                    $lunes_proximo = $lunes_proximo-1;
											                    $inicio = date('Y-m-d', strtotime($fechaHoy));
											                    $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
											                
											                } else {


											                    $domingo_proximo = date("N"); //domingo = 7
											                    $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
											                    $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
											                    $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

											                }

											            }
											        

											        } else {

											   
											            $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
											            $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
											            

											        }
											?>


											<?php
											        // echo $inicio;
											        if ( $fin < date('Y').'-01-01' ) {
											            // echo 'ok';
											            break; break; break;
											        }
											?>

													<?php  
														if ( $i == 0 ) {
													?>
															<option selected class="letraPequena" inicio="<?php echo $fin; ?>" fin="<?php echo $inicio; ?>">Semana <?php echo $semanas.' - '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?></option>
													<?php
														} else {
													?>

															<option class="letraPequena" inicio="<?php echo $fin; ?>" fin="<?php echo $inicio; ?>">Semana <?php echo $semanas.' - '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?></option>
													<?php
														}
													?>

											<?php

								                    $i++;
								                    $semanas--;

											        
											    } while( (date('Y').'-01-01' < $fin) );
											?>
										<!--  -->
										</select>
									</div>
									<!-- FIN SEMANA Y LIBRE -->
								<!--  -->
								</div>
							</div>
				            <!--  -->
							<!-- FIN FECHAS -->
							<!--  -->
						</div>
						<!-- FIN FILTROS FECHAS -->

						<hr>
						
						<!-- FILTROS ACADEMICOS -->
						<div id="contenedor_filtros_academicos">
							<!--  -->
							<div id="contenedor_filtros_estatus">
								<span>
		                            Estatus
		                        </span>

		                        <div class="row">
		                        	<div class="col-md-12">
		                        		<input type="checkbox" class="form-check-input checkboxEstatusGeneracion" id="checkboxEstatusGeneracion1" value="En curso" checked="checked">
                                        <label class="form-check-label letraPequena" for="checkboxEstatusGeneracion1" style="font-size: 10px;">
                                            <span class="text-success">⬤</span> En curso
                                        </label>


                                        <input type="checkbox" class="form-check-input checkboxEstatusGeneracion" id="checkboxEstatusGeneracion2" value="Por comenzar">
                                        <label class="form-check-label letraPequena" for="checkboxEstatusGeneracion2" style="font-size: 10px;">
                                            <span class="grey-text">⬤</span> Por comenzar
                                        </label>


                                        <input type="checkbox" class="form-check-input checkboxEstatusGeneracion" id="checkboxEstatusGeneracion3" value="Fin curso">
                                        <label class="form-check-label letraPequena" for="checkboxEstatusGeneracion3" style="font-size: 10px;">
                                            <span class="grey-text">⬤</span> Fin curso
                                        </label>

		                        	</div>
		                        </div>

							</div>

							<!-- PLANTELES -->
				            <div id="contenedor_planteles" style="
				            	<?php  
				            		if ( $tipo == 'Super' ) {
				            			echo "display: '';";
				            		} else {
				            			echo "display: none;";
				            		}
				            	?>
				            ">
				            	<span>
		                            CDE
		                        </span>

		                        <div class=" scrollspy-example" style=" height: 200px;">
		                            
		                            <div class="row">
		                                <div class="col-md-12">

		                                    <input type="checkbox" class="form-check-input" id="seleccionPlanteles" checked="checked">
		                                    <label class="form-check-label letraPequena" for="seleccionPlanteles" style="font-size: 10px;">
		                                        Todo
		                                    </label>
		                                    
		                                </div>
		                            </div>

		                            <?php


		                            	if ( $tipo == 'Super' ) {
		                            		
		                            		$sqlPlantel = "
			                                    SELECT *
			                                    FROM plantel
			                                    WHERE id_cad1 = '$cadena'
			                                    ORDER BY nom_pla DESC
			                                ";
		                            	
		                            	} else {

		                            		$sqlPlantel = "
			                                    SELECT *
			                                    FROM plantel
			                                    WHERE id_pla = '$plantel'
			                                    ORDER BY nom_pla DESC
			                                ";

		                            	}
		                                

		                                // echo $sqlPlantel;

		                                $resultadoPlantel = mysqli_query( $db, $sqlPlantel );
		                                $resultadoTotalPlantel = mysqli_query( $db, $sqlPlantel );

		                                $contadorPlantel = 1;

		                                $totalPlantel = mysqli_num_rows( $resultadoTotalPlantel );

		                                        for ( $i = 0 ; $i < $totalPlantel / 1 ; $i++ ) {
		                            ?>
		                                          <div class="row">
		                            <?php  
		                                              while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
		                            ?>
		                                                  <div class="col-md-12">
		                                                    
		                                                        <input type="checkbox" class="form-check-input checkboxPlanteles" id="plantel<?php echo $contadorPlantel; ?>" value="<?php echo $filaPlantel['id_pla']; ?>" checked="checked">
		                                                        <label class="form-check-label letraPequena" for="plantel<?php echo $contadorPlantel; ?>" style="font-size: 10px;">

		                                                            <?php echo $filaPlantel['nom_pla']; ?>

		                                                        </label>

		                                                  </div>
		                            <?php
		                                                $contadorPlantel++;
		                                              }
		                            ?>
		                                            
		                                          </div>

		                            <?php
		                                        }
		                                      // FIN for
		                            ?>

		                        </div>
				            </div>
	                        
	                        <!-- FIN PLANTELES -->

	                        <hr>
	                      	  
	                        <!-- PROGRAMAS -->
	                        <span>
	                            Programas
	                        </span>

	                        <div id="contenedor_programas_plantel">
	                            
	                        </div>
	                        <!-- FIN PROGRAMAS -->

	                        <hr>

	                        <!-- GENERACIONES -->
	                        <span>
	                            Grupos
	                        </span>

	                        <div  id="contenedor_generaciones">
	                            
	                            
	                            
	                        </div>

	                        <!-- FIN GENERACIONES -->
							<!--  -->
						</div>
						<!-- FIN FILTROS ACADEMICOS -->
			            

					</div>
					
				
				</div>
				<!-- FIN CARD -->
			</div>
			

		</div>
		<!-- FIN COL FILTROS -->

		<div class="col-md-9" id="contenedor_principal">

			<div class="card z-depth-1 <?php echo $estilos_modo['card']; ?>"  style="border-radius: 20px;">
				
				<div class="card-body">


					<form id="formulario_alumno">
						<div class="row p-2">
							<div class="col-md-12">

								<!-- Material input -->
								<div class="md-form">
									
									<i class="fas fa-search prefix fa-sm"></i>
									<input type="text" id="palabra" class="form-control letraGrande" autocomplete="off">
									<label for="palabra">Buscar...</label>
								
								</div>

							</div>


							<div class="col-md-4" style="display: none;">
								
								<!-- FECHAS -->
								<div class="md-form">
					            	
					            	<div class="row">
					            		
					            	

					            		<div class="col-md-12">
					            			<span class="letraPequena">
												Inicio del rango
											</span>
											<br>
					            			<input type="date" id="inicio" class="date-range-filter form-control validate grey-text" title="Inicio del Rango" style="font-size:10px;">
					            		
					            		</div>
					            	
					            	</div>


					            </div>
					            <!-- FIN FECHAS -->
							</div>

							<div class="col-md-4" style="display: none;">
								
								<!-- FECHAS -->
								<div class="md-form">
					            	
				

					            	<div class="row">
					            		

					            		<div class="col-md-12">
					            			<span class="letraPequena">
												Fin del rango
											</span>

											<br>
					            			<input type="date" id="fin" class="date-range-filter form-control validate grey-text" title="Fin del Rango" style="font-size:10px;">
					            		</div>
					            	
					            	</div>

					            </div>
					            <!-- FIN FECHAS -->
							</div>
						
						</div>

					</form>

					<hr>

					<div class="row">
						<div class="col-md-12">
							
							<a href="#" class="btn-link black-text waves-effect" id="btn_generacion">
								<h5>
									<i class="fas fa-plus"></i> Crear nuevo grupo
								</h5>
							</a>
							
							
						</div>
					</div>

					<hr>

					<div id="contenedor_visualizacion">
						
					</div>



					<div id="contenedor_visualizacion2">

						<div id="contenedor_visualizacion4">
							
						</div>


						<div class="row">

							<div class="col-md-4">
								

								<div id="contenedor_select">
							
								</div>

								

								
							</div>

							<div class="col-md-5">
								<div id="contenedor_paginacion">
							
								</div>
							</div>

					
							<div class="col-md-3">
								

								

								<div id="contenedor_info">
							
								</div>
							</div>
							
						</div>


						<!-- BOTONES ACCION SELECCION -->		            
			            <div class="row" id="contenedor_botones_accion">
				          	  
				        </div>
			            <!-- FIN BOTONES ACCION SELECCION -->

			            <div id="contenedor_visualizacion3">
			            	
			            </div>
						
					</div>



					<div id="contenedor_visualizacion5">
						<div id="contenedor_visualizacion6">
							
						</div>

						<div id="contenedor_visualizacion7">
							
						</div>
					</div>

				</div>
			
			</div>

		</div>

	</div>
	<!-- FIN BUSCADOR Y FECHAS -->




	<!-- FORMULARIO EDICION ALUMNOS -->
	<style>
	  #formulario_editar_alumno input{
	    font-size: 12px;

	    color: #4B515D;
	  }

	  #formulario_editar_alumno label{
	    font-size: 12px;
	  }
	</style>

	<!-- MODAL EDICION ALUMNO -->
    <div class="modal fade text-left " id="modal_editar_alumno">
        
        <div class="modal-dialog modal-lg" role="document">

          <div class="modal-content " style="border-radius: 20px;">
            
            <div class="modal-header " style="position: relative;">
              
              <div class="row">
                
                <div class="col-md-12">
                
                  	<span class="grey-text letraGrande">  
                    	Solicitud de inscripción
                  	</span>

                </div>

              </div>
            
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
                <span aria-hidden="true">&times;</span>
              </button>

            </div>
            
            <form id="formulario_editar_alumno">
              
              <div class="modal-body mx-3">

                <!-- DATOS GENERALES -->
                <div style="background: grey; height: 30px;">
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Datos Generales
                  </span>
                </div>


                <!-- DATOS PERSONALES -->
                <div class="row">
                  
                  <!-- FOTO -->
                  <div class="text-center col-md-3">

                    <br>

                    <img src="../img/usuario.jpg" alt="avatar" class="rounded-circle img-fluid" style="border-style: solid; width: 105px; height: 105px;" id="contenedor_imagen_edicion2">
                    
                    <div class="md-form">
                      <div class="file-field">
                        

                        <div class="file-path-wrapper"> 
                          <input class="file-path  letraPequena disabled" type="text" style="font-size: 8px;" placeholder="Sube un archivo en JPG, JPEG o PNG" id="fotoText2"> 
                        </div>

                        <br>

                        <div class="btn btn-info btn-sm float-left btn-block btn-rounded waves-effect">
                          <span>Elige un archivo</span>
                          <input type="file" id="foto2" name="foto2"> 
                        </div>
                        
                      </div>
                    </div>

                  
                  </div>
                  <!-- FIN FOTO -->


                  <!-- DATOS PERSONALES -->
                  <div class="col-md-9">

                    <div class="row">
                      
                      <div class="col-md-6">
                        
                        <p class="grey-text letraPequena">
                          ¡Todos los campos con * son obligatorios! 
                        </p>
                      
                      </div>

                    </div>


                    
                    
                    
                    <!-- NOMBRE -->
                    <div class="row">

                      <div class="col-md-4">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="nombre" name="nombre" class="form-control correoCompuesto" required="">
                          <label for="nombre">*Nombre</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="apellido1" name="apellido1" class="form-control correoCompuesto" required="">
                          <label for="apellido1">*Apellído paterno</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="apellido2" name="apellido2" class="form-control" required="">
                          <label  for="apellido2">*Apellído materno</label>
                        
                        </div>

                      </div>

                    </div>
                    <!-- FIN NOMBRE -->

                    <!-- FECHA DE NACIMIENTO Y GENERO -->
                    <div class="row">
                      
                      <div class="col-md-6" style="position: relative;">

                        <label style="position: absolute; top: 10px;" class="grey-text">Fecha de Nacimiento</label>
                        <div class="md-form mb-5">
                          <input type="date" id="nacimiento" name="nacimiento" class="form-control " required>
                        </div>
                        
                      </div>


                      <div class="col-md-6">
                        <br>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input generoAlumno2" id="materialInline12" name="generoAlumno" value="Mujer">
                            <label class="form-check-label" for="materialInline12">Mujer</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input generoAlumno2" id="materialInline22" name="generoAlumno" value="Hombre">
                            <label class="form-check-label" for="materialInline22">Hombre</label>
                        </div>

                      </div>

                    </div>
                    <!-- FIN FECHA DE NACIMIENTO Y GENERO -->

                    <!-- CURP Y LUGAR DE NACIMIENTO -->
                    <div class="row">
                      
                      <div class="col-md-4" style="display: none;">
                        
                        <div class="md-form mb-5">
                            
                          <input type="text" id="lug_alu" name="lug_alu" class="form-control" value="Pendiente">
                          <label  for="lug_alu">Lugar de nacimiento</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        
                        <div class="md-form">

                          <input type="text" id="telefono" name="telefono" class="form-control " required="">
                          <label  for="telefono">*Teléfono</label>
                        
                        </div>

                      </div>

                      <div class="col-md-8">
                        
                        <div class="row">
                        
                          <div class="col-md-12" style="position: relative;">
                        
                            <div class="md-form mb-5">
                            
                              <input type="text" id="curp" name="curp" class="form-control ">
                              <label  for="curp">CURP</label>
                            
                            </div>

                            <span id="validacion_curp_frontend" class="grey-text letraPequena" style="position: absolute; bottom: 30px;">
                              
                            </span>
                          </div>

                        
                        </div>
                      
                      </div>
                      
                    
                    </div>
                    <!-- FIN CURP Y LUGAR DE NACIMIENTO  -->

                    <!-- TELEFONOS -->

                    <div class="row" style="display: none;">
                      
                      

                      <!-- CONTACTO TUTOR -->
                      <div class="col-md-6">
                        <div class="md-form">
                          <input type="text" id="tel2_alu" name="tel2_alu" class="form-control" value="PENDIENTE">
                          <label for="tel2_alu">Celular</label>
                        </div>
                      </div>
                      <!-- FIN CONTACTO TUTOR -->



                    </div>
                    <!-- FIN TELEFONOS -->
                   
                    <!-- CORREO ORIGINAL -->
                    <div class="row">

                      


                      <div class="col-md-8">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="correo1_alumno" name="correo1_alumno" class="form-control " required="">
                          <label  for="correo1_alumno">*Correo electrónico</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        <br>
                        <a class="btn btn-info waves-effect btn-sm btn-rounded" title="Enviar un correo de prueba al correo electrónico que ingresaste" id="btn_correo_demo2" href="#">
                        	Enviar correo prueba
                        </a>

                      </div>

                    </div>

                    <!-- FIN CORREO ORIGINAL -->

                    <br>
                    <!-- CUENTA -->

                    <div class="row">

                      
                      <div class="col-md-4" style="position: relative;">
                          
                          <div style="position: absolute; top: -25px; ">

                            <input type="checkbox" class="form-check-input">
                            <label class="letraPequena font-weight-normal active grey-text" for="checkboxMatriculaCompuesta2" title="Matrícula Compuesta (Formato: mmyy000000)">
                              Matrícula Compuesta
                            </label>
                            
                          </div>
                          

                          <div class="md-form mb-5" id="matriculaAlumno2">

                            <input type="text" id="boleta" name="boleta" class="form-control  " value="">
                            <label  for="boleta" class="">*Matrícula</label>
                          </div>
                        
                      </div>


                      <div class="col-md-5" style="position: relative;">
                
                        <label id="output" style="position: absolute; top: -30px; "></label>
                            
                        <div class="md-form mb-5" id="contenedor_correo">
                          <input type="text" id="correoEdicion" class="form-control" name="correoEdicion">
                          <label for="correoEdicion">*Cuenta de acceso</label>
                        </div>

                      </div>



                      <div class="col-md-3">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="password" name="password" class="form-control " required="">
                          <label for="password" id="label_pas_alu2">*Contraseña</label>
                        
                        </div>

                      </div>


                      

                    </div>
                    <!-- FIN CUENTA -->
                  
                  </div>
                
                </div>
                <!-- FIN DATOS PERSONALES -->

                <!-- FIN DATOS GENERALES -->
                
                <hr>

          
                <!-- ACADEMICO -->
                <div style="background: grey; height: 30px;">
                  
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Programa académico
                  </span>

                </div>

                <div class="row">

                  <!-- DATOS PROGRAMA -->
                  <div class="col-md-4">

                    <span>
                      Programa académico
                    </span>
                    
                    <div class="scrollspy-example" style=" height: 200px;">

                      <?php  
                        $sqlProgramas = "
                          SELECT *
                          FROM rama
                          WHERE id_pla1 = '$plantel' AND est_ram = 'Activo'
                          ORDER BY id_ram ASC
                        ";

                        $resultadoProgramas = mysqli_query( $db, $sqlProgramas );


                        $contadorProgramas = 1;

                   
                          while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                        ?>


                        		<div class="form-check">
	                                <input type="radio" class="form-check-input programas_edicion_alumno" id="programas_edicion_alumno<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram[]">
	                                <label class="form-check-label letraPequena font-weight-normal" for="programas_edicion_alumno<?php echo $contadorProgramas; ?>">
	                                
	                                    <?php echo $filaProgramas['nom_ram']; ?>

	                                </label>
	                        
	                            </div>
                          

                      <?php
                          $contadorProgramas++;
                        }
                        // FIN while
                      ?>
                    </div>
                    
                  </div>
                  <!-- FIN DATOS PROGRAMA -->


                  <!-- DATOS GENERACION -->
                  <div class="col-md-8">

                      Grupos
                      <div class="text-left scrollspy-example" style=" height: 200px;" id="contenedor_generaciones_modal2">
                        
                      </div>
                    

                  </div>
                  <!-- DATOS ALUMNO FIN -->
                  

                </div>
                <!-- FIN ACADEMICO -->


                <hr style="display: none;">


                <!-- PAGOS -->
                <div style="background: grey; height: 30px;">
                  
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Calendario de inversiones  
                  </span>

                </div>


                <div id="contenedor_pagos_generacion2">
                  
                </div>
                <!-- FIN PAGOS -->


                <!-- IDENTIFICADORES -->
	            <div style="display: none;">                
                	<div class="md-form mb-5">
			            <input type="hidden" id="identificador" name="identificador" class="form-control validate">
			        </div>


			        <div class="md-form mb-5">
			            <input type="hidden" id="identificadorAlumnoRama" name="identificadorAlumnoRama" class="form-control validate">
			            <input type="hidden" class="form-control validate">
			        </div>

			        <div class="md-form mb-5">
			            <input type="hidden" id="identificadorRama" name="identificadorRama" class="form-control validate">
			            <input type="hidden" class="form-control validate">
			        </div>

                </div>
	            <!-- FIN IDENTIFICADORES -->



                <div class="modal-footer d-flex justify-content-center">
                  
                  <button class="btn btn-info btn-rounded waves-effect btn-sm" title="Agregar alumno..." type="submit" id="btn_guardar_alumno_edicion">
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

    </div>

	<!-- FIN MODAL EDICION ALUMNO -->



	<!-- MODAL MENSAJES -->

	<div class="modal fade" id="modal_mensaje_alumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h4 class="modal-title w-100">Envío de mensaje</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      	<div class="modal-body">

	      		<div class="row">
					<div class="col-md-6">
						<div class="form-check form-check-inline">
							<input type="radio" class="form-check-input radiosSeleccionEnvio" id="radioMensaje" name="radiosSeleccionEnvio" value="Mensaje" checked="">
							<label class="form-check-label letraPequena" for="radioMensaje">Mensaje</label>
						</div>
					</div>

					<!-- <div class="col-md-6">
						<div class="form-check form-check-inline">
							<input type="radio" class="form-check-input radiosSeleccionEnvio" id="radioArchivo" name="radiosSeleccionEnvio" value="Archivo" checked="">
							<label class="form-check-label letraPequena" for="radioArchivo">Archivo</label>
						</div>
					</div> -->
				</div>
	      	
	      	</div>
	      	


			<div id="contenedor_principal_mensaje_alumnos">
				
			</div>

	    </div>
	  </div>
	</div>
	<!-- Central Modal Small -->
	<!-- FIN BAJA DE MATERIAS MODAL -->


	<!-- FIN MODAL MENSAJES -->


	



	<!-- MODAL CAMBIO GEN -->

	<div class="modal fade" id="modal_cambio_generacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h4 class="modal-title w-100">
	        	Cambio de grupo
	        </h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>


	      	<div id="contenedor_cambio_generacion" class="modal-body">
	      		
	      	</div>

	      		
	    </div>
	  </div>
	</div>


	<!-- FIN MODAL CAMBIO GENERACION -->



	<!-- MODAL AGREGAR PROG -->

	<div class="modal fade" id="modal_agregar_programa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document" >


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h4 class="modal-title w-100">
	        	Agregar nuevo programa
	        </h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>


	      	<div id="contenedor_agregar_programa" class="modal-body">
	      		
	      	</div>

	      		
	    </div>
	  </div>
	</div>


	<!-- FIN MODAL AGREGAR PROG -->




	<!-- INSCRIPCION Y BAJA-->

	




	<!-- BAJA DE MATERIAS MULTIPLE MODAL -->

	<!-- Central Modal Small -->
	<div class="modal fade" id="modalBaja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">

	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h4 class="modal-title w-100">Baja Múltiple de Materias</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body" id="panzaModalBaja">
	        

	        


	      </div>
	      <div class="modal-footer" id="footerModalBaja">
	        
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Central Modal Small -->
	<!-- FIN BAJA DE MATERIAS MODAL -->

	<!-- FIN INSCRIPCION Y BAJA -->


	<!-- CONSULTA ALUMNO MODAL -->
	<div class="modal fade" id="modalConsultaAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h6 class="modal-title w-100" id="tituloConsultaAlumno">
	        </h6>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      <div class="modal-body bg-light" id="contenedorConsultaAlumno">
	        
	        

	      </div>
	      <div class="modal-footer bg-light">
	        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- FIN CONSULTA ALUMNO MODAL -->




	<!-- CALIFICACIONES ALUMNO MODAL -->
	<div class="modal fade" id="modal_calificaciones_generacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h6 class="modal-title w-100">
	        	Calificaciones grupales
	        </h6>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      <div class="modal-body bg-light" id="contenedor_calificaciones_generacion">
	        
	        

	      </div>
	      <div class="modal-footer bg-light text-center">
	        <button type="button" class="btn grey white-text btn-sm btn-rounded" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- FIN CALIFICACIONES ALUMNO MODAL -->


	
	<!-- HORARIO ALUMNO MODAL -->

	<!-- Central Modal Small -->
	<div class="modal fade" id="modalHorarioAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h4 class="modal-title w-100" id="tituloModalHorarioAlumno"></h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      <div class="modal-body" id="panzaModalHorarioAlumno">
	        
	        


	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Central Modal Small -->

	<!-- FIN HORARIO ALUMNO MODAL -->



<?php  

	include('inc/footer.php');

?>



<script>
    
    $("#seleccionPlanteles").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

        //console.log( $(this)[0].checked );

        if ( $(this)[0].checked == true ) {
          // console.log("checkeado");
            $('.checkboxPlanteles').prop({checked: true});
            obtenerProgramas();
          
        }else{ 
          
            $('.checkboxPlanteles').prop({checked: false});
            obtenerProgramas();

        }

    //$('.seleccionAnniosMeses').prop({checked: false});
    });





    function obtenerProgramas() {

        var id_pla = [];
        for ( var i = 0, j = 0 ; i < $(".checkboxPlanteles").length ; i++ ) {

            if ( $(".checkboxPlanteles")[i].checked == true ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxPlanteles').eq(i).attr("annio")+" esta seleccionado"  );

                id_pla[j] = $('.checkboxPlanteles').eq(i).val();

                j++;

            }
        }

        if ( id_pla.length == 0 ) {

            swal("¡No hay planteles seleccionados!", "Selecciona al menos uno para continuar", "info", {button: "Aceptar",});
            
            // $("#contenedor_generaciones").html("");
            // $("#contenedor_principal").html("");

        } else {
            
            $.ajax({
                url: 'server/obtener_programas_plantel.php',
                type: 'POST',
                data: { id_pla },
                success: function( respuesta ){

                    $("#contenedor_programas_plantel").html( respuesta );

                    // 
                    // 
                              
                }

            });

        }

        
    }


    obtenerProgramas();


    $('.checkboxPlanteles').on('click', function() {
        //event.preventDefault();
        /* Act on the event */
        obtenerProgramas();
        

    });


</script>


<script>
	$('#btn_generacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('#modal_generacion').modal('show');
		
		obtener_clave_generacion();
		
		setTimeout(function(){
			
			$('#nom_gen').focus();

		}, 500 );
		
	});



	$('.programasGeneracion').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_clave_generacion();
	
	});


	
	function obtener_clave_generacion(){
		
		var id_ram = $('input[name="id_ram"]:checked' ).val();

		$.ajax({
			url: 'server/obtener_clave_generacion.php',
			type: 'POST',
			data: { id_ram },
			success: function( respuesta ){

				

				setTimeout(function(){

					var fecha1 = moment( $('#ini_gen').val() );
					var fecha2 = moment( $('#fin_gen').val() );

					var dias = fecha2.diff( fecha1, 'days');

					var meses = Math.round( dias/30 );

					if ( meses < 0 ) {
						meses = 'N/A';
					} else {
						meses = meses+' meses';
					}

					// $('#nom_gen').focus();
					$('#modal_generacion_titulo').text( respuesta+' ( '+meses+' )' );
					$('#nom_gen').val( respuesta );


				
				}, 200 );

			}
		});
		


	}
</script>


<script>
	$('#modal_generacion_formulario').on('submit', function(event) {
		event.preventDefault();
		/* Act on the event */
		$("#btn_submit_generacion").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando...');

		
		var modal_generacion_formulario = new FormData( $('#modal_generacion_formulario')[0] );

		if ( $( '#checkbox_pagos' )[0].checked == true ) {
			// alert( 'checkeada' );
			var checkbox_pagos = 'Activo';
		
		} else {
			// alert( 'no checkeada' );
			var checkbox_pagos = 'Inactivo';
		
		}

		modal_generacion_formulario.append( 'checkbox_pagos', checkbox_pagos );
		
		$.ajax({
		
			url: 'server/agregar_generacion.php',
			type: 'POST',
			data: modal_generacion_formulario, 
			processData: false,
			contentType: false,
			cache: false,
			success: function(respuesta){
				
				console.log(respuesta);

				swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
				then((value) => {

					$("#btn_submit_generacion").removeAttr( 'disabled' ).html( 'Guardar');

					$('#modal_generacion').modal('hide');
					// obtenerAlumnosGeneraciones();
					obtenerGeneraciones();
				});

				var id_gen = respuesta;

				if ( isNaN( id_gen ) ) {

					$('#checkbox_generacion_pagos').prop({checked: false}).val( 0 );
					
				} else {

					$('#checkbox_generacion_pagos').prop({checked: true}).val( id_gen );

				}

				

			}
		});

	});
</script>


<script>
	$('#formulario_baja_alumno').on('submit', function() {
        event.preventDefault();
        /* Act on the event */


        $("#btn_baja_alumno").attr('disabled','disabled');

        $.ajax({
                          
            url: 'server/agregar_baja_alumno.php',
            type: 'POST',
            data: new FormData( formulario_baja_alumno ),
            processData: false,
            contentType: false,
            cache: false,
            success: function( respuesta ){
                console.log(respuesta);

                
                $("#btn_baja_alumno").removeAttr('disabled');

                swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {
                  //window.location.reload();
                    // obtenerAlumnosGeneraciones();
                    // reloadTableGeneral();

                    $('#modal_baja_alumno').modal('hide');
                    $('#mot_ing_alu_ram').val("");

                    obtener_bajas_reingresos_alumno();

                });
              
            }
        });

    });
</script>


<script>


	$('.radioPeriodo').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		console.log('radioPeriodo');

		obtenerAlumnosFechas();
		// alert( 'radioReporte' );

	});

	
    $('.filtros').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtenerAlumnosFechas();
		// alert( radioReporte );

	});

    // BUSCADOR
    $('#formulario_alumno').on('submit', function() {
        event.preventDefault();
        /* Act on the event */

        obtenerAlumnosBuscador();
        // alert('hi');
        
    });
    // FIN BUSCADOR


    $('#palabra').on('keyup', function(event) {
        event.preventDefault();
        /* Act on the event */

        var valor = $('#palabra').val();

        if ( valor == '' ) {

            obtenerAlumnosFechas();
        
        }



    });

	obtenerAlumnosFechas();
	function obtenerAlumnosFechas() {
        

        var palabra = '';
        
        // FECHAS
        var radioPeriodo = $(".radioPeriodo:checked").val();

		// FECHAS
		if ( radioPeriodo == 'Fecha' ) {

			var inicio = $('#inicio').val();
			var fin = $('#fin').val();

			$('#contenedor_fecha').css('display', '');
			$('#contenedor_semana').css( 'display', 'none' );
			$('#contenedor_mes_annio').css( 'display', 'none' );

	
		} else if ( radioPeriodo == 'Semana' ) {

			var inicio = $('#selectorSemana option:selected').attr('inicio');
			var fin = $('#selectorSemana option:selected').attr('fin');

			$('#contenedor_mes_annio').css( 'display', 'none' );
			$('#contenedor_fecha').css('display', 'none');
			$('#contenedor_semana').css( 'display', '' );


		} else if ( radioPeriodo == 'Mes' ) {


			$('#contenedor_mes_annio').css( 'display', '' );
			$('#contenedor_fecha').css('display', 'none');
			$('#contenedor_semana').css( 'display', 'none' );

			var diaInicio = $('#selectorMes option:selected').attr('inicio');
			var diaFin = $('#selectorMes option:selected').attr('fin');
			var mes = $('#selectorMes option:selected').val();
			var annio = $('#selectorAnnio option:selected').val();
			
			var inicio = annio+'-'+mes+'-'+diaInicio;
			var fin = annio+'-'+mes+'-'+diaFin;


		}
        // FIN FECHAS

        
        
        var tipo_estatus = [];
        var estatus = [];

        

        var id_gen = '';
        var total_alumnos = '';
        var pageLength = -1;
       

        $('#contenedor_visualizacion5').css( 'display', 'none' );
        $('#contenedor_visualizacion2').css( 'display', '' );
        $('#contenedor_visualizacion').css( 'display', 'none' );

        $('#contenedor_visualizacion4').html('');
        obtener_tabla_alumnos2( id_gen, palabra, inicio, fin, estatus, tipo_estatus, pageLength );

        
    }
</script>


<script>
	function obtenerAlumnosBuscador() {
        
        var palabra = $('#palabra').val();
        

        // FECHAS
        var radioPeriodo = $(".radioPeriodo:checked").val();

        var inicio = '';
        var fin = '';

        radiosVisualizacion = $(".radiosVisualizacion:checked").val();
   
        
        var tipo_estatus = [];
        var estatus = [];

        for ( var i = 0, j = 0 ; i < $(".seleccionEstatus").length ; i++ ) {

            if ( $(".seleccionEstatus").eq(i).attr('switch') == 'verdadero' ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

                tipo_estatus[j] = $('.seleccionEstatus').eq(i).attr('tipo_estatus');
                estatus[j] = $('.seleccionEstatus').eq(i).attr('estatus');

                j++;

            }
        }


        var id_gen = [];
        var total_alumnos = [];

        for ( var i = 0, j = 0 ; i < $(".checkboxGeneraciones").length ; i++ ) {

            if ( $(".checkboxGeneraciones")[i].checked == true ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

                id_gen[j] = $('.checkboxGeneraciones').eq(i).val();
                total_alumnos[j] = $('.checkboxGeneraciones').eq(i).attr('total_alumnos');

                j++;

            }
        }



        if ( 
            ( ( palabra != '' ) && ( id_gen.length == 0 ) ) || 
            ( id_gen.length > 0 ) ||  
            ( ( palabra != '' ) && ( id_gen.length > 0 ) )
        ){

            if ( palabra != '' ) {
                
                var pageLength = -1;

            } else {
               
                if ( id_gen.length == 1 ) {

                    var pageLength = total_alumnos[0];
                    
                } else {

                    var pageLength = 10;
                
                }
            }

            
            if ( radiosVisualizacion == 'Generaciones' ) {
                
                $('#contenedor_visualizacion5').css( 'display', 'none' );
                $('#contenedor_visualizacion2').css( 'display', 'none' );
                $('#contenedor_visualizacion').css( 'display', '' );
                
                obtenerListadoGeneraciones( id_gen, palabra, inicio, fin );

                function obtenerListadoGeneraciones( id_gen, palabra, inicio, fin ){

                    // $('#contenedor_select').html('');
                    // $('#contenedor_paginacion').html('');
                    // $('#contenedor_info').html('');
                    
                    $.ajax({
                        url: 'server/obtener_listado_generaciones.php',
                        type: 'POST',
                        data: { id_gen, palabra, inicio, fin },
                        success: function( respuesta ){

                            // console.log( respuesta );
                            $('#contenedor_visualizacion').html( respuesta );

                        }
                        
                    });
                }

            } else if ( radiosVisualizacion == 'Alumnos' ) {

                $('#contenedor_visualizacion5').css( 'display', 'none' );
                $('#contenedor_visualizacion2').css( 'display', '' );
                $('#contenedor_visualizacion').css( 'display', 'none' );

                $('#contenedor_visualizacion4').html('');
                obtener_tabla_alumnos( id_gen, palabra, inicio, fin, estatus, tipo_estatus, pageLength );



            // FINAL else if 'Alumnos'
            }
                        
        } else if ( id_gen.length == 0 ) {

            // swal("¡No hay generaciones seleccionadas!", "Selecciona al menos una para continuar", "info", {button: "Aceptar",});
            
            // $("#contenedor_principal").html("");
        }

        
    }
</script>


<script>
	obtener_contenedor_filtros();
	$(".radiosVisualizacion2").on('change', function() {
        // event.preventDefault();
        // alert('cam');
        obtener_contenedor_filtros();
        

    });


    function obtener_contenedor_filtros(){

    	// console.log('contenedor_filtros');
    	var radiosVisualizacion2 = $(".radiosVisualizacion2:checked").val();

        if ( radiosVisualizacion2 == 'Fechas' ) {
        	$('#contenedor_filtros_fechas').css('display', '');
        	$('#contenedor_filtros_academicos').css('display', 'none');
        	obtenerAlumnosFechas();
        } else if ( radiosVisualizacion2 == 'Académicos' ) {
        	$('#contenedor_filtros_fechas').css('display', 'none');
        	$('#contenedor_filtros_academicos').css('display', '');
        
        }
    }
</script>


<script>
    function reloadTableGeneral(){
        
        $('#tabla_alumnos').DataTable().ajax.reload();

    }
</script>

<script>

	obtener_filtros_alumnos();
	function obtener_filtros_alumnos(){
		var radiosVisualizacion = $(".radiosVisualizacion:checked").val();
        // alert( radiosVisualizacion );


        obtener_contenedor_filtros();
        if ( radiosVisualizacion == 'Alumnos' ) {
            
            $('#radioAlumnos2').prop({checked: true});
            $('#contenedor_radios_fechas').css('display', 'none');
    		$('#contenedor_radios_academicos').css('display', '');
        
        } else if ( radiosVisualizacion == 'Generaciones' ) {

        	
			$('#radioGeneraciones2').prop({checked: true});
        	$('#contenedor_radios_fechas').css('display', '');
        	$('#contenedor_radios_academicos').css('display', 'none');
        	
        }

	}
	$(".radiosVisualizacion").on('change', function() {
        // event.preventDefault();
        // alert('cam');

        obtener_filtros_alumnos();

        // obtenerAlumnosGeneraciones();

    });
</script>

<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - Alumnos');
</script>