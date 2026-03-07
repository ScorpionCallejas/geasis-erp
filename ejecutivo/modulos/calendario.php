<style>
:root {
    --azul-900: #0f172a;
    --azul-700: #1e40af;
    --azul-600: #2563eb;
    --azul-500: #3b82f6;
    --azul-100: #dbeafe;
    --azul-50: #eff6ff;
    --gris-900: #111827;
    --gris-800: #1f2937;
    --gris-700: #374151;
    --gris-600: #4b5563;
    --gris-500: #6b7280;
    --gris-400: #9ca3af;
    --gris-300: #d1d5db;
    --gris-200: #e5e7eb;
    --gris-100: #f3f4f6;
    --gris-50: #f9fafb;
    --verde: #10b981;
    --amarillo: #f59e0b;
    --rojo: #ef4444;
    --cyan: #06b6d4;
    --rosa: #ec4899;
    --naranja: #f97316;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.ev-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 12px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 11px;
    color: var(--gris-800);
}

/* ============================================
   🔥 STATS ULTRA COMPACTAS CON ÍCONOS
   ============================================ */
.ev-stats {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.ev-stat {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 5px;
    border-radius: 3px;
    background: var(--gris-200);
}

.ev-stat-icon {
    font-size: 9px;
    line-height: 1;
    font-weight: 700;
}

.ev-stat-num {
    font-size: 10px;
    font-weight: 700;
    line-height: 1;
}

.ev-stat-label {
    font-size: 7px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: var(--gris-600);
}

/* 🔥 COLORES POR ESTADO CON ÍCONOS */
.ev-stat.total {
    background: var(--gris-200);
}
.ev-stat.total .ev-stat-icon { color: var(--gris-600); }
.ev-stat.total .ev-stat-num { color: var(--gris-700); }

.ev-stat.resuelto {
    background: #dcfce7;
}
.ev-stat.resuelto .ev-stat-icon { color: var(--verde); }
.ev-stat.resuelto .ev-stat-num { color: var(--verde); }

.ev-stat.pendiente {
    background: #fef3c7;
}
.ev-stat.pendiente .ev-stat-icon { color: var(--amarillo); }
.ev-stat.pendiente .ev-stat-num { color: var(--amarillo); }

.ev-stat.vencido {
    background: #fee2e2;
}
.ev-stat.vencido .ev-stat-icon { color: var(--rojo); }
.ev-stat.vencido .ev-stat-num { color: var(--rojo); }

/* ============================================
   MAIN GRID
   ============================================ */
.ev-main {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 16px;
    align-items: start;
}

/* ============================================
   CALENDARIO COMPACTO
   ============================================ */
.ev-cal {
    background: white;
    border: 1px solid var(--gris-200);
    border-radius: 6px;
    overflow: hidden;
}

.ev-cal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: var(--azul-50);
    border-bottom: 1px solid var(--gris-200);
}

.ev-cal-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--azul-900);
}

.ev-cal-nav {
    display: flex;
    gap: 3px;
}

.ev-cal-btn {
    width: 24px;
    height: 24px;
    border: 1px solid var(--gris-300);
    background: white;
    border-radius: 3px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gris-600);
    font-size: 10px;
    transition: all 0.15s;
}

.ev-cal-btn:hover {
    background: var(--gris-50);
    border-color: var(--azul-500);
    color: var(--azul-600);
}

.ev-cal-body {
    padding: 10px;
}

.ev-cal-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 3px;
}

.ev-cal-wd {
    text-align: center;
    font-size: 9px;
    font-weight: 600;
    color: var(--gris-500);
    padding: 4px 0;
    text-transform: uppercase;
}

.ev-cal-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.ev-cal-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
    cursor: pointer;
    position: relative;
    font-size: 11px;
    color: var(--gris-700);
    transition: all 0.1s;
    min-height: 32px;
}

.ev-cal-day:hover {
    background: var(--gris-100);
}

.ev-cal-day.other {
    color: var(--gris-300);
}

