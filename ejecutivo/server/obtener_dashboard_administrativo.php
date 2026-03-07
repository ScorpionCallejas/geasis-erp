<?php
// DASHBOARD ADMINISTRATIVO - VERSIÓN FINAL CON GUION CHIQUITO
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// RECIBIR PARÁMETROS
$inicio = mysqli_real_escape_string($db, $_POST['inicio']);
$fin = mysqli_real_escape_string($db, $_POST['fin']);
$id_pla_filtro = intval($_POST['id_pla']);

// CALCULAR AYER
$ayer = date('Y-m-d', strtotime($inicio . ' -1 day'));

// FORMATEAR FECHA EN ESPAÑOL
$dias_semana = array('Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb');
$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$fecha_obj = new DateTime($inicio);
$dia_semana = $dias_semana[$fecha_obj->format('w')];
$dia = $fecha_obj->format('d');
$mes = $meses[intval($fecha_obj->format('n'))];
$anio = $fecha_obj->format('Y');
$fechaMostrar = "$dia_semana $dia $mes $anio";

// OBTENER PLANTELES DEL EJECUTIVO
$plantelesEjecutivo = array();

$sqlPlantelesEje = "
    SELECT DISTINCT p.id_pla, p.nom_pla 
    FROM plantel p
    INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
    WHERE pe.id_eje = '$id'
    ORDER BY p.nom_pla
";
$resultadoPlantelesEje = mysqli_query($db, $sqlPlantelesEje);

if(mysqli_num_rows($resultadoPlantelesEje) > 0) {
    while($filaPlantel = mysqli_fetch_assoc($resultadoPlantelesEje)) {
        $plantelesEjecutivo[] = $filaPlantel;
    }
} else {
    $sqlPlantelDefault = "
        SELECT p.id_pla, p.nom_pla 
        FROM plantel p
        INNER JOIN ejecutivo e ON p.id_pla = e.id_pla
        WHERE e.id_eje = '$id'
    ";
    $resultadoDefault = mysqli_query($db, $sqlPlantelDefault);
    if(mysqli_num_rows($resultadoDefault) > 0) {
        while($filaPlantel = mysqli_fetch_assoc($resultadoDefault)) {
            $plantelesEjecutivo[] = $filaPlantel;
        }
    }
}

if($id_pla_filtro > 0) {
    $plantelesEjecutivo = array_filter($plantelesEjecutivo, function($p) use ($id_pla_filtro) {
        return $p['id_pla'] == $id_pla_filtro;
    });
    $plantelesEjecutivo = array_values($plantelesEjecutivo);
}

// CONDICIÓN PLANTELES PARA QUERIES
$condicionPlantel = "";
$condicionPlantelGasto = "";
$idsPlantelesFiltro = array();

if($id_pla_filtro > 0) {
    $condicionPlantel = " AND alumno.id_pla8 = $id_pla_filtro";
    $condicionPlantelGasto = " AND id_pla13 = $id_pla_filtro";
    $idsPlantelesFiltro = array($id_pla_filtro);
} else if(count($plantelesEjecutivo) > 0) {
    $idsPlantelesFiltro = array_column($plantelesEjecutivo, 'id_pla');
    $condicionPlantel = " AND alumno.id_pla8 IN (" . implode(',', $idsPlantelesFiltro) . ")";
    $condicionPlantelGasto = " AND id_pla13 IN (" . implode(',', $idsPlantelesFiltro) . ")";
}

// VERIFICAR SI HAY MÚLTIPLES PLANTELES
$multiplePlanteles = count($plantelesEjecutivo) > 1;

// DATOS POR PLANTEL
$datosPorPlantel = array();
$totales = array(
    'cobranza_hoy' => 0,
    'gastos_hoy' => 0,
    'balance_hoy' => 0,
    'cobranza_ayer' => 0,
    'gastos_ayer' => 0,
    'balance_ayer' => 0
);

foreach($plantelesEjecutivo as $plantel) {
    $id_plantel = $plantel['id_pla'];
    $nom_plantel = $plantel['nom_pla'];
    
    // COBRANZA HOY
    $sqlCobranzaHoy = "
        SELECT SUM(abono_pago.mon_abo_pag) AS total
        FROM alumno
        JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
        JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
        JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
        WHERE abono_pago.fec_abo_pag = '$inicio'
        AND alumno.id_pla8 = $id_plantel
    ";
    $resCobranzaHoy = mysqli_query($db, $sqlCobranzaHoy);
    $cobranzaHoy = floatval(mysqli_fetch_assoc($resCobranzaHoy)['total']);
    
    // COBRANZA AYER
    $sqlCobranzaAyer = "
        SELECT SUM(abono_pago.mon_abo_pag) AS total
        FROM alumno
        JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
        JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
        JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
        WHERE abono_pago.fec_abo_pag = '$ayer'
        AND alumno.id_pla8 = $id_plantel
    ";
    $resCobranzaAyer = mysqli_query($db, $sqlCobranzaAyer);
    $cobranzaAyer = floatval(mysqli_fetch_assoc($resCobranzaAyer)['total']);
    
    // GASTOS HOY
    $sqlGastosHoy = "
        SELECT SUM(mon_egr) AS total
        FROM egreso
        WHERE fec_egr = '$inicio'
        AND id_pla13 = $id_plantel
    ";
    $resGastosHoy = mysqli_query($db, $sqlGastosHoy);
    $gastosHoy = floatval(mysqli_fetch_assoc($resGastosHoy)['total']);
    
    // GASTOS AYER
    $sqlGastosAyer = "
        SELECT SUM(mon_egr) AS total
        FROM egreso
        WHERE fec_egr = '$ayer'
        AND id_pla13 = $id_plantel
    ";
    $resGastosAyer = mysqli_query($db, $sqlGastosAyer);
    $gastosAyer = floatval(mysqli_fetch_assoc($resGastosAyer)['total']);
    
    // EFECTIVO HOY
    $sqlEfectivoHoy = "
        SELECT 
            SUM(CASE WHEN OBTENER_TIPO_ABONO(pago.id_pag) = 'Efectivo' THEN abono_pago.mon_abo_pag ELSE 0 END) AS efectivo,
            SUM(CASE WHEN OBTENER_TIPO_ABONO(pago.id_pag) != 'Efectivo' THEN abono_pago.mon_abo_pag ELSE 0 END) AS deposito
        FROM alumno
        JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
        JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
        JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
        WHERE abono_pago.fec_abo_pag = '$inicio'
        AND alumno.id_pla8 = $id_plantel
    ";
    $resEfectivoHoy = mysqli_query($db, $sqlEfectivoHoy);
    $filaEfectivo = mysqli_fetch_assoc($resEfectivoHoy);
    $efectivoHoy = floatval($filaEfectivo['efectivo']);
    $depositoHoy = floatval($filaEfectivo['deposito']);
    
    // DESGLOSE POR TIPO DE PAGO - PLANTEL
    $sqlTiposPlantel = "
        SELECT 
            pago.tip_pag,
            SUM(abono_pago.mon_abo_pag) AS total
        FROM alumno
        JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
        JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
        JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
        WHERE abono_pago.fec_abo_pag = '$inicio'
        AND alumno.id_pla8 = $id_plantel
        GROUP BY pago.tip_pag
    ";
    $resTiposPlantel = mysqli_query($db, $sqlTiposPlantel);
    $tiposPlantel = array('Colegiatura' => 0, 'Inscripción' => 0, 'Reinscripción' => 0, 'Trámites' => 0);
    while($filaTipo = mysqli_fetch_assoc($resTiposPlantel)) {
        $tipo = $filaTipo['tip_pag'] == 'Otros' ? 'Trámites' : $filaTipo['tip_pag'];
        if(isset($tiposPlantel[$tipo])) {
            $tiposPlantel[$tipo] = floatval($filaTipo['total']);
        }
    }
    
    $balanceHoy = $cobranzaHoy - $gastosHoy;
    $balanceAyer = $cobranzaAyer - $gastosAyer;
    
    $datosPorPlantel[] = array(
        'id_pla' => $id_plantel,
        'nombre' => $nom_plantel,
        'cobranza_hoy' => $cobranzaHoy,
        'gastos_hoy' => $gastosHoy,
        'balance_hoy' => $balanceHoy,
        'cobranza_ayer' => $cobranzaAyer,
        'gastos_ayer' => $gastosAyer,
        'balance_ayer' => $balanceAyer,
        'efectivo_hoy' => $efectivoHoy,
        'deposito_hoy' => $depositoHoy,
        'tipos' => $tiposPlantel
    );
    
    $totales['cobranza_hoy'] += $cobranzaHoy;
    $totales['gastos_hoy'] += $gastosHoy;
    $totales['balance_hoy'] += $balanceHoy;
    $totales['cobranza_ayer'] += $cobranzaAyer;
    $totales['gastos_ayer'] += $gastosAyer;
    $totales['balance_ayer'] += $balanceAyer;
}

