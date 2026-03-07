<?php  

	include('inc/header.php');
	$id_cic = $_GET['id_cic'];         
	$sqlCiclo = "
		SELECT * 
		FROM ciclo 
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_cic = '$id_cic'
	";
	$resultadoCiclo = mysqli_query($db, $sqlCiclo);
	$filaCiclo = mysqli_fetch_assoc($resultadoCiclo);

	$nom_cic = $filaCiclo['nom_cic'];
	$nom_ram = $filaCiclo['nom_ram'];	
	$id_ram= $filaCiclo['id_ram1'];	

?>
<style>
	#flecha {
  position: absolute;
  right: -150px;
  width: 300px;
  padding: 10px;
  z-index: 99;
}
</style>

<!-- BOTON FLOTANTE AGREGAR GRUPOS-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus fa-1x" title="Agregar Ciclo" id="agregarGrupo"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR GRUPOS-->

<!-- CONTENIDO --><!--INICIO DE DESPLIEGUE DE CICLOS ESCOLARES-->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Grupos con los que cunetas en la Rama">
			<i class="fas fa-bookmark"></i> 
			Grupos
		</span>
		<br>
		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al Inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="ramas.php" title="Vuelve a Programas"><span class="text-white">Programas</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="Ciclos.php?id_ram=<?php echo "$id_ram"; ?>" title="Vuelve a los Ciclos"><span class="text-white">Ciclos</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="" title="Estás aquí"><span style="color: black;">Grupos</span></a>		
		</div>
	</div>
	<div class="col text-right">


		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materias de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
		<br>
		<br>


		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Ciclo en el que estás">
			<i class="fas fa-certificate"></i>
			 Ciclo: <?php echo $nom_cic; ?>
		</span>	
	</div>
