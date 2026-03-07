<?php  
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
?>

<div class="row">

	<div class="col-2">
		<table class="table table-sm letraPequena table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>CDE</th>
					<th>INFORMES</th>
					<th>CITADOS</th>
					<th>%</th>
				</tr>
				
			</thead>

			<tbody>

				<?php  
					$total = 0;
	    			$total2 = 0;
	    			$sqlPlanteles = "
                        SELECT *
                        FROM plantel
                        WHERE id_cad1 = '$cadena'
                        ORDER BY nom_pla ASC
                    ";

                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                    	$id_pla = $filaPlanteles['id_pla'];
                ?>
                		<tr>
                			<td><?php echo $filaPlanteles['nom_pla']; ?></td>

                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_informes( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux = $datos['datos']['total'];
				                    echo $total_aux;

				                    $total = $total + $total_aux;
				                ?>
                			</td>
                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_citados( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux2 = $datos['datos']['total'];
				                    echo $total_aux2;

				                    $total2 = $total2 + $total_aux2;
				                ?>
                			</td>

                			<td>

                				<?php  
                					$porcentaje = ($total_aux != 0) ? ($total_aux2/$total_aux)*100 : 0;
                					$color_porcentaje;
                					if ($porcentaje <= 50) {
									    $color_porcentaje = 'bg-danger';
									} elseif ($porcentaje > 50 && $porcentaje < 70) {
									    $color_porcentaje = 'bg-warning';
									} else {
									    $color_porcentaje = 'bg-success';
									}
                				?>


                				<div style="width: 100px;">
                					<div class="progress-bar <?php echo $color_porcentaje; ?>" role="progressbar" style="width: <?php echo round($porcentaje, 1); ?>%;" aria-valuenow="<?php echo round($porcentaje, 1); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($porcentaje, 1); ?>%
                					</div>
                				</div>
                			</td>
                		</tr>
                <?php
                    }
	    		?>

	    		<tr>
	    			<td>Total</td>
	    			<td><?php echo $total; ?></td>
	    			<td><?php echo $total2; ?></td>
	    			<td></td>
	    		</tr>
			</tbody>
		</table>
	</div>

	<div class="col-3"></div>

	<div class="col-2">
		<table class="table table-sm letraPequena table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>CDE</th>
					<th>CITADOS</th>
					<th>ENTREVISTADOS</th>
					<th>%</th>
				</tr>
				
			</thead>

			<tbody>

				<?php  
					$total = 0;
	    			$total2 = 0;
	    			$sqlPlanteles = "
                        SELECT *
                        FROM plantel
                        WHERE id_cad1 = '$cadena'
                        ORDER BY nom_pla ASC
                    ";

                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                    	$id_pla = $filaPlanteles['id_pla'];
                ?>
                		<tr>
                			<td><?php echo $filaPlanteles['nom_pla']; ?></td>

                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_citados( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux = $datos['datos']['total'];
				                    echo $total_aux;

				                    $total = $total + $total_aux;
				                ?>
                			</td>
                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_entrevistados( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux2 = $datos['datos']['total'];
				                    echo $total_aux2;

				                    $total2 = $total2 + $total_aux2;
				                ?>
                			</td>

                			<td>

                				<?php  
                					$porcentaje = ($total_aux != 0) ? ($total_aux2/$total_aux)*100 : 0;
                					$color_porcentaje;
                					if ($porcentaje <= 50) {
									    $color_porcentaje = 'bg-danger';
									} elseif ($porcentaje > 50 && $porcentaje < 70) {
									    $color_porcentaje = 'bg-warning';
									} else {
									    $color_porcentaje = 'bg-success';
									}
                				?>


                				<div style="width: 100px;">
                					<div class="progress-bar <?php echo $color_porcentaje; ?>" role="progressbar" style="width: <?php echo round($porcentaje, 1); ?>%;" aria-valuenow="<?php echo round($porcentaje, 1); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($porcentaje, 1); ?>%
                					</div>
                				</div>
                			</td>
                		</tr>
                <?php
                    }
	    		?>

	    		<tr>
	    			<td>Total</td>
	    			<td><?php echo $total; ?></td>
	    			<td><?php echo $total2; ?></td>
	    			<td></td>
	    		</tr>
			</tbody>
		</table>
	</div>

	
</div>


<div class="row">
	<div class="col-2">
		<table class="table table-sm letraPequena table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>CDE</th>
					<th>ENTREVISTADOS</th>
					<th>PROCESOS</th>
					<th>%</th>
				</tr>
				
			</thead>

			<tbody>

				<?php  
					$total = 0;
	    			$total2 = 0;
	    			$sqlPlanteles = "
                        SELECT *
                        FROM plantel
                        WHERE id_cad1 = '$cadena'
                        ORDER BY nom_pla ASC
                    ";

                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                    	$id_pla = $filaPlanteles['id_pla'];
                ?>
                		<tr>
                			<td><?php echo $filaPlanteles['nom_pla']; ?></td>

                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_entrevistados( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux = $datos['datos']['total'];
				                    echo $total_aux;

				                    $total = $total + $total_aux;
				                ?>
                			</td>
                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_procesos( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux2 = $datos['datos']['total'];
				                    echo $total_aux2;

				                    $total2 = $total2 + $total_aux2;
				                ?>
                			</td>

                			<td>

                				<?php  
                					$porcentaje = ($total_aux != 0) ? ($total_aux2/$total_aux)*100 : 0;
                					$color_porcentaje;
                					if ($porcentaje <= 50) {
									    $color_porcentaje = 'bg-danger';
									} elseif ($porcentaje > 50 && $porcentaje < 70) {
									    $color_porcentaje = 'bg-warning';
									} else {
									    $color_porcentaje = 'bg-success';
									}
                				?>


                				<div style="width: 100px;">
                					<div class="progress-bar <?php echo $color_porcentaje; ?>" role="progressbar" style="width: <?php echo round($porcentaje, 1); ?>%;" aria-valuenow="<?php echo round($porcentaje, 1); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($porcentaje, 1); ?>%
                					</div>
                				</div>
                			</td>
                		</tr>
                <?php
                    }
	    		?>

	    		<tr>
	    			<td>Total</td>
	    			<td><?php echo $total; ?></td>
	    			<td><?php echo $total2; ?></td>
	    			<td></td>
	    		</tr>
			</tbody>
		</table>
	</div>

	<div class="col-3"></div>

	<div class="col-2">
		<table class="table table-sm letraPequena table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>CDE</th>
					<th>PROCESOS</th>
					<th>REGRESOS</th>
					<th>%</th>
				</tr>
				
			</thead>

			<tbody>

				<?php  
					$total = 0;
	    			$total2 = 0;
	    			$sqlPlanteles = "
                        SELECT *
                        FROM plantel
                        WHERE id_cad1 = '$cadena'
                        ORDER BY nom_pla ASC
                    ";

                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                    	$id_pla = $filaPlanteles['id_pla'];
                ?>
                		<tr>
                			<td><?php echo $filaPlanteles['nom_pla']; ?></td>

                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_procesos( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux = $datos['datos']['total'];
				                    echo $total_aux;

				                    $total = $total + $total_aux;
				                ?>
                			</td>
                			<td>
                				<?php 
				                    $sql = "SELECT obtener_total_regresos( '$inicio', '$fin', $id_pla ) AS total";
				                    //echo $sql;
				                    $datos = obtener_datos_consulta($db, $sql);
				                    $total_aux2 = $datos['datos']['total'];
				                    echo $total_aux2;

				                    $total2 = $total2 + $total_aux2;
				                ?>
                			</td>

                			<td>

                				<?php  
                					$porcentaje = ($total_aux != 0) ? ($total_aux2/$total_aux)*100 : 0;
                					$color_porcentaje;
                					if ($porcentaje <= 50) {
									    $color_porcentaje = 'bg-danger';
									} elseif ($porcentaje > 50 && $porcentaje < 70) {
									    $color_porcentaje = 'bg-warning';
									} else {
									    $color_porcentaje = 'bg-success';
									}
                				?>


                				<div style="width: 100px;">
                					<div class="progress-bar <?php echo $color_porcentaje; ?>" role="progressbar" style="width: <?php echo round($porcentaje, 1); ?>%;" aria-valuenow="<?php echo round($porcentaje, 1); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($porcentaje, 1); ?>%
                					</div>
                				</div>
                			</td>
                		</tr>
                <?php
                    }
	    		?>

	    		<tr>
	    			<td>Total</td>
	    			<td><?php echo $total; ?></td>
	    			<td><?php echo $total2; ?></td>
	    			<td></td>
	    		</tr>
			</tbody>
		</table>
	</div>
</div>