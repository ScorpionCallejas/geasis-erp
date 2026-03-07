<?php
/**
 * ============================================================================
 * ESTRUCTURA COMERCIAL - VERSIÓN CON CARGA ASÍNCRONA POR PLANTEL
 * ============================================================================
 * 
 * Cambios principales:
 * - Panel de totales generales con suma de todo
 * - Buscador poderoso con highlight de coincidencias
 * - CONTACTOS añadido con color #FFD700 (naranja claro/amarillo)
 * - Orden: PAC → CONTACTOS → CITAS → CITAS EFECTIVAS → REGISTROS
 * 
 * COLORES DE BADGES:
 * - CONTACTOS:       #FFD700 (naranja claro/amarillo) - texto negro
 * - CITAS:           #FF9800 (naranja) - texto blanco
 * - CITAS EFECTIVAS: #FFC0CB (rosa) - texto rojo #FF0000
 * - REGISTROS:       #00FFFF (cyan) - texto negro
 */

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$inicio = $_POST['inicio'];
$fin = $_POST['fin'];

// ============================================================================
// OBTENER LISTA DE PLANTELES DEL USUARIO (LIGERO)
// ============================================================================
$sqlPlanteles = "
    SELECT pe.id_pla, p.nom_pla
    FROM planteles_ejecutivo pe
    INNER JOIN plantel p ON p.id_pla = pe.id_pla
    WHERE pe.id_eje = '$id'
    ORDER BY p.nom_pla ASC
";

$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
$planteles = array();

while ($fila = mysqli_fetch_assoc($resultadoPlanteles)) {
    $planteles[] = $fila;
}

$totalPlanteles = count($planteles);

// Verificar permisos para el menú contextual
$sqlPlantelesEjecutivo = "
    SELECT COUNT(*) as total
    FROM planteles_ejecutivo
    WHERE id_eje = $id
