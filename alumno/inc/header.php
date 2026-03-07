<?php
  ob_start();
  require_once('cabeceras.php');
  include_once('funciones.php');
  
  $datos = obtenerEstatusAlumnoGlobal( $id );
	$estatus = obtenerEstatusAlumno( $id );
	$estatus2 = obtenerEstatus2Alumno( $id );
	$estatus_general = obtener_estatus_general_vista_alumnos( $id );

  
  $sql_pago = "SELECT count(id_pag) as adeudos from pago where id_alu_ram10= '$alumno_rama' AND (est_pag = 'Pendiente' AND tip_pag = 'Colegiatura') AND fin_pag<curdate()";
  $get_pagos = mysqli_query($db, $sql_pago);
  $check_pago = mysqli_fetch_assoc($get_pagos);
  //echo $sql_pago;
  $pagos;
  if ($check_pago['adeudos']>0) {
    //echo "El papu, debe plata";
    $sql_pago = "SELECT SUM(mon_pag) as adeudos from pago where id_alu_ram10= '$alumno_rama' AND (est_pag = 'Pendiente' AND tip_pag = 'Colegiatura') AND fin_pag<curdate()";
  $get_pagos = mysqli_query($db, $sql_pago);
  $check_pago = mysqli_fetch_assoc($get_pagos);
  $pagos['deuda'] = 'si';
  $pagos['monto'] = $check_pago['adeudos'];
  echo $pagos['monto'];
  }
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    

    <title>
      <?php 
        echo $nombrePlantel; 
      ?>
    </title>

    <link rel="icon" href="../uploads/<?php echo $fotoPlantel; ?>">


    <?php 

        require_once(  __DIR__."/../../includes/links_estilos.php");
    ?>

</head>

