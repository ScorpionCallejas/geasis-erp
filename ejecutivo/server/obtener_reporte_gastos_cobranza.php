<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	// $id_pla = $_POST['id_pla'];

	$id_pla = $_POST['id_pla'];

    // Procesar filtros de tipo y método de pago
    $filtros_tipo = isset($_POST['filtros_tipo']) ? $_POST['filtros_tipo'] : ['colegiatura', 'inscripcion', 'tramite'];
    $filtros_metodo = isset($_POST['filtros_metodo']) ? $_POST['filtros_metodo'] : ['efectivo', 'deposito'];

    // Crear variables booleanas para facilitar las condiciones
    $mostrar_colegiatura = in_array('colegiatura', $filtros_tipo);
    $mostrar_inscripcion = in_array('inscripcion', $filtros_tipo);
    $mostrar_tramite = in_array('tramite', $filtros_tipo);
    $mostrar_efectivo = in_array('efectivo', $filtros_metodo);
    $mostrar_deposito = in_array('deposito', $filtros_metodo);

  // echo "backend: ".$id_pla;
	//fechaDia( $fecha );
?>
  
  <style>
    .table td, .table th {
      padding: 5px;
    }

    .eliminacionEgreso {
        position: absolute;
        top: 0px;
        right: 5px;
        color: red;
        cursor: pointer;
        font-size: 18px;
    }

    .table td, .table th {
      padding: 1;
      margin: 0;
    }

    .textoEnde{
      color: #05B6DA;
    }

    .textoNegro{
      color: black;
    }

    .textoNegrito{
      font-weight: bold
    }

    .fondoEnde{
      background: #05B6DA;
    }

    .textoBlanco{
      color: white;
    }

    .fondoNegro{
      background: black;
    }

    .fondoBlanco{
      background: white;
    }

    /* Hacer toda la tabla con negritas */
    #tabla_reporte_azul td, #tabla_reporte_azul th {
      font-weight: bold;
    }

    /* Fila TOTAL GASTOS más visible */
    .fila-total-gastos td {
        background-color: #000000 !important;
        color: #ffffff !important;
    }
    
    .fila-total-gastos .custom-link {
        color: #ffffff !important;
        font-weight: bold !important;
    }
