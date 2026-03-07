<?php
// ============================================================================
// INCLUSIÓN DEL HEADER DEL SISTEMA
// ============================================================================
include('inc/header.php');
?>

<!-- ============================================================================ -->
<!-- BREADCRUMB Y TÍTULO -->
<!-- ============================================================================ -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item letraPequena"><a href="home.php">HOME</a></li>
                    <li class="breadcrumb-item active letraPequena">TEMPLATE LAYOUT</li>
                </ol>
            </div>
            <h4 class="page-title letraPequena">TEMPLATE LAYOUT BASE</h4>
        </div>
    </div>
</div>

<!-- ============================================================================ -->
<!-- LAYOUT PRINCIPAL -->
<!-- ============================================================================ -->
<div class="layout-system">
    
    <!-- BOTÓN TOGGLE -->
    <button class="layout-toggle" id="layoutToggle" onclick="toggleLayout()" title="ALTERNAR PANEL">
        <i class="fas fa-chevron-left" id="toggleIcon"></i>
    </button>

    <!-- PANEL LATERAL CON FILTROS -->
    <div class="lateral-panel" id="lateralPanel">
        <div class="panel-content">
            
            <!-- FILTRO 1 -->
            <div class="filter-group" id="filter-uno">
                <div class="filter-header" onclick="toggleFilterGroup('uno')">
                    <div>
                        <i class="fas fa-circle filter-header-icon"></i>
                        FILTRO 1
                    </div>
                    <i class="fas fa-chevron-down filter-collapse-icon" id="icon-uno"></i>
                </div>
                <div class="filter-items" id="items-uno">
                    <label class="filter-item">
                        <input type="checkbox" value="opcion1">
                        <span>OPCIÓN 1</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="opcion2">
                        <span>OPCIÓN 2</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="opcion3">
                        <span>OPCIÓN 3</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="opcion4">
                        <span>OPCIÓN 4</span>
                    </label>
                </div>
                <div class="filter-controls" id="controls-uno">
                    <button type="button" class="filter-btn">TODOS</button>
                    <button type="button" class="filter-btn">LIMPIAR</button>
                </div>
            </div>

            <!-- FILTRO 2 -->
            <div class="filter-group" id="filter-dos">
                <div class="filter-header" onclick="toggleFilterGroup('dos')">
                    <div>
                        <i class="fas fa-tags filter-header-icon"></i>
                        FILTRO 2
                    </div>
                    <i class="fas fa-chevron-down filter-collapse-icon" id="icon-dos"></i>
                </div>
                <div class="filter-items" id="items-dos">
                    <label class="filter-item">
                        <input type="radio" name="filtro_dos" value="todos" checked>
                        <span>TODOS</span>
                    </label>
                    <label class="filter-item">
                        <input type="radio" name="filtro_dos" value="tipo_a">
                        <span>TIPO A</span>
                    </label>
                    <label class="filter-item">
                        <input type="radio" name="filtro_dos" value="tipo_b">
                        <span>TIPO B</span>
                    </label>
                    <label class="filter-item">
                        <input type="radio" name="filtro_dos" value="tipo_c">
                        <span>TIPO C</span>
                    </label>
                </div>
                <div class="filter-controls" id="controls-dos">
                    <button type="button" class="filter-btn">RESET</button>
                </div>
            </div>

            <!-- FILTRO 3 -->
            <div class="filter-group" id="filter-tres">
                <div class="filter-header" onclick="toggleFilterGroup('tres')">
                    <div>
                        <i class="fas fa-folder filter-header-icon"></i>
                        FILTRO 3
                    </div>
                    <i class="fas fa-chevron-down filter-collapse-icon" id="icon-tres"></i>
                </div>
                <div class="filter-items" id="items-tres">
                    <label class="filter-item">
                        <input type="checkbox" value="cat_a">
                        <span>CATEGORÍA A</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="cat_b">
                        <span>CATEGORÍA B</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="cat_c">
                        <span>CATEGORÍA C</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="cat_d">
                        <span>CATEGORÍA D</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="cat_e">
                        <span>CATEGORÍA E</span>
                    </label>
                </div>
                <div class="filter-controls" id="controls-tres">
                    <button type="button" class="filter-btn">TODOS</button>
                    <button type="button" class="filter-btn">LIMPIAR</button>
                </div>
            </div>

            <!-- FILTRO 4 -->
            <div class="filter-group" id="filter-cuatro">
                <div class="filter-header" onclick="toggleFilterGroup('cuatro')">
                    <div>
                        <i class="fas fa-star filter-header-icon"></i>
                        FILTRO 4
                    </div>
                    <i class="fas fa-chevron-down filter-collapse-icon" id="icon-cuatro"></i>
                </div>
                <div class="filter-items" id="items-cuatro">
                    <label class="filter-item">
                        <input type="checkbox" value="nivel_1">
                        <span>NIVEL 1</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="nivel_2">
                        <span>NIVEL 2</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="nivel_3">
                        <span>NIVEL 3</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="nivel_4">
                        <span>NIVEL 4</span>
                    </label>
                </div>
                <div class="filter-controls" id="controls-cuatro">
                    <button type="button" class="filter-btn">TODOS</button>
                    <button type="button" class="filter-btn">LIMPIAR</button>
                </div>
            </div>

            <!-- FILTRO 5 -->
            <div class="filter-group" id="filter-cinco">
                <div class="filter-header" onclick="toggleFilterGroup('cinco')">
                    <div>
                        <i class="fas fa-cog filter-header-icon"></i>
                        FILTRO 5
                    </div>
                    <i class="fas fa-chevron-down filter-collapse-icon" id="icon-cinco"></i>
                </div>
                <div class="filter-items" id="items-cinco">
                    <label class="filter-item">
                        <input type="checkbox" value="grupo_x">
                        <span>GRUPO X</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="grupo_y">
                        <span>GRUPO Y</span>
                    </label>
                    <label class="filter-item">
                        <input type="checkbox" value="grupo_z">
                        <span>GRUPO Z</span>
                    </label>
                </div>
                <div class="filter-controls" id="controls-cinco">
                    <button type="button" class="filter-btn">TODOS</button>
                    <button type="button" class="filter-btn">LIMPIAR</button>
                </div>
            </div>

        </div>
    </div>

    <!-- ÁREA PRINCIPAL -->
    <div class="main-area">
        <div class="data-container bg-white">
            
            <!-- TOOLBAR SUPERIOR -->
            <div style="background-color: #E8E8E8; padding: 8px; margin: 0;">
                <div class="row">
                    
                    <!-- BOTONES DE ACCIÓN -->
                    <div class="col-md-4">
                        <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px;">
                            <button class="toolbar-btn">
                                <i class="fas fa-plus"></i> NUEVO
                            </button>
                            <button class="toolbar-btn">
                                <i class="fas fa-edit"></i> EDITAR
                            </button>
                            <button class="toolbar-btn">
                                <i class="fas fa-trash"></i> ELIMINAR
                            </button>
                            <button class="toolbar-btn success">
                                <i class="fas fa-file-excel"></i> EXCEL
                            </button>
                        </div>
                        
                        <!-- CONTADOR -->
                        <div style="font-size: 9px; color: #666; font-weight: 600; text-transform: uppercase;">
                            <span id="total_registros" style="font-weight: 800; color: #333; font-size: 10px;">100 </span> FILAS
                        </div>
                    </div>
                    
                    <!-- FILTROS DE FECHAS -->
                    <div class="col-md-4">
                        <?php include('modulos/filtros_fechas.php') ?>
                    </div>
                    
                    <!-- ESPACIO LIBRE -->
                    <div class="col-md-4">
                        <!-- Para futuros controles -->
                    </div>
                    
                </div>
            </div>
            
            <!-- TABLA DE DATOS -->
            <div id="data-sheet" class="data-grid" style="margin: 0 !important; padding: 0 !important;"></div>
            
        </div>
    </div>
