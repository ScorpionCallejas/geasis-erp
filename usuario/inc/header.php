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
        
    <title id="titulo_plataforma">
      <?php
        echo $lugar; 
      ?>
    </title>

    <link rel="icon" href="../uploads/ende.png">


    <?php 
        require_once(  __DIR__."/../../includes/links_estilos.php");
    ?>

    
</head>

<body class="fixed-sn <?php echo $estilos_modo['body']; ?>" style="z-index: -1" id="mainBody">

    <!--Double navigation-->
    <header>
        <!-- Sidebar navigation -->
        
        <div id="slide-out" class="side-nav sn-bg-4 fixed">

          <ul class="scrollbar scrollbar-deep-blue" id="barra-side">
            
            <!-- Marco Foto -->
            <div class="row p-3">
              
              <div class="col-md-12 text-center" >
              
                <img src="../uploads/ende.png"  width="80%" class="p-3 z-depth-1" style="border-radius: 20px;">
              
              </div>
           
            </div>
            <!-- Fin marco foto -->


            <div class="row">
              
              <div class="col-md-12 text-center">


                <?php  
                  if ( $mod_adm == 'dark' ) {
                ?>
                    
                    <!-- Switch -->
                    <div class="switch round primary-switch">
                      <label>
                        <input type="checkbox" id="btn_modo" checked="checked">
                        <span class="lever"></span>
                        </label>
                    </div>
               

                <?php
                  } else {
                ?>
                    
                    <!-- Switch -->
                    <div class="switch round primary-switch">
                      <label>
                        <input type="checkbox" id="btn_modo">
                        <span class="lever"></span>
                      </label>
                    </div>

                <?php
                  }
                ?>
                


              </div>
            
            </div>
          
            <hr>

                <!-- Side navigation links -->
                <li>

                    <ul class="custom-scrollbar list-unstyled ps ps--active-y collapsible collapsible-accordion">

                        <li>

                          <a class="collapsible-header waves-effect arrow-r"><i class="fas fa-chevron-right"></i> Dirección<i class="fas fa-angle-down rotate-icon"></i></a>
                            <div class="collapsible-body">
                                <ul class="list-unstyled">
                                    

                                    <?php  
                                      if ( $tipo == 'Super' ) {
                                    ?>

                                        <li>
                                            <a href="usuarios.php" class="waves-effect" title="Define usuarios">Usuarios</a>
                                        </li>


                                        <li>
                                            <a href="planteles.php" class="waves-effect" title="Planteles">CDEs</a>
                                        </li>


                                        <li>
                                            <a href="reportes.php" class="waves-effect" title="Reportería general">Reportes</a>
                                        </li>

                                        <li>
                                            <a href="encuestas.php" class="waves-effect" title="Encuestas">Encuestas</a>
                                        </li>


                                        <li>
                                            <a href="certificaciones.php" class="waves-effect" title="Certificaciones">Certificaciones</a>
                                        </li>

                                    <?php
                                      }
                                    ?>
                                    

                                    <?php  
                                      if ( $tipo == 'Admin' ) {
                                    ?>
                                        
                                        <li>
                                            <a href="usuarios.php" class="waves-effect" title="Define usuarios">Usuarios</a>
                                        </li>

                                        <li>
                                            <a href="reportes.php" class="waves-effect" title="Reportería general">Reportes</a>
                                        </li>
                                    <?php
                                      }
                                    ?>

                                    <li>
                                        <a href="panel_seguridad.php" class="waves-effect">Panel de Seguridad</a>
                                    </li>

                                </ul>
                            </div>
                        </li>


                      

                        <li>
                          <a class="collapsible-header waves-effect arrow-r">
                            <i class="fas fa-chevron-right"></i> 
                            Gestión Escolar<i class="fas fa-angle-down rotate-icon"></i></a>
                            <div class="collapsible-body">
                                <ul class="list-unstyled">
                                    <li>
                                      <?php  
                                        if ( $tipo == 'Super' ) {
                                      ?>

                                          <a href="alumnos.php" class="waves-effect" title="Ver Alumnos">Alumnos</a>
                                          <a href="horarios2.php" class="waves-effect" title="Ver todos los horarios">Grupos</a>

                                      <?php
                                        } else if ( $tipo == 'Admin' || $tipo == 'Adminge' ) {
                                      ?>

                                          <a href="alumnos.php" class="waves-effect" title="Ver Alumnos">Alumnos</a>
                                          <a href="ramas.php" class="waves-effect" title="Ver oferta educativa">Programas</a>
                                          <a href="profesores.php" class="waves-effect" title="Ver Profesores">Profesores</a>
                                          <a href="salones.php" class="waves-effect" title="Ver Salones">Salones</a>
                                          <a href="horarios.php" class="waves-effect" title="Ver todos los horarios">Grupos</a>

                                      <?php
                                        } else if ( $tipo == 'Caja' ) {
                                      ?>

                                          <a href="alumnos.php" class="waves-effect" title="Ver Alumnos">Alumnos</a>

                                      <?php
                                        }
                                      ?>
                                        
                                       
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <?php  
                          if ( $tipo == 'Admin' || $tipo == 'Caja' ) {
                        ?>

                            <li>
                              <a class="collapsible-header waves-effect arrow-r"><i class="fas fa-chevron-right"></i> Servicios empresariales <?php echo obtener_badge_nuevo('2021'); ?><i class="fas fa-angle-down rotate-icon"></i></a>
                                <div class="collapsible-body">
                                    <ul class="list-unstyled">
                                        


                                        <li>
                                            <a href="cobranza.php" class="waves-effect">Caja <?php echo obtener_badge_nuevo('2021'); ?></a>
                                        </li>


                                        <li>
                                            <a href="egresos.php" class="waves-effect">Egresos <?php echo obtener_badge_nuevo('2021'); ?></a>
                                        </li>

                                        

                                    </ul>
                                </div>
                            </li>
                           
                        <?php
                          }
                        ?>
                        
                        <?php  
                          if ( $tipo == 'Super' || $tipo == 'Admin' ) {
                        ?>

                            <li>
                                <a class="collapsible-header waves-effect arrow-r ">
                                  <i class="fas fa-chevron-right"></i> Área Comercial<i class="fas fa-angle-down rotate-icon"></i>
                                </a>

                                <div class="collapsible-body">
                                    <ul class="list-unstyled">
                                     

                                        <li>
                                            <a href="area_comercial.php" class="waves-effect">Área comercial</a>
                                        </li>

                                        
                                    </ul>
                                </div>
                            </li>    
                             
                        <?php
                          }
                        ?>
                        
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
        <nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav <?php echo $estilos_modo['navbar']; ?>" id="mainNabvar">




          <span class="badge blue-grey darken-4 font-weight-normal" title="Semana de trabajo..." style="font-size: 11px; position: absolute; top: 0px; right: 30px;">
            
            <?php  
              obtenerSemana( date('Y-m-d') );
            ?>
          
          </span>


          <span class="badge badge-pill blue-grey darken-4 font-weight-normal" title="Semana de trabajo..." style="font-size: 11px; position: absolute; top: 0px; left: 45%;">
            
            <?php  
              echo obtener_tipo_usuario( $tipo );
            ?>

          </span>

            <!-- SideNav slide-out button -->
            <div class="float-left">
                <a href="#" data-activates="slide-out" class="button-collapse"><i class="fas fa-bars"></i></a>
            </div>
            <!-- Breadcrumb-->
            

            <img src="../img/logoLogin2.png" style="height: 50px; width: 140px;">

            <ul class="nav navbar-nav nav-flex-icons ml-auto">


                <?php  
                  if ( $tipo == 'Admin' || $tipo == 'Adminge' ) {
                ?>

                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <span class="clearfix d-none d-sm-inline-block">
                          <i class="fas fa-plus"></i> Agregar
                        </span>

                        <?php echo obtener_badge_nuevo('2021'); ?>
                        
                      </a>
                      
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                          
                        <a class="dropdown-item" href="#" id="btn_agregar_alumno" title="Agrega un nuevo alumno">
                          
                          <span class="clearfix d-none d-sm-inline-block">
                            Agregar alumno 
                          </span>

                        </a>


                        <a class="dropdown-item" href="#" id="btn_egreso" title="Agrega un concepto nuevo por egreso">
                          
                    
                          <span class="clearfix d-none d-sm-inline-block">
                            Agregar egreso/fondeo
                          </span>
                          <?php echo obtener_badge_nuevo('2021'); ?>
                        </a>



                        <a class="dropdown-item" href="#" id="subir_aviso" title="Agrega un nuevo Aviso para el alumno (limitado a uno)">
                          
                    
                          <span class="clearfix d-none d-sm-inline-block">
                            Subir aviso
                          </span>
                          <?php echo obtener_badge_nuevo('2021'); ?>
                        </a>



                          
                          
                      </div>
                    </li>
                <?php
                  }
                ?>
                

                

                <!-- MENSAJERIA -->
                <li class="nav-item dropdown">
                  
                  <div class="dropdown" id="contenedor_notificaciones_mensajeria">
                  </div>
                
                </li>
                <!-- FIN MENSAJERIA -->

                <?php if($tipo=='Admin'){

                ?>
                   <!--Obtencion de token -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle revision_token" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                          <span class="clearfix d-none d-sm-inline-block">
                            <i class="fas fa-key"></i> Ver token
                          </span>

                          <?php //echo obtener_badge_nuevo('2021'); ?>
                          
                        </a>
                    </li>
                    <!-- FIN token-->
                <?php 
                } ?>



                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img id="foto_usuario" width="25px" height="25px" src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>" alt="avatar" class="avatar rounded-circle mr-0 ml-3 z-depth-1"> <span class="clearfix d-none d-sm-inline-block"> <span class="clearfix d-none d-sm-inline-block"><?php echo $nombre; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                      

                        <a class="dropdown-item" href="#" id="btn_calculadora" title="Usar Calculadora"><i class="fas fa-calculator"></i> Calculadora</a>


                        
                        <a class="dropdown-item" href="perfil.php"><i class="fas fa-wrench"></i> Cuenta</a>
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



  /*input[type=date]{
    color: yellow;
  }*/


  .letraNumerica{
    
    font-family: 'Ubuntu Condensed', sans-serif;

  }


  .loader {
  --cube-size: 25px;
  
  position: relative;
  width: calc(var(--cube-size) * 2);
  height: calc(var(--cube-size) * 2);
}

