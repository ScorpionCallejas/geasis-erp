<?php  
	//ARCHIVO VIA AJAX PARA OBTENER HORARIOS  DE GRUPO EN CICLOS CON ESTATUS ACTIVO PARA INSCRIPCION
	//inscripcion.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_gru = $_POST['grupo'];	

	$sqlHorario = "
		SELECT * 
    	FROM sub_hor
    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_gru1 = '$id_gru'
		GROUP BY nom_mat

	";

	$total = obtener_datos_consulta( $db, $sqlHorario )['total'];

	if ( $total == 0 ) {
		
		$sqlHorario = "
			SELECT * 
	    	FROM sub_hor
	        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			WHERE id_gru1 = '$id_gru'
			GROUP BY nom_mat

		";


	}

	// echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);

?>

<style>


.botonHijo {
  position: absolute;
  right: -30%;
  top: 0%;
}


.botonPadre {
  position: relative;
}
</style>
			<table class="table table-sm text-center table-hover animated fadeIn table-bordered" cellspacing="0" width="100%">
				<thead class="grey lighten-2">
					<tr class="filasHorario">
						
						<th class="letraPequena font-weight-normal">Clave de Grupo</th>
						<th class="letraPequena font-weight-normal">Profesor</th>
						<th class="letraPequena font-weight-normal">Alumnos</th>
						<th class="letraPequena font-weight-normal">Materia</th>
						<th class="letraPequena font-weight-normal">Salón</th>
						<th class="letraPequena font-weight-normal">Lunes</th>
						<th class="letraPequena font-weight-normal">Martes</th>
						<th class="letraPequena font-weight-normal">Miercoles</th>
						<th class="letraPequena font-weight-normal">Jueves</th>
						<th class="letraPequena font-weight-normal">Viernes</th>
						<th class="letraPequena font-weight-normal">Sabado</th>
						<th class="letraPequena font-weight-normal">Domingo</th>
					</tr>
				</thead>

				<tbody >

					<?php
					

						while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
							$id_sub_hor = $filaHorario['id_sub_hor'];

					?>

						<tr class="filasHorario" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>">
				
							<td class="letraPequena font-weight-normal">
								<?php echo $filaHorario['nom_sub_hor']; ?>
							</td>

							<td class="letraPequena font-weight-normal">
								<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
							</td>


							<td class="letraPequena font-weight-normal">
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


							<td class="letraPequena font-weight-normal">
								<?php echo $filaHorario['nom_mat']; ?>
							</td>



							<td class="letraPequena font-weight-normal">
								<?php  

									$sqlSalon = "
										SELECT *
										FROM salon
										INNER JOIN sub_hor ON sub_hor.id_sal1 = salon.id_sal
										WHERE id_sub_hor = '$id_sub_hor'
									";

									$resultadoSalon = mysqli_query( $db, $sqlSalon );


									if ( $resultadoSalon ) {
										
										$totalSalon = mysqli_num_rows( $resultadoSalon );

										if ( $totalSalon > 0 ) {
											
											$resultadoSalon2 = mysqli_query( $db, $sqlSalon );

											$filaSalon = mysqli_fetch_assoc( $resultadoSalon2 );

											echo $filaSalon['nom_sal'];


										} else {
											echo "N/A";
										}

									} else {

										echo $sqlSalon;
									
									}
								?>			
							</td>

							<?php
								$id_sub_hor = $filaHorario['id_sub_hor'];
								
								//LUNES
								$sqlSubHorLunes = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Lunes' AND id_sub_hor1 = '$id_sub_hor'
								";

								//echo $sqlSubHor;
								$resultadoSubHorLunes = mysqli_query($db, $sqlSubHorLunes);

								$filasLunes = mysqli_num_rows($resultadoSubHorLunes);

								if ($filasLunes == 0) {
							?>	
								<td class="letraPequena font-weight-normal">--</td>

							<?php
								}else{
									while($filaSubHorLunes = mysqli_fetch_assoc($resultadoSubHorLunes)){
									
									?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorLunes['ini_hor']."-".$filaSubHorLunes['fin_hor']; 
											?>
											
										</td>
							

							<?php
									}
								}
									
								//MARTES
								$sqlSubHorMartes = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Martes' AND id_sub_hor1 = '$id_sub_hor';
								";

								//echo $sqlSubHor;
								$resultadoSubHorMartes = mysqli_query($db, $sqlSubHorMartes);

								$filasMartes = mysqli_num_rows($resultadoSubHorMartes);

								if ($filasMartes == 0) {
							?>	
								<td class="letraPequena font-weight-normal">--</td>

							<?php
								}else{
									while($filaSubHorMartes = mysqli_fetch_assoc($resultadoSubHorMartes)){
									
									?>
											<td class="letraPequena font-weight-normal">
												<?php 
													echo $filaSubHorMartes['ini_hor']."-".$filaSubHorMartes['fin_hor']; 
												?>
												
											</td>
							

							<?php
									}
								}

								//MIERCOLES
								$sqlSubHorMiercoles = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Miércoles' AND id_sub_hor1 = '$id_sub_hor';
								";

								//echo $sqlSubHor;
								$resultadoSubHorMiercoles = mysqli_query($db, $sqlSubHorMiercoles);

								$filasMiercoles = mysqli_num_rows($resultadoSubHorMiercoles);

								if ($filasMiercoles == 0) {
							?>	
								<td class="letraPequena font-weight-normal">--</td>

							<?php
								}else{
									while($filaSubHorMiercoles = mysqli_fetch_assoc($resultadoSubHorMiercoles)){
									
									?>
											<td class="letraPequena font-weight-normal">
												<?php 
													echo $filaSubHorMiercoles['ini_hor']."-".$filaSubHorMiercoles['fin_hor']; 
												?>
												
											</td>
							

							<?php
									}
								}

								//JUEVES
								$sqlSubHorJueves = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Jueves' AND id_sub_hor1 = '$id_sub_hor';
								";

								//echo $sqlSubHor;
								$resultadoSubHorJueves = mysqli_query($db, $sqlSubHorJueves);

								$filasJueves = mysqli_num_rows($resultadoSubHorJueves);

								if ($filasJueves == 0) {
							?>	
								<td class="letraPequena font-weight-normal">--</td>

							<?php
								}else{
									while($filaSubHorJueves = mysqli_fetch_assoc($resultadoSubHorJueves)){
									
									?>
											<td class="letraPequena font-weight-normal">
												<?php 
													echo $filaSubHorJueves['ini_hor']."-".$filaSubHorJueves['fin_hor']; 
												?>
												
											</td>
							

							<?php
									}
								}


								//VIERNES
								$sqlSubHorViernes = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Viernes' AND id_sub_hor1 = '$id_sub_hor';
								";

								//echo $sqlSubHor;
								$resultadoSubHorViernes = mysqli_query($db, $sqlSubHorViernes);

								$filasViernes = mysqli_num_rows($resultadoSubHorViernes);

								if ($filasViernes == 0) {
							?>	
								<td class="letraPequena font-weight-normal">--</td>

							<?php
								}else{
									while($filaSubHorViernes = mysqli_fetch_assoc($resultadoSubHorViernes)){
									
									?>
											<td class="letraPequena font-weight-normal">
												<?php 
													echo $filaSubHorViernes['ini_hor']."-".$filaSubHorViernes['fin_hor']; 
												?>
												
											</td>

							<?php
									}
								}


								//SABADO
								$sqlSubHorSabado = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Sábado' AND id_sub_hor1 = '$id_sub_hor';
								";

								//echo $sqlSubHor;
								$resultadoSubHorSabado = mysqli_query($db, $sqlSubHorSabado);

								$filasSabado = mysqli_num_rows($resultadoSubHorSabado);

								if ($filasSabado == 0) {
							?>	
								<td class="letraPequena font-weight-normal">--</td>

							<?php
								}else{
									while($filaSubHorSabado = mysqli_fetch_assoc($resultadoSubHorSabado)){
									
									?>
											<td class="letraPequena font-weight-normal">
												<?php 
													echo $filaSubHorSabado['ini_hor']."-".$filaSubHorSabado['fin_hor']; 
												?>
												
											</td>
							

							<?php
									}
								}
									

								//DOMINGO
								$sqlSubHorDomingo = "
									SELECT *
							    	FROM sub_hor
							    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
									WHERE dia_hor = 'Domingo' AND id_sub_hor1 = '$id_sub_hor';
								";

								//echo $sqlSubHor;
								$resultadoSubHorDomingo = mysqli_query($db, $sqlSubHorDomingo);

								$filasDomingo = mysqli_num_rows($resultadoSubHorDomingo);

								if ($filasDomingo == 0) {
							?>	
								<td class="botonPadre letraPequena font-weight-normal">
							
									<div class="waves-effect btn-sm btn-info btn-floating botonHijo sub_hor" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>">
										<i class="fas fa-plus-circle fa-2x" title="Agregar este horario"></i>
									</div>

									
									--
								</td>

							<?php
								}else{
									while($filaSubHorDomingo = mysqli_fetch_assoc($resultadoSubHorDomingo)){
									
									?>
											<td class="botonPadre letraPequena font-weight-normal">
							
												<div class="waves-effect btn-floating btn-info btn-sm botonHijo sub_hor" sub_hor="<?php echo $filaHorario['id_sub_hor']; ?>">
													<i class="fas fa-plus-circle" title="Agregar este horario"></i>
												</div>

										
												<?php 
													echo $filaSubHorDomingo['ini_hor']."-".$filaSubHorDomingo['fin_hor']; 
												?>
												
											</td>
							

							<?php
									}
								}
									
					
							?>

						</tr>


					<?php

						}
						//FIN WHILE
					?>
					
					
	
					
				</tbody>

			</table>