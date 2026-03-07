<?php  

	include('inc/header.php');
	$id_ram = $_GET['id_ram'];

	$sqlRama = "SELECT * FROM rama WHERE id_ram = '$id_ram'";
	$resultadoRama = mysqli_query($db, $sqlRama);
	$filaRama = mysqli_fetch_assoc($resultadoRama);

	$nom_ram = $filaRama['nom_ram'];
	
	
?>

<!-- BOTON FLOTANTE AGREGAR CICLOS-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus fa-1x" title="Agregar Ciclo" id="agregarCiclo"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CICLOS-->

<!-- CONTENIDO -->
<!--INICIO DE DESPLIEGUE DE CICLOS ESCOLARES-->
 <div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ciclo">
			<i class="fas fa-bookmark"></i> 
			Ciclos Escolares
		</span>
		<br>
	<div class=" badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
		<a href="ramas.php" title="Vuelve a Programas"><span class="text-white">Programas</span></a>
		<i class="fas fa-angle-double-right"></i>
		<a style="color: black;" href="" title="Estás aquí"><span>Ciclos</span></a>		
	</div>
	</div>
	<div class="col text-right">
	<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre de la Rama en la que estás">
		<i class="fas fa-certificate"></i>
		<?php echo $nom_ram; ?>
	</span>
	</div>		
</div>

<!-- FIN DE DESPLIEGUE DE BANDAGE CICLOS ESCOLARES-->

<!-- ROW TABLA -->