.ev-cal-day.today {
    background: var(--azul-100);
    font-weight: 600;
    color: var(--azul-700);
}

.ev-cal-day.selected {
    background: var(--azul-600);
    color: white;
}

.ev-cal-day.selected:hover {
    background: var(--azul-700);
}

.ev-cal-day-num {
    line-height: 1;
}

.ev-cal-dots {
    display: flex;
    gap: 2px;
    margin-top: 2px;
    height: 12px;
    align-items: center;
}

.ev-cal-dot-mini {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    font-weight: 700;
    color: white;
}

.ev-cal-dot-mini.has-vencido { background: var(--rojo); }
.ev-cal-dot-mini.has-pendiente { background: var(--amarillo); }
.ev-cal-dot-mini.has-resuelto { background: var(--verde); }

/* Past Events */
.ev-cal-past {
    padding: 10px 12px;
    border-top: 1px solid var(--gris-200);
    background: var(--gris-50);
}

.ev-cal-past-title {
    font-size: 10px;
    font-weight: 600;
    color: var(--gris-500);
    text-transform: uppercase;
    margin: 0 0 6px 0;
}

.ev-cal-past-item {
    padding: 5px 0;
    border-bottom: 1px solid var(--gris-200);
}

.ev-cal-past-item:last-child {
    border-bottom: none;
}

.ev-cal-past-link {
    font-size: 11px;
    color: var(--azul-600);
    text-decoration: none;
    font-weight: 500;
    display: block;
}

.ev-cal-past-link:hover {
    text-decoration: underline;
}

.ev-cal-past-date {
    font-size: 9px;
    color: var(--gris-400);
    margin-top: 2px;
}

/* ============================================
   🔥 PANEL DE EVENTOS - SÚPER COMPACTO
   ============================================ */
.ev-panel {
    background: white;
    border: 1px solid var(--gris-200);
    border-radius: 6px;
    overflow: hidden;
}

.ev-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 8px;
    background: var(--azul-50);
    border-bottom: 1px solid var(--gris-200);
}

.ev-panel-title {
    font-size: 11px;
    font-weight: 600;
    color: var(--azul-900);
}

.ev-panel-actions {
    display: flex;
    gap: 4px;
}

.ev-btn-nuevo {
    padding: 4px 8px;
    font-size: 9px;
    font-weight: 600;
    background: var(--azul-600);
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.15s;
}

.ev-btn-nuevo:hover {
    background: var(--azul-700);
}

/* 🔥 BOTÓN VISTA ANUAL - ESTILO DISCRETO */
.ev-btn-vista-anual {
    padding: 4px 8px;
    font-size: 9px;
    font-weight: 600;
    background: var(--gris-100);
    color: var(--gris-700);
    border: 1px solid var(--gris-300);
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.15s;
}

.ev-btn-vista-anual:hover {
    background: var(--gris-200);
    border-color: var(--gris-400);
}

.ev-tabs {
    display: flex;
    border-bottom: 1px solid var(--gris-200);
    background: var(--gris-50);
    overflow-x: auto;
}

.ev-tab {
    padding: 5px 9px;
    font-size: 9px;
    font-weight: 500;
    color: var(--gris-600);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.15s;
    white-space: nowrap;
}

.ev-tab:hover {
    color: var(--gris-900);
    background: white;
}

.ev-tab.active {
    color: var(--azul-600);
    border-bottom-color: var(--azul-600);
    background: white;
}

.ev-tab-count {
    display: inline-block;
    margin-left: 3px;
    padding: 1px 4px;
    background: var(--gris-200);
    border-radius: 8px;
    font-size: 7px;
    font-weight: 600;
}

.ev-tab.active .ev-tab-count {
    background: var(--azul-100);
    color: var(--azul-600);
}

/* 🔥 LISTA DE EVENTOS - MUY COMPACTA */
.ev-list {
    max-height: calc(100vh - 240px);
    overflow-y: auto;
}

