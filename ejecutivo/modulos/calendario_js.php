<script>
"use strict";

var EV = {
    mes: new Date().getMonth(),
    anio: new Date().getFullYear(),
    fechaSel: null,
    eventos: [],
    eventoSel: null,
    tab: 'proximos',
    modoEdicion: false,
    modoNuevo: false,
    meses: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    mesesCortos: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    // 🔥 VARIABLES PARA VISTA ANUAL
    anioAnual: new Date().getFullYear()
};

var ID_EJE = <?php echo $id_eje; ?>;
var HOY = new Date().toISOString().split('T')[0];

$(document).ready(function() {
    cargarStats();
    renderCalendario();
    cargarEventosMes();
    verificarEventosHoy();
    
    $('#btnPrev').on('click', function() {
        EV.mes--;
        if (EV.mes < 0) { EV.mes = 11; EV.anio--; }
        renderCalendario();
        cargarEventosMes();
    });
    
    $('#btnNext').on('click', function() {
        EV.mes++;
        if (EV.mes > 11) { EV.mes = 0; EV.anio++; }
        renderCalendario();
        cargarEventosMes();
    });
    
    $('.ev-tab').on('click', function() {
        var tab = $(this).data('tab');
        $('.ev-tab').removeClass('active');
        $(this).addClass('active');
        EV.tab = tab;
        
        if (tab === 'proximos') cargarProximos();
        else if (tab === 'vencidos') cargarVencidos();
        else if (tab === 'generaciones') cargarGeneraciones();
        else if (tab === 'p100c') cargarPorCategoria('P100C');
        else if (tab === 'cobranza') cargarPorCategoria('Cobranza');
        else if (tab === 'plantillas') cargarPlantillas();
        else if (tab === 'fecha' && EV.fechaSel) cargarPorFecha(EV.fechaSel);
        else if (tab === 'fecha') mostrarVacio('Selecciona un día en el calendario');
    });
    
    $('#btnClose, #evOverlay').on('click', cerrarDrawer);
    $('#btnEdit').on('click', toggleModoEdicion);
    $('#btnDelete').on('click', eliminarEvento);
    $('#btnNuevoEvento').on('click', abrirNuevoEvento);
    $('#btnCancelar').on('click', cerrarDrawer);
    $('#btnGuardar').on('click', guardarEvento);
    
    // 🔥 EVENTOS PARA MODAL VISTA ANUAL
    $('#btnVistaAnual').on('click', abrirVistaAnual);
    $('#btnAnualClose, #evModalAnual').on('click', function(e) {
        if (e.target === this) {
            cerrarVistaAnual();
        }
    });
    
    $('#btnAnualPrev').on('click', function() {
        EV.anioAnual--;
        renderAnual();
    });
    
    $('#btnAnualNext').on('click', function() {
        EV.anioAnual++;
        renderAnual();
    });
});

// ============================================
// 🔥 ABRIR/CERRAR MODAL VISTA ANUAL
// ============================================
function abrirVistaAnual() {
    EV.anioAnual = EV.anio; // Usar el año del calendario actual
    $('#evModalAnual').addClass('show');
    renderAnual();
}

function cerrarVistaAnual() {
    $('#evModalAnual').removeClass('show');
}

// ============================================
// 🔥 RENDER VISTA ANUAL
// ============================================
function renderAnual() {
    $('#anualTitle').text(EV.anioAnual);
    
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosAnual',
        id_eje: ID_EJE,
        anio: EV.anioAnual
    }, function(r) {
        if (r.resultado === 'success') {
            renderMesesAnuales(r.eventos, r.stats);
        }
    }, 'json');
}

// ============================================
// 🔥 RENDER LOS 12 MESES
// ============================================
function renderMesesAnuales(eventos, stats) {
    var html = '';
    var hoy = new Date();
    var hoyStr = hoy.toISOString().split('T')[0];
    
    var eventosPorFecha = {};
    eventos.forEach(function(ev) {
        var fecha = ev.fecha;
        if (!eventosPorFecha[fecha]) {
            eventosPorFecha[fecha] = { vencido: 0, pendiente: 0, resuelto: 0, total: 0 };
        }
        eventosPorFecha[fecha].total++;
        if (ev.estado === 'Resuelto') {
            eventosPorFecha[fecha].resuelto++;
        } else if (fecha < hoyStr) {
            eventosPorFecha[fecha].vencido++;
        } else {
            eventosPorFecha[fecha].pendiente++;
        }
    });
    
    var statsPorMes = {};
    for (var i = 0; i < 12; i++) {
        statsPorMes[i] = { vencido: 0, pendiente: 0, resuelto: 0 };
    }
    
    eventos.forEach(function(ev) {
        var mesIdx = parseInt(ev.fecha.split('-')[1]) - 1;
        if (ev.estado === 'Resuelto') {
            statsPorMes[mesIdx].resuelto++;
        } else if (ev.fecha < hoyStr) {
            statsPorMes[mesIdx].vencido++;
        } else {
            statsPorMes[mesIdx].pendiente++;
        }
    });
    
    for (var mes = 0; mes < 12; mes++) {
        html += renderMesMini(EV.anioAnual, mes, eventosPorFecha, statsPorMes[mes], hoy);
    }
    
    $('#anualGrid').html(html);
    
    $('#anualTotal').text(stats.total || 0);
    $('#anualVencidos').text(stats.vencidos || 0);
    $('#anualPendientes').text(stats.pendientes || 0);
    $('#anualResueltos').text(stats.resueltos || 0);
    
    $('.ev-mes-mini-day:not(.other)').on('click', function() {
        var fecha = $(this).data('fecha');
        if (fecha) {
            var partes = fecha.split('-');
            EV.mes = parseInt(partes[1]) - 1;
            EV.anio = parseInt(partes[0]);
            
            cerrarVistaAnual();
            renderCalendario();
            seleccionarFecha(fecha);
        }
    });
}

