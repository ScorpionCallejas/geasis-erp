<?php  
    //ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
    //clase_contenido.php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_exa_cop = $_POST['id_exa_cop'];
    $id_alu_ram = $_POST['id_alu_ram'];

    //VALIDACION DE ALUMNO DE LA CARRERA
    //PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

    //***PENDIENTE DE VERIFICACION
    $sqlValidacion = "
        SELECT *
        FROM cal_act
        INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
        INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
        INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
        INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        WHERE id_alu_ram4 = '$id_alu_ram' AND id_exa_cop = '$id_exa_cop'
    ";

    $resultadoValidacion = mysqli_query($db, $sqlValidacion);

    // echo $sqlValidacion;
    $totalValidacion = mysqli_num_rows($resultadoValidacion);

    
    $filaValidacion = mysqli_fetch_assoc($resultadoValidacion);

    $nom_blo = $filaValidacion['nom_blo'];
    $des_blo = $filaValidacion['des_blo'];
    $con_blo = $filaValidacion['con_blo'];  
    $id_mat6 = $filaValidacion['id_mat6'];
    $nom_mat = $filaValidacion['nom_mat'];
    $nom_ram = $filaValidacion['nom_ram'];
    $nom_gru = $filaValidacion['nom_gru'];
    $img_blo = $filaValidacion['img_blo'];
    $id_blo = $filaValidacion['id_blo'];

    $nom_exa= $filaValidacion['nom_exa'];
    $id_mat = $filaValidacion['id_mat'];
    $id_ram = $filaValidacion['id_ram'];
    $id_exa = $filaValidacion['id_exa'];

    $des_exa = $filaValidacion['des_exa'];
    $pun_exa = $filaValidacion['pun_exa'];
    $ini_cal_act = $filaValidacion['ini_cal_act'];
    $fin_cal_act = $filaValidacion['fin_cal_act'];

    $id_sub_hor = $filaValidacion['id_sub_hor'];


    $id_exa_cop = $filaValidacion['id_exa_cop'];

    $pun_cal_act = $filaValidacion['pun_cal_act'];
    $intentosExamen = $filaValidacion['int_cal_act'];

    if ( ( $filaValidacion['fec_cal_act'] == '' ) || ( $filaValidacion['fec_cal_act'] == NULL ) ) {
        $fecha_realizacion = 'Pendiente';
    } else {
        $fecha_realizacion = fechaFormateadaCompacta2( $filaValidacion['fec_cal_act'] );
    }
    
    $fec_cal_act = $filaValidacion['fec_cal_act'];

    $dur_exa = $filaValidacion['dur_exa'];


    if ($filaValidacion['pun_cal_act'] == NULL) {
        //echo "Pendiente";
        $estatus_actividad = "Pendiente";
    }else{
        //echo "Finalizado";
        $estatus_actividad = "Finalizado";
    }
    
?>

<style>
    .botonHijo {
      position: absolute;
      right: -3%;
      top: -15%;
    }


    .botonPadre {
      position: relative;
    }
</style>

<!-- ACTIVIDAD -->

<div class="row text-center">

    <div class="col-md-4">

        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
                
                <span class="letraMediana font-weight-bold">
                    Puntos: <?php echo $pun_exa; ?>
                </span>

            </div>
        </div>
        
        
        
    </div>

    <div class="col-md-4">
        
        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
                
                <span class="letraMediana font-weight-bold">
                    Inicio: <?php echo fechaFormateadaCompacta2($ini_cal_act); ?>
                </span>

            </div>
        </div>

        
        
        
    </div>

    <div class="col-md-4">

        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
              
                <span class="letraMediana font-weight-bold">
                    Fin: <?php echo fechaFormateadaCompacta2($fin_cal_act); ?>
                </span>

            </div>
        </div>
        

        
    </div>

</div>
<!-- FIN DATOS ACTIVIDAD -->



<br>
<!-- DATOS DESEMPEÑO -->