</div>

<?php
// ============================================================================
// INCLUSIÓN DEL FOOTER DEL SISTEMA
// ============================================================================
include('inc/footer.php');
?>

<script>
// ============================================================================
// CONFIGURACIÓN DEL MÓDULO
// ============================================================================
var MODULE_PREFIX = 'template_';  // ⚠️ CAMBIAR EN CADA MÓDULO

// ============================================================================
// CONFIGURACIÓN DINÁMICA DE DATOS
// ============================================================================
const DATA_CONFIG = {
    TOTAL_REGISTROS: 200,
    OPCIONES: ['OPCIÓN 1', 'OPCIÓN 2', 'OPCIÓN 3', 'OPCIÓN 4'],
    TIPOS: ['TIPO A', 'TIPO B', 'TIPO C'],
    CATEGORIAS: ['CAT A', 'CAT B', 'CAT C', 'CAT D', 'CAT E'],
    NIVELES: ['NIVEL 1', 'NIVEL 2', 'NIVEL 3', 'NIVEL 4'],
    GRUPOS: ['GRUPO X', 'GRUPO Y', 'GRUPO Z'],
    ESTADOS: ['ACTIVO', 'PENDIENTE', 'INACTIVO', 'COMPLETADO', 'SUSPENDIDO'],
    VALOR_MIN: 500,
    VALOR_MAX: 10000,
    FECHA_BASE: new Date(2024, 7, 15),
    DIAS_RANGO: 365,
    NOMBRES_BASE: [
        'JUAN PÉREZ', 'MARÍA GARCÍA', 'CARLOS LÓPEZ', 'ANA MARTÍNEZ', 
        'LUIS RODRÍGUEZ', 'LAURA HERNÁNDEZ', 'DIEGO GONZÁLEZ', 'SOFIA RUIZ',
        'MIGUEL TORRES', 'ELENA FLORES', 'PABLO MORALES', 'CARMEN JIMÉNEZ',
        'FERNANDO CASTRO', 'PATRICIA ORTEGA', 'ANTONIO RAMOS', 'ISABEL VARGAS',
        'ROBERTO HERRERA', 'MONICA CRUZ', 'JAVIER MÉNDEZ', 'ADRIANA SILVA'
    ]
};

