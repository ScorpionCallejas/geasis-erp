<?php  
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ==================== OBTENER DATOS DE GENERACIÓN POR ID_GEN ====================
if(isset($_POST['id_gen']) && !isset($_POST['accion']) && !isset($_POST['datosGrupo']) && !isset($_POST['obtener_todos'])) {
    $id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);
    
    $sqlGeneracion = "SELECT * FROM generacion WHERE id_gen = '$id_gen'";
    $resultadoGeneracion = mysqli_query($db, $sqlGeneracion);
    
    if($resultadoGeneracion && mysqli_num_rows($resultadoGeneracion) > 0) {
        $datosGeneracion = mysqli_fetch_assoc($resultadoGeneracion);
        echo json_encode(array('success' => true, 'data' => $datosGeneracion));
    } else {
        echo json_encode(array('success' => false, 'mensaje' => 'Generación no encontrada'));
    }
    exit;
}

// ==================== ACCIONES CRUD ====================
if(isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    
    switch($accion) {
        case 'Alta':
            $nom_gen = mysqli_real_escape_string($db, $_POST['nom_gen']);
            $ini_gen = mysqli_real_escape_string($db, $_POST['ini_gen']);
            $fin_gen = mysqli_real_escape_string($db, $_POST['fin_gen']);
            $met_gen = mysqli_real_escape_string($db, $_POST['met_gen']);
            $dia_gen = mysqli_real_escape_string($db, $_POST['dia_gen']);
            $hor_gen = mysqli_real_escape_string($db, $_POST['hor_gen']);
            $id_ram = mysqli_real_escape_string($db, $_POST['id_ram']);
            
            $sqlAlta = "INSERT INTO generacion (nom_gen, ini_gen, fin_gen, met_gen, dia_gen, hor_gen, id_ram5, mod_gen) 
                       VALUES ('$nom_gen', '$ini_gen', '$fin_gen', '$met_gen', '$dia_gen', '$hor_gen', '$id_ram', 'Online')";
            
            $resultadoAlta = mysqli_query($db, $sqlAlta);
            
            if($resultadoAlta) {
                echo json_encode(array('resultado' => 'success', 'mensaje' => 'Grupo creado correctamente'));
            } else {
                echo json_encode(array('resultado' => 'error', 'mensaje' => 'Error al crear: ' . mysqli_error($db)));
            }
            exit;
            
        case 'Editar':
            $id_gen = mysqli_real_escape_string($db, $_POST['id_gen_editar']);
            $nom_gen = mysqli_real_escape_string($db, $_POST['nom_gen_editar']);
            $ini_gen = mysqli_real_escape_string($db, $_POST['ini_gen_editar']);
            $fin_gen = mysqli_real_escape_string($db, $_POST['fin_gen_editar']);
            $dia_gen = mysqli_real_escape_string($db, $_POST['dia_gen_editar']);
            $hor_gen = mysqli_real_escape_string($db, $_POST['hor_gen_editar']);
            $mod_gen = mysqli_real_escape_string($db, $_POST['mod_gen_editar']);
            
            $sqlEditar = "UPDATE generacion SET 
                         nom_gen = '$nom_gen', ini_gen = '$ini_gen', fin_gen = '$fin_gen',
                         dia_gen = '$dia_gen', hor_gen = '$hor_gen', mod_gen = '$mod_gen'
                         WHERE id_gen = '$id_gen'";
            
            $resultadoEditar = mysqli_query($db, $sqlEditar);
            
            if($resultadoEditar) {
                echo json_encode(array('resultado' => 'success', 'mensaje' => 'Grupo actualizado correctamente'));
            } else {
                echo json_encode(array('resultado' => 'error', 'mensaje' => 'Error al actualizar: ' . mysqli_error($db)));
            }
            exit;
            
        default:
            echo json_encode(array('resultado' => 'error', 'mensaje' => 'Acción no válida'));
            exit;
    }
}

// ==================== FUNCIONES PARA CONSTRUIR CONDICIONES ====================

// FUNCIÓN UNIFICADA PARA CONSTRUIR CONDICIONES DE POTENCIAL
function construirCondicionPotencial($fecha_inicio, $fecha_fin, $bolsaSQL) {
    $tieneFechas = !empty($fecha_inicio) && !empty($fecha_fin);
    $tieneBolsa = !empty($bolsaSQL);
    
    if ($tieneFechas && $tieneBolsa) {
        return "obtener_potencial_alumno_periodo_tipo(ar.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')";
    } elseif ($tieneFechas) {
        return "obtener_potencial_alumno_periodo(ar.id_alu_ram, '$fecha_inicio', '$fecha_fin')";
    } elseif ($tieneBolsa) {
        return "obtener_potencial_alumno_tipo(ar.id_alu_ram, '$bolsaSQL')";
    } else {
        return "obtener_potencial_alumno(ar.id_alu_ram)";
    }
}