// ============================================
// 🔥 RENDER UN MES MINI
// ============================================
function renderMesMini(anio, mes, eventosPorFecha, statsMes, hoy) {
    var primerDia = new Date(anio, mes, 1);
    var ultimoDia = new Date(anio, mes + 1, 0);
    var diasMes = ultimoDia.getDate();
    var diaSemInicio = primerDia.getDay();
    diaSemInicio = diaSemInicio === 0 ? 6 : diaSemInicio - 1;
    
    var hoyStr = hoy.toISOString().split('T')[0];
    
    var html = '<div class="ev-mes-mini">';
    
    html += '<div class="ev-mes-mini-header">';
    html += '<span class="ev-mes-mini-title">' + EV.meses[mes] + '</span>';
    html += '<div class="ev-mes-mini-stats">';
    if (statsMes.vencido > 0) {
        html += '<span class="ev-mes-mini-stat vencido">' + statsMes.vencido + '</span>';
    }
    if (statsMes.pendiente > 0) {
        html += '<span class="ev-mes-mini-stat pendiente">' + statsMes.pendiente + '</span>';
    }
    if (statsMes.resuelto > 0) {
        html += '<span class="ev-mes-mini-stat resuelto">' + statsMes.resuelto + '</span>';
    }
    html += '</div>';
    html += '</div>';
    
    html += '<div class="ev-mes-mini-weekdays">';
    var diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
    for (var i = 0; i < 7; i++) {
        html += '<div class="ev-mes-mini-wd">' + diasSemana[i] + '</div>';
    }
    html += '</div>';
    
    html += '<div class="ev-mes-mini-days">';
    
    var mesAnt = mes === 0 ? 11 : mes - 1;
    var anioAnt = mes === 0 ? anio - 1 : anio;
    var ultimoDiaAnt = new Date(anioAnt, mesAnt + 1, 0).getDate();
    
    for (var i = diaSemInicio - 1; i >= 0; i--) {
        var d = ultimoDiaAnt - i;
        html += '<div class="ev-mes-mini-day other">' + d + '</div>';
    }
    
    for (var d = 1; d <= diasMes; d++) {
        var fechaStr = anio + '-' + String(mes + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
        var info = eventosPorFecha[fechaStr];
        
        var cls = 'ev-mes-mini-day';
        
        if (fechaStr === hoyStr) {
            cls += ' today';
        }
        
        if (info) {
            cls += ' has-eventos';
            // Prioridad: vencidos > pendientes > resueltos
            if (info.vencido > 0) {
                cls += ' has-vencido';
            } else if (info.pendiente > 0) {
                cls += ' has-pendiente';
            } else if (info.resuelto > 0) {
                cls += ' has-resuelto';
            }
        }
        
        html += '<div class="' + cls + '" data-fecha="' + fechaStr + '">';
        html += d;
        
        if (info && info.total > 1) {
            var countCls = 'resuelto';
            if (info.vencido > 0) countCls = 'vencido';
            else if (info.pendiente > 0) countCls = 'pendiente';
            else if (info.resuelto > 0) countCls = 'resuelto';
            html += '<span class="ev-mes-mini-count ' + countCls + '">' + info.total + '</span>';
        }
        
        html += '</div>';
    }
    
    var totalCeldas = diaSemInicio + diasMes;
    var restantes = totalCeldas <= 35 ? 35 - totalCeldas : 42 - totalCeldas;
    for (var d = 1; d <= restantes; d++) {
        html += '<div class="ev-mes-mini-day other">' + d + '</div>';
    }
    
    html += '</div>';
    html += '</div>';
    
    return html;
}

// ============================================
// VERIFICAR EVENTOS HOY
// ============================================
function verificarEventosHoy() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosFecha',
        id_eje: ID_EJE,
        fecha: HOY
    }, function(r) {
        if (r.resultado === 'success' && r.eventos && r.eventos.length > 0) {
            $('.ev-tab').removeClass('active');
            $('.ev-tab[data-tab="fecha"]').addClass('active');
            EV.tab = 'fecha';
            seleccionarFecha(HOY);
        } else {
            cargarProximos();
        }
    }, 'json');
}

// ============================================
// ESTADISTICAS
// ============================================
function cargarStats() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEstadisticas',
        id_eje: ID_EJE
    }, function(r) {
        if (r.resultado === 'success') {
            $('#statTotal').text(r.total);
            $('#statResuelto').text(r.resueltos);
            $('#statPendiente').text(r.pendientes);
            $('#statVencido').text(r.vencidos);
            $('#tabProxCount').text(r.pendientes);
            $('#tabVencCount').text(r.vencidos);
            $('#tabGenCount').text(r.generaciones || 0);
            $('#tabP100CCount').text(r.p100c || 0);
            $('#tabCobranzaCount').text(r.cobranza || 0);
            $('#tabPlantillasCount').text(r.plantillas || 0);
        }
    }, 'json');
}

