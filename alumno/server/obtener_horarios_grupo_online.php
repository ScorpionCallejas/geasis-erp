<?php  
	//ARCHIVO VIA AJAX PARA OBTENER HORARIOS  DE GRUPO EN CICLOS CON ESTATUS ACTIVO PARA INSCRIPCION
	//inscripcion.php
	require('../inc/cabeceras.php');

	$id_gru = $_POST['grupo'];	

	$sqlHorario = "
		SELECT * 
    	FROM sub_hor
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_gru1 = '$id_gru'
		GROUP BY nom_mat

		";
	$resultadoHorario = mysqli_query($db, $sqlHorario);

?>

<style>


.botonHijo {
  position: absolute;
  right: 0%;
  top: -20%;
}

.botonPadre {
  position: relative;
}
</style>

			<table class="table table-sm text-center table-hover animated fadeInDown" cellspacing="0" width="99%" id="myTable">
				<thead class="grey lighten-2">
					<tr class="filasHorario">
						
						<th>Profesor</th>
						<th>Materia</th>
						<th></th>

					</tr>
				</thead>

				<tbody >

					<?php
					

						while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

					?>

						<tr class="filasHorario" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>">
				


							<td>
								<?php echo $filaHorario['nom_pro']; ?>
							</td>

							<td class="botonPadre">
								<?php echo $filaHorario['nom_mat']; ?>
								
								<div class="waves-effect btn-sm btn-info btn-floating botonHijo sub_hor" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>">
									<i class="fas fa-plus-circle fa-2x" title="Agregar este horario"></i>
								</div>

							</td>

						</tr>


					<?php

						}
						//FIN WHILE
					?>
					
					
	
					
				</tbody>

			</table>


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