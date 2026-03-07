<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR NOTIFICACIONES DE MENSAJES	
	//mensajes.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_sal = $_POST['id_sal'];

	$sql = "
		DELETE FROM notificacion_mensaje 
		WHERE id_sal5 = '$id_sal' AND use_not_men = '$id' AND tip_not_men = '$tipoUsuario'
	";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		echo "true";

	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>