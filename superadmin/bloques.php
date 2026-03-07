<?php  

	include('inc/header.php');
	$id_mat = $_GET['id_mat'];

	$sqlMateria = "
		SELECT * 
		FROM materia 
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_mat = '$id_mat'";
	$resultadoMateria = mysqli_query($db, $sqlMateria);
	$filaRama = mysqli_fetch_assoc($resultadoMateria);

	$nom_mat = $filaRama['nom_mat'];
	$nom_ram = $filaRama['nom_ram'];
	$id_ram = $filaRama['id_ram'];


	

?>

<!-- BOTON FLOTANTE AGREGAR BLOQUE-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus fa-1x" title="Agregar Bloque" id="agregarBloque"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR BLOQUE-->

<!-- CONTENIDO -->

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Bloques">
			<i class="fas fa-bookmark"></i> 
			Bloques
		</span>
		<br>
		<div class="badge badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="materias.php?id_ram=<?php echo $id_ram; ?>" title="Vuelve a Materias">Materias</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Bloques</a>
		</div>
	</div>

	<div class="col text-right">

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materias de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Bloques de <?php echo $nom_mat; ?>">
			<i class="fas fa-certificate"></i>
			Materia: <?php echo $nom_mat; ?>
		</span>
		<br>

		
		
	</div>
	
</div>
<!-- FIN TITULO -->




<!-- INDICADORES -->
<div class="row">

	<!-- TOTAL BLOQUES -->
	<div class="col-md-3">

		<?php  

			$sqlTotalBloques = "
				SELECT *
				FROM bloque 
				WHERE id_mat6 = '$id_mat'
			";


			$resultadoTotalBloques = mysqli_query($db, $sqlTotalBloques);

			$totalBloques = mysqli_num_rows($resultadoTotalBloques);

		?>
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info" title="Conteo de Bloques">
				Total Bloques
			</div>
			<div class="card-body text-center">
				<h2 class="card-title">
					<span>
						<?php
							echo $totalBloques;
						?>
					</span>
				</h2>
			</div>
		</div>
	</div>
	<!-- FIN TOTAL BLOQUES -->


	<div class="col-md-3">

		<?php  
			$sqlTeorias = "
				SELECT id_vid
				FROM bloque
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN video ON video.id_blo1 = bloque.id_blo
				WHERE id_mat = '$id_mat'
				UNION
				SELECT id_wik
				FROM bloque
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN wiki ON wiki.id_blo2 = bloque.id_blo
				WHERE id_mat = '$id_mat'
				UNION
				SELECT id_arc
				FROM bloque
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN archivo ON archivo.id_blo3 = bloque.id_blo
				WHERE id_mat = '$id_mat'

			";
			$resultadoTeorias = mysqli_query($db, $sqlTeorias);

			$totalTeorias = mysqli_num_rows($resultadoTeorias);
			// echo $sqlTeorias;
		?>
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info" title="Total recursos teóricos">
				Recursos Teóricos
			</div>
			<div class="card-body text-center">
				<h2 class="card-title">
					<span>
						<?php
							echo $totalTeorias;
							


						?>
					</span>
				</h2>
			</div>
		</div>
	</div>


	<!-- TOTAL ALUMNOS -->
	<div class="col-md-3">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info">
				Total Actividades
			</div>
			<div class="card-body">
				<h2 class="card-title text-center">
					<span>
						<?php 


							$sqlActividades = "
								SELECT id_for AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, tip_for AS tipo
								FROM bloque
								INNER JOIN materia ON materia.id_mat = bloque.id_mat6
								INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
								WHERE id_mat = '$id_mat'
								UNION
								SELECT id_ent AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, tip_ent AS tipo
								FROM bloque
								INNER JOIN materia ON materia.id_mat = bloque.id_mat6
								INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
								WHERE id_mat = '$id_mat'
								UNION
								SELECT id_exa AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, tip_exa AS tipo
								FROM bloque
								INNER JOIN materia ON materia.id_mat = bloque.id_mat6
								INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
								WHERE id_mat = '$id_mat'

							";
							$resultadoActividades = mysqli_query($db, $sqlActividades);

							$totalActividades = mysqli_num_rows($resultadoActividades);

							echo $totalActividades;


						?>
					</span>
				</h2>
			</div>
		</div>
	</div>
	<!-- FIN TOTAL ALUMNOS -->

	<!-- TOTAL ALUMNOS INSCRITOS ACTIVOS -->
	<div class="col-md-3">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info">
				Total Puntos
			</div>
			<div class="card-body text-center">
				<h2 class="card-title">
					<span>
					<?php

						$sqlPuntos = "
							SELECT SUM(pun_for) AS puntaje
							FROM bloque
							INNER JOIN materia ON materia.id_mat = bloque.id_mat6
							INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
							WHERE id_mat = '$id_mat'
							UNION
							SELECT SUM(pun_ent) AS puntaje
							FROM bloque
							INNER JOIN materia ON materia.id_mat = bloque.id_mat6
							INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
							WHERE id_mat = '$id_mat'
							UNION
							SELECT SUM(pun_exa) AS puntaje
							FROM bloque
							INNER JOIN materia ON materia.id_mat = bloque.id_mat6
							INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
							WHERE id_mat = '$id_mat'

						";


						$resultadoPuntos = mysqli_query($db, $sqlPuntos);

						$totalPuntos = 0;
						while($filaPuntos = mysqli_fetch_assoc($resultadoPuntos)){
							$totalPuntos = $totalPuntos + $filaPuntos['puntaje'];
						}



						

						echo round($totalPuntos, 2);
						 

					?>
					</span>
				</h2>
			</div>
		</div>
	</div>

	<!-- FIN TOTAL ALUMNOS INSCRITOS ACTIVOS -->


	


	


