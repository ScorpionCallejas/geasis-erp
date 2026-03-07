<?php
// ========================================
// CONTROLADOR UNIFICADO DE DASHBOARDS
// Genera JSON con datos para Admisiones y Administrativo
// ========================================

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

// ========================================
// OBTENER PLANTELES DEL EJECUTIVO
// ========================================
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

$multiplePlanteles = count($plantelesEjecutivo) > 1;
$idsPlanteles = array_column($plantelesEjecutivo, 'id_pla');
$condicionPlantel = count($idsPlanteles) > 0 ? " AND id_pla10 IN (" . implode(',', $idsPlanteles) . ")" : "";
$condicionPlanteles = count($idsPlanteles) > 0 ? " AND ejecutivo.id_pla IN (" . implode(',', $idsPlanteles) . ")" : "";
$condicionPlantelGasto = count($idsPlanteles) > 0 ? " AND id_pla13 IN (" . implode(',', $idsPlanteles) . ")" : "";

// ========================================
// SECCIÓN ADMISIONES
// ========================================

$admisiones = array();

// CALCULAR MÉTRICAS POR PLANTEL
$totales = array(
    'contactos_hoy' => 0,
    'citas_hoy' => 0,
    'efectivas_hoy' => 0,
    'registros_hoy' => 0,
    'contactos_ayer' => 0,
    'citas_ayer' => 0,
    'efectivas_ayer' => 0,
    'registros_ayer' => 0
);

$datosPorPlantel = array();

foreach($plantelesEjecutivo as $plantel) {
    $id_plantel = $plantel['id_pla'];
    $nom_plantel = $plantel['nom_pla'];
    
    $sqlHoy = "
        SELECT 
            obtener_citas_plantel($id_plantel, '$inicio', '$fin') AS citas,
            obtener_citas_efectivas_plantel($id_plantel, '$inicio', '$fin') AS efectivas,
            obtener_registros_plantel($id_plantel, '$inicio', '$fin') AS registros
    ";
    $resHoy = mysqli_query($db, $sqlHoy);
    $filaHoy = mysqli_fetch_assoc($resHoy);
    $citasHoy = intval($filaHoy['citas']);
    $efectivasHoy = intval($filaHoy['efectivas']);
    $registrosHoy = intval($filaHoy['registros']);
    
    $sqlContactosHoy = "SELECT COUNT(*) AS total FROM contacto WHERE DATE(fec_con) = '$inicio' AND id_pla10 = $id_plantel";
    $resContactosHoy = mysqli_query($db, $sqlContactosHoy);
    $contactosHoy = intval(mysqli_fetch_assoc($resContactosHoy)['total']);
    
    $sqlAyer = "
        SELECT 
            obtener_citas_plantel($id_plantel, '$ayer', '$ayer') AS citas,
            obtener_citas_efectivas_plantel($id_plantel, '$ayer', '$ayer') AS efectivas,
            obtener_registros_plantel($id_plantel, '$ayer', '$ayer') AS registros
    ";
    $resAyer = mysqli_query($db, $sqlAyer);
    $filaAyer = mysqli_fetch_assoc($resAyer);
    $citasAyer = intval($filaAyer['citas']);
    $efectivasAyer = intval($filaAyer['efectivas']);
    $registrosAyer = intval($filaAyer['registros']);
    
    $sqlContactosAyer = "SELECT COUNT(*) AS total FROM contacto WHERE DATE(fec_con) = '$ayer' AND id_pla10 = $id_plantel";
    $resContactosAyer = mysqli_query($db, $sqlContactosAyer);
    $contactosAyer = intval(mysqli_fetch_assoc($resContactosAyer)['total']);
    
    $datosPorPlantel[] = array(
        'id_pla' => $id_plantel,
        'nombre' => $nom_plantel,
        'contactos_hoy' => $contactosHoy,
        'citas_hoy' => $citasHoy,
        'efectivas_hoy' => $efectivasHoy,
        'registros_hoy' => $registrosHoy,
        'contactos_ayer' => $contactosAyer,
        'citas_ayer' => $citasAyer,
        'efectivas_ayer' => $efectivasAyer,
        'registros_ayer' => $registrosAyer
    );
    
    $totales['contactos_hoy'] += $contactosHoy;
    $totales['citas_hoy'] += $citasHoy;
    $totales['efectivas_hoy'] += $efectivasHoy;
    $totales['registros_hoy'] += $registrosHoy;
    $totales['contactos_ayer'] += $contactosAyer;
    $totales['citas_ayer'] += $citasAyer;
    $totales['efectivas_ayer'] += $efectivasAyer;
    $totales['registros_ayer'] += $registrosAyer;
}

