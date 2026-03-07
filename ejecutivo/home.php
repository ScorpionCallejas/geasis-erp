<?php  
	include('inc/header.php');
    
    // ========================================
    // VALIDACIONES Y PERMISOS
    // ========================================
    if(($tipoUsuario == 'Ejecutivo' && $foto == NULL) || ($estatusUsuario == 'Inactivo')) {
        header('location: perfil.php');
        exit;
    }
    
    // ========================================
    // 🔍 VERIFICAR SI TIENE PLANTELES ASIGNADOS
    // ========================================
    $tienePlanteles = false;
    
    // Verificar planteles específicos en planteles_ejecutivo
    $sqlCheckPlanteles = "
        SELECT COUNT(*) as total 
        FROM planteles_ejecutivo 
        WHERE id_eje = '$id'
    ";
    $resultCheck = mysqli_query($db, $sqlCheckPlanteles);
    $filaCheck = mysqli_fetch_assoc($resultCheck);
    
    if($filaCheck['total'] > 0) {
        $tienePlanteles = true;
    } else {
        // Si no tiene planteles específicos, verificar si tiene plantel por defecto
        $sqlCheckDefault = "
            SELECT id_pla 
            FROM ejecutivo 
            WHERE id_eje = '$id' 
            AND id_pla IS NOT NULL
        ";
        $resultDefault = mysqli_query($db, $sqlCheckDefault);
        if(mysqli_num_rows($resultDefault) > 0) {
            $tienePlanteles = true;
        }
    }
    
    // ========================================
    // ✅ REDIRIGIR SI NO TIENE PLANTELES
    // ========================================
    if(!$tienePlanteles && $usuario == null) {
        $sqlSesion = "
            UPDATE ejecutivo
            SET ult_eje = CURDATE()
            WHERE id_eje = $id
        ";
        $resultadoSesion = mysqli_query($db, $sqlSesion);
        if(!$resultadoSesion){
            echo $sqlSesion;
        }
        header('location: citas_admisiones.php');
        exit;
    }
    
    // ========================================
    // 🎯 DETERMINAR QUÉ MÓDULOS MOSTRAR
    // ========================================
    $esAdmisiones = ($usuario == null && $tienePlanteles); // ADMISIONES: null + planteles
    $esAdministrativo = ($usuario != null); // ADMINISTRATIVO: no null
    
    $mostrarAdmisiones = true; // Siempre muestra admisiones
    $mostrarAdministrativo = $esAdministrativo; // Solo si es administrativo
?>

<!-- ========================================
     PANTALLA DE CARGA
     ======================================== -->
<?php
    if(isset($_SESSION['primera_visita']) && $_SESSION['primera_visita'] === true) {
        $sqlSesion = "
            UPDATE ejecutivo
            SET ult_eje = CURDATE()
            WHERE id_eje = $id
        ";
        $resultadoSesion = mysqli_query($db, $sqlSesion);
        if(!$resultadoSesion){
            echo $sqlSesion;
        }
        
        echo '<div id="loader" style="display: flex; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 1); z-index: 9999; transition: background-color 3s ease-out;">
                <div class="text-center">
                    <span class="logo-lg">
                        <img src="../img/logoLoginEslogan.png" alt="" height="66">
                    </span>
                    <span class="letraSicamInicio efectoBrillo animate__delay-1s animate__animated animate__backInDown animate__slower">SICAM</span>
                </div>
              </div>';

        $_SESSION['primera_visita'] = false;
?>

    <audio id="epicSound" src="../img/epic2.mp3"></audio>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var loader = document.getElementById('loader');
            setTimeout(function(){
                $("#epicSound")[0].play();
            }, 3000);
            
            if(loader) {
                setTimeout(function() {
                    loader.style.backgroundColor = 'rgba(0, 0, 0, 0)';
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 1000);
                }, 4000);
            }
        });
    </script>

<?php
    } else {
?>
        <div id="loader">
            <div class="spinner-border avatar-lg text-primary m-2" role="status">
            </div>
            <span class="letraSicam">SICAM</span>
        </div>
<?php
    }
?>

<!-- ========================================
     CSS
     ======================================== -->
<?php require_once 'modulos_dashboards/dashboard_css.php'; ?>

<!-- ========================================
     PAGE TITLE (OCULTO)
     ======================================== -->
<div class="row" style="display: none;">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                </ol>
            </div>
            <h4 class="page-title">HOME</h4>
        </div>
    </div>
</div>

<hr>

<!-- ========================================
     FILTROS
     ======================================== -->
