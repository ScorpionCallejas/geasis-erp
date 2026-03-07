<?php  
		

  include('inc/header.php');
?>


<!-- CONTENIDO -->
<?php
	$sqlHorario = "
		SELECT *
    	FROM sub_hor
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_pro = '$id' AND est_sub_hor = 'Activo'
		ORDER BY id_sub_hor DESC
	";


	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	

	$resultadoHorarioTitulo = mysqli_query($db, $sqlHorario);
	$filaHorario = mysqli_fetch_assoc($resultadoHorarioTitulo);


	//VALIDACCION ACCESO
	// $resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	// $totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

	
	// if ($totalValidacion == 0) {
	// 	header('location: not_found_404_page.php');
	// }

	
?>
<!-- 

<style>
	.botonHijo {
		position: absolute;
		right: 5%;
		top: 5%; 
	}

	.botonPadre {
		position: relative;
	}
</style>
 -->
 <!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Horario</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Horario</a>
		</div>		
	</div>
</div>
<!-- FIN TITULO -->
	

<style>
 .scrollspy-example {
height: 360px;
}
</style>


<!-- INDICADORES Y FILTROS -->
<div class="row">

	<!-- PROGRAMAS -->
	<div class="col-md-6">
		<div class="card" style="height: 12.5em;">
			<div class="card-body bg-light scrollspy-example z-depth-1 text-center p-0" data-spy="scroll" data-target="#mdb-scrollspy-ex" data-offset="0">

				<div class="row"style="text-align: initial;">
					<div class="col-md-6">
						<h4>
				          <span class="badge badge-info" id="contenedor_registros">
				          
				          </span>
				        </h4>
					</div>

					<div class="col-md-6" id="contenedor_buscador">
						
					</div>
				</div>
						
				<?php  
					$sqlProgramas = "
						SELECT *
						FROM rama
						WHERE id_pla1 = '$plantel'
						ORDER BY id_ram ASC
					";

					$resultadoProgramas = mysqli_query( $db, $sqlProgramas );
					$resultadoTotalProgramas = mysqli_query( $db, $sqlProgramas );

					$contadorProgramas = 1;

					$totalProgramas = mysqli_num_rows( $resultadoTotalProgramas );

	            	for ( $i = 0 ; $i < $totalProgramas / 2 ; $i++ ) {
	            ?>
		            	<div class="row"style="text-align: initial;">
		            		<?php  
		            			while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
		            		?>
									<div class="col-md-6">
										<div class="form-check">
						                  <input type="checkbox" class="form-check-input checador5" id="programa<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['nom_ram']; ?>" columna="1">
						                  <label class="form-check-label letraPequena font-weight-normal font-weight-bold" for="programa<?php echo $contadorProgramas; ?>">
						                
						                    <?php echo $filaProgramas['nom_ram']; ?>

						                  </label>
						                </div>
									</div>
		            		<?php
		            				$contadorProgramas++;
		            			}
		            		?>
		            		
		            	</div>

				<?php
	            	}
	            // FIN for
	            ?>



			</div>
		</div>
	</div>
	<!-- FIN PROGRAMAS -->
	
	<!-- INPUTS -->
	<div class="col-md-6">
		<div class="card" style="height: 200px">
			<div class="card-body bg-light">
				<!-- FECHAS -->
				<div class="row">
					<!-- INICIO -->
					<div class="col-md-6">
						<div class="md-form mb-2">
			                <input type="date" id="min-date" class="date-range-filter form-control validate letraPequena font-weight-normal font-weight-bold" title="Inicio del Rango">
			            </div>
					</div>
					<!-- FIN INICIO -->

					<!-- FIN -->
					<div class="col-md-6">
						<div class="md-form mb-2">
			              <input type="date" id="max-date" class="date-range-filter form-control validate letraPequena font-weight-normal font-weight-bold" title="Fin del Rango">
			            </div>
					</div>
					<!-- FIN FIN -->
				</div>
				<!-- FIN FECHAS -->


				<!-- RADIO Y BUSCADOR Y SELECTOR PROFESORES -->
				<div class="row">
					<!-- RADIOS -->
					<div class="col-md-6">
						<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
		                  <input type="radio" class="form-check-input columna" id="inicioCobro" columna="4" name="inlineMaterialRadiosExample" checked>
		                  <label class="form-check-label letraPequena font-weight-normal font-weight-bold" for="inicioCobro" style="line-height: 100%;">Fecha<br>de Inicio</label>
		                </div>

		                <div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
		                  <input type="radio" class="form-check-input columna" id="finCobro" columna="5" name="inlineMaterialRadiosExample">
		                  <label class="form-check-label letraPequena font-weight-normal font-weight-bold" for="finCobro" style="line-height: 100%;">Fecha <br>de Fin</label>
		                </div>
					</div>
					<!-- FIN RADIOS -->

					<!-- BUSCADOR Y SELECTOR -->
					<div class="col-md-6">
						
						


						
						
					</div>
					<!-- FIN BUSCADOR Y SELECTOR -->


				</div>
				<!-- FIN RADIO Y BUSCADOR Y SELECTOR PROFESORES -->
				
			</div>
		</div>
	</div>
	<!-- FIN INPUTS -->
