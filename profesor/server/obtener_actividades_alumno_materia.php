<?php  
	//ARCHIVO VIA AJAX PARA OBTENER ACTIVIDADES DE ALUMNO X MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_alu_ram = $_POST['id_alu_ram'];
	$id_sub_hor = $_POST['id_sub_hor'];	

	$sqlActividades = "
		

		SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
		FROM alu_ram
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		
		INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
        INNER JOIN foro ON foro.id_for = foro_copia.id_for1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN bloque ON bloque.id_mat6 = materia.id_mat
        INNER JOIN rama ON rama.id_ram = materia.id_ram2

		INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
		WHERE id_alu_ram4 = '$id_alu_ram' AND id_sub_hor = '$id_sub_hor' AND id_pro1 = '$id'
		UNION
		SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
		FROM alu_ram
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		
		INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
        INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN bloque ON bloque.id_mat6 = materia.id_mat
        INNER JOIN rama ON rama.id_ram = materia.id_ram2

		INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
		WHERE id_alu_ram4 = '$id_alu_ram' AND id_sub_hor = '$id_sub_hor' AND id_pro1 = '$id'
		UNION
		SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
		FROM alu_ram
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		
		INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
        INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN bloque ON bloque.id_mat6 = materia.id_mat
        INNER JOIN rama ON rama.id_ram = materia.id_ram2

		INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
		WHERE id_alu_ram4 = '$id_alu_ram' AND id_sub_hor = '$id_sub_hor' AND id_pro1 = '$id'
		ORDER BY inicio ASC

	";
	$resultadoActividades = mysqli_query($db, $sqlActividades);
	//echo $sqlActividades;
?>
	<div class="row text-center animated fadeInUp delay-1s">
		<div class="col">
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Puntos Totales">
			  <div class="card-header bg-info">Puntos</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="puntos"></span></h2>
			  </div>
			</div>
		</div>
		<div class="col">
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Puntos Acumulados">
			  <div class="card-header bg-info">Puntos obtenidos</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="puntosObtenidos"></span></h2>
			  </div>
			</div>
		</div>

		<div class="col">
			
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Porcentaje de aprovechamiento (Un aproximado de tu evaluación online)">
			  <div class="card-header bg-info">Aprovechamiento</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="porcentaje"></span></h2>
			  </div>
			</div>

	        
		</div>


	</div>



	<div class="row animated fadeInDown delay-2s">
		<div class="col-md-2">
			
		</div>
		<div class="col-md-5">
			<br>
			<br>
			<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
			  <input type="radio" class="form-check-input columna" id="inicio" columna="3" name="inlineMaterialRadiosExample" checked>
			  <label class="form-check-label" for="inicio">Inicio</label>
			</div>

			<!-- Material inline 2 -->
			<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
			  <input type="radio" class="form-check-input columna" id="fin" columna="4" name="inlineMaterialRadiosExample">
			  <label class="form-check-label" for="fin">Fin</label>
			</div>

			<!-- Material inline 3 -->
			<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
			  <input type="radio" class="form-check-input columna" id="realizacion" columna="10" name="inlineMaterialRadiosExample">
			  <label class="form-check-label" for="realizacion">Realización</label>
			</div>
		</div>
		<div class="col-md-5">
			<div class="md-form mb-2">
	          <input type="date" id="min-date" class="date-range-filter form-control validate" title="Inicio del Rango">
	        </div>

			
	        <div class="md-form mb-2">
	          <input type="date" id="max-date" class="date-range-filter form-control validate" title="Fin del Rango">
	        </div>
			
		</div>
	</div>

	

	<table id="myTableActividadesAlumnoMateria" class="table table-hover table-sm text-left animated fadeInDown table-responsive" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr >
				<th>#</th>
				<th>Actividad</th>
				<th>Materia</th>
				<th>Inicio</th>
				<th>Fin</th>
				<th>Tipo</th>
				<th>Puntos</th>
				<th>Puntos Obtenidos</th>
				<th>Retroalimentación</th>
				<th>Estatus</th>
				<th>Fecha de realización</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaActividades = mysqli_fetch_assoc($resultadoActividades)){

		?>
			<tr>
				<td><?php echo $i; $i++;?></td>
		
				<td>
					
					<?php  
						if ($filaActividades['tipo'] == 'Foro') {
					?>
							<a href="foro.php?id_for_cop=<?php echo $filaActividades['id']; ?> " target="_blank" class="btn btn-link" title="Foro: <?php echo $filaActividades['actividad']; ?>">
								<?php echo $filaActividades['actividad']; ?>
							</a>
					<?php
						}else if($filaActividades['tipo'] == 'Entregable'){
					?>		
							<a href="entregable.php?id_ent_cop=<?php echo $filaActividades['id']; ?> " target="_blank" class="btn btn-link" title="Entregable: <?php echo $filaActividades['actividad']; ?>">
								<?php echo $filaActividades['actividad']; ?>
							</a>

					<?php
						}else if($filaActividades['tipo'] == 'Examen'){
					?>
							<a href="examen.php?id_exa_cop=<?php echo $filaActividades['id']; ?> " target="_blank" class="btn btn-link" title="Examen: <?php echo $filaActividades['actividad']; ?>">
								<?php echo $filaActividades['actividad']; ?>
							</a>

					<?php
						}

					?>					
					

						
				</td>


				<td>
					<?php  

						echo $filaActividades['materia'];
					?>
				</td>
				
				<td>
					<?php
						$inicio = $filaActividades['inicio']; 
						echo fechaFormateadaCompacta($inicio); 
					?>
						
				</td>
				
				<td>
					<?php
						$fin = $filaActividades['fin']; 
						echo fechaFormateadaCompacta($fin); 
					?>
						
				</td>
				
				<td><?php echo $filaActividades['tipo']; ?></td>
				<td><?php echo $filaActividades['puntaje']; ?></td>
				<td>
					
					<?php  
						if ($filaActividades['calificacion'] == NULL) {
							echo "Pendiente";
						}else{
							echo $filaActividades['calificacion'];
						}

					?>
				</td>


				<td>
					
					<?php  
						if ($filaActividades['retroalimentacion'] == NULL) {
							echo "Pendiente";
						}else{
							echo $filaActividades['retroalimentacion'];
						}

					?>
				</td>


				<td>
					
					<?php  
						if ($filaActividades['calificacion'] == NULL) {
							echo "Pendiente";
						}else{
							echo "Finalizado";
						}

					?>
				</td>

				<td>
					
					<?php  
						if ($filaActividades['fecha'] == NULL) {
							echo "Pendiente";
						}else{
							$fechaRealizacion = $filaActividades['fecha'];
							echo fechaFormateadaCompacta($fechaRealizacion); 
						}

					?>
				</td>



			</tr>


		<?php
			} 

		?>
	</table>