</div>
<!-- FIN DE DESPLIEGUE DE BANDAGE CICLOS ESCOLARES-->
<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-center">
		<?php  

		$sqlGrupos = "
			SELECT * 
			FROM grupo
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1 
			WHERE id_cic1 = '$id_cic' 
			ORDER BY id_gru ASC";
		$resultadoGrupos = mysqli_query($db, $sqlGrupos);


	?>
	<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th>#</th>
				<th>Grupo</th>
				<th>Acción Grupo</th>
				<th>Acción Horario</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaGrupos = mysqli_fetch_assoc($resultadoGrupos)){

		?>
			<tr>
				<td><?php echo $i; $i++;?></td>
		
				<td><?php echo $filaGrupos['nom_gru']; ?></td>

				
				<!-- BOTONES DE ACCION -->
				<td class="d-flex flex-row justify-content-center">				

					<a class="chip info-color text-white edicion" title="Editar <?php echo $filaGrupos['nom_gru']; ?>" edicion="<?php echo $filaGrupos['id_gru']; ?>">Editar</a>	
					
					<a class="chip danger-color text-white eliminacion" title="Eliminar <?php echo $filaGrupos['nom_gru']; ?>" eliminacion="<?php echo $filaGrupos['id_gru']; ?>" grupo="<?php echo $filaGrupos['nom_gru']; ?> ">Eliminar</a>
				</td>


				<td>
					<?php
						if ($filaGrupos['mod_ram'] == 'Online') {
							//VALIDACION DE MODALIDAD DE LA RAMA

							$grupo  = $filaGrupos['id_gru'];

							$sqlValidacionHorario = "
											SELECT * 
									    	FROM sub_hor
											WHERE id_gru1 = $grupo";


							$resultadoValidacionGrupo = mysqli_query($db, $sqlValidacionHorario);

							$validacionGrupos = mysqli_num_rows($resultadoValidacionGrupo);
							//echo $sqlValidacionHorario;
							if ($validacionGrupos == 0) {
					?>

							<a class="chip info-color text-white" href="horario_online.php?id_gru=<?php echo $filaGrupos['id_gru']; ?>" grupo="<?php echo $filaGrupos['id_gru']; ?> " title="Agregar Horario a <?php echo $filaGrupos['nom_gru']; ?>">Crear horario</a>

					<?php  
							}else{
					?>


								<a href="horario_online_visualizacion.php?id_gru=<?php echo $filaGrupos['id_gru']; ?>" title="Ver Horario" class="chip info-color text-white">Ver horario</a>


								<a class="chip info-color text-white" title="Editar horario del grupo <?php echo $filaGrupos['nom_gru']; ?>" href="editar_horario_online.php?id_gru=<?php echo $filaGrupos['id_gru']; ?>">
									Editar
								</a>	


								<a class="chip danger-color text-white eliminacionHorario" title="Eliminar horario del grupo <?php echo $filaGrupos['nom_gru']; ?>" eliminacionHorario="<?php echo $filaGrupos['id_gru']; ?>" grupo="<?php echo $filaGrupos['nom_gru']; ?> ">
									Eliminar
								</a>
					<?php
							}
					?>


						
					<?php	 	
						}else{
							// MODALIDAD PRESENCIAL

							$grupo  = $filaGrupos['id_gru'];

							$sqlValidacionHorario = "
											SELECT * 
									    	FROM sub_hor
									    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
											WHERE id_gru1 = $grupo";


							$resultadoValidacionGrupo = mysqli_query($db, $sqlValidacionHorario);

							$validacionGrupos = mysqli_num_rows($resultadoValidacionGrupo);
							//echo $sqlValidacionHorario;
							if ($validacionGrupos == 0) {
					?>
								<a class="chip info-color text-white" href="horario_presencial.php?id_gru=<?php echo $filaGrupos['id_gru']; ?>" grupo="<?php echo $filaGrupos['id_gru']; ?> " title="Agregar Horario a <?php echo $filaGrupos['nom_gru']; ?>">Crear</a>

					<?php  
							}else{
					?>

								<a href="horario_presencial_visualizacion.php?id_gru=<?php echo $filaGrupos['id_gru']; ?>" title="Ver Horario" class="chip info-color text-white">Ver</a>

								<a class="chip info-color text-white" title="Editar horario del grupo <?php echo $filaGrupos['nom_gru']; ?>" href="editar_horario_presencial.php?id_gru=<?php echo $filaGrupos['id_gru']; ?>">
									Editar
								</a>	

								<a class="chip danger-color text-white eliminacionHorario" title="Eliminar horario del grupo <?php echo $filaGrupos['nom_gru']; ?>" eliminacionHorario="<?php echo $filaGrupos['id_gru']; ?>" grupo="<?php echo $filaGrupos['nom_gru']; ?> ">
									Eliminar
								</a>
					<?php
							}
					?>

						


					<?php  
						}
					?>

						
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


<!-- CONTENIDO MODAL AGREGAR GRUPOS -->
<div class="modal fade text-left" id="agregarGrupoModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarGrupoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Grupo Nuevo</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	        <div class="md-form mb-5">
	          <i class="fas fa-users prefix grey-text"></i>
	          <input type="text" id="nom_gru" name="nom_gru" class="form-control validate">
	          <label  for="form34">Grupo</label>
	        </div>


	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR GRUPOS -->

<!-- CONTENIDO MODAL EDITAR GRUPOS -->
<div class="modal fade text-left" id="editarGrupoModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarGrupoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Grupo</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        
	        <div class="md-form mb-5">
	          <i class="fas fa-users prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Grupo</label>
	        </div>


	        <input type="hidden" id="identificador" name="identificador">
			
			<div class="modal-footer d-flex justify-content-center">
	        
	        	<button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i>
	        	</button>
	      	</div>
	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR GRUPOS -->


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

	//FORMULARIO DE CREACION DE GRUPOS
	//CODIGO PARA AGREGAR GRUPOS NUEVO ABRIENDO MODAL
	$('#agregarGrupo').on('click', function(event) {
		event.preventDefault();
		$('#agregarGrupoModal').modal('show');
		$('#agregarGrupoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarGrupoFormulario').on('submit', function(event) {
		event.preventDefault();
			

			$.ajax({
	
				url: 'server/agregar_grupo.php?id_cic=<?php echo $id_cic;?>',
				type: 'POST',
				data: new FormData(agregarGrupoFormulario), 
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
	//ELIMINACION DE GRUPOS
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var grupo = $(this).attr("eliminacion");
		var nombreGrupo = $(this).attr("grupo");

		// console.log(GRUPOS);

		swal({
		  title: "¿Deseas eliminar "+nombreGrupo+"?",
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
				url: 'server/eliminacion_grupo.php',
				type: 'POST',
				data: {grupo},
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
	//EDICION DE GRUPOS

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE GRUPOS

	$('.edicion').on('click', function(){
		$('#editarGrupoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionGrupo = $(this).attr("edicion");
		$('#editarGrupoFormulario label').addClass('active');
		$('#editarGrupoFormulario i').addClass('active');

		//console.log(edicionGrupo);

		$.ajax({
			url: 'server/obtener_grupo.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionGrupo},
			success: function(datos){

				$('#editarGrupoModal').modal('show');
				$('#nombre').attr({value: datos.nom_gru});
				$('#identificador').attr({value: datos.id_gru});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL GRUPOS
				$('#editarGrupoFormulario').on('submit', function(event) {
					event.preventDefault();

				
					$.ajax({
					
						url: 'server/editar_grupo.php',
						type: 'POST',
						data: new FormData(editarGrupoFormulario), 
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

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE GRUPOS

	
</script>





<script>
	//ELIMINACION DE HORARIOS
	$('.eliminacionHorario').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var grupo = $(this).attr("eliminacionHorario");
		var nombreGrupo = $(this).attr("grupo");

		// console.log(HORARIOS);

		swal({
		  title: "¿Deseas eliminar el horario de "+nombreGrupo+"?",
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
				url: 'server/eliminacion_horario.php',
				type: 'POST',
				data: {grupo},
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