<div class="row" style="display: none;">
    <div class="col-md-4">
        <select id="selector_plantel" class="form-control letraPequena filtros">
            <?php 
            if($id == '2311' || $id == '2601'){ 
            ?>
                <option value="0" selected>🕋 TODAS</option>
            <?php
            }
            
            $sqlPlantel = "
                SELECT *
                FROM planteles_ejecutivo 
                INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
                WHERE id_eje = '$id'
                ORDER BY plantel.id_pla ASC
            ";
            
            $resultadoPlantel = mysqli_query($db, $sqlPlantel);
            
            while($filaPlantel = mysqli_fetch_assoc($resultadoPlantel)){
            ?>
                <option value="<?php echo $filaPlantel['id_pla']; ?>">
                    🕋 <?php echo strtoupper($filaPlantel['nom_pla']); ?>
                </option>
            <?php
            }
            ?>
        </select>
    </div>

    <div class="col-md-4">
        <?php include('modulos/filtros_fechas.php'); ?>
    </div>
    
    <div class="col-md-4">
        <button class="btn-actualizar-moderno" id="btn-actualizar" onclick="obtener_datos()">
            <span class="icon-refresh">🔄</span>
            <span>Actualizar</span>
        </button>
    </div>
</div>

<!-- ========================================
     🎓 SECCIÓN CALENDARIO
     ======================================== -->
<?php require_once 'modulos/calendario.php'; ?>

<!-- ========================================
     🎓 DASHBOARD ADMISIONES
     ======================================== -->
<?php 
if($mostrarAdmisiones) {
    require_once 'modulos_dashboards/dashboard_admisiones.php'; 
}
?>

<!-- ========================================
     📋 DASHBOARD ADMINISTRATIVO
     ======================================== -->
<?php 
if($mostrarAdministrativo) {
    require_once 'modulos_dashboards/dashboard_administracion.php'; 
}
?>

<br>
<br>

<?php include('inc/footer.php'); ?>

<!-- ========================================
     CALENDARIO JS
     ======================================== -->
<?php require_once 'modulos/calendario_js.php'; ?>

<!-- ========================================
     SCRIPTS PRINCIPALES
     ======================================== -->
<script type="text/javascript">

// ========================================
// 🎯 CONFIGURACIÓN DE MÓDULOS A CARGAR
// ========================================
const MOSTRAR_ADMISIONES = <?php echo $mostrarAdmisiones ? 'true' : 'false'; ?>;
const MOSTRAR_ADMINISTRATIVO = <?php echo $mostrarAdministrativo ? 'true' : 'false'; ?>;

console.log('📊 Configuración de dashboards:');
console.log('   ✅ Admisiones:', MOSTRAR_ADMISIONES ? 'SÍ' : 'NO');
console.log('   ✅ Administrativo:', MOSTRAR_ADMINISTRATIVO ? 'SÍ' : 'NO');
console.log('   👤 Tipo usuario:', '<?php echo $esAdmisiones ? "ADMISIONES" : ($esAdministrativo ? "ADMINISTRATIVO" : "OTRO"); ?>');

// ========================================
// FUNCIONES COMPARTIDAS
// ========================================
function actualizarCambio(selector, cambio, porc, invertir = false) {
    const elem = $(selector);
    elem.removeClass('positivo negativo neutro');
    
    if(porc === null || porc === undefined) {
        elem.addClass('neutro');
        elem.text('—');
        return;
    }
    
    let esPositivo = cambio >= 0;
    if(invertir) esPositivo = !esPositivo;
    
    if(esPositivo) {
        elem.addClass('positivo');
        elem.text('▲ ' + Math.abs(porc).toFixed(1) + '%');
    } else {
        elem.addClass('negativo');
        elem.text('▼ ' + Math.abs(porc).toFixed(1) + '%');
    }
}

