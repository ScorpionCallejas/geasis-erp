<?php  

	include('inc/header.php');
	$id_ram = $_GET['id_ram'];



	$sqlRama = "SELECT * FROM rama WHERE id_ram = '$id_ram'";
	$resultadoRama = mysqli_query($db, $sqlRama);
	$filaRama = mysqli_fetch_assoc($resultadoRama);

	$nom_ram = $filaRama['nom_ram'];

?>

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Actividades">
			<i class="fas fa-bookmark"></i> 
			Actividades
		</span>
		<br>
		<div class="badge badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Actividades</a>
		</div>
		
	</div>

	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Actividades de <?php echo $nom_ram; ?>">
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

			$sqlBloques = "
				SELECT nom_for AS actividad, ini_for AS inicio, fin_for AS fin, pun_for AS puntos, tip_for AS tipo, nom_mat AS materia, nom_ram AS programa, nom_pla AS plantel, nom_blo AS bloque, id_for AS identificador, cic_mat AS nivel
				FROM foro
				INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_ram = '$id_ram'
				UNION
				SELECT nom_ent AS actividad, ini_ent AS inicio, fin_ent AS fin, pun_ent AS puntos, tip_ent AS tipo, nom_mat AS materia, nom_ram AS programa, nom_pla AS plantel, nom_blo AS bloque, id_ent AS identificador, cic_mat AS nivel
				FROM entregable
				INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_ram = '$id_ram'
				UNION
				SELECT nom_exa AS actividad, ini_exa AS inicio, fin_exa AS fin, pun_exa AS puntos, tip_exa AS tipo, nom_mat AS materia, nom_ram AS programa, nom_pla AS plantel, nom_blo AS bloque, id_exa AS identificador, cic_mat AS nivel
				FROM examen
				INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_ram = '$id_ram'
			";

			// echo $sqlBloques;
			$resultadoBloques = mysqli_query($db, $sqlBloques);

		?>

		<div class="table-responsive">
			
			<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
				<thead class="bg-info text-white">
					<tr>
						<th class="letraMediana font-weight-normal">#</th>
						<th class="letraMediana font-weight-normal">Actividad</th>
						<th class="letraMediana font-weight-normal">Inicio</th>
						<th class="letraMediana font-weight-normal">Fin</th>
						<th class="letraMediana font-weight-normal">Puntos</th>
						<th class="letraMediana font-weight-normal">Tipo</th>
						<th class="letraMediana font-weight-normal">Bloque</th>
						<th class="letraMediana font-weight-normal">
							Nivel
						</th>
						<th class="letraMediana font-weight-normal">Materia</th>
						<th class="letraMediana font-weight-normal">Programa</th>
						<th class="letraMediana font-weight-normal">Acción</th>
					</tr>
				</thead>


				<?php 
					$i = 1;
					while($filaBloques = mysqli_fetch_assoc($resultadoBloques)){

				?>

					<tr>

						<td class="letraMediana font-weight-normal"><?php echo $i; $i++;?></td>
						
						<td class="letraMediana font-weight-normal">

							<?php
								if ( $filaBloques['tipo'] == 'Foro' ) {
							?>
								<a href="foro_bloque.php?id_for=<?php echo $filaBloques['identificador']; ?>" class="btn-link" target="_blank">

							<?php
								} else if ( $filaBloques['tipo'] == 'Entregable' ) {
							?>
								<a href="entregable_bloque.php?id_ent=<?php echo $filaBloques['identificador']; ?>" class="btn-link" target="_blank">

							<?php	
								} else if ( $filaBloques['tipo'] == 'Examen' ) {
							?>
								<a href="examen_bloque.php?id_exa=<?php echo $filaBloques['identificador']; ?>" class="btn-link" target="_blank">
							<?php	
								}
							?>
							
								<?php echo $filaBloques['actividad']; ?>
							</a>
						</td>

						<td class="letraMediana font-weight-normal" data-order="<?php echo $filaBloques['inicio']; ?>">
							<input class="form-control letraMediana font-weight-normal polaridadActividad" polaridad="inicio" type="number" tipo="<?php echo $filaBloques['tipo']; ?>" identificador="<?php echo $filaBloques['identificador']; ?>" value="<?php echo $filaBloques['inicio']; ?>">
						</td>

						<td class="letraMediana font-weight-normal" data-order="<?php echo $filaBloques['fin']; ?>">
							<input class="form-control letraMediana font-weight-normal polaridadActividad" polaridad="fin" type="number" tipo="<?php echo $filaBloques['tipo']; ?>" identificador="<?php echo $filaBloques['identificador']; ?>" value="<?php echo $filaBloques['fin']; ?>">
						</td>

						<td class="letraMediana font-weight-normal"><?php echo $filaBloques['puntos']; ?></td>
						<td class="letraMediana font-weight-normal"><?php echo $filaBloques['tipo']; ?></td>
						<td class="letraMediana font-weight-normal"><?php echo $filaBloques['bloque']; ?></td>
						<td class="letraMediana font-weight-normal"><?php echo $filaBloques['nivel']; ?></td>
						<td class="letraMediana font-weight-normal"><?php echo $filaBloques['materia']; ?></td>
						<td class="letraMediana font-weight-normal"><?php echo $filaBloques['programa']; ?></td>
						<td class="letraMediana font-weight-normal">
							<!--Dropdown primary-->
							<div class="dropdown">

							  <!--Trigger-->
					
							  	
								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>

							  	<!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect eliminacionActividad" tipo="<?php echo $filaBloques['tipo']; ?>" identificador="<?php echo $filaBloques['identificador']; ?>" actividad="<?php echo $filaBloques['actividad']; ?>">
										Eliminar
									</a>


									<?php
						                if ( $filaBloques['tipo'] == 'Foro' ) {
						            ?>
						                <a href="foro_bloque.php?id_for=<?php echo $filaBloques['identificador']; ?>" class="dropdown-item waves-effect" target="_blank">

						            <?php
						                } else if ( $filaBloques['tipo'] == 'Entregable' ) {
						            ?>
						                <a href="entregable_bloque.php?id_ent=<?php echo $filaBloques['identificador']; ?>" class="dropdown-item waves-effect" target="_blank">

						            <?php 
						                } else if ( $filaBloques['tipo'] == 'Examen' ) {
						            ?>
						                <a href="examen_bloque.php?id_exa=<?php echo $filaBloques['identificador']; ?>" class="dropdown-item waves-effect" target="_blank">
						            <?php 
						                }
						            ?>
						              
						            	Ir a la actividad
						            </a>
									
								</div>
							</div>
							<!--/Dropdown primary-->
						</td>
						
					</tr>

				<?php
					} 

				?>
			</table>

		</div>
	
		
	</div>
	
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->

