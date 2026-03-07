<?php 

	//mensajes.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
  
  $sqlSalas = "
    SELECT *
    FROM usuario_sala 
    INNER JOIN sala ON sala.id_sal = usuario_sala.id_sal6
    WHERE (usu_usu_sal = '$id' AND tip_usu_sal = '$tipo') AND estatus_sala = 'Pendiente'
    ORDER BY fec_men_sal DESC
  ";

  if ( $_GET['id_sub_hor'] != 'falso' ) {
      
    $sqlSalas .= ', id_sub_hor6 = '.$_POST['id_sub_hor'].' DESC ';  

  }
  // echo $sqlSalas;

  $resultadoSalas = mysqli_query($db, $sqlSalas);

  $i = 1;
  while ( $filaSalas = mysqli_fetch_assoc( $resultadoSalas ) ) {

    $id_sal6 = $filaSalas['id_sal6'];

    // echo $filaSalas['tip_usu_sal'];

    // RECEPTOR
    $sqlusuarios = "
      SELECT *
      FROM usuario_sala 
      INNER JOIN sala ON sala.id_sal = usuario_sala.id_sal6
      WHERE id_sal6 = '$id_sal6' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' ) limit 1
    ";

    $resultadoUsuarios = mysqli_query( $db, $sqlusuarios );

    $resultadoUsuariosTotal = mysqli_query( $db, $sqlusuarios );

    $filaUsuarioTotal = mysqli_fetch_assoc( $resultadoUsuariosTotal );


    if ( $filaSalas['id_sub_hor6'] == NULL ) {
      
      while( $filausuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){
      
        $tip_not_men = $filausuarios['tip_usu_sal'];
        $use_not_men = $filausuarios['usu_usu_sal'];


        $datosContactoUltimoMensaje2 = obtener_datos_contacto_mensajeria_server( $tip_not_men, $use_not_men );

        

        $sala_tipo = $datosContactoUltimoMensaje2['tipo'];
        $sala_nombre = $datosContactoUltimoMensaje2['nombre'];

        if ( $sala_tipo == 'Admin' ) {

          $sala_tipo = 'Directivo😎';
        
        } else if ( $sala_tipo == 'Profesor' ) {
          
          $sala_tipo = 'Capacitador👨‍🏫';

        } else if ( $sala_tipo == 'Adminge' ) {
          
          $sala_tipo = 'Coordinador🎓';

        }

      
      }

    } else {

      // echo 'entre';

      $id_sub_hor = $filaSalas['id_sub_hor6'];
      $sqlGrupo = "
        SELECT nom_gru
        FROM grupo
        INNER JOIN sub_hor ON id_gru1 = id_gru
        WHERE id_sub_hor = '$id_sub_hor'
      ";

      // echo $sqlGrupo;

      $resultadoGrupo = mysqli_query( $db, $sqlGrupo );

      $filaGrupo = mysqli_fetch_assoc( $resultadoGrupo );

      $sala_nombre = $filaGrupo['nom_gru'];

      // echo 'sala_nombre: '.$sala_nombre;
      $sala_tipo = 'Grupal';


    }

    
    // FIN RECEPTOR


    $sqlUltimoMensaje = "
      SELECT * FROM mensaje WHERE id_sal4 = '$id_sal6' ORDER BY hor_men DESC LIMIT 1
    ";


    $datosUltimoMensaje = obtener_datos_consulta( $db, $sqlUltimoMensaje );

    // echo 'datosUltimioemensaje: '.$datosUltimoMensaje['total'];
    
    if ( $datosUltimoMensaje['total'] > 0 ) {
    
      $tipo_contacto = $datosUltimoMensaje['datos']['tip_men'];

      

      $id_contacto = $datosUltimoMensaje['datos']['use_men'];
      $hor_men = $datosUltimoMensaje['datos']['hor_men'];

      // echo $tipo_contacto.' '.$id_contacto;

      $datosContactoUltimoMensaje = obtener_datos_contacto_mensajeria_server( $tipo_contacto, $id_contacto );
      

      $mensaje_ultimo_contacto = $datosContactoUltimoMensaje['nombre'];
      $mensaje_ultimo = $datosUltimoMensaje['datos']['men_men'];

    } else {

      $sala_foto = 'grupo.jpg';
      $mensaje_ultimo_contacto = 'N/A';
      $mensaje_ultimo = 'N/A';
      $hor_men = date('Y-m-d');

    }

    if ( $filaSalas['id_sub_hor6'] == NULL ) {
        
      $sala_foto = $datosContactoUltimoMensaje['foto'];

    } else {

      $sala_foto = 'grupo.jpg';

    }

    
    // echo $datosContactoUltimoMensaje['foto'];
?>

    <!-- MSJ DE NOTIFICACION DE ENTRADA -->
    <div class="chat-list-item d-flex flex-row w-100 p-2 border-bottom unread seleccionSala" id_sal="<?php echo $id_sal6; ?>" sala_nombre="<?php echo $sala_nombre; ?>" sala_foto="<?php echo obtenerValidacionFotoUsuario( $sala_foto ); ?>">
      
      <?php  
        if ( $filaSalas['id_sub_hor6'] == NULL ) {
      ?>

          <img src="<?php echo obtenerValidacionFotoUsuario( $sala_foto ); ?>" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px; position: relative; border: 2px solid black;">

      <?php
        } else {
      ?>

          <img src="../img/<?php echo $sala_foto; ?>" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px; position: relative; border: 2px solid black;">

      <?php
        }
      ?>
      

      <!-- <i class="fas fa-times fa-2x red-text eliminacionSala" id_sal="<?php echo $datos['id_sal']; ?>" title="Eliminar"></i> -->

      <div class="w-50">


        <div class="badge badge-warning badge-pill small" ><?php echo $sala_tipo; ?></div>

        <?php  
          if ( $sala_tipo == 'Alumno' ) {

            echo obtener_generacion_alumno_server( $datosContactoUltimoMensaje2['id'] );

          } else {

        ?>
            <div class="badge badge-primary badge-pill small"><?php echo $filaSalas['nom_sal']; ?></div>
        <?php
          }
        ?>
        <div class="name"><?php echo $sala_nombre; ?></div>

        <div class="small last-message"><span id="seleccionSalaContactoUltimoMensaje<?php echo $id_sal6; ?>"><?php echo $mensaje_ultimo_contacto; ?></span> dice: <span id="seleccionSalaMensaje<?php echo $id_sal6; ?>"><?php echo $mensaje_ultimo; ?></span>
        </div>
  
      </div>
      
      <div class="flex-grow-1 text-right">
        <div class="small time" id="seleccionSalaHora<?php echo $id_sal6; ?>"><?php echo fechaFormateadaCompacta2( $hor_men ); ?></div>


        <div id="seleccionSalaNotificacion<?php echo $id_sal6; ?>">
          <?php  
            echo obtener_notificaciones_sala_server( $id_sal6, $id, $tipo );
          ?>
        </div>
        
        
      </div>
    </div>
    <!-- FIN MSJ DE NOTIFICACION DE ENTRADA -->
<?php
    $i++;
  }

