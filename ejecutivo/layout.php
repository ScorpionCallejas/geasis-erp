<?php
// ============================================================================
// INCLUSIÓN DEL HEADER DEL SISTEMA
// ============================================================================
include('inc/header.php');
?>



<?php
    // LA FUNCIÓN RECIBE 2 ARGUMENTOS -> CORREO DESTINO Y LA CONEXIÓN DB (POR SI INTERNAMENTE SE REQUIEREN HACER CONSULTAS)
    // ADICIONALMENTE ADOPTA EL CUERPO EN CUANTO A ESTILOS DE LOS OTROS CORREOS QUE YA SE ENVÍAN (INSCRIPCIÓN, TICKETS DE PAGO, CUENTAS DE CONSULTORES, ETC..)
    function enviarCorreoTest($email_destino, $db) {
        require_once(__DIR__.'/../vendor/PHPMailer-master/src/PHPMailer.php');
        require_once(__DIR__.'/../vendor/PHPMailer-master/src/Exception.php');
        require_once(__DIR__.'/../vendor/PHPMailer-master/src/SMTP.php');
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // 📧 Configuración SMTP (5 elementos esenciales como en BD)
            $mail->isSMTP();
            $mail->Host = 'svgp291.serverneubox.com.mx';  // 1. Host
            $mail->SMTPAuth = true;
            $mail->Username = 'contacto@ahjende.com';     // 2. Usuario
            $mail->Password = 'AHJ_ENDE_2025';            // 3. Contraseña  
            $mail->SMTPSecure = 'ssl';                    // 4. Protocolo
            $mail->Port = 465;                            // 5. Puerto
            $mail->CharSet = 'UTF-8';
            
            // 🎲 Datos random para prueba
            $nombre_random = 'Usuario Test ' . rand(100, 999);
            $programa_random = 'Programa Demo ' . rand(10, 50);
            $codigo_random = 'TEST-' . rand(100000, 999999);
            $fecha_random = date('d de F Y');
            
            // Colores del template original
            $color_principal = '#0588a6';
            $color_secundario = '#304357';
            $color_verde = '#4caf50';
            
            $mail->setFrom('contacto@ahjende.com', 'AHJ ENDE - Test');
            $mail->addAddress($email_destino);
            $mail->Subject = '🚀 TEST SICAM - ' . $programa_random;
            
            // 📧 Template HTML completo homologado con header y footer estándar
            $mail->isHTML(true);
            $mail->Body = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>🚀 Test SICAM - ' . htmlspecialchars($programa_random) . '</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333333;
                        margin: 0;
                        padding: 0;
                        font-size: 14px;
                        background-color: #ffffff;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                    }
                    .header {
                        background-color: '.$color_secundario.';
                        padding: 20px;
                        text-align: center;
                    }
                    .header img {
                        max-width: 200px;
                        filter: brightness(0) invert(1);
                    }
                    .content {
                        padding: 20px;
                        background-color: #ffffff;
                    }
                    h1 {
                        color: '.$color_secundario.';
                        margin-top: 0;
                        margin-bottom: 20px;
                        font-size: 20px;
                        font-weight: bold;
                    }
                    .datos {
                        background-color: #ffffff;
                        border-left: 3px solid '.$color_principal.';
                        padding: 15px;
                        margin: 15px 0;
                        border: 1px solid #e5e7eb;
                        border-radius: 4px;
                    }
                    .footer {
                        text-align: center;
                        padding: 15px;
                        background-color: #f8f9fa;
                        font-size: 12px;
                        color: #666;
                    }
                    .privacidad {
                        margin-top: 10px;
                        font-size: 11px;
                    }
                    .lider {
                        font-weight: bold;
                        color: '.$color_verde.';
                    }
                    .payment-confirmation {
                        background-color: #f0f9ff;
                        border: 1px solid '.$color_verde.';
                        border-radius: 4px;
                        padding: 12px;
                        margin: 20px 0;
                        text-align: center;
                    }
                    .highlight {
                        background-color: #e3f2fd;
                        padding: 3px 5px;
                        border-radius: 3px;
                        color: '.$color_secundario.';
                        font-weight: bold;
                    }
                    .website-section {
                        background-color: #ffffff;
                        border: 1px solid '.$color_principal.';
                        padding: 20px;
                        margin: 25px 0;
                        text-align: center;
                        border-radius: 4px;
                    }
                    .website-title {
                        font-size: 16px;
                        font-weight: bold;
                        color: '.$color_verde.';
                        margin: 0 0 15px 0;
                    }
                    .website-link {
                        display: block;
                        color: '.$color_principal.';
                        font-weight: bold;
                        text-decoration: underline;
                        font-size: 16px;
                        margin: 10px 0;
                    }
                    .social-section {
                        margin: 25px 0;
                        text-align: center;
                    }
                    .social-title {
                        font-weight: bold;
                        margin-bottom: 15px;
                    }
                    .social-table {
                        width: 100%;
                        max-width: 320px;
                        margin: 0 auto;
                        border-spacing: 0;
                        border-collapse: separate;
                    }
                    .social-cell {
                        width: 25%;
                        padding: 5px;
                        text-align: center;
                    }
                    .social-link {
                        display: inline-block;
                        width: 40px;
                        height: 40px;
                        line-height: 40px;
                        text-align: center;
                        border-radius: 50%;
                        color: #ffffff !important;
                        font-weight: bold;
                        text-decoration: none;
                        font-size: 18px;
                    }
                    .social-text {
                        display: block;
                        font-size: 10px;
                        margin-top: 5px;
                        color: #666;
                    }
                    .facebook {
                        background-color: #3b5998;
                    }
                    .instagram {
                        background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d);
                    }
                    .tiktok {
                        background-color: #000000;
                    }
                    .youtube {
                        background-color: #ff0000;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <img src="https://plataforma.ahjende.com/img/logoLoginEslogan.png" alt="AHJ ENDE">
                    </div>
                    
                    <div class="content">
                        // CONTENIDO AQUÍ - Datos random de prueba para verificar funcionamiento
                        <h1>¡Test Exitoso, LÍDER <span class="lider">' . htmlspecialchars($nombre_random) . '</span>! 🚀</h1>
                        
                        <p>✨ Esta es una prueba del sistema de correos SICAM.</p>
                        
                        <div class="payment-confirmation">
                            <p style="margin: 0; font-weight: bold; font-size: 14px; color: '.$color_verde.';">
                                <span>✅</span> Configuración SMTP funcionando correctamente
                            </p>
                            <p style="margin: 5px 0 0 0; font-size: 12px;">Los correos se envían sin problemas desde el sistema.</p>
                        </div>
                        
                        <div class="datos">
                            <h3 style="font-size: 15px; margin-top: 0; color: '.$color_verde.';">📋 Datos de prueba:</h3>
                            <p><strong>Usuario:</strong> ' . htmlspecialchars($nombre_random) . '</p>
                            <p><strong>Programa:</strong> ' . htmlspecialchars($programa_random) . '</p>
                            <p><strong>Código:</strong> <span class="highlight">' . $codigo_random . '</span></p>
                            <p><strong>Fecha:</strong> ' . $fecha_random . '</p>
                        </div>
                        
                        <p>🔧 <strong style="color: '.$color_verde.';">Sistema SICAM</strong> listo para enviar correos reales.</p>
                        
                        <div class="website-section">
                            <p class="website-title">🌟 Descubre todo lo que AHJ ENDE tiene para ti 🌟</p>
                            <a href="https://ahjende.com" class="website-link">https://ahjende.com</a>
                            <p style="margin-top: 5px; font-size: 12px;">Da clic en el enlace para conocer nuestros programas y beneficios</p>
                        </div>
                    </div>
                    
                    <div class="social-section">
                        <p class="social-title">✓ Síguenos en nuestras redes oficiales:</p>
                        <table class="social-table">
                            <tr>
                                <td class="social-cell">
                                    <a href="https://www.facebook.com/escueladenegociosydesarrolloempresarial" class="social-link facebook">f</a>
                                    <span class="social-text">Facebook</span>
                                </td>
                                <td class="social-cell">
                                    <a href="https://www.instagram.com/ahjendeoficial/" class="social-link instagram">i</a>
                                    <span class="social-text">Instagram</span>
                                </td>
                                <td class="social-cell">
                                    <a href="https://www.tiktok.com/@ahj.endeoficial" class="social-link tiktok">t</a>
                                    <span class="social-text">TikTok</span>
                                </td>
                                <td class="social-cell">
                                    <a href="https://www.youtube.com/@ahj-endeescueladenegocios4351" class="social-link youtube">y</a>
                                    <span class="social-text">YouTube</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="footer">
                        <p>© ' . date('Y') . ' AHJ ENDE - Todos los derechos reservados</p>
                        <p class="privacidad">Revisa nuestro <a href="https://ahjende.com/aviso-de-privacidad" style="color: '.$color_principal.';">Aviso de Privacidad</a></p>
                    </div>
                </div>
            </body>
            </html>
            ';
            
            $mail->AltBody = "Test SICAM - $nombre_random\nPrograma: $programa_random\nCódigo: $codigo_random";
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("❌ Error test SICAM: " . $mail->ErrorInfo);
            return false;
        }
    }

    $email_destino = 'ericorps1@gmail.com';
    enviarCorreoTest($email_destino, $db);


