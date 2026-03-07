 <?php  
 
	include('inc/header.php');

	$id_emp = $_GET['id_emp'];

	$sqlEmpleado = "SELECT * FROM empleado WHERE id_emp = '$id_emp'";
	$resultadoEmpleado = mysqli_query($db, $sqlEmpleado);
	$filaEmpleado = mysqli_fetch_assoc($resultadoEmpleado);

	$nombre = $filaEmpleado['nom_emp']." ".$filaEmpleado['app_emp']." ".$filaEmpleado['apm_emp'];
	$tipo = $filaEmpleado['tip_emp'];
	$ingreso = $filaEmpleado['ing_emp'];
	$telefono = $filaEmpleado['tel_emp'];
	$correo = $filaEmpleado['cor_emp'];
	$fotoEmpleado = $filaEmpleado['fot_emp'];

?>

<!-- BOTON FLOTANTE AGREGAR CONCEPTO-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar Concepto" id="agregarConcepto"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONCEPTO-->

<!-- CONTENIDO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Empleados en el Plantel">
			<i class="fas fa-bookmark"></i> 
			Empleados
		</span>
	<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="empleados.php" title="Vuelve a Empleados"><span class="text-white">Empleados</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Nómina</a>
		</div>
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Empleado">
		<i class="fas fa-certificate"></i>
		Empleado: <?php echo $nombre; ?>
		</span><br>
	</div>
</div>
<!-- ROW TABLA -->

<div class="row">
	<div class="col-md-4">
  	<!-- PRIMERA COL -->
  		<!-- Rotating card -->
	<div class="card-wrapper">
	  <div id="card-1" class="card card-rotating text-center">

	    <!-- Front Side -->
	    <div class="face front">

	      <!-- Image-->
	      <div class="card-up">
	        <img class="card-img-top" src="../img/nomina.jpg" alt="Image with a photo of clouds.">
	      </div>

	      <!-- Avatar -->
	      <div class="avatar mx-auto white"><img src="../uploads/<?php echo $fotoEmpleado; ?>" class="rounded-circle"
	          alt="Sample avatar image.">
	      </div>

	      <!-- Content -->
	      <div class="card-body">
	        <h4 class="font-weight-bold mb-3"><?php echo $nombre; ?></h4>
	        <p class="font-weight-bold"><?php echo $tipo; ?></p>
	        <h5 class="mb-3">Ingreso: <?php echo $ingreso; ?></h5>
	        <!-- Triggering button -->
	        <a class="rotate-btn" data-card="card-1"><i class="fas fa-redo-alt"></i> Ver más...</a>
	      </div>
	    </div>
	    <!-- Front Side -->

	    <!-- Back Side -->
	    <div class="face back">
	      <div class="card-body">

	        <!-- Content -->
	        <h4 class="font-weight-bold mb-0">Contacto</h4>
	        <hr>
			

			<h6 class="font-weight-bold mb-3"><?php echo $telefono; ?></h6>

			<h6 class="font-weight-bold mb-3"><?php echo $correo; ?></h6>

		        
				
	        
	          <!-- Triggering button -->
	          <a class="rotate-btn" data-card="card-1"><i class="fas fa-undo"></i> Volver...</a>

	      </div>
	    </div>
	    <!-- Back Side -->

	  </div>
	</div>
	<!-- Rotating card -->
  	</div>
  	<!-- FIN PRIMERA COL 4 -->

  	<!-- SEGUNDA COL DATATABLE -->
	<div class="col-md-8 text-center">
		<?php  

			$sqlConceptos = "SELECT * FROM concepto WHERE id_emp1 = '$id_emp' ORDER BY id_con DESC";
			$resultadoConceptos = mysqli_query($db, $sqlConceptos);


		?>
		<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
			<thead class="bg-info text-white">
				<tr>
					<th>#</th>
					<th>Concepto</th>
					<th>Monto</th>
					<th>Descripción</th>
					<th>Tipo</th>
					<th>Acción</th>
				</tr>
			</thead>


			<?php 
				$i = 1;
				while($filaConceptos = mysqli_fetch_assoc($resultadoConceptos)){

			?>
				<tr>
					<td><?php echo $i; $i++;?></td>
			
					<td><?php echo $filaConceptos['con_con']; ?></td>
					<td><?php echo "$ ".$filaConceptos['mon_con']; ?></td>
					<td><?php echo $filaConceptos['des_con']; ?></td>
					<td><?php echo $filaConceptos['tip_con']; ?></td>
					
					
					<!-- BOTONES DE ACCION -->
					<td>				

						<a class="btn btn-primary btn-sm edicion" title="Editar <?php echo $filaConceptos['con_con']; ?>" edicion="<?php echo $filaConceptos['id_con']; ?>"><i class="fas fa-edit fa-1x"></i></a>	
						<a class="btn btn-danger btn-sm eliminacion" title="Eliminar <?php echo $filaConceptos['con_con']; ?>" eliminacion="<?php echo $filaConceptos['id_con']; ?>" concepto="<?php echo $filaConceptos['con_con']; ?> "><i class="fas fa-trash-alt fa-1x" ></i></a>
						
						
					</td>
					<!-- FIN BOTONES DE ACCION -->

				</tr>


			<?php
				} 

			?>
		</table>
		
	</div>
	<!-- FIN SEGUNDA COL DATATABLE -->
	
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR CONCEPTO -->
<div class="modal fade text-left" id="agregarConceptoModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarConceptoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Concepto</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="con_con" name="con_con" class="form-control validate">
	          <label  for="form34">Concepto de Pago</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-money-check-alt prefix grey-text"></i>
	          <input type="number" id="mon_con" name="mon_con" class="form-control validate">
	          <label  for="form29">Monto</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-question prefix grey-text"></i>
	          <input type="text" id="des_con" name="des_con" class="form-control validate">
	          <label  for="form29">Descripción</label>
	        </div>
			
			

			<div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="tip_con" name="tip_con" class="form-control validate">
	          <label  for="form29">Tipo</label>
	        </div>


	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR CONCEPTO -->

