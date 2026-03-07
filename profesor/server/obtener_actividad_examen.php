<?php  

	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	

	$id_exa_cop = $_POST['id_exa_cop'];

	//VALIDACION DE ALUMNO DE LA CARRERA
	//PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

	//***PENDIENTE DE VERIFICACION
	$sqlValidacion = "
		SELECT *
        FROM examen_copia

        INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4     
       	INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
        INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1

        WHERE id_pro1 = '$id' AND id_exa_cop = '$id_exa_cop'
	";

	$resultadoValidacion = mysqli_query($db, $sqlValidacion);

	$totalValidacion = mysqli_num_rows($resultadoValidacion);

	
	if ($totalValidacion == 0) {
		header('location: not_found_404_page.php');
	}
	$filaValidacion = mysqli_fetch_assoc($resultadoValidacion);

	$nom_blo = $filaValidacion['nom_blo'];
	$des_blo = $filaValidacion['des_blo'];
	$con_blo = $filaValidacion['con_blo'];	
	$id_mat6 = $filaValidacion['id_mat6'];
	$nom_mat = $filaValidacion['nom_mat'];
	$nom_ram = $filaValidacion['nom_ram'];
	$nom_gru = $filaValidacion['nom_gru'];
	$img_blo = $filaValidacion['img_blo'];
	$id_blo = $filaValidacion['id_blo'];

	$nom_exa= $filaValidacion['nom_exa'];
	$id_mat = $filaValidacion['id_mat'];
	$id_ram = $filaValidacion['id_ram'];
	$id_exa = $filaValidacion['id_exa'];

	$des_exa = $filaValidacion['des_exa'];
	$pun_exa = $filaValidacion['pun_exa'];
	$ini_exa_cop = $filaValidacion['ini_exa_cop'];
	$fin_exa_cop = $filaValidacion['fin_exa_cop'];

	$id_sub_hor = $filaValidacion['id_sub_hor'];


	$id_exa_cop = $filaValidacion['id_exa_cop'];

	$dur_exa = $filaValidacion['dur_exa'];

	//$fechaHoy = date('Y-m-d');

	// VALIDACION DE FECHAS 
	// if ($fechaHoy < $ini_exa_cop || $fechaHoy > $fin_exa_cop) {
	// 	header("location: not_found_404_page.php");
	// }
	
?>



<!-- ACTIVIDAD -->

<div class="row text-center">

	<div class="col-md-4">

		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="fas fa-check"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Puntos: <?php echo $pun_exa; ?>
				</span>

			</div>
		</div>
		
		
		
	</div>

	<div class="col-md-4">
		
		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="far fa-calendar-minus"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Inicio: <?php echo fechaFormateadaCompacta($ini_exa_cop); ?>
				</span>

			</div>
		</div>

		
		
		
	</div>

	<div class="col-md-4">

		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="far fa-calendar-plus"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Fin: <?php echo fechaFormateadaCompacta($fin_exa_cop); ?>
				</span>

			</div>
		</div>
		

		
	</div>

</div>
<!-- FIN DATOS ACTIVIDAD -->

<br>

<div class="row">
    

    <!-- CONTENIDO DE ACTIVIDAD -->
    <div class="col-md-12">
        
        <div class="card grey lighten-5" style="border-radius: 20px;">
        	<div class="card-body" id="contenedor_instrucciones">
        		<?php  
					echo $des_exa;
	            ?>
        	</div>
        </div>

        


    </div>

    
</div>
    

<!-- FIN DETALLES DEL FORO -->

