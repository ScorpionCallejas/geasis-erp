<?php  
// server/controlador_evento.php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

// ============================================
// OBTENER PLANTELES DEL EJECUTIVO
// ============================================
function obtenerPlantelesEjecutivo($db, $id_eje) {
    $planteles = array();
    
    $sqlPlantelesEje = "
        SELECT DISTINCT p.id_pla 
        FROM plantel p
        INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
        WHERE pe.id_eje = '$id_eje'
    ";
    $resultado = mysqli_query($db, $sqlPlantelesEje);
    
    if(mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_assoc($resultado)) {
            $planteles[] = $fila['id_pla'];
        }
    } else {
        $sqlDefault = "
            SELECT p.id_pla 
            FROM plantel p
            INNER JOIN ejecutivo e ON p.id_pla = e.id_pla
            WHERE e.id_eje = '$id_eje'
        ";
        $resultadoDefault = mysqli_query($db, $sqlDefault);
        if(mysqli_num_rows($resultadoDefault) > 0) {
            while($fila = mysqli_fetch_assoc($resultadoDefault)) {
                $planteles[] = $fila['id_pla'];
            }
        }
    }
    
    return $planteles;
}

// ============================================
// OBTENER LISTA DE PLANTELES
// ============================================
if ($action == 'obtenerPlanteles') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT id_pla, nom_pla 
        FROM plantel 
        WHERE id_pla IN ($plantelesStr)
        ORDER BY nom_pla ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $lista = array();
    
    while($fila = mysqli_fetch_assoc($resultado)) {
        $lista[] = $fila;
    }
    
    echo json_encode(array(
        'resultado' => 'success',
        'planteles' => $lista
    ));
    exit;
}

// ============================================
// 🔥 OBTENER EVENTOS DEL AÑO COMPLETO (VISTA ANUAL)
// ============================================
if ($action == 'obtenerEventosAnual') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $anio = isset($_POST['anio']) ? mysqli_real_escape_string($db, $_POST['anio']) : date('Y');
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $fecha_inicio = "$anio-01-01";
    $fecha_fin = "$anio-12-31";
    $fecha_actual = date('Y-m-d');
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.ini_eve as fecha,
            e.est_eve as estado
        FROM evento e
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.ini_eve BETWEEN '$fecha_inicio' AND '$fecha_fin'
        
        UNION ALL
        
        SELECT 
            'generacion' as fuente,
            gp.id_gru_pag as id,
            gp.con_gru_pag as titulo,
            gp.ini_gru_pag as fecha,
            gp.val_gru_pag as estado
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
          AND gp.ini_gru_pag BETWEEN '$fecha_inicio' AND '$fecha_fin'
        GROUP BY gp.id_gru_pag
        
        ORDER BY fecha ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        $total = 0;
        $vencidos = 0;
        $pendientes = 0;
        $resueltos = 0;
        
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
            $total++;
            
            if ($fila['estado'] === 'Resuelto') {
                $resueltos++;
            } else if ($fila['fecha'] < $fecha_actual) {
                $vencidos++;
            } else {
                $pendientes++;
            }
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos,
            'stats' => array(
                'total' => $total,
                'vencidos' => $vencidos,
                'pendientes' => $pendientes,
                'resueltos' => $resueltos
            )
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER EVENTOS PARA EL CALENDARIO (UNION)
// ============================================
if ($action == 'obtenerEventosCalendario') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $mes = isset($_POST['mes']) ? mysqli_real_escape_string($db, $_POST['mes']) : date('m');
    $anio = isset($_POST['anio']) ? mysqli_real_escape_string($db, $_POST['anio']) : date('Y');
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $fecha_inicio = "$anio-$mes-01";
    $ultima_dia = date('t', strtotime($fecha_inicio));
    $fecha_fin = "$anio-$mes-$ultima_dia";
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.des_eve as descripcion,
            e.ini_eve as fecha,
            e.fin_eve as fecha_fin,
            e.est_eve as estado,
            e.tip_eve as tipo,
            e.cat_eve as categoria,
            e.hor_eve as hora,
            e.rec_eve as recurrente,
            e.tipo_rec_eve as tipo_recurrente,
            e.id_eve_padre as id_padre,
            e.id_pla as id_plantel,
            p.nom_pla as plantel,
            NULL as subtitulo,
            NULL as id_generacion,
            NULL as generacion,
            NULL as programa
        FROM evento e
        LEFT JOIN plantel p ON e.id_pla = p.id_pla
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND (
              (e.ini_eve BETWEEN '$fecha_inicio' AND '$fecha_fin')
              OR (e.fin_eve BETWEEN '$fecha_inicio' AND '$fecha_fin')
          )
        
        UNION ALL
        
        SELECT 
            'generacion' as fuente,
            gp.id_gru_pag as id,
            gp.con_gru_pag as titulo,
            gp.des_gru_pag as descripcion,
            gp.ini_gru_pag as fecha,
            gp.fin_gru_pag as fecha_fin,
            gp.val_gru_pag as estado,
            'Académico' as tipo,
            'Generaciones' as categoria,
            '00:00:00' as hora,
            'No' as recurrente,
            NULL as tipo_recurrente,
            NULL as id_padre,
            r.id_pla1 as id_plantel,
            p.nom_pla as plantel,
            gp.sem_gru_pag as subtitulo,
            g.id_gen as id_generacion,
            g.nom_gen as generacion,
            r.nom_ram as programa
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN plantel p ON r.id_pla1 = p.id_pla
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
          AND (
              (gp.ini_gru_pag BETWEEN '$fecha_inicio' AND '$fecha_fin')
              OR (gp.fin_gru_pag BETWEEN '$fecha_inicio' AND '$fecha_fin')
          )
        GROUP BY gp.id_gru_pag
        
        ORDER BY fecha ASC, hora ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER PRÓXIMOS EVENTOS (UNION)
