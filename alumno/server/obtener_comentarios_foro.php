<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_for_cop = $_POST['id_for_cop'];
    $id_alu_ram = $_POST['id_alu_ram'];
?>

<section class="my-5  p-4" data-step="11" data-intro="Cuando hayas mandado tu comentario acá podrás visualizarlo, borrarlo o replicar a otros que ya comentaron haciendo click sobre la flecha que gira, ¡mucho éxito!" data-position='right'>
                    
    <?php  

        $sqlComentarios = "
            SELECT * 
            FROM comentario
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = comentario.id_alu_ram5
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
            WHERE id_for_cop1 = '$id_for_cop' 
            ORDER BY id_com DESC
        ";
        $resultadoComentarios = mysqli_query($db, $sqlComentarios);

        $totalComentarios = mysqli_num_rows($resultadoComentarios);

    ?>
    <!-- Card header -->
    <!-- TOTAL COMENTARIOS -->
    <div class="card-header border-0 font-weight-bold grey white-text">
        Total comentarios: <?php echo $totalComentarios; ?>
    </div>
    <!-- FIN TOTAL COMENTARIOS -->


    <?php

        while ($filaComentarios = mysqli_fetch_assoc($resultadoComentarios)) {
    ?>
            <div class="media d-block d-md-flex mt-4">
                <!-- FOTO ALUMNO -->
                <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaComentarios['fot_alu']; ?>" alt="Generic placeholder image">
                <!-- FIN FOTO ALUMNO -->



                <div class="media-body text-center text-md-left ml-md-3 ml-0">
                    
                    
                    

                    <!-- COMENTARIO -->
                    <div class="  grey lighten-2 p-4 botonPadre" style="border-radius: 20px;">
                        <!-- NOMBRE ALUMNO -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- NOMBRE ALUMNO -->
                                <h6 class="font-weight-normal">
                                    <a class="text-info" href="#">
                                        <?php  
                                          echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu'];
                                        ?>
                                    </a>

                                    <a href="#" class="pull-right text-info replica" comentario="<?php echo $filaComentarios['id_com']; ?>" alumno="<?php echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']; ?>" id_com="<?php echo $filaComentarios['id_com']; ?> " title="Agrega una réplica">
                                      <i class="fas fa-reply"></i>
                                    </a>
                                    
                                </h6>
                                <!-- FIN NOMBRE ALUMNO -->
                            </div>

                            <div class="col-md-6 text-right">
                                <span class="letraMediana black-text">
                                    <?php
                                        $fechaComentario = $filaComentarios['fec_com']; 
                                        echo fechaHoraFormateada($fechaComentario); 
                                    ?>  
                                    
                                </span>
                            </div>

                        </div>
                        <!-- FIN NOMBRE ALUMNO -->
                        
                        <span class="letraMediana font-weight-normal">
                            <?php echo $filaComentarios['com_com']; ?>
                        </span>
                        

                        <?php 

                            if ($filaComentarios['id_alu_ram'] == $id_alu_ram) {
                        ?>
                                <!-- BOTON ELIMINACION -->
                                <div class="waves-effect btn-sm btn-danger btn-floating botonHijo  eliminacionComentario" comentario="<?php echo $filaComentarios['id_com']; ?> ">
                                    <i class="fas fa-times-circle fa-2x" title="Elimina tu comentario"></i>
                                </div>
                                <!-- FIN BOTON ELIMINACION -->
                        <?php
                            }
                        ?>


                        
                    </div>
                    <!-- FIN COMENTARIO -->
                
                    
                    <?php  

                        $id_com = $filaComentarios['id_com'];
                        $sqlReplicas = "
                            SELECT * 
                            FROM replica
                            INNER JOIN comentario ON comentario.id_com = replica.id_com1
                            INNER JOIN alu_ram ON alu_ram.id_alu_ram = replica.id_alu_ram7
                            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
                            WHERE id_com1 = '$id_com'
                            ORDER BY id_rep DESC
                        ";

                        $resultadoReplicas = mysqli_query($db, $sqlReplicas);

                        while ($filaReplicas = mysqli_fetch_assoc($resultadoReplicas)) {
                    ?>      

                        <!-- REPLICA -->
                        <div class="media d-block d-md-flex mt-4">
                            <!-- FOTO ALUMNO -->
                                <img class="img-replica rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaReplicas['fot_alu']; ?>" alt="Generic placeholder image">
                            <!-- FIN FOTO ALUMNO -->
                            <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                
                                <div class="  grey lighten-2 p-4 botonPadre" style="border-radius: 20px;">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="font-weight-normal mt-0">
                                                <!-- NOMBRE ALUMNO -->
                                                <a class="text-info" href="#">
                                                    <?php  
                                                    echo $filaReplicas['nom_alu']." ".$filaReplicas['app_alu']." ".$filaReplicas['apm_alu'];
                                                    ?>
                                                </a>
                                                <!-- FIN NOMBRE ALUMNO -->

                                                <a href="#" class="pull-right text-info replica" id_com="<?php echo $filaComentarios['id_com']; ?> " alumno="<?php echo $filaReplicas['nom_alu']." ".$filaReplicas['app_alu']; ?>" title="Agrega una réplica">
                                                    <i class="fas fa-reply"></i>
                                                </a>
                                                <br>


                                                
                                            </h6>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <span class="letraMediana">
                                                <?php
                                                    $fechaReplica = $filaReplicas['fec_rep']; 
                                                    echo fechaHoraFormateada($fechaReplica); 
                                                ?>  
                                                
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- REPLICA -->
                                    <span class="letraMediana font-weight-normal">
                                        <?php  

                                            echo $filaReplicas['rep_rep'];
                                            
                                        ?>
                                    </span>
                                    <!-- FIN REPLICA -->
                                
                                    <?php 

                                        if ($filaReplicas['id_alu_ram'] == $id_alu_ram) {
                                    ?>
                                            <!-- BOTON ELIMINACION -->
                                            <div class="waves-effect btn-sm btn-danger btn-floating botonHijo eliminacionReplica" replica="<?php echo $filaReplicas['id_rep']; ?> ">
                                                <i class="fas fa-times-circle fa-2x" title="Elimina tu comentario"></i>
                                            </div>
                                            <!-- FIN BOTON ELIMINACION -->
                                    <?php
                                        }
                                    ?>
                                </div>

                                
                            </div>
                        </div>
                    <!-- FIN REPLICA -->
                    <?php
                        }

                    ?>
                        
                    

                </div>
            </div>

    


    <?php       
        }


    ?>

    


    <br>
    <br>
