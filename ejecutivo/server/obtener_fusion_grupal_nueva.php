<?php  
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');
    
    // Obtener planteles
    $sqlPla = "SELECT id_pla, nom_pla FROM plantel WHERE id_cad1 = '$cadena' ORDER BY nom_pla ASC";
    $resPla = mysqli_query($db, $sqlPla);
    $planteles = array();
    while($r = mysqli_fetch_assoc($resPla)){ $planteles[] = $r; }
    
    // Obtener todas las materias con su info
    $sqlMat = "
        SELECT m.id_mat, m.nom_mat, r.id_ram, r.nom_ram, p.id_pla, p.nom_pla
        FROM materia m
        INNER JOIN rama r ON r.id_ram = m.id_ram2
        INNER JOIN plantel p ON p.id_pla = r.id_pla1
        INNER JOIN cadena c ON c.id_cad = p.id_cad1
        WHERE c.id_cad = '$cadena'
        ORDER BY m.nom_mat ASC
    ";
    $resMat = mysqli_query($db, $sqlMat);
    $materias = array();
    while($r = mysqli_fetch_assoc($resMat)){ $materias[] = $r; }
    
    // Obtener profesores CON CORREO
    $sqlPro = "
        SELECT p.id_pro, p.nom_pro, p.app_pro, p.cor_pro, pl.id_pla, pl.nom_pla
        FROM profesor p
        INNER JOIN plantel pl ON pl.id_pla = p.id_pla2
        WHERE pl.id_cad1 = '$cadena'
        ORDER BY p.nom_pro ASC
    ";
    $resPro = mysqli_query($db, $sqlPro);
    $profesores = array();
    while($r = mysqli_fetch_assoc($resPro)){ $profesores[] = $r; }
?>

