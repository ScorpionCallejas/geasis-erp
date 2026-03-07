<?php  
// CONTROLADOR DE DOCUMENTACIÓN - ALUMNOS Y PROGRAMAS
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ========================================================================
// SECCIÓN ALUMNOS: OBTENER DOCUMENTOS DEL ALUMNO
// ========================================================================
if(isset($_POST['obtener_documentos_alumno'])) {
    $id_alu_ram = intval($_POST['id_alu_ram']);
    
    if(!$id_alu_ram) {
        echo json_encode(['error' => true, 'mensaje' => 'ID de alumno no válido']);
        exit;
    }
    
    $sql = "
        SELECT 
            documento_alu_ram.id_doc_alu_ram,
            documento_alu_ram.est_doc_alu_ram,
            documento_alu_ram.fec_doc_alu_ram,
            documento_alu_ram.arc_doc_alu_ram,
            documento_rama.nom_doc_ram,
            documento_rama.est_doc_ram as doc_activo
        FROM documento_alu_ram 
        INNER JOIN documento_rama ON documento_rama.id_doc_ram = documento_alu_ram.id_doc_ram1
        WHERE documento_alu_ram.id_alu_ram11 = $id_alu_ram 
        AND documento_rama.est_doc_ram = 'Activo'
        ORDER BY documento_rama.nom_doc_ram ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['error' => true, 'mensaje' => mysqli_error($db)]);
        exit;
    }
    
    $documentos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $documentos[] = array(
            'id_doc_alu_ram' => $fila['id_doc_alu_ram'],
            'nom_doc_ram' => $fila['nom_doc_ram'],
            'est_doc_alu_ram' => $fila['est_doc_alu_ram'],
            'fec_doc_alu_ram' => $fila['fec_doc_alu_ram'],
            'arc_doc_alu_ram' => $fila['arc_doc_alu_ram'],
            'tiene_archivo' => !empty($fila['arc_doc_alu_ram']),
            'fecha_formateada' => $fila['fec_doc_alu_ram'] ? date('d/m/Y', strtotime($fila['fec_doc_alu_ram'])) : null,
            'url_archivo' => !empty($fila['arc_doc_alu_ram']) ? "../uploads/{$fila['arc_doc_alu_ram']}" : null
        );
    }
    
    echo json_encode(['success' => true, 'documentos' => $documentos]);
    exit;
}

// ========================================================================
// SECCIÓN ALUMNOS: CAMBIAR ESTADO DOCUMENTO
// ========================================================================
elseif(isset($_POST['cambiar_estado_documento'])) {
    $id_doc_alu_ram = intval($_POST['id_doc_alu_ram']);
    $nuevo_estado = mysqli_real_escape_string($db, $_POST['nuevo_estado']);
    
    $estados_validos = array('Pendiente', 'Entregado', 'Aprobado');
    if (!in_array($nuevo_estado, $estados_validos)) {
        echo json_encode(['success' => false, 'mensaje' => 'Estado no válido']);
        exit;
    }
    
    $fecha_sql = "";
    if ($nuevo_estado === 'Entregado') {
        $fecha_sql = ", fec_doc_alu_ram = NOW()";
    }
    
    $sql = "
        UPDATE documento_alu_ram 
        SET est_doc_alu_ram = '$nuevo_estado' $fecha_sql
        WHERE id_doc_alu_ram = $id_doc_alu_ram
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    echo json_encode([
        'success' => $resultado,
        'mensaje' => $resultado ? 'Estado actualizado correctamente' : 'Error al actualizar estado',
        'nuevo_estado' => $nuevo_estado
    ]);
    exit;
}

