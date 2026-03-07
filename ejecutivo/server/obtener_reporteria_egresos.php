<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	if( isset( $_POST['tipoEgreso'] ) ){
		$tipoEgreso = $_POST['tipoEgreso'];
	}

	$sql = "
		SELECT *
		FROM egreso
		WHERE id_pla13 = '$plantel' AND ( DATE(fec_egr) BETWEEN '$inicio' AND '$fin' )
	";

	// tipoEgreso
	if ( ( isset( $_POST['tipoEgreso'] ) ) && ( sizeof( $_POST['tipoEgreso'] ) > 0 ) ) {

		$tipoEgreso = $_POST['tipoEgreso'];

		$sql .= " AND ";
		for ( $i = 0 ;  $i < sizeof( $tipoEgreso )  ;  $i++ ) { 
		  
			  if ( sizeof( $tipoEgreso ) == 1 ) {
			  
				$sql .= " 
					( for_egr = '$tipoEgreso[$i]' )
				";

				  break;
				  break;

			} else if ( $i == ( sizeof( $tipoEgreso ) -1 ) ) {

				  $sql .= " 
					for_egr = '$tipoEgreso[$i]' )
				";
				  

			} else {
				  
				if ( $i == 0 ) {
			  
					$sql .= " ( ";
			  
				  }

				  $sql .= "for_egr = '$tipoEgreso[$i]' OR ";

			}

		}

	}
	// FIN tipoEgreso

	// echo $sql;

?>


<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
	moment.locale('es');

	var container = document.querySelector('#data-sheet');
	var colHeaders = ['ID', 'CONCEPTO', 'MONTO', 'CATEGORIA', 'FECHA', 'AUTORIZA', 'SOLICITA', 'FORMA DE GASTO', 'OBSERVACIONES'];
	var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", ""]);

<?php
	$total = obtener_datos_consulta($db, $sql)['total'];
	$totales_tipos_pago = array();
	$totales_tipos_pago['t_deposito'] = 0;
	$totales_tipos_pago['t_cuenta'] = 0;
	$totales_tipos_pago['t_efectivo'] = 0;
	$totales_tipos_pago['t_otros'] = 0;


	if ( $total > 0 ) {
		$resultado = mysqli_query($db, $sql);

		while($fila = mysqli_fetch_assoc($resultado)){
			

			if( $fila['for_egr'] == 'Gasto en depósito' ){
				$totales_tipos_pago['t_deposito'] += $fila['mon_egr'];

			} else if ( $fila['for_egr'] == 'Gasto en cuenta empresarial' ){ 
				$totales_tipos_pago['t_cuenta'] += $fila['mon_egr'];
			
			} else if( $fila['for_egr'] == 'Gasto en efectivo' ){
				$totales_tipos_pago['t_efectivo'] += $fila['mon_egr'];
			
			} else if ( $fila['for_egr'] == 'Otros' ){
				$totales_tipos_pago['t_otros'] += $fila['mon_egr'];
			
			}
		    $id_egr = json_encode($fila['id_egr']);
		    $con_egr = json_encode($fila['con_egr']);
		    $mon_egr = json_encode($fila['mon_egr']);
			// $mon_egr = json_encode(formatearDinero($fila['mon_egr']));
			$cat_egr = json_encode($fila['cat_egr']);
		    $fec_egr= json_encode(fechaFormateadaCompacta($fila['fec_egr']));
		    $res_egr = json_encode($fila['res_egr']);
			$res2_egr = json_encode($fila['res2_egr']);
			$for_egr = json_encode($fila['for_egr']);
			$obs_egr = json_encode($fila['obs_egr']);
		    echo "data.push([$id_egr, $con_egr, $mon_egr, $cat_egr, $fec_egr, $res_egr, $res2_egr, $for_egr, $obs_egr]);\n";
		}

	} else {
		echo 'data = Array(15).fill(0).map(() => ["", "", "", "", "", "", "", "", ""]);';
	}

	echo '$("#sumatoria_deposito").text( "'.formatearDinero( $totales_tipos_pago['t_deposito'] ).'" );';
	echo '$("#sumatoria_cuenta").text( "'.formatearDinero( $totales_tipos_pago['t_cuenta'] ).'" );';
	echo '$("#sumatoria_efectivo").text( "'.formatearDinero($totales_tipos_pago['t_efectivo']).'" );';
	echo '$("#sumatoria_otros").text( "'.formatearDinero($totales_tipos_pago['t_otros']).'" );';
	
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
		width: '100%',
		// hiddenColumns: {
	    //     columns: [0], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
	    //     indicators: false // Esto oculta el indicador de columnas ocultas
	    // },
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
			{
				readOnly: true,
			},  // Configuración por defecto para ID

			{},  // Configuración por defecto para Nombre
			
			{
				type: 'numeric',
			},  

			{
				type: 'dropdown',
				source: ['ADDS (VTA DIGITAL)','ARRENDAMIENTO','BAJA DE COLABORADOR','CARGA SOCIAL','CERTIFICACIÓN','COMMUNITY MANAGER','COMPETENCIAS E INCENTIVOS','COMPRA DE EQUIPO DE COMPUTO','COMPRA DE MOBILIARIO','CONTADOR','DEPÓSITOS EN CTA EMPRESARIAL','EVENTOS','FONDEO CAJA CHICA','GASTO OPERATIVO BACHILLERATO','GASTO OPERATIVO DIPLOMADOS','GASTO OPERATIVO UDS','GASTO OPERATIVO UPAV','GASTOS BANCARIOS','GASTOS DE CORPORATIVO','IMPUESTOS','INSUMOS COLABORADORES CDE','INVERSIÓN EN RVOE´S','JURÍDICO','LICENCIAS DE USO (SOFTWARE)','LIMPIEZA E HIGIENE','MANTENIMIENTO Y REMODELACIÓN','NÓMINA ADMINISTRATIVA','NÓMINA COMERCIAL','NÓMINA CORPORATIVO','NÓMINA DOCENTE','NÓMINA QUINCENAL','NÓMINA REINGRESO','PAGO PRÉSTAMO TRÁMITES','PAPELERIA Y PUBLICIDAD','PERMISOS Y LECENCIAS DE OPERACIÓN','PRÉSTAMO A COLABORADORES','REGALIAS DCK','SERVICIO DE AGUA','SERVICIO DE LUZ','SERVICIO DE TELEFONIA E INTERNET','SERVICIOS DE SEGURIDAD Y CÁMARAS','SERVIDOR Y PLATAFORMA','SIN CATEGORIZAR','SISTEMA INTERNO DCK','SOCIOS ENDE']
			},
			{
				// TIPO FECHA
				readOnly: true,
			    type: 'date',
			    dateFormat: 'DD/MM/YYYY',
			    correctFormat: true,
			    datePickerConfig: {
			        // First day of the week (0: Sunday, 1: Monday, etc)
			        firstDay: 0,
			        showWeekNumber: true
			        // Se ha eliminado la función disableDayFn
			    }
			},  // Configuración por defecto para Teléfono
			{},   // Configuración por defecto para obs
			{},
			

			{
				type: 'dropdown',
				source: ['Gasto en depósito', 'Gasto en cuenta empresarial', 'Gasto en efectivo', 'Otro']
			},
			{} 
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
	    filename: 'REPORTE COBRANZA',
	    mimeType: 'text/csv',
	    rowDelimiter: '\r\n',
	    rowHeaders: true,
	    columns: [0, 1, 2, 3]  // Aquí especificas las columnas que deseas exportar
	  });
	});

</script>