// ============================================
if ($action == 'obtenerProximosEventos') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    
    $hoy = new DateTime();
    $fecha_hoy = $hoy->format('Y-m-d');
    $ultimo_dia_mes_actual = $hoy->format('Y-m-t');
    
    $mes_sig = clone $hoy;
    $mes_sig->modify('first day of next month');
    $primer_dia_mes_sig = $mes_sig->format('Y-m-01');
    $ultimo_dia_mes_sig = $mes_sig->format('Y-m-t');
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.des_eve as descripcion,
            e.ini_eve as fecha,
            e.fin_eve as fecha_fin,
            e.est_eve as estado,
            e.tip_eve as tipo,
            e.cat_eve as categoria,
            e.hor_eve as hora,
            e.rec_eve as recurrente,
            e.id_eve_padre as id_padre,
            e.id_pla as id_plantel,
            p.nom_pla as plantel,
            NULL as subtitulo,
            NULL as id_generacion,
            NULL as generacion,
            NULL as programa
        FROM evento e
        LEFT JOIN plantel p ON e.id_pla = p.id_pla
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND (
              (e.ini_eve BETWEEN '$fecha_hoy' AND '$ultimo_dia_mes_actual')
              OR (e.ini_eve BETWEEN '$primer_dia_mes_sig' AND '$ultimo_dia_mes_sig')
          )
          AND e.est_eve = 'Pendiente'
        
        UNION ALL
        
        SELECT 
            'generacion' as fuente,
            gp.id_gru_pag as id,
            gp.con_gru_pag as titulo,
            gp.des_gru_pag as descripcion,
            gp.ini_gru_pag as fecha,
            gp.fin_gru_pag as fecha_fin,
            gp.val_gru_pag as estado,
            'Académico' as tipo,
            'Generaciones' as categoria,
            '00:00:00' as hora,
            'No' as recurrente,
            NULL as id_padre,
            r.id_pla1 as id_plantel,
            p.nom_pla as plantel,
            gp.sem_gru_pag as subtitulo,
            g.id_gen as id_generacion,
            g.nom_gen as generacion,
            r.nom_ram as programa
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN plantel p ON r.id_pla1 = p.id_pla
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
          AND (
              (gp.ini_gru_pag BETWEEN '$fecha_hoy' AND '$ultimo_dia_mes_actual')
              OR (gp.ini_gru_pag BETWEEN '$primer_dia_mes_sig' AND '$ultimo_dia_mes_sig')
          )
          AND gp.val_gru_pag = 'Pendiente'
        GROUP BY gp.id_gru_pag
        
        ORDER BY fecha ASC, hora ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        $meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        
        $mes_actual_num = (int)$hoy->format('n') - 1;
        $mes_sig_num = (int)$mes_sig->format('n') - 1;
        $anio_actual = $hoy->format('Y');
        $anio_sig = $mes_sig->format('Y');
        
        if ($mes_actual_num === $mes_sig_num) {
            $mes_nombre = $meses[$mes_actual_num] . ' ' . $anio_actual;
        } else {
            $mes_nombre = $meses[$mes_actual_num] . ' - ' . $meses[$mes_sig_num] . ' ' . $anio_sig;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos,
            'mes_nombre' => $mes_nombre
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER EVENTOS VENCIDOS (UNION)
// ============================================
if ($action == 'obtenerEventosVencidos') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $fecha_actual = date('Y-m-d');
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.des_eve as descripcion,
            e.ini_eve as fecha,
            e.fin_eve as fecha_fin,
            e.est_eve as estado,
            e.tip_eve as tipo,
            e.cat_eve as categoria,
            e.hor_eve as hora,
            e.rec_eve as recurrente,
            e.id_eve_padre as id_padre,
            e.id_pla as id_plantel,
            p.nom_pla as plantel,
            NULL as subtitulo,
            NULL as id_generacion,
            NULL as generacion,
            NULL as programa,
            DATEDIFF('$fecha_actual', e.ini_eve) as dias_vencido
        FROM evento e
        LEFT JOIN plantel p ON e.id_pla = p.id_pla
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.ini_eve < '$fecha_actual'
          AND e.est_eve = 'Pendiente'
        
        UNION ALL
        
        SELECT 
            'generacion' as fuente,
            gp.id_gru_pag as id,
            gp.con_gru_pag as titulo,
            gp.des_gru_pag as descripcion,
            gp.ini_gru_pag as fecha,
            gp.fin_gru_pag as fecha_fin,
            gp.val_gru_pag as estado,
            'Académico' as tipo,
            'Generaciones' as categoria,
            '00:00:00' as hora,
            'No' as recurrente,
            NULL as id_padre,
            r.id_pla1 as id_plantel,
            p.nom_pla as plantel,
            gp.sem_gru_pag as subtitulo,
            g.id_gen as id_generacion,
            g.nom_gen as generacion,
            r.nom_ram as programa,
            DATEDIFF('$fecha_actual', gp.ini_gru_pag) as dias_vencido
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN plantel p ON r.id_pla1 = p.id_pla
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
          AND gp.ini_gru_pag < '$fecha_actual'
          AND gp.val_gru_pag = 'Pendiente'
        GROUP BY gp.id_gru_pag
        
        ORDER BY fecha DESC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER SOLO EVENTOS DE GENERACIONES
// ============================================
if ($action == 'obtenerEventosGeneraciones') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT 
            'generacion' as fuente,
            gp.id_gru_pag as id,
            gp.con_gru_pag as titulo,
            gp.des_gru_pag as descripcion,
            gp.ini_gru_pag as fecha,
            gp.fin_gru_pag as fecha_fin,
            gp.val_gru_pag as estado,
            'Académico' as tipo,
            'Generaciones' as categoria,
            '00:00:00' as hora,
            'No' as recurrente,
            NULL as id_padre,
            r.id_pla1 as id_plantel,
            p.nom_pla as plantel,
            gp.sem_gru_pag as subtitulo,
            g.id_gen as id_generacion,
            g.nom_gen as generacion,
            r.nom_ram as programa
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN plantel p ON r.id_pla1 = p.id_pla
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
        GROUP BY gp.id_gru_pag
        ORDER BY gp.ini_gru_pag ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER EVENTOS POR CATEGORÍA
// ============================================
if ($action == 'obtenerEventosPorCategoria') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $categoria = mysqli_real_escape_string($db, $_POST['categoria']);
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.des_eve as descripcion,
            e.ini_eve as fecha,
            e.fin_eve as fecha_fin,
            e.est_eve as estado,
            e.tip_eve as tipo,
            e.cat_eve as categoria,
            e.hor_eve as hora,
            e.rec_eve as recurrente,
            e.id_eve_padre as id_padre,
            e.id_pla as id_plantel,
            p.nom_pla as plantel,
            NULL as subtitulo,
            NULL as id_generacion,
            NULL as generacion,
            NULL as programa
        FROM evento e
        LEFT JOIN plantel p ON e.id_pla = p.id_pla
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.cat_eve = '$categoria'
        ORDER BY e.ini_eve ASC, e.hor_eve ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER PLANTILLAS RECURRENTES
// ============================================
if ($action == 'obtenerPlantillas') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.des_eve as descripcion,
            e.ini_eve as fecha,
            e.fin_eve as fecha_fin,
            e.est_eve as estado,
            e.tip_eve as tipo,
            e.cat_eve as categoria,
            e.hor_eve as hora,
            e.rec_eve as recurrente,
            e.tipo_rec_eve as tipo_recurrente,
            e.dia_mes_eve as dia_mes,
            e.dia_sem_eve as dia_semana,
            e.num_sem_eve as num_semana,
            e.id_pla as id_plantel,
            p.nom_pla as plantel,
            NULL as subtitulo,
            NULL as id_generacion,
            NULL as generacion,
            NULL as programa
        FROM evento e
        LEFT JOIN plantel p ON e.id_pla = p.id_pla
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.rec_eve = 'Si'
          AND (e.id_eve_padre IS NULL OR e.id_eve_padre = 0)
        ORDER BY e.cat_eve ASC, e.nom_eve ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER ESTADÍSTICAS (AMBAS FUENTES)
// ============================================
if ($action == 'obtenerEstadisticas') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $fecha_actual = date('Y-m-d');
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    // Total eventos
    $sqlTotal = "
        SELECT COUNT(*) as total FROM (
            SELECT e.id_eve FROM evento e
            WHERE e.id_eje = '$id_eje'
              AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
              AND (e.id_eve_padre IS NULL OR e.id_eve_padre = 0)
            UNION ALL
            SELECT gp.id_gru_pag FROM grupo_pago gp
            INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
            INNER JOIN rama r ON g.id_ram5 = r.id_ram
            INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
            WHERE gp.tip_gru_pag = 'Fecha'
              AND r.id_pla1 IN ($plantelesStr)
            GROUP BY gp.id_gru_pag
        ) as todos
    ";
    $resultadoTotal = mysqli_query($db, $sqlTotal);
    $total = mysqli_fetch_assoc($resultadoTotal);
    $total = $total['total'];
    
    // Resueltos
    $sqlResueltos = "
        SELECT COUNT(*) as total FROM (
            SELECT e.id_eve FROM evento e
            WHERE e.id_eje = '$id_eje'
              AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
              AND e.est_eve = 'Resuelto'
            UNION ALL
            SELECT gp.id_gru_pag FROM grupo_pago gp
            INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
            INNER JOIN rama r ON g.id_ram5 = r.id_ram
            INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
            WHERE gp.tip_gru_pag = 'Fecha'
              AND r.id_pla1 IN ($plantelesStr)
              AND gp.val_gru_pag = 'Resuelto'
            GROUP BY gp.id_gru_pag
        ) as resueltos
    ";
    $resultadoResueltos = mysqli_query($db, $sqlResueltos);
    $resueltos = mysqli_fetch_assoc($resultadoResueltos);
    $resueltos = $resueltos['total'];
    
    // Pendientes (mes siguiente)
    $hoy = new DateTime();
    $mes_sig = clone $hoy;
    $mes_sig->modify('first day of next month');
    $primer_dia_mes_sig = $mes_sig->format('Y-m-01');
    $ultimo_dia_mes_sig = $mes_sig->format('Y-m-t');
    
    $sqlPendientes = "
        SELECT COUNT(*) as total FROM (
            SELECT e.id_eve FROM evento e
            WHERE e.id_eje = '$id_eje'
              AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
              AND e.est_eve = 'Pendiente'
              AND e.ini_eve BETWEEN '$primer_dia_mes_sig' AND '$ultimo_dia_mes_sig'
            UNION ALL
            SELECT gp.id_gru_pag FROM grupo_pago gp
            INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
            INNER JOIN rama r ON g.id_ram5 = r.id_ram
            INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
            WHERE gp.tip_gru_pag = 'Fecha'
              AND r.id_pla1 IN ($plantelesStr)
              AND gp.val_gru_pag = 'Pendiente'
              AND gp.ini_gru_pag BETWEEN '$primer_dia_mes_sig' AND '$ultimo_dia_mes_sig'
            GROUP BY gp.id_gru_pag
        ) as pendientes
    ";
    $resultadoPendientes = mysqli_query($db, $sqlPendientes);
    $pendientes = mysqli_fetch_assoc($resultadoPendientes);
    $pendientes = $pendientes['total'];
    
    // Vencidos
    $sqlVencidos = "
        SELECT COUNT(*) as total FROM (
            SELECT e.id_eve FROM evento e
            WHERE e.id_eje = '$id_eje'
              AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
              AND e.est_eve = 'Pendiente'
              AND e.ini_eve < '$fecha_actual'
            UNION ALL
            SELECT gp.id_gru_pag FROM grupo_pago gp
            INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
            INNER JOIN rama r ON g.id_ram5 = r.id_ram
            INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
            WHERE gp.tip_gru_pag = 'Fecha'
              AND r.id_pla1 IN ($plantelesStr)
              AND gp.val_gru_pag = 'Pendiente'
              AND gp.ini_gru_pag < '$fecha_actual'
            GROUP BY gp.id_gru_pag
        ) as vencidos
    ";
    $resultadoVencidos = mysqli_query($db, $sqlVencidos);
    $vencidos = mysqli_fetch_assoc($resultadoVencidos);
    $vencidos = $vencidos['total'];
    
    // Generaciones
    $sqlGeneraciones = "
        SELECT COUNT(DISTINCT gp.id_gru_pag) as total
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
    ";
    $resultadoGeneraciones = mysqli_query($db, $sqlGeneraciones);
    $generaciones = mysqli_fetch_assoc($resultadoGeneraciones);
    $generaciones = $generaciones['total'];
    
    // P100C
    $sqlP100C = "
        SELECT COUNT(*) as total FROM evento e
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.cat_eve = 'P100C'
    ";
    $resultadoP100C = mysqli_query($db, $sqlP100C);
    $p100c = mysqli_fetch_assoc($resultadoP100C);
    $p100c = $p100c['total'];
    
    // Cobranza
    $sqlCobranza = "
        SELECT COUNT(*) as total FROM evento e
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.cat_eve = 'Cobranza'
    ";
    $resultadoCobranza = mysqli_query($db, $sqlCobranza);
    $cobranza = mysqli_fetch_assoc($resultadoCobranza);
    $cobranza = $cobranza['total'];
    
    // Plantillas
    $sqlPlantillas = "
        SELECT COUNT(*) as total FROM evento e
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.rec_eve = 'Si'
          AND (e.id_eve_padre IS NULL OR e.id_eve_padre = 0)
    ";
    $resultadoPlantillas = mysqli_query($db, $sqlPlantillas);
    $plantillas = mysqli_fetch_assoc($resultadoPlantillas);
    $plantillas = $plantillas['total'];
    
    echo json_encode(array(
        'resultado' => 'success',
        'total' => intval($total),
        'resueltos' => intval($resueltos),
        'pendientes' => intval($pendientes),
        'vencidos' => intval($vencidos),
        'generaciones' => intval($generaciones),
        'p100c' => intval($p100c),
        'cobranza' => intval($cobranza),
        'plantillas' => intval($plantillas)
    ));
    exit;
}

