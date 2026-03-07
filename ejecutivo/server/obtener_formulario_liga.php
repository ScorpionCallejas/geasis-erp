<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');
    
    $modalidad = $_POST['modalidad'];
    $id_cit = $_POST['id_cit'];
    
    // Consulta para datos de la cita
    $sqlCita = "SELECT * FROM cita WHERE id_cit = '$id_cit'";
    $datosCita = obtener_datos_consulta($db, $sqlCita);
    
    // Obtener planteles para el selector
    $sqlPlanteles = "SELECT * FROM plantel WHERE id_cad1 = 1 ORDER BY nom_pla ASC";
    $resultPlanteles = mysqli_query($db, $sqlPlanteles);
?>

<div class="modal-header py-3 bg-dark text-white">
    <h5 class="modal-title text-white" id="modalLigaPagoLabel">
        <i class="fas fa-lock me-2 text-warning"></i>GENERAR LIGA DE PAGO SEGURA - FOLIO: <span id="folioHeader" class="badge bg-warning text-dark"><?php echo $id_cit; ?></span>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    
    <!-- Logo de tarjetas de crédito -->
    <div class="row mb-3">
        <div class="col-md-12 text-center">
            <img src="https://icrono.com/wp-content/uploads/2022/05/png-american-express-logo-png-Visa-Mastercard-American-Express-Logo.png" 
                 alt="Tarjetas aceptadas" class="img-fluid" style="max-height: 40px; opacity: 0.8;">
        </div>
    </div>

    <!-- PLANTEL -->
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="letraPequena text-uppercase fw-bold mb-2">
                <i class="fas fa-university me-2"></i>CENTRO DE DESTINO DEL PAGO:
            </label>
            <select id="plantelLigaPago" class="form-control form-control-sm border-2 border-dark">
                <?php
                while ($rowPlantel = mysqli_fetch_assoc($resultPlanteles)) {
                    $selected = ($rowPlantel['id_pla'] == $plantel) ? 'selected' : '';
                    echo "<option value='".$rowPlantel['id_pla']."' ".$selected.">".strtoupper($rowPlantel['nom_pla'])."</option>";
                }                
                ?>
            </select>
        </div>
    </div>

    <!-- PROGRAMA -->
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="letraPequena text-uppercase fw-bold mb-2">
                <i class="fas fa-book me-2"></i>SELECCIONA PROGRAMA:
            </label>
            <div id="contenedor_programas_liga">
            </div>
        </div>
    </div>

    <!-- GRUPO -->
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="letraPequena text-uppercase fw-bold mb-2">
                <i class="fas fa-users me-2"></i>SELECCIONA GRUPO:
            </label>
            <div id="contenedor_grupos_liga">
            </div>
        </div>
    </div>

    <!-- PAQUETES -->
    <div class="row mb-3" id="contenedorPaquetes" style="display: none;">
        <div class="col-md-12">
            <label class="letraPequena text-uppercase fw-bold mb-2">
                <i class="fas fa-graduation-cap me-2"></i>SELECCIONAR PAQUETE:
            </label>
            <select id="selectorPaquete" class="form-control form-control-sm border-2 border-dark">
                <!-- Se llenará dinámicamente según el programa -->
            </select>
        </div>
    </div>
    
    <!-- Campos ocultos -->
    <div class="row mb-2" style="display: none;">
        <div class="col-md-12">
            <input type="text" class="form-control form-control-sm" id="conceptoManual" value="<?php echo $modalidad; ?>">
        </div>
    </div>
    
    <div class="row mb-2" style="display: none;">
        <div class="col-md-12">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="montoManual" value="850.00" readonly>
            </div>
        </div>
    </div>
    
    <!-- Información de MSI -->
    <div id="infoMSI" class="row mb-3" style="display:none;">
        <div class="col-md-12">
            <div class="alert alert-success py-2 border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-credit-card me-2"></i>
                    <span class="letraPequena text-uppercase fw-bold">MSI DISPONIBLES: <span id="msiDisponibles"></span></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resumen final -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-2 border-success shadow-lg">
                <div class="card-body p-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-dollar-sign text-success me-2" style="font-size: 1.8rem;"></i>
                                <div>
                                    <h6 class="mb-1 text-muted text-uppercase fw-bold">TOTAL FINAL</h6>
                                    <h3 class="mb-0 text-success fw-bold" id="totalFinal" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">$850</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tag text-dark me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1 text-muted text-uppercase fw-bold">CONCEPTO</h6>
                                    <h6 class="mb-0 text-dark fw-bold" id="conceptoFinal">$850 - (1)INSCRIPCIÓN($850)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liga generada -->
    <div class="row mb-3" id="ligaGeneradaContainer" style="display:none;">
        <div class="col-md-12">
            <div class="card border-2 border-dark shadow">
                <div class="card-header py-2 bg-dark text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-link me-2"></i>
                        <span class="letraPequena text-uppercase fw-bold">LIGA DE PAGO GENERADA</span>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="input-group">
                        <input type="text" class="form-control border-2" id="ligaGenerada" readonly>
                        <button class="btn btn-dark" id="btnCopiarLiga" title="COPIAR LIGA">
                            <i class="fas fa-copy me-1"></i>COPIAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Spinner -->
    <div class="row justify-content-center mt-3 mb-0" id="spinnerContainer" style="display:none;">
        <div class="col-md-12 text-center">
            <div class="card border-0 shadow">
                <div class="card-body p-3">
                    <div class="spinner-border spinner-border-sm text-dark mb-2" role="status">
                        <span class="visually-hidden">GENERANDO LIGA...</span>
                    </div>
                    <p class="letraPequena mb-0 text-uppercase fw-bold">
                        <i class="fas fa-shield-alt me-1"></i>PROCESANDO PAGO SEGURO...
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer py-2 bg-light border-top-2 border-dark">
    <button type="button" class="btn btn-outline-dark btn-sm text-uppercase fw-bold" data-bs-dismiss="modal">
        <i class="fas fa-times me-1"></i>CANCELAR
    </button>
    <button type="button" class="btn btn-dark btn-sm text-uppercase fw-bold shadow" id="btnGenerarLiga">
        <i class="fas fa-lock me-1"></i>GENERAR LIGA SEGURA
    </button>
