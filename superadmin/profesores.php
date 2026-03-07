<?php  

	include('inc/header.php');


?>

<!-- BOTON FLOTANTE AGREGAR PROFESOR-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar Profesor" id="agregarProfesor"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR PROFESOR-->

<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Materias">
			<i class="fas fa-bookmark"></i> 
			Profesores
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-2s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Profesores</a>
</div>
<!-- FIN TITULO -->

<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-left">
		<?php  

		$sqlProfesores = "
			SELECT * 
			FROM profesor
			INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3 
			WHERE id_pla2 = '$plantel' 
			ORDER BY id_pro DESC";
		$resultadoProfesores = mysqli_query($db, $sqlProfesores);


	?>

		<div class="table-responsive">
		<!--  -->
			<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
				<thead>
					<tr class="grey white-text">
						<th class="letraMediana">#</th>
						<th class="letraMediana">Foto</th>
						<th class="letraMediana">Nombre</th>
						<th class="letraMediana">Género</th>
						<th class="letraMediana">Correo</th>
						<th class="letraMediana">Contraseña</th>
						<th class="letraMediana">Teléfono</th>
						<th class="letraMediana">Ingreso</th>
						<th class="letraMediana">Estatus</th>
						<th class="letraMediana">Especialidad</th>
						<th class="letraMediana">Último inicio de sesión</th>
						<th class="letraMediana">Acción</th>
					</tr>
				</thead>


				<?php 
					$i = 1;
					while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
						$id_pro = $filaProfesores['id_pro'];

				?>
					<tr style="height: 100px;">
						
						<td class="letraMediana"><?php echo $i; $i++;?></td>
						
						<td class="letraMediana">
							
							<a href="<?php echo obtenerValidacionFotoUsuario( $filaProfesores['fot_emp'] ) ?>" data-lightbox="roadtrip" data-title="Profesor <?php echo $filaProfesores['nom_pro']; ?>">
			                    
		                        <img src="<?php echo obtenerValidacionFotoUsuario( $filaProfesores['fot_emp'] ) ?>" class="img-fluid" style="height: 65px; width: 60px; border-radius: 40px;" title="Haz click para ampliar la foto de <?php echo $nombre; ?>">
		                    
		                    </a>
							
							

						</td>

						<td class="letraMediana">
						
							<?php echo $filaProfesores['nom_pro'].' '.$filaProfesores['app_pro'].' '.$filaProfesores['apm_pro']; ?>
								
						</td>

						<td class="letraMediana"><?php echo $filaProfesores['gen_pro']; ?></td>
						<td class="letraMediana"><?php echo $filaProfesores['cor_pro']; ?></td>
						<td class="letraMediana"><?php echo $filaProfesores['pas_pro']; ?></td>
						<td class="letraMediana"><?php echo $filaProfesores['tel_pro']; ?></td>
						<td class="letraMediana"><?php echo fechaFormateadaCompacta($filaProfesores['ing_pro']); ?></td>
						<td title="El estatus se cambia en el apartado de empleados">
							

							<?php  
								if ( $filaProfesores['est_pro'] == 'Activo' ) {
							?>
									<div class="row">
										<div class="col-md-12 text-center">
											<a class="btn-floating btn-sm lime accent-4 switchProfesor" id_pro="<?php echo $id_pro; ?>" id_pro="<?php echo $id_pro; ?>" estatus="<?php echo $filaProfesores['est_pro']; ?>" title="Activa/Desactiva la subida de contenido del profesor <?php echo $filaProfesores['nom_pro']; ?>">
												<i class="fas fa-power-off"></i>
											</a>
										</div>
									</div>
							<?php
								} else if ( $filaProfesores['est_pro'] == 'Inactivo' ) {
							?>

									<div class="row">
										<div class="col-md-12 text-center">
											<a class="btn-floating btn-sm btn-secondary switchProfesor" id_pro="<?php echo $id_pro; ?>" id_pro="<?php echo $id_pro; ?>" estatus="<?php echo $filaProfesores['est_pro']; ?>" title="Activa/Desactiva la subida de contenido del profesor <?php echo $filaProfesores['nom_pro']; ?>">
												<i class="fas fa-power-off"></i>
											</a>
										</div>
									</div>

							<?php
								}
							?>
						</td>
						
						<td class="letraMediana"><?php echo $filaProfesores['esp_pro']; ?></td>
						
						<td class="">
							<span class="letraGrande grey-text">
								<?php //echo obtenerUltimoInicioSesionUsuario( $filaProfesores['id_pro'], 'Profesor' ); ?>
							</span>
							
							
						</td>

						<!-- BOTONES DE ACCION -->
						<td class="letraMediana">					

							<a class="chip info-color text-white edicion" title="Editar a <?php echo $filaProfesores['nom_pro']; ?>" edicion="<?php echo $filaProfesores['id_pro']; ?>">Editar</a>


							<a class="chip danger-color text-white eliminacion" title="Eliminar a <?php echo $filaProfesores['nom_pro']; ?>" id_emp="<?php echo $filaProfesores['id_emp3']; ?>" nombreProfesor="<?php echo $filaProfesores['nom_pro'].' '.$filaProfesores['app_pro']; ?>">Eliminar</a>
							
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
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR PROFESOR -->
<div class="modal fade text-left" id="agregarProfesorModal">
  <div class="modal-dialog modal-lg" role="document">
    
	<form id="agregarProfesorFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Alta de profesor</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      	

	      	<div class="row">

	      		<div class="col-md-4"></div>
	      		<!-- FOTO -->
		        <div class="text-center col-md-4">

		            <br>

		            <img src="../img/usuario.jpg" alt="avatar" class="rounded-circle img-fluid" style="border-style: solid; width: 105px; height: 105px;" id="contenedor_imagen_profesor">
		            
		            <div class="md-form" > 
		              <div class="file-field">
		                

		                <div class="file-path-wrapper"> 
		                  <input class="file-path  letraPequena disabled" type="text" placeholder="Sube un archivo en JPG, JPEG o PNG"> 
		                </div>

		                <br>

		                <div class="btn btn-info btn-sm float-left btn-block btn-rounded">
		                  <span>Elige un archivo</span>
		                  <input type="file" id="fot_pro" name="fot_pro"> 
		                </div>
		                
		              </div>
		            </div>
		          
		        </div>
		        <!-- FIN FOTO -->
		        <div class="col-md-4"></div>
	      	</div>

	      	<hr>


	      	<div class="row">
	      		<span class="grey-text letraPequena">
		      		Los campos marcados con * son obligatorios
		      	</span>
	      	</div>
	      	<div class="row">
	      		
	      		<div class="col-md-4">
	      			<div class="md-form mb-5">
			          	<i class="fas fa-user prefix grey-text"></i>
			         	<input type="text" id="nom_pro" name="nom_pro" class="form-control validate" required="">
			          	<label  for="nom_pro">Nombre *</label>
			        </div>
	      		</div>

	      		<div class="col-md-4">
	      			<div class="md-form mb-5">
						<i class="far fa-address-card prefix grey-text"></i>
						<input type="text" id="app_pro" name="app_pro" class="form-control validate" required="">
						<label  for="app_pro">Apellido paterno *</label>
			        </div>
	      		</div>


	      		<div class="col-md-4">
	      			<div class="md-form mb-5">
						<i class="far fa-address-card prefix grey-text"></i>
						<input type="text" id="apm_pro" name="apm_pro" class="form-control validate" required="">
						<label  for="apm_pro">Apellído materno *</label>
			        </div>		
	      		</div>
	      	</div>
	        
	      	<div class="row">
	        	<div class="col-md-6" style="position: relative;">
	        		
	        		<span id="output" style="position: absolute; bottom: 20px; left: 50px"></span>
			        <div class="md-form mb-5" id="contenedor_correo_profesor">
			          	<i class="fas fa-envelope prefix grey-text"></i>
			          	<input type="text" id="cor_pro" class="form-control" name="cor_pro" required="">
			          	<label for="cor_pro">Correo electrónico *</label>
			          
			        </div>

	        	</div>

	        	<div class="col-md-6">
	        		<div class="md-form mb-5" id="contenedor_password_profesor">
			          	<i class="fas fa-key prefix grey-text"></i>
			          	<input type="text" id="pas_pro" name="pas_pro" class="form-control validate" required="" value="123">
			          	<label  for="pas_pro">Contraseña *</label>
			        </div>
	        	</div>
	        </div>


	        <div class="row">
	        	
	        	<div class="col-md-4">
	        		<div class="md-form mb-5">
				        <i class="fas fa-venus-mars prefix grey-text"></i>
				        <input type="text" id="gen_pro" name="gen_pro" class="form-control validate">
				        <label  for="gen_pro">Género</label>
			        </div>
	        	</div>


	        	<div class="col-md-4">
	        		<div class="md-form mb-5">
			          	<i class="fas fa-phone prefix grey-text"></i>
			          	<input type="text" id="tel_pro" name="tel_pro" class="form-control validate">
			          	<label  for="tel_pro">Teléfono</label>
			        </div>
	        	</div>


	        	<div class="col-md-4" style="position: relative;">
	        		<span  style="position: absolute; top: 5px;" class="grey-text">Fecha de nacimiento *</span>
			        <div class="md-form mb-5">
			          	<i class="far fa-calendar-check prefix grey-text"></i>
			         	<input type="date" id="nac_pro" name="nac_pro" class="form-control validate" required="" value="<?php echo date('Y-m-d'); ?>">
			        </div>
	        	</div>
	        
	        </div>

	        


	        <div class="row">
	    		
	    		<div class="col-md-4">
	    			<div class="md-form mb-5">
			          	<i class="fas fa-graduation-cap prefix grey-text"></i>
			          	<input type="text" id="esp_pro" name="esp_pro" class="form-control validate">
			          	<label  for="esp_pro">Especialidad</label>
			        </div>
	    		</div>

	    		<div class="col-md-4">
	    			<div class="md-form mb-5">
			          	<i class="fas fa-map-marker-alt prefix grey-text"></i>
			          	<input type="text" id="dir_pro" name="dir_pro" class="form-control validate">
			          	<label  for="dir_pro">Dirección</label>
			        </div>

	    		</div>

	    		<div class="col-md-4">
	    			<div class="md-form mb-5">
			          	<i class="fas fa-map-marker-alt prefix grey-text"></i>
			          	<input type="text" id="cp_pro" name="cp_pro" class="form-control validate">
			         	<label  for="cp_pro">Código Postal</label>
			        </div>
	    		</div>

	    	</div>


	        
			
	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info btn-sm btn-rounded" type="submit" id="btn_agregar_profesor">Guardar</button>
	      </div>

	  

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR PROFESOR -->



