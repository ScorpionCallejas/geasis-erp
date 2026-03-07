<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	$id_eje = $_POST['id_eje'];
	$escala = $_POST['escala'];
?>

<!-- MAIN SQL -->
<?php 
	$data = [];

	
	if( $rangoUsuario == 'GC' ){
		// echo 'condicion';
		$sql = "
			SELECT DISTINCT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con, contacto.fec_con, 
			ejecutivo.nom_eje,
			ejecutivo_asignado.nom_eje AS nom_ejecutivo_asignado,
			contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
			(SELECT nom_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS nom_cit,
			(SELECT cla_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS cla_cit,
			(SELECT obtener_registro_por_cita(id_cit) FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS es_registro,
			IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
			FROM contacto
			INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
			LEFT JOIN ejecutivo AS ejecutivo_asignado ON ejecutivo_asignado.id_eje = contacto.id_ejecutivo
			WHERE contacto.cla_con = 'Referido' AND contacto.id_eje10 = '$id' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin'
		";


		$data = obtener_tabla_estructura_referidos($id, $inicio, $fin, $db, $data);	

	} else {
		// ---------------
		if ( $id_eje == 'Todos' ) {
			$sqlPlanteles = "
				SELECT *
				FROM planteles_ejecutivo
				INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
				WHERE id_eje = '$id'
			";
	
			$totalValidacion = obtener_datos_consulta( $db, $sqlPlanteles )['total'];
	
			if( $totalValidacion == 0 ){
				$sql = "
					SELECT DISTINCT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con, contacto.fec_con, 
					ejecutivo.nom_eje,
					ejecutivo_asignado.nom_eje AS nom_ejecutivo_asignado,
					contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
					(SELECT nom_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS nom_cit,
					(SELECT cla_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS cla_cit,
					(SELECT obtener_registro_por_cita(id_cit) FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS es_registro,
					IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
					FROM contacto
					INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
					LEFT JOIN ejecutivo AS ejecutivo_asignado ON ejecutivo_asignado.id_eje = contacto.id_ejecutivo
					WHERE contacto.cla_con = 'Referido' AND contacto.id_pla10 = '$plantel' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin'
				";
			} else {
				$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
	
				$contador = 0;
				$sqlEjecutivos = '';
				while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
					if ($contador > 0) {
						$sqlEjecutivos .= ' OR ';
					}
					$sqlEjecutivos .= 'ejecutivo.id_pla = '.$filaPlanteles['id_pla'];
					$contador++;
				}
	
				$sqlEjecutivos = $sqlEjecutivos." ) ";
	
				$sql = "
					SELECT DISTINCT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con, contacto.fec_con, 
					ejecutivo.nom_eje,
					ejecutivo_asignado.nom_eje AS nom_ejecutivo_asignado,
					contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
					(SELECT nom_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS nom_cit,
					(SELECT cla_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS cla_cit,
					(SELECT obtener_registro_por_cita(id_cit) FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS es_registro,
					IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
					FROM contacto
					INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
					LEFT JOIN ejecutivo AS ejecutivo_asignado ON ejecutivo_asignado.id_eje = contacto.id_ejecutivo
					WHERE contacto.cla_con = 'Referido' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin' AND (
				";
				$sql .= $sqlEjecutivos;
			}
	
		} else {
			$sql = "
				SELECT DISTINCT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con, contacto.fec_con, 
				ejecutivo.nom_eje,
				ejecutivo_asignado.nom_eje AS nom_ejecutivo_asignado,
				contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
				(SELECT nom_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS nom_cit,
				(SELECT cla_cit FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS cla_cit,
				(SELECT obtener_registro_por_cita(id_cit) FROM cita WHERE id_con2 = contacto.id_con LIMIT 1) AS es_registro,
				IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
				FROM contacto
				INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
				LEFT JOIN ejecutivo AS ejecutivo_asignado ON ejecutivo_asignado.id_eje = contacto.id_ejecutivo
				WHERE contacto.cla_con = 'Referido' AND contacto.id_eje10 = '$id_eje' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin'
			";
		}
		// F -------------
	}
	


	// echo $sql;
?>
<!-- F MAIN SQL -->

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
	
	moment.locale('es');

	// VERIFICAR SI YA EXISTE ANTES DE DECLARAR
	if (typeof statusColorConfig === 'undefined') {
		// CONFIGURACIÓN DE COLORES PARA ESTATUS
		var statusColorConfig = {
			'Registro': { color: '#00FFFF', textColor: '#000000' },
			'Cita': { color: '#FF9800', textColor: '#FFFFFF' },
			'Cita no atendida': { color: '#FF6666', textColor: '#FFFFFF' },
			'Cita atendida': { color: '#00FF00', textColor: '#000000' },
			'Pendiente': { color: '#FFC107', textColor: '#000000' },
			'No respondio': { color: '#6C757D', textColor: '#FFFFFF' },
			'No se cerró': { color: '#DC3545', textColor: '#FFFFFF' },
			'Contactado': { color: '#28A745', textColor: '#FFFFFF' }
		};
	}

	// RENDERER PARA COLORES DE ESTATUS
	if (typeof statusColumnRenderer === 'undefined') {
		function statusColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
			Handsontable.renderers.TextRenderer.apply(this, arguments);
			
			if (value && statusColorConfig[value]) {
				td.style.backgroundColor = statusColorConfig[value].color;
				td.style.color = statusColorConfig[value].textColor;
				td.style.textAlign = 'center';
			}
		}
	}

	// RENDERER PARA COLORES DE ESTATUS
	function statusColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		
		if (value && statusColorConfig[value]) {
			td.style.backgroundColor = statusColorConfig[value].color;
			td.style.color = statusColorConfig[value].textColor;
			td.style.textAlign = 'center';
		}
	}

	var container = document.querySelector('#data-sheet');
	var colHeaders = ['FECHA', 'EJECUTIVO', 'CONSULTOR', 'NOMBRE', 'NÚMERO', 'MERCADO', 'PRODUCTO DE INTERES', 'ESTATUS', 'OBSERVACIONES', 'BOOL', 'ID_CIT'];
	var data = Array(0).fill(0).map(() => [ "", "", "", "", "", "", "", "", "", "", "" ]);
	
	// DROPDOWN EJECUTIVOS
	<?php 
		$sqlEjecutivo = "
			SELECT *
			FROM ejecutivo
			INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
			INNER JOIN cadena ON cadena.id_cad = plantel.id_cad1
			WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311 AND ran_eje = 'GR' AND id_cad1 = $cadena
			ORDER BY nom_eje ASC
		";
	?>
	var dropdownEjecutivo = [
		<?php
		$resultadoEjecutivo = mysqli_query($db, $sqlEjecutivo);

		while ($filaEjecutivo = mysqli_fetch_assoc($resultadoEjecutivo)) {
			echo '{ label: "' . $filaEjecutivo['nom_eje'] . '", value: ' . $filaEjecutivo['id_eje'] . ' },';
		}
		?>
	];
	// F DROPDOWN EJECUTIVOS 

	<?php	


	$resultado = mysqli_query($db, $sql);
	while ($fila = mysqli_fetch_assoc($resultado)) {
		$data[] = $fila;
	}

	if (sizeof($data) > 0) {
		echo 'data = [';
		foreach ($data as $fila) {
			$fec_con = json_encode(fechaFormateadaCompacta($fila['fec_con']));
			$nom_ejecutivo_asignado = isset($fila['nom_ejecutivo_asignado']) ? json_encode($fila['nom_ejecutivo_asignado']) : 'null';
			$nom_eje = isset($fila['nom_eje']) ? json_encode($fila['nom_eje']) : 'null';
			$nom_con = isset($fila['nom_con']) ? json_encode($fila['nom_con']) : 'null';
			$tel_con = isset($fila['tel_con']) ? json_encode($fila['tel_con']) : 'null';
			$can_con = json_encode($fila['can_con']);
			$pro_con = json_encode($fila['pro_con']);

			if( $fila['es_registro'] == 1 && $fila['cla_cit'] == 'Referido' ){
				$est_con = json_encode($fila['est_con']);
			} else if( $fila['es_registro'] == 0 && $fila['cla_cit'] == 'Referido' ){
				$est_con = json_encode('Pendiente');
			} else {
				$est_con = json_encode($fila['est_con']);
			}
			
			$obs_con = isset($fila['obs_con']) ? json_encode($fila['obs_con']) : 'null';
			$res = json_encode($fila['res']);
			$id_con = json_encode($fila['id_con']);

			echo "[$fec_con, $nom_ejecutivo_asignado, $nom_eje, $nom_con, $tel_con, $can_con, $pro_con, $est_con, $obs_con, $res, $id_con],\n";
		}
		echo '];';
	} else {
		echo 'data = Array(15).fill(0).map(() => [ "", "", "", "", "", "", "", "", "", "", "" ]);';
	}

?>

	var hot;

	if (hot) {
	    hot.destroy();
	}

	// VALIDACIÓN DE TELÉFONO
	function validarTelefonoMX(telefono) {
		// Limpiar espacios, guiones, paréntesis, tabs, etc.
		const limpio = telefono.replace(/[\s\-\(\)\t]/g, '');
		
		// Patrón 1: 10 dígitos (formato nacional)
		const nacional = /^[1-9]\d{9}$/;
		
		// Patrón 2: +52 + 10 dígitos (formato internacional)
		const internacional = /^\+52[1-9]\d{9}$/;
		
		return {
			valido: nacional.test(limpio) || internacional.test(limpio),
			numeroLimpio: limpio.replace(/^\+52/, ''), // Siempre guardar sin +52
			formato: limpio
		};
	}

	function validarTelefonoEnBD(telefono, row, column, hot) {
		console.log('=== INICIANDO VALIDACIÓN EN BD ===');
		console.log('Teléfono:', telefono, 'Row:', row, 'Column:', column);
		
		// Deshabilitar celda temporalmente
		console.log('Deshabilitando celda...');
		hot.setCellMeta(row, column, 'readOnly', true);
		hot.render();

		console.log('Enviando AJAX...');
		$.ajax({
			url: 'server/controlador_referido.php',
			type: 'POST',
			data: { 
				accion: 'ValidarTelefono',
				telefono: telefono
			},
			dataType: 'json',
			beforeSend: function() {
				console.log('AJAX: beforeSend');
			},
			success: function(response) {
				console.log('AJAX SUCCESS:', response);
				
				// Re-habilitar celda
				hot.setCellMeta(row, column, 'readOnly', false);
				
				if (response.disponible) {
					console.log('TELÉFONO DISPONIBLE - Continuando...');
					// Teléfono disponible - continuar normalmente
					hot.render();
					
					// Ahora sí llamar guardarCelda
					console.log('Llamando guardarCelda después de validación exitosa...');
					guardarCelda(hot, row, column, telefono);
				} else {
					console.log('TELÉFONO OCUPADO - Mostrando error');
					// Teléfono ocupado - mantener focus y mostrar error
					toastr.error('Este número ya está registrado en el sistema');
					
					// Limpiar la celda y mantener focus
					hot.setDataAtCell(row, column, '');
					hot.selectCell(row, column);
					hot.render();
				}
			},
			error: function(xhr, status, error) {
				console.log('AJAX ERROR:', status, error);
				console.log('Response text:', xhr.responseText);
				
				// Re-habilitar celda en caso de error
				hot.setCellMeta(row, column, 'readOnly', false);
				toastr.error('Error al validar el teléfono: ' + error);
				hot.render();
			}
		});
	}

	// RENDERER PARA COLUMNAS READ-ONLY (LAS PINTA DE GRIS)
	function readOnlyRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#E3E6E7';
	}

	function citaRowRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#E3E6E7';
		td.style.color = '#999';
	}

	function registroRowRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#00FFFF';
		td.style.color = '#666';
	}

	hot = new Handsontable(container, {
		language: 'es-MX',
		data,
		
		hiddenColumns: {
	        columns: [9,10],
	        indicators: false
	    },
		stretchH: 'all',
		colHeaders: colHeaders,
		
		rowHeaders: true,
		autoWrapRow: true,
		autoWrapCol: true,

		fixedRowsTop: 0,
      	fixedColumnsLeft: 0,

		width: '100%',
        height: 500,

		manualColumnResize: true,
		minRows: 20,
    	minSpareRows: 1,
		licenseKey: 'non-commercial-and-evaluation',
		afterChange: function(changes, source) {
        console.log('AFTERCHANGE TRIGGERED:', changes, source);
        
        if (source === 'loadData' || source === 'populateFromArray') {
            console.log('IGNORANDO CAMBIO POR SOURCE:', source);
            return;
        }
        if (changes) {
            changes.forEach(([row, prop, oldValue, newValue]) => {
                console.log('PROCESANDO CAMBIO:', {row, prop, oldValue, newValue});
                
                // MODIFICACIÓN: Agregar validación para columna 1 cuando $rangoUsuario == 'GR'
                <?php if ($rangoUsuario == 'GR'): ?>
                if (prop === 0 || prop === 1 || prop === 2 || prop === 10) {
                    console.log('IGNORANDO COLUMNA READONLY:', prop);
                    return;
                }
                <?php else: ?>
                if (prop === 0 || prop === 2 || prop === 10) {
                    console.log('IGNORANDO COLUMNA READONLY:', prop);
                    return;
                }
                <?php endif; ?>

				// VALIDACIÓN ESPECIAL PARA TELÉFONO (columna 4)
				if (prop === 4 && newValue && newValue.trim() !== '') {
					console.log('VALIDANDO TELÉFONO:', newValue);
					
					const validacion = validarTelefonoMX(newValue.trim());
					console.log('RESULTADO VALIDACIÓN REGEX:', validacion);
					
					if (validacion.valido) {
						console.log('TELÉFONO VÁLIDO - No se cambia valor para evitar loop');
						// NO CAMBIAR EL VALOR AQUÍ - evita el loop infinito
						console.log('INICIANDO VALIDACIÓN EN BD...');
						// Validar en BD
						validarTelefonoEnBD(validacion.numeroLimpio, row, prop, hot);
						return; // No continuar con guardarCelda hasta que termine validación
					} else {
						console.log('TELÉFONO INVÁLIDO');
						toastr.error('Formato de teléfono inválido. Use 10 dígitos o +52 seguido de 10 dígitos');
						// Usar setTimeout para evitar loop
						setTimeout(() => {
							hot.setDataAtCell(row, prop, '');
							hot.selectCell(row, prop);
						}, 0);
						return;
					}
				}

                if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
                    console.log('FILA NUEVA DETECTADA');
                    let rowData = hot.getDataAtRow(row);
                    adicionarFila(rowData);
                } else {
                    console.log('LLAMANDO GUARDAR CELDA');
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
		        }
		    }
		},

		columns: [
			{
				readOnly: true,
				renderer: readOnlyRenderer,
			    type: 'date',
			    dateFormat: 'DD/MM/YYYY',
			    correctFormat: true,
			    datePickerConfig: {
			        firstDay: 0,
			        showWeekNumber: true
			    }
			},

			// MODIFICACIÓN: Configuración condicional para columna EJECUTIVO
			<?php if ($rangoUsuario == 'GR'): ?>
			{
				readOnly: true,
				renderer: readOnlyRenderer
			},
			<?php else: ?>
			{
				type: 'dropdown',
				source: dropdownEjecutivo.map(item => item.label)
			},
			<?php endif; ?>

			{ 
				readOnly: true,
				renderer: readOnlyRenderer
			},

			{},
			{}, // TELÉFONO - sin validador aquí porque se maneja en afterChange
			
			{ 
				type: 'dropdown',
				source: ["Facebook", 'FANPAGE', "PAUTA ORGÁNICA", "PAUTA AHJ", "TIKTOK", "Mercado natural", "Mercado frío", "Referidos", "Rezagados", "Módulo", "Re matriculación", "Volantes", "Marketing", "PP"]
			},

			{
				type: 'dropdown',
				source: ['PREPA-6-MESES', 'PREPA-EMPRENDE', 'DIPLOMADO', 'LICENCIATURA', 'BACH-NEGOCIOS', 'SEMINARIO-LICENCIATURA']
			},

			{
				type: 'dropdown',
				source: ['Pendiente', 'No respondio', 'No se cerró', 'Cita no atendida', 'Cita atendida', 'Contactado'],
				renderer: statusColumnRenderer
			},

			{},
			{ 
				readOnly: true,
				renderer: readOnlyRenderer
			},
			{ 
				readOnly: true,
				renderer: readOnlyRenderer
			},
		]
	});

	function obtenerCoordenadasPrimeraCeldaSeleccionada( hot ) {
		var selectedRange = hot.getSelected();
		if (selectedRange) {
			var fila = selectedRange.start.row;
			var columna = selectedRange.start.col;
			console.log("Primera celda seleccionada: Fila", fila, ", Columna", columna);
			return [fila, columna];
		} else {
			console.log("No hay selección actual");
			return null;
		}
	}

	obtenerConteosEstatus( hot );
	function obtenerConteosEstatus( hot ){
		var posiblesEstados = {
		  'Pendiente': 0,
		  'No respondio': 0,
		  'Registro': 0,
		  'No se cerró': 0,
		  'Cita no atendida': 0,
		  'Cita atendida': 0,
		  'Cita': 0, 
		  'Contactado': 0
		};

		var statusColumnData = hot.getDataAtCol(7);

		statusColumnData.forEach(function(status) {
		  if (status && posiblesEstados.hasOwnProperty(status)) {
		    posiblesEstados[status]++;
		  }
		});

		var conteoTotal = Object.values(posiblesEstados).reduce(function (total, currentValue) {
		  return total + currentValue;
		}, 0);

		$('#conteo_total').text(conteoTotal);
		$('#conteo_pendientes').text(posiblesEstados['Pendiente']);
		$('#conteo_no_respondio').text(posiblesEstados['No respondio']);
		$('#conteo_registros').text(posiblesEstados['Registro']);
		$('#conteo_no_se_cerro').text(posiblesEstados['No se cerró']);
		$('#conteo_cita_no_atendida').text(posiblesEstados['Cita no atendida']);
		$('#conteo_cita_atendida').text(posiblesEstados['Cita atendida']);
		$('#conteo_citas').text(posiblesEstados['Cita']);
		$('#conteo_contactados').text(posiblesEstados['Contactado']);
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
	    filename: 'Reporte de contactos',
	    mimeType: 'text/csv',
	    rowDelimiter: '\r\n',
	    rowHeaders: true,
	  });
	});

	function adicionarFila(rowData) {
		alert("Datos de la fila: " + JSON.stringify(rowData));
	}

	function guardarCelda(hot, row, column, value) {
		var id = hot.getDataAtCell(row, 10);
		var observaciones = hot.getDataAtCell(row, 8);
		var mercado = hot.getDataAtCell(row, 5);
		var producto = hot.getDataAtCell(row, 6);
    	var nombre = hot.getDataAtCell(row, 3);
    	var telefono = hot.getDataAtCell(row, 4);
    	var id_eje = $('#selector_ejecutivo option:selected').val();

    	if ( id == "" || id == null || id == undefined ) {
    		var accion = "Alta";
    		var campo = obtenerCampoValor( column );
    		var valor = value;

			if (column === 1) {
				var dropdownValue = hot.getDataAtCell(row, column);
				var selectedOption = dropdownEjecutivo.find(function(option) {
					return option.label === dropdownValue;
				});
				valor = selectedOption ? selectedOption.value : null;
				console.log('id_ejecutivo (ALTA): ' + valor);
			}

    		if(valor){
    			$.ajax({
					url: 'server/controlador_referido.php',
					type: 'POST',
					data: { campo, valor, accion, id_eje },
					dataType: 'json',
					success: function( data ){
						console.log( 'response:'+data );
						
						hot.setDataAtCell(row, 10, data.id_con);
						hot.setDataAtCell(row, 9, data.id_con);
						hot.setDataAtCell(row, 0, data.fec_con);
						hot.setDataAtCell(row, 1, data.nom_ejecutivo_asignado);
						hot.setDataAtCell(row, 2, data.nom_eje);
						hot.setDataAtCell(row, 3, data.nom_con);
						hot.setDataAtCell(row, 4, data.tel_con);
						hot.setDataAtCell(row, 5, data.can_con);
						hot.setDataAtCell(row, 6, data.pro_con);
						hot.setDataAtCell(row, 7, data.est_con);
						hot.setDataAtCell(row, 8, data.obs_con);

						if ( campo == 'est_con' && valor == 'Registro' ) {
							$('#modal_registro').modal('show');
							$('#id_cit').val(data.id_cit1);
						} 

						obtenerConteosEstatus( hot );
						hot.alter('insert_row', 0);
						obtenerCoordenadasPrimeraCeldaSeleccionada( hot );
						hot.render();
					}
			    });
    		}

    	} else {
			console.log('edicion ref');

    		var accion = "Cambio";
    		var campo = obtenerCampoValor( column );
    		var valor = value;
    		var id_con = id;

			if (column === 1) {
				var dropdownValue = hot.getDataAtCell(row, column);
				var selectedOption = dropdownEjecutivo.find(function(option) {
					return option.label === dropdownValue;
				});
				valor = selectedOption ? selectedOption.value : null;
				console.log('id_ejecutivo (EDICION): ' + valor);
			}

    		$.ajax({
				url: 'server/controlador_referido.php',
				type: 'POST',
				data: { campo, valor, accion, id_con },
				dataType: 'json',
				success: function( data ){
					console.log( 'edicion referidos: '+data );
					
					if ( campo == 'est_con' && valor == 'Cita' ) {
						let submitClicked = false;

						swal({
							title: "¿Convertir este contacto a cita?",
							text: "Podrás consultarla en 'Citas'",
							icon: "warning",
							buttons: {
								cancel: {
									text: "Cancelar",
									value: null,
									visible: true,
									className: "",
									closeModal: true,
								},
								confirm: {
									text: "Continuar",
									value: true,
									visible: true,
									className: "",
									closeModal: false
								}
							},
							content: {
								element: "div",
								attributes: {
									innerHTML: `
										<div style="margin-bottom: 20px;">
											<label style="font-size: 10px; display: block; margin-bottom: 5px;">Día y hora</label>
											<div style="display: flex; align-items: center; margin-bottom: 10px;">
												<input id="fecha" type="date" class="form-control" style="flex-grow: 1; margin-left: 10px;" value="${new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0]}">
												<input id="horario" type="time" class="form-control" style="flex-grow: 1; margin-left: 10px;" value="18:00">
											</div>
										</div>
									`
								},
							},
							dangerMode: true,
						}).then((willDelete) => {
							if (willDelete && !submitClicked) {
								submitClicked = true;
								var fecha = document.getElementById('fecha').value;
								var horario = document.getElementById('horario').value;
								var accion = 'Alta';

								$('.swal-button--confirm').attr('disabled', 'disabled');

								$.ajax({
									url: 'server/agregar_cita.php',
									type: 'POST',
									data: { id_con, nombre, telefono, observaciones, accion, id_eje, fecha, horario, mercado, producto },
									success: function(data){
										console.log('response');
										console.log(data);
										swal.close();
										hot.render();
									},
									error: function(error) {
										console.log('Error:', error);
										swal("Error", "No se pudo registrar la cita. Por favor, intente nuevamente.", "error");
									},
									complete: function() {
										submitClicked = false;
									}
								});
								bloquearFila(hot, row);
							}
						});
					}

					if ( campo == 'est_con' && valor == 'Registro' ) {
						$('#modal_registro').modal('show');
						$('#id_cit').val(data.id_cit1);
						obtenerNombreDescompuesto( nombre );
						$('#tel_alu').val(telefono);
						obtenerCorreoCompuesto();
						obtener_colegiatura_grupo();
					} 

					console.log(data);
					if ( data.resultado == 'false' ) {
						hot.setDataAtCell(row, 10, '');
						hot.setDataAtCell(row, 9, '');
						hot.setDataAtCell(row, 0, '');
						hot.setDataAtCell(row, 1, '');
						hot.setDataAtCell(row, 2, '');
					}	
					obtenerConteosEstatus( hot );
					hot.render();
				}
		    });
    	}

    	function obtenerCampoValor( column ){
			if (column == 1) {
				columnName = "id_ejecutivo";
			} else if (column == 3) {
				columnName = "nom_con";
			} else if (column == 4) {	
				columnName = "tel_con";
			} else if (column == 5) {
				columnName = "can_con";
			} else if (column == 6) {
				columnName = "pro_con";
			} else if (column == 7) {
				columnName = "est_con";
			} else if (column == 8) {
				columnName = "obs_con";
			}
			return columnName;
		}
	}

	function obtenerNombreDescompuesto(nombreCompleto) {
		if (!nombreCompleto || nombreCompleto.trim() === '') {
			$('#nom_alu').val('Vacío');
			$('#app_alu').val('Vacío');
			$('#apm_alu').val('Vacío');
			return;
		}
		var partes = nombreCompleto.trim().split(' ');

		var nom_alu = partes.length > 0 ? partes.shift() : 'Vacío';
		var app_alu = partes.length > 0 ? partes.shift() : 'Vacío';
		var apm_alu = partes.length > 0 ? partes.join(' ') : 'Vacío';

		$('#nom_alu').val(nom_alu);
		$('#app_alu').val(app_alu);
		$('#apm_alu').val(apm_alu);
	}

	function obtenerCorreoCompuesto(){
        let correo = $('#correo').val(
            remove_accents( $('#nom_alu').val().trim().split(' ')[0].toLowerCase() ) + '-' + 
            remove_accents( $('#app_alu').val().trim().replace(' ', '').toLowerCase() ) + '@<?php echo $folioPlantel; ?>.com' 
        );

		validacionCorreoTiempoReal( correo );
    }

    correo = $('#correo').val();
    validacionCorreoTiempoReal( correo );

    function validacionCorreoTiempoReal( correo ){
        if (correo != '') {
          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: { correo },
            success: function(response){
              var respuesta = response; 

              if (respuesta == 'disponible') {
                $('#output').attr({
                  class: 'text-info letraPequena font-weight-normal'
                });
                $('#output').text("¡El correo electrónico está disponible!");
              }else{
                correo = correo.substring(0, correo.indexOf("@"))+'1';
                correo = correo+'@<?php echo $folioPlantel; ?>.com';
                $('#correo').val( correo );
                validacionCorreoTiempoReal( correo ); 
              }
            }
          })
        }
    }

    $('.correoCompuesto').on('keyup', function(event) {
         var correo = obtenerCorreoCompuesto();
         validacionCorreoTiempoReal( correo );
    });

	function validarFormatoHora(value) {
        var regex = /^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i;
        return regex.test(value);
    }

    function remove_accents(str){
        const map = {
          '-' : ' ',
          'a' : 'á|à|ã|â|ä|À|Á|Ã|Â|Ä',
          'e' : 'é|è|ê|ë|É|È|Ê|Ë',
          'i' : 'í|ì|î|ï|Í|Ì|Î|Ï',
          'o' : 'ó|ò|ô|õ|ö|Ó|Ò|Ô|Õ|Ö',
          'u' : 'ú|ù|û|ü|Ú|Ù|Û|Ü',
          'c' : 'ç|Ç',
          'n' : 'ñ|Ñ'
        };

        for (var pattern in map) {
          str = str.replace(new RegExp(map[pattern], 'g'), pattern);
        }

        return str;
    }
	
	function bloquearFila(hot, row) {
	    var colCount = hot.countCols();

	    for (var i = 0; i < colCount; i++) {
	        hot.setCellMeta(row, i, 'readOnly', true);
	    }

	    hot.render();
	}
	
</script>