<?php  
		

  include('inc/header.php');
?>


<style>
	.botonHijo {
		position: absolute;
		right: 5%;
		top: 5%; 
	}

	.botonPadre {
		position: relative;
	}

	input {
		font-size: 10px !important;
	}
</style>


<!-- TITULO -->
<div class="row ">
	<div class="col-md-6 text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Grupos">
			<i class="fas fa-bookmark"></i> 
			Grupos del Plantel
		</span>
		<br>
		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al Inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="" title="Estás aquí"><span style="color: black;">Grupos </span></a>		
		</div>
	</div>



	

</div>
<!-- FIN TITULO-->


<style>
 .scrollspy-example {
height: 360px;
}
</style>


<!-- INDICADORES Y FILTROS -->
<div class="row">

	<!-- PROGRAMAS -->
	<div class="col-md-3">
		<div class="card">

			<div class="card-header grey darken-1 text-center" role="tab">
		      <a data-toggle="collapse">
		        <h5 class="letraMediana white-text">
		        	Programas
		        </h5>
		      </a>
		    </div>


			<div class="card-body bg-light scrollspy-example z-depth-1 text-center p-0" data-spy="scroll" data-target="#mdb-scrollspy-ex" data-offset="0" style="height: 240px;">

				
				<?php  
					$sqlProgramas = "
						SELECT *
						FROM rama
						WHERE id_pla1 = '$plantel'
						ORDER BY id_ram ASC
					";

					$resultadoProgramas = mysqli_query( $db, $sqlProgramas );
					$resultadoTotalProgramas = mysqli_query( $db, $sqlProgramas );

					$contadorProgramas = 1;

					$totalProgramas = mysqli_num_rows( $resultadoTotalProgramas );

	            	for ( $i = 0 ; $i < $totalProgramas / 1 ; $i++ ) {
	            ?>
		            	<div class="row" style="text-align: initial; position: relative;top: 1vh;">
		            		<?php  
		            			while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
		            		?>
									<div class="col-md-12">
										<div class="form-check">
						                  <input type="checkbox" class="form-check-input checador5" id="programa<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['nom_ram']; ?>" columna="6">
						                  <label class="form-check-label letraPequena font-weight-bold" for="programa<?php echo $contadorProgramas; ?>">
						                
						                    <?php echo $filaProgramas['nom_ram']; ?>

						                  </label>
						                </div>
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
		</div>
	</div>
	<!-- FIN PROGRAMAS -->



	<!--CICLOS -->
    <div class="col-md-3">

		    <div class="card">

		    	<!-- Card header -->
			    <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo4">
			      <a data-toggle="collapse" data-parent="#accordionEx4" href="#collapseTwo4"
			        aria-expanded="false" aria-controls="collapseTwo4">
			        <h5 class="letraMediana white-text">
			        	Ciclos Escolares
			        </h5>
			      </a>
			    </div>

			    <!-- Card body -->
			 
					
					<div class="card-body bg-light scrollspy-example" data-spy="scroll" style="height: 240px;">
						
						<?php  
			                $sqlCiclos = "
			                  SELECT *
			                  FROM ciclo
			                  INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			                  WHERE id_pla1 = '$plantel'
			                  ORDER BY id_ram DESC
			                ";

			                $resultadoCiclos = mysqli_query( $db, $sqlCiclos );
			                $resultadoTotalCiclos = mysqli_query( $db, $sqlCiclos );

			                $contadorCiclos = 1;

			                $totalCiclos = mysqli_num_rows( $resultadoTotalCiclos );

			                      for ( $i = 0 ; $i < $totalCiclos / 1 ; $i++ ) {
			                    ?>
			                        <div class="row">
			                          <?php  
			                            while( $filaCiclos = mysqli_fetch_assoc( $resultadoCiclos ) ){
			                          ?>
			                        <div class="col-md-12">
			                          <div class="form-check">
			                                <input type="checkbox" class="form-check-input checador7" id="ciclo<?php echo $contadorCiclos; ?>" value="<?php echo $filaCiclos['nom_cic']; ?>" columna="8">
			                                <label class="form-check-label letraPequena font-weight-bold" for="ciclo<?php echo $contadorCiclos; ?>">
			                              
			                                  <?php echo $filaCiclos['nom_cic']; ?>

			                                </label>
			                              </div>
			                        </div>
			                          <?php
			                              $contadorCiclos++;
			                            }
			                          ?>
			                          
			                        </div>

			                <?php
			                      }
			                    // FIN for
			                  ?>

						
					</div>
			    
		    	<!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
		    		Programas Académicos
		    	</div> -->
		      	
		      
		
		    </div>
		

  	</div>
  	<!-- FIN CICLOS -->
	
	<!-- INPUTS -->
	<div class="col-md-6">
		<div class="card" style="height: 200px">

			<div class="card-header grey darken-1 text-center" role="tab" style="position: relative; z-index: 1;">
		      <a data-toggle="collapse">
		        <h5 class="letraMediana white-text">
		        	Filtros Avanzados
		        </h5>
		      </a>
		    </div>


			<div class="card-body bg-light" style="height: 260px; position: relative; top: -12%; ">
				
				<div class="row" style="text-align: initial;">
					<div class="col-md-6">
						<h5>
				          <span class="badge badge-info my-4" id="contenedor_registros">
				          
				          </span>
				        </h5>


					</div>



					<div class="col-md-6" id="contenedor_buscador">
							
					</div>


					
				</div>

				<!-- CICLOS -->
				<div class="row">
					<div class="col-md-4" >
						
						<input type="radio" class="form-check-input radiosCiclos" name="estatusCiclos" id="estatusCiclo1" value="Vigente" checked>
						<label class="form-check-label letraPequena font-weight-bold" for="estatusCiclo1" style="line-height: -60vh;">
							Vigentes
						</label>

                        
					</div>


					<div class="col-md-4">
						<input type="radio" class="form-check-input radiosCiclos" name="estatusCiclos" id="estatusCiclo2" value="Vencido">
						<label class="form-check-label letraPequena font-weight-bold" for="estatusCiclo2" style="line-height: -60vh;">
							Vencidos
						</label>


                        
					</div>

					<div class="col-md-4">
						
						<input type="radio" class="form-check-input radiosCiclos" name="estatusCiclos" id="estatusCiclo3" value="Todos">
						<label class="form-check-label letraPequena font-weight-bold" for="estatusCiclo3" style="line-height: -60vh;">
							Todos
						</label>
					</div>
				</div>
				<!-- FIN CICLOS -->

				<br>

				<!-- RADIO Y BUSCADOR Y SELECTOR PROFESORES -->
				<div class="row">
					<!-- RADIOS -->
					<div class="col-md-4">
						<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
		                  <input type="radio" class="form-check-input columna radioFechas" id="inicioCobro" columna="8" name="inlineMaterialRadiosExample" checked>
		                  <label class="form-check-label letraPequena font-weight-bold" for="inicioCobro" style="line-height: 100%;">Inicio de ciclo</label>
		                </div>

		   
					</div>


					<div class="col-md-4">
			

		                <div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
		                  <input type="radio" class="form-check-input columna radioFechas" id="finCobro" columna="9" name="inlineMaterialRadiosExample">
		                  <label class="form-check-label letraPequena font-weight-bold" for="finCobro" style="line-height: 100%;">Fin de ciclo</label>
		                </div>
					</div>
					<!-- FIN RADIOS -->

					<!-- BUSCADOR Y SELECTOR -->
					<div class="col-md-4">
						
						<!-- SELECT -->
						<div class="row">
							<div class="col-md-12">
								<?php  
									$sqlProfesor = "
										SELECT *
										FROM profesor
										WHERE id_pla2 = '$plantel'
									";

									//echo $sqlProfesor;
								?>
								<select id="selectorProfesor" columna="2">
									<?php


										$resultadoProfesor = mysqli_query( $db, $sqlProfesor );
										$validadorProfesor = true;

										while($filaProfesor = mysqli_fetch_assoc($resultadoProfesor)){
											if ( $validadorProfesor == true ) {
									?>
												<option value="Vacio" checked>Elige un profesor...</option>
												<option value="<?php echo $filaProfesor['nom_pro'].' '.$filaProfesor['app_pro']; ?>"><?php echo $filaProfesor["nom_pro"].' '.$filaProfesor['app_pro']; ?></option>

									<?php
												$validadorProfesor = false;

											}else{
									?>
												<option value="<?php echo $filaProfesor['nom_pro'].' '.$filaProfesor['app_pro']; ?>"><?php echo $filaProfesor["nom_pro"].' '.$filaProfesor['app_pro']; ?></option>
									<?php
											}
									?>
										
									<?php
										}
									?>
								</select>
							</div>
						</div>
						<!-- FIN SELECT -->


						
						
					</div>
					<!-- FIN BUSCADOR Y SELECTOR -->


				</div>
				<!-- FIN RADIO Y BUSCADOR Y SELECTOR PROFESORES -->

		
				<!-- FECHAS -->
				<div class="row">

					<!-- INICIO -->
					


					<div class="col-md-4"><!-- 
						<p class="letraPequena"><i class="fas fa-search"></i> Búsqueda por fecha</p> -->


						<div class="md-form mb-2" style="position: relative; top: -19%;">
			                <input type="date" id="min-date" class="date-range-filter form-control validate letraPequena font-weight-bold" title="Inicio del Rango">
			            </div>
					</div>
					<!-- FIN INICIO -->

					<!-- FIN -->
					<div class="col-md-4">


						<div class="md-form mb-2" style="position: relative; top: -19%;">
			              <input type="date" id="max-date" class="date-range-filter form-control validate letraPequena font-weight-bold" title="Fin del Rango">
			            </div>
					</div>
					<!-- FIN FIN -->

					<div class="col-md-4">

						<?php  
							$sqlSalon = "
								SELECT *
								FROM salon
								WHERE id_pla11 = '$plantel'
							";

							//echo $sqlSalon;
						?>
						<select id="selectorSalon" columna="5">
							<?php


								$resultadoSalon = mysqli_query( $db, $sqlSalon );
								$validadorSalon = true;

								while($filaSalon = mysqli_fetch_assoc($resultadoSalon)){
									if ( $validadorSalon == true ) {
							?>
										<option value="Vacio" checked>Elige un salón...</option>
										<option value="<?php echo $filaSalon['nom_sal']; ?>"><?php echo $filaSalon["nom_sal"]; ?></option>

							<?php
										$validadorSalon = false;

									}else{
							?>
										<option value="<?php echo $filaSalon['nom_sal']; ?>"><?php echo $filaSalon["nom_sal"]; ?></option>
							<?php
									}
							?>
								
							<?php
								}
							?>
						</select>
						
	
					</div>
				</div>
				<!-- FIN FECHAS -->


				
				
			</div>
		</div>
	</div>
	<!-- FIN INPUTS -->
</div>
<!-- FIN INDICADORES Y FILTROS -->



<!-- BOTON FLOTANTE AGREGAR HORARIO-->
  <a class="btn-floating btn-lg  flotante btn-info" id="agregarHorario"><i class="fas fa-plus" title="Agregar Horario"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR HORARIO-->


<!-- TABLA -->
<div id="contenedor_horarios">
		





</div>


<!-- FIN CONTENIDO -->



<!-- MODAL -->

<!-- AGREGAR HORARIO MODAL -->

<div class="modal fade" id="modalHorario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog modal-fluid" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="myModalLabel">Grupos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="panzaModalHorario">

      	<!-- TITULOS -->
      	<div class="row">

      		<!-- PROGRAMAS -->
      		<div class="col-md-4">
      			
      			<div class="row">
      				<div class="col-md-6 text-left">
	  					Selecciona Programa
	  				</div>
      			</div>
      		</div>
			<!-- FIN PROGRAMAS -->

			<!-- CICLOS -->
      		<div class="col-md-4">
      			<div class="row">
      				<div class="col-md-6 text-left">
	  					Selecciona Ciclo Escolar
	  				</div>
	  				<div class="col-md-6 text-right">
	  					<a class="btn btn-sm btn-info waves-effect" id="btn_agregar_ciclo">
							<i class="fas fa-plus"></i>
							Agregar Ciclo Escolar
	  					</a>
	  				</div>
      			</div>
      			
      		</div>
      		<!-- FIN CICLOS -->
			
			<!-- GRUPOS -->
      		<div class="col-md-4">
      			<div class="row">
      				<div class="col-md-6 text-left">
	  					Selecciona Periodo
	  				</div>
	  				<div class="col-md-6 text-right" id="contenedor_btn_grupo">
	  					
	  				</div>
      			</div>
      			
      		</div>
      		<!-- FIN GRUPOS -->

      	</div>
      	<!-- FIN TITULOS -->

      	<!-- SELECTORES -->
      	<div class="row">
      		<!-- PROGRAMAS -->
      		<div class="col-md-4">
      			<?php  
					$sqlRamas = "
						SELECT * 
						FROM rama 
						WHERE id_pla1 = $plantel
					";
					$resultadoRamas = mysqli_query($db, $sqlRamas);
					
				?>
      			<select id="selectorRama" class="selectoresHorariosPrimarios">
					<?php
						$validadorRama = true;
						while($filaRamas = mysqli_fetch_assoc($resultadoRamas)){
							if ( $validadorRama == true ) {
					?>
								<option value="<?php echo $filaRamas['id_ram']; ?>" checked><?php echo $filaRamas["nom_ram"]." - ".$filaRamas['mod_ram']; ?></option>

					<?php
								$validadorRama = false;
							}else{
					?>
								<option value="<?php echo $filaRamas['id_ram']; ?>" mod_ram="<?php echo $filaRamas['mod_ram']; ?>"><?php echo $filaRamas["nom_ram"]." - ".$filaRamas['mod_ram']; ?></option>
					<?php
							}
					?>
						
					<?php
						}
					?>
				</select>

      		</div>
      		<!-- FIN PROGRAMAS -->

      		<!-- CICLOS -->
			<div class="col-md-4" id="contenedor_ciclos">
      			
      		</div>
      		<!-- FIN CICLOS -->

      		<!-- GRUPOS -->
      		<div class="col-md-4" id="contenedor_grupos">
      			
      		</div>
      		<!-- FIN GRUPOS -->
      	</div>
      	<!-- FIN SELECTORES -->
		
		<!-- CREACION HORARIO -->
      	<div class="row">
			<div class="col-md-12 text-center"  id="contenedor_creacion_horario">
				
			</div>
      	</div>
      	<!-- FIN CREACION HORARIO -->
        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN AGREGAR HORARIO MODAL -->



<!-- MODAL ALUMNOS ONLINE -->
<!-- Central Modal Medium Info -->
<div class="modal fade" id="modalAlumnosOnline" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-notify modal-info" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Alumnos Conectados</p>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
        <div class="text-center" id="contenedorModalAlumnosOnline">
          
        </div>
      </div>

    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Central Modal Medium Info-->
<!-- FIN MODAL ALUMNOS ONLINE -->



<!-- FIN MODAL -->
<?php  

  include('inc/footer.php');

?>

<script>

	obtenerEstatusCiclo();
	function obtenerEstatusCiclo() {


		var estatusCiclo;

		for ( var i = 0, j = 0 ; i < $(".radiosCiclos").length ; i++ ) {

			if ( $(".radiosCiclos")[i].checked == true ) {
				// alert( "el checkbox numero: "+i+" con valor: "+$('.radiosCiclos').eq(i).attr("annio")+" esta seleccionado"  );

				estatusCiclo = $('.radiosCiclos').eq(i).val();
			

			}
		}

		// alert( estatusCiclo );

		obtener_horarios( estatusCiclo );
		
	}


	// obtenerEstatusCiclo();


	$('.radiosCiclos').on('click', function() {
		//event.preventDefault();
		/* Act on the event */

		obtenerEstatusCiclo();
		

	});


	function obtener_horarios( estatusCiclo ){
		$.ajax({
			url: 'server/obtener_horarios.php',
			type: 'POST',
			data: { estatusCiclo },
			success: function(respuesta){

				$("#contenedor_horarios").html(respuesta);
			}
		});
	}

</script>



<script>
	//ALUMNOS ONLINE

	// $("#alumnosOnline").on('click', function(event) {
	// 	event.preventDefault();
	// 	/* Act on the event */

	// 	$.ajax({
	// 		url: 'server/obtener_alumnos_conectados.php',
	// 		type: 'POST',
	// 		success: function(respuesta){

	// 			$("#modalAlumnosOnline").modal("show");
	
	// 			$("#contenedorModalAlumnosOnline").html(respuesta);
	// 		}
	// 	});
		
	// });
</script>

<script>

	//saber si muestro la modal para hacer un update a datatable

	




	//INICIALIZACION DE SELECTORES
	$('.selectoresHorariosPrimarios').materialSelect();

	$("#agregarHorario").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$("#modalHorario").modal('show');


		var id_ram = $("#selectorRama").val();

		obtener_ciclos( id_ram );

		// SELECTOR RAMA
		$("#selectorRama").on('change keyup', function(event) {
			event.preventDefault();
			/* Act on the event */
			var id_ram = $(this).val();

			
			obtener_ciclos( id_ram );

			//alert(id_ram);
		});


		function obtener_ciclos(id_ram){
			$.ajax({
				url: 'server/obtener_ciclos_rama_horarios.php',
				type: 'POST',
				data: {id_ram},
				success: function(respuesta){
				
					$("#contenedor_ciclos").html(respuesta);

				}
			});
		}
		//FIN FUNCION obtener_ciclos


		// AGREGAR CICLOS
		$("#btn_agregar_ciclo").on('click', function(event) {
			event.preventDefault();
			/* Act on the event */

			$.ajax({
				url: 'server/obtener_formulario_agregar_ciclo.php',
				type: 'POST',
				data: {id_ram},
				success: function(respuesta){
				
					$("#contenedor_creacion_horario").html(respuesta);

				}
			});

		});




	});
</script>