<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	$id_eje = $_POST['id_eje'];

	//fechaDia( $fecha );

?>

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
	moment.locale('es');

	var container = document.querySelector('#data-sheet');
	var colHeaders = ['ID', 'NOMBRE', 'FECHA CAP.', 'FEC. CITA', 'ESTATUS', 'TELÉFONO', 'OBSERVACIONES', 'ID_EJE'];
	var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", ""]);;;  // Declaración al inicio del script o función
	
<?php  
	$sql = "
	  SELECT *,
	  (IF((SELECT COUNT(id_eje) FROM ejecutivo WHERE id_can1 = id_can) > 0, 'true', 'false')) AS res
	  FROM candidato
	  WHERE id_eje = '$id_eje' AND DATE(fec_reg_can) BETWEEN '$inicio' AND '$fin'
	";

	$total = obtener_datos_consulta($db, $sql)['total'];

	//echo $sql;

	if ( $total != 0 ) {
		$resultado = mysqli_query($db, $sql);

		while($fila = mysqli_fetch_assoc($resultado)){
		    $id_can = $fila['id_can'];
		    $nom_can = json_encode($fila['nom_can']);
		    $fec_reg_can = json_encode(fechaFormateadaCompacta($fila['fec_reg_can']));
		    $fec_ent_can = json_encode(fechaFormateadaCompacta($fila['fec_ent_can']));
		    $est_can = json_encode($fila['est_can']);
		    $tel_can = isset($fila['tel_can']) ? json_encode($fila['tel_can']) : 'null';
		    $obs_can = isset($fila['obs_can']) ? json_encode($fila['obs_can']) : 'null';
		    $res = json_encode($fila['res']);
		    echo "data.push([$id_can, $nom_can, $fec_reg_can, $fec_ent_can, $est_can, $tel_can, $obs_can, $res]);\n";
		}

	} else {
		echo 'data = Array(15).fill(0).map(() => ["", "", "", "", "", "", "", ""]);';
	}
	