// ============================================
// OBTENER EVENTOS DE UNA FECHA ESPECÍFICA (UNION)
// ============================================
if ($action == 'obtenerEventosFecha') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $fecha = mysqli_real_escape_string($db, $_POST['fecha']);
    
    $planteles = obtenerPlantelesEjecutivo($db, $id_eje);
    
    if(empty($planteles)) {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => 'Sin planteles asignados'
        ));
        exit;
    }
    
    $plantelesStr = implode(',', $planteles);
    
    $sql = "
        SELECT 
            'evento' as fuente,
            e.id_eve as id,
            e.nom_eve as titulo,
            e.des_eve as descripcion,
            e.ini_eve as fecha,
            e.fin_eve as fecha_fin,
            e.est_eve as estado,
            e.tip_eve as tipo,
            e.cat_eve as categoria,
            e.hor_eve as hora,
            e.rec_eve as recurrente,
            e.id_eve_padre as id_padre,
            e.id_pla as id_plantel,
            p.nom_pla as plantel,
            NULL as subtitulo,
            NULL as id_generacion,
            NULL as generacion,
            NULL as programa
        FROM evento e
        LEFT JOIN plantel p ON e.id_pla = p.id_pla
        WHERE e.id_eje = '$id_eje'
          AND (e.id_pla IN ($plantelesStr) OR e.id_pla IS NULL OR e.id_pla = 0)
          AND e.ini_eve = '$fecha'
        
        UNION ALL
        
        SELECT 
            'generacion' as fuente,
            gp.id_gru_pag as id,
            gp.con_gru_pag as titulo,
            gp.des_gru_pag as descripcion,
            gp.ini_gru_pag as fecha,
            gp.fin_gru_pag as fecha_fin,
            gp.val_gru_pag as estado,
            'Académico' as tipo,
            'Generaciones' as categoria,
            '00:00:00' as hora,
            'No' as recurrente,
            NULL as id_padre,
            r.id_pla1 as id_plantel,
            p.nom_pla as plantel,
            gp.sem_gru_pag as subtitulo,
            g.id_gen as id_generacion,
            g.nom_gen as generacion,
            r.nom_ram as programa
        FROM grupo_pago gp
        INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN plantel p ON r.id_pla1 = p.id_pla
        INNER JOIN alu_ram ar ON ar.id_gen1 = g.id_gen
        WHERE gp.tip_gru_pag = 'Fecha'
          AND r.id_pla1 IN ($plantelesStr)
          AND gp.ini_gru_pag = '$fecha'
        GROUP BY gp.id_gru_pag
        
        ORDER BY hora ASC, categoria ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $eventos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $fila;
        }
        
        echo json_encode(array(
            'resultado' => 'success',
            'eventos' => $eventos
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// OBTENER DETALLE DE EVENTO (AMBAS FUENTES)
// ============================================
if ($action == 'obtenerDetalleEvento') {
    $fuente = mysqli_real_escape_string($db, $_POST['fuente']);
    $id = mysqli_real_escape_string($db, $_POST['id']);
    
    if ($fuente === 'evento') {
        $sql = "
            SELECT 
                'evento' as fuente,
                e.id_eve as id,
                e.nom_eve as titulo,
                e.des_eve as descripcion,
                e.ini_eve as fecha,
                e.fin_eve as fecha_fin,
                e.est_eve as estado,
                e.tip_eve as tipo,
                e.cat_eve as categoria,
                e.hor_eve as hora,
                e.rec_eve as recurrente,
                e.tipo_rec_eve as tipo_recurrente,
                e.dia_mes_eve as dia_mes,
                e.dia_sem_eve as dia_semana,
                e.num_sem_eve as num_semana,
                e.id_eve_padre as id_padre,
                e.id_pla as id_plantel,
                e.id_eje,
                p.nom_pla as plantel,
                NULL as subtitulo,
                NULL as id_generacion,
                NULL as generacion,
                NULL as programa
            FROM evento e
            LEFT JOIN plantel p ON e.id_pla = p.id_pla
            WHERE e.id_eve = '$id'
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado) {
            $evento = mysqli_fetch_assoc($resultado);
            
            if ($evento) {
                $otros = array();
                if ($evento['recurrente'] === 'Si') {
                    $sqlOtros = "
                        SELECT 
                            'evento' as fuente,
                            id_eve as id,
                            nom_eve as titulo,
                            '' as subtitulo,
                            ini_eve as fecha,
                            est_eve as estado
                        FROM evento
                        WHERE id_eve_padre = '$id'
                        ORDER BY ini_eve ASC
                        LIMIT 5
                    ";
                    $resultOtros = mysqli_query($db, $sqlOtros);
                    while ($fila = mysqli_fetch_assoc($resultOtros)) {
                        $otros[] = $fila;
                    }
                }
                
                echo json_encode(array(
                    'resultado' => 'success',
                    'evento' => $evento,
                    'otros' => $otros
                ));
            } else {
                echo json_encode(array(
                    'resultado' => 'error',
                    'mensaje' => 'Evento no encontrado'
                ));
            }
        }
    } else {
        $sql = "
            SELECT 
                'generacion' as fuente,
                gp.id_gru_pag as id,
                gp.con_gru_pag as titulo,
                gp.des_gru_pag as descripcion,
                gp.ini_gru_pag as fecha,
                gp.fin_gru_pag as fecha_fin,
                gp.val_gru_pag as estado,
                'Académico' as tipo,
                'Generaciones' as categoria,
                '00:00:00' as hora,
                'No' as recurrente,
                NULL as tipo_recurrente,
                NULL as dia_mes,
                NULL as dia_semana,
                NULL as num_semana,
                NULL as id_padre,
                r.id_pla1 as id_plantel,
                NULL as id_eje,
                p.nom_pla as plantel,
                gp.sem_gru_pag as subtitulo,
                g.id_gen as id_generacion,
                g.nom_gen as generacion,
                r.nom_ram as programa
            FROM grupo_pago gp
            INNER JOIN generacion g ON gp.id_gen15 = g.id_gen
            INNER JOIN rama r ON g.id_ram5 = r.id_ram
            INNER JOIN plantel p ON r.id_pla1 = p.id_pla
            WHERE gp.id_gru_pag = '$id'
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado) {
            $evento = mysqli_fetch_assoc($resultado);
            
            if ($evento) {
                $id_gen = $evento['id_generacion'];
                $sqlOtros = "
                    SELECT 
                        'generacion' as fuente,
                        gp.id_gru_pag as id,
                        gp.con_gru_pag as titulo,
                        gp.sem_gru_pag as subtitulo,
                        gp.ini_gru_pag as fecha,
                        gp.val_gru_pag as estado
                    FROM grupo_pago gp
                    WHERE gp.tip_gru_pag = 'Fecha'
                      AND gp.id_gen15 = '$id_gen'
                      AND gp.id_gru_pag != '$id'
                    ORDER BY gp.ini_gru_pag ASC
                ";
                $resultOtros = mysqli_query($db, $sqlOtros);
                $otros = array();
                while ($fila = mysqli_fetch_assoc($resultOtros)) {
                    $otros[] = $fila;
                }
                
                echo json_encode(array(
                    'resultado' => 'success',
                    'evento' => $evento,
                    'otros' => $otros
                ));
            } else {
                echo json_encode(array(
                    'resultado' => 'error',
                    'mensaje' => 'Evento no encontrado'
                ));
            }
        }
    }
    exit;
}

