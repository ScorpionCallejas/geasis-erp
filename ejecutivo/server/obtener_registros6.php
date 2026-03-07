<?php
/**
 * ARCHIVO CORREGIDO - obtener_registros6.php
 * 
 * FIX: Ahora muestra alumnos de cualquier plantel origen cuando su plantel_beneficiado
 * coincide con los planteles del ejecutivo actual
 */

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$escala = $_POST['escala'];
if( $escala != 'grupo' ){
	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$id_eje = $_POST['id_eje'];
}

// Obtener filtros de tipo de ejecutivo
$filtroAdministrativos = isset($_POST['filtroAdministrativos']) ? $_POST['filtroAdministrativos'] : 0;
$filtroAdmisiones = isset($_POST['filtroAdmisiones']) ? $_POST['filtroAdmisiones'] : 1;

/**
 * Función para generar condición de filtro por tipo de ejecutivo
 */
function generarCondicionFiltro($filtroAdministrativos, $filtroAdmisiones, $alias = 'ej') {
	if ($filtroAdministrativos == 1 && $filtroAdmisiones == 0) {
		return "AND ({$alias}.usu_eje IS NULL AND {$alias}.ran_eje != 'DM')";
	} elseif ($filtroAdministrativos == 0 && $filtroAdmisiones == 1) {
		return "AND ({$alias}.usu_eje IS NOT NULL OR {$alias}.ran_eje = 'DM')";
	} elseif ($filtroAdministrativos == 0 && $filtroAdmisiones == 0) {
		return "AND 1 = 0";
	} else {
		return "";
	}
}

$filtroCondicion = generarCondicionFiltro($filtroAdministrativos, $filtroAdmisiones);
$filtroCondicionEjecutivo = generarCondicionFiltro($filtroAdministrativos, $filtroAdmisiones, 'ejecutivo');

?>