// ============================================
// CALENDARIO
// ============================================
function renderCalendario() {
    var primerDia = new Date(EV.anio, EV.mes, 1);
    var ultimoDia = new Date(EV.anio, EV.mes + 1, 0);
    var diasMes = ultimoDia.getDate();
    var diaSemInicio = primerDia.getDay();
    diaSemInicio = diaSemInicio === 0 ? 6 : diaSemInicio - 1;
    
    $('#calTitle').text(EV.meses[EV.mes] + ' ' + EV.anio);
    $('#calDays').empty();
    
    var mesAnt = EV.mes === 0 ? 11 : EV.mes - 1;
    var anioAnt = EV.mes === 0 ? EV.anio - 1 : EV.anio;
    var ultimoDiaAnt = new Date(anioAnt, mesAnt + 1, 0).getDate();
    
    for (var i = diaSemInicio - 1; i >= 0; i--) {
        var d = ultimoDiaAnt - i;
        $('#calDays').append('<div class="ev-cal-day other"><span class="ev-cal-day-num">' + d + '</span></div>');
    }
    
    var hoyObj = new Date();
    for (var d = 1; d <= diasMes; d++) {
        var fecha = EV.anio + '-' + String(EV.mes + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
        var cls = 'ev-cal-day';
        
        if (hoyObj.getDate() === d && hoyObj.getMonth() === EV.mes && hoyObj.getFullYear() === EV.anio) {
            cls += ' today';
        }
        if (EV.fechaSel === fecha) {
            cls += ' selected';
        }
        
        var $dia = $('<div class="' + cls + '" data-fecha="' + fecha + '"><span class="ev-cal-day-num">' + d + '</span><div class="ev-cal-dots" data-fecha="' + fecha + '"></div></div>');
        $dia.on('click', function() {
            seleccionarFecha($(this).data('fecha'));
        });
        $('#calDays').append($dia);
    }
    
    var totalCeldas = diaSemInicio + diasMes;
    var restantes = totalCeldas <= 35 ? 35 - totalCeldas : 42 - totalCeldas;
    for (var d = 1; d <= restantes; d++) {
        $('#calDays').append('<div class="ev-cal-day other"><span class="ev-cal-day-num">' + d + '</span></div>');
    }
    
    actualizarDotsCalendario();
}

function cargarEventosMes() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosCalendario',
        id_eje: ID_EJE,
        mes: String(EV.mes + 1).padStart(2, '0'),
        anio: EV.anio
    }, function(r) {
        if (r.resultado === 'success') {
            EV.eventos = r.eventos;
            actualizarDotsCalendario();
        }
    }, 'json');
}

function actualizarDotsCalendario() {
    $('.ev-cal-dots').empty();
    
    var porFecha = {};
    EV.eventos.forEach(function(ev) {
        var fecha = ev.fecha;
        if (!porFecha[fecha]) {
            porFecha[fecha] = { vencido: 0, pendiente: 0, resuelto: 0 };
        }
        if (ev.estado === 'Resuelto') {
            porFecha[fecha].resuelto++;
        } else if (fecha < HOY) {
            porFecha[fecha].vencido++;
        } else {
            porFecha[fecha].pendiente++;
        }
    });
    
    for (var fecha in porFecha) {
        var $dots = $('.ev-cal-dots[data-fecha="' + fecha + '"]');
        if ($dots.length) {
            var info = porFecha[fecha];
            if (info.vencido > 0) $dots.append('<span class="ev-cal-dot-mini has-vencido">' + info.vencido + '</span>');
            if (info.pendiente > 0) $dots.append('<span class="ev-cal-dot-mini has-pendiente">' + info.pendiente + '</span>');
            if (info.resuelto > 0) $dots.append('<span class="ev-cal-dot-mini has-resuelto">' + info.resuelto + '</span>');
        }
    }
}

function seleccionarFecha(fecha) {
    EV.fechaSel = fecha;
    $('.ev-cal-day').removeClass('selected');
    $('.ev-cal-day[data-fecha="' + fecha + '"]').addClass('selected');
    
    $('.ev-tab').removeClass('active');
    $('.ev-tab[data-tab="fecha"]').addClass('active');
    EV.tab = 'fecha';
    
    cargarPorFecha(fecha);
}

// ============================================
// CARGAR EVENTOS
// ============================================
function cargarProximos() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerProximosEventos',
        id_eje: ID_EJE
    }, function(r) {
        if (r.resultado === 'success') {
            var titulo = r.mes_nombre ? 'Eventos de ' + r.mes_nombre : 'Próximos eventos';
            $('#panelTitle').text(titulo);
            renderLista(r.eventos);
        }
    }, 'json');
}

function cargarVencidos() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosVencidos',
        id_eje: ID_EJE
    }, function(r) {
        if (r.resultado === 'success') {
            $('#panelTitle').text('Eventos vencidos');
            renderLista(r.eventos);
            cargarPastList(r.eventos.slice(0, 3));
        }
    }, 'json');
}

function cargarGeneraciones() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosGeneraciones',
        id_eje: ID_EJE
    }, function(r) {
        if (r.resultado === 'success') {
            $('#panelTitle').text('Eventos de Generaciones');
            renderLista(r.eventos);
        }
    }, 'json');
}

function cargarPorFecha(fecha) {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosFecha',
        id_eje: ID_EJE,
        fecha: fecha
    }, function(r) {
        if (r.resultado === 'success') {
            var p = fecha.split('-');
            var d = parseInt(p[2]);
            var m = EV.meses[parseInt(p[1]) - 1];
            $('#panelTitle').text(d + ' de ' + m);
            renderLista(r.eventos);
        }
    }, 'json');
}

function cargarPorCategoria(categoria) {
    $.post('server/controlador_evento.php', {
        action: 'obtenerEventosPorCategoria',
        id_eje: ID_EJE,
        categoria: categoria
    }, function(r) {
        if (r.resultado === 'success') {
            $('#panelTitle').text('Eventos: ' + categoria);
            renderLista(r.eventos);
        }
    }, 'json');
}

