<?php  
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_eje = $id;
$accion = $_POST['accion'];

switch($accion) {
    case 'contador':
        obtener_contador($db, $id_eje);
        break;
        
    case 'listar':
        listar_notificaciones($db, $id_eje);
        break;
        
    case 'cargar_mas':
        $offset = $_POST['offset'];
        cargar_mas_notificaciones($db, $id_eje, $offset);
        break;
        
    case 'detalle':
        $id_notificacion = $_POST['id_notificacion'];
        obtener_detalle($db, $id_eje, $id_notificacion);
        break;
        
    case 'limpiar_todas':
        limpiar_todas($db, $id_eje);
        break;
        
    default:
        echo "Acción no válida";
        break;
}

function obtener_contador($db, $id_eje) {
    $query = "SELECT COUNT(*) as total FROM notificacion_ejecutivo WHERE id_eje = '$id_eje' AND est_not_eje = 'Pendiente'";
    $resultado = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($resultado);
    
    echo $row['total'];
}

function listar_notificaciones($db, $id_eje) {
    $query = "SELECT * FROM notificacion_ejecutivo WHERE id_eje = '$id_eje' ORDER BY fec_not_eje DESC LIMIT 10";
    $resultado = mysqli_query($db, $query);
    
    if(mysqli_num_rows($resultado) > 0) {
        while($row = mysqli_fetch_assoc($resultado)) {
            mostrar_notificacion($row);
        }
    } else {
        echo '<div class="dropdown-item text-center text-muted">No hay notificaciones</div>';
    }
}

function cargar_mas_notificaciones($db, $id_eje, $offset) {
    $query = "SELECT * FROM notificacion_ejecutivo WHERE id_eje = '$id_eje' ORDER BY fec_not_eje DESC LIMIT 10 OFFSET $offset";
    $resultado = mysqli_query($db, $query);
    
    if(mysqli_num_rows($resultado) > 0) {
        while($row = mysqli_fetch_assoc($resultado)) {
            mostrar_notificacion($row);
        }
    } else {
        echo '<div class="dropdown-item text-center text-muted"><small>No hay más notificaciones</small></div>';
    }
}

function mostrar_notificacion($row) {
    $id_not = $row['id_not_eje'];
    $titulo = $row['tit_not_eje'];
    $mensaje = $row['men_not_eje'];
    $fecha = date('d/m/Y H:i', strtotime($row['fec_not_eje']));
    $estado = $row['est_not_eje'];
    
    // Preview del mensaje (primeros 80 caracteres)
    $preview = strlen($mensaje) > 80 ? substr($mensaje, 0, 80) . '...' : $mensaje;
    
    // Estilos según el estado
    if($estado == 'Pendiente') {
        $clase_estado = 'fw-bold';
        $icono_estado = '<span class="badge bg-primary rounded-circle" style="width: 8px; height: 8px; padding: 0; margin-right: 8px; display: inline-block; vertical-align: middle;"></span>';
        $background_item = 'style="background-color: rgba(13, 110, 253, 0.08);"';
    } else {
        $clase_estado = '';
        $icono_estado = '<span style="width: 8px; height: 8px; background-color: #adb5bd; border-radius: 50%; margin-right: 8px; display: inline-block; vertical-align: middle;"></span>';
        $background_item = '';
    }
    
    echo '
    <a href="javascript:void(0);" class="dropdown-item notify-item notificacion_item waves-effect ' . $clase_estado . '" data-id="' . $id_not . '" ' . $background_item . '>
        <div class="notify-icon bg-primary">
            <i class="fe-bell"></i>
        </div>
        <p class="notify-details">
            ' . $icono_estado . $titulo . '
            <small class="text-muted d-block">' . $preview . '</small>
            <small class="text-muted float-end">' . $fecha . '</small>
        </p>
    </a>';
}

