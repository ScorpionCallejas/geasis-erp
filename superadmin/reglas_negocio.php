<?php  

	include('inc/header.php');


?>


<style>
	.cabeceraTabla th{
		font-size: 12px;
	}

	.cuerpoTabla td{
		font-size: 12px;
	}
</style>
<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Reglas de Negocio"><i class="fas fa-bookmark"></i> Reglas de Negocio</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Reglas de Negocio</a>
		</div>
		
	</div>
	
</div>
<!-- FIN TITULO -->


<!-- INDICADORES -->
<!-- FIN INDICADORES -->


<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 justify-content-center">
	<?php  
		$sqlRamas = "SELECT * FROM rama WHERE id_pla1 = '$plantel' ORDER BY id_ram DESC";
		$resultadoRamas = mysqli_query($db, $sqlRamas);
	?>

		<div class="card">

	        <!--Card content-->
	        <div class="card-body">
				<table id="myTable" class="table table-hover table-bordered table-sm table-responsive" cellspacing="0" width="100%">
					<thead class="bg-info text-white" >
						<tr>
							<th class="letraPequena">#</th>
							<th class="letraPequena">Nombre</th>
							<th class="letraPequena">Niveles</th>
							<th class="letraPequena">Periodos</th>
							<th class="letraPequena">Modalidad</th>
							<th class="letraPequena">Nivel Educativo</th>
							<th class="letraPequena">Beca Máxima</th>
							<th class="letraPequena">Alumnos</th>
							<th class="letraPequena">Precio Lista</th>
							<th class="letraPequena">Carga Regular</th>
							<th class="letraPequena">Recargo por Carga Baja</th>
							<th class="letraPequena">Descuento por Carga Alta</th>

							<th class="letraPequena">Acción</th>
						</tr>
					</thead>


					<?php 
						$i = 1;
						while($filaRamas = mysqli_fetch_assoc($resultadoRamas)){

					?>
						<tr>
							<td class="letraPequena"><?php echo $i; $i++;?></td>
					
							<td class="letraPequena">
								<a href="#" class="ramas badge badge-light badge-pill" id_ram="<?php echo $filaRamas['id_ram']; ?>">
									<h6>
										<?php echo $filaRamas['nom_ram']; ?>
									</h6>
							
								</a>
							</td>
							<td class="letraPequena">
								
									<?php echo $filaRamas['cic_ram']; ?>
								
									
							</td>
							<td class="letraPequena"><?php echo $filaRamas['per_ram']; ?></td>
							<td class="letraPequena"><?php echo $filaRamas['mod_ram']; ?></td>
							<td class="letraPequena"><?php echo $filaRamas['gra_ram']; ?></td>
							<td class="letraPequena">
								<?php echo $filaRamas['bec_max_ram']*100; ?>%
							</td>

							<td class="letraPequena">
								<?php  

									$id_ram = $filaRamas['id_ram'];

									$sqlTotal = "
										SELECT *
										FROM alumno
										INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
										WHERE id_ram3 = '$id_ram'
									";


									$resultadoTotal = mysqli_query($db, $sqlTotal);

									$total = mysqli_num_rows($resultadoTotal);

									echo $total;
								?>
							</td>

							
							
							<td class="letraPequena"><?php echo "$ ".$filaRamas['cos_ram']; ?></td>
							<td class="letraPequena"><?php echo $filaRamas['car_reg_ram']; ?></td>
							<td class="letraPequena"><?php echo $filaRamas['car_min_ram']*100; ?>%</td>
							<td class="letraPequena"><?php echo $filaRamas['des_max_ram']*100; ?>%</td>

							
							<!-- BOTONES DE ACCION -->
							<td class="letraPequena">				

								<a class="chip info-color text-white edicion edicion letraPequena" title="Editar <?php echo $filaRamas['nom_ram']; ?>" edicion="<?php echo $filaRamas['id_ram']; ?>">
									Modificar
								</a>
								
								
							</td>
							<!-- FIN BOTONES DE ACCION -->

						</tr>


					<?php
						} 

					?>
				</table>
			</div>
		</div>
		
	</div>
	
