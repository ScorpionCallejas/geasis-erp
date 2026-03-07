<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

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
	$sql = "
	  SELECT *
	  FROM egreso
	  WHERE id_pla13 = '$plantel' AND DATE(fec_egr) BETWEEN '$inicio' AND '$fin'
	";

	$total = obtener_datos_consulta($db, $sql)['total'];

	//echo $sql;

	if ( $total > 0 ) {
		$resultado = mysqli_query($db, $sql);

		while($fila = mysqli_fetch_assoc($resultado)){
			
		    $id_egr = $fila['id_egr'];
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
		 	hiddenColumns: {
	        columns: [0], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
	        indicators: false // Esto oculta el indicador de columnas ocultas
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


	// CONTEOS
	obtenerConteosEstatus(hot);
	// function obtenerConteosEstatus(hot) {
	// 	var sumaMonto = 0;
	// 	var sumaGastoDeposito = 0;
	// 	var columnaMonto = 2; // Asegúrate de que este índice esté alineado con la columna "MONTO" en tu Handsontable
	// 	var columnaFormaGasto = 4; // Asumiendo que "FORMA DE GASTO" es la quinta columna (índice 4)

	// 	var count = 0;

	// 	// Iterar a través de todas las filas
	// 	for (var row = 0; row < hot.countRows(); row++) {
	// 		// Verificar si la fila tiene datos
	// 		if (hot.isEmptyRow(row) === false) {
	// 			count++; // Contar la fila si no está vacía

	// 			// Obtener el valor de la celda actual en la columna "MONTO"
	// 			var valor = hot.getDataAtCell(row, columnaMonto);
	// 			valor = valor.replace(/[\$,]/g, ''); // Limpiar el formato de moneda

	// 			// Sumar al total si el valor es un número
	// 			if (!isNaN(valor) && valor !== null) {
	// 				sumaMonto += parseFloat(valor);
	// 			}

	// 			// Verificar si la forma de gasto es "Gasto en depósito" y sumar al total correspondiente
	// 			var formaGasto = hot.getDataAtCell(row, columnaFormaGasto);
	// 			if (formaGasto === 'Gasto en depósito') {
	// 				sumaGastoDeposito += parseFloat(valor);
	// 			}
	// 		}
	// 	}

	// 	// Actualizar los elementos HTML con los totales
	// 	$('#conteo_total').text(count);
	// 	$('#conteo_egreso').text(sumaMonto.toFixed(2)); // Total de la columna "MONTO"
	// 	$('#total_gasto_deposito').text(sumaGastoDeposito.toFixed(2)); // Total de "Gasto en depósito"
	// }


    function obtenerConteosEstatus(hot) {


		var sumaMonto = 0;
		
		var columnaMonto = 2; 

		// Iterar a través de todas las filas
		for (var row = 0; row < hot.countRows(); row++) {
			// Verificar si la fila tiene datos
			if (hot.isEmptyRow(row) === false) {
				// Obtener el valor de la celda actual en la columna "MONTO"
				var valor = hot.getDataAtCell(row, columnaMonto);

				// Asegurarse de que el valor es un número y sumarlo al total
				if (!isNaN(valor) && valor !== null) {
					sumaMonto += parseFloat(valor);
				}
			}
		}

		// Actualizar el elemento HTML con la suma total
		$('#conteo_egreso').text(sumaMonto.toFixed(2)); 

        var count = 0;

        // Iterar a través de todas las filas
        for (var row = 0; row < hot.countRows(); row++) {
            // Verificar si la fila tiene datos
            if (hot.isEmptyRow(row) === false) {
                count++;
            }
        }

        // Actualiza los elementos HTML con los conteos.
        $('#conteo_total').text(count);
        // $('#conteo_agendadas').text(posiblesEstados['Agendada']);
    }
	// FIN CONTEOS
	
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
	    filename: 'REPORTE EGRESOS',
	    mimeType: 'text/csv',
	    rowDelimiter: '\r\n',
	    rowHeaders: true,
	    columns: [0, 1, 2, 3]  // Aquí especificas las columnas que deseas exportar
	  });
	});



	function adicionarFila(rowData) {

		alert("Datos de la fila: " + JSON.stringify(rowData));

	    // $.ajax({
	    //     type: "POST",
	    //     url: "adicionar_fila.php",
	    //     data: { data: rowData },
	    //     success: function(response) {
	    //         console.log("Fila añadida con éxito!");
	    //     },
	    //     error: function(error) {
	    //         console.log("Hubo un problema al añadir la fila.");
	    //     }
	    // });
	}

	function guardarCelda(hot, row, column, value) {

		var id = hot.getDataAtCell(row, 0); // Obtener el valor de la primera columna (ID) para la fila específica


    	//alert("ID: " + id + "\nFila: " + row + "\nColumna: " + column + "\nValor: " + value);
    	if ( id == "" || id == null || id == undefined ) {
    		// ALTA
    		//alert("no hay datos en esta fila");

    		var accion = "Alta";
    		var campo = obtenerCampoValor( column );
    		var valor = value;

    		// ADD

    		if(valor){

    			$.ajax({
					url: 'server/controlador_egreso.php',
					type: 'POST',
					data: { campo, valor, accion },
					dataType: 'json',
					success: function( data ){
					//success
						console.log( data );
						hot.setDataAtCell(row, 0, data.id_egr);
						// hot.setDataAtCell(row, 1, data.con_egr);
						// hot.setDataAtCell(row, 2, data.mon_egr);
						// hot.setDataAtCell(row, 3, data.cat_egr);
						hot.setDataAtCell(row, 4, data.fec_egr);
						// hot.setDataAtCell(row, 5, data.res_egr);
						// hot.setDataAtCell(row, 6, data.res2_egr);
						if( column != 7 ){
							hot.setDataAtCell(row, 7, data.for_egr);
						}

						obtenerConteosEstatus(hot);
						
						console.log( data.sql );
					}

			    });

    		}

    	} else {

    		// UPDATE
			// console.log('cambio');
    		var accion = "Cambio";
    		var campo = obtenerCampoValor( column );
    		var valor = value;
    		var id_egr = id;

    		$.ajax({
				url: 'server/controlador_egreso.php',
				type: 'POST',
				data: { campo, valor, accion, id_egr },
				dataType: 'json',
				success: function( data ){
				//success

					console.log(data);
					if ( data.resultado == 'false' ) {
						hot.setDataAtCell(row, 0, '');
						hot.setDataAtCell(row, 4, '');
					}

					obtenerConteosEstatus(hot);
				}

		    });

    	}

	    function obtenerCampoValor( column ){

	//			alert("obtenerCampoValor");
				if (column == 1) {
				    columnName = "con_egr";
				} else if (column == 2) {
				    columnName = "mon_egr";
				} else if (column == 3) {
				    columnName = "cat_egr";
				} else if (column == 4) {
				    columnName = "fec_egr";
				} else if (column == 5) {
				    columnName = "res_egr";
				} else if (column == 6) {
				    columnName = "res2_egr";
				} else if (column == 7) {
				    columnName = "for_egr";
				} else if ( column == 8 ) {
					columnName = "obs_egr";
				}

				return columnName;

			}
		}


		// function obtenerCorreoCompuesto( nombre ){

	    //     return $('#cor_eje').val(
	    //         remove_accents( correo.val().trim().replace(' ', '').toLowerCase() ) + '@<?php echo $folioPlantel; ?>.com' 
	    //     );
	    // }
	
</script>