// ============================================
// CREAR EVENTO (SOLO TABLA evento)
// ============================================
if ($action == 'crearEvento') {
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $nom_eve = mysqli_real_escape_string($db, $_POST['nom_eve']);
    $des_eve = isset($_POST['des_eve']) ? mysqli_real_escape_string($db, $_POST['des_eve']) : '';
    $ini_eve = mysqli_real_escape_string($db, $_POST['ini_eve']);
    $fin_eve = mysqli_real_escape_string($db, $_POST['fin_eve']);
    $cat_eve = mysqli_real_escape_string($db, $_POST['cat_eve']);
    $tip_eve = isset($_POST['tip_eve']) ? mysqli_real_escape_string($db, $_POST['tip_eve']) : 'Administrativo';
    $hor_eve = isset($_POST['hor_eve']) ? mysqli_real_escape_string($db, $_POST['hor_eve']) : '00:00:00';
    $id_pla = isset($_POST['id_pla']) && $_POST['id_pla'] != '' ? mysqli_real_escape_string($db, $_POST['id_pla']) : '0';
    
    $rec_eve = isset($_POST['rec_eve']) ? mysqli_real_escape_string($db, $_POST['rec_eve']) : 'No';
    $tipo_rec_eve = 'NULL';
    $dia_mes_eve = 'NULL';
    $dia_sem_eve = 'NULL';
    $num_sem_eve = 'NULL';
    
    if ($rec_eve == 'Si') {
        $tipo_rec_eve = "'" . mysqli_real_escape_string($db, $_POST['tipo_rec_eve']) . "'";
        
        if ($_POST['tipo_rec_eve'] == 'Mensual_Fijo') {
            $dia_mes_eve = mysqli_real_escape_string($db, $_POST['dia_mes_eve']);
        } else {
            $dia_sem_eve = "'" . mysqli_real_escape_string($db, $_POST['dia_sem_eve']) . "'";
            $num_sem_eve = mysqli_real_escape_string($db, $_POST['num_sem_eve']);
        }
    }
    
    $sql = "
        INSERT INTO evento (
            id_eje, nom_eve, des_eve, ini_eve, fin_eve, fec_eve,
            est_eve, tip_eve, cat_eve, hor_eve, id_pla,
            rec_eve, tipo_rec_eve, dia_mes_eve, dia_sem_eve, num_sem_eve,
            id_eve_padre
        ) VALUES (
            '$id_eje', '$nom_eve', '$des_eve', '$ini_eve', '$fin_eve', CURDATE(),
            'Pendiente', '$tip_eve', '$cat_eve', '$hor_eve', '$id_pla',
            '$rec_eve', $tipo_rec_eve, $dia_mes_eve, $dia_sem_eve, $num_sem_eve,
            NULL
        )
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $id_insertado = mysqli_insert_id($db);
        
        echo json_encode(array(
            'resultado' => 'success',
            'id_eve' => $id_insertado,
            'mensaje' => 'Evento creado exitosamente'
        ));
    } else {
        echo json_encode(array(
            'resultado' => 'error',
            'mensaje' => mysqli_error($db)
        ));
    }
    exit;
}

