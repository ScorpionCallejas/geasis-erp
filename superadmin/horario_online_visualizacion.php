<?php  
		

  include('inc/header.php');
?>
<!-- CONTENIDO -->
<?php 
	$id_gru = $_GET['id_gru'];
	$sqlHorario = "
		SELECT * 
    	FROM sub_hor
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_gru1 = '$id_gru'

	";

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	$resultadoHorarioNav = mysqli_query($db, $sqlHorario);
	$filaHorarioNav = mysqli_fetch_assoc($resultadoHorarioNav);
	$id_ram= $filaHorarioNav['id_ram'];
	$id_cic= $filaHorarioNav['id_cic'];
	$nom_cic= $filaHorarioNav['nom_cic'];
	$nom_ram= $filaHorarioNav['nom_ram'];
	$nom_gru= $filaHorarioNav['nom_gru'];


	
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
 <!-- CONTENIDO --><!--INICIO DE DESPLIEGUE DE TITULO-->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Grupos con los que cunetas en la Rama">
			<i class="fas fa-bookmark"></i> 
			Horario
		</span>
		<br>
		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al Inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="ramas.php" title="Vuelve a Programas"><span class="text-white">Programas</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="ciclos.php?id_ram=<?php echo "$id_ram"; ?>" title="Vuelve a los Ciclos"><span class="text-white">Ciclos</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="grupos.php?id_cic=<?php echo "$id_cic"; ?>" title="Vuelve a los Grupos"><span class="text-white">Grupos</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="" title="Estás aquí"><span style="color: black;">Horario</span></a>		
		</div>
	</div>
	<div class="col text-right">

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Rama de Estudio <?php echo $nom_ram; ?>">
				<i class="fas fa-certificate"></i>
				Programa: <?php echo $nom_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Ciclo en el que estás">
			<i class="fas fa-certificate"></i>
			 Ciclo: <?php echo $nom_cic; ?>
		</span>	
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Grupo">
			<i class="fas fa-certificate"></i>
			 Grupo: <?php echo $nom_gru; ?>
		</span>	<br>
	</div>
</div>
<!-- FIN DE DESPLIEGUE DE TITULO-->


	<div class="row">
		<div class="col-md-3">
			
		</div>
		<div class="col-md-6">
			<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTableHorarioOnline">
				<thead class="grey lighten-2">
					<tr>
						<th>#</th>
						<th>Profesor</th>
						<th>Materia</th>
					</tr>
				</thead>

				<tbody >

					<?php
						$i = 1;

						while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

					?>

						<tr>
							<td>
								<?php echo $i; $i++;  ?>
							</td>


							<td>
								<?php echo $filaHorario['nom_pro']; ?>
							</td>


							<td>
								<?php echo $filaHorario['nom_mat']; ?>
							</td>
						</tr>
					<?php  
						}
					?>

				</tbody>

			</table>
		</div>
		<div class="col-md-3">
			
		</div>
		
			


	</div>


<!-- FIN CONTENIDO -->
<?php  

  include('inc/footer.php');

?>

<script>
	$(document).ready(function () {


		$('#myTableHorarioOnline').DataTable({
			
		
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
		$('#myTableHorarioOnline_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTableHorarioOnline_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTableHorarioOnline_wrapper input').attr("placeholder", "Buscar...");
			$('#myTableHorarioOnline_wrapper input').removeClass('form-control-sm');
		});
		$('#myTableHorarioOnline_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTableHorarioOnline_wrapper .dataTables_filter').addClass('md-form');
		$('#myTableHorarioOnline_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTableHorarioOnline_wrapper select').addClass('mdb-select');
		$('#myTableHorarioOnline_wrapper .mdb-select').materialSelect();
		$('#myTableHorarioOnline_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTableHorarioOnline_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

	
	});
</script>