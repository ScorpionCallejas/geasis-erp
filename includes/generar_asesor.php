<?php 
require('conexion.php');

if(isset($_POST['nombre'])) {
    $nom_eje= $_POST['nombre'];
    $app_eje= $_POST['app_eje'];
    $apm_eje= $_POST['apm_eje'];
    $tel_eje= $_POST['tel_eje'];
    $correo= $_POST['cor_eje'];
    $pas_eje= $_POST['pas_eje'];
    $grado_estudios= $_POST['estudios_eje'];
    $plantel= $_POST['plantel'];
    $token= $_POST['token'];
    $mail_personal= $_POST['correo_personal'];
    $nac_eje = date('Y-m-d');
    $ing_eje = date('Y-m-d');
    $dir_eje = 'Pendiente';
    $cp_eje  = 'Pendiente';
    $ran_eje = 'Ejecutivo';
    $esquema_pago = $_POST['seleccion_esquema'];
    //echo 'Datos de formulario: '.'nombre:'.$nom_eje;

    $confirmar_token = "SELECT validar_token('$token') AS llave";
    //echo $confirmar_token;
    $try_confirmar_token= mysqli_query($db, $confirmar_token);
    $get_token = mysqli_fetch_assoc($try_confirmar_token);
    $response = $get_token['llave'];
    //echo 'Respuesta: '.$response;
    if ($response == 'no') {
        echo "604"; //error en el token y no va a hacer nada
    }
    else{

        $alu_eje = 0;

    //echo $ran_eje;

    switch ( $ran_eje ) {
        
        case 'Ejecutivo':
            $alu_eje = 4;       
            break;
        case 'Líder de consultores';
            $alu_eje = 3;
            break;
        case 'Gerente de red':
            $alu_eje = 2;
            break;

        case 'Gerente comercial':
            $alu_eje = 1;
            break;  
        
    }

    // echo $alu_eje;


    $nombreEjecutivo = $nom_eje." ".$app_eje." ".$apm_eje;

    $sqlInsercionEmpleado = "INSERT INTO empleado (cor_emp, nom_emp, app_emp, apm_emp, tel_emp, nac_emp, ing_emp, id_pla6, tip_emp, est_emp, cp_emp, dir_emp) VALUES ('$correo', '$nom_eje', '$app_eje', '$apm_eje', '$tel_eje', '$nac_eje', '$ing_eje', '$plantel', 'Ejecutivo',  'Activo', '$cp_eje', '$dir_eje')";

    $resultadoInsercionEmpleado = mysqli_query($db, $sqlInsercionEmpleado);

    if ($resultadoInsercionEmpleado) {
    //INSERCION DE DIRECTIVO A EMPLEADO EXITOSA, PROSEGUIMOS CON EL ALTA AHORA A DIRECTIVO

    $sqlMaxEmpleado = "SELECT MAX(id_emp) AS ultimoEmpleado FROM empleado";
    $resultadoMaxEmpleado = mysqli_query($db, $sqlMaxEmpleado);

    if ($resultadoMaxEmpleado) {
        
        $filaMaxEmpleado = mysqli_fetch_assoc($resultadoMaxEmpleado);
        $maxEmpleado = $filaMaxEmpleado['ultimoEmpleado'];


        // FOTO
        // FOTO VACIA
        $foto = "foto-ejecutivo00".$maxEmpleado.".jpg";

        $fichero = '../../img/usuario.jpg';
        $nuevo_fichero = '../../uploads/'.$foto;


        if (!copy($fichero, $nuevo_fichero)) {
            echo "Error al copiar $fichero...\n";
        } else {

            //ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
            $sqlUpdateAlumno = "UPDATE empleado SET fot_emp = '$foto' WHERE id_emp = '$maxEmpleado'";

            mysqli_query($db, $sqlUpdateAlumno);

        }
        // FIN FOTO VACIA
        // FOTO

        $sqlInsercionEjecutivo = "INSERT INTO ejecutivo (nom_eje, app_eje, apm_eje, gen_eje, equ_eje, pas_eje, tel_eje, nac_eje, ing_eje, cor_eje, cor2_eje, tip_eje, est_eje, dir_eje, cp_eje, id_emp4, ran_eje, esquema_eje, estudios_eje, alu_eje ) VALUES ('$nom_eje', '$app_eje',   '$apm_eje',   '$gen_eje',  '$equ_eje', '$pas_eje',  '$tel_eje', '$nac_eje',  '$ing_eje', '$correo', '$mail_personal', 'Ejecutivo', 'Inactivo', '$dir_eje', '$cp_eje', '$maxEmpleado', '$esquema_pago', '$grado_estudios', 'Ejecutivo', '$alu_eje')";

        $resultadoInsercionEjecutivo = mysqli_query($db, $sqlInsercionEjecutivo);
        
        if ($resultadoInsercionEjecutivo) {
            
            // LOG

            //$des_log =  obtenerDescripcionPersonalLogServer( $nomResponsable, 'registró', 'ejecutivo', $nombreEjecutivo );
           
            //logServer ( 'Alta', $tipoUsuario, $id, 'Ejecutivo', $des_log, $plantel );
            // FIN LOG

            echo "Exito";
        }else{
            echo $sqlInsercionEjecutivo;
        }               
    
    }else{
        echo "Error en consulta de maximo empleado";
    }

    //echo "Exito";
}else{
    echo "Error en alta de profesor a empleado, verificar consulta";
    echo $sqlInsercionEmpleado;
}

echo "Hecho!";
        
    }

}
 ?>