<br>

	

	<div class="row text-center">
		

		<div class="col-md-12">
			<div class="card" style="border-radius: 20px;">
				<div class="card-body">

					<div class="row">

						<div class="col-md-12 text-center">
							<label class="letraGrande font-weight-normal">
									Detalles del cuestionario
							</label>
						</div>
						
					</div>

					<div class="row">
						<?php 
							$sqlTotalPreguntas = "
								SELECT * 
								FROM pregunta
								INNER JOIN examen ON examen.id_exa = pregunta.id_exa2
								WHERE id_exa = '$id_exa'

								";

							$resultadoTotalPreguntas = mysqli_query($db, $sqlTotalPreguntas);

							$totalPreguntas = mysqli_num_rows($resultadoTotalPreguntas);
						?>

						<div class="col-md-4">
							<label class="letraMediana font-weight-normal">Tiempo: <?php echo $dur_exa; ?> minutos</label>
						</div>

						<div class="col-md-4">
							<label class="letraMediana font-weight-normal">Valor: <?php echo $pun_exa; ?> puntos</label>
						</div>

						<div class="col-md-4">
							<label class="letraMediana font-weight-normal">Total: <?php echo $totalPreguntas; ?> preguntas</label>
						</div>
					</div>
				</div>
			</div>
			
		</div>

		

		
		
	</div>
	
	


<br>


