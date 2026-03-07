<?php  
// ========================================================================
// CONTROLADOR DE HORARIOS/GRUPOS - VERSIÓN COMPLETA CON VALIDACIONES
// Archivo: server/controlador_horarios.php
// Maneja grupos NORMALES, FUSIONADOS, CREACIÓN DE FUSIONES y VALIDACIONES
// ========================================================================
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ========== FUNCIÓN: CALCULAR SEMANA ACTUAL DEL CICLO ==========
function calcularSemana($fecha_inicio, $fecha_fin) {
    $hoy = new DateTime();
    $inicio = new DateTime($fecha_inicio);
    $fin = new DateTime($fecha_fin);
    
    if ($hoy < $inicio) {
        return 'N/A';
    }
    
    if ($hoy > $fin) {
        $diferencia = $inicio->diff($fin);
        return floor($diferencia->days / 7);
    }
    
    $diferencia = $inicio->diff($hoy);
    $semana = floor($diferencia->days / 7) + 1;
    
    return $semana;
}

// ========== FUNCIÓN: OBTENER HORARIO POR DÍA ==========
function obtenerHorarioDia($db, $id_sub_hor, $dia) {
    $sql = "
        SELECT ini_hor, fin_hor 
        FROM horario 
        WHERE id_sub_hor1 = '$id_sub_hor' AND dia_hor = '$dia'
        LIMIT 1
    ";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        return substr($fila['ini_hor'], 0, 5) . '-' . substr($fila['fin_hor'], 0, 5);
    }
    
    return '--';
}

// ========== FUNCIÓN: CALCULAR HORAS DE UN HORARIO ==========
function calcularHorasHorario($horarioStr) {
    if ($horarioStr === '--' || empty($horarioStr)) {
        return 0;
    }
    
    $partes = explode('-', $horarioStr);
    if (count($partes) !== 2) {
        return 0;
    }
    
    $inicio = strtotime($partes[0]);
    $fin = strtotime($partes[1]);
    
    if ($inicio === false || $fin === false) {
        return 0;
    }
    
    $diferencia = ($fin - $inicio) / 3600;
    return max(0, $diferencia);
}

// ========== FUNCIÓN: OBTENER DÍA DE LA SEMANA EN ESPAÑOL ==========
function obtenerDiaSemana($fecha) {
    $dias = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    $dt = new DateTime($fecha);
    return $dias[$dt->format('N')];
}

