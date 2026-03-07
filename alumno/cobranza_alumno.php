<?php
include('inc/header.php');
$id_alu_ram = $_GET['id_alu_ram'];

// Validación del alumno y rama
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

// DATOS ALUMNO
$nombreAlumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu']." ".$filaAlumno['apm_alu'];
$tipo = $filaAlumno['tip_alu'];
$ingreso = $filaAlumno['ing_alu'];
$id_alu = $filaAlumno['id_alu'];

// DATOS ADICIONALES ALUMNO
$fotoAlumno = $filaAlumno['fot_alu'];
$ingresoAlumno = $filaAlumno['ing_alu'];
$direccionAlumno = $filaAlumno['dir_alu'];
$cpAlumno = $filaAlumno['cp_alu'];
$coloniaAlumno = $filaAlumno['col_alu'];
$delegacionAlumno = $filaAlumno['del_alu'];
$entidadAlumno = $filaAlumno['ent_alu'];
$saldoAlumno = $filaAlumno['sal_alu'];
$bec_alu_ram = $filaAlumno['bec_alu_ram'];
$matriculaAlumno = $filaAlumno['bol_alu'];
$telefonoAlumno = $filaAlumno['tel_alu'];
$telefono2Alumno = $filaAlumno['tel2_alu'];
$correoAlumno = $filaAlumno['cor_alu'];
$passwordAlumno = $filaAlumno['pas_alu'];

// DATOS CARRERA
$id_ram = $filaAlumno['id_ram3'];
$nom_ram = $filaAlumno['nom_ram'];

// CONSULTA DE PAGOS - Ordenados por estado (Pendiente primero) y fecha
$sqlPagos = "
    SELECT 
        id_pag,
        id_alu_ram10,
        tip_pag,
        mon_pag,
        mon_ori_pag,
        est_pag,
        ini_pag,
        fin_pag
    FROM pago 
    WHERE id_alu_ram10 = '$id_alu_ram'
    ORDER BY 
        CASE 
            WHEN est_pag = 'Pendiente' THEN 1 
            WHEN est_pag = 'Pagado' THEN 2 
            ELSE 3 
        END,
        fin_pag ASC
";

$resultadoPagos = mysqli_query($db, $sqlPagos);

// Verificar si la consulta fue exitosa
if (!$resultadoPagos) {
    echo "Error en la consulta: " . mysqli_error($db);
}
?>

<!-- TITULO -->
<div class="row animated fadeIn">
    <div class="col-md-6 text-left">
        <span class="tituloPagina badge blue-grey darken-4 hoverable" title="Información de pagos">
            <i class="fas fa-bookmark"></i> Información de Pagos: <?php echo $nombreAlumno; ?>
        </span>
        <br>
        <span class="animated fadeIn badge blue-grey darken-4 hoverable" title="Títulos">
            Programa: <?php echo $nom_ram; ?>
        </span>
        <br>
        <div class="badge badge-warning animated fadeInUp delay-3s text-white">
            <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
            <i class="fas fa-angle-double-right"></i>
            <a style="color: black;" href="" title="Estás aquí">Historial de Pagos</a>
        </div>
    </div>
</div>
<!-- FIN TITULO -->

