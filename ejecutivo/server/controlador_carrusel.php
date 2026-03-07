<?php
// ====================================
// 🔧 DEBUGGING
// ====================================
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

function debug_log($data, $label = 'DEBUG') {
    error_log("[$label] " . json_encode($data, JSON_UNESCAPED_UNICODE));
}

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Función para sanitizar inputs
function sanitize_input($data) {
    global $db;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($db, $data);
}

if (!isset($_POST['accion'])) {
    debug_log(['error' => 'No se especificó la acción'], 'ERROR');
    echo json_encode(['success' => false, 'mensaje' => 'No se especificó la acción']);
    exit;
}

$accion = $_POST['accion'];
debug_log(['accion' => $accion], 'REQUEST');

// ========================================
// 🎯 OBTENER TRAZABILIDAD
// ========================================
if ($accion == 'obtener_trazabilidad') {
    $id_gen = sanitize_input($_POST['id_gen']);
    
    $sql = "
        SELECT 
            g.id_gen, g.nom_gen, g.ini_gen, g.fin_gen, g.id_ram5,
            r.id_ram, r.nom_ram, r.gra_ram, r.id_pla1,
            p.id_pla, p.nom_pla, p.id_cad1,
            c.id_cad, c.nom_cad
        FROM generacion g
        INNER JOIN rama r ON g.id_ram5 = r.id_ram
        INNER JOIN plantel p ON r.id_pla1 = p.id_pla
        INNER JOIN cadena c ON p.id_cad1 = c.id_cad
        WHERE g.id_gen = '$id_gen'
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        debug_log(['mysql_error' => mysqli_error($db)], 'MYSQL_ERROR');
        echo json_encode(['success' => false, 'mensaje' => 'Error en BD: ' . mysqli_error($db)]);
        exit;
    }
    
    if ($fila = mysqli_fetch_assoc($resultado)) {
        debug_log(['success' => true], 'SUCCESS');
        echo json_encode(['success' => true, 'datos' => $fila]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'No se encontró la generación']);
    }
    exit;
}

// ========================================
// 🏛️ OBTENER PLANTELES
// ========================================
if ($accion == 'obtener_planteles') {
    $id_eje = $id;
    
    $plantelesArray = array();
    
    $sqlPlantelesEje = "
        SELECT DISTINCT p.id_pla, p.nom_pla 
        FROM plantel p
        INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
        WHERE pe.id_eje = '$id_eje'
        ORDER BY p.nom_pla
    ";
    
    $resultadoPlantelesEje = mysqli_query($db, $sqlPlantelesEje);
    
    if (!$resultadoPlantelesEje) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . mysqli_error($db)]);
        exit;
    }
    
    if(mysqli_num_rows($resultadoPlantelesEje) > 0) {
        while($fila = mysqli_fetch_assoc($resultadoPlantelesEje)) {
            $plantelesArray[] = $fila;
        }
    } else {
        $sqlPlantelDefault = "
            SELECT p.id_pla, p.nom_pla 
            FROM plantel p
            INNER JOIN ejecutivo e ON p.id_pla = e.id_pla
            WHERE e.id_eje = '$id_eje'
        ";
        
        $resultadoDefault = mysqli_query($db, $sqlPlantelDefault);
        if($fila = mysqli_fetch_assoc($resultadoDefault)) {
            $plantelesArray[] = $fila;
        }
    }
    
    echo json_encode(['success' => true, 'planteles' => $plantelesArray]);
    exit;
}

// ========================================
// 📋 OBTENER ELEMENTOS POR PLANTEL (TODOS)
// ========================================
if ($accion == 'obtener_por_plantel') {
    $id_pla = sanitize_input($_POST['id_pla']);
    
    // 🔥 SIN FILTRO DE est_car - TRAE TODOS
    $sql = "
        SELECT 
            id_car, tit_car, des_car, url_car, img_car,
            DATE_FORMAT(fec_car, '%d/%m/%Y %H:%i') as fecha_formateada,
            est_car, res_car
        FROM carrusel
        WHERE id_pla33 = '$id_pla'
        AND id_gen33 IS NULL
        ORDER BY fec_car DESC
    ";
    
    debug_log(['query' => $sql], 'QUERY_PLANTEL');
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . mysqli_error($db)]);
        exit;
    }
    
    $elementos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $elementos[] = $fila;
    }
    
    debug_log(['total_elementos' => count($elementos)], 'ELEMENTOS_PLANTEL');
    
    echo json_encode(['success' => true, 'elementos' => $elementos]);
    exit;
}

