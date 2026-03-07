<?php  

	include('inc/header.php');


?>

<!-- BOTON FLOTANTE AGREGAR EJECUTIVO-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar Ejecutivo" id="agregarEjecutivo"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR EJECUTIVO-->

<!-- CONTENIDO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Ejecutivos del Plantel">
			<i class="fas fa-bookmark"></i> 
			Ejecutivos
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Ejecutivos</a>
</div>


<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-left">
		<?php  

		$sqlEjecutivos = "
			SELECT *
			FROM ejecutivo
			INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
            WHERE id_pla6 = '$plantel'
            ORDER BY id_eje DESC
		";
		$resultadoEjecutivos = mysqli_query($db, $sqlEjecutivos);

	?>


		


		<div class="card" style="border-radius: 20px;">
			<div class="card-body">

				<!-- <a href="#" class="btn btn-info btn-sm btn-rounded" id="btn_comision">Generar comisiones SCR</a> -->
				
				<div class="table-responsive">
					<!--  -->
					<table id="myTable" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
						<thead class="grey white-text">
							<tr>
								<th class="letraPequena">#</th>
								<th class="letraPequena">Foto</th>
								<th class="letraPequena">Nombre</th>
								<th class="letraPequena">Equipo</th>
								<th class="letraPequena">Teléfono</th>
								<th class="letraPequena">Correo Electrónico</th>
								<th class="letraPequena">Ingreso</th>
								<th class="letraPequena">Password</th>
								
								<th class="letraPequena">Estatus SCR</th>
								<th class="letraPequena">Estatus de cuenta</th>
								<th class="letraPequena">Acción</th>
							</tr>
						</thead>

						<?php 
							$i = 1;
							while($filaEjecutivos = mysqli_fetch_assoc($resultadoEjecutivos)){

						?>
							<tr>
								<td class="letraPequena"><?php echo $i; $i++;?></td>
								<td class="letraPequena">
									
									<?php  
			                            if ( $filaEjecutivos['fot_emp'] == '' ) {
			                        ?>
			                                <img src="../img/usuario.jpg"  class="rounded-circle img-fluid" style=" width: 50px; height: 50px;">
			                        <?php
			                            } else {
			                        ?>
			                                <img src="../uploads/<?php echo $filaEjecutivos['fot_emp']; ?>" class="rounded-circle img-fluid" style=" width: 50px; height: 50px;">
			                        <?php
			                            }
			                        ?>
								</td>
								<td class="letraPequena"><?php echo $filaEjecutivos['nom_eje'].' '.$filaEjecutivos['app_eje']; ?></td>
								<td class="letraPequena"><?php echo $filaEjecutivos['equ_eje']; ?></td>
								<td class="letraPequena"><?php echo $filaEjecutivos['tel_eje']; ?></td>
								<td class="letraPequena"><?php echo $filaEjecutivos['cor_eje']; ?></td>
								<td class="letraPequena"><?php echo fechaFormateadaCompacta2($filaEjecutivos['ing_eje']); ?></td>
								<td class="letraPequena"><?php echo $filaEjecutivos['pas_eje']; ?></td>
								

								<td class="letraPequena">
									

									<?php 

										echo 'inicio: '.obtener_fechas_semana_pasadas( $filaEjecutivos['id_eje'] )['inicio'].'<br>'.' fin: '.obtener_fechas_semana_pasadas( $filaEjecutivos['id_eje'] )['fin']; 
									
									?>
									<?php 

										echo obtener_datos_scr_semanal( $filaEjecutivos['id_eje'], obtener_fechas_semana_pasadas( $filaEjecutivos['id_eje'] )['inicio'], obtener_fechas_semana_pasadas( $filaEjecutivos['id_eje'] )['fin'] )['estatus']; 
									
									?>

									
								</td>

							

								<td class="letraPequena">
									<?php
										if ( $filaEjecutivos['est_eje'] == 'Activo' ) {
									?>
											<div class="row">
												<div class="col-md-12 text-center">
													<a class="btn-floating btn-sm lime accent-4 switchEjecutivo" id_eje="<?php echo $filaEjecutivos['id_eje']; ?>" estatus="<?php echo $filaEjecutivos['est_eje']; ?>" title="Activa/Desactiva la cuenta del ejecutivo <?php echo $filaEjecutivos['nom_eje']; ?>">
														<i class="fas fa-power-off"></i>
													</a>
												</div>
											</div>
									<?php
										} else if ( $filaEjecutivos['est_eje'] == 'Inactivo' ) {
									?>

											<div class="row">
												<div class="col-md-12 text-center">
													<a class="btn-floating btn-sm btn-secondary switchEjecutivo" id_eje="<?php echo $filaEjecutivos['id_eje']; ?>" estatus="<?php echo $filaEjecutivos['est_eje']; ?>" title="Activa/Desactiva la cuenta del ejecutivo <?php echo $filaEjecutivos['nom_eje']; ?>">
														<i class="fas fa-power-off"></i>
													</a>
												</div>
											</div>

									<?php
										}
									?>
								</td>
								

								<!-- BOTONES DE ACCION -->
								<td class="letraPequena">


									<a class="chip  text-white " style="background: #3F729B; width: 60px;" title="Consultar datos de <?php echo $filaEjecutivos['nom_eje']; ?>" target="_blank" href="historico_semanal_comercial.php?id_eje=<?php echo $filaEjecutivos['id_eje']; ?>">
										SCR
									</a>

									<br>


									<a class="chip info-color text-white edicion" title="Editar a <?php echo $filaEjecutivos['nom_eje']; ?>" edicion="<?php echo $filaEjecutivos['id_eje']; ?>" tipo="<?php echo $filaEjecutivos['tip_eje']; ?>">
										Editar
									</a>
									<br>

									<a class="chip danger-color eliminacion white-text" title="Eliminar a <?php echo $filaEjecutivos['nom_emp']; ?>" eliminacion="<?php echo $filaEjecutivos['id_emp']; ?>" empleado="<?php echo $filaEjecutivos['nom_emp'].' '.$filaEjecutivos['app_emp'].' '.$filaEjecutivos['apm_emp']; ?> ">
											Elimina
									</a>
									
								</td>
								<!-- FIN BOTONES DE ACCION -->

							</tr>


						<?php
							}
						?>
					</table>
					<!--  -->
				</div>
			</div>
		</div>
		
	
		
	</div>
	
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR EJECUTIVO -->
<div class="modal fade text-left" id="agregarEjecutivoModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarEjecutivoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Ejecutivo</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">


	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nom_eje" name="nom_eje" class="form-control validate">
	          <label  for="nom_eje">Nombre</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="app_eje" name="app_eje" class="form-control validate">
	          <label  for="app_eje">Apellído Paterno</label>
	        </div>
			

			<div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apm_eje" name="apm_eje" class="form-control validate">
	          <label  for="apm_eje">Apellído Materno</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-venus-mars prefix grey-text"></i>
	          <input type="text" id="gen_eje" name="gen_eje" class="form-control validate">
	          <label  for="gen_eje">Género</label>
	        </div>



	        <div class="md-form mb-5">
	          <i class="fas fa-venus-mars prefix grey-text"></i>
	          <input type="text" id="equ_eje" name="equ_eje" class="form-control validate">
	          <label  for="equ_eje">Equipo</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-phone prefix grey-text"></i>
	          <input type="text" id="tel_eje" name="tel_eje" class="form-control validate">
	          <label  for="tel_eje">Teléfono</label>
	        </div>



	 		<label id="output"></label>
	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="correo" class="form-control" name="correo">
	          <label for="correo">Correo Electrónico</label>
	          
	        </div>


	      	<div class="md-form mb-5">
	          <i class="fas fa-key prefix grey-text"></i>
	          <input type="text" id="pas_eje" name="pas_eje" class="form-control validate">
	          <label  for="pas_eje">Contraseña</label>
	        </div>


	        <label for="Ingreso">Fecha de Nacimiento</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="nac_eje" name="nac_eje" class="form-control validate" value="<?php echo date('Y-m-d'); ?>">
	        </div>


	        <label for="Ingreso">Fecha de Ingreso</label><br>
	        <div class="md-form mb-2">
	          	<i class="far fa-calendar-check prefix grey-text"></i>
	          	<input type="date" id="ing_eje" name="ing_eje" class="form-control validate" value="<?php echo date('Y-m-d'); ?>">
	        </div>


	        <div class="md-form mb-5">
			  <div class="file-field">
			    <div class="btn btn-info btn-sm float-left">
			      <span>Sube una foto</span>
			      <input type="file" id="fot_eje" name="fot_eje">
			    </div>
			    <div class="file-path-wrapper">
			      <input class="file-path validate" type="text" placeholder="3MB, JPEG, JPG o PNG">
			    </div>
			  </div>
	    	</div>


			<br>


	      	<div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="dir_eje" name="dir_eje" class="form-control validate">
	          <label  for="dir_eje">Dirección</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="cp_eje" name="cp_eje" class="form-control validate">
	          <label  for="cp_eje">Código Postal</label>
	        </div>



	        <p class="grey-text letraPequena">
	        	*Comisión para sistema de compensación residual(SCR)
	        </p>

	        <div class="md-form mb-3">
				<p  class="grey-text">Rango</p>
				<select class="mdb-select md-form" id="ran_eje" name="ran_eje">
				  	
				  	<option value="Ejecutivo" id="ran_eje_primera_opcion">Ejecutivo ( 4 registros )</option>
				  	<option value="Líder de consultores">Líder de consultores ( 3 registros )</option>
				  	<option value="Gerente de red">Gerente de red ( 2 registros ) </option>
				  	<option value="Gerente comercial">Gerente comercial ( 1 registro )</option>

				</select>
			</div>




			
	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	      <h3 id="validacionCorreo"></h3><br>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR EJECUTIVO -->



