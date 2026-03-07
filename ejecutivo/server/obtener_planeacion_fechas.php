<?php  
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

	$id_pla = $_POST['id_pla'];
	$estatus = $_POST['estatus'];

?>

<style>
    .table td, .table th {
      padding: 5px;
    }

    .letraDiminuta{
      font-size: 8px;
    }

    th, td {
      width: 200px;
      white-space: nowrap;
    }

    select.columna_certificacion {
        width: 100%;
        background-color: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    /* Estilos para las columnas de seguimiento */
    .columna-seguimiento {
        background-color: #f8f9fa;
        font-size: 9px;
        text-align: center;
        min-width: 80px;
        max-width: 80px;
    }

    .header-seguimiento {
        background-color: #000000 !important;
        color: white !important;
        font-size: 8px;
        text-align: center;
        padding: 3px 2px;
        min-width: 80px;
        max-width: 80px;
        line-height: 1.1;
        word-wrap: break-word;
        vertical-align: middle;
    }
</style>

  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-bordered" id="tabla_planeacion_simplificada">
			<thead class="" style="background-color: #002060; color: white;">
				<tr>
					<th class="letraPequena" rowspan="2">SEMANA</th>
					<th class="letraPequena" rowspan="2">PERMISOS (QUIÉN VE LOS GPOS)</th>
					<th class="letraPequena" rowspan="2">MES</th>
					<th class="letraPequena" rowspan="2">GRUPO</th>
					<th class="letraPequena" rowspan="2">PROGRAMA</th>
					<th class="letraPequena" rowspan="2">MODALIDAD</th>
					<th class="letraPequena" rowspan="2">DÍAS</th>
					<th class="letraPequena" rowspan="2">FECHA DE INICIO</th>
					<th class="letraPequena" rowspan="2">FECHA DE FIN</th>
					<th class="letraPequena" rowspan="2">HORARIO</th>
					<!-- Columnas de seguimiento - Primera fila (números de semana) -->
					<th class="header-seguimiento">SEMANA 7</th>
					<th class="header-seguimiento">SEMANA 8</th>
					<th class="header-seguimiento">SEMANA 10</th>
					<th class="header-seguimiento">SEMANA 11</th>
					<th class="header-seguimiento">SEMANA 13</th>
					<th class="header-seguimiento">SEMANA 15</th>
					<th class="header-seguimiento">SEMANA 16</th>
					<th class="header-seguimiento">SEMANA 25</th>
					<th class="header-seguimiento">SEMANA 26</th>
					<th class="header-seguimiento">SEMANA 27</th>
					<th class="header-seguimiento">SEMANA 28</th>
					<th class="header-seguimiento">SEMANA 29</th>
					<th class="header-seguimiento">SEMANA 30</th>
				</tr>
				<tr>
					<!-- Segunda fila con los nombres completos de los eventos -->
					<th class="header-seguimiento">TRÁMITE 1</th>
					<th class="header-seguimiento">REVISIÓN DE DESEMPEÑO</th>
					<th class="header-seguimiento">CARTA DE ASPIRANTE</th>
					<th class="header-seguimiento">TRÁMITE 2</th>
					<th class="header-seguimiento">RECEPCIÓN DE DOCUMENTOS</th>
					<th class="header-seguimiento">INGRESO DE CERTIFICACIÓN</th>
					<th class="header-seguimiento">REVISIÓN DE DESEMPEÑO</th>
					<th class="header-seguimiento">CONOCER SI APROBARON</th>
					<th class="header-seguimiento">FINALIZA CURSO</th>
					<th class="header-seguimiento">GESTIÓN DE FIN DE CURSO</th>
					<th class="header-seguimiento">EXAMEN / MEMBRESÍAS</th>
					<th class="header-seguimiento">GRADUACIÓN</th>
					<th class="header-seguimiento">ARRANQUE DE DIPLOMADO</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($estatus == 'En curso') {
					$sql = "
						SELECT 
							generacion.*,
							rama.*,
							plantel.*,
							MONTH(ini_gen) as mes_numero
						FROM generacion
						INNER JOIN rama ON rama.id_ram = generacion.id_ram5
						INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
						WHERE id_pla = '$id_pla' 
							AND CURDATE() >= ini_gen 
							AND CURDATE() <= fin_gen
							AND eli_eje = 1
						ORDER BY ini_gen ASC
					";
				} else if ($estatus == 'Fin curso') {
					$sql = "
						SELECT 
							generacion.*,
							rama.*,
							plantel.*,
							MONTH(ini_gen) as mes_numero
						FROM generacion
						INNER JOIN rama ON rama.id_ram = generacion.id_ram5
						INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
						WHERE id_pla = '$id_pla' 
							AND CURDATE() > fin_gen
							AND eli_eje = 1
						ORDER BY ini_gen ASC
					";
				} else if ($estatus == 'Por comenzar') {
					$sql = "
						SELECT 
							generacion.*,
							rama.*,
							plantel.*,
							MONTH(ini_gen) as mes_numero
						FROM generacion
						INNER JOIN rama ON rama.id_ram = generacion.id_ram5
						INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
						WHERE id_pla = '$id_pla' 
							AND CURDATE() < ini_gen
							AND eli_eje = 1
						ORDER BY ini_gen ASC
					";
				}

				$resultado = mysqli_query($db, $sql);
				$mes_actual = null;

				while ($fila = mysqli_fetch_assoc($resultado)) {
					// Si cambia el mes, imprimir fila separadora
					if ($mes_actual !== null && $mes_actual != obtenerMesServer($fila['ini_gen'])) {
						?>
						<!-- Fila separadora blanca -->
						<tr style="background-color: white; height: 15px;">
							<?php for ($i = 0; $i < 23; $i++) { ?>
								<td class="letraPequena"></td>
							<?php } ?>
						</tr>
						<!-- Fila separadora amarilla -->
						<tr style="background-color: #FFD965; height: 25px;">
							<?php for ($i = 0; $i < 23; $i++) { ?>
								<td class="letraPequena"></td>
							<?php } ?>
						</tr>
						<?php
					}

					$mes_actual = obtenerMesServer($fila['ini_gen']);
					
					// Calcular fechas para las columnas de seguimiento
					$fecha_inicio = $fila['ini_gen'];
					$fechas_seguimiento = array(
						7 => calcularFechaSemana($fecha_inicio, 7),
						8 => calcularFechaSemana($fecha_inicio, 8),
						10 => calcularFechaSemana($fecha_inicio, 10),
						11 => calcularFechaSemana($fecha_inicio, 11),
						13 => calcularFechaSemana($fecha_inicio, 13),
						15 => calcularFechaSemana($fecha_inicio, 15),
						16 => calcularFechaSemana($fecha_inicio, 16),
						25 => calcularFechaSemana($fecha_inicio, 25),
						26 => calcularFechaSemana($fecha_inicio, 26),
						27 => calcularFechaSemana($fecha_inicio, 27),
						28 => calcularFechaSemana($fecha_inicio, 28),
						29 => calcularFechaSemana($fecha_inicio, 29),
						30 => calcularFechaSemana($fecha_inicio, 30)
					);
					?>
					<tr style="background: white; color: black; position: relative;" data-id-gen="<?php echo $fila['id_gen']; ?>">
						<td class="letraPequena" ini_gen="<?php echo $fila['ini_gen']; ?>">
							<?php echo obtenerSemanaTrabajo2($fila['ini_gen']); ?>
							<span class="btn-eliminar" data-id-gen="<?php echo $fila['id_gen']; ?>" style="position: absolute; top: 5px; right: 5px; color: red; cursor: pointer; font-size: 12px; font-weight: bold;">✖</span>
						</td>
						<td class="letraPequena">
							<?php
							// Obtener el total de planteles
							$query_total_planteles = "SELECT COUNT(*) AS total_planteles FROM plantel WHERE id_cad1 = 1";
							$result_total_planteles = mysqli_query($db, $query_total_planteles);
							$total_planteles = mysqli_fetch_assoc($result_total_planteles)['total_planteles'];

							// Obtener el total de planteles vinculados al ejecutivo
							$query_planteles_ejecutivo = "SELECT COUNT(*) AS total_vinculados FROM planteles_ejecutivo WHERE id_eje = $id_eje";
							$result_planteles_ejecutivo = mysqli_query($db, $query_planteles_ejecutivo);
							$total_vinculados = mysqli_fetch_assoc($result_planteles_ejecutivo)['total_vinculados'];

							// Verificar si el ejecutivo tiene permisos
							$tiene_permisos = ($total_vinculados == $total_planteles);
							?>

							<?php if ($tiene_permisos): ?>
								<!-- Dropdown para usuarios con todos los permisos -->
								<div class="dropdown d-inline-block">
									<a class="btn-link dropdown-toggle text-primary" href="#"
									id="statusDropdown_<?php echo $fila['id_gen']; ?>"
									data-bs-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false"
									style="font-size: 10px;">
										<?php
										$estado_actual = $fila['est_gen'];
										switch ($estado_actual) {
											case '1':
												echo 'ADMIN Y COMERCIAL';
												break;
											case '2':
												echo 'SOLO COMERCIAL';
												break;
											case '3':
												echo 'SOLO ADMIN';
												break;
											case '4':
												echo 'NADIE';
												break;
										}
										?>
										<i class="mdi mdi-chevron-down"></i>
									</a>
									<div class="dropdown-menu" aria-labelledby="statusDropdown_<?php echo $fila['id_gen']; ?>">
										<a class="dropdown-item status-option" href="#"
										data-status="1"
										data-id="<?php echo $fila['id_gen']; ?>"
										style="font-size: 10px;">
											ADMIN Y COMERCIAL
										</a>
										<a class="dropdown-item status-option" href="#"
										data-status="2"
										data-id="<?php echo $fila['id_gen']; ?>"
										style="font-size: 10px;">
											SOLO COMERCIAL
										</a>
										<a class="dropdown-item status-option" href="#"
										data-status="3"
										data-id="<?php echo $fila['id_gen']; ?>"
										style="font-size: 10px;">
											SOLO ADMIN
										</a>
										<a class="dropdown-item status-option" href="#"
										data-status="4"
										data-id="<?php echo $fila['id_gen']; ?>"
										style="font-size: 10px;">
											NADIE
										</a>
									</div>
								</div>
							<?php else: ?>
								<!-- Switch deshabilitado para usuarios sin todos los permisos -->
								<label class="form-switch">
									<input 
										type="checkbox" 
										id="switch_<?php echo $fila['id_gen']; ?>" 
										class="form-check-input switch-estado-grupo" 
										<?php echo ($tiene_permisos) ? '' : 'disabled'; ?> 
										<?php echo ($fila['est_gen'] == '1' || $fila['est_gen'] == '3') ? 'checked' : ''; ?>
									>
									<span class="slider"></span>
								</label>
							<?php endif; ?>
						</td>
						<td class="letraPequena"><?php echo getMonth(obtenerMesServer($fila['ini_gen'])); ?></td>
						<td class="letraPequena">
							<a href="alumnos.php?id_gen=<?php echo $fila['id_gen']; ?>" class="text-primary ms-2" target="_blank">
								<strong><?php echo $fila['nom_gen']; ?></strong>
							</a>
						</td>
						<td class="letraPequena"><?php echo $fila['abr_ram']; ?></td>
						<td class="letraPequena" style="background-color: <?php echo $fila['mod_gen'] == 'Online' ? '#01B0F0' : '#5A9BD5'; ?>; color: black; padding: 2px 5px;">
							<?php echo $fila['mod_gen'] == 'Online' ? 'ONLINE' : 'Presencial'; ?>
						</td>
						<td class="letraPequena"><?php echo $fila['dia_gen']; ?></td>
						<td class="letraPequena" ini_gen="<?php echo $fila['ini_gen']; ?>"><?php echo fechaFormateadaCompacta4($fila['ini_gen']); ?></td>
						<td class="letraPequena" fin_gen="<?php echo $fila['fin_gen']; ?>"><?php echo fechaFormateadaCompacta4($fila['fin_gen']); ?></td>
						<td class="letraPequena"><?php echo $fila['hor_gen']; ?></td>
						
						<!-- Columnas de seguimiento con fechas calculadas -->
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[7]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[8]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[10]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[11]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[13]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[15]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[16]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[25]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[26]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[27]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[28]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[29]; ?></td>
						<td class="columna-seguimiento"><?php echo $fechas_seguimiento[30]; ?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
      </div>
    </div>
  </div>

  <hr>
  <br><br>

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
// Agregar el event listener para las opciones del dropdown
$('.status-option').on('click', function(e) {
    e.preventDefault();
    var $option = $(this);
    var id_gen = $option.data('id');
    var est_gen = $option.data('status');
    
    $.ajax({
        url: 'server/controlador_grupo2.php',
        type: 'POST',
        data: {
            accion: 'actualizarEstado',
            id_gen: id_gen,
            est_gen: est_gen
        },
        success: function(response) {
            // Esto actualiza el texto del dropdown manteniendo el ícono
            var newText = $option.text().trim();
            $('#statusDropdown_' + id_gen).html(newText + ' <i class="mdi mdi-chevron-down"></i>');
            toastr.success('Cambios guardados');
        },
        error: function() {
            toastr.error('Error al guardar los cambios');
        }
    });
});
</script>

<script>
    $('#tabla_planeacion_simplificada').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        ordering: false, // Desactiva la ordenación
        scrollX: true, // Habilita scroll horizontal para las nuevas columnas
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE PLANEACION SIMPLIFICADA CON SEGUIMIENTO',
                className: 'btn-sm btn-success',
                exportOptions: {
                    columns: ':not(:nth-child(2))' // Excluye la segunda columna (PERMISOS)
                }
            },
        ],
        language: {
            search: 'Buscar' // Cambia el texto del buscador
        },
        info: false // Esto desactiva la información del pie de la tabla
    });
