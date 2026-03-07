<?php
// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE SUPERUSUARIO
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Añade el header para JSON
header('Content-Type: application/json');

$response = array(); // Crear un arreglo para la respuesta

if (isset($_POST['estatus'])) {
	// PERMISOS DE SCOPE
    $estatus = $_POST['estatus'];
    $id_pla = $_POST['id_pla'];
    $id_eje = $id;

    if ($estatus == 1) {
        $sql = "INSERT INTO planteles_ejecutivo( id_pla, id_eje ) VALUES( '$id_pla', '$id_eje' )";
    } else if ($estatus == 0) {
        $sql = "DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje' AND id_pla = '$id_pla' ";
    }

    $resultado = mysqli_query($db, $sql);

    if ($resultado) {
        $response['status'] = 200;
        $response['message'] = 'Operación exitosa';
    } else {
        $response['status'] = 500; // Cambia el código de estado en caso de error
        $response['message'] = 'Error en la operación';
    }
} else {
    $response['status'] = 400; // Código de estado para solicitud incorrecta
    $response['message'] = 'Solicitud incorrecta';
}

echo json_encode($response);
?>