</div>

<script>
// Configuración de paquetes según modalidad del programa
// Configuración de paquetes según modalidad del programa (CORREGIDA)
var paquetesConfig = {
    'DIPLOMADO': {
        1: {
            titulo: "$1,500 - (1)MEMBRESÍA ORO($1,500)",
            tituloSimple: "MEMBRESÍA ORO",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 1500, cantidad: 1, descripcion: "MEMBRESÍA ORO" }
            ],
            total: 1500
        },
        2: {
            titulo: "$3,000 - (1)MEMBRESÍA ORO($3,000)",
            tituloSimple: "MEMBRESÍA ORO",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 3000, cantidad: 1, descripcion: "MEMBRESÍA ORO" }
            ],
            total: 3000
        },
        3: {
            titulo: "$14,000 - (1)MEMBRESÍA ORO($1,500) + (4)COLEGIATURAS($3,125)",
            tituloSimple: "MEMBRESÍA ORO + COLEGIATURAS",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 1500, cantidad: 1, descripcion: "MEMBRESÍA ORO" },
                { concepto: "COLEGIATURA", monto: 3125, cantidad: 4, descripcion: "COLEGIATURA" }
            ],
            total: 14000
        },
        4: {
            titulo: "$16,000 - (1)MEMBRESÍA ORO($3,000) + (4)COLEGIATURAS($3,250)",
            tituloSimple: "MEMBRESÍA ORO + COLEGIATURAS",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 3000, cantidad: 1, descripcion: "MEMBRESÍA ORO" },
                { concepto: "COLEGIATURA", monto: 3250, cantidad: 4, descripcion: "COLEGIATURA" }
            ],
            total: 16000
        },
        5: {
            titulo: "$18,000 - (1)MEMBRESÍA ORO($3,000) + (4)COLEGIATURAS($3,750)",
            tituloSimple: "MEMBRESÍA ORO + COLEGIATURAS",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 3000, cantidad: 1, descripcion: "MEMBRESÍA ORO" },
                { concepto: "COLEGIATURA", monto: 3750, cantidad: 4, descripcion: "COLEGIATURA" }
            ],
            total: 18000
        }
    },
    'BACHILLERATO': {
        'bach1': {
            titulo: "$850 - (1)INSCRIPCIÓN($850)",
            tituloSimple: "INSCRIPCIÓN",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 850, cantidad: 1, descripcion: "INSCRIPCIÓN" }
            ],
            total: 850
        },
        'bach2': {
            titulo: "$1,200 - (1)INSCRIPCIÓN($1,200)",
            tituloSimple: "INSCRIPCIÓN",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 1200, cantidad: 1, descripcion: "INSCRIPCIÓN" }
            ],
            total: 1200
        }
    },
    'PREPARATORIA': {
        'prep1': {
            titulo: "$850 - (1)INSCRIPCIÓN($850)",
            tituloSimple: "INSCRIPCIÓN",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 850, cantidad: 1, descripcion: "INSCRIPCIÓN" }
            ],
            total: 850
        },
        'prep2': {
            titulo: "$1,200 - (1)INSCRIPCIÓN($1,200)",
            tituloSimple: "INSCRIPCIÓN",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 1200, cantidad: 1, descripcion: "INSCRIPCIÓN" }
            ],
            total: 1200
        }
    },
    // AGREGAR LICENCIATURA ya que aparece en tu log
    'LICENCIATURA': {
        'lic1': {
            titulo: "$2,500 - (1)INSCRIPCIÓN($2,500)",
            tituloSimple: "INSCRIPCIÓN",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 2500, cantidad: 1, descripcion: "INSCRIPCIÓN" }
            ],
            total: 2500
        },
        'lic2': {
            titulo: "$3,500 - (1)INSCRIPCIÓN($3,500)",
            tituloSimple: "INSCRIPCIÓN",
            conceptos: [
                { concepto: "INSCRIPCION", monto: 3500, cantidad: 1, descripcion: "INSCRIPCIÓN" }
            ],
            total: 3500
        }
    }
};

