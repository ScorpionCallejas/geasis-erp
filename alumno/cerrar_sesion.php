<?php

    require('inc/cabeceras.php');

    
    session_destroy();
    if ( $plantel != 4 ) {
		
		header('location: https://ahjende.com');

	}
    // $_SESSION = array();

    header('Location: ../');

    
?>