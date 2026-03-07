        </div> <!-- container -->

        </div> <!-- content -->
        
        
        <?php
            // 🎯 DETECTAR MODO CARRUSEL
            $modo_carrusel = isset($_GET['generaciones']) ? 'generacion' : 'plantel';
            $id_gen_carrusel = isset($_GET['generaciones']) ? $_GET['generaciones'] : '';
        ?>
        <!-- MODAL CARRUSEL -->
        <!-- MODAL CARRUSEL -->
        <div class="modal fade" id="modal_carrusel" tabindex="-1" role="dialog" aria-labelledby="modalCarruselLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalCarruselLabel">
                            <i class="fas fa-images"></i> Gestión de Carrusel
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="body_carrusel">
                        <!-- Aquí se cargará dinámicamente el contenido -->
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando carrusel...</p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- F MODAL CARRUSEL -->
        
        

        <!-- MODALES GLOBALES -->
        <div class="modal fade right" id="modal_aviso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
            <div class="modal-dialog modal-full-height modal-right" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Avisos</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span>
                            <p>Sube una imagen para que el alumno vea en su inicio y esté enterado de lo que necesites.</p>
                        </span>
                        <span style="color:red;">
                            <p><strong>NOTA: Esta función está limitada a SOLO UN AVISO.</strong></p>
                        </span>

                        <form enctype="multipart/form-data" id="upload_aviso">
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="md-form">
                                        <label for="imagen_aviso" class="form-label" style="top: -1.75rem;">Sube el aviso</label>
                                        <input class="form-control form-control-sm" id="imagen_aviso" type="file" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12">
                                    <!-- Material input -->
                                    <div class="md-form">
                                        <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Breve descripción:">
                                    </div>
                                    <?php if ($tipo == 'Adminge'){ ?>
                                    <div class="md-form">
                                        <i class="fas fa-info prefix"></i>
                                        <input type="text" id="liga" name="liga" class="form-control">
                                        <label for="liga">Agrega un<strong>LINK</strong> a tu imagen</label>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12">
                                <h4><span id="mensaje" class="text-success" style="display: none;"></span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="carga_aviso">Subir Aviso</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL NOTIFICACIONES -->
        <div id="modal_notificaciones" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="standard-modalLabel">NOTIFICACIONES</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">    
                            <span class="letraPequena">*NOTIFICACIONES PENDIENTES</span>
                            <hr>
                            <div id="contenedor_notificaciones"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
        </div>
        <!-- F MODAL NOTIFICACIONES -->
        <!-- FIN MODALES GLOBALES -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        
                        <span class="letraSicam letraPequena">
                            <script>
                                document.write(new Date().getFullYear());
                            </script> 
                            <a href="">♥ AHJ ENDE &copy;  <?php if( $tipoUsuario == 'Ejecutivo' ){ echo 'S.I.C.A.M. - SISTEMA DE INFORMACIÓN COMERCIAL, ADMINISTRATIVO Y DE MENTORÍA'; } ?></a>
                        </span>
                    </div>

                    <div class="col-md-6">
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">

            <div data-simplebar class="h-100">

                <div class="rightbar-title">

                    <h4 class="font-16 m-0 text-white">Personaliza tu espacio</h4>
                </div>

                <!-- Tab panes -->
                <div class="tab-content pt-0">

                    <div class="tab-pane active" id="settings-tab" role="tabpanel">

                        <div class="p-3">
                            <div class="alert alert-warning" role="alert">
                                <strong>Personaliza</strong> el esquema de colores general, diseño, etc.
                            </div>

                            <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Esquema de Colores</h6>
                            <div class="form-check form-switch mb-1">
                                <input type="checkbox" class="form-check-input switch_color" name="layout-color"
                                    value="light" id="light-mode-check" checked />
                                <label class="form-check-label" for="light-mode-check">Modo Claro</label>
                            </div>

                            <div class="form-check form-switch mb-1">
                                <input type="checkbox" class="form-check-input switch_color" name="layout-color"
                                    value="dark" id="dark-mode-check" />
                                <label class="form-check-label" for="dark-mode-check">Modo Oscuro</label>
                            </div>


                        </div>

                    </div>
                </div>


            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        

        <!-- Vendor -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- jstree js -->
        <script src="assets/libs/jstree/jstree.min.js"></script>


        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday@1.8.2/pikaday.min.js"></script>
        <script src="../ejecutivo/assets/hansontable/es-MX.js"></script>

        <script src="assets/libs/selectize/js/standalone/selectize.min.js"></script>
        <script src="assets/libs/mohithg-switchery/switchery.min.js"></script>
        <script src="assets/libs/multiselect/js/jquery.multi-select.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>

        <!-- Sweet Alerts js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

        <!-- third party js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <!-- third party js ends -->

        <!-- Datatables init -->
        <script src="assets/js/pages/datatables.init.js"></script>


        <!-- Toastr js -->
        <script src="assets/libs/toastr/build/toastr.min.js"></script>

        <!-- Init js-->
        <script src="assets/js/pages/form-advanced.init.js"></script>

        <!-- DROPIFY -->
        <script src="assets/libs/dropzone/min/dropzone.min.js"></script>
        <script src="assets/libs/dropify/js/dropify.min.js"></script>

        <!-- Calendar -->
        <script src="assets/libs/moment/min/moment.min.js"></script>
        <script src="assets/libs/fullcalendar/main.min.js"></script>

        <!-- JODIT -->
        <!-- EDITOR DE TEXTO -->
        <script src="../js/jodit.min.js"></script>
        
        
        <!-- GOOGLE CHARTS CDN -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    

        <script type="text/javascript">
            $(document).ready(function() {
                // OPTIONS DE TOASTR
                toastr.options = {
                    positionClass: 'toast-bottom-right'
                };
                // FIN OPTIONS DE TOASTR

                // ------
                // ------

                $('.switch_color').change(function() {
                    var swi_eje = this.value;

                    // if ( swi_eje == 'dark' ) {
                    //     $('#data-sheet').addClass('dark-table');
                    // } else {
                    //     $('#data-sheet').removeClass('dark-table').addClass('');
                    // }

                    $.ajax({
                        url: 'server/controlador_template.php',
                        type: 'POST',
                        data: {
                            swi_eje
                        },
                        success: function(respuesta) {
                            console.log(respuesta);
                        }
                    });
                });


            });
        </script>

        <script>
          
        </script>

        <script>
            function obtener_tarjeta_ejecutivo( id_eje, inicio, fin ){
            
                $.ajax({
                    type: 'POST',
                    url: 'server/obtener_tarjeta_ejecutivo.php',
                    data: { id_eje },
                    dataType: 'JSON',
                    success: function(data) {  
                        console.log(data);

                        $('#contenedor_tarjeta_ejecutivo').html(
                            '<ul class="list-group mb-0 user-list" style="padding: 2px; border-radius: 20px;">' +
                            '<li class="list-group-item">' +
                            '<a href="#" class="user-list-item">' +
                            '<div class="user avatar-sm float-start me-2">' +
                            '<img src="/uploads/' + data.fot_eje + '" alt="" class="img-fluid rounded-circle">' +
                            '</div>' +
                            '<div class="user-desc">' +
                            '<h5 class="name mt-0 mb-1">' +
                            '<div class="dropdown">' +
                            '<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' +
                            data.nom_eje + 
                            '</a>' +
                            '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                            '<li><h6 class="dropdown-header">Opciones</h6></li>' +
                            '<li><a class="dropdown-item" href="referidos.php?id_eje=' + data.id_eje + '&id_pla=' + data.id_pla + '&estructura=ejecutivo&inicio=' + inicio + '&fin=' + fin + '">Consultar Contactos</a></li>' +
                            '<li><a class="dropdown-item" href="citas.php?id_eje=' + data.id_eje + '&id_pla=' + data.id_pla + '&estructura=ejecutivo&inicio=' + inicio + '&fin=' + fin + '">Consultar Citas</a></li>' +
                            '<li><a class="dropdown-item" href="registros.php?id_eje=' + data.id_eje + '&id_pla=' + data.id_pla + '&estructura=ejecutivo&inicio=' + inicio + '&fin=' + fin + '">Consultar Registros</a></li>' +
                            '</ul>' +
                            '</div>' +
                            '</h5>' +
                            '<p class="desc text-muted mb-0 font-12">' + data.ran_eje + '</p>' +
                            '</div>' +
                            '</a>' +
                            '</li>' +
                            '</ul>'
                        );
                    }
                });
            }
        </script>


        <script>
            $('.modal_notificaciones').on('click', function(e){
                e.preventDefault();
                $('#modal_notificaciones').modal('show');

                obtener_notificaciones();
            });

            function obtener_notificaciones(){
                $.ajax({
                    type: 'POST', 
                    url: 'server/obtener_notificaciones.php',
                    success: function(respuesta) {
                        // console.log(respuesta);
                        $('#contenedor_notificaciones').html( respuesta );
                        //alert(data);
                        
                    }
                });
            }
        </script>


        <?php 
            if( $tipoUsuario == 'Dirección' ){
                obtener_notificacion_egresos( $db, $plantel );
                obtener_notificacion_estructura_comercial( $db, $plantel );
            }
            
        ?>


        <!-- ************************************************************************* -->
        <!-- NOTIFICACIONES EJECUTIVO -->
        <div id="modal_notificaciones_ejecutivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_notificacion_detalle">DETALLE NOTIFICACIÓN</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div id="contenedor_notificacion_ejecutivo">
                            <!-- Aquí se cargará el contenido completo de la notificación específica -->
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>



        <!-- SCRIPT -->
        <script>
            // Variables para el scroll infinito
            var offset_notificaciones = 0;
            var cargando_notificaciones = false;

            // Cargar notificaciones al abrir el dropdown
            $('#contenedor_notificaciones_ejecutivo').on('show.bs.dropdown', function() {
                console.log('Dropdown abierto - cargando notificaciones...');
                offset_notificaciones = 0; // Reset offset
                obtener_notificaciones_ejecutivo();
            });

            // Cargar contador al iniciar la página
            console.log('Página cargada - obteniendo contador...');
            obtener_contador_notificaciones_ejecutivo();

            // Detectar scroll en el contenedor de notificaciones
            $(document).on('scroll', '#lista_notificaciones_contenedor', function() {
                var contenedor = $(this);
                var scroll_top = contenedor.scrollTop();
                var altura_contenedor = contenedor.height();
                var altura_contenido = contenedor[0].scrollHeight;
                
                // Si está cerca del final (90%) y no está cargando
                if (scroll_top + altura_contenedor >= altura_contenido * 0.9 && !cargando_notificaciones) {
                    console.log('Llegó al final del scroll - cargando más...');
                    cargar_mas_notificaciones();
                }
            });

            // Función para obtener contador de notificaciones pendientes
            function obtener_contador_notificaciones_ejecutivo() {
                console.log('Iniciando petición contador...');
                $.ajax({
                    type: 'POST',
                    url: 'server/obtener_notificaciones_ejecutivo.php',
                    data: { accion: 'contador' },
                    success: function(respuesta) {
                        console.log('Respuesta contador:', respuesta);
                        $('#badge_contador_notificaciones').text(respuesta);
                        
                        // Mostrar/ocultar badge
                        if(respuesta > 0) {
                            console.log('Mostrando badge con:', respuesta);
                            $('#badge_contador_notificaciones').show();
                        } else {
                            console.log('Ocultando badge');
                            $('#badge_contador_notificaciones').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error en contador:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            }

            // Función para obtener lista inicial de notificaciones
            function obtener_notificaciones_ejecutivo() {
                console.log('Iniciando petición lista...');
                $.ajax({
                    type: 'POST',
                    url: 'server/obtener_notificaciones_ejecutivo.php',
                    data: { accion: 'listar' },
                    success: function(respuesta) {
                        console.log('Respuesta lista:', respuesta);
                        $('#lista_notificaciones_contenedor').html(respuesta);
                        offset_notificaciones = 10; // Siguiente offset
                    },
                    error: function(xhr, status, error) {
                        console.log('Error en lista:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                        $('#lista_notificaciones_contenedor').html('<div class="dropdown-item">Error al cargar notificaciones</div>');
                    }
                });
            }

            // Función para cargar más notificaciones (scroll infinito)
            function cargar_mas_notificaciones() {
                if (cargando_notificaciones) return;
                
                cargando_notificaciones = true;
                $('#loading_notificaciones').show();
                
                console.log('Cargando más notificaciones, offset:', offset_notificaciones);
                
                $.ajax({
                    type: 'POST',
                    url: 'server/obtener_notificaciones_ejecutivo.php',
                    data: { 
                        accion: 'cargar_mas',
                        offset: offset_notificaciones
                    },
                    success: function(respuesta) {
                        console.log('Respuesta cargar más:', respuesta);
                        
                        // Si hay contenido, agregarlo
                        if(respuesta.trim() !== '' && !respuesta.includes('No hay más notificaciones')) {
                            $('#lista_notificaciones_contenedor').append(respuesta);
                            offset_notificaciones += 10; // Incrementar offset
                        } else {
                            console.log('No hay más notificaciones para cargar');
                        }
                        
                        $('#loading_notificaciones').hide();
                        cargando_notificaciones = false;
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al cargar más:', error);
                        $('#loading_notificaciones').hide();
                        cargando_notificaciones = false;
                    }
                });
            }

            // Click en notificación específica para ver detalle
            $(document).on('click', '.notificacion_item', function(e) {
                e.preventDefault();
                var id_notificacion = $(this).data('id');
                console.log('Click en notificación ID:', id_notificacion);
                
                // Mostrar modal con detalle completo
                $('#modal_notificaciones_ejecutivo').modal('show');
                
                // Cargar contenido y marcar como leída
                obtener_detalle_notificacion_ejecutivo(id_notificacion);
            });

            // Función para obtener detalle completo de notificación
            function obtener_detalle_notificacion_ejecutivo(id_notificacion) {
                console.log('Obteniendo detalle para ID:', id_notificacion);
                $.ajax({
                    type: 'POST',
                    url: 'server/obtener_notificaciones_ejecutivo.php',
                    data: { 
                        accion: 'detalle',
                        id_notificacion: id_notificacion
                    },
                    success: function(respuesta) {
                        console.log('Respuesta detalle:', respuesta);
                        $('#contenedor_notificacion_ejecutivo').html(respuesta);
                        
                        // Actualizar contador después de marcar como leída
                        obtener_contador_notificaciones_ejecutivo();
                    },
                    error: function(xhr, status, error) {
                        console.log('Error en detalle:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            }

            // Limpiar todas las notificaciones
            $('#limpiar_todas_notificaciones').on('click', function(e) {
                e.preventDefault();
                console.log('Limpiando todas las notificaciones...');
                
                $.ajax({
                    type: 'POST',
                    url: 'server/obtener_notificaciones_ejecutivo.php',
                    data: { accion: 'limpiar_todas' },
                    success: function(respuesta) {
                        console.log('Respuesta limpiar:', respuesta);
                        obtener_contador_notificaciones_ejecutivo();
                        obtener_notificaciones_ejecutivo();
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al limpiar:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            });
        </script>
        <!-- F SCRIPT -->
        <!-- F NOTIFICACIONES EJECUTIVO -->
        <!-- F *********************************************************************** -->
        
        <!-- CARRUSEL -->
        <script>
        $(document).ready(function() {
            
            var modoActual = '';
            var idGenActual = '';
            var intervalPreview = null;
            
            // 🎯 ABRIR MODAL
            $('#abrir-carrusel').on('click', function(e) {
                e.preventDefault();
                
                modoActual = $('body').data('carrusel-modo');
                idGenActual = $('body').data('carrusel-id-gen');
                
                console.log('🎯 Abriendo modal:', { modo: modoActual, id_gen: idGenActual });
                
                $('#body_carrusel').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border" style="color: #000;" role="status"></div>
                        <p class="mt-2" style="color: #6c757d; font-size: 12px;">Cargando...</p>
                    </div>
                `);
                
                $('#modal_carrusel').modal('show');
                
                if (modoActual === 'generacion' && idGenActual) {
                    cargarModoGeneracion(idGenActual);
                } else {
                    cargarModoPlantel();
                }
            });
            
            // 🎓 MODO GENERACIÓN
            function cargarModoGeneracion(id_gen) {
                console.log('🎓 Modo generación:', id_gen);
                
                $.ajax({
                    url: 'server/controlador_carrusel.php',
                    type: 'POST',
                    data: { accion: 'obtener_trazabilidad', id_gen: id_gen },
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta.success) {
                            var datos = respuesta.datos;
                            
                            $.ajax({
                                url: 'server/controlador_carrusel.php',
                                type: 'POST',
                                data: { accion: 'obtener_por_generacion', id_gen: id_gen },
                                dataType: 'json',
                                success: function(resp) {
                                    console.log('✅ Elementos generación:', resp.elementos);
                                    
                                    var htmlContent = `
                                        <div style="background: #000; padding: 10px 14px; margin-bottom: 12px; border-radius: 4px;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <span style="background: #fff; color: #000; padding: 3px 10px; border-radius: 3px; font-size: 10px; font-weight: 700;">GENERACIÓN</span>
                                                    <strong style="font-size: 13px; color: #fff; margin-left: 10px; font-weight: 600;">${datos.nom_gen}</strong>
                                                    <span style="font-size: 11px; color: #999; margin-left: 6px;">${datos.nom_pla}</span>
                                                </div>
                                                <button class="btn-agregar-gen" data-id-gen="${datos.id_gen}" data-id-ram="${datos.id_ram}" data-id-pla="${datos.id_pla}" data-id-cad="${datos.id_cad}" style="background: #fff; color: #000; border: none; padding: 6px 14px; border-radius: 3px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                                    + Agregar
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 12px;">
                                    `;
                                    
                                    if (resp.elementos && resp.elementos.length > 0) {
                                        htmlContent += '<div class="d-flex flex-wrap gap-2">';
                                        resp.elementos.forEach(function(elem) {
                                            htmlContent += renderElementoCard(elem, 150);
                                        });
                                        htmlContent += '</div>';
                                    } else {
                                        htmlContent += '<div style="text-align: center; padding: 30px; color: #999; font-size: 12px;">Sin elementos</div>';
                                    }
                                    
                                    htmlContent += '</div>';
                                    $('#body_carrusel').html(htmlContent);
                                    initSwitches();
                                    initLightbox();
                                }
                            });
                        }
                    }
                });
            }
            
            // 🏛️ MODO PLANTEL
            function cargarModoPlantel() {
                console.log('🏛️ Modo plantel');
                
                $.ajax({
                    url: 'server/controlador_carrusel.php',
                    type: 'POST',
                    data: { accion: 'obtener_planteles' },
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta.success && respuesta.planteles.length > 0) {
                            var htmlContent = `
                                <div style="background: #000; padding: 10px 14px; margin-bottom: 12px; border-radius: 4px;">
                                    <span style="background: #fff; color: #000; padding: 3px 10px; border-radius: 3px; font-size: 10px; font-weight: 700;">PLANTELES</span>
                                    <span style="font-size: 11px; color: #ccc; margin-left: 10px;">Gestiona el carrusel de tus centros</span>
                                </div>
                            `;
                            
                            var promesas = [];
                            respuesta.planteles.forEach(function(plantel) {
                                promesas.push($.ajax({
                                    url: 'server/controlador_carrusel.php',
                                    type: 'POST',
                                    data: { accion: 'obtener_por_plantel', id_pla: plantel.id_pla },
                                    dataType: 'json'
                                }));
                            });
                            
                            Promise.all(promesas).then(function(resultados) {
                                console.log('🔥 Resultados:', resultados);
                                
                                respuesta.planteles.forEach(function(plantel, index) {
                                    var elementos = resultados[index].elementos || [];
                                    var elementosActivos = elementos.filter(function(e) { return e.est_car === 'Activo'; });
                                    
                                    console.log('📦', plantel.nom_pla, 'Total:', elementos.length, 'Activos:', elementosActivos.length);
                                    
                                    htmlContent += `
                                        <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 12px; margin-bottom: 10px;">
                                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2" style="border-bottom: 1px solid #eee;">
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <span style="font-size: 14px;">🕋</span>
                                                    <strong style="font-size: 13px; color: #111; font-weight: 600;">${plantel.nom_pla}</strong>
                                                    <span style="background: #f5f5f5; color: #666; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 600;">${elementos.length}</span>
                                                    ${elementosActivos.length > 0 ? `<span class="badge-activos-${plantel.id_pla}" style="background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 600;">${elementosActivos.length} activos</span>` : ''}
                                                </div>
                                                <div class="d-flex" style="gap: 6px;">
                                                    ${elementosActivos.length > 0 ? `<button class="btn-preview-play" data-plantel="${plantel.id_pla}" style="background: #007bff; border: none; color: #fff; padding: 5px 12px; border-radius: 3px; font-size: 10px; font-weight: 600; cursor: pointer;"><i class="fas fa-play"></i> Preview</button>` : ''}
                                                    <button class="btn-agregar-pla" data-id-pla="${plantel.id_pla}" data-nom-pla="${plantel.nom_pla}" style="background: #28a745; border: none; color: #fff; padding: 5px 12px; border-radius: 3px; font-size: 10px; font-weight: 600; cursor: pointer;">
                                                        + Agregar
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div id="preview_${plantel.id_pla}" style="display: none; margin-bottom: 10px; background: #1a1a1a; padding: 16px; border-radius: 8px;">
                                                <div style="max-width: 260px; margin: 0 auto; background: #000; border-radius: 20px; padding: 12px 6px; box-shadow: 0 12px 32px rgba(0,0,0,0.5); position: relative;">
                                                    <button class="btn-cerrar-preview" data-plantel="${plantel.id_pla}" style="position: absolute; top: 6px; right: 6px; background: rgba(255,255,255,0.15); border: none; color: #fff; width: 24px; height: 24px; border-radius: 50%; font-size: 11px; cursor: pointer; z-index: 10; backdrop-filter: blur(4px);">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <div id="preview_content_${plantel.id_pla}" style="background: #fff; border-radius: 14px; overflow: hidden; min-height: 210px;"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="container-plantel-${plantel.id_pla}" data-id-plantel="${plantel.id_pla}" style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 10px;">
                                    `;
                                    
                                    if (elementos.length > 0) {
                                        htmlContent += '<div class="d-flex gap-2" style="overflow-x: auto; padding-bottom: 6px;">';
                                        elementos.forEach(function(elem) {
                                            htmlContent += renderElementoCard(elem, 125);
                                        });
                                        htmlContent += '</div>';
                                    } else {
                                        htmlContent += '<div style="text-align: center; padding: 20px; color: #999; font-size: 11px;">Sin elementos</div>';
                                    }
                                    
                                    htmlContent += '</div></div>';
                                });
                                
                                $('#body_carrusel').html(htmlContent);
                                initSwitches();
                                initLightbox();
                            });
                        }
                    }
                });
            }
            
            // 🎨 RENDER CARD
            function renderElementoCard(elem, width) {
                var switchId = 'switch_' + elem.id_car;
                var isActivo = elem.est_car === 'Activo';
                var opacity = isActivo ? '1' : '0.5';
                var filter = isActivo ? 'none' : 'grayscale(100%)';
                var fontSize = width === 125 ? '10px' : '11px';
                var imgHeight = width === 125 ? '70px' : '85px';
                var btnSize = width === 125 ? '8px' : '9px';
                var btnPadding = width === 125 ? '3px 6px' : '4px 7px';
                var switchW = width === 125 ? '28px' : '30px';
                var switchH = width === 125 ? '14px' : '16px';
                var circleW = width === 125 ? '10px' : '12px';
                var circleH = width === 125 ? '10px' : '12px';
                
                return `
                    <div class="card-elemento" data-id="${elem.id_car}" data-estatus="${elem.est_car}" data-titulo="${elem.tit_car}" data-desc="${elem.des_car || ''}" data-url="${elem.url_car || ''}" data-img="${elem.img_car}" style="flex-shrink: 0; width: ${width}px; background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; overflow: hidden; transition: all 0.3s; opacity: ${opacity};">
                        <img src="../img/${elem.img_car}" alt="${elem.tit_car}" class="img-lightbox" data-img="../img/${elem.img_car}" data-titulo="${elem.tit_car}" data-desc="${elem.des_car || ''}" style="width: 100%; height: ${imgHeight}; object-fit: cover; display: block; filter: ${filter}; transition: all 0.3s; cursor: pointer;">
                        <div style="padding: 7px;">
                            <div style="font-size: ${fontSize}; font-weight: 600; color: #111; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 6px; line-height: 1.2;" title="${elem.tit_car}">${elem.tit_car}</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex" style="gap: 3px;">
                                    <button class="btn-eliminar" data-id="${elem.id_car}" title="Eliminar" style="background: #dc3545; border: none; color: #fff; padding: ${btnPadding}; border-radius: 3px; font-size: ${btnSize}; cursor: pointer;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    ${elem.url_car ? `<a href="${elem.url_car}" target="_blank" title="Link" style="background: #17a2b8; border: none; color: #fff; padding: ${btnPadding}; border-radius: 3px; font-size: ${btnSize}; text-decoration: none; display: inline-block;"><i class="fas fa-link"></i></a>` : ''}
                                </div>
                                <label style="display: inline-flex; cursor: pointer; margin: 0;">
                                    <input type="checkbox" id="${switchId}" class="switch-estatus" data-id="${elem.id_car}" ${isActivo ? 'checked' : ''} style="display: none;">
                                    <span class="switch-bg" style="position: relative; width: ${switchW}; height: ${switchH}; background: ${isActivo ? '#28a745' : '#d0d0d0'}; border-radius: ${switchH}; transition: all 0.3s; display: inline-block;">
                                        <span class="switch-circle" style="position: absolute; top: 2px; left: ${isActivo ? '14px' : '2px'}; width: ${circleW}; height: ${circleH}; background: #fff; border-radius: 50%; transition: all 0.3s; box-shadow: 0 1px 2px rgba(0,0,0,0.3);"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // 🎬 PREVIEW (SOLO ACTIVOS DEL DOM)
            $(document).on('click', '.btn-preview-play', function() {
                var idPlantel = $(this).data('plantel');
                
                var elementosActivos = [];
                $('.container-plantel-' + idPlantel).find('.card-elemento[data-estatus="Activo"]').each(function() {
                    elementosActivos.push({
                        img_car: $(this).data('img'),
                        tit_car: $(this).data('titulo'),
                        des_car: $(this).data('desc'),
                        url_car: $(this).data('url')
                    });
                });
                
                console.log('🎬 Preview - Activos en DOM:', elementosActivos.length);
                
                if (elementosActivos.length === 0) {
                    swal({
                        title: 'Sin elementos activos',
                        text: 'No hay elementos activos para mostrar',
                        icon: 'info',
                        timer: 3000,
                        buttons: false
                    });
                    return;
                }
                
                $('#preview_' + idPlantel).slideDown(300);
                
                var currentIndex = 0;
                
                function mostrarElemento() {
                    var elem = elementosActivos[currentIndex];
                    var linkHTML = elem.url_car ? `<a href="${elem.url_car}" target="_blank" style="color: #007bff; text-decoration: none; font-size: 10px; font-weight: 500; margin-top: 6px; display: inline-block;"><i class="fas fa-external-link-alt"></i> Abrir enlace</a>` : '';
                    
                    $('#preview_content_' + idPlantel).html(`
                        <div style="position: relative; width: 100%; height: 210px;">
                            <img src="../img/${elem.img_car}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); padding: 40px 12px 12px;">
                                <div style="color: #fff; font-size: 13px; font-weight: 700; margin-bottom: 3px; text-shadow: 0 2px 4px rgba(0,0,0,0.6); line-height: 1.3;">${elem.tit_car}</div>
                                ${elem.des_car ? `<div style="color: #e8e8e8; font-size: 10px; line-height: 1.3; text-shadow: 0 1px 3px rgba(0,0,0,0.6);">${elem.des_car}</div>` : ''}
                            </div>
                            <div style="position: absolute; top: 8px; left: 10px; right: 10px; display: flex; justify-content: center; gap: 3px;">
                                ${elementosActivos.map((e, i) => `<span style="width: ${i === currentIndex ? '16px' : '5px'}; height: 5px; background: ${i === currentIndex ? '#fff' : 'rgba(255,255,255,0.5)'}; border-radius: 3px; transition: all 0.3s;"></span>`).join('')}
                            </div>
                        </div>
                        <div style="padding: 8px; text-align: center; background: #fff;">
                            ${linkHTML}
                            <div style="margin-top: 4px; font-size: 8px; color: #999;">${currentIndex + 1} / ${elementosActivos.length}</div>
                        </div>
                    `);
                    
                    currentIndex = (currentIndex + 1) % elementosActivos.length;
                }
                
                mostrarElemento();
                if (intervalPreview) clearInterval(intervalPreview);
                intervalPreview = setInterval(mostrarElemento, 5000);
            });
            
            $(document).on('click', '.btn-cerrar-preview', function() {
                var idPlantel = $(this).data('plantel');
                $('#preview_' + idPlantel).slideUp(300);
                if (intervalPreview) clearInterval(intervalPreview);
            });
            
            // 🖼️ LIGHTBOX
            function initLightbox() {
                $('.img-lightbox').off('dblclick').on('dblclick', function() {
                    var imgSrc = $(this).data('img');
                    var titulo = $(this).data('titulo');
                    var desc = $(this).data('desc');
                    
                    $('body').append(`
                        <div id="lightbox_overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.92); z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px;">
                            <div style="max-width: 90%; max-height: 90%; position: relative;">
                                <button onclick="$('#lightbox_overlay').remove()" style="position: absolute; top: -40px; right: 0; background: rgba(255,255,255,0.15); border: none; color: #fff; width: 36px; height: 36px; border-radius: 50%; font-size: 18px; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <img src="${imgSrc}" style="max-width: 100%; max-height: 80vh; border-radius: 8px; box-shadow: 0 8px 32px rgba(0,0,0,0.4);">
                                ${titulo ? `<div style="color: #fff; margin-top: 16px; text-align: center;"><strong style="font-size: 18px;">${titulo}</strong>${desc ? `<br><span style="font-size: 14px; color: #ccc;">${desc}</span>` : ''}</div>` : ''}
                            </div>
                        </div>
                    `);
                    
                    $('#lightbox_overlay').on('click', function(e) {
                        if (e.target === this) $(this).remove();
                    });
                });
            }
            
            // 🔄 SWITCHES
            function initSwitches() {
                $('.switch-estatus').off('change').on('change', function() {
                    var checkbox = $(this);
                    var idCar = checkbox.data('id');
                    var nuevoEstatus = checkbox.is(':checked') ? 'Activo' : 'Inactivo';
                    var cardElement = $('.card-elemento[data-id="' + idCar + '"]');
                    var switchBg = checkbox.siblings('.switch-bg');
                    var switchCircle = switchBg.find('.switch-circle');
                    var imgElement = cardElement.find('img');
                    
                    $.ajax({
                        url: 'server/controlador_carrusel.php',
                        type: 'POST',
                        data: { accion: 'cambiar_estatus', id_car: idCar, estatus: nuevoEstatus },
                        dataType: 'json',
                        success: function(respuesta) {
                            if (respuesta.success) {
                                var isActivo = nuevoEstatus === 'Activo';
                                
                                switchBg.css('background', isActivo ? '#28a745' : '#d0d0d0');
                                switchCircle.css('left', isActivo ? '14px' : '2px');
                                cardElement.css('opacity', isActivo ? '1' : '0.5');
                                imgElement.css('filter', isActivo ? 'none' : 'grayscale(100%)');
                                cardElement.attr('data-estatus', nuevoEstatus);
                                
                                var container = cardElement.closest('[class*="container-plantel-"]');
                                if (container.length > 0) {
                                    var idPlantel = container.data('id-plantel');
                                    var totalActivos = container.find('.card-elemento[data-estatus="Activo"]').length;
                                    
                                    var badge = $('.badge-activos-' + idPlantel);
                                    var btnPreview = $('[data-plantel="' + idPlantel + '"].btn-preview-play');
                                    var btnContainer = btnPreview.parent();
                                    
                                    if (totalActivos > 0) {
                                        if (badge.length === 0) {
                                            btnContainer.prepend(`<span class="badge-activos-${idPlantel}" style="background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 600; margin-right: 4px;">${totalActivos} activos</span>`);
                                        } else {
                                            badge.text(totalActivos + ' activos');
                                        }
                                        
                                        if (btnPreview.length === 0) {
                                            btnContainer.prepend(`<button class="btn-preview-play" data-plantel="${idPlantel}" style="background: #007bff; border: none; color: #fff; padding: 5px 12px; border-radius: 3px; font-size: 10px; font-weight: 600; cursor: pointer;"><i class="fas fa-play"></i> Preview</button>`);
                                        }
                                    } else {
                                        badge.remove();
                                        btnPreview.remove();
                                    }
                                }
                            } else {
                                checkbox.prop('checked', !checkbox.is(':checked'));
                                swal({
                                    title: 'Error',
                                    text: respuesta.mensaje,
                                    icon: 'error',
                                    timer: 3000,
                                    buttons: false
                                });
                            }
                        }
                    });
                });
            }
            
            $(document).on('click', '.btn-agregar-gen', function() {
                mostrarFormularioCarrusel('generacion', {
                    id_gen: $(this).data('id-gen'),
                    id_ram: $(this).data('id-ram'),
                    id_pla: $(this).data('id-pla'),
                    id_cad: $(this).data('id-cad')
                });
            });
            
            $(document).on('click', '.btn-agregar-pla', function() {
                var idPla = $(this).data('id-pla');
                var nomPla = $(this).data('nom-pla');
                
                $.ajax({
                    url: 'server/controlador_carrusel.php',
                    type: 'POST',
                    data: { accion: 'obtener_cadena_plantel', id_pla: idPla },
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.success) {
                            mostrarFormularioCarrusel('plantel', {
                                id_pla: idPla,
                                id_cad: resp.id_cad,
                                nom_pla: nomPla
                            });
                        }
                    }
                });
            });
            
            function mostrarFormularioCarrusel(modo, datos) {
                var titulo = modo === 'generacion' ? 'Agregar elemento para Generación' : 'Agregar para ' + datos.nom_pla;
                
                $('#body_carrusel').html(`
                    <div style="background: #000; padding: 10px 14px; margin-bottom: 12px; border-radius: 4px;">
                        <h5 style="margin: 0; font-size: 13px; color: #fff; font-weight: 600;">${titulo}</h5>
                    </div>
                    
                    <form id="form_carrusel" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <div style="background: #fafafa; border: 1px solid #e0e0e0; border-radius: 4px; padding: 12px; text-align: center; min-height: 210px;">
                                    <label style="font-size: 10px; font-weight: 600; color: #666; margin-bottom: 6px; display: block;">PREVIEW</label>
                                    <div id="preview_imagen" style="width: 100%; height: 170px; background: #f5f5f5; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #e5e5e5;">
                                        <i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #111; display: block; margin-bottom: 4px;">Imagen JPEG <span style="color: #dc3545;">*</span></label>
                                    <input type="file" class="form-control form-control-sm" id="img_car" name="img_car" accept="image/jpeg" required style="font-size: 11px;">
                                    <small style="font-size: 9px; color: #dc3545; font-weight: 600;">⚠️ EXCLUSIVAMENTE ARCHIVOS JPEG (.jpeg o .jpg) | Max: 5MB</small>
                                </div>
                                
                                <div class="mb-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #111;">Título <span style="color: #dc3545;">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="tit_car" name="tit_car" placeholder="Título del elemento" maxlength="1000" required style="font-size: 11px;">
                                </div>
                                
                                <div class="mb-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #111;">Descripción <span style="color: #999;">(opcional)</span></label>
                                    <textarea class="form-control form-control-sm" id="des_car" name="des_car" rows="2" placeholder="Descripción breve" maxlength="500" style="font-size: 11px;"></textarea>
                                    <small style="font-size: 9px; color: #999;">Max: 500 caracteres</small>
                                </div>
                                
                                <div class="mb-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #111;">URL <span style="color: #999;">(opcional)</span></label>
                                    <input type="url" class="form-control form-control-sm" id="url_car" name="url_car" placeholder="https://ejemplo.com" style="font-size: 11px;">
                                </div>
                                
                                <input type="hidden" name="id_cad33" value="${datos.id_cad}">
                                <input type="hidden" name="id_pla33" value="${datos.id_pla || ''}">
                                <input type="hidden" name="id_ram33" value="${datos.id_ram || ''}">
                                <input type="hidden" name="id_gen33" value="${datos.id_gen || ''}">
                            </div>
                        </div>
                        
                        <div class="mt-3 d-flex gap-2 justify-content-end" style="border-top: 1px solid #e5e5e5; padding-top: 10px;">
                            <button type="button" class="btn btn-sm" id="btn_cancelar_form" style="background: #f5f5f5; color: #666; border: 1px solid #d0d0d0; padding: 6px 16px; border-radius: 3px; font-size: 11px; font-weight: 600;">Cancelar</button>
                            <button type="submit" class="btn btn-sm" id="btn_guardar_elemento" style="background: #28a745; color: #fff; border: none; padding: 6px 16px; border-radius: 3px; font-size: 11px; font-weight: 600;">Guardar</button>
                        </div>
                    </form>
                `);
                
                // 🔥 VALIDACIÓN JPEG ULTRA ESTRICTA
                $('#img_car').on('change', function() {
                    var file = this.files[0];
                    if (!file) {
                        return;
                    }
                    
                    // ⚠️ VALIDACIÓN 1: EXTENSIÓN DEL ARCHIVO
                    var fileName = file.name.toLowerCase();
                    var extension = fileName.split('.').pop();
                    
                    if (extension !== 'jpeg' && extension !== 'jpg') {
                        swal({
                            title: 'ARCHIVO RECHAZADO',
                            text: 'SOLO se aceptan archivos JPEG (.jpeg o .jpg). Tu archivo tiene extensión: .' + extension,
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'Entendido',
                                    value: true,
                                    visible: true,
                                    className: '',
                                    closeModal: true
                                }
                            }
                        });
                        $(this).val('');
                        $('#preview_imagen').html('<i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>');
                        return;
                    }
                    
                    // ⚠️ VALIDACIÓN 2: MIME TYPE
                    if (file.type !== 'image/jpeg') {
                        swal({
                            title: 'TIPO DE ARCHIVO INVÁLIDO',
                            text: 'El archivo debe ser un JPEG real. Tipo detectado: ' + (file.type || 'desconocido'),
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'Entendido',
                                    value: true,
                                    visible: true,
                                    className: '',
                                    closeModal: true
                                }
                            }
                        });
                        $(this).val('');
                        $('#preview_imagen').html('<i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>');
                        return;
                    }
                    
                    // ⚠️ VALIDACIÓN 3: TAMAÑO MÁXIMO 5MB
                    if (file.size > 5 * 1024 * 1024) {
                        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                        swal({
                            title: 'ARCHIVO MUY GRANDE',
                            text: 'La imagen pesa ' + sizeMB + ' MB. El límite es 5 MB',
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'Entendido',
                                    value: true,
                                    visible: true,
                                    className: '',
                                    closeModal: true
                                }
                            }
                        });
                        $(this).val('');
                        $('#preview_imagen').html('<i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>');
                        return;
                    }
                    
                    // ✅ ARCHIVO VÁLIDO - MOSTRAR PREVIEW
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview_imagen').html('<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">');
                    };
                    reader.onerror = function() {
                        swal({
                            title: 'ERROR AL LEER ARCHIVO',
                            text: 'No se pudo procesar la imagen',
                            icon: 'error',
                            timer: 3000,
                            buttons: false
                        });
                        $('#img_car').val('');
                        $('#preview_imagen').html('<i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>');
                    };
                    reader.readAsDataURL(file);
                });
            }
            
            $(document).on('submit', '#form_carrusel', function(e) {
                e.preventDefault();
                
                var fileInput = $('#img_car')[0];
                var file = fileInput.files[0];
                
                // ⚠️ VALIDACIÓN FINAL ANTES DE ENVIAR
                if (!file) {
                    swal({
                        title: 'IMAGEN REQUERIDA',
                        text: 'Debes seleccionar una imagen JPEG',
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                value: true,
                                visible: true,
                                className: '',
                                closeModal: true
                            }
                        }
                    });
                    return;
                }
                
                // ⚠️ VERIFICAR EXTENSIÓN NUEVAMENTE
                var extension = file.name.toLowerCase().split('.').pop();
                if (extension !== 'jpeg' && extension !== 'jpg') {
                    swal({
                        title: 'FORMATO INVÁLIDO',
                        text: 'Solo archivos JPEG (.jpeg o .jpg)',
                        icon: 'error',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                value: true,
                                visible: true,
                                className: '',
                                closeModal: true
                            }
                        }
                    });
                    return;
                }
                
                // ⚠️ VERIFICAR MIME TYPE NUEVAMENTE
                if (file.type !== 'image/jpeg') {
                    swal({
                        title: 'TIPO INVÁLIDO',
                        text: 'El archivo debe ser un JPEG real',
                        icon: 'error',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                value: true,
                                visible: true,
                                className: '',
                                closeModal: true
                            }
                        }
                    });
                    return;
                }
                
                var formData = new FormData(this);
                formData.append('accion', 'crear');
                
                $('#btn_guardar_elemento').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                
                $.ajax({
                    url: 'server/controlador_carrusel.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta.success) {
                            swal({
                                title: '¡ÉXITO!',
                                text: respuesta.mensaje,
                                icon: 'success',
                                timer: 1500,
                                buttons: false
                            }).then(function() {
                                if (modoActual === 'generacion' && idGenActual) {
                                    cargarModoGeneracion(idGenActual);
                                } else {
                                    cargarModoPlantel();
                                }
                            });
                        } else {
                            swal({
                                title: 'ERROR',
                                text: respuesta.mensaje,
                                icon: 'error',
                                buttons: {
                                    confirm: {
                                        text: 'OK',
                                        value: true,
                                        visible: true,
                                        className: '',
                                        closeModal: true
                                    }
                                }
                            });
                            $('#btn_guardar_elemento').prop('disabled', false).html('Guardar');
                        }
                    },
                    error: function() {
                        swal({
                            title: 'ERROR DE CONEXIÓN',
                            text: 'No se pudo comunicar con el servidor',
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'OK',
                                    value: true,
                                    visible: true,
                                    className: '',
                                    closeModal: true
                                }
                            }
                        });
                        $('#btn_guardar_elemento').prop('disabled', false).html('Guardar');
                    }
                });
            });
            
            $(document).on('click', '#btn_cancelar_form', function() {
                if (modoActual === 'generacion' && idGenActual) {
                    cargarModoGeneracion(idGenActual);
                } else {
                    cargarModoPlantel();
                }
            });
            
            // 🗑️ ELIMINAR CON DEBUGGING COMPLETO
            $(document).on('click', '.btn-eliminar', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                var idCar = $(this).data('id');
                
                console.log('🗑️ ELIMINANDO - ID:', idCar);
                
                if (!idCar) {
                    console.error('❌ No se encontró el ID del carrusel');
                    return;
                }
                
                swal({
                    title: '¿Eliminar elemento?',
                    text: 'Esta acción eliminará permanentemente el registro y la imagen del servidor. No se puede deshacer.',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: 'Cancelar',
                            value: false,
                            visible: true,
                            className: '',
                            closeModal: true
                        },
                        confirm: {
                            text: 'Sí, eliminar',
                            value: true,
                            visible: true,
                            className: 'btn-danger',
                            closeModal: true
                        }
                    },
                    dangerMode: true
                }).then((result) => {
                    console.log('🔥 Resultado del swal:', result);
                    
                    if (result) {
                        console.log('✅ Usuario confirmó eliminación');
                        
                        $.ajax({
                            url: 'server/controlador_carrusel.php',
                            type: 'POST',
                            data: { accion: 'eliminar', id_car: idCar },
                            dataType: 'json',
                            beforeSend: function() {
                                console.log('📡 Enviando petición AJAX para eliminar ID:', idCar);
                            },
                            success: function(respuesta) {
                                console.log('📦 Respuesta del servidor:', respuesta);
                                
                                if (respuesta.success) {
                                    swal({
                                        title: 'ELIMINADO',
                                        text: respuesta.mensaje,
                                        icon: 'success',
                                        timer: 1500,
                                        buttons: false
                                    }).then(function() {
                                        console.log('🔄 Recargando vista...');
                                        if (modoActual === 'generacion' && idGenActual) {
                                            cargarModoGeneracion(idGenActual);
                                        } else {
                                            cargarModoPlantel();
                                        }
                                    });
                                } else {
                                    console.error('❌ Error del servidor:', respuesta.mensaje);
                                    swal({
                                        title: 'ERROR',
                                        text: respuesta.mensaje || 'No se pudo eliminar',
                                        icon: 'error',
                                        buttons: {
                                            confirm: {
                                                text: 'OK',
                                                value: true,
                                                visible: true,
                                                className: '',
                                                closeModal: true
                                            }
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('❌ Error AJAX:', error);
                                console.error('Status:', status);
                                console.error('Response:', xhr.responseText);
                                
                                swal({
                                    title: 'ERROR DE CONEXIÓN',
                                    text: 'No se pudo comunicar con el servidor: ' + error,
                                    icon: 'error',
                                    buttons: {
                                        confirm: {
                                            text: 'OK',
                                            value: true,
                                            visible: true,
                                            className: '',
                                            closeModal: true
                                        }
                                    }
                                });
                            }
                        });
                    } else {
                        console.log('❌ Usuario canceló la eliminación');
                    }
                });
            });
            
        });
        </script>
        <!-- F CARRUSEL -->


        <!-- ...... -->
         
        <!-- ...... -->
        </body>

        </html>