// CALCULAR TASAS Y CAMBIOS
$tasaCitasTotal = $totales['contactos_hoy'] > 0 ? (($totales['citas_hoy'] / $totales['contactos_hoy']) * 100) : 0;
$tasaEfectivasTotal = $totales['citas_hoy'] > 0 ? (($totales['efectivas_hoy'] / $totales['citas_hoy']) * 100) : 0;
$tasaRegistrosTotal = $totales['efectivas_hoy'] > 0 ? (($totales['registros_hoy'] / $totales['efectivas_hoy']) * 100) : 0;

$cambioContactos = $totales['contactos_hoy'] - $totales['contactos_ayer'];
$porcContactos = $totales['contactos_ayer'] > 0 ? (($cambioContactos / $totales['contactos_ayer']) * 100) : null;

$cambioCitas = $totales['citas_hoy'] - $totales['citas_ayer'];
$porcCitas = $totales['citas_ayer'] > 0 ? (($cambioCitas / $totales['citas_ayer']) * 100) : null;

$cambioEfectivas = $totales['efectivas_hoy'] - $totales['efectivas_ayer'];
$porcEfectivas = $totales['efectivas_ayer'] > 0 ? (($cambioEfectivas / $totales['efectivas_ayer']) * 100) : null;

$cambioRegistros = $totales['registros_hoy'] - $totales['registros_ayer'];
$porcRegistros = $totales['registros_ayer'] > 0 ? (($cambioRegistros / $totales['registros_ayer']) * 100) : null;

// EMBUDO
$admisiones['embudo'] = array(
    'contactos_hoy' => $totales['contactos_hoy'],
    'citas_hoy' => $totales['citas_hoy'],
    'efectivas_hoy' => $totales['efectivas_hoy'],
    'registros_hoy' => $totales['registros_hoy'],
    'tasa_citas' => round($tasaCitasTotal, 1),
    'tasa_efectivas' => round($tasaEfectivasTotal, 1),
    'tasa_registros' => round($tasaRegistrosTotal, 1),
    'cambio_contactos' => $cambioContactos,
    'porc_contactos' => $porcContactos !== null ? round($porcContactos, 1) : null,
    'cambio_citas' => $cambioCitas,
    'porc_citas' => $porcCitas !== null ? round($porcCitas, 1) : null,
    'cambio_efectivas' => $cambioEfectivas,
    'porc_efectivas' => $porcEfectivas !== null ? round($porcEfectivas, 1) : null,
    'cambio_registros' => $cambioRegistros,
    'porc_registros' => $porcRegistros !== null ? round($porcRegistros, 1) : null
);

// TENDENCIA 7 DÍAS
$ultimos7Dias = array();

for($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime($inicio . " -$i days"));
    $fecha_obj = new DateTime($fecha);
    $diaSemana = $dias_semana[$fecha_obj->format('w')];
    
    $sqlContactosDia = "SELECT COUNT(*) AS total FROM contacto WHERE DATE(fec_con) = '$fecha' $condicionPlantel";
    $resContactosDia = mysqli_query($db, $sqlContactosDia);
    $contactosDia = intval(mysqli_fetch_assoc($resContactosDia)['total']);
    
    $citasDia = 0;
    $efectivasDia = 0;
    $registrosDia = 0;
    foreach($idsPlanteles as $idP) {
        $sqlMetricas = "
            SELECT 
                obtener_citas_plantel($idP, '$fecha', '$fecha') AS citas,
                obtener_citas_efectivas_plantel($idP, '$fecha', '$fecha') AS efectivas,
                obtener_registros_plantel($idP, '$fecha', '$fecha') AS registros
        ";
        $resMetricas = mysqli_query($db, $sqlMetricas);
        $filaMetricas = mysqli_fetch_assoc($resMetricas);
        $citasDia += intval($filaMetricas['citas']);
        $efectivasDia += intval($filaMetricas['efectivas']);
        $registrosDia += intval($filaMetricas['registros']);
    }
    
    $ultimos7Dias[] = array(
        'dia' => $diaSemana,
        'fecha' => $fecha,
        'contactos' => $contactosDia,
        'citas' => $citasDia,
        'efectivas' => $efectivasDia,
        'registros' => $registrosDia
    );
}

$admisiones['tendencia_7_dias'] = $ultimos7Dias;

// SEMÁFORO
$ejecutivosVerdes = array();
$ejecutivosAmarillos = array();
$ejecutivosRojos = array();