// Headers de la tabla
var colHeaders = [
    "ID", "CÓDIGO", "NOMBRE", "CAMPO 1", "CAMPO 2", "CAMPO 3", 
    "CAMPO 4", "CAMPO 5", "CAMPO 6", "CAMPO 7", "FECHA 1", 
    "FECHA 2", "VALOR", "ESTADO", "ACCIONES"
];

// Variables globales
var container = document.querySelector('#data-sheet');
var hot;
var datosDemo;

// ============================================================================
// GENERADOR DE DATOS
// ============================================================================
function generarDatosDinamicos() {
    console.log(`🔄 GENERANDO ${DATA_CONFIG.TOTAL_REGISTROS} REGISTROS...`);
    
    const datos = [];
    
    for (let i = 1; i <= DATA_CONFIG.TOTAL_REGISTROS; i++) {
        const id = String(i).padStart(3, '0');
        const opcion = DATA_CONFIG.OPCIONES[i % DATA_CONFIG.OPCIONES.length];
        const tipo = DATA_CONFIG.TIPOS[i % DATA_CONFIG.TIPOS.length];
        const categoria = DATA_CONFIG.CATEGORIAS[i % DATA_CONFIG.CATEGORIAS.length];
        const nivel = DATA_CONFIG.NIVELES[i % DATA_CONFIG.NIVELES.length];
        const grupo = DATA_CONFIG.GRUPOS[i % DATA_CONFIG.GRUPOS.length];
        const estado = DATA_CONFIG.ESTADOS[i % DATA_CONFIG.ESTADOS.length];
        
        let nombre;
        if (i <= DATA_CONFIG.NOMBRES_BASE.length) {
            nombre = DATA_CONFIG.NOMBRES_BASE[i - 1];
        } else {
            nombre = `REGISTRO ${id}`;
        }
        
        const fechaBase = new Date(DATA_CONFIG.FECHA_BASE);
        const diasAleatorios = Math.floor(Math.random() * DATA_CONFIG.DIAS_RANGO);
        const fecha1 = new Date(fechaBase.getTime() - (diasAleatorios * 24 * 60 * 60 * 1000));
        const fecha2 = new Date(fechaBase.getTime() + (diasAleatorios * 24 * 60 * 60 * 1000));
        
        const valor = (Math.random() * (DATA_CONFIG.VALOR_MAX - DATA_CONFIG.VALOR_MIN) + DATA_CONFIG.VALOR_MIN).toFixed(2);
        
        datos.push([
            id,
            `COD${id}`,
            nombre,
            `DATO A${i}`,
            `DATO B${i}`,
            `DATO C${i}`,
            opcion,
            tipo,
            categoria,
            nivel,
            formatearFecha(fecha1),
            formatearFecha(fecha2),
            `$${valor}`,
            estado,
            'VER'
        ]);
    }
    
    console.log(`✅ ${datos.length} REGISTROS GENERADOS`);
    return datos;
}

