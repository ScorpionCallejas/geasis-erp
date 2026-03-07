<?php  
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $id_pla = $_POST['id_pla'];

    // Obtener planteles según el caso
    $planteles = array();
    if ($id_pla == "0") {
        $id_eje = $id; 
        $sqlPlantelesEjecutivo = "SELECT id_pla FROM planteles_ejecutivo WHERE id_eje = $id_eje";
        $resultadoPlantelesEjecutivo = mysqli_query($db, $sqlPlantelesEjecutivo);
        
        while ($filaPlantelesEjecutivo = mysqli_fetch_assoc($resultadoPlantelesEjecutivo)) {
            $planteles[] = $filaPlantelesEjecutivo['id_pla'];
        }
    } else {
        $planteles[] = $id_pla;
    }
?>

<style>
/* ============================================
   VARIABLES Y SISTEMA DE DISEÑO
   ============================================ */
:root {
    /* Colores principales - Inspirado en Google Calendar & Microsoft Teams */
    --cal-primary: #1a73e8;
    --cal-primary-dark: #1557b0;
    --cal-primary-light: #e8f0fe;
    
    --cal-success: #0f9d58;
    --cal-success-dark: #0d8043;
    --cal-success-light: #e6f4ea;
    
    --cal-danger: #d93025;
    --cal-danger-dark: #b31412;
    --cal-danger-light: #fce8e6;
    
    --cal-warning: #f9ab00;
    --cal-warning-dark: #e37400;
    --cal-warning-light: #fef7e0;
    
    --cal-purple: #9334e9;
    --cal-purple-dark: #7c2bc7;
    --cal-purple-light: #f3e8ff;
    
    /* Grises y neutrales */
    --cal-gray-50: #f8f9fa;
    --cal-gray-100: #f1f3f4;
    --cal-gray-200: #e8eaed;
    --cal-gray-300: #dadce0;
    --cal-gray-400: #bdc1c6;
    --cal-gray-500: #9aa0a6;
    --cal-gray-600: #80868b;
    --cal-gray-700: #5f6368;
    --cal-gray-800: #3c4043;
    --cal-gray-900: #202124;
    
    /* Sombras - Sistema escalonado */
    --shadow-xs: 0 1px 2px 0 rgba(60, 64, 67, 0.1);
    --shadow-sm: 0 1px 3px 0 rgba(60, 64, 67, 0.15);
    --shadow-md: 0 4px 6px -1px rgba(60, 64, 67, 0.15);
    --shadow-lg: 0 8px 12px -2px rgba(60, 64, 67, 0.2);
    --shadow-xl: 0 12px 20px -4px rgba(60, 64, 67, 0.25);
    --shadow-2xl: 0 20px 32px -8px rgba(60, 64, 67, 0.3);
    
    /* Border radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --radius-2xl: 24px;
    
    /* Transiciones */
    --transition-fast: 120ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Espaciado */
    --spacing-xs: 4px;
    --spacing-sm: 8px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
    --spacing-xl: 32px;
    --spacing-2xl: 48px;
}

/* ============================================
   RESET Y BASE
   ============================================ */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ============================================
   CONTENEDOR PRINCIPAL DEL CALENDARIO
   ============================================ */
.calendar-container {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* ============================================
   HEADER DEL CALENDARIO
   ============================================ */
.calendar-header {
    padding: var(--spacing-lg);
    background: linear-gradient(180deg, #ffffff 0%, var(--cal-gray-50) 100%);
    border-bottom: 1px solid var(--cal-gray-200);
}

.calendar-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.calendar-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--cal-gray-900);
    margin: 0;
    letter-spacing: -0.02em;
}

.calendar-filters {
    display: flex;
    gap: var(--spacing-sm);
}

.calendar-filter-toggle {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    background: white;
    border: 1px solid var(--cal-gray-300);
    border-radius: var(--radius-lg);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-base);
    user-select: none;
}

