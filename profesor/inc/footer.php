<?php 

    require("../includes/links_js.php");
?>
    

    </div>
    <!-- FIN JUMBROTRON -->

    <script>

         // SideNav Initialization
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


    <!-- MODAL CLASES MATERIAS -->
    <div class="modal fade" id="modal_materias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Materias</p>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="white-text">&times;</span>
            </button>
          </div>

            <!--Body-->
            <div class="modal-body">
                <h4 class="text-center mb-4">Selecciona un grupo</h4>
                
                <div class="row">
                    <?php  
                    $sqlMaterias = "
                        SELECT *
                        FROM sub_hor
                        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
                        WHERE id_pro = '$id' AND est_sub_hor = 'Activo' AND id_fus2 IS NULL
                        UNION
                        SELECT *
                        FROM sub_hor
                        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
                        WHERE id_pro = '$id' AND est_sub_hor = 'Activo' AND id_sub_hor_nat IS NULL
                    ";
                    $resultadoMaterias = mysqli_query($db, $sqlMaterias);

                    while($filaMaterias = mysqli_fetch_assoc($resultadoMaterias)) {
                    ?>
                        <div class="col-md-12 mb-1">
                            <div class="card waves-effect materiaClase" 
                                style="border-radius: 8px; cursor: pointer;"
                                title="<?php echo $filaMaterias['nom_cic'].' '.$filaMaterias['nom_ram']; ?>"
                                id_sub_hor="<?php echo $filaMaterias['id_sub_hor']; ?>" 
                                nom_mat="<?php echo $filaMaterias['nom_mat']; ?>" 
                                id_mat="<?php echo $filaMaterias['id_mat']; ?>">
                                
                                <div class="card-body p-3" style="position: relative;">
                                    <!-- Círculo azul con ícono -->
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mr-3" 
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-book text-white"></i>
                                        </div>
                                        
                                        <div>
                                            <h6 class="mb-1 font-weight-bold"><?php echo $filaMaterias['nom_mat']; ?></h6>
                                            <small class="text-muted d-block">
                                                <?php echo $filaMaterias['nom_gru']; ?> • <?php echo $filaMaterias['abr_ram']; ?>
                                            </small>
                                            <small class="text-muted">
                                                <?php echo fechaFormateadaCompacta2($filaMaterias['ini_cic']); ?> - 
                                                <?php echo fechaFormateadaCompacta2($filaMaterias['fin_cic']); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <?php 
                                        if( $filaMaterias['id_fus2'] != NULL && $filaMaterias['id_sub_hor_nat'] == NULL ){
                                    ?>
                                            <span class="letraPequena text-primary" style="position: absolute;">*GRUPO-FUSIONADO</span>
                                    <?php
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>

                       
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
          

                <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                    Cancelar
                </a>
                
            </div>

        </div>
        <!--/.Content-->
      </div>
    </div>

    <!-- FIN MODAL CLASES MATERIAS -->



    <!-- MODAL CLASES CREACION CLASE -->
    <!-- Central Modal Medium Info -->
    <div class="modal fade" id="modal_clase" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info" role="document">
         <!--Content-->
         <div class="modal-content">
           <!--Header-->
            <div class="modal-header">
                <p class="heading lead" id="modal_clase_titulo"></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            
            <form id="formularioClase">
           <!--Body-->
                <div class="modal-body">
                
                    <!-- Material input -->
                    <div class="md-form">
                        <i class="fas fa-chalkboard prefix grey-text"></i>
                        
                        <input type="text" id="nom_blo" class="form-control validate" name="nom_blo" required="">
                        <label for="nom_blo">Título de la clase</label>
                    </div>


                    <div class="md-form">
                        <i class="fas fa-info-circle  prefix grey-text"></i>

                        <input type="text" id="des_blo" class="form-control validate" name="des_blo" required="">
                        <label for="des_blo">Descripción de la clase</label>
                    </div>


                    
                
                </div>

               <!--Footer-->
               <div class="modal-footer justify-content-center">
                 
                <button type="submit" class="btn btn-info btn-rounded waves-effect btn-sm" title="Crear clase" id="btn_agregar_clase">
                    Crear
                </button>
                
                <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                    Cancelar
                </a>
               </div>

            </form>

         </div>
         <!--/.Content-->
        </div>
    </div>
    <!-- Central Modal Medium Info-->
    <!-- FUN MODAL CLASES CREACION CLASE -->


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
            


            $( "#calculadora").draggable();



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

            $(".modal-header").addClass('grey darken-1 white-text');

            $('table').addClass('bordeGrisTabla');

            $('thead').removeClass('bg-info').addClass('grey');

            $('.modal').addClass('animated slideInDown');



            // FUNCION CON DEPENDENCIA DE MOMENT JS QUE BASICAMENTE PERMITE EL ORDENAMIENTO DE FILTROS EN CABECERAS DE DATATABLE CON FORMATO (DD/MM/YYYY) *****
            $.fn.dataTable.moment( 'DD/MM/YYYY' );


            // $(document).introJs().start();
            
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



            


        });