// ============================================
// ACTUALIZAR EVENTO (AMBAS FUENTES)
// ============================================
if ($action == 'actualizarEvento') {
    $fuente = mysqli_real_escape_string($db, $_POST['fuente']);
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $nom_eve = mysqli_real_escape_string($db, $_POST['nom_eve']);
    $des_eve = isset($_POST['des_eve']) ? mysqli_real_escape_string($db, $_POST['des_eve']) : '';
    $ini_eve = mysqli_real_escape_string($db, $_POST['ini_eve']);
    
    if ($fuente === 'evento') {
        $fin_eve = mysqli_real_escape_string($db, $_POST['fin_eve']);
        $cat_eve = mysqli_real_escape_string($db, $_POST['cat_eve']);
        $tip_eve = isset($_POST['tip_eve']) ? mysqli_real_escape_string($db, $_POST['tip_eve']) : 'Administrativo';
        $hor_eve = isset($_POST['hor_eve']) ? mysqli_real_escape_string($db, $_POST['hor_eve']) : '00:00:00';
        $id_pla = isset($_POST['id_pla']) && $_POST['id_pla'] != '' ? mysqli_real_escape_string($db, $_POST['id_pla']) : '0';
        
        $sql = "
            UPDATE evento SET
                nom_eve = '$nom_eve',
                des_eve = '$des_eve',
                ini_eve = '$ini_eve',
                fin_eve = '$fin_eve',
                cat_eve = '$cat_eve',
                tip_eve = '$tip_eve',
                hor_eve = '$hor_eve',
                id_pla = '$id_pla'
            WHERE id_eve = '$id'
        ";
    } else {
        $sem_gru_pag = isset($_POST['sem_gru_pag']) ? mysqli_real_escape_string($db, $_POST['sem_gru_pag']) : '';
        
        $sql = "
            UPDATE grupo_pago SET
                con_gru_pag = '$nom_eve',
                des_gru_pag = '$des_eve',
                ini_gru_pag = '$ini_eve',
                fin_gru_pag = '$ini_eve',
                sem_gru_pag = '$sem_gru_pag'
            WHERE id_gru_pag = '$id'
        ";
    }
    
    $resultado = mysqli_query($db, $sql);
    
    echo json_encode(array(
        'resultado' => $resultado ? 'success' : 'error',
        'mensaje' => $resultado ? 'Evento actualizado' : mysqli_error($db)
    ));
    exit;
}