// ========== SECCIÓN 0: OBTENER HORARIOS (CONSULTA PRINCIPAL) ==========
if(!isset($_POST['accion'])) {
    
    $fechaHoy = date('Y-m-d');
    
    // ========== FILTRO: ESTATUS DEL CICLO ==========
    $estatusCiclo = isset($_POST['estatusCiclo']) ? $_POST['estatusCiclo'] : 'Vigente';
    
    $condicionEstatus = "";
    $condicionEstatusFusion = "";
    
    if ($estatusCiclo == 'Vigente') {
        $condicionEstatus = " AND c.ini_cic <= '$fechaHoy' AND '$fechaHoy' <= c.fin_cic ";
        $condicionEstatusFusion = " AND f.ini_fus <= '$fechaHoy' AND '$fechaHoy' <= f.fin_fus ";
    } else if ($estatusCiclo == 'Vencido') {
        $condicionEstatus = " AND c.fin_cic < '$fechaHoy' ";
        $condicionEstatusFusion = " AND f.fin_fus < '$fechaHoy' ";
    }
    
    // ========== FILTRO: PLANTELES ==========
    $plantelesCondicion = "";
    $plantelesCondicionFusion = "";
    if(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
        $plantelesLimpios = array_map('intval', $_POST['planteles_ajax']);
        $plantelesStr = implode(',', $plantelesLimpios);
        $plantelesCondicion = " AND p.id_pla IN ($plantelesStr) ";
        $plantelesCondicionFusion = " AND p.id_pla IN ($plantelesStr) ";
    }
    
    // ========== FILTRO: NIVEL ACADÉMICO (gra_ram) ==========
    $nivelesCondicion = "";
    $nivelesCondicionFusion = "";
    if(isset($_POST['niveles_ajax']) && !empty($_POST['niveles_ajax'])) {
        $nivelesLimpios = array_map(function($n) use ($db) {
            return "'" . mysqli_real_escape_string($db, $n) . "'";
        }, $_POST['niveles_ajax']);
        $nivelesStr = implode(',', $nivelesLimpios);
        $nivelesCondicion = " AND r.gra_ram IN ($nivelesStr) ";
        $nivelesCondicionFusion = " AND r.gra_ram IN ($nivelesStr) ";
    }
    
    // ========== FILTRO: TIPO (Normal/Fusionado) ==========
    $incluirNormales = true;
    $incluirFusionados = true;
    
    if(isset($_POST['tipos_ajax']) && !empty($_POST['tipos_ajax'])) {
        $incluirNormales = in_array('Normal', $_POST['tipos_ajax']);
        $incluirFusionados = in_array('Fusionado', $_POST['tipos_ajax']);
    }
    
    // ========== FILTRO: PROFESORES ==========
    $profesoresCondicion = "";
    $profesoresCondicionFusion = "";
    if(isset($_POST['profesores_ajax']) && !empty($_POST['profesores_ajax'])) {
        $profesoresLimpios = array_map('intval', $_POST['profesores_ajax']);
        $profesoresStr = implode(',', $profesoresLimpios);
        $profesoresCondicion = " AND pr.id_pro IN ($profesoresStr) ";
        $profesoresCondicionFusion = " AND pr.id_pro IN ($profesoresStr) ";
    }
    
    // ========== BÚSQUEDA POR TEXTO ==========
    $busquedaCondicion = "";
    $busquedaCondicionFusion = "";
    if(isset($_POST['datosHorario']) && !empty(trim($_POST['datosHorario']))) {
        $busqueda = mysqli_real_escape_string($db, trim($_POST['datosHorario']));
        $busquedaCondicion = " AND (
            g.nom_gru LIKE '%$busqueda%' OR
            c.nom_cic LIKE '%$busqueda%' OR
            CONCAT(pr.nom_pro, ' ', pr.app_pro) LIKE '%$busqueda%' OR
            m.nom_mat LIKE '%$busqueda%' OR
            r.nom_ram LIKE '%$busqueda%' OR
            r.abr_ram LIKE '%$busqueda%' OR
            r.gra_ram LIKE '%$busqueda%' OR
            p.nom_pla LIKE '%$busqueda%'
        ) ";
        $busquedaCondicionFusion = " AND (
            f.nom_fus LIKE '%$busqueda%' OR
            f.cic_fus LIKE '%$busqueda%' OR
            CONCAT(pr.nom_pro, ' ', pr.app_pro) LIKE '%$busqueda%' OR
            m.nom_mat LIKE '%$busqueda%' OR
            r.nom_ram LIKE '%$busqueda%' OR
            r.abr_ram LIKE '%$busqueda%' OR
            r.gra_ram LIKE '%$busqueda%' OR
            p.nom_pla LIKE '%$busqueda%'
        ) ";
    }
    
    // Array temporal para todos los horarios
    $horariosTemp = array();
    
    // Contadores para dashboard
    $totalHorasGlobal = 0;
    $horasPorDia = [
        'Lunes' => 0,
        'Martes' => 0,
        'Miércoles' => 0,
        'Jueves' => 0,
        'Viernes' => 0,
        'Sábado' => 0,
        'Domingo' => 0
    ];
    
    // ========== QUERY 1: GRUPOS NORMALES ==========
    if ($incluirNormales) {
        $sqlNormales = "
            SELECT 
                sh.id_sub_hor AS id,
                sh.id_sub_hor AS id_sub_hor_horario,
                sh.id_sub_hor AS id_orden,
                'Normal' AS tipo,
                p.nom_pla AS plantel,
                c.nom_cic AS ciclo,
                c.ini_cic AS inicio,
                c.fin_cic AS fin,
                g.nom_gru AS grupo,
                CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor,
                m.nom_mat AS materia,
                1 AS total_planteles,
                (SELECT COUNT(*) FROM alu_hor ah WHERE ah.id_sub_hor5 = sh.id_sub_hor AND ah.est_alu_hor = 'Activo') AS total_alumnos,
                IFNULL(s.nom_sal, 'N/A') AS salon,
                IFNULL(r.abr_ram, r.nom_ram) AS programa,
                IFNULL(r.gra_ram, 'N/A') AS nivel,
                IFNULL(sh.url_sub_hor, '') AS url_sub_hor
            FROM sub_hor sh
            INNER JOIN grupo g ON g.id_gru = sh.id_gru1
            INNER JOIN ciclo c ON c.id_cic = g.id_cic1
            INNER JOIN rama r ON r.id_ram = c.id_ram1
            INNER JOIN plantel p ON p.id_pla = r.id_pla1
            INNER JOIN profesor pr ON pr.id_pro = sh.id_pro1
            INNER JOIN materia m ON m.id_mat = sh.id_mat1
            LEFT JOIN salon s ON s.id_sal = sh.id_sal1
            WHERE sh.id_fus2 IS NULL
            AND p.id_cad1 = '$cadena'
            $condicionEstatus
            $plantelesCondicion
            $nivelesCondicion
            $profesoresCondicion
            $busquedaCondicion
        ";
        
        $resultadoNormales = mysqli_query($db, $sqlNormales);
        
        if ($resultadoNormales) {
            while ($fila = mysqli_fetch_assoc($resultadoNormales)) {
                $id_sub_hor = $fila['id_sub_hor_horario'];
                
                $semana = calcularSemana($fila['inicio'], $fila['fin']);
                
                $lunes = obtenerHorarioDia($db, $id_sub_hor, 'Lunes');
                $martes = obtenerHorarioDia($db, $id_sub_hor, 'Martes');
                $miercoles = obtenerHorarioDia($db, $id_sub_hor, 'Miércoles');
                $jueves = obtenerHorarioDia($db, $id_sub_hor, 'Jueves');
                $viernes = obtenerHorarioDia($db, $id_sub_hor, 'Viernes');
                $sabado = obtenerHorarioDia($db, $id_sub_hor, 'Sábado');
                $domingo = obtenerHorarioDia($db, $id_sub_hor, 'Domingo');
                
                $hLunes = calcularHorasHorario($lunes);
                $hMartes = calcularHorasHorario($martes);
                $hMiercoles = calcularHorasHorario($miercoles);
                $hJueves = calcularHorasHorario($jueves);
                $hViernes = calcularHorasHorario($viernes);
                $hSabado = calcularHorasHorario($sabado);
                $hDomingo = calcularHorasHorario($domingo);
                
                $horasPorDia['Lunes'] += $hLunes;
                $horasPorDia['Martes'] += $hMartes;
                $horasPorDia['Miércoles'] += $hMiercoles;
                $horasPorDia['Jueves'] += $hJueves;
                $horasPorDia['Viernes'] += $hViernes;
                $horasPorDia['Sábado'] += $hSabado;
                $horasPorDia['Domingo'] += $hDomingo;
                
                $totalHorasGlobal += ($hLunes + $hMartes + $hMiercoles + $hJueves + $hViernes + $hSabado + $hDomingo);
                
                // ============================================================
                // ARRAY DE 24 ELEMENTOS - ORDEN CORRECTO PARA FRONTEND
                // ============================================================
                // 0:  id (para modal - id_sub_hor en normales)
                // 1:  id_sub_hor_horario (para validaciones - SIEMPRE id_sub_hor real)
                // 2:  plantel
                // 3:  tipo
                // 4:  ciclo
                // 5:  inicio
                // 6:  fin
                // 7:  grupo
                // 8:  semana
                // 9:  profesor
                // 10: materia
                // 11: total_planteles (CDES)
                // 12: total_alumnos
                // 13: salon
                // 14: programa
                // 15: nivel
                // 16: url_sub_hor
                // 17: lunes
                // 18: martes
                // 19: miercoles
                // 20: jueves
                // 21: viernes
                // 22: sabado
                // 23: domingo
                // ============================================================
                
                $horariosTemp[] = array(
                    'id_orden' => intval($fila['id_orden']),
                    'data' => array(
                        $fila['id'],                    // 0: id (para modal)
                        $fila['id_sub_hor_horario'],    // 1: id_sub_hor_horario (para validaciones)
                        $fila['plantel'],               // 2: plantel
                        $fila['tipo'],                  // 3: tipo
                        $fila['ciclo'],                 // 4: ciclo
                        $fila['inicio'],                // 5: inicio
                        $fila['fin'],                   // 6: fin
                        $fila['grupo'],                 // 7: grupo
                        $semana,                        // 8: semana
                        $fila['profesor'],              // 9: profesor
                        $fila['materia'],               // 10: materia
                        $fila['total_planteles'],       // 11: CDES
                        $fila['total_alumnos'],         // 12: alumnos
                        $fila['salon'],                 // 13: salon
                        $fila['programa'],              // 14: programa
                        $fila['nivel'],                 // 15: nivel
                        $fila['url_sub_hor'],           // 16: url
                        $lunes,                         // 17: lunes
                        $martes,                        // 18: martes
                        $miercoles,                     // 19: miercoles
                        $jueves,                        // 20: jueves
                        $viernes,                       // 21: viernes
                        $sabado,                        // 22: sabado
                        $domingo                        // 23: domingo
                    )
                );
            }
        }
    }
    
    // ========== QUERY 2: GRUPOS FUSIONADOS ==========
    if ($incluirFusionados) {
        $sqlFusionados = "
            SELECT 
                f.id_fus AS id,
                sh.id_sub_hor AS id_sub_hor_horario,
                sh.id_sub_hor AS id_orden,
                'Fusionado' AS tipo,
                p.nom_pla AS plantel,
                f.cic_fus AS ciclo,
                f.ini_fus AS inicio,
                f.fin_fus AS fin,
                f.nom_fus AS grupo,
                CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor,
                m.nom_mat AS materia_dominante,
                (SELECT COUNT(*) FROM sub_hor sh2 WHERE sh2.id_fus2 = f.id_fus) AS total_materias,
                (SELECT COUNT(DISTINCT p2.id_pla) 
                 FROM sub_hor sh2 
                 INNER JOIN materia m2 ON m2.id_mat = sh2.id_mat1
                 INNER JOIN rama r2 ON r2.id_ram = m2.id_ram2
                 INNER JOIN plantel p2 ON p2.id_pla = r2.id_pla1
                 WHERE sh2.id_fus2 = f.id_fus) AS total_planteles,
                (SELECT IFNULL(SUM(
                    (SELECT COUNT(*) FROM alu_hor ah WHERE ah.id_sub_hor5 = sh3.id_sub_hor AND ah.est_alu_hor = 'Activo')
                ), 0) FROM sub_hor sh3 WHERE sh3.id_fus2 = f.id_fus) AS total_alumnos,
                IFNULL(s.nom_sal, 'N/A') AS salon,
                IFNULL(r.abr_ram, r.nom_ram) AS programa,
                IFNULL(r.gra_ram, 'N/A') AS nivel,
                IFNULL(sh.url_sub_hor, '') AS url_sub_hor
            FROM fusion f
            INNER JOIN sub_hor sh ON sh.id_fus2 = f.id_fus AND sh.id_sub_hor_nat IS NULL
            INNER JOIN profesor pr ON pr.id_pro = sh.id_pro1
            INNER JOIN materia m ON m.id_mat = sh.id_mat1
            INNER JOIN rama r ON r.id_ram = m.id_ram2
            INNER JOIN plantel p ON p.id_pla = r.id_pla1
            LEFT JOIN salon s ON s.id_sal = sh.id_sal1
            WHERE p.id_cad1 = '$cadena'
            $condicionEstatusFusion
            $plantelesCondicionFusion
            $nivelesCondicionFusion
            $profesoresCondicionFusion
            $busquedaCondicionFusion
        ";
        
        $resultadoFusionados = mysqli_query($db, $sqlFusionados);
        
        if ($resultadoFusionados) {
            while ($fila = mysqli_fetch_assoc($resultadoFusionados)) {
                // CRÍTICO: Para fusionados, id_sub_hor_horario es el del sub_hor dominante
                $id_sub_hor = $fila['id_sub_hor_horario'];
                
                $semana = calcularSemana($fila['inicio'], $fila['fin']);
                
                $lunes = obtenerHorarioDia($db, $id_sub_hor, 'Lunes');
                $martes = obtenerHorarioDia($db, $id_sub_hor, 'Martes');
                $miercoles = obtenerHorarioDia($db, $id_sub_hor, 'Miércoles');
                $jueves = obtenerHorarioDia($db, $id_sub_hor, 'Jueves');
                $viernes = obtenerHorarioDia($db, $id_sub_hor, 'Viernes');
                $sabado = obtenerHorarioDia($db, $id_sub_hor, 'Sábado');
                $domingo = obtenerHorarioDia($db, $id_sub_hor, 'Domingo');
                
                $hLunes = calcularHorasHorario($lunes);
                $hMartes = calcularHorasHorario($martes);
                $hMiercoles = calcularHorasHorario($miercoles);
                $hJueves = calcularHorasHorario($jueves);
                $hViernes = calcularHorasHorario($viernes);
                $hSabado = calcularHorasHorario($sabado);
                $hDomingo = calcularHorasHorario($domingo);
                
                $horasPorDia['Lunes'] += $hLunes;
                $horasPorDia['Martes'] += $hMartes;
                $horasPorDia['Miércoles'] += $hMiercoles;
                $horasPorDia['Jueves'] += $hJueves;
                $horasPorDia['Viernes'] += $hViernes;
                $horasPorDia['Sábado'] += $hSabado;
                $horasPorDia['Domingo'] += $hDomingo;
                
                $totalHorasGlobal += ($hLunes + $hMartes + $hMiercoles + $hJueves + $hViernes + $hSabado + $hDomingo);
                
                $materiaDisplay = $fila['materia_dominante'] . ' (' . $fila['total_materias'] . ')';
                
                // ============================================================
                // ARRAY DE 24 ELEMENTOS - FUSIONADOS
                // ============================================================
                // 0:  id_fus (para modal de fusión)
                // 1:  id_sub_hor del dominante (para validaciones)
                // ============================================================
                
                $horariosTemp[] = array(
                    'id_orden' => intval($fila['id_orden']),
                    'data' => array(
                        $fila['id'],                    // 0: id_fus (para modal)
                        $fila['id_sub_hor_horario'],    // 1: id_sub_hor dominante (para validaciones)
                        $fila['plantel'],               // 2: plantel
                        $fila['tipo'],                  // 3: tipo
                        $fila['ciclo'],                 // 4: ciclo
                        $fila['inicio'],                // 5: inicio
                        $fila['fin'],                   // 6: fin
                        $fila['grupo'],                 // 7: grupo
                        $semana,                        // 8: semana
                        $fila['profesor'],              // 9: profesor
                        $materiaDisplay,                // 10: materia (con contador)
                        $fila['total_planteles'],       // 11: CDES
                        $fila['total_alumnos'],         // 12: alumnos
                        $fila['salon'],                 // 13: salon
                        $fila['programa'],              // 14: programa
                        $fila['nivel'],                 // 15: nivel
                        $fila['url_sub_hor'],           // 16: url
                        $lunes,                         // 17: lunes
                        $martes,                        // 18: martes
                        $miercoles,                     // 19: miercoles
                        $jueves,                        // 20: jueves
                        $viernes,                       // 21: viernes
                        $sabado,                        // 22: sabado
                        $domingo                        // 23: domingo
                    )
                );
            }
        }
    }
    
    usort($horariosTemp, function($a, $b) {
        return $b['id_orden'] - $a['id_orden'];
    });
    
    $horarios = array_map(function($item) {
        return $item['data'];
    }, $horariosTemp);
    
    $totalNormales = 0;
    $totalFusionados = 0;
    foreach ($horarios as $h) {
        if ($h[3] === 'Normal') {  // Índice 3 es 'tipo'
            $totalNormales++;
        } else if ($h[3] === 'Fusionado') {
            $totalFusionados++;
        }
    }
    
    $response = array(
        'horarios' => $horarios,
        'metricas' => array(
            'total' => count($horarios),
            'normales' => $totalNormales,
            'fusionados' => $totalFusionados,
            'totalHoras' => round($totalHorasGlobal, 1),
            'horasPorDia' => array(
                'lunes' => round($horasPorDia['Lunes'], 1),
                'martes' => round($horasPorDia['Martes'], 1),
                'miercoles' => round($horasPorDia['Miércoles'], 1),
                'jueves' => round($horasPorDia['Jueves'], 1),
                'viernes' => round($horasPorDia['Viernes'], 1),
                'sabado' => round($horasPorDia['Sábado'], 1),
                'domingo' => round($horasPorDia['Domingo'], 1)
            )
        )
    );
    
    echo json_encode($response);
    exit;
}

