<?php  

	include('inc/header.php');
	muerteCiclos();

	$id_enc = obtener_validacion_encuesta( $id, $cadena, $plantel );

	echo $id_enc;

	if ( $id_enc > 0 ) {
		
		header('location: encuesta.php?id_enc='.$id_enc);

	}
	
?>
<!-- TITULO -->
<div class="row ">
	<div class="col-md-12">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Inicio"><i class="fas fa-bookmark"></i> Inicio</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Estás aquí"><span class="text-white">Inicio</span></a>

		</div>
		
	</div>
		
</div>
<!-- FIN TITULO -->


<!-- MODAL FILTROS -->
<div class="modal fade text-left" id="modal_filtros_columna">
  	
  	<div class="modal-dialog modal-lg" role="document">
    
		    
		    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
		      	
		      	<div class="modal-header text-center" style="position: relative;">

		            <div class="row">
		                <div class="col-md-12">
		                  
		                	<span class=" letraGrande">  
			        			Filtros
			                </span>

		                </div>
		            </div>
		              
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
		                <span aria-hidden="true">&times;</span>
		            </button>

		      	</div>
		      	
		      	<div class="row">
		      		<div class="col-md-6">
		      			<!--  -->
		      			<div class="modal-body" id="contenedor_filtros_columna1">

				      		<span class="letraPequena grey-text">Filtros de columna 1</span>


				      		<div class="row">
								<div class="col-md-12 text-center">
									<!--  -->
									
								    		<div class="form-check form-check-inline">
											  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22" name="seleccionPeriodo" value="Mes" checked="">
											  	<label class="form-check-label letraPequena" for="materialGroupExample22">Mensual</label>
											</div>


											<!-- Group of material radios - option 1 -->
											<div class="form-check form-check-inline">
											  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11" name="seleccionPeriodo"  value="Fecha">
											  	<label class="form-check-label letraPequena" for="materialGroupExample11">Día(s)</label>
											</div>

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
															<div class="col-md-8">

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


															<div class="col-md-4">
																
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
															<!--  -->
														</div>
													<!--  -->
													</div>


													<!-- SEMANA Y LIBRE -->
													<div id="contenedor_fecha" style="display: none;">


														<div class="row">
															<div class="col-md-6">

														        	<input type="date" class="form-control filtros letraPequena" id="inicio" value="<?php echo date('Y-m-d'); ?>">
														        
														        
															</div>

															<div class="col-md-6">

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
									<!--  -->
								</div>

							</div>			      		
				      	</div>
		      			<!--  -->
		      		</div>

		      		<div class="col-md-6">
		      			<!--  -->
		      			<div class="modal-body" id="contenedor_filtros_columna2">

				      		<span class="letraPequena grey-text">Filtros de columna 2</span>


				      		<div class="row">

								<div class="col-md-12 text-center">
									<!--  -->
									
								    		<div class="form-check form-check-inline">
											  	<input type="radio" class="form-check-input radioPeriodo2" id="materialGroupExample222" name="seleccionPeriodo2" value="Mes" checked="">
											  	<label class="form-check-label letraPequena" for="materialGroupExample222">Mensual</label>
											</div>


											<!-- Group of material radios - option 1 -->
											<div class="form-check form-check-inline">
											  	<input type="radio" class="form-check-input radioPeriodo2" id="materialGroupExample112" name="seleccionPeriodo2"  value="Fecha">
											  	<label class="form-check-label letraPequena" for="materialGroupExample112">Fechas</label>
											</div>

											<!-- Group of material radios - option 2 -->
											<div class="form-check form-check-inline">
											  	<input type="radio" class="form-check-input radioPeriodo2" id="materialGroupExample232" name="seleccionPeriodo2" value="Semana">
											  	<label class="form-check-label letraPequena" for="materialGroupExample232">Semanal</label>
											</div>

											<hr>


											<!--  -->
								            <div class="row">

												<div class="col-md-12">
												<!--  -->

													<div id="contenedor_mes_annio2" style="display: none;">
													<!--  -->
														<div class="row">
														
															<!--  -->
															<div class="col-md-8">

																<select class="browser-default custom-select letraPequena filtros2" id="selectorMes2">

																<!--  -->
																  	<?php
																  		
																  		
																  		$mesAnterior = strtotime('-1 month', strtotime(date('Y-m-d')));
																		$mesAnteriorFormateado = date('m', $mesAnterior);

																		$mesActualEntero = $mesAnteriorFormateado;

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


															<div class="col-md-4">
																
																<select class="browser-default custom-select letraPequena filtros2" id="selectorAnnio2">

																<!--  -->
																  	<?php
																  		

																		$annioActual = date('Y');
																		$i = 2018;
																		$annioFuturo = $annioActual+2;
																	    
																	    while( $i < $annioFuturo ) {

																	        
																	?>

																		<?php  
																			if ( $i == ($annioActual - 1) ) {
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
															<!--  -->
														</div>
													<!--  -->
													</div>


													<!-- SEMANA Y LIBRE -->
													<div id="contenedor_fecha2" style="display: none;">


														<div class="row">
															<div class="col-md-6">

														        	<input type="date" class="form-control filtros2 letraPequena" id="inicio2" value="<?php echo date('Y-m-d'); ?>">
														        
														        
															</div>

															<div class="col-md-6">

																	<input type="date" class="form-control filtros2 letraPequena" id="fin2" value="<?php echo date('Y-m-d'); ?>">
														        
															</div>
														</div>

													</div>

													<div id="contenedor_semana2" style="display: none;">
														
														

														
														<select class="browser-default custom-select letraPequena filtros2" id="selectorSemana2">
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
									<!--  -->
								</div>

							</div>			      		
				      	</div>
		      			<!--  -->
		      		</div>
		      	</div>
		      	


		      	


		    	<div class="modal-footer d-flex justify-content-center">
		    	

                	<a class="btn bg-grey white-text btn-rounded waves-effect btn-sm" title="Cerrar..." data-dismiss="modal">
                    	Cerrar
                	</a>    


		    	</div>

		    </div>

  	</div>

</div>
<!-- FIN MODAL FILTROS -->




<hr>

<div class="row">
	<div class="col-md-12">
		<div class="card" style="border-radius: 20px;">
			
			<div class="card-body">

				<div class="bc-icons">

				

				  <nav aria-label="breadcrumb">

				  	
				    <ol class="breadcrumb elegant-color">
				     	

				      	<li class="breadcrumb-item">
				      		<a class="white-text btn-link waves-effect" href="index.php"><i class="fas fa-home"></i> Inicio - <?php echo $lugar; ?></a>
				      	</li>
					    
					    <li class="breadcrumb-item">
					      	<a class="white-text btn-link waves-effect" href="reportes.php"><i class="fas fa-chart-line"></i> Reportes generales</a>
					    </li>

					    <li class="breadcrumb-item">
				      		<a class="white-text btn-link waves-effect" href="alumnos.php"><i class="fas fa-users"></i> Alumnos</a>
				      	</li>

				      	<li class="breadcrumb-item">
				      		<?php  
				      			if ( $tipo == 'Super' ) {
				      		?>
				      				<a class="white-text btn-link waves-effect" href="horarios2.php"><i class="fas fa-graduation-cap"></i> Horarios</a>
				      		<?php
				      			} else {
				      		?>
				      				<a class="white-text btn-link waves-effect" href="horarios.php"><i class="fas fa-graduation-cap"></i> Horarios</a>
				      		<?php
				      			}
				      		?>
				      	</li>

				      	<li class="breadcrumb-item">
				      		<a class="white-text btn-link waves-effect" href="cobranza.php"><i class="fas fa-dollar-sign"></i> Cobranza</a>
				      	</li>


				      	<li class="breadcrumb-item">
				      		<a class="white-text btn-link waves-effect" href="egresos.php"><i class="fas fa-money-check-alt"></i> Egresos</a>
				      	</li>

				      	<li class="breadcrumb-item">
				      		<a class="white-text btn-link waves-effect" href="area_comercial.php"><i class="fas fa-clipboard-list"></i> Área comercial</a>
				      	</li>


				      	

				    </ol>
				  </nav>

				</div>

				<a href="#" class="btn-link btn-sm letraMediana text-primary btn_columna" title="Filtros">
					Filtros de dashboard
				</a>
				<div class="row">
					<div class="col-md-8">
						<div id="contenedor_datos">
		        			N/A
		        		</div>
					</div>

					<div class="col-md-4">
						<div id="contenedor_ranking">N/A</div>
					</div>
				</div>
				
				

			</div>

			

		</div>
	</div>
</div>



<?php  

	include('inc/footer.php');

?>


<script>
	// $('#contenedor_datos').html('contenedor_datos');
	
	// COLUMNA 1
	$('.radioPeriodo').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		console.log('radioPeriodo');

		obtener_datos();
		// alert( 'radioReporte' );

	});

	
    $('.filtros').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_datos();
		// alert( radioReporte );

	});

    // FIN COLUMNA 1

    // COLUMNA 2
	$('.radioPeriodo2').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		console.log('radioPeriodo');

		obtener_datos2();
		// alert( 'radioReporte' );

	});

	
    $('.filtros2').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_datos2();
		// alert( radioReporte );

	});
	// FIN COLUMNA 2


	obtener_datos();

	// obtener_datos2();

	function obtener_datos(){

			
			
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

        //obtener_dashboard( inicio, fin );
		// FIN PLANTELES

			

	}


	function obtener_datos2(){

		setTimeout( function(){
			
			
			var radioPeriodo = $(".radioPeriodo2:checked").val();

			// FECHAS
			if ( radioPeriodo == 'Fecha' ) {

				var inicio = $('#inicio').val();
				var fin = $('#fin').val();

				$('#contenedor_fecha2').css('display', '');
				$('#contenedor_semana2').css( 'display', 'none' );
				$('#contenedor_mes_annio2').css( 'display', 'none' );

		
			} else if ( radioPeriodo == 'Semana' ) {

				var inicio = $('#selectorSemana2 option:selected').attr('inicio');
				var fin = $('#selectorSemana2 option:selected').attr('fin');

				$('#contenedor_mes_annio2').css( 'display', 'none' );
				$('#contenedor_fecha2').css('display', 'none');
				$('#contenedor_semana2').css( 'display', '' );


			} else if ( radioPeriodo == 'Mes' ) {


				$('#contenedor_mes_annio2').css( 'display', '' );
				$('#contenedor_fecha2').css('display', 'none');
				$('#contenedor_semana2').css( 'display', 'none' );

				var diaInicio = $('#selectorMes2 option:selected').attr('inicio');
				var diaFin = $('#selectorMes2 option:selected').attr('fin');
				var mes = $('#selectorMes2 option:selected').val();
				var annio = $('#selectorAnnio2 option:selected').val();
				
				var inicio = annio+'-'+mes+'-'+diaInicio;
				var fin = annio+'-'+mes+'-'+diaFin;


			}
			// FIN FECHAS

	        obtener_dashboard2( inicio, fin );
			// FIN PLANTELES
		}, 500 );
			
	}

	//function obtener_dashboard( inicio, fin ){
		// console.log('rep comercial');
		//$('#contenedor_datos').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');
		//var token = 'abc';

		//$.ajax({
		//	url: 'server/obtener_dashboard.php',
		//	type: 'POST',
		//	data: { inicio, fin, token },
		//	success: function( respuesta ){

		//		$('#contenedor_datos').html( respuesta );
		//		console.log( respuesta );
		//	}
		//});

	//}

	function obtener_dashboard2( inicio, fin ){
		// console.log('rep comercial');
		$('#contenedor_datos2').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');
		var token = 'xyz';

		$.ajax({
			url: 'server/obtener_dashboard.php',
			type: 'POST',
			data: { inicio, fin, token },
			success: function( respuesta ){
				$('#contenedor_datos2').html( respuesta );
				console.log( respuesta );
			}
		});

	}


	//obtener_ranking( '<?php echo date('Y-m-d'); ?>', '<?php echo date('Y-m-d'); ?>' );

	//function obtener_ranking( inicio, fin ){
		// console.log('rep comercial');
	//	$('#contenedor_ranking').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');

	//	$.ajax({
	//		url: 'server/obtener_ranking.php',
	//		type: 'POST',
	//		data: { inicio, fin },
	//		success: function( respuesta ){
	//			$('#contenedor_ranking').html( respuesta );
	//			console.log( respuesta );
	//		}
	//	});

	//}



</script>

<script>
	$('.btn_columna').on('click', function(event) {
		event.preventDefault();

		$('#modal_filtros_columna').modal('show');

	});
</script>

<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - Inicio');
</script>