<?php 
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $esquema = $_POST['esquema'];
    $identificador = $_POST['identificador'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    if( $esquema == 'esquema_rvt' ) {
        $campo_identificador = 'id_esq_rvt';
    } else if( $esquema == 'esquema_consultor' ) {
        $campo_identificador = 'id_esq_con';
    } else if( $esquema == 'esquema_sales' ) {
        $campo_identificador = 'id_esq_sal';
    }
    
    $sql = "
        UPDATE $esquema
        SET
        $campo = $valor
        WHERE $campo_identificador = '$identificador'
    ";

    $resultado = mysqli_query( $db, $sql );

	if( !$resultado ){
		echo $sql;
	}
?>