// ========== SECCIÓN 1: OBTENER DETALLE ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_detalle') {
    
    $id = intval($_POST['id']);
    $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
    
    if ($tipo === 'Normal') {
        $sql = "
            SELECT 
                sh.*,
                g.nom_gru,
                c.nom_cic, c.ini_cic, c.fin_cic,
                pr.id_pro,
                CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor,
                pr.cor_pro, pr.pas_pro,
                m.nom_mat, m.id_mat,
                r.nom_ram, r.id_ram,
                IFNULL(r.abr_ram, r.nom_ram) AS abr_ram,
                IFNULL(r.gra_ram, 'N/A') AS gra_ram,
                p.nom_pla, p.id_pla,
                sh.id_sal1,
                IFNULL(s.nom_sal, 'N/A') AS salon,
                IFNULL(sh.url_sub_hor, '') AS url_sub_hor,
                (SELECT COUNT(*) FROM alu_hor ah WHERE ah.id_sub_hor5 = sh.id_sub_hor AND ah.est_alu_hor = 'Activo') AS total_alumnos
            FROM sub_hor sh
            INNER JOIN grupo g ON g.id_gru = sh.id_gru1
            INNER JOIN ciclo c ON c.id_cic = g.id_cic1
            INNER JOIN rama r ON r.id_ram = c.id_ram1
            INNER JOIN plantel p ON p.id_pla = r.id_pla1
            INNER JOIN profesor pr ON pr.id_pro = sh.id_pro1
            INNER JOIN materia m ON m.id_mat = sh.id_mat1
            LEFT JOIN salon s ON s.id_sal = sh.id_sal1
            WHERE sh.id_sub_hor = $id
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $detalle = mysqli_fetch_assoc($resultado);
            
            $sqlHorarios = "SELECT * FROM horario WHERE id_sub_hor1 = $id ORDER BY FIELD(dia_hor, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
            $resHorarios = mysqli_query($db, $sqlHorarios);
            $horarios = array();
            while ($h = mysqli_fetch_assoc($resHorarios)) {
                $horarios[] = $h;
            }
            $detalle['horarios'] = $horarios;
            
            $id_pla = $detalle['id_pla'];
            $sqlProfesores = "
                SELECT id_pro, CONCAT(nom_pro, ' ', app_pro) AS nombre_completo
                FROM profesor
                WHERE id_pla2 = '$id_pla' AND est_pro = 'Activo'
                ORDER BY nom_pro, app_pro
            ";
            $resProfesores = mysqli_query($db, $sqlProfesores);
            $profesores = array();
            while ($p = mysqli_fetch_assoc($resProfesores)) {
                $profesores[] = $p;
            }
            $detalle['profesores_disponibles'] = $profesores;
            
            $sqlSalones = "
                SELECT id_sal, nom_sal
                FROM salon
                WHERE id_pla11 = '$id_pla'
                ORDER BY nom_sal
            ";
            $resSalones = mysqli_query($db, $sqlSalones);
            $salones = array();
            if ($resSalones && mysqli_num_rows($resSalones) > 0) {
                while ($s = mysqli_fetch_assoc($resSalones)) {
                    $salones[] = $s;
                }
            }
            $detalle['salones_disponibles'] = $salones;
            
            echo json_encode(['success' => true, 'detalle' => $detalle, 'tipo' => 'Normal']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Grupo no encontrado']);
        }
        
    } else if ($tipo === 'Fusionado') {
        $sql = "
            SELECT 
                f.*,
                sh.id_sub_hor AS id_sub_hor_dominante,
                sh.id_pro1 AS id_pro_dominante,
                CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor_dominante,
                pr.cor_pro, pr.pas_pro,
                m.nom_mat AS materia_dominante, m.id_mat,
                r.nom_ram, r.id_ram,
                IFNULL(r.abr_ram, r.nom_ram) AS abr_ram,
                IFNULL(r.gra_ram, 'N/A') AS gra_ram,
                p.nom_pla, p.id_pla,
                sh.id_sal1,
                IFNULL(s.nom_sal, 'N/A') AS salon,
                
                -- TRAER VALORES DE LA TABLA FUSION
                IFNULL(f.url_fus, '') AS url_fusion,
                IFNULL(f.con_fus, '') AS con_fusion,
                IFNULL(f.cos_fus, 0) AS cos_fusion
            FROM fusion f
            INNER JOIN sub_hor sh ON sh.id_fus2 = f.id_fus AND sh.id_sub_hor_nat IS NULL
            INNER JOIN profesor pr ON pr.id_pro = sh.id_pro1
            INNER JOIN materia m ON m.id_mat = sh.id_mat1
            INNER JOIN rama r ON r.id_ram = m.id_ram2
            INNER JOIN plantel p ON p.id_pla = r.id_pla1
            LEFT JOIN salon s ON s.id_sal = sh.id_sal1
            WHERE f.id_fus = $id
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $detalle = mysqli_fetch_assoc($resultado);
            
            // TRAER TODOS LOS SUB_HORS
            $sqlSubHors = "
                SELECT 
                    sh.id_sub_hor,
                    sh.id_sub_hor_nat,
                    sh.id_pro1,
                    sh.id_sal1,
                    m.nom_mat, m.id_mat,
                    r.nom_ram,
                    IFNULL(r.abr_ram, r.nom_ram) AS abr_ram,
                    IFNULL(r.gra_ram, 'N/A') AS gra_ram,
                    p.nom_pla, p.id_pla,
                    pr.id_pro,
                    CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor,
                    IFNULL(s.nom_sal, 'N/A') AS salon,
                    (SELECT COUNT(*) FROM alu_hor ah WHERE ah.id_sub_hor5 = sh.id_sub_hor AND ah.est_alu_hor = 'Activo') AS alumnos
                FROM sub_hor sh
                INNER JOIN materia m ON m.id_mat = sh.id_mat1
                INNER JOIN rama r ON r.id_ram = m.id_ram2
                INNER JOIN plantel p ON p.id_pla = r.id_pla1
                INNER JOIN profesor pr ON pr.id_pro = sh.id_pro1
                LEFT JOIN salon s ON s.id_sal = sh.id_sal1
                WHERE sh.id_fus2 = $id
                ORDER BY sh.id_sub_hor_nat IS NULL DESC, sh.id_sub_hor ASC
            ";
            $resSubHors = mysqli_query($db, $sqlSubHors);
            $subHors = array();
            $totalAlumnos = 0;
            while ($sh = mysqli_fetch_assoc($resSubHors)) {
                $subHors[] = $sh;
                $totalAlumnos += intval($sh['alumnos']);
            }
            $detalle['sub_hors'] = $subHors;
            $detalle['total_alumnos'] = $totalAlumnos;
            $detalle['total_materias'] = count($subHors);
            
            // Horarios del dominante
            $id_sub_hor_dom = $detalle['id_sub_hor_dominante'];
            $sqlHorarios = "SELECT * FROM horario WHERE id_sub_hor1 = $id_sub_hor_dom ORDER BY FIELD(dia_hor, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
            $resHorarios = mysqli_query($db, $sqlHorarios);
            $horarios = array();
            while ($h = mysqli_fetch_assoc($resHorarios)) {
                $horarios[] = $h;
            }
            $detalle['horarios'] = $horarios;
            
            // Profesores disponibles
            $id_pla = $detalle['id_pla'];
            $sqlProfesores = "
                SELECT id_pro, CONCAT(nom_pro, ' ', app_pro) AS nombre_completo
                FROM profesor
                WHERE id_pla2 = '$id_pla' AND est_pro = 'Activo'
                ORDER BY nom_pro, app_pro
            ";
            $resProfesores = mysqli_query($db, $sqlProfesores);
            $profesores = array();
            while ($p = mysqli_fetch_assoc($resProfesores)) {
                $profesores[] = $p;
            }
            $detalle['profesores_disponibles'] = $profesores;
            
            // Salones disponibles
            $sqlSalones = "
                SELECT id_sal, nom_sal
                FROM salon
                WHERE id_pla11 = '$id_pla'
                ORDER BY nom_sal
            ";
            $resSalones = mysqli_query($db, $sqlSalones);
            $salones = array();
            if ($resSalones && mysqli_num_rows($resSalones) > 0) {
                while ($s = mysqli_fetch_assoc($resSalones)) {
                    $salones[] = $s;
                }
            }
            $detalle['salones_disponibles'] = $salones;
            
            echo json_encode(['success' => true, 'detalle' => $detalle, 'tipo' => 'Fusionado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Fusión no encontrada']);
        }
    }
   
    
    exit;
}

