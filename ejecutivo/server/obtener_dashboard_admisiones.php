<?php
// DASHBOARD ADMISIONES - VERSIÓN ACTUALIZADA
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

$multiplePlanteles = count($plantelesEjecutivo) > 1;
$idsPlanteles = array_column($plantelesEjecutivo, 'id_pla');
$condicionPlantel = count($idsPlanteles) > 0 ? " AND id_pla10 IN (" . implode(',', $idsPlanteles) . ")" : "";

// ========================================
// CALCULAR MÉTRICAS POR PLANTEL
// ========================================
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
$porcContactos = $totales['contactos_ayer'] > 0 ? (($cambioContactos / $totales['contactos_ayer']) * 100) : 0;

$cambioCitas = $totales['citas_hoy'] - $totales['citas_ayer'];
$porcCitas = $totales['citas_ayer'] > 0 ? (($cambioCitas / $totales['citas_ayer']) * 100) : 0;

$cambioEfectivas = $totales['efectivas_hoy'] - $totales['efectivas_ayer'];
$porcEfectivas = $totales['efectivas_ayer'] > 0 ? (($cambioEfectivas / $totales['efectivas_ayer']) * 100) : 0;

$cambioRegistros = $totales['registros_hoy'] - $totales['registros_ayer'];
$porcRegistros = $totales['registros_ayer'] > 0 ? (($cambioRegistros / $totales['registros_ayer']) * 100) : 0;

// ORDENAR RANKINGS
$rankingContactos = $datosPorPlantel;
usort($rankingContactos, function($a, $b) { return $b['contactos_hoy'] - $a['contactos_hoy']; });

$rankingCitas = $datosPorPlantel;
usort($rankingCitas, function($a, $b) { return $b['citas_hoy'] - $a['citas_hoy']; });

$rankingEfectivas = $datosPorPlantel;
usort($rankingEfectivas, function($a, $b) { return $b['efectivas_hoy'] - $a['efectivas_hoy']; });

$rankingRegistros = $datosPorPlantel;
usort($rankingRegistros, function($a, $b) { return $b['registros_hoy'] - $a['registros_hoy']; });

// ========================================
// TENDENCIA ÚLTIMOS 7 DÍAS
// ========================================
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

// ========================================
// DISTRIBUCIÓN POR ESTATUS (est_cit)
// ========================================
$distribucionEstatus = array();
$condicionPlanteles = count($idsPlanteles) > 0 ? " AND ejecutivo.id_pla IN (" . implode(',', $idsPlanteles) . ")" : "";

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
";

$resEstatus = mysqli_query($db, $sqlEstatus);
while($filaEst = mysqli_fetch_assoc($resEstatus)) {
    $totalHoy = intval($filaEst['total_hoy']);
    $totalAyer = intval($filaEst['total_ayer']);
    $cambio = $totalHoy - $totalAyer;
    $porcCambio = $totalAyer > 0 ? (($cambio / $totalAyer) * 100) : 0;
    
    $distribucionEstatus[] = array(
        'estatus' => $filaEst['est_cit'],
        'total_hoy' => $totalHoy,
        'total_ayer' => $totalAyer,
        'cambio' => $cambio,
        'porc_cambio' => $porcCambio
    );
}

// ========================================
// DISTRIBUCIÓN POR PRODUCTOS (pro_cit)
// ========================================
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
    $totalHoy = intval($filaProd['total_hoy']);
    $totalAyer = intval($filaProd['total_ayer']);
    $cambio = $totalHoy - $totalAyer;
    $porcCambio = $totalAyer > 0 ? (($cambio / $totalAyer) * 100) : 0;
    
    $distribucionProductos[] = array(
        'producto' => $filaProd['pro_cit'],
        'total_hoy' => $totalHoy,
        'total_ayer' => $totalAyer,
        'cambio' => $cambio,
        'porc_cambio' => $porcCambio
    );
}

// ========================================
// DISTRIBUCIÓN POR TIPO DE CITA (tip_cit)
// ========================================
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

// ========================================
// RANKING DE CANALES (can_cit)
// ========================================
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
    $rankingCanales[] = $fila;
}

// ========================================
// RANKING DE NIVELES (niv_con via contacto)
// ========================================
$rankingNiveles = array();
$sqlNiveles = "
    SELECT niv_con AS nivel, COUNT(*) AS total
    FROM contacto
    WHERE DATE(fec_con) = '$inicio'
    $condicionPlantel
    AND niv_con IS NOT NULL AND niv_con != ''
    GROUP BY niv_con
    ORDER BY total DESC
    LIMIT 6
";
$resNiveles = mysqli_query($db, $sqlNiveles);
while($fila = mysqli_fetch_assoc($resNiveles)) {
    $rankingNiveles[] = $fila;
}