";
$totalPlantelesEjecutivo = obtener_datos_consulta($db, $sqlPlantelesEjecutivo)['datos']['total'];
$tienePermisosMultiPlantel = ($totalPlantelesEjecutivo > 1);
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
    .badge-pac {
        background: #495057 !important;
        color: white !important;
        font-weight: bold !important;
    }
    .meta-cumplida {
        border: 2px solid #28a745 !important;
    }
    .meta-fallida {
        border: 2px solid #dc3545 !important;
    }
    .meta-pendiente {
        border: 2px solid #ffc107 !important;
    }
    .badge-meta-clickable:hover {
        opacity: 0.8;
        cursor: pointer;
    }
    
    /* ========================================================================
       PANEL DE TOTALES GENERALES
       ======================================================================== */
    .tablero-general {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .tablero-titulo {
        font-weight: bold;
        color: #495057;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tablero-kpis {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .tablero-kpis .badge {
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 4px;
    }
    
    /* ========================================================================
       BUSCADOR PODEROSO
       ======================================================================== */
    .buscador-container {
        position: relative;
        min-width: 280px;
        flex-shrink: 0;
    }
    
    #buscadorGlobal {
        width: 100%;
        padding: 8px 40px 8px 15px;
        border: 2px solid #ced4da;
        border-radius: 25px;
        font-size: 13px;
        background: #fff;
        transition: all 0.3s;
    }
    
    #buscadorGlobal:focus {
        outline: none;
        border-color: #17a2b8;
        box-shadow: 0 0 8px rgba(23, 162, 184, 0.3);
    }
    
    #buscadorGlobal::placeholder {
        color: #adb5bd;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #6c757d;
        pointer-events: none;
    }
    
    .btn-limpiar-busqueda {
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 14px;
        display: none;
        padding: 0 5px;
    }
    
    .btn-limpiar-busqueda:hover {
        color: #a71d2a;
    }
    
    .resultados-busqueda {
        font-size: 11px;
        color: #6c757d;
        margin-top: 5px;
        text-align: right;
    }
    
    /* ========================================================================
       HIGHLIGHT DE COINCIDENCIAS
       ======================================================================== */
    .highlight-match {
        background-color: #ffff00 !important;
        color: #000 !important;
        font-weight: bold;
        padding: 1px 3px;
        border-radius: 3px;
        box-shadow: 0 0 3px rgba(255, 255, 0, 0.8);
    }
    
    .card-match {
        border: 3px solid #17a2b8 !important;
        box-shadow: 0 0 15px rgba(23, 162, 184, 0.4) !important;
        animation: pulse-match 1s ease-in-out;
    }
    
    @keyframes pulse-match {
        0% { transform: scale(1); }
        50% { transform: scale(1.01); }
        100% { transform: scale(1); }
    }
    
    .card-no-match {
        opacity: 0.3;
        filter: grayscale(50%);
    }
    
    /* ========================================================================
       ESTILOS PARA EL LOADER DE PLANTEL
       ======================================================================== */
    .plantel-loader {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        min-height: 200px;
    }
    .plantel-loader .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    .plantel-tiempo {
        font-size: 10px;
        color: #6c757d;
        margin-top: 10px;
        font-family: monospace;
    }
    
    /* ========================================================================
       OVERLAY SUTIL PARA RECARGA
       ======================================================================== */
    .plantel-overlay-recarga {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 4px;
    }
    .plantel-overlay-recarga .spinner-container {
        background: rgba(255, 255, 255, 0.9);
        padding: 10px 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .plantel-overlay-recarga .spinner-border {
        width: 1.5rem;
        height: 1.5rem;
    }
    .plantel-overlay-recarga .texto-recarga {
        font-size: 12px;
        color: #495057;
    }
    
    /* Hacer la card relativa para posicionar el overlay */
    .card-body.border {
        position: relative;
    }
    
    /* ========================================================================
       CARD DE PLANTEL
       ======================================================================== */
    .plantel-card-wrapper {
        transition: all 0.3s ease;
    }
    
    .plantel-card-wrapper .card {
        transition: all 0.3s ease;
    }
</style>

<!-- MODAL DE METAS -->
<div id="modal_meta" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px 15px;">
                <h6 class="modal-title" id="titulo_modal_meta">🎯 Nueva Meta</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 10px 15px;">
                <form id="formulario_meta">
                    <input type="hidden" id="meta_id_met" name="id_met">
                    <input type="hidden" id="meta_id_eje" name="id_eje5">
                    
                    <div class="row">
                        <div class="col-6">
                            <label class="letraPequena">Rubro</label>
                            <select class="form-control form-control-sm" id="meta_rub_met" name="rub_met" required>
                                <option value="Contacto">Contacto</option>
                                <option value="Cita">Cita</option>
                                <option value="CitaEfectiva">Cita Efectiva</option>
                                <option value="Registro">Registro</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="letraPequena">Cantidad</label>
                            <input type="number" class="form-control form-control-sm" id="meta_can_met" name="can_met" min="1" value="1" required>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-12">
                            <label class="letraPequena">Fecha límite</label>
                            <input type="date" class="form-control form-control-sm" id="meta_reg_met" name="reg_met" required>
                        </div>
                    </div>
                    
                    <div id="contenedor_info_meta" class="mt-2" style="display:none;">
                        <hr style="margin: 8px 0;">
                        <div class="d-flex justify-content-between">
                            <span class="letraPequena">Progreso:</span>
                            <span id="info_progreso_meta" class="letraPequena font-weight-bold">0/0</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 8px 15px;">
                <button type="button" id="btn_eliminar_meta" class="btn btn-danger btn-sm" style="display:none;">
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_guardar_meta" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================
     PANEL DE TOTALES GENERALES + BUSCADOR PODEROSO
     ======================================================================== -->
<div class="tablero-general">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="tablero-titulo">
            📊 TABLERO GENERAL:
        </div>
        
        <div class="tablero-kpis">
            <span class="badge bg-secondary">
                🕋 T. CENTROS: <strong id="total_centros_general"><?php echo $totalPlanteles; ?></strong>
            </span>
            
            <span class="badge badge-pac">
                <strong>PAC: <span id="total_pac_general">0</span></strong>
            </span>
            
            <!-- CONTACTOS - Naranja claro (casi amarillo) #FFD700 -->
            <span class="badge" style="background-color: #FFD700; color: black;">
                CON: <strong id="total_contactos_general">0</strong>
            </span>
            
            <!-- CITAS - Naranja #FF9800 -->
            <span class="badge" style="background-color: #FF9800; color: white;">
                CIT: <strong id="total_citas_general">0</strong>
            </span>
            
            <!-- CITAS EFECTIVAS - Rosa #FFC0CB -->
            <span class="badge" style="background-color: #FFC0CB; color: #FF0000;">
                CIT EFE: <strong id="total_citas_efectivas_general">0</strong>
            </span>
            
            <!-- REGISTROS - Cyan #00FFFF -->
            <span class="badge" style="background-color: #00FFFF; color: black;">
                REG: <strong id="total_registros_general">0</strong>
            </span>
        </div>
    </div>
    
    <div class="buscador-container">
        <input type="text" id="buscadorGlobal" placeholder="🔍 Buscar plantel o ejecutivo..." autocomplete="off">
        <button type="button" class="btn-limpiar-busqueda" id="btnLimpiarBusqueda" title="Limpiar búsqueda">✕</button>
        <span class="search-icon">🔍</span>
        <div class="resultados-busqueda" id="resultadosBusqueda"></div>
    </div>
</div>

<!-- CONTENEDORES DE PLANTELES (VACÍOS CON SPINNERS) -->
<div class="row" id="contenedor_planteles">
    <?php  
        $contadorCols = 0;
        foreach ($planteles as $plantel) {
            if ($contadorCols % 2 == 0 && $contadorCols != 0) {
                echo '</div><div class="row">';
            }
    ?>
    <div class="col-md-6 plantel-card-wrapper" data-plantel-id="<?php echo $plantel['id_pla']; ?>" data-plantel-nombre="<?php echo htmlspecialchars($plantel['nom_pla'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="card">
            <div class="card-body border" id="card_plantel_<?php echo $plantel['id_pla']; ?>">
                <!-- LOADER INICIAL -->
                <div class="plantel-loader" id="loader_plantel_<?php echo $plantel['id_pla']; ?>">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <span class="mt-2 text-muted">🕋 <?php echo $plantel['nom_pla']; ?></span>
                    <span class="plantel-tiempo" id="tiempo_plantel_<?php echo $plantel['id_pla']; ?>">Cargando...</span>
                </div>
                
                <!-- CONTENIDO (SE LLENA VÍA AJAX) -->
                <div id="contenido_plantel_<?php echo $plantel['id_pla']; ?>" style="display: none;"></div>
            </div>
        </div>
    </div>
    <?php 
        $contadorCols++;
        }
    ?>
</div>

<!-- JAVASCRIPT DE CARGA ASÍNCRONA -->
<script type="text/javascript">
(function() {
    // ========================================================================
    // CONFIGURACIÓN
    // ========================================================================
    var inicio = '<?php echo $inicio; ?>';
    var fin = '<?php echo $fin; ?>';
    var planteles = <?php echo json_encode($planteles); ?>;
    var tienePermisosMultiPlantel = <?php echo $tienePermisosMultiPlantel ? 'true' : 'false'; ?>;
    var tiemposGlobales = {};
    var plantelesCompletados = 0;
    var tiempoInicioGlobal = performance.now();
    
    // ========================================================================
    // TOTALES GLOBALES PARA EL PANEL
    // ========================================================================
    var totalesGlobales = {
        pac: 0,
        contactos: 0,
        citas: 0,
        citasEfectivas: 0,
        registros: 0
    };
    
    // Almacén de datos por plantel para el buscador
    var datosPorPlantel = {};
    
    console.log('🚀 [ESTRUCTURA COMERCIAL] Iniciando carga asíncrona de ' + planteles.length + ' planteles');
    console.log('📅 Periodo: ' + inicio + ' al ' + fin);
    console.log('-------------------------------------------');
    
    // ========================================================================
    // FUNCIÓN PARA CARGAR UN PLANTEL INDIVIDUAL
    // ========================================================================
    function cargarPlantel(plantel, index) {
        var id_pla = plantel.id_pla;
        var nom_pla = plantel.nom_pla;
        var tiempoInicio = performance.now();
        
        console.log('📦 [' + (index + 1) + '/' + planteles.length + '] Cargando: ' + nom_pla + ' (ID: ' + id_pla + ')');
        
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'ObtenerPlantelIndividual',
                id_pla: id_pla,
                inicio: inicio,
                fin: fin
            },
            success: function(response) {
                var tiempoTotal = (performance.now() - tiempoInicio).toFixed(2);
                
                if (response.success) {
                    // Guardar tiempos
                    tiemposGlobales[id_pla] = {
                        nom_pla: nom_pla,
                        tiempo_cliente: parseFloat(tiempoTotal),
                        tiempos_servidor: response.tiempos,
                        debug: response.debug
                    };
                    
                    // Guardar datos para el buscador
                    datosPorPlantel[id_pla] = {
                        nom_pla: nom_pla,
                        html: response.html,
                        debug: response.debug
                    };
                    
                    // Ocultar loader y mostrar contenido
                    $('#loader_plantel_' + id_pla).hide();
                    $('#contenido_plantel_' + id_pla).html(response.html).show();
                    
                    // Mostrar tiempo en la card
                    $('#tiempo_plantel_' + id_pla).html('✅ ' + tiempoTotal + 'ms (servidor: ' + response.tiempos.total + 'ms)');
                    
                    // Inicializar jsTree para este plantel
                    inicializarJsTree(id_pla, inicio, fin);
                    
                    // ========================================================
                    // ACTUALIZAR TOTALES GLOBALES
                    // ========================================================
                    actualizarTotalesGlobales(id_pla);
                    
                    // Log de tiempos detallados
                    console.log('✅ [' + nom_pla + '] Cargado en ' + tiempoTotal + 'ms');
                    console.log('   📊 Desglose servidor:');
                    console.log('      - Query ejecutivos: ' + response.tiempos.query_ejecutivos + 'ms');
                    console.log('      - Query métricas: ' + response.tiempos.query_metricas + 'ms');
                    console.log('      - Query metas: ' + response.tiempos.query_metas + 'ms');
                    console.log('      - Query planteles asignados: ' + response.tiempos.query_planteles_asignados + 'ms');
                    console.log('      - Precarga total: ' + response.tiempos.precarga_total + 'ms');
                    console.log('      - Totales plantel: ' + response.tiempos.totales_plantel + 'ms');
                    console.log('      - Renderizado: ' + response.tiempos.renderizado + 'ms');
                    console.log('      - TOTAL SERVIDOR: ' + response.tiempos.total + 'ms');
                    console.log('   👥 Ejecutivos: ' + response.debug.total_ejecutivos);
                    
                } else {
                    console.error('❌ [' + nom_pla + '] Error:', response.error);
                    $('#loader_plantel_' + id_pla).html('<span class="text-danger">Error al cargar</span>');
                }
                
                // Incrementar contador y verificar si terminamos
                plantelesCompletados++;
                verificarCargaCompleta();
            },
            error: function(xhr, status, error) {
                var tiempoTotal = (performance.now() - tiempoInicio).toFixed(2);
                console.error('❌ [' + nom_pla + '] Error AJAX en ' + tiempoTotal + 'ms:', error);
                $('#loader_plantel_' + id_pla).html('<span class="text-danger">Error de conexión</span>');
                
                plantelesCompletados++;
                verificarCargaCompleta();
            }
        });
    }
    
    // ========================================================================
    // FUNCIÓN PARA ACTUALIZAR TOTALES GLOBALES
    // ========================================================================
    function actualizarTotalesGlobales(id_pla) {
        // Resetear totales
        totalesGlobales = {
            pac: 0,
            contactos: 0,
            citas: 0,
            citasEfectivas: 0,
            registros: 0
        };
        
        // Recorrer todas las cards y extraer datos de los badges
        $('.plantel-card-wrapper').each(function() {
            var $card = $(this);
            var $contenido = $card.find('[id^="contenido_plantel_"]');
            
            if ($contenido.is(':visible') && $contenido.html().trim() !== '') {
                // Extraer PAC
                var pacText = $contenido.find('.badge-pac strong').text();
                var pacMatch = pacText.match(/PAC:\s*(\d+)/);
                if (pacMatch) {
                    totalesGlobales.pac += parseInt(pacMatch[1]) || 0;
                }
                
                // Extraer CONTACTOS (badge amarillo #FFD700)
                $contenido.find('.badge').each(function() {
                    var $badge = $(this);
                    var bgColor = $badge.css('background-color');
                    var text = $badge.text();
                    
                    // Convertir RGB a HEX para comparar
                    if (bgColor === 'rgb(255, 215, 0)' || $badge.attr('style')?.includes('#FFD700')) {
                        var conMatch = text.match(/CON:\s*(?:✅|🎯)?(\d+)/);
                        if (conMatch) {
                            totalesGlobales.contactos += parseInt(conMatch[1]) || 0;
                        }
                    }
                    // CITAS (naranja #FF9800)
                    else if (bgColor === 'rgb(255, 152, 0)' || $badge.attr('style')?.includes('#FF9800')) {
                        var citMatch = text.match(/CIT:\s*(?:✅|🎯)?(\d+)/);
                        if (citMatch) {
                            totalesGlobales.citas += parseInt(citMatch[1]) || 0;
                        }
                    }
                    // CITAS EFECTIVAS (rosa #FFC0CB)
                    else if (bgColor === 'rgb(255, 192, 203)' || $badge.attr('style')?.includes('#FFC0CB')) {
                        var citEfeMatch = text.match(/CIT EFE:\s*(?:✅|🎯)?(\d+)/);
                        if (citEfeMatch) {
                            totalesGlobales.citasEfectivas += parseInt(citEfeMatch[1]) || 0;
                        }
                    }
                    // REGISTROS (cyan #00FFFF)
                    else if (bgColor === 'rgb(0, 255, 255)' || $badge.attr('style')?.includes('#00FFFF')) {
                        var regMatch = text.match(/REG:\s*(?:✅|🎯)?(\d+)/);
                        if (regMatch) {
                            totalesGlobales.registros += parseInt(regMatch[1]) || 0;
                        }
                    }
                });
            }
        });
        
        // Actualizar el panel
        $('#total_pac_general').text(totalesGlobales.pac);
        $('#total_contactos_general').text(totalesGlobales.contactos);
        $('#total_citas_general').text(totalesGlobales.citas);
        $('#total_citas_efectivas_general').text(totalesGlobales.citasEfectivas);
        $('#total_registros_general').text(totalesGlobales.registros);
    }
    
    // ========================================================================
    // VERIFICAR SI LA CARGA ESTÁ COMPLETA
    // ========================================================================
    function verificarCargaCompleta() {
        var tiempoTotalGlobal = (performance.now() - tiempoInicioGlobal).toFixed(2);
        
        if (plantelesCompletados === planteles.length) {
            console.log('-------------------------------------------');
            console.log('🏁 [COMPLETADO] Todos los planteles cargados');
            console.log('⏱️ Tiempo total de carga (paralelo): ' + tiempoTotalGlobal + 'ms');
            
            // Actualizar totales finales
            actualizarTotalesGlobales(null);
        }
    }
    
    // ========================================================================
    // FUNCIÓN PARA INICIALIZAR jsTree
    // ========================================================================
    function inicializarJsTree(id_pla, inicio, fin) {
        $("#dragTree" + id_pla).jstree({
            dnd: {
                is_draggable: function(node) {
                    return true;
                }
            },
            core: {
                check_callback: true,
                themes: {
                    responsive: false
                }
            },
            types: {
                default: {
                    icon: "fas fa-user"
                }
            },
            plugins: ["types", "dnd", "contextmenu"],
            contextmenu: {
                items: function(node) {
                    var menuItems = {
                        "editItem": {
                            "label": "Consultar",
                            "action": function(obj) {
                                obtenerDatosNodo(node);
                            }
                        },
                        "deleteItem": {
                            "label": "Eliminar",
                            "action": function(obj) {
                                eliminarNodoConValidacion(node);
                            }
                        },
                        "SwitchItem": {
                            "label": "Activa/Desactiva",
                            "action": function(obj) {
                                switchearNodo(node);
                            }
                        },
                        "permisosItem": {
                            "label": "Otorgar/Quitar permisos CDE",
                            "action": function(obj) {
                                permisosNodo(node);
                            }
                        },
                        "metasItem": {
                            "label": "🎯 Gestionar Metas",
                            "action": function(obj) {
                                abrirModalMeta(node.id, node.li_attr['data-ejecutivo-nombre'], null, node.li_attr.id_pla);
                            }
                        },
                        "reportItemRegistrosConsultor": {
                            "label": "Consultar registros",
                            "action": function(obj) {
                                var url = 'registros.php?id_pla='+node.li_attr.id_pla+'&escala=ejecutivo&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                window.open(url, '_blank');
                            }
                        },
                        "reportItemCitasConsultor": {
                            "label": "Consultar citas",
                            "action": function(obj) {
                                var url = 'citas.php?id_pla='+node.li_attr.id_pla+'&escala=ejecutivo&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                window.open(url, '_blank');
                            }
                        },
                        "reportItemContactosConsultor": {
                            "label": "Consultar contactos",
                            "action": function(obj) {
                                var url = 'referidos.php?id_pla='+node.li_attr.id_pla+'&escala=ejecutivo&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                window.open(url, '_blank');
                            }
                        },
                        "reportItemRegistros": {
                            "label": "Consultar registros por equipo",
                            "action": function(obj) {
                                var url = 'registros.php?escala=estructura&id_eje=' + node.id + '&inicio=' + inicio + '&fin=' + fin;
                                window.open(url, '_blank');
                            }
                        }
                    };
                    
                    // Agregar opción de permisos AHJ ENDE solo si tiene múltiples planteles
                    if (tienePermisosMultiPlantel) {
                        menuItems["permisosItemMarca"] = {
                            "label": "Otorgar/Quitar permisos AHJ ENDE",
                            "action": function(obj) {
                                permisosNodoMarca(node);
                            }
                        };
                    }
                    
                    return menuItems;
                }
            }
        }).on('move_node.jstree', function(e, data) {
            var idHijo = data.node.id;
            var idPadre = data.parent;
            if (idPadre == '#') { idPadre = 0; }
            $.ajax({
                url: 'server/controlador_estructuras_comerciales.php',
                type: 'POST',
                data: { idHijo: idHijo, idPadre: idPadre, accion: 'Cambio' },
                success: function(response) {
                    toastr.success('Cambios guardados :D');
                }
            });
        });
    }
    
    // ========================================================================
    // FUNCIÓN PARA RECARGAR UN SOLO PLANTEL (CON OVERLAY SUTIL)
    // ========================================================================
    window.recargarPlantel = function(id_pla) {
        var plantelData = planteles.find(function(p) { return p.id_pla == id_pla; });
        if (!plantelData) {
            console.error('Plantel no encontrado:', id_pla);
            return;
        }
        
        var nom_pla = plantelData.nom_pla;
        console.log('🔄 Recargando plantel: ' + nom_pla + ' (ID: ' + id_pla + ')');
        
        // Agregar overlay sutil SIN tocar el árbol todavía
        var $card = $('#card_plantel_' + id_pla);
        $card.find('.plantel-overlay-recarga').remove();
        $card.append(`
            <div class="plantel-overlay-recarga">
                <div class="spinner-container">
                    <div class="spinner-border text-primary" role="status"></div>
                    <span class="texto-recarga">Actualizando...</span>
                </div>
            </div>
        `);
        
        var tiempoInicio = performance.now();
        
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'ObtenerPlantelIndividual',
                id_pla: id_pla,
                inicio: inicio,
                fin: fin
            },
            success: function(response) {
                var tiempoTotal = (performance.now() - tiempoInicio).toFixed(2);
                
                // Quitar overlay
                $card.find('.plantel-overlay-recarga').remove();
                
                if (response.success) {
                    // Destruir el jsTree viejo
                    if ($('#dragTree' + id_pla).jstree(true)) {
                        $('#dragTree' + id_pla).jstree('destroy');
                    }
                    
                    // Actualizar contenido
                    $('#contenido_plantel_' + id_pla).html(response.html);
                    
                    // Re-inicializar jsTree
                    inicializarJsTree(id_pla, inicio, fin);
                    
                    // Actualizar datos para el buscador
                    datosPorPlantel[id_pla] = {
                        nom_pla: nom_pla,
                        html: response.html,
                        debug: response.debug
                    };
                    
                    // Actualizar totales globales
                    actualizarTotalesGlobales(id_pla);
                    
                    console.log('✅ [' + nom_pla + '] Recargado en ' + tiempoTotal + 'ms');
                    toastr.success('Actualizado');
                } else {
                    console.error('❌ Error recargando plantel:', response.error);
                    toastr.error('Error al recargar plantel');
                }
            },
            error: function(xhr, status, error) {
                $card.find('.plantel-overlay-recarga').remove();
                console.error('❌ Error AJAX recargando plantel:', error);
                toastr.error('Error de conexión');
            }
        });
    };
    
    // ========================================================================
    // FUNCIONES GLOBALES PARA EL CONTEXT MENU
    // ========================================================================
    
    window.permisosNodoMarca = function(node) {
        var id_eje = node.id;
        var id_pla = node.li_attr.id_pla;
        var per_eje = node.li_attr.per_eje;
        per_eje = (per_eje == 0 || per_eje == 1) ? 2 : 0;
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: { id_eje: id_eje, accion: 'Permisos', per_eje: per_eje },
            success: function(datos) {
                toastr.success('Cambios guardados :D');
                recargarPlantel(id_pla);
            }
        });
    };

    window.permisosNodo = function(node) {
        var id_eje = node.id;
        var id_pla = node.li_attr.id_pla;
        var per_eje = node.li_attr.per_eje;
        per_eje = (per_eje == 1) ? 0 : 1;
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: { id_eje: id_eje, accion: 'Permisos', per_eje: per_eje },
            success: function(datos) {
                toastr.success('Cambios guardados :D');
                recargarPlantel(id_pla);
            }
        });
    };

    window.switchearNodo = function(node) {
        var id_eje = node.id;
        var id_pla = node.li_attr.id_pla;
        var est_eje = (node.li_attr.est_eje == 'Activo') ? 'Inactivo' : 'Activo';
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: { id_eje: id_eje, accion: 'Switch', est_eje: est_eje },
            success: function(datos) {
                toastr.success('Cambios guardados :D');
                recargarPlantel(id_pla);
            }
        });
    };

    window.obtenerDatosNodo = function(node) {
        var id_eje = node.id;
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: { id_eje: id_eje, accion: 'Despliegue' },
            success: function(datos) {
                $('#modal_agregar_asesor').modal('show');
                $('#nom_eje').val(datos.nom_eje);
                $('#ran_eje').val(datos.ran_eje);
                $('#id_pla').val(datos.id_pla);
                $('#tel_eje').val(datos.tel_eje);
                $('#cor_eje').val(datos.cor_eje);
                $('#pas_eje').val(datos.pas_eje);
                $('#obs_eje').val(datos.obs_eje);
                $('#id_eje').val(id_eje);
                $('#id_pla option[value="' + datos.id_pla + '"]').prop('selected', true);
                
                // Cargar checkboxes de planteles si existe el contenedor
                if ($('#contenedor_checkboxes_planteles').length > 0) {
                    $.ajax({
                        url: 'server/controlador_estructuras_comerciales.php',
                        type: 'POST',
                        dataType: 'json',
                        data: { accion: 'ObtenerPlantelesEjecutivo', id_eje: id_eje },
                        success: function(response) {
                            if(response.success) {
                                var html = '';
                                response.planteles.forEach(function(plantel) {
                                    var checked = plantel.asignado ? 'checked' : '';
                                    html += '<div class="form-check" style="margin-bottom: 8px;"><input class="form-check-input" type="checkbox" value="' + plantel.id_pla + '" id="chk_pla_' + plantel.id_pla + '" ' + checked + '><label class="form-check-label letraPequena" for="chk_pla_' + plantel.id_pla + '">🕋 ' + plantel.nom_pla + '</label></div>';
                                });
                                $('#contenedor_checkboxes_planteles').html(html);
                            }
                        }
                    });
                }
                
                $('#formulario_agregar_asesor').removeAttr('estatus').attr('estatus', 'Cambio');
            }
        });
    };

    window.eliminarNodoConValidacion = function(node) {
        var id_pla = node.li_attr.id_pla;
        swal({
            title: "¡Acceso Restringido!",
            icon: "warning",
            text: 'Necesitas permisos para continuar',
            content: { element: "input", attributes: { placeholder: "Ingresa tu contraseña...", type: "password" } },
            button: { text: "Validar", closeModal: false }
        }).then(function(password) {
            if (!password) { swal.stopLoading(); swal.close(); throw null; }
            return $.ajax({ url: 'server/validacion_permisos.php', type: 'POST', data: { password: password } });
        }).then(function(response) {
            if (response !== 'True') { swal.stopLoading(); swal.close(); throw new Error('Contraseña incorrecta.'); }
            return swal({ title: "¿Deseas eliminar este registro?", text: "¡Valida para continuar!", icon: "warning", buttons: { cancel: { text: "Cancelar", value: null, visible: true, closeModal: true }, confirm: { text: "Confirmar", value: true, visible: true, closeModal: true } }, dangerMode: true });
        }).then(function(willDelete) {
            if (!willDelete) { swal.stopLoading(); swal.close(); throw null; }
            return $.ajax({ url: 'server/controlador_estructuras_comerciales.php', type: 'POST', data: { id_eje: node.id, accion: 'Baja' } });
        }).then(function(response) {
            recargarPlantel(id_pla);
        }).catch(function(error) {
            if (error) { swal("Error", "No se pudo eliminar el registro", "error"); }
        });
    };
    
    // ========================================================================
    // DISPARAR CARGA DE TODOS LOS PLANTELES EN PARALELO
    // ========================================================================
    planteles.forEach(function(plantel, index) {
        // Pequeño delay escalonado para no saturar el servidor
        setTimeout(function() {
            cargarPlantel(plantel, index);
        }, index * 50); // 50ms de separación entre cada llamada
    });
    
    // ========================================================================
    // BUSCADOR GLOBAL POTENTE (VANILLA JS - ESTILO obtener_zonas.php)
    // ========================================================================
    var searchTimeout;
    var buscadorInput = document.getElementById('buscadorGlobal');
    var btnLimpiar = document.getElementById('btnLimpiarBusqueda');
    var resultadosDiv = document.getElementById('resultadosBusqueda');
    
    if (buscadorInput) {
        buscadorInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            
            if (btnLimpiar) {
                if (e.target.value.trim() !== '') {
                    btnLimpiar.style.display = 'block';
                } else {
                    btnLimpiar.style.display = 'none';
                }
            }
            
            searchTimeout = setTimeout(function() {
                var searchTerm = e.target.value.toLowerCase().trim();
                
                // Limpiar highlights anteriores
                document.querySelectorAll('.highlight-match').forEach(function(el) {
                    if (el.parentNode) {
                        el.outerHTML = el.textContent;
                    }
                });
                
                // Quitar clases de match
                document.querySelectorAll('.plantel-card-wrapper').forEach(function(wrapper) {
                    wrapper.classList.remove('card-no-match');
                    var card = wrapper.querySelector('.card');
                    if (card) card.classList.remove('card-match');
                });
                
                if (searchTerm === '') {
                    // Mostrar todo
                    document.querySelectorAll('.plantel-card-wrapper').forEach(function(wrapper) {
                        wrapper.style.display = '';
                    });
                    // Mostrar todos los li
                    document.querySelectorAll('[id^="dragTree"] li').forEach(function(li) {
                        li.style.display = '';
                    });
                    if (resultadosDiv) resultadosDiv.innerHTML = '';
                    return;
                }
                
                var totalCoincidencias = 0;
                var plantelesConCoincidencias = 0;
                
                // Buscar en planteles y ejecutivos
                document.querySelectorAll('.plantel-card-wrapper').forEach(function(wrapper) {
                    var plantelHasMatch = false;
                    var coincidenciasEnPlantel = 0;
                    var plantelNombre = (wrapper.getAttribute('data-plantel-nombre') || '').toLowerCase();
                    var idPla = wrapper.getAttribute('data-plantel-id');
                    
                    // Primero mostrar todos los li de este plantel
                    var treeContainer = document.getElementById('dragTree' + idPla);
                    if (treeContainer) {
                        treeContainer.querySelectorAll('li').forEach(function(li) {
                            li.style.display = '';
                        });
                    }
                    
                    // 1. Buscar en nombre del plantel
                    if (plantelNombre.includes(searchTerm)) {
                        plantelHasMatch = true;
                        coincidenciasEnPlantel++;
                    }
                    
                    // 2. Buscar en ejecutivos del plantel
                    if (treeContainer) {
                        var listaCoincidentes = [];
                        
                        treeContainer.querySelectorAll('li[data-ejecutivo-nombre]').forEach(function(li) {
                            var ejecutivoNombre = (li.getAttribute('data-ejecutivo-nombre') || '').toLowerCase();
                            
                            if (ejecutivoNombre.includes(searchTerm)) {
                                listaCoincidentes.push(li);
                                
                                // Highlight en el span del nombre
                                var nombreSpan = li.querySelector('span[title], span.ejecutivo-nombre');
                                if (nombreSpan) {
                                    var text = nombreSpan.textContent;
                                    var regex = new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi');
                                    var highlighted = text.replace(regex, '<span class="highlight-match">$1</span>');
                                    nombreSpan.innerHTML = highlighted;
                                }
                                
                                plantelHasMatch = true;
                                coincidenciasEnPlantel++;
                            }
                        });
                        
                        // Expandir nodos coincidentes en jsTree
                        if (listaCoincidentes.length > 0 && typeof jQuery !== 'undefined') {
                            var jstreeId = 'dragTree' + idPla;
                            var $tree = jQuery('#' + jstreeId);
                            if ($tree.length && $tree.jstree && $tree.jstree(true)) {
                                var jstreeInstance = $tree.jstree(true);
                                listaCoincidentes.forEach(function(li) {
                                    var nodeId = li.id;
                                    if (nodeId) {
                                        jstreeInstance.open_node(nodeId);
                                        var nodeData = jstreeInstance.get_node(nodeId);
                                        if (nodeData && nodeData.parents) {
                                            nodeData.parents.forEach(function(parentId) {
                                                if (parentId !== '#') {
                                                    jstreeInstance.open_node(parentId);
                                                }
                                            });
                                        }
                                    }
                                });
                            }
                        }
                    }
                    
                    // Aplicar estilos según coincidencia
                    var card = wrapper.querySelector('.card');
                    if (plantelHasMatch) {
                        wrapper.style.display = '';
                        wrapper.classList.remove('card-no-match');
                        if (card) card.classList.add('card-match');
                        plantelesConCoincidencias++;
                        totalCoincidencias += coincidenciasEnPlantel;
                    } else {
                        wrapper.classList.add('card-no-match');
                        if (card) card.classList.remove('card-match');
                    }
                });
                
                // Mostrar resultados
                if (resultadosDiv) {
                    if (totalCoincidencias > 0) {
                        resultadosDiv.innerHTML = '<span class="text-success">✅ ' + totalCoincidencias + ' coincidencia(s) en ' + plantelesConCoincidencias + ' plantel(es)</span>';
                    } else {
                        resultadosDiv.innerHTML = '<span class="text-warning">⚠️ Sin coincidencias para "' + escapeHtml(searchTerm) + '"</span>';
                    }
                }
                
            }, 300);
        });
    }
    
    // Botón limpiar búsqueda
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (buscadorInput) {
                buscadorInput.value = '';
                buscadorInput.dispatchEvent(new Event('input'));
            }
            this.style.display = 'none';
        });
    }
    
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
    
})();

