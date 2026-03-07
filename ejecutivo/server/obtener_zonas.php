<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    
    // 🔐 VALIDAR PERMISOS PARA VER PLANTELES
    $sqlPermiso = "
        SELECT COUNT(*) as total
        FROM planteles_ejecutivo
        WHERE id_eje = '$id'
    ";
    $resultadoPermiso = obtener_datos_consulta($db, $sqlPermiso);
    $puedeVerPlanteles = ($resultadoPermiso['datos']['total'] > 1);
    
    // Obtener totales generales
    $sqlTotalesGenerales = "
        SELECT 
            COUNT(DISTINCT z.id_zon) as total_zonas,
            COUNT(DISTINCT p.id_pla) as total_centros,
            COUNT(DISTINCT e.id_eje) as total_pac
        FROM zona z
        LEFT JOIN plantel p ON p.id_zon2 = z.id_zon
        LEFT JOIN ejecutivo e ON e.id_pla = p.id_pla AND e.eli_eje = 'Activo' AND e.tip_eje = 'Ejecutivo'
        WHERE z.id_cad3 = $cadena
    ";
    $datosGenerales = obtener_datos_consulta($db, $sqlTotalesGenerales);
    $totalZonasGeneral = $datosGenerales['datos']['total_zonas'];
    $totalCentrosGeneral = $datosGenerales['datos']['total_centros'];
    $totalPacGeneral = $datosGenerales['datos']['total_pac'];
    
    // Totales de KPIs generales
    $sqlCitasGeneral = "
        SELECT SUM(obtener_citas_zona(z.id_zon, '$inicio', '$fin')) as total
        FROM zona z
        WHERE z.id_cad3 = $cadena
    ";
    $datosCitasGen = obtener_datos_consulta($db, $sqlCitasGeneral);
    $totalCitasGeneral = isset($datosCitasGen['datos']['total']) ? $datosCitasGen['datos']['total'] : 0;
    
    $sqlCitasEfeGeneral = "
        SELECT SUM(obtener_citas_efectivas_zona(z.id_zon, '$inicio', '$fin')) as total
        FROM zona z
        WHERE z.id_cad3 = $cadena
    ";
    $datosCitasEfeGen = obtener_datos_consulta($db, $sqlCitasEfeGeneral);
    $totalCitasEfeGeneral = isset($datosCitasEfeGen['datos']['total']) ? $datosCitasEfeGen['datos']['total'] : 0;
    
    $sqlRegGeneral = "
        SELECT SUM(obtener_registros_zona(z.id_zon, '$inicio', '$fin')) as total
        FROM zona z
        WHERE z.id_cad3 = $cadena
    ";
    $datosRegGen = obtener_datos_consulta($db, $sqlRegGeneral);
    $totalRegGeneral = isset($datosRegGen['datos']['total']) ? $datosRegGen['datos']['total'] : 0;
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
    
    .tablero-general {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .tablero-kpis {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .buscador-container {
        position: relative;
        max-width: 300px;
        flex-shrink: 0;
    }
    
    #buscadorGlobal {
        width: 100%;
        padding: 6px 35px 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 20px;
        font-size: 12px;
        background: #fff;
        transition: all 0.3s;
    }
    
    #buscadorGlobal:focus {
        outline: none;
        border-color: #00FFFF;
        box-shadow: 0 0 5px rgba(0, 255, 255, 0.3);
    }
    
    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #666;
    }
    
    .highlight-match {
        background-color: #ffff00 !important;
        font-weight: bold;
        padding: 2px 4px;
        border-radius: 3px;
    }
    
    .zona-card {
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    
    .zona-card.hidden {
        display: none;
    }
    
    .zona-header {
        background: #2d2d2d;
        color: white;
        padding: 8px 12px;
        border: 1px solid #2d2d2d;
        border-bottom: 2px solid #4a4a4a;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
        user-select: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .zona-header:hover {
        background: #3d3d3d;
    }
    
    .zona-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .zona-header-right {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .plantel-section {
        border: 1px solid #dee2e6;
        border-top: none;
        padding: 10px;
        background: white;
    }
    
    .plantel-header {
        font-size: 12px;
        font-weight: bold;
        padding: 5px 8px;
        background: #e9ecef;
        border-left: 3px solid #6c757d;
        margin-bottom: 8px;
        cursor: pointer;
        user-select: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }
    
    .plantel-header.hidden {
        display: none;
    }
    
    .plantel-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .plantel-header-right {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .plantel-header:hover {
        background: #dee2e6;
    }
    
    .plantel-header.fisico {
        border-left-color: #28a745;
        background: #d4edda;
    }
    
    .plantel-header.fisico:hover {
        background: #c3e6cb;
    }
    
    .plantel-header.virtual {
        border-left-color: #ffc107;
        background: #fff3cd;
    }
    
    .plantel-header.virtual:hover {
        background: #ffe69c;
    }
    
    .collapse-icon {
        transition: transform 0.3s;
        display: inline-block;
        font-size: 10px;
    }
    
    .collapse-icon.collapsed {
        transform: rotate(-90deg);
    }
    
    .badge-pac {
        background: #495057 !important;
        color: white !important;
        font-weight: bold !important;
    }
</style>

<!-- TABLERO GENERAL -->
<div class="tablero-general">
    <div class="tablero-kpis">
        <span style="font-weight: bold; color: #495057; font-size: 13px;">📊 TABLERO:</span>
        
        <span class="badge bg-light" style="color: black; font-size: 10px;">
            🎯 T. ZONAS: <?php echo $totalZonasGeneral; ?>
        </span>
        
        <span class="badge bg-light" style="color: black; font-size: 10px;">
            🕋 T. CENTROS: <?php echo $totalCentrosGeneral; ?>
        </span>
        
        <span class="badge badge-pac" style="font-size: 10px;">
            <strong>PAC: <?php echo $totalPacGeneral; ?></strong>
        </span>
        
        <span class="badge bg-light" style="color: black; font-size: 10px;">
            CIT: <?php echo $totalCitasGeneral; ?>
        </span>
        
        <span class="badge" style="background-color: #FFC0CB; color: #FF0000; font-size: 10px;">
            CIT EFE: <?php echo $totalCitasEfeGeneral; ?>
        </span>
        
        <span class="badge" style="background-color: #00FFFF; color: black; font-size: 10px;">
            REG: <?php echo $totalRegGeneral; ?>
        </span>
    </div>
    
    <div class="buscador-container">
        <input type="text" id="buscadorGlobal" placeholder="🔍 Buscar..." autocomplete="off">
        <span class="search-icon">🔍</span>
    </div>
</div>

<div class="row" id="contenedorZonas">
    <?php  
        // Obtener todas las zonas de la cadena del usuario
        $sqlZonas = "
            SELECT z.*, p.nom_pla as nom_pla_responsable
            FROM zona z
            INNER JOIN plantel p ON p.id_pla = z.id_pla22
            WHERE z.id_cad3 = $cadena
            ORDER BY z.nom_zon ASC
        ";

        $resultadoZonas = mysqli_query($db, $sqlZonas);
        $contadorZonas = 0;
        
        while($filaZona = mysqli_fetch_assoc($resultadoZonas)){
            if ($contadorZonas % 2 == 0 && $contadorZonas != 0) {
                echo '</div><div class="row">';
            }
            
            $id_zon = $filaZona['id_zon'];
            $nom_zon = $filaZona['nom_zon'];
            $id_pla_responsable = $filaZona['id_pla22'];
            
            // Contar total de ejecutivos en la zona
            $sqlTotalEjecutivosZona = "
                SELECT COUNT(DISTINCT e.id_eje) as total
                FROM ejecutivo e
                INNER JOIN plantel p ON p.id_pla = e.id_pla
                WHERE p.id_zon2 = $id_zon 
                AND e.eli_eje = 'Activo' 
                AND e.tip_eje = 'Ejecutivo'
            ";
            $datosEjecutivosZona = obtener_datos_consulta($db, $sqlTotalEjecutivosZona);
            $totalEjecutivosZona = $datosEjecutivosZona['datos']['total'];
            
            // Contar total de planteles en la zona
            $sqlTotalPlantelesZona = "
                SELECT COUNT(*) as total
                FROM plantel
                WHERE id_zon2 = $id_zon
            ";
            $datosPlantelesZona = obtener_datos_consulta($db, $sqlTotalPlantelesZona);
            $totalPlantelesZona = $datosPlantelesZona['datos']['total'];
    ?>
    
    <div class="col-md-6 zona-card-wrapper" data-zona-id="<?php echo $id_zon; ?>">
        <div class="card zona-card">
            <div class="zona-header" data-zona-id="<?php echo $id_zon; ?>" data-zona-nombre="<?php echo strtoupper($nom_zon); ?>">
                <div class="zona-header-left">
                    <span class="collapse-icon" data-zona-icon="<?php echo $id_zon; ?>">▼</span>
                    <span class="zona-nombre">🎯 <?php echo strtoupper($nom_zon); ?></span>
                </div>
                
                <div class="zona-header-right">
                    <span class="badge bg-light" style="color: black; font-size: 9px;">
                        🕋 T. CENTROS: <?php echo $totalPlantelesZona; ?>
                    </span>
                    
                    <span class="badge badge-pac" style="font-size: 9px;">
                        <strong>PAC: <?php echo $totalEjecutivosZona; ?></strong>
                    </span>
                    
                    <span class="badge bg-light" style="color: black; font-size: 9px;">
                        CIT: 
                        <?php
                            $sql = "SELECT obtener_citas_zona($id_zon, '$inicio', '$fin') AS total";
                            $datos = obtener_datos_consulta($db, $sql);
                            echo $datos['datos']['total'];
                        ?>
                    </span>

                    <span class="badge" style="background-color: #FFC0CB; color: #FF0000; font-size: 9px;">
                        CIT EFE:
                        <?php
                            $sql = "SELECT obtener_citas_efectivas_zona($id_zon, '$inicio', '$fin') AS total";
                            $datos = obtener_datos_consulta($db, $sql);
                            echo $datos['datos']['total'];
                        ?>
                    </span>

                    <span class="badge" style="background-color: #00FFFF; color: black; font-size: 9px;">
                        REG: 
                        <?php
                            $sql = "SELECT obtener_registros_zona($id_zon, '$inicio', '$fin') AS total";
                            $datos = obtener_datos_consulta($db, $sql);
                            echo $datos['datos']['total'];
                        ?>
                    </span>
                </div>
            </div>
            
            <div class="collapse show plantel-section" data-zona-collapse="<?php echo $id_zon; ?>">
                <?php
                    // Obtener todos los planteles de esta zona (físico responsable + virtuales)
                    $sqlPlanteles = "
                        SELECT *
                        FROM plantel
                        WHERE id_zon2 = $id_zon
                        ORDER BY 
                            CASE WHEN tip_pla = 'Físico' THEN 0 ELSE 1 END,
                            nom_pla ASC
                    ";
                    
                    $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
                    
                    while($filaPlantel = mysqli_fetch_assoc($resultadoPlanteles)){
                        $id_pla = $filaPlantel['id_pla'];
                        $tip_pla = $filaPlantel['tip_pla'];
                        $es_fisico = ($tip_pla == 'Físico');
                        $es_responsable = ($id_pla == $id_pla_responsable);
                        
                        // Contar total de ejecutivos en el plantel
                        $sqlTotalEjecutivos = "
                            SELECT COUNT(*) as total
                            FROM ejecutivo 
                            WHERE id_pla = $id_pla 
                            AND eli_eje = 'Activo' 
                            AND tip_eje = 'Ejecutivo'
                        ";
                        $datosEjecutivos = obtener_datos_consulta($db, $sqlTotalEjecutivos);
                        $totalEjecutivos = $datosEjecutivos['datos']['total'];
                ?>
                
                <div class="plantel-header <?php echo $es_fisico ? 'fisico' : 'virtual'; ?>" 
                     data-plantel-id="<?php echo $id_pla; ?>"
                     data-zona-id="<?php echo $id_zon; ?>"
                     data-plantel-nombre="<?php echo $filaPlantel['nom_pla']; ?>">
                    <div class="plantel-header-left">
                        <span class="collapse-icon" data-plantel-icon="<?php echo $id_pla; ?>_<?php echo $id_zon; ?>">▼</span>
                        <span class="plantel-nombre">🕋 <?php echo $filaPlantel['nom_pla']; ?></span>
                        
                        <?php if($es_responsable){ ?>
                            <span class="badge bg-success" style="font-size: 9px;">RESP</span>
                        <?php } ?>
                        
                        <span class="badge <?php echo $es_fisico ? 'bg-success' : 'bg-warning'; ?>" style="font-size: 9px; <?php echo $es_fisico ? '' : 'color: black;'; ?>">
                            <?php echo $tip_pla; ?>
                        </span>
                    </div>
                    
                    <div class="plantel-header-right">
                        <span class="badge badge-pac" style="font-size: 9px;">
                            <strong>PAC: <?php echo $totalEjecutivos; ?></strong>
                        </span>
                        
                        <a href="<?php
                            $url = "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
                            echo $url;
                        ?>" target="_blank" onclick="event.stopPropagation();">
                            <span class="badge bg-light" style="color: black; font-size: 9px;">
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
                        ?>" target="_blank" onclick="event.stopPropagation();">
                            <span class="badge" style="background-color: #FFC0CB; color: #FF0000; font-size: 9px;">
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
                        ?>" target="_blank" onclick="event.stopPropagation();">
                            <span class="badge" style="background-color: #00FFFF; color: black; font-size: 9px;">
                                REG: 
                                <?php
                                    $sql = "SELECT obtener_registros_plantel($id_pla, '$inicio', '$fin') AS total";
                                    $datos = obtener_datos_consulta($db, $sql);
                                    echo $datos['datos']['total'];
                                ?>
                            </span>
                        </a>
                    </div>
                </div>
                
                <!-- ÁRBOL RECURSIVO DEL PLANTEL -->
                <div class="collapse show" data-plantel-collapse="<?php echo $id_pla; ?>_<?php echo $id_zon; ?>">
                    <div id="dragTree<?php echo $id_pla; ?>_zona<?php echo $id_zon; ?>" style="font-size: 11px; padding-left: 10px;" class="jstree-container">
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
                            <li data-jstree='{"opened":true, "icon":false}' 
                                style="width: 25px; height: 30px; border-radius: 35px;" 
                                id="<?php echo $filaRaices['id_eje']; ?>"
                                est_eje="<?php echo $filaRaices['est_eje']; ?>"
                                per_eje="<?php echo $filaRaices['per_eje']; ?>"
                                id_pla="<?php echo $filaRaices['id_pla']; ?>"
                                data-ejecutivo-nombre="<?php echo $filaRaices['nom_eje']; ?>"
                            >
                                <?php 
                                    // SEMÁFORO
                                    echo obtener_semaforo_ejecutivo( $filaRaices['ult_eje'] ); 
                                    
                                    // 🕋 EMOJIS DE PLANTELES (solo si tiene permiso)
                                    if($puedeVerPlanteles) {
                                        $sqlPlantelesEje = "
                                            SELECT p.nom_pla
                                            FROM planteles_ejecutivo pe
                                            INNER JOIN plantel p ON pe.id_pla = p.id_pla
                                            WHERE pe.id_eje = '$id_eje'
                                            ORDER BY p.nom_pla
                                        ";
                                        $resultadoPlantelesEje = mysqli_query($db, $sqlPlantelesEje);
                                        
                                        while($filaPlantelEje = mysqli_fetch_assoc($resultadoPlantelesEje)) {
                                            echo '<span title="'.$filaPlantelEje['nom_pla'].'">🕋</span>';
                                        }
                                    }
                                ?>

                                <img src="<?php echo obtenerValidacionFotoUsuarioServer($filaRaices['fot_eje']); ?>" 
                                loading="lazy"
                                width="20" 
                                height="25" 
                                style="border-radius: 35px;" 
                                class="imagenGrande">

                                <?php
                                    if( $filaRaices['usu_eje'] == null ){
                                        echo obtener_rango_usuario_badge( $filaRaices['ran_eje'] );
                                    } else {
                                        echo obtener_usuario_ejecutivo( $filaRaices['usu_eje'], $filaRaices['est_eje'] );
                                    }
                                ?>

                                <?php 
                                    echo ($filaRaices['per_eje'] == 1) ? '<span class="badge bg-success">Permisos CDE</span>' : 
                                        ( ( $filaRaices['per_eje'] == 2) ? '<span class="badge bg-success">Permisos AHJ ENDE</span>' : '');
                                ?>
                                
                                <span title="<?php echo $filaRaices['nom_eje']; ?>" class="ejecutivo-nombre <?php if ($filaRaices['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
                                    <?php echo obtenerPrimerasDosPalabras($filaRaices['nom_eje']); ?>
                                </span>

                                <?php generarNodosHijos($filaRaices['id_eje'], $db, $inicio, $fin, $puedeVerPlanteles); ?>
                            </li>
                        </ul>
                        <?php } ?>
                    </div>
                </div>
                
                <script type="text/javascript">
                (function() {
                    var idPla = <?php echo $id_pla; ?>;
                    var idZon = <?php echo $id_zon; ?>;
                    
                    $("#dragTree" + idPla + "_zona" + idZon).jstree({
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
                                            obtenerDatosNodo(node, idPla, idZon);
                                        }
                                    },
                                    "deleteItem": {
                                        "label": "Eliminar",
                                        "action": function(obj) {
                                            eliminarNodoConValidacion(node, idPla, idZon);
                                        }
                                    },
                                    "SwitchItem": {
                                        "label": "Activa/Desactiva",
                                        "action": function(obj) {
                                            switchearNodo(node, idPla, idZon);
                                        }
                                    },
                                    "permisosItem": {
                                        "label": "Otorgar/Quitar permisos CDE",
                                        "action": function(obj) {
                                            permisosNodo(node, idPla, idZon);
                                        }
                                    },
                                    <?php 
                                        $sqlPlantelesEjecutivo = "
                                            SELECT *
                                            FROM planteles_ejecutivo
                                            WHERE id_eje = $id
                                        ";
                                        $totalPlantelesEjecutivo = obtener_datos_consulta($db, $sqlPlantelesEjecutivo)['total'];

                                        if( $totalPlantelesEjecutivo > 1 ){
                                    ?>
                                            "permisosItemMarca": {
                                                "label": "Otorgar/Quitar permisos AHJ ENDE",
                                                "action": function(obj) {
                                                    permisosNodoMarca(node, idPla, idZon);
                                                }
                                            },
                                    <?php
                                        }
                                    ?>
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
                                    }
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
                            }
                        });
                    });
                })();

                function permisosNodoMarca(node, idPla, idZon) {
                    var id_eje = node.id;
                    var per_eje = node.li_attr.per_eje;
                    
                    if ( per_eje == 0  || per_eje == 1 ) {
                        per_eje = 2;
                    } else {
                        per_eje = 0;
                    }

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
                            console.log(datos);
                            toastr.success('Cambios guardados :D');
                            obtener_datos();
                        }
                    });
                }

                function permisosNodo(node, idPla, idZon) {
                    var id_eje = node.id;
                    var per_eje = node.li_attr.per_eje;
                    
                    if (per_eje == 1) {
                        per_eje = 0;
                    } else {
                        per_eje = 1;
                    }

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
                            console.log(datos);
                            toastr.success('Cambios guardados :D');
                            obtener_datos();
                        }
                    });
                }

                function switchearNodo(node, idPla, idZon) {
                    var id_eje = node.id;
                    var est_eje = node.li_attr.est_eje;
                    
                    if (est_eje == 'Activo') {
                        est_eje = 'Inactivo';
                    } else {
                        est_eje = 'Activo';
                    }

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
                            toastr.success('Cambios guardados :D');
                            obtener_datos();
                        }
                    });
                }

                function obtenerDatosNodo(node, idPla, idZon) {
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
                            console.log(datos);
                            $('#modal_agregar_asesor').modal('show');

                            $('#nom_eje').val(datos.nom_eje);
                            $('#ran_eje').val(datos.ran_eje);
                            $('#id_pla').val(datos.id_pla);
                            $('#tel_eje').val(datos.tel_eje);
                            $('#cor_eje').val(datos.cor_eje);
                            $('#pas_eje').val(datos.pas_eje);
                            $('#obs_eje').val(datos.obs_eje);
                            $('#id_eje').val(id_eje);

                            $('#id_pla option[value="' + datos.id_pla + '"]').prop('selected', true);

                            $('#formulario_agregar_asesor').removeAttr('estatus').attr('estatus', 'Cambio');
                        }
                    });
                }

                function eliminarNodoConValidacion(node, idPla, idZon) {
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
                            
                            return swal({
                                title: "¿Deseas eliminar este registro?",
                                text: "¡Valida para continuar!",
                                icon: "warning",
                                buttons: {
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
                            });
                        })
                        .then(willDelete => {
                            if (!willDelete) {
                                swal.stopLoading();
                                swal.close();
                                throw null;
                            }

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
                            obtener_datos();
                        })
                        .catch(error => {
                            if (error) {
                                swal("Error", "No se pudo eliminar el registro", "error");
                            }
                        });
                }
                </script>
                
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?php 
        $contadorZonas++;
        }
    ?>
