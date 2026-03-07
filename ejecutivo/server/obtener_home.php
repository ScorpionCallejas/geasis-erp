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
?>

<style> 
    .bg-orange {
        background-color: #F8C851 !important;
        color: #fff !important;
    }
    .bg-light-green {
        background-color: #90EE90 !important;
        color: #fff !important;
    }
    .bg-light-blue {
        background-color: #ADD8E6 !important;
        color: #fff !important;
    }
    .text-red {
        color: red !important;
    }
    .table td, .table th {
        padding: 0;
    }
</style>

<!-- Add New Event MODAL -->
<div class="modal fade" id="event-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header py-3 px-4 border-bottom-0 d-block">
            <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
            <h5 class="modal-title" id="modal-title">Evento</h5>
        </div>
        <div class="modal-body px-4 pb-4 pt-0">
            <form class="needs-validation" name="event-form" id="form-event" novalidate>
            <!-- Campo oculto para el ID del evento (usado para edición) -->
            <input type="hidden" name="id_eve" id="id_eve" value="" />
            <div class="row">
                <!-- Título del Evento -->
                <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Título del Evento</label>
                    <input class="form-control" placeholder="EJEM: REUNIÓN DE SOCIOS..."
                    type="text" name="eve_eve" id="eve_eve" required />
                    <div class="invalid-feedback">Por favor, proporcione un título válido.</div>
                </div>
                </div>
                <!-- Inicia -->
                <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Inicia</label>
                    <input class="form-control" type="date" name="ini_eve" id="ini_eve" required/>
                    <div class="invalid-feedback">Por favor, seleccione una fecha de inicio.</div>
                </div>
                </div>
                <!-- Termina -->
                <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Termina</label>
                    <input class="form-control" type="date" name="fin_eve" id="fin_eve" required />
                    <div class="invalid-feedback">Por favor, seleccione una fecha de término.</div>
                </div>
                </div>
                <!-- Área -->
                <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Área</label>
                    <select class="form-select" name="tip_eve" id="tip_eve" required>
                        <option value="Administrativo">🟠 Administrativo</option>
                        <option value="Comercial">🟢 Comercial</option>
                        <option value="Académico">🔵 Académico</option>
                    </select>
                    <div class="invalid-feedback">Por favor, seleccione un área válida.</div>
                    </div>

                </div>
            </div>
            <!-- Botones de acción -->
            <div class="row mt-2">
                <div class="col-md-6 col-4">
                <button type="button" class="btn btn-danger" id="btn-delete-event">Eliminar</button>
                </div>
                <div class="col-md-6 col-8 text-end">
                <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info" id="btn-save-event">Guardar</button>
                </div>
            </div>
            </form>
        </div>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div>
<!-- end modal-->
 <!-- CALENDARIO -->
