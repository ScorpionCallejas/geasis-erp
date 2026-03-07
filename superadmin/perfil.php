<?php  

	include('inc/header.php');

?>



<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina badge blue-grey darken-4 hoverable" title="Perfil"><i class="fas fa-bookmark"></i> Perfil</span>
		<br>
		<div class=" badge badge-warning text-white">
			<a href="index.php" title="Inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="#" title="Estás aquí">Perfil</a>
			
		</div>
		
	</div>
		
</div>
<!-- FIN TITULO -->
 
<hr>

<!-- CONTENIDO JUMBOTRON -->
<div class="row">
	<div class="col-md-4">

		
		
        <!-- Card -->
		<div class="card testimonial-card" style="border-radius: 20px">

		  <!-- Background color -->
		  <div class="card-up  bg-info" style="border-radius: 20px;"></div>

			  <!-- Avatar -->
			  <div class="avatar mx-auto white file-upload-wrapper view modalFoto" title="Haz click para cambiar tu foto">
			  	<div class="view overlay">
			    	<img src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>" class="rounded-circle modalFoto" style="border-radius: 50%;" alt="agregar foto nueva" id="avatar">
			    	<div class="mask rgba-white-strong flex-center">
			    		<i class="fas fa-camera fa-2x"></i>
			    	</div>
			    </div>
			  </div>

			
			<!-- CONTENIDO MODAL AGREGAR FOTO -->
			<div class="modal fade text-left " id="agregarFotoModal">
			  <div class="modal-dialog modal-sm" role="document">
			    
				    <div class="modal-content">
				      <div class="modal-header text-center">
				        <h4 class="modal-title w-100 font-weight-bold">Selecciona foto</h4>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body mx-3">
						 <!-- MODAL FOTO -->
							<form id="formularioUsuarioFoto"  enctype="multipart/form-data">
								<div class="file-upload-wrapper rounded-circle">
									<div class="input-group mb-3 border border-success">
										
											<input type="file" id="fot_emp" name="fot_emp" class="file_upload " placeholder="Sube Archivo"/>
										
										
									</div>
								</div>
							</form>
						  <!-- FIN MODAL FOTO -->
				      </div>
				    </div>
				</form>

			  </div>
			</div>
			<!-- FIN CONTENIDO MODAL AGREGAR FOTO -->
			 
			


			<!-- Content -->

			<div class="card-body" >
				<!-- Name -->
				<h4 class="card-title" id="nombre_perfil"><?php echo $nomResponsable; ?></h4>
				<h5 class="card-title">Tipo: <?php echo $tipoUsuario; ?></h5>

			</div>

		</div>
		<!-- Card -->
			<br>
			<br>


			
		
    </div>


    <div class="col-md-8">
    	<!-- Card -->
		<div class="card testimonial-card" style="border-radius: 20px;">

		  <!-- Background color -->
		  <div class="bg-info white-text p-4" style="border-radius: 20px;">
		  	<h4 class="card-title">Datos personales</h4>
		  </div>

		  <!-- Avatar -->
	

		  	<!-- Content -->
		  	<div class="card-body">
		    <!-- DATOS -->
				
				

		    	<div class="row">
	        		<div class="col-md-4">
	        			<a class="text-white btn-floating btn-info btn-sm  bg-info"><i class="fas fa-envelope"></i></a> 
	        			<br>
	        			<span class="letraMediana">
	        				Cuenta: <?php echo $correoUsuario; ?>
	        			</span>
	        		</div>


	        		<div class="col-md-4">
	        			<a class="text-white btn-floating btn-info btn-sm  bg-info"><i class="far fa-calendar-check"></i></a> 
	        			<br>
	        			<span class="letraMediana">
	        				Ingreso: <?php echo fechaFormateadaCompacta2($ingresoUsuario); ?>
	        			</span>
	        		</div>


	        		<div class="col-md-4">
	        			<a class="text-white btn-floating btn-info btn-sm  bg-info"><i class="fas fa-male"></i></a> 
	        			<br>
	        			<span class="letraMediana">
	        				Género: <?php echo $generoUsuario; ?>
	        			</span>
	        		</div>

	        		
	        	</div>

	        	<hr>
				

				<form id="formularioUsuarioDatos">

					<!-- NOMBRE -->
					<div class="row">
            	
	            		<div class="col-md-4">
	            			
	            			<div class="md-form mb-5">

								<input type="text" id="nom_adm" name="nom_adm" class="form-control validate estilo_input" value="<?php echo $nombreUsuario; ?>">
								<label class="estilo_input" for="nom_adm">Nombre</label>
		                    </div>
	            		
	            		</div>


	            		<div class="col-md-4">
	            			
	            			<div class="md-form mb-5">

								<input type="text" id="app_adm" name="app_adm" class="form-control validate estilo_input" value="<?php echo $appUsuario; ?>">
								<label class="estilo_input" for="app_adm">Apellido paterno</label>
		                    </div>
	            		
	            		</div>


	            		<div class="col-md-4">
	            			
	            			<div class="md-form mb-5">

								<input type="text" id="apm_adm" name="apm_adm" class="form-control validate estilo_input" value="<?php echo $apmUsuario; ?>">
								<label class="estilo_input" for="apm_adm">Apellido materno</label>
		                    </div>
	            		
	            		</div>

	            	</div>
					<!-- NOMBRE -->
					
					<div class="row">
            	
	            		<div class="col-md-4">
	            			
	            			<div class="md-form mb-5">

								<input type="text" id="pas_adm" name="pas_adm" class="form-control validate estilo_input" value="<?php echo $contrasenaUsuario; ?>">
								<label class="estilo_input" for="pas_adm">Contraseña</label>
		                    </div>
	            		
	            		</div>


	            		<div class="col-md-4">
	            			
	            			<div class="md-form mb-5">

								<input type="text" id="tel_adm" name="tel_adm" class="form-control validate estilo_input" value="<?php echo $telefonoUsuario; ?>">
								<label class="estilo_input" for="tel_adm">Teléfono</label>
		                    </div>
	            		
	            		</div>


	            		<div class="col-md-4">
	            			
	            			<div class="md-form mb-5">

								<input type="text" id="dir_adm" name="dir_adm" class="form-control validate estilo_input" value="<?php echo $direccionUsuario; ?>">
								<label class="estilo_input" for="dir_adm">Dirección</label>
		                    </div>
	            		
	            		</div>

	            	</div>

	          

	            	<button type="submit" class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..."  id="btn_formulario_usuario">
			            Guardar
			        </button>


				</form>
	        	

		    
		  	</div>

		</div>
		<!-- Card -->
    </div>