function cargarPlantillas() {
    $.post('server/controlador_evento.php', {
        action: 'obtenerPlantillas',
        id_eje: ID_EJE
    }, function(r) {
        if (r.resultado === 'success') {
            $('#panelTitle').text('Plantillas Recurrentes');
            renderLista(r.eventos);
        }
    }, 'json');
}

function cargarPastList(eventos) {
    if (!eventos || eventos.length === 0) {
        $('#calPast').hide();
        return;
    }
    
    $('#calPast').show();
    var html = '';
    eventos.forEach(function(ev) {
        var titulo = ev.titulo || 'Sin título';
        html += '<div class="ev-cal-past-item">';
        html += '<a href="#" class="ev-cal-past-link" data-fuente="' + ev.fuente + '" data-id="' + ev.id + '">' + titulo + '</a>';
        html += '<div class="ev-cal-past-date">' + formatFechaCorta(ev.fecha) + '</div>';
        html += '</div>';
    });
    $('#calPastList').html(html);
    
    $('.ev-cal-past-link').on('click', function(e) {
        e.preventDefault();
        abrirDetalle($(this).data('fuente'), $(this).data('id'));
    });
}

// ============================================
// RENDER LISTA UNIFICADA
// ============================================
function renderLista(eventos) {
    if (!eventos || eventos.length === 0) {
        mostrarVacio('No hay eventos');
        return;
    }
    
    var html = '';
    eventos.forEach(function(ev) {
        var esVencido = ev.fecha < HOY && ev.estado !== 'Resuelto';
        var esResuelto = ev.estado === 'Resuelto';
        var esPendiente = !esVencido && !esResuelto;
        
        var clsItem = 'ev-item';
        if (esVencido) clsItem += ' is-vencido';
        else if (esResuelto) clsItem += ' is-resuelto';
        else if (esPendiente) clsItem += ' is-pendiente';
        
        var pillCls = esVencido ? 'vencido' : (esResuelto ? 'resuelto' : 'pendiente');
        var pillTxt = esVencido ? 'Vencido' : (esResuelto ? 'Resuelto' : 'Pendiente');
        
        var fechaParts = ev.fecha.split('-');
        var dia = parseInt(fechaParts[2]);
        var mes = EV.mesesCortos[parseInt(fechaParts[1]) - 1];
        
        var titulo = ev.titulo || 'Sin título';
        var categoria = ev.categoria || 'Administrativo';
        var catClass = 'ev-cat-' + categoria.toLowerCase().replace(/\s+/g, '').replace(/ó/g, 'o').replace(/í/g, 'i');
        
        var hora = '';
        if (ev.hora && ev.hora !== '00:00:00') {
            var horaParts = ev.hora.split(':');
            hora = horaParts[0] + ':' + horaParts[1];
        }
        
        var fuenteBadge = '';
        if (ev.fuente === 'evento') {
            fuenteBadge = '<span class="ev-fuente-badge evento">Evento</span>';
        } else {
            fuenteBadge = '<span class="ev-fuente-badge generacion">Grupo</span>';
        }
        
        var recurrenteBadge = '';
        if (ev.recurrente === 'Si') {
            recurrenteBadge = '<span class="ev-item-recurrente" title="Evento recurrente">🔄</span>';
        } else if (ev.id_padre && ev.id_padre != '0') {
            recurrenteBadge = '<span class="ev-item-recurrente" title="Generado automáticamente">↻</span>';
        }
        
        var linkGeneracion = '';
        if (ev.fuente === 'generacion' && ev.generacion) {
            var periodoActual = new Date().toISOString().slice(0, 7);
        
            var urlGen = 'https://plataforma.ahjende.com/ejecutivo/alumnos.php'
                + '?centros=6%2C3%2C2%2C9%2C8%2C13'
                + '&estatus=PROSPECTO%2CREGISTRO%2CREGISTRADO%2CACTIVO%2CNP%2CBAJA%2CDESERCION%2CFIN+CURSO%2CREINGRESO%2CGRADUADO'
                + '&grupos=EN+CURSO%2CPOR+COMENZAR%2CVENCIDOS'
                + '&generaciones=' + ev.id_generacion
                + '&periodo=' + periodoActual
                + '&bolsa=colegiatura';
        
            linkGeneracion = '<a href="' + urlGen + '" target="_blank" class="ev-item-generacion" onclick="event.stopPropagation();">'
                + ev.generacion +
                '</a>';
        }

        
        html += '<div class="' + clsItem + '" data-fuente="' + ev.fuente + '" data-id="' + ev.id + '">';
        html += '  <div class="ev-item-date">';
        html += '    <div class="ev-item-day">' + dia + '</div>';
        html += '    <div class="ev-item-month">' + mes + '</div>';
        if (hora) {
            html += '    <div class="ev-item-time">' + hora + '</div>';
        }
        html += '  </div>';
        html += '  <div class="ev-item-content">';
        html += '    <div class="ev-item-header">';
        html += '      ' + fuenteBadge;
        if (linkGeneracion) {
            html += '      ' + linkGeneracion;
        }
        html += '    </div>';
        html += '    <h4 class="ev-item-title">' + titulo + recurrenteBadge + '</h4>';
        if (ev.subtitulo) {
            html += '    <div class="ev-item-subtitle">' + ev.subtitulo + '</div>';
        }
        if (ev.descripcion && ev.descripcion.trim() !== '') {
            html += '    <p class="ev-item-desc">' + ev.descripcion + '</p>';
        }
        html += '  </div>';
        html += '  <div class="ev-item-meta">';
        html += '    <span class="ev-pill ' + pillCls + '">' + pillTxt + '</span>';
        if (ev.plantel) {
            html += '    <span class="ev-item-plantel">' + ev.plantel + '</span>';
        }
        if (ev.programa) {
            html += '    <span class="ev-item-programa">' + ev.programa + '</span>';
        }
        html += '  </div>';
        html += '  <div class="ev-item-actions">';
        // 🔥 SIEMPRE mostrar checkbox, incluso si está resuelto
        html += '    <input type="checkbox" class="ev-item-check" data-fuente="' + ev.fuente + '" data-id="' + ev.id + '" ' + (esResuelto ? 'checked' : '') + ' onclick="event.stopPropagation();">';
        html += '  </div>';
        html += '</div>';
    });
    
    $('#evList').html(html);
    
    $('.ev-item').on('click', function() {
        abrirDetalle($(this).data('fuente'), $(this).data('id'));
    });
    
    $('.ev-item-check').on('change', function(e) {
        e.stopPropagation();
        var fuente = $(this).data('fuente');
        var id = $(this).data('id');
        var checked = $(this).is(':checked');
        actualizarValidacion(fuente, id, checked);
    });
}

