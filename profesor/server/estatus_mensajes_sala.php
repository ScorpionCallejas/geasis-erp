<?php  
	//estatus_mensajes_sala.php

	require('../inc/cabeceras.php');
 	require('../inc/funciones.php');

 	$id_sal = $_POST['id_sal'];

 	$sql = "
 		UPDATE estatus_mensaje
		INNER JOIN mensaje ON mensaje.id_men = estatus_mensaje.id_men2
		SET est_est_men = 'Visto'
		WHERE id_sal4 = '$id_sal' AND use_est_men = '$id' AND tip_est_men = '$tipo'
 	";

 	$resultado = mysqli_query( $db, $sql );

 	if ( $resultado ) {

 		echo "Exito";

 	} else {

 		echo $sql;
 	
 	}

?>