// Función para cargar paquetes según modalidad del programa (CORREGIDA)
function cargarPaquetesSegunPrograma(modalidad) {
    modalidadPrograma = modalidad.toUpperCase();
    
    console.log('Cargando paquetes para modalidad:', modalidadPrograma);
    console.log('Configuración disponible:', Object.keys(paquetesConfig));
    
    if (!paquetesConfig[modalidadPrograma]) {
        console.log('No hay configuración para esta modalidad:', modalidadPrograma);
        $('#contenedorPaquetes').hide();
        return;
    }
    
    var paquetes = paquetesConfig[modalidadPrograma];
    var optionsHtml = '';
    
    console.log('Paquetes encontrados:', paquetes);
    
    Object.keys(paquetes).forEach(function(key, index) {
        var paquete = paquetes[key];
        var selected = index === 0 ? 'selected' : '';
        optionsHtml += '<option value="' + key + '" ' + selected + '>' + paquete.titulo + '</option>';
    });
    
    $('#selectorPaquete').html(optionsHtml);
    $('#contenedorPaquetes').show(); // MOSTRAR el contenedor de paquetes
    
    console.log('Paquetes cargados, contenedor mostrado');
    
    // Actualizar automáticamente con el primer paquete
    if (Object.keys(paquetes).length > 0) {
        var primerPaquete = Object.keys(paquetes)[0];
        actualizarMontoPaquete(primerPaquete);
    }
}

// Función para actualizar el monto según el paquete seleccionado
function actualizarMontoPaquete(opcion) {
    if (paquetesConfig[modalidadPrograma] && paquetesConfig[modalidadPrograma][opcion]) {
        var paquete = paquetesConfig[modalidadPrograma][opcion];
        $('#montoManual').val(paquete.total.toFixed(2));
        $('#conceptoManual').val(paquete.titulo);
        
        // Actualizar resumen visual
        $('#totalFinal').text('$' + paquete.total.toLocaleString('es-MX'));
        $('#conceptoFinal').text(paquete.titulo);
        
        // Actualizar MSI
        actualizarInfoMSI(paquete.total);
    }
}

// Función para actualizar información de MSI según monto
function actualizarInfoMSI(monto) {
    if (monto >= 16000) {
        $('#msiDisponibles').text('3, 6, 9 Y 12 MSI');
        $('#infoMSI').show();
    } else if (monto >= 13000) {
        $('#msiDisponibles').text('3 O 6 MSI');
        $('#infoMSI').show();
    } else {
        $('#infoMSI').hide();
    }
}

