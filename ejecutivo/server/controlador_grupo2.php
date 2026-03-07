<?php  

// controlador_grupo2.php

// ⚠️ NO DEBE HABER NADA ANTES DE ESTA LÍNEA (ni espacios, ni BOM)

// 1. CAPTURAR OUTPUT DE INCLUDES
ob_start();

// 2. INCLUDES (pueden generar output basura)
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// 3. DESCARTAR TODO EL OUTPUT CAPTURADO
ob_end_clean();

// 4. AHORA SÍ ESTABLECER HEADER JSON
header('Content-Type: application/json; charset=utf-8');

// ACTUALIZAR ESTADO DE GENERACIÓN
if(isset($_POST['accion']) && $_POST['accion'] == 'actualizarEstado') {
    $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
    $est_gen = mysqli_real_escape_string($db, $_POST['est_gen']);
    
    $sql = "UPDATE generacion SET est_gen = '$est_gen' WHERE id_gen = '$id_gen'";
    $resultado = mysqli_query($db, $sql);
    
    echo json_encode([
        'sql' => $sql,
        'resultado' => $resultado,
        'error' => mysqli_error($db)
    ]);
    exit;
}

// ELIMINAR/OCULTAR REGISTRO
if(isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
    
    $sql = "UPDATE generacion SET eli_eje = 0 WHERE id_gen = '$id_gen'";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode(['resultado' => 'success']);
    } else {
        echo json_encode(['resultado' => 'error', 'mensaje' => mysqli_error($db)]);
    }
    exit;
}

// OBTENER ACCIÓN PRIMERO
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
} else {
    $accion = null;
}

// OBTENER DATOS DE UNA GENERACIÓN ESPECÍFICA (solo si NO hay acción)
if(isset($_POST['id_gen']) && !$accion) {
    $id_gen = $_POST['id_gen'];
    $sql = "SELECT * FROM generacion WHERE id_gen = $id_gen";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $datos = mysqli_fetch_assoc($resultado);
        $response = [
            'data' => $datos,
            'status' => 200,
            'message' => "Respuesta exitosa"
        ];
    } else {
        $response = [
            'status' => 500,
            'message' => "Error en la actualización",
            'query' => $sql
        ];
    }
    echo json_encode($response);
    exit;
}

// BÚSQUEDA O FILTRADO POR ESTATUS
if(isset($_POST['palabra']) || isset($_POST['estatus'])) {
    $sql = "
        SELECT *, obtener_estatus_generacion(ini_gen, fin_gen) AS estatus_generacion
        FROM generacion
        INNER JOIN rama ON rama.id_ram = generacion.id_ram5
        INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
        WHERE id_pla1 = '$plantel' 
        AND (est_gen = '1' OR est_gen = '3')
    ";

    if (isset($_POST['palabra']) && $_POST['palabra'] != '') {
        $palabra = $_POST['palabra'];
        $sql .= " AND ( ( id_gen LIKE '%$palabra%' ) OR ( UPPER( nom_gen ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci ) )";
    } else {
        $estatus = $_POST['estatus'];
        if($estatus == 'En curso'){
            $sql .= " AND ( CURDATE() >= ini_gen AND CURDATE() <= fin_gen )";
        } else if($estatus == 'Fin curso'){
            $sql .= " AND ( CURDATE() > fin_gen )";
        } else if($estatus == 'Por comenzar'){
            $sql .= " AND ( CURDATE() < ini_gen )";
        }
    }
    $sql .= " ORDER BY id_gen DESC";

    $resultado = mysqli_query($db, $sql);
    $alumnos = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $id_gen = $fila['id_gen'];
        
        // Calcular totales
        $sql_alumnos = "SELECT obtener_total_alumnos_generacion($id_gen) AS total";
        $total_alumnos = obtener_datos_consulta($db, $sql_alumnos)['datos']['total'];
        
        $sql_deudores = "SELECT obtener_deudores_alumnos_generacion($id_gen) AS total";
        $total_deudores = obtener_datos_consulta($db, $sql_deudores)['datos']['total'];
        
        // Calcular potencial y adeudo
        $sqlPotencial = "SELECT SUM(mon_ori_pag) AS potencial FROM vista_pagos WHERE (id_gen1 = '$id_gen') AND (estatus_general != 'Baja definitiva' AND estatus_general != 'Suspendido')";
        $resultadoPotencial = mysqli_query($db, $sqlPotencial);
        $potencial = mysqli_fetch_assoc($resultadoPotencial)['potencial'];
        
        $sqlAdeudo = "SELECT SUM(mon_pag) AS adeudo FROM vista_pagos WHERE (id_gen1 = '$id_gen') AND (estatus_general != 'Baja definitiva' AND estatus_general != 'Suspendido')";
        $resultadoAdeudo = mysqli_query($db, $sqlAdeudo);
        $adeudo = mysqli_fetch_assoc($resultadoAdeudo)['adeudo'];
        
        $cobrado = round($potencial - $adeudo, 2);
        $porcentaje = ($potencial == 0) ? "0%" : round(($cobrado / $potencial) * 100, 2) . "%";
        
        $alumno = [
            "ID" => '<a class="btn-link text-primary" target="_blank" href="alumnos.php?id_gen=' . $fila['id_gen'] . '">' . $fila['id_gen'] . '</a>',
            "GPO" => $fila['nom_gen'],
            "FECHA INICIO" => fechaFormateadaCompacta($fila['ini_gen']),
            "FECHA FIN" => fechaFormateadaCompacta($fila['fin_gen']),
            "PROGRAMA" => $fila['nom_ram'],
            "T_ALUMNOS" => $total_alumnos,
            "DEUDORES" => $total_deudores,
            "COBRADO" => formatearDinero($cobrado),
            "POTENCIAL" => formatearDinero($potencial),
            "POR COBRAR" => formatearDinero($adeudo),
            "PORCENTAJE" => $porcentaje,
            "TRÁMITE" => "######",
            "ESTADO" => $fila['estatus_generacion'],
            "ID." => $fila['id_gen'],
            "DÍAS" => $fila['dia_gen'],
            "HORARIO" => $fila['hor_gen'],
            "META" => $fila['met_gen'],
        ];
        $alumnos[] = $alumno;
    }
    
    echo json_encode($alumnos);
    exit;
}

