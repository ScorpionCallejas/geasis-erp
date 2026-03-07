<?php

  function mb_strtolower( $string ){
        return strtolower( $string );
  }
  

  function comprimirTextoVariable( $cadena, $longitud ){

    if ( strlen( $cadena ) > $longitud ) {

      return substr( $cadena, 0, $longitud )."...";
    
    } else {

      return $cadena;

    }

  }
  ////////////////////////
  function obtener_diferencia_fechas( $fechaInicio, $fechaFinal ){

		$fechaHoy = date('Y-m-d');

		$diferenciaDias = obtenerDiferenciaFechas( $fechaHoy, $fechaInicio );
		$dias = obtenerDiferenciaFechas( $fechaFinal, $fechaInicio );

		$datos = array();

		if ( $dias == 0 ) {

		$datos['estatus'] = 'Fin curso';
		$datos['porcentaje'] = 100;

		} else {
		
		$estatus = '';

		if ( $dias > 0 ) {
			// ACTIVO

			$datos['estatus'] = 'En curso';
			$datos['porcentaje'] = floor( ( ( $diferenciaDias * 100 ) / $dias ) );
			$porcentajeAvance = $datos['porcentaje']; // Initialize porcentajeAvance here

			if ( $porcentajeAvance < 0 ) {
			$datos['estatus'] = 'Por comenzar';
			$datos['semana'] = 'N/A';
			$datos['porcentaje'] = 0;

			} else if ( $porcentajeAvance > 100 ) {
			$datos['estatus'] = 'Fin curso';
			$datos['porcentaje'] = 100;

			$datos['semana'] = floor($dias / 7); 
			$semana = $datos['semana'] + 1; // Correctly initialize and increment semana here

			} else {

			$datos['semana'] = floor($diferenciaDias / 7); 
			$semana = $datos['semana'] + 1; // Correctly initialize and increment semana here
			
			}

		} else {
			// PENDIENTE
			$datos['estatus'] = 'Por comenzar';
			$datos['porcentaje'] = 0;
			
			$datos['semana'] = 'N/A';

		
		}
		}

		return $datos;
		
	}
       
       
  function obtener_porcentaje_generacion_alumno( $id_alu ){
    require('../includes/conexion.php');
    
    $sql = "
      SELECT ABS ( ( week(curdate())-week(ini_gen) )/week(fin_gen) ) AS porcentage FROM generacion INNER JOIN alu_ram ON generacion.id_ram5 = alu_ram.id_ram3 WHERE id_alu1 = '$id_alu' limit 1
    ";

    $resultado = mysqli_query( $db, $sql );
    
    return mysqli_fetch_assoc( $resultado )['porcentage'];


  }
  /////////////////// 

  function obtener_validacion_alumno_encuesta( $id_alu, $cadena, $plantel ){
    require('../includes/conexion.php');

    $sqlEncuesta = "
      SELECT *
      FROM encuesta
      WHERE ( id_cad5 = '$cadena' || id_pla7 = '$plantel' )  AND est_enc = 'Activo' AND obj_enc = 1
    ";

    // echo $sqlEncuesta;

    $datos_encuesta = obtener_datos_consulta( $db, $sqlEncuesta );
    if ( $datos_encuesta['total'] > 0 ) {
      
      $resultadoEncuesta = mysqli_query( $db, $sqlEncuesta );
      while( $filaEncuesta = mysqli_fetch_assoc( $resultadoEncuesta ) ){
        //
          $id_enc = $filaEncuesta['id_enc'];

          $sql = "
            SELECT *
            from encuesta 
            inner join encuesta_alumno on encuesta.id_enc = encuesta_alumno.id_enc3 
            WHERE id_alu23 = '$id_alu' AND id_enc3 = '$id_enc'
          ";

		//   echo $sql;


          $existencia_alumno_encuesta = obtener_datos_consulta( $db, $sql )['total'];

          if ( $existencia_alumno_encuesta == 0 ) {
            
            $sql_alumno = "
              SELECT id_alu, ini_gen, fin_gen
              FROM vista_alumnos
              WHERE id_alu = '$id_alu'
            ";

            $datos_alumno = obtener_datos_consulta( $db, $sql_alumno )['datos'];

            $porcentaje = obtener_diferencia_fechas( $datos_alumno['ini_gen'], $datos_alumno['fin_gen'] )['porcentaje'];

            if ( $datos_encuesta['datos']['tie_enc'] == 'Final' ) {
            
              $tie_enc = 70;
            
            } else if ( $datos_encuesta['datos']['tie_enc'] == 'Mitad' ){
            
              $tie_enc = 40;
            
            } else if ( $datos_encuesta['datos']['tie_enc'] == 'Inicio' ) {
              
              $tie_enc = 5;

            }

            //echo " %alu ".$porcentaje." - aplic. encuesta: ".$tie_enc;

            if ( $porcentaje > $tie_enc   ) {
              
              return $id_enc;

            }


          }
          // return $existencia_alumno_encuesta;
        //
      }

      

    }

  }

  // BUSCA SI EXISTE ARCHIVO ASOCIADO A REGISTRO RETORNA 0 O 1
  function obtener_estatus_tarea_server( $id_tar ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM tarea
      WHERE id_tar = '$id_tar'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    if ( $fila['doc_tar'] != NULL ) {

      $archivo = '../../uploads/'.$fila['doc_tar'];
      $existencia = file_exists( $archivo );
      
    }

    if ( $existencia == 1 ) {
        
      return '1';
   
    } else {
   
      return '0';
    
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

  // BUSCA REGISTROS DE TAREA SIN ARCHIVO, SI ASI ES BORRA EL REGISTRO
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

  // BUSCA REGISTROS DE TAREA SIN ARCHIVO, SI ASI ES BORRA EL REGISTRO
  function obtener_existencia_tarea_server( $id_tar, $id_alu_ram, $id_ent_cop ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM tarea
      WHERE id_tar = '$id_tar'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );


    if ( $fila['doc_tar'] != NULL ) {

      $archivo = '../../uploads/'.$fila['doc_tar'];
      $existencia = file_exists( $archivo );
     

    }

    if ( $existencia == 1 ) {
      // echo "archivo existe";       
      return '';
   
    } else {
    
      // echo "archivo NO existe";
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

      // echo $sqlUpdate;

      $resultadoUpdate = mysqli_query( $db, $sqlUpdate );

      if ( !$resultadoUpdate ) {
        return $sqlUpdate;
      }

      // FIN UPDATE


      $sqlDelete = "
        DELETE FROM tarea WHERE id_tar = '$id_tar'
      ";

      $resultadoDelete = mysqli_query( $db, $sqlDelete );



      if ( !$resultadoDelete ) {
        
        return $sqlDelete;
        // echo "error en el delete";
      } else {

        // echo "exito en el delete";
        return 'Error';

      }
   
    }

  }

  // FUNCIONES DE MENSAJERIA
  function obtenerValidacionFotoUsuarioServer( $foto ){

    if( ( $foto == NULL ) ){ 
    
      return '../img/usuario2.jpg';
    
    } else if( ( file_exists( '../uploads/'.$foto ) != 1 ) ){ 
      
      return '../img/usuario2.jpg'; 
      
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

  /*FUNCION PARA SABER SI EXISTE UNA SALA ENTRE 2 USUARIOS*/
  // RECIBE 2 id's y 2 tipos
  //RETORNA UN 'Falso' EN CASO DE NO EXISTIR LA SALA, O UN ENTERO EN CASO DE EXISTIR
  function obtener_existencia_sala( $id1, $tipo1, $id2, $tipo2 ){
    require('../includes/conexion.php');

    $sql = "
      
      SELECT *
      FROM usuario_sala
      INNER JOIN sala ON sala.id_sal = usuario_sala.id_sal6
      WHERE ( usu_usu_sal = '$id1' AND tip_usu_sal = '$tipo1' ) AND ( id_sub_hor6 IS NULL ) 
    
    ";

    // echo $sql;

    $resultado = mysqli_query( $db, $sql );

    $total = obtener_datos_consulta( $db, $sql )['total'];

    if ( $total == 0 ) {

      // echo 'aqui';
      
      $id_sal_aux = 'Falso';
    
    } else {

      // echo 'entonces aca';
      $id_sal_aux = '';

      while( $fila = mysqli_fetch_assoc( $resultado ) ){

        $id_sal = $fila['id_sal6'];

        $sql4 = "
          SELECT *
          FROM usuario_sala
          WHERE id_sal6 = '$id_sal'
        ";

        // echo '4'.$sql4;

        $total3 = obtener_datos_consulta( $db, $sql4 )['total'];

        if ( $total3 == 2 ) {
          
          // 
          $sql3 = "

            SELECT *
            FROM usuario_sala
            WHERE ( id_sal6 = '$id_sal' ) AND 
            ( ( usu_usu_sal = '$id2' AND tip_usu_sal = '$tipo2' ) OR 
            ( usu_usu_sal = '$id1' AND tip_usu_sal = '$tipo1' )  )

          ";

          // echo '3'.$sql3;
          
          $total2 = obtener_datos_consulta( $db, $sql3 )['total'];

          if ( $total2 == 1 ) {

            $id_sal_aux = 'Falso';
          
          } else {
            $datos = obtener_datos_consulta( $db, $sql3 )['datos'];
            $id_sal_aux = $datos['id_sal6'];
            
          }
          // 
        } else {
          
          $id_sal_aux = 'Falso';
        
        }

      }

      

    }
    
    return $id_sal_aux;

  }

  function obtener_existencia_sala_server( $id1, $tipo1, $id2, $tipo2 ){
    require('../../includes/conexion.php');

    $sql = "
      
      SELECT *
      FROM usuario_sala
      INNER JOIN sala ON sala.id_sal = usuario_sala.id_sal6
      WHERE ( usu_usu_sal = '$id1' AND tip_usu_sal = '$tipo1' ) AND ( id_sub_hor6 IS NULL ) 
    
    ";

    // echo $sql;

    $resultado = mysqli_query( $db, $sql );

    $total = obtener_datos_consulta( $db, $sql )['total'];

    if ( $total == 0 ) {

      // echo 'aqui';
      
      $id_sal_aux = 'Falso';
    
    } else {

      // echo 'entonces aca';
      $id_sal_aux = '';

      while( $fila = mysqli_fetch_assoc( $resultado ) ){

        $id_sal = $fila['id_sal6'];

        $sql4 = "
          SELECT *
          FROM usuario_sala
          WHERE id_sal6 = '$id_sal'
        ";

        // echo '4'.$sql4;

        $total3 = obtener_datos_consulta( $db, $sql4 )['total'];

        if ( $total3 == 2 ) {
          
          // 
          $sql3 = "

            SELECT *
            FROM usuario_sala
            WHERE ( id_sal6 = '$id_sal' ) AND 
            ( ( usu_usu_sal = '$id2' AND tip_usu_sal = '$tipo2' ) OR 
            ( usu_usu_sal = '$id1' AND tip_usu_sal = '$tipo1' )  )

          ";

          // echo '3'.$sql3;
          
          $total2 = obtener_datos_consulta( $db, $sql3 )['total'];

          if ( $total2 == 1 ) {

            $id_sal_aux = 'Falso';
          
          } else {
            $datos = obtener_datos_consulta( $db, $sql3 )['datos'];
            $id_sal_aux = $datos['id_sal6'];
            
          }
          // 
        } else {
          
          $id_sal_aux = 'Falso';
        
        }

      }

      

    }
    
    return $id_sal_aux;

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


  function horaFormateadaCompacta2($fecha){
    
    $hora = date("h:i A", strtotime($fecha));

    return $hora;
  
  }
  
  // FIN FUNCIONES DE MENSAJERIA

  function obtenerTotalNotificacionesTrabajosEspeciales( $id_alu_ram ){
    require('../includes/conexion.php');

    $fechaHoy = date( 'Y-m-d' );

    $sql = "
      SELECT *
      FROM proyecto_alu_ram
      INNER JOIN proyecto ON proyecto.id_pro = proyecto_alu_ram.id_pro1
      WHERE ( id_alu_ram15 = '$id_alu_ram' ) AND ( ini_pro_alu_ram <= '$fechaHoy' ) AND ( fec_pro_alu_ram IS NULL )
      ORDER BY id_pro DESC
    ";

    $resultadoTotal = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultadoTotal );

    return $total;

  }

  function obtenerEstatusProyectoAlumnoServer( $id_pro_alu_ram ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT *
      FROM proyecto_alu_ram
      WHERE id_pro_alu_ram = '$id_pro_alu_ram'
    ";

    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $fechaHoy = date( 'Y-m-d' );

    if ( ( $fila['pun_pro_alu_ram'] != null ) && ( $fila['fec_pro_alu_ram'] != null ) ) {
      
      return 'Entregada';

    } else {

      if ( $fechaHoy < $fila['ini_pro_alu_ram'] ) {
      
        return 'Pendiente';

      } else if ( ( $fechaHoy >= $fila['ini_pro_alu_ram'] ) && ( $fechaHoy <= $fila['fin_pro_alu_ram'] ) ) {

        return 'Activa';

      } else if ( $fechaHoy > $fila['fin_pro_alu_ram'] ) {

        return 'Vencida';

      }

    }
    



    
  }
  
  function fechaFormateadaCompacta2($fecha){
    
    $dia = date("d", strtotime($fecha));
    $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
    $annio = date("Y", strtotime($fecha));


    return $dia."/".$mes."/".$annio;
  
  }


  function obtenerAdeudoActividades( $id_alu ){
    require('../includes/conexion.php');

    $sql = "
      SELECT *
      FROM alu_ram
      WHERE id_alu1 = '$id_alu'
    ";

    $resultado = mysqli_query( $db, $sql );

    $actividadesAdeudo = 0;
    while( $fila = mysqli_fetch_assoc( $resultado ) ){

      $id_alu_ram = $fila['id_alu_ram'];

      $actividadesAdeudo = obtenerTotalActividadesVencidas( $id_alu_ram );

    }


    // echo $actividadesAdeudo;

    if ( $actividadesAdeudo >= 14 ) {
      
      $sqlDesactivarCuenta = "
        UPDATE alumno SET est_alu = 'Inactivo' WHERE id_alu = '$id_alu'
      ";

      $resultadoDesactivarCuenta = mysqli_query( $db, $sqlDesactivarCuenta );

      if ( !$resultadoDesactivarCuenta ) {
        echo $sqlDesactivarCuenta;  
      }


    
    }

    // return $actividadesAdeudo;


  }


  function obtenerTotalActividadesVencidas( $id_alu_ram ){
    require('../includes/conexion.php');

    $fechaHoy = date('Y-m-d');

    $sql = "
      SELECT *
      FROM cal_act
      WHERE id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL 
    ";

    //echo $sql;
    $resultado = mysqli_query( $db, $sql );

    if( $resultado ){

      $contador = 0;
      while ( $fila = mysqli_fetch_assoc( $resultado ) ) {

        

        if ( $fila['id_ent_cop2'] != NULL ) {
          
          $id_ent_cop = $fila['id_ent_cop2'];

          $sqlCopia = "
            SELECT *
            FROM entregable_copia
            WHERE id_ent_cop = '$id_ent_cop'
          ";

          $resultadoCopia = mysqli_query( $db, $sqlCopia );

          if ( $resultadoCopia ) {
            
            $filaCopia = mysqli_fetch_assoc( $resultadoCopia );
            $fin_ent_cop = $filaCopia['fin_ent_cop'];

            if ( $fechaHoy > $fin_ent_cop ) {
              $contador++;
            }

          } else {
            echo $sqlCopia;
          }

        } else if ( $fila['id_for_cop2'] != NULL ) {
          
          $id_for_cop = $fila['id_for_cop2'];

          $sqlCopia = "
            SELECT *
            FROM foro_copia
            WHERE id_for_cop = '$id_for_cop'
          ";

          $resultadoCopia = mysqli_query( $db, $sqlCopia );

          if ( $resultadoCopia ) {
            
            $filaCopia = mysqli_fetch_assoc( $resultadoCopia );
            $fin_for_cop = $filaCopia['fin_for_cop'];

            if ( $fechaHoy > $fin_for_cop ) {
              $contador++;
            }

          } else {
            echo $sqlCopia;
          }

        } else if ( $fila['id_exa_cop2'] != NULL ) {
          $id_exa_cop = $fila['id_exa_cop2'];

          $sqlCopia = "
            SELECT *
            FROM examen_copia
            WHERE id_exa_cop = '$id_exa_cop'
          ";

          $resultadoCopia = mysqli_query( $db, $sqlCopia );

          if ( $resultadoCopia ) {
            
            $filaCopia = mysqli_fetch_assoc( $resultadoCopia );
            $fin_exa_cop = $filaCopia['fin_exa_cop'];

            if ( $fechaHoy > $fin_exa_cop ) {
              $contador++;
            }

          } else {
            echo $sqlCopia;
          }
        }


      }


      return $contador;

    }else{
      echo $sql;
    }
  }


  function obtenerHoraFormateadaMensajeria( $fecha ){


    $fechaAux = date("Y-m-d", strtotime($fecha));
    $dia = date("d", strtotime($fecha));
    $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
    $annio = date("Y", strtotime($fecha));



    $hora = date("h:i A", strtotime($fecha));

    // echo "if ( ".$fechaAux." == ".date('Y-m-d')." )";  
    if ( $fechaAux == date('Y-m-d') ) {

      return '<span style="position: absolute; right: -10px;">'.$hora.'</span>';
      
    } else {


      return '<span style="position: absolute; right: -10px;">'.$dia.'/'.$mes.'/'.$annio.' </span>';
    
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
        FROM notificacion_mensaje
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

    $formato = explode( ".", $archivo );

    return end(  $formato );
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

  
  function formatearDinero( $dinero ){

    return "$".number_format(  $dinero,  0, '.', ',');
  
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
  
  function obtenerTotalNotificacionesBloqueGrupo( $id_alu_ram, $id_sub_hor, $id_blo ){
    require( '../includes/conexion.php' );
    $fechaHoy = date('Y-m-d');

    $sql = "
            SELECT fec_cal_act AS fecha, nom_for AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_for_cop AS identificador_copia, fot_alu AS foto, tip_for AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor2 = '$id_sub_hor' ) AND ( id_blo4 = '$id_blo' )
            GROUP BY identificador_copia
            UNION
            SELECT fec_cal_act AS fecha, nom_ent AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_ent_cop AS identificador_copia, fot_alu AS foto, tip_ent AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor3 = '$id_sub_hor' ) AND ( id_blo5 = '$id_blo' )
            GROUP BY identificador_copia
            UNION
            SELECT fec_cal_act AS fecha, nom_exa AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_exa_cop AS identificador_copia, fot_alu AS foto, tip_exa AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor4 = '$id_sub_hor' ) AND ( id_blo6 = '$id_blo' )
            GROUP BY identificador_copia
            ORDER BY fecha DESC
        "; 

        $resultado = mysqli_query( $db, $sql );

        $total = mysqli_num_rows( $resultado );

        return $total;

  }

  function obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ){
    require( '../includes/conexion.php' );
    $fechaHoy = date('Y-m-d');

    $sql = "

      SELECT fec_cal_act AS fecha, nom_for AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_for_cop AS identificador_copia, fot_alu AS foto, tip_for AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, ini_cal_act AS inicio, fin_cal_act AS fin
      FROM cal_act
      INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
      INNER JOIN foro ON foro.id_for = foro_copia.id_for1
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor2 = '$id_sub_hor' )
      GROUP BY identificador_copia
      UNION
      SELECT fec_cal_act AS fecha, nom_ent AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_ent_cop AS identificador_copia, fot_alu AS foto, tip_ent AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, ini_cal_act AS inicio, fin_cal_act AS fin
      FROM cal_act
      INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
      INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor3 = '$id_sub_hor' )
      GROUP BY identificador_copia
      UNION
      SELECT fec_cal_act AS fecha, nom_exa AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_exa_cop AS identificador_copia, fot_alu AS foto, tip_exa AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, ini_cal_act AS inicio, fin_cal_act AS fin
      FROM cal_act
      INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
      INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor4 = '$id_sub_hor' )
      GROUP BY identificador_copia
      ORDER BY fecha DESC
  ";

        $resultado = mysqli_query( $db, $sql );

        $total = mysqli_num_rows( $resultado );

        return $total;
  }


  function obtenerTotalNotificacionesPrograma( $id_alu_ram ){
    require( '../includes/conexion.php' );

    $fechaHoy = date('Y-m-d');

    $sql = "
      SELECT id_for_cop AS identificador_copia, nom_for AS actividad, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo_actividad, id_alu_ram AS id_alu_ram, id_for_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
      FROM cal_act
      INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
      INNER JOIN foro ON foro.id_for = foro_copia.id_for1
      INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
      INNER JOIN materia ON materia.id_mat = bloque.id_mat6
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram = '$id_alu_ram' AND fec_cal_act IS NULL
          UNION
      SELECT id_ent_cop AS identificador_copia, nom_ent AS actividad, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo_actividad, id_alu_ram AS id_alu_ram, id_ent_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
      FROM cal_act
      INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
      INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
      INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
      INNER JOIN materia ON materia.id_mat = bloque.id_mat6
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram = '$id_alu_ram' AND fec_cal_act IS NULL
      UNION
          SELECT id_exa_cop AS identificador_copia, nom_exa AS actividad, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo_actividad, id_alu_ram AS id_alu_ram, id_exa_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
      FROM cal_act
      INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
      INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
      INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
      INNER JOIN materia ON materia.id_mat = bloque.id_mat6
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram = '$id_alu_ram' AND fec_cal_act IS NULL
      ORDER BY inicio
    "; 

    $resultado = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultado );

    return $total;
  }

  function obtenerTotalNotificacionesHamburgesa( $id ){
    require( '../includes/conexion.php' );

    $fechaHoy = date('Y-m-d');

    $sql = "
      SELECT id_for_cop AS identificador_copia, nom_for AS actividad, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo_actividad, id_alu_ram AS id_alu_ram, id_for_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
      FROM cal_act
      INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
      INNER JOIN foro ON foro.id_for = foro_copia.id_for1
      INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
      INNER JOIN materia ON materia.id_mat = bloque.id_mat6
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
          UNION
      SELECT id_ent_cop AS identificador_copia, nom_ent AS actividad, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo_actividad, id_alu_ram AS id_alu_ram, id_ent_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
      FROM cal_act
      INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
      INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
      INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
      INNER JOIN materia ON materia.id_mat = bloque.id_mat6
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
      UNION
          SELECT id_exa_cop AS identificador_copia, nom_exa AS actividad, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo_actividad, id_alu_ram AS id_alu_ram, id_exa_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
      FROM cal_act
      INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
      INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
      INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
      INNER JOIN materia ON materia.id_mat = bloque.id_mat6
      INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
      INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
      INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
      WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
      ORDER BY inicio
    "; 

    $resultado = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultado );

    // NOTIFICACIONES DE TRABAJOS ESPECIALES
    $sqlAlumnos = "
      SELECT *
      FROM alu_ram
      WHERE id_alu1 = '$id'
    ";

    $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );

    $totalProyectos = 0;
    while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){

      $id_alu_ram = $filaAlumnos['id_alu_ram'];

      $totalProyectos = $totalProyectos + obtenerTotalNotificacionesTrabajosEspeciales( $id_alu_ram );

    }



    return $total + $totalProyectos;

  }


  function obtenerTotalNotificacionesGrupoServer( $id_alu_ram, $id_sub_hor ){
    require( '../../includes/conexion.php' );
    $fechaHoy = date( 'Y-m-d' );

    $sql = "
            SELECT id_for_cop AS identificador_copia, nom_for AS actividad, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo_actividad, id_alu_ram AS id_alu_ram, id_for_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
            INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor2 = '$id_sub_hor' )
                UNION
            SELECT id_ent_cop AS identificador_copia, nom_ent AS actividad, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo_actividad, id_alu_ram AS id_alu_ram, id_ent_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
            INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor3 = '$id_sub_hor' )
            UNION
                SELECT id_exa_cop AS identificador_copia, nom_exa AS actividad, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo_actividad, id_alu_ram AS id_alu_ram, id_exa_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
            INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL AND ( id_sub_hor4 = '$id_sub_hor' )
            ORDER BY inicio
        "; 

        $resultado = mysqli_query( $db, $sql );

        $total = mysqli_num_rows( $resultado );

        return $total;
  }


  function obtenerTotalNotificacionesProgramaServer( $id_alu_ram ){
    require( '../../includes/conexion.php' );
    $fechaHoy = date( 'Y-m-d' );

    $sql = "
            SELECT id_for_cop AS identificador_copia, nom_for AS actividad, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo_actividad, id_alu_ram AS id_alu_ram, id_for_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
            INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL
                UNION
            SELECT id_ent_cop AS identificador_copia, nom_ent AS actividad, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo_actividad, id_alu_ram AS id_alu_ram, id_ent_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
            INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL
            UNION
                SELECT id_exa_cop AS identificador_copia, nom_exa AS actividad, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo_actividad, id_alu_ram AS id_alu_ram, id_exa_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
            FROM cal_act
            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
            INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
            INNER JOIN materia ON materia.id_mat = bloque.id_mat6
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL
            ORDER BY inicio
        "; 

        $resultado = mysqli_query( $db, $sql );

        $total = mysqli_num_rows( $resultado );

        return $total;
  }

  function obtenerDatosGrupalesServer( $id_sub_hor ){

    require('../../includes/conexion.php');

    $sql = "
          SELECT *
          FROM sub_hor
          INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
          INNER JOIN profesor ON profesor.id_pro  = sub_hor.id_pro1
          INNER JOIN rama ON rama.id_ram = materia.id_ram2
          INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
          WHERE id_sub_hor = '$id_sub_hor'
      ";

      $resultado = mysqli_query( $db, $sql );

      if ( $resultado ) {
          
          // echo $sql;
          $fila = mysqli_fetch_assoc( $resultado );

      return $fila;

    }
  }





  function obtenerEstatusActividadServer2( $fec_cal_act, $fin_actividad, $pun_cal_act ){
    $fechaHoy = date( 'Y-m-d' );

    if ( $fec_cal_act == NULL ) {
      
      if ( ( $fechaHoy > $fin_actividad ) ) {
      
        return 'Vencida';
      
      } else {
      
        return 'Pendiente';
      
      }

    }else{
      
      if ( $pun_cal_act != NULL ) {

        return 'Calificada';
      
      } else {
      
        return 'Realizada'; 
      
      }
      
    }

  }

  
  function obtenerEstatusActividadServer( $fec_cal_act, $inicio_actividad, $fin_actividad, $pun_cal_act ){
    $fechaHoy = date( 'Y-m-d' );

    if ( $fec_cal_act == NULL ) {
      

      if ( $fechaHoy < $inicio_actividad ) {
        
        return 'Por entregar';

      } else {

        if ( $fechaHoy > $fin_actividad ) {
      
          return 'Vencida';
        
        } else {
        
          return 'Pendiente';
        
        }

      }
      

    }else{
      
      if ( $pun_cal_act != NULL ) {

        return 'Calificada';
      
      } else {
      
        return 'Realizada'; 
      
      }
      
    }

  }

  function obtenerBadgeEstatusActividadServer( $fec_cal_act, $fin_actividad, $pun_cal_act ){
    $fechaHoy = date( 'Y-m-d' );

    if ( $fec_cal_act == NULL ) {
      
      if ( $fechaHoy > $fin_actividad ) {
      
        return '<span class="badge badge-pill badge-danger font-weight-normal">Vencida</span>';
      
      } else {
      
        return '<span class="badge badge-pill badge-warning font-weight-normal">Pendiente</span>';
      
      }

    }else{
      
      if ( $pun_cal_act != NULL ) {

        return '<span class="badge badge-pill badge-success font-weight-normal">Calificada</span>';
      
      } else {
      
        return '<span class="badge badge-pill badge-info font-weight-normal">Realizada</span>'; 
      
      }
      
    }

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


  function contadorRecursosPracticosServer( $id_blo, $id_sub_hor, $id_alu_ram ){
    require('../../includes/conexion.php');

    $sql = "
      SELECT id_for AS identificador, id_for_cop AS identificador_copia, nom_for AS titulo, des_for AS descripcion, fec_for AS fecha, tip_for AS tipo, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, fec_cal_act AS fec_cal_act, pun_cal_act AS pun_cal_act
            FROM cal_act
            INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
            WHERE id_sub_hor2 = '$id_sub_hor' AND id_blo4 = '$id_blo' AND id_alu_ram4 = '$id_alu_ram'
        UNION
        SELECT id_ent AS identificador, id_ent_cop AS identificador_copia, nom_ent AS titulo, des_ent AS descripcion, fec_ent AS fecha, tip_ent AS tipo, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, fec_cal_act AS fec_cal_act, pun_cal_act AS pun_cal_act
        FROM cal_act
            INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
        WHERE id_sub_hor3 = '$id_sub_hor' AND id_blo5 = '$id_blo' AND id_alu_ram4 = '$id_alu_ram'
        UNION
        SELECT id_exa AS identificador, id_exa_cop AS identificador_copia, nom_exa AS titulo, des_exa AS descripcion, fec_exa AS fecha, tip_exa AS tipo, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, fec_cal_act AS fec_cal_act, pun_cal_act AS pun_cal_act
        FROM cal_act
            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
        WHERE id_sub_hor4 = '$id_sub_hor' AND id_blo6 = '$id_blo' AND id_alu_ram4 = '$id_alu_ram'
            ORDER BY fec_cal_act ASC
    ";

    $resultado = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultado );
    //$total = $sql;
    return $total;
  }


  function obtenerPorcentajeParticipacionActividadServer( $tipo, $identificador ){
    require( '../../includes/conexion.php' );

    $datosAlumnos = array();

    if ( $tipo == 'Foro') {

      $sqlForosAlumnosParticipantes = "
        SELECT * 
        FROM cal_act 
        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
        WHERE id_for_cop2 = '$identificador' AND fec_cal_act IS NOT NULL
        GROUP BY id_alu_ram4

      ";

      $resultadoAlumnosParticipantes = mysqli_query($db, $sqlForosAlumnosParticipantes);

      $alumnosParticipantes = mysqli_num_rows($resultadoAlumnosParticipantes);

      $sqlForosAlumnos = "
        SELECT * 
        FROM cal_act 
        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
        WHERE id_for_cop2 = '$identificador'
        GROUP BY id_alu_ram4

      ";

      $resultadoAlumnos = mysqli_query($db, $sqlForosAlumnos);

      $alumnos = mysqli_num_rows($resultadoAlumnos);


      $datosAlumnos['alumnos_responsables'] = $alumnosParticipantes;
      $datosAlumnos['alumnos_totales'] = $alumnos;


        
        if( $alumnos > 0 ) {

        $datosAlumnos['alumnos_porcentaje'] = round(100*($alumnosParticipantes/$alumnos), 2)."%";
      
      } else {
        $datosAlumnos['alumnos_porcentaje'] =  "0,00%";
      }
      

    }else if( $tipo == 'Entregable'){

      $sqlEntregablesAlumnosParticipantes = "
        SELECT * 
        FROM cal_act 
        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
        WHERE id_ent_cop2 = '$identificador' AND fec_cal_act IS NOT NULL
        GROUP BY id_alu_ram4

      ";

      $resultadoAlumnosParticipantes = mysqli_query($db, $sqlEntregablesAlumnosParticipantes);

      $alumnosParticipantes = mysqli_num_rows($resultadoAlumnosParticipantes);



      $sqlEntregablesAlumnos = "
        SELECT * 
        FROM cal_act 
        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
        WHERE id_ent_cop2 = '$identificador'
        GROUP BY id_alu_ram4

      ";

      $resultadoAlumnos = mysqli_query($db, $sqlEntregablesAlumnos);

      $alumnos = mysqli_num_rows($resultadoAlumnos);
      
      $datosAlumnos['alumnos_responsables'] = $alumnosParticipantes;
      $datosAlumnos['alumnos_totales'] = $alumnos;


        
        if( $alumnos > 0 ) {

        $datosAlumnos['alumnos_porcentaje'] = round(100*($alumnosParticipantes/$alumnos), 2)."%";
      
      } else {
        $datosAlumnos['alumnos_porcentaje'] =  "0,00%";
      }
      

    }else if( $tipo == 'Examen'){
      $sqlExamenesAlumnosParticipantes = "
        SELECT * 
        FROM cal_act 
        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
        WHERE id_exa_cop2 = '$identificador' AND fec_cal_act IS NOT NULL
        GROUP BY id_alu_ram4

      ";

      $resultadoAlumnosParticipantes = mysqli_query($db, $sqlExamenesAlumnosParticipantes);

      $alumnosParticipantes = mysqli_num_rows($resultadoAlumnosParticipantes);



      $sqlExamenesAlumnos = "
        SELECT * 
        FROM cal_act 
        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
        WHERE id_exa_cop2 = '$identificador'
        GROUP BY id_alu_ram4

      ";

      $resultadoAlumnos = mysqli_query($db, $sqlExamenesAlumnos);

      $alumnos = mysqli_num_rows($resultadoAlumnos);

      $datosAlumnos['alumnos_responsables'] = $alumnosParticipantes;
      $datosAlumnos['alumnos_totales'] = $alumnos;

        if( $alumnos > 0 ) {

        $datosAlumnos['alumnos_porcentaje'] = round(100*($alumnosParticipantes/$alumnos), 2)."%";
      
      } else {
      
        $datosAlumnos['alumnos_porcentaje'] =  "0,00%";
      
      }
      

    }


    return $datosAlumnos;

          
  }


  function obtenerTotalNotificacionesActividadServer( $identificador_copia, $id_alu_ram ){

    require( '../../includes/conexion.php' );

    $fechaHoy = date( 'Y-m-d' );


    $sql = "
            SELECT fec_cal_act AS fecha, nom_for AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_for_cop AS identificador_copia, fot_alu AS foto, tip_for AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, ini_cal_act AS inicio, fin_cal_act AS fin
            FROM cal_act
            INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
            INNER JOIN foro ON foro.id_for = foro_copia.id_for1
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE ( ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' ) AND (fec_cal_act IS NULL ) AND ( id_alu_ram4 = '$id_alu_ram' ) AND ( id_for_cop = '$identificador_copia' )
            GROUP BY identificador_copia
            UNION
            SELECT fec_cal_act AS fecha, nom_ent AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_ent_cop AS identificador_copia, fot_alu AS foto, tip_ent AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, ini_cal_act AS inicio, fin_cal_act AS fin
            FROM cal_act
            INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE ( ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' ) AND (fec_cal_act IS NULL ) AND ( id_alu_ram4 = '$id_alu_ram' ) AND ( id_ent_cop = '$identificador_copia' )
            GROUP BY identificador_copia
            UNION
            SELECT fec_cal_act AS fecha, nom_exa AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_exa_cop AS identificador_copia, fot_alu AS foto, tip_exa AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, ini_cal_act AS inicio, fin_cal_act AS fin
            FROM cal_act
            INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
            INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
            INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
            WHERE ( ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' ) AND (fec_cal_act IS NULL ) AND ( id_alu_ram4 = '$id_alu_ram' ) AND ( id_exa_cop = '$identificador_copia' )
            GROUP BY identificador_copia
            ORDER BY fecha DESC
        "; 

        $resultado = mysqli_query( $db, $sql );

        $total = mysqli_num_rows( $resultado );

        return $total;


  }

  // function cifradoServer($string, $key){
  //      $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
  //      $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM );
  //      mcrypt_generic_init($td, $key, $iv);
  //      $encrypted_data_bin = mcrypt_generic($td, $string);
  //      mcrypt_generic_deinit($td);
  //      mcrypt_module_close($td);
  //      $encrypted_data_hex = bin2hex($iv).bin2hex($encrypted_data_bin);
  //      return $encrypted_data_hex;
  // }

  // function descifradoServer($encrypted_data_hex, $key){
  //   $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
  //   $iv_size_hex = mcrypt_enc_get_iv_size($td)*2;
  //   $iv = pack("H*", substr($encrypted_data_hex, 0, $iv_size_hex));
  //   $encrypted_data_bin = pack("H*", substr($encrypted_data_hex, $iv_size_hex));
  //   mcrypt_generic_init($td, $key, $iv);
  //   $decrypted = mdecrypt_generic($td, $encrypted_data_bin);
  //   mcrypt_generic_deinit($td);
  //   mcrypt_module_close($td);
  //   return $decrypted;
  // }
  

  function obtenerTotalActividadesGrupo( $id_sub_hor, $id_blo ){
    require( '../includes/conexion.php' );

    $sql = "
      SELECT id_for_cop AS id
      FROM foro_copia
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
      INNER JOIN foro ON foro.id_for = foro_copia.id_for1
      WHERE  ( id_blo4 = '$id_blo' ) AND ( id_sub_hor2 = '$id_sub_hor' )
      UNION
      SELECT id_ent_cop AS id
      FROM entregable_copia
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
      INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
      WHERE ( id_blo5 = '$id_blo' ) AND ( id_sub_hor3 = '$id_sub_hor' )
      UNION
      SELECT id_exa_cop AS id
      FROM examen_copia
      INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
      INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
      WHERE ( id_blo6 = '$id_blo' ) AND ( id_sub_hor4 = '$id_sub_hor' )
    ";

    $resultado = mysqli_query( $db, $sql );

    $total = mysqli_num_rows( $resultado );

    return $total;

  }


  function contadorRecursosTeoricosServer( $id_blo ){
    require('../../includes/conexion.php');

    $sqlVideos = "
      SELECT *
      FROM video
      WHERE id_blo1 = '$id_blo'
    ";

    $resultadoVideos = mysqli_query( $db, $sqlVideos );

    $totalVideos = mysqli_num_rows( $resultadoVideos );

    $sqlWikis = "
      SELECT *
      FROM wiki
      WHERE id_blo2 = '$id_blo'
    ";

    $resultadoWikis = mysqli_query( $db, $sqlWikis );

    $totalWikis = mysqli_num_rows( $resultadoWikis );

    $sqlArchivos = "
      SELECT *
      FROM archivo
      WHERE id_blo3 = '$id_blo'
    ";

    $resultadoArchivos = mysqli_query( $db, $sqlArchivos );

    $totalArchivos = mysqli_num_rows( $resultadoArchivos );


    return $totalVideos + $totalWikis + $totalArchivos;   


  }


  function contadorRecursosTeoricos( $id_blo ){
    require('../includes/conexion.php');

    $sqlVideos = "
      SELECT *
      FROM video
      WHERE id_blo1 = '$id_blo'
    ";

    $resultadoVideos = mysqli_query( $db, $sqlVideos );

    $totalVideos = mysqli_num_rows( $resultadoVideos );

    $sqlWikis = "
      SELECT *
      FROM wiki
      WHERE id_blo2 = '$id_blo'
    ";

    $resultadoWikis = mysqli_query( $db, $sqlWikis );

    $totalWikis = mysqli_num_rows( $resultadoWikis );

    $sqlArchivos = "
      SELECT *
      FROM archivo
      WHERE id_blo3 = '$id_blo'
    ";

    $resultadoArchivos = mysqli_query( $db, $sqlArchivos );

    $totalArchivos = mysqli_num_rows( $resultadoArchivos );


    return $totalVideos + $totalWikis + $totalArchivos;   

  }

  function obtenerDiaMes( $fecha ){


    $diaNumero = date("j", strtotime($fecha));
      $mes = substr( getMonth(date("n", strtotime($fecha))), 0, 3 );

      return $diaNumero." ".$mes;
  }
	
	function obtenerEstatusAlumno( $id_alu ) {
		require('../includes/conexion.php');

    	$sql = "
			SELECT *
			FROM alumno
			WHERE id_alu = '$id_alu'
    	";

    	$resultado = mysqli_query( $db, $sql );


    	if ( $resultado ) {
    		
    		$fila = mysqli_fetch_assoc( $resultado );
    		$est_alu = $fila['est_alu'];

    		if ( $est_alu == "Inactivo" ) {
    			
    			return $est_alu;
    		} else {
          return $est_alu;
        }

    	} else {
    		echo $sql;
    	}

		
	}

  function obtener_estatus_general_vista_alumnos( $id_alu ){

    require('../includes/conexion.php');

      $sql = "
        SELECT *
        FROM vista_alumnos
        WHERE id_alu = '$id_alu'
      ";

      $resultado = mysqli_query( $db, $sql );


      if ( $resultado ) {
        
        $fila = mysqli_fetch_assoc( $resultado );
        $est_alu = $fila['estatus_general'];

        return $est_alu;

      } else {
        echo $sql;
      }

  }


  function obtenerEstatus2Alumno( $id_alu ) {
    require('../includes/conexion.php');

      $sql = "
      SELECT *
      FROM alumno
      WHERE id_alu = '$id_alu'
      ";

      $resultado = mysqli_query( $db, $sql );


      if ( $resultado ) {
        
        $fila = mysqli_fetch_assoc( $resultado );
        $est_alu = $fila['est2_alu'];

        return $est_alu;

      } else {
        echo $sql;
      }

    
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


  function fechaHoraFormateadaCompactaServer($fecha){
    
    $dia = date("d", strtotime($fecha));
      $mes = date("m", strtotime($fecha));
      $annio = date("Y", strtotime($fecha));
      $hora = date("h:i A", strtotime($fecha));

      return $dia."/".$mes."/".$annio." ".$hora;
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

  function logServer ( $tip_log, $usr_log, $id_usr_log, $ent_log, $des_log, $id_pla10 ) {

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


	

//FUNCION PARA SABER DIFERENCIA DE DIAS...LO DA COMO UN STRING

	function obtenerDiferenciaDias($fecha1, $fecha2)
    {
    	$date1=date_create($fecha1);
		$date2=date_create($fecha2);
		$diff=date_diff($date1,$date2);
		echo $diff->format("%a dias");
    }


	function muerteCiclos() {
		require('../includes/conexion.php');

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
				$sqlEliminacionCiclos = "
					DELETE FROM ciclo WHERE id_cic = '$id_cic'
				";

				// echo $sqlEliminacionCiclos

				$resultadoEliminacionCiclo = mysqli_query( $db, $sqlEliminacionCiclos );

				if (!$resultadoEliminacionCiclo) {
					echo "error al eliminar el ciclo: ".$filaCiclos['nom_cic'];
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
				return round( $promedio, 2 );
			}
			

		}else {
			echo $sql;
		}

	}



  function obtenerDia( $fecha ){


    $days_dias = array(
      'Monday'=>'Lunes',
      'Tuesday'=>'Martes',
      'Wednesday'=>'Miércoles',
      'Thursday'=>'Jueves',
      'Friday'=>'Viernes',
      'Saturday'=>'Sábado',
      'Sunday'=>'Domingo'
    );

    //lookup dia based on day name
    return $days_dias[date('l', strtotime( $fecha ))];
  }



  function obtenerDiferenciaFechas( $fecha1, $fecha2 ){

    $inicioEntero = strtotime( $fecha1 ) - strtotime( $fecha2 );

    $inicio = round( $inicioEntero / ( 60 * 60 * 24) );

      return $inicio;

  }


  function obtenerFechaGuapa( $fecha ){
    $dia = obtenerDia( $fecha );

    $diaNumero = date("d", strtotime($fecha));
      $mes = getMonth(date("n", strtotime($fecha)));
      $annio = date("Y", strtotime($fecha));


      return $dia." ".$diaNumero." de ".$mes;
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
				return $promedio;
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
			$programa = substr($fila_detalles['gra_ram'], 0, 2);
			$modalidad = substr($fila_detalles['mod_ram'], 0,2);
			$annio_ciclo = date('y');

			$sql_conteo_ciclos = "
				SELECT COUNT(id_cic) AS ciclos 
				FROM ciclo
				WHERE id_ram1 = '$rama';
			";
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


			<div id="MyClockDisplay" class="clock breadcrumb-dn mr-auto"></div>


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


	function fechaHoraFormateadaCompacta($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = getMonth(date("n", strtotime($fecha)));
	  	$annio = date("Y", strtotime($fecha));

	  	$hora = date("h:i A", strtotime($fecha));

	  	return "<span>".$dia." de ".$mes." del ".$annio." ".$hora."</span>";
	}


	function fechaFormateada($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = getMonth(date("n", strtotime($fecha)));
	  	$annio = date("Y", strtotime($fecha));


	  	return $dia." de ".$mes." del ".$annio;
	}



	function fechaFormateadaCompacta($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = date("m", strtotime($fecha));
	  	$annio = date("Y", strtotime($fecha));


	  	return $dia."/".$mes."/".$annio;
	}



 //  function generadorFaltas($id_alu_ram){
 //  	// ALGORITMO DE GENERACION DE INASISTENCIAS

 //  	require('../includes/conexion.php');

	// $sqlAsistencias = "
	// 	SELECT * FROM alu_ram 
	// 	INNER JOIN ciclo ON ciclo.id_ram1 = alu_ram.id_ram3 
	// 	WHERE id_alu_ram = '$id_alu_ram'

	// ";
	// //echo $sqlAsistencias;

	// $resultadoAsistencia = mysqli_query($db, $sqlAsistencias);

	// $filaAsistencias = mysqli_fetch_assoc($resultadoAsistencia);



	// $ini_cic = $filaAsistencias['ini_cic'];
	// $id_cic = $filaAsistencias['id_cic'];


	// ///echo $id_cic;

	// $fechaHoy = date('Y-m-d');

	// //ar_dump($fechaHoy);
	
	// // for ($i= 10; $i <= 16; $i++) { 
	// // 	echo date("l", strtotime('2019-05-'.$i))."<br>";
	// // }
	// //

	// $diff = abs(strtotime($ini_cic) - strtotime($fechaHoy));
	// $years = floor($diff / (365*60*60*24));
	// $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	// $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

	// for ($i = 0; $i < $days; $i++) { 
	// 	$fecha = gmdate('Y-m-d', $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $ini_cic )))."<br>";
	// 	$dia = gmdate('l', $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $ini_cic )))."<br>";
	// 	//echo $fecha;
	// 	//echo date("l", strtotime($fecha))."<br>";

	// 	$sqlCalendario = "
	// 		SELECT * FROM  evento WHERE fec_eve = '$fecha' AND id_cic2 = '$id_cic'
	// 	";

	// 	$resultadoCalendario = mysqli_query($db, $sqlCalendario);

	// 	$filaCalendario = mysqli_fetch_assoc($resultadoCalendario);

	// 	if ($filaCalendario['tip_eve'] == 'Falta') {
	// 		//echo $fecha;
	// 	}else{
	// 		//ESPERABA ASISTENCIA
	// 		// LA FORMA DE DETERMINAR SI ESPERABA ASISTENCIA Y NO EXISTE ES SI EL NUMERO DE REGISTROS PARA ESE DIA ES 0
	// 		$sqlConsultaAsistencia  = "
	// 			SELECT *
	// 			FROM asistencia
	// 			WHERE id_alu_ram3 = '$id_alu_ram' AND fec_asi = '$fecha' 
	// 		";

	// 		//echo $sqlConsultaAsistencia;

	// 		$resultadoConsultaAsistencia = mysqli_query($db, $sqlConsultaAsistencia);

	// 		$totalConsultaAsistencia = mysqli_num_rows($resultadoConsultaAsistencia);

	// 		if ($totalConsultaAsistencia == 0) {
	// 			//HOY ESPERABA ASISTENCIA PERO NO EXISTEN
	// 			//echo $dia;
				
				
	// 			if (strncasecmp ($dia, 'Monday', 6) == 0) {
	// 				//echo "entre lunes";	
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 						FROM alu_ram
	// 				    	INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Lunes' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				//echo $sqlMateriasDia;

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}



	// 			}else if(strncasecmp ($dia, 'Tuesday', 7) == 0){
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 				    	FROM alu_ram
	// 				        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Martes' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}
	// 			}else if(strncasecmp ($dia, 'Wednesday', 9) == 0){
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 				    	FROM alu_ram
	// 				        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Miércoles' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}
	// 			}else if(strncasecmp ($dia, 'Thursday', 8)){
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 				    	FROM alu_ram
	// 				        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Jueves' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}
	// 			}else if(strncasecmp ($dia, 'Friday', 6)){
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 				    	FROM alu_ram
	// 				        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Viernes' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}
	// 			}else if(strncasecmp ($dia, 'Saturday', 8)){
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 				    	FROM alu_ram
	// 				        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Sábado' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}
	// 			}else if(strncasecmp ($dia, 'Sunday', 6)){
	// 				$sqlMateriasDia = "
	// 						SELECT *
	// 				    	FROM alu_ram
	// 				        INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	// 				        INNER JOIN horario ON horario.id_sub_hor1 = alu_hor.id_sub_hor5
	// 				        INNER JOIN sub_hor ON sub_hor.id_sub_hor = horario.id_sub_hor1
	// 				        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	// 				    	WHERE id_alu_ram = '$id_alu_ram' AND dia_hor = 'Domingo' 
	// 				        ORDER BY id_sub_hor ASC

	// 				";

	// 				$resultadoMateriasDia = mysqli_query($db, $sqlMateriasDia);

	// 				while($filaMateriasDia = mysqli_fetch_assoc($resultadoMateriasDia)){
	// 					$id_mat = $filaMateriasDia['id_mat'];


	// 					$sqlInsercionAsistencia = "INSERT INTO asistencia(tip_asi, fec_asi, id_mat5, id_alu_ram3) VALUES('Falta', '$fecha', '$id_mat', '$id_alu_ram')";

	// 					$resultadoInsercionAsistencia = mysqli_query($db, $sqlInsercionAsistencia);
	// 				}
	// 			}
	// 		}
	// 	}
	// }
 //  }



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
			WHERE id_alu_ram1 = '$id_alu_ram'
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
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
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
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
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
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
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
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
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

					$tip2_pag = $filaCobrosCiclo['tip2_pag_cic'];

					$car_pag = $filaCobrosCiclo['car_pag_cic'];

					$id_alu_ram10 = $id_alu_ram;

					$des_pag = $filaCobrosCiclo['des_pag_cic'];

					


					$sqlInsercionPago = "
					INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10) 
					VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10')
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
									
									if ( $tip_abo_pag == 'Saldo_Digital' ) {
						
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

										if ( $tip_abo_pag == 'Saldo_Digital' ) {
						
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

										if ( $tip_abo_pag == 'Saldo_Digital' ) {
						
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
                //      $con_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

                //      $men_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['pro_pag'])."!";

                    // $fec_his_pag = $fechaHoy;

                    // $res_his_pag = 'Sistema';

                    // $est_his_pag = 'Pendiente';

                    // $tip_his_pag = "Descuento";

                    // $med_his_pag = "Whatsapp";


                    // // INSERCION HISTORIAL
                    // $sqlInsercionHistorialWhatsapp = "
                    //  INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
                    //  VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
                    // ";



                    // $resultadoInsercionHistorialWhatsapp = mysqli_query( $db, $sqlInsercionHistorialWhatsapp );

                    // if ( !$resultadoInsercionHistorialWhatsapp ) {
                    //  echo $sqlInsercionHistorialWhatsapp;
                    // }else {
                      
                    //  $client->messages->create('whatsapp:+525518292351', // to
                    //         array(
                    //             'from' => 'whatsapp:+14155238886',
                    //             'body' => $men_his_pag
                    //         )
                    //  );

                    // }
                  // FIN IF WHATSAPP
                  }

                  if ( ($smsPlantel == 'Activo') && ($tel_alu != NULL) ) {
                  // IF SMS

                    //echo "Envio SMS - Caso Pasado ";
                //      $con_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['pro_pag']);

                //      $men_his_pag = "Descuento de $".$descuento." a los $".$filaPago['mon_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['pro_pag'])."!";

                    // $fec_his_pag = $fechaHoy;

                    // $res_his_pag = 'Sistema';

                    // $est_his_pag = 'Pendiente';

                    // $tip_his_pag = "Descuento";

                    // $med_his_pag = "SMS";


                    // // INSERCION HISTORIAL
                    // $sqlInsercionHistorialSms = "
                    //  INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
                    //  VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
                    // ";



                    // $resultadoInsercionHistorialSms = mysqli_query( $db, $sqlInsercionHistorialSms );

                    // if ( !$resultadoInsercionHistorialSms ) {
                    //  echo $sqlInsercionHistorialSms;
                    // }else {
                    //  $client->messages->create(
                    //      // the number you'd like to send the message to
                    //      '+525518292351',
                    //      array(
                    //          // A Twilio phone number you purchased at twilio.com/console
                    //          'from' => '+13343261337',
                    //          // the body of the text message you'd like to send
                    //          'body' => $men_his_pag
                    //      )
                    //  );
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
                  //      if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
                  //        $descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

                  //        $con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
                  //      }else{

                  //        $con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
                  //      }

                        
                  //      $men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

                      // $fec_his_pag = $fechaHoy;

                      // $res_his_pag = 'Sistema';

                      // $est_his_pag = 'Pendiente';

                      // $tip_his_pag = "N/A";

                      // $med_his_pag = "Whatsapp";


                      // // INSERCION HISTORIAL
                      // $sqlInsercionHistorialWhatsapp = "
                      //  INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
                      //  VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
                      // ";



                      // $resultadoInsercionHistorialWhatsapp = mysqli_query( $db, $sqlInsercionHistorialWhatsapp );

                      // if ( !$resultadoInsercionHistorialWhatsapp ) {
                      //  echo $sqlInsercionHistorialWhatsapp;
                      // }else {
                        
                      //  $client->messages->create('whatsapp:+525518292351', // to
                      //         array(
                      //             'from' => 'whatsapp:+14155238886',
                      //             'body' => $men_his_pag
                      //         )
                      //  );

                      // }
                    // FIN IF WHATSAPP
                    }

                    if ( ($smsPlantel == 'Activo') && ($tel_alu != NULL) ) {
                    // IF SMS
                      
                        
                        // VALIDADOR SI HUBO DESCUENTO PREVIO
                  //      if ( $filaPago['mon_pag'] < $filaPago['mon_ori_pag'] ) {
                  //        $descuento = $filaPago['mon_ori_pag'] - $filaPago['mon_pag'];

                  //        $con_his_pag = "Eliminación de descuento de: ".$descuento.", fijando el adeudo en su saldo original, $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
                  //      }else{

                  //        $con_his_pag = "Adeudo de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  Si se realiza antes del ".fechaFormateadaCompacta($filaPago['fin_pag']);
                  //      }

                        
                  //      $men_his_pag = "Recuerda realizar tu pago de $".$filaPago['mon_ori_pag']." por concepto: ".$filaPago['con_pag'].".  ¡Realízalo antes del ".fechaFormateadaCompacta($filaPago['fin_pag'])." si quieres evitar recargos adicionales!";

                      // $fec_his_pag = $fechaHoy;

                      // $res_his_pag = 'Sistema';

                      // $est_his_pag = 'Pendiente';

                      // $tip_his_pag = "Descuento";

                      // $med_his_pag = "SMS";


                      // // INSERCION HISTORIAL
                      // $sqlInsercionHistorialSms = "
                      //  INSERT INTO historial_pago( con_his_pag, men_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
                      //  VALUES( '$con_his_pag', '$men_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag' )
                      // ";



                      // $resultadoInsercionHistorialSms = mysqli_query( $db, $sqlInsercionHistorialSms );

                      // if ( !$resultadoInsercionHistorialSms ) {
                      //  echo $sqlInsercionHistorialSms;
                      // }else {
                      //  $client->messages->create(
                      //      // the number you'd like to send the message to
                      //      '+525518292351',
                      //      array(
                      //          // A Twilio phone number you purchased at twilio.com/console
                      //          'from' => '+13343261337',
                      //          // the body of the text message you'd like to send
                      //          'body' => $men_his_pag
                      //      )
                      //  );
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
					WHERE id_alu_ram1 = '$id_alu_ram'
				";
				$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
				$totalAluHor = mysqli_num_rows($resultadoAluHor);
				//echo $totalAluHor;
				if($totalAluHor == 0){
			
					return "Pendiente";		
			
				}else {
			
					return "Inscrito";
		
				}
			}

		}else{
			echo "Error en validacionEgresado";
		}



    }


    function obtenerCargaAlumno( $id_alu_ram ){
      require('../includes/conexion.php');

      $sql = "
        SELECT *
        FROM alu_hor
        WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
      ";

      $resultado = mysqli_query( $db, $sql );

      $total = mysqli_num_rows( $resultado );

      return $total;

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
					WHERE id_alu_ram1 = '$id_alu_ram'
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
					WHERE id_alu_ram1 = '$id_alu_ram'
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
					WHERE id_alu_ram1 = '$id_alu_ram'
				";
				$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
				$totalAluHor = mysqli_num_rows($resultadoAluHor);
				//echo $totalAluHor;
				if($totalAluHor == 0){
			
					return "Pendiente";		
			
				}else {
			
					return "Inscrito";
		
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
							WHERE id_alu_ram1 = '$id_alu_ram'
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
							WHERE id_alu_ram1 = '$id_alu_ram'
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
					<div class="card-body">
						<label class="letraPequena">
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
					<div class="card-body">
					

					  	<label class="letraPequena">
							<?php echo $nom_cic; ?>
							<br>
							Inscripción: <?php echo fechaFormateadaCompacta($ins_cic); ?>
							<br>
							Inicio: <?php echo fechaFormateadaCompacta($ini_cic); ?>
							<br>
							Corte: <?php echo fechaFormateadaCompacta($cor_cic); ?>
							<br>
							Fin: <?php echo fechaFormateadaCompacta($fin_cic); ?>
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
								<tr class="letraPequena">
									<th class="letraPequena">#</th>
									<th class="letraPequena">Clave</th>
									<th class="letraPequena">Profesor</th>
									<th class="letraPequena">Materia</th>
									<th class="letraPequena">Lunes</th>
									<th class="letraPequena">Martes</th>
									<th class="letraPequena">Miercoles</th>
									<th class="letraPequena">Jueves</th>
									<th class="letraPequena">Viernes</th>
									<th class="letraPequena">Sabado</th>
									<th class="letraPequena">Domingo</th>
								</tr>
							</thead>

							<tbody >

								<?php
									$i = 1;

									while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

								?>

									<tr class="letraPequena">
										<td class="letraPequena">
											<?php echo $i; $i++;  ?>
										</td>

										<td class="letraPequena">
											<?php echo $filaHorario['nom_sub_hor']; ?>
										</td>


										<td class="letraPequena">
											<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
										</td>


										<td class="letraPequena">
											<?php echo $filaHorario['nom_mat']; ?>
										</td>

										<?php
											$id_sub_hor = $filaHorario['id_sub_hor'];
											
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorLunes = mysqli_fetch_assoc($resultadoSubHorLunes)){
												
												?>
													<td class="letraPequena">
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorMartes = mysqli_fetch_assoc($resultadoSubHorMartes)){
												
												?>
														<td class="letraPequena">
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorMiercoles = mysqli_fetch_assoc($resultadoSubHorMiercoles)){
												
												?>
														<td class="letraPequena">
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorJueves = mysqli_fetch_assoc($resultadoSubHorJueves)){
												
												?>
														<td class="letraPequena">
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorViernes = mysqli_fetch_assoc($resultadoSubHorViernes)){
												
												?>
														<td class="letraPequena">
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorSabado = mysqli_fetch_assoc($resultadoSubHorSabado)){
												
												?>
														<td class="letraPequena">
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
											<td class="letraPequena">--</td>

										<?php
											}else{
												while($filaSubHorDomingo = mysqli_fetch_assoc($resultadoSubHorDomingo)){
												
												?>
														<td class="letraPequena">
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
					<div class="card-body">
						<label class="letraPequena">
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
					<div class="card-body">
					

					  	<label class="letraPequena">
							<?php echo $nom_cic; ?>
							<br>
							Inscripción: <?php echo fechaFormateadaCompacta($ins_cic); ?>
							<br>
							Inicio: <?php echo fechaFormateadaCompacta($ini_cic); ?>
							<br>
							Corte: <?php echo fechaFormateadaCompacta($cor_cic); ?>
							<br>
							Fin: <?php echo fechaFormateadaCompacta($fin_cic); ?>
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
								<tr class="letraPequena">
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

									<tr class="letraPequena">
										<td class="letraPequena">
											<?php echo $i; $i++;  ?>
										</td>

										<td class="letraPequena">
											<?php echo $filaHorario['nom_sub_hor']; ?>
										</td>


										<td class="letraPequena">
											<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
										</td>


										<td class="letraPequena">
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
		if ( isset( $_POST['motivo_peticion'] ) ) {
			
			$mot_not_pag = $_POST['motivo_peticion'];

		}else{

			$mot_not_pag = 'Nulo';
		}


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
				return "Inactivo";
			}else{
				return "Activo";
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
				return "Inactivo";
			}else{
				return "Activo";
			}

		}else{
			echo $sqlEstatusAlumno;
		}

   	}
   	// FIN FUNCION PARA OBTENER ESTATUS DE PAGOS DE ALUMNO


   	function obtenerEstatusPago( $id_pag ){

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
				
				return '<span class="badge badge-success">Pagado</span>';
			
			}else if ( $fin_pag < $fechaHoy && $est_pag == 'Pendiente' ) {

				return '<span class="badge badge-danger">Vencido</span>';
			
			}else {

				return '<span class="badge badge-warning">Pendiente</span>';
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
				return '<a class="chip grey darken-1 text-white waves-effect letraPequena obtenerDocumentosPendientes" id_alu_ram="'.$id_alu_ram.'">Pendiente</a>';
			}else { 
				return '<a class="chip success-color text-white waves-effect letraPequena obtenerDocumentosPendientes" id_alu_ram="'.$id_alu_ram.'">Entregados</a>';
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


			for ( $i = 0, $bool = 'false', $mensaje = '' ; $i < $ultimo ; $i++ ) {

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


			for ( $i = 0, $bool = 'false', $mensaje = '' ; $i < $ultimo ; $i++ ) {

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

					}

					if ( $bool == 'false' ) {
						$mensaje = $nom1_mat." se empalma con ".$nom2_mat;
						$mensaje2 = $nom1_mat." de ".$ini1_hor." a ".$fin1_hor." se empalma con ".$nom2_mat." de ".$ini2_hor." a ".$fin2_hor;
						$mensaje3 = "La materia ".$nom1_mat." ha sido removida";
?>
						<script>

							var id_sub_hor = <?php echo $id_sub_hor[$ultimo]; ?>;
							swal( 'Error de empalme' , '<?php echo $mensaje2; ?>', "error", {button: "Aceptar",});
							toastr.error( '<?php echo $mensaje3 ?>' );

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
				<script>
					toastr.info( 'Agregado correctamente' );
				</script>


<?php
			}


		}else{

			echo $sql;
		}

	}

	
	function generarMatriculaCompuestaServer( $plantel ){
		require('../../includes/conexion.php');

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
			} else if ( ($est1_alu_ram == 'N') || ($est1_alu_ram == 'Rec') ) {
				
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


  function obtenerEstatusAlumnoGlobal( $id_alu ){
    require('../includes/conexion.php');
    
    $datos = array();
    $datos['totalSaldo'] = 0;
    $datos['totalRegistros'] = 0;
    
    $sql = "
      SELECT *
      FROM alu_ram
      WHERE id_alu1 = '$id_alu'
    ";

    $resultado = mysqli_query( $db, $sql );

    while( $fila = mysqli_fetch_assoc( $resultado ) ){
      $id_alu_ram = $fila['id_alu_ram'];

      $datos['totalSaldo'] = obtenerSaldoAlumnoFechaHoy( $id_alu_ram );
      $datos['totalRegistros'] = obtenerRegistrosPendientesFechaHoy( $id_alu_ram ); 

    }

    return $datos;

  }



  function obtenerSaldoAlumnoFechaHoy( $id_alu_ram ) {
    require('../includes/conexion.php');

      $fechaHoy = date('Y-m-d');

    $sql ="
        SELECT id_alu_ram, fin_pag, SUM(mon_pag) AS saldoPendiente
        FROM alu_ram
        INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
        WHERE fin_pag < '$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
    ";

    $resultado = mysqli_query( $db, $sql );

    if ( $resultado ) {
      $fila = mysqli_fetch_assoc( $resultado );

      return round($fila['saldoPendiente'], 2);

    }else{
      // echo $sql;
    }
  }


  function obtenerRegistrosPendientesFechaHoy( $id_alu_ram ) {
    require('../includes/conexion.php');

      $fechaHoy = date('Y-m-d');

    $sql ="
        SELECT id_alu_ram, fin_pag
        FROM alu_ram
        INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
        WHERE fin_pag <= '$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
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


	function obtenerSaldoAlumnoFechaHoyServer ( $id_alu_ram ) {
		require('../../includes/conexion.php');

    	$fechaHoy = date('Y-m-d');

		$sql ="
		    SELECT id_alu_ram, fin_pag, SUM(mon_pag) AS saldoPendiente
		    FROM alu_ram
		    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
		    WHERE fin_pag < '$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
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
		    WHERE fin_pag <= '$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
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
          SELECT SUM( mon_con_pag ) AS montoCondonado
          FROM condonacion_pago
          WHERE id_pag2 = '$id_pag'
        ";

        $resultado = mysqli_query( $db, $sql );

        if ( $resultado ) {
          	
          	$fila = mysqli_fetch_assoc( $resultado );
			
			return $fila['montoCondonado'];
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
			WHERE id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL 
		";

		//echo $sql;
		$resultado = mysqli_query( $db, $sql );

		if( $resultado ){

			$contador = 0;
			while ( $fila = mysqli_fetch_assoc( $resultado ) ) {

				

				if ( $fila['id_ent_cop2'] != NULL ) {
					
					$id_ent_cop = $fila['id_ent_cop2'];

					$sqlCopia = "
						SELECT *
						FROM entregable_copia
						WHERE id_ent_cop = '$id_ent_cop'
					";

					$resultadoCopia = mysqli_query( $db, $sqlCopia );

					if ( $resultadoCopia ) {
						
						$filaCopia = mysqli_fetch_assoc( $resultadoCopia );
						$fin_ent_cop = $filaCopia['fin_ent_cop'];

						if ( $fechaHoy > $fin_ent_cop ) {
							$contador++;
						}

					} else {
						echo $sqlCopia;
					}

				} else if ( $fila['id_for_cop2'] != NULL ) {
					
					$id_for_cop = $fila['id_for_cop2'];

					$sqlCopia = "
						SELECT *
						FROM foro_copia
						WHERE id_for_cop = '$id_for_cop'
					";

					$resultadoCopia = mysqli_query( $db, $sqlCopia );

					if ( $resultadoCopia ) {
						
						$filaCopia = mysqli_fetch_assoc( $resultadoCopia );
						$fin_for_cop = $filaCopia['fin_for_cop'];

						if ( $fechaHoy > $fin_for_cop ) {
							$contador++;
						}

					} else {
						echo $sqlCopia;
					}

				} else if ( $fila['id_exa_cop2'] != NULL ) {
					$id_exa_cop = $fila['id_exa_cop2'];

					$sqlCopia = "
						SELECT *
						FROM examen_copia
						WHERE id_exa_cop = '$id_exa_cop'
					";

					$resultadoCopia = mysqli_query( $db, $sqlCopia );

					if ( $resultadoCopia ) {
						
						$filaCopia = mysqli_fetch_assoc( $resultadoCopia );
						$fin_exa_cop = $filaCopia['fin_exa_cop'];

						if ( $fechaHoy > $fin_exa_cop ) {
							$contador++;
						}

					} else {
						echo $sqlCopia;
					}
				}


			}


			if ( $contador > 0 ) {

				return 'Adeudo';
			
			} else {

				return 'N/A';
			
			}

		}else{
			echo $sql;
		}
	}



	function obtenerEstatusPagoAlumnoGlobal( $id_alu ) {
		require('../includes/conexion.php');

		$sql = "
  		SELECT *
  		FROM alumno
  		WHERE id_alu = '$id_alu' AND est_alu = 'Inactivo'
  	";

  	$resultado = mysqli_query( $db, $sql );

  	if ( $resultado ) {
  		
  		$fila = mysqli_fetch_assoc( $resultado );
  		$est_alu = $fila['est_alu'];

  		if ( $est_alu == "Inactivo" ) {
  			
  			header( 'location: not_found_404_page.php' );

  		} else {

  			// VALIDACION ALUMNO ACTIVO
  			$fechaHoy = date('Y-m-d');

	    	$sqlAlumno = "
  				SELECT *
  				FROM alumno
  				INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
  				WHERE id_alu = '$id_alu'
	    	";

	    	$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

	    	$bool = "true";

	    	if ( $resultadoAlumno ) {
	    		
	    		while ( $filaAlumno = mysqli_fetch_assoc( $resultadoAlumno ) ) {

	    			$id_alu_ram = $filaAlumno['id_alu_ram'];

	    			$sqlEstatusAlumno ="
					    SELECT id_alu_ram, fin_pag 
					    FROM alu_ram
					    INNER JOIN pago ON pago.id_alu_ram10 = alu_ram.id_alu_ram
					    WHERE fin_pag <'$fechaHoy' AND est_pag = 'Pendiente' AND id_alu_ram = '$id_alu_ram'
  					";

  					$resultadoEstatusAlumno = mysqli_query( $db, $sqlEstatusAlumno );

  					if ( $resultadoEstatusAlumno ) {
  						$validacionEstatusAlumno = mysqli_num_rows( $resultadoEstatusAlumno );

  						if ( ( $validacionEstatusAlumno > 0 ) && ( $bool == "true" ) ) {
  							$bool = "false";
  							$alumno = $id_alu_ram;
  						}

  					}else{

  						echo $sqlEstatusAlumno;
  					
            }
	    		
          }

	    		if ( $bool == "false" ) {
	    			header( 'location: cobranza_alumno.php?id_alu_ram='.$alumno );
	    		}

	    	} else {
	    		echo $sqlAlumno;
	    	}

  			// FIN VALIDACION ALUMNO ACTIVO
  		}

  	} else {
  		echo $sql;
  	}

    	

		
	}



	function obtenerConteoNotificacionesServer( $id ) {

	require('../includes/conexion.php');

	$fechaHoy = date('Y-m-d');


	$sqlNotificacionesActividades = "
		SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo, id_alu_ram AS alumno_rama, id_for_cop AS id_cop
		  FROM alumno
		  INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
		  INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		  INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		  INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		  INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		  INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
		  INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
		  WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id'
		      UNION
		  SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo, id_alu_ram AS alumno_rama, id_ent_cop AS id_cop
		  FROM alumno
		  INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
		  INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		  INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		  INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		  INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		  INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
		  INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
		  WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id'
		  UNION
		      SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo, id_alu_ram AS alumno_rama, id_exa_cop AS id_cop
		  FROM alumno
		  INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
		  INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		  INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		  INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		  INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		  INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
		  INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
		  WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id'
		  ORDER BY inicio
		";

		//echo $sqlNotificacionesActividades;

		$resultadoValidacionActividades = mysqli_query($db, $sqlNotificacionesActividades);

		$totalValidacionActividades = 0;
		
		while($filaValidacionActividades = mysqli_fetch_assoc($resultadoValidacionActividades)){

		$id_cop = $filaValidacionActividades['id_cop'];
		$id_alu_ram = $filaValidacionActividades['alumno_rama'];
		$tipo = $filaValidacionActividades['tipo'];


		// echo $id_cop."<br>";
		// echo $id_alu_ram."<br>";
		// echo $tipo."<br>";

		if ($filaValidacionActividades['tipo'] == 'Foro') {

		  $sqlCalificacionActividad = "

		    SELECT *
		    FROM cal_act
		    INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
		    WHERE id_for_cop2 = '$id_cop' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL
		  ";

		  //echo $sqlCalificacionActividad;

		  $resultadoCalificacionActividad = mysqli_query($db, $sqlCalificacionActividad);

		  $totalCalificacionActividad = mysqli_num_rows($resultadoCalificacionActividad);

		  //echo $totalCalificacionActividad;

		  if ($totalCalificacionActividad > 0) {
		    $totalValidacionActividades = $totalValidacionActividades + 1;
		  }

		}else if($filaValidacionActividades['tipo'] == 'Entregable'){
		  $sqlCalificacionActividad = "

		    SELECT *
		    FROM cal_act
		    INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
		    WHERE id_ent_cop2 = '$id_cop' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL
		  ";

		  //echo $sqlCalificacionActividad;

		  $resultadoCalificacionActividad = mysqli_query($db, $sqlCalificacionActividad);

		  $totalCalificacionActividad = mysqli_num_rows($resultadoCalificacionActividad);

		  //echo $totalCalificacionActividad;

		  if ($totalCalificacionActividad > 0) {
		    $totalValidacionActividades = $totalValidacionActividades + 1;
		  }

		}else if($filaValidacionActividades['tipo'] == 'Examen'){
		  $sqlCalificacionActividad = "

		    SELECT *
		    FROM cal_act
		    INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
		    WHERE id_exa_cop2 = '$id_cop' AND id_alu_ram4 = '$id_alu_ram' AND fec_cal_act IS NULL
		  ";

		  //echo $sqlCalificacionActividad;

		  $resultadoCalificacionActividad = mysqli_query($db, $sqlCalificacionActividad);

		  $totalCalificacionActividad = mysqli_num_rows($resultadoCalificacionActividad);

		  //echo $totalCalificacionActividad;

		  if ($totalCalificacionActividad > 0) {
		    $totalValidacionActividades = $totalValidacionActividades + 1;
		  }


		}


	}

	return $totalValidacionActividades;

	}
?>