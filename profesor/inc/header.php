<?php  
  ob_start();
  require('cabeceras.php');
  include('funciones.php');

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

<body class="fixed-sn grey-skin grey lighten-3" style="z-index: -1" id="mainBody">

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
                <div class="card">

                  <!-- Card content -->
                  <div id="tarjeta_nav" class="card-body  black white-text rounded-bottom">

                    <div class="row">
                      <div class="col">
                        <br>
                          <img src="../uploads/<?php echo $fotoPlantel; ?>"  class="rounded-circle" width="45%">
                          <br>
                          
                   
                       
                      </div>
                    </div>

                    <small>


                      <hr class="hr-light">

                      <p>
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
                        
                        <ul class="collapsible collapsible-accordion">

                          <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                            <a class="collapsible-header waves-effect" href="horario.php" title="Mi horario">
                              <i class="fas fa-calendar-alt"></i>
                              Consultar horario
                            </a>
                          </li>

                          <?php  
                            $sqlMaterias = "
                              SELECT *
                              FROM sub_hor
                              INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                              INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                              INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                              INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                              WHERE id_pro = '$id' AND est_sub_hor = 'Activo' AND id_fus2 IS NULL
                              UNION
                              SELECT *
                              FROM sub_hor
                              INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                              INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                              INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                              INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                              WHERE id_pro = '$id' AND est_sub_hor = 'Activo' AND id_fus2 IS NOT NULL AND id_sub_hor_nat IS NULL
                              ORDER BY nom_mat
                            ";

                            // echo $sqlMaterias;

                            $resultadoMaterias = mysqli_query( $db, $sqlMaterias );

                            while( $filaMaterias = mysqli_fetch_assoc( $resultadoMaterias ) ){
                              $id_mat = $filaMaterias['id_mat'];
                              $id_sub_hor = $filaMaterias['id_sub_hor'];


                              $totalAlumnos = obtenerTotalAlumnosGrupo( $id_sub_hor );
                              // echo $sqlTotalAlumnos;
                          ?>
                                
                                <li>
                                  <a class="collapsible-header waves-effect arrow-r" 
                                    title="
                                      <?php
                                        echo $totalAlumnos.' alumnos / '.$filaMaterias['nom_mat'].' / '.$filaMaterias['nom_gru'].' / '.obtenerDiaMes( $filaMaterias['ini_cic'] )." al ".obtenerDiaMes( $filaMaterias['fin_cic'] );
                                      ?>
                                    "
                                  >
                                    
                                    <div class="row clasePadreMateria">
                                      <div class="col-md-10 font-weight-normal">
                                        <i class="fas fa-chevron-right"></i>

                                          <?php


                                            // echo strlen( $filaMaterias['nom_mat'] );
                                            
                                            if ( strlen( $filaMaterias['nom_mat'] ) > 10 ) {
                                              echo strtolower( substr( $filaMaterias['nom_mat'], 0, 10 ).".." )." / ".strtolower( substr( $filaMaterias['nom_gru'], 0, 20 ) ); 
                                            } else {
                                              echo strtolower( $filaMaterias['nom_mat'] )." / ".strtolower( substr( $filaMaterias['nom_gru'], 0, 20 ) ); 
                                            }
                                            
                                          ?>

                                        
                                      </div>

                                      
                                      <div class="col-md-2">


                                        
                                      </div>

                                      <?php  
                                        if ( obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ) > 0 ) {
                                      ?>
                                          <span class="badge badge-danger claseHijoMateria " id_sub_hor="<?php echo $id_sub_hor; ?>" title="Tienes <?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?></span>

                                      <?php
                                        }
                                      ?>


                                    </div>
                                    
                                    <i class="fas fa-angle-down rotate-icon"></i>
                                  </a>
                                    
                                  <div class="collapsible-body">
                                    
                                    <ul class="list-unstyled">
                                      
                                      <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect ">
                                        <a class="collapsible-header waves-effect clasePadreClase" href="clases_materia.php?id_sub_hor=<?php echo $filaMaterias['id_sub_hor']; ?>" title="Crea, edita, elimina y revisa actividades para el grupo de <?php echo $filaMaterias['nom_mat']; ?>">
                                          <i class="fas fa-chalkboard-teacher"></i>
                                          Clases

                                          <?php  
                                            if ( obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ) > 0 ) {
                                          ?>
                                              <span class="badge badge-danger claseHijoClase" id_sub_hor="<?php echo $id_sub_hor; ?>" title="Tienes <?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesGrupo( $id, $id_sub_hor ); ?></span>

                                          <?php
                                            }
                                          ?>
                                        </a>

                                        

                                        
                                      </li>


                                      <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                        <a class="collapsible-header waves-effect" href="alumnos_materia.php?id_sub_hor=<?php echo $filaMaterias['id_sub_hor']; ?>" title="Consulta, imprime y descarga el listado de alumnos del grupo de <?php echo $filaMaterias['nom_mat']; ?>">
                                          <i class="fas fa-user-graduate"></i>
                                          
                                          
                                          Listado de alumnos
                                        </a>
                                      </li>


                                      <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                        <a class="collapsible-header waves-effect" href="actividades_materia.php?id_sub_hor=<?php echo $filaMaterias['id_sub_hor']; ?>" title="Califica el listado de alumnos del grupo de <?php echo $filaMaterias['nom_mat']; ?>">
                                          <i class="fas fa-clipboard-list"></i>
                                          Calificar actividades
                                        </a>
                                      </li>



                                      <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-88068 waves-effect">
                                        <a class="collapsible-header waves-effect" href="mensajes.php?id_sub_hor=<?php echo $filaMaterias['id_sub_hor']; ?>" title="Envía y recibe mensajes en tiempo real con tu grupo de <?php echo $filaMaterias['nom_mat']; ?>">
                                          <i class="fas fa-comments"></i>
                                          Mensajería grupal
                                        </a>
                                      </li>



                                    </ul>

                                  </div>

                                </li>

                              
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
        <nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav grey" id="mainNabvar">
            <!-- SideNav slide-out button -->
            <div class="float-left clasePadreHamburguesa">
                <a href="#" data-activates="slide-out" class="button-collapse"><i class="fas fa-bars"></i></a>
                
                <?php  
                  if ( obtenerTotalNotificacionesHamburgesa( $id ) > 0 ) {
                ?>
                    <span class="badge badge-danger claseHijoHamburguesa" title="Tienes <?php echo obtenerTotalNotificacionesHamburgesa( $id ); ?> actividades pendientes por revisar" id="span_hamburguesa">
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
                      $sqlNotificacionesCobros = "
                        SELECT fec_cal_act AS fecha, nom_for AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_for_cop AS identificador_copia, fot_alu AS foto, tip_for AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru
                        FROM cal_act
                        INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
                        INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
                        INNER JOIN foro ON foro.id_for = foro_copia.id_for1
                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                        WHERE (fec_cal_act IS NOT null ) AND ( pun_cal_act IS NULL ) AND ( id_pro1 = '$id' )
                        GROUP BY identificador_copia, fecha, actividad, alumno, foto, tipo_actividad, clave, nom_mat, id_alu_ram, nom_gru
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
                        WHERE (fec_cal_act IS NOT null ) AND ( pun_cal_act IS NULL ) AND ( id_pro1 = '$id' )
                        GROUP BY identificador_copia, fecha, actividad, alumno, foto, tipo_actividad, clave, nom_mat, id_alu_ram, nom_gru
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
                        WHERE (fec_cal_act IS NOT null ) AND ( pun_cal_act IS NULL ) AND ( id_pro1 = '$id' )
                        GROUP BY identificador_copia, fecha, actividad, alumno, foto, tipo_actividad, clave, nom_mat, id_alu_ram, nom_gru
                        ORDER BY fecha DESC
                      ";
                      //echo $sqlNotificacionesCobros;

                      $resultadoNotificacionesCobros = mysqli_query( $db, $sqlNotificacionesCobros );
                      
                      if ( $resultadoNotificacionesCobros ) {
                        
                        $notificacionesCobros = mysqli_num_rows( $resultadoNotificacionesCobros );

                        if ( ($notificacionesCobros > 0) && ($notificacionesCobros < 10) ) {
                        // SI HAY NOTIFICACIONES
                    ?>
                          <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        
                            Notificaciones
                            <i class="fas fa-bell pr-1 animated swing infinite"></i>
                            <span class="badge badge-danger notification rounded-circle">
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
                            <span class="badge badge-danger notification rounded-circle">
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
                            <span class="badge badge-danger notification rounded-circle">
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
                


                <!-- MENSAJERIA -->
                <li class="nav-item dropdown">
                  
                  <div class="dropdown" id="contenedor_notificaciones_mensajeria">
                  </div>
                
                </li>
                <!-- FIN MENSAJERIA -->

                <?php  
                  if ( $estatusUsuario == 'Activo' ) {
                ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="¡Crear y administra tus clases más rápido!">
                          <i class="fas fa-chalkboard-teacher"></i>
                          <span class="clearfix d-none d-sm-inline-block"> 
                            Mis clases
                          </span>
                          <span class="badge badge-danger" >¡Nuevo!</span>

                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            
                            <a class="dropdown-item" href="#" id="btn_crear_clase">
                              <i class="fas fa-plus"></i>
                              Crear clase
                            </a>

                          

                        </div>
                    </li>

                <?php
                  }

                ?>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img id="foto_usuario" width="25px" height="25px" src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>" alt="avatar" class="avatar rounded-circle mr-0 ml-3 z-depth-1"> <span class="clearfix d-none d-sm-inline-block"><?php echo $nombre; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="editor.php" title="Usar Editor"><i class="fas fa-pen-square"></i> Editor</a>

                        <a class="dropdown-item" href="#" id="btn_calculadora" title="Usar Calculadora"><i class="fas fa-calculator"></i> Calculadora</a>


                        
                        <a class="dropdown-item" href="perfil.php"><i class="fas fa-wrench"></i> Ajustes</a>
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


  body {
    /*font-family: 'Josefin Sans', sans-serif;*/
    /*font-family: 'Space Mono', monospace;*/
    font-family: 'Heebo', sans-serif;
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




<!-- ACTUALIZACION DE DEL CICLO ESCOLAR Y SUS ESTATUS -->
    



<!-- FIN DE ACTUALIZACION DE CICLO ESCOLAR Y ESTATUS -->

    
    <!--Main layout-->
    <main>

        
        <div class="container-fluid" id="contenedor_principal">


            <?php 

                include("inc/calculadora.php");

            ?>



 
      

            
            <!-- Jumbotron -->
            <div class="jumbotron black-text mx-2 mb-5" id="mainContainer">