if($multiplePlanteles) {
    $sqlTodos = "
        SELECT 
            e.id_eje,
            e.nom_eje,
            e.fot_eje,
            e.ult_eje,
            p.nom_pla,
            DATEDIFF(NOW(), e.ult_eje) AS dias_sin_conexion
        FROM ejecutivo e
        INNER JOIN plantel p ON e.id_pla = p.id_pla
        INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
        WHERE pe.id_eje = '$id'
        AND e.eli_eje = 'Activo'
        AND e.tip_eje = 'Ejecutivo'
        ORDER BY e.nom_eje ASC
    ";
} else {
    $id_plantel_unico = $plantelesEjecutivo[0]['id_pla'];
    $sqlTodos = "
        SELECT 
            e.id_eje,
            e.nom_eje,
            e.fot_eje,
            e.ult_eje,
            p.nom_pla,
            DATEDIFF(NOW(), e.ult_eje) AS dias_sin_conexion
        FROM ejecutivo e
        INNER JOIN plantel p ON e.id_pla = p.id_pla
        WHERE p.id_pla = $id_plantel_unico
        AND e.eli_eje = 'Activo'
        AND e.tip_eje = 'Ejecutivo'
        ORDER BY e.nom_eje ASC
    ";
}

$resTodosEje = mysqli_query($db, $sqlTodos);

while($eje = mysqli_fetch_assoc($resTodosEje)) {
    $dias = $eje['dias_sin_conexion'];
    $fotoUrl = obtenerValidacionFotoUsuarioServer($eje['fot_eje']);
    
    $ejecutivoData = array(
        'id_eje' => $eje['id_eje'],
        'nom_eje' => $eje['nom_eje'],
        'foto' => $fotoUrl,
        'ult_eje' => $eje['ult_eje'],
        'nom_pla' => $eje['nom_pla'],
        'dias_sin_conexion' => $dias
    );
    
    if($dias === null || $dias >= 5) {
        $ejecutivosRojos[] = $ejecutivoData;
    } elseif($dias >= 2 && $dias <= 4) {
        $ejecutivosAmarillos[] = $ejecutivoData;
    } else {
        $ejecutivosVerdes[] = $ejecutivoData;
    }
}

$admisiones['semaforo'] = array(
    'verdes' => $ejecutivosVerdes,
    'amarillos' => $ejecutivosAmarillos,
    'rojos' => $ejecutivosRojos
);

// DISTRIBUCIÓN ESTATUS
$distribucionEstatus = array();

$sqlEstatus = "
    SELECT 
        cita.est_cit,
        COUNT(*) as total_hoy,
        (SELECT COUNT(*) 
         FROM cita c2 
         INNER JOIN ejecutivo e2 ON c2.id_eje3 = e2.id_eje
         WHERE c2.est_cit = cita.est_cit 
         AND DATE(c2.cit_cit) = '$ayer'
         AND e2.eli_eje = 'Activo'
         $condicionPlanteles
        ) as total_ayer
    FROM cita
    INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
    WHERE DATE(cita.cit_cit) = '$inicio'
    AND cita.cla_cit = 'Cita'
    AND ejecutivo.eli_eje = 'Activo'
    $condicionPlanteles
    GROUP BY cita.est_cit
    ORDER BY total_hoy DESC
    LIMIT 12
";

$resEstatus = mysqli_query($db, $sqlEstatus);
while($filaEst = mysqli_fetch_assoc($resEstatus)) {
    $distribucionEstatus[] = array(
        'estatus' => $filaEst['est_cit'],
        'total_hoy' => intval($filaEst['total_hoy']),
        'total_ayer' => intval($filaEst['total_ayer'])
    );
}

$admisiones['distribucion_estatus'] = $distribucionEstatus;

// PRODUCTOS
$distribucionProductos = array();

$sqlProductos = "
    SELECT 
        cita.pro_cit,
        COUNT(*) as total_hoy,
        (SELECT COUNT(*) 
         FROM cita c2 
         INNER JOIN ejecutivo e2 ON c2.id_eje3 = e2.id_eje
         WHERE c2.pro_cit = cita.pro_cit 
         AND DATE(c2.cit_cit) = '$ayer'
         AND e2.eli_eje = 'Activo'
         $condicionPlanteles
        ) as total_ayer
    FROM cita
    INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
    WHERE DATE(cita.cit_cit) = '$inicio'
    AND cita.cla_cit = 'Cita'
    AND cita.pro_cit IS NOT NULL
    AND cita.pro_cit != ''
    AND ejecutivo.eli_eje = 'Activo'
    $condicionPlanteles
    GROUP BY cita.pro_cit
    ORDER BY total_hoy DESC
    LIMIT 8