// ========================================================================
// SECCIÓN ALUMNOS: SUBIR ARCHIVO
// ========================================================================
elseif(isset($_FILES['archivo_documento']) && isset($_POST['id_doc_alu_ram'])) {
    $id_doc_alu_ram = intval($_POST['id_doc_alu_ram']);
    $archivo = $_FILES['archivo_documento'];
    
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'mensaje' => 'Error en la carga del archivo']);
        exit;
    }
    
    if ($archivo['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'mensaje' => 'El archivo no puede superar 5MB']);
        exit;
    }
    
    $extensiones_permitidas = array('png', 'jpg', 'jpeg', 'pdf');
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $extensiones_permitidas)) {
        echo json_encode(['success' => false, 'mensaje' => 'Solo se permiten archivos PNG, JPG, JPEG y PDF']);
        exit;
    }
    
    $mimes_permitidos = array(
        'image/png', 'image/jpg', 'image/jpeg', 'application/pdf'
    );
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $archivo['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $mimes_permitidos)) {
        echo json_encode(['success' => false, 'mensaje' => 'Tipo de archivo no válido']);
        exit;
    }
    
    $nombre_archivo = "documentacion-alu-ram-{$id_doc_alu_ram}.{$extension}";
    $ruta_destino = "../../uploads/{$nombre_archivo}";
    
    $directorio_uploads = "../../uploads/";
    if (!is_dir($directorio_uploads)) {
        mkdir($directorio_uploads, 0755, true);
    }
    
    // Eliminar archivo anterior si existe
    $sql_anterior = "SELECT arc_doc_alu_ram FROM documento_alu_ram WHERE id_doc_alu_ram = $id_doc_alu_ram";
    $resultado_anterior = mysqli_query($db, $sql_anterior);
    if ($resultado_anterior && mysqli_num_rows($resultado_anterior) > 0) {
        $archivo_anterior = mysqli_fetch_assoc($resultado_anterior)['arc_doc_alu_ram'];
        if (!empty($archivo_anterior) && file_exists("../../uploads/{$archivo_anterior}")) {
            unlink("../../uploads/{$archivo_anterior}");
        }
    }
    
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        $sql = "
            UPDATE documento_alu_ram 
            SET arc_doc_alu_ram = '$nombre_archivo',
                est_doc_alu_ram = 'Entregado',
                fec_doc_alu_ram = NOW()
            WHERE id_doc_alu_ram = $id_doc_alu_ram
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado) {
            echo json_encode([
                'success' => true, 
                'mensaje' => 'Archivo subido correctamente',
                'nombre_archivo' => $nombre_archivo,
                'url_archivo' => "../uploads/{$nombre_archivo}"
            ]);
        } else {
            unlink($ruta_destino);
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar base de datos']);
        }
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al guardar el archivo']);
    }
    exit;
}

// ========================================================================
// SECCIÓN ALUMNOS: ELIMINAR ARCHIVO
// ========================================================================
elseif(isset($_POST['eliminar_archivo_documento'])) {
    $id_doc_alu_ram = intval($_POST['id_doc_alu_ram']);
    
    $sql = "SELECT arc_doc_alu_ram FROM documento_alu_ram WHERE id_doc_alu_ram = $id_doc_alu_ram";
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado || mysqli_num_rows($resultado) === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Documento no encontrado']);
        exit;
    }
    
    $archivo_actual = mysqli_fetch_assoc($resultado)['arc_doc_alu_ram'];
    
    if (!empty($archivo_actual) && file_exists("../../uploads/{$archivo_actual}")) {
        unlink("../../uploads/{$archivo_actual}");
    }
    
    $sql_update = "
        UPDATE documento_alu_ram 
        SET arc_doc_alu_ram = NULL,
            est_doc_alu_ram = 'Pendiente',
            fec_doc_alu_ram = NULL
        WHERE id_doc_alu_ram = $id_doc_alu_ram
    ";
    
    $resultado_update = mysqli_query($db, $sql_update);
    
    echo json_encode([
        'success' => $resultado_update,
        'mensaje' => $resultado_update ? 'Archivo eliminado correctamente' : 'Error al eliminar archivo'
    ]);
    exit;
}