// ========================================
// SEPARAR EJECUTIVOS POR SEMÁFORO
// ========================================
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
    
    if($dias === null || $dias >= 5) {
        $ejecutivosRojos[] = $eje;
    } elseif($dias >= 2 && $dias <= 4) {
        $ejecutivosAmarillos[] = $eje;
    } else {
        $ejecutivosVerdes[] = $eje;
    }
}

$totalVerdes = count($ejecutivosVerdes);
$totalAmarillos = count($ejecutivosAmarillos);
$totalRojos = count($ejecutivosRojos);

// ========================================
// RENDIMIENTO POR HORA - EMBUDO
// ========================================
$rendimientoEmbudoHoras = array();

for($hora = 9; $hora <= 20; $hora++) {
    $horaInicio = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00:00';
    $horaFin = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':59:59';
    
    // CONTACTOS
    $sqlContactosHora = "
        SELECT COUNT(*) as total
        FROM contacto
        WHERE DATE(fec_con) = '$inicio'
        AND TIME(fec_con) BETWEEN '$horaInicio' AND '$horaFin'
        $condicionPlantel
    ";
    $resContactosHora = mysqli_query($db, $sqlContactosHora);
    $contactosHora = intval(mysqli_fetch_assoc($resContactosHora)['total']);
    
    // CITAS
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
    
    // EFECTIVAS
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
    
    // REGISTROS
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

// ========================================
// TOP 10 EJECUTIVOS POR REGISTROS
// ========================================
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
    $topEjecutivos[] = $eje;
}

// MAPEO DE COLORES DESDE statusConfig
$coloresEstatus = array(
    'CITA AGENDADA' => '#FF9800',
    'INVASIÓN DE CICLO' => '#FFFF00',
    'CITA REAGENDADA' => '#9C27B0',
    'CITA RECUPERADA' => '#4CAF50',
    'CITA NO ATENDIDA' => '#FF6666',
    'APARTADO' => '#1E90FF',
    'PAGO ESPERADO' => '#FF00FF',
    'PERDIDO POR PRECIO' => '#AABBCC',
    'PERDIDO POR HORARIO' => '#336699',
    'REGISTRO' => '#00FFFF',
    'NO LE INTERESA' => '#CC0000',
    'ASESORÍA REALIZADA' => '#00FF00',
    'CITA CONFIRMADA' => '#FFFF00',
    'SEGUIMIENTO' => '#FF4500'
);

?>

<style>
.adm-dashboard {
    background: #f5f5f5;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 12px;
}

/* HEADER ACTUALIZADO - FONDO BLANCO */
.adm-header {
    background: #ffffff;
    padding: 16px 20px;
    border-radius: 6px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 1px solid #e0e0e0;
}

.adm-title {
    font-size: 22px;
    font-weight: 900;
    color: #2c3e50;
    margin-bottom: 4px;
}

.adm-fecha {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
}

