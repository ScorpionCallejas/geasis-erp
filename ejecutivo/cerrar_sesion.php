<?php

	require('inc/cabeceras.php');


	// require_once(  __DIR__."/../includes/funciones.php");

 //    logSesiones ( 'Fin', $tipoUsuario, $id, 'Sesión', $plantel );
    
    session_destroy();
	// session_unset()
    // $_SESSION = array();

    header('Location: ../');

	
?>