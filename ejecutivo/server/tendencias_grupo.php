<?php  
require('../inc/cabeceras.php');
require('../inc/funciones.php');

if (!isset($_POST['id_gen'])) {
    echo json_encode(array('error' => true, 'mensaje' => 'ID de generación requerido'));
    exit;
}

$id_gen = intval($_POST['id_gen']);

// Obtener datos de generación
$sqlGen = "SELECT nom_gen, ini_gen, fin_gen FROM generacion WHERE id_gen = $id_gen";
$resultadoGen = mysqli_query($db, $sqlGen);

if (!$resultadoGen || mysqli_num_rows($resultadoGen) == 0) {
    echo json_encode(array('error' => true, 'mensaje' => 'Generación no encontrada'));
    exit;
}

$datosGen = mysqli_fetch_assoc($resultadoGen);
$fechaInicioGen = new DateTime($datosGen['ini_gen']);
$fechaFinGen = new DateTime($datosGen['fin_gen']);

error_log("=== TENDENCIAS RUBROS CON RANGO DINÁMICO INTELIGENTE ===");
error_log("GENERACIÓN: " . $datosGen['nom_gen']);
error_log("PERÍODO: " . $datosGen['ini_gen'] . " → " . $datosGen['fin_gen']);

// Calcular rango dinámico con manejo inteligente de años
$fechaInicioVisualizacion = clone $fechaInicioGen;
$fechaInicioVisualizacion->modify('-3 months');

$fechaFinVisualizacion = clone $fechaFinGen;
$fechaFinVisualizacion->modify('+3 months');

// Detectar si el rango cruza años y ajustar para balance visual
$añoInicioGen = intval($fechaInicioGen->format('Y'));
$añoFinGen = intval($fechaFinGen->format('Y'));
$añoInicioVis = intval($fechaInicioVisualizacion->format('Y'));
$añoFinVis = intval($fechaFinVisualizacion->format('Y'));

// Lógica inteligente para rangos que cruzan años
if ($añoInicioVis != $añoFinVis) {
    $mesInicioVis = intval($fechaInicioVisualizacion->format('m'));
    $mesFinVis = intval($fechaFinVisualizacion->format('m'));
    
    // Si el rango visual va desde muy temprano en el año hasta muy tarde,
    // balancear para que se vea simétrico
    if ($mesInicioVis <= 3 && $mesFinVis <= 3) {
        // Ej: Ene 2025 → Mar 2026, extender hasta Jun 2026 para balance
        $fechaFinVisualizacion = new DateTime($añoFinVis . '-06-30');
    } else if ($mesInicioVis >= 10 && $mesFinVis <= 3) {
        // Ej: Oct 2025 → Feb 2026, mantener hasta Mar 2026
        $fechaFinVisualizacion = new DateTime($añoFinVis . '-03-31');
    }
    // Para otros casos, mantener el cálculo original (+3 meses)
}

error_log("RANGO VISUALIZACIÓN: " . $fechaInicioVisualizacion->format('Y-m-d') . " → " . $fechaFinVisualizacion->format('Y-m-d'));

$datosMensuales = array();

// Generar array de meses dentro del rango calculado
$fechaActual = clone $fechaInicioVisualizacion;
$fechaActual->modify('first day of this month');

