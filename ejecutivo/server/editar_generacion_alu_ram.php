<?php 
//ARCHIVO VIA AJAX PARA EDITAR GENERACION CON AUDITORÍA AUTOMÁTICA
//alumnos.php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$estatus = $_POST['estatus'];
$id_gen = $_POST['id_gen'];
$id_alu_ram = $_POST['id_alu_ram'];

for ($i = 0; $i < sizeof($id_alu_ram); $i++) { 
    
    // OBTENER DATOS GENERACIÓN ANTERIOR COMPLETOS
    $sqlGenAnterior = "
        SELECT 
            ar.id_gen1,
            g.nom_gen,
            g.ini_gen,
            g.fin_gen,
            g.hor_gen,
            g.dia_gen
        FROM alu_ram ar
        INNER JOIN generacion g ON ar.id_gen1 = g.id_gen
        WHERE ar.id_alu_ram = '$id_alu_ram[$i]'
    ";
    $datosGenAnterior = obtener_datos_consulta($db, $sqlGenAnterior)['datos'];
    $id_gen_anterior = $datosGenAnterior['id_gen1'];
    $nom_gen_anterior = $datosGenAnterior['nom_gen'];
    $ini_gen_anterior = fechaFormateadaCompacta2($datosGenAnterior['ini_gen']);
    $fin_gen_anterior = fechaFormateadaCompacta2($datosGenAnterior['fin_gen']);
    $hor_gen_anterior = $datosGenAnterior['hor_gen'];
    $dia_gen_anterior = $datosGenAnterior['dia_gen'];
    
    // OBTENER DATOS GENERACIÓN NUEVA COMPLETOS
    $sqlGenNueva = "
        SELECT 
            nom_gen,
            ini_gen, 
            fin_gen,
            hor_gen,
            dia_gen
        FROM generacion
        WHERE id_gen = '$id_gen[$i]'
    ";
    $datosGenNueva = obtener_datos_consulta($db, $sqlGenNueva)['datos'];
    $nom_gen_nueva = $datosGenNueva['nom_gen'];
    $ini_gen_nueva = fechaFormateadaCompacta2($datosGenNueva['ini_gen']);
    $fin_gen_nueva = fechaFormateadaCompacta2($datosGenNueva['fin_gen']);
    $hor_gen_nueva = $datosGenNueva['hor_gen'];
    $dia_gen_nueva = $datosGenNueva['dia_gen'];
    
    if ($estatus[$i] == 'true') {
        // SI ES REINGRESO
        
        // ADICION HUELLA GRUPO ORIGEN EN BAJA	
        $sql1 = "
            SELECT * FROM alu_ram WHERE id_alu_ram = '$id_alu_ram[$i]'
        ";
        
        $datos1 = obtener_datos_consulta($db, $sql1)['datos'];
        
        $id_alu_aux = $datos1['id_alu1'];
        $mon_alu_ram = $datos1['mon_alu_ram'];
        $car_alu_ram = $datos1['car_alu_ram'];
        $bec_alu_ram = $datos1['bec_alu_ram'];
        $bec2_alu_ram = $datos1['bec2_alu_ram'];
        $tie_alu_ram = $datos1['tie_alu_ram'];
        $id_gen_aux = $datos1['id_gen1'];
        $id_ram_aux = $datos1['id_ram3'];
        $est1_alu_ram_aux = 'Baja definitiva';

        $sql2 = "
            INSERT INTO alu_ram (mon_alu_ram, car_alu_ram, bec_alu_ram, bec2_alu_ram, id_gen1, id_alu1, id_ram3, tie_alu_ram, est1_alu_ram) 
            VALUES ('$mon_alu_ram', '$car_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$id_gen_aux', '$id_alu_aux', '$id_ram_aux', '$tie_alu_ram', '$est1_alu_ram_aux')
        ";

        $resultado2 = mysqli_query($db, $sql2);
        if (!$resultado2) {
            echo $sql2;
            continue;
        }

        // UPDATE CON REINGRESO
        $sql = "
            UPDATE alu_ram 
            SET id_gen1 = '$id_gen[$i]', 
                est1_alu_ram = 'Reingreso', 
                id_eje66 = '$id', 
                rei_alu_ram = '$fechaHoy'  
            WHERE id_alu_ram = '$id_alu_ram[$i]'
        ";

        // MOVIMIENTO REINGRESO
        $datos = obtenerDatosAlumnoProgramaServer($id_alu_ram[$i]);
        $ing_alu = $datos['ing_alu'];
        $ori_ing_alu_ram = $ing_alu;
        $res_ing_alu_ram = $nombreCompleto.' - '.$tipoUsuario;

        $sqlMovimiento = "
            INSERT INTO ingreso_alu_ram (tip_ing_alu_ram, id_alu_ram14, mot_ing_alu_ram, res_ing_alu_ram, ori_ing_alu_ram)
            VALUES ('Reingreso', '$id_alu_ram[$i]', 'Cambio de grupo', '$res_ing_alu_ram', '$ori_ing_alu_ram')
        ";
        $resultadoMovimiento = mysqli_query($db, $sqlMovimiento);

        if (!$resultadoMovimiento) {
            echo "error, verificar consulta!";
            echo $sqlMovimiento;
            continue;
        }
        
        // MENSAJE DETALLADO PARA REINGRESO
        $obs_seguimiento = "🔄 CAMBIO DE GRUPO -> REINGRESO - Cambió de: [$nom_gen_anterior] ($ini_gen_anterior - $fin_gen_anterior, $dia_gen_anterior $hor_gen_anterior) → [$nom_gen_nueva] ($ini_gen_nueva - $fin_gen_nueva, $dia_gen_nueva $hor_gen_nueva)";
        
    } else {
        // NO ES REINGRESO - CAMBIO SIMPLE
        
        $sql = "
            UPDATE alu_ram 
            SET id_gen1 = '$id_gen[$i]' 
            WHERE id_alu_ram = '$id_alu_ram[$i]'
        ";
        
        // MENSAJE DETALLADO PARA CAMBIO SIMPLE
        $obs_seguimiento = "↔️ Cambio de grupo: [$nom_gen_anterior] ($ini_gen_anterior - $fin_gen_anterior, $dia_gen_anterior $hor_gen_anterior) → [$nom_gen_nueva] ($ini_gen_nueva - $fin_gen_nueva, $dia_gen_nueva $hor_gen_nueva)";
    }
    
    // EJECUTAR UPDATE
    $resultado = mysqli_query($db, $sql);

    if ($resultado) {
        
        // INSERTAR SEGUIMIENTO AUTOMÁTICO EN observacion_alu_ram
        $sqlSeguimiento = "
            INSERT INTO observacion_alu_ram (obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram)
            VALUES ('$obs_seguimiento', '$id_alu_ram[$i]', '$nombreCompleto')
        ";
        mysqli_query($db, $sqlSeguimiento);
        
        echo 'Exito';
        
    } else {
        echo "Error, verificar consulta";
    }
}

?>