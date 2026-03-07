<?php  

	include('inc/header.php');
	// include('inc/cabeceras.php');
	 // header('Location: /profesor/mensajes2.php');

?>

<style>
	/*ESTILOS DE SALA DE CHAT SCROLLING*/
	.card.chat-room .members-panel-1,
	.card.chat-room .chat-1 {
	position: relative;
	overflow-y: scroll; }

	.card.chat-room .members-panel-1 {
	height: 570px; }

	.card.chat-room .chat-1 {
	height: 495px; }

	.card.chat-room .friend-list li {
	border-bottom: 1px solid #e0e0e0; }
	.card.chat-room .friend-list li:last-of-type {
	border-bottom: none; }


	.rare-wind-gradient {
	background-image: -webkit-gradient(linear, left bottom, left top, from(#a8edea), to(#fed6e3));
	background-image: -webkit-linear-gradient(bottom, #a8edea 0%, #fed6e3 100%);
	background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%); }

	



	.botonesRespuesta {
	  position: absolute;
	  right: -10px;
	  bottom: 10px;
	  z-index: 100;
	}

	.botonesRespuestaPadre {
	  position: relative;
	}

	.active{
		background-color: #DEDEDE;
	}

</style>
      
<!-- MODAL -->

<!-- MODAL ARCHIVO -->
<div class="modal fade" id="modal_archivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Comparte un archivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formulario_archivo" enctype="multipart/form-data" method="POST">
	      <div class="modal-body">

	      	<div class="row">
		        <div class="col-md-12">
		                
		                                            
	                    <div class="file-upload-wrapper">
	                      	<div class="input-group mb-3 border border-success">
	                        	<input type="file" id="arc_men" name="arc_men" class="file_upload" placeholder="Sube Archivo"  required="" />

	                        	<input type="hidden" id="id_sal_archivo" name="id_sal_archivo">

	                      	</div>
	                    </div>

	                    <div class="progress md-progress" style="height: 20px">
	                        <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo">
	                            
	                          
	                        </div>
	                    </div>
		              

		        </div>
		    </div>
	      	
	      </div>
	      <div class="modal-footer">
	        <button class="btn btn-info white-text btn-rounded waves-effect btn-sm" type="submit" title="Enviar archivo" id="btn_enviar_archivo">
                Enviar archivo
            </button> 
	      </div>
	  </form>
    
    </div>
  
  </div>

</div>
<!-- FIN MODAL ARCHIVO -->



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
							
								<input type="file" id="fot_usu" name="fot_usu" class="file_upload " placeholder="Sube Archivo"/>
							
							
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

<!-- MODAL CONSULTAR USUARIOS -->

<div class="modal fade" id="modal_consulta_usuarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document" style="border-radius: 20px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Consultar usuarios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

	      <div class="modal-body">

			<div id="contenedor_consulta_usuarios">	
            </div>

	      </div>

	      <div class="modal-footer text-center">
	        
	        <a class="btn btn-sm btn-rounded btn-secondary" title="Cerrar" data-dismiss="modal">
            	Cerrar
        	</a>
	      
	      </div>
    
    </div>
  
  </div>

</div>
<!-- FIN MODAL CONSULTAR USUARIOS -->


<!-- FIN MODAL -->

<!-- Jumbotron Main Content-->

	<?php  
		// echo obtener_existencia_sala( 43, 'Profesor',  43, 'Profesor' );
	?>

	<div class="card chat-room" style="background-image: url('../img/white.png'); background-repeat: no-repeat; background-size: cover; background-position: center center;">
	  <div class="card-body " >

	    <!-- Grid row -->
	    <div class="row">

	      <!-- Grid column -->
	      <div class="col-md-6 col-xl-4 px-0">


	      	
	      	<!-- Card -->
			<div class="card testimonial-card hoverable" style="position: relative;">

			  	<!-- Background color -->
			  	<div class="bg-info" style="height: 80px;">
			  		<div class="avatar mx-auto white file-upload-wrapper view modalFoto" title="Haz click para cambiar tu foto" style="right: 150px; width: 50px; height: 50px; bottom: -75px;">

			  			
					  	<div class="view overlay">
					    	<img src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>" class="rounded-circle modalFoto" style="border-radius: 50%; " alt="agregar foto nueva" id="avatar">
					    	<div class="mask rgba-white-strong flex-center">
					    		<i class="fas fa-camera fa-2x grey-text"></i>
					    	</div>
					    </div>
					</div>


			  	</div>

			  	<span class="white-text" style="position: absolute; top: 30px; left: 30%">
			  		
		  				<?php echo $nombre; ?>
		  			
			  	</span>


			</div>

	      	
			<div class="row p-2">

				<div class="col-md-12 text-center">


					<form class="form-inline">
					  	<i class="fas fa-search" aria-hidden="true"></i>
					  	<input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Buscar sala:" id="input_buscador_sala" 
					    aria-label="Search">
					</form>

				</div>

			</div>

	        <div class="white z-depth-1 px-2 pt-3 pb-0 members-panel-1 scrollbar-light-blue " id="contenedor_contactos_mensajeria">
	          
	        </div>

	      </div>
	      <!-- Grid column -->

	      <!-- Grid column -->


	      <div class="col-md-6 col-xl-8 pl-md-3 px-lg-auto px-0">
	    	<div class="bg-info row hoverable" style="height: 80px;" id="cabecera_mensajeria">
	    		
	    		<div class="col-md-12 text-left">
	    			<br>
	    			
	    			<a class="btn-link text-white p-2 letraMediana btn_accion_mensajeria" href="#" id="btn_consulta_usuarios" title="Consulta los participantes de la sala" style="display: none;">	<i class="fas fa-users"></i> Ver participantes
	    			</a>

	    			<?php  
	    				if ( $tipo != 'Alumno'  ) {
	    			?>
	    					<a class="btn-link text-white p-2 letraMediana btn_accion_mensajeria" href="#" id="btn_archivo_mensajeria" title="Envía un archivo" style="display: none;"><i class="fas fa-paperclip"></i> Enviar archivo</a>
	    			<?php
	    				}
	    			?>

					<a class="btn-link text-white p-2 letraMediana btn_accion_mensajeria" href="#" id="btn_eliminacion_sala" title="Elimina esta sala de mensajería" style="display: none;"><i class="fas fa-trash-alt"></i> Eliminar sala</a>


	    		</div>
	    		
	    	</div>
	        <div class="chat-message">

	      
	          <ul class="list-unstyled chat-1 scrollbar-light-blue" id="contenedor_mensajes_sala" style="overflow-x: hidden;">
	          </ul>

	          <form id="formChat" role="form">
	            <div class="white">
	              <div class="form-group basic-textarea" style="position: relative;">

	              	

					<textarea class="form-control pl-2 my-0" rows="3" placeholder="Escribe un mensaje..." id_usuario="" id="mensaje" soy="" sala="" required=""></textarea>
	               

	              </div>

	            </div>

	            
	            <!-- <button type="button" class="btn btn-outline-pink btn-rounded btn-sm waves-effect waves-dark float-right" id="btn_send">Enviar</button> -->
	            <button class="btn btn-primary float-right btn-sm" id="btn_enviar">Enviar  <i class="fas fa-paper-plane"></i></button>
	          </form>

	        </div>

	      </div>
	      <!-- Grid column -->

	    </div>
	    <!-- Grid row -->

	  </div>
	</div>




	


<?php  

	include('inc/footer.php');

?>

<script>
	$('#mainContainer').removeClass('jumbotron black-text mx-2 mb-5');
	$('#contenedor_principal').removeClass('container-fluid');
</script>

<!-- PAGINACION DE CONTACTOS POR SCROLL -->
<script>

  var limite = 10;
  var inicio = 0;
  var action = 'inactive';

  // obtener_contactos_mensajeria(limite, inicio);
  	function obtener_contactos_mensajeria2(limite, inicio){
  		var id_sub_hor = '<?php echo $id_sub_hor; ?>';

      	$.ajax({
	        url: "server/obtener_contactos_mensajeria.php",
	        method: "POST",
	        data: {limite, inicio, id_sub_hor},
	        cache: false,
	        success: function(respuesta) {
	        	$('#contenedor_contactos_mensajeria').html(respuesta);
	        	obtener_contactos_mensajeria(limite, inicio);
	        }
	    });
	}

  function obtener_contactos_mensajeria(limite, inicio){
  	console.log('obtener_contactos_mensajeria');

  	<?php  
		if ( isset( $_GET['id_sub_hor'] ) ) {

			$id_sub_hor = $_GET['id_sub_hor'];
		
		} else {

			$id_sub_hor = 'falso';

		}
	?>

	var id_sub_hor = '<?php echo $id_sub_hor; ?>';
		

      $.ajax({
         url: "server/obtener_contactos_mensajeria.php",
         method: "POST",
         data: {limite, inicio, id_sub_hor},
         cache: false,
         success:function(data) {
              $('#contenedor_contactos_mensajeria').append(data);
              if(data == '')
              {

               	action = 'active';
              }
              else
              {
				action = "inactive";
              }
          }
      });
  }

  if(action == 'inactive') {
      action = 'active';
      obtener_contactos_mensajeria(limite, inicio);
  }


  $('#contenedor_contactos_mensajeria').on('scroll', function() {
      let div = $(this).get(0);
      if(div.scrollTop + div.clientHeight >= div.scrollHeight) {
          // do the lazy loading here

          action = 'active';
          inicio = inicio + limite;
          setTimeout(function(){
              obtener_contactos_mensajeria(limite, inicio);
          }, 1000);
      }
  });
</script>
<!-- FIN PAGINACION DE CONTACTOS POR SCROLL -->

<!-- ENVIO DE MENSAJES -->
<script>
	$('#btn_enviar').on('click',  function(event) {
		event.preventDefault();
		/* Act on the event */
		agregar_mensaje();
	});


	$('#mensaje').keypress(function(event){
	  var keycode = (event.keyCode ? event.keyCode : event.which);
	  if(keycode == '13'){
	    agregar_mensaje();
	  }
	});

	function agregar_mensaje(){
		
		var mensaje = $('#mensaje').val();


		var id_sal = $('#btn_enviar').attr("id_sal");

		// alert(id_sal);

		if ( id_sal == '' ) {

			// alert('sala vacia');
			swal("¡Selecciona una sala!", "Continuar", "info", {button: "Aceptar",});

		} else {
			


			if ( mensaje != '' ) {

				$.ajax({
					url: 'server/agregar_mensaje.php',
					type: 'POST',
					data: { mensaje, id_sal },
					success: function( respuesta ){

						console.log( respuesta );
						obtener_mensajes_sala( id_sal );
						$('#mensaje').val('').focus();
						

						obtener_scroll();

						var datos = {
						    
						    modulo : 'Mensaje',
						    id_sal : id_sal

						};

						// socket.send( JSON.stringify( datos ) );
						
						obtener_datos_sala( id_sal );
					}
				});
				

	    	}
		}

		
        

	}

	function obtener_scroll(){
		setTimeout(function(){
			var altura = $("#contenedor_mensajes_sala").prop("scrollHeight") + 1000;
				$("#contenedor_mensajes_sala").scrollTop(altura);
			}, 200);
	}

</script>
<!-- FIN ENVIO DE MENSAJES -->

<script>

	$('.file_upload').file_upload();

	$('.modalFoto').on('click', function(event) {
		event.preventDefault();
		$('#agregarFotoModal').modal('show');
		
	});

	
	function readURLFotoUsuario(input) {

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

	$("#fot_usu").change(function() {
	 	readURLFotoUsuario(this);

	    var formularioUsuarioFoto = $('#formularioUsuarioFoto')[0]; // You need to use standard javascript object here
		var formularioFoto = new FormData(formularioUsuarioFoto);

		formularioFoto.append('fot_usu', $('#fot_usu')[0].files[0]); 

	  	if ($("#fot_usu")[0].files[0]) {

			var fileName = $("#fot_usu")[0].files[0].name;
			var fileSize = $("#fot_usu")[0].files[0].size;


			var ext = fileName.split('.').pop();

			
			if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
				if (fileSize < 3000000) {
					$.ajax({
			
						url: 'server/editar_profesor.php',
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

<script>
	$('#btn_consulta_usuarios').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		console.log('consul');
		var id_sal = $(this).attr('id_sal');
		obtener_consulta_usuarios( id_sal );
		

		
		
	});


	function obtener_consulta_usuarios( id_sal ){
		if ( id_sal != undefined ) {
			$('#modal_consulta_usuarios').modal('show');

			$.ajax({
			
				url: 'server/obtener_consulta_usuarios.php',
				type: 'POST',
				data: { id_sal },
				success: function( respuesta ){

					$('#contenedor_consulta_usuarios').html( respuesta );
				
				}
			
			});
		} else {
			toastr.info('Selecciona una sala de mensajes primero :D');
		}
	}
</script>


<script>
	$('#btn_archivo_mensajeria').on('click', function(event) {
    	event.preventDefault();
    	/* Act on the event */

    	$("#barra_estado_archivo").attr({style: 'width: 0%; height: 20px;'}).text("0").removeClass('').addClass('progress-bar text-center white-text');

    	$('#modal_archivo').modal('show');

    	
    	// $('#id_sal_archivo').val(id_sal);

    });


    $('#formulario_archivo').on('submit', function(event) {
        event.preventDefault();

        console.log('click');
        if ($("#arc_men")[0].files[0]) {

            var fileName = $("#arc_men")[0].files[0].name;
            var fileSize = $("#arc_men")[0].files[0].size;


            var ext = fileName.split('.').pop();

            
            if(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf' || ext == 'xlsx'){
                if (fileSize < 10000000) {
                    

                    let barra_estado_archivo = $("#barra_estado_archivo");

                    //Eliminacion de "Listo"
                    barra_estado_archivo.text("");

                    //Remueve clase de estatus listo
                    barra_estado_archivo.removeClass();

                    //Agrega la clase inicial del progress bar
                    barra_estado_archivo.addClass('progress-bar text-center white-text');

                    var id_sal = obtener_sala_activa();
                    
                    var formulario_archivo = new FormData( $('#formulario_archivo')[0] );
                    formulario_archivo.append('id_sal', id_sal );

                    $.ajax({

                        xhr: function() {
                          
                            var peticion = new window.XMLHttpRequest();

                            peticion.upload.addEventListener("progress", (event)=>{
                            let porcentaje = Math.round((event.loaded / event.total) *100);
                            //console.log(porcentaje);

                            barra_estado_archivo.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
                            barra_estado_archivo.text(porcentaje+'%');

                          });

                          peticion.addEventListener("load", ()=>{
                            barra_estado_archivo.removeClass();
                            barra_estado_archivo.addClass('progress-bar text-center white-text bg-success');
                            barra_estado_archivo.text("Listo");

                            // toastr.success('¡Subido Correctamente!');
                          });

                          return peticion;
                          },
                        url: 'server/agregar_mensaje.php',
                        type: 'POST',
                        data: formulario_archivo,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(respuesta){

                            console.log(respuesta);
                          	
                          	// $("#btn_enviar").html('<i class="fas fa-check white-text"></i> <span>Subida Exitosa</span>');
                            swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                            then((value) => {

                            	$('#modal_archivo').modal('hide');
                                
       							obtener_mensajes_sala( id_sal );
								$('#mensaje').val('').focus();
								

								obtener_scroll();

								var datos = {
								    
								    modulo : 'Mensaje',
								    id_sal : id_sal

								};

								socket.send( JSON.stringify( datos ) );
								
								obtener_datos_sala( id_sal );


                            });
                        }
                    });
                }else{
                    swal ( "¡Archivo inválido!" ,  "¡Te recordamos que el peso no debe exceder los 10MB!" ,  "error" );
                }
                
            }else{
                swal ( "¡Archivo inválido!" ,  "¡Te recordamos que los formatos aceptados son word, excel, power point, pdf, jpeg, jpg o png!" ,  "error" );
            }

        }

        
    });
</script>