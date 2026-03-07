<?php  

// obtener_pagos_tramites.php

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);

// Obtener datos de la generación
$sqlGeneracion = "SELECT nom_gen, ini_gen, mon_tra_gen FROM generacion WHERE id_gen = '$id_gen'";
$resultadoGeneracion = mysqli_query($db, $sqlGeneracion);
$datosGeneracion = mysqli_fetch_assoc($resultadoGeneracion);
$ini_gen = $datosGeneracion['ini_gen'];
$mon_tra_gen = $datosGeneracion['mon_tra_gen'] ?: 0;

// Obtener registros existentes
$sqlPagos = "SELECT id_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag
             FROM grupo_pago 
             WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Otros'
             ORDER BY id_gru_pag ASC";
$resultadoPagos = mysqli_query($db, $sqlPagos);
$registrosExistentes = [];
if ($resultadoPagos) {
    while ($fila = mysqli_fetch_assoc($resultadoPagos)) {
        $registrosExistentes[] = $fila;
    }
}
$cantidadExistentes = count($registrosExistentes);
?>

<style>
.card {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    margin-bottom: 8px;
    font-size: 12px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 4px 4px 0 0;
    padding: 8px 12px;
    font-weight: 600;
    color: #495057;
    font-size: 12px;
}

.card-body {
    padding: 12px;
}

.info-generacion {
    background-color: #f8f9fa;
    border-radius: 3px;
    padding: 8px;
}

.tramite-row {
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    margin-bottom: 8px;
    padding: 10px;
    position: relative;
    transition: all 0.2s ease;
}