// ========================================================================
// SECCIÓN PROGRAMAS: OBTENER DOCUMENTOS DEL PROGRAMA
// ========================================================================
elseif(isset($_POST['accion']) && $_POST['accion'] === 'obtener_documentos') {
    $id_ram = intval($_POST['id_ram']);
    
    if(!$id_ram) {
        echo json_encode(['error' => true, 'mensaje' => 'ID de programa no válido']);
        exit;
    }
    
    $sql = "
        SELECT 
            id_doc_ram,
            nom_doc_ram,
            est_doc_ram,
            arc_doc_ram
        FROM documentacion_rama
        WHERE id_ram6 = $id_ram 
        ORDER BY nom_doc_ram ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['error' => true, 'mensaje' => mysqli_error($db)]);
        exit;
    }
    
    $documentos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $documentos[] = array(
            'id_doc_ram' => $fila['id_doc_ram'],
            'nom_doc_ram' => $fila['nom_doc_ram'],
            'est_doc_ram' => $fila['est_doc_ram'],
            'arc_doc_ram' => $fila['arc_doc_ram'],
            'tiene_archivo' => !empty($fila['arc_doc_ram'])
        );
    }
    
    echo json_encode(['success' => true, 'documentos' => $documentos]);
    exit;
}

// ========================================================================
// SECCIÓN PROGRAMAS: OBTENER UN DOCUMENTO ESPECÍFICO
// ========================================================================
elseif(isset($_POST['accion']) && $_POST['accion'] === 'obtener_documento') {
    $id_doc_ram = intval($_POST['id_doc_ram']);
    
    if(!$id_doc_ram) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de documento no válido']);
        exit;
    }
    
    $sql = "
        SELECT 
            id_doc_ram,
            nom_doc_ram,
            est_doc_ram,
            arc_doc_ram,
            id_ram6
        FROM documentacion_rama
        WHERE id_doc_ram = $id_doc_ram 
        LIMIT 1
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado || mysqli_num_rows($resultado) === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Documento no encontrado']);
        exit;
    }
    
    $documento = mysqli_fetch_assoc($resultado);
    
    echo json_encode(['success' => true, 'documento' => $documento]);
    exit;
}

// ========================================================================
// SECCIÓN PROGRAMAS: GUARDAR NUEVO DOCUMENTO
// ========================================================================
elseif(isset($_POST['accion']) && $_POST['accion'] === 'guardar_documento') {
    $id_ram6 = intval($_POST['id_ram6']);
    $nom_doc_ram = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nom_doc_ram'])));
    $est_doc_ram = mysqli_real_escape_string($db, $_POST['est_doc_ram']);
    
    // Validaciones
    if (empty($nom_doc_ram)) {
        echo json_encode(['success' => false, 'mensaje' => 'El nombre del documento es obligatorio']);
        exit;
    }
    
    if ($id_ram6 <= 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de programa no válido']);
        exit;
    }
    
    // Validar archivo (si se subió)
    $nombre_archivo = null;
    if (isset($_FILES['archivo_documento']) && $_FILES['archivo_documento']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivo_documento'];
        
        // Validar tamaño (10MB)
        if ($archivo['size'] > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'mensaje' => 'El archivo no puede superar 10MB']);
            exit;
        }
        
        // Validar extensión
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            echo json_encode(['success' => false, 'mensaje' => 'Solo se permiten archivos PDF']);
            exit;
        }
        
        // Validar MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);
        
        if ($mime_type !== 'application/pdf') {
            echo json_encode(['success' => false, 'mensaje' => 'El archivo debe ser un PDF válido']);
            exit;
        }
        
        // Insertar primero para obtener el ID
        $sql = "
            INSERT INTO documentacion_rama (
                nom_doc_ram, 
                est_doc_ram, 
                id_ram6
            ) VALUES (
                '$nom_doc_ram', 
                '$est_doc_ram', 
                $id_ram6
            )
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if (!$resultado) {
            echo json_encode(['success' => false, 'mensaje' => 'Error al guardar: ' . mysqli_error($db)]);
            exit;
        }
        
        $id_doc_ram = mysqli_insert_id($db);
        
        // Subir archivo
        $nombre_archivo = "doc-programa-{$id_ram6}-{$id_doc_ram}.pdf";
        $ruta_destino = "../../uploads/{$nombre_archivo}";
        
        $directorio_uploads = "../../uploads/";
        if (!is_dir($directorio_uploads)) {
            mkdir($directorio_uploads, 0755, true);
        }
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            // Actualizar con el nombre del archivo
            $sql_update = "
                UPDATE documentacion_rama 
                SET arc_doc_ram = '$nombre_archivo' 
                WHERE id_doc_ram = $id_doc_ram
            ";
            
            mysqli_query($db, $sql_update);
            
            echo json_encode([
                'success' => true,
                'mensaje' => 'Documento guardado correctamente',
                'id_doc_ram' => $id_doc_ram
            ]);
        } else {
            // Si falla el upload, eliminar el registro
            mysqli_query($db, "DELETE FROM documentacion_rama WHERE id_doc_ram = $id_doc_ram");
            echo json_encode(['success' => false, 'mensaje' => 'Error al subir el archivo']);
        }
        
    } else {
        // Sin archivo
        $sql = "
            INSERT INTO documentacion_rama (
                nom_doc_ram, 
                est_doc_ram, 
                id_ram6
            ) VALUES (
                '$nom_doc_ram', 
                '$est_doc_ram', 
                $id_ram6
            )
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado) {
            $id_doc_ram = mysqli_insert_id($db);
            echo json_encode([
                'success' => true,
                'mensaje' => 'Documento guardado correctamente',
                'id_doc_ram' => $id_doc_ram
            ]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al guardar: ' . mysqli_error($db)]);
        }
    }
    
    exit;
}