function mostrarVacio(msg) {
    $('#evList').html('<div class="ev-empty"><div class="ev-empty-icon">📅</div><p class="ev-empty-text">' + msg + '</p></div>');
}

// ============================================
// DRAWER - NUEVO EVENTO
// ============================================
function abrirNuevoEvento() {
    EV.modoNuevo = true;
    EV.modoEdicion = false;
    EV.eventoSel = null;
    
    $('#drawerTitle').text('Nuevo Evento');
    $('#btnEdit').hide();
    $('#btnDelete').hide();
    $('#evDrawerFooter').show();
    
    var html = '';
    
    html += '<div class="ev-field">';
    html += '  <span class="ev-label">Título del evento *</span>';
    html += '  <input type="text" class="ev-input" id="inpNombre" placeholder="Ej: Junta CEM, Validación DN, Pago día 5" maxlength="200">';
    html += '</div>';
    
    html += '<div class="ev-field-row">';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Tipo *</span>';
    html += '    <select class="ev-select" id="selTipo">';
    html += '      <option value="Administrativo">Administrativo</option>';
    html += '      <option value="Admisiones">Admisiones</option>';
    html += '      <option value="Académico">Académico</option>';
    html += '    </select>';
    html += '  </div>';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Categoría *</span>';
    html += '    <select class="ev-select" id="selCategoria">';
    html += '      <option value="Administrativo">Administrativo</option>';
    html += '      <option value="P100C">P100C</option>';
    html += '      <option value="Cobranza">Cobranza</option>';
    html += '      <option value="Pagos">Pagos</option>';
    html += '      <option value="Juntas">Juntas</option>';
    html += '      <option value="Mentoria">Mentoría</option>';
    html += '      <option value="Comercial">Comercial</option>';
    html += '    </select>';
    html += '  </div>';
    html += '</div>';
    
    html += '<div class="ev-field-row">';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Plantel</span>';
    html += '    <select class="ev-select" id="selPlantel">';
    html += '      <option value="">Todos</option>';
    html += '    </select>';
    html += '  </div>';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Hora</span>';
    html += '    <input type="time" class="ev-input" id="inpHora">';
    html += '  </div>';
    html += '</div>';
    
    html += '<div class="ev-field-row">';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Fecha inicio *</span>';
    html += '    <input type="date" class="ev-input" id="inpFechaIni" value="' + HOY + '">';
    html += '  </div>';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Fecha fin</span>';
    html += '    <input type="date" class="ev-input" id="inpFechaFin" value="' + HOY + '">';
    html += '  </div>';
    html += '</div>';
    
    html += '<div class="ev-field">';
    html += '  <span class="ev-label">Descripción</span>';
    html += '  <textarea class="ev-input ev-textarea" id="inpDesc" placeholder="Descripción del evento..." maxlength="400"></textarea>';
    html += '</div>';
    
    html += '<div class="ev-field">';
    html += '  <div class="ev-checkbox-row">';
    html += '    <input type="checkbox" class="ev-checkbox" id="chkRecurrente">';
    html += '    <label class="ev-checkbox-label" for="chkRecurrente">Evento recurrente (se repite automáticamente)</label>';
    html += '  </div>';
    html += '</div>';
    
    html += '<div id="panelRecurrencia" class="ev-recurrencia-panel" style="display:none;">';
    html += '  <div class="ev-recurrencia-title">Configuración de Recurrencia</div>';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Tipo de recurrencia</span>';
    html += '    <select class="ev-select" id="selTipoRec">';
    html += '      <option value="Mensual_Fijo">Mensual - Día fijo (Ej: día 5, día 27)</option>';
    html += '      <option value="Mensual_Variable">Mensual - Día variable (Ej: 1er Miércoles)</option>';
    html += '    </select>';
    html += '  </div>';
    html += '  <div id="panelMensualFijo" class="ev-field">';
    html += '    <span class="ev-label">Día del mes (1-31)</span>';
    html += '    <input type="number" class="ev-input" id="inpDiaMes" min="1" max="31" placeholder="Ej: 5 para día 5 de cada mes">';
    html += '  </div>';
    html += '  <div id="panelMensualVariable" class="ev-field-row" style="display:none;">';
    html += '    <div class="ev-field">';
    html += '      <span class="ev-label">Número de semana</span>';
    html += '      <select class="ev-select" id="selNumSem">';
    html += '        <option value="1">Primera</option>';
    html += '        <option value="2">Segunda</option>';
    html += '        <option value="3">Tercera</option>';
    html += '        <option value="4">Cuarta</option>';
    html += '        <option value="5">Última</option>';
    html += '      </select>';
    html += '    </div>';
    html += '    <div class="ev-field">';
    html += '      <span class="ev-label">Día de la semana</span>';
    html += '      <select class="ev-select" id="selDiaSem">';
    html += '        <option value="Lunes">Lunes</option>';
    html += '        <option value="Martes">Martes</option>';
    html += '        <option value="Miércoles">Miércoles</option>';
    html += '        <option value="Jueves">Jueves</option>';
    html += '        <option value="Viernes">Viernes</option>';
    html += '        <option value="Sábado">Sábado</option>';
    html += '        <option value="Domingo">Domingo</option>';
    html += '      </select>';
    html += '    </div>';
    html += '  </div>';
    html += '  <div class="ev-info-badge">';
    html += '    💡 Las plantillas recurrentes generan eventos automáticamente cada mes';
    html += '  </div>';
    html += '</div>';
    
    $('#evDrawerBody').html(html);
    
    cargarPlanteles();
    
    $('#chkRecurrente').on('change', function() {
        if ($(this).is(':checked')) {
            $('#panelRecurrencia').show();
        } else {
            $('#panelRecurrencia').hide();
        }
    });
    
    $('#selTipoRec').on('change', function() {
        if ($(this).val() === 'Mensual_Fijo') {
            $('#panelMensualFijo').show();
            $('#panelMensualVariable').hide();
        } else {
            $('#panelMensualFijo').hide();
            $('#panelMensualVariable').show();
        }
    });
    
    $('#evOverlay, #evDrawer').addClass('show');
}

