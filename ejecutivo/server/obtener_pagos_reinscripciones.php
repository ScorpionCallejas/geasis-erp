<?php  

// obtener_pagos_reinscripciones.php

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);

// JOIN con rama para obtener periodicidad y ciclos
$sqlGeneracion = "SELECT g.nom_gen, g.ini_gen, g.mon_rei_gen, r.per_ram, r.cic_ram, r.nom_ram
                  FROM generacion g
                  INNER JOIN rama r ON g.id_ram5 = r.id_ram
                  WHERE g.id_gen = '$id_gen'";
$resultadoGeneracion = mysqli_query($db, $sqlGeneracion);
$datosGeneracion = mysqli_fetch_assoc($resultadoGeneracion);

$ini_gen = $datosGeneracion['ini_gen'];
$mon_rei_gen = $datosGeneracion['mon_rei_gen'] ? $datosGeneracion['mon_rei_gen'] : 1500; // Default $1500 compatible PHP 5.6
$per_ram = $datosGeneracion['per_ram'];
$cic_ram = $datosGeneracion['cic_ram'];
$nom_ram = $datosGeneracion['nom_ram'];

// Mapear periodicidad a meses (compatible PHP 5.6)
$mesesPorPeriodicidad = array(
    'Semestral' => 6,
    'Cuatrimestral' => 4,
    'Trimestral' => 3
);

// Compatibilidad PHP 5.6 - sin null coalescing operator
$mesesIncremento = isset($mesesPorPeriodicidad[$per_ram]) ? $mesesPorPeriodicidad[$per_ram] : 3;
$reinscripcionesEsperadas = $cic_ram > 1 ? ($cic_ram - 1) : 0;

// Textos dinámicos según periodicidad
$textoPeriodicidad = strtolower($per_ram);
$textoFrequencia = "cada $mesesIncremento meses";

// Obtener registros existentes
$sqlPagos = "SELECT id_gru_pag, con_gru_pag, mon_gru_pag, ini_gru_pag, fin_gru_pag
             FROM grupo_pago 
             WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Pago' AND tip_pag_gru_pag = 'Reinscripción'
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

.reinscripcion-row {
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    margin-bottom: 8px;
    padding: 10px;
    position: relative;
    transition: all 0.2s ease;
}

