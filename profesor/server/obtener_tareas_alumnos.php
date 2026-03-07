<?php  
	//ARCHIVO VIA AJAX PARA OBTENER EL EXAMEN ORIGINAL
	//examen.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_ent_cop = $_POST['id_ent_cop'];

	$sqlSub = "
		SELECT *
		FROM entregable_copia
		WHERE id_ent_cop = '$id_ent_cop'
	";

	$resultadoSub = mysqli_query( $db, $sqlSub );

	$filaSub = mysqli_fetch_assoc( $resultadoSub );

	$id_sub_hor = $filaSub['id_sub_hor3'];

?>


<a href="#" id="btn_descargar_tareas" class="btn btn-block btn-rounded btn-info waves-effect">
	Descargar todo
</a>

<div class="row">
	
		<div class="col-md-12 text-center">
			

			<a class="toggle-vis btn-link text-info" data-column="3">Teléfono</a>
			<div class="table-responsive">

				<table class="table table-hover table-sm table-striped"  id="myTableTareas">
					<thead class="grey lighten-3 text-center">
						<th class="letraMediana font-weight-normal">#</th>
						<th class="letraMediana font-weight-normal">Matrícula</th>
						<th class="letraMediana font-weight-normal">Nombre</th>
						<th class="letraMediana font-weight-normal">Teléfono</th>
		            	<th class="letraMediana font-weight-normal">Fecha de Entrega</th>
		            	<th class="letraMediana font-weight-normal">Tarea</th>

						<th class="letraMediana font-weight-normal">Retroalimentación</th>

	            		<th class="letraMediana font-weight-normal" style="display: none;">Puntos</th>
	            		
	            		<th class="letraMediana font-weight-normal">Puntos</th>

						
					</thead>
					<tbody class="text-center">
						<?php

							$sqlTareas = "
								SELECT *
						        FROM sub_hor
						        INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
						        INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
						        INNER JOIN vista_alumnos ON vista_alumnos.id_alu_ram = alu_ram.id_alu_ram
						        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
						        INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
						        INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
						        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
						        WHERE id_ent_cop = '$id_ent_cop' AND est_alu_hor = 'Activo'
						        ORDER BY app_alu, apm_alu ASC
							";

								//echo $sqlTareas;

							$resultadoTareas = mysqli_query($db, $sqlTareas);
							$i = 1;
							$contadorTareas = 0;

							// $tareas = array();

							while ($filaTareas = mysqli_fetch_assoc($resultadoTareas)) {

								$id_alu_ram6 = $filaTareas['id_alu_ram'];
		                		$id_ent_cop1 = $filaTareas['id_ent_cop'];


		                		// VALIDACION ARCHIVOS
		                		$sqlArchivoTarea2 = "

									SELECT *
									FROM tarea 
									INNER JOIN alu_ram ON alu_ram.id_alu_ram = tarea.id_alu_ram6	
									WHERE id_alu_ram6 = '$id_alu_ram6' AND id_ent_cop1 = '$id_ent_cop1'
		                		";

		                		$resultadoTareaValidacion2 = mysqli_query( $db, $sqlArchivoTarea2 );

		                		$filaTareaValidacion2 = mysqli_fetch_assoc( $resultadoTareaValidacion2 );

		                		if ( $filaTareaValidacion2['fec_tar'] == NULL ) {
		                			
		                		} else {

		                			$resultadoArchivoTarea2 = mysqli_query($db, $sqlArchivoTarea2);

			                		$filaArchivoTarea2 = mysqli_fetch_assoc($resultadoArchivoTarea2);

		                			obtener_existencia_tarea_server( $filaArchivoTarea2['id_tar'], $id_alu_ram6, $id_ent_cop1 );
		                			// actualizar_registro_cal_act_server( $id_alu_ram6, $id_ent_cop1 );

		                		}


		                		// FIN VALIDACION ARCHIVOS

		                		$sqlArchivoTarea = "

									SELECT *
									FROM tarea 
									INNER JOIN alu_ram ON alu_ram.id_alu_ram = tarea.id_alu_ram6
									
									WHERE id_alu_ram6 = '$id_alu_ram6' AND id_ent_cop1 = '$id_ent_cop1'
		                		";

		                		$resultadoTareaValidacion = mysqli_query($db, $sqlArchivoTarea);

		                		$filaTareaValidacion = mysqli_fetch_assoc( $resultadoTareaValidacion );


								// echo obtenerValidacionAlumnoActividadServer( 'Entregable', $id_ent_cop, $id_alu_ram6 );
								if ( obtenerValidacionAlumnoActividadServer( 'Entregable', $id_ent_cop, $id_alu_ram6 ) > 0 ) {
						?>

									<?php  
										if ( $filaTareaValidacion2['fec_tar'] == NULL ) {
		                			?>
		                					<tr>
		                			<?php
				                		} else {
				                	?>
				                			<tr class=" animated pulse delay-1s light-green accent-1">
				                	<?php
				                		}
									?>
									
							

						<?php 
								}else{
						?>	
									<tr>
							
						<?php
								}
						?>
							
								<td class="letraMediana font-weight-normal">
									<?php echo $i; ?>
								</td>

								<td class="letraMediana font-weight-normal">
									<?php echo $filaTareas['bol_alu']; ?>
								</td>

							
				                <td class="letraMediana font-weight-normal">
				                	<?php 
				                		echo $filaTareas['app_alu']." ".$filaTareas['apm_alu']." ".$filaTareas['nom_alu'];
				                	?>
				                	<br>
				                	<?php  
				                		echo obtenerBadgeEstatusEjecutivoPosicion( $filaTareas['estatus_general'] );
				                	?>

				                </td>


				                <td class="letraMediana font-weight-normal">
									<?php echo $filaTareas['tel_alu']; ?>
								</td>




				                <td class="letraMediana font-weight-normal">
				                	<?php

				                		if ($filaTareaValidacion['fec_tar'] != NULL) {
				                		 	echo fechaHoraFormateadaCompactaServer($filaTareaValidacion['fec_tar']); 
				                		}else{
				                			echo "Pendiente";
				                		}
				                		
				                	?>
				                </td>



				                <td class="letraGrande font-weight-normal">

				                	<?php
				                		//echo $sqlArchivoTarea;

				                		$resultadoArchivoTarea = mysqli_query($db, $sqlArchivoTarea);

				                		$filaArchivoTarea = mysqli_fetch_assoc($resultadoArchivoTarea);


				                		

	                                    if ( $filaTareaValidacion['fec_tar'] == NULL) {
	                                ?>
	                                        
	                                        	<span class="badge badge'pill badge-danger font-weight-normal" title="No se ha entregado">Nulo</span>
	                                        
	                                <?php
	                                    }else{

	                                    	$tareas[$contadorTareas] = $filaArchivoTarea['doc_tar'];
	                                    	$contadorTareas++;

	                                    	// echo "booleano: ".obtener_existencia_tarea_server( $filaArchivoTarea['id_tar'] );

	                                ?>
	                                        <a href="../uploads/<?php echo $filaArchivoTarea['doc_tar']; ?>" download class="btn-link tareas" title="Descargar: <?php echo $filaArchivoTarea['doc_tar']; ?>">
	                                            Descargar tarea de <?php echo $filaTareas['app_alu']." ".$filaTareas['apm_alu']." ".$filaTareas['nom_alu']; ?>
	                                        </a>
	                                <?php
	                                    }

	                                ?>
				                	
				                </td>

				                
				                		<?php

					                    	$id_alu_ram = $filaTareas['id_alu_ram'];
					                		$id_ent_cop = $filaTareas['id_ent_cop'];

					                		$sqlCalificacionTarea = "

												SELECT *
												FROM cal_act 
												WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop2 = '$id_ent_cop'
					                		";


					                		$resultadoCalificacionTarea = mysqli_query($db, $sqlCalificacionTarea);

					                		$filaConsultaCalificacion = mysqli_fetch_assoc($resultadoCalificacionTarea);

					                        if ($filaConsultaCalificacion['ret_cal_act'] == NULL) {
					                    ?>
					                    	<td class=" font-weight-normal alumnosRetroalimentacionTd" data-order="<?php echo $filaConsultaCalificacion['ret_cal_act']; ?>">
				                				<div style="width: 200px;">

						                            <div class="form-group shadow-textarea">
						                                <textarea index="<?php echo $i-1; ?>" class="form-control z-depth-1  font-weight-normal alumnosRetroalimentacion letraPequena" id="exampleFormControlTextarea6" rows="6" placeholder="Retroalimenta a <?php echo $filaTareas['nom_alu']; ?>..." id_alu_ram="<?php echo $filaTareas['id_alu_ram']; ?>"></textarea>
						                            </div>
						                    
						                        </div>
				                    
				                			</td>




					                     
					                    <?php
					                        }else{

					                    ?>
					                    		<td class=" font-weight-normal alumnosRetroalimentacionTd" data-order="<?php echo $filaConsultaCalificacion['ret_cal_act']; ?>">
					                				<div style="width: 200px;">

							                            <div class="form-group shadow-textarea">
							                                <textarea index="<?php echo $i-1; ?>" class="form-control z-depth-1  font-weight-normal alumnosRetroalimentacion letraPequena" id="exampleFormControlTextarea6" rows="6" placeholder="Deja una observación para <?php echo $filaTareas['nom_alu']; ?>" id_alu_ram="<?php echo $filaTareas['id_alu_ram']; ?>" ><?php echo $filaConsultaCalificacion['ret_cal_act']; ?></textarea>
							                            </div>
							                    
							                        </div>
					                    
					                			</td>

					                            
					                    <?php
					                        }

					                    ?>
				                	

					                <?php 
				                        //echo $filaConsultaCalificacion['pun_cal_act'];
				                        if ($filaConsultaCalificacion['pun_cal_act'] == NULL) {
				                    ?>
				                    		<td class="letraGrande font-weight-normal alumnosCalificadosTd" style="display: none;">
				                    			0
				                            </td>
				                    <?php
				                        }else{

				                    ?>
				                    		<td class="letraGrande font-weight-normal alumnosCalificadosTd" style="display: none;">
												<?php echo $filaConsultaCalificacion['pun_cal_act']; ?>
				                    		</td>
				                            
				                    <?php
				                        }

				                    ?>

				                


				                    <?php 
				                        //echo $filaConsultaCalificacion['pun_cal_act'];
				                        if ($filaConsultaCalificacion['pun_cal_act'] == NULL) {
				                    ?>
				                    		<td class="letraGrande font-weight-normal ">

				                            	<input type="number" class="form-control letraGrande font-weight-normal alumnosCalificados" value="0" min="0" step=".1" max="<?php echo $filaTareas['pun_ent']; ?>" id_alu_ram="<?php echo $filaTareas['id_alu_ram']; ?>" index="<?php echo $i-1; ?>">

				                            </td>
				                    <?php
				                        }else{

				                    ?>
				                    		<td class="letraGrande font-weight-normal">
												
												<input type="number" class="form-control letraGrande font-weight-normal alumnosCalificados" value="<?php echo $filaConsultaCalificacion['pun_cal_act']; ?>" min="0" step=".1" max="<?php echo $filaTareas['pun_ent']; ?>" id_alu_ram="<?php echo $filaTareas['id_alu_ram']; ?>" index="<?php echo $i-1; ?>">
				                    		
				                    		</td>
				                            
				                    <?php
				                        }

				                    ?>
				                

								

							</tr>
						<?php

							$i++;
							}
							
						?>	
					</tbody>
				</table>
				
			</div>
				
		</div>
	</div>