.ev-item {
    display: grid;
    grid-template-columns: 32px 1fr 120px 18px;
    gap: 8px;
    padding: 6px 8px;
    border-bottom: 1px solid var(--gris-100);
    cursor: pointer;
    transition: background 0.1s;
    align-items: center;
}

.ev-item:hover {
    background: var(--gris-50);
}

.ev-item.is-vencido {
    background: #fef2f2;
    border-left: 3px solid var(--rojo);
}

.ev-item.is-vencido:hover {
    background: #fee2e2;
}

.ev-item.is-resuelto {
    opacity: 0.7;
}

.ev-item.is-resuelto:hover {
    opacity: 1;
}

.ev-item.is-resuelto .ev-item-check {
    opacity: 1;
}

.ev-item.is-pendiente {
    border-left: 3px solid var(--amarillo);
}

/* 🔥 BADGE DE FUENTE */
.ev-fuente-badge {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    padding: 1px 4px;
    border-radius: 2px;
    font-size: 7px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.ev-fuente-badge.evento {
    background: #e0e7ff;
    color: #3730a3;
}

.ev-fuente-badge.generacion {
    background: #fce7f3;
    color: #9f1239;
}

/* 🔥 FECHA - MINI */
.ev-item-date {
    text-align: center;
}

.ev-item-day {
    font-size: 13px;
    font-weight: 700;
    color: var(--gris-900);
    line-height: 1;
}

.ev-item-month {
    font-size: 7px;
    color: var(--gris-500);
    text-transform: uppercase;
    margin-top: 1px;
}

.ev-item-time {
    font-size: 8px;
    color: var(--azul-600);
    font-weight: 600;
    margin-top: 2px;
}

/* 🔥 CONTENIDO - COMPACTO */
.ev-item-content {
    min-width: 0;
}

.ev-item-header {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 2px;
}

.ev-item-category {
    font-size: 8px;
    display: inline-block;
}

.ev-item-generacion {
    font-size: 8px;
    color: var(--rosa);
    text-decoration: none;
    font-weight: 600;
}

.ev-item-generacion:hover {
    text-decoration: underline;
}

.ev-item-title {
    font-size: 10px;
    font-weight: 600;
    color: var(--gris-900);
    margin: 0 0 2px 0;
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: 4px;
}

.ev-item-recurrente {
    display: inline-flex;
    align-items: center;
    font-size: 8px;
    color: var(--cyan);
    background: #cffafe;
    padding: 1px 4px;
    border-radius: 2px;
}

.ev-item-subtitle {
    font-size: 8px;
    color: var(--gris-500);
    margin: 0 0 3px 0;
}

.ev-item-desc {
    font-size: 9px;
    color: var(--gris-600);
    line-height: 1.3;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* 🔥 META - COMPACTA */
.ev-item-meta {
    display: flex;
    flex-direction: column;
    gap: 3px;
    align-items: flex-end;
    font-size: 8px;
}

.ev-item-plantel {
    font-weight: 600;
    color: var(--gris-700);
}

.ev-item-programa {
    color: var(--gris-500);
}

/* 🔥 ACCIONES */
.ev-item-actions {
    display: flex;
    align-items: center;
    justify-content: center;
}

.ev-item-check {
    width: 13px;
    height: 13px;
    cursor: pointer;
    accent-color: var(--verde);
}

/* 🔥 PILLS - MINI */
.ev-pill {
    display: inline-block;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 7px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.ev-pill.resuelto {
    background: #dcfce7;
    color: #166534;
}

.ev-pill.pendiente {
    background: #fef3c7;
    color: #92400e;
}

.ev-pill.vencido {
    background: #fee2e2;
    color: #991b1b;
}

/* Categorías colores */
.ev-cat-p100c { background: #fce7f3; color: #9f1239; }
.ev-cat-administrativo { background: #dbeafe; color: #1e40af; }
.ev-cat-cobranza { background: #fee2e2; color: #991b1b; }
.ev-cat-pagos { background: #d1fae5; color: #065f46; }
.ev-cat-juntas { background: #e0e7ff; color: #3730a3; }
.ev-cat-mentoria { background: #fef3c7; color: #92400e; }
.ev-cat-comercial { background: #e0f2fe; color: #075985; }
.ev-cat-admisiones { background: #fef3c7; color: #92400e; }
.ev-cat-academico { background: #e0e7ff; color: #3730a3; }
.ev-cat-generaciones { background: #fce7f3; color: #9f1239; }

/* Empty state */
.ev-empty {
    padding: 30px 20px;
    text-align: center;
    color: var(--gris-400);
}

.ev-empty-icon {
    font-size: 28px;
    margin-bottom: 6px;
    opacity: 0.5;
}

.ev-empty-text {
    font-size: 12px;
    color: var(--gris-500);
}

/* ============================================
   MODAL DRAWER
   ============================================ */
.ev-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    z-index: 9998;
}

.ev-overlay.show {
    display: block;
}

.ev-drawer {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    width: 420px;
    height: 100%;
    background: white;
    box-shadow: -4px 0 12px rgba(0,0,0,0.1);
    z-index: 9999;
    transform: translateX(100%);
    transition: transform 0.2s ease;
    flex-direction: column;
}

.ev-drawer.show {
    display: flex;
    transform: translateX(0);
}

.ev-drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    background: var(--azul-50);
    border-bottom: 1px solid var(--gris-200);
}

.ev-drawer-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--azul-900);
}

.ev-drawer-actions {
    display: flex;
    gap: 4px;
    align-items: center;
}

.ev-drawer-btn {
    width: 28px;
    height: 28px;
    border: 1px solid var(--gris-300);
    background: white;
    border-radius: 3px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gris-600);
    font-size: 12px;
    transition: all 0.15s;
}

.ev-drawer-btn:hover {
    background: var(--gris-50);
    border-color: var(--azul-500);
    color: var(--azul-600);
}

.ev-drawer-btn.active {
    background: var(--azul-600);
    border-color: var(--azul-600);
    color: white;
}

.ev-drawer-btn.danger:hover {
    background: var(--rojo);
    border-color: var(--rojo);
    color: white;
}

.ev-drawer-close {
    width: 28px;
    height: 28px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 16px;
    color: var(--gris-500);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
}

.ev-drawer-close:hover {
    background: var(--gris-100);
}

.ev-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 14px;
}

.ev-drawer-footer {
    padding: 10px 14px;
    border-top: 1px solid var(--gris-200);
    background: var(--gris-50);
    display: flex;
    gap: 6px;
    justify-content: flex-end;
}

.ev-btn {
    padding: 6px 12px;
    font-size: 10px;
    font-weight: 600;
    border-radius: 3px;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s;
}

.ev-btn-primary {
    background: var(--azul-600);
    color: white;
}

.ev-btn-primary:hover {
    background: var(--azul-700);
}

.ev-btn-secondary {
    background: white;
    border-color: var(--gris-300);
    color: var(--gris-700);
}

.ev-btn-secondary:hover {
    background: var(--gris-50);
}

/* Form elements */
.ev-field {
    margin-bottom: 14px;
}

.ev-field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.ev-label {
    display: block;
    font-size: 9px;
    font-weight: 600;
    color: var(--gris-500);
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 4px;
}

.ev-value {
    font-size: 12px;
    color: var(--gris-800);
    padding: 7px 9px;
    background: var(--gris-50);
    border-radius: 3px;
    border: 1px solid var(--gris-200);
}

.ev-link {
    color: var(--azul-600);
    text-decoration: none;
    font-weight: 500;
}

.ev-link:hover {
    text-decoration: underline;
}

.ev-input, .ev-select {
    width: 100%;
    padding: 7px 9px;
    border: 1px solid var(--gris-300);
    border-radius: 3px;
    font-size: 12px;
    font-family: inherit;
    transition: all 0.15s;
}

.ev-input:focus, .ev-select:focus {
    outline: none;
    border-color: var(--azul-500);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.ev-input:disabled, .ev-select:disabled {
    background: var(--gris-50);
    color: var(--gris-600);
    cursor: not-allowed;
}

.ev-textarea {
    min-height: 60px;
    resize: vertical;
}

.ev-checkbox-row {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 9px;
    background: var(--gris-50);
    border-radius: 3px;
}

.ev-checkbox {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--verde);
}

.ev-checkbox-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--gris-800);
}

.ev-recurrencia-panel {
    background: #ecfeff;
    border: 1px solid #a5f3fc;
    border-radius: 4px;
    padding: 10px;
    margin-top: 8px;
}

.ev-recurrencia-title {
    font-size: 10px;
    font-weight: 600;
    color: var(--cyan);
    margin-bottom: 8px;
    text-transform: uppercase;
}

.ev-info-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: #fef3c7;
    border-radius: 3px;
    font-size: 9px;
    color: #92400e;
    margin-top: 8px;
}

.ev-otros {
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid var(--gris-200);
}

.ev-otros-title {
    font-size: 10px;
    font-weight: 600;
    color: var(--gris-500);
    text-transform: uppercase;
    margin-bottom: 8px;
}

.ev-otro-item {
    padding: 8px;
    margin-bottom: 6px;
    background: var(--gris-50);
    border-radius: 3px;
    border-left: 3px solid var(--gris-300);
    cursor: pointer;
    transition: all 0.15s;
}

.ev-otro-item:hover {
    background: var(--azul-50);
    border-left-color: var(--azul-500);
}

.ev-otro-item.is-vencido {
    border-left-color: var(--rojo);
}

.ev-otro-item.is-resuelto {
    opacity: 0.6;
    border-left-color: var(--verde);
}

.ev-otro-titulo {
    font-size: 11px;
    font-weight: 600;
    color: var(--gris-900);
    margin-bottom: 2px;
}

.ev-otro-subtitulo {
    font-size: 9px;
    color: var(--gris-500);
    margin-bottom: 2px;
}

.ev-otro-fecha {
    font-size: 10px;
    color: var(--gris-500);
}

/* Scrollbar */
.ev-list::-webkit-scrollbar,
.ev-drawer-body::-webkit-scrollbar {
    width: 5px;
}

.ev-list::-webkit-scrollbar-track,
.ev-drawer-body::-webkit-scrollbar-track {
    background: var(--gris-100);
}

.ev-list::-webkit-scrollbar-thumb,
.ev-drawer-body::-webkit-scrollbar-thumb {
    background: var(--gris-300);
    border-radius: 3px;
}

/* ============================================
   🔥 MODAL VISTA ANUAL - FULLSCREEN
   ============================================ */
.ev-modal-anual {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.ev-modal-anual.show {
    display: flex;
}

.ev-modal-anual-content {
    background: white;
    border-radius: 8px;
    width: 100%;
    max-width: 1400px;
    max-height: 95vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

/* 🔥 HEADER GRIS Y DELGADO */
.ev-anual-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 16px;
    background: var(--gris-100);
    border-bottom: 1px solid var(--gris-300);
}

.ev-anual-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--gris-800);
    display: flex;
    align-items: center;
    gap: 6px;
}

.ev-anual-title-icon {
    font-size: 16px;
}

.ev-anual-nav {
    display: flex;
    gap: 4px;
    align-items: center;
}

.ev-anual-btn {
    width: 28px;
    height: 28px;
    border: 1px solid var(--gris-300);
    background: white;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gris-600);
    font-size: 11px;
    transition: all 0.15s;
}

.ev-anual-btn:hover {
    background: var(--gris-50);
    border-color: var(--gris-400);
}

.ev-anual-close {
    width: 28px;
    height: 28px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 18px;
    color: var(--gris-500);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
}

.ev-anual-close:hover {
    background: var(--gris-200);
}

/* Body scrollable */
.ev-anual-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
}

/* Grid de 12 meses */
.ev-anual-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

@media (max-width: 1200px) {
    .ev-anual-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 800px) {
    .ev-anual-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 500px) {
    .ev-anual-grid {
        grid-template-columns: 1fr;
    }
}

/* Cada mes mini */
.ev-mes-mini {
    background: white;
    border: 1px solid var(--gris-200);
    border-radius: 6px;
    padding: 10px;
    transition: all 0.15s;
}

.ev-mes-mini:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.ev-mes-mini-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 6px;
    margin-bottom: 6px;
    background: var(--gris-50);
    border-radius: 3px;
}

