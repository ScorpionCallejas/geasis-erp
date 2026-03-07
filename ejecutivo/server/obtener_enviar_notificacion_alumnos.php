<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$alumnos = $_POST['alumnos'];

// OBTENER DATOS DEL PLANTEL PARA LOGO DINÁMICO
$sqlPlantel = "SELECT * FROM plantel WHERE id_pla = '$plantel'";
$datosPlantel = obtener_datos_consulta($db, $sqlPlantel)['datos'];
$titulo_escuela = strtoupper($datosPlantel['nom_pla'] . ' - ' . $datosPlantel['des_pla']);
$logo_archivo = "../img/" . $datosPlantel['fot_pla'];
?>

<!-- ========================================
     CSS OPTIMIZADO PARA VARIABLES
========================================= -->
<style>
/* LAYOUT PRINCIPAL - GRID 3 COLUMNAS */
.notificacion-layout {
    display: grid;
    grid-template-columns: 200px 1fr 300px;
    gap: 24px;
    min-height: 500px;
    max-height: 70vh;
}

.alumnos-sidebar {
    display: flex;
    flex-direction: column;
}

.formulario-central {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.preview-section {
    width: 300px;
    flex-shrink: 0;
}

/* FORMULARIO */
.form-group {
    margin-bottom: 12px;
}

.form-label {
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
    display: block;
}

.form-control {
    font-size: 13px;
    padding: 8px 10px;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    font-weight: 500;
    width: 100%;
    transition: border-color 0.1s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: none;
    outline: none;
}

/* TEXTAREA MENSAJE */
#mensaje_notificacion {
    resize: vertical;
    min-height: 80px;
    max-height: 120px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.4;
}

/* VARIABLES DINÁMICAS */
.variables-container {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    padding: 8px;
    margin-bottom: 8px;
}

.variables-title {
    font-size: 8px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    margin-bottom: 6px;
    font-weight: 600;
}

.variables-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.variable-btn {
    background: #495057;
    color: white;
    border: none;
    border-radius: 2px;
    padding: 4px 8px;
    font-size: 9px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    cursor: pointer;
    transition: all 0.1s ease;
}

.variable-btn:hover {
    background: #007bff;
    transform: translateY(-1px);
}

.variable-btn:active {
    transform: translateY(0);
}

/* PLANTILLAS */
.plantillas-container {
    max-height: 120px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    padding: 8px;
    background: #f8f9fa;
}

.plantillas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 6px;
}

.plantilla-btn {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    padding: 8px 10px;
    font-size: 10px;
    font-weight: 500;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    cursor: pointer;
    transition: all 0.1s ease;
    text-align: left;
}