// ========================================================================
// GESTIÓN DE METAS (GLOBAL)
// ========================================================================
var metaActual = { id_eje: null, id_met: null, nombre_eje: '', id_pla: null };

function abrirModalMeta(id_eje, nombre_eje, id_met, id_pla) {
    metaActual.id_eje = id_eje;
    metaActual.nombre_eje = nombre_eje || '';
    metaActual.id_met = id_met || null;
    metaActual.id_pla = id_pla || null;
    
    $('#formulario_meta')[0].reset();
    $('#meta_id_eje').val(id_eje);
    $('#meta_id_met').val('');
    $('#btn_eliminar_meta').hide();
    $('#contenedor_info_meta').hide();
    $('#titulo_modal_meta').html('🎯 Nueva Meta - ' + metaActual.nombre_eje);
    $('#meta_reg_met').val('<?php echo $fin; ?>');
    
    if(id_met) {
        $.ajax({
            url: 'server/controlador_estructuras_comerciales.php',
            type: 'POST',
            dataType: 'json',
            data: { accion: 'ObtenerMeta', id_met: id_met },
            success: function(response) {
                if(response.success) {
                    var meta = response.meta;
                    $('#meta_id_met').val(meta.id_met);
                    $('#meta_rub_met').val(meta.rub_met);
                    $('#meta_can_met').val(meta.can_met);
                    $('#meta_reg_met').val(meta.reg_met.substring(0, 10));
                    $('#titulo_modal_meta').html('🎯 Editar Meta - ' + metaActual.nombre_eje);
                    $('#btn_eliminar_meta').show();
                    $('#info_progreso_meta').html(response.logrado + '/' + meta.can_met);
                    $('#contenedor_info_meta').show();
                }
            }
        });
    }
    $('#modal_meta').modal('show');
}