<!-- CONTENEDOR PRINCIPAL DE PAGOS -->
<div class="container-fluid mt-4">
    <div class="row">
                    
                    <?php
                    if ($resultadoPagos && mysqli_num_rows($resultadoPagos) > 0) {
                        while ($filaPago = mysqli_fetch_assoc($resultadoPagos)) {
                            // Formatear el tipo de pago
                            $tipoPago = '';
                            switch($filaPago['tip_pag']) {
                                case 'Inscripción':
                                    $tipoPago = 'INSCRIPCIÓN';
                                    break;
                                case 'Colegiatura':
                                    $tipoPago = 'COLEGIATURA';
                                    break;
                                case 'Reinscripción':
                                    $tipoPago = 'REINSCRIPCIÓN';
                                    break;
                                case 'Otros':
                                    $tipoPago = 'TRÁMITE';
                                    break;
                                default:
                                    $tipoPago = strtoupper($filaPago['tip_pag']);
                            }
                            
                            // Formatear la fecha
                            $fechaVencimiento = '';
                            if (!empty($filaPago['fin_pag']) && $filaPago['fin_pag'] != '0000-00-00') {
                                $fechaVencimiento = date('d-M-Y', strtotime($filaPago['fin_pag']));
                                $fechaVencimiento = str_replace(
                                    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                    ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
                                    $fechaVencimiento
                                );
                            }
                            
                            // Determinar el estado y color
                            $estadoPago = $filaPago['est_pag'];
                            $colorEstado = '';
                            $iconoEstado = '';
                            
                            if ($estadoPago == 'Pagado') {
                                $colorEstado = 'success';
                                $iconoEstado = 'fas fa-check-circle';
                            } else {
                                $colorEstado = 'warning';
                                $iconoEstado = 'fas fa-clock';
                            }
                            
                            // Determinar qué monto mostrar según el estado
                            if ($estadoPago == 'Pagado') {
                                $montoMostrar = $filaPago['mon_ori_pag'];
                            } else {
                                $montoMostrar = $filaPago['mon_pag'];
                            }
                            
                            // Formatear el monto
                            $monto = number_format($montoMostrar, 0, '.', ',');
                    ?>
                    
                    <!-- TARJETA DE PAGO -->
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card payment-card"
                            <?php /* DESCOMENTAR PARA ACTIVAR PAGO CON STRIPE
                            if ($estadoPago == 'Pendiente') { ?>
                                class="card payment-card payment-card-clickable"
                                data-id-pag="<?php echo $filaPago['id_pag']; ?>"
                                data-monto="<?php echo $filaPago['mon_pag']; ?>"
                                data-tipo="<?php echo $filaPago['tip_pag']; ?>"
                                data-concepto="<?php echo $tipoPago; ?>"
                            <?php } 
                            */ ?>
                        >
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge badge-<?php echo $colorEstado; ?> badge-pill">
                                        <i class="<?php echo $iconoEstado; ?> mr-1"></i>
                                        <?php echo $estadoPago; ?>
                                    </span>
                                    <?php if ($estadoPago == 'Pendiente') { ?>
                                        <i class="fas fa-shield-alt text-dark"></i>
                                    <?php } ?>
                                </div>
                                <div class="text-muted small mb-1">
                                    <strong><?php echo $tipoPago; ?></strong>
                                </div>
                                <div class="h5 mb-2 font-weight-bold">
                                    $ <?php echo $monto; ?>
                                </div>
                                <div class="text-muted" style="font-size: 12px;">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?php echo $fechaVencimiento; ?>
                                </div>
                            </div>
                            <div class="payment-border border-<?php echo $colorEstado; ?>"></div>
                        </div>
                    </div>
                    
                    <?php
                        }
                    } else {
                    ?>
                    <!-- MENSAJE SIN PAGOS -->
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron registros de pagos</h5>
                        <p class="text-muted">Este alumno aún no tiene pagos registrados en el sistema.</p>
                    </div>
                    <?php
                    }
                    ?>
                    
    </div> <!-- Cierre row -->
                    
</div> <!-- Cierre container-fluid -->
<!-- FIN CONTENEDOR PRINCIPAL DE PAGOS -->

<?php /* DESCOMENTAR CUANDO SE ACTIVE STRIPE
<!-- MODAL DE PAGO STRIPE - CLEAN & MODERN -->
<div class="modal fade" id="modalPagoStripe" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-clean">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-lock text-success mr-2"></i>
                    Pago seguro
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="payment-info mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Concepto</span>
                        <span class="font-weight-600" id="modal-concepto"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Alumno</span>
                        <span class="font-weight-500"><?php echo $nombreAlumno; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0">Total</span>
                        <span class="h4 mb-0 font-weight-bold text-dark" id="modal-monto"></span>
                    </div>
                </div>

                <form id="payment-form">
                    <div id="payment-element" class="mb-3"></div>
                    <div id="payment-message" class="alert" style="display: none;"></div>
                    <button id="submit-payment" class="btn btn-dark btn-lg btn-block btn-pay-clean">
                        <span id="button-text">
                            <i class="fas fa-lock mr-2"></i>
                            Pagar
                        </span>
                        <span class="spinner-border spinner-border-sm ml-2" id="spinner" style="display: none;"></span>
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted d-flex align-items-center justify-content-center">
                        <i class="fab fa-stripe mr-2" style="font-size: 18px; color: #635BFF;"></i>
                        Procesado por Stripe
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
*/ ?>

<?php
include('inc/footer.php');
?>

<style>
.payment-card {
    position: relative;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
    background: white;
}
.payment-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}
.payment-card-clickable {
    cursor: pointer;
}
.payment-card-clickable:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    transform: translateY(-4px);
}
.payment-card-clickable:active {
    transform: translateY(-1px);
}
.payment-border {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
}
.payment-border.border-success {
    background: #28a745;
}
.payment-border.border-warning {
    background: #ffc107;
}
.modal-clean {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.modal-clean .modal-header {
    background: white;
    padding: 24px 24px 16px 24px;
}
.modal-clean .modal-body {
    background: white;
}
.payment-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
}
.btn-pay-clean {
    background: #000;
    border: none;
    color: white;
    font-weight: 600;
    padding: 16px;
    border-radius: 12px;
    transition: all 0.2s ease;
    font-size: 16px;
}
.btn-pay-clean:hover {
    background: #1a1a1a;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.btn-pay-clean:active {
    transform: translateY(0);
}
.btn-pay-clean:focus {
    box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
    color: white;
}
#payment-element {
    border-radius: 8px;
    overflow: hidden;
}
.badge-pill {
    border-radius: 12px;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
}
.font-weight-500 {
    font-weight: 500;
}
.font-weight-600 {
    font-weight: 600;
}
</style>

