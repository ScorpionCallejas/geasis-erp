<?php 
	//ARCHIVO VIA AJAX PARA VALIDAR DISPONIBILIDAD DEL CORREO TANTO PARA CREACION COMO EDICION
	//usado por forms de usuarios con correos de la plataforma
	require('../inc/cabeceras.php');
	

	if (isset($_POST['correo'])) {
		$correo = $_POST['correo'];

		if (!empty($correo)) {
			$sql = "

				SELECT cor_usu FROM usuario WHERE cor_usu = '$correo'

				UNION

				SELECT cor_eje FROM ejecutivo WHERE cor_eje = '$correo'

				UNION

				SELECT cor_alu FROM alumno WHERE cor_alu = '$correo'

				UNION

				SELECT cor_pro FROM profesor WHERE cor_pro = '$correo'

			";
			// echo $sql;

			$resultado = mysqli_query($db, $sql);

			$disponibilidad = mysqli_num_rows($resultado);



			if ($disponibilidad == 0) {
				echo "disponible";
			}else{
				echo "ocupado";
			}
		}else{
			echo 'vacio';
		}
	}else if(isset($_POST['correoEdicion'])){
		$identificador = $_POST['identificador'];
		$correo = $_POST['correoEdicion'];
		$tipo = $_POST['tipo'];


		if (!empty($correo)) {
			$sql = "
				SELECT cor_adm as correo, id_adm AS id, tip_adm AS tipo FROM admin WHERE cor_adm = '$correo'

				UNION

				SELECT cor_adg as correo, id_adg AS id, tip_adg AS tipo  FROM adminge WHERE cor_adg = '$correo'

				UNION

				SELECT cor_adc as correo, id_adc AS id, tip_adc AS tipo  FROM adminco WHERE cor_adc = '$correo'

				UNION

				SELECT cor_eje as correo, id_eje AS id, tip_eje AS tipo  FROM ejecutivo WHERE cor_eje = '$correo'

				UNION

				SELECT cor_alu as correo, id_alu AS id, tip_alu AS tipo  FROM alumno WHERE cor_alu = '$correo'

				UNION

				SELECT cor_pro as correo, id_pro AS id, tip_pro AS tipo  FROM profesor WHERE cor_pro = '$correo'

			";


			$resultado = mysqli_query($db, $sql);
			$fila = mysqli_fetch_assoc($resultado);

			$correoSospechoso = $fila['correo'];
			$idSospechoso = $fila['id'];
			$tipoSospechoso = $fila['tipo'];

			$disponibilidad = mysqli_num_rows($resultado);




			if ($disponibilidad == 0) {
				echo 'disponible';
			}else {
				if (($correoSospechoso == $correo) && ($idSospechoso == $identificador) && ($tipoSospechoso == $tipo)){
					echo "mio";
					// echo $correoSospechoso.$correo."<br>";
					// echo $idSospechoso.$identificador;


				}else{
					echo 'ocupado';
				}
			}
		}else{
			echo 'vacio';
		}

	}

?>