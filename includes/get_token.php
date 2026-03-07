<?php 
require('conexion.php');
//require('../inc/cabeceras.php');

if(isset($_POST['id_usr'])) {
    $user= $_POST['id_usr'];
    $tipo_usuario = $_POST['tip_usr'];
    $plantel  =1;
    //echo 'Datos de formulario: '.'nombre'.$nombre.'token:'.$token;
    $obtencion_token = "SELECT token_usr AS token FROM token_movimiento WHERE id_usr = '$user' AND tipo_usr = '$tipo_usuario' AND date(fecha_token) = date(curdate())";
    //echo $obtencion_token;
    $try_obtencion_token = mysqli_query($db, $obtencion_token);
    $response_token = mysqli_fetch_assoc($try_obtencion_token);
    if ($response_token == null) {
        //echo "el token no fue encontrado";
        $existencia_token = "SELECT token_usr AS token FROM token_movimiento WHERE id_usr = '$user' AND tipo_usr = '$tipo_usuario'";
        $try_get = mysqli_query($db, $existencia_token);
        $response_token = mysqli_fetch_assoc($try_get);

        if($response_token == null){
            $new_token = "INSERT INTO token_movimiento (token_usr,fecha_token,id_usr,tipo_usr,plantel_token) VALUES ( FLOOR(ROUND(RAND()*100000000,0)+1),now(),'$user','$tipo_usuario',$plantel)";
                //echo $new_token;
        $set_token = mysqli_query($db, $new_token);
        }
        else{

            $renew_token = "UPDATE token_movimiento SET token_usr = FLOOR(ROUND(RAND()*100000000,0)+1), fecha_token = now() WHERE id_usr = '$user' AND tipo_usr = '$tipo_usuario'";
            $set_token = mysqli_query($db, $renew_token);
        }
        
        $obtencion_token = "SELECT token_usr AS token FROM token_movimiento WHERE id_usr = '$user' AND tipo_usr = '$tipo_usuario' AND date(fecha_token) = date(curdate())";
        //echo $obtencion_token;
        $try_obtencion_token = mysqli_query($db, $obtencion_token);
        $response_token = mysqli_fetch_assoc($try_obtencion_token);
        $get_token = $response_token['token'];
        echo 'Se renovo el token para hoy; el token de acceso es: '.$get_token.' Asegurese de solo proporcionarselo a los usuarios apropiados';
    }
    else{
        $get_token = $response_token['token'];
        echo 'El token para el día de hoy es: '.$get_token.' Asegurese de solo proporcionarselo a los usuarios apropiados';
    }

}
 ?>