<div class="row">
	 
	<div class="col-md-12 text-center">
		<?php  

		$sqlCiclos = "SELECT * FROM ciclo WHERE id_ram1 = '$id_ram' ORDER BY id_cic ASC";
		$resultadoCiclos = mysqli_query($db, $sqlCiclos);


	?>
	<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th>#</th>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Inscripción</th>
				<th>Inicio</th>
				<th>Corte</th>
				<th>Fin</th>
				<th>Estatus</th>
				<th>Acción</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaCiclos = mysqli_fetch_assoc($resultadoCiclos)){

		?>
			<tr>
				<td><?php echo $i; $i++;?></td>
		
				<td><?php echo $filaCiclos['nom_cic']; ?></td>
				<td><?php echo $filaCiclos['des_cic']; ?></td>
				<td><?php echo fechaFormateadaCompacta($filaCiclos['ins_cic']); ?></td>
				<td><?php echo fechaFormateadaCompacta($filaCiclos['ini_cic']); ?></td>
				<td><?php echo fechaFormateadaCompacta($filaCiclos['cor_cic']); ?></td>
				<td><?php echo fechaFormateadaCompacta($filaCiclos['fin_cic']); ?></td>
				<td>
					<?php
						$hoy = date('Y-m-d');
						if ($filaCiclos['ins_cic'] <= $hoy  && $hoy <= $filaCiclos['ini_cic']) {
							echo "Periodo de Inscripcion";
						}else if($filaCiclos['ini_cic'] < $hoy  && $hoy <= $filaCiclos['fin_cic']){
							echo "Ciclo Activo";
						}else{
							echo "Ciclo Inactivo";
						}


					?>	
				</td>

				
				<!-- BOTONES DE ACCION -->
				<td>				
					<div class="d-flex flex-column">
						<a class="chip info-color text-white edicion" title="Editar <?php echo $filaCiclos['nom_cic']; ?>" edicion="<?php echo $filaCiclos['id_cic']; ?>">Editar</a>	
						
						<a class="chip danger-color text-white eliminacion" title="Eliminar <?php echo $filaCiclos['nom_cic']; ?>" eliminacion="<?php echo $filaCiclos['id_cic']; ?>" ciclo="<?php echo $filaCiclos['nom_cic']; ?> ">Eliminar</a>
					</div>
					<div class="d-flex flex-column">
						<a href="grupos.php?id_cic=<?php echo $filaCiclos['id_cic']; ?>" class="chip info-color text-white" title="Agregar grupos a <?php echo $filaCiclos['nom_cic']; ?>">Grupos</a>
						

						<a href="calendario_escolar.php?id_cic=<?php echo $filaCiclos['id_cic']; ?>" class="chip info-color text-white" title="Calendario Escolar de <?php echo $filaCiclos['nom_cic']; ?>">Calendario</a>
					</div>
					
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


<!-- CONTENIDO MODAL AGREGAR CICLOS -->
<div class="modal fade text-left" id="agregarCicloModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarCicloFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Ciclo Nuevo</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="nom_cic" name="nom_cic" class="form-control validate">
	          <label  for="form34">Ciclo</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="des_cic" name="des_cic" class="form-control validate">
	          <label  for="form34">Descripción</label>
	        </div>

	        <label for="Ingreso">Fecha de Inscripción</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="ins_cic" name="ins_cic" class="form-control validate">
	        </div>


	        <label for="Ingreso">Fecha de Inicio</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="ini_cic" name="ini_cic" class="form-control validate">
	        </div>

	        <label for="Ingreso">Fecha de Corte</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="cor_cic" name="cor_cic" class="form-control validate">
	        </div>


	        <label for="Ingreso">Final de Ciclo</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="fin_cic" name="fin_cic" class="form-control validate">
	        </div>


	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR CICLOS -->

<!-- CONTENIDO MODAL EDITAR CICLOS -->
<div class="modal fade text-left" id="editarCicloModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarCicloFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Ciclo</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        
	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Ciclo</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="descripcion" name="descripcion" class="form-control validate">
	          <label  for="form34">Descripción</label>
	        </div>

	        <label for="Ingreso">Fecha de Inscripción</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="inscripcion" name="inscripcion" class="form-control validate">
	        </div>


	        <label for="Ingreso">Fecha de Inicio</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="inicio" name="inicio" class="form-control validate">
	        </div>

	        <label for="Ingreso">Fecha de Corte</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="corte" name="corte" class="form-control validate">
	        </div>


	        <label for="Ingreso">Final de Ciclo</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="fin" name="fin" class="form-control validate">
	        </div>


	        <input type="hidden" value="" id="identificador" name="identificador">


	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>


	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR CICLOS -->




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

	//FORMULARIO DE CREACION DE CICLOS
	//CODIGO PARA AGREGAR CICLOS NUEVO ABRIENDO MODAL
	$('#agregarCiclo').on('click', function(event) {
		event.preventDefault();
		$('#agregarCicloModal').modal('show');
		$('#agregarCicloFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarCicloFormulario').on('submit', function(event) {
		event.preventDefault();
			
			var ins = $("#ins_cic").val();
			var ini = $("#ini_cic").val();
			var cor = $("#cor_cic").val();
			var fin = $("#fin_cic").val();
			var nom = $("#nom_cic").val();
			var des = $("#des_cic").val();
			console.log(ins, ini, cor, fin);


			if (ins != "" && ini != "" && cor != "" && fin != "" && nom != "" && des != "") {
				if(ins < ini && ini < cor && cor < fin){
					$.ajax({
			
						url: 'server/agregar_ciclo.php?id_ram=<?php echo $id_ram;?>',
						type: 'POST',
						data: new FormData(agregarCicloFormulario), 
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
					toastr.error('¡Inscripción debe ser menor a inicio, inicio menor que corte, y corte menor que fin de ciclo!');
				}
			}else{
				toastr.error('¡Todos los campos son obligatorios!');
			}

			
		
	});


	
</script>



<script>
	//ELIMINACION DE CICLOS
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var ciclo = $(this).attr("eliminacion");
		var nombreCiclo = $(this).attr("ciclo");

		// console.log(CICLOS);

		swal({
		  title: "¿Deseas eliminar "+nombreCiclo+"?",
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
				url: 'server/eliminacion_ciclo.php',
				type: 'POST',
				data: {ciclo},
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
	//EDICION DE CICLOS

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE CICLOS

	$('.edicion').on('click', function(){
		$('#editarCicloFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionCiclo = $(this).attr("edicion");
		$('#editarCicloFormulario label').addClass('active');
		$('#editarCicloFormulario i').addClass('active');

		//console.log(edicionCiclo);


		$.ajax({
			url: 'server/obtener_ciclo.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionCiclo},
			success: function(datos){

				$('#editarCicloModal').modal('show');
				$('#nombre').attr({value: datos.nom_cic});
				$('#descripcion').attr({value: datos.des_cic});
				$('#inscripcion').attr({value: datos.ins_cic});
				$('#inicio').attr({value: datos.ini_cic});
				$('#corte').attr({value: datos.cor_cic});
				$('#fin').attr({value: datos.fin_cic});
				$('#identificador').attr({value: datos.id_cic});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL CICLOS
				$('#editarCicloFormulario').on('submit', function(event) {
					event.preventDefault();

					var inscripcion = $("#inscripcion").val();
					var inicio = $("#inicio").val();
					var corte = $("#corte").val();
					var final = $("#fin").val();
					var nombre = $("#nombre").val();
					var descripcion = $("#descripcion").val();
					console.log(inscripcion, inicio, corte, final);

					if (inscripcion != "" && inicio != "" && corte != "" && final != "" && nombre != "" && descripcion != "") {
						if(inscripcion < inicio && inicio < corte && corte < final){
							$.ajax({
							
								url: 'server/editar_ciclo.php',
								type: 'POST',
								data: new FormData(editarCicloFormulario), 
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
						}else{
						toastr.error('¡Inscripción debe ser menor a inicio, inicio menor que corte, y corte menor que fin de ciclo!');
						}
					}else{
						toastr.error('¡Todos los campos son obligatorios!');
					}

				});				
			}
		});
	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE CICLOS

	
</script>