</script>

<script>
	// CSS para las celdas editables - ACTUALIZADO PARA NUEVAS COLUMNAS
	var estilos = `
	<style>
		#tabla_planeacion_simplificada tbody td:nth-child(4),    /* GRUPO */
		#tabla_planeacion_simplificada tbody td:nth-child(6),    /* MODALIDAD */
		#tabla_planeacion_simplificada tbody td:nth-child(7),    /* DÍAS */
		#tabla_planeacion_simplificada tbody td:nth-child(8),    /* FECHA INICIO */
		#tabla_planeacion_simplificada tbody td:nth-child(9),    /* FECHA FIN */
		#tabla_planeacion_simplificada tbody td:nth-child(10) {  /* HORARIO */
			cursor: pointer;
			transition: all 0.2s ease-in-out;
			position: relative;
		}

		#tabla_planeacion_simplificada tbody td:nth-child(4):hover,
		#tabla_planeacion_simplificada tbody td:nth-child(6):hover,
		#tabla_planeacion_simplificada tbody td:nth-child(7):hover,
		#tabla_planeacion_simplificada tbody td:nth-child(8):hover,
		#tabla_planeacion_simplificada tbody td:nth-child(9):hover,
		#tabla_planeacion_simplificada tbody td:nth-child(10):hover {
			background-color: #e9ecef !important;
			box-shadow: inset 0 0 0 1px #dee2e6;
		}

		#tabla_planeacion_simplificada tbody td:nth-child(4)::after,
		#tabla_planeacion_simplificada tbody td:nth-child(6)::after,
		#tabla_planeacion_simplificada tbody td:nth-child(7)::after,
		#tabla_planeacion_simplificada tbody td:nth-child(8)::after,
		#tabla_planeacion_simplificada tbody td:nth-child(9)::after,
		#tabla_planeacion_simplificada tbody td:nth-child(10)::after {
			content: '✎';
			position: absolute;
			right: 5px;
			top: 50%;
			transform: translateY(-50%);
			opacity: 0;
			color: #6c757d;
			transition: opacity 0.2s ease-in-out;
			font-size: 12px;
		}

		#tabla_planeacion_simplificada tbody td:nth-child(4):hover::after,
		#tabla_planeacion_simplificada tbody td:nth-child(6):hover::after,
		#tabla_planeacion_simplificada tbody td:nth-child(7):hover::after,
		#tabla_planeacion_simplificada tbody td:nth-child(8):hover::after,
		#tabla_planeacion_simplificada tbody td:nth-child(9):hover::after,
		#tabla_planeacion_simplificada tbody td:nth-child(10):hover::after {
			opacity: 1;
		}
	</style>`;

	$('head').append(estilos);

	// HTML de la modal - SIMPLIFICADO
	$('#warning-alert-modal .modal-body .form-group').html(`
		<input type="text" class="form-control" id="valor_edicion" style="display: none;">
		<select class="form-control letraPequena" id="select_modalidad" style="display: none;">
			<option value="Online">ONLINE</option>
			<option value="Presencial">PRESENCIAL</option>
		</select>
		<input type="date" class="form-control letraPequena" id="input_fecha" style="display: none;">
		<input type="date" class="form-control letraPequena" id="input_fecha_fin" style="display: none;">
	`);

	// El click handler - SIMPLIFICADO
	$('#tabla_planeacion_simplificada tbody').on('click', 'td', function(e) {
		console.log('¡Click detectado!');
		
		var $celda = $(this);
		var columnaIndex = $celda.index();
		
		console.log('Columna clickeada:', columnaIndex);
		
		// ÍNDICES PARA LA TABLA SIMPLIFICADA (solo las columnas editables originales)
		if (![3,5,6,7,8,9].includes(columnaIndex) || $(e.target).is('a')) {
			console.log('Columna no editable o click en link');
			return;
		}
		
		var $fila = $celda.closest('tr');
		var camposPorColumna = {
			3: 'nom_gen',       // GRUPO
			5: 'mod_gen',       // MODALIDAD  
			6: 'dia_gen',       // DÍAS
			7: 'ini_gen',       // FECHA DE INICIO
			8: 'fin_gen',       // FECHA DE FIN
			9: 'hor_gen'        // HORARIO
		};
		
		var campo = camposPorColumna[columnaIndex];
		var id_gen = $fila.find('td:eq(3) a').attr('href').split('id_gen=')[1];
		var nombreGrupo = $fila.find('td:eq(3) a').text().trim();
		
		console.log('Campo:', campo);
		console.log('ID:', id_gen);
		console.log('Grupo:', nombreGrupo);
		
		// Referencia a todos los inputs
		var $input = $('#valor_edicion');
		var $select = $('#select_modalidad');
		var $inputFecha = $('#input_fecha');
		var $inputFechaFin = $('#input_fecha_fin');
		
		// Escondemos todo primero
		$input.hide();
		$select.hide();
		$inputFecha.hide();
		$inputFechaFin.hide();
		
		// Configuramos según el campo
		if (campo === 'mod_gen') {
			var modalidad = $celda.text().trim();
			$select.show().val(modalidad.includes('ONLINE') ? 'Online' : 'Presencial');
			console.log('Modalidad:', $select.val());
		} 
		else if (campo === 'ini_gen') {
			var fechaOriginal = $celda.attr('ini_gen');
			$inputFecha.show().val(fechaOriginal);
			console.log('Fecha inicio:', fechaOriginal);
		} 
		else if (campo === 'fin_gen') {
			var fechaFin = $celda.attr('fin_gen');
			$inputFechaFin.show().val(fechaFin);
			console.log('Fecha fin:', fechaFin);
		} 
		else {
			$input.show()
				.attr('type', 'text')
				.val($celda.text().trim());
		}
		
		// Configuramos la modal
		$('#id_gen_aux').val(id_gen);
		$('#campo_edicion').val(campo);
		$('#nombreGrupoEdicion').text(nombreGrupo);
		
		// Mostrar la modal
		$('#warning-alert-modal').modal('show');
	});

	// El handler del botón de guardar - SIMPLIFICADO
	$('#btnGuardarEdicion').off('click');
	$('#btnGuardarEdicion').on('click', function() {
		var campo = $('#campo_edicion').val();
		var valor;
		
		switch(campo) {
			case 'mod_gen':
				valor = $('#select_modalidad').val();
				break;
			case 'ini_gen':
				var fecha = $('#input_fecha').val(); 
				if (fecha) {
					var partes = fecha.split('-');
					valor = partes[2] + '/' + partes[1] + '/' + partes[0];
					console.log('Fecha inicio original:', fecha);
					console.log('Fecha inicio formateada:', valor);
				}
				break;
			case 'fin_gen':
				var fechaFin = $('#input_fecha_fin').val(); 
				if (fechaFin) {
					var partes = fechaFin.split('-');
					valor = partes[2] + '/' + partes[1] + '/' + partes[0];
					console.log('Fecha fin original:', fechaFin);
					console.log('Fecha fin formateada:', valor);
				}
				break;
			default:
				valor = $('#valor_edicion').val();
		}
		
		if (!valor || !valor.trim()) {
			toastr.error('Por favor, ingrese un valor válido');
			return;
		}
		
		$.ajax({
			url: 'server/controlador_grupo2.php',
			type: 'POST',
			data: {
				accion: 'Cambio',
				id_gen_aux: $('#id_gen_aux').val(),
				campo: campo,
				valor: valor
			},
			success: function(response) {
				console.log('Respuesta:', response);
				
				var res = typeof response === 'string' ? JSON.parse(response) : response;
				
				if (res.resultado === 'success') {
					toastr.success('Datos actualizados correctamente');
					$('#warning-alert-modal').modal('hide');
					obtener_datos();
				} else if (res.resultado === 'deleted') {
					toastr.warning('Registro eliminado por valor vacío');
					obtener_datos();
				} else {
					console.error('Error en la respuesta:', res);
					toastr.error('Error al guardar: ' + (res.mensaje || 'Error desconocido'));
				}
			},
			error: function(xhr, status, error) {
				console.error('Error en la petición:', {
					error: error,
					status: status,
					response: xhr.responseText
				});
				toastr.error('Error en la conexión. Por favor, intente nuevamente');
			}
		});
	});
</script>

<!-- ELIMINACION / OCULTAR generacion -->
<script>
	// Handler para eliminar registro
	$(document).on('click', '.btn-eliminar', function(e) {
		e.stopPropagation();
		
		var id_gen = $(this).data('id-gen');
		var $fila = $(this).closest('tr');
		var nombreGrupo = $fila.find('td:eq(3) a').text().trim();
		
		swal({
			title: "¿Estás seguro?",
			text: 'Se ocultará el grupo "' + nombreGrupo + '"',
			icon: "warning",
			buttons: {
				cancel: "Cancelar",
				confirm: "Sí, ocultar"
			},
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'server/controlador_grupo2.php',
					type: 'POST',
					data: {
						accion: 'eliminar',
						id_gen: id_gen
					},
					success: function(response) {
						$fila.fadeOut(300, function() {
							$(this).remove();
						});
						
						swal("Grupo ocultado correctamente", "El grupo ha sido ocultado", "success", {
							button: "Aceptar",
						});
					},
					error: function() {
						swal("Error", "Error al ocultar el grupo", "error", {
							button: "Aceptar",
						});
					}
				});
			}
		});
	});
</script>
<!-- FIN ELIMINACION / OCULTAR generacion -->