// ========================================
// 🎓 OBTENER ELEMENTOS POR GENERACIÓN (TODOS)
// ========================================
if ($accion == 'obtener_por_generacion') {
    $id_gen = sanitize_input($_POST['id_gen']);
    
    // 🔥 SIN FILTRO DE est_car - TRAE TODOS
    $sql = "
        SELECT 
            id_car, tit_car, des_car, url_car, img_car,
            DATE_FORMAT(fec_car, '%d/%m/%Y %H:%i') as fecha_formateada,
            est_car, res_car
        FROM carrusel
        WHERE id_gen33 = '$id_gen'
        ORDER BY fec_car DESC
    ";
    
    debug_log(['query' => $sql], 'QUERY_GENERACION');
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . mysqli_error($db)]);
        exit;
    }
    
    $elementos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $elementos[] = $fila;
    }
    
    debug_log(['total_elementos' => count($elementos)], 'ELEMENTOS_GENERACION');
    
    echo json_encode(['success' => true, 'elementos' => $elementos]);
    exit;
}

// ========================================
// 🔍 OBTENER CADENA DE PLANTEL
// ========================================
if ($accion == 'obtener_cadena_plantel') {
    $id_pla = intval($_POST['id_pla']);
    
    $sql = "SELECT id_cad1 as id_cad FROM plantel WHERE id_pla = $id_pla";
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . mysqli_error($db)]);
        exit;
    }
    
    if ($fila = mysqli_fetch_assoc($resultado)) {
        echo json_encode(['success' => true, 'id_cad' => $fila['id_cad']]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'No se encontró el plantel']);
    }
    exit;
}

// ========================================
// ➕ CREAR ELEMENTO
// ========================================
if ($accion == 'crear') {
    debug_log(['FILES' => $_FILES], 'CREAR_INICIO');
    
    if (!isset($_FILES['img_car']['name']) || $_FILES['img_car']['error'] != 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Debes seleccionar una imagen válida']);
        exit;
    }
    
    $tit_car = sanitize_input($_POST['tit_car']);
    $des_car = sanitize_input($_POST['des_car']);
    $url_car = sanitize_input($_POST['url_car']);
    $id_cad33 = intval($_POST['id_cad33']);
    $id_pla33 = isset($_POST['id_pla33']) && $_POST['id_pla33'] != '' ? intval($_POST['id_pla33']) : 'NULL';
    $id_ram33 = isset($_POST['id_ram33']) && $_POST['id_ram33'] != '' ? intval($_POST['id_ram33']) : 'NULL';
    $id_gen33 = isset($_POST['id_gen33']) && $_POST['id_gen33'] != '' ? intval($_POST['id_gen33']) : 'NULL';
    $res_car = $nombreCompleto;
    
    if (empty($tit_car)) {
        echo json_encode(['success' => false, 'mensaje' => 'El título es obligatorio']);
        exit;
    }
    
    $archivo = $_FILES['img_car']['name'];
    $extension = pathinfo($archivo, PATHINFO_EXTENSION);
    $extensionesValidas = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    
    if (!in_array(strtolower($extension), $extensionesValidas)) {
        echo json_encode(['success' => false, 'mensaje' => 'Formato no válido. Usa: JPG, PNG, WEBP']);
        exit;
    }
    
    if ($_FILES['img_car']['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'mensaje' => 'La imagen no debe superar 5MB']);
        exit;
    }
    
    $nombreArchivo = 'carrusel_' . time() . '_' . uniqid() . '.' . $extension;
    $carpetaDestino = '../../img/';
    $rutaCompleta = $carpetaDestino . $nombreArchivo;
    
    $guardado = move_uploaded_file($_FILES['img_car']['tmp_name'], $rutaCompleta);
    
    if (!$guardado) {
        debug_log(['error' => 'move_uploaded_file falló'], 'UPLOAD_ERROR');
        echo json_encode(['success' => false, 'mensaje' => 'Error al guardar la imagen']);
        exit;
    }
    
    $img_car = $nombreArchivo;
    
    $sql = "
        INSERT INTO carrusel (
            tit_car, des_car, url_car, img_car, res_car, 
            id_cad33, id_pla33, id_ram33, id_gen33
        ) VALUES (
            '$tit_car', '$des_car', '$url_car', '$img_car', '$res_car',
            $id_cad33, $id_pla33, $id_ram33, $id_gen33
        )
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        debug_log(['id' => mysqli_insert_id($db)], 'INSERT_SUCCESS');
        echo json_encode(['success' => true, 'mensaje' => 'Elemento agregado correctamente']);
    } else {
        debug_log(['error' => mysqli_error($db)], 'MYSQL_ERROR');
        
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }
        
        echo json_encode(['success' => false, 'mensaje' => 'Error en BD: ' . mysqli_error($db)]);
    }
    exit;
}