// ORDENAR
usort($datosPorPlantel, function($a, $b) {
    return $b['cobranza_hoy'] - $a['cobranza_hoy'];
});

// RANKING USUARIOS (SIN HIPERVÍNCULOS)
$rankingUsuarios = array();
$sqlUsuarios = "
    SELECT 
        abono_pago.res_abo_pag AS responsable,
        SUM(abono_pago.mon_abo_pag) AS total_cobrado
    FROM alumno
    JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
    JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
    JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
    WHERE abono_pago.fec_abo_pag = '$inicio'
    $condicionPlantel
    AND abono_pago.res_abo_pag IS NOT NULL
    AND abono_pago.res_abo_pag != ''
    AND abono_pago.res_abo_pag != '0'
    GROUP BY abono_pago.res_abo_pag
    ORDER BY total_cobrado DESC
    LIMIT 10
";
$resUsuarios = mysqli_query($db, $sqlUsuarios);
while($fila = mysqli_fetch_assoc($resUsuarios)) {
    $rankingUsuarios[] = array(
        'nombre' => $fila['responsable'],
        'monto' => floatval($fila['total_cobrado'])
    );
}

// RANKING POR TIPO DE PAGO - HOY
$rankingTiposHoy = array();
$sqlTiposHoy = "
    SELECT 
        pago.tip_pag,
        SUM(abono_pago.mon_abo_pag) AS total
    FROM alumno
    JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
    JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
    JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
    WHERE abono_pago.fec_abo_pag = '$inicio'
    $condicionPlantel
    GROUP BY pago.tip_pag
    ORDER BY total DESC
";
$resTiposHoy = mysqli_query($db, $sqlTiposHoy);
$totalTiposHoy = 0;
while($fila = mysqli_fetch_assoc($resTiposHoy)) {
    $tipo = $fila['tip_pag'] == 'Otros' ? 'Trámites' : $fila['tip_pag'];
    $monto = floatval($fila['total']);
    $rankingTiposHoy[$tipo] = $monto;
    $totalTiposHoy += $monto;
}

// RANKING POR TIPO DE PAGO - AYER
$rankingTiposAyer = array();
$sqlTiposAyer = "
    SELECT 
        pago.tip_pag,
        SUM(abono_pago.mon_abo_pag) AS total
    FROM alumno
    JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
    JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
    JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
    WHERE abono_pago.fec_abo_pag = '$ayer'
    $condicionPlantel
    GROUP BY pago.tip_pag
    ORDER BY total DESC
";
$resTiposAyer = mysqli_query($db, $sqlTiposAyer);
$totalTiposAyer = 0;
while($fila = mysqli_fetch_assoc($resTiposAyer)) {
    $tipo = $fila['tip_pag'] == 'Otros' ? 'Trámites' : $fila['tip_pag'];
    $monto = floatval($fila['total']);
    $rankingTiposAyer[$tipo] = $monto;
    $totalTiposAyer += $monto;
}

// RANKING POR TIPO Y PLANTEL (SOLO SI HAY MÚLTIPLES PLANTELES)
$rankingTiposPorPlantel = array();
if($multiplePlanteles) {
    foreach($plantelesEjecutivo as $plantel) {
        $id_plantel = $plantel['id_pla'];
        $nom_plantel = $plantel['nom_pla'];
        
        $sqlTiposPlantel = "
            SELECT 
                pago.tip_pag,
                SUM(abono_pago.mon_abo_pag) AS total_hoy
            FROM alumno
            JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
            JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
            JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
            WHERE abono_pago.fec_abo_pag = '$inicio'
            AND alumno.id_pla8 = $id_plantel
            GROUP BY pago.tip_pag
            ORDER BY total_hoy DESC
        ";
        $resTiposPlantel = mysqli_query($db, $sqlTiposPlantel);
        
        $tiposDelPlantel = array();
        while($fila = mysqli_fetch_assoc($resTiposPlantel)) {
            $tipo = $fila['tip_pag'] == 'Otros' ? 'Trámites' : $fila['tip_pag'];
            $tiposDelPlantel[$tipo] = floatval($fila['total_hoy']);
        }
        
        if(array_sum($tiposDelPlantel) > 0) {
            $rankingTiposPorPlantel[] = array(
                'id_pla' => $id_plantel,
                'plantel' => $nom_plantel,
                'tipos' => $tiposDelPlantel
            );
        }
    }
}

// TIPOS DE PAGO POR PLANTEL (STACKED) - SOLO SI HAY MÚLTIPLES
$tiposPagoPorPlantel = array();
if($multiplePlanteles) {
    foreach($plantelesEjecutivo as $plantel) {
        $id_plantel = $plantel['id_pla'];
        $nom_plantel = $plantel['nom_pla'];
        
        $sqlTiposPlantel = "
            SELECT 
                pago.tip_pag,
                SUM(abono_pago.mon_abo_pag) AS total
            FROM alumno
            JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
            JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
            JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
            WHERE abono_pago.fec_abo_pag = '$inicio'
            AND alumno.id_pla8 = $id_plantel
            GROUP BY pago.tip_pag
        ";
        $resTiposPlantel = mysqli_query($db, $sqlTiposPlantel);
        
        $tipos = array('Colegiatura' => 0, 'Inscripción' => 0, 'Reinscripción' => 0, 'Trámites' => 0);
        while($fila = mysqli_fetch_assoc($resTiposPlantel)) {
            $tipo = $fila['tip_pag'] == 'Otros' ? 'Trámites' : $fila['tip_pag'];
            if(isset($tipos[$tipo])) {
                $tipos[$tipo] = floatval($fila['total']);
            }
        }
        
        $tiposPagoPorPlantel[] = array(
            'plantel' => $nom_plantel,
            'tipos' => $tipos
        );
    }
}