<script>


        $('#myTableTareas').DataTable({
            
        
            dom: 'Bftp',
   //          "columnDefs": [
			// {
			//   	"targets": [ 5 ],
			//   	"visible": false
			// }],

            // "order": [[ 6, "asc" ]],
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
		
                            // columns: ':visible'
                            columns:[ 0,1,2,3,4,5,6,7 ],
                        },
                    },                  

                    // {
                        
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: ':visible'
                    //     },

                    // },

                    // {
                    //     extend: 'print',
                    //     exportOptions: {
                    //         columns: ':visible'
                    //     },
                    // },

                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns:[ 0,1,2,3,4,5,6,7 ],
                        },
                    },

            ],

            "pageLength": -1,

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
        $('#myTableTareas_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableTareas_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableTareas_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableTareas_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableTareas_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableTareas_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableTareas_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableTareas_wrapper select').addClass('mdb-select');
        $('#myTableTareas_wrapper .mdb-select').materialSelect();
        $('#myTableTareas_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableTareas_wrapper .dt-buttons').children().addClass('btn btn-primary btn-sm waves-effect');
        console.log(botones.eq(0));

        

        var table = $('#myTableTareas').DataTable();

        //INDICADORES DE INICIO
		$("#alumnosResponsables").text(table.rows( '.alumnosResponsables' ).count());
		$("#alumnosIrresponsables").text(table.rows( '.alumnosIrresponsables' ).count());

		//INDICADORES DINAMICOS
		table.on('draw', function(){

			$("#alumnosResponsables").text(table.rows( ['.alumnosResponsables'], { filter: 'applied' }).count());
			$("#alumnosIrresponsables").text(table.rows( ['.alumnosIrresponsables'], { filter: 'applied' }).count());
	    	
	    });



		// OCULTAR TELEFONO
		var column = table.column( $('a.toggle-vis').attr('data-column') );
		column.visible( ! column.visible() );



	    $('a.toggle-vis').on( 'click', function (e) {
	        e.preventDefault();
	 
	        // Get the column API object
	        var column = table.column( $(this).attr('data-column') );
	 
	        // Toggle the visibility
	        column.visible( ! column.visible() );
	    });

	
		// PUNTAJE
    $('.alumnosCalificados').on( 'change', function () {
        //event.preventDefault();
        var id_sub_hor = <?php echo $id_sub_hor; ?>;

        console.log("click");
        var puntos = $(this).val();
        var id_alu_ram = $(this).attr("id_alu_ram");
        console.log(puntos+" - "+id_alu_ram);

        var index = $(this).attr("index");

        $('.alumnosCalificadosTd').eq( index ).html( puntos );




        $.ajax({
                    
            url: 'server/editar_calificacion_entregable.php?id_ent_cop=<?php echo $id_ent_cop; ?>',
            type: 'POST',
            data: {puntos, id_alu_ram},
            success: function(respuesta){
                console.log(respuesta);

                if (respuesta == 'Exito') {
                    // obtenerTareas();
                    toastr.success('Guardado exitosamente');

                    $( '#modal_obtener_actividad' ).on('hidden.bs.modal', function () {
                        removeParam("identificador_copia");
                        removeParam("tipo_actividad");

                        obtenerActividades();
                        
                        // alert( id_sub_hor );
                        obtenerNotificacionesActividadesMateria( id_sub_hor );

                        obtenerNotificacionesActividadesNavbar();



                        
                    });
                }
            }
        });
    });


    // RETROALIMENTACION
    $('.alumnosRetroalimentacion').on( 'change', function () {
        //event.preventDefault();

        var retroalimentacion = $(this).val();
        var id_alu_ram = $(this).attr("id_alu_ram");

        var index = $(this).attr("index");

        $('.alumnosRetroalimentacionTd').eq( index ).removeAttr('data-order').attr('data-order', retroalimentacion );
        console.log(retroalimentacion+" - "+id_alu_ram);

        $.ajax({
                    
            url: 'server/editar_calificacion_entregable.php?id_ent_cop=<?php echo $id_ent_cop; ?>',
            type: 'POST',
            data: {retroalimentacion, id_alu_ram},
            success: function(respuesta){
                console.log(respuesta);

                // if (respuesta == 'Exito') {
                //     toastr.success('Guardado exitosamente');
                // }
            }
        });

       
    });
</script>

<script>
	$('#btn_descargar_tareas').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		generarAlerta('Descargando todo...');
		for( var i = 0; i < $('.tareas').length; i++ ){

			window.open($('.tareas').eq(i).attr('href'));

		}

	

	});
</script>