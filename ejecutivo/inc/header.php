<?php  
  ob_start();
  require('cabeceras.php');
  include('funciones.php');

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8" />

    <title id="titulo_plataforma">
    </title>


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../img/iconEnde.png">
    <!-- App css -->

    <!-- jstree css -->
    <link href="assets/libs/jstree/themes/default/style.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/libs/treeview/style.css" rel="stylesheet" type="text/css" /> -->

    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- icons -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- third party css -->
    <link href="assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <!-- third party css end -->


    <!-- HANSONTABLE -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday@1.8.2/css/pikaday.css">

    <!-- SELECTORES -->
    <link href="assets/libs/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">

    <!-- Notification css (Toastr) -->
    <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/libs/mohithg-switchery/switchery.min.css" rel="stylesheet" type="text/css" />

    <!-- Dropify -->
    <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />

    <!-- Full s -->
    <link href="assets/libs/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Audiowide&display=swap" rel="stylesheet">
    
    
    <!-- ============================================================================ -->
    <!-- LAYOUT DE FILTROS / TOGGLE -->
    <!-- ============================================================================ -->
    <link href="assets/css/filters-layout.css" rel="stylesheet" type="text/css" />
    <!-- ============================================================================ -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

    <style>

        .custom-link {
            text-decoration: none;
        }
        .custom-link:hover {
            text-decoration: underline;
        }
        .badge-danger {
            background-color: #e74c3c; /* Rojo atenuado */
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-warning {
            background-color: #f0ad4e; /* Amarillo atenuado */
            color: black;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-success {
            background-color: #218838; /* Verde atenuado */
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-primary {
            background-color: #0056b3; /* Azul atenuado */
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-info {
            background-color: #138496; /* Cian atenuado */
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-secondary {
            background-color: #545b62; /* Gris atenuado */
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-dark {
            background-color: #343a40; /* Negro/gris oscuro */
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }


        .letraSicamInicio {
            font-family: "Audiowide", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 18px;
            display: block; /* Asegura que el texto tome su propia línea */
            margin-top: 10px; /* Espacio entre el spinner y el texto */
            color: white;
        }

        .letraSicam {
            font-family: "Audiowide", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 14px;
            display: block; /* Asegura que el texto tome su propia línea */
            margin-top: 10px; /* Espacio entre el spinner y el texto */
        }

        .letraMonday {
            font-family: "Archivo Black", sans-serif;
            font-weight: 400;
            font-style: normal;
            color: grey;
        }

        .prueba_posicion.show{
            position: inherit !important !important;
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2); /* semi-transparent white background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* ensure loader is on top */
            flex-direction: column;
        }
        #loader.hidden {
            display: none;
        }

        .spinner-border {
            animation-duration: 0.5s; /* 0.75s - 20% = 0.6s */
        }
    </style>

    <style type="text/css">
        <?php   
            if( $tipoUsuario == 'Ejecutivo' ){
        ?>
                body {
                    text-transform: uppercase;
                }
        <?php
            }
        ?>
        
    .letraDiminuta {
        font-size: 9px;
    }


    .letraPequena {
        font-size: 10px;
    }


    .bg-light {
        background-color: #e9ecef !important; /* Slightly darker than default Bootstrap light */
        color: black !important;
    }

    /* Ensure text remains visible during selection */
    .bg-light::selection {
        background-color: #F8F9FA;
        color: black;
    }

    body.lighten {
        background-color: #FFF;
        /* Color final claro */
        opacity: 1;
        /* Opacidad final */
    }

    @keyframes fadeInLogo {
        from {
            opacity: 0;
            transform: scale(0.5);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeOutLogo {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        /* Oscurece la pantalla */
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
</head>

<body class="loading" data-layout-mode="horizontal" data-layout-color="<?php echo $switch_ejecutivo; ?>"
    data-layout-size="fluid" data-topbar-color="dark" data-leftbar-position="scrollable" data-leftbar-color="gradient">


    <!-- Begin page -->
    <div id="wrapper">


        <!-- Topbar Start -->
        <div class="navbar-custom" style="background-color: #777; background-image: linear-gradient(to right, #777, #000);">

        <?php
           echo ($permisos == '1') ? '<span class="badge bg-success" style="position: fixed; top: 10px; right: -36px; transform: translate(-50%, -50%); z-index: 9999;">Permisos CDE</span>' : 
            (($permisos == '2') ? '<span class="badge bg-success" style="position: fixed; top: 10px; right: -36px; transform: translate(-50%, -50%); z-index: 9999;">Permisos AHJ ENDE</span>' : ''); 
        ?>


            <span class="badge bg-dark" style="position: fixed; top: 10px; left: 100px; transform: translate(-50%, -50%); z-index: 9999;">
                <?php echo obtenerSemanaTrabajo( $fechaHoy ); ?>
            </span>
            
            
            <div class="container">
                <ul class="list-unstyled topnav-menu float-end mb-0">

                    <li class="d-none d-lg-block">
                        <form class="app-search" id="formulario_buscador_citas">
                            <div class="app-search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar..." id="searchInput2">
                                    <button class="btn input-group-text" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>

                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                         
                            <form id="formulario_buscador_citas2" class="p-3">
                                <div class="input-group">
                                    
                                    <input type="text" class="form-control" placeholder="Buscar..."
                                    aria-label="Buscar..." id="searchInput3">

                                </div>
                            </form>
                            
                        </div>
                    </li>

                    

                            <!-- NOTIFICACIONES PRESIDENCIA -->
                            <?php 
                            if( $id == 2311 ){
                                $sqlNotificaciones = "
                                    SELECT * FROM notificacion_pago WHERE est_not_pag = 'Pendiente2'
                                ";
                                $resultado = $db->query($sqlNotificaciones);
                                $totalNotifiaciones = $resultado->num_rows;
                            ?>

                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                                    role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="fe-bell noti-icon"></i>
                                    <span class="badge bg-danger rounded-circle noti-icon-badge"><?php echo $totalNotifiaciones; ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5 class="m-0">
                                            <span class="float-end">
                                                <!-- <a href="" class="text-dark">
                                                    <small>Limpiar todo</small>
                                                </a> -->
                                            </span>Notificaciones
                                        </h5>
                                    </div>

                                    <div class="noti-scroll" data-simplebar>
                                        <?php
                                        while($row = $resultado->fetch_assoc()){
                                            $motivo = $row['mot_not_pag'];
                                        ?>
                                            <!-- item-->
                                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                                <div class="notify-icon bg-primary">
                                                    <i class="mdi mdi-bell-outline"></i>
                                                </div>

                                                <p class="text-muted mb-0 user-msg modal_notificaciones">
                                                    <small><?php echo $motivo; ?></small>
                                                </p>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <!-- All-->
                                    <a href="javascript:void(0);"
                                        class="dropdown-item text-center text-primary notify-item notify-all modal_notificaciones">
                                        Ver todo
                                        <i class="fe-arrow-right"></i>
                                    </a>
                                </div>
                            </li>

                            <?php        
                            } else {
                            ?>
                                <!--  -->
                                <li class="dropdown notification-list topbar-dropdown" id="contenedor_notificaciones_ejecutivo">
                                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                                        role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="fe-bell noti-icon"></i>
                                        <span class="badge bg-danger rounded-circle noti-icon-badge" id="badge_contador_notificaciones" style="background-color: #dc3545 !important; font-weight: bold;">0</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-lg" style="background-color: #ffffff !important; opacity: 1 !important;">
                                        <!-- item-->
                                        <div class="dropdown-item noti-title">
                                            <h5 class="m-0">
                                                <span class="float-end">
                                                    <a href="javascript:void(0);" class="text-dark" id="limpiar_todas_notificaciones">
                                                        <small>Limpiar todo</small>
                                                    </a>
                                                </span>Notificaciones
                                            </h5>
                                        </div>

                                        <div class="noti-scroll" data-simplebar id="lista_notificaciones_contenedor" style="max-height: 240px; overflow-y: auto;">
                                            <!-- Aquí se cargarán las notificaciones via AJAX -->
                                        </div>
                                        
                                        <div id="loading_notificaciones" style="display: none; text-align: center; padding: 10px;">
                                            <small class="text-muted">Cargando más notificaciones...</small>
                                        </div>
                                    </div>
                                </li>
                                <!--  -->
                            <?php
                                }                         
                            ?>
                            <!-- F NOTIFICACIONES PRESIDENCIA -->
                            

                      

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            
                            <span class="pro-user-name ms-1">
                                <?php echo $nombre; ?> <i class="mdi mdi-chevron-down"></i>
                            </span>

                            <?php
                                if( $usuario == null ){
                                    echo obtener_rango_usuario_badge( $rangoUsuario );
                                } else {
                                    echo obtener_usuario_ejecutivo($usuario, $estatusUsuario);
                                }
                            ?>

                            
                            <?php
                                if( $tipoUsuario == 'Ejecutivo' && $foto == NULL ){
                            ?>
                                    <img src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>"
                                    alt="user-image" class="rounded-circle" id="foto_usuario">
                            <?php
                                } else if( $tipoUsuario == 'Ejecutivo' && $foto != NULL ){
                            ?>
                                    <img src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>"
                                    alt="user-image" class="rounded-circle" id="foto_usuario">
                            <?php
                                } else {
                            ?>
                                    <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png"
                                    alt="user-image" class="rounded-circle" id="foto_usuario">
                            <?php
                                }
                            ?>

                        </a>
                        
                        <!--  -->
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Bienvenido, <?php echo $nombre; ?></h6>
                            </div>

                            <!-- item-->
                            <a href="perfil.php" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Mi perfil</span>
                            </a>

                            <!-- item-->
                            <!-- <a class="dropdown-item notify-item" href="mensajeria.php">
                                <i class="fas fa-envelope"></i>
                                <span>Mensajería</span>
                            </a> -->

                            <!-- item-->
                            <a class="dropdown-item notify-item" href="p100c.php">
                                <i class="fas fa-download"></i>
                                <span>P100C-33 - REPOSITORIO</span>
                            </a>

                            <?php 
                                if( ( $rangoUsuario == 'GC' && $usuario != NULL ) || ( $rangoUsuario == 'DM' && $usuario != NULL ) || ( $rangoUsuario == 'DC' && $usuario != NULL) ){
                            ?>
                                    <a class="dropdown-item notify-item" href="repositorio_plantel.php">
                                        <i class="fas fa-folder"></i>
                                        <span>CARPETA</span>
                                    </a>
                            <?php
                                }
                            ?>
                            
                            <!-- item CARRUSEL -->
                            <a href="#" class="dropdown-item notify-item" id="abrir-carrusel">
                                <i class="fas fa-images"></i>
                                <span>CARRUSEL</span>
                            </a>

                            <a href="#" class="dropdown-item notify-item" id="ver-video">
                                <i class="fas fa-play-circle"></i>
                                <span>SIGED</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <?php
                                if ( $tipoUsuario != 'Ejecutivo' ){
                            ?>
                                    <a class="dropdown-item notify-item" href="#" id="subir_aviso">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>Agregar aviso</span>
                                    </a>
                            <?php
                                }
                            ?>
                            <script>
                                document.getElementById('ver-video').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    
                                    // Crear el contenedor del video
                                    const videoOverlay = document.createElement('div');
                                    videoOverlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;';
                                    
                                    // Crear el video
                                    const video = document.createElement('video');
                                    video.src = '../img/siged.mp4';
                                    video.style.cssText = 'max-width: 90%; max-height: 90%;';
                                    video.controls = true;
                                    video.autoplay = true;
                                    
                                    // Agregar botón de cerrar
                                    const closeButton = document.createElement('button');
                                    closeButton.innerHTML = '×';
                                    closeButton.style.cssText = 'position: absolute; top: 20px; right: 20px; background: none; border: none; color: white; font-size: 30px; cursor: pointer;';
                                    
                                    // Manejar el cierre
                                    closeButton.onclick = function() {
                                        document.body.removeChild(videoOverlay);
                                    };
                                    
                                    // Ensamblar y mostrar
                                    videoOverlay.appendChild(video);
                                    videoOverlay.appendChild(closeButton);
                                    document.body.appendChild(videoOverlay);
                                });
                            </script>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="cerrar_sesion.php" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Cerrar sesión</span>
                            </a>

                        </div>
                        <!--  -->
                    </li>
<!-- 
                    <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="fe-settings noti-icon"></i>
                        </a>
                    </li> -->

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="home.php" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="../img/iconEnde.png" alt="" height="44">
                        </span>
                        <span class="logo-lg">
                            <img src="../img/logoLoginEslogan.png" alt="" height="66">
                        </span>
                    </a>
                    <a href="home.php" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="../img/iconEnde.png" alt="" height="44">
                        </span>
                        <span class="logo-lg">
                            <img src="../img/logoLoginEslogan.png" alt="" height="66">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">

                    <li>
                        <!-- Mobile menu toggle (Horizontal Layout)-->
                        <a class="navbar-toggle nav-link" data-bs-toggle="collapse"
                            data-bs-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                </ul>

                <div class="clearfix"></div>

            </div>

        </div>
        <!-- end Topbar -->

        <!-- topnav-->
        <div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav letraPequena">
                            

                        <?php
                            // Mostrar HOME si:
                            // 1. Es usuario administrativo ($usuario != null)
                            // 2. Es usuario admisiones ($usuario == null) Y tiene planteles
                            
                            $mostrarHome = false;
                            
                            if($usuario != null) {
                                // Usuario administrativo
                                $mostrarHome = true;
                            } else if($usuario == null) {
                                // Usuario admisiones - verificar planteles
                                $sqlCheckPlanteles = "
                                    SELECT COUNT(*) as total 
                                    FROM planteles_ejecutivo 
                                    WHERE id_eje = '$id'
                                ";
                                $resultCheck = mysqli_query($db, $sqlCheckPlanteles);
                                $filaCheck = mysqli_fetch_assoc($resultCheck);
                                
                                if($filaCheck['total'] > 0) {
                                    $mostrarHome = true;
                                } else {
                                    // Verificar plantel por defecto
                                    $sqlCheckDefault = "
                                        SELECT id_pla 
                                        FROM ejecutivo 
                                        WHERE id_eje = '$id' 
                                        AND id_pla IS NOT NULL
                                    ";
                                    $resultDefault = mysqli_query($db, $sqlCheckDefault);
                                    if(mysqli_num_rows($resultDefault) > 0) {
                                        $mostrarHome = true;
                                    }
                                }
                            }
                            
                            if($mostrarHome) {
                        ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link arrow-none" href="home.php" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        HOME
                                    </a>
                                </li>
                        <?php
                            }
                        ?>
                            


                            <?php 
                                if( $tipoUsuario != 'Ejecutivo' ){
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="grupos.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Grupos
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="alumnos.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Alumnos
                                </a>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="usuarios.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Usuarios
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="egresos.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Gastos
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Reportes <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                    <a href="reporte_concentrado.php" class="dropdown-item">Concentrado semana</a>
                                    <a href="reporteria_cobranza.php" class="dropdown-item">Corte del día</a>

                                    <!-- <a href="reporte_azul.php" class="dropdown-item">Reporte de cobranza semana</a> -->
                                </div>
                            </li>

                                <?php
                                    }
                                ?>
                            
                            <!-- PERMISOS SEGMENTACION -->
                            <?php 
                                if( $usuario != '' ){
                            ?>  
                                    <!-- USUARIO EJECUTIVO CON PERMISOS -->
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                AREA ADMISIONES <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                                <a href="contactos.php" class="dropdown-item">CONTACTOS</a>
                                                <a href="citas_administracion.php" class="dropdown-item">CITAS ADMINISTRATIVAS</a>
                                                <a href="citas_admisiones.php" class="dropdown-item">CITAS ADMISIONES</a>
                                                <a href="registros.php" class="dropdown-item">REGISTROS</a>
                                                <a href="estructuras_comerciales.php" class="dropdown-item">ESTRUCTURAS COMERCIALES</a>
                                                <a href="reporteria_citas.php" class="dropdown-item">REPORTERÍA CITAS</a>
                                            </div>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                AREA ADMINISTRATIVA <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                                <a href="grupos.php" class="dropdown-item">GRUPOS</a>
                                                <a href="alumnos.php" class="dropdown-item">ALUMNOS</a>
                                                <a href="cobranza.php" class="dropdown-item">COBRANZA</a>
                                                
                                                <a href="gastos.php" class="dropdown-item">GASTOS</a>
                                            </div>
                                        </li>
                                        <!-- NO ME DEJO METER MÁS DE 3 ELEMENTOS EN EL DROPDOWN -->

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                AREA MENTORÍA <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                                <a href="horarios.php" class="dropdown-item">HORARIOS</a>
                                                <a href="programas.php" class="dropdown-item">PROGRAMAS</a>
                                                <a href="profesores.php" class="dropdown-item">PROFESORES</a>
                                                <!-- <a href="profesores.php" class="dropdown-item">PROFESORES</a> -->
                                            </div>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                REPORTERÍA<div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                                <a href="reporte_cobranza_gastos.php" class="dropdown-item">COBRANZA Y GASTOS</a>
                                                <a href="planeacion_inicios.php" class="dropdown-item">PLANEACIÓN DE INICIOS</a>
                                                <!-- <a href="planeacion_fechas.php" class="dropdown-item">PLANEACIÓN DE INICIOS - FECHAS DE SEMANAS</a> -->
                                                <a href="reporte_semanal.php" class="dropdown-item">REPORTE SEMANAL</a>
                                                <a href="tramites.php" class="dropdown-item">CALENDARIO TRÁMITES</a>
                                                <a href="reporte_sabanas.php" class="dropdown-item">SÁBANA MENSUAL</a>

                                            </div>
                                        </li>
                                    <!-- F USUARIO EJECUTIVO CON PERMISOS -->

                                <?php
                                    } else {
                                ?>
                                    <!-- USUARIO EJECUTIVO SIN PERMISOS -->
                                        <!-- <li class="nav-item dropdown">
                                            <a class="nav-link arrow-none" href="referidos.php" id="topnav-dashboard" role="button"
                                                aria-haspopup="true" aria-expanded="false">
                                                Contactos
                                            </a>
                                        </li> -->
                                        
                                        <li class="nav-item dropdown">
                                            <a class="nav-link arrow-none" 
                                            href="contactos.php" 
                                            id="topnav-contactos" 
                                            role="button"
                                            onclick="event.preventDefault(); event.stopPropagation(); window.location.href='contactos.php'; return false;"
                                            onmousedown="window.location.href='contactos.php';"
                                            style="pointer-events: auto !important; z-index: 9999 !important; position: relative !important; cursor: pointer !important;"
                                            aria-haspopup="false" 
                                            aria-expanded="false">
                                                CONTACTOS
                                            </a>
                                        </li>

                                        <?php 
                                            if( $rangoUsuario == 'DM' || $rangoUsuario == 'GR' ){
                                        ?>
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link arrow-none" href="citas_administracion.php" id="topnav-dashboard" role="button"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        CITAS ADMINISTRATIVAS
                                                    </a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="nav-link arrow-none" href="citas_admisiones.php" id="topnav-dashboard" role="button"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        CITAS ADMISIONES
                                                    </a>
                                                </li>
                                        <?php
                                            } else {
                                        ?>
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link arrow-none" href="citas_admisiones.php" id="topnav-dashboard" role="button"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        CITAS ADMISIONES
                                                    </a>
                                                </li>
                                        <?php
                                            }
                                        ?>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link arrow-none" href="registros.php" id="topnav-dashboard" role="button"
                                                aria-haspopup="true" aria-expanded="false">
                                                Registros
                                            </a>
                                        </li>
                                            

                                        <?php 
                                            if( $rangoUsuario == 'GC' || $rangoUsuario == 'DM' ){
                                        ?>
                                                
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link arrow-none" href="estructuras_comerciales.php" id="topnav-dashboard"
                                                        role="button" aria-haspopup="true" aria-expanded="false">
                                                        Estructuras Comerciales
                                                    </a>
                                                </li>


                                                <li class="nav-item dropdown">
                                                    <a class="nav-link arrow-none" href="reporteria_citas.php" id="topnav-dashboard"
                                                        role="button" aria-haspopup="true" aria-expanded="false">
                                                        REPORTE CITAS
                                                    </a>
                                                </li>
                                        <?php
                                            }
                                        ?>

                                        
                                    <!-- F USUARIO EJECUTIVO SIN PERMISOS -->
                            <?php
                                }
                            ?>
                            <!-- F PERMISOS SEGMENTACION -->

                            

                            

                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="citas.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Citas
                                </a>
                            </li> -->

                            

                            <?php  
                                    if ( $rangoUsuario == 'LC' || $rangoUsuario == 'GR' ) {
                            ?>
                            <li class="nav-item dropdown" style="display: none;">
                                <a class="nav-link arrow-none" href="pac.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    PAC
                                </a>
                            </li>


                            <?php
                                    }
                                ?>

                            <?php  
                                    if ( $rangoUsuario == 'DC' || $rangoUsuario == 'GC' || $tipoUsuario == 'Dirección' ) {
                            ?>
                                    <li class="nav-item dropdown" style="display: none;">
                                        <a class="nav-link arrow-none" href="pac.php" id="topnav-dashboard" role="button"
                                            aria-haspopup="true" aria-expanded="false">
                                            PAC
                                        </a>
                                    </li>
                            
                           
                                    
                                    <?php /** 
                                        <?php 
                                            if( $id == 2311 ){
                                        ?> 
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link arrow-none" href="estructuras_comerciales.php" id="topnav-dashboard"
                                                        role="button" aria-haspopup="true" aria-expanded="false">
                                                        Estructuras Comerciales
                                                    </a>
                                                </li>
                                        <?php
                                            }
                                        ?>

                                    */ ?>

                            
                                 
                            

                            <li class="nav-item dropdown" style="display: none;">
                                <a class="nav-link arrow-none" href="asesores.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Asesores
                                </a>
                            </li>

                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="reporte_general_citas.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Reporte Citas
                                </a>
                            </li> -->

                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="reporte_general_asesorias.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Reporte Asesorías
                                </a>
                            </li> -->


                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="esquema_comision.php" id="topnav-dashboard" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    Esquema de Comisión
                                </a>
                            </li> -->

                            <?php
                                    }

                                ?>





                        </ul> <!-- end navbar-->
                    </div> <!-- end .collapsed-->
                </nav>
            </div> <!-- end container-fluid -->
        </div> <!-- end topnav-->


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="">