<?php 
    //ARCHIVO VIA AJAX PARA OBTENER TODOS LOS ALUMNOS DE FOROS
    //examen.php/
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_exa_cop = $_POST['id_exa_cop'];

    $sql = "
        SELECT *
        FROM examen_copia
        WHERE id_exa_cop = '$id_exa_cop'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $id_sub_hor = $fila['id_sub_hor4'];

?>
<div class="alert alert-warning alert-dismissible fade show font-weight-normal letraMediana" role="alert">
   
    <strong>NOTA:</strong> 
    <!-- <p class="letraMediana grey-text "> -->
        Aquí el guardado es automático. No hay botón de "guardar". Conforme escribes una retroalimentación o
        alguna calificación los cambios de guardarán al instante. Gracias.
        
        Consulta las calificaciones globales de actividades <a href="actividades_materia.php?id_sub_hor=<?php echo $id_sub_hor; ?>" class="text-info btn-link">aquí</a>.

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<span class="letraPequena grey-text">**Más información:</span>
<br>
<a class="toggle-vis2 btn-link text-info" data-column="2">Teléfono</a>
<a class="toggle-vis2 btn-link text-info" data-column="3">CDE</a>


<div class="table-responsive">
    <table class="table table-hover table-sm" id="myTable">
        <thead class="grey white-text">
            <tr>
                <th class="letraGrande font-weight-normal">#</th>
                <th class="letraGrande font-weight-normal">Nombre</th>
                <th class="letraGrande font-weight-normal">Teléfono</th>
                <th class="letraGrande font-weight-normal">CDE</th>


                <th class="letraGrande font-weight-normal">Retroalimentación</th>
                <th class="letraGrande font-weight-normal">Puntos</th>
                <th class="letraGrande font-weight-normal">Inicio</th>
                <th class="letraGrande font-weight-normal">Fin</th>
            </tr>
        </thead>

        <tbody>
            
            <?php  
            $sqlAlumnos = "
                SELECT *, obtener_estatus_general(id_alu_ram, fin_gen, est1_alu_ram ) AS estatus_general
                FROM examen_copia
                INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
                INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
                INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
                INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
                INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
                INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                WHERE id_exa_cop = '$id_exa_cop' AND est_alu_hor = 'Activo'
                ORDER BY app_alu ASC

            ";

            $resultadoAlumnos = mysqli_query($db, $sqlAlumnos);
            $i = 1;
            while ($filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos)) {
                ?>
                <tr>
                    <td class="letraGrande font-weight-normal"><?php echo $i; $i++;?></td>
        

                    <td class="letraGrande font-weight-normal">
                        <?php echo $filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']." ".$filaAlumnos['nom_alu']; ?>
                        <br>
                        <?php  
                            echo obtenerBadgeEstatusEjecutivoPosicion( $filaAlumnos['estatus_general'] );
                        ?>        

                    </td>


                    <td class="letraGrande font-weight-normal">
                        <?php 
                            echo $filaAlumnos['tel_alu'];
                        ?>
                        

                    </td>



                    <td class="letraGrande font-weight-normal">
                        <?php 
                            echo $filaAlumnos['nom_pla'];
                        ?>
                        
                    </td>

                    <?php 
                        $id_alu_ram = $filaAlumnos['id_alu_ram'];
                        $sqlConsultaCalificacion = "
                            SELECT *
                            FROM cal_act
                            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
                            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
                            WHERE id_alu_ram4 = '$id_alu_ram' AND id_exa_cop2 = '$id_exa_cop'

                        ";
                        //echo $sqlConsultaCalificacion;


                        $resultadoConsultaCalificacion = mysqli_query($db, $sqlConsultaCalificacion);
                        $filaConsultaCalificacion = mysqli_fetch_assoc($resultadoConsultaCalificacion);
                    ?>

                    <td class="letraGrande font-weight-normal">
                        <?php

                            if ($filaConsultaCalificacion['ret_cal_act'] == NULL) {
                        ?>

                                <div class="form-group shadow-textarea">
                                    <textarea class="form-control z-depth-1 letraGrande font-weight-normal alumnosRetroalimentacion" id="exampleFormControlTextarea6" rows="3" placeholder="Retroalimenta a <?php echo $filaAlumnos['nom_alu']; ?>..." id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>"></textarea>
                                </div>


                         
                        <?php
                            }else{

                        ?>
                                <div class="form-group shadow-textarea">
                                    <textarea class="form-control z-depth-1 letraGrande font-weight-normal alumnosRetroalimentacion" id="exampleFormControlTextarea6" rows="3" placeholder="Deja una observación para <?php echo $filaAlumnos['nom_alu']; ?>" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>"><?php echo $filaConsultaCalificacion['ret_cal_act']; ?></textarea>
                                </div>
                        <?php
                            }

                        ?>
                    </td>


                    <td class="letraGrande font-weight-normal">


                        <?php 
                            //echo $filaConsultaCalificacion['pun_cal_act'];
                            if ($filaConsultaCalificacion['pun_cal_act'] == NULL) {
                        ?>
                                <input type="number" class="form-control letraGrande font-weight-normal alumnosCalificados" value="0" min="0" step=".1" max="<?php echo $filaAlumnos['pun_exa']; ?>" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>">
                        <?php
                            }else{

                        ?>
                                <input type="number" class="form-control letraGrande font-weight-normal alumnosCalificados" value="<?php echo $filaConsultaCalificacion['pun_cal_act']; ?>" min="0" step=".1" max="<?php echo $filaAlumnos['pun_exa']; ?>" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>">
                        <?php
                            }

                        ?>
                    </td>

                    <td class="letraGrande font-weight-normal">

                        <input type="date" class="form-control letraGrande font-weight-normal actividadInicio" value="<?php echo $filaConsultaCalificacion['ini_cal_act']; ?>" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>" id_cal_act="<?php echo $filaConsultaCalificacion['id_cal_act']; ?>">

                    </td>


                    <td class="letraGrande font-weight-normal">

                        <input type="date" class="form-control letraGrande font-weight-normal actividadFin" value="<?php echo $filaConsultaCalificacion['fin_cal_act']; ?>" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>" id_cal_act="<?php echo $filaConsultaCalificacion['id_cal_act']; ?>">

                    </td>

                    
                </tr>

                <?php
            }

            ?>



        </tbody>
    </table>
