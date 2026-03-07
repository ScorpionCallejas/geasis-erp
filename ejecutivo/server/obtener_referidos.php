<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	$id_eje = $_POST['id_eje'];

	$escala = $_POST['escala'];
?>


<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
	
	// CONFIGURACIÓN DE COLORES PARA ESTATUS (SOLO ESTO SE AÑADIÓ)
	const statusColorConfig = {
		'Registro': { color: '#00FFFF', textColor: '#000000' },
		'Cita': { color: '#FF9800', textColor: '#FFFFFF' },
		'Cita no atendida': { color: '#FF6666', textColor: '#FFFFFF' },
		'Cita atendida': { color: '#00FF00', textColor: '#000000' },
		'Pendiente': { color: '#FFC107', textColor: '#000000' },
		'Cargado': { color: '#17A2B8', textColor: '#FFFFFF' },
		'No respondio': { color: '#6C757D', textColor: '#FFFFFF' },
		'No se cerró': { color: '#DC3545', textColor: '#FFFFFF' },
		'Contactado': { color: '#28A745', textColor: '#FFFFFF' }
	};

	// RENDERER PARA COLORES DE ESTATUS (SOLO ESTO SE AÑADIÓ)
	function statusColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		
		if (value && statusColorConfig[value]) {
			td.style.backgroundColor = statusColorConfig[value].color;
			td.style.color = statusColorConfig[value].textColor;
		}
	}

	obtener_tarjeta_ejecutivo( '<?php echo $id_eje; ?>', '<?php echo $inicio; ?>', '<?php echo $fin; ?>' );

	moment.locale('es');

	var container = document.querySelector('#data-sheet');
	var colHeaders = ['FECHA', 'EJECUTIVO', 'NOMBRE', 'NÚMERO', 'MERCADO', 'PRODUCTO DE INTERES', 'ESTATUS', 'OBSERVACIONES', 'BOOL', 'ID_CIT'];
	var data = Array(0).fill(0).map(() => [ "", "", "", "", "", "", "", "", "", "" ]);  // Declaración al inicio del script o función
	
	<?php  
	if ( $id_eje == 'Todos' ) {
		//echo 'TOOODOS';
		$sqlPlanteles = "
			SELECT *
			FROM planteles_ejecutivo
			INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
			WHERE id_eje = '$id'
		";

		$totalValidacion = obtener_datos_consulta( $db, $sqlPlanteles )['total'];

		if( $totalValidacion == 0 ){
		// NO HAY PLANTELES ASOCIADOS
			$sql = "
				SELECT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con,  contacto.fec_con, ejecutivo.nom_eje, contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
				IF(cita.id_con2 IS NOT NULL, cita.nom_cit, NULL) AS nom_cit,
				IF(cita.id_con2 IS NOT NULL, cita.cla_cit, 'false') AS cla_cit,
				IF(cita.id_con2 IS NOT NULL, obtener_registro_por_cita(cita.id_cit), NULL) AS es_registro,
				IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
				FROM contacto
				INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
				LEFT JOIN cita ON cita.id_con2 = contacto.id_con
				WHERE contacto.cla_con = 'Referido' AND contacto.id_pla10 = '$plantel' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin'
			";
		//
		} else {
		//
			$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

			$contador = 0;
			$sqlEjecutivos = '';
			while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
				if ($contador > 0) {
					$sqlEjecutivos .= ' OR ';
				}
				$sqlEjecutivos .= 'id_pla = '.$filaPlanteles['id_pla'];
				
				$contador++;
			}

			$sqlEjecutivos = $sqlEjecutivos." ) ";

			$sql = "
				SELECT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con,  contacto.fec_con, ejecutivo.nom_eje, contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
				IF(cita.id_con2 IS NOT NULL, cita.nom_cit, NULL) AS nom_cit,
				IF(cita.id_con2 IS NOT NULL, cita.cla_cit, 'false') AS cla_cit,
				IF(cita.id_con2 IS NOT NULL, obtener_registro_por_cita(cita.id_cit), NULL) AS es_registro,
				IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
				FROM contacto
				INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
				LEFT JOIN cita ON cita.id_con2 = contacto.id_con
				WHERE contacto.cla_con = 'Referido' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin' AND (
			";
			$sql .= $sqlEjecutivos;
			//echo $sql;
			
		//
		}

	} else {
		$sql = "
			SELECT contacto.id_con, contacto.est_con, contacto.can_con, contacto.pro_con,  contacto.fec_con, ejecutivo.nom_eje, contacto.tel_con, contacto.nom_con, contacto.niv_con, contacto.obs_con, contacto.tip_con, contacto.cit_con, contacto.cla_con, contacto.id_eje10,
			IF(cita.id_con2 IS NOT NULL, cita.nom_cit, NULL) AS nom_cit,
			IF(cita.id_con2 IS NOT NULL, cita.cla_cit, 'false') AS cla_cit,
			IF(cita.id_con2 IS NOT NULL, obtener_registro_por_cita(cita.id_cit), NULL) AS es_registro,
			IF((SELECT COUNT(id_cit) FROM cita WHERE id_con2 = contacto.id_con) > 0, 'true', 'false') AS res
			FROM contacto
			INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10
			LEFT JOIN cita ON cita.id_con2 = contacto.id_con
			WHERE contacto.cla_con = 'Referido' AND contacto.id_eje10 = '$id_eje' AND DATE(fec_con) BETWEEN '$inicio' AND '$fin'
		";
	}

	// $sql .= " GROUP BY id_con";

	// echo $sql;
	

	// echo $sql;
	$resultado = mysqli_query($db, $sql);
	
	$data = [];


	// Llamada inicial a la función recursiva para procesar todo el árbol
	if( $escala != '' && $escala == 'estructura' ){
		$data = obtener_tabla_estructura_referidos($id_eje, $inicio, $fin, $db, $data);	
	}

	// $data = obtener_tabla_estructura_referidos($id_eje, $inicio, $fin, $db, $data);

	// echo "alert('Tamaño de data antes de la recursividad: " . sizeof($data) . "');";
	// Consulta para obtener los datos del nodo raíz después de procesar la recursividad
	$resultado = mysqli_query($db, $sql);

	// Procesamiento de los resultados del nodo raíz
	while ($fila = mysqli_fetch_assoc($resultado)) {
		$data[] = $fila;
	}


	if (sizeof($data) > 0) {
		echo 'data = [';
		foreach ($data as $fila) {
			$fec_con = json_encode(fechaFormateadaCompacta($fila['fec_con']));
			$nom_eje = isset($fila['nom_eje']) ? json_encode($fila['nom_eje']) : 'null';
			$nom_con = isset($fila['nom_con']) ? json_encode($fila['nom_con']) : 'null';
			$tel_con = isset($fila['tel_con']) ? json_encode($fila['tel_con']) : 'null';
			$can_con = json_encode($fila['can_con']);
			$pro_con = json_encode($fila['pro_con']);

			// CASO CONTACTO ---> REGISTRO
			
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

			echo "[$fec_con, $nom_eje, $nom_con, $tel_con, $can_con, $pro_con, $est_con, $obs_con, $res, $id_con],\n";
		}
		echo '];';
	} else {
		echo 'data = Array(15).fill(0).map(() => [ "", "", "", "", "", "", "", "", "", "" ]);';
	}

