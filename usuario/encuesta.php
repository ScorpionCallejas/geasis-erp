<?php  

	include('inc/header.php');

	$id_enc = $_GET['id_enc'];

	$sql = "
		SELECT *
		FROM encuesta 
		WHERE id_enc = '$id_enc'
	";

	$datos = obtener_datos_consulta( $db, $sql )['datos'];

	
?>
	<!-- FUENTES -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap');

        .fuente_encuestas {
            font-family: 'Roboto Condensed', sans-serif;
        }

        .fixed-width-table {
		  table-layout: fixed;
		  width: 100%;
		}

		.fixed-width-table th,
		.fixed-width-table td {
		  padding: 8px;
		  overflow: hidden;
		  text-overflow: ellipsis;
		  white-space: normal;
		  word-wrap: break-word;
		}

		.fixed-width-table th:first-child,
		.fixed-width-table td:first-child {
		  text-align: left;
		}

		.fixed-width-table td:not(:first-child) {
		  text-align: center;
		}

        .restriccion {
		  cursor: not-allowed;
		}

        /* Estilo para todas las tablas */
        table {
            width: 100%;          /* Ocupa el ancho completo de su contenedor */
            border-collapse: collapse;   /* Hace que las celdas compartan los bordes */
        }

        /* Estilo para los encabezados de las tablas */
        table thead th {
            background-color: skyblue;   /* Color de fondo azul cielo */
            border: 1px solid black;     /* Borde negro alrededor de cada encabezado */
        }

        /* Estilo para todas las celdas de la tabla */
        table td, table th {
            border: 1px solid black;     /* Borde negro alrededor de cada celda */
            padding: 8px;                /* Espaciado dentro de las celdas para un mejor diseño */
        }

        /* Estilo para la primera columna */
        table td:first-child, table th:first-child {
            width: 50%;                  /* El 40% del ancho total de la tabla */
        }



    </style>
    <!-- FIN FUENTES -->
	
	<div class="row fuente_encuestas">
		<div class="col-md-2"></div>
		<div class="col-md-8">

			<!-- BANNER -->
            <div class="card">
                <div class="card-body bg-dark text-center">
                    <img src="https://lh3.googleusercontent.com/HzodaZ9MDOP58YA3znLNUiJMVjKeVi5wu-_ZBkRZT33WMuj-iqknlmIb7DFTSKNrXp0=w2400" class="img-responsive" width="50%">
                </div>
            </div>
            <!-- FIN BANNER -->

			<!--  -->
			<!-- TITULO -->
            <div class="card">
                <div class="card-body text-center h2" id="contenedor_encuesta_titulo">
                    <h3>
						<?php echo $datos['nom_enc']; ?>
					</h3>
                </div>
            </div>
            <!-- FIN TITULO -->


            <!-- INSTRUCCIONES -->
            <div class="card">
                <div class="card-body letraMediana" id="contenedor_encuesta_instrucciones">
                	<?php  
                        if ( $datos['obj_enc'] == 2 ) {
                    ?>
                            <strong class="letraGrande">
                                ESTIMADO COLABORADOR, <?php echo strtoupper($nombre); ?>
                            </strong>
                    <?php
                        } else {
                    ?>
                            <strong class="letraGrande">
                                ESTIMADO ALUMNO
                            </strong>

                    <?php
                        }
                    ?>
                    
                	<br>
                    <span>
						<?php echo $datos['des_enc']; ?>
					</span>	
                </div>
            </div>
            <!-- FIN INSTRUCCIONES -->
			

			<hr>

			<!-- PLANTEL -->
			<div class="card letraGrande restriccion" style="border-radius: 20px;">
				<div class="card-body" style="border-radius: 20px;">
					<strong class="letraGrande ">
                		CDE
                	</strong>
                	<hr>
					<?php  
						$sqlPlanteles = "
							SELECT *
							FROM plantel
							WHERE id_cad1 = '1'
						";

						$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
						$i = 0;
						while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
					?>

							<input type="checkbox" class="form-check-input disabled" id="planteles<?php echo $i;?>" name="planteles" disabled <?php echo ( $filaPlanteles['id_pla'] == $plantel )? 'checked': ''; ?>>
							<label class="form-check-label disabled" for="planteles<?php echo $i; ?>">
							  	<?php
									echo $filaPlanteles['nom_pla']; 
								?> 
							</label>
							<br>
					<?php
							$i++;
						}
					?>
				</div>
			</div>			

			<br>
                        		
			<!-- FIN PLANTEL -->

			<?php  
				// VALIDACION DE SI EXISTE ORDENAMIENTO PREVIO
                $sqlValidacionOrdenamiento = "
                    SELECT *
                    FROM reactivo
                    WHERE id_enc1 = '$id_enc' AND ord_rea IS NOT NULL
                ";

                $validacionOrden = obtener_datos_consulta( $db, $sqlValidacionOrdenamiento )['total'];

                if ( $validacionOrden > 0 ) {

                    $sqlReactivo = "
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea != 'Tabla' AND tip_rea != 'Profesores' AND tip_rea != 'Organigrama'
                        
                        UNION
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea = 'Tabla'
                        GROUP BY for_rea
                        
                        UNION
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea = 'Organigrama'
                        GROUP BY for_rea

                        UNION
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea = 'Profesores'
                        GROUP BY for_rea
                        ORDER BY ord_rea ASC
                    
                    ";

                } else {

                    $sqlReactivo = "
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea != 'Tabla' AND tip_rea != 'Profesores' AND tip_rea != 'Organigrama'
                        UNION
                        
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea = 'Tabla'
                        GROUP BY for_rea

                        UNION
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea = 'Organigrama'
                        GROUP BY for_rea
                        
                        UNION
                        SELECT *
                        FROM reactivo
                        WHERE id_enc1 = '$id_enc' AND tip_rea = 'Profesores'
                        GROUP BY for_rea
                    ";

                }

				$resultadoReactivo = mysqli_query( $db, $sqlReactivo );

				$i = 1;
				$j = 1;
				while( $filaReactivo = mysqli_fetch_assoc( $resultadoReactivo ) ){
					$id_rea = $filaReactivo['id_rea'];
			?>

					
					<!-- CARD -->
					<div class="card letraGrande " style="border-radius: 20px;">
						<?php  
                            if ( $filaReactivo['tip_rea'] == 'Organigrama' ) {
                                $for_rea = $filaReactivo['for_rea'];
                        ?>

                            <?php
                                $sqlValidacionArbol = "
                                    SELECT *
                                    FROM arbol
                                    WHERE id_hijo = '$id' AND tabla = 'usuario' AND id_padre IS NOT NULL
                                ";

                                // echo $sqlValidacionArbol;

                                $validacionArbol = obtener_datos_consulta( $db, $sqlValidacionArbol );
                                
                                $totalValidacionArbol = $validacionArbol['total'];
                                // $datosValidacionArbol = $validacionArbol['datos'];

                                $resultadoValidacionArbol = mysqli_query( $db, $sqlValidacionArbol );

                                if ( $totalValidacionArbol > 0 ) {
                        ?>
                                    <!--  -->
                                    <!-- ORGANIGRAMA -->
                                    <div class="card-body p-2" style="border-radius: 20px;">

                                        <br>
                                        <caption><?php echo $filaReactivo['rea_rea']; ?></caption>
                                        
                                        <table class="fixed-width-table table-bordered">
                                            
                                            <thead>

                                                
                                                    <tr >
                                                        <th></th>

                                                        <!-- RESPUESTAS -->
                                                        <?php  
                                                            $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea'";
                                                            $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

                                                        ?>
                                                        
                                                        <?php  
                                                            while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
                                                        ?>

                                                                <th style="position: relative;" class="text-center">
                                                                    <div id="contenedor_opcion<?php echo $filaOpcion['id_opc']; ?>">

                                                                        <label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
                                                                            <?php
                                                                                echo $filaOpcion['opc_opc']; 
                                                                            ?>
                                                                        </label>

                                                                    </div>
                                                                    
                                                                </th>
                                                        <?php
                                                                $j++;
                                                            }
                                                        ?>
                                                        <!-- FIN RESPUESTAS -->

                                                    </tr>
                                            </thead>
                                            <tbody>
                                    <!--  -->

                                            <!-- WHILE -->
                                            <?php

                                                        while( $datosValidacionArbol = mysqli_fetch_assoc( $resultadoValidacionArbol ) ){
                                                            // echo "1";
                                            ?>


                                                <!-- CONDICIONAL IF -->
                                                <?php
                                                            if ( $datosValidacionArbol['tabla'] == 'usuario' ) {
                                                                $id_usu = $datosValidacionArbol['id_padre'];
                                                                
                                                                $sqlTabla = "
                                                                    SELECT *
                                                                    FROM reactivo
                                                                    INNER JOIN usuario ON usuario.id_usu = reactivo.id_usu 
                                                                    WHERE usuario.id_usu = '$id_usu' AND for_rea = '$for_rea'
                                                                    LIMIT 1
                                                                ";
                                                                // echo $sqlTabla;

                                                                $resultadoTabla = mysqli_query( $db, $sqlTabla );

                                                ?>
                                                <!-- FIN CONDICIONAL IF -->

                                                
                                                                
                                                                            <?php  
                                                                                while( $filaTabla = mysqli_fetch_assoc( $resultadoTabla ) ){
                                                                            ?>
                                                                                    <!--  -->
                                                                                    <tr id="contenedor_reactivo_tabla<?php echo $filaTabla['id_rea']; ?>" class="Cerrado">
                                                                                       
                                                                                        <!-- FOTO Y NOMBRE -->
                                                                                        <td style="position: relative;">
                                                                                            
                                                                                            <a href="https://elcomercio.pe/resizer/0NTL2WSmZaVDgXKBWHXTTHZPF34=/1200x1200/smart/filters:format(jpeg):quality(75)/cloudfront-us-east-1.images.arcpublishing.com/elcomercio/T2CUKPVKLBG6DJJLJ7K3UQ2XFA.jpg" data-lightbox="roadtrip" data-title="Usuario <?php echo $filaTabla['nom_pro']; ?>">
                                                                                                
                                                                                                <img src="https://elcomercio.pe/resizer/0NTL2WSmZaVDgXKBWHXTTHZPF34=/1200x1200/smart/filters:format(jpeg):quality(75)/cloudfront-us-east-1.images.arcpublishing.com/elcomercio/T2CUKPVKLBG6DJJLJ7K3UQ2XFA.jpg" class="img-fluid" style="height: 45px; width: 40px; border-radius: 40px;" title="Haz click para ampliar la imagen :D">
                                                                                            
                                                                                            </a>

                                                                                            <span><?php echo $filaTabla['nom_usu']; ?></span>
                                                                                        </td>  
                                                                                        <!-- FIN FOTO y NOMBRE -->

                                                                                      
                                                                                        <!-- RESPUESTAS -->
                                                                                        <?php  
                                                                                            $id_rea_aux = $filaTabla['id_rea'];
                                                                                            $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea_aux'";
                                                                                            $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

                                                                                        ?>
                                                                                        
                                                                                        <?php  

                                                                                            while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
                                                                                        ?>

                                                                                                <td style="position: relative;" class="text-center">
                                                                                                    <div id="contenedor_opcion<?php echo $filaOpcion['id_opc']; ?>">
                                                                                                 
                                                                                                        <input type="radio" class="form-check-input respuesta" id="materialGroupExample<?php echo $j;?>"
                                                                                                        name="res<?php echo $id_rea_aux; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
                                                                                                        <label class="form-check-label" for="materialGroupExample<?php echo $j;?>"></label>
                                                                                                    </div>
                                                                                                    
                                                                                                </td>
                                                                                        <?php
                                                                                                $j++;
                                                                                            }
                                                                                        ?>
                                                                                        <!-- FIN RESPUESTAS -->

                                                                                    </tr>
                                                                                    <!--  -->

                                                                            <?php
                                                                                }
                                                                            ?>
                                                                        

                                                <!-- CONDICIONAL ELSE  -->
                                                <?php


                                                            } else if ( $datosValidacionArbol['tabla'] == 'ejecutivo' ) {
                                                                // !!!!!!!FALTA VALIDAR!!!!!!!!!! 
                                                            }

                                                    }
                                                ?>
                                                <!-- FIN CONDICIONAL ELSE -->


                                            <!-- FIN WHILE -->
                                            <?php
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- ORGANIGRAMA -->
                                    <!--  -->
                        
						<?php  
							} else if ( $filaReactivo['tip_rea'] == 'Profesores' ) {
								$for_rea = $filaReactivo['for_rea'];
						?>
						<!-- PROFESORES -->
								<div class="card-body p-2" style="border-radius: 20px;">

                                    <br>
                                    <caption><?php echo $filaReactivo['rea_rea']; ?></caption>
                                    <table class="fixed-width-table table-bordered">
                                    	
                                        <thead>

                                            <?php  
                                                $sqlTabla = "
                                                    SELECT *
													FROM reactivo
													INNER JOIN profesor ON profesor.id_pro = reactivo.id_pro3
													INNER JOIN sub_hor ON sub_hor.id_pro1 = profesor.id_pro
													INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
													INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
													INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                                                    WHERE id_enc1 = '$id_enc' AND for_rea = '$for_rea' AND id_alu_ram1 = '$id_alu_ram'
                                                ";

                                                $resultadoTabla = mysqli_query( $db, $sqlTabla );
                                            ?>
                                            
                                                <tr >
                                                	<th></th>
                                                    <th></th>
                                                    <th></th>

                                                    <!-- RESPUESTAS -->
                                                    <?php  
                                                        $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea'";
                                                        $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

                                                    ?>
                                                    
                                                    <?php  
                                                        while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
                                                    ?>

                                                            <th style="position: relative;" class="text-center">
                                                                <div id="contenedor_opcion<?php echo $filaOpcion['id_opc']; ?>">

                                                                    <label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
                                                                        <?php
                                                                            echo $filaOpcion['opc_opc']; 
                                                                        ?>
                                                                    </label>

                                                                </div>
                                                                
                                                            </th>
                                                    <?php
                                                            $j++;
                                                        }
                                                    ?>
                                                    <!-- FIN RESPUESTAS -->

                                                </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                                                while( $filaTabla = mysqli_fetch_assoc( $resultadoTabla ) ){
                                            ?>
                                                    <!--  -->
                                                    <tr id="contenedor_reactivo_tabla<?php echo $filaTabla['id_rea']; ?>" class="Cerrado">
                                                    	<td>
                                                    		<a href="<?php echo obtenerValidacionFotoUsuarioServer( $filaTabla['fot_emp'] ) ?>" data-lightbox="roadtrip" data-title="Profesor <?php echo $filaTabla['nom_pro']; ?>">
                                                                
                                                                <img src="<?php echo obtenerValidacionFotoUsuarioServer( $filaTabla['fot_emp'] ) ?>" class="img-fluid" style="height: 45px; width: 40px; border-radius: 40px;" title="Haz click para ampliar la imagen :D">
                                                            
                                                            </a>
                                                    	</td>
                                                        <!-- FOTO Y NOMBRE -->
                                                        <td style="position: relative;">
                                                            
                                                            <span><?php echo $filaTabla['nom_pro'].' '.$filaTabla['app_pro'].' '.$filaTabla['apm_pro']; ?></span>
                                                        </td>  
                                                        <!-- FIN FOTO y NOMBRE -->

                                                        <!-- MATERIA -->
                                                        <td><strong><?php echo $filaTabla['nom_mat']; ?></strong></td>
                                                        <!-- FIN MATERIA -->

                                                        <!-- RESPUESTAS -->
                                                        <?php  
                                                            $id_rea_aux = $filaTabla['id_rea'];
                                                            $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea_aux'";
                                                            $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

                                                        ?>
                                                        
                                                        <?php  

                                                            while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
                                                        ?>

                                                                <td style="position: relative;" class="text-center">
                                                                    <div id="contenedor_opcion<?php echo $filaOpcion['id_opc']; ?>">
                                                                 
                                                                        <input type="radio" class="form-check-input respuesta" id="materialGroupExample<?php echo $j;?>"
														 	 			name="res<?php echo $id_rea_aux; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
																		<label class="form-check-label" for="materialGroupExample<?php echo $j;?>"></label>
                                                                    </div>
                                                                    
                                                                </td>
                                                        <?php
                                                                $j++;
                                                            }
                                                        ?>
                                                        <!-- FIN RESPUESTAS -->

                                                    </tr>
                                                    <!--  -->

                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
						
						<!-- FIN PROFESORES -->
                        <?php  
                            } else if ( $filaReactivo['tip_rea'] == 'Tabla' ) {
                            	$for_rea = $filaReactivo['for_rea'];
                        ?>

                        		<div class="card-body p-2" style="border-radius: 20px;">

                                    <br>
                                    <table class="fixed-width-table table-bordered">
                                        <thead>

                                            <?php  
                                                $sqlTabla = "
                                                    SELECT *
                                                    FROM reactivo
                                                    WHERE id_enc1 = '$id_enc' AND for_rea = '$for_rea'
                                                ";

                                                $resultadoTabla = mysqli_query( $db, $sqlTabla );
                                            ?>
                                            
                                                <tr >
                                                    <th></th>

                                                    <!-- RESPUESTAS -->
                                                    <?php  
                                                        $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea'";
                                                        $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

                                                    ?>
                                                    
                                                    <?php  
                                                        while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
                                                    ?>

                                                            <th style="position: relative;" class="text-center">
                                                                <div id="contenedor_opcion<?php echo $filaOpcion['id_opc']; ?>">

                                                                    <label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
                                                                        <?php
                                                                            echo $filaOpcion['opc_opc']; 
                                                                        ?>
                                                                    </label>

                                                                </div>
                                                                
                                                            </th>
                                                    <?php
                                                            $j++;
                                                        }
                                                    ?>
                                                    <!-- FIN RESPUESTAS -->

                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php  
                                                while( $filaTabla = mysqli_fetch_assoc( $resultadoTabla ) ){
                                            ?>
                                                    <!--  -->
                                                    <tr id="contenedor_reactivo_tabla<?php echo $filaTabla['id_rea']; ?>" class="Cerrado">
                                                        <!-- PREGUNTA -->
                                                        <th style="position: relative;">
	                                                    	<span><?php echo $filaTabla['rea_rea']; ?></span>
                                                        </th>   
                                                        <!-- FIN PREGUNTA -->

                                                        <!-- RESPUESTAS -->
                                                        <?php  
                                                            $id_rea_aux = $filaTabla['id_rea'];
                                                            $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea_aux'";
                                                            $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

                                                        ?>
                                                        
                                                        <?php  

                                                            while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
                                                        ?>

                                                                <th style="position: relative;" class="text-center">
                                                                    <div id="contenedor_opcion<?php echo $filaOpcion['id_opc']; ?>">
                                                                 
                                                                        <input type="radio" class="form-check-input respuesta" id="materialGroupExample<?php echo $j;?>"
														 	 			name="res<?php echo $id_rea_aux; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
																		<label class="form-check-label" for="materialGroupExample<?php echo $j;?>"></label>
                                                                    </div>
                                                                    
                                                                </th>
                                                        <?php
                                                                $j++;
                                                            }
                                                        ?>
                                                        <!-- FIN RESPUESTAS -->

                                                    </tr>
                                                    <!--  -->

                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                
                        <?php  
                            } else {
                        ?>
        						<!--  -->
        						<div class="card-header <?php echo ( $filaReactivo['tip_rea'] == 'Texto' )? 'grey lighten-4': 'white'; ?> <?php echo $filaReactivo['tip_rea']; ?>" style="border-radius: 20px;" tipo="">
									<div class="row p-2  clasePadre">

										<br>
		                                <?php  
		                                    if ( $filaReactivo['tip_rea'] == 'Texto' ) {
		                                ?>
		                                        <h5>
		                                            <?php echo $filaReactivo['rea_rea']; ?>
		                                        </h5>
		                                <?php
		                                    } else {
		                                ?>
		                                        <?php echo $filaReactivo['rea_rea']; ?>
		                                        <br>
		                                <?php
		                                    }
		                                ?>

									</div>

									
								</div>
								



							  	<!-- SECCION DE RESPUESTAS -->
							  	<div class="body" style="border-radius: 20px;">

							  		<?php  
										if ( $filaReactivo['tip_rea'] == 'Abierto' ) {
									?>


											<div class="row p-2">
																
												<div class="col-md-1"></div>

											    <!-- Grid column -->
											    <div class="col-md-10">
											    	<div class="card m-2" style="border-radius: 20px;">
														<div class="card-body">

															<div class="row clasePadre">

																<div class = "col-md-12">

																	<span class="letraPequena grey-text">*Máximo 500 caracteres</span>

																	<textarea id_rea="<?php echo $filaReactivo['id_rea']; ?>" rows="5" style="width: 100%;" class="respuesta_abierta"></textarea>

																</div>
															</div>
															
														</div>
														
													</div>
											    </div>
											   	<div class="col-md-1"></div>
											</div>


									<?php
										} else if( $filaReactivo['tip_rea'] == 'Multiple' ) {
									?>

											<?php  

		                                        $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea'";
		                                        $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

		                                        $total_respuestas = obtener_datos_consulta($db, $sqlRespuestas)['total'];
		                                    ?>
		                                    <?php  
		                                        if ( $total_respuestas > 6 ) {
		                                    ?>  
		                                    		<!--  -->
		                                    		<div class="table-responsive text-center">
		                                                <table class="table">
		                                                    <thead>
		                                                        <?php  
		                                                            while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
		                                                        ?>

		                                                                <th>
		                                                                    <input type="checkbox" class="form-check-input respuesta_multiple" id="materialGroupExample<?php echo $j;?>" 
																		  	name="res<?php echo $i; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
																			<label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
																			  	<?php
																					echo $filaOpcion['opc_opc']; 
																				?> 
																			</label>
		                                                                    
		                                                                </th>
		                                                        <?php
		                                                                $j++;
		                                                            }
		                                                        ?>
		                                                    </thead>
		                                                </table>
		                                            </div>
		                                    		<!--  -->
		                                    <?php
		                                    	} else {

		                                    ?>
		                                    		<!--  -->
		                                    		<?php
												  		while($filaOpcion = mysqli_fetch_assoc($resultadoRespuesta)){
												  	?>
												  		
												  			<div class="row p-2">
																			
																<div class="col-md-1"></div>

															    <!-- Grid column -->
															    <div class="col-md-10">
															    	<div class="card m-2" style="border-radius: 20px;">
																		<div class="card-body">

																			<div class="row clasePadre">

																				<div class = "col-md-12">

																					<input type="checkbox" class="form-check-input respuesta_multiple" id="materialGroupExample<?php echo $j;?>" 
																				  	name="res<?php echo $i; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
																					<label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
																					  	<?php
																							echo $filaOpcion['opc_opc']; 
																						?> 
																					</label>
																					
																				</div>
																			</div>
																			
																		</div>
																		
																	</div>
															    </div>
															   	<div class="col-md-1"></div>
															</div>

												  	<?php
												  			$j++;

												  		}
												  	?>
		                                    		
		                                    		<!--  -->
		                                    <?php
		                                    	}
		                                    ?>


									

									<?php
										}else{
									?>

											<!--  -->
											<?php  
											
		                                        $sqlRespuestas = "SELECT * FROM opcion WHERE id_rea1 = '$id_rea'";
		                                        $resultadoRespuesta = mysqli_query($db, $sqlRespuestas);

		                                        $total_respuestas = obtener_datos_consulta($db, $sqlRespuestas)['total'];
		                                    ?>
		                                    <?php  
		                                        if ( $total_respuestas > 6 ) {
		                                    ?>  
		                                    		<!--  -->
		                                    		<div class="table-responsive text-center">
		                                                <table class="table">
		                                                    <thead>
		                                                        <?php  
		                                                            while( $filaOpcion = mysqli_fetch_assoc( $resultadoRespuesta ) ){
		                                                        ?>

		                                                                <th>
		                                                                    <input type="radio" class="form-check-input respuesta" id="materialGroupExample<?php echo $j;?>" 
																		 	 name="res<?php echo $i; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
																			<label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
																			  	<?php
																					echo $filaOpcion['opc_opc']; 
																				?> 
																			</label>
		                                                            
		                                                                </th>
		                                                        <?php
		                                                                $j++;
		                                                            }
		                                                        ?>
		                                                    </thead>
		                                                </table>
		                                            </div>
		                                    		<!--  -->
		                                    <?php
		                                    	} else {

		                                    ?>
		                                    		<!--  -->
		                                    		<?php
												  		while($filaOpcion = mysqli_fetch_assoc($resultadoRespuesta)){
												  	?>
												  		
												  			<div class="row p-2">
																
																<div class="col-md-1"></div>

															    <!-- Grid column -->
															    <div class="col-md-10">
															    	<div class="card" style="border-radius: 20px;">
																		<div class="card-body">

																			<div class="row clasePadre">

																				<div class = "col-md-12">

																					<input type="radio" class="form-check-input respuesta" id="materialGroupExample<?php echo $j;?>" 
																				 	 name="res<?php echo $i; ?>" value="<?php echo $filaOpcion['id_opc']; ?>">
																					<label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
																					  	<?php
																							echo $filaOpcion['opc_opc']; 
																						?> 
																					</label>
																					
																				</div>
																			</div>
																			
																		</div>
																		
																	</div>
															    </div>
															   	<div class="col-md-1"></div>
															</div>

												  	<?php
												  			$j++;

												  		}
												  	?>
		                                    		
		                                    		<!--  -->
		                                    <?php
		                                    	}
		                                    ?>
											<!--  -->

											
									<?php
										}
									?>
							  	


							  	

							  	</div>
        						<!--  -->
                        <?php  
                            }
                        ?>
                        <!-- FIN TABLA -->
						<!--  -->

						
					  	<?php

					  		$i++;

					  	?>

					</div>
					<!-- FIN CARD -->
					<?php echo ( $filaReactivo['tip_rea'] == 'Texto' )? '': '<hr>'; ?>
					
			<?php
				}
			?>
			

			<div class="row">
				<div class="col col-md-12 col-sm-12 col-12 text-center">
					<a href="#" class="btn btn-lg btn-info btn-rounded waves-effect" id="btn_encuesta">Terminar encuesta</a>
				</div>	
			</div>

			<br>
			<br>



			<!-- ENCUESTA MODAL -->


			<div class="modal fade" id="modal_encuesta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
			   aria-hidden="true">
			  	<div class="modal-dialog modal-notify modal-info" role="document">
			     <!--Content-->
				    <div class="modal-content" style="border-radius: 20px;">
				       <!--Header-->
				       <div class="modal-header" style="border-radius: 20px;">
				         <p class="heading lead">Solicitud de encuesta</p>

				         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				           <span aria-hidden="true" class="white-text">&times;</span>
				         </button>
				       </div>

				       <!--Body-->
				       <div class="modal-body">
				         <div class="text-center">
				         	<i class="fas fa-bell fa-4x mb-3 delay-2s animated swing infinite text-warning"></i>

				           	<p>
				           		Hola, <?php echo $nombre ?>. Tus respuestas nos ayudan a mejorar el servicio que ofrecemos. Al concluir, pordrás continuar realizando tus actividades normalmente.
				           		<br>
				           		<br>
				           		Agradecemos tu apoyo, <?php echo $nombre ?>.
				           	</p>
				         </div>
				       </div>

				       
				       	<div class="modal-footer justify-content-center">
							<a type="button" class="btn btn-info btn-rounded btn-sm" data-dismiss="modal">Continuar</a>
						</div>

				    </div>
				     
			   	</div>
			</div>


			<!-- FIN ENCUESTA MODAL -->
			<!--  -->
		</div>
		<div class="col-md-2"></div>
	</div>
	