function formatearFecha(fecha) {
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const año = fecha.getFullYear();
    return `${dia}/${mes}/${año}`;
}

// ============================================================================
// BRIDGE DE FECHAS
// ============================================================================
function obtenerFechasFiltro() {
    try {
        const radioSeleccionado = $('.radioPeriodo:checked');
        const tipoSeleccionado = radioSeleccionado.val();
        
        let fechas = {
            inicio: null,
            fin: null,
            tipo: tipoSeleccionado || 'Ninguno'
        };
        
        switch(tipoSeleccionado) {
            case 'Fecha':
                fechas.inicio = $('#inicio').val();
                fechas.fin = $('#fin').val() || $('#inicio').val();
                break;
            case 'Semana':
                const opcionSemana = $('#selectorSemana option:selected');
                fechas.inicio = opcionSemana.attr('inicio');
                fechas.fin = opcionSemana.attr('fin');
                break;
            case 'Mes':
                const opcionMes = $('#selectorMes option:selected');
                fechas.inicio = opcionMes.attr('inicio');
                fechas.fin = opcionMes.attr('fin');
                break;
            case 'Rango':
                fechas.inicio = $('#inicio_rango').val();
                fechas.fin = $('#fin_rango').val();
                break;
        }
        
        return fechas;
        
    } catch (error) {
        console.error('❌ ERROR EN obtenerFechasFiltro():', error);
        return { inicio: null, fin: null, tipo: 'Error' };
    }
}

function validarFechasFiltro() {
    const fechas = obtenerFechasFiltro();
    
    if (!fechas.inicio || !fechas.fin) return false;
    
    const formatoFecha = /^\d{4}-\d{2}-\d{2}$/;
    if (!formatoFecha.test(fechas.inicio) || !formatoFecha.test(fechas.fin)) return false;
    
    if (new Date(fechas.inicio) > new Date(fechas.fin)) return false;
    
    return true;
}

// ============================================================================
// FUNCIONES DE LAYOUT
// ============================================================================
function toggleLayout() {
    const panel = document.getElementById('lateralPanel');
    const icon = document.getElementById('toggleIcon');
    const toggle = document.getElementById('layoutToggle');
    
    if (panel.classList.contains('collapsed')) {
        // EXPANDIR
        panel.classList.remove('collapsed');
        toggle.classList.remove('collapsed');
        icon.className = 'fas fa-chevron-left';
        
        setTimeout(() => {
            toggle.style.left = window.innerWidth <= 768 ? '165px' : '205px';
        }, 10);
        
        localStorage.setItem(MODULE_PREFIX + 'layout_state', 'expanded');
        console.log('📖 PANEL EXPANDIDO');
    } else {
        // COLAPSAR
        panel.classList.add('collapsed');
        toggle.classList.add('collapsed');
        icon.className = 'fas fa-chevron-right';
        toggle.style.left = '0px';
        
        localStorage.setItem(MODULE_PREFIX + 'layout_state', 'collapsed');
        console.log('📕 PANEL COLAPSADO');
    }
}