// OBTENER ACCIÓN Y PROCESAR
if ($accion) {

// ============================================================================
// 🔥 ALTA COMPLETA DE MÚLTIPLES GRUPOS (NUEVO)
// ============================================================================
if ($accion == "AltaCompleta") {
    error_log("🔥 INICIANDO AltaCompleta");
    error_log("🔥 POST recibido: " . print_r($_POST, true));
    
    try {
        // VALIDAR Y OBTENER DATOS
        $planteles = json_decode($_POST['planteles'], true);
        
        if (!is_array($planteles) || empty($planteles)) {
            throw new Exception('Debe seleccionar al menos un plantel');
        }
        
        $nom_gen = mysqli_real_escape_string($db, $_POST['nom_gen']);
        $ini_gen = mysqli_real_escape_string($db, $_POST['ini_gen']);
        $fin_gen = mysqli_real_escape_string($db, $_POST['fin_gen']);
        $met_gen = mysqli_real_escape_string($db, $_POST['met_gen']);
        $dia_gen = mysqli_real_escape_string($db, $_POST['dia_gen']);
        $hor_gen = mysqli_real_escape_string($db, $_POST['hor_gen']);
        $mod_gen = mysqli_real_escape_string($db, $_POST['mod_gen']);
        $id_ram = mysqli_real_escape_string($db, $_POST['id_ram']);
        
        // COSTOS
        $mon_ins_gen = floatval($_POST['mon_ins_gen']);
        $mon_col_gen = floatval($_POST['mon_col_gen']);
        $mon_tra_gen = floatval($_POST['mon_tra_gen']);
        $mon_rei_gen = floatval($_POST['mon_rei_gen']);
        
        // ARRAYS JSON
        $tramites = json_decode($_POST['tramites'], true);
        $reinscripciones = json_decode($_POST['reinscripciones'], true);
        $eventos = json_decode($_POST['eventos'], true);
        
        if (!is_array($tramites)) $tramites = [];
        if (!is_array($reinscripciones)) $reinscripciones = [];
        if (!is_array($eventos)) $eventos = [];
        
        error_log("🔥 PLANTELES: " . count($planteles));
        error_log("🔥 TRAMITES: " . count($tramites));
        error_log("🔥 REINSCRIPCIONES: " . count($reinscripciones));
        error_log("🔥 EVENTOS: " . count($eventos));
        
        // INICIAR TRANSACCIÓN
        mysqli_begin_transaction($db);
        
        $gruposCreados = [];
        $totalTramites = 0;
        $totalReinscripciones = 0;
        $totalEventos = 0;
        
        // CREAR UN GRUPO POR CADA PLANTEL
        foreach ($planteles as $id_pla) {
            $id_pla = mysqli_real_escape_string($db, $id_pla);
            
            error_log("🔥 CREANDO GRUPO PARA PLANTEL: $id_pla");
            
            // 1️⃣ INSERTAR GENERACIÓN
            $sqlGen = "INSERT INTO generacion (
                nom_gen, ini_gen, fin_gen, met_gen, dia_gen, hor_gen, mod_gen, id_ram5,
                mon_ins_gen, mon_col_gen, mon_tra_gen, mon_rei_gen
            ) VALUES (
                '$nom_gen', '$ini_gen', '$fin_gen', '$met_gen', '$dia_gen', '$hor_gen', '$mod_gen', '$id_ram',
                '$mon_ins_gen', '$mon_col_gen', '$mon_tra_gen', '$mon_rei_gen'
            )";
            
            if (!mysqli_query($db, $sqlGen)) {
                throw new Exception('Error al crear generación en plantel ' . $id_pla . ': ' . mysqli_error($db));
            }
            
            $id_gen = mysqli_insert_id($db);
            $gruposCreados[] = $id_gen;
            
            error_log("🔥 GENERACIÓN CREADA: $id_gen");
            
            // 2️⃣ INSERTAR TRÁMITES
            foreach ($tramites as $t) {
                $concepto = mysqli_real_escape_string($db, $t['concepto']);
                $monto = floatval($t['monto']);
                $fecha = mysqli_real_escape_string($db, $t['fecha']);
                
                $sqlTra = "INSERT INTO grupo_pago (
                    id_gen15, tip_gru_pag, tip_pag_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag
                ) VALUES (
                    '$id_gen', 'Pago', 'Otros', '$concepto', '$monto', '$fecha', '$fecha'
                )";
                
                if (!mysqli_query($db, $sqlTra)) {
                    throw new Exception('Error al insertar trámite: ' . mysqli_error($db));
                }
                $totalTramites++;
            }
            
            // 3️⃣ INSERTAR REINSCRIPCIONES
            foreach ($reinscripciones as $r) {
                $concepto = mysqli_real_escape_string($db, $r['concepto']);
                $monto = floatval($r['monto']);
                $fecha = mysqli_real_escape_string($db, $r['fecha']);
                
                $sqlRei = "INSERT INTO grupo_pago (
                    id_gen15, tip_gru_pag, tip_pag_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag
                ) VALUES (
                    '$id_gen', 'Pago', 'Reinscripción', '$concepto', '$monto', '$fecha', '$fecha'
                )";
                
                if (!mysqli_query($db, $sqlRei)) {
                    throw new Exception('Error al insertar reinscripción: ' . mysqli_error($db));
                }
                $totalReinscripciones++;
            }
            
            // 4️⃣ INSERTAR EVENTOS
            foreach ($eventos as $e) {
                $concepto = mysqli_real_escape_string($db, $e['concepto']);
                $semana = mysqli_real_escape_string($db, $e['semana']);
                $descripcion = mysqli_real_escape_string($db, $e['descripcion']);
                $fecha = mysqli_real_escape_string($db, $e['fecha']);
                $validacion = isset($e['validacion']) ? mysqli_real_escape_string($db, $e['validacion']) : 'Pendiente';
                
                $sqlEve = "INSERT INTO grupo_pago (
                    id_gen15, tip_gru_pag, con_gru_pag, sem_gru_pag, des_gru_pag, ini_gru_pag, fin_gru_pag, val_gru_pag
                ) VALUES (
                    '$id_gen', 'Fecha', '$concepto', '$semana', '$descripcion', '$fecha', '$fecha', '$validacion'
                )";
                
                if (!mysqli_query($db, $sqlEve)) {
                    throw new Exception('Error al insertar evento: ' . mysqli_error($db));
                }
                $totalEventos++;
            }
        }
        
        // COMMIT TRANSACCIÓN
        mysqli_commit($db);
        
        error_log("🔥 TRANSACCIÓN EXITOSA");
        error_log("🔥 GRUPOS CREADOS: " . count($gruposCreados));
        error_log("🔥 TOTAL TRÁMITES: $totalTramites");
        error_log("🔥 TOTAL REINSCRIPCIONES: $totalReinscripciones");
        error_log("🔥 TOTAL EVENTOS: $totalEventos");
        
        // CONSTRUIR MENSAJE DINÁMICO
        $mensaje = count($gruposCreados) . ' grupo(s) creado(s) correctamente';
        
        $detalles = [];
        if ($totalTramites > 0) {
            $detalles[] = $totalTramites . ' trámite(s)';
        }
        if ($totalReinscripciones > 0) {
            $detalles[] = $totalReinscripciones . ' reinscripción(es)';
        }
        if ($totalEventos > 0) {
            $detalles[] = $totalEventos . ' evento(s)';
        }
        
        if (!empty($detalles)) {
            $mensaje .= ' con ' . implode(', ', $detalles);
        }
        
        echo json_encode([
            'resultado' => 'success',
            'mensaje' => $mensaje,
            'ids' => $gruposCreados,
            'total_grupos' => count($gruposCreados),
            'total_tramites' => $totalTramites,
            'total_reinscripciones' => $totalReinscripciones,
            'total_eventos' => $totalEventos
        ]);
        
    } catch (Exception $e) {
        // ROLLBACK EN CASO DE ERROR
        mysqli_rollback($db);
        error_log('🔥 ERROR: ' . $e->getMessage());
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// ============================================================================
// 🔥 ALTA COMPLETA MULTI-PROGRAMA (NUEVO)
// ============================================================================
if ($accion == "AltaCompletaMultiPrograma") {
    error_log("🔥 INICIANDO AltaCompletaMultiPrograma");
    error_log("🔥 POST recibido: " . print_r($_POST, true));
    
    try {
        // VALIDAR Y OBTENER DATOS
        $grupos = json_decode($_POST['grupos'], true);
        
        if (!is_array($grupos) || empty($grupos)) {
            throw new Exception('Debe seleccionar al menos una combinación plantel-programa');
        }
        
        $nom_gen = mysqli_real_escape_string($db, $_POST['nom_gen']);
        $ini_gen = mysqli_real_escape_string($db, $_POST['ini_gen']);
        $fin_gen = mysqli_real_escape_string($db, $_POST['fin_gen']);
        $met_gen = mysqli_real_escape_string($db, $_POST['met_gen']);
        $dia_gen = mysqli_real_escape_string($db, $_POST['dia_gen']);
        $hor_gen = mysqli_real_escape_string($db, $_POST['hor_gen']);
        $mod_gen = mysqli_real_escape_string($db, $_POST['mod_gen']);
        
        // COSTOS
        $mon_ins_gen = floatval($_POST['mon_ins_gen']);
        $mon_col_gen = floatval($_POST['mon_col_gen']);
        $mon_tra_gen = floatval($_POST['mon_tra_gen']);
        $mon_rei_gen = floatval($_POST['mon_rei_gen']);
        
        // ARRAYS JSON
        $tramites = json_decode($_POST['tramites'], true);
        $reinscripciones = json_decode($_POST['reinscripciones'], true);
        $eventos = json_decode($_POST['eventos'], true);
        
        if (!is_array($tramites)) $tramites = [];
        if (!is_array($reinscripciones)) $reinscripciones = [];
        if (!is_array($eventos)) $eventos = [];
        
        error_log("🔥 GRUPOS: " . count($grupos));
        
        // INICIAR TRANSACCIÓN
        mysqli_begin_transaction($db);
        
        $gruposCreados = [];
        $totalTramites = 0;
        $totalReinscripciones = 0;
        $totalEventos = 0;
        
        // CREAR GRUPOS
        foreach ($grupos as $grupo) {
            $id_pla = mysqli_real_escape_string($db, $grupo['id_pla']);
            $id_ram = mysqli_real_escape_string($db, $grupo['id_ram']);
            
            error_log("🔥 CREANDO GRUPO PARA PLANTEL: $id_pla, PROGRAMA: $id_ram");
            
            // 1️⃣ INSERTAR GENERACIÓN
            $sqlGen = "INSERT INTO generacion (
                nom_gen, ini_gen, fin_gen, met_gen, dia_gen, hor_gen, mod_gen, id_ram5,
                mon_ins_gen, mon_col_gen, mon_tra_gen, mon_rei_gen
            ) VALUES (
                '$nom_gen', '$ini_gen', '$fin_gen', '$met_gen', '$dia_gen', '$hor_gen', '$mod_gen', '$id_ram',
                '$mon_ins_gen', '$mon_col_gen', '$mon_tra_gen', '$mon_rei_gen'
            )";
            
            if (!mysqli_query($db, $sqlGen)) {
                throw new Exception('Error al crear generación: ' . mysqli_error($db));
            }
            
            $id_gen = mysqli_insert_id($db);
            $gruposCreados[] = $id_gen;
            
            error_log("🔥 GENERACIÓN CREADA: $id_gen");
            
            // 2️⃣ INSERTAR TRÁMITES
            foreach ($tramites as $t) {
                $concepto = mysqli_real_escape_string($db, $t['concepto']);
                $monto = floatval($t['monto']);
                $fecha = mysqli_real_escape_string($db, $t['fecha']);
                
                $sqlTra = "INSERT INTO grupo_pago (
                    id_gen15, tip_gru_pag, tip_pag_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag
                ) VALUES (
                    '$id_gen', 'Pago', 'Otros', '$concepto', '$monto', '$fecha', '$fecha'
                )";
                
                if (!mysqli_query($db, $sqlTra)) {
                    throw new Exception('Error al insertar trámite: ' . mysqli_error($db));
                }
                $totalTramites++;
            }
            
            // 3️⃣ INSERTAR REINSCRIPCIONES
            foreach ($reinscripciones as $r) {
                $concepto = mysqli_real_escape_string($db, $r['concepto']);
                $monto = floatval($r['monto']);
                $fecha = mysqli_real_escape_string($db, $r['fecha']);
                
                $sqlRei = "INSERT INTO grupo_pago (
                    id_gen15, tip_gru_pag, tip_pag_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag
                ) VALUES (
                    '$id_gen', 'Pago', 'Reinscripción', '$concepto', '$monto', '$fecha', '$fecha'
                )";
                
                if (!mysqli_query($db, $sqlRei)) {
                    throw new Exception('Error al insertar reinscripción: ' . mysqli_error($db));
                }
                $totalReinscripciones++;
            }
            
            // 4️⃣ INSERTAR EVENTOS
            foreach ($eventos as $e) {
                $concepto = mysqli_real_escape_string($db, $e['concepto']);
                $semana = mysqli_real_escape_string($db, $e['semana']);
                $descripcion = mysqli_real_escape_string($db, $e['descripcion']);
                $fecha = mysqli_real_escape_string($db, $e['fecha']);
                $validacion = isset($e['validacion']) ? mysqli_real_escape_string($db, $e['validacion']) : 'Pendiente';
                
                $sqlEve = "INSERT INTO grupo_pago (
                    id_gen15, tip_gru_pag, con_gru_pag, sem_gru_pag, des_gru_pag, ini_gru_pag, fin_gru_pag, val_gru_pag
                ) VALUES (
                    '$id_gen', 'Fecha', '$concepto', '$semana', '$descripcion', '$fecha', '$fecha', '$validacion'
                )";
                
                if (!mysqli_query($db, $sqlEve)) {
                    throw new Exception('Error al insertar evento: ' . mysqli_error($db));
                }
                $totalEventos++;
            }
        }
        
        // COMMIT TRANSACCIÓN
        mysqli_commit($db);
        
        error_log("🔥 TRANSACCIÓN EXITOSA");
        
        // CONSTRUIR MENSAJE
        $mensaje = count($gruposCreados) . ' grupo(s) creado(s) correctamente';
        
        $detalles = [];
        if ($totalTramites > 0) {
            $detalles[] = $totalTramites . ' trámite(s)';
        }
        if ($totalReinscripciones > 0) {
            $detalles[] = $totalReinscripciones . ' reinscripción(es)';
        }
        if ($totalEventos > 0) {
            $detalles[] = $totalEventos . ' evento(s)';
        }
        
        if (!empty($detalles)) {
            $mensaje .= ' con ' . implode(', ', $detalles);
        }
        
        echo json_encode([
            'resultado' => 'success',
            'mensaje' => $mensaje,
            'ids' => $gruposCreados,
            'total_grupos' => count($gruposCreados)
        ]);
        
    } catch (Exception $e) {
        mysqli_rollback($db);
        error_log('🔥 ERROR: ' . $e->getMessage());
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// ============================================================================
// ALTA DE NUEVA GENERACIÓN (INDIVIDUAL - LEGACY)
// ============================================================================
if ($accion == "Alta") {
    $nom_gen = $_POST['nom_gen'];
    $ini_gen = $_POST['ini_gen'];
    $fin_gen = $_POST['fin_gen'];
    $id_ram5 = $_POST['id_ram'];
    $dia_gen = $_POST['dia_gen'];
    $hor_gen = $_POST['hor_gen'];
    $met_gen = $_POST['met_gen'];
    $cla_gen = isset($_POST['cla_gen']) ? $_POST['cla_gen'] : '';

    // Obtener monto programa
    $sqlPrograma = "SELECT * FROM rama WHERE id_ram = $id_ram5";
    $resultadoPrograma = obtener_datos_consulta($db, $sqlPrograma);
    $cos_ram = $resultadoPrograma['datos']['cos_ram'];
    $mon_gen = $cos_ram;

    $sql = "INSERT INTO generacion (nom_gen, ini_gen, fin_gen, id_ram5, mon_gen, dia_gen, hor_gen, met_gen, cla_gen) VALUES ('$nom_gen', '$ini_gen', '$fin_gen', '$id_ram5', '$mon_gen', '$dia_gen', '$hor_gen', '$met_gen', '$cla_gen')";
    $resultado = mysqli_query($db, $sql);

    if (!$resultado) {
        echo json_encode(['error' => mysqli_error($db), 'sql' => $sql]);
        exit;
    }

    $id_gen = mysqli_insert_id($db);

    // Procesar grupo_pago si existe
    if(isset($_POST['con_gru_pag']) && is_array($_POST['con_gru_pag']) && !empty($_POST['con_gru_pag'])) {
        $conceptos = $_POST['con_gru_pag'];
        $tipos = $_POST['tip_gru_pag'];
        $fechas = $_POST['ini_gru_pag'];
        $montos = isset($_POST['mon_gru_pag']) ? $_POST['mon_gru_pag'] : array();
        
        for($i = 0; $i < count($conceptos); $i++) {
            if(!empty($conceptos[$i]) && !empty($fechas[$i]) && !empty($tipos[$i])) {
                $monto = ($tipos[$i] === 'Pago' && isset($montos[$i])) ? $montos[$i] : 0;
                
                $sqlPago = "INSERT INTO grupo_pago (id_gen15, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag, tip_gru_pag) VALUES ('$id_gen', '{$conceptos[$i]}', '{$monto}', '{$fechas[$i]}', '{$fechas[$i]}', '{$tipos[$i]}')";
                mysqli_query($db, $sqlPago);

                if (function_exists('agregar_grupo_pago')) {
                    agregar_grupo_pago($id_gen, $db);
                }
                
                if(mysqli_error($db)) {
                    echo json_encode(['error' => 'Error al insertar pago: ' . mysqli_error($db), 'sql' => $sqlPago]);
                    exit;
                }
            }
        }
    }

    // Obtener datos finales
    $sqlDatos = "SELECT generacion.*, rama.nom_ram FROM generacion INNER JOIN rama ON rama.id_ram = generacion.id_ram5 WHERE id_gen = '$id_gen'";
    $resultadoDatos = obtener_datos_consulta($db, $sqlDatos);
    $datos = $resultadoDatos['datos'];

    echo json_encode([
        'id_gen' => $datos['id_gen'],
        'nom_gen' => $datos['nom_gen'],
        'cla_gen' => $datos['cla_gen'],
        'ini_gen' => $datos['ini_gen'],
        'fin_gen' => $datos['fin_gen'],
        'nom_ram' => $datos['nom_ram'],
        'dia_gen' => $datos['dia_gen'],
        'hor_gen' => $datos['hor_gen'],
        'met_gen' => $datos['met_gen'],
        'sql' => $sql
    ]);
    exit;
}

// CAMBIO/EDICIÓN DE GENERACIÓN
if ($accion == "Cambio") {
    $campo = mysqli_real_escape_string($db, $_POST['campo']);
    $valor = mysqli_real_escape_string($db, $_POST['valor']);
    $id_gen = mysqli_real_escape_string($db, $_POST['id_gen_aux']);

    $campos_permitidos = ['nom_gen', 'cla_gen', 'mod_gen', 'dia_gen', 'ini_gen', 'fin_gen', 'hor_gen', 'mon_ins_gen', 'mon_col_gen', 'mon_tra_gen', 'mon_rei_gen', 'met_gen'];
    
    if (!in_array($campo, $campos_permitidos)) {
        echo json_encode([
            'resultado' => 'error',
            'mensaje' => 'Campo no permitido para edición: ' . $campo
        ]);
        exit;
    }

    // Manejo especial para fechas
    if (in_array($campo, ['ini_gen', 'fin_gen'])) {
        if ($valor == '') {
            $sql = "UPDATE generacion SET $campo = NULL WHERE id_gen = '$id_gen'";
        } else {
            $fecha = $valor;
            $partesFecha = explode('/', $fecha);
            if (count($partesFecha) == 3) {
                $fechaFormatoMySQL = implode('-', array_reverse($partesFecha));
            } else {
                $fechaFormatoMySQL = $fecha;
            }
            $sql = "UPDATE generacion SET $campo = '$fechaFormatoMySQL' WHERE id_gen = '$id_gen'";
        }
    } else {
        $sql = "UPDATE generacion SET $campo = '$valor' WHERE id_gen = '$id_gen'";
    }

    $resultado = mysqli_query($db, $sql);

    if (!$resultado) {
        echo json_encode([
            'resultado' => 'error', 
            'mensaje' => mysqli_error($db),
            'sql' => $sql,
            'campo' => $campo,
            'valor' => $valor,
            'id_gen' => $id_gen
        ]);
        exit;
    }

    // Verificar si el registro debe eliminarse
    $sqlDatos = "SELECT * FROM generacion WHERE id_gen = '$id_gen'";
    $resultadoDatos = mysqli_query($db, $sqlDatos);

    if ($resultadoDatos) {
        $datos = mysqli_fetch_assoc($resultadoDatos);
        
        if (empty($datos['nom_gen']) && empty($datos['ini_gen']) && empty($datos['fin_gen']) && empty($datos['dia_gen']) && empty($datos['hor_gen'])) {
            $sqlEliminar = "DELETE FROM generacion WHERE id_gen = '$id_gen'";
            $resultadoEliminar = mysqli_query($db, $sqlEliminar);
            if (!$resultadoEliminar) {
                echo json_encode([
                    'resultado' => 'error', 
                    'mensaje' => mysqli_error($db),
                    'sql' => $sql,
                    'sqlEliminar' => $sqlEliminar
                ]);
            } else {
                echo json_encode([
                    'resultado' => 'deleted',
                    'sql' => $sql,
                    'sqlEliminar' => $sqlEliminar
                ]);
            }
        } else {
            echo json_encode([
                'resultado' => 'success',
                'sql' => $sql,
                'campo' => $campo,
                'valor' => $valor,
                'datos' => $datos
            ]);
        }
    } else {
        echo json_encode([
            'resultado' => 'error', 
            'mensaje' => mysqli_error($db),
            'sql' => $sql
        ]);
    }
    exit;
}

// OBTENER FECHAS GRUPO PAGO (LEGACY)
if ($accion == "obtenerFechasGrupoPago") {
    $id_gen15 = mysqli_real_escape_string($db, $_POST['id_gen15']);
    
    $sqlGrupo = "SELECT nom_gen FROM generacion WHERE id_gen = '$id_gen15'";
    $resultadoGrupo = mysqli_query($db, $sqlGrupo);
    $nombreGrupo = mysqli_fetch_assoc($resultadoGrupo)['nom_gen'];
    
    $sqlPagos = "SELECT id_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag FROM grupo_pago WHERE id_gen15 = '$id_gen15' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros' ORDER BY id_gru_pag ASC";
    $resultadoPagos = mysqli_query($db, $sqlPagos);
    
    if (!$resultadoPagos) {
        echo json_encode(['resultado' => 'error', 'error' => 'Error al obtener pagos: ' . mysqli_error($db)]);
        exit;
    }
    
    $pagos = array();
    while ($fila = mysqli_fetch_assoc($resultadoPagos)) {
        $pagos[] = $fila;
    }
    
    echo json_encode(['resultado' => 'success', 'nom_gen' => $nombreGrupo, 'pagos' => $pagos]);
    exit;
}

// ACTUALIZAR FECHAS GRUPO PAGO (LEGACY)
if ($accion == "actualizarFechasGrupoPago") {
    try {
        $id_gen = mysqli_real_escape_string($db, $_POST['id_gen_edicion']);
        $idsActivos = json_decode($_POST['ids_activos'], true);
        
        if (!empty($idsActivos)) {
            $idsString = "'" . implode("','", array_map(function($id) use ($db) {
                return mysqli_real_escape_string($db, $id);
            }, $idsActivos)) . "'";
            $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros' AND id_gru_pag NOT IN ($idsString)";
        } else {
            $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros'";
        }
        mysqli_query($db, $sqlDelete);
        
        if (isset($_POST['ini_gru_pag']) && is_array($_POST['ini_gru_pag'])) {
            foreach ($_POST['ini_gru_pag'] as $i => $fecha) {
                if (empty($fecha)) continue;
                
                $fecha = mysqli_real_escape_string($db, $fecha);
                $concepto = mysqli_real_escape_string($db, $_POST['con_gru_pag'][$i]);
                $tipo = mysqli_real_escape_string($db, $_POST['tip_gru_pag'][$i]);
                $monto = ($tipo === 'Pago' && isset($_POST['mon_gru_pag'][$i])) ? floatval($_POST['mon_gru_pag'][$i]) : 0;
                
                if (!empty($_POST['id_gru_pag'][$i])) {
                    $id = mysqli_real_escape_string($db, $_POST['id_gru_pag'][$i]);
                    $sql = "UPDATE grupo_pago SET con_gru_pag = '$concepto', mon_gru_pag = '$monto', ini_gru_pag = '$fecha', fin_gru_pag = '$fecha' WHERE id_gru_pag = '$id' AND id_gen15 = '$id_gen'";
                } else {
                    $sql = "INSERT INTO grupo_pago (id_gen15, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag, tip_gru_pag, tip_pag_gru_pag) VALUES ('$id_gen', '$concepto', '$monto', '$fecha', '$fecha', 'Pago', 'Otros')";
                }
                
                if (!mysqli_query($db, $sql)) {
                    throw new Exception(mysqli_error($db));
                }
            }
        }

        if (function_exists('agregar_grupo_pago')) {
            agregar_grupo_pago($id_gen, $db);
        }
        
        echo json_encode(['resultado' => 'success', 'mensaje' => 'Actualización completada']);
        
    } catch (Exception $e) {
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// 🔥 GUARDAR PAGOS DE TRÁMITES
if ($accion == "guardarPagosTramites") {
    error_log("🔥 INICIANDO guardarPagosTramites");
    error_log("🔥 POST recibido: " . print_r($_POST, true));
    
    try {
        $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
        $tramites = json_decode($_POST['tramites'], true);
        
        error_log("🔥 ID_GEN: " . $id_gen);
        error_log("🔥 TRAMITES DECODIFICADOS: " . print_r($tramites, true));
        
        if (!is_array($tramites)) {
            throw new Exception('Datos de trámites inválidos');
        }

        if (empty($tramites)) {
            error_log("🔥 NO HAY TRÁMITES - SOLO LIMPIANDO TABLA");
            
            $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros'";
            error_log("🔥 QUERY DELETE (VACÍO): " . $sqlDelete);
            
            $resultDelete = mysqli_query($db, $sqlDelete);
            if (!$resultDelete) {
                throw new Exception('Error al limpiar: ' . mysqli_error($db));
            }
            
            echo json_encode([
                'resultado' => 'success',
                'mensaje' => 'Trámites eliminados correctamente',
                'total' => 0,
                'procesados' => 0
            ]);
            exit;
        }
        
        // 1. LIMPIAR registros anteriores
        $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros'";
        error_log("🔥 QUERY DELETE: " . $sqlDelete);
        
        $resultDelete = mysqli_query($db, $sqlDelete);
        if (!$resultDelete) {
            throw new Exception('Error al limpiar: ' . mysqli_error($db));
        }
        error_log("🔥 REGISTROS ELIMINADOS: " . mysqli_affected_rows($db));
        
        // 2. INSERTAR nuevos registros
        foreach ($tramites as $index => $tramite) {
            $concepto = mysqli_real_escape_string($db, $tramite['concepto']);
            $monto = floatval($tramite['monto']);
            $fecha = mysqli_real_escape_string($db, $tramite['fecha']);
            
            $sqlInsert = "INSERT INTO grupo_pago (id_gen15, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag, tip_gru_pag, tip_pag_gru_pag) VALUES ('$id_gen', '$concepto', '$monto', '$fecha', '$fecha', 'Pago', 'Otros')";
            error_log("🔥 QUERY INSERT $index: " . $sqlInsert);
            
            $resultInsert = mysqli_query($db, $sqlInsert);
            if (!$resultInsert) {
                throw new Exception('Error al insertar trámite ' . $index . ': ' . mysqli_error($db));
            }
            error_log("🔥 INSERTADO TRÁMITE $index - ID: " . mysqli_insert_id($db));
        }
        
        // 3. CONTAR registros finales
        $sqlCount = "SELECT COUNT(*) as total FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros'";
        $resultCount = mysqli_query($db, $sqlCount);
        $totalFinal = mysqli_fetch_assoc($resultCount)['total'];
        error_log("🔥 TOTAL FINAL: " . $totalFinal);
        
        echo json_encode([
            'resultado' => 'success',
            'mensaje' => 'Trámites guardados correctamente',
            'total' => $totalFinal,
            'procesados' => count($tramites)
        ]);
        
    } catch (Exception $e) {
        error_log("🔥 ERROR: " . $e->getMessage());
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// 🔥 GUARDAR PAGOS DE REINSCRIPCIONES
if ($accion == "guardarPagosReinscripciones") {
    error_log("🔥 INICIANDO guardarPagosReinscripciones");
    error_log("🔥 POST recibido: " . print_r($_POST, true));
    
    try {
        $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
        $reinscripciones = json_decode($_POST['reinscripciones'], true);
        
        error_log("🔥 ID_GEN: " . $id_gen);
        error_log("🔥 REINSCRIPCIONES DECODIFICADAS: " . print_r($reinscripciones, true));
        
        if (!is_array($reinscripciones)) {
            throw new Exception('Datos de reinscripciones inválidos');
        }

        if (empty($reinscripciones)) {
            error_log("🔥 NO HAY REINSCRIPCIONES - SOLO LIMPIANDO TABLA");
            
            $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Reinscripción'";
            error_log("🔥 QUERY DELETE (VACÍO): " . $sqlDelete);
            
            $resultDelete = mysqli_query($db, $sqlDelete);
            if (!$resultDelete) {
                throw new Exception('Error al limpiar: ' . mysqli_error($db));
            }
            
            echo json_encode([
                'resultado' => 'success',
                'mensaje' => 'Reinscripciones eliminadas correctamente',
                'total' => 0,
                'procesados' => 0
            ]);
            exit;
        }
        
        // 1. LIMPIAR registros anteriores
        $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Reinscripción'";
        error_log("🔥 QUERY DELETE: " . $sqlDelete);
        
        $resultDelete = mysqli_query($db, $sqlDelete);
        if (!$resultDelete) {
            throw new Exception('Error al limpiar: ' . mysqli_error($db));
        }
        error_log("🔥 REGISTROS ELIMINADOS: " . mysqli_affected_rows($db));
        
        // 2. INSERTAR nuevos registros
        foreach ($reinscripciones as $index => $reinscripcion) {
            $concepto = mysqli_real_escape_string($db, $reinscripcion['concepto']);
            $monto = floatval($reinscripcion['monto']);
            $fecha = mysqli_real_escape_string($db, $reinscripcion['fecha']);
            
            $sqlInsert = "INSERT INTO grupo_pago (id_gen15, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag, tip_gru_pag, tip_pag_gru_pag) VALUES ('$id_gen', '$concepto', '$monto', '$fecha', '$fecha', 'Pago', 'Reinscripción')";
            error_log("🔥 QUERY INSERT $index: " . $sqlInsert);
            
            $resultInsert = mysqli_query($db, $sqlInsert);
            if (!$resultInsert) {
                throw new Exception('Error al insertar reinscripción ' . $index . ': ' . mysqli_error($db));
            }
            error_log("🔥 INSERTADA REINSCRIPCIÓN $index - ID: " . mysqli_insert_id($db));
        }
        
        // 3. CONTAR registros finales
        $sqlCount = "SELECT COUNT(*) as total FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Reinscripción'";
        $resultCount = mysqli_query($db, $sqlCount);
        $totalFinal = mysqli_fetch_assoc($resultCount)['total'];
        error_log("🔥 TOTAL FINAL: " . $totalFinal);
        
        echo json_encode([
            'resultado' => 'success',
            'mensaje' => 'Reinscripciones guardadas correctamente',
            'total' => $totalFinal,
            'procesados' => count($reinscripciones)
        ]);
        
    } catch (Exception $e) {
        error_log("🔥 ERROR: " . $e->getMessage());
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// 🔥 GUARDAR EVENTOS DE GENERACIÓN
if ($accion == "guardarEventosGeneracion") {
    error_log("🔥 INICIANDO guardarEventosGeneracion");
    error_log("🔥 POST recibido: " . print_r($_POST, true));
    
    try {
        $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
        $eventos = json_decode($_POST['eventos'], true);
        
        error_log("🔥 ID_GEN: " . $id_gen);
        error_log("🔥 EVENTOS DECODIFICADOS: " . print_r($eventos, true));
        
        if (!is_array($eventos)) {
            throw new Exception('Datos de eventos inválidos');
        }

        if (empty($eventos)) {
            error_log("🔥 NO HAY EVENTOS - SOLO LIMPIANDO TABLA");
            
            $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Fecha'";
            error_log("🔥 QUERY DELETE (VACÍO): " . $sqlDelete);
            
            $resultDelete = mysqli_query($db, $sqlDelete);
            if (!$resultDelete) {
                throw new Exception('Error al limpiar: ' . mysqli_error($db));
            }
            
            echo json_encode([
                'resultado' => 'success',
                'mensaje' => 'Eventos eliminados correctamente',
                'total' => 0,
                'procesados' => 0
            ]);
            exit;
        }
        
        // 1. LIMPIAR registros anteriores
        $sqlDelete = "DELETE FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Fecha'";
        error_log("🔥 QUERY DELETE: " . $sqlDelete);
        
        $resultDelete = mysqli_query($db, $sqlDelete);
        if (!$resultDelete) {
            throw new Exception('Error al limpiar: ' . mysqli_error($db));
        }
        error_log("🔥 REGISTROS ELIMINADOS: " . mysqli_affected_rows($db));
        
        // 2. INSERTAR nuevos registros CON VALIDACIÓN DEFENSIVA
        foreach ($eventos as $index => $evento) {
            if (!isset($evento['concepto'])) {
                error_log("🔥 WARNING: Evento $index no tiene concepto, usando valor por defecto");
                error_log("🔥 EVENTO COMPLETO: " . print_r($evento, true));
            }
            
            $concepto = isset($evento['concepto']) ? mysqli_real_escape_string($db, $evento['concepto']) : 'SIN TÍTULO';
            $semana = isset($evento['semana']) ? mysqli_real_escape_string($db, $evento['semana']) : '';
            $descripcion = isset($evento['descripcion']) ? mysqli_real_escape_string($db, $evento['descripcion']) : '';
            $fecha = isset($evento['fecha']) ? mysqli_real_escape_string($db, $evento['fecha']) : '';
            $validacion = isset($evento['validacion']) ? mysqli_real_escape_string($db, $evento['validacion']) : 'Pendiente';
            
            error_log("🔥 EVENTO $index PROCESADO: concepto='$concepto', semana='$semana', fecha='$fecha'");
            
            $sqlInsert = "INSERT INTO grupo_pago (
                id_gen15, 
                con_gru_pag, 
                sem_gru_pag, 
                des_gru_pag, 
                ini_gru_pag, 
                fin_gru_pag, 
                val_gru_pag, 
                tip_gru_pag
            ) VALUES (
                '$id_gen', 
                '$concepto', 
                '$semana', 
                '$descripcion', 
                '$fecha', 
                '$fecha', 
                '$validacion', 
                'Fecha'
            )";
            error_log("🔥 QUERY INSERT $index: " . $sqlInsert);
            
            $resultInsert = mysqli_query($db, $sqlInsert);
            if (!$resultInsert) {
                throw new Exception('Error al insertar evento ' . $index . ': ' . mysqli_error($db));
            }
            error_log("🔥 INSERTADO EVENTO $index - ID: " . mysqli_insert_id($db));
        }
        
        // 3. CONTAR registros finales
        $sqlCount = "SELECT COUNT(*) as total FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Fecha'";
        $resultCount = mysqli_query($db, $sqlCount);
        $totalFinal = mysqli_fetch_assoc($resultCount)['total'];
        error_log("🔥 TOTAL FINAL: " . $totalFinal);
        
        echo json_encode([
            'resultado' => 'success',
            'mensaje' => 'Eventos guardados correctamente',
            'total' => $totalFinal,
            'procesados' => count($eventos)
        ]);
        
    } catch (Exception $e) {
        error_log("🔥 ERROR: " . $e->getMessage());
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// GENERAR REINSCRIPCIONES AUTOMÁTICAS CON STORED PROCEDURE
if ($accion == "generarReinscripcionesAutomaticas") {
    try {
        $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
        
        // Ejecutar stored procedure
        $sql = "CALL sp_crear_reinscripciones($id_gen)";
        $resultado = mysqli_query($db, $sql);
        
        if (!$resultado) {
            throw new Exception('Error al ejecutar SP: ' . mysqli_error($db));
        }
        
        // Contar registros creados
        $sqlCount = "SELECT COUNT(*) as total FROM grupo_pago WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Reinscripción'";
        $resultCount = mysqli_query($db, $sqlCount);
        $totalCreado = mysqli_fetch_assoc($resultCount)['total'];
        
        echo json_encode([
            'resultado' => 'success',
            'mensaje' => 'Reinscripciones generadas automáticamente',
            'total' => $totalCreado
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
    }
    exit;
}

// Acción no reconocida
echo json_encode(['resultado' => 'error', 'mensaje' => 'Acción no reconocida: ' . $accion]);

} else {
    // No hay acción definida
    echo json_encode(['resultado' => 'error', 'mensaje' => 'No se especificó ninguna acción']);
}

?>