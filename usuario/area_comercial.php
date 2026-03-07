<?php  

	include('inc/header.php');
	//include('../includes/conexion.php');
	$inicio;
	$fin;

?>

<div class="modal fade asignar_jefe" id="centralModalInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-notify modal-info" role="document">
     <!--Content-->
     <div class="modal-content">
       <!--Header-->
       <div class="modal-header">
         <p class="heading lead">Asignar/Reasignar Asesor</p>

         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true" class="white-text">&times;</span>
         </button>
       </div>

       <!--Body-->
       <div class="modal-body">
         <div class="text-center">
         		<p><span class="text-bold text-info" id="Asesor"></span></p>
           	 <!--Dropdown primary-->
							<div class="dropdown">
							  <!--Trigger-->
							  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1-1"
							    data-toggle="dropdown">Lista de Asesores</button>

							  <!--Menu-->
							  <div class="dropdown-menu dropdown-primary" style="max-height: 30vw; overflow-y: auto; top: 20vw;" id="lista_ejecutivos">
							  	<?php 
							  			$sql_lista_ejecutivos="SELECT CONCAT(nom_eje,' ',app_eje,' ',apm_eje) AS nombre, id_eje AS ejecutivo from ejecutivo";
							  			$solcitud_ejecutivo = mysqli_query($db, $sql_lista_ejecutivos);

							  	 ?>
							    <input class="form-control buscador_asesor" type="text" placeholder="Buscar Asesor" aria-label="Buscar Asesor">
							    <?php while ($asesor = mysqli_fetch_assoc($solcitud_ejecutivo)) {
							    	
							    ?>
							    <a class="dropdown-item mdb-dropdownLink-1 lista" responsable="<?php echo $asesor['ejecutivo']; ?>" nombre_ejecutivo="<?php echo $asesor['nombre']; ?>"><?php echo $asesor['nombre']; ?></a>
							    <?php } ?>
							  </div>
							</div>
							<!--/Dropdown primary-->
         </div>
       </div>

       <!--Footer-->
       <div class="modal-footer justify-content-center">
         <a type="button" class="btn btn-danger desasignar"><i class="far fa-minus-square fa-lg"></i> Desasignar</a>
         <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Cancelar</a>
       </div>
     </div>
     <!--/.Content-->
   </div>
 </div>


<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Reportes de ventas">
			<i class="fas fa-bookmark"></i> 
			Reportes de Ventas
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Reportes de Ventas</a>
</div>
<!-- FIN TITULO -->



<style>
	
	.gerente_comercial{
		border:  solid red .3vw;
	}
	.gerente_red{
		border-block:  solid yellow .3vw;
	}
	.lider_consultor{
		border-block:  solid green .3vw;
	}
	.consultor{
		border-block:  solid blue .3vw;
	}



</style>

<hr>

<div class="row">
	<div class="col-md-6">
		<h4>Filtros</h4>
	</div>

	<div class="col-md-6 text-right">
		<a href="#" class="btn btn-info btn-rounded" id="btn_agregar_ejecutivo">
			Agregar ejecutivo
		</a>
	</div>	
</div>