.calendar-filter-toggle:hover {
    background: var(--cal-gray-50);
    border-color: var(--cal-gray-400);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.calendar-filter-toggle.active {
    border-color: currentColor;
    background: currentColor;
    color: white;
}

.calendar-filter-toggle .filter-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
    transition: all var(--transition-base);
}

.calendar-filter-toggle.filter-tramites .filter-indicator {
    background: var(--cal-danger);
}

.calendar-filter-toggle.filter-reinscripciones .filter-indicator {
    background: var(--cal-purple);
}

.calendar-filter-toggle.filter-colegiaturas .filter-indicator {
    background: var(--cal-primary);
}

.calendar-filter-toggle.active .filter-indicator {
    background: white;
}

/* ============================================
   FULLCALENDAR CUSTOMIZATION - ENTERPRISE LEVEL
   ============================================ */
#calendar {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Google Sans', Roboto, 'Helvetica Neue', Arial, sans-serif;
    flex: 1;
    padding: var(--spacing-lg);
}

.fc {
    border: none;
    height: 100%;
}

.fc .fc-toolbar {
    margin-bottom: var(--spacing-lg) !important;
    gap: var(--spacing-md);
}

.fc .fc-toolbar-title {
    font-size: 22px !important;
    font-weight: 600 !important;
    color: var(--cal-gray-900) !important;
    letter-spacing: -0.03em;
}

/* Botones del calendario */
.fc .fc-button {
    background: white !important;
    border: 1px solid var(--cal-gray-300) !important;
    color: var(--cal-gray-700) !important;
    padding: 10px 18px !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    border-radius: var(--radius-md) !important;
    transition: all var(--transition-base) !important;
    box-shadow: var(--shadow-xs) !important;
    text-transform: capitalize !important;
}

.fc .fc-button:hover {
    background: var(--cal-gray-50) !important;
    border-color: var(--cal-gray-400) !important;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm) !important;
}

.fc .fc-button:active,
.fc .fc-button-active {
    background: var(--cal-primary) !important;
    color: white !important;
    border-color: var(--cal-primary) !important;
    box-shadow: var(--shadow-sm) !important;
}

.fc .fc-button:focus {
    box-shadow: 0 0 0 3px var(--cal-primary-light) !important;
}

.fc .fc-button-primary:disabled {
    opacity: 0.4 !important;
    cursor: not-allowed !important;
    transform: none !important;
}

/* Grid del calendario */
.fc .fc-scrollgrid {
    border: 1px solid var(--cal-gray-200) !important;
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.fc .fc-col-header {
    background: var(--cal-gray-50) !important;
}

.fc .fc-col-header-cell {
    border-color: var(--cal-gray-200) !important;
    padding: var(--spacing-md) var(--spacing-sm) !important;
}

.fc .fc-col-header-cell-cushion {
    font-size: 12px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.08em !important;
    color: var(--cal-gray-600) !important;
}

/* Celdas de días */
.fc .fc-daygrid-day {
    border-color: var(--cal-gray-200) !important;
    transition: background-color var(--transition-fast);
}

.fc .fc-daygrid-day:hover {
    background: var(--cal-gray-50);
}

.fc .fc-daygrid-day-frame {
    min-height: 100px;
    padding: var(--spacing-sm);
}

.fc .fc-daygrid-day-number {
    font-size: 14px !important;
    font-weight: 500 !important;
    color: var(--cal-gray-700) !important;
    padding: var(--spacing-xs) var(--spacing-sm) !important;
}

/* Día actual */
.fc .fc-day-today {
    background: var(--cal-primary-light) !important;
}

.fc .fc-day-today .fc-daygrid-day-number {
    background: var(--cal-primary);
    color: white !important;
    border-radius: var(--radius-md);
    font-weight: 600 !important;
}

/* Días fuera del mes actual */
.fc .fc-day-other .fc-daygrid-day-number {
    color: var(--cal-gray-400) !important;
}

/* ============================================
   EVENTOS EN EL CALENDARIO - ESTILO BADGE
   ============================================ */
.fc .fc-daygrid-day-events {
    margin-top: var(--spacing-sm) !important;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.fc .fc-event {
    border: none !important;
    border-radius: var(--radius-sm) !important;
    padding: 4px 8px !important;
    margin: 0 !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all var(--transition-base) !important;
    box-shadow: var(--shadow-xs) !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px;
}

.fc .fc-event:hover {
    transform: scale(1.02);
    box-shadow: var(--shadow-md) !important;
    z-index: 10 !important;
}

.fc .fc-event-title {
    font-weight: 600 !important;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
}

/* Estilos por tipo de pago */
.fc .payment-event-tramite {
    background: var(--cal-danger) !important;
    color: white !important;
}

.fc .payment-event-tramite:hover {
    background: var(--cal-danger-dark) !important;
}

.fc .payment-event-reinscripcion {
    background: var(--cal-purple) !important;
    color: white !important;
}

.fc .payment-event-reinscripcion:hover {
    background: var(--cal-purple-dark) !important;
}

.fc .payment-event-colegiatura {
    background: var(--cal-primary) !important;
    color: white !important;
}

.fc .payment-event-colegiatura:hover {
    background: var(--cal-primary-dark) !important;
}

/* ============================================
   MODAL DE DETALLE - ESTILO MICROSOFT TEAMS
   ============================================ */
.payment-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-lg);
    animation: modalFadeIn var(--transition-base);
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.payment-modal {
    background: white;
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-2xl);
    max-width: 700px;
    width: 100%;
    max-height: 85vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: modalSlideUp var(--transition-slow);
}