?>

<!-- ============================================================================ -->
<!-- TÍTULO DE PÁGINA Y BREADCRUMB -->
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
<!-- LAYOUT -->
<!-- ============================================================================ -->
<div class="layout-system">
    
    <!-- ======================================================================== -->
    <!-- BOTÓN TOGGLE -->
    <!-- ======================================================================== -->
    <button class="layout-toggle" id="layoutToggle" onclick="toggleLayout()" title="ALTERNAR PANEL">
        <i class="fas fa-chevron-left" id="toggleIcon"></i>
    </button>

    <!-- ======================================================================== -->
    <!-- PANEL LATERAL -->
    <!-- ======================================================================== -->
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

    

    <!-- ======================================================================== -->
    <!-- ÁREA PRINCIPAL -->
    <!-- ======================================================================== -->
    <div class="main-area">
        
        <!-- CONTENEDOR -->
        <div class="data-container bg-white" >

            <div class="row">
                <div class="col-12">
                    <div class="card" style="background-color: #E8E8E8;">
                        <div class="card-body" style="padding: 4px;">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <?php include('modulos/filtros_fechas.php') ?>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            
            <div class="row">
                <div class="col-12 text-center">
                    <!-- DISPLAY DE FECHAS ACTUAL -->
                    <div class="mt-2">
                        <small class="text-muted">
                            📅 <span id="fechas-display">Inicio: -- | Fin: --</span>
                        </small>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php