</div>


<script>

        $('#myTable').DataTable({
            
        
            dom: 'frtp',
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


        // OCULTAR/MOSTRAR COLUMNAS
        var table = $('#myTable').DataTable();
        var column = table.column( $('a.toggle-vis2').eq(0).attr('data-column') );
        column.visible( ! column.visible() );

        var column = table.column( $('a.toggle-vis2').eq(1).attr('data-column') );
        column.visible( ! column.visible() );



        $('a.toggle-vis2').on( 'click', function (e) {
            e.preventDefault();
     
            // Get the column API object
            var column = table.column( $(this).attr('data-column') );
     
            // Toggle the visibility
            column.visible( ! column.visible() );
        });
        // FIN OCULTAR/MOSTRAR COLUMNAS

        // INGRESO DE DATOS DE PROFESOR/ALUMNO
    // PUNTAJE
    $('.alumnosCalificados').on( 'change', function () {
        //event.preventDefault();

        var id_sub_hor = <?php echo $id_sub_hor; ?>;

        console.log("click");
        var puntos = $(this).val();
        var id_alu_ram = $(this).attr("id_alu_ram");
        console.log(puntos+" - "+id_alu_ram);

        $.ajax({
                    
            url: 'server/editar_calificacion_examen.php?id_exa_cop=<?php echo $id_exa_cop; ?>',
            type: 'POST',
            data: {puntos, id_alu_ram},
            success: function(respuesta){
                console.log(respuesta);

                if (respuesta == 'Exito') {
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


        console.log(retroalimentacion+" - "+id_alu_ram);

        $.ajax({
                    
            url: 'server/editar_calificacion_examen.php?id_exa_cop=<?php echo $id_exa_cop; ?>',
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
    // FIN INGRESO DE DATOS DE PROFESOR/ALUMNO

    // // EDITAR FECHAS ACTIVIDAD INICIO
    $('.actividadInicio').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var tipo = 'Inicio';
        var fecha = $(this).val();
        var id_cal_act = $(this).attr('id_cal_act');
        console.log( id_cal_act );
        $.ajax({
         
            url: 'server/editar_fechas_actividad_alumno.php',
            type: 'POST',
            data: { tipo, fecha, id_cal_act },
            success: function( respuesta ){
                
                if ( respuesta == 'Exito' ) {
                
                    toastr.success('Guardado exitosamente');
                
                } else {

                    console.log( respuesta );
                
                }
            }
        
        });
        
    });


    // // EDITAR FECHAS ACTIVIDAD FIN
    $('.actividadFin').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var tipo = 'Fin';
        var fecha = $(this).val();
        var id_cal_act = $(this).attr('id_cal_act');

        $.ajax({
         
            url: 'server/editar_fechas_actividad_alumno.php',
            type: 'POST',
            data: { tipo, fecha, id_cal_act },
            success: function( respuesta ){
                
                if ( respuesta == 'Exito' ) {
                
                    toastr.success('Guardado exitosamente');
                
                } else {

                    console.log( respuesta );
                
                }
            }
        
        });
        
    });
    
</script>