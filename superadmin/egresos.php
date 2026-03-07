<?php  

	include('inc/header.php');

?>


<style>
	.my-custom-scrollbar {
		overflow: auto;
		max-width: 100%;
	}

	
</style>


<!-- TITULO -->
<div class="row ">
	
	<div class="col text-left">
		
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Egresos">
			<i class="fas fa-bookmark"></i> 
			Egresos
		</span>
	
	</div>

</div>

<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Egresos</a>
</div>
<!-- FIN TITULO -->





<!-- CONTENEDOR REPORTE -->
<div class="row">

	<div class="col-md-12">

		<div class="card" style="border-radius: 20px;">

			<div class="card-body">
			<!--  -->

				<!-- INDICADORES -->
				<div class="row">
					
					<div class="col-md-12">
						
						<div class="card" style="border-radius: 20px;">
							
							<div class="card-body">
								
								<div class="row">


									<div class="col-md-3">
										
										<div class="card hoverable" style="border-radius: 20px; position: relative;">
											
											<i class="fas fa-balance-scale fa-2x grey-text" style="position: absolute; right: 20px; top: 15px;"></i>

											<div class="card-body">
											
												<h6>Balance: <span id="balance" class="h5"></span></h6>
											
											</div>

										</div>

									</div>

									
									<div class="col-md-3">
										
										<div class="card hoverable" style="border-radius: 20px; position: relative;">
											
											<i class="fas fa-minus fa-2x grey-text" style="position: absolute; right: 20px; top: 15px;"></i>

											<div class="card-body">
											
												<h6>Egreso: <span id="egreso" class="h5"></span></h6>
											
											</div>

										</div>

									</div>


									<div class="col-md-3">
						
										<div class="card hoverable" style="border-radius: 20px; position: relative;">
											
											<i class="fas fa-plus fa-2x grey-text" style="position: absolute; right: 20px; top: 15px;"></i>

											<div class="card-body">
											
												<h6>Cobrado: <span id="cobrado" class="h5"></span></h6>
											
											</div>

										</div>

									</div>



								






								</div>

							</div>

						</div>		

					</div>

				</div>
				<!-- FIN INDICADORES -->
				<hr>

				<div class="row">
					<div class="col-md-12">

									<form id="formulario_buscador">

										<div class="md-form">
											
											<div class="row">

												<div class="col-md-3 text-center">
													
												
												</div>
												<div class="col-md-6">
													
													<div class="card" style="border-radius: 30px;">
														<div class="card-body">
															
															<div class="row">
																<div class="col-md-10 col-sm-10">
												
																	<i class="fas fa-search prefix"></i>
																	<input type="text" id="palabra" class="form-control">
																	<label for="palabra" id="placeholderPalabra">Buscar...</label>
																
																</div>

																<div class="col-md-2 col-sm-2" style="position: relative;">
																	
																	<button class="btn btn-rounded btn-block btn-sm grey-text waves-effect" type="submit" id="btn_buscar" style="position: absolute; top: 5px; left: -5px;">
															        	<i class="fas fa-search"></i>
															        </button>
																
																</div>
															</div>


															<div class="row">
																
																<div class="col-md-12">


																	<div style="">
																		
																		<div class="form-check form-check-inline">
																		  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22" name="seleccionPeriodo" checked value="Mes">
																		  	<label class="form-check-label letraPequena" for="materialGroupExample22">Por mes</label>
																		</div>


																		<!-- Group of material radios - option 1 -->
																		<div class="form-check form-check-inline">
																		  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11" name="seleccionPeriodo"  value="Fecha">
																		  	<label class="form-check-label letraPequena" for="materialGroupExample11">Por fechas</label>
																		</div>

																		<!-- Group of material radios - option 2 -->
																		<div class="form-check form-check-inline">
																		  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample23" name="seleccionPeriodo" value="Semana">
																		  	<label class="form-check-label letraPequena" for="materialGroupExample23">Por semanas</label>
																		</div>
																	
																	</div>
																	
																</div>
															</div>

															<br>


															<div id="contenedor_mes_annio" style="display: none;">
															<!--  -->
																<div class="row">
																
																	<!--  -->
																	<div class="col-md-8">

																		<select class="browser-default custom-select letraPequena" id="selectorMes">

																		<!--  -->
																		  	<?php
																		  		
																		  		$mesActualEntero = date('m');
																		  		$mesActualTexto = getMonth( $mesActualEntero );

																				$meses = 12;
																				$i = 1;
																			    
																			    while( $i <= $meses ) {

																			        
																			?>

																				<?php  
																					if ( $i == $mesActualEntero ) {
																				?>
																				
																						<option selected value="<?php echo $i; ?>" inicio="1" fin="30"><?php echo getMonth( $i ); ?></option>
																				
																				<?php
																					} else {
																				?>

																						<option value="<?php echo $i; ?>" inicio="1" fin="30"><?php echo getMonth( $i ); ?></option>

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
																		
																		<select class="browser-default custom-select letraPequena" id="selectorAnnio">

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
											

																<div class="row mt-1">
																	<div class="col-md-6">

																			<span class="letraPequena">Inicio</span>
																        	<input type="date" class="form-control filtrosFecha letraMediana" id="inicio" value="<?php echo date('Y-m-d'); ?>">
																        
																        
																	</div>

																	<div class="col-md-6">

																			<span class="letraPequena">Fin</span>
																			<input type="date" class="form-control filtrosFecha letraMediana" id="fin" value="<?php echo date('Y-m-d'); ?>">
																        
																	</div>
																</div>

															</div>

															<div id="contenedor_semana" style="display: none;">
																
																<span class="letraPequena">Selecciona una semana</span>
																<select class="browser-default custom-select letraPequena" id="selectorSemana">

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
															
															
										
														</div>
													</div>


												</div>
														
												
												
											
											</div>



											
										
										</div>

									</form>
									<!-- FIN BUSCADOR -->




								</div>
				</div>

				<div class="table-responsive">
					<table class="table table-hover" id="myTable" width="100%">
						<thead>


							<th class="letraMediana">Solicitó</th>
							<th class="letraMediana">Aprobó</th>
							<th class="letraMediana">Fecha y hora</th>
							<th class="letraMediana">Concepto</th>
							<th class="letraMediana">Cantidad</th>


						</thead>
						<tbody>
						
						</tbody>
					</table>

				</div>

			<!--  -->
			</div>
			
		</div>
	</div>
</div>
<!-- FIN CONTENEDOR REPORTE -->



<?php  

	include('inc/footer.php');

?>



<script>
	// BUSCADOR

    $('#formulario_buscador').on('submit', function() {
        event.preventDefault();
        /* Act on the event */
        var valor = $('#palabra').val();

        if ( valor.length >= 3 ) {

            obtener_egresos();
        
        }
        
    });

    $('#selectorMes').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_egresos();
		

	});


	$('#selectorAnnio').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_egresos();
		

	});



	// 
	$('#selectorSemana').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_egresos();
		

	});


	$('.filtrosFecha').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_egresos();
		// alert( radioReporte );

	});
	// 


    $('#palabra').on('keyup', function(event) {
        event.preventDefault();
        /* Act on the event */

        var valor = $('#palabra').val();

        if ( valor == '' ) {

            obtener_egresos();
        
        }



    });

    // FIN BUSCADOR