// ========== SECCIÓN 2: ELIMINAR GRUPO NORMAL ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'eliminar_normal') {
    
    $id_sub_hor = intval($_POST['id']);
    
    $sqlCheck = "SELECT COUNT(*) AS total FROM alu_hor WHERE id_sub_hor5 = $id_sub_hor AND est_alu_hor = 'Activo'";
    $resCheck = mysqli_query($db, $sqlCheck);
    $filaCheck = mysqli_fetch_assoc($resCheck);
    
    if ($filaCheck['total'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar: tiene ' . $filaCheck['total'] . ' alumno(s) activo(s)'
        ]);
        exit;
    }
    
    mysqli_query($db, "DELETE FROM horario WHERE id_sub_hor1 = $id_sub_hor");
    mysqli_query($db, "DELETE FROM alu_hor WHERE id_sub_hor5 = $id_sub_hor");
    mysqli_query($db, "DELETE FROM validacion_sub_hor WHERE id_sub_hor1 = $id_sub_hor");
    $resultado = mysqli_query($db, "DELETE FROM sub_hor WHERE id_sub_hor = $id_sub_hor");
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Grupo eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . mysqli_error($db)]);
    }
    
    exit;
}

// ========== SECCIÓN 3: ELIMINAR FUSIÓN ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'eliminar_fusion') {
    
    $id_fus = intval($_POST['id']);
    
    $sqlCheck = "
        SELECT COUNT(*) AS total 
        FROM alu_hor ah
        INNER JOIN sub_hor sh ON sh.id_sub_hor = ah.id_sub_hor5
        WHERE sh.id_fus2 = $id_fus AND ah.est_alu_hor = 'Activo'
    ";
    $resCheck = mysqli_query($db, $sqlCheck);
    $filaCheck = mysqli_fetch_assoc($resCheck);
    
    if ($filaCheck['total'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar: tiene ' . $filaCheck['total'] . ' alumno(s) activo(s)'
        ]);
        exit;
    }
    
    $sqlSubHors = "SELECT id_sub_hor FROM sub_hor WHERE id_fus2 = $id_fus";
    $resSubHors = mysqli_query($db, $sqlSubHors);
    
    while ($sh = mysqli_fetch_assoc($resSubHors)) {
        $id_sh = $sh['id_sub_hor'];
        mysqli_query($db, "DELETE FROM horario WHERE id_sub_hor1 = $id_sh");
        mysqli_query($db, "DELETE FROM alu_hor WHERE id_sub_hor5 = $id_sh");
        mysqli_query($db, "DELETE FROM validacion_sub_hor WHERE id_sub_hor1 = $id_sh");
    }
    
    mysqli_query($db, "DELETE FROM sub_hor WHERE id_fus2 = $id_fus");
    $resultado = mysqli_query($db, "DELETE FROM fusion WHERE id_fus = $id_fus");
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Fusión eliminada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . mysqli_error($db)]);
    }
    
    exit;
}

// ========== SECCIÓN 4: OBTENER PLANTELES ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_planteles') {
    
    $sql = "
        SELECT DISTINCT p.id_pla, p.nom_pla
        FROM plantel p
        WHERE p.id_cad1 = '$cadena'
        ORDER BY p.nom_pla ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $planteles = array();
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $planteles[] = $fila;
    }
    
    echo json_encode(['success' => true, 'planteles' => $planteles]);
    exit;
}

// ========== SECCIÓN 5: OBTENER NIVELES ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_niveles') {
    
    $plantelesCondicion = "";
    if(isset($_POST['planteles']) && !empty($_POST['planteles'])) {
        $plantelesLimpios = array_map('intval', $_POST['planteles']);
        $plantelesStr = implode(',', $plantelesLimpios);
        $plantelesCondicion = " AND r.id_pla1 IN ($plantelesStr) ";
    }
    
    $sql = "
        SELECT DISTINCT r.gra_ram
        FROM rama r
        INNER JOIN plantel p ON p.id_pla = r.id_pla1
        WHERE p.id_cad1 = '$cadena'
        AND r.gra_ram IS NOT NULL 
        AND r.gra_ram != ''
        $plantelesCondicion
        ORDER BY r.gra_ram ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $niveles = array();
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $niveles[] = $fila['gra_ram'];
    }
    
    echo json_encode(['success' => true, 'niveles' => $niveles]);
    exit;
}

// ========== SECCIÓN 6: OBTENER PROFESORES ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_profesores') {
    
    $plantelesCondicion = "";
    if(isset($_POST['planteles']) && !empty($_POST['planteles'])) {
        $plantelesLimpios = array_map('intval', $_POST['planteles']);
        $plantelesStr = implode(',', $plantelesLimpios);
        $plantelesCondicion = " AND p.id_pla IN ($plantelesStr) ";
    }
    
    $sql = "
        SELECT DISTINCT pr.id_pro, pr.nom_pro, pr.app_pro,
               CONCAT(pr.nom_pro, ' ', pr.app_pro) AS nombre_completo
        FROM profesor pr
        INNER JOIN sub_hor sh ON sh.id_pro1 = pr.id_pro
        INNER JOIN materia m ON m.id_mat = sh.id_mat1
        INNER JOIN rama r ON r.id_ram = m.id_ram2
        INNER JOIN plantel p ON p.id_pla = r.id_pla1
        WHERE p.id_cad1 = '$cadena'
        AND pr.est_pro = 'Activo'
        $plantelesCondicion
        ORDER BY pr.nom_pro ASC, pr.app_pro ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $profesores = array();
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $profesores[] = $fila;
    }
    
    echo json_encode(['success' => true, 'profesores' => $profesores]);
    exit;
}

// ========== SECCIÓN 7: ACTUALIZAR SUB_HOR (PROFESOR, SALÓN, URL) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_sub_hor') {
    
    $id_sub_hor = intval($_POST['id_sub_hor']);
    $id_pro = intval($_POST['id_pro']);
    $id_sal = isset($_POST['id_sal']) && $_POST['id_sal'] !== '' ? intval($_POST['id_sal']) : 'NULL';
    $url_sub_hor = mysqli_real_escape_string($db, trim($_POST['url_sub_hor']));
    $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
    $es_fusion = ($tipo === 'Fusionado');
    
    $sqlCheck = "SELECT id_sub_hor, id_fus2, id_sub_hor_nat FROM sub_hor WHERE id_sub_hor = $id_sub_hor";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario no encontrado']);
        exit;
    }
    
    $filaCheck = mysqli_fetch_assoc($resCheck);
    
    $actualizarFusion = false;
    $id_fus = null;
    if ($es_fusion && $filaCheck['id_fus2'] !== null && $filaCheck['id_sub_hor_nat'] === null) {
        $actualizarFusion = true;
        $id_fus = $filaCheck['id_fus2'];
    }
    
    $id_sal_value = ($id_sal === 'NULL') ? 'NULL' : "'$id_sal'";
    
    $sqlUpdate = "
        UPDATE sub_hor 
        SET id_pro1 = '$id_pro',
            id_sal1 = $id_sal_value,
            url_sub_hor = '$url_sub_hor'
        WHERE id_sub_hor = $id_sub_hor
    ";
    
    $resultado = mysqli_query($db, $sqlUpdate);
    
    if ($resultado) {
        if ($actualizarFusion && $id_fus) {
            $sqlUpdateFusion = "UPDATE fusion SET id_pro8 = '$id_pro' WHERE id_fus = $id_fus";
            mysqli_query($db, $sqlUpdateFusion);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Sub-horario actualizado correctamente',
            'es_fusion' => $es_fusion,
            'actualizo_fusion' => $actualizarFusion
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . mysqli_error($db)]);
    }
    
    exit;
}

// ========== SECCIÓN 8: ACTUALIZAR HORARIOS (DÍAS Y HORAS) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_horarios') {
    
    $id_sub_hor = intval($_POST['id_sub_hor']);
    $horarios = isset($_POST['horarios']) ? $_POST['horarios'] : array();
    
    $sqlCheck = "SELECT id_sub_hor FROM sub_hor WHERE id_sub_hor = $id_sub_hor";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario no encontrado']);
        exit;
    }
    
    $sqlDelete = "DELETE FROM horario WHERE id_sub_hor1 = $id_sub_hor";
    mysqli_query($db, $sqlDelete);
    
    $diasValidos = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    $insertados = 0;
    $errores = array();
    
    foreach ($horarios as $horario) {
        $dia = mysqli_real_escape_string($db, $horario['dia']);
        $ini_hor = mysqli_real_escape_string($db, $horario['ini_hor']);
        $fin_hor = mysqli_real_escape_string($db, $horario['fin_hor']);
        
        if (!in_array($dia, $diasValidos)) {
            $errores[] = "Día inválido: $dia";
            continue;
        }
        
        if (empty($ini_hor) || empty($fin_hor)) {
            continue;
        }
        
        if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $ini_hor) || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $fin_hor)) {
            $errores[] = "Formato de hora inválido para $dia";
            continue;
        }
        
        $sqlInsert = "
            INSERT INTO horario (dia_hor, ini_hor, fin_hor, id_sub_hor1)
            VALUES ('$dia', '$ini_hor', '$fin_hor', '$id_sub_hor')
        ";
        
        if (mysqli_query($db, $sqlInsert)) {
            $insertados++;
        } else {
            $errores[] = "Error al insertar $dia: " . mysqli_error($db);
        }
    }
    
    if (count($errores) > 0) {
        echo json_encode([
            'success' => true, 
            'message' => "Horarios actualizados con advertencias",
            'insertados' => $insertados,
            'errores' => $errores
        ]);
    } else {
        echo json_encode([
            'success' => true, 
            'message' => 'Horarios actualizados correctamente',
            'insertados' => $insertados
        ]);
    }
    
    exit;
}