</div>

<script>
// ============================================
// SISTEMA DE LOCALSTORAGE CON EVENT DELEGATION
// ============================================

// Event delegation para clicks en zonas
document.addEventListener('click', function(e) {
    const zonaHeader = e.target.closest('.zona-header');
    if (zonaHeader) {
        const idZona = zonaHeader.getAttribute('data-zona-id');
        if (idZona) {
            toggleZona(idZona);
        }
    }
});

// Event delegation para clicks en planteles
document.addEventListener('click', function(e) {
    const plantelHeader = e.target.closest('.plantel-header');
    if (plantelHeader && !e.target.closest('a')) {
        const idPla = plantelHeader.getAttribute('data-plantel-id');
        const idZona = plantelHeader.getAttribute('data-zona-id');
        if (idPla && idZona) {
            togglePlantel(idPla, idZona);
        }
    }
});

function toggleZona(idZona) {
    const icon = document.querySelector(`[data-zona-icon="${idZona}"]`);
    const collapseElement = document.querySelector(`[data-zona-collapse="${idZona}"]`);
    
    if (!collapseElement) return;
    
    const isCurrentlyShown = collapseElement.classList.contains('show');
    
    if (isCurrentlyShown) {
        collapseElement.classList.remove('show');
        if (icon) icon.classList.add('collapsed');
        localStorage.setItem('accordion_state_zona_' + idZona, 'collapsed');
    } else {
        collapseElement.classList.add('show');
        if (icon) icon.classList.remove('collapsed');
        localStorage.setItem('accordion_state_zona_' + idZona, 'expanded');
    }
}