$(document).on('click', '.badge-meta-clickable', function(e) {
    e.stopPropagation();
    var id_eje = $(this).data('id-eje');
    var id_met = $(this).data('id-met');
    var $li = $(this).closest('li');
    var nombre = $li.attr('data-ejecutivo-nombre') || '';
    var id_pla = $li.attr('id_pla') || null;
    abrirModalMeta(id_eje, nombre, id_met, id_pla);
});

$('#btn_guardar_meta').on('click', function() {
    var id_met = $('#meta_id_met').val();
    var accion = id_met ? 'ActualizarMeta' : 'CrearMeta';
    $.ajax({
        url: 'server/controlador_estructuras_comerciales.php',
        type: 'POST',
        dataType: 'json',
        data: { 
            accion: accion, 
            id_met: id_met, 
            id_eje5: $('#meta_id_eje').val(), 
            rub_met: $('#meta_rub_met').val(), 
            can_met: $('#meta_can_met').val(), 
            reg_met: $('#meta_reg_met').val() 
        },
        success: function(response) {
            if(response.success) {
                toastr.success('Meta guardada correctamente');
                $('#modal_meta').modal('hide');
                if (metaActual.id_pla) {
                    recargarPlantel(metaActual.id_pla);
                }
            } else {
                toastr.error(response.error || 'Error al guardar la meta');
            }
        },
        error: function() { toastr.error('Error de conexión'); }
    });
});

$('#btn_eliminar_meta').on('click', function() {
    var id_met = $('#meta_id_met').val();
    if(!id_met) return;
    swal({ 
        title: "¿Eliminar esta meta?", 
        icon: "warning", 
        buttons: ["Cancelar", "Eliminar"], 
        dangerMode: true 
    }).then(function(willDelete) {
        if(willDelete) {
            $.ajax({
                url: 'server/controlador_estructuras_comerciales.php',
                type: 'POST',
                dataType: 'json',
                data: { accion: 'EliminarMeta', id_met: id_met },
                success: function(response) {
                    if(response.success) {
                        toastr.success('Meta eliminada');
                        $('#modal_meta').modal('hide');
                        if (metaActual.id_pla) {
                            recargarPlantel(metaActual.id_pla);
                        }
                    } else {
                        toastr.error(response.error || 'Error al eliminar');
                    }
                }
            });
        }
    });
});
</script>