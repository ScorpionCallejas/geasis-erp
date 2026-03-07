<?php
require('conexion.php');

if(isset($_POST['id_pla'])) {
    $plantel= $_POST['id_pla'];
    $sql_get_boss = "SELECT CONCAT(nom_adm,' ',app_adm,' ',apm_adm) AS jefe FROM admin  WHERE id_pla3 = '$plantel' AND director_pla = 'si'";
    $response= mysqli_query($db, $sql_get_boss);
    $jefazo = mysqli_fetch_assoc($response);
}
    //echo var_dump($jefazo);
    $resultado = $jefazo["jefe"];
    if ($resultado == '' || $resultado == null) {
        $resultado = 'No disponible';
    }
    echo $resultado;
    //echo $jefazo['jefe']; director_pla