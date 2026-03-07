<?php  

	include('inc/header.php');


?>

<!-- BOTON FLOTANTE AGREGAR SALON-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar salón" id="agregarSalon"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR SALON-->

<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Salones del plantel">
			<i class="fas fa-bookmark"></i> 
			Salones
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-2s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Salones</a>
</div>
<!-- FIN TITULO -->

<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-left">
		<?php  

			$sqlSalones = "
				SELECT * 
				FROM salon
				WHERE id_pla11 = '$plantel' 
				ORDER BY id_sal DESC";
			$resultadoSalones = mysqli_query($db, $sqlSalones);


		?>
	<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th class="letraPequena">#</th>
				<th class="letraPequena">Nombre</th>
				<th class="letraPequena">Descripción</th>
				<th class="letraPequena">Capacidad</th>
				<th class="letraPequena">Acción</th>
			</tr>
		</thead>

		<tbody>
			
		
			<?php 
				$i = 1;
				while($filaSalones = mysqli_fetch_assoc($resultadoSalones)){

			?>
				<tr>
					<td class="letraPequena"><?php echo $i; $i++;?></td>
		
					<td class="letraPequena"><?php echo $filaSalones['nom_sal']; ?></td>
					<td class="letraPequena"><?php echo $filaSalones['des_sal']; ?></td>
					<td class="letraPequena"><?php echo $filaSalones['cap_sal']; ?></td>
					

					<!-- BOTONES DE ACCION -->
					<td class="letraPequena">					

						<a  class="chip  info-color text-white edicion letraPequena" edicion="<?php echo $filaSalones['id_sal']; ?>" title="Editar <?php echo $filaSalones['nom_sal']; ?>">Editar</a>
					
						<a class="chip red text-white eliminacion letraPequena" title="Eliminar <?php echo $filaSalones['nom_sal']; ?>" eliminacion="<?php echo $filaSalones['id_sal']; ?>" salon="<?php echo $filaSalones['nom_sal']; ?> ">Eliminar</a>
						
					</td>
					<!-- FIN BOTONES DE ACCION -->

				</tr>


			<?php
				} 

			?>
		</tbody>
	</table>
		
	</div>
	
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR SALON -->
<div class="modal fade text-left" id="agregarSalonModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarSalonFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Salon</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        	<span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">


	        <div class="md-form mb-5">
	        	<i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="nom_sal" name="nom_sal" class="form-control validate">
	          <label  for="nom_sal">Nombre</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="des_sal" name="des_sal" class="form-control validate">
	          <label  for="des_sal">Descripción</label>
	        </div>
			


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="number" id="cap_sal" name="cap_sal" class="form-control validate">
	          <label  for="cap_sal">Capacidad</label>
	        </div>

			
	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>


	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR SALON -->



<!-- CONTENIDO MODAL EDITAR SALON -->
<div class="modal fade text-left" id="editarSalonModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarSalonFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Salon</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="nombre">Nombre</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="descripcion" name="descripcion" class="form-control validate">
	          <label  for="descripcion">Descripción</label>
	        </div>
			


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="number" id="capacidad" name="capacidad" class="form-control validate">
	          <label  for="capacidad">Capacidad</label>
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
<!-- FIN CONTENIDO MODAL EDITAR SALON -->




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

	//FORMULARIO DE CREACION DE SALON
	//CODIGO PARA AGREGAR SALON NUEVO ABRIENDO MODAL


	$('#agregarSalon').on('click', function(event) {
		event.preventDefault();
		$('#agregarSalonModal').modal('show');
		$('#agregarSalonFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarSalonFormulario').on('submit', function(event) {
		event.preventDefault();


		$.ajax({

			url: 'server/agregar_salon.php',
			type: 'POST',
			data: new FormData(agregarSalonFormulario), 
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
	//EDICION DE SALON

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE SALON

	$('.edicion').on('click', function(){
		$('#editarSalonFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionSalon = $(this).attr("edicion");
		$('#editarSalonFormulario label').addClass('active');
		$('#editarSalonFormulario i').addClass('active');

		//console.log(edicionSalon);


		$.ajax({
			url: 'server/obtener_salon.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionSalon},
			success: function(datos){

				$('#editarSalonModal').modal('show');
				$('#nombre').attr({value: datos.nom_sal});
				$('#descripcion').attr({value: datos.des_sal});
				$('#capacidad').attr({value: datos.cap_sal});
				
				$('#identificador').attr({value: datos.id_sal});


				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL SALON
				$('#editarSalonFormulario').on('submit', function(event) {
					event.preventDefault();

					$.ajax({

						url: 'server/editar_salon.php',
						type: 'POST',
						data: new FormData(editarSalonFormulario),
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

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE SALON



	
</script>



<script>
	//ELIMINACION DE GRUPOS
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var salon = $(this).attr("eliminacion");
		var nombreSalon = $(this).attr("salon");

		// console.log(GRUPOS);

		swal({
		  title: "¿Deseas eliminar "+nombreSalon+"?",
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
				url: 'server/eliminacion_salon.php',
				type: 'POST',
				data: { salon },
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