<body class="fixed-sn grey-skin grey lighten-4" style="z-index: -1" id="mainBody">

    <!--Double navigation-->
    <header>
        <!-- Sidebar navigation -->

        <div id="slide-out" class="side-nav sn-bg-4 fixed" style="width: 300px;">
          <ul class="scrollbar scrollbar-deep-blue" id="barra-side">

              <!-- Marco Foto -->
              <div class="container p-2 text-center waves-effect">
                  
                <div class="row">
                  <div class="col">
                     <!-- Card Dark -->
                    <div class="card animated fadeIn delay-1s">




                      <!-- Card content -->
                      <div class="card-body  black white-text rounded-bottom">

                        <div class="row">
                          <div class="col">
                            <br>
                              <img src="../uploads/<?php echo $fotoPlantel; ?>"  class="rounded-circle" width="45%">
                              <br>
                              
                       
                           
                          </div>
                        </div>

                        <small>


                          <hr class="hr-light">

                          <p class="white-text">
                            <?php echo $nombrePlantel; ?>
                              
                          </p>

                          <!-- Title -->
                          

                            

                        </small>
                        
                     
                      </div>

                    </div>
                    <!-- Card Dark -->
                           
                  </div>
                </div>
              </div>

              <!-- Fin marco foto -->
            
            <!-- Side navigation links -->
            <li>

                <ul class="custom-scrollbar list-unstyled ps ps--active-y collapsible collapsible-accordion">
                  <!-- Side navigation links -->
                  <li>
                    
                    <ul class="collapsible collapsible-accordion clasePadre">

                        <?php 
                          $i = 1;

                          $sqlConsultaRamasHeader = "
                              SELECT * 
                              FROM alumno
                              INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
                              INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
                              INNER JOIN plantel ON plantel.id_pla = rama.id_pla1 
                              WHERE id_alu = '$id'";

                            $resultadoConsultaRamasHeader = mysqli_query($db, $sqlConsultaRamasHeader); 
                              while($filaRamasHeader = mysqli_fetch_assoc($resultadoConsultaRamasHeader)){

                                $id_alu_ram = $filaRamasHeader['id_alu_ram'];
                                $id_ram = $filaRamasHeader['id_ram'];

                                $sqlConsultaAluHor = "SELECT * FROM alu_hor WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo' ";
                                $resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
                                $totalAluHor = mysqli_num_rows($resultadoAluHor);

                            ?>

                                <!-- PROGRAMAS -->
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                  <a class="collapsible-header waves-effect" href="horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Programa académico">
                                    <i class="fas fa-university"></i>
                                    <?php echo $filaRamasHeader['nom_ram']; ?>
                                  </a>
                                </li>
                                <!-- FIN PROGRAMAS -->


                                <!-- PAGOS -->
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                  <a class="collapsible-header waves-effect" href="cobranza_alumno.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Consulta tu historial de pagos">
                                    <i class="fas fa-dollar-sign"></i>
                                    Historial de pagos
                                  </a>
                                </li>
                                <!-- FIN PAGOS -->


                                <!-- CALIFICACIONES -->
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                  <a class="collapsible-header waves-effect" href="historial_academico.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Consulta tu historial académico">
                                    <i class="fas fa-clipboard-check"></i>
                                    Historial de calificaciones
                                  </a>
                                </li>
                                <!-- FIN CALIFICACIONES -->

                                
                                <?php  
                                  if ( obtenerCargaAlumno( $id_alu_ram ) > 0 ) {
                                ?>

                                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                      <a class="collapsible-header waves-effect" href="historial_actividades.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Consulta el listado total de actividades">
                                        <i class="fas fa-clipboard-list"></i>
                                        Historial de actividades
                                      </a>


                                      <?php  
                                        if ( obtenerTotalNotificacionesPrograma( $id_alu_ram ) > 0 ) {
                                      ?>
                                          <span class="badge badge-danger font-weight-normal claseHijoPrograma" title="Tienes <?php echo obtenerTotalNotificacionesPrograma( $id_alu_ram ); ?> actividades pendientes por revisar" id_alu_ram="<?php echo $id_alu_ram; ?>">
                                            <?php echo obtenerTotalNotificacionesPrograma( $id_alu_ram ); ?>
                                              
                                          </span>

                                      <?php
                                        }
                                      ?>
                                    </li>
      

                                    <!-- HORARIO -->
                                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                      <a class="collapsible-header waves-effect" href="horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Mi horario">
                                        <i class="fas fa-calendar-alt"></i>
                                        Consultar horario
                                      </a>
                                    </li>
                                    <!-- FIN HORARIO -->


                                    <!-- CALIFICACIONES -->
                                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                      <a class="collapsible-header waves-effect" href="calificaciones.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Mis calificaciones">
                                        <i class="fas fa-check-circle"></i>
                                        Consultar calificaciones
                                      </a>
                                    </li>
                                    <!-- FIN CALIFICACIONES -->



                                    <!-- VALIDACION SI EXISTEN TRABAJOS ESPECIALES POR id_alu_ram -->
                                    <?php  
                                      $sqlValidacion = "
                                        SELECT *
                                        FROM proyecto_alu_ram
                                        WHERE id_alu_ram15 = '$id_alu_ram'
                                      ";

                                      $resultadoValidacion = mysqli_query( $db, $sqlValidacion );

                                      $validacion = mysqli_num_rows( $resultadoValidacion );

                                      if ( $validacion > 0 ) {
                                    ?>

                                        <!-- TRABAJOS ESPECIALES -->
                                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">



                                          

                                          
                                          <a class="collapsible-header waves-effect clasePadreClase" href="trabajos_especiales.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Consulta tus trabajos especiales">
                                            <?php  
                                            
                                              $notificacionesTrabajosEspeciales = obtenerTotalNotificacionesTrabajosEspeciales( $id_alu_ram );
                                              
                                              if ( $notificacionesTrabajosEspeciales > 0 ) {
                                            
                                            ?>
                                            
                                                <span class="badge badge-danger font-weight-normal claseHijoClase" title="Tienes <?php echo $notificacionesTrabajosEspeciales; ?> trabajos especiales pendientes por revisar"><?php echo $notificacionesTrabajosEspeciales; ?></span>

                                            
                                            <?php
                                              }
                                            ?>
                                            
                                            <i class="fas fa-star"></i>
                                            Trabajos especiales

                                          </a>
                                       
                                        </li>
                                        <!-- FIN TRABAJOS ESPECIALES -->

                                    <?php
                                      }
                                    ?>
                                    <!-- FIN VALIDACION SI EXISTEN TRABAJOS ESPECIALES POR id_alu_ram -->
                                    





                                    




                                    <?php
                                      $sqlMaterias = "
                                          
                                          SELECT *
                                          FROM alu_hor
                                          INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
                                          INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                                          INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                                          INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                                          INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                                          WHERE id_alu_ram1 = '$id_alu_ram' AND est_sub_hor = 'Activo' AND est_alu_hor = 'Activo'
                                          ORDER BY nom_mat

                                      ";
                                      
                                      

                                      $resultadoMaterias = mysqli_query( $db, $sqlMaterias );

                                      while( $filaMaterias = mysqli_fetch_assoc( $resultadoMaterias ) ){
                                        $id_mat = $filaMaterias['id_mat'];
                                        $id_sub_hor = $filaMaterias['id_sub_hor'];
                                        $id_alu_ram = $filaMaterias['id_alu_ram1'];

                                        // echo $sqlTotalAlumnos;
                                    ?>
                                          <!-- OPCIONES GRUPALES -->
                                          <li>
                                            <a class="collapsible-header waves-effect arrow-r" 
                                              title="
                                                <?php
                                                  echo $filaMaterias['nom_mat'].' / '.$filaMaterias['nom_gru'].' / '.obtenerDiaMes( $filaMaterias['ini_cic'] )." al ".obtenerDiaMes( $filaMaterias['fin_cic'] );
                                                ?>
                                              "
                                            >
                                              
                                              <div class="row clasePadreMateria">

                                                <?php  
                                                  if ( obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ) > 0 ) {
                                                ?>
                                                    <span class="badge badge-danger font-weight-normal claseHijoMateria " id_sub_hor="<?php echo $id_sub_hor; ?>" title="Tienes <?php echo obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ); ?></span>

                                                <?php
                                                  }
                                                ?>

                                                <div class="col-md-10 font-weight-normal">
                                                  <i class="fas fa-chevron-right"></i>

                                                    <?php


                                                      // echo strlen( $filaMaterias['nom_mat'] );
                                                      
                                                      if ( strlen( $filaMaterias['nom_mat'] ) > 10 ) {
                                                        echo mb_strtolower( substr( $filaMaterias['nom_mat'], 0, 10 ).".." )." / ".mb_strtolower( substr( $filaMaterias['nom_gru'], 0, 20 ) ); 
                                                      } else {
                                                        echo mb_strtolower( $filaMaterias['nom_mat'] )." / ".mb_strtolower( substr( $filaMaterias['nom_gru'], 0, 20 ) ); 
                                                      }
                                                      
                                                    ?>

                                                  
                                                </div>

                                                
                                                <div class="col-md-2">


                                                  
                                                </div>

                                                

                                              </div>
                                              
                                              <i class="fas fa-angle-down rotate-icon"></i>
                                            </a>
                                              
                                            <div class="collapsible-body">
                                              
                                              <ul class="list-unstyled">
                                                
                                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect ">
                                                  <a class="collapsible-header waves-effect clasePadreClase" href="clases_materia.php?id_sub_hor=<?php echo $filaMaterias['id_sub_hor']; ?>&id_alu_ram=<?php echo $id_alu_ram; ?>" title="Consulta las clases del grupo de <?php echo $filaMaterias['nom_mat']; ?>">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                    Clases

                                                    <?php  
                                                      if ( obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ) > 0 ) {
                                                    ?>
                                                        <span class="badge badge-danger font-weight-normal claseHijoClase" id_sub_hor="<?php echo $id_sub_hor; ?>" title="Tienes <?php echo obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesGrupo( $id_alu_ram, $id_sub_hor ); ?></span>

                                                    <?php
                                                      }
                                                    ?>

                                                    
                                                  </a>
                                                </li>


                                                <?php /** 
                                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                                  <a class="collapsible-header waves-effect" href="mensajes.php?id_sub_hor=<?php echo $filaMaterias['id_sub_hor']; ?>" title="Envía y recibe mensajes en tiempo real con tu grupo de <?php echo $filaMaterias['nom_mat']; ?>">
                                                    <i class="fas fa-comments"></i>
                                                    Mensajería
                                                  </a>
                                                </li>
                                                */
                                                ?>
                                              



                                              </ul>

                                            </div>

                                          </li>

                                          <!-- FIN OPCIONES GRUPALES -->

                                        
                                    <?php  
                                      
                                      }
                                    
                                    ?>

                                <?php
                                  }
                                ?>
                                

                                




                            <?php
                              }

                            ?>

       
                    </ul>
                      
                      </li>
                      <div class="scrollbar scrollbar-deep-blue">
                        <div class="force-overflow"></div>
                      </div>
                      <!--/. Side navigation links -->
                  </ul>
                </li>
                <!--/. Side navigation links -->
                <div class="scrollbar scrollbar-deep-blue">
                  <div class="force-overflow"></div>
                </div>
            </ul>
            <div class="sidenav-bg mask-strong"></div>
        </div>
        <!--/. Sidebar navigation -->

        <!-- Navbar -->
        <nav id="mainNabvar" class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav grey darken-1">
            <!-- SideNav slide-out button -->
            <div class="float-left clasePadreHamburguesa">
                
                <a href="#" data-activates="slide-out" class="button-collapse">
                  <i class="fas fa-bars">    
                  </i>                  
                </a>

                <?php  
                  if ( obtenerTotalNotificacionesHamburgesa( $id ) > 0 ) {
                ?>
                    <span class="badge badge-danger font-weight-normal claseHijoHamburguesa" title="Tienes <?php echo obtenerTotalNotificacionesHamburgesa( $id ); ?> actividades pendientes por revisar" id="span_hamburguesa">
                      <?php echo obtenerTotalNotificacionesHamburgesa( $id ); ?>
                        
                    </span>

                <?php
                  }
                ?>

            </div>
            <!-- Breadcrumb-->
            

            <?php

              reloj();

            ?>
            <ul class="nav navbar-nav nav-flex-icons ml-auto">


              <li class="nav-item dropdown">
                <div class="dropdown" id="contenedor_notificaciones_actividades">

                  <?php
                    $fechaHoy = date('Y-m-d');  
                    $sqlNotificacionesCobros = "
                      SELECT id_for_cop AS id, nom_for AS actividad, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, id_alu_ram AS alumno_rama, id_for_cop AS id_cop
                        FROM cal_act
                        INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
                        INNER JOIN foro ON foro.id_for = foro_copia.id_for1
                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1                       
                        WHERE  ini_for_cop <= '$fechaHoy' AND fin_for_cop >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
                            UNION
                        SELECT id_ent_cop AS id, nom_ent AS actividad, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, id_alu_ram AS alumno_rama, id_ent_cop AS id_cop
                        FROM cal_act
                        INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
                        INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
                        WHERE  ini_ent_cop <= '$fechaHoy' AND fin_ent_cop >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
                        UNION
                            SELECT id_exa_cop AS id, nom_exa AS actividad, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, id_alu_ram AS alumno_rama, id_exa_cop AS id_cop
                        FROM cal_act
                        INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
                        INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
                        WHERE  ini_exa_cop <= '$fechaHoy' AND fin_exa_cop >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
                        ORDER BY inicio
                    ";
                    // echo $sqlNotificacionesCobros;

                    $resultadoNotificacionesCobros = mysqli_query( $db, $sqlNotificacionesCobros );
                    
                    if ( $resultadoNotificacionesCobros ) {
                      
                      $notificacionesCobros = mysqli_num_rows( $resultadoNotificacionesCobros );

                      // NOTIFICACIONES DE TRABAJOS ESPECIALES
                      $notificacionesTrabajosEspeciales = obtenerTotalNotificacionesTrabajosEspeciales( $id_alu_ram );

                      // echo $notificacionesTrabajosEspeciales;
                      $notificacionesCobros = $notificacionesCobros + $notificacionesTrabajosEspeciales;
                      // NOTIFICACIONES DE TRABAJOS ESPECIALES

                      if ( ($notificacionesCobros > 0) && ($notificacionesCobros < 10) ) {
                      // SI HAY NOTIFICACIONES
                  ?>
                        <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      
                          Notificaciones
                          <i class="fas fa-bell pr-1 animated swing infinite"></i>
                          <span class="badge badge-danger font-weight-normal notification rounded-circle">
                            <?php echo $notificacionesCobros; ?>
                          </span>
                          
                        </a>

                        <div class="dropdown-menu grey lighten-2">

                          <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones">
                  
                            
                            
                            <table class="table table-sm" id="tablaValidaciones">
                            <!-- NO HAY HEADER DE TABLA -->
                              <tbody >
                                <div class="accordion" id="accordionExample275">

                                </div>
                              </tbody>
                              
                            </table>
                 
                            <div id="load_data_message" class="text-center"></div>
                          </form>

                        </div>

                  <?php
                      }else if ($notificacionesCobros >=10) {
                        ?>
                         <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo $notificacionesCobros; ?> notificaciones pendientes">
                      
                          Notificaciones
                          <i class="fas fa-bell pr-1 animated swing"></i>
                          <span class="badge badge-danger font-weight-normal notification rounded-circle">
                            +9
                          </span>

                        </a>

                        <div class="dropdown-menu grey lighten-2">

                          <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones" style="height: 210px;">
                        
                            
                            <table class="table table-sm" id="tablaValidaciones">
                            <!-- NO HAY HEADER DE TABLA -->
                              <tbody >
                                <div class="accordion" id="accordionExample275">

                                </div>
                              </tbody>
                              
                            </table>
                 
                            <div id="load_data_message" class="text-center"></div>
                          </form>
                          
                        </div>

                  <?php
                      } else{
                      // NO HAY NOTIFICACIONES
                  ?>
                        <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      
                          Notificaciones
                          <i class="fas fa-bell pr-1 animated swing"></i>
                          <span class="badge badge-danger font-weight-normal notification rounded-circle">
                            0
                          </span>

                        </a>

                        <div class="dropdown-menu grey lighten-2">

                          <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones" style="height: 210px;">
                        
                            
                            <table class="table table-sm" id="tablaValidaciones">
                            <!-- NO HAY HEADER DE TABLA -->
                              <tbody >
                                <div class="accordion" id="accordionExample275">

                                </div>
                              </tbody>
                              
                            </table>
                 
                            <div id="load_data_message" class="text-center"></div>
                          </form>
                          
                        </div>

                  <?php
                      }

                    }else{
                      echo $sqlNotificacionesCobros;
                    }
                  ?>

                </div>
              </li>


                
                <?php /** 
                <!-- MENSAJERIA -->
                <li class="nav-item dropdown">
                  
                  <div class="dropdown" id="contenedor_notificaciones_mensajeria">
                  </div>
                
                </li>
                <!-- FIN MENSAJERIA -->
                */?>

      

                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img width="25px" height="25px" src="../uploads/<?php echo $fotoUsuario; ?>" alt="avatar" class="avatar rounded-circle mr-0 ml-3 z-depth-1" id="foto_usuario"> <span class="clearfix d-none d-sm-inline-block"><?php echo $nombre; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="editor.php" title="Usar Editor, herramienta de procesamiento de texto, guarda y consulta tus archivos"><i class="fas fa-pen-square"></i> Editor</a>

                        <a class="dropdown-item" href="#" id="btn_calculadora" title="Usar Calculadora"><i class="fas fa-calculator"></i> Calculadora</a>


                        
                        <a class="dropdown-item" href="perfil.php"><i class="fas fa-wrench" title="Modifica información básica de tu cuenta como tu foto o contraseña"></i> Ajustes</a>

                        <a class="dropdown-item" href="ayuda.php" title="No sabes cómo empezar, obten ayuda aquí"><i class="fas fa-question-circle"></i> Ayuda</a>

                        <a class="dropdown-item" href="cerrar_sesion.php" title="Cerrar sesión"><i class="fas fa-sign-out-alt"></i> Salir</a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.Navbar -->
    </header>
    <!--/.Double navigation-->




