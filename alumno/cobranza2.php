<?php
include('inc/header.php');
$id_alu_ram = $_GET['id_alu_ram'];

$sqlAlumno = "
    SELECT * 
    FROM alu_ram
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
    INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
    WHERE id_alu_ram = '$id_alu_ram' AND id_alu = '$id'
";

$resultadoValidacion = mysqli_query($db, $sqlAlumno);
$validacion = mysqli_num_rows($resultadoValidacion);

if ($validacion == 0) {
    header('location: not_found_404_page.php');
}

$resultadoAlumno = mysqli_query($db, $sqlAlumno);
$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);

$nombreAlumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu']." ".$filaAlumno['apm_alu'];
$correoAlumno = $filaAlumno['cor_alu'];
$nom_ram = $filaAlumno['nom_ram'];
?>

<!-- TITULO -->
<div class="row animated fadeIn">
    <div class="col-md-6 text-left">
        <span class="tituloPagina badge blue-grey darken-4 hoverable">
            <i class="fas fa-bookmark"></i> Información de Pagos: <?php echo $nombreAlumno; ?>
        </span>
        <br>
        <span class="animated fadeIn badge blue-grey darken-4 hoverable">
            Programa: <?php echo $nom_ram; ?>
        </span>
        <br>
        <div class="badge badge-warning animated fadeInUp delay-3s text-white">
            <a href="index.php"><span class="text-white">Inicio</span></a>
            <i class="fas fa-angle-double-right"></i>
            <a style="color: black;" href="">Historial de Pagos</a>
        </div>
    </div>
</div>

<!-- CONTENEDOR -->
<div class="container-fluid mt-4">

    <!-- DOMICILIACIÓN -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: 1px solid #dee2e6; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-start">
                                <div style="width: 56px; height: 56px; background: #17a2b8; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-credit-card" style="font-size: 24px; color: white;"></i>
                                </div>
                                <div class="ml-3 flex-grow-1">
                                    <h5 class="mb-2 font-weight-bold">Domiciliación Bancaria</h5>
                                    <p class="text-muted mb-2">Paga tus colegiaturas automáticamente cada mes</p>
                                    <div id="estado-domiciliacion">
                                        <span class="badge badge-light">
                                            <i class="fas fa-circle-notch fa-spin mr-1"></i>Cargando...
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 text-md-right">
                            <div id="boton-domiciliacion"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGOS -->
    <div class="row">
        <div class="col-12 mb-3">
            <h5 class="font-weight-bold">
                <i class="fas fa-receipt mr-2"></i>Mis Pagos
            </h5>
        </div>
    </div>

    <div class="row" id="grid-pagos">
        <div class="col-12 text-center py-5">
            <i class="fas fa-circle-notch fa-spin text-muted mb-3" style="font-size: 48px;"></i>
            <h5 class="text-muted">Cargando pagos...</h5>
        </div>
    </div>
    
</div>

