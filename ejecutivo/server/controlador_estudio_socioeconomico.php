<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id_cit = $_POST['id_cit'];
    $q1 = $_POST['q1'];
    // ... (resto de las variables)

    // Verificar si ya existe un registro para este id_cit
    $sqlCheck = "SELECT * FROM estudio_socioeconomico WHERE id_cit = ?";
    $stmtCheck = $db->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id_cit);
    $stmtCheck->execute();
    $resultadoCheck = $stmtCheck->get_result();

    if ($resultadoCheck->num_rows > 0) {
        // Actualizar registro existente
        $sqlUpdate = "UPDATE estudio_socioeconomico SET
            q1 = ?, q2 = ?, q3 = ?, q4_tiene_servicio = ?, q4_gasto = ?, q5 = ?, q6 = ?, q7 = ?, q8 = ?, q9 = ?, q10 = ?, q11 = ?, q12 = ?, q13 = ?
            WHERE id_cit = ?";
        $stmt = $db->prepare($sqlUpdate);
        $stmt->bind_param(
            "siisdsdssdssiii",
            $q1,
            $q2,
            $q3,
            $q4_tiene_servicio,
            $q4_gasto,
            $q5,
            $q6,
            $q7,
            $q8,
            $q9,
            $q10,
            $q11,
            $q12,
            $q13,
            $id_cit
        );
    } else {
        // Insertar nuevo registro
        $stmt = $db->prepare("INSERT INTO estudio_socioeconomico (
            id_cit, q1, q2, q3, q4_tiene_servicio, q4_gasto, q5, q6, q7, q8, q9, q10, q11, q12, q13
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isiiisdssdssiii",
            $id_cit,
            $q1,
            $q2,
            $q3,
            $q4_tiene_servicio,
            $q4_gasto,
            $q5,
            $q6,
            $q7,
            $q8,
            $q9,
            $q10,
            $q11,
            $q12,
            $q13
        );
    }

    if ($stmt->execute()) {
        // Éxito
        echo json_encode(['status' => 'success', 'message' => 'Estudio socio-económico guardado correctamente.']);
    } else {
        // Error
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmtCheck->close();
    $stmt->close();
    $db->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
