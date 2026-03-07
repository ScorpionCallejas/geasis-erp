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
	$id_cic =  $filaGrupo['id_cic'];
	$nom_ram =  $filaGrupo['nom_ram'];
	$nom_gru =  $filaGrupo['nom_gru'];
	$nom_cic =  $filaGrupo['nom_cic'];
?>

<style>
	.botonHijo {
		position: absolute;
		right: 5%;
		top: 5%; 
	}

	.botonPadre {
		position: relative;
	}

	input {
		font-size: 10px !important;
	}
</style>
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
		</span>	<br>
	</div>
</div>
<!-- FIN DE DESPLIEGUE DE TITULO-->


<!-- BOTON FLOTANTE AGREGAR CONTENIDO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 15%; right: 2%; position: fixed;" id="agregarFila"><i class="fas fa-plus fa-1x" title="Agregar Fila" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONTENIDO-->

	
	<div class="row">
		
		<form id="formularioHorario">
			<table class="table table-striped table-sm text-center">
				<thead>
					<tr class="white-text">
						<th class="letraPequena">#</th>
						<th class="letraPequena">Profesor</th>
						<th class="letraPequena">Materia</th>
						<th class="letraPequena">Lunes</th>
						<th class="letraPequena">Martes</th>
						<th class="letraPequena">Miercoles</th>
						<th class="letraPequena">Jueves</th>
						<th class="letraPequena">Viernes</th>
						<th class="letraPequena">Sabado</th>
						<th class="letraPequena">Domingo</th>
					</tr>
				</thead>

				<tbody id="panza">
					
					<tr>


							<td class="letraPequena">
								
								<span class="numero"></span>
							</td>

								
							<td class="letraPequena">
								<?php  
									$sqlProfesores = "SELECT * FROM profesor WHERE id_pla2 = $plantel";
									$resultadoProfesores = mysqli_query($db, $sqlProfesores);
									
								?>

								

								<select  id="profesor" class="mdb-select" name="profesor[]" form="formularioHorario">
									<?php  
										while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
									?>
										<option value="<?php echo $filaProfesores["id_pro"]; ?>"><?php echo $filaProfesores["nom_pro"]." ".$filaProfesores['app_pro']; ?></option>
									<?php  
										}
									?>
								</select>
								
							</td>

							<td class="letraPequena">
								<?php  
									$sqlMaterias = "SELECT * FROM materia WHERE id_ram2 = $id_ram";
									$resultadoMaterias = mysqli_query($db, $sqlMaterias);
									
								?>

								<select id="materia" class="mdb-select" name="materia[]" form="formularioHorario">
									<?php  
										while($filaMaterias = mysqli_fetch_assoc($resultadoMaterias)){
									?>
										<option value="<?php echo $filaMaterias["id_mat"]; ?>"><?php echo $filaMaterias["nom_mat"]; ?></option>
									<?php  
										}
									?>
								</select>
							</td>

							
							<td class="letraPequena">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="1" name="lunes[]" value="Lunes">
								  <label class="form-check-label" for="1"></label>

								  	<div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_lun[]" dia="Lunes">
										<input  type="time" class="form-control horas" name="fin_hor_lun[]" dia="Lunes">
									 
									</div>
								</div>
							</td>
							


							<td class="letraPequena">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="2" name="martes[]" value="Martes">
								  <label class="form-check-label" for="2"></label>
								  	<div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_mar[]" dia="Martes">
										<input  type="time" class="form-control horas" name="fin_hor_mar[]" dia="Martes">
									 
									</div>
								</div>
							</td>

							<td class="letraPequena">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="3" name="miercoles[]" value="Miércoles">
								  <label class="form-check-label" for="3"></label>
									<div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_mie[]" dia="Miércoles">
										<input  type="time" class="form-control horas" name="fin_hor_mie[]" dia="Miércoles">

									</div>
								</div>
							</td>


							<td class="letraPequena">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="4" name="jueves[]" value="Jueves">
								  <label class="form-check-label" for="4"></label>
								  	<div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_jue[]" dia="Jueves">
										<input  type="time" class="form-control horas" name="fin_hor_jue[]" dia="Jueves">

									</div>
								</div>
							</td>


							<td class="letraPequena">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="5" name="viernes[]" value="Viernes">
								  <label class="form-check-label" for="5"></label>
								  <div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_vie[]">
										<input  type="time" class="form-control horas" name="fin_hor_vie[]">

									</div>
								</div>
							</td>


							<td class="letraPequena">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="6" name="sabado[]" value="Sábado">
								  <label class="form-check-label" for="6"></label>
								  <div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_sab[]">
										<input  type="time" class="form-control horas" name="fin_hor_sab[]">

									</div>
								</div>
							</td>

							<td class="botonPadre">
								<div class="form-check form-check-inline">
								  <input type="checkbox" class="form-check-input checkboxes" id="7" name="domingo[]" value="Domingo" maximo="7">
								  <label class="form-check-label" for="7"></label>
								  <div class="md-form">
										<input  type="time" class="form-control horas" name="ini_hor_dom[]" dia="Domingo">
										<input  type="time" class="form-control horas" name="fin_hor_dom[]" dia="Domingo">

									</div>
								</div>

							
								<div class="botonHijo">

								  <a href="#" class="eliminacionFila" title="Eliminar fila">

								    <i class="fas fa-times fa-2x red-text"></i>
								  </a>
								</div>
							
							</td>
							
					</tr>
					

					<!-- BOTON FLOTANTE AGREGAR HORARIO-->
					<button type="submit" class="btn-floating btn-lg  flotante btn-info" id="btn_formulario"><i class="fas fa-save" title="Agregar Horario" ></i></button>
					<!-- FIN BOTON FLOTANTE AGREGAR HORARIO-->

					
				</tbody>



			</table>
		</form>

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
					'<td class="letraPequena">'+
						'<span class="numero"></span>'+
						
					'</td>'+
					'<td class="letraPequena">'+
						<?php  
							$sqlProfesores = "SELECT * FROM profesor WHERE id_pla2 = $plantel";
							$resultadoProfesores = mysqli_query($db, $sqlProfesores);
							
						?>
						'<select  id="profesor" class="mdb-select" name="profesor[]" form="formularioHorario">'+
							<?php  
								while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
							?>
								'<option value="<?php echo $filaProfesores["id_pro"]; ?>"><?php echo $filaProfesores["nom_pro"].' '.$filaProfesores['app_pro']; ?></option>'+
							<?php  
								}
							?>
						'</select>'+
						
					'</td>'+

					'<td class="letraPequena">'+
						<?php  
							$sqlMaterias = "SELECT * FROM materia WHERE id_ram2 = $id_ram";
							$resultadoMaterias = mysqli_query($db, $sqlMaterias);
							
						?>

						'<select id="materia" class="mdb-select" name="materia[]" form="formularioHorario">'+
							<?php  
								while($filaMaterias = mysqli_fetch_assoc($resultadoMaterias)){
							?>
								'<option value="<?php echo $filaMaterias["id_mat"]; ?>"><?php echo $filaMaterias["nom_mat"]; ?></option>'+
							<?php  
								}
							?>
						'</select>'+
					'</td>'+

					
					'<td class="letraPequena">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+1)+'" name="lunes[]" value="Lunes">'+
						  '<label class="form-check-label" for="'+(maximo+1)+'"></label>'+

						  	'<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_lun[]" dia="Lunes">'+
								'<input  type="time" class="form-control horas" name="fin_hor_lun[]" dia="Lunes">'+
							 
							'</div>'+
						'</div>'+
					'</td>'+
					


					'<td class="letraPequena">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+2)+'" name="martes[]" value="Martes">'+
						  '<label class="form-check-label" for="'+(maximo+2)+'"></label>'+
						  	'<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_mar[]" dia="Martes">'+
								'<input  type="time" class="form-control horas" name="fin_hor_mar[]" dia="Martes">'+
							 
							'</div>'+
						'</div>'+
					'</td>'+

					'<td class="letraPequena">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+3)+'" name="miercoles[]" value="Miércoles">'+
						  '<label class="form-check-label" for="'+(maximo+3)+'"></label>'+
							'<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_mie[]" dia="Miércoles">'+
								'<input  type="time" class="form-control horas" name="fin_hor_mie[]" dia="Miércoles">'+

							'</div>'+
						'</div>'+
					'</td>'+


					'<td class="letraPequena">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+4)+'" name="jueves[]" value="Jueves">'+
						  '<label class="form-check-label" for="'+(maximo+4)+'"></label>'+
						  	'<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_jue[]" dia="Jueves">'+
								'<input  type="time" class="form-control horas" name="ini_hor_jue[]" dia="Jueves">'+

							'</div>'+
						'</div>'+
					'</td>'+


					'<td class="letraPequena">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+5)+'" name="viernes[]" value="Viernes">'+
						  '<label class="form-check-label" for="'+(maximo+5)+'"></label>'+
						  '<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_vie[]">'+
								'<input  type="time" class="form-control horas" name="fin_hor_vie[]">'+

							'</div>'+
						'</div>'+
					'</td>'+


					'<td class="letraPequena">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+6)+'" name="sabado[]" value="Sábado">'+
						  '<label class="form-check-label" for="'+(maximo+6)+'"></label>'+
						  '<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_sab[]">'+
								'<input  type="time" class="form-control horas" name="fin_hor_sab[]">'+

							'</div>'+
						'</div>'+
					'</td>'+



					'<td class="botonPadre">'+
						'<div class="form-check form-check-inline">'+
						  '<input type="checkbox" class="form-check-input checkboxes" id="'+(maximo+7)+'" name="domingo[]" value="Domingo" maximo="'+(maximo+7)+'">'+
						  '<label class="form-check-label" for="'+(maximo+7)+'"></label>'+
						  '<div class="md-form">'+
								'<input  type="time" class="form-control horas" name="ini_hor_dom[]" dia="Domingo">'+
								'<input  type="time" class="form-control horas" name="fin_hor_dom[]" dia="Domingo">'+

							'</div>'+
						'</div>'+

						'<div class="botonHijo">'+

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

		var checkeados=[];
		var validacion = true;
		var check_vacio=true;
		for(var j = 0; j < $(".checkboxes").length; j++){
			if ($(".checkboxes")[j].checked == true) {
				check_vacio=false;
			}
		}
		if(check_vacio==false){
			for(var i = 0; i < $(".checkboxes").length; i++){
				if ($(".checkboxes")[i].checked == false) {
					$(".checkboxes").eq(i).attr({"value": "vacio"});

					if ($(".horas")[i*2].value !="" && $(".horas")[i*2+1].value !="") {
						$(".horas").eq(i*2).addClass('amber');
						$(".horas").eq(i*2+1).parent().parent().parent().addClass('amber');
						validacion = false;
					}else if($(".horas")[i*2].value !=""){
						$(".horas").eq(i*2).parent().parent().parent().addClass('amber');
						validacion = false;
					}else if($(".horas")[i*2+1].value !=""){
						$(".horas").eq(i*2+1).parent().parent().parent().addClass('amber');
						validacion = false;
					}
				}else if($(".checkboxes")[i].checked == true) {
					

					if ($(".horas")[i*2].value == "" && $(".horas")[i*2+1].value =="") {
						$(".horas").eq(i*2).addClass('amber');
						$(".horas").eq(i*2+1).addClass('amber');
						validacion = false;
					}else if($(".horas")[i*2].value == ""){
						$(".horas").eq(i*2).addClass('amber');
						validacion = false;
					}else if($(".horas")[i*2+1].value ==""){
						$(".horas").eq(i*2+1).addClass('amber');
						validacion = false;
					}
					//if ($(".horas").eq(i*2).value()) {}
					
					// $(".horas").eq(i).attr({"value": "11:00"});
					// $(".horas").eq(i+1).attr({"value": "11:00"});

				}
				checkeados[i]=$(".checkboxes")[i].checked;
			}
		}
		else
		{
			validacion=false;
		}
		//console.log(checkeados);
		
		// $(this).prop("checked", true);

		for(var i = 0; i < $(".checkboxes").length; i++){
			console.log(checkeados[i]);
		}
		
		//console.log(validacion);		/* Act on the event */
		//var datos = new FormData(formularioHorario);
		if (validacion == true) {

			$(".checkboxes").prop({checked: true});
			$("#btn_formulario").attr('disabled','disabled');
			$.ajax({
				url: 'server/agregar_horario_presencial.php?id_gru=<?php echo $id_gru;?>',
				type: 'POST',
				data: new FormData(formularioHorario),
				processData: false,
				contentType: false,
				cache: false,
				success: function(respuesta){
					$(".spinnito").remove();
					for(var i = 0; i < $(".checkboxes").length; i++){
						$(".checkboxes").eq(i).prop({checked: checkeados[i]});
					}
					$(".horas").removeClass('amber');
					$(".horas").parent().parent().parent().removeClass('amber');

					//console.log(respuesta);

					swal("Agregado correctamente", "Continuar", "success").
					then((value) => {
					  window.location.href='grupos.php?id_cic=<?php echo "$id_cic";?>';
					});;
				}
			})
		}else{
			swal ( "¡Faltan datos!" ,  "¡Olvidaste algo...!" ,  "error" )
		}
		


		//console.log($("#materia").val());

		
	});
 

	
</script>