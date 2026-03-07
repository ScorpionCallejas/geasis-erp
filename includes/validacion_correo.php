<?php 
	//ARCHIVO VIA AJAX PARA VALIDAR DISPONIBILIDAD DEL CORREO SOLO CREACION
	//usado por forms de usuarios con correos de la plataforma
	require('conexion.php');

	if (isset($_POST['correo_final'])) {
		$correo = $_POST['correo_final'];
		//echo $correo;
		if (!empty($correo)) {
			$sql = "
				SELECT cor_adm FROM admin WHERE cor_adm = '$correo'

				UNION

				SELECT cor_adg FROM adminge WHERE cor_adg = '$correo'

				UNION

				SELECT cor_adc FROM adminco WHERE cor_adc = '$correo'

				UNION

				SELECT cor_eje FROM ejecutivo WHERE cor_eje = '$correo'

				UNION

				SELECT cor_alu FROM alumno WHERE cor_alu = '$correo'

				UNION

				SELECT cor_pro FROM profesor WHERE cor_pro = '$correo'

			";


			$resultado = mysqli_query($db, $sql);

			$disponibilidad = mysqli_num_rows($resultado);



			if ($disponibilidad == 0) {
				echo "disponible";
			}else{
				echo $disponibilidad+1;
			}
		}else{
			echo 'vacio';
		}
	}
	
	
?>