";

$resProductos = mysqli_query($db, $sqlProductos);
while($filaProd = mysqli_fetch_assoc($resProductos)) {
    $distribucionProductos[] = array(
        'producto' => $filaProd['pro_cit'],
        'total_hoy' => intval($filaProd['total_hoy']),
        'total_ayer' => intval($filaProd['total_ayer'])
    );
}

$admisiones['productos'] = $distribucionProductos;

// CANALES
$rankingCanales = array();
$sqlCanales = "
    SELECT 
        cita.can_cit AS canal, 
        COUNT(*) AS total
    FROM cita
    INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
    WHERE DATE(cita.cit_cit) = '$inicio'
    AND cita.can_cit IS NOT NULL 
    AND cita.can_cit != ''
    AND ejecutivo.eli_eje = 'Activo'
    $condicionPlanteles
    GROUP BY cita.can_cit
    ORDER BY total DESC
    LIMIT 6
";
$resCanales = mysqli_query($db, $sqlCanales);
while($fila = mysqli_fetch_assoc($resCanales)) {
    $rankingCanales[] = array(
        'canal' => $fila['canal'],
        'total' => intval($fila['total'])
    );
}

$admisiones['canales'] = $rankingCanales;

// TIPO CITA
$distribucionTipoCita = array();

$sqlTipoCita = "
    SELECT 
        cita.tip_cit,
        COUNT(*) as total
    FROM cita
    INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
    WHERE DATE(cita.cit_cit) = '$inicio'
    AND cita.cla_cit = 'Cita'
    AND cita.tip_cit IS NOT NULL
    AND cita.tip_cit != ''
    AND ejecutivo.eli_eje = 'Activo'
    $condicionPlanteles
    GROUP BY cita.tip_cit
    ORDER BY total DESC
";

$resTipoCita = mysqli_query($db, $sqlTipoCita);
while($filaTipo = mysqli_fetch_assoc($resTipoCita)) {
    $distribucionTipoCita[] = array(
        'tipo' => $filaTipo['tip_cit'],
        'total' => intval($filaTipo['total'])
    );
}

$admisiones['tipo_cita'] = $distribucionTipoCita;

// TOP EJECUTIVOS
$topEjecutivos = array();

$sqlTopEje = "
    SELECT 
        e.id_eje,
        e.nom_eje,
        e.fot_eje,
        p.nom_pla,
        (SELECT COUNT(*) 
         FROM cita c 
         WHERE c.id_eje3 = e.id_eje 
         AND c.est_cit = 'REGISTRO' 
         AND DATE(c.cit_cit) = '$inicio'
        ) as registros_hoy
    FROM ejecutivo e
    INNER JOIN plantel p ON e.id_pla = p.id_pla
    " . ($multiplePlanteles ? "INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla WHERE pe.id_eje = '$id'" : "WHERE p.id_pla = " . $plantelesEjecutivo[0]['id_pla']) . "
    AND e.eli_eje = 'Activo'
    AND e.tip_eje = 'Ejecutivo'
    HAVING registros_hoy > 0
    ORDER BY registros_hoy DESC
    LIMIT 10
";

$resTopEje = mysqli_query($db, $sqlTopEje);
while($eje = mysqli_fetch_assoc($resTopEje)) {
    $topEjecutivos[] = array(
        'id_eje' => $eje['id_eje'],
        'nom_eje' => $eje['nom_eje'],
        'foto' => obtenerValidacionFotoUsuarioServer($eje['fot_eje']),
        'nom_pla' => $eje['nom_pla'],
        'registros_hoy' => intval($eje['registros_hoy'])
    );
}

$admisiones['top_ejecutivos'] = $topEjecutivos;

// EMBUDO POR HORA
$rendimientoEmbudoHoras = array();

