<?php 
	//ARCHIVO VIA AJAX PARA VALIDAR DISPONIBILIDAD DEL CORREO TANTO PARA CREACION COMO EDICION
	//usado por forms de usuarios con totalCorreos de la plataforma
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
	
	if ( isset($_POST['correo']) && (!isset($_POST['password'])) ) {
		
		$correo = $_POST['correo'];
		if (!empty($correo)) {

			$sql = "
				SELECT cor_eje 
				FROM ejecutivo 
				WHERE cor_eje = '$correo' AND ran_eje IN ('GC', 'DC')
			";

			$resultado = mysqli_query($db, $sql);

			$totalCorreos = mysqli_num_rows($resultado);

			if ($totalCorreos > 0) {
				echo "True";
			}else{
				echo "False";
			}
		}
	}else if( (isset($_POST['correo'])) && (isset($_POST['password'])) ){
		
		$correo = $_POST['correo'];
		$password = $_POST['password'];
		
		if ( (!empty($correo)) && (!empty($password)) ) {

			$sql = "
				SELECT cor_eje 
				FROM ejecutivo 
				WHERE cor_eje = '$correo' AND pas_eje = '$password' AND ran_eje IN ('GC', 'DC')
			";

			$resultado = mysqli_query($db, $sql);

			$totalCorreos = mysqli_num_rows($resultado);


			if ($totalCorreos > 0) {
				echo "True";

			}else{
				echo "False";
			
			}
		}
	}

?>