// FUNCIÓN PARA CONSTRUIR CONDICIONES DE ADEUDO
function construirCondicionAdeudo($fecha_inicio, $fecha_fin, $bolsaSQL) {
    $tieneFechas = !empty($fecha_inicio) && !empty($fecha_fin);
    $tieneBolsa = !empty($bolsaSQL);
    
    if ($tieneFechas && $tieneBolsa) {
        return "obtener_adeudo_alumno_periodo_tipo(ar.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')";
    } elseif ($tieneFechas) {
        return "obtener_adeudo_alumno_periodo(ar.id_alu_ram, '$fecha_inicio', '$fecha_fin')";
    } elseif ($tieneBolsa) {
        return "obtener_adeudo_alumno_tipo(ar.id_alu_ram, '$bolsaSQL')";
    } else {
        return "obtener_adeudo_alumno(ar.id_alu_ram)";
    }
}

// FUNCIÓN PARA CONSTRUIR CONDICIONES DE COBRADO
function construirCondicionCobrado($fecha_inicio, $fecha_fin, $bolsaSQL) {
    $tieneFechas = !empty($fecha_inicio) && !empty($fecha_fin);
    $tieneBolsa = !empty($bolsaSQL);
    
    if ($tieneFechas && $tieneBolsa) {
        return "obtener_cobrado_alumno_periodo_tipo(ar.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')";
    } elseif ($tieneFechas) {
        return "obtener_cobrado_alumno_periodo(ar.id_alu_ram, '$fecha_inicio', '$fecha_fin')";
    } elseif ($tieneBolsa) {
        return "obtener_cobrado_alumno_tipo(ar.id_alu_ram, '$bolsaSQL')";
    } else {
        return "obtener_cobrado_alumno(ar.id_alu_ram)";
    }
}

// FUNCIÓN PARA CONSTRUIR CONDICIONES DE PORCENTAJE (SUBCONSULTA DIRECTA)
function construirCondicionPorcentaje($fecha_inicio, $fecha_fin, $bolsaSQL) {
    $condiciones = array();
    
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $condiciones[] = "(p.fin_pag BETWEEN '$fecha_inicio' AND '$fecha_fin')";
    }
    
    if (!empty($bolsaSQL)) {
        $condiciones[] = "p.tip_pag = '$bolsaSQL'";
    }
    
    if (empty($condiciones)) {
        return '1=1';
    }
    
    return implode(' AND ', $condiciones);
}

// ==================== PROCESAMIENTO PRINCIPAL ====================
error_log("========== CONTROLADOR GRUPOS REFACTORIZADO ==========");
error_log("POST DATA: " . json_encode($_POST, JSON_UNESCAPED_UNICODE));

// OBTENER FECHAS PARA FILTRO
$fecha_inicio = '';
$fecha_fin = '';
$filtroHabilitado = isset($_POST['filtro_periodo_habilitado']) && $_POST['filtro_periodo_habilitado'] === 'true';

if($filtroHabilitado && isset($_POST['fecha_inicio_mes']) && isset($_POST['fecha_fin_mes']) 
   && !empty($_POST['fecha_inicio_mes']) && !empty($_POST['fecha_fin_mes'])) {
    $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio_mes']);
    $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin_mes']);
    error_log("FECHAS VÁLIDAS: $fecha_inicio a $fecha_fin");
} else {
    error_log("SIN FECHAS O PERÍODO INHABILITADO");
}

// OBTENER FILTRO DE BOLSA DE PAGO
$bolsaPago = isset($_POST['bolsa_pago']) ? $_POST['bolsa_pago'] : 'adeudo';
$bolsaSQL = '';

switch($bolsaPago) {
    case 'colegiatura':
        $bolsaSQL = 'Colegiatura';
        break;
    case 'inscripcion':
        $bolsaSQL = 'Inscripción';
        break;
    case 'reinscripcion':
        $bolsaSQL = 'Reinscripción';
        break;
    case 'tramite':
        $bolsaSQL = 'Otros';
        break;
    case 'adeudo':
    default:
        $bolsaSQL = '';
        break;
}

error_log("BOLSA SELECCIONADA: $bolsaPago -> $bolsaSQL");