.ev-mes-mini-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--gris-800);
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.ev-mes-mini-stats {
    display: flex;
    gap: 3px;
}

.ev-mes-mini-stat {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 16px;
    height: 16px;
    padding: 0 4px;
    border-radius: 8px;
    font-size: 8px;
    font-weight: 700;
    color: white;
}

.ev-mes-mini-stat.vencido { background: var(--rojo); }
.ev-mes-mini-stat.pendiente { background: var(--amarillo); color: #92400e; }
.ev-mes-mini-stat.resuelto { background: var(--verde); }

/* Días de la semana mini */
.ev-mes-mini-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 3px;
}

.ev-mes-mini-wd {
    text-align: center;
    font-size: 7px;
    font-weight: 600;
    color: var(--gris-400);
    padding: 2px 0;
    text-transform: uppercase;
}

/* Grid de días mini */
.ev-mes-mini-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.ev-mes-mini-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
    cursor: pointer;
    position: relative;
    font-size: 9px;
    color: var(--gris-600);
    transition: all 0.1s;
    min-height: 24px;
    background: var(--gris-50);
}

.ev-mes-mini-day:hover {
    background: var(--azul-100);
    transform: scale(1.08);
    z-index: 1;
}

.ev-mes-mini-day.other {
    color: var(--gris-300);
    background: transparent;
}