// ========================================
// FUNCIONES PARA OBTENER FECHAS DEL FILTRO
// ========================================
function obtenerFechasFiltro() {
    let resultado = {
        inicio: null,
        fin: null,
        tipo: ''
    };
    
    const tipoFiltro = document.querySelector('.radioPeriodo:checked');
    
    if(!tipoFiltro) {
        console.warn('❌ Ningún tipo de filtro seleccionado');
        return resultado;
    }
    
    const valorSeleccionado = tipoFiltro.value;
    resultado.tipo = valorSeleccionado;
    
    switch(valorSeleccionado) {
        case 'Fecha':
            const inputInicio = document.getElementById('inicio');
            const inputFin = document.getElementById('fin');
            
            if(inputInicio) {
                resultado.inicio = inputInicio.value;
                
                if(inputFin && inputFin.value) {
                    resultado.fin = inputFin.value;
                } else {
                    resultado.fin = resultado.inicio;
                }
                
                console.log('📅 FILTRO POR DÍA(S):', resultado.inicio, 'al', resultado.fin);
            }
            break;
            
        case 'Semana':
            const selectorSemana = document.getElementById('selectorSemana');
            
            if(selectorSemana) {
                const opcionSeleccionada = selectorSemana.options[selectorSemana.selectedIndex];
                
                if(opcionSeleccionada) {
                    resultado.inicio = opcionSeleccionada.getAttribute('inicio');
                    resultado.fin = opcionSeleccionada.getAttribute('fin');
                    
                    console.log('📅 FILTRO POR SEMANA:', resultado.inicio, 'al', resultado.fin);
                }
            }
            break;
            
        case 'Mes':
            const selectorMes = document.getElementById('selectorMes');
            
            if(selectorMes) {
                const opcionSeleccionada = selectorMes.options[selectorMes.selectedIndex];
                
                if(opcionSeleccionada) {
                    resultado.inicio = opcionSeleccionada.getAttribute('inicio');
                    resultado.fin = opcionSeleccionada.getAttribute('fin');
                    
                    console.log('📅 FILTRO POR MES:', resultado.inicio, 'al', resultado.fin);
                }
            }
            break;
            
        case 'Rango':
            const inputInicioRango = document.getElementById('inicio_rango');
            const inputFinRango = document.getElementById('fin_rango');
            
            if(inputInicioRango && inputFinRango) {
                resultado.inicio = inputInicioRango.value;
                resultado.fin = inputFinRango.value;
                
                console.log('📅 FILTRO POR RANGO:', resultado.inicio, 'al', resultado.fin);
            }
            break;
            
        default:
            console.warn('⚠️ Tipo de filtro no reconocido:', valorSeleccionado);
    }
    
    // Validar que las fechas estén en formato correcto
    if(resultado.inicio && resultado.fin) {
        const regexFecha = /^\d{4}-\d{2}-\d{2}$/;
        
        if(!regexFecha.test(resultado.inicio) || !regexFecha.test(resultado.fin)) {
            console.error('❌ ERROR: Formato de fecha incorrecto', resultado.inicio, resultado.fin);
            return {
                inicio: null,
                fin: null,
                tipo: ''
            };
        }
        
        if(new Date(resultado.inicio) > new Date(resultado.fin)) {
            console.warn('⚠️ ADVERTENCIA: Fecha de inicio posterior a fecha de fin. Intercambiando valores.');
            const temp = resultado.inicio;
            resultado.inicio = resultado.fin;
            resultado.fin = temp;
        }
    }
    
    return resultado;
}

// ========================================
// FORMATEO DE FECHAS PARA TÍTULOS
// ========================================
function formatearFechaTitulo(fechaInicio, fechaFin) {
    const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 
                   'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    
    const fechaInicioObj = new Date(fechaInicio + 'T00:00:00');
    const fechaFinObj = new Date(fechaFin + 'T00:00:00');
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    
    const ayer = new Date(hoy);
    ayer.setDate(ayer.getDate() - 1);
    
    if(fechaInicio === fechaFin) {
        if(fechaInicioObj.getTime() === hoy.getTime()) {
            return 'HOY';
        } else if(fechaInicioObj.getTime() === ayer.getTime()) {
            return 'AYER';
        } else {
            const dia = fechaInicioObj.getDate();
            const mes = meses[fechaInicioObj.getMonth()];
            const anio = fechaInicioObj.getFullYear();
            return `${dia} DE ${mes} ${anio}`;
        }
    } else {
        const diaInicio = fechaInicioObj.getDate();
        const mesInicio = meses[fechaInicioObj.getMonth()];
        const anioInicio = fechaInicioObj.getFullYear();
        
        const diaFin = fechaFinObj.getDate();
        const mesFin = meses[fechaFinObj.getMonth()];
        const anioFin = fechaFinObj.getFullYear();
        
        if(anioInicio === anioFin && mesInicio === mesFin) {
            return `${diaInicio} AL ${diaFin} DE ${mesInicio} ${anioInicio}`;
        } else if(anioInicio === anioFin) {
            return `${diaInicio} ${mesInicio} AL ${diaFin} ${mesFin} ${anioInicio}`;
        } else {
            return `${diaInicio} ${mesInicio} ${anioInicio} AL ${diaFin} ${mesFin} ${anioFin}`;
        }
    }
}

