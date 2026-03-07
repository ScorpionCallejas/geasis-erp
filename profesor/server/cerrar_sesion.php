<?php

	// require('inc/cabeceras.php');


	// require_once(  __DIR__."/../includes/funciones.php");

    // logSesiones ( 'Fin', $tipoUsuario, $id, 'Sesión', $plantel );
    
    session_destroy();
    // $_SESSION = array();

    header('Location: ../');

	
?>