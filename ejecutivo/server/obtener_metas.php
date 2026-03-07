<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$inicio = $_POST['inicio'];
$fin = $_POST['fin'];

// Function to get the list of weekdays between two dates
function obtenerIntervaloDias($inicio, $fin) {
    $fechaInicio = new DateTime($inicio);
    $fechaFin = new DateTime($fin);
    $intervalo = new DateInterval('P1D');
    $periodo = new DatePeriod($fechaInicio, $intervalo, $fechaFin->modify('+1 day'));
    $dias = array();
    foreach ($periodo as $fecha) {
        $dias[] = $fecha->format('D');
    }
    return $dias;
}

// Function to get the day number for a given date
function obtenerNumeroDia($fecha) {
    $diaSemana = date('N', strtotime($fecha));
    return $diaSemana;
}

// Function to get the day name in Spanish
function obtenerNombreDia($dia) {
    $nombresDias = array(
        'Mon' => 'LUN',
        'Tue' => 'MAR',
        'Wed' => 'MIE',
        'Thu' => 'JUE',
        'Fri' => 'VIE',
        'Sat' => 'SAB',
        'Sun' => 'DOM'
    );
    return $nombresDias[$dia];
}

// Get the list of weekdays between the given dates
$listaDias = obtenerIntervaloDias($inicio, $fin);
?>

<h3>
    REPORTE METAS REGISTROS
</h3>

<style>
    .table td, .table th {
        padding: 0;
    }
</style>

