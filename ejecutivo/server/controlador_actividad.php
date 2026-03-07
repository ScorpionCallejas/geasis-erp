<?php
/**
 * CONTROLADOR DE ACTIVIDADES
 * server/controlador_actividad.php
 * 
 * Acciones: obtener, guardar, reiniciar_examen
 * VERSIÓN 1.0 HÍBRIDA
 */

header('Content-Type: application/json; charset=utf-8');

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Respuesta por defecto
$response = ['success' => false, 'mensaje' => 'Acción no especificada'];

// Obtener acción
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
$id_alu_ram = isset($_POST['id_alu_ram']) ? mysqli_real_escape_string($db, $_POST['id_alu_ram']) : '';

// Validar id_alu_ram para acciones que lo requieran
if (in_array($accion, ['obtener', 'reiniciar_examen']) && empty($id_alu_ram)) {
    echo json_encode(['success' => false, 'mensaje' => 'ID de alumno no proporcionado']);
    exit;
}

switch ($accion) {
    
    // ==================== OBTENER ACTIVIDADES ====================
    case 'obtener':
        
        $sql = "
            SELECT 
                id_for_cop AS id_copia,
                'Foro' AS tipo_copia,
                nom_for AS actividad,
                pun_for AS puntaje,
                ini_cal_act AS ini_cal_act,
                fin_cal_act AS fin_cal_act,
                tip_for AS tipo_actividad,
                id_alu_ram AS id_alu_ram,
                fec_cal_act AS fec_cal_act,
                nom_mat AS nom_mat,
                id_blo AS id_blo,
                id_sub_hor AS id_sub_hor,
                nom_gru AS nom_gru,
                ret_cal_act AS ret_cal_act,
                pun_cal_act AS pun_cal_act,
                nom_alu AS nom_alu,
                nom_blo AS nom_blo,
                CONCAT(nom_pro, ' ', app_pro) AS nom_pro,
                id_cal_act AS id_cal_act
            FROM cal_act
            INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
            INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
            WHERE id_alu_ram = '$id_alu_ram'
            
            UNION
            
            SELECT 
                id_ent_cop AS id_copia,
                'Entregable' AS tipo_copia,
                nom_ent AS actividad,
                pun_ent AS puntaje,
                ini_cal_act AS ini_cal_act,
                fin_cal_act AS fin_cal_act,
                tip_ent AS tipo_actividad,
                id_alu_ram AS id_alu_ram,
                fec_cal_act AS fec_cal_act,
                nom_mat AS nom_mat,
                id_blo AS id_blo,
                id_sub_hor AS id_sub_hor,
                nom_gru AS nom_gru,
                ret_cal_act AS ret_cal_act,
                pun_cal_act AS pun_cal_act,
                nom_alu AS nom_alu,
                nom_blo AS nom_blo,
                CONCAT(nom_pro, ' ', app_pro) AS nom_pro,
                id_cal_act AS id_cal_act
            FROM cal_act
            INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
            INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
            WHERE id_alu_ram = '$id_alu_ram'
            
            UNION
            
            SELECT 
                id_exa_cop AS id_copia,
                'Examen' AS tipo_copia,
                nom_exa AS actividad,
                pun_exa AS puntaje,
                ini_cal_act AS ini_cal_act,
                fin_cal_act AS fin_cal_act,
                tip_exa AS tipo_actividad,
                id_alu_ram AS id_alu_ram,
                fec_cal_act AS fec_cal_act,
                nom_mat AS nom_mat,
                id_blo AS id_blo,
                id_sub_hor AS id_sub_hor,
                nom_gru AS nom_gru,
                ret_cal_act AS ret_cal_act,
                pun_cal_act AS pun_cal_act,
                nom_alu AS nom_alu,
                nom_blo AS nom_blo,
                CONCAT(nom_pro, ' ', app_pro) AS nom_pro,
                id_cal_act AS id_cal_act
            FROM cal_act
            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
            INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
            WHERE id_alu_ram = '$id_alu_ram'
            
            ORDER BY ini_cal_act DESC
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if (!$resultado) {
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error en consulta: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        $actividades = [];
        
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $actividades[] = [
                'id_cal_act' => $fila['id_cal_act'],
                'id_copia' => $fila['id_copia'],
                'tipo_copia' => $fila['tipo_copia'],
                'actividad' => $fila['actividad'],
                'puntaje' => $fila['puntaje'],
                'ini_cal_act' => $fila['ini_cal_act'],
                'fin_cal_act' => $fila['fin_cal_act'],
                'tipo_actividad' => $fila['tipo_actividad'],
                'fec_cal_act' => $fila['fec_cal_act'],
                'nom_mat' => $fila['nom_mat'],
                'nom_blo' => $fila['nom_blo'],
                'nom_gru' => $fila['nom_gru'],
                'nom_pro' => $fila['nom_pro'],
                'ret_cal_act' => $fila['ret_cal_act'],
                'pun_cal_act' => $fila['pun_cal_act']
            ];
        }
        
        $response = [
            'success' => true,
            'actividades' => $actividades,
            'total' => count($actividades)
        ];
        
        break;
    
    // ==================== GUARDAR CAMPO ====================
    case 'guardar':
        
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
        $valor = isset($_POST['valor']) ? $_POST['valor'] : '';
        $id_cal_act = isset($_POST['id_cal_act']) ? mysqli_real_escape_string($db, $_POST['id_cal_act']) : '';
        
        if (empty($tipo) || empty($id_cal_act)) {
            echo json_encode(['success' => false, 'mensaje' => 'Parámetros incompletos']);
            exit;
        }
        
        // Construir SQL según tipo
        $sql = '';
        
        switch ($tipo) {
            case 'Inicio':
                $valor_escaped = mysqli_real_escape_string($db, $valor);
                $sql = "UPDATE cal_act SET ini_cal_act = '$valor_escaped' WHERE id_cal_act = '$id_cal_act'";
                break;
            
            case 'Fin':
                $valor_escaped = mysqli_real_escape_string($db, $valor);
                $sql = "UPDATE cal_act SET fin_cal_act = '$valor_escaped' WHERE id_cal_act = '$id_cal_act'";
                break;
            
            case 'Retroalimentacion':
                $valor_escaped = mysqli_real_escape_string($db, $valor);
                $sql = "UPDATE cal_act SET ret_cal_act = '$valor_escaped' WHERE id_cal_act = '$id_cal_act'";
                break;
            
            case 'Puntos':
                // Validar que sea numérico o vacío
                if ($valor !== '' && !is_numeric($valor)) {
                    echo json_encode(['success' => false, 'mensaje' => 'Puntos debe ser un número válido']);
                    exit;
                }
                
                if ($valor === '') {
                    $sql = "UPDATE cal_act SET pun_cal_act = NULL WHERE id_cal_act = '$id_cal_act'";
                } else {
                    $valor_escaped = mysqli_real_escape_string($db, $valor);
                    $sql = "UPDATE cal_act SET pun_cal_act = '$valor_escaped' WHERE id_cal_act = '$id_cal_act'";
                }
                break;
            
            default:
                echo json_encode(['success' => false, 'mensaje' => 'Tipo de campo no reconocido']);
                exit;
        }
        
        // Ejecutar consulta
        $resultado = mysqli_query($db, $sql);
        
        if ($resultado) {
            $response = [
                'success' => true,
                'mensaje' => 'Campo actualizado correctamente',
                'tipo' => $tipo
            ];
        } else {
            $response = [
                'success' => false,
                'mensaje' => 'Error al actualizar: ' . mysqli_error($db)
            ];
        }
        
        break;
    
    // ==================== REINICIAR EXAMEN ====================
    case 'reiniciar_examen':
        
        $id_cal_act = isset($_POST['id_cal_act']) ? mysqli_real_escape_string($db, $_POST['id_cal_act']) : '';
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
        $id_copia = isset($_POST['id_copia']) ? mysqli_real_escape_string($db, $_POST['id_copia']) : '';
        
        if (empty($id_cal_act) || empty($tipo) || empty($id_copia)) {
            echo json_encode(['success' => false, 'mensaje' => 'Parámetros incompletos']);
            exit;
        }
        
        // Validar que sea un examen
        if ($tipo !== 'Examen') {
            echo json_encode(['success' => false, 'mensaje' => 'Solo se pueden reiniciar exámenes']);
            exit;
        }
        
        // 1. Actualizar cal_act (incrementar intentos, limpiar fecha y puntos)
        $sqlUpdate = "
            UPDATE cal_act 
            SET 
                int_cal_act = int_cal_act + 1,
                fec_cal_act = NULL, 
                pun_cal_act = NULL 
            WHERE 
                id_cal_act = '$id_cal_act' 
                AND id_alu_ram4 = '$id_alu_ram'
        ";
        
        $resultadoUpdate = mysqli_query($db, $sqlUpdate);
        
        if (!$resultadoUpdate) {
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error al actualizar cal_act: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        // 2. Eliminar respuestas del alumno
        $sqlDelete = "
            DELETE FROM respuesta_alumno
            WHERE id_exa_cop1 = '$id_copia' AND id_alu_ram8 = '$id_alu_ram'
        ";
        
        $resultadoDelete = mysqli_query($db, $sqlDelete);
        
        if (!$resultadoDelete) {
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error al eliminar respuestas: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        $response = [
            'success' => true,
            'mensaje' => 'Examen reiniciado correctamente',
            'intentos_eliminados' => mysqli_affected_rows($db)
        ];
        
        break;
    
    default:
        $response = ['success' => false, 'mensaje' => 'Acción no reconocida: ' . $accion];
        break;
}

echo json_encode($response);

?>