<div class="row text-center">

    <div class="col-md-4">

        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
                
                <span class="letraMediana font-weight-bold">
                    Puntos obtenidos: 
                    <?php  
                        if ( $pun_cal_act == "" ) {
                                                            
                            echo 'Sin calificación';

                        } else {
                            
                            echo $pun_cal_act;
                        
                        }
                    ?>
                </span>

            </div>
        </div>
        
        
        
    </div>

    <div class="col-md-4">
        
        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
                
                <span class="letraMediana font-weight-bold">
                    Fecha de realización: <?php echo $fecha_realizacion; ?>
                </span>

            </div>
        </div>

        
        
        
    </div>

    <div class="col-md-4">

        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
                
                <span class="letraMediana font-weight-bold">
                    Estatus: 
                    <?php  
                        $estatusActividad = obtenerEstatusActividadServer2( $fec_cal_act, $fin_cal_act, $pun_cal_act );
                        if ( $estatusActividad == 'Vencida' ) {
                    ?>
                        <span class="text-danger">
                            <?php echo obtenerEstatusActividadServer2( $fec_cal_act, $fin_cal_act, $pun_cal_act ); ?>
                        </span>

                    <?php
                        } else if ( $estatusActividad == 'Pendiente' ) {
                    ?>
                        <span class="text-warning">
                            <?php echo obtenerEstatusActividadServer2( $fec_cal_act, $fin_cal_act, $pun_cal_act ); ?>
                        </span>

                    <?php
                        } else if ( $estatusActividad == 'Realizada' ) {
                    ?>
                        <span class="text-info">
                            <?php echo obtenerEstatusActividadServer2( $fec_cal_act, $fin_cal_act, $pun_cal_act ); ?>
                        </span>

                    <?php
                        } else if ( $estatusActividad == 'Calificada' ) {
                    ?>
                        <span class="text-success">
                            <?php echo obtenerEstatusActividadServer2( $fec_cal_act, $fin_cal_act, $pun_cal_act ); ?>
                        </span>

                    <?php   
                        }
                    ?>
                </span>

            </div>
        </div>
        

        
    </div>

</div>

<!-- FIN DATOS DESEMPEÑO -->
<br>

<!-- INSTRUCCIONES DEL EXAMEN -->
<div class="row">

    <!-- CONTENIDO DE ACTIVIDAD -->
    <div class="col-md-12">
        
        <div class="card grey lighten-5" style="border-radius: 20px;">
            <div class="card-body" id="contenedor_instrucciones">
                <?php  
                    echo $des_exa;
                ?>
            </div>
        </div>

    </div>
    
</div>
    

<!-- FIN INSTRUCCIONES DEL EXAMEN -->

<br>

<!-- EXAMEN -->




