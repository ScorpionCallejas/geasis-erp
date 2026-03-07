<?php  
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];	
?>


<?php  

	$sql = "
	        
	    SELECT id_for_cop AS identificador_copia, nom_for AS actividad, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo_actividad, id_alu_ram AS id_alu_ram, id_for_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru, ret_cal_act AS retroalimentacion, pun_cal_act AS puntos_obtenidos, nom_alu AS nom_alu, nom_blo AS nom_blo, CONCAT( nom_pro,' ',app_pro ) AS nom_pro, id_cal_act AS id_cal_act
		FROM cal_act
		INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
		INNER JOIN foro ON foro.id_for = foro_copia.id_for1
		INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		WHERE id_alu_ram = '$id_alu_ram'
		UNION
		SELECT id_ent_cop AS identificador_copia, nom_ent AS actividad, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo_actividad, id_alu_ram AS id_alu_ram, id_ent_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru, ret_cal_act AS retroalimentacion, pun_cal_act AS puntos_obtenidos, nom_alu AS nom_alu, nom_blo AS nom_blo, CONCAT( nom_pro,' ',app_pro ) AS nom_pro, id_cal_act AS id_cal_act
		FROM cal_act
		INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
		INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
		INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		WHERE id_alu_ram = '$id_alu_ram'
		UNION
		SELECT id_exa_cop AS identificador_copia, nom_exa AS actividad, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo_actividad, id_alu_ram AS id_alu_ram, id_exa_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru, ret_cal_act AS retroalimentacion, pun_cal_act AS puntos_obtenidos, nom_alu AS nom_alu, nom_blo AS nom_blo, CONCAT( nom_pro,' ',app_pro ) AS nom_pro, id_cal_act AS id_cal_act
		FROM cal_act
		INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
		INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
		INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		WHERE id_alu_ram = '$id_alu_ram'
		ORDER BY inicio

	";


	// echo $sql;

	$resultado = mysqli_query( $db, $sql );

	// $total = mysqli_num_rows( $resultado );

	// echo $total;
?>