// ========================================
// 🔄 CAMBIAR ESTATUS
// ========================================
if ($accion == 'cambiar_estatus') {
    $id_car = intval($_POST['id_car']);
    $nuevo_estatus = sanitize_input($_POST['estatus']);
    
    debug_log(['id_car' => $id_car, 'nuevo_estatus' => $nuevo_estatus], 'CAMBIAR_ESTATUS');
    
    $sql = "UPDATE carrusel SET est_car = '$nuevo_estatus' WHERE id_car = $id_car";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode(['success' => true, 'mensaje' => 'Estatus actualizado']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . mysqli_error($db)]);
    }
    exit;
}

// ========================================
// 🗑️ ELIMINAR (DELETE FÍSICO DE LA BD)
// ========================================
if ($accion == 'eliminar') {
    $id_car = intval($_POST['id_car']);
    
    debug_log(['id_car' => $id_car, 'accion' => 'ELIMINAR_FISICO'], 'ELIMINAR_INICIO');
    
    // Primero obtener el nombre de la imagen para eliminarla físicamente
    $sqlObtener = "SELECT img_car FROM carrusel WHERE id_car = $id_car";
    $resultadoObtener = mysqli_query($db, $sqlObtener);
    
    if (!$resultadoObtener) {
        debug_log(['error' => mysqli_error($db)], 'ERROR_OBTENER_IMAGEN');
        echo json_encode(['success' => false, 'mensaje' => 'Error al obtener la imagen: ' . mysqli_error($db)]);
        exit;
    }
    
    $filaImagen = mysqli_fetch_assoc($resultadoObtener);
    
    if (!$filaImagen) {
        debug_log(['error' => 'No se encontró el registro'], 'ERROR_NO_EXISTE');
        echo json_encode(['success' => false, 'mensaje' => 'El elemento no existe']);
        exit;
    }
    
    $nombreImagen = $filaImagen['img_car'];
    
    // Eliminar el registro de la base de datos
    $sqlEliminar = "DELETE FROM carrusel WHERE id_car = $id_car";
    $resultadoEliminar = mysqli_query($db, $sqlEliminar);
    
    if ($resultadoEliminar) {
        debug_log(['success' => true, 'registros_afectados' => mysqli_affected_rows($db)], 'ELIMINADO_BD');
        
        // Eliminar la imagen física del servidor
        $rutaImagen = '../../img/' . $nombreImagen;
        
        if (file_exists($rutaImagen)) {
            if (unlink($rutaImagen)) {
                debug_log(['imagen_eliminada' => $nombreImagen], 'IMAGEN_FISICA_ELIMINADA');
            } else {
                debug_log(['warning' => 'No se pudo eliminar la imagen física', 'ruta' => $rutaImagen], 'WARNING_IMAGEN');
            }
        } else {
            debug_log(['warning' => 'La imagen física no existe', 'ruta' => $rutaImagen], 'WARNING_NO_EXISTE_IMAGEN');
        }
        
        echo json_encode(['success' => true, 'mensaje' => 'Elemento eliminado correctamente']);
    } else {
        debug_log(['error' => mysqli_error($db)], 'ERROR_ELIMINAR_BD');
        echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar: ' . mysqli_error($db)]);
    }
    exit;
}

echo json_encode(['success' => false, 'mensaje' => 'Acción no válida: ' . $accion]);
?>