function toggleFilterGroup(groupName) {
    const items = document.getElementById(`items-${groupName}`);
    const controls = document.getElementById(`controls-${groupName}`);
    const icon = document.getElementById(`icon-${groupName}`);
    
    if (items) {
        const isCollapsed = items.classList.contains('collapsed');
        
        if (isCollapsed) {
            // EXPANDIR
            items.classList.remove('collapsed');
            if (controls) controls.classList.remove('collapsed');
            if (icon) {
                icon.classList.remove('collapsed');
                icon.className = 'fas fa-chevron-down filter-collapse-icon';
            }
        } else {
            // COLAPSAR
            items.classList.add('collapsed');
            if (controls) controls.classList.add('collapsed');
            if (icon) {
                icon.classList.add('collapsed');
                icon.className = 'fas fa-chevron-right filter-collapse-icon collapsed';
            }
        }
        
        localStorage.setItem(MODULE_PREFIX + `filter_${groupName}_state`, isCollapsed ? 'expanded' : 'collapsed');
    }
}

function restaurarEstadoLayout() {
    const toggle = document.getElementById('layoutToggle');
    const panel = document.getElementById('lateralPanel');
    const icon = document.getElementById('toggleIcon');
    
    if (localStorage.getItem(MODULE_PREFIX + 'layout_state') === 'collapsed') {
        panel.classList.add('collapsed');
        toggle.classList.add('collapsed');
        icon.className = 'fas fa-chevron-right';
        toggle.style.left = '0px';
    } else {
        toggle.classList.remove('collapsed');
        panel.classList.remove('collapsed');
        icon.className = 'fas fa-chevron-left';
        toggle.style.left = window.innerWidth <= 768 ? '165px' : '205px';
    }
}

function restaurarEstadosFiltros() {
    const filtros = ['uno', 'dos', 'tres', 'cuatro', 'cinco'];
    
    filtros.forEach(function(filtro) {
        const estadoGuardado = localStorage.getItem(MODULE_PREFIX + `filter_${filtro}_state`);
        
        if (estadoGuardado === 'collapsed') {
            const items = document.getElementById(`items-${filtro}`);
            const controls = document.getElementById(`controls-${filtro}`);
            const icon = document.getElementById(`icon-${filtro}`);
            
            if (items) {
                items.classList.add('collapsed');
                if (controls) controls.classList.add('collapsed');
                if (icon) {
                    icon.classList.add('collapsed');
                    icon.className = 'fas fa-chevron-right filter-collapse-icon collapsed';
                }
            }
        }
    });
    
    console.log('✅ ESTADOS DE FILTROS RESTAURADOS');
}

// ============================================================================
// HANDSONTABLE
// ============================================================================
function inicializarHandsontable() {
    if (hot) {
        hot.destroy();
    }

    hot = new Handsontable(container, {
        language: 'es-MX',
        data: datosDemo,
        licenseKey: 'non-commercial-and-evaluation',
        
        width: '100%',
        height: 'auto',
        stretchH: 'all',
        renderAllRows: false,
        
        colHeaders: colHeaders,
        rowHeaders: true,
        
        manualColumnResize: true,
        manualRowResize: true,
        columnSorting: true,
        outsideClickDeselects: false,
        
        filters: true,
        dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
        
        autoWrapRow: true,
        autoWrapCol: true,
        
        contextMenu: {
            items: {
                "row_above": { name: 'INSERTAR FILA ARRIBA' },
                "row_below": { name: 'INSERTAR FILA DEBAJO' },
                "remove_row": { name: 'ELIMINAR FILA' },
                "hsep1": "---------",
                "copy": { name: 'COPIAR' },
                "cut": { name: 'CORTAR' }
            }
        },

        columns: [
            { readOnly: true, width: 60 },   // ID
            { readOnly: true, width: 80 },   // CÓDIGO
            { readOnly: true, width: 150 },  // NOMBRE
            { readOnly: true, width: 100 },  // CAMPO 1
            { readOnly: true, width: 100 },  // CAMPO 2
            { readOnly: true, width: 100 },  // CAMPO 3
            { readOnly: true, width: 100 },  // CAMPO 4
            { readOnly: true, width: 100 },  // CAMPO 5
            { readOnly: true, width: 100 },  // CAMPO 6
            { readOnly: true, width: 100 },  // CAMPO 7
            { readOnly: true, width: 100 },  // FECHA 1
            { readOnly: true, width: 100 },  // FECHA 2
            { readOnly: true, width: 100 },  // VALOR
            { readOnly: true, width: 100 },  // ESTADO
            { readOnly: true, width: 80 }    // ACCIONES
        ]
    });

    container.classList.add('template-table');
    actualizarContadorRegistros();
    console.log(`📊 HANDSONTABLE INICIALIZADO CON ${datosDemo.length} REGISTROS`);
}

