<?php
  
  // FUNCIONES DE ENCUESTAS
  function obtener_escala_encuesta( $id_cad, $id_pla, $db ){

    if ( $id_cad != null ) {
        
        $sql = "
            SELECT *
            FROM cadena
            WHERE id_cad = '$id_cad'
        ";

        $datos['escala'] = obtener_datos_consulta( $db, $sql )['datos']['nom_cad'];

    } else {

        $sql = "
            SELECT *
            FROM plantel
            WHERE id_pla = '$id_pla'
        ";

        $datos['escala'] = obtener_datos_consulta( $db, $sql )['datos']['nom_pla'];
        
    }

    return $datos['escala'];
  
  }

  // FUNCIONES FUSION
  function obtener_eliminacion_actividades_server( $id_sub_hor ){
    require('../../includes/conexion.php');

    $sql = "
      DELETE FROM foro_copia WHERE id_sub_hor2 = '$id_sub_hor';
      DELETE FROM entregable_copia WHERE id_sub_hor3 = '$id_sub_hor';
      DELETE FROM examen_copia WHERE id_sub_hor4 = '$id_sub_hor';
    ";

    $resultado = mysqli_multi_query( $db, $sql );

    if ( !$resultado ) {
      
      echo $sql;

    }

  }

  function obtener_conteo_datos_fusion_server( $id_fus ){
    require('../../includes/conexion.php');
    // MATERIAS - PROGRAMAS - MODALIDADES - CDES - ALUMNOS - SALONES
    $datos = array();


    // CONTEO GENERAL
    $sql = "
      SELECT *
      FROM sub_hor
      INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
      INNER JOIN rama ON rama.id_ram = materia.id_ram2
      INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
      WHERE id_fus2 = '$id_fus'
    ";

    $datos1 = obtener_datos_consulta( $db, $sql );

    $datos['total_materias'] = $datos1['total'];
    // FIN CONTEO GENERAL

    // ALUMNOS
    $sqlAlumnos = "
      SELECT *
      FROM alu_hor
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
      INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
      INNER JOIN rama ON rama.id_ram = materia.id_ram2
      INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
      WHERE id_fus2 = '$id_fus' AND est_alu_hor = 'Activo'
    ";


    $datos2 = obtener_datos_consulta( $db, $sqlAlumnos );

    $datos['total_alumnos'] = $datos2['total'];
    // FIN ALUMNOS

    return $datos;

  }

  
  // FIN FUNCIONES FUSION
  function obtener_plantel_administrador_server( $id_pla ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT nom_pla
      FROM plantel
      WHERE id_pla = '$id_pla'
    ";

    $resultado = mysqli_query( $db, $sql );

    $datos = mysqli_fetch_assoc( $resultado );

    return $datos;
  }

  function obtener_tipo_usuario( $tipo ){

    switch ( $tipo ) {
      case 'Admin':
        return 'Coordinador de CDE';
        break;

      case 'Adminge':
        return 'Coordinador de académico';
        break;
      
      case 'Super':
        return 'Superadministrador';
        break;

      case 'Caja':
        return 'Servicios empresariales';
        break;

    }
  }


  function obtener_datos_plantel( $id_pla ){
    require('../includes/conexion.php');

    $sql = "
      SELECT *
      FROM plantel
      WHERE id_pla = '$id_pla'
    ";

    $resultado = mysqli_query( $db, $sql );

    if ( !$resultado ) {

      echo $sql;
    
    } else {
      
      $fila = mysqli_fetch_assoc( $resultado );

      return $fila;

    }

  }


  function obtener_datos_usuario_server( $tipo, $id ){
    require('../../includes/conexion.php');

    if ( $tipo == 'Admin' ) {
            
      $sql = "
          SELECT nom_adm AS nombre, tip_adm AS tipo, fot_emp AS foto, id_adm AS id
          FROM admin
          INNER JOIN empleado ON empleado.id_emp = admin.id_emp7
          WHERE id_adm = '$id'
      ";
        
    } else if ( $tipo == 'Adminge' ) {

      $sql = "
          SELECT nom_adg AS nombre, tip_adg AS tipo, fot_emp AS foto, id_adg AS id
          FROM adminge
          INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6
          WHERE id_adg = '$id'
      ";

    } else if ( $tipo == 'Cobranza' ) {
        
      $sql = "
          SELECT nom_cob AS nombre, tip_cob AS tipo, fot_emp AS foto, id_cob AS id
          FROM cobranza
          INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
          WHERE id_cob = '$id'
      ";

    } else if ( $tipo == 'Profesor' ) {

      // echo 'entree';
      $sql = "
          SELECT nom_pro AS nombre, tip_pro AS tipo, fot_emp AS foto, id_pro AS id
          FROM profesor
          INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
          WHERE id_pro = '$id'
      ";

    } else if ( $tipo == 'Ejecutivo' ) {
    
      $sql = "
          SELECT nom_eje AS nombre, tip_eje AS tipo, fot_emp AS foto, id_eje AS id
          FROM ejecutivo
          INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
          WHERE id_eje = '$id'
      ";

    } else if ( $tipo == 'Alumno' ) {
        
      $sql = "
          SELECT nom_alu AS nombre, tip_alu AS tipo, fot_alu AS foto, id_alu AS id
          FROM alumno
          WHERE id_alu = '$id'
      ";

    } else if ( $tipo == 'Superadmin' ) {
      
      $sql = "
          SELECT nom_sup AS nombre, 'Superadmin' AS tipo, 'usuario.png' AS foto, id_sup AS id
          FROM superadmin
          WHERE id_sup = '$id'
      ";

      $resultado = mysqli_query( $db, $sql );

      if ( $resultado ) {
        
        $fila = mysqli_fetch_assoc( $resultado );
        
        return $fila;

      } else {
        
        echo $sql;
      
      }

    }

  }


  function obtener_datos_sub_hor_server( $id_sub_hor ){

    require('../../includes/conexion.php');

    $sql = "
          SELECT *
          FROM sub_hor
          INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
          INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
          INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
          WHERE id_sub_hor = '$id_sub_hor'
      ";

      $resultado = mysqli_query( $db, $sql );

      if ( $resultado ) {
          
          // echo $sql;
          $fila = mysqli_fetch_assoc( $resultado );

      return $fila;

    }
  }


  function obtener_datos_generacion_server( $id_gen ){
    require('../../includes/conexion.php');
    
    $sql = "
      SELECT *
      FROM generacion
      WHERE id_gen = '$id_gen'
    ";

    $resultado = mysqli_query( $db, $sql );

    $datos = mysqli_fetch_assoc( $resultado );

    $fechaHoy = date('Y-m-d');
    $diferenciaDias = obtenerDiferenciaFechas( $fechaHoy, $datos['ini_gen'] );

    $dias = obtenerDiferenciaFechas( $datos['fin_gen'], $datos['ini_gen'] );

    if ( $dias == 0 ) {

      $estatusGeneracion = 'Fin curso';
      $porcentajeAvance = 100;

    } else {
      
      $estatusGeneracion = '';
      
      if ( $dias > 0 ) {
        // ACTIVO

        $estatusGeneracion = 'En curso';
        $porcentajeAvance = floor( ( ( $diferenciaDias * 100 ) / $dias ) );

        if ( $porcentajeAvance < 0 ) {
        
          $estatusGeneracion = 'Por comenzar';

          $semana = 'N/A';

          // PENDIENTE 
          $porcentajeAvance = 0;
        
        } else if ( $porcentajeAvance > 100 ) {

          $estatusGeneracion = 'Fin curso';
          // FINALIZADO
          $porcentajeAvance = 100;

          $semana = floor( $dias / 7 );

        } else {

          $semana = floor( $diferenciaDias / 7 );

        }
      } else {
        // PENDIENTE
        $estatusGeneracion = 'Por comenzar';
        $porcentajeAvance = 0;
        $semana = 'N/A';

      }

    }

    $datos['porcentaje_generacion'] = $porcentajeAvance;
    $datos['semana_generacion'] = $semana;
    $datos['estatus_generacion'] = $estatusGeneracion;

    return $datos;
  }
  

  function obtener_datos_vista_alumno_server( $id_alu_ram ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM vista_alumnos
      WHERE id_alu_ram = '$id_alu_ram'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    return $fila;

  }

  function obtener_datos_pagos_alumno_server( $id_alu_ram ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT SUM( mon_ori_pag ) AS potencial, SUM( mon_pag ) AS adeudo
      FROM vista_pagos
      WHERE id_alu_ram = '$id_alu_ram'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $potencial = $fila['potencial'];
    $adeudo = $fila['adeudo'];

    $cobrado = $potencial - $adeudo;

    $porcentaje = ( $cobrado / $potencial ) * 100;

    $porcentaje = round( $porcentaje, 2 );


    $sql2 = "
      SELECT SUM( mon_abo_pag ) AS monto, tip_abo_pag
      FROM vista_pagos
      INNER JOIN abono_pago ON abono_pago.id_pag1 = vista_pagos.id_pag
      WHERE id_alu_ram = '$id_alu_ram'
      GROUP BY tip_abo_pag
    ";

    $resultado2 = mysqli_query( $db, $sql2 );

    $efectivo = 0;
    $tarjeta = 0;
    $deposito = 0;
    $otros = 0;

    while( $fila2 = mysqli_fetch_assoc( $resultado2 ) ){
    
      if ( $fila2['tip_abo_pag'] == 'Efectivo' ) {
        
        $efectivo = $fila2['monto'];
    
      } else if ( $fila2['tip_abo_pag'] == 'Tarjeta' ) {
        
        $tarjeta = $fila2['monto'];

      } else if ( $fila2['tip_abo_pag'] == 'Depósito' ) {
        
        $deposito = $fila2['monto'];

      } else if ( $fila2['tip_abo_pag'] == 'Otro' ) {
        
        $otros = $fila2['monto'];
      
      }
    
    }

    $datos = array();

    $datos['potencial'] = $potencial;
    $datos['adeudo'] = $adeudo;
    $datos['cobrado'] = $cobrado;
    $datos['porcentaje'] = $porcentaje;

    $datos['efectivo'] = $efectivo;
    $datos['tarjeta'] = $tarjeta;
    $datos['deposito'] = $deposito;
    $datos['otros'] = $otros;

    return $datos;

  }


  // BUSCA REGISTROS DE TAREA SIN ARCHIVO, SI ASI ES BORRA EL REGISTRO

//obtencion datos de ejefe
 function obtener_datos_jefe( $id){
    require('../../includes/conexion.php');
    $id_consulta = $id;
    $sql_consulta_jefe = "SELECT CONCAT(nom_eje,' ',app_eje) AS nombre, fot_eje AS foto, ran_eje AS rango, voy_rel  AS id
                          FROM relacion 
                          INNER JOIN ejecutivo ON ejecutivo.id_eje = relacion.voy_rel
                          where relacion.soy_rel = '$id_consulta'";
    $respuesta_jefe = mysqli_query($db, $sql_consulta_jefe);
    $fila_jefe = mysqli_fetch_assoc($respuesta_jefe);
    if ($fila_jefe == NULL) {
      $resultado_jefe['nombre']= 'No asignado';
      $resultado_jefe['foto']='';
      $resultado_jefe['badge']= 'red darken-4';
      $resultado_jefe['id']=$id_consulta;
    }
    else{
      $resultado_jefe['nombre']= $fila_jefe['nombre'];
      $resultado_jefe['foto']=$fila_jefe['foto'];
      $rango = $fila_jefe['rango'];
      $resultado_jefe['id'] = $fila_jefe['id'];
      switch ($rango) {
        case 'Gerente comercial':
          $resultado_jefe['badge']= 'red darken-4';
          break;
        case 'Gerente de red':
          $resultado_jefe['badge']= 'amber lighten-3';
          break;
        case 'Líder de consultores':
          $resultado_jefe['badge']= 'success-color';
          break;
        default:
          $resultado_jefe['badge']= 'cyan darken-2';
          break;
      }
      
    }
    return $resultado_jefe;

  }
  //fin de obtencion

  function obtener_existencia_tarea( $id_tar, $id_alu_ram, $id_ent_cop ){
    require('../includes/conexion.php');

    $sql = "
      SELECT *
      FROM tarea
      WHERE id_tar = '$id_tar'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    if ( $fila['doc_tar'] != NULL ) {

      $archivo = '../uploads/'.$fila['doc_tar'];
      $existencia = file_exists( $archivo );
      
      
      // if ( $validacionEliminacion == 1 ) {
      //   unlink( $path );
      // }

    }

    if ( $existencia == 1 ) {
        
      return '';
   
    } else {
   
      // UPDATE

      $sqlUpdate = "
        UPDATE cal_act
        SET
        fec_cal_act = NULL
        WHERE 
        id_alu_ram4 = '$id_alu_ram'
        AND
        id_ent_cop2 = '$id_ent_cop'
      ";

      $resultadoUpdate = mysqli_query( $db, $sqlUpdate );

      if ( !$resultadoUpdate ) {
        echo $sqlUpdate;
      }

      // FIN UPDATE


      $sqlDelete = "
        DELETE FROM tarea WHERE id_tar = '$id_tar'
      ";

      $resultadoDelete = mysqli_query( $db, $sqlDelete );



      if ( !$resultadoDelete ) {
        
        echo $sqlDelete;
      
      } else {

        echo 'Error';
      
      }
   
    }

  }


  // BUSCA SI EXISTE ARCHIVO ASOCIADO A REGISTRO RETORNA 0 O 1
  function obtener_estatus_tarea( $id_tar ){
    require('../includes/conexion.php');

    $sql = "
      SELECT *
      FROM tarea
      WHERE id_tar = '$id_tar'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    if ( $fila['doc_tar'] != NULL ) {

      $archivo = '../uploads/'.$fila['doc_tar'];
      $existencia = file_exists( $archivo );
      
      
      // if ( $validacionEliminacion == 1 ) {
      //   unlink( $path );
      // }

    }

    if ( $existencia == 1 ) {
        
      return '1';
   
    } else {
   
      return '0';
    
    }

  }
  
  // FUNCIONES DE MENSAJERIA
  function obtener_existencia_sala_server( $id1, $tipo1, $id2, $tipo2 ){
    require('../../includes/conexion.php');

    $sql = "
      
      SELECT *
      FROM usuario_sala
      WHERE ( usu_usu_sal = '$id1' AND tip_usu_sal = '$tipo1' ) 
    
    ";

    // echo $sql;

    $resultado = mysqli_query( $db, $sql );

    $resultado2 = mysqli_query( $db, $sql );
    $total = mysqli_num_rows( $resultado2 );

    if ( $total == 0 ) {
      
      return 'Falso';
    
    } else {

      $id_sal_aux = '';

      while( $fila = mysqli_fetch_assoc( $resultado ) ){

        $id_sal = $fila['id_sal6'];

        
        $sql4 = "
          
          SELECT *
          FROM usuario_sala
          WHERE ( id_sal6 = '$id_sal' )
        
        ";

        // echo $sql4;

        $totalValidacion = obtener_datos_consulta( $db, $sql4 )['total'];

        // echo 'total val: '.$totalValidacion;

        if ( $totalValidacion > 2 ) {
          
          $id_sal_aux = 'Falso';
          // echo 'falso 56';

        } else if ( ( $totalValidacion == 0 ) || ( $totalValidacion == 2 ) ) {


          $sql3 = "
          
            SELECT *
            FROM usuario_sala
            WHERE ( id_sal6 = '$id_sal' ) AND ( usu_usu_sal = '$id2' AND tip_usu_sal = '$tipo2' )
          
          ";

          // echo $sql3;

          $resultado3 = mysqli_query( $db, $sql3 );

          $total3 = mysqli_num_rows( $resultado3 );

          if ( $total3 == 1 ) {
            
            $id_sal_aux = $id_sal;
            break;
            break;

          } else {

            $id_sal_aux = 'Falso';
          
          }


        }

        
        
      }

      return $id_sal_aux;

    }
    

  }
  
 
  function obtener_conteo_notificaciones_usuario_server( $id, $tipo ){

    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM estatus_mensaje
      INNER JOIN mensaje ON mensaje.id_men = estatus_mensaje.id_men2
      WHERE est_est_men = 'Entregado' AND ( use_est_men = '$id' AND tip_est_men = '$tipo' )
    ";

    $total = obtener_datos_consulta( $db, $sql )['total'];

    return $total;

  }

  function obtener_conteo_notificaciones_sala_server( $id_sal, $id, $tipo ){

    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM estatus_mensaje
      INNER JOIN mensaje ON mensaje.id_men = estatus_mensaje.id_men2
      WHERE id_sal4 = '$id_sal' AND est_est_men = 'Entregado' AND ( use_est_men = '$id' AND tip_est_men = '$tipo' )
    ";

    $total = obtener_datos_consulta( $db, $sql )['total'];

    return $total;

  }
  
  function obtener_notificaciones_sala_server( $id_sal, $id, $tipo ){

    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM estatus_mensaje
      INNER JOIN mensaje ON mensaje.id_men = estatus_mensaje.id_men2
      WHERE id_sal4 = '$id_sal' AND est_est_men = 'Entregado' AND ( use_est_men = '$id' AND tip_est_men = '$tipo' )
    ";

    $total = obtener_datos_consulta( $db, $sql )['total'];

    if ( $total > 0 ) {
      // return 'Entregado';
      return '<div class="badge badge-success badge-pill small" id="unread-count">'.$total.'</div>';
    
    }

  }

  function obtener_estatus_mensaje_server( $id_men ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM estatus_mensaje
      WHERE id_men2 = '$id_men' AND est_est_men = 'Visto'
    ";

    $total = obtener_datos_consulta( $db, $sql )['total'];

    if ( $total == 0 ) {
      // return 'Entregado';
      return '<i class="far fa-check-circle" style="color: grey;"></i>';
    
    } else {
      // return 'Visto';
      return '<i class="fas fa-check-circle" style="color: #33b5e5;"></i>';
    }
  
  }

  function obtener_datos_consulta( $db, $sql ){

    $datos = array();
    $datos['total'] = '';
    $datos['datos'] = '';

    $resultado = mysqli_query( $db, $sql );

    if ( $resultado ) {
      
      $resultado2 = mysqli_query( $db, $sql );

      $datos['total'] = mysqli_num_rows( $resultado2 );

      $datos['datos'] = mysqli_fetch_assoc( $resultado );

      return $datos;
    
    } else {

      echo $sql;
    
    }
    
  }


  function obtener_datos_contacto_mensajeria_server( $tipo, $id ){
    require('../../includes/conexion.php');

    // echo $id;

    if ( $tipo == 'Admin' ) {
            
      $sql = "
          SELECT nom_adm AS nombre, tip_adm AS tipo, fot_emp AS foto, id_adm AS id
          FROM admin
          INNER JOIN empleado ON empleado.id_emp = admin.id_emp7
          WHERE id_adm = '$id'
      ";
        
    } else if ( $tipo == 'Adminge' ) {

      $sql = "
          SELECT nom_adg AS nombre, tip_adg AS tipo, fot_emp AS foto, id_adg AS id
          FROM adminge
          INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6
          WHERE id_adg = '$id'
      ";

    } else if ( $tipo == 'Cobranza' ) {
        
      $sql = "
          SELECT nom_cob AS nombre, tip_cob AS tipo, fot_emp AS foto, id_cob AS id
          FROM cobranza
          INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
          WHERE id_cob = '$id'
      ";

    } else if ( $tipo == 'Profesor' ) {

      // echo 'entree';
      $sql = "
          SELECT nom_pro AS nombre, tip_pro AS tipo, fot_emp AS foto, id_pro AS id
          FROM profesor
          INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
          WHERE id_pro = '$id'
      ";

    } else if ( $tipo == 'Ejecutivo' ) {
    
      $sql = "
          SELECT nom_eje AS nombre, tip_eje AS tipo, fot_emp AS foto, id_eje AS id
          FROM ejecutivo
          INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
          WHERE id_eje = '$id'
      ";

    } else if ( $tipo == 'Alumno' ) {
        
      $sql = "
          SELECT nom_alu AS nombre, tip_alu AS tipo, fot_alu AS foto, id_alu AS id
          FROM alumno
          WHERE id_alu = '$id'
      ";

    }

    $resultado = mysqli_query( $db, $sql );

    // echo $sql;

    if ( $resultado ) {

      $fila = mysqli_fetch_assoc( $resultado );

      return $fila;
      
    } else {
      
      echo $sql;
    
    } 

  }




  // FIN FUNCIONES DE MENSAJERIA


  function generar_pago_generacion_server( $id_gen2, $tip_gen_pag, $folioPlantel, $nomResponsable ){
    require('../../includes/conexion.php');

    // ALGORITMO generacion_pagos
    $datosGeneracion = obtenerDatosGeneracionProgramaLogServer( $id_gen2 );

    $cos_ram = $datosGeneracion['cos_ram'];

    $sqlPagos = "
      SELECT *
      FROM generacion_pago
      WHERE id_gen2 = '$id_gen2' AND tip_gen_pag = '$tip_gen_pag'
      ORDER BY ini_gen_pag DESC
      LIMIT 1
    ";

    
    $resultadoTotal = mysqli_query( $db, $sqlPagos );

    $total = mysqli_num_rows( $resultadoTotal );

    if ( $total == 0 ) {
      
      $date = date_create();
      date_date_set($date, date("Y"), date("m"), 1);
      $date  = date_format( $date, 'Y-m-d');
      $ini_gen_pag = date( "Y-m-1", strtotime( $date."+ 1 month") );
      $fin_gen_pag = date( "Y-m-d", strtotime( $ini_gen_pag."+ 4 day") );

    } else {

      $resultadoPagos = mysqli_query( $db, $sqlPagos );

      $filaPagos = mysqli_fetch_assoc( $resultadoPagos );

      $ini_gen_pag_pasada = $filaPagos['ini_gen_pag'];

      $ini_gen_pag = date( "Y-m-d", strtotime( $ini_gen_pag_pasada."+ 1 month") );  
      $fin_gen_pag = date( "Y-m-d", strtotime( $ini_gen_pag."+ 4 day") );
    
    }

    

    $diaHoy = 1;  
    

    $mesHoy = date( "m", strtotime( $ini_gen_pag ) );

    $con_gen_pag = $tip_gen_pag.' '.$diaHoy.' al '.( $diaHoy + 4 ).' '.substr( getMonth( $mesHoy ), 0, 3 );
    
    $mon_gen_pag = $cos_ram;
        
    $tip1_gen_pag = 'Nulo';
    $des_gen_pag = 0;
    $int_gen_pag = 'Nulo';
    $tip2_gen_pag = 'Nulo';
    $car_gen_pag = 0;
    $pro_gen_pag = date('Y-m-d');
    
    $sqlPago = "
            
      INSERT INTO generacion_pago ( con_gen_pag, tip_gen_pag, mon_gen_pag, ini_gen_pag, fin_gen_pag, tip1_gen_pag, des_gen_pag, int_gen_pag, tip2_gen_pag, car_gen_pag, pro_gen_pag, id_gen2 ) 
      VALUES ( '$con_gen_pag', '$tip_gen_pag', '$mon_gen_pag', '$ini_gen_pag', '$fin_gen_pag', '$tip1_gen_pag', '$des_gen_pag', '$int_gen_pag', '$tip2_gen_pag', '$car_gen_pag', '$pro_gen_pag', '$id_gen2' )

    ";

    $resultadoPago = mysqli_query( $db, $sqlPago );

    if ( !$resultadoPago ) {
      
      echo $sqlPago;
    
    } else {

      $id_gen_pag = obtenerUltimoIdentificadorServer( 'generacion_pago', 'id_gen_pag' );

      $sqlGeneracion = "
        SELECT *
        FROM alu_ram
        WHERE id_gen1 = '$id_gen2'
      ";

      $resultadoGeneracion = mysqli_query( $db, $sqlGeneracion );

      while( $filaGeneracion = mysqli_fetch_assoc( $resultadoGeneracion ) ){

        // 

        $datosGeneracionPago = obtener_datos_generacion_pago_server( $id_gen_pag );

        $id_alu_ram10 = $filaGeneracion['id_alu_ram'];

        $fec_pag = date('Y-m-d');

        $mon_ori_pag = $datosGeneracionPago['mon_gen_pag'];

        $mon_pag = $mon_ori_pag;

        $con_pag = $datosGeneracionPago['con_gen_pag'];;

        $est_pag = 'Pendiente';

        $res_pag = $nomResponsable;

        $ini_pag = $datosGeneracionPago['ini_gen_pag'];

        $fin_pag = $datosGeneracionPago['fin_gen_pag'];

        $pro_pag = date('Y-m-d');

        $pri_pag = 1;

        $tip1_pag = 'Monetario';

        $tip2_pag = '';

        $car_pag = $datosGeneracionPago['car_gen_pag'];

        $des_pag = 0;

        $int_pag = '';

        $tip_pag = $datosGeneracionPago['tip_gen_pag'];

        $sqlInsercionPago = "
          INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, int_pag, id_alu_ram10, id_gen_pag2, tip_pag ) 
          VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$int_pag', '$id_alu_ram10', '$id_gen_pag', '$tip_pag' )
        ";

        $resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
        
        if ( !$resultadoInsercionPago ) {
        
          echo $sqlInsercionPago;
        
        }else {
          // OBTENCION DE id MAXIMO DE PAGO
          // PARA INSERCION DE FOLIO 
          $id_pag = obtenerUltimoIdentificadorServer( 'pago', 'id_pag' );

          $fol_pag = $folioPlantel."00".$id_pag;

          $sqlUpdatePago = "
            UPDATE pago
            SET 
            fol_pag = '$fol_pag'
            WHERE
            id_pag = '$id_pag'
          ";

          $resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

          if ( !$resultadoUpdatePago ) {
            
            echo $sqlMaximoPago;

          }

        }

        // 

      }

    }
    // FIN ALGORITMO generacion_pagos

  }

  function obtener_badge_nuevo( $fechaTermino ){
    $fechaHoy = date('Y-m-d');

    if ( $fechaTermino < $fechaHoy ) {
      return '<span class="badge badge-danger font-weight-normal">¡Nuevo!</span>';
    }    
  }

  function obtenerEstatusProyectoAdminServer( $id_pro ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM proyecto
      WHERE id_pro = '$id_pro'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $fechaHoy = date( 'Y-m-d' );
    if ( $fechaHoy < $fila['ini_pro'] ) {
      
      return 'Pendiente';

    } else if ( ( $fechaHoy >= $fila['ini_pro'] ) && ( $fechaHoy <= $fila['fin_pro'] ) ) {

      return 'Activa';

    } else if ( $fechaHoy > $fila['fin_pro'] ) {

      return 'Vencida';

    }



    
  }

  function obtener_datos_generacion_pago_server( $id_gen_pag ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM generacion_pago
      WHERE id_gen_pag = '$id_gen_pag'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    return $fila;
    
  }


  function obtenerSemana( $fecha ){
    require('../includes/conexion.php');

    $fechaHoy = $fecha;

    $sql = "
      SELECT WEEK(curdate(),7) AS semana
    ";

    $resultado = mysqli_query( $db, $sql );
    
    $fila = mysqli_fetch_assoc( $resultado ); 

    echo 'Semana '.$fila['semana'];
                  
  // 
  }



  function obtenerColorRango( $rango ){
    $rango_ejecutivo = $rango;
    $etiqueta;

    switch ($rango_ejecutivo) {
      
      case 'Gerente comercial':
      
        $etiqueta = 'bg-danger';
        break;

      case 'Gerente de red':
      
          $etiqueta = 'bg-warning';
          break;
      
      case 'Líder de consultores':
          
          $etiqueta = 'bg-success';
          break;

      default:
          $etiqueta = 'bg-info';
          break;
    }

    return $etiqueta;
  }

  function generar_etiqueta($rango){

    $rango_ejecutivo = $rango;
    $etiqueta;

    switch ($rango_ejecutivo) {
      case 'Gerente comercial':
         $etiqueta = 'gerente_comercial';
        break;

      case 'Gerente de red':
          $etiqueta = 'gerente_red';
          break;
      case 'Líder de consultores':
          $etiqueta = 'lider_consultor';
          break;
      default:
          $etiqueta = 'consultor';
        break;
    }

    return $etiqueta;
  }

  function obtenerTituloReporteServer( $id_eje, $inicio, $fin, $tipo ){

    $titulo = '';

    if ( $id_eje == 'Todos' ) {
      $titulo = 'Reporte comercial general de '.strtolower( $tipo ).'. Con inicio del '.fechaFormateadaCompacta2( $inicio ).' al '.fechaFormateadaCompacta2( $fin );


    } else if ( $id_eje == 'Tabla' ) {
      
      $titulo = 'Reporte comercial general. Con inicio del '.fechaFormateadaCompacta2( $inicio ).' al '.fechaFormateadaCompacta2( $fin );
    
    } else {
      
      $datos = obtenerDatosEjecutivoServer( $id_eje );
      $titulo = 'Reporte comercial del ejecutivo: '.$datos['nom_eje'].' '.$datos['app_eje'].' de '.strtolower( $tipo ).'. Con inicio del '.fechaFormateadaCompacta2( $inicio ).' al '.fechaFormateadaCompacta2( $fin );
    }
    

    return $titulo;
  }


  function obtenerNodoRaizServer( $id_eje ){
    require('../../includes/conexion.php');
    
    $sql = "
      SELECT voy_rel AS id_eje
      FROM relacion 
      INNER JOIN ejecutivo ON ejecutivo.id_eje = relacion.voy_rel 
      WHERE ( soy_rel = '$id_eje' )
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $resultadoTotal = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultadoTotal );

    // echo $total;
    if ( $total == 0 ) {
       

      return $id_eje;
      
      
    } else {
      
      $id_eje = $fila['id_eje'];
      return obtenerNodoRaizServer( $id_eje );

    }

    

  }

  // RECURSIVA
  function obtenerNodoRaizServer2( $id_eje, &$contador, &$jerarquias ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM relacion 
      INNER JOIN ejecutivo ON ejecutivo.id_eje = relacion.voy_rel
      WHERE ( soy_rel = '$id_eje' )
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );
    $id_eje_padre = $fila['voy_rel'];

    $resultadoTotal = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultadoTotal );


    if ( ( ($total == 0) && ($contador == 0) ) ) {

      $datosEjecutivo = obtenerDatosEjecutivoSimpleServer( $id_eje );
      // return 0;
      $jerarquias[$contador]['ran_eje'] = $datosEjecutivo['ran_eje'];
      $jerarquias[$contador]['nom_eje'] = $datosEjecutivo['nom_eje'].' '.$datosEjecutivo['app_eje'].' '.$datosEjecutivo['apm_eje'];

      $contador++;

      
    } else if ( $total == 1 ) {

      if ( $contador == 0 ) {
        
        $datosEjecutivo = obtenerDatosEjecutivoSimpleServer( $id_eje );
        // return 0;
        $jerarquias[$contador]['ran_eje'] = $datosEjecutivo['ran_eje'];
        $jerarquias[$contador]['nom_eje'] = $datosEjecutivo['nom_eje'].' '.$datosEjecutivo['app_eje'].' '.$datosEjecutivo['apm_eje'];

        $contador++;

      }

      $jerarquias[$contador]['ran_eje'] = $fila['ran_eje'];
      $jerarquias[$contador]['nom_eje'] = $fila['nom_eje'].' '.$fila['app_eje'].' '.$fila['apm_eje'];

      $contador++;      
      // echo $contador;
      
      obtenerNodoRaizServer2( $id_eje_padre, $contador, $jerarquias );

    }


    $jerarquias['contador'] = $contador;

    return $jerarquias;    

  }

  // FIN RECURSIVA





  function obtenerFechaPrimerColegiatura( $id_alu_ram ){
    require('../includes/conexion.php');


    $sql = "
      SELECT *
      FROM pago
      WHERE id_alu_ram10 = '$id_alu_ram' AND tip_pag = 'Colegiatura' AND est_pag = 'Pagado'
      ORDER BY id_pag ASC
      LIMIT 1
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    return $fila;

  }



  function obtenerFechaPrimerColegiaturaServer( $id_alu_ram ){
    require('../../includes/conexion.php');


    $sql = "
      SELECT *
      FROM pago
      WHERE id_alu_ram10 = '$id_alu_ram' AND tip_pag = 'Colegiatura' AND est_pag = 'Pagado'
      ORDER BY id_pag ASC
      LIMIT 1
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    return $fila;

  }
  
  function obtener_arreglo_cadena( $cadena ){

    $arreglo = array();
     $i = 0;

    $separador = ' ';
    $cadena = explode($separador, $cadena );
    

    // echo sizeof( $cadena );
      
    foreach( $cadena as $palabra ) {
      
        if ( ( sizeof( $cadena ) == 4 ) && ( $i == 0 ) ) {

            $arreglo[$i] = $palabra.' '.$cadena[$i+1];


        } else if ( ( sizeof( $cadena ) == 4 ) && ( $i > 0 ) ){

            $arreglo[$i] = $cadena[$i+1];


        } else {

            $arreglo[$i] = $palabra;
        
        }

        if ( ( ( sizeof( $cadena ) - 2) == $i ) && ( sizeof( $cadena ) == 4 ) ) {
            break; break;
        }
      
      $i++;
        
    }
    
    return $arreglo;
  }

  function obtener_indicadores_ejecutivo_server( $id_eje, $inicio, $fin ){
    require('../../includes/conexion.php');

    $fechaHoy = date('Y-m-d');
    
    $datos = array();
    $datos['citasTotales'] = 0;
    $datos['citasExitosas'] = 0;
    $datos['alumnosTotales'] = 0;
    $datos['alumnosActivos'] = 0;

    // CITAS
    $sqlCitas = "
      SELECT COUNT( * ) AS citasTotales, 
      SUM( CASE WHEN est_cit = 'Exitosa' THEN 1 ELSE 0 END ) AS citasExitosas
      FROM cita
      WHERE ( fec_cit BETWEEN '$inicio' AND '$fin' ) AND ( id_eje3 = '$id_eje' )
    ";


    // echo $sqlCitas;

    $resultadoCitas = mysqli_query( $db, $sqlCitas );

    $filaCitas = mysqli_fetch_assoc( $resultadoCitas );

    
    if ( $filaCitas['citasExitosas'] == NULL ) {
    
      $datos['citasExitosas'] = 0;
    
    } else {
    
      $datos['citasExitosas'] = $filaCitas['citasExitosas'];
    
    }

    $datos['citasTotales'] = $filaCitas['citasTotales'];
    

    // CITAS


    //ALUMNOS
    
    $sql = "
      SELECT *
      FROM vista_alumnos
      WHERE ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND ( id_eje3 = '$id_eje' )
    ";
    // echo $sql;


    $resultado = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultado );
    // echo $total;

    $datos['alumnosTotales'] = $total;


    $sql2 = "
      SELECT *
      FROM vista_alumnos
      WHERE ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND ( estatus_general = 'Activo' ) AND ( id_eje3 = '$id_eje' )
    ";


    // echo $sql2;


    $resultado3 = mysqli_query( $db, $sql2 );

    $totalActivos = mysqli_num_rows( $resultado3 );

    // echo $totalActivos;

    $datos['alumnosActivos'] = $totalActivos;
    // ALUMNOS

    return $datos;

  }


  function obtener_botones_accion_ejecutivo( $id_eje, $datosEjecutivo2, $validacion ){
?>
    
    <!--Dropdown primary-->
    <div class="dropdown " style="position: absolute; top: 22px; right: 5px;">
      
        <!--Trigger-->

      <a class="btn-floating btn-sm waves-effect dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v grey-text"></i>
      </a>


      <!--Menu-->
      <div class="dropdown-menu dropdown-info">
        
        <a class="dropdown-item waves-effect revisionEjecutivo" href="sistema_compensacion_residual.php?id_eje=<?php echo $datosEjecutivo2['id_eje'] ?>" target="_blank">
          Sistema de compensación residual
        </a>

        
        <a class="dropdown-item waves-effect edicionEjecutivo" href="#" id_eje="<?php echo $datosEjecutivo2['id_eje']; ?>">
          Editar
        </a>

        <a class="dropdown-item waves-effect eliminacionEjecutivo" id_eje="<?php echo $datosEjecutivo2['id_eje']; ?>"  href="#" id_emp="<?php echo $datosEjecutivo2['id_emp']; ?>"  nom_eje="<?php echo $datosEjecutivo2['nom_eje']; ?>" validacion="<?php echo $validacion; ?>">
          Eliminar
        </a>


      </div>

    </div>
    <!--/Dropdown primary-->
<?php
  }

  function obtener_nodo_server( $id_eje, $inicio, $fin ){
    require('../../includes/conexion.php');

    // FUNCION
    $sqlNodos = "
      SELECT *
      FROM relacion
      INNER JOIN ejecutivo ON ejecutivo.id_eje = relacion.voy_rel
      WHERE ( voy_rel = '$id_eje' ) AND ( eli_eje = 'Activo' ) 

    ";

    $resultadoNodos = mysqli_query( $db, $sqlNodos );
    $resultadoTotalNodos = mysqli_query( $db, $sqlNodos );

    $totalNodos = mysqli_num_rows( $resultadoTotalNodos );

    while( $filaNodos = mysqli_fetch_assoc( $resultadoNodos ) ){
      
      $id_eje = $filaNodos['soy_rel'];
      $datosEjecutivo2 = obtenerDatosEjecutivoSimpleServer( $id_eje );
      // $datosSrc = obtener_datos_scr_server( $id_eje );
  ?>

      <div class="list-group-item nested-1 card ejecutivos m-3 nodosRedes <?php echo generar_etiqueta($datosEjecutivo2['ran_eje']); ?>" id="<?php echo $datosEjecutivo2['id_eje']; ?>" id_eje="<?php echo $datosEjecutivo2['id_eje']; ?>" style="border-radius: 20px; width: 100%; position: relative;" id="<?php echo $datosEjecutivo2['id_eje']; ?>" nom_eje="<?php echo $datosEjecutivo2['nom_eje']; ?>">

        <div class="chip seleccionEjecutivoFinal" id_eje="<?php echo $datosEjecutivo2['id_eje']; ?>" style="width: 150px;" title="<?php echo $datosEjecutivo2['nom_eje'].' '.$datosEjecutivo2['app_eje'].' '.$datosEjecutivo2['apm_eje']; ?>">
          
          <span class="letraPequena">
            <?php echo comprimirTexto( $datosEjecutivo2['nom_eje'].' '.$datosEjecutivo2['app_eje'] ); ?>
          </span>

          <?php  
            $validacion = 'Ejecutivo';
            obtener_botones_accion_ejecutivo( $id_eje, $datosEjecutivo2, $validacion );
          ?>
          

          <span style="position: absolute; top: 10px; right: 20px;" class="letraPequena grey-text">
            <?php echo $datosEjecutivo2['ran_eje']; ?>

            <?php
              // if ( $datosSrc['estatus'] == 'Consultor' ) {
            ?>
            <!-- <span class="badge badge-pill dusty-grass-gradient font-weight-normal letraMediana"> -->

              <?php
                  // echo $datosSrc['estatus'];
              ?>
            <!-- </span> -->

            <?php
              // }
            ?>
          </span>

            <?php  
              $foto = $datosEjecutivo2['fot_emp'];
            ?>


            <img src="<?php echo ( ( file_exists( '../../uploads/'.$foto ) != 1 ) || ( $foto == NULL ) )? '../img/usuario2.jpg' : '../uploads/'.$foto ?>">
          
          </div>


          
          <div class="list-group nested-sortable">

            <?php  
              if ( $totalNodos > 0 ) {
                obtener_nodo_server( $id_eje, $inicio, $fin );
              }
            ?>
          </div>

      </div>


  <?php
    // FUNCION
    }
  }

?>
<?php
  function obtener_nodo_buscado_server( $id_eje, $inicio, $fin, $palabra ){
    require('../../includes/conexion.php');

    // FUNCION
    $sqlNodos = "
      SELECT *
      FROM relacion
      WHERE voy_rel = '$id_eje'
    ";

    $resultadoNodos = mysqli_query( $db, $sqlNodos );
    $resultadoTotalNodos = mysqli_query( $db, $sqlNodos );

    $totalNodos = mysqli_num_rows( $resultadoTotalNodos );

    while( $filaNodos = mysqli_fetch_assoc( $resultadoNodos ) ){
      
      $id_eje = $filaNodos['soy_rel'];
      $datosEjecutivo2 = obtenerDatosEjecutivoSimpleServer( $id_eje );
      // $datosSrc = obtener_datos_scr_server( $id_eje );
  ?>

      <div class="list-group-item nested-1 card ejecutivos m-3 nodosRedes <?php echo generar_etiqueta($filaEjecutivo['ran_eje']); ?>" id="<?php echo $datosEjecutivo2['id_eje']; ?>" id_eje="<?php echo $datosEjecutivo2['id_eje']; ?>" style="border-radius: 20px; width: 100%; position: relative;" id="<?php echo $datosEjecutivo2['id_eje']; ?>" nom_eje="<?php echo $datosEjecutivo2['nom_eje']; ?>">

        <div class="chip seleccionEjecutivoFinal" id_eje="<?php echo $datosEjecutivo2['id_eje']; ?>" style="width: 150px;" title="<?php echo $datosEjecutivo2['nom_eje'].' '.$datosEjecutivo2['app_eje'].' '.$datosEjecutivo2['apm_eje']; ?>">
          
          <span class="letraPequena">

              <?php  
                if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

                  $palabra = $_POST['palabra'];
                
                  echo obtenerPalabraBuscada( $palabra, $datosEjecutivo2['nom_eje'].' '.$datosEjecutivo2['app_eje'] );
        
                } else {

                  echo $datosEjecutivo2['nom_eje'].' '.$datosEjecutivo2['app_eje'];
              
                }
              ?>


          </span>

          <?php  
            $validacion = 'Ejecutivo';
            obtener_botones_accion_ejecutivo( $id_eje, $datosEjecutivo2, $validacion );
          ?>
          

          <span style="position: absolute; top: 10px; right: 20px;" class="letraPequena grey-text">
            <?php echo $datosEjecutivo2['ran_eje']; ?>

          </span>

            <?php  
              $foto = $datosEjecutivo2['fot_emp'];
            ?>


            <img src="<?php echo ( ( file_exists( '../../uploads/'.$foto ) != 1 ) || ( $foto == NULL ) )? '../img/usuario2.jpg' : '../uploads/'.$foto ?>">
          
          </div>


          <?php  
            // $datosIndicadores = obtener_indicadores_ejecutivo_server( $id_eje, $inicio, $fin );
          ?>

          <!-- <br>
          <span class="badge badge-pill badge-info letraPequena font-weight-normal">
            Citas: <?php echo $datosIndicadores['citasTotales']; ?>
          </span>


          <span class="badge badge-pill badge-info letraPequena font-weight-normal">
            Citas exitosas: <?php echo $datosIndicadores['citasExitosas']; ?>
          </span>


          <span class="badge badge-pill badge-info letraPequena font-weight-normal">
            Registros: <?php echo $datosIndicadores['alumnosTotales']; ?>
          </span>


          <span class="badge badge-pill badge-info letraPequena font-weight-normal">
            Activos: <?php echo $datosIndicadores['alumnosActivos']; ?>
          </span> -->


          
          <div class="list-group nested-sortable">

            <?php  
              if ( $totalNodos > 0 ) {
                obtener_nodo_server( $id_eje, $inicio, $fin );
              }
            ?>
          </div>

      </div>


  <?php
    // FUNCION
    }
  }


  function obtener_nodo_tabla_server( $id_eje, $inicio, $fin, &$contador ){
  require('../../includes/conexion.php');

    $id_res = $id_eje;
    $contador++;

    // FUNCION
    $sqlNodos = "
      SELECT *
      FROM relacion
      INNER JOIN ejecutivo ON ejecutivo.id_eje = relacion.voy_rel
      WHERE ( voy_rel = '$id_eje' ) AND ( eli_eje = 'Activo' )
    ";

    $resultadoNodos = mysqli_query( $db, $sqlNodos );
    $resultadoTotalNodos = mysqli_query( $db, $sqlNodos );

    $totalNodos = mysqli_num_rows( $resultadoTotalNodos );

    while( $filaNodos = mysqli_fetch_assoc( $resultadoNodos ) ){
      
      $id_eje = $filaNodos['soy_rel'];
      $datosEjecutivo2 = obtenerDatosEjecutivoSimpleServer( $id_eje );
      // $datosSrc = obtener_datos_scr_server( $id_eje );
      $datosResponsable = obtenerDatosEjecutivoSimpleServer( $id_res );

      $datosEjecutivo = obtenerDatosEjecutivoServer( $id_res, $inicio, $fin );
  ?>
      

      <tr class="nodoEstructura <?php echo obtenerColorRango( $datosEjecutivo2['ran_eje'] ); ?>" rango="<?php echo $datosEjecutivo2['ran_eje']; ?>" contador="<?php echo $contador; ?>">

        <td>
          <?php echo $contador; ?>   
        </td>

        <td>
          <?php echo $datosResponsable['nom_eje'].' '.$datosResponsable['app_eje']; ?>
        </td>

        <td>
          <?php echo $datosEjecutivo2['nom_eje'].' '.$datosEjecutivo2['app_eje']; ?>
          
          
        </td>


        <td>

          <?php echo $datosEjecutivo2['ran_eje']; ?>
          
        </td>

        <td>
          <?php echo fechaFormateadaCompacta2( $datosEjecutivo2['ing_eje'] ); ?>
        </td>


        <td>
            <?php
            $id_ejecutivo = $datosEjecutivo2['id_eje']; 
            $sql_presentados = "Select obtener_total_registros($id_ejecutivo, '$inicio', '$fin') AS presentados";
            //echo $sql_presentados;
            $resultado_presentacion=mysqli_query($db, $sql_presentados);
            $presentacion=mysqli_fetch_assoc($resultado_presentacion);
            echo $presentacion['presentados'];
             ?>
          </td>


          <td>
            <?php
            $id_ejecutivo = $datosEjecutivo2['id_eje']; 
            $sql_presentados = "Select obtener_total_iniciados($id_ejecutivo, '$inicio', '$fin') AS presentados";
            //echo $sql_presentados;
            $resultado_presentacion=mysqli_query($db, $sql_presentados);
            $presentacion=mysqli_fetch_assoc($resultado_presentacion);
            echo $presentacion['presentados'];
             ?>
          </td>


          <td>
            <?php
            $id_ejecutivo = $datosEjecutivo2['id_eje']; 
            $sql_presentados = "Select obtener_total_presentados($id_ejecutivo, '$inicio', '$fin') AS presentados";
            $resultado_presentacion=mysqli_query($db, $sql_presentados);
            $presentacion=mysqli_fetch_assoc($resultado_presentacion);
            echo $presentacion['presentados'];
             ?>
          </td>


          <td>
            <?php
            $id_ejecutivo = $datosEjecutivo2['id_eje']; 
            $sql_presentados = "Select obtener_porcentaje_presentacion($id_ejecutivo, '$inicio', '$fin') AS presentados";
            $resultado_presentacion=mysqli_query($db, $sql_presentados);
            $presentacion=mysqli_fetch_assoc($resultado_presentacion);
            echo $presentacion['presentados'];
             ?>
          </td>


          <td>
            <?php
            $id_ejecutivo = $datosEjecutivo2['id_eje']; 
            $sql_presentados = "Select obtener_total_activos($id_ejecutivo, '$inicio', '$fin') AS presentados";
            //echo $sql_presentados;
            $resultado_presentacion=mysqli_query($db, $sql_presentados);
            $presentacion=mysqli_fetch_assoc($resultado_presentacion);
            echo $presentacion['presentados'];
             ?>
          </td>

          <td>
            <?php
            $id_ejecutivo = $datosEjecutivo2['id_eje']; 
            $sql_presentados = "Select obtener_porcentaje_revolvente($id_ejecutivo) AS presentados";
            //echo $sql_presentados;
            $resultado_presentacion=mysqli_query($db, $sql_presentados);
            $presentacion=mysqli_fetch_assoc($resultado_presentacion);
            echo $presentacion['presentados'];
             ?>
          </td>

      </tr>


      <?php  
        if ( $totalNodos > 0 ) {


          obtener_nodo_tabla_server( $id_eje, $inicio, $fin, $contador );
          
        }

      ?>


  <?php
    
    }

  return $contador;    
  // FUNCION
  }

?>
<?php

  function obtenerPalabraBuscada( $palabra_buscada, $palabra_comparada ){

    if ( stripos($palabra_comparada, $palabra_buscada) !== false ) {
              //echo 'hay coincidencia';
        $first_pos = stripos($palabra_comparada, $palabra_buscada);
        $last_pos = strlen ($palabra_buscada) + $first_pos - 1;
        $longitudCadena = strlen($palabra_comparada);

        if ( $first_pos == 0 ) {

          return "<span class='bg-info white-text seleccionNombre waves-effect'>".substr($palabra_comparada, $first_pos, $last_pos+1)."</span>".substr($palabra_comparada, $last_pos+1, $longitudCadena);
          

      
        }else {
      
          return substr($palabra_comparada, 0, $first_pos)."<span class='bg-info white-text seleccionNombre waves-effect'>".substr($palabra_comparada, $first_pos, $last_pos-$first_pos+1)."</span>".substr($palabra_comparada, $last_pos+1, $longitudCadena-$last_pos);
      
        }
        

    } else {

      return '<span class="seleccionNombre waves-effect">'.$palabra_comparada.'</span>';
          
    }

  }

  function getMonthLower($mes){
    return strtolower( getMonth($mes) );
  }

  function obtenerBadgeEstatusEjecutivo( $estatus ){
    if ( ( $estatus == 'Activo' ) || ( $estatus == 'Reingreso' ) ) {
      return '<div class="badge badge-success font-weight-normal">'.$estatus.'</div>';
    } else if ( ( $estatus == 'Fin curso' ) || ( $estatus == 'Graduado' ) || ( $estatus == 'Certificado' ) || ( $estatus == 'Anticipado' ) ) {
      return '<div class="badge badge-info font-weight-normal">'.$estatus.'</div>';
    
    } else if ( $estatus == 'Apartado' ) {
      
      return '<div class="badge badge-warning font-weight-normal">'.$estatus.'</div>';

    } else {
      return '<div class="badge badge-danger font-weight-normal">'.$estatus.'</div>';
    }
  }


  function obtenerBadgeEstatusEjecutivoPosicion( $estatus ){
    if ( ( $estatus == 'Activo' ) || ( $estatus == 'Reingreso' ) ) {
      return '<div class="badge badge-success font-weight-normal" style="position: absolute; bottom: -25px; left: 5px; ">'.$estatus.'</div>';
    } else if ( ( $estatus == 'Fin curso' ) || ( $estatus == 'Graduado' ) || ( $estatus == 'Certificado' ) || ( $estatus == 'Anticipado' ) ) {
      return '<div style="position: absolute; bottom: -25px; left: 5px; " class="badge badge-info font-weight-normal">'.$estatus.'</div>';
    
    } else if ( $estatus == 'Apartado' ) {
      
      return '<div style="position: absolute; bottom: -25px; left: 5px; " class="badge badge-warning font-weight-normal">'.$estatus.'</div>';

    } else {
      return '<div style="position: absolute; bottom: -25px; left: 5px; " class="badge badge-danger font-weight-normal">'.$estatus.'</div>';
    }
  }
  

  function obtenerSemanasEjecutivo( $semanas ){
    
    for( $i = sizeof( $semanas )-1; $i >= 0; $i-- ){
      echo '"'.$semanas[$i].'",';
    }

  }


  function obtenerRegistrosEjecutivo( $registros ){
    
    for( $i = sizeof( $registros )-1; $i >= 0; $i-- ){
      echo $registros[$i].',';
    }

  }


  function obtenerActivosEjecutivo( $activos ){
    
    for( $i = sizeof( $activos )-1; $i >= 0; $i-- ){
      echo $activos[$i].',';
    }

  }



  function obtenerDatosEjecutivoSimpleServer( $id_eje ){
    require('../../includes/conexion.php');
    
    $sql = "
      SELECT *
      FROM ejecutivo
      INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
      WHERE id_eje = '$id_eje'
    ";

    $resultado = mysqli_query( $db, $sql );

    $datos = mysqli_fetch_assoc( $resultado );

    return $datos;

  }



  function obtenerIndicadoresEjecutivoServer( $id_eje, $inicio = NULL, $fin = NULL ){
    require('../../includes/conexion.php');

    $datos = array();


    // TOTAL REGISTROS

    if ( $inicio == NULL ) {
      $sql = "
        SELECT *
        FROM vista_alumnos
        WHERE ( id_eje3 = '$id_eje' )
      ";


    } else {

      $sql = "
        SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND ( id_eje3 = '$id_eje' )
      ";
    }

    $resultadoRegistros = mysqli_query( $db, $sql );

    $totalRegistros = mysqli_num_rows( $resultadoRegistros );

    $datos['totalRegistros'] = $totalRegistros;

    if ( $inicio == NULL ) {
      $sql1 = "
        SELECT *
        FROM vista_alumnos
        WHERE ( id_eje3 = '$id_eje' ) AND ( CURDATE() >= ini_gen )
      ";
    } else {

      $sql1 = "
        SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND ( id_eje3 = '$id_eje' ) AND ( CURDATE() >= ini_gen )
      ";
    
    }
    
    // echo $sql1;


    $resultado = mysqli_query( $db, $sql1 );

    $total = mysqli_num_rows( $resultado );
    // echo $total;

    
    if ( $inicio == NULL ) {

      $sql2 = "
        SELECT *
        FROM vista_alumnos
        WHERE ( id_eje3 = '$id_eje' ) AND ( estatus_presentacion = 'Presentado' )
      ";

    } else {

      $sql2 = "
        SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND ( id_eje3 = '$id_eje' ) AND ( estatus_presentacion = 'Presentado' )
      ";
    
    }


    // echo $sql2;

    $resultado3 = mysqli_query( $db, $sql2 );

    $totalActivos = mysqli_num_rows( $resultado3 );

    // echo $totalActivos;
    if ( $total == 0 ) {
      $datos['promedioPresentados'] = 0;
    } else {
      $datos['promedioPresentados'] = round( ( $totalActivos/$total ) * 100, 2 ).'%';
    }
    

    $datos['totalPresentados'] = $totalActivos;
    $datos['totalIniciados'] = $total;


    // ACTIVOS
    if ( $inicio == NULL ) {

      $sqlActivos = "
        SELECT *
        FROM vista_alumnos
        WHERE ( id_eje3 = '$id_eje' ) AND ( estatus_general = 'Activo' )
      ";

    } else {

      $sqlActivos = "
        SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND ( id_eje3 = '$id_eje' ) AND ( estatus_general = 'Activo' )
      ";
    
    }

    $resultadoActivos = mysqli_query( $db, $sqlActivos );

    $totalActivos = mysqli_num_rows( $resultadoActivos );

    $datos['totalActivos'] = $totalActivos;
    // FIN ACTIVOS

    return $datos;
  }

  function obtenerDatosEjecutivoServer( $id_eje, $inicio = NULL, $fin = NULL ){
    require('../../includes/conexion.php');
    
    $sql = "
      SELECT *
      FROM ejecutivo
      INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
      WHERE id_eje = '$id_eje'
    ";

    $resultado = mysqli_query( $db, $sql );

    $datos = mysqli_fetch_assoc( $resultado );

    
    


    // RELACION

    $sqlRelacion = "
      SELECT *
      FROM relacion
      WHERE soy_rel = '$id_eje'
    ";

    $resultadoTotalRelacion = mysqli_query( $db, $sqlRelacion );

    $totalRelacion = mysqli_num_rows( $resultadoTotalRelacion );

    if ( $totalRelacion > 0 ) {
      $resultadoRelacion = mysqli_query( $db, $sqlRelacion );

      $filaRelacion = mysqli_fetch_assoc( $resultadoRelacion );
      // $jefe = $fila_relacion['voy_rel'];
      // $sql_superior = "
      //           SELECT *
      //           FROM relacion
      //           WHERE soy_rel = '$id_eje'
      //         ";
      // $res_jefe = mysqli_query($db, $sql_superior);
      // $$filaRelacion = mysqli_fetch_assoc($res_jefe);

      $datos['superior'] = obtenerDatosEjecutivoServer( $filaRelacion['voy_rel'] );

    } else {
      
      $datos['superior'] = 'N/A';
    
    }

    // FIN RELACION

    return $datos;

  }


  function obtenerDatosEjecutivo( $id_eje, $inicio = NULL, $fin = NULL ){
    require('../includes/conexion.php');
    
    $sql = "
      SELECT *
      FROM ejecutivo
      INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
      WHERE id_eje = '$id_eje'
    ";






    // RELACION

    $sqlRelacion = "
      SELECT *
      FROM relacion
      WHERE soy_rel = '$id_eje'
    ";

    $resultadoTotalRelacion = mysqli_query( $db, $sqlRelacion );

    $totalRelacion = mysqli_num_rows( $resultadoTotalRelacion );

    if ( $totalRelacion > 0 ) {
      $resultadoRelacion = mysqli_query( $db, $sqlRelacion );

      $filaRelacion = mysqli_fetch_assoc( $resultadoRelacion );
      // $jefe = $fila_relacion['voy_rel'];
      // $sql_superior = "
      //           SELECT *
      //           FROM relacion
      //           WHERE soy_rel = '$id_eje'
      //         ";
      // $res_jefe = mysqli_query($db, $sql_superior);
      // $$filaRelacion = mysqli_fetch_assoc($res_jefe);

      $datos['superior'] = obtenerDatosEjecutivo( $filaRelacion['voy_rel'] );

    } else {
      
      $datos['superior'] = 'N/A';
    
    }

    // FIN RELACION

    return $datos;

  }


  function obtener_fechas_semana_pasadas( $id_eje ){
  
    $fechaHoy = date('Y-m-d');
    
    $inicio = '';
    $fin = '';
    $datos = array();

    $datos['inicio'] = '';
    $datos['fin'] = '';
    $datos ['condicion']='';
 
    $i = 0;
      
            //echo 'if';
    $dias = date("N"); //lunes_proximo = 1
    $fin = date('Y-m-d', strtotime( $fechaHoy. " - $dias days")); //lunes 2021/05/03      
    $inicio = date('Y-m-d', strtotime( $fin. " - 6 days"));

    $datos['inicio'] = $inicio;
    $datos['fin'] = $fin;

    return $datos;

  }


  function obtener_fechas_scr( $id_eje ){
  
    $fechaHoy = date('Y-m-d');
    
    $inicio = '';
    $fin = '';
    $periodo = 6;
    $periodicidad = $periodo+1;
    $lunes = date("j");
    $datos = array();

    $datos['inicio'] = '';
    $datos['fin'] = '';
    $datos ['condicion']='';
 
    $i = 0;

      if ( $i == 0 ) {

          if ( $lunes != 6 ) {
            //echo 'if';
            $datos['condicion']='Primera condicion';
            $domingo_proximo =  $fechaHoy;
            $lunes_proximo = date("N"); //lunes_proximo = 2
            $lunes_proximo = $lunes_proximo-1; //2-1=1
            $fin = date('Y-m-d', strtotime($fechaHoy));
            $inicio = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

          } else{
            //echo 'else';
            if ( $lunes == 6 ) {
                $datos['condicion']='Segunda condicion';
                $domingo_proximo =  $fechaHoy;
                $lunes_proximo = date("N");
                $lunes_proximo = $lunes_proximo-1;
                $inicio = date('Y-m-d', strtotime($fechaHoy));
                $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
            
            } else {

                $datos['condicion']='tercera condicion';
                $domingo_proximo = date("N"); //domingo = 7
                $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
                $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
                $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

            }

          }
      

      } else {

 
          $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
          $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
          

      }

      $datos['inicio'] = $inicio;
      $datos['fin'] = $fin;

      return $datos;

  }

  function obtener_datos_scr_semanal( $id_eje, $inicio, $fin ){

    require('../includes/conexion.php');
    
    $fechaHoy = date('Y-m-d');
    
    $datos = array();
    $datos['comision'] = '';
    $datos['estatus'] = '';
    $datos['semanas'] = '';
    $datos['alumnosTotales'] = 0;
    $datos['alumnosActivos'] = 0;
    $datos['alumnosAuxTotales'] = 0;
    $datos['porcentaje'] = 0;


    $datos['estatus'] = 'N/A';
    $datos['comision'] = 'N/A';

 
    $i = 0;
    
    $sql = "
      SELECT *
      FROM vista_alumnos
      WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' )
    ";
    // echo $sql;


    $resultado = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultado );
    // echo $total;

    $datos['alumnosTotales'] = $datos['alumnosTotales'] + $total;


    $sql2 = "
      SELECT *
      FROM vista_alumnos
      WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( estatus_general = 'Activo' OR estatus_general = 'Reingreso' ) AND ( id_eje3 = '$id_eje' )
    ";

    $resultado2 = mysqli_query( $db, $sql2 );

    $resultado3 = mysqli_query( $db, $sql2 );

    $totalActivos = mysqli_num_rows( $resultado3 );



    $datos['alumnosActivos'] = $datos['alumnosActivos'] + $totalActivos;


    $sqlTotalAux = "
      SELECT *
      FROM vista_alumnos
      WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' ) AND ( CURDATE() >= ini_gen )
    ";


    // echo $sqlTotalAux;

    $resultadoTotalAux = mysqli_query( $db, $sqlTotalAux );

    $totalAux = mysqli_num_rows( $resultadoTotalAux );


    if ( ( $totalAux == '' ) || ( $totalAux == 0 ) ) {
      $totalAux = 0;
    }
    $datos['alumnosAuxTotales'] = $datos['alumnosAuxTotales'] + $totalAux;

    if ( $total >= 4 ) {
    // VALIDADOR SI CUMPLE SCR


      


      $bool = false;

      $resultadoBool = mysqli_query( $db, $sqlTotalAux );

      while( $filaBool = mysqli_fetch_assoc( $resultadoBool ) ){

        if ( $filaBool['ini_gen'] >= $fechaHoy ) {
          
          $bool = true;  
        
        } else {

          $bool = false;

          break;
          break;
        
        } 

      }

      
      
      if ( ( $total > $totalAux ) && ( $totalAux > 0 ) && ( $bool == true ) ) {
      //VALIDACION SI CUMPLE REGISTROS CON INICIO ARRANCADO
        // echo "semana parcial<br>";
        $porcentaje = ( $totalActivos / $totalAux ) * 100;
        
        
        if ( $porcentaje >= 65 ) {
          
          if ( $i == 0 ) {

            $datos['estatus'] = 'Consultor';  
            // echo $totalAux."hi";    
          }

          while( $fila = mysqli_fetch_assoc( $resultado2 ) ){

            $datos['comision'] = $datos['comision'] + $fila['com_ram'];
            $datos['semanas'] = ( $i + 1 );

          
          }

        } else {

          if ( $i == 0 ) {

            $datos['estatus'] = 'N/A';  
          
          }
        
        }
        

      } else {
      //VALIDACION SI NO CUMPLE REGISTROS CON INICIO ARRANCADO
        // echo "semana real<br>";

        
        $porcentaje = ( $totalActivos / $totalAux ) * 100;
        
        // echo $datos['alumnosAuxTotales'];
        if ( $porcentaje >= 65 ) {


          if ( $i == 0 ) {

            $datos['estatus'] = 'Consultor';  
          
          }
          
          while( $fila = mysqli_fetch_assoc( $resultado2 ) ){

            $datos['comision'] = $datos['comision'] + $fila['com_ram'];
            $datos['semanas'] = ( $i + 1 );

          }

        } else {

          if ( $i == 0 ) {

            if ( $totalAux > 0 ) {
              
              $datos['estatus'] = 'N/A';  
            
            } else {

              $datos['estatus'] = 'Consultor';
              
            }

          }

        }
       
      }
    
    }

    $i++;      

    
    $datos['comisionNumerica'] = $datos['comision'];
    
    if ( $datos['comision'] > 0 ) {
    
      $datos['comision'] = '$'.$datos['comision'];
    
    } else {
      $datos['comision'] = 'N/A';
    }

    if ( $datos['alumnosAuxTotales'] > 0 ) {
    
      $datos['porcentaje'] = round( ( $datos['alumnosActivos']/$datos['alumnosAuxTotales'] ) * 100, 2 );
    
    } else {
    
      $datos['porcentaje'] = 0;
    
    }
    

    return $datos;
    


  }

  function obtener_datos_scr_server( $id_eje ){

    require('../../includes/conexion.php');
    
    $fechaHoy = date('Y-m-d');
    
    $inicio = '';
    $fin = '';
    $periodo = 6;
    $periodicidad = $periodo+1;
    $lunes = date("j");
    $datos = array();
    $datos['comision'] = '';
    $datos['estatus'] = '';
    $datos['semanas'] = '';
    $datos['alumnosTotales'] = 0;
    $datos['alumnosActivos'] = 0;
    $datos['alumnosAuxTotales'] = 0;
    $datos['porcentaje'] = 0;


    $datos['estatus'] = 'N/A';
    $datos['comision'] = 'N/A';
 
    $i = 0;
    
    do{

      if ( $i == 0 ) {

          if ( $lunes != 6 ) {
            //echo 'if';
            $domingo_proximo =  $fechaHoy;
            $lunes_proximo = date("N");
            $lunes_proximo = $lunes_proximo-1;
            $inicio = date('Y-m-d', strtotime($fechaHoy));
            $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

          } else{
            //echo 'else';
            if ( $lunes == 6 ) {
                $domingo_proximo =  $fechaHoy;
                $lunes_proximo = date("N");
                $lunes_proximo = $lunes_proximo-1;
                $inicio = date('Y-m-d', strtotime($fechaHoy));
                $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
            
            } else {


                $domingo_proximo = date("N"); //domingo = 7
                $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
                $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
                $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

            }

          }
      

      } else {

 
          $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
          $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
          

      }

      

      $sql = "
        SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' )
      ";
      // echo $sql;


      $resultado = mysqli_query( $db, $sql );

      $total = mysqli_num_rows( $resultado );
      // echo $total;

      $datos['alumnosTotales'] = $datos['alumnosTotales'] + $total;


      $sql2 = "
        SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( estatus_general = 'Activo' OR estatus_general = 'Reingreso' ) AND ( id_eje3 = '$id_eje' )
      ";

      $resultado2 = mysqli_query( $db, $sql2 );

      $resultado3 = mysqli_query( $db, $sql2 );

      $totalActivos = mysqli_num_rows( $resultado3 );



      $datos['alumnosActivos'] = $datos['alumnosActivos'] + $totalActivos;


      $sqlTotalAux = "
          SELECT *
        FROM vista_alumnos
        WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' ) AND ( CURDATE() >= ini_gen )
      ";

      $resultadoTotalAux = mysqli_query( $db, $sqlTotalAux );

      $totalAux = mysqli_num_rows( $resultadoTotalAux );


      if ( ( $totalAux == '' ) || ( $totalAux == 0 ) ) {
        $totalAux = 0;
      }
      $datos['alumnosAuxTotales'] = $datos['alumnosAuxTotales'] + $totalAux;

      if ( $total >= 4 ) {
      // VALIDADOR SI CUMPLE SCR

        
        


        $bool = false;

        $resultadoBool = mysqli_query( $db, $sqlTotalAux );

        while( $filaBool = mysqli_fetch_assoc( $resultadoBool ) ){

          if ( $filaBool['ini_gen'] >= $fechaHoy ) {
            
            $bool = true;  
          
          } else {

            $bool = false;

            break;
            break;
          
          } 

        }

        
        
        if ( ( $total > $totalAux ) && ( $totalAux > 0 ) && ( $bool == true ) ) {
        //VALIDACION SI CUMPLE REGISTROS CON INICIO ARRANCADO
          // echo "semana parcial<br>";
          $porcentaje = ( $totalActivos / $totalAux ) * 100;
    
          if ( $porcentaje >= 65 ) {
            
            if ( $i == 0 ) {

              $datos['estatus'] = 'Consultor';  
              // echo $totalAux."hi";    
            }

            while( $fila = mysqli_fetch_assoc( $resultado2 ) ){

              $datos['comision'] = $datos['comision'] + $fila['com_ram'];
              $datos['semanas'] = ( $i + 1 );

            
            }

          } else {

            if ( $i == 0 ) {

              $datos['estatus'] = 'N/A';  
            
            }
          
          }
          

        } else {
        //VALIDACION SI NO CUMPLE REGISTROS CON INICIO ARRANCADO
          // echo "semana real<br>";
          
          $porcentaje = ( $totalActivos / $totalAux ) * 100;
          
          // echo $datos['alumnosAuxTotales'];
          if ( $porcentaje >= 65 ) {


            if ( $i == 0 ) {

              $datos['estatus'] = 'Consultor';  
            
            }
            
            while( $fila = mysqli_fetch_assoc( $resultado2 ) ){

              $datos['comision'] = $datos['comision'] + $fila['com_ram'];
              $datos['semanas'] = ( $i + 1 );

            }

          } else {

            if ( $i == 0 ) {

              if ( $totalAux > 0 ) {
                
                $datos['estatus'] = 'N/A';  
              
              } else {

                $datos['estatus'] = 'Consultor';
                
              }

            }

          }
         
        }
      
      }

      $i++;      

    }while( ( $total >= 4 ) );
    
    $datos['comisionNumerica'] = $datos['comision'];
    
    if ( $datos['comision'] > 0 ) {
    
      $datos['comision'] = '$'.$datos['comision'];
    
    } else {
      $datos['comision'] = 'N/A';
    }

    if ( $datos['alumnosAuxTotales'] > 0 ) {
    
      $datos['porcentaje'] = round( ( $datos['alumnosActivos']/$datos['alumnosAuxTotales'] ) * 100, 2 );
    
    } else {
    
      $datos['porcentaje'] = 0;
    
    }
    

    return $datos;
  }

  // function obtener_datos_scr( $id_eje ){
  //   require('../includes/conexion.php');
  
  //   $fechaHoy = date('Y-m-d');
    
  //   $inicio = '';
  //   $fin = '';
  //   $periodo = 6;
  //   $periodicidad = $periodo+1;
  //   $lunes = date("j");
  //   $datos = array();
  //   $datos['comision'] = '';
  //   $datos['estatus'] = '';
  //   $datos['semanas'] = '';
  //   $datos['alumnosTotales'] = 0;
  //   $datos['alumnosActivos'] = 0;
  //   $datos['alumnosAuxTotales'] = 0;
  //   $datos['porcentaje'] = 0;


  //   $datos['estatus'] = 'N/A';
  //   $datos['comision'] = 'N/A';
 
  //   $i = 0;
    
  //   do{

  //     if ( $i == 0 ) {

  //         if ( $lunes != 6 ) {
  //           //echo 'if';
  //           $domingo_proximo =  $fechaHoy;
  //           $lunes_proximo = date("N");
  //           $lunes_proximo = $lunes_proximo-1;
  //           $inicio = date('Y-m-d', strtotime($fechaHoy));
  //           $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

  //         } else{
  //           //echo 'else';
  //           if ( $lunes == 6 ) {
  //               $domingo_proximo =  $fechaHoy;
  //               $lunes_proximo = date("N");
  //               $lunes_proximo = $lunes_proximo-1;
  //               $inicio = date('Y-m-d', strtotime($fechaHoy));
  //               $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
            
  //           } else {


  //               $domingo_proximo = date("N"); //domingo = 7
  //               $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
  //               $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
  //               $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

  //           }

  //         }
      

  //     } else {

 
  //         $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
  //         $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
          

  //     }

      

  //     $sql = "
  //       SELECT *
  //       FROM vista_alumnos
  //       WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' )
  //     ";
  //     // echo $sql;


  //     $resultado = mysqli_query( $db, $sql );

  //     $total = mysqli_num_rows( $resultado );
  //     // echo $total;

  //     $datos['alumnosTotales'] = $datos['alumnosTotales'] + $total;


  //     $sql2 = "
  //       SELECT *
  //       FROM vista_alumnos
  //       WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( estatus_general = 'Activo' OR estatus_general = 'Reingreso' ) AND ( id_eje3 = '$id_eje' )
  //     ";

  //     $resultado2 = mysqli_query( $db, $sql2 );

  //     $resultado3 = mysqli_query( $db, $sql2 );

  //     $totalActivos = mysqli_num_rows( $resultado3 );



  //     $datos['alumnosActivos'] = $datos['alumnosActivos'] + $totalActivos;


  //     $sqlTotalAux = "
  //         SELECT *
  //       FROM vista_alumnos
  //       WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' ) AND ( CURDATE() >= ini_gen )
  //     ";

  //     $resultadoTotalAux = mysqli_query( $db, $sqlTotalAux );

  //     $totalAux = mysqli_num_rows( $resultadoTotalAux );


  //     if ( ( $totalAux == '' ) || ( $totalAux == 0 ) ) {
  //       $totalAux = 0;
  //     }
  //     $datos['alumnosAuxTotales'] = $datos['alumnosAuxTotales'] + $totalAux;

  //     if ( $total >= 4 ) {
  //     // VALIDADOR SI CUMPLE SCR

        
        


  //       $bool = false;

  //       $resultadoBool = mysqli_query( $db, $sqlTotalAux );

  //       while( $filaBool = mysqli_fetch_assoc( $resultadoBool ) ){

  //         if ( $filaBool['ini_gen'] >= $fechaHoy ) {
            
  //           $bool = true;  
          
  //         } else {

  //           $bool = false;

  //           break;
  //           break;
          
  //         } 

  //       }

        
        
  //       if ( ( $total > $totalAux ) && ( $totalAux > 0 ) && ( $bool == true ) ) {
  //       //VALIDACION SI CUMPLE REGISTROS CON INICIO ARRANCADO
  //         // echo "semana parcial<br>";
  //         $porcentaje = ( $totalActivos / $totalAux ) * 100;
    
  //         if ( $porcentaje >= 65 ) {
            
  //           if ( $i == 0 ) {

  //             $datos['estatus'] = 'Consultor';  
  //             // echo $totalAux."hi";    
  //           }

  //           while( $fila = mysqli_fetch_assoc( $resultado2 ) ){

  //             $datos['comision'] = $datos['comision'] + $fila['com_ram'];
  //             $datos['semanas'] = ( $i + 1 );

            
  //           }

  //         } else {

  //           if ( $i == 0 ) {

  //             $datos['estatus'] = 'N/A';  
            
  //           }
          
  //         }
          

  //       } else {
  //       //VALIDACION SI NO CUMPLE REGISTROS CON INICIO ARRANCADO
  //         // echo "semana real<br>";
          
  //         $porcentaje = ( $totalActivos / $totalAux ) * 100;
          
  //         // echo $datos['alumnosAuxTotales'];
  //         if ( $porcentaje >= 65 ) {


  //           if ( $i == 0 ) {

  //             $datos['estatus'] = 'Consultor';  
            
  //           }
            
  //           while( $fila = mysqli_fetch_assoc( $resultado2 ) ){

  //             $datos['comision'] = $datos['comision'] + $fila['com_ram'];
  //             $datos['semanas'] = ( $i + 1 );

  //           }

  //         } else {

  //           if ( $i == 0 ) {

  //             if ( $totalAux > 0 ) {
                
  //               $datos['estatus'] = 'N/A';  
              
  //             } else {

  //               $datos['estatus'] = 'Consultor';
                
  //             }

  //           }

  //         }
         
  //       }
      
  //     }

  //     $i++;      

  //   }while( ( $total >= 4 ) );
    
  //   $datos['comisionNumerica'] = $datos['comision'];
    
  //   if ( $datos['comision'] > 0 ) {
    
  //     $datos['comision'] = '$'.$datos['comision'];
    
  //   } else {
  //     $datos['comision'] = 'N/A';
  //   }

  //   if ( $datos['alumnosAuxTotales'] > 0 ) {
    
  //     $datos['porcentaje'] = round( ( $datos['alumnosActivos']/$datos['alumnosAuxTotales'] ) * 100, 2 );
    
  //   } else {
    
  //     $datos['porcentaje'] = 0;
    
  //   }
    

  //   return $datos;


  // }



  function obtener_generacion_alumno_server( $id_alu ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM alu_ram
      INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
      WHERE id_alu1 = '$id_alu'
    ";

    // echo $sql;

    $resultado = mysqli_query( $db, $sql );

    while( $fila = mysqli_fetch_assoc( $resultado ) ){
      
      $cadena = ' <div class="badge badge-primary badge-pill small">'.$fila['nom_gen'].'</div>';
    
    }

    return $cadena;

  }
  
  function obtenerTipoUsuarioCompleto( $tipo ){
    
    if ( $tipo == 'Admin' ) {
      
      return '<span class="badge-pill badge-dark font-weight-normal letraPequena"><i class="fas fa-glasses"></i> Administrador</span>';

    } else if ( $tipo == 'Adminge' ) {
      
      return '<span class="badge-pill badge-dark font-weight-normal letraPequena">Gestor Escolar</span>';

    } else if ( $tipo == 'Profesor' ) {

      return '<span class="badge-pill badge-primary font-weight-normal letraPequena">Profesor</span>';
      
    } else if ( $tipo == 'Ejecutivo' ) {
      
      return '<span class="badge-pill badge-primary font-weight-normal letraPequena">Ejecutivo</span>';
    
    } else if ( $tipo == 'Alumno' ) {
      
      return '<span class="badge-pill badge-info font-weight-normal letraPequena">Alumno</span>';

    }
  }

  function recortarTexto( $texto ){

    if ( strlen( $texto ) < 15 ) {
      
      return $texto;
    
    } else {

      return substr( $texto, 0, 15 ).'...';
    
    }
  
  }
  
  function obtenerValidacionFotoUsuarioServer( $foto ){

    if( ( $foto == NULL ) ){ 
    
      return '../../img/usuario2.jpg';
    
    } else if( ( file_exists( '../../uploads/'.$foto ) != 1 ) ){ 
      
      return '../../img/usuario2.jpg'; 
      
    } else {
      
      return '../uploads/'.$foto; 
    
    }
  
  }

  function obtenerValidacionFotoUsuario( $foto ){

    if( ( $foto == NULL ) ){ 
    
      return '../img/usuario2.jpg'; 
    
    } else if( ( file_exists( '../uploads/'.$foto ) != 1 ) ){ 
      
      return '../img/usuario2.jpg'; 
      
    } else {
      
      return '../uploads/'.$foto;
    
    }
  
  }
  
  function obtenerTotalEstatusGeneralGeneracionServer( $plantel, $id_gen, $tipo_estatus, $estatus ){

    require('../../includes/conexion.php');

    $sql = "
      
      SELECT * 
      FROM vista_alumnos
      WHERE id_pla8 = '$plantel' AND id_gen1 = '$id_gen' AND $tipo_estatus = '$estatus'
    
    ";

    $resultado = mysqli_query( $db, $sql );

    if ( !$resultado ) {
    
      echo $sql;
    
    } else {

      $total = mysqli_num_rows( $resultado );

      return $total;
    
    }
  }

  function obtenerSumaEstatusPagosGeneracionServer( $plantel, $id_gen, $tipo_estatus ){
    require('../../includes/conexion.php');

    $sql = "

        SELECT SUM( $tipo_estatus ) AS $tipo_estatus
        FROM vista_alumnos
        WHERE ( id_pla8 = '$plantel' AND id_gen1 = '$id_gen' )

    ";

    
    $resultado = mysqli_query( $db, $sql );

    if ( !$resultado ) {
    
      echo $sql;
    
    } else {

      $fila = mysqli_fetch_assoc( $resultado );

      return $fila[$tipo_estatus];
    
    }


    


  }



  function obtenerDiferenciaFechas( $fecha1, $fecha2 ){

    $inicioEntero = strtotime( $fecha1 ) - strtotime( $fecha2 );

    $inicio = round( $inicioEntero / ( 60 * 60 * 24) );

      return $inicio;

  }


  function obtenerDiferenciaFechasSemanas( $fecha1, $fecha2 ){

    $inicioEntero = strtotime( $fecha1 ) - strtotime( $fecha2 );

    $inicio = round( ( $inicioEntero / ( 60 * 60 * 24) ) / 7 );

      return $inicio;

  }



  function obtenerHoraFormateadaMensajeria( $fecha ){


    $fechaAux = date("Y-m-d", strtotime($fecha));
    $dia = date("d", strtotime($fecha));
    $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
    $annio = date("Y", strtotime($fecha));



    $hora = date("h:i A", strtotime($fecha));

    // echo "if ( ".$fechaAux." == ".date('Y-m-d')." )";  
    if ( $fechaAux == date('Y-m-d') ) {

      return '<span style="position: absolute; right: -10px;" class="letraPequena">'.$hora.'</span>';
    
    } else if( $fechaAux == gmdate( 'Y-m-d', strtotime ( '- 1 day' , strtotime ( date( 'Y-m-d' ) ) ) ) ){

      return '<span style="position: absolute; right: -10px;" class="letraPequena">Ayer</span>';

    } else {


      return '<span style="position: absolute; right: -10px;" class="letraPequena">'.$dia.'/'.$mes.'/'.$annio.'</span>';
    
    }

    

  }


	function obtenerNombreUsuarioServer( $usr_log, $id_usr_log ){
    require('../../includes/conexion.php');

    if ( $usr_log == 'Admin' ) {
            
            $sql = "
                SELECT *
                FROM admin
                WHERE id_adm = '$id_usr_log'
            ";

            //echo $sql;

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {

              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_adm']." ".$fila['app_adm']." ".$fila['apm_adm'];

              return $usuario;
              
              

            } else {
              
              echo $sql;
            
            }
            
            

        } else if ( $usr_log == 'Adminge' ) {

          $sql = "
                SELECT *
                FROM adminge
                WHERE id_adg = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_adg']." ".$fila['app_adg']." ".$fila['apm_adg'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }
            

        } else if ( $usr_log == 'Adminco' ) {

          $sql = "
                SELECT *
                FROM adminco
                WHERE id_adc = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_adc']." ".$fila['app_adc']." ".$fila['apm_adc'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }
            

        } else if ( $usr_log == 'Cobranza' ) {
            
            $sql = "
                SELECT *
                FROM cobranza
                WHERE id_cob = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_cob']." ".$fila['app_cob']." ".$fila['apm_cob'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }
            

        } else if ( $usr_log == 'Profesor' ) {
            
            $sql = "
                SELECT *
                FROM profesor
                WHERE id_pro = '$id_usr_log'
            ";
            // echo $sql;

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_pro']." ".$fila['app_pro']." ".$fila['apm_pro'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }

        } else if ( $usr_log == 'Ejecutivo' ) {
        
        $sql = "
                SELECT *
                FROM ejecutivo
                WHERE id_eje = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_eje']." ".$fila['app_eje']." ".$fila['apm_eje'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }

        } else if ( $usr_log == 'Alumno' ) {
            
          $sql = "
                SELECT *
                FROM alumno
                WHERE id_alu = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_alu']." ".$fila['app_alu']." ".$fila['apm_alu'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }

        }

  }

  
	function obtenerDatosUsuarioSalaServer( $id_sal, $tipo_usuario, $id_usuario ){
	    require('../../includes/conexion.php');
	    $fechaHoy = date( 'Y-m-d H:i:s' );

	    $datos = array();
	    $datos['id_usuario'] = "";
	    $datos['tipo_usuario'] = "";

	    $sql = "
	      SELECT *
	      FROM sala
	      WHERE id_sal = '$id_sal'
	    ";
	      //echo $sqlSalas;

	    $resultado = mysqli_query($db, $sql);

	    $fila = mysqli_fetch_assoc($resultado);


	    if ( $fila['tip1_sal'] == $tipo_usuario && $fila['use1_sal'] == $id_usuario ) {

	      $datos['tipo_usuario'] = $fila['tip2_sal'];

	      $datos['id_usuario'] = $fila['use2_sal'];    

	    } else if ( $fila['tip2_sal'] == $tipo_usuario && $fila['use2_sal'] == $id_usuario ) {
	      
	      $datos['tipo_usuario'] = $fila['tip1_sal'];

	      $datos['id_usuario'] = $fila['use1_sal'];

	    }

	    return $datos;

	}

	function obtenerTotalNotificacionesMensajesSalaServer( $id_sal, $tipo_usuario, $id_usuario ){

		require('../../includes/conexion.php');

	    $sql = "
	          
	      SELECT *
	      FROM estatus_mensaje
	      INNER JOIN sala ON sala.id_sal = notificacion_mensaje.id_sal5
	      WHERE ( tip_not_men = '$tipo_usuario' ) AND ( est_not_men = 'Pendiente' ) AND ( use_not_men = '$id_usuario' ) AND ( id_sal5 = '$id_sal' )
	      GROUP BY id_sal
	      ORDER BY fec_not_men DESC
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    $total = mysqli_num_rows( $resultado );
	    if ( $total > 0 ) {
	    	return '<span class="notificacionPendiente badge badge-danger notification rounded-circle" style="position: absolute; z-index: 9;">'.$total.'</span>';
	    }
	    

	}


	function obtenerTotalNotificacionesMensajesServer( $tipo_usuario, $id_usuario ){
	    require('../../includes/conexion.php');

	    $sql = "
	          
	      SELECT *
	      FROM notificacion_mensaje
	      INNER JOIN sala ON sala.id_sal = notificacion_mensaje.id_sal5
	      WHERE ( tip_not_men = '$tipo_usuario' ) AND ( est_not_men = 'Pendiente' ) AND ( use_not_men = '$id_usuario' )
	      GROUP BY id_sal
	      ORDER BY fec_not_men DESC
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    $total = mysqli_num_rows( $resultado );

	    return $total;

	  }


	  function obtenerTotalNotificacionesMensajes( $tipo_usuario, $id_usuario ){
	    require('../includes/conexion.php');

	    $sql = "
	          
	      SELECT *
	      FROM notificacion_mensaje
	      INNER JOIN sala ON sala.id_sal = notificacion_mensaje.id_sal5
	      WHERE ( tip_not_men = '$tipo_usuario' ) AND ( est_not_men = 'Pendiente' ) AND ( use_not_men = '$id_usuario' )
	      GROUP BY id_sal
	      ORDER BY fec_not_men DESC
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    $total = mysqli_num_rows( $resultado );

	    return $total;

	  }

	function generarNotificacionMensaje( $id_sal, $tipo_usuario, $id_usuario ){
    	require('../../includes/conexion.php');
        $fechaHoy = date( 'Y-m-d H:i:s' );

      $sqlValidador = "
        SELECT *
        FROM sala
        WHERE id_sal = '$id_sal'
      ";

      $resultadoValidador = mysqli_query( $db, $sqlValidador );

      $filaValidador = mysqli_fetch_assoc( $resultadoValidador );

      if ( $filaValidador['id_sub_hor6'] != NULL ) {
        // MENSAJE GRUPAL

        $id_sub_hor = $filaValidador['id_sub_hor6'];


        if ( $tipo_usuario == 'Profesor' ) {
          // TIPO PROFESOR

          $sqlAlumnos = "
            SELECT *
            FROM alu_hor
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            WHERE id_sub_hor5 = '$id_sub_hor'
            GROUP BY id_alu
          ";

          $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

          while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

            $id_alu = $filaAlumnos['id_alu'];
            
            $sqlNotificacion = "
              INSERT INTO notificacion_mensaje ( fec_not_men, est_not_men, tip_not_men, use_not_men, id_sal5 ) 
              VALUES ( '$fechaHoy', 'Pendiente', 'Alumno', '$id_alu', '$id_sal' )
            ";

            $resultadoNotificacion = mysqli_query( $db, $sqlNotificacion );

            if ( !$resultadoNotificacion ) {
              echo $sqlNotificacion;
            }


          }

          // FIN TIPO PROFESOR
        } else {
          // TIPO ALUMNO

          $sqlAlumnos = "
            SELECT *
            FROM alu_hor
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            WHERE id_sub_hor5 = '$id_sub_hor'
            GROUP BY id_alu
          ";

          $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

          while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

            if ( $id_usuario != $filaAlumnos['id_alu'] ) {
              
              $id_alu = $filaAlumnos['id_alu'];
            
              $sqlNotificacion = "
                INSERT INTO notificacion_mensaje ( fec_not_men, est_not_men, tip_not_men, use_not_men, id_sal5 ) 
                VALUES ( '$fechaHoy', 'Pendiente', 'Alumno', '$id_alu', '$id_sal' )
              ";

              $resultadoNotificacion = mysqli_query( $db, $sqlNotificacion );

              if ( !$resultadoNotificacion ) {
                echo $sqlNotificacion;
              }

            }

          }

          $sqlProfesor = "
            SELECT *
            FROM sub_hor
            WHERE id_sub_hor = '$id_sub_hor'
          ";

          $resultadoProfesor = mysqli_query( $db, $sqlProfesor );

          $filaProfesor = mysqli_fetch_assoc( $resultadoProfesor );

          $id_pro = $filaProfesor['id_pro1'];

          $sqlNotificacion = "
            INSERT INTO notificacion_mensaje ( fec_not_men, est_not_men, tip_not_men, use_not_men, id_sal5 ) 
            VALUES ( '$fechaHoy', 'Pendiente', 'Profesor', '$id_pro', '$id_sal' )
          ";

          $resultadoNotificacion = mysqli_query( $db, $sqlNotificacion );

          if ( !$resultadoNotificacion ) {
            echo $sqlNotificacion;
          }

          // FIN TIPO ALUMNO
        }

        // FIN MENSAJE GRUPAL
      } else {

        
        // MENSAJE PRIVADO
        $sql = "
          SELECT *
          FROM sala
          WHERE id_sal = '$id_sal'
        ";
          //echo $sqlSalas;

        $resultado = mysqli_query($db, $sql);

        $fila = mysqli_fetch_assoc($resultado);


        if ( $fila['tip1_sal'] == $tipo_usuario && $fila['use1_sal'] == $id_usuario ) {

          $tip2_sal = $fila['tip2_sal'];

          $use2_sal = $fila['use2_sal'];

          $sqlNotificacion = "
            INSERT INTO notificacion_mensaje ( fec_not_men, est_not_men, tip_not_men, use_not_men, id_sal5 ) 
            VALUES ( '$fechaHoy', 'Pendiente', '$tip2_sal', '$use2_sal', '$id_sal' )
          ";

          $resultadoNotificacion = mysqli_query( $db, $sqlNotificacion );

          if ( !$resultadoNotificacion ) {
            echo $sqlNotificacion;
          }

        } else if ( $fila['tip2_sal'] == $tipo_usuario && $fila['use2_sal'] == $id_usuario ) {
          
          $tip1_sal = $fila['tip1_sal'];

          $use1_sal = $fila['use1_sal'];

          $sqlNotificacion = "
            INSERT INTO notificacion_mensaje ( fec_not_men, est_not_men, tip_not_men, use_not_men, id_sal5 ) 
            VALUES ( '$fechaHoy', 'Pendiente', '$tip1_sal', '$use1_sal', '$id_sal' )
          ";

          $resultadoNotificacion = mysqli_query( $db, $sqlNotificacion );

          if ( !$resultadoNotificacion ) {
            echo $sqlNotificacion;
          }

        }

          
        // FIN MENSAJE PRIVADO
      }


      
    }


	function obtenerFormatoArchivo( $archivo ){

		$formato = end( explode( ".", $archivo ) );

		return $formato;
	}
	
	
	function obtenerUltimoIdentificadorServer( $tabla, $identificador ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT MAX( $identificador ) AS ultimo 
			FROM $tabla
		";

		$resultado = mysqli_query( $db, $sql );

		$fila = mysqli_fetch_assoc( $resultado );

		return $fila['ultimo'];

	}



  function obtenerUltimoIdentificador( $tabla, $identificador ){
    require('../includes/conexion.php');

    $sql = "
      SELECT MAX( $identificador ) AS ultimo 
      FROM $tabla
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    return $fila['ultimo'];

  }

	
	function formatearDinero( $dinero ){

    if ( $dinero < 0 ) {
      
      $dinero = $dinero*(-1);
      
      return "-$".number_format(  $dinero,  0, '.', ',');
    
    } else {
    
      return "$".number_format(  $dinero,  0, '.', ',');
    
    }
		
	
	}

	function obtenerEstatusDashboardServer( $plantel, $tipo_estatus2, $estatus2 ){
		require('../../includes/conexion.php');

	      if (  
		    ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) OR 
		    ( isset( $_POST['inicio'] ) ) && ( ( $_POST['inicio'] ) != '' ) OR 
		    ( isset( $_POST['fin'] ) ) && ( ( $_POST['fin'] ) != '' ) OR 
		    ( ( isset( $_POST['estatus'] ) ) && ( sizeof( $_POST['estatus'] ) > 0 ) )
		  ) {
		    
		    if ( ( $tipo_estatus2 == 'pagado_alumno' ) ) {
		    
		    	$sqlAlumnos = "

			        SELECT SUM( pagado_alumno ) AS pagado_alumno
			        FROM vista_alumnos
			        WHERE ( id_pla8 = '$plantel' ) AND

			    ";
		    
		    } else if ( ( $tipo_estatus2 == 'adeudo_alumno' ) ) {
		    
		    	$sqlAlumnos = "

			        SELECT SUM( adeudo_alumno ) AS adeudo_alumno
			        FROM vista_alumnos
			        WHERE ( id_pla8 = '$plantel' ) AND

			    ";	
		    
		    } else {
		    
		    	$sqlAlumnos = "

			        SELECT * 
			        FROM vista_alumnos
			        WHERE ( id_pla8 = '$plantel' AND $tipo_estatus2 = '$estatus2' ) AND

			    ";	
		    
		    }
		    

		    // ESTATUS
		    if ( ( isset( $_POST['estatus'] ) ) && ( sizeof( $_POST['estatus'] ) > 0 ) ) {

		      $estatus = $_POST['estatus'];
		      $tipo_estatus = $_POST['tipo_estatus'];

		      for ( $i = 0 ;  $i < sizeof( $estatus )  ;  $i++ ) { 
		        

		        if ( ( $tipo_estatus[$i] != 'pagado_alumno' ) && ( $tipo_estatus[$i] != 'adeudo_alumno' ) ) {
		        // VALIDA SI EL TIPO ESTATUS ES SUMATORIA
		          	if ( sizeof( $estatus ) == 1 ) {
		          	
		          	
			            $sqlAlumnos .= " 
			              ( $tipo_estatus[$i] = '$estatus[$i]' ) AND
			            ";

			            break;
			            break;

			        } else {

			            if ( $i == 0 ) {
			            
			              $sqlAlumnos .= " ( ";
			            
			            }

			            if ( $i < ( sizeof( $estatus ) -1 ) ) {
			                
			                $sqlAlumnos .= "$tipo_estatus[$i] = '$estatus[$i]' OR ";

			            } else if ( $i == ( sizeof( $estatus ) -1 ) ) {
			                
			                $sqlAlumnos .= " 
			                  $tipo_estatus[$i] = '$estatus[$i]' ) AND
			                ";

			            }

			        }
		        
		        }
		        

		      }

		    }
		    // FIN ESTATUS

		    // PALABRA
		    if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

		      $palabra = $_POST['palabra'];
		      
		      $sqlAlumnos .= " 
		        ( ( bol_alu LIKE '%$palabra%' ) OR ( UPPER( nom_alu ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci ) OR ( tel_alu LIKE '%$palabra%' ) OR ( UPPER( cor_alu ) LIKE UPPER('%$palabra%') ) ) AND 
		      ";
		      
		    }
		    // FIN PALABRA


		    // INGRESO
		    if ( ( isset( $_POST['inicio'] ) ) && ( ( $_POST['inicio'] ) != '' ) AND ( isset( $_POST['fin'] ) ) && ( ( $_POST['fin'] ) != '' ) ) {
		    
		      $inicio = $_POST['inicio'];
		      $fin = $_POST['fin'];
		      
		      $sqlAlumnos .= " 
		        ( ing_alu BETWEEN '$inicio' AND '$fin' ) AND
		      ";

		    }
		    // FIN INGRESO


		    // GENERACIONES
		    if ( isset( $_POST['id_gen'] ) && ( $_POST['id_gen'] != '' ) ) {

		      $id_gen = $_POST['id_gen'];

		      for ( $i = 0 ;  $i < sizeof( $id_gen ) ;  $i++ ) {

		        if ( sizeof( $id_gen ) == 1 ) {
		        
		          $sqlAlumnos .= " 
		              ( ( id_gen1 = '$id_gen[$i]' ) )
		          ";

		          break;
		          break;

		        } else {
		          
		          if ( $i == 0 ) {
		            
		            $sqlAlumnos .= " ( ";  
		          
		          }
		          
		          if ( $i < ( sizeof( $id_gen ) -1 ) ) {
		              
		              $sqlAlumnos .= " ( id_gen1 = '$id_gen[$i]' ) OR ";

		          } else if ( $i == ( sizeof( $id_gen ) -1 ) ) {
		              
		              $sqlAlumnos .= " 
		                  ( id_gen1 = '$id_gen[$i]' ) )
		              ";

		          }

		        }

		      }
		      
		    }
		    // FIN GENERACIONES



		  } else {


		  	if ( ( $tipo_estatus2 == 'pagado_alumno' ) ) {
		    
		    	$sqlAlumnos = "

			        SELECT SUM( pagado_alumno ) AS pagado_alumno
			        FROM vista_alumnos
			        WHERE ( id_pla8 = '$plantel' ) AND (

			    ";
		    
		    } else if ( ( $tipo_estatus2 == 'adeudo_alumno' ) ) {
		    
		    	$sqlAlumnos = "

			        SELECT SUM( adeudo_alumno ) AS adeudo_alumno
			        FROM vista_alumnos
			        WHERE ( id_pla8 = '$plantel' ) AND (

			    ";	
		    
		    } else {
		    
		    	$sqlAlumnos = "

			        SELECT * 
			        FROM vista_alumnos
			        WHERE ( id_pla8 = '$plantel' AND $tipo_estatus2 = '$estatus2' ) AND (

			    ";	
		    
		    }
		    

		    // GENERACIONES
		    if ( isset( $_POST['id_gen'] ) && ( $_POST['id_gen'] != '' ) ) {

		      $id_gen = $_POST['id_gen'];

		      for ( $i = 0 ;  $i < sizeof( $id_gen ) ;  $i++ ) {

		        if ( sizeof( $id_gen ) == 1 ) {
		        
		          $sqlAlumnos .= " 
		              ( id_gen1 = '$id_gen[$i]' ) )
		          ";

		          break;
		          break;

		        } else {

		          if ( $i < ( sizeof( $id_gen ) -1 ) ) {
		              
		              $sqlAlumnos .= " ( id_gen1 = '$id_gen[$i]' ) OR ";

		          } else if ( $i == ( sizeof( $id_gen ) -1 ) ) {
		              
		              $sqlAlumnos .= " 
		                  ( id_gen1 = '$id_gen[$i]' ) )
		              ";

		          }

		        }

		      }
		      
		    }
		    // FIN GENERACIONES


		  }

		$resultado = mysqli_query( $db, $sqlAlumnos );


		if ( ( $tipo_estatus2 == 'pagado_alumno' ) ) {
		    
	    	$fila = mysqli_fetch_assoc( $resultado );

	    	if ( $fila['pagado_alumno'] == '' ) {

	    		$total = '$0';	
	    	
	    	} else {

	    		$total = formatearDinero($fila['pagado_alumno']);
	    	
	    	}
	    	
	    
	    } else if ( ( $tipo_estatus2 == 'adeudo_alumno' ) ) {
	    	
	    	$fila = mysqli_fetch_assoc( $resultado );
	    	
	    	if ( $fila['adeudo_alumno'] == '' ) {

	    		$total = '$0';	
	    	
	    	} else {

	    		$total = formatearDinero($fila['adeudo_alumno']);
	    	
	    	}
	    
	    } else {
	    
	    	$total = mysqli_num_rows( $resultado );
	    
	    }

		return $total;

	}

	function obtenerTotalAlumnosGeneracionServer( $id_gen ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT *
			FROM alu_ram
			WHERE id_gen1 = '$id_gen'
		";

		$resultado = mysqli_query( $db, $sql );

		$total = mysqli_num_rows( $resultado );

		return $total;

	}

	function comprimirTexto( $cadena ){

		if ( strlen( $cadena ) > 25 ) {

			return substr( $cadena, 0, 25 )."...";
		
		} else {

			return $cadena;

		}

	}


  function comprimirTextoVariable( $cadena, $longitud ){

    if ( strlen( $cadena ) > $longitud ) {

      return substr( $cadena, 0, $longitud )."...";
    
    } else {

      return $cadena;

    }

  }


	function obtenerEstatusComisionServer( $id_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT *
			FROM pago
			WHERE id_alu_ram10 = '$id_alu_ram'
			ORDER BY id_pag ASC
			LIMIT 2
		";


		$booleano = "Activo";

		$resultado = mysqli_query( $db, $sql );

		while( $fila = mysqli_fetch_assoc( $resultado ) ){

			if ( $fila['est_pag'] == 'Pendiente' ) {
				$booleano = "Registro";

				break; break;
			}

		}

		return $booleano;

	}

	function obtenerTotalAluRamsServer( $id_pla ){
		require( '../../includes/conexion.php' );

		$sql = "
		 	SELECT * 
			FROM alu_ram 
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
			INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
			WHERE id_pla1 = '$id_pla'
		";

		$resultado = mysqli_query( $db, $sql );

		return mysqli_num_rows( $resultado );

	}



  function enviarCorreoPagoAlumnoServer( $id_pag, $nombrePlantel, $correo2Plantel, $ligaPlantel, $esloganPlantel, $nomResponsable, $direccionPlantel ){
    require( '../../includes/conexion.php' );

    $sql = "
      SELECT *
      FROM vista_pagos
      WHERE id_pag = '$id_pag'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $abonado = $fila['mon_ori_pag'] - $fila['mon_pag'];
    $id_alu_ram = $fila['id_alu_ram'];

    $datosAlumno = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
    $correoAlumno = $datosAlumno['cor1_alu'];
    $bol_alu = $datosAlumno['bol_alu'];

    $para ="";
    $para .= $correoAlumno;

    $titulo = 'Recibo de pago de '.$nombrePlantel;

      
    $mensaje = '
      <!DOCTYPE html>

      <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
      <head>
      <title></title>
      <meta charset="utf-8"/>
      <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
      <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
      <!--[if !mso]><!-->
      <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css"/>
      <!--<![endif]-->
      <style>
          * {
            box-sizing: border-box;
          }

          body {
            margin: 0;
            padding: 0;
          }

          th.column {
            padding: 0
          }

          a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
          }

          #MessageViewBody a {
            color: inherit;
            text-decoration: none;
          }

          p {
            line-height: inherit
          }

          @media (max-width:700px) {
            .icons-inner {
              text-align: center;
            }

            .icons-inner td {
              margin: 0 auto;
            }

            .fullMobileWidth,
            .row-content {
              width: 100% !important;
            }

            .image_block img.big {
              width: auto !important;
            }

            .mobile_hide {
              display: none;
            }

            .stack .column {
              width: 100%;
              display: block;
            }

            .mobile_hide {
              min-height: 0;
              max-height: 0;
              max-width: 0;
              overflow: hidden;
              font-size: 0px;
            }
          }
        </style>
      </head>
      <body style="background-color: #4f4fef; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">



      <br>
      <br>
      <br>

      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #4f4fef;" width="100%">
      <tbody>
      <tr>
      <td>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3939ad;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:30px;">
      <div style="font-family: sans-serif; ">
      <div style="font-size: 12px; color: #ffffff; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;position: relative; ">
      
      <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Servicios empresariales de '.$nombrePlantel.'</span></p>
      <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Gracias por tu confianza ❤️</span>
      </p>

      <p style="margin: 0; font-size: 12px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:12px; color: lightblue;">'.$esloganPlantel.'</span>
      </p>

      <p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:10px; color: white;">Responsable: '.$nomResponsable.'</span></p>

      </div>

      <!-- <div style="text-align: center;">
        
        
      </div> -->

      </div>
      </td>
      </tr>
      </table>

      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:5px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Recibo de pago</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">


      <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">'.$fila['con_pag'].'</span></strong></span></p>


      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.$direccionPlantel.'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.$nombrePlantel.'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Alumno: '.$fila['nom_alu'].' - Matrícula: '.$bol_alu.'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.$fila['nom_gen'].' - '.$fila['nom_ram'].'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.fechaHoraFormateadaCompacta2( date('Y-m-d H:i:s') ).'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      </th>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>



      <td style="padding-bottom:5px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Cantidad</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;"><strong><span style="font-size:20px;"><span style="font-size: 30px;">'.formatearDinero( $fila['mon_ori_pag'] ).'</span></span></strong></p>

      <p style="margin: 0; font-size: 14px;"><strong><span style="font-size:20px;"><span style="font-size: 16px; color: grey;">'.$fila['est_pag'].'</span></span></strong></p>


      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td style="padding-bottom:15px;padding-left:35px;padding-right:10px;padding-top:10px;text-align:left;">

        <!-- LOGO -->
      </td>
      </tr>
      </table>
      </th>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #4f4fef;" width="100%">
      <tbody>
      <tr>
      <td>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 0px;" width="100%">
      <table border="0" cellpadding="10" cellspacing="0" class="divider_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td>
      <div align="center">
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #D6D3D3;"><span></span></td>
      </tr>
      </table>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:5px;padding-left:35px;padding-right:10px;padding-top:20px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Folio de pago</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td style="padding-bottom:20px;padding-left:35px;padding-right:10px;padding-top:10px;text-align:left;">
      <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:32px;width:48px;v-text-anchor:middle;" arcsize="13%" stroke="false" fillcolor="#f9d5d5"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#e17370; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
      <div style="text-decoration:none;display:inline-block;color:#e17370;background-color:#f9d5d5;border-radius:4px;width:auto;border-top:0px solid #8a3b8f;border-right:0px solid #8a3b8f;border-bottom:0px solid #8a3b8f;border-left:0px solid #8a3b8f;padding-top:0px;padding-bottom:0px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">'.$fila['fol_pag'].'</span></span></div>
      <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
      </td>
      </tr>
      </table>


      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tbody>
      <tr>
      <td>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">Pagado</span></p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      </th>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
      <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
      <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
      <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
      </th>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $abonado ).'</span></p>

      </div>


      </div>
      </td>
      </tr>
      </table>
      </th>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>



      <!-- ABONOS -->
      ';


        $sqlAbonos = "
          SELECT *
          FROM abono_pago
          WHERE id_pag1 = '$id_pag'
        ";

        $resultadoAbonos = mysqli_query( $db, $sqlAbonos );

        if ( !$resultadoAbonos ) {
          
          echo $sqlAbonos;

        }

        while( $filaAbonos = mysqli_fetch_assoc( $resultadoAbonos ) ){
      
          
          $mensaje.= '<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
            <tbody>
            <tr>
            <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
            <tbody>
            <tr>
            <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
            <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
            <tr>
            <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
            <div style="font-family: sans-serif">
            <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
            <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:12px;">'.$filaAbonos['tip_abo_pag'].' <br>'.fechaFormateadaCompacta2( $filaAbonos['fec_abo_pag'] ).'</span></p>
            </div>
            </div>
            </td>
            </tr>
            </table>
            </th>
            <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
            <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
            <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
            <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
            </th>
            <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
            <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
            <tr>
            <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
            <div style="font-family: sans-serif">
            <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
            <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $filaAbonos['mon_abo_pag'] ).'</span></p>

            </div>


            </div>
            </td>
            </tr>
            </table>
            </th>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            </tbody>
            </table>
          ';
      
        }
      


      $mensaje.= '
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-11" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">Adeudo</span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $fila['mon_pag'] ).'</span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-12" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:15px;padding-left:35px;padding-right:10px;padding-top:20px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">Total</span></strong></span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:15px;padding-left:35px;padding-right:10px;padding-top:20px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;"><strong><span style="font-size:20px;"><span style="">'.formatearDinero( $fila['mon_ori_pag'] ).'</span></span></strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-13" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
        <div class="spacer_block" style="height:20px;line-height:20px;"> </div>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-14" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #4f4fef;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 0px;" width="100%">
        <table border="0" cellpadding="10" cellspacing="0" class="divider_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tr>
        <td>
        <div align="center">
        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tr>
        <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #D6D3D3;"><span></span></td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:25px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">Pago seguro</span></strong></span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:35px;padding-right:35px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #393d47; line-height: 1.8; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;">Recuerda presentar este comprobante en caso de tener cualquier problema eventualmente.</p>
        </div>
        </div>
        </td>
        </tr>
        </table>

        <hr>
      ';


          
          $sqlPagos = "
            SELECT *
            FROM pago
            WHERE id_alu_ram10 = '$id_alu_ram' AND est_pag = 'Pendiente'
            ORDER BY ini_pag ASC
          ";

          $resultadoPagos = mysqli_query( $db, $sqlPagos );

          $resultadoTotalPagos = mysqli_query( $db, $sqlPagos );

          $totalPagos = mysqli_num_rows( $resultadoTotalPagos );

          if ( $totalPagos > 0 ) {
            
            $mensaje.= '
              <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
              <tr>
              <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:25px;">
              <div style="font-family: sans-serif">
              <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
              <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">¡RECUERDA! <br>Calendario de próximos pagos</span></strong></span></p>
              </div>
              </div>
              </td>
              </tr>
              </table>
            ';

            
              while( $filaPagos = mysqli_fetch_assoc( $resultadoPagos ) ){
            
                $mensaje.= '
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                  <tbody>
                  <tr>
                  <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
                  <tbody>
                  <tr>
                  <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
                  <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                  <tr>
                  <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
                  <div style="font-family: sans-serif">
                  <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                  <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:12px;">'.$filaPagos['con_pag'].' <br>'.fechaFormateadaCompacta2( $filaPagos['fin_pag'] ).'</span></p>
                  </div>
                  </div>
                  </td>
                  </tr>
                  </table>
                  </th>
                  <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
                  <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
                  <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
                  <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
                  </th>
                  <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
                  <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                  <tr>
                  <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
                  <div style="font-family: sans-serif">
                  <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                  <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $filaPagos['mon_pag'] ).'</span></p>

                  </div>


                  </div>
                  </td>
                  </tr>
                  </table>
                  </th>
                  </tr>
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  </tbody>
                  </table>
                  <hr>
                ';


            
              }
            
          
          } else {
            
            $mensaje.= '
              <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
              <tr>
              <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:25px;">
              <div style="font-family: sans-serif">
              <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
              <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">No tienes más pagos de momento.</span></strong></span></p>
              </div>
              </div>
              </td>
              </tr>
              </table>
            ';
        
          }
        
      
      $mensaje.= '     
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tr>
        <td style="padding-bottom:30px;padding-left:35px;padding-right:10px;padding-top:10px;text-align:center;">
        <div align="center">
        <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://www.example.com" style="height:44px;width:122px;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#E17370" fillcolor="#e17370"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]--><a href="'.$ligaPlantel.'" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#e17370;border-radius:4px;width:auto;border-top:1px solid #E17370;border-right:1px solid #E17370;border-bottom:1px solid #E17370;border-left:1px solid #E17370;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;" target="_blank"><span style="padding-left:30px;padding-right:30px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Ir a la plataforma</span></span></a>
        <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-15" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffb0af;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
        <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td>
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #393d47; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px; text-align: center;">FOLIO : <strong>'.$fila['fol_pag'].'</strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-16" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>



        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-20" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3939ad;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; line-height: 1.2;">
        <p style="margin: 0; font-size: 18px; text-align: left;"><strong><span style="color:#ffffff;">Redes sociales</span></strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:20px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #C0C0C0; line-height: 1.8;">
        <p style="margin: 0;"><span style=""></span></p>
        <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21.6px;"><span style="color:#C0C0C0;font-size:12px;">Recuerdo darle like y seguir nuestras fanpages en facebook para estar al pendiente de los eventos, promociones, ofertas y más...</span></p>
        <p style="margin: 0; mso-line-height-alt: 21.6px;"></p>
        </div>
        </div>
        </td>
        </tr>
        </table>


        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; line-height: 1.2;">
        <p style="margin: 0; font-size: 18px; text-align: left;"><strong><span style="color:#ffffff;">Contacto</span></strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:20px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #C0C0C0; line-height: 1.8; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">

        <p style="margin: 0; mso-line-height-alt: 21.6px;"><span style="color:#C0C0C0;font-size:12px;">Dirección:
          '.$direccionPlantel.' </br> Teléfonos: '.$telefonoPlantel.'</span></p>

        Visita: <a href="'.$ligaPlantel.'" style="color: white;" target="_blank">
          '.$ligaPlantel.'
        </a>

          
        </div>
        </div>
        </td>
        </tr>
        </table>

        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-21" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3939ad;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
        <div class="spacer_block" style="height:20px;line-height:20px;"> </div>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-22" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>



        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="100%">
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        <div class="spacer_block mobile_hide" style="height:20px;line-height:20px;"> </div>
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>

        </td>
        </tr>
        </tbody>
        </table><!-- End -->
        </body>
        </html>
      ';

      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

      // Cabeceras adicionales
      $cabeceras .= 'From: '.$nombrePlantel.' <'.$correo2Plantel.'>' . "\r\n";

      // Enviarlo
      mail( $para, $titulo, $mensaje, $cabeceras );

  } 

  

	function enviarCorreoBienvenidaAlumnoDemo( $cuentaAlumno, $correoAlumno, $passwordAlumno, $nombreAlumno, $nombrePlantel, $correo2Plantel, $ligaPlantel, $fotoPlantel ){

		$para ="";
	    $para .= $correoAlumno;

	    $titulo = 'Bienvenido a '.$nombrePlantel;

	    // mensaje
	    $mensaje = '
	      	<!doctype html>
	      	<html lang="es">
	        	<head>
	          		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	          		<!-- Bootstrap CSS -->
	          		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

	          		<title>Mensaje de bienvenida</title>
	        	</head>
	        	<body>
	          		<div class="container">

	          			<div class="row">
							<div class="col-md-1 col-sm-1">
								<img src="'.$ligaPlantel.'uploads/'.$fotoPlantel.'" style="width: 60px; height: 60px;">
							</div>

							<div class="col-md-11 col-sm-11 text-center">
								<br>
								<h3>
									'.$nombrePlantel.' te da la más cordial bienvenida, '.$nombreAlumno.'. 
								</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<br>
								<p>
									A continuación, te compartimos tu cuenta de acceso y tu contraseña para acceder
									a la plataforma.
									<br>
									<br>

									Correo:
									<br>
									<span style="font-weight: bold;">
										'.$cuentaAlumno.'	
									</span>
									<br>
									<br>
									
									
									Contraseña:
									<br>
									<span style="font-weight: bold;">
										'.$passwordAlumno.'
									</span>
									<br>
									<br>
									

									<br>
									La dirección web de la plataforma es esta:
									<br>

									
									<a style="font-weight: bold; color: blue;" class="btn-link" href="'.$ligaPlantel.'" target="_blank">
										'.$ligaPlantel.'
									</a>
									<br>
									<br>
									<br>

									<span style="font-size: 10px; color: grey;">
										Esto es un mensaje automatizado, favor de no responder. 
									</span>
									<br>
									<br>

								</p>
							</div>
						</div>
	            
	          		</div>
					<!-- Optional JavaScript -->
					<!-- jQuery first, then Popper.js, then Bootstrap JS -->
					<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
					<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
					<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	        	</body>
	      	</html>
	    ';

	    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	    $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

	    // Cabeceras adicionales
	    $cabeceras .= 'From: '.$nombrePlantel.' <'.$correo2Plantel.'>' . "\r\n";

	    // Enviarlo
	    mail( $para, $titulo, $mensaje, $cabeceras );

	}

	function enviarCorreoBienvenidaAlumno( $cuentaAlumno, $correoAlumno, $passwordAlumno, $nombreAlumno, $nombrePlantel, $correo2Plantel, $ligaPlantel, $fotoPlantel ){

		$para ="";
	    $para .= $correoAlumno;

	    $titulo = 'Bienvenido a '.$nombrePlantel;

	    // mensaje
	    $mensaje = '
	      	<!doctype html>
	      	<html lang="es">
	        	<head>
	          		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	          		<!-- Bootstrap CSS -->
	          		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

	          		<title>Mensaje de bienvenida</title>
	        	</head>
	        	<body>
	          		<div class="container">

	          			<div class="row">
							<div class="col-md-1 col-sm-1">
								<img src="'.$ligaPlantel.'uploads/'.$fotoPlantel.'" style="width: 60px; height: 60px;">
							</div>

							<div class="col-md-11 col-sm-11 text-center">
								<br>
								<h3>
									'.$nombrePlantel.' te da la más cordial bienvenida, '.$nombreAlumno.'. 
								</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<br>
								<p>
									A continuación, te compartimos tu cuenta de acceso y tu contraseña para acceder
									a la plataforma.
									<br>
									<br>

									Correo:
									<br>
									<span style="font-weight: bold;">
										'.$cuentaAlumno.'	
									</span>
									<br>
									<br>
									
									
									Contraseña:
									<br>
									<span style="font-weight: bold;">
										'.$passwordAlumno.'
									</span>
									<br>
									<br>
									

									<br>
									La dirección web de la plataforma es esta:
									<br>

									
									<a style="font-weight: bold; color: blue;" class="btn-link" href="'.$ligaPlantel.'" target="_blank">
										'.$ligaPlantel.'
									</a>
									<br>
									<br>
									<br>

									<span style="font-size: 10px; color: grey;">
										Esto es un mensaje automatizado, favor de no responder. 
									</span>
									<br>
									<br>

								</p>
							</div>
						</div>
	            
	          		</div>
					<!-- Optional JavaScript -->
					<!-- jQuery first, then Popper.js, then Bootstrap JS -->
					<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
					<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
					<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	        	</body>
	      	</html>
	    ';

	    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	    $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

	    // Cabeceras adicionales
	    $cabeceras .= 'From: '.$nombrePlantel.' <'.$correo2Plantel.'>' . "\r\n";

	    // Enviarlo
	    mail( $para, $titulo, $mensaje, $cabeceras );

	}
	
	// INSERTAR ACTIVIDAD EN GRUPO CON LA FECHA DE HOY CON FECHA DE HOY + 2 DIAS
	function generarActividadGrupo( $identificador_actividad, $tipo_actividad, $id_sub_hor ){
		require( '../includes/conexion.php' );

		$inicio = date( 'Y-m-d' );
		$fin = gmdate( 'Y-m-d', strtotime ( '+ 2 day' , strtotime ( date( 'Y-m-d' ) ) ) );

		if ( $tipo_actividad == 'Foro' ) {
			$prefijo = "_for";
			$tabla = "foro";

			$sql = "
				INSERT INTO foro_copia (ini_for_cop, fin_for_cop, id_for1, id_sub_hor2) 
				VALUES('$inicio', '$fin', '$identificador_actividad', '$id_sub_hor')
			";

		} else if ( $tipo_actividad == 'Entregable' ) {
			$prefijo = "_ent";
			$tabla = "entregable";

			$sql = "
				INSERT INTO entregable_copia (ini_ent_cop, fin_ent_cop, id_ent1, id_sub_hor3) 
				VALUES('$inicio', '$fin', '$identificador_actividad', '$id_sub_hor')
			";

		} else if ( $tipo_actividad == 'Examen' ) {
			$prefijo = "_exa";
			$tabla = "examen";

			$sql = "
				INSERT INTO examen_copia (ini_exa_cop, fin_exa_cop, id_exa1, id_sub_hor4)
				VALUES('$inicio', '$fin', '$identificador_actividad', '$id_sub_hor')
			";
			
		}


		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {

			$sqlMaximoCopia = "
				SELECT MAX(id".$prefijo."_cop) AS maximo
				FROM ".$tabla."_copia
			";

			$resultadoMaximoCopia = mysqli_query($db, $sqlMaximoCopia);

			if ($resultadoMaximoCopia) {
				$filaMaximoCopia = mysqli_fetch_assoc($resultadoMaximoCopia);

				$maximoCopia = $filaMaximoCopia['maximo'];
				$identificador_copia = $maximoCopia;

				$sqlAlumnosActualizados = "

					SELECT *
					FROM alu_hor
					WHERE id_sub_hor5 = '$id_sub_hor' AND est_alu_hor = 'Activo'
				";

				$resultadoAlumnosActualizados = mysqli_query($db, $sqlAlumnosActualizados);

				if ($resultadoAlumnosActualizados) {

					while($filaAlumnosActualizados = mysqli_fetch_assoc($resultadoAlumnosActualizados)){
						
						
						$id_alu_ram = $filaAlumnosActualizados['id_alu_ram1'];

						$sqlInsercions = "INSERT INTO cal_act(id".$prefijo."_cop2, id_alu_ram4) VALUES('$identificador_copia', '$id_alu_ram')";
						$resultadoInsercions = mysqli_query($db, $sqlInsercions);

						if(!$resultadoInsercions){
							echo "error en insercion copias calacts";
						}

					}

					//echo "Exito";
					
				}else{
					echo "error en consulta de alu_hor";
				}


			}else{
				echo $sqlMaximoCopia;
				// echo "error en extraccion de maximo entregable copia";
			}
			


		} else {
			echo $sql;
		}

		
	}
	
	// ALGORITMOS ESPACIOS DE FILTROS
	function obtenerIndiceCard( $card, $tipoUsuario, $id ){
		require( '../includes/conexion.php' );

		if ( $tipoUsuario == 'Admin' ) {
			 
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_adm3 = '$id'
			";

		} else if ( $tipoUsuario == 'Adminge' ) {
			
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_adg3 = '$id'
			";

		} else if ( $tipoUsuario == 'Cobranza' ) {
			
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_cob2 = '$id'
			";
		}

		$resultadoTotal = mysqli_query( $db, $sql );

		$total = mysqli_num_rows( $resultadoTotal );

		if ( $total > 0 ) {
		
			$resultado = mysqli_query( $db, $sql );
			$fila = mysqli_fetch_assoc( $resultado );

			return $fila[$card];

		} else {
			return 'false';
		}


		
	}


	function obtenerValidacionIndiceCardServer( $tipoUsuario, $id ){
		require( '../../includes/conexion.php' );

		if ( $tipoUsuario == 'Admin' ) {
			 
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_adm3 = '$id'
			";


			$resultadoTotal = mysqli_query( $db, $sql );

			$total = mysqli_fetch_assoc( $resultadoTotal );

			if ( $total == 0 ) {
			
				$sqlInsercionFiltro = "
					INSERT INTO filtro( id_adm3 )
					VALUES ( $id )
				";

				$resultadoInsercionFiltro = mysqli_query( $db, $sqlInsercionFiltro );

				if ( !$resultadoInsercionFiltro ) {
					echo $sqlInsercionFiltro;
				}

			}

		} else if ( $tipoUsuario == 'Adminge' ) {
			
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_adg3 = '$id'
			";


			$resultadoTotal = mysqli_query( $db, $sql );

			$total = mysqli_fetch_assoc( $resultadoTotal );

			if ( $total == 0 ) {
			
				$sqlInsercionFiltro = "
					INSERT INTO filtro( id_adg3 )
					VALUES ( $id )
				";

				$resultadoInsercionFiltro = mysqli_query( $db, $sqlInsercionFiltro );

				if ( !$resultadoInsercionFiltro ) {
					echo $sqlInsercionFiltro;
				}

			}

		} else if ( $tipoUsuario == 'Cobranza' ) {
			
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_cob2 = '$id'
			";


			$resultadoTotal = mysqli_query( $db, $sql );

			$total = mysqli_fetch_assoc( $resultadoTotal );

			if ( $total == 0 ) {
			
				$sqlInsercionFiltro = "
					INSERT INTO filtro( id_cob2 )
					VALUES ( $id )
				";

				$resultadoInsercionFiltro = mysqli_query( $db, $sqlInsercionFiltro );

				if ( !$resultadoInsercionFiltro ) {
					echo $sqlInsercionFiltro;
				}

			}
		}

		
	}




	function obtenerEstatusAcordeon( $acordeon, $tipoUsuario, $id ){
		require( '../includes/conexion.php' );

		if ( $tipoUsuario == 'Admin' ) {
			 
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_adm3 = '$id'
			";

		} else if ( $tipoUsuario == 'Adminge' ) {
			
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_adg3 = '$id'
			";

		} else if ( $tipoUsuario == 'Cobranza' ) {
			
			$sql = "
				SELECT *
				FROM filtro
				WHERE id_cob2 = '$id'
			";
		}

		$resultadoTotal = mysqli_query( $db, $sql );

		$total = mysqli_num_rows( $resultadoTotal );

		if ( $total > 0 ) {
		
			$resultado = mysqli_query( $db, $sql );
			$fila = mysqli_fetch_assoc( $resultado );

			if ( $fila[$acordeon] != NULL ) {
				
				return $fila[$acordeon];
			
			} else {
			
				return 'falso';
			
			}

			

		} else {
			return 'falso';
			// SE COLOCO 'falso' DEBIDO A QUE CHOCABA CON EL ATRIBUTO RECICLADO DE aria-expanded
		}


		
	}

	// FIN ALGORITMO ESPACIOS DE FILTROS



  function agregar_referido_server( $nom_ref, $tel_ref, $cor_ref, $id_cit ){
    require('../../includes/conexion.php');
    $sql = "
      INSERT INTO referido ( nom_ref, tel_ref, cor_ref, id_cit3  )
      VALUES ( '$nom_ref', '$tel_ref', '$cor_ref', '$id_cit' )
    ";

    $resultado = mysqli_query( $db, $sql );

    if ( !$resultado ) {
      echo $sql;
    }
  }



	function obtenerTipoUsuarioKardexServer( $tipo, $identificador ) {
		require( '../../includes/conexion.php' );
		if ( $tipo == 'Admin' ) {

			$sql = "
				SELECT *
				FROM admin
				WHERE id_adm = '$identificador'
			";

			$resultado = mysqli_query( $db, $sql );

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila['nom_adm'].' '.$fila['app_adm'];
			
			
		} else if ( $identificador == 'Adminge' ) {

			$sql = "
				SELECT *
				FROM adminge
				WHERE id_adg = '$identificador'
			";

			$resultado = mysqli_query( $db, $sql );

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila['nom_adg'].' '.$fila['app_adg'];

		} else if ( $identificador == 'Profesor' ) {

			$sql = "
				SELECT *
				FROM profesor
				WHERE id_pro = '$identificador'
			";

			$resultado = mysqli_query( $db, $sql );

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila['nom_pro'].' '.$fila['app_pro'];
	
		}

	}


	

	function obtenerInsercionKardexServer( $tipoUsuario, $tipoCalificacion, $calificacion, $id_calificacion, $id_sub_hor_aux, $id_usuario ) {
		require( '../../includes/conexion.php' );

		$fechaHoy = date( 'Y-m-d H:i:s' );

		$fec_kar = $fechaHoy;
		$tip_kar = $tipoCalificacion;
		$cal_kar = $calificacion;
		$id_cal1 = $id_calificacion;
		$id_sub_hor9 = $id_sub_hor_aux;
		$id_usuario = $id_usuario;

		if ( $tipoUsuario == 'Admin' ) {
			
			$sql = "

				INSERT INTO kardex ( fec_kar, tip_kar, cal_kar, id_cal1, id_sub_hor9, tip2_kar, id_adm2, id_pro6, id_adg2) 
				VALUES ( '$fec_kar', '$tip_kar', '$cal_kar', '$id_cal1', '$id_sub_hor9', '$tipoUsuario', '$id_usuario', NULL, NULL)
			
			";


		} else if ( $tipoUsuario == 'Adminge' ) {

			$sql = "

				INSERT INTO kardex ( fec_kar, tip_kar, cal_kar, id_cal1, id_sub_hor9, tip2_kar, id_adm2, id_pro6, id_adg2 ) 
				VALUES ( '$fec_kar', '$tip_kar', '$cal_kar', '$id_cal1', '$id_sub_hor9', '$tipoUsuario', NULL, NULL, '$id_usuario' )
			
			";
			
		} else if ( $tipoUsuario == 'Profesor' ) {
			
			$sql = "

				INSERT INTO kardex ( fec_kar, tip_kar, cal_kar, id_cal1, id_sub_hor9, tip2_kar, id_adm2, id_pro6, id_adg2) 
				VALUES ( '$fec_kar', '$tip_kar', '$cal_kar', '$id_cal1', '$id_sub_hor9', '$tipoUsuario', NULL, '$id_usuario', NULL)
			
			";

		}

		$resultado = mysqli_query( $db, $sql );

		if ( !$resultado ) {
			
			echo $sql;

		}
		
		
	}

	function eliminacionPagosFuturos ( $id_alu_ram ) {
		require( '../../includes/conexion.php' );
		$fechaHoy = date( 'Y-m-d' );

		$sql = "
	    	DELETE
	    	FROM pago
	    	WHERE id_alu_ram10 = '$id_alu_ram' AND  ini_pag > '$fechaHoy'
			
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( !$resultado ) {
	    	
	    	echo $sql;

	    }
	}



//FUNCION PARA SABER DIFERENCIA DE DIAS...LO DA COMO UN STRING

	function obtenerDiferenciaDias($fecha1, $fecha2)
    {
    	$date1=date_create($fecha1);
		$date2=date_create($fecha2);
		$diff=date_diff($date1,$date2);
		echo $diff->format("%a dias");
    }

    function obtenerEstatusCicloServer( $id_cic ) {
    	
    	require('../../includes/conexion.php');

		$fechaHoyCiclos = date('Y-m-d');

		$sqlCiclos = "

			SELECT *
			FROM ciclo
			WHERE id_cic = '$id_cic' AND fin_cic < '$fechaHoyCiclos'
		";


		$resultadoValidacionCiclos = mysqli_query( $db, $sqlCiclos );

		$totalValidacionCiclos = mysqli_num_rows( $resultadoValidacionCiclos );


		if ($totalValidacionCiclos > 0) {

			return 'Vencido';

		} else {
			return 'Vigente';
		}

    }




  //   function obtenerEstatusCicloServer( $id_cic ) {
    	
  //   	require('../../includes/conexion.php');

		// $fechaHoyCiclos = date('Y-m-d');

		// $sqlCiclos = "
		// 	SELECT *
		// 	FROM ciclo
		// 	WHERE id_cic = '$id_cic' AND ins_cic <= '$fechaHoyCiclos'
		// ";


		// $resultadoValidacionCiclos = mysqli_query( $db, $sqlCiclos );

		// $totalValidacionCiclos = mysqli_num_rows( $resultadoValidacionCiclos );


		// if ($totalValidacionCiclos > 0) {

		// 	return 'Vencido';

		// } else {
		// 	return 'Vigente';
		// }

  //   }


	function muerteCiclos() {
		require('../includes/conexion.php');

		$fechaHoy = date('Y-m-d H:i:s');
		$fechaHoyCiclos = date('Y-m-d');

		$validacionEliminacion = true;

		$sqlCiclos = "

			SELECT *
			FROM ciclo
			WHERE fin_cic < '$fechaHoyCiclos'
		";


		$resultadoValidacionCiclos = mysqli_query($db, $sqlCiclos);

		$totalValidacionCiclos = mysqli_num_rows($resultadoValidacionCiclos);


		if ($totalValidacionCiclos > 0) {
			$resultadoCiclos = mysqli_query($db, $sqlCiclos);

			while($filaCiclos = mysqli_fetch_assoc($resultadoCiclos)){
				$id_cic = $filaCiclos['id_cic'];


				$sqlTareas = "
					SELECT *
					FROM tarea
					INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = tarea.id_ent_cop1
		            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
					INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
					INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
					WHERE id_cic = '$id_cic'
				";

				//echo $sqlTareas;

				$resultadoTareas = mysqli_query($db, $sqlTareas);

				while($filaTareas = mysqli_fetch_assoc($resultadoTareas)){

					$archivo = $filaTareas['doc_tar'];

					if ( $archivo != NULL) {
						$path = '../uploads/'.$archivo;
						$validacionEliminacion = file_exists($path);
						if ( $validacionEliminacion == 1 ) {
							unlink( $path );
						}

					}


				}
				

				$sqlGrupos = "  
					SELECT *
					FROM sub_hor
					INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
					WHERE id_cic1 = '$id_cic'
				";

				// echo $sqlGrupos;

				$resultadoGrupos = mysqli_query( $db, $sqlGrupos );

				while( $filaGrupos = mysqli_fetch_assoc( $resultadoGrupos ) ) {

					$id_sub_hor = $filaGrupos['id_sub_hor'];

					$sqlUpdateAlumnos = "
						UPDATE alu_hor
						SET 
						est_alu_hor = 'Inactivo',
						fec_alu_hor = '$fechaHoy'
						WHERE id_sub_hor5 = '$id_sub_hor'
					";

					$resultadoUpdateAlumnos = mysqli_query( $db, $sqlUpdateAlumnos );

					if ( $resultadoUpdateAlumnos ) {

						$sqlUpdateGrupos = "
							UPDATE sub_hor
							SET 
							est_sub_hor = 'Inactivo',
							fec_sub_hor = '$fechaHoy'
							WHERE id_sub_hor = '$id_sub_hor'
						";

						$resultadoUpdateGrupos = mysqli_query( $db, $sqlUpdateGrupos );

						if ( !$resultadoUpdateGrupos ) {
							
							echo $sqlUpdateGrupos;
						
						}

					} else {
						echo $sqlUpdateAlumnos;
					}



					$sqlDeleteForosCopias = "
						DELETE FROM foro_copia WHERE id_sub_hor2 = '$id_sub_hor'
					";


					$resultadoForosCopias = mysqli_query( $db, $sqlDeleteForosCopias );


					$sqlDeleteEntregablesCopias = "
						DELETE FROM entregable_copia WHERE id_sub_hor3 = '$id_sub_hor'
					";


					$resultadoEntregablesCopias = mysqli_query( $db, $sqlDeleteEntregablesCopias );



					$sqlDeleteExamenesCopias = "
						DELETE FROM examen_copia WHERE id_sub_hor4 = '$id_sub_hor'
					";


					$resultadoExamenesCopias = mysqli_query( $db, $sqlDeleteExamenesCopias );


				}




			}
		}
	}
	
	function obtenerEvaluacion( $id_alu_ram ){
		require('../includes/conexion.php');

		$sql = "
			SELECT *
			FROM calificacion 
			WHERE id_alu_ram2 = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$i = 0;
			$promedio = 0;
			$sumatoria = 0;
			while( $fila = mysqli_fetch_assoc( $resultado ) ) {
				if ( $fila['fin_cal'] != NULL ) {
					$sumatoria = $sumatoria + $fila['fin_cal'];
					$i++;	
				}
			}

			if ( $i == 0 ) {
				return "Pendiente";
			} else {
				$promedio = $sumatoria / $i;
				return $promedio;
			}
			

		}else {
			echo $sql;
		}

	}



	function obtenerEvaluacionServer( $id_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT *
			FROM calificacion 
			WHERE id_alu_ram2 = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$i = 0;
			$promedio = 0;
			$sumatoria = 0;
			while( $fila = mysqli_fetch_assoc( $resultado ) ) {
				if ( $fila['fin_cal'] != NULL ) {
					$sumatoria = $sumatoria + $fila['fin_cal'];
					$i++;	
				}
			}

			if ( $i == 0 ) {
				return "Pendiente";
			} else {
				$promedio = $sumatoria / $i;
				return round( $promedio, 2 );
			}
			

		}else {
			echo $sql;
		}

	}


	// CLAVE COMPUESTA
	function obtenerClaveCompuestaServer($id_cic)
	{
		require('../../includes/conexion.php');
		
		$ciclo = $id_cic;
		$sql_detalles_ciclo = "SELECT * FROM ciclo 
							   INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
							   WHERE id_cic='$ciclo' ";
		$resultado_detalles_ciclo = mysqli_query($db, $sql_detalles_ciclo);
		if ($resultado_detalles_ciclo) {
			$fila_detalles = mysqli_fetch_assoc($resultado_detalles_ciclo);
			$rama=$fila_detalles['id_ram1'];
			$inicio = $fila_detalles['ini_cic'];
			$programa = substr($fila_detalles['gra_ram'], 0, 2);
			$modalidad = substr($fila_detalles['mod_ram'], 0,2);
			$annio_ciclo = date('y');

			$sql_conteo_ciclos = "
				SELECT COUNT(id_cic) AS ciclos 
				FROM ciclo
				WHERE id_ram1 = '$rama' AND ini_cic < '$inicio';
			";

			// echo $sql_conteo_ciclos;

			$resultado_conteo_ciclos = mysqli_query($db, $sql_conteo_ciclos);
			$fila_ciclos= mysqli_fetch_assoc($resultado_conteo_ciclos);
			$ciclos=$fila_ciclos['ciclos'];
			$ciclos= $ciclos+1;
			if ($ciclos <= 9) {
				 $digitos_ciclo = "0".$ciclos;
			}
			else{
				$digitos_ciclo = $ciclos;
			}

			$grupo = $programa.$modalidad."_".$digitos_ciclo.$annio_ciclo;
			
			// return $sql_conteo_ciclos;
			return strtoupper ($grupo);
			

		}
	}
	// FIN CLAVE COMPUESTA

	function reloj(){
		echo '
			<style>
	
			.clock {
				
				transform: translateX(10%);
				color: #17D4FE;
				font-size: 20px;
				font-family: "Orbitron", sans-serif;
				letter-spacing: 4px;

				}
			</style>


			<div id="MyClockDisplay" class="clock breadcrumb-dn mr-auto">
      </div>


			<script>
				function showTime(){
			    var date = new Date();
			    var h = date.getHours(); // 0 - 23
			    var m = date.getMinutes(); // 0 - 59
			    var s = date.getSeconds(); // 0 - 59
			    var session = "AM";
			    
			    if(h == 0){
			        h = 12;
			    }
			    
			    if(h > 12){
			        h = h - 12;
			        session = "PM";
			    }
			    
			    h = (h < 10) ? "0" + h : h;
			    m = (m < 10) ? "0" + m : m;
			    s = (s < 10) ? "0" + s : s;
			    
			    var time = h + ":" + m + ":" + s + " " + session;
			    document.getElementById("MyClockDisplay").innerText = time;
			    document.getElementById("MyClockDisplay").textContent = time;
			    
			    setTimeout(showTime, 1000);
			    
				}

				showTime();
			</script>


		';



	}


	function getMonth($mes){
    switch ($mes) {

      case 1:
        return "Enero";
        
        break;

      case 2:
        return "Febrero";
        
        break;

      case 3:
        return "Marzo";
        
        break;

      case 4:
        return "Abril";
        
        break;

      case 5:
        return "Mayo";
        
        break;

      case 6:
        return "Junio";
        
        break;


      case 7:
        return "Julio";
        
        break;

      case 8:
        return "Agosto";
        
        break;

      case 9:
        return "Septiembre";
        
        break;
            

      case 10:
        return "Octubre";
        
        break;

      case 11:
        return "Noviembre";
        
        break;

      case 12:
        return "Diciembre";
        
        break;
      
    }

}

    function fechaHoraFormateada($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = getMonth(date("n", strtotime($fecha)));
	  	$annio = date("Y", strtotime($fecha));

	  	$hora = date("h:i A", strtotime($fecha));

	  	return '<span class="hoverable">'.$dia." de ".$mes." del ".$annio.'</span>'.' <span class="badge badge-pill badge-info">'.$hora."</span>";
	}

	function fechaHoraFormateadaServer($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = getMonth(date("n", strtotime($fecha)));
	  	$annio = date("Y", strtotime($fecha));

	  	$hora = date("h:i A", strtotime($fecha));

	  	return "<span>".$dia." de ".$mes." del ".$annio." ".$hora."</span>";
	}



	function fechaHoraFormateadaCompactaServer($fecha){
		
		$dia = date("d", strtotime($fecha));
	  	$mes = date("m", strtotime($fecha));
	  	$annio = date("Y", strtotime($fecha));
	  	$hora = date("h:i A", strtotime($fecha));

	  	return $dia."/".$mes."/".$annio." ".$hora;
	}


	function obtenerMesServer( $fecha ) {

	  	$mes = date("m", strtotime($fecha));


	  	return $mes;
	}



  function fechaFormateadaCompacta3( $fecha ){
    $dia = date("d", strtotime($fecha));
      $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
      $annio = date("Y", strtotime($fecha));


      return $dia."/".$mes;
  }

	function fechaFormateada($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = getMonth(date("n", strtotime($fecha)));
	  	$annio = date("Y", strtotime($fecha));


	  	return $dia." de ".$mes." del ".$annio;
	}



	function fechaFormateadaCompacta2($fecha){

    if ( $fecha != null ) {
      $dia = date("d", strtotime($fecha));
      $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
      $annio = date("Y", strtotime($fecha));


      return $dia."/".$mes."/".$annio;
    } else {
      return 'Sin definir';
    }
	  
    
	}


  function fechaHoraFormateadaCompacta2($fecha){
    
    $dia = date("d", strtotime($fecha));
    $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
    $annio = date("Y", strtotime($fecha));
    $hora = date("h:i A", strtotime($fecha));

    return $dia."/".$mes."/".$annio." ".$hora;
  
  }



  function horaFormateadaCompacta2($fecha){
    
    $hora = date("h:i A", strtotime($fecha));

    return $hora;
  
  }


	function fechaFormateadaCompacta($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = date("m", strtotime($fecha));
	  	$annio = date("Y", strtotime($fecha));


	  	return $dia."/".$mes."/".$annio;
	}


	function obtenerAnnioServer( $fecha ) {

	  	$annio = date("Y", strtotime($fecha));


	  	return $annio;
	}



  function generadorFaltas($id_alu_ram){
  	// ALGORITMO DE GENERACION DE INASISTENCIAS

  	require('../includes/conexion.php');

	$sqlAsistencias = "
		SELECT * FROM alu_ram 
		INNER JOIN ciclo ON ciclo.id_ram1 = alu_ram.id_ram3 
		WHERE id_alu_ram = '$id_alu_ram'

	";
	//echo $sqlAsistencias;

	$resultadoAsistencia = mysqli_query($db, $sqlAsistencias);

	$filaAsistencias = mysqli_fetch_assoc($resultadoAsistencia);



	$ini_cic = $filaAsistencias['ini_cic'];
	$id_cic = $filaAsistencias['id_cic'];


	///echo $id_cic;

	$fechaHoy = date('Y-m-d');

	//ar_dump($fechaHoy);
	
	// for ($i= 10; $i <= 16; $i++) { 
	// 	echo date("l", strtotime('2019-05-'.$i))."<br>";
	// }
	//

	$diff = abs(strtotime($ini_cic) - strtotime($fechaHoy));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

	for ($i = 0; $i < $days; $i++) { 
		$fecha = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $ini_cic )))."<br>";
		$dia = gmdate('l', $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $ini_cic )))."<br>";
		//echo $fecha;
		//echo date("l", strtotime($fecha))."<br>";

		$sqlCalendario = "
			SELECT * FROM  evento WHERE fec_eve = '$fecha' AND id_cic2 = '$id_cic'
		";

		$resultadoCalendario = mysqli_query($db, $sqlCalendario);

		$filaCalendario = mysqli_fetch_assoc($resultadoCalendario);

		if ($filaCalendario['tip_eve'] == 'Falta') {
			//echo $fecha;
		}else{
			//ESPERABA ASISTENCIA
			// LA FORMA DE DETERMINAR SI ESPERABA ASISTENCIA Y NO EXISTE ES SI EL NUMERO DE REGISTROS PARA ESE DIA ES 0
			$sqlConsultaAsistencia  = "
				SELECT *
				FROM asistencia
				WHERE id_alu_ram3 = '$id_alu_ram' AND fec_asi = '$fecha' 
			";

			//echo $sqlConsultaAsistencia;

			$resultadoConsultaAsistencia = mysqli_query($db, $sqlConsultaAsistencia);

			$totalConsultaAsistencia = mysqli_num_rows($resultadoConsultaAsistencia);

			if ($totalConsultaAsistencia == 0) {
				//HOY ESPERABA ASISTENCIA PERO NO EXISTEN
				//echo $dia;
				
				
				if (strncasecmp ($dia, 'Monday', 6) == 0) {
					//echo "entre lunes";	
					$sqlMateriasDia = "
							SELECT *
							FROM alu_ram
					    	INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Lunes' 
					        ORDER BY id_sub_hor ASC

					";

					//echo $sqlMateriasDia;

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}



				}else if(strncasecmp ($dia, 'Tuesday', 7) == 0){
					$sqlMateriasDia = "
							SELECT *
					    	FROM alu_ram
					        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Martes' 
					        ORDER BY id_sub_hor ASC

					";

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}
				}else if(strncasecmp ($dia, 'Wednesday', 9) == 0){
					$sqlMateriasDia = "
							SELECT *
					    	FROM alu_ram
					        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Miércoles' 
					        ORDER BY id_sub_hor ASC

					";

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}
				}else if(strncasecmp ($dia, 'Thursday', 8)){
					$sqlMateriasDia = "
							SELECT *
					    	FROM alu_ram
					        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Jueves' 
					        ORDER BY id_sub_hor ASC

					";

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}
				}else if(strncasecmp ($dia, 'Friday', 6)){
					$sqlMateriasDia = "
							SELECT *
					    	FROM alu_ram
					        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Viernes' 
					        ORDER BY id_sub_hor ASC

					";

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}
				}else if(strncasecmp ($dia, 'Saturday', 8)){
					$sqlMateriasDia = "
							SELECT *
					    	FROM alu_ram
					        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Sábado' 
					        ORDER BY id_sub_hor ASC

					";

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}
				}else if(strncasecmp ($dia, 'Sunday', 6)){
					$sqlMateriasDia = "
							SELECT *
					    	FROM alu_ram
					        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
					        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
					        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
					        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Domingo' 
					        ORDER BY id_sub_hor ASC

					";

					$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

					while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
						$id_mat = $filaMateriasDia['id_mat'];


						$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

						$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
					}
				}
			}
		}
	}
  }



  	// PAGOS RECURRENTES
  	function generarPagosRecurrentes($id_alu_ram, $folioPlantel){
    	require('../../includes/conexion.php');

    	$sqlTotalMaterias = "
			SELECT *
			FROM alu_hor
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
			INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
		";

		$resultadoDetallesCarrera = mysqli_query($db, $sqlTotalMaterias);

		$filaDetallesCarrera = mysqli_fetch_assoc($resultadoDetallesCarrera);


		// DATOS CARRERA
		$id_ram = $filaDetallesCarrera['id_ram'];
		$car_reg_ram = $filaDetallesCarrera['car_reg_ram'];
		$bec_max_ram = $filaDetallesCarrera['bec_max_ram'];
		$car_min_ram = $filaDetallesCarrera['car_min_ram'];
		$des_max_ram = $filaDetallesCarrera['des_max_ram'];

		// DATOS CICLO
		$id_cic = $filaDetallesCarrera['id_cic'];

		// DATOS ALUMNO
		$bec_alu_ram = $filaDetallesCarrera['bec_alu_ram'];
		$bec2_alu_ram = $filaDetallesCarrera['bec2_alu_ram'];
		$car_alu_ram = $filaDetallesCarrera['car_alu_ram'];

		$resultadoTotalMaterias = mysqli_query($db, $sqlTotalMaterias);

		$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);

		//echo $totalMaterias;

		// echo $id_ram;


		$sqlCobrosCiclo = "
			SELECT *
			FROM pago_ciclo
			WHERE id_cic3 = '$id_cic'
		";

		$resultadoCobrosCiclo = mysqli_query($db, $sqlCobrosCiclo);

		while( $filaCobrosCiclo = mysqli_fetch_assoc($resultadoCobrosCiclo) ){
			

			if ( $filaCobrosCiclo['tip_pag_cic'] == 'Inscripción' || $filaCobrosCiclo['tip_pag_cic'] == 'Reinscripción' ) {
				// CASO INSCRIPCION/REINSCRIPCION

				$fec_pag = date('Y-m-d');

				$mon_ori_pag = $filaCobrosCiclo['mon_pag_cic']-($filaCobrosCiclo['mon_pag_cic']*$bec_alu_ram);

				$mon_pag = $mon_ori_pag;

				$con_pag = $filaCobrosCiclo['con_pag_cic'];

				$est_pag = 'Pendiente';

				$res_pag = 'Sistema';

				$ini_pag = $filaCobrosCiclo['ini_pag_cic'];

				$fin_pag = $filaCobrosCiclo['fin_pag_cic'];

				$pro_pag = $filaCobrosCiclo['pro_pag_cic'];

				$pri_pag = $filaCobrosCiclo['pri_pag_cic'];

				$tip1_pag = $filaCobrosCiclo['tip1_pag_cic'];

				$int_pag = $filaCobrosCiclo['int_pag_cic'];

				$tip2_pag = $filaCobrosCiclo['tip2_pag_cic'];

				$car_pag = $filaCobrosCiclo['car_pag_cic'];

				$id_alu_ram10 = $id_alu_ram;

				//VALIDACION SI ALU_RAM APLICA PARA DESCUENTO
				if($bec_alu_ram < $bec_max_ram){

					$des_pag = $filaCobrosCiclo['des_pag_cic'];

				}else{

					$des_pag = 0;
				}


				$sqlInsercionPago = "
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, int_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$int_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
				";

				$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
				
				if ( !$resultadoInsercionPago ) {
					
					echo $sqlInsercionPago;
				
				}else {
					// OBTENCION DE id MAXIMO DE PAGO
					// PARA INSERCION DE FOLIO 
					$sqlMaximoPago = "
						SELECT MAX(id_pag) AS maximo
						FROM pago
						WHERE id_alu_ram10 = '$id_alu_ram10'
					";

					$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

					if ( !$resultadoMaximoPago ) {
						
						echo $sqlMaximoPago;
					
					}else {

						$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
						$maximoPago = $filaMaximoPago['maximo'];
						// SQL UPDATE PARA AGREGAR FOLIO

						$fol_pag = $folioPlantel."00".$maximoPago;

						$sqlUpdatePago = "
							UPDATE pago
							SET 
							fol_pag = '$fol_pag'
							WHERE
							id_pag = '$maximoPago'
						";

						$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

						if ( !$resultadoUpdatePago ) {
							echo $sqlMaximoPago;
						}
					}

				}


				// FIN CASO INSCRIPCION/REINSCRIPCION
			}else if( $filaCobrosCiclo['tip_pag_cic'] == 'Colegiatura' ) {
				// CASO COLEGIATURA
				
				//3 CASOS - REGULAR, BAJA, ALTA
				if ( $totalMaterias == $car_alu_ram ) {
					// CASO REGULAR

					$fec_pag = date('Y-m-d');

					$mon_ori_pag = $filaCobrosCiclo['mon_pag_cic']-($filaCobrosCiclo['mon_pag_cic']*$bec2_alu_ram);

					$mon_pag = $mon_ori_pag;

					$con_pag = $filaCobrosCiclo['con_pag_cic'];

					$est_pag = 'Pendiente';

					$res_pag = 'Sistema';

					$ini_pag = $filaCobrosCiclo['ini_pag_cic'];

					$fin_pag = $filaCobrosCiclo['fin_pag_cic'];

					$pro_pag = $filaCobrosCiclo['pro_pag_cic'];

					$pri_pag = $filaCobrosCiclo['pri_pag_cic'];

					$tip1_pag = $filaCobrosCiclo['tip1_pag_cic'];

					$int_pag = $filaCobrosCiclo['int_pag_cic'];

					$tip2_pag = $filaCobrosCiclo['tip2_pag_cic'];

					$car_pag = $filaCobrosCiclo['car_pag_cic'];

					$id_alu_ram10 = $id_alu_ram;

					//VALIDACION SI ALU_RAM APLICA PARA DESCUENTO
					if( $bec2_alu_ram < $bec_max_ram ){

						$des_pag = $filaCobrosCiclo['des_pag_cic'];

					}else{

						$des_pag = 0;
					}


					$sqlInsercionPago = "
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, int_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$int_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
				";

					$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
					
					if ( !$resultadoInsercionPago ) {
					
						echo $sqlInsercionPago;
					
					}else {
						// OBTENCION DE id MAXIMO DE PAGO
						// PARA INSERCION DE FOLIO 
						$sqlMaximoPago = "
							SELECT MAX(id_pag) AS maximo
							FROM pago
							WHERE id_alu_ram10 = '$id_alu_ram10'
						";

						$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

						if ( !$resultadoMaximoPago ) {
							
							echo $sqlMaximoPago;
						
						}else {

							$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
							$maximoPago = $filaMaximoPago['maximo'];
							// SQL UPDATE PARA AGREGAR FOLIO

							$fol_pag = $folioPlantel."00".$maximoPago;

							$sqlUpdatePago = "
								UPDATE pago
								SET 
								fol_pag = '$fol_pag'
								WHERE
								id_pag = '$maximoPago'
							";

							$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

							if ( !$resultadoUpdatePago ) {
								echo $sqlMaximoPago;
							}
						}

					}



					//FIN CASO REGULAR
				}else if ( $totalMaterias < $car_alu_ram ) {
					// CASO CARGA BAJA

					$fec_pag = date('Y-m-d');

					$mon_ori_pag =  $filaCobrosCiclo['mon_pag_cic']-($filaCobrosCiclo['mon_pag_cic']*$bec2_alu_ram);
					
					$mon_ori_pag = $mon_ori_pag/$car_alu_ram;
					
					$mon_ori_pag = ( $mon_ori_pag*$totalMaterias );
					
					$mon_ori_pag = $mon_ori_pag+( $mon_ori_pag*$car_min_ram );

					$mon_pag = $mon_ori_pag;

					$con_pag = $filaCobrosCiclo['con_pag_cic'];

					$est_pag = 'Pendiente';

					$res_pag = 'Sistema';

					$ini_pag = $filaCobrosCiclo['ini_pag_cic'];

					$fin_pag = $filaCobrosCiclo['fin_pag_cic'];

					$pro_pag = $filaCobrosCiclo['pro_pag_cic'];

					$pri_pag = $filaCobrosCiclo['pri_pag_cic'];

					$tip1_pag = $filaCobrosCiclo['tip1_pag_cic'];

					$int_pag = $filaCobrosCiclo['int_pag_cic'];

					$tip2_pag = $filaCobrosCiclo['tip2_pag_cic'];

					$car_pag = $filaCobrosCiclo['car_pag_cic'];

					$id_alu_ram10 = $id_alu_ram;

					//VALIDACION SI ALU_RAM APLICA PARA DESCUENTO
					if($bec2_alu_ram < $bec_max_ram){

						$des_pag = $filaCobrosCiclo['des_pag_cic'];

					}else{

						$des_pag = 0;
					}


					$sqlInsercionPago = "
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, int_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$int_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
				";

					$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
					
					if ( !$resultadoInsercionPago ) {
					
						echo $sqlInsercionPago;
					
					}else {
						// OBTENCION DE id MAXIMO DE PAGO
						// PARA INSERCION DE FOLIO 
						$sqlMaximoPago = "
							SELECT MAX(id_pag) AS maximo
							FROM pago
							WHERE id_alu_ram10 = '$id_alu_ram10'
						";

						$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

						if ( !$resultadoMaximoPago ) {
							
							echo $sqlMaximoPago;
						
						}else {

							$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
							$maximoPago = $filaMaximoPago['maximo'];
							// SQL UPDATE PARA AGREGAR FOLIO

							$fol_pag = $folioPlantel."00".$maximoPago;

							$sqlUpdatePago = "
								UPDATE pago
								SET 
								fol_pag = '$fol_pag'
								WHERE
								id_pag = '$maximoPago'
							";

							$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

							if ( !$resultadoUpdatePago ) {
								echo $sqlMaximoPago;
							}
						}

					}



					// FIN CASO CARGA BAJA
				}else if ( $totalMaterias > $car_alu_ram ) {
					// CASO CARGA ALTA


					$fec_pag = date('Y-m-d');

					$mon_ori_pag =  $filaCobrosCiclo['mon_pag_cic']-($filaCobrosCiclo['mon_pag_cic']*$bec2_alu_ram);
					
					$mon_ori_pag = $mon_ori_pag/$car_alu_ram;
					
					$mon_ori_pag = ( $mon_ori_pag*$totalMaterias );
					
					$mon_ori_pag = $mon_ori_pag-( $mon_ori_pag*$des_max_ram );

					$mon_pag = $mon_ori_pag;

					$con_pag = $filaCobrosCiclo['con_pag_cic'];

					$est_pag = 'Pendiente';

					$res_pag = 'Sistema';

					$ini_pag = $filaCobrosCiclo['ini_pag_cic'];

					$fin_pag = $filaCobrosCiclo['fin_pag_cic'];

					$pro_pag = $filaCobrosCiclo['pro_pag_cic'];

					$pri_pag = $filaCobrosCiclo['pri_pag_cic'];

					$tip1_pag = $filaCobrosCiclo['tip1_pag_cic'];

					$int_pag = $filaCobrosCiclo['int_pag_cic'];

					$tip2_pag = $filaCobrosCiclo['tip2_pag_cic'];

					$car_pag = $filaCobrosCiclo['car_pag_cic'];

					$id_alu_ram10 = $id_alu_ram;

					//VALIDACION SI ALU_RAM APLICA PARA DESCUENTO
					if($bec2_alu_ram < $bec_max_ram){

						$des_pag = $filaCobrosCiclo['des_pag_cic'];

					}else{

						$des_pag = 0;
					}


					$sqlInsercionPago = "
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, int_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$int_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
				";

					$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
					
					if ( !$resultadoInsercionPago ) {
					
						echo $sqlInsercionPago;
					
					}else {
						// OBTENCION DE id MAXIMO DE PAGO
						// PARA INSERCION DE FOLIO 
						$sqlMaximoPago = "
							SELECT MAX(id_pag) AS maximo
							FROM pago
							WHERE id_alu_ram10 = '$id_alu_ram10'
						";

						$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

						if ( !$resultadoMaximoPago ) {
							
							echo $sqlMaximoPago;
						
						}else {

							$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
							$maximoPago = $filaMaximoPago['maximo'];
							// SQL UPDATE PARA AGREGAR FOLIO

							$fol_pag = $folioPlantel."00".$maximoPago;

							$sqlUpdatePago = "
								UPDATE pago
								SET 
								fol_pag = '$fol_pag'
								WHERE
								id_pag = '$maximoPago'
							";

							$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

							if ( !$resultadoUpdatePago ) {
								echo $sqlMaximoPago;
							}
						}

					}

					// FIN CASO CARGA ALTA
				}
				

				// FIN CASO COLEGIATURA
			}else {
				// CASO OTROS

				$fec_pag = date('Y-m-d');

					$mon_ori_pag = $filaCobrosCiclo['mon_pag_cic'];

					$mon_pag = $mon_ori_pag;

					$con_pag = $filaCobrosCiclo['con_pag_cic'];

					$est_pag = 'Pendiente';

					$res_pag = 'Sistema';

					$ini_pag = $filaCobrosCiclo['ini_pag_cic'];

					$fin_pag = $filaCobrosCiclo['fin_pag_cic'];

					$pro_pag = $filaCobrosCiclo['pro_pag_cic'];

					$pri_pag = $filaCobrosCiclo['pri_pag_cic'];

					$tip1_pag = $filaCobrosCiclo['tip1_pag_cic'];

					$int_pag = $filaCobrosCiclo['int_pag_cic'];

					$tip2_pag = $filaCobrosCiclo['tip2_pag_cic'];

					$car_pag = $filaCobrosCiclo['car_pag_cic'];

					$id_alu_ram10 = $id_alu_ram;

					$des_pag = $filaCobrosCiclo['des_pag_cic'];
					


					$sqlInsercionPago = "
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, int_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$int_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
				";

					$resultadoInsercionPago = mysqli_query($db, $sqlInsercionPago);
					
					if ( !$resultadoInsercionPago ) {
					
						echo $sqlInsercionPago;
					
					}else {
						// OBTENCION DE id MAXIMO DE PAGO
						// PARA INSERCION DE FOLIO 
						$sqlMaximoPago = "
							SELECT MAX(id_pag) AS maximo
							FROM pago
							WHERE id_alu_ram10 = '$id_alu_ram10'
						";

						$resultadoMaximoPago = mysqli_query($db, $sqlMaximoPago);

						if ( !$resultadoMaximoPago ) {
							
							echo $sqlMaximoPago;
						
						}else {

							$filaMaximoPago = mysqli_fetch_assoc( $resultadoMaximoPago );
							$maximoPago = $filaMaximoPago['maximo'];
							// SQL UPDATE PARA AGREGAR FOLIO

							$fol_pag = $folioPlantel."00".$maximoPago;

							$sqlUpdatePago = "
								UPDATE pago
								SET 
								fol_pag = '$fol_pag'
								WHERE
								id_pag = '$maximoPago'
							";

							$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

							if ( !$resultadoUpdatePago ) {
								echo $sqlMaximoPago;
							}
						}

					}
				
				// FIN CASO OTROS
			}
		
		//FIN WHILE
		}
    	
	}
	// FIN FUNCION PAGOS RECURRENTES


	// CAJA SMART
	function cajaSmartServer($id_alu_ram, $abono, $tip_abo_pag, $nomResponsable){
		require('../../includes/conexion.php');

		$pag_pag = date('Y-m-d');
		$fechaHoy = date('Y-m-d');


		$sql = "
	        SELECT *
	        FROM pago 
	        WHERE id_alu_ram10 = '$id_alu_ram' AND est_pag = 'Pendiente'
	        ORDER BY ini_pag ASC, pri_pag ASC, id_pag ASC
	    ";

	    //echo $sql;

		$resultado = mysqli_query($db, $sql);
		$resultadoCobros = mysqli_query($db, $sql);

		

		if ( ($resultado) && ($resultadoCobros) ) {
			
			$totalResultados = mysqli_num_rows($resultado);


			if ($totalResultados > 0) {
			//COBROS VIEJOS

				while( $filaCobros = mysqli_fetch_assoc( $resultadoCobros ) ){
					
					if( $abono >= $filaCobros['mon_pag'] ){
					//SI ABONO CUBRE O ES IGUAL A UN COBRO

						//VARIABLES PRINCIPALES
						$id_pag = $filaCobros['id_pag'];

						$mon_pag = $filaCobros['mon_pag'];



						// VERIFICACION SI EXISTEN ABONOS PREVIOS
						$sqlPagado = "
							SELECT *
							FROM abono_pago
							WHERE id_pag1 = '$id_pag'
						";

						$resultadoPagado = mysqli_query( $db, $sqlPagado );

						if ( !$resultadoPagado ) {
						// VALIDACION EJECUCION DE CONSULTA
							echo $sqlPagado;

						}else {
						// VALIDACION CORRECTA
							$rowsPagados = mysqli_num_rows( $resultadoPagado );

							if ( $rowsPagados > 0 ) {
							//SI EXISTEN ABONOS PREVIOS

								$sqlTotalPagado = "
									SELECT SUM(mon_abo_pag) AS totalPagado
									FROM abono_pago
									WHERE id_pag1 = '$id_pag'
								";

								$resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );

								if ( $resultadoTotalPagado ) {
									
									$filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );

									$tot_pag = $filaTotalPagado['totalPagado'];

								}else {
									echo $sqlTotalPagado;
								}

							}else {
							// SI NO EXISTEN ABONOS PREVIOS
								$tot_pag = 0;
							}
						}


						$adeudo = $mon_pag;
						// INSERCION DE ABONO

						$sqlInsercionAbono = "
							INSERT INTO abono_pago( mon_abo_pag, fec_abo_pag, tip_abo_pag, res_abo_pag, id_pag1 ) 
							VALUES( '$adeudo', '$fechaHoy', '$tip_abo_pag', '$nomResponsable', '$id_pag' )

						";

						$resultadoInsercionAbono = mysqli_query( $db, $sqlInsercionAbono );

						if( $resultadoInsercionAbono ){
						//VALIDACION INSERCION ABONO (LIQUIDACION ADEUDO)
							//INSERCION DE HISTORIAL
							// VARIABLES

							$con_his_pag = "Abono por $".$adeudo." realizado por ".$nomResponsable." la fecha del ".fechaFormateadaCompacta($fechaHoy)." liquidando el saldo pendiente.";

							$fec_his_pag = $fechaHoy;

							$res_his_pag = $nomResponsable;

							$est_his_pag = 'Pendiente';

							$tip_his_pag = "Liquidación";

							$med_his_pag = "Sistema";


							// INSERCION HISTORIAL
							$sqlInsercionHistorial = "
								INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
								VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
							";

							$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );



							if ( !$resultadoInsercionHistorial ) {
								echo $sqlInsercionHistorial;
							}else {

								// UPDATE DEL PAGO
								$pag_pag = $fechaHoy;
								$est_pag = 'Pagado';
								$mon_pag = 0;

								$sqlUpdatePago = "
									UPDATE pago
									SET
									est_pag = '$est_pag',
									mon_pag = '$mon_pag',
									pag_pag = '$pag_pag'

									WHERE id_pag = '$id_pag'
								";

								$resultadoUpdatePago = mysqli_query($db, $sqlUpdatePago);

								if ( !$resultadoUpdatePago ) {

									echo $sqlUpdatePago;

								}else{
									
									if ( $tip_abo_pag == 'Dinero Digital' ) {
						
										$sqlAlumno = "
											SELECT *
											FROM pago
											INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
											INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
											WHERE id_pag = '$id_pag'
										";

										$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

										$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

										$id_alu = $filaAlumno['id_alu'];
										$sal_alu = $filaAlumno['sal_alu'];

										$saldoFinal = $sal_alu - $adeudo;

										$sqlUpdateAlumno = "
											UPDATE alumno
											SET
											sal_alu = '$saldoFinal'
											WHERE 
											id_alu = '$id_alu'
										";

										$resultadoUpdateAlumno = mysqli_query( $db, $sqlUpdateAlumno );

										if ( $resultadoUpdateAlumno ) {
											
											// HISTORIAL SALDO
											$con_his_sal = "Se egresó saldo digital por la cantidad de $".$adeudo;

											$fec_his_sal = $fechaHoy;

											$res_his_sal = $nomResponsable;

											$est_his_sal = 'Pendiente';

											$tip_his_sal = "Egreso";

											$id_alu3 = $id_alu;


											// INSERCION HISTORIAL
											$sqlInsercionHistorialSaldo = "
												INSERT INTO historial_saldo ( con_his_sal, fec_his_sal, res_his_sal, est_his_sal, tip_his_sal,  id_alu3 ) 
												VALUES( '$con_his_sal', '$fec_his_sal', '$res_his_sal', '$est_his_sal', '$tip_his_sal', '$id_alu3' )
											";

											$resultadoInsercionHistorialSaldo = mysqli_query( $db, $sqlInsercionHistorialSaldo );

											if ( !$resultadoInsercionHistorialSaldo ) {
												
												echo $sqlInsercionHistorialSaldo;

											}


										} else {
											echo $sqlUpdateAlumno;
										}
									}

									$abono = $abono - $adeudo;
								}

								// FIN UPDATE PAGO
							}



						}else {
							echo $sqlInsercionAbono;
						}





					}else if( ($abono > 0) && ($abono < $filaCobros['mon_pag']) ){
					// SI EL ABONO ESTA POR DEBAJO DEL MONTO ADEUDO PERO ES MAYOR QUE CERO
						
						//VARIABLES PRINCIPALES
						$id_pag = $filaCobros['id_pag'];

						$mon_pag = $filaCobros['mon_pag'];



						// VERIFICACION SI EXISTEN ABONOS PREVIOS
						$sqlPagado = "
							SELECT *
							FROM abono_pago
							WHERE id_pag1 = '$id_pag'
						";

						$resultadoPagado = mysqli_query( $db, $sqlPagado );

						if ( !$resultadoPagado ) {
						// VALIDACION EJECUCION DE CONSULTA
							echo $sqlPagado;

						}else {
						// VALIDACION CORRECTA
							$rowsPagados = mysqli_num_rows( $resultadoPagado );

							if ( $rowsPagados > 0 ) {
							//SI EXISTEN ABONOS PREVIOS

								$sqlTotalPagado = "
									SELECT SUM(mon_abo_pag) AS totalPagado
									FROM abono_pago
									WHERE id_pag1 = '$id_pag'
								";

								$resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );

								if ( $resultadoTotalPagado ) {
									
									$filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );

									$tot_pag = $filaTotalPagado['totalPagado'];

								}else {
									echo $sqlTotalPagado;
								}

							}else {
							// SI NO EXISTEN ABONOS PREVIOS
								$tot_pag = 0;
							}
						}


						$adeudo = $mon_pag;
						// INSERCION DE ABONO

						
						//VALIDACION SI EL ABONO LIQUIDA O PARCIALIZA EL ADEUDO

						if ( $abono >= $adeudo) {
						// EL ABONO LIQUIDA ADEUDO

							$sqlInsercionAbono = "
								INSERT INTO abono_pago( mon_abo_pag, fec_abo_pag, tip_abo_pag, res_abo_pag, id_pag1 ) 
								VALUES( '$adeudo', '$fechaHoy', '$tip_abo_pag', '$nomResponsable', '$id_pag' )
							";

							$resultadoInsercionAbono = mysqli_query( $db, $sqlInsercionAbono );

							if( $resultadoInsercionAbono ){
						
								//INSERCION DE HISTORIAL
								// VARIABLES

								$con_his_pag = "Abono por $".$adeudo." realizado por ".$nomResponsable." la fecha del ".fechaFormateadaCompacta($fechaHoy)." liquidando el saldo pendiente.";

								$fec_his_pag = $fechaHoy;

								$res_his_pag = $nomResponsable;

								$est_his_pag = 'Pendiente';

								$tip_his_pag = "Liquidación";

								$med_his_pag = "Sistema";


								// INSERCION HISTORIAL
								$sqlInsercionHistorial = "
									INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
									VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
								";

								$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

								if ( !$resultadoInsercionHistorial ) {
									echo $sqlInsercionHistorial;
								}else {

									// UPDATE DEL PAGO
									$pag_pag = $fechaHoy;
									$est_pag = 'Pagado';
									$mon_pag = 0;

									$sqlUpdatePago = "
										UPDATE pago
										SET
										est_pag = '$est_pag',
										mon_pag = '$mon_pag',
										pag_pag = '$pag_pag'

										WHERE id_pag = '$id_pag'
									";

									$resultadoUpdatePago = mysqli_query($db, $sqlUpdatePago);

									if ( !$resultadoUpdatePago ) {

										echo $sqlUpdatePago;

									}else{

										if ( $tip_abo_pag == 'Dinero Digital' ) {
						
											$sqlAlumno = "
												SELECT *
												FROM pago
												INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
												INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
												WHERE id_pag = '$id_pag'
											";

											$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

											$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

											$id_alu = $filaAlumno['id_alu'];
											$sal_alu = $filaAlumno['sal_alu'];

											$saldoFinal = $sal_alu - $adeudo;

											$sqlUpdateAlumno = "
												UPDATE alumno
												SET
												sal_alu = '$saldoFinal'
												WHERE 
												id_alu = '$id_alu'
											";

											$resultadoUpdateAlumno = mysqli_query( $db, $sqlUpdateAlumno );

											if ( $resultadoUpdateAlumno ) {
												
												// HISTORIAL SALDO
												$con_his_sal = "Se egresó saldo digital por la cantidad de $".$adeudo;

												$fec_his_sal = $fechaHoy;

												$res_his_sal = $nomResponsable;

												$est_his_sal = 'Pendiente';

												$tip_his_sal = "Egreso";

												$id_alu3 = $id_alu;


												// INSERCION HISTORIAL
												$sqlInsercionHistorialSaldo = "
													INSERT INTO historial_saldo ( con_his_sal, fec_his_sal, res_his_sal, est_his_sal, tip_his_sal,  id_alu3 ) 
													VALUES( '$con_his_sal', '$fec_his_sal', '$res_his_sal', '$est_his_sal', '$tip_his_sal', '$id_alu3' )
												";

												$resultadoInsercionHistorialSaldo = mysqli_query( $db, $sqlInsercionHistorialSaldo );

												if ( !$resultadoInsercionHistorialSaldo ) {
													
													echo $sqlInsercionHistorialSaldo;

												}


											} else {
												echo $sqlUpdateAlumno;
											}
										}

										$abono = $abono - $adeudo;
									}

									// FIN UPDATE PAGO
								}


							}else {
								echo $sqlInsercionAbono;
							}

							


						}else{
						// ABONO PARCIALIZA EL ADEUDO


							$sqlInsercionAbono = "
								INSERT INTO abono_pago( mon_abo_pag, fec_abo_pag, tip_abo_pag, res_abo_pag, id_pag1 ) 
								VALUES( '$abono', '$fechaHoy', '$tip_abo_pag', '$nomResponsable', '$id_pag' )
							";

							$resultadoInsercionAbono = mysqli_query( $db, $sqlInsercionAbono );

							if( $resultadoInsercionAbono ){
						
								//INSERCION DE HISTORIAL
								// VARIABLES

								$con_his_pag = "Abono por $".$abono." realizado por ".$nomResponsable." la fecha del ".fechaFormateadaCompacta($fechaHoy).".";

								$fec_his_pag = $fechaHoy;

								$res_his_pag = $nomResponsable;

								$est_his_pag = 'Pendiente';

								$tip_his_pag = "Parcialidad";

								$med_his_pag = "Sistema";


								// INSERCION HISTORIAL
								$sqlInsercionHistorial = "
									INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
									VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
								";

								$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

								if ( !$resultadoInsercionHistorial ) {
									echo $sqlInsercionHistorial;
								}else {

									// UPDATE DEL PAGO
									$pag_pag = $fechaHoy;
									$est_pag = 'Pendiente';
									$mon_pag = $mon_pag - $abono;

									$sqlUpdatePago = "
										UPDATE pago
										SET
										est_pag = '$est_pag',
										mon_pag = '$mon_pag'

										WHERE id_pag = '$id_pag'
									";

									$resultadoUpdatePago = mysqli_query($db, $sqlUpdatePago);

									if ( !$resultadoUpdatePago ) {

										echo $sqlUpdatePago;

									}else{

										if ( $tip_abo_pag == 'Dinero Digital' ) {
						
											$sqlAlumno = "
												SELECT *
												FROM pago
												INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
												INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
												WHERE id_pag = '$id_pag'
											";

											$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

											$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

											$id_alu = $filaAlumno['id_alu'];
											$sal_alu = $filaAlumno['sal_alu'];

											$saldoFinal = $sal_alu - $abono;

											$sqlUpdateAlumno = "
												UPDATE alumno
												SET
												sal_alu = '$saldoFinal'
												WHERE 
												id_alu = '$id_alu'
											";

											$resultadoUpdateAlumno = mysqli_query( $db, $sqlUpdateAlumno );

											if ( $resultadoUpdateAlumno ) {
												
												// HISTORIAL SALDO
												$con_his_sal = "Se egresó saldo digital por la cantidad de $".$abono;

												$fec_his_sal = $fechaHoy;

												$res_his_sal = $nomResponsable;

												$est_his_sal = 'Pendiente';

												$tip_his_sal = "Egreso";

												$id_alu3 = $id_alu;


												// INSERCION HISTORIAL
												$sqlInsercionHistorialSaldo = "
													INSERT INTO historial_saldo ( con_his_sal, fec_his_sal, res_his_sal, est_his_sal, tip_his_sal,  id_alu3 ) 
													VALUES( '$con_his_sal', '$fec_his_sal', '$res_his_sal', '$est_his_sal', '$tip_his_sal', '$id_alu3' )
												";

												$resultadoInsercionHistorialSaldo = mysqli_query( $db, $sqlInsercionHistorialSaldo );

												if ( !$resultadoInsercionHistorialSaldo ) {
													
													echo $sqlInsercionHistorialSaldo;

												}


											} else {
												echo $sqlUpdateAlumno;
											}
										}
										$abono = 0;
									}

									// FIN UPDATE PAGO
								}


							}else {
								echo $sqlInsercionAbono;
							}

						}

					
					// FIN SI EL ABONO ESTA POR DEBAJO DEL MONTO ADEUDO PERO ES MAYOR QUE CERO
					}
				
				//FIN WHILE
				}

				echo "Exito";

			// FIN COBROS PASADOS
			}


			
		}else{
			echo "error, verificar consulta!";
			//echo $sql;
		}
	}
	// FIN CAJA SMART
    



    // GENERADOR DESCUENTOS O RECARGOS

    function generadorDescuentosRecargos($id_alu_ram, $whatsappPlantel, $smsPlantel, $emailPlantel, $client){
		require('../../includes/conexion.php');
		
		$sqlPago = "
			SELECT * 
	        FROM pago 
	        INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
	        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
	        WHERE id_alu_ram10 = '$id_alu_ram' AND est_pag = 'Pendiente'
	        
	        ORDER BY est_pag DESC, ini_pag ASC, pri_pag ASC, id_pag ASC
		";

		//echo $sqlPago;

	    $resultadoPago = mysqli_query( $db, $sqlPago );

	    //$fechaHoy = '2020-01-15';
	    $fechaHoy = date('Y-m-d');


	    while ( $filaPago = mysqli_fetch_assoc( $resultadoPago ) ) {
	  		// VARIABLES RELEVANTES
	    	$id_pag = $filaPago['id_pag'];
	    	$cor_alu = $filaPago['cor_alu'];
	    	$nom_alu = $filaPago['nom_alu']." ".$filaPago['app_alu'];
	    	$tel_alu = $filaPago['tel_alu'];

	    	// VALIDADOR SI EXISTEN FECHAS
	    	// SOLAMENTE COBROS AVANZADOS
	    	if ( ($filaPago['ini_pag'] != NULL ) && ( $filaPago['fin_pag'] != NULL )  && ( $filaPago['fin_pag'] != NULL ) ) {
	    	 	
	    		// DESCUENTOS
		    	if ( $fechaHoy <= $filaPago['pro_pag'] ) {
		    	//IF DESCUENTO


		    		// VALIDACION SI EXISTEN HISTORIALES ASOCIADOS A PAGO
					$sqlValidacionHistorialInicial = "
						SELECT *
						FROM historial_pago
						WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'Descuento'
					";
					

					$resultadoValidacionHistorialInicial = mysqli_query( $db, $sqlValidacionHistorialInicial );

					if ( $resultadoValidacionHistorialInicial ) {

						$validacionHistorialInicial = mysqli_num_rows( $resultadoValidacionHistorialInicial );

						if ( ($validacionHistorialInicial == 0) ) {

							if ( $filaPago['tip1_pag'] == 'Porcentual' ) {
				    		// CASO PORCENTUAL
				    			$descuento = ($filaPago['mon_ori_pag'])*( $filaPago['des_pag']/100 );
				    			$nuevoMonto = $filaPago['mon_ori_pag'] - $descuento;

				    		// FIN CASO PORCENTUAL
				    		}else if( $filaPago['tip1_pag'] == 'Monetario' ){
				    		// CASO MONETARIO
				    			$descuento = $filaPago['des_pag'];
				    			$nuevoMonto = $filaPago['mon_ori_pag'] - $descuento;
				    		// FIN CASO MONETARIO
				    		}


				    		$sqlUpdatePago = "
								UPDATE pago
								SET 
								mon_pag = '$nuevoMonto'
								WHERE id_pag = '$id_pag'
			    			";

			    			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

			    			if ( !$resultadoUpdatePago ) {

			    				echo $sqlUpdatePago;
			    			}

			    			// VALIDACION SI EXISTEN HISTORIALES ASOCIADOS A PAGO
		    				$sqlValidacionHistorial = "
								SELECT *
								FROM historial_pago
								WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'Descuento'
		    				";

		    				$resultadoValidacionHistorial = mysqli_query( $db, $sqlValidacionHistorial );

		    				if ( $resultadoValidacionHistorial ) {

		    					$validacionHistorial = mysqli_num_rows( $resultadoValidacionHistorial );

		    					$fecha1 = $fechaHoy;
								$fecha2 = $filaPago['pro_pag'];
								$date1 = date_create( $fecha1 );
								$date2 = date_create( $fecha2 );
								$diff = date_diff( $date1, $date2 );
								$diferencia = $diff->format("%a");
								
								// SEGUNDA CONDICIONANTE O CON OR
								//|| ($validacionHistorial == 1 && $diferencia <= 3)
		    					if ( ($validacionHistorial == 0 && $diferencia <= 7) ) {
			    				// VALIDACION SI EXISTE HISTORIAL (SISTEMA, EMAIL, WHATS, SMS) 7 DIAS
			    					//HISTORIAL

				    				$con_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

				    				$men_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

									$fec_his_pag = $fechaHoy;

									$res_his_pag = 'Sistema';

									$est_his_pag = 'Pendiente';

									$tip_his_pag = "Descuento";

									$med_his_pag = "Sistema";


									// INSERCION HISTORIAL
									$sqlInsercionHistorial = "
										INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
										VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
									";



									$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

									if ( !$resultadoInsercionHistorial ) {
										echo $sqlInsercionHistorial;
									}
									// FIN HISTORIAL

									if ( ($whatsappPlantel == 'Activo') && ($tel_alu != NULL) ) {
									// IF WHATSAPP

										//echo "Envio Whatsapp - Caso Pasado ";
					    	// 			$con_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

					    	// 			$men_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['pro_pag'])."!";

										// $fec_his_pag = $fechaHoy;

										// $res_his_pag = 'Sistema';

										// $est_his_pag = 'Pendiente';

										// $tip_his_pag = "Descuento";

										// $med_his_pag = "Whatsapp";


										// // INSERCION HISTORIAL
										// $sqlInsercionHistorialWhatsapp = "
										// 	INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
										// 	VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
										// ";



										// $resultadoInsercionHistorialWhatsapp = mysqli_query( $db, $sqlInsercionHistorialWhatsapp );

										// if ( !$resultadoInsercionHistorialWhatsapp ) {
										// 	echo $sqlInsercionHistorialWhatsapp;
										// }else {
											
										// 	$client->messages->create('whatsapp:+525518292351', // to
										// 	       array(
										// 	           'from' => 'whatsapp:+14155238886',
										// 	           'body' => $men_his_pag
										// 	       )
										// 	);

										// }
									// FIN IF WHATSAPP
									}

									if ( ($smsPlantel == 'Activo') && ($tel_alu != NULL) ) {
									// IF SMS

										//echo "Envio SMS - Caso Pasado ";
					    	// 			$con_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

					    	// 			$men_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['pro_pag'])."!";

										// $fec_his_pag = $fechaHoy;

										// $res_his_pag = 'Sistema';

										// $est_his_pag = 'Pendiente';

										// $tip_his_pag = "Descuento";

										// $med_his_pag = "SMS";


										// // INSERCION HISTORIAL
										// $sqlInsercionHistorialSms = "
										// 	INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
										// 	VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
										// ";



										// $resultadoInsercionHistorialSms = mysqli_query( $db, $sqlInsercionHistorialSms );

										// if ( !$resultadoInsercionHistorialSms ) {
										// 	echo $sqlInsercionHistorialSms;
										// }else {
										// 	$client->messages->create(
										// 	    // the number you'd like to send the message to
										// 	    '+525518292351',
										// 	    array(
										// 	        // A Twilio phone number you purchased at twilio.com/console
										// 	        'from' => '+13343261337',
										// 	        // the body of the text message you'd like to send
										// 	        'body' => $men_his_pag
										// 	    )
										// 	);
										// }

									// FIN IF SMS
									}

									if( $emailPlantel == 'Activo'){
									// IF CORREO
					    				$con_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

					    				$men_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['pro_pag'])."!";

										$fec_his_pag = $fechaHoy;

										$res_his_pag = 'Sistema';

										$est_his_pag = 'Pendiente';

										$tip_his_pag = "Descuento";

										$med_his_pag = "Correo";


										// INSERCION HISTORIAL
										$sqlInsercionHistorialCorreo = "
											INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
											VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
										";



										$resultadoInsercionHistorialCorreo = mysqli_query( $db, $sqlInsercionHistorialCorreo );

										if ( !$resultadoInsercionHistorialCorreo ) {
											echo $sqlInsercionHistorialCorreo;
										}else {
											
											$destino = $cor_alu;
										    $asunto = $men_his_pag;
										    $mensaje = $men_his_pag;


										    mail($destino, $asunto, $mensaje);
										}
									// FIN IF CORREO

									}




									// FIN HISTORIAL

			    				// FIN VALIDACION SI EXISTE HISTORIAL (SISTEMA, EMAIL, WHATS, SMS)
			    				}




		    				}else {
		    					echo $sqlValidacionHistorial;
		    				}


						}

					}


		    		

		    		
	    			



		    	// FIN IF DESCUENTO
		    	}else if ( ($fechaHoy > $filaPago['pro_pag']) && ($fechaHoy <= $filaPago['fin_pag']) ) {
		    	// IF PRESENTE

		    		// VALIDACION SI EXISTEN HISTORIALES ASOCIADOS A PAGO
					$sqlValidacionHistorialInicial = "
						SELECT *
						FROM historial_pago
						WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'N/A'
					";
					

					$resultadoValidacionHistorialInicial = mysqli_query( $db, $sqlValidacionHistorialInicial );

					if ( $resultadoValidacionHistorialInicial ) {

						$validacionHistorialInicial = mysqli_num_rows( $resultadoValidacionHistorialInicial );

						if ( ($validacionHistorialInicial == 0) ) {

							//SENTENCIA PARA VALIDAR SI EXISTE ALGUN ABONO ASOCIADO AL PAGO PARA APLICAR CARGO AL ADEUDO
							$sqlBuscaAbono = " SELECT * FROM abono_pago WHERE id_pag1 = '$id_pag'";
							$sqlBuscarCondonacion="SELECT * FROM condonacion_pago WHERE id_pag2 = '$id_pag'";
							$resultadoCondonacion= mysqli_query($db, $sqlBuscarCondonacion);
							$resultadoBuscaAbono= mysqli_query( $db, $sqlBuscaAbono);


							// RECARGOS

							$sqlTotalRecargo = "
								SELECT SUM( mon_rec_pag ) AS totalRecargo
								FROM recargo_pago
								WHERE id_pag5 = '$id_pag'
							";

							$resultadoTotalRecargo = mysqli_query( $db, $sqlTotalRecargo );

							$filaTotalRecargo = mysqli_fetch_assoc( $resultadoTotalRecargo );

							$totalRecargo = $filaTotalRecargo['totalRecargo'];

							if ( $totalRecargo == NULL ) {
								$totalRecargo = 0;
							}

							// FIN RECARGOS
							
							if( $resultadoBuscaAbono ){
								
								$totalBuscaAbono = mysqli_num_rows( $resultadoBuscaAbono );
								$totalCondonacion = mysqli_num_rows($resultadoCondonacion);

								if ( ($totalBuscaAbono > 0) || ($totalCondonacion > 0) ) {
									
									$sqlTotalAbonado = "
										SELECT SUM(mon_abo_pag) AS totalAbonado
										FROM abono_pago
										WHERE id_pag1 = '$id_pag'
										UNION
										SELECT SUM(can_con_pag) AS totalAbonado
										FROM condonacion_pago
										WHERE id_pag2 = '$id_pag' AND est_con_pag = 'Aprobado'

									";

									$resultadoTotalAbonado = mysqli_query( $db, $sqlTotalAbonado );

									if ( $resultadoTotalAbonado ) {
										
										$totalAbonado = 0;
										
										while( $filaTotalAbonado = mysqli_fetch_assoc( $resultadoTotalAbonado ) ){
											$totalAbonado = $totalAbonado + $filaTotalAbonado['totalAbonado'];
										}

										$nuevoMonto = $filaPago['mon_ori_pag'] + $totalRecargo - $totalAbonado;
									}
									


								}else{

									$nuevoMonto = $filaPago['mon_ori_pag'] + $totalRecargo;

								}

							}

				    		$sqlUpdatePago = "
								UPDATE pago
								SET 
								mon_pag = '$nuevoMonto'
								WHERE id_pag = '$id_pag'
			    			";

			    			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

			    			if ( $resultadoUpdatePago ) {

			    				// VALIDACION SI EXISTEN HISTORIALES ASOCIADOS A PAGO
			    				$sqlValidacionHistorial = "
									SELECT *
									FROM historial_pago
									WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'N/A'
			    				";


			    				$resultadoValidacionHistorial = mysqli_query( $db, $sqlValidacionHistorial );

			    				if ( $resultadoValidacionHistorial ) {

			    					$validacionHistorial = mysqli_num_rows( $resultadoValidacionHistorial );

			    					if ( ($validacionHistorial == 0) ) {
				    					//HISTORIAL

			    						// VALIDADOR SI HUBO DESCUENTO PREVIO
				    					if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
					    					$descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

					    					$con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
					    				}else{

					    					$con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
					    				}

					    				
					    				$men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";


										$fec_his_pag = $fechaHoy;

										$res_his_pag = 'Sistema';

										$est_his_pag = 'Pendiente';

										$tip_his_pag = "N/A";

										$med_his_pag = "Sistema";


										// INSERCION HISTORIAL
										$sqlInsercionHistorial = "
											INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
											VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
										";



										$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

										if ( !$resultadoInsercionHistorial ) {
											echo $sqlInsercionHistorial;
										}
										// FIN HISTORIAL

										if ( ($whatsappPlantel == 'Activo') && ($tel_alu != NULL) ) {
										// IF WHATSAPP
						    				
						    				
						    				// VALIDADOR SI HUBO DESCUENTO PREVIO
					    		// 			if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
						    	// 				$descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

						    	// 				$con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
						    	// 			}else{

						    	// 				$con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
						    	// 			}

						    				
						    	// 			$men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

											// $fec_his_pag = $fechaHoy;

											// $res_his_pag = 'Sistema';

											// $est_his_pag = 'Pendiente';

											// $tip_his_pag = "N/A";

											// $med_his_pag = "Whatsapp";


											// // INSERCION HISTORIAL
											// $sqlInsercionHistorialWhatsapp = "
											// 	INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
											// 	VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
											// ";



											// $resultadoInsercionHistorialWhatsapp = mysqli_query( $db, $sqlInsercionHistorialWhatsapp );

											// if ( !$resultadoInsercionHistorialWhatsapp ) {
											// 	echo $sqlInsercionHistorialWhatsapp;
											// }else {
												
											// 	$client->messages->create('whatsapp:+525518292351', // to
											// 	       array(
											// 	           'from' => 'whatsapp:+14155238886',
											// 	           'body' => $men_his_pag
											// 	       )
											// 	);

											// }
										// FIN IF WHATSAPP
										}

										if ( ($smsPlantel == 'Activo') && ($tel_alu != NULL) ) {
										// IF SMS
											
						    				
						    				// VALIDADOR SI HUBO DESCUENTO PREVIO
					    		// 			if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
						    	// 				$descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

						    	// 				$con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
						    	// 			}else{

						    	// 				$con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
						    	// 			}

						    				
						    	// 			$men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

											// $fec_his_pag = $fechaHoy;

											// $res_his_pag = 'Sistema';

											// $est_his_pag = 'Pendiente';

											// $tip_his_pag = "Descuento";

											// $med_his_pag = "SMS";


											// // INSERCION HISTORIAL
											// $sqlInsercionHistorialSms = "
											// 	INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
											// 	VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
											// ";



											// $resultadoInsercionHistorialSms = mysqli_query( $db, $sqlInsercionHistorialSms );

											// if ( !$resultadoInsercionHistorialSms ) {
											// 	echo $sqlInsercionHistorialSms;
											// }else {
											// 	$client->messages->create(
											// 	    // the number you'd like to send the message to
											// 	    '+525518292351',
											// 	    array(
											// 	        // A Twilio phone number you purchased at twilio.com/console
											// 	        'from' => '+13343261337',
											// 	        // the body of the text message you'd like to send
											// 	        'body' => $men_his_pag
											// 	    )
											// 	);
											// }
										// FIN IF SMS
										}

										if( $emailPlantel == 'Activo'){
										// IF CORREO
						    				// VALIDADOR SI HUBO DESCUENTO PREVIO
					    					if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
						    					$descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

						    					$con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
						    				}else{

						    					$con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
						    				}

						    				
						    				$men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

											$fec_his_pag = $fechaHoy;

											$res_his_pag = 'Sistema';

											$est_his_pag = 'Pendiente';

											$tip_his_pag = "N/A";

											$med_his_pag = "Correo";


											// INSERCION HISTORIAL
											$sqlInsercionHistorialCorreo = "
												INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
												VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
											";



											$resultadoInsercionHistorialCorreo = mysqli_query( $db, $sqlInsercionHistorialCorreo );

											if ( !$resultadoInsercionHistorialCorreo ) {
												echo $sqlInsercionHistorialCorreo;
											}else {
												
												$destino = $cor_alu;
											    $asunto = $men_his_pag;
											    $mensaje = $men_his_pag;


											    mail($destino, $asunto, $mensaje);
											}
										// FIN IF CORREO

										}




										// FIN HISTORIAL

				    				// FIN VALIDACION SI EXISTE HISTORIAL (SISTEMA, EMAIL, WHATS, SMS)
				    				}




			    				}else {
			    					echo $sqlValidacionHistorial;
			    				}
			    				

			    				


			    				

			    			}else {
			    				echo $sqlUpdatePago;
			    			}

						}

					}

		    	// FIN IF PRESENTE
		    	}else if ( $fechaHoy > $filaPago['fin_pag'] ) {
		    	// IF FUTURO
					
					$tip2_pag = $filaPago['tip2_pag'];
					$mon_ori_pag = $filaPago['mon_ori_pag'];
					$fecha1 = $fechaHoy;
					$fecha2 = $filaPago['fin_pag'];
					$date1 = date_create( $fecha1 );
					$date2 = date_create( $fecha2 );
					$diff = date_diff( $date1, $date2 );
					$diferenciaDias = $diff->format("%a");


					// RECARGOS
					$sqlTotalRecargo = "
						SELECT SUM( mon_rec_pag ) AS totalRecargo
						FROM recargo_pago
						WHERE id_pag5 = '$id_pag'
					";

					$resultadoTotalRecargo = mysqli_query( $db, $sqlTotalRecargo );

					$filaTotalRecargo = mysqli_fetch_assoc( $resultadoTotalRecargo );

					$totalRecargo = $filaTotalRecargo['totalRecargo'];

					if ( $totalRecargo == NULL ) {
						$totalRecargo = 0;
					}

					// FIN RECARGOS

		    		if ( $filaPago['int_pag'] == 'Única' ) {
					//UNICA UNICO
						$sqlValidacionHistorialInicial = "
		                      SELECT *
		                      FROM historial_pago
		                      WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'Recargo'
		                ";

		                $resultadoValidacionHistorialInicial = mysqli_query( $db, $sqlValidacionHistorialInicial );

						if ( $resultadoValidacionHistorialInicial ) {

							$totalValidacionHistorialInicial = mysqli_num_rows( $resultadoValidacionHistorialInicial );

							if ( $totalValidacionHistorialInicial == 0 ) {
							// RECARGO IF

								 //SENTENCIA PARA VALIDAR SI EXISTE ALGUN ABONO ASOCIADO AL PAGO PARA APLICAR CARGO AL ADEUDO
		                           $sqlBuscaAbono = " SELECT * FROM abono_pago WHERE id_pag1 = '$id_pag'";
		                           $sqlBuscarCondonacion="SELECT * FROM condonacion_pago WHERE id_pag2 = '$id_pag'";
		                           $resultadoCondonacion= mysqli_query($db, $sqlBuscarCondonacion);
		                           $resultadoBuscaAbono= mysqli_query( $db, $sqlBuscaAbono);



									
		                           
		                           if( $resultadoBuscaAbono ){
		                                
		                                $totalBuscaAbono = mysqli_num_rows( $resultadoBuscaAbono );
		                                $totalCondonacion = mysqli_num_rows($resultadoCondonacion);

		                           
		                                     $sqlTotalAbonado = "
		                                          SELECT SUM(mon_abo_pag) AS totalAbonado
		                                          FROM abono_pago
		                                          WHERE id_pag1 = '$id_pag'
		                                          UNION
		                                          SELECT SUM(can_con_pag) AS totalAbonado
		                                          FROM condonacion_pago
		                                          WHERE id_pag2 = '$id_pag' AND est_con_pag = 'Aprobado'

		                                     ";

		                                     $resultadoTotalAbonado = mysqli_query( $db, $sqlTotalAbonado );

		                                    if ( $resultadoTotalAbonado ) {

		                                          $totalAbonado = 0;
										
													while( $filaTotalAbonado = mysqli_fetch_assoc( $resultadoTotalAbonado ) ){
														$totalAbonado = $totalAbonado + $filaTotalAbonado['totalAbonado'];
													}

		                                          if ( $totalAbonado == NULL ) {
		                                          	$totalAbonado = 0;
		                                          }
		                                          
		                                          if ( $filaPago['tip2_pag'] == 'Porcentual' ) {
				                                     // CASO PORCENTUAL

			                                          $recargo = ( $filaPago['mon_ori_pag'] - $totalAbonado )* (  $filaPago['car_pag']/100 )  ;

			                                          $nuevoMonto = $filaPago['mon_ori_pag'] + $totalRecargo - $totalAbonado + $recargo;

			                                     // FIN CASO PORCENTUAL
			                                     }else if( $filaPago['tip2_pag'] == 'Monetario' ){
			                                     // CASO MONETARIO
			                                          $recargo = $filaPago['car_pag'];
			                                          $nuevoMonto = $filaPago['mon_ori_pag'] + $totalRecargo - $totalAbonado + $recargo;
			                                     // FIN CASO MONETARIO
			                                     }


			                                    // RECARGO
			                                    $sqlValidacionRecargo = "
													SELECT *
													FROM recargo_pago
													WHERE id_pag5 = '$id_pag'
										    	";

										    	$resultadoValidacionRecargo = mysqli_query( $db, $sqlValidacionRecargo );

										    	if ( $resultadoValidacionRecargo ) {
										    		
										    		$validacionRecargo = mysqli_fetch_assoc( $resultadoValidacionRecargo );

										    		if ( $validacionRecargo == 0 ) {
										    			
										    			$fechaHoy = $fechaHoy;
														$res_rec_pag = 'Sistema';
														$mon_rec_pag = $recargo;
														$id_pag5 = $id_pag;

														$sqlInsercionRecargo = "
															INSERT INTO recargo_pago ( fec_rec_pag, res_rec_pag, mon_rec_pag, id_pag5 )
															VALUES ( '$fechaHoy', '$res_rec_pag', '$mon_rec_pag', '$id_pag5' )
														";

														$resultadoInsercionRecargo = mysqli_query( $db, $sqlInsercionRecargo );

														

														if ( !$resultadoInsercionRecargo ) {
															echo $sqlInsercionRecargo;
														}
										    		}


										    	} else {
										    		echo $sqlValidacionRecargo;
										    	}
			                                    // FIN RECARGO 

		                                    }

		                                     


		                                

		                           }

		                      

		                      
		                      $sqlUpdatePago = "
		                                UPDATE pago
		                                SET 
		                                mon_pag = '$nuevoMonto'
		                                WHERE id_pag = '$id_pag'
		                      ";

		                      $resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

		                      if ( $resultadoUpdatePago ) {	                           

		                           //HISTORIAL

		                             $con_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);

		                             $men_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);


		                                  $fec_his_pag = $fechaHoy;

		                                  $res_his_pag = 'Sistema';

		                                  $est_his_pag = 'Pendiente';

		                                  $tip_his_pag = "Recargo";

		                                  $med_his_pag = "Sistema";


		                                  // INSERCION HISTORIAL
		                                  $sqlInsercionHistorial = "
		                                       INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                       VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                  ";



		                                  $resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

		                                  if ( !$resultadoInsercionHistorial ) {
		                                       echo $sqlInsercionHistorial;
		                                  }
		                                  // FIN HISTORIAL

		                                  if ( ($whatsappPlantel == 'Activo') && ($tel_alu != NULL) ) {
		                                  // IF WHATSAPP
		                                  
		                                  // VALIDADOR SI HUBO DESCUENTO PREVIO
		                   //             if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
		                   //                  $descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

		                   //                  $con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                   //             }else{

		                   //                  $con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                   //             }

		                                  
		                   //             $men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

		                                       // $fec_his_pag = $fechaHoy;

		                                       // $res_his_pag = 'Sistema';

		                                       // $est_his_pag = 'Pendiente';

		                                       // $tip_his_pag = "N/A";

		                                       // $med_his_pag = "Whatsapp";


		                                       // // INSERCION HISTORIAL
		                                       // $sqlInsercionHistorialWhatsapp = "
		                                       //   INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                       //   VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                       // ";



		                                       // $resultadoInsercionHistorialWhatsapp = mysqli_query( $db, $sqlInsercionHistorialWhatsapp );

		                                       // if ( !$resultadoInsercionHistorialWhatsapp ) {
		                                       //   echo $sqlInsercionHistorialWhatsapp;
		                                       // }else {
		                                            
		                                       //   $client->messages->create('whatsapp:+525518292351', // to
		                                       //          array(
		                                       //              'from' => 'whatsapp:+14155238886',
		                                       //              'body' => $men_his_pag
		                                       //          )
		                                       //   );

		                                       // }
		                                  // FIN IF WHATSAPP
		                                  }

		                                  if ( ($smsPlantel == 'Activo') && ($tel_alu != NULL) ) {
		                                  // IF SMS
		                                       
		                                  
		                                  // VALIDADOR SI HUBO DESCUENTO PREVIO
		                   //             if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
		                   //                  $descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

		                   //                  $con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                   //             }else{

		                   //                  $con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                   //             }

		                                  
		                   //             $men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

		                                       // $fec_his_pag = $fechaHoy;

		                                       // $res_his_pag = 'Sistema';

		                                       // $est_his_pag = 'Pendiente';

		                                       // $tip_his_pag = "Descuento";

		                                       // $med_his_pag = "SMS";


		                                       // // INSERCION HISTORIAL
		                                       // $sqlInsercionHistorialSms = "
		                                       //   INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                       //   VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                       // ";



		                                       // $resultadoInsercionHistorialSms = mysqli_query( $db, $sqlInsercionHistorialSms );

		                                       // if ( !$resultadoInsercionHistorialSms ) {
		                                       //   echo $sqlInsercionHistorialSms;
		                                       // }else {
		                                       //   $client->messages->create(
		                                       //       // the number you'd like to send the message to
		                                       //       '+525518292351',
		                                       //       array(
		                                       //           // A Twilio phone number you purchased at twilio.com/console
		                                       //           'from' => '+13343261337',
		                                       //           // the body of the text message you'd like to send
		                                       //           'body' => $men_his_pag
		                                       //       )
		                                       //   );
		                                       // }
		                                  // FIN IF SMS
		                                  }

		                                  if( $emailPlantel == 'Activo'){
		                                  // IF CORREO
		                                  $con_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);

		                                  $men_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);


		                                       $fec_his_pag = $fechaHoy;

		                                       $res_his_pag = 'Sistema';

		                                       $est_his_pag = 'Pendiente';

		                                       $tip_his_pag = "Recargo";

		                                       $med_his_pag = "Correo";


		                                       // INSERCION HISTORIAL
		                                       $sqlInsercionHistorialCorreo = "
		                                            INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                            VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                       ";



		                                       $resultadoInsercionHistorialCorreo = mysqli_query( $db, $sqlInsercionHistorialCorreo );

		                                       if ( !$resultadoInsercionHistorialCorreo ) {
		                                            echo $sqlInsercionHistorialCorreo;
		                                       }else {
		                                            
		                                            $destino = $cor_alu;
		                                           $asunto = $men_his_pag;
		                                           $mensaje = $men_his_pag;


		                                           mail($destino, $asunto, $mensaje);
		                                       }
		                                  // FIN IF CORREO

		                                  }




		                                  // FIN HISTORIAL
		                           

		                           


		                           

		                      }else {
		                           echo $sqlUpdatePago;
		                      }






								
							// FIN RECARGO IF
							}

						}

					// UNICO UNICA FIN
					} else if ( $filaPago['int_pag'] == 'Recurrente' ) {
					// RECURRENTE
						$sqlValidacionHistorialInicial = "
		                      SELECT *
		                      FROM historial_pago
		                      WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'Recargo' AND fec_his_pag = '$fechaHoy'
		                ";


		                 $resultadoValidacionHistorialInicial = mysqli_query( $db, $sqlValidacionHistorialInicial );

		                 if ( $resultadoValidacionHistorialInicial ) {

		                      $validacionHistorialInicial = mysqli_num_rows( $resultadoValidacionHistorialInicial );

		                      if ( ($validacionHistorialInicial == 0) || ( ($validacionHistorialInicial == 0) && ($diferenciaDias%3 == 0) ) ) {


		                           //SENTENCIA PARA VALIDAR SI EXISTE ALGUN ABONO ASOCIADO AL PAGO PARA APLICAR CARGO AL ADEUDO
		                           $sqlBuscaAbono = " SELECT * FROM abono_pago WHERE id_pag1 = '$id_pag'";
		                           $sqlBuscarCondonacion="SELECT * FROM condonacion_pago WHERE id_pag2 = '$id_pag'";
		                           $resultadoCondonacion= mysqli_query($db, $sqlBuscarCondonacion);
		                           $resultadoBuscaAbono= mysqli_query( $db, $sqlBuscaAbono);
		                           
		                           if( $resultadoBuscaAbono ){
		                                
		                                $totalBuscaAbono = mysqli_num_rows( $resultadoBuscaAbono );
		                                $totalCondonacion = mysqli_num_rows($resultadoCondonacion);
     
	                                    $sqlTotalAbonado = "
											SELECT SUM(mon_abo_pag) AS totalAbonado
											FROM abono_pago
											WHERE id_pag1 = '$id_pag'
											UNION
											SELECT SUM(can_con_pag) AS totalAbonado
											FROM condonacion_pago
											WHERE id_pag2 = '$id_pag' AND est_con_pag = 'Aprobado'

	                                    ";

	                                     $resultadoTotalAbonado = mysqli_query( $db, $sqlTotalAbonado );

	                                    if ( $resultadoTotalAbonado ) {

	                                        $totalAbonado = 0;
										
											while( $filaTotalAbonado = mysqli_fetch_assoc( $resultadoTotalAbonado ) ){
												$totalAbonado = $totalAbonado + $filaTotalAbonado['totalAbonado'];
											}

	                                        if ( $totalAbonado == NULL ) {
	                                        	$totalAbonado = 0;
	                                        }
	                                        
	                                        $fechaFin = $filaPago['fin_pag'];

											$begin = new DateTime( $fechaFin );
											$end   = new DateTime( $fechaHoy );
											$begin->modify('+1 day');
											
											$total = $mon_ori_pag - $totalAbonado + $totalRecargo;

											$totalRecargo = 0;

											for( $i = $begin, $mon_rec_pag = 0 ; $i <= $end; $i->modify('+1 day') ) {
											    //echo "<br>".$i->format("Y-m-d");
											    $dia_registro = $i->format("Y-m-d");
											    
											    $diff = date_diff( $begin, $end );
												$diferenciaDias = $diff->format("%a");


												if ( $tip2_pag == 'Porcentual' ) {
											    	
											    	$recargo =  ($mon_ori_pag * ( $filaPago['car_pag'] / 100 ) )/365;

											    	$total = $total + $recargo;
											

											    } else if ( $tip2_pag == 'Monetario' ) {
											    	
											    // CASO MONETARIO
			                                          $recargo = $filaPago['car_pag'];
			                                          $total = $total + $recargo;
			                                     // FIN CASO MONETARIO
											    
											    }

											    $totalRecargo = $totalRecargo + $recargo;

											    $fec_rec_pag = $dia_registro;
											    	
										    	$sqlValidacionRecargo = "
													SELECT *
													FROM recargo_pago
													WHERE fec_rec_pag = '$fec_rec_pag' AND id_pag5 = '$id_pag'
										    	";

										    	$resultadoValidacionRecargo = mysqli_query( $db, $sqlValidacionRecargo );

										    	if ( $resultadoValidacionRecargo ) {
										    		
										    		$validacionRecargo = mysqli_fetch_assoc( $resultadoValidacionRecargo );

										    		if ( $validacionRecargo == 0 ) {
										    			
										    			$fec_rec_pag = $fec_rec_pag;
														$res_rec_pag = 'Sistema';
														$mon_rec_pag = $recargo;
														$id_pag5 = $id_pag;

														$sqlInsercionRecargo = "
															INSERT INTO recargo_pago ( fec_rec_pag, res_rec_pag, mon_rec_pag, id_pag5 )
															VALUES ( '$fec_rec_pag', '$res_rec_pag', '$mon_rec_pag', '$id_pag5' )
														";

														$resultadoInsercionRecargo = mysqli_query( $db, $sqlInsercionRecargo );

														

														if ( !$resultadoInsercionRecargo ) {
															echo $sqlInsercionRecargo;
														}
										    		}


										    	} else {
										    		echo $sqlValidacionRecargo;
										    	}

											// FIN FOR
											}

											$recargo = $totalRecargo;
											$nuevoMonto = $total;  


	                                    }

		                                     

		                           }

		                      

		                      
		                      $sqlUpdatePago = "
		                                UPDATE pago
		                                SET 
		                                mon_pag = '$nuevoMonto'
		                                WHERE id_pag = '$id_pag'
		                      ";

		                      $resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

		                      if ( $resultadoUpdatePago ) {

		                           // VALIDACION SI EXISTEN HISTORIALES ASOCIADOS A PAGO
		                           $sqlValidacionHistorial = "
		                                     SELECT *
		                                     FROM historial_pago
		                                     WHERE id_pag4 = '$id_pag' AND tip_his_pag = 'Recargo' AND fec_his_pag = '$fechaHoy'
		                           ";


		                           $resultadoValidacionHistorial = mysqli_query( $db, $sqlValidacionHistorial );

		                           if ( $resultadoValidacionHistorial ) {

		                                $validacionHistorial = mysqli_num_rows( $resultadoValidacionHistorial );

		                                if ( ($validacionHistorial == 0) || ($validacionHistorial == 0) && ($diferenciaDias%3 == 0) ) {
		                                     //HISTORIAL

		                                     $con_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);

		                                     $men_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);


		                                          $fec_his_pag = $fechaHoy;

		                                          $res_his_pag = 'Sistema';

		                                          $est_his_pag = 'Pendiente';

		                                          $tip_his_pag = "Recargo";

		                                          $med_his_pag = "Sistema";


		                                          // INSERCION HISTORIAL
		                                          $sqlInsercionHistorial = "
		                                               INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                               VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                          ";



		                                          $resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

		                                          if ( !$resultadoInsercionHistorial ) {
		                                               echo $sqlInsercionHistorial;
		                                          }
		                                          // FIN HISTORIAL

		                                          if ( ($whatsappPlantel == 'Activo') && ($tel_alu != NULL) ) {
		                                          // IF WHATSAPP
		                                          
		                                          // VALIDADOR SI HUBO DESCUENTO PREVIO
		                           //             if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
		                           //                  $descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

		                           //                  $con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                           //             }else{

		                           //                  $con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                           //             }

		                                          
		                           //             $men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

		                                               // $fec_his_pag = $fechaHoy;

		                                               // $res_his_pag = 'Sistema';

		                                               // $est_his_pag = 'Pendiente';

		                                               // $tip_his_pag = "N/A";

		                                               // $med_his_pag = "Whatsapp";


		                                               // // INSERCION HISTORIAL
		                                               // $sqlInsercionHistorialWhatsapp = "
		                                               //   INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                               //   VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                               // ";



		                                               // $resultadoInsercionHistorialWhatsapp = mysqli_query( $db, $sqlInsercionHistorialWhatsapp );

		                                               // if ( !$resultadoInsercionHistorialWhatsapp ) {
		                                               //   echo $sqlInsercionHistorialWhatsapp;
		                                               // }else {
		                                                    
		                                               //   $client->messages->create('whatsapp:+525518292351', // to
		                                               //          array(
		                                               //              'from' => 'whatsapp:+14155238886',
		                                               //              'body' => $men_his_pag
		                                               //          )
		                                               //   );

		                                               // }
		                                          // FIN IF WHATSAPP
		                                          }

		                                          if ( ($smsPlantel == 'Activo') && ($tel_alu != NULL) ) {
		                                          // IF SMS
		                                               
		                                          
		                                          // VALIDADOR SI HUBO DESCUENTO PREVIO
		                           //             if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
		                           //                  $descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

		                           //                  $con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                           //             }else{

		                           //                  $con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
		                           //             }

		                                          
		                           //             $men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

		                                               // $fec_his_pag = $fechaHoy;

		                                               // $res_his_pag = 'Sistema';

		                                               // $est_his_pag = 'Pendiente';

		                                               // $tip_his_pag = "Descuento";

		                                               // $med_his_pag = "SMS";


		                                               // // INSERCION HISTORIAL
		                                               // $sqlInsercionHistorialSms = "
		                                               //   INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                               //   VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                               // ";



		                                               // $resultadoInsercionHistorialSms = mysqli_query( $db, $sqlInsercionHistorialSms );

		                                               // if ( !$resultadoInsercionHistorialSms ) {
		                                               //   echo $sqlInsercionHistorialSms;
		                                               // }else {
		                                               //   $client->messages->create(
		                                               //       // the number you'd like to send the message to
		                                               //       '+525518292351',
		                                               //       array(
		                                               //           // A Twilio phone number you purchased at twilio.com/console
		                                               //           'from' => '+13343261337',
		                                               //           // the body of the text message you'd like to send
		                                               //           'body' => $men_his_pag
		                                               //       )
		                                               //   );
		                                               // }
		                                          // FIN IF SMS
		                                          }

		                                          if( $emailPlantel == 'Activo'){
		                                          // IF CORREO
		                                          $con_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);

		                                          $men_his_pag = "Se generó recargo de $".round($recargo, 2)." al saldo original de $".$filaPago['mon_ori_pag'].". Nuevo adeudo de $".round($nuevoMonto, 2);


		                                               $fec_his_pag = $fechaHoy;

		                                               $res_his_pag = 'Sistema';

		                                               $est_his_pag = 'Pendiente';

		                                               $tip_his_pag = "Recargo";

		                                               $med_his_pag = "Correo";


		                                               // INSERCION HISTORIAL
		                                               $sqlInsercionHistorialCorreo = "
		                                                    INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
		                                                    VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
		                                               ";



		                                               $resultadoInsercionHistorialCorreo = mysqli_query( $db, $sqlInsercionHistorialCorreo );

		                                               if ( !$resultadoInsercionHistorialCorreo ) {
		                                                    echo $sqlInsercionHistorialCorreo;
		                                               }else {
		                                                    
		                                                    $destino = $cor_alu;
		                                                   $asunto = $men_his_pag;
		                                                   $mensaje = $men_his_pag;


		                                                   mail($destino, $asunto, $mensaje);
		                                               }
		                                          // FIN IF CORREO

		                                          }




		                                          // FIN HISTORIAL

		                                // FIN VALIDACION SI EXISTE HISTORIAL (SISTEMA, EMAIL, WHATS, SMS)
		                                }




		                           }else {
		                                echo $sqlValidacionHistorial;
		                           }
		                           

		                           


		                           

		                      }else {
		                           echo $sqlUpdatePago;
		                      }


		                      }
		                 }

		            // FIN IF RECURRENTE
					}
					

		    	// FIN IF FUTURO
		    	}


	    	} 
	    	// FIN VALIDADOR SI EXISTEN FECHAS
	    	



	    }

	}

    // FIN GENERADOR DESCUENTOS O RECARGOS


    function estatusAlumno($id_alu_ram, $id_ram){
    	require('../includes/conexion.php');

		$sqlValidacionEgresado = "
			SELECT *
			FROM materia
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_ram = '$id_ram' 
		";

		//echo $sqlValidacionEgresado;

		$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);

		$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

		$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


		if ($resultadoValidacionEgresado) {
			
			$sqlValidacionCalificacionAprobatoria = "
				SELECT *
				FROM calificacion 
				WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
			";

			$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

			$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


			if ($totalMaterias == $validacionCalificacionAprobatoria) {
				return "Egresado";

				$totalAluHor = 0;
			}else{
				$sqlConsultaAluHor = "
					SELECT * 
					FROM alu_hor 
					WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
				";
				$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
				$totalAluHor = mysqli_num_rows($resultadoAluHor);
				//echo $totalAluHor;
				if($totalAluHor == 0){
			
					return "Inactivo";		
			
				}else {
			
					return "Activo";
		
				}
			}

		}else{
			echo "Error en validacionEgresado";
		}



    }





    function estatusAlumnoTotalCarga($id_alu_ram, $id_ram){
    	require('../includes/conexion.php');

		$sqlValidacionEgresado = "
			SELECT *
			FROM materia
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_ram = '$id_ram' 
		";

		//echo $sqlValidacionEgresado;

		$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);

		$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

		$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


		if ($resultadoValidacionEgresado) {
			
			$sqlValidacionCalificacionAprobatoria = "
				SELECT *
				FROM calificacion 
				WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
			";

			$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

			$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


			if ($totalMaterias == $validacionCalificacionAprobatoria) {
				//echo "Egresado";

				$totalAluHor = 0;

				return $totalAluHor;
			}else{
				$sqlConsultaAluHor = "
					SELECT * 
					FROM alu_hor 
					WHERE id_alu_ram1 = '$id_alu_ram'  AND est_alu_hor = 'Activo'
				";
				$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
				$totalAluHor = mysqli_num_rows($resultadoAluHor);
				//echo $totalAluHor;
				if($totalAluHor == 0){
			
					return $totalAluHor;		
			
				}else {
			
					return $totalAluHor;
		
				}
			}

		}else{
			echo "Error en validacionEgresado";
		}



    }

    //FUNCION PARA ELIMINACION DE PAGOS FUTUROS CUANDO EL ALUMNO SE DE DE Baja

    function elimina_pagos_baja($id_alu_ram)
    {
    	require('../includes/conexion.php');
    	
    	$identificador = $id_alu_ram;
    	$fecha_mes = date('m');
    	$feha_annio = date('Y');

    	$sql_delete_pagos = " DELETE FROM pago 
    						  WHERE id_alu_ram10 = '$identificador' 
    						  AND (MONTH(ini_pag) > '$fecha_mes' AND Year(ini_pag) >= '$feha_annio') ";

    	$resultado_delete = mysqli_query( $db, $sql_delete_pagos);
    	if ($resultado_delete) {
    		
    		logServer( 'Baja', $tipoUsuario, $id, 'Baja', $plantel );
    	}
    	else{
    		echo $sql_delete_pagos;
    	}

    }


    function obtenerDescripcionInternetUsuarioLogServer( $tipoUsuario, $nombreUsuario, $descarga, $subida, $latencia  ){
	    $fechaHoy = date( 'Y-m-d H:i:s' );

	    if ( $tipoUsuario == 'Admin' ) {
	          
	        $des_log = 'El Administrador: '.$nombreUsuario." registró una medición de internet ( descarga: $descarga Mbps; subida: $subida Mbps; latencia: $latencia ms ). Registrado ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
	          

	      } else if ( $tipoUsuario == 'Adminge' ) {

	        $des_log = 'El Gestor Escolar: '.$nombreUsuario." registró una medición de internet ( descarga: $descarga Mbps; subida: $subida Mbps; latencia: $latencia ms ). Registrado ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

	      } else if ( $tipoUsuario == 'Alumno' ) {
	        
	        $des_log = 'El Alumno: '.$nombreUsuario." registró una medición de internet ( descarga: $descarga Mbps; subida: $subida Mbps; latencia: $latencia ms ). Registrado ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

	      } else if ( $tipoUsuario == 'Profesor' ) {

	        $des_log = 'El Profesor: '.$nombreUsuario." registró una medición de internet ( descarga: $descarga Mbps; subida: $subida Mbps; latencia: $latencia ms ). Registrado ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
	        
	      }

	    return $des_log;
	}


    // FUNCION PARA CONOCER CARGA DE MATERIAS INSCRITAS DE ALUMNO
    function estatusAlumnoCargaServer($id_alu_ram, $id_ram){
    	require('../../includes/conexion.php');

		$sqlValidacionEgresado = "
			SELECT *
			FROM materia
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_ram = '$id_ram' 
		";

		//echo $sqlValidacionEgresado;

		$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);

		$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

		$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


		if ($resultadoValidacionEgresado) {
			
			$sqlValidacionCalificacionAprobatoria = "
				SELECT *
				FROM calificacion 
				WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
			";

			$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

			$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


			if ($totalMaterias == $validacionCalificacionAprobatoria) {
				//echo "Egresado";

				$totalAluHor = 0;

				return $totalAluHor;
			}else{
				$sqlConsultaAluHor = "
					SELECT * 
					FROM alu_hor 
					WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
				";
				$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
				$totalAluHor = mysqli_num_rows($resultadoAluHor);
				//echo $totalAluHor;
				if($totalAluHor == 0){
			
					return $totalAluHor;		
			
				}else {
			
					return $totalAluHor;
		
				}
			}

		}else{
			echo "Error en validacionEgresado";
		}



    }



    function obtenerCantidadAlumnosInscritosServer ( $id_sub_hor ) {
    	require('../../includes/conexion.php');
    	$sqlTotalAlumnos = "

			SELECT *
			FROM alu_hor 
			INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			WHERE id_sub_hor = '$id_sub_hor' AND est_alu_hor = 'Activo'

		";

		$resultadoTotalAlumnos = mysqli_query($db, $sqlTotalAlumnos);

		// while($filaTotalAlumnos = mysqli_fetch_assoc($resultadoTotalAlumnos)){

		// 	echo $filaTotalAlumnos['nom_alu'];

		// }


		$totalAlumnos = mysqli_num_rows($resultadoTotalAlumnos);
		return $totalAlumnos;
    }





    // FUNCION PARA CONOCER ESTATUS ACADEMICO DE ALUMNO DESDE ARCHIVOS DE SERVER
    function estatusAlumnoServer($id_alu_ram, $id_ram){
    	require('../../includes/conexion.php');

		$sqlValidacionEgresado = "
			SELECT *
			FROM materia
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_ram = '$id_ram' 
		";

		//echo $sqlValidacionEgresado;

		$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);

		$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

		$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


		if ($resultadoValidacionEgresado) {
			
			$sqlValidacionCalificacionAprobatoria = "
				SELECT *
				FROM calificacion 
				WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
			";

			$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

			$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


			if ($totalMaterias == $validacionCalificacionAprobatoria) {
				return "Egresado";

				$totalAluHor = 0;
			}else{
				$sqlConsultaAluHor = "
					SELECT * 
					FROM alu_hor 
					WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
				";
				$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
				$totalAluHor = mysqli_num_rows($resultadoAluHor);
				//echo $totalAluHor;
				if($totalAluHor == 0){
			
					return "Inactivo";		
			
				}else {
			
					return "Activo";
		
				}
			}

		}else{
			echo "Error en validacionEgresado";
		}
    }



    // FUNCION PARA CONOCER CONTEO DE ESTATUS DE PAGOS Y ACADEMICO DE ALUMNOS ASOCIADOS A GENERACION
    function conteoEstatusAlumnosGeneracionServer( $id_gen ){
    	require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');
    	// INICIALIZACION DE ARREGLO ASOCIATIVO
    	$estatusAlumnosGeneracion = array();
	    $estatusAlumnosGeneracion['alumnosTotales'] = 0;
	    $estatusAlumnosGeneracion['alumnosInscritos'] = 0;
	    $estatusAlumnosGeneracion['alumnosPendientes'] = 0;
	    $estatusAlumnosGeneracion['alumnosEgresados'] = 0;
	    $estatusAlumnosGeneracion['alumnosActivos'] = 0;
	    $estatusAlumnosGeneracion['alumnosInactivos'] = 0;

	    //return $estatusAlumnosGeneracion;
    	// OBTENCION DE MATERIAS DE PROGRAMA ASOCIADO A GENERACION
		$sqlValidacionEgresado = "
			SELECT *
			FROM materia
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			INNER JOIN generacion ON generacion.id_ram5 = rama.id_ram
			WHERE id_gen = '$id_gen' 
		";
		
		$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);


		// OBTENCION DE ALUMNOS ASOCIADOS A GENERACION 
		$sqlAlumnosGeneracion = "
			SELECT *
			FROM alu_ram
			INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
			WHERE id_gen = '$id_gen'
		";

		$resultadoAlumnosGeneracion = mysqli_query($db, $sqlAlumnosGeneracion);

		if (!$resultadoAlumnosGeneracion) {
			echo $sqlAlumnosGeneracion;
		}else{
			// EJECUCION DE OBTENCION DE ALUMNOS ASOCIADOS A GENERACION CORRECTA
			// PROCEDEMOS A ITERAR ALUMNOS

			while( $filaAlumnosGeneracion = mysqli_fetch_assoc( $resultadoAlumnosGeneracion ) ){
				// id_alu_ram
				$id_alu_ram = $filaAlumnosGeneracion['id_alu_ram'];


				// ESTATUS DE PAGO --> DEFINIDA POR ALGORITMO DE SISTEMA Y ACTUALIZADA CONSTANTEMENTE
				$sqlEstatusAlumno ="
				    SELECT id_alu_ram, fin_pag 
				    FROM alu_ram
				    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
				    WHERE fin_pag < '$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
				";

				$resultadoEstatusAlumno = mysqli_query( $db, $sqlEstatusAlumno );

				if ( $resultadoEstatusAlumno ) {
					$validacionEstatusAlumno = mysqli_num_rows( $resultadoEstatusAlumno );

					if ( $validacionEstatusAlumno > 0 ) {
						$estatusAlumnosGeneracion['alumnosInactivos']++;
					}else{
						$estatusAlumnosGeneracion['alumnosActivos']++;
					}

				}else{
					echo $sqlEstatusAlumno;
				}

				
				

				// CONTEO DE ESTATUS ACADEMICO
				$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

				$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


				if ($resultadoValidacionEgresado) {
					
					$sqlValidacionCalificacionAprobatoria = "
						SELECT *
						FROM calificacion 
						WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
					";

					$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

					$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


					if ($totalMaterias == $validacionCalificacionAprobatoria) {
						//return "Egresado";

						$estatusAlumnosGeneracion['alumnosEgresados']++;

						$totalAluHor = 0;
					}else{
						$sqlConsultaAluHor = "
							SELECT * 
							FROM alu_hor 
							WHERE id_alu_ram1 = '$id_alu_ram'  AND est_alu_hor = 'Activo'
						";
						$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
						$totalAluHor = mysqli_num_rows($resultadoAluHor);
						//echo $totalAluHor;
						if($totalAluHor == 0){
					
							//return "Pendiente";		
							$estatusAlumnosGeneracion['alumnosPendientes']++;
						}else {

							// return "Inscrito";
							$estatusAlumnosGeneracion['alumnosInscritos']++;
						}
					}

				}else{
					echo "Error en validacionEgresado";
				}


				// CONTEO TOTAL DE ALUMNOS ASOCIADOS A GENERACION
				$estatusAlumnosGeneracion['alumnosTotales']++;

				// FIN CODIGO
			}
			// FIN WHILE

			return $estatusAlumnosGeneracion;

		}

		//echo $sqlValidacionEgresado;
		
    }



    // FUNCION PARA CONOCER CONTEO DE ESTATUS DE PAGOS Y ACADEMICO DE ALUMNOS ASOCIADOS A RAMA
    function conteoEstatusAlumnosRama($id_ram){
    	require('../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

    	// INICIALIZACION DE ARREGLO ASOCIATIVO
    	$estatusAlumnosGeneracion = array();
	    $estatusAlumnosGeneracion['alumnosTotales'] = 0;
	    $estatusAlumnosGeneracion['alumnosInscritos'] = 0;
	    $estatusAlumnosGeneracion['alumnosPendientes'] = 0;
	    $estatusAlumnosGeneracion['alumnosEgresados'] = 0;
	    $estatusAlumnosGeneracion['alumnosActivos'] = 0;
	    $estatusAlumnosGeneracion['alumnosInactivos'] = 0;

	    //return $estatusAlumnosGeneracion;
    	// OBTENCION DE MATERIAS DE PROGRAMA ASOCIADO A GENERACION
		$sqlValidacionEgresado = "
			SELECT *
			FROM materia
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_ram = '$id_ram' 
		";
		
		$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);


		// OBTENCION DE ALUMNOS ASOCIADOS A GENERACION 
		$sqlAlumnosGeneracion = "
			SELECT *
			FROM alu_ram
			INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
			WHERE id_ram = '$id_ram'
		";

		$resultadoAlumnosGeneracion = mysqli_query($db, $sqlAlumnosGeneracion);

		if (!$resultadoAlumnosGeneracion) {
			echo $sqlAlumnosGeneracion;
		}else{
			// EJECUCION DE OBTENCION DE ALUMNOS ASOCIADOS A GENERACION CORRECTA
			// PROCEDEMOS A ITERAR ALUMNOS

			while( $filaAlumnosGeneracion = mysqli_fetch_assoc( $resultadoAlumnosGeneracion ) ){
				// id_alu_ram
				$id_alu_ram = $filaAlumnosGeneracion['id_alu_ram'];

				// ESTATUS DE PAGO --> DEFINIDA POR ALGORITMO DE SISTEMA Y ACTUALIZADA CONSTANTEMENTE
				$sqlEstatusAlumno ="
				    SELECT id_alu_ram, fin_pag 
				    FROM alu_ram
				    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
				    WHERE fin_pag <'$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
				";

				$resultadoEstatusAlumno = mysqli_query( $db, $sqlEstatusAlumno );

				if ( $resultadoEstatusAlumno ) {
					$validacionEstatusAlumno = mysqli_num_rows( $resultadoEstatusAlumno );

					if ( $validacionEstatusAlumno > 0 ) {
						$estatusAlumnosGeneracion['alumnosInactivos']++;
					}else{
						$estatusAlumnosGeneracion['alumnosActivos']++;
					}

				}else{
					echo $sqlEstatusAlumno;
				}

				// CONTEO DE ESTATUS ACADEMICO
				$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

				$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


				if ($resultadoValidacionEgresado) {
					
					$sqlValidacionCalificacionAprobatoria = "
						SELECT *
						FROM calificacion 
						WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
					";

					$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

					$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


					if ($totalMaterias == $validacionCalificacionAprobatoria) {
						//return "Egresado";

						$estatusAlumnosGeneracion['alumnosEgresados']++;

						$totalAluHor = 0;
					}else{
						$sqlConsultaAluHor = "
							SELECT * 
							FROM alu_hor 
							WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
						";
						$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
						$totalAluHor = mysqli_num_rows($resultadoAluHor);
						//echo $totalAluHor;
						if($totalAluHor == 0){
					
							//return "Pendiente";		
							$estatusAlumnosGeneracion['alumnosPendientes']++;
						}else {

							// return "Inscrito";
							$estatusAlumnosGeneracion['alumnosInscritos']++;
						}
					}

				}else{
					echo "Error en validacionEgresado";
				}


				// CONTEO TOTAL DE ALUMNOS ASOCIADOS A GENERACION
				$estatusAlumnosGeneracion['alumnosTotales']++;

				// FIN CODIGO
			}
			// FIN WHILE

			return $estatusAlumnosGeneracion;

		}

		//echo $sqlValidacionEgresado;
		
    }


    // OBTENER AVANCE PORCENTUAL ALUMNO CARRERA
    function obtenerAvanceAlumnoCarreraServer( $id_alu_ram ){
    	require('../../includes/conexion.php');

    	$porcentaje = 0;

    	$sqlRama = "
			SELECT *
			FROM alu_ram
			INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
			WHERE id_alu_ram = '$id_alu_ram'
    	";

    	$resultadoRama = mysqli_query( $db, $sqlRama );

    	if ( $resultadoRama ) {
    		
    		$filaRama = mysqli_fetch_assoc( $resultadoRama );
    		$id_ram = $filaRama['id_ram'];

    		$sqlValidacionEgresado = "
				SELECT *
				FROM materia
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_ram = '$id_ram' 
			";

			//echo $sqlValidacionEgresado;

			$resultadoValidacionEgresado = mysqli_query($db, $sqlValidacionEgresado);

			$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionEgresado);

			$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


			if ($resultadoValidacionEgresado) {
				
				$sqlValidacionCalificacionAprobatoria = "
					SELECT *
					FROM calificacion 
					WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
				";

				$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

				$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);

				$porcentaje = $validacionCalificacionAprobatoria/$totalMaterias;

				return round( ($porcentaje * 100), 2 )." %";

			}else{
				echo "Error en validacionEgresado";
			}


    	}else{

    		echo $sqlRama;
    	
    	}

    }


    function obtenerHorarioPresencialServer( $id_gru ){
    	require('../../includes/conexion.php');

		$sqlHorario = "
			SELECT * 
	    	FROM sub_hor
	        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			WHERE id_gru1 = '$id_gru'

		";

		$resultadoHorarioDatos = mysqli_query( $db, $sqlHorario );

		$filaHorarioDatos = mysqli_fetch_assoc( $resultadoHorarioDatos );

		// DATOS RAMA
		$nom_ram = $filaHorarioDatos['nom_ram'];
		$mod_ram = $filaHorarioDatos['mod_ram'];
		$gra_ram = $filaHorarioDatos['gra_ram'];
		$per_ram = $filaHorarioDatos['per_ram'];
		$cic_ram = $filaHorarioDatos['cic_ram'];

		// DATOS CICLO ESCOLAR
		$nom_cic = $filaHorarioDatos['nom_cic'];
		$ins_cic = $filaHorarioDatos['ins_cic'];
		$ini_cic = $filaHorarioDatos['ini_cic'];
		$cor_cic = $filaHorarioDatos['cor_cic'];
		$fin_cic = $filaHorarioDatos['fin_cic'];

		// DATOS GRUPO
		$nom_gru = $filaHorarioDatos['nom_gru'];

		$resultadoHorario = mysqli_query( $db, $sqlHorario );
?>
		
		<div class="row">
			<div class="col-md-3 text-left">
				<div class="card">
					<div class="card-header bg-white">
						Datos del Programa
					</div>
					<div class="card-body">
						<label class="letraPequena font-weight-normal">
							Programa: <?php echo $nom_ram; ?>
							<br>
							Modalidad: <?php echo $mod_ram; ?>
							<br>
							Nivel Educativo: <?php echo $gra_ram; ?>
							<br>
							Tipo de Periodo: <?php echo $per_ram; ?>
							<br>
							Cantidad de Periodos: <?php echo $cic_ram; ?>

						</label>

					
					</div>
				</div>
			</div>

			<div class="col-md-3 text-left">
				<div class="card">
					<div class="card-header bg-white">
						Datos del Ciclo Escolar
					</div>
					<div class="card-body">
					

					  	<label class="letraPequena font-weight-normal">
							<?php echo $nom_cic; ?>
							<br>
							Inscripción: <?php echo fechaFormateadaCompacta2($ins_cic); ?>
							<br>
							Inicio: <?php echo fechaFormateadaCompacta2($ini_cic); ?>
							<br>
							Corte: <?php echo fechaFormateadaCompacta2($cor_cic); ?>
							<br>
							Fin: <?php echo fechaFormateadaCompacta2($fin_cic); ?>
						</label>
					</div>
				</div>
			</div>

		</div>
		
		<br>

		<div class="row">
			<div class="col-md-12">
				
				<div class="card">
					
					<div class="card-body">
						<br>
						<label for="">
							<?php echo $nom_gru; ?>
						</label>
						<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTableHorarioPresencial">
							<thead class="grey lighten-2">
								<tr class="letraPequena font-weight-normal">
									<th class="letraPequena font-weight-normal">#</th>
									<th class="letraPequena font-weight-normal">Clave Grupal</th>
									<th class="letraPequena font-weight-normal">Profesor</th>
									<th class="letraPequena font-weight-normal">Materia</th>
									<th class="letraPequena font-weight-normal">Salón</th>

									<th class="letraPequena font-weight-normal">Lunes</th>
									<th class="letraPequena font-weight-normal">Martes</th>
									<th class="letraPequena font-weight-normal">Miercoles</th>
									<th class="letraPequena font-weight-normal">Jueves</th>
									<th class="letraPequena font-weight-normal">Viernes</th>
									<th class="letraPequena font-weight-normal">Sabado</th>
									<th class="letraPequena font-weight-normal">Domingo</th>
								</tr>
							</thead>

							<tbody >

								<?php
									$i = 1;

									while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
										$id_sub_hor = $filaHorario['id_sub_hor'];
								?>

									<tr class="letraPequena font-weight-normal">
										<td class="letraPequena font-weight-normal">
											<?php echo $i; $i++;  ?>
										</td>

										<td class="letraPequena font-weight-normal">
											<?php echo $filaHorario['nom_sub_hor']; ?>
										</td>


										<td class="letraPequena font-weight-normal">
											<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
										</td>


										<td class="letraPequena font-weight-normal">
											<?php echo $filaHorario['nom_mat']; ?>
										</td>


										<td class="letraPequena font-weight-normal">
											<?php  
												$sqlSalon = "
													SELECT *
													FROM salon
													INNER JOIN sub_hor ON sub_hor.id_sal1 = salon.id_sal
													WHERE id_sub_hor = '$id_sub_hor'
												";

												$resultadoSalon = mysqli_query( $db, $sqlSalon );


												if ( $resultadoSalon ) {
													
													$totalSalon = mysqli_num_rows( $resultadoSalon );

													if ( $totalSalon > 0 ) {
														
														$resultadoSalon2 = mysqli_query( $db, $sqlSalon );

														$filaSalon = mysqli_fetch_assoc( $resultadoSalon2 );

														echo $filaSalon['nom_sal'];


													} else {
														echo "N/A";
													}

												} else {

													echo $sqlSalon;
												
												}
											?>
										</td>

										<?php
											
											
											//LUNES
											$sqlSubHorLunes = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Lunes' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorLunes = mysqli_query($db, $sqlSubHorLunes);

											$filasLunes = mysqli_num_rows($resultadoSubHorLunes);

											if ($filasLunes == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorLunes = mysqli_fetch_assoc($resultadoSubHorLunes)){
												
												?>
													<td class="letraPequena font-weight-normal">
														<?php 
															echo $filaSubHorLunes['ini_hor']."-".$filaSubHorLunes['fin_hor']; 
														?>
														
													</td>
										

										<?php
												}
											}
												
											//MARTES
											$sqlSubHorMartes = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Martes' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorMartes = mysqli_query($db, $sqlSubHorMartes);

											$filasMartes = mysqli_num_rows($resultadoSubHorMartes);

											if ($filasMartes == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorMartes = mysqli_fetch_assoc($resultadoSubHorMartes)){
												
												?>
														<td class="letraPequena font-weight-normal">
															<?php 
																echo $filaSubHorMartes['ini_hor']."-".$filaSubHorMartes['fin_hor']; 
															?>
															
														</td>
										

										<?php
												}
											}

											//MIERCOLES
											$sqlSubHorMiercoles = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Miércoles' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorMiercoles = mysqli_query($db, $sqlSubHorMiercoles);

											$filasMiercoles = mysqli_num_rows($resultadoSubHorMiercoles);

											if ($filasMiercoles == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorMiercoles = mysqli_fetch_assoc($resultadoSubHorMiercoles)){
												
												?>
														<td class="letraPequena font-weight-normal">
															<?php 
																echo $filaSubHorMiercoles['ini_hor']."-".$filaSubHorMiercoles['fin_hor']; 
															?>
															
														</td>
										

										<?php
												}
											}

											//JUEVES
											$sqlSubHorJueves = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Jueves' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorJueves = mysqli_query($db, $sqlSubHorJueves);

											$filasJueves = mysqli_num_rows($resultadoSubHorJueves);

											if ($filasJueves == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorJueves = mysqli_fetch_assoc($resultadoSubHorJueves)){
												
												?>
														<td class="letraPequena font-weight-normal">
															<?php 
																echo $filaSubHorJueves['ini_hor']."-".$filaSubHorJueves['fin_hor']; 
															?>
															
														</td>
										

										<?php
												}
											}


											//VIERNES
											$sqlSubHorViernes = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Viernes' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorViernes = mysqli_query($db, $sqlSubHorViernes);

											$filasViernes = mysqli_num_rows($resultadoSubHorViernes);

											if ($filasViernes == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorViernes = mysqli_fetch_assoc($resultadoSubHorViernes)){
												
												?>
														<td class="letraPequena font-weight-normal">
															<?php 
																echo $filaSubHorViernes['ini_hor']."-".$filaSubHorViernes['fin_hor']; 
															?>
															
														</td>

										<?php
												}
											}


											//SABADO
											$sqlSubHorSabado = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Sábado' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorSabado = mysqli_query($db, $sqlSubHorSabado);

											$filasSabado = mysqli_num_rows($resultadoSubHorSabado);

											if ($filasSabado == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorSabado = mysqli_fetch_assoc($resultadoSubHorSabado)){
												
												?>
														<td class="letraPequena font-weight-normal">
															<?php 
																echo $filaSubHorSabado['ini_hor']."-".$filaSubHorSabado['fin_hor']; 
															?>
															
														</td>
										

										<?php
												}
											}
												

											//DOMINGO
											$sqlSubHorDomingo = "
												SELECT *
										    	FROM sub_hor
										    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
												WHERE dia_hor = 'Domingo' AND id_sub_hor1 = '$id_sub_hor';
											";

											//echo $sqlSubHor;
											$resultadoSubHorDomingo = mysqli_query($db, $sqlSubHorDomingo);

											$filasDomingo = mysqli_num_rows($resultadoSubHorDomingo);

											if ($filasDomingo == 0) {
										?>	
											<td class="letraPequena font-weight-normal">--</td>

										<?php
											}else{
												while($filaSubHorDomingo = mysqli_fetch_assoc($resultadoSubHorDomingo)){
												
												?>
														<td class="letraPequena font-weight-normal">
															<?php 
																echo $filaSubHorDomingo['ini_hor']."-".$filaSubHorDomingo['fin_hor']; 
															?>
															
														</td>
										

										<?php
												}
											}
												
								
										?>

									</tr>


								<?php

									}
									//FIN WHILE
								?>
								
								

								
							</tbody>

						</table>
					</div>
				</div>
			</div>
		</div>
		


		<script>
			$(document).ready(function () {


				$('#myTableHorarioPresencial').DataTable({
					
				
					dom: 'Bfrtlip',
					"scrollX": true,
		            
		            buttons: [

		            
		                    {
		                        extend: 'excelHtml5',
		                        exportOptions: {
		                            columns: ':visible'
		                        },
		                    },

		                    {
		                        
		                        extend: 'copyHtml5',
		                        exportOptions: {
		                            columns: ':visible'
		                        },

		                    },

		                    {
		                        extend: 'print',
		                        exportOptions: {
		                            columns: ':visible'
		                        },
		                    },

		                    {
		                        extend: 'pdf',
		                        exportOptions: {
		                            columns: ':visible'
		                        },
		                    },

		            ],

					"language": {
		                            "sProcessing":     "Procesando...",
		                            "sLengthMenu":     "Mostrar _MENU_ registros",
		                            "sZeroRecords":    "No se encontraron resultados",
		                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
		                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
		                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		                            "sInfoPostFix":    "",
		                            "sSearch":         "Buscar:",
		                            "sUrl":            "",
		                            "sInfoThousands":  ",",
		                            "sLoadingRecords": "Cargando...",
		                            "oPaginate": {
		                                "sFirst":    "Primero",
		                                "sLast":     "Último",
		                                "sNext":     "Siguiente",
		                                "sPrevious": "Anterior"
		                            },
		                            "oAria": {
		                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
		                            }
		                        }
				});
				$('#myTableHorarioPresencial_wrapper').find('label').each(function () {
					$(this).parent().append($(this).children());
				});
				$('#myTableHorarioPresencial_wrapper .dataTables_filter').find('input').each(function () {
					$('#myTableHorarioPresencial_wrapper input').attr("placeholder", "Buscar...");
					$('#myTableHorarioPresencial_wrapper input').removeClass('form-control-sm');
				});
				$('#myTableHorarioPresencial_wrapper .dataTables_length').addClass('d-flex flex-row');
				$('#myTableHorarioPresencial_wrapper .dataTables_filter').addClass('md-form');
				$('#myTableHorarioPresencial_wrapper select').removeClass(
				'custom-select custom-select-sm form-control form-control-sm');
				$('#myTableHorarioPresencial_wrapper select').addClass('mdb-select');
				$('#myTableHorarioPresencial_wrapper .mdb-select').materialSelect('destroy');
				$('#myTableHorarioPresencial_wrapper .mdb-select').materialSelect();
				$('#myTableHorarioPresencial_wrapper .dataTables_filter').find('label').remove();
				var botones = $('#myTableHorarioPresencial_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
				//console.log(botones);

			
			});
		</script>
<?php

    }
    // FIN FUNCTION CONSULTAR HORARIO SERVER


    function obtenerHorarioOnlineServer( $id_gru ){
    	require('../../includes/conexion.php');

    	$sqlHorario = "
			SELECT * 
	    	FROM sub_hor
	        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			WHERE id_gru1 = '$id_gru'

		";

		$resultadoHorarioDatos = mysqli_query( $db, $sqlHorario );

		$filaHorarioDatos = mysqli_fetch_assoc( $resultadoHorarioDatos );

		// DATOS RAMA
		$nom_ram = $filaHorarioDatos['nom_ram'];
		$mod_ram = $filaHorarioDatos['mod_ram'];
		$gra_ram = $filaHorarioDatos['gra_ram'];
		$per_ram = $filaHorarioDatos['per_ram'];
		$cic_ram = $filaHorarioDatos['cic_ram'];

		// DATOS CICLO ESCOLAR
		$nom_cic = $filaHorarioDatos['nom_cic'];
		$ins_cic = $filaHorarioDatos['ins_cic'];
		$ini_cic = $filaHorarioDatos['ini_cic'];
		$cor_cic = $filaHorarioDatos['cor_cic'];
		$fin_cic = $filaHorarioDatos['fin_cic'];

		// DATOS GRUPO
		$nom_gru = $filaHorarioDatos['nom_gru'];

		$resultadoHorario = mysqli_query( $db, $sqlHorario );
?>
		
		<div class="row">
			<div class="col-md-3 text-left">
				<div class="card">
					<div class="card-header bg-white">
						Datos del Programa
					</div>
					<div class="card-body">
						<label class="letraPequena font-weight-normal">
							Programa: <?php echo $nom_ram; ?>
							<br>
							Modalidad: <?php echo $mod_ram; ?>
							<br>
							Nivel Educativo: <?php echo $gra_ram; ?>
							<br>
							Tipo de Periodo: <?php echo $per_ram; ?>
							<br>
							Cantidad de Periodos: <?php echo $cic_ram; ?>

						</label>

					
					</div>
				</div>
			</div>

			<div class="col-md-3 text-left">
				<div class="card">
					<div class="card-header bg-white">
						Datos del Ciclo Escolar
					</div>
					<div class="card-body">
					

					  	<label class="letraPequena font-weight-normal">
							<?php echo $nom_cic; ?>
							<br>
							Inscripción: <?php echo fechaFormateadaCompacta2($ins_cic); ?>
							<br>
							Inicio: <?php echo fechaFormateadaCompacta2($ini_cic); ?>
							<br>
							Corte: <?php echo fechaFormateadaCompacta2($cor_cic); ?>
							<br>
							Fin: <?php echo fechaFormateadaCompacta2($fin_cic); ?>
						</label>
					</div>
				</div>
			</div>

		</div>
		
		<br>

		<div class="row">
			<div class="col-md-12">
				
				<div class="card">
					
					<div class="card-body">
						<br>
						<label for="">
							<?php echo $nom_gru; ?>
						</label>
						<?php
								//echo $sqlHorario;
							$resultadoHorario = mysqli_query($db, $sqlHorario);
						?>
							
						<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTableHorarioOnline">
							<thead class="grey lighten-2">
								<tr class="letraPequena font-weight-normal">
									<th>#</th>
									<th>Clave</th>
									<th>Profesor</th>
									<th>Materia</th>
								</tr>
							</thead>

							<tbody >

								<?php
									$i = 1;

									while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

								?>

									<tr class="letraPequena font-weight-normal">
										<td class="letraPequena font-weight-normal">
											<?php echo $i; $i++;  ?>
										</td>

										<td class="letraPequena font-weight-normal">
											<?php echo $filaHorario['nom_sub_hor']; ?>
										</td>


										<td class="letraPequena font-weight-normal">
											<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
										</td>


										<td class="letraPequena font-weight-normal">
											<?php echo $filaHorario['nom_mat']; ?>
										</td>
									</tr>
								<?php  
									}
								?>

							</tbody>

						</table>

						<script>
							$(document).ready(function () {
								

								$('#myTableHorarioOnline').DataTable({
									
								
									dom: 'Bfrtlip',
						            
						            buttons: [

						            
						                    {
						                        extend: 'excelHtml5',
						                        exportOptions: {
						                            columns: ':visible'
						                        },
						                    },                  

						                    {
						                        
						                        extend: 'copyHtml5',
						                        exportOptions: {
						                            columns: ':visible'
						                        },

						                    },

						                    {
						                        extend: 'print',
						                        exportOptions: {
						                            columns: ':visible'
						                        },
						                    },

						                    {
						                        extend: 'pdf',
						                        exportOptions: {
						                            columns: ':visible'
						                        },
						                    },

						            ],

									"language": {
						                            "sProcessing":     "Procesando...",
						                            "sLengthMenu":     "Mostrar _MENU_ registros",
						                            "sZeroRecords":    "No se encontraron resultados",
						                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
						                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						                            "sInfoPostFix":    "",
						                            "sSearch":         "Buscar:",
						                            "sUrl":            "",
						                            "sInfoThousands":  ",",
						                            "sLoadingRecords": "Cargando...",
						                            "oPaginate": {
						                                "sFirst":    "Primero",
						                                "sLast":     "Último",
						                                "sNext":     "Siguiente",
						                                "sPrevious": "Anterior"
						                            },
						                            "oAria": {
						                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
						                            }
						                        }
								});
								$('#myTableHorarioOnline_wrapper').find('label').each(function () {
									$(this).parent().append($(this).children());
								});
								$('#myTableHorarioOnline_wrapper .dataTables_filter').find('input').each(function () {
									$('#myTableHorarioOnline_wrapper input').attr("placeholder", "Buscar...");
									$('#myTableHorarioOnline_wrapper input').removeClass('form-control-sm');
								});
								$('#myTableHorarioOnline_wrapper .dataTables_length').addClass('d-flex flex-row');
								$('#myTableHorarioOnline_wrapper .dataTables_filter').addClass('md-form');
								$('#myTableHorarioOnline_wrapper select').removeClass(
								'custom-select custom-select-sm form-control form-control-sm');
								$('#myTableHorarioOnline_wrapper select').addClass('mdb-select');
								$('#myTableHorarioOnline_wrapper .mdb-select').materialSelect('destroy');
								$('#myTableHorarioOnline_wrapper .mdb-select').materialSelect();
								$('#myTableHorarioOnline_wrapper .dataTables_filter').find('label').remove();
								var botones = $('#myTableHorarioOnline_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
								//console.log(botones);

							
							});
						</script>
					</div>
				</div>
			</div>
		</div>


		

		


<?php

    }
    // FIN FUNCION OBTENER HORARIO ONLINE SERVER




    function obtenerHorarioEmpalmadoServer( $id_sub_hor, $mensaje, $tipo ){
    	require('../../includes/conexion.php');

		$sqlHorario = "
			SELECT * 
	    	FROM sub_hor
	        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			WHERE id_sub_hor = '$id_sub_hor'

		";

		$resultadoHorarioDatos = mysqli_query( $db, $sqlHorario );

		$filaHorarioDatos = mysqli_fetch_assoc( $resultadoHorarioDatos );

		// DATOS RAMA
		$nom_ram = $filaHorarioDatos['nom_ram'];
		$mod_ram = $filaHorarioDatos['mod_ram'];
		$gra_ram = $filaHorarioDatos['gra_ram'];
		$per_ram = $filaHorarioDatos['per_ram'];
		$cic_ram = $filaHorarioDatos['cic_ram'];

		// DATOS CICLO ESCOLAR
		$nom_cic = $filaHorarioDatos['nom_cic'];
		$ins_cic = $filaHorarioDatos['ins_cic'];
		$ini_cic = $filaHorarioDatos['ini_cic'];
		$cor_cic = $filaHorarioDatos['cor_cic'];
		$fin_cic = $filaHorarioDatos['fin_cic'];

		// DATOS GRUPO
		$nom_gru = $filaHorarioDatos['nom_gru'];

		$resultadoHorario = mysqli_query( $db, $sqlHorario );
?>

		<div class="card">
			
			<div class="card-body">
				<br>
				<h5 class="text-danger">
					<?php echo $mensaje; ?>
				</h5>
				<label for="">
					<?php echo $nom_gru; ?>
				</label>
				<table class="table table-sm text-center table-hover animated wobble table-bordered" cellspacing="0" width="99%">
					<thead class="grey lighten-2">
						<tr class="letraPequena font-weight-normal text-danger">
							<th class="letraPequena font-weight-normal text-danger">#</th>
							<th class="letraPequena font-weight-normal text-danger">Clave Grupal</th>
							<th class="letraPequena font-weight-normal text-danger">Profesor</th>
							<th class="letraPequena font-weight-normal text-danger">Materia</th>
							<th class="letraPequena font-weight-normal text-danger">Salón</th>

							<th class="letraPequena font-weight-normal text-danger">Lunes</th>
							<th class="letraPequena font-weight-normal text-danger">Martes</th>
							<th class="letraPequena font-weight-normal text-danger">Miercoles</th>
							<th class="letraPequena font-weight-normal text-danger">Jueves</th>
							<th class="letraPequena font-weight-normal text-danger">Viernes</th>
							<th class="letraPequena font-weight-normal text-danger">Sabado</th>
							<th class="letraPequena font-weight-normal text-danger">Domingo</th>
						</tr>
					</thead>

					<tbody >

						<?php
							$i = 1;

							while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
								$id_sub_hor = $filaHorario['id_sub_hor'];
						?>

							<tr class="letraPequena font-weight-normal text-danger">
								<td class="letraPequena font-weight-normal text-danger">
									<?php echo $i; $i++;  ?>
								</td>

								<td class="letraPequena font-weight-normal text-danger">
									<?php echo $filaHorario['nom_sub_hor']; ?>
								</td>

								<?php  

									if ( ( $tipo == 'Profesor' )  || ( $tipo == 'Ambos' ) ) {
								?>
										<td class="letraPequena font-weight-normal">
											<h6 class="text-danger">
												<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
											</h6>
											
										</td>
								<?php
									} else {
								?>
										<td class="letraPequena font-weight-normal text-danger">
											<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
										</td>

								<?php
									}
								?>


								


								<td class="letraPequena font-weight-normal text-danger">
									<?php echo $filaHorario['nom_mat']; ?>
								</td>

								

								<?php  

									if ( ( $tipo == 'Salón' )  || ( $tipo == 'Ambos' ) ) {
								?>
										<td class="letraPequena font-weight-normal ">

											<h6 class="text-danger">
												<?php  
													$sqlSalon = "
														SELECT *
														FROM salon
														INNER JOIN sub_hor ON sub_hor.id_sal1 = salon.id_sal
														WHERE id_sub_hor = '$id_sub_hor'
													";

													$resultadoSalon = mysqli_query( $db, $sqlSalon );


													if ( $resultadoSalon ) {
														
														$totalSalon = mysqli_num_rows( $resultadoSalon );

														if ( $totalSalon > 0 ) {
															
															$resultadoSalon2 = mysqli_query( $db, $sqlSalon );

															$filaSalon = mysqli_fetch_assoc( $resultadoSalon2 );

															echo $filaSalon['nom_sal'];


														} else {
															echo "N/A";
														}

													} else {

														echo $sqlSalon;
													
													}
												?>
											</h6>
											
										</td>
								<?php
									} else {
								?>
										<td class="letraPequena font-weight-normal text-danger">

												<?php  
													$sqlSalon = "
														SELECT *
														FROM salon
														INNER JOIN sub_hor ON sub_hor.id_sal1 = salon.id_sal
														WHERE id_sub_hor = '$id_sub_hor'
													";

													$resultadoSalon = mysqli_query( $db, $sqlSalon );


													if ( $resultadoSalon ) {
														
														$totalSalon = mysqli_num_rows( $resultadoSalon );

														if ( $totalSalon > 0 ) {
															
															$resultadoSalon2 = mysqli_query( $db, $sqlSalon );

															$filaSalon = mysqli_fetch_assoc( $resultadoSalon2 );

															echo $filaSalon['nom_sal'];


														} else {
															echo "N/A";
														}

													} else {

														echo $sqlSalon;
													
													}
												?>
											
										</td>

								<?php
									}
								?>

								<?php
									
									
									//LUNES
									$sqlSubHorLunes = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Lunes' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorLunes = mysqli_query($db, $sqlSubHorLunes);

									$filasLunes = mysqli_num_rows($resultadoSubHorLunes);

									if ($filasLunes == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorLunes = mysqli_fetch_assoc($resultadoSubHorLunes)){
										
										?>
											<td class="letraPequena font-weight-normal text-danger">
												<?php 
													echo $filaSubHorLunes['ini_hor']."-".$filaSubHorLunes['fin_hor']; 
												?>
												
											</td>
								

								<?php
										}
									}
										
									//MARTES
									$sqlSubHorMartes = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Martes' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorMartes = mysqli_query($db, $sqlSubHorMartes);

									$filasMartes = mysqli_num_rows($resultadoSubHorMartes);

									if ($filasMartes == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorMartes = mysqli_fetch_assoc($resultadoSubHorMartes)){
										
										?>
												<td class="letraPequena font-weight-normal text-danger">
													<?php 
														echo $filaSubHorMartes['ini_hor']."-".$filaSubHorMartes['fin_hor']; 
													?>
													
												</td>
								

								<?php
										}
									}

									//MIERCOLES
									$sqlSubHorMiercoles = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Miércoles' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorMiercoles = mysqli_query($db, $sqlSubHorMiercoles);

									$filasMiercoles = mysqli_num_rows($resultadoSubHorMiercoles);

									if ($filasMiercoles == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorMiercoles = mysqli_fetch_assoc($resultadoSubHorMiercoles)){
										
										?>
												<td class="letraPequena font-weight-normal text-danger">
													<?php 
														echo $filaSubHorMiercoles['ini_hor']."-".$filaSubHorMiercoles['fin_hor']; 
													?>
													
												</td>
								

								<?php
										}
									}

									//JUEVES
									$sqlSubHorJueves = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Jueves' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorJueves = mysqli_query($db, $sqlSubHorJueves);

									$filasJueves = mysqli_num_rows($resultadoSubHorJueves);

									if ($filasJueves == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorJueves = mysqli_fetch_assoc($resultadoSubHorJueves)){
										
										?>
												<td class="letraPequena font-weight-normal text-danger">
													<?php 
														echo $filaSubHorJueves['ini_hor']."-".$filaSubHorJueves['fin_hor']; 
													?>
													
												</td>
								

								<?php
										}
									}


									//VIERNES
									$sqlSubHorViernes = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Viernes' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorViernes = mysqli_query($db, $sqlSubHorViernes);

									$filasViernes = mysqli_num_rows($resultadoSubHorViernes);

									if ($filasViernes == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorViernes = mysqli_fetch_assoc($resultadoSubHorViernes)){
										
										?>
												<td class="letraPequena font-weight-normal text-danger">
													<?php 
														echo $filaSubHorViernes['ini_hor']."-".$filaSubHorViernes['fin_hor']; 
													?>
													
												</td>

								<?php
										}
									}


									//SABADO
									$sqlSubHorSabado = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Sábado' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorSabado = mysqli_query($db, $sqlSubHorSabado);

									$filasSabado = mysqli_num_rows($resultadoSubHorSabado);

									if ($filasSabado == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorSabado = mysqli_fetch_assoc($resultadoSubHorSabado)){
										
										?>
												<td class="letraPequena font-weight-normal text-danger">
													<?php 
														echo $filaSubHorSabado['ini_hor']."-".$filaSubHorSabado['fin_hor']; 
													?>
													
												</td>
								

								<?php
										}
									}
										

									//DOMINGO
									$sqlSubHorDomingo = "
										SELECT *
								    	FROM sub_hor
								    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
										WHERE dia_hor = 'Domingo' AND id_sub_hor1 = '$id_sub_hor';
									";

									//echo $sqlSubHor;
									$resultadoSubHorDomingo = mysqli_query($db, $sqlSubHorDomingo);

									$filasDomingo = mysqli_num_rows($resultadoSubHorDomingo);

									if ($filasDomingo == 0) {
								?>	
									<td class="letraPequena font-weight-normal text-danger">--</td>

								<?php
									}else{
										while($filaSubHorDomingo = mysqli_fetch_assoc($resultadoSubHorDomingo)){
										
										?>
												<td class="letraPequena font-weight-normal text-danger">
													<?php 
														echo $filaSubHorDomingo['ini_hor']."-".$filaSubHorDomingo['fin_hor']; 
													?>
													
												</td>
								

								<?php
										}
									}
										
						
								?>

							</tr>


						<?php

							}
							//FIN WHILE
						?>
						
						

						
					</tbody>

				</table>
			</div>
		</div>
		
		



<?php

    }
    // FIN FUNCTION CONSULTAR HORARIO sub_hor SERVER



    function obtenerTotalAbonadoServer($id_pag){

    	require('../../includes/conexion.php');

    	$sqlTotalAbonado = "
			SELECT SUM(mon_abo_pag) AS totalAbonado
			FROM abono_pago
			WHERE id_pag1 = '$id_pag'
    	";

    	$resultadoTotalAbonado = mysqli_query( $db, $sqlTotalAbonado );

    	$filaTotalAbonado = mysqli_fetch_assoc( $resultadoTotalAbonado );

    	$totalAbonado = $filaTotalAbonado['totalAbonado'];

    	if ( $totalAbonado == "" ) {
    		$totalAbonado = 0;
    	}
    	return $totalAbonado;
    }





    function procesarPeticionServer( $identificador_peticion, $tipo_peticion, $respuesta_peticion, $nomResponsable, $motivo_peticion ){
    	require('../../includes/conexion.php');


    	$fechaHoy = date('Y-m-d');

		// DATOS DE notificacion_pago
		$fec_not_pag = date('Y-m-d h:i:s');
		$est_not_pag = 'Pendiente';
		$mot_not_pag = $motivo_peticion;


    	// TIPO DE PETICION
    	if ( $tipo_peticion == 'Condonación' ) {
		//CONDONACION
			
			$id_con_pag = $identificador_peticion;
			$est_con_pag = $respuesta_peticion;

			// UPDATE EN condonacion_pago
			$sqlUpdateCondonacion = "
				UPDATE condonacion_pago
				SET
				est_con_pag = '$est_con_pag',
				val_con_pag = '$nomResponsable'

				WHERE id_con_pag = '$id_con_pag'
			";


			$resultadoUpdateCondonacion = mysqli_query( $db, $sqlUpdateCondonacion );

			if ( $resultadoUpdateCondonacion ) {




				// CONSULTA DE CONDONACION
				$sqlCondonacion = "
					SELECT *
					FROM condonacion_pago
					WHERE id_con_pag = '$id_con_pag'
		    	";

		    	$resultadoCondonacion = mysqli_query( $db, $sqlCondonacion );

		    	if ( $resultadoCondonacion ) {
		    		
		    		$filaCondonacion = mysqli_fetch_assoc( $resultadoCondonacion );
		    		$mon_con_pag = $filaCondonacion['mon_con_pag'];
		    		$tip1_con_pag = $filaCondonacion['tip1_con_pag'];
		    		$val_con_pag = $filaCondonacion['val_con_pag'];
		    		$id_pag = $filaCondonacion['id_pag2'];


		    		// RESPUESTA
					if ( $respuesta_peticion == 'Aprobado' ) {
					// APROBADO

						// HISTORIAL
						if ( $tip1_con_pag == 'Porcentual' ) {
							
							

							$con_his_pag = "Solicitud APROBADA de la condonación porcentual del ".$mon_con_pag." % la fecha del ".fechaFormateadaCompacta($fechaHoy);

						}else if ( $tip1_con_pag == 'Monetario' ) {

							$con_his_pag = "Solicitud APROBADA de la condonación monetaria por $".$mon_con_pag." la fecha del ".fechaFormateadaCompacta($fechaHoy);

						}

						$fec_his_pag = $fechaHoy;

						$res_his_pag = $val_con_pag;

						$est_his_pag = 'Pendiente';

						$tip_his_pag = "Condonación";

						$med_his_pag = "Sistema";

						$id_pag4 = $id_pag;


						// INSERCION HISTORIAL
						$sqlInsercionHistorial = "
							INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
							VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
						";

						$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

						if ( !$resultadoInsercionHistorial ) {
							echo $sqlInsercionHistorial;
						}else{

							// PROCESAMIENTO PAGO Y CONDONACION
							$sqlPago = "
								SELECT *
								FROM pago
								WHERE id_pag = '$id_pag'
					    	";

					    	$resultadoPago = mysqli_query( $db, $sqlPago );

					    	if ( $resultadoPago ) {


					    		$filaPago = mysqli_fetch_assoc( $resultadoPago );
					    		
					    		$mon_pag = $filaPago['mon_pag'];

					    		if ( $tip1_con_pag == 'Porcentual' ) {
					    			
					    			$montoCondonado = ( ( $mon_con_pag/100 ) * $mon_pag );
					    			$diferencia = $mon_pag - $montoCondonado;
					    			 //diferenciaCondonacion = montoAdeudo - ( (cantidadCondonacion/100 ) * ( montoAdeudo ) );

					    		}else if ( $tip1_con_pag == 'Monetario' ) {
					    			$montoCondonado = $mon_con_pag;
					    			$diferencia = $mon_pag - $montoCondonado;
					    			// diferenciaCondonacion = montoAdeudo-cantidadCondonacion;
					    		}

					    		
					    		// UNA VEZ OBTENEMOS LA DIFERENCIA, ES NECESARIO CONOCER SI ES IGUAL O MENOR
					    		if ( $diferencia > 0 ) {
					    		// PAGO PENDIENTE
					    			$mon_pag = $diferencia;

					    			$sqlUpdatePago = "
										UPDATE pago
										SET
										mon_pag = '$mon_pag'
										WHERE 
										id_pag = '$id_pag'
					    			";

					    			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

					    			if ( $resultadoUpdatePago ) {

						    			// HISTORIAL
						    			$con_his_pag = "Condonación por $".$montoCondonado." aprobado por ".$val_con_pag." la fecha del ".fechaFormateadaCompacta($fechaHoy);

										$fec_his_pag = $fechaHoy;

										$res_his_pag = $val_con_pag;

										$est_his_pag = 'Pendiente';

										$tip_his_pag = "Parcialidad";

										$med_his_pag = "Sistema";

										$id_pag4 = $id_pag;


										// INSERCION HISTORIAL
										$sqlInsercionHistorial = "
											INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
											VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
										";

										$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

										if ( !$resultadoInsercionHistorial ) {
											echo $sqlInsercionHistorial;
										}else{
											// INSERT EN notificacion_pago 
											$sqlInsertNotificacion = "
												INSERT INTO notificacion_pago( fec_not_pag, est_not_pag, mot_not_pag, id_con_pag1 )
												VALUES( '$fec_not_pag', '$est_not_pag', '$mot_not_pag', '$id_con_pag' )
											";

											$resultadoInsertNotificacion = mysqli_query( $db, $sqlInsertNotificacion );

											if ( $resultadoInsertNotificacion ) {
												// UPDATE EN pago

												// condonacion aprobada
												echo "Exito";

												// FIN UPDATE EN pago
											}else{

												echo $sqlInsertNotificacion;
											
											}

											// FIN INSERT EN notificacion_pago	
										}
										


						    			// FIN HISTORIAL
					    			}else{
					    				echo $sqlUpdatePago;
					    			}


					    		//FIN PAGO PENDIENTE
					    		}else{
					    		// PAGO PAGADO

					    			$mon_pag = $diferencia;
					    			$est_pag = 'Pagado';
					    			$pag_pag = $fechaHoy;

					    			$sqlUpdatePago = "
										UPDATE pago
										SET
										mon_pag = '$mon_pag',
										est_pag = '$est_pag',
										pag_pag = '$pag_pag'
										WHERE 
										id_pag = '$id_pag'
					    			";

					    			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

					    			if ( $resultadoUpdatePago ) {

						    			// HISTORIAL
						    			$con_his_pag = "Condonación por $".$montoCondonado." aprobado por ".$val_con_pag." la fecha del ".fechaFormateadaCompacta($fechaHoy)." liquidando el saldo pendiente.";

										$fec_his_pag = $fechaHoy;

										$res_his_pag = $val_con_pag;

										$est_his_pag = 'Pendiente';

										$tip_his_pag = "Liquidación";

										$med_his_pag = "Sistema";

										$id_pag4 = $id_pag;


										// INSERCION HISTORIAL
										$sqlInsercionHistorial = "
											INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
											VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
										";

										$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

										if ( !$resultadoInsercionHistorial ) {
											echo $sqlInsercionHistorial;
										}else{
											
											// INSERT EN notificacion_pago 
											$sqlInsertNotificacion = "
												INSERT INTO notificacion_pago( fec_not_pag, est_not_pag, mot_not_pag, id_con_pag1 )
												VALUES( '$fec_not_pag', '$est_not_pag', '$mot_not_pag', '$id_con_pag' )
											";

											$resultadoInsertNotificacion = mysqli_query( $db, $sqlInsertNotificacion );

											if ( $resultadoInsertNotificacion ) {
												// UPDATE EN pago

												// condonacion aprobada
												echo "Exito";

												// FIN UPDATE EN pago
											}else{

												echo $sqlInsertNotificacion;
											
											}

											// FIN INSERT EN notificacion_pago	
										}
										

						    			// FIN HISTORIAL
					    			}else{
					    				echo $sqlUpdatePago;
					    			}


					    		// FIN PAGO PAGADO
					    		}

					    	}else {
					    		echo $sqlPago;
					    	}
							// FIN PROCESAMIENTO PAGO Y CONDONACION

						}

			    		// FIN HISTORIAL



					// FIN APROBADO	
					}else if ( $respuesta_peticion == 'Rechazado' ) {
					// RECHAZADO

						// HISTORIAL
						if ( $tip1_con_pag == 'Porcentual' ) {

							$con_his_pag = "Solicitud RECHAZADA de la condonación porcentual del ".$mon_con_pag." % la fecha del ".fechaFormateadaCompacta($fechaHoy);

						}else if ( $tip1_con_pag == 'Monetario' ) {

							$con_his_pag = "Solicitud RECHAZADA de la condonación monetaria por $".$mon_con_pag." la fecha del ".fechaFormateadaCompacta($fechaHoy);

						}

						$fec_his_pag = $fechaHoy;

						$res_his_pag = $val_con_pag;

						$est_his_pag = 'Pendiente';

						$tip_his_pag = "Condonación";

						$med_his_pag = "Sistema";

						$id_pag4 = $id_pag;


						// INSERCION HISTORIAL
						$sqlInsercionHistorial = "
							INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
							VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
						";

						$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

						if ( !$resultadoInsercionHistorial ) {
							echo $sqlInsercionHistorial;
						}else{

							// INSERT EN notificacion_pago 
							$sqlInsertNotificacion = "
								INSERT INTO notificacion_pago( fec_not_pag, est_not_pag, mot_not_pag, id_con_pag1 )
								VALUES( '$fec_not_pag', '$est_not_pag', '$mot_not_pag', '$id_con_pag' )
							";

							$resultadoInsertNotificacion = mysqli_query( $db, $sqlInsertNotificacion );

							if ( $resultadoInsertNotificacion ) {
								// UPDATE EN pago

								// condonacion aprobada
								echo "Exito";

								// FIN UPDATE EN pago
							}else{

								echo $sqlInsertNotificacion;
							
							}

							// FIN INSERT EN notificacion_pago	

						}

			    		// FIN HISTORIAL

					// FIN RECHAZADO
					}
					// FIN RESPUESTA

		    		

		    	}else{
		    		echo $sqlCondonacion;
		    	}
				// FIN CONSULTA CONDONACION

				

				


			}else{
				echo $sqlUpdateCondonacion;
			}
			// FIN UPDATE EN condonacion_pago
			
			


		// FIN CONDONACION
		}else if ($tipo_peticion == 'Convenio' ) {
		// CONVENIO

			$id_acu_pag = $identificador_peticion;
			$est_acu_pag = $respuesta_peticion;

			// UPDATE EN convenio_pago
			$sqlUpdateConvenio = "
				UPDATE convenio_pago
				SET
				est_acu_pag = '$est_acu_pag',
				val_acu_pag = '$nomResponsable'
				WHERE id_acu_pag = '$id_acu_pag'
			";


			$resultadoUpdateConvenio = mysqli_query( $db, $sqlUpdateConvenio );

			if ( $resultadoUpdateConvenio ) {

				// CONSULTA DE CONVENIO
				$sqlConvenio = "
					SELECT *
					FROM convenio_pago
					WHERE id_acu_pag = '$id_acu_pag'
		    	";

		    	$resultadoConvenio = mysqli_query( $db, $sqlConvenio );

		    	if ( $resultadoConvenio ) {
		    		
		    		$filaConvenio = mysqli_fetch_assoc( $resultadoConvenio );
		    		$ini_acu_pag = $filaConvenio['ini_acu_pag'];
		    		$fin_acu_pag = $filaConvenio['fin_acu_pag'];
		    		$ini2_acu_pag = $filaConvenio['ini2_acu_pag'];
		    		$fin2_acu_pag = $filaConvenio['fin2_acu_pag'];

		    		$val_acu_pag = $filaConvenio['val_acu_pag'];
		    		$id_pag = $filaConvenio['id_pag3'];


		    		// RESPUESTA
					if ( $respuesta_peticion == 'Aprobado' ) {
					// APROBADO

						// HISTORIAL
						$con_his_pag = "Solicitud APROBADA del convenio de fechas del: ".fechaFormateadaCompacta($ini_acu_pag)." al ".fechaFormateadaCompacta($fin_acu_pag).", modificadas del ".fechaFormateadaCompacta($ini2_acu_pag)." al ".fechaFormateadaCompacta($fin2_acu_pag)." la fecha del ".fechaFormateadaCompacta($fechaHoy);

						$fec_his_pag = $fechaHoy;

						$res_his_pag = $val_acu_pag;

						$est_his_pag = 'Pendiente';

						$tip_his_pag = "Convenio";

						$med_his_pag = "Sistema";

						$id_pag4 = $id_pag;


						// INSERCION HISTORIAL
						$sqlInsercionHistorial = "
							INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
							VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
						";

						$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

						if ( !$resultadoInsercionHistorial ) {
							echo $sqlInsercionHistorial;
						}else{

							$ini_pag = $ini2_acu_pag;
							$fin_pag = $fin2_acu_pag;

							// PROCESAMIENTO PAGO Y CONVENIO
							$sqlUpdatePago = "
								UPDATE pago
								SET
								ini_pag = '$ini_pag',
								fin_pag = '$fin_pag'
								WHERE 
								id_pag = '$id_pag'
			    			";

			    			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

			    			if ( $resultadoUpdatePago ) {
			    				
			    				// INSERT EN notificacion_pago 
								$sqlInsertNotificacion = "
									INSERT INTO notificacion_pago( fec_not_pag, est_not_pag, mot_not_pag, id_acu_pag1 )
									VALUES( '$fec_not_pag', '$est_not_pag', '$mot_not_pag', '$id_acu_pag' )
								";

								$resultadoInsertNotificacion = mysqli_query( $db, $sqlInsertNotificacion );

								if ( $resultadoInsertNotificacion ) {
									// UPDATE EN pago

									// condonacion aprobada
									echo "Exito";

									// FIN UPDATE EN pago
								}else{

									echo $sqlInsertNotificacion;
								
								}

								// FIN INSERT EN notificacion_pago
			    			}

							// FIN PROCESAMIENTO PAGO Y CONVENIO

						}

			    		// FIN HISTORIAL



					// FIN APROBADO	
					}else if ( $respuesta_peticion == 'Rechazado' ) {
					// RECHAZADO

						// HISTORIAL
						$con_his_pag = "Solicitud RECHAZADA del convenio de fechas del: ".fechaFormateadaCompacta($ini_acu_pag)." al ".fechaFormateadaCompacta($fin_acu_pag).", modificadas del ".fechaFormateadaCompacta($ini2_acu_pag)." al ".fechaFormateadaCompacta($fin2_acu_pag)." la fecha del ".fechaFormateadaCompacta($fechaHoy);

						$fec_his_pag = $fechaHoy;

						$res_his_pag = $val_acu_pag;

						$est_his_pag = 'Pendiente';

						$tip_his_pag = "Convenio";

						$med_his_pag = "Sistema";

						$id_pag4 = $id_pag;


						// INSERCION HISTORIAL
						$sqlInsercionHistorial = "
							INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
							VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
						";

						$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

						if ( !$resultadoInsercionHistorial ) {
							echo $sqlInsercionHistorial;
						}else{

							
			    			// INSERT EN notificacion_pago 
							$sqlInsertNotificacion = "
								INSERT INTO notificacion_pago( fec_not_pag, est_not_pag, mot_not_pag, id_acu_pag1 )
								VALUES( '$fec_not_pag', '$est_not_pag', '$mot_not_pag', '$id_acu_pag' )
							";

							$resultadoInsertNotificacion = mysqli_query( $db, $sqlInsertNotificacion );

							if ( $resultadoInsertNotificacion ) {
								// UPDATE EN pago

								// condonacion aprobada
								echo "Exito";

								// FIN UPDATE EN pago
							}else{

								echo $sqlInsertNotificacion;
							
							}

							// FIN INSERT EN notificacion_pago
			    			
							

						}

			    		// FIN HISTORIAL

					// FIN RECHAZADO
					}
					// FIN RESPUESTA

		    		

		    	}else{
		    		echo $sqlCondonacion;
		    	}
				// FIN CONSULTA CONVENIO


			}else{
				echo $sqlUpdateCondonacion;
			}
			// FIN UPDATE EN convenio_pago

		// FIN CONVENIO
		}

    	// FIN TIPO DE PETICION


    	
	}
	// FIN FUNCION procesarCondonacionServer



	function obtenerEstatusPagoAlumnoServer( $id_alu_ram ){
    	require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sqlEstatusAlumno ="
		    SELECT id_alu_ram, fin_pag 
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE fin_pag <'$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		$resultadoEstatusAlumno = mysqli_query( $db, $sqlEstatusAlumno );

		if ( $resultadoEstatusAlumno ) {
			$validacionEstatusAlumno = mysqli_num_rows( $resultadoEstatusAlumno );

			if ( $validacionEstatusAlumno > 0 ) {
				return "Con adeudo";
			}else{
				return "Sin adeudo";
			}

		}else{
			echo $sqlEstatusAlumno;
		}

   	}

   	function obtenerEstatusPagoAlumno( $id_alu_ram ){
    	require('../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sqlEstatusAlumno ="
		    SELECT id_alu_ram, fin_pag 
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE fin_pag <'$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		$resultadoEstatusAlumno = mysqli_query( $db, $sqlEstatusAlumno );

		if ( $resultadoEstatusAlumno ) {
			$validacionEstatusAlumno = mysqli_num_rows( $resultadoEstatusAlumno );

			if ( $validacionEstatusAlumno > 0 ) {
				return "Con adeudo";
			}else{
				return "Sin adeudo";
			}

		}else{
			echo $sqlEstatusAlumno;
		}

   	}
   	// FIN FUNCION PARA OBTENER ESTATUS DE PAGOS DE ALUMNO


   	function obtenerEstatusPago( $id_pag ) {

   		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sqlEstatusPago ="
		    SELECT * 
		    FROM pago
		    WHERE id_pag = '$id_pag'
		";

		$resultadoEstatusPago = mysqli_query( $db, $sqlEstatusPago );

		if ( $resultadoEstatusPago ) {
			
			$filaEstatusPago = mysqli_fetch_assoc( $resultadoEstatusPago );


			$fin_pag = $filaEstatusPago['fin_pag'];
			$est_pag = $filaEstatusPago['est_pag'];

			if ( $est_pag == 'Pagado' ) {
				
				return '<span class="badge badge-success font-weight-normal letraPequena">Pagado</span>';
			
			}else if ( $fin_pag < $fechaHoy && $est_pag == 'Pendiente' ) {

				return '<span class="badge badge-danger font-weight-normal letraPequena">Vencido</span>';
			
			}else {

				return '<span class="badge badge-warning font-weight-normal letraPequena">Pendiente</span>';
			}	

		}else{
			echo $sqlEstatusPago;
		}

   	}



    function obtenerEstatusPago2( $id_pag ) {

      require('../../includes/conexion.php');

      $fechaHoy = date('Y-m-d');

      $sqlEstatusPago ="
          SELECT * 
          FROM pago
          WHERE id_pag = '$id_pag'
      ";

      $resultadoEstatusPago = mysqli_query( $db, $sqlEstatusPago );

      if ( $resultadoEstatusPago ) {
        
        $filaEstatusPago = mysqli_fetch_assoc( $resultadoEstatusPago );


        $fin_pag = $filaEstatusPago['fin_pag'];
        $est_pag = $filaEstatusPago['est_pag'];

        if ( $est_pag == 'Pagado' ) {
          
          return 'Pagado';
        
        }else if ( $fin_pag < $fechaHoy && $est_pag == 'Pendiente' ) {

          return 'Vencido';
        
        }else {

          return 'Pendiente';
        } 

      }else{
        echo $sqlEstatusPago;
      }

    }






   	function obtenerEstatusPagoSimpleServer( $id_pag ){

   		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sqlEstatusPago ="
		    SELECT * 
		    FROM pago
		    WHERE id_pag = '$id_pag'
		";

		$resultadoEstatusPago = mysqli_query( $db, $sqlEstatusPago );

		if ( $resultadoEstatusPago ) {
			
			$filaEstatusPago = mysqli_fetch_assoc( $resultadoEstatusPago );


			$fin_pag = $filaEstatusPago['fin_pag'];
			$est_pag = $filaEstatusPago['est_pag'];

			if ( $est_pag == 'Pagado' ) {
				
				return 'Pagado';
			
			}else if ( $fin_pag < $fechaHoy && $est_pag == 'Pendiente' ) {

				return 'Vencido';
			
			}else {

				return 'Pendiente';
			}	

		}else{
			echo $sqlEstatusPago;
		}

	}
	   



	// FUNCION PARA SABER ESTATUS DE ALUMNO SI DEBE DOCUMENTACION
	function obtenerEstatusDocumentacionAlumnoServer( $id_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT *
			FROM documento_alu_ram
			WHERE est_doc_alu_ram = 'Pendiente' AND id_alu_ram11 = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if( $resultado ){
			$total = mysqli_num_rows( $resultado );

			if ( $total > 0 ) {
				return '<a class="chip grey darken-1 text-white waves-effect letraPequena font-weight-normal obtenerDocumentosPendientes" id_alu_ram="'.$id_alu_ram.'">Pendiente</a>';
			}else { 
				return '<a class="chip success-color text-white waves-effect letraPequena font-weight-normal obtenerDocumentosPendientes" id_alu_ram="'.$id_alu_ram.'">Entregados</a>';
			}

		}else{
			echo $sql;
		}
	}

	// FUNCION PARA SABER ESTATUS DE ALUMNO SI DEBE DOCUMENTACION
	function obtenerTextoEstatusDocumentacionAlumnoServer( $id_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT *
			FROM documento_alu_ram
			WHERE est_doc_alu_ram = 'Pendiente' AND id_alu_ram11 = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if( $resultado ){
			$total = mysqli_num_rows( $resultado );

			if ( $total > 0 ) {
				return "Pendiente";
			}else { 
				return 'Entregados';
			}

		}else{
			echo $sql;
		}
	}




	function obtenerValidacionCreacionHorarioServer( $id_sal2, $ini2_hor, $fin2_hor, $dia2_hor, $id_pro2 ) {
		require('../../includes/conexion.php');

			$bool = 'false';

			$sqlPrevio = "
				SELECT *
				FROM sub_hor
				INNER JOIN horario ON horario.id_sub_hor1 = sub_hor.id_sub_hor
				INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
				WHERE (id_pro1 = '$id_pro2' OR id_sal1 ='$id_sal2') AND est_sub_hor = 'Activo'
			";
			//echo $sqlPrevio;

			$resultadoConteo = mysqli_query( $db, $sqlPrevio );

			$conteo = mysqli_num_rows( $resultadoConteo );

			if ( $conteo == 0 ) {
				
				return 'true';

			} else {

				// ELSE CONTEO 0

				$resultadoPrevio = mysqli_query( $db, $sqlPrevio );

				while( $filaPrevio = mysqli_fetch_assoc( $resultadoPrevio ) ) {

					$id_sub_hor = $filaPrevio['id_sub_hor']; 

					// VARIABLES DE LOS PREVIOS DE id_sub_hor
					$ini1_hor = $filaPrevio['ini_hor'];
					$fin1_hor = $filaPrevio['fin_hor'];
					$dia1_hor = $filaPrevio['dia_hor'];
					$nom1_mat = $filaPrevio['nom_mat'];
					$id_sal1 = $filaPrevio['id_sal1'];
					$id_pro1 = $filaPrevio['id_pro1'];
					// echo $dia1_hor." == ".$dia2_hor."<br>";
					// echo $nom1_mat." - ".$nom2_mat."<br>";
					if ( $id_pro1 == $id_pro2 ) {

						if ( ( $id_sal1 == $id_sal2 ) || ( ( $id_sal1 == $id_sal2 ) && ( $id_sal2 == NULL ) ) ) {
						
							if ( $dia1_hor == $dia2_hor ) {

								if ( ( date( "H:i" ,strtotime( $fin1_hor ) ) <= date( "H:i" ,strtotime( $ini2_hor ) ) ) || ( date( "H:i" ,strtotime( $ini1_hor ) ) >= date( "H:i" ,strtotime( $fin2_hor ) ) ) ) {
							
									$bool = 'true';
								
								}else{

									// echo $fin1_hor." <= ".$ini2_hor." || ".$ini1_hor." >= ".$fin2_hor."<br>";
									// echo "linea 6078<br>";
									$tipo = "Ambos";
									$mensaje = "El profesor y el salón no están disponibles";
									$bool = 'false';

								}

							} else {
								$bool = "true";
							}

							if ( $bool == 'false' ) {

								// echo "linea 6087";
								obtenerHorarioEmpalmadoServer( $id_sub_hor, $mensaje, $tipo );
								// return "ERROR";
								// break;
								// break;
								// break;

							}



						} else {

							if ( $dia1_hor == $dia2_hor ) {
							
								if ( ( date( "H:i" ,strtotime( $fin1_hor ) ) <= date( "H:i" ,strtotime( $ini2_hor ) ) ) || ( date( "H:i" ,strtotime( $ini1_hor ) ) >= date( "H:i" ,strtotime( $fin2_hor ) ) ) ) {
							
									$bool = 'true';
								
								}else{
									// echo $fin1_hor." <= ".$ini2_hor." || ".$ini1_hor." >= ".$fin2_hor."<br>";
									$bool = 'false';
									$mensaje = "El profesor no está disponible";
									$tipo = "Profesor";
								}

							} else {
								$bool = "true";
							}

							if ( $bool == 'false' ) {

								
								// echo "linea 6118";
								obtenerHorarioEmpalmadoServer( $id_sub_hor, $mensaje, $tipo );
								// break;
								// break;
								// break;
							}
						
						}
						
					} else {

						if ( $id_sal1 == $id_sal2 ) {
						
							if ( $dia1_hor == $dia2_hor ) {
							
								if ( ( date( "H:i" ,strtotime( $fin1_hor ) ) <= date( "H:i" ,strtotime( $ini2_hor ) ) ) || ( date( "H:i" ,strtotime( $ini1_hor ) ) >= date( "H:i" ,strtotime( $fin2_hor ) ) ) ) {
							
									$bool = 'true';
								
								}else{

									$bool = 'false';
									$mensaje = "El salón no está disponible";
									$tipo = "Salón";
								}

							} else {
								$bool = "true";
							}

							if ( $bool == 'false' ) {

								// echo "linea 6149";
								obtenerHorarioEmpalmadoServer( $id_sub_hor, $mensaje, $tipo );
								// return "ERROR";
								// break;
								// break;
								// break;

							}



						} else {

							$bool = 'true';
						
						}
					}

				}
				
				

				if ( $bool == 'true' ) {
					return 'true';
				}

				// FIN ELSE CONTEO 0

			}

			
		
	}







	// FUNCION PARA VERIFICAR EL EMPALME DE HORARIOS EN INSCRIPCION
	function obtenerValidacionHorarioInscripcionServer( $id_sub_hor ) {
		require('../../includes/conexion.php');

		$ultimo = ( sizeof( $id_sub_hor ) - 1 );
		//var_dump( $id_sub_hor );

		$sqlUltimo = "
			SELECT *
			FROM sub_hor
			INNER JOIN horario ON horario.id_sub_hor1 = sub_hor.id_sub_hor
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			WHERE id_sub_hor = '$id_sub_hor[$ultimo]';
		";
		
		$resultadoUltimo = mysqli_query( $db, $sqlUltimo );

		if ( $resultadoUltimo ) {

			$filaUltimo = mysqli_fetch_assoc( $resultadoUltimo );
			// VARIABLES DEL ULTIMO id_sub_hor
			$ini1_hor = $filaUltimo['ini_hor'];
			$fin1_hor = $filaUltimo['fin_hor'];
			$dia1_hor = $filaUltimo['dia_hor'];
			$nom1_mat = $filaUltimo['nom_mat'];

			$bool = 'false';

			for ( $i = 0, $mensaje = '' ; $i < $ultimo ; $i++ ) {

				$sqlPrevio = "
					SELECT *
					FROM sub_hor
					INNER JOIN horario ON horario.id_sub_hor1 = sub_hor.id_sub_hor
					INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					WHERE id_sub_hor = '$id_sub_hor[$i]';
				";

				$resultadoPrevio = mysqli_query( $db, $sqlPrevio );

				if ( $resultadoPrevio ) {
					
					$filaPrevio = mysqli_fetch_assoc( $resultadoPrevio );
					// VARIABLES DE LOS PREVIOS DE id_sub_hor
					$ini2_hor = $filaPrevio['ini_hor'];
					$fin2_hor = $filaPrevio['fin_hor'];
					$dia2_hor = $filaPrevio['dia_hor'];
					$nom2_mat = $filaPrevio['nom_mat'];
					
					// echo $dia1_hor." == ".$dia2_hor;
					// echo $nom1_mat." - ".$nom2_mat;
					
					if ( $dia1_hor == $dia2_hor ) {
						
						if ( ( $fin1_hor <= $ini2_hor ) || ( $ini1_hor >= $fin2_hor ) ) {
					
							$bool = 'true';
						
						}else{

							$bool = 'false';
						}

					} else {

						$bool = 'true';
					
					}

					if ( $bool == 'false' ) {

						return $bool;
						break;
						break;

						

					}


				}
			
			}

			if ( $bool == 'true' ) {

				return $bool;
			}

		}else{

			echo $sql;
		}

	}



	// FUNCION PARA VERIFICAR EL EMPALME DE HORARIOS EN INSCRIPCION
	function obtenerMensajeValidacionHorarioInscripcionServer( $id_sub_hor ) {
		require('../../includes/conexion.php');

		$ultimo = ( sizeof( $id_sub_hor ) - 1 );
		//var_dump( $id_sub_hor );

		$sqlUltimo = "
			SELECT *
			FROM sub_hor
			INNER JOIN horario ON horario.id_sub_hor1 = sub_hor.id_sub_hor
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			WHERE id_sub_hor = '$id_sub_hor[$ultimo]';
		";
		
		$resultadoUltimo = mysqli_query( $db, $sqlUltimo );

		if ( $resultadoUltimo ) {

			$filaUltimo = mysqli_fetch_assoc( $resultadoUltimo );
			// VARIABLES DEL ULTIMO id_sub_hor
			$ini1_hor = $filaUltimo['ini_hor'];
			$fin1_hor = $filaUltimo['fin_hor'];
			$dia1_hor = $filaUltimo['dia_hor'];
			$nom1_mat = $filaUltimo['nom_mat'];


			$bool = 'false';
			for ( $i = 0, $mensaje = '' ; $i < $ultimo ; $i++ ) {

				$sqlPrevio = "
					SELECT *
					FROM sub_hor
					INNER JOIN horario ON horario.id_sub_hor1 = sub_hor.id_sub_hor
					INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
					WHERE id_sub_hor = '$id_sub_hor[$i]';
				";

				$resultadoPrevio = mysqli_query( $db, $sqlPrevio );

				if ( $resultadoPrevio ) {
					
					$filaPrevio = mysqli_fetch_assoc( $resultadoPrevio );
					// VARIABLES DE LOS PREVIOS DE id_sub_hor
					$ini2_hor = $filaPrevio['ini_hor'];
					$fin2_hor = $filaPrevio['fin_hor'];
					$dia2_hor = $filaPrevio['dia_hor'];
					$nom2_mat = $filaPrevio['nom_mat'];
					
					// echo $dia1_hor." == ".$dia2_hor;
					// echo $nom1_mat." - ".$nom2_mat;
					
					if ( $dia1_hor == $dia2_hor ) {
								
						if ( ( $fin1_hor <= $ini2_hor ) || ( $ini1_hor >= $fin2_hor ) ) {
					
							$bool = 'true';
						
						}else{

							$bool = 'false';
						}

					} else {

						$bool = 'true';
					
					}

					if ( $bool == 'false' ) {
						$mensaje = $nom1_mat." se empalma con ".$nom2_mat;
						$mensaje2 = $nom1_mat." de ".$ini1_hor." a ".$fin1_hor." se empalma con ".$nom2_mat." de ".$ini2_hor." a ".$fin2_hor;
						$mensaje3 = "La materia ".$nom1_mat." ha sido removida";
?>
						<script>

							var id_sub_hor = <?php echo $id_sub_hor[$ultimo]; ?>;
							swal( 'Error de empalme' , '<?php echo $mensaje2; ?>', "error", {button: "Aceptar",});
				

							for ( var i = 0 ; i < $( ".filasHorario" ).length ; i++ ) {
								if ( $(".filasHorario").eq(i).attr("sub_hor") == id_sub_hor ){
									$(".filasHorario").eq(i).remove();
								}
							}
							
						</script>


<?php
						break;
						break;

					}


				}
			
			}

			if ( $bool == 'true' ) {
?>
		
		


<?php
			}


		}else{

			echo $sql;
		}

	}

	
	function generarMatriculaCompuestaServer( $plantel ){
		require('../includes/conexion.php');

		$fecha = date('my');
        $sqlConteo = "
          SELECT MAX( id_alu ) AS maximo
          FROM alumno
          WHERE id_pla8 = '$plantel'
        ";

        $resultadoConteo = mysqli_query( $db, $sqlConteo );

        $filaConteo = mysqli_fetch_assoc( $resultadoConteo );

        $maximo = $filaConteo['maximo'];

        if ( strlen( $maximo ) < 6 ) {
          $diferencia = 6 - strlen( $maximo );

          $matricula = "";

          for ($i = 0; $i < $diferencia; $i++) { 
            $matricula = "0".$matricula;
          }

          $matricula = $fecha.$matricula.$maximo;

          return $matricula;

        }else{

          return $maximo;

        }
	}



	function obtenerFechasActividadesServer( $inicio, $fin, $ini_cic ){
		
		require('../../includes/conexion.php');

		if ( $inicio != "" && $fin != "" ) {
			
			$inicio_copia = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$inicio.' day' , strtotime ( $ini_cic )));
			$fin_copia = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$fin.' day' , strtotime ( $ini_cic )));

			$fechas = [
				"inicio_copia" => $inicio_copia,
				"fin_copia" => $fin_copia
			];

			return $fechas;
			
		}

	}


	function obtenerEstatus1AlumnoRamaServer( $id_alu_ram ) {

		require('../../includes/conexion.php');

		$sqlAlumno = "
			SELECT *
			FROM alu_ram
			WHERE id_alu_ram = '$id_alu_ram'
		";

		$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

		$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );
		$est1_alu_ram = $filaAlumno['est1_alu_ram'];
		$est2_alu_ram = $filaAlumno['est2_alu_ram'];

		// echo $est1_alu_ram;
		// echo $est2_alu_ram;

		if ( $est2_alu_ram == 'Baja' ) {
			
			$est1_alu_ram = 'REC';
			
			$sqlUpdate = "
				UPDATE alu_ram
				SET
				est1_alu_ram = '$est1_alu_ram',
				est2_alu_ram = NULL
				WHERE
				id_alu_ram = '$id_alu_ram'	
			";

			$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

			if ( !$resultadoUpdate ) {
				echo $sqlUpdate;
			}
		} else {

			if ( $est1_alu_ram == NULL ) {
			
				$est1_alu_ram = 'N';
				
				$sqlUpdate = "
					UPDATE alu_ram
					SET
					est1_alu_ram = '$est1_alu_ram'
					WHERE
					id_alu_ram = '$id_alu_ram'	
				";

				$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

				if ( !$resultadoUpdate ) {
					echo $sqlUpdate;
				}
			} else if ( ($est1_alu_ram == 'N') || ($est1_alu_ram == 'REC') ) {
				
				$est1_alu_ram = 'R';
				
				$sqlUpdate = "
					UPDATE alu_ram
					SET
					est1_alu_ram = '$est1_alu_ram'
					WHERE
					id_alu_ram = '$id_alu_ram'	
				";

				$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

				if ( !$resultadoUpdate ) {
					echo $sqlUpdate;
				}
			}

		}
		
		
	}


	function obtenerSaldoAlumnoFechaHoyServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, fin_pag, SUM(mon_pag) AS saldoPendiente
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE ( ini_pag <= '$fechaHoy' ) AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$fila = mysqli_fetch_assoc( $resultado );

			return round($fila['saldoPendiente'], 2);

		}else{
			echo $sql;
		}
	}



	function obtenerSaldoAlumnoFechaHoy( $id_alu_ram ) {
		require('../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, fin_pag, SUM(mon_pag) AS saldoPendiente
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE ( ini_pag <= '$fechaHoy' ) AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$fila = mysqli_fetch_assoc( $resultado );

			return round($fila['saldoPendiente'], 2);

		}else{
			echo $sql;
		}
	}


	function obtenerRegistrosPendientesFechaHoyServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, fin_pag
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE fin_pag < '$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		//echo $sql;

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$total = mysqli_num_rows( $resultado );

			return $total;

		}else{
			echo $sql;
		}
	}


	function obtenerRegistrosPendientesGlobalServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$total = mysqli_num_rows( $resultado );

			return $total;

		}else{
			echo $sql;
		}
	}


	function obtenerRegistrosPagadosFechaHoyServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, fin_pag
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE fin_pag < '$fechaHoy' AND est_pag = 'Pagado' AND id_alu_ram = '$id_alu_ram'
		    
		";

		//echo $sql;

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$total = mysqli_num_rows( $resultado );

			return $total;

		}else{
			echo $sql;
		}
	}

	function ObtenerRegistrosPagadosGlobalServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE est_pag = 'Pagado' AND id_alu_ram = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$total = mysqli_num_rows( $resultado );

			return $total;

		}else{
			echo $sql;
		}
	}


	function obtenerSaldoAlumnoGlobalServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, SUM(mon_pag) AS saldoPendiente
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
		";

		//echo $sql;

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$fila = mysqli_fetch_assoc( $resultado );

			return round($fila['saldoPendiente'], 2);

		}else{
			echo $sql;
		}
	}

	function obtenerPagadoAlumnoFechaHoyServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, fin_pag, SUM(mon_abo_pag) AS saldoPagado
		    FROM abono_pago
		    INNER JOIN pago ON pago.id_pag = abono_pago.id_pag1
		    INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
		    WHERE fin_pag < '$fechaHoy' AND id_alu_ram = '$id_alu_ram'
		";

		//echo $sql;

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$fila = mysqli_fetch_assoc( $resultado );

			return round($fila['saldoPagado'], 2);

		}else{
			echo $sql;
		}
	}



	function obtenerPagadoAlumnoGlobalServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, SUM(mon_abo_pag) AS saldoPagado
		    FROM abono_pago
		    INNER JOIN pago ON pago.id_pag = abono_pago.id_pag1
		    INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
		    WHERE id_alu_ram = '$id_alu_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$fila = mysqli_fetch_assoc( $resultado );

			return round($fila['saldoPagado'], 2);

		}else{
			echo $sql;
		}
	}


	function obtenerMateriasProgramaServer ( $id_ram ) {
		require('../../includes/conexion.php');

		$sql ="
		    SELECT *
		    FROM materia
		    WHERE id_ram2 = '$id_ram'
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$total = mysqli_num_rows( $resultado );

			return $total;

		}else{
			echo $sql;
		}
	}

	function obtenerMateriasAprobadasAlumnoServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');
		$sql = "
			SELECT *
			FROM calificacion
			WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal > 6	
		";

		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			$total = mysqli_num_rows( $resultado );

			return $total;

		}else{
			echo $sql;
		}
	}


	function obtenerTotalAbonadoPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sqlValidacionPagado = "
          SELECT *
          FROM abono_pago
          WHERE id_pag1 = '$id_pag'
        ";

        $resultadoValidacionPagado = mysqli_query( $db, $sqlValidacionPagado );

        if ( $resultadoValidacionPagado ) {
          
			$validacionPagado = mysqli_num_rows( $resultadoValidacionPagado );

			if ( $validacionPagado > 0 ) {

				$sqlTotalPagado = "
				  SELECT SUM(mon_abo_pag) AS totalPagado
				  FROM abono_pago
				  WHERE id_pag1 = '$id_pag'
				";

				$resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );

				if ( $resultadoTotalPagado ) {
					$filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );
					$totalAbonado = $filaTotalPagado['totalPagado'];
					return $totalAbonado;

				} else {
					echo $sqlTotalPagado;
				}
			}
		} else {
			echo $sqlValidacionPagado;
		}

	}



  function obtenerTotalAbonadoPago( $id_pag ) {
    require('../includes/conexion.php');

        $sqlValidacionPagado = "
          SELECT *
          FROM abono_pago
          WHERE id_pag1 = '$id_pag'
        ";

        $resultadoValidacionPagado = mysqli_query( $db, $sqlValidacionPagado );

        if ( $resultadoValidacionPagado ) {
          
      $validacionPagado = mysqli_num_rows( $resultadoValidacionPagado );

      if ( $validacionPagado > 0 ) {

        $sqlTotalPagado = "
          SELECT SUM(mon_abo_pag) AS totalPagado
          FROM abono_pago
          WHERE id_pag1 = '$id_pag'
        ";

        $resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );

        if ( $resultadoTotalPagado ) {
          $filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );
          $totalAbonado = $filaTotalPagado['totalPagado'];
          return $totalAbonado;

        } else {
          echo $sqlTotalPagado;
        }
      }
    } else {
      echo $sqlValidacionPagado;
    }

  }


	function obtenerTotalRecargoPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sqlValidacionPagado = "
          SELECT *
          FROM recargo_pago
          WHERE id_pag5 = '$id_pag'
        ";

        $resultadoValidacionPagado = mysqli_query( $db, $sqlValidacionPagado );

        if ( $resultadoValidacionPagado ) {
          
			$validacionPagado = mysqli_num_rows( $resultadoValidacionPagado );

			if ( $validacionPagado > 0 ) {

				$sqlTotalPagado = "
				  SELECT SUM(mon_rec_pag) AS totalRecargo
				  FROM recargo_pago
				  WHERE id_pag5 = '$id_pag'
				";

				$resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );

				if ( $resultadoTotalPagado ) {
					$filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );
					$totalAbonado = $filaTotalPagado['totalRecargo'];
					return round($totalAbonado, 2);

				} else {
					echo $sqlTotalPagado;
				}
			}
		} else {
			echo $sqlValidacionPagado;
		}

	}



	function obtenerTotalCondonacionesPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT *
          FROM condonacion_pago
          WHERE id_pag2 = '$id_pag'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$total = mysqli_num_rows( $resultado );
			
			return $total;
		} else {
			echo $sql;
		}

	}


	function obtenerTotalConveniosPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT *
          FROM convenio_pago
          WHERE id_pag3 = '$id_pag'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$total = mysqli_num_rows( $resultado );
			
			return $total;
		} else {
			echo $sql;
		}

	}



	function obtenerTotalWhatsappPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT *
          FROM historial_pago
          WHERE id_pag4 = '$id_pag' AND med_his_pag = 'Whatsapp'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$total = mysqli_num_rows( $resultado );
			
			return $total;
		} else {
			echo $sql;
		}

	}



	function obtenerTotalSmsPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT *
          FROM historial_pago
          WHERE id_pag4 = '$id_pag' AND med_his_pag = 'SMS'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$total = mysqli_num_rows( $resultado );
			
			return $total;
		} else {
			echo $sql;
		}

	}


	function obtenerTotalEmailPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT *
          FROM historial_pago
          WHERE id_pag4 = '$id_pag' AND med_his_pag = 'Correo'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$total = mysqli_num_rows( $resultado );
			
			return $total;
		} else {
			echo $sql;
		}

	}



	function obtenerTotalHistorialPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT *
          FROM historial_pago
          WHERE id_pag4 = '$id_pag'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$total = mysqli_num_rows( $resultado );
			
			return $total;
		} else {
			echo $sql;
		}

	}


	function obtenerMontoCondonadoPagoServer( $id_pag ) {
		require('../../includes/conexion.php');

        $sql = "
          SELECT SUM( can_con_pag ) AS montoCondonado
          FROM condonacion_pago
          WHERE id_pag2 = '$id_pag' AND est_con_pag = 'Aprobado'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$fila = mysqli_fetch_assoc( $resultado );
			
			return round( $fila['montoCondonado'], 2);
		} else {
			echo $sql;
		}

	}





	// FUNCION PARA SABER ESTATUS DE ALUMNO SI DEBE DOCUMENTACION
	function obtenerEstatusActividadAcademicaAlumnoServer( $id_alu_ram ){
		
		require('../../includes/conexion.php');
		$fechaHoy = date('Y-m-d');

		$sql = "
			SELECT *
			FROM cal_act
			INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
			WHERE ( id_alu_ram4 = '$id_alu_ram' AND  fec_cal_act IS NULL ) AND ( fin_for_cop < '$fechaHoy' ) 
			UNION
			SELECT *
			FROM cal_act
			INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
			WHERE ( id_alu_ram4 = '$id_alu_ram' AND  fec_cal_act IS NULL ) AND ( fin_ent_cop < '$fechaHoy' )
			UNION
			SELECT *
			FROM cal_act
			INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
			WHERE ( id_alu_ram4 = '$id_alu_ram' AND  fec_cal_act IS NULL ) AND ( fin_exa_cop < '$fechaHoy' )	
		";

		// echo $sql;
		$resultado = mysqli_query( $db, $sql );

		if( $resultado ){

			
			$contador = mysqli_num_rows( $resultado );

			if ( $contador > 0 ) {

				return 'Adeudo';
			
			} else {

				return 'N/A';
			
			}

		}else{
			echo $sql;
		}
	}



	// OBTENER NOMBRE

	function obtenerNombreAlumnoServer ( $id_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM alumno
	        INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
	        WHERE id_alu_ram = '$id_alu_ram'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );
			return $fila['nom_alu'].' '.$fila['app_alu'].' '.$fila['apm_alu'];

		}

	}


	function obtenerDatosPagosReglasServer( $identificador, $tipo ){
		require('../../includes/conexion.php');

		if ( $tipo == 'Recurrente' ) {
			$sql = "
		        SELECT *
		        FROM pago_ciclo
		        INNER JOIN ciclo ON ciclo.id_cic = pago_ciclo.id_cic3
		        WHERE id_pag_cic = '$identificador'
		    ";

		} else if ( $tipo == 'Global' ) {

			$sql = "
		        SELECT *
		        FROM pago_rama
		        INNER JOIN rama ON rama.id_ram = pago_rama.id_ram4
		        WHERE id_pag_ram = '$identificador'
		    ";
		}

		

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );
			return $fila;

		}


	}




	function obtenerDatosPagoAlumnoServer( $identificador ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM pago
	        WHERE id_pag = '$identificador'
	    ";


	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {

			$fila = mysqli_fetch_assoc( $resultado );
			return $fila;

		}


	}


	function obtenerDatosSalonLogServer( $id_sal ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM salon
	        WHERE id_sal = '$id_sal'
	    ";


	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {

			$fila = mysqli_fetch_assoc( $resultado );
			return $fila;

		}
	}





	function  obtenerNombreTablaAlumnoServer( $id_alu ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM alumno
	        WHERE id_alu = '$id_alu'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );
			return $fila['nom_alu'].' '.$fila['app_alu'].' '.$fila['apm_alu'];

		}
	}


	function obtenerDescripcionActivacionAlumnoLogServer( $tipoUsuario, $nomResponsable, $accion, $nombreAlumno ){
		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipoUsuario == 'Admin' ) {
          
          $des_log = 'El Administrador: '.$nomResponsable." ".$accion." al alumno ".$nombreAlumno.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipoUsuario == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$nomResponsable." ".$accion." al alumno ".$nombreAlumno.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}


  function obtenerDescripcionActivacionEjecutivoLogServer( $tipoUsuario, $nomResponsable, $accion, $nombreEjecutivo ){
    $fechaHoy = date( 'Y-m-d H:i:s' );


    if ( $tipoUsuario == 'Admin' ) {
          
          $des_log = 'El Administrador: '.$nomResponsable." ".$accion." al ejecutivo ".$nombreEjecutivo.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            
        } else if ( $tipoUsuario == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$nomResponsable." ".$accion." al ejecutivo ".$nombreEjecutivo.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        } else if ( $tipoUsuario == 'Adminco' ) {

            $des_log = 'El Gerente Comercial: '.$nomResponsable." ".$accion." al ejecutivo ".$nombreEjecutivo.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

    return $des_log;
  }



	function obtenerDescripcionActivacionProfesorLogServer( $tipoUsuario, $nomResponsable, $accion, $nombreProfesor ){
		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipoUsuario == 'Admin' ) {
          
          $des_log = 'El Administrador: '.$nomResponsable." ".$accion." al profesor ".$nombreProfesor.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipoUsuario == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$nomResponsable." ".$accion." al alumno ".$nombreProfesor.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}


	function obtenerDescripcionDocumentacionAlumnoLogServer( $tipoUsuario, $nomResponsable, $accion, $nombreDocumento, $nombreAlumno, $nombrePrograma ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipoUsuario == 'Admin' ) {
          				
          $des_log = 'El Administrador: '.$nomResponsable." ".$accion." el documento ( ".$nombreDocumento." ) al alumno ".$nombreAlumno." del programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipoUsuario == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$nomResponsable." ".$accion." el documento ( ".$nombreDocumento." ) al alumno ".$nombreAlumno." del programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}


	function obtenerDatosDocumentacionAlumno( $id_doc_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM documento_alu_ram
	        INNER JOIN documento_rama ON documento_rama.id_doc_ram = documento_alu_ram.id_doc_ram1
	        INNER JOIN alu_ram ON alu_ram.id_alu_ram = documento_alu_ram.id_alu_ram11
	        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
	        INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
	        WHERE id_doc_alu_ram = '$id_doc_alu_ram'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );
			return $fila;

		}


	}



	// function obtenerNombreAlumnoServer ( $id_pag ){
	// 	require('../../includes/conexion.php');

	// 	$sql = "
	//         SELECT *
	//         FROM alumno
	//         INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
	//         WHERE id_alu_ram = '$id_alu_ram'
	//     ";

	//     $resultado = mysqli_query( $db, $sql );

	//     if ( $resultado ) {
	      

	// 		$fila = mysqli_fetch_assoc( $resultado );
	// 		return $fila['nom_alu'].' '.$fila['app_alu'].' '.$fila['apm_alu'];

	// 	}

	// }


	function obtenerNombreProgramaServer ( $id_ram ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM rama
	        WHERE id_ram = '$id_ram'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila['nom_ram'];

		}

	}


	function obtenerDatosCicloPrograma( $id_cic ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM ciclo
	        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	        WHERE id_cic = '$id_cic'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}

	}


	function obtenerDatosDocumentacionProgramaServer( $id_doc_ram ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM documento_rama
	        INNER JOIN rama ON rama.id_ram = documento_rama.id_ram6
	        WHERE id_doc_ram = '$id_doc_ram'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}
	}



	function obtenerDatosAlumnoProgramaServer( $id_alu_ram ){
		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM alu_ram
	        INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
	        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
	        WHERE id_alu_ram = '$id_alu_ram'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}

	}



  function obtenerDatosAlumnoPrograma( $id_alu_ram ){
    require('../includes/conexion.php');

    $sql = "
          SELECT *
          FROM alu_ram
          INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
          INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
          WHERE id_alu_ram = '$id_alu_ram'
      ";

      $resultado = mysqli_query( $db, $sql );

      if ( $resultado ) {
        

      $fila = mysqli_fetch_assoc( $resultado );

      return $fila;

    }

  }

	function obtenerDescripcionAbonosLogServer( $tipo_quien, $quien, $que, $cuanto, $a_que, $a_quien  ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
      
          $des_log = 'El Administrador: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        } else if ( $tipo_quien == 'Adminco' ) {

            $des_log = 'El Administrador Comercial: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Cobranza' ) {
            
            $des_log = 'El Área de Cobranza: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        } else if ( $tipo_quien == 'Profesor' ) {
            
          $des_log = 'El Profesor: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        } else if ( $tipo_quien == 'Ejecutivo' ) {
          
            $des_log = 'El Ejecutivo: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        } else if ( $tipo_quien == 'Alumno' ) {
            
            $des_log = 'El Alumno: '.$quien.", realizó un(a) ".$que." por $ ".round($cuanto, 3)." en ".$a_que." a ".$a_quien.". Registrado el ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;

	}


	
	function obtenerDescripcionPeticionPagoLogServer( $tipo_quien, $quien, $que, $tipo_peticion, $peticion, $a_quien ){

		$fechaHoy = date( 'Y-m-d H:i:s' );

		if ( $tipo_quien == 'Admin' ) {
          	// El Administrador: Juan Zarate, registro una condonacion ( $con_his_pag ). Para $nombreAlumno 
          	$des_log = 'El Administrador: '.$quien." ".$que." un(a) ".$tipo_peticion." ( ".$peticion." ). Para ".$a_quien.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Cobranza' ) {

            $des_log = 'El Personal de Cobranza: '.$quien." ".$que." un(a) ".$tipo_peticion." ( ".$peticion." ). Para ".$a_quien.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}





	function obtenerDescripcionAlumnoLogServer( $tipo_quien, $quien, $que, $a_quien, $donde  ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
          
          $des_log = 'El Administrador: '.$quien." ".$que." al alumno ".$a_quien." en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." al alumno ".$a_quien." en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;

	}


	function obtenerDatosActividadServer( $tipo, $identificador ){
		require('../../includes/conexion.php');

		if ( $tipo == 'Foro' ) {

			$sql = "
				SELECT *
				FROM foro
				INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_for = '$identificador'
			";

		} else if ( $tipo == 'Entregable' ) {

			$sql = "
				SELECT *
				FROM entregable
				INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_ent = '$identificador'
			";

		} else if ( $tipo == 'Examen' ) {

			$sql = "
				SELECT *
				FROM examen
				INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_exa = '$identificador'
			";
			
		} else if ( $tipo == 'Wiki' ) {

			$sql = "
				SELECT *
				FROM wiki
				INNER JOIN bloque ON bloque.id_blo = wiki.id_blo2
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_wik = '$identificador'
			";
			
		
		} else if ( $tipo == 'Archivo' ) {

			$sql = "
				SELECT *
				FROM archivo
				INNER JOIN bloque ON bloque.id_blo = archivo.id_blo3
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_arc = '$identificador'
			";
			
		
		} else if ( $tipo == 'Video' ) {

			$sql = "
				SELECT *
				FROM video
				INNER JOIN bloque ON bloque.id_blo = video.id_blo1
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				WHERE id_vid = '$identificador'
			";
			
		
		}

		$resultado = mysqli_query( $db, $sql );

		$fila = mysqli_fetch_assoc( $resultado );		

		return $fila;

	}	


	function obtenerDatosActividadHorarioLogServer( $tipo, $identificador ){
		require('../../includes/conexion.php');

		if ( $tipo == 'Foro' ) {

			$sql = "
				SELECT *
				FROM foro_copia
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
				INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN foro ON foro.id_for = foro_copia.id_for1
				WHERE id_for_cop = '$identificador'
			";

		} else if ( $tipo == 'Entregable' ) {

			$sql = "
				SELECT *
				FROM entregable_copia
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
				INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
				WHERE id_ent_cop = '$identificador'
			";

		} else if ( $tipo == 'Examen' ) {

			$sql = "
				SELECT *
				FROM examen_copia
				INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
				INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
				WHERE id_exa_cop = '$identificador'
			";
			
		}

		$resultado = mysqli_query( $db, $sql );

		$fila = mysqli_fetch_assoc( $resultado );		

		return $fila;
	}


	function obtenerDescripcionActividadHorarioLogServer( $tipo_quien, $quien, $que, $tipo_actividad, $actividad, $materia, $clave, $programa ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
        	// El Administrador: juan Valenzuela Aguilar editó el Foro ( Opinion del Coronavirus ) de Matemáticas ( PRON_0120_MATE1 ) en el programa: Evaluación única - Online. El dia 10/04/2020 02:25 PM.
          $des_log = 'El Administrador: '.$quien." ".$que." el ".$tipo_actividad." ( ".$actividad." ) de ".$materia." ( ".$clave." )  en el programa: ".$programa.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." el ".$tipo_actividad." ( ".$actividad." ) de ".$materia." ( ".$clave." )  en el programa: ".$programa.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}



	function obtenerDescripcionHorarioLogServer( $tipo_quien, $quien, $que, $cual, $clave, $donde ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
          // // EL ADMINISTRADOR: JUAN ZARATE registró grupo de ciencias sociales ( clave_grupal ) del programa Evaluacion unica.
          $des_log = 'El Administrador: '.$quien." ".$que." grupo de ".$cual." ( ".$clave." )  en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." grupo de ".$cual." ( ".$clave." )  en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}


	function obtenerNombreCicloServer( $id_cic ){

		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM ciclo
	        WHERE id_cic = '$id_cic'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      

			$fila = mysqli_fetch_assoc( $resultado );

			return $fila['nom_cic'];

		}
	}



	function obtenerDescripcionPagoReglasNegocioLogServer( $nomResponsable, $accion, $concepto, $monto, $donde, $tipoPago ){

		$fechaHoy = date( 'Y-m-d H:i:s' );

		// el administrador juan zarate registro un cobro por concepto: colegiatura abril y cantidad: $1500. Programa: evaluacion unica. fecha...

		if ( $tipoPago == 'Recurrente' ) {

			$des_log = 'El Administrador: '.$nomResponsable." ".$accion." un cobro por concepto: ".$concepto.", por la cantidad de $ ".$monto."; en el ciclo escolar: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

		} else if ( $tipoPago == 'Global' ) {
			
			$des_log = 'El Administrador: '.$nomResponsable." ".$accion." un cobro por concepto: ".$concepto.", por la cantidad de $ ".$monto."; en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

		}


		
		return $des_log;

	}


	function obtenerDatosExamenServer( $id_exa ){

		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM examen
	        INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
	        INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	        INNER JOIN rama ON rama.id_ram = materia.id_ram2
	        WHERE id_exa = '$id_exa'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      
			$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}

	}

	function obtenerDescripcionExamenLogServer( $tipo_quien, $quien, $que, $tipo_que, $nombreExamen, $nombrePrograma ){
		
		$fechaHoy = date( 'Y-m-d H:i:s' );

		if ( $tipo_quien == 'Admin' ) {
          
          $des_log = 'El Administrador: '.$quien." ".$que." un(a) ".$tipo_que." al examen: ".$nombreExamen.", en el programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." un(a) ".$tipo_que." al examen: ".$nombreExamen.", en el programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
        
        } else if ( $tipo_quien == 'Profesor' ) {
        
        	$des_log = 'El Profesor: '.$quien." ".$que." un(a) ".$tipo_que." al examen: ".$nombreExamen.", en el programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
        
        }

		return $des_log;

	}

		

	function obtenerDescripcionPagoAlumnoLogServer( $tipo_quien, $quien, $que, $concepto, $monto, $a_quien ){

		$fechaHoy = date( 'Y-m-d H:i:s' );

		if ( $tipo_quien == 'Admin' ) {
          // el administrador juan zarate registro un cobro por concepto: colegiatura 2, por la cantidad de $1500, a Pedrito Sola. fecha...
          $des_log = 'El Administrador: '.$quien." ".$que." un cobro por concepto: ".$concepto.", por la cantidad de $ ".$monto." a ".$a_quien.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Cobranza' ) {

            $des_log = 'El Personal de Cobranza: '.$quien." ".$que." un cobro por concepto: ".$concepto.", por la cantidad de $ ".$monto." a ".$a_quien.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}


	function obtenerDescripcionPersonalLogServer( $quien, $que, $a_que_tipo, $a_quien ) {

		$fechaHoy = date( 'Y-m-d H:i:s' );


		$des_log = 'El Administrador: '.$quien." ".$que." un(a) ".$a_que_tipo." ( ".$a_quien." ). El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

		return $des_log;
	}


	function obtenerDatosGrupalesServer( $id_sub_hor ){

		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM sub_hor
	        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	        INNER JOIN profesor ON profesor.id_pro  = sub_hor.id_pro1
	        INNER JOIN rama ON rama.id_ram = materia.id_ram2
	        WHERE id_sub_hor = '$id_sub_hor'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      	
	      	// echo $sql;
	      	$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}
	}


	function obtenerDescripcionHorarioProfesorLogServer( $tipo_quien, $quien, $accion, $nombreProfesor, $nombreProfesor2,  $nombreMateria, $nombreClave, $nombrePrograma ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
          // el administrador juan zarate edito profesor (seto) por (pedrito) en  español ( 1nv11 ) de lic en ciencias biomedicas
          $des_log = 'El Administrador: '.$quien." ".$accion." profesor ".$nombreProfesor." por ".$nombreProfesor2." en ".$nombreMateria." ( ".$nombreClave." )  en el programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$accion." profesor ".$nombreProfesor." por ".$nombreProfesor2." en ".$nombreMateria." ( ".$nombreClave." )  en el programa: ".$nombrePrograma.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;

	}


	function obtenerDescripcionInscripcionAlumnoLogServer( $tipo_quien, $quien, $que, $a_quien, $cual, $clave, $donde ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
          // // EL ADMINISTRADOR: JUAN ZARATE registró a pedrito sola en ciencias sociales ( clave_grupal ) del programa Evaluacion unica.
          $des_log = 'El Administrador: '.$quien." ".$que." a ".$a_quien." en ".$cual." ( ".$clave." )  en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." a ".$a_quien." en ".$cual." ( ".$clave." )  en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;

	}


	function obtenerDatosBloqueServer( $id_blo ){

		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM bloque
	        INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	        INNER JOIN rama ON rama.id_ram = materia.id_ram2
	        WHERE id_blo = '$id_blo'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      
	      	$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}
	}


	function obtenerDatosArchivoServer( $archivoMax ){

		require('../../includes/conexion.php');

		$sql = "
	        SELECT *
	        FROM archivo
	        INNER JOIN bloque ON bloque.id_blo = archivo.id_blo3
	        INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	        INNER JOIN rama ON rama.id_ram = materia.id_ram2
	        WHERE id_arc = '$archivoMax'
	    ";

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      
	    	// echo $sql;
			$fila = mysqli_fetch_assoc( $resultado );

			return $fila;

		}
	}


	function obtenerDescripcionNodoProgramaLogServer( $tipo_quien, $quien, $que, $tipo_que, $a_que, $donde ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {
          
          $des_log = 'El Administrador: '.$quien." ".$que." un(a) ".$tipo_que." ( ".$a_que." ) en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." un(a) ".$tipo_que." ( ".$a_que." ) en el programa: ".$donde.". El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;

	}


	function obtenerDescripcionProgramaLogServer( $tipo_quien, $quien, $que, $cual, $programa ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipo_quien == 'Admin' ) {

          	$des_log = 'El Administrador: '.$quien." ".$que." un(a) ".$cual." ( ".$programa." ). El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipo_quien == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." un(a) ".$cual." ( ".$programa." ). El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}


	


	function obtenerDatosAlumnoCalificacionServer( $id_cal ){
		require('../../includes/conexion.php');
		$sql = "
			SELECT *
			FROM calificacion
			INNER JOIN materia ON materia.id_mat = calificacion.id_mat4
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = calificacion.id_alu_ram2
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
			WHERE id_cal = '$id_cal'
		";

		$resultado = mysqli_query( $db, $sql );

		$fila = mysqli_fetch_assoc( $resultado );

		return $fila;
	}



	function obtenerDatosMateriaProgramaLogServer( $id_mat ){
		require('../../includes/conexion.php');

		$sql = "
			SELECT *
			FROM materia 
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_mat = '$id_mat'
		";

		$resultado = mysqli_query( $db, $sql );

		$fila = mysqli_fetch_assoc( $resultado );

		return $fila;
	}


	function obtenerDatosGrupoProgramaLogServer( $id_gru ){
		require('../../includes/conexion.php');

		$sqlPrograma = "
			SELECT *
			FROM grupo 
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			WHERE id_gru = '$id_gru'
		";

		$resultadoPrograma = mysqli_query( $db, $sqlPrograma );

		$filaPrograma = mysqli_fetch_assoc( $resultadoPrograma );

		return $filaPrograma;
	}


	function obtenerDatosGeneracionProgramaLogServer( $id_gen ){
		require('../../includes/conexion.php');

		$sqlPrograma = "
			SELECT *
			FROM generacion 
			INNER JOIN rama ON rama.id_ram = generacion.id_ram5
			WHERE id_gen = '$id_gen'
		";

		$resultadoPrograma = mysqli_query( $db, $sqlPrograma );

		$filaPrograma = mysqli_fetch_assoc( $resultadoPrograma );

		return $filaPrograma;
	}



	function obtenerDescripcionAlumnoCalificacionLogServer( $tipoUsuario, $quien, $que, $materia, $calificacion, $alumno, $programa ){

		$fechaHoy = date( 'Y-m-d H:i:s' );


		if ( $tipoUsuario == 'Admin' ) {

          	$des_log = 'El Administrador: '.$quien." ".$que." la calificación ( ".$materia." con ".$calificacion." ), de ".$alumno." en ( ".$programa." ). El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
            

        } else if ( $tipoUsuario == 'Adminge' ) {

            $des_log = 'El Gestor Escolar: '.$quien." ".$que." la calificación ( ".$materia." con ".$calificacion." ), de ".$alumno." en ( ".$programa." ). El dia ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";

        }

		return $des_log;
	}





	function logServer( $tip_log, $usr_log, $id_usr_log, $ent_log, $des_log, $id_pla10 ) {

		require('../../includes/conexion.php');

        $fechaHoy = date( 'Y-m-d H:i:s' );

		$sqlLog = "
			INSERT INTO log ( tip_log, usr_log, ent_log, id_usr_log, fec_log, des_log, id_pla10 ) 
			VALUES ( '$tip_log', '$usr_log', '$ent_log', '$id_usr_log', '$fechaHoy', '$des_log', '$id_pla10' )
		";

		$resultadoLog = mysqli_query( $db, $sqlLog );

		if ( !$resultadoLog ) {
		
			//echo "Exito";
			echo $sqlLog;

		}


	}
	// FIN logServer



	
	function obtenerNombreUsuarioLogServer( $usr_log, $id_usr_log ){
		require('../../includes/conexion.php');

		if ( $usr_log == 'Admin' ) {
            
            $sql = "
                SELECT *
                FROM admin
                WHERE id_adm = '$id_usr_log'
            ";

            //echo $sql;

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {

              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_adm']." ".$fila['app_adm']." ".$fila['apm_adm'];

              return $usuario;
              
              

            } else {
              
              echo $sql;
            
            }
            
            

        } else if ( $usr_log == 'Adminge' ) {

          $sql = "
                SELECT *
                FROM adminge
                WHERE id_adg = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_adg']." ".$fila['app_adg']." ".$fila['apm_adg'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }
            

        } else if ( $usr_log == 'Adminco' ) {

          $sql = "
                SELECT *
                FROM adminco
                WHERE id_adc = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_adc']." ".$fila['app_adc']." ".$fila['apm_adc'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }
            

        } else if ( $usr_log == 'Cobranza' ) {
            
            $sql = "
                SELECT *
                FROM cobranza
                WHERE id_cob = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_cob']." ".$fila['app_cob']." ".$fila['apm_cob'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }
            

        } else if ( $usr_log == 'Profesor' ) {
            
            $sql = "
                SELECT *
                FROM profesor
                WHERE id_pro = '$id_usr_log'
            ";
            // echo $sql;

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_pro']." ".$fila['app_pro']." ".$fila['apm_pro'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }

        } else if ( $usr_log == 'Ejecutivo' ) {
        
        $sql = "
                SELECT *
                FROM ejecutivo
                WHERE id_eje = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_eje']." ".$fila['app_eje']." ".$fila['apm_eje'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }

        } else if ( $usr_log == 'Alumno' ) {
            
          $sql = "
                SELECT *
                FROM alumno
                WHERE id_alu = '$id_usr_log'
            ";

            $resultado = mysqli_query( $db, $sql );

            if ( $resultado ) {
              
              $fila = mysqli_fetch_assoc( $resultado );
              
              $usuario = $fila['nom_alu']." ".$fila['app_alu']." ".$fila['apm_alu'];

              return $usuario;

            } else {
              
              echo $sql;
            
            }

        }

	}
	// FIN FUNCION PARA OBTENER EL NOMBRE DEL USUARIO EN LOG



	function obtenerTipoUsuarioLogServer ( $usr_log ) {

		
		if ( $usr_log == 'Admin' ) {
            
            return 'Administrador';
            
        } else if ( $usr_log == 'Adminge' ) {

         	return 'Gestor Escolar';

        } else if ( $usr_log == 'Adminco' ) {

        	return 'Administrador_Comercial';
            

        } else if ( $usr_log == 'Cobranza' ) {
            
            return 'Cobranza';
           

        } else if ( $usr_log == 'Profesor' ) {

            return 'Profesor';
            
        } else if ( $usr_log == 'Ejecutivo' ) {
        	
        	return 'Ejecutivo';
        
        } else if ( $usr_log == 'Alumno' ) {
            
         	return 'Alumno';

        }

	}

	//FUNCION OBTENCION CONVENIOS-CONDONACIONES DEL PLANTEL


	function obtenerTotalCondonacionesAlumnoServer( $id_alu_ram ) {

		require('../../includes/conexion.php');

		$sql_obtener_conteo_condonaciones = "SELECT COUNT(id_con_pag) AS condonacion_pago FROM condonacion_pago
														INNER JOIN pago ON pago.id_pag = condonacion_pago.id_pag2
														WHERE id_alu_ram10= '$id_alu_ram'";
					$resultado_condonacion = mysqli_query($db, $sql_obtener_conteo_condonaciones);
					$fila_condonacion = mysqli_fetch_assoc($resultado_condonacion);
					return $fila_condonacion['condonacion_pago'];
	}


	function obtenerTotalConveniosAlumnoServer( $id_alu_ram ) {

		require('../../includes/conexion.php');

		$sql_obtener_convenio = "SELECT COUNT(id_acu_pag) AS convenio_pago FROM convenio_pago
											INNER JOIN pago ON pago.id_pag = convenio_pago.id_pag3
											WHERE id_alu_ram10= '$id_alu_ram'";

											//echo $sql_obtener_convenio;

					$resultado_convenio = mysqli_query($db, $sql_obtener_convenio);
					$fila_convenio = mysqli_fetch_assoc($resultado_convenio);
					return $fila_convenio['convenio_pago'];
	}





	function duplicarContenido( $emisor, $receptor ) {		
    	require( '../includes/conexion.php' );
    	
    	$id_ram1 = $emisor;
    	$id_ram2 = $receptor;

    	$sqlMaterias1 = "
			SELECT *
			FROM materia
			WHERE id_ram2 = ' $id_ram1 '
    	";

    	$resultadoMaterias1 = mysqli_query( $db, $sqlMaterias1 );

    	while( $filaMaterias1 = mysqli_fetch_assoc( $resultadoMaterias1 ) ) {

    		// EXTRACCION DE DATOS DE MATERIA DE P1
    		$nom_mat1 = $filaMaterias1['nom_mat'];
    		$id_mat1 = $filaMaterias1['id_mat'];


    		// CONSULTA DE DATOS DE MATERIAS DE P2
    		$sqlMaterias2 = "
				SELECT *
				FROM materia
				WHERE id_ram2 = '$id_ram2' AND nom_mat = '$nom_mat1'
    		";

    		$resultadoMaterias2 = mysqli_query( $db, $sqlMaterias2 );

    		$filaMaterias2 = mysqli_fetch_assoc( $resultadoMaterias2 );

    		$id_mat2 = $filaMaterias2['id_mat'];

    		// BLOQUES

    		$sqlBloques = "
				SELECT *
				FROM bloque
				WHERE id_mat6 = '$id_mat1'
    		";

    		$resultadoBloques = mysqli_query( $db, $sqlBloques );

    		while( $filaBloques = mysqli_fetch_assoc( $resultadoBloques ) ) {
    			// DATOS DEL BLOQUE
    			$id_blo = $filaBloques['id_blo'];
    			$nom_blo = $filaBloques['nom_blo'];
    			$des_blo = $filaBloques['des_blo'];
    			$con_blo = $filaBloques['con_blo'];
    			$img_blo = $filaBloques['img_blo'];
    			
    			// INSERCION DEL BLOQUE A MATERIAS DE P2

    			$sqlInsercionBloque = "
					INSERT INTO bloque ( nom_blo, des_blo, con_blo, img_blo, id_mat6 )
					VALUES ( '$nom_blo', '$des_blo', '$con_blo', '$img_blo', '$id_mat2' )
    			";

    			$resultadoInsercionBloque = mysqli_query( $db, $sqlInsercionBloque );

    			if ( $resultadoInsercionBloque ) {
    				
    				// OBTENCION id_blo MAXIMO

    				$sqlMaximoBloque = "
						SELECT MAX( id_blo ) AS maximo FROM bloque
    				";

    				$resultadoMaximoBloque = mysqli_query( $db, $sqlMaximoBloque );

    				$filaMaximoBloque = mysqli_fetch_assoc( $resultadoMaximoBloque );

    				$id_blo_max = $filaMaximoBloque['maximo'];



    				// DUPLICIDAD DE CONTENIDOS DE BLOQUE

    				// VIDEOS
    				// CONSULTA
    				$sqlVideos = "
						SELECT *
						FROM video
						WHERE id_blo1 = '$id_blo'
    				";

    				$resultadoVideos = mysqli_query( $db, $sqlVideos );

    				while ( $filaVideos = mysqli_fetch_assoc( $resultadoVideos ) ) {
    					$nom_vid = $filaVideos['nom_vid'];
	    				$des_vid = $filaVideos['des_vid'];
	    				$vid_vid = $filaVideos['vid_vid'];
	    				$url_vid = $filaVideos['url_vid'];
	    				$tip_vid = $filaVideos['tip_vid'];

	    				// INSERCION

	    				$sqlInsercionVideo = "
							INSERT INTO video ( nom_vid, des_vid, vid_vid, url_vid, tip_vid, id_blo1 ) 
							VALUES ( '$nom_vid', '$des_vid', '$vid_vid', '$url_vid', '$tip_vid', '$id_blo_max' )
	    				";

	    				$resultadoInsercionVideo = mysqli_query( $db, $sqlInsercionVideo );

	    				if ( !$resultadoInsercionVideo ) {
	    					echo $sqlInsercionVideo;

	    					// break; break;
	    				}

    				}
    				

    				// WIKIS
    				// CONSULTA
    				$sqlWikis = "
						SELECT *
						FROM wiki
						WHERE id_blo2 = '$id_blo'
    				";

    				$resultadoWikis = mysqli_query( $db, $sqlWikis );

    				while ( $filaWikis = mysqli_fetch_assoc( $resultadoWikis ) )  {

    					$nom_wik = $filaWikis['nom_wik'];
	    				$des_wik = $filaWikis['des_wik'];
	    				$tip_wik = $filaWikis['tip_wik'];

	    				// INSERCION

	    				$sqlInsercionWiki = "
							INSERT INTO wiki ( nom_wik, des_wik, tip_wik, id_blo2 ) 
							VALUES ( '$nom_wik', '$des_wik', '$tip_wik', '$id_blo_max' )
	    				";

	    				$resultadoInsercionWiki = mysqli_query( $db, $sqlInsercionWiki );
    				}

    				


    				// ARCHIVOS
    				// CONSULTA
    				$sqlArchivos = "
						SELECT *
						FROM archivo
						WHERE id_blo3 = '$id_blo'
    				";

    				$resultadoArchivos = mysqli_query( $db, $sqlArchivos );

    				

    				while ( $filaArchivos = mysqli_fetch_assoc( $resultadoArchivos ) ) {
    					$nom_arc = $filaArchivos['nom_arc'];
	    				$des_arc = $filaArchivos['des_arc'];
	    				$arc_arc = $filaArchivos['arc_arc'];
	    				$tip_arc = $filaArchivos['tip_arc'];

	    				// INSERCION

	    				$sqlInsercionArchivo = "
							INSERT INTO archivo ( nom_arc, des_arc, arc_arc, tip_arc, id_blo3 ) 
							VALUES ( '$nom_arc', '$des_arc', '$arc_arc', '$tip_arc', '$id_blo_max' )
	    				";

	    				$resultadoInsercionArchivo = mysqli_query( $db, $sqlInsercionArchivo );
    				}


    				// FOROS
    				// CONSULTA
    				$sqlForos = "
						SELECT *
						FROM foro
						WHERE id_blo4 = '$id_blo'
    				";

    				$resultadoForos = mysqli_query( $db, $sqlForos );

    				while ( $filaForos = mysqli_fetch_assoc( $resultadoForos ) ) {

    					$nom_for = $filaForos['nom_for'];
	    				$des_for = $filaForos['des_for'];
	    				$tip_for = $filaForos['tip_for'];
	    				$pun_for = $filaForos['pun_for'];
	    				$ini_for = $filaForos['ini_for'];
	    				$fin_for = $filaForos['fin_for'];
	    				// INSERCION

	    				$sqlInsercionForo = "
							INSERT INTO foro ( nom_for, des_for, tip_for, pun_for, ini_for, fin_for, id_blo4 ) 
							VALUES ( '$nom_for', '$des_for', '$tip_for', '$pun_for', '$ini_for', '$fin_for', '$id_blo_max' )
	    				";

	    				$resultadoInsercionForo = mysqli_query( $db, $sqlInsercionForo );
    				}




    				// ENTREGABLES
    				// CONSULTA
    				$sqlEntregables = "
						SELECT *
						FROM entregable
						WHERE id_blo5 = '$id_blo'
    				";

    				$resultadoEntregables = mysqli_query( $db, $sqlEntregables );

    				while ( $filaEntregables = mysqli_fetch_assoc( $resultadoEntregables ) ) {

    					$nom_ent = $filaEntregables['nom_ent'];
	    				$des_ent = $filaEntregables['des_ent'];
	    				$tip_ent = $filaEntregables['tip_ent'];
	    				$pun_ent = $filaEntregables['pun_ent'];
	    				$ini_ent = $filaEntregables['ini_ent'];
	    				$fin_ent = $filaEntregables['fin_ent'];
	    				// INSERCION

	    				$sqlInsercionEntregable = "
							INSERT INTO entregable ( nom_ent, des_ent, tip_ent, pun_ent, ini_ent, fin_ent, id_blo5 ) 
							VALUES ( '$nom_ent', '$des_ent', '$tip_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$id_blo_max' )
	    				";

	    				$resultadoInsercionEntregable = mysqli_query( $db, $sqlInsercionEntregable );
    				}

    				


    				// EXAMEN
					// CONSULTA
					$sqlExamenes = "
						SELECT *
						FROM examen
						WHERE id_blo6 = '$id_blo'
					";

					$resultadoExamenes = mysqli_query( $db, $sqlExamenes );

					while ( $filaExamenes = mysqli_fetch_assoc( $resultadoExamenes ) )  {

						$id_exa = $filaExamenes['id_exa'];
						$nom_exa = $filaExamenes['nom_exa'];
						$des_exa = $filaExamenes['des_exa'];
						$tip_exa = $filaExamenes['tip_exa'];
						$pun_exa = $filaExamenes['pun_exa'];
						$ini_exa = $filaExamenes['ini_exa'];
						$fin_exa = $filaExamenes['fin_exa'];
						$dur_exa = $filaExamenes['dur_exa'];

						// INSERCION

						$sqlInsercionExamen = "
							INSERT INTO examen ( nom_exa, des_exa, tip_exa, pun_exa, ini_exa, fin_exa,  dur_exa, id_blo6 ) 
							VALUES ( '$nom_exa', '$des_exa', '$tip_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$id_blo_max' )
						";

						$resultadoInsercionExamen = mysqli_query( $db, $sqlInsercionExamen );

						if ( $resultadoInsercionExamen ) {
							// OBTENCION DE id_exa MAXIMO
							$sqlMaximoExamen = "
								SELECT MAX( id_exa ) AS maximo FROM examen
							";

							$resultadoMaximoExamen = mysqli_query( $db, $sqlMaximoExamen );

							$filaMaximoExamen = mysqli_fetch_assoc( $resultadoMaximoExamen );

							$id_exa_max = $filaMaximoExamen['maximo'];

							// CONSULTA DE PREGUNTAS EN TABLA pregunta
							$sqlPreguntas = "
								SELECT *
								FROM pregunta
								WHERE id_exa2 = '$id_exa'
							";

							$resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

							while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
								// DATOS DE pregunta
								$id_pre = $filaPreguntas['id_pre'];
								$pre_pre = $filaPreguntas['pre_pre'];
								$pun_pre = $filaPreguntas['pun_pre'];


								$sqlInsercionPregunta = "
									INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
									VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
								";

								$resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

								if ( $resultadoInsercionPregunta ) {
									// OBTENCION DE RESPUESTAS EN TABLA respuesta
									$sqlMaximoRespuesta = "
										SELECT MAX( id_pre ) AS maximo FROM pregunta
									";

									$resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

									$filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

									$id_pre_max = $filaMaximoRespuesta['maximo'];

									// DATOS DE respuesta
									$sqlRespuesta = "
										SELECT *
										FROM respuesta
										WHERE id_pre1 = '$id_pre'
									";

									$resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

									while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
										// CONSULTA
										$res_res = $filaRespuesta['res_res'];
										$val_res = $filaRespuesta['val_res'];

										// INSERCION

										$sqlInsercionRespuesta = "
											INSERT INTO respuesta ( res_res, val_res, id_pre1 )
											VALUES ( '$res_res', '$val_res', '$id_pre_max' )
										";

										$resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

									}

								}

							}

						}

					}
					
    			}

    		}



    	// FIN WHILE MATERIAS P1
    	}

    }



    function copiar_contenido_plantel_a_plantel( $plantel_emisor, $plantel_receptor ){
      require( '../includes/conexion.php' );

      $sqlProgramas = "
        SELECT * 
        FROM rama 
        WHERE id_pla1 = '$plantel_emisor';
      ";

      $resultadoProgramas = mysqli_query( $db, $sqlProgramas );

      $id_pla1 = $plantel_receptor;

      while( $filaProgramas = mysqli_query( $resultadoProgramas ) ){

        $id_ram = $filaProgramas['id_ram'];
        $nom_ram = $filaProgramas['nom_ram'];
        $cic_ram = $filaProgramas['cic_ram'];
        $per_ram = $filaProgramas['per_ram'];
        $cos_ram = $filaProgramas['cos_ram'];
        $eva_ram = $filaProgramas['eva_ram'];
        $mod_ram = $filaProgramas['mod_ram'];
        $car_reg_ram = $filaProgramas['car_reg_ram'];
        $gra_ram = $filaProgramas['gra_ram'];
        $car_min_ram = $filaProgramas['car_min_ram'];
        $bec_max_ram = $filaProgramas['bec_max_ram'];
        $com_ram = $filaProgramas['com_ram'];
        $pag_ram = $filaProgramas['pag_ram'];

        $sqlInsertarPrograma = "
          INSERT INTO rama ( nom_ram, cic_ram, per_ram, cos_ram, eva_ram, mod_ram, car_reg_ram, gra_ram, id_pla1, car_min_ram, bec_max_ram, com_ram, pag_ram ) VALUES ( '$nom_ram', '$cic_ram', '$per_ram', '$cos_ram', '$eva_ram', '$mod_ram', '$car_reg_ram', '$gra_ram', '$id_pla1', '$car_min_ram', '$bec_max_ram', '$com_ram', '$pag_ram' )
        ";

        $resultadoInsertarPrograma = mysqli_query( $resultadoInsertarPrograma );

        if ( !$resultadoInsertarPrograma ) {
          echo $sqlInsertarPrograma;
        } else {

          $emisor = $id_ram;
          $receptor = obtenerUltimoIdentificador('rama', 'id_ram');
          duplicarContenidoPrograma( $emisor, $receptor );

          echo "PROGRAMA: ".$nom_ram.". Copiado correctamente. <br>";

        }


      }

    }

    function duplicarContenidoPrograma( $emisor, $receptor ) {		
    	require( '../includes/conexion.php' );
    	
    	$id_ram1 = $emisor;
    	$id_ram2 = $receptor;

    	$sqlMaterias1 = "
			SELECT *
			FROM materia
			WHERE id_ram2 = '$id_ram1'
    	";

    	$resultadoMaterias1 = mysqli_query( $db, $sqlMaterias1 );

    	while( $filaMaterias1 = mysqli_fetch_assoc( $resultadoMaterias1 ) ) {

    		// EXTRACCION DE DATOS DE MATERIA DE P1
    		$nom_mat1 = $filaMaterias1['nom_mat'];
    		$cic_mat = $filaMaterias1['cic_mat'];
    		$id_mat1 = $filaMaterias1['id_mat'];



    		// INSERCION DE MATERIAS EN PROGRAMA RECEPTOR
    		$sqlMaterias2 = "
				INSERT INTO materia ( nom_mat, cic_mat, id_ram2 )
				VALUES ( '$nom_mat1', '$cic_mat', '$id_ram2' )
    		";

    		$resultadoMaterias2 = mysqli_query( $db, $sqlMaterias2 );

    		if ( !$resultadoMaterias2 ) {
    			echo $sqlMaterias2;
    		}

    		$sqlMaximoMateria = "
				SELECT MAX( id_mat ) AS maximo
				FROM materia
				WHERE id_ram2 = '$id_ram2'
    		";

    		$resultadoMaximoMateria = mysqli_query( $db, $sqlMaximoMateria );

    		$filaMaximoMateria = mysqli_fetch_assoc( $resultadoMaximoMateria );

    		$id_mat2 = $filaMaximoMateria['maximo'];

    		// BLOQUES

    		$sqlBloques = "
				SELECT *
				FROM bloque
				WHERE id_mat6 = '$id_mat1'
    		";

    		$resultadoBloques = mysqli_query( $db, $sqlBloques );

    		while( $filaBloques = mysqli_fetch_assoc( $resultadoBloques ) ) {
    			// DATOS DEL BLOQUE
    			$id_blo = $filaBloques['id_blo'];
    			$nom_blo = $filaBloques['nom_blo'];
    			$des_blo = $filaBloques['des_blo'];
    			$con_blo = $filaBloques['con_blo'];
    			$img_blo = $filaBloques['img_blo'];
    			
    			// INSERCION DEL BLOQUE A MATERIAS DE P2

    			$sqlInsercionBloque = "
					INSERT INTO bloque ( nom_blo, des_blo, con_blo, img_blo, id_mat6 )
					VALUES ( '$nom_blo', '$des_blo', '$con_blo', '$img_blo', '$id_mat2' )
    			";

    			$resultadoInsercionBloque = mysqli_query( $db, $sqlInsercionBloque );

    			if ( $resultadoInsercionBloque ) {
    				
    				// OBTENCION id_blo MAXIMO

    				$sqlMaximoBloque = "
						SELECT MAX( id_blo ) AS maximo FROM bloque
    				";

    				$resultadoMaximoBloque = mysqli_query( $db, $sqlMaximoBloque );

    				$filaMaximoBloque = mysqli_fetch_assoc( $resultadoMaximoBloque );

    				$id_blo_max = $filaMaximoBloque['maximo'];



    				// DUPLICIDAD DE CONTENIDOS DE BLOQUE

    				// VIDEOS
    				// CONSULTA
    				$sqlVideos = "
						SELECT *
						FROM video
						WHERE id_blo1 = '$id_blo'
    				";

    				$resultadoVideos = mysqli_query( $db, $sqlVideos );

    				while ( $filaVideos = mysqli_fetch_assoc( $resultadoVideos ) ) {
    					$nom_vid = $filaVideos['nom_vid'];
	    				$des_vid = $filaVideos['des_vid'];
	    				$vid_vid = $filaVideos['vid_vid'];
	    				$url_vid = $filaVideos['url_vid'];
	    				$tip_vid = $filaVideos['tip_vid'];

	    				// INSERCION

	    				$sqlInsercionVideo = "
							INSERT INTO video ( nom_vid, des_vid, vid_vid, url_vid, tip_vid, id_blo1 ) 
							VALUES ( '$nom_vid', '$des_vid', '$vid_vid', '$url_vid', '$tip_vid', '$id_blo_max' )
	    				";

	    				$resultadoInsercionVideo = mysqli_query( $db, $sqlInsercionVideo );

	    				if ( !$resultadoInsercionVideo ) {
	    					echo $sqlInsercionVideo;

	    					// break; break;
	    				}

    				}
    				

    				// WIKIS
    				// CONSULTA
    				$sqlWikis = "
						SELECT *
						FROM wiki
						WHERE id_blo2 = '$id_blo'
    				";

    				$resultadoWikis = mysqli_query( $db, $sqlWikis );

    				while ( $filaWikis = mysqli_fetch_assoc( $resultadoWikis ) )  {

    					$nom_wik = $filaWikis['nom_wik'];
	    				$des_wik = $filaWikis['des_wik'];
	    				$tip_wik = $filaWikis['tip_wik'];

	    				// INSERCION

	    				$sqlInsercionWiki = "
							INSERT INTO wiki ( nom_wik, des_wik, tip_wik, id_blo2 ) 
							VALUES ( '$nom_wik', '$des_wik', '$tip_wik', '$id_blo_max' )
	    				";

	    				$resultadoInsercionWiki = mysqli_query( $db, $sqlInsercionWiki );
    				}

    				


    				// ARCHIVOS
    				// CONSULTA
    				$sqlArchivos = "
						SELECT *
						FROM archivo
						WHERE id_blo3 = '$id_blo'
    				";

    				$resultadoArchivos = mysqli_query( $db, $sqlArchivos );

    				

    				while ( $filaArchivos = mysqli_fetch_assoc( $resultadoArchivos ) ) {
    					$nom_arc = $filaArchivos['nom_arc'];
	    				$des_arc = $filaArchivos['des_arc'];
	    				$arc_arc = $filaArchivos['arc_arc'];
	    				$tip_arc = $filaArchivos['tip_arc'];

	    				// INSERCION

	    				$sqlInsercionArchivo = "
							INSERT INTO archivo ( nom_arc, des_arc, arc_arc, tip_arc, id_blo3 ) 
							VALUES ( '$nom_arc', '$des_arc', '$arc_arc', '$tip_arc', '$id_blo_max' )
	    				";

	    				$resultadoInsercionArchivo = mysqli_query( $db, $sqlInsercionArchivo );
    				}


    				// FOROS
    				// CONSULTA
    				$sqlForos = "
						SELECT *
						FROM foro
						WHERE id_blo4 = '$id_blo'
    				";

    				$resultadoForos = mysqli_query( $db, $sqlForos );

    				while ( $filaForos = mysqli_fetch_assoc( $resultadoForos ) ) {

    					$nom_for = $filaForos['nom_for'];
	    				$des_for = $filaForos['des_for'];
	    				$tip_for = $filaForos['tip_for'];
	    				$pun_for = $filaForos['pun_for'];
	    				$ini_for = $filaForos['ini_for'];
	    				$fin_for = $filaForos['fin_for'];
	    				// INSERCION

	    				$sqlInsercionForo = "
							INSERT INTO foro ( nom_for, des_for, tip_for, pun_for, ini_for, fin_for, id_blo4 ) 
							VALUES ( '$nom_for', '$des_for', '$tip_for', '$pun_for', '$ini_for', '$fin_for', '$id_blo_max' )
	    				";

	    				$resultadoInsercionForo = mysqli_query( $db, $sqlInsercionForo );
    				}




    				// ENTREGABLES
    				// CONSULTA
    				$sqlEntregables = "
						SELECT *
						FROM entregable
						WHERE id_blo5 = '$id_blo'
    				";

    				$resultadoEntregables = mysqli_query( $db, $sqlEntregables );

    				while ( $filaEntregables = mysqli_fetch_assoc( $resultadoEntregables ) ) {

    					$nom_ent = $filaEntregables['nom_ent'];
	    				$des_ent = $filaEntregables['des_ent'];
	    				$tip_ent = $filaEntregables['tip_ent'];
	    				$pun_ent = $filaEntregables['pun_ent'];
	    				$ini_ent = $filaEntregables['ini_ent'];
	    				$fin_ent = $filaEntregables['fin_ent'];
	    				// INSERCION

	    				$sqlInsercionEntregable = "
							INSERT INTO entregable ( nom_ent, des_ent, tip_ent, pun_ent, ini_ent, fin_ent, id_blo5 ) 
							VALUES ( '$nom_ent', '$des_ent', '$tip_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$id_blo_max' )
	    				";

	    				$resultadoInsercionEntregable = mysqli_query( $db, $sqlInsercionEntregable );
    				}

    				


    				// EXAMEN
					// CONSULTA
					$sqlExamenes = "
						SELECT *
						FROM examen
						WHERE id_blo6 = '$id_blo'
					";

					$resultadoExamenes = mysqli_query( $db, $sqlExamenes );

					while ( $filaExamenes = mysqli_fetch_assoc( $resultadoExamenes ) )  {

						$id_exa = $filaExamenes['id_exa'];
						$nom_exa = $filaExamenes['nom_exa'];
						$des_exa = $filaExamenes['des_exa'];
						$tip_exa = $filaExamenes['tip_exa'];
						$pun_exa = $filaExamenes['pun_exa'];
						$ini_exa = $filaExamenes['ini_exa'];
						$fin_exa = $filaExamenes['fin_exa'];
						$dur_exa = $filaExamenes['dur_exa'];

						// INSERCION

						$sqlInsercionExamen = "
							INSERT INTO examen ( nom_exa, des_exa, tip_exa, pun_exa, ini_exa, fin_exa,  dur_exa, id_blo6 ) 
							VALUES ( '$nom_exa', '$des_exa', '$tip_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$id_blo_max' )
						";

						$resultadoInsercionExamen = mysqli_query( $db, $sqlInsercionExamen );

						if ( $resultadoInsercionExamen ) {
							// OBTENCION DE id_exa MAXIMO
							$sqlMaximoExamen = "
								SELECT MAX( id_exa ) AS maximo FROM examen
							";

							$resultadoMaximoExamen = mysqli_query( $db, $sqlMaximoExamen );

							$filaMaximoExamen = mysqli_fetch_assoc( $resultadoMaximoExamen );

							$id_exa_max = $filaMaximoExamen['maximo'];

							// CONSULTA DE PREGUNTAS EN TABLA pregunta
							$sqlPreguntas = "
								SELECT *
								FROM pregunta
								WHERE id_exa2 = '$id_exa'
							";

							$resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

							while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
								// DATOS DE pregunta
								$id_pre = $filaPreguntas['id_pre'];
								$pre_pre = $filaPreguntas['pre_pre'];
								$pun_pre = $filaPreguntas['pun_pre'];


								$sqlInsercionPregunta = "
									INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
									VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
								";

								$resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

								if ( $resultadoInsercionPregunta ) {
									// OBTENCION DE RESPUESTAS EN TABLA respuesta
									$sqlMaximoRespuesta = "
										SELECT MAX( id_pre ) AS maximo FROM pregunta
									";

									$resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

									$filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

									$id_pre_max = $filaMaximoRespuesta['maximo'];

									// DATOS DE respuesta
									$sqlRespuesta = "
										SELECT *
										FROM respuesta
										WHERE id_pre1 = '$id_pre'
									";

									$resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

									while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
										// CONSULTA
										$res_res = $filaRespuesta['res_res'];
										$val_res = $filaRespuesta['val_res'];

										// INSERCION

										$sqlInsercionRespuesta = "
											INSERT INTO respuesta ( res_res, val_res, id_pre1 )
											VALUES ( '$res_res', '$val_res', '$id_pre_max' )
										";

										$resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

									}

								}

							}

						}

					}
					
    			}

    		}



    	// FIN WHILE MATERIAS P1
    	}

    }


    // FUNCION COPIAR CONTENIDO DE MATERIA A PROGRAMA
    // **Si ya existe la materia destino, se tiene que borrar porque el copiado de todo el contenido incluye la materia en si
    function duplicarContenidoProgramaMateria( $emisor, $receptor, $materia ) {		
    	require( '../includes/conexion.php' );
    	
    	$id_ram1 = $emisor;
    	$id_ram2 = $receptor;

    	$sqlMaterias1 = "
			SELECT *
			FROM materia
			WHERE id_ram2 = '$id_ram1' AND id_mat = '$materia'
    	";

    	$resultadoMaterias1 = mysqli_query( $db, $sqlMaterias1 );

    	while( $filaMaterias1 = mysqli_fetch_assoc( $resultadoMaterias1 ) ) {

    		// EXTRACCION DE DATOS DE MATERIA DE P1
    		$nom_mat1 = $filaMaterias1['nom_mat'];
    		$cic_mat = $filaMaterias1['cic_mat'];
    		$id_mat1 = $filaMaterias1['id_mat'];



    		// INSERCION DE MATERIAS EN PROGRAMA RECEPTOR
    		$sqlMaterias2 = "
				INSERT INTO materia ( nom_mat, cic_mat, id_ram2 )
				VALUES ( '$nom_mat1', '$cic_mat', '$id_ram2' )
    		";

    		$resultadoMaterias2 = mysqli_query( $db, $sqlMaterias2 );

    		if ( !$resultadoMaterias2 ) {
    			echo $sqlMaterias2;
    		}

    		$sqlMaximoMateria = "
				SELECT MAX( id_mat ) AS maximo
				FROM materia
				WHERE id_ram2 = '$id_ram2'
    		";

    		$resultadoMaximoMateria = mysqli_query( $db, $sqlMaximoMateria );

    		$filaMaximoMateria = mysqli_fetch_assoc( $resultadoMaximoMateria );

    		$id_mat2 = $filaMaximoMateria['maximo'];

    		// BLOQUES

    		$sqlBloques = "
				SELECT *
				FROM bloque
				WHERE id_mat6 = '$id_mat1'
    		";

    		$resultadoBloques = mysqli_query( $db, $sqlBloques );

    		while( $filaBloques = mysqli_fetch_assoc( $resultadoBloques ) ) {
    			// DATOS DEL BLOQUE
    			$id_blo = $filaBloques['id_blo'];
    			$nom_blo = $filaBloques['nom_blo'];
    			$des_blo = $filaBloques['des_blo'];
    			$con_blo = $filaBloques['con_blo'];
    			$img_blo = $filaBloques['img_blo'];
    			
    			// INSERCION DEL BLOQUE A MATERIAS DE P2

    			$sqlInsercionBloque = "
					INSERT INTO bloque ( nom_blo, des_blo, con_blo, img_blo, id_mat6 )
					VALUES ( '$nom_blo', '$des_blo', '$con_blo', '$img_blo', '$id_mat2' )
    			";

    			$resultadoInsercionBloque = mysqli_query( $db, $sqlInsercionBloque );

    			if ( $resultadoInsercionBloque ) {
    				
    				// OBTENCION id_blo MAXIMO

    				$sqlMaximoBloque = "
						SELECT MAX( id_blo ) AS maximo FROM bloque
    				";

    				$resultadoMaximoBloque = mysqli_query( $db, $sqlMaximoBloque );

    				$filaMaximoBloque = mysqli_fetch_assoc( $resultadoMaximoBloque );

    				$id_blo_max = $filaMaximoBloque['maximo'];



    				// DUPLICIDAD DE CONTENIDOS DE BLOQUE

    				// VIDEOS
    				// CONSULTA
    				$sqlVideos = "
						SELECT *
						FROM video
						WHERE id_blo1 = '$id_blo'
    				";

    				$resultadoVideos = mysqli_query( $db, $sqlVideos );

    				while ( $filaVideos = mysqli_fetch_assoc( $resultadoVideos ) ) {
    					$nom_vid = $filaVideos['nom_vid'];
	    				$des_vid = $filaVideos['des_vid'];
	    				$vid_vid = $filaVideos['vid_vid'];
	    				$url_vid = $filaVideos['url_vid'];
	    				$tip_vid = $filaVideos['tip_vid'];

	    				// INSERCION

	    				$sqlInsercionVideo = "
							INSERT INTO video ( nom_vid, des_vid, vid_vid, url_vid, tip_vid, id_blo1 ) 
							VALUES ( '$nom_vid', '$des_vid', '$vid_vid', '$url_vid', '$tip_vid', '$id_blo_max' )
	    				";

	    				$resultadoInsercionVideo = mysqli_query( $db, $sqlInsercionVideo );

	    				if ( !$resultadoInsercionVideo ) {
	    					echo $sqlInsercionVideo;

	    					// break; break;
	    				}

    				}
    				

    				// WIKIS
    				// CONSULTA
    				$sqlWikis = "
						SELECT *
						FROM wiki
						WHERE id_blo2 = '$id_blo'
    				";

    				$resultadoWikis = mysqli_query( $db, $sqlWikis );

    				while ( $filaWikis = mysqli_fetch_assoc( $resultadoWikis ) )  {

    					$nom_wik = $filaWikis['nom_wik'];
	    				$des_wik = $filaWikis['des_wik'];
	    				$tip_wik = $filaWikis['tip_wik'];

	    				// INSERCION

	    				$sqlInsercionWiki = "
							INSERT INTO wiki ( nom_wik, des_wik, tip_wik, id_blo2 ) 
							VALUES ( '$nom_wik', '$des_wik', '$tip_wik', '$id_blo_max' )
	    				";

	    				$resultadoInsercionWiki = mysqli_query( $db, $sqlInsercionWiki );
    				}

    				


    				// ARCHIVOS
    				// CONSULTA
    				$sqlArchivos = "
						SELECT *
						FROM archivo
						WHERE id_blo3 = '$id_blo'
    				";

    				$resultadoArchivos = mysqli_query( $db, $sqlArchivos );

    				

    				while ( $filaArchivos = mysqli_fetch_assoc( $resultadoArchivos ) ) {
    					$nom_arc = $filaArchivos['nom_arc'];
	    				$des_arc = $filaArchivos['des_arc'];
	    				$arc_arc = $filaArchivos['arc_arc'];
	    				$tip_arc = $filaArchivos['tip_arc'];

	    				// INSERCION

	    				$sqlInsercionArchivo = "
							INSERT INTO archivo ( nom_arc, des_arc, arc_arc, tip_arc, id_blo3 ) 
							VALUES ( '$nom_arc', '$des_arc', '$arc_arc', '$tip_arc', '$id_blo_max' )
	    				";

	    				$resultadoInsercionArchivo = mysqli_query( $db, $sqlInsercionArchivo );
    				}


    				// FOROS
    				// CONSULTA
    				$sqlForos = "
						SELECT *
						FROM foro
						WHERE id_blo4 = '$id_blo'
    				";

    				$resultadoForos = mysqli_query( $db, $sqlForos );

    				while ( $filaForos = mysqli_fetch_assoc( $resultadoForos ) ) {

    					$nom_for = $filaForos['nom_for'];
	    				$des_for = $filaForos['des_for'];
	    				$tip_for = $filaForos['tip_for'];
	    				$pun_for = $filaForos['pun_for'];
	    				$ini_for = $filaForos['ini_for'];
	    				$fin_for = $filaForos['fin_for'];
	    				// INSERCION

	    				$sqlInsercionForo = "
							INSERT INTO foro ( nom_for, des_for, tip_for, pun_for, ini_for, fin_for, id_blo4 ) 
							VALUES ( '$nom_for', '$des_for', '$tip_for', '$pun_for', '$ini_for', '$fin_for', '$id_blo_max' )
	    				";

	    				$resultadoInsercionForo = mysqli_query( $db, $sqlInsercionForo );
    				}




    				// ENTREGABLES
    				// CONSULTA
    				$sqlEntregables = "
						SELECT *
						FROM entregable
						WHERE id_blo5 = '$id_blo'
    				";

    				$resultadoEntregables = mysqli_query( $db, $sqlEntregables );

    				while ( $filaEntregables = mysqli_fetch_assoc( $resultadoEntregables ) ) {

    					$nom_ent = $filaEntregables['nom_ent'];
	    				$des_ent = $filaEntregables['des_ent'];
	    				$tip_ent = $filaEntregables['tip_ent'];
	    				$pun_ent = $filaEntregables['pun_ent'];
	    				$ini_ent = $filaEntregables['ini_ent'];
	    				$fin_ent = $filaEntregables['fin_ent'];
	    				// INSERCION

	    				$sqlInsercionEntregable = "
							INSERT INTO entregable ( nom_ent, des_ent, tip_ent, pun_ent, ini_ent, fin_ent, id_blo5 ) 
							VALUES ( '$nom_ent', '$des_ent', '$tip_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$id_blo_max' )
	    				";

	    				$resultadoInsercionEntregable = mysqli_query( $db, $sqlInsercionEntregable );
    				}

    				


    				// EXAMEN
					// CONSULTA
					$sqlExamenes = "
						SELECT *
						FROM examen
						WHERE id_blo6 = '$id_blo'
					";

					$resultadoExamenes = mysqli_query( $db, $sqlExamenes );

					while ( $filaExamenes = mysqli_fetch_assoc( $resultadoExamenes ) )  {

						$id_exa = $filaExamenes['id_exa'];
						$nom_exa = $filaExamenes['nom_exa'];
						$des_exa = $filaExamenes['des_exa'];
						$tip_exa = $filaExamenes['tip_exa'];
						$pun_exa = $filaExamenes['pun_exa'];
						$ini_exa = $filaExamenes['ini_exa'];
						$fin_exa = $filaExamenes['fin_exa'];
						$dur_exa = $filaExamenes['dur_exa'];

						// INSERCION

						$sqlInsercionExamen = "
							INSERT INTO examen ( nom_exa, des_exa, tip_exa, pun_exa, ini_exa, fin_exa,  dur_exa, id_blo6 ) 
							VALUES ( '$nom_exa', '$des_exa', '$tip_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$id_blo_max' )
						";

						$resultadoInsercionExamen = mysqli_query( $db, $sqlInsercionExamen );

						if ( $resultadoInsercionExamen ) {
							// OBTENCION DE id_exa MAXIMO
							$sqlMaximoExamen = "
								SELECT MAX( id_exa ) AS maximo FROM examen
							";

							$resultadoMaximoExamen = mysqli_query( $db, $sqlMaximoExamen );

							$filaMaximoExamen = mysqli_fetch_assoc( $resultadoMaximoExamen );

							$id_exa_max = $filaMaximoExamen['maximo'];

							// CONSULTA DE PREGUNTAS EN TABLA pregunta
							$sqlPreguntas = "
								SELECT *
								FROM pregunta
								WHERE id_exa2 = '$id_exa'
							";

							$resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

							while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
								// DATOS DE pregunta
								$id_pre = $filaPreguntas['id_pre'];
								$pre_pre = $filaPreguntas['pre_pre'];
								$pun_pre = $filaPreguntas['pun_pre'];


								$sqlInsercionPregunta = "
									INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
									VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
								";

								$resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

								if ( $resultadoInsercionPregunta ) {
									// OBTENCION DE RESPUESTAS EN TABLA respuesta
									$sqlMaximoRespuesta = "
										SELECT MAX( id_pre ) AS maximo FROM pregunta
									";

									$resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

									$filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

									$id_pre_max = $filaMaximoRespuesta['maximo'];

									// DATOS DE respuesta
									$sqlRespuesta = "
										SELECT *
										FROM respuesta
										WHERE id_pre1 = '$id_pre'
									";

									$resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

									while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
										// CONSULTA
										$res_res = $filaRespuesta['res_res'];
										$val_res = $filaRespuesta['val_res'];

										// INSERCION

										$sqlInsercionRespuesta = "
											INSERT INTO respuesta ( res_res, val_res, id_pre1 )
											VALUES ( '$res_res', '$val_res', '$id_pre_max' )
										";

										$resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

									}

								}

							}

						}

					}
					
    			}

    		}



    	// FIN WHILE MATERIAS P1
    	}

    }



    // FUNCION QUE COPIA CONTENIDO DE LA MATERIA_A A LA MATERIA_B
    function duplicarContenidoProgramaMateriaAMateria( $materia_emisora, $materia_receptora ) {   
      require( '../includes/conexion.php' );
      
      // 
      // BLOQUES

      $sqlBloques = "
      SELECT *
      FROM bloque
      WHERE id_mat6 = '$materia_emisora'
      ";

      $resultadoBloques = mysqli_query( $db, $sqlBloques );


      while( $filaBloques = mysqli_fetch_assoc( $resultadoBloques ) ) {
        // DATOS DEL BLOQUE
        $id_blo = $filaBloques['id_blo'];
        $nom_blo = $filaBloques['nom_blo'];
        $des_blo = $filaBloques['des_blo'];
        $con_blo = $filaBloques['con_blo'];
        $img_blo = $filaBloques['img_blo'];
        
        // INSERCION DEL BLOQUE A MATERIAS DE P2

        $sqlInsercionBloque = "
        INSERT INTO bloque ( nom_blo, des_blo, con_blo, img_blo, id_mat6 )
        VALUES ( '$nom_blo', '$des_blo', '$con_blo', '$img_blo', '$materia_receptora' )
        ";

        // echo $sqlInsercionBloque;

        $resultadoInsercionBloque = mysqli_query( $db, $sqlInsercionBloque );

        if ( $resultadoInsercionBloque ) {
          
          // OBTENCION id_blo MAXIMO

          $sqlMaximoBloque = "
          SELECT MAX( id_blo ) AS maximo FROM bloque
          ";

          $resultadoMaximoBloque = mysqli_query( $db, $sqlMaximoBloque );

          $filaMaximoBloque = mysqli_fetch_assoc( $resultadoMaximoBloque );

          $id_blo_max = $filaMaximoBloque['maximo'];



          // DUPLICIDAD DE CONTENIDOS DE BLOQUE

          // VIDEOS
          // CONSULTA
          $sqlVideos = "
          SELECT *
          FROM video
          WHERE id_blo1 = '$id_blo'
          ";

          $resultadoVideos = mysqli_query( $db, $sqlVideos );

          while ( $filaVideos = mysqli_fetch_assoc( $resultadoVideos ) ) {
            $nom_vid = $filaVideos['nom_vid'];
            $des_vid = $filaVideos['des_vid'];
            $vid_vid = $filaVideos['vid_vid'];
            $url_vid = $filaVideos['url_vid'];
            $tip_vid = $filaVideos['tip_vid'];

            // INSERCION

            $sqlInsercionVideo = "
            INSERT INTO video ( nom_vid, des_vid, vid_vid, url_vid, tip_vid, id_blo1 ) 
            VALUES ( '$nom_vid', '$des_vid', '$vid_vid', '$url_vid', '$tip_vid', '$id_blo_max' )
            ";

            $resultadoInsercionVideo = mysqli_query( $db, $sqlInsercionVideo );

            if ( !$resultadoInsercionVideo ) {
              echo $sqlInsercionVideo;

              // break; break;
            }

          }
          

          // WIKIS
          // CONSULTA
          $sqlWikis = "
          SELECT *
          FROM wiki
          WHERE id_blo2 = '$id_blo'
          ";

          $resultadoWikis = mysqli_query( $db, $sqlWikis );

          while ( $filaWikis = mysqli_fetch_assoc( $resultadoWikis ) )  {

            $nom_wik = $filaWikis['nom_wik'];
            $des_wik = $filaWikis['des_wik'];
            $tip_wik = $filaWikis['tip_wik'];

            // INSERCION

            $sqlInsercionWiki = "
            INSERT INTO wiki ( nom_wik, des_wik, tip_wik, id_blo2 ) 
            VALUES ( '$nom_wik', '$des_wik', '$tip_wik', '$id_blo_max' )
            ";

            $resultadoInsercionWiki = mysqli_query( $db, $sqlInsercionWiki );
          }

          


          // ARCHIVOS
          // CONSULTA
          $sqlArchivos = "
          SELECT *
          FROM archivo
          WHERE id_blo3 = '$id_blo'
          ";

          $resultadoArchivos = mysqli_query( $db, $sqlArchivos );

          

          while ( $filaArchivos = mysqli_fetch_assoc( $resultadoArchivos ) ) {
            $nom_arc = $filaArchivos['nom_arc'];
            $des_arc = $filaArchivos['des_arc'];
            $arc_arc = $filaArchivos['arc_arc'];
            $tip_arc = $filaArchivos['tip_arc'];

            // INSERCION

            $sqlInsercionArchivo = "
            INSERT INTO archivo ( nom_arc, des_arc, arc_arc, tip_arc, id_blo3 ) 
            VALUES ( '$nom_arc', '$des_arc', '$arc_arc', '$tip_arc', '$id_blo_max' )
            ";

            $resultadoInsercionArchivo = mysqli_query( $db, $sqlInsercionArchivo );
          }


          // FOROS
          // CONSULTA
          $sqlForos = "
          SELECT *
          FROM foro
          WHERE id_blo4 = '$id_blo'
          ";

          $resultadoForos = mysqli_query( $db, $sqlForos );

          while ( $filaForos = mysqli_fetch_assoc( $resultadoForos ) ) {

            $nom_for = $filaForos['nom_for'];
            $des_for = $filaForos['des_for'];
            $tip_for = $filaForos['tip_for'];
            $pun_for = $filaForos['pun_for'];
            $ini_for = $filaForos['ini_for'];
            $fin_for = $filaForos['fin_for'];
            // INSERCION

            $sqlInsercionForo = "
            INSERT INTO foro ( nom_for, des_for, tip_for, pun_for, ini_for, fin_for, id_blo4 ) 
            VALUES ( '$nom_for', '$des_for', '$tip_for', '$pun_for', '$ini_for', '$fin_for', '$id_blo_max' )
            ";

            $resultadoInsercionForo = mysqli_query( $db, $sqlInsercionForo );
          }




          // ENTREGABLES
          // CONSULTA
          $sqlEntregables = "
          SELECT *
          FROM entregable
          WHERE id_blo5 = '$id_blo'
          ";

          $resultadoEntregables = mysqli_query( $db, $sqlEntregables );

          while ( $filaEntregables = mysqli_fetch_assoc( $resultadoEntregables ) ) {

            $nom_ent = $filaEntregables['nom_ent'];
            $des_ent = $filaEntregables['des_ent'];
            $tip_ent = $filaEntregables['tip_ent'];
            $pun_ent = $filaEntregables['pun_ent'];
            $ini_ent = $filaEntregables['ini_ent'];
            $fin_ent = $filaEntregables['fin_ent'];
            // INSERCION

            $sqlInsercionEntregable = "
            INSERT INTO entregable ( nom_ent, des_ent, tip_ent, pun_ent, ini_ent, fin_ent, id_blo5 ) 
            VALUES ( '$nom_ent', '$des_ent', '$tip_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$id_blo_max' )
            ";

            $resultadoInsercionEntregable = mysqli_query( $db, $sqlInsercionEntregable );
          }

          


          // EXAMEN
        // CONSULTA
        $sqlExamenes = "
          SELECT *
          FROM examen
          WHERE id_blo6 = '$id_blo'
        ";

        $resultadoExamenes = mysqli_query( $db, $sqlExamenes );

        while ( $filaExamenes = mysqli_fetch_assoc( $resultadoExamenes ) )  {

          $id_exa = $filaExamenes['id_exa'];
          $nom_exa = $filaExamenes['nom_exa'];
          $des_exa = $filaExamenes['des_exa'];
          $tip_exa = $filaExamenes['tip_exa'];
          $pun_exa = $filaExamenes['pun_exa'];
          $ini_exa = $filaExamenes['ini_exa'];
          $fin_exa = $filaExamenes['fin_exa'];
          $dur_exa = $filaExamenes['dur_exa'];

          // INSERCION

          $sqlInsercionExamen = "
            INSERT INTO examen ( nom_exa, des_exa, tip_exa, pun_exa, ini_exa, fin_exa,  dur_exa, id_blo6 ) 
            VALUES ( '$nom_exa', '$des_exa', '$tip_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$id_blo_max' )
          ";

          $resultadoInsercionExamen = mysqli_query( $db, $sqlInsercionExamen );

          if ( $resultadoInsercionExamen ) {
            // OBTENCION DE id_exa MAXIMO
            $sqlMaximoExamen = "
              SELECT MAX( id_exa ) AS maximo FROM examen
            ";

            $resultadoMaximoExamen = mysqli_query( $db, $sqlMaximoExamen );

            $filaMaximoExamen = mysqli_fetch_assoc( $resultadoMaximoExamen );

            $id_exa_max = $filaMaximoExamen['maximo'];

            // CONSULTA DE PREGUNTAS EN TABLA pregunta
            $sqlPreguntas = "
              SELECT *
              FROM pregunta
              WHERE id_exa2 = '$id_exa'
            ";

            $resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

            while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
              // DATOS DE pregunta
              $id_pre = $filaPreguntas['id_pre'];
              $pre_pre = $filaPreguntas['pre_pre'];
              $pun_pre = $filaPreguntas['pun_pre'];


              $sqlInsercionPregunta = "
                INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
                VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
              ";

              $resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

              if ( $resultadoInsercionPregunta ) {
                // OBTENCION DE RESPUESTAS EN TABLA respuesta
                $sqlMaximoRespuesta = "
                  SELECT MAX( id_pre ) AS maximo FROM pregunta
                ";

                $resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

                $filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

                $id_pre_max = $filaMaximoRespuesta['maximo'];

                // DATOS DE respuesta
                $sqlRespuesta = "
                  SELECT *
                  FROM respuesta
                  WHERE id_pre1 = '$id_pre'
                ";

                $resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

                while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
                  // CONSULTA
                  $res_res = $filaRespuesta['res_res'];
                  $val_res = $filaRespuesta['val_res'];

                  // INSERCION

                  $sqlInsercionRespuesta = "
                    INSERT INTO respuesta ( res_res, val_res, id_pre1 )
                    VALUES ( '$res_res', '$val_res', '$id_pre_max' )
                  ";

                  $resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

                }

              }

            }

          }

        }
        
        }

      }
      // 
      

    }





    // FUNCION COPIAR BLOQUE
    // FUNCION QUE COPIA CONTENIDO DE LA MATERIA_A A LA MATERIA_B
    function duplicarContenidoBloqueAMateria( $bloque_emisor, $materia_receptora ) {   
      require( '../includes/conexion.php' );
      
      // 
      // BLOQUES

      $sqlBloques = "
      SELECT *
      FROM bloque
      WHERE id_blo = '$bloque_emisor'
      ";

      $resultadoBloques = mysqli_query( $db, $sqlBloques );

      $filaBloques = mysqli_fetch_assoc( $resultadoBloques );

        // DATOS DEL BLOQUE
        $id_blo = $filaBloques['id_blo'];
        $nom_blo = $filaBloques['nom_blo'];
        $des_blo = $filaBloques['des_blo'];
        $con_blo = $filaBloques['con_blo'];
        $img_blo = $filaBloques['img_blo'];
        
        // INSERCION DEL BLOQUE A MATERIAS DE P2

        $sqlInsercionBloque = "
        INSERT INTO bloque ( nom_blo, des_blo, con_blo, img_blo, id_mat6 )
        VALUES ( '$nom_blo', '$des_blo', '$con_blo', '$img_blo', '$materia_receptora' )
        ";

        // echo $sqlInsercionBloque;

        $resultadoInsercionBloque = mysqli_query( $db, $sqlInsercionBloque );

        if ( $resultadoInsercionBloque ) {
          
          // OBTENCION id_blo MAXIMO

          $sqlMaximoBloque = "
          SELECT MAX( id_blo ) AS maximo FROM bloque
          ";

          $resultadoMaximoBloque = mysqli_query( $db, $sqlMaximoBloque );

          $filaMaximoBloque = mysqli_fetch_assoc( $resultadoMaximoBloque );

          $id_blo_max = $filaMaximoBloque['maximo'];



          // DUPLICIDAD DE CONTENIDOS DE BLOQUE

          // VIDEOS
          // CONSULTA
          $sqlVideos = "
          SELECT *
          FROM video
          WHERE id_blo1 = '$id_blo'
          ";

          $resultadoVideos = mysqli_query( $db, $sqlVideos );

          while ( $filaVideos = mysqli_fetch_assoc( $resultadoVideos ) ) {
            $nom_vid = $filaVideos['nom_vid'];
            $des_vid = $filaVideos['des_vid'];
            $vid_vid = $filaVideos['vid_vid'];
            $url_vid = $filaVideos['url_vid'];
            $tip_vid = $filaVideos['tip_vid'];

            // INSERCION

            $sqlInsercionVideo = "
            INSERT INTO video ( nom_vid, des_vid, vid_vid, url_vid, tip_vid, id_blo1 ) 
            VALUES ( '$nom_vid', '$des_vid', '$vid_vid', '$url_vid', '$tip_vid', '$id_blo_max' )
            ";

            $resultadoInsercionVideo = mysqli_query( $db, $sqlInsercionVideo );

            if ( !$resultadoInsercionVideo ) {
              echo $sqlInsercionVideo;

              // break; break;
            }

          }
          

          // WIKIS
          // CONSULTA
          $sqlWikis = "
          SELECT *
          FROM wiki
          WHERE id_blo2 = '$id_blo'
          ";

          $resultadoWikis = mysqli_query( $db, $sqlWikis );

          while ( $filaWikis = mysqli_fetch_assoc( $resultadoWikis ) )  {

            $nom_wik = $filaWikis['nom_wik'];
            $des_wik = $filaWikis['des_wik'];
            $tip_wik = $filaWikis['tip_wik'];

            // INSERCION

            $sqlInsercionWiki = "
            INSERT INTO wiki ( nom_wik, des_wik, tip_wik, id_blo2 ) 
            VALUES ( '$nom_wik', '$des_wik', '$tip_wik', '$id_blo_max' )
            ";

            $resultadoInsercionWiki = mysqli_query( $db, $sqlInsercionWiki );
          }

          


          // ARCHIVOS
          // CONSULTA
          $sqlArchivos = "
          SELECT *
          FROM archivo
          WHERE id_blo3 = '$id_blo'
          ";

          $resultadoArchivos = mysqli_query( $db, $sqlArchivos );

          

          while ( $filaArchivos = mysqli_fetch_assoc( $resultadoArchivos ) ) {
            $nom_arc = $filaArchivos['nom_arc'];
            $des_arc = $filaArchivos['des_arc'];
            $arc_arc = $filaArchivos['arc_arc'];
            $tip_arc = $filaArchivos['tip_arc'];

            // INSERCION

            $sqlInsercionArchivo = "
            INSERT INTO archivo ( nom_arc, des_arc, arc_arc, tip_arc, id_blo3 ) 
            VALUES ( '$nom_arc', '$des_arc', '$arc_arc', '$tip_arc', '$id_blo_max' )
            ";

            $resultadoInsercionArchivo = mysqli_query( $db, $sqlInsercionArchivo );
          }


          // FOROS
          // CONSULTA
          $sqlForos = "
          SELECT *
          FROM foro
          WHERE id_blo4 = '$id_blo'
          ";

          $resultadoForos = mysqli_query( $db, $sqlForos );

          while ( $filaForos = mysqli_fetch_assoc( $resultadoForos ) ) {

            $nom_for = $filaForos['nom_for'];
            $des_for = $filaForos['des_for'];
            $tip_for = $filaForos['tip_for'];
            $pun_for = $filaForos['pun_for'];
            $ini_for = $filaForos['ini_for'];
            $fin_for = $filaForos['fin_for'];
            // INSERCION

            $sqlInsercionForo = "
            INSERT INTO foro ( nom_for, des_for, tip_for, pun_for, ini_for, fin_for, id_blo4 ) 
            VALUES ( '$nom_for', '$des_for', '$tip_for', '$pun_for', '$ini_for', '$fin_for', '$id_blo_max' )
            ";

            $resultadoInsercionForo = mysqli_query( $db, $sqlInsercionForo );
          }




          // ENTREGABLES
          // CONSULTA
          $sqlEntregables = "
          SELECT *
          FROM entregable
          WHERE id_blo5 = '$id_blo'
          ";

          $resultadoEntregables = mysqli_query( $db, $sqlEntregables );

          while ( $filaEntregables = mysqli_fetch_assoc( $resultadoEntregables ) ) {

            $nom_ent = $filaEntregables['nom_ent'];
            $des_ent = $filaEntregables['des_ent'];
            $tip_ent = $filaEntregables['tip_ent'];
            $pun_ent = $filaEntregables['pun_ent'];
            $ini_ent = $filaEntregables['ini_ent'];
            $fin_ent = $filaEntregables['fin_ent'];
            // INSERCION

            $sqlInsercionEntregable = "
            INSERT INTO entregable ( nom_ent, des_ent, tip_ent, pun_ent, ini_ent, fin_ent, id_blo5 ) 
            VALUES ( '$nom_ent', '$des_ent', '$tip_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$id_blo_max' )
            ";

            $resultadoInsercionEntregable = mysqli_query( $db, $sqlInsercionEntregable );
          }

          


          // EXAMEN
        // CONSULTA
        $sqlExamenes = "
          SELECT *
          FROM examen
          WHERE id_blo6 = '$id_blo'
        ";

        $resultadoExamenes = mysqli_query( $db, $sqlExamenes );

        while ( $filaExamenes = mysqli_fetch_assoc( $resultadoExamenes ) )  {

          $id_exa = $filaExamenes['id_exa'];
          $nom_exa = $filaExamenes['nom_exa'];
          $des_exa = $filaExamenes['des_exa'];
          $tip_exa = $filaExamenes['tip_exa'];
          $pun_exa = $filaExamenes['pun_exa'];
          $ini_exa = $filaExamenes['ini_exa'];
          $fin_exa = $filaExamenes['fin_exa'];
          $dur_exa = $filaExamenes['dur_exa'];

          // INSERCION

          $sqlInsercionExamen = "
            INSERT INTO examen ( nom_exa, des_exa, tip_exa, pun_exa, ini_exa, fin_exa,  dur_exa, id_blo6 ) 
            VALUES ( '$nom_exa', '$des_exa', '$tip_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$id_blo_max' )
          ";

          $resultadoInsercionExamen = mysqli_query( $db, $sqlInsercionExamen );

          if ( $resultadoInsercionExamen ) {
            // OBTENCION DE id_exa MAXIMO
            $sqlMaximoExamen = "
              SELECT MAX( id_exa ) AS maximo FROM examen
            ";

            $resultadoMaximoExamen = mysqli_query( $db, $sqlMaximoExamen );

            $filaMaximoExamen = mysqli_fetch_assoc( $resultadoMaximoExamen );

            $id_exa_max = $filaMaximoExamen['maximo'];

            // CONSULTA DE PREGUNTAS EN TABLA pregunta
            $sqlPreguntas = "
              SELECT *
              FROM pregunta
              WHERE id_exa2 = '$id_exa'
            ";

            $resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

            while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
              // DATOS DE pregunta
              $id_pre = $filaPreguntas['id_pre'];
              $pre_pre = $filaPreguntas['pre_pre'];
              $pun_pre = $filaPreguntas['pun_pre'];


              $sqlInsercionPregunta = "
                INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
                VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
              ";

              $resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

              if ( $resultadoInsercionPregunta ) {
                // OBTENCION DE RESPUESTAS EN TABLA respuesta
                $sqlMaximoRespuesta = "
                  SELECT MAX( id_pre ) AS maximo FROM pregunta
                ";

                $resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

                $filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

                $id_pre_max = $filaMaximoRespuesta['maximo'];

                // DATOS DE respuesta
                $sqlRespuesta = "
                  SELECT *
                  FROM respuesta
                  WHERE id_pre1 = '$id_pre'
                ";

                $resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

                while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
                  // CONSULTA
                  $res_res = $filaRespuesta['res_res'];
                  $val_res = $filaRespuesta['val_res'];

                  // INSERCION

                  $sqlInsercionRespuesta = "
                    INSERT INTO respuesta ( res_res, val_res, id_pre1 )
                    VALUES ( '$res_res', '$val_res', '$id_pre_max' )
                  ";

                  $resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

                }

              }

            }

          }

        }
        
        }

      // }
      // 
      

    }


    // FUNCION CONTENIDO BLOQUES CALENDARIZADO

    function copiarContenidoBloques( $id_sub_hor, $id_blo ){
    	require( '../includes/conexion.php' );

    	$sqlHorario = "
			SELECT *
			FROM sub_hor
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			WHERE id_sub_hor = '$id_sub_hor'
	    ";

	    $resultadoHorario = mysqli_query( $db, $sqlHorario );

	    $filaHorario = mysqli_fetch_assoc( $resultadoHorario );

	    $id_mat = $filaHorario['id_mat1'];

	    $ini_cic = $filaHorario['ini_cic'];

	    $sqlBloque = "
	    	SELECT * 
	    	FROM bloque 
	    	WHERE id_mat6 = '$id_mat' AND id_blo = '$id_blo'
	    	ORDER BY id_blo DESC
	    	LIMIT 1
	    ";

	    // echo $sqlBloque;
		$resultadoBloque = mysqli_query( $db, $sqlBloque );

		//WHILE BLOQUES
		while( $filaBloque = mysqli_fetch_assoc($resultadoBloque) ){

			$id_blo = $filaBloque['id_blo'];

			// P2- FOROS ASOCIADOS AL BLOQUE
			$sqlForo = "SELECT * FROM foro WHERE id_blo4 = '$id_blo'";
			$resultadoForo = mysqli_query($db, $sqlForo);


			while($filaForo = mysqli_fetch_assoc($resultadoForo)){

				if ($filaForo['ini_for'] == "" OR $filaForo['fin_for'] == "") {
					
				}else{

					$ini_for_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaForo['ini_for'].' day' , strtotime ( $ini_cic )));
					$fin_for_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaForo['fin_for'].' day' , strtotime ( $ini_cic )));
					$id_for1     = $filaForo['id_for']; //EXTRACCION DE CLAVE FORANEA 
					$id_sub_hor2 = $id_sub_hor; //EXTRACCION DE LA OTRA CLAVE FORANEA

					$sqlForoCopia = "
						INSERT INTO foro_copia(ini_for_cop, fin_for_cop, id_for1, id_sub_hor2) 
						VALUES('$ini_for_cop', '$fin_for_cop', '$id_for1', '$id_sub_hor2')
					";

					$resuladoForoCopia = mysqli_query($db, $sqlForoCopia);

					$sqlMaximo = "
						SELECT MAX( id_for_cop ) AS maximo
						FROM foro_copia
						WHERE id_for1 = '$id_for1'
					";

					$resultadoMaximo = mysqli_query( $db, $sqlMaximo );

					$filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

					$id_for_cop = $filaMaximo['maximo'];

					$sqlAlumnos = "
						SELECT * 
						FROM alu_hor 
						INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1 
						WHERE id_sub_hor5 = '$id_sub_hor'
					";


					$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );


					while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

						$id_alu_ram = $filaAlumnos['id_alu_ram'];

						$sqlInsercionForos = "
							INSERT INTO cal_act(id_for_cop2, id_alu_ram4) 
							VALUES('$id_for_cop', '$id_alu_ram')
						";

						$resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);


					}
					
				}
				
			}


			// P3- ENTREGABLES ASOCIADOS AL BLOQUE
			
			$sqlEntregable = "SELECT * FROM entregable WHERE id_blo5 = '$id_blo'";
			$resultadoEntregable = mysqli_query($db, $sqlEntregable);


			while($filaEntregable = mysqli_fetch_assoc($resultadoEntregable)){
				
				

				if ($filaEntregable['ini_ent'] == "" OR $filaEntregable['fin_ent'] == "") {
					
				}else{
			
					$ini_ent_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaEntregable['ini_ent'].' day' , strtotime ( $ini_cic )));
					$fin_ent_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaEntregable['fin_ent'].' day' , strtotime ( $ini_cic )));
					$id_ent1     = $filaEntregable['id_ent']; //EXTRACCION DE CLAVE FORANEA 
					$id_sub_hor3 = $id_sub_hor; //EXTRACCION DE LA OTRA CLAVE FORANEA

					$sqlEntregableCopia = "INSERT INTO entregable_copia(ini_ent_cop, fin_ent_cop, id_ent1, id_sub_hor3) VALUES('$ini_ent_cop', '$fin_ent_cop', '$id_ent1', '$id_sub_hor3')";

					//echo $sqlEntregableCopia;
					$resuladoEntregableCopia = mysqli_query($db, $sqlEntregableCopia);

					$sqlMaximo = "
						SELECT MAX( id_ent_cop ) AS maximo
						FROM entregable_copia
						WHERE id_ent1 = '$id_ent1'
					";

					$resultadoMaximo = mysqli_query( $db, $sqlMaximo );

					$filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

					$id_ent_cop = $filaMaximo['maximo'];


					$sqlAlumnos = "
						SELECT * 
						FROM alu_hor 
						INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1 
						WHERE id_sub_hor5 = '$id_sub_hor'
					";


					$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );


					while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

						$id_alu_ram = $filaAlumnos['id_alu_ram'];

						$sqlInsercionEntregables = "
							INSERT INTO cal_act(id_ent_cop2, id_alu_ram4) 
							VALUES('$id_ent_cop', '$id_alu_ram')
						";

						$resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);


					}
				}
				


			}


			//P4- EXAMENES ASOCIADOS AL BLOQUE
			$sqlExamen = "SELECT * FROM examen WHERE id_blo6 = '$id_blo'";
			$resultadoExamen = mysqli_query($db, $sqlExamen);


			while($filaExamen = mysqli_fetch_assoc($resultadoExamen)){
				
				

				if ($filaExamen['ini_exa'] == "" OR $filaExamen['fin_exa'] == "") {
					
				}else{
					$ini_exa_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaExamen['ini_exa'].' day' , strtotime ( $ini_cic )));
					$fin_exa_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaExamen['fin_exa'].' day' , strtotime ( $ini_cic )));
					$id_exa1     = $filaExamen['id_exa']; //EXTRACCION DE CLAVE FORANEA 
					$id_sub_hor4 = $id_sub_hor; //EXTRACCION DE LA OTRA CLAVE FORANEA

					$sqlExamenCopia = "INSERT INTO examen_copia(ini_exa_cop, fin_exa_cop, id_exa1, id_sub_hor4) VALUES('$ini_exa_cop', '$fin_exa_cop', '$id_exa1', '$id_sub_hor4')";
					$resuladoExamenCopia = mysqli_query($db, $sqlExamenCopia);


					$sqlMaximo = "
						SELECT MAX( id_exa_cop ) AS maximo
						FROM examen_copia
						WHERE id_exa1 = '$id_exa1'
					";

					$resultadoMaximo = mysqli_query( $db, $sqlMaximo );

					$filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

					$id_exa_cop = $filaMaximo['maximo'];


					// 

					$sqlAlumnos = "
						SELECT * 
						FROM alu_hor 
						INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1 
						WHERE id_sub_hor5 = '$id_sub_hor'
					";


					$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );


					while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

						$id_alu_ram = $filaAlumnos['id_alu_ram'];

						$sqlInsercionExamenes = "
							INSERT INTO cal_act(id_exa_cop2, id_alu_ram4) 
							VALUES('$id_exa_cop', '$id_alu_ram')
						";

						$resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes );


					}


				}

			}


		}
		//FIN WHILE BLOQUES
    }







    // FUNCION CONTENIDO MATERIA CALENDARIZADO DESDE SUB_HOR

    function copiarContenidoMateria( $id_sub_hor ){
      require( '../includes/conexion.php' );

      $sqlHorario = "
        SELECT *
        FROM sub_hor
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
        WHERE id_sub_hor = '$id_sub_hor'
      ";

      $resultadoHorario = mysqli_query( $db, $sqlHorario );

      $filaHorario = mysqli_fetch_assoc( $resultadoHorario );

      $id_mat = $filaHorario['id_mat1'];

      $ini_cic = $filaHorario['ini_cic'];


      $sqlBloque = "
        SELECT * 
        FROM bloque 
        WHERE id_mat6 = '$id_mat'
        ORDER BY id_blo DESC
      ";

      // echo $sqlBloque;
    $resultadoBloque = mysqli_query( $db, $sqlBloque );

    //WHILE BLOQUES
    while( $filaBloque = mysqli_fetch_assoc($resultadoBloque) ){

      $id_blo = $filaBloque['id_blo'];

      // P2- FOROS ASOCIADOS AL BLOQUE
      $sqlForo = "SELECT * FROM foro WHERE id_blo4 = '$id_blo'";
      $resultadoForo = mysqli_query($db, $sqlForo);


      while($filaForo = mysqli_fetch_assoc($resultadoForo)){

        if ($filaForo['ini_for'] == "" OR $filaForo['fin_for'] == "") {
          
        }else{

          $ini_for_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaForo['ini_for'].' day' , strtotime ( $ini_cic )));
          $fin_for_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaForo['fin_for'].' day' , strtotime ( $ini_cic )));
          $id_for1     = $filaForo['id_for']; //EXTRACCION DE CLAVE FORANEA 
          $id_sub_hor2 = $id_sub_hor; //EXTRACCION DE LA OTRA CLAVE FORANEA

          $sqlForoCopia = "
            INSERT INTO foro_copia(ini_for_cop, fin_for_cop, id_for1, id_sub_hor2) 
            VALUES('$ini_for_cop', '$fin_for_cop', '$id_for1', '$id_sub_hor2')
          ";

          $resuladoForoCopia = mysqli_query($db, $sqlForoCopia);

          $sqlMaximo = "
            SELECT MAX( id_for_cop ) AS maximo
            FROM foro_copia
            WHERE id_for1 = '$id_for1'
          ";

          $resultadoMaximo = mysqli_query( $db, $sqlMaximo );

          $filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

          $id_for_cop = $filaMaximo['maximo'];

          $sqlAlumnos = "
            SELECT * 
            FROM alu_hor 
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1 
            WHERE id_sub_hor5 = '$id_sub_hor'
          ";


          $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );


          while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

            $id_alu_ram = $filaAlumnos['id_alu_ram'];
            $ini_cal_act = $ini_for_cop;
            $fin_cal_act = $fin_for_cop;

            $sqlInsercionForos = "
              INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) 
              VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act' )
            ";

            $resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);


          }
          
        }
        
      }


      // P3- ENTREGABLES ASOCIADOS AL BLOQUE
      
      $sqlEntregable = "SELECT * FROM entregable WHERE id_blo5 = '$id_blo'";
      $resultadoEntregable = mysqli_query($db, $sqlEntregable);


      while($filaEntregable = mysqli_fetch_assoc($resultadoEntregable)){
        
        

        if ($filaEntregable['ini_ent'] == "" OR $filaEntregable['fin_ent'] == "") {
          
        }else{
      
          $ini_ent_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaEntregable['ini_ent'].' day' , strtotime ( $ini_cic )));
          $fin_ent_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaEntregable['fin_ent'].' day' , strtotime ( $ini_cic )));
          $id_ent1     = $filaEntregable['id_ent']; //EXTRACCION DE CLAVE FORANEA 
          $id_sub_hor3 = $id_sub_hor; //EXTRACCION DE LA OTRA CLAVE FORANEA

          $sqlEntregableCopia = "INSERT INTO entregable_copia(ini_ent_cop, fin_ent_cop, id_ent1, id_sub_hor3) VALUES('$ini_ent_cop', '$fin_ent_cop', '$id_ent1', '$id_sub_hor3')";

          //echo $sqlEntregableCopia;
          $resuladoEntregableCopia = mysqli_query($db, $sqlEntregableCopia);

          $sqlMaximo = "
            SELECT MAX( id_ent_cop ) AS maximo
            FROM entregable_copia
            WHERE id_ent1 = '$id_ent1'
          ";

          $resultadoMaximo = mysqli_query( $db, $sqlMaximo );

          $filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

          $id_ent_cop = $filaMaximo['maximo'];


          $sqlAlumnos = "
            SELECT * 
            FROM alu_hor 
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1 
            WHERE id_sub_hor5 = '$id_sub_hor'
          ";


          $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );


          while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

            $id_alu_ram = $filaAlumnos['id_alu_ram'];
            $ini_cal_act = $ini_ent_cop;
            $fin_cal_act = $fin_ent_cop;

            $sqlInsercionEntregables = "
              INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) 
              VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act' )
            ";

            $resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);

            // if ( !$resultadoInsercionEntregables ) {
              // echo $sqlInsercionEntregables;
            // }


          }
        }
        


      }


      //P4- EXAMENES ASOCIADOS AL BLOQUE
      $sqlExamen = "SELECT * FROM examen WHERE id_blo6 = '$id_blo'";
      $resultadoExamen = mysqli_query($db, $sqlExamen);


      while($filaExamen = mysqli_fetch_assoc($resultadoExamen)){
        
        

        if ($filaExamen['ini_exa'] == "" OR $filaExamen['fin_exa'] == "") {
          
        }else{
          $ini_exa_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaExamen['ini_exa'].' day' , strtotime ( $ini_cic )));
          $fin_exa_cop = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$filaExamen['fin_exa'].' day' , strtotime ( $ini_cic )));
          $id_exa1     = $filaExamen['id_exa']; //EXTRACCION DE CLAVE FORANEA 
          $id_sub_hor4 = $id_sub_hor; //EXTRACCION DE LA OTRA CLAVE FORANEA

          $sqlExamenCopia = "INSERT INTO examen_copia(ini_exa_cop, fin_exa_cop, id_exa1, id_sub_hor4) VALUES('$ini_exa_cop', '$fin_exa_cop', '$id_exa1', '$id_sub_hor4')";
          $resuladoExamenCopia = mysqli_query($db, $sqlExamenCopia);


          $sqlMaximo = "
            SELECT MAX( id_exa_cop ) AS maximo
            FROM examen_copia
            WHERE id_exa1 = '$id_exa1'
          ";

          $resultadoMaximo = mysqli_query( $db, $sqlMaximo );

          $filaMaximo = mysqli_fetch_assoc( $resultadoMaximo );

          $id_exa_cop = $filaMaximo['maximo'];


          // 

          $sqlAlumnos = "
            SELECT * 
            FROM alu_hor 
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1 
            WHERE id_sub_hor5 = '$id_sub_hor'
          ";


          $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );


          while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

            $id_alu_ram = $filaAlumnos['id_alu_ram'];
            $ini_cal_act = $ini_exa_cop;
            $fin_cal_act = $fin_exa_cop;

            $sqlInsercionExamenes = "
              INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) 
              VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act' )
            ";

            $resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes );


          }


        }

      }


    }
    //FIN WHILE BLOQUES
    }



  // funciona cuando eliminan una generacion, debido al set null, siguen alli los alumnos, se encarga de matchearlos con la generacion a la que fueron enviados
  function recuperarPagos( $programa_emisor, $programa_receptor ){
    include( 'inc/conexion.php' );

    $sql = "
      SELECT * FROM alu_ram WHERE id_gen1 IS NULL AND id_ram3 = '$programa_emisor'
    ";

    // lic. integral - online 64


    $resultado = mysqli_query( $db, $sql );
    $i = 1;
    while( $fila = mysqli_fetch_assoc( $resultado ) ){

      // VALIDAR QUE ESE id_alu_ram de lic. integral ejecutiva online tenga pagos
      
      $id_alu = $fila['id_alu1'];

      $sql2 = "
        SELECT *
        FROM alu_ram
        WHERE id_alu1 = '$id_alu' AND id_ram3 = '$$programa_receptor'
      ";

      $resultado2 = mysqli_query( $db, $sql2 );

      $fila2 = mysqli_fetch_assoc( $resultado2 );

      
      $id_alu_ram1 = $fila2['id_alu_ram'];
      $id_alu_ram2 = $fila['id_alu_ram'];

      // echo $i.' consulta 1: id_alu1 = '.$fila['id_alu1'].' id_alu_ram =  '.$fila['id_alu_ram']."<br>";
      // echo 'consulta 2: id_alu1 = '.$fila2['id_alu1'].' id_alu_ram =  '.$fila2['id_alu_ram']."<br><br>";

      // $id_alu_ram1 = $fila['id_alu_ram'];
      

      $sql3 = "
        UPDATE pago
        SET
        id_alu_ram10 = '$id_alu_ram1'
        WHERE 
        id_alu_ram10 = '$id_alu_ram2'
      ";

      $resultado3 = mysqli_query( $db, $sql3 );

      if ( !$resultado3 ) {
        echo $sql3;
      }

      $i++;


    }
  }


  function agregarHorarioAlumno( $id_sub_hor, $id_alu_ram ){
    require( '../includes/conexion.php' );

    $fechaHoy = date('Y-m-d');
    
    
    $sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$id_sub_hor', '$fechaHoy', 'Activo' )";
    
    $resultado = mysqli_query($db, $sql);
    //echo $sql;

    //CAL_ACT PARA REGISTROS DE ACTIVIDADES CON CALIFICACION PENDIENTE
    //FOROS
    $sqlForos = "
      SELECT * 
      FROM sub_hor 
      INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
      WHERE id_sub_hor = '$id_sub_hor'
    ";


    $resultadoForos = mysqli_query($db, $sqlForos);


    while($filaForos = mysqli_fetch_assoc($resultadoForos)){

      $id_for_cop = $filaForos['id_for_cop'];
      $ini_cal_act = $filaForos['ini_for_cop'];
      $fin_cal_act = $filaForos['fin_for_cop'];

      $sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
      $resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);

      //echo $sqlInsercionForos;


    }

    //ENTREGABLES


    $sqlEntregables = "
      SELECT * 
      FROM sub_hor 
      INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
      INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
      WHERE id_sub_hor = '$id_sub_hor'
    ";



    // echo $sqlEntregables;
    
    $resultadoEntregables = mysqli_query($db, $sqlEntregables);


    while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
      $id_ent_cop = $filaEntregables['id_ent_cop'];
      // echo $filaEntregables['nom_ent'];

      $ini_cal_act = $filaEntregables['ini_ent_cop'];
      $fin_cal_act = $filaEntregables['fin_ent_cop'];

      $sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";

      $resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);

      //echo $sqlInsercionEntregables;
    }



    //EXAMENES

    $sqlExamenes = "
      SELECT * 
      FROM sub_hor 
      INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
      WHERE id_sub_hor = '$id_sub_hor'
    ";


    $resultadoExamenes = mysqli_query($db, $sqlExamenes);


    while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
      
      $id_exa_cop = $filaExamenes['id_exa_cop'];
      
      $ini_cal_act = $filaExamenes['ini_exa_cop'];
      $fin_cal_act = $filaExamenes['fin_exa_cop'];

      $sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
      
      $resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);

      //echo $sqlInsercionExamenes;

    }

  }




  // FUNCION PARA AGREGAR NUEVA MATERIA EN GRUPO QUE YA ARRANCO
  //  PARA FUNCIONAR SE DEBE GENERAR EL SUB_HOR EN UN GRUPO ASOCIADO AL MISMO CICLO, UPDATE Y EJECUCION DE FUNCION
  //id_sub_hor1 = extrae alumnos inmersos en una materia ya inscrita, 
  // id_sub_hor2 = materia a inscribir en los alumnos
  function obtener_inscripcion_materia_alumnos( $id_sub_hor1, $id_sub_hor2 ){
    require( '../includes/conexion.php' );

    $sqlAlumnos = "
      SELECT *
      FROM alu_hor
      WHERE id_sub_hor5 = '$id_sub_hor1'
    ";

    $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

    while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){
    // 
      $id_alu_ram = $filaAlumnos['id_alu_ram1'];

      $sql = "INSERT INTO alu_hor ( id_alu_ram1, id_sub_hor5, fec_alu_hor, est_alu_hor ) VALUES ('$id_alu_ram', '$id_sub_hor2', '$fechaHoy', 'Activo' )";

      $resultado = mysqli_query( $db, $sql );
      //echo $sql;

      //CAL_ACT PARA REGISTROS DE ACTIVIDADES CON CALIFICACION PENDIENTE
      //FOROS
      $sqlForos = "
        SELECT * 
        FROM sub_hor 
        INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
        WHERE id_sub_hor = '$id_sub_hor2'
      ";


      $resultadoForos = mysqli_query($db, $sqlForos);


      while($filaForos = mysqli_fetch_assoc($resultadoForos)){

        $id_for_cop = $filaForos['id_for_cop'];
        $ini_cal_act = $filaForos['ini_for_cop'];
        $fin_cal_act = $filaForos['fin_for_cop'];

        $sqlInsercionForos = "INSERT INTO cal_act(id_for_cop2, id_alu_ram4, ini_cal_act, fin_cal_act ) VALUES('$id_for_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
        $resultadoInsercionForos = mysqli_query($db, $sqlInsercionForos);

        //echo $sqlInsercionForos;


      }

      //ENTREGABLES


      $sqlEntregables = "
        SELECT * 
        FROM sub_hor 
        INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
        INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
        WHERE id_sub_hor = '$id_sub_hor2'
      ";



      // echo $sqlEntregables;
      
      $resultadoEntregables = mysqli_query($db, $sqlEntregables);


      while($filaEntregables = mysqli_fetch_assoc($resultadoEntregables)){
        $id_ent_cop = $filaEntregables['id_ent_cop'];
        // echo $filaEntregables['nom_ent'];

        $ini_cal_act = $filaEntregables['ini_ent_cop'];
        $fin_cal_act = $filaEntregables['fin_ent_cop'];

        $sqlInsercionEntregables = "INSERT INTO cal_act(id_ent_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_ent_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";

        $resultadoInsercionEntregables = mysqli_query($db, $sqlInsercionEntregables);

        //echo $sqlInsercionEntregables;
      }


      //EXAMENES

      $sqlExamenes = "
        SELECT * 
        FROM sub_hor 
        INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
        WHERE id_sub_hor = '$id_sub_hor2'
      ";


      $resultadoExamenes = mysqli_query($db, $sqlExamenes);


      while($filaExamenes = mysqli_fetch_assoc($resultadoExamenes)){
        
        $id_exa_cop = $filaExamenes['id_exa_cop'];
        
        $ini_cal_act = $filaExamenes['ini_exa_cop'];
        $fin_cal_act = $filaExamenes['fin_exa_cop'];

        $sqlInsercionExamenes = "INSERT INTO cal_act(id_exa_cop2, id_alu_ram4, ini_cal_act, fin_cal_act) VALUES('$id_exa_cop', '$id_alu_ram', '$ini_cal_act', '$fin_cal_act')";
        
        $resultadoInsercionExamenes = mysqli_query($db, $sqlInsercionExamenes);

        //echo $sqlInsercionExamenes;

      }
    // 
    }
    

  }

?>