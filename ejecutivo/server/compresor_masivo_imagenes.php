<?php
/**
 * 🗜️ COMPRESOR MASIVO CON TINYPNG - PHP 5.6
 * Rutas corregidas basadas en la estructura real
 */

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// 🔑 API KEY DE TINYPNG
define('TINYPNG_API_KEY', '9Qp4lvsQZ63dc2YR4S3kZvJnM3mS3nYM');

// 📝 LOG DE DEBUGGING
$logFile = dirname(__FILE__) . '/../../uploads/compresor_debug.log';
function escribirLog($mensaje) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $mensaje\n", FILE_APPEND);
}

switch($accion) {
    
    case 'OBTENER_ESTADISTICAS':
        try {
            $sqlTotal = "SELECT COUNT(*) as total FROM ejecutivo WHERE fot_eje IS NOT NULL AND fot_eje != '' AND eli_eje = 'Activo'";
            $resultTotal = mysqli_query($db, $sqlTotal);
            $filaTotal = mysqli_fetch_assoc($resultTotal);
            $total = intval($filaTotal['total']);
            
            $sqlFotos = "SELECT fot_eje FROM ejecutivo WHERE fot_eje IS NOT NULL AND fot_eje != '' AND eli_eje = 'Activo'";
            $resultFotos = mysqli_query($db, $sqlFotos);
            
            $tamanoTotal = 0;
            $contadorExistentes = 0;
            
            while($fila = mysqli_fetch_assoc($resultFotos)) {
                // RUTA: ejecutivo/server/ -> ../../uploads/
                $rutaFoto = dirname(__FILE__) . '/../../uploads/' . $fila['fot_eje'];
                if(file_exists($rutaFoto)) {
                    $tamanoTotal += filesize($rutaFoto);
                    $contadorExistentes++;
                }
            }
            
            echo json_encode(array(
                'success' => true,
                'total_imagenes' => $contadorExistentes,
                'tamano_total_mb' => round($tamanoTotal / 1048576, 2)
            ));
            
        } catch(Exception $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()));
        }
        exit;
        
    case 'COMPRIMIR_LOTE':
        try {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
            $limite = 10;
            
            escribirLog("=== LOTE offset=$offset ===");
            
            $sqlFotos = "SELECT id_eje, fot_eje FROM ejecutivo WHERE fot_eje IS NOT NULL AND fot_eje != '' AND eli_eje = 'Activo' ORDER BY id_eje LIMIT $limite OFFSET $offset";
            $resultFotos = mysqli_query($db, $sqlFotos);
            
            $procesadas = 0;
            $saltadas = 0;
            $errores = array();
            $tamanoOriginalTotal = 0;
            $tamanoComprimidoTotal = 0;
            
            while($fila = mysqli_fetch_assoc($resultFotos)) {
                $resultado = comprimirYReemplazarFotoTinyPNG($db, $fila['id_eje'], $fila['fot_eje']);
                
                if($resultado['success']) {
                    if(isset($resultado['saltado']) && $resultado['saltado']) {
                        $saltadas++;
                    } else {
                        $procesadas++;
                        $tamanoOriginalTotal += isset($resultado['tamano_anterior']) ? $resultado['tamano_anterior'] : 0;
                        $tamanoComprimidoTotal += isset($resultado['tamano_nuevo']) ? $resultado['tamano_nuevo'] : 0;
                    }
                } else {
                    $errores[] = array('id_eje' => $fila['id_eje'], 'error' => $resultado['error']);
                }
            }
            
            $ahorroMB = ($tamanoOriginalTotal - $tamanoComprimidoTotal) / 1048576;
            
            escribirLog("Lote OK: procesadas=$procesadas, saltadas=$saltadas, errores=" . count($errores));
            
            echo json_encode(array(
                'success' => true,
                'procesadas' => $procesadas,
                'saltadas' => $saltadas,
                'errores' => $errores,
                'offset_siguiente' => $offset + $limite,
                'estadisticas' => array(
                    'tamano_original_total' => $tamanoOriginalTotal,
                    'tamano_comprimido_total' => $tamanoComprimidoTotal,
                    'ahorro_mb' => $ahorroMB
                )
            ));
            
        } catch(Exception $e) {
            escribirLog("ERROR: " . $e->getMessage());
            echo json_encode(array('success' => false, 'error' => $e->getMessage()));
        }
        exit;
        
    default:
        echo json_encode(array('success' => false, 'error' => 'Acción inválida'));
        exit;
}

/**
 * 🗜️ COMPRIMIR CON TINYPNG
 */