<!-- MODAL PAGO -->
<div class="modal fade" id="modalPagoStripe" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-lock text-success mr-2"></i>Pago Seguro
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div style="background: #f1f3f5; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Concepto</span>
                        <span class="font-weight-bold" id="modal-concepto"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-muted">Alumno</span>
                        <span><?php echo $nombreAlumno; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0">Total</span>
                        <span class="h3 mb-0 font-weight-bold" id="modal-monto"></span>
                    </div>
                </div>

                <form id="payment-form">
                    <div id="payment-element" class="mb-3"></div>
                    <div id="payment-message" class="alert" style="display: none; border-radius: 8px;"></div>
                    <button type="submit" id="submit-payment" class="btn btn-info btn-block btn-lg" style="border-radius: 8px;">
                        <span id="button-text">
                            <i class="fas fa-lock mr-2"></i>Pagar Ahora
                        </span>
                        <span class="spinner-border spinner-border-sm ml-2" id="spinner" style="display: none;"></span>
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fab fa-stripe mr-1" style="color: #635BFF;"></i>
                        Procesado por Stripe
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTIVAR -->
<div class="modal fade" id="modalActivarDom" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-credit-card text-info mr-2"></i>Activar Domiciliación
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info" style="border-radius: 10px;">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>¿Cómo funciona?</strong>
                    <p class="mb-0 mt-2 small">
                        Vincula tu tarjeta. Tus colegiaturas se cobrarán automáticamente cada mes.
                    </p>
                </div>

                <form id="setup-form">
                    <div id="setup-element" class="mb-3"></div>
                    <div id="setup-message" class="alert" style="display: none; border-radius: 8px;"></div>
                    <button type="submit" id="submit-setup" class="btn btn-info btn-block btn-lg" style="border-radius: 8px;">
                        <span id="setup-button-text">
                            <i class="fas fa-link mr-2"></i>Vincular Tarjeta
                        </span>
                        <span class="spinner-border spinner-border-sm ml-2" id="setup-spinner" style="display: none;"></span>
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fab fa-stripe mr-1" style="color: #635BFF;"></i>
                        Procesado por Stripe
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL GESTIONAR -->
<div class="modal fade" id="modalGestionarDom" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-cog text-info mr-2"></i>Gestionar Domiciliación
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Link cancelar arriba -->
                <div class="text-right mb-3">
                    <a href="javascript:void(0)" id="btn-desactivar-desde-gestion" style="font-size: 13px; color: #dc3545; text-decoration: none;">
                        <i class="fas fa-times-circle mr-1"></i>Cancelar domiciliación
                    </a>
                </div>
                
                <!-- Estado -->
                <div class="alert alert-success mb-4" style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 10px;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-check-circle mr-3" style="font-size: 24px; color: #28a745;"></i>
                        <div class="flex-grow-1">
                            <strong style="font-size: 16px; color: #155724;">Domiciliación Activa</strong>
                            <p class="mb-1 mt-1" style="font-size: 14px; color: #155724;">
                                Tus colegiaturas se cobran automáticamente
                            </p>
                            <p class="mb-0 mt-2" style="font-size: 13px; color: #155724;">
                                <i class="fas fa-calendar-check mr-1"></i>
                                Desde: <strong id="modal-fecha-activacion">--/--/--</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta -->
                <h6 class="font-weight-bold mb-3">Tarjeta vinculada</h6>
                <div class="card border-0 mb-3" style="background: #f1f3f5; border-radius: 10px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fab fa-cc-visa fa-3x mr-3 text-info" id="modal-card-icon"></i>
                            <div class="flex-grow-1">
                                <p class="mb-0 font-weight-bold" style="font-size: 16px;">
                                    <span id="modal-card-brand">VISA</span> •••• <span id="modal-card-last4">****</span>
                                </p>
                                <small class="text-muted">
                                    Expira: <span id="modal-card-exp">--/----</span>
                                </small>
                            </div>
                        </div>
                        
                        <button class="btn btn-info btn-block" id="btn-cambiar-tarjeta" style="border-radius: 8px;">
                            <i class="fas fa-sync-alt mr-2"></i>Cambiar Tarjeta
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL CANCELAR -->
<div class="modal fade" id="modalCancelarDom" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Confirmar
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3">¿Desactivar el cobro automático?</p>
                <p class="text-muted small mb-0">Tendrás que pagar manualmente cada mes.</p>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Cancelar</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmar-cancelar" style="border-radius: 6px;">Desactivar</button>
            </div>
        </div>
    </div>
</div>

<?php include('inc/footer.php'); ?>