<div class="row">
    <div class="col-lg-8">
        <!-- RANKING -->
        <span class="letraMonday">RANKING DEL DÍA</span>
        <div class="table-responsive">

            <table class="table" id="tabla_registros">
                <thead>
                    <tr>
                        <th class="">#</th>
                        <th class="">CDE</th>
                        <th class="">REG ADMINISTRATIVOS</th>
                        <th class="">REG MODULO</th>
                        <th class="">REG ÁREA COMERCIAL</th>
                        <th class="">REG TOTALES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $inicio_aux = date('Y-m-d');
                    $fin_aux = $inicio_aux;
                    
                    $contador = 1;
                    $total_registros_administrativos = 0;
                    $total_registros_modulo = 0;
                    $total_registros_comerciales = 0;
                    $total_registros_totales = 0;
                    $inicio_aux_semana_anterior = restarDias($inicio_aux, 7);
                    $fin_aux_semana_anterior = restarDias($inicio_aux, 1); // Un día antes del inicio_aux
                    
                    $sqlPlanteles = "
                        SELECT p.*,
                        obtener_registros_administrativos_plantel(p.id_pla, '$inicio_aux', '$fin_aux') AS registros_administrativos,
                        obtener_registros_modulo_plantel(p.id_pla, '$inicio_aux', '$fin_aux') AS registros_modulo,
                        obtener_registros_comerciales_plantel3(p.id_pla, '$inicio_aux', '$fin_aux') AS registros_comerciales,
                        (obtener_registros_modulo_plantel(p.id_pla, '$inicio_aux', '$fin_aux') + 
                        obtener_registros_administrativos_plantel(p.id_pla, '$inicio_aux', '$fin_aux') + 
                        obtener_registros_comerciales_plantel3(p.id_pla, '$inicio_aux', '$fin_aux')) AS registros_totales
                        FROM plantel p
                        INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
                        WHERE pe.id_eje = $id_eje
                        ORDER BY registros_totales DESC
                    ";

                    //echo $sqlPlanteles;
                    $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
                    
                    while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
                        $id_pla_aux = $filaPlanteles['id_pla'];
                        $registros_administrativos = $filaPlanteles['registros_administrativos'];
                        $registros_modulo = $filaPlanteles['registros_modulo'];
                        $registros_comerciales = $filaPlanteles['registros_comerciales'];
                        $registros_totales = $filaPlanteles['registros_totales'];
                    
                        $total_registros_administrativos += $registros_administrativos;
                        $total_registros_modulo += $registros_modulo;
                        $total_registros_comerciales += $registros_comerciales;
                        $total_registros_totales += $registros_totales;
                    
                        $color_celda = obtenerColorFila($contador, $registros_totales);
                    ?>
                        <tr>
                            <td class=""><?php echo $contador; ?></td>
                            <td class="" style="<?php echo $color_celda; ?>">🕋 <?php echo $filaPlanteles['nom_pla']; ?></td>
                            <td class="" style="text-align: center;"><?php echo $registros_administrativos; ?></td>
                            <td class="" style="text-align: center;"><?php echo $registros_modulo; ?></td>
                            <td class="" style="text-align: center;"><?php echo $registros_comerciales; ?></td>

                            <td class="" style="<?php echo $color_celda; ?> text-align: center;">
                                <a href="<?php
                                    $url = "registros.php?escala=plantel&id_pla=$id_pla_aux&inicio_aux=$inicio_aux&fin=$fin_aux";
                                    echo $url;
                                ?>" target="_blank">
                                    <?php echo $registros_totales; ?>
                                </a>	
                            
                            </td>
                        </tr>
                    <?php
                        $contador++;
                    }
                    ?>
                    
                    <tr>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                    </tr>
                    <tr>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                    </tr>
                    <tr>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                        <td class="">--</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td class="">--</td>
                        <td class="" style="text-align: center;">Gran Total</td>
                        <td class="" style="text-align: center;"><?php echo $total_registros_administrativos; ?></td>
                        <td class="" style="text-align: center;"><?php echo $total_registros_modulo; ?></td>
                        <td class="" style="text-align: center;"><?php echo $total_registros_comerciales; ?></td>
                        <td class="" style="text-align: center;">
                            <?php echo $total_registros_totales; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- F RANKING -->
        
        <hr>
        
        <!-- EMBUDO -->
        <span class="letraMonday">EMBUDO DE CITAS DEL DÍA</span>
        <?php
            if ($id_pla == "0") {
                $id_eje = $id; // ID del ejecutivo 
                $sqlPlantelesEjecutivo = "SELECT id_pla FROM planteles_ejecutivo WHERE id_eje = $id_eje";
                $resultadoPlantelesEjecutivo = mysqli_query($db, $sqlPlantelesEjecutivo);
                
                $contador = 0;
                while ($filaPlantelesEjecutivo = mysqli_fetch_assoc($resultadoPlantelesEjecutivo)) {
                    $planteles[$contador] = $filaPlantelesEjecutivo['id_pla'];
                    $contador++;
                }
            } else {
                $planteles[0] = $id_pla; 
            }

            // echo $sqlPlantelesEjecutivo;
        ?>

        <!-- CODIGO VA AQUI -->
        <div class="text-center">
        <?php
            $total_citas = 0;
            $total_efectivas = 0;
            $total_registros = 0;

            $planteles_count = count($planteles);
            //var_dump($planteles);
            for ($i = 0; $i < $planteles_count; $i++) {
                $id_plantel = $planteles[$i];
                
                // Obtener todas las métricas en una sola iteración
                $sql_citas = "SELECT obtener_citas_plantel($id_plantel, '$inicio_aux', '$fin_aux') AS total";
                $sql_efectivas = "SELECT obtener_citas_efectivas_plantel($id_plantel, '$inicio_aux', '$fin_aux') AS total";
                $sql_registros = "SELECT obtener_registros_plantel($id_plantel, '$inicio_aux', '$fin_aux') AS total";
                
                //echo $sql_citas;

                $datos_citas = obtener_datos_consulta($db, $sql_citas);
                $datos_efectivas = obtener_datos_consulta($db, $sql_efectivas);
                $datos_registros = obtener_datos_consulta($db, $sql_registros);
                
                $total_citas += intval($datos_citas['datos']['total']);
                $total_efectivas += intval($datos_efectivas['datos']['total']);
                $total_registros += intval($datos_registros['datos']['total']);
            }
        ?>

            <span class="badge" style="background-color: #6c757d; color: #fff; padding: 6px; clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);">
                TOTAL: <span id="conteo_total"><?php echo $total_citas; ?></span>
            </span>

            <span class="badge" style="background-color: #FFC0CB; color: #FF0000; padding: 6px; clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);">
                CITA EFECTIVA: <span id="total_cita_efectiva"><?php echo $total_efectivas; ?></span>
            </span>

            <span class="badge" style="background-color: #00FFFF; color: #000000; padding: 6px; clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);">
                REGISTROS: <span id="conteo_registros"><?php echo $total_registros; ?></span>
            </span>
        </div>
        <!-- F CODIGO VA AQUI -->

        <!-- F EMBUDO -->
    </div>
    <div class="col-lg-4">
        <div class="card" >
            <div class="card-body">
            <div class="d-flex justify-content-center">
            </div>
            <div id="calendar" style="box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);"></div>
            </div> <!-- end card body-->
        </div> <!-- end card -->

    </div> <!-- end col-->
