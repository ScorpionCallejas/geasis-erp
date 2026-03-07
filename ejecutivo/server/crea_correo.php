<?php 
require('../inc/cabeceras.php');
require('../inc/funciones.php');

	$name = $_POST['nombre'];

	// Cadena de entrada: Nombre + Apellido Paterno + Apellido Materno
	$cadenaCompleta = $name;

	// Dividir la cadena en partes (nombre, apellido paterno, apellido materno)
	$partes = explode(" ", $cadenaCompleta);
	if (count($partes) >= 2) {
			// Obtener la primera letra del nombre
			$primeraLetraNombre = substr($partes[0], 0, 1);

			// Obtener el apellido paterno
			$apellidoPaterno = $partes[1];

			// Crear la nueva cadena deseada
			$nuevaCadena = $primeraLetraNombre .'.'. $apellidoPaterno;

			// Mostrar el resultado
			$nucleo_correo = strtolower($nuevaCadena);
			
			//echo $nuevaCadena;
			generarCorreoRecursivo($nucleo_correo, 0);
			
	}
	
function generarCorreoRecursivo($nucleo, $intentos) {
	require(  __DIR__."/../../includes/conexion.php");
			$try = $intentos;
			//echo $try.'<br>'; 
			$inicio = $nucleo;
			if ($try <= 0) {
				$nuevaCadena = strtolower($inicio).'@ende.com';
			}
			else{
				$nuevaCadena = $inicio.$try.'@ende.com';
			}
			
			$sql = "SELECT validar_correo('$nuevaCadena') AS validar_correo";
			//echo $sql;
			$request = mysqli_query($db, $sql);
			$set = mysqli_fetch_assoc($request);
			if ($set['validar_correo']>0) {
				$try++;
				generarCorreoRecursivo($inicio, $try);
			}
			else{
				echo $nuevaCadena;	
			}
			


}




 ?>