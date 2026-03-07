<?php   
	//ARCHIVO VIA AJAX PARA OBTENER BLOQUES DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');

	$id_sub_hor = $_POST['id_sub_hor'];	

	$sqlBloques = "
		SELECT * 
		FROM sub_hor
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    	INNER JOIN bloque ON bloque.id_mat6 = materia.id_mat
    	INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    	WHERE id_sub_hor = '$id_sub_hor'
    	ORDER BY id_blo ASC

	";
	$resultadoBloques = mysqli_query($db, $sqlBloques);
	//echo $sqlBloques;
?>
 


	<table id="myTableBloquesMateria" class="table table-hover table-bordered table-sm animated fadeInDown" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th>#</th>
				<th>Bloque</th>
				<th>Descripción</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaBloques = mysqli_fetch_assoc($resultadoBloques)){
				$id_gru = $filaBloques['id_gru'];

		?>
			<tr>
				<td><?php echo $i; $i++;?></td>
		
				<td>
					<a href="bloque_contenido.php?id_blo=<?php echo $filaBloques['id_blo'].'&id_gru='.$id_gru; ?> " target="_blank" class="btn btn-link" title="Contenido del bloque <?php echo $filaBloques['nom_blo']; ?> ">
						<?php echo $filaBloques['nom_blo']; ?>
					</a>
					
						
				</td>
				
				<td><?php echo $filaBloques['des_blo']; ?></td>
				
		

			</tr>


		<?php
			} 

		?>
	</table>



<script>
	$(document).ready(function () {
		$.fn.dataTable.ext.search.pop();
		$('#myTableBloquesMateria').DataTable({
			
		
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
		$('#myTableBloquesMateria_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTableBloquesMateria_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTableBloquesMateria_wrapper input').attr("placeholder", "Buscar...");
			$('#myTableBloquesMateria_wrapper input').removeClass('form-control-sm');
		});
		$('#myTableBloquesMateria_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTableBloquesMateria_wrapper .dataTables_filter').addClass('md-form');
		$('#myTableBloquesMateria_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTableBloquesMateria_wrapper select').addClass('mdb-select');
		$('#myTableBloquesMateria_wrapper .mdb-select').materialSelect();
		$('#myTableBloquesMateria_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTableBloquesMateria_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

	
	});
</script>