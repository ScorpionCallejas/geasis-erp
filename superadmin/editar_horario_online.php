<?php  
		

  include('inc/header.php');
?>
<!-- CONTENIDO -->
<?php 
	$id_gru = $_GET['id_gru'];
	$sqlHorario = "
		SELECT * 
    	FROM sub_hor
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_gru1 = '$id_gru'

	";

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	$resultadoHorarioNav = mysqli_query($db, $sqlHorario);
	$filaHorarioNav = mysqli_fetch_assoc($resultadoHorarioNav);
	$id_ram= $filaHorarioNav['id_ram'];
	$id_cic= $filaHorarioNav['id_cic'];
	$nom_cic= $filaHorarioNav['nom_cic'];
	$nom_ram= $filaHorarioNav['nom_ram'];
	$nom_gru= $filaHorarioNav['nom_gru'];


	
?>
<!-- 

<style>
	.botonHijo {
		position: absolute;
		right: 5%;
		top: 5%; 
	}

	.botonPadre {
		position: relative;
	}
</style>
 -->
 <!-- CONTENIDO --><!--INICIO DE DESPLIEGUE DE TITULO-->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Edición de horario">
			<i class="fas fa-bookmark"></i> 
			Edición de horario
		</span>
		<br>
		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al Inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="ramas.php" title="Vuelve a las Ramas"><span class="text-white">Ramas</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="ciclos.php?id_ram=<?php echo "$id_ram"; ?>" title="Vuelve a los Ciclos"><span class="text-white">Ciclos</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="grupos.php?id_cic=<?php echo "$id_cic"; ?>" title="Vuelve a los Grupos"><span class="text-white">Grupos</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a href="" title="Estás aquí"><span style="color: black;">Horario</span></a>		
		</div>
	</div>
	<div class="col text-right">

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Rama de Estudio <?php echo $nom_ram; ?>">
				<i class="fas fa-certificate"></i>
				Carrera: <?php echo $nom_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Ciclo en el que estás">
			<i class="fas fa-certificate"></i>
			 Ciclo: <?php echo $nom_cic; ?>
		</span>	
		<br>
		<br>
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Grupo">
			<i class="fas fa-certificate"></i>
			 Grupo: <?php echo $nom_gru; ?>
		</span>
	</div>
</div>
<!-- FIN DE DESPLIEGUE DE TITULO-->

<br>


	<div class="row">
		<div class="col-md-3">
			
		</div>
		<div class="col-md-6">

			<form id="horarioFormulario">
				
				<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTable">
					<thead class="grey lighten-2">
						<tr>
							<th>#</th>
							<th>Profesor</th>
							<th>Materia</th>
						</tr>
					</thead>

					<tbody >

						<?php
							$i = 1;

							while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
								$id_sub_hor = $filaHorario['id_sub_hor'];
								$id_pro = $filaHorario['id_pro1'];

						?>

							<tr>
								<td>
									<?php echo $i; $i++;  ?>
								</td>


								<td>
									<input type="hidden" value="<?php echo $id_sub_hor; ?>" name="id_sub_hor[]">
									<select class="mdb-select md-form colorful-select dropdown-primary" name="profesor[]">
										<?php  
											$sqlProfesores = "
												SELECT *
												FROM profesor
												WHERE id_pla2 = '$plantel'
											";

											$resultadoProfesores = mysqli_query($db, $sqlProfesores);
											$i = 1;
											while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
												if ($id_pro == $filaProfesores['id_pro']) {
										?>
													<option value="<?php echo $filaProfesores['id_pro']; ?>" selected><?php echo $filaProfesores['nom_pro']; ?>
													</option>

										<?php
												}else{
										?>
													<option value="<?php echo $filaProfesores['id_pro']; ?>"><?php echo $filaProfesores['nom_pro']; ?>
													</option>
										<?php
												}

										?>
											

											

										<?php
											}


										?>
									  	
									  	
									</select>
								</td>


								<td>
									<?php echo $filaHorario['nom_mat']; ?>
								</td>
							</tr>
						<?php  
							}
						?>

					</tbody>

				</table>


				<button class="btn btn-info btn-block" type="submit">
					Actualizar
				</button>

			</form>
		</div>
		<div class="col-md-3">
			
		</div>
		
			


	</div>


<!-- FIN CONTENIDO -->
<?php  

  include('inc/footer.php');

?>


<script>
	$('.mdb-select').materialSelect();
</script>


<script>
	
	//EDICION DE HORARIO

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE HORARIO
	$('#horarioFormulario').on('submit', function(event) {
		event.preventDefault();

		$.ajax({
		
			url: 'server/editar_horario_online.php',
			type: 'POST',
			data: new FormData(horarioFormulario), 
			processData: false,
			contentType: false,
			cache: false,
			success: function(respuesta){
				console.log(respuesta);

				if (respuesta == 'Exito') {
					swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
					  window.location.reload();
					});
					
				}
			}
		});
	});				
			
	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE HORARIO
</script>