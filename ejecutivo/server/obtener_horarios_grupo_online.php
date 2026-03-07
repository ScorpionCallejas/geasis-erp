<?php  
	//ARCHIVO VIA AJAX PARA OBTENER HORARIOS  DE GRUPO EN CICLOS CON ESTATUS ACTIVO PARA INSCRIPCION
	//inscripcion.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_gru = $_POST['grupo'];

	$sqlHorario = "
		SELECT * 
    	FROM sub_hor
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_gru1 = '$id_gru'
		GROUP BY nom_mat
	";

	// $sqlHorario = "
	// 	SELECT * 
    // 	FROM sub_hor
    //     INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
    //     INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    //     INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	// 	WHERE nom_gru = 'GRUPO-FUSIONADO-ECA-QRO'
	// 	GROUP BY nom_mat
	// ";

	// echo $sqlHorario;

	// echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);

?>

<style>


.botonHijo {
  position: absolute;
  right: -10%;
  top: -20%;
}

.botonPadre {
  position: relative;
}
</style>

<table class="table table-sm text-center table-hover animated fadeIn table-bordered" cellspacing="0" width="100%">
	<thead class="grey lighten-2">
		<tr class="filasHorario">
			
			<th>Clave de Grupo</th>
			<th>Profesor</th>
			<th>Alumnos</th>
			<th>Materia</th>

		</tr>
	</thead>

	<tbody >

		<?php
		

			while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
				$id_sub_hor = $filaHorario['id_sub_hor'];
		?>

			<tr class="filasHorario" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>" style="height: 25px;">
	
				<td class="letraPequena">
					<?php echo $filaHorario['nom_sub_hor']; ?>
				</td>


				<td class="letraPequena">
					<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
				</td>

				<td class="letraPequena">
					<?php  

						// echo 'id_fus2: '.$filaHorario['id_fus2'];
						if ( ( $filaHorario['id_fus2'] != null ) || $filaHorario['id_fus2'] != '' ) {
					    	// echo 'entre';
					    	echo obtener_conteo_datos_fusion_server( $filaHorario['id_fus2'] )['total_alumnos'];

					    } else {


					    	
							echo obtenerCantidadAlumnosInscritosServer( $id_sub_hor );

					    }
						
					?>
				</td>

				<td class="botonPadre letraPequena">
					<?php echo $filaHorario['nom_mat']; ?>
					
					<div class="waves-effect btn-sm btn-info btn-floating botonHijo sub_hor" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>">
						<i class="fas fa-plus-circle" title="Agregar este horario"></i>
					</div>

				</td>

			</tr>


		<?php

			}
			//FIN WHILE
		?>
		
	</tbody>

</table>