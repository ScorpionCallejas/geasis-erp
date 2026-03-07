<?php 
	//ARCHIVO VIA AJAX PARA OBTENER  PREGUNTAS DE EXAMEN
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_exa_cop = $_POST['id_exa_cop'];

	$fila = obtenerDatosActividadGrupoServer( $id_exa_cop, 'Examen', 'arreglo' );
	$id_exa = $fila['identificador'];

	$sql = "
		SELECT *
		FROM pregunta
		WHERE id_exa2 = '$id_exa'
		ORDER BY id_pre DESC
	";

	$resultado = mysqli_query( $db, $sql );
	$resultadoTotal = mysqli_query( $db, $sql );

	$total = mysqli_num_rows( $resultadoTotal );
	$i = 1;

	if ( $total > 0 ) {

	while( $fila = mysqli_fetch_assoc( $resultado ) ){
?>
		<div class="row">
							
			<div class="col-md-12">

				<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
					
					<div class="card-header z-depth-1 bg-white" style="border-radius: 20px;">
						<div class="row p-2  clasePadre">
															
							<div class="claseHijoNumeracion font-weight-bold">
								<div class="claseTextoHijoNumeracion">
									<?php echo $total; ?>
								</div>
									
							</div>

							<div class="col-md-6">
								
								<?php echo $fila['pre_pre']; ?>
							</div>

							<div class="col-md-5 text-right">
								<p class="letraMediana grey-text">
						      		<?php echo "Valor: +".$fila['pun_pre']; ?>
						      	</p>
								
							</div>

							<div class="col-md-1">

								<!--Dropdown primary-->
								<div class="dropdown">

								  <!--Trigger-->

									<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
										<i class="fas fa-ellipsis-v grey-text"></i>
					    			</a>


								  <!--Menu-->
									<div class="dropdown-menu dropdown-info">


										<a class="dropdown-item waves-effect abrirVentanaRespuesta" ventanaRespuesta="ventanaRespuesta<?php echo $i.$fila['id_pre']; ?>">
											Agregar respuesta
										</a>


										<a class="dropdown-item waves-effect edicionPregunta" id_pre="<?php echo $fila['id_pre']; ?>" id_exa="<?php echo $id_exa; ?>">
											Editar pregunta
										</a>
										

										<a class="dropdown-item waves-effect eliminacionPregunta" id_pre="<?php echo $fila['id_pre']; ?>" href="#">
											Eliminar pregunta
										</a>

									</div>
								</div>
								<!--/Dropdown primary-->

							</div>
						</div>
						
					</div>
					
					<div class="body" style="border-radius: 20px;">
						
						
						<div class="row clasePadre">
							<div class="col-md-12" id="contenedor_respuestas<?php echo $i.$fila['id_pre']; ?>">
								

								<?php

									$id_pre1 = $fila['id_pre'];

									$sqlRespuesta = "
										SELECT *
										FROM respuesta
										WHERE id_pre1 = '$id_pre1'
										ORDER BY id_res DESC
									";

									// echo $sqlRespuesta;

									$resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );
									$resultadoTotalRespuesta = mysqli_query( $db, $sqlRespuesta );

									$totalRespuesta = mysqli_num_rows( $resultadoTotalRespuesta );

									if ( $totalRespuesta > 0 ) {

										$j = 1;
										
										while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ){
								
								?>
										<div class="row p-2">
											<div class="col-md-1"></div>
											<div class="col-md-10">

												<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
													
													<div class="card-body z-depth-1 bg-white" style="border-radius: 20px;">

														<div class="row clasePadre">
															
															<div class="claseHijoNumeracion font-weight-bold">
																<div class="claseTextoHijoNumeracion">
																	<?php echo $totalRespuesta; ?>
																</div>
																	
															</div>

															<div class="col-md-10">
																<?php 
																	echo $filaRespuesta['res_res']."  <br>(".$filaRespuesta['val_res'].")"; 
																?>
															</div>

															<div class="col-md-2">
																
																<!--Dropdown primary-->
																<div class="dropdown">

																  <!--Trigger-->

																	<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
																		<i class="fas fa-ellipsis-v grey-text"></i>
													    			</a>


																  <!--Menu-->
																	<div class="dropdown-menu dropdown-info">
															
																		<a class="dropdown-item waves-effect eliminacionRespuesta" id_res="<?php echo $filaRespuesta['id_res']; ?>" selector="contenedor_respuestas<?php echo $i.$fila['id_pre']; ?>" id_pre="<?php echo $fila['id_pre']; ?>" href="#">
																			Eliminar respuesta
																		</a>

																	</div>
																</div>
																<!--/Dropdown primary-->
																
															</div>
														</div>
														
													</div>

												</div>

											</div>
											<div class="col-md-1"></div>
										</div>


								<?php
										$totalRespuesta--;
										}
								?>

								<?php  
									} else {
								?>

										<div class="row p-2">
															
											<div class="col-md-12 text-center">
														
												<br>
												<br>
												<h5>
													<span class="badge badge-warning">
														¡No hay respuestas!
													</span>
												</h5>
												
												<img src="../img/sentado.gif" width="10%" class="animated tada delay-2s">
												
												
												<br>
												<br>


												<h6>
													<span class="badge badge-warning">
														¡Agrega al menos dos!
													</span>
												</h6>

											</div>

										</div>
										


								<?php  
									}
								?>
								

							</div>

							<!-- <div class="col-md-6"> -->
							<!-- AGREGADO RESPUESTAS -->

								<!-- <div class="row"> -->
							
									<!-- <div class="col-md-12"> -->
										
										<?php  
											if ( $i == 1 ) {
										?>
												<div class="card grey ventanaRespuestaDraggable" style="border-radius: 20px; position: absolute; z-index: 100; width: 300px; display: ''; top: -11%; right: -15%;" id="ventanaRespuesta<?php echo $i.$fila['id_pre']; ?>">
										<?php
											} else {
										?>
												<div class="card grey ventanaRespuestaDraggable" style="border-radius: 20px; position: absolute; z-index: 100; width: 300px; display: none; top: -11%; right: -15%;" id="ventanaRespuesta<?php echo $i.$fila['id_pre']; ?>">

										<?php 
											}
										?>
										

											<div class="card-header ventanaRespuestaDraggableManejador grey" style="border-radius: 20px; ">
												<div class="row">
													<div class="col-md-12 text-center white-text letraMediana">
														Mover ventana ( pregunta #<?php echo $total; ?> )
														
														
													</div>
												</div>
											</div>
											
											<div class="body bg-white p-2" style="border-radius: 20px;">
												
												<div class="row">
													
													<div class="col-md-12">
													
														
														<p class="letraPequena grey-text">
												      		* Define entre <strong>'Verdadero'</strong> o <strong>'Falso'</strong>
												      	</p>
														<!-- Group of material radios - option 1 -->
														<select class="mdb-select md-form colorful-select dropdown-info" id="val_res<?php echo $i.$fila['id_pre']; ?>">
															<option value="Verdadero">Verdadero</option>
															<option value="Falso">Falso</option>
														</select>

														<p class="letraPequena grey-text">
												      		* Define tu respuesta
												      	</p>

														<div class="md-form mb-2">
															
															<div id="res_res<?php echo $i.$fila['id_pre']; ?>">    
											        			Tu respuesta va aquí...
												        	</div>
										          		
										          		</div>

														<div class="row">
															<div class="col-md-12 text-center">
																<a class="btn btn-info white-text btn-rounded waves-effect btn-sm" id="agregarRespuesta<?php echo $i.$fila['id_pre']; ?>">
																	Agregar respuesta
																</a>
												          		
												          		<a class="btn grey white-text btn-rounded waves-effect btn-sm cerrarVentanaRespuesta" title="Cierra esta ventana, puedes abrirla en las opciones de pregunta">
																	Cerrar
																</a>
															</div>
														</div>
								          				

										          		

														
													</div>

													
												</div>
												
													
												

											</div>
										</div>
									<!-- </div> -->
								<!-- </div> -->
							<!-- FIN AGREADO RESPUESTAS -->
							<!-- </div> -->


						</div>
					</div>

				</div>					
			
			</div>

		</div>

		<hr>


		<script>
			var res_res<?php echo $i.$fila['id_pre']; ?> = new Jodit("#res_res<?php echo $i.$fila['id_pre']; ?>	", {
		        "language": "es",
		        "uploader": {
					"insertImageAsBase64URI": true
				},
				toolbarButtonSize: "small"

		    });

			$( '#agregarRespuesta<?php echo $i.$fila['id_pre']; ?>' ).on('click', function(event) {
				event.preventDefault();
				/* Act on the event */

				var id_pre = '<?php echo $fila['id_pre']; ?>';

				var res_res = res_res<?php echo $i.$fila['id_pre']; ?>.value;
				var val_res = $( '#val_res<?php echo $i.$fila['id_pre']; ?>' ).val();

				var selector = 'contenedor_respuestas<?php echo $i.$fila['id_pre']; ?>';

				$.ajax({
					url: 'server/agregar_respuesta.php',
					type: 'POST',
					data: { res_res, val_res, id_pre },
					success: function( respuesta ){

						// $( '#contenedor_respuestas'+posicion+id_pre ).html( respuesta );
						if ( respuesta == 'Exito' ) {

							generarAlerta( 'Cambios guardados' );
							obtenerRespuestasPregunta( selector, id_pre );
						
						} else {
						
							console.log( respuesta );
						
						}
						

					}
				});
				

			});
		</script>

		
<?php
	$total--;
	$i++;
	}
