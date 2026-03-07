<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');
    
    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
        exit;
    }
    
    // Obtener datos enviados por AJAX con los nombres específicos requeridos
    $id_cit = $_POST['id_cit'];
    $modalidad = $_POST['modalidad'];
    
    // CAMPOS ESPECÍFICOS REQUERIDOS:
    $can_col_cit = isset($_POST['can_col_cit']) ? (int)$_POST['can_col_cit'] : 0;     
    $mon_col_cit = isset($_POST['mon_col_cit']) ? (float)$_POST['mon_col_cit'] : 0;   
    $can_ins_cit = isset($_POST['can_ins_cit']) ? (int)$_POST['can_ins_cit'] : 0;     
    $mon_ins_cit = isset($_POST['mon_ins_cit']) ? (float)$_POST['mon_ins_cit'] : 0;   
    $mon_str_cit = isset($_POST['mon_str_cit']) ? (float)$_POST['mon_str_cit'] : 0;   
    $con_str_cit = isset($_POST['con_str_cit']) ? $_POST['con_str_cit'] : '';         
    $id_pla_des = isset($_POST['id_pla_des']) ? (int)$_POST['id_pla_des'] : 0;
    $id_gen_des = isset($_POST['id_gen_des']) ? (int)$_POST['id_gen_des'] : 0;        // ID GENERACION DESTINO
    
    try {
        // *** NUEVA LÓGICA: Si no hay colegiaturas en el paquete, definir monto por modalidad ***
        if ($can_col_cit == 0) {
            switch (strtoupper($modalidad)) {
                case 'DIPLOMADO':
                    $mon_col_cit = 2000;
                    break;
                case 'PREPA-EMPRENDE':
                    $mon_col_cit = 1000;
                    break;
                case 'PREPA-6-MESES':
                    $mon_col_cit = 1500;
                    break;
                default:
                    // Modalidad por defecto si no coincide
                    $mon_col_cit = 1600; // O el valor que consideres estándar
                    break;
            }
        }
        
        // Actualizar tabla cita con los campos específicos (incluyendo id_gen_des)
        $fechaActual = date('Y-m-d H:i:s');
        $sqlActualizarCita = "UPDATE cita SET 
                             can_col_cit = '$can_col_cit',
                             mon_col_cit = '$mon_col_cit',
                             can_ins_cit = '$can_ins_cit',
                             mon_ins_cit = '$mon_ins_cit',
                             mon_str_cit = '$mon_str_cit',
                             con_str_cit = '" . mysqli_real_escape_string($db, $con_str_cit) . "',
                             id_pla_des = '$id_pla_des',
                             id_gen_des = '$id_gen_des'
                             WHERE id_cit = '$id_cit'";
        
        $resultadoActualizarCita = mysqli_query($db, $sqlActualizarCita);
        
        if (!$resultadoActualizarCita) {
            throw new Exception("Error al actualizar cita: " . mysqli_error($db));
        }
        
        // Enviar respuesta de éxito
        echo json_encode([
            'status' => 'success', 
            'message' => 'Liga de pago generada correctamente',
            'datos_guardados' => [
                'can_col_cit' => $can_col_cit,
                'mon_col_cit' => $mon_col_cit, // Ahora incluye el valor ajustado por modalidad
                'can_ins_cit' => $can_ins_cit,
                'mon_ins_cit' => $mon_ins_cit,
                'mon_str_cit' => $mon_str_cit,
                'con_str_cit' => $con_str_cit,
                'id_pla_des' => $id_pla_des,
                'id_gen_des' => $id_gen_des,    // AGREGADO
                'modalidad_aplicada' => $modalidad // Para debug
            ]
        ]);
        
    } catch (Exception $e) {
        // Enviar respuesta de error
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage()
        ]);
    }
?>