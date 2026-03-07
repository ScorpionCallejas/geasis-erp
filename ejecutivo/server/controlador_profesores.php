<?php  
// ========================================================================
// CONTROLADOR DE PROFESORES
// Archivo: server/controlador_profesores.php
// VERSIÓN LIMPIA - SIN GÉNERO NI ESPECIALIDAD EN FILTROS
// ========================================================================

// Suprimir errores HTML en respuestas JSON
error_reporting(0);
ini_set('display_errors', 0);

// Header JSON
header('Content-Type: application/json; charset=utf-8');

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ========== SECCIÓN 0: OBTENER PROFESORES (CONSULTA PRINCIPAL) ==========
if(!isset($_POST['actualizar_campo_profesor']) && 
   !isset($_POST['eliminar_profesor']) && 
   !isset($_POST['accion'])) {
    
    try {
        // DETECTAR BÚSQUEDA
        $datosProfesor = '';
        if(isset($_POST['datosProfesor'])) {
            $datosProfesor = trim(preg_replace('!\s+!', ' ', $_POST['datosProfesor']));
        }
        
        // ========== FILTRO: PLANTELES ==========
        $plantelesCondicion = "";
        if(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax']) && is_array($_POST['planteles_ajax'])) {
            $plantelesLimpios = array_map('intval', $_POST['planteles_ajax']);
            $plantelesLimpios = array_filter($plantelesLimpios, function($v) { return $v > 0; });
            if(!empty($plantelesLimpios)) {
                $plantelesStr = implode(',', $plantelesLimpios);
                $plantelesCondicion = " AND p.id_pla2 IN ($plantelesStr)";
            }
        }
        
        // ========== FILTRO: ESTATUS ==========
        $estatusCondicion = "";
        if(isset($_POST['estatus_ajax']) && !empty($_POST['estatus_ajax']) && is_array($_POST['estatus_ajax'])) {
            $estatusLimpios = array();
            foreach($_POST['estatus_ajax'] as $e) {
                $estatusLimpios[] = "'" . mysqli_real_escape_string($db, $e) . "'";
            }
            if(!empty($estatusLimpios)) {
                $estatusStr = implode(',', $estatusLimpios);
                $estatusCondicion = " AND p.est_pro IN ($estatusStr)";
            }
        }
        
        // ========== CONSULTA PRINCIPAL ==========
        $sql = "
            SELECT 
                p.id_pro,
                p.id_emp3,
                pl.nom_pla AS plantel,
                CONCAT(p.nom_pro, ' ', p.app_pro, ' ', IFNULL(p.apm_pro, '')) AS nombre_completo,
                p.nom_pro,
                p.app_pro,
                p.apm_pro,
                p.gen_pro AS genero,
                p.cor_pro AS correo,
                p.pas_pro AS password,
                p.tel_pro AS telefono,
                DATE_FORMAT(p.nac_pro, '%Y-%m-%d') AS nacimiento,
                DATE_FORMAT(p.ing_pro, '%d/%m/%Y') AS ingreso,
                p.esp_pro AS especialidad,
                p.dir_pro AS direccion,
                p.cp_pro AS codigo_postal,
                p.est_pro AS estatus
            FROM profesor p
            INNER JOIN empleado e ON e.id_emp = p.id_emp3
            INNER JOIN plantel pl ON pl.id_pla = p.id_pla2
            WHERE 1=1
            $plantelesCondicion
            $estatusCondicion
        ";
        
        // ========== BÚSQUEDA POR TEXTO ==========
        if(!empty($datosProfesor)) {
            $busqueda = mysqli_real_escape_string($db, $datosProfesor);
            $sql .= " AND (
                p.id_pro LIKE '%$busqueda%' OR
                p.nom_pro LIKE '%$busqueda%' OR
                p.app_pro LIKE '%$busqueda%' OR
                p.apm_pro LIKE '%$busqueda%' OR
                p.cor_pro LIKE '%$busqueda%' OR
                p.tel_pro LIKE '%$busqueda%' OR
                p.esp_pro LIKE '%$busqueda%' OR
                pl.nom_pla LIKE '%$busqueda%' OR
                CONCAT(p.nom_pro, ' ', p.app_pro, ' ', IFNULL(p.apm_pro, '')) LIKE '%$busqueda%'
            )";
        }
        
        $sql .= " ORDER BY p.id_pro DESC";
        
        // ========== EJECUTAR ==========
        $resultado = mysqli_query($db, $sql);
        
        if(!$resultado) {
            echo json_encode([
                'error' => true,
                'mensaje' => mysqli_error($db)
            ]);
            exit;
        }
        
        // ========== PROCESAR RESULTADOS ==========
        $profesores = array();
        while($fila = mysqli_fetch_assoc($resultado)) {
            $profesores[] = array(
                $fila['id_pro'],
                $fila['id_emp3'],
                $fila['plantel'],
                $fila['nombre_completo'],
                $fila['genero'],
                $fila['correo'],
                $fila['password'],
                $fila['telefono'],
                $fila['ingreso'],
                $fila['especialidad'],
                $fila['estatus']
            );
        }
        
        echo json_encode($profesores);
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'error' => true,
            'mensaje' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SECCIÓN 1: ACTUALIZAR ESTATUS INLINE ==========
if(isset($_POST['actualizar_campo_profesor'])) {
    
    try {
        $id_pro = intval($_POST['id_pro']);
        $campo = mysqli_real_escape_string($db, $_POST['campo']);
        $valor = mysqli_real_escape_string($db, $_POST['valor']);
        
        // SOLO PERMITIR EDICIÓN DE ESTATUS
        if($campo !== 'est_pro') {
            echo json_encode([
                'status' => 400,
                'message' => 'Campo no permitido'
            ]);
            exit;
        }
        
        // VALIDAR VALORES DE ESTATUS
        if(!in_array($valor, ['Activo', 'Inactivo'])) {
            echo json_encode([
                'status' => 400,
                'message' => 'Valor de estatus inválido'
            ]);
            exit;
        }
        
        // EJECUTAR UPDATE
        $sql = "UPDATE profesor SET est_pro = '$valor' WHERE id_pro = $id_pro";
        $resultado = mysqli_query($db, $sql);
        
        if($resultado) {
            // LOG
            $sqlNombre = "SELECT CONCAT(nom_pro, ' ', app_pro) AS nombre FROM profesor WHERE id_pro = $id_pro";
            $resNombre = mysqli_query($db, $sqlNombre);
            $filaNombre = mysqli_fetch_assoc($resNombre);
            $nombreProfesor = isset($filaNombre['nombre']) ? $filaNombre['nombre'] : 'Profesor';
            
            $accionLog = ($valor === 'Activo') ? 'activó' : 'desactivó';
            
            if(function_exists('obtenerDescripcionActivacionProfesorLogServer')) {
                $des_log = obtenerDescripcionActivacionProfesorLogServer($tipoUsuario, $nomResponsable, $accionLog, $nombreProfesor);
                logServer('Cambio', $tipoUsuario, $id, 'Profesor', $des_log, $plantel);
            }
            
            echo json_encode([
                'status' => 200,
                'message' => 'Estatus actualizado correctamente'
            ]);
        } else {
            echo json_encode([
                'status' => 500,
                'message' => 'Error al actualizar: ' . mysqli_error($db)
            ]);
        }
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'status' => 500,
            'message' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SECCIÓN 2: ELIMINAR PROFESOR ==========
if(isset($_POST['eliminar_profesor'])) {
    
    try {
        $id_emp = intval($_POST['eliminar_profesor']);
        
        // OBTENER DATOS DEL PROFESOR
        $sqlProfesor = "
            SELECT p.id_pro, CONCAT(p.nom_pro, ' ', p.app_pro) AS nombre, e.fot_emp 
            FROM profesor p 
            INNER JOIN empleado e ON e.id_emp = p.id_emp3 
            WHERE p.id_emp3 = $id_emp
        ";
        $resProfesor = mysqli_query($db, $sqlProfesor);
        
        if(!$resProfesor || mysqli_num_rows($resProfesor) == 0) {
            echo json_encode([
                'status' => 404,
                'message' => 'Profesor no encontrado'
            ]);
            exit;
        }
        
        $filaProfesor = mysqli_fetch_assoc($resProfesor);
        $nombreProfesor = $filaProfesor['nombre'];
        $fotoEmpleado = $filaProfesor['fot_emp'];
        $id_pro_interno = $filaProfesor['id_pro'];
        
        // ELIMINAR FOTO SI EXISTE
        if($fotoEmpleado != NULL && !empty($fotoEmpleado)) {
            $pathFoto = "../../uploads/$fotoEmpleado";
            if(file_exists($pathFoto)) {
                @unlink($pathFoto);
            }
        }
        
        // ELIMINAR EMPLEADO (CASCADE)
        $sql = "DELETE FROM empleado WHERE id_emp = $id_emp";
        $resultado = mysqli_query($db, $sql);
        
        if($resultado) {
            // LOG
            if(function_exists('obtenerDescripcionPersonalLogServer')) {
                $des_log = obtenerDescripcionPersonalLogServer($nomResponsable, 'eliminó', 'profesor', $nombreProfesor);
                logServer('Baja', $tipoUsuario, $id, 'Profesor', $des_log, $plantel);
            }
            
            echo json_encode([
                'status' => 200,
                'message' => 'Profesor eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'status' => 500,
                'message' => 'Error al eliminar: ' . mysqli_error($db)
            ]);
        }
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'status' => 500,
            'message' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SECCIÓN 3: GUARDAR NUEVO PROFESOR ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'guardar_profesor') {
    
    try {
        // RECIBIR DATOS
        $id_pla = intval($_POST['id_pla']);
        $nom_pro = ucwords(strtolower(trim(mysqli_real_escape_string($db, $_POST['nom_pro']))));
        $app_pro = ucwords(strtolower(trim(mysqli_real_escape_string($db, $_POST['app_pro']))));
        $apm_pro = isset($_POST['apm_pro']) ? ucwords(strtolower(trim(mysqli_real_escape_string($db, $_POST['apm_pro'])))) : '';
        $gen_pro = isset($_POST['gen_pro']) ? mysqli_real_escape_string($db, $_POST['gen_pro']) : '';
        $cor_pro = strtolower(trim(mysqli_real_escape_string($db, $_POST['cor_pro'])));
        $pas_pro = mysqli_real_escape_string($db, $_POST['pas_pro']);
        $tel_pro = isset($_POST['tel_pro']) ? mysqli_real_escape_string($db, $_POST['tel_pro']) : '';
        $nac_pro = isset($_POST['nac_pro']) ? mysqli_real_escape_string($db, $_POST['nac_pro']) : '';
        $esp_pro = isset($_POST['esp_pro']) ? mysqli_real_escape_string($db, $_POST['esp_pro']) : '';
        $dir_pro = isset($_POST['dir_pro']) ? mysqli_real_escape_string($db, $_POST['dir_pro']) : '';
        $cp_pro = isset($_POST['cp_pro']) ? mysqli_real_escape_string($db, $_POST['cp_pro']) : '';
        $ing_pro = date('Y-m-d');
        
        $nombreProfesor = "$nom_pro $app_pro $apm_pro";
        
        // VALIDACIONES
        if(empty($nom_pro)) {
            echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
            exit;
        }
        
        if(empty($app_pro)) {
            echo json_encode(['success' => false, 'message' => 'El apellido paterno es obligatorio']);
            exit;
        }
        
        if(empty($cor_pro)) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico es obligatorio']);
            exit;
        }
        
        if($id_pla <= 0) {
            echo json_encode(['success' => false, 'message' => 'Debe seleccionar un plantel']);
            exit;
        }
        
        // VALIDAR CORREO ÚNICO
        $sqlCheck = "SELECT id_pro FROM profesor WHERE cor_pro = '$cor_pro' LIMIT 1";
        $resCheck = mysqli_query($db, $sqlCheck);
        
        if(mysqli_num_rows($resCheck) > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está en uso']);
            exit;
        }
        
        // 1. INSERTAR EN EMPLEADO
        $sqlEmpleado = "
            INSERT INTO empleado (
                cor_emp, nom_emp, app_emp, apm_emp, tel_emp, 
                nac_emp, ing_emp, id_pla6, tip_emp, est_emp, 
                cp_emp, dir_emp
            ) VALUES (
                '$cor_pro', '$nom_pro', '$app_pro', '$apm_pro', '$tel_pro',
                " . (!empty($nac_pro) ? "'$nac_pro'" : "NULL") . ", '$ing_pro', '$id_pla', 'Profesor', 'Activo',
                '$cp_pro', '$dir_pro'
            )
        ";
        
        $resEmpleado = mysqli_query($db, $sqlEmpleado);
        
        if(!$resEmpleado) {
            echo json_encode(['success' => false, 'message' => 'Error al crear empleado: ' . mysqli_error($db)]);
            exit;
        }
        
        $id_emp = mysqli_insert_id($db);
        
        // 2. INSERTAR EN PROFESOR
        $sqlProfesor = "
            INSERT INTO profesor (
                nom_pro, app_pro, apm_pro, gen_pro, pas_pro,
                tel_pro, nac_pro, ing_pro, cor_pro, tip_pro,
                esp_pro, dir_pro, cp_pro, est_pro, id_pla2, id_emp3
            ) VALUES (
                '$nom_pro', '$app_pro', '$apm_pro', '$gen_pro', '$pas_pro',
                '$tel_pro', " . (!empty($nac_pro) ? "'$nac_pro'" : "NULL") . ", '$ing_pro', '$cor_pro', 'Profesor',
                '$esp_pro', '$dir_pro', '$cp_pro', 'Activo', '$id_pla', '$id_emp'
            )
        ";
        
        $resProfesor = mysqli_query($db, $sqlProfesor);
        
        if(!$resProfesor) {
            // Rollback: eliminar empleado
            mysqli_query($db, "DELETE FROM empleado WHERE id_emp = $id_emp");
            echo json_encode(['success' => false, 'message' => 'Error al crear profesor: ' . mysqli_error($db)]);
            exit;
        }
        
        $id_pro = mysqli_insert_id($db);
        
        // LOG
        if(function_exists('obtenerDescripcionPersonalLogServer')) {
            $des_log = obtenerDescripcionPersonalLogServer($nomResponsable, 'registró', 'profesor', $nombreProfesor);
            logServer('Alta', $tipoUsuario, $id, 'Profesor', $des_log, $plantel);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Profesor guardado correctamente',
            'id_pro' => $id_pro
        ]);
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'success' => false,
            'message' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SECCIÓN 4: OBTENER PROFESOR (PARA MODAL) ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_profesor') {
    
    try {
        $id_pro = intval($_POST['id_pro']);
        
        $sql = "
            SELECT 
                p.*,
                e.fot_emp,
                pl.nom_pla
            FROM profesor p
            INNER JOIN empleado e ON e.id_emp = p.id_emp3
            INNER JOIN plantel pl ON pl.id_pla = p.id_pla2
            WHERE p.id_pro = $id_pro 
            LIMIT 1
        ";
        $resultado = mysqli_query($db, $sql);
        
        if($resultado && mysqli_num_rows($resultado) > 0) {
            $profesor = mysqli_fetch_assoc($resultado);
            echo json_encode([
                'success' => true,
                'profesor' => $profesor
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Profesor no encontrado'
            ]);
        }
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'success' => false,
            'message' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SECCIÓN 5: EDITAR PROFESOR ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'editar_profesor') {
    
    try {
        $id_pro = intval($_POST['id_pro']);
        $id_pla = intval($_POST['id_pla']);
        $nom_pro = ucwords(strtolower(trim(mysqli_real_escape_string($db, $_POST['nom_pro']))));
        $app_pro = ucwords(strtolower(trim(mysqli_real_escape_string($db, $_POST['app_pro']))));
        $apm_pro = isset($_POST['apm_pro']) ? ucwords(strtolower(trim(mysqli_real_escape_string($db, $_POST['apm_pro'])))) : '';
        $gen_pro = isset($_POST['gen_pro']) ? mysqli_real_escape_string($db, $_POST['gen_pro']) : '';
        $cor_pro = strtolower(trim(mysqli_real_escape_string($db, $_POST['cor_pro'])));
        $pas_pro = mysqli_real_escape_string($db, $_POST['pas_pro']);
        $tel_pro = isset($_POST['tel_pro']) ? mysqli_real_escape_string($db, $_POST['tel_pro']) : '';
        $nac_pro = isset($_POST['nac_pro']) ? mysqli_real_escape_string($db, $_POST['nac_pro']) : '';
        $esp_pro = isset($_POST['esp_pro']) ? mysqli_real_escape_string($db, $_POST['esp_pro']) : '';
        $dir_pro = isset($_POST['dir_pro']) ? mysqli_real_escape_string($db, $_POST['dir_pro']) : '';
        $cp_pro = isset($_POST['cp_pro']) ? mysqli_real_escape_string($db, $_POST['cp_pro']) : '';
        $est_pro = isset($_POST['est_pro']) ? mysqli_real_escape_string($db, $_POST['est_pro']) : 'Activo';
        
        $nombreProfesor = "$nom_pro $app_pro $apm_pro";
        
        // VALIDACIONES
        if(empty($nom_pro) || empty($app_pro) || empty($cor_pro) || $id_pla <= 0 || $id_pro <= 0) {
            echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
            exit;
        }
        
        // VALIDAR ESTATUS
        if(!in_array($est_pro, ['Activo', 'Inactivo'])) {
            $est_pro = 'Activo';
        }
        
        // VALIDAR CORREO ÚNICO (EXCLUYENDO EL ACTUAL)
        $sqlCheck = "SELECT id_pro FROM profesor WHERE cor_pro = '$cor_pro' AND id_pro != $id_pro LIMIT 1";
        $resCheck = mysqli_query($db, $sqlCheck);
        
        if(mysqli_num_rows($resCheck) > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está en uso']);
            exit;
        }
        
        // OBTENER ID_EMP
        $sqlGetEmp = "SELECT id_emp3 FROM profesor WHERE id_pro = $id_pro";
        $resGetEmp = mysqli_query($db, $sqlGetEmp);
        $filaGetEmp = mysqli_fetch_assoc($resGetEmp);
        $id_emp = $filaGetEmp['id_emp3'];
        
        // 1. ACTUALIZAR PROFESOR
        $sqlProfesor = "
            UPDATE profesor SET
                nom_pro = '$nom_pro',
                app_pro = '$app_pro',
                apm_pro = '$apm_pro',
                gen_pro = '$gen_pro',
                cor_pro = '$cor_pro',
                pas_pro = '$pas_pro',
                tel_pro = '$tel_pro',
                nac_pro = " . (!empty($nac_pro) ? "'$nac_pro'" : "NULL") . ",
                esp_pro = '$esp_pro',
                dir_pro = '$dir_pro',
                cp_pro = '$cp_pro',
                est_pro = '$est_pro',
                id_pla2 = '$id_pla'
            WHERE id_pro = $id_pro
        ";
        
        $resProfesor = mysqli_query($db, $sqlProfesor);
        
        if(!$resProfesor) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar profesor: ' . mysqli_error($db)]);
            exit;
        }
        
        // 2. ACTUALIZAR EMPLEADO
        $sqlEmpleado = "
            UPDATE empleado SET
                cor_emp = '$cor_pro',
                nom_emp = '$nom_pro',
                app_emp = '$app_pro',
                apm_emp = '$apm_pro',
                tel_emp = '$tel_pro',
                nac_emp = " . (!empty($nac_pro) ? "'$nac_pro'" : "NULL") . ",
                cp_emp = '$cp_pro',
                dir_emp = '$dir_pro',
                id_pla6 = '$id_pla'
            WHERE id_emp = $id_emp
        ";
        
        $resEmpleado = mysqli_query($db, $sqlEmpleado);
        
        if(!$resEmpleado) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar empleado: ' . mysqli_error($db)]);
            exit;
        }
        
        // LOG
        if(function_exists('obtenerDescripcionPersonalLogServer')) {
            $des_log = obtenerDescripcionPersonalLogServer($nomResponsable, 'editó', 'profesor', $nombreProfesor);
            logServer('Cambio', $tipoUsuario, $id, 'Profesor', $des_log, $plantel);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Profesor actualizado correctamente'
        ]);
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'success' => false,
            'message' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SECCIÓN 6: VALIDAR CORREO ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'validar_correo') {
    
    try {
        $correo = strtolower(trim(mysqli_real_escape_string($db, $_POST['correo'])));
        $id_pro = isset($_POST['id_pro']) ? intval($_POST['id_pro']) : 0;
        
        if(empty($correo)) {
            echo json_encode(['disponible' => false, 'message' => 'vacio']);
            exit;
        }
        
        // BUSCAR EN PROFESOR
        $sql = "SELECT id_pro FROM profesor WHERE cor_pro = '$correo' LIMIT 1";
        $resultado = mysqli_query($db, $sql);
        
        if(mysqli_num_rows($resultado) == 0) {
            echo json_encode(['disponible' => true, 'message' => 'disponible']);
        } else {
            $fila = mysqli_fetch_assoc($resultado);
            
            if($id_pro > 0 && intval($fila['id_pro']) === $id_pro) {
                echo json_encode(['disponible' => true, 'message' => 'mio']);
            } else {
                echo json_encode(['disponible' => false, 'message' => 'ocupado']);
            }
        }
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'disponible' => false,
            'message' => 'error'
        ]);
        exit;
    }
}

