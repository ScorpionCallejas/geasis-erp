<?php  
    // CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_cit = $_POST['id_cit'];
	$bec_cit = $_POST['beca'];

	$sql = "
		UPDATE cita 
		SET 
		bec_cit = '$bec_cit'
		WHERE id_cit = $id_cit
	";
	$resultado = mysqli_query( $db, $sql );

	if(!$resultado){
		echo $sql;
	}else{
		echo "Exito";
	}
?>