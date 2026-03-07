<?php  

	include('inc/header.php');

?>

<!-- BOTON FLOTANTE AGREGAR EMPLEADO-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar Empleado" id="agregarEmpleado"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR EMPLEADO-->

<!-- CONTENIDO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Empleados en el Plantel">
			<i class="fas fa-bookmark"></i> 
			Empleados
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Empleados</a>
</div>


<!-- ROW TABLA -->

<div class="row">
	<div class="col-md-12 text-center">
		<?php  

		$sqlEmpleados = "SELECT * FROM empleado WHERE id_pla6 = '$plantel' ORDER BY id_emp DESC";
		$resultadoEmpleados = mysqli_query($db, $sqlEmpleados);


	?>
	<br>
	<table id="myTable" class="table table-hover table-bordered table-sm table-responsive" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th>#</th>
				<th>Foto</th>
				<th>Nombre</th>
				<th>Apellído Paterno</th>
				<th>Apellído Materno</th>
				<th>Correo</th>
				<th>Teléfono</th>
				<th>RFC</th>
				<th>Ingreso</th>
				<th>Tipo</th>
				<th>Estatus</th>
				<th>Switch</th>
				<th>Nómina</th>
				<th>Acción</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaEmpleados = mysqli_fetch_assoc($resultadoEmpleados)){

		?>
			<tr>
				<td><?php echo $i; $i++;?></td>
				<td>
					<div class="view overlay imagenes" title="Información de <?php echo $filaEmpleados['nom_emp']; ?>">
					 
					  <img src="../uploads/<?php echo $filaEmpleados['fot_emp']; ?>" class="img-fluid avatar rounded-circle" alt="404" width="40px" height="40px" imagen="<?php echo $filaEmpleados['id_emp']; ?>" >
					  <a>
					    <div class="mask waves-effect waves-light rgba-white-slight"></div>
					  </a>
					</div>
					
				</td>
				<td><?php echo $filaEmpleados['nom_emp']; ?></td>
				<td><?php echo $filaEmpleados['app_emp']; ?></td>
				<td><?php echo $filaEmpleados['apm_emp']; ?></td>
				<td><?php echo $filaEmpleados['cor_emp']; ?></td>
				<td><?php echo $filaEmpleados['tel_emp']; ?></td>
				<td><?php echo $filaEmpleados['rfc_emp']; ?></td>
				<td><?php echo $filaEmpleados['ing_emp']; ?></td>
				<td><?php echo $filaEmpleados['tip_emp']; ?></td>
				<!-- OUTPUT DE TEXTO SEGUN ESTATUS DE EMPLEADO -->
				<td>
					<span id="<?php echo $filaEmpleados['id_emp']; ?>"><?php echo $filaEmpleados['est_emp']; ?></span>
				</td>
				<!-- FIN OUTPUT DE TEXTO SEGUN ESTATUS DE EMPLEADO-->


				<!-- SWITCHES SEGUN CONDICION -->
				<td>
					<?php  
						if ($filaEmpleados['est_emp'] == 'Activo') { 
					?>

						<a class="btn-floating btn-sm light-green accent-3 switches" switch="<?php echo $filaEmpleados['id_emp']; ?> " estatus="<?php echo $filaEmpleados['est_emp']; ?>" title="Haz click para Desactivar a <?php echo $filaEmpleados['nom_emp']; ?>"><i class="fas fa-power-off"></i></a>
					<?php
							
						}else if($filaEmpleados['est_emp'] == 'Inactivo'){
					?>
						<a class="btn-floating btn-sm grey switches" switch="<?php echo $filaEmpleados['id_emp']; ?> " estatus="<?php echo $filaEmpleados['est_emp']; ?>" title="Haz click para Activar a <?php echo $filaEmpleados['nom_emp']; ?>"><i class="fas fa-power-off"></i></a>
					<?php		
						}
					?>					
				</td>
				<!-- FIN SWITCHES SEGUN CONDICION -->

				<!-- ACCIONES DE NOMINA -->
				<td>
					<a class="text-white" href="nomina_empleado.php?id_emp=<?php echo $filaEmpleados['id_emp']; ?>" title="Ver nómina de <?php echo $filaEmpleados['nom_emp']; ?>">
						<div class="chip info-color text-white">

							Ver
						
						</div>
					</a>
					
				</td>
				<!-- FIN ACCIONES DE NOMINA -->
					

				<!-- BOTONES DE ACCION -->
				<td class="d-flex flex-column">					
					<a class="eliminacion" title="Eliminar a <?php echo $filaEmpleados['nom_emp']; ?>" eliminacion="<?php echo $filaEmpleados['id_emp']; ?>" empleado="<?php echo $filaEmpleados['nom_emp'].' '.$filaEmpleados['app_emp'].' '.$filaEmpleados['apm_emp']; ?> ">
						<div class="chip  red text-white">
							Elimina
						</div>
					</a>
					<a class="edicion" title="Editar a <?php echo $filaEmpleados['nom_emp']; ?>" edicion="<?php echo $filaEmpleados['id_emp']; ?>">
						<div class="chip info-color text-white">
							Edita
						</div>
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
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR EMPLEADO -->
<div class="modal fade text-left" id="agregarEmpleadoModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarEmpleadoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Empleado</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nom_emp" name="nom_emp" class="form-control validate">
	          <label  for="form34">Nombre</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="app_emp" name="app_emp" class="form-control validate">
	          <label  for="form29">Apellído Paterno</label>
	        </div>
			

			<div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apm_emp" name="apm_emp" class="form-control validate">
	          <label  for="form29">Apellído Materno</label>
	        </div>
	      	

	      	<div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="dir_emp" name="dir_emp" class="form-control validate">
	          <label  for="form29">Dirección</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="cp_emp" name="cp_emp" class="form-control validate">
	          <label  for="form29">Código Postal</label>
	        </div>

			<label for="Ingreso">Fecha de Nacimiento</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="nac_emp" name="nac_emp" class="form-control validate">
	        </div>

	    	<div class="md-form mb-5">
	    		
			  <div class="file-field">
			    <div class="btn btn-info btn-sm float-left">
			      <span>Sube una foto</span>
			      <input type="file" id="fot_emp" name="fot_emp">
			    </div>
			    <div class="file-path-wrapper">
			      <input class="file-path validate" type="text" placeholder="5MB, JPEG, JPG o PNG">
			    </div>
			  </div>

	    	</div>
		
		
	        <div class="md-form mb-5">
	          <i class="fas fa-fingerprint prefix grey-text"></i>
	          <input type="text" id="rfc_emp" name="rfc_emp" class="form-control validate">
	          <label  for="form29">RFC</label>
	        </div>

			
			<label id="output"></label>
	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="correo" class="form-control" name="correo">
	          <label for="form29">Correo Electrónico</label>
	          
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-phone prefix grey-text"></i>
	          <input type="text" id="tel_emp" name="tel_emp" class="form-control validate">
	          <label  for="form29">Teléfono</label>
	        </div>


	        <label for="Ingreso">Fecha de Ingreso</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="ing_emp" name="ing_emp" class="form-control validate">
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="tip_emp" name="tip_emp" class="form-control validate">
	          <label  for="form29">Tipo de Empleado</label>
	        </div>


	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	      <h3 id="validacionCorreo"></h3><br>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR EMPLEADO -->

<!-- CONTENIDO MODAL EDITAR EMPLEADO -->
<div class="modal fade text-left" id="editarEmpleadoModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarEmpleadoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Empleado</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Nombre</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apellido1" name="apellido1" class="form-control validate">
	          <label  for="form29">Apellído Paterno</label>
	        </div>
			

			<div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apellido2" name="apellido2" class="form-control validate">
	          <label  for="form29">Apellído Materno</label>
	        </div>
	      	

	      	<div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="direccion" name="direccion" class="form-control validate">
	          <label  for="form29">Dirección</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="codigo" name="codigo" class="form-control validate">
	          <label  for="form29">Código Postal</label>
	        </div>

			<label for="Ingreso">Fecha de Nacimiento</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="nacimiento" name="nacimiento" class="form-control validate">
	        </div>

	    	<div class="md-form mb-5">
	    		
			  <div class="file-field">
			    <div class="btn btn-info btn-sm float-left">
			      <span>Sube una foto</span>
			      <input type="file" id="foto" name="foto">
			    </div>
			    <div class="file-path-wrapper">
			      <input class="file-path validate" type="text" placeholder="5MB, JPEG, JPG o PNG" id="fotoText">
			    </div>
			  </div>

	    	</div>
		
		
	        <div class="md-form mb-5">
	          <i class="fas fa-fingerprint prefix grey-text"></i>
	          <input type="text" id="rfc" name="rfc" class="form-control validate">
	          <label  for="form29">RFC</label>
	        </div>

			
			<label id="outputEdicion"></label>
	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="correoEdicion" class="form-control" name="correoEdicion">
	          <label for="form29">Correo Electrónico</label>
	          
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-phone prefix grey-text"></i>
	          <input type="text" id="telefono" name="telefono" class="form-control validate">
	          <label  for="form29">Teléfono</label>
	        </div>


	        <label for="Ingreso">Fecha de Ingreso</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="ingreso" name="ingreso" class="form-control validate">
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="tipo" name="tipo" class="form-control validate">
	          <label  for="form29">Tipo de Empleado</label>
	        </div>

	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">
	         
	        </div>



	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	      <h3 id="validacionCorreoEdicion"></h3><br>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR EMPLEADO -->




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
	//CODIGO PARA TOMAR ID DE IMAGEN Y DESPLEGAR EN MODAL INFO DEL EMPLEADO
	$('.imagenes').on('click', function(event) {
			event.preventDefault();

			var imagen = $(this).children().attr("imagen");

			console.log(imagen);
			/* Act on the event */
		});
</script>

<script>

	//FORMULARIO DE CREACION DE EMPLEADO
	//CODIGO PARA AGREGAR EMPLEADO NUEVO ABRIENDO MODAL
	$('#agregarEmpleado').on('click', function(event) {
		event.preventDefault();
		$('#agregarEmpleadoModal').modal('show');
		$('#agregarEmpleadoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarEmpleadoFormulario').on('submit', function(event) {
			event.preventDefault();
			



			if ($("#fot_emp")[0].files[0]) {

				var fileName = $("#fot_emp")[0].files[0].name;
				var fileSize = $("#fot_emp")[0].files[0].size;


				var ext = fileName.split('.').pop();

				
				if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
					if (fileSize < 3000000) {
						$.ajax({
				
							url: 'server/agregar_empleado.php',
							type: 'POST',
							data: new FormData(agregarEmpleadoFormulario), 
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
					}else{
						swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 5MB!" ,  "error" )
					}
					
				}else{
					swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
				}

			}else{
				$.ajax({
				
					url: 'server/agregar_empleado.php',
					type: 'POST',
					data: new FormData(agregarEmpleadoFormulario), 
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

			}
			
		});


	
</script>


<script>
	//CODIGO DEL SWITCH DEL EMPLEADO


	$('.switches').on('click', function(event) {
		event.preventDefault();
		var id_emp = $(this).attr("switch");
		var estatus = $(this).attr("estatus");
		var elemento = $(this);

		if (estatus == 'Activo') {
			$.ajax({
				url: 'server/estatus_empleado.php',
				type: 'POST',
				data: {id_emp, estatus},
				success: function(respuesta){
					console.log(respuesta);
					elemento.removeClass();
					elemento.addClass('btn-floating btn-sm grey switches');
					elemento.removeAttr("estatus");
					elemento.attr({estatus: 'Inactivo'});
					console.log($('#'+id_emp+'').text('Inactivo'));

				}
			});
			
			
		}else if(estatus == 'Inactivo'){
			$.ajax({
				url: 'server/estatus_empleado.php',
				type: 'POST',
				data: {id_emp, estatus},
				success: function(respuesta){
					console.log(respuesta);
					elemento.removeClass();
					elemento.addClass('btn-floating btn-sm light-green accent-3 switches');
					elemento.removeAttr("estatus");
					elemento.attr({estatus: 'Activo'});
					console.log($('#'+id_emp+'').text('Activo'));
					
				}
			});

		}
		//console.log(id_emp+estatus);
	});

</script>



<script>
	//ELIMINACION DE EMPLEADO
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var empleado = $(this).attr("eliminacion");
		var nombreEmpleado = $(this).attr("empleado");

		// console.log(empleado);

		swal({
		  title: "¿Deseas eliminar a "+nombreEmpleado+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a esa persona!",
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
				url: 'server/eliminacion_empleado.php',
				type: 'POST',
				data: {empleado},
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
	//EDICION DE EMPLEADO

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE EMPLEADO

	$('.edicion').on('click', function(){
		$('#editarEmpleadoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionEmpleado = $(this).attr("edicion");
		$('#editarEmpleadoFormulario label').addClass('active');
		$('#editarEmpleadoFormulario i').addClass('active');

		//console.log(edicionEmpleado);


		$.ajax({
			url: 'server/obtener_empleado.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionEmpleado},
			success: function(datos){

				$('#editarEmpleadoModal').modal('show');
				$('#nombre').attr({value: datos.nom_emp});
				$('#apellido1').attr({value: datos.app_emp});
				$('#apellido2').attr({value: datos.apm_emp});
				$('#direccion').attr({value: datos.dir_emp});
				$('#codigo').attr({value: datos.cp_emp});
				$('#nacimiento').attr({value: datos.nac_emp});
				$('#foto').attr({value: datos.fot_emp});
				$('#fotoText').attr({value: datos.fot_emp});
				$('#rfc').attr({value: datos.rfc_emp});
				$('#correoEdicion').attr({value: datos.cor_emp});
				$('#telefono').attr({value: datos.tel_emp});
				$('#ingreso').attr({value: datos.ing_emp});
				$('#tipo').attr({value: datos.tip_emp});
				$('#identificador').attr({value: datos.id_emp});


				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL EMPLEADO
				$('#editarEmpleadoFormulario').on('submit', function(event) {
					event.preventDefault();

						

						if ($("#foto")[0].files[0]) {

							var fileName = $("#foto")[0].files[0].name;
							var fileSize = $("#foto")[0].files[0].size;


							var ext = fileName.split('.').pop();

							
							if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
								if (fileSize < 3000000) {
									$.ajax({
							
										url: 'server/editar_empleado.php',
										type: 'POST',
										data: new FormData(editarEmpleadoFormulario), 
										processData: false,
										contentType: false,
										cache: false,
										success: function(respuesta){
										console.log(respuesta);

											if (respuesta == 'Exito') {
												console.log("condicion");
												swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
												then((value) => {
												  window.location.reload();
												});
												
											}
										}
									});
								}else{
									swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 5MB!" ,  "error" )
								}
								
							}else{
								swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
							}

						}else{
							$.ajax({
							
								url: 'server/editar_empleado.php',
								type: 'POST',
								data: new FormData(editarEmpleadoFormulario), 
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

						}

					
				});
				
			}
		});
		

	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE EMPLEADO



	
</script>