// ============================================================================
// INCLUSIÓN DEL FOOTER DEL SISTEMA
// ============================================================================
include('inc/footer.php');
?>

<!-- ============================================================================ -->
<!-- SCRIPTS DEL LAYOUT -->
<!-- ============================================================================ -->
<script>
// ============================================================================
// CONFIGURACIÓN DE MÓDULO - ¡MUY IMPORTANTE!
// ============================================================================

/**
 * PREFIJO ÚNICO DEL MÓDULO
 * 
 * ¿Por qué es necesario?
 * - Sin prefijo: todos los módulos usarían las mismas keys en localStorage
 * - Problema: alumnos.php y citas.php compartirían 'layout_state'
 * - Resultado: colapsar panel en alumnos afectaría a citas
 * 
 * ¡CAMBIAR ESTE PREFIJO EN CADA MÓDULO!
 * Ejemplos: 'alumnos_', 'citas_', 'ejecutivos_', 'reportes_'
 */
var MODULE_PREFIX = 'template_';  // ← ¡CAMBIAR AQUÍ EN CADA MÓDULO!

// ============================================================================
// BRIDGE DE FECHAS - FUNCIÓN PRINCIPAL PARA RECUPERAR FECHAS
// ============================================================================

/**
 * FUNCIÓN PRINCIPAL: Obtiene las fechas activas del filtro
 * 
 * Esta función es INTELIGENTE y detecta automáticamente qué tipo 
 * de filtro está seleccionado y extrae las fechas correspondientes.
 * 
 * @returns {Object} {inicio: 'YYYY-MM-DD', fin: 'YYYY-MM-DD', tipo: 'Fecha|Semana|Mes|Rango'}
 */
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
                // MODO DÍA: Obtener de inputs de fecha
                fechas.inicio = $('#inicio').val();
                fechas.fin = $('#fin').val() || $('#inicio').val(); // Si no hay fin, usar inicio
                break;
                
            case 'Semana':
                // MODO SEMANA: Obtener de select de semanas
                const opcionSemana = $('#selectorSemana option:selected');
                fechas.inicio = opcionSemana.attr('inicio');
                fechas.fin = opcionSemana.attr('fin');
                break;
                
            case 'Mes':
                // MODO MES: Obtener de select de meses
                const opcionMes = $('#selectorMes option:selected');
                fechas.inicio = opcionMes.attr('inicio');
                fechas.fin = opcionMes.attr('fin');
                break;
                
            case 'Rango':
                // MODO RANGO: Obtener de inputs de rango
                fechas.inicio = $('#inicio_rango').val();
                fechas.fin = $('#fin_rango').val();
                break;
                
            default:
                console.warn('⚠️ TIPO DE FILTRO NO RECONOCIDO:', tipoSeleccionado);
                return fechas;
        }
        
        // VALIDACIÓN
        if (!fechas.inicio || !fechas.fin) {
            console.warn('⚠️ FECHAS INCOMPLETAS:', fechas);
        }
        
        return fechas;
        
    } catch (error) {
        console.error('❌ ERROR EN obtenerFechasFiltro():', error);
        return {
            inicio: null,
            fin: null,
            tipo: 'Error'
        };
    }
}

