 <?php  

	include('inc/header.php');
	$id_ram = $_GET['id_ram'];

	$sqlRama = "SELECT * FROM rama WHERE id_ram = '$id_ram'";
	$resultadoRama = mysqli_query($db, $sqlRama);
	$filaRama = mysqli_fetch_assoc($resultadoRama);

	$nom_ram = $filaRama['nom_ram'];
	
	

?>

<!-- BOTON FLOTANTE AGREGAR DOCUMENTO-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus fa-1x" title="Agregar Documento" id="agregarDocumento"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR DOCUMENTO-->

<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Documentos">
			<i class="fas fa-bookmark"></i> 
			Documentación del Programa
		</span>
		<br>
		<div class="badge badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Documentos</a>
		</div>
		
	</div>

	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Documentos de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
		
	</div>
	
</div>
<!-- FIN TITULO -->


<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-center">
		<?php  

		$sqlDocumentos = "
			SELECT * 
			FROM documento_rama
			WHERE id_ram6 = '$id_ram'
		";

		$resultadoRamas = mysqli_query($db, $sqlDocumentos);


	?>
	<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			
			<tr>
			
				<th>#</th>
				<th>Documento</th>
				<th>Acción</th>

			</tr>
		
		</thead>


		<?php 
			$i = 1;
			while($filaDocumentos = mysqli_fetch_assoc($resultadoRamas)){
				$id_doc_ram = $filaDocumentos['id_doc_ram'];
		?>
			<tr>
				<td><?php echo $i; $i++;?></td>
		
				<td><?php echo $filaDocumentos['nom_doc_ram']; ?></td>

				
				<!-- BOTONES DE ACCION -->
				<td class="d-flex flex-row justify-content-center">

					<a class="chip info-color text-white edicion" title="Editar <?php echo $filaDocumentos['nom_doc_ram']; ?>" edicion="<?php echo $filaDocumentos['id_doc_ram']; ?>">Editar</a>	
					
					<a class="chip danger-color text-white eliminacion" title="Eliminar <?php echo $filaDocumentos['nom_doc_ram']; ?>" eliminacion="<?php echo $filaDocumentos['id_doc_ram']; ?>" documento="<?php echo $filaDocumentos['nom_doc_ram']; ?> ">Eliminar</a>


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


<!-- CONTENIDO MODAL AGREGAR DOCUMENTO -->
<div class="modal fade text-left" id="agregarDocumentoModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarDocumentoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Solicitar documento</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	        <div class="md-form">
	          	
	          	<i class="fas fa-file-alt prefix grey-text"></i>
	          	<input type="text" id="nom_doc_ram" name="nom_doc_ram" class="form-control validate">
	          	<label  for="nom_doc_ram">Documento</label>
	        
	        </div>


	        <!-- Group of material radios - option 1 -->
			<!-- div class="md-form">
				
				<div class="form-check">
				  	
				  	<input type="checkbox" class="form-check-input radioReporte" id="checkbox_est_doc_ram" name="checkbox_est_doc_ram">
				  	<label class="form-check-label letraPequena" for="checkbox_est_doc_ram">Permitir subir archivo</label>

				</div>
			
			</div> -->

	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        
	        <button class="btn btn-info btn-rounded waves-effect btn-sm" type="submit" id="btn_formulario">
	        	Guardar <i class="fas fa-paper-plane-o ml-1"></i>
	        </button>

	        <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                Cancelar
            </a> 
	      
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR DOCUMENTO -->

<!-- CONTENIDO MODAL EDITAR DOCUMENTO -->
<div class="modal fade text-left" id="editarDocumentoModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarDocumentoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar documento</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-file-alt prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="nombre">Documento</label>
	        </div>



	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">	         
	        </div>


	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        
	        <button class="btn btn-info btn-rounded waves-effect btn-sm" type="submit">

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
<!-- FIN CONTENIDO MODAL EDITAR DOCUMENTO -->




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
	//FORMULARIO DE CREACION DE DOCUMENTO
	//CODIGO PARA AGREGAR DOCUMENTO NUEVO ABRIENDO MODAL
	$('#agregarDocumento').on('click', function(event) {
		event.preventDefault();
		$('#agregarDocumentoModal').modal('show');
		$('#agregarDocumentoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR

		setTimeout(function(){
		
			$('#nom_doc_ram').focus();
		
		}, 300 );

	});


	$('#agregarDocumentoFormulario').on('submit', function(event) {
		event.preventDefault();
		
		$("#btn_formulario").attr('disabled','disabled');
		var agregarDocumentoFormulario = new FormData( $('#agregarDocumentoFormulario')[0] );


		$.ajax({
		
			url: 'server/agregar_documento_rama.php?id_ram=<?php echo $id_ram;?>',
			type: 'POST',
			data: agregarDocumentoFormulario, 
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
	//ELIMINACION DE DOCUMENTO
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var documento = $(this).attr("eliminacion");
		var nombreDocumento = $(this).attr("documento");

		// console.log(DOCUMENTO);

		swal({
		  title: "¿Deseas eliminar "+nombreDocumento+"?",
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
				url: 'server/eliminacion_documento_rama.php',
				type: 'POST',
				data: {documento},
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
	//EDICION DE DOCUMENTO

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE DOCUMENTO

	$('.edicion').on('click', function(){
		$('#editarDocumentoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionDocumento = $(this).attr("edicion");
		$('#editarDocumentoFormulario label').addClass('active');
		$('#editarDocumentoFormulario i').addClass('active');

		//console.log(edicionDocumento);


		$.ajax({
			url: 'server/obtener_documento_rama.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionDocumento},
			success: function(datos){

				$('#editarDocumentoModal').modal('show');
				$('#nombre').attr({value: datos.nom_doc_ram});


				$('#identificador').attr({value: datos.id_doc_ram});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL DOCUMENTO
				$('#editarDocumentoFormulario').on('submit', function(event) {
					event.preventDefault();

					var editarDocumentoFormulario = new FormData( $('#editarDocumentoFormulario')[0] );

					editarDocumentoFormulario.append('est_doc_ram', 'Inactivo');

					$.ajax({
					
						url: 'server/editar_documento_rama.php',
						type: 'POST',
						data: editarDocumentoFormulario, 
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

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE DOCUMENTO

	
</script>