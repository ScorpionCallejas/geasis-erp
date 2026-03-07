<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
?>

<style>
    .imagenGrande {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .imagenGrande:hover {
        transform: scale(2);
        z-index: 100;
    }

</style>

<div class="row">
    <?php  
        $sqlPlanteles = "
            SELECT *
            FROM planteles_ejecutivo
            INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
            WHERE id_eje = '$id'
        ";

        $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
        $contadorCols = 0;

        while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
            if ($contadorCols % 2 == 0 && $contadorCols != 0) {
                echo '</div><div class="row">';
            }
            $id_pla = $filaPlanteles['id_pla'];
    ?>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body border">
                <h4 class="header-title mt-0 mb-3">
                    🕋<?php echo $filaPlanteles['nom_pla']; ?>
                
                    <a href="<?php
                        $url = "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
                        echo $url;
                    ?>" target="_blank">
                        <span class="badge bg-light" style="color: black;">
                            CIT: 
                            <?php
                                $sql = "SELECT obtener_citas_plantel($id_pla, '$inicio', '$fin') AS total";
                                $datos = obtener_datos_consulta($db, $sql);
                                echo $datos['datos']['total'];
                            ?>
                        </span>
                    </a>

                    <a href="<?php
                        $url = "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
                        echo $url;
                    ?>" target="_blank">
                        <span class="badge" style="background-color: #FFC0CB; color: #FF0000;">
                            CIT EFE:
                            <?php
                                $sql = "SELECT obtener_citas_efectivas_plantel($id_pla, '$inicio', '$fin') AS total";
                                $datos = obtener_datos_consulta($db, $sql);
                                echo $datos['datos']['total'];
                            ?>
                        </span>
                    </a>


                    <a href="<?php
                        $url = "registros.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
                        echo $url;
                    ?>" target="_blank">
                        <span class="badge " style="background-color: #00FFFF; color: black;">
                            REG: 
                            <?php
                                $sql = "SELECT obtener_registros_plantel($id_pla, '$inicio', '$fin') AS total";
                                $datos = obtener_datos_consulta($db, $sql);
                                echo $datos['datos']['total'];
                            ?>
                        </span>
                    </a>

                </h4>
                <div id="dragTree<?php echo $id_pla; ?>">
                    <?php  
                        $sqlRaices = "
                            SELECT *, obtener_conteo_recursivo_registros_ejecutivo(id_eje, '$inicio', '$fin') AS total_registros
                            FROM ejecutivo 
                            WHERE id_pla = '$id_pla' AND id_padre IS NULL AND eli_eje = 'Activo' AND ran_eje != 'DC'
                            AND tip_eje = 'Ejecutivo'
                        ";
                        $resultadoRaices = mysqli_query($db, $sqlRaices);

                        while ($filaRaices = mysqli_fetch_assoc($resultadoRaices)) {
                            $id_eje = $filaRaices['id_eje'];
                    ?>
                    <ul>
                        <li data-jstree='{"opened":true, "icon":false}' style="width: 25px; height: 30px; border-radius: 35px;" 
                            id="<?php echo $filaRaices['id_eje']; ?>"
                            est_eje="<?php echo $filaRaices['est_eje']; ?>"
                            per_eje="<?php echo $filaRaices['per_eje']; ?>"
                            id_pla="<?php echo $filaRaices['id_pla']; ?>"
                        >
                            <?php //echo obtener_semaforo_ejecutivo( $filaRaices['ult_eje'], $id_eje, $db, $filaRaices['eli_eje'] ); ?>

                            <?php echo obtener_semaforo_ejecutivo( $filaRaices['ult_eje'] ); ?>

                            <!-- <div id="imagenGrande-container"> -->
                                <img src="<?php echo obtenerValidacionFotoUsuarioServer($filaRaices['fot_eje']); ?>" style="width: 20px; height: 25px; border-radius: 35px;" class="imagenGrande">
                            <!-- </div>    -->
                            
                            <?php 
                                echo ($filaRaices['per_eje'] == 1) ? '<span class="badge bg-success">Permisos CDE</span>' : 
                                    (($filaRaices['per_eje'] == 2) ? '<span class="badge bg-success">Permisos AHJ ENDE</span>' : ''); 
                            ?>

                            <span class="<?php if ($filaRaices['est_eje'] == 'Inactivo') echo 'text-danger'; ?> badge rounded-pill badge-outline-<?php 
                                echo $filaRaices['ran_eje'] == 'GC' ? 'dark' : 
                                     ($filaRaices['ran_eje'] == 'GR' ? 'success' : 'primary'); 
                            ?>">
                                <?php echo obtener_rango_usuario($filaRaices['ran_eje']); ?>
                            </span>
                            <span title="<?php echo $filaRaices['nom_eje']; ?>" class="<?php if ($filaRaices['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
                                <?php echo obtenerPrimerasDosPalabras($filaRaices['nom_eje']); ?>
                            </span>
                           

                            <?php 
                                if( $filaRaices['per_eje'] == 1 || $filaRaices['per_eje'] == 2 ){
                            ?>
                                    <span class="badge bg-pink" style="color: black;">
                                        CIT AGE: <?php 
                                            $sql = "SELECT obtener_citas_agendadas_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                            $datos = obtener_datos_consulta($db, $sql);
                                            echo $datos['datos']['total'];
                                        ?>
                                    </span>
                            <?php
                                }
                            ?>
                            

                            <span class="badge bg-light" style="color: black;">
                                CIT: <?php 
                                    $sql = "SELECT obtener_citas_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>

                            <span class="badge" style="background-color: #FFC0CB; color: #FF0000;">
                                CIT EFE: <?php 
                                    $sql = "SELECT obtener_citas_efectivas_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>

                            <span class="badge " style="background-color: #00FFFF; color: black;">
                                REG: <?php 
                                    $sql = "SELECT obtener_registros_ejecutivo($id_eje, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>
                            <?php generarNodosHijos($filaRaices['id_eje'], $db, $inicio, $fin); ?>
                        </li>
                    </ul>
                    <?php } ?>
                </div>
                <script type="text/javascript">
                $("#dragTree<?php echo $id_pla; ?>").jstree({
                    dnd: {
                        is_draggable: function(node) {
                            return true;
                        }
                    },
                    core: {
                        check_callback: true,
                        themes: {
                            responsive: false
                        }
                    },
                    types: {
                        default: {
                            icon: "fas fa-user"
                        }
                    },
                    plugins: ["types", "dnd", "contextmenu"],
                    contextmenu: {
                        items: function(node) {
                            return {
                                "editItem": {
                                    "label": "Consultar",
                                    "action": function(obj) {
                                        obtenerDatosNodo(node);
                                    }
                                },
                                "deleteItem": {
                                    "label": "Eliminar",
                                    "action": function(obj) {
                                        eliminarNodoConValidacion(node);
                                    }
                                },
                                "SwitchItem": {
                                    "label": "Activa/Desactiva",
                                    "action": function(obj) {
                                        switchearNodo(node);
                                    }
                                },
                                "permisosItem": {
                                    "label": "Otorgar/Quitar permisos CDE",
                                    "action": function(obj) {
                                        permisosNodo(node);
                                    }
                                },

                                "permisosItemMarca": {
                                    "label": "Otorgar/Quitar permisos AHJ ENDE",
                                    "action": function(obj) {
                                        permisosNodoMarca(node);
                                    }
                                },

                                "reportItemRegistrosConsultor": {
                                    "label": "Consultar registros",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'registros.php?id_pla='+node.li_attr.id_pla+'&escala=ejecutivo&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },
                                "reportItemCitasConsultor": {
                                    "label": "Consultar citas",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'citas.php?id_pla='+node.li_attr.id_pla+'&escala=ejecutivo&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },
                                "reportItemContactosConsultor": {
                                    "label": "Consultar contactos",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'referidos.php?id_pla='+node.li_attr.id_pla+'&escala=ejecutivo&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },

                                "reportItemRegistros": {
                                    "label": "Consultar registros por equipo",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'registros.php?escala=estructura&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },

                                <?php /** 
                                 
                                "reportItemRegistros": {
                                    "label": "Registros por equipo",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'registros.php?escala=estructura&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },
                                "reportItemCitas": {
                                    "label": "Citas por equipo",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'citas.php?escala=estructura&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },
                                "reportItemContactos": {
                                    "label": "Contactos por equipo",
                                    "action": function(obj) {
                                        var inicio = '<?php echo $inicio; ?>';
                                        var fin = '<?php echo $fin; ?>';
                                        var url = 'referidos.php?escala=estructura&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                        window.open(url, '_blank');
                                    }
                                },
                                */ ?>
                            };
                        }
                    }
                }).on('move_node.jstree', function(e, data) {
                    var idHijo = data.node.id;
                    var idPadre = data.parent;

                    if (idPadre == '#') {
                        idPadre = 0;
                    }

                    var accion = 'Cambio';

                    $.ajax({
                        url: 'server/controlador_estructuras_comerciales.php',
                        type: 'POST',
                        data: {
                            idHijo,
                            idPadre,
                            accion
                        },
                        success: function(response) {
                            console.log(response);

                            toastr.success('Cambios guardados :D');
                            // setTimeout(() => {
                            //     obtener_datos();
                            // }, 200);

                        }
                    });
                });


                function permisosNodoMarca(node) {

                    var id_eje = node.id;
                    var per_eje = node.li_attr.per_eje;
                    
                    // alert('llegué: '+per_eje);

                    if ( per_eje == 0  || per_eje == 1 ) {
                        per_eje = 2;
                    } else {
                        per_eje = 0;
                    }


                    // var id_eje = node.id;
                    // var per_eje = node.li_attr.per_eje;
                    // alert('se fue: '+per_eje);

                    var estatus = 'Permisos';
                    var proc = 'estructuras_comerciales';
                    // alert( per_eje );
                    $.ajax({
                        url: 'server/controlador_ejecutivo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_eje,
                            estatus,
                            proc,
                            per_eje
                        },
                        success: function(datos) {
                            /////CODIGO AQUÍ abajo

                            console.log(datos);
                            toastr.success('Cambios guardados :D');
                            obtener_datos();
                            
                        }
                    });
                }

                function permisosNodo(node) {

                    var id_eje = node.id;
                    var per_eje = node.li_attr.per_eje;
                    
                    //alert('llegué: '+per_eje);
                    if (per_eje == 1) {
                        per_eje = 0;
                    } else {
                        per_eje = 1;
                    }

                    // alert('se fue: '+per_eje);

                    var estatus = 'Permisos';
                    var proc = 'estructuras_comerciales';
                    $.ajax({
                        url: 'server/controlador_ejecutivo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_eje,
                            estatus,
                            proc,
                            per_eje
                        },
                        success: function(datos) {
                            /////CODIGO AQUÍ abajo

                            console.log(datos);
                            toastr.success('Cambios guardados :D');
                            obtener_datos();
                            
                        }
                    });
                }

                function switchearNodo(node) {

                    var id_eje = node.id;
                    var est_eje = node.li_attr.est_eje;
                    //alert('llegué: '+est_eje);
                    if (est_eje == 'Activo') {
                        est_eje = 'Inactivo';
                    } else {
                        est_eje = 'Activo';
                    }

                    //alert('se fue: '+est_eje);

                    var estatus = 'Switch';
                    var proc = 'estructuras_comerciales';
                    $.ajax({
                        url: 'server/controlador_ejecutivo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_eje,
                            estatus,
                            proc,
                            est_eje
                        },
                        success: function(datos) {
                            /////CODIGO AQUÍ abajo
                            toastr.success('Cambios guardados :D');
                            obtener_datos();
                        }
                    });
                }

                function obtenerDatosNodo(node) {

                    var id_eje = node.id;
                    var estatus = 'Despliegue';
                    var proc = 'estructuras_comerciales';
                    $.ajax({
                        url: 'server/controlador_ejecutivo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_eje,
                            estatus,
                            proc
                        },
                        success: function(datos) {
                            /////CODIGO AQUÍ abajo
                            console.log(datos);
                            $('#modal_agregar_asesor').modal('show');

                            // Rellena los campos del formulario con los datos obtenidos
                            $('#nom_eje').val(datos.nom_eje);
                            $('#ran_eje').val(datos.ran_eje);
                            $('#id_pla').val(datos.id_pla);
                            $('#tel_eje').val(datos.tel_eje);
                            $('#cor_eje').val(datos.cor_eje);
                            $('#pas_eje').val(datos.pas_eje);
                            $('#obs_eje').val(datos.obs_eje);
                            $('#id_eje').val(id_eje);

                            $('#id_pla option[value="' + datos.id_pla + '"]').prop('selected', true);

                            console.log('ejecutivo origen plantel: '+datos.id_pla);

                            $('#formulario_agregar_asesor').removeAttr('estatus').attr('estatus', 'Cambio');
                        }
                    });
                }

                function eliminarNodoConValidacion(node) {
                    swal({
                            title: "¡Acceso Restringido!",
                            icon: "warning",
                            text: 'Necesitas permisos para continuar',
                            content: {
                                element: "input",
                                attributes: {
                                    placeholder: "Ingresa tu contraseña...",
                                    type: "password",
                                },
                            },
                            button: {
                                text: "Validar",
                                closeModal: false,
                            },
                        })
                        .then(password => {
                            if (!password) {
                                swal.stopLoading();
                                swal.close();
                                throw null;
                            }

                            return $.ajax({
                                url: 'server/validacion_permisos.php',
                                type: 'POST',
                                data: {
                                    password: password
                                },
                            });
                        })
                        .then(response => {
                            if (response !== 'True') {
                                swal.stopLoading();
                                swal.close();
                                throw new Error('Contraseña incorrecta.');
                            }
                            // Proceso de confirmación de eliminación
                            return swal({
                                title: "¿Deseas eliminar este registro?",
                                text: "¡Valida para continuar!",
                                icon: "warning",
                                buttons: {
                                    cancel: {
                                        text: "Cancelar", // Texto del botón para cancelar
                                        value: null,
                                        visible: true,
                                        className: "",
                                        closeModal: true,
                                    },
                                    confirm: {
                                        text: "Confirmar", // Texto del botón para confirmar
                                        value: true,
                                        visible: true,
                                        className: "",
                                        closeModal: true
                                    }
                                },
                                dangerMode: true,
                            });
                        })
                        .then(willDelete => {
                            if (!willDelete) {
                                swal.stopLoading();
                                swal.close();
                                throw null;
                            }

                            // Eliminación del nodo
                            return $.ajax({
                                url: 'server/controlador_estructuras_comerciales.php',
                                type: 'POST',
                                data: {
                                    id_eje: node.id,
                                    accion: 'Baja'
                                },
                            });
                        })
                        .then(response => {
                            // Asumiendo que tu servidor devuelve una respuesta que puedes evaluar

                            obtener_datos();

                        })
                        .catch(error => {
                            if (error) {
                                swal("Error", "No se pudo eliminar el registro", "error");
                            }
                        });
                }
                </script>
            </div>
        </div>
    </div>
    <?php 
        $contadorCols++;
        }
    ?>
</div>