<!-- SPINNER HTML,  Y JS  -->

<style>
  body {
    /*font-family: 'Josefin Sans', sans-serif;*/
    /*font-family: 'Space Mono', monospace;*/
    font-family: 'Heebo', sans-serif;
  }


  .claseHijoPrograma{
    position: absolute;
    left: 27px;
    bottom: 20px;
  }



  
.claseHijoHamburguesa {
  position: absolute;
  right: -12px;
  bottom: 20px;
}

.clasePadreHamburguesa {
  position: relative;
}


.claseHijoMateria {
  position: absolute;
  left: 27px;
  bottom: 20px;
}

.clasePadreMateria {
  position: relative;
}


.claseHijoClase {
  position: absolute;
  left: 35px;
  bottom: 20px;
}

.clasePadreClase {
  position: relative;
}
  

.sk-cube-grid {
  width: 40px;
  height: 40px;
  margin: 100px auto;
}

.sk-cube-grid .sk-cube {
  width: 33%;
  height: 33%;
  background-color: #9e9e9e;
  float: left;
  -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
          animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out; 
}
.sk-cube-grid .sk-cube1 {
  -webkit-animation-delay: 0.2s;
          animation-delay: 0.2s; }
.sk-cube-grid .sk-cube2 {
  -webkit-animation-delay: 0.3s;
          animation-delay: 0.3s; }