</div>
<!--  FIN ROW TABLA-->

<br>

<!-- CICLOS ESCOLARES -->
<div class="row" id="contenedor_ciclos">
	
	
	
</div>

<br>

<!-- ROW PAGOS GLOBALES Y RECURRENTES -->
<div class="row" id="contenedor_pagos">
	

	
</div>













<!-- FIN CONTENIDO -->

<!-- CONTENIDO MODAL EDITAR RAMA -->
<div class="modal fade text-left" id="editarRamaModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarRamaFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">
	        	Editar/Agregar Reglas del Negocio para 
	        	<span id="nombre"></span>
	        </h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">



			<div class="md-form mb-5">
	          <i class="fas fa-dollar-sign prefix grey-text"></i>
	          <input type="number" id="costo" name="costo" class="form-control validate">
	          <label  for="form29">Costo</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-money-check-alt prefix grey-text"></i>
	          <input type="number" id="beca" name="beca" class="form-control validate" min="0" step=".1">
	          <label  for="form29">Beca Máxima</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-money-check-alt prefix grey-text"></i>
	          <input type="number" id="regular" name="regular" class="form-control validate">
	          <label  for="form29">Carga Regular</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-plus-circle prefix grey-text"></i>
	          <input type="number" id="cargo" name="cargo" class="form-control validate" min="0" max="100">
	          <label  for="form29">Recargo por Carga Baja (% Porcentual)</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-minus-circle prefix grey-text"></i>
	          <input type="number" id="descuento" name="descuento" class="form-control validate" min="0" max="100">
	          <label  for="form29">Descuento por Carga Alta (% Porcentual)</label>
	        </div>
	      	


	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">
	         
	        </div>


	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>


	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR RAMA -->









<?php  

	include('inc/footer.php');

?>