<?php /* DESCOMENTAR CUANDO SE ACTIVE STRIPE
<script src="https://js.stripe.com/v3/"></script>
<script>
const STRIPE_PUBLIC_KEY = 'pk_live_51RHXUrAr8nVhZRNRUo3igmOmSYodkk9N7VLJJY04AnuLWZlQWHqrchOydh7mt1rHxspduK6k0LTNNg9ZfzutAjd100QNCrgxud';
const API_BASE_URL = 'https://terminal.ahjende.com/api';
const stripe = Stripe(STRIPE_PUBLIC_KEY);
let elements;
let pagoActual = {};

const alumnoData = {
    nom_alu: '<?php echo $nombreAlumno; ?>',
    nom_ram: '<?php echo $nom_ram; ?>',
    id_alu_ram: '<?php echo $id_alu_ram; ?>'
};

$(document).ready(function() {
    // Click directo en la tarjeta para pagar
    $('.payment-card-clickable').click(function() {
        const card = $(this);
        pagoActual = {
            id_pag: card.data('id-pag'),
            mon_pag: card.data('monto'),
            tip_pag: card.data('tipo'),
            con_pag: card.data('concepto')
        };
        $('#modal-concepto').text(pagoActual.con_pag);
        $('#modal-monto').text('$ ' + Number(pagoActual.mon_pag).toLocaleString('es-MX'));
        $('#modalPagoStripe').modal('show');
        inicializarPago();
    });

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
});

async function inicializarPago() {
    try {
        showMessage('Preparando pago seguro...', 'info');
        const response = await fetch(`${API_BASE_URL}/crear-payment-intent`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                monto: Math.round(parseFloat(pagoActual.mon_pag) * 100),
                descripcion: pagoActual.con_pag,
                metadata: {
                    id_pago: pagoActual.id_pag,
                    tipo_pago: pagoActual.tip_pag,
                    nombre_alumno: alumnoData.nom_alu,
                    nombre_programa: alumnoData.nom_ram
                }
            })
        });
        const data = await response.json();
        if (!data.success || !data.clientSecret) throw new Error('Error al crear el intento de pago');
        
        const options = {
            clientSecret: data.clientSecret,
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#000000',
                    colorBackground: '#ffffff',
                    colorText: '#30313d',
                    colorDanger: '#df1b41',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    borderRadius: '8px',
                    spacingUnit: '4px'
                },
                rules: {
                    '.Input': {
                        border: '1px solid #e0e0e0',
                        boxShadow: 'none'
                    },
                    '.Input:focus': {
                        border: '1px solid #000',
                        boxShadow: '0 0 0 1px #000'
                    }
                }
            },
            locale: 'es'
        };
        elements = stripe.elements(options);
        elements.create('payment').mount('#payment-element');
        hideMessage();
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error al preparar el pago. Intenta de nuevo.', 'danger');
    }
}

document.getElementById('payment-form').addEventListener('submit', handleSubmit);

async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);
    try {
        const {error, paymentIntent} = await stripe.confirmPayment({
            elements,
            confirmParams: {return_url: window.location.href},
            redirect: 'if_required'
        });
        if (error) {
            showMessage(error.message, 'danger');
            setLoading(false);
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            await registrarPago(paymentIntent.id);
        }
    } catch (error) {
        showMessage('Error al procesar el pago', 'danger');
        setLoading(false);
    }
}

async function registrarPago(paymentIntentId) {
    try {
        showMessage('Registrando pago...', 'info');
        const formData = new FormData();
        formData.append('id_pag', pagoActual.id_pag);
        formData.append('mon_pag', pagoActual.mon_pag);
        formData.append('tip_abo_pag', 'Tarjeta');
        formData.append('mon_abo_pag', pagoActual.mon_pag);
        formData.append('tip_pag', pagoActual.tip_pag);
        formData.append('str_abo_pag', paymentIntentId);
        
        const response = await fetch(`${API_BASE_URL}/pagos/abonar`, {method: 'POST', body: formData});
        const data = await response.json();
        
        if (data.trans) {
            showMessage('¡Pago procesado exitosamente!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            throw new Error('Error al registrar el pago');
        }
    } catch (error) {
        showMessage('El pago se procesó pero hubo un error al registrarlo. Contacte a soporte.', 'warning');
        setLoading(false);
    }
}

function showMessage(messageText, type = 'info') {
    const msg = document.querySelector('#payment-message');
    msg.classList.remove('alert-danger', 'alert-success', 'alert-info', 'alert-warning');
    msg.classList.add(`alert-${type}`);
    msg.textContent = messageText;
    msg.style.display = 'block';
}

function hideMessage() {
    document.querySelector('#payment-message').style.display = 'none';
}

function setLoading(isLoading) {
    document.querySelector('#submit-payment').disabled = isLoading;
    document.querySelector('#spinner').style.display = isLoading ? 'inline-block' : 'none';
    document.querySelector('#button-text').style.display = isLoading ? 'none' : 'inline-block';
}
</script>