.ev-mes-mini-day.today {
    background: var(--azul-500);
    color: white;
    font-weight: 700;
    box-shadow: 0 1px 3px rgba(37, 99, 235, 0.4);
}

.ev-mes-mini-day.selected {
    background: var(--azul-700);
    color: white;
    font-weight: 700;
}

/* Indicadores de eventos en día mini */
.ev-mes-mini-day.has-eventos {
    font-weight: 600;
}

.ev-mes-mini-day.has-vencido {
    background: #fee2e2;
    color: var(--rojo);
    font-weight: 700;
}

.ev-mes-mini-day.has-vencido:hover {
    background: #fecaca;
}

.ev-mes-mini-day.has-pendiente {
    background: #fef3c7;
    color: #92400e;
}

.ev-mes-mini-day.has-pendiente:hover {
    background: #fde68a;
}

.ev-mes-mini-day.has-resuelto:not(.has-vencido):not(.has-pendiente) {
    background: #dcfce7;
    color: var(--verde);
}

.ev-mes-mini-day.has-resuelto:hover {
    background: #bbf7d0;
}

/* Contador de eventos en día */
.ev-mes-mini-count {
    position: absolute;
    top: -2px;
    right: -2px;
    min-width: 12px;
    height: 12px;
    padding: 0 3px;
    border-radius: 6px;
    font-size: 7px;
    font-weight: 700;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.ev-mes-mini-count.vencido { background: var(--rojo); }
.ev-mes-mini-count.pendiente { background: var(--amarillo); color: #92400e; }
.ev-mes-mini-count.resuelto { background: var(--verde); }

/* Footer con leyenda y stats */
.ev-anual-footer {
    padding: 12px 16px;
    background: var(--gris-50);
    border-top: 1px solid var(--gris-200);
}

.ev-anual-legend {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-bottom: 12px;
}

.ev-legend-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 9px;
    color: var(--gris-600);
}

.ev-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 2px;
}

