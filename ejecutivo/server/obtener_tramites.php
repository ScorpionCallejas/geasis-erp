<?php  
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	$id_pla = $_POST['id_pla'];
    $estatus = $_POST['estatus'];

    // echo $estatus;
	// echo $id_pla;
    function obtenerSemaforoTramites($porcentaje) {
        if ($porcentaje >= 0 && $porcentaje <= 90) {
            return 'style="background-color: #FFC7CE;"'; // Rojo tenue
        } elseif ($porcentaje > 90 && $porcentaje < 100) {
            return 'style="background-color: #FFEB9C;"'; // Amarillo tenue
        } elseif ($porcentaje == 100) {
            return 'style="background-color: #C6EFCE;"'; // Verde tenue
        } else {
            return '';
        }
    }

    function obtenerAzul($index) {
        $blue_colors = [
            '#E6F3FF', // Very Light Blue
            '#C2DFFF', // Un poco más intenso
            '#B3D9FF', // Light Sky Blue  
            '#99CCFF', // Baby Blue
            '#A6D5FF', // Otro tono medio
            '#D9E2F3', // Navy Blue (el original)
            '#BCDCDC', // Un cyan diferente
            '#D6E4F2', // Alice Blue
            '#CFE7FF', // Otro tono distinto
            '#B9D3EF', // Variación del Steel Blue
            '#C4DDFF', // Otro azure distinto
            '#BCE0E0', // Cyan diferenciado
            '#D1E8FF'  // Otro tono distinto
        ];
         
        if ($index < 1 || $index > 12) {
            return $blue_colors[5]; 
        }
        return $blue_colors[$index - 1];
    }
?>


