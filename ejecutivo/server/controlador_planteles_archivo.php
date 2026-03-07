<?php
// Limpiar cualquier salida previa
ob_clean();

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Asegurar respuesta JSON
header('Content-Type: application/json');

// Verificar si es una acción de eliminación
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    // Verificar que se recibió el ID del archivo
    if (!isset($_POST['id_pla_arc']) || empty($_POST['id_pla_arc'])) {
        echo json_encode([
            'estatus' => 0,
            'mensaje' => 'Error: ID de archivo no especificado'
        ]);
        exit;
    }

    $id_pla_arc = mysqli_real_escape_string($db, $_POST['id_pla_arc']);

    // Obtener información del archivo antes de marcarlo como inactivo
    $sql_select = "SELECT arc_pla_arc FROM planteles_archivo WHERE id_pla_arc = '{$id_pla_arc}'";
    $result = mysqli_query($db, $sql_select);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        echo json_encode([
            'estatus' => 0,
            'mensaje' => 'Error: Archivo no encontrado'
        ]);
        exit;
    }
    
    $archivo_info = mysqli_fetch_assoc($result);
    $arc_pla_arc = $archivo_info['arc_pla_arc'];
    
    // Marcar el archivo como inactivo en la base de datos
    $sql_update = "UPDATE planteles_archivo SET est_pla_arc = 'Inactivo' WHERE id_pla_arc = '{$id_pla_arc}'";
    
    if (mysqli_query($db, $sql_update)) {
        // Eliminar físicamente el archivo
        $ruta_archivo = "../../img/archivos_plantel/{$arc_pla_arc}";
        $eliminado = false;
        
        if (file_exists($ruta_archivo)) {
            $eliminado = unlink($ruta_archivo);
        }
        
        echo json_encode([
            'estatus' => 1,
            'mensaje' => 'Archivo eliminado correctamente' . ($eliminado ? '' : ' (registro actualizado pero el archivo físico no se encontró)')
        ]);
    } else {
        echo json_encode([
            'estatus' => 0,
            'mensaje' => 'Error al eliminar el archivo: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// Código para la subida de archivos
// Verificar directorio de destino
$ruta_base = "../../img/archivos_plantel";
if (!file_exists($ruta_base)) {
    echo json_encode([
        'estatus' => 0,
        'mensaje' => 'Error: Directorio de destino no existe'
    ]);
    exit;
}

// Verificar datos básicos
if (!isset($_POST['nom_pla_arc']) || !isset($_FILES['archivo'])) {
    echo json_encode([
        'estatus' => 0,
        'mensaje' => 'Error: Faltan datos requeridos'
    ]);
    exit;
}

// Verificar carpeta
if (!isset($_POST['car_pla_arc']) || empty($_POST['car_pla_arc'])) {
    echo json_encode([
        'estatus' => 0,
        'mensaje' => 'Error: Debe seleccionar una carpeta'
    ]);
    exit;
}

// Verificar archivo
if ($_FILES['archivo']['error'] !== 0) {
    echo json_encode([
        'estatus' => 0,
        'mensaje' => 'Error al subir el archivo'
    ]);
    exit;
}

// Capturar datos comunes
$nom_pla_arc = $_POST['nom_pla_arc'];
$des_pla_arc = isset($_POST['des_pla_arc']) ? $_POST['des_pla_arc'] : NULL;
$car_pla_arc = $_POST['car_pla_arc'];
$archivo = $_FILES['archivo'];
$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
$tipo_archivo = isset($_POST['tipo_archivo']) ? $_POST['tipo_archivo'] : 'cde';

// Manejar archivo según su tipo (corporativo o CDE)
$errores = [];

if ($tipo_archivo === 'corporativo') {
    // Archivo corporativo
    $id_cad = isset($_POST['id_cad']) ? $_POST['id_cad'] : 1; // Valor por defecto para cadena
    
    try {
        // Insertar registro inicial para archivo corporativo
        $sql = "INSERT INTO planteles_archivo (
            nom_pla_arc, 
            des_pla_arc, 
            for_pla_arc, 
            est_pla_arc, 
            id_cad,
            id_eje,
            car_pla_arc
        ) VALUES (
            '" . mysqli_real_escape_string($db, $nom_pla_arc) . "',
            " . ($des_pla_arc ? "'" . mysqli_real_escape_string($db, $des_pla_arc) . "'" : "NULL") . ",
            '" . mysqli_real_escape_string($db, $extension) . "',
            'Activo',
            '" . mysqli_real_escape_string($db, $id_cad) . "',
            '" . mysqli_real_escape_string($db, $id) . "',
            '" . mysqli_real_escape_string($db, $car_pla_arc) . "'
        )";

        if (!mysqli_query($db, $sql)) {
            throw new Exception("Error en base de datos: " . mysqli_error($db));
        }

        // Obtener ID generado y crear nombre de archivo
        $id_pla_arc = mysqli_insert_id($db);
        $arc_pla_arc = "arc_corp_{$id_pla_arc}.{$extension}";
        $ruta_destino = "{$ruta_base}/{$arc_pla_arc}";

        // Copiar archivo
        if (!copy($archivo['tmp_name'], $ruta_destino)) {
            throw new Exception("Error al copiar archivo");
        }

        // Actualizar arc_pla_arc en la base de datos
        $sql_update = "UPDATE planteles_archivo 
                      SET arc_pla_arc = '" . mysqli_real_escape_string($db, $arc_pla_arc) . "' 
                      WHERE id_pla_arc = {$id_pla_arc}";

        if (!mysqli_query($db, $sql_update)) {
            unlink($ruta_destino);
            throw new Exception("Error al actualizar registro");
        }

        echo json_encode([
            'estatus' => 1,
            'mensaje' => 'Archivo corporativo guardado correctamente'
        ]);
        exit;

    } catch (Exception $e) {
        $errores[] = "Error al guardar archivo corporativo: " . $e->getMessage();
        
        // Limpiar registro si existe
        if (isset($id_pla_arc)) {
            mysqli_query($db, "DELETE FROM planteles_archivo WHERE id_pla_arc = {$id_pla_arc}");
        }
        
        // Limpiar archivo si existe
        if (isset($ruta_destino) && file_exists($ruta_destino)) {
            unlink($ruta_destino);
        }
        
        echo json_encode([
            'estatus' => 0,
            'mensaje' => implode(". ", $errores)
        ]);
        exit;
    }
} else {
    // Archivo de CDE específico
    $planteles = isset($_POST['planteles']) ? $_POST['planteles'] : [];
    
    if (empty($planteles)) {
        echo json_encode([
            'estatus' => 0,
            'mensaje' => 'Error: Debe seleccionar al menos un CDE'
        ]);
        exit;
    }
    
    // Procesar cada plantel
    $exitos = 0;
    foreach ($planteles as $id_pla) {
        try {
            // Insertar registro inicial
            $sql = "INSERT INTO planteles_archivo (
                nom_pla_arc, 
                des_pla_arc, 
                for_pla_arc, 
                est_pla_arc, 
                id_pla,
                id_eje,
                car_pla_arc
            ) VALUES (
                '" . mysqli_real_escape_string($db, $nom_pla_arc) . "',
                " . ($des_pla_arc ? "'" . mysqli_real_escape_string($db, $des_pla_arc) . "'" : "NULL") . ",
                '" . mysqli_real_escape_string($db, $extension) . "',
                'Activo',
                '" . mysqli_real_escape_string($db, $id_pla) . "',
                '" . mysqli_real_escape_string($db, $id) . "',
                '" . mysqli_real_escape_string($db, $car_pla_arc) . "'
            )";

            if (!mysqli_query($db, $sql)) {
                throw new Exception("Error en base de datos: " . mysqli_error($db));
            }

            // Obtener ID generado y crear nombre de archivo
            $id_pla_arc = mysqli_insert_id($db);
            $arc_pla_arc = "arc_pla_arc_{$id_pla_arc}.{$extension}";
            $ruta_destino = "{$ruta_base}/{$arc_pla_arc}";

            // Copiar archivo (solo la primera vez, después reutilizamos)
            if ($exitos === 0) {
                if (!copy($archivo['tmp_name'], $ruta_destino)) {
                    throw new Exception("Error al copiar archivo");
                }
            } else {
                // Copiar el primer archivo guardado
                $primer_ruta = "{$ruta_base}/arc_pla_arc_{$primer_id}.{$extension}";
                if (!copy($primer_ruta, $ruta_destino)) {
                    throw new Exception("Error al copiar archivo para múltiples CDE");
                }
            }

            // Actualizar arc_pla_arc en la base de datos
            $sql_update = "UPDATE planteles_archivo 
                          SET arc_pla_arc = '" . mysqli_real_escape_string($db, $arc_pla_arc) . "' 
                          WHERE id_pla_arc = {$id_pla_arc}";

            if (!mysqli_query($db, $sql_update)) {
                unlink($ruta_destino);
                throw new Exception("Error al actualizar registro");
            }
            
            // Guardar el primer ID para referencias posteriores
            if ($exitos === 0) {
                $primer_id = $id_pla_arc;
            }
            
            $exitos++;

        } catch (Exception $e) {
            $errores[] = "Error en CDE {$id_pla}: " . $e->getMessage();
            
            // Limpiar registro si existe
            if (isset($id_pla_arc)) {
                mysqli_query($db, "DELETE FROM planteles_archivo WHERE id_pla_arc = {$id_pla_arc}");
            }
            
            // Limpiar archivo si existe
            if (isset($ruta_destino) && file_exists($ruta_destino)) {
                unlink($ruta_destino);
            }
        }
    }
}

// Respuesta final para archivos CDE
if (empty($errores)) {
    echo json_encode([
        'estatus' => 1,
        'mensaje' => 'Archivo guardado correctamente en ' . $exitos . ' CDE(s)'
    ]);
} else {
    if ($exitos > 0) {
        echo json_encode([
            'estatus' => 1,
            'mensaje' => 'Archivo guardado parcialmente en ' . $exitos . ' de ' . count($planteles) . ' CDE(s). Errores: ' . implode(". ", $errores)
        ]);
    } else {
        echo json_encode([
            'estatus' => 0,
            'mensaje' => implode(". ", $errores)
        ]);
    }
}
?>