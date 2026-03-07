<?php 
	//ARCHIVO VIA AJAX PARA VALIDAR CONTRASEÑA DEL EJECUTIVO SUPERIOR
	//usado por todos los usuarios en todos lados
	require('../inc/cabeceras.php');
	

	if (isset($_POST['password'])) {
		$password = $_POST['password'];

		if (!empty($password)) {
			$sql = "
				SELECT pas_eje FROM ejecutivo WHERE pas_eje = '$password' AND ( ( ran_eje = 'GC' || ran_eje = 'DC' ) || ( tip_eje != 'Ejecutivo' ) ) 
			";

			$resultado = mysqli_query($db, $sql);

			$totalPasswords = mysqli_num_rows($resultado);

			if ($totalPasswords > 0) {
				echo "True";
			}else{
				echo "False";
			}
		}
	}

?>