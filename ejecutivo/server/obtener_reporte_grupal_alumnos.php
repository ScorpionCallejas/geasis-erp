<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_gen = $_POST['id_gen'];
	
	$sqlGeneracion = "
		SELECT * 
		FROM generacion 
		INNER JOIN rama ON rama.id_ram = generacion.id_ram5
		WHERE id_gen = $id_gen
	";
	$datosGeneracion = obtener_datos_consulta( $db, $sqlGeneracion )['datos'];
?>

<style>
.tabla-asistencia {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tabla-asistencia th {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 8px;
    text-align: center;
    font-weight: bold;
    color: #495057;
}

.tabla-asistencia td {
    border: 1px solid #dee2e6;
    padding: 6px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

.tabla-asistencia .nombre-completo {
    text-align: left;
    max-width: 200px;
    font-weight: bold;
}

.tabla-asistencia .numero {
    width: 35px;
    text-align: center;
}

.tabla-asistencia .matricula {
    width: 80px;
    text-align: center;
}

.tabla-asistencia .dia-semana {
    width: 35px;
    text-align: center;
    min-width: 30px;
}

.tabla-asistencia .estatus {
    width: 100px;
    text-align: center;
    font-weight: bold;
}

.tabla-asistencia .telefono {
    width: 300px;
    text-align: center;
}

.fila-total {
    background-color: #e9ecef;
    font-weight: bold;
}

.fila-total-general {
    background-color: #6c757d;
    color: white;
    font-weight: bold;
}

.tabla-asistencia tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.tabla-asistencia tbody tr:hover {
    background-color: #e3f2fd;
}

.info-grupo {
    font-family: Arial, sans-serif;
    font-size: 11px;
    color: #6c757d;
    margin-bottom: 15px;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    background-color: #f8f9fa;
    max-width: 500px;
}

.info-grupo td {
    padding: 4px 8px;
    border: none;
}

.info-grupo td:first-child {
    font-weight: bold;
    background-color: #e9ecef;
    width: 80px;
    font-size: 10px;
}
</style>

<!-- INFORMACIÓN DEL GRUPO -->
<table class="info-grupo" cellpadding="4" cellspacing="0">
  <tr>
    <td>GRUPO</td>
    <td><?php echo $datosGeneracion['nom_gen']; ?></td>
  </tr>
  <tr>
    <td>HORARIO</td>
    <td><?php echo $datosGeneracion['hor_gen']; ?></td>
  </tr>
  <tr>
    <td>DÍAS</td>
    <td><?php echo $datosGeneracion['dia_gen']; ?></td>
  </tr>
  <tr>
    <td>PROGRAMA</td>
    <td><?php echo $datosGeneracion['nom_ram']; ?></td>
  </tr>
  <tr>
    <td>INICIO</td>
    <td><?php echo $datosGeneracion['ini_gen']; ?></td>
  </tr>
  <tr>
    <td>FIN</td>
    <td><?php echo $datosGeneracion['fin_gen']; ?></td>
  </tr>
</table>



<div class="table-responsive">
	<table class="table-bordered tabla-asistencia">
		<!-- CABEECERA -->
		<thead>
			<tr>
				<th>#</th>
				<th>MATRICULA</th>
				<th>NOMBRE</th>

				<th class="dia-semana">L</th>
				<th class="dia-semana">M</th>
				<th class="dia-semana">M</th>
				<th class="dia-semana">J</th>
				<th class="dia-semana">V</th>
				<th class="dia-semana">S</th>
				<th class="dia-semana">D</th>

				<th class="dia-semana">L</th>
				<th class="dia-semana">M</th>
				<th class="dia-semana">M</th>
				<th class="dia-semana">J</th>
				<th class="dia-semana">V</th>
				<th class="dia-semana">S</th>
				<th class="dia-semana">D</th>

				<th>ESTATUS</th>
				<th>TELÉFONOS</th>


			</tr>
		</thead>
		<!-- F CABECERA -->

		<!-- CUERPO -->
		<tbody>
			<?php 
				$sqlAlumnos = " 
					SELECT * 
					FROM vista_alumnos  
					WHERE id_gen1 = $id_gen 
					ORDER BY estatus_general ASC, nom_alu ASC
				";  
				
				$conteoEstatus = array(); 
				$contador = 1; 
				$totalGeneral = 0;
				$resultadoAlumnos = mysqli_query( $db, $sqlAlumnos ); 
				
				// Agrupar datos por estatus
				$alumnosPorEstatus = array();
				while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){
					$estatus = $filaAlumnos['estatus_general'];
					if (!isset($alumnosPorEstatus[$estatus])) {
						$alumnosPorEstatus[$estatus] = array();
					}
					$alumnosPorEstatus[$estatus][] = $filaAlumnos;
				}
				
				// Mostrar datos agrupados con totales
				foreach ($alumnosPorEstatus as $estatus => $alumnos) {
					// Mostrar alumnos del estatus actual
					foreach ($alumnos as $filaAlumnos) {
			?>
						<tr>
							<td class="numero"><?php echo $contador; ?></td>
							<td class="matricula"><?php echo $filaAlumnos['id_alu_ram']; ?></td>
							<td class="nombre-completo"><?php echo $filaAlumnos['nom_alu']; ?></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="dia-semana"></td>
							<td class="estatus"><?php echo $filaAlumnos['estatus_general']; ?></td>
							<td class="telefono"><?php echo $filaAlumnos['tel_alu'].' / '.$filaAlumnos['tel2_alu']; ?></td>
						</tr>
			<?php
						$contador++;
						$totalGeneral++;
					}
					
					// Mostrar fila de total para el estatus actual
					$totalEstatus = count($alumnos);
			?>
					<tr class="fila-total">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="text-align: right; padding-right: 10px;">Total Grupo:</td>
						<td style="text-align: center;"><?php echo $totalEstatus; ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
			<?php
				}
			?>
		</tbody>
		<!-- F CUERPO -->
		
		<!-- TOTAL GENERAL -->
		<tfoot>
			<tr class="fila-total-general">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align: right; padding-right: 10px;">Total General:</td>
				<td style="text-align: center;"><?php echo $totalGeneral; ?></td>
			</tr>
		</tfoot>
	</table>
</div>