<div class="row">

	<div class="col-md-12">
		
		<div class="card" style="border-radius: 20px;">
			
			<div class="row p-3">
				

				<div class="col-md-3">
					<!-- Group of material radios - option 1 -->
					<div class="form-check">
					  <input type="radio" class="form-check-input radioReporte" id="materialGroupExample1" name="seleccionReporte" value="Citas">
					  <label class="form-check-label letraPequena" for="materialGroupExample1">Citas</label>
					</div>

					<!-- Group of material radios - option 2 -->
					<div class="form-check">
					  <input type="radio" class="form-check-input radioReporte" id="materialGroupExample2" name="seleccionReporte" checked value="Registros">
					  <label class="form-check-label letraPequena" for="materialGroupExample2">Registros</label>
					</div>

					<!-- Group of material radios - option 3 -->
					<div class="form-check">
					  <input type="radio" class="form-check-input radioReporte" id="materialGroupExample3" name="seleccionReporte" value="Estructura">
					  <label class="form-check-label letraPequena" for="materialGroupExample3">Estructura comercial</label>
					</div>

				</div>


				<div class="col-md-5" style="position: relative;">


					<div style="position: absolute; bottom: 0px; z-index: 9;">
						
						<!-- Group of material radios - option 1 -->
						<div class="form-check form-check-inline">
						  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11" name="seleccionPeriodo" checked value="Fecha">
						  	<label class="form-check-label letraPequena" for="materialGroupExample11">Por fechas</label>
						</div>

						<!-- Group of material radios - option 2 -->
						<div class="form-check form-check-inline">
						  	<input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22" name="seleccionPeriodo" value="Semana">
						  	<label class="form-check-label letraPequena" for="materialGroupExample22">Por semanas</label>
						</div>
					
					</div>
					

					


					<div class="row">

						<div class="col-md-12">


							<div id="contenedor_fecha" style="display: none;">
								

								<div class="row mt-1">
									<div class="col-md-6">
										<div class="md-form">
											<span class="letraPequena">Inicio</span>
								        	<input type="date" class="form-control filtrosFecha letraMediana" id="inicio" value="<?php echo date('Y-m-d'); ?>">
								        
								        </div>
									</div>

									<div class="col-md-6">
										<div class="md-form ">
											<span class="letraPequena">Fin</span>
											<input type="date" class="form-control filtrosFecha letraMediana" id="fin" value="<?php echo date('Y-m-d'); ?>">
								        
								        </div>
									</div>
								</div>
								


						        

							</div>

							<div id="contenedor_semana" style="display: none;">
								
								<span class="letraPequena">Selecciona una semana</span>
								<select class="mdb-select md-form" id="selectorSemana">

								<!--  -->
								  	<?php
										$i = 0;
										$semanas = obtenerDiferenciaFechasSemanas( $fechaHoy, date('Y').'-01-01' );
										$semanas++;
										$lunes = date("j");
										$periodo = 6;
									    $periodicidad = $periodo+1;
									    
									    do {


									        if ( $i == 0 ) {

									            if ( $lunes != 6 ) {
									              //echo 'if';
									              $domingo_proximo =  $fechaHoy;
									              $lunes_proximo = date("N");
									              $lunes_proximo = $lunes_proximo-1;
									              $inicio = date('Y-m-d', strtotime($fechaHoy));
									              $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

									              // $semanas = $semanas + 1;

									            } else {
									              //echo 'else';

									                if ( $lunes == 6 ) {
									                    $domingo_proximo =  $fechaHoy;
									                    $lunes_proximo = date("N");
									                    $lunes_proximo = $lunes_proximo-1;
									                    $inicio = date('Y-m-d', strtotime($fechaHoy));
									                    $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
									                
									                } else {


									                    $domingo_proximo = date("N"); //domingo = 7
									                    $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
									                    $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
									                    $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

									                }

									            }
									        

									        } else {

									   
									            $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
									            $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
									            

									        }
									?>


									<?php
									        // echo $inicio;
									        if ( $fin < date('Y').'-01-01' ) {
									            // echo 'ok';
									            break; break; break;
									        }
									?>

															<?php  
																if ( $i == 0 ) {
															?>
																	<option selected class="letraPequena" inicio="<?php echo $fin; ?>" fin="<?php echo $inicio; ?>">Semana <?php echo $semanas.' - '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?></option>
															<?php
																} else {
															?>

																	<option class="letraPequena" inicio="<?php echo $fin; ?>" fin="<?php echo $inicio; ?>">Semana <?php echo $semanas.' - '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?></option>
															<?php
																}
															?>
									       
									                        
									                 

									<?php
									                    


									                    $i++;
									                    $semanas--;
									       
									        

									        

									        
									    } while( (date('Y').'-01-01' < $fin) );
									?>
								<!--  -->
								</select>
							</div>

							
						</div>
					</div>
					

					
					
				</div>


				


				<div class="col-md-4">
					

					<!-- BUSCADOR -->
		    
					<span class="letraPequena">Selecciona ejecutivo</span>
					<select class="mdb-select md-form" id="selectorEjecutivo">
					  	<option value="Todos" selected>Todos</option>
					  	
					  	<?php  
					  		$sqlEjecutivos = "
					  			SELECT *, UPPER(nom_eje) AS nom_eje, UPPER(app_eje) AS app_eje
					  			FROM ejecutivo
					  			INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
					  			WHERE ( id_pla6 = '$plantel' ) AND ( eli_eje = 'Activo' )
					  			ORDER BY nom_eje ASC
					  		";

					  		$resultadoEjecutivos = mysqli_query( $db, $sqlEjecutivos );

					  		while( $filaEjecutivos = mysqli_fetch_assoc( $resultadoEjecutivos ) ){
					  	?>
					  			<option value="<?php echo $filaEjecutivos['id_eje']; ?>"><?php echo $filaEjecutivos['nom_eje'].' '.$filaEjecutivos['app_eje'].' - '.$filaEjecutivos['ran_eje']; ?></option>
					  	<?php
					  		}
					  	?>
					  
					</select>

					<!-- FIN BUSCADOR -->
				</div>

			</div>
	
		</div>
	
	</div>
	