// Función para generar la liga de pago (MODIFICADA)
function generarLigaPago() {
    var id_cit = $('#folioHeader').text();
    var monto = parseFloat($('#montoManual').val()) || 0;
    var concepto = $('#conceptoManual').val();
    var id_pla = $('#plantelLigaPago').val();
    var opcionSeleccionada = $('#selectorPaquete').val();
    var id_gen_des = $('#selector_generacion option:selected').attr('id_gen') || 0;
    
    console.log('id_gen_des capturado:', id_gen_des);
    
    // Extraer los datos de inscripción y colegiatura según el paquete seleccionado
    var can_ins_cit = 0;
    var mon_ins_cit = 0;
    var can_col_cit = 0;
    var mon_col_cit = 0;
    
    // Verificar si existe configuración para la modalidad
    if (paquetesConfig[modalidadPrograma] && paquetesConfig[modalidadPrograma][opcionSeleccionada]) {
        var paquete = paquetesConfig[modalidadPrograma][opcionSeleccionada];
        
        // Recorrer los conceptos para extraer inscripción y colegiatura
        paquete.conceptos.forEach(function(item) {
            if (item.concepto.toUpperCase() === 'INSCRIPCION') {
                can_ins_cit = item.cantidad;
                mon_ins_cit = item.monto;
            } else if (item.concepto.toUpperCase() === 'COLEGIATURA') {
                can_col_cit = item.cantidad;
                mon_col_cit = item.monto;
            }
        });
    }
    
    // Validar campos
    if (!id_cit || isNaN(monto) || !concepto || !id_pla) {
        toastr.error('POR FAVOR COMPLETA TODOS LOS CAMPOS');
        return;
    }
    
    // Ocultar contenedor y mostrar spinner
    $('#ligaGeneradaContainer').hide();
    $('#spinnerContainer').show();
    
    console.log('DATOS A ENVIAR:');
    console.log('modalidad programa:', modalidadPrograma);
    console.log('can_col_cit:', can_col_cit);
    console.log('mon_col_cit:', mon_col_cit);
    console.log('can_ins_cit:', can_ins_cit);
    console.log('mon_ins_cit:', mon_ins_cit);
    console.log('mon_str_cit:', monto);
    console.log('con_str_cit:', concepto);
    console.log('id_pla_des:', id_pla);
    console.log('id_gen_des:', id_gen_des);
    
    // Enviar datos a través de AJAX
    $.ajax({
        url: 'server/agregar_liga_pago.php',
        type: 'POST',
        data: {
            id_cit: id_cit,
            modalidad: modalidadPrograma,
            can_col_cit: can_col_cit,
            mon_col_cit: mon_col_cit,
            can_ins_cit: can_ins_cit,
            mon_ins_cit: mon_ins_cit,
            mon_str_cit: monto,
            con_str_cit: concepto,
            id_pla_des: id_pla,
            id_gen_des: id_gen_des
        },
        dataType: 'json',
        success: function(response) {
            $('#spinnerContainer').hide();
            
            if (response.status === 'success') {
                var ligaPago = 'https://plataforma.ahjende.com/terminal.php?id_cit=' + id_cit + '&monto=' + monto + '&concepto=' + encodeURIComponent(concepto) + '&id_pla=' + id_pla;
                
                $('#ligaGenerada').val(ligaPago);
                $('#ligaGeneradaContainer').show();
                
                toastr.success('LIGA DE PAGO GENERADA EXITOSAMENTE');
                console.log('Datos guardados correctamente');
            } else {
                toastr.error('ERROR AL GUARDAR LOS DATOS: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            $('#spinnerContainer').hide();
            console.log('Error AJAX:', xhr.responseText);
            toastr.error('ERROR DE CONEXIÓN AL GUARDAR LOS DATOS');
        }
    });
}

// Función para copiar la liga al portapapeles
function copiarLigaPortapapeles() {
    var ligaInput = document.getElementById('ligaGenerada');
    ligaInput.select();
    document.execCommand('copy');
    
    toastr.success('¡LIGA DE PAGO COPIADA AL PORTAPAPELES!');
}

// Event listener para cambio de paquete
$(document).on('change', '#selectorPaquete', function() {
    var opcion = $(this).val();
    actualizarMontoPaquete(opcion);
});

// Event listener para cuando cambia el programa y se obtiene gra_ram
$(document).on('change', '#selectorProgramas', function() {
    var gra_ram = $(this).find('option:selected').attr('gra_ram');
    console.log('Modalidad del programa:', gra_ram);
    
    if (gra_ram) {
        cargarPaquetesSegunPrograma(gra_ram);
    }
});

// Inicializar componentes al cargar
$(document).ready(function() {
    $('#btnGenerarLiga').on('click', function() {
        generarLigaPago();
    });
    
    $('#btnCopiarLiga').on('click', function() {
        copiarLigaPortapapeles();
    });
});
</script>

<script>
    obtener_selector_programas_liga();

    $('#plantelLigaPago').on('change', function(){
        obtener_selector_programas_liga();
    });

    function obtener_selector_programas_liga(){
        var id_pla = $('#plantelLigaPago option:selected').val();
        var booleano_liga = true;

        $.ajax({
            url: 'server/obtener_selector_programas.php',
            type: 'POST',
            data: { id_pla, booleano_liga },
            success: function(resp) {
                $('#contenedor_programas_liga').html( resp );
            },
        });
    }
</script>