<?php  

	include('inc/footer.php');

?>


<script>
	$(document).ready(function () {


		$('#myTable').DataTable({
			
		
			dom: 'Bfrtlip',
			"pageLength": -1,
            
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
	$('.polaridadActividad').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		var polaridad  = $(this).attr('polaridad');
		var identificador = $(this).attr('identificador');
		var tipo = $(this).attr('tipo');
		var valor = $(this).val();

		// alert( 'polaridad: '+polaridad+' identificador: '+identificador+' tipo: '+tipo+' valor: '+valor );

		$.ajax({
                    
            url: 'server/editar_enteros_actividad.php',
            type: 'POST',
            data: { polaridad, identificador, tipo, valor },
            success: function(respuesta){
                console.log(respuesta);

                if (respuesta == 'Exito') {
                    toastr.success('Guardado exitosamente');

                }
            }
        });

	});
</script>



<script>
	//ELIMINACION DE BLOQUE
	$('.eliminacionActividad').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var identificador = $(this).attr("identificador");
		var actividad = $(this).attr("actividad");
		var tipo = $(this).attr("tipo");
		// console.log(BLOQUE);

		swal({
		  title: "¿Deseas eliminar "+actividad+"?",
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

		    if ( tipo == 'Foro' ) {

		    	var foro = identificador;

		    	$.ajax({
					url: 'server/eliminacion_foro.php',
					type: 'POST',
					data: {foro, tipo},
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


		    } else if ( tipo == 'Entregable' ) {


		    	var entregable = identificador;

		    	$.ajax({
					url: 'server/eliminacion_entregable.php',
					type: 'POST',
					data: {entregable, tipo},
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


		    } else if ( tipo == 'Examen' ) {


		    	var examen = identificador;

		    	$.ajax({
					url: 'server/eliminacion_examen.php',
					type: 'POST',
					data: { examen, tipo },
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

		    
		    
		  }
		});
	});


</script>