</div>
<!-- FILTROS -->

<hr>


<!-- CONTENEDOR REPORTE -->
<div class="row">
	<div class="col-md-12">
		<div class="card" style="border-radius: 20px;">


			<div class="card-body">
				<div class="row">
					<div class="col-md-12 mr-1">

						<div id="contenedor_reporte"></div>
					
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<!-- FIN CONTENEDOR REPORTE -->




<!-- MODAL GENERACION -->

	<!-- AGREGAR -->
	<div class="modal fade" id="modal_ejecutivo">
	  <div class="modal-dialog modal-lg" role="document">
	    
		<form id="formulario_agregar_ejecutivo" enctype="multipart/form-data">
		    
		    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
		      	<div class="modal-header text-center">
		        	<h5 class="modal-title">Ejecutivo</h5>
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          		<span aria-hidden="true">&times;</span>
		        	</button>
		      	</div>
		      
		      	<div class="modal-body mx-3">
		      		<p class="grey-text letraPequena">
		      			*El ejecutivo podrá modificar algunos datos más tarde. Tambíén podrá agregar una foto.
		      		</p>
		      		<div class="row">
		      			
		      			<div class="col-md-3">
		      				<div class="md-form">

		      					<input type="hidden" id="id_eje" name="id_eje">



					          	<input type="text" id="nom_eje" name="nom_eje" class="form-control" required="">
					          	<label  for="nom_eje">Nombre</label>
					        </div>
		      			</div>


		      			<div class="col-md-3">
		      				<div class="md-form" id="contenedor_app_ejecutivo">




					          	<input type="text" id="app_eje" name="app_eje" class="form-control" required="">
					          	<label  for="app_eje">Apellido paterno</label>
					        </div>
		      			</div>


		      			<div class="col-md-3">
		      				<div class="md-form" id="contenedor_apm_ejecutivo">



					          	<input type="text" id="apm_eje" name="apm_eje" class="form-control" required="">
					          	<label  for="apm_eje">Apellido materno</label>
					        </div>
		      			</div>

		      			<div class="col-md-3">
		      				<div class="md-form" style="position: relative;">
								<p  class="grey-text letraMediana" style="position: absolute; top: -15px; left: -5px;">Rango</p>
								<select class="mdb-select md-form" id="ran_eje" name="ran_eje">
								  	
								  	<option value="Ejecutivo" id="ran_eje_primera_opcion">Ejecutivo</option>
								  	<option value="Líder de consultores">Líder de consultores</option>
								  	<option value="Gerente de red">Gerente de red</option>
								  	<option value="Gerente comercial">Gerente comercial</option>

								</select>
							</div>
		      			</div>


		      		</div>


		      		<div class="row">
		 


		      			<div class="col-md-6" style="position: relative;">
		      				
		      				<div class="md-form" id="contenedor_correo_ejecutivo">

					          	<input type="text" id="cor_eje" class="form-control" name="cor_eje" required="">
					          	<label for="cor_eje">Cuenta de acceso</label>
					          	
					        </div>
					        <span id="outputEjecutivo" style="position: absolute; bottom: -5px; "></span>
		      			</div>


		      			<div class="col-md-3">
		      				<div class="md-form" id="contenedor_password_ejecutivo">

					          	<input type="text" id="pas_eje" name="pas_eje" class="form-control" value="123" required="">
					          	<label  for="pas_eje">Contraseña</label>
					        </div>
		      			</div>


		      			<div class="col-md-3">
		      				
		      				<div class="md-form" id="contenedor_telefono_ejecutivo">

					          	<input type="text" id="tel_eje" class="form-control" name="tel_eje" value="Pendiente" required="">
					          	<label for="tel_eje">Teléfono</label>
					          	
					        </div>

		      			</div>

		      		</div>
		     

		      	</div>

		    <div class="modal-footer d-flex justify-content-center">
		    	
		    	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_agregar_ejecutivo_formulario">
                    Guardar
                </button>

                <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                    Cancelar
                </a>    


		    </div>

		    </div>
		</form>

	  </div>
	</div>
	<!-- FIN AGREGAR -->
	<!-- FIN MODAL GENERACION -->




