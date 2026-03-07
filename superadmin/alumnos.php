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

	</style>


	<!-- MODAL PAGOS -->
	<div class="modal fade text-left" id="modal_generacion_pagos">
	  	
	  	<div class="modal-dialog modal-lg" role="document">
	    
			<form id="formulario_generacion_pagos">
			    
			    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
			      	
			      	<div class="modal-header text-center" style="position: relative;">

			      		<span class="text-center"  style="position: absolute; right: 47%; bottom: 1%;">
			                <?php
			                  echo "CDE: ".$direccionPlantel."<br>";
			                  echo obtenerSemana( date('Y-m-d') );
			                ?>
			            </span>
			            <div class="row">
			                <div class="col-md-12">
			                  
			                  
				                <span class="grey-text letraMediana">
				                    <?php echo $nombrePlantel; ?>
				                </span>

				                <br>  
				                <span class="grey-text letraGrande">  
				                    <i class="fas fa-money-check-alt fa-1x"></i>

				        			Calendario de pagos:
				        			<span id="titulo_generacion_pagos"></span>
				                </span>


			                </div>


			            </div>
			              
		
			            <img src="../uploads/<?php echo $fotoPlantel; ?>" style="width: 55px; height: 55px; position: absolute; right: 35px; top: 20px;">  

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
							Tipo de visualización
						</span>


						<div class="row">
							<div class="col-md-6">
								<div class="form-check form-check-inline">
									<input type="radio" class="form-check-input radiosVisualizacion" id="radioGeneraciones" name="radiosVisualizacion" value="Generaciones" checked="">
									<label class="form-check-label letraPequena" for="radioGeneraciones">Grupos</label>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-check form-check-inline">
									<input type="radio" class="form-check-input radiosVisualizacion" id="radioAlumnos" name="radiosVisualizacion" value="Alumnos" >
									<label class="form-check-label letraPequena" for="radioAlumnos">Alumnos</label>
								</div>
							</div>
						</div>


						<hr>


						<!-- PLANTELES -->
			            <span>
							CDE
						</span>

		            	<div class=" scrollspy-example" style=" height: 200px;">
							
							<div class="row">
								<div class="col-md-12">

									<input type="checkbox" class="form-check-input" id="seleccionPlantel" checked="checked">
									<label class="form-check-label letraPequena" for="seleccionPlantel" style="font-size: 10px;">
										Todo
									</label>
									
								</div>
							</div>


							<?php
								$sqlPlantel = "
									SELECT *
									FROM plantel
									WHERE id_cad1 = '$cadena'
									ORDER BY nom_pla DESC
								";

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
								                    
														<input type="checkbox" class="form-check-input checkboxPlantel" id="plantel<?php echo $contadorPlantel; ?>" value="<?php echo $filaPlantel['id_pla']; ?>" checked="checked">
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
						<!-- FIN PLANTELES -->

			            <hr>
						
			            <!-- PROGRAMAS -->
			            <span>
							Programas
						</span>

		            	<div class=" scrollspy-example" style=" height: 200px;">
							
							<div class="row">
								<div class="col-md-12">

									<input type="checkbox" class="form-check-input" id="seleccionProgramas" checked="checked">
									<label class="form-check-label letraPequena" for="seleccionProgramas" style="font-size: 10px;">
										Todo
									</label>
									
								</div>
							</div>


							<?php
								$sqlProgramas = "
									SELECT *
									FROM rama
									WHERE id_pla1 = '$plantel'
									ORDER BY id_ram DESC
								";

								$resultadoProgramas = mysqli_query( $db, $sqlProgramas );
								$resultadoTotalProgramas = mysqli_query( $db, $sqlProgramas );

								$contadorProgramas = 1;

								$totalProgramas = mysqli_num_rows( $resultadoTotalProgramas );

						                for ( $i = 0 ; $i < $totalProgramas / 1 ; $i++ ) {
						    ?>
						                  <div class="row">
						    <?php  
						                      while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
						    ?>
								                  <div class="col-md-12">
								                    
														<input type="checkbox" class="form-check-input checkboxProgramas" id="programa<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" checked="checked">
														<label class="form-check-label letraPequena" for="programa<?php echo $contadorProgramas; ?>" style="font-size: 10px;">

															<?php echo $filaProgramas['nom_ram']; ?>

					                              		</label>

								                  </div>
						    <?php
						                        $contadorProgramas++;
						                      }
						    ?>
						                    
						                  </div>

						    <?php
						                }
						              // FIN for
						    ?>

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
	<!-- MODAL ALUMNO -->
	<div class="modal fade text-left " id="modal_editar_alumno">
	    
	    <div class="modal-dialog modal-lg" role="document">

	      <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
	        
	        <div class="modal-header ">

	          <div class="row">
	            <div class="col-md-3">
	              <img src="../uploads/<?php echo $fotoPlantel; ?>" style="width: 65px; height: 65px;">
	              
	            </div>

	            <div class="col-md-9">
	              <h4 class="modal-title w-100">
	                Edición alumno
	              </h4>
	            </div>

	          </div>
	          
	          

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
	            <span aria-hidden="true">&times;</span>
	          </button>

	        </div>
	        
	        <form id="formulario_editar_alumno">
	          
	          <div class="modal-body mx-3">

	            <!-- DATOS PERSONALES -->
	            <div class="row">
	              
	              <!-- FOTO -->
	              <div class="text-center col-md-4">

	                <br>

	                <img src="../img/usuario.jpg" alt="avatar" class="rounded-circle img-fluid" style="border-style: solid; width: 105px; height: 105px;" id="contenedor_imagen_edicion">
	                
	                <div class="md-form" >
	                  <div class="file-field">
	                    

	                    <div class="file-path-wrapper"> 
	                      <input class="file-path  letraPequena disabled" type="text" placeholder="Sube un archivo en JPG, JPEG o PNG" id="fotoText"> 
	                    </div>

	                    <br>

	                    <div class="btn btn-info btn-sm float-left btn-block btn-rounded">
	                      <span>Elige un archivo</span>
	                      <input type="file" id="foto" name="foto"> 
	                    </div>
	                    
	                  </div>
	                </div>
	              
	              </div>
	              <!-- FIN FOTO -->


	              <!-- DATOS PERSONALES -->
	              <div class="col-md-8">

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

	                      <input type="text" id="nombre" name="nombre" class="form-control correoCompuestoEdicion" required="">
	                      <label id="nombre"  for="nombre">*Nombre</label>
	                    
	                    </div>

	                  </div>


	                  <div class="col-md-4">
	                    
	                    <div class="md-form mb-5">

	                      <input type="text" id="apellido1" name="apellido1" class="form-control correoCompuestoEdicion" required="">
	                      <label id="apellido1"  for="apellido1">*Apellído paterno</label>
	                    
	                    </div>

	                  </div>


	                  <div class="col-md-4">
	                    
	                    <div class="md-form mb-5">

	                      <input type="text" id="apellido2" name="apellido2" class="form-control" required="">
	                      <label id="apellido2"  for="apellido2">*Apellído materno</label>
	                    
	                    </div>

	                  </div>

	                </div>
	                <!-- FIN NOMBRE -->

	                <!-- CUENTA -->

	                <div class="row">

	                  
	                  <div class="col-md-4" style="position: relative;">
	                      
	                  
	                      <div class="md-form mb-5" id="matriculaAlumno_edicion">

	                        <input type="text" id="boleta" name="boleta" class="form-control  " value="">
	                        <label  for="boleta" class="">*Matrícula</label>
	                      </div>
	                    
	                  </div>


	                  <div class="col-md-5" style="position: relative;">
	            
	                    <label id="outputEdicion" style="position: absolute; top: -30px; "></label>
	                        
	                    <div class="md-form mb-5" id="contenedor_correo_edicion">
	                      <input type="text" id="correoEdicion" class="form-control" name="correoEdicion">
	                      <label for="correoEdicion">*Cuenta de acceso</label>
	                    </div>

	                  </div>


	                  


	                  <div class="col-md-3">
	                    
	                    <div class="md-form mb-5">

	                      <input type="text" id="password" name="password" class="form-control " required="">
	                      <label for="password" id="label_password">*Contraseña</label>
	                    
	                    </div>

	                  </div>


	                  

	                </div>
	                <!-- FIN CUENTA -->


	                <!-- CORREO ORIGINAL -->
	                <div class="row">

	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">

	                      <input type="text" id="telefono" name="telefono" class="form-control " required="">
	                      <label id="telefono"  for="telefono">*Teléfono</label>
	                    
	                    </div>

	                  </div>


	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">

	                      <input type="text" id="correo1_alumno" name="correo1_alumno" class="form-control " required="">
	                      <label id="correo1_alumno"  for="correo1_alumno">*Correo electrónico</label>
	                    	
	                    </div>

	                  </div>


	                  
	         
	                </div>

	                <!-- FIN CORREO ORIGINAL -->
	              
	              </div>



	            
	            </div>
	            <!-- FIN DATOS PERSONALES -->

	            <hr>

	            <!-- DATOS SECUNDARIOS -->
	            <div class="row" id="contenedor_datos_secundarios_edicion">
	           
	              <div class="col-md-6">
	                
	                <!-- BECAS -->
	                <div class="row">
	                  
	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5" title="Beca de inscripción/reinscripción">      
	                      	<input type="number" id="beca_alu_ram" class="form-control" name="beca_alu_ram" min="0" max="100" step=".1">
	                        <label for="beca_alu_ram">Beca inscr./reins.</label>
	                    </div>
	                  
	                  </div>

	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">      
	                      	<input type="number" id="beca2_alu_ram" class="form-control" name="beca2_alu_ram" min="0" max="100" step=".1">
	                        <label for="beca2_alu_ram">Beca colegiatura</label>
	                    </div>
	                  
	                  </div>
	                
	                </div>
	                <!-- FIN BECAS -->

	                <!-- NACIMIENTO Y GENERO -->
	                <div class="row">
	                  <div class="col-md-6" style="position: relative;">

	                    <label style="position: absolute; top: 10px;" class="grey-text">Fecha de Nacimiento</label>
	                    <div class="md-form mb-5">
	                      <input type="date" id="nacimiento" name="nacimiento" class="form-control " required>
	                    </div>
	                    
	                  </div>

	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">
	                      <input type="text" id="genero" name="genero" class="form-control ">
	                      <label  for="genero">Género</label>
	                    </div>

	                  </div>
	                </div>
	                <!-- FIN NACIMIENTO Y GENERO -->


	                <!-- CURP Y PROCEDENCIA -->
	                <div class="row">
	                  <div class="col-md-6">

	                    <div class="md-form mb-5">
	                      <input type="text" id="curp" name="curp" class="form-control ">
	                      <label  for="curp">CURP</label>
	                    </div>
	                    
	                  </div>

	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5" title="¿De qué escuela procede?">
	                      <input type="text" id="procedencia" name="procedencia" class="form-control ">
	                      <label  for="procedencia">Procedencia</label>
	                    </div>   

	                  </div>
	                </div>
	                <!-- FIN CURP Y PROCEDENCIA -->

	                <!-- VACIAR FORMULARIO -->
	                <a class="btn btn-warning white-text btn-rounded waves-effect btn-sm" title="Vacía todos los campos" id="btn_limpiar_formulario_edicion">
	                  Vacíar formulario
	                </a>
	                <!-- FIN VACIAR FORMULARIO -->

	              </div>

	              <!-- DIRECCION -->
	              <div class="col-md-6">


	                <div class="row">
	                  <!-- DIRECCION -->
	                  <div class="col-md-12">

	                    <div class="md-form mb-5">
	                      <input type="text" id="direccion" name="direccion" class="form-control ">
	                      <label for="direccion">Dirección</label>
	                    </div>
	                    
	                  </div>
	                  <!-- FIN DIRECCION -->
	                </div>
	                
	                <div class="row">
	                  
	                  

	                  <!-- CP -->
	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">
	                      <input type="text" id="codigo" name="codigo" class="form-control">
	                      <label  for="codigo">Código Postal</label>
	                    </div>
	                    
	                  </div>
	                  <!-- FIN CP -->

	                  <!-- COLONIA -->
	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">
	                      <input type="text" id="colonia" name="colonia" class="form-control">
	                      <label for="colonia">Colonia</label>
	                    </div>
	                  
	                  </div>
	                  <!-- COLONIA -->

	                </div>

	                <div class="row">
	                  
	                  <!-- DELEGACION -->
	                  <div class="col-md-6">

	                    <div class="md-form mb-5">
	                      <input type="text" id="delegacion" name="delegacion" class="form-control">
	                      <label  for="delegacion">Delegación</label>
	                    </div>

	                  </div>
	                  <!-- FIN DELEGACION -->

	                  <!-- ENTIDAD -->
	                  <div class="col-md-6">
	                    
	                    <div class="md-form mb-5">
	                      <input type="text" id="entidad" name="entidad" class="form-control">
	                      <label for="entidad">Entidad</label>
	                    </div>

	                  </div>
	                  <!-- FIN ENTIDAD -->

	                </div>


	                <div class="row">
	                  
	                  <!-- TUTOR -->
	                  <div class="col-md-6">
	                    <div class="md-form mb-5">
	                      <input type="text" id="tutor" name="tutor" class="form-control">
	                      <label for="tutor">Tutor</label>
	                    </div>
	                  </div>
	                  <!-- FIN TUTOR -->

	                  <!-- CONTACTO TUTOR -->
	                  <div class="col-md-6">
	                    <div class="md-form mb-5">
	                      <input type="text" id="telefono2" name="telefono2" class="form-control">
	                      <label for="telefono2">Contacto de Tutor</label>
	                    </div>
	                  </div>
	                  <!-- FIN CONTACTO TUTOR -->

	                </div>


	                  
	              </div>
	              <!-- FIN DIRECCION -->
	            </div>
	            <!-- FIN DATOS SECUNDARIOS -->


	            <!-- IDENTIFICADORES -->
	            <div class="md-form mb-5">
		            <input type="hidden" id="identificador" name="identificador" class="form-control validate">
		        </div>


		        <div class="md-form mb-5">
		            <input type="hidden" id="identificadorAlumnoRama" name="identificadorAlumnoRama" class="form-control validate">
		            <input type="hidden" id="carga" name="carga" class="form-control validate">
		        </div>
	            <!-- FIN IDENTIFICADORES -->


	            <div class="modal-footer d-flex justify-content-center">
	              
	              <button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_guardar_alumno_edicion">
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

	<!-- FIN MODAL ALUMNO -->
	<!-- FIN FORMULARIO EDICION ALUMNOS -->


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
	        	Cambio de generación
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

	<!-- INSCRIPCION MULTIPLE MODAL -->

	<!-- Central Modal Small -->
	<div class="modal fade" id="modalInscripcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	  aria-hidden="true">

	  <!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">


	    <div class="modal-content">
	      <div class="modal-header grey darken-1 white-text text-center">
	        <h4 class="modal-title w-100" id="myModalLabel">Inscripción</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body" id="panzaModalInscripcion">
	        


	        


	      </div>
	      <div class="modal-footer">
	      		<button type="button" class="btn btn-info btn-sm btn-rounded" title="Concluye el proceso de inscripción" id="btn_finalizar">Finalizar</button>
	        	<button type="button" class="btn grey white-text btn-sm btn-rounded" data-dismiss="modal">Salir</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Central Modal Small -->

	<!-- FIN INSCRIPCION MULTIPLE MODAL -->




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
	setTimeout(function(){
		$('#buscador').focus();
	}, 300 );
	
