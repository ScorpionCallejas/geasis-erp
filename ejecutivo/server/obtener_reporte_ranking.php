<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
?>

<h3>
    REPORTE RANKING
</h3>

<span class="letraPequena grey-text"><?php echo obtenerTituloReporte($inicio, $fin); ?></span>

<style>
	.table td, .table th {
		padding: 0;
	}
</style>

<hr>

<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4 text-center">
		<img src="../img/logoDorado.png" width="200px">
	</div>
	<div class="col-md-4"></div>
</div>

<div class="row">
	<div class="col md-2"></div>
	<div class="col-md-8">
		<!--  -->

		
		<div class="table-responsive">

			<table class="table table-bordered" id="tabla_registros">
				<thead>
					<tr>
						<th>#</th>
						<th>CDE</th>
						<th>REG ADMINISTRATIVOS</th>
            			<th>REG MODULO</th>
						<th>REG ÁREA COMERCIAL</th>
						<th>REG TOTALES</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$contador = 1;
					$total_registros_administrativos = 0;
          			$total_registros_modulo = 0;
					$total_registros_comerciales = 0;
					$total_registros_totales = 0;
					$inicio_semana_anterior = restarDias($inicio, 7);
					$fin_semana_anterior = restarDias($inicio, 1); // Un día antes del inicio
					
					$sqlPlanteles = "
						SELECT *,
						obtener_registros_administrativos_plantel(id_pla, '$inicio', '$fin') AS registros_administrativos,
            			obtener_registros_modulo_plantel(id_pla, '$inicio', '$fin') AS registros_modulo,
						obtener_registros_comerciales_plantel3(id_pla, '$inicio', '$fin') AS registros_comerciales,
						(obtener_registros_modulo_plantel(id_pla, '$inicio', '$fin') + obtener_registros_administrativos_plantel(id_pla, '$inicio', '$fin') + obtener_registros_comerciales_plantel3(id_pla, '$inicio', '$fin')) AS registros_totales
						FROM plantel
						WHERE id_cad1 = 1
						ORDER BY registros_totales DESC
					";

					// echo $sqlPlanteles;

					$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
					
					while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
						$id_pla = $filaPlanteles['id_pla'];
						$registros_administrativos = $filaPlanteles['registros_administrativos'];
            			$registros_modulo = $filaPlanteles['registros_modulo'];
						$registros_comerciales = $filaPlanteles['registros_comerciales'];
						$registros_totales = $filaPlanteles['registros_totales'];
					
						$total_registros_administrativos += $registros_administrativos;
						$total_registros_modulo += $registros_modulo;
            			$total_registros_comerciales += $registros_comerciales;
						$total_registros_totales += $registros_totales;
					
						$color_celda = obtenerColorFila($contador, $registros_totales);
					?>
						<tr>
							<td><?php echo $contador; ?></td>
							<td style="<?php echo $color_celda; ?>">🕋 <?php echo $filaPlanteles['nom_pla']; ?></td>
							<td style="text-align: center;"><?php echo $registros_administrativos; ?></td>
              				<td style="text-align: center;"><?php echo $registros_modulo; ?></td>
							<td style="text-align: center;"><?php echo $registros_comerciales; ?></td>

							<td style="<?php echo $color_celda; ?> text-align: center;">
								<a href="<?php
									$url = "registros.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
									echo $url;
								?>" target="_blank">
									<?php echo $registros_totales; ?>
								</a>	
							
							</td>
						</tr>
					<?php
						$contador++;
					}
					?>
					
					<tr>
						<td>--</td>
						<td>--</td>
						<td>--</td>
						<td>--</td>
						<td>--</td>
            			<td>--</td>
					</tr>
					<tr>
						<td>--</td>
						<td>--</td>
						<td>--</td>
						<td>--</td>
						<td>--</td>
            			<td>--</td>
					</tr>
					<tr>
						<td>--</td>
						<td>--</td>
						<td>--</td>
            			<td>--</td>
						<td>--</td>
						<td>--</td>
					</tr>
					<tr style="font-size: 1.2em; font-weight: bold;">
						<td>--</td>
						<td style="text-align: center;">Gran Total</td>
						<td style="text-align: center;"><?php echo $total_registros_administrativos; ?></td>
            			<td style="text-align: center;"><?php echo $total_registros_modulo; ?></td>
						<td style="text-align: center;"><?php echo $total_registros_comerciales; ?></td>
						<td style="text-align: center;">
							<?php echo $total_registros_totales; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<!--  -->
	</div>

	<div class="col-md-2"></div>
</div>

<hr>

<br><br>
<br><br><br><br><br><br><br><br><br>


<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
    $('#tabla_registros').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        ordering: false, // Desactiva la ordenación
        // dom: 'Bfrtip',
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'REPORTE REGISTROS',
                className: 'btn-sm btn-success'
            },
            {
                extend: 'pdfHtml5',
                title: 'REPORTE REGISTROS',
                className: 'btn-sm btn-danger',
                orientation: 'landscape',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                }
            }
        ],
        language: {
            search: 'Buscar' // Cambia el texto del buscador
        },
        info: false // Esto desactiva la información del pie de la tabla

    });
</script>