<?php  
//ARCHIVO VIA AJAX PARA PAGOS Y DOMICILIACIÓN
//controlador_pago.php
require('../inc/cabeceras.php');

header('Content-Type: application/json');

// Validar que llegue una acción
if(!isset($_POST['accion'])) {
    echo json_encode([
        'success' => false,
        'message' => '❌ No se especificó ninguna acción'
    ]);
    exit;
}

$accion = $_POST['accion'];

// ========================================
// 📊 OBTENER ESTADO DE DOMICILIACIÓN
// ========================================
if($accion == 'obtener_estado_domiciliacion') {
    
    if(!isset($_POST['id_alu_ram'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falta el id_alu_ram'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    // Consultar alu_ram + payment_methods
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

// ========================================
// 📋 OBTENER PAGOS DEL ALUMNO
// ========================================
if($accion == 'obtener_pagos') {
    
    if(!isset($_POST['id_alu_ram'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falta el id_alu_ram'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    $sql = "SELECT 
                id_pag,
                id_alu_ram10,
                tip_pag,
                mon_pag,
                mon_ori_pag,
                est_pag,
                ini_pag,
                fin_pag
            FROM pago 
            WHERE id_alu_ram10 = '$id_alu_ram'
            ORDER BY 
                CASE 
                    WHEN est_pag = 'Pendiente' THEN 1 
                    WHEN est_pag = 'Pagado' THEN 2 
                    ELSE 3 
                END,
                fin_pag ASC";
    
    $resultado = mysqli_query($db, $sql);
    
    if($resultado) {
        $pagos = [];
        
        while($fila = mysqli_fetch_assoc($resultado)) {
            
            // Formatear tipo
            $tipoPago = '';
            switch($fila['tip_pag']) {
                case 'Inscripción': $tipoPago = 'INSCRIPCIÓN'; break;
                case 'Colegiatura': $tipoPago = 'COLEGIATURA'; break;
                case 'Reinscripción': $tipoPago = 'REINSCRIPCIÓN'; break;
                case 'Otros': $tipoPago = 'TRÁMITE'; break;
                default: $tipoPago = strtoupper($fila['tip_pag']);
            }
            
            // Formatear fecha
            $fechaVencimiento = '';
            if (!empty($fila['fin_pag']) && $fila['fin_pag'] != '0000-00-00') {
                $fechaVencimiento = date('d M Y', strtotime($fila['fin_pag']));
                $fechaVencimiento = str_replace(
                    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    $fechaVencimiento
                );
            }
            
            $isPagado = ($fila['est_pag'] == 'Pagado');
            $montoMostrar = $isPagado ? $fila['mon_ori_pag'] : $fila['mon_pag'];
            
            $pagos[] = [
                'id_pag' => $fila['id_pag'],
                'tip_pag' => $fila['tip_pag'],
                'tip_pag_formateado' => $tipoPago,
                'mon_pag' => $fila['mon_pag'],
                'mon_mostrar' => $montoMostrar,
                'mon_formateado' => number_format($montoMostrar, 0, '.', ','),
                'est_pag' => $fila['est_pag'],
                'is_pagado' => $isPagado,
                'fecha' => $fechaVencimiento
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $pagos
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al consultar pagos: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========================================
// 💾 REGISTRAR PAGO ÚNICO
// ========================================
if($accion == 'registrar_pago') {
    
    if(!isset($_POST['id_pag']) || !isset($_POST['payment_intent_id']) || !isset($_POST['monto'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Faltan datos del pago'
        ]);
        exit;
    }
    
    $id_pag = mysqli_real_escape_string($db, $_POST['id_pag']);
    $payment_intent_id = mysqli_real_escape_string($db, $_POST['payment_intent_id']);
    $monto = mysqli_real_escape_string($db, $_POST['monto']);
    $tip_pag = mysqli_real_escape_string($db, $_POST['tip_pag']);
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    // Insertar abono
    $sqlAbono = "INSERT INTO abono_pago 
                 (id_pag8, tip_abo_pag, mon_abo_pag, fec_abo_pag, str_abo_pag) 
                 VALUES 
                 ('$id_pag', 'Tarjeta', '$monto', NOW(), '$payment_intent_id')";
    
    $resultAbono = mysqli_query($db, $sqlAbono);
    
    if($resultAbono) {
        // Actualizar estado del pago
        $sqlUpdatePago = "UPDATE pago 
                          SET est_pag = 'Pagado',
                              mon_ori_pag = mon_pag
                          WHERE id_pag = '$id_pag'";
        
        mysqli_query($db, $sqlUpdatePago);
        
        // 🔥 OBTENER PAGOS ACTUALIZADOS
        $sqlPagos = "SELECT 
                        id_pag,
                        tip_pag,
                        mon_pag,
                        mon_ori_pag,
                        est_pag,
                        fin_pag
                    FROM pago 
                    WHERE id_alu_ram10 = '$id_alu_ram'
                    ORDER BY 
                        CASE 
                            WHEN est_pag = 'Pendiente' THEN 1 
                            WHEN est_pag = 'Pagado' THEN 2 
                            ELSE 3 
                        END,
                        fin_pag ASC";
        
        $resultPagos = mysqli_query($db, $sqlPagos);
        $pagosActualizados = [];
        
        while($fila = mysqli_fetch_assoc($resultPagos)) {
            $tipoPago = '';
            switch($fila['tip_pag']) {
                case 'Inscripción': $tipoPago = 'INSCRIPCIÓN'; break;
                case 'Colegiatura': $tipoPago = 'COLEGIATURA'; break;
                case 'Reinscripción': $tipoPago = 'REINSCRIPCIÓN'; break;
                case 'Otros': $tipoPago = 'TRÁMITE'; break;
                default: $tipoPago = strtoupper($fila['tip_pag']);
            }
            
            $fechaVencimiento = '';
            if (!empty($fila['fin_pag']) && $fila['fin_pag'] != '0000-00-00') {
                $fechaVencimiento = date('d M Y', strtotime($fila['fin_pag']));
                $fechaVencimiento = str_replace(
                    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    $fechaVencimiento
                );
            }
            
            $isPagado = ($fila['est_pag'] == 'Pagado');
            $montoMostrar = $isPagado ? $fila['mon_ori_pag'] : $fila['mon_pag'];
            
            $pagosActualizados[] = [
                'id_pag' => $fila['id_pag'],
                'tip_pag' => $fila['tip_pag'],
                'tip_pag_formateado' => $tipoPago,
                'mon_pag' => $fila['mon_pag'],
                'mon_mostrar' => $montoMostrar,
                'mon_formateado' => number_format($montoMostrar, 0, '.', ','),
                'est_pag' => $fila['est_pag'],
                'is_pagado' => $isPagado,
                'fecha' => $fechaVencimiento
            ];
        }
        
        echo json_encode([
            'success' => true,
            'message' => '✅ Pago registrado correctamente',
            'data' => [
                'id_pag' => $id_pag,
                'payment_intent_id' => $payment_intent_id,
                'pagos' => $pagosActualizados
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al registrar el pago: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========================================
// 🔗 VINCULAR TARJETA (Domiciliación)
// ========================================
if($accion == 'vincular_tarjeta') {
    
    // 🔥 VALIDACIÓN COMPLETA DE DATOS
    if(!isset($_POST['id_alu_ram']) || !isset($_POST['customer_id']) || !isset($_POST['payment_method_id'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Faltan datos de Stripe (id_alu_ram, customer_id o payment_method_id)'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    $customer_id = mysqli_real_escape_string($db, $_POST['customer_id']);
    $payment_method_id = mysqli_real_escape_string($db, $_POST['payment_method_id']);
    
    // 🔥 VALIDAR FORMATO DE IDs de Stripe
    if(empty($customer_id) || empty($payment_method_id)) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Los datos de Stripe están vacíos'
        ]);
        exit;
    }
    
    if(!preg_match('/^cus_/', $customer_id)) {
        echo json_encode([
            'success' => false,
            'message' => '❌ customer_id inválido (debe comenzar con cus_)'
        ]);
        exit;
    }
    
    if(!preg_match('/^pm_/', $payment_method_id)) {
        echo json_encode([
            'success' => false,
            'message' => '❌ payment_method_id inválido (debe comenzar con pm_)'
        ]);
        exit;
    }
    
    // 🔥 OBTENER DATOS DE LA TARJETA DESDE EL FRONTEND
    // En lugar de llamar a Stripe, recibimos los datos del frontend
    
    if(!isset($_POST['last_4']) || !isset($_POST['brand']) || !isset($_POST['exp_month']) || !isset($_POST['exp_year'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Faltan datos de la tarjeta (last_4, brand, exp_month, exp_year)'
        ]);
        exit;
    }
    
    $last_4 = mysqli_real_escape_string($db, $_POST['last_4']);
    $brand = mysqli_real_escape_string($db, $_POST['brand']);
    $exp_month = mysqli_real_escape_string($db, $_POST['exp_month']);
    $exp_year = mysqli_real_escape_string($db, $_POST['exp_year']);
    
    // 🔥 VALIDAR QUE LOS DATOS NO ESTÉN VACÍOS
    if(empty($last_4) || empty($brand) || empty($exp_month) || empty($exp_year)) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Los datos de la tarjeta están incompletos'
        ]);
        exit;
    }
    
    // 🔥 SI LLEGAMOS AQUÍ, TENEMOS TODOS LOS DATOS CORRECTAMENTE
    
    // PASO 1: Actualizar alu_ram
    $sqlAluRam = "UPDATE alu_ram 
                  SET 
                      dom_alu_ram = 'Activo',
                      fec_dom_alu_ram = NOW(),
                      customer_id = '$customer_id'
                  WHERE id_alu_ram = '$id_alu_ram'";
    
    $resultadoAluRam = mysqli_query($db, $sqlAluRam);
    
    if(!$resultadoAluRam) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al actualizar alu_ram: ' . mysqli_error($db)
        ]);
        exit;
    }
    
    // PASO 2: Eliminar tarjeta anterior (si existe)
    $sqlDelete = "DELETE FROM payment_methods WHERE id_alu_ram = '$id_alu_ram'";
    mysqli_query($db, $sqlDelete);
    
    // PASO 3: Insertar nueva tarjeta CON DATOS COMPLETOS
    $sqlInsert = "INSERT INTO payment_methods 
                  (id_alu_ram, payment_method_id, last_4, brand, exp_month, exp_year) 
                  VALUES 
                  ('$id_alu_ram', '$payment_method_id', '$last_4', '$brand', '$exp_month', '$exp_year')";
    
    $resultadoInsert = mysqli_query($db, $sqlInsert);
    
    if($resultadoInsert) {
        
        // Obtener fecha formateada
        $sqlFecha = "SELECT fec_dom_alu_ram FROM alu_ram WHERE id_alu_ram = '$id_alu_ram'";
        $resultFecha = mysqli_query($db, $sqlFecha);
        $filaFecha = mysqli_fetch_assoc($resultFecha);
        $fechaFormateada = date('d/M/y', strtotime($filaFecha['fec_dom_alu_ram']));
        
        echo json_encode([
            'success' => true,
            'message' => '✅ Domiciliación activada correctamente',
            'data' => [
                'id_alu_ram' => $id_alu_ram,
                'customer_id' => $customer_id,
                'payment_method_id' => $payment_method_id,
                'last_4' => $last_4,
                'brand' => $brand,
                'exp_month' => $exp_month,
                'exp_year' => $exp_year,
                'estado' => 'Activo',
                'fecha' => $fechaFormateada
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al insertar tarjeta: ' . mysqli_error($db)
        ]);
    }
    
    exit;
}

// ========================================
// 🗑️ CANCELAR DOMICILIACIÓN
// ========================================
if($accion == 'cancelar_domiciliacion') {
    
    if(!isset($_POST['id_alu_ram'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falta el id_alu_ram'
        ]);
        exit;
    }
    
    $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
    
    // PASO 1: Actualizar alu_ram a Inactivo
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
    
    // PASO 2: Eliminar tarjeta
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

// ========================================
// ❌ ACCIÓN NO RECONOCIDA
// ========================================
echo json_encode([
    'success' => false,
    'message' => '❌ Acción no reconocida: ' . $accion
]);

?>