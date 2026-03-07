<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$inicio = $_POST['inicio'];
$fin = $_POST['fin']; 
$id_pla = $_POST['id_pla'];

if(isset($_POST['tipoPago'])){
   $tipoPago = $_POST['tipoPago'];
}

if(isset($_POST['formaPago'])){
   $formaPago = $_POST['formaPago'];
}

$sql = "
   SELECT 
   pago.id_pag AS fol_pag,
   obtener_plantel_ejecutivo( vista_alumnos.id_eje3 ) AS nom_pla_eje,
   vista_alumnos.nom_eje AS nom_eje,
   pago.est_pag AS est_pag,
   pago.con_pag AS con_pag,
   abono_pago.mon_abo_pag AS mon_abo_pag,
   OBTENER_TIPO_ABONO(pago.id_pag) AS tip_abo_pag,
   pago.ini_pag AS ini_pag,
   pago.fin_pag AS fin_pag,
   pago.obs_pag AS obs_pag,
   pago.tip_pag AS tip_pag,
   pago.fac_pag AS fac_pag,
   pago.mon_ori_pag AS mon_ori_pag,
   pago.mon_pag AS mon_pag,
   pago.id_pag AS id_pag,
   pago.fec_pag AS fec_pag,
   abono_pago.res_abo_pag AS res_abo_pag,
   abono_pago.fec_abo_pag AS fec_abo_pag,
   pago.id_alu_ram10 AS id_alu_ram10,
   pago.id_gen_pag2 AS id_gen_pag2,
   alu_ram.id_ram3 AS id_ram3,
   rama.nom_ram AS nom_ram,
   CONCAT_WS(' ',
           `alumno`.`nom_alu`,
           `alumno`.`app_alu`,
           `alumno`.`apm_alu`) AS `nom_alu`,
   alumno.id_pla8 AS id_pla8,
   generacion.nom_gen AS nom_gen,
   generacion.id_gen AS id_gen,
   plantel.nom_pla AS nom_pla
   FROM alumno
   JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
   JOIN vista_alumnos ON vista_alumnos.id_alu1 = alumno.id_alu
   JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
   JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
   JOIN generacion ON alu_ram.id_gen1 = generacion.id_gen
   JOIN rama ON alu_ram.id_ram3 = rama.id_ram
   JOIN plantel ON alumno.id_pla8 = plantel.id_pla
   WHERE ( fec_abo_pag BETWEEN '$inicio' AND '$fin' )
";

// formaPago
if ((isset($_POST['formaPago'])) && (sizeof($_POST['formaPago']) > 0)) {
	$formaPago = $_POST['formaPago'];
	
	$sql .= " AND ";
	for ($i = 0; $i < sizeof($formaPago); $i++) {
		if (sizeof($formaPago) == 1) {
			if($formaPago[$i] == 'Depósito') {
				$sql .= " ( tip_abo_pag != 'Efectivo' )";
			} else {
				$sql .= " ( tip_abo_pag = '$formaPago[$i]' )";
			}
			break;
		} else if ($i == (sizeof($formaPago) -1)) {
			if($formaPago[$i] == 'Depósito') {
				$sql .= " tip_abo_pag != 'Efectivo' )";
			} else {
				$sql .= " tip_abo_pag = '$formaPago[$i]' )";
			}
		} else {
			if ($i == 0) {
				$sql .= " ( ";
			}
			if($formaPago[$i] == 'Depósito') {
				$sql .= "tip_abo_pag != 'Efectivo' OR ";
			} else {
				$sql .= "tip_abo_pag = '$formaPago[$i]' OR ";
			}
		}
	}
 }
// FIN formaPago

// tipoPago
if ((isset($_POST['tipoPago'])) && (sizeof($_POST['tipoPago']) > 0)) {
   $tipoPago = $_POST['tipoPago'];
   
   $sql .= " AND ";
   for ($i = 0; $i < sizeof($tipoPago); $i++) {
       if (sizeof($tipoPago) == 1) {
           $sql .= " 
               ( tip_pag = '$tipoPago[$i]' )
           ";
           break;
           break;
       } else if ($i == (sizeof($tipoPago) -1)) {
           $sql .= " 
               tip_pag = '$tipoPago[$i]' )
           ";
       } else {
           if ($i == 0) {
               $sql .= " ( ";
           }
           $sql .= "tip_pag = '$tipoPago[$i]' OR ";
       }
   }
}
// FIN tipoPago

// Condición de planteles
if ($id_pla == 'Nacional') {
   // Obtener planteles del ejecutivo
   $sql_planteles = "SELECT id_pla FROM planteles_ejecutivo WHERE id_eje = $id";
   $result_planteles = mysqli_query($db, $sql_planteles);
   
   $planteles = [];
   while ($row = mysqli_fetch_assoc($result_planteles)) {
       $planteles[] = $row['id_pla'];
   }
   
   // Agregar 
   if (count($planteles) > 0) {
       $sql .= " AND alumno.id_pla8 IN (" . implode(',', $planteles) . ")";
   }
} else {
   // Caso para plantel específico
   $sql .= " AND alumno.id_pla8 = '$id_pla'";
}

