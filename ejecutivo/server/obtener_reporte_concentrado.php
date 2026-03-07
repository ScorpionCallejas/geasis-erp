<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	//fechaDia( $fecha );

?>

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
	moment.locale('es');

	var container = document.querySelector('#data-sheet');
	var colHeaders = ['MES', 'SEMANA', 'COBRANZA EFECTIVO', 'SOBRANTE', 'GASTOS', 'COLEGIATURAS DEPOSITO', 'GASTOS EN DEPOSITO', 'TRAMITES EN DEPOSITO', 'COBRANZA TOTAL', 'TRAMITES EN EFECTIVO'];
	var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", "", ""]);

<?php  
	
	// Convierte las fechas de inicio y fin en marcas de tiempo
	$inicio_ts = strtotime($inicio);
	$fin_ts = strtotime($fin);

	// Función para obtener el nombre del mes en español
	function getSpanishMonth($month) {
		$months = [
			'01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
			'04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
			'07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
			'10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
		];
		return $months[$month];
	}

	// Inicia la semana en la que cae la fecha de inicio
	$semana_actual = date('W', $inicio_ts);

	// Itera desde la fecha de inicio hasta la fecha de fin

	$contador = 0;
	while ($inicio_ts <= $fin_ts) {
		// Obtiene el mes actual en formato 'MM'
		$mes_actual = date('m', $inicio_ts);
		// Obtiene el nombre del mes en español
		$nombre_mes = getSpanishMonth($mes_actual);

		// Encuentra los días de inicio y fin de la semana actual
		$dia_inicio_semana = date('Y-m-d', $inicio_ts);
		
		if( $contador != 0 ){
			$dia_inicio_semana = sumarDias( $dia_fin_semana, 1 );
		}
		
		$dia_fin_semana = date('Y-m-d', strtotime('sunday this week', $inicio_ts));

		

		// Imprime el mes y la semana
		// echo $nombre_mes . ' ' . $semana_actual . '<br>';
		$diferencia = obtenerDiferenciaFechas(  $fin, $dia_inicio_semana );

		if( $diferencia < 8 ){
			$dia_fin_semana = $fin;
		}

		/// CÓDIGO
		// $mes = json_encode('diferencia: '.$diferencia.' -- '.$nombre_mes);

		$mes = json_encode($nombre_mes);

		$semana_actual = $semana_actual.'--->'.fechaFormateadaCompacta2($dia_inicio_semana).'-'.fechaFormateadaCompacta2($dia_fin_semana);
		$semana = json_encode($semana_actual);
		
		$sql = "
			SELECT obtener_abonado_efectivo_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$cobranza_efectivo = obtener_datos_consulta( $db, $sql )['datos']['total'];

		$sql = "
			SELECT obtener_egreso_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$gastos = obtener_datos_consulta( $db, $sql )['datos']['total'];

		$sobrante = $cobranza_efectivo - $gastos;
		

		$cobranza_efectivo = json_encode( formatearDinero($cobranza_efectivo) );

		$sobrante = json_encode( formatearDinero($sobrante) );
		$gastos = json_encode( formatearDinero($gastos));

		$sql = "
			SELECT obtener_abonado_colegiatura_deposito_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$colegiaturas_deposito = obtener_datos_consulta( $db, $sql )['datos']['total'];
		$colegiaturas_deposito = json_encode( formatearDinero($colegiaturas_deposito) );

		$sql = "
			SELECT obtener_egreso_deposito_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$gastos_deposito = obtener_datos_consulta( $db, $sql )['datos']['total'];
		$gastos_deposito = json_encode( formatearDinero($gastos_deposito));

		$sql = "
			SELECT obtener_abonado_tramite_deposito_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$tramites_deposito = obtener_datos_consulta( $db, $sql )['datos']['total'];
		$tramites_deposito = json_encode( formatearDinero($tramites_deposito));

		$sql = "
			SELECT obtener_abonado_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$cobranza_total = obtener_datos_consulta( $db, $sql )['datos']['total'];
		$cobranza_total = json_encode( formatearDinero($cobranza_total));

		$sql = "
			SELECT obtener_abonado_tramite_efectivo_plantel($plantel, '$dia_inicio_semana', '$dia_fin_semana') AS total;
		";
		$tramites_efectivo = obtener_datos_consulta( $db, $sql )['datos']['total'];
		$tramites_efectivo = json_encode( formatearDinero($tramites_efectivo));

		echo "data.push([$mes, $semana, $cobranza_efectivo, $sobrante, $gastos, $colegiaturas_deposito, $gastos_deposito, $tramites_deposito, $cobranza_total, $tramites_efectivo ]);\n";

		///FIN CODIGO


		// Añade 7 días a la fecha de inicio actual
		$inicio_ts = strtotime('+1 week', $inicio_ts);
		// Actualiza el número de semana
		$semana_actual = date('W', $inicio_ts);

		$contador++;
	}
	
		
	

	
?>
	// FUNCION QUE CAMBIAR COLOR DE COLUMNA
	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
	  	td.style.backgroundColor = '#17202A'; // Cambia el color de fondo
	}

	var hot;  // Declaración al inicio del script o función

	if (hot) {
	    hot.destroy();
	}

	hot = new Handsontable(container, {

		language: 'es-MX',
		data,
		height: 'auto',
	
		stretchH: 'all',
		colHeaders: colHeaders,
		rowHeaders: true,

		manualColumnResize: true,
		minRows: 20,
    	minSpareRows: 1,
		licenseKey: 'non-commercial-and-evaluation',
		// afterChange: function(changes, source) {
        //     if (source === 'loadData' || source === 'populateFromArray') {
        //         // Si la fuente es loadData o populateFromArray, no hacer nada
        //         return;
        //     }
        //     if (changes) {
        //         changes.forEach(([row, prop, oldValue, newValue]) => {
        //             // Ignora los cambios en las columnas 0 y 2
        //             // POR EJEMPLO ID Y FEC CAPTURA QUE SON DATOS FIJOS
        //             if (prop === 0) {
        //                 return;
        //             }

        //             if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
        //                 // Si es una fila nueva
        //                 let rowData = hot.getDataAtRow(row);
        //                 adicionarFila(rowData);
        //             } else {
        //                 // Si es una fila existente
        //                 guardarCelda(hot, row, prop, newValue);
        //             }
        //         });
        //     }
        // },

		filters: true,
		dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
		height: 'auto',
		columnSorting: true,

		contextMenu: {
		    items: {
		        "row_above": {
		            name: 'Insertar fila arriba',
		            disabled: function() {
		                // Deshabilitar cuando la primera fila está seleccionada
		                return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
		            }
		        },
		        "row_below": {
		            name: 'Insertar fila debajo'
		        }
		    }
		},

		columns: [
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
			{readOnly: true,},
		]
	});
	
	container.classList.add('dark-mode');

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
	    filename: 'REPORTE CONCENTRADO',
	    mimeType: 'text/csv',
	    rowDelimiter: '\r\n',
	    rowHeaders: true,
	  });
	});

	
</script>