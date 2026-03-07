<?php  
    //ARCHIVO VIA AJAX PARA OBTENER TABLA ACTIVIDADES MATERIA
    //actividades_materia.php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_sub_hor = $_POST['id_sub_hor'];

    $sqlGrupos = "
        SELECT *
        FROM sub_hor
        WHERE id_sub_hor = '$id_sub_hor'
    ";

    $resultadoGrupos = mysqli_query( $db, $sqlGrupos );

    $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos );

    $id_gru = $filaGrupos['id_gru1'];


?>

<!-- BUBLE SUB_HORS -->

<?php  
    echo obtenerConsultaHorarioServer( $id_gru );
?>

<hr>

<?php  
    
    $sqlMaterias = "
        SELECT *
        FROM sub_hor
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        WHERE id_gru1 = '$id_gru'
    ";

    $resultadoMaterias = mysqli_query( $db, $sqlMaterias );

    while( $filaMaterias = mysqli_fetch_assoc( $resultadoMaterias ) ){

        echo $filaMaterias['nom_mat'].' - '.$filaMaterias['nom_pro']."<br>";
        $id_sub_hor = $filaMaterias['id_sub_hor'];
        $grupo = $filaMaterias['nom_gru'];
        $materia = $filaMaterias['nom_mat'];
?>


        <!-- TABLA LISTADO ALUMNMOS EVALUACION -->
        <table id="myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>" class="table table-hover table-bordered table-sm text-center table-striped" cellspacing="0" width="100%">
            <thead class="grey text-white">
                <tr>
                    <th class="letraPequena font-weight-normal grey">#</th>
                    <th class="letraPequena grey" >
                        <div style="width: 100px">
                            Alumno
                        </div>
                    </th>

                    
                    <?php 
                        $sqlActividades = "

                            SELECT nom_sub_hor AS clave, id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, tip_for AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_for_cop AS inicio, fin_for_cop AS fin
                            FROM foro_copia
                            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
                            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
                            INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
                            WHERE id_sub_hor2 = '$id_sub_hor'
                            UNION
                            SELECT nom_sub_hor AS clave, id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, tip_ent AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_ent_cop AS inicio, fin_ent_cop AS fin
                            FROM entregable_copia
                            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
                            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
                            INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
                            WHERE id_sub_hor3 = '$id_sub_hor'
                            UNION
                            SELECT nom_sub_hor AS clave, id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, tip_exa AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_exa_cop AS inicio, fin_exa_cop AS fin
                            FROM examen_copia
                            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
                            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
                            INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
                            WHERE id_sub_hor4 = '$id_sub_hor'
                            ORDER BY materia ASC, id_blo ASC, tipo DESC
                        ";

                        //echo $sqlParciales;
                        $resultadoActividades = mysqli_query( $db, $sqlActividades );

                        $contadorActividad = 1;

                        while( $filaActividades = mysqli_fetch_assoc( $resultadoActividades ) ) {
                    ?>
                            <th class="letraPequena font-weight-normal clasePadreTabla" title="<?php echo $filaActividades['actividad'].' del '.fechaFormateadaCompacta( $filaActividades['inicio'] ).' al '.fechaFormateadaCompacta( $filaActividades['fin'] ); ?> " >
                                <div style="width: 150px; height: 50px;">
                                    <?php
                                        echo $contadorActividad." - "; $contadorActividad++;
                                        if ( strlen( $filaActividades['actividad'] ) > 70 ) {
                                        
                                            echo substr( $filaActividades['actividad'], 0, 70 )."...";  
                                        
                                        } else {

                                            echo $filaActividades['actividad'];
                                        
                                        }

                                        
                                    ?>

                                </div>
                                

                                <div class="claseHijoTabla">
                                    <?php  
                                        echo fechaFormateadaCompacta( $filaActividades['inicio'] ).' al '.fechaFormateadaCompacta( $filaActividades['fin'] );
                                    ?>
                                </div>
                            </th>

                    <?php
                        }
                    ?>

                    <th class="letraPequena font-weight-normal" >
                        <div style="width: 50px">
                            Puntos totales
                        </div>
                    </th>

                    <th class="letraPequena font-weight-normal white-text grey" >
                        <div style="width: 50px">
                            Puntos obtenidos
                        </div>
                    </th>


                    <th class="letraPequena font-weight-normal white-text grey" >
                        <div style="width: 50px">
                            Promedio final
                        </div>
                    </th>


                    <th class="letraPequena grey" >
                        <div style="height: 30px">
                            Estatus
                        </div>
                    </th>
                        
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
                    ORDER BY app_alu ASC
                ";

                $resultadoAlumnos = mysqli_query($db, $sqlAlumnos);
                $i = 1;
                $fila = 0;
                $columna = 0;

                while($filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos)){

                    $id_alu_ram = $filaAlumnos['id_alu_ram'];
                    $nombreAlumno = $filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']." ".$filaAlumnos['nom_alu'];
                    
                    $columna = 2;
                    

            ?>
                <tr class="cantidadFilas<?php echo $filaMaterias['id_sub_hor']; ?>">
                    <td class="letraMediana font-weight-normal grey white-text"><?php echo $i; $i++;?></td>

            
                    <td class="letraMediana font-weight-normal white-text grey">
                        <div style="height: 30px">
                            <?php echo $nombreAlumno; ?>
                        </div>
                        
                            
                    </td>



                    

                    <?php 
                        $sqlActividades2 = "

                            SELECT nom_sub_hor AS clave, id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, tip_for AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_for_cop AS inicio_copia, fin_for_cop AS fin_copia
                            FROM foro_copia
                            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
                            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
                            INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
                            WHERE id_sub_hor2 = '$id_sub_hor'
                            UNION
                            SELECT nom_sub_hor AS clave, id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, tip_ent AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_ent_cop AS inicio, fin_ent_cop AS fin
                            FROM entregable_copia
                            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
                            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
                            INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
                            WHERE id_sub_hor3 = '$id_sub_hor'
                            UNION
                            SELECT nom_sub_hor AS clave, id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, tip_exa AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_exa_cop AS inicio, fin_exa_cop AS fin
                            FROM examen_copia
                            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
                            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
                            INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
                            WHERE id_sub_hor4 = '$id_sub_hor'
                            ORDER BY materia ASC, id_blo ASC, tipo DESC
                        ";

                        //echo $sqlParciales;
                        $resultadoActividades2 = mysqli_query( $db, $sqlActividades2 );

             

                        $totalCalificacion = 0;

                        while( $filaActividades2 = mysqli_fetch_assoc( $resultadoActividades2 ) ) {
                            $tipo = $filaActividades2['tipo'];
                            $id = $filaActividades2['id'];

                    ?>

                            

                    <?php


                            if ( $tipo == 'Foro' ) {
                                
                                $sqlCalificacionActividad = "
                                    SELECT *
                                    FROM cal_act
                                    WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop2 = '$id'
                                ";

                                $resultadoCalificacionActividad = mysqli_query( $db, $sqlCalificacionActividad );

                                $filaCalificacionActividad = mysqli_fetch_assoc( $resultadoCalificacionActividad );

                                $totalCalificacion = $totalCalificacion + $filaCalificacionActividad['pun_cal_act'];
                    ?>
                                <td class="letraGrande font-weight-normal waves-effect calificacionAlumno<?php echo $filaMaterias['id_sub_hor']; ?> 
                                    <?php echo ( $filaCalificacionActividad['pun_cal_act'] == '' ) ? 'text-danger' : ''; ?>" 
                                    id_alu_ram="<?php echo $id_alu_ram; ?>" 
                                    tipo="<?php echo $tipo; ?>" 
                                    identificador="<?php echo $id; ?>" 
                                    pun_cal_act="<?php if ( $filaCalificacionActividad['pun_cal_act'] == '' ) { echo '0';
                                        } else {
                                            echo round( $filaCalificacionActividad['pun_cal_act'], 2 );
                                        } ?>"
                                    nombreAlumno = "<?php echo $nombreAlumno; ?>"
                                    actividad = "<?php echo $filaActividades2['actividad']; ?>"
                                    columna ="<?php echo $columna; ?>"
                                    fila="<?php echo $fila; ?>"
                                    puntaje="<?php echo $filaActividades2['puntaje']; ?>"
                                >

                                    <?php  
                                        if ( $filaCalificacionActividad['pun_cal_act'] == '' ) {
                                    ?>
                                            
                                                <?php echo "Sin presentar"; ?>
                                            

                                    <?php   
                                        } else {
                                    ?>
                                            
                                                <?php echo round( $filaCalificacionActividad['pun_cal_act'], 2 ); ?>
                                            


                                    <?php
                                        }
                                    ?>
                                </td>
                                
                            <?php


                            } else if ( $tipo == 'Entregable' ) {

                                $sqlCalificacionActividad = "
                                    SELECT *
                                    FROM cal_act
                                    WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop2 = '$id'
                                ";

                                $resultadoCalificacionActividad = mysqli_query( $db, $sqlCalificacionActividad );

                                $filaCalificacionActividad = mysqli_fetch_assoc( $resultadoCalificacionActividad );

                                $totalCalificacion = $totalCalificacion + $filaCalificacionActividad['pun_cal_act'];
                            ?>
                                
                                <td class="letraGrande font-weight-normal waves-effect calificacionAlumno<?php echo $filaMaterias['id_sub_hor']; ?> 
                                    <?php echo ( $filaCalificacionActividad['pun_cal_act'] == '' ) ? 'text-danger' : ''; ?>" 
                                    id_alu_ram="<?php echo $id_alu_ram; ?>" 
                                    tipo="<?php echo $tipo; ?>" 
                                    identificador="<?php echo $id; ?>" 
                                    pun_cal_act="<?php if ( $filaCalificacionActividad['pun_cal_act'] == '' ) { echo '0';
                                        } else {
                                            echo round( $filaCalificacionActividad['pun_cal_act'], 2 );
                                        } ?>"
                                    nombreAlumno = "<?php echo $nombreAlumno; ?>"
                                    actividad = "<?php echo $filaActividades2['actividad']; ?>"
                                    columna ="<?php echo $columna; ?>"
                                    fila="<?php echo $fila; ?>"
                                    puntaje="<?php echo $filaActividades2['puntaje']; ?>"
                                >

                                    <?php  
                                        if ( $filaCalificacionActividad['pun_cal_act'] == '' ) {
                                    ?>
                                            
                                                <?php echo "Sin presentar"; ?>
                                            

                                    <?php   
                                        } else {
                                    ?>
                                            
                                                <?php echo round( $filaCalificacionActividad['pun_cal_act'], 2 ); ?>
                                            


                                    <?php
                                        }
                                    ?>
                                    
                                </td>
                                
                            <?php
                                
                            } else if ( $tipo == 'Examen' ) {

                                $sqlCalificacionActividad = "
                                    SELECT *
                                    FROM cal_act
                                    WHERE id_alu_ram4 = '$id_alu_ram' AND id_exa_cop2 = '$id'
                                ";

                                $resultadoCalificacionActividad = mysqli_query( $db, $sqlCalificacionActividad );

                                $filaCalificacionActividad = mysqli_fetch_assoc( $resultadoCalificacionActividad );

                                $totalCalificacion = $totalCalificacion + $filaCalificacionActividad['pun_cal_act'];
                            ?>

                                <td class="letraGrande font-weight-normal waves-effect calificacionAlumno<?php echo $filaMaterias['id_sub_hor']; ?> 
                                    <?php echo ( $filaCalificacionActividad['pun_cal_act'] == '' ) ? 'text-danger' : ''; ?>" 
                                    id_alu_ram="<?php echo $id_alu_ram; ?>" 
                                    tipo="<?php echo $tipo; ?>" 
                                    identificador="<?php echo $id; ?>" 
                                    pun_cal_act="<?php if ( $filaCalificacionActividad['pun_cal_act'] == '' ) { echo '0';
                                        } else {
                                            echo round( $filaCalificacionActividad['pun_cal_act'], 2 );
                                        } ?>"
                                    nombreAlumno = "<?php echo $nombreAlumno; ?>"
                                    actividad = "<?php echo $filaActividades2['actividad']; ?>"
                                    columna ="<?php echo $columna; ?>"
                                    fila="<?php echo $fila; ?>"
                                    puntaje="<?php echo $filaActividades2['puntaje']; ?>"
                                >
                                    <?php  
                                        if ( $filaCalificacionActividad['pun_cal_act'] == '' ) {
                                    ?>

                                                <?php echo "Sin presentar"; ?>
                                    

                                    <?php   
                                        } else {
                                    ?>
                                    
                                                <?php echo round( $filaCalificacionActividad['pun_cal_act'], 2 ); ?>
                                    


                                    <?php
                                        }
                                    ?>
                                    
                                </td>
                                
                            <?php
                                
                            }

                            ?>

                        <!-- </td> -->

                    <?php
                            $columna++;

                        }

                    ?>

                    <td class="letraMediana font-weight-normal">
                        <?php 
                            $totalPuntos = obtenerTotalPuntajeGrupoServer( $id_sub_hor );
                            echo round( $totalPuntos, 2 );
                        ?>
                    </td>

                    <td class="letraMediana font-weight-normal white-text grey">
                    </td>



                    <td class="letraMediana font-weight-normal  white-text grey">
                    </td>


                    <td class="letraMediana font-weight-normal white-text grey">
                        <div style="height: 30px">
                            <?php echo obtenerColorDesempennioAlumnoServer( $id_alu_ram ); ?>    
                        </div>
                        
                    </td>
               

                                            

                </tr>

            <?php
                    $fila++;
                }

            ?>
        </table>
        <!-- FIN TABLA LISTADO ALUMNOS EVALUACION-->



        <hr>


        <script>
            // $(document).ready(function () {
                

                const table<?php echo $filaMaterias['id_sub_hor']; ?> = $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable({
                    
                    "pageLength": -1,

                    scrollX: true,
                    fixedColumns: {
                      leftColumns: 2,
                      rightColumns: 3
                    },

                    dom: 'Brt',
         
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
                            }


                    ],

                });

                var botones = $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
                
                obtenerSumatoriaCeldasFilas();

                function obtenerSumatoriaCeldasFilas(){
                    var totalColumnas = <?php echo $columna; ?>;
                    var totalFilas = <?php echo $fila; ?>;
                    var puntosObtenidos;
                    var puntosTotales;
                    var promedioFila;


                    // alert( totalColumnas );


                    for( var fila = 0; fila < totalFilas; fila++ ){
                        puntosObtenidos = 0;
                        puntosTotales = parseFloat( $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, totalColumnas ).data() ); 
                        
                        for( var columna = 2; columna < totalColumnas; columna++ ){
                                
                            if (  !isNaN( $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data() )  ) {
                                
                                valorCelda = parseFloat( $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data() );
                                puntosObtenidos = puntosObtenidos + valorCelda;
                            }
                                
                        }

                        // table.cell( fila, 28 ).data( totalFila ).invalidate().draw();
                        $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, totalColumnas+1 ).data( puntosObtenidos.toFixed(2) ).invalidate().draw();
                        // console.log( puntosTotales );

                        promedioFila = ( ( puntosObtenidos / puntosTotales ) * 10 ).toFixed(2);
                        $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, totalColumnas+2 ).data( promedioFila ).invalidate().draw();


                    }
                }


                function obtenerSumatoriaFila( fila ){

                    var totalColumnas = <?php echo $columna; ?>;
                    var totalFilas = <?php echo $fila; ?>;
                    var puntosObtenidos;
                    var puntosTotales;
                    var promedioFila;

                    puntosObtenidos = 0;
                    puntosTotales = parseFloat( $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, totalColumnas ).data() ); 
                    
                    for( var columna = 2; columna < totalColumnas; columna++ ){
                            
                        if (  !isNaN( $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data() )  ) {
                            
                            valorCelda = parseFloat( $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data() );
                            puntosObtenidos = puntosObtenidos + valorCelda;
                        }
                            
                    }

                    // table.cell( fila, 28 ).data( totalFila ).invalidate().draw();
                    $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, totalColumnas+1 ).data( puntosObtenidos.toFixed(2) ).invalidate().draw();
                    // console.log( puntosTotales );

                    promedioFila = ( ( puntosObtenidos / puntosTotales ) * 10 ).toFixed(2);
                    $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, totalColumnas+2 ).data( promedioFila ).invalidate().draw();

                }


                // ACCIONES PUNTAJES
                $( '.calificacionAlumno<?php echo $filaMaterias['id_sub_hor']; ?>' ).on('click', function( event ) {
                    event.preventDefault();
                    /* Act on the event */
                    var elemento = $( this );

                    elemento.addClass('grey lighten-2');
                    var pun_cal_act = $( this ).attr( 'pun_cal_act' );
                    var id_alu_ram = $( this ).attr( 'id_alu_ram' );
                    var tipo = $( this ).attr( 'tipo' );
                    var identificador = $( this ).attr( 'identificador' );

                    var nombreAlumno = $( this ).attr( 'nombreAlumno' );
                    var actividad = $( this ).attr( 'actividad' );
                    var puntaje = $( this ).attr( 'puntaje' );

                    const columna = $( this ).attr( 'columna' );
                    const fila = $( this ).attr( 'fila' );

                    $( '#modal_calificacion_actividad<?php echo $filaMaterias['id_sub_hor']; ?>' ).modal( 'show' );

                    setTimeout( function(){
                        $( '#modal_calificacion_actividad<?php echo $filaMaterias['id_sub_hor']; ?>_titulo' ).html( actividad );
                        $( '#pun_cal_act' ).val( pun_cal_act ).focus().select().removeAttr('max').attr( 'max', puntaje );
                        $( '#label_pun_cal_act' ).html( 'Calificación para '+nombreAlumno );
                    }, 500 );

                    if ( tipo == 'Foro' ) {
                        $( '#contenedor_revisar_editar_actividad' ).html(
                            '<strong>Nota</strong> <br>Antes de calificar al alumno, puedes revisar la actividad para conocer su desempeño haciendo click <a href="#" class="btn-link revisarActividadForo" id_for_cop="'+identificador+'" nom_for="'+actividad+'">aquí</a>.'    
                        );
                        


                    } else if ( tipo == 'Entregable' ) {

                        $( '#contenedor_revisar_editar_actividad' ).html(
                            '<strong>Nota</strong> <br>Antes de calificar al alumno, puedes revisar la actividad para conocer su desempeño haciendo click <a href="#" class="btn-link revisarActividadEntregable" id_ent_cop="'+identificador+'" nom_ent="'+actividad+'">aquí</a>.'    
                        );


                    } else if ( tipo == 'Examen' ) {


                        $( '#contenedor_revisar_editar_actividad' ).html(
                            '<strong>Nota</strong> <br>Antes de calificar al alumno, puedes revisar la actividad para conocer su desempeño haciendo click <a href="#" class="btn-link revisarActividadExamen" id_exa_cop="'+identificador+'" nom_exa="'+actividad+'">aquí</a>.'    
                        );

                        

                    }


                    revisarActividades();


                    $( '#formularioActividad' ).off('submit');
                    
                    $( '#formularioActividad' ).on('submit', function(event) {
                        event.preventDefault();
                        /* Act on the event */



                        var puntos = $( '#pun_cal_act' ).val();

                        var formularioActividad = new FormData( $('#formularioActividad')[0] );

                        formularioActividad.append('puntos', puntos ); 
                        formularioActividad.append('id_alu_ram', id_alu_ram ); 

                        if ( tipo == 'Foro' ) {

                            var id_for_cop = identificador;

                            $.ajax({
                                url: 'server/editar_calificacion_foro.php?id_for_cop='+id_for_cop,
                                type: 'POST',
                                data: formularioActividad,
                                processData: false,
                                contentType: false, 
                                cache: false,
                                success: function( respuesta ){

                                    console.log( respuesta );
                                    $( '#modal_calificacion_actividad<?php echo $filaMaterias['id_sub_hor']; ?>' ).modal( 'hide' );
                                    $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data( puntos ).invalidate().draw();
                                    generarAlerta( 'Cambios guardados' );
                                    elemento.removeAttr('pun_cal_act').attr( 'pun_cal_act', puntos ).removeClass('text-danger');
                                    // alert( 'fila:'+fila+'-columna:'+columna );
                                    obtenerSumatoriaFila( fila );

                                }
                            });



                        } else if ( tipo == 'Entregable' ) {

                            var id_ent_cop = identificador;

                            $.ajax({
                                url: 'server/editar_calificacion_entregable.php?id_ent_cop='+id_ent_cop,
                                type: 'POST',
                                data: formularioActividad,
                                processData: false,
                                contentType: false, 
                                cache: false,
                                success: function( respuesta ){

                                    console.log( respuesta );
                                    $( '#modal_calificacion_actividad<?php echo $filaMaterias['id_sub_hor']; ?>' ).modal( 'hide' );
                                    $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data( puntos ).invalidate().draw();
                                    generarAlerta( 'Cambios guardados' );
                                    elemento.removeAttr('pun_cal_act').attr( 'pun_cal_act', puntos ).removeClass('text-danger');
                                    obtenerSumatoriaFila( fila );
                                    // alert( 'fila:'+fila+'-columna:'+columna );


                                
                                }
                            });

                        } else if ( tipo == 'Examen' ) {

                            var id_exa_cop = identificador;

                            $.ajax({
                                url: 'server/editar_calificacion_examen.php?id_exa_cop='+id_exa_cop,
                                type: 'POST',
                                data: formularioActividad,
                                processData: false,
                                contentType: false, 
                                cache: false,
                                success: function( respuesta ){

                                    console.log( respuesta );
                                    $( '#modal_calificacion_actividad<?php echo $filaMaterias['id_sub_hor']; ?>' ).modal( 'hide' );
                                    $('#myTableAlumnosActividades<?php echo $filaMaterias['id_sub_hor']; ?>').DataTable().cell( fila, columna ).data( puntos ).invalidate().draw();
                                    generarAlerta( 'Cambios guardados' );
                                    elemento.removeAttr('pun_cal_act').attr( 'pun_cal_act', puntos ).removeClass('text-danger');
                                    // alert( 'fila:'+fila+'-columna:'+columna );
                                    obtenerSumatoriaFila( fila );
                                }
                            });

                        }
                        
                        
                        
                    });


                });
                // FIN ACCIONES PUNTAJES

                // alert( table.fnSettings().aoColumns.length );
            // });
        </script>



        <script>


            function revisarActividades(){


                // REVISION DE ACTIVIDADES
                $( '.revisarActividadForo' ).on('click', function(event) {
                    event.preventDefault();
                    /* Act on the event */

                    var id_for_cop = $( this ).attr( 'id_for_cop' );
                    var nom_for = $( this ).attr( 'nom_for' );

                    $.ajax({
                        url: 'server/obtener_controlador_foro.php',
                        type: 'POST',
                        data: { id_for_cop },
                        success: function ( respuesta ) {
                            // console.log( respuesta );
                        
                            $( '#modal_obtener_actividad' ).modal( 'show' );
                            $( '#contenedor_modal_obtener_actividad' ).html( respuesta );
                            $( '#titulo_modal_obtener_actividad' ).html( nom_for );

                        }

                    });
                });


                $( '.revisarActividadEntregable' ).on('click', function(event) {
                    event.preventDefault();
                    /* Act on the event */

                    var id_ent_cop = $( this ).attr( 'id_ent_cop' );
                    var nom_ent = $( this ).attr( 'nom_ent' );

                    $.ajax({
                        url: 'server/obtener_controlador_entregable.php',
                        type: 'POST',
                        data: { id_ent_cop },
                        success: function ( respuesta ) {
                            // console.log( respuesta );
                        
                            $( '#modal_obtener_actividad' ).modal( 'show' );
                            $( '#contenedor_modal_obtener_actividad' ).html( respuesta );
                            $( '#titulo_modal_obtener_actividad' ).html( nom_ent );

                        }

                    });
                });



                $( '.revisarActividadExamen' ).on('click', function(event) {
                    event.preventDefault();
                    /* Act on the event */

                    var id_exa_cop = $( this ).attr( 'id_exa_cop' );
                    var nom_exa = $( this ).attr( 'nom_exa' );

                    $.ajax({
                        url: 'server/obtener_controlador_examen.php',
                        type: 'POST',
                        data: { id_exa_cop },
                        success: function ( respuesta ) {
                            // console.log( respuesta );
                        
                            $( '#modal_obtener_actividad' ).modal( 'show' );
                            $( '#contenedor_modal_obtener_actividad' ).html( respuesta );
                            $( '#titulo_modal_obtener_actividad' ).html( nom_exa );

                        }

                    });
                });
            }

        </script>
<?php

    }



?>
<!-- FIN BUCLE SUB_HORS -->


