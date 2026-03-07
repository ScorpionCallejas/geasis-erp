<?php
	require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	$palabra = $_POST['palabra'];

	$sql = "
		SELECT * 
		FROM vista_alumnos
		WHERE ( id_pla8 = '$plantel' ) AND ( ( id_alu_ram = '$palabra' ) OR ( tel_alu = '$palabra' ) OR ( UPPER( cor_alu ) = UPPER('$palabra') ) )
	";

	$total = obtener_datos_consulta( $db, $sql )['total'];

	//echo $sql;
	if( $total == 0 ){
		echo 'Sin datos';
	} else {
		$resultado = mysqli_query( $db, $sql );
		$datos = mysqli_fetch_assoc( $resultado );
		echo $datos['nom_alu'];
	}
	
?>