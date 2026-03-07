<?php 
    require_once(  __DIR__."/../../includes/links_js.php");
?>


<!-- CONFIG ZONA HORARIA -->
<?php  
  // ini_set('date.timezone','America/Tijuana'); //zona horaria seteada America/Lima
  // echo date("h:i A"); //Muestra la hora actual

  // echo '<br>';


  // $script_tz = date_default_timezone_get();

  // echo $script_tz;
?>

<script>

  // alert(Intl.DateTimeFormat().resolvedOptions().timeZone);
  // var zona_horaria = Intl.DateTimeFormat().resolvedOptions().timeZone;

  // console.log('zh js: '+zona_horaria);
  // $.ajax({
  //   url: 'server/obtener_zona_horaria.php',
  //   type: 'POST',
  //   data: { zona_horaria },
  //   success: function( respuesta ){
  //     console.log( 'res: '+respuesta );
  //   }
  // });
  

</script>
<!-- FIN CONFIG ZONA HORARIA -->
    

    <script>

        $(".button-collapse").sideNav();
        
        new WOW().init();

        var el = document.querySelector('#barra-side');
        Ps.initialize(el);


        function generarAlerta( mensaje ){

          $( 'body' ).append(

            '<div class="animated fadeInDown alerta" style=" z-index: 100000; background: black; border-radius: 10px; width: 250px; height: 35px; position: fixed; right: 3%; bottom: 5%; ">' +
              '<p style=" color: white; padding: 8px; font-size: 13px; text-align: center; ">' +
                '<i class="fas fa-check"></i> ' + mensaje +
              '</p>' +
            '</div>'
          );


          $( '.alerta' ).removeClass( 'animated fadeInDown' );

          setTimeout(function(){
            $( '.alerta' ).addClass( 'animated fadeOut' );
          }, 1500);

        }

        
    
    </script>
    

    <!-- MODAL -->
    <!-- Central Modal Medium Info -->
    <div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Usuarios</p>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="white-text">&times;</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <div class="text-center" id="contenedorUsuariosPlanteles">
              
            </div>
          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>
    <!-- Central Modal Medium Info-->
    <!-- FIN MODAL -->


    <style>
      span.link-chido:hover, span.link-chido:active {
        color: #42a5f5;
        text-decoration: underline;
        cursor: pointer;
      }
    </style>



    <script>
        //CALCULADORA
        function s(v) { document.getElementById('res').value = v }
        function a(v) { document.getElementById('res').value += v }
        function e() { try { s(eval(document.getElementById('res').value)) } catch(e) { s('Error') } }
    </script>

    <script>
        $( function() {

            // TABLA NOTIFICACIONES
            $('#tablaValidaciones').removeClass('bordeGrisTabla');

            

            $( "#calculadora").draggable();

            $('[data-toggle="popover"]').popover();



            $("#btn_calculadora").on('click', function(event) {
                event.preventDefault();
                
                    $("#calculadora").removeAttr("style");   
                
            });


            $("#btn_calculadora_cerrar").on('click', function(event) {
                event.preventDefault();
                $("#calculadora").attr({"style": "display: none;"})
            });


            // ANIMACION * ICONOS ON HOVER

           $('i').on('mouseover', function(){
                $(this).addClass('animated rubberBand');

            });
            $('i').on('mouseleave', function(){
                $(this).removeClass('animated rubberBand');
            });

            $(".modal-header").addClass('');

            // $('table').addClass('bordeGrisTabla');

            // $('thead').removeClass('bg-info').addClass('grey');

            //$('.modal').addClass('animated slideInDown');


            $.fn.dataTable.moment( 'DD/MM/YYYY' );


            // VALIDACION SUPERADMIN
            $(document).bind('keydown', 'ctrl+m', function(){
                
                swal({
                    title: "¡Acceso Restringido!",
                    icon: "warning",
                    text: 'Necesitas permisos de SúperAdministrador para continuar',
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
                                
                            url: '../../server/validacion_permisos_superadmin.php',
                            type: 'POST',
                            data: {correo},
                            success: function(respuesta){
                                console.log(respuesta);

                                if (respuesta == 'True') {
                                
                                    console.log("Existe el correo");
                                    // CORREO VALIDADO 
                                    swal({
                                        title: "¡Acceso Restringido!",
                                        icon: "warning",
                                        text: 'Necesitas permisos de SúperAdministrador para continuar',
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
                                                    
                                                url: '../../server/validacion_permisos_superadmin.php',
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
                                                            $('#modalUsuarios').modal('show');
                                                            $.ajax({
                                                                url: '../../server/obtener_usuarios_superadmin.php',
                                                                type: 'POST',
                                                                success: function(respuesta){
                                                                    $("#contenedorUsuariosPlanteles").html(respuesta);
                                                                }
                                                            });

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
            });
            // FIN VALIDACION SUPERADMIN



            // TABLA NOTIFICACIONES DE HEADER
            $('#tablaValidaciones').removeClass('bordeGrisTabla');
            
            var limite = 10;
            var inicio = 0;
            var action = 'inactive';
            function obtener_notificaciones(limite, inicio){
                $.ajax({
                   url: "server/obtener_notificaciones.php",
                   method: "POST",
                   data: {limite, inicio},
                   cache: false,
                   success:function(data) {
                        $('#accordionExample275').append(data);
                        if(data == '')
                        {
                         $('#load_data_message').html('<label class="animated fadeInDown letraPequena">¡No hay más registros!</label>');
                         action = 'active';
                        }
                        else
                        {
                         $('#load_data_message').html('<label class="letraPequena"><i class="fas fa-spinner fa-pulse"></i> Cargando...</label>');
                         action = "inactive";
                        }
                        


                        $(".actividadPendiente").addClass('link-chido');
                    }
                });
            }

            if(action == 'inactive') {
                action = 'active';
                obtener_notificaciones(limite, inicio);
            }
            $('#formularioNotificaciones').scroll(function(){
                if($('#formularioNotificaciones').scrollTop() + $('#formularioNotificaciones').height() >$('#formularioNotificaciones').height() && action == 'inactive') {
                    action = 'active';
                    inicio = inicio + limite;
                    setTimeout(function(){
                        obtener_notificaciones(limite, inicio);
                    }, 1000);

                 
                }
            });



            

        });
        



        
    </script>


    <script>
      
      $( '#dropdownMenuLinkMensajeria' ).on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        if ( $( '#icono_mensajeria' ).hasClass('fas fa-envelope') ) {
            $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope' ).addClass( 'fas fa-envelope-open' );
        } else {
            $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope-open' ).addClass( 'fas fa-envelope' );
        }
        
      });


    </script>



    

<!--NOTIFICACIONES MENSAJES -->
<script>

  // var burbuja = new Audio('../audio/burbuja.mp3');
  // var error = new Audio('../audio/error.mp3');

  // $('#btn_modo').on('click', function() {
  //   // event.preventDefault();
  //   /* Act on the event */
  //   burbuja.play();

  //   // console.log('modo tal');
  //   obtenerModo();

      

      
    

  // });


  // $('.form-check-input').on('click', function() {
  //   // event.preventDefault();
  //   /* Act on the event */
  //   burbuja.play();

    

  // });



</script>

<script>


  obtener_panel_notificaciones_mensajeria();


  function obtener_panel_notificaciones_mensajeria(){

    $.ajax({
      url: 'server/obtener_panel_notificaciones_mensajeria.php',
      type: 'POST',
      success: function( respuesta ){
        $('#contenedor_notificaciones_mensajeria').html( respuesta );

        $( '#dropdownMenuLinkMensajeria' ).on('click', function(event) {
          event.preventDefault();
          /* Act on the event */

          if ( $( '#icono_mensajeria' ).hasClass('fas fa-envelope') ) {

              $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope' ).addClass( 'fas fa-envelope-open' );
          
          } else {
          
              $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope-open' ).addClass( 'fas fa-envelope' );
          
          }
          

          
        });
      }
    });
    
  }




</script>
<!-- FIN NOTIFICACIONES MENSAJES -->


<script>
  function getParameterByName(name, url) {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
</script>


<!-- 🔥🔥🔥 SESSION KEEP-ALIVE - MANTENER SESIÓN VIVA 🔥🔥🔥 -->
<script>
(function() {
    // ⚙️ CONFIGURACIÓN
    const PING_INTERVAL = 10 * 60 * 1000; // 10 minutos en milisegundos
    const ENDPOINT = 'server/mantener_sesion_viva.php';
    
    let pingTimer = null;
    let ultimoPing = Date.now();
    let keepAliveActivo = false;
    
    // 📡 Función para hacer ping al servidor
    function pingSesion() {
        $.ajax({
            url: ENDPOINT,
            type: 'POST',
            data: { 
                keep_alive: true,
                timestamp: Date.now() 
            },
            dataType: 'json',
            success: function(respuesta) {
                if (respuesta.success) {
                    ultimoPing = Date.now();
                    console.log('🟢 Sesión renovada:', new Date().toLocaleTimeString());
                    
                    // Debug info (opcional)
                    if (respuesta.datos) {
                        console.log('📊 Pings realizados:', respuesta.datos.ping_count);
                    }
                } else {
                    console.warn('⚠️ Ping fallido:', respuesta.mensaje);
                    
                    // Si la sesión murió, recargar página
                    if (respuesta.sesion_muerta) {
                        console.error('❌ Sesión expirada, redirigiendo al login...');
                        // Detener keep-alive
                        clearInterval(pingTimer);
                        keepAliveActivo = false;
                        // Recargar después de 2 segundos
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error en ping de sesión:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
    
    // 🚀 Iniciar timer de ping
    function iniciarKeepAlive() {
        if (keepAliveActivo) {
            console.log('⚠️ Keep-Alive ya está activo');
            return;
        }
        
        keepAliveActivo = true;
        
        // Hacer ping inicial después de 5 segundos
        setTimeout(function() {
            console.log('🚀 Iniciando primer ping de sesión...');
            pingSesion();
        }, 5000);
        
        // Luego cada PING_INTERVAL
        pingTimer = setInterval(pingSesion, PING_INTERVAL);
        
        console.log('🟢 Session Keep-Alive activado (ping cada ' + (PING_INTERVAL / 60000) + ' minutos)');
    }
    
    // 👂 Detectar actividad del usuario para hacer ping adicional
    let actividadTimeout = null;
    let ultimaActividad = Date.now();
    
    function onActividadUsuario() {
        ultimaActividad = Date.now();
        
        clearTimeout(actividadTimeout);
        
        // Si han pasado más de 5 minutos desde el último ping Y el usuario está activo
        actividadTimeout = setTimeout(function() {
            const tiempoDesdeUltimoPing = Date.now() - ultimoPing;
            if (tiempoDesdeUltimoPing > 5 * 60 * 1000) {
                console.log('👤 Usuario activo, renovando sesión anticipadamente...');
                pingSesion();
            }
        }, 1000);
    }
    
    // Eventos de actividad del usuario
    const eventosActividad = ['mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
    eventosActividad.forEach(function(evento) {
        document.addEventListener(evento, onActividadUsuario, { passive: true });
    });
    
    // 🎬 Iniciar cuando el DOM esté listo
    $(document).ready(function() {
        console.log('🔧 Inicializando Session Keep-Alive...');
        iniciarKeepAlive();
    });
    
    // 🔔 Guardar info antes de cerrar (debugging)
    window.addEventListener('beforeunload', function(e) {
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem('ultimo_ping_sesion', ultimoPing);
            localStorage.setItem('ultima_actividad_usuario', ultimaActividad);
        }
    });
    
    // 👁️ Detectar cuando la pestaña vuelve a estar visible
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && keepAliveActivo) {
            const tiempoDesdeUltimoPing = Date.now() - ultimoPing;
            // Si han pasado más de 8 minutos, hacer ping inmediato
            if (tiempoDesdeUltimoPing > 8 * 60 * 1000) {
                console.log('👁️ Pestaña visible de nuevo, renovando sesión...');
                pingSesion();
            }
        }
    });
    
})();
</script>
<!-- FIN SESSION KEEP-ALIVE -->


</body>
  </div>
  <!-- Copyright -->
      </main>
    <!--/Main layout--> 



<!-- Footer -->

<!-- Footer -->

</html>