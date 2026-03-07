<?php  
// ========================================================================
// CONTROLADOR DE HORARIOS/GRUPOS
// Archivo: server/controlador_horarios.php
// Maneja grupos NORMALES y FUSIONADOS
// INCLUYE: Filtro de PROFESORES, Edición de sub_hor, url_sub_hor
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
        return $fila['ini_hor'] . '-' . $fila['fin_hor'];
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
                sh.id_sub_hor AS id_sub_hor_horario,
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
                
                // 23 COLUMNAS (agregamos url_sub_hor después de nivel)
                $horariosTemp[] = array(
                    'id_orden' => intval($fila['id_orden']),
                    'data' => array(
                        $fila['id'],              // 0: ID (oculto)
                        $fila['plantel'],         // 1: PLANTEL
                        $fila['tipo'],            // 2: TIPO
                        $fila['ciclo'],           // 3: CICLO
                        $fila['inicio'],          // 4: INICIO
                        $fila['fin'],             // 5: FIN
                        $fila['grupo'],           // 6: GRUPO
                        $semana,                  // 7: SEMANA
                        $fila['profesor'],        // 8: PROFESOR
                        $fila['materia'],         // 9: MATERIA
                        $fila['total_planteles'], // 10: CDES
                        $fila['total_alumnos'],   // 11: ALUMNOS
                        $fila['salon'],           // 12: SALÓN
                        $fila['programa'],        // 13: PROG (abr_ram)
                        $fila['nivel'],           // 14: NIVEL (gra_ram)
                        $fila['url_sub_hor'],     // 15: URL (NUEVO)
                        $lunes,                   // 16: LUN
                        $martes,                  // 17: MAR
                        $miercoles,               // 18: MIÉ
                        $jueves,                  // 19: JUE
                        $viernes,                 // 20: VIE
                        $sabado,                  // 21: SÁB
                        $domingo                  // 22: DOM
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
                sh.id_sub_hor AS id_sub_hor_horario,
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
                
                // 23 COLUMNAS (agregamos url_sub_hor después de nivel)
                $horariosTemp[] = array(
                    'id_orden' => intval($fila['id_orden']),
                    'data' => array(
                        $fila['id'],              // 0: ID (oculto)
                        $fila['plantel'],         // 1: PLANTEL
                        $fila['tipo'],            // 2: TIPO
                        $fila['ciclo'],           // 3: CICLO
                        $fila['inicio'],          // 4: INICIO
                        $fila['fin'],             // 5: FIN
                        $fila['grupo'],           // 6: GRUPO
                        $semana,                  // 7: SEMANA
                        $fila['profesor'],        // 8: PROFESOR
                        $materiaDisplay,          // 9: MATERIA
                        $fila['total_planteles'], // 10: CDES
                        $fila['total_alumnos'],   // 11: ALUMNOS
                        $fila['salon'],           // 12: SALÓN
                        $fila['programa'],        // 13: PROG (abr_ram)
                        $fila['nivel'],           // 14: NIVEL (gra_ram)
                        $fila['url_sub_hor'],     // 15: URL (NUEVO)
                        $lunes,                   // 16: LUN
                        $martes,                  // 17: MAR
                        $miercoles,               // 18: MIÉ
                        $jueves,                  // 19: JUE
                        $viernes,                 // 20: VIE
                        $sabado,                  // 21: SÁB
                        $domingo                  // 22: DOM
                    )
                );
            }
        }
    }
    
    // ========== ORDENAR POR id_sub_hor DESC ==========
    usort($horariosTemp, function($a, $b) {
        return $b['id_orden'] - $a['id_orden'];
    });
    
    $horarios = array_map(function($item) {
        return $item['data'];
    }, $horariosTemp);
    
    $totalNormales = 0;
    $totalFusionados = 0;
    foreach ($horarios as $h) {
        if ($h[2] === 'Normal') {
            $totalNormales++;
        } else if ($h[2] === 'Fusionado') {
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
            
            // Obtener horarios
            $sqlHorarios = "SELECT * FROM horario WHERE id_sub_hor1 = $id ORDER BY FIELD(dia_hor, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
            $resHorarios = mysqli_query($db, $sqlHorarios);
            $horarios = array();
            while ($h = mysqli_fetch_assoc($resHorarios)) {
                $horarios[] = $h;
            }
            $detalle['horarios'] = $horarios;
            
            // Obtener profesores disponibles del plantel
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
            
            // Obtener salones disponibles del plantel
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
                pr.id_pro,
                CONCAT(pr.nom_pro, ' ', pr.app_pro) AS profesor,
                pr.cor_pro, pr.pas_pro,
                m.nom_mat AS materia_dominante, m.id_mat,
                r.nom_ram, r.id_ram,
                IFNULL(r.abr_ram, r.nom_ram) AS abr_ram,
                IFNULL(r.gra_ram, 'N/A') AS gra_ram,
                p.nom_pla, p.id_pla,
                sh.id_sal1,
                IFNULL(s.nom_sal, 'N/A') AS salon,
                IFNULL(sh.url_sub_hor, '') AS url_sub_hor
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
            
            // Obtener todos los sub_hors de la fusión
            $sqlSubHors = "
                SELECT 
                    sh.id_sub_hor,
                    sh.id_sub_hor_nat,
                    sh.id_pro1,
                    sh.id_sal1,
                    IFNULL(sh.url_sub_hor, '') AS url_sub_hor,
                    m.nom_mat, m.id_mat,
                    r.nom_ram,
                    IFNULL(r.abr_ram, r.nom_ram) AS abr_ram,
                    IFNULL(r.gra_ram, 'N/A') AS gra_ram,
                    p.nom_pla, p.id_pla,
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
            
            // Obtener horarios del sub_hor dominante
            $id_sub_hor_dom = $detalle['id_sub_hor_dominante'];
            $sqlHorarios = "SELECT * FROM horario WHERE id_sub_hor1 = $id_sub_hor_dom ORDER BY FIELD(dia_hor, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
            $resHorarios = mysqli_query($db, $sqlHorarios);
            $horarios = array();
            while ($h = mysqli_fetch_assoc($resHorarios)) {
                $horarios[] = $h;
            }
            $detalle['horarios'] = $horarios;
            
            // Obtener profesores disponibles del plantel dominante
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
            
            // Obtener salones disponibles del plantel dominante
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

// ========== SECCIÓN 7: ACTUALIZAR SUB_HOR (PROFESOR, SALÓN, URL) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_sub_hor') {
    
    $id_sub_hor = intval($_POST['id_sub_hor']);
    $id_pro = intval($_POST['id_pro']);
    $id_sal = isset($_POST['id_sal']) && $_POST['id_sal'] !== '' ? intval($_POST['id_sal']) : 'NULL';
    $url_sub_hor = mysqli_real_escape_string($db, trim($_POST['url_sub_hor']));
    $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
    $es_fusion = ($tipo === 'Fusionado');
    
    // Validar que el sub_hor existe
    $sqlCheck = "SELECT id_sub_hor, id_fus2, id_sub_hor_nat FROM sub_hor WHERE id_sub_hor = $id_sub_hor";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario no encontrado']);
        exit;
    }
    
    $filaCheck = mysqli_fetch_assoc($resCheck);
    
    // Si es fusión y es el dominante, actualizar también la tabla fusion.id_pro8
    $actualizarFusion = false;
    $id_fus = null;
    if ($es_fusion && $filaCheck['id_fus2'] !== null && $filaCheck['id_sub_hor_nat'] === null) {
        $actualizarFusion = true;
        $id_fus = $filaCheck['id_fus2'];
    }
    
    // Construir query de actualización
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
        // Si es fusión dominante, actualizar también la tabla fusion
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
    
    // Validar que el sub_hor existe
    $sqlCheck = "SELECT id_sub_hor FROM sub_hor WHERE id_sub_hor = $id_sub_hor";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario no encontrado']);
        exit;
    }
    
    // Eliminar horarios existentes
    $sqlDelete = "DELETE FROM horario WHERE id_sub_hor1 = $id_sub_hor";
    mysqli_query($db, $sqlDelete);
    
    // Insertar nuevos horarios
    $diasValidos = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    $insertados = 0;
    $errores = array();
    
    foreach ($horarios as $horario) {
        $dia = mysqli_real_escape_string($db, $horario['dia']);
        $ini_hor = mysqli_real_escape_string($db, $horario['ini_hor']);
        $fin_hor = mysqli_real_escape_string($db, $horario['fin_hor']);
        
        // Validar día
        if (!in_array($dia, $diasValidos)) {
            $errores[] = "Día inválido: $dia";
            continue;
        }
        
        // Validar que ambas horas estén presentes
        if (empty($ini_hor) || empty($fin_hor)) {
            continue; // Simplemente no insertar si está vacío
        }
        
        // Validar formato de hora (HH:MM o HH:MM:SS)
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

// ========== SECCIÓN 9: ACTUALIZAR SUB_HOR DE FUSIÓN (INDIVIDUAL) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_sub_hor_fusion') {
    
    $id_sub_hor = intval($_POST['id_sub_hor']);
    $id_pro = intval($_POST['id_pro']);
    $id_sal = isset($_POST['id_sal']) && $_POST['id_sal'] !== '' ? intval($_POST['id_sal']) : 'NULL';
    $url_sub_hor = mysqli_real_escape_string($db, trim($_POST['url_sub_hor']));
    
    // Validar que el sub_hor existe y pertenece a una fusión
    $sqlCheck = "SELECT id_sub_hor, id_fus2, id_sub_hor_nat FROM sub_hor WHERE id_sub_hor = $id_sub_hor AND id_fus2 IS NOT NULL";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
        echo json_encode(['success' => false, 'message' => 'Sub-horario de fusión no encontrado']);
        exit;
    }
    
    $filaCheck = mysqli_fetch_assoc($resCheck);
    $esDominante = ($filaCheck['id_sub_hor_nat'] === null);
    
    // Construir query de actualización
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
        // Si es dominante, actualizar también fusion.id_pro8
        if ($esDominante) {
            $id_fus = $filaCheck['id_fus2'];
            $sqlUpdateFusion = "UPDATE fusion SET id_pro8 = '$id_pro' WHERE id_fus = $id_fus";
            mysqli_query($db, $sqlUpdateFusion);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Sub-horario de fusión actualizado correctamente',
            'es_dominante' => $esDominante
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . mysqli_error($db)]);
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

?>