.tramite-row:hover {
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.tramite-row.nuevo {
    background-color: #e7f3ff;
    border-color: #007bff;
}

.btn-eliminar-tramite {
    position: absolute;
    top: 5px;
    right: 5px;
    color: #dc3545;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    background: none;
    border: none;
    padding: 2px;
    width: 20px;
    height: 20px;
    line-height: 1;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.btn-eliminar-tramite:hover {
    background-color: #dc3545;
    color: white;
}

#btn-agregar-tramite {
    color: #0066cc;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

#btn-agregar-tramite:hover {
    color: #004499;
    text-decoration: underline !important;
}

.stat-card {
    text-align: center;
    padding: 5px;
}

.stat-value {
    font-size: 14px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 2px;
}

.stat-label {
    font-size: 10px;
    color: #6c757d;
    font-weight: 500;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 3px;
    font-size: 10px;
}

.form-control {
    border-radius: 3px;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    font-size: 12px;
    padding: 4px 8px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
}

.input-group-text {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    font-weight: 500;
    font-size: 12px;
    padding: 4px 8px;
}
</style>

<!-- Card de Información de la Generación -->
<div class="card">
    <div class="card-header">
        <i class="mdi mdi-information"></i> Pagos de trámites de grupo
    </div>
    <div class="card-body">
        <div class="info-generacion">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo date('d/m/Y', strtotime($ini_gen)); ?></div>
                        <div class="stat-label">Fecha Inicio</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value">$<?php echo number_format($mon_tra_gen, 2); ?></div>
                        <div class="stat-label">Monto Base</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value" id="contador-tramites"><?php echo $cantidadExistentes; ?></div>
                        <div class="stat-label">Total de Pagos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hipervínculo Agregar (fuera del card) -->
<div class="text-end mb-2">
    <a href="#" id="btn-agregar-tramite" class="text-decoration-none">
        <i class="mdi mdi-plus-circle"></i> Agregar Nuevo Pago
    </a>
</div>

<!-- Card de Pagos -->
<div class="card">
    <div class="card-header">
        <i class="mdi mdi-credit-card-outline"></i> Gestión de Pagos
    </div>
    <div class="card-body">
        <div id="contenedor-tramites">
            <?php if (empty($registrosExistentes)) { ?>
                <div class="text-center text-muted py-2">
                    <i class="mdi mdi-credit-card-off" style="font-size: 24px; opacity: 0.3;"></i>
                    <p class="mb-0 mt-1" style="font-size: 12px;">No hay pagos registrados</p>
                    <small style="font-size: 10px;">Utiliza el enlace "Agregar Nuevo Pago" para comenzar</small>
                </div>
            <?php } else { ?>
                <?php foreach ($registrosExistentes as $registro) { ?>
                    <div class="tramite-row" data-tipo="existente" data-id="<?php echo $registro['id_gru_pag']; ?>">
                        <button type="button" class="btn-eliminar-tramite" title="Eliminar">×</button>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Concepto:</label>
                                <input type="text" class="form-control concepto-tramite" 
                                       value="<?php echo htmlspecialchars($registro['con_gru_pag']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Monto:</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control monto-tramite" 
                                           value="<?php echo $registro['mon_gru_pag']; ?>" 
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha:</label>
                                <input type="date" class="form-control fecha-tramite" 
                                       value="<?php echo $registro['ini_gru_pag']; ?>">
                            </div>
                        </div>
                        <input type="hidden" class="id-tramite" value="<?php echo $registro['id_gru_pag']; ?>">
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<script>
// Variables globales para el modal de trámites
window.modalTramitesData = {
    idGen: <?php echo $id_gen; ?>,
    fechaInicio: '<?php echo $ini_gen; ?>',
    montoBase: <?php echo $mon_tra_gen; ?>
};

// Función para calcular fecha por semana
function calcularFechaSemana(fechaInicio, numeroSemana) {
    if (!fechaInicio) return '';
    
    const fecha = new Date(fechaInicio);
    const diasAgregar = (numeroSemana - 1) * 7 + 1;
    fecha.setDate(fecha.getDate() + diasAgregar);
    
    return fecha.toISOString().split('T')[0];
}

// Función para obtener próxima semana disponible
function obtenerProximaSemana() {
    return 7 + ($('.tramite-row').length * 4);
}

// Función para agregar nuevo trámite
function agregarNuevoTramite() {
    // Ocultar mensaje de "no hay pagos" si existe
    $('.text-center.text-muted').fadeOut(300);
    
    const proximaSemana = obtenerProximaSemana();
    const numeroTramite = $('.tramite-row').length + 1;
    const concepto = `TRAMITE ${numeroTramite} - SEMANA ${proximaSemana}`;
    const fechaCalculada = calcularFechaSemana(window.modalTramitesData.fechaInicio, proximaSemana);
    
    const html = `
        <div class="tramite-row nuevo" data-tipo="nuevo" style="display: none;">
            <button type="button" class="btn-eliminar-tramite" title="Eliminar">×</button>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Concepto:</label>
                    <input type="text" class="form-control concepto-tramite" value="${concepto}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Monto:</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control monto-tramite" 
                               value="${window.modalTramitesData.montoBase}" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha:</label>
                    <input type="date" class="form-control fecha-tramite" value="${fechaCalculada}">
                </div>
            </div>
            <input type="hidden" class="id-tramite" value="">
        </div>
    `;
    
    $('#contenedor-tramites').append(html);
    $('.tramite-row.nuevo').last().fadeIn(300);
    $('#contador-tramites').text($('.tramite-row').length);
}

// Función para actualizar contador
function actualizarContador() {
    const total = $('.tramite-row').length;
    $('#contador-tramites').text(total);
    
    // Mostrar mensaje si no hay pagos
    if (total === 0) {
        const mensajeVacio = `
            <div class="text-center text-muted py-2">
                <i class="mdi mdi-credit-card-off" style="font-size: 24px; opacity: 0.3;"></i>
                <p class="mb-0 mt-1" style="font-size: 12px;">No hay pagos registrados</p>
                <small style="font-size: 10px;">Utiliza el enlace "Agregar Nuevo Pago" para comenzar</small>
            </div>
        `;
        $('#contenedor-tramites').html(mensajeVacio);

        // Enviar al backend para eliminar de la base de datos
        $.ajax({
            url: 'server/controlador_grupo2.php',
            type: 'POST',
            data: {
                accion: 'guardarPagosTramites',
                id_gen: window.modalTramitesData.idGen,
                tramites: JSON.stringify([]) // Array vacío
            },
            success: function(response) {
                console.log('Eliminación completa guardada en BD');
                // Actualizar tabla principal si existe la función
                if (typeof obtener_datos === 'function') {
                    obtener_datos();
                }
            },
            error: function() {
                console.error('Error al guardar eliminación en BD');
            }
        });
    }
}

// Event handlers
$(document).ready(function() {
    // Agregar nuevo trámite
    $(document).off('click', '#btn-agregar-tramite').on('click', '#btn-agregar-tramite', function(e) {
        e.preventDefault();
        agregarNuevoTramite();
    });
    
    // Eliminar trámite con SweetAlert
    $(document).off('click', '.btn-eliminar-tramite').on('click', '.btn-eliminar-tramite', function() {
        const $tramiteRow = $(this).closest('.tramite-row');
        const concepto = $tramiteRow.find('.concepto-tramite').val();
        
        swal({
            title: "¿Estás seguro?",
            text: 'Se eliminará el pago "' + concepto + '"',
            icon: "warning",
            buttons: {
                cancel: "Cancelar",
                confirm: "Sí, eliminar"
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $tramiteRow.fadeOut(300, function() {
                    $(this).remove();
                    actualizarContador();
                });
            }
        });
    });
    
    // Validación en tiempo real de montos
    $(document).off('input', '.monto-tramite').on('input', '.monto-tramite', function() {
        const valor = parseFloat($(this).val());
        if (valor < 0 || isNaN(valor)) {
            $(this).val(0);
        }
    });

    // Permitir guardar sin trámites
    window.permitirGuardadoVacio = true;
});
</script>