<?php  
// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE EGRESO
require('../inc/cabeceras.php');
require('../inc/funciones.php');


if(isset($db) && isset($cadena) && isset($nomResponsable)) {
    generar_gastos_fijos_mes_actual($db, $cadena, $nomResponsable);
}

// ========== SECCIÓN 0: OBTENER CATEGORÍAS DINÁMICAS ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_categorias') {
    
    $sql = "
        SELECT id_cat_egr, cat_cat_egr 
        FROM categorias_egreso 
        WHERE id_cad5 = $cadena AND est_cat_egr = 'Activo'
        ORDER BY cat_cat_egr ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $categorias = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $categorias[] = array(
                'id' => $fila['id_cat_egr'],
                'nombre' => $fila['cat_cat_egr']
            );
        }
        
        echo json_encode([
            'success' => true,
            'categorias' => $categorias
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtener categorías: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 1: ACTUALIZAR CAMPO INDIVIDUAL (EDICIÓN INLINE) ==========
if(isset($_POST['actualizar_campo_egreso'])) {
    $id_egr = intval($_POST['id_egr']);
    $campo = mysqli_real_escape_string($db, $_POST['campo']);
    $valor = mysqli_real_escape_string($db, $_POST['valor']);
    
    // Validar que el campo sea editable
    $camposPermitidos = ['con_egr', 'mon_egr', 'id_cat_egr', 'for_egr', 'rec_egr', 'ori_egr', 'est_egr', 'obs_egr'];
    
    if (!in_array($campo, $camposPermitidos)) {
        echo json_encode([
            'status' => 400,
            'message' => 'Campo no editable'
        ]);
        exit;
    }
    
    // LÓGICA ESPECIAL PARA ESTADO DE PAGO
    $sqlExtra = "";
    $pag_egr = null;
    
    if ($campo === 'est_egr') {
        if ($valor === 'Pagado') {
            // Si cambia a Pagado, setear fecha de pago a HOY
            $sqlExtra = ", pag_egr = CURDATE()";
            $pag_egr = date('Y-m-d');
        } else if ($valor === 'Pendiente') {
            // Si cambia a Pendiente, limpiar fecha de pago
            $sqlExtra = ", pag_egr = NULL";
            $pag_egr = null;
        }
    }
    
    // Construir consulta SQL
    $sql = "
        UPDATE egreso 
        SET $campo = '$valor' $sqlExtra
        WHERE id_egr = $id_egr
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $response = [
            'status' => 200,
            'message' => 'Campo actualizado correctamente',
            'campo' => $campo,
            'valor' => $valor
        ];
        
        // Si se actualizó el estado, devolver la fecha de pago
        if ($campo === 'est_egr') {
            $response['pag_egr'] = $pag_egr;
        }
        
        echo json_encode($response);
    } else {
        echo json_encode([
            'status' => 500,
            'message' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 2: ELIMINAR EGRESO ==========
else if(isset($_POST['eliminar_egreso'])) {
    $id_egr = intval($_POST['eliminar_egreso']);
    
    $sql = "DELETE FROM egreso WHERE id_egr = $id_egr";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'status' => 200,
            'message' => 'Egreso eliminado correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 500,
            'message' => 'Error al eliminar el egreso: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 3: GUARDAR NUEVO EGRESO (CON LÓGICA "OTRA") ==========
else if(isset($_POST['accion']) && $_POST['accion'] === 'guardar_egreso') {
    
    // Validar que existan las variables de sesión necesarias
    if (!isset($cadena) || empty($cadena)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: Variable de sesión cadena no definida'
        ]);
        exit;
    }
    
    if (!isset($nomResponsable) || empty($nomResponsable)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: Variable de sesión nomResponsable no definida'
        ]);
        exit;
    }
    
    // Obtener datos del formulario
    $id_pla13 = mysqli_real_escape_string($db, $_POST['id_pla13']);
    $con_egr = mysqli_real_escape_string($db, $_POST['con_egr']);
    $mon_egr = floatval($_POST['mon_egr']);
    $cat_egr = mysqli_real_escape_string($db, $_POST['cat_egr']);
    $cat_egr_otra = isset($_POST['cat_egr_otra']) ? strtoupper(trim(mysqli_real_escape_string($db, $_POST['cat_egr_otra']))) : '';
    $for_egr = mysqli_real_escape_string($db, $_POST['for_egr']);
    $obs_egr = mysqli_real_escape_string($db, $_POST['obs_egr']);
    $rec_egr = mysqli_real_escape_string($db, $_POST['rec_egr']);
    $ori_egr = mysqli_real_escape_string($db, $_POST['ori_egr']);
    $est_egr = mysqli_real_escape_string($db, $_POST['est_egr']);
    
    // Valores fijos
    $tip_egr = 'Egreso';
    $res_egr = $nomResponsable;
    
    // Validaciones básicas
    if (empty($id_pla13) || empty($mon_egr) || empty($con_egr)) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan campos obligatorios (plantel, monto o concepto)'
        ]);
        exit;
    }
    
    // ========== LÓGICA PARA CATEGORÍA "OTRA" ==========
    $id_cat_egr_final = null;
    $nueva_categoria_creada = false;
    
    if ($cat_egr === 'OTRA') {
        
        if (empty($cat_egr_otra)) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes especificar el nombre de la categoría personalizada'
            ]);
            exit;
        }
        
        // Verificar si ya existe esa categoría para esta cadena
        $sql_existe = "
            SELECT id_cat_egr 
            FROM categorias_egreso 
            WHERE UPPER(cat_cat_egr) = UPPER('$cat_egr_otra')
            AND id_cad5 = $cadena 
            AND est_cat_egr = 'Activo'
            LIMIT 1
        ";
        
        $resultado_existe = mysqli_query($db, $sql_existe);
        
        if (!$resultado_existe) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al verificar categoría existente: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        if (mysqli_num_rows($resultado_existe) > 0) {
            // Ya existe, usamos ese ID
            $fila_existe = mysqli_fetch_assoc($resultado_existe);
            $id_cat_egr_final = intval($fila_existe['id_cat_egr']);
            $nueva_categoria_creada = false;
        } else {
            // NO existe, la insertamos
            $sql_insertar_cat = "
                INSERT INTO categorias_egreso (cat_cat_egr, est_cat_egr, id_cad5) 
                VALUES ('$cat_egr_otra', 'Activo', $cadena)
            ";
            
            $resultado_insertar_cat = mysqli_query($db, $sql_insertar_cat);
            
            if (!$resultado_insertar_cat) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear nueva categoría: ' . mysqli_error($db)
                ]);
                exit;
            }
            
            $id_cat_egr_final = mysqli_insert_id($db);
            $nueva_categoria_creada = true;
        }
        
    } else {
        // Categoría estándar (no es OTRA)
        $id_cat_egr_final = intval($cat_egr);
        
        if ($id_cat_egr_final <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes seleccionar una categoría válida'
            ]);
            exit;
        }
    }
    
    // ========== LÓGICA DE FECHA DE PAGO ==========
    $pag_egr_valor = 'NULL';
    if ($est_egr === 'Pagado') {
        $pag_egr_valor = "CURDATE()";
    }
    
    // ========== INSERTAR EGRESO EN LA TABLA ==========
    $sql = "
        INSERT INTO egreso (
            id_pla13, con_egr, mon_egr, tip_egr, 
            fec_egr, res_egr, id_cat_egr, for_egr, obs_egr,
            rec_egr, ori_egr, est_egr, pag_egr
        ) VALUES (
            '$id_pla13', '$con_egr', $mon_egr, '$tip_egr',
            CURDATE(), '$res_egr', $id_cat_egr_final, '$for_egr', '$obs_egr',
            '$rec_egr', '$ori_egr', '$est_egr', $pag_egr_valor
        )
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        // Obtener el ID generado automáticamente
        $id_egr = mysqli_insert_id($db);
        
        echo json_encode([
            'success' => true,
            'message' => 'Egreso guardado correctamente',
            'id_egr' => $id_egr,
            'nueva_categoria_creada' => $nueva_categoria_creada,
            'id_categoria' => $id_cat_egr_final
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar el egreso: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== SECCIÓN 4: BÚSQUEDA Y LISTADO DE EGRESOS ==========
else {
    // OBTENER FECHAS PARA FILTRO
    $fecha_inicio = '';
    $fecha_fin = '';
    
    if(isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) 
       && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
        $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio']);
        $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin']);
    }
    
    // DETECTAR TIPO DE BÚSQUEDA
    $esBusquedaNormal = false;
    $datosEgreso = '';
    
    if(isset($_POST['datosEgreso'])) {
        $datosEgreso = trim(preg_replace('!\s+!', ' ', $_POST['datosEgreso']));
        $esBusquedaNormal = true;
    }
    
    // ========== MANEJO DE PLANTELES ==========
    $plantelesCondicion = "";
    $plantelesArray = array();
    
    if(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
        $plantelesArray = $_POST['planteles_ajax'];
    }
    
    if(!empty($plantelesArray)) {
        $plantelesLimpios = array();
        foreach($plantelesArray as $p) {
            $plantelesLimpios[] = intval($p);
        }
        $plantelesStr = implode(',', $plantelesLimpios);
        $plantelesCondicion = " AND e.id_pla13 IN ($plantelesStr)";
    }
    
    // ========== MANEJO DE CATEGORÍAS (CON FK) ==========
    $categoriasCondicion = "";
    $categoriasArray = array();
    
    if(isset($_POST['categorias_ajax']) && !empty($_POST['categorias_ajax'])) {
        $categoriasArray = $_POST['categorias_ajax'];
    }
    
    if(!empty($categoriasArray)) {
        $categoriasLimpias = array();
        foreach($categoriasArray as $c) {
            $categoriasLimpias[] = intval($c);
        }
        $categoriasStr = implode(',', $categoriasLimpias);
        $categoriasCondicion = " AND e.id_cat_egr IN ($categoriasStr)";
    }
    
    // ========== MANEJO DE ORÍGENES ==========
    $origenesCondicion = "";
    $origenesArray = array();
    
    if(isset($_POST['origenes_ajax']) && !empty($_POST['origenes_ajax'])) {
        $origenesArray = $_POST['origenes_ajax'];
    }
    
    if(!empty($origenesArray)) {
        $origenesLimpios = array();
        foreach($origenesArray as $o) {
            $origenesLimpios[] = "'" . mysqli_real_escape_string($db, $o) . "'";
        }
        $origenesStr = implode(',', $origenesLimpios);
        $origenesCondicion = " AND e.ori_egr IN ($origenesStr)";
    }
    
    // ========== MANEJO DE ESTADOS (INCLUYE VENCIDO) ==========
    $estadosCondicion = "";
    $estadosArray = array();
    
    if(isset($_POST['estados_ajax']) && !empty($_POST['estados_ajax'])) {
        $estadosArray = $_POST['estados_ajax'];
    }
    
    if(!empty($estadosArray)) {
        $tienePendiente = in_array('Pendiente', $estadosArray);
        $tieneVencido = in_array('Vencido', $estadosArray);
        $tienePagado = in_array('Pagado', $estadosArray);
        
        $estadosDB = array();
        
        // Si tiene Pendiente O Vencido, incluimos "Pendiente" en la consulta
        if ($tienePendiente || $tieneVencido) {
            $estadosDB[] = "'Pendiente'";
        }
        
        if ($tienePagado) {
            $estadosDB[] = "'Pagado'";
        }
        
        if (!empty($estadosDB)) {
            $estadosStr = implode(',', $estadosDB);
            $estadosCondicion = " AND e.est_egr IN ($estadosStr)";
        }
    }
    
    // ========== MANEJO DE FORMAS DE EGRESO ==========
    $formasCondicion = "";
    $formasArray = array();
    
    if(isset($_POST['formas_ajax']) && !empty($_POST['formas_ajax'])) {
        $formasArray = $_POST['formas_ajax'];
    }
    
    if(!empty($formasArray)) {
        $formasLimpias = array();
        foreach($formasArray as $f) {
            $formasLimpias[] = "'" . mysqli_real_escape_string($db, $f) . "'";
        }
        $formasStr = implode(',', $formasLimpias);
        $formasCondicion = " AND e.for_egr IN ($formasStr)";
    }
    
    // ========== MANEJO DE FECHAS ==========
    $fechasCondicion = "";
    
    if(!empty($fecha_inicio) && !empty($fecha_fin)) {
        $fechasCondicion = " AND e.fec_egr BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }
    
    // ========== CONSTRUIR CONSULTA BASE (CON JOIN A categorias_egreso) ==========
    // En controlador_egreso.php, línea ~185
    $sql = "
        SELECT 
            e.id_egr,
            (SELECT nom_pla FROM plantel WHERE id_pla = e.id_pla13) AS plantel,
            e.id_egr AS folio,
            e.con_egr,
            e.mon_egr,
            COALESCE(ce.cat_cat_egr, CAST(e.id_cat_egr AS CHAR)) AS categoria,
            e.for_egr,
            e.rec_egr,
            e.ori_egr,
            e.est_egr,
            e.fec_egr,
            e.pag_egr,
            e.res_egr,
            e.obs_egr
        FROM egreso e
        LEFT JOIN categorias_egreso ce ON e.id_cat_egr = ce.id_cat_egr
        WHERE 1=1
    ";
    // ========== AGREGAR CONDICIONES ==========
    $sql .= $plantelesCondicion;
    $sql .= $categoriasCondicion;
    $sql .= $origenesCondicion;
    $sql .= $estadosCondicion;
    $sql .= $formasCondicion;
    $sql .= $fechasCondicion;
    
    // ========== AGREGAR BÚSQUEDA POR TEXTO ==========
    if($esBusquedaNormal && !empty($datosEgreso)) {
        $busqueda = mysqli_real_escape_string($db, $datosEgreso);
        $sql .= " AND (
            e.id_egr LIKE '%$busqueda%' OR
            e.con_egr LIKE '%$busqueda%' OR
            e.res_egr LIKE '%$busqueda%' OR
            ce.cat_cat_egr LIKE '%$busqueda%' OR
            e.obs_egr LIKE '%$busqueda%'
        )";
    }
    
    // ========== ORDENAR RESULTADOS ==========
    $sql .= " ORDER BY e.id_egr DESC";
    
    // ========== EJECUTAR CONSULTA ==========
    $resultado = mysqli_query($db, $sql);
    
    if(!$resultado) {
        $error = array(
            'error' => true,
            'mensaje' => mysqli_error($db)
        );
        echo json_encode($error);
        exit;
    }
    
    // ========== PROCESAR RESULTADOS ==========
    $egresos = array();
    while($fila = mysqli_fetch_assoc($resultado)) {
        $egresos[] = array(
            "ID" => $fila['id_egr'],
            "PLANTEL" => $fila['plantel'],
            "FOLIO" => $fila['folio'],
            "CONCEPTO" => $fila['con_egr'],
            "MONTO" => $fila['mon_egr'],
            "CATEGORÍA" => $fila['categoria'],
            "FORMA" => strtoupper(str_replace('_', ' ', $fila['for_egr'])),
            "RECURRENCIA" => $fila['rec_egr'],
            "ORIGEN" => $fila['ori_egr'],
            "ESTADO" => $fila['est_egr'],
            "FECHA EGRESO" => $fila['fec_egr'],
            "FECHA PAGO" => $fila['pag_egr'],
            "RESPONSABLE" => $fila['res_egr'],
            "OBSERVACIONES" => $fila['obs_egr']
        );
    }
    
    echo json_encode($egresos);
    exit;
}
?>