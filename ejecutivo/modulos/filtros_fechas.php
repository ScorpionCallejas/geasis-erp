<?php  
// ============================================================================
// DETECCIÓN DE PARÁMETROS URL
// ============================================================================
$tieneParametrosURL = false;
$fechaInicioURL = null;
$fechaFinURL = null;
$filtroDefault = 'Fecha';

if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
    $fechaInicioURL = $_GET['fecha_inicio'];
    $fechaFinURL    = $_GET['fecha_fin'];
    
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicioURL) && 
        preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFinURL)) {
        
        $tieneParametrosURL = true;
        
        if ($fechaInicioURL === $fechaFinURL) {
            $filtroDefault = 'Fecha';
        } else {
            $diff = (strtotime($fechaFinURL) - strtotime($fechaInicioURL)) / 86400;
            if ($diff >= 6 && $diff <= 7) {
                $filtroDefault = 'Semana';
            } elseif ($diff >= 28 && $diff <= 31) {
                $filtroDefault = 'Mes';
            } else {
                $filtroDefault = 'Rango';
            }
        }
    }
}
?>

<style>
.filtros-fecha-container {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 3px;
    padding: 3px 6px;
}
.filtros-fecha-container .form-check-inline {
    margin-right: 6px;
    margin-bottom: 3px;
}
.filtros-fecha-container .form-check-label {
    font-size: 10px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}
.filtros-fecha-container .form-check-input:checked ~ .form-check-label {
    color: #007bff;
    font-weight: 600;
}
.filtros-fecha-container .form-control {
    font-size: 10px;
    border-radius: 2px;
    padding: 2px 4px;
}
.filtros-fecha-container .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
}
.contenedor-filtro {
    margin-top: 3px;
}
</style>