<?php 


    if ( $estatus_actividad == 'Finalizado' && $intentosExamen == 0 ) {

    //include('inc/footer.php');

?>


<!-- EXAMEN Y TIMER -->
<!-- EXAMEN FINALIZADO -->

<div class="jumbotron" style="border-radius: 20px;">



    <div class="row">

        <?php 
            $sqlTotalPreguntas = "
                SELECT * 
                FROM pregunta
                INNER JOIN examen ON examen.id_exa = pregunta.id_exa2
                WHERE id_exa = '$id_exa'

                ";

            $resultadoTotalPreguntas = mysqli_query($db, $sqlTotalPreguntas);

            $totalPreguntas = mysqli_num_rows($resultadoTotalPreguntas);
        ?>
        <div class="col-md-4">

            <div class="jumbotron" data-step="9" data-intro="Aquí se hallan los detalles del cuestionario, cuánto dura, cuántas preguntas hay y cuánto vale en puntos" data-position='right' style="border-radius: 20px;">
                <h5>
                    <strong>
                        Detalles 
                    </strong>
                </h5>
                <hr>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Tiempo: <?php echo $dur_exa; ?> minutos</span>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Valor: <?php echo $pun_exa; ?> puntos</span>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Total: <?php echo $totalPreguntas; ?> preguntas</span>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Intentos: <?php echo $intentosExamen; ?> disponibles</span>

                <?php
                    $sqlAciertos = "
                        SELECT COUNT(id_res1) AS correctas
                        FROM pregunta
                        INNER JOIN respuesta ON respuesta.id_pre1 = pregunta.id_pre
                        INNER JOIN respuesta_alumno ON respuesta_alumno.id_res1 = respuesta.id_res
                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = respuesta_alumno.id_alu_ram8
                        WHERE id_alu_ram = '$id_alu_ram' AND val_res = 'Verdadero' AND id_exa2 = '$id_exa'
                    ";


                    $resultadoAciertos = mysqli_query($db, $sqlAciertos);

                    if (!$resultadoAciertos) {
                        echo $sqlAciertos;
                    }else{
                        $filaAciertos = mysqli_fetch_assoc($resultadoAciertos);
                    }

                ?>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">

                    
                    Aciertos: <?php echo round($filaAciertos['correctas'])."/".$totalPreguntas; ?>
                </span>

            </div>

            
        </div>


        <div class="col-md-8" id="contenedor_examen">



        <?php
            $sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa'";
            $resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
            $i = 1;
            $j = 1;
            while($filaPreguntas = mysqli_fetch_assoc($resultadoPreguntas)){
        ?>
            <!-- CARD -->
            <div class="card" style="border-radius: 20px;">
            
                
                <div class="card-header z-depth-1 bg-white" style="border-radius: 20px;">
                    <div class="row p-2  clasePadre">

                        <div class="claseHijoNumeracion font-weight-bold">
                            <div class="claseTextoHijoNumeracion">
                                <?php echo $i; ?>
                            </div>
                                
                        </div>

                        <div class="col-md-6">
                            
                            <?php echo $filaPreguntas['pre_pre']; ?>
                        </div>

                        <div class="col-md-6 text-right">
                            <p class="letraMediana grey-text">
                                <?php echo "Valor: +".$filaPreguntas['pun_pre']; ?>
                            </p>
                            
                        </div>
                    </div>

                    
                </div>
                      

                <!-- SECCION DE RESPUESTAS -->
                <div class="body" style="border-radius: 20px;">
                <?php

                    $id_pre = $filaPreguntas['id_pre'];
                    $sqlRespuestas = "SELECT * FROM respuesta WHERE id_pre1 = '$id_pre'";
                    $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);
                    
                    while($filaRespuestas = mysqli_fetch_assoc($resultadoRespuesta)){
                        
                ?>
                    <div class="row p-2">
                                    
                        <div class="col-md-1"></div>

                        <!-- Grid column -->
                        <div class="col-md-10">
                            <div class="card" style="border-radius: 20px;">
                                <div class="card-body">

                                    <div class="row clasePadre">

                                        <?php 
                                            $id_res = $filaRespuestas['id_res'];
                                            $sqlValidacionRespuestaAlumno = "

                                                SELECT * 
                                                FROM respuesta 
                                                INNER JOIN respuesta_alumno ON respuesta_alumno.id_res1 = respuesta.id_res
                                                WHERE id_res1 = '$id_res' AND id_alu_ram8 = '$id_alu_ram'
                                            ";

                                            //echo $sqlValidacionRespuestaAlumno;

                                            $resultadoValidacionRespuestaAlumno = mysqli_query($db, $sqlValidacionRespuestaAlumno);

                                            $totalValidacionRespuestaAlumno = mysqli_num_rows($resultadoValidacionRespuestaAlumno);
                                            //echo $totalValidacionRespuestaAlumno;
                                            if ($totalValidacionRespuestaAlumno == 1) {
                                        ?>

                                                <?php 

                                                    if ($filaRespuestas['val_res'] == 'Verdadero') {
                                                ?>
                                                        <label class="form-check-label light-green accent-4 rounded waves-effect" for="materialGroupExample<?php echo $j; ?>">
                                                            <?php 
                                                                echo $filaRespuestas['res_res'];
                                                                
                                                            ?> 
                                                        </label>

                                                <?php
                                                    }else{
                                                ?>

                                                        <label class="form-check-label red rounded waves-effect" for="materialGroupExample<?php echo $j; ?>">
                                                            <?php 
                                                                echo $filaRespuestas['res_res'];
                                                                
                                                            ?> 
                                                        </label>
                                                <?php
                                                    }
                                                ?>
                                                
                                        <?php
                                            }else{
                                        ?>
                                                
                                                <label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
                                                    <?php 
                                                        echo $filaRespuestas['res_res']; 
                                                    ?> 
                                                </label>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>




                <?php
                        $j++;

                    }

                    $i++;

                ?>

                </div>
                <!-- FIN SECCION DE RESPUESTAS -->

            </div>
            <!-- FIN CARD -->

            <br>


        <?php

            }

        ?>


            
            

        
    </div>
</div>





<!-- FIN EXAMEN Y TIMER -->


<?php
    }else{
        // NO HA HECHO EL EXAMEN
?>

<br>


<style>
    #clock {
        zoom: 0.4;
        -moz-transform: scale(0.4);
    }

    #timer {
      position: -webkit-sticky;
      position: sticky;
      top: 0;

    }