for($hora = 9; $hora <= 20; $hora++) {
    $horaInicio = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00:00';
    $horaFin = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':59:59';
    
    $sqlContactosHora = "
        SELECT COUNT(*) as total
        FROM contacto
        WHERE DATE(fec_con) = '$inicio'
        AND TIME(fec_con) BETWEEN '$horaInicio' AND '$horaFin'
        $condicionPlantel
    ";
    $resContactosHora = mysqli_query($db, $sqlContactosHora);
    $contactosHora = intval(mysqli_fetch_assoc($resContactosHora)['total']);
    
    $sqlCitasHora = "
        SELECT COUNT(*) as total
        FROM cita
        INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
        WHERE DATE(cita.cit_cit) = '$inicio'
        AND cita.hor_cit BETWEEN '$horaInicio' AND '$horaFin'
        AND ejecutivo.eli_eje = 'Activo'
        $condicionPlanteles
    ";
    $resCitasHora = mysqli_query($db, $sqlCitasHora);
    $citasHora = intval(mysqli_fetch_assoc($resCitasHora)['total']);
    
    $sqlEfectivasHora = "
        SELECT COUNT(*) as total
        FROM cita
        INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
        WHERE DATE(cita.cit_cit) = '$inicio'
        AND cita.hor_cit BETWEEN '$horaInicio' AND '$horaFin'
        AND cita.est_cit NOT IN ('CITA NO ATENDIDA', 'NO LE INTERESA')
        AND ejecutivo.eli_eje = 'Activo'
        $condicionPlanteles
    ";
    $resEfectivasHora = mysqli_query($db, $sqlEfectivasHora);
    $efectivasHora = intval(mysqli_fetch_assoc($resEfectivasHora)['total']);
    
    $sqlRegistrosHora = "
        SELECT COUNT(*) as total
        FROM cita
        INNER JOIN ejecutivo ON cita.id_eje3 = ejecutivo.id_eje
        WHERE DATE(cita.cit_cit) = '$inicio'
        AND cita.hor_cit BETWEEN '$horaInicio' AND '$horaFin'
        AND cita.est_cit = 'REGISTRO'
        AND ejecutivo.eli_eje = 'Activo'
        $condicionPlanteles
    ";
    $resRegistrosHora = mysqli_query($db, $sqlRegistrosHora);
    $registrosHora = intval(mysqli_fetch_assoc($resRegistrosHora)['total']);
    
    $rendimientoEmbudoHoras[] = array(
        'hora' => $hora,
        'contactos' => $contactosHora,
        'citas' => $citasHora,
        'efectivas' => $efectivasHora,
        'registros' => $registrosHora
    );
}

$admisiones['embudo_horas'] = $rendimientoEmbudoHoras;

// DESGLOSE PLANTELES (SI MÚLTIPLE)
if($multiplePlanteles) {
    $rankingContactos = $datosPorPlantel;
    usort($rankingContactos, function($a, $b) { return $b['contactos_hoy'] - $a['contactos_hoy']; });
    
    $rankingCitas = $datosPorPlantel;
    usort($rankingCitas, function($a, $b) { return $b['citas_hoy'] - $a['citas_hoy']; });
    
    $rankingEfectivas = $datosPorPlantel;
    usort($rankingEfectivas, function($a, $b) { return $b['efectivas_hoy'] - $a['efectivas_hoy']; });
    
    $rankingRegistros = $datosPorPlantel;
    usort($rankingRegistros, function($a, $b) { return $b['registros_hoy'] - $a['registros_hoy']; });
    
    $admisiones['desglose_planteles'] = array(
        'totales' => array(
            'contactos_hoy' => $totales['contactos_hoy'],
            'citas_hoy' => $totales['citas_hoy'],
            'efectivas_hoy' => $totales['efectivas_hoy'],
            'registros_hoy' => $totales['registros_hoy'],
            'cambio_contactos' => $cambioContactos,
            'porc_contactos' => $porcContactos !== null ? round($porcContactos, 1) : null,
            'cambio_citas' => $cambioCitas,
            'porc_citas' => $porcCitas !== null ? round($porcCitas, 1) : null,
            'cambio_efectivas' => $cambioEfectivas,
            'porc_efectivas' => $porcEfectivas !== null ? round($porcEfectivas, 1) : null,
            'cambio_registros' => $cambioRegistros,
            'porc_registros' => $porcRegistros !== null ? round($porcRegistros, 1) : null
        ),
        'ranking_contactos' => $rankingContactos,
        'ranking_citas' => $rankingCitas,
        'ranking_efectivas' => $rankingEfectivas,
        'ranking_registros' => $rankingRegistros
    );
}

$admisiones['multiple_planteles'] = $multiplePlanteles;

// ========================================
// SECCIÓN ADMINISTRATIVO
// ========================================

$administrativo = array();

// CALCULAR KPIs
$totalesAdmin = array(
    'cobranza_hoy' => 0,
    'gastos_hoy' => 0,
    'balance_hoy' => 0,
    'cobranza_ayer' => 0,
    'gastos_ayer' => 0,
    'balance_ayer' => 0
);