function comprimirYReemplazarFotoTinyPNG($db, $id_eje, $foto_actual) {
    // RUTA ACTUAL: ejecutivo/server/ -> ../../uploads/foto.jpg
    $rutaActual = dirname(__FILE__) . '/../../uploads/' . $foto_actual;
    
    escribirLog("Procesando: $foto_actual (ID: $id_eje)");
    
    // ✅ Validar existencia
    if(!file_exists($rutaActual)) {
        escribirLog("ERROR: Archivo no existe - $rutaActual");
        return array('success' => false, 'error' => 'Archivo no existe');
    }
    
    $tamanoOriginal = filesize($rutaActual);
    
    // ✅ Saltar pequeñas
    if($tamanoOriginal < 102400) {
        escribirLog("Saltada (pequeña): " . round($tamanoOriginal/1024, 2) . "KB");
        return array('success' => true, 'saltado' => true);
    }
    
    escribirLog("Enviando a TinyPNG: " . round($tamanoOriginal/1024, 2) . "KB");
    
    // 🚀 ENVIAR A TINYPNG
    $input = file_get_contents($rutaActual);
    
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://api.tinify.com/shrink",
        CURLOPT_USERPWD => "api:9Qp4lvsQZ63dc2YR4S3kZvJnM3mS3nYM",
        CURLOPT_POSTFIELDS => $input,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 60
    ));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    // ❌ Error cURL
    if($curlError) {
        escribirLog("ERROR cURL: $curlError");
        return array('success' => false, 'error' => 'cURL: ' . $curlError);
    }
    
    // ❌ Error HTTP
    if($httpCode !== 201) {
        $errorData = json_decode($response, true);
        $errorMsg = isset($errorData['message']) ? $errorData['message'] : 'HTTP ' . $httpCode;
        
        if($httpCode == 429) {
            escribirLog("LÍMITE ALCANZADO");
            return array('success' => false, 'error' => 'Límite 500/mes alcanzado');
        }
        
        if($httpCode == 401) {
            escribirLog("API KEY INVÁLIDA");
            return array('success' => false, 'error' => 'API Key inválida');
        }
        
        escribirLog("ERROR TinyPNG: $errorMsg");
        return array('success' => false, 'error' => $errorMsg);
    }
    
    escribirLog("TinyPNG OK (201)");
    
    // ✅ Extraer URL del JSON
    $responseData = json_decode($response, true);
    
    if(!isset($responseData['output']['url'])) {
        escribirLog("ERROR: No hay URL en respuesta - " . substr($response, 0, 200));
        return array('success' => false, 'error' => 'No se obtuvo URL');
    }
    
    $urlComprimida = $responseData['output']['url'];
    escribirLog("URL: $urlComprimida");
    
    // 📥 Descargar imagen comprimida
    $imagenComprimida = file_get_contents($urlComprimida);
    
    if(!$imagenComprimida) {
        escribirLog("ERROR: No se descargó imagen");
        return array('success' => false, 'error' => 'Error al descargar');
    }
    
    escribirLog("Descargada: " . round(strlen($imagenComprimida)/1024, 2) . "KB");
    
    // 💾 Nuevo nombre con timestamp
    $pathinfo = pathinfo($foto_actual);
    $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : 'jpg';
    $nuevoNombre = 'foto-ejecutivo' . $id_eje . '-' . time() . rand(100, 999) . '.' . $extension;
    
    // RUTA NUEVA: ejecutivo/server/ -> ../../uploads/foto-nueva.jpg
    $rutaNueva = dirname(__FILE__) . '/../../uploads/' . $nuevoNombre;
    
    // 💾 Guardar
    file_put_contents($rutaNueva, $imagenComprimida);
    $tamanoNuevo = filesize($rutaNueva);
    $ahorroPorcentaje = round((($tamanoOriginal - $tamanoNuevo) / $tamanoOriginal) * 100, 2);
    
    escribirLog("Guardada: $nuevoNombre (" . round($tamanoNuevo/1024, 2) . "KB) Ahorro: $ahorroPorcentaje%");
    
    // 🔄 Actualizar BD
    $nuevoNombreEscapado = mysqli_real_escape_string($db, $nuevoNombre);
    $sqlUpdate = "UPDATE ejecutivo SET fot_eje = '$nuevoNombreEscapado' WHERE id_eje = $id_eje";
    $resultUpdate = mysqli_query($db, $sqlUpdate);
    
    if(!$resultUpdate) {
        escribirLog("ERROR BD: " . mysqli_error($db));
        unlink($rutaNueva);
        return array('success' => false, 'error' => 'Error BD');
    }
    
    escribirLog("BD actualizada");
    
    // 🗑️ Borrar vieja
    if($foto_actual !== $nuevoNombre && file_exists($rutaActual)) {
        unlink($rutaActual);
        escribirLog("Borrada vieja: $foto_actual");
    }
    
    escribirLog("COMPLETADO: $foto_actual -> $nuevoNombre");
    
    return array(
        'success' => true,
        'nuevo_nombre' => $nuevoNombre,
        'tamano_anterior' => $tamanoOriginal,
        'tamano_nuevo' => $tamanoNuevo,
        'ahorro_porcentaje' => $ahorroPorcentaje
    );
}
?>