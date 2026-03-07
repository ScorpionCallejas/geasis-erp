 <?php  

	include('inc/header.php');
	$id_ram = $_GET['id_ram'];

	$sqlRama = "SELECT * FROM rama WHERE id_ram = '$id_ram'";
	$resultadoRama = mysqli_query($db, $sqlRama);
	$filaRama = mysqli_fetch_assoc($resultadoRama);

	$nom_ram = $filaRama['nom_ram'];
	
	

?>

<!-- BOTON FLOTANTE AGREGAR MATERIA-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus fa-1x" title="Agregar Materia" id="agregarMateria"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR MATERIA-->

<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Materias">
			<i class="fas fa-bookmark"></i> 
			Materias
		</span>
		<br>
		<div class="badge badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Materias</a>
		</div>
		
	</div>

	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materias de <?php echo $nom_ram; ?>">
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

		$sqlMaterias = "SELECT * FROM materia WHERE id_ram2 = '$id_ram' ORDER BY id_mat DESC";
		$resultadoRamas = mysqli_query($db, $sqlMaterias);


	?>
	<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th class="letraPequena">#</th>
				<th class="letraPequena">Materia</th>
				<th class="letraPequena">Nivel</th>
				<th class="letraPequena">Bloques</th>
<!-- 				<th class="letraPequena">Recursos Teóricos</th> -->

				<th class="letraPequena">Videos</th>
				<th class="letraPequena">Wikis</th>
				<th class="letraPequena">Archivos</th>

				<th class="letraPequena">Actividades</th>
				<th class="letraPequena">Puntos Totales</th>
				<th class="letraPequena">Acción</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaMaterias = mysqli_fetch_assoc($resultadoRamas)){
				$id_mat = $filaMaterias['id_mat'];
		?>
			<tr>
				<td class="letraPequena"><?php echo $i; $i++;?></td>
		
				<td class="letraPequena"><?php echo $filaMaterias['nom_mat']; ?></td>
				<td class="letraPequena"><?php echo $filaMaterias['cic_mat']; ?></td>
				<!-- BLOQUES -->
				<td class="letraPequena">
					<?php  

						$sqlTotalBloques = "
							SELECT *
							FROM bloque 
							WHERE id_mat6 = '$id_mat'
						";


						$resultadoTotalBloques = mysqli_query($db, $sqlTotalBloques);

						$totalBloques = mysqli_num_rows($resultadoTotalBloques);
						echo $totalBloques;
					?>


				</th>
				<!-- FIN BLOQUES -->

				
				<td class="letraPequena">
					<?php  
						$sqlTotalVideos = "
							SELECT *
							FROM bloque
							INNER JOIN video ON video.id_blo1 = bloque.id_blo
							WHERE id_mat6 = '$id_mat'
						";

						// echo $sqlTotalVideos;

						$resultadoTotalVideos = mysqli_query( $db, $sqlTotalVideos );

						$totalVideos = mysqli_num_rows( $resultadoTotalVideos );

						echo $totalVideos
					?>
				</td>

				<td class="letraPequena">
					<?php  

						$sqlTotalWikis = "
							SELECT *
							FROM bloque
							INNER JOIN wiki ON wiki.id_blo2 = bloque.id_blo
							WHERE id_mat6 = '$id_mat'
						";

						$resultadoTotalWikis = mysqli_query( $db, $sqlTotalWikis );

						$totalWikis = mysqli_num_rows( $resultadoTotalWikis );

						echo $totalWikis
					?>
				</td>
				
				<td class="letraPequena">
					<?php  

						$sqlTotalArchivo = "
							SELECT *
							FROM bloque
							INNER JOIN archivo ON archivo.id_blo3 = bloque.id_blo
							WHERE id_mat6 = '$id_mat'
						";

						$resultadoTotalArchivo = mysqli_query( $db, $sqlTotalArchivo );

						$totalArchivo = mysqli_num_rows( $resultadoTotalArchivo );

						echo $totalArchivo
					?>
				</td>

				<!-- TEORIAS -->
				<!-- <td class="letraPequena"> -->
					<?php  
						// $sqlTeorias = "
						// 	SELECT id_vid
						// 	FROM bloque
						// 	INNER JOIN materia ON materia.id_mat = bloque.id_mat6
						// 	INNER JOIN video ON video.id_blo1 = bloque.id_blo
						// 	WHERE id_mat = '$id_mat'
						// 	UNION
						// 	SELECT id_wik
						// 	FROM bloque
						// 	INNER JOIN materia ON materia.id_mat = bloque.id_mat6
						// 	INNER JOIN wiki ON wiki.id_blo2 = bloque.id_blo
						// 	WHERE id_mat = '$id_mat'
						// 	UNION
						// 	SELECT id_arc
						// 	FROM bloque
						// 	INNER JOIN materia ON materia.id_mat = bloque.id_mat6
						// 	INNER JOIN archivo ON archivo.id_blo3 = bloque.id_blo
						// 	WHERE id_mat = '$id_mat'

						// ";
						// $resultadoTeorias = mysqli_query($db, $sqlTeorias);

						// $totalTeorias = mysqli_num_rows($resultadoTeorias);
						// echo $totalTeorias;
					?>
					
				<!-- </td> -->
				<!-- FIN TEORIAS -->


				<!-- ACTIVIDADES -->
				<td class="letraPequena">
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
				</td>
				<!-- FIN ACTIVIDADES -->

				<!-- PUNTOS TOTALES -->
				<td class="letraPequena">
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
				</td>
				<!-- FIN PUNTOS TOTALES -->
				
				<!-- BOTONES DE ACCION -->
				<td class="">				
				
					<a  class="chip  info-color text-white edicion " edicion="<?php echo $filaMaterias['id_mat']; ?>" title="Editar <?php echo $filaMaterias['nom_mat']; ?>">Editar</a>
				
					
					<a class="chip info-color text-white" href="bloques.php?id_mat=<?php echo $filaMaterias['id_mat']; ?>" title="Agregar bloques a <?php echo $filaMaterias['nom_mat']; ?>">Bloques</a>

				
					<a class="chip red text-white eliminacion" title="Eliminar <?php echo $filaMaterias['nom_mat']; ?>" eliminacion="<?php echo $filaMaterias['id_mat']; ?>" materia="<?php echo $filaMaterias['nom_mat']; ?> ">Eliminar</a>


					<a class="chip red text-white vaciarContenido" title="Vaciar contenidos teóricos y prácticos de <?php echo $filaMaterias['nom_mat']; ?>" id_mat="<?php echo $filaMaterias['id_mat']; ?>" nom_mat="<?php echo $filaMaterias['nom_mat']; ?> ">Vaciar contenido</a>


					<a class="chip bg-info text-white copiarContenido" title="Copiar contenidos teóricos y prácticos de <?php echo $filaMaterias['nom_mat']; ?>" id_mat="<?php echo $filaMaterias['id_mat']; ?>" nom_mat="<?php echo $filaMaterias['nom_mat']; ?> ">Copiar contenido</a>
									
					
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


<!-- CONTENIDO MODAL AGREGAR MATERIA -->
<div class="modal fade text-left" id="agregarMateriaModal">

	<div class="modal-dialog" role="document">

		<div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Agregar Materia</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
		
		<div class="modal-content">
			<div class="modal-body">


				<!-- LAYOUT TAB -->
					<div class="modal-c-tabs">

						<!-- Nav tabs -->
						<ul class="nav md-pills nav-justified pills-info mt-4 mx-4" role="tablist" style="font-size: 15px;">
						  
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#panelConsultaAlumno1" role="tab">
									Agregar materia
								</a>
							</li>


							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#panelConsultaAlumno2" role="tab">
								  	Cargar CSV
								</a>
							</li>
						</ul>

						<!-- TAB PANELS -->
						<div class="tab-content pt-3">
						  
						  	<!-- PANEL 1-->
						  	<!-- GENERAL -->
						  	<div class="tab-pane fade in show active" id="panelConsultaAlumno1" role="tabpanel">


						  	
							    <!--BODY-->
							    <div class="modal-body mb-1">
									<!-- CODIGO -->

									<form id="agregarMateriaFormulario" enctype="multipart/form-data" method="POST" tipo="formulario">
									      
									      <div class="modal-body mx-3">

									        <div class="md-form mb-5">
									          <i class="fas fa-atlas prefix grey-text"></i>
									          <input type="text" id="nom_mat" name="nom_mat" class="form-control validate">
									          <label  for="nom_mat">Materia</label>
									        </div>

									        <div class="md-form mb-5">
									          <i class="fas fa-calendar-week prefix grey-text"></i>
									          <input type="number" id="cic_mat" min="0" name="cic_mat" class="form-control validate">
									          <label  for="cic_mat">Nivel</label>
									        </div>


									      </div>

									      <div class="modal-footer d-flex justify-content-center">
									        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
									      </div>

									</form>

									<!-- FIN CODIGO -->
							    </div>
							    <!--FIN BODY-->

							</div>
						  	<!-- FIN GENERAL -->
						  	<!--/.FIN PANEL 1-->

						  <!--PANEL 2-->
						  	<!-- ACADEMICO -->
							<div class="tab-pane fade" id="panelConsultaAlumno2" role="tabpanel">

							    <!--BODY-->
							    <div class="modal-body">
								<!-- CODIGO -->

									<form id="agregarCsvFormulario" enctype="multipart/form-data" method="POST" tipo="csv">
									    
									      
									      <div class="modal-body mx-3">

									        <div class="md-form mb-5">

												<label for="">Carga un CSV</label>

									          	<div id="contenedor_imagen_noticia">
				                  
								                  <div class="file-upload-wrapper" >
								                    
								                    <div class="input-group mb-3 border border-success">

								                      <input type="file" name="csv_materias" class="file-upload" style="border-radius: 50px;"/ >
								                    
								                    </div>
								                  
								                  </div>

								                </div>

									        </div>

								


									      </div>

									      <div class="modal-footer d-flex justify-content-center">
									        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
									      </div>

									</form>


								<!-- FIN CODIGO -->
								</div>
								<!-- FIN BODY -->
							</div>
						</div>
						
					</div>
					<!-- FIN LAYOUT TAB -->
				
			</div>
		</div>

	</div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR MATERIA -->

<!-- CONTENIDO MODAL EDITAR MATERIA -->
<div class="modal fade text-left" id="editarMateriaModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarMateriaFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Materia</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-atlas prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Materia</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-calendar-week prefix grey-text"></i>
	          <input type="number" min="0" id="ciclo" name="ciclo" class="form-control validate">
	          <label  for="form29">Ciclo</label>
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
<!-- FIN CONTENIDO MODAL EDITAR MATERIA -->



<!-- COPIAR MATERIA -->
<!-- Modal -->
<div class="modal fade" id="modal_copiar_materia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content " style="border-radius: 20px;">
	      	<div class="modal-header">
	        	<h5 class="modal-title" id="titulo_copiar_materia"></h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true">&times;</span>
	        	</button>
	      	</div>

	      	<span id="materia_emisora" id_mat="">
			<span id="nombre_materia_emisora" id_mat="">
	      	<div class="modal-body" id="contenedor_copiar_materia">
	        	
	      	</div>
	      	<div class="modal-footer">
	        	
	        	<button type="button" class="btn btn-info waves-effect btn-sm btn-rounded" id="btn_copiar_materia">Guardar</button>

	        	<button type="button" class="btn btn-secondary waves-effect btn-sm btn-rounded" data-dismiss="modal">Cancelar</button>
	      	</div>
	    </div>
  	</div>
</div>
<!-- FIN COPIAR MATERIA -->

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

	//FORMULARIO DE CREACION DE MATERIA
	//CODIGO PARA AGREGAR MATERIA NUEVO ABRIENDO MODAL
	$('#agregarMateria').on('click', function(event) {
		event.preventDefault();
		$('#agregarMateriaModal').modal('show');
		$('#agregarMateriaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarMateriaFormulario').on('submit', function(event) {
		event.preventDefault();
			
		$.ajax({
		
			url: 'server/agregar_materia.php?id_ram=<?php echo $id_ram;?>',
			type: 'POST',
			data: new FormData(agregarMateriaFormulario), 
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
	//ELIMINACION DE MATERIA
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var materia = $(this).attr("eliminacion");
		var nombreMateria = $(this).attr("materia");

		// console.log(MATERIA);

		swal({
		  title: "¿Deseas eliminar "+nombreMateria+"?",
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
				url: 'server/eliminacion_materia.php',
				type: 'POST',
				data: {materia},
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
	//ELIMINACION DE MATERIA
	$('.vaciarContenido').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_mat = $(this).attr("id_mat");
		var nom_mat = $(this).attr("nom_mat");

		// console.log(MATERIA);

		swal({
		  title: "¿Deseas vaciar contenidos de "+nom_mat+"?",
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
				url: 'server/eliminacion_contenidos_materia.php',
				type: 'POST',
				data: { id_mat },
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
	//EDICION DE MATERIA

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE MATERIA

	$('.edicion').on('click', function(){
		$('#editarMateriaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionMateria = $(this).attr("edicion");
		$('#editarMateriaFormulario label').addClass('active');
		$('#editarMateriaFormulario i').addClass('active');

		//console.log(edicionMateria);


		$.ajax({
			url: 'server/obtener_materia.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionMateria},
			success: function(datos){

				$('#editarMateriaModal').modal('show');
				$('#nombre').attr({value: datos.nom_mat});
				$('#ciclo').attr({value: datos.cic_mat});
				$('#identificador').attr({value: datos.id_mat});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL MATERIA
				$('#editarMateriaFormulario').on('submit', function(event) {
					event.preventDefault();

		
					$.ajax({
					
						url: 'server/editar_materia.php',
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

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE MATERIA

	
</script>


<script>
	$('.file-upload').file_upload();


	$('#agregarCsvFormulario').on('submit', function(event) {
		event.preventDefault();

		var tipo = $( this ).attr( 'tipo' );
			
		$.ajax({
		
			url: 'server/agregar_materia.php?id_ram=<?php echo $id_ram;?>&tipo='+tipo,
			type: 'POST',
			data: new FormData( agregarCsvFormulario ), 
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


<!-- COPIADO DE CONTENIDO -->
<script>

	$('.copiarContenido').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_mat = $(this).attr("id_mat");
		var nom_mat = $(this).attr("nom_mat");

		// alert(id_mat);

		$('#materia_emisora').attr( "id_mat", id_mat );
		$('#nombre_materia_emisora').attr( "nom_mat", nom_mat );

		$("#btn_copiar_materia").removeAttr('disabled').html('Guardar');

		$('#modal_copiar_materia').modal('show');
		$('#titulo_copiar_materia').html("Copiar contenido de "+nom_mat);

		$('#contenedor_copiar_materia').html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

		$.ajax({
			url: 'server/obtener_materia_destino.php',
			type: 'POST',
			success: function( respuesta ){

				// console.log( respuesta );
				$("#contenedor_copiar_materia").html( respuesta );

			}
		});
		

		// console.log(MATERIA);

		
	});


	$("#btn_copiar_materia").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var materia_destino = $('#selector_materia').val();
		var nombre_materia_destino = $('#selector_materia option:selected').attr('nom_mat');
		var nom_mat = $('#nombre_materia_emisora').attr("nom_mat");
		var id_mat = $('#materia_emisora').attr("id_mat");;

		

		swal({
		  title: "¿Deseas copiar los contenidos de "+nom_mat+" a: "+nombre_materia_destino+"?",
		  text: "¡Una vez confirmes se copiarán todos los datos relacionados a ese registro!",
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
		    $("#btn_copiar_materia").attr('disabled', 'disabled').html('<i class="fas fa-cog fa-spin"></i> Copiando...');

		    $.ajax({
				url: 'server/agregar_contenido_materia.php',
				type: 'POST',
				data: { id_mat, materia_destino },
				success: function(respuesta){
					
					$("#btn_copiar_materia").removeAttr('disabled').html('Guardar');
					console.log(respuesta);

					console.log("Exito en consulta");
					swal("Copiado correctamente", "Continuar", "success", {button: "Aceptar",});

					$('#modal_copiar_materia').modal('hide');


				}
			});
		    
		  }
		});

	});


</script>
<!-- FIN COPIADO DE CONTENIDO -->