// ============================================
// ACTUALIZAR VALIDACIÓN (AMBAS FUENTES)
// ============================================
if ($action == 'actualizarValidacion') {
    $fuente = mysqli_real_escape_string($db, $_POST['fuente']);
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $est_eve = mysqli_real_escape_string($db, $_POST['val_eve']);
    
    if ($fuente === 'evento') {
        $sql = "UPDATE evento SET est_eve = '$est_eve' WHERE id_eve = '$id'";
    } else {
        $sql = "UPDATE grupo_pago SET val_gru_pag = '$est_eve' WHERE id_gru_pag = '$id'";
    }
    
    $resultado = mysqli_query($db, $sql);
    
    echo json_encode(array(
        'resultado' => $resultado ? 'success' : 'error',
        'mensaje' => $resultado ? '' : mysqli_error($db)
    ));
    exit;
}

// ============================================
// ELIMINAR EVENTO (AMBAS FUENTES)
// ============================================
if ($action == 'eliminarEvento') {
    $fuente = mysqli_real_escape_string($db, $_POST['fuente']);
    $id = mysqli_real_escape_string($db, $_POST['id']);
    
    if ($fuente === 'evento') {
        $sqlCheck = "SELECT rec_eve FROM evento WHERE id_eve = '$id'";
        $resultCheck = mysqli_query($db, $sqlCheck);
        $evento = mysqli_fetch_assoc($resultCheck);
        
        if ($evento && $evento['rec_eve'] == 'Si') {
            $sqlDeleteHijos = "DELETE FROM evento WHERE id_eve_padre = '$id'";
            mysqli_query($db, $sqlDeleteHijos);
            
            $sqlDeletePadre = "DELETE FROM evento WHERE id_eve = '$id'";
            $resultado = mysqli_query($db, $sqlDeletePadre);
        } else {
            $sql = "DELETE FROM evento WHERE id_eve = '$id'";
            $resultado = mysqli_query($db, $sql);
        }
    } else {
        $sql = "DELETE FROM grupo_pago WHERE id_gru_pag = '$id'";
        $resultado = mysqli_query($db, $sql);
    }
    
    echo json_encode(array(
        'resultado' => $resultado ? 'success' : 'error',
        'mensaje' => $resultado ? 'Evento eliminado' : mysqli_error($db)
    ));
    exit;
}