<!-- CONTENIDO MODAL EDITAR EJECUTIVO -->
<div class="modal fade text-left" id="editarEjecutivoModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarEjecutivoFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Ejecutivo</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Nombre</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apellido1" name="apellido1" class="form-control validate">
	          <label  for="form29">Apellído Paterno</label>
	        </div>
			

			<div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apellido2" name="apellido2" class="form-control validate">
	          <label  for="form29">Apellído Materno</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-venus-mars prefix grey-text"></i>
	          <input type="text" id="genero" name="genero" class="form-control validate">
	          <label  for="form29">Género</label>
	        </div>



	        <div class="md-form mb-5">
	          <i class="fas fa-venus-mars prefix grey-text"></i>
	          <input type="text" id="equipo" name="equipo" class="form-control validate">
	          <label  for="form29">Equipo</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-phone prefix grey-text"></i>
	          <input type="text" id="telefono" name="telefono" class="form-control validate">
	          <label  for="form29">Teléfono</label>
	        </div>



	        <label id="outputEdicion"></label>
	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="correoEdicion" class="form-control" name="correoEdicion">
	          <label for="form29">Correo Electrónico</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-key prefix grey-text"></i>
	          <input type="text" id="password" name="password" class="form-control validate">
	          <label  for="form29">Contraseña</label>
	        </div>


	        <label for="Nacimiento">Fecha de Nacimiento</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="nacimiento" name="nacimiento" class="form-control validate">
	        </div>


	        <label for="Ingreso">Fecha de Ingreso</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="ingreso" name="ingreso" class="form-control validate">
	        </div>


			<br>


	      	<div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="direccion" name="direccion" class="form-control validate">
	          <label  for="form29">Dirección</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="codigo" name="codigo" class="form-control validate">
	          <label  for="form29">Código Postal</label>
	        </div>



	        <p class="grey-text letraPequena">
	        	*Comisión para sistema de compensación residual(SCR)
	        </p>

	        <div class="md-form mb-3">
				<p  class="grey-text">Rango</p>
				<select class="mdb-select md-form" id="rango" name="rango">
				  	
				  	<option value="Ejecutivo" id="ran_eje_primera_opcion_edicion">Ejecutivo ( 4 registros )</option>
				  	<option value="Líder de consultores">Líder de consultores ( 3 registros )</option>
				  	<option value="Gerente de red">Gerente de red ( 2 registros ) </option>
				  	<option value="Gerente comercial">Gerente comercial ( 1 registro )</option>

				</select>
			</div>

		
		

	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">
	        </div>



	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	      <h3 id="validacionCorreoEdicion"></h3><br>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR EJECUTIVO -->