// FORMAS DE PAGO POR TIPO
$formasPorTipo = array();
foreach(['Colegiatura', 'Inscripción', 'Reinscripción', 'Trámites'] as $tipoPago) {
    $tipoPagoQuery = $tipoPago == 'Trámites' ? 'Otros' : $tipoPago;
    
    $sqlFormas = "
        SELECT 
            SUM(CASE WHEN OBTENER_TIPO_ABONO(pago.id_pag) = 'Efectivo' THEN abono_pago.mon_abo_pag ELSE 0 END) AS efectivo,
            SUM(CASE WHEN OBTENER_TIPO_ABONO(pago.id_pag) != 'Efectivo' THEN abono_pago.mon_abo_pag ELSE 0 END) AS deposito
        FROM alumno
        JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
        JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
        JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
        WHERE abono_pago.fec_abo_pag = '$inicio'
        AND pago.tip_pag = '$tipoPagoQuery'
        $condicionPlantel
    ";
    $resFormas = mysqli_query($db, $sqlFormas);
    $filaFormas = mysqli_fetch_assoc($resFormas);
    
    $formasPorTipo[$tipoPago] = array(
        'efectivo' => floatval($filaFormas['efectivo']),
        'deposito' => floatval($filaFormas['deposito'])
    );
}

// GASTOS POR CATEGORÍA
$categoriasGastosHoy = array();
$categoriasGastosAyer = array();

$sqlCatHoy = "
    SELECT cat_egr, SUM(mon_egr) AS total
    FROM egreso
    WHERE fec_egr = '$inicio'
    $condicionPlantelGasto
    AND cat_egr IS NOT NULL
    AND cat_egr != ''
    GROUP BY cat_egr
    ORDER BY total DESC
    LIMIT 5
";
$resCatHoy = mysqli_query($db, $sqlCatHoy);
while($fila = mysqli_fetch_assoc($resCatHoy)) {
    $categoriasGastosHoy[$fila['cat_egr']] = floatval($fila['total']);
}

$sqlCatAyer = "
    SELECT cat_egr, SUM(mon_egr) AS total
    FROM egreso
    WHERE fec_egr = '$ayer'
    $condicionPlantelGasto
    AND cat_egr IS NOT NULL
    AND cat_egr != ''
    GROUP BY cat_egr
    ORDER BY total DESC
    LIMIT 5
";
$resCatAyer = mysqli_query($db, $sqlCatAyer);
while($fila = mysqli_fetch_assoc($resCatAyer)) {
    $categoriasGastosAyer[$fila['cat_egr']] = floatval($fila['total']);
}

// ÚLTIMOS 7 DÍAS (TENDENCIA)
$ultimos7Dias = array();
for($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime($inicio . " -$i days"));
    $fecha_obj = new DateTime($fecha);
    $diaSemana = $dias_semana[$fecha_obj->format('w')];
    
    $sqlCobranzaDia = "
        SELECT SUM(abono_pago.mon_abo_pag) AS total
        FROM alumno
        JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
        JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
        JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
        WHERE abono_pago.fec_abo_pag = '$fecha'
        $condicionPlantel
    ";
    $resCobranzaDia = mysqli_query($db, $sqlCobranzaDia);
    $cobranzaDia = floatval(mysqli_fetch_assoc($resCobranzaDia)['total']);
    
    $sqlGastosDia = "
        SELECT SUM(mon_egr) AS total
        FROM egreso
        WHERE fec_egr = '$fecha'
        $condicionPlantelGasto
    ";
    $resGastosDia = mysqli_query($db, $sqlGastosDia);
    $gastosDia = floatval(mysqli_fetch_assoc($resGastosDia)['total']);
    
    $ultimos7Dias[] = array(
        'dia' => $diaSemana,
        'fecha' => $fecha,
        'cobranza' => $cobranzaDia,
        'gastos' => $gastosDia,
        'balance' => $cobranzaDia - $gastosDia
    );
}

// CALCULAR CAMBIOS
$cambioCobranza = $totales['cobranza_hoy'] - $totales['cobranza_ayer'];
$porcCobranza = $totales['cobranza_ayer'] > 0 ? (($cambioCobranza / $totales['cobranza_ayer']) * 100) : 0;

$cambioGastos = $totales['gastos_hoy'] - $totales['gastos_ayer'];
$porcGastos = $totales['gastos_ayer'] > 0 ? (($cambioGastos / $totales['gastos_ayer']) * 100) : 0;

$cambioBalance = $totales['balance_hoy'] - $totales['balance_ayer'];
$porcBalance = $totales['balance_ayer'] != 0 ? (($cambioBalance / abs($totales['balance_ayer'])) * 100) : 0;

// EMOJIS POR TIPO
$emojisTipos = array(
    'Colegiatura' => '💼',
    'Inscripción' => '🎓',
    'Reinscripción' => '🔄',
    'Trámites' => '📋'
);

// MAPEO DE BOLSAS PARA URLs
$mapaBolsas = array(
    'Colegiatura' => 'Colegiatura',
    'Inscripción' => 'Inscripcion',
    'Reinscripción' => 'Reinscripcion',
    'Trámites' => 'Tramite'
);

// CONSTRUIR URLs BASE
$centrosParam = urlencode(implode(',', $idsPlantelesFiltro));
$urlCobranza = "cobranza.php?centros={$centrosParam}&bolsas=Colegiatura,Inscripcion,Reinscripcion,Tramite,Varios&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
$urlGastos = "gastos.php?centros={$centrosParam}&formas=colegiatura_efectivo,colegiatura_deposito,tramite_efectivo,tramite_deposito,reinscripcion_efectivo,reinscripcion_deposito,inscripcion_efectivo,inscripcion_deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";

// OBTENER ÚLTIMAS 30 OBSERVACIONES (SIN FILTRO DE FECHA)
$observaciones = array();

if(count($idsPlantelesFiltro) > 0) {
    $condicionPlantelObs = " WHERE a.id_pla8 IN (" . implode(',', $idsPlantelesFiltro) . ")";
} else {
    $condicionPlantelObs = " WHERE 1=0";
}

$sqlObs = "
    SELECT 
        oar.fec_obs_alu_ram,
        oar.obs_obs_alu_ram,
        oar.res_obs_alu_ram,
        a.nom_alu,
        a.app_alu,
        p.nom_pla
    FROM observacion_alu_ram oar
    INNER JOIN alu_ram ar ON oar.id_alu_ram16 = ar.id_alu_ram
    INNER JOIN alumno a ON ar.id_alu1 = a.id_alu
    INNER JOIN plantel p ON a.id_pla8 = p.id_pla
    $condicionPlantelObs
    ORDER BY oar.fec_obs_alu_ram DESC
    LIMIT 30
";
$resObs = mysqli_query($db, $sqlObs);
if($resObs) {
    while($obs = mysqli_fetch_assoc($resObs)) {
        $observaciones[] = $obs;
    }
}

?>


<style>
.ops-dashboard {
    background: #f5f5f5;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 16px;
}

.ops-header {
    background: #f8f8f8;
    padding: 14px 18px;
    border-radius: 4px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 1px solid #ddd;
}

.ops-title {
    font-size: 20px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 4px;
}

.ops-fecha {
    font-size: 12px;
    color: #666;
    font-weight: 600;
}

/* KPIs */
.kpi-ops-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.kpi-ops-card {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    position: relative;
    transition: all 0.2s;
}

.kpi-ops-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #6c757d;
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.kpi-ops-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: #666;
}

.kpi-icon-link {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    color: #666;
    font-size: 11px;
}