.sk-cube-grid .sk-cube3 {
  -webkit-animation-delay: 0.4s;
          animation-delay: 0.4s; }
.sk-cube-grid .sk-cube4 {
  -webkit-animation-delay: 0.1s;
          animation-delay: 0.1s; }
.sk-cube-grid .sk-cube5 {
  -webkit-animation-delay: 0.2s;
          animation-delay: 0.2s; }
.sk-cube-grid .sk-cube6 {
  -webkit-animation-delay: 0.3s;
          animation-delay: 0.3s; }
.sk-cube-grid .sk-cube7 {
  -webkit-animation-delay: 0s;
          animation-delay: 0s; }
.sk-cube-grid .sk-cube8 {
  -webkit-animation-delay: 0.1s;
          animation-delay: 0.1s; }
.sk-cube-grid .sk-cube9 {
  -webkit-animation-delay: 0.2s;
          animation-delay: 0.2s; }

@-webkit-keyframes sk-cubeGridScaleDelay {
  0%, 70%, 100% {
    -webkit-transform: scale3D(1, 1, 1);
            transform: scale3D(1, 1, 1);
  } 35% {
    -webkit-transform: scale3D(0, 0, 1);
            transform: scale3D(0, 0, 1); 
  }
}

@keyframes sk-cubeGridScaleDelay {
  0%, 70%, 100% {
    -webkit-transform: scale3D(1, 1, 1);
            transform: scale3D(1, 1, 1);
  } 35% {
    -webkit-transform: scale3D(0, 0, 1);
            transform: scale3D(0, 0, 1);
  } 
}

