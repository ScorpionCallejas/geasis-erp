<?php  

	include('inc/cabeceras.php');
	include('inc/funciones.php');
	// include('inc/cabeceras.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Mensajería</title>

	<link rel="icon" href="../uploads/<?php echo $fotoPlantel; ?>">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">

	<link href="../css/lightbox.css" rel="stylesheet">

	<style>
		.has-search .form-control {
		    padding-left: 2.375rem;
		}

		.has-search .form-control-feedback {
		    position: absolute;
		    z-index: 2;
		    display: block;
		    width: 2.375rem;
		    height: 2.375rem;
		    line-height: 2.375rem;
		    text-align: center;
		    pointer-events: none;
		    color: #aaa;
		}


	</style>	
</head>

<body>
	
	<div class="container-fluid" id="main-container">

		

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
				        <div class="col-md-12 text-center">
				                
				                                            
			                    
			                    <input type="file" id="arc_men" name="arc_men" class="file_upload" placeholder="Sube Archivo"  required="" />

			                    <hr>

		                        <input type="hidden" id="id_sal_archivo" name="id_sal_archivo">

		                    
			                    <div class="progress md-progress" style="height: 20px">
			                        <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo">
			                            
			                          
			                        </div>
			                    </div>
				              

				        </div>
				    </div>
			      	
			      </div>
			      <div class="modal-footer">
			        <button class="btn btn-info white-text btn-rounded waves-effect btn-sm btn-block" type="submit" title="Enviar archivo" id="btn_enviar_archivo">
	                    Enviar archivo
	                </button> 
			      </div>
			  </form>
		    
		    </div>
		  
		  </div>
		
		</div>
		<!-- FIN MODAL ARCHIVO -->


		<!-- MODAL BUSCAR CONTACTO -->

		<div class="modal fade" id="modal_buscar_contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		  aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Buscar contacto</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>

			      <div class="modal-body">

			                    
                      <div class="form-group has-search">
					    <span class="fa fa-search form-control-feedback"></span>
					    <input type="text" class="form-control" placeholder="Buscar contacto..." id="palabra">
					  </div>



					  	<div class="custom-control custom-radio custom-control-inline">
						  <input type="radio" class="custom-control-input buscar_contacto_radio" id="defaultInline1" name="inlineDefaultRadiosExample" value="admin">
						  <label class="custom-control-label" for="defaultInline1">Administrativos</label>
						</div>

						<!-- Default inline 2-->
						<div class="custom-control custom-radio custom-control-inline">
						  <input type="radio" class="custom-control-input buscar_contacto_radio" id="defaultInline2" name="inlineDefaultRadiosExample" value="profesor">
						  <label class="custom-control-label" for="defaultInline2">Profesores</label>
						</div>


						<?php  
							if ( $tipo != 'Alumno'  ) {
						?>
								<!-- Default inline 3-->
								<div class="custom-control custom-radio custom-control-inline">
								  <input type="radio" class="custom-control-input buscar_contacto_radio" id="defaultInline3" name="inlineDefaultRadiosExample" value="alumno" checked>
								  <label class="custom-control-label" for="defaultInline3">Alumnos</label>
								</div>

						<?php
							}
						?>

						

                    <hr>


                    <div id="contenedor_buscar_contacto">
                    	
                    </div>

				    
			      </div>
			      <div class="modal-footer">
			        <button class="btn btn-info white-text btn-rounded waves-effect btn-sm btn-block" type="submit" title="Agregar contacto" id="btn_enviar_buscar_contacto" disabled="">
	                    Enviar mensaje
	                </button> 
			      </div>
		    
		    </div>
		  
		  </div>
		
		</div>
		<!-- FIN MODAL BUSCAR CONTACTO -->


		<!-- MODAL CONSULTAR USUARIOS -->

		<div class="modal fade" id="modal_consulta_usuarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		  aria-hidden="true">
		  <div class="modal-dialog" role="document">
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

			      <div class="modal-footer">
			        
			        <a class="btn" title="Salir..." data-dismiss="modal">
                    	Cerrar
                	</a>
			      
			      </div>
		    
		    </div>
		  
		  </div>
		
		</div>
		<!-- FIN MODAL CONSULTAR USUARIOS -->
		


		<div class="row h-100">
			<div class="col-12 col-sm-5 col-md-4 d-flex flex-column" id="chat-list-area" style="position:relative;">

				<!-- Navbar -->
				<div class="row d-flex flex-row align-items-center p-2" id="navbar">

					<a href="index.php" title="Volver al inicio..."><i class="fas fa-home fa-2x mx-3 text-white d-md-block"></i></a>	

					<img alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px; width: 50px; cursor:pointer;" src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>">
					<div class="text-white font-weight-bold" id="username"><?php echo $nombreUsuario; ?></div>
					
					<div class="nav-item dropdown ml-auto">
						
						<a href="#"><i class="fas fa-search mx-3 text-white d-md-block" id="btn_buscar_contacto"></i></a>
						

					</div>

					
				</div>


				<!-- CONTACTOS MENSAJERIA -->
				<div class="row p-2">

					<div class="col-md-12 text-center">
						<div class="form-group has-search">
							<span class="fa fa-search form-control-feedback"></span>
							<input type="text" class="form-control" placeholder="Buscar sala..." id="input_buscador_sala" style="border-radius: 20px;">
						</div>
					</div>

					
				</div>

				<div class="row" id="contenedor_contactos_mensajeria" style="overflow:auto;">
					
					

				</div>

				<!-- FIN CONTACTOS MENSAJERIA -->

				<!-- Profile Settings -->
				<div class="d-flex flex-column w-100 h-100" id="profile-settings">
					<div class="row d-flex flex-row align-items-center p-2 m-0" style="background:#009688; min-height:65px;">
						<i class="fas fa-arrow-left p-2 mx-3 my-1 text-white" style="font-size: 1.5rem; cursor: pointer;" onclick="hideProfileSettings()"></i>
						<div class="text-white font-weight-bold">Profile</div>
					</div>
					<div class="d-flex flex-column" style="overflow:auto;">
						<img alt="Profile Photo" class="img-fluid rounded-circle my-5 justify-self-center mx-auto" id="profile-pic">
						<input type="file" id="profile-pic-input" class="d-none">
						<div class="bg-white px-3 py-2">
							<div class="text-muted mb-2"><label for="input-name">Your Name</label></div>
							<input type="text" name="name" id="input-name" class="w-100 border-0 py-2 profile-input">
						</div>
						<div class="text-muted p-3 small">
							This is not your username or pin. This name will be visible to your WhatsApp contacts.
						</div>
						<div class="bg-white px-3 py-2">
							<div class="text-muted mb-2"><label for="input-about">About</label></div>
							<input type="text" name="name" id="input-about" value="" class="w-100 border-0 py-2 profile-input">
						</div>
					</div>
					
				</div>
			</div>

			<!-- Message Area -->
			<div class="d-none d-sm-flex flex-column col-12 col-sm-7 col-md-8 p-0 h-100" id="message-area" style="background:  #e0e0e0;">
				<div class="w-100 h-100 overlay" id="fondo_mensajeria"></div>

				<!-- Navbar -->
				<div class="row d-flex flex-row align-items-center p-2 m-0 w-100" id="navbar">
					<div class="d-block d-sm-none">
						<i class="fas fa-arrow-left p-2 mr-2 text-white" style="font-size: 1.5rem; cursor: pointer;" onclick="showChatList()"></i>
					</div>
					<a href="#"><img id="foto_sala" class="img-fluid rounded-circle mr-2" style="height:50px;" id="pic"></a>
					<div class="d-flex flex-column">
						<div class="text-white font-weight-bold" id="name"></div>
						<div class="text-white small" id="details"></div>
					</div>
					<div class="d-flex flex-row align-items-center ml-auto">
						
						
						<?php  
							if ( $tipo != 'Alumno' ) {
						?>
								
						<?php
							}
						?>


						<a href="#" id="btn_archivo_mensajeria" title="Envía un archivo"><i class="fas fa-paperclip mx-3 text-white d-md-block"></i></a>


						<a href="#" id="btn_consulta_usuarios" title="Consulta los miembros de la sala"><i class="fas fa-users mx-3 text-white d-md-block"></i></a>


					</div>
				</div>

				<!-- Messages -->
				<div class="d-flex flex-column" style="flex: 1!important; overflow: auto;" id="contenedor_mensajes_sala"></div>


				<div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
					
					<!-- <a href="#"><i class="far fa-smile text-muted px-3" style="font-size:1.5rem;"></i></a> -->
					<input type="text" id="mensaje" placeholder="Escribe un mensaje..." class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm">


					<i class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;" id="btn_enviar" id_sal=""></i>

				</div>

			</div>
		</div>
	</div>



	<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
	    crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
	    crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
		crossorigin="anonymous"></script>
	<script src="datastore.js"></script>
	<script src="date-utils.js"></script>
	<script src="script.js"></script>
 -->

 	<?php 
        require_once(  __DIR__."/../includes/links_js.php");

    ?>


	<script>
		obtener_mensaje_socket();
		function obtener_mensaje_socket(){

			socket.onmessage = function (event) {
		//console.log( event.data );

				var datos = JSON.parse(event.data);
				console.log(datos);

				if ( datos.modulo ) {


					if ( ( datos.modulo == 'Mensaje' ) ) {

						if ( datos.id ) {

							// console.log('1');

							if ( ( datos.id == <?php echo $id; ?> ) && ( datos.tipo == '<?php echo $tipo; ?>' ) ) {
							
								// console.log('2');
								// obtener_contactos_mensajeria();
									
							}
							


						} else {

							if ( obtener_sala_activa() == datos.id_sal ) {
								// VALIDAR SI LA SALA ES ACTIVA
								obtener_datos_sala( datos.id_sal );
								obtener_mensajes_sala( datos.id_sal );
							
							} else {
							// 	// SI NO, GENERAR NOTIFICACION

								
								obtener_datos_sala( datos.id_sal );

							}

						
						}
						
						

					}

				}

			}
		
		}

		
	</script>


	<script>
		$('#btn_enviar').on('click',  function(event) {
			event.preventDefault();
			/* Act on the event */
			agregar_mensaje();
		});



		// $('#input').on('keyup', function(event) {
		// 	event.preventDefault();
		// 	/* Act on the event */

		// 	if ( event.which == '13' ) {
	 //        	// console.log('if');
	 //        	agregar_mensaje();
	 //          // return false;
	 //        } else {
	 //        	console.log('else');
	 //        }

		// });


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

							socket.send( JSON.stringify( datos ) );
							
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


	<!-- ENVIO ARCHIVO -->
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
	<!-- fin envio archivo -->


	<!-- BUSCAR CONTACTO -->
	<script>
		$('#btn_buscar_contacto').on('click', function(event) {
			event.preventDefault();
			/* Act on the event */
			$('#modal_buscar_contacto').modal('show');

		});


		$('.buscar_contacto_radio').on('change', function(event) {
			event.preventDefault();
			/* Act on the event */

			obtener_busqueda_contacto();
		});


		$("#palabra").on('keyup', function(event) {
			event.preventDefault();
			/* Act on the event */

			obtener_busqueda_contacto();
			
		});


		function obtener_busqueda_contacto(){
			var palabra = $('#palabra').val();

			var tipo_usuario = $('.buscar_contacto_radio:checked').val();

			if ( palabra.length > 2 ) {

				$.ajax({
					url: 'server/obtener_buscar_contacto.php',
					type: 'POST',
					data: { palabra, tipo_usuario },
					success: function( respuesta ){

						$("#contenedor_buscar_contacto").html( respuesta );
					
					}
				});
			}
		}
	</script>
	<!-- FIN BUSCAR CONTACTO -->

	

