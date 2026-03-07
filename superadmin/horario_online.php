<?php  

  include('inc/header.php');
?>
<!-- CONTENIDO -->
<?php 
	$id_gru = $_GET['id_gru'];
	$sqlGrupo = "
		SELECT * 
		FROM grupo
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1 
		WHERE id_gru = '$id_gru'";
	$resultadoGrupo = mysqli_query($db, $sqlGrupo);

	$filaGrupo = mysqli_fetch_assoc($resultadoGrupo);

	$id_ram =  $filaGrupo['id_ram'];
	$nom_ram = $filaGrupo['nom_ram'];
	$nom_cic = $filaGrupo['nom_cic'];
	$nom_gru = $filaGrupo['nom_gru'];
	$id_cic = $filaGrupo['id_cic1'];
?>


 <!-- CONTENIDO --><!--INICIO DE DESPLIEGUE DE TITULO-->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Grupos con los que cunetas en la Rama">
			<i class="fas fa-bookmark"></i> 
			Horario
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
		<br>
	</div>
</div>
<!-- FIN DE DESPLIEGUE DE TITULO-->

<!-- BOTON FLOTANTE AGREGAR CONTENIDO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 15%; right: 2%; position: fixed;" id="agregarFila"><i class="fas fa-plus fa-1x" title="Agregar Fila" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONTENIDO-->

	
	<div class="row">
		<div class="col-md-3">

		</div>

		<div class="col-md-4">
			<form id="formularioHorario">
				<table class="table table-striped table-sm text-center">
					<thead class="grey white-text">
						<tr>
							<th>#</th>
							<th>Profesor</th>
							<th>Materia</th>
							<th>Acción</th>
						</tr>
					</thead>

					<tbody id="panza">
						
						<tr>


								<td>
									
									<span class="numero"></span>
								</td>

								
								<td>
									<?php  
										$sqlProfesores = "SELECT * FROM profesor WHERE id_pla2 = $plantel";
										$resultadoProfesores = mysqli_query($db, $sqlProfesores);
										
									?>

									

									<select  id="profesor" class="mdb-select selectForm" name="profesor[]" form="formularioHorario">
										<?php  
											while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
										?>
											<option value="<?php echo $filaProfesores["id_pro"]; ?>"><?php echo $filaProfesores["nom_pro"]; ?></option>
										<?php  
											}
										?>
									</select>
									
								</td>

								<td>
									<?php  
										$sqlMaterias = "SELECT * FROM materia WHERE id_ram2 = $id_ram";
										$resultadoMaterias = mysqli_query($db, $sqlMaterias);
										
									?>

									<select id="materia" class="mdb-select selectForm" name="materia[]" form="formularioHorario">
										<?php  
											while($filaMaterias = mysqli_fetch_assoc($resultadoMaterias)){
										?>
											<option value="<?php echo $filaMaterias["id_mat"]; ?>"><?php echo $filaMaterias["nom_mat"]; ?></option>
										<?php  
											}
										?>
									</select>
								</td>


								<td>
									<div>

									  <a href="#" class="eliminacionFila" title="Eliminar fila">

									    <i class="fas fa-times fa-2x red-text"></i>
									  </a>
									</div>

								</td>

								
							
								
						</tr>
						

						<!-- BOTON FLOTANTE AGREGAR HORARIO-->
						<button type="submit" class="btn-floating btn-lg  flotante btn-info" id="btn_formulario"><i class="fas fa-save" title="Agregar Profesor" ></i></button>
						<!-- FIN BOTON FLOTANTE AGREGAR HORARIO-->

						
					</tbody>



				</table>
			</form>
			
		</div>
		
		

	</div>


<!-- FIN CONTENIDO -->
<?php  

  include('inc/footer.php');

?>



<script>
	$( function() {
		$('.mdb-select').materialSelect();

		// var arregloNumeros;
		// arregloNumeros = [$(".numero")];

		numeracion();
		

	});

	function numeracion(){
		for(var i = 0; i < $(".numero").length; i++){
			$(".numero").eq(i).text(i+1);
		}
	}

	
	
</script>

<script>

	//console.log($( ".checkboxes" ).last()));

	$("#agregarFila").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$('.mdb-select').materialSelect('destroy');
		

		//var maxNumero = parseInt($( ".numero" ).last().attr("maxNumero"));

		if (!$( ".checkboxes").last().attr("maximo")) {
			var maximo = 0;
		}else{
			var maximo = parseInt($( ".checkboxes" ).last().attr("maximo"));
		}
		
		$("#panza").append(
			
			'<tr>'+	
					'<td>'+
						'<span class="numero"></span>'+
						
					'</td>'+
					'<td>'+
						<?php  
							$sqlProfesores = "SELECT * FROM profesor WHERE id_pla2 = $plantel";
							$resultadoProfesores = mysqli_query($db, $sqlProfesores);
							
						?>
						'<select  id="profesor" class="mdb-select selectForm" name="profesor[]" form="formularioHorario">'+
							<?php  
								while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
							?>
								'<option value="<?php echo $filaProfesores["id_pro"]; ?>"><?php echo $filaProfesores["nom_pro"]; ?></option>'+
							<?php  
								}
							?>
						'</select>'+
						
					'</td>'+

					'<td>'+
						<?php  
							$sqlMaterias = "SELECT * FROM materia WHERE id_ram2 = $id_ram";
							$resultadoMaterias = mysqli_query($db, $sqlMaterias);
							
						?>

						'<select id="materia" class="mdb-select selectForm" name="materia[]" form="formularioHorario">'+
							<?php  
								while($filaMaterias = mysqli_fetch_assoc($resultadoMaterias)){
							?>
								'<option value="<?php echo $filaMaterias["id_mat"]; ?>"><?php echo $filaMaterias["nom_mat"]; ?></option>'+
							<?php  
								}
							?>
						'</select>'+
					'</td>'+

					'<td>'+
						'<div>'+

						  '<a href="#" class="eliminacionFila" title="Eliminar fila">'+

						    '<i class="fas fa-times fa-2x red-text"></i>'+
						  '</a>'+
						'</div>'+

					'</td>'+

					
					
			'</tr>'
		);
		
		$('.mdb-select').materialSelect();

		numeracion();

		$(".eliminacionFila").on('click',  function(event) {
			event.preventDefault();
			//console.log($(this));
			$(this).parent().parent().parent().remove("");
			numeracion();
		});
		

	});


	
	

	$("#formularioHorario").on('submit', function(event) {
		event.preventDefault();
		//var horarioFormulario = $(this);
	

		$("#btn_formulario").attr('disabled','disabled');

		$.ajax({
			url: 'server/agregar_horario_online.php?id_gru=<?php echo $id_gru;?>',
			type: 'POST',
			data: new FormData(formularioHorario),
			processData: false,
			contentType: false,
			cache: false,
			success: function(respuesta){
				$(".spinnito").remove();
				if (respuesta == 'true') {
					swal("Agregado correctamente", "Continuar", "success").
					then((value) => {
					  window.location.href='grupos.php?id_cic=<?php echo "$id_cic";?>';
					});
				}else{
					console.log(respuesta);
					swal ( "¡Faltan datos!" ,  "¡Olvidaste algo...!" ,  "error" );
				}
			}
		});
		//console.log($("#materia").val());
	});
 

	
</script>