@keyframes modalSlideUp {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.96);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.payment-modal-header {
    padding: var(--spacing-xl);
    border-bottom: 1px solid var(--cal-gray-200);
    background: linear-gradient(180deg, #ffffff 0%, var(--cal-gray-50) 100%);
}

.payment-modal-header-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
}

.payment-modal-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--cal-gray-900);
    margin: 0;
    letter-spacing: -0.03em;
}

.payment-modal-close {
    background: var(--cal-gray-100);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--transition-fast);
    color: var(--cal-gray-600);
    font-size: 20px;
    flex-shrink: 0;
}

.payment-modal-close:hover {
    background: var(--cal-gray-200);
    color: var(--cal-gray-900);
    transform: scale(1.1);
}

.payment-modal-date {
    font-size: 14px;
    color: var(--cal-gray-600);
    font-weight: 500;
}

.payment-modal-body {
    padding: var(--spacing-xl);
    overflow-y: auto;
    flex: 1;
}

.payment-modal-body::-webkit-scrollbar {
    width: 8px;
}

.payment-modal-body::-webkit-scrollbar-track {
    background: var(--cal-gray-100);
}

.payment-modal-body::-webkit-scrollbar-thumb {
    background: var(--cal-gray-300);
    border-radius: 4px;
}

.payment-modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--cal-gray-400);
}

/* Grupos de tipos de pago */
.payment-type-group {
    background: var(--cal-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    border: 1px solid var(--cal-gray-200);
}

.payment-type-group:last-child {
    margin-bottom: 0;
}

.payment-type-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--cal-gray-200);
}

.payment-type-title {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    font-size: 16px;
    font-weight: 600;
    color: var(--cal-gray-900);
}

.payment-type-indicator {
    width: 20px;
    height: 20px;
    border-radius: var(--radius-sm);
    flex-shrink: 0;
}

.payment-type-indicator.tramite {
    background: var(--cal-danger);
}

.payment-type-indicator.reinscripcion {
    background: var(--cal-purple);
}

.payment-type-indicator.colegiatura {
    background: var(--cal-primary);
}

.payment-type-stats {
    display: flex;
    gap: var(--spacing-lg);
}

.payment-stat {
    text-align: right;
}

.payment-stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--cal-gray-600);
    font-weight: 600;
    margin-bottom: 2px;
}

.payment-stat-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--cal-gray-900);
}