<div class="row">
    <div class="col md-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_metas_registros">
                <thead>
                    <tr style="background: black; font-weight: bold; color: white;">
                        <th>#</th>
                        <th>CONSULTOR</th>
                        <?php
                        $fecha = new DateTime($inicio);
                        foreach ($listaDias as $dia) {
                            $nombreDia = obtenerNombreDia($dia);
                            $numeroDia = $fecha->format('d');
                            echo "<th style='width: 50px;' class='text-center'>$nombreDia</th><th style='width: 60px;' class='text-center'>$numeroDia</th>";
                            $fecha->modify('+1 day');
                        }
                        ?>
                        <th class='text-center'>TOTAL</th>
                        <th class='text-center'>% EFECT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlPlantelesEjecutivo = "
                        SELECT *
                        FROM planteles_ejecutivo
                        INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
                        WHERE id_eje = $id
                        ORDER BY plantel.id_pla ASC
                    ";

                    $resultadoPlantelesEjecutivo = mysqli_query($db, $sqlPlantelesEjecutivo);
                    
                    // Inicializar variables para los totales generales
                    $totalGeneralRegistros = 0;
                    $totalGeneralMetas = 0;

                    while ($filaPlantelesEjecutivo = mysqli_fetch_assoc($resultadoPlantelesEjecutivo)) {
                        $id_pla = $filaPlantelesEjecutivo['id_pla'];
                        $nom_pla = $filaPlantelesEjecutivo['nom_pla'];

                        // Inicializar variables para los totales del plantel
                        $totalPlantelRegistros = 0;
                        $totalPlantelMetas = 0;
                    ?>
                        <tr style="background: grey; color: black; font-weight: bold;">
                            <td class="letraPequena">--</td>
                            <td class="letraGrande">🕋 <?php echo $nom_pla; ?></td>

                            <?php 
                            $fechaIteracion = new DateTime($inicio);
                            for ($j = 0; $j < sizeof($listaDias); $j++) { 
                            ?>
                                <td class="letraPequena text-center">REG</td><td class="letraPequena text-center">MET</td>
                            <?php 
                                $fechaIteracion->modify('+1 day');
                            } 
                            ?>
                            <td class="letraPequena">--</td>
                            <td class="letraPequena">--</td>
                        </tr>

                        <?php
                        $sqlEjecutivos = "
                            SELECT *, obtener_conteo_recursivo_registros_ejecutivo(id_eje, '$inicio', '$fin') AS total_registros
                            FROM ejecutivo 
                            WHERE 
                            id_pla = '$id_pla' AND 
                            eli_eje = 'Activo' AND 
                            ran_eje != 'DC' AND 
                            tip_eje = 'Ejecutivo'
                            ORDER BY nom_eje ASC
                        ";

                        $contador = 1;
                        $resultadoEjecutivos = mysqli_query($db, $sqlEjecutivos);
                        while ($filaEjecutivos = mysqli_fetch_assoc($resultadoEjecutivos)) {
                            $id_eje = $filaEjecutivos['id_eje'];
                        ?>
                            <tr style="color: black; background: #EAEEF1;" class="fila-ejecutivo">
                                <td class="letraPequena"><?php echo $contador; ?></td>
                                <td class="letraPequena">
                                    <?php echo obtener_semaforo_ejecutivo($filaEjecutivos['ult_eje']); ?>
                                    <img src="<?php echo obtenerValidacionFotoUsuarioServer($filaEjecutivos['fot_eje']); ?>" style="width: 20px; height: 25px; border-radius: 35px;" class="imagenGrande">
                                    
                                    <span title="<?php echo $filaEjecutivos['nom_eje']; ?>" class="<?php if ($filaEjecutivos['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
                                        <?php echo obtenerPrimerasDosPalabras($filaEjecutivos['nom_eje']); ?>
                                    </span>
                                    <br>
                                    <span class="<?php if ($filaEjecutivos['est_eje'] == 'Inactivo') echo 'text-danger'; ?> badge rounded-pill badge-outline-<?php 
                                        echo $filaEjecutivos['ran_eje'] == 'GC' ? 'dark' : 
                                            ($filaEjecutivos['ran_eje'] == 'GR' ? 'success' : 'primary'); 
                                    ?>">
                                        <?php echo obtener_rango_usuario($filaEjecutivos['ran_eje']); ?>
                                    </span>
                                </td>

                                <?php 
                                $fechaIteracion = new DateTime($inicio);
                                $totalRegistros = 0;
                                $totalMetas = 0;
                                for ($j = 0; $j < sizeof($listaDias); $j++) { 
                                    $fechaActual = $fechaIteracion->format('Y-m-d');
                                ?>
                                    <td class="letraPequena">
                                        <br>
                                        <span class="badge registro-badge" style="background-color: #00FFFF; color: black; font-size: 10px;">
                                            REG: <?php 
                                                $sql = "SELECT obtener_registros_ejecutivo($id_eje, '$fechaActual', '$fechaActual') AS total";
                                                $datos = obtener_datos_consulta($db, $sql);
                                                $registros = $datos['datos']['total'];
                                                $totalRegistros += $registros;
                                                $totalPlantelRegistros += $registros;
                                                echo $registros;
                                            ?>
                                        </span>
                                    </td>

                                    <td class="letraPequena">
                                        <?php
                                        $sqlValidacion = "
                                            SELECT *
                                            FROM meta 
                                            WHERE DATE(fec_met) = DATE('$fechaActual') 
                                            AND rub_met = 'Registro' 
                                            AND id_eje5 = $id_eje
                                        ";
                                        $datosValidacion = obtener_datos_consulta($db, $sqlValidacion);

                                        echo '<select class="form-control metasRegistros" style="background: #EAEEF1; font-size:12px; border: none; font-weight: bold; margin-top: 6px;"';

                                        if ($datosValidacion['total'] == 0) {
                                            echo ' id_eje="'.$id_eje.'" fec_met="'.$fechaActual.'">';
                                            $valorSeleccionado = '';
                                        } else {
                                            echo ' id_met="'.$datosValidacion['datos']['id_met'].'">';
                                            $valorSeleccionado = $datosValidacion['datos']['can_met'];
                                        }

                                        $totalMetas += intval($valorSeleccionado);
                                        $totalPlantelMetas += intval($valorSeleccionado);

                                        echo '<option value="" '.($valorSeleccionado === '' ? 'selected' : '').'></option>';

                                        for ($i = 1; $i <= 99; $i++) {
                                            $selected = ($i == $valorSeleccionado) ? 'selected' : '';
                                            echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                        }

                                        echo '</select>';
                                        ?>
                                    </td>
                                <?php
                                    $fechaIteracion->modify('+1 day');
                                }
                                ?>

                                <td class="letraPequena text-center"> 
                                    <br>
                                    <span class="metasRegistrosTotales" style="font-weight: bold; font-size: 14px;"><?php echo $totalRegistros . '/' . $totalMetas; ?></span> 
                                </td>
                                <td class="letraPequena text-center"> 
                                    <br>
                                    <span class="metasRegistrosPorcentuales" style="font-weight: bold; bold; font-size: 14px;"></span> 
                                </td>
                            </tr>

                        <?php
                            $contador++;
                        }

                        // Actualizar totales generales
                        $totalGeneralRegistros += $totalPlantelRegistros;
                        $totalGeneralMetas += $totalPlantelMetas;

                        // Mostrar totales del plantel
                        ?>
                        <tr style="background: #d3d3d3; font-weight: bold;">
							<td>Total</td>
							<td><?php echo $nom_pla; ?></td>
                            <?php 
                            $fechaIteracion = new DateTime($inicio);
                            for ($j = 0; $j < sizeof($listaDias); $j++) { 
                                $fechaActual = $fechaIteracion->format('Y-m-d');
                                
                                // Calcular totales para esta fecha
                                $sqlTotalRegistros = "SELECT SUM(obtener_registros_ejecutivo(id_eje, '$fechaActual', '$fechaActual')) AS total FROM ejecutivo WHERE id_pla = '$id_pla'";
                                $sqlTotalMetas = "SELECT SUM(can_met) AS total FROM meta WHERE DATE(fec_met) = DATE('$fechaActual') AND rub_met = 'Registro' AND id_eje5 IN (SELECT id_eje FROM ejecutivo WHERE id_pla = '$id_pla')";
                                
                                $totalRegistros = obtener_datos_consulta($db, $sqlTotalRegistros)['datos']['total'];
                                $totalMetas = obtener_datos_consulta($db, $sqlTotalMetas)['datos']['total'];
                            ?>
                                <td class="text-center"><?php echo $totalRegistros; ?></td>
                                <td class="text-center"><?php echo $totalMetas; ?></td>
                            <?php
                                $fechaIteracion->modify('+1 day');
                            }
                            
                            // Mostrar totales del plantel
                            $porcentajeEfectividad = $totalPlantelMetas > 0 ? ($totalPlantelRegistros / $totalPlantelMetas) * 100 : 0;
                            ?>
                            <td class="text-center"><?php echo $totalPlantelRegistros . '/' . $totalPlantelMetas; ?></td>
                            <td class="text-center"><?php echo number_format($porcentajeEfectividad, 2) . '%'; ?></td>
                        </tr>
                    <?php 
                    } 

                    // Después de todos los planteles, agregar una fila de totales generales
                    ?>
                    <tr style="background: #000000; color: white; font-weight: bold;">
						<td></td>
						<td>TOTAL GENERAL</td>
						
                        <?php 
                        $fechaIteracion = new DateTime($inicio);
                        for ($j = 0; $j < sizeof($listaDias); $j++) { 
                            $fechaActual = $fechaIteracion->format('Y-m-d');
                            
                            // Calcular totales generales para esta fecha
                            $sqlTotalRegistros = "SELECT SUM(obtener_registros_ejecutivo(id_eje, '$fechaActual', '$fechaActual')) AS total FROM ejecutivo";
                            $sqlTotalMetas = "SELECT SUM(can_met) AS total FROM meta WHERE DATE(fec_met) = DATE('$fechaActual') AND rub_met = 'Registro'";
                            
                            $totalRegistros = obtener_datos_consulta($db, $sqlTotalRegistros)['datos']['total'];
                            $totalMetas = obtener_datos_consulta($db, $sqlTotalMetas)['datos']['total'];
                        ?>
                            <td class="text-center"><?php echo $totalRegistros; ?></td>
                            <td class="text-center"><?php echo $totalMetas; ?></td>
                        <?php
                            $fechaIteracion->modify('+1 day');
                        }
                        
                        // Mostrar totales generales
                        $porcentajeEfectividadGeneral = $totalGeneralMetas > 0 ? ($totalGeneralRegistros / $totalGeneralMetas) * 100 : 0;
                        ?>
                        <td class="text-center"><?php echo $totalGeneralRegistros . '/' . $totalGeneralMetas; ?></td>
                        <td class="text-center"><?php echo number_format($porcentajeEfectividadGeneral, 2) . '%'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$('.metasRegistros').on('change', function(e) {
    e.preventDefault();

    var metaRegistro = $(this);
    var accion = metaRegistro.attr('id_met') === undefined ? 'Agregar' : 'Cambio';
    var data = {
        accion: accion,
        can_met: metaRegistro.val(),
        rub_met: 'Registro'
    };

    if( data.can_met == '' ){
        data.can_met = 0;
    }

    if (accion === 'Agregar') {
        data.id_eje = metaRegistro.attr('id_eje');
        data.fec_met = metaRegistro.attr('fec_met');
    } else {
        data.id_met = metaRegistro.attr('id_met');
    }

    $.ajax({
        url: 'server/controlador_meta.php',
        type: 'POST',
        data: data,
        success: function(respuesta) {
            console.log(respuesta);
            if (accion === 'Agregar') {
                metaRegistro.attr('id_met', respuesta);
            }
            toastr.success('Guardado correctamente');
            calcularTotalesPorFila();
            calcularTotalesPlantel();
            calcularTotalesGenerales();
        },
        error: function() {
            toastr.error('Error en el guardado');
        }
    });
});