while ($fechaActual <= $fechaFinVisualizacion) {
    $año = $fechaActual->format('Y');
    $mes = $fechaActual->format('m');
    $mesStr = $año . '-' . $mes;
    
    // Calcular fechas de inicio y fin del mes
    $fechaInicioMes = $año . '-' . $mes . '-01';
    $ultimoDiaMes = $fechaActual->format('t');
    $fechaFinMes = $año . '-' . $mes . '-' . $ultimoDiaMes;
    
    $fechaMesInicio = new DateTime($fechaInicioMes);
    $fechaMesFin = new DateTime($fechaFinMes);
    
    // Verificar si el mes está dentro del período de generación
    $mesEnPeriodo = ($fechaMesInicio <= $fechaFinGen && $fechaMesFin >= $fechaInicioGen);
    
    error_log("MES $mesStr: " . ($mesEnPeriodo ? "ACTIVO" : "INACTIVO"));
    
    if ($mesEnPeriodo) {
        // CONSULTA COMPLETA CON TODOS LOS DATOS NECESARIOS
        $sql = "
            SELECT 
                -- RUBRO 1: CONTEOS BÁSICOS
                COUNT(DISTINCT ar.id_alu_ram) AS total_alumnos,
                SUM(CASE WHEN obtener_adeudo_alumno_periodo_tipo(ar.id_alu_ram, '$fechaInicioMes', '$fechaFinMes', 'Colegiatura') > 0 THEN 1 ELSE 0 END) AS deudores,
                
                -- RUBRO 2: ANÁLISIS FINANCIERO
                IFNULL(SUM(obtener_cobrado_alumno_periodo_tipo(ar.id_alu_ram, '$fechaInicioMes', '$fechaFinMes', 'Colegiatura')), 0) AS cobrado,
                IFNULL(SUM(obtener_potencial_alumno_periodo_tipo(ar.id_alu_ram, '$fechaInicioMes', '$fechaFinMes', 'Colegiatura')), 0) AS potencial,
                
                -- RUBRO 3: ESTATUS AGRUPADOS (10 estatus)
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'PROSPECTO' THEN 1 ELSE 0 END) AS prospecto,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'REGISTRO' THEN 1 ELSE 0 END) AS registro,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'REGISTRADO' THEN 1 ELSE 0 END) AS registrado,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'ACTIVO' THEN 1 ELSE 0 END) AS activo,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'NP' THEN 1 ELSE 0 END) AS np,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'BAJA' THEN 1 ELSE 0 END) AS baja,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'DESERCION' THEN 1 ELSE 0 END) AS desercion,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'FIN CURSO' THEN 1 ELSE 0 END) AS fin_curso,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'REINGRESO' THEN 1 ELSE 0 END) AS reingreso,
                SUM(CASE WHEN OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, '$fechaFinMes', ar.est1_alu_ram) = 'GRADUADO' THEN 1 ELSE 0 END) AS graduado
                
            FROM alu_ram ar
            INNER JOIN alumno a ON a.id_alu = ar.id_alu1
            INNER JOIN rama r ON r.id_ram = ar.id_ram3
            INNER JOIN plantel p ON p.id_pla = r.id_pla1
            WHERE ar.id_gen1 = $id_gen
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if (!$resultado) {
            error_log("ERROR SQL MES $mesStr: " . mysqli_error($db));
            // Continúar con valores en cero en lugar de saltar el mes
            $fila = array(
                'total_alumnos' => 0, 'deudores' => 0, 'cobrado' => 0, 'potencial' => 0,
                'prospecto' => 0, 'registro' => 0, 'registrado' => 0, 'activo' => 0,
                'np' => 0, 'baja' => 0, 'desercion' => 0, 'fin_curso' => 0, 
                'reingreso' => 0, 'graduado' => 0
            );
        } else {
            $fila = mysqli_fetch_assoc($resultado);
        }
        
        // Calcular pagadores
        $totalAlumnos = intval($fila['total_alumnos']);
        $deudores = intval($fila['deudores']);
        $pagadores = max(0, $totalAlumnos - $deudores);
        
        // Estructurar datos por rubros
        $datosMensuales[$mesStr] = array(
            // RUBRO 1: CONTEOS BÁSICOS
            'alumnos' => $totalAlumnos,
            'pagadores' => $pagadores,
            'deudores' => $deudores,
            
            // RUBRO 2: ANÁLISIS FINANCIERO  
            'cobrado' => floatval($fila['cobrado']),
            'potencial' => floatval($fila['potencial']),
            
            // RUBRO 3: ESTATUS AGRUPADOS
            'estatus' => array(
                'PROSPECTO' => intval($fila['prospecto']),
                'REGISTRO' => intval($fila['registro']),
                'REGISTRADO' => intval($fila['registrado']),
                'ACTIVO' => intval($fila['activo']),
                'NP' => intval($fila['np']),
                'BAJA' => intval($fila['baja']),
                'DESERCION' => intval($fila['desercion']),
                'FIN CURSO' => intval($fila['fin_curso']),
                'REINGRESO' => intval($fila['reingreso']),
                'GRADUADO' => intval($fila['graduado'])
            ),
            
            // METADATOS
            'activo' => true,
            'fecha_inicio' => $fechaInicioMes,
            'fecha_fin' => $fechaFinMes,
            'año' => intval($año),
            'mes' => intval($mes)
        );
        
        error_log("MES $mesStr PROCESADO - Alumnos: $totalAlumnos, Cobrado: " . $fila['cobrado']);
        
    } else {
        // Mes fuera del período de generación - valores en cero
        $datosMensuales[$mesStr] = array(
            // RUBRO 1: CONTEOS BÁSICOS
            'alumnos' => 0,
            'pagadores' => 0,
            'deudores' => 0,
            
            // RUBRO 2: ANÁLISIS FINANCIERO
            'cobrado' => 0.0,
            'potencial' => 0.0,
            
            // RUBRO 3: ESTATUS AGRUPADOS
            'estatus' => array(
                'PROSPECTO' => 0, 'REGISTRO' => 0, 'REGISTRADO' => 0,
                'ACTIVO' => 0, 'NP' => 0, 'BAJA' => 0,
                'DESERCION' => 0, 'FIN CURSO' => 0, 'REINGRESO' => 0, 'GRADUADO' => 0
            ),
            
            // METADATOS
            'activo' => false,
            'fecha_inicio' => $fechaInicioMes,
            'fecha_fin' => $fechaFinMes,
            'año' => intval($año),
            'mes' => intval($mes)
        );
        
        error_log("MES $mesStr INACTIVO - Fuera del período de generación");
    }
    
    // Avanzar al siguiente mes
    $fechaActual->modify('+1 month');
}

