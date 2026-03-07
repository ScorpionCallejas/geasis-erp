<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');
// Captura el id_gen de la URL. Si no viene, se usa 0.
$id_gen_desde_url = isset($_POST['id_gen']) ? (int)$_POST['id_gen'] : 0;
?>

<style>
.trends-container {
    padding: 14px;
    max-width: 1100px;
    margin: 0 auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.trends-header {
    text-align: center;
    margin-bottom: 21px;
    padding: 14px 0;
    border-bottom: 1px solid #e1e5e9;
}

.trends-title {
    font-size: 18px;
    font-weight: 300;
    color: #333;
    margin: 0;
}

.trends-subtitle {
    font-size: 11px;
    color: #666;
    margin: 6px 0 0 0;
}

.chart-container {
    background: white;
    border-radius: 8px;
    padding: 25px;
    margin: 14px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
}

.rubros-selector {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.rubro-btn {
    padding: 12px 24px;
    border: 2px solid #dadce0;
    background: white;
    border-radius: 25px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 600;
    text-transform: uppercase;
    min-width: 160px;
    text-align: center;
}

.rubro-btn.active {
    background: #1a73e8;
    color: white;
    border-color: #1a73e8;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(26, 115, 232, 0.3);
}

.rubro-btn:hover:not(.active) {
    background: #f8f9fa;
    transform: translateY(-1px);
}

.chart-wrapper {
    position: relative;
    height: 350px;
    margin: 20px 0;
}

.loading-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 350px;
    color: #666;
    font-size: 12px;
}

.concentrado-section {
    margin-top: 30px;
    border-top: 1px solid #e1e5e9;
    padding-top: 25px;
}

.concentrado-title {
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    text-transform: uppercase;
    varter-spacing: 0.5px;
}

.concentrado-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 8px;
    margin-bottom: 20px;
    max-width: 100%;
}