</script>




<script>

	$('.radioPeriodo').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_egresos();
		// alert( radioReporte );

	});

	obtener_egresos();

	function obtener_egresos(){
		// $('#myTable').DataTable().ajax.reload();

		var palabra = $('#palabra').val();

		var radioPeriodo = $(".radioPeriodo:checked").val();

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

		

	    // length = 10;
	    // start = 0;
	    // draw = 10;

	    // $.ajax({
	    //     url: 'server/obtener_egresos.php',
	    //     type: 'POST',
	    //     data: { palabra, length, start, draw, inicio, fin },
	    //     success: function( respuesta ){

	    //         console.log( respuesta );
	    //         // $('#contenedor_visualizacion').html( respuesta );

	    //     }
	        
	    // });

	    // INDICADORES
		$.ajax({
	        url: 'server/obtener_indicadores_cobranza.php',
	        type: 'POST',
	        dataType: 'json',
	        data: { palabra, inicio, fin },
	        success: function( datos ){

	            // $('#contenedor_datos').html( datos );
	            console.log( datos );

	            $('#cobrado').html( datos.cobrado );

	        }
	    });



		// INDICADORES
		$.ajax({
	        url: 'server/obtener_indicadores_egreso.php',
	        type: 'POST',
	        dataType: 'json',
	        data: { palabra, inicio, fin },
	        success: function( datos ){

	            // $('#contenedor_datos').html( datos );
	            console.log( datos );

	            $('#egreso').html( datos.egresos );
	            $('#balance').html( datos.balance ); 
	           
	            
	        }
	    });

		// DATATABLE
		$('#myTable').DataTable().destroy();
		$('#myTable').DataTable({
	    
	    	dom: 'Bpfrtl',
	                        
	        scrollX: true,
	        scrollY: true,
	        
	        // scrollCollapse: true,
	        // fixedColumns: {     
	        //   leftColumns: [2]
	        // },
	        buttons: [

	                {
	                    extend: 'excelHtml5',
	                   	className: 'btn btn-info btn-rounded btn-sm',
	                    messageTop: 'Listado de Alumnos del Plantel',
	                    exportOptions: {
	                        columns: ':visible'
	                    },
	                }

	        ],
	        "pageLength" : 10,
	        // "columnDefs": [
	        //   { 
	        //   	"orderable": false, 
	        //   	"targets": [ 0, 2, 3 ] 
	        //   }
	        // ],
	        "processing" : true,
	        "serverSide" : true,
	        "order" : [],
	        "searching" : false,

	        "ajax" : {
	            url:"server/obtener_egresos.php",
	            type:"POST",
	            data: { palabra, inicio, fin }
	        },

		    // LANGUAGE
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
	        // FIN LANGUAGE        
	        
	            
	    });


	    $('#myTable_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTable_wrapper input').attr("placeholder", "Buscar...");
            $('#myTable_wrapper input').removeClass('form-control-sm');
        });
        $('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTable_wrapper .dataTables_filter').addClass('md-form');
        $('#myTable_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
        $('#myTable_wrapper .mdb-select').materialSelect('destroy');
        $('#myTable_wrapper select').addClass('mdb-select');
        $('#myTable_wrapper .mdb-select').materialSelect();
        $('#myTable_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');



	}

	
</script>

<script>
	// OBTENER RESPUESTA DATATABLE
	// var diaInicio = $('#selectorMes option:selected').attr('inicio');
	// var diaFin = $('#selectorMes option:selected').attr('fin');
	// var mes = $('#selectorMes option:selected').val();
	// var annio = $('#selectorAnnio option:selected').val();
	
	// var inicio = annio+'-'+mes+'-'+diaInicio;
	// var fin = annio+'-'+mes+'-'+diaFin;

 //    length = 10;
 //    start = 0;
 //    draw = 10;
 //    palabra = '';
 //    $.ajax({
 //        url: 'server/obtener_egresos.php',
 //        type: 'POST',
 //        data: { palabra, length, start, draw, inicio, fin },
 //        success: function( respuesta ){

 //            console.log( respuesta );
 //            // $('#contenedor_visualizacion').html( respuesta );

 //        }
        
 //    });
</script>