<style>

    .table td, .table th {
      padding: 5px;
    }

    .letraDiminuta{
      font-size: 8px;
    }

    th, td {
      width: 200px;
      white-space: nowrap;
    }

    select.columna_certificacion {
        width: 100%;
        background-color: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
  </style>

  <div class="row">
    <div class="col-md-12">
      <!--  -->
        <!--  -->
        <div class="table-responsive">
            <!--  -->
            <!--  -->
            <table class="table table-bordered" id="tabla_tramites">
              <thead class="" style="background-color: #002060; color: white;">
                  <tr>
                      <th class="letraPequena">GRUPO</th>
                      <th class="letraPequena">PROGRAMA</th>
                      <th class="letraPequena">DIAS</th>
                      <th class="letraPequena">MODALIDAD</th>
                      <th class="letraPequena">HORARIO</th>
                      <th class="letraPequena">F. INICIO</th>
                      <th class="letraPequena">F. TERMINO</th>
                      <th class="letraPequena">ACTIVOS</th>
                      <th class="letraPequena">TRAMITE 1</th>
                      <th class="letraPequena">POTENCIAL</th>
                      <th class="letraPequena">COBRADO</th>
                      <th class="letraPequena">X COBRAR</th>
                      <th class="letraPequena">%</th>
                      <th class="letraPequena">TRAMITE 2</th>
                      <th class="letraPequena">POTENCIAL</th>
                      <th class="letraPequena">COBRADO</th>
                      <th class="letraPequena">%</th>
                      
                  </tr>
              </thead>
              <tbody>
                <!--  -->
                <?php
                    // Construir la cláusula WHERE según el estatus
                    $where_clause = "WHERE p.id_pla = '$id_pla'";
                    switch ($estatus) {
                        case 'En curso':
                            $where_clause .= " AND CURDATE() BETWEEN g.ini_gen AND g.fin_gen";
                            break;
                        case 'Por comenzar':
                            $where_clause .= " AND g.ini_gen > CURDATE()";
                            break;
                        case 'Fin curso':
                            $where_clause .= " AND g.fin_gen < CURDATE()";
                            break;
                    }

                    // Consulta SQL principal
                    $sql = "
                    WITH GeneracionData AS (
                        SELECT 
                            g.id_gen,
                            g.nom_gen AS GRUPO,
                            r.abr_ram AS PROGRAMA,
                            g.dia_gen AS DIAS,
                            g.mod_gen AS MODALIDAD,
                            g.hor_gen AS HORARIO,
                            g.ini_gen AS F_INICIO,
                            g.fin_gen AS F_TERMINO,
                            MONTH(
                                (SELECT ini_gru_pag 
                                FROM grupo_pago 
                                WHERE id_gen15 = g.id_gen 
                                AND tip_gru_pag = 'Pago'
                                AND tip_pag_gru_pag = 'Otros'
                                ORDER BY ini_gru_pag ASC
                                LIMIT 1)
                            ) AS mes_inicio,  -- Mes basado en TRAMITE_1
                            (SELECT ini_gru_pag 
                            FROM grupo_pago 
                            WHERE id_gen15 = g.id_gen 
                            AND tip_gru_pag = 'Pago'
                            AND tip_pag_gru_pag = 'Otros'
                            ORDER BY ini_gru_pag ASC
                            LIMIT 1) AS TRAMITE_1,
                            (SELECT mon_gru_pag 
                            FROM grupo_pago 
                            WHERE id_gen15 = g.id_gen 
                            AND tip_gru_pag = 'Pago'
                            AND tip_pag_gru_pag = 'Otros'
                            ORDER BY ini_gru_pag ASC
                            LIMIT 1) AS MONTO_T1,
                            (SELECT ini_gru_pag 
                            FROM grupo_pago 
                            WHERE id_gen15 = g.id_gen 
                            AND tip_gru_pag = 'Pago'
                            AND tip_pag_gru_pag = 'Otros'
                            AND ini_gru_pag > (
                                SELECT ini_gru_pag 
                                FROM grupo_pago 
                                WHERE id_gen15 = g.id_gen 
                                AND tip_gru_pag = 'Pago'
                                AND tip_pag_gru_pag = 'Otros'
                                ORDER BY ini_gru_pag ASC
                                LIMIT 1
                            )
                            ORDER BY ini_gru_pag ASC
                            LIMIT 1) AS TRAMITE_2,
                            (SELECT mon_gru_pag 
                            FROM grupo_pago 
                            WHERE id_gen15 = g.id_gen 
                            AND tip_gru_pag = 'Pago'
                            AND tip_pag_gru_pag = 'Otros'
                            AND ini_gru_pag > (
                                SELECT ini_gru_pag 
                                FROM grupo_pago 
                                WHERE id_gen15 = g.id_gen 
                                AND tip_gru_pag = 'Pago'
                                AND tip_pag_gru_pag = 'Otros'
                                ORDER BY ini_gru_pag ASC
                                LIMIT 1
                            )
                            ORDER BY ini_gru_pag ASC
                            LIMIT 1) AS MONTO_T2
                        FROM 
                            generacion g
                            INNER JOIN rama r ON r.id_ram = g.id_ram5
                            INNER JOIN plantel p ON p.id_pla = r.id_pla1
                        {$where_clause}
                    )
                    SELECT 
                        *,
                        SUM(MONTO_T1) OVER (PARTITION BY mes_inicio) AS TOTAL_MES_T1,
                        SUM(MONTO_T2) OVER (PARTITION BY mes_inicio) AS TOTAL_MES_T2,
                        SUM(MONTO_T1) OVER () AS TOTAL_GENERAL_T1,
                        SUM(MONTO_T2) OVER () AS TOTAL_GENERAL_T2
                    FROM GeneracionData
                    ORDER BY TRAMITE_1 ASC;  -- Ordenar por TRAMITE_1
                    ";

                    $resultado = mysqli_query($db, $sql);

                    // Inicialización de variables
                    $mes_actual = null;
                    $totales_mes = [
                        't1' => 0, 't2' => 0, 'potencial_t1' => 0, 'potencial_t2' => 0,
                        'cobrado_t1' => 0, 'cobrado_t2' => 0, 'alumnos' => 0
                    ];
                    $totales_generales = [
                        't1' => 0, 't2' => 0, 'potencial_t1' => 0, 'potencial_t2' => 0,
                        'cobrado_t1' => 0, 'cobrado_t2' => 0, 'alumnos' => 0
                    ];
                    $contador_color = 1;

                    // Procesar los resultados
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $mes_inicio = date('n', strtotime($fila['TRAMITE_1']));  // Mes basado en TRAMITE_1

                        // Si cambia el mes, imprimir el total del mes anterior
                        if ($mes_actual !== null && $mes_actual != $mes_inicio) {
                            imprimirTotalMes($totales_mes, $contador_color);
                            $totales_mes = array_fill_keys(array_keys($totales_mes), 0);  // Reiniciar totales del mes
                            $contador_color++;
                        }

                        $mes_actual = $mes_inicio;

                        // Obtener el total de alumnos activos
                        $total_alumnos = obtenerTotalAlumnosActivos($db, $fila['id_gen']);

                        // Calcular potenciales y montos cobrados
                        $potencial_t1 = $total_alumnos * $fila['MONTO_T1'];
                        $potencial_t2 = $total_alumnos * $fila['MONTO_T2'];
                        $cobrado_t1 = obtenerMontoCobrado($db, $fila['id_gen'], 'tramite1');
                        $cobrado_t2 = obtenerMontoCobrado($db, $fila['id_gen'], 'tramite2');

                        // Actualizar totales
                        actualizarTotales($totales_mes, $totales_generales, $fila, $total_alumnos, $potencial_t1, $potencial_t2, $cobrado_t1, $cobrado_t2);

                        // Imprimir fila
                        imprimirFila($fila, $total_alumnos, $potencial_t1, $potencial_t2, $cobrado_t1, $cobrado_t2, $contador_color);
                    }

                    // Imprimir el último total mensual si hay datos
                    if ($mes_actual !== null) {
                        imprimirTotalMes($totales_mes, $contador_color);
                    }

                    // Imprimir total general
                    imprimirTotalGeneral($totales_generales);

                    // Funciones auxiliares
                    function obtenerTotalAlumnosActivos($db, $id_gen) {
                        $sql = "SELECT obtener_total_alumnos_activos_generacion($id_gen) AS total";
                        $resultado = mysqli_query($db, $sql);
                        $row = mysqli_fetch_assoc($resultado);
                        return $row['total'];
                    }

                    function obtenerMontoCobrado($db, $id_gen, $tramite) {
                        $sql = "SELECT obtener_abonado_{$tramite}_generacion($id_gen) AS total";
                        $resultado = mysqli_query($db, $sql);
                        $row = mysqli_fetch_assoc($resultado);
                        return $row['total'];
                    }

                    function actualizarTotales(&$totales_mes, &$totales_generales, $fila, $total_alumnos, $potencial_t1, $potencial_t2, $cobrado_t1, $cobrado_t2) {
                        $totales_mes['t1'] += $fila['MONTO_T1'];
                        $totales_mes['t2'] += $fila['MONTO_T2'];
                        $totales_mes['potencial_t1'] += $potencial_t1;
                        $totales_mes['potencial_t2'] += $potencial_t2;
                        $totales_mes['cobrado_t1'] += $cobrado_t1;
                        $totales_mes['cobrado_t2'] += $cobrado_t2;
                        $totales_mes['alumnos'] += $total_alumnos;

                        $totales_generales['t1'] += $fila['MONTO_T1'];
                        $totales_generales['t2'] += $fila['MONTO_T2'];
                        $totales_generales['potencial_t1'] += $potencial_t1;
                        $totales_generales['potencial_t2'] += $potencial_t2;
                        $totales_generales['cobrado_t1'] += $cobrado_t1;
                        $totales_generales['cobrado_t2'] += $cobrado_t2;
                        $totales_generales['alumnos'] += $total_alumnos;
                    }

                    function imprimirFila($fila, $total_alumnos, $potencial_t1, $potencial_t2, $cobrado_t1, $cobrado_t2, $contador_color) {
                        ?>
                        <tr style="background-color: white; font-weight: bold;">
                            <td class="letraPequena" data-id="<?= $fila['id_gen'] ?>">
                                <a href="alumnos.php?id_gen=<?= $fila['id_gen'] ?>" target="_blank"><?= $fila['GRUPO'] ?></a>
                            </td>
                            <td class="letraPequena"><?= $fila['PROGRAMA'] ?></td>
                            <td class="letraPequena"><?= $fila['DIAS'] ?></td>
                            <td class="letraPequena"><?= $fila['MODALIDAD'] ?></td>
                            <td class="letraPequena"><?= $fila['HORARIO'] ?></td>
                            <td class="letraPequena" ini_gen="<?= $fila['F_INICIO'] ?>"><?= fechaFormateadaCompacta4($fila['F_INICIO']) ?></td>
                            <td class="letraPequena" fin_gen="<?= $fila['F_TERMINO'] ?>"><?= fechaFormateadaCompacta4($fila['F_TERMINO']) ?></td>
                            <td class="letraPequena"><?= $total_alumnos ?></td>
                            <td class="letraPequena" style="background-color: <?= obtenerAzul($contador_color) ?>">
                                <?= (!empty($fila['TRAMITE_1']) && $fila['TRAMITE_1'] != '0000-00-00') ? fechaFormateadaCompacta4($fila['TRAMITE_1']) : 'N/A' ?>
                            </td>
                            <td class="letraPequena"><?= formatearDinero($potencial_t1) ?></td>
                            <td class="letraPequena"><?= (!empty($fila['TRAMITE_1']) && $fila['TRAMITE_1'] != '0000-00-00') ? formatearDinero($cobrado_t1) : 'N/A' ?></td>
                            <td class="letraPequena"><?= (!empty($fila['TRAMITE_1']) && $fila['TRAMITE_1'] != '0000-00-00') ? formatearDinero($potencial_t1 - $cobrado_t1) : 'N/A' ?></td>
                            <td class="letraPequena"><?= (!empty($fila['TRAMITE_1']) && $fila['TRAMITE_1'] != '0000-00-00' && $potencial_t1 > 0) ? number_format(($cobrado_t1 / $potencial_t1) * 100, 1) . '%' : 'N/A' ?></td>
                            <td class="letraPequena" style="background-color: <?= obtenerAzul($contador_color) ?>">
                                <?= (!empty($fila['TRAMITE_2']) && $fila['TRAMITE_2'] != '0000-00-00') ? fechaFormateadaCompacta4($fila['TRAMITE_2']) : 'N/A' ?>
                            </td>
                            <td class="letraPequena"><?= formatearDinero($potencial_t2) ?></td>
                            <td class="letraPequena"><?= (!empty($fila['TRAMITE_2']) && $fila['TRAMITE_2'] != '0000-00-00') ? formatearDinero($cobrado_t2) : 'N/A' ?></td>
                            <td class="letraPequena"><?= (!empty($fila['TRAMITE_2']) && $fila['TRAMITE_2'] != '0000-00-00' && $potencial_t2 > 0) ? number_format(($cobrado_t2 / $potencial_t2) * 100, 1) . '%' : 'N/A' ?></td>
                        </tr>
                        <?php
                    }

                    function imprimirTotalMes($totales_mes, $contador_color) {
                        ?>
                        <tr style="background-color: #FFEB9C; font-weight: bold;">
                            <!-- Columnas 1-8 -->
                            <td class="letraPequena">TOTAL MES</td> <!-- Columna 1: GRUPO -->
                            
                            <td class="letraPequena"></td>          <!-- Columna 3: DIAS -->
                            <td class="letraPequena"></td>          <!-- Columna 4: MODALIDAD -->
                            <td class="letraPequena"></td>          <!-- Columna 5: HORARIO -->
                            <td class="letraPequena"></td>          <!-- Columna 6: F. INICIO -->
                            <td class="letraPequena"></td>          <!-- Columna 7: F. TERMINO -->
                            <td class="letraPequena"></td>          <!-- Columna 8: ACTIVOS -->
                    
                            <!-- Columnas 9-17 -->
                            <td class="letraPequena"><?= $totales_mes['alumnos'] ?></td>                          <!-- Columna 9: TRAMITE 1 -->
                            <td class="letraPequena"></td>          <!-- Columna 2: PROGRAMA -->
                            <td class="letraPequena"><?= formatearDinero($totales_mes['potencial_t1']) ?></td>     <!-- Columna 10: POTENCIAL -->
                            <td class="letraPequena"><?= formatearDinero($totales_mes['cobrado_t1']) ?></td>       <!-- Columna 11: COBRADO -->
                            <td class="letraPequena"><?= formatearDinero($totales_mes['potencial_t1'] - $totales_mes['cobrado_t1']) ?></td> <!-- Columna 12: X COBRAR -->
                            <td class="letraPequena"><?= ($totales_mes['potencial_t1'] > 0) ? number_format(($totales_mes['cobrado_t1'] / $totales_mes['potencial_t1']) * 100, 1) . '%' : '0%' ?></td> <!-- Columna 13: % -->
                            <td class="letraPequena"></td>          <!-- Columna 14: TRAMITE 2 -->
                            <td class="letraPequena"><?= formatearDinero($totales_mes['potencial_t2']) ?></td>     <!-- Columna 15: POTENCIAL -->
                            <td class="letraPequena"><?= formatearDinero($totales_mes['cobrado_t2']) ?></td>       <!-- Columna 16: COBRADO -->
                            <td class="letraPequena"><?= ($totales_mes['potencial_t2'] > 0) ? number_format(($totales_mes['cobrado_t2'] / $totales_mes['potencial_t2']) * 100, 1) . '%' : '0%' ?></td> <!-- Columna 17: % -->
                        </tr>
                        <?php
                    }
                    
                    function imprimirTotalGeneral($totales_generales) {
                        ?>
                        <tr style="background-color: #FFEB9C; font-weight: bold;">
                            <!-- Columnas 1-8 -->
                            <td class="letraPequena">TOTAL GENERAL</td> <!-- Columna 1: GRUPO -->
                            
                            <td class="letraPequena"></td>              <!-- Columna 3: DIAS -->
                            <td class="letraPequena"></td>              <!-- Columna 4: MODALIDAD -->
                            <td class="letraPequena"></td>              <!-- Columna 5: HORARIO -->
                            <td class="letraPequena"></td>              <!-- Columna 6: F. INICIO -->
                            <td class="letraPequena"></td>              <!-- Columna 7: F. TERMINO -->
                            <td class="letraPequena"></td>              <!-- Columna 8: ACTIVOS -->
                    
                            <!-- Columnas 9-17 -->
                            <td class="letraPequena"><?= $totales_generales['alumnos'] ?></td>                     <!-- Columna 9: TRAMITE 1 -->
                            <td class="letraPequena"></td>              <!-- Columna 2: PROGRAMA -->
                            <td class="letraPequena"><?= formatearDinero($totales_generales['potencial_t1']) ?></td><!-- Columna 10: POTENCIAL -->
                            <td class="letraPequena"><?= formatearDinero($totales_generales['cobrado_t1']) ?></td>  <!-- Columna 11: COBRADO -->
                            <td class="letraPequena"><?= formatearDinero($totales_generales['potencial_t1'] - $totales_generales['cobrado_t1']) ?></td> <!-- Columna 12: X COBRAR -->
                            <td class="letraPequena"><?= ($totales_generales['potencial_t1'] > 0) ? number_format(($totales_generales['cobrado_t1'] / $totales_generales['potencial_t1']) * 100, 1) . '%' : '0%' ?></td> <!-- Columna 13: % -->
                            <td class="letraPequena"></td>              <!-- Columna 14: TRAMITE 2 -->
                            <td class="letraPequena"><?= formatearDinero($totales_generales['potencial_t2']) ?></td><!-- Columna 15: POTENCIAL -->
                            <td class="letraPequena"><?= formatearDinero($totales_generales['cobrado_t2']) ?></td>  <!-- Columna 16: COBRADO -->
                            <td class="letraPequena"><?= ($totales_generales['potencial_t2'] > 0) ? number_format(($totales_generales['cobrado_t2'] / $totales_generales['potencial_t2']) * 100, 1) . '%' : '0%' ?></td> <!-- Columna 17: % -->
                        </tr>
                        <?php
                    }
                ?>
                <!-- F -->
              </tbody>

            </table>

            <!--  -->
            <!--  -->
        </div>
        <!--  -->
      <!--  -->
    </div>
  
  </div>

  
  <hr>
  
  <br><br>
  <br><br><br><br><br><br><br><br><br>


  

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
    $('#tabla_tramites').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        ordering: false, // Desactiva la ordenación
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE PLANEACION DE INICIOS',
                className: 'btn-sm btn-success',
                exportOptions: {
                    columns: ':not(:nth-child(2))' // Excluye la segunda columna (PERMISOS)
                }
            },
        ],
        language: {
            search: 'Buscar' // Cambia el texto del buscador
        },
        info: false // Esto desactiva la información del pie de la tabla
    });