</section>



<!-- REPLICA INPUT -->
<div id="ventanaReplica" class="card grey ventanaReplicaDraggable" style="border-radius: 20px; position: fixed; z-index: 100; width: 300px; display: none; top: 40%; right: 5%;">




    <div class="card-header grey ventanaRespuestaDraggableManejador" style="border-radius: 20px; ">
        <div class="row">
            <div class="col-md-12 text-center white-text letraMediana">
                Replicar a <span id="titulo_replicar"></span>
                
            </div>
        </div>
    </div>
    
    <div class="body bg-white p-2" style="border-radius: 20px;">
        

        <form id="agregarReplicaFormulario">
            <div class="row">
                
                <div class="col-md-12">
    
                    <!-- Group of material radios - option 1 -->
                    
                    

                    <div class="md-form mb-2">
                        
                        <textarea class="form-control md-textarea pt-0 letraPequena" id="rep_rep" name="rep_rep"  rows="5" placeholder="Deja una réplica" required=""></textarea>
                        
                        <input type="hidden" id="id_com1">
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-info white-text btn-rounded waves-effect btn-sm" id="btn_agregar_replica">
                                Replicar
                            </button>
                            
                            <a class="btn grey white-text btn-rounded waves-effect btn-sm cerrarVentanaReplica" title="Cierra esta ventana">
                                Cerrar
                            </a>
                        </div>
                    </div>
                    

                    

                    
                </div>

                
            </div>
        </form>
            
        

    </div>
</div>
<!-- FIN REPLICA INPUT -->


<script>

// ELIMINACION DE COMENTARIOS


    $('.eliminacionComentario').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var comentario = $(this).attr("comentario");
        // console.log(BLOQUE);

        swal({
          title: "¿Deseas eliminar tu comentario?",
          text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
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

            $.ajax({
                url: 'server/eliminacion_comentario.php',
                type: 'POST',
                data: {comentario},
                success: function(respuesta){
                    
                    if (respuesta == "true") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                            obtener_comentarios_foro();
                        });
                    }else{
                        console.log(respuesta);

                    }

                }
            });
            
          }
        });
    });


    // ELIMINACION DE REPLICAS

    $('.eliminacionReplica').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var replica = $(this).attr("replica");
        // console.log(BLOQUE);

        swal({
          title: "¿Deseas eliminar tu replica?",
          text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
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

            $.ajax({
                url: 'server/eliminacion_replica.php',
                type: 'POST',
                data: {replica},
                success: function(respuesta){
                    
                    if (respuesta == "true") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                            obtener_comentarios_foro();
                        });
                    }else{
                        console.log(respuesta);

                    }

                }
            });
            
          }
        });
    });

    
    

</script>


<script>
    //FORMULARIO DE CREACION DE REPLICA A COMENTARIO Y A REPLICA
    //CODIGO PARA AGREGAR REPLICA NUEVO ABRIENDO MODAL
    $( '.ventanaReplicaDraggable' ).draggable({
        handle: ".card-header"
    });

    $( '.cerrarVentanaReplica' ).on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $( this ).parent().parent().parent().parent().parent().parent().parent().css({
            display: 'none'
        });
    });


    $( '.replica' ).on( 'click', function( event ) {
        event.preventDefault();
        /* Act on the event */

        $( '#ventanaReplica' ).css({
            display: ''
        });

        $( '#titulo_replicar' ).html( $(this).attr( 'alumno' ) );

        $("#btn_agregar_replica").removeAttr('disabled');

        $("#id_com1").val($(this).attr( 'id_com' ));

        setTimeout( function(){

            $( '#rep_rep' ).focus();
        
        }, 500 );
    });


    $('#agregarReplicaFormulario').on('submit', function(event) {
        event.preventDefault();

        $("#btn_agregar_replica").attr( 'disabled', 'disabled' );

        var agregarReplicaFormulario = new FormData( $('#agregarReplicaFormulario')[0] );
        agregarReplicaFormulario.append( 'id_com' ,  $("#id_com1").val() );
            
        $.ajax({
        
            url: 'server/agregar_replica.php?id_alu_ram=<?php echo $id_alu_ram."&id_for_cop=".$id_for_cop; ?>',
            type: 'POST',
            data: agregarReplicaFormulario, 
            processData: false,
            contentType: false,
            cache: false,
            success: function(respuesta){
                console.log(respuesta);

                if (respuesta == 'Exito') {
                    swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {
                        
                        $("#btn_agregar_replica").removeAttr('disabled');
                        $("#rep_rep").html('');
                        obtener_comentarios_foro();


                    });
                    
                }
            }
        });
    });
</script>