<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO BLOQUE
	//bloques.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$nom_blo = $_POST['nom_blo'];
	$des_blo = $_POST['des_blo'];
	$id_mat6 = $_POST['id_mat'];


	$sql = "INSERT INTO bloque (nom_blo, des_blo, img_blo, id_mat6) VALUES ('$nom_blo', '$des_blo', 'img_backtoschool.jpg', '$id_mat6')";

	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		$sqlMaximo = "
			SELECT MAX( id_blo ) AS maximo
			FROM bloque
			WHERE id_mat6 = '$id_mat6'
		";

		$resultadoMaximo = mysqli_query( $db, $sqlMaximo );

		$filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

		$id_blo = $filaMaximo['maximo'];
		
		// LOG
		$filaDatos = obtenerDatosBloqueServer( $id_blo );
        $nombreRama = $filaDatos['nom_ram'];

        $des_log =  obtenerDescripcionNodoProgramaLogServer( $tipoUsuario, $nomResponsable, 'registró', 'bloque', $nom_blo, $nombreRama );
       
        logServer( 'Alta', $tipoUsuario, $id, 'Bloque', $des_log, $plantel );
        // FIN LOG

		// echo $id_blo;

		// CIFRADO
		
		echo $id_blo;

		// FIN CIFRADO


	}else{
		echo "false";
		//echo $sql;
	}
		
	
?>