.plantilla-btn:hover {
    border-color: #007bff;
    background: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.plantilla-btn.selected {
    border-color: #007bff;
    background: #007bff;
    color: white;
}

.plantilla-btn .titulo {
    font-weight: 600;
    margin-bottom: 2px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.plantilla-btn .preview {
    font-size: 10px;
    opacity: 0.7;
    line-height: 1.2;
    margin-top: 2px;
}

/* PREVIEW MÓVIL */
.mobile-preview {
    background: #495057;
    border-radius: 18px;
    padding: 12px;
    height: 100%;
    position: relative;
}

.mobile-screen {
    background: #000;
    border-radius: 15px;
    height: 100%;
    padding: 0;
    position: relative;
    overflow: hidden;
}

.mobile-header {
    background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    padding: 6px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    font-size: 7px;
    font-weight: 600;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.mobile-time {
    font-family: 'Courier New', monospace;
}

.mobile-indicators {
    display: flex;
    gap: 2px;
    align-items: center;
}

/* SELECTOR DE ALUMNO PARA PREVIEW */
.preview-selector {
    padding: 8px;
    background: rgba(255,255,255,0.1);
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.preview-selector select {
    background: rgba(0,0,0,0.3);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 2px;
    padding: 4px 6px;
    font-size: 8px;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

.preview-selector select option {
    background: #333;
    color: white;
}

.notification-preview {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    padding: 8px;
    margin: 12px 8px;
    transition: all 0.2s ease;
}

.notification-preview.empty {
    opacity: 0.5;
    border-style: dashed;
}

.notification-header {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
}

.app-icon {
    width: 30px;
    height: 30px;
    background: #007bff;
    border-radius: 2px;
    margin-right: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7px;
    color: white;
    font-weight: 600;
    overflow: hidden;
    position: relative;
}

.app-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 1px;
}

.notification-title {
    font-size: 8px;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    flex: 1;
}

.notification-time {
    font-size: 6px;
    color: #6c757d;
    font-family: 'Courier New', monospace;
}

.notification-body {
    font-size: 9px;
    color: #495057;
    line-height: 1.3;
    margin-bottom: 4px;
}

.notification-actions {
    display: flex;
    gap: 4px;
}

.notification-action {
    background: #e9ecef;
    border: none;
    border-radius: 2px;
    padding: 2px 4px;
    font-size: 6px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

/* BADGES ALUMNOS */
.alumnos-container {
    border: 1px solid #e9ecef;
    border-radius: 2px;
    background: #f8f9fa;
    flex: 1;
}

.alumnos-grid {
    display: flex;
    flex-direction: column;
    gap: 4px;
    height: 100%;
    overflow-y: auto;
    padding: 8px;
    max-height: 400px;
}

.alumno-badge {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    padding: 6px 8px;
    font-size: 10px;
    font-weight: 500;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    min-height: 28px;
}

.alumno-badge .badge-right {
    display: flex;
    align-items: center;
    gap: 4px;
}

.alumno-badge .token-status {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}

.alumno-badge .token-app-si {
    background: #28a745;
}

.alumno-badge .token-app-no {
    background: #dc3545;
}

/* CONTADOR CARACTERES */
.char-counter {
    font-size: 8px;
    color: #6c757d;
    text-align: right;
    margin-top: 2px;
    font-family: 'Courier New', monospace;
}

.char-counter.warning {
    color: #ffc107;
}

.char-counter.danger {
    color: #dc3545;
    font-weight: 600;
}

/* LEYENDA */
.app-status-legend {
    margin-top: 8px;
    padding: 8px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 2px;
    font-size: 7px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    text-align: center;
}

.legend-items {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 6px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 4px;
    justify-content: center;
}

.legend-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
}

.legend-dot.app-instalada {
    background: #28a745;
}

.legend-dot.app-no-instalada {
    background: #dc3545;
}

.legend-text {
    font-size: 7px;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

.legend-note {
    font-size: 6px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    border-top: 1px solid #e9ecef;
    padding-top: 6px;
}

.seccion-titulo {
    font-size: 10px;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 8px;
    padding-bottom: 4px;
    border-bottom: 1px solid #e9ecef;
}

/* BOTÓN ENVIAR */
#btn_enviar_notificaciones {
    background: #007bff;
    border-color: #007bff;
    color: white;
    min-width: 140px;
}

#btn_enviar_notificaciones:hover:not(:disabled) {
    background: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 1px 3px rgba(0,123,255,0.2);
}

#btn_enviar_notificaciones:disabled {
    background: #6c757d;
    border-color: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

/* SCROLLBARS */
.alumnos-grid::-webkit-scrollbar,
.plantillas-container::-webkit-scrollbar {
    width: 4px;
}

.alumnos-grid::-webkit-scrollbar-track,
.plantillas-container::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.alumnos-grid::-webkit-scrollbar-thumb,
.plantillas-container::-webkit-scrollbar-thumb {
    background: #e9ecef;
    border-radius: 2px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .notificacion-layout {
        grid-template-columns: 1fr;
        grid-template-rows: 200px 1fr 300px;
        gap: 12px;
        height: 600px;
    }
    
    .alumnos-grid {
        flex-direction: row;
        flex-wrap: wrap;
        height: 150px;
    }
    
    .alumno-badge {
        width: auto;
        min-width: 120px;
    }
}
/* TRACKING OVERLAY - ESTILO MODERNO AHJ */
.tracking-overlay {
    position: fixed;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    width: 420px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.1);
    z-index: 9999;
    display: none;
    overflow: hidden;
}
.tracking-overlay.active {
    display: block;
    animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
@keyframes slideIn {
    from { transform: translateY(-50%) translateX(440px); opacity: 0; }
    to { transform: translateY(-50%) translateX(0); opacity: 1; }
}
.tracking-overlay-header {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tracking-overlay-title {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tracking-overlay-close {
    background: rgba(255,255,255,0.15);
    border: none;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 20px;
    line-height: 1;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tracking-overlay-close:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.05);
}
.tracking-overlay-body {
    max-height: 500px;
    overflow-y: auto;
}
.tracking-status-bar {
    background: linear-gradient(to bottom, #f8fafc 0%, #f1f5f9 100%);
    padding: 12px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tracking-status-text {
    font-size: 11px;
    color: #64748b;
    font-weight: 500;
}
.tracking-status-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #3b82f6;
    font-weight: 600;
}
.tracking-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #3b82f6;
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.2); }
}
.tracking-table-container {
    padding: 0;
}
.tracking-table {
    width: 100%;
    border-collapse: collapse;
}
.tracking-table thead {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}
.tracking-table th {
    padding: 12px 20px;
    text-align: left;
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}
.tracking-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.tracking-table tbody tr:hover {
    background: #fafbfc;
}
.tracking-table tbody tr:last-child {
    border-bottom: none;
}
.tracking-table td {
    padding: 14px 20px;
    font-size: 13px;
}
.tracking-nombre {
    font-weight: 600;
    color: #1e293b;
}
.tracking-mensaje {
    color: #64748b;
    font-size: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
.tracking-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    gap: 5px;
}
.tracking-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: block;
}
.tracking-badge.pendiente {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #fbbf24;
}
.tracking-badge.pendiente::before {
    background: #f59e0b;
}
.tracking-badge.enviada {
    background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
    color: #1e40af;
    border: 1px solid #3b82f6;
}
.tracking-badge.enviada::before {
    background: #2563eb;
}
.tracking-badge.leida {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #34d399;
}
.tracking-badge.leida::before {
    background: #10b981;
}
.tracking-empty {
    padding: 60px 20px;
    text-align: center;
    color: #94a3b8;
}
.tracking-empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.3;
}
.tracking-empty-text {
    font-size: 13px;
    font-weight: 500;
}
</style>

