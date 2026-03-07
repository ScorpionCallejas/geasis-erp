<?php  
// CONTROLADOR DE MATERIAS - LEGACY + NUEVOS ENDPOINTS
require('../inc/cabeceras.php');
require('../inc/funciones.php');

header('Content-Type: application/json');

// ========================================================================
// SECCIÓN LEGACY: ALTA/BAJA/CAMBIO (NO TOCAR - USADO POR OTROS MÓDULOS)
// ========================================================================
if (isset($_POST['campo']) && isset($_POST['accion'])) {
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];
    $accion = $_POST['accion'];
    $id_ram2 = isset($_POST['id_ram']) ? $_POST['id_ram'] : null;

    if ($accion == "Alta") {
        $sql = "INSERT INTO materia ($campo, id_ram2) VALUES ('$valor', '$id_ram2')";
        
        $resultado = mysqli_query($db, $sql);

        if (!$resultado) {
            echo json_encode(['error' => $sql]);
        } else {
            $id_mat = mysqli_insert_id($db); // ← CORREGIDO
            
            $sqlDatos = "SELECT * FROM materia WHERE id_mat = $id_mat";
            $datos = obtener_datos_consulta($db, $sqlDatos)['datos'];

            echo json_encode([
                'id_mat' => $datos['id_mat'],
                'nom_mat' => $datos['nom_mat'], 
                'cic_mat' => $datos['cic_mat']
            ]);
        }
    } 
    elseif ($accion == "Cambio") {
        $id_mat = $_POST['id_mat'];

        $sql = "
            UPDATE materia
            SET $campo = '$valor'
            WHERE id_mat = '$id_mat'
        ";

        $resultado = mysqli_query($db, $sql);

        if (!$resultado) {
            echo json_encode(['resultado' => 'error']);
        } else {
            $sqlDatos = "SELECT * FROM materia WHERE id_mat = $id_mat";
            $datos = obtener_datos_consulta($db, $sqlDatos)['datos'];

            if ($datos['nom_mat'] == '' && $datos['cic_mat'] == '') {
                $sqlEliminar = "DELETE FROM materia WHERE id_mat = '$id_mat'";
                $resultadoEliminar = mysqli_query($db, $sqlEliminar);

                if (!$resultadoEliminar) {
                    echo json_encode(['resultado' => 'error query']);
                } else {
                    echo json_encode(['resultado' => 'false']);
                }
            } else {
                echo json_encode(['resultado' => 'exito']);
            }
        }
    }
    exit;
}

// ========================================================================
// NUEVOS ENDPOINTS: CRUD PARA MODAL DE PROGRAMAS
// ========================================================================

// ========== 1. OBTENER MATERIAS DE UN PROGRAMA ==========
if (isset($_POST['accion']) && $_POST['accion'] === 'obtener_materias_programa') {
    $id_ram = intval($_POST['id_ram']);
    
    if (!$id_ram) {
        echo json_encode(['error' => true, 'mensaje' => 'ID de programa no válido']);
        exit;
    }
    
    $sql = "
        SELECT 
            id_mat,
            nom_mat,
            abb_mat,
            cre_mat,
            est_mat
        FROM materia
        WHERE id_ram2 = $id_ram
        ORDER BY nom_mat ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['error' => true, 'mensaje' => mysqli_error($db)]);
        exit;
    }
    
    $materias = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $materias[] = array(
            'id_mat' => $fila['id_mat'],
            'nom_mat' => $fila['nom_mat'],
            'abb_mat' => $fila['abb_mat'],
            'cre_mat' => $fila['cre_mat'],
            'est_mat' => $fila['est_mat']
        );
    }
    
    echo json_encode(['success' => true, 'materias' => $materias]);
    exit;
}

// ========== 2. GUARDAR NUEVA MATERIA ==========
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_materia') {
    $id_ram2 = intval($_POST['id_ram2']);
    $nom_mat = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nom_mat'])));
    $abb_mat = strtoupper(trim(mysqli_real_escape_string($db, $_POST['abb_mat'])));
    $cre_mat = intval($_POST['cre_mat']);
    $est_mat = mysqli_real_escape_string($db, $_POST['est_mat']);
    
    // Validaciones
    if (empty($nom_mat)) {
        echo json_encode(['success' => false, 'message' => 'El nombre de la materia es obligatorio']);
        exit;
    }
    
    if ($id_ram2 <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de programa no válido']);
        exit;
    }
    
    // Validar duplicado
    $sqlCheck = "SELECT id_mat FROM materia WHERE UPPER(nom_mat) = '$nom_mat' AND id_ram2 = $id_ram2 LIMIT 1";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (mysqli_num_rows($resCheck) > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una materia con ese nombre en el programa']);
        exit;
    }
    
    // Insertar
    $sql = "
        INSERT INTO materia (
            nom_mat, abb_mat, cre_mat, est_mat, id_ram2
        ) VALUES (
            '$nom_mat', '$abb_mat', $cre_mat, '$est_mat', $id_ram2
        )
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        $id_mat = mysqli_insert_id($db);
        echo json_encode([
            'success' => true,
            'message' => 'Materia guardada correctamente',
            'id_mat' => $id_mat
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== 3. EDITAR MATERIA ==========
if (isset($_POST['accion']) && $_POST['accion'] === 'editar_materia') {
    $id_mat = intval($_POST['id_mat']);
    $nom_mat = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nom_mat'])));
    $abb_mat = strtoupper(trim(mysqli_real_escape_string($db, $_POST['abb_mat'])));
    $cre_mat = intval($_POST['cre_mat']);
    $est_mat = mysqli_real_escape_string($db, $_POST['est_mat']);
    $id_ram2 = intval($_POST['id_ram2']);
    
    // Validaciones
    if (empty($nom_mat) || $id_mat <= 0) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit;
    }
    
    // Validar duplicado (excluyendo el actual)
    $sqlCheck = "SELECT id_mat FROM materia WHERE UPPER(nom_mat) = '$nom_mat' AND id_ram2 = $id_ram2 AND id_mat != $id_mat LIMIT 1";
    $resCheck = mysqli_query($db, $sqlCheck);
    
    if (mysqli_num_rows($resCheck) > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otra materia con ese nombre en el programa']);
        exit;
    }
    
    // Actualizar
    $sql = "
        UPDATE materia SET
            nom_mat = '$nom_mat',
            abb_mat = '$abb_mat',
            cre_mat = $cre_mat,
            est_mat = '$est_mat'
        WHERE id_mat = $id_mat
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Materia actualizada correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== 4. ELIMINAR MATERIA ==========
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_materia') {
    $id_mat = intval($_POST['id_mat']);
    
    if ($id_mat <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de materia no válido']);
        exit;
    }
    
    // Validar: ¿Tiene alumnos inscritos?
    $sqlAlumnos = "SELECT COUNT(*) AS total FROM alu_mat WHERE id_mat3 = $id_mat";
    $resAlumnos = mysqli_query($db, $sqlAlumnos);
    $filaAlumnos = mysqli_fetch_assoc($resAlumnos);
    
    if ($filaAlumnos['total'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar: tiene ' . $filaAlumnos['total'] . ' alumno(s) inscrito(s)'
        ]);
        exit;
    }
    
    // Eliminar
    $sql = "DELETE FROM materia WHERE id_mat = $id_mat";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Materia eliminada correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========== DEFAULT ==========
echo json_encode(['error' => true, 'mensaje' => 'Acción no especificada']);
?>