<!-- CONTENIDO MODAL EDITAR PROFESOR -->
<div class="modal fade text-left" id="editarProfesorModal" >
  <div class="modal-dialog modal-lg" role="document">
    
	<form id="editarProfesorFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Edición de profesor</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      	<!--  -->
	      	<div class="row">

	      		<div class="col-md-4"></div>
	      		

	      		<!-- FOTO -->
	            <div class="text-center col-md-4">

	                <br>

	                <img alt="avatar" class="rounded-circle img-fluid" style="border-style: solid; width: 105px; height: 105px;" id="contenedor_imagen_edicion">
	                
	                <div class="md-form" >
	                  <div class="file-field">
	                    

	                    <div class="file-path-wrapper"> 
	                      <input class="file-path  letraPequena disabled" type="text" placeholder="Sube un archivo en JPG, JPEG o PNG" id="fotoText"> 
	                    </div>

	                    <br>

	                    <div class="btn btn-info btn-sm float-left btn-block btn-rounded">
	                      <span>Elige un archivo</span>
	                      <input type="file" id="foto" name="foto"> 
	                    </div>
	                    
	                  </div>
	                </div>
	              
	            </div>
	            <!-- FIN FOTO -->


		        <div class="col-md-4"></div>
	      	</div>

	      	<hr>


	      	<div class="row">
	      		<span class="grey-text letraPequena">
		      		Los campos marcados con * son obligatorios
		      	</span>
	      	</div>
	      	<div class="row">
	      		
	      		<div class="col-md-4">
	      			<div class="md-form mb-5">
			          	<i class="fas fa-user prefix grey-text"></i>
			         	<input type="text" id="nombre" name="nombre" class="form-control validate" required="">
			          	<label  for="nombre">Nombre *</label>
			        </div>
	      		</div>

	      		<div class="col-md-4">
	      			<div class="md-form mb-5">
						<i class="far fa-address-card prefix grey-text"></i>
						<input type="text" id="apellido1" name="apellido1" class="form-control validate" required="">
						<label  for="apellido1">Apellido paterno *</label>
			        </div>
	      		</div>


	      		<div class="col-md-4">
	      			<div class="md-form mb-5">
						<i class="far fa-address-card prefix grey-text"></i>
						<input type="text" id="apellido2" name="apellido2" class="form-control validate" required="">
						<label  for="apellido2">Apellído materno *</label>
			        </div>		
	      		</div>
	      	</div>
	        
	      	<div class="row">
	        	<div class="col-md-6" style="position: relative;">
	        		
	        		<span id="outputEdicion" style="position: absolute; bottom: 20px; left: 50px"></span>
			        <div class="md-form mb-5" id="contenedor_correo_profesor">
			          	<i class="fas fa-envelope prefix grey-text"></i>
			          	<input type="text" id="correoEdicion" class="form-control" name="correoEdicion" required="">
			          	<label for="correoEdicion">Correo electrónico *</label>
			          
			        </div>

	        	</div>

	        	<div class="col-md-6">
	        		<div class="md-form mb-5" id="contenedor_password_profesor">
			          	<i class="fas fa-key prefix grey-text"></i>
			          	<input type="text" id="password" name="password" class="form-control validate" required="" value="123">
			          	<label  for="password">Contraseña *</label>
			        </div>
	        	</div>
	        </div>


	        <div class="row">
	        	
	        	<div class="col-md-4">
	        		<div class="md-form mb-5">
				        <i class="fas fa-venus-mars prefix grey-text"></i>
				        <input type="text" id="genero" name="genero" class="form-control validate">
				        <label  for="genero">Género</label>
			        </div>
	        	</div>


	        	<div class="col-md-4">
	        		<div class="md-form mb-5">
			          	<i class="fas fa-phone prefix grey-text"></i>
			          	<input type="text" id="telefono" name="telefono" class="form-control validate">
			          	<label  for="telefono">Teléfono</label>
			        </div>
	        	</div>


	        	<div class="col-md-4" style="position: relative;">
	        		<span  style="position: absolute; top: 5px;" class="grey-text">Fecha de nacimiento *</span>
			        <div class="md-form mb-5">
			          	<i class="far fa-calendar-check prefix grey-text"></i>
			         	<input type="date" id="nacimiento" name="nacimiento" class="form-control validate" required="" value="<?php echo date('Y-m-d'); ?>">
			        </div>
	        	</div>
	        
	        </div>

	        


	        <div class="row">
	    		
	    		<div class="col-md-4">
	    			<div class="md-form mb-5">
			          	<i class="fas fa-graduation-cap prefix grey-text"></i>
			          	<input type="text" id="especialidad" name="especialidad" class="form-control validate">
			          	<label  for="especialidad">Especialidad</label>
			        </div>
	    		</div>

	    		<div class="col-md-4">
	    			<div class="md-form mb-5">
			          	<i class="fas fa-map-marker-alt prefix grey-text"></i>
			          	<input type="text" id="direccion" name="direccion" class="form-control validate">
			          	<label  for="direccion">Dirección</label>
			        </div>

	    		</div>

	    		<div class="col-md-4">
	    			<div class="md-form mb-5">
			          	<i class="fas fa-map-marker-alt prefix grey-text"></i>
			          	<input type="text" id="codigo" name="codigo" class="form-control validate">
			         	<label  for="codigo">Código Postal</label>
			        </div>
	    		</div>

	    	</div>
	      	<!--  -->


	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">
	        </div>



	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info btn-sm btn-rounded" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>



	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR PROFESOR -->