<?php  

	include('inc/footer.php');

?>


<script>
	$(document).ready(function () {

		
		

		$('#myTable').DataTable({
			
		
			dom: 'Bfrtlip',
			pageLength: 25,
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

            ],

			"language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
		});
		$('#myTable_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTable_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTable_wrapper input').attr("placeholder", "Buscar...");
			$('#myTable_wrapper input').removeClass('form-control-sm');
		});
		$('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTable_wrapper .dataTables_filter').addClass('md-form');
		$('#myTable_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTable_wrapper select').addClass('mdb-select');
		$('#myTable_wrapper .mdb-select').materialSelect();
		$('#myTable_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);
		
	
	});
</script>



<script>
	//CODIGO PARA TOMAR ID DE IMAGEN Y DESPLEGAR EN MODAL INFO DEL EJECUTIVO
	$('.imagenes').on('click', function(event) {
			event.preventDefault();

			var imagen = $(this).children().attr("imagen");

			console.log(imagen);
			/* Act on the event */
		});
</script>


<script>
	//VALIDACION EN TEMPO REAL DESDE EL INPUT DEL CORREO
	validacionCorreoTiempoReal();
	function validacionCorreoTiempoReal(){
		$('#correo').keyup(function(event) {
	        var correo = $('#correo').val();

	        console.log($('#correo').val());

	        if (correo != '') {
	          $.ajax({
	            url: 'server/validacion_correo.php',
	            type: 'POST',
	            data: {correo},
	            success: function(response){
	            	var respuesta = response; 


	              if (respuesta == 'disponible') {
	                
	                $('#output').attr({
	                	class: 'text-info'
	                });
	                $('#output').text("¡El correo electrónico está disponible!");

	              }else{
	              	$('#output').attr({
	                	class: 'text-danger'
	                });
	                $('#output').text("¡El correo electrónico está ocupado!");

	              }
	            }
	          })

	        }else{
	          $('#output').attr({class: 'text-warning'});
	          $('#output').text("¡Ingresa un Correo Electrónico!");
	        }
	        
	    });
	}



	validacionCorreoTiempoRealEdicion();
	function validacionCorreoTiempoRealEdicion(){
		$('#correoEdicion').keyup(function(event) {
	        var correoEdicion = $('#correoEdicion').val();
	        var identificador = $('#identificador').val();
	        var tipo = "Ejecutivo";


	        if (correoEdicion != '') {
	          $.ajax({
	            url: 'server/validacion_correo.php',
	            type: 'POST',
	            data: {correoEdicion, identificador, tipo},
	            success: function(response){
	            	var respuesta = response; 

	              if (respuesta == 'disponible') {
	                
	                $('#outputEdicion').attr({
	                	class: 'text-info'
	                });
	                $('#outputEdicion').text("¡El correo electrónico está disponible!");

	              } else if (respuesta == 'mio') {
	                
	                $('#outputEdicion').attr({
	                	class: 'text-warning'
	                });
	                $('#outputEdicion').text("¡El correo electrónico es el mismo!");

	              } else{
	              	$('#outputEdicion').attr({
	                	class: 'text-danger'
	                });
	                $('#outputEdicion').text("¡El correo electrónico está ocupado!");

	              }
	            }
	          })

	        }else{
	          $('#outputEdicion').attr({class: 'text-warning'});
	          $('#outputEdicion').text("¡Ingresa un Correo Electrónico!");
	        } 
	    });
	}