// CONSTRUIR CONDICIONES DINÁMICAS UNIFICADAS
$condicionPotencial = construirCondicionPotencial($fecha_inicio, $fecha_fin, $bolsaSQL);
$condicionAdeudo = construirCondicionAdeudo($fecha_inicio, $fecha_fin, $bolsaSQL);
$condicionCobrado = construirCondicionCobrado($fecha_inicio, $fecha_fin, $bolsaSQL);
$condicionPorcentaje = construirCondicionPorcentaje($fecha_inicio, $fecha_fin, $bolsaSQL);

error_log("CONDICIONES GENERADAS:");
error_log("  - Potencial: $condicionPotencial");
error_log("  - Adeudo: $condicionAdeudo");
error_log("  - Cobrado: $condicionCobrado");
error_log("  - Porcentaje: $condicionPorcentaje");

// DETECTAR TIPO DE BÚSQUEDA
$esBusquedaNormal = false;
$datosGrupo = '';

if(isset($_POST['obtener_todos']) && $_POST['obtener_todos'] == true) {
    $datosGrupo = '';
    $esBusquedaNormal = false;
} else {
    $datosGrupo = isset($_POST['datosGrupo']) ? trim(preg_replace('!\s+!', ' ', $_POST['datosGrupo'])) : '';
    $esBusquedaNormal = !empty($datosGrupo);
}

// MANEJO DE PLANTELES DINÁMICO
$plantelesCondicion = "";
$plantelesArray = array();

if(isset($_POST['planteles_seleccionados']) && !empty($_POST['planteles_seleccionados'])) {
    $plantelesArray = $_POST['planteles_seleccionados'];
} elseif(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
    $plantelesArray = $_POST['planteles_ajax'];
} else {
    $plantelesArray = array($plantel);
}

if(!empty($plantelesArray)) {
    $plantelesLimpios = array();
    foreach($plantelesArray as $p) {
        $plantelesLimpios[] = intval($p);
    }
    $plantelesStr = implode(',', $plantelesLimpios);
    $plantelesCondicion = " AND plantel.id_pla IN ($plantelesStr)";
    $plantelesSubconsulta = " AND p_sub.id_pla IN ($plantelesStr)";
} else {
    $plantelesCondicion = " AND plantel.id_pla = '$plantel'";
    $plantelesSubconsulta = " AND p_sub.id_pla = '$plantel'";
}

// MANEJO DE NIVEL ACADÉMICO
$nivelArray = array();
if(isset($_POST['nivel_ajax']) && !empty($_POST['nivel_ajax'])) {
    $nivelArray = $_POST['nivel_ajax'];
}

$nivelCondicion = "";
$nivelSubconsulta = "";
if(!empty($nivelArray)) {
    $nivelLimpios = array();
    foreach($nivelArray as $nivel) {
        $nivelLimpios[] = "'" . mysqli_real_escape_string($db, $nivel) . "'";
    }
    $nivelStr = implode(',', $nivelLimpios);
    $nivelCondicion = " AND rama.gra_ram IN ($nivelStr)";
    $nivelSubconsulta = " AND r_sub.gra_ram IN ($nivelStr)";
}

// MANEJO DE ESTATUS DE ALUMNOS
$estatusArray = array();
if(isset($_POST['estatus_ajax']) && !empty($_POST['estatus_ajax'])) {
    $estatusArray = $_POST['estatus_ajax'];
}

$estatusStr = '';
if(!empty($estatusArray)) {
    $estatusLimpios = array();
    foreach($estatusArray as $e) {
        $estatusLimpios[] = "'" . mysqli_real_escape_string($db, $e) . "'";
    }
    $estatusStr = implode(',', $estatusLimpios);
} else {
    $estatusStr = "'PROSPECTO', 'REGISTRO','REGISTRADO','ACTIVO','NP','BAJA','DESERCION','FIN CURSO','REINGRESO','GRADUADO'";
}

// MANEJO DE ESTATUS GRUPAL
$gruposArray = array();
if(isset($_POST['grupos_ajax']) && !empty($_POST['grupos_ajax'])) {
    $gruposArray = $_POST['grupos_ajax'];
}

$gruposCondicion = "";
if(!empty($gruposArray)) {
    $hoy = date('Y-m-d');
    $condicionesGrupo = array();
    
    foreach($gruposArray as $grupo) {
        $grupo = mysqli_real_escape_string($db, trim($grupo));
        
        switch($grupo) {
            case 'EN CURSO':
                $condicionesGrupo[] = "(generacion.ini_gen <= '$hoy' AND generacion.fin_gen >= '$hoy')";
                break;
            case 'POR COMENZAR':
                $condicionesGrupo[] = "(generacion.ini_gen > '$hoy')";
                break;
            case 'VENCIDOS':
                $condicionesGrupo[] = "(generacion.fin_gen < '$hoy')";
                break;
        }
    }
    
    if(!empty($condicionesGrupo)) {
        $gruposCondicion = " AND (" . implode(' OR ', $condicionesGrupo) . ")";
    }
}