</style>
<!-- EXAMEN Y TIMER -->

<div class="jumbotron" style="border-radius: 20px;">
    <div class="row">

        <?php
            $sqlTotalPreguntas = "
                SELECT * 
                FROM pregunta
                INNER JOIN examen ON examen.id_exa = pregunta.id_exa2
                WHERE id_exa = '$id_exa'

            ";

            $resultadoTotalPreguntas = mysqli_query($db, $sqlTotalPreguntas);

            $totalPreguntas = mysqli_num_rows($resultadoTotalPreguntas);
        ?>
        <div class="col-md-4">
            <div class="jumbotron" data-step="9" data-intro="Aquí se hallan los detalles del cuestionario, cuánto dura, cuántas preguntas hay y cuánto vale en puntos" data-position='right' style="border-radius: 20px;">
                <h5>
                    <strong>
                        Detalles 
                    </strong>
                </h5>
                <hr>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Tiempo: <?php echo $dur_exa; ?> minutos</span>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Valor: <?php echo $pun_exa; ?> puntos</span>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Total: <?php echo $totalPreguntas; ?> preguntas</span>
                <span class="badge badge-pill badge-dark font-weight-normal letraMediana">Intentos: <?php echo $intentosExamen; ?> disponibles</span>

            </div>

            <div class="jumbotron text-center hoverable " id="timer" style="border-radius: 20px;" data-step="10" data-intro="Este es el temporizador que contará en cuenta regresiva y cuando llegue a cero hasta donde contestaste se te evaluará" data-position='right'>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5>
                            <i class="fas fa-stopwatch"></i> Cronometro
                        </h5>
                        
                        <hr>

                        <div id="clock" title="Cuando llegue a cero, no podrás hacer cambios y hasta donde hayas llegado serás evaluado"></div>
                    </div>
                    
                </div>

                <hr>
                
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-warning white-text btn-rounded waves-effect btn-sm" title="Presiona este botón para iniciar el cuestionario" id="btn_comenzar" data-step="11" data-intro="Cuando presiones este botón se aplicará el cuestionario y empezará a correr el tiempo, ¡así que asegúrate de estar listo y mucho éxito!" data-position='right'>
                            Comenzar
                        </button>
                    </div>
                    
                </div>

                

                
            </div>
            
        </div>


        <div class="col-md-8 text-center " id="contenedor_examen">

            <?php  
                if ( $intentosExamen > 0 && $intentosExamen < 3 ) {
            ?>

                    <h4>
                        Tu calificación fue de <?php echo $pun_cal_act; ?> de <?php echo $pun_exa; ?>, te recordamos que puedes intentarlo de nuevo, haciendo click en "comenzar".
                        <hr>
                        Te restan <?php echo $intentosExamen; ?> intentos. Pero puedes conservar tus resultados haciendo click en este botón:
                        <br>
                        <br>
                            <a href="#" id="btn_aceptar_calificacion" class="btn btn-block btn-info btn-sm btn-rounded">Finalizar</a>
                        <hr>

                    </h4>

            <?php
                } else {
            ?>

            <?php 
                }
            ?>
            
            
        </div>

        
    </div>
