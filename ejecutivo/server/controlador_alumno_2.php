<?php  
// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ========== SECCIÓN 1: ELIMINACIÓN POR ID_CIT ==========
if( isset( $_POST['id_cit'] ) ){
    $id_cit = $_POST['id_cit'];
    $sql = "DELETE FROM alumno WHERE id_cit1 = '$id_cit'";
    $resultado = mysqli_query( $db, $sql );

    $response = array();
    if ($resultado) {
        $response['status'] = 200;
        $response['message'] = "Actualización exitosa";
    } else {
        $response['status'] = 500;
        $response['message'] = "Error en la actualización";
        $response['query'] = $sql;
    }
    echo json_encode($response);
    exit;
}

// ========== SECCIÓN 2: OBTENER ALUMNO ==========
else if( isset( $_POST['obtener_alumno'] ) ){
    $id_alu_ram = $_POST['id_alu_ram'];
    $sql = "
        SELECT * 
        FROM alumno
        INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
        WHERE id_alu_ram = $id_alu_ram
    ";
    $resultado = mysqli_query($db, $sql);
    $datos = mysqli_fetch_assoc($resultado);
    echo json_encode($datos);
    exit;
}

// ========== NUEVO: OBTENER ALUMNO PARA EDICIÓN ==========
else if( isset( $_POST['obtener_alumno_edicion'] ) ){
    $id_alu_ram = intval($_POST['id_alu_ram']);
    
    $sql = "
        SELECT 
            alumno.*,
            alu_ram.id_alu_ram,
            alu_ram.mon_alu_ram,
            alu_ram.tie_alu_ram,
            alu_ram.tit_alu_ram,
            alu_ram.id_ram3,
            alu_ram.val_alu_ram
        FROM alumno
        INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
        WHERE alu_ram.id_alu_ram = $id_alu_ram
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(['error' => true, 'mensaje' => mysqli_error($db)]);
        exit;
    }
    
    if (mysqli_num_rows($resultado) === 0) {
        echo json_encode(['error' => true, 'mensaje' => 'Alumno no encontrado']);
        exit;
    }
    
    $datos = mysqli_fetch_assoc($resultado);
    
    // Verificar si necesita mostrar periodicidad
    $valores_periodicidad = array(226, 227, 229, 230, 231, 232, 235, 237);
    $datos['mostrar_periodicidad'] = in_array($datos['id_ram3'], $valores_periodicidad);
    
    echo json_encode($datos);
    exit;
}

// ========== OBTENER ESTADO ALUMNO ==========
else if( isset( $_POST['obtener_estado_alumno'] ) ){
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $sql = "
        SELECT alumno.est_alu 
        FROM alumno
        INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
        WHERE alu_ram.id_alu_ram = $id_alu_ram
    ";
    $resultado = mysqli_query($db, $sql);
    $datos = mysqli_fetch_assoc($resultado);
    echo json_encode(['success' => true, 'est_alu' => $datos['est_alu']]);
    exit;
}

// ========== CAMBIAR ESTADO ALUMNO ==========
else if( isset( $_POST['cambiar_estado_alumno'] ) ){
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $nuevo_estado = $_POST['nuevo_estado'];
    
    // Validar que el nuevo estado sea válido
    if ($nuevo_estado !== 'Activo' && $nuevo_estado !== 'Inactivo') {
        echo json_encode(['success' => false, 'mensaje' => 'Estado no válido']);
        exit;
    }
    
    $sqlGetId = "SELECT id_alu1 FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $resultGetId = mysqli_query($db, $sqlGetId);
    
    if (!$resultGetId || mysqli_num_rows($resultGetId) === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Alumno no encontrado']);
        exit;
    }
    
    $id_alu = mysqli_fetch_assoc($resultGetId)['id_alu1'];
    
    $sql = "UPDATE alumno SET est_alu = '$nuevo_estado' WHERE id_alu = $id_alu";
    $resultado = mysqli_query($db, $sql);
    
    echo json_encode([
        'success' => $resultado, 
        'mensaje' => $resultado ? 'Estado actualizado' : 'Error al actualizar',
        'nuevo_estado' => $nuevo_estado
    ]);
    exit;
}

// ========== OBTENER VALIDACIÓN ALUMNO ==========
else if( isset( $_POST['obtener_validacion_alumno'] ) ){
    $id_alu_ram = intval($_POST['id_alu_ram']);
    
    $sql = "SELECT val_alu_ram FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado || mysqli_num_rows($resultado) === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Alumno no encontrado']);
        exit;
    }
    
    $datos = mysqli_fetch_assoc($resultado);
    echo json_encode([
        'success' => true, 
        'val_alu_ram' => $datos['val_alu_ram']
    ]);
    exit;
}

