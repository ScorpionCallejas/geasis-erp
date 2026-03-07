<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $totalRegistros = 0;
?>

<h3>TOP CONSULTORES</h3>

<div class="row">
    <div class="col-md-6"> <!-- Esta columna contendrá todas las tarjetas -->

        <h4>CDE</h4>
        <?php  
            $sqlPlanteles = "
                SELECT *
                FROM planteles_ejecutivo
                INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
                WHERE id_eje = '$id'
            ";

            $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

            while ($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
                $id_pla = $filaPlanteles['id_pla'];
        ?>
        <div class="card mb-3"> <!-- Se añadió mb-3 para margen inferior entre tarjetas -->
            <div class="card-body border">
                <h4 class="header-title mt-0 mb-3">
                    🕋<?php echo $filaPlanteles['nom_pla']; ?>


                    <a href="<?php
                        $url = "registros.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
                        echo $url;
                    ?>" target="_blank">
                        <span class="badge bg-black">
                            REGISTROS: 
                            <?php
                                $sql = "SELECT obtener_registros_plantel($id_pla, '$inicio', '$fin') AS total";
                                $datos = obtener_datos_consulta($db, $sql);
                                $totalRegistros += $datos['datos']['total']; 
                                echo $datos['datos']['total'];
                            ?>
                        </span>
                    </a>
                </h4>

                <div id="dragTree<?php echo $id_pla; ?>">
                    <!--  -->
                    <?php  
                        $sqlRaices = "
                            SELECT *,
                            obtener_registros_ejecutivo(id_eje, '$inicio', '$fin') AS total_registros,
                            obtener_citas_ejecutivo(id_eje, '$inicio', '$fin') AS total_citas
                            FROM ejecutivo
                            WHERE id_pla = '$id_pla' AND tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND ran_eje != 'DC'
                            ORDER BY total_registros DESC;
                        ";
                        $resultadoRaices = mysqli_query($db, $sqlRaices);
                        $contador = 1;

                        while ($filaRaices = mysqli_fetch_assoc($resultadoRaices)) {
                            $id_eje = $filaRaices['id_eje'];
                    ?>
                    <ul>
                        <li data-jstree='{"opened":true, "icon":false}' 
                            id="<?php echo $filaRaices['id_eje']; ?>"
                            est_eje="<?php echo $filaRaices['est_eje']; ?>"
                            per_eje="<?php echo $filaRaices['per_eje']; ?>"
                            id_pla="<?php echo $filaRaices['id_pla']; ?>"
                        >
                            
                            <div style="width: 25px; height: 25px; background-color: <?php echo $medalColor; ?>; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; color: <?php echo $textColor; ?>; font-size: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); margin-right: 10px;">
                                <?php echo $contador; ?>
                            </div>

                            <?php //echo obtener_semaforo_ejecutivo( $filaRaices['ult_eje'], $id_eje, $db, $filaRaices['eli_eje'] ); ?>
                            <?php echo obtener_semaforo_ejecutivo( $filaRaices['ult_eje'] ); ?>
                            <img src="<?php echo obtenerValidacionFotoUsuarioServer($filaRaices['fot_eje']); ?>" style="width: 20px; height: 25px; border-radius: 35px;" class="imagenGrande">
                    
                            <?php            
                                if( $filaRaices['usu_eje'] == null ){
                                    echo obtener_rango_usuario_badge( $filaRaices['ran_eje'] );
                                } else {
                                    echo obtener_usuario_ejecutivo( $filaRaices['usu_eje'], $filaRaices['est_eje'] );
                                }
                            ?>

                            <?php 
                                echo ($filaRaices['per_eje'] == 1) ? '<span class="badge bg-success">Permisos CDE</span>' : 
                                    (($filaRaices['per_eje'] == 2) ? '<span class="badge bg-success">Permisos AHJ ENDE</span>' : ''); 
                            ?>
                            <span title="<?php echo $filaRaices['nom_eje']; ?>" class="<?php if ($filaRaices['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
                                <?php echo obtenerPrimerasDosPalabras($filaRaices['nom_eje']); ?>
                            </span>

                            <span class="badge bg-secondary">
                                CITAS: <?php 
                                    $sql = "SELECT obtener_citas_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>

                            <span class="badge bg-black">
                                REGISTROS: <?php 
                                    $sql = "SELECT obtener_registros_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>
                        </li>
                    </ul>
                    <?php 
                            $contador++;
                        } 
                    ?>
                    <!--  -->
                </div>

                <script type="text/javascript">
                    $("#dragTree<?php echo $id_pla; ?>").jstree({
                        // dnd: {
                        //     is_draggable: function(node) {
                        //         return true;
                        //     }
                        // },
                        
                    });

                </script>
            </div>
        </div>
        <?php 
            }
        ?>
    </div> <!-- Fin de la columna col-md-6 -->
    <div class="col-md-6">
        <!--  -->
        <h4>NACIONAL</h4>
        <div class="card mb-3"> <!-- Se añadió mb-3 para margen inferior entre tarjetas -->
            <div class="card-body border">
                <h4 class="header-title mt-0 mb-3">
                    🕋AHJ ENDE - NACIONAL

                    <a href="<?php
                        $url = "registros.php?inicio=$inicio&fin=$fin";
                        echo $url;
                    ?>" target="_blank">
                        <span class="badge bg-black">
                            REGISTROS: 
                            <?php
                                echo $totalRegistros;
                            ?>
                        </span>
                    </a>
                </h4>

                <?php 
                    $sqlRaices = "
                        SELECT *,
                        obtener_registros_ejecutivo(id_eje, '$inicio', '$fin') AS total_registros,
                        obtener_citas_ejecutivo(id_eje, '$inicio', '$fin') AS total_citas
                        FROM ejecutivo
                        INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                        WHERE plantel.id_cad1 = '1' AND tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND ran_eje != 'DC'
                        ORDER BY total_registros DESC;
                    ";

                    // echo $sqlRaices;
                ?>
                <div id="dragTree<?php echo "abc"; ?>">
                    <!--  -->
                    <?php  
                        
                        $resultadoRaices = mysqli_query($db, $sqlRaices);
                        $contador = 1;

                        while ($filaRaices = mysqli_fetch_assoc($resultadoRaices)) {
                            $id_eje = $filaRaices['id_eje'];
                    ?>
                    <ul>
                        <li data-jstree='{"opened":true, "icon":false}' 
                            id="<?php echo $filaRaices['id_eje']; ?>"
                            est_eje="<?php echo $filaRaices['est_eje']; ?>"
                            per_eje="<?php echo $filaRaices['per_eje']; ?>"
                            id_pla="<?php echo $filaRaices['id_pla']; ?>"
                        >
                            
                            <div style="width: 25px; height: 25px; background-color: <?php echo $medalColor; ?>; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; color: <?php echo $textColor; ?>; font-size: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); margin-right: 10px;">
                                <?php echo $contador; ?>
                            </div>

                            <?php echo obtener_semaforo_ejecutivo($filaRaices['ult_eje']); ?>
                            
                            <img src="<?php echo obtenerValidacionFotoUsuarioServer($filaRaices['fot_eje']); ?>" style="width: 20px; height: 25px; border-radius: 35px;" class="imagenGrande">
                            
                            <?php            
                                if( $filaRaices['usu_eje'] == null ){
                                    echo obtener_rango_usuario_badge( $filaRaices['ran_eje'] );
                                } else {
                                    echo obtener_usuario_ejecutivo( $filaRaices['usu_eje'], $filaRaices['est_eje'] );
                                }
                            ?>

                            <?php 
                                echo ($filaRaices['per_eje'] == 1) ? '<span class="badge bg-success">Permisos CDE</span>' : 
                                    (($filaRaices['per_eje'] == 2) ? '<span class="badge bg-success">Permisos AHJ ENDE</span>' : ''); 
                            ?>
                            <span title="<?php echo $filaRaices['nom_eje']; ?>" class="<?php if ($filaRaices['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
                                <?php echo obtenerPrimerasDosPalabras($filaRaices['nom_eje']); ?>
                            </span>

                            <span class="badge bg-secondary">
                                CITAS: <?php 
                                    $sql = "SELECT obtener_citas_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>
                            
                            <span class="badge bg-black">
                                REGISTROS: <?php 
                                    $sql = "SELECT obtener_registros_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>
                        </li>
                    </ul>
                    <?php 
                            $contador++;
                        } 
                    ?>
                    <!--  -->
                </div>

                <script type="text/javascript">
                    $("#dragTree<?php echo "abc"; ?>").jstree({
                        // dnd: {
                        //     is_draggable: function(node) {
                        //         return true;
                        //     }
                        // },
                        
                    });

                </script>
            </div>
        </div>
      
        <!--  -->
    </div>
</div>