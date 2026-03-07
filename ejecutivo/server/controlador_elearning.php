<?php  
// ============================================
// ✅ CONFIGURACIÓN FORZADA PARA 150MB
// ============================================
@ini_set('upload_max_filesize', '200M');
@ini_set('post_max_size', '200M');
@ini_set('max_execution_time', 600);
@ini_set('max_input_time', 600);
@ini_set('memory_limit', '512M');

// ✅ VERIFICAR SI EL POST LLEGÓ VACÍO POR LÍMITE
if (empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
    ob_clean();
    echo json_encode(array(
        'error' => 'El archivo es demasiado grande para el servidor. Límite actual: ' . ini_get('post_max_size'),
        'content_length' => $_SERVER['CONTENT_LENGTH'],
        'post_max_size' => ini_get('post_max_size'),
        'upload_max_filesize' => ini_get('upload_max_filesize')
    ));
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

ob_start();

require('../inc/cabeceras.php');
require('../inc/funciones.php');

header('Content-Type: application/json; charset=utf-8');

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

// ID del ejecutivo logueado
$id = isset($_POST['id_eje']) ? $_POST['id_eje'] : (isset($id) ? $id : null);

// ============================================
// VALIDACIÓN INICIAL
// ============================================
if (empty($accion) || empty($tipo)) {
    ob_clean();
    echo json_encode(array(
        'error' => 'Faltan parámetros requeridos',
        'debug' => array(
            'accion' => $accion,
            'tipo' => $tipo
        )
    ));
    exit;
}

// ============================================
// ✅ CONSTANTES DE VALIDACIÓN - 150MB
// ============================================
define('MAX_SIZE_IMAGEN', 5 * 1024 * 1024);      // 5MB
define('MAX_SIZE_VIDEO', 150 * 1024 * 1024);     // 150MB
define('MAX_SIZE_AUDIO', 150 * 1024 * 1024);     // 150MB
define('MAX_SIZE_PDF', 150 * 1024 * 1024);       // 150MB
define('MAX_SIZE_ARCHIVO', 150 * 1024 * 1024);   // 150MB

// ============================================
// ✅ FUNCIÓN: VALIDAR ARCHIVO POR TIPO
// ============================================
function validarArchivo($archivo, $tipoEsperado) {
    $extensiones = array(
        'imagen' => array('jpg', 'jpeg', 'png', 'webp', 'gif'),
        'video' => array('mp4', 'webm', 'mov', 'avi', 'mkv', 'mpeg'),
        'audio' => array('mp3', 'wav', 'ogg', 'm4a', 'aac'),
        'pdf' => array('pdf'),
        'archivo' => array('mp4', 'webm', 'mov', 'avi', 'mkv', 'mp3', 'wav', 'ogg', 'm4a', 'pdf', 'jpg', 'jpeg', 'png', 'webp', 'mpeg')
    );
    
    $maxSizes = array(
        'imagen' => MAX_SIZE_IMAGEN,
        'video' => MAX_SIZE_VIDEO,
        'audio' => MAX_SIZE_AUDIO,
        'pdf' => MAX_SIZE_PDF,
        'archivo' => MAX_SIZE_ARCHIVO
    );
    
    // ✅ VALIDAR ERROR DE UPLOAD
    if (!isset($archivo['error'])) {
        return array('error' => 'Error: estructura de archivo inválida');
    }
    
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        $errores = array(
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el límite del servidor (upload_max_filesize)',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el límite del formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente. Intenta de nuevo',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Error del servidor: falta carpeta temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error del servidor: no se puede escribir en disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión PHP detuvo la subida'
        );
        
        $mensaje = isset($errores[$archivo['error']]) ? $errores[$archivo['error']] : 'Error desconocido: código ' . $archivo['error'];
        return array('error' => $mensaje);
    }
    
    // ✅ VALIDAR EXTENSIÓN
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $extensiones[$tipoEsperado])) {
        return array('error' => 'Extensión .' . $extension . ' no permitida. Permitidas: ' . implode(', ', $extensiones[$tipoEsperado]));
    }
    
    // ✅ VALIDAR TAMAÑO - 150MB PARA VIDEOS/AUDIOS
    if ($archivo['size'] > $maxSizes[$tipoEsperado]) {
        $maxMB = round($maxSizes[$tipoEsperado] / 1024 / 1024);
        $actualMB = round($archivo['size'] / 1024 / 1024, 2);
        return array('error' => "El archivo ({$actualMB}MB) excede los {$maxMB}MB permitidos");
    }
    
    return array('exito' => true, 'extension' => $extension);
}