</div>


<!-- FIN EXAMEN Y TIMER -->
    
<script>
    function obtenerNotificacionesActividadesMateria(){

        var id_alu_ram = parseInt( '<?php echo $id_alu_ram; ?>' );
        var id_sub_hor = parseInt( '<?php echo $id_sub_hor; ?>' );

        for( var i = 0; i < $( '.claseHijoMateria' ).length; i++ ){

            if ( $( '.claseHijoMateria' ).eq( i ).attr( 'id_sub_hor' ) == id_sub_hor ) {

                $.ajax({
                    ajaxContador: i,
                    url: 'server/obtener_total_notificaciones_grupo.php',
                    type: 'POST',
                    data: { id_sub_hor, id_alu_ram },
                    success: function( respuesta ){

                        $( '.claseHijoMateria' ).eq( this.ajaxContador ).html( respuesta );
                        $( '.claseHijoClase' ).eq( this.ajaxContador ).html( respuesta );

                    }
                });

            }

        }

    }





    function obtenerNotificacionesActividadesNavbar(){
        $.ajax({
            url: 'server/obtener_notificaciones_actividades.php',
            type: 'POST',
            success: function( respuesta ){
                $( '#contenedor_notificaciones_actividades' ).html( respuesta );
            }
        });
    }



    function obtenerNotificacionesActividadesPrograma(){

        var id_alu_ram = parseInt( '<?php echo $id_alu_ram; ?>' );

        for( var i = 0; i < $( '.claseHijoPrograma' ).length; i++ ){

            if ( $( '.claseHijoPrograma' ).eq( i ).attr( 'id_alu_ram' ) == id_alu_ram ) {

                $.ajax({
                    ajaxContador: i,
                    url: 'server/obtener_total_notificaciones_programa.php',
                    type: 'POST',
                    data: { id_alu_ram },
                    success: function( respuesta ){

                        $( '.claseHijoPrograma' ).eq( this.ajaxContador ).html( respuesta );

                    }
                });

            }

        }

    }



    function removeParam(parameter)
    {
      var url=document.location.href;
      var urlparts= url.split('?');

     if (urlparts.length>=2)
     {
      var urlBase=urlparts.shift(); 
      var queryString=urlparts.join("?"); 

      var prefix = encodeURIComponent(parameter)+'=';
      var pars = queryString.split(/[&;]/g);
      for (var i= pars.length; i-->0;)               
          if (pars[i].lastIndexOf(prefix, 0)!==-1)   
              pars.splice(i, 1);
      url = urlBase+'?'+pars.join('&');
      window.history.pushState('',document.title,url); // added this line to push the new url directly to url bar .

    }
    return url;
    }

</script>

