<?php  

    include('inc/header.php');
    

    $id_for_cop = $_GET['id_for_cop'];

    //VALIDACION DE ALUMNO DE LA CARRERA
    //PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

    //***PENDIENTE DE VERIFICACION
    $sqlValidacion = "
        SELECT *
        FROM foro_copia

        INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2     
        INNER JOIN foro ON foro.id_for = foro_copia.id_for1
        INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1

        WHERE id_pro1 = '$id' AND id_for_cop = '$id_for_cop'
    ";

    $resultadoValidacion = mysqli_query($db, $sqlValidacion);

    // echo $sqlValidacion;
    $totalValidacion = mysqli_num_rows($resultadoValidacion);

    
    if ($totalValidacion == 0) {
        header('location: not_found_404_page.php');
    }
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
    $ini_for_cop = $filaValidacion['ini_for_cop'];
    $fin_for_cop = $filaValidacion['fin_for_cop'];

    $id_sub_hor = $filaValidacion['id_sub_hor'];


    $id_for_cop = $filaValidacion['id_for_cop'];


    //$fechaHoy = date('Y-m-d');

    // VALIDACION DE FECHAS 
    // if ($fechaHoy < $ini_for_cop || $fechaHoy > $fin_for_cop) {
    //  header("location: not_found_404_page.php");
    // }
    
?>






