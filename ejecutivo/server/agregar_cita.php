<?php 
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    if (isset($_POST['accion'])) {
        $id_con = $_POST['id_con'];
        
        $nom_cit = $_POST['nombre'];
        $tel_cit = $_POST['telefono'];
        $obs_cit = $_POST['observaciones'];
        $pro_cit = $_POST['producto'];
        $can_cit = $_POST['mercado'];
        
        $fecha_actual = date('Y-m-d');
        $tip_cit = 'Videoconferencia';

        $cit_cit = date('Y-m-d 13:00:00', strtotime($_POST['fecha']));
        $hor_cit = isset($_POST['horario']) ? date('H:i', strtotime($_POST['horario'])) : date('H:i');

        // Verificar y ajustar el horario - AMPLIADO DE 8AM A 10PM
        if (strtotime($hor_cit) < strtotime('08:00') || strtotime($hor_cit) > strtotime('22:00')) {
            $hor_cit = '13:00';
        }

        $sql = "
            INSERT INTO cita ( nom_cit, tel_cit, cit_cit, tip_cit, obs_cit, id_eje3, hor_cit, id_con2, pro_cit, can_cit, id_eje_agendo, cam_cit ) 
            VALUES ( '$nom_cit', '$tel_cit', '$cit_cit', '$tip_cit', '$obs_cit', $id, '$hor_cit', '$id_con', '$pro_cit', '$can_cit', '$id', 1 )
        ";

        // echo $sql;

    } else {
        $nom_cit = $_POST['nom_cit_for'];
        $tel_cit = $_POST['tel_cit_for'];
        $hor_cit = date('H:i', strtotime($_POST['hor_cit_for']));
        $fecha_actual = date('Y-m-d');

        $cit_cit = date('Y-m-d 13:00:00', strtotime($_POST['cit_cit_for']));

        $tip_cit = $_POST['tip_cit_for'];
        $obs_cit = $_POST['obs_cit_for'];

        $eda_cit = $_POST['eda_cit_for'];
        $pro_cit = $_POST['pro_cit_for'];

        // Verificar y ajustar el horario - AMPLIADO DE 8AM A 10PM
        if (strtotime($hor_cit) < strtotime('08:00') || strtotime($hor_cit) > strtotime('22:00')) {
            $hor_cit = '13:00';
        }

        $sql = "
            INSERT INTO cita ( nom_cit, tel_cit, cit_cit, tip_cit, obs_cit, id_eje3, hor_cit, id_eje_agendo, pro_cit, eda_cit, cam_cit ) 
            VALUES ( '$nom_cit', '$tel_cit', '$cit_cit', '$tip_cit', '$obs_cit', $id, '$hor_cit', '$id', '$pro_cit', '$eda_cit', 1 )
        ";
    }

    $resultado = mysqli_query($db, $sql);

    if (!$resultado) {
        echo $sql;
    }
?>