?>
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

		cells: function deshabilitarFila(row, col) {
		    var cellProperties = {};

		    // Asumiendo que el estado está en la columna 'ESTATUS'
		    if (this.instance.getDataAtRowProp(row, 4) === 'Regreso' && this.instance.getDataAtRowProp(row, 7) == 'true') {
		      cellProperties.readOnly = true;
		    }

		    if ( col === 0 || col === 2 ) { // Verifica si es la primera columna
		      cellProperties.renderer = firstColumnRenderer; // Usa un renderizador personalizado
		    }

		    return cellProperties;
		},
		
		height: 'auto',
		width: '100%',
		 	hiddenColumns: {
	        columns: [7], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
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
                    if (prop === 0 || prop === 2) {
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
			},
			{
			    type: 'date',
			    dateFormat: 'DD/MM/YYYY',
			    correctFormat: true,
			    datePickerConfig: {
			        // First day of the week (0: Sunday, 1: Monday, etc)
			        firstDay: 0,
			        showWeekNumber: true
			        // Se ha eliminado la función disableDayFn
			    }
			},

			{
				type: 'dropdown',
				source: ['Informes', 'Citado', 'Entrevistado', 'Proceso', 'Regreso']  // Estatus que vi en la imagen
			},
			{},   // Configuración por defecto para Teléfono
			{},   // Configuración por defecto para obs
			{ readOnly: true } 
		]
	});


	obtenerConteosEstatus( hot );
	function obtenerConteosEstatus( hot ){
		//console.log('conteos estatus');

		// Define los posibles estados.
		var posiblesEstados = {
		  'Informes': 0,
		  'Citado': 0,
		  'Entrevistado': 0,
		  'Proceso': 0,
		  'Regreso': 0
		};

		// Obtén todos los datos de la columna con índice 10.
		var statusColumnData = hot.getDataAtCol(4);

		// Cuenta las ocurrencias de cada estado.
		statusColumnData.forEach(function(status) {
		  if (status && posiblesEstados.hasOwnProperty(status)) {
		    posiblesEstados[status]++;
		  }
		});

		var conteoTotal = Object.values(posiblesEstados).reduce(function (total, currentValue) {
		  return total + currentValue;
		}, 0);

		// Actualiza los elementos HTML con los conteos.
		$('#conteo_total').text(conteoTotal);
		$('#conteo_informes').text(posiblesEstados['Informes']);
		$('#conteo_citados').text(posiblesEstados['Citado']);
		$('#conteo_entrevistados').text(posiblesEstados['Entrevistado']);
		$('#conteo_procesos').text(posiblesEstados['Proceso']);
		$('#conteo_regresos').text(posiblesEstados['Regreso']);

		//obtener_totales_historicos( <?php echo $id_eje; ?> );
	}


	
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
	    filename: 'Reporte PAC',
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
		var nombre = hot.getDataAtCell(row, 1); 
    	var telefono = hot.getDataAtCell(row, 5);

    	//alert("ID: " + id + "\nFila: " + row + "\nColumna: " + column + "\nValor: " + value);
    	if ( id == "" || id == null || id == undefined ) {
    		// ALTA
    		//alert("no hay datos en esta fila");

    		var accion = "Alta";
    		var campo = obtenerCampoValor( column );
    		var valor = value;

    		// ADD
    		var id_eje = $('#selector_ejecutivo option:selected').val();

    		if(valor){

    			$.ajax({
					url: 'server/controlador_candidato.php',
					type: 'POST',
					data: { campo, valor, accion, id_eje },
					dataType: 'json',
					success: function( data ){
					//success
						console.log( data );
						hot.setDataAtCell(row, 0, data.id_can);
						//hot.setDataAtCell(row, 1, data.nom_can);
						hot.setDataAtCell(row, 2, data.fec_reg_can);
						hot.setDataAtCell(row, 3, data.fec_ent_can);
						hot.setDataAtCell(row, 4, data.est_can);
						// hot.setDataAtCell(row, 5, data.tel_can);
						// hot.setDataAtCell(row, 6, data.obs_can);

						obtenerConteosEstatus( hot );
						//alert( data.sql );
					}

			    });

    		}

    	} else {

    		// UPDATE
    		var accion = "Cambio";
    		var campo = obtenerCampoValor( column );
    		var valor = value;
    		var id_can = id;

    		$.ajax({
				url: 'server/controlador_candidato.php',
				type: 'POST',
				data: { campo, valor, accion, id_can },
				dataType: 'json',
				success: function( data ){
				//success
					//alert(data);
					//alert(campo);
					// EJECUTIVO
					if ( campo == 'est_can' && valor == 'Regreso' ) {

						//alert('ENTRE');
		    			$('#modal_agregar_asesor').modal('show');

		    			$('#id_can').val( id_can );

		    			//$('#id_can').val(id);
		    			$('#nom_eje').val(nombre);
		    			$('#tel_eje').val(telefono);

		    			//bloquearFila( hot, row );
		    			
			    	}
					// FIN EJECUTIVO

					console.log(data);
					if ( data.resultado == 'false' ) {
						hot.setDataAtCell(row, 0, '');
						hot.setDataAtCell(row, 2, '');
					}

					obtenerConteosEstatus( hot );
					// console.log(data.resultado);
				}

		    });

    	}

	    function obtenerCampoValor( column ){

	//			alert("obtenerCampoValor");
				if (column == 1) {
				    columnName = "nom_can";
				} else if (column == 2) {
				    columnName = "fec_reg_can";
				} else if (column == 3) {
				    columnName = "fec_ent_can";
				} else if (column == 4) {
				    columnName = "est_can";
				} else if (column == 5) {
				    columnName = "tel_can";
				} else if (column == 6) {
				    columnName = "obs_can";
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