<script type="text/javascript">
    var clock;
    var duracion = <?php echo $dur_exa; ?>;
         
    clock = $('#clock').FlipClock(duracion*60, {
        clockFace: 'MinuteCounter',
        countdown: true,
        autoStart: false,
        language:'es-es',
        callbacks: {
            start: function() {
                $('.message').html('The clock has started!');
            },
            stop: function() {
                 window.location.reload();
            }
        }
    });

    $('#btn_comenzar').click(function(e) {
        e.preventDefault();

        $('#modal_obtener_actividad').on('hide.bs.modal', function(e){
                
            e.preventDefault();
            // e.stopImmediatePropagation();

            window.onbeforeunload = preguntarAntesDeSalir;

            function preguntarAntesDeSalir(){
                return "¿Seguro que quieres salir?";
            }
         
        
        });

        swal({
          title: "¿Seguro que deseas comenzar el cuestionario, te restan <?php echo $intentosExamen ?> intentos?",
          text: "¡Una vez confirmado, comenzará a correr el tiempo y se restará 1 intento!",
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
        }).then((confirmacion) => {
          if (confirmacion) {
            //CODIGO A REALIZAR
            clock.start();
            $('#btn_comenzar').html('Finalizar').removeAttr('id').attr({"id": "btn_finalizar"}).removeClass('btn-warning').addClass('btn-danger');

            var examen = <?php echo $id_exa; ?>;
            var examenCopia = <?php echo $id_exa_cop; ?>;
            $.ajax({
                url: 'server/obtener_examen.php?id_alu_ram=<?php echo $id_alu_ram; ?>',
                type: 'POST',
                data: {examen, examenCopia },
                success: function(respuesta){


                    $("#contenedor_examen").html(respuesta);

                    $(".respuesta").on('change', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        var respuesta =  $(this).attr("respuesta");
                        console.log(respuesta);

                        $.ajax({
                            url: 'server/editar_examen.php?id_alu_ram=<?php echo $id_alu_ram; ?>&id_exa_cop=<?php echo $id_exa_cop; ?>',
                            type: 'POST',
                            data: {respuesta},
                            success: function(respuesta){
                                console.log(respuesta);

                            }                           
                        });
                        


                    });

                    $("#btn_finalizar").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */


                        $('#modal_obtener_actividad').off('hide.bs.modal');


                        swal({
                          title: "¿Seguro que deseas finalizar el cuestionario?",
                          text: "¡Una vez confirmado, NO podrás hacer cambios después!",
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
                        }).then((confirmacion) => {
                          if (confirmacion) {
                            //CODIGO A REALIZAR

                            var id_exa_cop = '<?php echo (int)$id_exa_cop; ?>';

                            // $('#modal_obtener_actividad').on('hide.bs.modal', function(e){
                
                            //     // e.preventDefault();

                            
                            // });

                            obtener_controlador_examen( id_exa_cop );
                            $( '#modal_obtener_actividad' ).on('hidden.bs.modal', function () {
                                

                                if ( window.location.pathname != '/geasis/alumno/historial_actividades.php' ) {
                                    removeParam("identificador_copia");
                                    removeParam("tipo_actividad");

                                    obtenerActividades();
                                } else {
                                    obtenerTablaHistorialActividades();
                                }
                                
                                

                                // alert( id_sub_hor );
                                obtenerNotificacionesActividadesMateria();

                                obtenerNotificacionesActividadesNavbar();


                                obtenerNotificacionesActividadesPrograma();

                               

                                
                            });
                            
                          }
                        });
                        
                    });
                    
                    
                }
            });
            
          }
        });
    });
    
</script>

<?php
    }
?>

<!-- FIN EXAMEN -->


<script>
    setTimeout(function(){
        $('#contenedor_instrucciones img').addClass('img-fluid');
    }, 500 );
    // $('#contenedor_instrucciones')
</script>

<script>
    $('#btn_aceptar_calificacion').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        swal({
          title: "¿Seguro que deseas guardar tu calificación?",
          text: "¡Una vez confirmado, no podrás volver a intentarlo después!",
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
        }).then((confirmacion) => {
            if (confirmacion) {
            //CODIGO A REALIZAR
                var respuesta_alumno = 'Finalizar';

                $.ajax({
                    url: 'server/editar_examen.php?id_alu_ram=<?php echo $id_alu_ram; ?>&id_exa_cop=<?php echo $id_exa_cop; ?>',
                    type: 'POST',
                    data: { respuesta_alumno },
                    success: function( res ){
                        console.log( res );

                        var id_exa_cop = '<?php echo (int)$id_exa_cop; ?>';
                        obtener_controlador_examen( id_exa_cop );

                    }
                });
                
                
            }
        });

    });
</script>