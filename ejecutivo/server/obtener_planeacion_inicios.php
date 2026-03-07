<?php  

// obtener_planeacion_inicios.php

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_pla = $_POST['id_pla'];
$estatus = $_POST['estatus'];

function obtenerAzul($index) {
    $blue_colors = [
        '#ADD8E6', // Light Blue
        '#87CEEB', // Sky Blue
        '#00BFFF', // Deep Sky Blue
        '#1E90FF', // Dodger Blue
        '#4169E1', // Royal Blue
        '#D9E2F3', // Navy Blue
        '#87CECC', // Medium Blue
    ];

    if ($index < 1 || $index > 6) {
        return $blue_colors[5];
    }
    return $blue_colors[$index - 1];
}
?>

<style>
    .table td, .table th {
        padding: 5px;
    }

    .letraDiminuta{
        font-size: 8px;
    }

    th, td {
        width: 200px;
        white-space: nowrap;
    }

    select.columna_certificacion {
        width: 100%;
        background-color: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    /* ESTILOS PARA DROPDOWN PDF */
    .pdf-dropdown {
        position: relative;
        display: inline-block;
    }

    .pdf-dropdown-btn {
        background-color: #dc3545;
        color: white;
        padding: 3px 8px;
        font-size: 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        transition: background-color 0.2s ease;
    }

    .pdf-dropdown-btn:hover {
        background-color: #c82333;
        color: white;
        text-decoration: none;
    }

    .pdf-dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1000;
        border-radius: 4px;
        overflow: hidden;
        top: 100%;
        left: 0;
    }

    .pdf-dropdown-content a {
        color: #333;
        padding: 8px 12px;
        text-decoration: none;
        display: block;
        font-size: 11px;
        transition: background-color 0.2s ease;
    }

    .pdf-dropdown-content a:hover {
        background-color: #f1f1f1;
        text-decoration: none;
        color: #333;
    }

    .pdf-dropdown:hover .pdf-dropdown-content {
        display: block;
    }

    /* ESTILOS PARA EVENTOS CLICKEABLE */
    .eventos-clickeable {
        cursor: pointer !important;
        position: relative;
        font-weight: 600;
    }
    
    .eventos-clickeable:hover {
        box-shadow: inset 0 0 0 2px #6c757d !important;
    }
    
    .eventos-clickeable::after {
        content: '📅';
        position: absolute;
        right: 3px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
        font-size: 12px;
    }
    
    .eventos-clickeable:hover::after {
        opacity: 1;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered" id="tabla_planeacion_inicios">
                    <thead class="" style="background-color: #002060; color: white;">
                        <tr>
                            <th class="letraPequena">SEMANA</th>
                            <th class="letraPequena">IMPRIMIR</th>
                            <th class="letraPequena">PERMISOS (QUIÉN VE LOS GPOS)</th>
                            <th class="letraPequena">MES</th>
                            <th class="letraPequena">GRUPO</th>
                            <th class="letraPequena">CLAVE</th>
                            <th class="letraPequena">PROGRAMA</th>
                            <th class="letraPequena">NIVEL</th>
                            <th class="letraPequena">MODALIDAD</th>
                            <th class="letraPequena">DIAS</th>
                            <th class="letraPequena">FECHA DE INICIO</th>
                            <th class="letraPequena">FECHA DE FIN</th>
                            <th class="letraPequena">HORARIO</th>
                            <th class="letraPequena">EVENTOS</th>
                            <th class="letraPequena">$ INSCRIPCIÓN</th>
                            <th class="letraPequena">$ COLEGIATURA</th>
                            <th class="letraPequena">$ TRÁMITE</th>
                            <th class="letraPequena"># TRÁMITE</th>
                            <th class="letraPequena">$ REINSCRIPCIÓN</th>
                            <th class="letraPequena"># REINSCRIPCIÓN</th>
                            <th class="letraPequena">REGISTRADOS</th>
                            <th class="letraPequena">META</th>
                            <th class="letraPequena">%</th>
                            <th class="letraPequena">REG.</th>
                            <th class="letraPequena">PRES</th>
                            <th class="letraPequena">REINGRESOS</th>
                            <th class="letraPequena">GRADUADOS</th>
                            <th class="letraPequena">N PRES</th>
                            <th class="letraPequena">%</th>
                            <th class="letraPequena">PERDIDA</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // ==================== 🔥 QUERIES CON EXCEPCIÓN PARA DIC-2025 ====================
                        
                        // Query para estatus 'En curso'
                        // 🔥 SOLO MOSTRAR SI TIENE AL MENOS 1 ALUMNO - EXCEPTO DICIEMBRE 2025
                        if ($estatus == 'En curso') {
                            $sql = "
                                SELECT 
                                    generacion.*,
                                    rama.*,
                                    plantel.*,
                                    obtener_estatus_presentacion_generacion(id_gen, 'PRESENTADO') AS total_presentados,
                                    obtener_estatus_presentacion_generacion(id_gen, 'NP') AS total_nps,
                                    obtener_estatus_presentacion_generacion(id_gen, 'REINGRESO') AS total_reingresos,
                                    (
                                        SELECT COUNT(*) 
                                        FROM alu_ram 
                                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                                        WHERE alu_ram.id_gen1 = generacion.id_gen 
                                        AND est1_alu_ram IS NULL
                                    ) AS total_registros,
                                    MONTH(ini_gen) as mes_numero,
                                    YEAR(ini_gen) as anio_numero
                                FROM generacion
                                INNER JOIN rama ON rama.id_ram = generacion.id_ram5
                                INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                                WHERE id_pla = '$id_pla' 
                                    AND CURDATE() >= ini_gen 
                                    AND CURDATE() <= fin_gen
                                    AND eli_eje = 1
                                    /* 🔥 FILTRO: Solo generaciones con al menos 1 alumno, EXCEPTO dic-2025 */
                                    AND (
                                        EXISTS (
                                            SELECT 1 FROM alu_ram 
                                            WHERE alu_ram.id_gen1 = generacion.id_gen
                                        )
                                        OR (MONTH(ini_gen) = 12 AND YEAR(ini_gen) = 2025)
                                    )
                                ORDER BY ini_gen DESC
                            ";
                        } 
                        // Query para estatus 'Fin curso'
                        // 🔥 SOLO MOSTRAR SI TIENE AL MENOS 1 ALUMNO - EXCEPTO DICIEMBRE 2025
                        else if ($estatus == 'Fin curso') {
                            $sql = "
                                SELECT 
                                    generacion.*,
                                    rama.*,
                                    plantel.*,
                                    obtener_estatus_presentacion_generacion(id_gen, 'PRESENTADO') AS total_presentados,
                                    obtener_estatus_presentacion_generacion(id_gen, 'NP') AS total_nps,
                                    obtener_estatus_presentacion_generacion(id_gen, 'REINGRESO') AS total_reingresos,
                                    (
                                        SELECT COUNT(*) 
                                        FROM alu_ram 
                                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                                        WHERE alu_ram.id_gen1 = generacion.id_gen 
                                        AND est1_alu_ram IS NULL
                                    ) AS total_registros,
                                    MONTH(ini_gen) as mes_numero,
                                    YEAR(ini_gen) as anio_numero
                                FROM generacion
                                INNER JOIN rama ON rama.id_ram = generacion.id_ram5
                                INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                                WHERE id_pla = '$id_pla' 
                                    AND CURDATE() > fin_gen
                                    AND eli_eje = 1
                                    /* 🔥 FILTRO: Solo generaciones con al menos 1 alumno, EXCEPTO dic-2025 */
                                    AND (
                                        EXISTS (
                                            SELECT 1 FROM alu_ram 
                                            WHERE alu_ram.id_gen1 = generacion.id_gen
                                        )
                                        OR (MONTH(ini_gen) = 12 AND YEAR(ini_gen) = 2025)
                                    )
                                ORDER BY ini_gen ASC
                            ";
                        } 
                        // Query para estatus 'Por comenzar'
                        // 🔥 MOSTRAR SIEMPRE (aunque no tenga alumnos) - SIN FILTRO EXISTS
                        else if ($estatus == 'Por comenzar') {
                            $sql = "
                                SELECT 
                                    generacion.*,
                                    rama.*,
                                    plantel.*,
                                    obtener_estatus_presentacion_generacion(id_gen, 'PRESENTADO') AS total_presentados,
                                    obtener_estatus_presentacion_generacion(id_gen, 'NP') AS total_nps,
                                    obtener_estatus_presentacion_generacion(id_gen, 'REINGRESO') AS total_reingresos,
                                    (
                                        SELECT COUNT(*) 
                                        FROM alu_ram 
                                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                                        WHERE alu_ram.id_gen1 = generacion.id_gen
                                        AND est1_alu_ram IS NULL
                                    ) AS total_registros,
                                    MONTH(ini_gen) as mes_numero,
                                    YEAR(ini_gen) as anio_numero
                                FROM generacion
                                INNER JOIN rama ON rama.id_ram = generacion.id_ram5
                                INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                                WHERE id_pla = '$id_pla' 
                                    AND CURDATE() < ini_gen
                                    AND eli_eje = 1
                                ORDER BY ini_gen ASC
                            ";
                        }

                        $resultado = mysqli_query($db, $sql);
                        $mes_actual = null;
                        $totales_mes = array(
                            'registrados' => 0,
                            'meta' => 0,
                            'reg' => 0,
                            'pres' => 0,
                            'reingresos' => 0,
                            'n_pres' => 0,
                            'perdida' => 0,
                        );

                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            // CONSULTA DINÁMICA PARA CONTAR TRÁMITES
                            $query_tramites = "SELECT COUNT(id_gru_pag) as total_tramites FROM grupo_pago WHERE id_gen15 = '".$fila['id_gen']."' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros'";
                            $result_tramites = mysqli_query($db, $query_tramites);
                            $total_tramites_dinamico = mysqli_fetch_assoc($result_tramites)['total_tramites'];

                            // CONSULTA DINÁMICA PARA CONTAR REINSCRIPCIONES
                            $query_reinscripciones = "SELECT COUNT(id_gru_pag) as total_reinscripciones FROM grupo_pago WHERE id_gen15 = '".$fila['id_gen']."' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Reinscripción'";
                            $result_reinscripciones = mysqli_query($db, $query_reinscripciones);
                            $total_reinscripciones_dinamico = mysqli_fetch_assoc($result_reinscripciones)['total_reinscripciones'];

                            // 🔥 CONSULTA DINÁMICA PARA EVENTOS
                            $query_eventos = "
                                SELECT 
                                    COUNT(*) as total_eventos,
                                    SUM(CASE WHEN val_gru_pag = 'Resuelto' THEN 1 ELSE 0 END) as eventos_resueltos
                                FROM grupo_pago 
                                WHERE id_gen15 = '".$fila['id_gen']."' AND tip_gru_pag = 'Fecha'
                            ";
                            $result_eventos = mysqli_query($db, $query_eventos);
                            $datos_eventos = mysqli_fetch_assoc($result_eventos);
                            $total_eventos = $datos_eventos['total_eventos'];
                            $eventos_resueltos = $datos_eventos['eventos_resueltos'];
                            
                            // 🔥 CALCULAR DISPLAY Y COLOR
                            $display_eventos = 'N/A';
                            $color_evento = '#FFFFFF'; // Blanco por defecto
                            
                            if ($total_eventos > 0) {
                                $display_eventos = $eventos_resueltos . ' / ' . $total_eventos;
                                $porcentaje_eventos = ($eventos_resueltos / $total_eventos) * 100;
                                
                                if ($porcentaje_eventos >= 100) {
                                    $color_evento = '#CCFFCC'; // Verde tenue
                                } elseif ($porcentaje_eventos >= 70) {
                                    $color_evento = '#FFFFCC'; // Amarillo tenue
                                } else {
                                    $color_evento = '#FFB3B3'; // Rojo tenue
                                }
                            }

                            // Si cambia el mes, imprimir totales del mes anterior
                            if ($mes_actual !== null && $mes_actual != obtenerMesServer($fila['ini_gen'])) {
                                ?>
                                <tr style="background-color: white; height: 25px;">
                                    <?php for ($i = 0; $i < 30; $i++) { ?>
                                        <td class="letraPequena"></td>
                                    <?php } ?>
                                </tr>
                                <tr class="total-mes" style="background-color: #FFD965; color: black;">
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena">TOTAL</td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"></td>
                                    <td class="letraPequena"><?php echo $totales_mes['registrados']; ?></td>
                                    <td class="letraPequena"><?php echo $totales_mes['meta']; ?></td>
                                    <td class="letraPequena" style="background-color: <?php
                                    if ($totales_mes['meta'] > 0) {
                                        $porcentaje = ($totales_mes['registrados'] / $totales_mes['meta']) * 100;
                                        if ($porcentaje < 70) {
                                            echo '#FFB3B3';
                                        } elseif ($porcentaje < 80) {
                                            echo '#FFFFCC';
                                        } else {
                                            echo '#CCFFCC';
                                        }
                                    }
                                    ?>;"><?php
                                        echo ($totales_mes['meta'] > 0) ?
                                            round(($totales_mes['registrados'] / $totales_mes['meta']) * 100) . '%' :
                                            '0%';
                                        ?></td>
                                    <td class="letraPequena"><?php echo $totales_mes['reg']; ?></td>
                                    <td class="letraPequena"><?php echo $totales_mes['pres']; ?></td>
                                    <td class="letraPequena"><?php echo $totales_mes['reingresos']; ?></td>
                                    <td class="letraPequena">0</td>
                                    <td class="letraPequena"><?php echo $totales_mes['n_pres']; ?></td>
                                    <td class="letraPequena" style="background-color: <?php
                                    if ($totales_mes['reg'] > 0) {
                                        $porcentaje = ($totales_mes['pres'] / $totales_mes['reg']) * 100;
                                        if ($porcentaje < 70) {
                                            echo '#FFB3B3';
                                        } elseif ($porcentaje < 80) {
                                            echo '#FFFFCC';
                                        } else {
                                            echo '#CCFFCC';
                                        }
                                    }
                                    ?>;"><?php
                                        echo ($totales_mes['reg'] > 0) ?
                                            round(($totales_mes['pres'] / $totales_mes['reg']) * 100) . '%' :
                                            '0%';
                                        ?></td>
                                    <td class="letraPequena">$ <?php echo number_format($totales_mes['perdida'], 2); ?></td>
                                </tr>
                                <tr style="background-color: #002060; color: white;">
                                    <td class="letraPequena">SEMANA</td>
                                    <td class="letraPequena">PDF</td>
                                    <td class="letraPequena">PERMISOS</td>
                                    <td class="letraPequena">MES</td>
                                    <td class="letraPequena">GRUPO</td>
                                    <td class="letraPequena">CLAVE</td>
                                    <td class="letraPequena">PROGRAMA</td>
                                    <td class="letraPequena">NIVEL</td>
                                    <td class="letraPequena">MODALIDAD</td>
                                    <td class="letraPequena">DIAS</td>
                                    <td class="letraPequena">FECHA DE INICIO</td>
                                    <td class="letraPequena">FECHA DE FIN</td>
                                    <td class="letraPequena">HORARIO</td>
                                    <td class="letraPequena">EVENTOS</td>
                                    <td class="letraPequena">INSCRIPCIÓN</td>
                                    <td class="letraPequena">COLEGIATURA</td>
                                    <td class="letraPequena">TRÁMITE</td>
                                    <td class="letraPequena"># TRÁMITE</td>
                                    <td class="letraPequena">REINSCRIPCIÓN</td>
                                    <td class="letraPequena"># REINSCRIPCIÓN</td>
                                    <td class="letraPequena">REGISTRADOS</td>
                                    <td class="letraPequena">META</td>
                                    <td class="letraPequena">%</td>
                                    <td class="letraPequena">REG.</td>
                                    <td class="letraPequena">PRES</td>
                                    <td class="letraPequena">REINGRESOS</td>
                                    <td class="letraPequena">GRADUADOS</td>
                                    <td class="letraPequena">N PRES</td>
                                    <td class="letraPequena">%</td>
                                    <td class="letraPequena">PERDIDA</td>
                                </tr>
                                <?php
                                $totales_mes = array_fill_keys(array_keys($totales_mes), 0);
                            }

                            $mes_actual = obtenerMesServer($fila['ini_gen']);
                            $perdida = $fila['total_nps'] * 1500;

                            $totales_mes['registrados'] += $fila['total_registros'];
                            $totales_mes['meta'] += $fila['met_gen'];
                            $totales_mes['reg'] += $fila['total_registros'];
                            $totales_mes['pres'] += $fila['total_presentados'];
                            $totales_mes['reingresos'] += $fila['total_reingresos'];
                            $totales_mes['n_pres'] += $fila['total_nps'];
                            $totales_mes['perdida'] += $perdida;

                            // Obtener el dominio actual dinámicamente
                            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                            $domain = $_SERVER['HTTP_HOST'];
                            $base_url = $protocol . $domain;
                            ?>
                            <tr style="background: white; color: black; position: relative;" data-id-gen="<?php echo $fila['id_gen']; ?>">
                                <td class="letraPequena" ini_gen="<?php echo $fila['ini_gen']; ?>">
                                    <?php echo obtenerSemanaTrabajo2($fila['ini_gen']); ?>
                                    <span class="btn-eliminar" data-id-gen="<?php echo $fila['id_gen']; ?>" style="position: absolute; top: 5px; right: 5px; color: red; cursor: pointer; font-size: 12px; font-weight: bold;">✖</span>
                                </td>
                                <!-- NUEVA COLUMNA PDF -->
                                <td class="letraPequena">
                                    <div class="pdf-dropdown">
                                        <a href="#" class="pdf-dropdown-btn">
                                            <i class="mdi mdi-file-pdf"></i> PDF <i class="mdi mdi-chevron-down"></i>
                                        </a>
                                        <div class="pdf-dropdown-content">
                                            <a href="<?php echo $base_url; ?>/ejecutivo/reporte_grupal_alumnos_pdf.php?id_gen=<?php echo $fila['id_gen']; ?>" target="_blank">
                                                <i class="mdi mdi-account-group"></i> REPORTE GRUPAL
                                            </a>
                                            <a href="<?php echo $base_url; ?>/ejecutivo/reporte_grupal_alumnos_presentacion_pdf.php?id_gen=<?php echo $fila['id_gen']; ?>" target="_blank">
                                                <i class="mdi mdi-presentation"></i> REPORTE GRUPAL - PRESENTACIÓN
                                            </a>
                                            <a href="<?php echo $base_url; ?>/ejecutivo/resumen_inicio_pdf.php?id_gen=<?php echo $fila['id_gen']; ?>" target="_blank">
                                                <i class="mdi mdi-file-document-outline"></i> PLANTILLA DE INICIO
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="letraPequena">
                                    <?php
                                    $query_total_planteles = "SELECT COUNT(*) AS total_planteles FROM plantel WHERE id_cad1 = 1";
                                    $result_total_planteles = mysqli_query($db, $query_total_planteles);
                                    $total_planteles = mysqli_fetch_assoc($result_total_planteles)['total_planteles'];

                                    $query_planteles_ejecutivo = "SELECT COUNT(*) AS total_vinculados FROM planteles_ejecutivo WHERE id_eje = $id_eje";
                                    $result_planteles_ejecutivo = mysqli_query($db, $query_planteles_ejecutivo);
                                    $total_vinculados = mysqli_fetch_assoc($result_planteles_ejecutivo)['total_vinculados'];

                                    $tiene_permisos = ($total_vinculados == $total_planteles);
                                    ?>

                                    <?php if ($tiene_permisos): ?>
                                        <div class="dropdown d-inline-block">
                                            <a class="btn-link dropdown-toggle text-primary" href="#"
                                            id="statusDropdown_<?php echo $fila['id_gen']; ?>"
                                            data-bs-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            style="font-size: 10px;">
                                                <?php
                                                $estado_actual = $fila['est_gen'];
                                                switch ($estado_actual) {
                                                    case '1':
                                                        echo 'ADMIN Y COMERCIAL';
                                                        break;
                                                    case '2':
                                                        echo 'SOLO COMERCIAL';
                                                        break;
                                                    case '3':
                                                        echo 'SOLO ADMIN';
                                                        break;
                                                    case '4':
                                                        echo 'NADIE';
                                                        break;
                                                }
                                                ?>
                                                <i class="mdi mdi-chevron-down"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="statusDropdown_<?php echo $fila['id_gen']; ?>">
                                                <a class="dropdown-item status-option" href="#" data-status="1" data-id="<?php echo $fila['id_gen']; ?>" style="font-size: 10px;">ADMIN Y COMERCIAL</a>
                                                <a class="dropdown-item status-option" href="#" data-status="2" data-id="<?php echo $fila['id_gen']; ?>" style="font-size: 10px;">SOLO COMERCIAL</a>
                                                <a class="dropdown-item status-option" href="#" data-status="3" data-id="<?php echo $fila['id_gen']; ?>" style="font-size: 10px;">SOLO ADMIN</a>
                                                <a class="dropdown-item status-option" href="#" data-status="4" data-id="<?php echo $fila['id_gen']; ?>" style="font-size: 10px;">NADIE</a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <label class="form-switch">
                                            <input type="checkbox" id="switch_<?php echo $fila['id_gen']; ?>" class="form-check-input switch-estado-grupo" <?php echo ($tiene_permisos) ? '' : 'disabled'; ?> <?php echo ($fila['est_gen'] == '1' || $fila['est_gen'] == '3') ? 'checked' : ''; ?>>
                                            <span class="slider"></span>
                                        </label>
                                    <?php endif; ?>
                                </td>
                                <td class="letraPequena"><?php echo getMonth(obtenerMesServer($fila['ini_gen'])) . '-' . date('Y', strtotime($fila['ini_gen'])); ?></td>

                                <td class="letraPequena">
                                    <a href="alumnos.php?generaciones=<?php echo $fila['id_gen']; ?>&centros=<?php echo $fila['id_pla']; ?>" class="text-primary ms-2" target="_blank">
                                        <strong><?php echo $fila['nom_gen']; ?></strong>
                                    </a>
                                </td>
                                
                                <!-- 🔥 NUEVA COLUMNA CLAVE -->
                                <td class="letraPequena"><?php echo $fila['cla_gen']; ?></td>
                                
                                <td class="letraPequena"><?php echo $fila['abr_ram']; ?></td>
                                
                                <!-- 🔥 NUEVA COLUMNA NIVEL -->
                                <td class="letraPequena"><?php echo $fila['gra_ram']; ?></td>
                                
                                <td class="letraPequena" style="background-color: <?php echo $fila['mod_gen'] == 'Online' ? '#01B0F0' : '#5A9BD5'; ?>; color: black; padding: 2px 5px;">
                                    <?php echo $fila['mod_gen'] == 'Online' ? 'ONLINE' : 'Presencial'; ?>
                                </td>
                                <td class="letraPequena"><?php echo $fila['dia_gen']; ?></td>
                                <td class="letraPequena" ini_gen="<?php echo $fila['ini_gen']; ?>"><?php echo fechaFormateadaCompacta4($fila['ini_gen']); ?></td>
                                <td class="letraPequena" fin_gen="<?php echo $fila['fin_gen']; ?>"><?php echo fechaFormateadaCompacta4($fila['fin_gen']); ?></td>
                                <td class="letraPequena"><?php echo $fila['hor_gen']; ?></td>
                                
                                <!-- 🔥 NUEVA COLUMNA EVENTOS -->
                                <td class="letraPequena eventos-clickeable" 
                                    style="background-color: <?php echo $color_evento; ?>;" 
                                    data-total-eventos="<?php echo $total_eventos; ?>" 
                                    data-eventos-resueltos="<?php echo $eventos_resueltos; ?>" 
                                    data-id-gen="<?php echo $fila['id_gen']; ?>">
                                    <?php echo $display_eventos; ?>
                                </td>
                                
                                <td class="letraPequena">$<?php echo number_format($fila['mon_ins_gen'], 2); ?></td>
                                <td class="letraPequena">$<?php echo number_format($fila['mon_col_gen'], 2); ?></td>
                                <td class="letraPequena">$<?php echo number_format($fila['mon_tra_gen'], 2); ?></td>
                                <td class="letraPequena tramite-clickeable" data-total-tramites="<?php echo $total_tramites_dinamico; ?>" data-id-gen="<?php echo $fila['id_gen']; ?>"><?php echo $total_tramites_dinamico; ?></td>
                                <td class="letraPequena">$<?php echo number_format($fila['mon_rei_gen'], 2); ?></td>
                                
                                <td class="letraPequena reinscripcion-clickeable" data-total-reinscripciones="<?php echo $total_reinscripciones_dinamico; ?>" data-id-gen="<?php echo $fila['id_gen']; ?>"><?php echo $total_reinscripciones_dinamico; ?></td>

                                <td class="letraPequena">
                                    <a href="registros.php?escala=grupo&id_gen=<?php echo $fila['id_gen']; ?>" target="_blank">
                                        <strong><?php echo $fila['total_registros']; ?></strong>
                                    </a>
                                </td>
                                <td class="letraPequena"><?php echo $fila['met_gen']; ?></td>
                                <td class="letraPequena" style="background-color: <?php
                                if ($fila['met_gen'] > 0) {
                                    $porcentaje = ($fila['total_registros'] / $fila['met_gen']) * 100;
                                    if ($porcentaje < 70) {
                                        echo '#FFB3B3';
                                    } elseif ($porcentaje < 80) {
                                        echo '#FFFFCC';
                                    } else {
                                        echo '#CCFFCC';
                                    }
                                }
                                ?>;">
                                    <?php
                                    echo ($fila['met_gen'] > 0) ?
                                        round(($fila['total_registros'] / $fila['met_gen']) * 100) . '%' :
                                        '0%';
                                    ?>
                                </td>
                                <td class="letraPequena"><?php echo $fila['total_registros']; ?></td>
                                <td class="letraPequena"><?php echo $fila['total_presentados']; ?></td>
                                <td class="letraPequena"><?php echo $fila['total_reingresos']; ?></td>
                                <td class="letraPequena">0</td>
                                <td class="letraPequena"><?php echo $fila['total_nps']; ?></td>
                                <td class="letraPequena" style="background-color: <?php
                                if ($fila['total_registros'] > 0) {
                                    $porcentaje = ($fila['total_presentados'] / $fila['total_registros']) * 100;
                                    if ($porcentaje < 70) {
                                        echo '#FFB3B3';
                                    } elseif ($porcentaje < 80) {
                                        echo '#FFFFCC';
                                    } else {
                                        echo '#CCFFCC';
                                    }
                                } else {
                                    echo '#FFB3B3';
                                }
                                ?>;">
                                    <?php
                                    echo ($fila['total_registros'] > 0) ?
                                        round(($fila['total_presentados'] / $fila['total_registros']) * 100) . '%' :
                                        '0%';
                                    ?>
                                </td>
                                <td class="letraPequena <?php echo ($perdida > 0) ? 'bg-danger' : ''; ?>">
                                    $ <?php echo number_format($perdida, 2); ?>
                                </td>
                            </tr>
                            <?php
                        }

                        // Totales del último mes
                        if ($mes_actual !== null) {
                            ?>
                            <tr style="background-color: white; height: 25px;">
                                <?php for ($i = 0; $i < 30; $i++) { ?>
                                    <td class="letraPequena"></td>
                                <?php } ?>
                            </tr>
                            <tr class="total-mes" style="background-color: #FFD965; color: black;">
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena">TOTAL</td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"></td>
                                <td class="letraPequena"><?php echo $totales_mes['registrados']; ?></td>
                                <td class="letraPequena"><?php echo $totales_mes['meta']; ?></td>
                                <td class="letraPequena" style="background-color: <?php
                                if ($totales_mes['meta'] > 0) {
                                    $porcentaje = ($totales_mes['registrados'] / $totales_mes['meta']) * 100;
                                    if ($porcentaje < 70) {
                                        echo '#FFB3B3';
                                    } elseif ($porcentaje < 80) {
                                        echo '#FFFFCC';
                                    } else {
                                        echo '#CCFFCC';
                                    }
                                }
                                ?>;"><?php
                                    echo ($totales_mes['meta'] > 0) ?
                                        round(($totales_mes['registrados'] / $totales_mes['meta']) * 100) . '%' :
                                        '0%';
                                    ?></td>
                                <td class="letraPequena"><?php echo $totales_mes['reg']; ?></td>
                                <td class="letraPequena"><?php echo $totales_mes['pres']; ?></td>
                                <td class="letraPequena"><?php echo $totales_mes['reingresos']; ?></td>
                                <td class="letraPequena">0</td>
                                <td class="letraPequena"><?php echo $totales_mes['n_pres']; ?></td>
                                <td class="letraPequena" style="background-color: <?php
                                if ($totales_mes['reg'] > 0) {
                                    $porcentaje = ($totales_mes['pres'] / $totales_mes['reg']) * 100;
                                    if ($porcentaje < 70) {
                                        echo '#FFB3B3';
                                    } elseif ($porcentaje < 80) {
                                        echo '#FFFFCC';
                                    } else {
                                        echo '#CCFFCC';
                                    }
                                }
                                ?>;"><?php
                                    echo ($totales_mes['reg'] > 0) ?
                                        round(($totales_mes['pres'] / $totales_mes['reg']) * 100) . '%' :
                                        '0%';
                                    ?></td>
                                <td class="letraPequena">$ <?php echo number_format($totales_mes['perdida'], 2); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDICION CAMPOS generacion -->
<div id="warning-alert-modal" class="modal fade" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="dripicons-warning h1 text-warning"></i>
                    <h4 class="mt-2">EDICIÓN: <span id="nombreGrupoEdicion"></span></h4>
                    
                    <!-- Inputs ocultos -->
                    <input type="hidden" id="id_gen_aux">
                    <input type="hidden" id="campo_edicion">
                    
                    <div class="form-group mt-3">
                        <input type="text" class="form-control text-center letraPequena" id="valor_edicion" style="display: none;">
                        <select class="form-control letraPequena" id="select_modalidad" style="display: none;">
                            <option value="Online">ONLINE</option>
                            <option value="Presencial">PRESENCIAL</option>
                        </select>
                        <input type="date" class="form-control letraPequena" id="input_fecha" style="display: none;">
                        <input type="date" class="form-control letraPequena" id="input_fecha_fin" style="display: none;">
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning me-2" id="btnGuardarEdicion">Guardar</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<br><br>

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
// Event listener para dropdown de permisos
$('.status-option').on('click', function(e) {
    e.preventDefault();
    var $option = $(this);
    var id_gen = $option.data('id');
    var est_gen = $option.data('status');
    
    $.ajax({
        url: 'server/controlador_grupo2.php',
        type: 'POST',
        data: {
            accion: 'actualizarEstado',
            id_gen: id_gen,
            est_gen: est_gen
        },
        success: function(response) {
            var newText = $option.text().trim();
            $('#statusDropdown_' + id_gen).html(newText + ' <i class="mdi mdi-chevron-down"></i>');
            toastr.success('Cambios guardados');
        },
        error: function() {
            toastr.error('Error al guardar los cambios');
        }
    });
});
</script>

<script>
// DataTable
$('#tabla_planeacion_inicios').DataTable({
    paging: false,
    searching: false,
    ordering: false,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            title: 'REPORTE PLANEACION DE INICIOS',
            className: 'btn-sm btn-success',
            exportOptions: {
                columns: ':not(:nth-child(2)):not(:nth-child(3))'
            }
        },
    ],
    language: {
        search: 'Buscar'
    },
    info: false
});
</script>