// ============================================
// CURSOS - ALTA
// ============================================
if ($accion == "Alta" && $tipo == 'curso') {
    
    $nom_curso = mysqli_real_escape_string($db, $_POST['nom_curso']);
    $des_curso = mysqli_real_escape_string($db, isset($_POST['des_curso']) ? $_POST['des_curso'] : '');
    $duracion_estimada = isset($_POST['duracion_estimada']) && $_POST['duracion_estimada'] != '' ? intval($_POST['duracion_estimada']) : NULL;
    
    if (empty($nom_curso)) {
        ob_clean();
        echo json_encode(array('error' => 'El nombre del curso es requerido'));
        exit;
    }
    
    $sql = "INSERT INTO curso_elearning (nom_curso, des_curso, duracion_estimada, id_eje_creador) 
            VALUES ('$nom_curso', '$des_curso', ".($duracion_estimada ? $duracion_estimada : "NULL").", '$id_eje')";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => 'Error al crear curso: ' . mysqli_error($db)));
        exit;
    }
    
    $id_curso = mysqli_insert_id($db);
    
    // ✅ PROCESAR IMAGEN - MAX 5MB
    if (isset($_FILES['img_curso']) && $_FILES['img_curso']['error'] === UPLOAD_ERR_OK) {
        $validacion = validarArchivo($_FILES['img_curso'], 'imagen');
        
        if (isset($validacion['error'])) {
            mysqli_query($db, "DELETE FROM curso_elearning WHERE id_curso = '$id_curso'");
            ob_clean();
            echo json_encode($validacion);
            exit;
        }
        
        $archivo = $_FILES['img_curso'];
        $extension = $validacion['extension'];
        
        $contenido_archivo = file_get_contents($archivo['tmp_name']);
        $sha1_hash = sha1($contenido_archivo . $id_curso . time());
        $img_curso = "curso-{$id_curso}-{$sha1_hash}.{$extension}";
        
        $ruta = '../../uploads/' . $img_curso;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
            mysqli_query($db, "UPDATE curso_elearning SET img_curso = '$img_curso' WHERE id_curso = '$id_curso'");
        } else {
            // Si falla el move, eliminar el curso
            mysqli_query($db, "DELETE FROM curso_elearning WHERE id_curso = '$id_curso'");
            ob_clean();
            echo json_encode(array('error' => 'Error al guardar la imagen en el servidor'));
            exit;
        }
    }
    
    // INSERTAR RANGOS
    if (isset($_POST['rangos'])) {
        $rangos = is_array($_POST['rangos']) ? $_POST['rangos'] : explode(',', $_POST['rangos']);
        foreach ($rangos as $rango) {
            $rango_esc = mysqli_real_escape_string($db, trim($rango));
            if (!empty($rango_esc)) {
                mysqli_query($db, "INSERT INTO curso_rango_asignado (id_curso, rango_asignado, id_eje_asignador) 
                                  VALUES ('$id_curso', '$rango_esc', '$id_eje')");
            }
        }
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_curso' => $id_curso, 'mensaje' => 'Curso creado correctamente'));
    exit;
}

// ============================================
// CURSOS - CAMBIO
// ============================================
if ($accion == "Cambio" && $tipo == 'curso') {
    
    $id_curso = intval($_POST['id_curso']);
    $campo = isset($_POST['campo']) ? $_POST['campo'] : '';
    
    if ($campo == 'eliminar') {
        mysqli_query($db, "UPDATE curso_elearning SET eli_curso = 0 WHERE id_curso = '$id_curso'");
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Curso eliminado'));
        exit;
    }
    
    if (isset($_POST['nom_curso'])) {
        $nom_curso = mysqli_real_escape_string($db, $_POST['nom_curso']);
        $des_curso = mysqli_real_escape_string($db, isset($_POST['des_curso']) ? $_POST['des_curso'] : '');
        $duracion = isset($_POST['duracion_estimada']) && $_POST['duracion_estimada'] != '' ? intval($_POST['duracion_estimada']) : NULL;
        
        $sql = "UPDATE curso_elearning 
                SET nom_curso = '$nom_curso', 
                    des_curso = '$des_curso', 
                    duracion_estimada = ".($duracion ? $duracion : "NULL")." 
                WHERE id_curso = '$id_curso'";
        mysqli_query($db, $sql);
        
        // ACTUALIZAR RANGOS
        if (isset($_POST['rangos'])) {
            mysqli_query($db, "UPDATE curso_rango_asignado SET eli_curso_rango = 0 WHERE id_curso = '$id_curso'");
            
            $rangos = is_array($_POST['rangos']) ? $_POST['rangos'] : explode(',', $_POST['rangos']);
            foreach ($rangos as $rango) {
                $rango_esc = mysqli_real_escape_string($db, trim($rango));
                if (!empty($rango_esc)) {
                    $sql_check = "SELECT id_curso_rango FROM curso_rango_asignado 
                                 WHERE id_curso = '$id_curso' AND rango_asignado = '$rango_esc'";
                    $result_check = mysqli_query($db, $sql_check);
                    
                    if (mysqli_num_rows($result_check) > 0) {
                        mysqli_query($db, "UPDATE curso_rango_asignado SET eli_curso_rango = 1 
                                          WHERE id_curso = '$id_curso' AND rango_asignado = '$rango_esc'");
                    } else {
                        mysqli_query($db, "INSERT INTO curso_rango_asignado (id_curso, rango_asignado, id_eje_asignador) 
                                          VALUES ('$id_curso', '$rango_esc', '$id_eje')");
                    }
                }
            }
        }
    }
    
    // ✅ PROCESAR IMAGEN
    if (isset($_FILES['img_curso']) && $_FILES['img_curso']['error'] === UPLOAD_ERR_OK) {
        $validacion = validarArchivo($_FILES['img_curso'], 'imagen');
        
        if (isset($validacion['error'])) {
            ob_clean();
            echo json_encode($validacion);
            exit;
        }
        
        $archivo = $_FILES['img_curso'];
        $extension = $validacion['extension'];
        
        // ELIMINAR IMAGEN ANTERIOR
        $result = mysqli_query($db, "SELECT img_curso FROM curso_elearning WHERE id_curso = '$id_curso'");
        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['img_curso'] && file_exists("../../uploads/".$row['img_curso'])) {
                unlink("../../uploads/".$row['img_curso']);
            }
        }
        
        $contenido_archivo = file_get_contents($archivo['tmp_name']);
        $sha1_hash = sha1($contenido_archivo . $id_curso . time());
        $nueva_img = "curso-{$id_curso}-{$sha1_hash}.{$extension}";
        
        $ruta = '../../uploads/' . $nueva_img;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
            mysqli_query($db, "UPDATE curso_elearning SET img_curso = '$nueva_img' WHERE id_curso = '$id_curso'");
        }
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Curso actualizado'));
    exit;
}

// ============================================
// CURSOS - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'cursos_disponibles') {
    
    if (!isset($id_eje) || empty($id_eje)) {
        ob_clean();
        echo json_encode(array(
            'error' => 'ID ejecutivo no encontrado en sesión',
            'debug' => array('id_eje' => $id_eje)
        ));
        exit;
    }
    
    // OBTENER RANGO
    $sql_rango = "SELECT ran_eje FROM ejecutivo WHERE id_eje = '$id_eje'";
    $result_rango = mysqli_query($db, $sql_rango);
    
    if (!$result_rango) {
        ob_clean();
        echo json_encode(array(
            'error' => 'Error al consultar ejecutivo',
            'debug' => array('sql_error' => mysqli_error($db))
        ));
        exit;
    }
    
    $row_rango = mysqli_fetch_assoc($result_rango);
    
    if (!$row_rango || !isset($row_rango['ran_eje'])) {
        ob_clean();
        echo json_encode(array(
            'error' => 'Ejecutivo no encontrado o sin rango'
        ));
        exit;
    }
    
    $rango = $row_rango['ran_eje'];
    $busqueda = isset($_POST['busqueda']) ? mysqli_real_escape_string($db, $_POST['busqueda']) : '';
    
    $sql = "SELECT c.* FROM curso_elearning c WHERE c.eli_curso = 1";
    
    if ($busqueda != '') {
        $sql .= " AND (c.nom_curso LIKE '%$busqueda%' OR c.des_curso LIKE '%$busqueda%')";
    }
    
    $sql .= " ORDER BY c.fec_creacion_curso DESC";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        ob_clean();
        echo json_encode(array(
            'error' => 'Error en query de cursos',
            'debug' => array('sql_error' => mysqli_error($db))
        ));
        exit;
    }
    
    $cursos = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        
        // CREADOR + FOTO + RANGO
        $sql_creador = "SELECT nom_eje, fot_eje, ran_eje, usu_eje, est_eje FROM ejecutivo WHERE id_eje = '".$row['id_eje_creador']."'";
        $res_creador = mysqli_query($db, $sql_creador);
        $row_creador = mysqli_fetch_assoc($res_creador);
        $row['creador_nombre'] = $row_creador ? $row_creador['nom_eje'] : 'Desconocido';
        $row['creador_foto'] = ($row_creador && !empty($row_creador['fot_eje'])) ? $row_creador['fot_eje'] : null;
        $row['creador_rango'] = $row_creador ? $row_creador['ran_eje'] : null;
        $row['creador_usuario'] = $row_creador ? $row_creador['usu_eje'] : null;
        $row['creador_estatus'] = $row_creador ? $row_creador['est_eje'] : 'Activo';
        
        // TOTAL CLASES
        $sql_clases = "SELECT COUNT(*) as total FROM clase_elearning WHERE id_curso = '".$row['id_curso']."' AND eli_clase = 1";
        $res_clases = mysqli_query($db, $sql_clases);
        $row_clases = mysqli_fetch_assoc($res_clases);
        $row['total_clases'] = $row_clases ? $row_clases['total'] : '0';
        
        // TOTAL CONTENIDOS
        $sql_cont = "SELECT COUNT(co.id_contenido) as total 
                     FROM contenido_elearning co
                     INNER JOIN clase_elearning cl ON co.id_clase = cl.id_clase
                     WHERE cl.id_curso = '".$row['id_curso']."' AND co.eli_contenido = 1 AND cl.eli_clase = 1";
        $res_cont = mysqli_query($db, $sql_cont);
        $row_cont = mysqli_fetch_assoc($res_cont);
        $row['total_contenidos'] = $row_cont ? $row_cont['total'] : '0';
        
        // RANGOS ASIGNADOS
        $sql_rangos = "SELECT GROUP_CONCAT(rango_asignado ORDER BY rango_asignado SEPARATOR ', ') as rangos
                       FROM curso_rango_asignado 
                       WHERE id_curso = '".$row['id_curso']."' AND eli_curso_rango = 1";
        $res_rangos = mysqli_query($db, $sql_rangos);
        $row_rangos = mysqli_fetch_assoc($res_rangos);
        $row['rangos_asignados'] = ($row_rangos && $row_rangos['rangos']) ? $row_rangos['rangos'] : '';
        
        // VERIFICAR ACCESO POR RANGO
        if (!empty($row['rangos_asignados'])) {
            $rangos_array = explode(', ', $row['rangos_asignados']);
            if (!in_array($rango, $rangos_array)) {
                continue;
            }
        }
        
        // PROGRESO
        $sql_prog = "SELECT porcentaje_avance, fec_completado FROM progreso_curso 
                     WHERE id_eje = '$id_eje' AND id_curso = '".$row['id_curso']."'";
        $result_prog = mysqli_query($db, $sql_prog);
        $progreso = mysqli_fetch_assoc($result_prog);
        
        $row['progreso'] = $progreso ? $progreso['porcentaje_avance'] : '0';
        $row['completado'] = ($progreso && $progreso['fec_completado']) ? true : false;
        
        // VERIFICAR EXAMEN
        $sql_examen = "SELECT id_examen, nom_examen FROM examen_elearning 
                       WHERE id_curso = '".$row['id_curso']."' AND eli_examen = 1 LIMIT 1";
        $result_examen = mysqli_query($db, $sql_examen);
        $examen = mysqli_fetch_assoc($result_examen);
        
        $row['tiene_examen'] = $examen ? true : false;
        $row['id_examen'] = $examen ? $examen['id_examen'] : null;
        $row['nom_examen'] = $examen ? $examen['nom_examen'] : null;
        
        // ESTADO EXAMEN
        if ($examen) {
            $sql_intento = "SELECT cal_intento, aprobado FROM intento_examen 
                           WHERE id_examen = '".$examen['id_examen']."' AND id_ejecutivo = '$id_eje' 
                           AND est_intento = 'completado' ORDER BY fec_inicio_intento DESC LIMIT 1";
            $result_intento = mysqli_query($db, $sql_intento);
            $intento = mysqli_fetch_assoc($result_intento);
            
            if ($intento) {
                $row['estado_examen'] = $intento['aprobado'] ? 'APROBADO' : 'REPROBADO';
                $row['calificacion_examen'] = $intento['cal_intento'];
            } else {
                $row['estado_examen'] = 'PENDIENTE';
                $row['calificacion_examen'] = null;
            }
        }
        
        $cursos[] = $row;
    }
    
    ob_clean();
    echo json_encode(array(
        'cursos' => $cursos,
        'debug' => array(
            'total_cursos' => count($cursos),
            'rango_usuario' => $rango
        )
    ));
    exit;
}