/**
 * FUNCIÓN AUXILIAR: Valida si las fechas son correctas
 * 
 * @returns {Boolean} true si las fechas son válidas
 */
function validarFechasFiltro() {
    const fechas = obtenerFechasFiltro();
    
    if (!fechas.inicio || !fechas.fin) {
        return false;
    }
    
    // Verificar formato de fecha
    const formatoFecha = /^\d{4}-\d{2}-\d{2}$/;
    if (!formatoFecha.test(fechas.inicio) || !formatoFecha.test(fechas.fin)) {
        return false;
    }
    
    // Verificar que inicio <= fin
    if (new Date(fechas.inicio) > new Date(fechas.fin)) {
        return false;
    }
    
    return true;
}



// ============================================================================
// EJEMPLO DE USO EN FUNCIONES DE DATOS
// ============================================================================

/**
 * EJEMPLO: Función que carga datos con filtro de fechas
 * 
 * Esta es la forma típica de usar obtenerFechasFiltro() 
 * en funciones que necesiten las fechas para consultas.
 */
function cargarDatosConFiltro() {
    // PASO 1: Obtener fechas
    const fechas = obtenerFechasFiltro();
    
    // PASO 2: Validar
    if (!validarFechasFiltro()) {
        console.warn('⚠️ No se pueden cargar datos: fechas inválidas');
        return;
    }
    
    // PASO 3: Usar en AJAX/consulta
    console.log('🔄 CARGANDO DATOS CON FILTRO:');
    console.log('   Desde:', fechas.inicio);
    console.log('   Hasta:', fechas.fin);
    console.log('   Tipo:', fechas.tipo);
    
    // EJEMPLO DE AJAX (descomentizar cuando sea necesario)
    /*
    $.ajax({
        url: 'api/obtener_datos.php',
        method: 'POST',
        data: {
            fecha_inicio: fechas.inicio,
            fecha_fin: fechas.fin,
            tipo_filtro: fechas.tipo
        },
        success: function(response) {
            console.log('✅ DATOS CARGADOS:', response);
            // Procesar respuesta...
        },
        error: function(error) {
            console.error('❌ ERROR AL CARGAR DATOS:', error);
        }
    });
    */
}

// ============================================================================
// FUNCIONES DEL LAYOUT ORIGINALES
// ============================================================================

/**
 * Toggle del panel lateral
 */
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
            if (window.innerWidth <= 768) {
                toggle.style.left = '165px';
            } else {
                toggle.style.left = '205px';
            }
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

/**
 * Toggle de grupos de filtros
 */
function toggleFilterGroup(groupName) {
    const items = document.getElementById(`items-${groupName}`);
    const controls = document.getElementById(`controls-${groupName}`);
    const icon = document.getElementById(`icon-${groupName}`);
    
    if (items) {
        const isCollapsed = items.classList.contains('collapsed');
        
        if (isCollapsed) {
            // EXPANDIR
            items.classList.remove('collapsed');
            
            if (controls) {
                controls.classList.remove('collapsed');
            }
            
            if (icon) {
                icon.classList.remove('collapsed');
                icon.className = 'fas fa-chevron-down filter-collapse-icon';
            }
            
            console.log(`🔍 ${groupName.toUpperCase()} EXPANDIDO`);
        } else {
            // COLAPSAR
            items.classList.add('collapsed');
            
            if (controls) {
                controls.classList.add('collapsed');
            }
            
            if (icon) {
                icon.classList.add('collapsed');
                icon.className = 'fas fa-chevron-right filter-collapse-icon collapsed';
            }
            
            console.log(`📦 ${groupName.toUpperCase()} COLAPSADO`);
        }
        
        localStorage.setItem(MODULE_PREFIX + `filter_${groupName}_state`, isCollapsed ? 'expanded' : 'collapsed');
    }
}

