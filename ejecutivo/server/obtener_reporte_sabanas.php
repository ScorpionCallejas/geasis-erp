<?php  
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $id_pla = $_POST['id_pla'];

    // Obtener planteles según el caso
    $planteles = array();
    if ($id_pla == "0") {
        $id_eje = $id; // ID del ejecutivo 
        $sqlPlantelesEjecutivo = "SELECT id_pla FROM planteles_ejecutivo WHERE id_eje = $id_eje";
        $resultadoPlantelesEjecutivo = mysqli_query($db, $sqlPlantelesEjecutivo);
        
        while ($filaPlantelesEjecutivo = mysqli_fetch_assoc($resultadoPlantelesEjecutivo)) {
            $planteles[] = $filaPlantelesEjecutivo['id_pla'];
        }
    } else {
        $planteles[] = $id_pla;
    }

    // echo $inicio;
?>


<!-- SABANAS -->
<?php 
    function generarTabla($titulo, $semanas, $cobranzas, $gastos, $id_plantel = null) {
        ob_start();
            $forma = '';
            $tipo = '';
            // Determinar forma y tipo según el título
            if($titulo == 'COLE.EFEC') {
                $tipo = 'Colegiatura';
                $forma = 'Efectivo';
            } else if($titulo == 'TRM.EFEC') {
                $tipo = 'Tramite';
                $forma = 'Efectivo';
            } else if($titulo == 'COLE.CUENTA') {
                $tipo = 'Colegiatura';
                $forma = 'Deposito';
            } else if($titulo == 'TRM.CUENTA') {
                $tipo = 'Tramite';
                $forma = 'Deposito';
            }
        ?>
        
        <table class="table table-bordered table-sm m-0">
            <thead>
                <tr class="bg-primary text-white">
                    <th colspan="4" class="text-center p-0">
                        <?php echo htmlspecialchars($titulo); ?>
                    </th>
                </tr>
                <tr>
                    <th class="p-0">SEM</th>
                    <th class="p-0">COBRANZA</th>
                    <th class="p-0">GASTOS</th>
                    <th class="p-0">SOBRANTE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semanas as $numSemana => $semana): 
                    $cobranza = isset($cobranzas[$numSemana]) ? $cobranzas[$numSemana] : 0;
                    $gasto = isset($gastos[$numSemana]) ? $gastos[$numSemana] : 0;
                    $sobrante = $cobranza - $gasto;
                    
                    // Generar enlace para la cobranza
                    $inicio = $semana['inicio'];
                    $fin = $semana['fin'];
                    $plantel_id = ($id_plantel === 'NACIONAL') ? 'Nacional' : $id_plantel;
                    $liga = obtenerLigaCobranza($plantel_id, $inicio, $fin, $forma, $tipo);
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $numSemana; ?></td>
                    <td class="text-end p-0">
                        <?php if($cobranza > 0): ?>
                            <a href="<?php echo $liga; ?>" target="_blank" class="text-primary custom-link">
                                $ <?php echo number_format($cobranza, 2); ?>
                            </a>
                        <?php else: ?>
                            $ <?php echo number_format($cobranza, 2); ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-end p-0">$ <?php echo number_format($gasto, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($sobrante, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php 
                    // Generar enlace para el total
                    $primera_semana = reset($semanas);
                    $ultima_semana = end($semanas);
                    $liga_total = obtenerLigaCobranza(
                        ($id_plantel === 'NACIONAL') ? 'Nacional' : $id_plantel,
                        $primera_semana['inicio'],
                        $ultima_semana['fin'],
                        $forma,
                        $tipo
                    );
                ?>
                <tr class="table-primary">
                    <td class="text-center p-0">TOTAL</td>
                    <td class="text-end p-0">
                        <a href="<?php echo $liga_total; ?>" target="_blank" class="text-primary custom-link">
                            $ <?php echo number_format(array_sum($cobranzas), 2); ?>
                        </a>
                    </td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($gastos), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas) - array_sum($gastos), 2); ?></td>
                </tr>
            </tbody>
        </table>
        <?php
        return ob_get_clean();
    }
    
    $semanas = obtenerSemanasPeriodo($inicio, $fin);
    // Estructura para almacenar datos por plantel
    $datos_por_plantel = array();
    $totales_nacionales = array(
        'cobranzas_cole_efec' => array(),
        'gastos_cole_efec' => array(),
        'cobranzas_cole_cuenta' => array(),
        'gastos_cole_cuenta' => array(),
        'cobranzas_trm_efec' => array(),
        'gastos_trm_efec' => array(),
        'cobranzas_trm_cuenta' => array(),
        'gastos_trm_cuenta' => array()
    );

    // Obtener datos para cada plantel
    foreach($planteles as $plantel_id) {
        $datos_plantel = array(
            'id' => $plantel_id,
            'cobranzas_cole_efec' => array(),
            'gastos_cole_efec' => array(),
            'cobranzas_cole_cuenta' => array(),
            'gastos_cole_cuenta' => array(),
            'cobranzas_trm_efec' => array(),
            'gastos_trm_efec' => array(),
            'cobranzas_trm_cuenta' => array(),
            'gastos_trm_cuenta' => array(),
            'total_general' => 0
        );

        foreach($semanas as $numSemana => $semana) {
            // Colegiaturas Efectivo
            $query = "SELECT obtener_abonado_colegiatura_efectivo_plantel($plantel_id, '{$semana['inicio']}', '{$semana['fin']}') AS total";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['cobranzas_cole_efec'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
                WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
                AND id_pla13 = '$plantel_id' 
                AND for_egr = 'colegiatura_efectivo'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['gastos_cole_efec'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            // Colegiaturas Cuenta
            $query = "SELECT obtener_abonado_colegiatura_deposito_plantel($plantel_id, '{$semana['inicio']}', '{$semana['fin']}') AS total";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['cobranzas_cole_cuenta'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
                WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
                AND id_pla13 = '$plantel_id' 
                AND for_egr = 'colegiatura_deposito'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['gastos_cole_cuenta'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            // Trámites Efectivo
            $query = "SELECT obtener_abonado_tramite_efectivo_plantel($plantel_id, '{$semana['inicio']}', '{$semana['fin']}') AS total";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['cobranzas_trm_efec'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
                WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
                AND id_pla13 = '$plantel_id' 
                AND for_egr = 'tramite_efectivo'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['gastos_trm_efec'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            // Trámites Cuenta
            $query = "SELECT obtener_abonado_tramite_deposito_plantel($plantel_id, '{$semana['inicio']}', '{$semana['fin']}') AS total";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['cobranzas_trm_cuenta'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
                WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
                AND id_pla13 = '$plantel_id' 
                AND for_egr = 'tramite_deposito'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $datos_plantel['gastos_trm_cuenta'][$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

            // Sumar a los totales nacionales
            if (!isset($totales_nacionales['cobranzas_cole_efec'][$numSemana])) {
                $totales_nacionales['cobranzas_cole_efec'][$numSemana] = 0;
                $totales_nacionales['gastos_cole_efec'][$numSemana] = 0;
                $totales_nacionales['cobranzas_cole_cuenta'][$numSemana] = 0;
                $totales_nacionales['gastos_cole_cuenta'][$numSemana] = 0;
                $totales_nacionales['cobranzas_trm_efec'][$numSemana] = 0;
                $totales_nacionales['gastos_trm_efec'][$numSemana] = 0;
                $totales_nacionales['cobranzas_trm_cuenta'][$numSemana] = 0;
                $totales_nacionales['gastos_trm_cuenta'][$numSemana] = 0;
            }

            $totales_nacionales['cobranzas_cole_efec'][$numSemana] += $datos_plantel['cobranzas_cole_efec'][$numSemana];
            $totales_nacionales['gastos_cole_efec'][$numSemana] += $datos_plantel['gastos_cole_efec'][$numSemana];
            $totales_nacionales['cobranzas_cole_cuenta'][$numSemana] += $datos_plantel['cobranzas_cole_cuenta'][$numSemana];
            $totales_nacionales['gastos_cole_cuenta'][$numSemana] += $datos_plantel['gastos_cole_cuenta'][$numSemana];
            $totales_nacionales['cobranzas_trm_efec'][$numSemana] += $datos_plantel['cobranzas_trm_efec'][$numSemana];
            $totales_nacionales['gastos_trm_efec'][$numSemana] += $datos_plantel['gastos_trm_efec'][$numSemana];
            $totales_nacionales['cobranzas_trm_cuenta'][$numSemana] += $datos_plantel['cobranzas_trm_cuenta'][$numSemana];
            $totales_nacionales['gastos_trm_cuenta'][$numSemana] += $datos_plantel['gastos_trm_cuenta'][$numSemana];
        }

        $datos_por_plantel[$plantel_id] = $datos_plantel;
    }
?>

<div class="container">
    <!-- Sección Nacional -->
    
    <?php 
        if( sizeof( $planteles ) > 1 ){
    ?>
        <div class="row ">
            <div class="col-12">
                <h4 class="text-center mb-3 letraMonday">🇲🇽 TOTALES NACIONALES</h4>
            </div>
            <div class="col-lg-3">
                <?php
                    echo generarTabla('COLE.EFEC', $semanas, $totales_nacionales['cobranzas_cole_efec'], $totales_nacionales['gastos_cole_efec'], 'NACIONAL');
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                    echo generarTabla('TRM.EFEC', $semanas, $totales_nacionales['cobranzas_trm_efec'], $totales_nacionales['gastos_trm_efec'], 'NACIONAL');
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                    echo generarTabla('COLE.CUENTA', $semanas, $totales_nacionales['cobranzas_cole_cuenta'], $totales_nacionales['gastos_cole_cuenta'], 'NACIONAL');
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                    echo generarTabla('TRM.CUENTA', $semanas, $totales_nacionales['cobranzas_trm_cuenta'], $totales_nacionales['gastos_trm_cuenta'], 'NACIONAL');
                ?>
            </div>
        </div>

        <hr class="my-4">
    <?php 
        }
    ?>

    <!-- Sección por Planteles -->
    <?php if (!empty($datos_por_plantel)): ?>
        <?php foreach($datos_por_plantel as $plantel_id => $datos_plantel): ?>
            <div class="row mb-4">
                <div class="col-12">
                <h5 class="text-center mb-3 letraMonday">
                    🕋 CDE  
                    <?php 
                        switch ($plantel_id) {
                            case 2:
                                echo "NAUCALPAN";
                                break;
                            case 3:
                                echo "ECATEPEC";
                                break;
                            case 6:
                                echo "CUAUTITLAN";
                                break;
                            case 8:
                                echo "QUERETARO";
                                break;
                            case 9:
                                echo "PACHUCA";
                                break;
                            case 13:
                                echo "SAN LUIS POTOSI";
                                break;
                        }
                    ?>
                </h5>

                </div>
                <div class="col-lg-3">
                    <?php
                        echo generarTabla('COLE.EFEC', $semanas, $datos_plantel['cobranzas_cole_efec'], $datos_plantel['gastos_cole_efec'], $plantel_id);
                    ?>
                </div>
                <div class="col-lg-3">
                    <?php
                        echo generarTabla('TRM.EFEC', $semanas, $datos_plantel['cobranzas_trm_efec'], $datos_plantel['gastos_trm_efec'], $plantel_id);
                    ?>
                </div>
                <div class="col-lg-3">
                    <?php
                        echo generarTabla('COLE.CUENTA', $semanas, $datos_plantel['cobranzas_cole_cuenta'], $datos_plantel['gastos_cole_cuenta'], $plantel_id);
                    ?>
                </div>
                <div class="col-lg-3">
                    <?php
                        echo generarTabla('TRM.CUENTA', $semanas, $datos_plantel['cobranzas_trm_cuenta'], $datos_plantel['gastos_trm_cuenta'], $plantel_id);
                    ?>
                </div>
            </div>
            <hr class="my-4">
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<hr>

<!-- F SABANAS -->