// ============================================
// TRACKING: EJECUTIVOS QUE HAN CURSADO
// ✅ CON FILTRO POR CADENA Y PERMISOS
// ============================================

// ============================================
// TRACKING: EJECUTIVOS QUE HAN CURSADO
// ✅ CON FILTRO POR CADENA, PERMISOS Y ESTADO DEL EXAMEN
// ============================================
if ($accion == "Consulta" && $tipo == 'ejecutivos_curso') {
    $id_curso = isset($_POST['id_curso']) ? mysqli_real_escape_string($db, $_POST['id_curso']) : 0;
    
    if (!$id_curso) {
        ob_clean();
        echo json_encode(array('error' => 'ID de curso requerido'));
        exit;
    }
    
    // ✅ OBTENER DATOS DEL EJECUTIVO LOGEADO
    $sql_sesion = "SELECT id_pla, ran_eje, usu_eje 
                   FROM ejecutivo 
                   WHERE id_eje = '$id_eje'";
    $res_sesion = mysqli_query($db, $sql_sesion);
    $sesion = mysqli_fetch_assoc($res_sesion);
    
    if (!$sesion) {
        ob_clean();
        echo json_encode(array('error' => 'Sesión inválida'));
        exit;
    }
    
    $id_pla_sesion = $sesion['id_pla'];
    $ran_eje_sesion = $sesion['ran_eje'];
    $usu_eje_sesion = $sesion['usu_eje'];
    
    // ✅ DETERMINAR NIVEL DE ACCESO
    $es_admin_global = in_array($usu_eje_sesion, array('SISTEMAS', 'DIR. NEGOCIO', 'PRES. EJECUTIVA'));
    $es_admin_plantel = in_array($ran_eje_sesion, array('DM', 'GC', 'DC'));
    
    // ✅ OBTENER ID DEL EXAMEN ASOCIADO AL CURSO
    $sql_examen = "SELECT id_examen FROM examen_elearning 
                   WHERE id_curso = '$id_curso' AND eli_examen = 1 
                   LIMIT 1";
    $res_examen = mysqli_query($db, $sql_examen);
    $examen = mysqli_fetch_assoc($res_examen);
    $id_examen = $examen ? $examen['id_examen'] : null;
    
    // ✅ QUERY CON FILTROS DE PERMISOS Y ESTADO DEL EXAMEN
    $sql_ejecutivos = "
        SELECT 
            e.id_eje,
            e.nom_eje,
            e.app_eje,
            e.apm_eje,
            e.fot_eje,
            e.ran_eje,
            e.usu_eje,
            e.est_eje,
            e.id_pla,
            p.nom_pla,
            IFNULL(pc.porcentaje_avance, 0) as porcentaje_completado,
            pc.fec_inicio as fecha_inicio,
            pc.fec_completado as fecha_completado,
            pc.ultimo_contenido_visto as ultimo_contenido_id,
            CASE 
                WHEN pc.fec_completado IS NOT NULL THEN 'Completado'
                WHEN pc.porcentaje_avance > 0 THEN 'En Progreso'
                ELSE 'Sin Iniciar'
            END as estado_curso,
            -- ✅ DATOS DEL EXAMEN
            ie.cal_intento as calificacion_examen,
            ie.aprobado as examen_aprobado,
            ie.fec_fin_intento as fecha_examen,
            (SELECT COUNT(*) FROM intento_examen 
             WHERE id_examen = ".($id_examen ? "'$id_examen'" : "NULL")." 
             AND id_ejecutivo = e.id_eje 
             AND est_intento = 'completado') as total_intentos,
            CASE 
                WHEN ie.aprobado = 1 THEN 'Aprobado'
                WHEN ie.aprobado = 0 THEN 'Reprobado'
                ELSE 'Pendiente'
            END as estado_examen
        FROM ejecutivo e
        INNER JOIN plantel p ON e.id_pla = p.id_pla
        LEFT JOIN progreso_curso pc ON e.id_eje = pc.id_eje AND pc.id_curso = '$id_curso'
        LEFT JOIN (
            SELECT 
                id_ejecutivo,
                cal_intento,
                aprobado,
                fec_fin_intento,
                ROW_NUMBER() OVER (PARTITION BY id_ejecutivo ORDER BY fec_fin_intento DESC) as rn
            FROM intento_examen
            WHERE id_examen = ".($id_examen ? "'$id_examen'" : "NULL")."
            AND est_intento = 'completado'
        ) ie ON e.id_eje = ie.id_ejecutivo AND ie.rn = 1
        WHERE e.eli_eje = 'Activo' 
          AND e.tip_eje = 'Ejecutivo'
    ";
    
    // ✅ APLICAR FILTROS SEGÚN NIVEL DE ACCESO
    // if ($es_admin_global) {
    //     // SISTEMAS, DIR. NEGOCIO, PRES. EJECUTIVA → Ve TODA la cadena
    //     $sql_ejecutivos .= " AND p.id_cad1 = '$cadena'";
    // } else if ($es_admin_plantel) {
    //     // DM, GC, DC → Ve solo SU PLANTEL
    //     $sql_ejecutivos .= " AND e.id_pla = '$id_pla_sesion'";
    // } else {
    //     // OTROS RANGOS → Solo ve SU PROPIO PROGRESO
    //     $sql_ejecutivos .= " AND e.id_eje = '$id_eje'";
    // }
    
    // ✅ ORDENAMIENTO
    $sql_ejecutivos .= "
        ORDER BY 
            CASE 
                WHEN pc.fec_completado IS NOT NULL THEN 1
                WHEN pc.porcentaje_avance > 0 THEN 2
                ELSE 3
            END,
            pc.porcentaje_avance DESC,
            e.nom_eje ASC
    ";
    
    $resultado_ejecutivos = mysqli_query($db, $sql_ejecutivos);
    
    if (!$resultado_ejecutivos) {
        ob_clean();
        echo json_encode(array(
            'error' => 'Error en query',
            'debug' => mysqli_error($db)
        ));
        exit;
    }
    
    $ejecutivos = array();
    
    while ($row = mysqli_fetch_assoc($resultado_ejecutivos)) {
        // Nombre completo
        $row['nombre_completo'] = trim($row['nom_eje'] . ' ' . $row['app_eje'] . ' ' . $row['apm_eje']);
        
        // Foto (null si está vacía)
        if (empty($row['fot_eje'])) {
            $row['fot_eje'] = null;
        }
        
        // Asegurar valores numéricos
        $row['porcentaje_completado'] = floatval($row['porcentaje_completado']);
        $row['calificacion_examen'] = $row['calificacion_examen'] ? floatval($row['calificacion_examen']) : null;
        $row['total_intentos'] = intval($row['total_intentos']);
        
        $ejecutivos[] = $row;
    }
    
    // ✅ Calcular stats
    $total_ejecutivos = count($ejecutivos);
    $total_completados = 0;
    $total_en_progreso = 0;
    $total_sin_iniciar = 0;
    $total_aprobados = 0;
    $total_reprobados = 0;
    $total_pendientes_examen = 0;
    
    foreach ($ejecutivos as $e) {
        if ($e['estado_curso'] === 'Completado') $total_completados++;
        else if ($e['estado_curso'] === 'En Progreso') $total_en_progreso++;
        else $total_sin_iniciar++;
        
        if ($e['estado_examen'] === 'Aprobado') $total_aprobados++;
        else if ($e['estado_examen'] === 'Reprobado') $total_reprobados++;
        else $total_pendientes_examen++;
    }
    
    ob_clean();
    echo json_encode(array(
        'resultado' => 'exito',
        'ejecutivos' => $ejecutivos,
        'total_ejecutivos' => $total_ejecutivos,
        'total_completados' => $total_completados,
        'total_en_progreso' => $total_en_progreso,
        'total_sin_iniciar' => $total_sin_iniciar,
        'total_aprobados' => $total_aprobados,
        'total_reprobados' => $total_reprobados,
        'total_pendientes_examen' => $total_pendientes_examen,
        'tiene_examen' => $id_examen ? true : false,
        'permisos' => array(
            'nivel_acceso' => $es_admin_global ? 'GLOBAL' : ($es_admin_plantel ? 'PLANTEL' : 'PERSONAL'),
            'id_cad' => $cadena,
            'id_pla' => $id_pla_sesion
        )
    ));
    exit;
}

