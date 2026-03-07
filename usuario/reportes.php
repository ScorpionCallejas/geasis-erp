<?php  

	include('inc/header.php');

?>

<style>
	#myTable td{
	
		font-size: 10px;
	
	}

	.dropdown-menu {
        max-height: 10vw;
        overflow-y: auto;
    }
</style>


<!-- CONSULTA DE HISTORIAL DE PAGO -->
<div class="modal fade" id="modalHistorialPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog" role="document">


    <div class="modal-content" style="border-radius: 20px;">
      
      <div class="modal-header text-center">
        <h4 class="modal-title w-100" id="myModalLabel">Consulta de pago <span id="titulo_medio_historial"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="panzaModalHistorialPago">
        


        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm btn-rounded waves-effect" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA DE HISTORIAL DE PAGO -->
<!-- TITULO -->
<div class="row ">
	
	<div class="col text-left">
		
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="reportes waves-effect">
			<i class="fas fa-bookmark"></i> 
			Reportes
		</span>
	
	</div>

</div>

<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Reportes</a>
</div>
<!-- FIN TITULO -->




<!-- CONTENEDOR -->

<div class="row">
	<div class="col-md-12">
		<div class="bg-white border rounded-5">
			<ul class="nav nav-tabs m-2" id="myTab" role="tablist">
			  <li class="nav-item">
			    <a class="nav-link active reportes waves-effect" id="reporte_comercial" data-toggle="tab" href="#reporte_comercial" role="tab" aria-controls="reporte_comercial"
			      aria-selected="true">Comercial</a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link reportes waves-effect" id="reporte_alumnos" data-toggle="tab" href="#reporte_alumnos" role="tab" aria-controls="reporte_alumnos"
			      aria-selected="false">Alumnos</a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link reportes waves-effect" id="reporte_cobranza" data-toggle="tab" href="#reporte_cobranza" role="tab" aria-controls="reporte_cobranza"
			      aria-selected="false">Cobranza</a>
			  </li>


			  <li class="nav-item">
			    <a class="nav-link reportes waves-effect" id="reporte_egresos" data-toggle="tab" href="#reporte_egresos" role="tab" aria-controls="reporte_egresos"
			      aria-selected="false">Egresos</a>
			  </li>

			  <li class="nav-item">
			    <a class="nav-link reportes waves-effect" id="reporte_grupos" data-toggle="tab" href="#reporte_grupos" role="tab" aria-controls="reporte_grupos"
			      aria-selected="false">Grupos</a>
			  </li>
			</ul>
		</div>
		
	</div>	
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<!--  -->

				<div class="row">
					<div class="col-md-4 border">
						<!-- FECHAS -->
						<!-- CONTENEDOR REPORTE -->
						<br>
						<div class="row">
																	
							<div class="col-md-12">


								<div style="">
									
									<div class="form-check form-check-inline">
									  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22" name="seleccionPeriodo" value="Mes">
									  	<label class="form-check-label letraPequena" for="materialGroupExample22">Mensual</label>
									</div>


									<!-- Group of material radios - option 1 -->
									<div class="form-check form-check-inline">
									  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11" name="seleccionPeriodo"  value="Fecha" checked="">
									  	<label class="form-check-label letraPequena" for="materialGroupExample11">Día(s)</label>
									</div>

									<!-- Group of material radios - option 2 -->
									<div class="form-check form-check-inline">
									  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample23" name="seleccionPeriodo" value="Semana">
									  	<label class="form-check-label letraPequena" for="materialGroupExample23">Semanal</label>
									</div>
								
								</div>
								
							</div>
						</div>

						<br>


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
						<!-- FIN CONTENEDOR REPORTE -->

						<!-- FIN FECHAS -->
					</div>

					<div class="col-md-4 border">
						<!-- ESCALA -->
						<br>
						<div class="row">
                            <div class="col-md-12">

                                <input type="checkbox" class="form-check-input" id="seleccionPlanteles" checked="checked">
                                <label class="form-check-label letraPequena" for="seleccionPlanteles" style="font-size: 10px;">
                                    Todo
                                </label>
                                
                            </div>
                        </div>

                        <hr>

                        <div class=" scrollspy-example" style=" height: 100px;">
                            
                            
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

                                        for ( $i = 0 ; $i < $totalPlantel ; $i++ ) {
                            ?>

                                          	<div class="row">
                            <?php  
                                              	while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                            ?>
                            						<?php 
		                            					if ( ( $tipo == 'Super' ) && ( $contadorPlantel == 1 ) ) {
		                            				?>

		                            						<div class="col-md-12">
		                                                    
		                                                        <input type="checkbox" class="form-check-input checkboxPlanteles" id="plantel0" value="0" checked="checked">
		                                                        <label class="form-check-label letraPequena" for="plantel0" style="font-size: 10px;">

		                                                            <?php echo $lugar; ?>

		                                                        </label>

		                                                  	</div>
		                            				<?php          	
						                            	} 
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
						<!-- FIN ESCALA -->
					</div>

					<div class="col-md-4 border" id="contenedor_filtros_alumnos" style="display: none;">
						
						<!-- GENERAL -->

							<span class="letraPequena p-2 font-weight-bold">
								Estatus
							</span>

							<div class="card-body scrollspy-example" style=" height: 200px;">

								<div class="row">
									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Certificado" href="#" style="width: 60px;" title="Alumnos que han concluido su programa completo">
												Certificados
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_cer">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Graduado" href="#" style="width: 60px;" title="Alumnos que aprobaron las materias de su programa académico">
												Graduados
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_gra">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Fin" href="#" style="width: 60px;" title="Alumnos finalizaron su curso">
												Fin curso
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_fin">
											</span>
										</h6>

									</div>

									
								</div>

								<div class="row">
									
									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Activo" href="#" style="width: 60px;" title="Alumnos que pagaron su primer colegiatura">
												Activos
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_act">
											</span>
										</h6>
									</div>
									
									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Registro" href="#" style="width: 60px;" title="Alumnos que aun no paga su primera colegiatura">
												Registros
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_reg">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Promocionado" href="#" style="width: 60px;" title="Alumnos que aun no paga nada">
												Promocionado
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_apa">
											</span>
										</h6>
									</div>

								</div>


								<div class="row">
									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Anticipado" href="#" style="width: 60px;" title="Alumnos que ya pagaron y no han comenzado">
												Anticipado
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_ant">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="NP" href="#" style="width: 60px;" title="Alumnos que pagaron Insc. pero no colegiatura y ya arrancó el curso">
												NP
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_np">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Bloqueado" href="#" style="width: 60px;" title="Alumnos que adeudan un mes">
												Bloqueado
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_blo">
											</span>
										</h6>
									</div>
								</div>



								<div class="row">
									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Baja" href="#" style="width: 60px;" title="Alumnos con baja definitiva">
												Baja
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_baj">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Reingreso" href="#" style="width: 60px;" title="Alumnos que reingresan">
												Reingreso
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_rei">
											</span>
										</h6>
									</div>

									<div class="col">
										<h6 class="letraNumerica">
											<a class="btn-light btn-rounded btn-sm letraPequena waves-effect seleccionEstatus" switch="falso" tipo_estatus="estatus_general" estatus="Suspendido" href="#" style="width: 60px;" title="Alumnos que adeudan más de un mes">
												Suspendido
											</a>
											<br>
											<span class="font-weight-bold" id="est_gen_sus">
											</span>
										</h6>
									</div>
								</div>
								

								
								

							</div>
							
						<!-- FIN GENERAL -->

					</div>
				</div>
				
				<!--  -->
			</div>
		</div>
	</div>
</div>

<hr>

<div id="contenedor_datos" style="position: relative;">
	
</div>
<!-- FIN CONTENEDOR -->







<?php  

	include('inc/footer.php');

?>

<script>


	// ESTATUS GENERAL
	$('.seleccionEstatus').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        if ( $(this).hasClass('btn-light') ){
        
            $(this).removeClass('btn-light').addClass('btn-info').removeAttr('switch').attr( 'switch', 'verdadero' );
        	obtener_datos();

        } else if ( $(this).hasClass('btn-info') ) {

            $(this).removeClass('btn-info').addClass('btn-light').removeAttr('switch').attr( 'switch', 'falso' );

            obtener_datos();
        
        }

	});

	// FIN ESTATUS GENERAL

	$('.radioPeriodo').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_datos();
		// alert( radioReporte );

	});

	$('.checkboxPlanteles').on('click', function() {
   
        obtener_datos();    

    });


    $('.filtros').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_datos();
		// alert( radioReporte );

	});

	$('.reportes').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		obtener_datos();
	
		
	});




	obtener_datos();

	function obtener_datos(){

		setTimeout( function(){
			
			for( var i = 0; i < $('.reportes').length; i++ ){
				if ( $('.reportes').eq( i ).hasClass('active') ) {

					var reporte = $('.reportes').eq( i ).attr('aria-controls');
				}

			}
			// alert( reporte );

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


			// PLANTELES
			var id_pla = [];

	        for ( var i = 0, j = 0 ; i < $(".checkboxPlanteles").length ; i++ ) {

	            if ( $(".checkboxPlanteles")[i].checked == true ) {
	                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxPlanteles').eq(i).attr("annio")+" esta seleccionado"  );

	                id_pla[j] = $('.checkboxPlanteles').eq(i).val();

	                j++;

	            }
	        }


	        // ESTATUS GENERAL
	        var tipo_estatus = [];
	        var estatus = [];

	        for ( var i = 0, j = 0 ; i < $(".seleccionEstatus").length ; i++ ) {

	            if ( $(".seleccionEstatus").eq(i).attr('switch') == 'verdadero' ) {

	                tipo_estatus[j] = $('.seleccionEstatus').eq(i).attr('tipo_estatus');
	                estatus[j] = $('.seleccionEstatus').eq(i).attr('estatus');

	                j++;

	            }
	        }


	        // alert( estatus.length );
	        // FIN ESTATUS GENERAL

	        if ( id_pla.length == 0 ) {

	            swal("¡No hay planteles seleccionados!", "Selecciona al menos uno para continuar", "info", {button: "Aceptar",});
	            
	        } else {
	            
	            if ( reporte == 'reporte_comercial' ) {
	            
	            	obtener_reporte_comercial( inicio, fin, id_pla );

	            	$('#contenedor_filtros_alumnos').css('display', 'none');
	            
	            } else if ( reporte == 'reporte_alumnos' ) {

	            	obtener_reporte_alumnos( inicio, fin, id_pla, estatus, tipo_estatus );
	            	$('#contenedor_filtros_alumnos').css('display', '');
	            
	            } else if ( reporte == 'reporte_cobranza' ) {

	            	obtener_reporte_cobranza2( inicio, fin, id_pla );
	            	$('#contenedor_filtros_alumnos').css('display', 'none');
	            
	            } else if ( reporte == 'reporte_grupos' ) {


	            	obtener_reporte_grupos( inicio, fin, id_pla );
	            	$('#contenedor_filtros_alumnos').css('display', 'none');
	            
	            } else if ( reporte == 'reporte_egresos' ) {

	            	obtener_reporte_egresos( inicio, fin, id_pla );
	            	$('#contenedor_filtros_alumnos').css('display', 'none');
	            }
	        
	        }
			// FIN PLANTELES
		}, 500 );
			

	}


	function obtener_reporte_comercial( inicio, fin, id_pla ){

		$('#contenedor_datos').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');

		$.ajax({
			url: 'server/obtener_reporte_comercial.php',
			type: 'POST',
			data: { inicio, fin, id_pla },
			success: function( respuesta ){
				$('#contenedor_datos').html( respuesta );
				// console.log( respuesta );
			}
		});

	}



	function obtener_reporte_alumnos( inicio, fin, id_pla, estatus, tipo_estatus ){

		$('#contenedor_datos').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');

		$.ajax({
			url: 'server/obtener_reporte_alumnos.php',
			type: 'POST',
			data: { inicio, fin, id_pla, estatus, tipo_estatus },
			success: function( respuesta ){
				$('#contenedor_datos').html( respuesta );
				// alert( respuesta );
			}
		});

	}



	function obtener_reporte_cobranza2( inicio, fin, id_pla ){

		$('#contenedor_datos').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');

		$.ajax({
			url: 'server/obtener_reporte_cobranza2.php',
			type: 'POST',
			data: { inicio, fin, id_pla },
			success: function( respuesta ){
				$('#contenedor_datos').html( respuesta );
				// alert( respuesta );
			}
		});

	}



	function obtener_reporte_egresos( inicio, fin, id_pla ){

		$('#contenedor_datos').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');

		$.ajax({
			url: 'server/obtener_reporte_egresos.php',
			type: 'POST',
			data: { inicio, fin, id_pla },
			success: function( respuesta ){
				$('#contenedor_datos').html( respuesta );
				// alert( respuesta );
			}
		});

	}



	function obtener_reporte_grupos( inicio, fin, id_pla ){

		$('#contenedor_datos').html('<div style="height: 800px; background-color: white;"><div class="sk-cube-grid" style="height:60px; width:60px; position:absolute; left:48%; top:30%; z-index: 99999;"> <div class="loader"> <div class="cube"></div><div class="cube"></div><div class="cube"></div></div></div></div>');

		$.ajax({
			url: 'server/obtener_reporte_grupos.php',
			type: 'POST',
			data: { inicio, fin, id_pla },
			success: function( respuesta ){
				$('#contenedor_datos').html( respuesta );
				// alert( respuesta );
			}
		});

	}

	
</script>

<script>
	$("#seleccionPlanteles").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

        //console.log( $(this)[0].checked );

        if ( $(this)[0].checked == true ) {
          // console.log("checkeado");
            $('.checkboxPlanteles').prop({checked: true});
            obtener_datos();
            
        }else{ 
          
            $('.checkboxPlanteles').prop({checked: false});
            obtener_datos();

        }

    //$('.seleccionAnniosMeses').prop({checked: false});
    });
</script>

<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - Reportes');
</script>