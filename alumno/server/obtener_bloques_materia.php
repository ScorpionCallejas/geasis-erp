<?php  
	//ARCHIVO VIA AJAX PARA OBTENER BLOQUES DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');

	$id_mat = $_POST['materia'];	
	$id_alu_ram = $_POST['id_alu_ram'];	

	$sqlBloques = "
		SELECT * 
		FROM alu_hor
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    	INNER JOIN bloque ON bloque.id_mat6 = materia.id_mat
    	WHERE id_mat6 = '$id_mat' AND id_alu_ram1 = '$id_alu_ram'
    	ORDER BY id_blo ASC
	";

	$resultadoBloques = mysqli_query($db, $sqlBloques);
	//echo $sqlBloques;


	$resultadoTotalBloques = mysqli_query($db, $sqlBloques);

	$totalBloques = mysqli_num_rows($resultadoTotalBloques);
	$contador = 1;


	$resultadoBloquesMateria = mysqli_query($db, $sqlBloques);

	$filaBloquesMateria = mysqli_fetch_assoc($resultadoBloquesMateria);

	$nom_mat = $filaBloquesMateria['nom_mat'];

	//echo $totalBloques;
?>

<div class="container animated fadeInDown">


	<div class="row grey text-center">
		<h3 class="white-text p-2">
			<i class="fas fa-dot-circle"></i>
			<?php  
				echo $nom_mat;
			?>
		<h3/>
	</div>

	<br>

	<?php
		for ($i = 0; $i < $totalBloques/2; $i++) {
	?>
			<div class="row">
				
				<?php
					while($filaBloques = mysqli_fetch_assoc($resultadoBloques)){
						$id_blo = $filaBloques['id_blo'];
						$nom_blo = $filaBloques['nom_blo'];
				?>


				<?php 

					$sqlForo = "
						SELECT * 
						FROM cal_act 
						INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2 
						INNER JOIN foro ON foro.id_for = foro_copia.id_for1
						INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
						WHERE id_alu_ram4 = '$id_alu_ram' AND id_blo = '$id_blo'
					";



					$resultadoForo = mysqli_query($db, $sqlForo);

					$resultadoTotalForos = mysqli_query($db, $sqlForo);

					$totalForos = mysqli_num_rows($resultadoTotalForos);

				?>


				<?php  
			    	$sqlEntregable = "
						SELECT * 
						FROM cal_act 
						INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2 
						INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
						INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5 
						WHERE id_alu_ram4 = '$id_alu_ram' AND id_blo = '$id_blo'
			
					";
					//echo $sqlEntregable;

					$resultadoEntregable = mysqli_query($db, $sqlEntregable);

					$resultadoTotalEntregables = mysqli_query($db, $sqlEntregable);

					$totalEntregables = mysqli_num_rows($resultadoTotalEntregables);

				?>

 
				<?php  
			    
			    	$sqlExamen = "
						SELECT * 
						FROM cal_act 
						INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2 
						INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
						INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
						WHERE id_alu_ram4 = '$id_alu_ram' AND id_blo = '$id_blo'
					";


					$resultadoExamen = mysqli_query($db, $sqlExamen);


					$resultadoTotalExamenes = mysqli_query($db, $sqlExamen);

					$totalExamenes = mysqli_num_rows($resultadoTotalExamenes);

				?>


					<div class="col-md-6">
						<!-- Card -->
						<div class="card">

						  <!-- Card image -->
						  

						  <!-- Button -->
						  <a class="btn-floating btn-action ml-auto mr-4 grey " href="bloque_contenido.php?id_blo=<?php echo $filaBloques['id_blo'].'&id_alu_ram='.$id_alu_ram; ?> " target="_blank" title="Ir al <?php echo $nom_blo; ?>">
						  	<i class="fas fa-chevron-right pl-1"></i>
						  </a>

						  <!-- Card content -->
						  <div class="card-body">
							
							<!-- TITULO E INDICADOR -->
						  	<div class="row">
						  		<!-- TITULO -->
						  		<div class="col-md-6">
						  			<a href="bloque_contenido.php?id_blo=<?php echo $filaBloques['id_blo'].'&id_alu_ram='.$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Contenido del bloque <?php echo $filaBloques['nom_blo']; ?> ">
										<h4 class="card-title">
											<?php
												echo $filaBloques['nom_blo'];
											?>
									    </h4>
								    </a>
						  		</div>
						  		<!-- FIN TITULO -->
								
								<!-- INDICADOR -->
						  		<?php

						  			$sqlActividades = "SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
										FROM alu_ram
										INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
										INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
										INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
										INNER JOIN materia ON materia.id_mat = bloque.id_mat6
										INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
										INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
										INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
										WHERE id_alu_ram4 = '$id_alu_ram' AND id_blo = '$id_blo'
										UNION
										SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
										FROM alu_ram
										INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
										INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
										INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
										INNER JOIN materia ON materia.id_mat = bloque.id_mat6
										INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
										INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
										INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
										WHERE id_alu_ram4 = '$id_alu_ram' AND id_blo = '$id_blo'
										UNION
										SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
										FROM alu_ram
										INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
										INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
										INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
										INNER JOIN materia ON materia.id_mat = bloque.id_mat6
										INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
										INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
										INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
										WHERE id_alu_ram4 = '$id_alu_ram' AND id_blo = '$id_blo'
										ORDER BY inicio ASC
									";


									$resultadoActividades = mysqli_query($db, $sqlActividades);
									$totalActividadesRealizadas = 0;
									while($filaActividades = mysqli_fetch_assoc($resultadoActividades)){
										if ($filaActividades['fecha'] != NULL) {
											$totalActividadesRealizadas++;
										}
									}

									//echo $totalActividadesRealizadas;
						  		?>


						  		<div class="col-md-6">
						  			<p>
										Total Actividades: 
										<?php  
											$totalActividades = $totalExamenes+$totalEntregables+$totalForos;
											echo $totalActividades;
										?>
								    </p>

								    
								    <br>
									
									<?php

							    		if ($totalActividades == 0) {
							    	?>		
							    			<span class="grey-text">
							    				Avance
							    			</span>
							    			<h1 class="text-center" title="No hay actividades en este bloque">
												0
							    			</h1>
							    	
									<?php
							    		} else{
							    	?>
							    			<span class="grey-text">
							    				Avance
							    			</span>
											<h1 class="text-center" title="Avance del bloque">
							    	<?php
							    				echo round(($totalActividadesRealizadas/$totalActividades)*100, 2)."%";
							    	?>
											</h1>
									
							    	<?php
							    		}	
							    	?>

								    
						  			
						  		</div>
						  		<!-- FIN INDICADOR -->
						  	</div>
						  	<!-- FIN TITULO E INDICADOR -->

						    

						    
						    
						    <hr>
						    <!-- Text -->
						    <p class="card-text" style="height: 40px;">
						    	<?php  
						    		echo $filaBloques['des_blo'];
						    	?>
						    </p>

						  </div>

						  <!-- Card footer -->
						  <div class="rounded-bottom grey p-2">
						    <div class="row">
						    	<!-- TEORIA -->
						    	<div class="col-md-6 text-left">
						    		<!-- VIDEOS -->
									<?php

										$sqlVideo = "
											SELECT *
											FROM video
											WHERE id_blo1 = '$id_blo'
										";


										$resultadoVideo = mysqli_query($db, $sqlVideo);

										$resultadoTotalVideos = mysqli_query($db, $sqlVideo);

										$totalVideos = mysqli_num_rows($resultadoTotalVideos);


									?>
									<span class="white-text">
										<i class="fas fa-video" title="Videos"></i> - 
										<?php  
											echo $totalVideos;
										?> videos
									</span>
						    		<!-- FIN VIDEOS -->
						    		<br>

						    		<!-- WIKIS -->
						    		<?php  
								    	$sqlWiki = "SELECT * FROM wiki WHERE id_blo2 = '$id_blo'";
								    	$resultadoWiki = mysqli_query($db, $sqlWiki);

								    	$resultadoTotalWiki = mysqli_query($db, $sqlWiki);

								    	$totalWikis = mysqli_num_rows($resultadoTotalWiki);

									?>

									<span class="white-text">
										<i class="fab fa-wikipedia-w" title="Wikis"></i> - 
										<?php  
											echo $totalWikis;
										?> wikis
									</span>									
						    		<!-- FIN WIKIS -->

						    		<br>


						    		<!-- ARCHIVOS -->
						    		<?php  
								    	$sqlArchivo = "
											SELECT *
											FROM archivo
											WHERE id_blo3 = '$id_blo'
										";

										$resultadoArchivo = mysqli_query($db, $sqlArchivo);

										$resultadoTotalArchivos = mysqli_query($db, $sqlArchivo);

										$totalArchivos = mysqli_num_rows($resultadoTotalArchivos);

									?>

									<span class="white-text">
										<i class="fas fa-file-alt" title="Archivos"></i> - 
										<?php  
											echo $totalArchivos;
										?> archivos
									</span>									
						    		<!-- FIN ARCHIVOS -->
						    	</div>
						    	<!-- FIN TEORIA -->
								

								<!-- PRACTICA -->
						    	<div class="col-md-6">
									<!-- FOROS -->
						    		

									<span class="white-text">
										<i class="fas fa-comment-dots" title="Foros"></i> - 
										<?php  
											echo $totalForos;
										?> foros
									</span>									
						    		<!-- FIN FOROS -->
						    		<br>

						    		<!-- ENTREGABLES -->
						    		
									<span class="white-text">
										<i class="fas fa-file" title="Entregables"></i> - 
										<?php  
											echo $totalEntregables;
										?> entregables
									</span>									
						    		<!-- FIN ENTREGABLES -->
						    		<br>


						    		<!-- EXAMENES -->
						    		

									<span class="white-text">
										<i class="fas fa-diagnoses" title="Exámenes"></i> - 
										<?php  
											echo $totalExamenes;
										?> examenes
									</span>									
						    		<!-- FIN EXAMENES -->

						    		
						    	</div>
						    	<!-- FIN PRACTICA -->
						    </div>
						  </div>

						</div>
						<!-- Card -->
					</div>
				<?php
					if ($contador%2 == 0) {
				?>	

						</div><hr><div class="row">
						
						

				<?php
				    }
				    $contador++;
			
					} 

				?>

	<?php
		}

	?>
</div>