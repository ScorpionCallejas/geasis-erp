<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE HISTORIAL DE ACTIVIDADES
	//historial_actividades.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$fechaHoy = date( 'Y-m-d' );
	$id_alu_ram = $_POST['id_alu_ram'];

	$sqlActividades = "
	    SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp, sub_hor.id_sub_hor AS id_sub_hor
	    FROM alu_ram
	    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
	    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
	    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
	    INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
	    INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
	    INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
	    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_cal_act <= '$fechaHoy' 
	    GROUP BY id
	    UNION
	    SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp, sub_hor.id_sub_hor AS id_sub_hor
	    FROM alu_ram
	    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
	    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
	    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
	    INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
	    INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
	    INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
	    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_cal_act <= '$fechaHoy' 
	    GROUP BY id
	    UNION
	    SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp, sub_hor.id_sub_hor AS id_sub_hor
	    FROM alu_ram
	    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
	    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
	    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
	    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
	    INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
	    INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
	    INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
	    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_cal_act <= '$fechaHoy'
	    GROUP BY id 
	    ORDER BY inicio DESC
	";

	$resultadoActividades = mysqli_query($db, $sqlActividades);
?>

<style>
:root {
	--gray-50: #FAFAFA;
	--gray-100: #F5F5F5;
	--gray-200: #E5E5E5;
	--gray-400: #9CA3AF;
	--gray-600: #666;
	--gray-900: #111827;
	--blue-50: #E3F2FD;
	--blue-600: #1976D2;
	--green-50: #E8F5E9;
	--green-600: #34C759;
	--red-50: #FFE0E0;
	--red-600: #D32F2F;
	--orange-50: #FFF3E0;
	--orange-600: #F57C00;
	--shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
	--shadow-md: 0 2px 4px rgba(0,0,0,0.05);
}

.summary-container {
	display: flex;
	gap: 8px;
	margin-bottom: 12px;
}

.summary-card {
	flex: 1;
	background: white;
	border-radius: 6px;
	padding: 8px 10px;
	box-shadow: var(--shadow-sm);
	text-align: center;
}

.summary-label {
	font-size: 9px;
	font-weight: 600;
	color: var(--gray-600);
	text-transform: uppercase;
	letter-spacing: 0.3px;
	margin-bottom: 3px;
}

.summary-value {
	font-size: 18px;
	font-weight: 700;
	color: var(--gray-900);
}

.summary-value.success {
	color: var(--green-600);
}

.filters-container {
	display: flex;
	gap: 6px;
	margin-bottom: 12px;
	flex-wrap: wrap;
}

.filter-badge {
	padding: 5px 10px;
	border-radius: 6px;
	font-size: 10px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.2s;
	border: 2px solid transparent;
	background: white;
	box-shadow: var(--shadow-sm);
	user-select: none;
}

.filter-badge:hover {
	transform: translateY(-1px);
	box-shadow: var(--shadow-md);
}

.filter-badge.pendiente {
	color: var(--gray-600);
	background: var(--gray-100);
}

.filter-badge.pendiente.active {
	background: var(--gray-600);
	color: white;
}

.filter-badge.vencida {
	color: var(--red-600);
	background: var(--red-50);
}

.filter-badge.vencida.active {
	background: var(--red-600);
	color: white;
}

.filter-badge.realizada {
	color: var(--blue-600);
	background: var(--blue-50);
}

.filter-badge.realizada.active {
	background: var(--blue-600);
	color: white;
}

.filter-badge.calificada {
	color: var(--green-600);
	background: var(--green-50);
}

.filter-badge.calificada.active {
	background: var(--green-600);
	color: white;
}

.table-container {
	background: white;
	border-radius: 6px;
	overflow: hidden;
	box-shadow: var(--shadow-sm);
}

.data-table {
	width: 100%;
	border-collapse: collapse;
	font-size: 11px;
}

.data-table thead {
	background: var(--gray-900);
	color: white;
}

.data-table thead th {
	padding: 6px 8px;
	text-align: left;
	font-size: 9px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.3px;
}