$sql .= ' GROUP BY pago.id_pag';

// echo $sql;
// echo "id_pla: ".$id_pla;
?>

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
    moment.locale('es');

    var container = document.querySelector('#data-sheet');
    var colHeaders = ['FOLIO', 'ESTATUS', 'CONCEPTO', 'TIPO', 'FECHA DE MOVIMIENTO', 'RESPONSABLE', 'ADEUDO', 'COBRADO', 'VENCIMIENTO', 'FORMA PAGO', 'MATRÍCULA', 'ALUMNO', 'GPO', 'ID_GEN', 'PROGRAMA', 'CDE', 'CDE ORIGEN', 'CONSULTOR'];
	var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]);

<?php
    $total = obtener_datos_consulta($db, $sql)['total'];

    $totales_tipos_pago = array();
    $totales_tipos_pago['t_colegiatura'] = 0;
    $totales_tipos_pago['t_inscripcion'] = 0;
    $totales_tipos_pago['t_tramite'] = 0;
    $totales_tipos_pago['t_varios'] = 0;

    if ($total > 0) {
		$resultado = mysqli_query($db, $sql);
	 
		while($fila = mysqli_fetch_assoc($resultado)){
			// Capturamos primero todos los campos
			$id_gen = json_encode($fila['id_gen']); 
			$fol_pag = json_encode($fila['fol_pag']);
			$est_pag = json_encode($fila['est_pag']);
			$con_pag = json_encode($fila['con_pag']);
			
			// Manejo del tipo de pago
			if($fila['tip_pag'] == 'Otros'){
				$fila['tip_pag'] = 'Trámite';
			}
			$tip_pag = json_encode($fila['tip_pag']);
			
			// Campos formateados
			$fec_abo_pag = json_encode(fechaFormateadaCompacta2($fila['fec_abo_pag']));
			$res_abo_pag = json_encode($fila['res_abo_pag']);
			$nom_pla = json_encode($fila['nom_pla']);
			$mon_pag = json_encode(formatearDinero($fila['mon_pag']));
	 
			// Sumatorias según tipo de pago
			switch($fila['tip_pag']) {
				case 'Colegiatura':
					$totales_tipos_pago['t_colegiatura'] += $fila['mon_abo_pag'];
					break;
				case 'Inscripción':
					$totales_tipos_pago['t_inscripcion'] += $fila['mon_abo_pag'];
					break;
				case 'Trámite':
					$totales_tipos_pago['t_tramite'] += $fila['mon_abo_pag'];
					break;
				case 'Varios':
					$totales_tipos_pago['t_varios'] += $fila['mon_abo_pag'];
					break;
			}
	 
			$mon_abo_pag = json_encode(formatearDinero($fila['mon_abo_pag']));
			$fin_pag = json_encode(fechaFormateadaCompacta2($fila['fin_pag']));
			$tip_abo_pag = json_encode($fila['tip_abo_pag']);
			$id_alu_ram = json_encode($fila['id_alu_ram10']);
			$nom_alu = json_encode($fila['nom_alu']);
			$nom_gen = json_encode($fila['nom_gen']);
			$nom_ram = json_encode($fila['nom_ram']);
			$nom_pla_eje = json_encode($fila['nom_pla_eje']);
			$nom_eje = json_encode($fila['nom_eje']);
	 
			// Push con el orden correcto: nom_gen - id_gen - nom_ram
			echo "data.push([$fol_pag, $est_pag, $con_pag, $tip_pag, $fec_abo_pag, $res_abo_pag, $mon_pag, $mon_abo_pag, $fin_pag, $tip_abo_pag, $id_alu_ram, $nom_alu, $nom_gen, $id_gen, $nom_ram, $nom_pla, $nom_pla_eje, $nom_eje]);\n";
		}
	 } else {
		// Actualizado a 16 columnas para incluir id_gen
		echo 'data = Array(16).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]);';
	 }

    echo '$("#sumatoria_colegiatura").text("'.formatearDinero($totales_tipos_pago['t_colegiatura']).'");';
    echo '$("#sumatoria_tramite").text("'.formatearDinero($totales_tipos_pago['t_tramite']).'");';
    echo '$("#sumatoria_inscripcion").text("'.formatearDinero($totales_tipos_pago['t_inscripcion']).'");';
    echo '$("#sumatoria_varios").text("'.formatearDinero($totales_tipos_pago['t_varios']).'");';

    // Sumatoria total
    $suma_total = $totales_tipos_pago['t_colegiatura'] + $totales_tipos_pago['t_tramite'] + $totales_tipos_pago['t_inscripcion'] + $totales_tipos_pago['t_varios'];

    echo '$("#conteo_total").text("'.formatearDinero($suma_total).'");';
