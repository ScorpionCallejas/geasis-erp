<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_for_cop = $_POST['id_for_cop'];
    $id_alu_ram = $_POST['id_alu_ram'];

    //VALIDACION DE ALUMNO DE LA CARRERA
    //PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

    //***PENDIENTE DE VERIFICACION
    $sqlValidacion = "
        SELECT *
        FROM cal_act
        INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
        INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2     
        INNER JOIN foro ON foro.id_for = foro_copia.id_for1
        INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop = '$id_for_cop'
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

    $nom_for= $filaValidacion['nom_for'];
    $id_mat = $filaValidacion['id_mat'];
    $id_ram = $filaValidacion['id_ram'];
    $id_for = $filaValidacion['id_for'];

    $des_for = $filaValidacion['des_for'];
    $pun_for = $filaValidacion['pun_for'];
    $ini_cal_act = $filaValidacion['ini_cal_act'];
    $fin_cal_act = $filaValidacion['fin_cal_act'];

    $id_sub_hor = $filaValidacion['id_sub_hor'];


    $id_for_cop = $filaValidacion['id_for_cop'];



    $pun_cal_act = $filaValidacion['pun_cal_act'];

    if ( ( $filaValidacion['fec_cal_act'] == '' ) || ( $filaValidacion['fec_cal_act'] == NULL ) ) {
        $fecha_realizacion = 'Pendiente';
    } else {
        $fecha_realizacion = fechaFormateadaCompacta( $filaValidacion['fec_cal_act'] );
    }
    

    $fec_cal_act = $filaValidacion['fec_cal_act'];

    //$fechaHoy = date('Y-m-d');

    // VALIDACION DE FECHAS 
    // if ($fechaHoy < $ini_cal_act || $fechaHoy > $fin_cal_act) {
    //  header("location: not_found_404_page.php");
    // }
    
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
                    Puntos: <?php echo $pun_for; ?>
                </span>

            </div>
        </div>
        
        
        
    </div>

    <div class="col-md-4">
        
        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
                
                <span class="letraMediana font-weight-bold">
                    Inicio: <?php echo fechaFormateadaCompacta($ini_cal_act); ?>
                </span>

            </div>
        </div>

        
        
        
    </div>

    <div class="col-md-4">

        <div class="card " style="border-radius: 20px;">
            <div class="card-body">
                
              
                <span class="letraMediana font-weight-bold">
                    Fin: <?php echo fechaFormateadaCompacta($fin_cal_act); ?>
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

<!-- INSTRUCCIONES DEL FORO -->
<div class="row">

    <!-- CONTENIDO DE ACTIVIDAD -->
    <div class="col-md-12">
        
        <div class="card grey lighten-5" style="border-radius: 20px;">
            <div class="card-body" id="contenedor_instrucciones">
                <?php  
                    echo $des_for;
                ?>
            </div>
        </div>

    </div>
    
</div>
    

<!-- FIN INSTRUCCIONES DEL FORO -->

    <!-- CAJA DE COMENTARIOS Y REPLICAS -->

    <br>

    <div class="jumbotron" style="border-radius: 20px;">
        <div class="row">

            <div class="col-md-12">
                <section data-step="9" data-intro="Aquí tendrás que dejar una opinión bien argumentada que satisfaga los criterios del profesor en las instrucciones que dejó" data-position='right'>

                  <div class="card-header border-0 font-weight-bold grey white-text">Mi comentario</div>

                  <div class="d-md-flex flex-md-fill px-1">
                    <div class="d-flex justify-content-center mr-md-5 mt-md-5 mt-4">
                      <img class="card-img-64 z-depth-1 rounded-circle" src="../uploads/<?php echo $foto; ?>"
                        alt="avatar">
                    </div>
                    <div class="md-form w-100">

                      <textarea class="form-control md-textarea pt-0" id="comentario" rows="5" placeholder="Comenta según las instrucciones..."></textarea>
                    </div>
                  </div>
                  <div class="text-center" data-step="10" data-intro="Cuando estés listo presiona el botón para enviar tu comentario" data-position='right'>
                    <button class="btn btn-info white-text btn-rounded waves-effect btn-sm" id="btn_enviar">Enviar</button>
                  </div>

                </section>
            </div>
            <div class="col-md-12" id="contenedor_comentarios_foro">
                
            </div>
        </div>

    </div>
    
    <!-- FIN CAJA DE COMENTARIOS Y REPLICAS -->


    



<script>
    setTimeout( function(){
        $( '#comentario' ).focus();
    }, 500 );
    
</script>


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


<script>
    //AGREGADO DE COMENTARIOS
    obtener_comentarios_foro();
    $("#btn_enviar").on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $("#btn_enviar").attr( 'disabled', 'disabled' );
        var comentario = $("#comentario").val();


        if (comentario != "") {
            var id_alu_ram5 = <?php echo $id_alu_ram; ?>;
            var id_for_cop1 = <?php echo $id_for_cop; ?>;

            $.ajax({
                url: 'server/agregar_comentario.php',
                type: 'POST',
                data: {comentario, id_alu_ram5, id_for_cop1 },
                success: function(respuesta){
                    swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {
                        
                        $("#btn_enviar").removeAttr( 'disabled' );
                        obtener_comentarios_foro();

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

                    });

                }
            });
        }else{
            swal("¡Faltan Datos!", "Comentario vacío, asegúrate de proporcionar una opinión", "warning", {button: "Aceptar",});
        }
        
    });


    function obtener_comentarios_foro(){

        var id_alu_ram = <?php echo $id_alu_ram; ?>;
        var id_for_cop = <?php echo $id_for_cop; ?>;
        
        $.ajax({
            url: 'server/obtener_comentarios_foro.php',
            type: 'POST',
            data: { id_alu_ram, id_for_cop },
            success: function( respuesta ){
                $( '#contenedor_comentarios_foro' ).html( respuesta ); 
            }
        });
        

        
    
    }



</script>



<script>
    setTimeout(function(){
        $('#contenedor_instrucciones img').addClass('img-fluid');
    }, 500 );
    // $('#contenedor_instrucciones')
</script>