<?php  

	include('inc/footer.php');

?>





<script>
	
	obtener_reporte();
	$('#selectorEjecutivo').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */
		obtener_reporte();
	});

	$('#selectorSemana').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_reporte();
		

	});


	$('.radioReporte').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_reporte();
		// alert( radioReporte );

	});


	$('.filtrosFecha').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_reporte();
		// alert( radioReporte );

	});


	$('.radioPeriodo').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_reporte();
		// alert( radioReporte );

	});




	function obtener_reporte(){

		var radioPeriodo = $(".radioPeriodo:checked").val();

		if ( radioPeriodo == 'Fecha' ) {

			var inicio = $('#inicio').val();
			var fin = $('#fin').val();

			$('#contenedor_fecha').css('display', '');
			$('#contenedor_semana').css( 'display', 'none' );


	
		} else if ( radioPeriodo == 'Semana' ) {

			var inicio = $('#selectorSemana option:selected').attr('inicio');
			var fin = $('#selectorSemana option:selected').attr('fin');

			$('#contenedor_fecha').css('display', 'none');
			$('#contenedor_semana').css( 'display', '' );


		}


		// console.log( inicio );
		

		var id_eje = $('#selectorEjecutivo option:selected').val();
		var radioReporte = $(".radioReporte:checked").val();

		$('#contenedor_reporte').html('<h3 class="text-center grey-text" style="right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

		console.log(id_eje);

		var url = '';

		if ( radioReporte == 'Estructura' ) {

			url = 'server/obtener_estructura.php';
	
		} else if ( radioReporte == 'Registros' ) {

			url = 'server/obtener_registros.php';

		} else if ( radioReporte == 'Citas' ) {

			url = 'server/obtener_citas.php';

		}



		$.ajax({
			url: url,
			type: 'POST',
			data: { inicio, fin, id_eje },
			success: function(respuesta){

				// console.log( respuesta );
				$("#contenedor_reporte").html(respuesta);

				// setTimeout( function(){
				// 	$('#contenedor_prueba').html( 'hello world' );
				// }, 5000 );

			}
		});
	
	}
</script>


<script>
	//AGREGAR
	$('#btn_agregar_ejecutivo').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('#modal_ejecutivo').modal('show');

		setTimeout(function(){
			$('#nom_eje').focus();
			$('#contenedor_password_ejecutivo label').addClass('active');
			$('#contenedor_telefono_ejecutivo label').addClass('active');
			$('#ran_eje').materialSelect('destroy');
			$('#ran_eje').materialSelect();

		}, 200 );

		$("#btn_agregar_ejecutivo_formulario").removeAttr('disabled').html('Guardar');

		$('#modal_ejecutivo').removeAttr('tipo').attr('tipo', 'Agregar');
	
	});