<div class="table-responsive">
	<table class="table table-sm text-center table-hover " id="myTableActividadesAlumno">
		<thead>
			<tr>
				<th>#</th>
				<th>Actividad</th>
				<th>Profesor</th>
				
				<th>Inicio</th>
				<th>Fin</th>

				<th>Retroalimentación</th>
				<th>
					<div style="width: 100px;">
						Puntos	
					</div>
				</th>

				<th>Acción</th>

			</tr>
		</thead>

		<tbody>
			<?php
				$i = 1;
				while( $fila = mysqli_fetch_assoc( $resultado ) ){
			?>
					<tr style="height: 100px;">
						<td><?php echo $i; $i++; ?></td>
						
						<td title="<?php echo $fila['actividad']; ?>">

							<?php echo comprimirTexto( $fila['actividad'] ); ?>
							<br>
							<span class="letraPequena">
								<?php echo $fila['nom_mat']; ?>
							</span>
							<br>
							<span class="letraPequena grey-text">
								<?php echo $fila['nom_blo'].' --- '.$fila['tipo_actividad']; ?>
							</span>

							<br>
							<span><?php echo obtenerEstatusActividadServer( $fila['fecha'], $fila['inicio'], $fila['fin'], $fila['puntos_obtenidos'] ); ?></span>
						</td>

						<td>
							<?php echo $fila['nom_pro']; ?>
						</td>
						
	                    <td class="letraPequena font-weight-normal" data-order="<?php echo $fila['inicio']; ?>">

	                        <input type="date" class="form-control letraPequena font-weight-normal actividadInicio" value="<?php echo $fila['inicio']; ?>" id_alu_ram="<?php echo $fila['id_alu_ram']; ?>" id_cal_act="<?php echo $fila['id_cal_act']; ?>">

	                    </td>


	                    <td class="letraPequena font-weight-normal" data-order="<?php echo $fila['fin']; ?>">

	                        <input type="date" class="form-control letraPequena font-weight-normal actividadFin" value="<?php echo $fila['fin']; ?>" id_alu_ram="<?php echo $fila['id_alu_ram']; ?>" id_cal_act="<?php echo $fila['id_cal_act']; ?>">

	                    </td>


	                    <td class="letraPequena font-weight-normal">
							<input type="text" class="form-control letraPequena font-weight-normal actividadRetroalimentacion" value="<?php echo $fila['retroalimentacion']; ?>" id_alu_ram="<?php echo $fila['id_alu_ram']; ?>" id_cal_act="<?php echo $fila['id_cal_act']; ?>">
	                    </td>


	                    <td class="letraPequena font-weight-normal">

							<input type="number" class="form-control letraPequena font-weight-normal actividadPuntos" value="<?php echo $fila['puntos_obtenidos']; ?>" id_alu_ram="<?php echo $fila['id_alu_ram']; ?>" id_cal_act="<?php echo $fila['id_cal_act']; ?>">
	                        
	                    </td>

						
						<td class=" letraPequena font-weight-normal">

							<!-- CONDICIONANTE SI ES EXAMEN -->
							<?php
								if( $fila['tipo_actividad'] == 'Examen' ){
							?>

								<!--  -->
								<?php
									$id_exa_cop = $fila['identificador_copia'];
									$sqlValidacionExamen = "
										SELECT *
										FROM cal_act
										WHERE id_alu_ram4 = '$id_alu_ram' AND id_exa_cop2 = '$id_exa_cop'
									";

									$resultadoValidacionExamen = mysqli_query( $db, $sqlValidacionExamen );

									if ( $resultadoValidacionExamen ) {
										
										$filaValidacionExamen = mysqli_fetch_assoc( $resultadoValidacionExamen );

										// VALIDACION ADICIONAL PORQUE SE HA SUSCITADO EL CASO DONDE HAY PREGUNTAS CONTESTADAS DE ALUMNOS PERO NO SE GUARDA LA FECHA ASI QUE SE CONTEMPLARA DOBLE CONDICION

										$sqlValidacionAdicional = "
											SELECT *
											FROM respuesta_alumno
											WHERE id_alu_ram8 = '$id_alu_ram' AND id_exa_cop1 = '$id_exa_cop'
										";
										

										$resultadoValidacionAdicional = mysqli_query( $db, $sqlValidacionAdicional );

										if ( !$resultadoValidacionAdicional ) {
											
											echo $sqlValidacionAdicional;

										}

										$totalValidacionAdicional = mysqli_num_rows( $resultadoValidacionAdicional );


										if ( ( $filaValidacionExamen['fec_cal_act'] == NULL ) && ( $totalValidacionAdicional == 0 ) ) {
										
											echo "N/A";
										
										} else {
										
								?>
											<a class="btn btn-sm btn-info btn-rounded reiniciarExamen" id_alu_ram="<?php echo $id_alu_ram; ?>" id_exa_cop="<?php echo $id_exa_cop; ?>">
												Reiniciar
											</a>
								<?php
										
										}

									} else {
										echo $sqlValidacionExamen;
									}
								?>
								<!--  -->

							<?php
								} else {
									echo 'N/A';
								}
							?>
							<!-- FIN CONDICIONANTE SI ES EXAMEN -->
							
							
						</td>


					</tr>

			<?php
				}
			?>
		</tbody>
		
	</table>	
</div>