// ========== SECCIÓN 7: TOGGLE ESTATUS ==========
if(isset($_POST['accion']) && $_POST['accion'] === 'toggle_estatus') {
    
    try {
        $id_pro = intval($_POST['id_pro']);
        $estatus_actual = mysqli_real_escape_string($db, $_POST['estatus_actual']);
        
        $nuevo_estatus = ($estatus_actual === 'Activo') ? 'Inactivo' : 'Activo';
        
        $sql = "UPDATE profesor SET est_pro = '$nuevo_estatus' WHERE id_pro = $id_pro";
        $resultado = mysqli_query($db, $sql);
        
        if($resultado) {
            // LOG
            $sqlNombre = "SELECT CONCAT(nom_pro, ' ', app_pro) AS nombre FROM profesor WHERE id_pro = $id_pro";
            $resNombre = mysqli_query($db, $sqlNombre);
            $filaNombre = mysqli_fetch_assoc($resNombre);
            $nombreProfesor = isset($filaNombre['nombre']) ? $filaNombre['nombre'] : 'Profesor';
            
            $accionLog = ($nuevo_estatus === 'Activo') ? 'activó' : 'desactivó';
            
            if(function_exists('obtenerDescripcionActivacionProfesorLogServer')) {
                $des_log = obtenerDescripcionActivacionProfesorLogServer($tipoUsuario, $nomResponsable, $accionLog, $nombreProfesor);
                logServer('Cambio', $tipoUsuario, $id, 'Profesor', $des_log, $plantel);
            }
            
            echo json_encode([
                'success' => true,
                'nuevo_estatus' => $nuevo_estatus,
                'message' => "Profesor $accionLog correctamente"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al cambiar estatus'
            ]);
        }
        exit;
        
    } catch(Exception $ex) {
        echo json_encode([
            'success' => false,
            'message' => $ex->getMessage()
        ]);
        exit;
    }
}

// ========== SI NO COINCIDE NINGUNA ACCIÓN ==========
echo json_encode([
    'error' => true,
    'message' => 'Acción no reconocida'
]);
exit;

?>