</script>



<script>
	
	//VALIDACION EN TEMPO REAL DESDE EL INPUT DEL CORREO
	function validacionCorreoTiempoRealEjecutivo( correo ){
        console.log( correo );

        if (correo != '') {
          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: { correo },
            success: function(response){
              // console.log(  response );
              var respuesta = response; 


              if (respuesta == 'disponible') {
                
                $('#outputEjecutivo').attr({
                  class: 'text-info letraPequena font-weight-normal'
                });
                $('#outputEjecutivo').text("¡El correo electrónico está disponible!");

              }else{
                // correo = correo+'1';
                
                correo = correo.substring(0, correo.indexOf("@"))+'1';

                correo = correo+'@<?php echo $folioPlantel; ?>.com';
                $('#cor_eje').val( correo );

                validacionCorreoTiempoRealEjecutivo( correo );
                
              }
            }
          })

        }else{
          $('#outputEjecutivo').attr({class: 'text-warning letraPequena font-weight-normal'});
          $('#outputEjecutivo').text("¡Ingresa un Correo Electrónico!");
        }
              
    }


	$('#nom_eje').on('keyup', function(event) {
        /* Act on the event */

	    obtenerCorreoCompuestoEjecutivo();
	    var correo = $('#cor_eje').val();
	    // console.log( correo );
        validacionCorreoTiempoRealEjecutivo( correo );
	    $('#contenedor_correo_ejecutivo label').addClass('active');

	    
	});


	$('#app_eje').on('keyup', function(event) {
        /* Act on the event */

	    obtenerCorreoCompuestoEjecutivo();
	    var correo = $('#cor_eje').val();
	    // console.log( correo );
        validacionCorreoTiempoRealEjecutivo( correo );
	    $('#contenedor_correo_ejecutivo label').addClass('active');

	    
	});

	function obtenerCorreoCompuestoEjecutivo(){
		var cadena = $('#nom_eje').val()+'-'+$('#app_eje').val();
		var y = remove_accents( cadena.split(' ').slice(0,2).join('-').replace(' ', '-').toLowerCase() );
		var correo = $('#cor_eje').val( y+'@<?php echo $folioPlantel; ?>.com' );
        // console.log( correo );
		// y = "ABC+XYZ"
        return correo;

    }


</script>


<script>
	$('#formulario_agregar_ejecutivo').on('submit', function(event) {
		event.preventDefault();

		$("#btn_agregar_ejecutivo_formulario").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando');

		var tipo = $('#modal_ejecutivo').attr('tipo');
		
		if ( tipo == 'Agregar' ) {  
		
			var url1 = 'server/agregar_ejecutivo.php';
		
		} else if ( tipo == 'Editar' ) {

			var url1 = 'server/editar_ejecutivo.php'
		
		}

		$.ajax({
					
			url: url1,
			type: 'POST',
			data: new FormData(formulario_agregar_ejecutivo), 
			processData: false,
			contentType: false,	
			cache: false,
			success: function( respuesta ){
			console.log(respuesta);

					swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {

						$("#btn_agregar_ejecutivo_formulario").removeAttr('disabled').html('Guardar');
						$('#modal_ejecutivo').modal('hide');
						obtener_reporte();
						$('#nom_eje').val('');
						$('#cor_eje').val('');
					
					});
					
				
			}
		});
		
	});


</script>

<script>
	$('#selectorEjecutivo').materialSelect();
	$('#selectorSemana').materialSelect();

	
</script>	