// ============================================
// DRAWER - DETALLE/EDICIÓN
// ============================================
function abrirDetalle(fuente, id) {
    EV.modoNuevo = false;
    EV.modoEdicion = false;
    $('#btnEdit').removeClass('active').show();
    $('#btnDelete').show();
    $('#evDrawerFooter').hide();
    
    $.post('server/controlador_evento.php', {
        action: 'obtenerDetalleEvento',
        fuente: fuente,
        id: id
    }, function(r) {
        if (r.resultado === 'success') {
            EV.eventoSel = r.evento;
            $('#drawerTitle').text('Detalle del evento');
            renderDetalle(r.evento, r.otros);
            $('#evOverlay, #evDrawer').addClass('show');
        }
    }, 'json');
}

function renderDetalle(ev, otros) {
    var esResuelto = ev.estado === 'Resuelto';
    var esGeneracion = ev.fuente === 'generacion';
    var esRecurrente = ev.recurrente === 'Si';
    var esGenerado = ev.id_padre && ev.id_padre != '0';
    
    var html = '';
    
    if (esGeneracion) {
        html += '<div class="ev-info-badge">';
        html += '  🎓 Este evento pertenece a una generación académica';
        html += '</div>';
    }
    
    if (esGenerado) {
        html += '<div class="ev-info-badge">';
        html += '  ↻ Este evento fue generado automáticamente desde una plantilla';
        html += '</div>';
    }
    
    // 🔥 SIEMPRE mostrar checkbox para marcar/desmarcar
    html += '<div class="ev-field">';
    html += '  <div class="ev-checkbox-row">';
    html += '    <input type="checkbox" class="ev-checkbox" id="chkResuelto" ' + (esResuelto ? 'checked' : '') + '>';
    html += '    <label class="ev-checkbox-label" for="chkResuelto">Marcar como resuelto</label>';
    html += '  </div>';
    html += '</div>';
    
    html += '<div class="ev-field">';
    html += '  <span class="ev-label">Título</span>';
    html += '  <input type="text" class="ev-input" id="inpNombre" value="' + (ev.titulo || '') + '" disabled>';
    html += '</div>';
    
    if (esGeneracion) {
        var periodoActual = new Date().toISOString().slice(0, 7);
        var urlGen = 'https://plataforma.ahjende.com/ejecutivo/alumnos.php?centros=6%2C3%2C2%2C9%2C8%2C13&estatus=ACTIVO%2CBAJA%2CFIN+CURSO%2CREINGRESO%2CGRADUADO&grupos=EN+CURSO&generaciones=' + ev.id_generacion + '&periodo=' + periodoActual + '&bolsa=colegiatura';
        
        html += '<div class="ev-field">';
        html += '  <span class="ev-label">Grupo</span>';
        html += '  <div class="ev-value"><a href="' + urlGen + '" target="_blank" class="ev-link">' + ev.generacion + ' (#' + ev.id_generacion + ')</a></div>';
        html += '</div>';
        
        if (ev.subtitulo) {
            html += '<div class="ev-field">';
            html += '  <span class="ev-label">Semana</span>';
            html += '  <input type="text" class="ev-input" id="inpSemana" value="' + ev.subtitulo + '" disabled>';
            html += '</div>';
        }
    } else {
        html += '<div class="ev-field-row">';
        html += '  <div class="ev-field">';
        html += '    <span class="ev-label">Tipo</span>';
        html += '    <select class="ev-select" id="selTipo" disabled>';
        html += '      <option value="Administrativo" ' + (ev.tipo === 'Administrativo' ? 'selected' : '') + '>Administrativo</option>';
        html += '      <option value="Admisiones" ' + (ev.tipo === 'Admisiones' ? 'selected' : '') + '>Admisiones</option>';
        html += '      <option value="Académico" ' + (ev.tipo === 'Académico' ? 'selected' : '') + '>Académico</option>';
        html += '    </select>';
        html += '  </div>';
        html += '  <div class="ev-field">';
        html += '    <span class="ev-label">Categoría</span>';
        html += '    <select class="ev-select" id="selCategoria" disabled>';
        html += '      <option value="Administrativo" ' + (ev.categoria === 'Administrativo' ? 'selected' : '') + '>Administrativo</option>';
        html += '      <option value="P100C" ' + (ev.categoria === 'P100C' ? 'selected' : '') + '>P100C</option>';
        html += '      <option value="Cobranza" ' + (ev.categoria === 'Cobranza' ? 'selected' : '') + '>Cobranza</option>';
        html += '      <option value="Pagos" ' + (ev.categoria === 'Pagos' ? 'selected' : '') + '>Pagos</option>';
        html += '      <option value="Juntas" ' + (ev.categoria === 'Juntas' ? 'selected' : '') + '>Juntas</option>';
        html += '      <option value="Mentoria" ' + (ev.categoria === 'Mentoria' ? 'selected' : '') + '>Mentoría</option>';
        html += '      <option value="Comercial" ' + (ev.categoria === 'Comercial' ? 'selected' : '') + '>Comercial</option>';
        html += '    </select>';
        html += '  </div>';
        html += '</div>';
    }
    
    html += '<div class="ev-field-row">';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Plantel</span>';
    html += '    <select class="ev-select" id="selPlantel" disabled>';
    html += '      <option value="">Todos</option>';
    html += '    </select>';
    html += '  </div>';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Hora</span>';
    html += '    <input type="time" class="ev-input" id="inpHora" value="' + (ev.hora || '') + '" disabled>';
    html += '  </div>';
    html += '</div>';
    
    html += '<div class="ev-field-row">';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Fecha inicio</span>';
    html += '    <input type="date" class="ev-input" id="inpFechaIni" value="' + ev.fecha + '" disabled>';
    html += '  </div>';
    html += '  <div class="ev-field">';
    html += '    <span class="ev-label">Fecha fin</span>';
    html += '    <input type="date" class="ev-input" id="inpFechaFin" value="' + (ev.fecha_fin || ev.fecha) + '" disabled>';
    html += '  </div>';
    html += '</div>';
    
    html += '<div class="ev-field">';
    html += '  <span class="ev-label">Descripción</span>';
    html += '  <textarea class="ev-input ev-textarea" id="inpDesc" disabled>' + (ev.descripcion || '') + '</textarea>';
    html += '</div>';
    
    if (esRecurrente) {
        html += '<div class="ev-recurrencia-panel">';
        html += '  <div class="ev-recurrencia-title">🔄 Evento Recurrente</div>';
        html += '  <div class="ev-field">';
        html += '    <span class="ev-label">Tipo</span>';
        html += '    <div class="ev-value">';
        if (ev.tipo_recurrente === 'Mensual_Fijo') {
            html += 'Mensual - Día ' + ev.dia_mes + ' de cada mes';
        } else {
            var numSemTxt = ['Primera', 'Segunda', 'Tercera', 'Cuarta', 'Última'][ev.num_semana - 1];
            html += 'Mensual - ' + numSemTxt + ' ' + ev.dia_semana + ' de cada mes';
        }
        html += '    </div>';
        html += '  </div>';
        html += '</div>';
    }
    
    if (otros && otros.length > 0) {
        html += '<div class="ev-otros">';
        html += '  <div class="ev-otros-title">Otros eventos relacionados</div>';
        otros.forEach(function(otro) {
            var otroVencido = otro.fecha < HOY && otro.estado !== 'Resuelto';
            var otroResuelto = otro.estado === 'Resuelto';
            var clsOtro = 'ev-otro-item';
            if (otroVencido) clsOtro += ' is-vencido';
            else if (otroResuelto) clsOtro += ' is-resuelto';
            
            var titulo = otro.titulo || 'Sin título';
            var subtitulo = otro.subtitulo || '';
            
            html += '  <div class="' + clsOtro + '" data-fuente="' + otro.fuente + '" data-id="' + otro.id + '">';
            html += '    <div class="ev-otro-titulo">' + titulo + '</div>';
            if (subtitulo) {
                html += '    <div class="ev-otro-subtitulo">' + subtitulo + '</div>';
            }
            html += '    <div class="ev-otro-fecha">' + formatFechaCorta(otro.fecha) + '</div>';
            html += '  </div>';
        });
        html += '</div>';
    }
    
    $('#evDrawerBody').html(html);
    
    cargarPlanteles(ev.id_plantel);
    
    $('#chkResuelto').on('change', function() {
        actualizarValidacion(ev.fuente, ev.id, $(this).is(':checked'));
    });
    
    $('.ev-otro-item').on('click', function() {
        abrirDetalle($(this).data('fuente'), $(this).data('id'));
    });
}

