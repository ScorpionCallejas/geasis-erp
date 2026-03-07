<?php
session_start();

// Al recibir el "ping", renovar la sesión
session_regenerate_id(false);
$_SESSION['ultima_actividad'] = time();

echo json_encode(['success' => true]);
?>