<?php  

	include('inc/footer.php');

?>

<script>
	$('#modal_encuesta').modal('show');
</script>

<script>
	$('#mainNabvar').hide();
</script>

<script>
	$('#btn_encuesta').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		cerradas = [];
		abiertas = [];
		multiples = [];
		id_rea = [];

		id_enc = <?php echo $id_enc; ?>;

        
		if ( ( $('.Cerrado').length == $('.Cerrado input:radio:checked').length ) ) {

			for( var i = 0; i < $('.Cerrado').length; i++ ){

				cerradas[i] = $('input:radio:checked').eq(i).val();
			}

			for( var i = 0; i < $('.Abierto').length; i++ ){

				abiertas[i] = $('.respuesta_abierta').eq(i).val();
				id_rea[i] = $('.respuesta_abierta').eq(i).attr('id_rea');
			}

			for( var i = 0; i < $('.respuesta_multiple:input:checkbox:checked').length; i++ ){

				multiples[i] = $('.respuesta_multiple:input:checkbox:checked').eq(i).val();
			}


			$('#btn_encuesta').html('<i class="fas fa-cog fa-spin"></i> Enviando...').removeClass('btn-info').addClass('btn-warning disabled');

			$.ajax({
				url: 'server/editar_opciones_encuesta.php',
				type: 'POST',
				data: { abiertas, cerradas, multiples, id_rea, id_enc },
				success: function( respuesta ){
					console.log( respuesta );

					if ( respuesta == 'Exito' ) {
						swal("Gracias por tu apoyo", "Continuar", "success", {button: "Continuar",}).
						then((value) => {
						  window.location.href = 'index.php';
						});
					}
				}
			});


		} else {

			toastr.error('No has completado las respuestas');

		}

	});
</script>	
<script>
	$('#mainContainer').removeClass('white');
</script>