<style>
/* ========== ESCALA MEJORADA ========== */
.fus{font-family:'Roboto',sans-serif;font-size:12px;line-height:1.35;color:#1e293b;max-width:1200px;margin:0 auto;height:520px;display:flex;flex-direction:column}
.fus .form-control,.fus .browser-default{font-size:11px!important;height:26px!important;padding:3px 8px!important}
.fus .grey-text{font-size:10px!important;color:#64748b}

/* ========== STEPPER ========== */
.stepper-container{display:flex;justify-content:center;align-items:flex-start;gap:70px;margin:0 auto 10px;padding:0;max-width:650px;flex-shrink:0;position:relative}
.stepper-item{flex:0 0 auto;display:flex;flex-direction:column;align-items:center;position:relative}
.stepper-circle{width:36px;height:36px;border-radius:50%;background:#e2e8f0;color:#64748b;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;cursor:pointer;transition:all .3s;position:relative;z-index:2;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.08)}
.stepper-item.active .stepper-circle{background:#3b82f6;color:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.2)}
.stepper-item.completed .stepper-circle{background:#16a34a;color:#fff}
.stepper-line{width:70px;height:2px;background:#e2e8f0;position:absolute;top:18px;left:36px;z-index:1;transition:all .3s}
.stepper-item:last-child .stepper-line{display:none}
.stepper-item.completed .stepper-line{background:#16a34a}
.stepper-label{margin-top:7px;font-size:10px;color:#64748b;white-space:nowrap;font-weight:500;text-align:center;line-height:1.2}
.stepper-item.active .stepper-label{color:#3b82f6;font-weight:600}
.stepper-item.completed .stepper-label{color:#16a34a}

/* CHIPS INFORMATIVOS */
.info-chips{display:flex;flex-wrap:wrap;gap:5px;padding:7px;background:#f8fafc;border-radius:5px;margin-bottom:8px;flex-shrink:0}
.info-chip{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#fff;border:1px solid #e2e8f0;border-radius:18px;font-size:10px;color:#1e293b;box-shadow:0 1px 2px rgba(0,0,0,.04)}
.info-chip-label{color:#64748b;font-weight:500}
.info-chip-value{color:#1e293b;font-weight:600}

/* PASOS */
.paso-content{display:none;flex:1;overflow:hidden;min-height:0}
.paso-content.active{display:flex;flex-direction:column}
.paso-content::-webkit-scrollbar{width:6px}
.paso-content::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:3px}
#paso_1{overflow-y:auto}
#paso_2{overflow:hidden}
#paso_3{overflow-y:auto}

/* HEADER PASO 1 */
.fus-hdr-row1{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px}
.fus-hdr-row2{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:10px}
.fus-hdr-row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;padding-top:10px;border-top:2px solid #e2e8f0}
.fus-hdr-row1>div,.fus-hdr-row2>div,.fus-hdr-row3>div{display:flex;flex-direction:column;padding:7px;background:#fff;border:1px solid #e2e8f0;border-radius:5px}

/* TABS */
.tabs-hor{display:flex;align-items:flex-end;gap:2px;border-bottom:2px solid #3b82f6;margin-bottom:0;flex-shrink:0}
.tab-hor{padding:5px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-bottom:none;border-radius:5px 5px 0 0;cursor:pointer;font-size:11px;font-weight:500;color:#64748b;position:relative;bottom:-1px}
.tab-hor:hover{background:#e2e8f0}
.tab-hor.active{background:#3b82f6;color:#fff;border-color:#3b82f6}
.tab-hor .cerrar{margin-left:5px;font-size:10px;opacity:.7}
.tab-hor .cerrar:hover{opacity:1;color:#fca5a5}
.btn-add-hor{padding:5px 12px;background:#3b82f6;color:#fff;border:none;border-radius:5px 5px 0 0;cursor:pointer;font-size:10px;margin-left:4px}
.btn-add-hor:hover{background:#2563eb}

/* SECCIÓN CON SCROLL */
.seccion-materias{display:none;padding:8px;background:#fff;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 5px 5px;flex:1;overflow-y:auto;min-height:0}
.seccion-materias.active{display:flex;flex-direction:column}
.seccion-materias::-webkit-scrollbar{width:6px}
.seccion-materias::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:3px}
#contenedor_secciones{flex:1;display:flex;flex-direction:column;min-height:0;overflow:hidden}

/* SELECTOR PROFESOR - MÁS COMPACTO */
.profesor-box{position:relative;width:100%;margin-bottom:8px}
.profesor-search-wrapper{position:relative;width:100%}
.profesor-search-input{width:100%!important;font-size:10px!important;height:22px!important;padding:2px 22px 2px 6px!important;border:1px solid #e2e8f0!important;border-radius:4px!important;box-sizing:border-box!important}
.profesor-search-input:focus{border-color:#3b82f6!important;outline:none!important}
.profesor-search-clear{position:absolute;right:5px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:10px;padding:2px;z-index:2}
.profesor-search-clear:hover{color:#ef4444}
.profesor-dropdown{position:absolute;top:calc(100% + 2px);left:0;right:0;background:#fff;border:1px solid #e2e8f0;border-radius:4px;max-height:150px;overflow-y:auto;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,.15);display:none}
.profesor-dropdown.active{display:block}
.profesor-dropdown::-webkit-scrollbar{width:5px}
.profesor-dropdown::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:3px}
.profesor-option{padding:5px;cursor:pointer;font-size:10px;border-bottom:1px solid #f1f5f9;transition:background .15s}
.profesor-option:last-child{border-bottom:none}
.profesor-option:hover{background:#eff6ff}
.profesor-option.hidden{display:none!important}
.profesor-option-nom{font-weight:500;color:#1e293b;margin-bottom:1px}
.profesor-option-correo{font-size:8px;color:#3b82f6;margin-bottom:1px;font-style:italic}
.profesor-option-pla{font-size:8px;color:#94a3b8}
.profesor-selected{display:none;padding:4px 6px;background:#dbeafe;border:1px solid:#93c5fd;border-radius:4px;font-size:10px;cursor:pointer;align-items:center;justify-content:space-between}
.profesor-selected.active{display:flex}
.profesor-selected-info{flex:1}
.profesor-selected-nom{font-weight:500;color:#1e293b;margin-bottom:1px;font-size:10px}
.profesor-selected-correo{font-size:8px;color:#3b82f6;margin-bottom:1px;font-style:italic}
.profesor-selected-pla{font-size:8px;color:#64748b}
.profesor-selected-clear{color:#94a3b8;font-size:11px;margin-left:5px;padding:2px;border-radius:3px;transition:all .15s}
.profesor-selected-clear:hover{background:#fecaca;color:#dc2626}

/* FILTROS */
.filtros-box{display:grid;grid-template-columns:1fr 1fr 1.5fr;gap:6px;padding:6px;background:#f1f5f9;border-radius:4px;margin-bottom:6px}
.filtros-box .fg{display:flex;flex-direction:column}
.filtros-box .fg label{font-size:9px;color:#64748b;margin-bottom:2px}
.filtro-info{font-size:9px;color:#64748b;margin-bottom:5px;display:flex;justify-content:space-between;align-items:center}
.filtro-info .contador{color:#3b82f6;font-weight:600}
.btn-limpiar{font-size:9px;color:#3b82f6;cursor:pointer;background:none;border:none;padding:0}
.btn-limpiar:hover{text-decoration:underline}

/* LISTA MATERIAS */
.materias-row{display:flex;gap:8px;margin-bottom:8px}
.materias-lista-box{flex:1;max-height:120px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:4px;background:#fff}
.materias-lista-box::-webkit-scrollbar{width:5px}
.materias-lista-box::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:3px}
.mat-item{padding:5px 7px;border-bottom:1px solid #f1f5f9;display:flex;align-items:flex-start;gap:7px;cursor:pointer;transition:background .15s}
.mat-item:hover{background:#f8fafc}
.mat-item.disabled{background:#fef2f2;opacity:.5;pointer-events:none}
.mat-item.selected{background:#e0f2fe;border-left:3px solid #0ea5e9}
.mat-item.hidden{display:none}
.mat-item input[type="checkbox"]{margin-top:2px;cursor:pointer;flex-shrink:0}
.mat-item .mat-info{flex:1;min-width:0}
.mat-item .mat-nom{font-weight:500;color:#1e293b;font-size:11px}
.mat-item.selected .mat-nom{color:#0369a1}
.mat-item .mat-meta{font-size:9px;color:#94a3b8}
.mat-item.selected .mat-meta{color:#0891b2}

/* SELECCIÓN */
.seleccion-box{width:240px;border:2px dashed #93c5fd;border-radius:4px;padding:7px;background:#dbeafe;max-height:120px;overflow-y:auto}
.seleccion-box::-webkit-scrollbar{width:5px}
.seleccion-box::-webkit-scrollbar-thumb{background:#3b82f6;border-radius:3px}
.seleccion-titulo{font-size:10px;color:#3b82f6;font-weight:500;margin-bottom:5px}
.mat-sel{padding:5px 7px;margin-bottom:4px;background:#fff;border:1px solid #e2e8f0;border-radius:4px;display:flex;justify-content:space-between;align-items:center;position:relative}
.mat-sel.dominante{background:#16a34a;color:#fff;border-color:#16a34a}
.mat-sel.plantel-repetido{background:#fef08a;border:2px solid #eab308;color:#713f12}
.mat-sel .mat-sel-info{flex:1;min-width:0}
.mat-sel .mat-sel-nom{font-size:10px;font-weight:500}
.mat-sel .mat-sel-meta{font-size:9px;opacity:.7}
.mat-sel .quitar{cursor:pointer;font-size:11px;opacity:.6;padding:2px 4px;border-radius:3px;transition:all .15s;flex-shrink:0}
.mat-sel .quitar:hover{opacity:1;background:rgba(239,68,68,.2);color:#ef4444}
.mat-sel.dominante .quitar:hover{background:rgba(252,165,165,.3);color:#fca5a5}
.alerta-plantel{font-size:9px;color:#dc2626;background:#fef2f2;padding:5px 7px;border-radius:3px;margin-top:5px;display:none;border-left:3px solid #dc2626}
.alerta-plantel.active{display:block}

/* HORARIOS GRID DEBAJO DEL PROFESOR */
.fusion-form-group{margin-top:8px;padding-top:8px;border-top:2px solid #e2e8f0}
.fusion-label{font-size:10px;font-weight:600;color:#1e293b;margin-bottom:6px;display:block}
.fusion-horarios-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:4px}
.fusion-dia-box{text-align:center;background:#fff;padding:4px 2px;border-radius:4px;border:1px solid #e2e8f0}
.fusion-dia-nombre{font-size:9px;font-weight:700;color:#1e293b;margin-bottom:3px;text-transform:uppercase}
.fusion-dia-box input[type="time"]{width:100%;font-size:9px;padding:2px;border:1px solid #e2e8f0;border-radius:3px;margin-bottom:2px}
.fusion-dia-box input[type="time"]:focus{border-color:#3b82f6;outline:none;box-shadow:0 0 0 2px rgba(59,130,246,.1)}
.fusion-hora-label{font-size:7px;color:#64748b;display:block;margin-bottom:1px;text-transform:uppercase}

/* PASO 3 - CONFIRMACIÓN */
.confirmacion-section{background:#fff;border:1px solid #e2e8f0;border-radius:5px;padding:10px;margin-bottom:10px}
.confirmacion-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;padding-bottom:7px;border-bottom:2px solid #e2e8f0}
.confirmacion-titulo{font-size:12px;font-weight:600;color:#1e293b;display:flex;align-items:center;gap:5px}
.confirmacion-titulo i{color:#3b82f6}
.btn-editar-paso{font-size:10px;color:#3b82f6;cursor:pointer;background:none;border:1px solid #3b82f6;padding:4px 12px;border-radius:4px;transition:all .2s}
.btn-editar-paso:hover{background:#3b82f6;color:#fff}
.confirmacion-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:10px}
.confirmacion-item{display:flex;flex-direction:column;gap:4px}
.confirmacion-label{font-size:9px;color:#64748b;font-weight:500;text-transform:uppercase;letter-spacing:.3px}
.confirmacion-value{font-size:11px;color:#1e293b;font-weight:500;word-break:break-word}
.confirmacion-value a{color:#3b82f6;text-decoration:none}
.confirmacion-value a:hover{text-decoration:underline}
.confirmacion-horario{background:#f8fafc;padding:10px;border-radius:5px;margin-bottom:8px;border-left:3px solid #3b82f6}
.confirmacion-horario:last-child{margin-bottom:0}
.confirmacion-horario-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:7px}
.confirmacion-horario-titulo{font-size:12px;font-weight:600;color:#3b82f6}
.confirmacion-profesor{font-size:10px;color:#64748b;margin-bottom:5px}
.confirmacion-profesor strong{color:#1e293b}
.confirmacion-materias{display:flex;flex-wrap:wrap;gap:4px;margin-top:5px;margin-bottom:8px}
.confirmacion-materia-tag{font-size:9px;padding:4px 10px;background:#e0f2fe;color:#0369a1;border-radius:12px;display:inline-flex;align-items:center;gap:3px;line-height:1.3}
.confirmacion-materia-tag.dominante{background:#dcfce7;color:#15803d;font-weight:600}
.confirmacion-materia-tag.dominante::before{content:'★';font-size:10px}
.confirmacion-horarios-detalle{font-size:10px;color:#64748b;line-height:1.6}
.hint-dominante{font-size:10px;color:#16a34a;margin-bottom:6px}

/* FOOTER NAVEGACIÓN */
.fus-nav{display:flex;justify-content:space-between;align-items:center;padding:7px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:5px;margin-top:8px;flex-shrink:0}
.btn-nav{padding:6px 18px;border:none;border-radius:5px;cursor:pointer;font-size:11px;font-weight:600;transition:all .2s;display:flex;align-items:center;gap:5px}
.btn-anterior{background:#e2e8f0;color:#64748b}
.btn-anterior:hover:not(:disabled){background:#cbd5e1}
.btn-siguiente{background:#3b82f6;color:#fff}
.btn-siguiente:hover{background:#2563eb}
.btn-guardar{background:#16a34a;color:#fff}
.btn-guardar:hover{background:#15803d}
.btn-nav:disabled{opacity:.4;cursor:not-allowed}
.paso-indicator{font-size:10px;color:#64748b;font-weight:500}
.paso-indicator strong{color:#3b82f6;font-size:12px}
</style>

<div class="fus" id="secciones_materias_container">
    
    <!-- STEPPER -->
    <div class="stepper-container">
        <div class="stepper-item active" data-paso="1">
            <div class="stepper-circle">1</div>
            <div class="stepper-line"></div>
            <div class="stepper-label">Información<br>General</div>
        </div>
        <div class="stepper-item" data-paso="2">
            <div class="stepper-circle">2</div>
            <div class="stepper-line"></div>
            <div class="stepper-label">Horarios y<br>Materias</div>
        </div>
        <div class="stepper-item" data-paso="3">
            <div class="stepper-circle">3</div>
            <div class="stepper-line"></div>
            <div class="stepper-label">Confirmación</div>
        </div>
    </div>

    <!-- CHIPS INFORMATIVOS -->
    <div class="info-chips" id="info_chips" style="display:none">
        <div class="info-chip">
            <span class="info-chip-label">Grupo:</span>
            <span class="info-chip-value" id="chip_nom_fus">-</span>
        </div>
        <div class="info-chip">
            <span class="info-chip-label">Ciclo:</span>
            <span class="info-chip-value" id="chip_cic_fus">-</span>
        </div>
        <div class="info-chip">
            <span class="info-chip-label">📅 Inicio:</span>
            <span class="info-chip-value" id="chip_ini_fus">-</span>
        </div>
        <div class="info-chip">
            <span class="info-chip-label">📅 Fin:</span>
            <span class="info-chip-value" id="chip_fin_fus">-</span>
        </div>
    </div>

    <!-- PASO 1: INFORMACIÓN GENERAL -->
    <div class="paso-content active" id="paso_1">
        <div class="fus-hdr-row1">
            <div>
                <span class="grey-text">Nombre grupo fusionado</span>
                <input type="text" class="form-control" id="nom_fus" value="Gpo-fusionado">
            </div>
            <div>
                <span class="grey-text">Nombre del ciclo</span>
                <input type="text" class="form-control" id="cic_fus" value="Ciclo-fusionado">
            </div>
        </div>
        
        <div class="fus-hdr-row2">
            <div>
                <span class="grey-text">Inscripción</span>
                <input type="date" class="form-control" id="ins_fus" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div>
                <span class="grey-text">Inicio</span>
                <input type="date" class="form-control" id="ini_fus" value="<?php echo gmdate('Y-m-d', strtotime('+10 days')); ?>">
            </div>
            <div>
                <span class="grey-text">Corte</span>
                <input type="date" class="form-control" id="cor_fus" value="<?php echo gmdate('Y-m-d', strtotime('+130 days')); ?>">
            </div>
            <div>
                <span class="grey-text">Fin</span>
                <input type="date" class="form-control" id="fin_fus" value="<?php echo gmdate('Y-m-d', strtotime('+150 days')); ?>">
            </div>
        </div>
        
        <div class="fus-hdr-row3">
            <div>
                <span class="grey-text"><i class="fas fa-video"></i> URL videoconferencia (opcional)</span>
                <input type="url" class="form-control" id="url_sub_hor" placeholder="https://zoom.us/j/123456789">
            </div>
            <div>
                <span class="grey-text"><i class="fas fa-key"></i> Contraseña (opcional)</span>
                <input type="text" class="form-control" id="con_url_sub_hor" placeholder="Contraseña">
            </div>
            <div>
                <span class="grey-text"><i class="fas fa-dollar-sign"></i> Costo por hora (opcional)</span>
                <input type="number" step="0.01" min="0" class="form-control" id="cos_sub_hor" placeholder="250.50">
            </div>
        </div>
    </div>

    <!-- PASO 2: HORARIOS Y MATERIAS -->
    <div class="paso-content" id="paso_2">
        <div class="tabs-hor" id="tabsHorarios">
            <div class="tab-hor active" data-seccion="1">Horario 1</div>
            <button type="button" class="btn-add-hor" id="agregar_seccion_materias"><i class="fas fa-plus"></i> Añadir horario</button>
        </div>

        <div id="contenedor_secciones">
            <div class="seccion-materias active" data-seccion-id="1">
                
                <!-- PROFESOR COMPACTO -->
                <span class="grey-text" style="margin-bottom:3px;display:block">Profesor</span>
                <div class="profesor-box">
                    <div class="profesor-selected" id="profesor_selected_1">
                        <div class="profesor-selected-info">
                            <div class="profesor-selected-nom"></div>
                            <div class="profesor-selected-correo"></div>
                            <div class="profesor-selected-pla"></div>
                        </div>
                        <span class="profesor-selected-clear" title="Cambiar profesor">✕</span>
                    </div>
                    
                    <div class="profesor-search-wrapper" id="profesor_search_wrapper_1">
                        <input type="text" class="profesor-search-input" placeholder="Buscar profesor..." autocomplete="off">
                        <span class="profesor-search-clear" style="display:none">✕</span>
                        <div class="profesor-dropdown" id="profesor_dropdown_1">
                            <?php foreach($profesores as $p): ?>
                            <div class="profesor-option" 
                                 data-id="<?php echo $p['id_pro']; ?>" 
                                 data-pla="<?php echo $p['id_pla']; ?>" 
                                 data-nombre="<?php echo strtolower($p['nom_pro'].' '.$p['app_pro']); ?>"
                                 data-correo="<?php echo strtolower($p['cor_pro']); ?>"
                                 data-nom-display="<?php echo $p['nom_pro'].' '.$p['app_pro']; ?>"
                                 data-correo-display="<?php echo $p['cor_pro']; ?>"
                                 data-pla-display="<?php echo $p['nom_pla']; ?>">
                                <div class="profesor-option-nom"><?php echo $p['nom_pro'].' '.$p['app_pro']; ?></div>
                                <div class="profesor-option-correo"><?php echo $p['cor_pro']; ?></div>
                                <div class="profesor-option-pla"><?php echo $p['nom_pla']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <input type="hidden" class="seleccionProfesor" id="profesor_seccion_1">
                </div>

                <!-- HORARIOS DEBAJO DEL PROFESOR -->
                <div class="fusion-form-group">
                    <label class="fusion-label"><i class="fas fa-clock"></i> Horarios</label>
                    <div class="fusion-horarios-grid">
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Lun</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Lunes" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Lunes" data-tipo="fin">
                        </div>
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Mar</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Martes" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Martes" data-tipo="fin">
                        </div>
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Mié</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Miércoles" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Miércoles" data-tipo="fin">
                        </div>
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Jue</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Jueves" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Jueves" data-tipo="fin">
                        </div>
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Vie</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Viernes" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Viernes" data-tipo="fin">
                        </div>
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Sáb</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Sábado" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Sábado" data-tipo="fin">
                        </div>
                        <div class="fusion-dia-box">
                            <div class="fusion-dia-nombre">Dom</div>
                            <label class="fusion-hora-label">Inicio</label>
                            <input type="time" class="fusion-horario-input" data-dia="Domingo" data-tipo="ini">
                            <label class="fusion-hora-label">Fin</label>
                            <input type="time" class="fusion-horario-input" data-dia="Domingo" data-tipo="fin">
                        </div>
                    </div>
                </div>

                <span class="hint-dominante"><i class="fas fa-star"></i> Primera materia = DOMINANTE</span>

                <!-- FILTROS -->
                <div class="filtros-box">
                    <div class="fg">
                        <label><i class="fas fa-building"></i> Plantel</label>
                        <select class="browser-default custom-select filtro-plantel" data-seccion="1">
                            <option value="">Todos los planteles</option>
                            <?php foreach($planteles as $p): ?>
                            <option value="<?php echo $p['id_pla']; ?>"><?php echo $p['nom_pla']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fg">
                        <label><i class="fas fa-graduation-cap"></i> Programa</label>
                        <select class="browser-default custom-select filtro-rama" data-seccion="1">
                            <option value="">Todos los programas</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label><i class="fas fa-search"></i> Buscar materia o programa</label>
                        <input type="text" class="form-control filtro-texto" data-seccion="1" placeholder="Nombre...">
                    </div>
                </div>

                <div class="filtro-info">
                    <span>Mostrando <span class="contador" id="contador_1">0</span> de <span class="total" id="total_1">0</span> materias</span>
                    <button type="button" class="btn-limpiar" data-seccion="1"><i class="fas fa-times"></i> Limpiar filtros</button>
                </div>

                <!-- LISTA MATERIAS + SELECCIÓN -->
                <div class="materias-row">
                    <div class="materias-lista-box" id="lista_materias_1"></div>
                    <div class="seleccion-box" id="seleccion_1">
                        <div class="seleccion-titulo"><i class="fas fa-check-circle"></i> Seleccionadas: <span class="sel-count">0</span></div>
                        <div id="seleccion_scroll_1"></div>
                        <div class="alerta-plantel" id="alerta_plantel_1"><i class="fas fa-exclamation-triangle"></i> ¡ALERTA! Materias de diferentes planteles</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PASO 3: CONFIRMACIÓN -->
    <div class="paso-content" id="paso_3">
        <div class="confirmacion-section">
            <div class="confirmacion-header">
                <div class="confirmacion-titulo">
                    <i class="fas fa-info-circle"></i> INFORMACIÓN GENERAL
                </div>
                <button type="button" class="btn-editar-paso" data-paso="1"><i class="fas fa-edit"></i> Editar</button>
            </div>
            <div class="confirmacion-grid">
                <div class="confirmacion-item">
                    <div class="confirmacion-label">NOMBRE GRUPO</div>
                    <div class="confirmacion-value" id="conf_nom_fus">-</div>
                </div>
                <div class="confirmacion-item">
                    <div class="confirmacion-label">CICLO</div>
                    <div class="confirmacion-value" id="conf_cic_fus">-</div>
                </div>
                <div class="confirmacion-item">
                    <div class="confirmacion-label">INSCRIPCIÓN</div>
                    <div class="confirmacion-value" id="conf_ins_fus">-</div>
                </div>
                <div class="confirmacion-item">
                    <div class="confirmacion-label">INICIO</div>
                    <div class="confirmacion-value" id="conf_ini_fus">-</div>
                </div>
                <div class="confirmacion-item">
                    <div class="confirmacion-label">CORTE</div>
                    <div class="confirmacion-value" id="conf_cor_fus">-</div>
                </div>
                <div class="confirmacion-item">
                    <div class="confirmacion-label">FIN</div>
                    <div class="confirmacion-value" id="conf_fin_fus">-</div>
                </div>
                <div class="confirmacion-item" id="conf_url_wrapper" style="display:none">
                    <div class="confirmacion-label"><i class="fas fa-video"></i> VIDEOCONFERENCIA</div>
                    <div class="confirmacion-value" id="conf_url_sub_hor">-</div>
                </div>
                <div class="confirmacion-item" id="conf_con_wrapper" style="display:none">
                    <div class="confirmacion-label"><i class="fas fa-key"></i> CONTRASEÑA</div>
                    <div class="confirmacion-value" id="conf_con_url_sub_hor">-</div>
                </div>
                <div class="confirmacion-item" id="conf_cos_wrapper" style="display:none">
                    <div class="confirmacion-label"><i class="fas fa-dollar-sign"></i> COSTO/HORA</div>
                    <div class="confirmacion-value" id="conf_cos_sub_hor">-</div>
                </div>
            </div>
        </div>

        <div class="confirmacion-section">
            <div class="confirmacion-header">
                <div class="confirmacion-titulo">
                    <i class="fas fa-calendar-alt"></i> HORARIOS Y MATERIAS
                </div>
                <button type="button" class="btn-editar-paso" data-paso="2"><i class="fas fa-edit"></i> Editar</button>
            </div>
            <div id="conf_horarios"></div>
        </div>
    </div>

    <!-- NAVEGACIÓN -->
    <div class="fus-nav">
        <button type="button" class="btn-nav btn-anterior" id="btn_anterior" disabled>
            <i class="fas fa-arrow-left"></i> Anterior
        </button>
        <div class="paso-indicator">
            Paso <strong id="paso_actual">1</strong> de 3
        </div>
        <button type="button" class="btn-nav btn-siguiente" id="btn_siguiente">
            Siguiente <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<script>
var MATERIAS = <?php echo json_encode($materias); ?>;
var PLANTELES = <?php echo json_encode($planteles); ?>;
var PROFESORES = <?php echo json_encode($profesores); ?>;
var mesesES = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

function formatearFecha(fecha) {
    if (!fecha) return '-';
    var partes = fecha.split('-');
    if (partes.length !== 3) return fecha;
    var mes = mesesES[parseInt(partes[1])-1];
    return partes[2] + ' ' + mes + ' ' + partes[0];
}

function obtenerHorarios(sec) {
    var horarios = [];
    $('.seccion-materias[data-seccion-id="'+sec+'"] .fusion-horario-input').each(function() {
        var $input = $(this);
        var dia = $input.data('dia');
        var tipo = $input.data('tipo');
        var valor = $input.val();
        
        if (valor) {
            var horario = horarios.find(function(h) { return h.dia === dia; });
            if (!horario) {
                horario = { dia: dia, ini_hor: '', fin_hor: '' };
                horarios.push(horario);
            }
            
            if (tipo === 'ini') {
                horario.ini_hor = valor + ':00';
            } else if (tipo === 'fin') {
                horario.fin_hor = valor + ':00';
            }
        }
    });
    
    return horarios.filter(function(h) { return h.ini_hor && h.fin_hor; });
}
</script>

<script>
$(document).ready(function() {
    var seccionCounter = 1;
    var pasoActual = 1;
    
    renderMaterias(1);
    initProfesorSearch(1);
    
    function irAPaso(paso) {
        if (paso > pasoActual) {
            if (pasoActual === 1 && !validarPaso1()) return;
            if (pasoActual === 2 && !validarPaso2()) return;
        }
        
        if (paso >= 2) {
            actualizarChips();
            $('#info_chips').show();
        } else {
            $('#info_chips').hide();
        }
        
        if (paso === 3) {
            generarConfirmacion();
        }
        
        $('.stepper-item').removeClass('active completed');
        $('.stepper-item').each(function() {
            var p = $(this).data('paso');
            if (p < paso) $(this).addClass('completed');
            if (p === paso) $(this).addClass('active');
        });
        
        $('.paso-content').removeClass('active');
        $('#paso_' + paso).addClass('active');
        
        pasoActual = paso;
        $('#paso_actual').text(paso);
        
        $('#btn_anterior').prop('disabled', paso === 1);
        
        if (paso === 3) {
            $('#btn_siguiente').html('<i class="fas fa-save"></i> Guardar Fusión').removeClass('btn-siguiente').addClass('btn-guardar');
        } else {
            $('#btn_siguiente').html('Siguiente <i class="fas fa-arrow-right"></i>').removeClass('btn-guardar').addClass('btn-siguiente');
        }
    }
    
    $(document).on('click', '.stepper-circle', function() {
        var paso = $(this).closest('.stepper-item').data('paso');
        irAPaso(paso);
    });
    
    $('#btn_anterior').on('click', function() {
        if (pasoActual > 1) irAPaso(pasoActual - 1);
    });
    
    $('#btn_siguiente').on('click', function() {
        if (pasoActual < 3) {
            irAPaso(pasoActual + 1);
        } else {
            guardarFusion();
        }
    });
    
    $(document).on('click', '.btn-editar-paso', function() {
        var paso = $(this).data('paso');
        irAPaso(paso);
    });
    
    function actualizarChips() {
        $('#chip_nom_fus').text($('#nom_fus').val());
        $('#chip_cic_fus').text($('#cic_fus').val());
        $('#chip_ini_fus').text(formatearFecha($('#ini_fus').val()));
        $('#chip_fin_fus').text(formatearFecha($('#fin_fus').val()));
    }
    
    function validarPaso1() {
        var nom = $.trim($('#nom_fus').val());
        var cic = $.trim($('#cic_fus').val());
        if (!nom || !cic) {
            swal('Error', 'Nombre y ciclo son requeridos', 'error');
            return false;
        }
        return true;
    }
    
    function validarPaso2() {
        var hayError = false;
        
        $('.seccion-materias').each(function() {
            var secId = $(this).data('seccion-id');
            var profesor = $('#profesor_seccion_'+secId).val();
            var materias = $('#seleccion_scroll_'+secId+' .mat-sel').length;
            
            if (materias === 0) {
                swal('Error', 'Horario '+secId+' sin materias', 'error');
                hayError = true;
                return false;
            }
            if (!profesor) {
                swal('Error', 'Horario '+secId+' sin profesor', 'error');
                hayError = true;
                return false;
            }
        });
        
        return !hayError;
    }
    
    function generarConfirmacion() {
        $('#conf_nom_fus').text($('#nom_fus').val());
        $('#conf_cic_fus').text($('#cic_fus').val());
        $('#conf_ins_fus').text(formatearFecha($('#ins_fus').val()));
        $('#conf_ini_fus').text(formatearFecha($('#ini_fus').val()));
        $('#conf_cor_fus').text(formatearFecha($('#cor_fus').val()));
        $('#conf_fin_fus').text(formatearFecha($('#fin_fus').val()));
        
        var url = $('#url_sub_hor').val();
        var con = $('#con_url_sub_hor').val();
        var cos = $('#cos_sub_hor').val();
        
        if (url) {
            $('#conf_url_sub_hor').html('<a href="'+url+'" target="_blank">'+url+'</a>');
            $('#conf_url_wrapper').show();
        } else {
            $('#conf_url_wrapper').hide();
        }
        
        if (con) {
            $('#conf_con_url_sub_hor').text(con);
            $('#conf_con_wrapper').show();
        } else {
            $('#conf_con_wrapper').hide();
        }
        
        if (cos) {
            $('#conf_cos_sub_hor').text('$' + parseFloat(cos).toFixed(2) + ' MXN');
            $('#conf_cos_wrapper').show();
        } else {
            $('#conf_cos_wrapper').hide();
        }
        
        var html = '';
        var horarioNum = 1;
        
        $('.seccion-materias').each(function() {
            var secId = $(this).data('seccion-id');
            var profesorNombre = $('#profesor_selected_'+secId+' .profesor-selected-nom').text() || 'Sin asignar';
            
            html += '<div class="confirmacion-horario">';
            html += '<div class="confirmacion-horario-header">';
            html += '<div class="confirmacion-horario-titulo">Horario '+horarioNum+'</div>';
            html += '</div>';
            html += '<div class="confirmacion-profesor"><strong>Profesor:</strong> '+profesorNombre+'</div>';
            html += '<div class="confirmacion-materias">';
            
            var idx = 0;
            $('#seleccion_scroll_'+secId+' .mat-sel').each(function() {
                var nom = $(this).find('.mat-sel-nom').text();
                var pla = $(this).find('.mat-sel-meta').text().split(' - ')[1];
                var esDominante = idx === 0;
                
                var clases = 'confirmacion-materia-tag';
                if (esDominante) clases += ' dominante';
                
                html += '<span class="'+clases+'">'+nom+'<span class="confirmacion-materia-plantel" style="display:block;font-size:8px;opacity:.8;margin-top:1px">'+pla+'</span></span>';
                idx++;
            });
            
            html += '</div>';
            
            var horarios = obtenerHorarios(secId);
            if (horarios.length > 0) {
                html += '<div class="confirmacion-horarios-detalle"><strong>Horarios:</strong><br>';
                horarios.forEach(function(h) {
                    html += h.dia + ' ' + h.ini_hor.substring(0,5) + '-' + h.fin_hor.substring(0,5) + '<br>';
                });
                html += '</div>';
            }
            
            html += '</div>';
            horarioNum++;
        });
        
        $('#conf_horarios').html(html);
    }
    
    function guardarFusion() {
        var secciones = [];
        
        $('.seccion-materias').each(function() {
            var secId = $(this).data('seccion-id');
            var profesor = $('#profesor_seccion_'+secId).val();
            var materias = [];
            var horarios = obtenerHorarios(secId);
            
            $('#seleccion_scroll_'+secId+' .mat-sel').each(function() {
                materias.push($(this).data('id').toString());
            });
            
            secciones.push({
                materias: materias, 
                profesor: profesor,
                horarios: horarios
            });
        });
        
        var $btn = $('#btn_siguiente');
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
        
        var url_sub_hor = $('#url_sub_hor').val() || null;
        var con_url_sub_hor = $('#con_url_sub_hor').val() || null;
        var cos_sub_hor = $('#cos_sub_hor').val() || null;
        
        $.ajax({
            url: 'server/controlador_horarios.php',
            type: 'POST',
            data: {
                accion: 'guardar_fusion',
                nom_fus: $('#nom_fus').val(),
                cic_fus: $('#cic_fus').val(),
                ins_fus: $('#ins_fus').val(),
                ini_fus: $('#ini_fus').val(),
                cor_fus: $('#cor_fus').val(),
                fin_fus: $('#fin_fus').val(),
                url_sub_hor: url_sub_hor,
                con_url_sub_hor: con_url_sub_hor,
                cos_sub_hor: cos_sub_hor,
                datosEnvio: JSON.stringify({secciones: secciones})
            },
            dataType: 'json',
            success: function(r) {
                if (r.ok) {
                    swal('¡Éxito!', r.mensaje, 'success').then(function() {
                        $('#modal_formulario_grupo_fusionado').modal('hide');
                        if (typeof obtenerAjaxHorarios === 'function') obtenerAjaxHorarios();
                        if (typeof obtener_grupos_fusionados === 'function') obtener_grupos_fusionados();
                        if (typeof obtener_horarios === 'function') obtener_horarios();
                    });
                } else {
                    swal('Error', r.error, 'error');
                }
            },
            error: function() { swal('Error', 'Error de conexión', 'error'); },
            complete: function() { 
                $btn.html('<i class="fas fa-save"></i> Guardar Fusión').prop('disabled', false);
            }
        });
    }
    
    function initProfesorSearch(sec) {
        var $wrapper = $('#profesor_search_wrapper_' + sec);
        var $input = $wrapper.find('.profesor-search-input');
        var $dropdown = $('#profesor_dropdown_' + sec);
        var $clearBtn = $wrapper.find('.profesor-search-clear');
        var $selected = $('#profesor_selected_' + sec);
        var $hidden = $('#profesor_seccion_' + sec);
        
        $input.on('focus', function() { $dropdown.addClass('active'); });
        
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.profesor-box').length) {
                $('.profesor-dropdown').removeClass('active');
            }
        });
        
        $input.on('input', function() {
            var busqueda = $(this).val().toLowerCase().trim();
            busqueda ? $clearBtn.show() : $clearBtn.hide();
            
            $dropdown.find('.profesor-option').each(function() {
                var nombre = $(this).data('nombre');
                var correo = $(this).data('correo');
                if (!busqueda || nombre.indexOf(busqueda) !== -1 || correo.indexOf(busqueda) !== -1) {
                    $(this).removeClass('hidden');
                } else {
                    $(this).addClass('hidden');
                }
            });
            $dropdown.addClass('active');
        });
        
        $clearBtn.on('click', function() { $input.val('').trigger('input').focus(); });
        
        $dropdown.on('click', '.profesor-option', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nom-display');
            var correo = $(this).data('correo-display');
            var plantel = $(this).data('pla-display');
            
            $hidden.val(id);
            $selected.find('.profesor-selected-nom').text(nombre);
            $selected.find('.profesor-selected-correo').text(correo);
            $selected.find('.profesor-selected-pla').text(plantel);
            $selected.addClass('active');
            $wrapper.hide();
            $dropdown.removeClass('active');
            $input.val('');
            $clearBtn.hide();
        });
        
        $selected.find('.profesor-selected-clear').on('click', function() {
            $hidden.val('');
            $selected.removeClass('active');
            $wrapper.show();
            $input.focus();
        });
    }
    
    $('#agregar_seccion_materias').on('click', function() {
        seccionCounter++;
        var newId = seccionCounter;
        
        var $tab = $('<div class="tab-hor" data-seccion="'+newId+'">Horario '+newId+' <span class="cerrar" data-seccion="'+newId+'">✕</span></div>');
        $(this).before($tab);
        
        var $nueva = $('.seccion-materias[data-seccion-id="1"]').clone();
        $nueva.attr('data-seccion-id', newId).removeClass('active');
        
        $nueva.find('.profesor-selected').attr('id', 'profesor_selected_'+newId).removeClass('active');
        $nueva.find('.profesor-search-wrapper').attr('id', 'profesor_search_wrapper_'+newId).show();
        $nueva.find('.profesor-search-input').val('');
        $nueva.find('.profesor-search-clear').hide();
        $nueva.find('.profesor-dropdown').attr('id', 'profesor_dropdown_'+newId).removeClass('active');
        $nueva.find('.profesor-option').removeClass('hidden');
        $nueva.find('.seleccionProfesor').attr('id', 'profesor_seccion_'+newId).val('');
        
        $nueva.find('.fusion-horario-input').val('');
        
        $nueva.find('.filtro-plantel').attr('data-seccion', newId).val('');
        $nueva.find('.filtro-rama').attr('data-seccion', newId).html('<option value="">Todos los programas</option>');
        $nueva.find('.filtro-texto').attr('data-seccion', newId).val('');
        $nueva.find('.btn-limpiar').attr('data-seccion', newId);
        $nueva.find('.contador').attr('id', 'contador_'+newId);
        $nueva.find('.total').attr('id', 'total_'+newId);
        
        $nueva.find('.materias-lista-box').attr('id', 'lista_materias_'+newId).empty();
        $nueva.find('.seleccion-box').attr('id', 'seleccion_'+newId);
        $nueva.find('.seleccion-box > div:nth-child(2)').attr('id', 'seleccion_scroll_'+newId).empty();
        $nueva.find('.alerta-plantel').attr('id', 'alerta_plantel_'+newId);
        
        $('#contenedor_secciones').append($nueva);
        renderMaterias(newId);
        initProfesorSearch(newId);
        switchTab(newId);
    });
    
    $(document).on('click', '.tab-hor', function(e) {
        if (!$(e.target).hasClass('cerrar')) {
            switchTab($(this).data('seccion'));
        }
    });
    
    $(document).on('click', '.tab-hor .cerrar', function(e) {
        e.stopPropagation();
        var sec = $(this).data('seccion');
        if ($('.tab-hor').length <= 1) { swal('Error', 'Mínimo 1 horario', 'warning'); return; }
        $('.tab-hor[data-seccion="'+sec+'"]').remove();
        $('.seccion-materias[data-seccion-id="'+sec+'"]').remove();
        switchTab($('.tab-hor').first().data('seccion'));
    });
    
    function switchTab(sec) {
        $('.tab-hor').removeClass('active');
        $('.tab-hor[data-seccion="'+sec+'"]').addClass('active');
        $('.seccion-materias').removeClass('active');
        $('.seccion-materias[data-seccion-id="'+sec+'"]').addClass('active');
    }
    
    function renderMaterias(sec) {
        var $lista = $('#lista_materias_'+sec);
        var html = '';
        
        for (var i = 0; i < MATERIAS.length; i++) {
            var m = MATERIAS[i];
            html += '<div class="mat-item" data-id="'+m.id_mat+'" data-pla="'+m.id_pla+'" data-ram="'+m.id_ram+'" data-nom="'+m.nom_mat.toLowerCase()+'" data-rama-nom="'+m.nom_ram.toLowerCase()+'">';
            html += '<input type="checkbox" class="chk-mat" data-seccion="'+sec+'" data-id="'+m.id_mat+'" data-nom="'+m.nom_mat+'" data-ram="'+m.nom_ram+'" data-pla="'+m.nom_pla+'" data-pla-id="'+m.id_pla+'">';
            html += '<div class="mat-info"><div class="mat-nom">'+m.nom_mat+'</div>';
            html += '<div class="mat-meta">'+m.nom_ram+' - '+m.nom_pla+'</div></div></div>';
        }
        
        $lista.html(html);
        $('#total_'+sec).text(MATERIAS.length);
        aplicarFiltros(sec);
    }
    
    $(document).on('change', '.filtro-plantel', function() {
        var sec = $(this).data('seccion');
        var plaId = $(this).val();
        var $rama = $('.filtro-rama[data-seccion="'+sec+'"]');
        
        var ramas = {};
        for (var i = 0; i < MATERIAS.length; i++) {
            if (!plaId || MATERIAS[i].id_pla == plaId) {
                ramas[MATERIAS[i].id_ram] = MATERIAS[i].nom_ram;
            }
        }
        
        var html = '<option value="">Todos los programas</option>';
        for (var id in ramas) {
            html += '<option value="'+id+'">'+ramas[id]+'</option>';
        }
        $rama.html(html);
        aplicarFiltros(sec);
    });
    
    $(document).on('change', '.filtro-rama', function() { aplicarFiltros($(this).data('seccion')); });
    $(document).on('input', '.filtro-texto', function() { aplicarFiltros($(this).data('seccion')); });
    
    $(document).on('click', '.btn-limpiar', function() {
        var sec = $(this).data('seccion');
        $('.filtro-plantel[data-seccion="'+sec+'"]').val('').trigger('change');
        $('.filtro-texto[data-seccion="'+sec+'"]').val('');
        aplicarFiltros(sec);
    });
    
    function aplicarFiltros(sec) {
        var plaId = $('.filtro-plantel[data-seccion="'+sec+'"]').val();
        var ramId = $('.filtro-rama[data-seccion="'+sec+'"]').val();
        var texto = $('.filtro-texto[data-seccion="'+sec+'"]').val().toLowerCase().trim();
        var visibles = 0;
        var $lista = $('#lista_materias_'+sec);
        
        $lista.find('.mat-item').each(function() {
            var $item = $(this);
            var matchPla = !plaId || $item.data('pla') == plaId;
            var matchRam = !ramId || $item.data('ram') == ramId;
            
            var nomMat = $item.data('nom');
            var nomRama = $item.data('rama-nom');
            var matchTxt = !texto || nomMat.indexOf(texto) !== -1 || nomRama.indexOf(texto) !== -1;
            
            if (matchPla && matchRam && matchTxt) {
                $item.removeClass('hidden');
                visibles++;
            } else {
                $item.addClass('hidden');
            }
        });
        
        $('#contador_'+sec).text(visibles);
    }
    
    function actualizarVisualSeleccion(sec) {
        var $lista = $('#lista_materias_'+sec);
        var seleccionadas = [];
        
        $('#seleccion_scroll_'+sec+' .mat-sel').each(function() {
            seleccionadas.push($(this).data('id'));
        });
        
        $lista.find('.mat-item').each(function() {
            var id = $(this).data('id');
            if (seleccionadas.indexOf(id) !== -1) {
                $(this).addClass('selected');
            } else {
                $(this).removeClass('selected');
            }
        });
    }
    
    $(document).on('click', '.mat-item', function(e) {
        if ($(e.target).is('input[type="checkbox"]')) return;
        if ($(this).hasClass('disabled')) return;
        var $chk = $(this).find('.chk-mat');
        $chk.prop('checked', !$chk.is(':checked')).trigger('change');
    });
    
    $(document).on('change', '.chk-mat', function() {
        var $el = $(this);
        var $item = $el.closest('.mat-item');
        
        if ($item.hasClass('disabled')) {
            $el.prop('checked', false);
            return;
        }
        
        var sec = $el.data('seccion');
        var id = $el.data('id');
        var nom = $el.data('nom');
        var ram = $el.data('ram');
        var pla = $el.data('pla');
        var plaId = $el.data('pla-id');
        
        if ($el.is(':checked')) {
            if (materiaUsadaGlobal(id, sec)) {
                $el.prop('checked', false);
                swal('Error', 'Materia ya seleccionada en otro horario', 'warning');
                return;
            }
            
            var html = '<div class="mat-sel" data-id="'+id+'" data-seccion="'+sec+'" data-pla-id="'+plaId+'">';
            html += '<div class="mat-sel-info"><div class="mat-sel-nom">'+nom+'</div><div class="mat-sel-meta">'+ram+' - '+pla+'</div></div>';
            html += '<span class="quitar" data-id="'+id+'" data-seccion="'+sec+'">✕</span></div>';
            $('#seleccion_scroll_'+sec).append(html);
        } else {
            $('#seleccion_scroll_'+sec+' .mat-sel[data-id="'+id+'"]').remove();
        }
        
        actualizarDominante(sec);
        actualizarVisualSeleccion(sec);
        validarPlanteles(sec);
        actualizarDisabled();
    });
    
    $(document).on('click', '.mat-sel .quitar', function(e) {
        e.stopPropagation();
        var id = $(this).data('id');
        var sec = $(this).data('seccion');
        $('.chk-mat[data-seccion="'+sec+'"][data-id="'+id+'"]').prop('checked', false).trigger('change');
    });
    
    function materiaUsadaGlobal(id, seccionActual) {
        var usada = false;
        $('.mat-sel').each(function() {
            if ($(this).data('id') == id && $(this).data('seccion') != seccionActual) {
                usada = true;
                return false;
            }
        });
        return usada;
    }
    
    function actualizarDisabled() {
        var seleccionadas = [];
        $('.mat-sel').each(function() {
            seleccionadas.push($(this).data('id'));
        });
        
        $('.mat-item').each(function() {
            var id = $(this).data('id');
            var $chk = $(this).find('.chk-mat');
            var estaChecked = $chk.is(':checked');
            
            if (seleccionadas.indexOf(id) !== -1 && !estaChecked) {
                $(this).addClass('disabled');
            } else {
                $(this).removeClass('disabled');
            }
        });
    }
    
    function actualizarDominante(sec) {
        $('#seleccion_scroll_'+sec+' .mat-sel').removeClass('dominante');
        $('#seleccion_scroll_'+sec+' .mat-sel').first().addClass('dominante');
        $('#seleccion_'+sec+' .sel-count').text($('#seleccion_scroll_'+sec+' .mat-sel').length);
    }
    
    function validarPlanteles(sec) {
        var $seleccion = $('#seleccion_'+sec);
        var planteles = {};
        var hayRepeticion = false;
        
        $seleccion.find('.mat-sel').removeClass('plantel-repetido');
        
        $seleccion.find('.mat-sel').each(function() {
            var plaId = $(this).data('pla-id');
            if (planteles[plaId]) {
                hayRepeticion = true;
                $(this).addClass('plantel-repetido');
                $seleccion.find('.mat-sel[data-pla-id="'+plaId+'"]').addClass('plantel-repetido');
            } else {
                planteles[plaId] = true;
            }
        });
        
        if (hayRepeticion) {
            $('#alerta_plantel_'+sec).addClass('active');
        } else {
            $('#alerta_plantel_'+sec).removeClass('active');
        }
        
        return !hayRepeticion;
    }
});
</script>