function toggleModoEdicion() {
    EV.modoEdicion = !EV.modoEdicion;
    
    if (EV.modoEdicion) {
        $('#btnEdit').addClass('active');
        $('#evDrawerFooter').show();
        
        if (EV.eventoSel.fuente === 'evento') {
            $('#inpNombre, #inpFechaIni, #inpFechaFin, #inpHora, #inpDesc, #selTipo, #selCategoria, #selPlantel').prop('disabled', false);
        } else {
            $('#inpNombre, #inpSemana, #inpFechaIni, #inpDesc').prop('disabled', false);
        }
    } else {
        $('#btnEdit').removeClass('active');
        $('#evDrawerFooter').hide();
        $('#inpNombre, #inpFechaIni, #inpFechaFin, #inpHora, #inpDesc, #inpSemana, #selTipo, #selCategoria, #selPlantel').prop('disabled', true);
    }
}

function cerrarDrawer() {
    $('#evOverlay, #evDrawer').removeClass('show');
    EV.eventoSel = null;
    EV.modoEdicion = false;
    EV.modoNuevo = false;
}

// ============================================
// CRUD - GUARDAR
// ============================================
function guardarEvento() {
    if (EV.modoNuevo) {
        var nombre = $('#inpNombre').val().trim();
        var tipo = $('#selTipo').val();
        var categoria = $('#selCategoria').val();
        var plantel = $('#selPlantel').val();
        var fechaIni = $('#inpFechaIni').val();
        var fechaFin = $('#inpFechaFin').val();
        var hora = $('#inpHora').val();
        var desc = $('#inpDesc').val().trim();
        
        if (!nombre) {
            alert('El título del evento es obligatorio');
            return;
        }
        
        if (!fechaIni) {
            alert('La fecha es obligatoria');
            return;
        }
        
        var data = {
            action: 'crearEvento',
            id_eje: ID_EJE,
            nom_eve: nombre,
            tip_eve: tipo,
            cat_eve: categoria,
            id_pla: plantel,
            ini_eve: fechaIni,
            fin_eve: fechaFin || fechaIni,
            hor_eve: hora || '00:00:00',
            des_eve: desc
        };
        
        if ($('#chkRecurrente').is(':checked')) {
            data.rec_eve = 'Si';
            data.tipo_rec_eve = $('#selTipoRec').val();
            
            if (data.tipo_rec_eve === 'Mensual_Fijo') {
                data.dia_mes_eve = $('#inpDiaMes').val();
            } else {
                data.num_sem_eve = $('#selNumSem').val();
                data.dia_sem_eve = $('#selDiaSem').val();
            }
        } else {
            data.rec_eve = 'No';
        }
        
        $.post('server/controlador_evento.php', data, function(r) {
            if (r.resultado === 'success') {
                cerrarDrawer();
                cargarStats();
                cargarEventosMes();
                recargarLista();
            } else {
                alert('Error: ' + (r.mensaje || 'No se pudo guardar'));
            }
        }, 'json');
        
    } else {
        var data = {
            action: 'actualizarEvento',
            fuente: EV.eventoSel.fuente,
            id: EV.eventoSel.id,
            nom_eve: $('#inpNombre').val().trim(),
            ini_eve: $('#inpFechaIni').val(),
            des_eve: $('#inpDesc').val().trim()
        };
        
        if (EV.eventoSel.fuente === 'evento') {
            data.tip_eve = $('#selTipo').val();
            data.cat_eve = $('#selCategoria').val();
            data.id_pla = $('#selPlantel').val();
            data.fin_eve = $('#inpFechaFin').val();
            data.hor_eve = $('#inpHora').val() || '00:00:00';
        } else {
            data.sem_gru_pag = $('#inpSemana').val().trim();
        }
        
        $.post('server/controlador_evento.php', data, function(r) {
            if (r.resultado === 'success') {
                cerrarDrawer();
                cargarStats();
                cargarEventosMes();
                recargarLista();
            } else {
                alert('Error: ' + (r.mensaje || 'No se pudo guardar'));
            }
        }, 'json');
    }
}