$datosPorPlantelAdmin = array();

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
    
    // EFECTIVO Y DEPÓSITO HOY
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
    
    // DESGLOSE POR TIPO DE PAGO
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
    
    $cambioCobranza = $cobranzaHoy - $cobranzaAyer;
    $porcCobranza = $cobranzaAyer > 0 ? (($cambioCobranza / $cobranzaAyer) * 100) : 0;
    
    $cambioGastos = $gastosHoy - $gastosAyer;
    $porcGastos = $gastosAyer > 0 ? (($cambioGastos / $gastosAyer) * 100) : 0;
    
    $cambioBalance = $balanceHoy - $balanceAyer;
    $porcBalance = $balanceAyer != 0 ? (($cambioBalance / abs($balanceAyer)) * 100) : 0;
    
    $porcEfectivo = ($efectivoHoy + $depositoHoy) > 0 ? (($efectivoHoy / ($efectivoHoy + $depositoHoy)) * 100) : 0;
    
    $fecha_obj = new DateTime($inicio);
    $dia_semana = $dias_semana[$fecha_obj->format('w')];
    $dia = $fecha_obj->format('d');
    $mes = $meses[intval($fecha_obj->format('n'))];
    $anio = $fecha_obj->format('Y');
    $fechaMostrar = "$dia_semana $dia $mes $anio";
    
    $datosPorPlantelAdmin[] = array(
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
        'tipos' => $tiposPlantel,
        'cambio_cobranza' => $cambioCobranza,
        'porc_cobranza' => round($porcCobranza, 1),
        'cambio_gastos' => $cambioGastos,
        'porc_gastos' => round($porcGastos, 1),
        'cambio_balance' => $cambioBalance,
        'porc_balance' => round($porcBalance, 1),
        'porc_efectivo' => round($porcEfectivo, 1),
        'fecha' => $fechaMostrar
    );
    
    $totalesAdmin['cobranza_hoy'] += $cobranzaHoy;
    $totalesAdmin['gastos_hoy'] += $gastosHoy;
    $totalesAdmin['balance_hoy'] += $balanceHoy;
    $totalesAdmin['cobranza_ayer'] += $cobranzaAyer;
    $totalesAdmin['gastos_ayer'] += $gastosAyer;
    $totalesAdmin['balance_ayer'] += $balanceAyer;
}

// CAMBIOS TOTALES
$cambioCobranzaTotal = $totalesAdmin['cobranza_hoy'] - $totalesAdmin['cobranza_ayer'];
$porcCobranzaTotal = $totalesAdmin['cobranza_ayer'] > 0 ? (($cambioCobranzaTotal / $totalesAdmin['cobranza_ayer']) * 100) : null;

$cambioGastosTotal = $totalesAdmin['gastos_hoy'] - $totalesAdmin['gastos_ayer'];
$porcGastosTotal = $totalesAdmin['gastos_ayer'] > 0 ? (($cambioGastosTotal / $totalesAdmin['gastos_ayer']) * 100) : null;

$cambioBalanceTotal = $totalesAdmin['balance_hoy'] - $totalesAdmin['balance_ayer'];
$porcBalanceTotal = $totalesAdmin['balance_ayer'] != 0 ? (($cambioBalanceTotal / abs($totalesAdmin['balance_ayer'])) * 100) : null;

$administrativo['kpis'] = array(
    'cobranza_hoy' => $totalesAdmin['cobranza_hoy'],
    'gastos_hoy' => $totalesAdmin['gastos_hoy'],
    'balance_hoy' => $totalesAdmin['balance_hoy'],
    'cambio_cobranza' => $cambioCobranzaTotal,
    'porc_cobranza' => $porcCobranzaTotal !== null ? round($porcCobranzaTotal, 1) : null,
    'cambio_gastos' => $cambioGastosTotal,
    'porc_gastos' => $porcGastosTotal !== null ? round($porcGastosTotal, 1) : null,
    'cambio_balance' => $cambioBalanceTotal,
    'porc_balance' => $porcBalanceTotal !== null ? round($porcBalanceTotal, 1) : null
);

// RANKING PLANTELES
$rankingPlanteles = $datosPorPlantelAdmin;
usort($rankingPlanteles, function($a, $b) {
    return $b['cobranza_hoy'] - $a['cobranza_hoy'];
});

$administrativo['ranking_planteles'] = $rankingPlanteles;

// RANKING USUARIOS
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
    " . (count($idsPlanteles) > 0 ? " AND alumno.id_pla8 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
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

$administrativo['ranking_usuarios'] = $rankingUsuarios;

// RANKING POR BOLSA
$rankingBolsasHoy = array();
$rankingBolsasAyer = array();