</script>


<script>
    // CSS para las celdas editables
    var estilos = `
    <style>
        #tabla_tramites tbody td:nth-child(1),    /* GRUPO */
        #tabla_tramites tbody td:nth-child(2),    /* PROGRAMA */
        #tabla_tramites tbody td:nth-child(3),    /* DIAS */
        #tabla_tramites tbody td:nth-child(4),    /* MODALIDAD */
        #tabla_tramites tbody td:nth-child(5),    /* HORARIO */
        #tabla_tramites tbody td:nth-child(6),    /* F. INICIO */
        #tabla_tramites tbody td:nth-child(7) {   /* F. TERMINO */
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        #tabla_tramites tbody td:nth-child(1):hover,
        #tabla_tramites tbody td:nth-child(2):hover,
        #tabla_tramites tbody td:nth-child(3):hover,
        #tabla_tramites tbody td:nth-child(4):hover,
        #tabla_tramites tbody td:nth-child(5):hover,
        #tabla_tramites tbody td:nth-child(6):hover,
        #tabla_tramites tbody td:nth-child(7):hover {
            background-color: #e9ecef !important;
            box-shadow: inset 0 0 0 1px #dee2e6;
        }

        #tabla_tramites tbody td:nth-child(1)::after,
        #tabla_tramites tbody td:nth-child(2)::after,
        #tabla_tramites tbody td:nth-child(3)::after,
        #tabla_tramites tbody td:nth-child(4)::after,
        #tabla_tramites tbody td:nth-child(5)::after,
        #tabla_tramites tbody td:nth-child(6)::after,
        #tabla_tramites tbody td:nth-child(7)::after {
            content: '✎';
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            color: #6c757d;
            transition: opacity 0.2s ease-in-out;
            font-size: 12px;
        }

        #tabla_tramites tbody td:nth-child(1):hover::after,
        #tabla_tramites tbody td:nth-child(2):hover::after,
        #tabla_tramites tbody td:nth-child(3):hover::after,
        #tabla_tramites tbody td:nth-child(4):hover::after,
        #tabla_tramites tbody td:nth-child(5):hover::after,
        #tabla_tramites tbody td:nth-child(6):hover::after,
        #tabla_tramites tbody td:nth-child(7):hover::after {
            opacity: 1;
        }
    </style>`;

    $('head').append(estilos);

    // HTML de la modal
    $('#warning-alert-modal .modal-body .form-group').html(`
        <input type="text" class="form-control" id="valor_edicion" style="display: none;">
        <select class="form-control letraPequena" id="select_modalidad" style="display: none;">
            <option value="Online">ONLINE</option>
            <option value="Presencial">PRESENCIAL</option>
        </select>
        <input type="date" class="form-control letraPequena" id="input_fecha" style="display: none;">
    `);

    // Handler del click
    $('#tabla_tramites tbody').on('click', 'td', function(e) {
        var $celda = $(this);
        var columnaIndex = $celda.index();
        
        if (![0,1,2,3,4,5,6].includes(columnaIndex) || $(e.target).is('a')) {
            return;
        }
        
        if ($celda.closest('tr').find('td').text().includes('TOTAL')) {
            return;
        }
        
        var $fila = $celda.closest('tr');
        var camposPorColumna = {
            0: 'nom_gen',     // GRUPO
            1: 'nom_pro',     // PROGRAMA 
            2: 'dia_gen',     // DIAS
            3: 'mod_gen',     // MODALIDAD
            4: 'hor_gen',     // HORARIO
            5: 'ini_gen',     // F. INICIO
            6: 'fin_gen'      // F. TERMINO
        };
        
        var campo = camposPorColumna[columnaIndex];
        var id_gen = $fila.find('td:eq(0)').attr('data-id'); // Asumiendo que agregarás este atributo
        var nombreGrupo = $fila.find('td:eq(0)').text().trim();
        
        var $input = $('#valor_edicion');
        var $select = $('#select_modalidad');
        var $inputFecha = $('#input_fecha');
        
        $input.hide();
        $select.hide();
        $inputFecha.hide();
        
        if (campo === 'mod_gen') {
            var modalidad = $celda.text().trim();
            $select.show().val(modalidad.includes('ONLINE') ? 'Online' : 'Presencial');
        } 
        else if (campo === 'ini_gen' || campo === 'fin_gen') {
            var fechaOriginal = $celda.attr(campo);
            $inputFecha.show().val(fechaOriginal);
        } 
        else {
            $input.show()
                .attr('type', 'text')
                .val($celda.text().trim());
        }
        
        $('#id_gen_aux').val(id_gen);
        $('#campo_edicion').val(campo);
        $('#nombreGrupoEdicion').text(nombreGrupo);
        
        $('#warning-alert-modal').modal('show');
    });

    // Handler del botón guardar
    $('#btnGuardarEdicion').off('click').on('click', function() {
        var campo = $('#campo_edicion').val();
        var valor;
        
        switch(campo) {
            case 'mod_gen':
                valor = $('#select_modalidad').val();
                break;
            case 'ini_gen':
            case 'fin_gen':
                var fecha = $('#input_fecha').val(); 
                if (fecha) {
                    var partes = fecha.split('-');
                    valor = partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                break;
            default:
                valor = $('#valor_edicion').val();
        }
        
        if (!valor || !valor.trim()) {
            toastr.error('Por favor, ingrese un valor válido');
            return;
        }
        
        $.ajax({
            url: 'server/controlador_grupo2.php',
            type: 'POST',
            data: {
                accion: 'Cambio',
                id_gen_aux: $('#id_gen_aux').val(),
                campo: campo,
                valor: valor
            },
            success: function(response) {
                var res = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (res.resultado === 'success') {
                    toastr.success('Datos actualizados correctamente');
                    $('#warning-alert-modal').modal('hide');
                    obtener_datos();
                } else if (res.resultado === 'deleted') {
                    toastr.warning('Registro eliminado por valor vacío');
                    obtener_datos();
                } else {
                    toastr.error('Error al guardar: ' + (res.mensaje || 'Error desconocido'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición:', {
                    error: error,
                    status: status,
                    response: xhr.responseText
                });
                toastr.error('Error en la conexión. Por favor, intente nuevamente');
            }
        });
    });
</script>