.my-custom-scrollbar {
    position: relative;
    height: 200px;
    overflow: auto;
  }
  .table-wrapper-scroll-y {
    display: block;
  }

</style>

<div style="background: black; z-index: 99999999; width: 100%; height: 100%; " id="overlay">
    <div class="sk-cube-grid" style="height:60px;
      width:60px;
      position:fixed;
      left:48%;
      top:30%; z-index: 99999;">
      <div class="sk-cube sk-cube1"></div>
      <div class="sk-cube sk-cube2"></div>
      <div class="sk-cube sk-cube3"></div>
      <div class="sk-cube sk-cube4"></div>
      <div class="sk-cube sk-cube5"></div>
      <div class="sk-cube sk-cube6"></div>
      <div class="sk-cube sk-cube7"></div>
      <div class="sk-cube sk-cube8"></div>
      <div class="sk-cube sk-cube9"></div>
      <span style="color: #9e9e9e;">Cargando...</span>
    </div>
    
</div>



<script>
  var overlay = document.getElementById("overlay");

  window.addEventListener('load', function(){
    overlay.style.display = 'none';
    
  });

</script>


<!-- FIN SPINNER -->

    <!--Main layout-->
    <main>
        <div class="container-fluid white" id="mainContainer">
            <?php 
                include("inc/calculadora.php");
             ?>

            <br>