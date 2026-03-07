<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR SALAS Y MENSAJES ASOCIADAS A SALA
	//mensajes.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_sal = $_POST['id_sal'];


	$sql = "DELETE FROM sala WHERE id_sal = '$id_sal'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		// logServer ( 'Baja', $tipoUsuario, $id, 'Sala', $plantel );
		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>