.data-table tbody tr {
	border-bottom: 1px solid var(--gray-100);
}

.data-table tbody tr:nth-child(even) {
	background: var(--gray-50);
}

.data-table tbody tr:hover {
	background: var(--blue-50);
}

.data-table tbody td {
	padding: 6px 8px;
	vertical-align: middle;
}

.estatus-badge, .tipo-badge {
	display: inline-block;
	padding: 2px 6px;
	border-radius: 4px;
	font-size: 9px;
	font-weight: 700;
	white-space: nowrap;
}

.estatus-badge.pendiente { background: var(--gray-100); color: var(--gray-600); }
.estatus-badge.vencida { background: var(--red-50); color: var(--red-600); }
.estatus-badge.realizada { background: var(--blue-50); color: var(--blue-600); }
.estatus-badge.calificada { background: var(--green-50); color: var(--green-600); }

.tipo-badge.tarea { background: var(--blue-50); color: var(--blue-600); }
.tipo-badge.cuestionario { background: var(--orange-50); color: var(--orange-600); }
.tipo-badge.foro { background: #F3E5F5; color: #7B1FA2; }

.actividad-link, .bloque-link {
	color: var(--blue-600);
	text-decoration: underline;
	font-weight: 600;
	transition: color 0.2s;
	font-size: 11px;
}

.actividad-link:hover, .bloque-link:hover {
	color: #0d47a1;
}

.truncate-feedback {
	max-width: 200px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	display: inline-block;
	cursor: pointer;
	color: var(--blue-600);
	text-decoration: underline;
}

.modal-retro {
	display: none;
	position: fixed;
	z-index: 9999;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0,0,0,0.5);
}

.modal-retro-content {
	background-color: white;
	margin: 10% auto;
	padding: 20px;
	border-radius: 8px;
	width: 90%;
	max-width: 600px;
	box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.modal-retro-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 15px;
	padding-bottom: 10px;
	border-bottom: 2px solid var(--gray-200);
}

.modal-retro-title {
	font-size: 16px;
	font-weight: 700;
	color: var(--gray-900);
}

.modal-retro-close {
	font-size: 28px;
	font-weight: bold;
	color: var(--gray-600);
	cursor: pointer;
}

.modal-retro-close:hover {
	color: var(--red-600);
}

.modal-retro-body {
	font-size: 13px;
	color: var(--gray-900);
	line-height: 1.6;
	max-height: 400px;
	overflow-y: auto;
}

.text-center { text-align: center; }
.text-muted { color: var(--gray-400); font-size: 10px; }
.index-number { color: var(--gray-600); font-weight: 600; font-size: 10px; }

.dt-buttons { margin-bottom: 8px; }
.dt-button {
	background: var(--green-600) !important;
	color: white !important;
	border: none !important;
	padding: 6px 12px !important;
	border-radius: 4px !important;
	font-size: 11px !important;
	font-weight: 600 !important;
	cursor: pointer !important;
}

.dt-button:hover {
	background: #2da84a !important;
}

@media (max-width: 768px) {
	.summary-container { flex-direction: column; }
	.table-container { overflow-x: auto; }
}
</style>

<!-- Summary Cards -->
<div class="summary-container">
	<div class="summary-card">
		<div class="summary-label">Total</div>
		<div class="summary-value" id="totalRegistros">0</div>
	</div>
	<div class="summary-card">
		<div class="summary-label">Puntos</div>
		<div class="summary-value" id="totalPuntos">0</div>
	</div>
	<div class="summary-card">
		<div class="summary-label">Obtenidos</div>
		<div class="summary-value" id="totalObtenidos">-</div>
	</div>
	<div class="summary-card">
		<div class="summary-label">Aprovechamiento</div>
		<div class="summary-value success" id="porcentaje">-</div>
	</div>
</div>