<script>
$(document).ready(function() {
    // CSS ESTILOS
    var estilos = `
    <style>
        /* ESTILOS PARA CELDAS EDITABLES - ACTUALIZADO CON NUEVAS COLUMNAS */
        #tabla_planeacion_inicios tbody td:nth-child(5),    /* GRUPO */
        #tabla_planeacion_inicios tbody td:nth-child(6),    /* CLAVE (NUEVA) */
        #tabla_planeacion_inicios tbody td:nth-child(9),    /* MODALIDAD */
        #tabla_planeacion_inicios tbody td:nth-child(10),   /* DÍAS */
        #tabla_planeacion_inicios tbody td:nth-child(11),   /* FECHA INICIO */
        #tabla_planeacion_inicios tbody td:nth-child(12),   /* FECHA FIN */
        #tabla_planeacion_inicios tbody td:nth-child(13),   /* HORARIO */
        #tabla_planeacion_inicios tbody td:nth-child(15),   /* INSCRIPCIÓN */
        #tabla_planeacion_inicios tbody td:nth-child(16),   /* COLEGIATURA */
        #tabla_planeacion_inicios tbody td:nth-child(17),   /* TRÁMITE */
        #tabla_planeacion_inicios tbody td:nth-child(19),   /* REINSCRIPCIÓN */
        #tabla_planeacion_inicios tbody td:nth-child(22) {  /* META */
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        #tabla_planeacion_inicios tbody td:nth-child(5):hover,
        #tabla_planeacion_inicios tbody td:nth-child(6):hover,
        #tabla_planeacion_inicios tbody td:nth-child(9):hover,
        #tabla_planeacion_inicios tbody td:nth-child(10):hover,
        #tabla_planeacion_inicios tbody td:nth-child(11):hover,
        #tabla_planeacion_inicios tbody td:nth-child(12):hover,
        #tabla_planeacion_inicios tbody td:nth-child(13):hover,
        #tabla_planeacion_inicios tbody td:nth-child(15):hover,
        #tabla_planeacion_inicios tbody td:nth-child(16):hover,
        #tabla_planeacion_inicios tbody td:nth-child(17):hover,
        #tabla_planeacion_inicios tbody td:nth-child(19):hover,
        #tabla_planeacion_inicios tbody td:nth-child(22):hover {
            background-color: #e9ecef !important;
            box-shadow: inset 0 0 0 1px #dee2e6;
        }

        #tabla_planeacion_inicios tbody td:nth-child(5)::after,
        #tabla_planeacion_inicios tbody td:nth-child(6)::after,
        #tabla_planeacion_inicios tbody td:nth-child(9)::after,
        #tabla_planeacion_inicios tbody td:nth-child(10)::after,
        #tabla_planeacion_inicios tbody td:nth-child(11)::after,
        #tabla_planeacion_inicios tbody td:nth-child(12)::after,
        #tabla_planeacion_inicios tbody td:nth-child(13)::after,
        #tabla_planeacion_inicios tbody td:nth-child(15)::after,
        #tabla_planeacion_inicios tbody td:nth-child(16)::after,
        #tabla_planeacion_inicios tbody td:nth-child(17)::after,
        #tabla_planeacion_inicios tbody td:nth-child(19)::after,
        #tabla_planeacion_inicios tbody td:nth-child(22)::after {
            content: '✎';
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            color: #6c757d;
            transition: opacity 0.2s ease-in-out;
            font-size: 12px;
        }

        #tabla_planeacion_inicios tbody td:nth-child(5):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(6):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(9):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(10):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(11):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(12):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(13):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(15):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(16):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(17):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(19):hover::after,
        #tabla_planeacion_inicios tbody td:nth-child(22):hover::after {
            opacity: 1;
        }

        /* ESTILOS ESPECÍFICOS PARA # REINSCRIPCIÓN */
        .reinscripcion-clickeable {
            cursor: pointer !important;
            position: relative;
        }

        .reinscripcion-clickeable:hover {
            background-color: #f3e5f5 !important;
            box-shadow: inset 0 0 0 2px #9c27b0 !important;
        }

        .reinscripcion-clickeable::after {
            content: '🔄';
            position: absolute;
            right: 3px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
            font-size: 12px;
        }

        .reinscripcion-clickeable:hover::after {
            opacity: 1;
        }

        /* ESTILOS ESPECÍFICOS PARA # TRÁMITE */
        .tramite-clickeable {
            cursor: pointer !important;
            position: relative;
        }
        
        .tramite-clickeable:hover {
            background-color: #e3f2fd !important;
            box-shadow: inset 0 0 0 2px #2196f3 !important;
        }
        
        .tramite-clickeable::after {
            content: '🧾';
            position: absolute;
            right: 3px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
            font-size: 12px;
        }
        
        .tramite-clickeable:hover::after {
            opacity: 1;
        }
    </style>`;

    $('head').append(estilos);

    // 🔥 HANDLER PARA EDICIÓN GENERAL (ACTUALIZADO CON NUEVAS COLUMNAS)
    $('#tabla_planeacion_inicios tbody').on('click', 'td:not(.tramite-clickeable):not(.reinscripcion-clickeable):not(.eventos-clickeable)', function(e) {
        var $celda = $(this);
        var columnaIndex = $celda.index();
        
        console.log('Click edición columna:', columnaIndex);
        
        // VALIDAR COLUMNAS EDITABLES (ACTUALIZADO: 4, 5, 8, 9, 10, 11, 12, 14, 15, 16, 18, 21)
        if (![4,5,8,9,10,11,12,14,15,16,18,21].includes(columnaIndex) || $(e.target).is('a')) {            
            return;
        }
        
        // NO EDITAR FILAS DE TOTALES
        if ($celda.closest('tr').find('td').text().includes('TOTAL')) {
            return;
        }
        
        var $fila = $celda.closest('tr');
        var id_gen = $fila.data('id-gen');
        
        if (!id_gen) {
            var link = $fila.find('td:eq(4) a').attr('href');
            if (link) {
                var match1 = link.match(/generaciones=(\d+)/);
                var match2 = link.match(/id_gen=(\d+)/);
                id_gen = match1 ? match1[1] : (match2 ? match2[1] : null);
            }
        }
        
        if (!id_gen) {
            console.error('🔥 NO SE PUDO OBTENER ID_GEN');
            toastr.error('Error: No se pudo identificar el registro');
            return;
        }
        
        var nombreGrupo = $fila.find('td:eq(4) a').text().trim();
        
        // MAPEO ACTUALIZADO DE COLUMNAS
        var camposPorColumna = {
            4: 'nom_gen',      // GRUPO
            5: 'cla_gen',      // CLAVE (NUEVA)
            8: 'mod_gen',      // MODALIDAD
            9: 'dia_gen',      // DÍAS
            10: 'ini_gen',     // FECHA INICIO
            11: 'fin_gen',     // FECHA FIN
            12: 'hor_gen',     // HORARIO
            14: 'mon_ins_gen', // INSCRIPCIÓN
            15: 'mon_col_gen', // COLEGIATURA
            16: 'mon_tra_gen', // TRÁMITE
            18: 'mon_rei_gen', // REINSCRIPCIÓN
            21: 'met_gen'      // META
        };
        
        var campo = camposPorColumna[columnaIndex];
        
        var $input = $('#valor_edicion');
        var $select = $('#select_modalidad');
        var $inputFecha = $('#input_fecha');
        var $inputFechaFin = $('#input_fecha_fin');
        
        $input.hide();
        $select.hide();
        $inputFecha.hide();
        $inputFechaFin.hide();
        
        if (campo === 'mod_gen') {
            var modalidad = $celda.text().trim();
            $select.show().val(modalidad.includes('ONLINE') ? 'Online' : 'Presencial');
        } 
        else if (campo === 'ini_gen') {
            var fechaOriginal = $celda.attr('ini_gen');
            $inputFecha.show().val(fechaOriginal);
        } 
        else if (campo === 'fin_gen') {
            var fechaFin = $celda.attr('fin_gen');
            $inputFechaFin.show().val(fechaFin);
        } 
        else if (['met_gen', 'mon_ins_gen', 'mon_col_gen', 'mon_tra_gen', 'mon_rei_gen'].includes(campo)) {
            $input.show().attr('type', 'number').val($celda.text().replace(/[$,\s]/g, '').trim());
        } 
        else {
            $input.show().attr('type', 'text').val($celda.text().trim());
        }
        
        $('#id_gen_aux').val(id_gen);
        $('#campo_edicion').val(campo);
        $('#nombreGrupoEdicion').text(nombreGrupo);
        
        $('#warning-alert-modal').modal('show');
    });

    // 🔥 HANDLER ESPECÍFICO PARA # TRÁMITE
    $(document).on('click', '.tramite-clickeable', function(e) {
        e.stopPropagation();
        
        var $celda = $(this);
        var $fila = $celda.closest('tr');
        
        if ($fila.find('td').text().includes('TOTAL')) {
            return;
        }
        
        var id_gen = $celda.data('id-gen') || $fila.data('id-gen');
        var nombreGrupo = $fila.find('td:eq(4) a').text().trim().toUpperCase();
        
        $('#tramites-pago-modalLabel').text('PAGOS DE TRÁMITE: ' + nombreGrupo);
        $('#id_gen_tramites').val(id_gen);
        $('#contenedor_tramites_pago').html('<p class="text-center">Cargando pagos de trámites...</p>');
        $('#modal_tramites_pago').modal('show');
        
        cargarPagosTramites(id_gen);
    });

    // 🔥 HANDLER ESPECÍFICO PARA # REINSCRIPCIÓN
    $(document).on('click', '.reinscripcion-clickeable', function(e) {
        e.stopPropagation();
        
        var $celda = $(this);
        var $fila = $celda.closest('tr');
        
        if ($fila.find('td').text().includes('TOTAL')) {
            return;
        }
        
        var id_gen = $celda.data('id-gen') || $fila.data('id-gen');
        var nombreGrupo = $fila.find('td:eq(4) a').text().trim().toUpperCase();
        
        $('#reinscripciones-pago-modalLabel').text('PAGOS DE REINSCRIPCIÓN: ' + nombreGrupo);
        $('#id_gen_reinscripciones').val(id_gen);
        $('#contenedor_reinscripciones_pago').html('<p class="text-center">Cargando pagos de reinscripciones...</p>');
        $('#modal_reinscripciones_pago').modal('show');
        
        cargarPagosReinscripciones(id_gen);
    });

    // 🔥 HANDLER ESPECÍFICO PARA EVENTOS
    $(document).on('click', '.eventos-clickeable', function(e) {
        e.stopPropagation();
        
        var $celda = $(this);
        var $fila = $celda.closest('tr');
        
        if ($fila.find('td').text().includes('TOTAL')) {
            return;
        }
        
        var id_gen = $celda.data('id-gen') || $fila.data('id-gen');
        var nombreGrupo = $fila.find('td:eq(4) a').text().trim().toUpperCase();
        
        $('#eventos-generacion-modalLabel').text('EVENTOS: ' + nombreGrupo);
        $('#id_gen_eventos').val(id_gen);
        $('#contenedor_eventos_generacion').html('<p class="text-center">Cargando eventos...</p>');
        $('#modal_eventos_generacion').modal('show');
        
        cargarEventosGeneracion(id_gen);
    });

    // GUARDAR EDICIÓN GENERAL
    $('#btnGuardarEdicion').off('click').on('click', function() {
        var campo = $('#campo_edicion').val();
        var valor;
        
        switch(campo) {
            case 'mod_gen':
                valor = $('#select_modalidad').val();
                break;
            case 'ini_gen':
                var fecha = $('#input_fecha').val(); 
                if (fecha) {
                    var partes = fecha.split('-');
                    valor = partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                break;
            case 'fin_gen':
                var fechaFin = $('#input_fecha_fin').val(); 
                if (fechaFin) {
                    var partes = fechaFin.split('-');
                    valor = partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                break;
            default:
                valor = $('#valor_edicion').val();
        }
        
        if (!valor || !valor.trim()) {
            toastr.error('Por favor, ingrese un valor válido');
            return;
        }
        
        $.ajax({
            url: 'server/controlador_grupo2.php',
            type: 'POST',
            data: {
                accion: 'Cambio',
                id_gen_aux: $('#id_gen_aux').val(),
                campo: campo,
                valor: valor
            },
            success: function(response) {
                var res = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (res.resultado === 'success') {
                    toastr.success('Datos actualizados correctamente');
                    $('#warning-alert-modal').modal('hide');
                    obtener_datos();
                } else if (res.resultado === 'deleted') {
                    toastr.warning('Registro eliminado por valor vacío');
                    obtener_datos();
                } else {
                    toastr.error('Error al guardar: ' + (res.mensaje || 'Error desconocido'));
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error en la conexión. Por favor, intente nuevamente');
            }
        });
    });

    // 🔥 FUNCIÓN CARGAR PAGOS TRÁMITES
    function cargarPagosTramites(id_gen) {
        $.ajax({
            url: 'server/obtener_pagos_tramites.php',
            type: 'POST',
            data: { id_gen: id_gen },
            beforeSend: function() {
                $('#contenedor_tramites_pago').html(`
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando pagos de trámites...</p>
                    </div>
                `);
            },
            success: function(response) {
                $('#contenedor_tramites_pago').html(response);
            },
            error: function(xhr, status, error) {
                $('#contenedor_tramites_pago').html(`
                    <div class="alert alert-danger">
                        <h6><i class="mdi mdi-alert"></i> Error al cargar trámites</h6>
                        <p>No se pudieron cargar los pagos de trámites.</p>
                    </div>
                `);
            }
        });
    }
    
    window.cargarPagosTramites = cargarPagosTramites;

    // 🔥 FUNCIÓN CARGAR PAGOS REINSCRIPCIONES
    function cargarPagosReinscripciones(id_gen) {
        $.ajax({
            url: 'server/obtener_pagos_reinscripciones.php',
            type: 'POST',
            data: { id_gen: id_gen },
            beforeSend: function() {
                $('#contenedor_reinscripciones_pago').html(`
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando pagos de reinscripciones...</p>
                    </div>
                `);
            },
            success: function(response) {
                $('#contenedor_reinscripciones_pago').html(response);
            },
            error: function() {
                $('#contenedor_reinscripciones_pago').html(`
                    <div class="alert alert-danger">
                        <h6><i class="mdi mdi-alert"></i> Error al cargar reinscripciones</h6>
                        <p>No se pudieron cargar los pagos de reinscripciones.</p>
                    </div>
                `);
            }
        });
    }

    window.cargarPagosReinscripciones = cargarPagosReinscripciones;

    // 🔥 FUNCIÓN CARGAR EVENTOS GENERACIÓN
    function cargarEventosGeneracion(id_gen) {
        $.ajax({
            url: 'server/obtener_eventos_generacion.php',
            type: 'POST',
            data: { id_gen: id_gen },
            beforeSend: function() {
                $('#contenedor_eventos_generacion').html(`
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando eventos...</p>
                    </div>
                `);
            },
            success: function(response) {
                $('#contenedor_eventos_generacion').html(response);
            },
            error: function() {
                $('#contenedor_eventos_generacion').html(`
                    <div class="alert alert-danger">
                        <h6><i class="mdi mdi-alert"></i> Error al cargar eventos</h6>
                        <p>No se pudieron cargar los eventos.</p>
                    </div>
                `);
            }
        });
    }

    window.cargarEventosGeneracion = cargarEventosGeneracion;
});
</script>

<!-- ELIMINACIÓN GENERACIÓN -->
<script>
$(document).on('click', '.btn-eliminar', function(e) {
    e.stopPropagation();
    
    var id_gen = $(this).data('id-gen');
    var $fila = $(this).closest('tr');
    var nombreGrupo = $fila.find('td:eq(4) a').text().trim();
    
    swal({
        title: "¿Estás seguro?",
        text: 'Se ocultará el grupo "' + nombreGrupo + '"',
        icon: "warning",
        buttons: {
            cancel: "Cancelar",
            confirm: "Sí, ocultar"
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'server/controlador_grupo2.php',
                type: 'POST',
                data: {
                    accion: 'eliminar',
                    id_gen: id_gen
                },
                success: function(response) {
                    $fila.fadeOut(300, function() {
                        $(this).remove();
                    });
                    
                    swal("Grupo ocultado correctamente", "El grupo ha sido ocultado", "success", {
                        button: "Aceptar",
                    });
                },
                error: function() {
                    swal("Error", "Error al ocultar el grupo", "error", {
                        button: "Aceptar",
                    });
                }
            });
        }
    });
});
</script>