// ========== SECCIÓN: ACTUALIZAR FUSIÓN COMPLETA (ATOMIZAR) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_fusion_atomizar') {
    
    $id_fus = intval($_POST['id_fus']);
    $url_fusion = mysqli_real_escape_string($db, trim($_POST['url_fusion']));
    $con_fusion = mysqli_real_escape_string($db, trim($_POST['con_fusion']));
    $cos_fusion = isset($_POST['cos_fusion']) && $_POST['cos_fusion'] !== '' ? floatval($_POST['cos_fusion']) : 'NULL';
    
    // ✅ PASO 1: ACTUALIZAR TABLA FUSION
    $cosValue = ($cos_fusion === 'NULL') ? 'NULL' : "'$cos_fusion'";
    
    $sqlUpdateFusion = "
        UPDATE fusion 
        SET url_fus = '$url_fusion',
            con_fus = '$con_fusion',
            cos_fus = $cosValue
        WHERE id_fus = $id_fus
    ";
    
    $resultFusion = mysqli_query($db, $sqlUpdateFusion);
    
    if (!$resultFusion) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar fusión: ' . mysqli_error($db)]);
        exit;
    }
    
    // ✅ PASO 2: ATOMIZAR A TODOS LOS SUB_HORS
    $sqlAtomizar = "
        UPDATE sub_hor 
        SET url_sub_hor = '$url_fusion',
            con_url_sub_hor = '$con_fusion',
            cos_sub_hor = $cosValue
        WHERE id_fus2 = $id_fus
    ";
    
    $resultAtomizar = mysqli_query($db, $sqlAtomizar);
    
    if ($resultAtomizar) {
        $totalActualizados = mysqli_affected_rows($db);
        echo json_encode([
            'success' => true, 
            'message' => 'Fusión actualizada y atomizada',
            'atomizados' => $totalActualizados
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al atomizar: ' . mysqli_error($db)]);
    }
    
    exit;
}



if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_sub_hor_fusion') {
    
    $id_sub_hor = intval($_POST['id_sub_hor']);
    $id_pro = intval($_POST['id_pro']);
    $id_sal = isset($_POST['id_sal']) && $_POST['id_sal'] !== '' ? intval($_POST['id_sal']) : 'NULL';
    
    $sqlCheck = "SELECT id_sub_hor, id_fus2, id_sub_hor_nat FROM sub_hor WHERE id_sub_hor = $id_sub_hor AND id_fus2 IS NOT NULL";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario de fusión no encontrado']);
        exit;
    }
    
    $filaCheck = mysqli_fetch_assoc($resCheck);
    $esDominante = ($filaCheck['id_sub_hor_nat'] === null);
    
    $id_sal_value = ($id_sal === 'NULL') ? 'NULL' : "'$id_sal'";
    
    // SOLO ACTUALIZAR PROFESOR Y SALÓN
    $sqlUpdate = "
        UPDATE sub_hor 
        SET id_pro1 = '$id_pro',
            id_sal1 = $id_sal_value
        WHERE id_sub_hor = $id_sub_hor
    ";
    
    $resultado = mysqli_query($db, $sqlUpdate);
    
    if ($resultado) {
        // Si es dominante, actualizar también la tabla fusion
        if ($esDominante) {
            $id_fus = $filaCheck['id_fus2'];
            $sqlUpdateFusion = "UPDATE fusion SET id_pro8 = '$id_pro' WHERE id_fus = $id_fus";
            mysqli_query($db, $sqlUpdateFusion);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Sub-horario actualizado',
            'es_dominante' => $esDominante
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . mysqli_error($db)]);
    }
    
    exit;
}

// ========== SECCIÓN 9: ACTUALIZAR SUB_HOR DE FUSIÓN (INDIVIDUAL) ==========
// ========== NUEVA SECCIÓN: ACTUALIZAR FUSIÓN COMPLETA (ATOMIZAR) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_fusion_atomizar') {
    
    $id_fus = intval($_POST['id_fus']);
    $url_fusion = mysqli_real_escape_string($db, trim($_POST['url_fusion']));
    $con_fusion = mysqli_real_escape_string($db, trim($_POST['con_fusion']));
    $cos_fusion = isset($_POST['cos_fusion']) && $_POST['cos_fusion'] !== '' ? floatval($_POST['cos_fusion']) : 'NULL';
    
    // PASO 1: ACTUALIZAR TABLA FUSION
    $cosValue = ($cos_fusion === 'NULL') ? 'NULL' : "'$cos_fusion'";
    
    $sqlUpdateFusion = "
        UPDATE fusion 
        SET url_fus = '$url_fusion',
            con_fus = '$con_fusion',
            cos_fus = $cosValue
        WHERE id_fus = $id_fus
    ";
    
    $resultFusion = mysqli_query($db, $sqlUpdateFusion);
    
    if (!$resultFusion) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar fusión: ' . mysqli_error($db)]);
        exit;
    }
    
    // PASO 2: ATOMIZAR A TODOS LOS SUB_HORS
    $sqlAtomizar = "
        UPDATE sub_hor 
        SET url_sub_hor = '$url_fusion',
            con_url_sub_hor = '$con_fusion',
            cos_sub_hor = $cosValue
        WHERE id_fus2 = $id_fus
    ";
    
    $resultAtomizar = mysqli_query($db, $sqlAtomizar);
    
    if ($resultAtomizar) {
        $totalActualizados = mysqli_affected_rows($db);
        echo json_encode([
            'success' => true, 
            'message' => 'Fusión actualizada y atomizada',
            'atomizados' => $totalActualizados
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al atomizar: ' . mysqli_error($db)]);
    }
    
    exit;
}

// ========== SECCIÓN 10: OBTENER SALONES POR PLANTEL ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_salones_plantel') {
    
    $id_pla = intval($_POST['id_pla']);
    
    $sql = "
        SELECT id_sal, nom_sal
        FROM salon
        WHERE id_pla11 = '$id_pla'
        ORDER BY nom_sal ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $salones = array();
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $salones[] = $fila;
        }
    }
    
    echo json_encode(['success' => true, 'salones' => $salones]);
    exit;
}

// ========== SECCIÓN 11: OBTENER PROFESORES POR PLANTEL ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_profesores_plantel') {
    
    $id_pla = intval($_POST['id_pla']);
    
    $sql = "
        SELECT id_pro, CONCAT(nom_pro, ' ', app_pro) AS nombre_completo
        FROM profesor
        WHERE id_pla2 = '$id_pla' AND est_pro = 'Activo'
        ORDER BY nom_pro, app_pro ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $profesores = array();
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $profesores[] = $fila;
    }
    
    echo json_encode(['success' => true, 'profesores' => $profesores]);
    exit;
}

// ========== SECCIÓN 12: OBTENER MATERIAS POR PROGRAMA ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_materias_programa') {
    
    $id_ram = intval($_POST['id_ram']);
    
    $sql = "
        SELECT id_mat, nom_mat
        FROM materia
        WHERE id_ram2 = '$id_ram'
        ORDER BY nom_mat ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $materias = array();
    
    while($fila = mysqli_fetch_assoc($resultado)) {
        $materias[] = $fila;
    }
    
    echo json_encode(['success' => true, 'materias' => $materias]);
    exit;
}

// ========== SECCIÓN 13: OBTENER SALONES POR RAMA ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_salones_rama') {
    
    $id_ram = intval($_POST['id_ram']);
    
    $sql = "
        SELECT s.id_sal, s.nom_sal
        FROM salon s
        INNER JOIN rama r ON r.id_pla1 = s.id_pla11
        WHERE r.id_ram = '$id_ram'
        ORDER BY s.nom_sal ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $salones = array();
    
    while($fila = mysqli_fetch_assoc($resultado)) {
        $salones[] = $fila;
    }
    
    echo json_encode(['success' => true, 'salones' => $salones]);
    exit;
}


