<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	$id_eje = $_POST['id_eje'];
	//fechaDia( $fecha );
?>

<style>
	.table {
		width: 100%;
		border-collapse: collapse;
	}
	.table th, .table td {
		/* border: 1px solid black; */
		padding: 8px;
		text-align: left;
	}
	.table-responsive {
		width: 100%;
		overflow-x: auto;
	}
</style>

<div class="table-responsive">
	<table class="table">
		<tr>
			<th>EJECUTIVO</th>
			<th>TIPO DE CITA</th>
			<th>NOMBRE</th>
			<th>NÚMERO</th>
			<th>MODALIDAD</th>
			<th>MERCADO</th>
			<th>ESTATUS</th>
			<th>FOLIO</th>
			<th>OBSERVACIONES</th>
		</tr>
		<?php
			$startTime = strtotime('9:00 AM');
			$endTime = strtotime('7:00 PM');
			$timeIncrement = 30 * 60;

			while ($startTime <= $endTime) {
				echo '<tr>';
				echo '<td>' . date('h:i A', $startTime) . '</td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '</tr>';

				$startTime += $timeIncrement;
			}
		?>
	</table>
</div>

	