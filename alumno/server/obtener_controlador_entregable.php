<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_ent_cop = $_POST['id_ent_cop'];
    $id_alu_ram = $_POST['id_alu_ram'];

    //VALIDACION DE ALUMNO DE LA CARRERA
    //PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

    //***PENDIENTE DE VERIFICACION
    $sqlValidacion = "
        SELECT *
        FROM cal_act
        INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
        INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3   
        INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
        INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop = '$id_ent_cop'
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

    $nom_ent= $filaValidacion['nom_ent'];
    $id_mat = $filaValidacion['id_mat'];
    $id_ram = $filaValidacion['id_ram'];
    $id_ent = $filaValidacion['id_ent'];

    $des_ent = $filaValidacion['des_ent'];
    $pun_ent = $filaValidacion['pun_ent'];
    $ini_cal_act = $filaValidacion['ini_cal_act'];
    $fin_cal_act = $filaValidacion['fin_cal_act'];

    $id_sub_hor = $filaValidacion['id_sub_hor'];


    $id_ent_cop = $filaValidacion['id_ent_cop'];

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
      right: 0%;
      top: 0%;
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
                    Puntos: <?php echo $pun_ent; ?>
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

<!-- INSTRUCCIONES DEL ENTREGABLE -->
<div class="row">

    <!-- CONTENIDO DE ACTIVIDAD -->
    <div class="col-md-12">
        
        <div class="card grey lighten-5" style="border-radius: 20px;">
            <div class="card-body" id="contenedor_instrucciones">
                <?php  
                    echo $des_ent;
                ?>
            </div>
        </div>

    </div>
    
</div>
    

<!-- FIN INSTRUCCIONES DEL ENTREGABLE -->

<br>

<!-- INTERACCION -->
<div id="contenedor_tarea">
    
</div>

<script>
    


    obtener_tarea();
    function obtener_tarea(){

        var id_alu_ram = <?php echo $id_alu_ram; ?>;
        var id_ent_cop = <?php echo $id_ent_cop; ?>;
        
        $.ajax({
            url: 'server/obtener_tarea.php',
            type: 'POST',
            data: { id_alu_ram, id_ent_cop },
            success: function( respuesta ){
                $( '#contenedor_tarea' ).html( respuesta ); 
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