// ========================================================================
// SECCIÓN PROGRAMAS: EDITAR DOCUMENTO
// ========================================================================
elseif(isset($_POST['accion']) && $_POST['accion'] === 'editar_documento') {
    $id_doc_ram = intval($_POST['id_doc_ram']);
    $nom_doc_ram = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nom_doc_ram'])));
    $est_doc_ram = mysqli_real_escape_string($db, $_POST['est_doc_ram']);
    
    // Validaciones
    if (empty($nom_doc_ram) || $id_doc_ram <= 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Faltan campos obligatorios']);
        exit;
    }
    
    // Actualizar solo nombre y estatus (NO el archivo)
    $sql = "
        UPDATE documentacion_rama SET
            nom_doc_ram = '$nom_doc_ram',
            est_doc_ram = '$est_doc_ram'
        WHERE id_doc_ram = $id_doc_ram
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Documento actualizado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========================================================================
// SECCIÓN PROGRAMAS: ELIMINAR DOCUMENTO
// ========================================================================
elseif(isset($_POST['accion']) && $_POST['accion'] === 'eliminar_documento') {
    $id_doc_ram = intval($_POST['id_doc_ram']);
    
    if ($id_doc_ram <= 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de documento no válido']);
        exit;
    }
    
    // Obtener archivo para eliminarlo
    $sql = "SELECT arc_doc_ram FROM documentacion_rama WHERE id_doc_ram = $id_doc_ram";
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado || mysqli_num_rows($resultado) === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Documento no encontrado']);
        exit;
    }
    
    $archivo_actual = mysqli_fetch_assoc($resultado)['arc_doc_ram'];
    
    // Eliminar archivo físico si existe
    if (!empty($archivo_actual) && file_exists("../../uploads/{$archivo_actual}")) {
        unlink("../../uploads/{$archivo_actual}");
    }
    
    // Eliminar registro
    $sql_delete = "DELETE FROM documentacion_rama WHERE id_doc_ram = $id_doc_ram";
    $resultado_delete = mysqli_query($db, $sql_delete);
    
    if ($resultado_delete) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Documento eliminado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al eliminar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ========================================================================
// ACCIÓN NO ESPECIFICADA
// ========================================================================
else {
    echo json_encode(['error' => true, 'mensaje' => 'Acción no especificada']);
}
?>