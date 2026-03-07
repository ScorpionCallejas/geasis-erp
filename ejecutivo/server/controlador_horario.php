<?php
/**
 * CONTROLADOR DE HORARIOS
 * server/controlador_horario.php
 * 
 * Acciones: obtener
 * VERSIÓN 1.0 MODULAR
 */

header('Content-Type: application/json; charset=utf-8');

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Respuesta por defecto
$response = ['success' => false, 'mensaje' => 'Acción no especificada'];

// Obtener acción
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
$id_alu_ram = isset($_POST['id_alu_ram']) ? mysqli_real_escape_string($db, $_POST['id_alu_ram']) : '';

// Validar id_alu_ram
if (empty($id_alu_ram)) {
    echo json_encode(['success' => false, 'mensaje' => 'ID de alumno no proporcionado']);
    exit;
}

switch ($accion) {
    
    // ==================== OBTENER HORARIOS ====================
    case 'obtener':
        
        // ========== CONSULTA PRINCIPAL ==========
        $sqlHorario = "
            SELECT 
                sub_hor.id_sub_hor,
                sub_hor.nom_sub_hor,
                sub_hor.id_sal1,
                CONCAT(profesor.nom_pro, ' ', profesor.app_pro) AS profesor,
                materia.nom_mat AS materia,
                grupo.nom_gru,
                ciclo.nom_cic,
                ciclo.ins_cic,
                ciclo.ini_cic,
                ciclo.cor_cic,
                ciclo.fin_cic,
                rama.nom_ram,
                rama.mod_ram,
                rama.gra_ram,
                rama.per_ram,
                rama.cic_ram
            FROM sub_hor
            INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
            INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN rama ON rama.id_ram = materia.id_ram2
            WHERE alu_hor.id_alu_ram1 = '$id_alu_ram' 
            AND alu_hor.est_alu_hor = 'Activo'
        ";
        
        $resultadoHorario = mysqli_query($db, $sqlHorario);
        
        if (!$resultadoHorario) {
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error en consulta: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        $totalMaterias = mysqli_num_rows($resultadoHorario);
        
        if ($totalMaterias === 0) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'El alumno no tiene horarios activos',
                'materias' => []
            ]);
            exit;
        }
        
        // ========== OBTENER INFO GENERAL (primera fila) ==========
        $resultadoInfo = mysqli_query($db, $sqlHorario);
        $filaInfo = mysqli_fetch_assoc($resultadoInfo);
        
        $info = [
            'nom_ram' => $filaInfo['nom_ram'],
            'mod_ram' => $filaInfo['mod_ram'],
            'gra_ram' => $filaInfo['gra_ram'],
            'per_ram' => $filaInfo['per_ram'],
            'cic_ram' => $filaInfo['cic_ram'],
            'nom_cic' => $filaInfo['nom_cic'],
            'nom_gru' => $filaInfo['nom_gru'],
            'ins_cic' => fechaFormateadaCompacta2($filaInfo['ins_cic']),
            'ini_cic' => fechaFormateadaCompacta2($filaInfo['ini_cic']),
            'cor_cic' => fechaFormateadaCompacta2($filaInfo['cor_cic']),
            'fin_cic' => fechaFormateadaCompacta2($filaInfo['fin_cic'])
        ];
        
        // ========== PROCESAR MATERIAS Y HORARIOS ==========
        $materias = [];
        $resultadoMaterias = mysqli_query($db, $sqlHorario);
        
        while ($fila = mysqli_fetch_assoc($resultadoMaterias)) {
            $id_sub_hor = $fila['id_sub_hor'];
            
            // --- OBTENER SALÓN ---
            $salon = 'N/A';
            if (!empty($fila['id_sal1'])) {
                $sqlSalon = "
                    SELECT nom_sal 
                    FROM salon 
                    WHERE id_sal = '{$fila['id_sal1']}'
                ";
                $resultadoSalon = mysqli_query($db, $sqlSalon);
                if ($resultadoSalon && mysqli_num_rows($resultadoSalon) > 0) {
                    $filaSalon = mysqli_fetch_assoc($resultadoSalon);
                    $salon = $filaSalon['nom_sal'];
                }
            }
            
            // --- OBTENER HORARIOS POR DÍA ---
            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $horarios = [];
            
            foreach ($dias as $dia) {
                $sqlDia = "
                    SELECT ini_hor, fin_hor
                    FROM horario
                    WHERE id_sub_hor1 = '$id_sub_hor' 
                    AND dia_hor = '$dia'
                ";
                
                $resultadoDia = mysqli_query($db, $sqlDia);
                
                if ($resultadoDia && mysqli_num_rows($resultadoDia) > 0) {
                    $filaDia = mysqli_fetch_assoc($resultadoDia);
                    $horarios[strtolower($dia)] = $filaDia['ini_hor'] . '-' . $filaDia['fin_hor'];
                } else {
                    $horarios[strtolower($dia)] = '--';
                }
            }
            
            // --- AGREGAR MATERIA ---
            $materias[] = [
                'id_sub_hor' => $id_sub_hor,
                'nom_sub_hor' => $fila['nom_sub_hor'],
                'profesor' => $fila['profesor'],
                'materia' => $fila['materia'],
                'salon' => $salon,
                'lunes' => $horarios['lunes'],
                'martes' => $horarios['martes'],
                'miercoles' => $horarios['miércoles'],
                'jueves' => $horarios['jueves'],
                'viernes' => $horarios['viernes'],
                'sabado' => $horarios['sábado'],
                'domingo' => $horarios['domingo']
            ];
        }
        
        // ========== RESPUESTA EXITOSA ==========
        $response = [
            'success' => true,
            'mensaje' => 'Horarios obtenidos correctamente',
            'info' => $info,
            'materias' => $materias,
            'total' => count($materias)
        ];
        
        break;
    
    // ==================== ACCIÓN NO RECONOCIDA ====================
    default:
        $response = ['success' => false, 'mensaje' => 'Acción no reconocida: ' . $accion];
        break;
}

echo json_encode($response);

?>