<?php  

	include('inc/footer.php');

?>


<script>



		$('#myTable').DataTable({
			
		
			dom: 'Bfrtlip',
			pageLength: -1,
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
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

	

</script>




<script>
	//VALIDACION EN TEMPO REAL DESDE EL INPUT DEL CORREO


	$('#cor_pro').keyup(function(event) {
        
        validacionCorreoTiempoReal();
        
    });

	function validacionCorreoTiempoReal(){
		var correo = $('#cor_pro').val();


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
          $('#output').text("¡Ingresa un correo electrónico!");
        }
	}


	$('#correoEdicion').keyup(function(event) {
	     
	     validacionCorreoTiempoRealEdicion();   
        
    });

	
	validacionCorreoTiempoRealEdicion();

	function validacionCorreoTiempoRealEdicion(){

		var correoEdicion = $('#correoEdicion').val();
        var identificador = $('#identificador').val();

        console.log($('#correoEdicion').val());

        if (correoEdicion != '') {
          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: {correoEdicion, identificador},
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
          $('#outputEdicion').text("¡Ingresa un correo electrónico!");
        }
		
	}


</script>

<script>

	//FORMULARIO DE CREACION DE PROFESOR
	//CODIGO PARA AGREGAR PROFESOR NUEVO ABRIENDO MODAL


	$('#agregarProfesor').on('click', function(event) {
		event.preventDefault();
		$('#agregarProfesorModal').modal('show');
		$('#agregarProfesorFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		setTimeout(function(){
			$('#nom_pro').focus();
			$('#contenedor_password_profesor label').addClass('active');
			$('#output').text('');
		}, 300);

		$('#btn_agregar_profesor').removeAttr('disabled');

	});


	$('#agregarProfesorFormulario').on('submit', function(event) {
		event.preventDefault();

		$('#btn_agregar_profesor').attr('disabled','disabled');

		var correo = $('#cor_pro').val();


		$.ajax({
			url: 'server/validacion_correo.php',
			type: 'POST',
			data: {correo},
			success: function(respuesta){
				if (respuesta == "disponible") {

					$('#validacionCorreo').attr({class: 'text-info text-center'}).text("¡Correcto!");
					if ($("#fot_pro")[0].files[0]) {

						var fileName = $("#fot_pro")[0].files[0].name;
						var fileSize = $("#fot_pro")[0].files[0].size;


						var ext = fileName.split('.').pop();

						
						if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
							if (fileSize < 3000000) {
								$.ajax({
						
									url: 'server/agregar_profesor.php',
									type: 'POST',
									data: new FormData(agregarProfesorFormulario), 
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
						
							url: 'server/agregar_profesor.php',
							type: 'POST',
							data: new FormData(agregarProfesorFormulario), 
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
	//EDICION DE PROFESOR

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE PROFESOR

	$('.edicion').on('click', function(){
		$('#editarProfesorFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionProfesor = $(this).attr("edicion");
		$('#editarProfesorFormulario label').addClass('active');
		$('#editarProfesorFormulario i').addClass('active');

		setTimeout(function(){
			$('#nombre').focus();
			// $('#contenedor_password_profesor label').addClass('active');
			$('#outputEdicion').text('');
		}, 300);
		//console.log(edicionProfesor);


		$.ajax({
			url: 'server/obtener_profesor.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionProfesor},
			success: function(datos){

				$('#editarProfesorModal').modal('show');
				$('#nombre').attr({value: datos.nom_pro});
				$('#apellido1').attr({value: datos.app_pro});
				$('#apellido2').attr({value: datos.apm_pro});
				$('#genero').attr({value: datos.gen_pro});
				$('#telefono').attr({value: datos.tel_pro});
				$('#correoEdicion').attr({value: datos.cor_pro});
				$('#password').attr({value: datos.pas_pro});
				$('#nacimiento').attr({value: datos.nac_pro});
				$('#ingreso').attr({value: datos.ing_pro});
				$('#foto').attr({value: datos.fot_pro});
				$('#fotoText').attr({value: datos.fot_pro});
				$('#especialidad').attr({value: datos.esp_pro});
				$('#direccion').attr({value: datos.dir_pro});
				$('#codigo').attr({value: datos.cp_pro});				
				$('#identificador').attr({value: datos.id_pro});

				console.log( datos.fot_emp );
				if ( datos.fot_emp == null ) {


                    $('#contenedor_imagen_edicion').removeAttr('src').attr( 'src', '../img/usuario.jpg' ); 
                    $('#fotoText').removeAttr('placeholder').attr('placeholder', 'Sube un archivo en JPG, JPEG o PNG');

                } else {
                
                    $('#contenedor_imagen_edicion').removeAttr('src').attr( 'src', '../uploads/'+datos.fot_emp ); 
                    $('#fotoText').removeAttr('placeholder').attr('placeholder', datos.fot_emp);
                
                }

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL PROFESOR
				$('#editarProfesorFormulario').on('submit', function(event) {
					event.preventDefault();

					var correoEdicion = $('#correoEdicion').val();
					var identificador = $('#identificador').val();
					var tipo = "Profesor";


					$.ajax({
					  url: 'server/validacion_correo.php',
					  type: 'POST',
					  data: {correoEdicion, identificador, tipo},
					  success: function(respuesta){
					    console.log(respuesta);

					    if (respuesta == "disponible" || respuesta == "mio") {

					      $('#validacionCorreoEdicion').attr({class: 'text-info text-center'}).text("¡Correcto!");
						      	if ($("#foto")[0].files[0]) {

									var fileName = $("#foto")[0].files[0].name;
									var fileSize = $("#foto")[0].files[0].size;


									var ext = fileName.split('.').pop();

									
									if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
										if (fileSize < 3000000) {
											$.ajax({
									
												url: 'server/editar_profesor.php',
												type: 'POST',
												data: new FormData(editarProfesorFormulario),
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
											swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
										}
										
									}else{
										swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
									}

								}else{
									$.ajax({
									
										url: 'server/editar_profesor.php',
										type: 'POST',
										data: new FormData(editarProfesorFormulario), 
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

								}				     

					      
					    }else{

					      $('#validacionCorreoEdicion').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

					    }
					  }
					}); 
						

						

					
				});
				
			}
		});
		

	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE PROFESOR



	
</script>



<script>
	$(".switchProfesor").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var elemento = $(this);
		var id_pro = $(this).attr( "id_pro" );
		var estatus = $(this).attr( "estatus" );
		// alert( id_pro );

		// console.log( estatus + id_pro );
		$.ajax({
			url: 'server/editar_estatus_profesor.php',
			type: 'POST',
			data: { id_pro, estatus },

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
	$('#fot_pro').on('change', function(event) {
        event.preventDefault();

        readURL(this);

      });


      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#contenedor_imagen_profesor')
              .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }
</script>

<script>
	$('#nom_pro').on('keyup', function(event) {
        /* Act on the event */

	    obtenerCorreoCompuestoEjecutivo();
	    var correo = $('#cor_pro').val();
	    // console.log( correo );
        validacionCorreoTiempoReal( correo );
	    $('#contenedor_correo_profesor label').addClass('active');

	    
	});


	$('#app_pro').on('keyup', function(event) {
        /* Act on the event */

	    obtenerCorreoCompuestoEjecutivo();
	    var correo = $('#cor_pro').val();
	    // console.log( correo );
        validacionCorreoTiempoReal( correo );
	    $('#contenedor_correo_profesor label').addClass('active');

	    
	});

	function obtenerCorreoCompuestoEjecutivo(){
		var cadena = $('#nom_pro').val()+'-'+$('#app_pro').val();
		var y = remove_accents( cadena.split(' ').slice(0,2).join('-').replace(' ', '-').toLowerCase() );
		var correo = $('#cor_pro').val( y+'@<?php echo $folioPlantel; ?>.com' );
        // console.log( correo );
		// y = "ABC+XYZ"
        return correo;

    }
</script>


<script>
	$('#foto').on('change', function(event) {
        event.preventDefault();

        readURL(this);

    });


    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {
            $('#contenedor_imagen_edicion')
              .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        
        }
    }

</script>

<script>
	//ELIMINACION DE EMPLEADO
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var empleado = $(this).attr("id_emp");
		var nombreProfesor = $(this).attr("nombreProfesor");

		// console.log(empleado);

		swal({
		  title: "¿Deseas eliminar a "+nombreProfesor+"?",
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