?>


<script>
	$('.mdb-select').materialSelect('destroy');
	$('.mdb-select').materialSelect();

	$( '.ventanaRespuestaDraggable' ).draggable({
		handle: ".card-header"
	});

	$( '.cerrarVentanaRespuesta' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( this ).parent().parent().parent().parent().parent().parent().css({
			display: 'none'
		});
	});

	$( '.abrirVentanaRespuesta' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var ventanaRespuesta = $( this ).attr( 'ventanaRespuesta' );
		$( '#'+ventanaRespuesta+'' ).css({
			display: ''
		});
	});

	function obtenerRespuestasPregunta( selector, id_pre ){

		$.ajax({
			url: 'server/obtener_respuestas_pregunta.php',
			type: 'POST',
			data: { id_pre, selector },
			success: function( respuesta ){

				// $( '#contenedor_respuestas'+posicion+id_pre ).html( respuesta );
				// console.log( respuesta );
				$( '#'+selector+'' ).html( respuesta );
				
			}
		});

		// $( '#contenedor_respuestas'+posicion+id_pre ).html( respuesta );
	
	}



	//ELIMINACION DE PREGUNTA
  $('.eliminacionPregunta').on('click', function(event) {
      event.preventDefault();
      /* Act on the event */

      console.log( 'click eliminacion respuesta' );

      var id_pre = $(this).attr("id_pre");

      var id_exa_cop = $( '#agregarExamenModal' ).attr( 'id_exa' );
      
      // console.log(PREGUNTA);

    swal({
          title: "¿Deseas eliminar esta pregunta?",
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
            url: 'server/eliminacion_pregunta.php',
            type: 'POST',
            data: { id_pre },
            success: function(respuesta){
              console.log( respuesta );

              if (respuesta == "true") {
                console.log("Exito en consulta");
                swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {

                	obtener_preguntas_examen( id_exa_cop );
                	generarAlerta( 'Cambios guardados' );
                
                });
              }else{
                console.log(respuesta);

              }

            }
          });
            
          }
      });
  });






  //ELIMINACION DE RESPUESTA
  $('.eliminacionRespuesta').on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var id_res = $( this ).attr( "id_res" );
      var selector = $( this ).attr( 'selector' );
      var id_pre = $( this ).attr( 'id_pre' );
      // console.log(RESPUESTA);

    swal({
          title: "¿Deseas eliminar esta respuesta?",
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
            url: 'server/eliminacion_respuesta.php',
            type: 'POST',
            data: {id_res},
            success: function(respuesta){
              
              if (respuesta == "true") {
                console.log("Exito en consulta");
                swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {

                	generarAlerta( 'Cambios guardados' );
					obtenerRespuestasPregunta( selector, id_pre );
                
                });
              }else{
                console.log(respuesta);

              }

            }
          });
            
          }
      });
  });