// ==================== QUERY PRINCIPAL UNIFICADO ====================
$hoy = date('Y-m-d');
$sql = "
    SELECT 
        plantel.nom_pla AS centro,
        CASE 
            WHEN generacion.ini_gen > '$hoy' THEN 'POR COMENZAR'
            WHEN generacion.fin_gen < '$hoy' THEN 'VENCIDOS'
            ELSE 'EN CURSO'
        END AS estatus_grupal,
        generacion.nom_gen AS grupo,
        
        -- T.ALUM - TOTAL DE ALUMNOS
        (
            SELECT COUNT(DISTINCT ar.id_alu_ram)
            FROM alu_ram ar
            INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
            INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
            INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
            WHERE ar.id_gen1 = generacion.id_gen
            AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
            $plantelesSubconsulta
            $nivelSubconsulta
        ) AS t_alum,
        
        -- DEUDORES - ALUMNOS CON ADEUDO > 0
        (
            SELECT COUNT(DISTINCT ar.id_alu_ram)
            FROM alu_ram ar
            INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
            INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
            INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
            WHERE ar.id_gen1 = generacion.id_gen
            AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
            AND $condicionAdeudo > 0
            $plantelesSubconsulta
            $nivelSubconsulta
        ) AS deudores,
        
        -- T.PAGA - CALCULADO COMO (T.ALUM - DEUDORES)
        (
            (
                SELECT COUNT(DISTINCT ar.id_alu_ram)
                FROM alu_ram ar
                INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
                INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
                INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
                WHERE ar.id_gen1 = generacion.id_gen
                AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
                $plantelesSubconsulta
                $nivelSubconsulta
            ) - 
            (
                SELECT COUNT(DISTINCT ar.id_alu_ram)
                FROM alu_ram ar
                INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
                INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
                INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
                WHERE ar.id_gen1 = generacion.id_gen
                AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
                AND $condicionAdeudo > 0
                $plantelesSubconsulta
                $nivelSubconsulta
            )
        ) AS t_paga,
        
        rama.abr_ram AS programa,
        rama.gra_ram AS nivel_academico,
        generacion.ini_gen AS f_inicio,
        generacion.fin_gen AS f_fin,
        
        -- COBRADO - SUMA DE FUNCIONES DINÁMICAS
        (
            SELECT IFNULL(SUM($condicionCobrado), 0)
            FROM alu_ram ar
            INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
            INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
            INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
            WHERE ar.id_gen1 = generacion.id_gen
            AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
            $plantelesSubconsulta
            $nivelSubconsulta
        ) AS cobrado,
        
        -- POTENCIAL - SUMA DE FUNCIONES DINÁMICAS
        (
            SELECT IFNULL(SUM($condicionPotencial), 0)
            FROM alu_ram ar
            INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
            INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
            INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
            WHERE ar.id_gen1 = generacion.id_gen
            AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
            $plantelesSubconsulta
            $nivelSubconsulta
        ) AS potencial,
        
        -- PORCENTAJE - CALCULADO CON CONDICIONES UNIFICADAS
        CASE 
            WHEN (
                SELECT IFNULL(SUM(p.mon_ori_pag), 0)
                FROM alu_ram ar
                INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
                INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
                INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
                INNER JOIN pago p ON p.id_alu_ram10 = ar.id_alu_ram
                WHERE ar.id_gen1 = generacion.id_gen
                AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
                AND $condicionPorcentaje
                $plantelesSubconsulta
                $nivelSubconsulta
            ) = 0 THEN '0%'
            ELSE CONCAT(
                ROUND(
                    (
                        (
                            SELECT IFNULL(SUM($condicionCobrado), 0)
                            FROM alu_ram ar
                            INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
                            INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
                            INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
                            WHERE ar.id_gen1 = generacion.id_gen
                            AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
                            $plantelesSubconsulta
                            $nivelSubconsulta
                        ) / 
                        (
                            SELECT IFNULL(SUM(p.mon_ori_pag), 1)
                            FROM alu_ram ar
                            INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
                            INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
                            INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
                            INNER JOIN pago p ON p.id_alu_ram10 = ar.id_alu_ram
                            WHERE ar.id_gen1 = generacion.id_gen
                            AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
                            AND $condicionPorcentaje
                            $plantelesSubconsulta
                            $nivelSubconsulta
                        )
                    ) * 100, 1
                ), '%'
            )
        END AS porcentaje,
        
        generacion.mod_gen AS modalidad,
        generacion.dia_gen AS dias,
        generacion.hor_gen AS horario,
        generacion.id_gen,
        generacion.ini_gen AS f_inicio_raw,
        generacion.fin_gen AS f_fin_raw
        
    FROM generacion 
    INNER JOIN rama ON rama.id_ram = generacion.id_ram5 
    INNER JOIN plantel ON plantel.id_pla = rama.id_pla1 
    WHERE 1=1 
    $plantelesCondicion 
    $nivelCondicion
    $gruposCondicion
    
    -- ==================== 🚀 FILTRO CRÍTICO: SOLO GRUPOS CON ALUMNOS ====================
    AND EXISTS (
        SELECT 1 
        FROM alu_ram ar
        INNER JOIN alumno a_sub ON a_sub.id_alu = ar.id_alu1
        INNER JOIN rama r_sub ON r_sub.id_ram = ar.id_ram3
        INNER JOIN plantel p_sub ON p_sub.id_pla = r_sub.id_pla1
        WHERE ar.id_gen1 = generacion.id_gen
        AND OBTENER_ESTATUS_GENERAL(ar.id_alu_ram, generacion.fin_gen, ar.est1_alu_ram) IN ($estatusStr)
        $plantelesSubconsulta
        $nivelSubconsulta
    )