</div>
<!-- F CALENDARIO -->

<hr>

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

    // Obtener las semanas del período
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
                            case 16:
                                echo "Portales";
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

<script>
  "use strict";

  <?php 
    // Consigue la fecha y hora actual
    $currentDate = date('Y-m-d H:i:s');

    if( $id_pla == 0 ){
        $id_pla = $plantel;
    }

    // Query para eventos normales
    $sqlEventos = "
        SELECT 
        e.*,
        NULL as id_gen 
        FROM evento e 
        WHERE e.id_pla = '$id_pla'
    ";

    // Query para eventos de las programaciones (grupo_pago)
    $sqlGrupoPago = "
        SELECT 
            g.id_gen,
            g.nom_gen,
            g.ini_gen,
            g.fin_gen,
            gp.id_gru_pag,
            gp.con_gru_pag,
            gp.ini_gru_pag,
            gp.fin_gru_pag,
            r.id_pla1 as id_pla,
            '' as id_eje,
            'Fecha programada' as tip_eve,
            'Pendiente' as est_eve
        FROM generacion g
        INNER JOIN grupo_pago gp ON g.id_gen = gp.id_gen15
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        WHERE gp.tip_gru_pag = 'Fecha'
        AND r.id_pla1 = '$id_pla'
        ORDER BY g.id_gen, gp.ini_gru_pag
    ";

    $resultadoEventos = mysqli_query($db, $sqlEventos);
    $resultadoGrupoPago = mysqli_query($db, $sqlGrupoPago);

    // Inicio del array de eventos para JavaScript
    echo "var dbEvents = [";
    $first = true;

    // Procesar eventos normales del plantel
    while($filaEventos = mysqli_fetch_assoc($resultadoEventos)) {
    if (!$first) echo ",";
    
    // Determinar clase CSS según tipo de evento
    $className = "";
    switch($filaEventos['tip_eve']) {
        case 'Comercial':
            $className = "bg-light-green";
            break;
        case 'Administrativo':
            $className = "bg-orange";
            break;
        case 'Académico':
            $className = "bg-light-blue";
            break;
    }

    // Verificar estado del evento
    $est_eve = $filaEventos['est_eve'];
    if ($est_eve != 'Completado' && $filaEventos['fin_eve'] < $currentDate) {
        $est_eve = 'Vencido';
        $className .= ' text-red';
    }

    // Generar objeto de evento
    echo "{";
    echo "id: '" . $filaEventos['id_eve'] . "',";
    echo "title: '" . addslashes($filaEventos['eve_eve']) . "',";
    echo "start: '" . $filaEventos['ini_eve'] . "',";
    echo "end: '" . $filaEventos['fin_eve'] . "',";
    echo "className: '" . $className . "',";
    echo "editable: true,";
    echo "extendedProps: {";
    echo "  id_pla: '" . $filaEventos['id_pla'] . "',";
    echo "  id_eje: '" . $filaEventos['id_eje'] . "',";
    echo "  tipo: '" . $filaEventos['tip_eve'] . "',";
    echo "  estatus: '" . $est_eve . "',";
    echo "  id_gen: null";
    echo "}";
    echo "}";
    $first = false;
    }

    // Procesar eventos de grupo_pago
    while($filaGrupoPago = mysqli_fetch_assoc($resultadoGrupoPago)) {
        if (!$first) echo ",";
        
        echo "{";
        echo "id: 'gp_" . $filaGrupoPago['id_gen'] . "_" . $filaGrupoPago['id_gru_pag'] . "',"; // ID compuesto
        echo "title: '" . addslashes($filaGrupoPago['con_gru_pag'] . ' - ' . $filaGrupoPago['nom_gen']) . "',";
        echo "start: '" . $filaGrupoPago['ini_gru_pag'] . "',";
        echo "end: '" . $filaGrupoPago['fin_gru_pag'] . "',";
        echo "className: 'bg-pink',";
        echo "editable: false,";
        echo "extendedProps: {";
        echo "  id_pla: '" . $filaGrupoPago['id_pla'] . "',";
        echo "  id_eje: '" . $filaGrupoPago['id_eje'] . "',";
        echo "  tipo: '" . $filaGrupoPago['tip_eve'] . "',";
        echo "  estatus: '" . $filaGrupoPago['est_eve'] . "',";
        echo "  id_gen: " . $filaGrupoPago['id_gen'] . ",";
        echo "  nom_gen: '" . addslashes($filaGrupoPago['nom_gen']) . "',";
        echo "  ini_gen: '" . $filaGrupoPago['ini_gen'] . "',";
        echo "  fin_gen: '" . $filaGrupoPago['fin_gen'] . "'";
        echo "}";
        echo "}";
        $first = false;
    }
    
    echo "];";
  ?>