<!-- Filtros -->
<div class="filters-container" id="filtrosEstatus">
	<div class="filter-badge pendiente" data-estatus="Pendiente">Pendientes (<span class="cnt-pendiente">0</span>)</div>
	<div class="filter-badge vencida" data-estatus="Vencida">Vencidas (<span class="cnt-vencida">0</span>)</div>
	<div class="filter-badge realizada" data-estatus="Realizada">Realizadas (<span class="cnt-realizada">0</span>)</div>
	<div class="filter-badge calificada" data-estatus="Calificada">Calificadas (<span class="cnt-calificada">0</span>)</div>
</div>

<!-- Tabla -->
<div class="table-container">
	<table id="tablaActividades" class="data-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Estatus</th>
				<th>Tipo</th>
				<th>Actividad</th>
				<th>Materia</th>
				<th>Bloque</th>
				<th>Inicio</th>
				<th>Fin</th>
				<th>Puntos</th>
				<th>Obtenidos</th>
				<th>Retroalimentación</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$i = 1;
				while($fila = mysqli_fetch_assoc($resultadoActividades)){
					// Estatus
					if ($fila['fecha'] == NULL && $fila['calificacion'] == NULL) {
						$estatus = ($fechaHoy > $fila['fin']) ? 'Vencida' : 'Pendiente';
					} else {
						$estatus = ($fila['calificacion'] != NULL) ? 'Calificada' : 'Realizada';
					}
					$estatusClass = strtolower($estatus);
					
					// Tipo
					$tipo = $fila['tipo'];
					if ($tipo == 'Examen') { $tipoDisplay = 'Cuestionario'; $tipoClass = 'cuestionario'; }
					else if ($tipo == 'Entregable') { $tipoDisplay = 'Tarea'; $tipoClass = 'tarea'; }
					else { $tipoDisplay = 'Foro'; $tipoClass = 'foro'; }
					
					$urlBloque = "https://plataforma.ahjende.com/alumno/clase_contenido.php?id_sub_hor={$fila['id_sub_hor']}&id_blo={$fila['id_blo']}&id_alu_ram={$id_alu_ram}";
			?>
				<tr data-estatus="<?php echo $estatus; ?>" data-puntos="<?php echo $fila['puntaje']; ?>" data-obtenidos="<?php echo ($fila['calificacion'] !== NULL) ? $fila['calificacion'] : ''; ?>">
					<td class="text-center"><span class="index-number"><?php echo $i++; ?></span></td>
					<td class="text-center"><span class="estatus-badge <?php echo $estatusClass; ?>"><?php echo $estatus; ?></span></td>
					<td class="text-center"><span class="tipo-badge <?php echo $tipoClass; ?>"><?php echo $tipoDisplay; ?></span></td>
					<td>
						<?php if ($tipo == 'Foro') { ?>
							<a class="actividad-link revisarActividadForo" href="#" 
							   data-id="<?php echo $fila['id']; ?>" 
							   data-nombre="<?php echo htmlspecialchars($fila['actividad']); ?>"
							   data-estatus-server="<?php echo obtenerEstatusActividadServer($fila['fecha'], $fila['inicio'], $fila['fin'], $fila['calificacion']); ?>">
								<?php echo $fila['actividad']; ?>
							</a>
						<?php } else if ($tipo == 'Entregable') { ?>
							<a class="actividad-link revisarActividadEntregable" href="#"
							   data-id="<?php echo $fila['id']; ?>"
							   data-nombre="<?php echo htmlspecialchars($fila['actividad']); ?>"
							   data-estatus-server="<?php echo obtenerEstatusActividadServer($fila['fecha'], $fila['inicio'], $fila['fin'], $fila['calificacion']); ?>">
								<?php echo $fila['actividad']; ?>
							</a>
						<?php } else { ?>
							<a class="actividad-link revisarActividadExamen" href="#"
							   data-id="<?php echo $fila['id']; ?>"
							   data-nombre="<?php echo htmlspecialchars($fila['actividad']); ?>"
							   data-estatus-server="<?php echo obtenerEstatusActividadServer($fila['fecha'], $fila['inicio'], $fila['fin'], $fila['calificacion']); ?>">
								<?php echo $fila['actividad']; ?>
							</a>
						<?php } ?>
					</td>
					<td><?php echo $fila['materia']; ?></td>
					<td><a href="<?php echo $urlBloque; ?>" class="bloque-link" target="_blank"><?php echo $fila['bloque']; ?></a></td>
					<td class="text-center"><?php echo fechaFormateadaCompacta($fila['inicio']); ?></td>
					<td class="text-center"><?php echo fechaFormateadaCompacta($fila['fin']); ?></td>
					<td class="text-center"><?php echo $fila['puntaje']; ?></td>
					<td class="text-center"><?php echo ($fila['calificacion'] == NULL) ? '<span class="text-muted">-</span>' : $fila['calificacion']; ?></td>
					<td>
						<?php if ($fila['retroalimentacion'] == NULL || trim($fila['retroalimentacion']) == '') { ?>
							<span class="text-muted">-</span>
						<?php } else { 
							$retroCorta = (strlen($fila['retroalimentacion']) > 50) ? substr($fila['retroalimentacion'], 0, 50) . '...' : $fila['retroalimentacion'];
						?>
							<span class="truncate-feedback ver-retro" data-retro="<?php echo htmlspecialchars($fila['retroalimentacion']); ?>"><?php echo htmlspecialchars($retroCorta); ?></span>
						<?php } ?>
					</td>
					<td class="text-center"><?php echo ($fila['fecha'] == NULL) ? '<span class="text-muted">-</span>' : fechaFormateadaCompacta($fila['fecha']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<!-- Modal -->
<div id="modalRetro" class="modal-retro">
	<div class="modal-retro-content">
		<div class="modal-retro-header">
			<span class="modal-retro-title">Retroalimentación</span>
			<span class="modal-retro-close">&times;</span>
		</div>
		<div class="modal-retro-body" id="modalRetroBody"></div>
	</div>
</div>

<script>
(function() {
	var filtrosActivos = [];
	var table;
	var idAluRam = '<?php echo (int)$id_alu_ram; ?>';

	// Filtro personalizado de DataTable - usa data-estatus del TR
	$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
		if (settings.nTable.id !== 'tablaActividades') return true;
		if (filtrosActivos.length === 0) return true;
		
		var row = table.row(dataIndex).node();
		var estatusFila = $(row).attr('data-estatus');
		return filtrosActivos.indexOf(estatusFila) !== -1;
	});

	$(document).ready(function() {
		// Inicializar DataTable
		table = $('#tablaActividades').DataTable({
			dom: 'Bfrtip',
			pageLength: -1,
			paging: false,
			info: false,
			buttons: [{
				extend: 'excelHtml5',
				text: 'Excel',
				className: 'dt-button',
				exportOptions: { columns: ':visible' }
			}],
			language: {
				search: "",
				searchPlaceholder: "Buscar...",
				zeroRecords: "No se encontraron resultados",
				emptyTable: "Sin actividades"
			}
		});

		// Contar por estatus al inicio
		contarEstatus();
		actualizarDashboard();

		// Click en filtros
		$('#filtrosEstatus .filter-badge').on('click', function() {
			var estatus = $(this).attr('data-estatus');
			$(this).toggleClass('active');

			if ($(this).hasClass('active')) {
				if (filtrosActivos.indexOf(estatus) === -1) {
					filtrosActivos.push(estatus);
				}
			} else {
				var idx = filtrosActivos.indexOf(estatus);
				if (idx > -1) filtrosActivos.splice(idx, 1);
			}

			table.draw();
			actualizarDashboard();
		});

		// Actualizar dashboard en cada draw
		table.on('draw', function() {
			actualizarDashboard();
		});

		// EVENTOS DE ACTIVIDADES
		$(document).on('click', '.revisarActividadForo', function(e) {
			e.preventDefault();
			var estatus = $(this).attr('data-estatus-server');
			if (estatus == 'Vencida') {
				swal("Actividad vencida :(", "No realizaste esta actividad en tiempo y forma, comunícate con tu profesor...", "error", {button: "Aceptar"});
			} else {
				var id = $(this).attr('data-id');
				var nombre = $(this).attr('data-nombre');
				$.ajax({
					url: 'server/obtener_controlador_foro.php',
					type: 'POST',
					data: { id_for_cop: id, id_alu_ram: idAluRam },
					success: function(resp) {
						$('#modal_obtener_actividad').modal('show');
						$('#contenedor_modal_obtener_actividad').html(resp);
						$('#titulo_modal_obtener_actividad').html(nombre);
					}
				});
			}
		});

		$(document).on('click', '.revisarActividadEntregable', function(e) {
			e.preventDefault();
			var estatus = $(this).attr('data-estatus-server');
			if (estatus == 'Vencida') {
				swal("Actividad vencida :(", "No realizaste esta actividad en tiempo y forma, comunícate con tu profesor...", "error", {button: "Aceptar"});
			} else {
				var id = $(this).attr('data-id');
				var nombre = $(this).attr('data-nombre');
				$.ajax({
					url: 'server/obtener_controlador_entregable.php',
					type: 'POST',
					data: { id_ent_cop: id, id_alu_ram: idAluRam },
					success: function(resp) {
						$('#modal_obtener_actividad').modal('show');
						$('#contenedor_modal_obtener_actividad').html(resp);
						$('#titulo_modal_obtener_actividad').html(nombre);
					}
				});
			}
		});

		$(document).on('click', '.revisarActividadExamen', function(e) {
			e.preventDefault();
			var estatus = $(this).attr('data-estatus-server');
			if (estatus == 'Vencida') {
				swal("Actividad vencida :(", "No realizaste esta actividad en tiempo y forma, comunícate con tu profesor...", "error", {button: "Aceptar"});
			} else {
				var id = $(this).attr('data-id');
				var nombre = $(this).attr('data-nombre');
				$('#titulo_modal_obtener_actividad').html(nombre);
				$.ajax({
					url: 'server/obtener_controlador_examen.php',
					type: 'POST',
					data: { id_exa_cop: id, id_alu_ram: idAluRam },
					success: function(resp) {
						$('#modal_obtener_actividad').modal('show');
						$('#contenedor_modal_obtener_actividad').html(resp);
					}
				});
			}
		});

		// Modal retroalimentación
		$(document).on('click', '.ver-retro', function() {
			$('#modalRetroBody').text($(this).attr('data-retro'));
			$('#modalRetro').fadeIn(200);
		});

		$('.modal-retro-close').on('click', function() {
			$('#modalRetro').fadeOut(200);
		});

		$('#modalRetro').on('click', function(e) {
			if (e.target.id === 'modalRetro') $('#modalRetro').fadeOut(200);
		});
	});

	function contarEstatus() {
		var contadores = { Pendiente: 0, Vencida: 0, Realizada: 0, Calificada: 0 };
		$('#tablaActividades tbody tr').each(function() {
			var est = $(this).attr('data-estatus');
			if (contadores.hasOwnProperty(est)) contadores[est]++;
		});
		$('.cnt-pendiente').text(contadores.Pendiente);
		$('.cnt-vencida').text(contadores.Vencida);
		$('.cnt-realizada').text(contadores.Realizada);
		$('.cnt-calificada').text(contadores.Calificada);
	}

	function actualizarDashboard() {
		var puntos = 0;
		var obtenidos = 0;
		var total = 0;

		table.rows({ filter: 'applied' }).every(function() {
			var row = this.node();
			var p = parseFloat($(row).attr('data-puntos')) || 0;
			var o = parseFloat($(row).attr('data-obtenidos')) || 0;
			puntos += p;
			obtenidos += o;
			total++;
		});

		$('#totalRegistros').text(total);
		$('#totalPuntos').text(Math.round(puntos));

		if (obtenidos === 0 || puntos === 0) {
			$('#totalObtenidos').text('-');
			$('#porcentaje').text('-');
		} else {
			$('#totalObtenidos').text(obtenidos);
			var pct = (obtenidos * 100) / puntos;
			$('#porcentaje').text(Math.round(pct * 100) / 100 + '%');
		}
	}
})();
</script>