";

// Agregar filtro de búsqueda por texto
if ($esBusquedaNormal && !empty($datosGrupo)) {
    $sql .= " AND 
        ( generacion.id_gen LIKE '%$datosGrupo%' OR  
          UPPER(generacion.nom_gen) LIKE UPPER(_utf8 '%$datosGrupo%') COLLATE utf8_general_ci OR 
          UPPER(plantel.nom_pla) LIKE UPPER(_utf8 '%$datosGrupo%') COLLATE utf8_general_ci OR 
          UPPER(rama.abr_ram) LIKE UPPER(_utf8 '%$datosGrupo%') COLLATE utf8_general_ci OR
          UPPER(rama.nom_ram) LIKE UPPER(_utf8 '%$datosGrupo%') COLLATE utf8_general_ci OR
          UPPER(rama.gra_ram) LIKE UPPER(_utf8 '%$datosGrupo%') COLLATE utf8_general_ci ) 
    ";
}

$sql .= ' ORDER BY generacion.id_gen DESC';

error_log("QUERY FINAL CON FILTRO T_ALUM > 0: " . $sql);

// ==================== EJECUCIÓN Y RESPUESTA ====================
$resultado = mysqli_query($db, $sql);

if (!$resultado) {
    $error = array(
        'error' => true,
        'mensaje' => mysqli_error($db),
        'sql' => $sql
    );
    error_log("ERROR MYSQL: " . mysqli_error($db));
    echo json_encode($error);
    exit;
}

$grupos = array();
while ($fila = mysqli_fetch_assoc($resultado)) {
    $grupos[] = array(
        "CENTRO" => $fila['centro'],
        "ESTATUS GRUPAL" => $fila['estatus_grupal'],
        "GRUPO" => $fila['grupo'],
        "T.ALUM" => $fila['t_alum'],
        "T.PAGA" => $fila['t_paga'],
        "DEUDORES" => $fila['deudores'],
        "PROGRAMA" => $fila['programa'],
        "NIVEL ACADÉMICO" => $fila['nivel_academico'],
        "F.INICIO" => fechaFormateadaCompacta4($fila['f_inicio']),
        "F.FIN" => fechaFormateadaCompacta4($fila['f_fin']),
        "COBRADO" => formatearDinero($fila['cobrado']),
        "POTENCIAL" => formatearDinero($fila['potencial']),
        "PORCENTAJE" => $fila['porcentaje'],
        "MODALIDAD" => $fila['modalidad'],
        "DÍAS" => $fila['dias'],
        "HORARIO" => $fila['horario'],
        "ID_GEN" => $fila['id_gen'],
        "F_INICIO_RAW" => $fila['f_inicio_raw'],
        "F_FIN_RAW" => $fila['f_fin_raw']
    );
}

error_log("GRUPOS CON T_ALUM > 0 - RESULTADOS: " . count($grupos));
echo json_encode($grupos);
?>