function calcularTotalesPorFila() {
    $('#tabla_metas_registros tbody tr.fila-ejecutivo').each(function() {
        let totalMetas = 0;
        let totalRegistros = 0;

        $(this).find('.metasRegistros').each(function() {
            let valorMeta = parseInt($(this).val()) || 0;
            totalMetas += valorMeta;
        });

        $(this).find('.registro-badge').each(function() {
            let valorRegistro = parseInt($(this).text().replace('REG: ', '')) || 0;
            totalRegistros += valorRegistro;
        });

        $(this).find('.metasRegistrosTotales').text(totalRegistros + '/' + totalMetas);

        let porcentajeElemento = $(this).find('.metasRegistrosPorcentuales');

        if (totalMetas === 0 && totalRegistros === 0) {
            porcentajeElemento.html('<div style="background-color: #cdcdcd; color: black; padding: 5px;">N/A</div>');
        } else if (totalMetas > 0) {
            let porcentajeEfectividad = (totalRegistros / totalMetas) * 100;
            let colorFondo = porcentajeEfectividad < 60 ? '#ff2222' : (porcentajeEfectividad < 80 ? '#fff000' : '#00ff17');
            let colorTexto = porcentajeEfectividad < 60 ? 'white' : 'black';

            porcentajeElemento.html(
                `<div style="background-color: ${colorFondo}; color: ${colorTexto}; padding: 5px;">${porcentajeEfectividad.toFixed(2)}%</div>`
            );
        } else {
            porcentajeElemento.html('<div style="background-color: #cdcdcd; color: black; padding: 5px;">N/A</div>');
        }
    });
}