// ========================================================================
// SECCIÓN: GUARDAR FUSIÓN CON VIDEOCONFERENCIA + COSTO + HORARIOS
// ========================================================================
if(isset($_POST['accion']) && $_POST['accion'] === 'guardar_fusion') {
    
    $nom_fus = mysqli_real_escape_string($db, trim($_POST['nom_fus']));
    $cic_fus = mysqli_real_escape_string($db, trim($_POST['cic_fus']));
    $ins_fus = mysqli_real_escape_string($db, $_POST['ins_fus']);
    $ini_fus = mysqli_real_escape_string($db, $_POST['ini_fus']);
    $cor_fus = mysqli_real_escape_string($db, $_POST['cor_fus']);
    $fin_fus = mysqli_real_escape_string($db, $_POST['fin_fus']);
    
    // ✅ VIDEOCONFERENCIA (PROPAGADO)
    $url_sub_hor = isset($_POST['url_sub_hor']) && !empty(trim($_POST['url_sub_hor'])) 
        ? mysqli_real_escape_string($db, trim($_POST['url_sub_hor'])) 
        : '';
    
    $con_url_sub_hor = isset($_POST['con_url_sub_hor']) && !empty(trim($_POST['con_url_sub_hor'])) 
        ? mysqli_real_escape_string($db, trim($_POST['con_url_sub_hor'])) 
        : '';
    
    // ✅ COSTO POR HORA (PROPAGADO)
    $cos_sub_hor = isset($_POST['cos_sub_hor']) && !empty(trim($_POST['cos_sub_hor'])) 
        ? floatval($_POST['cos_sub_hor']) 
        : NULL;
    
    $datosEnvio = json_decode($_POST['datosEnvio'], true);
    
    // Validaciones básicas
    if (empty($nom_fus) || empty($cic_fus)) {
        echo json_encode(array('ok' => false, 'error' => 'Nombre y ciclo son obligatorios'));
        exit;
    }
    
    if (!isset($datosEnvio['secciones']) || empty($datosEnvio['secciones'])) {
        echo json_encode(array('ok' => false, 'error' => 'Debe haber al menos un horario con materias'));
        exit;
    }
    
    foreach ($datosEnvio['secciones'] as $idx => $seccion) {
        if (empty($seccion['materias'])) {
            echo json_encode(array('ok' => false, 'error' => 'Horario ' . ($idx + 1) . ' no tiene materias'));
            exit;
        }
        if (empty($seccion['profesor'])) {
            echo json_encode(array('ok' => false, 'error' => 'Horario ' . ($idx + 1) . ' no tiene profesor'));
            exit;
        }
    }
    
    // Validar duplicados globales
    $todasMaterias = array();
    foreach ($datosEnvio['secciones'] as $seccion) {
        foreach ($seccion['materias'] as $mat) {
            if (in_array($mat, $todasMaterias)) {
                echo json_encode(array('ok' => false, 'error' => 'Materia duplicada: ' . $mat));
                exit;
            }
            $todasMaterias[] = $mat;
        }
    }
    
    $id_pro_principal = mysqli_real_escape_string($db, $datosEnvio['secciones'][0]['profesor']);
    
    // ============================================================
    // PASO 1: IDENTIFICAR RAMAS Y CREAR CICLOS/GRUPOS
    // ============================================================
    $ramasInvolucradas = array();
    foreach ($datosEnvio['secciones'] as $seccion) {
        foreach ($seccion['materias'] as $id_mat) {
            $sqlRama = "SELECT m.id_ram2 FROM materia m WHERE m.id_mat = '$id_mat'";
            $resRama = mysqli_query($db, $sqlRama);
            if ($resRama && mysqli_num_rows($resRama) > 0) {
                $filaRama = mysqli_fetch_assoc($resRama);
                $id_ram = $filaRama['id_ram2'];
                if (!isset($ramasInvolucradas[$id_ram])) {
                    $ramasInvolucradas[$id_ram] = array('id_ram' => $id_ram);
                }
            }
        }
    }
    
    // CREAR CICLOS Y GRUPOS POR CADA RAMA
    $ciclosYGrupos = array();
    foreach ($ramasInvolucradas as $rama) {
        $id_ram = $rama['id_ram'];
        
        $sqlCiclo = "
            INSERT INTO ciclo (nom_cic, des_cic, ins_cic, ini_cic, cor_cic, fin_cic, fec_cic, id_ram1)
            VALUES ('$cic_fus', 'Grupo fusionado', '$ins_fus', '$ini_fus', '$cor_fus', '$fin_fus', NOW(), '$id_ram')
        ";
        if (!mysqli_query($db, $sqlCiclo)) {
            echo json_encode(array('ok' => false, 'error' => 'Error ciclo: ' . mysqli_error($db)));
            exit;
        }
        $id_cic = mysqli_insert_id($db);
        
        $sqlGrupo = "INSERT INTO grupo (nom_gru, id_cic1) VALUES ('$nom_fus', '$id_cic')";
        if (!mysqli_query($db, $sqlGrupo)) {
            echo json_encode(array('ok' => false, 'error' => 'Error grupo: ' . mysqli_error($db)));
            exit;
        }
        $id_gru = mysqli_insert_id($db);
        
        $ciclosYGrupos[$id_ram] = array('id_cic' => $id_cic, 'id_gru' => $id_gru);
    }
    
    // ============================================================
    // PASO 2: CREAR FUSIONES (UNA POR SECCIÓN)
    // ============================================================
    $id_fus_array = array();
    foreach ($datosEnvio['secciones'] as $idx => $seccion) {
        $id_pro_seccion = mysqli_real_escape_string($db, $seccion['profesor']);
        
        $sqlFusion = "
            INSERT INTO fusion (nom_fus, est_fus, cic_fus, ins_fus, ini_fus, cor_fus, fin_fus, id_pro8, id_cad2)
            VALUES ('$nom_fus', 'Activo', '$cic_fus', '$ins_fus', '$ini_fus', '$cor_fus', '$fin_fus', '$id_pro_seccion', '$cadena')
        ";
        if (!mysqli_query($db, $sqlFusion)) {
            echo json_encode(array('ok' => false, 'error' => 'Error fusion: ' . mysqli_error($db)));
            exit;
        }
        $id_fus_array[$idx] = mysqli_insert_id($db);
    }
    
    // ============================================================
    // PASO 3: CREAR SUB_HOR CON VIDEOCONFERENCIA + COSTO
    // (PRIMERA MATERIA DE CADA SECCIÓN = DOMINANTE)
    // ============================================================
    $subHorsGenerados = array();
    
    foreach ($datosEnvio['secciones'] as $idxSeccion => $seccion) {
        $id_fus = $id_fus_array[$idxSeccion];
        $profesor = mysqli_real_escape_string($db, $seccion['profesor']);
        $materias = $seccion['materias'];
        
        foreach ($materias as $idxMat => $id_mat) {
            // Obtener rama de la materia
            $sqlRama = "SELECT m.id_ram2 FROM materia m WHERE m.id_mat = '$id_mat'";
            $resRama = mysqli_query($db, $sqlRama);
            $filaRama = mysqli_fetch_assoc($resRama);
            $id_ram = $filaRama['id_ram2'];
            $id_gru = $ciclosYGrupos[$id_ram]['id_gru'];
            
            // ✅ CREAR SUB_HOR CON VIDEOCONFERENCIA + COSTO (PROPAGADO)
            $cosValue = ($cos_sub_hor !== NULL) ? "'$cos_sub_hor'" : "NULL";
            
            $sqlSubhor = "
                INSERT INTO sub_hor (
                    nom_sub_hor, 
                    est_sub_hor, 
                    fec_sub_hor, 
                    id_gru1, 
                    id_mat1, 
                    id_pro1, 
                    id_fus2,
                    url_sub_hor,
                    con_url_sub_hor,
                    cos_sub_hor
                )
                VALUES (
                    '$nom_fus', 
                    'Activo', 
                    NOW(), 
                    '$id_gru', 
                    '$id_mat', 
                    '$profesor', 
                    '$id_fus',
                    '$url_sub_hor',
                    '$con_url_sub_hor',
                    $cosValue
                )
            ";
            
            if (!mysqli_query($db, $sqlSubhor)) {
                echo json_encode(array('ok' => false, 'error' => 'Error sub_hor: ' . mysqli_error($db)));
                exit;
            }
            $id_sub_hor = mysqli_insert_id($db);
            
            // MARCAR DOMINANCIA: Primera materia de cada sección
            $es_dominante = ($idxMat == 0);
            
            // Si NO es dominante, marcarlo con id_sub_hor_nat apuntando a sí mismo
            if (!$es_dominante) {
                mysqli_query($db, "UPDATE sub_hor SET id_sub_hor_nat = '$id_sub_hor' WHERE id_sub_hor = '$id_sub_hor'");
            }
            // Si ES dominante, id_sub_hor_nat queda NULL
            
            $subHorsGenerados[] = array(
                'id_sub_hor' => $id_sub_hor,
                'id_mat' => $id_mat,
                'id_pro' => $profesor,
                'es_dominante' => $es_dominante,
                'id_fus' => $id_fus,
                'id_ram' => $id_ram,
                'id_seccion' => $idxSeccion
            );
        }
    }
    
    // ============================================================
    // PASO 4: CREAR HORARIOS (SOLO PARA DOMINANTES)
    // ============================================================
    foreach ($subHorsGenerados as $subHor) {
        if (!$subHor['es_dominante']) continue;
        
        $id_sub_hor = $subHor['id_sub_hor'];
        $id_seccion = $subHor['id_seccion'];
        
        // ✅ OBTENER HORARIOS DE LA SECCIÓN
        $horarios = isset($datosEnvio['secciones'][$id_seccion]['horarios']) 
            ? $datosEnvio['secciones'][$id_seccion]['horarios'] 
            : array();
        
        // ✅ INSERTAR HORARIOS DINÁMICOS
        foreach ($horarios as $h) {
            $dia = mysqli_real_escape_string($db, $h['dia']);
            $ini = mysqli_real_escape_string($db, $h['ini_hor']);
            $fin = mysqli_real_escape_string($db, $h['fin_hor']);
            
            $sqlHorario = "
                INSERT INTO horario (dia_hor, ini_hor, fin_hor, id_sub_hor1)
                VALUES ('$dia', '$ini', '$fin', '$id_sub_hor')
            ";
            mysqli_query($db, $sqlHorario);
        }
    }
    
    // ============================================================
    // PASO 5: COPIAR ACTIVIDADES (SOLO PARA DOMINANTES)
    // ============================================================
    foreach ($subHorsGenerados as $subHor) {
        if (!$subHor['es_dominante']) continue;
        
        $id_sub_hor = $subHor['id_sub_hor'];
        $id_mat = $subHor['id_mat'];
        
        $resBloque = mysqli_query($db, "SELECT id_blo FROM bloque WHERE id_mat6 = '$id_mat'");
        while ($bloque = mysqli_fetch_assoc($resBloque)) {
            $id_blo = $bloque['id_blo'];
            
            // Foros
            $resForo = mysqli_query($db, "SELECT * FROM foro WHERE id_blo4 = '$id_blo'");
            while ($foro = mysqli_fetch_assoc($resForo)) {
                if ($foro['ini_for'] != "" && $foro['fin_for'] != "") {
                    $ini = gmdate('Y-m-d', strtotime('+' . $foro['ini_for'] . ' days', strtotime($ini_fus)));
                    $fin = gmdate('Y-m-d', strtotime('+' . $foro['fin_for'] . ' days', strtotime($ini_fus)));
                    mysqli_query($db, "INSERT INTO foro_copia (ini_for_cop, fin_for_cop, id_for1, id_sub_hor2) VALUES ('$ini', '$fin', '{$foro['id_for']}', '$id_sub_hor')");
                }
            }
            
            // Entregables
            $resEnt = mysqli_query($db, "SELECT * FROM entregable WHERE id_blo5 = '$id_blo'");
            while ($ent = mysqli_fetch_assoc($resEnt)) {
                if ($ent['ini_ent'] != "" && $ent['fin_ent'] != "") {
                    $ini = gmdate('Y-m-d', strtotime('+' . $ent['ini_ent'] . ' days', strtotime($ini_fus)));
                    $fin = gmdate('Y-m-d', strtotime('+' . $ent['fin_ent'] . ' days', strtotime($ini_fus)));
                    mysqli_query($db, "INSERT INTO entregable_copia (ini_ent_cop, fin_ent_cop, id_ent1, id_sub_hor3) VALUES ('$ini', '$fin', '{$ent['id_ent']}', '$id_sub_hor')");
                }
            }
            
            // Exámenes
            $resExa = mysqli_query($db, "SELECT * FROM examen WHERE id_blo6 = '$id_blo'");
            while ($exa = mysqli_fetch_assoc($resExa)) {
                if ($exa['ini_exa'] != "" && $exa['fin_exa'] != "") {
                    $ini = gmdate('Y-m-d', strtotime('+' . $exa['ini_exa'] . ' days', strtotime($ini_fus)));
                    $fin = gmdate('Y-m-d', strtotime('+' . $exa['fin_exa'] . ' days', strtotime($ini_fus)));
                    mysqli_query($db, "INSERT INTO examen_copia (ini_exa_cop, fin_exa_cop, id_exa1, id_sub_hor4) VALUES ('$ini', '$fin', '{$exa['id_exa']}', '$id_sub_hor')");
                }
            }
        }
    }
    
    // ============================================================
    // PASO 6: CREAR SALAS (SOLO PARA DOMINANTES)
    // ============================================================
    foreach ($subHorsGenerados as $subHor) {
        if (!$subHor['es_dominante']) continue;
        
        $id_sub_hor = $subHor['id_sub_hor'];
        $id_mat = $subHor['id_mat'];
        $id_pro = $subHor['id_pro'];
        
        $resPla = mysqli_query($db, "SELECT r.id_pla1 FROM materia m INNER JOIN rama r ON r.id_ram = m.id_ram2 WHERE m.id_mat = '$id_mat'");
        $rowPla = mysqli_fetch_assoc($resPla);
        $id_pla = isset($rowPla['id_pla1']) ? $rowPla['id_pla1'] : '';
        
        mysqli_query($db, "INSERT INTO sala (nom_sal, id_sub_hor6, id_pla6) VALUES ((SELECT nom_mat FROM materia WHERE id_mat = '$id_mat'), '$id_sub_hor', '$id_pla')");
        $id_sal = mysqli_insert_id($db);
        
        mysqli_query($db, "INSERT INTO usuario_sala (usu_usu_sal, tip_usu_sal, id_sal6) VALUES ('$id_pro', 'Profesor', '$id_sal')");
    }
    
    $dominantes = 0;
    $totalHorarios = 0;
    foreach ($subHorsGenerados as $sh) {
        if ($sh['es_dominante']) {
            $dominantes++;
            $horarios = isset($datosEnvio['secciones'][$sh['id_seccion']]['horarios']) 
                ? $datosEnvio['secciones'][$sh['id_seccion']]['horarios'] 
                : array();
            $totalHorarios += count($horarios);
        }
    }
    
    // ✅ RESPUESTA CON INFO DE VIDEOCONFERENCIA + COSTO + HORARIOS
    echo json_encode(array(
        'ok' => true, 
        'mensaje' => 'Fusión creada: ' . $dominantes . ' dominante(s), ' . count($subHorsGenerados) . ' sub_hor(s), ' . $totalHorarios . ' horario(s)', 
        'total_subhors' => count($subHorsGenerados),
        'dominantes' => $dominantes,
        'secciones' => count($datosEnvio['secciones']),
        'horarios_creados' => $totalHorarios,
        'videoconferencia' => array(
            'url' => $url_sub_hor ? 'Configurada' : 'No configurada',
            'password' => $con_url_sub_hor ? 'Configurada' : 'No configurada'
        ),
        'costo_hora' => $cos_sub_hor !== NULL ? '$' . number_format($cos_sub_hor, 2) . ' MXN' : 'No configurado'
    ));
    exit;
}


