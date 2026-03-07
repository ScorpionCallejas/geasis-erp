<?php  
	require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];

	$sqlCalificacion = "
		SELECT *
		FROM materia
		INNER JOIN calificacion ON calificacion.id_mat4 = materia.id_mat
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = calificacion.id_alu_ram2
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		WHERE id_alu_ram = '$id_alu_ram'
		ORDER BY cic_mat ASC, nom_mat ASC
	";

	// echo $sqlCalificacion;

	$resultadoCalificacion = mysqli_query( $db, $sqlCalificacion );

?>

<form id="formularioCalificacion">
	<button type="submit" id="btn_enviar" class="btn btn-info btn-rounded btn-sm waves-effect">Guardar cambios</button>

	<div class="table-responsive">

		<table id="myTableCalificaciones" class="table table-hover" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>#</th>
					<th>Materia</th>
					<th>Nivel</th>
					<th>Extra</th>
					<th>Final</th>
				</tr>
			</thead>


			<?php 
				$i = 1;
				while( $filaCalificacion = mysqli_fetch_assoc( $resultadoCalificacion ) ){
					$id_mat = $filaCalificacion['id_mat'];
			?>
				<tr>
					<td>
						<?php echo $i; $i++;?>
							
					</td>
			
					<td>
						<?php  

							echo $filaCalificacion['nom_mat'];
						?>	
					</td>


					<td>
						<?php  
							echo $filaCalificacion['cic_mat'];
						?>
					</td>
					
					<?php
						$id_mat = $filaCalificacion['id_mat'];
						$sqlEvaluacionCalificacion = "
							SELECT *
							FROM calificacion
							WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat'
							GROUP BY id_mat4
						";

						// echo $sqlEvaluacionCalificacion;

						$resultadoEvaluacionCalificacion = mysqli_query($db, $sqlEvaluacionCalificacion);

						while ($filaEvaluacion = mysqli_fetch_assoc($resultadoEvaluacionCalificacion)) {
					?>
						<td>

	                        <input type="hidden" name="id_cal[]" value="<?php echo $filaEvaluacion['id_cal']; ?>">
	                        <?php
	                        
	                            if ($filaEvaluacion['ext_cal'] == NULL) {
	                        ?>
	                            
	                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="ext_cal[]">
	                        <?php
	                            }else{
	                        ?>
	                            
	                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="ext_cal[]" value="<?php echo $filaEvaluacion['ext_cal']; ?>">

	                        <?php
	                            }
	                        
	                        ?>
	                    </td>

	                    <td>
	                        <?php
	                        
	                            if ($filaEvaluacion['fin_cal'] == NULL) {
	                        ?>
	                 
	                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="fin_cal[]">
	                        <?php
	                            }else{
	                        ?>
	                           
	                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="fin_cal[]" value="<?php echo $filaEvaluacion['fin_cal']; ?>">

	                        <?php
	                            }
	                        
	                        ?>
	                    </td>
					<?php
						}

					?>


					
				</tr>


			<?php
				} 

			?>
		</table>

	</div>

</form>

<script>

	$('#myTableCalificaciones').DataTable({
	
		dom: 'Bfrtlip',
        pageLengh: -1,
        buttons: [

            {
                extend: 'excelHtml5',
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

	$('#myTableCalificaciones_wrapper').find('label').each(function () {
		$(this).parent().append($(this).children());
	});
	$('#myTableCalificaciones_wrapper .dataTables_filter').find('input').each(function () {

		$('#myTableCalificaciones_wrapper #myTableCalificaciones_filter input').attr("placeholder", "Buscar...");

		// $('#myTableCalificaciones_wrapper input evaluaciones').attr("placeholder", "Buscar...");
		$('#myTableCalificaciones_wrapper input').removeClass('form-control-sm');
	});
	$('#myTableCalificaciones_wrapper .dataTables_length').addClass('d-flex flex-row');
	$('#myTableCalificaciones_wrapper .dataTables_filter').addClass('md-form');
	$('#myTableCalificaciones_wrapper select').removeClass(
	'custom-select custom-select-sm form-control form-control-sm');
	$('#myTableCalificaciones_wrapper select').addClass('mdb-select');
	$('#myTableCalificaciones_wrapper .mdb-select').materialSelect();
	$('#myTableCalificaciones_wrapper .dataTables_filter').find('label').remove();
	var botones = $('#myTableCalificaciones_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
	//console.log(botones);

</script>


<script>
	$("#formularioCalificacion").on('submit', function(event) {
		event.preventDefault();

		$.ajax({
                                        
            url: 'server/editar_evaluacion_alumno.php?id_alu_ram=<?php echo $id_alu_ram; ?>',
            type: 'POST',
            data: new FormData(formularioCalificacion),
            processData: false,
            contentType: false,
            cache: false,
            success: function(respuesta){
                console.log(respuesta);

                generarAlerta('Cambios guardados');
            }
        });								

	});
</script>