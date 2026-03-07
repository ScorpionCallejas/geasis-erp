<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');
    
    $inicio = '';
    $fin = '';
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
    
    .container {
        width: 95%;
        margin: 0 auto;
        padding: 10px;
    }
    .header {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .ejecutivo-info {
        display: flex;
        align-items: center;
        margin-right: 20px;
    }
    .header-info {
        margin-left: 10px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 15px;
    }
    .letraMonday {
        font-family: Arial, sans-serif;
        font-weight: bold;
        font-size: 16px;
    }
    .foto-ejecutivo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #007bff;
    }
    .periodo {
        font-size: 12px;
        margin-top: 3px;
    }
    
    /* Embudo medio al lado del nombre */
    .funnel-inline-container {
        display: flex;
        align-items: center;
        padding: 0;
    }
    
    .funnel-step {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .funnel-box {
        padding: 5px 8px;
        margin: 0 3px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 12px;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    
    .funnel-title {
        margin-right: 5px;
        font-size: 11px;
    }
    
    .funnel-value {
        font-weight: bold;
        font-size: 14px;
    }
    
    .arrow-right {
        font-size: 12px;
        margin: 0 3px;
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .funnel-percent {
        font-size: 11px;
        font-weight: bold;
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    /* Colores específicos */
    .funnel-citas {
        background-color: rgba(255, 99, 132, 0.15);
        border-left: 3px solid rgba(255, 99, 132, 1);
    }
    
    .funnel-citas-efectivas {
        background-color: rgba(255, 159, 64, 0.15);
        border-left: 3px solid rgba(255, 159, 64, 1);
    }
    
    .funnel-registros {
        background-color: rgba(75, 192, 192, 0.15);
        border-left: 3px solid rgba(75, 192, 192, 1);
    }
    
    .porcentaje-citas {
        color: rgba(255, 159, 64, 1);
    }
    
    .porcentaje-registros {
        color: rgba(75, 192, 192, 1);
    }
    
    /* Resto de estilos */
    .week-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        margin-bottom: 5px;
        padding: 0 10px;
    }
    .week-label {
        text-align: center;
        flex: 1;
        font-size: 12px;
        border-top: 2px solid #e9ecef;
        padding-top: 5px;
    }
    .week-counts {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    .cita-count {
        font-size: 16px;
        font-weight: bold;
        color: #ff6384;
    }
    .cita-efectiva-count {
        font-size: 16px;
        font-weight: bold;
        color: #FF9F40; /* Naranja en lugar de amarillo */
    }
    .reg-count {
        font-size: 16px;
        font-weight: bold;
        color: #4bc0c0;
    }
    .week-date {
        font-size: 10px;
        color: #6c757d;
        margin-top: 2px;
    }
    .legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 10px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        font-size: 12px;
    }
    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-right: 5px;
    }
    .cita-color {
        background-color: rgba(255, 99, 132, 1);
    }
    .cita-efectiva-color {
        background-color: rgba(255, 159, 64, 1); /* Naranja en lugar de amarillo */
    }
    .reg-color {
        background-color: rgba(75, 192, 192, 1);
    }
    .porcentajes {
        display: flex;
        flex-direction: column;
        font-size: 10px;
        margin-top: 3px;
    }
    .porcentaje-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
    }
    .flecha {
        font-size: 8px;
    }
    .porcentaje-ce {
        color: #FF9F40;
    }
    .porcentaje-reg {
        color: #4bc0c0;
    }
</style>

<?php
    // ESTEFANÍA RIVERO - PRUEBA
    $id_eje = 2599;
    $sql = "SELECT * FROM ejecutivo WHERE id_eje = $id_eje";
    $datosEjecutivo = obtener_datos_consulta($db, $sql)['datos'];
    
    // Si no hay fechas en POST, usar todo el mes de marzo 2025
    if(empty($inicio) || empty($fin)) {
        $inicio = '2025-02-01';
        $fin = '2025-02-28';
    }
    
    // Función para obtener las semanas del período
    function obtenerSemanasPeriodo_1($fechaInicio, $fechaFin) {
        // Convertir fechas a timestamp
        $inicio = strtotime($fechaInicio);
        $fin = strtotime($fechaFin);
        
        $semanas = array();
        
        // Calcular cuántas semanas han pasado desde el inicio del año hasta el inicio del periodo
        $inicioAno = strtotime(date('Y', $inicio) . '-01-01');
        $contadorSemanas = floor(($inicio - $inicioAno) / (7 * 24 * 60 * 60));
        
        // Asegurarnos de que el inicio sea un lunes
        $fechaActual = strtotime('last monday', $inicio);
        if (date('N', $inicio) == 1) { // Si ya es lunes, no retrocedemos
            $fechaActual = $inicio;
        }
        
        // Si la primera semana incluye días del mes anterior, la omitimos
        if ($fechaActual < $inicio) {
            $fechaActual = strtotime('+7 days', $fechaActual);
        }
        
        while ($fechaActual <= $fin) {
            $inicioSemana = $fechaActual;
            $finSemana = strtotime('+6 days', $inicioSemana);
            
            $contadorSemanas++;
            
            $semanas[$contadorSemanas] = array(
                'inicio' => date('Y-m-d', $inicioSemana),
                'fin' => date('Y-m-d', $finSemana),
                'inicio_formato' => date('d/m/Y', $inicioSemana),
                'fin_formato' => date('d/m/Y', $finSemana),
            );
            
            $fechaActual = strtotime('+7 days', $fechaActual);
        }
        
        return $semanas;
    }
    
    // Función para calcular porcentaje con manejo de división por cero
    function calcularPorcentaje($numerador, $denominador) {
        if ($denominador == 0) return 0;
        return round(($numerador / $denominador) * 100, 2);
    }
    
    // Obtenemos las semanas del período
    $semanas = obtenerSemanasPeriodo_1($inicio, $fin);
    
    // Recopilamos los datos para cada semana
    $datos_semanas = array();
    $etiquetas = array();
    $valores_citas = array();
    $valores_citas_efectivas = array();
    $valores_registros = array();
    
    $i = 0;
    $total_citas = 0;
    $total_citas_efectivas = 0;
    $total_registros = 0;
    
    foreach ($semanas as $numSemana => $semana) {
        $inicio_semana = $semana['inicio'];
        $fin_semana = $semana['fin'];
        
        // 1. Obtenemos los datos de citas del ejecutivo para esta semana
        $sql = "SELECT obtener_citas_ejecutivo($id_eje, '$inicio_semana', '$fin_semana') AS total";
        $datos = obtener_datos_consulta($db, $sql);
        $total_semana_citas = isset($datos['datos']['total']) ? $datos['datos']['total'] : 0;
        $total_citas += $total_semana_citas;
        
        // 2. Obtenemos los datos de citas efectivas del ejecutivo para esta semana
        $sql = "SELECT obtener_citas_efectivas_ejecutivo($id_eje, '$inicio_semana', '$fin_semana') AS total";
        $datos = obtener_datos_consulta($db, $sql);
        $total_semana_citas_efectivas = isset($datos['datos']['total']) ? $datos['datos']['total'] : 0;
        $total_citas_efectivas += $total_semana_citas_efectivas;
        
        // 3. Obtenemos los datos de registros del ejecutivo para esta semana
        $sql = "SELECT obtener_registros_ejecutivo($id_eje, '$inicio_semana', '$fin_semana') AS total";
        $datos = obtener_datos_consulta($db, $sql);
        $total_semana_registros = isset($datos['datos']['total']) ? $datos['datos']['total'] : 0;
        $total_registros += $total_semana_registros;
        
        // Calculamos los porcentajes de conversión
        $porcentaje_c_a_ce = calcularPorcentaje($total_semana_citas_efectivas, $total_semana_citas);
        $porcentaje_ce_a_r = calcularPorcentaje($total_semana_registros, $total_semana_citas_efectivas);
        
        $etiquetas[] = "Sem " . $numSemana;
        $valores_citas[] = $total_semana_citas;
        $valores_citas_efectivas[] = $total_semana_citas_efectivas;
        $valores_registros[] = $total_semana_registros;
        
        $datos_semanas[$numSemana] = array(
            'numero' => $numSemana,
            'etiqueta' => "Semana $numSemana",
            'inicio' => $inicio_semana,
            'fin' => $fin_semana,
            'inicio_formato' => $semana['inicio_formato'],
            'fin_formato' => $semana['fin_formato'],
            'citas' => $total_semana_citas,
            'citas_efectivas' => $total_semana_citas_efectivas,
            'registros' => $total_semana_registros,
            'porcentaje_c_a_ce' => $porcentaje_c_a_ce,
            'porcentaje_ce_a_r' => $porcentaje_ce_a_r
        );
        
        $i++;
    }
    
    // Calculamos los porcentajes totales
    $porcentaje_total_c_a_ce = calcularPorcentaje($total_citas_efectivas, $total_citas);
    $porcentaje_total_ce_a_r = calcularPorcentaje($total_registros, $total_citas_efectivas);
    
    // Determinamos el valor máximo para la escala Y
    $max_citas = count($valores_citas) > 0 ? max($valores_citas) : 0;
    $max_citas_efectivas = count($valores_citas_efectivas) > 0 ? max($valores_citas_efectivas) : 0;
    $max_registros = count($valores_registros) > 0 ? max($valores_registros) : 0;
    $max_valor = max($max_citas, $max_citas_efectivas, $max_registros) * 1.2; // 20% más alto
    if($max_valor < 20) $max_valor = 20;
?>

<div class="container">
    <div class="header">
        <div class="ejecutivo-info">
            <img src="<?php echo obtenerValidacionFotoUsuarioServer($datosEjecutivo['fot_eje']); ?>" class="foto-ejecutivo" alt="Foto del ejecutivo">
            <div class="header-info">
                <div class="letraMonday"><?php echo htmlspecialchars($datosEjecutivo['nom_eje']); ?></div>
                <div class="periodo"><?php echo date('d/m/Y', strtotime($inicio)); ?> - <?php echo date('d/m/Y', strtotime($fin)); ?></div>
            </div>
        </div>
        
        <!-- Embudo al lado del nombre -->
        <div class="funnel-inline-container">
            <div class="funnel-step">
                <div class="funnel-box funnel-citas">
                    <span class="funnel-title">Citas:</span>
                    <span class="funnel-value"><?php echo $total_citas; ?></span>
                </div>
            </div>
            
            <div class="arrow-right">
                <span>→</span>
                <div class="funnel-percent porcentaje-citas"><?php echo $porcentaje_total_c_a_ce; ?>%</div>
            </div>
            
            <div class="funnel-step">
                <div class="funnel-box funnel-citas-efectivas">
                    <span class="funnel-title">C.Efect:</span>
                    <span class="funnel-value"><?php echo $total_citas_efectivas; ?></span>
                </div>
            </div>
            
            <div class="arrow-right">
                <span>→</span>
                <div class="funnel-percent porcentaje-registros"><?php echo $porcentaje_total_ce_a_r; ?>%</div>
            </div>
            
            <div class="funnel-step">
                <div class="funnel-box funnel-registros">
                    <span class="funnel-title">Reg:</span>
                    <span class="funnel-value"><?php echo $total_registros; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color cita-color"></div>
            <div>Citas</div>
        </div>
        <div class="legend-item">
            <div class="legend-color cita-efectiva-color"></div>
            <div>Citas Efectivas</div>
        </div>
        <div class="legend-item">
            <div class="legend-color reg-color"></div>
            <div>Registros</div>
        </div>
    </div>
    
    <div class="chart-container">
        <canvas id="registrosChart"></canvas>
    </div>
    
    <!-- Añadimos etiquetas destacadas debajo del gráfico -->
    <div class="week-labels">
        <?php foreach ($datos_semanas as $numSemana => $semana): ?>
        <div class="week-label">
            <div class="week-counts">
                <div class="cita-count"><?php echo $semana['citas']; ?></div>
                <span>/</span>
                <div class="cita-efectiva-count"><?php echo $semana['citas_efectivas']; ?></div>
                <span>/</span>
                <div class="reg-count"><?php echo $semana['registros']; ?></div>
            </div>
            <div>Semana <?php echo $numSemana; ?></div>
            <div class="week-date"><?php echo $semana['inicio_formato']; ?> - <?php echo $semana['fin_formato']; ?></div>
            
            <!-- Porcentajes de conversión -->
            <div class="porcentajes">
                <div class="porcentaje-item">
                    <span class="flecha">→</span>
                    <span class="porcentaje-ce"><?php echo $semana['porcentaje_c_a_ce']; ?>%</span>
                    <span class="flecha">→</span>
                    <span class="porcentaje-reg"><?php echo $semana['porcentaje_ce_a_r']; ?>%</span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Verificamos si jQuery ya está cargado
if (typeof jQuery === 'undefined') {
    // Si no está cargado, lo agregamos
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    script.onload = function() {
        cargarChartJS();
    };
    document.head.appendChild(script);
} else {
    cargarChartJS();
}

function cargarChartJS() {
    // Si Chart.js ya está cargado, no lo cargamos de nuevo
    if (typeof Chart !== 'undefined') {
        inicializarGrafica();
        return;
    }
    
    var script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js';
    script.onload = function() {
        inicializarGrafica();
    };
    document.head.appendChild(script);
}

function inicializarGrafica() {
    if (typeof Chart === 'undefined') {
        return;
    }
    
    var etiquetas = <?php echo json_encode($etiquetas); ?>;
    var valores_citas = <?php echo json_encode($valores_citas); ?>;
    var valores_citas_efectivas = <?php echo json_encode($valores_citas_efectivas); ?>;
    var valores_registros = <?php echo json_encode($valores_registros); ?>;
    var fechas_inicio = <?php echo json_encode(array_column($datos_semanas, 'inicio_formato')); ?>;
    var fechas_fin = <?php echo json_encode(array_column($datos_semanas, 'fin_formato')); ?>;
    
    try {
        var ctx = document.getElementById('registrosChart').getContext('2d');
        var registrosChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [
                    {
                        label: 'Citas',
                        data: valores_citas,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        lineTension: 0.4,
                        order: 1 // Menor número = mostrar adelante
                    },
                    {
                        label: 'Citas Efectivas',
                        data: valores_citas_efectivas,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)', // Naranja en lugar de amarillo
                        borderColor: 'rgba(255, 159, 64, 1)', // Naranja en lugar de amarillo
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(255, 159, 64, 1)', // Naranja en lugar de amarillo
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        lineTension: 0.4,
                        order: 2
                    },
                    {
                        label: 'Registros',
                        data: valores_registros,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        lineTension: 0.4,
                        order: 3 // Mayor número = mostrar atrás
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: <?php echo $max_valor; ?>,
                            stepSize: 5,
                            fontSize: 10
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontSize: 10
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label;
                            var index = tooltipItem.index;
                            return [
                                datasetLabel + ': ' + tooltipItem.yLabel,
                                'Período: ' + fechas_inicio[index] + ' - ' + fechas_fin[index]
                            ];
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    onComplete: function() {
                        // Dibujar los valores sobre los puntos después de la animación
                        var ctx = this.chart.ctx;
                        ctx.font = '11px Arial';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        
                        this.data.datasets.forEach(function(dataset, datasetIndex) {
                            // Definir color según el dataset
                            if (datasetIndex === 0) ctx.fillStyle = 'rgba(255, 99, 132, 1)';
                            else if (datasetIndex === 1) ctx.fillStyle = 'rgba(255, 159, 64, 1)';
                            else ctx.fillStyle = 'rgba(75, 192, 192, 1)';
                            
                            var meta = registrosChart.getDatasetMeta(datasetIndex);
                            if (!meta.hidden) {
                                meta.data.forEach(function(element, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, element._model.x, element._model.y - 10);
                                });
                            }
                        });
                    }
                }
            }
        });
    } catch (e) {
        console.error(e);
    }
}
</script>