/* EMBUDO - COLORES ACTUALIZADOS SEGÚN statusConfig */
.embudo-compacto {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.embudo-title {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.embudo-stage {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-left: 4px solid #6c757d;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.embudo-stage:hover {
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.embudo-stage-label {
    font-size: 10px;
    font-weight: 700;
    color: #2c3e50;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;
}

.embudo-stage-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.embudo-stage-valor {
    font-size: 20px;
    font-weight: 900;
    color: #2c3e50;
}

.embudo-stage-tasa {
    font-size: 9px;
    font-weight: 700;
    background: rgba(108, 117, 125, 0.15);
    color: #666;
    padding: 3px 6px;
    border-radius: 3px;
}

.embudo-stage-cambio {
    font-size: 9px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 3px;
}

/* SEMÁFORO - SIN BORDERS DE COLORES, CON BUSCADOR */
.semaforo-container {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.semaforo-title {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.semaforo-box {
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    padding: 12px;
    height: 100%;
}

.semaforo-box-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 10px;
    margin-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
}

.semaforo-box-title {
    display: flex;
    align-items: center;
    gap: 8px;
}

.semaforo-box-label {
    font-size: 10px;
    font-weight: 800;
    color: #2c3e50;
    text-transform: uppercase;
}

.semaforo-box-count {
    font-size: 18px;
    font-weight: 900;
    color: #2c3e50;
    background: #ffffff;
    padding: 4px 12px;
    border-radius: 12px;
}

/* BUSCADOR EN SEMÁFORO */
.semaforo-search {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 10px;
    margin-bottom: 10px;
    transition: all 0.2s;
}

.semaforo-search:focus {
    outline: none;
    border-color: #2c3e50;
    box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
}

.ejecutivos-scroll {
    max-height: 320px;
    overflow-y: auto;
    padding-right: 6px;
}

.ejecutivos-scroll::-webkit-scrollbar {
    width: 6px;
}

.ejecutivos-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.ejecutivos-scroll::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.ejecutivos-scroll::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* EJECUTIVO ITEM CON BOLITA DE COLOR INDIVIDUAL */
.ejecutivo-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    margin-bottom: 4px;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.ejecutivo-item:hover {
    background: #e9ecef;
    transform: translateX(3px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* BOLITA DE COLOR INDIVIDUAL */
.ejecutivo-circulo {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.ejecutivo-circulo.verde {
    background: #28a745;
}

.ejecutivo-circulo.amarillo {
    background: #ffc107;
}

.ejecutivo-circulo.rojo {
    background: #dc3545;
}

.ejecutivo-foto {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    flex-shrink: 0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.ejecutivo-foto:hover {
    transform: scale(2);
    z-index: 1000;
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}

.ejecutivo-info {
    flex: 1;
    min-width: 0;
}

.ejecutivo-nombre {
    font-size: 9px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ejecutivo-plantel {
    font-size: 7px;
    color: #6c757d;
    line-height: 1.2;
}

.ejecutivo-dias {
    font-size: 8px;
    font-weight: 700;
    line-height: 1.2;
    flex-shrink: 0;
}

.ejecutivo-dias.verde { color: #28a745; }
.ejecutivo-dias.amarillo { color: #ffc107; }
.ejecutivo-dias.rojo { color: #dc3545; }

/* KPI SECTIONS */
.kpi-section {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 14px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.kpi-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px;
    margin-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.kpi-section-title {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-section-total {
    display: flex;
    align-items: center;
    gap: 8px;
}

.kpi-total-valor {
    font-size: 20px;
    font-weight: 900;
    color: #2c3e50;
}

.kpi-total-cambio {
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 3px;
}

.kpi-total-cambio.positivo {
    background: rgba(40, 167, 69, 0.15);
    color: #28a745;
}

.kpi-total-cambio.negativo {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
}

.kpi-plantel-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 10px;
    margin-bottom: 4px;
    background: #f8f9fa;
    border-radius: 4px;
    transition: all 0.2s;
}

.kpi-plantel-item:hover {
    background: #e9ecef;
    transform: translateX(3px);
}

.kpi-plantel-nombre {
    font-size: 9px;
    font-weight: 600;
    color: #495057;
    flex: 1;
}

.kpi-plantel-valor {
    font-size: 14px;
    font-weight: 800;
    color: #2c3e50;
    margin-right: 8px;
}

.kpi-plantel-cambio {
    font-size: 8px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    min-width: 50px;
    text-align: center;
}

/* CHARTS */
.chart-container {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.chart-title {
    font-size: 11px;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* TOP EJECUTIVOS */
.top-ejecutivo-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 6px;
    transition: all 0.2s ease;
}

.top-ejecutivo-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.top-ejecutivo-ranking {
    font-size: 16px;
    font-weight: 900;
    color: #6c757d;
    min-width: 28px;
}

.top-ejecutivo-foto {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    cursor: pointer;
}

.top-ejecutivo-foto:hover {
    transform: scale(2);
    z-index: 1000;
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}

.top-ejecutivo-info {
    flex: 1;
}

.top-ejecutivo-nombre {
    font-size: 10px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
}

.top-ejecutivo-plantel {
    font-size: 8px;
    color: #6c757d;
    line-height: 1.2;
}

.top-ejecutivo-valor {
    font-size: 18px;
    font-weight: 900;
    color: #28a745;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .adm-dashboard {
        padding: 8px;
    }
    
    .embudo-stage-valor {
        font-size: 16px;
    }
    
    .kpi-total-valor {
        font-size: 18px;
    }
}
</style>

<div class="adm-dashboard">
    <!-- HEADER -->
    <div class="adm-header">
        <div class="adm-title">🎓 DASHBOARD ADMISIONES</div>
        <div class="adm-fecha"><?php echo strtoupper($fechaMostrar); ?></div>
    </div>

    <!-- ROW 1: EMBUDO + TENDENCIA -->
    <div class="row">
        <div class="col-md-5">
            <!-- EMBUDO CON COLORES ACTUALIZADOS -->
            <div class="embudo-compacto">
                <div class="embudo-title">🎯 EMBUDO DE CONVERSIÓN</div>
                
                <!-- CONTACTOS - Color: #AABBCC (PERDIDO POR PRECIO) -->
                <div class="embudo-stage" style="border-left-color: #AABBCC;">
                    <div class="embudo-stage-label"><span>📞</span><span>CONTACTOS</span></div>
                    <div class="embudo-stage-info">
                        <span class="embudo-stage-valor"><?php echo $totales['contactos_hoy']; ?></span>
                        <span class="embudo-stage-tasa">100%</span>
                        <span class="embudo-stage-cambio <?php echo $cambioContactos >= 0 ? 'positivo' : 'negativo'; ?>" style="background: <?php echo $cambioContactos >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambioContactos >= 0 ? '#28a745' : '#dc3545'; ?>">
                            <?php echo ($cambioContactos >= 0 ? '▲' : '▼') . number_format(abs($porcContactos), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                
                <!-- CITAS AGENDADAS - Color: #FF9800 (CITA AGENDADA) -->
                <div class="embudo-stage" style="border-left-color: #FF9800;">
                    <div class="embudo-stage-label"><span>📅</span><span>CITAS AGENDADAS</span></div>
                    <div class="embudo-stage-info">
                        <span class="embudo-stage-valor"><?php echo $totales['citas_hoy']; ?></span>
                        <span class="embudo-stage-tasa"><?php echo number_format($tasaCitasTotal, 1); ?>%</span>
                        <span class="embudo-stage-cambio <?php echo $cambioCitas >= 0 ? 'positivo' : 'negativo'; ?>" style="background: <?php echo $cambioCitas >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambioCitas >= 0 ? '#28a745' : '#dc3545'; ?>">
                            <?php echo ($cambioCitas >= 0 ? '▲' : '▼') . number_format(abs($porcCitas), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                
                <!-- CITAS EFECTIVAS - Color: #FF6666 (CITA NO ATENDIDA - color rosa/rojo claro) -->
                <div class="embudo-stage" style="border-left-color: #FF6666;">
                    <div class="embudo-stage-label"><span>✅</span><span>CITAS EFECTIVAS</span></div>
                    <div class="embudo-stage-info">
                        <span class="embudo-stage-valor"><?php echo $totales['efectivas_hoy']; ?></span>
                        <span class="embudo-stage-tasa"><?php echo number_format($tasaEfectivasTotal, 1); ?>%</span>
                        <span class="embudo-stage-cambio <?php echo $cambioEfectivas >= 0 ? 'positivo' : 'negativo'; ?>" style="background: <?php echo $cambioEfectivas >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambioEfectivas >= 0 ? '#28a745' : '#dc3545'; ?>">
                            <?php echo ($cambioEfectivas >= 0 ? '▲' : '▼') . number_format(abs($porcEfectivas), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                
                <!-- REGISTROS - Color: #00FFFF (REGISTRO - cyan/azul cielo) -->
                <div class="embudo-stage" style="border-left-color: #00FFFF;">
                    <div class="embudo-stage-label"><span>🎓</span><span>REGISTROS</span></div>
                    <div class="embudo-stage-info">
                        <span class="embudo-stage-valor"><?php echo $totales['registros_hoy']; ?></span>
                        <span class="embudo-stage-tasa"><?php echo number_format($tasaRegistrosTotal, 1); ?>%</span>
                        <span class="embudo-stage-cambio <?php echo $cambioRegistros >= 0 ? 'positivo' : 'negativo'; ?>" style="background: <?php echo $cambioRegistros >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambioRegistros >= 0 ? '#28a745' : '#dc3545'; ?>">
                            <?php echo ($cambioRegistros >= 0 ? '▲' : '▼') . number_format(abs($porcRegistros), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="chart-container">
                <div class="chart-title">📊 TENDENCIA EMBUDO 7 DÍAS</div>
                <div id="chartEmbudoTendencia" style="height: 280px;"></div>
            </div>
        </div>
    </div>

    <!-- ROW 2: SEMÁFORO EN 3 CONTENEDORES CON BÚSQUEDA -->
    <div class="row">
        <div class="col-md-12">
            <div class="semaforo-container">
                <div class="semaforo-title">🚦 SEMÁFORO DE CONEXIONES <?php echo $multiplePlanteles ? '(NACIONAL)' : '(PLANTEL)'; ?></div>
                
                <div class="row">
                    <!-- VERDES -->
                    <div class="col-md-4">
                        <div class="semaforo-box">
                            <div class="semaforo-box-header">
                                <div class="semaforo-box-title">
                                    <span class="semaforo-box-label">ACTIVOS (0-1d)</span>
                                </div>
                                <span class="semaforo-box-count"><?php echo $totalVerdes; ?></span>
                            </div>
                            <input type="text" class="semaforo-search" placeholder="🔍 Buscar ejecutivo..." onkeyup="filtrarEjecutivos(this, 'verdes')">
                            <div class="ejecutivos-scroll" id="lista-verdes">
                                <?php foreach($ejecutivosVerdes as $eje): 
                                    $fotoUrl = obtenerValidacionFotoUsuarioServer($eje['fot_eje']);
                                    $diasTexto = $eje['dias_sin_conexion'] == 0 ? 'Hoy' : 'Hace ' . $eje['dias_sin_conexion'] . 'd';
                                ?>
                                <div class="ejecutivo-item" data-nombre="<?php echo strtolower($eje['nom_eje']); ?>">
                                    <span class="ejecutivo-circulo verde"></span>
                                    <img src="<?php echo $fotoUrl; ?>" class="ejecutivo-foto" alt="<?php echo $eje['nom_eje']; ?>">
                                    <div class="ejecutivo-info">
                                        <div class="ejecutivo-nombre"><?php echo strtoupper($eje['nom_eje']); ?></div>
                                        <div class="ejecutivo-plantel">🕋 <?php echo $eje['nom_pla']; ?></div>
                                    </div>
                                    <span class="ejecutivo-dias verde"><?php echo $diasTexto; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- AMARILLOS -->
                    <div class="col-md-4">
                        <div class="semaforo-box">
                            <div class="semaforo-box-header">
                                <div class="semaforo-box-title">
                                    <span class="semaforo-box-label">ADVERTENCIA (2-4d)</span>
                                </div>
                                <span class="semaforo-box-count"><?php echo $totalAmarillos; ?></span>
                            </div>
                            <input type="text" class="semaforo-search" placeholder="🔍 Buscar ejecutivo..." onkeyup="filtrarEjecutivos(this, 'amarillos')">
                            <div class="ejecutivos-scroll" id="lista-amarillos">
                                <?php foreach($ejecutivosAmarillos as $eje): 
                                    $fotoUrl = obtenerValidacionFotoUsuarioServer($eje['fot_eje']);
                                    $diasTexto = 'Hace ' . $eje['dias_sin_conexion'] . 'd';
                                ?>
                                <div class="ejecutivo-item" data-nombre="<?php echo strtolower($eje['nom_eje']); ?>">
                                    <span class="ejecutivo-circulo amarillo"></span>
                                    <img src="<?php echo $fotoUrl; ?>" class="ejecutivo-foto" alt="<?php echo $eje['nom_eje']; ?>">
                                    <div class="ejecutivo-info">
                                        <div class="ejecutivo-nombre"><?php echo strtoupper($eje['nom_eje']); ?></div>
                                        <div class="ejecutivo-plantel">🕋 <?php echo $eje['nom_pla']; ?></div>
                                    </div>
                                    <span class="ejecutivo-dias amarillo"><?php echo $diasTexto; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ROJOS -->
                    <div class="col-md-4">
                        <div class="semaforo-box">
                            <div class="semaforo-box-header">
                                <div class="semaforo-box-title">
                                    <span class="semaforo-box-label">RIESGO (5+ d)</span>
                                </div>
                                <span class="semaforo-box-count"><?php echo $totalRojos; ?></span>
                            </div>
                            <input type="text" class="semaforo-search" placeholder="🔍 Buscar ejecutivo..." onkeyup="filtrarEjecutivos(this, 'rojos')">
                            <div class="ejecutivos-scroll" id="lista-rojos">
                                <?php foreach($ejecutivosRojos as $eje): 
                                    $fotoUrl = obtenerValidacionFotoUsuarioServer($eje['fot_eje']);
                                    $diasTexto = is_null($eje['ult_eje']) ? 'Sin conexión' : 'Hace ' . $eje['dias_sin_conexion'] . 'd';
                                ?>
                                <div class="ejecutivo-item" data-nombre="<?php echo strtolower($eje['nom_eje']); ?>">
                                    <span class="ejecutivo-circulo rojo"></span>
                                    <img src="<?php echo $fotoUrl; ?>" class="ejecutivo-foto" alt="<?php echo $eje['nom_eje']; ?>">
                                    <div class="ejecutivo-info">
                                        <div class="ejecutivo-nombre"><?php echo strtoupper($eje['nom_eje']); ?></div>
                                        <div class="ejecutivo-plantel">🕋 <?php echo $eje['nom_pla']; ?></div>
                                    </div>
                                    <span class="ejecutivo-dias rojo"><?php echo $diasTexto; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 3: DISTRIBUCIÓN ESTATUS + PRODUCTOS -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">📋 DISTRIBUCIÓN POR ESTATUS</div>
                <div id="chartEstatus" style="height: 400px;"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">🎓 PRODUCTOS ACADÉMICOS</div>
                <div id="chartProductos" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- ROW 4: CANALES + TIPO CITA -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">📱 CANALES DE CONTACTO</div>
                <div id="chartCanales" style="height: 300px;"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">📞 TIPO DE CITA</div>
                <div id="chartTipoCita" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- ROW 5: TOP EJECUTIVOS + EMBUDO POR HORA -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">🏆 TOP 10 EJECUTIVOS (REGISTROS HOY)</div>
                <div>
                    <?php 
                    $ranking = 1;
                    foreach($topEjecutivos as $eje): 
                        $fotoUrl = obtenerValidacionFotoUsuarioServer($eje['fot_eje']);
                    ?>
                    <div class="top-ejecutivo-item">
                        <div class="top-ejecutivo-ranking">#<?php echo $ranking; ?></div>
                        <img src="<?php echo $fotoUrl; ?>" class="top-ejecutivo-foto" alt="<?php echo $eje['nom_eje']; ?>">
                        <div class="top-ejecutivo-info">
                            <div class="top-ejecutivo-nombre"><?php echo strtoupper($eje['nom_eje']); ?></div>
                            <div class="top-ejecutivo-plantel">🕋 <?php echo $eje['nom_pla']; ?></div>
                        </div>
                        <div class="top-ejecutivo-valor"><?php echo $eje['registros_hoy']; ?></div>
                    </div>
                    <?php 
                    $ranking++;
                    endforeach; 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">🔥 EMBUDO POR HORA</div>
                <div id="chartEmbudoHoras" style="height: 380px;"></div>
            </div>
        </div>
    </div>

    <!-- ROW 6: DESGLOSE POR PLANTEL (SI ES MULTIPLANTELES) -->
    <?php if($multiplePlanteles): ?>
    <div class="row">
        <div class="col-md-3">
            <div class="kpi-section">
                <div class="kpi-section-header">
                    <div class="kpi-section-title">📞 CONTACTOS</div>
                    <div class="kpi-section-total">
                        <span class="kpi-total-valor"><?php echo $totales['contactos_hoy']; ?></span>
                        <span class="kpi-total-cambio <?php echo $cambioContactos >= 0 ? 'positivo' : 'negativo'; ?>">
                            <?php echo ($cambioContactos >= 0 ? '▲' : '▼') . number_format(abs($porcContactos), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                <?php foreach($rankingContactos as $item): if($item['contactos_hoy'] > 0): 
                    $cambio = $item['contactos_hoy'] - $item['contactos_ayer'];
                    $porc = $item['contactos_ayer'] > 0 ? (($cambio / $item['contactos_ayer']) * 100) : 0;
                ?>
                <div class="kpi-plantel-item">
                    <span class="kpi-plantel-nombre">🕋 <?php echo strtoupper(substr($item['nombre'], 0, 12)); ?></span>
                    <span class="kpi-plantel-valor"><?php echo $item['contactos_hoy']; ?></span>
                    <span class="kpi-plantel-cambio" style="background: <?php echo $cambio >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambio >= 0 ? '#28a745' : '#dc3545'; ?>">
                        <?php echo ($item['contactos_ayer'] > 0) ? (($cambio >= 0 ? '▲' : '▼') . number_format(abs($porc), 0) . '%') : 'NEW'; ?>
                    </span>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-section">
                <div class="kpi-section-header">
                    <div class="kpi-section-title">📅 CITAS</div>
                    <div class="kpi-section-total">
                        <span class="kpi-total-valor"><?php echo $totales['citas_hoy']; ?></span>
                        <span class="kpi-total-cambio <?php echo $cambioCitas >= 0 ? 'positivo' : 'negativo'; ?>">
                            <?php echo ($cambioCitas >= 0 ? '▲' : '▼') . number_format(abs($porcCitas), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                <?php foreach($rankingCitas as $item): if($item['citas_hoy'] > 0): 
                    $cambio = $item['citas_hoy'] - $item['citas_ayer'];
                    $porc = $item['citas_ayer'] > 0 ? (($cambio / $item['citas_ayer']) * 100) : 0;
                ?>
                <div class="kpi-plantel-item">
                    <span class="kpi-plantel-nombre">🕋 <?php echo strtoupper(substr($item['nombre'], 0, 12)); ?></span>
                    <span class="kpi-plantel-valor"><?php echo $item['citas_hoy']; ?></span>
                    <span class="kpi-plantel-cambio" style="background: <?php echo $cambio >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambio >= 0 ? '#28a745' : '#dc3545'; ?>">
                        <?php echo ($item['citas_ayer'] > 0) ? (($cambio >= 0 ? '▲' : '▼') . number_format(abs($porc), 0) . '%') : 'NEW'; ?>
                    </span>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-section">
                <div class="kpi-section-header">
                    <div class="kpi-section-title">✅ EFECTIVAS</div>
                    <div class="kpi-section-total">
                        <span class="kpi-total-valor"><?php echo $totales['efectivas_hoy']; ?></span>
                        <span class="kpi-total-cambio <?php echo $cambioEfectivas >= 0 ? 'positivo' : 'negativo'; ?>">
                            <?php echo ($cambioEfectivas >= 0 ? '▲' : '▼') . number_format(abs($porcEfectivas), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                <?php foreach($rankingEfectivas as $item): if($item['efectivas_hoy'] > 0): 
                    $cambio = $item['efectivas_hoy'] - $item['efectivas_ayer'];
                    $porc = $item['efectivas_ayer'] > 0 ? (($cambio / $item['efectivas_ayer']) * 100) : 0;
                ?>
                <div class="kpi-plantel-item">
                    <span class="kpi-plantel-nombre">🕋 <?php echo strtoupper(substr($item['nombre'], 0, 12)); ?></span>
                    <span class="kpi-plantel-valor"><?php echo $item['efectivas_hoy']; ?></span>
                    <span class="kpi-plantel-cambio" style="background: <?php echo $cambio >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambio >= 0 ? '#28a745' : '#dc3545'; ?>">
                        <?php echo ($item['efectivas_ayer'] > 0) ? (($cambio >= 0 ? '▲' : '▼') . number_format(abs($porc), 0) . '%') : 'NEW'; ?>
                    </span>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-section">
                <div class="kpi-section-header">
                    <div class="kpi-section-title">🎓 REGISTROS</div>
                    <div class="kpi-section-total">
                        <span class="kpi-total-valor"><?php echo $totales['registros_hoy']; ?></span>
                        <span class="kpi-total-cambio <?php echo $cambioRegistros >= 0 ? 'positivo' : 'negativo'; ?>">
                            <?php echo ($cambioRegistros >= 0 ? '▲' : '▼') . number_format(abs($porcRegistros), 0) . '%'; ?>
                        </span>
                    </div>
                </div>
                <?php foreach($rankingRegistros as $item): if($item['registros_hoy'] > 0): 
                    $cambio = $item['registros_hoy'] - $item['registros_ayer'];
                    $porc = $item['registros_ayer'] > 0 ? (($cambio / $item['registros_ayer']) * 100) : 0;
                ?>
                <div class="kpi-plantel-item">
                    <span class="kpi-plantel-nombre">🕋 <?php echo strtoupper(substr($item['nombre'], 0, 12)); ?></span>
                    <span class="kpi-plantel-valor"><?php echo $item['registros_hoy']; ?></span>
                    <span class="kpi-plantel-cambio" style="background: <?php echo $cambio >= 0 ? 'rgba(40, 167, 69, 0.15)' : 'rgba(220, 53, 69, 0.15)'; ?>; color: <?php echo $cambio >= 0 ? '#28a745' : '#dc3545'; ?>">
                        <?php echo ($item['registros_ayer'] > 0) ? (($cambio >= 0 ? '▲' : '▼') . number_format(abs($porc), 0) . '%') : 'NEW'; ?>
                    </span>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- JAVASCRIPT PARA BÚSQUEDA EN SEMÁFOROS -->
<script>
function filtrarEjecutivos(input, tipo) {
    const filtro = input.value.toLowerCase();
    const lista = document.getElementById('lista-' + tipo);
    const items = lista.getElementsByClassName('ejecutivo-item');
    
    for (let i = 0; i < items.length; i++) {
        const nombre = items[i].getAttribute('data-nombre');
        if (nombre.includes(filtro)) {
            items[i].style.display = '';
        } else {
            items[i].style.display = 'none';
        }
    }
}
</script>

<!-- GOOGLE CHARTS -->
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawChartEmbudoTendencia();
        drawChartEstatus();
        drawChartProductos();
        drawChartCanales();
        drawChartTipoCita();
        drawChartEmbudoHoras();
    }

    function drawChartEmbudoTendencia() {
        var data = google.visualization.arrayToDataTable([
            ['Día', 'Contactos', 'Citas', 'Efectivas', 'Registros'],
            <?php 
            $first = true;
            foreach($ultimos7Dias as $dia) {
                if(!$first) echo ",";
                echo "['" . $dia['dia'] . "', " . $dia['contactos'] . ", " . $dia['citas'] . ", " . $dia['efectivas'] . ", " . $dia['registros'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '85%', height: '70%'},
            colors: ['#AABBCC', '#FF9800', '#FF6666', '#00FFFF'],
            legend: {position: 'top', textStyle: {color: '#2c3e50', fontSize: 10, bold: true}},
            hAxis: {textStyle: {color: '#666', fontSize: 10}, gridlines: {color: '#f0f0f0'}},
            vAxis: {textStyle: {color: '#666', fontSize: 10}, gridlines: {color: '#f0f0f0'}},
            bar: {groupWidth: '70%'}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chartEmbudoTendencia'));
        chart.draw(data, options);
    }

    function drawChartEstatus() {
        var data = google.visualization.arrayToDataTable([
            ['Estatus', 'Total', { role: 'style' }],
            <?php 
            $first = true;
            foreach(array_slice($distribucionEstatus, 0, 12) as $item) {
                if(!$first) echo ",";
                $color = isset($coloresEstatus[$item['estatus']]) ? $coloresEstatus[$item['estatus']] : '#6c757d';
                echo "['" . substr($item['estatus'], 0, 18) . "', " . $item['total_hoy'] . ", '" . $color . "']";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '80%', height: '80%'},
            legend: {position: 'none'},
            hAxis: {textStyle: {color: '#666', fontSize: 9}, format: 'short', gridlines: {color: '#f0f0f0'}},
            vAxis: {textStyle: {color: '#666', fontSize: 9}, gridlines: {color: '#f0f0f0'}},
            bar: {groupWidth: '70%'}
        };

        var chart = new google.visualization.BarChart(document.getElementById('chartEstatus'));
        chart.draw(data, options);
    }

    function drawChartProductos() {
        var data = google.visualization.arrayToDataTable([
            ['Producto', 'Total'],
            <?php 
            $first = true;
            foreach($distribucionProductos as $item) {
                if(!$first) echo ",";
                echo "['" . substr($item['producto'], 0, 15) . "', " . $item['total_hoy'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            pieHole: 0.4,
            chartArea: {width: '90%', height: '80%'},
            colors: ['#28a745', '#ffc107', '#dc3545', '#6c757d', '#2c3e50', '#ff9800', '#4CAF50', '#9C27B0'],
            legend: {position: 'right', textStyle: {color: '#2c3e50', fontSize: 9}},
            pieSliceText: 'value',
            pieSliceTextStyle: {color: '#fff', fontSize: 10, bold: true}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartProductos'));
        chart.draw(data, options);
    }

    function drawChartCanales() {
        var data = google.visualization.arrayToDataTable([
            ['Canal', 'Total'],
            <?php 
            $first = true;
            foreach($rankingCanales as $item) {
                if(!$first) echo ",";
                echo "['" . substr($item['canal'], 0, 12) . "', " . $item['total'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '75%', height: '75%'},
            colors: ['#2c3e50'],
            legend: {position: 'none'},
            hAxis: {textStyle: {color: '#666', fontSize: 9}, format: 'short', gridlines: {color: '#f0f0f0'}},
            vAxis: {textStyle: {color: '#666', fontSize: 9}, gridlines: {color: '#f0f0f0'}},
            bar: {groupWidth: '70%'}
        };

        var chart = new google.visualization.BarChart(document.getElementById('chartCanales'));
        chart.draw(data, options);
    }

    function drawChartTipoCita() {
        var data = google.visualization.arrayToDataTable([
            ['Tipo', 'Total'],
            <?php 
            $first = true;
            foreach($distribucionTipoCita as $item) {
                if(!$first) echo ",";
                echo "['" . $item['tipo'] . "', " . $item['total'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            pieHole: 0.5,
            chartArea: {width: '90%', height: '80%'},
            colors: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
            legend: {position: 'bottom', textStyle: {color: '#2c3e50', fontSize: 9}},
            pieSliceText: 'value',
            pieSliceTextStyle: {color: '#fff', fontSize: 11, bold: true}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartTipoCita'));
        chart.draw(data, options);
    }

    function drawChartEmbudoHoras() {
        var data = google.visualization.arrayToDataTable([
            ['Hora', 'Contactos', 'Citas', 'Efectivas', 'Registros'],
            <?php 
            $first = true;
            foreach($rendimientoEmbudoHoras as $item) {
                if(!$first) echo ",";
                $horaFormato = $item['hora'] . ':00';
                echo "['" . $horaFormato . "', " . $item['contactos'] . ", " . $item['citas'] . ", " . $item['efectivas'] . ", " . $item['registros'] . "]";
                $first = false;
            }
            ?>
        ]);

        var options = {
            backgroundColor: 'transparent',
            chartArea: {width: '85%', height: '75%'},
            colors: ['#AABBCC', '#FF9800', '#FF6666', '#00FFFF'],
            legend: {position: 'top', textStyle: {color: '#2c3e50', fontSize: 9, bold: true}},
            hAxis: {
                textStyle: {color: '#666', fontSize: 9}, 
                slantedText: true,
                slantedTextAngle: 45,
                gridlines: {color: '#f0f0f0'}
            },
            vAxis: {textStyle: {color: '#666', fontSize: 10}, gridlines: {color: '#f0f0f0'}},
            bar: {groupWidth: '75%'}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chartEmbudoHoras'));
        chart.draw(data, options);
    }

    window.addEventListener('resize', drawCharts);
</script>

<?php
// Cerrar conexión si existe
if(isset($db)) {
    mysqli_close($db);
}
?>