/**
 * Restaurar estado del layout
 */
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
        
        if (window.innerWidth <= 768) {
            toggle.style.left = '165px';
        } else {
            toggle.style.left = '205px';
        }
    }
}

/**
 * Restaurar estados de filtros
 */
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
                
                if (controls) {
                    controls.classList.add('collapsed');
                }
                
                if (icon) {
                    icon.classList.add('collapsed');
                    icon.className = 'fas fa-chevron-right filter-collapse-icon collapsed';
                }
            }
        }
    });
    
    console.log('✅ ESTADOS RESTAURADOS');
}

// ============================================================================
// INICIALIZACIÓN
// ============================================================================

$(document).ready(function() {
    
    // Título
    $("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - TEMPLATE LAYOUT');
    
    // Inicializar layout
    restaurarEstadoLayout();
    restaurarEstadosFiltros();
    
    // Redimensionamiento
    $(window).resize(function() {
        const panel = document.getElementById('lateralPanel');
        const toggle = document.getElementById('layoutToggle');
        
        if (!panel.classList.contains('collapsed')) {
            if (window.innerWidth <= 768) {
                toggle.style.left = '165px';
            } else {
                toggle.style.left = '205px';
            }
        } else {
            toggle.style.left = '0px';
        }
    });
    
    // Botones de control
    $('.filter-btn').on('click', function() {
        const accion = $(this).text().trim();
        const grupo = $(this).closest('.filter-group').attr('id');
        console.log('🎛️ BOTÓN:', accion, 'en', grupo);
        
        if (accion === 'TODOS') {
            $(this).closest('.filter-group').find('input[type="checkbox"]').prop('checked', true);
            console.log('✅ TODOS activados');
        } else if (accion === 'LIMPIAR') {
            $(this).closest('.filter-group').find('input[type="checkbox"]').prop('checked', false);
            console.log('🧹 TODOS desactivados');
        } else if (accion === 'RESET') {
            $(this).closest('.filter-group').find('input[type="radio"]').first().prop('checked', true);
            console.log('🔄 Radio reseteado');
        }
    });
    
    // ============================================================================
    // LISTENER DE FECHAS - DETECTA CAMBIOS AUTOMÁTICAMENTE
    // ============================================================================
    
    /**
     * ESCUCHA CAMBIOS EN EL FILTRO DE FECHAS
     * 
     * Cada vez que el usuario cambia cualquier elemento del filtro de fechas,
     * se dispara automáticamente esta función. Aquí puedes agregar lógica
     * para recargar datos automáticamente.
     */
    $(document).on('change', '.radioPeriodo, .form-control', function() {
        const fechas = obtenerFechasFiltro();
        
        console.log('📅 FECHAS ACTUALES:', fechas.inicio, 'hasta', fechas.fin, '(' + fechas.tipo + ')');
        
        // ACTUALIZAR DISPLAY EN PANTALLA
        $('#fechas-display').text(`Inicio: ${fechas.inicio || '--'} | Fin: ${fechas.fin || '--'}`);
        
        // OPCIONAL: Recargar datos automáticamente
        // if (validarFechasFiltro()) {
        //     cargarDatosConFiltro();
        // }
    });
    
    // Finalización
    $('#loader').addClass('hidden');
    
    // INICIALIZAR DISPLAY DE FECHAS
    setTimeout(function() {
        const fechas = obtenerFechasFiltro();
        $('#fechas-display').text(`Inicio: ${fechas.inicio || '--'} | Fin: ${fechas.fin || '--'}`);
    }, 100);
    
    console.log('🎉 TEMPLATE LAYOUT CON BRIDGE DE FECHAS INICIALIZADO');
    console.log('📅 FUNCIONES DISPONIBLES:');
    console.log('   - obtenerFechasFiltro()');
    console.log('   - validarFechasFiltro()');
    console.log('   - cargarDatosConFiltro()');

});

</script>