function togglePlantel(idPla, idZona) {
    const icon = document.querySelector(`[data-plantel-icon="${idPla}_${idZona}"]`);
    const collapseElement = document.querySelector(`[data-plantel-collapse="${idPla}_${idZona}"]`);
    
    if (!collapseElement) return;
    
    const isCurrentlyShown = collapseElement.classList.contains('show');
    
    if (isCurrentlyShown) {
        collapseElement.classList.remove('show');
        if (icon) icon.classList.add('collapsed');
        localStorage.setItem('accordion_state_plantel_' + idPla + '_zona_' + idZona, 'collapsed');
    } else {
        collapseElement.classList.add('show');
        if (icon) icon.classList.remove('collapsed');
        localStorage.setItem('accordion_state_plantel_' + idPla + '_zona_' + idZona, 'expanded');
    }
}

// Restaurar estados de localStorage al cargar
function restaurarEstados() {
    // Restaurar zonas
    document.querySelectorAll('[data-zona-collapse]').forEach(function(element) {
        const dataAttr = element.getAttribute('data-zona-collapse');
        const idZona = dataAttr;
        const estado = localStorage.getItem('accordion_state_zona_' + idZona);
        
        if (estado === 'collapsed') {
            element.classList.remove('show');
            const icon = document.querySelector(`[data-zona-icon="${idZona}"]`);
            if (icon) icon.classList.add('collapsed');
        }
    });
    
    // Restaurar planteles
    document.querySelectorAll('[data-plantel-collapse]').forEach(function(element) {
        const dataAttr = element.getAttribute('data-plantel-collapse');
        const matches = dataAttr.match(/(\d+)_(\d+)/);
        
        if (matches) {
            const idPla = matches[1];
            const idZona = matches[2];
            const estado = localStorage.getItem('accordion_state_plantel_' + idPla + '_zona_' + idZona);
            
            if (estado === 'collapsed') {
                element.classList.remove('show');
                const icon = document.querySelector(`[data-plantel-icon="${idPla}_${idZona}"]`);
                if (icon) icon.classList.add('collapsed');
            }
        }
    });
}