<!-- EXAMEN Y TIMER -->

	<div class="row">

		
		<div class="col-md-5">
			

			<div class="jumbotron text-center" style="border-radius: 20px;">
				
				<div class="card grey lighten-1 mb-3 waves-effect hoverable white-text selectoresElemento" style="max-width: 20rem;" examen="original">
					<div class="card-header  grey darken-1" title="Carga el examen original" >
						Cuestionario original
					</div>
				</div>
				
				<table class="table table-hover table-sm table-striped table-responsive" id="alumnosExamen" width="100%">
	                <thead class="grey lighten-3 text-center">
	                    

	                    <th class='letraPequena'>
	                    	<div style="width: 20px;">
	                    		Nombre
	                    	</div>
	                    </th>
	                    
	                    <th class='letraPequena'>
	                    	<div style="width: 20px;">
	                    		Aciertos
	                    	</div>
	                    </th>
	                    
	                
	                    
	                    <th class="letraPequena">
	                    	<div style="width: 20px;">
	                    		Acción
	                    	</div>
	                    </th>
	                    
	                </thead>
	                <tbody class="text-center">
	                    <?php

	                        $sqlExamen = "
	                            SELECT *
	                            FROM sub_hor
	                            INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
	                            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
	                            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
	                            INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
						        INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
						        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1


	                            WHERE id_exa_cop = '$id_exa_cop' AND est_alu_hor = 'Activo'
	                            ORDER BY app_alu ASC
	                        ";

	                            //echo $sqlExamen;

	                        $resultadoExamen = mysqli_query($db, $sqlExamen);
	                        $i = 1;
	                        while ($filaExamen = mysqli_fetch_assoc($resultadoExamen)) {
	                        	$id_alu_ram = $filaExamen['id_alu_ram'];
	                        	$alumno = $filaExamen['app_alu']." ".$filaExamen['nom_alu'];
	                    ?>
	                        <tr id="examen<?php echo $id_alu_ram; ?>">
	                    


	                            <td class="letraPequena">
	                            	<div class="card   grey lighten-1 mb-3 waves-effect hoverable white-text selectoresElemento"  style="width: 80px;" title="Conoce qué respuestas tuvo <?php echo $filaExamen['nom_alu']." ".$filaExamen['app_alu']; ?>" id_alu_ram="<?php echo $filaExamen['id_alu_ram']; ?>" examen="alumno">
										<div class="card-header  grey darken-1 letraPequena">
											<?php echo substr( $alumno, 0, 30 ); ?>
										</div>
									 
									</div>
	                            </td>

	                            <td class="letraPequena" title="Respuestas correctas de respuestas totales">

	                            	<h5>

	                            		<?php

			                            		$id_alu_ram8 = $filaExamen['id_alu_ram'];
		 	                            		$sqlAciertos = "
													SELECT COUNT(id_res1) AS correctas
													FROM pregunta
													INNER JOIN respuesta ON respuesta.id_pre1 = pregunta.id_pre
													INNER JOIN respuesta_alumno ON respuesta_alumno.id_res1 = respuesta.id_res
													INNER JOIN alu_ram ON alu_ram.id_alu_ram = respuesta_alumno.id_alu_ram8
													WHERE id_alu_ram = '$id_alu_ram8' AND val_res = 'Verdadero' AND id_exa2 = '$id_exa'
			                            		";

			                            		$resultadoAciertos = mysqli_query($db, $sqlAciertos);
			                            		$filaAciertos = mysqli_fetch_assoc($resultadoAciertos);

			                            ?>
	                            		<span class=" letraPequena font-weight-normal" title="El alumno <?php echo $filaExamen['nom_alu']." ".$filaExamen['app_alu'].", obtuvo: ".$filaAciertos['correctas']." correctas de ".$totalPreguntas." totales"; ?>">
	                            			<?php	

			                            		echo $filaAciertos['correctas']."/".$totalPreguntas;
			                            	?>
	                            		</span>
	                            	</h5>
	                            	
	                            </td>





	                            <td class=" letraPequena font-weight-normal">
	                            	
	                            	<?php  
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
	                            			
	                            				echo "Pendiente";
	                            			
	                            			} else {
	                            			
	                            	?>
												<a class="btn btn-sm btn-info btn-rounded reiniciarExamen" id_alu_ram=" <?php echo $id_alu_ram; ?>" alumno="<?php echo $alumno; ?>" title="<?php echo $alumno; ?> ya hizo el cuestionario, presiona este botón para reiniciarlo">
	                            					Reiniciar
	                            				</a>
	                            	<?php
	                            			
	                            			}

	                            		} else {
	                            			echo $sqlValidacionExamen;
	                            		}
	                            	?>
	                            	
	                            </td>
	                        </tr>
	                    <?php
	                        }  
	                    ?>
	                </tbody>
	            </table>

			</div>
		</div>


		<div class="col-md-7" >
			<div id="contenedor_examen">
				
				<?php  
					$sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa'";
					$resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
					$i = 1;
					$j = 1;
					while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ){
				?>

					<div class="card" style="border-radius: 20px;">
						<div class="card-header z-depth-1 bg-white" style="border-radius: 20px;">
							<div class="row p-2  clasePadre">
																
								<div class="claseHijoNumeracion font-weight-bold">
									<div class="claseTextoHijoNumeracion">
										<?php echo $i; ?>
									</div>
										
								</div>

								<div class="col-md-6">
									
									<?php echo $filaPreguntas['pre_pre']; ?>
								</div>

								<div class="col-md-6 text-right">
									<p class="letraMediana grey-text">
							      		<?php echo "Valor: +".$filaPreguntas['pun_pre']; ?>
							      	</p>
									
								</div>
							</div>

							
						</div>

						<div class="body" style="border-radius: 20px;">
							<!-- SECCION DE RESPUESTAS -->
						  	<?php


						  		$id_pre = $filaPreguntas['id_pre'];
						  		$sqlRespuestas = "SELECT * FROM respuesta WHERE id_pre1 = '$id_pre'";


						  		//echo $sqlRespuestas;
						  		$resultadoRespuesta = mysqli_query($db, $sqlRespuestas);
						  		$contadorRespuesta = 1;
						  		while($filaRespuestas = mysqli_fetch_assoc($resultadoRespuesta)){
						  			
						  	?>
								
									<div class="row p-2">
							
										<div class="col-md-1"></div>

									    <!-- Grid column -->
									    <div class="col-md-10">
									    	<div class="card" style="border-radius: 20px;">
												<div class="card-body">

													<div class="row clasePadre">

														<div class="claseHijoNumeracion font-weight-bold">
															<div class="claseTextoHijoNumeracion">
																<?php echo $contadorRespuesta; ?>
															</div>
																
														</div>


														<?php 
															echo $filaRespuestas['res_res']."<br>(".$filaRespuestas['val_res'].")";
															$contadorRespuesta++;
														?> 
													</div>
													
												</div>
												
											</div>
									    </div>
									   	<div class="col-md-1"></div>
									</div>

									



						  	<?php
						  			$j++;

						  		}

						  		$i++;

						  	?>

						  <!-- FIN SECCION DE RESPUESTAS -->
						</div>

						
					</div>
					<br>
					


				<?php

					}

				?>
			</div>
		</div>

		
	</div>