.reinscripcion-row:hover {
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.reinscripcion-row.nuevo {
    background-color: #f3e5f5;
    border-color: #9c27b0;
}

.btn-eliminar-reinscripcion {
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

.btn-eliminar-reinscripcion:hover {
    background-color: #dc3545;
    color: white;
}

#btn-agregar-reinscripcion {
    color: #9c27b0;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

#btn-agregar-reinscripcion:hover {
    color: #7b1fa2;
    text-decoration: underline !important;
}

.stat-card {
    text-align: center;
    padding: 5px;
}

.stat-value {
    font-size: 14px;
    font-weight: bold;
    color: #9c27b0;
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
    border-color: #9c27b0;
    box-shadow: 0 0 0 0.1rem rgba(156, 39, 176, 0.25);
}

.input-group-text {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    font-weight: 500;
    font-size: 12px;
    padding: 4px 8px;
}

.programa-info {
    font-size: 11px;
    color: #6c757d;
    margin-top: 4px;
}
</style>

<!-- Card de Información de la Generación -->
<div class="card">
    <div class="card-header">
        <i class="mdi mdi-information"></i> Pagos de reinscripción de grupo (Periodicidad <?php echo ucfirst($textoPeriodicidad); ?>)
    </div>
    <div class="card-body">
        <div class="info-generacion">
            <div class="programa-info">
                <strong><?php echo htmlspecialchars($nom_ram); ?></strong> - 
                <?php echo $cic_ram; ?> ciclos <?php echo $textoPeriodicidad; ?>es - 
                <?php echo $reinscripcionesEsperadas; ?> reinscripciones
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo date('d/m/Y', strtotime($ini_gen)); ?></div>
                        <div class="stat-label">Fecha Inicio</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value">$<?php echo number_format($mon_rei_gen, 2); ?></div>
                        <div class="stat-label">Monto Base</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo ucfirst($textoPeriodicidad); ?></div>
                        <div class="stat-label">Periodicidad</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value" id="contador-reinscripciones"><?php echo $cantidadExistentes; ?></div>
                        <div class="stat-label">Pagos Creados</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hipervínculo Agregar (fuera del card) -->
<div class="text-end mb-2">
    <a href="#" id="btn-agregar-reinscripcion" class="text-decoration-none">
        <i class="mdi mdi-plus-circle"></i> Agregar Nuevo Pago (<?php echo $textoFrequencia; ?>)
    </a>
</div>

<!-- Card de Pagos -->
<div class="card">
    <div class="card-header">
        <i class="mdi mdi-credit-card-outline"></i> Gestión de Pagos <?php echo ucfirst($textoPeriodicidad); ?>es
    </div>
    <div class="card-body">
        <div id="contenedor-reinscripciones">
            <?php if (empty($registrosExistentes)) { ?>
                <div class="text-center text-muted py-2">
                    <i class="mdi mdi-credit-card-off" style="font-size: 24px; opacity: 0.3;"></i>
                    <p class="mb-0 mt-1" style="font-size: 12px;">No hay pagos registrados</p>
                    <small style="font-size: 10px;">Utiliza el enlace "Agregar Nuevo Pago" para comenzar</small>
                </div>
            <?php } else { ?>
                <?php foreach ($registrosExistentes as $registro) { ?>
                    <div class="reinscripcion-row" data-tipo="existente" data-id="<?php echo $registro['id_gru_pag']; ?>">
                        <button type="button" class="btn-eliminar-reinscripcion" title="Eliminar">×</button>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Concepto:</label>
                                <input type="text" class="form-control concepto-reinscripcion" 
                                       value="<?php echo htmlspecialchars($registro['con_gru_pag']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Monto:</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control monto-reinscripcion" 
                                           value="<?php echo $registro['mon_gru_pag']; ?>" 
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha:</label>
                                <input type="date" class="form-control fecha-reinscripcion" 
                                       value="<?php echo $registro['ini_gru_pag']; ?>">
                            </div>
                        </div>
                        <input type="hidden" class="id-reinscripcion" value="<?php echo $registro['id_gru_pag']; ?>">
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<script>
// Variables globales dinámicas
window.modalReinscripcionesData = {
    idGen: <?php echo $id_gen; ?>,
    fechaInicio: '<?php echo $ini_gen; ?>',
    montoBase: <?php echo $mon_rei_gen; ?>,
    periodicidad: '<?php echo $per_ram; ?>',
    mesesIncremento: <?php echo $mesesIncremento; ?>,
    ciclosTotales: <?php echo $cic_ram; ?>,
    reinscripcionesEsperadas: <?php echo $reinscripcionesEsperadas; ?>,
    textoPeriodicidad: '<?php echo $textoPeriodicidad; ?>'
};

// 🔥 FUNCIÓN PARA CALCULAR FECHA SEGÚN PERIODICIDAD DINÁMICA
function calcularFechaPorPeriodicidad(fechaInicio, numeroPago) {
    if (!fechaInicio) return '';
    
    const fecha = new Date(fechaInicio);
    
    // El primer pago es después del primer periodo completo
    // REINSCRIPCIÓN 1 = +mesesIncremento desde el inicio
    const mesesAgregar = numeroPago * window.modalReinscripcionesData.mesesIncremento;
    fecha.setMonth(fecha.getMonth() + mesesAgregar);
    
    return fecha.toISOString().split('T')[0];
}

// 🔥 FUNCIÓN PARA OBTENER PRÓXIMO NÚMERO DE PAGO
function obtenerProximoNumeroPago() {
    return $('.reinscripcion-row').length + 1;
}

// 🔥 FUNCIÓN PARA AGREGAR NUEVA REINSCRIPCIÓN DINÁMICA
function agregarNuevaReinscripcion() {
    // Verificar si ya se alcanzó el máximo esperado
    const cantidadActual = $('.reinscripcion-row').length;
    if (cantidadActual >= window.modalReinscripcionesData.reinscripcionesEsperadas) {
        swal({
            title: "Límite alcanzado",
            text: `Ya tienes ${window.modalReinscripcionesData.reinscripcionesEsperadas} reinscripciones (máximo esperado para ${window.modalReinscripcionesData.ciclosTotales} ciclos)`,
            icon: "info",
            button: "Entendido"
        });
        return;
    }
    
    // Ocultar mensaje de "no hay pagos" si existe
    $('.text-center.text-muted').fadeOut(300);
    
    const proximoNumero = obtenerProximoNumeroPago();
    const mesesDesdeInicio = proximoNumero * window.modalReinscripcionesData.mesesIncremento;
    
    const concepto = `REINSCRIPCIÓN ${proximoNumero}`;
    const fechaCalculada = calcularFechaPorPeriodicidad(window.modalReinscripcionesData.fechaInicio, proximoNumero);
    
    const html = `
        <div class="reinscripcion-row nuevo" data-tipo="nuevo" style="display: none;">
            <button type="button" class="btn-eliminar-reinscripcion" title="Eliminar">×</button>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Concepto:</label>
                    <input type="text" class="form-control concepto-reinscripcion" value="${concepto}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Monto:</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control monto-reinscripcion" 
                               value="${window.modalReinscripcionesData.montoBase}" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha (+${mesesDesdeInicio} meses):</label>
                    <input type="date" class="form-control fecha-reinscripcion" value="${fechaCalculada}">
                </div>
            </div>
            <input type="hidden" class="id-reinscripcion" value="">
        </div>
    `;
    
    $('#contenedor-reinscripciones').append(html);
    $('.reinscripcion-row.nuevo').last().fadeIn(300);
    $('#contador-reinscripciones').text($('.reinscripcion-row').length);
}

// Función para actualizar contador
function actualizarContador() {
    const total = $('.reinscripcion-row').length;
    $('#contador-reinscripciones').text(total);
    
    // Mostrar mensaje si no hay pagos
    if (total === 0) {
        const mensajeVacio = `
            <div class="text-center text-muted py-2">
                <i class="mdi mdi-credit-card-off" style="font-size: 24px; opacity: 0.3;"></i>
                <p class="mb-0 mt-1" style="font-size: 12px;">No hay pagos registrados</p>
                <small style="font-size: 10px;">Utiliza el enlace "Agregar Nuevo Pago" para comenzar</small>
            </div>
        `;
        $('#contenedor-reinscripciones').html(mensajeVacio);

        // Enviar al backend para eliminar de la base de datos
        $.ajax({
            url: 'server/controlador_grupo2.php',
            type: 'POST',
            data: {
                accion: 'guardarPagosReinscripciones',
                id_gen: window.modalReinscripcionesData.idGen,
                reinscripciones: JSON.stringify([]) // Array vacío
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
    // Debug: mostrar configuración en consola
    console.log('Configuración dinámica:', window.modalReinscripcionesData);
    
    // Agregar nueva reinscripción
    $(document).off('click', '#btn-agregar-reinscripcion').on('click', '#btn-agregar-reinscripcion', function(e) {
        e.preventDefault();
        agregarNuevaReinscripcion();
    });
    
    // Eliminar reinscripción con SweetAlert
    $(document).off('click', '.btn-eliminar-reinscripcion').on('click', '.btn-eliminar-reinscripcion', function() {
        const $reinscripcionRow = $(this).closest('.reinscripcion-row');
        const concepto = $reinscripcionRow.find('.concepto-reinscripcion').val();
        
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
                $reinscripcionRow.fadeOut(300, function() {
                    $(this).remove();
                    actualizarContador();
                });
            }
        });
    });
    
    // Validación en tiempo real de montos
    $(document).off('input', '.monto-reinscripcion').on('input', '.monto-reinscripcion', function() {
        const valor = parseFloat($(this).val());
        if (valor < 0 || isNaN(valor)) {
            $(this).val(0);
        }
    });

    // Permitir guardado sin reinscripciones
    window.permitirGuardadoVacio = true;
});
</script>