</script>

<script>

	//FORMULARIO DE CREACION DE EJECUTIVO
	//CODIGO PARA AGREGAR EJECUTIVO NUEVO ABRIENDO MODAL


	$('#agregarEjecutivo').on('click', function(event) {
		event.preventDefault();
		$('#agregarEjecutivoModal').modal('show');
		$('#agregarEjecutivoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		//$("#tip_eje").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??

		$('.mdb-select').materialSelect('destroy');
		$('.mdb-select').materialSelect();

		
		$('#ran_eje_primera_opcion').attr( 'selected', '');
	});


	$('#agregarEjecutivoFormulario').on('submit', function(event) {
		event.preventDefault();


		var correo = $('#correo').val();


		$.ajax({
			url: 'server/validacion_correo.php',
			type: 'POST',
			data: {correo},
			success: function(respuesta){
				if (respuesta == "disponible") {

					$('#validacionCorreo').attr({class: 'text-info text-center'}).text("¡Correcto!");
					if ($("#fot_eje")[0].files[0]) {

						var fileName = $("#fot_eje")[0].files[0].name;
						var fileSize = $("#fot_eje")[0].files[0].size;


						var ext = fileName.split('.').pop();

						
						if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
							if (fileSize < 3000000) {
								$.ajax({
						
									url: 'server/agregar_ejecutivo.php',
									type: 'POST',
									data: new FormData(agregarEjecutivoFormulario), 
									processData: false,
									contentType: false,	
									cache: false,
									success: function(respuesta){
									console.log(respuesta);

										if (respuesta == 'Exito') {
											swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
											then((value) => {
											  window.location.reload();
											});
											
										}
									}
								});
							}else{
								swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
							}
							
						}else{
							swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
						}

					}else{

						//VALIDACION SI MANDA FOTO, EN CASO DE MANDAR VALIDA, SI NO, ACCEDE DIRECTAMENTE
						$.ajax({
						
							url: 'server/agregar_ejecutivo.php',
							type: 'POST',
							data: new FormData(agregarEjecutivoFormulario), 
							processData: false,
							contentType: false,
							cache: false,
							success: function(respuesta){
								console.log(respuesta);

								if (respuesta == 'Exito') {
									swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
									then((value) => {
									  window.location.reload();
									});
									
								}
							}
						});

					}

					
				}else{

					$('#validacionCorreo').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

				}
			}
		});			
	});