function obtener_detalle($db, $id_eje, $id_notificacion) {
    // Obtener la notificación
    $query = "SELECT * FROM notificacion_ejecutivo WHERE id_not_eje = '$id_notificacion' AND id_eje = '$id_eje'";
    $resultado = mysqli_query($db, $query);
    
    if($row = mysqli_fetch_assoc($resultado)) {
        $titulo = $row['tit_not_eje'];
        $mensaje = $row['men_not_eje'];
        $fecha = date('d/m/Y H:i:s', strtotime($row['fec_not_eje']));
        
        // Marcar como leída
        $update_query = "UPDATE notificacion_ejecutivo SET est_not_eje = 'Leida' WHERE id_not_eje = '$id_notificacion'";
        mysqli_query($db, $update_query);
        
        // Formatear el mensaje en tabla
        $mensaje_formateado = formatear_mensaje_tabla($mensaje);
        
        echo '
        <div class="alert alert-info">
            <h5>' . $titulo . '</h5>
            <small class="text-muted">Fecha: ' . $fecha . '</small>
        </div>
        <div class="mt-3">
            ' . $mensaje_formateado . '
        </div>';
        
    } else {
        echo '<div class="alert alert-danger">Notificación no encontrada</div>';
    }
}

function formatear_mensaje_tabla($mensaje) {
    // Detectar y convertir URLs a enlaces con target="_blank"
    $mensaje = preg_replace(
        '/(https?:\/\/[^\s<>"{}|\\^`[\]]*)/i',
        '<a href="$1" target="_blank" style="color: #0d6efd; text-decoration: underline;">$1</a>',
        $mensaje
    );
    
    $tabla = '<table class="table table-bordered table-sm">
        <thead>
            <tr style="background-color: rgba(13, 110, 253, 0.1);">
                <th class="fw-bold" style="width: 35%; background-color: rgba(13, 110, 253, 0.1); color: #495057;">Campo</th>
                <th class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Información</th>
            </tr>
        </thead>
        <tbody>';
    
    // Extraer información usando regex
    if (preg_match('/ALUMNO:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Alumno</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/TELÉFONO:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Teléfono</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/PROGRAMA:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Programa</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/GENERACIÓN:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Generación</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/PAQUETE:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Paquete</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/MONTO TOTAL:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Monto Total</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/COMISIÓN TOTAL:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Comisión Total</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/CONSULTOR:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Consultor</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    if (preg_match('/FOLIO CITA:\s*([^\r\n]+)/i', $mensaje, $matches)) {
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Folio</td>
            <td class="fw-bold" style="background-color: #f8f9fa; color: #343a40;">' . trim($matches[1]) . '</td>
        </tr>';
    }
    
    // Buscar enlace de consulta alumno (usando regex que funciona)
    if (preg_match('/CONSULTA ALUMNO:(.*?)(https?:\/\/\S+)/i', $mensaje, $matches)) {
        $url_limpia = trim($matches[2]);
        $enlace = '<a href="' . $url_limpia . '" target="_blank" style="color: #0d6efd; text-decoration: underline;">Ver detalle del alumno</a>';
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Consulta Alumno</td>
            <td style="background-color: #f8f9fa;">' . $enlace . '</td>
        </tr>';
    }
    
    // Buscar enlace de solicitud inscripción
    if (preg_match('/SOLICITUD DE INSCRIPCIÓN:(.*?)(https?:\/\/\S+)/i', $mensaje, $matches)) {
        $url_limpia = trim($matches[2]);
        $enlace = '<a href="' . $url_limpia . '" target="_blank" style="color: #0d6efd; text-decoration: underline;">Ver solicitud de inscripción</a>';
        $tabla .= '<tr>
            <td class="fw-bold" style="background-color: rgba(13, 110, 253, 0.1); color: #495057;">Solicitud Inscripción</td>
            <td style="background-color: #f8f9fa;">' . $enlace . '</td>
        </tr>';
    }
    
    $tabla .= '</tbody></table>';
    
    return $tabla;
}

function limpiar_todas($db, $id_eje) {
    $query = "UPDATE notificacion_ejecutivo SET est_not_eje = 'Leida' WHERE id_eje = '$id_eje' AND est_not_eje = 'Pendiente'";
    mysqli_query($db, $query);
    
    echo "OK";
}

?>