function actualizarContadorRegistros() {
    if (!hot) {
        $('#total_registros').text('0 ');
        return;
    }
    
    let count = 0;
    for (let row = 0; row < hot.countRows(); row++) {
        if (hot.isEmptyRow(row) === false) {
            count++;
        }
    }
    
    $('#total_registros').text(count + ' ');
}

// ============================================================================
// FUNCIONES AUXILIARES
// ============================================================================
function regenerarDatos(cantidad) {
    if (cantidad && cantidad > 0) {
        DATA_CONFIG.TOTAL_REGISTROS = cantidad;
    }
    
    datosDemo = generarDatosDinamicos();
    
    if (hot) {
        hot.loadData(datosDemo);
        actualizarContadorRegistros();
    }
    
    return datosDemo.length;
}

function obtenerFiltrosActivos() {
    var filtros = { uno: [], dos: null, tres: [], cuatro: [], cinco: [] };
    
    $('.filter-group input[type="checkbox"]:checked').each(function() {
        var grupo = $(this).closest('.filter-group').attr('id').replace('filter-', '');
        filtros[grupo].push($(this).val());
    });
    
    var radioSeleccionado = $('.filter-group input[type="radio"]:checked').val();
    if (radioSeleccionado) {
        filtros.dos = radioSeleccionado;
    }
    
    return filtros;
}

