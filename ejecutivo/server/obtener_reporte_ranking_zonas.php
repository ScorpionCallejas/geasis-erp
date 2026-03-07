<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
?>

<h3>
    RANKING ZONAS
</h3>

<span class="letraPequena grey-text"><?php echo obtenerTituloReporte($inicio, $fin); ?></span>

<style>
	.table td, .table th {
		padding: 2px 4px !important;
		font-size: 10px !important;
		vertical-align: middle !important;
	}
	
	.table thead th {
		padding: 4px !important;
		font-size: 10px !important;
		font-weight: bold !important;
		background-color: #f8f9fa;
	}
	
	.badge-fisico {
		background-color: #28a745;
		color: white;
		font-size: 8px;
		padding: 2px 4px;
	}
	
	.badge-virtual {
		background-color: #ffc107;
		color: black;
		font-size: 8px;
		padding: 2px 4px;
	}
	
	.dataTables_wrapper .dataTables_filter input {
		padding: 2px 4px;
		font-size: 11px;
		margin-left: 5px;
	}
	
	.dataTables_wrapper .dataTables_filter {
		font-size: 11px;
	}
	
	.dataTables_wrapper .dataTables_info {
		font-size: 10px;
		padding-top: 5px;
	}
	
	.dataTables_wrapper .dataTables_paginate {
		font-size: 10px;
		padding-top: 5px;
	}
	
	.dataTables_wrapper .dataTables_length select {
		padding: 2px;
		font-size: 10px;
	}
</style>

<hr>

<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4 text-center">
		<img src="../img/logoDorado.png" width="150px">
	</div>
	<div class="col-md-4"></div>
</div>

<div class="row">
    <div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-sm" id="tabla_ranking_zonas">
				<thead>
					<tr>
						<th>#</th>
						<th>ZONA</th>
						<th>CDE</th>
						<th>TIPO</th>
            			<th>REG MÓD</th>
						<th>REG COM</th>
						<th>REG TOT</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$contador = 1;
					
					// Obtener todos los planteles con su zona
					$sqlPlanteles = "
						SELECT 
							p.*,
							z.nom_zon,
							obtener_registros_modulo_plantel(p.id_pla, '$inicio', '$fin') as reg_modulo,
							obtener_registros_comerciales_plantel3(p.id_pla, '$inicio', '$fin') as reg_comerciales,
							(obtener_registros_modulo_plantel(p.id_pla, '$inicio', '$fin') + 
							 obtener_registros_comerciales_plantel3(p.id_pla, '$inicio', '$fin')) as reg_totales
						FROM plantel p
						INNER JOIN zona z ON z.id_zon = p.id_zon2
						WHERE z.id_cad3 = $cadena
						ORDER BY reg_totales DESC
					";
					
					$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
					
					while($filaPlantel = mysqli_fetch_assoc($resultadoPlanteles)) {
						$id_pla = $filaPlantel['id_pla'];
						$nom_pla = $filaPlantel['nom_pla'];
						$nom_zona = $filaPlantel['nom_zon'];
						$tip_pla = $filaPlantel['tip_pla'];
						$reg_modulo = isset($filaPlantel['reg_modulo']) ? $filaPlantel['reg_modulo'] : 0;
						$reg_comerciales = isset($filaPlantel['reg_comerciales']) ? $filaPlantel['reg_comerciales'] : 0;
						$reg_totales = isset($filaPlantel['reg_totales']) ? $filaPlantel['reg_totales'] : 0;
						
						// Abreviar nombre de zona (primeras 3 palabras)
						$palabras_zona = explode(' ', $nom_zona);
						$zona_abreviada = implode(' ', array_slice($palabras_zona, 0, 3));
						
						$badge_tipo = ($tip_pla == 'Físico') ? 'badge-fisico' : 'badge-virtual';
					?>
						<tr>
							<td><?php echo $contador; ?></td>
							<td><?php echo strtoupper($zona_abreviada); ?></td>
							<td><?php echo $nom_pla; ?></td>
							<td style="text-align: center;">
								<span class="badge <?php echo $badge_tipo; ?>">
									<?php echo strtoupper($tip_pla); ?>
								</span>
							</td>
							<td style="text-align: center;"><?php echo $reg_modulo; ?></td>
							<td style="text-align: center;"><?php echo $reg_comerciales; ?></td>
							<td style="text-align: center;">
								<a href="<?php
									$url = "registros.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin";
									echo $url;
								?>" target="_blank">
									<strong><?php echo $reg_totales; ?></strong>
								</a>	
							</td>
						</tr>
					<?php
						$contador++;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>

<hr>

<br><br>
<br><br><br><br><br><br><br><br><br>

<script>
    $('#tabla_ranking_zonas').DataTable({
        paging: true,
        pageLength: 50,
        searching: true,
        ordering: true,
        order: [[6, 'desc']], // Ordenar por REG TOTALES descendente
        dom: 'frtip', // Solo filtro, tabla, info y paginación
        language: {
            search: '',
            searchPlaceholder: 'Buscar...',
            lengthMenu: '_MENU_',
            info: '_START_-_END_ de _TOTAL_',
            infoEmpty: '0-0 de 0',
            infoFiltered: '(filtrado de _MAX_)',
            paginate: {
                first: '«',
                last: '»',
                next: '›',
                previous: '‹'
            },
            zeroRecords: 'Sin resultados'
        }
    });
</script>