function calcularTotalesPlantel() {
    $('#tabla_metas_registros tbody tr').each(function() {
        if ($(this).find('td:first').text() === 'Total') {
            let totalMetas = 0;
            let totalRegistros = 0;

            $(this).prevUntil('tr:has(td[colspan="2"])').each(function() {
                let valores = $(this).find('.metasRegistrosTotales').text().split('/');
                totalRegistros += parseInt(valores[0]) || 0;
                totalMetas += parseInt(valores[1]) || 0;
            });

            $(this).find('td:nth-last-child(2)').text(totalRegistros + '/' + totalMetas);

            let porcentaje = totalMetas > 0 ? (totalRegistros / totalMetas) * 100 : 0;
            $(this).find('td:last').text(porcentaje.toFixed(2) + '%');
        }
    });
}

function calcularTotalesGenerales() {
    let totalGeneralMetas = 0;
    let totalGeneralRegistros = 0;

    $('#tabla_metas_registros tbody tr').each(function() {
        if ($(this).find('td:first').text() === 'Total') {
            let valores = $(this).find('td:nth-last-child(2)').text().split('/');
            totalGeneralRegistros += parseInt(valores[0]) || 0;
            totalGeneralMetas += parseInt(valores[1]) || 0;
        }
    });

    let trTotalGeneral = $('#tabla_metas_registros tbody tr:last');
    trTotalGeneral.find('td:nth-last-child(2)').text(totalGeneralRegistros + '/' + totalGeneralMetas);

    let porcentajeGeneral = totalGeneralMetas > 0 ? (totalGeneralRegistros / totalGeneralMetas) * 100 : 0;
    trTotalGeneral.find('td:last').text(porcentajeGeneral.toFixed(2) + '%');
}

$(document).ready(function() {
    calcularTotalesPorFila();
    calcularTotalesPlantel();
    calcularTotalesGenerales();
});

$('.metasRegistros').change(function() {
    calcularTotalesPorFila();
    calcularTotalesPlantel();
    calcularTotalesGenerales();
});

$('#tabla_metas_registros').DataTable({
    paging: false,
    searching: true,
    ordering: false,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            title: 'REPORTE METAS REGISTROS',
            className: 'btn-sm btn-success'
        },
    ],
    language: {
        search: 'Buscar'
    },
    info: false
});
</script>