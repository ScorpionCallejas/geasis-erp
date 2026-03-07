<?php  
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

	$id_pla = $_POST['id_pla'];
	$estatus = $_POST['estatus'];

	// echo $id_pla;
  function obtenerSemaforoTramites($porcentaje) {
      if ($porcentaje >= 0 && $porcentaje <= 90) {
          return 'style="background-color: #FFC7CE;"'; // Rojo tenue
      } elseif ($porcentaje > 90 && $porcentaje < 100) {
          return 'style="background-color: #FFEB9C;"'; // Amarillo tenue
      } elseif ($porcentaje == 100) {
          return 'style="background-color: #C6EFCE;"'; // Verde tenue
      } else {
          return '';
      }
  }

  function obtenerAzul($index) {
    $blue_colors = [
      '#ADD8E6', // Light Blue
      '#87CEEB', // Sky Blue
      '#00BFFF', // Deep Sky Blue
      '#1E90FF', // Dodger Blue
      '#4169E1', // Royal Blue
      '#D9E2F3', // Navy Blue
      '#87CECC', // Medium Blue
    ];

    if ($index < 1 || $index > 6) {
      return $blue_colors[5];
    }
    return $blue_colors[$index - 1];
  }

  

?>

<link href="assets/libs/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />
<script src="assets/libs/fullcalendar/main.min.js"></script>

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
  </style>

  <div class="row">
    <div class="col-md-12">
      <!--  -->
      
      <div class="table-responsive">

        <!--  -->
        <div class="table-responsive">
            <!--  -->
            <!--  -->
            <table class="table table-bordered" id="tabla_planeacion_inicios">
				<thead class="" style="background-color: #002060; color: white;">
					<tr>
						<th class="letraPequena">SEMANA</th>
						<th class="letraPequena">MES</th>
						<th class="letraPequena">GRUPO</th>
						<th class="letraPequena">PROGRAMA</th>
						<th class="letraPequena">MODALIDAD</th>
						<th class="letraPequena">DIAS</th>
						<th class="letraPequena">FECHA DE INICIO</th>
						<th class="letraPequena">HORARIO</th>
						<th class="letraPequena">REGISTRADOS</th>
						<th class="letraPequena">META</th>
						<th class="letraPequena">%</th>
						<th class="letraPequena">REG.</th>
						<th class="letraPequena">PRES</th>
						<th class="letraPequena">REINGRESOS</th>
						<th class="letraPequena">GRADUADOS</th>
						<th class="letraPequena">N PRES</th>
						<th class="letraPequena">%</th>
						<th class="letraPequena">PERDIDA</th>
					</tr>
				</thead>

				<!--  -->
				<tbody>
					<?php
						if( $estatus == 'En curso' ){
							$sql = "
								SELECT 
									generacion.*,
									rama.*,
									plantel.*,
									obtener_estatus_presentacion_generacion(id_gen, 'PRESENTADO') AS total_presentados,
									obtener_estatus_presentacion_generacion(id_gen, 'NP') AS total_nps,
									obtener_estatus_presentacion_generacion(id_gen, 'REINGRESO') AS total_reingresos,
									(SELECT COUNT(*) FROM alu_ram WHERE alu_ram.id_gen1 = generacion.id_gen) AS total_registros,
									MONTH(ini_gen) as mes_numero
								FROM generacion
								INNER JOIN rama ON rama.id_ram = generacion.id_ram5
								INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
								WHERE id_pla = '$id_pla' 
									AND CURDATE() >= ini_gen 
									AND CURDATE() <= fin_gen
									AND est_gen = 'Inactivo'
								ORDER BY ini_gen ASC
							";
						}else if( $estatus == 'Fin curso' ){
							$sql = "
								SELECT 
									generacion.*,
									rama.*,
									plantel.*,
									obtener_estatus_presentacion_generacion(id_gen, 'PRESENTADO') AS total_presentados,
									obtener_estatus_presentacion_generacion(id_gen, 'NP') AS total_nps,
									obtener_estatus_presentacion_generacion(id_gen, 'REINGRESO') AS total_reingresos,
									(SELECT COUNT(*) FROM alu_ram WHERE alu_ram.id_gen1 = generacion.id_gen) AS total_registros,
									MONTH(ini_gen) as mes_numero
								FROM generacion
								INNER JOIN rama ON rama.id_ram = generacion.id_ram5
								INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
								WHERE id_pla = '$id_pla' 
									AND CURDATE() > fin_gen
									AND est_gen = 'Inactivo'
								ORDER BY ini_gen ASC
							";
						}else if( $estatus == 'Por comenzar' ){
							$sql = "
								SELECT 
									generacion.*,
									rama.*,
									plantel.*,
									obtener_estatus_presentacion_generacion(id_gen, 'PRESENTADO') AS total_presentados,
									obtener_estatus_presentacion_generacion(id_gen, 'NP') AS total_nps,
									obtener_estatus_presentacion_generacion(id_gen, 'REINGRESO') AS total_reingresos,
									(SELECT COUNT(*) FROM alu_ram WHERE alu_ram.id_gen1 = generacion.id_gen) AS total_registros,
									MONTH(ini_gen) as mes_numero
								FROM generacion
								INNER JOIN rama ON rama.id_ram = generacion.id_ram5
								INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
								WHERE id_pla = '$id_pla' 
									AND CURDATE() < ini_gen
									AND est_gen = 'Inactivo'
								ORDER BY ini_gen ASC
							";
						}
						
						$resultado = mysqli_query($db, $sql);
						$mes_actual = null;
						$totales_mes = array(
							'registrados' => 0,
							'meta' => 0,
							'reg' => 0,
							'pres' => 0,
							'reingresos' => 0,
							'n_pres' => 0,
							'perdida' => 0
						);

						while($fila = mysqli_fetch_assoc($resultado)) {
							// Si cambia el mes, imprimir totales del mes anterior
							if ($mes_actual !== null && $mes_actual != obtenerMesServer($fila['ini_gen'])) {
								// Agregar fila vacía antes de la fila de totales
								?>
								<tr style="background-color: white; height: 25px;">
									<?php for ($i = 0; $i < 18; $i++) { ?>
										<td class="letraPequena"></td>
									<?php } ?>
								</tr>

								
								<tr class="total-mes" style="background-color: #FFD965; color: black;">
									<td class="letraPequena"></td>
									<td class="letraPequena"></td>
									<td class="letraPequena"></td>
									<td class="letraPequena"></td>
									<td class="letraPequena"></td>
									<td class="letraPequena"></td>
									<td class="letraPequena"></td>
									<td class="letraPequena">TOTAL</td>
									<td class="letraPequena"><?php echo $totales_mes['registrados']; ?></td>
									<td class="letraPequena"><?php echo $totales_mes['meta']; ?></td>
									<td class="letraPequena" style="background-color: <?php 
										if ($totales_mes['meta'] > 0) {
											$porcentaje = ($totales_mes['registrados'] / $totales_mes['meta']) * 100;
											if ($porcentaje < 70) {
												echo '#FFB3B3'; // Rojo tenue
											} elseif ($porcentaje < 80) {
												echo '#FFFFCC'; // Amarillo tenue
											} else {
												echo '#CCFFCC'; // Verde tenue
											}
										}
									?>;"><?php 
										echo ($totales_mes['meta'] > 0) ? 
											round(($totales_mes['registrados']/$totales_mes['meta'])*100) . '%' : 
											'0%'; 
									?></td>
									<td class="letraPequena"><?php echo $totales_mes['reg']; ?></td>
									<td class="letraPequena"><?php echo $totales_mes['pres']; ?></td>
									<td class="letraPequena"><?php echo $totales_mes['reingresos']; ?></td>
									<td class="letraPequena">0</td>
									<td class="letraPequena"><?php echo $totales_mes['n_pres']; ?></td>
									<td class="letraPequena" style="background-color: <?php 
										if ($totales_mes['reg'] > 0) {
											$porcentaje = ($totales_mes['pres']/$totales_mes['reg']) * 100;
											if ($porcentaje < 70) {
												echo '#FFB3B3'; // Rojo tenue
											} elseif ($porcentaje < 80) {
												echo '#FFFFCC'; // Amarillo tenue
											} else {
												echo '#CCFFCC'; // Verde tenue
											}
										}
									?>;"><?php 
										echo ($totales_mes['reg'] > 0) ? 
											round(($totales_mes['pres']/$totales_mes['reg'])*100) . '%' : 
											'0%'; 
									?></td>
									<td class="letraPequena">$ <?php echo number_format($totales_mes['perdida'], 2); ?></td>
								</tr>

								<tr style="background-color: #002060; color: white;">
									<td class="letraPequena">SEMANA</td>
									<td class="letraPequena">MES</td>
									<td class="letraPequena">GRUPO</td>
									<td class="letraPequena">PROGRAMA</td>
									<td class="letraPequena">MODALIDAD</td>
									<td class="letraPequena">DIAS</td>
									<td class="letraPequena">FECHA DE INICIO</td>
									<td class="letraPequena">HORARIO</td>
									<td class="letraPequena">REGISTRADOS</td>
									<td class="letraPequena">META</td>
									<td class="letraPequena">%</td>
									<td class="letraPequena">REG.</td>
									<td class="letraPequena">PRES</td>
									<td class="letraPequena">REINGRESOS</td>
									<td class="letraPequena">GRADUADOS</td>
									<td class="letraPequena">N PRES</td>
									<td class="letraPequena">%</td>
									<td class="letraPequena">PERDIDA</td>
								</tr>

								<?php
								// Reiniciar totales
								$totales_mes = array_fill_keys(array_keys($totales_mes), 0);
							}

							$mes_actual = obtenerMesServer($fila['ini_gen']);
							
							// Calcular pérdida (asumiendo $14,000 por NP)
							$perdida = $fila['total_nps'] * 1500;
							
							// Acumular totales del mes
							$totales_mes['registrados'] += $fila['total_registros'];
							$totales_mes['meta'] += $fila['met_gen'];
							$totales_mes['reg'] += $fila['total_registros'];
							$totales_mes['pres'] += $fila['total_presentados'];
							$totales_mes['reingresos'] += $fila['total_reingresos'];
							$totales_mes['n_pres'] += $fila['total_nps'];
							$totales_mes['perdida'] += $perdida;
							?>
							<tr style="background: white; color: black;">
								<td class="letraPequena"><?php echo obtenerSemanaTrabajo2($fila['ini_gen']); ?></td>
								<td class="letraPequena"><?php echo getMonth(obtenerMesServer($fila['ini_gen'])); ?></td>
								
								<td class="letraPequena">
									<a href="alumnos.php?id_gen=<?php echo $fila['id_gen']; ?>" class="text-primary" target="_blank">
										<?php echo $fila['nom_gen']; ?>
									</a>
									<div class="form-check form-switch ms-2 d-inline-block">
										<input type="checkbox" class="form-check-input switch-generacion" 
											id="switch_<?php echo $fila['id_gen']; ?>"
											data-id="<?php echo $fila['id_gen']; ?>"
											<?php echo ($fila['est_gen'] == 1) ? 'checked' : ''; ?>
											<?php 
											// Aquí validaremos los permisos del ejecutivo
											if (!$tienePermiso) echo 'disabled'; 
											?>>
									</div>
								</td>
								
								<td class="letraPequena"><?php echo $fila['nom_ram']; ?></td>
								<td class="letraPequena" style="background-color: <?php echo $fila['mod_gen'] == 'Online' ? '#01B0F0' : '#5A9BD5'; ?>; color: black; padding: 2px 5px;">
									<?php echo $fila['mod_gen'] == 'Online' ? 'ONLINE' : 'Presencial'; ?>
								</td>
								<td class="letraPequena"><?php echo $fila['dia_gen']; ?></td>
								<td class="letraPequena"><?php echo fechaFormateadaCompacta4($fila['ini_gen']); ?></td>
								<td class="letraPequena"><?php echo $fila['hor_gen']; ?></td>
								<td class="letraPequena"><?php echo $fila['total_registros']; ?></td>
								<td class="letraPequena"><?php echo $fila['met_gen']; ?></td>
								<td class="letraPequena" style="background-color: <?php 
									if ($fila['met_gen'] > 0) {
										$porcentaje = ($fila['total_registros'] / $fila['met_gen']) * 100;
										if ($porcentaje < 70) {
											echo '#FFB3B3'; // Rojo tenue
										} elseif ($porcentaje < 80) {
											echo '#FFFFCC'; // Amarillo tenue
										} else {
											echo '#CCFFCC'; // Verde tenue
										}
									}
								?>;">
									<?php 
										echo ($fila['met_gen'] > 0) ? 
											round(($fila['total_registros']/$fila['met_gen'])*100) . '%' : 
											'0%'; 
									?>
								</td>
								<td class="letraPequena"><?php echo $fila['total_registros']; ?></td>
								<td class="letraPequena"><?php echo $fila['total_presentados']; ?></td>
								<td class="letraPequena"><?php echo $fila['total_reingresos']; ?></td>
								<td class="letraPequena">0</td>
								<td class="letraPequena"><?php echo $fila['total_nps']; ?></td>
								<td class="letraPequena" style="background-color: <?php 
									if ($fila['total_registros'] > 0) {
										$porcentaje = ($fila['total_presentados']/$fila['total_registros']) * 100;
										if ($porcentaje < 70) {
											echo '#FFB3B3'; // Rojo tenue
										} elseif ($porcentaje < 80) {
											echo '#FFFFCC'; // Amarillo tenue
										} else {
											echo '#CCFFCC'; // Verde tenue
										}
									} else {
										echo '#FFB3B3';
									}
								?>;">
								
									<?php 
										echo ($fila['total_registros'] > 0) ? 
											round(($fila['total_presentados']/$fila['total_registros'])*100) . '%' : 
											'0%'; 
									?>
								</td>
								<td class="letraPequena <?php echo ($perdida > 0) ? 'bg-danger' : ''; ?>">
									$ <?php echo number_format($perdida, 2); ?>
								</td>

							</tr>
							<?php
						}
						
						// Imprimir totales del último mes
						if ($mes_actual !== null) {
							// Agregar fila vacía antes de la fila de totales
							?>
							<tr style="background-color: white; height: 25px;">
								<?php for ($i = 0; $i < 18; $i++) { ?>
									<td class="letraPequena"></td>
								<?php } ?>
							</tr>
							<tr class="total-mes" style="background-color: #FFD965; color: black;">
								<td class="letraPequena"></td>
								<td class="letraPequena"></td>
								<td class="letraPequena"></td>
								<td class="letraPequena"></td>
								<td class="letraPequena"></td>
								<td class="letraPequena"></td>
								<td class="letraPequena"></td>
								<td class="letraPequena">TOTAL</td>
								<td class="letraPequena"><?php echo $totales_mes['registrados']; ?></td>
								<td class="letraPequena"><?php echo $totales_mes['meta']; ?></td>
								<td class="letraPequena" style="background-color: <?php 
									if ($totales_mes['meta'] > 0) {
										$porcentaje = ($totales_mes['registrados'] / $totales_mes['meta']) * 100;
										if ($porcentaje < 70) {
											echo '#FFB3B3'; // Rojo tenue
										} elseif ($porcentaje < 80) {
											echo '#FFFFCC'; // Amarillo tenue
										} else {
											echo '#CCFFCC'; // Verde tenue
										}
									}
								?>;"><?php 
									echo ($totales_mes['meta'] > 0) ? 
										round(($totales_mes['registrados']/$totales_mes['meta'])*100) . '%' : 
										'0%'; 
								?></td>
								<td class="letraPequena"><?php echo $totales_mes['reg']; ?></td>
								<td class="letraPequena"><?php echo $totales_mes['pres']; ?></td>
								<td class="letraPequena"><?php echo $totales_mes['reingresos']; ?></td>
								<td class="letraPequena">0</td>
								<td class="letraPequena"><?php echo $totales_mes['n_pres']; ?></td>
								<td class="letraPequena" style="background-color: <?php 
									if ($totales_mes['reg'] > 0) {
										$porcentaje = ($totales_mes['pres']/$totales_mes['reg']) * 100;
										if ($porcentaje < 70) {
											echo '#FFB3B3'; // Rojo tenue
										} elseif ($porcentaje < 80) {
											echo '#FFFFCC'; // Amarillo tenue
										} else {
											echo '#CCFFCC'; // Verde tenue
										}
									}
								?>;"><?php 
									echo ($totales_mes['reg'] > 0) ? 
										round(($totales_mes['pres']/$totales_mes['reg'])*100) . '%' : 
										'0%'; 
								?></td>
								<td class="letraPequena">$ <?php echo number_format($totales_mes['perdida'], 2); ?></td>
							</tr>
							<?php
						}
					?>
				</tbody>

				<!--  -->
          	</table>

            <!--  -->
            <!--  -->
        </div>
        <!--  -->

      </div>
      
      <!--  -->
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
    $('#tabla_planeacion_inicios').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        ordering: false, // Desactiva la ordenación
        // dom: 'Bfrtip',
    	dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE PLANEACION DE INICIOS',
                className: 'btn-sm btn-success'
            },
        ],
        language: {
            search: 'Buscar' // Cambia el texto del buscador
        },
        info: false // Esto desactiva la información del pie de la tabla

    });


    $('.columna_certificacion').on('change', function(e) {
        e.preventDefault();
        
        // Capturamos los datos del selector
        var id_gen_aux = $(this).data('id_gen');
        var campo = $(this).data('columna');
        var valor = $(this).val();

        //alert("ID generado: " + id_gen + ", Columna: " + columna + ", Valor: " + valor);

        var accion = 'Cambio';
        
        // Enviamos por AJAX
        $.ajax({
            url: 'server/controlador_grupo.php',
            method: 'POST',
            dataType: 'json',
            data: {
                accion,
                id_gen_aux,
                campo,
                valor
            },
            success: function(response) {
                console.log('Actualización exitosa:', response);

                toastr.success('Guardado correctamente');
            },
            error: function(xhr, status, error) {
                console.error('Error en la actualización:', error);
            }
        });
    });
</script>