<?php 
// ========================================
// CASO 1: ESCALA = PLANTEL
// ========================================
if( $escala == 'plantel' ){

	$id_pla = $_POST['id_pla'];

	// Esta lógica ya estaba bien, solo la mantenemos igual
	$sql = "
		SELECT
		ci.id_cit,
		ej.id_eje,
		obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
		ej.nom_eje,
		ej_agendo.nom_eje AS nom_eje_agendo,
		ej_cerrador.nom_eje AS nom_eje_cerrador,
		ej.cor_eje,
		pl.id_pla AS id_pla,
		pl.nom_pla,
		ra.id_ram,
		al.id_alu,
		ge.id_gen,
		ar.id_alu_ram,
		al.ing_alu,
		CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
		al.tel_alu,
		ge.nom_gen,
		ra.nom_ram,
		ge.ini_gen,
		ge.fin_gen,
		ar.est1_alu_ram,
		ar.est_alu_ram,
		obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
		obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
		obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
		obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
		ci.tip_cit AS tipo_cita,
		obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
		FROM alu_ram ar
		INNER JOIN alumno al ON al.id_alu = ar.id_alu1
		INNER JOIN cita ci ON ci.id_cit = al.id_cit1
		INNER JOIN ejecutivo ej ON ej.id_eje = ci.id_eje3
		LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
		LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
		INNER JOIN rama ra ON ra.id_ram = ar.id_ram3
		INNER JOIN plantel pl ON pl.id_pla = ra.id_pla1
		INNER JOIN generacion ge ON ge.id_gen = ar.id_gen1
		WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
		AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
		AND al.plantel_beneficiado = '$id_pla'
		
		UNION ALL
		
		SELECT
		ci.id_cit,
		ej.id_eje,
		obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
		ej.nom_eje,
		ej_agendo.nom_eje AS nom_eje_agendo,
		ej_cerrador.nom_eje AS nom_eje_cerrador,
		ej.cor_eje,
		pl.id_pla AS id_pla,
		pl.nom_pla,
		ra.id_ram,
		al.id_alu,
		ge.id_gen,
		ar.id_alu_ram,
		al.ing_alu,
		CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
		al.tel_alu,
		ge.nom_gen,
		ra.nom_ram,
		ge.ini_gen,
		ge.fin_gen,
		ar.est1_alu_ram,
		ar.est_alu_ram,
		obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
		obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
		obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
		obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
		ci.tip_cit AS tipo_cita,
		obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
		FROM alu_ram ar
		INNER JOIN alumno al ON al.id_alu = ar.id_alu1
		INNER JOIN cita ci ON ci.id_cit = al.id_cit1
		INNER JOIN ejecutivo ej ON ej.id_eje = ci.id_eje3
		LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
		LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
		INNER JOIN rama ra ON ra.id_ram = ar.id_ram3
		INNER JOIN plantel pl ON pl.id_pla = ra.id_pla1
		INNER JOIN generacion ge ON ge.id_gen = ar.id_gen1
		WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
		AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
		AND al.plantel_beneficiado IS NULL
		AND ej.id_pla = '$id_pla'
	";

// ========================================
// CASO 2: ESCALA = GRUPO
// ========================================
} else if( $escala == 'grupo' ){
	
	$id_gen = $_POST['id_gen'];
	$sql = "
		SELECT
		ci.id_cit,
		ej.id_eje,
		obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
		ej.nom_eje,
		ej_agendo.nom_eje AS nom_eje_agendo,
		ej_cerrador.nom_eje AS nom_eje_cerrador,
		ej.cor_eje,
		pl.id_pla AS id_pla,
		pl.nom_pla,
		ra.id_ram,
		al.id_alu,
		ge.id_gen,
		ar.id_alu_ram,
		al.ing_alu,
		CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
		al.tel_alu,
		ge.nom_gen,
		ra.nom_ram,
		ge.ini_gen,
		ge.fin_gen,
		ar.est1_alu_ram,
		ar.est_alu_ram,
		obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
		obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
		obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
		obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
		ci.tip_cit AS tipo_cita,
		obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
		FROM alu_ram ar
		INNER JOIN alumno al ON al.id_alu = ar.id_alu1
		INNER JOIN cita ci ON ci.id_cit = al.id_cit1
		INNER JOIN ejecutivo ej ON ej.id_eje = ci.id_eje3
		LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
		LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
		INNER JOIN rama ra ON ra.id_ram = ar.id_ram3
		INNER JOIN plantel pl ON pl.id_pla = ra.id_pla1
		INNER JOIN generacion ge ON ge.id_gen = ar.id_gen1
		WHERE ej.tip_eje = 'Ejecutivo'
		AND ar.id_gen1 = '$id_gen'
	";

// ========================================
// CASO 3: SIN ESCALA ESPECÍFICA
// ========================================
} else {
	
	// ----------------------------------------
	// SUB-CASO 3.1: id_eje = "Todos"
	// ----------------------------------------
	if ( $id_eje == 'Todos' ) {
		
		// Verificar cuántos planteles tiene el ejecutivo
		$sqlPlanteles = "
			SELECT *
			FROM planteles_ejecutivo
			INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
			WHERE id_eje = '$id'
		";

		$totalValidacion = obtener_datos_consulta( $db, $sqlPlanteles )['total'];

		// ----------------------------------------
		// SUB-SUB-CASO 3.1.A: GC con 0 o 1 plantel
		// ----------------------------------------
		if( $totalValidacion == 0 || $totalValidacion == 1 ){

			$id_pla = $plantel;

			// *** LÓGICA CORREGIDA ***
			$sql = "
				SELECT
				ci.id_cit,
				ej.id_eje,
				obtener_plantel_ejecutivo(ej.id_eje) AS plantel_ejecutivo,
				ej.nom_eje,
				ej_agendo.nom_eje AS nom_eje_agendo,
				ej_cerrador.nom_eje AS nom_eje_cerrador,
				ej.cor_eje,
				pl.id_pla AS id_pla,
				pl.nom_pla,
				ra.id_ram,
				al.id_alu,
				ge.id_gen,
				ar.id_alu_ram,
				al.ing_alu,
				CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
				al.tel_alu,
				ge.nom_gen,
				ra.nom_ram,
				ge.ini_gen,
				ge.fin_gen,
				ar.est1_alu_ram,
				ar.est_alu_ram,
				obtener_fin_colegiatura(ar.id_alu_ram) AS fin_colegiatura,
				obtener_monto_colegiatura(ar.id_alu_ram) AS monto_colegiatura,
				obtener_forma_inscripcion(ar.id_alu_ram) AS forma_inscripcion,
				obtener_monto_inscripcion(ar.id_alu_ram) AS monto_inscripcion,
				ci.tip_cit AS tipo_cita,
				obtener_estatus_general(ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram) AS estatus_general
				FROM alu_ram AS ar
				INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
				INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
				INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
				LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
				LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
				INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
				INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
				INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
				WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
				AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
				AND al.plantel_beneficiado = '$id_pla'
			
				UNION ALL
			
				SELECT
				ci.id_cit,
				ej.id_eje,
				obtener_plantel_ejecutivo(ej.id_eje) AS plantel_ejecutivo,
				ej.nom_eje,
				ej_agendo.nom_eje AS nom_eje_agendo,
				ej_cerrador.nom_eje AS nom_eje_cerrador,
				ej.cor_eje,
				pl.id_pla AS id_pla,
				pl.nom_pla,
				ra.id_ram,
				al.id_alu,
				ge.id_gen,
				ar.id_alu_ram,
				al.ing_alu,
				CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
				al.tel_alu,
				ge.nom_gen,
				ra.nom_ram,
				ge.ini_gen,
				ge.fin_gen,
				ar.est1_alu_ram,
				ar.est_alu_ram,
				obtener_fin_colegiatura(ar.id_alu_ram) AS fin_colegiatura,
				obtener_monto_colegiatura(ar.id_alu_ram) AS monto_colegiatura,
				obtener_forma_inscripcion(ar.id_alu_ram) AS forma_inscripcion,
				obtener_monto_inscripcion(ar.id_alu_ram) AS monto_inscripcion,
				ci.tip_cit AS tipo_cita,
				obtener_estatus_general(ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram) AS estatus_general
				FROM alu_ram AS ar
				INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
				INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
				INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
				LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
				LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
				INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
				INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
				INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
				WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
				AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
				AND al.plantel_beneficiado IS NULL
				AND ej.id_pla = '$id_pla'
			";

		// ----------------------------------------
		// SUB-SUB-CASO 3.1.B: DC con múltiples planteles
		// *** ESTE ES EL CASO PRINCIPAL QUE SE CORRIGIÓ ***
		// ----------------------------------------
		} else {
			
			$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

			// Construir lista de IDs de planteles separados por coma
			$plantelesList = '';
			$contador = 0;
			while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
				if ($contador > 0) {
					$plantelesList .= ', ';
				}
				$plantelesList .= $filaPlanteles['id_pla'];
				$contador++;
			}

			// *** QUERY CORREGIDA CON UNION ALL ***
			$sql = "
				SELECT
				obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
				ci.id_cit,
				ej.id_eje,
				ej.nom_eje,
				ej_agendo.nom_eje AS nom_eje_agendo,
				ej_cerrador.nom_eje AS nom_eje_cerrador,
				ej.cor_eje,
				pl.id_pla AS id_pla,
				pl.nom_pla,
				ra.id_ram,
				al.id_alu,
				ge.id_gen,
				ar.id_alu_ram,
				al.id_alu,
				al.ing_alu,
				CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
				al.tel_alu, 
				ge.nom_gen,
				ra.nom_ram,
				ge.ini_gen,
				ge.fin_gen,
				ar.est1_alu_ram,
				ar.est_alu_ram,
				obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
				obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
				obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
				obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
				ci.tip_cit AS tipo_cita,
				obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
				FROM alu_ram AS ar
				INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
				INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
				INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
				LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
				LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
				INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
				INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
				INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
				WHERE DATE( al.ing_alu ) BETWEEN '$inicio' AND '$fin' 
				AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
				AND al.plantel_beneficiado IN ($plantelesList)
				
				UNION ALL
				
				SELECT
				obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
				ci.id_cit,
				ej.id_eje,
				ej.nom_eje,
				ej_agendo.nom_eje AS nom_eje_agendo,
				ej_cerrador.nom_eje AS nom_eje_cerrador,
				ej.cor_eje,
				pl.id_pla AS id_pla,
				pl.nom_pla,
				ra.id_ram,
				al.id_alu,
				ge.id_gen,
				ar.id_alu_ram,
				al.id_alu,
				al.ing_alu,
				CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
				al.tel_alu, 
				ge.nom_gen,
				ra.nom_ram,
				ge.ini_gen,
				ge.fin_gen,
				ar.est1_alu_ram,
				ar.est_alu_ram,
				obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
				obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
				obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
				obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
				ci.tip_cit AS tipo_cita,
				obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
				FROM alu_ram AS ar
				INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
				INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
				INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
				LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
				LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
				INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
				INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
				INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
				WHERE DATE( al.ing_alu ) BETWEEN '$inicio' AND '$fin' 
				AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
				AND al.plantel_beneficiado IS NULL
				AND ej.id_pla IN ($plantelesList)
			";
		}

	// ----------------------------------------
	// SUB-CASO 3.2: Ejecutivo específico (NO "Todos")
	// ----------------------------------------
	} else {
		
		$sql = "
			SELECT
			obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
			ci.id_cit,
			ej.id_eje,
			ej.nom_eje,
			ej_agendo.nom_eje AS nom_eje_agendo,
			ej_cerrador.nom_eje AS nom_eje_cerrador,
			ej.cor_eje,
			pl.id_pla AS id_pla,
			pl.nom_pla,
			ra.id_ram,
			al.id_alu,
			ge.id_gen,
			ar.id_alu_ram,
			al.ing_alu,
			CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
			al.tel_alu, 
			ge.nom_gen,
			ra.nom_ram,
			ge.ini_gen,
			ge.fin_gen,
			ar.est1_alu_ram,
			ar.est_alu_ram,
			obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
			obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
			obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
			obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
			ci.tip_cit AS tipo_cita,
			obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
			FROM alu_ram AS ar
			INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
			INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
			INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
			LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
			LEFT JOIN ejecutivo ej_cerrador ON ej_cerrador.id_eje = ci.id_eje_cerrador
			INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
			INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
			INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
			WHERE ej.id_eje = '$id_eje' 
			AND DATE( al.ing_alu ) BETWEEN '$inicio' AND '$fin' 
			AND ej.tip_eje = 'Ejecutivo' $filtroCondicion
		";
	}
}