//AREA DE NOTIFICACIONES
$(".modal-header").addClass('grey darken-1 white-text');

            $('table').addClass('bordeGrisTabla');

            $('thead').removeClass('bg-info').addClass('grey');

            $('.modal').addClass('animated slideInDown');


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



            // MIS CLASES
            $( '#btn_crear_clase' ).on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                $( '#modal_materias' ).modal( 'show' );


                
            });

            $( '.materiaClase' ).on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                nom_mat = $( this ).attr( 'nom_mat' );
                id_sub_hor = $( this ).attr( 'id_sub_hor' );
                id_mat = $( this ).attr( 'id_mat' );
                // console.log( nom_mat + id_sub_hor );
                
                $( '#modal_materias' ).modal( 'hide' );
                $( '#modal_clase' ).modal( 'show' );
                $( '#modal_clase_titulo' ).text( 'Crear clase para '+ nom_mat );

                setTimeout( function(){
                    $( '#nom_blo' ).focus();
                }, 1000 );
                
  
            });


            $( '#formularioClase' ).on('submit', function(event) {
                event.preventDefault();
                /* Act on the event */

                $("#btn_agregar_clase").html( '<i class="fas fa-cog fa-spin"></i> Creando clase...' );
                $("#btn_agregar_clase").attr( 'disabled','disabled' );
                $( '#formularioClase input' ).addClass('disabled');
                
                var formularioClase = new FormData( $('#formularioClase')[0] );
                formularioClase.append( 'id_mat', id_mat );

                $.ajax({
        
                    url: 'server/agregar_clase.php',
                    type: 'POST',
                    data: formularioClase, 
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function( respuesta ){
                    
                        console.log(respuesta);

                        if ( respuesta != 'false' ) {

                            swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                            then((value) => {

                                $("#btn_agregar_clase").removeClass('btn-info').addClass('btn-success').html( '<i class="fas fa-cog fa-spin"></i> Redireccionando...' );

                                window.location.href = 'clase_contenido.php?id_sub_hor='+id_sub_hor+'&id_blo='+respuesta;
                                // alert( 'el id creado de clase es: '+respuesta );
                                


                            });
                              
                            

                        } else {
                            alert( respuesta );
                        }
                    
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
  var burbuja = new Audio('../audio/burbuja.mp3');
  var error = new Audio('../audio/error.mp3');

  $('#btn_modo').on('click', function() {
    // event.preventDefault();
    /* Act on the event */
    burbuja.play();

    // console.log('modo tal');
    obtenerModo();

      
    

  });


  $('.form-check-input').on('click', function() {
    // event.preventDefault();
    /* Act on the event */
    burbuja.play();

    

  });


  //wss
  // var socket = new WebSocket("<?php echo $socket; ?>");

  // console.log( socket );
    
  // socket.onopen = function(e) {
    
  //   console.log("Connection established!");
          
  // };


  // console.log( socket.OPEN  );

</script>


<script>
  // var socket = new WebSocket("<?php echo $socket; ?>");

  // socket.onmessage = function (event) {
  //   //console.log( event.data );

  //   var datos = JSON.parse(event.data);
  //   console.log(datos);

  //   setTimeout( function(){

  //     // alert( datos.mensaje );
    
  //   }, 500 );
         
  // }

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


</body>
  </div>
  <!-- Copyright -->
      </main>
    <!--/Main layout--> 
<footer>
    <!--Footer-->
</footer>

</html>