$sqlTiposHoy = "
    SELECT 
        pago.tip_pag,
        SUM(abono_pago.mon_abo_pag) AS total
    FROM alumno
    JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
    JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
    JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
    WHERE abono_pago.fec_abo_pag = '$inicio'
    " . (count($idsPlanteles) > 0 ? " AND alumno.id_pla8 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    GROUP BY pago.tip_pag
    ORDER BY total DESC
";
$resTiposHoy = mysqli_query($db, $sqlTiposHoy);
while($fila = mysqli_fetch_assoc($resTiposHoy)) {
    $tipo = $fila['tip_pag'] == 'Otros' ? 'Trámites' : $fila['tip_pag'];
    $rankingBolsasHoy[$tipo] = floatval($fila['total']);
}

$sqlTiposAyer = "
    SELECT 
        pago.tip_pag,
        SUM(abono_pago.mon_abo_pag) AS total
    FROM alumno
    JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
    JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
    JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
    WHERE abono_pago.fec_abo_pag = '$ayer'
    " . (count($idsPlanteles) > 0 ? " AND alumno.id_pla8 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    GROUP BY pago.tip_pag
    ORDER BY total DESC
";
$resTiposAyer = mysqli_query($db, $sqlTiposAyer);
while($fila = mysqli_fetch_assoc($resTiposAyer)) {
    $tipo = $fila['tip_pag'] == 'Otros' ? 'Trámites' : $fila['tip_pag'];
    $rankingBolsasAyer[$tipo] = floatval($fila['total']);
}

$administrativo['ranking_bolsas'] = array(
    'hoy' => $rankingBolsasHoy,
    'ayer' => $rankingBolsasAyer
);

// RANKING BOLSA POR PLANTEL (SI MÚLTIPLE)
$rankingBolsaPorPlantel = array();
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
            $rankingBolsaPorPlantel[] = array(
                'id_pla' => $id_plantel,
                'plantel' => $nom_plantel,
                'tipos' => $tiposDelPlantel
            );
        }
    }
}

$administrativo['ranking_bolsa_plantel'] = $rankingBolsaPorPlantel;

// TIPOS POR PLANTEL (STACKED)
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

$administrativo['tipos_por_plantel'] = $tiposPagoPorPlantel;

// FORMAS DE PAGO POR TIPO
$formasPorTipo = array();
foreach(array('Colegiatura', 'Inscripción', 'Reinscripción', 'Trámites') as $tipoPago) {
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
        " . (count($idsPlanteles) > 0 ? " AND alumno.id_pla8 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    ";
    $resFormas = mysqli_query($db, $sqlFormas);
    $filaFormas = mysqli_fetch_assoc($resFormas);
    
    $formasPorTipo[$tipoPago] = array(
        'efectivo' => floatval($filaFormas['efectivo']),
        'deposito' => floatval($filaFormas['deposito'])
    );
}

$administrativo['formas_por_tipo'] = $formasPorTipo;

// GASTOS POR CATEGORÍA
$categoriasGastosHoyArray = array();
$categoriasGastosAyerArray = array();

$sqlCatHoy = "
    SELECT cat_egr, SUM(mon_egr) AS total
    FROM egreso
    WHERE fec_egr = '$inicio'
    " . (count($idsPlanteles) > 0 ? " AND id_pla13 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    AND cat_egr IS NOT NULL
    AND cat_egr != ''
    GROUP BY cat_egr
    ORDER BY total DESC
    LIMIT 5
";
$resCatHoy = mysqli_query($db, $sqlCatHoy);
while($fila = mysqli_fetch_assoc($resCatHoy)) {
    $categoriasGastosHoyArray[] = array(
        'categoria' => $fila['cat_egr'],
        'monto' => floatval($fila['total'])
    );
}

$categoriasGastosAyerMap = array();
$sqlCatAyer = "
    SELECT cat_egr, SUM(mon_egr) AS total
    FROM egreso
    WHERE fec_egr = '$ayer'
    " . (count($idsPlanteles) > 0 ? " AND id_pla13 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    AND cat_egr IS NOT NULL
    AND cat_egr != ''
    GROUP BY cat_egr
    ORDER BY total DESC
";
$resCatAyer = mysqli_query($db, $sqlCatAyer);
while($fila = mysqli_fetch_assoc($resCatAyer)) {
    $categoriasGastosAyerMap[$fila['cat_egr']] = floatval($fila['total']);
}

$administrativo['gastos_categorias'] = array(
    'hoy' => $categoriasGastosHoyArray,
    'ayer' => $categoriasGastosAyerMap
);