<script>
	$(document).ready(function () {
		


		$('#myTableActividadesAlumnoMateria').DataTable({
			
		
			dom: 'frtlip',
            
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

		$('#myTableActividadesAlumnoMateria_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTableActividadesAlumnoMateria_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTableActividadesAlumnoMateria_wrapper input').attr("placeholder", "Buscar...");
			$('#myTableActividadesAlumnoMateria_wrapper input').removeClass('form-control-sm');
		});
		$('#myTableActividadesAlumnoMateria_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTableActividadesAlumnoMateria_wrapper .dataTables_filter').addClass('md-form');
		$('#myTableActividadesAlumnoMateria_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTableActividadesAlumnoMateria_wrapper select').addClass('mdb-select');
		$('#myTableActividadesAlumnoMateria_wrapper .mdb-select').materialSelect();
		$('#myTableActividadesAlumnoMateria_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTableActividadesAlumnoMateria_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);





		var table = $('#myTableActividadesAlumnoMateria').DataTable();
		var puntos = table.column( 6, {filter:'applied'} ).data().sum();
		var puntosObtenidos = table.column( 7, {filter:'applied'} ).data().sum();



		if (isNaN(puntosObtenidos)) {

			$('#puntos').text(puntos);
			$('#puntosObtenidos').text("Pendiente");	
				
		}else{
			
			$('#puntos').text(puntos);	
			$('#puntosObtenidos').text(puntosObtenidos);
			var porcentaje = ((table.column(7, {filter: 'applied'}).data().sum())*100)/(table.column(6, {filter: 'applied'}).data().sum());
			if (isNaN(porcentaje)) {
				$("#porcentaje").text("Nulo");
			}else{
				//console.log(Math.round(porcentaje*100)/100);
	    		$("#porcentaje").text("% "+Math.round(porcentaje*100)/100);
			}
	    	
		}


		table.on('draw', function(){
	    	$("#puntos").text(table.column(6, {filter: 'applied'}).data().sum());
	    	$("#puntosObtenidos").text(table.column(7, {filter: 'applied'}).data().sum());
	    	var porcentaje = ((table.column(7, {filter: 'applied'}).data().sum())*100)/(table.column(6, {filter: 'applied'}).data().sum());
	    	//console.log(Math.round(porcentaje*100)/100);
	    	if (isNaN(porcentaje)) {
				$("#porcentaje").text("Nulo");
			}else{
				//console.log(Math.round(porcentaje*100)/100);
	    		$("#porcentaje").text("% "+Math.round(porcentaje*100)/100);
			}
	    });

		

		//var columna = 3;

	    // Extend dataTables search
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
		        // Our date column in the table
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

		// Re-draw the table when the a date range filter changes
		$('.date-range-filter').change( function() {
		    table.draw();

		});

		// Re-draw the table when the radio buttons change
		$('.columna').change( function() {
		    table.draw();

		});


	
	});
</script>