// ============================================================================
// INICIALIZACIÓN PRINCIPAL
// ============================================================================
$(document).ready(function() {
    
    // Configuración inicial
    $("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - TEMPLATE LAYOUT');
    
    // Generar datos y inicializar
    datosDemo = generarDatosDinamicos();
    restaurarEstadoLayout();
    restaurarEstadosFiltros();
    inicializarHandsontable();
    
    // ========================================================================
    // EVENTOS DEL LAYOUT
    // ========================================================================
    
    // Redimensionamiento de ventana
    $(window).resize(function() {
        const panel = document.getElementById('lateralPanel');
        const toggle = document.getElementById('layoutToggle');
        
        if (!panel.classList.contains('collapsed')) {
            toggle.style.left = window.innerWidth <= 768 ? '165px' : '205px';
        } else {
            toggle.style.left = '0px';
        }
    });
    
    // Efectos hover en toggle
    $('#layoutToggle').hover(
        function() { $(this).css('transform', 'translateY(-50%) scale(1.05)'); },
        function() { $(this).css('transform', 'translateY(-50%) scale(1)'); }
    );
    
    // ========================================================================
    // EVENTOS DE FILTROS
    // ========================================================================
    
    // Checkboxes
    $('.filter-item input[type="checkbox"]').on('change', function() {
        const filtro = $(this).val();
        const checked = $(this).is(':checked');
        console.log('🔘 FILTRO:', filtro, checked ? 'ON' : 'OFF');
    });
    
    // Radio buttons
    $('.filter-item input[type="radio"]').on('change', function() {
        const filtro = $(this).val();
        const name = $(this).attr('name');
        console.log('📻 RADIO:', name, '->', filtro);
    });
    
    // Botones de control de filtros
    $('.filter-btn').on('click', function() {
        const accion = $(this).text().trim();
        const grupo = $(this).closest('.filter-group').attr('id');
        
        if (accion === 'TODOS') {
            $(this).closest('.filter-group').find('input[type="checkbox"]').prop('checked', true);
            console.log('✅ TODOS los filtros activados en', grupo);
        } else if (accion === 'LIMPIAR') {
            $(this).closest('.filter-group').find('input[type="checkbox"]').prop('checked', false);
            console.log('🧹 TODOS los filtros desactivados en', grupo);
        } else if (accion === 'RESET') {
            $(this).closest('.filter-group').find('input[type="radio"]').first().prop('checked', true);
            console.log('🔄 Radio reseteado en', grupo);
        }
    });
    
    // ========================================================================
    // EVENTOS DEL TOOLBAR
    // ========================================================================
    
    $('.toolbar-btn').on('click', function() {
        const accion = $(this).text().trim();
        
        switch(accion) {
            case 'NUEVO':
                console.log('➕ Crear nuevo registro');
                break;
            case 'EDITAR':
                console.log('✏️ Editar registro seleccionado');
                break;
            case 'ELIMINAR':
                console.log('🗑️ Eliminar registro seleccionado');
                break;
            case 'EXCEL':
                // Exportar a Excel
                if (hot) {
                    var exportPlugin = hot.getPlugin('exportFile');
                    if (exportPlugin) {
                        exportPlugin.downloadFile('csv', {
                            bom: false,
                            columnDelimiter: ',',
                            columnHeaders: true,
                            exportHiddenColumns: false,
                            exportHiddenRows: false,
                            fileExtension: 'csv',
                            filename: 'TEMPLATE_DATA_' + new Date().toISOString().slice(0,10),
                            mimeType: 'text/csv',
                            rowDelimiter: '\r\n',
                            rowHeaders: true
                        });
                        console.log('📊 ARCHIVO EXCEL EXPORTADO');
                    } else {
                        console.warn('⚠️ Plugin de exportación no disponible');
                    }
                }
                break;
        }
    });
    
    // ========================================================================
    // EVENTOS DE FECHAS
    // ========================================================================
    
    $(document).on('change', '.radioPeriodo, .form-control', function() {
        const fechas = obtenerFechasFiltro();
        console.log('📅 FECHAS ACTUALIZADAS:', fechas.inicio, 'hasta', fechas.fin, '(' + fechas.tipo + ')');
        
        // Aquí puedes agregar lógica para filtrar datos automáticamente
        // if (validarFechasFiltro()) {
        //     console.log('✅ Fechas válidas - recargar datos');
        // }
    });
    
    // ========================================================================
    // HOOKS DE HANDSONTABLE
    // ========================================================================
    
    setTimeout(function() {
        if (hot) {
            // Evento de selección de celdas
            hot.addHook('afterSelectionEnd', function(row, column, row2, column2) {
                console.log('📍 SELECCIÓN EN TABLA:', `Filas ${row}-${row2}, Columnas ${column}-${column2}`);
            });
            
            // Evento de cambio de datos
            hot.addHook('afterChange', function(changes, source) {
                if (changes && source !== 'loadData') {
                    console.log('📝 DATOS MODIFICADOS:', changes);
                    actualizarContadorRegistros();
                }
            });
            
            // Evento de filtrado
            hot.addHook('afterFilter', function() {
                actualizarContadorRegistros();
                console.log('🔍 FILTROS APLICADOS EN TABLA');
            });
        }
    }, 1000);
    
    // ========================================================================
    // FINALIZACIÓN
    // ========================================================================
    
    $('#loader').addClass('hidden');
    
    // ========================================================================
    // API GLOBAL PARA DESARROLLADORES
    // ========================================================================
    
    window.layoutTemplate = {
        // Funciones principales
        toggle: toggleLayout,
        toggleFilter: toggleFilterGroup,
        regenerar: regenerarDatos,
        
        // Getters de datos
        datos: function() { 
            return hot ? hot.getData() : []; 
        },
        tabla: function() { 
            return hot; 
        },
        fechas: obtenerFechasFiltro,
        validarFechas: validarFechasFiltro,
        filtros: obtenerFiltrosActivos,
        
        // Configuración
        prefijo: MODULE_PREFIX,
        configuracion: DATA_CONFIG,
        
        // Utilidades avanzadas
        exportar: function(formato = 'csv') {
            if (hot) {
                var exportPlugin = hot.getPlugin('exportFile');
                if (exportPlugin) {
                    exportPlugin.downloadFile(formato, {
                        bom: false,
                        columnDelimiter: ',',
                        columnHeaders: true,
                        filename: 'TEMPLATE_EXPORT_' + new Date().toISOString().slice(0,10),
                        mimeType: formato === 'csv' ? 'text/csv' : 'application/vnd.ms-excel'
                    });
                    return true;
                }
            }
            return false;
        },
        
        filtrarTabla: function(columna, valor) {
            if (hot) {
                const filtersPlugin = hot.getPlugin('filters');
                filtersPlugin.addCondition(columna, 'contains', [valor]);
                filtersPlugin.filter();
                console.log(`🔍 FILTRO APLICADO: Columna ${columna} contiene "${valor}"`);
            }
        },
        
        limpiarFiltrosTabla: function() {
            if (hot) {
                const filtersPlugin = hot.getPlugin('filters');
                filtersPlugin.clearConditions();
                filtersPlugin.filter();
                console.log('🧹 FILTROS DE TABLA LIMPIADOS');
            }
        },
        
        debug: function() {
            console.log('🔍 DEBUG COMPLETO DEL TEMPLATE:');
            console.log('=====================================');
            console.log('📱 Viewport:', window.innerWidth + 'x' + window.innerHeight);
            console.log('📊 Panel lateral:', $('#lateralPanel').hasClass('collapsed') ? 'COLAPSADO' : 'EXPANDIDO');
            console.log('📋 Total registros:', this.datos().length);
            console.log('🎛️ Filtros laterales:', this.filtros());
            console.log('📅 Fechas activas:', this.fechas());
            console.log('🔑 Prefijo módulo:', this.prefijo);
            console.log('⚙️ Configuración:', this.configuracion);
            
            if (hot) {
                console.log('📏 Tabla Handsontable:');
                console.log('   - Filas totales:', hot.countRows());
                console.log('   - Columnas:', hot.countCols());
                console.log('   - Filas renderizadas:', hot.countRenderedRows());
                console.log('   - Columnas renderizadas:', hot.countRenderedCols());
                console.log('   - Filas vacías:', hot.countEmptyRows());
            }
            
            console.log('💾 LocalStorage keys:');
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith(this.prefijo)) {
                    console.log('   -', key, '=', localStorage.getItem(key));
                }
            }
            console.log('=====================================');
        }
    };
    
    // ========================================================================
    // MENSAJES FINALES
    // ========================================================================
    
    console.log('🎉 TEMPLATE LAYOUT COMPLETO - INICIALIZADO CORRECTAMENTE');
    console.log('📊 Datos generados:', datosDemo.length, 'registros');
    console.log('🎛️ Filtros laterales: 5 grupos configurados');
    console.log('📅 Bridge de fechas: ACTIVO');
    console.log('🔑 Prefijo del módulo:', MODULE_PREFIX);
    console.log('');
    console.log('🛠️ API DISPONIBLE EN: window.layoutTemplate');
    console.log('📖 Métodos principales:');
    console.log('   layoutTemplate.toggle()              // Toggle panel lateral');
    console.log('   layoutTemplate.datos()               // Obtener todos los datos');
    console.log('   layoutTemplate.filtros()             // Estado de filtros laterales');
    console.log('   layoutTemplate.fechas()              // Fechas del filtro activo');
    console.log('   layoutTemplate.regenerar(cantidad)   // Regenerar datos');
    console.log('   layoutTemplate.exportar("csv")       // Exportar tabla');
    console.log('   layoutTemplate.filtrarTabla(col,val) // Filtrar por columna');
    console.log('   layoutTemplate.debug()               // Info completa del sistema');
    console.log('');
    console.log('✅ TEMPLATE LISTO PARA PRODUCCIÓN');
    console.log('🚀 Copia este código a tu módulo y cambia MODULE_PREFIX');

});
</script>