.ev-legend-dot.vencido { background: #fee2e2; border: 1px solid var(--rojo); }
.ev-legend-dot.pendiente { background: #fef3c7; border: 1px solid var(--amarillo); }
.ev-legend-dot.resuelto { background: #dcfce7; border: 1px solid var(--verde); }
.ev-legend-dot.today { background: var(--azul-500); }

/* Resumen anual */
.ev-anual-summary {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.ev-summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.ev-summary-num {
    font-size: 20px;
    font-weight: 800;
    line-height: 1;
}

.ev-summary-num.total { color: var(--gris-700); }
.ev-summary-num.vencido { color: var(--rojo); }
.ev-summary-num.pendiente { color: var(--amarillo); }
.ev-summary-num.resuelto { color: var(--verde); }

.ev-summary-label {
    font-size: 8px;
    font-weight: 600;
    color: var(--gris-500);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* Responsive */
@media (max-width: 900px) {
    .ev-main {
        grid-template-columns: 1fr;
    }
    
    .ev-cal {
        max-width: 320px;
    }
    
    .ev-drawer {
        width: 100%;
    }
}
</style>
<div class="ev-container">
    <div class="ev-main">
        <!-- CALENDARIO -->
        <div class="ev-cal" id="evCal">
            <div class="ev-cal-header">
                <h2 class="ev-cal-title" id="calTitle">Enero 2026</h2>
                <div class="ev-cal-nav">
                    <button class="ev-cal-btn" id="btnPrev">◀</button>
                    <button class="ev-cal-btn" id="btnNext">▶</button>
                </div>
            </div>
            <div class="ev-cal-body">
                <div class="ev-cal-weekdays">
                    <div class="ev-cal-wd">L</div>
                    <div class="ev-cal-wd">M</div>
                    <div class="ev-cal-wd">X</div>
                    <div class="ev-cal-wd">J</div>
                    <div class="ev-cal-wd">V</div>
                    <div class="ev-cal-wd">S</div>
                    <div class="ev-cal-wd">D</div>
                </div>
                <div class="ev-cal-days" id="calDays"></div>
            </div>
            <div class="ev-cal-past" id="calPast" style="display:none;">
                <h4 class="ev-cal-past-title">Eventos vencidos</h4>
                <div id="calPastList"></div>
            </div>
        </div>

        <!-- PANEL DE EVENTOS -->
        <div class="ev-panel">
            <div class="ev-panel-header">
                <h2 class="ev-panel-title" id="panelTitle">Próximos eventos</h2>
                <div style="display:flex; gap:8px; align-items:center;">
                    <!-- 🔥 STATS COMPACTAS CON ÍCONOS -->
                    <div class="ev-stats">
                        <div class="ev-stat resuelto">
                            <span class="ev-stat-icon">✓</span>
                            <span class="ev-stat-num" id="statResuelto">0</span>
                            <span class="ev-stat-label">resueltos</span>
                        </div>
                        <div class="ev-stat pendiente">
                            <span class="ev-stat-icon">⏰</span>
                            <span class="ev-stat-num" id="statPendiente">0</span>
                            <span class="ev-stat-label">próximos</span>
                        </div>
                        <div class="ev-stat vencido">
                            <span class="ev-stat-icon">⚠</span>
                            <span class="ev-stat-num" id="statVencido">0</span>
                            <span class="ev-stat-label">atrasados</span>
                        </div>
                        <div class="ev-stat total">
                            <span class="ev-stat-icon">📊</span>
                            <span class="ev-stat-num" id="statTotal">0</span>
                            <span class="ev-stat-label">total</span>
                        </div>
                    </div>
                    <button class="ev-btn-vista-anual" id="btnVistaAnual">📅 Vista Anual</button>
                    <button class="ev-btn-nuevo" id="btnNuevoEvento">+ Nuevo</button>
                </div>
            </div>
            <div class="ev-tabs">
                <div class="ev-tab active" data-tab="proximos">Próximos<span class="ev-tab-count" id="tabProxCount">0</span></div>
                <div class="ev-tab" data-tab="vencidos">Vencidos<span class="ev-tab-count" id="tabVencCount">0</span></div>
                <div class="ev-tab" data-tab="fecha">Por fecha</div>
                <div class="ev-tab" data-tab="generaciones">Generaciones<span class="ev-tab-count" id="tabGenCount">0</span></div>
                <div class="ev-tab" data-tab="p100c">P100C<span class="ev-tab-count" id="tabP100CCount">0</span></div>
                <div class="ev-tab" data-tab="cobranza">Cobranza<span class="ev-tab-count" id="tabCobranzaCount">0</span></div>
                <div class="ev-tab" data-tab="plantillas">Plantillas<span class="ev-tab-count" id="tabPlantillasCount">0</span></div>
            </div>
            <div class="ev-list" id="evList">
                <div class="ev-empty">
                    <div class="ev-empty-icon">📅</div>
                    <p class="ev-empty-text">Cargando eventos...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔥 MODAL VISTA ANUAL -->
<div class="ev-modal-anual" id="evModalAnual">
    <div class="ev-modal-anual-content">
        <div class="ev-anual-header">
            <h2 class="ev-anual-title">
                <span class="ev-anual-title-icon">📅</span>
                <span id="anualTitle">2026</span>
            </h2>
            <div class="ev-anual-nav">
                <button class="ev-anual-btn" id="btnAnualPrev" title="Año anterior">◀</button>
                <button class="ev-anual-btn" id="btnAnualNext" title="Año siguiente">▶</button>
                <button class="ev-anual-close" id="btnAnualClose" title="Cerrar">×</button>
            </div>
        </div>
        <div class="ev-anual-body">
            <div class="ev-anual-grid" id="anualGrid">
                <!-- Se genera dinámicamente -->
            </div>
        </div>
        <div class="ev-anual-footer">
            <div class="ev-anual-legend">
                <div class="ev-legend-item">
                    <div class="ev-legend-dot today"></div>
                    <span>Hoy</span>
                </div>
                <div class="ev-legend-item">
                    <div class="ev-legend-dot vencido"></div>
                    <span>Vencidos</span>
                </div>
                <div class="ev-legend-item">
                    <div class="ev-legend-dot pendiente"></div>
                    <span>Pendientes</span>
                </div>
                <div class="ev-legend-item">
                    <div class="ev-legend-dot resuelto"></div>
                    <span>Resueltos</span>
                </div>
            </div>
            <div class="ev-anual-summary">
                <div class="ev-summary-item">
                    <span class="ev-summary-num total" id="anualTotal">0</span>
                    <span class="ev-summary-label">Total</span>
                </div>
                <div class="ev-summary-item">
                    <span class="ev-summary-num vencido" id="anualVencidos">0</span>
                    <span class="ev-summary-label">Vencidos</span>
                </div>
                <div class="ev-summary-item">
                    <span class="ev-summary-num pendiente" id="anualPendientes">0</span>
                    <span class="ev-summary-label">Pendientes</span>
                </div>
                <div class="ev-summary-item">
                    <span class="ev-summary-num resuelto" id="anualResueltos">0</span>
                    <span class="ev-summary-label">Resueltos</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DRAWER DETALLE/EDICIÓN -->
<div class="ev-overlay" id="evOverlay"></div>
<div class="ev-drawer" id="evDrawer">
    <div class="ev-drawer-header">
        <h3 class="ev-drawer-title" id="drawerTitle">Detalle del evento</h3>
        <div class="ev-drawer-actions">
            <button class="ev-drawer-btn" id="btnEdit" title="Editar">✎</button>
            <button class="ev-drawer-btn danger" id="btnDelete" title="Eliminar">🗑</button>
            <button class="ev-drawer-close" id="btnClose">×</button>
        </div>
    </div>
    <div class="ev-drawer-body" id="evDrawerBody">
        <!-- Contenido dinámico -->
    </div>
    <div class="ev-drawer-footer" id="evDrawerFooter" style="display:none;">
        <button class="ev-btn ev-btn-secondary" id="btnCancelar">Cancelar</button>
        <button class="ev-btn ev-btn-primary" id="btnGuardar">Guardar</button>
    </div>
</div>