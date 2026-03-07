<?php 
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    // Recibir datos del formulario
    $nom_con = $_POST['nom_con'];
    $tel_con = $_POST['tel_con'];
    $pro_con = $_POST['pro_con'];
    $can_con = $_POST['can_con']; // Ahora viene del formulario
    $obs_con = $_POST['obs_con'];

    // Escapar datos para prevenir SQL injection
    $nom_con = mysqli_real_escape_string($db, $nom_con);
    $tel_con = mysqli_real_escape_string($db, $tel_con);
    $pro_con = mysqli_real_escape_string($db, $pro_con);
    $can_con = mysqli_real_escape_string($db, $can_con);
    $obs_con = mysqli_real_escape_string($db, $obs_con);

    $sql = "
        INSERT INTO contacto ( nom_con, tel_con, pro_con, obs_con, id_eje10, can_con ) 
        VALUES ( '$nom_con', '$tel_con', '$pro_con', '$obs_con', $id, '$can_con' )
    ";
    
	$resultado = mysqli_query( $db, $sql );

	if( !$resultado ){
		echo "Error en la consulta: " . $sql . "\n";
		echo "Error MySQL: " . mysqli_error($db);
	} else {
		echo "Contacto agregado exitosamente";
	}
?>