<?php  

    //ARCHIVO VIA AJAX PARA OBTENER TODOS LOS ALUMNOS DE UNA MATERIA DE HORARIO
    //entregable.php/
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_sub_hor = $_POST['id_sub_hor'];

    $sqlMateria = "
        SELECT *
        FROM sub_hor
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        WHERE id_sub_hor = '$id_sub_hor'
    ";

    $resultadoMateria = mysqli_query( $db, $sqlMateria );
    $filaMateria = mysqli_fetch_assoc( $resultadoMateria );

    $id_mat = $filaMateria['id_mat'];

?>
<!-- LAYOUT TAB -->
<div class="modal-c-tabs">

    <!-- Nav tabs -->
    <ul class="nav md-pills nav-justified pills-info mt-4 mx-4" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#panel1" role="tab">
            
            Lista Grupal
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#panel2" role="tab" id="btn_evaluar">
            Calificaciones
        </a>
      </li>

    </ul>

    <!-- TAB PANELS -->
    <div class="tab-content pt-3">
      <!-- PANEL 1-->
      <div class="tab-pane fade in show active" id="panel1" role="tabpanel">

        <!--BODY-->
        <div class="modal-body mb-1">


            <!-- TABLA LISTADO ALUMNMOS -->
            <table id="myTableAlumnos" class="table table-hover table-bordered table-sm text-center" cellspacing="0" width="100%">
                <thead class="grey text-white">
                    <tr>
                        <th class="letraPequena">#</th>
                        <th class="letraPequena">Alumno</th>

                    </tr>
                </thead>


                <?php

                    $sqlAlumnos = "
                        SELECT *
                        FROM alu_hor 
                        INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                        WHERE id_sub_hor = '$id_sub_hor' AND est_alu_hor = 'Activo'
                    ";

                    $resultadoAlumnos = mysqli_query($db, $sqlAlumnos);
                    $i = 1;

                    while($filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos)){

                ?>
                    <tr>
                        <td class="letraPequena"><?php echo $i; $i++;?></td>

                
                        <td class="letraPequena"><?php echo $filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']." ".$filaAlumnos['nom_alu']; ?></td>
                        

                    </tr>

                <?php
                    } 

                ?>
            </table>
            <!-- FIN TABLA LISTADO ALUMNOS -->
            
        </div>
        <!--FIN BODY-->

      </div>
      <!--/.FIN PANEL 1-->

      <!--PANEL 2-->
      <div class="tab-pane fade" id="panel2" role="tabpanel">

        <!--BODY-->
        <div class="modal-body" id="contenedor_evaluacion_alumnos">



            
          
            



        </div>
        <!-- FIN BODY -->
      </div>
      <!--/.FIN PANEL 2-->
    </div>
    <!-- FIN TAB PANNELS -->

</div>


<!-- TITULOS A DATATABLE -->
<?php  

    $resultadoTitulo = mysqli_query($db, $sqlAlumnos);

    $filaTitulo = mysqli_fetch_assoc($resultadoTitulo);

    $materia = $filaTitulo['nom_mat'];
    $grupo = $filaTitulo['nom_gru'];


?>
<!-- FIN TITULOS A DATATABLE -->

<script>
    $(document).ready(function () {


        $('#myTableAlumnos').DataTable({
            
        
            dom: 'Bfrtlip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
            "pageLength": -1,
            
            buttons: [

            
                    'copy',
                    {
                        extend: 'excel',
                        messageTop: "<?php echo $grupo.' - '.$materia; ?>"
                    },
                    {
                        extend: 'print',
                        messageTop: "<?php echo $grupo.' - '.$materia; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo $grupo.' - '.$materia; ?>",
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
        $('#myTableAlumnos_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableAlumnos_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableAlumnos_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableAlumnos_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableAlumnos_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableAlumnos_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableAlumnos_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableAlumnos_wrapper select').addClass('mdb-select');
        $('#myTableAlumnos_wrapper .mdb-select').materialSelect();
        $('#myTableAlumnos_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableAlumnos_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);


    
    });
</script>


<script>
    // TAB2
    var id_sub_hor = <?php echo $id_sub_hor; ?>;
    
    obtener_evaluacion_alumnos( id_sub_hor );

    function obtener_evaluacion_alumnos( id_sub_hor ) {
        $.ajax({
            
            url: 'server/obtener_calificaciones_alumnos_materia.php',
            type: 'POST',
            data: { id_sub_hor },
            success: function( respuesta ) {
                $("#contenedor_evaluacion_alumnos").html( respuesta );

            }
        });
        
    }

</script>