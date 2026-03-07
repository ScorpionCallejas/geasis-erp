<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_for_cop = $_POST['id_for_cop'];

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
	

<!-- ACTIVIDAD -->

<div class="row text-center">

	<div class="col-md-4">

		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="fas fa-check"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Puntos: <?php echo $pun_for; ?>
				</span>

			</div>
		</div>
		
		
		
	</div>

	<div class="col-md-4">
		
		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="far fa-calendar-minus"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Inicio: <?php echo fechaFormateadaCompacta($ini_for_cop); ?>
				</span>

			</div>
		</div>

		
		
		
	</div>

	<div class="col-md-4">

		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="far fa-calendar-plus"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Fin: <?php echo fechaFormateadaCompacta($fin_for_cop); ?>
				</span>

			</div>
		</div>
		

		
	</div>

</div>
<!-- FIN DATOS ACTIVIDAD -->

<br>

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
    

<!-- FIN DETALLES DEL FORO -->

<br>

<!-- CAJA DE COMENTARIOS Y REPLICAS -->


<div class="jumbotron" style="border-radius: 20px;">
    <div class="row">

        
        <div class="col-md-12">
            <section class="">
                
                
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
                    $id_alu_ram = $filaComentarios['id_alu_ram'];
                    ?>
                    <div class="media d-block d-md-flex mt-4">
                        <!-- FOTO ALUMNO -->
                        <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaComentarios['fot_alu']; ?>" alt="Generic placeholder image">
                        <!-- FIN FOTO ALUMNO -->



                        <div class="media-body text-center text-md-left ml-md-3 ml-0">
                            
                            
                            

                            <!-- COMENTARIO -->

                            <?php  
                                if ( obtenerValidacionAlumnoActividadServer( 'Foro', $id_for_cop, $id_alu_ram ) > 0 ) {
                            ?>
                                    <div class="  animated pulse delay-1s light-green accent-1 p-4 botonPadre" id="comentario<?php echo $id_alu_ram; ?>" style="border-radius: 20px;" title="El alumno <?php echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu']; ?> aun no ha sido calificado...">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- NOMBRE ALUMNO -->
                                                <h6 class="font-weight-normal">
                                                    <a class="text-info" href="#">
                                                        <?php  
                                                          echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu'];
                                                        ?>
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
                                        
                                        
                                    

                                        <span class="letraMediana font-weight-normal">
                                            <?php echo $filaComentarios['com_com']; ?>
                                        </span>
                                        

                                        
                                        
                                    </div>

                            <?php
                                } else {
                            ?>

                                    <div class="  grey lighten-2 p-4 botonPadre" id="comentario<?php echo $id_alu_ram; ?>" style="border-radius: 20px;">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- NOMBRE ALUMNO -->
                                                <h6 class="font-weight-normal">
                                                    <a class="text-info" href="#">
                                                        <?php  
                                                          echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu'];
                                                        ?>
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
                                        
                                        
                                    

                                        <span class="letraMediana font-weight-normal">
                                            <?php echo $filaComentarios['com_com']; ?>
                                        </span>
                                        

                                        
                                        
                                    </div>

                            <?php
                                }
                            ?>
                            
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
                                    
                                <?php  
                                    if ( obtenerValidacionAlumnoActividadServer( 'Foro', $id_for_cop, $id_alu_ram ) > 0 ) {
                                ?>
                                        
                                        <!-- REPLICA -->
                                        <div class="media d-block d-md-flex mt-4 ">
                                            <!-- FOTO ALUMNO -->
                                            <img class="img-replica rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaReplicas['fot_alu']; ?>" alt="Generic placeholder image">
                                            <!-- FIN FOTO ALUMNO -->
                                            <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                                
                                                <div class="animated pulse delay-1s  light-green accent-1 p-4 botonPadre" style="border-radius: 20px;" id="replica<?php echo $id_alu_ram; ?>" title="El alumno <?php echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu']; ?> aun no ha sido calificado...">
                                                    
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


                                                </div>

                                                
                                            </div>
                                        </div>
                                        <!-- FIN REPLICA -->
                                <?php  
                                    } else {
                                ?>
                                        <!-- REPLICA -->
                                        <div class="media d-block d-md-flex mt-4 ">
                                            <!-- FOTO ALUMNO -->
                                            <img class="img-replica rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaReplicas['fot_alu']; ?>" alt="Generic placeholder image">
                                            <!-- FIN FOTO ALUMNO -->
                                            <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                                
                                                <div class="grey lighten-2 p-4 botonPadre" style="border-radius: 20px;" id="replica<?php echo $id_alu_ram; ?>">
                                                    
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


                                                </div>

                                                
                                            </div>
                                        </div>
                                        <!-- FIN REPLICA -->

                                <?php
                                    }
                                ?>
                                
                                <?php
                            }

                            ?>
                            
                            

                        </div>
                    </div>

                    


                    <?php       
                }


                ?>

             
            </section>
        </div>
    </div>

</div>

<!-- FIN CAJA DE COMENTARIOS Y REPLICAS -->

<!-- FIN ACTIVIDAD -->


<script>
    setTimeout(function(){
        $('#contenedor_instrucciones img').addClass('img-fluid');
    }, 500 );
    // $('#contenedor_instrucciones')
</script>