// ============================================
// CRUD - ELIMINAR
// ============================================
function eliminarEvento() {
    if (!EV.eventoSel) return;
    
    var msg = '¿Eliminar este evento?';
    if (EV.eventoSel.fuente === 'evento' && EV.eventoSel.recurrente === 'Si') {
        msg = '⚠️ Este es un evento recurrente (plantilla).\n¿Eliminar la plantilla y todos los eventos futuros generados?';
    }
    
    if (!confirm(msg)) return;
    
    $.post('server/controlador_evento.php', {
        action: 'eliminarEvento',
        fuente: EV.eventoSel.fuente,
        id: EV.eventoSel.id
    }, function(r) {
        if (r.resultado === 'success') {
            cerrarDrawer();
            cargarStats();
            cargarEventosMes();
            recargarLista();
        } else {
            alert('Error: ' + (r.mensaje || 'No se pudo eliminar'));
        }
    }, 'json');
}

// ============================================
// ACCIONES
// ============================================
function actualizarValidacion(fuente, id, checked) {
    var val = checked ? 'Resuelto' : 'Pendiente';
    $.post('server/controlador_evento.php', {
        action: 'actualizarValidacion',
        fuente: fuente,
        id: id,
        val_eve: val
    }, function() {
        cargarStats();
        cargarEventosMes();
        recargarLista();
    }, 'json');
}

function recargarLista() {
    if (EV.tab === 'proximos') cargarProximos();
    else if (EV.tab === 'vencidos') cargarVencidos();
    else if (EV.tab === 'generaciones') cargarGeneraciones();
    else if (EV.tab === 'p100c') cargarPorCategoria('P100C');
    else if (EV.tab === 'cobranza') cargarPorCategoria('Cobranza');
    else if (EV.tab === 'plantillas') cargarPlantillas();
    else if (EV.tab === 'fecha' && EV.fechaSel) cargarPorFecha(EV.fechaSel);
}

// ============================================
// UTILS
// ============================================
function cargarPlanteles(idSeleccionado) {
    $.post('server/controlador_evento.php', {
        action: 'obtenerPlanteles',
        id_eje: ID_EJE
    }, function(r) {
        if (r.resultado === 'success') {
            var html = '<option value="">Todos</option>';
            r.planteles.forEach(function(p) {
                var sel = (idSeleccionado && idSeleccionado == p.id_pla) ? 'selected' : '';
                html += '<option value="' + p.id_pla + '" ' + sel + '>' + p.nom_pla + '</option>';
            });
            $('#selPlantel').html(html);
        }
    }, 'json');
}

function formatFechaCorta(fecha) {
    var p = fecha.split('-');
    return parseInt(p[2]) + ' ' + EV.mesesCortos[parseInt(p[1]) - 1] + ' ' + p[0];
}
</script>