?>
	// FUNCION QUE CAMBIAR COLOR DE COLUMNA
	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#17202A'; // Cambia el color de fondo
	}

	function linkRenderer(instance, td, row, col, prop, value, cellProperties) {
		td.innerHTML = '';
		const link = document.createElement('a');
		const rowData = instance.getDataAtRow(row);
		
		if (prop === 0) { // folio pago
			link.href = '#';
			link.classList.add('ver-historial'); // Clase para el trigger de la modal
			link.dataset.folio = value; // Guardamos el folio como data attribute
		} else if (prop === 11) { // nom_alu
			link.href = `consulta_alumno.php?id_alu_ram=${rowData[10]}`; // id_alu_ram
		} else if (prop === 12) { // nom_gen
			link.href = `alumnos.php?id_gen=${rowData[13]}`; // id_gen ahora está en posición 13
		}
		
		link.target = prop === 0 ? '' : '_blank'; // Solo _blank para los que no son folio
		link.textContent = value;
		
		// Agregamos las clases de estilo
		link.classList.add('text-primary', 'custom-link');
		
		td.appendChild(link);
	}

	var hot;  // Declaración al inicio del script o función
	if (hot) {
		hot.destroy();
	}

	hot = new Handsontable(container, {
		language: 'es-MX',
		data,
		height: 'auto',
		width: '100%',
		hiddenColumns: {
			columns: [13, 5], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
			indicators: true, // Esto muestra el indicador de columnas ocultas
			contextMenu: {
				enabled: true,
				items: {
					'show_column': {
						name: 'Mostrar columna'
					},
					'hide_column': {
						name: 'Ocultar columna'
					}
				}
			}
		},
		stretchH: 'all',
		colHeaders: colHeaders,
		rowHeaders: true,

		manualColumnResize: true,
		minRows: 20,
		minSpareRows: 1,
		licenseKey: 'non-commercial-and-evaluation',
		afterChange: function(changes, source) {
			if (source === 'loadData' || source === 'populateFromArray') {
				// Si la fuente es loadData o populateFromArray, no hacer nada
				return;
			}
			if (changes) {
				changes.forEach(([row, prop, oldValue, newValue]) => {
					// Ignora los cambios en las columnas 0 y 2
					// POR EJEMPLO ID Y FEC CAPTURA QUE SON DATOS FIJOS
					if (prop === 0) {
						return;
					}

					if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
						// Si es una fila nueva
						let rowData = hot.getDataAtRow(row);
						adicionarFila(rowData);
					} else {
						// Si es una fila existente
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
				hidden_columns_show: {
					name: 'Mostrar columna oculta'
				},
				hidden_columns_hide: {
					name: 'Ocultar columna'
				},
				"row_above": {
					name: 'Insertar fila arriba',
					disabled: function() {
						return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
					}
				},
				"row_below": {
					name: 'Insertar fila debajo'
				}
			}
		},

		columns: [
			{ readOnly: true, renderer: linkRenderer },  
			{ readOnly: true },  // ESTATUS
			{ readOnly: true },  // CONCEPTO
			{ readOnly: true },  // TIPO
			{ readOnly: true },  // FECHA DE MOVIMIENTO
			{ readOnly: true },  // RESPONSABLE
			{ readOnly: true },  // ADEUDO
			{ readOnly: true },  // COBRADO
			{ readOnly: true },  // VENCIMIENTO
			{ readOnly: true },  // FORMA PAGO
			{ readOnly: true },  // MATRÍCULA
			{ readOnly: true, renderer: linkRenderer },  // nom_alu
			{ readOnly: true, renderer: linkRenderer },  // nom_gen
			{ readOnly: true },  // id_gen
			{ readOnly: true },  // nom_ram
			{ readOnly: true },  // nom_pla
			{ readOnly: true },  // CDE ORIGEN
			{ readOnly: true }   // nom_eje
		]
	});

	// Listener para los links de historial
	$(document).on('click', '.ver-historial', function(e) {
		e.preventDefault();
		let id_pag = $(this).data('folio');
		
		$.ajax({
			url: 'server/obtener_historial_pago.php',
			type: 'POST',
			data: { id_pag },
			success: function(respuesta) {
				$('#historialModalBody').html(respuesta);
				$('#historialModal').modal('show');
			},
			error: function() {
				alert('Error al cargar el historial');
			}
		});
	});

	var exportPlugin = hot.getPlugin('exportFile');
	var button = document.querySelector('#export-file');

	button.addEventListener('click', () => {
		exportPlugin.downloadFile('csv', {
			bom: false,
			columnDelimiter: ',',
			columnHeaders: false,
			exportHiddenColumns: true,
			exportHiddenRows: true,
			fileExtension: 'csv',
			filename: 'REPORTE COBRANZA',
			mimeType: 'text/csv',
			rowDelimiter: '\r\n',
			rowHeaders: true,
			columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]  // Actualizado para incluir la nueva columna id_gen
		});
	});

</script>