<style>
.card-pago {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-pago:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.card-pago-clickable {
    cursor: pointer;
}

.card-pago-clickable:hover .pago-hover {
    opacity: 1;
    visibility: visible;
}

.pago-hover {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(23, 162, 184, 0.95);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    color: white;
}

.pago-hover i {
    font-size: 28px;
}

.pago-hover span {
    font-size: 16px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .btn-activar-dom,
    .btn-gestionar-dom {
        width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
// ========================================
// 🔧 CONFIGURACIÓN
// ========================================
const STRIPE_PUBLIC_KEY = 'pk_live_51RHXUrAr8nVhZRNRUo3igmOmSYodkk9N7VLJJY04AnuLWZlQWHqrchOydh7mt1rHxspduK6k0LTNNg9ZfzutAjd100QNCrgxud';
const API_BASE_URL = 'https://terminal.ahjende.com/api';
const DEBUG_MODE = true; // Cambiar a false en producción

const stripe = Stripe(STRIPE_PUBLIC_KEY);

let elements, setupElements;
let pagoActual = {};

const alumnoData = {
	nom_alu: '<?php echo $nombreAlumno; ?>',
	nom_ram: '<?php echo $nom_ram; ?>',
	id_alu_ram: '<?php echo $id_alu_ram; ?>',
	correo: '<?php echo $correoAlumno; ?>'
};

// ========================================
// 🔧 SISTEMA DE LOGS
// ========================================
function log(tipo, mensaje, datos = null) {
	if (!DEBUG_MODE) return;
	
	const timestamp = new Date().toLocaleTimeString();
	const styles = {
		'info': 'color: #17a2b8; font-weight: bold;',
		'success': 'color: #28a745; font-weight: bold;',
		'error': 'color: #dc3545; font-weight: bold;',
		'warning': 'color: #ffc107; font-weight: bold;',
		'stripe': 'color: #635BFF; font-weight: bold;'
	};
	
	console.log(`%c[${timestamp}] ${tipo.toUpperCase()}:`, styles[tipo] || '', mensaje);
	if (datos) {
		console.log('📦 Datos:', datos);
	}
}

// ========================================
// 📱 DOCUMENT READY
// ========================================
$(document).ready(function() {
	log('info', '✅ Página de pagos cargada (cobranza2.php)', {
		id_alu_ram: alumnoData.id_alu_ram,
		alumno: alumnoData.nom_alu,
		timestamp: new Date().toISOString()
	});
	
	cargarEstadoDomiciliacion();
	cargarPagos();
	
	// Limpiar modal de pago al cerrar
	$('#modalPagoStripe').on('hidden.bs.modal', function() {
		if (elements) { 
			elements.destroy(); 
			elements = null; 
		}
		$('#payment-message').hide();
		$('#submit-payment').prop('disabled', false);
		$('#spinner').hide();
		$('#button-text').show();
	});
	
	// Evento: Activar domiciliación
	$(document).on('click', '#btn-activar-dom', function() {
		log('info', '🔓 Modal de activar domiciliación abierto');
		$('#modalActivarDom').modal('show');
		inicializarSetup();
	});
	
	// Evento: Gestionar domiciliación
	$(document).on('click', '#btn-gestionar-dom', function() {
		log('info', '⚙️ Modal de gestionar domiciliación abierto');
		$('#modalGestionarDom').modal('show');
	});
	
	// Limpiar modal de setup al cerrar
	$('#modalActivarDom').on('hidden.bs.modal', function() {
		if (setupElements) { 
			setupElements.destroy(); 
			setupElements = null; 
		}
		$('#setup-message').hide();
		$('#submit-setup').prop('disabled', false);
		$('#setup-spinner').hide();
		$('#setup-button-text').show();
	});
	
	// Evento: Desactivar desde modal de gestión
	$(document).on('click', '#btn-desactivar-desde-gestion', function() {
		$('#modalGestionarDom').modal('hide');
		setTimeout(() => $('#modalCancelarDom').modal('show'), 300);
	});
	
	// Evento: Cambiar tarjeta
	$(document).on('click', '#btn-cambiar-tarjeta', function() {
		log('info', '🔄 Usuario solicita cambiar tarjeta');
		$('#modalGestionarDom').modal('hide');
		setTimeout(() => {
			$('#modalActivarDom').modal('show');
			inicializarSetup();
		}, 300);
	});
	
	// Evento: Confirmar cancelación
	$('#confirmar-cancelar').click(function() {
		cancelarDomiciliacion();
	});
});

// ========================================
// 🔍 CARGAR ESTADO DE DOMICILIACIÓN
// ========================================
async function cargarEstadoDomiciliacion() {
	try {
		log('info', '🔍 Consultando estado de domiciliación');
		
		const formData = new FormData();
		formData.append('accion', 'obtener_estado_domiciliacion');
		formData.append('id_alu_ram', alumnoData.id_alu_ram);
		
		const response = await fetch('server/controlador_pago.php', {
			method: 'POST',
			body: formData
		});
		
		log('info', '📥 Respuesta HTTP recibida', {
			status: response.status,
			statusText: response.statusText
		});
		
		const data = await response.json();
		
		log('info', '📦 Estado de domiciliación obtenido', data);
		
		if (data.success) {
			renderizarEstadoDomiciliacion(data.data);
		} else {
			log('error', '❌ Error al obtener estado', data);
			throw new Error(data.message);
		}
		
	} catch (error) {
		log('error', '❌ Error en cargarEstadoDomiciliacion()', {
			error: error.message,
			stack: error.stack
		});
		
		renderizarEstadoDomiciliacion({ activa: false });
		console.error('Error al cargar estado de domiciliación:', error);
	}
}

// ========================================
// 🎨 RENDERIZAR ESTADO DE DOMICILIACIÓN
// ========================================
function renderizarEstadoDomiciliacion(estado) {
	
	let estadoHTML = '';
	if (estado.activa) {
		estadoHTML = `
			<span class="badge badge-success" style="padding: 8px 14px; font-size: 13px; font-weight: 600;">
				<i class="fas fa-check-circle mr-1"></i>Domiciliación Activa
			</span>
			<div style="background: #cfe2ff; padding: 8px 12px; border-radius: 8px; margin-top: 8px; display: inline-flex; align-items: center; border: 1px solid #9ec5fe;">
				<i class="fas fa-calendar-check" style="margin-right: 6px; color: #084298;"></i>
				<span style="font-size: 13px; color: #084298;">Activada el ${estado.fecha}</span>
			</div>
		`;
	} else {
		estadoHTML = `
			<span class="badge badge-light" style="padding: 8px 14px; font-size: 13px; font-weight: 600; border-radius: 5px;">
				<i class="fas fa-times-circle mr-1"></i>Sin domiciliación
			</span>
		`;
	}
	
	let botonHTML = '';
	if (estado.activa) {
		botonHTML = `
			<button class="btn btn-info" id="btn-gestionar-dom" style="padding: 12px 28px; font-weight: 600; border-radius: 8px;">
				<i class="fas fa-cog mr-2"></i>Gestionar
			</button>
		`;
		
		// Actualizar datos en el modal de gestión
		if (estado.fecha) $('#modal-fecha-activacion').text(estado.fecha);
		if (estado.last_4) $('#modal-card-last4').text(estado.last_4);
		
		if (estado.brand) {
			const brandUpper = estado.brand.toUpperCase();
			$('#modal-card-brand').text(brandUpper);
			
			let iconClass = 'fa-cc-visa';
			if (estado.brand === 'mastercard') iconClass = 'fa-cc-mastercard';
			if (estado.brand === 'amex') iconClass = 'fa-cc-amex';
			if (estado.brand === 'discover') iconClass = 'fa-cc-discover';
			
			$('#modal-card-icon').removeClass().addClass(`fab ${iconClass} fa-3x mr-3 text-info`);
		}
		
		if (estado.exp_month && estado.exp_year) {
			const expMonthStr = String(estado.exp_month).padStart(2, '0');
			$('#modal-card-exp').text(`${expMonthStr}/${estado.exp_year}`);
		}
		
	} else {
		botonHTML = `
			<button class="btn btn-info" id="btn-activar-dom" style="padding: 12px 28px; font-weight: 600; border-radius: 8px;">
				<i class="fas fa-plus-circle mr-2"></i>Activar Ahora
			</button>
		`;
	}
	
	$('#estado-domiciliacion').html(estadoHTML);
	$('#boton-domiciliacion').html(botonHTML);
}

// ========================================
// 📋 CARGAR PAGOS
// ========================================
async function cargarPagos() {
	try {
		log('info', '📋 Consultando pagos del alumno');
		
		const formData = new FormData();
		formData.append('accion', 'obtener_pagos');
		formData.append('id_alu_ram', alumnoData.id_alu_ram);
		
		const response = await fetch('server/controlador_pago.php', {
			method: 'POST',
			body: formData
		});
		
		const data = await response.json();
		
		log('info', '📦 Pagos obtenidos', data);
		
		if (data.success) {
			renderizarPagos(data.data);
		} else {
			$('#grid-pagos').html(`
				<div class="col-12 text-center py-5">
					<i class="fas fa-receipt text-muted mb-3" style="font-size: 64px; opacity: 0.2;"></i>
					<h5 class="text-muted">No hay pagos registrados</h5>
				</div>
			`);
		}
	} catch (error) {
		log('error', '❌ Error en cargarPagos()', error);
		console.error('Error:', error);
	}
}

// ========================================
// 🎨 RENDERIZAR PAGOS
// ========================================
function renderizarPagos(pagos) {
	if (!pagos || pagos.length === 0) {
		$('#grid-pagos').html(`
			<div class="col-12 text-center py-5">
				<i class="fas fa-receipt text-muted mb-3" style="font-size: 64px; opacity: 0.2;"></i>
				<h5 class="text-muted">No hay pagos registrados</h5>
			</div>
		`);
		return;
	}
	
	let html = '';
	
	pagos.forEach(pago => {
		const badgeClass = pago.is_pagado ? 'badge-success' : 'badge-warning';
		const iconClass = pago.is_pagado ? 'fa-check-circle' : 'fa-clock';
		const clickable = pago.is_pagado ? '' : 'card-pago-clickable';
		const dataAttrs = pago.is_pagado ? '' : `
			data-id-pag="${pago.id_pag}"
			data-monto="${pago.mon_pag}"
			data-tipo="${pago.tip_pag}"
			data-concepto="${pago.tip_pag_formateado}"
		`;
		
		html += `
		<div class="col-lg-3 col-md-4 col-sm-6 mb-3">
			<div class="card card-pago ${clickable}" ${dataAttrs}>
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-start mb-3">
						<span class="badge ${badgeClass}">
							<i class="fas ${iconClass} mr-1"></i>${pago.est_pag}
						</span>
						${!pago.is_pagado ? '<i class="fas fa-credit-card text-info"></i>' : ''}
					</div>
					<div class="mb-2">
						<small class="text-uppercase text-muted d-block mb-1" style="font-size: 11px; font-weight: 600;">
							${pago.tip_pag_formateado}
						</small>
						<h4 class="mb-0 font-weight-bold">$${pago.mon_formateado}</h4>
					</div>
					<div class="text-muted" style="font-size: 13px;">
						<i class="far fa-calendar mr-2"></i>${pago.fecha}
					</div>
				</div>
				${!pago.is_pagado ? `
				<div class="pago-hover">
					<i class="fas fa-arrow-right mb-2"></i>
					<span>Pagar ahora</span>
				</div>
				` : ''}
			</div>
		</div>
		`;
	});
	
	$('#grid-pagos').html(html);
	
	// Evento: Click en pago pendiente
	$('.card-pago-clickable').off('click').on('click', function() {
		const card = $(this);
		pagoActual = {
			id_pag: card.data('id-pag'),
			mon_pag: card.data('monto'),
			tip_pag: card.data('tipo'),
			con_pag: card.data('concepto')
		};
		
		log('info', '💳 Usuario seleccionó pago', pagoActual);
		
		$('#modal-concepto').text(pagoActual.con_pag);
		$('#modal-monto').text('$' + Number(pagoActual.mon_pag).toLocaleString('es-MX'));
		$('#modalPagoStripe').modal('show');
		inicializarPago();
	});
}

// ========================================
// 💳 INICIALIZAR PAGO ÚNICO
// ========================================
async function inicializarPago() {
	try {
		log('info', '💳 Inicializando pago único');
		
		showMessage('Preparando pago...', 'info', 'payment-message');
		
		const requestData = {
			monto: Math.round(parseFloat(pagoActual.mon_pag) * 100),
			descripcion: pagoActual.con_pag,
			metadata: {
				id_pago: pagoActual.id_pag,
				tipo_pago: pagoActual.tip_pag,
				nombre_alumno: alumnoData.nom_alu,
				nombre_programa: alumnoData.nom_ram
			}
		};
		
		log('info', '📤 Solicitando Payment Intent a Node.js', requestData);
		
		const response = await fetch(`${API_BASE_URL}/crear-payment-intent`, {
			method: 'POST',
			headers: {'Content-Type': 'application/json'},
			body: JSON.stringify(requestData)
		});
		
		const data = await response.json();
		
		log('info', '📦 Payment Intent recibido', data);
		
		if (!data.success || !data.clientSecret) {
			throw new Error(data.message || 'Error al preparar pago');
		}
		
		// Inicializar Stripe Elements
		elements = stripe.elements({
			clientSecret: data.clientSecret,
			appearance: { 
				theme: 'stripe',
				variables: {
					colorPrimary: '#17a2b8',
				}
			},
			locale: 'es'
		});
		
		log('info', '🎨 Stripe Elements inicializado para pago');
		
		elements.create('payment').mount('#payment-element');
		hideMessage('payment-message');
		
		log('success', '✅ Formulario de pago montado');
		
	} catch (error) {
		log('error', '❌ Error en inicializarPago()', error);
		showMessage('Error al preparar pago: ' + error.message, 'danger', 'payment-message');
	}
}

// ========================================
// 💳 SUBMIT DEL PAGO ÚNICO
// ========================================
document.getElementById('payment-form').addEventListener('submit', async (e) => {
	e.preventDefault();
	
	log('info', '📝 Usuario envió formulario de pago');
	
	setLoading(true, 'payment');
	
	try {
		log('stripe', '🔄 Confirmando pago con Stripe...');
		
		const {error, paymentIntent} = await stripe.confirmPayment({
			elements,
			confirmParams: {return_url: window.location.href},
			redirect: 'if_required'
		});
		
		if (error) {
			log('error', '❌ Error de Stripe en confirmPayment', {
				type: error.type,
				code: error.code,
				message: error.message
			});
			
			// Traducir errores de Stripe
			let mensajeError = error.message;
			
			switch(error.code) {
				case 'card_declined':
					mensajeError = 'Tu tarjeta fue rechazada. Verifica con tu banco.';
					break;
				case 'expired_card':
					mensajeError = 'Tu tarjeta está vencida.';
					break;
				case 'incorrect_cvc':
					mensajeError = 'El código de seguridad (CVV) es incorrecto.';
					break;
				case 'processing_error':
					mensajeError = 'Error al procesar. Intenta nuevamente.';
					break;
				case 'incorrect_number':
					mensajeError = 'El número de tarjeta es incorrecto.';
					break;
				case 'incomplete_number':
					mensajeError = 'El número de tarjeta está incompleto.';
					break;
			}
			
			showMessage(mensajeError, 'danger', 'payment-message');
			setLoading(false, 'payment');
			
		} else if (paymentIntent && paymentIntent.status === 'succeeded') {
			log('success', '✅ Pago confirmado por Stripe', {
				paymentIntent_id: paymentIntent.id
			});
			
			await registrarPago(paymentIntent.id);
		}
		
	} catch (error) {
		log('error', '❌ Error general en pago', error);
		showMessage('Error: ' + error.message, 'danger', 'payment-message');
		setLoading(false, 'payment');
	}
});

// ========================================
// 💾 REGISTRAR PAGO EN BD
// ========================================
async function registrarPago(paymentIntentId) {
	try {
		log('info', '💾 Registrando pago en base de datos');
		
		showMessage('Registrando pago...', 'info', 'payment-message');
		
		const formData = new FormData();
		formData.append('accion', 'registrar_pago');
		formData.append('id_pag', pagoActual.id_pag);
		formData.append('payment_intent_id', paymentIntentId);
		formData.append('monto', pagoActual.mon_pag);
		formData.append('tip_pag', pagoActual.tip_pag);
		formData.append('id_alu_ram', alumnoData.id_alu_ram);
		
		const response = await fetch('server/controlador_pago.php', {
			method: 'POST',
			body: formData
		});
		
		const data = await response.json();
		
		log('info', '📦 Respuesta del registro de pago', data);
		
		if (data.success) {
			log('success', '✅ Pago registrado exitosamente');
			
			showMessage('✅ Pago registrado', 'success', 'payment-message');
			
			setTimeout(() => {
				$('#modalPagoStripe').modal('hide');
				renderizarPagos(data.data.pagos);
				
				Swal.fire({
					icon: 'success',
					title: '¡Pago Exitoso!',
					text: 'Tu pago ha sido procesado correctamente',
					timer: 2500,
					showConfirmButton: false
				});
			}, 1500);
		} else {
			throw new Error(data.message);
		}
		
	} catch (error) {
		log('error', '❌ Error al registrar pago', error);
		showMessage('Error al registrar: ' + error.message, 'warning', 'payment-message');
		setLoading(false, 'payment');
	}
}

// ========================================
// 🔗 INICIALIZAR SETUP (DOMICILIACIÓN)
// ========================================
async function inicializarSetup() {
	try {
		log('info', '🚀 Iniciando setup de domiciliación');
		
		showMessage('Preparando formulario...', 'info', 'setup-message');
		
		const requestData = {
			id_alu_ram: alumnoData.id_alu_ram,
			email: alumnoData.correo,
			nombre: alumnoData.nom_alu,
			generacion: alumnoData.nom_ram
		};
		
		log('info', '📤 Solicitando Setup Intent a Node.js', requestData);
		
		const response = await fetch(`${API_BASE_URL}/crear-setup-intent`, {
			method: 'POST',
			headers: {'Content-Type': 'application/json'},
			body: JSON.stringify(requestData)
		});
		
		log('info', '📥 Respuesta HTTP recibida', {
			status: response.status,
			statusText: response.statusText
		});
		
		const data = await response.json();
		
		log('info', '📦 Datos JSON parseados', data);
		
		if (!data.success || !data.clientSecret) {
			log('error', '❌ Error al crear Setup Intent', data);
			throw new Error(data.message || 'No se pudo preparar el formulario');
		}
		
		log('success', '✅ Client Secret obtenido');

		// 🔥 GUARDAR SETUP INTENT ID
		window.setupIntentId = data.setupIntentId;
		console.log('🔥 SETUP INTENT ID GUARDADO:', window.setupIntentId);
		
		// Inicializar Stripe Elements
		setupElements = stripe.elements({
			clientSecret: data.clientSecret,
			appearance: { 
				theme: 'stripe',
				variables: {
					colorPrimary: '#17a2b8',
				}
			},
			locale: 'es'
		});
		
		log('info', '🎨 Stripe Elements inicializado para setup');
		
		setupElements.create('payment').mount('#setup-element');
		
		log('success', '✅ Formulario de setup montado en el DOM');
		
		hideMessage('setup-message');
		
	} catch (error) {
		log('error', '❌ Error en inicializarSetup()', {
			error: error.message,
			stack: error.stack
		});
		
		showMessage('Error al cargar formulario: ' + error.message, 'danger', 'setup-message');
	}
}

// ========================================
// 🔗 SUBMIT DEL SETUP (VINCULAR TARJETA)
// ========================================
document.getElementById('setup-form').addEventListener('submit', async (e) => {
	e.preventDefault();
	
	log('info', '📝 Usuario envió formulario de setup');
	
	setLoading(true, 'setup');
	
	try {
		log('stripe', '🔄 Confirmando setup con Stripe...');
		
		const {error, setupIntent} = await stripe.confirmSetup({
			elements: setupElements,
			confirmParams: {return_url: window.location.href},
			redirect: 'if_required'
		});
		
		if (error) {
			log('error', '❌ Error de Stripe en confirmSetup', {
				type: error.type,
				code: error.code,
				message: error.message,
				fullError: error
			});
			
			// Traducir errores de Stripe
			let mensajeError = error.message;
			
			switch(error.code) {
				case 'card_declined':
					mensajeError = 'Tu tarjeta fue rechazada. Verifica con tu banco.';
					break;
				case 'expired_card':
					mensajeError = 'Tu tarjeta está vencida.';
					break;
				case 'incorrect_cvc':
					mensajeError = 'El código de seguridad (CVV) es incorrecto.';
					break;
				case 'processing_error':
					mensajeError = 'Error al procesar. Intenta nuevamente.';
					break;
				case 'incorrect_number':
					mensajeError = 'El número de tarjeta es incorrecto.';
					break;
				case 'incomplete_number':
					mensajeError = 'El número de tarjeta está incompleto.';
					break;
			}
			
			showMessage(mensajeError, 'danger', 'setup-message');
			setLoading(false, 'setup');
			
		} else if (setupIntent && setupIntent.status === 'succeeded') {
			
			log('success', '✅ Setup Intent confirmado por Stripe', {
				setupIntent_id: setupIntent.id,
				status: setupIntent.status
			});
			
			await activarDomiciliacionCompleta(setupIntent.id);
			
		} else {
			log('warning', '⚠️ Setup Intent en estado inesperado', {
				status: setupIntent?.status,
				setupIntent: setupIntent
			});
			
			showMessage('Estado inesperado. Intenta nuevamente.', 'warning', 'setup-message');
			setLoading(false, 'setup');
		}
		
	} catch (error) {
		log('error', '❌ Error general en submit setup', {
			error: error.message,
			stack: error.stack
		});
		
		showMessage('Error: ' + error.message, 'danger', 'setup-message');
		setLoading(false, 'setup');
	}
});

// ========================================
// 🔗 ACTIVAR DOMICILIACIÓN COMPLETA
// ========================================
async function activarDomiciliacionCompleta(setupIntentId) {
	try {
		log('info', '💳 Activando domiciliación completa via Node.js');
		
		showMessage('Activando domiciliación...', 'info', 'setup-message');
		
		// 🔥 UNA SOLA LLAMADA AL NUEVO ENDPOINT
		const response = await fetch(`${API_BASE_URL}/activar-domiciliacion-completa`, {
			method: 'POST',
			headers: {'Content-Type': 'application/json'},
			body: JSON.stringify({
				id_alu_ram: alumnoData.id_alu_ram,
				setup_intent_id: setupIntentId,
				email: alumnoData.correo,
				nombre: alumnoData.nom_alu,
				generacion: alumnoData.nom_ram
			})
		});
		
		log('info', '📥 Respuesta HTTP recibida', {
			status: response.status,
			statusText: response.statusText
		});
		
		const data = await response.json();
		
		log('info', '📦 Datos JSON parseados', data);
		
		if (!data.success) {
			log('error', '❌ Error del backend', data);
			throw new Error(data.message || 'Error al activar domiciliación');
		}
		
		log('success', '✅ Domiciliación activada correctamente', data.data);
		
		showMessage('✅ Tarjeta vinculada exitosamente', 'success', 'setup-message');
		
		setTimeout(() => {
			$('#modalActivarDom').modal('hide');
			
			// 🔥 ACTUALIZAR UI CON LOS DATOS REALES
			renderizarEstadoDomiciliacion({
				activa: true,
				fecha: data.data.fecha,
				last_4: data.data.last_4,
				brand: data.data.brand,
				exp_month: data.data.exp_month,
				exp_year: data.data.exp_year
			});
			
			// 🔥 MOSTRAR NOTIFICACIÓN DE ÉXITO
			const brandName = formatearBrand(data.data.brand);
			Swal.fire({
				icon: 'success',
				title: '¡Domiciliación Activada!',
				html: `
					<p>Tu tarjeta <strong>${brandName} •••• ${data.data.last_4}</strong> ha sido vinculada.</p>
					<p class="text-muted small">Tus colegiaturas se cobrarán automáticamente</p>
				`,
				timer: 3000,
				showConfirmButton: false
			});
		}, 1500);
		
	} catch (error) {
		log('error', '❌ Error en activarDomiciliacionCompleta()', {
			error: error.message,
			stack: error.stack
		});
		
		showMessage(error.message, 'danger', 'setup-message');
		setLoading(false, 'setup');
		
		Swal.fire({
			icon: 'error',
			title: 'Error al Vincular Tarjeta',
			text: error.message
		});
	}
}

// ========================================
// 🗑️ CANCELAR DOMICILIACIÓN
// ========================================
async function cancelarDomiciliacion() {
	try {
		log('info', '🗑️ Cancelando domiciliación via Node.js');
		
		// 🔥 UNA SOLA LLAMADA AL NUEVO ENDPOINT
		const response = await fetch(`${API_BASE_URL}/cancelar-domiciliacion-completa`, {
			method: 'POST',
			headers: {'Content-Type': 'application/json'},
			body: JSON.stringify({
				id_alu_ram: alumnoData.id_alu_ram
			})
		});
		
		const data = await response.json();
		
		log('info', '📦 Respuesta de cancelación', data);
		
		if (!data.success) {
			throw new Error(data.message);
		}
		
		log('success', '✅ Domiciliación cancelada');
		
		$('#modalCancelarDom').modal('hide');
		
		Swal.fire({
			icon: 'success',
			title: 'Desactivada',
			text: 'La domiciliación ha sido cancelada',
			timer: 2000,
			showConfirmButton: false
		});
		
		renderizarEstadoDomiciliacion({ activa: false });
		
	} catch (error) {
		log('error', '❌ Error al cancelar domiciliación', error);
		
		Swal.fire({
			icon: 'error',
			title: 'Error',
			text: error.message || 'No se pudo cancelar la domiciliación'
		});
	}
}

// ========================================
// 🎨 UTILIDADES
// ========================================
function formatearBrand(brand) {
	const marcas = {
		'visa': 'Visa',
		'mastercard': 'Mastercard',
		'amex': 'American Express',
		'discover': 'Discover',
		'diners': 'Diners Club',
		'jcb': 'JCB',
		'unionpay': 'UnionPay'
	};
	return marcas[brand?.toLowerCase()] || brand?.toUpperCase() || 'Tarjeta';
}

function showMessage(text, type, elementId) {
	const msg = $(`#${elementId}`);
	msg.removeClass('alert-danger alert-success alert-info alert-warning');
	msg.addClass(`alert-${type}`);
	msg.text(text);
	msg.show();
}

function hideMessage(elementId) {
	$(`#${elementId}`).hide();
}

function setLoading(isLoading, tipo) {
	if (tipo === 'payment') {
		$('#submit-payment').prop('disabled', isLoading);
		$('#spinner').toggle(isLoading);
		$('#button-text').toggle(!isLoading);
	} else {
		$('#submit-setup').prop('disabled', isLoading);
		$('#setup-spinner').toggle(isLoading);
		$('#setup-button-text').toggle(!isLoading);
	}
}
</script>