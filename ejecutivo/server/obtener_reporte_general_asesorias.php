<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	$id_eje = $_POST['id_eje'];
    
    $semana = obtenerSemanaTrabajo($inicio);
    // echo "La semana para $fecha1 es la semana $semana1.<br>";
    // echo "La semana para $fecha2 es la semana $semana2.";  
?>

<style>
    #tabla_reporte_general_citas{
        font-size: 12px;
        color: black;
    }

    .sin-padding-margin-table {
        padding: 0;
        margin: 0;
    }
    .sin-padding-margin-table td, .sin-padding-margin-table th {
        padding: 5px;
        margin: 0;
    }
    
    
</style>

<div class="table-responsive">
    <!--  -->
    <table class="table table-bordered sin-padding-margin-table" id="tabla_reporte_general_citas" style="padding: 0; border: 3px solid black; font-weight: bold;">
        <thead style="background: blue;">
            <!-- VACIO -->
            <tr>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th></th>
            </tr>
            <!-- F VACIO -->
        </thead>

            <!-- SEMANA -->
            <tr>
                <td><?php echo $semana; ?></td>
                <td></td>

                <!-- IMPRESION DE 20 COLS -->
                <?php 
                    for( $i = 0, $max = 20; $max > $i; $i++ ){
                ?>
                        <td></td>
                <?php

                    }
                ?>
                <!-- F IMPRESION DE 20 COLS -->
            </tr>
            <!-- F SEMANA -->
            
            <!-- FECHA RIT Y CONFIANZA Y COLORES -->
            <tr>
                <td><?php echo fechaFormateadaCompacta2($inicio); ?></td>
                <td>RITMO Y CONFIANZA</td>
                <td></td>

                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

                <td></td>
                <td></td>
            </tr>
            <!-- F FECHA RIT Y CONFIANZA Y COLORES -->

            <!-- EJECUTIVO CITAS HOY, MAÑANA Y BUCLE DIAS -->
            <tr>
                <td>EJECUTIVO</td>
                <td>ASESORÍAS POR DÍA</td>
                
                <!-- RECORRIDO DE 15 DÍAS HACIA ATRÁS -->
                <?php 
                    $numeroInicio = 1;
                    $numeroFin = 17;

                    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {
                ?>
                        <td>
                            <?php echo $i; ?>
                        </td>
                <?php
                    }
                ?>

                <!-- F REORRIDO DE 15 DIAS HACIA ATRAS -->

                <td>REG. DE DÍA</td>
                <td>META SEMANA</td>
                <td>% DE EFECTIVIDAD</td>
            </tr>
            <!-- EJECUTIVO CITAS HOY, MAÑANA Y BUCLE DIAS -->


            <!-- PLANTEL -->
            <?php
                $sqlPlantelesEjecutivo = "
                    SELECT *
                    FROM planteles_ejecutivo
                    INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
                    WHERE id_eje = '$id'
                ";
        
                $totalValidacion = obtener_datos_consulta( $db, $sqlPlantelesEjecutivo )['total'];
        
                if( $totalValidacion > 0 ){
                    
                    $resultadoPlantelesEjecutivo = mysqli_query( $db, $sqlPlantelesEjecutivo );

                    $contador = 0;
                    $sqlPlanteles = '';
                    while($filaPlantelesEjecutivo = mysqli_fetch_assoc($resultadoPlantelesEjecutivo)) {
                        if ($contador > 0) {
                            $sqlPlanteles .= ' OR ';
                        }
                        $sqlPlanteles .= 'id_pla = '.$filaPlantelesEjecutivo['id_pla'];
                        
                        $contador++;
                    }

                    $sqlPlanteles = $sqlPlanteles." ) ";

                    $sql = "
                        SELECT * 
                        FROM plantel
                        WHERE ( 
                    ";
                    $sql .= $sqlPlanteles;

                    $resultadoPlanteles = mysqli_query( $db, $sql );

                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                ?>
                        <tr style="border: 3px solid blue; background-color: skyblue;">
                            <td ><?php echo $filaPlanteles['nom_pla']; ?></td>

                            <!-- DIA HOY -->
                            <td></td>
                            <!-- F DIA HOY -->

                

                            <!-- IMPRESION DE 20 COLS -->
                            <?php 
                                for( $i = 0, $max = 20; $max > $i; $i++ ){
                            ?>
                                    <td></td>
                            <?php

                                }
                            ?>
                            <!-- F IMPRESION DE 20 COLS -->

                            <!-- *** LIMITADO A 3 COLS X AHORA -->
                            
                        </tr>
                        

                        <!-- ASESOR + CONTEOS -->
                        <?php 
                            $id_pla = $filaPlanteles['id_pla'];

                            $totalCitas = array();
                            $totalCitas['hoy'] = 0;
                            $totalCitas['manana'] = 0;

                            $sqlEjecutivoPlantel = "
                                SELECT * 
                                FROM ejecutivo
                                WHERE id_pla = '$id_pla' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                ORDER BY 
                                    CASE 
                                        WHEN ran_eje = 'GC' THEN 1 
                                        WHEN ran_eje = 'GR' THEN 2 
                                        WHEN ran_eje = 'LC' THEN 3 
                                        ELSE 4 
                                    END,
                                    ran_eje,
                                    nom_eje
                            ";

                            $resultadoEjecutivoPlantel = mysqli_query( $db, $sqlEjecutivoPlantel );
                            
                            $total_citas_plantel = 0;
                            $total_citas_efectivas_plantel = 0;
                            
                            while( $filaEjecutivoPlantel = mysqli_fetch_assoc( $resultadoEjecutivoPlantel ) ){
                                $id_eje_plantel = $filaEjecutivoPlantel['id_eje'];

                                $datos_citas_hoy = array();
                                $datos_citas_manana = array();
                        ?>
                                <tr>
                                    <!-- PONDERANDO POR GC -->
                                    
                                    <td>
                                        
                                        <img src="<?php echo obtenerValidacionFotoUsuarioServer( $filaEjecutivoPlantel['fot_eje'] ); ?>" style="width: 20px; height: 25px; border-radius: 35px;"> <?php echo $filaEjecutivoPlantel['nom_eje']; ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!--  -->
                                                
                                                
                                                <?php echo obtener_rango_badge_ejecutivo($filaEjecutivoPlantel['ran_eje'], $filaEjecutivoPlantel['est_eje']); ?>
                                                <!--  -->
                                            </div>
                                        </div>
                                        
                                    </td>
                                    <!-- F PONDERANDO POR GC -->

                                    <!-- TOTAL HOY JEJE -->
                                    <td>
                                        <?php
                                            $sqlCitasHoy = "
                                                SELECT *
                                                FROM cita
                                                WHERE id_eje3 = $id_eje_plantel AND 
                                                cla_cit = 'Cita' AND 
                                                (est_cit = 'Asesoria Realizada' || est_cit = 'Registro' ) AND 
                                                DATE( cit_cit ) = DATE('$inicio')
                                            ";

                                            // echo $sqlCitasHoy;

                                            $datosTotalCitasHoy = obtener_datos_consulta( $db, $sqlCitasHoy );
                                            $totalCitasHoy = $datosTotalCitasHoy['total'];
                                            $totalCitas['hoy'] = $totalCitas['hoy'] + $totalCitasHoy;

                                            // EXTRACCION DE ids DE CITAS
                                            $resultadoCitasHoy = mysqli_query($db, $sqlCitasHoy);

                                            $contador = 0;
                                            while( $filaCitasHoy = mysqli_fetch_assoc( $resultadoCitasHoy ) ){
                                                $datos_citas_hoy[$contador] = $filaCitasHoy['id_cit']; 
                                                $contador++;
                                            }

                                            // F EXTRACCIOND DE ids DE CITAS
                                            echo $totalCitasHoy;

                                            
                                        
                                        ?>
                                    </td>
                                    <!-- F TOTAL HOY -->

                                    <!-- CONTEOS EJECUTIVO 15< -->
                                        
                                        <!-- CITAS HOY -->
                                        <?php
                                            $citas_efectivas = 0;
                                            $max_hoy = min(16, sizeof($datos_citas_hoy));
                                            $max_manana = min(16 - $max_hoy, sizeof($datos_citas_manana));

                                            for($i = 0; $i < $max_hoy; $i++){
                                                $id_cit = $datos_citas_hoy[$i];
                                                $sql = "
                                                    SELECT obtener_estatus_comercial( id_alu_ram ) AS estatus_general, id_alu, id_alu_ram, id_cit1
                                                    FROM alu_ram
                                                    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                                                    INNER JOIN cita ON cita.id_cit = alumno.id_cit1
                                                    WHERE id_cit1 = $id_cit
                                                    ORDER BY estatus_general DESC
                                                ";

                                                
                                            
                                                $resultado = mysqli_query($db, $sql);

                                                $fila = mysqli_fetch_assoc($resultado);
                                                
                                                if( 
                                                    $fila['estatus_general'] != 'Prospecto' && $fila['id_alu'] != null
                                                ){
                                                    echo '<td style="background: skyblue; color: white;">R</td>';
                                                } else {
                                                    if( $max_hoy > 0 ){
                                                        $citas_efectivas++;
                                                        echo '<td style="background: red;"></td>';
                                                    } else if( $max_hoy > 8 ) {
                                                        echo '<td style="background: yellow;"></td>';
                                                    } else {
                                                        echo '<td style="background: #76FF03;"></td>';
                                                    }
                                                }
                                            }
                                        ?>
                                        <!-- FIN CITAS HOY -->

                                        <!-- CITAS MAÑANA -->
                                        <?php
                                            for($i = 0; $i < $max_manana; $i++){
                                                echo '<td style="background: purple;"></td>';
                                            }
                                        ?>
                                        <!-- FIN CITAS MAÑANA -->

                                        
                                        <!-- DIFERENCIA -->
                                        <?php 
                                            $total_citas = 0;
                                            $total_citas = sizeof($datos_citas_hoy) + sizeof($datos_citas_manana);
                                            
                                            if( $total_citas >= 17 ){
                                                $diferencia = 0;
                                            } else {
                                                $diferencia = $total_citas - 17;
                                                $diferencia = $diferencia*-1;
                                            }
                                            for( $i = 0; $i < $diferencia; $i++ ){
                                        ?>
                                                <td></td>
                                        <?php
                                            }
                                            
                                        ?>
                                        <!-- F DIFERENCIA -->

                                        <!-- TOTAL CITAS  -->
                                        <td>
                                            <?php echo $total_citas; ?>
                                        </td>
                                        <!-- F TOTAL CITAS -->

                                        <!-- CITAS EFECTIVAS -->
                                        <td>
                                            0
                                        </td>
                                        <!-- F CITAS EFECTIVAS -->

                                        <!-- % DE EFECTIVIDAD -->
                                        <td>
                                            <?php
                                                echo ( sizeof($datos_citas_hoy) == 0 || sizeof($datos_citas_manana) == 0 )? '0%': round( ( $citas_efectivas/$total_citas )*100, 2 )." %"; 
                                            ?>
                                        </td>
                                        <!-- F % DE EFECTIVIDAD -->

                                    
                                    <!-- F CONTEOS EJCUTIVO 15< -->
                                    
                                </tr>
                        <?php
                                // 
                                // 
                                // 
                                $total_citas_plantel = $total_citas_plantel + $total_citas;
                                $total_citas_efectivas_plantel = $total_citas_efectivas_plantel + $citas_efectivas;
                            }
                        ?>
                        <!-- TOTALES CDE -->
                        <tr style="border: 3px solid blue; background-color: #0070CB;">
                            <td>TOTAL</td>
                            <td><?php echo $totalCitas['hoy']; ?></td>
                            
                            <!-- IMPRESION DE 17 COLS -->
                            <?php 
                                for( $i = 0, $max = 17; $max > $i; $i++ ){
                            ?>
                                    <td></td>
                            <?php

                                }
                            ?>
                            <!-- F IMPRESION DE 17 COLS -->
                            
                            <td><?php echo $total_citas_plantel; ?></td>
                            <td><?php echo $total_citas_efectivas_plantel; ?></td>
                            <td>---</td>

                            <?php 
                            /** 
                             * <!-- <td><?php echo ( $total_citas_plantel == 0 )? '0%': round( ( $total_citas_efectivas_plantel/$total_citas_plantel )*100, 2 )." %"; ?></td>
                            * */ 
                            ?>
                            
                      
                            
                            
                        </tr>
                        <!-- F TOTALES CDE -->
                        <!-- F ASESOR + CONTEOS -->

                <?php
                    }
                    // $resultadoCdes
            ?>

    
                    
            <?php
                }
            ?>

            <!-- F PLANTEL -->
            

        
    </table>
    <!--  -->
</div>

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
    $('#tabla_reporte_general_citas').DataTable({
        paging: false, // Desactiva la paginación
        searching: true, // Activa el buscador
        ordering: false, // Desactiva la ordenación
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE CITAS',
                className: 'btn-sm btn-success'
            },
            {
                extend: 'pdfHtml5',
                title: 'REPORTE CITAS',
                className: 'btn-sm btn-danger',
                orientation: 'landscape',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                }
            }
        ],
        language: {
            search: 'Buscar' // Cambia el texto del buscador
        },
        info: false // Esto desactiva la información del pie de la tabla

    });
</script>