</script>



<script>
	$('.edicionPregunta').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var id_pre = $(this).attr('id_pre');
		var id_exa = $(this).attr('id_exa');

		generarAlerta('Editar pregunta');


		$.ajax({
			url: 'server/obtener_pregunta.php',
			type: 'POST',
			dataType: 'json',
			data: { id_pre },
			success: function(datos){

				

				setTimeout(function() {
					pregunta.selection.focus();
					

					$('#puntaje').attr({value: datos.pun_pre});
					pregunta.value = datos.pre_pre;

					$('#agregarExamenModal').animate({ scrollTop: 0 }, 'slow');

				}, 100);
				
				$('#agregarPregunta').removeAttr('estatus').attr( 'estatus', 'Editar' ).attr('id_pre', id_pre).removeAttr('id_exa').attr('id_exa', id_exa);
			}
		});
		



	});
</script>



<?php
	} else {
?>
		<div class="row"><div class="col-md-12 text-center"><div class="card z-depth-1 bg-white" style="border-radius: 20px;"><div class="card-body z-depth-1 bg-white" style="border-radius: 20px;"><h5><span class="badge badge-warning">¡No hay preguntas!</span></h5><!-- <br>--><img src="../img/acostado.gif" width="15%" class="animated tada delay-3s"></div></div></div></div>

<?php  
	}
?>