<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE FALTAS DEL ALUMNO
	//historial_asistencia.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$id_mat = $_POST['id_mat'];
	$id_alu_ram = $_POST['id_alu_ram'];

	$sql = "
		SELECT * 
		FROM asistencia
		WHERE id_mat5 = '$id_mat' AND id_alu_ram3 = '$id_alu_ram' AND tip_asi = 'Falta'
	";
	$resultado = mysqli_query($db, $sql);
	$i = 1;
?>
	

<br>
<br>
<br>
<br>
<br>
<br>
<h1 class="h1" id="titulo"></h1>
<table class="table table-hover animated fadeInDown">
	<thead>
		<th class="text-danger">Fechas donde registras faltas</th>
	</thead>

	<tbody>
		<?php
			while($fila = mysqli_fetch_assoc($resultado)){
				
		?>
			<tr>
				<td>
					<?php 
						$fec_asi = $fila['fec_asi'];
						echo $i." - ".fechaFormateada($fec_asi); $i++;
					?>
				</td>
			</tr>

		<?php
			}

		?>		
	</tbody>
</table>