// ============================================
// EXAMEN DETALLE
// ============================================
if ($accion == "Consulta" && $tipo == 'examen_detalle') {
    
    $id_examen = intval($_POST['id_examen']);
    
    $sql = "SELECT * FROM examen_elearning WHERE id_examen = '$id_examen' AND eli_examen = 1";
    $result = mysqli_query($db, $sql);
    $examen = mysqli_fetch_assoc($result);
    
    if (!$examen) {
        ob_clean();
        echo json_encode(array('error' => 'Examen no encontrado'));
        exit;
    }
    
    // CONTAR PREGUNTAS
    $sql_preguntas = "SELECT COUNT(*) as total FROM pregunta_examen WHERE id_examen = '$id_examen' AND eli_pregunta = 1";
    $result_preguntas = mysqli_query($db, $sql_preguntas);
    $row_preguntas = mysqli_fetch_assoc($result_preguntas);
    $examen['total_preguntas'] = $row_preguntas['total'];
    
    ob_clean();
    echo json_encode(array('examen' => $examen));
    exit;
}

// ============================================
// PUEDE REINTENTAR
// ============================================

// ============================================
// PUEDE REINTENTAR - ✅ MEJORADO: NO PERMITE SI YA APROBÓ
// ============================================
if ($accion == "Consulta" && $tipo == 'puede_reintentar') {
    
    $id_examen = intval($_POST['id_examen']);
    
    // ✅ VERIFICAR SI YA APROBÓ ALGUNA VEZ
    $sql_aprobado = "SELECT id_intento, cal_intento, fec_fin_intento 
                     FROM intento_examen 
                     WHERE id_examen = '$id_examen' 
                       AND id_ejecutivo = '$id_eje' 
                       AND aprobado = 1 
                       AND est_intento = 'completado'
                     ORDER BY fec_fin_intento DESC 
                     LIMIT 1";
    
    $result_aprobado = mysqli_query($db, $sql_aprobado);
    $intento_aprobado = mysqli_fetch_assoc($result_aprobado);
    
    if ($intento_aprobado) {
        // 🚫 YA APROBÓ - NO PUEDE REINTENTAR
        ob_clean();
        echo json_encode(array(
            'puede_reintentar' => false,
            'ya_aprobo' => true,
            'calificacion' => floatval($intento_aprobado['cal_intento']),
            'fecha_aprobacion' => $intento_aprobado['fec_fin_intento']
        ));
        exit;
    }
    
    // ✅ NO HA APROBADO - VERIFICAR TIEMPO DE REINTENTO
    $sql_examen = "SELECT horas_reintento FROM examen_elearning WHERE id_examen = '$id_examen'";
    $result_examen = mysqli_query($db, $sql_examen);
    $examen = mysqli_fetch_assoc($result_examen);
    
    if (!$examen) {
        ob_clean();
        echo json_encode(array('error' => 'Examen no encontrado'));
        exit;
    }
    
    $horas_reintento = intval($examen['horas_reintento']);
    
    // VERIFICAR ÚLTIMO INTENTO (APROBADO O NO)
    $sql_ultimo = "SELECT fec_inicio_intento FROM intento_examen 
                   WHERE id_examen = '$id_examen' AND id_ejecutivo = '$id_eje' 
                   ORDER BY fec_inicio_intento DESC LIMIT 1";
    $result_ultimo = mysqli_query($db, $sql_ultimo);
    $ultimo_intento = mysqli_fetch_assoc($result_ultimo);
    
    if (!$ultimo_intento) {
        // ✅ PRIMER INTENTO
        ob_clean();
        echo json_encode(array(
            'puede_reintentar' => true,
            'ya_aprobo' => false
        ));
        exit;
    }
    
    // ⏰ CALCULAR TIEMPO TRANSCURRIDO
    $horas_transcurridas = (strtotime('now') - strtotime($ultimo_intento['fec_inicio_intento'])) / 3600;
    
    if ($horas_transcurridas >= $horas_reintento) {
        ob_clean();
        echo json_encode(array(
            'puede_reintentar' => true,
            'ya_aprobo' => false
        ));
        exit;
    }
    
    $horas_restantes = ceil($horas_reintento - $horas_transcurridas);
    
    ob_clean();
    echo json_encode(array(
        'puede_reintentar' => false,
        'ya_aprobo' => false,
        'horas_restantes' => $horas_restantes
    ));
    exit;
}

// ============================================
// CLASES - ALTA
// ============================================
if ($accion == "Alta" && $tipo == 'clase') {
    
    $id_curso = intval($_POST['id_curso']);
    $tit_clase = mysqli_real_escape_string($db, $_POST['tit_clase']);
    $des_clase = mysqli_real_escape_string($db, isset($_POST['des_clase']) ? $_POST['des_clase'] : '');
    $ord_clase = isset($_POST['ord_clase']) && $_POST['ord_clase'] != '' ? intval($_POST['ord_clase']) : 1;
    
    if (empty($tit_clase)) {
        ob_clean();
        echo json_encode(array('error' => 'El título es requerido'));
        exit;
    }
    
    $sql = "INSERT INTO clase_elearning (id_curso, tit_clase, des_clase, ord_clase, id_eje_creador) 
            VALUES ('$id_curso', '$tit_clase', '$des_clase', '$ord_clase', '$id_eje')";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_clase = mysqli_insert_id($db);
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_clase' => $id_clase, 'mensaje' => 'Clase creada'));
    exit;
}

// ============================================
// CLASES - CAMBIO
// ============================================
if ($accion == "Cambio" && $tipo == 'clase') {
    
    $id_clase = intval($_POST['id_clase']);
    $campo = isset($_POST['campo']) ? $_POST['campo'] : '';
    
    if ($campo == 'eliminar') {
        mysqli_query($db, "UPDATE clase_elearning SET eli_clase = 0 WHERE id_clase = '$id_clase'");
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Clase eliminada'));
        exit;
    }
    
    if (isset($_POST['tit_clase']) && isset($_POST['id_curso'])) {
        $id_curso = intval($_POST['id_curso']);
        $tit_clase = mysqli_real_escape_string($db, $_POST['tit_clase']);
        $des_clase = mysqli_real_escape_string($db, isset($_POST['des_clase']) ? $_POST['des_clase'] : '');
        $ord_clase = isset($_POST['ord_clase']) && $_POST['ord_clase'] != '' ? intval($_POST['ord_clase']) : 1;
        
        if (empty($tit_clase)) {
            ob_clean();
            echo json_encode(array('error' => 'El título es requerido'));
            exit;
        }
        
        if (empty($id_curso) || $id_curso == 0) {
            ob_clean();
            echo json_encode(array('error' => 'Debe seleccionar un curso'));
            exit;
        }
        
        $sql = "UPDATE clase_elearning 
                SET id_curso = '$id_curso',
                    tit_clase = '$tit_clase', 
                    des_clase = '$des_clase', 
                    ord_clase = '$ord_clase'
                WHERE id_clase = '$id_clase'";
        
        if (!mysqli_query($db, $sql)) {
            ob_clean();
            echo json_encode(array('error' => 'Error al actualizar: ' . mysqli_error($db)));
            exit;
        }
        
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Clase actualizada correctamente'));
        exit;
    }
    
    ob_clean();
    echo json_encode(array('error' => 'Faltan parámetros requeridos'));
    exit;
}