/* Lista de pagos */
.payment-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.payment-item {
    background: white;
    border: 1px solid var(--cal-gray-200);
    border-radius: var(--radius-md);
    padding: var(--spacing-md);
    transition: all var(--transition-fast);
    cursor: pointer;
}

.payment-item:hover {
    border-color: var(--cal-gray-400);
    box-shadow: var(--shadow-sm);
    transform: translateX(4px);
}

.payment-item-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: var(--spacing-sm);
}

.payment-item-student {
    font-size: 14px;
    font-weight: 600;
    color: var(--cal-gray-900);
    line-height: 1.4;
}

.payment-item-amount {
    font-size: 16px;
    font-weight: 700;
    color: var(--cal-gray-900);
    white-space: nowrap;
}

.payment-item-meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-sm);
}

.payment-item-meta-item {
    font-size: 12px;
    color: var(--cal-gray-600);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.payment-item-meta-label {
    font-weight: 600;
    color: var(--cal-gray-700);
}

/* Footer del modal */
.payment-modal-footer {
    padding: var(--spacing-xl);
    border-top: 1px solid var(--cal-gray-200);
    background: var(--cal-gray-50);
}

.payment-modal-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-modal-total-label {
    font-size: 18px;
    font-weight: 600;
    color: var(--cal-gray-900);
}

.payment-modal-total-amount {
    font-size: 32px;
    font-weight: 700;
    color: var(--cal-gray-900);
    letter-spacing: -0.02em;
}

.payment-modal-total-breakdown {
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--cal-gray-300);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--spacing-md);
}

.payment-modal-total-item {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.payment-modal-total-item-label {
    font-size: 12px;
    color: var(--cal-gray-600);
    font-weight: 500;
}

.payment-modal-total-item-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--cal-gray-900);
}

/* Loading state */
.payment-modal-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-2xl);
    gap: var(--spacing-lg);
}

.payment-modal-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--cal-gray-200);
    border-top-color: var(--cal-primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.payment-modal-loading-text {
    color: var(--cal-gray-600);
    font-size: 14px;
    font-weight: 500;
}

/* Empty state */
.payment-modal-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-2xl);
    gap: var(--spacing-md);
}

.payment-modal-empty-icon {
    font-size: 64px;
    opacity: 0.2;
}

.payment-modal-empty-text {
    color: var(--cal-gray-600);
    font-size: 15px;
    text-align: center;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1024px) {
    .calendar-container {
        margin: 0 -15px;
        border-radius: 0;
    }
}

