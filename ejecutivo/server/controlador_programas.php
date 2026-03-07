<?php  
// ========================================================================
// CONTROLADOR DE PROGRAMAS ACADÉMICOS (RAMA)
// Archivo: server/controlador_programas.php
// VERSIÓN LIMPIA - SIN COLUMNAS INNECESARIAS
// ========================================================================
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ========== SECCIÓN 0: OBTENER PROGRAMAS (CONSULTA PRINCIPAL) ==========
if(!isset($_POST['actualizar_campo_programa']) && 
   !isset($_POST['eliminar_programa']) && 
   !isset($_POST['accion'])) {
    
    // DETECTAR BÚSQUEDA
    $datosPrograma = '';
    if(isset($_POST['datosPrograma'])) {
        $datosPrograma = trim(preg_replace('!\s+!', ' ', $_POST['datosPrograma']));
    }
    
    // ========== FILTRO: PLANTELES ==========
    $plantelesCondicion = "";
    if(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
        $plantelesLimpios = array_map('intval', $_POST['planteles_ajax']);
        $plantelesStr = implode(',', $plantelesLimpios);
        $plantelesCondicion = " AND r.id_pla1 IN ($plantelesStr)";
    }
    
    // ========== FILTRO: MODALIDADES ==========
    $modalidadesCondicion = "";
    if(isset($_POST['modalidades_ajax']) && !empty($_POST['modalidades_ajax'])) {
        $modalidadesLimpias = array_map(function($m) use ($db) {
            return "'" . mysqli_real_escape_string($db, $m) . "'";
        }, $_POST['modalidades_ajax']);
        $modalidadesStr = implode(',', $modalidadesLimpias);
        $modalidadesCondicion = " AND r.mod_ram IN ($modalidadesStr)";
    }
    
    // ========== FILTRO: NIVELES EDUCATIVOS ==========
    $nivelesCondicion = "";
    if(isset($_POST['niveles_ajax']) && !empty($_POST['niveles_ajax'])) {
        $nivelesLimpios = array_map(function($n) use ($db) {
            return "'" . mysqli_real_escape_string($db, $n) . "'";
        }, $_POST['niveles_ajax']);
        $nivelesStr = implode(',', $nivelesLimpios);
        $nivelesCondicion = " AND r.gra_ram IN ($nivelesStr)";
    }
    
    // ========== FILTRO: PERIODICIDADES ==========
    $periodicidadesCondicion = "";
    if(isset($_POST['periodicidades_ajax']) && !empty($_POST['periodicidades_ajax'])) {
        $periodicidadesLimpias = array_map(function($p) use ($db) {
            return "'" . mysqli_real_escape_string($db, $p) . "'";
        }, $_POST['periodicidades_ajax']);
        $periodicidadesStr = implode(',', $periodicidadesLimpias);
        $periodicidadesCondicion = " AND r.per_ram IN ($periodicidadesStr)";
    }
    
    // ========== FILTRO: ESTATUS (VISIBILIDAD) ==========
    $estatusCondicion = "";
    if(isset($_POST['estatus_ajax']) && !empty($_POST['estatus_ajax'])) {
        $estatusLimpios = array_map(function($e) use ($db) {
            return "'" . mysqli_real_escape_string($db, $e) . "'";
        }, $_POST['estatus_ajax']);
        $estatusStr = implode(',', $estatusLimpios);
        $estatusCondicion = " AND r.est_ram IN ($estatusStr)";
    }
    
    // ========== CONSULTA PRINCIPAL (SOLO COLUMNAS NECESARIAS) ==========
    $sql = "
        SELECT 
            r.id_ram,
            p.nom_pla AS plantel,
            r.nom_ram AS programa,
            r.abr_ram AS abreviatura,
            r.gra_ram AS nivel,
            r.mod_ram AS modalidad,
            r.per_ram AS periodicidad,
            r.cic_ram AS ciclos,
            r.eva_ram AS parciales,
            r.cos_ram AS costo,
            (SELECT COUNT(*) FROM materia WHERE id_ram2 = r.id_ram) AS total_materias,
            r.est_ram AS estatus
        FROM rama r
        INNER JOIN plantel p ON p.id_pla = r.id_pla1
        WHERE 1=1
        $plantelesCondicion
        $modalidadesCondicion
        $nivelesCondicion
        $periodicidadesCondicion
        $estatusCondicion
    ";
    
    // ========== BÚSQUEDA POR TEXTO ==========
    if(!empty($datosPrograma)) {
        $busqueda = mysqli_real_escape_string($db, $datosPrograma);
        $sql .= " AND (
            r.id_ram LIKE '%$busqueda%' OR
            r.nom_ram LIKE '%$busqueda%' OR
            r.abr_ram LIKE '%$busqueda%' OR
            p.nom_pla LIKE '%$busqueda%' OR
            r.gra_ram LIKE '%$busqueda%'
        )";
    }
    
    $sql .= " ORDER BY r.id_ram DESC";
    
    // ========== EJECUTAR ==========
    $resultado = mysqli_query($db, $sql);
    
    if(!$resultado) {
        echo json_encode([
            'error' => true,
            'mensaje' => mysqli_error($db)
        ]);
        exit;
    }
    
    // ========== PROCESAR RESULTADOS (12 COLUMNAS) ==========
    $programas = array();
    while($fila = mysqli_fetch_assoc($resultado)) {
        
        // ESTRUCTURA DE DATOS PARA HANDSONTABLE (12 COLUMNAS)
        $programas[] = array(
            $fila['id_ram'],           // 0: ID (oculto)
            $fila['plantel'],          // 1: PLANTEL (readonly)
            $fila['programa'],         // 2: PROGRAMA (readonly)
            $fila['abreviatura'],      // 3: ABREVIATURA (readonly)
            $fila['nivel'],            // 4: NIVEL (readonly)
            $fila['modalidad'],        // 5: MODALIDAD (readonly)
            $fila['periodicidad'],     // 6: PERIODICIDAD (readonly)
            $fila['ciclos'],           // 7: CICLOS (readonly)
            $fila['parciales'],        // 8: PARCIALES (readonly)
            $fila['costo'],            // 9: COSTO (readonly)
            $fila['total_materias'],   // 10: MATERIAS (readonly)
            $fila['estatus']           // 11: ESTATUS (readonly)
        );
    }
    
    echo json_encode($programas);
    exit;
}