// ========================================
// OBTENER DASHBOARD UNIFICADO (SOLO JSON)
// ========================================
function obtener_dashboard_unificado(fechaInicio, fechaFin, id_pla) {
    // Deshabilitar botón
    const btnActualizar = document.getElementById('btn-actualizar');
    btnActualizar.disabled = true;
    btnActualizar.querySelector('.icon-refresh').classList.add('icon-spin');
    
    $.ajax({
        url: 'server/controlador_dashboard.php',
        type: 'POST',
        dataType: 'json',
        data: {
            inicio: fechaInicio,
            fin: fechaFin,
            id_pla: id_pla
        },
        success: function(data) {
            console.log('✅ Dashboard unificado cargado exitosamente', data);
            
            // ========================================
            // LLENAR MÓDULOS SEGÚN CONFIGURACIÓN
            // ========================================
            if(MOSTRAR_ADMISIONES) {
                console.log('🎓 Llenando módulo ADMISIONES...');
                llenarAdmisiones(data.admisiones);
                
                // FADE IN ADMISIONES
                const skeletonAdm = document.getElementById('skeleton_admisiones');
                const contenedorAdm = document.getElementById('contenedor_admisiones');
                
                skeletonAdm.classList.add('fade-out');
                setTimeout(() => {
                    skeletonAdm.style.display = 'none';
                    contenedorAdm.style.display = 'block';
                    contenedorAdm.classList.add('fade-in');
                }, 300);
            }
            
            if(MOSTRAR_ADMINISTRATIVO) {
                console.log('📋 Llenando módulo ADMINISTRATIVO...');
                llenarAdministrativo(data.administrativo);
                
                // FADE IN ADMINISTRATIVO
                const skeletonAdmin = document.getElementById('skeleton_administrativo');
                const contenedorAdmin = document.getElementById('contenedor_administrativo');
                
                skeletonAdmin.classList.add('fade-out');
                setTimeout(() => {
                    skeletonAdmin.style.display = 'none';
                    contenedorAdmin.style.display = 'block';
                    contenedorAdmin.classList.add('fade-in');
                    
                    // INICIAR FEED (solo si está administrativo)
                    iniciarFeedActividad();
                }, 300);
            }
            
            // Ocultar loader principal
            $('#loader').addClass('hidden');
            
            // Rehabilitar botón
            btnActualizar.disabled = false;
            btnActualizar.querySelector('.icon-refresh').classList.remove('icon-spin');
        },
        error: function(xhr, status, error) {
            console.error('❌ Error al cargar dashboard:', error);
            alert('Error al cargar los datos. Por favor intenta nuevamente.');
            
            // Rehabilitar botón
            btnActualizar.disabled = false;
            btnActualizar.querySelector('.icon-refresh').classList.remove('icon-spin');
            $('#loader').addClass('hidden');
        }
    });
}

// ========================================
// EVENT LISTENERS
// ========================================
$(window).on('load', function() {
    $('#loader').addClass('hidden');
});

$('.filtros, .radioPeriodo, #selectorMes, #selectorSemana, #inicio, #fin, #inicio_rango, #fin_rango').on('change', function(event) {
    event.preventDefault();
    if(typeof ultimoIdLog !== 'undefined') {
        ultimoIdLog = 0;
    }
});

// ========================================
// FUNCIÓN PRINCIPAL
// ========================================
function obtener_datos() {
    const fechas = obtenerFechasFiltro();
    
    if(!fechas.inicio || !fechas.fin) {
        swal({
            title: "Error",
            text: "Debe seleccionar un período válido",
            icon: "warning"
        });
        return;
    }
    
    var id_pla = $('#selector_plantel option:selected').val();
    
    var textoFecha = formatearFechaTitulo(fechas.inicio, fechas.fin);
    
    if(MOSTRAR_ADMISIONES) {
        $('#titulo_fecha_admisiones').text(textoFecha);
    }
    
    if(MOSTRAR_ADMINISTRATIVO) {
        $('#titulo_fecha_administrativo').text(textoFecha);
    }
    
    console.log('🔄 ACTUALIZANDO DASHBOARDS:', fechas.inicio, 'al', fechas.fin, '| Plantel:', id_pla);
    
    // Mostrar skeletons según configuración
    if(MOSTRAR_ADMISIONES) {
        document.getElementById('skeleton_admisiones').style.display = 'block';
        document.getElementById('contenedor_admisiones').style.display = 'none';
        document.getElementById('skeleton_admisiones').classList.remove('fade-out');
        document.getElementById('contenedor_admisiones').classList.remove('fade-in');
    }
    
    if(MOSTRAR_ADMINISTRATIVO) {
        document.getElementById('skeleton_administrativo').style.display = 'block';
        document.getElementById('contenedor_administrativo').style.display = 'none';
        document.getElementById('skeleton_administrativo').classList.remove('fade-out');
        document.getElementById('contenedor_administrativo').classList.remove('fade-in');
    }
    
    // Llamar al controlador unificado
    obtener_dashboard_unificado(fechas.inicio, fechas.fin, id_pla);
}

// ========================================
// INICIALIZACIÓN
// ========================================
google.charts.load('current', {'packages':['corechart', 'bar']});
google.charts.setOnLoadCallback(function() {
    obtener_datos();
});

</script>

<script>
    $("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - HOME');
</script>