</div>
<!-- FIN INDICADORES Y FILTROS -->



<!-- HORARIOS -->
<div class="row">

	<div class="col-md-12 animated fadeInUp">
		
		<table class="table table-sm text-center table-hover table-responsive table-striped" cellspacing="0" width="99%" id="myTableHorarios">
			<thead class="grey lighten-2">
				<tr>
					<th class="letraPequena font-weight-normal">#</th>
					<th class="letraPequena font-weight-normal">Programa</th>
					<th class="letraPequena font-weight-normal">Modalidad</th>
					<th class="letraPequena font-weight-normal">Ciclo Escolar</th>
					<th class="letraPequena font-weight-normal">Inicio de Ciclo</th>
					<th class="letraPequena font-weight-normal">Fin de Ciclo</th>
					<th class="letraPequena font-weight-normal">Grupo</th>
					<th class="letraPequena font-weight-normal">Clave</th>

					<th class="letraPequena font-weight-normal">Materia</th>
					<th class="letraPequena font-weight-normal">Alumnos</th>
					<th class="letraPequena font-weight-normal">Lunes</th>
					<th class="letraPequena font-weight-normal">Martes</th>
					<th class="letraPequena font-weight-normal">Miercoles</th>
					<th class="letraPequena font-weight-normal">Jueves</th>
					<th class="letraPequena font-weight-normal">Viernes</th>
					<th class="letraPequena font-weight-normal">Sabado</th>
					<th class="letraPequena font-weight-normal">Domingo</th>
				</tr>
			</thead>

			<tbody >

				<?php
					$i = 1;
					while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

						$id_sub_hor = $filaHorario['id_sub_hor'];
				?>

					<tr style="height: 80px;">
						<td class="letraPequena font-weight-normal">
							<?php echo $i; $i++;  ?>
						</td>

						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['nom_ram']; ?>			
						</td>

						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['mod_ram']; ?>			
						</td>

						<td class="letraPequena font-weight-normal" title="<?php echo "Inscripción: ".fechaFormateadaCompacta($filaHorario['ins_cic']); ?> <?php echo "Inicio: ".fechaFormateadaCompacta($filaHorario['ini_cic']); ?> <?php echo "Corte: ".fechaFormateadaCompacta($filaHorario['cor_cic']); ?> <?php echo "Fin: ".fechaFormateadaCompacta($filaHorario['fin_cic']); ?>">
							<?php echo $filaHorario['nom_cic']; ?>
						</td>

						<td class="letraPequena font-weight-normal" title="<?php echo $filaHorario['nom_cic']; ?>">
							<?php echo fechaFormateadaCompacta($filaHorario['ini_cic']); ?>
						</td>

						<td class="letraPequena font-weight-normal" title="<?php echo $filaHorario['nom_cic']; ?>">
							<?php echo fechaFormateadaCompacta($filaHorario['fin_cic']); ?>
						</td>

				
						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['nom_gru']; ?>
						</td>

						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['nom_sub_hor']; ?>
						</td>

						<td class=" btn btn-info letraPequena font-weight-normal verActividades" id_sub_hor="<?php echo $id_sub_hor; ?>" materia="<?php echo $filaHorario['nom_mat'].' '.$filaHorario['nom_sub_hor']; ?>" grupo="<?php echo $filaHorario['nom_gru']; ?>"
							title="Configuración Avanzada de Actividades de <?php echo $filaHorario['nom_mat']; ?>"	style="height: 60px; width: 100px;">
							<?php echo substr( $filaHorario['nom_mat'], 0, 25); ?>
						</td>


						<td class="letraPequena font-weight-normal">
							<?php  

								
								$sqlTotalAlumnos = "

									SELECT *
									FROM alu_hor 
									INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
									INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
									INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
									INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
									WHERE id_sub_hor = '$id_sub_hor' AND est_alu_hor = 'Activo'

								";

								$resultadoTotalAlumnos = mysqli_query($db, $sqlTotalAlumnos);

								// while($filaTotalAlumnos = mysqli_fetch_assoc($resultadoTotalAlumnos)){

								// 	echo $filaTotalAlumnos['nom_alu'];

								// }


								$totalAlumnos = mysqli_num_rows($resultadoTotalAlumnos);

								if ($totalAlumnos > 0) {
							?>

									<a href="#" class="btn btn-link verAlumnos" id_sub_hor="<?php echo $id_sub_hor; ?>" materia="<?php echo $filaHorario['nom_mat']; ?>" grupo="<?php echo $filaHorario['nom_gru']; ?>">
										<?php  
											echo $totalAlumnos;
										?>
									</a>
							<?php  
									
						

								}else{
							?>

								0


							<?php
								}

								

							?>


						</td>



						<?php
							$id_sub_hor = $filaHorario['id_sub_hor'];
							
							//LUNES
							$sqlSubHorLunes = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Lunes' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorLunes = mysqli_query($db, $sqlSubHorLunes);

							$filasLunes = mysqli_num_rows($resultadoSubHorLunes);

							if ($filasLunes == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorLunes = mysqli_fetch_assoc($resultadoSubHorLunes)){
								
								?>
									<td class="letraPequena font-weight-normal">
										<?php 
											echo $filaSubHorLunes['ini_hor']."-".$filaSubHorLunes['fin_hor']; 
										?>
										
									</td>
						

						<?php
								}
							}
								
							//MARTES
							$sqlSubHorMartes = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Martes' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorMartes = mysqli_query($db, $sqlSubHorMartes);

							$filasMartes = mysqli_num_rows($resultadoSubHorMartes);

							if ($filasMartes == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorMartes = mysqli_fetch_assoc($resultadoSubHorMartes)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorMartes['ini_hor']."-".$filaSubHorMartes['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}

							//MIERCOLES
							$sqlSubHorMiercoles = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Miércoles' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorMiercoles = mysqli_query($db, $sqlSubHorMiercoles);

							$filasMiercoles = mysqli_num_rows($resultadoSubHorMiercoles);

							if ($filasMiercoles == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorMiercoles = mysqli_fetch_assoc($resultadoSubHorMiercoles)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorMiercoles['ini_hor']."-".$filaSubHorMiercoles['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}

							//JUEVES
							$sqlSubHorJueves = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Jueves' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorJueves = mysqli_query($db, $sqlSubHorJueves);

							$filasJueves = mysqli_num_rows($resultadoSubHorJueves);

							if ($filasJueves == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorJueves = mysqli_fetch_assoc($resultadoSubHorJueves)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorJueves['ini_hor']."-".$filaSubHorJueves['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}


							//VIERNES
							$sqlSubHorViernes = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Viernes' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorViernes = mysqli_query($db, $sqlSubHorViernes);

							$filasViernes = mysqli_num_rows($resultadoSubHorViernes);

							if ($filasViernes == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorViernes = mysqli_fetch_assoc($resultadoSubHorViernes)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorViernes['ini_hor']."-".$filaSubHorViernes['fin_hor']; 
											?>
											
										</td>

						<?php
								}
							}


							//SABADO
							$sqlSubHorSabado = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Sábado' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorSabado = mysqli_query($db, $sqlSubHorSabado);

							$filasSabado = mysqli_num_rows($resultadoSubHorSabado);

							if ($filasSabado == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorSabado = mysqli_fetch_assoc($resultadoSubHorSabado)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorSabado['ini_hor']."-".$filaSubHorSabado['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}
								

							//DOMINGO
							$sqlSubHorDomingo = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Domingo' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorDomingo = mysqli_query($db, $sqlSubHorDomingo);

							$filasDomingo = mysqli_num_rows($resultadoSubHorDomingo);

							if ($filasDomingo == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorDomingo = mysqli_fetch_assoc($resultadoSubHorDomingo)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorDomingo['ini_hor']."-".$filaSubHorDomingo['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}
								
				
						?>

					</tr>


				<?php

					}
					//FIN WHILE
				?>
				
				

				
			</tbody>

		</table>
	</div>
	
</div>
<!-- FIN HORARIOS -->















<!-- FIN CONTENIDO -->




<!-- MODAL -->
<!-- Central Modal Medium Info -->
<div class="modal fade" id="modalAlumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Alumnos de <span id="modalTitulo"></span></p>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
        <div class="text-center" id="contenedorModal">
          
        </div>
      </div>

    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Central Modal Medium Info-->
<!-- FIN MODAL -->




<!-- MODAL ACTIVIDADES-->
<!-- Central Modal Medium Info -->
<div class="modal fade" id="modalActividades" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-fluid" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Actividades de <span id="modalTituloActividades"></span></p>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
        <div class="text-center" id="contenedorActividades">
          
        </div>
      </div>

    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Central Modal Medium Info-->
<!-- FIN MODAL ACTIVIDADES-->

<?php  

  include('inc/footer.php');

?>

<script>
	$(document).ready(function () {


		$('#myTableHorarios').DataTable({
			
		
			dom: 'Bfrtlip',

			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
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
                            "sInfo":           "Registros encontrados: _TOTAL_",
                            "sInfoEmpty":      "No se encontraron registros",
                            "sInfoFiltered":   "",
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
		$('#myTableHorarios_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTableHorarios_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTableHorarios_wrapper input').attr("placeholder", "Buscar...").addClass('letraPequena font-weight-normal');
			$('#myTableHorarios_wrapper input').removeClass('form-control-sm');
		});
		$('#myTableHorarios_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTableHorarios_wrapper .dataTables_filter').addClass('md-form');
		$('#myTableHorarios_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTableHorarios_wrapper select').addClass('mdb-select');
		$('#myTableHorarios_wrapper .mdb-select').materialSelect();
		$('#myTableHorarios_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTableHorarios_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);
		var tablaHorarios = $('#myTableHorarios').DataTable();

		var entrada = $('#myTableHorarios_filter');
		
		$('#selectorProfesor').materialSelect('destroy');
		$('#selectorProfesor').materialSelect();

		$("#contenedor_registros").html($("#myTableHorarios_info"));

		$( "#contenedor_buscador" ).append( entrada );




		// CHECKBOX PROGRAMA
	    $('.checador5').on( 'keyup change', function () {
	      var busqueda = [];
	      for(var i = 0; i < $('.checador5').length; i++){
	        if($('.checador5').eq(i).prop("checked") == true){
	          //console.log($('.checador1').eq(i).val());
	          if(busqueda=="")
	          {
	            busqueda=$('.checador5').eq(i).val();   
	          }
	          else
	          {
	            busqueda = busqueda+'|'+$('.checador5').eq(i).val();    
	          }
	          
	        }
	      }
	      
	      var columna = $(this).attr("columna");
	      if (busqueda != "") {
	          tablaHorarios
	          .columns( columna )
	          .search( busqueda, true, false)
	          .draw();
	      }else{
	          tablaHorarios
	          .columns( columna )
	          .search('')
	          .draw();
	      }
	  
	    });

	    // FIN CHECKBOX PROGRAMA



	    // FUNCION ADICIONAL DE FILTROS POR FECHA
	    $.fn.dataTable.ext.search.push(

	      
	        function fechas( settings, data, dataIndex ) {
	          var min  = $('#min-date').val();
	          var max  = $('#max-date').val();
	          
	          for(var i = 0; i < $(".columna").length; i++){
	            if ($(".columna")[i].checked == true) {
	              var columna = $(".columna").eq(i).attr("columna");
	            }
	          }
	          
	          //console.log(columna);

	          var arregloFechas = moment(data[columna] || 0,"DD/MM/YYYY").format("YYYY-MM-DD"); 
	            // Our date column in the tablaCobros
	            //console.log(moment(arregloFechas).isValid());

	          if  ( 
	                  ( min == "" || max == "" )
	                  || 
	                  ( moment(arregloFechas).isSameOrAfter(min) && 
	                    moment(arregloFechas).isSameOrBefore(max))
	              )
	          {
	              return true;
	          }
	          return false;
	        }
	    );

	    // Re-draw the tablaCobros when the a date range filter changes
	    $('.date-range-filter').change( function() {
	        tablaHorarios.draw();

	    });




	    $('#selectorProfesor').on( 'keyup change', function () {
	      //console.log($(this));
	      var busqueda = [];
	      
	      busqueda = $('#selectorProfesor option:selected').val();
	      
	      var columna = $(this).attr("columna");

	      // SI ES 0 APARECE TODO, O SEGUN LA PRIORIDAD ELEGIDA
	      //console.log(busqueda);
	      if (busqueda != "Vacio") {
	          tablaHorarios
	          .columns( columna )
	          .search( busqueda, true, false)
	          .draw();
	      }else{
	        tablaHorarios
	          .columns( columna )
	          .search('')
	          .draw();
	      }
	      
	    });

	
	});
</script>


<script>
	//LISTAS ALUMNOS

	$(".verAlumnos").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var id_sub_hor = $(this).attr("id_sub_hor");
		var materia = $(this).attr("materia");
		var grupo = $(this).attr("grupo");

		$.ajax({
			url: 'server/obtener_alumnos_horario.php',
			type: 'POST',
			data: {id_sub_hor},
			success: function(respuesta){

				$("#modalAlumnos").modal("show");
				$("#modalTitulo").html(materia);
				$("#modalGrupo").html(grupo);
				$("#contenedorModal").html(respuesta);
			}
		});
		
	});
</script>




<script>
	//LISTAS ACTIVIDADES

	$(".verActividades").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var id_sub_hor = $(this).attr("id_sub_hor");
		var materia = $(this).attr("materia");

		$.ajax({
			url: 'server/obtener_actividades_materias_horario.php',
			type: 'POST',
			data: { id_sub_hor },
			success: function(respuesta){

				$("#modalActividades").modal("show");
				$("#modalTituloActividades").html( materia );
				$("#contenedorActividades").html( respuesta );


				// FINALIZAR
				$("#btn_finalizar_configuracion_avanzada").on('click', function(event) {
				    // event.preventDefault();
				    /* Act on the event */


				    $("#btn_finalizar_configuracion_avanzada").attr('disabled','disabled');
				      swal({
				          title: "Confirmación de horario",
				          text: "¿Estás seguro de continuar?",
				          icon: "warning",
				          buttons:  {
				              cancel: {
				                text: "Cancelar",
				                value: null,
				                visible: true,
				                closeModal: true,
				                className: "btn-danger waves-effect"
				              },
				            confirm: {
				              text: "Confirmar",
				              value: true,
				              visible: true,
				              closeModal: true,
				              className: "btn-info waves-effect"
				            }
				          },
				          dangerMode: true,
				      }).then((willDelete) => {
				      if (willDelete) {
				        //VALIDACION ACEPTADA
				 

				        // OBTENCION DE TIPOS E IDENTIFICADORES PARA ENVIO
				   
				        // OBTENCION DE TIPOS E IDENTIFICADORES PARA ENVIO
				        var tipos = [];
				        var identificadores = [];
				        var inicios = [];
				        var fines = [];
				        var estatus = [];

				        for( var i = 0 ; i < $('.checkboxActividadesTodos').length ; i++  ){
				          if ( $('.checkboxActividadesTodos')[i].checked == true ) {
				            // console.log("checkeado");

				            tipos[i] = $('.checkboxActividadesTodos').eq(i).attr('tipo');
				            identificadores[i] = $('.checkboxActividadesTodos').eq(i).attr('identificador');
				            inicios[i] = $('.checkboxActividadesTodos').eq(i).attr('inicio');
				            fines[i] = $('.checkboxActividadesTodos').eq(i).attr('fin');
				            estatus[i] = 'Verdadero';
				           
				          }else{

				          	tipos[i] = $('.checkboxActividadesTodos').eq(i).attr('tipo');
				            identificadores[i] = $('.checkboxActividadesTodos').eq(i).attr('identificador');
				            inicios[i] = $('.checkboxActividadesTodos').eq(i).attr('inicio');
				            fines[i] = $('.checkboxActividadesTodos').eq(i).attr('fin');
				            estatus[i] = 'Falso';
				          }
				        }

				        

				        $.ajax({
				        	url: 'server/editar_actividades_horario.php',
				        	type: 'POST',
				        	data: { tipos, identificadores, inicios, fines, estatus },
				        	success: function ( respuesta ){

				        		if (respuesta == "true") {
									console.log("Exito en consulta");
									swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",});


								}else{
									console.log(respuesta);

								}

				        	}
				        });
				        

				        

				        

				              
				        //console.log("acepto");
				        
				      }else{

				        console.log("no acepto");
				      }
				    });




				    
				    
				});



				// FIN FINALIZAR
			}
		});
		
	});
</script>