</script>


<script>
	// SELECCION DE TODOS LOS ANNIOS
	$("#seleccionProgramas").on('click', function() {
	//event.preventDefault();
	/* Act on the event */


		//console.log( $(this)[0].checked );

		if ( $(this)[0].checked == true ) {
		  // console.log("checkeado");
		  	$('.checkboxProgramas').prop({checked: true});
		  	obtenerGeneraciones();
		  
		}else{ 
		  
			$('.checkboxProgramas').prop({checked: false});
		 	obtenerGeneraciones();

		}

	//$('.seleccionAnniosMeses').prop({checked: false});
	});


	function obtenerGeneraciones() {

		var id_ram = [];

		for ( var i = 0, j = 0 ; i < $(".checkboxProgramas").length ; i++ ) {

			if ( $(".checkboxProgramas")[i].checked == true ) {
				// alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxProgramas').eq(i).attr("annio")+" esta seleccionado"  );

				id_ram[j] = $('.checkboxProgramas').eq(i).val();

				j++;

			}
		}

		if ( id_ram.length == 0 ) {

			swal("¡No hay programas seleccionados!", "Selecciona al menos uno para continuar", "info", {button: "Aceptar",});
			
			// $("#contenedor_generaciones").html("");
			// $("#contenedor_principal").html("");

		} else {
			
			$.ajax({
				url: 'server/obtener_generaciones_programa.php',
				type: 'POST',
				data: { id_ram },
				success: function( respuesta ){

					$("#contenedor_generaciones").html( respuesta );


	                          
				}

			});

		}

		
	}


	obtenerGeneraciones();


	$('.checkboxProgramas').on('click', function() {
		//event.preventDefault();
		/* Act on the event */
		obtenerGeneraciones();
		

	});





	var burbuja = new Audio('../audio/burbuja.mp3');
    $('.seleccionEstatus').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        burbuja.play();

        if ( $(this).hasClass('btn-light') ){
        
            $(this).removeClass('btn-light').addClass('btn-info').removeAttr('switch').attr( 'switch', 'verdadero' );
        
        } else if ( $(this).hasClass('btn-info') ) {

            $(this).removeClass('btn-info').addClass('btn-light').removeAttr('switch').attr( 'switch', 'falso' );
        
        }


        obtenerAlumnosGeneraciones();
    });

    $(".radiosVisualizacion").on('change', function() {
        // event.preventDefault();
        // alert('cam');
        obtenerAlumnosGeneraciones();

    });