// ============================================
// CLASES - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'clases_curso') {
    
    $id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;
    
    if ($id_curso == 0) {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM contenido_elearning WHERE id_clase = c.id_clase AND eli_contenido = 1) as total_contenidos
                FROM clase_elearning c
                WHERE c.eli_clase = 1
                ORDER BY c.id_curso, c.ord_clase ASC";
    } else {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM contenido_elearning WHERE id_clase = c.id_clase AND eli_contenido = 1) as total_contenidos
                FROM clase_elearning c
                WHERE c.id_curso = '$id_curso' AND c.eli_clase = 1
                ORDER BY c.ord_clase ASC";
    }
    
    $resultado = mysqli_query($db, $sql);
    $clases = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $clases[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('clases' => $clases));
    exit;
}

// ============================================
// ✅ CONTENIDOS - ALTA (150MB)
// ============================================
if ($accion == "Alta" && $tipo == 'contenido') {
    
    $id_clase = intval($_POST['id_clase']);
    $tit_contenido = mysqli_real_escape_string($db, $_POST['tit_contenido']);
    $tip_contenido = mysqli_real_escape_string($db, $_POST['tip_contenido']);
    $des_contenido = mysqli_real_escape_string($db, isset($_POST['des_contenido']) ? $_POST['des_contenido'] : '');
    $ord_contenido = isset($_POST['ord_contenido']) && $_POST['ord_contenido'] != '' ? intval($_POST['ord_contenido']) : 1;
    
    if (empty($tit_contenido)) {
        ob_clean();
        echo json_encode(array('error' => 'El título es requerido'));
        exit;
    }
    
    if (empty($tip_contenido)) {
        ob_clean();
        echo json_encode(array('error' => 'El tipo es requerido'));
        exit;
    }
    
    $arc_contenido = NULL;
    $url_contenido = NULL;
    
    // VIDEO YOUTUBE
    if ($tip_contenido == 'video_youtube') {
        $url_contenido = mysqli_real_escape_string($db, $_POST['url_contenido']);
        if (empty($url_contenido)) {
            ob_clean();
            echo json_encode(array('error' => 'La URL de YouTube es requerida'));
            exit;
        }
    } 
    // TEXTO
    else if ($tip_contenido == 'texto') {
        // No requiere archivo
    } 
    // ✅ ARCHIVOS (VIDEO/AUDIO/PDF/IMAGEN) - 150MB
    else {
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            ob_clean();
            echo json_encode(array('error' => 'El archivo es requerido'));
            exit;
        }
        
        $archivo = $_FILES['archivo'];
        
        // ✅ VALIDACIÓN POR TIPO - 150MB PARA VIDEOS/AUDIOS/PDFs
        $tipoValidacion = 'archivo';
        if ($tip_contenido == 'video_archivo') $tipoValidacion = 'video';
        else if ($tip_contenido == 'audio') $tipoValidacion = 'audio';
        else if ($tip_contenido == 'pdf') $tipoValidacion = 'pdf';
        else if ($tip_contenido == 'imagen') $tipoValidacion = 'imagen';
        
        $validacion = validarArchivo($archivo, $tipoValidacion);
        
        if (isset($validacion['error'])) {
            ob_clean();
            echo json_encode($validacion);
            exit;
        }
        
        $extension = $validacion['extension'];
        
        // CREAR CONTENIDO TEMPORAL
        $sqlTemp = "INSERT INTO contenido_elearning 
                (id_clase, tit_contenido, tip_contenido, des_contenido, ord_contenido, id_eje_creador) 
                VALUES 
                ('$id_clase', '$tit_contenido', '$tip_contenido', '$des_contenido', '$ord_contenido', '$id_eje')";
        
        if (!mysqli_query($db, $sqlTemp)) {
            ob_clean();
            echo json_encode(array('error' => mysqli_error($db)));
            exit;
        }
        
        $id_contenido = mysqli_insert_id($db);
        
        // ✅ GUARDAR ARCHIVO - HASTA 150MB
        $contenido_archivo = file_get_contents($archivo['tmp_name']);
        $sha1_hash = sha1($contenido_archivo . $id_contenido . time());
        $arc_contenido = "contenido-{$id_contenido}-{$sha1_hash}.{$extension}";
        
        $ruta = '../../uploads/' . $arc_contenido;
        
        if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
            mysqli_query($db, "DELETE FROM contenido_elearning WHERE id_contenido = '$id_contenido'");
            ob_clean();
            echo json_encode(array('error' => 'Error al guardar archivo en el servidor'));
            exit;
        }
        
        mysqli_query($db, "UPDATE contenido_elearning SET arc_contenido = '$arc_contenido' WHERE id_contenido = '$id_contenido'");
        
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'id_contenido' => $id_contenido, 'mensaje' => 'Contenido creado'));
        exit;
    }
    
    // INSERTAR CONTENIDO SIN ARCHIVO
    $sql = "INSERT INTO contenido_elearning 
            (id_clase, tit_contenido, tip_contenido, arc_contenido, url_contenido, des_contenido, ord_contenido, id_eje_creador) 
            VALUES 
            ('$id_clase', '$tit_contenido', '$tip_contenido', NULL, 
            ".($url_contenido ? "'$url_contenido'" : "NULL").", 
            '$des_contenido', '$ord_contenido', '$id_eje')";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_contenido = mysqli_insert_id($db);
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_contenido' => $id_contenido, 'mensaje' => 'Contenido creado'));
    exit;
}

// ============================================
// CONTENIDOS - CAMBIO
// ============================================
if ($accion == "Cambio" && $tipo == 'contenido') {
    
    $id_contenido = intval($_POST['id_contenido']);
    $campo = isset($_POST['campo']) ? $_POST['campo'] : '';
    
    if ($campo == 'eliminar') {
        // ELIMINAR ARCHIVO FÍSICO
        $result = mysqli_query($db, "SELECT arc_contenido FROM contenido_elearning WHERE id_contenido = '$id_contenido'");
        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['arc_contenido'] && file_exists("../../uploads/".$row['arc_contenido'])) {
                unlink("../../uploads/".$row['arc_contenido']);
            }
        }
        
        mysqli_query($db, "UPDATE contenido_elearning SET eli_contenido = 0 WHERE id_contenido = '$id_contenido'");
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Contenido eliminado'));
        exit;
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Contenido actualizado'));
    exit;
}

// ============================================
// CONTENIDOS - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'contenidos_clase') {
    
    $id_clase = intval($_POST['id_clase']);
    
    $sql = "SELECT c.* FROM contenido_elearning c
            WHERE c.id_clase = '$id_clase' AND c.eli_contenido = 1
            ORDER BY c.ord_contenido ASC";
    
    $resultado = mysqli_query($db, $sql);
    $contenidos = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $result_prog = mysqli_query($db, "SELECT completado, tiempo_visto FROM progreso_contenido 
                                          WHERE id_eje = '$id_eje' AND id_contenido = '".$row['id_contenido']."'");
        $progreso = mysqli_fetch_assoc($result_prog);
        
        $row['completado'] = $progreso && $progreso['completado'] ? true : false;
        $row['tiempo_visto'] = $progreso ? $progreso['tiempo_visto'] : 0;
        
        $contenidos[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('contenidos' => $contenidos));
    exit;
}