@media (max-width: 768px) {
    .calendar-header-top {
        flex-direction: column;
        gap: var(--spacing-md);
    }
    
    .calendar-filters {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .calendar-filter-toggle {
        flex: 1;
        justify-content: center;
    }
    
    .payment-modal {
        max-width: 100%;
        max-height: 95vh;
        border-radius: var(--radius-xl);
    }
    
    .payment-modal-header,
    .payment-modal-body,
    .payment-modal-footer {
        padding: var(--spacing-lg);
    }
    
    .payment-type-stats {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .payment-item-meta {
        grid-template-columns: 1fr;
    }
    
    .payment-modal-total-breakdown {
        grid-template-columns: 1fr;
    }
}

/* ============================================
   UTILITIES
   ============================================ */
.hidden {
    display: none !important;
}

.no-scroll {
    overflow: hidden;
}
</style>

<!-- CALENDARIO 8/12 COLUMNAS -->
<div class="row">
    <div class="col-lg-8">
        <div class="calendar-container">
            <div class="calendar-header">
                <div class="calendar-header-top">
                    <h3 class="calendar-title">Calendario de Pagos Pendientes</h3>
                    
                    <div class="calendar-filters">
                        <button class="calendar-filter-toggle filter-tramites active" data-tipo="Otros">
                            <span class="filter-indicator"></span>
                            <span>Trámites</span>
                        </button>
                        <button class="calendar-filter-toggle filter-reinscripciones active" data-tipo="Reinscripción">
                            <span class="filter-indicator"></span>
                            <span>Reinscripciones</span>
                        </button>
                        <button class="calendar-filter-toggle filter-colegiaturas" data-tipo="Colegiatura">
                            <span class="filter-indicator"></span>
                            <span>Colegiaturas</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="calendar"></div>
        </div>
    </div>
</div>

<script>
"use strict";

<?php 
    $currentDate = date('Y-m-d H:i:s');

    if($id_pla == 0){
        $id_pla = $plantel;
    }

    // QUERY PAGOS AGRUPADOS - SOLO PENDIENTES
    $sqlPagosCalendario = "
        SELECT 
            DATE(p.ini_pag) as fecha_pago,
            p.tip_pag,
            COUNT(*) as cantidad,
            SUM(p.mon_pag) as monto_total,
            pl.nom_pla,
            CONCAT('pago_', DATE(p.ini_pag), '_', REPLACE(p.tip_pag, ' ', '_'), '_', pl.id_pla) as id_evento
        FROM pago p
        INNER JOIN alu_ram ar ON p.id_alu_ram10 = ar.id_alu_ram
        INNER JOIN rama r ON ar.id_ram3 = r.id_ram
        INNER JOIN plantel pl ON r.id_pla1 = pl.id_pla
        WHERE pl.id_pla = '$id_pla'
          AND p.ini_pag IS NOT NULL
          AND p.est_pag = 'Pendiente'
          AND p.ini_pag >= CURDATE() - INTERVAL 3 MONTH
          AND p.ini_pag <= CURDATE() + INTERVAL 12 MONTH
        GROUP BY DATE(p.ini_pag), p.tip_pag, pl.id_pla
        ORDER BY fecha_pago
    ";

    $resultadoPagosCalendario = mysqli_query($db, $sqlPagosCalendario);

    echo "var allEvents = [";
    $first = true;

    // PROCESAR PAGOS AGRUPADOS
    while($filaPago = mysqli_fetch_assoc($resultadoPagosCalendario)) {
        if (!$first) echo ",";
        
        $className = '';
        $titulo_tipo = '';
        
        switch($filaPago['tip_pag']) {
            case 'Colegiatura':
                $className = 'payment-event-colegiatura';
                $titulo_tipo = 'COL';
                break;
            case 'Reinscripción':
                $className = 'payment-event-reinscripcion';
                $titulo_tipo = 'REINS';
                break;
            case 'Otros':
                $className = 'payment-event-tramite';
                $titulo_tipo = 'TRÁM';
                break;
        }
        
        $cantidad = $filaPago['cantidad'];
        $titulo = "$cantidad $titulo_tipo";
        
        echo "{";
        echo "id: '" . $filaPago['id_evento'] . "',";
        echo "title: '" . addslashes($titulo) . "',";
        echo "start: '" . $filaPago['fecha_pago'] . "',";
        echo "className: '" . $className . "',";
        echo "editable: false,";
        echo "extendedProps: {";
        echo "  tipo_pago: '" . $filaPago['tip_pag'] . "',";
        echo "  cantidad: " . $cantidad . ",";
        echo "  monto: " . $filaPago['monto_total'] . ",";
        echo "  plantel: '" . addslashes($filaPago['nom_pla']) . "',";
        echo "  fecha: '" . $filaPago['fecha_pago'] . "',";
        echo "  es_pago: true";
        echo "}";
        echo "}";
        $first = false;
    }
    
    echo "];";
?>

(function($) {
    // ============================================
    // VARIABLES GLOBALES
    // ============================================
    var calendar = null;
    var currentModal = null;
    var activeFilters = ['Otros', 'Reinscripción']; // Por defecto: Trámites + Reinscripciones
    
    // ============================================
    // FUNCIONES DE UTILIDAD
    // ============================================
    function formatMoney(amount) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }
    
    function formatDate(dateString) {
        const meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        const fecha = new Date(dateString + 'T00:00:00');
        const diaSemana = dias[fecha.getDay()];
        const dia = fecha.getDate();
        const mes = meses[fecha.getMonth()];
        const anio = fecha.getFullYear();
        
        return diaSemana + ', ' + dia + ' de ' + mes + ' de ' + anio;
    }
    
    function getTipoPagoLabel(tipo) {
        const labels = {
            'Colegiatura': 'Colegiaturas',
            'Reinscripción': 'Reinscripciones',
            'Otros': 'Trámites'
        };
        return labels[tipo] || tipo;
    }
    
    function getTipoPagoClass(tipo) {
        const classes = {
            'Colegiatura': 'colegiatura',
            'Reinscripción': 'reinscripcion',
            'Otros': 'tramite'
        };
        return classes[tipo] || 'tramite';
    }
    
    // ============================================
    // SISTEMA DE FILTROS
    // ============================================
    function toggleFilter(tipo) {
        const index = activeFilters.indexOf(tipo);
        
        if (index > -1) {
            activeFilters.splice(index, 1);
        } else {
            activeFilters.push(tipo);
        }
        
        updateCalendarEvents();
    }
    
    function updateCalendarEvents() {
        // Remover todos los eventos
        calendar.getEvents().forEach(event => event.remove());
        
        // Agregar solo los eventos filtrados
        const filteredEvents = allEvents.filter(event => 
            activeFilters.includes(event.extendedProps.tipo_pago)
        );
        
        filteredEvents.forEach(event => calendar.addEvent(event));
    }
    
    // Event listeners para filtros
    $('.calendar-filter-toggle').on('click', function() {
        const tipo = $(this).data('tipo');
        $(this).toggleClass('active');
        toggleFilter(tipo);
    });
    
    // ============================================
    // FUNCIONES DE MODAL
    // ============================================
    function createModal() {
        const overlay = document.createElement('div');
        overlay.className = 'payment-modal-overlay';
        overlay.innerHTML = `
            <div class="payment-modal">
                <div class="payment-modal-header">
                    <div class="payment-modal-header-top">
                        <div>
                            <h3 class="payment-modal-title">Pagos Pendientes</h3>
                            <div class="payment-modal-date" id="modal-date"></div>
                        </div>
                        <button class="payment-modal-close" id="modal-close">×</button>
                    </div>
                </div>
                <div class="payment-modal-body" id="modal-body">
                    <div class="payment-modal-loading">
                        <div class="payment-modal-spinner"></div>
                        <div class="payment-modal-loading-text">Cargando información...</div>
                    </div>
                </div>
                <div class="payment-modal-footer" id="modal-footer" style="display: none;">
                    <div class="payment-modal-total">
                        <span class="payment-modal-total-label">Total del día</span>
                        <span class="payment-modal-total-amount" id="modal-total-amount">$0.00</span>
                    </div>
                    <div class="payment-modal-total-breakdown" id="modal-breakdown"></div>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        document.body.classList.add('no-scroll');
        
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeModal();
            }
        });
        
        document.getElementById('modal-close').addEventListener('click', closeModal);
        document.addEventListener('keydown', handleEscKey);
        
        return overlay;
    }
    
    function handleEscKey(e) {
        if (e.key === 'Escape' && currentModal) {
            closeModal();
        }
    }
    
    function closeModal() {
        if (currentModal) {
            currentModal.remove();
            currentModal = null;
            document.body.classList.remove('no-scroll');
            document.removeEventListener('keydown', handleEscKey);
        }
    }
    
    function showPaymentDetails(fecha) {
        currentModal = createModal();
        document.getElementById('modal-date').textContent = formatDate(fecha);
        
        $.ajax({
            url: 'server/controlador_pago.php',
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'obtener_pagos_dia',
                fecha: fecha,
                id_pla: <?php echo $id_pla; ?>,
                tipos: activeFilters.join(',')
            },
            success: function(response) {
                if (response.success) {
                    renderPaymentDetails(response.data);
                } else {
                    showEmptyState();
                }
            },
            error: function() {
                showErrorState();
            }
        });
    }
    
    function renderPaymentDetails(data) {
        const modalBody = document.getElementById('modal-body');
        const modalFooter = document.getElementById('modal-footer');
        const modalBreakdown = document.getElementById('modal-breakdown');
        
        if (!data || Object.keys(data.por_tipo).length === 0) {
            showEmptyState();
            return;
        }
        
        let html = '<div class="payment-modal-section">';
        
        Object.keys(data.por_tipo).forEach(function(tipo) {
            const grupo = data.por_tipo[tipo];
            const tipoClass = getTipoPagoClass(tipo);
            const tipoLabel = getTipoPagoLabel(tipo);
            
            html += `
                <div class="payment-type-group">
                    <div class="payment-type-header">
                        <div class="payment-type-title">
                            <div class="payment-type-indicator ${tipoClass}"></div>
                            <span>${tipoLabel}</span>
                        </div>
                        <div class="payment-type-stats">
                            <div class="payment-stat">
                                <div class="payment-stat-label">Pagos</div>
                                <div class="payment-stat-value">${grupo.cantidad}</div>
                            </div>
                            <div class="payment-stat">
                                <div class="payment-stat-label">Monto</div>
                                <div class="payment-stat-value">${formatMoney(grupo.monto_total)}</div>
                            </div>
                        </div>
                    </div>
                    <div class="payment-list">
            `;
            
            grupo.pagos.forEach(function(pago) {
                html += `
                    <div class="payment-item">
                        <div class="payment-item-header">
                            <div class="payment-item-student">${pago.alumno}</div>
                            <div class="payment-item-amount">${formatMoney(pago.monto)}</div>
                        </div>
                        <div class="payment-item-meta">
                            <div class="payment-item-meta-item">
                                <span class="payment-item-meta-label">MAT:</span>
                                <span>${pago.matricula}</span>
                            </div>
                            <div class="payment-item-meta-item">
                                <span class="payment-item-meta-label">Concepto:</span>
                                <span>${pago.concepto}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div></div>';
        });
        
        html += '</div>';
        modalBody.innerHTML = html;
        
        document.getElementById('modal-total-amount').textContent = formatMoney(data.total_monto);
        
        let breakdownHtml = '';
        Object.keys(data.por_tipo).forEach(function(tipo) {
            const grupo = data.por_tipo[tipo];
            breakdownHtml += `
                <div class="payment-modal-total-item">
                    <span class="payment-modal-total-item-label">${getTipoPagoLabel(tipo)}</span>
                    <span class="payment-modal-total-item-value">${formatMoney(grupo.monto_total)}</span>
                </div>
            `;
        });
        
        modalBreakdown.innerHTML = breakdownHtml;
        modalFooter.style.display = 'block';
    }
    
    function showEmptyState() {
        const modalBody = document.getElementById('modal-body');
        modalBody.innerHTML = `
            <div class="payment-modal-empty">
                <div class="payment-modal-empty-text">No hay pagos pendientes para esta fecha con los filtros seleccionados</div>
            </div>
        `;
    }
    
    function showErrorState() {
        const modalBody = document.getElementById('modal-body');
        modalBody.innerHTML = `
            <div class="payment-modal-empty">
                <div class="payment-modal-empty-text">Error al cargar la información. Intente nuevamente.</div>
            </div>
        `;
    }
    
    // ============================================
    // INICIALIZACIÓN DEL CALENDARIO
    // ============================================
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: "es",
        themeSystem: "bootstrap",
        initialView: "dayGridMonth",
        height: 'auto',
        buttonText: {
            today: "Hoy",
            month: "Mes"
        },
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth"
        },
        editable: false,
        eventClick: function(info) {
            var event = info.event;
            if(event.extendedProps.es_pago) {
                showPaymentDetails(event.extendedProps.fecha);
            }
        }
    });

    calendar.render();
    updateCalendarEvents(); // Aplicar filtros iniciales
    
})(jQuery);

</script>