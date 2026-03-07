    <div class="app-drawer-overlay d-none animated fadeIn"></div>


    <div class="body-block-example-1 d-none">
        <div class="loader bg-transparent no-shadow p-0">
            <div class="ball-grid-pulse">
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
            </div>
        </div>
    </div>


    <!-- plugin dependencies -->
    <script type="text/javascript" src="../vendors_template/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../vendors_template/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../vendors_template/moment/moment.js"></script>
    <script type="text/javascript" src="../vendors_template/metismenu/dist/metisMenu.js"></script>
    <script type="text/javascript" src="../vendors_template/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
    <script type="text/javascript" src="../vendors_template/jquery-circle-progress/dist/circle-progress.min.js"></script>
    <script type="text/javascript" src="../vendors_template/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script type="text/javascript" src="../vendors_template/toastr/build/toastr.min.js"></script>
    <script type="text/javascript" src="../vendors_template/jquery.fancytree/dist/jquery.fancytree-all-deps.min.js"></script>
    <script type="text/javascript" src="../vendors_template/apexcharts/dist/apexcharts.min.js"></script>
    <script type="text/javascript" src="../vendors_template/bootstrap-table/dist/bootstrap-table.min.js"></script>
    <script type="text/javascript" src="../vendors_template/datatables.net/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../vendors_template/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="../vendors_template/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="../vendors_template/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="../vendors_template/slick-carousel/slick/slick.min.js"></script>
    <script type="text/javascript" src="../vendors_template/block-ui/jquery.blockUI.js"></script>
    
    <script type="text/javascript" src="../js_template/charts/apex-charts.js"></script>
    <script type="text/javascript" src="../js_template/circle-progress.js"></script>
    <script type="text/javascript" src="../js_template/demo.js"></script>
    <script type="text/javascript" src="../js_template/scrollbar.js"></script>
    <script type="text/javascript" src="../js_template/toastr.js"></script>
    <script type="text/javascript" src="../js_template/treeview.js"></script>
    <script type="text/javascript" src="../js_template/form-components/toggle-switch.js"></script>
    <script type="text/javascript" src="../js_template/tables.js"></script>
    <script type="text/javascript" src="../js_template/carousel-slider.js"></script>
    <script src="../js_template/app.js"></script>
    <script type="text/javascript" src="../js_template/blockui.js"></script>
    <script src="../js_template/hello.js"></script>

    <!-- MANEJO DE FECHAS Y HORAS -->
    <script src="../js/moment.min.js"></script>

    <!-- SWEET ALERTS -->
    <script src="../js/sweetalert.min.js"></script>

    <!-- LIGHT BOX IMAGENES SLIDER -->
    <script src="../js/lightbox.js"></script>

    <script src="../js/dataTables.buttons.min.js"></script>
    <script src="../js/jszip.min.js"></script>
    <script src="../js/pdfmake.min.js"></script>
    <script src="../js/vfs_fonts.js"></script>
    <script src="../js/buttons.html5.min.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        $("#btn_burger").on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            
            var estatus = $('#logoLogin').attr('estatus');

            if ( estatus == 'Activo' ) {

                $('#logoLogin').removeAttr('estatus').attr('estatus', 'Inactivo');
                $('#logoLogin').css('display', 'none');

            } else {

                $('#logoLogin').removeAttr('estatus').attr('estatus', 'Activo');
                $('#logoLogin').css('display', '');
            }
            


        });
    </script>


    <script>
        function obtener_validacion_superadmin( callback ){

            swal({
                title: "¡Acceso restringido!",
                icon: "warning",
                text: 'Necesitas permisos de Súper-administrador para continuar',
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Ingresa un correo...",
                        type: "text",
                    },
                },
                button: {
                    text: "Validar",
                    closeModal: false,
                },
            })
            .then(correo => {
                if (correo){
                    //console.log(name);
                    var correo = correo;
                    $.ajax({
                            
                        url: '../server/validacion_permisos_superadmin.php',
                        type: 'POST',
                        data: {correo},
                        success: function(respuesta){
                            console.log(respuesta);

                            if (respuesta == 'True') {
                            
                                console.log("Existe el correo");
                                // CORREO VALIDADO 
                                swal({
                                    title: "¡Acceso restringido!",
                                    icon: "warning",
                                    text: 'Necesitas permisos de Súper-administrador para continuar',
                                    content: {
                                        element: "input",
                                        attributes: {
                                            placeholder: "Ingresa un password...",
                                            type: "password",
                                        },
                                    },
                                    button: {
                                        text: "Validar",
                                        closeModal: false,
                                    },
                                })
                                .then(password => {
                                    if (password){
                                        //console.log(name);
                                        var password = password;
                                        $.ajax({
                                                
                                            url: '../server/validacion_permisos_superadmin.php',
                                            type: 'POST',
                                            data: {password, correo},
                                            success: function(respuesta){
                                                console.log(respuesta);

                                                if (respuesta == 'True') {
                                                    swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                        //
                                                        console.log("Existe correo y password");
                                                        // PASSWORD Y CORREO VALIDADO 
                                                        
                                                        callback();

                                                        // FIN PASSWORD Y CORREO VALIDADO
                                                    });
                                                    
                                                }else{
                                                    // PASSWORD NO EXISTE
                                                    swal({
                                                        title: "¡Datos incorrectos!",
                                                        text: 'No existe el password...',
                                                        icon: "error",
                                                        button: "Aceptar",
                                                    });
                                                    swal.stopLoading();
                                                    swal.close();
                                                }
                                            }
                                        });


                                    }else{
                                    // DATOS VACIOS
                                        swal({
                                            title: "¡Datos vacíos!",
                                            text: 'Necesitas ingresar un password...',
                                            icon: "error",
                                            button: "Aceptar",
                                        });
                                        swal.stopLoading();
                                        swal.close();
                                    }
                                });

                                // FIN CORREO VALIDADO
                            
                                
                            }else{
                                // CORREO NO EXISTE
                                swal({
                                    title: "¡Datos incorrectos!",
                                    text: 'No existe el correo...',
                                    icon: "error",
                                    button: "Aceptar",
                                });
                                swal.stopLoading();
                                swal.close();
                            }
                        }
                    });


                }else{
                // DATOS VACIOS
                    swal({
                        title: "¡Datos vacíos!",
                        text: 'Necesitas ingresar un correo...',
                        icon: "error",
                        button: "Aceptar",
                    });
                    swal.stopLoading();
                    swal.close();
                }
            });
        } 
    </script>


    <!-- LOADER -->
    <script>
        // BlockUI Loading

            $.blockUI.defaults = {
                timeout: 2000,
                fadeIn: 200,
                fadeOut: 400,
            };

            $.blockUI({ message: $(".body-block-example-1") });
            
            $(".block-page-btn-example-1").click(function () {
                $.blockUI({ message: $(".body-block-example-1") });
            });

            $(".block-page-btn-example-2").click(function () {
                $.blockUI({ message: $(".body-block-example-2") });
            });

            $(".block-page-btn-example-3").click(function () {
                $.blockUI({ message: $(".body-block-example-3") });
            });

            $(".block-element-btn-example-1").click(function () {
                $(".element-block-example").block({
                    message: $(
                        '<div class="loader mx-auto">\n' +
                        '                            <div class="ball-grid-pulse">\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        '                                <div class="bg-white"></div>\n' +
                        "                            </div>\n" +
                        "                        </div>"
                    ),
                });
            });

            $(".block-element-btn-example-2").click(function () {
                $(".element-block-example").block({
                    message: $(
                        "" +
                        '<div class="loader mx-auto">\n' +
                        '                            <div class="line-scale-pulse-out">\n' +
                        '                                <div class="bg-success"></div>\n' +
                        '                                <div class="bg-success"></div>\n' +
                        '                                <div class="bg-success"></div>\n' +
                        '                                <div class="bg-success"></div>\n' +
                        '                                <div class="bg-success"></div>\n' +
                        "                            </div>\n" +
                        "                        </div>"
                    ),
                });
            });

            $(".block-element-btn-example-3").click(function () {
                $(".element-block-example").block({
                    message: $(
                        '<div class="loader mx-auto">\n' +
                        '                            <div class="ball-pulse-sync">\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        "                            </div>\n" +
                        "                        </div>"
                    ),
                });
            });

    </script>
    <!-- FIN LOADER -->

        </div>
    </div>

  </body>
</html>