// TENDENCIA ÚLTIMOS 7 DÍAS
$ultimos7DiasAdmin = array();
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
        " . (count($idsPlanteles) > 0 ? " AND alumno.id_pla8 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    ";
    $resCobranzaDia = mysqli_query($db, $sqlCobranzaDia);
    $cobranzaDia = floatval(mysqli_fetch_assoc($resCobranzaDia)['total']);
    
    $sqlGastosDia = "
        SELECT SUM(mon_egr) AS total
        FROM egreso
        WHERE fec_egr = '$fecha'
        " . (count($idsPlanteles) > 0 ? " AND id_pla13 IN (" . implode(',', $idsPlanteles) . ")" : "") . "
    ";
    $resGastosDia = mysqli_query($db, $sqlGastosDia);
    $gastosDia = floatval(mysqli_fetch_assoc($resGastosDia)['total']);
    
    $ultimos7DiasAdmin[] = array(
        'dia' => $diaSemana,
        'fecha' => $fecha,
        'cobranza' => $cobranzaDia,
        'gastos' => $gastosDia,
        'balance' => $cobranzaDia - $gastosDia
    );
}

$administrativo['tendencia_7_dias'] = $ultimos7DiasAdmin;

// OBSERVACIONES
$observaciones = array();

if(count($idsPlanteles) > 0) {
    $condicionPlantelObs = " WHERE a.id_pla8 IN (" . implode(',', $idsPlanteles) . ")";
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
        $nombreCompleto = trim($obs['res_obs_alu_ram']);
        $partes = explode(' ', $nombreCompleto);
        $iniciales = '';
        if(!empty($nombreCompleto)) {
            $iniciales = strtoupper(substr($partes[0], 0, 1));
            if(count($partes) > 1) {
                $iniciales .= strtoupper(substr($partes[count($partes)-1], 0, 1));
            }
        } else {
            $iniciales = 'SN';
        }
        
        $fechaObj = new DateTime($obs['fec_obs_alu_ram']);
        $horaMin = $fechaObj->format('H:i');
        $alumnoNombre = trim($obs['nom_alu'] . ' ' . $obs['app_alu']);
        
        $diaNum = $fechaObj->format('d');
        $mesNum = intval($fechaObj->format('n'));
        $mesNombre = $meses[$mesNum];
        $diaSemana = $dias_semana[$fechaObj->format('w')];
        $fechaFormateada = "$diaSemana $diaNum $mesNombre";
        
        $observaciones[] = array(
            'fec_obs_alu_ram' => $obs['fec_obs_alu_ram'],
            'obs_obs_alu_ram' => $obs['obs_obs_alu_ram'],
            'res_obs_alu_ram' => $obs['res_obs_alu_ram'],
            'iniciales' => $iniciales,
            'hora' => $horaMin,
            'alumno_nombre' => $alumnoNombre,
            'nom_pla' => $obs['nom_pla'],
            'fecha_formateada' => $fechaFormateada
        );
    }
}

$administrativo['observaciones'] = $observaciones;

// DETALLE PLANTELES
$administrativo['detalle_planteles'] = $datosPorPlantelAdmin;

// URLs
$centrosParam = urlencode(implode(',', $idsPlanteles));
$administrativo['urls'] = array(
    'cobranza' => "cobranza.php?centros={$centrosParam}&bolsas=Colegiatura,Inscripcion,Reinscripcion,Tramite,Varios&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}",
    'gastos' => "gastos.php?centros={$centrosParam}&formas=colegiatura_efectivo,colegiatura_deposito,tramite_efectivo,tramite_deposito,reinscripcion_efectivo,reinscripcion_deposito,inscripcion_efectivo,inscripcion_deposito&fecha_inicio={$inicio}&fecha_fin={$fin}",
    'cobranza_base' => "cobranza.php?centros=PLANTEL_ID&bolsas=Colegiatura,Inscripcion,Reinscripcion,Tramite,Varios&formas=Efectivo,Deposito&fecha_inicio={$inicio}&fecha_fin={$fin}",
    'gastos_base' => "gastos.php?centros=PLANTEL_ID&formas=colegiatura_efectivo,colegiatura_deposito,tramite_efectivo,tramite_deposito,reinscripcion_efectivo,reinscripcion_deposito,inscripcion_efectivo,inscripcion_deposito&fecha_inicio={$inicio}&fecha_fin={$fin}"
);

$administrativo['multiple_planteles'] = $multiplePlanteles;

// ========================================
// RESPUESTA JSON
// ========================================
header('Content-Type: application/json; charset=utf-8');
echo json_encode(array(
    'success' => true,
    'admisiones' => $admisiones,
    'administrativo' => $administrativo
), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

mysqli_close($db);
?>