// Agregar filtro de reingresos
$sqlReingresos = " AND est1_alu_ram IS NULL";
$sql .= $sqlReingresos;

// echo $sql; // Descomentar para debug
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<!-- Modal estructura -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SELECCIONA EL PDF QUE NECESITAS DEL REGISTRO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" id="btnSolicitud">
                        <i class="fas fa-file-alt me-2"></i> Solicitud de Inscripción
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" id="btnTramites">
                        <i class="fas fa-file-import me-2"></i> Carta de Trámites  
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" id="btnExpediente">
                        <i class="fas fa-folder-open me-2"></i> Carta de Expediente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">

	moment.locale('es');

	var container = document.querySelector('#data-sheet');
	var colHeaders = ['FECHA', 'CDE ORIGEN', 'CDE DESTINO', 'CONSULTOR', 'AGENDO', 'EJECUTIVO', 'TEAM LEADER', 'SALES MANAGER', 'PROGRAMA', 'GRUPO', 'NOMBRE', 'TELÉFONO', 'FECHA LÍMITE COLEG.', 'MONTO COLEG.', 'TIPO DE CITA', 'FORMA. PAGO', 'MONTO INSCRIP.', 'ESTATUS', 'PRESENTACION', 'ID' ];
	var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]);

	function nameColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		const id_alu_ram = instance.getDataAtCell(row, 19);
		
		if (value && id_alu_ram) {
			const wrapper = document.createElement('div');
			wrapper.innerHTML = value;
			wrapper.style.cursor = 'pointer';
			wrapper.classList.add('text-primary', 'custom-link');
			
			wrapper.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				abrirModal(id_alu_ram);
			});
			
			td.innerHTML = '';
			td.appendChild(wrapper);
		} else {
			Handsontable.renderers.TextRenderer.apply(this, arguments);
		}
		
		td.style.backgroundColor = '#E3E6E7';
	}

	function abrirModal(id_alu_ram) {
		const modal = new bootstrap.Modal(document.getElementById('documentModal'));
		const modalEl = document.getElementById('documentModal');

		const btnSolicitud = modalEl.querySelector('#btnSolicitud');
		const btnTramites = modalEl.querySelector('#btnTramites');
		const btnExpediente = modalEl.querySelector('#btnExpediente');

		btnSolicitud.onclick = (e) => {
			e.preventDefault();
			window.open(`solicitud_inscripcion.php?id_alu_ram=${id_alu_ram}`, '_blank');
			modal.hide();
		};

		btnTramites.onclick = (e) => {
			e.preventDefault();
			window.open(`carta_tramites.php?id_alu_ram=${id_alu_ram}`, '_blank');
			modal.hide();
		};

		btnExpediente.onclick = (e) => {
			e.preventDefault();
			window.open(`carta_expediente.php?id_alu_ram=${id_alu_ram}`, '_blank');
			modal.hide();
		};

		modal.show();
	}

	<?php
		// Función para obtener el Team Leader y Sales Manager de un ejecutivo
		function obtenerJerarquiaEjecutivo($id_eje, $db) {
			$resultado = array(
				'team_leader' => '',
				'sales_manager' => ''
			);
			
			$sqlEjecutivo = "SELECT ej.id_eje, ej.id_padre, ej.nom_eje, ej.ran_eje, ej.usu_eje 
							 FROM ejecutivo ej 
							 WHERE ej.id_eje = '$id_eje'";
			$resultadoEjecutivo = mysqli_query($db, $sqlEjecutivo);
			
			if ($resultadoEjecutivo && mysqli_num_rows($resultadoEjecutivo) > 0) {
				$filaEjecutivo = mysqli_fetch_assoc($resultadoEjecutivo);
				
				if ($filaEjecutivo['ran_eje'] == 'TL') {
					$resultado['team_leader'] = $filaEjecutivo['nom_eje'];
					
					if (!empty($filaEjecutivo['id_padre'])) {
						$sqlPadreTL = "SELECT ej.id_eje, ej.nom_eje, ej.ran_eje, ej.usu_eje 
									  FROM ejecutivo ej 
									  WHERE ej.id_eje = '{$filaEjecutivo['id_padre']}'";
						$resultadoPadreTL = mysqli_query($db, $sqlPadreTL);
						
						if ($resultadoPadreTL && mysqli_num_rows($resultadoPadreTL) > 0) {
							$filaPadreTL = mysqli_fetch_assoc($resultadoPadreTL);
							
							if ($filaPadreTL['ran_eje'] == 'GC' && $filaPadreTL['usu_eje'] === NULL) {
								$resultado['sales_manager'] = $filaPadreTL['nom_eje'];
							}
						}
					}
				}
				elseif ($filaEjecutivo['ran_eje'] == 'GC' && $filaEjecutivo['usu_eje'] === NULL) {
					$resultado['sales_manager'] = $filaEjecutivo['nom_eje'];
				}
				else {
					if (!empty($filaEjecutivo['id_padre'])) {
						$sqlSuperior = "SELECT ej.id_eje, ej.id_padre, ej.nom_eje, ej.ran_eje, ej.usu_eje 
									   FROM ejecutivo ej 
									   WHERE ej.id_eje = '{$filaEjecutivo['id_padre']}'";
						$resultadoSuperior = mysqli_query($db, $sqlSuperior);
						
						if ($resultadoSuperior && mysqli_num_rows($resultadoSuperior) > 0) {
							$filaSuperior = mysqli_fetch_assoc($resultadoSuperior);
							
							if ($filaSuperior['ran_eje'] == 'TL') {
								$resultado['team_leader'] = $filaSuperior['nom_eje'];
							}
							
							if ($filaSuperior['ran_eje'] == 'GC' && $filaSuperior['usu_eje'] === NULL) {
								$resultado['sales_manager'] = $filaSuperior['nom_eje'];
							}
							
							if (!empty($filaSuperior['id_padre']) && $filaSuperior['ran_eje'] != 'GC') {
								$sqlAbuelo = "SELECT ej.id_eje, ej.nom_eje, ej.ran_eje, ej.usu_eje 
											 FROM ejecutivo ej 
											 WHERE ej.id_eje = '{$filaSuperior['id_padre']}'";
								$resultadoAbuelo = mysqli_query($db, $sqlAbuelo);
								
								if ($resultadoAbuelo && mysqli_num_rows($resultadoAbuelo) > 0) {
									$filaAbuelo = mysqli_fetch_assoc($resultadoAbuelo);
									
									if ($filaAbuelo['ran_eje'] == 'GC' && $filaAbuelo['usu_eje'] === NULL) {
										$resultado['sales_manager'] = $filaAbuelo['nom_eje'];
									}
								}
							}
						}
					}
				}
			}
			
			return $resultado;
		}

		// Código principal
		$data = [];
		$resultado = mysqli_query($db, $sql);

		if ($escala != '' && $escala == 'estructura') {
			$data = obtener_tabla_estructura_registros($id_eje, $inicio, $fin, $db, $data);
		}

		$resultado = mysqli_query($db, $sql);

		while ($fila = mysqli_fetch_assoc($resultado)) {
			$data[] = $fila;
		}

		if (sizeof($data) > 0) {
			foreach ($data as $fila) {
				$jerarquia = obtenerJerarquiaEjecutivo($fila['id_eje'], $db);
				
				$ing_alu = json_encode(fechaFormateadaCompacta2($fila['ing_alu']));
				$plantel_origen = json_encode($fila['plantel_ejecutivo']);
				$plantel_destino = json_encode($fila['nom_pla']);
				$nom_eje = json_encode($fila['nom_eje']);
				$nom_eje_agendo = json_encode($fila['nom_eje_agendo']);
				$nom_eje_cerrador = json_encode($fila['nom_eje_cerrador']);
				
				$team_leader = json_encode($jerarquia['team_leader']);
				$sales_manager = json_encode($jerarquia['sales_manager']);
				
				$nom_ram = json_encode($fila['nom_ram']);
				$nom_gen = json_encode($fila['nom_gen']);
				$nom_alu = json_encode($fila['nom_alu']);
				$tel_alu = json_encode($fila['tel_alu']);
				$fin_colegiatura = json_encode(fechaFormateadaCompacta2($fila['fin_colegiatura']));
				$monto_colegiatura = json_encode($fila['monto_colegiatura']);
				$tipo_cita = json_encode($fila['tipo_cita']);
				$forma_inscripcion = json_encode($fila['forma_inscripcion']);
				$monto_inscripcion = json_encode($fila['monto_inscripcion']);
				$estatus_general = json_encode($fila['estatus_general']);

				$id_alu_ram_aux = $fila['id_alu_ram'];
				$sqlPresentacion = "SELECT obtener_estatus_presentacion($id_alu_ram_aux) AS estatus_presentacion";
				$estatus_presentacion = obtener_datos_consulta($db, $sqlPresentacion)['datos']['estatus_presentacion'];

				$est_alu_ram = json_encode($estatus_presentacion);
				$id_alu_ram = json_encode($fila['id_alu_ram']);

				echo "data.push([$ing_alu, $plantel_origen, $plantel_destino, $nom_eje, $nom_eje_agendo, $nom_eje_cerrador, $team_leader, $sales_manager, $nom_ram, $nom_gen, $nom_alu, $tel_alu, $fin_colegiatura, $monto_colegiatura, $tipo_cita, $forma_inscripcion, $monto_inscripcion, $estatus_general, $est_alu_ram, $id_alu_ram]);\n";
			}
		} else {
			echo 'data = Array(20).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]);';
		}
	?>
	
	<?php 
		if( $permisos == 1 ){
	?>
			var dropdownEjecutivo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];
	<?php
		} else if( $permisos == 2 ){
	?>
			var dropdownEjecutivo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_cad1 = 1 AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];

	<?php
		} else {
	?>
			var dropdownEjecutivo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];

	<?php
		}
	?>

	var hot;

	if (hot) {
	    hot.destroy();
	}

	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
	  	td.style.backgroundColor = '#E3E6E7';
	}

	hot = new Handsontable(container, {
		language: 'es-MX',
		data,
		height: 'auto',
		width: '100%',
		hiddenColumns: {
			columns: [19, 12, 15],
			indicators: false
		},
		stretchH: 'all',
		colHeaders: colHeaders,
		rowHeaders: true,
		fixedRowsTop: 1,
		
		cells: function deshabilitarFila(row, col) {
			var cellProperties = {};

			if (col === 0 || col === 1 || col === 2 || col === 3 || col === 4 || col === 5 || col === 6 || col === 7 || col === 8 || col === 9 || col === 10 || col === 11 || col === 12 || col === 13 || col === 14 || col === 15 || col === 16 <?php echo ($rangoUsuario == 'Asesor')? ' || col === 16 ' : ''; ?>) {
				if (col === 10) {
					cellProperties.renderer = nameColumnRenderer;
				} else {
					cellProperties.renderer = firstColumnRenderer;
				}
			}

			return cellProperties;
		},

		manualColumnResize: true,
		minRows: 20,
		minSpareRows: 1,
		licenseKey: 'non-commercial-and-evaluation',
		afterChange: function(changes, source) {
			if (source === 'loadData' || source === 'populateFromArray') {
				return;
			}
			if (changes) {
				changes.forEach(([row, prop, oldValue, newValue]) => {
					if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
						let rowData = hot.getDataAtRow(row);
						adicionarFila(rowData);
					} else {
						guardarCelda(hot, row, prop, newValue);
					}
				});
			}
		},

		filters: true,
		dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
		height: 'auto',
		columnSorting: true,

		contextMenu: {
			items: {
				"row_above": {
					name: 'Insertar fila arriba',
					disabled: function() {
						return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
					}
				},
				"row_below": {
					name: 'Insertar fila debajo'
				},
				<?php if($rangoUsuario == 'GC' || $rangoUsuario == 'DC'){ ?>
				"custom_option": {
					name: 'ELIMINAR REGISTRO',
					callback: function() {
						try {
							var selection = this.getSelected();
							if (selection && selection.length > 0) {
								var selectedRow = selection[0][0];
								var id_alu_ram = this.getDataAtCell(selectedRow, 19);
								var eliminar_alumno = true;

								swal({
									title: "Continuar",
									text: "¿Estás seguro que deseas eliminar el registro?",
									icon: "warning",
									buttons: ["Cancelar", "Aceptar"],
									dangerMode: true,
								})
								.then((willContinue) => {
									if (willContinue) {
										swal("Continuar", "Confirmado correctamente", "success").then((confirmation) => {
											$.ajax({
												type: "POST",
												dataType: 'json',
												url: "server/controlador_alumno.php",
												data: { id_alu_ram, eliminar_alumno },
												success: function(response) {
													console.log(response);
													obtener_datos();
												},
											});
										});
									}
								});
							}
						} catch (error) {
							console.error("Se produjo un error:", error);
						}
					}
				}
				<?php } ?>
			}
		},

		columns: [
			{ readOnly: true, }, // 0-17
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
			{ readOnly: true, },
		]
	});

	obtenerConteosEstatus( hot );
	
	function obtenerConteosEstatus(hot) {
		var posiblesEstados = {
			'REGISTRO': 0,
			'REGISTRADO': 0,
			'ACTIVO': 0,
			'NP': 0,
			'BAJA': 0,
			'DESERCION': 0,
			'REINGRESO': 0,
			'FIN CURSO': 0,
			'GRADUADO': 0
		};

		var statusColumnData = hot.getDataAtCol(17);

		statusColumnData.forEach(function(status) {
			if (status && posiblesEstados.hasOwnProperty(status)) {
				posiblesEstados[status]++;
			}
		});

		var conteoTotal = Object.values(posiblesEstados).reduce(function(total, currentValue) {
			return total + currentValue;
		}, 0);

		$('#total_registros').text(conteoTotal);
		$('#total_registro').text(posiblesEstados['REGISTRO']);
		$('#total_registrado').text(posiblesEstados['REGISTRADO']);
		$('#total_activo').text(posiblesEstados['ACTIVO']);
		$('#total_np').text(posiblesEstados['NP']);
		$('#total_baja').text(posiblesEstados['BAJA']);
		$('#total_desercion').text(posiblesEstados['DESERCION']);
		$('#total_reingreso').text(posiblesEstados['REINGRESO']);
		$('#total_fin_curso').text(posiblesEstados['FIN CURSO']);
		$('#total_graduado').text(posiblesEstados['GRADUADO']);
	}

	container.classList.add('dark-mode');

	var exportPlugin = hot.getPlugin('exportFile');
	var button = document.querySelector('#export-file');
	
	button.addEventListener('click', () => {
		const data = [colHeaders, ...hot.getData()];
		const worksheet = XLSX.utils.aoa_to_sheet(data);
		const workbook = XLSX.utils.book_new();
		XLSX.utils.book_append_sheet(workbook, worksheet, 'Hoja1');

		const fileName = 'Reporte_registros.xlsx';
		const wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' });

		function s2ab(s) {
			const buf = new ArrayBuffer(s.length);
			const view = new Uint8Array(buf);
			for (let i = 0; i < s.length; i++) {
			view[i] = s.charCodeAt(i) & 0xff;
			}
			return buf;
		}

		const blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });

		if (navigator.msSaveBlob) {
			navigator.msSaveBlob(blob, fileName);
		} else {
			const link = document.createElement('a');
			if (link.download !== undefined) {
			const url = URL.createObjectURL(blob);
			link.setAttribute('href', url);
			link.setAttribute('download', fileName);
			link.style.visibility = 'hidden';
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
			}
		}
	});

	function adicionarFila(rowData) {
		alert("Datos de la fila: " + JSON.stringify(rowData));
	}

	function guardarCelda(hot, row, column, value) {
		var id = hot.getDataAtCell(row, 19);
		var nombre = hot.getDataAtCell(row, 10);
		var telefono = hot.getDataAtCell(row, 12);

		console.log(hot);

		var id_eje = $('#selector_ejecutivo option:selected').val();
		var valor = value;

		<?php 
			if( $rangoUsuario == 'GC' || $permisos == 1 || $permisos == 2 ){
		?>
				var id_eje_ejecutivo;
				if (column === 3) {
					var dropdownValue = hot.getDataAtCell(row, column);
					var selectedOption = dropdownEjecutivo.find(function(option) {
						return option.label === dropdownValue;
					});
					id_eje_ejecutivo = selectedOption ? selectedOption.value : null;
					valor = id_eje_ejecutivo;
				}
		<?php
			}
		?>

		if ( id == "" || id == null || id == undefined || id == '--' ) {
			var accion = "Alta";
			var campo = obtenerCampoValor( column );
			
			console.log('alta');

			if ( ( column == 1 && validarFormatoHora(value) ) || ( column != 1 ) ) {
				if(valor){
					console.log('ajax alta');
					if( column == 1 ){
						var data_json = { campo, valor, accion, id_eje };
					} else {
						var hor_cit = hot.getDataAtCell(row, 19);
						var data_json = { campo, valor, accion, id_eje, hor_cit };
						console.log('hor_cit :D');
					}
					
					$.ajax({
						url: 'server/controlador_cita2.php',
						type: 'POST',
						data: data_json,
						dataType: 'json',
						success: function( data ){
							console.log( 'response: '+data );

							hot.setDataAtCell(row, 18, data.id_cit);
							hot.setDataAtCell(row, 0, '--');
							hot.setDataAtCell(row, 1, data.hor_cit);
							hot.setDataAtCell(row, 2, data.cit_cit);
							hot.setDataAtCell(row, 3, data.nom_eje_agendo);
							hot.setDataAtCell(row, 4, data.nom_eje_cerrador);
							hot.setDataAtCell(row, 5, data.nom_eje_cerrador);
							hot.setDataAtCell(row, 6, data.team_leader || '');
							hot.setDataAtCell(row, 7, data.sales_manager || '');
							hot.setDataAtCell(row, 8, data.nom_eje);
							hot.setDataAtCell(row, 9, data.tip_cit);
							hot.setDataAtCell(row, 10, data.nom_cit);
							hot.setDataAtCell(row, 11, data.eda_cit);
							hot.setDataAtCell(row, 12, data.tel_cit);
							hot.setDataAtCell(row, 13, data.pro_cit);
							hot.setDataAtCell(row, 14, data.can_cit);
							hot.setDataAtCell(row, 15, data.est_cit);
							hot.setDataAtCell(row, 16, data.efe_cit);
							hot.setDataAtCell(row, 17, data.obs_cit);
							
							obtenerConteosEstatus( hot );
						}
					});
				}
			}

		} else {
			var accion = "Cambio";
			var campo = obtenerCampoValor( column );
			var id_alu_ram = id;

			$.ajax({
				url: 'server/controlador_cita2.php',
				type: 'POST',
				data: { campo, valor, accion, id_alu_ram },
				dataType: 'json',
				success: function( data ){
					console.log( 'edicion :DDDD ---->'+data );
				}
			});
		}

		function obtenerCampoValor(column) {
			let columnName;
			if (column == 1) {
				columnName = "hor_cit";
			} else if (column == 2) {
				columnName = "cit_cit";
			} else if (column == 3) {
				columnName = "id_eje_ejecutivo";
			} else if (column == 4) {
				columnName = "id_eje_ejecutivo";
			} else if (column == 5) {
				columnName = "id_eje_cerrador";
			} else if (column == 6) {
				columnName = "team_leader";
			} else if (column == 7) {
				columnName = "sales_manager";
			} else if (column == 8) {
				columnName = "id_eje_ejecutivo";
			} else if (column == 9) {
				columnName = "tip_cit";
			} else if (column == 10) {
				columnName = "nom_cit";
			} else if (column == 11) {
				columnName = "eda_cit";
			} else if (column == 12) {
				columnName = "tel_cit";
			} else if (column == 13) {
				columnName = "pro_cit";
			} else if (column == 14) {
				columnName = "can_cit";
			} else if (column == 15) {
				columnName = "est_cit";
			} else if (column == 16) {
				columnName = "efe_cit";
			} else if (column == 17) {
				columnName = "obs_cit";
			}
			return columnName;
		}
	}
	
</script>