?>

	var hot;  // Declaración al inicio del script o función

	if (hot) {
	    hot.destroy();
	}

	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
	  	td.style.backgroundColor = '#E3E6E7'; // Cambia el color de fondo
	}

	hot = new Handsontable(container, {
		language: 'es-MX',
		data,

		cells: function deshabilitarFila(row, col) {
		    var cellProperties = {};

		    // Asumiendo que el estado está en la columna 'ESTATUS'
		    if (this.instance.getDataAtRowProp(row, 6) === 'Cita' && this.instance.getDataAtRowProp(row, 8) == 'true') {
		      cellProperties.readOnly = true;
		    }

			if (this.instance.getDataAtRowProp(row, 6) === 'Registro' && this.instance.getDataAtRowProp(row, 8) == 'true') {
		      cellProperties.readOnly = true;
		    }

		    // APLICAR RENDERER DE COLORES SOLO A LA COLUMNA 6 (ESTATUS)
		    if (col === 6) {
		        cellProperties.renderer = statusColumnRenderer;
		    }

		    if ( col === 0 || col === 1 || col === 8 || col === 9 ) { // Verifica si es la primera columna
		      cellProperties.renderer = firstColumnRenderer; // Usa un renderizador personalizado
		    }

		    return cellProperties;
		},
		
		hiddenColumns: {
	        columns: [8,9], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
	        indicators: false // Esto oculta el indicador de columnas ocultas
	    },
		stretchH: 'all',
		colHeaders: colHeaders,
		
		rowHeaders: true,
		autoWrapRow: true,
		autoWrapCol: true,

		fixedRowsTop: 0, // Fija la primera fila (encabezado)
      	fixedColumnsLeft: 0, // Fija la primera columna

		width: '100%',
        height: 500,  // Ajusta el valor según tus necesidades

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
                if (prop === 0 || prop === 1 || prop === 9) {
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

			{ readOnly: true, },

			{},  // nombre
			{},  // número
			
			{ 
				// mercado
				type: 'dropdown',
				source: ["Facebook", "FANPAGE", "PAUTA ORGÁNICA", "PAUTA AHJ", "Mercado natural", "Mercado frío", "Referidos", "Rezagados", "Módulo", "Re matriculación", "Volantes", "Marketing", "PP"]
			},

			{
				// prod de interes
				type: 'dropdown',
				source: ['PE', 'EXAMEN ÚNICO', 'LICENCIATURA', 'BACH18']  //
			},

			{
				// estatus - SOLO SE AÑADIÓ EL RENDERER
				type: 'dropdown',
				source: ['Pendiente', 'Cargado', 'No respondio', 'Registro', 'No se cerró', 'Cita no atendida', 'Cita atendida', 'Cita', 'Contactado'],
				renderer: statusColumnRenderer
			},

			{},  // obs
			{ readOnly: true, },  // BOOL
			{ readOnly: true, },  // ID_CIT
		]
	});


	function obtenerCoordenadasPrimeraCeldaSeleccionada( hot ) {
		var selectedRange = hot.getSelected();
		if (selectedRange) {
			var fila = selectedRange.start.row;
			var columna = selectedRange.start.col;
			console.log("Primera celda seleccionada: Fila", fila, ", Columna", columna);
			return [fila, columna]; // Retorna las coordenadas en un array
		} else {
			console.log("No hay selección actual");
			return null;
		}
	}


	obtenerConteosEstatus( hot );
	function obtenerConteosEstatus( hot ){

		// Define los posibles estados.
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

		// Obtén todos los datos de la columna con índice 10.
		var statusColumnData = hot.getDataAtCol(6);

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

		// 'Pendiente', 'No respondio', 'Registro', 'No se cerró', 'Cita no atendida', 'Cita atendida', 'Cita', 'Contactado'
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
	    //columns: [0, 1, 2, 3]  // Aquí especificas las columnas que deseas exportar
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


		var id = hot.getDataAtCell(row, 9); // Obtener el valor de la primera columna (ID) para la fila específica

		var observaciones = hot.getDataAtCell(row, 7);
		var mercado = hot.getDataAtCell(row, 4);
		var producto = hot.getDataAtCell(row, 5);

    	var nombre = hot.getDataAtCell(row, 2); 
    	var telefono = hot.getDataAtCell(row, 3);

    	var id_eje = $('#selector_ejecutivo option:selected').val();

    	//alert( id_eje );
    	

    	//alert("ID: " + id + "\nFila: " + row + "\nColumna: " + column + "\nValor: " + value);
    	if ( id == "" || id == null || id == undefined ) {
    		// ALTA
    		//alert("no hay datos en esta fila");

			// console.log('entre a alta');

    		var accion = "Alta";
    		var campo = obtenerCampoValor( column );
    		var valor = value;

    		// ADD

    		if(valor){

    			$.ajax({
					url: 'server/controlador_referido.php',
					type: 'POST',
					data: { campo, valor, accion, id_eje },
					dataType: 'json',
					success: function( data ){
					//success
						console.log( 'response:'+data );
						
						hot.setDataAtCell(row, 9, data.id_con); // Folio de la cita
						hot.setDataAtCell(row, 8, data.id_con); // Folio de la cita
						hot.setDataAtCell(row, 0, data.fec_con); // Fecha de la cita
						hot.setDataAtCell(row, 1, data.nom_eje); // Estado de la cita
						hot.setDataAtCell(row, 2, data.nom_con); // Cantidad de citas
						hot.setDataAtCell(row, 3, data.tel_con); // Teléfono de la cita
						hot.setDataAtCell(row, 4, data.can_con); // Nombre de la cita
						hot.setDataAtCell(row, 5, data.pro_con); // Nivel de la cita
						hot.setDataAtCell(row, 6, data.est_con); // Observaciones de la cita
						hot.setDataAtCell(row, 7, data.obs_con); // Tipo de cita


						// REGISTRO
						if ( campo == 'est_con' && valor == 'Registro' ) {

							$('#modal_registro').modal('show');
							$('#id_cit').val(data.id_cit1);
							
							
						} 
						// F REGISTRO

						
						obtenerConteosEstatus( hot );
						hot.alter('insert_row', 0);
						//obtener_conteos_citas();
						//alert( data.sql );}
						obtenerCoordenadasPrimeraCeldaSeleccionada( hot );
					}

			    });

    		}

    	} else {
			console.log('edicion ref');

    		//UPDATE
    		var accion = "Cambio";
    		var campo = obtenerCampoValor( column );
    		var valor = value;
    		var id_con = id;

    		$.ajax({
				url: 'server/controlador_referido.php',
				type: 'POST',
				data: { campo, valor, accion, id_con },
				dataType: 'json',
				success: function( data ){
				//success

					console.log( 'edicion referidos: '+data );
					// CITA
					if ( campo == 'est_con' && valor == 'Cita' ) {
						//
						// SWAL
						let submitClicked = false; // Variable de control

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
									closeModal: false  // Modal no se cierra automáticamente.
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
							if (willDelete && !submitClicked) { // Verificar si el botón fue clicado y no ha sido desactivado
								submitClicked = true; // Establecer la variable a true para evitar clics adicionales
								var fecha = document.getElementById('fecha').value;
								var horario = document.getElementById('horario').value;

								var accion = 'Alta';

								console.log('ajax');

								// Desactivar el botón
								$('.swal-button--confirm').attr('disabled', 'disabled');

								$.ajax({
									url: 'server/agregar_cita.php',
									type: 'POST',
									data: { id_con, nombre, telefono, observaciones, accion, id_eje, fecha, horario, mercado, producto },
									success: function(data){
										console.log('response');
										console.log(data);
										obtener_datos();
										swal.close();  // Cerrar el modal aquí después del éxito.
									},
									error: function(error) {
										console.log('Error:', error);
										swal("Error", "No se pudo registrar la cita. Por favor, intente nuevamente.", "error");
									},
									complete: function() {
										submitClicked = false; // Restablecer la variable después de que se complete la solicitud
									}
								});
								bloquearFila(hot, row);
							}
						});

						// F SWAL




						//
						// $('#modal_registro').modal('show');

						// $('#id_con').val(id);
						// obtenerNombreDescompuesto( nombre );
						// $('#tel_alu').val(telefono);
						// obtenerCorreoCompuesto();

						
						
					}
					// FIN CITA


					// REGISTRO
					if ( campo == 'est_con' && valor == 'Registro' ) {

						$('#modal_registro').modal('show');
						$('#id_cit').val(data.id_cit1);
						obtenerNombreDescompuesto( nombre );
						$('#tel_alu').val(telefono);
						obtenerCorreoCompuesto();
						obtener_colegiatura_grupo();
					} 
					// F REGISTRO

					console.log(data);
					if ( data.resultado == 'false' ) {
						hot.setDataAtCell(row, 9, '');
						hot.setDataAtCell(row, 8, '');
						hot.setDataAtCell(row, 0, '');
						hot.setDataAtCell(row, 1, '');
					}	
					//console.log(data.resultado);
					obtenerConteosEstatus( hot );
					//obtener_conteos_citas();
				}

		    });

    	}

    	function obtenerCampoValor( column ){

			if (column == 2) {
			    columnName = "nom_con";
			} else if (column == 3) {	
			    columnName = "tel_con";
			} else if (column == 4) {
			    columnName = "can_con";
			} else if (column == 5) {
			    columnName = "pro_con";
			} else if (column == 6) {
			    columnName = "est_con";
			} else if (column == 7) {
			    columnName = "obs_con";
			}
			return columnName;
		}
	}

	

	function obtenerNombreDescompuesto(nombreCompleto) {
		// Limpiar espacios en blanco y verificar si el nombre completo está vacío
		if (!nombreCompleto || nombreCompleto.trim() === '') {
			$('#nom_alu').val('Vacío');
			$('#app_alu').val('Vacío');
			$('#apm_alu').val('Vacío');
			return;
		}
		// Separar el nombre por espacios
		var partes = nombreCompleto.trim().split(' ');

		// Asignar valores por defecto
		var nom_alu = partes.length > 0 ? partes.shift() : 'Vacío'; // Primer elemento para el nombre
		var app_alu = partes.length > 0 ? partes.shift() : 'Vacío'; // Segundo elemento para el apellido paterno
		var apm_alu = partes.length > 0 ? partes.join(' ') : 'Vacío'; // El resto para el apellido materno

		// Actualizar los valores en los campos correspondientes
		$('#nom_alu').val(nom_alu);
		$('#app_alu').val(app_alu);
		$('#apm_alu').val(apm_alu);
	}


	// 
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
        //console.log( correo );

        if (correo != '') {
          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: { correo },
            success: function(response){
               //console.log(response);
              var respuesta = response; 


              if (respuesta == 'disponible') {
                
                $('#output').attr({
                  class: 'text-info letraPequena font-weight-normal'
                });
                $('#output').text("¡El correo electrónico está disponible!");

              }else{
                // correo = correo+'1';
                
                correo = correo.substring(0, correo.indexOf("@"))+'1';

                correo = correo+'@<?php echo $folioPlantel; ?>.com';
                $('#correo').val( correo );

                validacionCorreoTiempoReal( correo ); 
                
                // $('#output').attr({
                //   class: 'text-danger letraPequena font-weight-normal'
                // });
                // $('#output').text("¡El correo electrónico está ocupado!");

              }
            }
          })

        }
              
    }


    $('.correoCompuesto').on('keyup', function(event) {
        /* Act on the event */

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
	// 
	
	function bloquearFila(hot, row) {
	    // Obtén la cantidad de columnas en la tabla
	    var colCount = hot.countCols();

	    // Recorre todas las columnas de la fila específica y establece la propiedad readOnly
	    for (var i = 0; i < colCount; i++) {
	        hot.setCellMeta(row, i, 'readOnly', true);
	    }

	    // Rerenderiza la tabla para aplicar los cambios de metadatos
	    hot.render();
	}
	
</script>