.kpi-icon-link:hover {
    background: #e0e0e0;
    color: #333;
    transform: scale(1.15);
}

.kpi-ops-valor {
    font-size: 22px;
    font-weight: 800;
    color: #2c3e50;
    margin: 4px 0;
}

.kpi-ops-cambio {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 3px;
    width: fit-content;
}

.kpi-ops-cambio.positivo {
    background: rgba(40, 167, 69, 0.15);
    color: #28a745;
}

.kpi-ops-cambio.negativo {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
}

.kpi-ops-cambio.neutro {
    background: rgba(108, 117, 125, 0.15);
    color: #6c757d;
    font-size: 14px;
    font-weight: 400;
}

/* SECTIONS */
.ops-section {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.ops-section-title {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e8e8e8;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.section-icon-link {
    width: 20px;
    height: 20px;
    border-radius: 3px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    color: #666;
    font-size: 10px;
}

.section-icon-link:hover {
    background: #e0e0e0;
    color: #333;
    transform: scale(1.1);
}

/* RANKINGS CON HIPERVÍNCULOS */
.ranking-ops-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 10px;
    margin-bottom: 5px;
    background: #f8f8f8;
    border-radius: 3px;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.ranking-ops-item:hover {
    background: #e8e8e8;
    border-color: #ddd;
    transform: translateX(3px);
}

/* ESTILO PARA ITEMS CLICKEABLES */
.ranking-ops-item-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 10px;
    margin-bottom: 5px;
    background: #f8f8f8;
    border-radius: 3px;
    transition: all 0.2s;
    border: 1px solid transparent;
    text-decoration: none;
    color: inherit;
    cursor: pointer;
}

.ranking-ops-item-link:hover {
    background: #e0f0ff;
    border-color: #2196F3;
    transform: translateX(3px);
    box-shadow: 0 2px 6px rgba(33, 150, 243, 0.2);
}

.ranking-posicion {
    font-size: 16px;
    font-weight: 800;
    min-width: 35px;
    text-align: center;
    color: #2c3e50;
}

.ranking-nombre {
    flex: 1;
    font-size: 11px;
    font-weight: 600;
    color: #555;
    margin: 0 10px;
}

.ranking-monto {
    font-size: 13px;
    font-weight: 800;
    color: #28a745;
}

.ranking-cambio {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    min-width: 60px;
    text-align: center;
}

.ranking-cambio.neutro {
    font-size: 14px;
    font-weight: 400;
}

.ranking-total {
    background: #e8e8e8;
    color: #2c3e50;
    margin-top: 8px;
    border: 1px solid #ddd;
}

.ranking-total:hover {
    background: #d8d8d8;
}

.ranking-total .ranking-monto,
.ranking-total .ranking-nombre {
    color: #2c3e50;
}

/* CHARTS */
.chart-ops-container {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.chart-ops-title {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 10px;
    text-transform: uppercase;
}

/* FEED */
.activity-feed-ops {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 12px;
    max-height: 400px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.feed-ops-header {
    padding: 8px 12px;
    border-bottom: 1px solid #ddd;
    background: #f8f8f8;
    border-radius: 4px 4px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.feed-ops-title {
    font-size: 10px;
    font-weight: 800;
    color: #2c3e50;
    text-transform: uppercase;
}

.feed-ops-badge {
    background: #e0e0e0;
    color: #555;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 9px;
    font-weight: 700;
}

.feed-ops-body {
    flex: 1;
    overflow-y: auto;
}

.feed-ops-body::-webkit-scrollbar {
    width: 4px;
}

.feed-ops-body::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.log-ops-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 8px 10px;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s;
}

.log-ops-item:hover {
    background: #f8f8f8;
}

.log-ops-header {
    display: flex;
    align-items: center;
    gap: 8px;
}

.log-ops-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    background: #999;
}

.log-ops-user {
    font-size: 10px;
    font-weight: 700;
    color: #2c3e50;
    flex: 1;
}

.log-ops-time {
    font-size: 9px;
    color: #999;
    font-family: 'Courier New', monospace;
}

.log-ops-message {
    font-size: 9px;
    color: #666;
    line-height: 1.3;
    padding-left: 32px;
}

.log-ops-meta {
    font-size: 8px;
    color: #999;
    padding-left: 32px;
    display: flex;
    gap: 8px;
}

.feed-ops-empty {
    padding: 30px 15px;
    text-align: center;
    color: #999;
    font-size: 11px;
}

/* PLANTEL CARDS - 2 POR FILA */
.plantel-ops-card {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    position: relative;
    transition: all 0.2s;
}

.plantel-ops-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.plantel-ops-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #bbb;
    border-radius: 4px 4px 0 0;
}

.plantel-ops-header {
    font-size: 13px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e8e8e8;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.plantel-fecha-badge {
    font-size: 8px;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    background: #f0f0f0;
    padding: 2px 6px;
    border-radius: 3px;
    letter-spacing: 0.3px;
}

.plantel-ops-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 8px;
    margin-bottom: 10px;
}

.stat-ops-mini {
    background: #f8f8f8;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #e8e8e8;
    transition: all 0.2s;
}

.stat-ops-mini:hover {
    border-color: #ddd;
    background: #f0f0f0;
}

.stat-ops-mini-label {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    color: #666;
    margin-bottom: 4px;
}

.stat-ops-mini-valor {
    font-size: 14px;
    font-weight: 800;
    color: #2c3e50;
    margin: 2px 0;
}

.stat-ops-mini-cambio {
    font-size: 9px;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 3px;
    width: fit-content;
}

.stat-ops-mini-cambio-neutro {
    font-size: 13px;
    font-weight: 400;
}

.plantel-desglose {
    background: #f8f8f8;
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
    border: 1px solid #e8e8e8;
}

.plantel-desglose-title {
    font-size: 10px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.plantel-tipo-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #e8e8e8;
}

.plantel-tipo-item:last-child {
    border-bottom: none;
}

/* ITEM CLICKEABLE PARA PLANTEL TIPOS */
.plantel-tipo-item-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 6px;
    margin: 0 -6px;
    border-bottom: 1px solid #e8e8e8;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
    border-radius: 3px;
}

.plantel-tipo-item-link:last-child {
    border-bottom: none;
}

.plantel-tipo-item-link:hover {
    background: #e0f0ff;
    border-color: transparent;
    transform: translateX(2px);
}

.plantel-tipo-nombre {
    font-size: 10px;
    font-weight: 600;
    color: #555;
}

.plantel-tipo-monto {
    font-size: 11px;
    font-weight: 800;
    color: #28a745;
}

.plantel-tipo-porc {
    font-size: 9px;
    color: #666;
    margin-left: 5px;
}

.plantel-total {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 2px solid #d0d0d0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.plantel-total-label {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    text-transform: uppercase;
}

.plantel-total-valor {
    font-size: 16px;
    font-weight: 800;
    color: #666;
}
</style>