<script>
	$('#myTableActividadesAlumno').DataTable({
			
		dom: 'Bft',
        pageLength: -1,
        buttons: [

                {
                    extend: 'excelHtml5',
                   	messageTop: 'Reporte de actividades',
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
	$('#myTableActividadesAlumno_wrapper').find('label').each(function () {
		$(this).parent().append($(this).children());
	});
	// $('#myTableActividadesAlumno_wrapper .dataTables_filter').find('input').each(function () {
	// 	$('#myTableActividadesAlumno_wrapper input myTableActividadesAlumno_filter').attr("placeholder", "Buscar...");
	// 	$('#myTableActividadesAlumno_wrapper input').removeClass('form-control-sm');
	// });

	// $('#myTableActividadesAlumno_wrapper input myTableActividadesAlumno_filter').attr("placeholder", "Buscar...");
	$('#myTableActividadesAlumno_wrapper .dataTables_length').addClass('d-flex flex-row');
	$('#myTableActividadesAlumno_wrapper .dataTables_filter').addClass('md-form');
	$('#myTableActividadesAlumno_wrapper select').removeClass(
	'custom-select custom-select-sm form-control form-control-sm');
	$('#myTableActividadesAlumno_wrapper select').addClass('mdb-select');
	$('#myTableActividadesAlumno_wrapper .mdb-select').materialSelect();
	$('#myTableActividadesAlumno_wrapper .dataTables_filter').find('label').remove();
	var botones = $('#myTableActividadesAlumno_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
	//console.log(botones);
</script>


<script>
	// EDITAR PUNTOS
    $('.actividadPuntos').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var tipo = 'Puntos';
        var puntos = $(this).val();
        var id_cal_act = $(this).attr('id_cal_act');
        console.log( id_cal_act );
        $.ajax({
         
            url: 'server/editar_fechas_actividad_alumno.php',
            type: 'POST',
            data: { tipo, puntos, id_cal_act },
            success: function( respuesta ){
                
                if ( respuesta == 'Exito' ) {
                
                    toastr.success('Guardado exitosamente');
                
                } else {

                    console.log( respuesta );
                
                }
            }
        
        });
        
    });
	
	// EDITAR RETROALIMENTACION
    $('.actividadRetroalimentacion').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var tipo = 'Retroalimentacion';
        var retroalimentacion = $(this).val();
        var id_cal_act = $(this).attr('id_cal_act');
        console.log( id_cal_act );
        $.ajax({
         
            url: 'server/editar_fechas_actividad_alumno.php',
            type: 'POST',
            data: { tipo, retroalimentacion, id_cal_act },
            success: function( respuesta ){
                
                if ( respuesta == 'Exito' ) {
                
                    toastr.success('Guardado exitosamente');
                
                } else {

                    console.log( respuesta );
                
                }
            }
        
        });
        
    });
	
	// EDITAR FECHAS ACTIVIDAD INICIO
    $('.actividadInicio').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var tipo = 'Inicio';
        var fecha = $(this).val();
        var id_cal_act = $(this).attr('id_cal_act');
        console.log( id_cal_act );
        $.ajax({
         
            url: 'server/editar_fechas_actividad_alumno.php',
            type: 'POST',
            data: { tipo, fecha, id_cal_act },
            success: function( respuesta ){
                
                if ( respuesta == 'Exito' ) {
                
                    toastr.success('Guardado exitosamente');
                
                } else {

                    console.log( respuesta );
                
                }
            }
        
        });
        
    });


    // EDITAR FECHAS ACTIVIDAD FIN
    $('.actividadFin').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var tipo = 'Fin';
        var fecha = $(this).val();
        var id_cal_act = $(this).attr('id_cal_act');

        $.ajax({
         
            url: 'server/editar_fechas_actividad_alumno.php',
            type: 'POST',
            data: { tipo, fecha, id_cal_act },
            success: function( respuesta ){
                
                if ( respuesta == 'Exito' ) {
                
                    toastr.success('Guardado exitosamente');
                
                } else {

                    console.log( respuesta );
                
                }
            }
        
        });
        


        
    });

	$( ".reiniciarExamen" ).on( 'click', function() {
		

		var id_alu_ram = $( this ).attr( 'id_alu_ram' );
		var id_exa_cop = $( this ).attr( 'id_exa_cop' );

		swal({
		  title: "¿ Estás seguro que desear reiniciar el cuestionario?",
		  text: "¡Una vez reiniciado el alumno podrá realizar de nuevo el cuestionario!",
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
				url: 'server/editar_examen_alumno.php',
				type: 'POST',
				data: { id_alu_ram, id_exa_cop },
				success: function(respuesta){
					
					if (respuesta == "Exito") {
						console.log("Exito en consulta");
						swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							obtener_actividades_alumno();
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