<!-- CONTENIDO DINÁMICO - LAYOUT 3 COLUMNAS COMPLETO -->
<div class="notificacion-layout">
    <!-- SIDEBAR IZQUIERDO - ALUMNOS -->
    <div class="alumnos-sidebar">
        <div class="seccion-titulo">DESTINATARIOS</div>
        <div class="alumnos-container">
            <div class="alumnos-grid" id="alumnos-grid">
                <?php  
                    for($i = 0; $i < sizeof($alumnos); $i++){
                        // Query para obtener datos del alumno CON ADEUDOS ESPECÍFICOS
                        $sqlAlumno = "
                            SELECT 
                                ar.id_alu_ram,
                                g.nom_gen,
                                a.nom_alu, a.app_alu, a.cor_alu, a.id_alu,
                                r.nom_ram,
                                obtener_adeudo_alumno_tipo(ar.id_alu_ram, 'Colegiatura') AS adeudo_colegiatura,
                                obtener_adeudo_alumno_tipo(ar.id_alu_ram, 'Inscripción') AS adeudo_inscripcion,
                                obtener_adeudo_alumno_tipo(ar.id_alu_ram, 'Reinscripción') AS adeudo_reinscripcion,
                                obtener_adeudo_alumno_tipo(ar.id_alu_ram, 'Otros') AS adeudo_tramites
                            FROM alu_ram ar
                            INNER JOIN generacion g ON g.id_gen = ar.id_gen1
                            INNER JOIN alumno a ON a.id_alu = ar.id_alu1
                            INNER JOIN rama r ON r.id_ram = ar.id_ram3
                            WHERE ar.id_alu_ram = '$alumnos[$i]'
                        ";

                        $resultadoAlumno = mysqli_query($db, $sqlAlumno);
                        
                        if ($resultadoAlumno && mysqli_num_rows($resultadoAlumno) > 0) {
                            $filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
                            
                            // Verificar push token
                            $sqlToken = "SELECT token FROM alumno_token WHERE alumno = '".$filaAlumno['id_alu']."' ORDER BY id DESC LIMIT 1";
                            $resultToken = mysqli_query($db, $sqlToken);
                            $tieneToken = ($resultToken && mysqli_num_rows($resultToken) > 0);
                ?>
                            <div class="alumno-badge seleccionAlumnos" 
                                 data-id="<?php echo $filaAlumno['id_alu']; ?>" 
                                 data-app="<?php echo $tieneToken ? '1' : '0'; ?>"
                                 id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>"
                                 id_alu="<?php echo $filaAlumno['id_alu']; ?>" 
                                 data-nombre="<?php echo htmlspecialchars($filaAlumno['nom_alu']); ?>"
                                 data-apellido="<?php echo htmlspecialchars($filaAlumno['app_alu']); ?>"
                                 data-programa="<?php echo htmlspecialchars($filaAlumno['nom_ram']); ?>"
                                 data-generacion="<?php echo htmlspecialchars($filaAlumno['nom_gen']); ?>"
                                 data-correo="<?php echo htmlspecialchars($filaAlumno['cor_alu']); ?>"
                                 data-adeudo-colegiatura="<?php echo $filaAlumno['adeudo_colegiatura']; ?>"
                                 data-adeudo-inscripcion="<?php echo $filaAlumno['adeudo_inscripcion']; ?>"
                                 data-adeudo-reinscripcion="<?php echo $filaAlumno['adeudo_reinscripcion']; ?>"
                                 data-adeudo-tramites="<?php echo $filaAlumno['adeudo_tramites']; ?>"
                                 title="<?php echo $filaAlumno['nom_alu'].' '.$filaAlumno['app_alu']; ?>">
                                <span class="badge-name"><?php echo substr($filaAlumno['nom_alu'].' '.$filaAlumno['app_alu'], 0, 15); ?></span>
                                <div class="badge-right">
                                    <span class="token-status <?php echo $tieneToken ? 'token-app-si' : 'token-app-no'; ?>"></span>
                                </div>
                            </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
        <div class="app-status-legend">
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-dot app-instalada"></div>
                    <span class="legend-text">CON APP</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot app-no-instalada"></div>
                    <span class="legend-text">SIN APP</span>
                </div>
            </div>
            <div class="legend-note">
                SOLO CON APP RECIBEN NOTIFICACIÓN
            </div>
        </div>
    </div>

    <!-- CENTRO - PLANTILLAS + FORMULARIO -->
    <div class="formulario-central">
        <!-- PLANTILLAS -->
        <div class="form-group">
            <label class="form-label">PLANTILLAS RÁPIDAS</label>
            <div class="plantillas-container">
                <div class="plantillas-grid" id="plantillas-grid"></div>
            </div>
        </div>
        
        <!-- TÍTULO -->
        <div class="form-group">
            <label class="form-label" for="titulo_notificacion">TÍTULO</label>
            <input type="text" 
                   id="titulo_notificacion" 
                   class="form-control" 
                   placeholder="Título de la notificación"
                   maxlength="50"
                   value="NOTIFICACIÓN ACADÉMICA">
        </div>
        
        <!-- VARIABLES DINÁMICAS -->
        <div class="form-group">
            <label class="form-label">VARIABLES DINÁMICAS</label>
            <div class="variables-container">
                <div class="variables-title">HAZ CLIC PARA INSERTAR:</div>
                <div class="variables-grid" id="variables-grid"></div>
            </div>
        </div>
        
        <!-- MENSAJE -->
        <div class="form-group">
            <label class="form-label" for="mensaje_notificacion">MENSAJE</label>
            <textarea id="mensaje_notificacion" 
                      class="form-control" 
                      placeholder="Escribe tu mensaje usando variables como {alumno}, {programa}, {adeudo_colegiatura}, etc..."
                      maxlength="160"></textarea>
            <div class="char-counter" id="char-counter">0/160</div>
        </div>
    </div>
    
    <!-- DERECHA - PREVIEW -->
    <div class="preview-section">
        <div class="mobile-preview">
            <div class="mobile-screen">
                <div class="mobile-header">
                    <div class="mobile-time" id="mobile-time">14:46</div>
                    <div class="mobile-indicators">
                        <span style="font-size: 6px;">🔋</span>
                        <span style="font-size: 6px;">📶</span>
                    </div>
                </div>
                
                <!-- SELECTOR DE ALUMNO PARA PREVIEW -->
                <div class="preview-selector">
                    <select id="alumno-preview-selector">
                        <option value="0">Ejemplo con primer alumno</option>
                    </select>
                </div>
                
                <div class="notification-preview" id="notification-preview">
                    <div class="notification-header">
						<div class="app-icon" id="app-icon" style="
							width: 45px !important;
							height: 45px !important;
							border-radius: 8px !important;
							overflow: hidden !important;
							background: transparent !important;
							flex-shrink: 0 !important;
						">
							<!-- SOLO EL LOGO, SIN FONDO AZUL QUE LO JODA -->
							<img src="<?php echo $logo_archivo; ?>" alt="Logo" style="
								width: 100% !important;
								height: 100% !important;
								object-fit: cover !important;
								display: block !important;
								border: none !important;
							" onerror="this.style.display='none';" />
						</div>
                        <div class="notification-title" id="preview-titulo">NOTIFICACIÓN ACADÉMICA</div>
                        <div class="notification-time" id="preview-time">AHORA</div>
                    </div>
                    <div class="notification-body" id="preview-mensaje">
                        Selecciona una plantilla o escribe un mensaje con variables
                    </div>
                    <div class="notification-actions">
                        <button class="notification-action">VER</button>
                        <button class="notification-action">CERRAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- TRACKING OVERLAY -->
<div class="tracking-overlay" id="tracking_overlay">
    <div class="tracking-overlay-header">
        <span class="tracking-overlay-title">
            📊 Seguimiento de Envío
        </span>
        <button class="tracking-overlay-close" onclick="cerrarTracking()">×</button>
    </div>
    <div class="tracking-status-bar">
        <span class="tracking-status-text">Actualización automática cada 5 segundos</span>
        <div class="tracking-status-indicator">
            <span class="tracking-status-dot"></span>
            EN VIVO
        </div>
    </div>
    <div class="tracking-overlay-body">
        <div class="tracking-table-container">
            <table class="tracking-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Título</th>
                        <th style="text-align: center;">Estado</th>
                    </tr>
                </thead>
                <tbody id="tracking_tbody">
                    <!-- Filas dinámicas -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JAVASCRIPT CON VARIABLES DINÁMICAS -->
<script>
/**
 * SISTEMA DE NOTIFICACIONES CON VARIABLES DINÁMICAS
 * Preview inteligente con datos reales de alumnos
 */

// VARIABLES GLOBALES
var envioActivo = false;
var alumnosNotificacion = [];
var plantillaActual = null;
var contadorTiempo = null;
var alumnoPreviewActual = 0; // Índice del alumno para preview

// DATOS DEL PLANTEL
var logoArchivo = '<?php echo $logo_archivo; ?>';
var tituloEscuela = '<?php echo $titulo_escuela; ?>';

// PLANTILLAS CON VARIABLES DINÁMICAS INCLUYENDO ADEUDOS ESPECÍFICOS
var PLANTILLAS = {
    recordatorio_pago: {
        titulo: "✨ RECORDATORIO PAGO",
        mensaje: "⚠️ Hola {alumno}, tienes pagos pendientes en {programa}. Total adeudo: ${adeudo_colegiatura}",
        icono: "✨"
    },
    vencimiento_proximo: {
        titulo: "⏰ VENCIMIENTO PRÓXIMO", 
        mensaje: "⚠️ Estimado {alumno}, tu pago de colegiatura ${adeudo_colegiatura} vence pronto. Evita recargos.",
        icono: "⏰"
    },
    inscripcion_materias: {
        titulo: "✅ INSCRIPCIÓN EXITOSA",
        mensaje: "⭐ {alumno}, a partir de este momento puedes comenzar a realizar tus actividades",
        icono: "✅"
    },
    certificado_listo: {
        titulo: "✅ CERTIFICADO DISPONIBLE",
        mensaje: "⭐ {alumno}, tu certificado de {programa} está listo. Trámites pendientes: ${adeudo_tramites}",
        icono: "✅"
    },
    expediente_completo: {
        titulo: "✅ EXPEDIENTE COMPLETO",
        mensaje: "✅ {alumno}, tu expediente de {programa} ha sido completado exitosamente.",
        icono: "✅"
    },
    plantel_cerrado: {
        titulo: "❌ PLANTEL CERRADO",
        mensaje: "⚠️ Estimado {alumno}, el plantel permanecerá cerrado mañana {fecha}.",
        icono: "❌"
    },
    suspension_clases: {
        titulo: "❌ SIN CLASES",
        mensaje: "⚠️ {alumno}, no habrá clases de {programa} el día {fecha}.",
        icono: "❌"
    },
    aviso_general: {
        titulo: "ℹ️ AVISO ACADÉMICO",
        mensaje: "ℹ️ {alumno}, tienes información importante sobre {programa} en tu centro de estudios.",
        icono: "ℹ️"
    },
    felicitaciones: {
        titulo: "✨ ¡FELICITACIONES!",
        mensaje: "⭐ ¡Felicidades {alumno}! Has completado exitosamente {programa}.",
        icono: "✨"
    },
    recordatorio_reinscripcion: {
        titulo: "✅ REINSCRIPCIÓN DISPONIBLE",
        mensaje: "✅ {alumno}, ya puedes realizar tu reinscripción. Adeudo: ${adeudo_reinscripcion}",
        icono: "✅"
    },
    adeudo_tramites: {
        titulo: "ℹ️ TRÁMITES PENDIENTES",
        mensaje: "ℹ️ {alumno}, tienes trámites pendientes por ${adeudo_tramites}. Regulariza tu situación.",
        icono: "ℹ️"
    },
    alumno_bloqueado: {
        titulo: "❌ ALUMNO BLOQUEADO",
        mensaje: "ℹ️ El alumno con correo {correo} ha sido BLOQUEADO. Favor de asistir o comunicarse con su centro para regularizar su situación.",
        icono: "❌"
    },
    alumno_desbloqueado: {
        titulo: "✅ ALUMNO DESBLOQUEADO",
        mensaje: "⭐ El alumno con correo {correo} ha sido DESBLOQUEADO. Favor de asistir o comunicarse con su centro para regularizar su situación.",
        icono: "✅"
    }
};



// Hacer disponibles globalmente si no existen
if (!window.PLANTILLAS) window.PLANTILLAS = PLANTILLAS;
if (!window.VARIABLES_DISPONIBLES) window.VARIABLES_DISPONIBLES = VARIABLES_DISPONIBLES;

// VARIABLES DISPONIBLES INCLUYENDO ADEUDOS ESPECÍFICOS
var VARIABLES_DISPONIBLES = [
    { codigo: '{alumno}', descripcion: 'Nombre completo del alumno' },
    { codigo: '{programa}', descripcion: 'Programa académico' },
    { codigo: '{grupo}', descripcion: 'Grupo/Generación' },
    { codigo: '{fecha}', descripcion: 'Fecha actual' },
    { codigo: '{correo}', descripcion: 'Correo electrónico' },
    { codigo: '{adeudo_colegiatura}', descripcion: 'Adeudo de colegiatura' },
    { codigo: '{adeudo_inscripcion}', descripcion: 'Adeudo de inscripción' },
    { codigo: '{adeudo_reinscripcion}', descripcion: 'Adeudo de reinscripción' },
    { codigo: '{adeudo_tramites}', descripcion: 'Adeudo de trámites' }
];

/**
 * Extraer datos reales de alumnos desde DOM INCLUYENDO ADEUDOS
 */
function extraerDatosAlumnos() {
    var alumnosData = [];
    
    $('.seleccionAlumnos').each(function() {
        var $badge = $(this);
        var alumno = {
            id_alu: $badge.attr('id_alu'),
            id_alu_ram: $badge.attr('id_alu_ram'),
            nombre_completo: $badge.attr('title') || $badge.text(),
            nombre: $badge.data('nombre') || '',
            apellido: $badge.data('apellido') || '',
            programa: $badge.data('programa') || '',
            generacion: $badge.data('generacion') || '',
            correo: $badge.data('correo') || '',
            tieneToken: $badge.data('app') === 1,
            // NUEVOS ADEUDOS ESPECÍFICOS
            adeudo_colegiatura: parseFloat($badge.data('adeudo-colegiatura')) || 0,
            adeudo_inscripcion: parseFloat($badge.data('adeudo-inscripcion')) || 0,
            adeudo_reinscripcion: parseFloat($badge.data('adeudo-reinscripcion')) || 0,
            adeudo_tramites: parseFloat($badge.data('adeudo-tramites')) || 0
        };
        alumnosData.push(alumno);
    });
    
    // Actualizar array global con datos reales
    alumnosNotificacion = alumnosData;
    console.log('📊 DATOS EXTRAÍDOS CON ADEUDOS:', alumnosNotificacion);
}

/**
 * Generar plantillas con variables
 */
function generarPlantillas() {
    const container = $('#plantillas-grid');
    
    Object.entries(PLANTILLAS).forEach(([key, plantilla]) => {
        var btn = $('<div class="plantilla-btn" data-plantilla="' + key + '">' +
            '<div class="titulo">' +
                '<span>' + plantilla.titulo + '</span>' +
                '<span>' + plantilla.icono + '</span>' +
            '</div>' +
            '<div class="preview">' + plantilla.mensaje.substring(0, 50) + '...</div>' +
        '</div>');
        container.append(btn);
    });
}

/**
 * Generar botones de variables INCLUYENDO ADEUDOS
 */
function generarVariables() {
    const container = $('#variables-grid');
    
    VARIABLES_DISPONIBLES.forEach(variable => {
        var btn = $('<button class="variable-btn" data-variable="' + variable.codigo + '" title="' + variable.descripcion + '">' +
            variable.codigo +
        '</button>');
        container.append(btn);
    });
}

/**
 * Generar selector de alumno para preview
 */
function generarSelectorPreview() {
    const selector = $('#alumno-preview-selector');
    selector.empty();
    
    alumnosNotificacion.forEach((alumno, index) => {
        var option = $('<option value="' + index + '">' + alumno.nombre_completo + '</option>');
        selector.append(option);
    });
}

/**
 * Configurar todos los eventos
 */
function configurarEventos() {
    // PLANTILLAS
    $(document).on('click', '.plantilla-btn', function() {
        $('.plantilla-btn').removeClass('selected');
        $(this).addClass('selected');
        
        const key = $(this).data('plantilla');
        const plantilla = PLANTILLAS[key];
        
        $('#titulo_notificacion').val(plantilla.titulo);
        $('#mensaje_notificacion').val(plantilla.mensaje);
        
        plantillaActual = key;
        actualizarPreview();
        validarFormulario();
        
        console.log('📝 PLANTILLA SELECCIONADA:', key);
    });
    
    // VARIABLES
    $(document).on('click', '.variable-btn', function() {
        const variable = $(this).data('variable');
        insertarVariable(variable);
    });
    
    // SELECTOR DE ALUMNO PARA PREVIEW
    $(document).on('change', '#alumno-preview-selector', function() {
        alumnoPreviewActual = parseInt($(this).val());
        actualizarPreview();
    });
    
    // TÍTULO Y MENSAJE
    $('#titulo_notificacion, #mensaje_notificacion').on('input', function() {
        // Deseleccionar plantilla si se edita manualmente
        if (plantillaActual) {
            $('.plantilla-btn').removeClass('selected');
            plantillaActual = null;
        }
        
        actualizarPreview();
        validarFormulario();
        
        if (this.id === 'mensaje_notificacion') {
            actualizarContadorCaracteres();
        }
    });
    
    // BOTÓN ENVIAR
    $(document).off('click', '#btn_enviar_notificaciones').on('click', '#btn_enviar_notificaciones', function(e) {
        e.preventDefault();
        
        console.log('📤 CLICK ENVIAR');
        
        if (!validarFormulario()) {
            swal({
                title: "FORMULARIO INCOMPLETO",
                text: "Completa todos los campos requeridos",
                icon: "warning",
                button: "ENTENDIDO"
            });
            return;
        }
        
        const destinatarios = alumnosNotificacion.filter(a => a.tieneToken).length;
        
        swal({
            title: "¿CONFIRMAR ENVÍO?",
            text: destinatarios + ' notificaciones push serán enviadas (solo a alumnos con app instalada)',
            icon: "warning",
            buttons: {
                cancel: "CANCELAR",
                confirm: {
                    text: "SÍ, ENVIAR",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                }
            },
            dangerMode: true,
        }).then((confirmar) => {
            if (confirmar) {
                procesarEnvioNotificaciones();
            }
        });
    });
}

/**
 * Insertar variable en textarea
 */
function insertarVariable(variable) {
    const textarea = document.getElementById('mensaje_notificacion');
    const cursorPos = textarea.selectionStart;
    const textoBefore = textarea.value.substring(0, cursorPos);
    const textoAfter = textarea.value.substring(cursorPos);
    
    // Insertar variable
    textarea.value = textoBefore + variable + textoAfter;
    
    // Mover cursor
    const newPos = cursorPos + variable.length;
    textarea.setSelectionRange(newPos, newPos);
    textarea.focus();
    
    // Actualizar preview y contador
    actualizarPreview();
    actualizarContadorCaracteres();
    validarFormulario();
}

/**
 * Formatear dinero
 */
function formatearDinero(cantidad) {
    if (!cantidad || cantidad === 0) return '0.00';
    return parseFloat(cantidad).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

/**
 * Personalizar mensaje con datos reales del alumno INCLUYENDO ADEUDOS
 */
function personalizarMensaje(mensajeOriginal, alumno) {
    if (!alumno) return mensajeOriginal;
    
    const fechaHoy = new Date().toLocaleDateString('es-ES', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    let mensajePersonalizado = mensajeOriginal;
    
    // Reemplazar variables con datos reales INCLUYENDO ADEUDOS
    mensajePersonalizado = mensajePersonalizado
        .replace(/{alumno}/gi, alumno.nombre_completo || 'Alumno')
        .replace(/{programa}/gi, alumno.programa || 'Programa')
        .replace(/{grupo}/gi, alumno.generacion || 'Grupo')
        .replace(/{fecha}/gi, fechaHoy)
        .replace(/{correo}/gi, alumno.correo || 'correo@ejemplo.com')
        .replace(/{adeudo_colegiatura}/gi, formatearDinero(alumno.adeudo_colegiatura))
        .replace(/{adeudo_inscripcion}/gi, formatearDinero(alumno.adeudo_inscripcion))
        .replace(/{adeudo_reinscripcion}/gi, formatearDinero(alumno.adeudo_reinscripcion))
        .replace(/{adeudo_tramites}/gi, formatearDinero(alumno.adeudo_tramites));
    
    return mensajePersonalizado;
}

/**
 * Actualizar preview en tiempo real CON VARIABLES REALES Y ADEUDOS
 */
function actualizarPreview() {
    const titulo = $('#titulo_notificacion').val() || 'NOTIFICACIÓN ACADÉMICA';
    const mensaje = $('#mensaje_notificacion').val() || 'Selecciona una plantilla o escribe un mensaje con variables';
    
    // Obtener alumno actual para preview
    const alumnoPreview = alumnosNotificacion[alumnoPreviewActual] || alumnosNotificacion[0];
    
    // Personalizar mensaje con datos reales
    const mensajePersonalizado = personalizarMensaje(mensaje, alumnoPreview);
    
    $('#preview-titulo').text(titulo);
    $('#preview-mensaje').text(mensajePersonalizado);
    
    // Actualizar tiempo en notificación
    const ahora = new Date();
    $('#preview-time').text(ahora.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }));
    
    // Estado del preview
    const $preview = $('#notification-preview');
    if (mensaje.trim() === '' || mensaje === 'Selecciona una plantilla o escribe un mensaje con variables') {
        $preview.addClass('empty');
    } else {
        $preview.removeClass('empty');
    }
}

/**
 * Actualizar contador de caracteres
 */
function actualizarContadorCaracteres() {
    const mensaje = $('#mensaje_notificacion').val();
    const longitud = mensaje.length;
    const maximo = 160;
    
    const $counter = $('#char-counter');
    $counter.text(longitud + '/' + maximo);
    
    $counter.removeClass('warning danger');
    if (longitud > maximo * 0.8) {
        $counter.addClass('warning');
    }
    if (longitud > maximo) {
        $counter.addClass('danger');
    }
}

/**
 * Inicializar reloj en preview
 */
function iniciarRelojPreview() {
    contadorTiempo = setInterval(() => {
        const ahora = new Date();
        const timeString = ahora.toLocaleTimeString('es-MX', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        // Actualizar hora en header del móvil
        $('#mobile-time').text(timeString);
        
        // Actualizar hora en notificación
        $('#preview-time').text(timeString);
    }, 1000);
}

/**
 * Validar formulario completo
 */
function validarFormulario() {
    const titulo = $('#titulo_notificacion').val().trim();
    const mensaje = $('#mensaje_notificacion').val().trim();
    const longitudValida = mensaje.length > 0 && mensaje.length <= 160;
    const alumnosValidos = alumnosNotificacion.length > 0;
    
    const formularioValido = titulo.length > 0 && longitudValida && alumnosValidos;
    
    // Actualizar estado del botón
    $('#btn_enviar_notificaciones').prop('disabled', !formularioValido);
    
    return formularioValido;
}

/**
 * Procesar envío de notificaciones
 */
function procesarEnvioNotificaciones() {
    console.log('⚡ PROCESANDO ENVÍO...');
    
    if (envioActivo) {
        console.warn('⚠️ YA EN PROCESO');
        return false;
    }
    
    if (!validarFormulario()) {
        swal({
            title: "FORMULARIO INCOMPLETO",
            text: "Completa todos los campos requeridos",
            icon: "warning",
            button: "ENTENDIDO"
        });
        iniciarTracking($("#titulo_notificacion").val(), $("#mensaje_notificacion").val());
        return false;
    }
    
    envioActivo = true;
    
    // Cambiar estado del botón
    const $btn = $('#btn_enviar_notificaciones');
    $btn.text('ENVIANDO...').prop('disabled', true);
    
    // Recopilar datos
    const titulo = $('#titulo_notificacion').val().trim();
    const mensaje = $('#mensaje_notificacion').val().trim();
    
    console.log('📤 ENVIANDO A:', alumnosNotificacion.length, 'alumnos');
    
    // Implementar envío real aquí
    enviar_notificacion_masiva(titulo, mensaje, alumnosNotificacion);
}

/**
 * Envío real de notificaciones CON VARIABLES DE ADEUDOS
 */
function enviar_notificacion_masiva(titulo, mensaje, alumnos) {
    console.log('📱 Enviando notificación masiva con adeudos:', { titulo, alumnos: alumnos.length });
    
    // FILTRAR SOLO ALUMNOS CON TOKEN
    var alumnosConToken = alumnos.filter(alumno => alumno.tieneToken);
    var alumnosSinToken = alumnos.length - alumnosConToken.length;
    
    if (alumnosConToken.length === 0) {
        finalizarEnvio(0, 0, alumnos.length);
        return;
    }
    
    var totalAlumnos = alumnosConToken.length;
    var procesados = 0;
    var enviadas = 0;
    var errores = 0;
    
    alumnosConToken.forEach(function(alumno, index) {
        var id_alu = alumno.id_alu;
        var mensajePersonalizado = personalizarMensaje(mensaje, alumno);
        var fecha = new Date().getTime();

        setTimeout(function() {
            var formData = new FormData();
            formData.append('title', titulo);
            formData.append('description', mensajePersonalizado);
            formData.append('json_data', JSON.stringify({
                "tipo": "masiva", 
                "origen": "web", 
                "timestamp": fecha.toString(),
                "adeudo_colegiatura": (alumno.adeudo_colegiatura || 0).toFixed(2),
                "adeudo_inscripcion": (alumno.adeudo_inscripcion || 0).toFixed(2),
                "adeudo_reinscripcion": (alumno.adeudo_reinscripcion || 0).toFixed(2),
                "adeudo_tramites": (alumno.adeudo_tramites || 0).toFixed(2)
            }));

            $.ajax({
                url: 'https://plataforma.ahjende.com/api/alumno/send_push_notification/' + id_alu,
                type: 'POST',
                headers: { 'token': '61caf15d6b41de2d046caa5a44bb124f' },
                data: formData,
                processData: false,
                contentType: false,
                success: function(respuesta) {
                    procesados++;
                    enviadas++;
                    console.log('Notificación enviada a ' + alumno.nombre_completo);
                    
                    if (procesados === totalAlumnos) {
                        finalizarEnvio(enviadas, errores, alumnosSinToken);
                    }
                },
                error: function(xhr, status, error) {
                    procesados++;
                    errores++;
                    console.error('Error enviando a ' + alumno.nombre_completo + ':', error);
                    
                    if (procesados === totalAlumnos) {
                        finalizarEnvio(enviadas, errores, alumnosSinToken);
                    }
                }
            });
        }, index * 200);
    });
}

/**
 * Finalizar envío
 */
function finalizarEnvio(enviados, errores, sinToken = 0) {
    console.log('🏁 ENVÍO FINALIZADO');
    console.log('✅ ENVIADOS:', enviados);
    console.log('❌ ERRORES:', errores);
    console.log('📱 SIN TOKEN:', sinToken);
    
    envioActivo = false;
    
    // Restaurar botón
    $('#btn_enviar_notificaciones').text('ENVIAR NOTIFICACIONES').prop('disabled', false);

    // Limpiar formulario
    $('#titulo_notificacion').val('NOTIFICACIÓN ACADÉMICA');
    $('#mensaje_notificacion').val('');
    $('.plantilla-btn').removeClass('selected');
    plantillaActual = null;
    actualizarContadorCaracteres();
    actualizarPreview();
    validarFormulario();
    
    // Mostrar resultado mejorado
    if (errores === 0 && enviados > 0) {  
        var mensaje = enviados + ' notificaciones enviadas exitosamente';
        if (sinToken > 0) {
            mensaje += ' (' + sinToken + ' alumnos sin app fueron omitidos)';
        }
        toastr.success(mensaje);
        iniciarTracking($('#titulo_notificacion').val(), $('#mensaje_notificacion').val());
        
    } else if (enviados > 0) {
        swal({
            title: "ENVÍO COMPLETADO",
            text: enviados + ' enviadas correctamente, ' + errores + ' fallaron' + (sinToken > 0 ? ', ' + sinToken + ' sin app' : ''),
            icon: "warning",
            button: "ENTENDIDO"
        });
        iniciarTracking($("#titulo_notificacion").val(), $("#mensaje_notificacion").val());
        
    } else {
        let mensaje = "No se enviaron notificaciones";
        if (sinToken > 0) {
            mensaje = 'Los ' + sinToken + ' alumnos seleccionados no tienen la app instalada';
        }
        swal({
            title: "SIN ENVÍOS",
            text: mensaje,
            icon: "error", 
            button: "ENTENDIDO"
        });
        iniciarTracking($("#titulo_notificacion").val(), $("#mensaje_notificacion").val());
    }
}

/**
 * Limpiar variables y resetear estado
 */
function limpiarVariables() {
    alumnosNotificacion = [];
    plantillaActual = null;
    envioActivo = false;
    alumnoPreviewActual = 0;
    
    if (contadorTiempo) {
        clearInterval(contadorTiempo);
        contadorTiempo = null;
    }
}

/**
 * Función para testing de variables de adeudos
 */
function testVariablesAdeudos() {
    console.log('🧪 TESTING VARIABLES DE ADEUDOS:');
    
    if (alumnosNotificacion.length > 0) {
        const alumnoTest = alumnosNotificacion[0];
        const mensajeTest = "Hola {alumno}, tu adeudo de colegiatura es ${adeudo_colegiatura}, inscripción ${adeudo_inscripcion}, reinscripción ${adeudo_reinscripcion} y trámites ${adeudo_tramites}";
        
        const resultado = personalizarMensaje(mensajeTest, alumnoTest);
        
        console.log('📝 Mensaje original:', mensajeTest);
        console.log('📝 Mensaje personalizado:', resultado);
        console.log('💰 Adeudos del alumno:', {
            colegiatura: alumnoTest.adeudo_colegiatura,
            inscripcion: alumnoTest.adeudo_inscripcion,
            reinscripcion: alumnoTest.adeudo_reinscripcion,
            tramites: alumnoTest.adeudo_tramites
        });
    } else {
        console.warn('⚠️ No hay alumnos cargados para testing');
    }
}

// INICIALIZACIÓN AUTOMÁTICA
$(document).ready(function() {
    // Extraer datos de alumnos del DOM
    extraerDatosAlumnos();
    
    // Inicializar componentes
    generarPlantillas();
    generarVariables();
    generarSelectorPreview();
    
    // Configurar eventos
    configurarEventos();
    
    // Actualizar preview inicial
    actualizarPreview();
    
    // Iniciar reloj
    iniciarRelojPreview();
    
    // Validar formulario inicial
    validarFormulario();
    
    // Focus inicial
    setTimeout(() => {
        $('#titulo_notificacion').focus();
    }, 300);
    
    // Test de variables de adeudos (solo en desarrollo)
    setTimeout(() => {
        testVariablesAdeudos();
    }, 1000);
    
    console.log('✅ SISTEMA NOTIFICACIONES CON VARIABLES DINÁMICAS Y ADEUDOS INICIALIZADO');
    console.log('📊 Alumnos cargados:', alumnosNotificacion.length);
    console.log('💰 Variables de adeudos disponibles:', [
        '{adeudo_colegiatura}',
        '{adeudo_inscripcion}', 
        '{adeudo_reinscripcion}',
        '{adeudo_tramites}'
    ]);
});

// Event listener para debugging
$(document).on('click', '.variable-btn', function() {
    const variable = $(this).data('variable');
    console.log('🎯 Variable seleccionada:', variable);
    
    if (variable.includes('adeudo_') && alumnosNotificacion.length > 0) {
        const alumnoActual = alumnosNotificacion[alumnoPreviewActual] || alumnosNotificacion[0];
        const valorAdeudo = alumnoActual[variable.replace(/[{}]/g, '')];
        console.log('Valor actual de ' + variable + ':', valorAdeudo);
    }
});




// TRACKING OVERLAY
var trackingActivo=false,trackingInterval=null,notificacionesEnviadas=[];

function iniciarTracking(t,i){
    console.log("Iniciando tracking overlay");
    console.log("Título:",t);
    console.log("Mensaje:",i);
    notificacionesEnviadas=alumnosNotificacion.map(function(n){
        return{
            id_alu:n.id_alu,
            nombre:n.nombre_completo,
            mensaje:i.substring(0,50)+(i.length>50?"...":""),
            titulo:t,
            estatus:"Pendiente"
        };
    });
    $("#tracking_overlay").addClass("active");
    renderizarTrackingTabla();
    trackingActivo=!0;
    trackingInterval=setInterval(function(){
        actualizarEstatusNotificaciones();
    },5e3);
}

function renderizarTrackingTabla(){
    var tbody=$("#tracking_tbody");
    tbody.empty();
    if(notificacionesEnviadas.length===0){
        tbody.append('<tr><td colspan="3"><div class="tracking-empty"><div class="tracking-empty-icon">📭</div><div class="tracking-empty-text">No hay notificaciones en seguimiento</div></div></td></tr>');
        return;
    }
    notificacionesEnviadas.forEach(function(n){
        var estatusClase=n.estatus.toLowerCase().replace("í","i");
        var row='<tr>';
        row+='<td><div class="tracking-nombre">'+n.nombre+'</div></td>';
        row+='<td><div class="tracking-mensaje" title="'+(n.mensaje||'')+'">'+((n.titulo||n.mensaje)||'Sin título')+'</div></td>';
        row+='<td style="text-align:center;"><span class="tracking-badge '+estatusClase+'">'+n.estatus+'</span></td>';
        row+='</tr>';
        tbody.append(row);
    });
}

function actualizarEstatusNotificaciones(){
    if(!trackingActivo)return;
    var ids=notificacionesEnviadas.map(function(n){return n.id_alu;});
    $.ajax({
        url:"server/get_estatus_notificaciones.php",
        type:"POST",
        data:{alumnos:JSON.stringify(ids),plantel:'<?php echo $plantel; ?>'},
        dataType:"json",
        success:function(response){
            console.log("RESPONSE:",response);
            // if(response.query){
            //     toastr.info(response.query,"QUERY EJECUTADA",{timeOut:10000});
            // }
            if(response.success&&response.notificaciones){
                response.notificaciones.forEach(function(notifBD){
                    for(var i=0;i<notificacionesEnviadas.length;i++){
                        if(notificacionesEnviadas[i].id_alu===notifBD.id_alu){
                            notificacionesEnviadas[i].estatus=notifBD.est_not;
                            notificacionesEnviadas[i].titulo=notifBD.titulo;
                            notificacionesEnviadas[i].mensaje=notifBD.mensaje;
                            break;
                        }
                    }
                });
                renderizarTrackingTabla();
                $(".tracking-status-dot").css("background","#10b981");
                $(".tracking-status-indicator").css("color","#10b981");
                setTimeout(function(){
                    $(".tracking-status-dot").css("background","#3b82f6");
                    $(".tracking-status-indicator").css("color","#3b82f6");
                },500);
            }
        },
        error:function(xhr,status,error){
            console.error("ERROR:",xhr.responseText);
            toastr.error(xhr.responseText,"ERROR EN BACKEND",{timeOut:0});
            $(".tracking-status-dot").css("background","#ef4444");
            $(".tracking-status-indicator").css("color","#ef4444").text("ERROR");
            setTimeout(function(){
                $(".tracking-status-dot").css("background","#3b82f6");
                $(".tracking-status-indicator").css("color","#3b82f6").text("EN VIVO");
            },2000);
        }
    });
}

function cerrarTracking(){
    trackingActivo=!1;
    if(trackingInterval){
        clearInterval(trackingInterval);
        trackingInterval=null;
    }
    $("#tracking_overlay").removeClass("active");
    notificacionesEnviadas=[];
}

</script>