<div class="ops-dashboard">
    <!-- HEADER -->
    <div class="ops-header">
        <div class="ops-title">📋 ADMINISTRATIVO</div>
        <div class="ops-fecha"><?php echo strtoupper($fechaMostrar); ?></div>
    </div>

    <!-- KPIs CON ÍCONOS -->
    <div class="kpi-ops-grid">
        <div class="kpi-ops-card">
            <div class="kpi-header">
                <div class="kpi-ops-label">💰 Cobranza Total</div>
                <a href="<?php echo $urlCobranza; ?>" class="kpi-icon-link" title="Ver detalle" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="kpi-ops-valor"><?php echo formatearDinero($totales['cobranza_hoy']); ?></div>
            <div class="kpi-ops-cambio <?php echo $totales['cobranza_ayer'] > 0 ? ($cambioCobranza >= 0 ? 'positivo' : 'negativo') : 'neutro'; ?>">
                <?php 
                if($totales['cobranza_ayer'] > 0) {
                    echo ($cambioCobranza >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcCobranza), 1) . '%';
                } else {
                    echo '—';
                }
                ?>
            </div>
        </div>
        
        <div class="kpi-ops-card">
            <div class="kpi-header">
                <div class="kpi-ops-label">💸 Gastos Totales</div>
                <a href="<?php echo $urlGastos; ?>" class="kpi-icon-link" title="Ver detalle" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="kpi-ops-valor"><?php echo formatearDinero($totales['gastos_hoy']); ?></div>
            <div class="kpi-ops-cambio <?php echo $totales['gastos_ayer'] > 0 ? ($cambioGastos >= 0 ? 'negativo' : 'positivo') : 'neutro'; ?>">
                <?php 
                if($totales['gastos_ayer'] > 0) {
                    echo ($cambioGastos >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcGastos), 1) . '%';
                } else {
                    echo '—';
                }
                ?>
            </div>
        </div>
        
        <div class="kpi-ops-card">
            <div class="kpi-header">
                <div class="kpi-ops-label">💎 Balance Neto</div>
            </div>
            <div class="kpi-ops-valor" style="color: <?php echo $totales['balance_hoy'] >= 0 ? '#28a745' : '#dc3545'; ?>">
                <?php echo formatearDinero($totales['balance_hoy']); ?>
            </div>
            <div class="kpi-ops-cambio <?php echo $totales['balance_ayer'] != 0 ? ($cambioBalance >= 0 ? 'positivo' : 'negativo') : 'neutro'; ?>">
                <?php 
                if($totales['balance_ayer'] != 0) {
                    echo ($cambioBalance >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcBalance), 1) . '%';
                } else {
                    echo '—';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- GRID PRINCIPAL - CONDICIONAL -->
    <div class="row">
        <?php if($multiplePlanteles): ?>
        <div class="col-md-4">
            <div class="ops-section">
                <div class="ops-section-title">
                    <span>🏆 Top Planteles</span>
                    <a href="<?php echo $urlCobranza; ?>" class="section-icon-link" title="Ver todos" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <?php 
                $posicion = 1;
                foreach($datosPorPlantel as $plantel): 
                    $cambio = $plantel['cobranza_hoy'] - $plantel['cobranza_ayer'];
                    $porc = $plantel['cobranza_ayer'] > 0 ? (($cambio / $plantel['cobranza_ayer']) * 100) : 0;
                    $medalla = '';
                    if($posicion == 1) $medalla = '🥇';
                    else if($posicion == 2) $medalla = '🥈';
                    else if($posicion == 3) $medalla = '🥉';
                    else $medalla = $posicion . '.';
                    
                    // URL ESPECÍFICA PARA CADA PLANTEL
                    $centroParamPlantel = urlencode($plantel['id_pla']);
                    $urlPlantel = "cobranza.php?centros={$centroParamPlantel}&bolsas=Colegiatura,Inscripcion,Reinscripcion,Tramite,Varios&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
                ?>
                <a href="<?php echo $urlPlantel; ?>" target="_blank" class="ranking-ops-item-link">
                    <span class="ranking-posicion"><?php echo $medalla; ?></span>
                    <span class="ranking-nombre"><?php echo strtoupper(substr($plantel['nombre'], 0, 12)); ?></span>
                    <span class="ranking-monto"><?php echo formatearDinero($plantel['cobranza_hoy']); ?></span>
                    <span class="ranking-cambio <?php echo $plantel['cobranza_ayer'] > 0 ? ($cambio >= 0 ? 'positivo' : 'negativo') : 'neutro'; ?>" style="background: <?php echo $plantel['cobranza_ayer'] > 0 ? ($cambio >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)') : 'rgba(108, 117, 125, 0.15)'; ?>; color: <?php echo $plantel['cobranza_ayer'] > 0 ? ($cambio >= 0 ? '#28a745' : '#dc3545') : '#6c757d'; ?>">
                        <?php 
                        if($plantel['cobranza_ayer'] > 0) {
                            echo ($cambio >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porc), 0) . '%';
                        } else {
                            echo '—';
                        }
                        ?>
                    </span>
                </a>
                <?php 
                    $posicion++;
                endforeach; 
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="<?php echo $multiplePlanteles ? 'col-md-4' : 'col-md-6'; ?>">
            <div class="ops-section">
                <div class="ops-section-title">
                    <span>👤 Top Usuarios</span>
                    <a href="<?php echo $urlCobranza; ?>" class="section-icon-link" title="Ver todos" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <?php 
                $posicion = 1;
                foreach($rankingUsuarios as $usuario): 
                    $medalla = '';
                    if($posicion == 1) $medalla = '🥇';
                    else if($posicion == 2) $medalla = '🥈';
                    else if($posicion == 3) $medalla = '🥉';
                    else $medalla = $posicion . '.';
                ?>
                <!-- SIN HIPERVÍNCULO PORQUE NO FILTRA POR USUARIO -->
                <div class="ranking-ops-item">
                    <span class="ranking-posicion"><?php echo $medalla; ?></span>
                    <span class="ranking-nombre"><?php echo strtoupper(substr($usuario['nombre'], 0, 12)); ?></span>
                    <span class="ranking-monto"><?php echo formatearDinero($usuario['monto']); ?></span>
                </div>
                <?php 
                    $posicion++;
                endforeach; 
                ?>
            </div>
        </div>

        <div class="<?php echo $multiplePlanteles ? 'col-md-4' : 'col-md-6'; ?>">
            <div class="ops-section">
                <div class="ops-section-title">
                    <span>💼 Ranking por Bolsa</span>
                    <a href="<?php echo $urlCobranza; ?>" class="section-icon-link" title="Ver detalle" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <?php 
                $posicion = 1;
                arsort($rankingTiposHoy);
                foreach($rankingTiposHoy as $tipo => $montoHoy): 
                    $montoAyer = isset($rankingTiposAyer[$tipo]) ? $rankingTiposAyer[$tipo] : 0;
                    $cambio = $montoHoy - $montoAyer;
                    $porc = $montoAyer > 0 ? (($cambio / $montoAyer) * 100) : 0;
                    
                    $medalla = '';
                    if($posicion == 1) $medalla = '🥇';
                    else if($posicion == 2) $medalla = '🥈';
                    else if($posicion == 3) $medalla = '🥉';
                    else $medalla = $posicion . '.';
                    
                    $emoji = isset($emojisTipos[$tipo]) ? $emojisTipos[$tipo] : '📝';
                    
                    // URL ESPECÍFICA PARA CADA TIPO DE BOLSA
                    $bolsaUrl = isset($mapaBolsas[$tipo]) ? $mapaBolsas[$tipo] : 'Varios';
                    $urlBolsa = "cobranza.php?centros={$centrosParam}&bolsas={$bolsaUrl}&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
                ?>
                <a href="<?php echo $urlBolsa; ?>" target="_blank" class="ranking-ops-item-link">
                    <span class="ranking-posicion"><?php echo $medalla; ?></span>
                    <span class="ranking-nombre"><?php echo $emoji . ' ' . strtoupper(substr($tipo, 0, 10)); ?></span>
                    <span class="ranking-monto"><?php echo formatearDinero($montoHoy); ?></span>
                    <span class="ranking-cambio <?php echo $montoAyer > 0 ? ($cambio >= 0 ? 'positivo' : 'negativo') : 'neutro'; ?>" style="background: <?php echo $montoAyer > 0 ? ($cambio >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)') : 'rgba(108, 117, 125, 0.15)'; ?>; color: <?php echo $montoAyer > 0 ? ($cambio >= 0 ? '#28a745' : '#dc3545') : '#6c757d'; ?>">
                        <?php 
                        if($montoAyer > 0) {
                            echo ($cambio >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porc), 0) . '%';
                        } else {
                            echo '—';
                        }
                        ?>
                    </span>
                </a>
                <?php 
                    $posicion++;
                endforeach; 
                
                $cambioTotal = $totalTiposHoy - $totalTiposAyer;
                $porcTotal = $totalTiposAyer > 0 ? (($cambioTotal / $totalTiposAyer) * 100) : 0;
                ?>
                <div class="ranking-ops-item ranking-total">
                    <span class="ranking-posicion">💰</span>
                    <span class="ranking-nombre">TOTAL</span>
                    <span class="ranking-monto"><?php echo formatearDinero($totalTiposHoy); ?></span>
                    <span class="ranking-cambio" style="background: rgba(255,255,255,0.2); color: #fff;">
                        <?php 
                        if($totalTiposAyer > 0) {
                            echo ($cambioTotal >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcTotal), 0) . '%';
                        } else {
                            echo '—';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- RANKING POR BOLSA Y PLANTEL (SOLO SI HAY MÚLTIPLES) -->
    <?php if($multiplePlanteles && count($rankingTiposPorPlantel) > 0): ?>
    <div class="row">
        <?php foreach($rankingTiposPorPlantel as $rankinPlantel): 
            $centroParam = urlencode($rankinPlantel['id_pla']);
            $urlPlantelCobranza = "cobranza.php?centros={$centroParam}&bolsas=Colegiatura,Inscripcion,Reinscripcion,Tramite,Varios&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
        ?>
        <div class="col-md-4">
            <div class="ops-section">
                <div class="ops-section-title">
                    <span>🕋 <?php echo strtoupper(substr($rankinPlantel['plantel'], 0, 15)); ?></span>
                    <a href="<?php echo $urlPlantelCobranza; ?>" class="section-icon-link" title="Ver plantel" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <?php 
                $posicion = 1;
                arsort($rankinPlantel['tipos']);
                foreach($rankinPlantel['tipos'] as $tipo => $monto):
                    if($monto > 0):
                        $medalla = '';
                        if($posicion == 1) $medalla = '🥇';
                        else if($posicion == 2) $medalla = '🥈';
                        else if($posicion == 3) $medalla = '🥉';
                        else $medalla = $posicion . '.';
                        
                        $emoji = isset($emojisTipos[$tipo]) ? $emojisTipos[$tipo] : '📝';
                        
                        // URL PARA BOLSA ESPECÍFICA DE ESTE PLANTEL
                        $bolsaUrl = isset($mapaBolsas[$tipo]) ? $mapaBolsas[$tipo] : 'Varios';
                        $urlBolsaPlantel = "cobranza.php?centros={$centroParam}&bolsas={$bolsaUrl}&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
                ?>
                <a href="<?php echo $urlBolsaPlantel; ?>" target="_blank" class="ranking-ops-item-link">
                    <span class="ranking-posicion"><?php echo $medalla; ?></span>
                    <span class="ranking-nombre"><?php echo $emoji . ' ' . strtoupper(substr($tipo, 0, 10)); ?></span>
                    <span class="ranking-monto"><?php echo formatearDinero($monto); ?></span>
                </a>
                <?php 
                        $posicion++;
                    endif;
                endforeach;
                
                $totalPlantelBolsa = array_sum($rankinPlantel['tipos']);
                ?>
                <div class="ranking-ops-item ranking-total">
                    <span class="ranking-posicion">💰</span>
                    <span class="ranking-nombre">TOTAL</span>
                    <span class="ranking-monto"><?php echo formatearDinero($totalPlantelBolsa); ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- FEED + GRÁFICAS -->
    <div class="row">
        
        <div class="col-md-4">
            <div class="activity-feed-ops">
                <div class="feed-ops-header">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span>🔔</span>
                        <span class="feed-ops-title">Últimas Observaciones</span>
                        <span class="feed-ops-badge"><?php echo count($observaciones); ?></span>
                    </div>
                </div>
                <div class="feed-ops-body">
                    <?php if(count($observaciones) > 0): ?>
                        <?php 
                        $fechaActual = null;
                        foreach($observaciones as $obs): 
                            $nombreCompleto = trim($obs['res_obs_alu_ram']);
                            $iniciales = '';
                            if(!empty($nombreCompleto)) {
                                $partes = explode(' ', $nombreCompleto);
                                $iniciales = strtoupper(substr($partes[0], 0, 1));
                                if(count($partes) > 1) {
                                    $iniciales .= strtoupper(substr($partes[count($partes)-1], 0, 1));
                                }
                            } else {
                                $iniciales = 'SN';
                            }
                            
                            $fechaObj = new DateTime($obs['fec_obs_alu_ram']);
                            $fechaObservacion = $fechaObj->format('Y-m-d');
                            $horaMin = $fechaObj->format('H:i');
                            $alumnoNombre = trim($obs['nom_alu'] . ' ' . $obs['app_alu']);
                            
                            $diaNum = $fechaObj->format('d');
                            $mesNum = intval($fechaObj->format('n'));
                            $mesNombre = $meses[$mesNum];
                            $diaSemana = $dias_semana[$fechaObj->format('w')];
                            $fechaFormateada = "$diaSemana $diaNum $mesNombre";
                            
                            if($fechaObservacion != $fechaActual):
                                $fechaActual = $fechaObservacion;
                        ?>
                        <div style="padding: 6px 10px; background: #f0f0f0; border-bottom: 1px solid #ddd; font-size: 9px; font-weight: 700; color: #666; text-transform: uppercase;">
                            📅 <?php echo $fechaFormateada; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="log-ops-item">
                            <div class="log-ops-header">
                                <div class="log-ops-avatar"><?php echo $iniciales; ?></div>
                                <div class="log-ops-user"><?php echo strtoupper($nombreCompleto ?: 'SIN NOMBRE'); ?></div>
                                <div class="log-ops-time"><?php echo $horaMin; ?></div>
                            </div>
                            <div class="log-ops-message"><?php echo htmlspecialchars($obs['obs_obs_alu_ram']); ?></div>
                            <div class="log-ops-meta">
                                <span>📚 <?php echo htmlspecialchars($alumnoNombre); ?></span>
                                <span>🏫 <?php echo htmlspecialchars($obs['nom_pla']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="feed-ops-empty">Sin observaciones registradas</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="chart-ops-container">
                <div class="chart-ops-title">📈 Tendencia Últimos 7 Días</div>
                <div id="chartTendencia7Dias" style="height: 200px;"></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-ops-container">
                        <div class="chart-ops-title">🎯 Tipos de Pago</div>
                        <div id="chartDonutTipos" style="height: 200px;"></div>
                    </div>
                </div>
                
                <?php if($multiplePlanteles): ?>
                <div class="col-md-6">
                    <div class="chart-ops-container">
                        <div class="chart-ops-title">📊 Tipos por Plantel</div>
                        <div id="chartStackedTipos" style="height: 200px;"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="chart-ops-container">
                <div class="chart-ops-title">💵 Efectivo vs 🏦 Depósito</div>
                <div id="chartFormasPorTipo" style="height: 200px;"></div>
            </div>

            <div class="chart-ops-container">
                <div class="chart-ops-title">💸 Gastos por Categoría</div>
                <div id="chartGastosCategorias" style="height: 200px;"></div>
            </div>
        </div>
    </div>

    <!-- DETALLE PLANTELES - 2 POR FILA -->
    <div class="row">
    <?php foreach($datosPorPlantel as $datos): 
        $cambioC = $datos['cobranza_hoy'] - $datos['cobranza_ayer'];
        $porcC = $datos['cobranza_ayer'] > 0 ? (($cambioC / $datos['cobranza_ayer']) * 100) : 0;
        
        $cambioG = $datos['gastos_hoy'] - $datos['gastos_ayer'];
        $porcG = $datos['gastos_ayer'] > 0 ? (($cambioG / $datos['gastos_ayer']) * 100) : 0;
        
        $cambioB = $datos['balance_hoy'] - $datos['balance_ayer'];
        $porcB = $datos['balance_ayer'] != 0 ? (($cambioB / abs($datos['balance_ayer'])) * 100) : 0;
        
        $porcEfectivo = ($datos['efectivo_hoy'] + $datos['deposito_hoy']) > 0 ? (($datos['efectivo_hoy'] / ($datos['efectivo_hoy'] + $datos['deposito_hoy'])) * 100) : 0;
        
        $centroParam = urlencode($datos['id_pla']);
        $urlPlantelCobranza = "cobranza.php?centros={$centroParam}&bolsas=Colegiatura,Inscripcion,Reinscripcion,Tramite,Varios&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
        $urlPlantelGastos = "gastos.php?centros={$centroParam}&formas=colegiatura_efectivo,colegiatura_deposito,tramite_efectivo,tramite_deposito,reinscripcion_efectivo,reinscripcion_deposito,inscripcion_efectivo,inscripcion_deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
    ?>
    <div class="col-md-6">
        <div class="plantel-ops-card">
            <div class="plantel-ops-header">
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <span>🕋 <?php echo strtoupper($datos['nombre']); ?></span>
                    <span class="plantel-fecha-badge"><?php echo $fechaMostrar; ?></span>
                </div>
                <a href="<?php echo $urlPlantelCobranza; ?>" class="section-icon-link" title="Ver cobranza" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            
            <div class="plantel-ops-stats">
                <a href="<?php echo $urlPlantelCobranza; ?>" target="_blank" style="text-decoration: none; color: inherit;">
                    <div class="stat-ops-mini" style="cursor: pointer;">
                        <div class="stat-ops-mini-label">💰 Cobranza</div>
                        <div class="stat-ops-mini-valor" style="color: #28a745;"><?php echo formatearDinero($datos['cobranza_hoy']); ?></div>
                        <div class="stat-ops-mini-cambio <?php echo $datos['cobranza_ayer'] == 0 ? 'stat-ops-mini-cambio-neutro' : ''; ?>" style="background: <?php echo $datos['cobranza_ayer'] > 0 ? ($cambioC >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)') : 'rgba(108, 117, 125, 0.15)'; ?>; color: <?php echo $datos['cobranza_ayer'] > 0 ? ($cambioC >= 0 ? '#28a745' : '#dc3545') : '#6c757d'; ?>">
                            <?php 
                            if($datos['cobranza_ayer'] > 0) {
                                echo ($cambioC >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcC), 1) . '%';
                            } else {
                                echo '—';
                            }
                            ?>
                        </div>
                    </div>
                </a>
                
                <a href="<?php echo $urlPlantelGastos; ?>" target="_blank" style="text-decoration: none; color: inherit;">
                    <div class="stat-ops-mini" style="cursor: pointer;">
                        <div class="stat-ops-mini-label">💸 Gastos</div>
                        <div class="stat-ops-mini-valor" style="color: #dc3545;"><?php echo formatearDinero($datos['gastos_hoy']); ?></div>
                        <div class="stat-ops-mini-cambio <?php echo $datos['gastos_ayer'] == 0 ? 'stat-ops-mini-cambio-neutro' : ''; ?>" style="background: <?php echo $datos['gastos_ayer'] > 0 ? ($cambioG >= 0 ? 'rgba(220, 53, 69, 0.15)' : 'rgba(40, 167, 69, 0.15)') : 'rgba(108, 117, 125, 0.15)'; ?>; color: <?php echo $datos['gastos_ayer'] > 0 ? ($cambioG >= 0 ? '#dc3545' : '#28a745') : '#6c757d'; ?>">
                            <?php 
                            if($datos['gastos_ayer'] > 0) {
                                echo ($cambioG >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcG), 1) . '%';
                            } else {
                                echo '—';
                            }
                            ?>
                        </div>
                    </div>
                </a>
                
                <div class="stat-ops-mini">
                    <div class="stat-ops-mini-label">💎 Balance</div>
                    <div class="stat-ops-mini-valor" style="color: <?php echo $datos['balance_hoy'] >= 0 ? '#28a745' : '#dc3545'; ?>">
                        <?php echo formatearDinero($datos['balance_hoy']); ?>
                    </div>
                    <div class="stat-ops-mini-cambio <?php echo $datos['balance_ayer'] == 0 ? 'stat-ops-mini-cambio-neutro' : ''; ?>" style="background: <?php echo $datos['balance_ayer'] != 0 ? ($cambioB >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)') : 'rgba(108, 117, 125, 0.15)'; ?>; color: <?php echo $datos['balance_ayer'] != 0 ? ($cambioB >= 0 ? '#28a745' : '#dc3545') : '#6c757d'; ?>">
                        <?php 
                        if($datos['balance_ayer'] != 0) {
                            echo ($cambioB >= 0 ? '▲' : '▼') . ' ' . number_format(abs($porcB), 1) . '%';
                        } else {
                            echo '—';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="stat-ops-mini">
                    <div class="stat-ops-mini-label">💵 Efectivo</div>
                    <div class="stat-ops-mini-valor" style="color: #6c757d;"><?php echo formatearDinero($datos['efectivo_hoy']); ?></div>
                    <div class="stat-ops-mini-cambio" style="background: rgba(108, 117, 125, 0.15); color: #6c757d;">
                        <?php echo number_format($porcEfectivo, 1); ?>%
                    </div>
                </div>
                
                <div class="stat-ops-mini">
                    <div class="stat-ops-mini-label">🏦 Depósito</div>
                    <div class="stat-ops-mini-valor" style="color: #28a745;"><?php echo formatearDinero($datos['deposito_hoy']); ?></div>
                    <div class="stat-ops-mini-cambio" style="background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <?php echo number_format(100 - $porcEfectivo, 1); ?>%
                    </div>
                </div>
            </div>

            <div class="plantel-desglose">
                <div class="plantel-desglose-title">📊 Desglose por Bolsa</div>
                <?php 
                $totalPlantel = array_sum($datos['tipos']);
                foreach($datos['tipos'] as $tipo => $monto): 
                    if($monto > 0):
                        $porcTipo = $totalPlantel > 0 ? (($monto / $totalPlantel) * 100) : 0;
                        $emoji = isset($emojisTipos[$tipo]) ? $emojisTipos[$tipo] : '📝';
                        
                        // URL PARA CADA BOLSA DE ESTE PLANTEL
                        $bolsaUrl = isset($mapaBolsas[$tipo]) ? $mapaBolsas[$tipo] : 'Varios';
                        $urlBolsaPlantel = "cobranza.php?centros={$centroParam}&bolsas={$bolsaUrl}&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}";
                ?>
                <a href="<?php echo $urlBolsaPlantel; ?>" target="_blank" class="plantel-tipo-item-link">
                    <span class="plantel-tipo-nombre"><?php echo $emoji . ' ' . strtoupper($tipo); ?></span>
                    <div>
                        <span class="plantel-tipo-monto"><?php echo formatearDinero($monto); ?></span>
                        <span class="plantel-tipo-porc">(<?php echo number_format($porcTipo, 1); ?>%)</span>
                    </div>
                </a>
                <?php 
                    endif;
                endforeach; 
                ?>
                <div class="plantel-total">
                    <span class="plantel-total-label">💰 Total Plantel</span>
                    <span class="plantel-total-valor"><?php echo formatearDinero($totalPlantel); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>

<!-- GOOGLE CHARTS -->
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawChartTendencia7Dias();
        drawChartDonutTipos();
        <?php if($multiplePlanteles): ?>
        drawChartStackedTipos();
        <?php endif; ?>
        drawChartFormasPorTipo();
        drawChartGastosCategorias();
    }

    function drawChartTendencia7Dias() {
        var data = google.visualization.arrayToDataTable([
            ['Día', 'Cobranza', 'Gastos', 'Balance'],
            <?php 
            $first = true;
            foreach($ultimos7Dias as $dia) {
                if(!$first) echo ",";
                echo "['" . $dia['dia'] . "', " . $dia['cobranza'] . ", " . $dia['gastos'] . ", " . $dia['balance'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '85%', height: '70%'},
            colors: ['#28a745', '#dc3545', '#6f42c1'],
            legend: { 
                position: 'top', 
                textStyle: {color: '#2c3e50', fontSize: 9}
            },
            hAxis: {
                textStyle: {color: '#666', fontSize: 9},
                gridlines: {color: '#f0f0f0'}
            },
            vAxis: {
                textStyle: {color: '#666', fontSize: 9},
                format: 'short',
                gridlines: {color: '#f0f0f0'}
            },
            pointSize: 4,
            lineWidth: 2
        };

        var chart = new google.visualization.LineChart(document.getElementById('chartTendencia7Dias'));
        chart.draw(data, options);
    }

    function drawChartDonutTipos() {
        var data = google.visualization.arrayToDataTable([
            ['Tipo', 'Monto'],
            <?php 
            $first = true;
            foreach($rankingTiposHoy as $tipo => $monto) {
                if($monto > 0) {
                    if(!$first) echo ",";
                    echo "['" . $tipo . "', " . $monto . "]";
                    $first = false;
                }
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            pieHole: 0.5,
            chartArea: {width: '90%', height: '75%'},
            colors: ['#28a745', '#6c757d', '#6f42c1', '#fd7e14'],
            legend: { 
                position: 'bottom',
                textStyle: {color: '#2c3e50', fontSize: 9}
            },
            pieSliceText: 'percentage',
            pieSliceTextStyle: {
                color: '#fff',
                fontSize: 9
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartDonutTipos'));
        chart.draw(data, options);
    }

    <?php if($multiplePlanteles): ?>
    function drawChartStackedTipos() {
        var data = google.visualization.arrayToDataTable([
            ['Plantel', 'Colegiatura', 'Inscripción', 'Reinscripción', 'Trámites'],
            <?php 
            $first = true;
            foreach($tiposPagoPorPlantel as $plantel) {
                if(!$first) echo ",";
                echo "['" . substr($plantel['plantel'], 0, 8) . "', " . 
                     $plantel['tipos']['Colegiatura'] . ", " . 
                     $plantel['tipos']['Inscripción'] . ", " . 
                     $plantel['tipos']['Reinscripción'] . ", " . 
                     $plantel['tipos']['Trámites'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '75%', height: '65%'},
            colors: ['#28a745', '#6c757d', '#6f42c1', '#fd7e14'],
            legend: { 
                position: 'top',
                textStyle: {color: '#2c3e50', fontSize: 9}
            },
            isStacked: true,
            hAxis: {
                textStyle: {color: '#666', fontSize: 9},
                format: 'short',
                gridlines: {color: '#f0f0f0'}
            },
            vAxis: {
                textStyle: {color: '#666', fontSize: 9},
                gridlines: {color: '#f0f0f0'}
            },
            bar: { groupWidth: '70%' }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chartStackedTipos'));
        chart.draw(data, options);
    }
    <?php endif; ?>

    function drawChartFormasPorTipo() {
        var data = google.visualization.arrayToDataTable([
            ['Tipo', 'Efectivo', 'Depósito'],
            <?php 
            $first = true;
            foreach($formasPorTipo as $tipo => $formas) {
                if($formas['efectivo'] > 0 || $formas['deposito'] > 0) {
                    if(!$first) echo ",";
                    echo "['" . $tipo . "', " . $formas['efectivo'] . ", " . $formas['deposito'] . "]";
                    $first = false;
                }
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '75%', height: '65%'},
            colors: ['#6c757d', '#28a745'],
            legend: { 
                position: 'top',
                textStyle: {color: '#2c3e50', fontSize: 9}
            },
            bar: { groupWidth: '65%' },
            hAxis: {
                textStyle: {color: '#666', fontSize: 9},
                format: 'short',
                gridlines: {color: '#f0f0f0'}
            },
            vAxis: {
                textStyle: {color: '#666', fontSize: 9},
                gridlines: {color: '#f0f0f0'}
            }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chartFormasPorTipo'));
        chart.draw(data, options);
    }

    function drawChartGastosCategorias() {
        <?php if(count($categoriasGastosHoy) > 0): ?>
        var data = google.visualization.arrayToDataTable([
            ['Categoría', 'HOY', 'AYER'],
            <?php 
            $first = true;
            foreach($categoriasGastosHoy as $cat => $monto) {
                if(!$first) echo ",";
                $montoAyer = isset($categoriasGastosAyer[$cat]) ? $categoriasGastosAyer[$cat] : 0;
                echo "['" . substr($cat, 0, 10) . "', " . $monto . ", " . $montoAyer . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '75%', height: '65%'},
            colors: ['#dc3545', '#fca5a5'],
            legend: { 
                position: 'top',
                textStyle: {color: '#2c3e50', fontSize: 9}
            },
            bar: { groupWidth: '65%' },
            hAxis: {
                textStyle: {color: '#666', fontSize: 9},
                format: 'short',
                gridlines: {color: '#f0f0f0'}
            },
            vAxis: {
                textStyle: {color: '#666', fontSize: 9},
                gridlines: {color: '#f0f0f0'}
            }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chartGastosCategorias'));
        chart.draw(data, options);
        <?php else: ?>
        document.getElementById('chartGastosCategorias').innerHTML = '<div style="padding: 50px; text-align: center; color: #999; font-size: 11px;">Sin gastos HOY</div>';
        <?php endif; ?>
    }

    window.addEventListener('resize', function() {
        drawCharts();
    });
</script>