<!-- FIN EXAMEN Y TIMER -->

	<!-- CALIFICACION MODAL -->
    <!-- Side Modal Bottom Right Success-->
    <div class="modal fade" id="sideModalBRSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" >
	    <div class="modal-dialog  modal-notify modal-info modal-lg " role="document">
	        <!--Content-->
	        <div class="modal-content " id="tamanoModal" >

	            

	            


	            <!--Header-->
	            <div class="modal-header" title="Puedes mover este elemento arrastrándolo">
	                <p class="heading lead">Calificaciones para: <?php echo $nom_exa; ?></p>

	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true" class="white-text">&times;</span>
	                </button>
	            </div>


	      
	            
	            <form id="formularioCalificacion" >
	                <!--Body-->
	                <div class="modal-body text-center" id="contenedorModal">
	                        
	                        
	                </div>
	                <!-- Fin Body -->

	                <!--Footer-->
	                <div class="modal-footer justify-content-center">

	                    
	                </div>
	            </form> 


	            
	        </div>
	        <!--/.Content-->
	    </div>
	</div>
	<!-- Side Modal Bottom Right Success-->

	<!-- FIN CALIFICACION MODAL -->
	
	


<script>
	$(".selectoresElemento").on('click', function(event) {
		event.preventDefault();
		// /* Act on the event */
		$('.selectoresElemento').children().removeClass('grey darken-1');
		$('.selectoresElemento').children().removeClass('light-green accent-4');
		$('.selectoresElemento').children().addClass('grey darken-1');
		$(this).children().removeClass('grey darken-1');
		$(this).children().addClass('light-green accent-4');


		var examen = $(this).attr("examen");


		if ( examen == 'original' ) {

			console.log("original");
			var id_exa = <?php echo $id_exa; ?>;

			$.ajax({
				url: 'server/obtener_examen_original.php',
				type: 'POST',
				data: {id_exa},
				success: function(respuesta){
					//console.log(respuesta);

					$("#contenedor_examen").html(respuesta);
				}
			});

		}else{
			var id_alu_ram = $(this).attr("id_alu_ram");
			var id_exa = <?php echo $id_exa; ?>;

			$.ajax({
				url: 'server/obtener_examen_alumno.php',
				type: 'POST',
				data: {id_exa, id_alu_ram},
				success: function(respuesta){
					//console.log(respuesta);

					$("#contenedor_examen").html(respuesta);
				}
			});
		}

		
		
	});
</script>


<script>
    $(document).ready(function () {


        $('#alumnosExamen').DataTable({
            
        
            dom: 'ftp',
            "pageLength": 50,
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
        $('#alumnosExamen_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#alumnosExamen_wrapper .dataTables_filter').find('input').each(function () {
            $('#alumnosExamen_wrapper input').attr("placeholder", "Buscar...");
            $('#alumnosExamen_wrapper input').removeClass('form-control-sm');
        });
        $('#alumnosExamen_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#alumnosExamen_wrapper .dataTables_filter').addClass('md-form');
        $('#alumnosExamen_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#alumnosExamen_wrapper select').addClass('mdb-select');
        $('#alumnosExamen_wrapper .mdb-select').materialSelect();
        $('#alumnosExamen_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#alumnosExamen_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);

        
    });


</script>

<script>
	$( ".reiniciarExamen" ).on( 'click', function() {
		

		var id_alu_ram = $( this ).attr( 'id_alu_ram' );
		var alumno = $( this ).attr( 'alumno' );
		var id_exa_cop = <?php echo $id_exa_cop; ?>;

		swal({
		  title: "¿ Estás seguro que desear reiniciar el cuestionario para "+alumno+"?",
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
							obtener_actividad_examen();
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
	setTimeout(function(){
		$('#contenedor_instrucciones img').addClass('img-fluid');
	}, 500 );
	// $('#contenedor_instrucciones')
</script>