</script>

<!-- BOTON AMPLIA Y REDUCE ESPACIO -->
<script>
	$('#btn_espacio').on('click', function(event) {
		event.preventDefault();
		burbuja.play();		
		var estatus = $(this).attr('estatus'), elemento = $(this);

		if ( estatus == 'Inactivo' ) {
			$('#contenedor_col-3').css({
				display: 'none'
			});

			elemento.removeAttr('estatus').attr('estatus', 'Activo');
			elemento.html('<i class="fas fa-angle-double-right"></i> Ampliar/Reducir');



			$('#contenedor_principal').removeClass('col-md-9').addClass('col-md-12');
		} else if ( estatus == 'Activo' ) {

			$('#contenedor_col-3').css({
				display: ''
			});

			$('#contenedor_principal').removeClass('col-md-12').addClass('col-md-9');

			elemento.removeAttr('estatus').attr('estatus', 'Inactivo');
			elemento.html('<i class="fas fa-angle-double-left"></i> Ampliar/Reducir');
		}

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
	function obtener_tabla_alumnos( id_gen = '', palabra = '', inicio = '', fin = '', estatus = '', tipo_estatus = '', pageLength = '' ){

        // console.log( id_gen.length );

        $('#contenedor_botones_accion').html('<div class="col-md-12"> <div class="row"> <div class="col-md-12"> <div class="form-check"> <input type="checkbox" class="form-check-input" id="seleccionTotal"> <label class="form-check-label letraPequena font-weight-normal" for="seleccionTotal"> <span class="badge badge-pill badge-danger letraMediana font-weight-normal" id="contador_alumnos_seleccionados"></span> Seleccionar/Deseleccionar </label> </div></div></div><br><div class="row"> <div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Inscribir materias" id="btn_inscripcion" style="width: 100%;"> Inscripción </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Dar de baja materias" id="btn_baja" style="width: 100%;"> Cancelación de inscr. </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Envía un mensaje a los alumnos seleccionados" id="btn_mensaje_alumnos" style="width: 100%;"> Enviar mensaje </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Genera un pago para los alumnos seleccionados" id="btn_pago_alumnos" style="width: 100%;"> Crear pago </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Cambiar de grupo" id="btn_cambio" style="width: 100%;"> Cambiar grupo </a> </div> <div class="col-md-3"><a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Agregar a otro programa o curso" id="btn_programa" style="width: 100%;"> Agregar a programa </a></div></div></div>');
                
        $('#tabla_alumnos').DataTable().destroy();

        $('#contenedor_visualizacion3').html('<div class=""> <table id="tabla_alumnos" class="table table-striped"> <thead> <tr> <th class="letraPequena" style="background-color: #F5F5F5 !important;">Foto</th> <th class="letraPequena" style="background-color: #F5F5F5 !important;">Nombre</th> <th class="letraPequena">Acción</th> <th class="letraPequena">Selección</th> <th class="letraPequena">Ingreso</th>  <th class="letraPequena">Matrícula</th> <th class="letraPequena">Teléfono</th> <th class="letraPequena">Cuenta de acceso</th> <th class="letraPequena">Contraseña</th> <th class="letraPequena">Grupo</th> <th class="letraPequena">Programa</th> <th class="letraPequena">Estatus general</th> <th class="letraPequena">Estatus académico</th><th class="letraPequena">Carga alumno</th> <th class="letraPequena">Estatus de pagos</th> <th class="letraPequena">Meses adeudo</th> <th class="letraPequena">Adeudo</th> <th class="letraPequena">Pagado</th> <th class="letraPequena">Estatus de cuenta</th> <th class="letraPequena">Subestatus</th> <th class="letraPequena">Estatus de actividad</th> <th class="letraPequena">Estatus de documentación</th> </tr></thead> </table> </div>');

            // OBTENER RESPUESTA DATATABLE IMPRIMIR
            // length = 10;
            // start = 0;
            // draw = 10;
            // $.ajax({
            //     url: 'server/obtener_alumnos_generaciones.php',
            //     type: 'POST',
            //     data: { id_gen, palabra, inicio, fin, estatus, tipo_estatus, length, start, draw },
            //     success: function( respuesta ){

            //         console.log( respuesta );
            //         // $('#contenedor_visualizacion').html( respuesta );
            //     }
                
            // });

            var dataTable = $('#tabla_alumnos').DataTable({


                dom: 'Bfrtpli',
                
                scrollX: true,
                scrollY: true,
                scrollCollapse: true,
                fixedColumns: {     
                  leftColumns: [2]
                },
                buttons: [

                    {
                        extend: 'excelHtml5',
                        messageTop: 'Listado de Alumnos del Plantel',
                        exportOptions: {
                            columns: ':visible'
                        },
                    }                  

                ],
                "pageLength" : pageLength,
                "columnDefs": [
                  { "orderable": false, "targets": [ 0, 2, 3 ] }
                ],
                "processing" : true,
                "serverSide" : true,
                "order" : [],
                "searching" : false,


                "ajax" : {
                    url:"server/obtener_alumnos_generaciones.php",
                    type:"POST",
                    data:{
                        id_gen, palabra, inicio, fin, estatus, tipo_estatus
                    } 
                },

                "fnDrawCallback": function( oSettings ) {
                    

                	// NUEVA CONSULTA ALUMNO
                	$('.consultaGeneralAlumno').on('click', function(event) {
                		event.preventDefault();
                		/* Act on the event */
                		var id_alu_ram = $(this).attr('id_alu_ram');

                		$('#contenedor_visualizacion6').html('<div class="row"><div class="col-md-12"><a href="#" id="btn_volver_alumnos" class="btn-link text-primary waves"><h5>< Volver a alumnos</h5></a></div></div><hr>');

						$('#contenedor_visualizacion2').css( 'display', 'none' );
						$('#contenedor_visualizacion5').css( 'display', '' );

						$('#contenedor_visualizacion7').html('<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

						$.ajax({
                			url: 'server/obtener_consulta_general_alumno.php',
                			type: 'POST',
                			data: { id_alu_ram },
                			success: function( respuesta ){
                				// console.log( respuesta );
                				$('#contenedor_visualizacion7').html( respuesta );
                			}
                		});
						
						// console.log('hehe');

						$('#btn_volver_alumnos').on('click', function(event) {
							event.preventDefault();
							/* Act on the event */

							error.play();
							$('#contenedor_visualizacion2').css( 'display', '' );
							$('#contenedor_visualizacion5').css( 'display', 'none' );

						});

                		
                	
                	});

                	// FIN NUEVA CONSULTA ALUMNO


                    pegarSeleccionAlumno();
                    validarPaginacionSeleccionada();

                    // SELECCION
                    function validarPaginacionSeleccionada(){
                        var booleano = true;
                        for(  var i = 0; i < $('.seleccionAlumno').length; i++ ){
                            
                            if ( $('.seleccionAlumno')[i].checked == false ) {
                                booleano = false;
                                break; break;
                            }
                            
                        }

                        if ( booleano == true ) {

                            $('#seleccionTotal').prop({checked: true});

                        } else {
                            $('#seleccionTotal').prop({checked: false});
                        }
                    }   

                    $('.seleccionAlumno').on('click', function(event) {
                        // event.preventDefault();

                        burbuja.play();

                        var id_alu_ram = $(this).attr( 'id_alu_ram' );
                        var checkbox = $(this)[0].checked;
                        var path_foto = $(this).attr( 'path_foto' );
                        var nombre_alumno = $(this).attr( 'nombre_alumno' );
                        var id_ram = $(this).attr( 'id_ram' );
                        var estatus_pago = $(this).attr( 'estatus_pago' );

                        console.log(estatus_pago);



                        pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, id_ram, estatus_pago );
                        

                    });
                    // PREVIEW SELECCION
                    

                    
                    // FUNCION PARA QUE EN CADA PAGINACION SE CHECKEEN ALUMNOS PREVI. SELECCIONADOS
                    function pegarSeleccionAlumno(){
                        
                        if ( $('.pegadoSeleccionAlumno').length > 0 ) {

                            for( var i = 0; i < $('.seleccionAlumno').length; i++ ){
                            
                                for( var j = 0; j < $('.pegadoSeleccionAlumno').length; j++ ){

                                    if ( $('.pegadoSeleccionAlumno').eq( j ).attr( 'id_alu_ram' ) == $(".seleccionAlumno").eq( i ).attr( 'id_alu_ram' ) ) {

                                        $(".seleccionAlumno").eq( i ).prop({checked: true});
                                    
                                    }
                                    
                                
                                }
                            
                            }

                        }
                    
                    }


                    function pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, id_ram, estatus_pago ) {

                        console.log( 'id_ram: '+id_ram+' - estatus_pago: '+estatus_pago );
                        var array_id_alu_ram = [];

                        if ( $('.pegadoSeleccionAlumno').length == 0 ) {
                            

                            if ( checkbox == true ) {
                                // console.log('accedi');

                                setTimeout(function(){


                                    $("#contenedor_seleccion_alumnos").html( '<div class="chip pegadoSeleccionAlumno" id_alu_ram="'+id_alu_ram+'" path_foto="'+path_foto+'" id_ram="'+id_ram+'" nombre_alumno="'+nombre_alumno+'" estatus_pago="'+estatus_pago+'"><img src="'+path_foto+'"><span>'+nombre_alumno+'</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>' );

                                    contadorAlumnosSeleccionados();

                                }, 200);
                                
                            
                            }

                            

                        } else {

                            for( var i = 0 ; i < $('.pegadoSeleccionAlumno').length ; i++ ){

                                if ( id_alu_ram != $('.pegadoSeleccionAlumno').eq(i).attr('id_alu_ram') ) {
                                    // console.log('if');
                                    if ( checkbox == true ) {

                                            $("#contenedor_seleccion_alumnos").append( '<div class="chip pegadoSeleccionAlumno" id_alu_ram='+id_alu_ram+' id_ram="'+id_ram+'" nombre_alumno="'+nombre_alumno+'" estatus_pago="'+estatus_pago+'"><img src="'+path_foto+'"><span>'+nombre_alumno+'</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>' );
                                            break; break; break;

                                    }
                                    

                                } else {
                                    // console.log('else');
                                    $('.pegadoSeleccionAlumno').eq( i ).remove();
                                    break; break;

                                }

                            }


                            for( var i = 0; i < $('.pegadoSeleccionAlumno').length; i++ ){

                                array_id_alu_ram[i] = ( $('.pegadoSeleccionAlumno').eq(i).attr('id_alu_ram') );

                            }



                            function onlyUnique(value, index, self) {
                                return self.indexOf(value) === index;
                            }



                            var array_id_alu_ram_purgado = array_id_alu_ram.filter(onlyUnique);
                            
                            
                            $("#contenedor_seleccion_alumnos").html('');

                            for( var i = 0; i < array_id_alu_ram_purgado.length; i++ ){


                                for( var j = 0; j < $('.seleccionAlumno').length ; j++ ){
                                    
                                    if( $('.seleccionAlumno').eq( j ).attr( 'id_alu_ram' ) == array_id_alu_ram_purgado[i] ){
                                        nombre_alumno = $('.seleccionAlumno').eq( j ).attr( 'nombre_alumno' );
                                        path_foto = $('.seleccionAlumno').eq( j ).attr( 'path_foto' );
                                        estatus_pago = $('.seleccionAlumno').eq( j ).attr( 'estatus_pago' );
                                        id_ram = $('.seleccionAlumno').eq( j ).attr( 'id_ram' );

                                        break; break;
                                    }

                                }

                                
                                // console.log( seleccion[i] );
                                $("#contenedor_seleccion_alumnos").append( '<div class="chip pegadoSeleccionAlumno" id_alu_ram="'+array_id_alu_ram_purgado[i]+'" path_foto="'+path_foto+'" id_ram="'+id_ram+'" nombre_alumno="'+nombre_alumno+'" estatus_pago="'+estatus_pago+'"><img src="'+path_foto+'"><span>'+nombre_alumno+'</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>' );

                            }

                            contadorAlumnosSeleccionados();

                            

                        }

                        function contadorAlumnosSeleccionados(){
                            if ( $('.pegadoSeleccionAlumno').length > 0 ) {
                                $('#contador_alumnos_seleccionados').text( $('.pegadoSeleccionAlumno').length );
                            } else {
                                $('#contador_alumnos_seleccionados').text('');
                            }
                        }

                    }



                    // FIN SELECCION


                    // SELECCION SOMBRA X NOMBRE
                    $( '.seleccionNombre' ).on('click', function( event ) {
                        // event.preventDefault();
                        /* Act on the event */
                        var elemento = $( this ).parent().parent().children();
                        var indice = $( this ).parent().parent().index();

                        //     burbuja.play();
                        //     elemento.addClass('grey lighten-2');

                        //     $('#tabla_alumnos tr').eq( ++indice ).addClass('grey lighten-2');
                        // console.log( $( this ).parent().hasClass('grey lighten-2') );
                        
                        burbuja.play();
                        elemento.addClass('grey lighten-2');

                        $('#tabla_alumnos tr').eq( ++indice ).addClass('grey lighten-2');

//                            if ( $( this ).parent().hasClass('grey lighten-2') == false ) {
                            

//                                console.log('2');
//                                // alert('nada');
//                                burbuja.play();
//                                elemento.addClass('grey lighten-2');

//                                $('#tabla_alumnos tr').eq( ++indice ).addClass('grey lighten-2');

//                            } else {
// error.play();
//                                elemento.removeClass('grey lighten-2');

//                                $('#tabla_alumnos tr').eq( ++indice ).removeClass('grey lighten-2');
                            
//                                console.log('1');
                            
//                            // alert( $(this).index() );
                            
//                            }
                        

                        
                    });


                    //SELECCION
                    $("#seleccionTotal").on('click', function() {
                        //event.preventDefault();
                        /* Act on the event */

                        //console.log( $(this)[0].checked );

                        if ( $(this)[0].checked == true ) {
                          // console.log("checkeado");

                            // $('#contenedor_seleccion_alumnos').html('<div class="chip pegadoSeleccionAlumno" id_alu_ram="0" id_ram="Sin adeudo" nombre_alumno="rocio maldonado rosas" estatus_pago="undefined"><img src="../uploads/foto-alumno002134.png"><span>rocio maldonado rosas</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>');

                            $('.seleccionAlumno').prop({checked: true});
                                i = 0;

                                
                                obtenerDatos1();
                                async function obtenerDatos1(){
                                    while (  $('.seleccionAlumno').length  > i){
                                        var id_alu_ram = $('.seleccionAlumno').eq(i).attr( 'id_alu_ram' );
                                        var checkbox = $('.seleccionAlumno')[i].checked;

                                        console.log( $('.seleccionAlumno')[i].checked );
                                        var path_foto = $('.seleccionAlumno').eq(i).attr( 'path_foto' );
                                        var nombre_alumno = $('.seleccionAlumno').eq(i).attr( 'nombre_alumno' );
                                        var id_ram = $('.seleccionAlumno').eq(i).attr( 'id_ram' );
                                        var estatus_pago = $('.seleccionAlumno').eq(i).attr( 'estatus_pago' );
                                        
                                        
                                        await new Promise( resolve => setTimeout( resolve, 300 ) )
                                        pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, estatus_pago );
                                         
                                        
                                        // if ( i == 1 ) {
                                        //     $('.pegadoSeleccionAlumno').eq(0).remove();
                                        // }
                                        
                                        i++;
                                        
                                    }
                                }

                            
                            

                            
                            // pegadoSeleccionAlumnos();
                            

                        }else{ 
                            

                            $('.seleccionAlumno').prop({checked: false});

                            i = 0;

                                
                            obtenerDatos2();
                            async function obtenerDatos2(){
                                while (  $('.seleccionAlumno').length  > i){
                                    var id_alu_ram = $('.seleccionAlumno').eq(i).attr( 'id_alu_ram' );
                                    var checkbox = $('.seleccionAlumno')[i].checked;

                                    console.log( $('.seleccionAlumno')[i].checked );
                                    var path_foto = $('.seleccionAlumno').eq(i).attr( 'path_foto' );
                                    var nombre_alumno = $('.seleccionAlumno').eq(i).attr( 'nombre_alumno' );
                                    var id_ram = $('.seleccionAlumno').eq(i).attr( 'id_ram' );
                                    var estatus_pago = $('.seleccionAlumno').eq(i).attr( 'estatus_pago' );
                                    
                                    
                                    await new Promise( resolve => setTimeout( resolve, 300 ) )
                                    pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, estatus_pago );
                                     
                                    
                                    // if ( i == 1 ) {
                                    //     $('.pegadoSeleccionAlumno').eq(0).remove();
                                    // }
                                    
                                    i++;
                                    
                                }
                            }
                        
                        }

                        //$('.seleccionAlumno').prop({checked: false});
                    });


                    // FIN SELECCION

                    $('.eliminacionAlumno').on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        var alumno = $(this).attr("eliminacion");
                        var nombreAlumno = $(this).attr("alumno");

                        // console.log(alumno);

                        // VALIDACION PERMISOS

                        swal({
                          title: "¡Acceso Restringido!",
                          icon: "warning",
                          text: 'Necesitas permisos de administrador para continuar',
                          content: {
                            element: "input",
                            attributes: {
                              placeholder: "Ingresa una contraseña...",
                              type: "password",
                            },
                          },

                          button: {
                            text: "Validar",
                            closeModal: false,
                          },
                        })
                        .then(name => {
                          if (name){
                            //console.log(name);
                            var password = name;
                            $.ajax({
                                    
                                url: 'server/validacion_permisos.php',
                                type: 'POST',
                                data: {password},
                                success: function(respuesta){
                                    //console.log(respuesta);

                                    if (respuesta == 'True') {

                                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                        then((value) => {
                                            //
                                            //console.log("Existe el password");
                                            // CODIGO

                                            swal({
                                              title: "¿Deseas eliminar a "+nombreAlumno+"?",
                                              text: "¡Una vez eliminado se perderán todos los datos relacionados a esa persona!",
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
                                                url: 'server/eliminacion_alumno.php',
                                                type: 'POST',
                                                data: {alumno},
                                                success: function( respuesta ){

                                                    console.log( respuesta );
                                                  
                                                  // if (respuesta == "true") {
                                                    console.log("Exito en consulta");
                                                    swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                      // window.location.reload();


                                                      // obtenerAlumnosGeneraciones();
                                                      reloadTableGeneral();


                                                    });
                                                  // }else{
                                                  //   console.log(respuesta);

                                                  // }

                                                }
                                              });
                                                
                                              }
                                            });

                                            // FIN CODIGO
                                        });
                                        
                                    }else{
                                        // LA CONTRASENA NO EXISTE
                                        
                                        swal({
                                          title: "¡Datos incorrectos!",
                                          text: 'No existe la contraseña...',
                                          icon: "error",
                                          button: "Aceptar",
                                        });
                                        swal.stopLoading();
                                        swal.close();

                                        
                                    }
                                }
                            });

                          }else{
                            // DATOS VACIOS
                            swal({
                              title: "¡Datos vacíos!",
                              text: 'Necesitas ingresar una contraseña...',
                              icon: "error",
                              button: "Aceptar",
                            });
                            swal.stopLoading();
                            swal.close();
                          }
                          
                        });

                        // FIN VALIDACION PERMISOS

                    });
                    // FIN ELIMINACION


                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


                    //EDICION DE ALUMNO

                    $('#btn_limpiar_formulario_edicion').on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        error.play();
                        $('#formulario_editar_alumno input').val('');
                        $('#contenedor_datos_secundarios_edicion label').removeClass('active');
                        $('#contenedor_imagen_edicion').removeAttr('src').attr('src', '../img/usuario.jpg');

                    });


                    // // FORMULARIO DATOS SECUNDARIOS
                    // obtener_checkbox_datos_secundarios_edicion();
                    // $("#checkboxAlumnoDatosSecundariosEdicion").on('click', function() {

                    //     obtener_checkbox_datos_secundarios_edicion();

                    // });

                    // function obtener_checkbox_datos_secundarios_edicion(){

                    //     if ( $('#checkboxAlumnoDatosSecundariosEdicion')[0].checked == true ) {
                    //         // console.log("checkeado");
                    //       $('#contenedor_datos_secundarios_edicion label').addClass('active');
                    //       $('#beca_alu_ram').val(0);
                    //       $('#beca2_alu_ram').val(0);
                    //       $('#nacimiento').val('<?php echo date('Y-m-d'); ?>');
                    //       $('#genero').val('PENDIENTE');
                    //       $('#curp').val('PENDIENTE');
                    //       $('#procedencia').val('PENDIENTE');
                    //       $('#direccion').val('PENDIENTE');
                    //       $('#codigo').val('PENDIENTE');
                    //       $('#colonia').val('PENDIENTE');
                    //       $('#delegacion').val('PENDIENTE');
                    //       $('#entidad').val('PENDIENTE');
                    //       $('#tutor').val('PENDIENTE');
                    //       $('#telefono2').val('PENDIENTE');

                    //     }else{
                          
                    //       $('#contenedor_datos_secundarios_edicion label').removeClass('active');
                    //       $('#beca_alu_ram').val('');
                    //       $('#beca2_alu_ram').val('');
                    //       $('#nacimiento').val('');
                    //       $('#genero').val('');
                    //       $('#curp').val('');
                    //       $('#procedencia').val('');
                    //       $('#direccion').val('');
                    //       $('#codigo').val('');
                    //       $('#colonia').val('');
                    //       $('#delegacion').val('');
                    //       $('#entidad').val('');
                    //       $('#tutor').val('');
                    //       $('#telefono2').val('');

                    //     }
                    // }


                    $('#foto').on('change', function(event) {
                        event.preventDefault();

                        readURL(this);

                    });


                    function readURL(input) {
                        if (input.files && input.files[0]) {

                            var reader = new FileReader();
                            reader.onload = function (e) {
                            $('#contenedor_imagen_edicion')
                              .attr('src', e.target.result);
                            };
                            reader.readAsDataURL(input.files[0]);
                        
                        }
                    }


                    $('#correoEdicion').on('keyup', function(event) {
                        /* Act on the event */

                        var correo = $('#correoEdicion').val();
                        validacionCorreoTiempoRealEdicion( correo );

                    });

                    function validacionCorreoTiempoRealEdicion( correoEdicion ){
                        console.log( correoEdicion );

                        var identificador = $('#identificador').val();
                        var tipo = "Alumno";

                        if (correoEdicion != '') {
                          $.ajax({
                            url: 'server/validacion_correo.php',
                            type: 'POST',
                            data: { correoEdicion, tipo, identificador },
                            success: function(response){
                              console.log(  response );
                              var respuesta = response; 

                              if (respuesta == 'disponible') {
                                
                                $('#outputEdicion').attr({
                                  class: 'text-info letraPequena font-weight-normal'
                                });
                                $('#outputEdicion').text("¡El correo electrónico está disponible!");

                              } else if ( respuesta == 'mio' ) {


                                $('#outputEdicion').attr({
                                  class: 'text-info letraPequena font-weight-normal'
                                });
                                $('#outputEdicion').text("¡El correo electrónico es el mismo!");


                              } else {
                                // correo = correo+'1';
                                
                                correoEdicion = correoEdicion.substring(0, correoEdicion.indexOf("@"))+'1';

                                correoEdicion = correoEdicion+'@<?php echo $folioPlantel; ?>.com';
                                $('#correoEdicion').val( correoEdicion );

                                validacionCorreoTiempoRealEdicion( correoEdicion );
                                
                                // $('#outputEdicion').attr({
                                //   class: 'text-danger letraPequena font-weight-normal'
                                // });
                                // $('#outputEdicion').text("¡El correo electrónico está ocupado!");

                              }
                            }
                          })

                        }else{
                          $('#outputEdicion').attr({class: 'text-warning letraPequena font-weight-normal'});
                          $('#outputEdicion').text("¡Ingresa un Correo Electrónico!");
                        }
                              
                    }

                    
                    $('.edicionAlumno').on('click', function(event){
                        event.preventDefault();

                        var edicionAlumno = $(this).attr("edicion");
                        var rama = $(this).attr("rama");
                        $('#formulario_editar_alumno label').addClass('active');
                        $('#formulario_editar_alumno i').addClass('active');

                        $('#modal_editar_alumno').modal('show');


                        console.log('edicion alumno');

                        $.ajax({
                          url: 'server/obtener_alumno.php',
                          type: 'POST',
                          dataType: 'json',
                          data: {edicionAlumno, rama},
                          success: function(datos){

                            console.log(datos);

                            $('#fotoText').removeAttr('placeholder').val('');
                            
                            $('#nombre').attr({value: datos.nom_alu});

                            $('#apellido1').attr({value: datos.app_alu});
                            $('#apellido2').attr({value: datos.apm_alu});
                            
                            $('#correo1_alumno').attr({value: datos.cor1_alu});
                            $('#boleta').attr({value: datos.bol_alu});
                            $('#genero').attr({value: datos.gen_alu});
                            $('#telefono').attr({value: datos.tel_alu});
                            $('#curp').attr({value: datos.cur_alu});
                            $('#procedencia').attr({value: datos.pro_alu});
                            $('#correoEdicion').attr({value: datos.cor_alu});
                            $('#password').attr({value: datos.pas_alu});
                            $('#nacimiento').attr({value: datos.nac_alu});
                     
                            $('#beca_alu_ram').attr({value: datos.bec_alu_ram*100});
                            $('#beca2_alu_ram').attr({value: datos.bec2_alu_ram*100});
                            $('#carga').attr({value: datos.car_alu_ram});


                            if ( datos.fot_alu == null ) {

                                $('#contenedor_imagen_edicion').removeAttr('src').attr( 'src', '../img/usuario.jpg' ); 
                                $('#fotoText').removeAttr('placeholder').attr('placeholder', 'Sube un archivo en JPG, JPEG o PNG');

                            } else {
                            
                                $('#contenedor_imagen_edicion').removeAttr('src').attr( 'src', '../uploads/'+datos.fot_alu ); 
                                $('#fotoText').removeAttr('placeholder').attr('placeholder', datos.fot_alu);
                            
                            }
                            
                            

                            $('#direccion').attr({value: datos.dir_alu});
                            $('#codigo').attr({value: datos.cp_alu});
                            $('#colonia').attr({value: datos.col_alu});
                            $('#delegacion').attr({value: datos.del_alu});
                            $('#entidad').attr({value: datos.ent_alu});
                            $('#tutor').attr({value: datos.tut_alu});
                            $('#telefono2').attr({value: datos.tel2_alu});
                            $('#identificador').attr({value: datos.id_alu});
                            $('#identificadorAlumnoRama').attr({value: datos.id_alu_ram});
                            //AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL ALUMNO
                            $('#formulario_editar_alumno').on('submit', function(event) {
                              
                              event.preventDefault();

                              $("#btn_guardar_alumno_edicion").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando');

                              var correoEdicion = $('#correoEdicion').val();
                              var identificador = $('#identificador').val();
                              var tipo = "Alumno";

                              $.ajax({
                                url: 'server/validacion_correo.php',
                                type: 'POST',
                                data: {correoEdicion, identificador, tipo},
                                success: function(respuesta){
                                  console.log(respuesta);

                                  if (respuesta == "disponible" || respuesta == "mio") {
                                        
                                        if ($("#foto")[0].files[0]) {

                                          var fileName = $("#foto")[0].files[0].name;
                                          var fileSize = $("#foto")[0].files[0].size;

                                          var ext = fileName.split('.').pop();

                                          
                                          if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
                                            if (fileSize < 3000000) {
                                              $.ajax({
                                          
                                                url: 'server/editar_alumno.php',
                                                type: 'POST',
                                                data: new FormData( formulario_editar_alumno ),
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                success: function(respuesta){
                                                console.log(respuesta);

                                                    
                                                    $("#btn_guardar_alumno_edicion").removeAttr('disabled').html('<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info').addClass('light-green accent-4');

                                                    swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                      //window.location.reload();
                                                        // obtenerAlumnosGeneraciones();
                                                        reloadTableGeneral();

                                                        setTimeout(function(){
                                                            $("#btn_guardar_alumno_edicion").html('Guardar').removeClass('light-green accent-4').addClass('btn-info');
                                                        }, 2000 );

                                                        $('#modal_editar_alumno').modal('hide');

                                                    });
                                                  
                                                }
                                              });
                                            }else{
                                              swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
                                            }
                                            
                                          }else{
                                            swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
                                          }

                                        }else{
                                            $.ajax({
                                          
                                                url: 'server/editar_alumno.php',
                                                type: 'POST',
                                                data: new FormData( formulario_editar_alumno ), 
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                success: function(respuesta){
                                                  console.log(respuesta);


                                                    $("#btn_guardar_alumno_edicion").removeAttr('disabled').html('<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info').addClass('light-green accent-4');

                                                    swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                        
                                                        //window.location.reload();
                                                        // obtenerAlumnosGeneraciones();
                                                        reloadTableGeneral();
                                                        //console.log("exito sin foto");
                                                        
                                                        setTimeout(function(){
                                                            $("#btn_guardar_alumno_edicion").html('Guardar').removeClass('light-green accent-4').addClass('btn-info');
                                                        }, 2000 );

                                                        $('#modal_editar_alumno').modal('hide');

                                                    });
                                                    
                                                  
                                                }
                                            });

                                        }            

                                    
                                  }else{

                                    $('#validacionCorreoEdicion').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

                                  }
                                }
                              });   
                            });
                          }
                        });
                    });

                    //FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE ALUMNO



                    // CAMBIO DE GENERACION

                    $("#btn_cambio").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        console.log('cambio gen');
                        // VARIABLES
                        //ARREGLO DE ALUMNOS POR INSCRIBIR
                        var alumnos = [];

                        for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                contador++;
                            
                        }

                        if ( alumnos.length == 0 ) {
                          swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                        }else{

                            $("#modal_cambio_generacion").modal('show');

                            $.ajax({
                                url: 'server/obtener_cambio_generacion_alumnos.php',
                                type: 'POST',
                                data: { alumnos },
                                success: function(respuesta){
                                  $("#contenedor_cambio_generacion").html(respuesta);
                                }
                            });
                          
                        }
                    });
                    

                    // FIN CAMBIO DE GENERACION



                    // AGREGAR PROGRAMA
                    $("#btn_programa").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        console.log('cambio prog');
                        // VARIABLES
                        //ARREGLO DE ALUMNOS POR INSCRIBIR
                        var alumnos = [];

                        for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                contador++;
                            
                        }

                        if ( alumnos.length == 0 ) {
                          swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                          
                        }else{

                            $("#modal_agregar_programa").modal('show');

                            $.ajax({
                                url: 'server/obtener_agregar_programa_alumnos.php',
                                type: 'POST',
                                data: { alumnos },
                                success: function(respuesta){
                                  $("#contenedor_agregar_programa").html(respuesta);
                                }
                            });
                          
                        }
                    });

                    // FIN AGREGAR PROGRAMA

                    // CONSULTA DOCUMENTACION
                    $('.consultaDocumentacion').on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        var id_alu_ram = $(this).attr("id_alu_ram");
                        var nombreAlumno = $(this).attr("alumno");

                        // console.log(alumno);

                        // VALIDACION PERMISOS

                        swal({
                          title: "¡Acceso Restringido!",
                          icon: "warning",
                          text: 'Necesitas permisos de administrador para continuar',
                          content: {
                            element: "input",
                            attributes: {
                              placeholder: "Ingresa una contraseña...",
                              type: "password",
                            },
                          },

                          button: {
                            text: "Validar",
                            closeModal: false,
                          },
                        })
                        .then(name => {
                          if (name){
                            //console.log(name);
                            var password = name;
                            $.ajax({
                                    
                                url: 'server/validacion_permisos.php',
                                type: 'POST',
                                data: {password},
                                success: function(respuesta){
                                    //console.log(respuesta);

                                    if (respuesta == 'True') {

                                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                        then((value) => {
                                            //
                                            //console.log("Existe el password");

                                            $('#modal_consulta_documentacion').modal('show');

                                            $('#titulo_consulta_documentacion').html( '<i class="fas fa-file-archive"></i> Alumno: '+nombreAlumno );


                                            $.ajax({
                                                url: 'server/obtener_documentacion_alumno.php',
                                                type: 'POST',
                                                data: { id_alu_ram },
                                                success: function( respuesta ){

                                                    $('#contenedor_consulta_documentacion').html( respuesta );

                                                }
                                            });
                                            
 
                                        });
                                        
                                    }else{
                                        // LA CONTRASENA NO EXISTE
                                        
                                        swal({
                                          title: "¡Datos incorrectos!",
                                          text: 'No existe la contraseña...',
                                          icon: "error",
                                          button: "Aceptar",
                                        });
                                        swal.stopLoading();
                                        swal.close();

                                        
                                    }
                                }
                            });

                          }else{
                            // DATOS VACIOS
                            swal({
                              title: "¡Datos vacíos!",
                              text: 'Necesitas ingresar una contraseña...',
                              icon: "error",
                              button: "Aceptar",
                            });
                            swal.stopLoading();
                            swal.close();
                          }
                          
                        });

                        // FIN VALIDACION PERMISOS

                    });


                    

                    // FIN CONSULTA DOCUMENTACION



                      $("#btn_seleccion_alumnos").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */

                        for( var i = 0, contador = 0, contador2 = 0; i < $(".seleccionAlumno").length ; i++ ){

                          if ( $(".seleccionAlumno")[i].checked == true ) {

                            //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                            toastr.info( $(".seleccionAlumno").eq(i).attr("nombre_alumno") );
                            contador++;
                           
                          }

                        }

                      });

                      // FIN PREVIEW SELECCION


                      // ENVIO DE MENSAJES
                        $("#btn_mensaje_alumnos").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            console.log('clicl');
                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnos = [];

                            for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                    //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                    alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                    contador++;
                                
                            }

                            if ( alumnos.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                                $("#modal_mensaje_alumnos").modal('show');

                                

                                obtener_seleccion_envio();

                                $('.radiosSeleccionEnvio').on('change', function(event) {
                                    event.preventDefault();
                                    /* Act on the event */

                                    obtener_seleccion_envio();
                                });
                                function obtener_seleccion_envio() {
                               
                                    radiosSeleccionEnvio = $(".radiosSeleccionEnvio:checked").val();
                                
                                    if ( radiosSeleccionEnvio == 'Mensaje' ) {
                                        
                                        $.ajax({
                                            url: 'server/obtener_mensaje_alumnos.php',
                                            type: 'POST',
                                            data: { alumnos },
                                            success: function(respuesta){
                                                // console.log( respuesta );
                                                $("#contenedor_principal_mensaje_alumnos").html(respuesta);

                                                $('#contenedor_tipo_mensaje').html('<div class="form-group basic-textarea" style="position: relative;"><textarea class="form-control pl-2 my-0" rows="3" placeholder="Escribe un mensaje..." id_usuario="" id="mensaje_alumnos" soy="" sala="" required=""></textarea> </div>');

                                                $("#mensaje_alumnos").emojioneArea({
                                            
                                                    pickerPosition: "top",

                                                    events: {
                                                      keyup: function(editor, event) {
                                                        // catches everything but enter
                                                        if (event.which == 13) {
                                                            // console.log('if');
                                                            enviar_mensaje_alumnos();
                                                          // return false;
                                                        } else {
                                                            console.log('else');
                                                        }

                                                      }
                                                    }
                                                
                                                });

                                                $('#btn_enviar_mensaje_alumnos').on('click', function(event) {
                                                    event.preventDefault();
                                                    /* Act on the event */
                                                    enviar_mensaje_alumnos();
                                                });

                                            }
                                        });
                                        
                                        


                                    } else if ( radiosSeleccionEnvio == 'Archivo' ) {
                                        // alert( 'Archivo' );

                                        // $('#contenedor_btn_enviar_mensaje_alumnos');

                                      

                                        // $('#barra_estado_mensaje').removeAttr('tipo').attr('tipo', 'archivo');

                                        // $.ajax({
                                        //     url: 'server/obtener_formulario_archivo.php',
                                        //     type: 'POST',
                                        //     data: { alumnos },
                                        //     success: function( respuesta ){
                                        //         // console.log( respuesta );
                                        //         $("#contenedor_principal_mensaje_alumnos").html(respuesta);
                                        //         $('.file_upload').file_upload();


                                        //     }
                                        // });
                                        

                                    }

                                }

                                // ENVIAR MENSAJE
                                function enviar_mensaje_alumnos(){

                                    // 
                                        swal({
                                          title: "¿Deseas enviar este mensaje a estos "+$(".seleccionAlumnoFinal").length+" alumnos?",
                                          text: "¡Podrás revisarlo en el área de mensajería más tarde!",
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

                                            $(".eliminacionSeleccionAlumnoFinal").remove();
                                            let barra_estado_mensaje = $("#barra_estado_mensaje");
                                            var porcentaje;
                                            var contador;

                                            radiosSeleccionEnvio = $(".radiosSeleccionEnvio:checked").val();
                                    
                                            if ( radiosSeleccionEnvio == 'Mensaje' ) {

                                                var mensaje = $("#mensaje_alumnos").data("emojioneArea").getText();

                                                for(var i = 0, tipo_usuario = 'Alumno'; i < $(".seleccionAlumnoFinal").length; i++){
                                                
                                                    var id_usuario = $('.seleccionAlumnoFinal').eq(i).attr("id_alu");
                                                    var id_sal = $('.seleccionAlumnoFinal').eq(i).attr("id_sal");
                                                    
                                                    $.ajax({
                                                        ajaxContador: i,
                                                        url: 'server/agregar_mensaje.php',
                                                        type: 'POST',
                                                        data: { id_usuario, tipo_usuario, id_sal, mensaje },
                                                        beforeSend: function(){

                                                            $("#btn_enviar_mensaje_alumnos").removeClass('btn-info').addClass('btn-secondary disabled').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');

                                                        }
                                                    }).done(function(respuesta) {
                                                        //console.log(respuesta);

                                                        if ( $(".seleccionAlumnoFinal").eq(this.ajaxContador).attr("id_alu") == respuesta ) {
                                                            $(".seleccionAlumnoFinal").eq(this.ajaxContador).addClass('light-green accent-4 white-text');
                                                        }

                                                        contador = this.ajaxContador + 1;
                                                        porcentaje = Math.floor( contador*(100/$(".seleccionAlumnoFinal").length), 2 );
                                                        

                                                        if (porcentaje <= 100) {
                                                            
                                                            barra_estado_mensaje.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
                                                                
                                                            barra_estado_mensaje.text(porcentaje+'%');
                                                            
                                                            if (porcentaje == 100) {
                                                                barra_estado_mensaje.removeClass();
                                                                barra_estado_mensaje.addClass('progress-bar text-center white-text bg-success');
                                                                barra_estado_mensaje.text("Listo");
                                                                $(".seleccionAlumnoFinal").eq(i).addClass('light-green accent-4 white-text');

                                                                $("#btn_enviar_mensaje_alumnos").removeClass('btn-secondary').addClass('light-green accent-4 white-text').html('<i class="fas fa-check white-text"></i> <span>Enviar</span>');

                                                                swal("Envío de mensaje exitoso", "Continuar", "success", {button: "Aceptar",}).
                                                                then((value) => {
                                                                  
                                                                  $("#btn_enviar_mensaje_alumnos").removeClass('disabled light-green accent-4 white-text').addClass('btn-info');

                                                                    var el = $("#mensaje_alumnos").emojioneArea();//REEMPLAZO DEL CLASICO .val("")
                                                                    el[0].emojioneArea.setText(''); // clear input 

                                                                  // $("#modal_mensaje_alumnos").modal("hide");




                                                                });
                                                            }

                                                        }
                                                        
                                                    });


                                                }
                                                // BUCLE FOR

                                                obtener_seleccion_envio();

                                            }
                                            
                                          }
                                        });
                                    // 
                                    

                                    

                                }
                                // FIN ENVIAR MENSAJE
                                
                                
                              
                            }
                        });

                      // FIN DE ENVIO DE MENSAJE

                        $("#btn_pago_alumnos").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            console.log('pagos');
                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnos = [];

                            for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                    //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                    alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                    contador++;
                                
                            }

                            if ( alumnos.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                                $("#modal_pago_alumnos").modal('show');

                                $.ajax({
                                    url: 'server/obtener_pago_alumnos.php',
                                    type: 'POST',
                                    data: { alumnos },
                                    success: function(respuesta){
                                      $("#contenedor_pago_alumnos").html(respuesta);
                                    }
                                });
                              
                            }
                        });





                        // INSCRIPCION

                        $("#btn_inscripcion").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            
                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnosInscripcion = [];
                            var alumnosValidador = [];
                            var alumnosValidadorAdeudo = [];
                            var alumnosDeudores = [];
                            var alumnosNombresInscripcion = [];

                            
                            for( var i = 0, contador = 0, contador2 = 0; i < $(".pegadoSeleccionAlumno").length ; i++ ){


                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnosInscripcion[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                alumnosValidador[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_ram");
                                alumnosValidadorAdeudo[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("estatus_pago");
                                alumnosNombresInscripcion[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("nombre_alumno");

                                contador++;
                               

                            }





                            if ( alumnosInscripcion.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                              var validador = true;
                              for ( var i = 0 ; i < alumnosValidador.length ; i++ ) {
                                for ( var j = 0 ; j < alumnosValidador.length ; j++ ) {

                                  if ( alumnosValidador[j] != alumnosValidador[i] ) {
                                        // console.log( alumnosValidador[j] != alumnosValidador[i] );
                                    validador = false;
                                    break;
                                    break;
                                    break;
                                  }
                                }
                              }


                              var validadorAdeudo = true;
                              for ( var i = 0, j = 0 ; i < alumnosValidadorAdeudo.length ; i++ ) {
                                
                                if ( ( alumnosValidadorAdeudo[i] == 'Con adeudo' )  && ( validadorAdeudo == true ) ) {
                                  // console.log( alumnosValidador[j] != alumnosValidador[i] );
                                  validadorAdeudo = false; 
                                }

                                if ( alumnosValidadorAdeudo[i] == 'Con adeudo' ) {
                                  alumnosDeudores[j] = alumnosNombresInscripcion[i];
                                  j++;
                                }
                              }

                              if ( validador == true ) {


                                if ( validadorAdeudo == true ) {

                                  var id_ram = alumnosValidador[0];
                                  var validadorGlobal = 1;

                                  $("#modalInscripcion").modal('show');

                                  $("#panzaModalInscripcion").html( '<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>' );

                                  $.ajax({
                                    url: 'server/obtener_inscripcion_alumnos.php',
                                    type: 'POST',
                                    data: { alumnosInscripcion, id_ram, validadorGlobal },
                                    success: function( respuesta ) {
                                      
                                      $("#panzaModalInscripcion").html(respuesta);
                                    }
                                  });

                                } else if ( validadorAdeudo == false ) {

                                    error.play();
                                  for ( var i = 0 ; i < alumnosDeudores.length ; i++ ) {
                                    toastr.warning(alumnosDeudores[i]+' presenta adeudo, NO se puede inscribir');
                                  }
                                  swal("¡Error en selección de alumnos!", "Para continuar, asegúrate de que los alumnos no adeuden pagos", "info", {button: "Aceptar",});
                                }
                                


                              } else if ( validador == false ) {
                                swal("¡Error en selección de alumnos!", "Para continuar, asegúrate de que los alumnos pertenezcan al mismo programa", "info", {button: "Aceptar",});  
                              }

                              
                            }

                            

                          });


                        // BAJA
                        $("#btn_baja").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnosInscripcion = [];

                            for(var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++){

                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnosInscripcion[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                contador++;

                            }

                            if ( alumnosInscripcion.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                              $("#modalBaja").modal('show');
                              var validadorGlobal = 1;

                              $.ajax({
                                url: 'server/obtener_alumnos_baja_materias.php',
                                type: 'POST',
                                data: {alumnosInscripcion, validadorGlobal},
                                success: function(respuesta){
                                  $("#panzaModalBaja").html(respuesta);
                                }
                              });
                              
                            }
                        });


                        // MODAL ALUMNO
                        $(".consultaAlumno").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            var id_alu_ram = $(this).attr("id_alu_ram");
                            $.ajax({
                              url: 'server/obtener_consulta_alumno.php',
                              type: 'POST',
                              data: { id_alu_ram },
                              success: function( respuesta ){
                                $("#modalConsultaAlumno").modal( 'show' );
                                $("#contenedorConsultaAlumno").html( respuesta );
                              }
                            });
                            
                        });
                        // FIN MODAL ALUMNO


                        //CONSULTA DE HORARIO ALUMNO

                        $(".horarioAlumno").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            var edicionAlumno = $(this).attr("id_alu");
                            var rama = $(this).attr("id_ram");
                            var id_alu_ram = $(this).attr("id_alu_ram");

                            $.ajax({
                              url: 'server/obtener_alumno.php',
                              type: 'POST',
                              dataType: 'json',
                              data: {edicionAlumno, rama},
                              success: function(datos){

                                $("#modalHorarioAlumno").modal('show');
                                $('#tituloModalHorarioAlumno').html('<img src="../uploads/'+datos.fot_alu+'" class="img-fluid avatar rounded-circle" width="40px" height="40px"> '+"Horario de "+datos.nom_alu+" "+datos.app_alu);

                                $.ajax({
                                  url: 'server/obtener_horario_alumno.php',
                                  type: 'POST',
                                  data: {id_alu_ram},
                                  success: function(respuesta){
                                    $("#panzaModalHorarioAlumno").html(respuesta);
                                  }
                                });
                                
                              }
                            });

                        });


                          // CONSULTA DE ACTIVIDADES
                            // ACTIVIDADES ALUMNO
                          $(".actividadesAlumno").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            console.log('click');

                            var id_alu_ram = $(this).attr("id_alu_ram");
                            
                            $.ajax({
                              url: 'server/obtener_actividades_alumno.php',
                              type: 'POST',
                              data: { id_alu_ram },
                              success: function( respuesta ){
                                $("#modalActividadesAlumno").modal( 'show' );
                                $("#contenedorActividadesAlumno").html( respuesta );
                              }
                            });
                            
                          });
                
                // FIN FUNCION LOAD REGISTROS NUEVOS
                },





                


                
             // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

             "language": {
                    "sProcessing": '<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>',
                      "sLengthMenu":     "Mostrar _MENU_ registros",
                      "sZeroRecords":    "No se encontraron resultados",
                      "sEmptyTable":     "Ningún dato disponible en esta tabla",
                      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                      "sInfoPostFix":    "",
                      "sSearch":         "Buscar:",
                      "sUrl":            "",
                      "sInfoThousands":  ",",
                      "sLoadingRecords": "Cargando...",
                      "oPaginate": {
                          "sFirst":    "Primero",
                          "sLast":     "Último",
                          "sNext":     "Siguiente",
                          "sPrevious": "Anterior"
                         },
                     "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                     }
                 }


         });
        
        



        $('#tabla_alumnos_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });

        // setTimeout(function(){
        //     $('#tabla_alumnos tr').find('td').each(function () {
        //         $(this).addClass('letraPequena');
        //     });
        // }, 500 );

        // SCROLL TOP AND BOTTOM
        $('.dataTables_scrollHead').css({
            'overflow-x':'scroll'
        }).on('scroll', function(e){
            var scrollBody = $(this).parent().find('.dataTables_scrollBody').get(0);
            scrollBody.scrollLeft = this.scrollLeft;
            $(scrollBody).trigger('scroll');
        });
        // SCROLL TOP AND BOTTOM
        
        $('#tabla_alumnos_wrapper .dataTables_filter').find('input').each(function () {
            $('#tabla_alumnos_wrapper input').attr("placeholder", "Buscar...");
            $('#tabla_alumnos_wrapper input').removeClass('form-control-sm');
        });
        $('#tabla_alumnos_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#tabla_alumnos_wrapper .dataTables_filter').addClass('md-form');
        $('#tabla_alumnos_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
        $('#tabla_alumnos_wrapper .mdb-select').materialSelect('destroy');
        $('#tabla_alumnos_wrapper select').addClass('mdb-select');
        $('#tabla_alumnos_wrapper .mdb-select').materialSelect();
        $('#tabla_alumnos_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#tabla_alumnos_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');

        $('#contenedor_select').html('');
        $('#contenedor_paginacion').html('');
        $('#contenedor_info').html('');

        $('#contenedor_select').html($('#tabla_alumnos_length'));
        $('#tabla_alumnos_length').find('label');
        $('#contenedor_paginacion').html($('#tabla_alumnos_paginate'));
        $('#contenedor_info').html($('#tabla_alumnos_info').addClass('letraPequena'));
    }
</script>