</script>


<script>
	//EDICION DE EJECUTIVO

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DE EDICION DE EJECUTIVO
	$('.edicion').on('click', function(){
		$('#editarEjecutivoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionEjecutivo = $(this).attr("edicion");
		$('#editarEjecutivoFormulario label').addClass('active');
		$('#editarEjecutivoFormulario i').addClass('active');


		//console.log(edicionEjecutivo);

		$.ajax({
			url: 'server/obtener_ejecutivo.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionEjecutivo},
			success: function(datos){
				//console.log(datos);

				$('#editarEjecutivoModal').modal('show');

				$('#nombre').attr({value: datos.nom_eje});
				$('#apellido1').attr({value: datos.app_eje});
				$('#apellido2').attr({value: datos.apm_eje});
				$('#genero').attr({value: datos.gen_eje});
				$('#equipo').attr({value: datos.equ_eje});
				$('#telefono').attr({value: datos.tel_eje});
				$('#correoEdicion').attr({value: datos.cor_eje});
				$('#password').attr({value: datos.pas_eje});
				$('#nacimiento').attr({value: datos.nac_eje});
				$('#ingreso').attr({value: datos.ing_eje});


				// $("#tipo").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
				// $('#tipo').children().removeAttr('selected');//SE REMUEVE PARA QUE NO CHOQUE CON EL NUEVO A SELECCIONAR
				//AGREGADO A SELECT DEL DATO OBTENIDO MEDIANTE COMPARACION
				// for(var i = 0 ; i < $('#tipo').children().length; i++){
				// 	//console.log($('#tipo').children().eq(i).attr("value") );

				// 	if ($('#tipo').children().eq(i).attr("value") == datos.tip_eje) {
				// 		$('#tipo').children().eq(i).attr({"selected": ""});			
				// 	}
				// }

				// $('#tipo').attr({value: datos.tip_eje});//AGREGADO DE VALUE PARA VALIDACIONDECORREOEDICIONREALTIME
				// $('#divTipo').hide();
				
				$('#direccion').attr({value: datos.dir_eje});
				$('#codigo').attr({value: datos.cp_eje});				
				$('#identificador').attr({value: datos.id_eje});


				$("#rango").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
				$('#rango').children().removeAttr('selected');//SE REMUEVE PARA QUE NO CHOQUE CON EL NUEVO A SELECCIONAR
				for(var i = 0 ; i < $('#rango').children().length; i++){
					//console.log($('#tipo').children().eq(i).attr("value") );

					if ($('#rango').children().eq(i).attr("value") == datos.ran_eje) {
						$('#rango').children().eq(i).attr({"selected": ""});			
					}
				}
				
				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL EJECUTIVO
				$('#editarEjecutivoFormulario').on('submit', function(event) {
					event.preventDefault();

					var correoEdicion = $('#correoEdicion').val();
					var identificador = $('#identificador').val();
					var tipo = "Ejecutivo";

					$.ajax({
					  url: 'server/validacion_correo.php',
					  type: 'POST',
					  data: {correoEdicion, identificador, tipo},
					  success: function(respuesta){
					    console.log(respuesta);

					    if (respuesta == "disponible" || respuesta == "mio") {

					     	$('#validacionCorreoEdicion').attr({class: 'text-info text-center'}).text("¡Correcto!");
			     
					      	$.ajax({
									
								url: 'server/editar_ejecutivo.php',
								type: 'POST',
								data: new FormData(editarEjecutivoFormulario),
								processData: false,
								contentType: false,
								cache: false,
								success: function(respuesta){
								console.log(respuesta);

									if (respuesta == 'Exito') {
										console.log("condicion");
										swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
										then((value) => {
										  window.location.reload();
										});
									}
								}
							});
					      
					    }else{

					      $('#validacionCorreoEdicion').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

					    }
					  }
					}); 
						
				});	
			}
		});
	});
	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE EJECUTIVO	
</script>



<script>
	//ELIMINACION DE EMPLEADO
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var empleado = $(this).attr("eliminacion");
		var nombreEmpleado = $(this).attr("empleado");

		// console.log(empleado);

		swal({
		  title: "¿Deseas eliminar a "+nombreEmpleado+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a esa persona!",
		  icon: "warning",
		  buttons: 	{
					  cancel: {
					    text: "Cancelar",
					    value: null,
					    visible: true,
					    className: "",
					    closeModal: true,
					  },
					  confirm: {
					    text: "Confirmar",
					    value: true,
					    visible: true,
					    className: "",
					    closeModal: true
					  }
					},
		  dangerMode: true,
		}).then((willDelete) => {
		  if (willDelete) {
		    //ELIMINACION ACEPTADA

		    $.ajax({
				url: 'server/eliminacion_empleado.php',
				type: 'POST',
				data: {empleado},
				success: function(respuesta){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
						  window.location.reload();
						});
					}else{
						console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});


</script>



<script>
	$(".switchEjecutivo").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var elemento = $(this);
		var id_eje = $(this).attr( "id_eje" );
		var estatus = $(this).attr( "estatus" );
		// alert( id_eje );

		// console.log( estatus + id_eje );
		$.ajax({
			url: 'server/editar_estatus_ejecutivo.php',
			type: 'POST',
			data: { id_eje, estatus },

			success: function ( respuesta ) {

				console.log( respuesta );
				if ( respuesta == 'Exito' ) {
					
					if ( estatus == 'Activo' ) {

						elemento.removeClass('lime accent-4').addClass('btn-secondary').removeAttr('estatus').attr( 'estatus', 'Inactivo' );
						
						toastr.error('La cuenta ha sido desactivada');

					} else if ( estatus == 'Inactivo' ) {

						elemento.removeClass('btn-secondary').addClass('lime accent-4').removeAttr('estatus').attr( 'estatus', 'Activo' );
						toastr.success('La cuenta ha sido activada');

					}
				}
			}
		});
		
	});
</script>



<script>
	$('#btn_comision').on('click',function(event) {
        event.preventDefault();
        /* Act on the event */

        var generarComision = 'true';
        $.ajax({
            url: 'server/agregar_comision.php',
            type: 'POST',
            data: { generarComision },
            success: function( res ){

                console.log( res );
                
                if ( res == '' ) {
                    
                    swal("Comisiones agregadas correctamente", "Continuar", "success", {button: "Aceptar",});

                } else {

                   console.log( res );

                }
                
            }
        });
        

        
    });
</script>