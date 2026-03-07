<?php
//ARCHIVO VIA AJAX PARA EDITAR EJECUTIVO
require('../inc/cabeceras.php');

// Función para sanitizar inputs
function sanitize_input($data) {
    global $db;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($db, $data);
}

if (!isset($_FILES['fot_eje']['name'])) {
    // ========== EDICIÓN DE DATOS ==========
    
    $nom_eje = isset($_POST['nom_eje']) ? sanitize_input($_POST['nom_eje']) : '';
    $tel_eje = isset($_POST['tel_eje']) ? sanitize_input($_POST['tel_eje']) : '';
    $pas_eje = isset($_POST['pas_eje']) ? sanitize_input($_POST['pas_eje']) : '';
    $des_eje = isset($_POST['des_eje']) ? sanitize_input($_POST['des_eje']) : '';
    $cue_eje = isset($_POST['cue_eje']) ? sanitize_input($_POST['cue_eje']) : '';
    $ban_eje = isset($_POST['ban_eje']) ? sanitize_input($_POST['ban_eje']) : '';
    
    // Validar cuenta (solo números)
    if (!empty($cue_eje) && !preg_match('/^\d+$/', $cue_eje)) {
        echo json_encode(array(
            'success' => false,
            'message' => 'La cuenta debe contener solo números'
        ));
        exit;
    }
    
    // Validar banco
    $bancosValidos = array('BBVA', 'Banamex', 'Santander', 'HSBC', 'Scotiabank', 'Bancoppel', 'Azteca', 'Otro', '');
    if (!in_array($ban_eje, $bancosValidos)) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Banco no válido'
        ));
        exit;
    }

    $sqlEdicionEjecutivo = "
        UPDATE ejecutivo 
        SET 
        nom_eje = '$nom_eje',
        tel_eje = '$tel_eje',
        pas_eje = '$pas_eje',
        des_eje = '$des_eje',
        cue_eje = '$cue_eje',
        ban_eje = '$ban_eje'
        WHERE id_eje = '$id'
    ";

    $resultadoEdicionEjecutivo = mysqli_query($db, $sqlEdicionEjecutivo);
    
    if ($resultadoEdicionEjecutivo) {
        echo json_encode(array(
            'success' => true, 
            'message' => 'Datos actualizados correctamente'
        ));
    } else {
        echo json_encode(array(
            'success' => false, 
            'message' => 'Error al actualizar los datos',
            'mysql_error' => mysqli_error($db)
        ));
    }
    
} else {
    // ========== EDICIÓN DE FOTO ==========
    
    // ✅ VALIDAR EXTENSIÓN PRIMERO
    $fot_eje = $_FILES['fot_eje']['name'];
    $extension = strtolower(pathinfo($fot_eje, PATHINFO_EXTENSION));
    
    $extensionesPermitidas = array('jpg', 'jpeg', 'png');
    
    if (!in_array($extension, $extensionesPermitidas)) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Formato no permitido. Solo JPG, JPEG o PNG'
        ));
        exit;
    }
    
    // ✅ VALIDAR TAMAÑO
    if ($_FILES['fot_eje']['size'] > 30000000) {
        echo json_encode(array(
            'success' => false,
            'message' => 'La imagen no debe exceder 30MB'
        ));
        exit;
    }
    
    // ✅ USAR SIEMPRE $id (NO $id_eje)
    $sqlConsulta = "SELECT fot_eje FROM ejecutivo WHERE id_eje = '$id'";
    $resultadoConsulta = mysqli_query($db, $sqlConsulta);
    $filaConsulta = mysqli_fetch_assoc($resultadoConsulta);
    $fotoActual = $filaConsulta['fot_eje'];
    
    // ✅ Borrar foto anterior si existe
    if ($fotoActual != NULL && file_exists("../../uploads/$fotoActual")) {
        unlink("../../uploads/$fotoActual");
    }
    
    // ✅ Crear nombre único y guardar
    $foto = "foto-ejecutivo000".$id.".".$extension;
    $carpeta_destino = '../../uploads/';
    $guardado = move_uploaded_file($_FILES['fot_eje']['tmp_name'], $carpeta_destino.$foto);
    
    if ($guardado) {
        $sqlUpdate = "UPDATE ejecutivo SET fot_eje = '$foto' WHERE id_eje = '$id'";
        $resultadoUpdate = mysqli_query($db, $sqlUpdate);
        
        if ($resultadoUpdate) {
            echo "Exito";
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error al actualizar en BD: ' . mysqli_error($db)
            ));
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Error al mover el archivo. Verifica permisos del directorio uploads/'
        ));
    }
}
?>