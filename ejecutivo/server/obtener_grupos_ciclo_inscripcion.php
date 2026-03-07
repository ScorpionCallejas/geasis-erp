<?php  
	//ARCHIVO VIA AJAX PARA OBTENER GRUPOS DEL CICLO
	//horarios.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// echo $_POST['id_cic'];
	if ( isset( $_POST['id_cic'] ) ) {

		$id_cic = $_POST['id_cic'];

		$sqlGrupos = "
			SELECT * 
			FROM grupo
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			WHERE id_cic1 = '$id_cic' 
			ORDER BY id_gru DESC
		";

		// echo $sqlGrupos;
		
		$resultadoGrupos = mysqli_query($db, $sqlGrupos);

		$resultadoTotalGrupos = mysqli_query( $db, $sqlGrupos );

		$totalGrupos = mysqli_num_rows( $resultadoTotalGrupos );


?>

	<p class="letraPequena grey-text">
		Selecciona un grupo
	</p>

	<?php 
		if ( $totalGrupos > 0 ) {
	?>

			<select id="selectorGrupo" class="form-control letraPequena">
				<?php
					$validadorGrupo = true;
					while($filaGrupos = mysqli_fetch_assoc($resultadoGrupos)){
						if ( $validadorGrupo == true ) {
				?>
							<option value="<?php echo $filaGrupos['id_gru']; ?>" mod_ram="<?php echo $filaGrupos['mod_ram']; ?>" id_gru="<?php echo $filaGrupos['id_gru']; ?>" selected><?php echo $filaGrupos["nom_gru"]; ?></option>

				<?php
							$validadorGrupo = false;
						}else{
				?>
							<option value="<?php echo $filaGrupos['id_gru']; ?>" mod_ram="<?php echo $filaGrupos['mod_ram']; ?>" id_gru="<?php echo $filaGrupos['id_gru']; ?>"><?php echo $filaGrupos["nom_gru"]; ?></option>
				<?php
						}
				?>
					
				<?php
					}
				?>
			</select>

			<script>
				var id_gru = $("#selectorGrupo option:selected").val();
				var mod_ram = $("#selectorGrupo option:selected").attr('mod_ram');

				// setTimeout(function(){
					obtener_grupos( id_gru, mod_ram );
				// }, 200);
				

				// SELECTOR CICLO
				$("#selectorGrupo").on('change', function(event) {
					event.preventDefault();
					/* Act on the event */
					var id_gru = $(this).val();


					obtener_grupos( id_gru, mod_ram );

					//alert(id_ram);
				});


				function obtener_grupos(grupo, mod_ram){

					if( mod_ram == 'Online' ){
							
						url = 'server/obtener_horarios_grupo_online.php';
				
					}else if( mod_ram == 'Presencial' ){ 
				
						url = 'server/obtener_horarios_grupo.php';
				
					}

					$.ajax({
						url: url,
						type: 'POST',
						data: {grupo, mod_ram},
						success: function(respuesta){
						
							$("#contenedor_materias").html(respuesta);


							$('.sub_hor').on('click', function(event) {
								event.preventDefault();
								// burbuja.play();
								/* Act on the event */

								//$('.sub_hor').off('click');

								// generarAlerta( 'Cambios guardados' );

								$(this).removeClass('btn-info').addClass('btn-danger removerHorario');
								$(this).children().removeClass('fas fa-plus-circle').addClass('fas fa-times-circle').removeAttr().attr({title: "Quita este horario"});
								$("#panzaHorarioAlumno").append($(this).parent().parent());


								// VALIDADOR HORARIOS
								  
								if ( mod_ram == 'Presencial' ) {
							
									var validadorMaterias = [];
									for(var i = 0; i < $("#panzaHorarioAlumno .filasHorario").length; i++){
									    	
								    	validadorMaterias[i] = $("#panzaHorarioAlumno .filasHorario").eq(i).attr("sub_hor");
								    	//console.log( materias[i] );
								    }

								    //alert( validadorMaterias.length );
								    
								    if ( validadorMaterias.length > 1 ) {
								    	$.ajax({
								    		url: 'server/obtener_validacion_horarios.php',
								    		type: 'POST',
								    		data: { validadorMaterias },
								    		success: function( respuesta ){
								    			$( "#modalInscripcion" ).append( respuesta );

								    		}
								    	});
								    	
								    }
							
								}


								$('.removerHorario').on('click', function(event) {
									event.preventDefault();
									/* Act on the event */
									//error.play();
									$(this).parent().parent().remove();


									if ( $("#panzaHorarioAlumno .filasHorario").length == 0 ) {
										//error.play();
										$('#btn_paso_2').removeClass('next-step');

										swal("¡No agregaste ninguna materia!", "Agrega al menos una para continuar", "error", {button: "Aceptar",});
										$('#btn_finalizar').removeAttr('disabled');
										$('#btn_finalizar').attr('disabled', 'disabled');
										
									}

								});



								

							});
							

							// FINALIZAR
							$("#btn_finalizar").on('click', function(event) {
								event.preventDefault();
								/* Act on the event */
								$("#btn_finalizar").attr('disabled','disabled');
								validador = true;
								if(validador==true){
									swal({
										  title: "Confirmación de horario",
										  text: "¿Estás seguro de continuar?",
										  icon: "warning",
										  buttons: 	{
													  cancel: {
													    text: "Cancelar",
													    value: null,
													    visible: true,
													    closeModal: true,
													    className: "btn-danger waves-effect"
													  },
													  confirm: {
													    text: "Confirmar",
													    value: true,
													    visible: true,
													    closeModal: true,
													    className: "btn-info waves-effect"
													  }
													},
										  dangerMode: true,
								}).then((willDelete) => {
								  if (willDelete) {
								    //VALIDACION ACEPTADA

								    

								    validador=false;
								    var sub_hor = [];
								    for(var i = 0; i < $("#panzaHorarioAlumnoFinal .filasHorario").length; i++){
								    	
								    	sub_hor[i] = $("#panzaHorarioAlumnoFinal .filasHorario").eq(i).attr("sub_hor");
								    	//console.log($(".filasHorario").eq(i).attr("sub_hor"));
								    }

								    //INSCRIPCION MULTIPLE
								    //ELIMINACION TACHES
									$(".eliminacionSeleccionAlumnoFinal").remove();
									let barra_estado = $("#barra_estado");
									var porcentaje;
									var contador;

									// VALIDADOR COBROS DE CICLO
									var estatus_cobros = 'Falso';
									var estatus_actividades = $("#abc1")[0].checked;

									// CAPTURAR TIPO DE INSCRIPCION
									var tipo_inscripcion = $('input[name="tipo_inscripcion"]:checked').val();
									console.log('Tipo inscripción enviado al servidor: ' + tipo_inscripcion);


									// console.log( $(".seleccionAlumnoFinal").length +'total' );
								    for(var i = 0 ; i < $(".seleccionAlumnoFinal").length; i++){
								    	
								    	var id_alu_ram = $(".seleccionAlumnoFinal").eq(i).attr("id_alu_ram");

										// console.log( 'contador:'+i );

										// TRABAJOS ESPECIALES 2021-OCT
										$.ajax({
											url: 'server/agregar_trabajo_especial_alumno.php?id_alu_ram='+id_alu_ram,
											type: 'POST',
											data: { sub_hor },
											success: function( respuesta ){

												console.log( respuesta );

											}
										});
										

										// FIN TRABAJOS ESPECIALES 2021-OCT

								    	$.ajax({
											ajaxContador: i,
									    	url: 'server/agregar_horario.php?id_alu_ram='+id_alu_ram,
									    	type: 'POST',
									    	data: { 
									    		sub_hor, 
									    		estatus_cobros, 
									    		estatus_actividades,
									    		tipo_inscripcion  // NUEVO PARAMETRO
									    	},
									    	beforeSend: function(){

												$("#btn_finalizar").removeClass('btn-info').addClass('light-green accent-4').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>').attr('disabled', 'disabled');


											}
									    }).done(function(respuesta) {
								    		console.log(respuesta);

								    		if ( $(".seleccionAlumnoFinal").eq(this.ajaxContador).attr("id_alu_ram") == respuesta ) {
								    			$(".seleccionAlumnoFinal").eq(this.ajaxContador).addClass('light-green accent-4 white-text');
								    		}

								    		contador = this.ajaxContador + 1;
							    			porcentaje = Math.floor( contador*(100/$(".seleccionAlumnoFinal").length), 2 );
											

											if (porcentaje <= 100) {
												
												barra_estado.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
										    		
										    	barra_estado.text(porcentaje+'%');
												
												if (porcentaje == 100) {
													barra_estado.removeClass();
													barra_estado.addClass('progress-bar text-center white-text bg-success');
													barra_estado.text("Listo");
										    		$(".seleccionAlumnoFinal").eq(i).addClass('light-green accent-4 white-text');

										    		$("#btn_finalizar").html('<i class="fas fa-check white-text"></i> <span>Inscripción Exitosa</span>');



										    		swal("Inscripción Exitosa", "Continuar", "success", {button: "Aceptar",}).
													then((value) => {
													  	
														$('#seleccionTotal').prop({checked: false});
													  	obtenerAjaxAlumnoUnificado();
														$('#modal_inscripcion').modal('hide');
														// reloadTable();

													});
												}
											}
								    		
									    });


								    }
								    // BUCLE FOR

								    	    
								    //console.log("acepto");
								    
								  }else{

								  	console.log("no acepto");
								  }
								});
								}
								
							});
							// FIN FINALIZAR
							

						}
					});
				}
				//FIN FUNCION obtener_grupos
			</script>





			
	<?php	
		}else{
	?>
			<p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Sin grupos</p>

			<script>
				$("#contenedor_grupos").html('');
			</script>
	<?php
		}
	?>




<?php
	}
?>