<script>
	$(document).ready(function () {


		$('#myTable').DataTable({
			
		
			dom: 'Bfrtlip',
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

            ],

			"language": {
                            "sProcessing":     "Procesando...",
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
		$('#myTable_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTable_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTable_wrapper input').attr("placeholder", "Buscar...");
			$('#myTable_wrapper input').removeClass('form-control-sm');
		});
		$('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTable_wrapper .dataTables_filter').addClass('md-form');
		$('#myTable_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTable_wrapper select').addClass('mdb-select');
		$('#myTable_wrapper .mdb-select').materialSelect();
		$('#myTable_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

	
	});



	$("#editarRamaModal").draggable();
</script>




<script>
	//EDICION DE RAMA

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE RAMA

	$('.edicion').on('click', function(){
		$('#editarRamaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionRama = $(this).attr("edicion");
		$('#editarRamaFormulario label').addClass('active');
		$('#editarRamaFormulario i').addClass('active');

		//console.log(edicionRama);


		$.ajax({
			url: 'server/obtener_rama.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionRama},
			success: function(datos){

				$('#editarRamaModal').modal('show');
				$('#nombre').text(datos.nom_ram);
				$('#costo').attr({value: datos.cos_ram});
				$('#beca').attr({value: datos.bec_max_ram*100});
				$('#regular').attr({value: datos.car_reg_ram});
				$('#descuento').attr({value: datos.des_max_ram*100});
				$('#cargo').attr({value: datos.car_min_ram*100});
				$('#identificador').attr({value: datos.id_ram});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL RAMA
				$('#editarRamaFormulario').on('submit', function(event) {
					event.preventDefault();

		
					$.ajax({
					
						url: 'server/editar_reglas_negocio.php',
						type: 'POST',
						data: new FormData(editarRamaFormulario), 
						processData: false,
						contentType: false,
						cache: false,
						success: function(respuesta){
							console.log(respuesta);

							if (respuesta == 'Exito') {
								swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
								then((value) => {
								  window.location.reload();
								});
								
							}
						}
					});

				});				
			}
		});
	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE RAMA
</script>

<script>
	$(".ramas").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$('.ramas').removeClass('badge badge-light badge-pill animated bounceIn');
		$('.ramas').removeClass('badge badge-info badge-pill animated bounceIn');
		$('.ramas').addClass('badge badge-light badge-pill');
		$(this).removeClass('badge badge-light badge-pill animated bounceIn');
		$(this).addClass('badge badge-info badge-pill animated bounceIn');

		var id_ram = $(this).attr("id_ram");


		$.ajax({
			url: 'server/obtener_reglas_pagos.php',
			type: 'POST',
			data: {id_ram},
			success: function(respuesta){
				$("#contenedor_pagos").html(respuesta);
			}
		});


		$.ajax({
			url: 'server/obtener_ciclos_rama.php',
			type: 'POST',
			data: {id_ram},
			success: function(respuesta){
				$("#contenedor_ciclos").html(respuesta);
			}
		});




		
	});
</script>


<script>

	//FORMULARIO DE CREACION DE CARGOS DE RAMA
	$('#agregarPagoCiclo').on('click', function(event) {
		event.preventDefault();
		$('#agregarPagoCicloModal').draggable();
		$('#agregarPagoCicloModal').modal('show');
		$('#agregarPagoCicloFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		$('.selectorPagoCiclo').materialSelect('destroy');
		$('.selectorPagoCiclo').materialSelect();
	});


	$('#agregarPagoCicloFormulario').on('submit', function(event) {
		event.preventDefault();
		$('#btn_pago_ciclo').attr('disabled','disabled');
		$.ajax({
 
			url: 'server/agregar_pago_ciclo.php?id_ram=<?php echo $id_ram;?>',
			type: 'POST',
			data: new FormData(agregarPagoCicloFormulario), 
			processData: false,
			contentType: false,
			cache: false,
			success: function(respuesta){
				console.log(respuesta);

				if (respuesta == 'Exito') {
					swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {

						var id_ram = <?php echo $id_ram; ?>;
					  	$.ajax({
							url: 'server/obtener_reglas_pagos.php',
							type: 'POST',
							data: {id_ram},
							success: function(respuesta){
								$('.modal-backdrop').remove();
								$("#contenedor_pagos").html(respuesta);
							}
						});
					});
					
				}
			}
		});
			
		
	});


	
</script>


<script>
	//EDICION DE EDICION PAGO RAMA

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE EDICION PAGO RAMA

	$('.edicionPagoCiclo').on('click', function(){
		$('#editarPagoCicloFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionPagoCiclo = $(this).attr("edicionPagoCiclo");
		$('#editarPagoCicloFormulario label').addClass('active');
		$('#editarPagoCicloFormulario i').addClass('active');

		//console.log(edicionPagoCiclo);


		$.ajax({
			url: 'server/obtener_pago_ciclo.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionPagoCiclo},
			success: function(datos){

				$('#editarPagoCicloModal').modal('show');
				$('#concepto_ciclo').attr({value: datos.con_pag_cic});
				$('#monto_ciclo').attr({value: datos.mon_pag_cic});
				$('#inicio_ciclo').attr({value: datos.ini_pag_cic});
				$('#fin_ciclo').attr({value: datos.fin_pag_cic});

				$("#tipo1_ciclo").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
				$('#tipo1_ciclo').children().removeAttr('selected');//SE REMUEVE PARA QUE NO CHOQUE CON EL NUEVO A SELECCIONAR
				//AGREGADO A SELECT DEL DATO OBTENIDO MEDIANTE COMPARACION
				for(var i = 0 ; i < $('#tipo1_ciclo').children().length; i++){
					//console.log($('#tipo1_ciclo').children().eq(i).attr("value") );

					if ($('#tipo1_ciclo').children().eq(i).attr("value") == datos.tip1_pag_cic) {
						$('#tipo1_ciclo').children().eq(i).attr({"selected": ""});			
					}
				}

				$('#tipo1_ciclo').attr({value: datos.tip1_pag_cic});

				$('#descuento_ciclo').attr({value: datos.des_pag_cic});
				//$('#prioridad_ciclo').attr({value: datos.pri_pag_cic});


				$("#tipo2_ciclo").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
				$('#tipo2_ciclo').children().removeAttr('selected');//SE REMUEVE PARA QUE NO CHOQUE CON EL NUEVO A SELECCIONAR
				//AGREGADO A SELECT DEL DATO OBTENIDO MEDIANTE COMPARACION
				for(var i = 0 ; i < $('#tipo2_ciclo').children().length; i++){
					//console.log($('#tipo2_ciclo').children().eq(i).attr("value") );

					if ($('#tipo2_ciclo').children().eq(i).attr("value") == datos.tip2_pag_cic) {
						$('#tipo2_ciclo').children().eq(i).attr({"selected": ""});			
					}
				}

				$('#tipo2_ciclo').attr({value: datos.tip2_pag_cic});

				$('#cargo_ciclo').attr({value: datos.car_pag_cic});
				
				$('#repeticion_ciclo').attr({value: datos.rep_pag_cic});

				$("#prioridad_ciclo").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
				$('#prioridad_ciclo').children().removeAttr('selected');//SE REMUEVE PARA QUE NO CHOQUE CON EL NUEVO A SELECCIONAR
				//AGREGADO A SELECT DEL DATO OBTENIDO MEDIANTE COMPARACION
				for(var i = 0 ; i < $('#prioridad_ciclo').children().length; i++){
					//console.log($('#prioridad_ciclo').children().eq(i).attr("value") );

					if ($('#prioridad_ciclo').children().eq(i).attr("value") == datos.pri_pag_cic) {
						$('#prioridad_ciclo').children().eq(i).attr({"selected": ""});			
					}
				}

				$('#prioridad_ciclo').attr({value: datos.pri_pag_cic});//AGREGADO DE VALUE PARA VALIDACIONDECORREOEDICIONREALTIME
				
				$('#identificador_ciclo').attr({value: datos.id_pag_cic});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL EDICION PAGO RAMA
				$('#editarPagoCicloFormulario').on('submit', function(event) {
					event.preventDefault();

		
					$.ajax({
					
						url: 'server/editar_pago_ciclo.php',
						type: 'POST',
						data: new FormData(editarPagoCicloFormulario), 
						processData: false,
						contentType: false,
						cache: false,
						success: function(respuesta){
							console.log(respuesta);

							if (respuesta == 'Exito') {
								swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
								then((value) => {
									var id_ram = <?php echo $id_ram; ?>;
								  	$.ajax({
										url: 'server/obtener_reglas_pagos.php',
										type: 'POST',
										data: {id_ram},
										success: function(respuesta){
											$('.modal-backdrop').remove();
											$("#contenedor_pagos").html(respuesta);
										}
									});
								});
								
							}
						}
					});

				});				
			}
		});
	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE EDICION PAGO RAMA

</script>


<script>
	//ELIMINACION DE BLOQUE
	$('.eliminacionPagoCiclo').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var pagoCiclo = $(this).attr("eliminacionPagoCiclo");
		var nombrePagoCiclo = $(this).attr("pagoCiclo");


		swal({
		  title: "¿Deseas eliminar "+nombrePagoCiclo+"?",
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
				url: 'server/eliminacion_pago_ciclo.php',
				type: 'POST',
				data: {pagoCiclo},
				success: function(respuesta){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							var id_ram = <?php echo $id_ram; ?>;
						  	$.ajax({
								url: 'server/obtener_reglas_pagos.php',
								type: 'POST',
								data: {id_ram},
								success: function(respuesta){
									$('.modal-backdrop').remove();
									$("#contenedor_pagos").html(respuesta);
								}
							});
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

<!-- FIN SCRIPTS DE PAGOS CICLOS -->