// Calcular estadísticas mejoradas - PHP 5.6 compatible
$mesesActivos = array();
foreach($datosMensuales as $mes) {
    if($mes['activo']) {
        $mesesActivos[] = $mes;
    }
}

$keys = array_keys($datosMensuales);
$primerMes = !empty($keys) ? $keys[0] : null;
$ultimoMes = !empty($keys) ? $keys[count($keys) - 1] : null;

$estadisticas = array(
    'meses_activos' => count($mesesActivos),
    'total_meses' => count($datosMensuales),
    'periodo_generacion' => array(
        'inicio' => $datosGen['ini_gen'],
        'fin' => $datosGen['fin_gen']
    ),
    'periodo_visualizacion' => array(
        'inicio' => $fechaInicioVisualizacion->format('Y-m-d'),
        'fin' => $fechaFinVisualizacion->format('Y-m-d')
    ),
    'cruza_años' => ($añoInicioVis != $añoFinVis),
    'años_abarcados' => array($añoInicioVis, $añoFinVis)
);

// Información adicional del rango para debugging
$infoRango = array(
    'meses_antes_generacion' => 3,
    'meses_despues_generacion' => 3,
    'ajuste_balance_visual' => ($añoInicioVis != $añoFinVis),
    'primer_mes' => $primerMes,
    'ultimo_mes' => $ultimoMes
);

// Respuesta final estructurada
$respuesta = array(
    'error' => false,
    'datos' => $datosMensuales,
    'info_generacion' => $datosGen,
    'estadisticas' => $estadisticas,
    'info_rango' => $infoRango,
    'rubros_disponibles' => array(
        'conteos' => array('alumnos', 'pagadores', 'deudores'),
        'financiero' => array('cobrado', 'potencial'),
        'estatus' => array('PROSPECTO', 'REGISTRO', 'REGISTRADO', 'ACTIVO', 'NP', 'BAJA', 'DESERCION', 'FIN CURSO', 'REINGRESO', 'GRADUADO')
    )
);

error_log("BACKEND COMPLETADO CON RANGO DINÁMICO INTELIGENTE");
error_log("TOTAL MESES PROCESADOS: " . count($datosMensuales));
error_log("MESES ACTIVOS: " . count($mesesActivos) . "/" . count($datosMensuales));
error_log("RANGO FINAL: " . $primerMes . " → " . $ultimoMes);
error_log("CRUZA AÑOS: " . ($estadisticas['cruza_años'] ? 'SÍ' : 'NO'));

echo json_encode($respuesta);
?>