// ========== SECCIÓN 1: ACTUALIZAR CAMPO INDIVIDUAL (DESDE MODAL) ==========
if(isset($_POST['actualizar_campo_programa'])) {
    
    $id_ram = intval($_POST['id_ram']);
    $campo = mysqli_real_escape_string($db, $_POST['campo']);
    $valor = mysqli_real_escape_string($db, $_POST['valor']);
    
    // CAMPOS PERMITIDOS PARA EDICIÓN (DESDE MODAL)
    $camposPermitidos = [
        'nom_ram',   // PROGRAMA
        'abr_ram',   // ABREVIATURA
        'gra_ram',   // NIVEL
        'mod_ram',   // MODALIDAD
        'per_ram',   // PERIODICIDAD
        'cic_ram',   // CICLOS
        'eva_ram',   // PARCIALES
        'cos_ram',   // COSTO
        'est_ram'    // ESTATUS
    ];
    
    if (!in_array($campo, $camposPermitidos)) {
        echo json_encode([
            'status' => 400,
            'message' => 'Campo no permitido para edición: ' . $campo
        ]);
        exit;
    }
    
    // VALIDACIÓN PARA CAMPOS NUMÉRICOS
    $camposNumericos = ['cic_ram', 'eva_ram', 'cos_ram'];
    if(in_array($campo, $camposNumericos)) {
        $valor = is_numeric($valor) ? $valor : 0;
    }
    
    // EJECUTAR UPDATE
    $sql = "UPDATE rama SET $campo = '$valor' WHERE id_ram = $id_ram";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'status' => 200,
            'message' => 'Campo actualizado correctamente',
            'campo' => $campo,
            'valor' => $valor
        ]);
    } else {
        echo json_encode([
            'status' => 500,
            'message' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 2: ELIMINAR PROGRAMA ==========
if(isset($_POST['eliminar_programa'])) {
    
    $id_ram = intval($_POST['eliminar_programa']);
    
    // VALIDAR: ¿Tiene materias?
    $sqlMaterias = "SELECT COUNT(*) AS total FROM materia WHERE id_ram2 = $id_ram";
    $resMaterias = mysqli_query($db, $sqlMaterias);
    $filaMaterias = mysqli_fetch_assoc($resMaterias);
    
    if($filaMaterias['total'] > 0) {
        echo json_encode([
            'status' => 400,
            'message' => 'No se puede eliminar: tiene ' . $filaMaterias['total'] . ' materia(s) asociada(s). Elimina primero las materias.'
        ]);
        exit;
    }
    
    // VALIDAR: ¿Tiene alumnos?
    $sqlAlumnos = "SELECT COUNT(*) AS total FROM alu_ram WHERE id_ram3 = $id_ram";
    $resAlumnos = mysqli_query($db, $sqlAlumnos);
    $filaAlumnos = mysqli_fetch_assoc($resAlumnos);
    
    if($filaAlumnos['total'] > 0) {
        echo json_encode([
            'status' => 400,
            'message' => 'No se puede eliminar: tiene ' . $filaAlumnos['total'] . ' alumno(s) inscrito(s).'
        ]);
        exit;
    }
    
    // ELIMINAR
    $sql = "DELETE FROM rama WHERE id_ram = $id_ram";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'status' => 200,
            'message' => 'Programa eliminado correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 500,
            'message' => 'Error al eliminar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 3: GUARDAR NUEVO PROGRAMA ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'guardar_programa') {
    
    $id_pla1 = intval($_POST['id_pla1']);
    $nom_ram = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nom_ram'])));
    $abr_ram = strtoupper(trim(mysqli_real_escape_string($db, $_POST['abr_ram'])));
    $gra_ram = mysqli_real_escape_string($db, $_POST['gra_ram']);
    $mod_ram = mysqli_real_escape_string($db, $_POST['mod_ram']);
    $per_ram = mysqli_real_escape_string($db, $_POST['per_ram']);
    $cic_ram = intval($_POST['cic_ram']);
    $eva_ram = intval($_POST['eva_ram']);
    $cos_ram = floatval($_POST['cos_ram']);
    
    // VALIDACIONES
    if (empty($nom_ram)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del programa es obligatorio']);
        exit;
    }
    
    if ($id_pla1 <= 0) {
        echo json_encode(['success' => false, 'message' => 'Debe seleccionar un plantel']);
        exit;
    }
    
    // VALIDAR DUPLICADO
    $sqlCheck = "SELECT id_ram FROM rama WHERE UPPER(nom_ram) = '$nom_ram' AND id_pla1 = $id_pla1 LIMIT 1";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if(mysqli_num_rows($resCheck) > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un programa con ese nombre en el plantel seleccionado']);
        exit;
    }
    
    // INSERTAR (SIN pagos, comision)
    $sql = "
        INSERT INTO rama (
            nom_ram, abr_ram, gra_ram, mod_ram, per_ram,
            cic_ram, eva_ram, cos_ram, id_pla1, est_ram
        ) VALUES (
            '$nom_ram', '$abr_ram', '$gra_ram', '$mod_ram', '$per_ram',
            $cic_ram, $eva_ram, $cos_ram, $id_pla1, 'Activo'
        )
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $id_ram = mysqli_insert_id($db);
        echo json_encode([
            'success' => true,
            'message' => 'Programa guardado correctamente',
            'id_ram' => $id_ram
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 4: OBTENER PROGRAMA (PARA MODAL EDICIÓN) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_programa') {
    
    $id_ram = intval($_POST['id_ram']);
    
    $sql = "
        SELECT r.*, p.nom_pla 
        FROM rama r
        INNER JOIN plantel p ON p.id_pla = r.id_pla1
        WHERE r.id_ram = $id_ram 
        LIMIT 1
    ";
    $resultado = mysqli_query($db, $sql);
    
    if($resultado && mysqli_num_rows($resultado) > 0) {
        $programa = mysqli_fetch_assoc($resultado);
        echo json_encode([
            'success' => true,
            'programa' => $programa
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Programa no encontrado'
        ]);
    }
    exit;
}

// ========== SECCIÓN 5: EDITAR PROGRAMA (DESDE MODAL) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'editar_programa') {
    
    $id_ram = intval($_POST['id_ram']);
    $id_pla1 = intval($_POST['id_pla1']);
    $nom_ram = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nom_ram'])));
    $abr_ram = strtoupper(trim(mysqli_real_escape_string($db, $_POST['abr_ram'])));
    $gra_ram = mysqli_real_escape_string($db, $_POST['gra_ram']);
    $mod_ram = mysqli_real_escape_string($db, $_POST['mod_ram']);
    $per_ram = mysqli_real_escape_string($db, $_POST['per_ram']);
    $cic_ram = intval($_POST['cic_ram']);
    $eva_ram = intval($_POST['eva_ram']);
    $cos_ram = floatval($_POST['cos_ram']);
    $est_ram = mysqli_real_escape_string($db, $_POST['est_ram']);
    
    // VALIDACIONES
    if (empty($nom_ram) || $id_pla1 <= 0 || $id_ram <= 0) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit;
    }
    
    // VALIDAR DUPLICADO (excluyendo el actual)
    $sqlCheck = "SELECT id_ram FROM rama WHERE UPPER(nom_ram) = '$nom_ram' AND id_pla1 = $id_pla1 AND id_ram != $id_ram LIMIT 1";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if(mysqli_num_rows($resCheck) > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otro programa con ese nombre en el plantel']);
        exit;
    }
    
    // ACTUALIZAR (SIN pagos, comision)
    $sql = "
        UPDATE rama SET
            nom_ram = '$nom_ram',
            abr_ram = '$abr_ram',
            gra_ram = '$gra_ram',
            mod_ram = '$mod_ram',
            per_ram = '$per_ram',
            cic_ram = $cic_ram,
            eva_ram = $eva_ram,
            cos_ram = $cos_ram,
            id_pla1 = $id_pla1,
            est_ram = '$est_ram'
        WHERE id_ram = $id_ram
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Programa actualizado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

?>