// Ejecutar al cargar
document.addEventListener('DOMContentLoaded', restaurarEstados);

// ============================================
// BUSCADOR GLOBAL POTENTE
// ============================================
let searchTimeout;

document.getElementById('buscadorGlobal').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(function() {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        // Limpiar highlights anteriores
        document.querySelectorAll('.highlight-match').forEach(function(el) {
            el.outerHTML = el.innerHTML;
        });
        
        if (searchTerm === '') {
            // Mostrar todo
            document.querySelectorAll('.zona-card-wrapper').forEach(function(zona) {
                zona.style.display = '';
            });
            document.querySelectorAll('.plantel-header').forEach(function(plantel) {
                plantel.classList.remove('hidden');
            });
            document.querySelectorAll('.jstree-container').forEach(function(tree) {
                tree.querySelectorAll('li').forEach(function(li) {
                    li.style.display = '';
                });
            });
            return;
        }
        
        let foundAny = false;
        
        // Buscar en zonas, planteles y ejecutivos
        document.querySelectorAll('.zona-card-wrapper').forEach(function(zonaWrapper) {
            let zonaHasMatch = false;
            const zona = zonaWrapper.querySelector('.zona-header');
            const zonaNombre = zona.getAttribute('data-zona-nombre').toLowerCase();
            
            // Buscar en nombre de zona
            if (zonaNombre.includes(searchTerm)) {
                highlightText(zona.querySelector('.zona-nombre'), searchTerm);
                zonaHasMatch = true;
                foundAny = true;
            }
            
            // Buscar en planteles
            zonaWrapper.querySelectorAll('.plantel-header').forEach(function(plantel) {
                const plantelNombre = plantel.getAttribute('data-plantel-nombre').toLowerCase();
                let plantelHasMatch = false;
                
                if (plantelNombre.includes(searchTerm)) {
                    highlightText(plantel.querySelector('.plantel-nombre'), searchTerm);
                    plantelHasMatch = true;
                    zonaHasMatch = true;
                    foundAny = true;
                    
                    // Expandir plantel
                    const idPla = plantel.getAttribute('data-plantel-id');
                    const idZona = plantel.getAttribute('data-zona-id');
                    const collapseElement = document.querySelector(`[data-plantel-collapse="${idPla}_${idZona}"]`);
                    if (collapseElement) {
                        collapseElement.classList.add('show');
                        const icon = document.querySelector(`[data-plantel-icon="${idPla}_${idZona}"]`);
                        if (icon) icon.classList.remove('collapsed');
                    }
                }
                
                // Buscar en ejecutivos del plantel
                const idPla = plantel.getAttribute('data-plantel-id');
                const idZona = plantel.getAttribute('data-zona-id');
                const treeContainer = document.getElementById(`dragTree${idPla}_zona${idZona}`);
                
                if (treeContainer) {
                    let ejecutivoMatch = false;
                    treeContainer.querySelectorAll('li[data-ejecutivo-nombre]').forEach(function(li) {
                        const ejecutivoNombre = li.getAttribute('data-ejecutivo-nombre').toLowerCase();
                        
                        if (ejecutivoNombre.includes(searchTerm)) {
                            highlightText(li.querySelector('.ejecutivo-nombre'), searchTerm);
                            li.style.display = '';
                            ejecutivoMatch = true;
                            plantelHasMatch = true;
                            zonaHasMatch = true;
                            foundAny = true;
                        } else {
                            li.style.display = 'none';
                        }
                    });
                    
                    if (ejecutivoMatch) {
                        // Expandir plantel
                        const collapseElement = document.querySelector(`[data-plantel-collapse="${idPla}_${idZona}"]`);
                        if (collapseElement) {
                            collapseElement.classList.add('show');
                            const icon = document.querySelector(`[data-plantel-icon="${idPla}_${idZona}"]`);
                            if (icon) icon.classList.remove('collapsed');
                        }
                        plantel.classList.remove('hidden');
                    }
                }
                
                // Mostrar/ocultar plantel
                if (!plantelHasMatch) {
                    plantel.classList.add('hidden');
                } else {
                    plantel.classList.remove('hidden');
                }
            });
            
            // Mostrar/ocultar zona
            if (!zonaHasMatch) {
                zonaWrapper.style.display = 'none';
            } else {
                zonaWrapper.style.display = '';
                // Expandir zona
                const idZona = zona.getAttribute('data-zona-id');
                const collapseElement = document.querySelector(`[data-zona-collapse="${idZona}"]`);
                if (collapseElement) {
                    collapseElement.classList.add('show');
                    const icon = document.querySelector(`[data-zona-icon="${idZona}"]`);
                    if (icon) icon.classList.remove('collapsed');
                }
            }
        });
    }, 300);
});

function highlightText(element, searchTerm) {
    if (!element) return;
    
    const text = element.textContent;
    const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
    const highlighted = text.replace(regex, '<span class="highlight-match">$1</span>');
    element.innerHTML = highlighted;
}

function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}
</script>

<?php
    // Si no hay zonas creadas
    $totalZonas = mysqli_num_rows($resultadoZonas);
    mysqli_data_seek($resultadoZonas, 0);
    
    if($totalZonas == 0){
?>
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h4>📍 No hay zonas configuradas</h4>
            <p>Aún no se han creado zonas para esta cadena.</p>
        </div>
    </div>
<?php } ?>