// ============================================
// COMENTARIOS - ALTA
// ============================================
if ($accion == "Alta" && $tipo == 'comentario') {
    
    $id_contenido = intval($_POST['id_contenido']);
    $tex_comentario = mysqli_real_escape_string($db, $_POST['tex_comentario']);
    $id_comentario_padre = isset($_POST['id_comentario_padre']) ? intval($_POST['id_comentario_padre']) : NULL;
    
    if (empty($tex_comentario)) {
        ob_clean();
        echo json_encode(array('error' => 'El comentario no puede estar vacío'));
        exit;
    }
    
    $sql = "INSERT INTO comentario_elearning (id_contenido, tex_comentario, id_eje_comentario, id_comentario_padre) 
            VALUES ('$id_contenido', '$tex_comentario', '$id_eje', ".($id_comentario_padre ? "'$id_comentario_padre'" : "NULL").")";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_comentario = mysqli_insert_id($db);
    
    $result = mysqli_query($db, "SELECT c.*, e.nom_eje FROM comentario_elearning c
                                 INNER JOIN ejecutivo e ON c.id_eje_comentario = e.id_eje
                                 WHERE c.id_comentario = '$id_comentario'");
    $datos = mysqli_fetch_assoc($result);
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_comentario' => $id_comentario, 'datos' => $datos));
    exit;
}

// ============================================
// COMENTARIOS - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'comentarios_contenido') {
    
    $id_contenido = intval($_POST['id_contenido']);
    
    $sql = "SELECT c.*, e.nom_eje, 
            (SELECT COUNT(*) FROM comentario_elearning WHERE id_comentario_padre = c.id_comentario AND eli_comentario = 1) as total_respuestas
            FROM comentario_elearning c
            INNER JOIN ejecutivo e ON c.id_eje_comentario = e.id_eje
            WHERE c.id_contenido = '$id_contenido' AND c.id_comentario_padre IS NULL AND c.eli_comentario = 1
            ORDER BY c.fec_comentario DESC";
    
    $resultado = mysqli_query($db, $sql);
    $comentarios = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $result_resp = mysqli_query($db, "SELECT c.*, e.nom_eje FROM comentario_elearning c
                                          INNER JOIN ejecutivo e ON c.id_eje_comentario = e.id_eje
                                          WHERE c.id_comentario_padre = '".$row['id_comentario']."' AND c.eli_comentario = 1
                                          ORDER BY c.fec_comentario ASC");
        $respuestas = array();
        while ($resp = mysqli_fetch_assoc($result_resp)) {
            $respuestas[] = $resp;
        }
        $row['respuestas'] = $respuestas;
        $comentarios[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('comentarios' => $comentarios));
    exit;
}

// ============================================
// PROGRESO - REGISTRAR
// ============================================
if ($accion == "Registrar" && $tipo == 'progreso_contenido') {
    
    $id_contenido = intval($_POST['id_contenido']);
    $completado = isset($_POST['completado']) ? intval($_POST['completado']) : 0;
    $tiempo_visto = isset($_POST['tiempo_visto']) ? intval($_POST['tiempo_visto']) : 0;
    
    $result = mysqli_query($db, "SELECT cl.id_curso, c.id_clase FROM contenido_elearning c
                                 INNER JOIN clase_elearning cl ON c.id_clase = cl.id_clase
                                 WHERE c.id_contenido = '$id_contenido'");
    $dato = mysqli_fetch_assoc($result);
    $id_curso = $dato['id_curso'];
    $id_clase = $dato['id_clase'];
    
    $sql = "INSERT INTO progreso_contenido (id_eje, id_contenido, completado, tiempo_visto, fec_completado)
            VALUES ('$id_eje', '$id_contenido', '$completado', '$tiempo_visto', ".($completado ? "NOW()" : "NULL").")
            ON DUPLICATE KEY UPDATE 
            completado = VALUES(completado),
            tiempo_visto = VALUES(tiempo_visto),
            fec_ultima_vista = NOW(),
            fec_completado = VALUES(fec_completado)";
    
    mysqli_query($db, $sql);
    
    $result_total = mysqli_query($db, "SELECT COUNT(*) as total FROM contenido_elearning co
                                       INNER JOIN clase_elearning cl ON co.id_clase = cl.id_clase
                                       WHERE cl.id_curso = '$id_curso' AND co.eli_contenido = 1 AND cl.eli_clase = 1");
    $total = mysqli_fetch_assoc($result_total);
    $total = $total['total'];
    
    $result_comp = mysqli_query($db, "SELECT COUNT(*) as completados FROM progreso_contenido pc
                                      INNER JOIN contenido_elearning co ON pc.id_contenido = co.id_contenido
                                      INNER JOIN clase_elearning cl ON co.id_clase = cl.id_clase
                                      WHERE cl.id_curso = '$id_curso' AND pc.id_eje = '$id_eje' AND pc.completado = 1 
                                      AND co.eli_contenido = 1 AND cl.eli_clase = 1");
    $completados = mysqli_fetch_assoc($result_comp);
    $completados = $completados['completados'];
    
    $porcentaje = $total > 0 ? round(($completados / $total) * 100, 2) : 0;
    $curso_completado = $porcentaje >= 100 ? "NOW()" : "NULL";
    
    $sqlProgreso = "INSERT INTO progreso_curso (id_eje, id_curso, porcentaje_avance, ultima_clase_vista, ultimo_contenido_visto, fec_completado)
                   VALUES ('$id_eje', '$id_curso', '$porcentaje', '$id_clase', '$id_contenido', $curso_completado)
                   ON DUPLICATE KEY UPDATE 
                   porcentaje_avance = VALUES(porcentaje_avance),
                   ultima_clase_vista = VALUES(ultima_clase_vista),
                   ultimo_contenido_visto = VALUES(ultimo_contenido_visto),
                   fec_ultima_actividad = NOW(),
                   fec_completado = VALUES(fec_completado)";
    
    mysqli_query($db, $sqlProgreso);
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'porcentaje' => $porcentaje, 'completados' => $completados, 'total' => $total));
    exit;
}

// ============================================
// PREREQUISITOS - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'verificar_prerequisitos') {
    
    $id_curso = intval($_POST['id_curso']);
    
    $sql = "SELECT cp.*, c.nom_curso as nombre_curso_requerido
            FROM curso_prerequisito cp
            INNER JOIN curso_elearning c ON cp.id_curso_requerido = c.id_curso
            WHERE cp.id_curso = '$id_curso' AND cp.eli_prerequisito = 1";
    
    $resultado = mysqli_query($db, $sql);
    $prerequisitos = array();
    $puede_tomar = true;
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $result_comp = mysqli_query($db, "SELECT fec_completado FROM progreso_curso 
                                          WHERE id_eje = '$id_eje' AND id_curso = '".$row['id_curso_requerido']."' AND fec_completado IS NOT NULL");
        $completado = mysqli_fetch_assoc($result_comp);
        
        $row['completado'] = $completado ? true : false;
        
        if ($row['obligatorio'] && !$row['completado']) {
            $puede_tomar = false;
        }
        
        $prerequisitos[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('prerequisitos' => $prerequisitos, 'puede_tomar' => $puede_tomar));
    exit;
}

// ============================================
// EXÁMENES - ALTA
// ============================================
if ($accion == "Alta" && $tipo == 'examen') {
    
    $id_curso = isset($_POST['id_curso']) && $_POST['id_curso'] != '' ? intval($_POST['id_curso']) : NULL;
    $nom_examen = mysqli_real_escape_string($db, $_POST['nom_examen']);
    $des_examen = mysqli_real_escape_string($db, isset($_POST['des_examen']) ? $_POST['des_examen'] : '');
    $dur_examen = isset($_POST['dur_examen']) ? intval($_POST['dur_examen']) : 15;
    $cal_min_examen = isset($_POST['cal_min_examen']) ? floatval($_POST['cal_min_examen']) : 70.00;
    $ord_preguntas_aleatorio = isset($_POST['ord_preguntas_aleatorio']) ? intval($_POST['ord_preguntas_aleatorio']) : 0;
    $ord_opciones_aleatorio = isset($_POST['ord_opciones_aleatorio']) ? intval($_POST['ord_opciones_aleatorio']) : 0;
    $permitir_regresar = isset($_POST['permitir_regresar']) ? intval($_POST['permitir_regresar']) : 1;
    $mostrar_resultados_inmediatos = isset($_POST['mostrar_resultados_inmediatos']) ? intval($_POST['mostrar_resultados_inmediatos']) : 0;
    $mostrar_respuestas_correctas = isset($_POST['mostrar_respuestas_correctas']) ? intval($_POST['mostrar_respuestas_correctas']) : 0;
    $horas_reintento = isset($_POST['horas_reintento']) ? intval($_POST['horas_reintento']) : 24;
    $num_preguntas_mostrar = isset($_POST['num_preguntas_mostrar']) && $_POST['num_preguntas_mostrar'] != '' ? intval($_POST['num_preguntas_mostrar']) : NULL;
    
    if (empty($nom_examen)) {
        ob_clean();
        echo json_encode(array('error' => 'El nombre del examen es requerido'));
        exit;
    }
    
    $sql = "INSERT INTO examen_elearning (
                id_curso, nom_examen, des_examen, dur_examen, cal_min_examen, 
                ord_preguntas_aleatorio, ord_opciones_aleatorio, permitir_regresar, 
                mostrar_resultados_inmediatos, mostrar_respuestas_correctas, 
                horas_reintento, num_preguntas_mostrar, id_eje_creador
            ) VALUES (
                ".($id_curso ? "'$id_curso'" : "NULL").", '$nom_examen', '$des_examen', '$dur_examen', '$cal_min_examen', 
                '$ord_preguntas_aleatorio', '$ord_opciones_aleatorio', '$permitir_regresar', 
                '$mostrar_resultados_inmediatos', '$mostrar_respuestas_correctas', 
                '$horas_reintento', ".($num_preguntas_mostrar ? "'$num_preguntas_mostrar'" : "NULL").", '$id_eje'
            )";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_examen = mysqli_insert_id($db);
    
    // INSERTAR RANGOS
    if (isset($_POST['rangos'])) {
        $rangos = is_array($_POST['rangos']) ? $_POST['rangos'] : explode(',', $_POST['rangos']);
        foreach ($rangos as $rango) {
            $rango_esc = mysqli_real_escape_string($db, trim($rango));
            if (!empty($rango_esc)) {
                mysqli_query($db, "INSERT INTO examen_rango_asignado (id_examen, rango_asignado, id_eje_asignador) 
                                  VALUES ('$id_examen', '$rango_esc', '$id_eje')");
            }
        }
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_examen' => $id_examen, 'mensaje' => 'Examen creado'));
    exit;
}

// ============================================
// EXÁMENES - CAMBIO
// ============================================
if ($accion == "Cambio" && $tipo == 'examen') {
    
    $id_examen = intval($_POST['id_examen']);
    $campo = isset($_POST['campo']) ? $_POST['campo'] : '';
    
    if ($campo == 'eliminar') {
        mysqli_query($db, "UPDATE examen_elearning SET eli_examen = 0 WHERE id_examen = '$id_examen'");
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Examen eliminado'));
        exit;
    }
    
    if (isset($_POST['nom_examen'])) {
        $nom_examen = mysqli_real_escape_string($db, $_POST['nom_examen']);
        $des_examen = mysqli_real_escape_string($db, isset($_POST['des_examen']) ? $_POST['des_examen'] : '');
        $dur_examen = isset($_POST['dur_examen']) ? intval($_POST['dur_examen']) : 15;
        $cal_min_examen = isset($_POST['cal_min_examen']) ? floatval($_POST['cal_min_examen']) : 70.00;
        
        mysqli_query($db, "UPDATE examen_elearning 
                          SET nom_examen = '$nom_examen', des_examen = '$des_examen', 
                              dur_examen = '$dur_examen', cal_min_examen = '$cal_min_examen' 
                          WHERE id_examen = '$id_examen'");
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Examen actualizado'));
    exit;
}

// ============================================
// EXÁMENES - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'examenes_disponibles') {
    
    $sql = "SELECT e.*, c.nom_curso,
            (SELECT COUNT(*) FROM pregunta_examen WHERE id_examen = e.id_examen AND eli_pregunta = 1) as total_preguntas
            FROM examen_elearning e
            LEFT JOIN curso_elearning c ON e.id_curso = c.id_curso
            WHERE e.eli_examen = 1
            ORDER BY e.fec_creacion_examen DESC";
    
    $resultado = mysqli_query($db, $sql);
    $examenes = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $examenes[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('examenes' => $examenes));
    exit;
}

// ============================================
// PREGUNTAS - ALTA
// ============================================
if ($accion == "Alta" && $tipo == 'pregunta') {
    
    $id_examen = intval($_POST['id_examen']);
    $tex_pregunta = mysqli_real_escape_string($db, $_POST['tex_pregunta']);
    $tip_pregunta = mysqli_real_escape_string($db, isset($_POST['tip_pregunta']) ? $_POST['tip_pregunta'] : 'opcion_multiple');
    $pun_pregunta = isset($_POST['pun_pregunta']) ? floatval($_POST['pun_pregunta']) : 1.00;
    $ord_pregunta = isset($_POST['ord_pregunta']) ? intval($_POST['ord_pregunta']) : 1;
    $exp_pregunta = mysqli_real_escape_string($db, isset($_POST['exp_pregunta']) ? $_POST['exp_pregunta'] : '');
    
    if (empty($tex_pregunta)) {
        ob_clean();
        echo json_encode(array('error' => 'El texto de la pregunta es requerido'));
        exit;
    }
    
    $sql = "INSERT INTO pregunta_examen (id_examen, tex_pregunta, tip_pregunta, pun_pregunta, ord_pregunta, exp_pregunta) 
            VALUES ('$id_examen', '$tex_pregunta', '$tip_pregunta', '$pun_pregunta', '$ord_pregunta', '$exp_pregunta')";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_pregunta = mysqli_insert_id($db);
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_pregunta' => $id_pregunta, 'mensaje' => 'Pregunta creada'));
    exit;
}

// ============================================
// PREGUNTAS - CAMBIO
// ============================================
if ($accion == "Cambio" && $tipo == 'pregunta') {
    
    $id_pregunta = intval($_POST['id_pregunta']);
    $campo = isset($_POST['campo']) ? $_POST['campo'] : '';
    
    if ($campo == 'eliminar') {
        mysqli_query($db, "UPDATE pregunta_examen SET eli_pregunta = 0 WHERE id_pregunta = '$id_pregunta'");
        ob_clean();
        echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Pregunta eliminada'));
        exit;
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Pregunta actualizada'));
    exit;
}

// ============================================
// PREGUNTAS - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'preguntas_examen') {
    
    $id_examen = intval($_POST['id_examen']);
    
    $sql = "SELECT p.*,
            (SELECT COUNT(*) FROM opcion_pregunta WHERE id_pregunta = p.id_pregunta AND eli_opcion = 1) as total_opciones
            FROM pregunta_examen p
            WHERE p.id_examen = '$id_examen' AND p.eli_pregunta = 1
            ORDER BY p.ord_pregunta ASC";
    
    $resultado = mysqli_query($db, $sql);
    $preguntas = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $preguntas[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('preguntas' => $preguntas));
    exit;
}

// ============================================
// OPCIONES - ALTA
// ============================================
if ($accion == "Alta" && $tipo == 'opcion') {
    
    $id_pregunta = intval($_POST['id_pregunta']);
    $tex_opcion = mysqli_real_escape_string($db, $_POST['tex_opcion']);
    $es_correcta = isset($_POST['es_correcta']) ? intval($_POST['es_correcta']) : 0;
    $ord_opcion = isset($_POST['ord_opcion']) ? intval($_POST['ord_opcion']) : 1;
    
    if (empty($tex_opcion)) {
        ob_clean();
        echo json_encode(array('error' => 'El texto de la opción es requerido'));
        exit;
    }
    
    $sql = "INSERT INTO opcion_pregunta (id_pregunta, tex_opcion, es_correcta, ord_opcion) 
            VALUES ('$id_pregunta', '$tex_opcion', '$es_correcta', '$ord_opcion')";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_opcion = mysqli_insert_id($db);
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'id_opcion' => $id_opcion, 'mensaje' => 'Opción creada'));
    exit;
}

// ============================================
// OPCIONES - CONSULTA
// ============================================
if ($accion == "Consulta" && $tipo == 'opciones_pregunta') {
    
    $id_pregunta = intval($_POST['id_pregunta']);
    
    $sql = "SELECT * FROM opcion_pregunta 
            WHERE id_pregunta = '$id_pregunta' AND eli_opcion = 1
            ORDER BY ord_opcion ASC";
    
    $resultado = mysqli_query($db, $sql);
    $opciones = array();
    
    while ($row = mysqli_fetch_assoc($resultado)) {
        $opciones[] = $row;
    }
    
    ob_clean();
    echo json_encode(array('opciones' => $opciones));
    exit;
}

// ============================================
// EXAMEN - INICIAR
// ============================================
if ($accion == "IniciarExamen" && $tipo == 'intento') {
    
    $id_examen = intval($_POST['id_examen']);
    
    $sql = "SELECT * FROM examen_elearning WHERE id_examen = '$id_examen' AND eli_examen = 1";
    $result = mysqli_query($db, $sql);
    $examen = mysqli_fetch_assoc($result);
    
    if (!$examen) {
        ob_clean();
        echo json_encode(array('error' => 'Examen no encontrado'));
        exit;
    }
    
    // VERIFICAR ÚLTIMO INTENTO
    $sql_ultimo = "SELECT fec_inicio_intento FROM intento_examen 
                   WHERE id_examen = '$id_examen' AND id_ejecutivo = '$id_eje' 
                   ORDER BY fec_inicio_intento DESC LIMIT 1";
    $result_ultimo = mysqli_query($db, $sql_ultimo);
    $ultimo_intento = mysqli_fetch_assoc($result_ultimo);
    
    if ($ultimo_intento) {
        $horas_transcurridas = (strtotime('now') - strtotime($ultimo_intento['fec_inicio_intento'])) / 3600;
        if ($horas_transcurridas < $examen['horas_reintento']) {
            $horas_restantes = ceil($examen['horas_reintento'] - $horas_transcurridas);
            ob_clean();
            echo json_encode(array('error' => "Debes esperar $horas_restantes horas para reintentar"));
            exit;
        }
    }
    
    // OBTENER PREGUNTAS
    $sql_preguntas = "SELECT p.*, 
                     (SELECT COUNT(*) FROM opcion_pregunta WHERE id_pregunta = p.id_pregunta AND eli_opcion = 1) as total_opciones
                     FROM pregunta_examen p
                     WHERE p.id_examen = '$id_examen' AND p.eli_pregunta = 1
                     ORDER BY ".($examen['ord_preguntas_aleatorio'] ? "RAND()" : "p.ord_pregunta ASC");
    
    if ($examen['num_preguntas_mostrar']) {
        $sql_preguntas .= " LIMIT ".$examen['num_preguntas_mostrar'];
    }
    
    $result_preguntas = mysqli_query($db, $sql_preguntas);
    $preguntas = array();
    
    while ($row = mysqli_fetch_assoc($result_preguntas)) {
        $sql_opciones = "SELECT * FROM opcion_pregunta 
                        WHERE id_pregunta = '".$row['id_pregunta']."' AND eli_opcion = 1
                        ORDER BY ".($examen['ord_opciones_aleatorio'] ? "RAND()" : "ord_opcion ASC");
        $result_opciones = mysqli_query($db, $sql_opciones);
        $opciones = array();
        
        while ($opcion = mysqli_fetch_assoc($result_opciones)) {
            unset($opcion['es_correcta']);
            $opciones[] = $opcion;
        }
        
        $row['opciones'] = $opciones;
        unset($row['exp_pregunta']);
        $preguntas[] = $row;
    }
    
    // CREAR INTENTO
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = mysqli_real_escape_string($db, $_SERVER['HTTP_USER_AGENT']);
    $orden_preguntas = json_encode(array_column($preguntas, 'id_pregunta'));
    
    $sql_intento = "INSERT INTO intento_examen (id_examen, id_ejecutivo, fec_inicio_intento, ip_intento, navegador_intento, ord_preguntas_json)
                   VALUES ('$id_examen', '$id_eje', NOW(), '$ip', '$navegador', '$orden_preguntas')";
    
    if (!mysqli_query($db, $sql_intento)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    $id_intento = mysqli_insert_id($db);
    
    ob_clean();
    echo json_encode(array(
        'resultado' => 'exito',
        'id_intento' => $id_intento,
        'examen' => $examen,
        'preguntas' => $preguntas
    ));
    exit;
}

// ============================================
// EXAMEN - GUARDAR RESPUESTA
// ============================================
if ($accion == "GuardarRespuesta" && $tipo == 'respuesta') {
    
    $id_intento = intval($_POST['id_intento']);
    $id_pregunta = intval($_POST['id_pregunta']);
    $id_opcion = isset($_POST['id_opcion']) && $_POST['id_opcion'] != '' ? intval($_POST['id_opcion']) : NULL;
    $tiempo_respuesta = isset($_POST['tiempo_respuesta']) ? intval($_POST['tiempo_respuesta']) : NULL;
    
    $es_correcta = NULL;
    $pun_obtenidos = 0;
    
    if ($id_opcion) {
        $sql_opcion = "SELECT es_correcta FROM opcion_pregunta WHERE id_opcion = '$id_opcion'";
        $result_opcion = mysqli_query($db, $sql_opcion);
        $opcion = mysqli_fetch_assoc($result_opcion);
        $es_correcta = $opcion['es_correcta'];
        
        if ($es_correcta) {
            $sql_pregunta = "SELECT pun_pregunta FROM pregunta_examen WHERE id_pregunta = '$id_pregunta'";
            $result_pregunta = mysqli_query($db, $sql_pregunta);
            $pregunta = mysqli_fetch_assoc($result_pregunta);
            $pun_obtenidos = $pregunta['pun_pregunta'];
        }
    }
    
    $sql = "INSERT INTO respuesta_intento (id_intento, id_pregunta, id_opcion, es_correcta, pun_obtenidos, tiempo_respuesta)
            VALUES ('$id_intento', '$id_pregunta', ".($id_opcion ? "'$id_opcion'" : "NULL").", ".($es_correcta !== NULL ? "'$es_correcta'" : "NULL").", '$pun_obtenidos', ".($tiempo_respuesta ? "'$tiempo_respuesta'" : "NULL").")
            ON DUPLICATE KEY UPDATE 
            id_opcion = VALUES(id_opcion),
            es_correcta = VALUES(es_correcta),
            pun_obtenidos = VALUES(pun_obtenidos),
            tiempo_respuesta = VALUES(tiempo_respuesta),
            fec_respuesta = NOW()";
    
    if (!mysqli_query($db, $sql)) {
        ob_clean();
        echo json_encode(array('error' => mysqli_error($db)));
        exit;
    }
    
    ob_clean();
    echo json_encode(array('resultado' => 'exito', 'mensaje' => 'Respuesta guardada'));
    exit;
}

// ============================================
// EXAMEN - FINALIZAR
// ============================================
if ($accion == "FinalizarExamen" && $tipo == 'intento') {
    
    $id_intento = intval($_POST['id_intento']);
    
    $sql_intento = "SELECT ie.*, ee.cal_min_examen, ee.mostrar_resultados_inmediatos, ee.mostrar_respuestas_correctas
                   FROM intento_examen ie
                   INNER JOIN examen_elearning ee ON ie.id_examen = ee.id_examen
                   WHERE ie.id_intento = '$id_intento'";
    $result_intento = mysqli_query($db, $sql_intento);
    $intento = mysqli_fetch_assoc($result_intento);
    
    $dur_real = (strtotime('now') - strtotime($intento['fec_inicio_intento']));
    
    $sql_puntos = "SELECT 
                  SUM(ri.pun_obtenidos) as pun_obtenidos,
                  SUM(pe.pun_pregunta) as pun_totales
                  FROM respuesta_intento ri
                  INNER JOIN pregunta_examen pe ON ri.id_pregunta = pe.id_pregunta
                  WHERE ri.id_intento = '$id_intento'";
    $result_puntos = mysqli_query($db, $sql_puntos);
    $puntos = mysqli_fetch_assoc($result_puntos);
    
    $pun_obtenidos = $puntos['pun_obtenidos'] ? floatval($puntos['pun_obtenidos']) : 0;
    $pun_totales = $puntos['pun_totales'] ? floatval($puntos['pun_totales']) : 1;
    
    $calificacion = ($pun_obtenidos / $pun_totales) * 100;
    $aprobado = $calificacion >= $intento['cal_min_examen'] ? 1 : 0;
    
    $sql_update = "UPDATE intento_examen 
                  SET fec_fin_intento = NOW(),
                      dur_real_intento = '$dur_real',
                      cal_intento = '$calificacion',
                      pun_obtenidos = '$pun_obtenidos',
                      pun_totales = '$pun_totales',
                      est_intento = 'completado',
                      aprobado = '$aprobado'
                  WHERE id_intento = '$id_intento'";
    
    mysqli_query($db, $sql_update);
    
    $resultado = array(
        'resultado' => 'exito',
        'calificacion' => round($calificacion, 2),
        'aprobado' => $aprobado,
        'pun_obtenidos' => $pun_obtenidos,
        'pun_totales' => $pun_totales
    );
    
    if ($intento['mostrar_respuestas_correctas']) {
        $sql_respuestas = "SELECT ri.*, pe.tex_pregunta, pe.exp_pregunta, op.tex_opcion, op.es_correcta,
                          (SELECT tex_opcion FROM opcion_pregunta WHERE id_pregunta = pe.id_pregunta AND es_correcta = 1 LIMIT 1) as respuesta_correcta
                          FROM respuesta_intento ri
                          INNER JOIN pregunta_examen pe ON ri.id_pregunta = pe.id_pregunta
                          LEFT JOIN opcion_pregunta op ON ri.id_opcion = op.id_opcion
                          WHERE ri.id_intento = '$id_intento'";
        $result_respuestas = mysqli_query($db, $sql_respuestas);
        $respuestas = array();
        
        while ($row = mysqli_fetch_assoc($result_respuestas)) {
            $respuestas[] = $row;
        }
        
        $resultado['respuestas'] = $respuestas;
    }
    
    ob_clean();
    echo json_encode($resultado);
    exit;
}

// ============================================
// ACCIÓN NO VÁLIDA
// ============================================
ob_clean();
echo json_encode(array('error' => 'Acción no válida', 'accion' => $accion, 'tipo' => $tipo));
?>