// ========== SECCIÓN 15: OBTENER PROGRAMAS POR PLANTEL ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_programas_plantel') {
    
    $id_pla = intval($_POST['id_pla']);
    
    $sql = "
        SELECT r.id_ram, r.nom_ram, r.abr_ram
        FROM rama r
        WHERE r.id_pla1 = '$id_pla' AND r.est_ram = 'Activo'
        ORDER BY r.nom_ram ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $programas = array();
    
    while($fila = mysqli_fetch_assoc($resultado)) {
        $programas[] = $fila;
    }
    
    echo json_encode(['success' => true, 'programas' => $programas]);
    exit;
}

// ========================================================================
// SECCIÓN 16-20: VALIDACIONES DE HORARIOS
// ========================================================================

// ========== SECCIÓN 16: TOGGLE VALIDACIÓN (CREAR/ELIMINAR) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'toggle_validacion') {
    
    $id_sub_hor = intval($_POST['id_sub_hor']);
    $fecha_val = mysqli_real_escape_string($db, $_POST['fecha_val']);
    $ini_hor_val = mysqli_real_escape_string($db, $_POST['ini_hor_val']);
    $fin_hor_val = mysqli_real_escape_string($db, $_POST['fin_hor_val']);
    
    // Validar fecha formato YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_val)) {
        echo json_encode(['success' => false, 'message' => 'Formato de fecha inválido']);
        exit;
    }
    
    // Verificar que el sub_hor existe
    $sqlCheck = "SELECT id_sub_hor FROM sub_hor WHERE id_sub_hor = $id_sub_hor";
    $resCheck = mysqli_query($db, $sqlCheck);
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario no encontrado']);
        exit;
    }
    
    // Verificar si ya existe validación para este sub_hor + fecha
    $sqlExiste = "
        SELECT id_val_sub_hor 
        FROM validacion_sub_hor 
        WHERE id_sub_hor1 = $id_sub_hor AND fecha_val = '$fecha_val'
    ";
    $resExiste = mysqli_query($db, $sqlExiste);
    
    if ($resExiste && mysqli_num_rows($resExiste) > 0) {
        // EXISTE: ELIMINAR
        $fila = mysqli_fetch_assoc($resExiste);
        $id_val = $fila['id_val_sub_hor'];
        
        $sqlDelete = "DELETE FROM validacion_sub_hor WHERE id_val_sub_hor = $id_val";
        $resultado = mysqli_query($db, $sqlDelete);
        
        if ($resultado) {
            echo json_encode([
                'success' => true,
                'accion' => 'eliminado',
                'message' => 'Validación eliminada'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . mysqli_error($db)]);
        }
    } else {
        // NO EXISTE: CREAR
        $nom_eje_val = mysqli_real_escape_string($db, $nomResponsable);
        
        $sqlInsert = "
            INSERT INTO validacion_sub_hor (id_sub_hor1, fecha_val, ini_hor_val, fin_hor_val, id_eje1, nom_eje_val)
            VALUES ('$id_sub_hor', '$fecha_val', '$ini_hor_val', '$fin_hor_val', '$id_eje', '$nom_eje_val')
        ";
        $resultado = mysqli_query($db, $sqlInsert);
        
        if ($resultado) {
            echo json_encode([
                'success' => true,
                'accion' => 'creado',
                'id_val_sub_hor' => mysqli_insert_id($db),
                'message' => 'Validación registrada'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear: ' . mysqli_error($db)]);
        }
    }
    
    exit;
}

// ========== SECCIÓN 17: OBTENER VALIDACIONES POR RANGO DE FECHAS ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_validaciones') {
    
    $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio']);
    $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin']);
    
    // Filtro opcional por sub_hors específicos
    $subHorsCondicion = "";
    if (isset($_POST['sub_hors']) && !empty($_POST['sub_hors'])) {
        $subHorsLimpios = array_map('intval', $_POST['sub_hors']);
        $subHorsStr = implode(',', $subHorsLimpios);
        $subHorsCondicion = " AND v.id_sub_hor1 IN ($subHorsStr) ";
    }
    
    $sql = "
        SELECT 
            v.id_val_sub_hor,
            v.id_sub_hor1,
            v.fecha_val,
            v.ini_hor_val,
            v.fin_hor_val,
            v.id_eje1,
            v.nom_eje_val,
            v.created_at
        FROM validacion_sub_hor v
        WHERE v.fecha_val BETWEEN '$fecha_inicio' AND '$fecha_fin'
        $subHorsCondicion
        ORDER BY v.fecha_val ASC, v.id_sub_hor1 ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $validaciones = array();
    
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $validaciones[] = $fila;
        }
    }
    
    echo json_encode([
        'success' => true,
        'validaciones' => $validaciones,
        'total' => count($validaciones)
    ]);
    
    exit;
}