<script>
	function obtener_socket_salas( id_sal ){
		

		$.ajax({
			url: 'server/obtener_envio_socket.php',
			type: 'POST',
			dataType: 'json',
			data: { id_sal },
			success: function( datos ){

				console.log(datos.tipo.length);

				console.log( datos.tipo[0] );

				for( var i = 0; i < datos.tipo.length; i++ ){

					var datos2 = {
			    
					    modulo : 'Mensaje',
					    id: datos.id[i],
					    tipo : datos.tipo[i]

					};


					// console.log('json: '+datos.id[i]);
					socket.send( JSON.stringify( datos2 ) );

				}


			}
		});
		
		// var datos = {
		    
		//     modulo : 'Mensaje',
		//     id_sal : id_sal

		// };

		// var datos = {
		    
		//     modulo : 'contacto_nuevo',
		//     id: '$tipo_usr_destino',
		//     tipo : id_usr_que_recibe0

		// };

		// socket.send( JSON.stringify( datos ) );
		


	}
</script>



<script>
	$('#btn_consulta_usuarios').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		console.log('consul');
		var id_sal = $(this).attr('id_sal');

		$('#modal_consulta_usuarios').modal('show');

		$.ajax({
		
			url: 'server/obtener_consulta_usuarios.php',
			type: 'POST',
			data: { id_sal },
			success: function( respuesta ){

				$('#contenedor_consulta_usuarios').html( respuesta );
			
			}
		
		});
		
	});
</script>


<!-- PAGINACION DE CONTACTOS POR SCROLL -->
<script>

  var limite = 10;
  var inicio = 0;
  var action = 'inactive';

  // obtener_contactos_mensajeria(limite, inicio);

  function obtener_contactos_mensajeria(limite, inicio){

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

</body>

</html>