?>


<script>

  function showChatList(){

    // window.addEventListener("resize", e => {
    //  if (window.innerWidth > 575) showChatList();
    // });
    if ( $('#message-area').hasClass('d-none') ) {

      $('#chat-list-area').removeClass('d-flex').addClass('d-none');
      $('#message-area').removeClass('d-none').addClass('d-flex');
      $('#fondo_mensajeria').css('display', 'none');
    
    } else {    

      $('#message-area').removeClass('d-flex').addClass('d-none');
      $('#chat-list-area').removeClass('d-none').addClass('d-flex');
      $('#fondo_mensajeria').css('display', '');

    }
    
  }


  if ( $(window).width() <= 800 ){
    // showChatList();


    // alert('entro');
    $('#contenedor_contactos_mensajeria').css('max-height', '80vh');
    
  } else {

    
    $('#contenedor_contactos_mensajeria').css('max-height', '90vh');
    
  }

</script>

<script>
  $('.seleccionSala').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    var elemento = $(this);

    var sala_foto = elemento.attr('sala_foto');

    $('#foto_sala').removeAttr('src').attr('src', sala_foto);

    $('.seleccionSala').removeClass('active');
    $(this).addClass('active');

    $('#fondo_mensajeria').removeClass('w-100 h-100 overlay');

    var id_sal = elemento.attr('id_sal');

    $('#btn_eliminacion_sala').removeAttr('id_sal');
    $('#btn_eliminacion_sala').attr('id_sal', id_sal);

    obtener_mensajes_sala( id_sal );

    // console.log('index: '+index);

    obtener_datos_sala( id_sal );
    
    // chat-list-area
    // message-area
    // fondo_mensajeria
    

    // $( window ).resize(function() {
      
    //   console.log('rezise');

    if ( $(window).width() <= 800 ){
    
      showChatList();
    
    }


  });


  $('#btn_eliminacion_sala').on('click', function(event) {
    event.preventDefault();
    
    var id_sal = $(this).attr("id_sal");

    // console.log(SALA);
    swal({
      title: "¿Deseas eliminar esta sala?",
      text: "¡Una vez confirmes, se perderán todos los mensajes para los dos usuarios!",
      icon: "warning",
      buttons:  {
            cancel: {
              text: "Cancelar",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "Confirmar",
              value: true,
              visible: true,
              className: "",
              closeModal: true
            }
          },
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        //ELIMINACION ACEPTADA

        // setTimeout(function(){
          
          obtener_socket_salas( id_sal );
        
        // }, 2000 );
        

        $.ajax({
          url: 'server/eliminacion_sala.php',
          type: 'POST',
          data: { id_sal },
          success: function(respuesta){
            
            if (respuesta == "true") {
              console.log("Exito en consulta");
              swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
              then((value) => {
                
                
                $('#contenedor_mensajes_sala').html('');
                $('#btn_enviar').removeAttr('id_sal').attr('id_sal', '');
                obtener_contactos_mensajeria(); 

                // obtener_socket_salas( id_sal );
                  

                

              });
            }else{
              console.log(respuesta);

            }

          }
        });
        
      }
    });

  });



  


  // obtener_datos_sala( 443 );

  function obtener_datos_sala( id_sal ){

    // console.log('func');

    $.ajax({
      url: 'server/obtener_datos_sala.php',
      type: 'POST',
      dataType: 'json',
      data: { id_sal },
      success: function( datos ){

        console.log( datos );
        // console.log( datos.mensaje.datos.men_men );
        $('#seleccionSalaMensaje'+id_sal).text( datos.mensaje.men_men );
        $('#seleccionSalaHora'+id_sal).text( datos.mensaje.hor_men );
        $('#seleccionSalaContactoUltimoMensaje'+id_sal).text( datos.emisor.nombre );

        if ( datos.notificacion == 0 ) {

          $('#seleccionSalaNotificacion'+id_sal).html('');

        } else {

          $('#seleccionSalaNotificacion'+id_sal).html( '<div class="badge badge-success badge-pill small" id="unread-count">'+datos.notificacion+'</div>' );

        }
        
      }

    });

  }


  function obtener_sala_activa(){
  
    return $('.active').attr('id_sal');
  
  }

  function obtener_mensajes_sala( id_sal ){
    sala = id_sal;
   var sala_previa = 0;
    var limit = 0;
    if (sala = sala_previa) {
      limit = limit+10;
    }
    else{
      limit = 10;
      sala_previa = sala;
    }
    obtener_estatus_sala( id_sal );

    $.ajax({
      url: 'server/obtener_mensajes_sala.php',
      type: 'POST',
      data: { id_sal, limit },
      success: function( respuesta ){
        limit = 10;
        // console.log( respuesta );
        $('#contenedor_mensajes_sala').html( respuesta );

        $('#btn_enviar').attr('id_sal', id_sal);
        $('#btn_consulta_usuarios').attr('id_sal', id_sal);
        
        obtener_scroll();

      }
    });

  }


  function obtener_estatus_sala( id_sal ){

    $.ajax({
      url: 'server/estatus_mensajes_sala.php',
      type: 'POST',
      data: { id_sal },
      success: function( respuesta ){

        console.log( respuesta );

      }
    
    });
  
  }

</script>


<script>

    $('#input_buscador_sala').keyup(function(){
      // console.log('eventr');
       var nombres =  $('.seleccionSala');
       var palabra = $(this).val().toLowerCase();

       console.log(palabra);


       var item='';

       // console.log(nombres.length);
       
       for( var i = 0; i < nombres.length; i++ ){

           item = $(nombres[i]).attr('sala_nombre').toLowerCase();

            for(var x = 0; x < item.length; x++ ){
                
                // console.log( nombres );
                if( palabra.length == 0 || item.indexOf( palabra ) > -1 ){

                  nombres.eq(i).removeClass('d-none').addClass('d-flex'); 
                
                }else{
                
                    nombres.eq(i).removeClass('d-flex').addClass('d-none');
                }
            
            }
       
       }
    
    });

</script>