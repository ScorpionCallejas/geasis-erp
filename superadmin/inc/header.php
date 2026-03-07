<?php  
  ob_start();
  require('cabeceras.php');
  include('funciones.php');
?>
<!doctype html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>
        <?php
            echo $nombreCadena; 
        ?> ☆ Superadmin
        </title>


        <link rel="icon" href="../uploads/<?php echo $fotoPlantel; ?>">

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
        <meta name="description" content="This is an example dashboard created using build-in elements and components.">
        <!-- Disable tap highlight on IE -->
        <meta name="msapplication-tap-highlight" content="no">
        <link href="../vendors_template/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link rel="stylesheet" href="https://bootswatch.com/5/quartz/bootstrap.css"> -->

        <link rel="stylesheet" href="../vendors_template/@fortawesome/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="../vendors_template/ionicons-npm/css/ionicons.css">
        <link rel="stylesheet" href="../vendors_template/linearicons-master/dist/web-font/style.css">
        <link rel="stylesheet" href="../vendors_template/pixeden-stroke-7-icon-master/pe-icon-7-stroke/dist/pe-icon-7-stroke.css">
        <link href="../styles_template/css/base.css" rel="stylesheet">

        <link href="../css/lightbox.css" rel="stylesheet">

        <style>
            .app-main__outer{
                max-width: 100%;
                background-color: #F1F4F6;
            }

            .main-card{
                border-radius: 20px;
            }


            .letraPequena{
                font-size: 10px;
            }

            

            .grey-text{
                color: grey;
            }

            .btn-rounded{
                border-radius: 15px;
            }

            
            .sash tr td{
              position: relative;
              overflow: hidden;
              width: 200px;
              height: 100px;
              
            }
            .sash tr td.prova:after {
                content: attr(data-ribbon);
                position: absolute;
                width: 90px;
                height: 40px;
                background: #428bca;
                font-size: 15px;
                color: white;
                top: -25px;
                text-align: center;
                line-height: 50px;
                right: -25px;
                transform: rotate(315deg);
              /*box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);*/
            }


            .grey-text{
                color: grey;
            }
        </style>

    </head>
  
    <body>

        <div class="app-container body-tabs-shadow fixed-header fixed-sidebar closed-sidebar-mobile closed-sidebar app-theme-gray">
            <div class="app-header header-shadow bg-premium-dark header-text-light">
                
                <div class="app-header__logo">

                    <div id="logoLogin" estatus="Activo">
                      <img src="../img/logoLogin34.png" style="width: 200px;">
                    </div>
                    <div class="header__pane ms-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar" id="btn_burger">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                
                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                
                <div class="app-header__content">
                    <div class="app-header-left">
                        <div class="search-wrapper">
                            <div class="input-holder">
                                <input type="text" class="search-input" placeholder="Buscar alumno...">
                                <button class="search-icon">
                                    <span></span>
                                </button>
                            </div>
                            <button class="btn-close"></button>
                        </div>
                        
                    </div>


                    <div class="app-header-right">
                     
                        <div class="header-btn-lg pe-0">
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="btn-group">
                                            <a data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                                <img width="42" class="rounded-circle" src="images_template/avatars/1.jpg" alt="">
                                                <i class="fa fa-angle-down ms-2 opacity-8"></i>
                                            </a>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                                <div class="dropdown-menu-header">
                                                    <div class="dropdown-menu-header-inner bg-asteroid">
                                                        <div class="menu-header-image opacity-2" style="background-image: url('images_template/dropdown-header/city3.jpg');"></div>
                                                        <div class="menu-header-content text-start">
                                                            <div class="widget-content p-0">
                                                                <div class="widget-content-wrapper">
                                                                    
                                                                    <div class="widget-content-left me-3">
                                                                        <img width="42" class="rounded-circle"
                                                                            src="images_template/avatars/1.jpg"  alt="">
                                                                    </div>

                                                                    <div class="widget-content-left">
                                                                        <div class="widget-heading"><?php echo $nombreUsuario; ?></div>
                                                                        <div class="widget-subheading opacity-8">Super-Administrador</div>
                                                                    </div>

                                                                    <div class="widget-content-right me-2">
                                                                        <a class="btn-pill btn-shadow btn-shine btn btn-focus" href="cerrar_sesion.php">Cerrar sesión</a>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="scroll-area-xs" style="height: 150px;">
                                                    <div class="scrollbar-container ps">
                                                        <ul class="nav flex-column">
                                                            
                                                            <li class="nav-item-header nav-item">
                                                                Mi cuenta
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="javascript:void(0);" class="nav-link">
                                                                    Ajustes
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="javascript:void(0);" class="nav-link">
                                                                    Mensajes
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="javascript:void(0);" class="nav-link">Logs</a>
                                                            </li>


                                                          
                                                        </ul>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content-left  ms-3 header-user-info">
                                        <div class="widget-heading"> <?php echo $nombreUsuario; ?></div>
                                        <div class="widget-subheading"> Super-Administrador <i class="fas fa-glasses"></i></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="app-main">
                <div class="app-sidebar sidebar-shadow bg-premium-dark sidebar-text-light">
                    <div class="app-header__logo">
                        <div class="logo-src"></div>
                        <div class="header__pane ms-auto">
                            <div>
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div>
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="app-header__menu">
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                    </div>
                    <div class="scrollbar-sidebar">
                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Navegación principal</li>


                                <li>
                                    <a href="index.php">

                                        <i class="metismenu-icon pe-7s-home"></i>
                                        Inicio
                                    </a>
                                </li>

                                <li>
                                    <a href="planteles.php">

                                        <i class="metismenu-icon pe-7s-culture"></i>
                                        CDEs
                                    </a>
                                </li>


                                <li>
                                    <a href="usuarios.php">

                                        <i class="metismenu-icon pe-7s-user"></i>
                                        Super-Administradores
                                    </a>
                                </li>


                                <li>
                                    <a href="usuarios.php">


                                        <i class="metismenu-icon pe-7s-add-user"></i>
                                        Usuarios
                                    </a>
                                </li>

                                <li>
                                    <a href="tokens.php">

                                        <i class="metismenu-icon pe-7s-key"></i>
                                        Tokens
                                    </a>
                                </li>                                

                                <li>
                                    <a href="charts-chartjs.html">
                                        <i class="metismenu-icon pe-7s-graph2"></i>
                                        Ventas
                                    </a>
                                </li>
                                <li>
                                    <a href="charts-apexcharts.html">
                                        <i class="metismenu-icon pe-7s-graph"></i>
                                        Cobranza
                                    </a>
                                </li>

                                <li>
                                    <a href="charts-sparklines.html">

                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        Documentación
                                    </a>
                                </li>

                                <li>
                                    <a href="grupos.php">
                                        <i class="metismenu-icon pe-7s-study"></i>
                                        Consultar grupos
                                    </a>
                                </li>


                                <li>
                                    <a href="encuestas.php">


                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Encuestas
                                    </a>
                                </li>


                                <li>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-study"></i>
                                        Grupos
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>

                                    <ul>
                                        
                                        <li>
                                            <a href="grupos.php">
                                                <i class="metismenu-icon"></i>
                                                Consultar grupos
                                            </a>
                                        </li>

                                    </ul>
                                </li>


                                <li>
                                    <a href="cerrar_sesion.php">
                                        Cerrar sesión
                                    </a>
                                </li>

                                <li class="app-sidebar__heading">Navegación académica</li>
                                <li class="mm-active">
                                    <a href="#">
                                        <!-- <i class="fas fa-graduation-cap"></i> -->
                                        <i class="metismenu-icon pe-7s-id"></i>
                                        Funciones académicas
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="index.php" class="mm-active">
                                                <i class="metismenu-icon"></i>
                                                Inicio
                                            </a>
                                        </li>

                                        <li>
                                            <a href="dashboards-sales.html">
                                                <i class="metismenu-icon"></i>
                                                Programas
                                            </a>
                                        </li>

                                        
                                        <li>
                                            <a href="alumnos.php">
                                                <i class="metismenu-icon"></i>
                                                Alumnos
                                            </a>
                                        </li>
                                        
                                        
                                        <li>
                                            <a href="dashboards-crm.html">
                                                <i class="metismenu-icon"></i>
                                                Profesores
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                
                            </ul>
                        </div>
                    </div>
                </div>