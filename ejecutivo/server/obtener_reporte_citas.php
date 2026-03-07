<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$tipo_vista = isset($_POST['tipo_vista']) ? $_POST['tipo_vista'] : 'reportes';

// Función para obtener clase de color según porcentaje
if(!function_exists('getColorClass')) {
	function getColorClass($porc) {
		if($porc >= 80) return 'valor-verde';
		if($porc >= 50) return 'valor-amarillo';
		return 'valor-rojo';
	}
}
?>

<style>
.table td, .table th { padding: 2px 4px; }
.tabla-texto-small { font-size: 11px; }
.tabla-texto-small td, .tabla-texto-small th { font-size: 11px; }
.text-center-header { text-align: center !important; vertical-align: middle !important; }
.bg-plantel { background-color: #95A5A6 !important; color: white !important; font-weight: bold; }
.bg-ejecutivo { background-color: #2C3E50 !important; color: white !important; font-weight: bold; }
.bg-subtotal { background-color: #BDC3C7 !important; font-weight: bold; }
.bg-total { background-color: #1ABC9C !important; color: white !important; font-weight: bold; }
.bg-meta { background-color: #F39C12 !important; color: white !important; text-align: center; }
.bg-dia { background-color: #3498DB !important; color: white !important; text-align: center; }
.valor-verde { background-color: #27AE60 !important; color: white !important; }
.valor-rojo { background-color: #E74C3C !important; color: white !important; }
.valor-amarillo { background-color: #F1C40F !important; color: black !important; }
.badge-contacto { background-color: #FFF9C4; color: #000; padding: 2px 8px; border-radius: 12px; font-weight: bold; }
.badge-cita { background-color: #FFB74D; color: #000; padding: 2px 8px; border-radius: 12px; font-weight: bold; }
.badge-citaefe { background-color: #FFCDD2; color: #C62828; padding: 2px 8px; border-radius: 12px; font-weight: bold; }
.badge-registro { background-color: #4DD0E1; color: #000; padding: 2px 8px; border-radius: 12px; font-weight: bold; }
</style>

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<?php if($tipo_vista == 'embudo'): ?>
<!-- ============================================== -->
<!-- VISTA EMBUDO -->
<!-- ============================================== -->

<h3>REPORTE EMBUDO POR EJECUTIVO</h3>
<span class="letraPequena grey-text"><?php echo obtenerTituloReporte($inicio, $fin); ?></span>
<hr>

<?php
$fecha_inicio_obj = new DateTime($inicio);
$fecha_fin_obj = new DateTime($fin);
$dias = array();
$nombres_dias = array('DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB');

$fecha_temp = clone $fecha_inicio_obj;
while($fecha_temp <= $fecha_fin_obj) {
	$dias[] = array(
		'fecha' => $fecha_temp->format('Y-m-d'),
		'dia_semana' => $nombres_dias[$fecha_temp->format('w')],
		'dia_num' => $fecha_temp->format('d')
	);
	$fecha_temp->modify('+1 day');
}

$num_dias = count($dias);
?>

<div class="table-responsive">
	<table class="table table-bordered tabla-texto-small" id="tabla_embudo">
		<thead>
			<tr>
				<th style="background-color: #2C3E50; color: white; min-width: 200px;">EJECUTIVO / METRICA</th>
				<th style="background-color: #F39C12; color: white; width: 50px;">META</th>
				<?php foreach($dias as $dia): ?>
				<th class="bg-dia" style="min-width: 45px;"><?php echo $dia['dia_semana']; ?><br><small><?php echo $dia['dia_num']; ?></small></th>
				<?php endforeach; ?>
				<th style="background-color: #2C3E50; color: white; width: 55px;">TOTAL</th>
				<th style="background-color: #2C3E50; color: white; width: 50px;">%</th>
			</tr>
		</thead>
		<tbody>
<?php
$sql_planteles = "SELECT DISTINCT p.id_pla, p.nom_pla FROM plantel p INNER JOIN planteles_ejecutivo pe ON pe.id_pla = p.id_pla WHERE pe.id_eje = $id ORDER BY p.id_pla ASC";
$resultado_planteles = mysqli_query($db, $sql_planteles);

$totales_globales = array();
foreach($dias as $dia) { 
	$totales_globales[$dia['fecha']] = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0); 
}
$totales_globales_sum = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0);
$totales_metas_globales = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0);

while($plantel = mysqli_fetch_assoc($resultado_planteles)):
?>
			<tr class="bg-plantel">
				<td><?php echo strtoupper($plantel['nom_pla']); ?></td>
				<td></td>
				<?php for($i = 0; $i < $num_dias; $i++): ?><td></td><?php endfor; ?>
				<td></td>
				<td></td>
			</tr>
<?php
$sql_ejecutivos = "SELECT e.id_eje, e.nom_eje FROM ejecutivo e WHERE e.est_eje = 'Activo' AND e.tip_eje = 'Ejecutivo' AND e.eli_eje = 'Activo' AND (e.usu_eje IS NULL) AND e.id_pla = {$plantel['id_pla']} ORDER BY e.nom_eje ASC";
$resultado_ejecutivos = mysqli_query($db, $sql_ejecutivos);

$subtotales_plantel = array();
foreach($dias as $dia) { 
	$subtotales_plantel[$dia['fecha']] = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0); 
}
$subtotales_plantel_sum = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0);
$subtotales_metas_plantel = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0);

while($ejecutivo = mysqli_fetch_assoc($resultado_ejecutivos)):
	$datos_eje = array('contactos' => array(), 'citas' => array(), 'asesorias' => array(), 'registros' => array());
	$totales_eje = array('contactos' => 0, 'citas' => 0, 'asesorias' => 0, 'registros' => 0);

	// Obtener UNA meta por rubro para todo el periodo (inicio-fin)
	$res = mysqli_query($db, "SELECT obtener_meta_ejecutivo({$ejecutivo['id_eje']}, 'Contacto', '$inicio', '$fin') AS meta");
	$row = mysqli_fetch_assoc($res);
	$meta_contacto = isset($row['meta']) ? intval($row['meta']) : 0;
	
	$res = mysqli_query($db, "SELECT obtener_meta_ejecutivo({$ejecutivo['id_eje']}, 'Cita', '$inicio', '$fin') AS meta");
	$row = mysqli_fetch_assoc($res);
	$meta_cita = isset($row['meta']) ? intval($row['meta']) : 0;
	
	$res = mysqli_query($db, "SELECT obtener_meta_ejecutivo({$ejecutivo['id_eje']}, 'CitaEfectiva', '$inicio', '$fin') AS meta");
	$row = mysqli_fetch_assoc($res);
	$meta_asesoria = isset($row['meta']) ? intval($row['meta']) : 0;
	
	$res = mysqli_query($db, "SELECT obtener_meta_ejecutivo({$ejecutivo['id_eje']}, 'Registro', '$inicio', '$fin') AS meta");
	$row = mysqli_fetch_assoc($res);
	$meta_registro = isset($row['meta']) ? intval($row['meta']) : 0;

	// Metas del periodo (meta diaria * número de días)
	$metas_periodo = array(
		'contactos' => $meta_contacto * $num_dias,
		'citas' => $meta_cita * $num_dias,
		'asesorias' => $meta_asesoria * $num_dias,
		'registros' => $meta_registro * $num_dias
	);
	
	// Metas diarias (para comparar cada celda)
	$metas_diarias = array(
		'contactos' => $meta_contacto,
		'citas' => $meta_cita,
		'asesorias' => $meta_asesoria,
		'registros' => $meta_registro
	);

	foreach($dias as $dia) {
		$fecha = $dia['fecha'];
		
		// Obtener datos reales
		$res = mysqli_query($db, "SELECT obtener_contactos_ejecutivo({$ejecutivo['id_eje']}, '$fecha', '$fecha') AS total");
		$row = mysqli_fetch_assoc($res);
		$contactos = isset($row['total']) ? intval($row['total']) : 0;
		
		$res = mysqli_query($db, "SELECT obtener_citas_ejecutivo({$ejecutivo['id_eje']}, '$fecha', '$fecha') AS total");
		$row = mysqli_fetch_assoc($res);
		$citas = isset($row['total']) ? intval($row['total']) : 0;
		
		$res = mysqli_query($db, "SELECT obtener_citas_efectivas_ejecutivo({$ejecutivo['id_eje']}, '$fecha', '$fecha') AS total");
		$row = mysqli_fetch_assoc($res);
		$asesorias = isset($row['total']) ? intval($row['total']) : 0;
		
		$res = mysqli_query($db, "SELECT obtener_registros_ejecutivo({$ejecutivo['id_eje']}, '$fecha', '$fecha') AS total");
		$row = mysqli_fetch_assoc($res);
		$registros = isset($row['total']) ? intval($row['total']) : 0;

		$datos_eje['contactos'][$fecha] = $contactos;
		$datos_eje['citas'][$fecha] = $citas;
		$datos_eje['asesorias'][$fecha] = $asesorias;
		$datos_eje['registros'][$fecha] = $registros;

		$totales_eje['contactos'] += $contactos;
		$totales_eje['citas'] += $citas;
		$totales_eje['asesorias'] += $asesorias;
		$totales_eje['registros'] += $registros;

		$subtotales_plantel[$fecha]['contactos'] += $contactos;
		$subtotales_plantel[$fecha]['citas'] += $citas;
		$subtotales_plantel[$fecha]['asesorias'] += $asesorias;
		$subtotales_plantel[$fecha]['registros'] += $registros;

		$totales_globales[$fecha]['contactos'] += $contactos;
		$totales_globales[$fecha]['citas'] += $citas;
		$totales_globales[$fecha]['asesorias'] += $asesorias;
		$totales_globales[$fecha]['registros'] += $registros;
	}

	$subtotales_plantel_sum['contactos'] += $totales_eje['contactos'];
	$subtotales_plantel_sum['citas'] += $totales_eje['citas'];
	$subtotales_plantel_sum['asesorias'] += $totales_eje['asesorias'];
	$subtotales_plantel_sum['registros'] += $totales_eje['registros'];
	
	// Acumular metas del plantel
	$subtotales_metas_plantel['contactos'] += $metas_periodo['contactos'];
	$subtotales_metas_plantel['citas'] += $metas_periodo['citas'];
	$subtotales_metas_plantel['asesorias'] += $metas_periodo['asesorias'];
	$subtotales_metas_plantel['registros'] += $metas_periodo['registros'];

	// Calcular porcentajes usando metas del periodo (si meta = 0, porcentaje = 0)
	$porc_contactos = ($metas_periodo['contactos'] > 0) ? round(($totales_eje['contactos'] / $metas_periodo['contactos']) * 100, 0) : 0;
	$porc_citas = ($metas_periodo['citas'] > 0) ? round(($totales_eje['citas'] / $metas_periodo['citas']) * 100, 0) : 0;
	$porc_asesorias = ($metas_periodo['asesorias'] > 0) ? round(($totales_eje['asesorias'] / $metas_periodo['asesorias']) * 100, 0) : 0;
	$porc_registros = ($metas_periodo['registros'] > 0) ? round(($totales_eje['registros'] / $metas_periodo['registros']) * 100, 0) : 0;
?>
			<tr class="bg-ejecutivo">
				<td><?php echo strtoupper($ejecutivo['nom_eje']); ?></td>
				<td></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $dia['dia_semana']; ?><br><small><?php //echo $dia['dia_num']; ?></small></td>
				<?php endforeach; ?>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td style="padding-left: 20px;"><span class="badge-contacto">CONTACTOS</span></td>
				<td class="text-center" style="font-weight:bold;"><?php echo $metas_periodo['contactos']; ?></td>
				<?php foreach($dias as $dia): 
					$val = $datos_eje['contactos'][$dia['fecha']]; 
					$porc_dia = ($metas_diarias['contactos'] > 0) ? round(($val / $metas_diarias['contactos']) * 100, 0) : 0;
					$class = '';
					if($metas_diarias['contactos'] > 0) {
						$class = ($porc_dia >= 80) ? 'valor-verde' : (($porc_dia >= 50) ? 'valor-amarillo' : (($val > 0) ? 'valor-rojo' : ''));
					}
				?>
				<td class="text-center <?php echo $class; ?>"><?php echo $val; ?></td>
				<?php endforeach; ?>
				<td class="text-center" style="font-weight: bold;"><?php echo $totales_eje['contactos']; ?></td>
				<td class="text-center <?php echo ($metas_periodo['contactos'] > 0) ? getColorClass($porc_contactos) : ''; ?>"><?php echo $porc_contactos; ?>%</td>
			</tr>
			<tr>
				<td style="padding-left: 20px;"><span class="badge-cita">CITAS</span></td>
				<td class="text-center" style="font-weight:bold;"><?php echo $metas_periodo['citas']; ?></td>
				<?php foreach($dias as $dia): 
					$val = $datos_eje['citas'][$dia['fecha']]; 
					$porc_dia = ($metas_diarias['citas'] > 0) ? round(($val / $metas_diarias['citas']) * 100, 0) : 0;
					$class = '';
					if($metas_diarias['citas'] > 0) {
						$class = ($porc_dia >= 80) ? 'valor-verde' : (($porc_dia >= 50) ? 'valor-amarillo' : (($val > 0) ? 'valor-rojo' : ''));
					}
				?>
				<td class="text-center <?php echo $class; ?>"><?php echo $val; ?></td>
				<?php endforeach; ?>
				<td class="text-center" style="font-weight: bold;"><?php echo $totales_eje['citas']; ?></td>
				<td class="text-center <?php echo ($metas_periodo['citas'] > 0) ? getColorClass($porc_citas) : ''; ?>"><?php echo $porc_citas; ?>%</td>
			</tr>
			<tr>
				<td style="padding-left: 20px;"><span class="badge-citaefe">ASESORIAS</span></td>
				<td class="text-center" style="font-weight:bold;"><?php echo $metas_periodo['asesorias']; ?></td>
				<?php foreach($dias as $dia): 
					$val = $datos_eje['asesorias'][$dia['fecha']]; 
					$porc_dia = ($metas_diarias['asesorias'] > 0) ? round(($val / $metas_diarias['asesorias']) * 100, 0) : 0;
					$class = '';
					if($metas_diarias['asesorias'] > 0) {
						$class = ($porc_dia >= 80) ? 'valor-verde' : (($porc_dia >= 50) ? 'valor-amarillo' : (($val > 0) ? 'valor-rojo' : ''));
					}
				?>
				<td class="text-center <?php echo $class; ?>"><?php echo $val; ?></td>
				<?php endforeach; ?>
				<td class="text-center" style="font-weight: bold;"><?php echo $totales_eje['asesorias']; ?></td>
				<td class="text-center <?php echo ($metas_periodo['asesorias'] > 0) ? getColorClass($porc_asesorias) : ''; ?>"><?php echo $porc_asesorias; ?>%</td>
			</tr>
			<tr>
				<td style="padding-left: 20px;"><span class="badge-registro">REGISTROS</span></td>
				<td class="text-center" style="font-weight:bold;"><?php echo $metas_periodo['registros']; ?></td>
				<?php foreach($dias as $dia): 
					$val = $datos_eje['registros'][$dia['fecha']]; 
					$porc_dia = ($metas_diarias['registros'] > 0) ? round(($val / $metas_diarias['registros']) * 100, 0) : 0;
					$class = '';
					if($metas_diarias['registros'] > 0) {
						$class = ($porc_dia >= 80) ? 'valor-verde' : (($porc_dia >= 50) ? 'valor-amarillo' : (($val > 0) ? 'valor-rojo' : ''));
					}
				?>
				<td class="text-center <?php echo $class; ?>"><?php echo $val; ?></td>
				<?php endforeach; ?>
				<td class="text-center" style="font-weight: bold;"><?php echo $totales_eje['registros']; ?></td>
				<td class="text-center <?php echo ($metas_periodo['registros'] > 0) ? getColorClass($porc_registros) : ''; ?>"><?php echo $porc_registros; ?>%</td>
			</tr>
<?php endwhile; // fin ejecutivos ?>
			<tr class="bg-subtotal">
				<td>SUBTOTAL CONTACTOS</td>
				<td class="text-center"><?php echo $subtotales_metas_plantel['contactos']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $subtotales_plantel[$dia['fecha']]['contactos']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $subtotales_plantel_sum['contactos']; ?></td>
				<td class="text-center"><?php echo ($subtotales_metas_plantel['contactos'] > 0) ? round(($subtotales_plantel_sum['contactos'] / $subtotales_metas_plantel['contactos']) * 100, 0) : 0; ?>%</td>
			</tr>
			<tr class="bg-subtotal">
				<td>SUBTOTAL CITAS</td>
				<td class="text-center"><?php echo $subtotales_metas_plantel['citas']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $subtotales_plantel[$dia['fecha']]['citas']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $subtotales_plantel_sum['citas']; ?></td>
				<td class="text-center"><?php echo ($subtotales_metas_plantel['citas'] > 0) ? round(($subtotales_plantel_sum['citas'] / $subtotales_metas_plantel['citas']) * 100, 0) : 0; ?>%</td>
			</tr>
			<tr class="bg-subtotal">
				<td>SUBTOTAL ASESORIAS</td>
				<td class="text-center"><?php echo $subtotales_metas_plantel['asesorias']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $subtotales_plantel[$dia['fecha']]['asesorias']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $subtotales_plantel_sum['asesorias']; ?></td>
				<td class="text-center"><?php echo ($subtotales_metas_plantel['asesorias'] > 0) ? round(($subtotales_plantel_sum['asesorias'] / $subtotales_metas_plantel['asesorias']) * 100, 0) : 0; ?>%</td>
			</tr>
			<tr class="bg-subtotal">
				<td>SUBTOTAL REGISTROS</td>
				<td class="text-center"><?php echo $subtotales_metas_plantel['registros']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $subtotales_plantel[$dia['fecha']]['registros']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $subtotales_plantel_sum['registros']; ?></td>
				<td class="text-center"><?php echo ($subtotales_metas_plantel['registros'] > 0) ? round(($subtotales_plantel_sum['registros'] / $subtotales_metas_plantel['registros']) * 100, 0) : 0; ?>%</td>
			</tr>
<?php
$totales_globales_sum['contactos'] += $subtotales_plantel_sum['contactos'];
$totales_globales_sum['citas'] += $subtotales_plantel_sum['citas'];
$totales_globales_sum['asesorias'] += $subtotales_plantel_sum['asesorias'];
$totales_globales_sum['registros'] += $subtotales_plantel_sum['registros'];

// Acumular metas globales
$totales_metas_globales['contactos'] += $subtotales_metas_plantel['contactos'];
$totales_metas_globales['citas'] += $subtotales_metas_plantel['citas'];
$totales_metas_globales['asesorias'] += $subtotales_metas_plantel['asesorias'];
$totales_metas_globales['registros'] += $subtotales_metas_plantel['registros'];
endwhile; // fin planteles
?>
			<tr class="bg-total">
				<td>TOTAL CONTACTOS</td>
				<td class="text-center"><?php echo $totales_metas_globales['contactos']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $totales_globales[$dia['fecha']]['contactos']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $totales_globales_sum['contactos']; ?></td>
				<td class="text-center"><?php echo ($totales_metas_globales['contactos'] > 0) ? round(($totales_globales_sum['contactos'] / $totales_metas_globales['contactos']) * 100, 0) : 0; ?>%</td>
			</tr>
			<tr class="bg-total">
				<td>TOTAL CITAS</td>
				<td class="text-center"><?php echo $totales_metas_globales['citas']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $totales_globales[$dia['fecha']]['citas']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $totales_globales_sum['citas']; ?></td>
				<td class="text-center"><?php echo ($totales_metas_globales['citas'] > 0) ? round(($totales_globales_sum['citas'] / $totales_metas_globales['citas']) * 100, 0) : 0; ?>%</td>
			</tr>
			<tr class="bg-total">
				<td>TOTAL ASESORIAS</td>
				<td class="text-center"><?php echo $totales_metas_globales['asesorias']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $totales_globales[$dia['fecha']]['asesorias']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $totales_globales_sum['asesorias']; ?></td>
				<td class="text-center"><?php echo ($totales_metas_globales['asesorias'] > 0) ? round(($totales_globales_sum['asesorias'] / $totales_metas_globales['asesorias']) * 100, 0) : 0; ?>%</td>
			</tr>
			<tr class="bg-total">
				<td>TOTAL REGISTROS</td>
				<td class="text-center"><?php echo $totales_metas_globales['registros']; ?></td>
				<?php foreach($dias as $dia): ?>
				<td class="text-center"><?php echo $totales_globales[$dia['fecha']]['registros']; ?></td>
				<?php endforeach; ?>
				<td class="text-center"><?php echo $totales_globales_sum['registros']; ?></td>
				<td class="text-center"><?php echo ($totales_metas_globales['registros'] > 0) ? round(($totales_globales_sum['registros'] / $totales_metas_globales['registros']) * 100, 0) : 0; ?>%</td>
			</tr>
		</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
	$('#tabla_embudo').DataTable({
		paging: false,
		searching: true,
		ordering: false,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'excelHtml5',
			title: 'REPORTE_EMBUDO_<?php echo date("Y-m-d"); ?>',
			className: 'btn-sm btn-success',
			text: 'Exportar Excel'
		}],
		language: { search: 'Buscar:' },
		info: false
	});
});
</script>

<?php else: ?>
<!-- ============================================== -->
<!-- VISTA REPORTES (ORIGINAL) -->
<!-- ============================================== -->

<h3>REPORTE CITAS</h3>
<span class="letraPequena grey-text"><?php echo obtenerTituloReporte($inicio, $fin); ?></span>
<hr>

<!-- TABLA PLANTELES -->
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
		<h3>REPORTE CITAS POR CDE</h3>
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_citas">
                <thead>
                    <tr>
                        <th style="background-color: black; color: white; text-align: center;">CDE</th>
                        <th style="background-color: #3E98D7; color: white; text-align: center;">CITAS</th>
                        <th style="background-color: #3E98D7; color: white; text-align: center;">CITAS EFECTIVAS</th>
                        <th style="background-color: #3E98D7; color: white; text-align: center;">EFECTIVIDAD DE CITAS</th>
                        <th style="background-color: #3E98D7; color: white; text-align: center;">REGISTROS</th>
                        <th style="background-color: #3E98D7; color: white; text-align: center;">EFECTIVIDAD DE CIERRE</th>
                    </tr>
                </thead>
                <tbody style="color: black;">
<?php
$sql_planteles = "
	SELECT p.*, 
	obtener_citas_plantel(p.id_pla, '$inicio', '$fin') AS citas,
	obtener_citas_efectivas_plantel(p.id_pla, '$inicio', '$fin') AS citas_efectivas,
	obtener_registros_plantel(p.id_pla, '$inicio', '$fin') AS registros
	FROM plantel p
	INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
	WHERE pe.id_eje = $id_eje
	ORDER BY citas DESC
";
$resultado = mysqli_query($db, $sql_planteles);

$total_citas = 0;
$total_efectivas = 0;
$total_registros = 0;

while($fila = mysqli_fetch_assoc($resultado)) {
	$citas = $fila['citas'];
	$efectivas = $fila['citas_efectivas'];
	$registros = $fila['registros'];
	
	$efectividad_citas = ($citas > 0) ? round(($efectivas / $citas) * 100, 2) : 0;
	$efectividad_cierre = ($efectivas > 0) ? round(($registros / $efectivas) * 100, 2) : 0;
	
	$style_citas = ($efectividad_citas > 60) 
		? "background-color: #83AA03;" 
		: "background-color: #dc3545; color: white;";

	$style_cierre = ($efectividad_cierre > 50) 
		? "background-color: #83AA03;" 
		: "background-color: #dc3545; color: white;";
	
	$total_citas += $citas;
	$total_efectivas += $efectivas;
	$total_registros += $registros;

	$liga_citas = obtenerLigaCitas($fila['id_pla'], $inicio, $fin);
	$liga_registros = obtenerLigaRegistros($fila['id_pla'], $inicio, $fin);
?>
					<tr>
						<td><?php echo $fila['nom_pla']; ?></td>
						<td class="text-center"><a href="<?php echo $liga_citas; ?>" class="custom-link" target="_blank"><?php echo $citas; ?></a></td>
						<td class="text-center"><?php echo $efectivas; ?></td>
						<td class="text-center" style="<?php echo $style_citas; ?>"><?php echo $efectividad_citas; ?>%</td>
						<td class="text-center"><a href="<?php echo $liga_registros; ?>" class="custom-link" target="_blank"><?php echo $registros; ?></a></td>
						<td class="text-center" style="<?php echo $style_cierre; ?>"><?php echo $efectividad_cierre; ?>%</td>
					</tr>
<?php } ?>
<?php
$total_porc_citas = ($total_citas > 0) ? round(($total_efectivas / $total_citas) * 100, 2) : 0;
$total_porc_cierre = ($total_efectivas > 0) ? round(($total_registros / $total_efectivas) * 100, 2) : 0;

$style_total_citas = ($total_porc_citas > 70) 
	? "background-color: #83AA03;" 
	: "background-color: #dc3545; color: white;";

$style_total_cierre = ($total_porc_cierre > 50) 
	? "background-color: #83AA03;" 
	: "background-color: #dc3545; color: white;";
?>
					<tr style="font-weight: bold; background-color: #f8f9fa;">
						<td>TOTAL</td>
						<td class="text-center"><?php echo $total_citas; ?></td>
						<td class="text-center"><?php echo $total_efectivas; ?></td>
						<td class="text-center" style="<?php echo $style_total_citas; ?>"><?php echo $total_porc_citas; ?>%</td>
						<td class="text-center"><?php echo $total_registros; ?></td>
						<td class="text-center" style="<?php echo $style_total_cierre; ?>"><?php echo $total_porc_cierre; ?>%</td>
					</tr>
				</tbody>
            </table>
		</div>
		
		<h3>REPORTE CITAS POR EJECUTIVO</h3>
        <div class="table-responsive">
			<table class="table table-bordered" id="tabla_ejecutivos">
				<thead>
					<tr>
						<th style="background-color: black; color: white; text-align: center;">EJECUTIVO</th>
						<th style="background-color: black; color: white; text-align: center;">CDE</th>
						<th style="background-color: #3E98D7; color: white; text-align: center;">CITAS</th>
						<th style="background-color: #3E98D7; color: white; text-align: center;">CITAS EFECTIVAS</th>
						<th style="background-color: #3E98D7; color: white; text-align: center;">EFECTIVIDAD DE CITAS</th>
					</tr>
				</thead>
				<tbody style="color: black;">
<?php
$sql_ejecutivos = "
	SELECT e.id_eje, e.nom_eje,
	obtener_citas_agendadas_ejecutivo(e.id_eje, '$inicio', '$fin') AS citas,
	obtener_citas_agendadas_efectivas_ejecutivo(e.id_eje, '$inicio', '$fin') AS citas_efectivas
	FROM ejecutivo e
	INNER JOIN plantel p ON e.id_pla = p.id_pla
	INNER JOIN planteles_ejecutivo pe ON pe.id_pla = p.id_pla
	WHERE e.ran_eje = 'GR'
	AND e.est_eje = 'Activo' 
	AND e.eli_eje = 'Activo'
	AND pe.id_eje = $id
	ORDER BY citas DESC
";

$resultado = mysqli_query($db, $sql_ejecutivos);

$total_citas_eje = 0;
$total_efectivas_eje = 0;

while($fila = mysqli_fetch_assoc($resultado)) {
	$id_ejecutivo = $fila['id_eje'];
	$citas = $fila['citas'];
	$efectivas = $fila['citas_efectivas'];
	
	$sql_planteles_eje = "
		SELECT nom_pla, count(id_cit) as total_citas
		FROM cita
		INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
		INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
		WHERE id_eje_agendo = $id_ejecutivo 
		AND cla_cit = 'Cita'
		AND DATE(cit_cit) BETWEEN '$inicio' AND '$fin'
		AND hor_cit >= '09:00' AND hor_cit < '20:00'
		GROUP BY nom_pla
		ORDER BY total_citas DESC
	";
	
	$resultado_planteles_eje = mysqli_query($db, $sql_planteles_eje);
	
	$planteles_array = array();
	
	if($resultado_planteles_eje && mysqli_num_rows($resultado_planteles_eje) > 0) {
		while($plantel_eje = mysqli_fetch_assoc($resultado_planteles_eje)) {
			$planteles_array[] = $plantel_eje['nom_pla'] . " (" . $plantel_eje['total_citas'] . ")";
		}
	} else {
		$sql_plantel_default = "
			SELECT p.nom_pla 
			FROM ejecutivo e 
			JOIN plantel p ON e.id_pla = p.id_pla 
			WHERE e.id_eje = $id_ejecutivo
		";
		$resultado_plantel_default = mysqli_query($db, $sql_plantel_default);
		
		if($resultado_plantel_default && mysqli_num_rows($resultado_plantel_default) > 0) {
			$plantel_info = mysqli_fetch_assoc($resultado_plantel_default);
			$planteles_array[] = $plantel_info['nom_pla'] . " (0)";
		} else {
			$planteles_array[] = "Sin asignacion (0)";
		}
	}
	
	$texto_planteles = implode(", ", $planteles_array);
	
	$efectividad_citas = ($citas > 0) ? round(($efectivas / $citas) * 100, 2) : 0;
	
	$style_citas = ($efectividad_citas > 60) 
		? "background-color: #83AA03;" 
		: "background-color: #dc3545; color: white;";
	
	$total_citas_eje += $citas;
	$total_efectivas_eje += $efectivas;
?>
					<tr>
						<td><?php echo $fila['nom_eje']; ?></td>
						<td><?php echo $texto_planteles; ?></td>
						<td class="text-center"><?php echo $citas; ?></td>
						<td class="text-center"><?php echo $efectivas; ?></td>
						<td class="text-center" style="<?php echo $style_citas; ?>"><?php echo $efectividad_citas; ?>%</td>
					</tr>
<?php } ?>
<?php
$total_porc_citas_eje = ($total_citas_eje > 0) ? round(($total_efectivas_eje / $total_citas_eje) * 100, 2) : 0;

$style_total_citas_eje = ($total_porc_citas_eje > 70) 
	? "background-color: #83AA03;" 
	: "background-color: #dc3545; color: white;";
?>
					<tr style="font-weight: bold; background-color: #f8f9fa;">
						<td>TOTAL</td>
						<td>---</td>
						<td class="text-center"><?php echo $total_citas_eje; ?></td>
						<td class="text-center"><?php echo $total_efectivas_eje; ?></td>
						<td class="text-center" style="<?php echo $style_total_citas_eje; ?>"><?php echo $total_porc_citas_eje; ?>%</td>
					</tr>
				</tbody>
			</table>
		</div>
    </div>
    <div class="col-md-2"></div>
</div>

<hr>

<div class="row">
	<div class="col-md-12">
		<h3>REPORTE EFECTIVIDAD POR CONSULTOR</h3>

		<div class="table-responsive">
			<table class="table table-bordered tabla-texto-small" id="tabla_semanal">
				<thead>
					<tr>
						<th style="background-color: black; color: white;" class="text-center-header">CONSULTOR</th>
						<?php
						$fecha_actual = new DateTime($inicio);
						$fecha_fin_dt = new DateTime($fin);
						
						$nombres_dias_largo = array('DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO');
						
						while($fecha_actual <= $fecha_fin_dt) {
							echo "<th style='background-color: #3E98D7; color: white;' class='text-center-header'>CITAS</th>";
							echo "<th style='background-color: #3E98D7; color: white;' class='text-center-header'>CITAS EFE</th>";
							echo "<th style='background-color: #3E98D7; color: white;' class='text-center-header'>REG</th>";
							echo "<th style='background-color: #3E98D7; color: white;' class='text-center-header'>% CIERRE</th>";
							
							$fecha_actual->modify('+1 day');
						}
						?>
						<th style="background-color: black; color: white;" class="text-center-header">TOTAL CITAS</th>
						<th style="background-color: black; color: white;" class="text-center-header">TOTAL CITAS EFE</th>
						<th style="background-color: black; color: white;" class="text-center-header">TOTAL REG</th>
						<th style="background-color: black; color: white;" class="text-center-header">% TOTAL CIERRE</th>
					</tr>
				</thead>
				<tbody style="color: black;">
<?php
$sql_planteles_sem = "
	SELECT DISTINCT p.id_pla, p.nom_pla
	FROM plantel p
	INNER JOIN planteles_ejecutivo pe ON pe.id_pla = p.id_pla
	WHERE pe.id_eje = $id
	ORDER BY p.id_pla ASC
";

$resultado_planteles_sem = mysqli_query($db, $sql_planteles_sem);

$totales_globales_sem = array();
$fecha_actual = new DateTime($inicio);
$fecha_fin_dt = new DateTime($fin);
while($fecha_actual <= $fecha_fin_dt) {
	$fecha = $fecha_actual->format('Y-m-d');
	$totales_globales_sem[$fecha] = array(
		'citas' => 0,
		'citas_efe' => 0,
		'reg' => 0
	);
	$fecha_actual->modify('+1 day');
}
$total_global_citas_sem = 0;
$total_global_citas_efe_sem = 0;
$total_global_reg_sem = 0;

while($plantel_sem = mysqli_fetch_assoc($resultado_planteles_sem)) {
	echo "<tr style='background-color: #CCCCCC;'>";
	echo "<td>{$plantel_sem['nom_pla']}</td>";
	
	$fecha_actual = new DateTime($inicio);
	$fecha_fin_dt = new DateTime($fin);
	while($fecha_actual <= $fecha_fin_dt) {
		$fecha = $fecha_actual->format('Y-m-d');
		$dia_semana = $fecha_actual->format('w');
		$nombre_dia = $nombres_dias_largo[$dia_semana];
		
		echo "<td class='text-center'><strong>$nombre_dia</strong></td>";
		echo "<td class='text-center'><strong>" . fechaFormateadaCompacta2($fecha) . "</strong></td>";
		echo "<td></td>";
		echo "<td></td>";
		
		$fecha_actual->modify('+1 day');
	}
	echo "<td></td><td></td><td></td><td></td>";
	echo "</tr>";
	
	$sql_ejecutivos_sem = "
		SELECT e.id_eje, e.nom_eje
		FROM ejecutivo e
		WHERE e.est_eje = 'Activo' 
		AND e.tip_eje = 'Ejecutivo'
		AND e.eli_eje = 'Activo'
		AND (e.usu_eje IS NULL)
		AND e.id_pla = {$plantel_sem['id_pla']}
		ORDER BY e.nom_eje ASC
	";
	
	$resultado_ejecutivos_sem = mysqli_query($db, $sql_ejecutivos_sem);
	
	$subtotales_plantel_sem = array();
	$fecha_actual = new DateTime($inicio);
	$fecha_fin_dt = new DateTime($fin);
	while($fecha_actual <= $fecha_fin_dt) {
		$fecha = $fecha_actual->format('Y-m-d');
		$subtotales_plantel_sem[$fecha] = array(
			'citas' => 0,
			'citas_efe' => 0,
			'reg' => 0
		);
		$fecha_actual->modify('+1 day');
	}
	
	while($ejecutivo_sem = mysqli_fetch_assoc($resultado_ejecutivos_sem)) {
		echo "<tr>";
		echo "<td>{$ejecutivo_sem['nom_eje']}</td>";
		
		$total_eje_citas_sem = 0;
		$total_eje_citas_efe_sem = 0;
		$total_eje_reg_sem = 0;
		
		$fecha_actual = new DateTime($inicio);
		$fecha_fin_dt = new DateTime($fin);
		while($fecha_actual <= $fecha_fin_dt) {
			$fecha = $fecha_actual->format('Y-m-d');
			
			$sql_citas_sem = "SELECT obtener_citas_ejecutivo({$ejecutivo_sem['id_eje']}, '$fecha', '$fecha') AS total";
			$sql_citas_efe_sem = "SELECT obtener_citas_efectivas_ejecutivo({$ejecutivo_sem['id_eje']}, '$fecha', '$fecha') AS total";
			$sql_reg_sem = "SELECT obtener_registros_ejecutivo({$ejecutivo_sem['id_eje']}, '$fecha', '$fecha') AS total";
			
			$datos_citas_sem = obtener_datos_consulta($db, $sql_citas_sem);
			$datos_citas_efe_sem = obtener_datos_consulta($db, $sql_citas_efe_sem);
			$datos_reg_sem = obtener_datos_consulta($db, $sql_reg_sem);
			
			$citas_sem = $datos_citas_sem['datos']['total'];
			$citas_efe_sem = $datos_citas_efe_sem['datos']['total'];
			$reg_sem = $datos_reg_sem['datos']['total'];
			
			$porc_cierre_sem = ($citas_efe_sem > 0) ? round(($reg_sem / $citas_efe_sem) * 100, 2) : 0;
			$style_cierre_sem = ($porc_cierre_sem >= 50) ? "background-color: #83AA03; color: white;" : "background-color: #dc3545; color: white;";
			
			$total_eje_citas_sem += $citas_sem;
			$total_eje_citas_efe_sem += $citas_efe_sem;
			$total_eje_reg_sem += $reg_sem;
			
			$subtotales_plantel_sem[$fecha]['citas'] += $citas_sem;
			$subtotales_plantel_sem[$fecha]['citas_efe'] += $citas_efe_sem;
			$subtotales_plantel_sem[$fecha]['reg'] += $reg_sem;
			$totales_globales_sem[$fecha]['citas'] += $citas_sem;
			$totales_globales_sem[$fecha]['citas_efe'] += $citas_efe_sem;
			$totales_globales_sem[$fecha]['reg'] += $reg_sem;
			
			echo "<td class='text-center'>$citas_sem</td>";
			echo "<td class='text-center'>$citas_efe_sem</td>";
			echo "<td class='text-center'>$reg_sem</td>";
			echo "<td class='text-center' style='$style_cierre_sem'>{$porc_cierre_sem}%</td>";
			
			$fecha_actual->modify('+1 day');
		}
		
		$porc_total_eje_sem = ($total_eje_citas_efe_sem > 0) ? round(($total_eje_reg_sem / $total_eje_citas_efe_sem) * 100, 2) : 0;
		$style_total_eje_sem = ($porc_total_eje_sem >= 50) ? "background-color: #83AA03; color: white;" : "background-color: #dc3545; color: white;";
		
		echo "<td class='text-center'>$total_eje_citas_sem</td>";
		echo "<td class='text-center'>$total_eje_citas_efe_sem</td>";
		echo "<td class='text-center'>$total_eje_reg_sem</td>";
		echo "<td class='text-center' style='$style_total_eje_sem'>{$porc_total_eje_sem}%</td>";
		echo "</tr>";
	}
	
	echo "<tr style='background-color: #CCCCCC;'>";
	echo "<td><strong>SUBTOTAL</strong></td>";
	
	$total_plantel_citas_sem = 0;
	$total_plantel_citas_efe_sem = 0;
	$total_plantel_reg_sem = 0;
	
	foreach($subtotales_plantel_sem as $subtotales_sem) {
		$porc_subtotal_sem = ($subtotales_sem['citas_efe'] > 0) ? 
			round(($subtotales_sem['reg'] / $subtotales_sem['citas_efe']) * 100, 2) : 0;
		$style_subtotal_sem = ($porc_subtotal_sem >= 50) ? 
			"background-color: #83AA03; color: white;" : 
			"background-color: #dc3545; color: white;";
		
		echo "<td class='text-center'><strong>{$subtotales_sem['citas']}</strong></td>";
		echo "<td class='text-center'><strong>{$subtotales_sem['citas_efe']}</strong></td>";
		echo "<td class='text-center'><strong>{$subtotales_sem['reg']}</strong></td>";
		echo "<td class='text-center' style='$style_subtotal_sem'><strong>{$porc_subtotal_sem}%</strong></td>";
		
		$total_plantel_citas_sem += $subtotales_sem['citas'];
		$total_plantel_citas_efe_sem += $subtotales_sem['citas_efe'];
		$total_plantel_reg_sem += $subtotales_sem['reg'];
	}
	
	$porc_total_plantel_sem = ($total_plantel_citas_efe_sem > 0) ? 
		round(($total_plantel_reg_sem / $total_plantel_citas_efe_sem) * 100, 2) : 0;
	$style_total_plantel_sem = ($porc_total_plantel_sem >= 50) ? 
		"background-color: #83AA03; color: white;" : 
		"background-color: #dc3545; color: white;";
	
	echo "<td class='text-center'><strong>$total_plantel_citas_sem</strong></td>";
	echo "<td class='text-center'><strong>$total_plantel_citas_efe_sem</strong></td>";
	echo "<td class='text-center'><strong>$total_plantel_reg_sem</strong></td>";
	echo "<td class='text-center' style='$style_total_plantel_sem'><strong>{$porc_total_plantel_sem}%</strong></td>";
	echo "</tr>";
}

echo "<tr style='background-color: #CCCCCC;'>";
echo "<td><strong>TOTALES GENERALES</strong></td>";

foreach($totales_globales_sem as $totales_sem) {
	$porc_total_sem = ($totales_sem['citas_efe'] > 0) ? 
		round(($totales_sem['reg'] / $totales_sem['citas_efe']) * 100, 2) : 0;
	$style_total_sem = ($porc_total_sem >= 50) ? 
		"background-color: #83AA03; color: white;" : 
		"background-color: #dc3545; color: white;";
	
	echo "<td class='text-center'><strong>{$totales_sem['citas']}</strong></td>";
	echo "<td class='text-center'><strong>{$totales_sem['citas_efe']}</strong></td>";
	echo "<td class='text-center'><strong>{$totales_sem['reg']}</strong></td>";
	echo "<td class='text-center' style='$style_total_sem'><strong>{$porc_total_sem}%</strong></td>";
	
	$total_global_citas_sem += $totales_sem['citas'];
	$total_global_citas_efe_sem += $totales_sem['citas_efe'];
	$total_global_reg_sem += $totales_sem['reg'];
}

$porc_total_global_sem = ($total_global_citas_efe_sem > 0) ? 
	round(($total_global_reg_sem / $total_global_citas_efe_sem) * 100, 2) : 0;
$style_total_global_sem = ($porc_total_global_sem >= 50) ? 
	"background-color: #83AA03; color: white;" : 
	"background-color: #dc3545; color: white;";

echo "<td class='text-center'><strong>$total_global_citas_sem</strong></td>";
echo "<td class='text-center'><strong>$total_global_citas_efe_sem</strong></td>";
echo "<td class='text-center'><strong>$total_global_reg_sem</strong></td>";
echo "<td class='text-center' style='$style_total_global_sem'><strong>{$porc_total_global_sem}%</strong></td>";
echo "</tr>";
?>
				</tbody>
			</table>
		</div>

		<script>
		$(document).ready(function() {
			$('#tabla_semanal').DataTable({
				paging: false,
				searching: false,
				ordering: false,
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						title: 'REPORTE SEMANAL',
						className: 'btn-sm btn-success'
					}
				],
				language: {
					search: 'Buscar'
				},
				info: false
			});
		});
		</script>
	</div>
</div>

<br><br><br><br><br><br><br><br><br>

<script>
$(document).ready(function() {
    $('#tabla_citas').DataTable({
        paging: false,
        searching: false,
        ordering: false,
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE CITAS',
                className: 'btn-sm btn-success'
            }
        ],
        language: {
            search: 'Buscar'
        },
        info: false
    });

	$('#tabla_ejecutivos').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE CITAS EJECUTIVOS',
                className: 'btn-sm btn-success'
            }
        ],
        language: {
            search: 'Buscar'
        },
        info: false
    });
});
</script>

<?php endif; ?>