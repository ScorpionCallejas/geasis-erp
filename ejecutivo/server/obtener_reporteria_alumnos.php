<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	// $estatusGeneral = $_POST['estatusGeneral'];
	// $seleccionGrupos = $_POST['seleccionGrupos'];

	$sql = "
		SELECT *,
		obtener_documento_pendiente(id_alu_ram) AS documento_pendiente,
		obtener_actividades_vencidas(id_alu_ram) AS actividades_vencidas
		FROM vista_alumnos
		WHERE ( id_pla8 = '$plantel' ) AND ( ing_alu BETWEEN '$inicio' AND '$fin' )
	";

	// estatusGeneral
	if ( ( isset( $_POST['estatusGeneral'] ) ) && ( sizeof( $_POST['estatusGeneral'] ) > 0 ) ) {

		$estatusGeneral = $_POST['estatusGeneral'];

		$sql .= " AND ";
		for ( $i = 0 ;  $i < sizeof( $estatusGeneral )  ;  $i++ ) { 
		  
			  if ( sizeof( $estatusGeneral ) == 1 ) {
			  
				$sql .= " 
					( estatus_general = '$estatusGeneral[$i]' )
				";

				  break;
				  break;

			} else if ( $i == ( sizeof( $estatusGeneral ) -1 ) ) {

				  $sql .= " 
					estatus_general = '$estatusGeneral[$i]' )
				";
				  

			} else {
				  
				if ( $i == 0 ) {
			  
					$sql .= " ( ";
			  
				  }

				  $sql .= "estatus_general = '$estatusGeneral[$i]' OR ";

			}

		}

	}
	// FIN estatusGeneral

	// seleccionGrupos
	if ( ( isset( $_POST['seleccionGrupos'] ) ) && ( sizeof( $_POST['seleccionGrupos'] ) > 0 ) ) {

		$seleccionGrupos = $_POST['seleccionGrupos'];

		$sql .= " AND ";
		for ( $i = 0 ;  $i < sizeof( $seleccionGrupos )  ;  $i++ ) { 
		  
			  if ( sizeof( $seleccionGrupos ) == 1 ) {
			  
				$sql .= " 
					( id_gen1 = '$seleccionGrupos[$i]' )
				";

				  break;
				  break;

			} else if ( $i == ( sizeof( $seleccionGrupos ) -1 ) ) {

				  $sql .= " 
					id_gen1 = '$seleccionGrupos[$i]' )
				";
				  

			} else {
				  
				if ( $i == 0 ) {
			  
					$sql .= " ( ";
			  
				  }

				  $sql .= "id_gen1 = '$seleccionGrupos[$i]' OR ";

			}

		}

	}
	// FIN seleccionGrupos


	// echo $sql;

?>



<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
	moment.locale('es');

	var container = document.querySelector('#data-sheet');
	var colHeaders = ["MATRICULA", "NOMBRE", "MATRICULA", "TELÉFONOS", "GPO", "ADEUDOS", "ESTATUS", "CORREO", "CONTRASEÑA", "EXPEDIENTE", "ACT VENCIDAS", "PROMEDIO FINAL", "ID", "CARGA"];
	var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", ""]);

<?php
	$total = obtener_datos_consulta($db, $sql)['total'];

	//echo $sql;
	$totales_tipos_pago = array();
	// $totales_tipos_pago['t_colegiatura'] = 0;
	$totales_estatus = [
		'Activo' => 0,
		'Activo/Reingreso' => 0,
		'Certificado' => 0,
		'Fin curso' => 0,
		'NP' => 0,
		'Prospecto' => 0,
		'Registro' => 0,
		'Suspendido' => 0,
		'Baja definitiva' => 0,
		'Bloqueado' => 0,
		'Anticipado' => 0,
	];
	

	if ( $total > 0 ) {

		$resultado = mysqli_query($db, $sql);
		

		while($fila = mysqli_fetch_assoc($resultado)){
			if (isset($totales_estatus[$fila['estatus_general']])) {
				$totales_estatus[$fila['estatus_general']] += 1;
			}

			echo "data.push([
				".json_encode('<a class="btn-link text-primary" target="_blank" href="consulta_alumno.php?id_alu_ram='.$fila['id_alu_ram'].'">'.$fila['id_alu_ram'].'</a>').", 
				".json_encode($fila['nom_alu']).", 
				".json_encode($fila['bol_alu']).", 
				".json_encode($fila['tel_alu'].' / '.$fila['tel2_alu']).", 
				".json_encode($fila['nom_gen']).", 
				".json_encode(formatearDinero($fila['adeudo_alumno'])).", 
				".json_encode($fila['estatus_general']).", 
				".json_encode($fila['cor_alu']).", 
				".json_encode($fila['pas_alu']).", 
				".json_encode($fila['documento_pendiente']).", 
				".json_encode($fila['actividades_vencidas']).", 
				'######',
				".json_encode($fila['id_alu_ram']).", 
				".json_encode($fila['carga_alumno'])."
			]);\n";			
			
		}

		// var_dump( $totales_estatus );

		// echo 'alert( '.$totales_tipos_pago['t_colegiatura'].' )';
		

	} else {
		echo 'data = Array(15).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", ""]);';
	}

	// AQUÍ PEGA LOS CONTEOS:
	foreach ($totales_estatus as $estatus => $conteo) {
        // Convertir el nombre del estatus a su correspondiente ID de elemento en el cliente
        $estatusId = strtolower(str_replace(['/', ' '], ['', '_'], $estatus)); // 'Activo/Reingreso' se convierte en 'activo_reingreso'
        echo "$('#conteo_" . $estatusId . "').text(" . $conteo . ");\n";
    }

	$total_conteo = array_sum($totales_estatus);
	echo "$('#conteo_total').text(" . $total_conteo . ");\n";
	
	
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
            columns: [12, 2], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
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
                renderer: "html"
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            {
                readOnly: true
            },
            
            {
                readOnly: true
            },
		]
	});


	// CONTEOS
	//obtenerConteosEstatus(hot);
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


    // function obtenerConteosEstatus(hot) {

	// 	var sumaMonto = 0;
		
	// 	var columnaMonto = 2; 

	// 	// Iterar a través de todas las filas
	// 	for (var row = 0; row < hot.countRows(); row++) {
	// 		// Verificar si la fila tiene datos
	// 		if (hot.isEmptyRow(row) === false) {
	// 			// Obtener el valor de la celda actual en la columna "MONTO"
	// 			var valor = hot.getDataAtCell(row, columnaMonto);

	// 			// Asegurarse de que el valor es un número y sumarlo al total
	// 			if (!isNaN(valor) && valor !== null) {
	// 				sumaMonto += parseFloat(valor);
	// 			}
	// 		}
	// 	}

	// 	// Actualizar el elemento HTML con la suma total
	// 	$('#conteo_egreso').text(sumaMonto.toFixed(2)); 

    //     var count = 0;

    //     // Iterar a través de todas las filas
    //     for (var row = 0; row < hot.countRows(); row++) {
    //         // Verificar si la fila tiene datos
    //         if (hot.isEmptyRow(row) === false) {
    //             count++;
    //         }
    //     }

    //     // Actualiza los elementos HTML con los conteos.
    //     $('#conteo_total').text(count);
    //     // $('#conteo_agendadas').text(posiblesEstados['Agendada']);
    // }
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
	    filename: 'REPORTE ALUMNOS',
	    mimeType: 'text/csv',
	    rowDelimiter: '\r\n',
	    rowHeaders: true,
	    columns: [0, 1, 2, 3]  // Aquí especificas las columnas que deseas exportar
	  });
	});

</script>