.mes-card {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 12px 6px;
    text-align: center;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.mes-card:hover {
    background: #e3f2fd;
    border-color: #1a73e8;
    transform: translateY(-1px);
}

.mes-card.activo {
    background: #e8f5e9;
    border-color: #4caf50;
}

.mes-card.inactivo {
    background: #fff3e0;
    border-color: #ff9800;
    opacity: 0.7;
}

.mes-card.sin-datos {
    background: #f5f5f5;
    border-color: #ccc;
    border-style: dashed;
    opacity: 0.5;
    cursor: default;
}

.mes-nombre {
    font-size: 9px;
    font-weight: 600;
    color: #666;
    margin-bottom: 4px;
    text-transform: uppercase;
}

.mes-valores {
    font-size: 8px;
    line-height: 1.2;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.valor-item {
    margin: 1px 0;
    color: #333;
}

.valor-destacado {
    font-weight: 700;
    color: #1a73e8;
}

.porcentaje-eficiencia {
    font-weight: 700;
    color: #ff5722;
    background: rgba(255, 87, 34, 0.1);
    padding: 2px 4px;
    border-radius: 3px;
    margin-top: 2px;
    display: inline-block;
}

.resumen-anual {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.resumen-card {
    background: #f1f3f4;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    border-left: 4px solid #1a73e8;
}

.resumen-valor {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.resumen-label {
    font-size: 10px;
    color: #666;
    text-transform: uppercase;
    varter-spacing: 0.5px;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .concentrado-grid {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    }
}

@media (max-width: 1200px) {
    .concentrado-grid {
        grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
    }
    
    .rubros-selector {
        gap: 10px;
    }
    
    .rubro-btn {
        min-width: 140px;
        padding: 10px 16px;
    }
}

@media (max-width: 768px) {
    .concentrado-grid {
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 6px;
    }
    
    .mes-card {
        padding: 8px 4px;
        min-height: 70px;
    }
    
    .mes-nombre {
        font-size: 8px;
    }
    
    .mes-valores {
        font-size: 7px;
    }
    
    .rubros-selector {
        flex-direction: column;
        align-items: center;
    }
    
    .rubro-btn {
        min-width: 200px;
    }
}
</style>

<div class="trends-container">

    <div class="chart-container">
        <div class="rubros-selector">
            <button class="rubro-btn" data-rubro="conteos">
                👥 CONTEOS BÁSICOS
                <div style="font-size: 9px; font-weight: 400; margin-top: 2px;">
                    Alumnos • Pagadores • Deudores
                </div>
            </button>
            
            <button class="rubro-btn" data-rubro="estatus">
                📊 ESTATUS AGRUPADOS
                <div style="font-size: 9px; font-weight: 400; margin-top: 2px;">
                    10 Estatus de Alumnos
                </div>
            </button>
            
            <button class="rubro-btn active" data-rubro="financiero">
                💰 ANÁLISIS FINANCIERO
                <div style="font-size: 9px; font-weight: 400; margin-top: 2px;">
                    Cobrado vs Potencial
                </div>
            </button>
        </div>

        <div class="chart-wrapper">
            <div class="loading-state" id="loadingState">🔄 Cargando datos mensuales...</div>
            <canvas id="trendsChart" style="display: none;"></canvas>
        </div>

        <div class="concentrado-section">
            <h3 class="concentrado-title" id="concentradoTitle">Concentrado Mensual - Análisis Financiero</h3>
            
            <div class="concentrado-grid" id="concentradoGrid">
            </div>
            
            <div class="resumen-anual" id="resumenAnual">
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
var idGenDesdeUrl = <?php echo $id_gen_desde_url; ?>;

var rangoMeses = [];
var fechaInicioVisualizacion = null;
var fechaFinVisualizacion = null;

$(document).ready(function() {
    var trendsChart;
    var datosMensuales = {};
    var infoGeneracion = {};
    var rubroActual = 'financiero';
    
    if (idGenDesdeUrl === 0) {
        $('#loadingState').html('❌ Error: No se especificó un ID de generación en la URL.');
        $('#grupoInfo').html('Por favor, verifica el enlace.');
        return;
    }
    
    cargarDatos();
    
    $('.rubro-btn').on('click', function() {
        $('.rubro-btn').removeClass('active');
        $(this).addClass('active');
        rubroActual = $(this).data('rubro');
        
        actualizarGrafica();
        actualizarConcentrado();
    });
    
    function cargarDatos() {

        $('#loader').removeClass('hidden');
        $.ajax({
            url: 'server/tendencias_grupo.php',
            type: 'POST',
            dataType: 'json',
            data: { id_gen: idGenDesdeUrl },
            success: function(response) {
                if (response.error) {
                    
                    $('#loadingState').html('❌ ' + response.mensaje);
                    return;
                }
                
                datosMensuales = response.datos;
                infoGeneracion = response.info_generacion;
                
                calcularRangoFechas();
                
                $('#grupoInfo').html(`${infoGeneracion.nom_gen} • ${infoGeneracion.ini_gen} → ${infoGeneracion.fin_gen}`);
                
                inicializarGrafica();
                actualizarConcentrado();
                $('#loadingState').hide();
                $('#trendsChart').show();

                $('#loader').addClass('hidden');
            },
            error: function() {
                $('#loadingState').html('❌ Error de conexión al servidor.');
            }
        });
    }
    
    function calcularRangoFechas() {
        var fechaInicio = new Date(infoGeneracion.ini_gen);
        var fechaFin = new Date(infoGeneracion.fin_gen);
        
        fechaInicioVisualizacion = new Date(fechaInicio);
        fechaInicioVisualizacion.setMonth(fechaInicioVisualizacion.getMonth() - 3);
        
        fechaFinVisualizacion = new Date(fechaFin);
        fechaFinVisualizacion.setMonth(fechaFinVisualizacion.getMonth() + 3);
        
        rangoMeses = [];
        var fechaActual = new Date(fechaInicioVisualizacion);
        
        while (fechaActual <= fechaFinVisualizacion) {
            var año = fechaActual.getFullYear();
            var mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
            var nombreMes = fechaActual.toLocaleDateString('es-ES', { month: 'short' });
            
            var esInicioAño = fechaActual.getMonth() === 0;
            var esCambioAño = rangoMeses.length > 0 && rangoMeses[rangoMeses.length - 1].año !== año.toString();
            var mostrarAño = rangoMeses.length === 0 || esCambioAño || esInicioAño;
            
            var label = mostrarAño ? `${nombreMes} ${año}` : nombreMes;
            
            var fechaMes = new Date(año, fechaActual.getMonth(), 1);
            var tieneDatos = fechaMes >= fechaInicio && fechaMes <= fechaFin;
            
            rangoMeses.push({
                año: año.toString(),
                mes: mes,
                key: `${año}-${mes}`,
                label: label,
                nombreMes: nombreMes,
                tieneDatos: tieneDatos
            });
            
            fechaActual.setMonth(fechaActual.getMonth() + 1);
        }
        
        console.log('Rango calculado:', rangoMeses);
    }
    
    function inicializarGrafica() {
        var ctx = document.getElementById('trendsChart').getContext('2d');
        
        trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rangoMeses.map(m => m.label),
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: { font: { size: 11 }, padding: 20 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        cornerRadius: 6,
                        titleFont: { size: 11 },
                        bodyFont: { size: 10 },
                        callbacks: {
                            title: function(context) {
                                var mesIndex = context[0].dataIndex;
                                var mes = rangoMeses[mesIndex];
                                return `${mes.nombreMes} ${mes.año}`;
                            },
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    var value = context.parsed.y;
                                    if(rubroActual === 'financiero') {
                                        label += new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value);
                                    } else {
                                        label += value.toLocaleString('es-MX');
                                    }
                                }
                                return label;
                            },
                            afterLabel: function(context) {
                                if (rubroActual === 'financiero' && context.dataset.label.includes('Cobrado')) {
                                    var mesActual = rangoMeses[context.dataIndex];
                                    var datos = datosMensuales[mesActual.key];
                                    
                                    if (datos && datos.potencial > 0) {
                                        var porcentaje = ((datos.cobrado / datos.potencial) * 100).toFixed(1);
                                        return `Eficiencia: ${porcentaje}%`;
                                    }
                                }
                                return null;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { 
                            color: '#666', 
                            font: { size: 10 },
                            callback: function(value) {
                                if (rubroActual === 'financiero') {
                                    return '$' + value.toLocaleString('es-MX');
                                }
                                return value.toLocaleString('es-MX');
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: '#666', 
                            font: { size: 10 },
                            maxRotation: 45
                        }
                    }
                }
            }
        });
        
        actualizarGrafica();
    }

    function actualizarGrafica() {
        if (!trendsChart || !rangoMeses.length) return;
        
        var datasets = [];

        if (rubroActual === 'conteos') {
            var metricas = [
                { key: 'alumnos', label: '👥 Total Alumnos', color: '#1a73e8' },
                { key: 'pagadores', label: '💰 Pagadores', color: '#4caf50' },
                { key: 'deudores', label: '⚠️ Deudores', color: '#f44336' }
            ];
            
            metricas.forEach(metrica => {
                var datos = rangoMeses.map((mes) => {
                    if (!mes.tieneDatos) return null;
                    var datosDelMes = datosMensuales[mes.key];
                    if (!datosDelMes || !datosDelMes.activo) return null;
                    return datosDelMes[metrica.key] || 0;
                });

                datasets.push({
                    label: metrica.label,
                    data: datos,
                    borderColor: metrica.color,
                    backgroundColor: metrica.color + '20',
                    borderWidth: 3,
                    fill: 'start',
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    spanGaps: false
                });
            });
            
        } else if (rubroActual === 'estatus') {
            var estatusTop = ['PROSPECTO', 'ACTIVO', 'REGISTRADO', 'REGISTRO', 'NP', 'BAJA'];
            var colores = ['#2196f3', '#22c55e', '#8bc34a', '#ff9800', '#dc2626', '#dc2626'];
            
            estatusTop.forEach((estatus, index) => {
                var datos = rangoMeses.map((mes) => {
                    if (!mes.tieneDatos) return null;
                    var datosDelMes = datosMensuales[mes.key];
                    if (!datosDelMes || !datosDelMes.activo) return null;
                    return datosDelMes.estatus?.[estatus] || 0;
                });

                datasets.push({
                    label: `${getEstatusEmoji(estatus)} ${estatus}`,
                    data: datos,
                    borderColor: colores[index],
                    backgroundColor: colores[index] + '15',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    pointRadius: 3,
                    spanGaps: false
                });
            });
            
        } else if (rubroActual === 'financiero') {
            var financiero = [
                { key: 'cobrado', label: '💵 Cobrado', color: '#4caf50' },
                { key: 'potencial', label: '🎯 Potencial', color: '#f44336' }
            ];
            
            financiero.forEach(metrica => {
                var datos = rangoMeses.map((mes) => {
                    if (!mes.tieneDatos) return null;
                    var datosDelMes = datosMensuales[mes.key];
                    if (!datosDelMes || !datosDelMes.activo) return null;
                    return datosDelMes[metrica.key] || 0;
                });

                datasets.push({
                    label: metrica.label,
                    data: datos,
                    borderColor: metrica.color,
                    backgroundColor: metrica.color + '20',
                    borderWidth: 4,
                    fill: 'start',
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    spanGaps: false
                });
            });
        }
        
        trendsChart.data.datasets = datasets;
        trendsChart.update('none');
    }
    
    function actualizarConcentrado() {
        if (!rangoMeses.length) return;
        
        var html = '';
        var resumenHtml = '';
        
        var titulos = {
            'conteos': 'Concentrado Mensual - Conteos Básicos',
            'estatus': 'Concentrado Mensual - Estatus Agrupados', 
            'financiero': 'Concentrado Mensual - Análisis Financiero'
        };
        $('#concentradoTitle').text(titulos[rubroActual]);
        
        rangoMeses.forEach((mes) => {
            var cardClass = '';
            var cardContent = '';

            if (!mes.tieneDatos) {
                cardClass = 'sin-datos';
                cardContent += `<div class="mes-nombre">${mes.label}</div><div class="mes-valores">Sin datos</div>`;

            } else {
                var datos = datosMensuales[mes.key];
                var esActivo = datos && datos.activo;
                cardClass = esActivo ? 'activo' : 'inactivo';
                cardContent += `<div class="mes-nombre">${mes.label}</div><div class="mes-valores">`;

                if (rubroActual === 'conteos') {
                    cardContent += `<div class="valor-item">👥 <span class="valor-destacado">${(datos ? datos.alumnos : 0).toLocaleString('es-MX')}</span></div>`;
                    cardContent += `<div class="valor-item">💰 ${(datos ? datos.pagadores : 0).toLocaleString('es-MX')}</div>`;
                    cardContent += `<div class="valor-item">⚠️ ${(datos ? datos.deudores : 0).toLocaleString('es-MX')}</div>`;
                } else if (rubroActual === 'estatus' && datos && datos.estatus) {
                    var top3 = Object.entries(datos.estatus).sort((a, b) => b[1] - a[1]).slice(0, 3);
                    top3.forEach(([estatus, count]) => {
                        if (count > 0) {
                            cardContent += `<div class="valor-item">${getEstatusEmoji(estatus)} ${count}</div>`;
                        }
                    });
                } else if (rubroActual === 'financiero') {
                    var cobrado = datos ? datos.cobrado : 0;
                    var potencial = datos ? datos.potencial : 0;
                    var porcentajeMensual = potencial > 0 ? ((cobrado / potencial) * 100).toFixed(1) : '0.0';
                    cardContent += `<div class="valor-item">💵 <span class="valor-destacado">$${formatNumber(cobrado)}</span></div>`;
                    cardContent += `<div class="valor-item">🎯 $${formatNumber(potencial)}</div>`;
                    cardContent += `<div class="porcentaje-eficiencia">${porcentajeMensual}%</div>`;
                }
                cardContent += `</div>`;
            }

            var onClickAction = mes.tieneDatos ? `onclick="irADetalle('${mes.mes}', '${mes.año}')"` : '';
            html += `<div class="mes-card ${cardClass}" ${onClickAction}>${cardContent}</div>`;
        });
        
        var mesesConDatos = rangoMeses.filter(m => m.tieneDatos);
        var datosReales = mesesConDatos.map(m => datosMensuales[m.key]).filter(d => d && d.activo);
        
        if (rubroActual === 'conteos' && datosReales.length > 0) {
            var maxAlumnos = Math.max(...datosReales.map(d => d.alumnos || 0));
            var promPagadores = datosReales.reduce((sum, d) => sum + (d.pagadores || 0), 0) / datosReales.length;
            var promDeudores = datosReales.reduce((sum, d) => sum + (d.deudores || 0), 0) / datosReales.length;
            resumenHtml = `
                <div class="resumen-card"><div class="resumen-valor">${maxAlumnos.toLocaleString('es-MX')}</div><div class="resumen-label">👥 Máximo Alumnos</div></div>
                <div class="resumen-card"><div class="resumen-valor">${Math.round(promPagadores).toLocaleString('es-MX')}</div><div class="resumen-label">💰 Promedio Pagadores</div></div>
                <div class="resumen-card"><div class="resumen-valor">${Math.round(promDeudores).toLocaleString('es-MX')}</div><div class="resumen-label">⚠️ Promedio Deudores</div></div>`;
        } else if (rubroActual === 'financiero' && datosReales.length > 0) {
            var totalCobrado = datosReales.reduce((sum, d) => sum + (d.cobrado || 0), 0);
            var totalPotencial = datosReales.reduce((sum, d) => sum + (d.potencial || 0), 0);
            var eficiencia = totalPotencial > 0 ? ((totalCobrado / totalPotencial) * 100) : 0;
            resumenHtml = `
                <div class="resumen-card"><div class="resumen-valor">$${formatNumber(totalCobrado)}</div><div class="resumen-label">💵 Total Cobrado</div></div>
                <div class="resumen-card"><div class="resumen-valor">$${formatNumber(totalPotencial)}</div><div class="resumen-label">🎯 Total Potencial</div></div>
                <div class="resumen-card"><div class="resumen-valor">${eficiencia.toFixed(1)}%</div><div class="resumen-label">📊 Eficiencia Cobranza</div></div>`;
        } else if (rubroActual === 'estatus' && datosReales.length > 0) {
            var promedioActivos = Math.round(datosReales.reduce((sum, d) => sum + (d.estatus?.ACTIVO || 0), 0) / datosReales.length);
            var promedioProspectos = Math.round(datosReales.reduce((sum, d) => sum + (d.estatus?.PROSPECTO || 0), 0) / datosReales.length);
            var promedioBajas = Math.round(datosReales.reduce((sum, d) => sum + (d.estatus?.BAJA || 0), 0) / datosReales.length);
            resumenHtml = `
                <div class="resumen-card"><div class="resumen-valor">${promedioActivos.toLocaleString('es-MX')}</div><div class="resumen-label">🟢 Promedio Activos</div></div>
                <div class="resumen-card"><div class="resumen-valor">${promedioProspectos.toLocaleString('es-MX')}</div><div class="resumen-label">🔍 Promedio Prospectos</div></div>
                <div class="resumen-card"><div class="resumen-valor">${promedioBajas.toLocaleString('es-MX')}</div><div class="resumen-label">🔴 Promedio Bajas</div></div>`;
        }
        
        $('#concentradoGrid').html(html);
        $('#resumenAnual').html(resumenHtml);
    }
    
    function getEstatusEmoji(estatus) {
        var emojis = {
            'PROSPECTO': '🔍', 'REGISTRO': '📝', 'REGISTRADO': '✅',
            'ACTIVO': '🟢', 'REINGRESO': '🟢', 'GRADUADO': '🎓',
            'NP': '🔴', 'BAJA': '🔴', 'DESERCION': '🔴', 'FIN CURSO': '🏁'
        };
        return emojis[estatus] || '📊';
    }
    
    function formatNumber(num) {
        return Math.round(num).toLocaleString('es-MX');
    }
    
    window.irADetalle = function(mes, año) {
        var url = `grupos.php?periodo=${año}-${mes}&id_gen_focus=${idGenDesdeUrl}`;
        window.open(url, '_blank');
    };
    
    window.debugTendencias = function() {
        console.log('=== DEBUG TENDENCIAS SIN PROYECCIONES ===');
        console.log('ID Generación:', idGenDesdeUrl);
        console.log('Rango de fechas:', rangoMeses);
        console.log('Info generación:', infoGeneracion);
        console.log('Datos mensuales:', datosMensuales);
        console.log('Rubro actual:', rubroActual);
    };
});
</script>