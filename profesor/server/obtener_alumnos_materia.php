<?php  
	//ARCHIVO VIA AJAX PARA OBTENER BLOQUES DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];


	//CONSULTA ALUMNOS ASOCIADOS AL PROFESOR Y A LA MATERIA QUE ESTE IMPARTE
	
?>


<!-- JUMBOTRON DE ALUMNOS -->



    <div class="card grey lighten-1 mb-3 waves-effect hoverable white-text selectoresElemento " style="max-width: 20rem;" id_alu_ram="alumnosMateria">
        <div class="card-header  grey darken-1" title="Carga todos los alumnos de la materia">
            Alumnos de la Materia
        </div>
    </div>


	
	<table class="table table-hover table-responsive table-sm animated fadeInDown" id="myTableAlumnosMateria">
        <thead class="grey lighten-3 text-center">
            <th>#</th>
            <th>Foto</th>
            <th>Nombre</th>
            
        </thead>
        <tbody class="text-center">
            <?php

                $sqlAlumnos = "
					SELECT *
					FROM alu_ram
					INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
					INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
					WHERE id_pro1 = '$id' AND id_sub_hor = '$id_sub_hor'
				";

                //echo $sqlAlumnos;

                $resultadoAlumnos = mysqli_query($db, $sqlAlumnos);
                $i = 1;
                while ($filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos)) {
            ?>
                <tr>
                    <td>
                        <?php echo $i; $i++; ?>
                    </td>

                    <td>
                        <img src="../uploads/<?php echo $filaAlumnos['fot_alu']; ?>" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-2 z-depth-1" width="30px" height="40px">
                    </td>

                    <td>
                    	<div class="card   grey lighten-1 mb-3 waves-effect hoverable white-text selectoresElemento"  style="max-width: 20rem;" title="Haz click para evaluar y conocer el progreso de actividades de <?php echo $filaAlumnos['nom_alu']." ".$filaAlumnos['app_alu']; ?>" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>">
							<div class="card-header  grey darken-1 letraPequena">
								<?php echo $filaAlumnos['nom_alu']." ".$filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']; ?>
							</div>
						 
						</div>
                    </td>

      
                </tr>
            <?php
                }  
            ?>
        </tbody>
    </table>

<!-- FIN JUMBOTRON DE ALUMNOS -->


<script>
    $(document).ready(function () {
        $.fn.dataTable.ext.search.pop();
        $('#myTableAlumnosMateria').DataTable({
            
        
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
        $('#myTableAlumnosMateria_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableAlumnosMateria_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableAlumnosMateria_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableAlumnosMateria_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableAlumnosMateria_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableAlumnosMateria_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableAlumnosMateria_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableAlumnosMateria_wrapper select').addClass('mdb-select');
        $('#myTableAlumnosMateria_wrapper .mdb-select').materialSelect();
        $('#myTableAlumnosMateria_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableAlumnosMateria_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);
    });
</script>