<div class="filtros-fecha-container text-center">

    <!-- RADIO BUTTONS -->
    <div class="form-check form-check-inline">
        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22"
            name="seleccionPeriodo" value="Mes"
            <?php echo ($filtroDefault === 'Mes') ? 'checked' : ''; ?>>
        <label class="form-check-label" for="materialGroupExample22">Mensual</label>
    </div>
    <div class="form-check form-check-inline">
        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11"
            name="seleccionPeriodo" value="Fecha"
            <?php echo ($filtroDefault === 'Fecha') ? 'checked' : ''; ?>>
        <label class="form-check-label" for="materialGroupExample11">Día(s)</label>
    </div>
    <div class="form-check form-check-inline">
        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample23"
            name="seleccionPeriodo" value="Semana"
            <?php echo ($filtroDefault === 'Semana') ? 'checked' : ''; ?>>
        <label class="form-check-label" for="materialGroupExample23">Semanal</label>
    </div>
    <div class="form-check form-check-inline">
        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample24"
            name="seleccionPeriodo" value="Rango"
            <?php echo ($filtroDefault === 'Rango') ? 'checked' : ''; ?>>
        <label class="form-check-label" for="materialGroupExample24">Rango</label>
    </div>

    <!-- CONTENEDOR MES/AÑO -->
    <div id="contenedor_mes_annio" class="contenedor-filtro"
        style="display: <?php echo ($filtroDefault === 'Mes') ? 'block' : 'none'; ?>;">
        <div class="row">
            <div class="col-md-12">
                <select class="form-control filtros letraPequena" id="selectorMes">
                    <?php
                    $mesActual = date('m');
                    $añoActual = date('Y');
                    $años = array(2024, 2025, 2026);
                    foreach ($años as $año) {
                        for ($i = 1; $i <= 12; $i++) {
                            $mes = str_pad($i, 2, "0", STR_PAD_LEFT);
                            $primerDia = new DateTime("{$año}-{$mes}-01");
                            $ultimoDia = clone $primerDia;
                            $ultimoDia->modify('last day of this month');
                            $primerLunes = clone $primerDia;
                            while ($primerLunes->format('N') != 1) { $primerLunes->modify('-1 day'); }
                            if ($primerLunes->format('m') != $mes) { $primerLunes->modify('+1 week'); }
                            $ultimoLunes = clone $ultimoDia;
                            while ($ultimoLunes->format('N') != 1) { $ultimoLunes->modify('-1 day'); }
                            if ($ultimoLunes->format('m') != $mes) { $ultimoLunes->modify('-1 week'); }
                            $finSemana = clone $ultimoLunes;
                            $finSemana->modify('+6 days');
                            $inicio    = $primerLunes->format('Y-m-d');
                            $fin       = $finSemana->format('Y-m-d');
                            $fmtInicio = $primerLunes->format('d/m/Y');
                            $fmtFin    = $finSemana->format('d/m/Y');
                            $semana1   = $primerLunes->format('W');
                            $semana2   = $ultimoLunes->format('W');
                            $selected  = ($mes == $mesActual && $año == $añoActual) ? 'selected' : '';
                            echo "<option {$selected} value='{$mes}' inicio='{$inicio}' fin='{$fin}'>" .
                                 getMonth($mes) . " {$año} - Semanas {$semana1}-{$semana2}" .
                                 " ({$fmtInicio} al {$fmtFin})</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CONTENEDOR FECHA                                             -->
    <!-- FIX PRINCIPAL: id="fin" SIEMPRE presente en TODOS los casos -->
    <!-- Sin esto, $_POST['fin'] llega vacío → Undefined index        -->
    <!-- ============================================================ -->
    <div id="contenedor_fecha" class="contenedor-filtro"
        style="display: <?php echo ($filtroDefault === 'Fecha') ? 'block' : 'none'; ?>;">
        <?php
        $valorInicio = $tieneParametrosURL ? $fechaInicioURL : date('Y-m-d');
        $valorFin    = $tieneParametrosURL ? $fechaFinURL    : date('Y-m-d');
        ?>
        <div class="row">
            <div class="col-md-6">
                <input type="date" class="form-control filtros letraPequena" id="inicio"
                    value="<?php echo $valorInicio; ?>">
            </div>
            <div class="col-md-6">
                <input type="date" class="form-control filtros letraPequena" id="fin"
                    value="<?php echo $valorFin; ?>">
            </div>
        </div>
    </div>

    <!-- CONTENEDOR SEMANA -->
    <div id="contenedor_semana" class="contenedor-filtro"
        style="display: <?php echo ($filtroDefault === 'Semana') ? 'block' : 'none'; ?>;">
        <select class="form-control filtros letraPequena" id="selectorSemana">
            <?php
            $añoActual   = date('Y');
            $añoAnterior = $añoActual - 1;
            $fechaInicioGen = $añoAnterior . '-01-01';
            $fechaFinGen    = $añoActual   . '-12-31';
            $fechaHoy = new DateTime('now');
            $fechaHoy->setTime(0, 0, 0);
            $semanas = array();
            $fecha   = $fechaInicioGen;
            while ($fecha <= $fechaFinGen) {
                $inicio = new DateTime($fecha);
                $inicio->modify('monday this week');
                $inicio->setTime(0, 0, 0);
                $fin = clone $inicio;
                $fin->modify('sunday this week');
                $fin->setTime(23, 59, 59);
                $numSemana = $inicio->format('W');
                $añoSemana = $inicio->format('Y');
                if ($añoSemana == $añoAnterior && $inicio->format('Y-m-d') >= $añoAnterior . '-12-30') {
                    $numSemana = "53";
                } elseif ($añoSemana == $añoActual) {
                    $primerLunes = new DateTime($añoActual . '-01-01');
                    $primerLunes->modify('first monday of january ' . $añoActual);
                    if ($inicio >= $primerLunes) {
                        $diff      = $inicio->diff($primerLunes);
                        $numSemana = str_pad(floor($diff->days / 7) + 1, 2, '0', STR_PAD_LEFT);
                    }
                }
                $inicioFormatted = fechaFormateadaCompacta2($inicio->format('Y-m-d'));
                $finFormatted    = fechaFormateadaCompacta2($fin->format('Y-m-d'));
                $esActual        = ($fechaHoy >= $inicio && $fechaHoy <= $fin);
                $esSeleccionada  = false;
                if ($tieneParametrosURL && $filtroDefault === 'Semana') {
                    $inicioURL = new DateTime($fechaInicioURL);
                    $finURL    = new DateTime($fechaFinURL);
                    $esSeleccionada = (
                        $inicio->format('Y-m-d') === $inicioURL->format('Y-m-d') &&
                        $fin->format('Y-m-d')    === $finURL->format('Y-m-d')
                    );
                }
                if (in_array($inicio->format('Y'), array($añoAnterior, $añoActual))) {
                    $semanas[] = array(
                        'inicio'         => $inicio->format('Y-m-d'),
                        'fin'            => $fin->format('Y-m-d'),
                        'texto'          => "Semana {$numSemana} - {$inicioFormatted} al {$finFormatted}",
                        'esActual'       => $esActual,
                        'esSeleccionada' => $esSeleccionada
                    );
                }
                $fecha = $inicio->modify('+1 week')->format('Y-m-d');
            }
            $semanas = array_reverse($semanas);
            foreach ($semanas as $semana) {
                $selected = ($semana['esSeleccionada'] || (!$tieneParametrosURL && $semana['esActual']))
                            ? 'selected' : '';
                echo "<option {$selected} class='letraPequena'" .
                     " inicio='{$semana['inicio']}' fin='{$semana['fin']}'>" .
                     $semana['texto'] . "</option>";
            }
            ?>
        </select>
    </div>

    <!-- CONTENEDOR RANGO -->
    <div id="contenedor_rango" class="contenedor-filtro"
        style="display: <?php echo ($filtroDefault === 'Rango') ? 'block' : 'none'; ?>;">
        <div class="row">
            <div class="col-md-6">
                <input type="date" class="form-control filtros letraPequena" id="inicio_rango"
                    value="<?php echo $tieneParametrosURL ? $fechaInicioURL : date('Y-m-d'); ?>">
            </div>
            <div class="col-md-6">
                <input type="date" class="form-control filtros letraPequena" id="fin_rango"
                    value="<?php echo $tieneParametrosURL ? $fechaFinURL : date('Y-m-d'); ?>">
            </div>
        </div>
    </div>

</div><!-- /.filtros-fecha-container -->

<script>
document.addEventListener('DOMContentLoaded', function() {

    function alternarContenedoresFecha() {
        var radioSeleccionado = document.querySelector('.radioPeriodo:checked');
        var tipoSeleccionado  = radioSeleccionado ? radioSeleccionado.value : '';

        var ids = ['contenedor_fecha', 'contenedor_semana', 'contenedor_mes_annio', 'contenedor_rango'];
        for (var i = 0; i < ids.length; i++) {
            var el = document.getElementById(ids[i]);
            if (el) el.style.display = 'none';
        }

        var mapa = {
            'Fecha'  : 'contenedor_fecha',
            'Semana' : 'contenedor_semana',
            'Mes'    : 'contenedor_mes_annio',
            'Rango'  : 'contenedor_rango'
        };

        if (mapa[tipoSeleccionado]) {
            var target = document.getElementById(mapa[tipoSeleccionado]);
            if (target) target.style.display = 'block';
        }
    }

    var radioButtons = document.querySelectorAll('.radioPeriodo');
    for (var i = 0; i < radioButtons.length; i++) {
        radioButtons[i].addEventListener('change', alternarContenedoresFecha);
    }
});
</script>