<!-- CONTENIDO MODAL EDITAR CONCEPTO -->
<div class="modal fade text-left" id="editarConceptoModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarConceptoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Concepto</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="concepto" name="concepto" class="form-control validate">
	          <label  for="form34">Concepto de Pago</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="number" id="monto" name="monto" class="form-control validate">
	          <label  for="form29">Monto</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="descripcion" name="descripcion" class="form-control validate">
	          <label  for="form29">Descripción</label>
	        </div>
			
			

			<div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="tipo" name="tipo" class="form-control validate">
	          <label  for="form29">Tipo</label>
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
<!-- FIN CONTENIDO MODAL EDITAR CONCEPTO -->




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
</script>


<script>

	//FORMULARIO DE CREACION DE CONCEPTO
	//CODIGO PARA AGREGAR CONCEPTO NUEVO ABRIENDO MODAL
	$('#agregarConcepto').on('click', function(event) {
		event.preventDefault();
		$('#agregarConceptoModal').modal('show');
		$('#agregarConceptoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarConceptoFormulario').on('submit', function(event) {
		event.preventDefault();
			
		$.ajax({
			//PASAR VARIABLE POR URL PARA TOMAR POR GET EN EL SERVER AUNADO A LOS DATOS DEL FORMULARIO
			url: 'server/agregar_concepto.php?id_emp=<?php echo $id_emp;?> ',
			type: 'POST',
			data: new FormData(agregarConceptoFormulario), 
			processData: false,
			contentType: false,
			cache: false,
			success: function(respuesta){
				console.log(respuesta);

				if (respuesta == 'Exito') {
					swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
					  window.location.reload();
					});
					
				}
			}
		});
	});

</script>



<script>
	//ELIMINACION DE CONCEPTO
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var concepto = $(this).attr("eliminacion");
		var nombreConcepto = $(this).attr("concepto");

		swal({
		  title: "¿Deseas eliminar "+nombreConcepto+"?",
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
				url: 'server/eliminacion_concepto.php',
				type: 'POST',
				data: {concepto},
				success: function(respuesta){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
						  window.location.reload();
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
	//EDICION DE CONCEPTO

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE CONCEPTO

	$('.edicion').on('click', function(){
		$('#editarConceptoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionConcepto = $(this).attr("edicion");
		$('#editarConceptoFormulario label').addClass('active');
		$('#editarConceptoFormulario i').addClass('active');

		//console.log(edicionRama);


		$.ajax({
			url: 'server/obtener_concepto.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionConcepto},
			success: function(datos){

				$('#editarConceptoModal').modal('show');
				$('#concepto').attr({value: datos.con_con});
				$('#monto').attr({value: datos.mon_con});
				$('#descripcion').attr({value: datos.des_con});
				$('#tipo').attr({value: datos.tip_con});
				$('#identificador').attr({value: datos.id_con});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL CONCEPTO
				$('#editarConceptoFormulario').on('submit', function(event) {
					event.preventDefault();

		
					$.ajax({
					
						url: 'server/editar_concepto.php',
						type: 'POST',
						data: new FormData(editarConceptoFormulario), 
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

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE CONCEPTO

	
</script>