</style>
        <!-- TABLA TOTALES -->
        <div class="row mb-4">
            <!-- Tabla Colegiaturas -->
            <?php if($mostrar_colegiatura): ?>
            <div class="col-md-4">
                <table class="table-totales" id="tabla_colegiaturas">
                    <tr class="encabezado">
                        <td class="letraPequena">COLEGIATURAS</td>
                        <td class="letraPequena"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Cobranza efectivo Colegiatura</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Gastos en efectivo Colegiatura</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Sobrante de colegiaturas</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Cobranza en la cuenta empresarial</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Gastos en la cuenta empresarial</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr class="ultimo">
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Sobrante en cuenta empresarial colegiaturas</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>

            <!-- Tabla Inscripciones -->
            <?php if($mostrar_inscripcion): ?>
            <div class="col-md-4">
                <table class="table-totales" id="tabla_inscripciones">
                    <tr class="encabezado">
                        <td class="letraPequena">INSCRIPCIONES</td>
                        <td class="letraPequena"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Cobranza efectivo Inscripción</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Gastos en efectivo Inscripción</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Sobrante de inscripciones</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Cobranza en la cuenta empresarial</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Gastos en la cuenta empresarial</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr class="ultimo">
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Sobrante en cuenta empresarial inscripciones</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>

            <!-- Tabla Trámites -->
            <?php if($mostrar_tramite): ?>
            <div class="col-md-4">
                <table class="table-totales" id="tabla_tramites">
                    <tr class="encabezado">
                        <td class="letraPequena">TRÁMITES</td>
                        <td class="letraPequena"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Cobranza efectivo tramites</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Gastos en efectivo tramites</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Sobrante tramites</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Cobranza en la cuenta empresarial</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Gastos en la cuenta empresarial</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                    <tr class="ultimo">
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff">Sobrante en cuenta empresarial tramites</td>
                        <td class="letraPequena textoNegro" style="background-color: #e7f0ff"></td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>
        </div>
        <!-- F TABLA TOTALES -->


        <!-- TABLA COBRANZA Y GASTOS -->
        <div class="table-responsive">
          <!--  -->
          <table class="table-bordered table" id="tabla_reporte_azul">

            <thead>
                <tr class="fondoNegro textoEnde">
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                    <th class="letraPequena"></th>
                </tr>
            </thead>
            <tbody>

            <!-- COLEGIATURA EFECTIVO -->
            <?php if($mostrar_colegiatura && $mostrar_efectivo): ?>
                <!--  -->
                <?php
                  // Consulta de egresos
                    $sqlEgresosEfectivo = "SELECT * FROM egreso 
                                        WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                        AND id_pla13 = '$id_pla' 
                                        AND for_egr = 'colegiatura_efectivo'
                                        ORDER BY id_egr DESC                
                    ";

                  // Debugging: imprimir la query
                  // echo $sqlEgresosEfectivo;

                  $resultEgresos = mysqli_query($db, $sqlEgresosEfectivo);
                  $conceptos = array();

                  $dias = array(
                      '0' => 'domingo',
                      '1' => 'lunes',
                      '2' => 'martes',
                      '3' => 'miercoles',
                      '4' => 'jueves',
                      '5' => 'viernes',
                      '6' => 'sabado'
                  );

                  if($resultEgresos) {
                      while($egreso = mysqli_fetch_assoc($resultEgresos)) {
                          $timestamp = strtotime($egreso['fec_egr']);
                          if($timestamp !== false) {
                              $numero_dia = date('w', $timestamp);
                              
                              $conceptos[] = array(
                                  'id_egr' => $egreso['id_egr'],     // Añadido
                                  'concepto' => trim($egreso['con_egr']),
                                  'monto' => is_numeric($egreso['mon_egr']) ? floatval($egreso['mon_egr']) : 0,
                                  'dia' => $dias[$numero_dia],
                                  'observaciones' => trim($egreso['obs_egr'])
                              );
                          }
                      }
                  }

                  // Liberar el resultado
                  mysqli_free_result($resultEgresos);
                ?>
                  
                <?php
                  $dia_1 = $inicio;
                  $dia_2 = sumarDias($inicio, 1);
                  $dia_3 = sumarDias($inicio, 2);
                  $dia_4 = sumarDias($inicio, 3);
                  $dia_5 = sumarDias($inicio, 4);
                  $dia_6 = sumarDias($inicio, 5);
                  $dia_7 = sumarDias($inicio, 6);

                  // Obtenemos todos los valores
                  $valores = [];

                  // LUNES
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['lunes'] = $datos['datos']['total'];
                  $liga_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Efectivo', 'Colegiatura');

                  // MARTES
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['martes'] = $datos['datos']['total'];
                  $liga_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Efectivo', 'Colegiatura');

                  // MIÉRCOLES
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['miercoles'] = $datos['datos']['total'];
                  $liga_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Efectivo', 'Colegiatura');

                  // JUEVES
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['jueves'] = $datos['datos']['total'];
                  $liga_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Efectivo', 'Colegiatura');

                  // VIERNES
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['viernes'] = $datos['datos']['total'];
                  $liga_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Efectivo', 'Colegiatura');

                  // SÁBADO
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['sabado'] = $datos['datos']['total'];
                  $liga_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Efectivo', 'Colegiatura');

                //   echo $sql;
                  // DOMINGO
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['domingo'] = $datos['datos']['total'];
                  $liga_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Efectivo', 'Colegiatura');

                  // Calculamos el total
                  $total_cobranza = array_sum($valores);

                  // Armamos el array de filas fijas con los valores obtenidos y sus ligas
                    // Generar la liga para el total (de dia_1 a dia_7)
                    $liga_total_cobranza = obtenerLigaCobranza($id_pla, $dia_1, $dia_7, 'Efectivo', 'Colegiatura');
                    $filas_fijas = [
                        'LUNES' => [
                            'monto' => $valores['lunes'],
                            'liga' => $liga_lunes
                        ],
                        'MARTES' => [
                            'monto' => $valores['martes'],
                            'liga' => $liga_martes
                        ],
                        'MIÉRCOLES' => [
                            'monto' => $valores['miercoles'],
                            'liga' => $liga_miercoles
                        ],
                        'JUEVES' => [
                            'monto' => $valores['jueves'],
                            'liga' => $liga_jueves
                        ],
                        'VIERNES' => [
                            'monto' => $valores['viernes'],
                            'liga' => $liga_viernes
                        ],
                        'SÁBADO' => [
                            'monto' => $valores['sabado'],
                            'liga' => $liga_sabado
                        ],
                        'DOMINGO' => [
                            'monto' => $valores['domingo'],
                            'liga' => $liga_domingo
                        ],
                        'TOTAL COBRANZA' => [
                            'monto' => $total_cobranza,
                            'liga' => $liga_total_cobranza
                        ]
                    ];

                    // Determinamos si hay más conceptos que filas fijas
                    $conceptos_adicionales = count($conceptos) > count($filas_fijas);
                ?>

              <!--  -->
              <!-- TOTALES -->
                <?php
                // Calcular sumatorias por día
                $sumatorias = array(
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0
                );

                foreach ($conceptos as $concepto) {
                    if (isset($concepto['dia']) && isset($concepto['monto'])) {
                        $sumatorias[$concepto['dia']] += floatval($concepto['monto']);
                    }
                }

                // Generar las URLs para los totales
                $liga_total_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Efectivo', 'Colegiatura');
                $liga_total_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Efectivo', 'Colegiatura');
                $liga_total_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Efectivo', 'Colegiatura');
                $liga_total_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Efectivo', 'Colegiatura');
                $liga_total_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Efectivo', 'Colegiatura');
                $liga_total_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Efectivo', 'Colegiatura');
                $liga_total_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Efectivo', 'Colegiatura');
                ?>
                <!-- F TOTALES -->

                <!--  -->
                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">COLEGIATURAS</td>
                    <td class="letraPequena">EFECTIVO</td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_1); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_2); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_3); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_4); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_5); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_6); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_7); ?></td>
                    <td class="letraPequena"></td>
                </tr>

                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">CONCEPTO</td>
                    <td class="letraPequena">IMPORTE</td>
                    <td class="letraPequena">CONCEPTO DEL GASTO</td>
                    <td class="letraPequena">LUNES</td>
                    <td class="letraPequena">MARTES</td>
                    <td class="letraPequena">MIÉRCOLES</td>
                    <td class="letraPequena">JUEVES</td>
                    <td class="letraPequena">VIERNES</td>
                    <td class="letraPequena">SÁBADO</td>
                    <td class="letraPequena">DOMINGO</td>
                    <td class="letraPequena">OBSERVACIONES</td>
                </tr>

                <!-- Primera parte: las 8 filas fijas -->
                <?php foreach ($filas_fijas as $dia => $datos) : ?>
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: black; color: #05B6DA;"><?php echo $dia; ?></td>
                        <td class="letraPequena">
                            
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $datos['liga']; ?>">
                                <?php if ($dia !== 'TOTAL COBRANZA') : ?>
                                    $ <?php echo number_format($datos['monto'], 2); ?>
                                <?php else : ?>
                                    $ <?php echo number_format($datos['monto'], 2); ?>
                                <?php endif; ?>
                            </a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                            <?php
                            $index = array_search($dia, array_keys($filas_fijas));
                            if (isset($conceptos[$index])) {
                                echo '<span class="eliminacionEgreso" id_egr="' . $conceptos[$index]['id_egr'] . '">&times;</span>';
                                echo $conceptos[$index]['concepto'];
                            }
                            ?>
                        </td>
                        <?php foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia_columna) : ?>
                            <td class="letraPequena">
                                <?php
                                if (isset($conceptos[$index]) && $conceptos[$index]['dia'] == $dia_columna) {
                                    echo '$ ' . number_format($conceptos[$index]['monto'], 2);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="letraPequena">
                            <?php echo isset($conceptos[$index]) ? $conceptos[$index]['observaciones'] : ''; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Segunda parte: conceptos adicionales si existen -->
                <?php if ($conceptos_adicionales) : ?>
                    <?php for ($i = count($filas_fijas); $i < count($conceptos); $i++) : ?>
                        <tr class="textoNegro">
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                                <span class="eliminacionEgreso" id_egr="<?php echo $conceptos[$i]['id_egr']; ?>">&times;</span>
                                <?php echo $conceptos[$i]['concepto']; ?>
                            </td>

                            <?php foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia) : ?>
                                <td class="letraPequena">
                                    <?php echo ($conceptos[$i]['dia'] == $dia) ? '$ ' . number_format($conceptos[$i]['monto'], 2) : ''; ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="letraPequena">
                                <?php echo $conceptos[$i]['observaciones']; ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>

                <!-- FILAS TOTALES -->
                <tr class="textoNegro">
                    <td class="letraPequena" style="background-color: #e7f0ff;">TOTAL GASTOS</td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_lunes; ?>">$ <?php echo number_format($sumatorias['lunes'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_martes; ?>">$ <?php echo number_format($sumatorias['martes'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_miercoles; ?>">$ <?php echo number_format($sumatorias['miercoles'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_jueves; ?>">$ <?php echo number_format($sumatorias['jueves'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_viernes; ?>">$ <?php echo number_format($sumatorias['viernes'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_sabado; ?>">$ <?php echo number_format($sumatorias['sabado'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_domingo; ?>">$ <?php echo number_format($sumatorias['domingo'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                </tr>
                <!-- F FILAS TOTALES -->

                <!-- Filas negras finales -->
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
            <?php endif; ?>
            <!-- F COLEGIATURA EFECTIVO -->


            <!-- COLEGIATURA DEPOSITO -->
            <?php if($mostrar_colegiatura && $mostrar_deposito): ?>

                <!--  -->
                <?php
                  // Consulta de egresos
                    $sqlEgresosEfectivo = "SELECT * FROM egreso 
                                        WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                        AND id_pla13 = '$id_pla' 
                                        AND for_egr = 'colegiatura_deposito'
                                        ORDER BY id_egr DESC                
                    ";

                  // Debugging: imprimir la query
                  // echo $sqlEgresosEfectivo;

                  $resultEgresos = mysqli_query($db, $sqlEgresosEfectivo);
                  $conceptos = array();

                  $dias = array(
                      '0' => 'domingo',
                      '1' => 'lunes',
                      '2' => 'martes',
                      '3' => 'miercoles',
                      '4' => 'jueves',
                      '5' => 'viernes',
                      '6' => 'sabado'
                  );

                  if($resultEgresos) {
                      while($egreso = mysqli_fetch_assoc($resultEgresos)) {
                          $timestamp = strtotime($egreso['fec_egr']);
                          if($timestamp !== false) {
                              $numero_dia = date('w', $timestamp);
                              
                              $conceptos[] = array(
                                  'id_egr' => $egreso['id_egr'],     // Añadido
                                  'concepto' => trim($egreso['con_egr']),
                                  'monto' => is_numeric($egreso['mon_egr']) ? floatval($egreso['mon_egr']) : 0,
                                  'dia' => $dias[$numero_dia],
                                  'observaciones' => trim($egreso['obs_egr'])
                              );
                          }
                      }
                  }

                  // Liberar el resultado
                  mysqli_free_result($resultEgresos);
                ?>
                  
                <?php
                  $dia_1 = $inicio;
                  $dia_2 = sumarDias($inicio, 1);
                  $dia_3 = sumarDias($inicio, 2);
                  $dia_4 = sumarDias($inicio, 3);
                  $dia_5 = sumarDias($inicio, 4);
                  $dia_6 = sumarDias($inicio, 5);
                  $dia_7 = sumarDias($inicio, 6);
                  
                  // Obtenemos todos los valores
                  $valores = [];
                  
                  // LUNES
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['lunes'] = $datos['datos']['total'];
                  $liga_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Deposito', 'Colegiatura');
                  
                  // MARTES
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['martes'] = $datos['datos']['total'];
                  $liga_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Deposito', 'Colegiatura');
                  
                  // MIÉRCOLES
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['miercoles'] = $datos['datos']['total'];
                  $liga_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Deposito', 'Colegiatura');
                  
                  // JUEVES
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['jueves'] = $datos['datos']['total'];
                  $liga_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Deposito', 'Colegiatura');
                  
                  // VIERNES
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['viernes'] = $datos['datos']['total'];
                  $liga_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Deposito', 'Colegiatura');
                  
                  // SÁBADO
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['sabado'] = $datos['datos']['total'];
                  $liga_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Deposito', 'Colegiatura');
                  
                  // DOMINGO
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['domingo'] = $datos['datos']['total'];
                  $liga_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Deposito', 'Colegiatura');
                  
                  // Calculamos el total
                  $total_cobranza = array_sum($valores);
                  
                  // Generamos la liga para el total (de dia_1 a dia_7)
                  $liga_total_cobranza = obtenerLigaCobranza($id_pla, $dia_1, $dia_7, 'Deposito', 'Colegiatura');

                  // Armamos el array de filas fijas con los valores obtenidos y sus ligas
                    $filas_fijas = [
                        'LUNES' => [
                            'monto' => $valores['lunes'],
                            'liga' => $liga_lunes
                        ],
                        'MARTES' => [
                            'monto' => $valores['martes'],
                            'liga' => $liga_martes
                        ],
                        'MIÉRCOLES' => [
                            'monto' => $valores['miercoles'],
                            'liga' => $liga_miercoles
                        ],
                        'JUEVES' => [
                            'monto' => $valores['jueves'],
                            'liga' => $liga_jueves
                        ],
                        'VIERNES' => [
                            'monto' => $valores['viernes'],
                            'liga' => $liga_viernes
                        ],
                        'SÁBADO' => [
                            'monto' => $valores['sabado'],
                            'liga' => $liga_sabado
                        ],
                        'DOMINGO' => [
                            'monto' => $valores['domingo'],
                            'liga' => $liga_domingo
                        ],
                        'TOTAL COBRANZA' => [
                            'monto' => $total_cobranza,
                            'liga' => $liga_total_cobranza
                        ]
                    ];

                    // Determinamos si hay más conceptos que filas fijas
                    $conceptos_adicionales = count($conceptos) > count($filas_fijas);
                    ?>

                    <!-- TOTALES -->
                    <?php
                    // Calcular sumatorias por día
                    $sumatorias = array(
                        'lunes' => 0,
                        'martes' => 0,
                        'miercoles' => 0,
                        'jueves' => 0,
                        'viernes' => 0,
                        'sabado' => 0,
                        'domingo' => 0
                    );

                    foreach($conceptos as $concepto) {
                        if(isset($concepto['dia']) && isset($concepto['monto'])) {
                            $sumatorias[$concepto['dia']] += floatval($concepto['monto']);
                        }
                    }
                    ?>
                    <!-- F TOTALES -->

                    <!--  -->
                    <tr class="fondoNegro textoEnde textoNegrito">
                        <td class="letraPequena">COLEGIATURAS</td>
                        <td class="letraPequena">DEPÓSITO</td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_1); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_2); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_3); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_4); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_5); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_6); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_7); ?></td>
                        <td class="letraPequena"></td>
                    </tr>

                    <tr class="fondoNegro textoEnde textoNegrito">
                        <td class="letraPequena">CONCEPTO</td>
                        <td class="letraPequena">IMPORTE</td>
                        <td class="letraPequena">CONCEPTO DEL GASTO</td>
                        <td class="letraPequena">LUNES</td>
                        <td class="letraPequena">MARTES</td>
                        <td class="letraPequena">MIÉRCOLES</td>
                        <td class="letraPequena">JUEVES</td>
                        <td class="letraPequena">VIERNES</td>
                        <td class="letraPequena">SÁBADO</td>
                        <td class="letraPequena">DOMINGO</td>
                        <td class="letraPequena">OBSERVACIONES</td>
                    </tr>
                  
                    <!-- Primera parte: las 8 filas fijas -->
                    <?php foreach($filas_fijas as $dia => $datos): ?>
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: black; color: #05B6DA;"><?php echo $dia; ?></td>
                        <td class="letraPequena">
                            <a target="_blank" class="text-primary custom-link"  class="text-primary custom-link"  href="<?php echo $datos['liga']; ?>">
                                $ <?php echo number_format($datos['monto'], 2); ?>
                            </a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                            <?php 
                            $index = array_search($dia, array_keys($filas_fijas));
                            if(isset($conceptos[$index])) {
                                echo '<span class="eliminacionEgreso" id_egr="' . $conceptos[$index]['id_egr'] . '">&times;</span>';
                                echo $conceptos[$index]['concepto'];
                            }
                            ?>
                        </td>
                        <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia_columna): ?>
                        <td class="letraPequena">
                            <?php
                            if(isset($conceptos[$index]) && $conceptos[$index]['dia'] == $dia_columna) {
                                echo '$ '.number_format($conceptos[$index]['monto'], 2);
                            }
                            ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="letraPequena">
                            <?php echo isset($conceptos[$index]) ? $conceptos[$index]['observaciones'] : ''; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <!-- Segunda parte: conceptos adicionales si existen -->
                    <?php if($conceptos_adicionales): ?>
                        <?php for($i = count($filas_fijas); $i < count($conceptos); $i++): ?>
                        <tr class="textoNegro">
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                                <span class="eliminacionEgreso" id_egr="<?php echo $conceptos[$i]['id_egr']; ?>">&times;</span>
                                <?php echo $conceptos[$i]['concepto']; ?>
                            </td>

                            <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia): ?>
                            <td class="letraPequena">
                                <?php echo ($conceptos[$i]['dia'] == $dia) ? '$ '.number_format($conceptos[$i]['monto'], 2) : ''; ?>
                            </td>
                            <?php endforeach; ?>
                            <td class="letraPequena">
                                <?php echo $conceptos[$i]['observaciones']; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <!-- FILAS TOTALES -->
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: #e7f0ff;">TOTAL GASTOS</td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_lunes; ?>">$ <?php echo number_format($sumatorias['lunes'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_martes; ?>">$ <?php echo number_format($sumatorias['martes'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_miercoles; ?>">$ <?php echo number_format($sumatorias['miercoles'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_jueves; ?>">$ <?php echo number_format($sumatorias['jueves'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_viernes; ?>">$ <?php echo number_format($sumatorias['viernes'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_sabado; ?>">$ <?php echo number_format($sumatorias['sabado'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_domingo; ?>">$ <?php echo number_format($sumatorias['domingo'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    </tr>
                    <!-- F FILAS TOTALES -->

                    <!-- Filas negras finales -->
                    <tr style="background-color: black;">
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                    </tr>
                    <tr style="background-color: black;">
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                    </tr>

            <?php endif; ?>
            <!-- F COLEGIATURA DEPOSITO -->

            <!-- TRAMITE EFECTIVO -->
            <?php if($mostrar_tramite && $mostrar_efectivo): ?>

                <!--  -->
                <?php
                // Consulta de egresos
                    $sqlEgresosEfectivo = "SELECT * FROM egreso 
                                        WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                        AND id_pla13 = '$id_pla' 
                                        AND for_egr = 'tramite_efectivo'
                                        ORDER BY id_egr DESC                
                    ";

                // Debugging: imprimir la query
                // echo $sqlEgresosEfectivo;

                $resultEgresos = mysqli_query($db, $sqlEgresosEfectivo);
                $conceptos = array();

                $dias = array(
                    '0' => 'domingo',
                    '1' => 'lunes',
                    '2' => 'martes',
                    '3' => 'miercoles',
                    '4' => 'jueves',
                    '5' => 'viernes',
                    '6' => 'sabado'
                );

                if($resultEgresos) {
                    while($egreso = mysqli_fetch_assoc($resultEgresos)) {
                        $timestamp = strtotime($egreso['fec_egr']);
                        if($timestamp !== false) {
                            $numero_dia = date('w', $timestamp);
                            
                            $conceptos[] = array(
                                'id_egr' => $egreso['id_egr'],     // Añadido
                                'concepto' => trim($egreso['con_egr']),
                                'monto' => is_numeric($egreso['mon_egr']) ? floatval($egreso['mon_egr']) : 0,
                                'dia' => $dias[$numero_dia],
                                'observaciones' => trim($egreso['obs_egr'])
                            );
                        }
                    }
                }

                // Liberar el resultado
                mysqli_free_result($resultEgresos);
                ?>
                
                <?php
                $dia_1 = $inicio;
                $dia_2 = sumarDias($inicio, 1);
                $dia_3 = sumarDias($inicio, 2);
                $dia_4 = sumarDias($inicio, 3);
                $dia_5 = sumarDias($inicio, 4);
                $dia_6 = sumarDias($inicio, 5);
                $dia_7 = sumarDias($inicio, 6);

                // Obtenemos todos los valores
                $valores = [];

                // LUNES
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['lunes'] = $datos['datos']['total'];

                // MARTES
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['martes'] = $datos['datos']['total'];

                // MIÉRCOLES
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['miercoles'] = $datos['datos']['total'];

                // JUEVES
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['jueves'] = $datos['datos']['total'];

                // VIERNES
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['viernes'] = $datos['datos']['total'];

                // SÁBADO
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['sabado'] = $datos['datos']['total'];

                // DOMINGO
                $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['domingo'] = $datos['datos']['total'];

                // Calculamos el total
                $total_cobranza = array_sum($valores);

                  // Armamos el array de filas fijas con los valores obtenidos y sus ligas
                $filas_fijas = [
                    'LUNES' => [
                        'monto' => $valores['lunes'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Efectivo', 'Tramite')
                    ],
                    'MARTES' => [
                        'monto' => $valores['martes'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Efectivo', 'Tramite')
                    ],
                    'MIÉRCOLES' => [
                        'monto' => $valores['miercoles'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Efectivo', 'Tramite')
                    ],
                    'JUEVES' => [
                        'monto' => $valores['jueves'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Efectivo', 'Tramite')
                    ],
                    'VIERNES' => [
                        'monto' => $valores['viernes'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Efectivo', 'Tramite')
                    ],
                    'SÁBADO' => [
                        'monto' => $valores['sabado'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Efectivo', 'Tramite')
                    ],
                    'DOMINGO' => [
                        'monto' => $valores['domingo'],
                        'liga' => obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Efectivo', 'Tramite')
                    ],
                    'TOTAL COBRANZA' => [
                        'monto' => $total_cobranza,
                        'liga' => obtenerLigaCobranza($id_pla, $dia_1, $dia_7, 'Efectivo', 'Tramite')
                    ]
                ];

                // Determinamos si hay más conceptos que filas fijas
                $conceptos_adicionales = count($conceptos) > count($filas_fijas);
                ?>

                <!-- TOTALES -->
                <?php
                // Calcular sumatorias por día
                $sumatorias = array(
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0
                );

                foreach($conceptos as $concepto) {
                    if(isset($concepto['dia']) && isset($concepto['monto'])) {
                        $sumatorias[$concepto['dia']] += floatval($concepto['monto']);
                    }
                }
                ?>
                <!-- F TOTALES -->

                <!--  -->
                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">TRAMITES</td>
                    <td class="letraPequena">EFECTIVO</td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_1); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_2); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_3); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_4); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_5); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_6); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_7); ?></td>
                    <td class="letraPequena"></td>
                </tr>

                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">CONCEPTO</td>
                    <td class="letraPequena">IMPORTE</td>
                    <td class="letraPequena">CONCEPTO DEL GASTO</td>
                    <td class="letraPequena">LUNES</td>
                    <td class="letraPequena">MARTES</td>
                    <td class="letraPequena">MIÉRCOLES</td>
                    <td class="letraPequena">JUEVES</td>
                    <td class="letraPequena">VIERNES</td>
                    <td class="letraPequena">SÁBADO</td>
                    <td class="letraPequena">DOMINGO</td>
                    <td class="letraPequena">OBSERVACIONES</td>
                </tr>

                <!-- Primera parte: las 8 filas fijas -->
                <?php foreach($filas_fijas as $dia => $datos): ?>
                <tr class="textoNegro">
                    <td class="letraPequena" style="background-color: black; color: #05B6DA;"><?php echo $dia; ?></td>
                    <td class="letraPequena">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $datos['liga']; ?>">
                            $ <?php echo number_format($datos['monto'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                        <?php 
                        $index = array_search($dia, array_keys($filas_fijas));
                        if(isset($conceptos[$index])) {
                            echo '<span class="eliminacionEgreso" id_egr="' . $conceptos[$index]['id_egr'] . '">&times;</span>';
                            echo $conceptos[$index]['concepto'];
                        }
                        ?>
                    </td>
                    <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia_columna): ?>
                    <td class="letraPequena">
                        <?php
                        if(isset($conceptos[$index]) && $conceptos[$index]['dia'] == $dia_columna) {
                            echo '$ '.number_format($conceptos[$index]['monto'], 2);
                        }
                        ?>
                    </td>
                    <?php endforeach; ?>
                    <td class="letraPequena">
                        <?php echo isset($conceptos[$index]) ? $conceptos[$index]['observaciones'] : ''; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                    <!-- Segunda parte: conceptos adicionales si existen -->
                <?php if($conceptos_adicionales): ?>
                    <?php for($i = count($filas_fijas); $i < count($conceptos); $i++): ?>
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                            <span class="eliminacionEgreso" id_egr="<?php echo $conceptos[$i]['id_egr']; ?>">&times;</span>
                            <?php echo $conceptos[$i]['concepto']; ?>
                        </td>

                        <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia): ?>
                        <td class="letraPequena">
                            <?php echo ($conceptos[$i]['dia'] == $dia) ? '$ '.number_format($conceptos[$i]['monto'], 2) : ''; ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="letraPequena">
                            <?php echo $conceptos[$i]['observaciones']; ?>
                        </td>
                    </tr>
                    <?php endfor; ?>
                <?php endif; ?>

                <!-- FILAS TOTALES -->
                <tr class="textoNegro">
                    <td class="letraPequena" style="background-color: #e7f0ff;">TOTAL GASTOS</td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['lunes'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['martes'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['miercoles'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['jueves'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['viernes'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['sabado'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Efectivo', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['domingo'], 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                </tr>
                <!-- F FILAS TOTALES -->

                <!-- Filas negras finales -->
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
            <?php endif; ?>
            <!-- F TRAMITE EFECTIVO -->

            <!-- TRAMITE DEPOSITO -->
            <?php if($mostrar_tramite && $mostrar_deposito): ?>
                <!--  -->
                <?php
                // Consulta de egresos
                    $sqlEgresosDeposito = "SELECT * FROM egreso 
                                        WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                        AND id_pla13 = '$id_pla' 
                                        AND for_egr = 'tramite_deposito'
                                        ORDER BY id_egr DESC                
                    ";

                $resultEgresos = mysqli_query($db, $sqlEgresosDeposito);
                $conceptos = array();

                $dias = array(
                    '0' => 'domingo',
                    '1' => 'lunes',
                    '2' => 'martes',
                    '3' => 'miercoles',
                    '4' => 'jueves',
                    '5' => 'viernes',
                    '6' => 'sabado'
                );

                if($resultEgresos) {
                    while($egreso = mysqli_fetch_assoc($resultEgresos)) {
                        $timestamp = strtotime($egreso['fec_egr']);
                        if($timestamp !== false) {
                            $numero_dia = date('w', $timestamp);
                            
                            $conceptos[] = array(
                                'id_egr' => $egreso['id_egr'],
                                'concepto' => trim($egreso['con_egr']),
                                'monto' => is_numeric($egreso['mon_egr']) ? floatval($egreso['mon_egr']) : 0,
                                'dia' => $dias[$numero_dia],
                                'observaciones' => trim($egreso['obs_egr'])
                            );
                        }
                    }
                }

                mysqli_free_result($resultEgresos);
                ?>
                
                <?php
                $dia_1 = $inicio;
                $dia_2 = sumarDias($inicio, 1);
                $dia_3 = sumarDias($inicio, 2);
                $dia_4 = sumarDias($inicio, 3);
                $dia_5 = sumarDias($inicio, 4);
                $dia_6 = sumarDias($inicio, 5);
                $dia_7 = sumarDias($inicio, 6);

                // Obtenemos todos los valores
                $valores = [];

                // LUNES
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['lunes'] = $datos['datos']['total'];

                // MARTES
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['martes'] = $datos['datos']['total'];

                // MIÉRCOLES
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['miercoles'] = $datos['datos']['total'];

                // JUEVES
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['jueves'] = $datos['datos']['total'];

                // VIERNES
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['viernes'] = $datos['datos']['total'];

                // SÁBADO
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['sabado'] = $datos['datos']['total'];

                // DOMINGO
                $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                $datos = obtener_datos_consulta($db, $sql);
                $valores['domingo'] = $datos['datos']['total'];

                // Calculamos el total
                $total_cobranza = array_sum($valores);

                // Armamos el array de filas fijas con los valores obtenidos
                $filas_fijas = [
                    'LUNES' => $valores['lunes'],
                    'MARTES' => $valores['martes'],
                    'MIÉRCOLES' => $valores['miercoles'],
                    'JUEVES' => $valores['jueves'],
                    'VIERNES' => $valores['viernes'],
                    'SÁBADO' => $valores['sabado'],
                    'DOMINGO' => $valores['domingo'],
                    'TOTAL COBRANZA' => $total_cobranza
                ];

                // Determinamos si hay más conceptos que filas fijas
                $conceptos_adicionales = count($conceptos) > count($filas_fijas);
                ?>

                <!-- TOTALES -->
                <?php
                // Calcular sumatorias por día
                $sumatorias = array(
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0
                );

                foreach($conceptos as $concepto) {
                    if(isset($concepto['dia']) && isset($concepto['monto'])) {
                        $sumatorias[$concepto['dia']] += floatval($concepto['monto']);
                    }
                }
                ?>
                <!-- F TOTALES -->


                <!--  -->
                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">TRAMITES</td>
                    <td class="letraPequena">DEPÓSITO</td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_1); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_2); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_3); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_4); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_5); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_6); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_7); ?></td>
                    <td class="letraPequena"></td>
                </tr>

                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">CONCEPTO</td>
                    <td class="letraPequena">IMPORTE</td>
                    <td class="letraPequena">CONCEPTO DEL GASTO</td>
                    <td class="letraPequena">LUNES</td>
                    <td class="letraPequena">MARTES</td>
                    <td class="letraPequena">MIÉRCOLES</td>
                    <td class="letraPequena">JUEVES</td>
                    <td class="letraPequena">VIERNES</td>
                    <td class="letraPequena">SÁBADO</td>
                    <td class="letraPequena">DOMINGO</td>
                    <td class="letraPequena">OBSERVACIONES</td>
                </tr>

                <!-- Primera parte: las 8 filas fijas -->
                <?php foreach($filas_fijas as $dia => $importe): ?>
                <tr class="textoNegro">
                    <td class="letraPequena" style="background-color: black; color: #05B6DA;"><?php echo $dia; ?></td>
                    <td class="letraPequena">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, ${strtolower('dia_'.array_search($dia, array_keys($filas_fijas)) + 1)}, ${strtolower('dia_'.array_search($dia, array_keys($filas_fijas)) + 1)}, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($importe, 2); ?>
                        </a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                        <?php 
                        $index = array_search($dia, array_keys($filas_fijas));
                        if(isset($conceptos[$index])) {
                            echo '<span class="eliminacionEgreso" id_egr="' . $conceptos[$index]['id_egr'] . '">&times;</span>';
                            echo $conceptos[$index]['concepto'];
                        }
                        ?>
                    </td>
                    <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia_columna): ?>
                    <td class="letraPequena">
                        <?php
                        if(isset($conceptos[$index]) && $conceptos[$index]['dia'] == $dia_columna) {
                            echo '$ '.number_format($conceptos[$index]['monto'], 2);
                        }
                        ?>
                    </td>
                    <?php endforeach; ?>
                    <td class="letraPequena">
                        <?php echo isset($conceptos[$index]) ? $conceptos[$index]['observaciones'] : ''; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                <!-- Segunda parte: conceptos adicionales si existen -->
                <?php if($conceptos_adicionales): ?>
                    <?php for($i = count($filas_fijas); $i < count($conceptos); $i++): ?>
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                            <span class="eliminacionEgreso" id_egr="<?php echo $conceptos[$i]['id_egr']; ?>">&times;</span>
                            <?php echo $conceptos[$i]['concepto']; ?>
                        </td>

                        <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia): ?>
                        <td class="letraPequena">
                            <?php echo ($conceptos[$i]['dia'] == $dia) ? '$ '.number_format($conceptos[$i]['monto'], 2) : ''; ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="letraPequena">
                            <?php echo $conceptos[$i]['observaciones']; ?>
                        </td>
                    </tr>
                    <?php endfor; ?>
                <?php endif; ?>
                  
                  <!-- FILAS TOTALES -->
                <tr class="textoNegro">
                    <td class="letraPequena" style="background-color: #e7f0ff;">TOTAL GASTOS</td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    
                    <!-- Total Lunes -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['lunes'], 2); ?>
                        </a>
                    </td>
                    
                    <!-- Total Martes -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['martes'], 2); ?>
                        </a>
                    </td>
                    
                    <!-- Total Miércoles -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['miercoles'], 2); ?>
                        </a>
                    </td>
                    
                    <!-- Total Jueves -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['jueves'], 2); ?>
                        </a>
                    </td>
                    
                    <!-- Total Viernes -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['viernes'], 2); ?>
                        </a>
                    </td>
                    
                    <!-- Total Sábado -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['sabado'], 2); ?>
                        </a>
                    </td>
                    
                    <!-- Total Domingo -->
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Deposito', 'Tramite'); ?>">
                            $ <?php echo number_format($sumatorias['domingo'], 2); ?>
                        </a>
                    </td>
                    
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                </tr>
                <!-- F FILAS TOTALES -->

                <!-- Filas negras finales -->
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
            <?php endif; ?>
            <!-- F TRAMITE DEPOSITO -->

            <!-- INS -->
            <!-- INSCRIPCION EFECTIVO -->
            <?php if($mostrar_inscripcion && $mostrar_efectivo): ?>
                <!--  -->
                <?php
                  // Consulta de egresos
                    $sqlEgresosEfectivo = "SELECT * FROM egreso 
                                        WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                        AND id_pla13 = '$id_pla' 
                                        AND for_egr = 'inscripcion_efectivo'
                                        ORDER BY id_egr DESC                
                    ";

                  $resultEgresos = mysqli_query($db, $sqlEgresosEfectivo);
                  $conceptos = array();

                  $dias = array(
                      '0' => 'domingo',
                      '1' => 'lunes',
                      '2' => 'martes',
                      '3' => 'miercoles',
                      '4' => 'jueves',
                      '5' => 'viernes',
                      '6' => 'sabado'
                  );

                  if($resultEgresos) {
                      while($egreso = mysqli_fetch_assoc($resultEgresos)) {
                          $timestamp = strtotime($egreso['fec_egr']);
                          if($timestamp !== false) {
                              $numero_dia = date('w', $timestamp);
                              
                              $conceptos[] = array(
                                  'id_egr' => $egreso['id_egr'],
                                  'concepto' => trim($egreso['con_egr']),
                                  'monto' => is_numeric($egreso['mon_egr']) ? floatval($egreso['mon_egr']) : 0,
                                  'dia' => $dias[$numero_dia],
                                  'observaciones' => trim($egreso['obs_egr'])
                              );
                          }
                      }
                  }

                  mysqli_free_result($resultEgresos);
                ?>
                  
                <?php
                  $dia_1 = $inicio;
                  $dia_2 = sumarDias($inicio, 1);
                  $dia_3 = sumarDias($inicio, 2);
                  $dia_4 = sumarDias($inicio, 3);
                  $dia_5 = sumarDias($inicio, 4);
                  $dia_6 = sumarDias($inicio, 5);
                  $dia_7 = sumarDias($inicio, 6);

                  // Obtenemos todos los valores
                  $valores = [];

                  // LUNES
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['lunes'] = $datos['datos']['total'];
                  $liga_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Efectivo', 'Inscripción');

                  // MARTES
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['martes'] = $datos['datos']['total'];
                  $liga_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Efectivo', 'Inscripción');

                  // MIÉRCOLES
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['miercoles'] = $datos['datos']['total'];
                  $liga_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Efectivo', 'Inscripción');

                  // JUEVES
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['jueves'] = $datos['datos']['total'];
                  $liga_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Efectivo', 'Inscripción');

                  // VIERNES
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['viernes'] = $datos['datos']['total'];
                  $liga_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Efectivo', 'Inscripción');

                  // SÁBADO
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['sabado'] = $datos['datos']['total'];
                  $liga_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Efectivo', 'Inscripción');

                  // DOMINGO
                  $sql = "SELECT obtener_abonado_inscripcion_efectivo_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['domingo'] = $datos['datos']['total'];
                  $liga_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Efectivo', 'Inscripción');

                  // Calculamos el total
                  $total_cobranza = array_sum($valores);

                  // Armamos el array de filas fijas con los valores obtenidos y sus ligas
                    $liga_total_cobranza = obtenerLigaCobranza($id_pla, $dia_1, $dia_7, 'Efectivo', 'Inscripción');
                    $filas_fijas = [
                        'LUNES' => [
                            'monto' => $valores['lunes'],
                            'liga' => $liga_lunes
                        ],
                        'MARTES' => [
                            'monto' => $valores['martes'],
                            'liga' => $liga_martes
                        ],
                        'MIÉRCOLES' => [
                            'monto' => $valores['miercoles'],
                            'liga' => $liga_miercoles
                        ],
                        'JUEVES' => [
                            'monto' => $valores['jueves'],
                            'liga' => $liga_jueves
                        ],
                        'VIERNES' => [
                            'monto' => $valores['viernes'],
                            'liga' => $liga_viernes
                        ],
                        'SÁBADO' => [
                            'monto' => $valores['sabado'],
                            'liga' => $liga_sabado
                        ],
                        'DOMINGO' => [
                            'monto' => $valores['domingo'],
                            'liga' => $liga_domingo
                        ],
                        'TOTAL COBRANZA' => [
                            'monto' => $total_cobranza,
                            'liga' => $liga_total_cobranza
                        ]
                    ];

                    // Determinamos si hay más conceptos que filas fijas
                    $conceptos_adicionales = count($conceptos) > count($filas_fijas);
                ?>

              <!-- TOTALES -->
                <?php
                // Calcular sumatorias por día
                $sumatorias = array(
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0
                );

                foreach ($conceptos as $concepto) {
                    if (isset($concepto['dia']) && isset($concepto['monto'])) {
                        $sumatorias[$concepto['dia']] += floatval($concepto['monto']);
                    }
                }

                // Generar las URLs para los totales
                $liga_total_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Efectivo', 'Inscripción');
                $liga_total_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Efectivo', 'Inscripción');
                $liga_total_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Efectivo', 'Inscripción');
                $liga_total_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Efectivo', 'Inscripción');
                $liga_total_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Efectivo', 'Inscripción');
                $liga_total_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Efectivo', 'Inscripción');
                $liga_total_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Efectivo', 'Inscripción');
                ?>
                <!-- F TOTALES -->

                <!--  -->
                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">INSCRIPCIONES</td>
                    <td class="letraPequena">EFECTIVO</td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_1); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_2); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_3); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_4); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_5); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_6); ?></td>
                    <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_7); ?></td>
                    <td class="letraPequena"></td>
                </tr>

                <tr class="fondoNegro textoEnde textoNegrito">
                    <td class="letraPequena">CONCEPTO</td>
                    <td class="letraPequena">IMPORTE</td>
                    <td class="letraPequena">CONCEPTO DEL GASTO</td>
                    <td class="letraPequena">LUNES</td>
                    <td class="letraPequena">MARTES</td>
                    <td class="letraPequena">MIÉRCOLES</td>
                    <td class="letraPequena">JUEVES</td>
                    <td class="letraPequena">VIERNES</td>
                    <td class="letraPequena">SÁBADO</td>
                    <td class="letraPequena">DOMINGO</td>
                    <td class="letraPequena">OBSERVACIONES</td>
                </tr>

                <!-- Primera parte: las 8 filas fijas -->
                <?php foreach ($filas_fijas as $dia => $datos) : ?>
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: black; color: #05B6DA;"><?php echo $dia; ?></td>
                        <td class="letraPequena">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $datos['liga']; ?>">
                                $ <?php echo number_format($datos['monto'], 2); ?>
                            </a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                            <?php
                            $index = array_search($dia, array_keys($filas_fijas));
                            if (isset($conceptos[$index])) {
                                echo '<span class="eliminacionEgreso" id_egr="' . $conceptos[$index]['id_egr'] . '">&times;</span>';
                                echo $conceptos[$index]['concepto'];
                            }
                            ?>
                        </td>
                        <?php foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia_columna) : ?>
                            <td class="letraPequena">
                                <?php
                                if (isset($conceptos[$index]) && $conceptos[$index]['dia'] == $dia_columna) {
                                    echo '$ ' . number_format($conceptos[$index]['monto'], 2);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="letraPequena">
                            <?php echo isset($conceptos[$index]) ? $conceptos[$index]['observaciones'] : ''; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Segunda parte: conceptos adicionales si existen -->
                <?php if ($conceptos_adicionales) : ?>
                    <?php for ($i = count($filas_fijas); $i < count($conceptos); $i++) : ?>
                        <tr class="textoNegro">
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                                <span class="eliminacionEgreso" id_egr="<?php echo $conceptos[$i]['id_egr']; ?>">&times;</span>
                                <?php echo $conceptos[$i]['concepto']; ?>
                            </td>

                            <?php foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia) : ?>
                                <td class="letraPequena">
                                    <?php echo ($conceptos[$i]['dia'] == $dia) ? '$ ' . number_format($conceptos[$i]['monto'], 2) : ''; ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="letraPequena">
                                <?php echo $conceptos[$i]['observaciones']; ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>

                <!-- FILAS TOTALES -->
                <tr class="textoNegro">
                    <td class="letraPequena" style="background-color: #e7f0ff;">TOTAL GASTOS</td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_lunes; ?>">$ <?php echo number_format($sumatorias['lunes'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_martes; ?>">$ <?php echo number_format($sumatorias['martes'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_miercoles; ?>">$ <?php echo number_format($sumatorias['miercoles'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_jueves; ?>">$ <?php echo number_format($sumatorias['jueves'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_viernes; ?>">$ <?php echo number_format($sumatorias['viernes'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_sabado; ?>">$ <?php echo number_format($sumatorias['sabado'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;">
                        <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_domingo; ?>">$ <?php echo number_format($sumatorias['domingo'], 2); ?></a>
                    </td>
                    <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                </tr>
                <!-- F FILAS TOTALES -->

                <!-- Filas negras finales -->
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
                <tr style="background-color: black;">
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                    <td class="letraPequena"></td>
                </tr>
            <?php endif; ?>
            <!-- F INSCRIPCION EFECTIVO -->

            <!-- INSCRIPCION DEPOSITO -->
            <?php if($mostrar_inscripcion && $mostrar_deposito): ?>
                <!--  -->
                <?php
                  // Consulta de egresos
                    $sqlEgresosDeposito = "SELECT * FROM egreso 
                                        WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                        AND id_pla13 = '$id_pla' 
                                        AND for_egr = 'inscripcion_deposito'
                                        ORDER BY id_egr DESC                
                    ";

                  $resultEgresos = mysqli_query($db, $sqlEgresosDeposito);
                  $conceptos = array();

                  $dias = array(
                      '0' => 'domingo',
                      '1' => 'lunes',
                      '2' => 'martes',
                      '3' => 'miercoles',
                      '4' => 'jueves',
                      '5' => 'viernes',
                      '6' => 'sabado'
                  );

                  if($resultEgresos) {
                      while($egreso = mysqli_fetch_assoc($resultEgresos)) {
                          $timestamp = strtotime($egreso['fec_egr']);
                          if($timestamp !== false) {
                              $numero_dia = date('w', $timestamp);
                              
                              $conceptos[] = array(
                                  'id_egr' => $egreso['id_egr'],
                                  'concepto' => trim($egreso['con_egr']),
                                  'monto' => is_numeric($egreso['mon_egr']) ? floatval($egreso['mon_egr']) : 0,
                                  'dia' => $dias[$numero_dia],
                                  'observaciones' => trim($egreso['obs_egr'])
                              );
                          }
                      }
                  }

                  mysqli_free_result($resultEgresos);
                ?>
                  
                <?php
                  $dia_1 = $inicio;
                  $dia_2 = sumarDias($inicio, 1);
                  $dia_3 = sumarDias($inicio, 2);
                  $dia_4 = sumarDias($inicio, 3);
                  $dia_5 = sumarDias($inicio, 4);
                  $dia_6 = sumarDias($inicio, 5);
                  $dia_7 = sumarDias($inicio, 6);
                  
                  // Obtenemos todos los valores
                  $valores = [];
                  
                  // LUNES
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['lunes'] = $datos['datos']['total'];
                  $liga_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Deposito', 'Inscripción');
                  
                  // MARTES
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['martes'] = $datos['datos']['total'];
                  $liga_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Deposito', 'Inscripción');
                  
                  // MIÉRCOLES
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['miercoles'] = $datos['datos']['total'];
                  $liga_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Deposito', 'Inscripción');
                  
                  // JUEVES
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['jueves'] = $datos['datos']['total'];
                  $liga_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Deposito', 'Inscripción');
                  
                  // VIERNES
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['viernes'] = $datos['datos']['total'];
                  $liga_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Deposito', 'Inscripción');
                  
                  // SÁBADO
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['sabado'] = $datos['datos']['total'];
                  $liga_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Deposito', 'Inscripción');
                  
                  // DOMINGO
                  $sql = "SELECT obtener_abonado_inscripcion_deposito_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                  $datos = obtener_datos_consulta($db, $sql);
                  $valores['domingo'] = $datos['datos']['total'];
                  $liga_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Deposito', 'Inscripción');
                  
                  // Calculamos el total
                  $total_cobranza = array_sum($valores);
                  
                  // Generamos la liga para el total (de dia_1 a dia_7)
                  $liga_total_cobranza = obtenerLigaCobranza($id_pla, $dia_1, $dia_7, 'Deposito', 'Inscripción');

                  // Armamos el array de filas fijas con los valores obtenidos y sus ligas
                    $filas_fijas = [
                        'LUNES' => [
                            'monto' => $valores['lunes'],
                            'liga' => $liga_lunes
                        ],
                        'MARTES' => [
                            'monto' => $valores['martes'],
                            'liga' => $liga_martes
                        ],
                        'MIÉRCOLES' => [
                            'monto' => $valores['miercoles'],
                            'liga' => $liga_miercoles
                        ],
                        'JUEVES' => [
                            'monto' => $valores['jueves'],
                            'liga' => $liga_jueves
                        ],
                        'VIERNES' => [
                            'monto' => $valores['viernes'],
                            'liga' => $liga_viernes
                        ],
                        'SÁBADO' => [
                            'monto' => $valores['sabado'],
                            'liga' => $liga_sabado
                        ],
                        'DOMINGO' => [
                            'monto' => $valores['domingo'],
                            'liga' => $liga_domingo
                        ],
                        'TOTAL COBRANZA' => [
                            'monto' => $total_cobranza,
                            'liga' => $liga_total_cobranza
                        ]
                    ];

                    // Determinamos si hay más conceptos que filas fijas
                    $conceptos_adicionales = count($conceptos) > count($filas_fijas);
                    ?>

                    <!-- TOTALES -->
                    <?php
                    // Calcular sumatorias por día
                    $sumatorias = array(
                        'lunes' => 0,
                        'martes' => 0,
                        'miercoles' => 0,
                        'jueves' => 0,
                        'viernes' => 0,
                        'sabado' => 0,
                        'domingo' => 0
                    );

                    foreach($conceptos as $concepto) {
                        if(isset($concepto['dia']) && isset($concepto['monto'])) {
                            $sumatorias[$concepto['dia']] += floatval($concepto['monto']);
                        }
                    }

                    // Generar las URLs para los totales
                    $liga_total_lunes = obtenerLigaCobranza($id_pla, $dia_1, $dia_1, 'Deposito', 'Inscripción');
                    $liga_total_martes = obtenerLigaCobranza($id_pla, $dia_2, $dia_2, 'Deposito', 'Inscripción');
                    $liga_total_miercoles = obtenerLigaCobranza($id_pla, $dia_3, $dia_3, 'Deposito', 'Inscripción');
                    $liga_total_jueves = obtenerLigaCobranza($id_pla, $dia_4, $dia_4, 'Deposito', 'Inscripción');
                    $liga_total_viernes = obtenerLigaCobranza($id_pla, $dia_5, $dia_5, 'Deposito', 'Inscripción');
                    $liga_total_sabado = obtenerLigaCobranza($id_pla, $dia_6, $dia_6, 'Deposito', 'Inscripción');
                    $liga_total_domingo = obtenerLigaCobranza($id_pla, $dia_7, $dia_7, 'Deposito', 'Inscripción');
                    ?>
                    <!-- F TOTALES -->

                    <!--  -->
                    <tr class="fondoNegro textoEnde textoNegrito">
                        <td class="letraPequena">INSCRIPCIONES</td>
                        <td class="letraPequena">DEPÓSITO</td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_1); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_2); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_3); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_4); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_5); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_6); ?></td>
                        <td class="letraPequena"><?php echo fechaFormateadaCompacta3($dia_7); ?></td>
                        <td class="letraPequena"></td>
                    </tr>

                    <tr class="fondoNegro textoEnde textoNegrito">
                        <td class="letraPequena">CONCEPTO</td>
                        <td class="letraPequena">IMPORTE</td>
                        <td class="letraPequena">CONCEPTO DEL GASTO</td>
                        <td class="letraPequena">LUNES</td>
                        <td class="letraPequena">MARTES</td>
                        <td class="letraPequena">MIÉRCOLES</td>
                        <td class="letraPequena">JUEVES</td>
                        <td class="letraPequena">VIERNES</td>
                        <td class="letraPequena">SÁBADO</td>
                        <td class="letraPequena">DOMINGO</td>
                        <td class="letraPequena">OBSERVACIONES</td>
                    </tr>
                  
                    <!-- Primera parte: las 8 filas fijas -->
                    <?php foreach($filas_fijas as $dia => $datos): ?>
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: black; color: #05B6DA;"><?php echo $dia; ?></td>
                        <td class="letraPequena">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $datos['liga']; ?>">
                                $ <?php echo number_format($datos['monto'], 2); ?>
                            </a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                            <?php 
                            $index = array_search($dia, array_keys($filas_fijas));
                            if(isset($conceptos[$index])) {
                                echo '<span class="eliminacionEgreso" id_egr="' . $conceptos[$index]['id_egr'] . '">&times;</span>';
                                echo $conceptos[$index]['concepto'];
                            }
                            ?>
                        </td>
                        <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia_columna): ?>
                        <td class="letraPequena">
                            <?php
                            if(isset($conceptos[$index]) && $conceptos[$index]['dia'] == $dia_columna) {
                                echo '$ '.number_format($conceptos[$index]['monto'], 2);
                            }
                            ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="letraPequena">
                            <?php echo isset($conceptos[$index]) ? $conceptos[$index]['observaciones'] : ''; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <!-- Segunda parte: conceptos adicionales si existen -->
                    <?php if($conceptos_adicionales): ?>
                        <?php for($i = count($filas_fijas); $i < count($conceptos); $i++): ?>
                        <tr class="textoNegro">
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                            <td class="letraPequena" style="background-color: #e7f0ff; position: relative;">
                                <span class="eliminacionEgreso" id_egr="<?php echo $conceptos[$i]['id_egr']; ?>">&times;</span>
                                <?php echo $conceptos[$i]['concepto']; ?>
                            </td>

                            <?php foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia): ?>
                            <td class="letraPequena">
                                <?php echo ($conceptos[$i]['dia'] == $dia) ? '$ '.number_format($conceptos[$i]['monto'], 2) : ''; ?>
                            </td>
                            <?php endforeach; ?>
                            <td class="letraPequena">
                                <?php echo $conceptos[$i]['observaciones']; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <!-- FILAS TOTALES -->
                    <tr class="textoNegro">
                        <td class="letraPequena" style="background-color: #e7f0ff;">TOTAL GASTOS</td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_lunes; ?>">$ <?php echo number_format($sumatorias['lunes'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_martes; ?>">$ <?php echo number_format($sumatorias['martes'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_miercoles; ?>">$ <?php echo number_format($sumatorias['miercoles'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_jueves; ?>">$ <?php echo number_format($sumatorias['jueves'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_viernes; ?>">$ <?php echo number_format($sumatorias['viernes'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_sabado; ?>">$ <?php echo number_format($sumatorias['sabado'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;">
                            <a target="_blank" class="text-primary custom-link"  href="<?php echo $liga_total_domingo; ?>">$ <?php echo number_format($sumatorias['domingo'], 2); ?></a>
                        </td>
                        <td class="letraPequena" style="background-color: #e7f0ff;"></td>
                    </tr>
                    <!-- F FILAS TOTALES -->

                    <!-- Filas negras finales -->
                    <tr style="background-color: black;">
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                    </tr>
                    <tr style="background-color: black;">
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                        <td class="letraPequena"></td>
                    </tr>
            <?php endif; ?>
            <!-- F INSCRIPCION DEPOSITO -->
            <!-- F INS -->
               
            </tbody>
          </table>
          <!--  -->
        </div>
        <!-- F TABLA COBRANZA Y GASTOS -->
        

        <!-- TOTALES -->
        <?php
            // Total colegiatura efectivo
            $query_cobranza_efectivo = mysqli_query($db, "SELECT obtener_abonado_colegiatura_efectivo_plantel($id_pla, '$inicio', '$fin') AS total");

            // INSCRIPCIONES
            // Total inscripciones efectivo
            $query_inscripciones_efectivo = mysqli_query($db, "SELECT obtener_abonado_inscripcion_efectivo_plantel($id_pla, '$inicio', '$fin') AS total");
            $row_inscripciones_efectivo = mysqli_fetch_assoc($query_inscripciones_efectivo);
            $total_inscripciones_efectivo = empty($row_inscripciones_efectivo['total']) ? 0 : $row_inscripciones_efectivo['total'];

            // Gastos inscripciones efectivo
            $query_gastos_inscripciones_efectivo = mysqli_query($db, "SELECT SUM(mon_egr) as total FROM egreso 
                                                                WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                                                AND id_pla13 = '$id_pla' 
                                                                AND for_egr = 'inscripcion_efectivo'");
            $row_gastos_inscripciones_efectivo = mysqli_fetch_assoc($query_gastos_inscripciones_efectivo);
            $gastos_inscripciones_efectivo = empty($row_gastos_inscripciones_efectivo['total']) ? 0 : $row_gastos_inscripciones_efectivo['total'];
            $diferencia_inscripciones_efectivo = $total_inscripciones_efectivo - $gastos_inscripciones_efectivo;

            // Total inscripciones depósito
            $query_inscripciones_deposito = mysqli_query($db, "SELECT obtener_abonado_inscripcion_deposito_plantel($id_pla, '$inicio', '$fin') AS total");
            $row_inscripciones_deposito = mysqli_fetch_assoc($query_inscripciones_deposito);
            $total_inscripciones_deposito = empty($row_inscripciones_deposito['total']) ? 0 : $row_inscripciones_deposito['total'];

            // Gastos inscripciones depósito
            $query_gastos_inscripciones_deposito = mysqli_query($db, "SELECT SUM(mon_egr) as total FROM egreso 
                                                                WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                                                AND id_pla13 = '$id_pla' 
                                                                AND for_egr = 'inscripcion_deposito'");
            $row_gastos_inscripciones_deposito = mysqli_fetch_assoc($query_gastos_inscripciones_deposito);
            $gastos_inscripciones_deposito = empty($row_gastos_inscripciones_deposito['total']) ? 0 : $row_gastos_inscripciones_deposito['total'];
            $diferencia_inscripciones_deposito = $total_inscripciones_deposito - $gastos_inscripciones_deposito;

            // Total general de inscripciones
            $total_inscripciones = $total_inscripciones_efectivo + $total_inscripciones_deposito;
            
            $row_cobranza_efectivo = mysqli_fetch_assoc($query_cobranza_efectivo);
            $total_colegiatura_efectivo = empty($row_cobranza_efectivo['total']) ? 0 : $row_cobranza_efectivo['total'];

            // Gastos colegiatura efectivo
            $query_gastos_efectivo = mysqli_query($db, "SELECT SUM(mon_egr) as total FROM egreso 
                                                    WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                                    AND id_pla13 = '$id_pla' 
                                                    AND for_egr = 'colegiatura_efectivo'");
            $row_gastos_efectivo = mysqli_fetch_assoc($query_gastos_efectivo);
            $gastos_colegiatura_efectivo = empty($row_gastos_efectivo['total']) ? 0 : $row_gastos_efectivo['total'];
            $diferencia_efectivo = $total_colegiatura_efectivo - $gastos_colegiatura_efectivo;

            // Total colegiatura depósito
            $query_cobranza_deposito = mysqli_query($db, "SELECT obtener_abonado_colegiatura_deposito_plantel($id_pla, '$inicio', '$fin') AS total");
            $row_cobranza_deposito = mysqli_fetch_assoc($query_cobranza_deposito);
            $total_colegiatura_deposito = empty($row_cobranza_deposito['total']) ? 0 : $row_cobranza_deposito['total'];

            // Gastos colegiatura depósito
            $query_gastos_deposito = mysqli_query($db, "SELECT SUM(mon_egr) as total FROM egreso 
                                                    WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                                    AND id_pla13 = '$id_pla' 
                                                    AND for_egr = 'colegiatura_deposito'");
            $row_gastos_deposito = mysqli_fetch_assoc($query_gastos_deposito);
            $gastos_colegiatura_deposito = empty($row_gastos_deposito['total']) ? 0 : $row_gastos_deposito['total'];
            $diferencia_deposito = $total_colegiatura_deposito - $gastos_colegiatura_deposito;

            // Total general de colegiaturas
            $total_colegiaturas = $total_colegiatura_efectivo + $total_colegiatura_deposito;

            // TRÁMITES
            // Total trámites efectivo
            $query_tramites_efectivo = mysqli_query($db, "SELECT obtener_abonado_tramite_efectivo_plantel($id_pla, '$inicio', '$fin') AS total");
            $row_tramites_efectivo = mysqli_fetch_assoc($query_tramites_efectivo);
            $total_tramites_efectivo = empty($row_tramites_efectivo['total']) ? 0 : $row_tramites_efectivo['total'];

            // Gastos trámites efectivo
            $query_gastos_tramites_efectivo = mysqli_query($db, "SELECT SUM(mon_egr) as total FROM egreso 
                                                                WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                                                AND id_pla13 = '$id_pla' 
                                                                AND for_egr = 'tramite_efectivo'");
            $row_gastos_tramites_efectivo = mysqli_fetch_assoc($query_gastos_tramites_efectivo);
            $gastos_tramites_efectivo = empty($row_gastos_tramites_efectivo['total']) ? 0 : $row_gastos_tramites_efectivo['total'];
            $diferencia_tramites_efectivo = $total_tramites_efectivo - $gastos_tramites_efectivo;

            // Total trámites depósito
            $query_tramites_deposito = mysqli_query($db, "SELECT obtener_abonado_tramite_deposito_plantel($id_pla, '$inicio', '$fin') AS total");
            $row_tramites_deposito = mysqli_fetch_assoc($query_tramites_deposito);
            $total_tramites_deposito = empty($row_tramites_deposito['total']) ? 0 : $row_tramites_deposito['total'];

            // Gastos trámites depósito
            $query_gastos_tramites_deposito = mysqli_query($db, "SELECT SUM(mon_egr) as total FROM egreso 
                                                                WHERE (DATE(fec_egr) BETWEEN '$inicio' AND '$fin') 
                                                                AND id_pla13 = '$id_pla' 
                                                                AND for_egr = 'tramite_deposito'");
            $row_gastos_tramites_deposito = mysqli_fetch_assoc($query_gastos_tramites_deposito);
            $gastos_tramites_deposito = empty($row_gastos_tramites_deposito['total']) ? 0 : $row_gastos_tramites_deposito['total'];
            $diferencia_tramites_deposito = $total_tramites_deposito - $gastos_tramites_deposito;

            // Total general de trámites
            $total_tramites = $total_tramites_efectivo + $total_tramites_deposito;

            // Preparar arrays para JSON
            $datos_colegiaturas = array(
                'total' => $total_colegiaturas,
                'efectivo' => array(
                    'total' => $total_colegiatura_efectivo,
                    'gastos' => $gastos_colegiatura_efectivo,
                    'diferencia' => $diferencia_efectivo
                ),
                'deposito' => array(
                    'total' => $total_colegiatura_deposito,
                    'gastos' => $gastos_colegiatura_deposito,
                    'diferencia' => $diferencia_deposito
                )
            );

            $datos_tramites = array(
                'total' => $total_tramites,
                'efectivo' => array(
                    'total' => $total_tramites_efectivo,
                    'gastos' => $gastos_tramites_efectivo,
                    'diferencia' => $diferencia_tramites_efectivo
                ),
                'deposito' => array(
                    'total' => $total_tramites_deposito,
                    'gastos' => $gastos_tramites_deposito,
                    'diferencia' => $diferencia_tramites_deposito
                )
            );

            $datos_inscripciones = array(
                'total' => $total_inscripciones,
                'efectivo' => array(
                    'total' => $total_inscripciones_efectivo,
                    'gastos' => $gastos_inscripciones_efectivo,
                    'diferencia' => $diferencia_inscripciones_efectivo
                ),
                'deposito' => array(
                    'total' => $total_inscripciones_deposito,
                    'gastos' => $gastos_inscripciones_deposito,
                    'diferencia' => $diferencia_inscripciones_deposito
                )
            );
        ?>

        <script>
            $(document).ready(function() {
                const datosColegiaturas = <?php echo json_encode($datos_colegiaturas); ?>;
                const datosInscripciones = <?php echo json_encode($datos_inscripciones); ?>;
                const datosTramites = <?php echo json_encode($datos_tramites); ?>;

                // Llenar tabla de colegiaturas
                $('#tabla_colegiaturas tr').eq(0).find('td:last').text('$ ' + formatNumber(datosColegiaturas.total));
                $('#tabla_colegiaturas tr').eq(1).find('td:last').text('$ ' + formatNumber(datosColegiaturas.efectivo.total));
                $('#tabla_colegiaturas tr').eq(2).find('td:last').text('$ ' + formatNumber(datosColegiaturas.efectivo.gastos));
                $('#tabla_colegiaturas tr').eq(3).find('td:last').text('$ ' + formatNumber(datosColegiaturas.efectivo.diferencia));
                $('#tabla_colegiaturas tr').eq(4).find('td:last').text('$ ' + formatNumber(datosColegiaturas.deposito.total));
                $('#tabla_colegiaturas tr').eq(5).find('td:last').text('$ ' + formatNumber(datosColegiaturas.deposito.gastos));
                $('#tabla_colegiaturas tr').eq(6).find('td:last').text('$ ' + formatNumber(datosColegiaturas.deposito.diferencia));

                // Llenar tabla de inscripciones
                $('#tabla_inscripciones tr').eq(0).find('td:last').text('$ ' + formatNumber(datosInscripciones.total));
                $('#tabla_inscripciones tr').eq(1).find('td:last').text('$ ' + formatNumber(datosInscripciones.efectivo.total));
                $('#tabla_inscripciones tr').eq(2).find('td:last').text('$ ' + formatNumber(datosInscripciones.efectivo.gastos));
                $('#tabla_inscripciones tr').eq(3).find('td:last').text('$ ' + formatNumber(datosInscripciones.efectivo.diferencia));
                $('#tabla_inscripciones tr').eq(4).find('td:last').text('$ ' + formatNumber(datosInscripciones.deposito.total));
                $('#tabla_inscripciones tr').eq(5).find('td:last').text('$ ' + formatNumber(datosInscripciones.deposito.gastos));
                $('#tabla_inscripciones tr').eq(6).find('td:last').text('$ ' + formatNumber(datosInscripciones.deposito.diferencia));

                // Llenar tabla de trámites
                $('#tabla_tramites tr').eq(0).find('td:last').text('$ ' + formatNumber(datosTramites.total));
                $('#tabla_tramites tr').eq(1).find('td:last').text('$ ' + formatNumber(datosTramites.efectivo.total));
                $('#tabla_tramites tr').eq(2).find('td:last').text('$ ' + formatNumber(datosTramites.efectivo.gastos));
                $('#tabla_tramites tr').eq(3).find('td:last').text('$ ' + formatNumber(datosTramites.efectivo.diferencia));
                $('#tabla_tramites tr').eq(4).find('td:last').text('$ ' + formatNumber(datosTramites.deposito.total));
                $('#tabla_tramites tr').eq(5).find('td:last').text('$ ' + formatNumber(datosTramites.deposito.gastos));
                $('#tabla_tramites tr').eq(6).find('td:last').text('$ ' + formatNumber(datosTramites.deposito.diferencia));
            });

            function formatNumber(number) {
                return new Intl.NumberFormat('es-MX', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number);
            }
        </script>

        <style>
        .table-totales {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table-totales td {
            padding: 8px;
            border: 1px solid #ddd;

        }

        .table-totales td:last-child {
            text-align: right;
        }

        .table-totales .encabezado {
            background-color: black;
            color: white;
            font-weight: bold;
        }

        .table-totales .ultimo {
            margin-bottom: 15px;
            border-bottom: 3px solid #ddd;
        }
        </style>
        <!-- F TOTALES -->
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
    $('#tabla_reporte_azul').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        ordering: false, // Desactiva la ordenación
        // dom: 'Bfrtip',
      dom: 'Bfrtip',
          buttons: [
              {
                  extend: 'excelHtml5',
                  title: 'REPORTE COBRANZA Y GASTOS',
                  className: 'btn-sm btn-success'
              },
          ],
          language: {
              search: 'Buscar' // Cambia el texto del buscador
          },
          info: false // Esto desactiva la información del pie de la tabla

      });
  </script>


  <script>
    $('.eliminacionEgreso').on('click', function() {
        var id_egr = $(this).attr('id_egr');
        var elemento = $(this).closest('tr'); // Asumiendo que quieres eliminar la fila completa

        swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrás recuperar este registro.",
            icon: "warning",
            buttons: ["Cancelar", "Eliminar"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                
              // 
              $.ajax({
                url: 'server/controlador_egreso.php',
                type: 'POST',
                dataType: 'json', // Importante para que jQuery parsee la respuesta como JSON
                data: {
                    accion: 'Eliminar',
                    id_egr: id_egr
                },
                success: function(resp) {
                    if (resp.resultado == 'exito') {
                        swal("¡Registro eliminado!", "Continuar", {
                            icon: "success",
                            button: "Aceptar",
                        });
                        obtener_datos();
                    } else {
                        swal("Error al eliminar el registro.", {
                            icon: "error",
                            button: "Aceptar",
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en la petición AJAX:', textStatus, errorThrown);
                    swal("Error en la comunicación con el servidor.", {
                        icon: "error",
                        button: "Aceptar",
                    });
                }
            });

              // 
            } else {
                swal("El registro no ha sido eliminado.", {
                    button: "Aceptar",
                });
            }
        });
    });

</script>