// ========== CAMBIAR VALIDACIÓN ALUMNO ==========
else if( isset( $_POST['cambiar_validacion_alumno'] ) ){
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $nueva_validacion = $_POST['nueva_validacion'];
    
    // Validar que el nuevo estado sea válido (0 o 1)
    if ($nueva_validacion !== '0' && $nueva_validacion !== '1' && $nueva_validacion !== 0 && $nueva_validacion !== 1) {
        echo json_encode(['success' => false, 'mensaje' => 'Validación no válida']);
        exit;
    }
    
    // Convertir a entero
    $nueva_validacion = intval($nueva_validacion);
    
    $sql = "UPDATE alu_ram SET val_alu_ram = $nueva_validacion WHERE id_alu_ram = $id_alu_ram";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        // Registrar en observaciones
        $texto_obs = ($nueva_validacion === 1) ? 'Alumno validado' : 'Validación de alumno removida';
        $sqlObs = "INSERT INTO observacion_alu_ram (id_alu_ram3, obs_alu_ram, fec_alu_ram) 
                   VALUES ($id_alu_ram, '$texto_obs', NOW())";
        mysqli_query($db, $sqlObs);
        
        echo json_encode([
            'success' => true, 
            'mensaje' => 'Validación actualizada correctamente',
            'nueva_validacion' => $nueva_validacion
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'mensaje' => 'Error al actualizar validación: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========== CAMBIAR VALIDACIÓN CURP (TOGGLE SIMPLE) ==========
else if( isset( $_POST['cambiar_validacion_curp'] ) ){
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $nueva_validacion = intval($_POST['nueva_validacion']);
    
    // Obtener id_alu desde alu_ram
    $sqlGetIdAlu = "SELECT id_alu1 FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $resultGetIdAlu = mysqli_query($db, $sqlGetIdAlu);
    
    if (!$resultGetIdAlu || mysqli_num_rows($resultGetIdAlu) === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Alumno no encontrado']);
        exit;
    }
    
    $id_alu = mysqli_fetch_assoc($resultGetIdAlu)['id_alu1'];
    
    // Determinar valor a guardar
    $valor_validacion = ($nueva_validacion === 1) ? 'Validado' : 'Pendiente';
    
    // Actualizar val_cur_alu en tabla alumno
    $sql = "UPDATE alumno SET val_cur_alu = '$valor_validacion' WHERE id_alu = $id_alu";
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        // Registrar en observaciones
        $texto_obs = ($nueva_validacion === 1) ? 'CURP marcado como VALIDADO manualmente' : 'CURP marcado como PENDIENTE manualmente';
        $sqlObs = "INSERT INTO observacion_alu_ram (id_alu_ram3, obs_alu_ram, fec_alu_ram) 
                   VALUES ($id_alu_ram, '$texto_obs', NOW())";
        mysqli_query($db, $sqlObs);
        
        echo json_encode([
            'success' => true, 
            'mensaje' => $texto_obs,
            'nueva_validacion' => $nueva_validacion,
            'valor_bd' => $valor_validacion
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'mensaje' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========== 🔥 ACTUALIZAR CAMPO ALUMNO (CORREGIDO) ==========
else if(isset($_POST['actualizar_campo_alumno'])) {
    
    // 🔥 LIMPIAR CUALQUIER OUTPUT PREVIO
    if (ob_get_length()) ob_clean();
    
    // 🔥 FORZAR HEADER JSON
    header('Content-Type: application/json; charset=utf-8');
    
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $campo = mysqli_real_escape_string($db, $_POST['campo']);
    $valor = mysqli_real_escape_string($db, $_POST['valor']);
    
    // 🔥 LISTA AMPLIADA DE CAMPOS PERMITIDOS
    $camposPermitidos = [
        'cor_alu', 'pas_alu', 'cor1_alu', 'tel_alu', 'tel2_alu', 
        'cur_alu', 'nac_alu', 'mon_alu_ram',
        'nom_alu', 'app_alu', 'apm_alu', 'ent2_alu', 'gen_alu'
    ];
    
    if (!in_array($campo, $camposPermitidos)) {
        die(json_encode(['success' => false, 'mensaje' => 'Campo no permitido: ' . $campo]));
    }
    
    // Obtener id_alu
    $sqlGetIdAlu = "SELECT id_alu1 FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $resultGetIdAlu = mysqli_query($db, $sqlGetIdAlu);
    
    if (!$resultGetIdAlu || mysqli_num_rows($resultGetIdAlu) === 0) {
        die(json_encode(['success' => false, 'mensaje' => 'Alumno no encontrado']));
    }
    
    $id_alu = mysqli_fetch_assoc($resultGetIdAlu)['id_alu1'];
    
    // 🔥 DETECTAR SI CAMBIÓ DATO QUE AFECTA CURP
    $camposQueInvalidanCURP = ['nom_alu', 'app_alu', 'apm_alu', 'ent2_alu', 'nac_alu', 'gen_alu'];
    $debeInvalidarCURP = in_array($campo, $camposQueInvalidanCURP);
    
    // Determinar tabla y ejecutar UPDATE
    if ($campo === 'mon_alu_ram') {
        $sql = "UPDATE alu_ram SET mon_alu_ram = '$valor' WHERE id_alu_ram = $id_alu_ram";
    } else {
        $sql = "UPDATE alumno SET $campo = '$valor' WHERE id_alu = $id_alu";
    }
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        die(json_encode(['success' => false, 'mensaje' => 'Error al actualizar: ' . mysqli_error($db)]));
    }
    
    // 🔥 SI CAMBIÓ UN CAMPO QUE AFECTA CURP, INVALIDARLO
    if ($debeInvalidarCURP) {
        $sqlInvalidarCURP = "UPDATE alumno SET val_cur_alu = 'Pendiente' WHERE id_alu = $id_alu";
        mysqli_query($db, $sqlInvalidarCURP);
        $obs = "Campo '$campo' actualizado a '$valor' - CURP marcado como PENDIENTE";
    } else {
        $obs = "Campo '$campo' actualizado a '$valor'";
    }
    
    // Registrar en observaciones
    $sqlObs = "INSERT INTO observacion_alu_ram (id_alu_ram3, obs_alu_ram, fec_alu_ram) 
               VALUES ($id_alu_ram, '$obs', NOW())";
    mysqli_query($db, $sqlObs);
    
    // 🔥 RESPUESTA EXITOSA
    die(json_encode([
        'success' => true, 
        'mensaje' => 'Campo actualizado correctamente'
    ]));
}

// ========== VALIDAR CURP CONTRA DATOS ==========
else if(isset($_POST['validar_curp'])) {
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $curp = strtoupper(trim(mysqli_real_escape_string($db, $_POST['curp'])));
    $nombre = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nombre'])));
    $app_paterno = strtoupper(trim(mysqli_real_escape_string($db, $_POST['app_paterno'])));
    $app_materno = strtoupper(trim(mysqli_real_escape_string($db, $_POST['app_materno'])));
    $entidad = strtoupper(trim(mysqli_real_escape_string($db, $_POST['entidad'])));
    $fecha_nac = mysqli_real_escape_string($db, $_POST['fecha_nac']);
    
    // Validar formato CURP
    if (!preg_match('/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9]{2}$/', $curp)) {
        echo json_encode([
            'success' => false,
            'valido' => false,
            'mensaje' => 'Formato CURP inválido'
        ]);
        exit;
    }
    
    // Extraer componentes del CURP
    $curp_ap_paterno_letra = substr($curp, 0, 1);
    $curp_ap_paterno_vocal = substr($curp, 1, 1);
    $curp_ap_materno_letra = substr($curp, 2, 1);
    $curp_nombre_letra = substr($curp, 3, 1);
    $curp_fecha = substr($curp, 4, 6);
    $curp_entidad = substr($curp, 11, 2);
    
    // Validar fecha
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_nac);
    if (!$fecha_obj) {
        echo json_encode([
            'success' => false,
            'valido' => false,
            'mensaje' => 'Fecha de nacimiento inválida'
        ]);
        exit;
    }
    
    $curp_fecha_esperada = $fecha_obj->format('ymd');
    
    // FUNCIÓN PARA OBTENER PRIMERA VOCAL INTERNA
    function obtenerPrimeraVocalInterna($texto) {
        $vocales = ['A', 'E', 'I', 'O', 'U'];
        for ($i = 1; $i < strlen($texto); $i++) {
            if (in_array($texto[$i], $vocales)) {
                return $texto[$i];
            }
        }
        return 'X';
    }
    
    // FUNCIÓN PARA OBTENER PRIMERA CONSONANTE INTERNA
    function obtenerPrimeraConsonanteInterna($texto) {
        $vocales = ['A', 'E', 'I', 'O', 'U'];
        for ($i = 1; $i < strlen($texto); $i++) {
            if (!in_array($texto[$i], $vocales) && ctype_alpha($texto[$i])) {
                return $texto[$i];
            }
        }
        return 'X';
    }
    
    // Validar componentes
    $errores = array();
    
    // Apellido paterno
    $ap_paterno_letra_esperada = substr($app_paterno, 0, 1);
    $ap_paterno_vocal_esperada = obtenerPrimeraVocalInterna($app_paterno);
    
    if ($ap_paterno_letra_esperada !== $curp_ap_paterno_letra) {
        $errores[] = "Apellido paterno (primera letra) no coincide";
    }
    
    if ($ap_paterno_vocal_esperada !== $curp_ap_paterno_vocal) {
        $errores[] = "Apellido paterno (vocal) no coincide";
    }
    
    // Apellido materno
    if (!empty($app_materno)) {
        $ap_materno_letra_esperada = substr($app_materno, 0, 1);
        if ($ap_materno_letra_esperada !== $curp_ap_materno_letra) {
            $errores[] = "Apellido materno no coincide";
        }
    }
    
    // Nombre
    $nombre_letra_esperada = substr($nombre, 0, 1);
    if ($nombre_letra_esperada !== $curp_nombre_letra) {
        $errores[] = "Nombre no coincide";
    }
    
    // Fecha
    if ($curp_fecha !== $curp_fecha_esperada) {
        $errores[] = "Fecha de nacimiento no coincide";
    }
    
    // Entidad
    if (!empty($entidad) && $entidad !== $curp_entidad) {
        $errores[] = "Entidad de nacimiento no coincide";
    }
    
    if (count($errores) > 0) {
        echo json_encode([
            'success' => true,
            'valido' => false,
            'mensaje' => implode(' | ', $errores)
        ]);
        exit;
    }
    
    // TODO CORRECTO - MARCAR COMO VALIDADO
    $sqlGetIdAlu = "SELECT id_alu1 FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $resultGetIdAlu = mysqli_query($db, $sqlGetIdAlu);
    $id_alu = mysqli_fetch_assoc($resultGetIdAlu)['id_alu1'];
    
    $sqlValidar = "UPDATE alumno SET val_cur_alu = 'Validado' WHERE id_alu = $id_alu";
    $resultadoValidar = mysqli_query($db, $sqlValidar);
    
    if ($resultadoValidar) {
        $sqlObs = "INSERT INTO observacion_alu_ram (id_alu_ram3, obs_alu_ram, fec_alu_ram) 
                   VALUES ($id_alu_ram, 'CURP validado correctamente', NOW())";
        mysqli_query($db, $sqlObs);
        
        echo json_encode([
            'success' => true,
            'valido' => true,
            'mensaje' => 'CURP validado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'valido' => false,
            'mensaje' => 'Error al actualizar validación: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========== GENERAR CURP AUTOMÁTICAMENTE ==========
else if(isset($_POST['generar_curp'])) {
    $id_alu_ram = intval($_POST['id_alu_ram']);
    $nombre_original = strtoupper(trim(mysqli_real_escape_string($db, $_POST['nombre'])));
    $app_paterno_original = strtoupper(trim(mysqli_real_escape_string($db, $_POST['app_paterno'])));
    $app_materno_original = strtoupper(trim(mysqli_real_escape_string($db, $_POST['app_materno'])));
    $sexo = strtoupper(trim(mysqli_real_escape_string($db, $_POST['sexo'])));
    $entidad = strtoupper(trim(mysqli_real_escape_string($db, $_POST['entidad'])));
    $fecha_nac = mysqli_real_escape_string($db, $_POST['fecha_nac']);
    
    // Validar datos mínimos
    if (empty($nombre_original) || empty($app_paterno_original) || empty($fecha_nac) || empty($sexo) || empty($entidad)) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Faltan datos requeridos para generar CURP'
        ]);
        exit;
    }
    
    // Validar sexo
    if ($sexo !== 'H' && $sexo !== 'M') {
        echo json_encode([
            'success' => false,
            'mensaje' => 'El género debe ser H (Hombre) o M (Mujer)'
        ]);
        exit;
    }
    
    // Parsear fecha
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_nac);
    if (!$fecha_obj) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Fecha de nacimiento inválida'
        ]);
        exit;
    }
    
    // FUNCIONES AUXILIARES
    function normalizarTexto($texto) {
        $texto = strtoupper(trim($texto));
        $acentos = array('Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ú'=>'U', 'Ü'=>'U');
        $texto = strtr($texto, $acentos);
        return $texto;
    }
    
    function quitarPreposiciones($texto) {
        $preposiciones = array('DE ', 'DEL ', 'LA ', 'LAS ', 'LOS ', 'Y ', 'MC ', 'MAC ', 'VON ', 'VAN ', 'DER ');
        foreach($preposiciones as $prep) {
            $texto = str_replace($prep, '', $texto);
        }
        return trim($texto);
    }
    
    function obtenerPrimeraVocalInterna($texto) {
        $vocales = array('A', 'E', 'I', 'O', 'U');
        $texto = str_replace('Ñ', 'X', $texto);
        for ($i = 1; $i < strlen($texto); $i++) {
            if (in_array($texto[$i], $vocales)) {
                return $texto[$i];
            }
        }
        return 'X';
    }
    
    function obtenerPrimeraConsonanteInterna($texto) {
        $vocales = array('A', 'E', 'I', 'O', 'U');
        $texto = str_replace('Ñ', 'X', $texto);
        for ($i = 1; $i < strlen($texto); $i++) {
            $char = $texto[$i];
            if (!in_array($char, $vocales) && ctype_alpha($char)) {
                return $char;
            }
        }
        return 'X';
    }
    
    function filtrarNombre($nombre) {
        $partes = explode(' ', $nombre);
        $nombresComunes = array('MARIA', 'MA', 'MA.', 'JOSE', 'J', 'J.');
        
        if (count($partes) > 1 && in_array($partes[0], $nombresComunes)) {
            return $partes[1];
        }
        return $partes[0];
    }
    
    function verificarPalabraInconveniente($curp4) {
        $inconvenientes = array(
            'BACA', 'BAKA', 'BUEI', 'BUEY', 'CACA', 'CACO', 'CAGA', 'CAGO', 
            'CAKA', 'CAKO', 'COGE', 'COGI', 'COJA', 'COJE', 'COJI', 'COJO', 
            'COLA', 'CULO', 'FALO', 'FETO', 'GETA', 'GUEI', 'GUEY', 'JETA', 
            'JOTO', 'KACA', 'KACO', 'KAGA', 'KAGO', 'KAKA', 'KAKO', 'KOGE', 
            'KOGI', 'KOJA', 'KOJE', 'KOJI', 'KOJO', 'KOLA', 'KULO', 'LILO', 
            'LOCA', 'LOCO', 'LOKA', 'LOKO', 'MAME', 'MAMO', 'MEAR', 'MEAS', 
            'MEON', 'MIAR', 'MION', 'MOCO', 'MOKO', 'MULA', 'MULO', 'NACA', 
            'NACO', 'PEDA', 'PEDO', 'PENE', 'PIPI', 'PITO', 'POPO', 'PUTA', 
            'PUTO', 'QULO', 'RATA', 'ROBA', 'ROBE', 'ROBO', 'RUIN', 'SENO', 
            'TETA', 'VACA', 'VAGA', 'VAGO', 'VAKA', 'VUEI', 'VUEY', 'WUEI', 'WUEY'
        );
        return in_array($curp4, $inconvenientes);
    }
    
    // PROCESAR DATOS
    $nombre = normalizarTexto($nombre_original);
    $app_paterno = normalizarTexto(quitarPreposiciones($app_paterno_original));
    $app_materno = normalizarTexto(quitarPreposiciones($app_materno_original));
    $nombre_filtrado = filtrarNombre($nombre);
    
    // GENERAR CURP
    $curp = '';
    
    // 1. Primera letra del apellido paterno
    $letra1 = !empty($app_paterno) ? substr($app_paterno, 0, 1) : 'X';
    $curp .= $letra1;
    
    // 2. Primera vocal interna del apellido paterno
    $vocal_paterno = !empty($app_paterno) ? obtenerPrimeraVocalInterna($app_paterno) : 'X';
    $curp .= $vocal_paterno;
    
    // 3. Primera letra del apellido materno
    $letra_materno = !empty($app_materno) ? substr($app_materno, 0, 1) : 'X';
    $curp .= $letra_materno;
    
    // 4. Primera letra del nombre
    $letra_nombre = !empty($nombre_filtrado) ? substr($nombre_filtrado, 0, 1) : 'X';
    $curp .= $letra_nombre;
    
    // Verificar palabra inconveniente
    if (verificarPalabraInconveniente($curp)) {
        $curp = $letra1 . 'X' . $letra_materno . $letra_nombre;
    }
    
    // 5. Fecha (AAMMDD)
    $curp .= $fecha_obj->format('ymd');
    
    // 6. Sexo (H o M)
    $curp .= $sexo;
    
    // 7. Entidad (2 letras)
    $curp .= $entidad;
    
    // 8. Primera consonante interna del apellido paterno
    $cons_paterno = !empty($app_paterno) ? obtenerPrimeraConsonanteInterna($app_paterno) : 'X';
    $curp .= $cons_paterno;
    
    // 9. Primera consonante interna del apellido materno
    $cons_materno = !empty($app_materno) ? obtenerPrimeraConsonanteInterna($app_materno) : 'X';
    $curp .= $cons_materno;
    
    // 10. Primera consonante interna del nombre
    $cons_nombre = !empty($nombre_filtrado) ? obtenerPrimeraConsonanteInterna($nombre_filtrado) : 'X';
    $curp .= $cons_nombre;
    
    // 11. Homoclave (2 caracteres)
    $anio_nac = intval($fecha_obj->format('Y'));
    if ($anio_nac < 2000) {
        $curp .= '00';
    } else {
        $curp .= 'A0';
    }
    
    // Asegurar 18 caracteres
    $curp = substr(str_pad($curp, 18, '0'), 0, 18);
    
    // GUARDAR EN BD
    $sqlGetIdAlu = "SELECT id_alu1 FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $resultGetIdAlu = mysqli_query($db, $sqlGetIdAlu);
    
    if (!$resultGetIdAlu || mysqli_num_rows($resultGetIdAlu) === 0) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Alumno no encontrado'
        ]);
        exit;
    }
    
    $id_alu = mysqli_fetch_assoc($resultGetIdAlu)['id_alu1'];
    
    $sqlUpdate = "UPDATE alumno SET cur_alu = '$curp', val_cur_alu = 'Generado' WHERE id_alu = $id_alu";
    $resultado = mysqli_query($db, $sqlUpdate);
    
    if ($resultado) {
        $sqlObs = "INSERT INTO observacion_alu_ram (id_alu_ram3, obs_alu_ram, fec_alu_ram) 
                   VALUES ($id_alu_ram, 'CURP generado automáticamente: $curp', NOW())";
        mysqli_query($db, $sqlObs);
        
        echo json_encode([
            'success' => true,
            'curp' => $curp,
            'mensaje' => 'CURP generado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al guardar CURP: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========== OBTENER DOMICILIACIÓN ==========
else if(isset($_POST['obtener_domiciliacion'])) {
    
    if(!isset($_POST['id_alu_ram'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falta el id_alu_ram'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    $sql = "SELECT 
                ar.dom_alu_ram, 
                ar.customer_id, 
                ar.fec_dom_alu_ram,
                pm.payment_method_id,
                pm.last_4,
                pm.brand,
                pm.exp_month,
                pm.exp_year
            FROM alu_ram ar
            LEFT JOIN payment_methods pm ON pm.id_alu_ram = ar.id_alu_ram
            WHERE ar.id_alu_ram = '$id_alu_ram'";
    
    $resultado = mysqli_query($db, $sql);
    
    if($resultado && mysqli_num_rows($resultado) > 0) {
        $datos = mysqli_fetch_assoc($resultado);
        
        $tieneActiva = ($datos['dom_alu_ram'] == 'Activo');
        $fechaFormateada = null;
        
        if(!empty($datos['fec_dom_alu_ram'])) {
            $fechaFormateada = date('d/M/y', strtotime($datos['fec_dom_alu_ram']));
        }
        
        echo json_encode([
            'success' => true,
            'data' => [
                'activa' => $tieneActiva,
                'estado' => $datos['dom_alu_ram'],
                'customer_id' => $datos['customer_id'],
                'payment_method_id' => $datos['payment_method_id'],
                'last_4' => $datos['last_4'],
                'brand' => $datos['brand'],
                'exp_month' => $datos['exp_month'],
                'exp_year' => $datos['exp_year'],
                'fecha' => $fechaFormateada
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se encontró el registro'
        ]);
    }
    
    exit;
}

// ========== CANCELAR DOMICILIACIÓN ==========
else if(isset($_POST['cancelar_domiciliacion'])) {
    
    if(!isset($_POST['id_alu_ram'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falta el id_alu_ram'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    $sqlAluRam = "UPDATE alu_ram 
                  SET 
                      dom_alu_ram = 'Inactivo',
                      fec_dom_alu_ram = NOW()
                  WHERE id_alu_ram = '$id_alu_ram'";
    
    $resultadoAluRam = mysqli_query($db, $sqlAluRam);
    
    if(!$resultadoAluRam) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al actualizar alu_ram: ' . mysqli_error($db)
        ]);
        exit;
    }
    
    $sqlDelete = "DELETE FROM payment_methods WHERE id_alu_ram = '$id_alu_ram'";
    $resultadoDelete = mysqli_query($db, $sqlDelete);
    
    if($resultadoDelete) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Domiciliación cancelada correctamente',
            'data' => [
                'id_alu_ram' => $id_alu_ram,
                'estado' => 'Inactivo'
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al eliminar tarjeta: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========== ENVIAR CORREO DE PRUEBA ==========
else if(isset($_POST['enviar_correo_prueba'])) {
    
    if(!isset($_POST['id_alu_ram'])) {
        echo json_encode([
            'success' => false,
            'mensaje' => '❌ Falta el id_alu_ram'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    $resultado = enviar_correo_alumno_prueba($id_alu_ram, $db);
    
    if(!is_array($resultado)) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error: La función no retornó un array válido'
        ]);
        exit;
    }
    
    if(!isset($resultado['success'])) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error: La función no retornó el campo "success"'
        ]);
        exit;
    }
    
    if($resultado['success']) {
        echo json_encode([
            'success' => true,
            'correo' => isset($resultado['correo']) ? $resultado['correo'] : 'N/A',
            'mensaje' => 'Correo enviado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => isset($resultado['mensaje']) ? $resultado['mensaje'] : 'Error desconocido'
        ]);
    }
    
    exit;
}

// ========== SECCIÓN 3: ELIMINAR ALUMNO ==========
else if( isset( $_POST['eliminar_alumno'] ) ) {
    $id_alu_ram = $_POST['eliminar_alumno'];
    $sqlAlumno = "SELECT * FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
    $id_alu = obtener_datos_consulta( $db, $sqlAlumno )['datos']['id_alu1'];

    $sql = "DELETE FROM alumno WHERE id_alu = '$id_alu'";
    $resultado = mysqli_query($db, $sql);

    $response = array();
    if ($resultado) {
        $response['status'] = 200;
        $response['message'] = "Actualización exitosa";
    } else {
        $response['status'] = 500;
        $response['message'] = "Error en la actualización";
        $response['query'] = $sql;
    }
    echo json_encode($response);
    exit;
} 

// ========== SECCIÓN 4: EDITAR ALUMNO ==========
else if( isset( $_POST['editar_alumno'] ) ) {
    $id_alu = mysqli_real_escape_string($db, $_POST['id_alu']);
    $nom_alu = mysqli_real_escape_string($db, $_POST['nom_alu']);
    $app_alu = mysqli_real_escape_string($db, $_POST['app_alu']);
    $apm_alu = mysqli_real_escape_string($db, $_POST['apm_alu']);
    $tel_alu = mysqli_real_escape_string($db, $_POST['tel_alu']);
    $gen_alu = mysqli_real_escape_string($db, $_POST['gen_alu']);
    $nac_alu = mysqli_real_escape_string($db, $_POST['nac_alu']);
    $cur_alu = mysqli_real_escape_string($db, $_POST['cur_alu']);
    $tut_alu = mysqli_real_escape_string($db, $_POST['tut_alu']);
    $tel2_alu = mysqli_real_escape_string($db, $_POST['tel2_alu']);
    $ocu_alu = mysqli_real_escape_string($db, $_POST['ocu_alu']);
    $dir_alu = mysqli_real_escape_string($db, $_POST['direccion']);
    $cp_alu = mysqli_real_escape_string($db, $_POST['cp_alu']);
    $cor_alu = mysqli_real_escape_string($db, $_POST['correo']);
    $pas_alu = mysqli_real_escape_string($db, $_POST['pas_alu']);
    $mon_alu_ram = mysqli_real_escape_string($db, $_POST['mon_alu_ram']);
    
    $tit_alu_ram = isset($_POST['tit_alu_ram']) ? mysqli_real_escape_string($db, $_POST['tit_alu_ram']) : '';
    
    if( $_POST['tie_alu_ram'] == 0 ){
        $tie_alu_ram = NULL;
    } else {
        $tie_alu_ram = mysqli_real_escape_string($db, $_POST['tie_alu_ram']);
    }
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);

    $sql2 = "
        UPDATE alu_ram
        SET
            tie_alu_ram = '$tie_alu_ram',
            mon_alu_ram = '$mon_alu_ram',
            tit_alu_ram = '$tit_alu_ram'
        WHERE id_alu_ram = '$id_alu_ram'
    ";
    $resultado2 = mysqli_query( $db, $sql2 );

    $sql = "
        UPDATE alumno
        SET
            nom_alu = '$nom_alu',
            app_alu = '$app_alu',
            apm_alu = '$apm_alu',
            tel_alu = '$tel_alu',
            gen_alu = '$gen_alu',
            nac_alu = '$nac_alu',
            cur_alu = '$cur_alu',
            tut_alu = '$tut_alu',
            tel2_alu = '$tel2_alu',
            ocu_alu = '$ocu_alu',
            dir_alu = '$dir_alu',
            cp_alu = '$cp_alu',
            cor_alu = '$cor_alu',
            pas_alu = '$pas_alu'
        WHERE id_alu = '$id_alu'
    ";
    $resultado = mysqli_query( $db, $sql );

    $response = array();
    if ($resultado && $resultado2) {
        $response['status'] = 200;
        $response['message'] = "Actualización exitosa";
    } else {
        $response['status'] = 500;
        $response['message'] = "Error en la actualización";
        $response['query'] = $sql;
        $response['query2'] = $sql2;
        $response['error'] = mysqli_error($db);
    }
    echo json_encode($response);
    exit;
} 

// ========== SECCIÓN 5: BÚSQUEDA Y LISTADO DE ALUMNOS ==========
else {
    
    // OBTENER FECHAS PARA FILTRO
    $fecha_inicio = '';
    $fecha_fin = '';
    $filtroHabilitado = isset($_POST['filtro_periodo_habilitado']) && $_POST['filtro_periodo_habilitado'] === 'true';

    if($filtroHabilitado && isset($_POST['fecha_inicio_mes']) && isset($_POST['fecha_fin_mes']) 
    && !empty($_POST['fecha_inicio_mes']) && !empty($_POST['fecha_fin_mes'])) {
        $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio_mes']);
        $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin_mes']);
    }
    
    // OBTENER FILTRO DE BOLSA DE PAGO
    $bolsaPago = '';
    $bolsaSQL = '';
    if(isset($_POST['bolsa_pago']) && !empty($_POST['bolsa_pago'])) {
        $bolsaPago = mysqli_real_escape_string($db, $_POST['bolsa_pago']);
        
        switch($bolsaPago) {
            case 'colegiatura':
                $bolsaSQL = 'Colegiatura';
                break;
            case 'inscripcion':
                $bolsaSQL = 'Inscripción';
                break;
            case 'reinscripcion':
                $bolsaSQL = 'Reinscripción';
                break;
            case 'tramite':
                $bolsaSQL = 'Otros';
                break;
            case 'adeudo':
            default:
                $bolsaSQL = '';
                break;
        }
    }
    
    $hoy = date('Y-m-d');
    
    if(isset($_POST['id_gen_maestro']) && !empty($_POST['id_gen_maestro'])) {
        $id_gen = intval($_POST['id_gen_maestro']);
        
        $nivelArray = array();
        if(isset($_POST['nivel_ajax']) && !empty($_POST['nivel_ajax'])) {
            $nivelArray = $_POST['nivel_ajax'];
        }
        
        $nivelCondicion = "";
        if(!empty($nivelArray)) {
            $nivelLimpios = array();
            foreach($nivelArray as $nivel) {
                $nivelLimpios[] = "'" . mysqli_real_escape_string($db, $nivel) . "'";
            }
            $nivelStr = implode(',', $nivelLimpios);
            $nivelCondicion = " AND rama.gra_ram IN ($nivelStr)";
        }
        
        $sql = "
            SELECT 
                plantel.nom_pla AS centro,
                alu_ram.id_alu_ram,
                alu_ram.id_alu1,
                alu_ram.dom_alu_ram AS domiciliacion,
                alu_ram.val_alu_ram,
                alumno.est_alu,
                alumno.val_cur_alu,
                CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu) AS nom_alu,
                CASE 
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' AND '$bolsaSQL' != '' THEN
                        obtener_cobrado_alumno_periodo_tipo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' THEN
                        obtener_cobrado_alumno_periodo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin')
                    WHEN '$bolsaSQL' != '' THEN
                        obtener_cobrado_alumno_tipo(alu_ram.id_alu_ram, '$bolsaSQL')
                    ELSE
                        obtener_cobrado_alumno(alu_ram.id_alu_ram)
                END AS cobrado_alumno,
                CASE 
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' AND '$bolsaSQL' != '' THEN
                        obtener_potencial_alumno_periodo_tipo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' THEN
                        obtener_potencial_alumno_periodo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin')
                    WHEN '$bolsaSQL' != '' THEN
                        obtener_potencial_alumno_tipo(alu_ram.id_alu_ram, '$bolsaSQL')
                    ELSE
                        obtener_potencial_ALUMNO(alu_ram.id_alu_ram)
                END AS adeudo_alumno,
                alumno.bol_alu,
                alumno.tel_alu,
                alumno.ing_alu,
                alumno.tel2_alu,
                generacion.nom_gen,
                generacion.ini_gen,
                generacion.fin_gen,
                rama.gra_ram AS nivel_academico,
                generacion.nom_gen AS estado_grupo,
                OBTENER_ESTATUS_GENERAL(alu_ram.id_alu_ram, generacion.fin_gen, alu_ram.est1_alu_ram) AS estatus_general,
                alumno.cur_alu,
                alumno.val_cur_alu,
                alumno.cor_alu,
                alumno.pas_alu,
                alu_ram.id_gen1,
                OBTENER_CARGA_ALUMNO(alu_ram.id_alu_ram) AS carga_alumno,
                OBTENER_NOMBRE_EJECUTIVO_ALUMNO(alu_ram.id_alu1) AS nom_eje,
                obtener_documento_pendiente(alu_ram.id_alu_ram) AS documento_pendiente, 
                obtener_actividades_vencidas(alu_ram.id_alu_ram) AS actividades_vencidas 
            FROM alu_ram
            JOIN alumno ON (alumno.id_alu = alu_ram.id_alu1)
            JOIN rama ON (rama.id_ram = alu_ram.id_ram3)
            JOIN plantel ON (plantel.id_pla = rama.id_pla1)
            JOIN generacion ON (generacion.id_gen = alu_ram.id_gen1)
            WHERE alu_ram.id_gen1 = $id_gen
            $nivelCondicion
            ORDER BY alu_ram.id_alu_ram DESC
        ";
    } 
    else {
        $esBusquedaNormal = false;
        $datosAlumno = '';
        
        if(isset($_POST['obtener_todos']) && $_POST['obtener_todos'] == true) {
            $datosAlumno = '';
            $esBusquedaNormal = false;
        } else {
            $datosAlumno = isset($_POST['datosAlumno']) ? trim(preg_replace('!\s+!', ' ', $_POST['datosAlumno'])) : '';
            $esBusquedaNormal = true;
        }
        
        $plantelesCondicion = "";
        $plantelesArray = array();
        
        if(isset($_POST['planteles_seleccionados']) && !empty($_POST['planteles_seleccionados'])) {
            $plantelesArray = $_POST['planteles_seleccionados'];
        }
        elseif(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
            $plantelesArray = $_POST['planteles_ajax'];
        }
        else {
            $plantelesArray = array($plantel);
        }
        
        if(!empty($plantelesArray)) {
            $plantelesLimpios = array();
            foreach($plantelesArray as $p) {
                $plantelesLimpios[] = intval($p);
            }
            $plantelesStr = implode(',', $plantelesLimpios);
            $plantelesCondicion = " AND plantel.id_pla IN ($plantelesStr)";
        } else {
            $plantelesCondicion = " AND plantel.id_pla = '$plantel'";
        }
        
        $nivelArray = array();
        if(isset($_POST['nivel_ajax']) && !empty($_POST['nivel_ajax'])) {
            $nivelArray = $_POST['nivel_ajax'];
        }
        
        $nivelCondicion = "";
        if(!empty($nivelArray)) {
            $nivelLimpios = array();
            foreach($nivelArray as $nivel) {
                $nivelLimpios[] = "'" . mysqli_real_escape_string($db, $nivel) . "'";
            }
            $nivelStr = implode(',', $nivelLimpios);
            $nivelCondicion = " AND rama.gra_ram IN ($nivelStr)";
        }
        
        $estatusArray = array();
        if(isset($_POST['estatus_ajax']) && !empty($_POST['estatus_ajax'])) {
            $estatusArray = $_POST['estatus_ajax'];
        }
        
        $gruposArray = array();
        if(isset($_POST['grupos_ajax']) && !empty($_POST['grupos_ajax'])) {
            $gruposArray = $_POST['grupos_ajax'];
        }
        
        $gruposCondicion = "";
        if(!empty($gruposArray)) {
            $condicionesGrupo = array();
            
            foreach($gruposArray as $grupo) {
                switch($grupo) {
                    case 'EN CURSO':
                        $condicionesGrupo[] = "(generacion.ini_gen <= '$hoy' AND generacion.fin_gen >= '$hoy')";
                        break;
                    case 'POR COMENZAR':
                        $condicionesGrupo[] = "(generacion.ini_gen > '$hoy')";
                        break;
                    case 'VENCIDOS':
                        $condicionesGrupo[] = "(generacion.fin_gen < '$hoy')";
                        break;
                }
            }
            
            if(!empty($condicionesGrupo)) {
                $gruposCondicion = " AND (" . implode(' OR ', $condicionesGrupo) . ")";
            }
        }
        
        $generacionesArray = array();
        if(isset($_POST['generaciones_ajax']) && !empty($_POST['generaciones_ajax'])) {
            $generacionesArray = $_POST['generaciones_ajax'];
        }

        $generacionesCondicion = "";
        if(!empty($generacionesArray)) {
            $generacionesLimpios = array();
            foreach($generacionesArray as $gen) {
                $generacionesLimpios[] = intval($gen);
            }
            $generacionesStr = implode(',', $generacionesLimpios);
            
            $generacionesCondicion = " AND (
                generacion.id_gen IN ($generacionesStr)
                AND (
                    generacion.ini_gen > '$hoy'
                    OR
                    (
                        generacion.ini_gen <= '$hoy'
                        AND EXISTS (
                            SELECT 1 FROM alu_ram ar2 
                            WHERE ar2.id_gen1 = generacion.id_gen
                        )
                    )
                )
            )";
        }
        
        $sql = "
            SELECT 
                plantel.nom_pla AS centro,
                alu_ram.id_alu_ram,
                alu_ram.id_alu1,
                alu_ram.dom_alu_ram AS domiciliacion,
                alu_ram.val_alu_ram,
                alumno.est_alu,
                alumno.val_cur_alu,
                alumno.cur_alu,
                CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu) AS nom_alu,
                CASE 
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' AND '$bolsaSQL' != '' THEN
                        obtener_cobrado_alumno_periodo_tipo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' THEN
                        obtener_cobrado_alumno_periodo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin')
                    WHEN '$bolsaSQL' != '' THEN
                        obtener_cobrado_alumno_tipo(alu_ram.id_alu_ram, '$bolsaSQL')
                    ELSE
                        obtener_cobrado_alumno(alu_ram.id_alu_ram)
                END AS cobrado_alumno,
                CASE 
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' AND '$bolsaSQL' != '' THEN
                        obtener_potencial_alumno_periodo_tipo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin', '$bolsaSQL')
                    WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' THEN
                        obtener_potencial_alumno_periodo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin')
                    WHEN '$bolsaSQL' != '' THEN
                        obtener_potencial_alumno_tipo(alu_ram.id_alu_ram, '$bolsaSQL')
                    ELSE
                        obtener_potencial_ALUMNO(alu_ram.id_alu_ram)
                END AS adeudo_alumno,
                alumno.bol_alu,
                alumno.tel_alu,
                alumno.ing_alu,
                alumno.tel2_alu,
                generacion.nom_gen,
                generacion.ini_gen,
                generacion.fin_gen,
                rama.gra_ram AS nivel_academico,
                CASE 
                    WHEN generacion.ini_gen > '$hoy' THEN 'POR COMENZAR'
                    WHEN generacion.ini_gen <= '$hoy' AND generacion.fin_gen >= '$hoy' THEN 'EN CURSO'
                    ELSE 'VENCIDOS'
                END AS estado_grupo,
                OBTENER_ESTATUS_GENERAL(alu_ram.id_alu_ram, generacion.fin_gen, alu_ram.est1_alu_ram) AS estatus_general,
                alumno.cor_alu,
                alumno.pas_alu,
                alu_ram.id_gen1,
                OBTENER_CARGA_ALUMNO(alu_ram.id_alu_ram) AS carga_alumno,
                OBTENER_NOMBRE_EJECUTIVO_ALUMNO(alu_ram.id_alu1) AS nom_eje,
                obtener_documento_pendiente(alu_ram.id_alu_ram) AS documento_pendiente, 
                obtener_actividades_vencidas(alu_ram.id_alu_ram) AS actividades_vencidas 
            FROM alu_ram
            JOIN alumno ON (alumno.id_alu = alu_ram.id_alu1)
            JOIN rama ON (rama.id_ram = alu_ram.id_ram3)
            JOIN plantel ON (plantel.id_pla = rama.id_pla1)
            JOIN generacion ON (generacion.id_gen = alu_ram.id_gen1)
            WHERE 1=1 
            $plantelesCondicion 
            $nivelCondicion
            $gruposCondicion
            $generacionesCondicion
        ";
    
        if ($esBusquedaNormal && isset($_POST['datosAlumno']) && $_POST['datosAlumno'] != '') {
            $datosAlumno = mysqli_real_escape_string($db, $datosAlumno);
            $sql .= " AND 
                ( alu_ram.id_alu_ram LIKE '%$datosAlumno%' OR  
                  alumno.bol_alu LIKE '%$datosAlumno%' OR  
                  UPPER(REPLACE(CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu), '  ', ' ')) LIKE UPPER(_utf8 '%$datosAlumno%') COLLATE utf8_general_ci OR 
                  UPPER(generacion.nom_gen) LIKE UPPER(_utf8 '%$datosAlumno%') COLLATE utf8_general_ci OR 
                  alumno.tel_alu LIKE '%$datosAlumno%' OR  
                  UPPER(alumno.cor_alu) LIKE UPPER('%$datosAlumno%') ) 
            ";
        }
        
        if(!empty($estatusArray)) {
            $estatusLimpios = array();
            foreach($estatusArray as $e) {
                $estatusLimpios[] = "'" . mysqli_real_escape_string($db, $e) . "'";
            }
            $estatusStr = implode(',', $estatusLimpios);
            $sql .= " HAVING estatus_general IN ($estatusStr)";
        }
    
        $sql .= ' ORDER BY alu_ram.id_alu_ram DESC';
    }

    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        $error = array(
            'error' => true,
            'mensaje' => mysqli_error($db),
            'sql' => $sql
        );
        echo json_encode($error);
        exit;
    }
    
    $alumnos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
         
         $id_alu = $fila['id_alu1'];
         $sqlToken = "SELECT token FROM alumno_token WHERE alumno = '$id_alu' ORDER BY id DESC LIMIT 1";
         $resultToken = mysqli_query($db, $sqlToken);
         $tieneApp = ($resultToken && mysqli_num_rows($resultToken) > 0) ? 'SI' : 'NO';
         
         $alumnos[] = array(
             "CENTRO" => $fila['centro'],
             "MATRICULA" => $fila['id_alu_ram'],
             "F. INGRESO" => fechaFormateadaCompacta4($fila['ing_alu']),
             "NOMBRE" => $fila['nom_alu'],
             "COBRADO" => formatearDinero($fila['cobrado_alumno']),
             "ADEUDOS" => formatearDinero($fila['adeudo_alumno']),
             "ESTATUS" => $fila['estatus_general'],
             "MATRICULA_HIDDEN" => $fila['bol_alu'],
             "TELÉFONOS" => $fila['tel_alu'] . ' / ' . $fila['tel2_alu'],
             "GPO" => $fila['nom_gen'] . ' (' . $fila['estado_grupo'] . ') [' . FechaFormateadaCompacta2($fila['ini_gen']) . ' - ' . FechaFormateadaCompacta2($fila['fin_gen']) . ']',
             "NIVEL" => $fila['nivel_academico'],
             "CURP" => $fila['cur_alu'],
             "VAL_CUR_ALU" => $fila['val_cur_alu'],
             "CORREO" => $fila['cor_alu'],
             "CONTRASEÑA" => $fila['pas_alu'],
             "EXPEDIENTE" => $fila['documento_pendiente'],
             "ACT VENCIDAS" => $fila['actividades_vencidas'],
             "ID" => $fila['id_gen1'],
             "CARGA" => $fila['carga_alumno'],
             "CONSULTOR" => $fila['nom_eje'],
             "APP" => $tieneApp,
             "DOMICILIACIÓN" => $fila['domiciliacion'],
             "EST_ALU" => $fila['est_alu'],
             "VAL_ALU_RAM" => $fila['val_alu_ram'],
             "VAL_CUR_ALU" => $fila['val_cur_alu']
         );
    }
     
    echo json_encode($alumnos);
}
?>