</div>
<!-- FIN INDICADORES -->

<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-center">
		<?php  

			$sqlBloques = "SELECT * FROM bloque WHERE id_mat6 = '$id_mat' ORDER BY id_blo ASC";
			$resultadoBloques = mysqli_query($db, $sqlBloques);

		?>
	<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th class="letraPequena">#</th>
				<th class="letraPequena">Bloque</th>
				<th class="letraPequena">Descripción</th>
				<th class="letraPequena">Recursos Teóricos</th>
				<th class="letraPequena">Actividades</th>
				<th class="letraPequena">Puntos Totales</th>
				<th class="letraPequena">Acción</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaBloques = mysqli_fetch_assoc($resultadoBloques)){

		?>
			<?php $bloque=$filaBloques['id_blo']; ?>
			<tr>
				<td class="letraPequena"><?php echo $i; $i++;?></td>
		
				<td class="letraPequena"><?php echo $filaBloques['nom_blo']; ?></td>
				<td class="letraPequena"><?php echo $filaBloques['des_blo']; ?></td>
				<!--TEORIAS-->
				<td class="letraPequena"><?php  
						$sqlTeorias = "
							SELECT id_vid
							FROM bloque
							INNER JOIN video ON video.id_blo1 = bloque.id_blo
							WHERE id_blo = '$bloque'
							UNION
							SELECT id_wik
							FROM bloque
							INNER JOIN wiki ON wiki.id_blo2 = bloque.id_blo
							WHERE id_blo = '$bloque'
							UNION
							SELECT id_arc
							FROM bloque
							INNER JOIN archivo ON archivo.id_blo3 = bloque.id_blo
							WHERE id_blo = '$bloque'

						";
						$resultadoTeorias = mysqli_query($db, $sqlTeorias);

						$totalTeorias = mysqli_num_rows($resultadoTeorias);
						echo $totalTeorias;
					?></td>
				<!-- ACTIVIDADES -->
				<td class="letraPequena">
					<?php 


						$sqlActividades = "
							SELECT id_for AS id, nom_for AS actividad, pun_for AS puntaje, tip_for AS tipo
							FROM bloque
							INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
							WHERE id_blo = '$bloque'
							UNION
							SELECT id_ent AS id, nom_ent AS actividad, pun_ent AS puntaje, tip_ent AS tipo
							FROM bloque
							INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
							WHERE id_blo = '$bloque'
							UNION
							SELECT id_exa AS id, nom_exa AS actividad, pun_exa AS puntaje, tip_exa AS tipo
							FROM bloque
							INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
							WHERE id_blo = '$bloque'

						";
						//echo $sqlActividades;
						$resultadoActividades = mysqli_query($db, $sqlActividades);

						$totalActividades = mysqli_num_rows($resultadoActividades);

						echo $totalActividades;


					?>
				</td>
				<!-- FIN ACTIVIDADES -->
				<!-- PUNTOS TOTALES -->
				<td class="letraPequena">
					<?php

						$sqlPuntos = "
							SELECT SUM(pun_for) AS puntaje
							FROM bloque
							INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
							WHERE id_blo = '$bloque'
							UNION
							SELECT SUM(pun_ent) AS puntaje
							FROM bloque
							INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
							WHERE id_blo = '$bloque'
							UNION
							SELECT SUM(pun_exa) AS puntaje
							FROM bloque
							INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
							WHERE id_blo = '$bloque'

						";

						//echo $sqlPuntos;
						$resultadoPuntos = mysqli_query($db, $sqlPuntos);

						$totalPuntos = 0;
						while($filaPuntos = mysqli_fetch_assoc($resultadoPuntos)){
							$totalPuntos = $totalPuntos + $filaPuntos['puntaje'];
						}


						echo round($totalPuntos, 2);
						 

					?>
				</td>
				<!-- FIN PUNTOS TOTALES -->
				
				<!-- BOTONES DE ACCION -->
				<td class="flex-column">				
					<div class="chip  info-color text-white edicion" title="Editar <?php echo $filaBloques['nom_blo']; ?>" edicion="<?php echo $filaBloques['id_blo']; ?>"><p>Editar</p>
					</div>
					<div class="chip danger-color text-white eliminacion" title="Eliminar <?php echo $filaBloques['nom_blo']; ?>" eliminacion="<?php echo $filaBloques['id_blo']; ?>" bloque="<?php echo $filaBloques['nom_blo']; ?> ">
						<p>Eliminar</p>
					</div>	


					<a href="bloque_contenido.php?id_blo=<?php echo $filaBloques['id_blo']; ?>" title="Agregar contenido a <?php echo $filaBloques['nom_blo']; ?>"><div class="chip info-color text-white">
						<p>Contenido</p>
					</div></a>
					
					
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


<!-- CONTENIDO MODAL AGREGAR BLOQUE -->
<div class="modal fade text-left" id="agregarBloqueModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarBloqueFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Bloque</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nom_blo" name="nom_blo" class="form-control validate">
	          <label  for="form34">Bloque</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-question prefix grey-text"></i>
	          <input type="text" id="des_blo" min="0" name="des_blo" class="form-control validate">
	          <label  for="form29">Descripción</label>
	        </div>


	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR BLOQUE -->

<!-- CONTENIDO MODAL EDITAR BLOQUE -->
<div class="modal fade text-left" id="editarBloqueModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarMateriaFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Bloque</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Bloque</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-question prefix grey-text"></i>
	          <input type="text" min="0" id="descripcion" name="descripcion" class="form-control validate">
	          <label  for="form29">Descripción</label>
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
<!-- FIN CONTENIDO MODAL EDITAR BLOQUE -->




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

	//FORMULARIO DE CREACION DE BLOQUE
	//CODIGO PARA AGREGAR BLOQUE NUEVO ABRIENDO MODAL
	$('#agregarBloque').on('click', function(event) {
		event.preventDefault();
		$('#agregarBloqueModal').modal('show');
		$('#agregarBloqueFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarBloqueFormulario').on('submit', function(event) {
		event.preventDefault();
			
		$.ajax({
		
			url: 'server/agregar_bloque.php?id_mat=<?php echo $id_mat;?>',
			type: 'POST',
			data: new FormData(agregarBloqueFormulario), 
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
	//ELIMINACION DE BLOQUE
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var bloque = $(this).attr("eliminacion");
		var nombreBloque = $(this).attr("bloque");

		// console.log(BLOQUE);

		swal({
		  title: "¿Deseas eliminar "+nombreBloque+"?",
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
				url: 'server/eliminacion_bloque.php',
				type: 'POST',
				data: {bloque},
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
	//EDICION DE BLOQUE

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE BLOQUE

	$('.edicion').on('click', function(){
		$('#editarMateriaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionBloque = $(this).attr("edicion");
		$('#editarMateriaFormulario label').addClass('active');
		$('#editarMateriaFormulario i').addClass('active');

		//console.log(edicionBloque);


		$.ajax({
			url: 'server/obtener_bloque.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionBloque},
			success: function(datos){

				$('#editarBloqueModal').modal('show');
				$('#nombre').attr({value: datos.nom_blo});
				$('#descripcion').attr({value: datos.des_blo});
				$('#identificador').attr({value: datos.id_blo});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL BLOQUE
				$('#editarMateriaFormulario').on('submit', function(event) {
					event.preventDefault();

		
					$.ajax({
					
						url: 'server/editar_bloque.php',
						type: 'POST',
						data: new FormData(editarMateriaFormulario), 
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

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE BLOQUE

	
</script>