// ============================================
// GENERAR EVENTOS RECURRENTES (CRON JOB)
// ============================================
if ($action == 'generarEventosRecurrentes') {
    $mes_siguiente = date('Y-m', strtotime('+1 month'));
    $primer_dia = $mes_siguiente . '-01';
    $ultimo_dia = date('Y-m-t', strtotime($primer_dia));
    
    $sqlPlantillas = "
        SELECT * FROM evento 
        WHERE rec_eve = 'Si' 
          AND (id_eve_padre IS NULL OR id_eve_padre = 0)
          AND est_eve != 'Resuelto'
    ";
    
    $resultPlantillas = mysqli_query($db, $sqlPlantillas);
    $generados = 0;
    
    while ($plantilla = mysqli_fetch_assoc($resultPlantillas)) {
        $fecha_evento = null;
        
        if ($plantilla['tipo_rec_eve'] == 'Mensual_Fijo') {
            $dia = str_pad($plantilla['dia_mes_eve'], 2, '0', STR_PAD_LEFT);
            $fecha_evento = $mes_siguiente . '-' . $dia;
            
            if (!checkdate(date('m', strtotime($primer_dia)), $plantilla['dia_mes_eve'], date('Y', strtotime($primer_dia)))) {
                continue;
            }
            
        } else {
            $fecha_evento = calcularDiaVariable($mes_siguiente, $plantilla['num_sem_eve'], $plantilla['dia_sem_eve']);
        }
        
        if ($fecha_evento) {
            $sqlCheck = "
                SELECT id_eve FROM evento 
                WHERE id_eve_padre = '" . $plantilla['id_eve'] . "' 
                  AND ini_eve = '$fecha_evento'
            ";
            $resultCheck = mysqli_query($db, $sqlCheck);
            
            if (mysqli_num_rows($resultCheck) == 0) {
                $sqlInsert = "
                    INSERT INTO evento (
                        id_eje, nom_eve, des_eve, ini_eve, fin_eve, fec_eve,
                        est_eve, tip_eve, cat_eve, hor_eve, id_pla,
                        rec_eve, id_eve_padre
                    ) VALUES (
                        '" . $plantilla['id_eje'] . "',
                        '" . mysqli_real_escape_string($db, $plantilla['nom_eve']) . "',
                        '" . mysqli_real_escape_string($db, $plantilla['des_eve']) . "',
                        '$fecha_evento',
                        '$fecha_evento',
                        CURDATE(),
                        'Pendiente',
                        '" . $plantilla['tip_eve'] . "',
                        '" . $plantilla['cat_eve'] . "',
                        '" . $plantilla['hor_eve'] . "',
                        '" . $plantilla['id_pla'] . "',
                        'No',
                        '" . $plantilla['id_eve'] . "'
                    )
                ";
                
                if (mysqli_query($db, $sqlInsert)) {
                    $generados++;
                }
            }
        }
    }
    
    echo json_encode(array(
        'resultado' => 'success',
        'generados' => $generados,
        'mensaje' => "Se generaron $generados eventos para $mes_siguiente"
    ));
    exit;
}

