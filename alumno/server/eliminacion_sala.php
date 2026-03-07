<?php 
	//ARCHIVO VIA AJAX PARA ELIMINAR SALAS Y MENSAJES ASOCIADAS A SALA
	//mensajes.php
	require('../inc/cabeceras.php');


	$id_sal = $_POST['sala'];


	$sql = "DELETE FROM sala WHERE id_sal = '$id_sal'";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {
		echo "true";
	}else{
		echo "error, verificar consulta";
		//echo $sql;
	}


?>