// ========== SECCIÓN 18: OBTENER RESUMEN DE HORAS POR RANGO ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_resumen_horas') {
    
    $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio']);
    $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin']);
    
    // Calcular días del rango
    $inicio = new DateTime($fecha_inicio);
    $fin = new DateTime($fecha_fin);
    $fin->modify('+1 day');
    
    $diasRango = array();
    $period = new DatePeriod($inicio, new DateInterval('P1D'), $fin);
    foreach ($period as $dt) {
        $diasRango[] = [
            'fecha' => $dt->format('Y-m-d'),
            'dia' => obtenerDiaSemana($dt->format('Y-m-d'))
        ];
    }
    
    // Obtener horas validadas por día
    $sqlValidadas = "
        SELECT 
            fecha_val,
            SUM(TIMESTAMPDIFF(MINUTE, ini_hor_val, fin_hor_val)) / 60 AS horas
        FROM validacion_sub_hor
        WHERE fecha_val BETWEEN '$fecha_inicio' AND '$fecha_fin'
        GROUP BY fecha_val
    ";
    $resValidadas = mysqli_query($db, $sqlValidadas);
    
    $horasValidadasPorDia = array();
    if ($resValidadas) {
        while ($fila = mysqli_fetch_assoc($resValidadas)) {
            $horasValidadasPorDia[$fila['fecha_val']] = floatval($fila['horas']);
        }
    }
    
    echo json_encode([
        'success' => true,
        'dias' => $diasRango,
        'horas_validadas' => $horasValidadasPorDia
    ]);
    
    exit;
}

// ========== SECCIÓN 19: OBTENER HORARIOS CON VALIDACIONES PARA GRID ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_horarios_grid') {
    
    $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio']);
    $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin']);
    
    // Filtros opcionales
    $plantelesCondicion = "";
    if(isset($_POST['planteles']) && !empty($_POST['planteles'])) {
        $plantelesLimpios = array_map('intval', $_POST['planteles']);
        $plantelesStr = implode(',', $plantelesLimpios);
        $plantelesCondicion = " AND p.id_pla IN ($plantelesStr) ";
    }
    
    $nivelesCondicion = "";
    if(isset($_POST['niveles']) && !empty($_POST['niveles'])) {
        $nivelesLimpios = array_map(function($n) use ($db) {
            return "'" . mysqli_real_escape_string($db, $n) . "'";
        }, $_POST['niveles']);
        $nivelesStr = implode(',', $nivelesLimpios);
        $nivelesCondicion = " AND r.gra_ram IN ($nivelesStr) ";
    }
    
    // Calcular días del rango
    $inicio = new DateTime($fecha_inicio);
    $fin = new DateTime($fecha_fin);
    $fin->modify('+1 day');
    
    $diasRango = array();
    $period = new DatePeriod($inicio, new DateInterval('P1D'), $fin);
    foreach ($period as $dt) {
        $diasRango[] = [
            'fecha' => $dt->format('Y-m-d'),
            'dia' => obtenerDiaSemana($dt->format('Y-m-d')),
            'display' => $dt->format('d/m')
        ];
    }
    
    // Query principal de sub_hors con sus horarios
    $sql = "
        SELECT 
            sh.id_sub_hor,
            p.nom_pla AS plantel,
            IFNULL(r.abr_ram, r.nom_ram) AS programa,
            IFNULL(r.gra_ram, 'N/A') AS nivel,
            m.nom_mat AS materia,
            g.nom_gru AS grupo,
            CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor,
            (SELECT COUNT(*) FROM alu_hor ah WHERE ah.id_sub_hor5 = sh.id_sub_hor AND ah.est_alu_hor = 'Activo') AS alumnos
        FROM sub_hor sh
        INNER JOIN grupo g ON g.id_gru = sh.id_gru1
        INNER JOIN ciclo c ON c.id_cic = g.id_cic1
        INNER JOIN rama r ON r.id_ram = c.id_ram1
        INNER JOIN plantel p ON p.id_pla = r.id_pla1
        INNER JOIN profesor pr ON pr.id_pro = sh.id_pro1
        INNER JOIN materia m ON m.id_mat = sh.id_mat1
        WHERE sh.id_fus2 IS NULL
        AND p.id_cad1 = '$cadena'
        AND c.ini_cic <= '$fecha_fin' AND c.fin_cic >= '$fecha_inicio'
        $plantelesCondicion
        $nivelesCondicion
        ORDER BY p.nom_pla, r.gra_ram, g.nom_gru
    ";
    
    $resultado = mysqli_query($db, $sql);
    $subHors = array();
    $idsSubHor = array();
    
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $idSh = $fila['id_sub_hor'];
            $idsSubHor[] = $idSh;
            
            // Obtener horarios de este sub_hor
            $sqlHorarios = "SELECT dia_hor, ini_hor, fin_hor FROM horario WHERE id_sub_hor1 = $idSh";
            $resHorarios = mysqli_query($db, $sqlHorarios);
            $horariosPorDia = array();
            
            while ($h = mysqli_fetch_assoc($resHorarios)) {
                $horariosPorDia[$h['dia_hor']] = [
                    'ini' => $h['ini_hor'],
                    'fin' => $h['fin_hor'],
                    'display' => substr($h['ini_hor'], 0, 5) . '-' . substr($h['fin_hor'], 0, 5)
                ];
            }
            
            $fila['horarios_semana'] = $horariosPorDia;
            $subHors[] = $fila;
        }
    }
    
    // Obtener validaciones existentes
    $validaciones = array();
    if (!empty($idsSubHor)) {
        $idsStr = implode(',', $idsSubHor);
        $sqlVal = "
            SELECT id_sub_hor1, fecha_val, ini_hor_val, fin_hor_val, nom_eje_val, created_at
            FROM validacion_sub_hor
            WHERE id_sub_hor1 IN ($idsStr)
            AND fecha_val BETWEEN '$fecha_inicio' AND '$fecha_fin'
        ";
        $resVal = mysqli_query($db, $sqlVal);
        
        while ($v = mysqli_fetch_assoc($resVal)) {
            $key = $v['id_sub_hor1'] . '_' . $v['fecha_val'];
            $validaciones[$key] = $v;
        }
    }
    
    echo json_encode([
        'success' => true,
        'dias' => $diasRango,
        'sub_hors' => $subHors,
        'validaciones' => $validaciones,
        'total_sub_hors' => count($subHors)
    ]);
    
    exit;
}

// ========== SECCIÓN 20: ESTADÍSTICAS DE VALIDACIONES ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'estadisticas_validaciones') {
    
    $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio']);
    $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin']);
    
    // Total horas validadas en el rango
    $sqlTotalValidadas = "
        SELECT 
            SUM(TIMESTAMPDIFF(MINUTE, ini_hor_val, fin_hor_val)) / 60 AS total_horas,
            COUNT(*) AS total_validaciones
        FROM validacion_sub_hor
        WHERE fecha_val BETWEEN '$fecha_inicio' AND '$fecha_fin'
    ";
    $resTotalVal = mysqli_query($db, $sqlTotalValidadas);
    $filaTotal = mysqli_fetch_assoc($resTotalVal);
    
    // Validaciones por ejecutivo
    $sqlPorEjecutivo = "
        SELECT 
            nom_eje_val,
            COUNT(*) AS validaciones,
            SUM(TIMESTAMPDIFF(MINUTE, ini_hor_val, fin_hor_val)) / 60 AS horas
        FROM validacion_sub_hor
        WHERE fecha_val BETWEEN '$fecha_inicio' AND '$fecha_fin'
        GROUP BY id_eje1, nom_eje_val
        ORDER BY validaciones DESC
    ";
    $resPorEje = mysqli_query($db, $sqlPorEjecutivo);
    $porEjecutivo = array();
    while ($fila = mysqli_fetch_assoc($resPorEje)) {
        $porEjecutivo[] = $fila;
    }
    
    // Validaciones por día
    $sqlPorDia = "
        SELECT 
            fecha_val,
            COUNT(*) AS validaciones,
            SUM(TIMESTAMPDIFF(MINUTE, ini_hor_val, fin_hor_val)) / 60 AS horas
        FROM validacion_sub_hor
        WHERE fecha_val BETWEEN '$fecha_inicio' AND '$fecha_fin'
        GROUP BY fecha_val
        ORDER BY fecha_val ASC
    ";
    $resPorDia = mysqli_query($db, $sqlPorDia);
    $porDia = array();
    while ($fila = mysqli_fetch_assoc($resPorDia)) {
        $porDia[] = $fila;
    }
    
    echo json_encode(array(
        'success' => true,
        'total_horas_validadas' => floatval(isset($filaTotal['total_horas']) ? $filaTotal['total_horas'] : 0),
        'total_validaciones'   => intval(isset($filaTotal['total_validaciones']) ? $filaTotal['total_validaciones'] : 0),
        'por_ejecutivo'        => $porEjecutivo,
        'por_dia'              => $porDia
    ));
    
    exit;
}

?>