<!-- TITULO -->
<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
    background-image: url('../fondos_clase/<?php echo $img_blo; ?>'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; 

">

    
    <div class="col text-left">
        <span class="tituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Foro: <?php echo $nom_for; ?></span>
        <br>
        <br>


        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Bloque">
            <i class="fas fa-certificate"></i>
            Clase: <?php echo $nom_blo; ?>
        </span>
        
        <br>
        <br>

        <div class=" badge badge-warning animated fadeInUp  text-white">
            <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
            <i class="fas fa-angle-double-right"></i>
            
            <a class="text-white" href="clases_materia.php?id_sub_hor=<?php echo $id_sub_hor; ?>" title="Vuelve a clases">Clases</a>

            
            <i class="fas fa-angle-double-right"></i>
            <a style="color: black;" href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo cifradoServer( $id_blo, 'geasis' ); ?>">
                <span class="text-white"><?php echo $nom_blo; ?></span>
                
            </a>

            <i class="fas fa-angle-double-right"></i>
            <a style="color: black;" href="" title="Estás aquí">Foro</a>


        </div>
    </div>
    
    <div class="col text-right">
        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Carrera">
            <i class="fas fa-certificate"></i>
            Programa: <?php echo $nom_ram; ?>
        </span>
            <br>
            <br>

        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Grupo">
            <i class="fas fa-certificate"></i>
            Grupo: <?php echo $nom_gru; ?>
        </span>

        <br>
        <br>

        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Materia">
            <i class="fas fa-certificate"></i>
            Materia: <?php echo $nom_mat; ?>
        </span> 
        
        
    </div>
    
</div>
<br>
<!-- FIN TITULO -->

    <!-- DETALLES DEL FORO -->
    <div class="jumbotron grey lighten-1">
        <div class="row">
            <div class="col-md-4">
                <div class="jumbotron bg-light mb-3" style="max-width: 20rem;">
                    <h4 class="h4 text-center" title="Detalles de la actividad">
                        Detalles
                    </h4>

                    <hr>


                    <table class="table table-hover">
                        

                        <tbody>
                            <tr>
                                <td>
                                    <span>
                                        <h6 class="h6">
                                            Puntos: 
                                        </h6>
                                    </span>
                                </td>

                                <td>
                                    <h5>
                                        <span class="badge badge-info">
                                            <?php echo $pun_for; ?>
                                        </span>
                                    </h5>
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <span>
                                        <h6 class="h6">
                                            Inicio: 
                                        </h6>
                                    </span>
                                    
                                </td>

                                <td>
                                    <h5>
                                        <span class="badge badge-info">
                                            <?php echo fechaFormateadaCompacta($ini_for_cop); ?>
                                        </span>
                                    </h5>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span>
                                        <h6 class="h6">
                                            Fin: 
                                        </h6>
                                    </span>
                                </td>

                                <td>
                                    <h5>
                                        <span class="badge badge-info">
                                            <?php echo fechaFormateadaCompacta($fin_for_cop); ?>
                                        </span>
                                    </h5>
                                    
                                </td>
                            </tr>

                            
                        </tbody>
                    </table>                    
                    
                    
                </div>  
            </div>


            <!-- CONTENIDO DE ACTIVIDAD -->
            <div class="col-md-8">
                <!-- Jumbotron -->
                <div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5">
                    <?php  

                    echo $des_for;
                    ?>
                </div>
                <!-- FIN Jumbotron -->
                
            </div>

            
        </div>
        

    </div>
    <!-- FIN DETALLES DEL FORO -->
    


    <!-- CAJA DE COMENTARIOS Y REPLICAS -->


    <div class="jumbotron grey lighten-4">
        <div class="row">

            
            <div class="col-md-12">
                <section class="my-5 grey lighten-4 p-4">
                    
                    
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
                    <div class="card-header border-0 font-weight-bold bg-info">
                        Total comentarios: <?php echo $totalComentarios; ?>
                    </div>
                    <!-- FIN TOTAL COMENTARIOS -->


                    <?php

                    while ($filaComentarios = mysqli_fetch_assoc($resultadoComentarios)) {
                        $id_alu_ram = $filaComentarios['id_alu_ram'];
                        ?>
                        <div class="media d-block d-md-flex mt-4">
                            <!-- FOTO ALUMNO -->
                            <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaComentarios['fot_alu']; ?>" alt="Generic placeholder image">
                            <!-- FIN FOTO ALUMNO -->



                            <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                
                                
                                

                                <!-- COMENTARIO -->
                                <div class="  grey lighten-2 p-4 botonPadre" id="comentario<?php echo $id_alu_ram; ?>" style="border-radius: 50px;">
                                    <!-- NOMBRE ALUMNO -->
                                    <h5 class="font-weight-bold mt-0">
                                        <a class="text-default" href="#">
                                            <?php  
                                            echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu'];
                                            ?>
                                        </a>
                                        

                                        <br>

                                        <span style="font-size: 14px; color: grey;">
                                            <?php
                                            $fechaComentario = $filaComentarios['fec_com']; 
                                            echo fechaHoraFormateada($fechaComentario); 
                                            ?>  
                                            
                                        </span>
                                    </h5>
                                    <!-- FIN NOMBRE ALUMNO -->
                                    
                                    <span>
                                        <?php echo $filaComentarios['com_com']; ?>
                                    </span>
                                    

                                    
                                    
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
                                ORDER BY id_rep ASC
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
                                            
                                            <div class="  grey lighten-2 p-4 botonPadre" style="border-radius: 50px;" id="replica<?php echo $id_alu_ram; ?>">
                                                
                                                <h5 class="font-weight-bold mt-0">
                                                    <!-- NOMBRE ALUMNO -->
                                                    <a class="text-default" href="">
                                                        <?php  
                                                        echo $filaReplicas['nom_alu']." ".$filaReplicas['app_alu']." ".$filaReplicas['apm_alu'];
                                                        ?>
                                                    </a>
                                                    <!-- FIN NOMBRE ALUMNO -->


                                                    <br>

                                                    <span style="font-size: 14px; color: grey;">
                                                        <?php
                                                        $fechaReplica = $filaReplicas['fec_rep']; 
                                                        echo fechaHoraFormateada($fechaReplica); 
                                                        ?>  
                                                        
                                                    </span>
                                                </h5>
                                                <!-- REPLICA -->
                                                <span>
                                                    <?php  

                                                    echo $filaReplicas['rep_rep'];
                                                    ?>
                                                </span>
                                                <!-- FIN REPLICA -->


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
            </div>
        </div>

    </div>
    
    <!-- FIN CAJA DE COMENTARIOS Y REPLICAS -->
    


    <!-- CALIFICACION MODAL -->
    <!-- Side Modal Bottom Right Success-->
    <div class="modal fade" id="sideModalBRSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" >
    <div class="modal-dialog  modal-notify modal-info modal-lg" role="document">
        <!--Content-->
        <div class="modal-content " id="tamanoModal" >

            <!--Header-->
            <div class="modal-header" title="Puedes mover este elemento arrastrándolo">
                <p class="heading lead">Calificaciones para: <?php echo $nom_for; ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>


            
            <!--Body-->
            <div class="modal-body" id="contenedorModal">
                    
                    
            </div>
            <!-- Fin Body -->

            <!--Footer-->
            <div class="modal-footer justify-content-center">
                
            </div>
        

            
        </div>
        <!--/.Content-->
    </div>
</div>
<!-- Side Modal Bottom Right Success-->

<!-- FIN CALIFICACION MODAL -->


<!-- FLOATING BUTTON -->
<a type="button" class="btn-floating btn-lg  flotante btn-info" data-target="#sideModalBRSuccess" title="Haz click para calificar a tus alumnos" id="btn_modal">
    <i class="fas fa-award"></i>
</a>
<!-- FIN FLOATING BUTTON -->


<?php  

    include('inc/footer.php');

?>


<script>

    $("#sideModalBRSuccess").draggable();
</script>



<script>
    //MODAL
    // OBTENER ALUMNOS ACTIVIDAD

    $('#btn_modal').on('click', function(event) {
        event.preventDefault();
        $('#sideModalBRSuccess').modal('show');

        var id_for_cop = <?php echo $id_for_cop; ?>;
        $.ajax({
            url: 'server/obtener_alumnos_foro.php',
            type: 'POST',
            data: {id_for_cop},
            success: function(respuesta){
                $("#contenedorModal").html(respuesta);
                //console.log(respuesta);

       
            }
        });
    });


    <?php  
        if ( isset( $_GET['id_alu_ram'] ) && isset( $_GET['tipo'] ) ) {
            $id_alu_ram = $_GET['id_alu_ram'];
            $id_for_cop = $_GET['id_for_cop'];

            $sqlValidacion2 = "
                SELECT *
                FROM cal_act
                WHERE id_alu_ram4 = '$id_alu_ram' AND id_for_cop2 = '$id_for_cop' AND pun_cal_act IS NULL
            ";

            //echo $sqlValidacion2;

            $resultadoValidacion2 = mysqli_query( $db, $sqlValidacion2 );

            if ( $resultadoValidacion2 ) {
                $validacion2 = mysqli_num_rows( $resultadoValidacion2 );

                // echo $validacion2;

                if ( $validacion2 == 1 ) {
    ?>
                    var elemento = $('#<?php echo $_GET['tipo'].$_GET['id_alu_ram']; ?>'); 
                    $('html, body').animate({
                        scrollTop: elemento.offset().top-70
                    }, 1000);
                    elemento.removeClass('grey lighten-2').addClass('animated pulse delay-1s light-green accent-1');

                    // setTimeout(function(){
                    //  elemento.removeClass('light-green accent-1');
                    // }, 5000);

    <?php
                }

            } else {
                echo $sqlValidacion2;
            }
    ?>

        

    <?php
        }

    ?>


    
    
</script>