</div>
<!-- FIN CONTENIDO JUMBOTRON -->




<br>
<br>



<?php  

	include('inc/footer.php');

?>
<script>

    $('.file_upload').file_upload();


    setTimeout(function(){
 		$('#pas_adm').focus();
    }, 500 );
   

</script>


<script>

	//FOTO MODAL
	$('.modalFoto').on('click', function(event) {
		event.preventDefault();
		$('#agregarFotoModal').modal('show');
		
	});


	$('#formularioUsuarioDatos').on('submit', function(event) {
			console.log("submit");

			/* Act on the event */
			event.preventDefault();

			$.ajax({
	
				url: 'server/editar_perfil.php',
				type: 'POST',
				data: new FormData(formularioUsuarioDatos), 
				processData: false,
				contentType: false,
				cache: false,
				success: function(respuesta){
					console.log(respuesta);


					if (respuesta == 'Exito') {

						$("#nombre_perfil").text( $('#nom_adm').val()+' '+$('#app_adm').val()+' '+$('#apm_adm').val() );
						generarAlerta( 'Cambios guardados' );
						
					}
				}
			});
	});



	function readURL(input) {

	  if (input.files && input.files[0]) {
	    var reader = new FileReader();

	    reader.onload = function(e) {
	      $('#avatar').attr('src', e.target.result);

	      $('#foto_usuario').attr('src', e.target.result);
	      // $('#icono').hide();
	    }

	    reader.readAsDataURL(input.files[0]);
	  }
	}

	$("#fot_emp").change(function() {
	 	readURL(this);

	    var formularioUsuarioFoto = $('#formularioUsuarioFoto')[0]; // You need to use standard javascript object here
		var formularioFoto = new FormData(formularioUsuarioFoto);

		formularioFoto.append('fot_emp', $('#fot_emp')[0].files[0]); 

	  	if ($("#fot_emp")[0].files[0]) {

			var fileName = $("#fot_emp")[0].files[0].name;
			var fileSize = $("#fot_emp")[0].files[0].size;


			var ext = fileName.split('.').pop();

			
			if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
				if (fileSize < 3000000) {
					$.ajax({
			
						url: 'server/editar_perfil.php',
						type: 'POST',
						data: formularioFoto, 
						processData: false,
						contentType: false,	
						cache: false,
						success: function(respuesta){
						console.log(respuesta);

							if (respuesta == 'Exito') {
								
								// then((value) => {
								//   window.location.reload();
								// });

								generarAlerta('Cambios guardados');

								
							}
						}
					});
				}else{
					swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
				}
				
			}else{
				swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
			}

		}
	  		
	});
	
</script>