.loader .cube {
  width: var(--cube-size);
  height: var(--cube-size);
  background: #1f4454;
  position: absolute;
  top: 0;
  left: 0;
  animation: loader-cube-1 2.4s .2s cubic-bezier(.72,.01,.01,1) infinite;
}

.loader .cube:nth-of-type(2) {
  background: #00a3db;
  right: 0;
  left: auto;
  animation-name: loader-cube-2;
  animation-delay: .4s;
}

.loader .cube:nth-of-type(3) {
  background: grey;
  bottom: 0;
  top: auto;
  animation-name: loader-cube-3;
  animation-delay: 0s;
}

@keyframes loader-cube-1 {
  from, to {
    transform: translate(0%);
  }
  25% {
    transform: translateY(100%);
  }
  50% {
    transform: translate(100%, 100%);
  }
  75% {
    transform: translate(100%);
  }
}

@keyframes loader-cube-2 {
  from, to {
    transform: translate(0);
  }
  25% {
    transform: translate(-100%);
  }
  50% {
    transform: translate(-100%, 100%);
  }
  75% {
    transform: translateY(100%);
  }
}

@keyframes loader-cube-3 {
  from, to {
    transform: translate(0);
  }
  25% {
    transform: translate(100%);
  }
  50% {
    transform: translate(100%,-100%);
  }
  75% {
    transform: translateY(-100%);
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

<div style="background: #2E2E2E; z-index: 99999999; width: 100%; height: 100%; " id="overlay">
    <div class="sk-cube-grid" style="height:60px;
      width:60px;
      position:fixed;
      left:48%;
      top:30%; z-index: 99999;">
      <div class="loader">
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
      </div>
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

        
        <div class="container-fluid">


            <?php 

                include("inc/calculadora.php");

            ?>



             <?php
                // CODIGO QUE SE ENCARGA DE GENERAR ESTATUS DE CICLOS ESCOLARES

                //$sqlEstatusCiclo = "SELECT * FROM ciclo WHERE ini_cic >= '2019-02-25' AND fin_cic <= '2019-05-30'";

                if ( $tipo == 'Admin' ) {
                  // 
                  $fechaHoy = date('Y-m-d');

                  $sqlCiclos = "
                      SELECT *
                      FROM ciclo
                      INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
                      INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                      WHERE id_pla = '$plantel'

                  ";

                  $resultadoCiclos = mysqli_query($db, $sqlCiclos);

                  while($filaCiclos = mysqli_fetch_assoc($resultadoCiclos)){
                      //echo $filaCiclos['nom_cic']."<br>";
                      if ($fechaHoy >= $filaCiclos['ins_cic'] && $fechaHoy <= $filaCiclos['ini_cic']) {
                          $id_cic = $filaCiclos['id_cic'];
                          $sqlUpdateEstatusCiclo = "
                              UPDATE ciclo SET est_cic = 'Inscripción' WHERE id_cic = '$id_cic'
                          ";

                          mysqli_query($db, $sqlUpdateEstatusCiclo);




                      }else if($fechaHoy > $filaCiclos['ini_cic'] && $fechaHoy <= $filaCiclos['fin_cic']){
                          $id_cic = $filaCiclos['id_cic'];
                          $sqlUpdateEstatusCiclo = "
                              UPDATE ciclo SET est_cic = 'Activo' WHERE id_cic = '$id_cic'
                          ";

                          mysqli_query($db, $sqlUpdateEstatusCiclo);


                      }else{
                        $id_cic = $filaCiclos['id_cic'];
                        $sqlUpdateEstatusCiclo = "
                            UPDATE ciclo SET est_cic = 'Inactivo' WHERE id_cic = '$id_cic'
                        ";

                        mysqli_query($db, $sqlUpdateEstatusCiclo);

                      }
                  }
                  // 
                }

                
            ?>


            <?php  
              if ( isset( $_SESSION['database'] ) ) {
            ?>
                <a href="" class="btn btn-block btn-danger waves-effect"  style="position: fixed;top: 12%; left: 0%; z-index: 1;" id="btn_salida"><i class="fas fa-user-secret"></i> Salir del respaldo</a>

            <?php
              }

            ?>

            
            <br>

            
            <!-- Jumbotron -->
            <div class=" black-text mx-2 mb-5 <?php echo $estilos_modo['container']; ?>" id="mainContainer">
            <div id="loader" style="display:none;">Cargando...</div>