(function($) {
    function CalendarApp() {
        this.$body = $("body");
        this.$modalEl = document.getElementById("event-modal");
        this.$modal = new bootstrap.Modal(this.$modalEl, { keyboard: false });

        this.$modalEl.addEventListener('hidden.bs.modal', function () {
            // Restaurar el formulario original si está oculto
            self.$formEvent.show();
            self.$formEvent[0].reset();
            self.$formEvent.removeClass("was-validated");
            // Limpiar cualquier contenido dinámico
            self.$modalEl.querySelector('.modal-content').innerHTML = self.$formEvent.parent().html();
        });

        this.$calendar = $("#calendar");
        this.$formEvent = $("#form-event");
        this.$btnNewEvent = $("#btn-new-event");
        this.$btnDeleteEvent = $("#btn-delete-event");
        this.$modalTitle = $("#modal-title");
        this.$calendarObj = null;
        this.$selectedEvent = null;
    }

    // CalendarApp.prototype.onEventClick = function(info) {
    //     var event = info.event;
    //     // Agregar al inicio de onEventClick
    //     // if (event.id.startsWith('gp_')) {
    //     //     return false; // No permite click en eventos de grupo_pago
    //     // }
        
    //     this.$formEvent[0].reset();
    //     this.$formEvent.removeClass("was-validated");
    //     this.$selectedEvent = event;

    //     var titleWithoutIcon = event.title.replace(/^[^\s]+\s/, '');
    //     $("#id_eve").val(event.id);
    //     $("#eve_eve").val(titleWithoutIcon);
    //     $("#ini_eve").val(event.startStr.split('T')[0]);

    //     var finEveDate = event.end ? new Date(event.end) : new Date(event.start);
    //     finEveDate.setDate(finEveDate.getDate() - 1);
    //     $("#fin_eve").val(finEveDate.toISOString().split('T')[0]);

    //     $("#tip_eve").val(event.extendedProps.tipo);
    //     $("#est_eve").val(event.extendedProps.estatus);

    //     this.$btnDeleteEvent.show();
    //     this.$modalTitle.text("Editar Evento");
    //     this.$modal.show();
    // };
    CalendarApp.prototype.onEventClick = function(info) {
        var event = info.event;
        
        if (event.id.startsWith('gp_')) {
            // Es un evento de grupo_pago
            // Limpiar el modal y esconder todos los campos del formulario
            this.$formEvent.hide();
            
            // Crear la tabla de cronología
            var tableHtml = `
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="table-info">
                                <th>
                                    <strong>
                                        <a href="alumnos.php?id_gen=${event.extendedProps.id_gen}" class="btn-link" target="_blank">
                                            ${event.extendedProps.nom_gen}
                                        </a>
                                    </strong>
                                </th>
                                <th>${event.extendedProps.id_gen}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Inicio grupal</td>
                                <td>${new Date(event.extendedProps.ini_gen).toLocaleDateString('es-MX')}</td>
                            </tr>
                            <tr>
                                <td>Fin grupal</td>
                                <td>${new Date(event.extendedProps.fin_gen).toLocaleDateString('es-MX')}</td>
                            </tr>
            `;

            
            // Obtener todos los eventos de la misma generación
            var generationEvents = this.$calendarObj.getEvents().filter(function(evt) {
                return evt.id.startsWith('gp_') && 
                    evt.extendedProps.id_gen === event.extendedProps.id_gen;
            });
            
            // Ordenar eventos por fecha
            generationEvents.sort(function(a, b) {
                return new Date(a.start) - new Date(b.start);
            });
            
            // Agregar cada evento a la tabla
            generationEvents.forEach(function(evt) {
                tableHtml += `
                    <tr>
                        <td>${evt.title.split(' - ')[0]}</td>
                        <td>${new Date(evt.start).toLocaleDateString('es-MX')}</td>
                    </tr>
                `;
            });
            
            tableHtml += `
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            `;
            
            // Insertar la tabla en el modal
            this.$modalEl.querySelector('.modal-content').innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title">CALENDARIO - CRONOLOGIA DE EVENTOS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                ${tableHtml}
            `;
            
        } else {
            // Es un evento normal - mantener la funcionalidad original
            this.$formEvent.show();
            this.$formEvent[0].reset();
            this.$formEvent.removeClass("was-validated");
            this.$selectedEvent = event;

            var titleWithoutIcon = event.title.replace(/^[^\s]+\s/, '');
            $("#id_eve").val(event.id);
            $("#eve_eve").val(titleWithoutIcon);
            $("#ini_eve").val(event.startStr.split('T')[0]);
            
            var finEveDate = event.end ? new Date(event.end) : new Date(event.start);
            finEveDate.setDate(finEveDate.getDate() - 1);
            $("#fin_eve").val(finEveDate.toISOString().split('T')[0]);
            
            $("#tip_eve").val(event.extendedProps.tipo);
            $("#est_eve").val(event.extendedProps.estatus);
            this.$btnDeleteEvent.show();
        }

        this.$modal.show();
    };

    CalendarApp.prototype.onSelect = function(selectionInfo) {
        this.$formEvent[0].reset();
        this.$formEvent.removeClass("was-validated");
        this.$selectedEvent = null;

        $("#ini_eve").val(selectionInfo.startStr || new Date().toISOString().split('T')[0]);
        $("#fin_eve").val(selectionInfo.endStr || new Date().toISOString().split('T')[0]);
        $("#est_eve").val('Pendiente');

        this.$btnDeleteEvent.hide();
        this.$modalTitle.text("Agregar Evento");
        this.$modal.show();
    };

    CalendarApp.prototype.updateEvent = function(event) {
        var iniEve = event.startStr.split('T')[0];
        var finEve = event.end ? event.endStr.split('T')[0] : iniEve;

        var eventData = {
            id_eve: event.id,
            ini_eve: iniEve,
            fin_eve: finEve
        };

        $.ajax({
            url: 'server/controlador_evento.php',
            type: 'POST',
            data: { action: 'updateDate', data: eventData },
            success: function(response) {
                console.log("Fecha actualizada correctamente: ", response);
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar el evento: ' + error);
            }
        });
    };

    CalendarApp.prototype.init = function() {
        var self = this;

        var calendarEl = this.$calendar[0];
        this.$calendarObj = new FullCalendar.Calendar(calendarEl, {
            
            locale: "es",
            themeSystem: "bootstrap",
            initialView: "dayGridMonth",
            buttonText: {
                today: "Hoy",
                month: "Mes",
                prev: "<",
                next: ">",
                yearList: "Año (Lista)"
            },
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,yearList"
            },
            views: {
                yearList: {
                    type: "listYear",
                    buttonText: "Año (Lista)"
                }
            },

            events: dbEvents,
            // Agregar aquí las nuevas propiedades
            // eventStartEditable: function(info) {
            //     return !info.event.id.startsWith('gp_');
            // },
            // eventDurationEditable: function(info) {
            //     return !info.event.id.startsWith('gp_');
            // },
            // El resto de la configuración existente
            eventDataTransform: function(eventData) {
                var estatus = eventData.extendedProps ? eventData.extendedProps.estatus : eventData.estatus;
                var icon = '';
                switch (estatus) {
                    case 'Pendiente': icon = '⚠️'; break;
                    case 'Completado': icon = '✅'; break;
                    case 'Vencido': icon = '❌'; break;
                    default: icon = '';
                }
                eventData.title = icon + ' ' + eventData.title;
                return eventData;
            },
            editable: true,
            selectable: true,
            dateClick: function(info) {
                self.onSelect(info);
            },
            eventClick: function(info) {
                self.onEventClick(info);
            },
            eventDrop: function(info) {
                self.updateEvent(info.event);
            },
            eventResize: function(info) {
                self.updateEvent(info.event);
            },
        });

        this.$calendarObj.render();

        this.$btnNewEvent.on("click", function(e) {
            e.preventDefault();
            self.onSelect({
                dateStr: new Date().toISOString().split('T')[0]
            });
        });

        this.$formEvent.on("submit", function(e) {
            e.preventDefault();
            var form = self.$formEvent[0];
            if (form.checkValidity()) {
                var id_eve = $("#id_eve").val();
                var ini_eve = $("#ini_eve").val();
                var fin_eve = $("#fin_eve").val();

                var finEveDate = new Date(fin_eve);
                finEveDate.setDate(finEveDate.getDate() + 1);
                var finEveAdjusted = finEveDate.toISOString().split('T')[0];

                var eventData = {
                    id_eve: id_eve,
                    eve_eve: $("#eve_eve").val(),
                    ini_eve: ini_eve,
                    fin_eve: finEveAdjusted,
                    tip_eve: $("#tip_eve").val(),
                    est_eve: $("#est_eve").val()
                };

                var action = id_eve ? "edit" : "add";

                $.ajax({
                    url: 'server/controlador_evento.php',
                    type: 'POST',
                    data: { action: action, data: eventData },
                    success: function(response) {
                        var event = JSON.parse(response);

                        var icon = '';
                        switch (event.estatus) {
                            case 'Pendiente': icon = '⚠️'; break;
                            case 'Completado': icon = '✅'; break;
                            case 'Vencido': icon = '❌'; break;
                        }

                        var eventTitle = icon + ' ' + event.title;

                        if (action === "add") {
                            self.$calendarObj.addEvent({
                                id: event.id_eve,
                                title: eventTitle,
                                start: event.start,
                                end: event.end,
                                allDay: true,
                                className: event.className,
                                extendedProps: {
                                    tipo: event.tipo,
                                    estatus: event.estatus
                                }
                            });
                        } else if (self.$selectedEvent) {
                            self.$selectedEvent.setProp("title", eventTitle);
                            self.$selectedEvent.setStart(event.start);
                            self.$selectedEvent.setEnd(event.end);
                            self.$selectedEvent.setExtendedProp("tipo", event.tipo);
                            self.$selectedEvent.setExtendedProp("estatus", event.estatus);

                            // Agregar animación al actualizar
                            let eventElement = document.querySelector(`[data-event-id="${event.id_eve}"]`);
                            if (eventElement) {
                                eventElement.classList.add("highlight-update");
                                setTimeout(() => {
                                    eventElement.classList.remove("highlight-update");
                                }, 1000);
                            }
                        }

                        setTimeout(() => {
                            self.$modalEl.classList.remove("show");
                            self.$modal.hide();
                            self.$body.removeClass("modal-open");
                            $(".modal-backdrop").remove();
                        }, 300);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al guardar el evento: " + error);
                    }
                });
            } else {
                e.stopPropagation();
                form.classList.add("was-validated");
            }
        });

        this.$btnDeleteEvent.on("click", function(e) {
            if (self.$selectedEvent) {
                var id_eve = self.$selectedEvent.id;
                $.ajax({
                    url: 'server/controlador_evento.php',
                    type: 'POST',
                    data: { action: 'delete', id_eve: id_eve },
                    success: function(response) {
                        self.$selectedEvent.remove();
                        setTimeout(() => {
                            self.$modalEl.classList.remove("show");
                            self.$modal.hide();
                            self.$body.removeClass("modal-open");
                            $(".modal-backdrop").remove();
                        }, 300);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al eliminar el evento: ' + error);
                    }
                });
            }
        });
    };

    $(document).ready(function() {
        var app = new CalendarApp();
        app.init();
    });
})(jQuery);

</script>