// ============================================
// FUNCIÓN AUXILIAR: CALCULAR DÍA VARIABLE
// ============================================
function calcularDiaVariable($mes_anio, $num_semana, $dia_semana) {
    $dias_ingles = array(
        'Lunes' => 'Monday',
        'Martes' => 'Tuesday',
        'Miércoles' => 'Wednesday',
        'Jueves' => 'Thursday',
        'Viernes' => 'Friday',
        'Sábado' => 'Saturday',
        'Domingo' => 'Sunday'
    );
    
    $dia_en = $dias_ingles[$dia_semana];
    $primer_dia_mes = $mes_anio . '-01';
    
    if ($num_semana == 5) {
        $ultimo_dia_mes = date('Y-m-t', strtotime($primer_dia_mes));
        $fecha = new DateTime($ultimo_dia_mes);
        
        while ($fecha->format('l') != $dia_en) {
            $fecha->modify('-1 day');
        }
        
        return $fecha->format('Y-m-d');
    } else {
        $fecha = new DateTime($primer_dia_mes);
        
        while ($fecha->format('l') != $dia_en) {
            $fecha->modify('+1 day');
        }
        
        if ($num_semana > 1) {
            $fecha->modify('+' . ($num_semana - 1) . ' weeks');
        }
        
        return $fecha->format('Y-m-d');
    }
}

echo json_encode(array(
    'resultado' => 'error',
    'mensaje' => 'Acción no válida: ' . $action
));
?>