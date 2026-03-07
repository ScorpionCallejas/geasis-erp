<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $inicio = $_POST['inicio']; // Fecha de inicio (domingo)
    $fin = $_POST['fin']; // Fecha de fin (sábado)

    // Generar las fechas de las últimas 5 semanas
    $weeks = array();
    for ($i = 5; $i >= 1; $i--) {
        $week_start = restarDias($inicio, 7 * $i);
        $week_end = restarDias($inicio, (7 * $i) - 1);
        $weeks[] = array('start' => $week_start, 'end' => $week_end);
    }

    // Obtener los datos de los planteles y las tendencias semanales
    $plantelData = array();

    $sqlPlanteles = "SELECT * FROM plantel WHERE id_cad1 = 1";
    $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

    $contador = 1;
    $total_registros_administrativos = 0;
    $total_registros_modulo = 0;
    $total_registros_comerciales = 0;
    $total_registros_totales = 0;

    while ($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
        $id_pla = $filaPlanteles['id_pla'];

        // Obtener los registros de la semana actual
        $registros_administrativos = obtener_registros_administrativos_plantel($id_pla, $inicio, $fin);
        $registros_modulo = obtener_registros_modulo_plantel($id_pla, $inicio, $fin);
        $registros_comerciales = obtener_registros_comerciales_plantel3($id_pla, $inicio, $fin);
        $registros_totales = $registros_administrativos + $registros_modulo + $registros_comerciales;

        // Acumular los totales
        $total_registros_administrativos += $registros_administrativos;
        $total_registros_modulo += $registros_modulo;
        $total_registros_comerciales += $registros_comerciales;
        $total_registros_totales += $registros_totales;

        // Obtener los registros de las últimas 5 semanas
        $weeklyData = array();
        foreach ($weeks as $week) {
            $week_administrativos = obtener_registros_administrativos_plantel($id_pla, $week['start'], $week['end']);
            $week_modulo = obtener_registros_modulo_plantel($id_pla, $week['start'], $week['end']);
            $week_comerciales = obtener_registros_comerciales_plantel3($id_pla, $week['start'], $week['end']);
            $week_totales = $week_administrativos + $week_modulo + $week_comerciales;

            $weeklyData[] = array(
                'week_start' => $week['start'],
                'week_end' => $week['end'],
                'registros_totales' => $week_totales
            );
        }

        // Almacenar los datos del plantel
        $plantelData[] = array(
            'id_pla' => $id_pla,
            'nom_pla' => $filaPlanteles['nom_pla'],
            'registros_administrativos' => $registros_administrativos,
            'registros_modulo' => $registros_modulo,
            'registros_comerciales' => $registros_comerciales,
            'registros_totales' => $registros_totales,
            'weeklyData' => $weeklyData
        );

        $contador++;
    }
?>

<h3>
    REPORTE RANKING 3
</h3>

<span class="letraPequena grey-text"><?php echo obtenerTituloReporte($inicio, $fin); ?></span>

<style>
    .table td, .table th {
        padding: 0;
    }
</style>

<hr>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
        <img src="../img/logoDorado.png" width="200px">
    </div>
    <div class="col-md-4"></div>
</div>

<div class="row">
    <div class="col md-2"></div>
    <div class="col-md-8">
        <!-- Tabla de datos -->
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_registros">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CDE</th>
                        <th>REG ADMINISTRATIVOS</th>
                        <th>REG MODULO</th>
                        <th>REG ÁREA COMERCIAL</th>
                        <th>REG TOTALES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    foreach ($plantelData as $plantel) {
                        $color_celda = obtenerColorFila($contador, $plantel['registros_totales']);
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td style="<?php echo $color_celda; ?>">🕋 <?php echo $plantel['nom_pla']; ?></td>
                            <td style="text-align: center;"><?php echo $plantel['registros_administrativos']; ?></td>
                            <td style="text-align: center;"><?php echo $plantel['registros_modulo']; ?></td>
                            <td style="text-align: center;"><?php echo $plantel['registros_comerciales']; ?></td>
                            <td style="<?php echo $color_celda; ?> text-align: center;">
                                <a href="<?php
                                    $url = "registros.php?escala=plantel&id_pla=" . $plantel['id_pla'] . "&inicio=$inicio&fin=$fin";
                                    echo $url;
                                ?>" target="_blank">
                                    <?php echo $plantel['registros_totales']; ?>
                                </a>
                            </td>
                        </tr>
                    <?php
                        $contador++;
                    }
                    ?>
                    <!-- Totales -->
                    <tr style="font-size: 1.2em; font-weight: bold;">
                        <td>--</td>
                        <td style="text-align: center;">Gran Total</td>
                        <td style="text-align: center;"><?php echo $total_registros_administrativos; ?></td>
                        <td style="text-align: center;"><?php echo $total_registros_modulo; ?></td>
                        <td style="text-align: center;"><?php echo $total_registros_comerciales; ?></td>
                        <td style="text-align: center;">
                            <?php echo $total_registros_totales; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gráficas de tendencias -->
        <?php foreach ($plantelData as $plantel) { ?>
            <div id="chart_div_<?php echo $plantel['id_pla']; ?>" style="width: 100%; height: 400px;"></div>
        <?php } ?>
    </div>

    <div class="col-md-2"></div>
</div>

<hr>

<!-- Incluye las librerías de Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    // Carga el paquete de visualización
    google.charts.load('current', { 'packages': ['corechart'] });

    // Llama a la función drawCharts cuando la API de Google Charts está lista
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        var plantelData = <?php echo json_encode($plantelData); ?>;

        plantelData.forEach(function (plantel) {
            drawChartForPlantel(plantel);
        });
    }

    function drawChartForPlantel(plantel) {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Semana');
        data.addColumn('number', 'Registros Totales');

        var rows = [];

        // Añade los datos de las últimas 5 semanas
        plantel.weeklyData.forEach(function (week) {
            var weekLabel = 'Del ' + week.week_start + ' al ' + week.week_end;
            rows.push([weekLabel, week.registros_totales]);
        });

        data.addRows(rows);

        var options = {
            title: 'Tendencia de las últimas 5 semanas - ' + plantel.nom_pla,
            hAxis: { title: 'Semana' },
            vAxis: { title: 'Registros Totales' },
            legend: 'none'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div_' + plantel.id_pla));
        chart.draw(data, options);
    }
</script>

<!-- Scripts adicionales -->
<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
    $('#tabla_registros').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE REGISTROS',
                className: 'btn-sm btn-success'
            },
            {
                extend: 'pdfHtml5',
                title: 'REPORTE REGISTROS',
                className: 'btn-sm btn-danger',
                orientation: 'landscape',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                }
            }
        ],
        language: {
            search: 'Buscar'
        },
        info: false
    });
</script>