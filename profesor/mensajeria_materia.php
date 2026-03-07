<?php  

	include( 'inc/header.php' );
	$id_sub_hor = $_GET['id_sub_hor'];

	$sqlMateria = "
		SELECT *
		FROM sub_hor
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_sub_hor = '$id_sub_hor' AND id_pro1 = '$id'
	";

	$resultadoValidacion = mysqli_query( $db, $sqlMateria );

	$validacion = mysqli_num_rows( $resultadoValidacion );

	if ( $validacion == 0 ) {
	
		header('location: not_found_404_page.php');
	
	}

	$resultadoMateria = mysqli_query( $db, $sqlMateria );

	$filaMateria = mysqli_fetch_assoc( $resultadoMateria );

	$sqlSala = "
		SELECT * 
		FROM sala
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = sala.id_sub_hor6
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
    	WHERE id_sub_hor = '$id_sub_hor'
	";

	// echo $sqlSala;

	$resultadoValidacionSala = mysqli_query($db, $sqlSala);

	$totalValidacionSala = mysqli_num_rows($resultadoValidacionSala);

	if ($totalValidacionSala == 0) {
		// NO EXISTE LA SALA
		//SE CREA LA SALA

		$sqlSubhor = "
			SELECT *
			FROM sub_hor
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			WHERE id_sub_hor = '$id_sub_hor'
		";


		$resultadoSubhor = mysqli_query($db, $sqlSubhor);

		if ($resultadoSubhor) {
			
			$filaSubhor = mysqli_fetch_assoc($resultadoSubhor);

			$nom_sal = "Sala de ".$filaSubhor['nom_mat'];
		
			$sqlInsercionSala = "
				INSERT INTO sala( nom_sal, id_sub_hor6, id_pla6 ) VALUES('$nom_sal', $id_sub_hor, '$plantel')
			";

			$resultadoInsercionSala = mysqli_query($db, $sqlInsercionSala);

			if ($resultadoInsercionSala) {
			// VALIDACION DE INSERCION
				$sqlMaximaSala = "
					SELECT MAX(id_sal) AS maxima 
					FROM sala
				";

				$resultadoMaximaSala = mysqli_query($db, $sqlMaximaSala);

				if ($resultadoMaximaSala) {
					// VALIDACION DE EXTRACCION DEL MAXIMO SALA
					$filaMaximaSala = mysqli_fetch_assoc($resultadoMaximaSala);

					$id_sal = $filaMaximaSala['maxima'];

					$sqlUltimaSala = "
						SELECT *
						FROM sala
						WHERE id_sal = '$id_sal'
					";

					$resultadoUltimaSala = mysqli_query($db, $sqlUltimaSala);

					if ($resultadoUltimaSala) {
						
						$filaUltimaSala = mysqli_fetch_assoc($resultadoUltimaSala);

						$nom_sal = $filaUltimaSala['nom_sal'];
					}


				}else{
					echo $sqlMaximaSala;
				}

				
			}else{
				echo $sqlInsercionSala;
			}



		}else{
			echo $sqlSubhor;
		}



	}else{
		
		$resultadoSalaMateria = mysqli_query($db, $sqlSala);

		$filaSalaMateria = mysqli_fetch_assoc($resultadoSalaMateria);

		//DATOS SALA
		$nom_sal = $filaSalaMateria['nom_sal'];
		$id_sal = $filaSalaMateria['id_sal'];


		//echo $sqlCompaneros;
	}

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

	.scrollbar-light-blue::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
	background-color: #F5F5F5;
	border-radius: 10px; }

	.scrollbar-light-blue::-webkit-scrollbar {
	width: 12px;
	background-color: #F5F5F5; }

	.scrollbar-light-blue::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
	background-color: #82B1FF; }

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

</style>


<!-- NAVEGACION INTERNA -->
<?php  
	echo obtenerNavegacionGrupo( $id_sub_hor, $id );
?>
<!-- FIN NAVEGACION INTERNA -->

<!-- TITULO -->
<div class="row ">
	<div class="col-md-6 text-left">
		<span class="tituloPagina animated fadeInUp badge blue-grey darken-4 hoverable" title="Video-clase">
			<i class="fas fa-bookmark"></i> 
			Mensajería de <?php echo $filaMateria['nom_mat']; ?>
		</span>
		
		<br>
		
		<div class=" badge badge-warning animated fadeInUp text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Mensajería</a>
		</div>
	</div>

	<div class="col-md-6 text-right">
		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Grupo">
			<i class="fas fa-circle"></i>
			<?php echo $filaMateria['nom_gru']; ?>
		</span>
	</div>
	
</div>
<!-- FIN TITULO -->



<style>

	.claseHijoClaseMateria {
	  position: absolute;
	  right: 0px;
	  top: 0px;
	  z-index: 1;
	}

	.clasePadreClaseMAteria {
	  position: relative;
	}

	.claseHijoIzquierda {
		position: absolute;
		left: 15px;
		bottom: -40px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.claseHijoDerecha {
		position: absolute;
		right: 15px;
		bottom: -40px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.clasePadre {
		position: relative;
	}

</style>


<!-- MENSAJERIA -->

<div class="row">
	<div class="col-md-12">
		<div class="card chat-room" style="background-image: url('../img/white.png'); background-repeat: no-repeat; background-size: cover; background-position: center center;">
		  	<div class="card-body " >

		    	<!-- Grid row -->
		    	<div class="row px-lg-2 px-2">
					
					<div class="col-md-4">
		    			<h4>
		    				<span class="badge badge-info">
		    					Participantes
		    				</span>
		    			</h4>

		    			<div class="white z-depth-1 px-2 pt-3 pb-0 members-panel-1 scrollbar-light-blue">
		          		
		          			<ul class="list-unstyled friend-list">
		          				<?php  
		          					// COMPANEROS DE CLASE
									$sqlCompaneros = "
										SELECT nom_pro AS nombre, app_pro AS apellido1, fot_emp AS foto, tip_pro AS tipo, id_pro AS id
										FROM alu_hor
										INNER JOIN sub_hor ON  sub_hor.id_sub_hor = alu_hor.id_sub_hor5
										INNER JOIN  profesor ON profesor.id_pro = sub_hor.id_pro1
										INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
										WHERE id_sub_hor5 = '$id_sub_hor' AND est_alu_hor = 'Activo'
										UNION
										SELECT nom_alu AS nombre, app_alu AS apellido1, fot_alu AS foto, tip_alu AS tipo, id_alu AS id
										FROM alu_hor
										INNER JOIN sub_hor ON  sub_hor.id_sub_hor = alu_hor.id_sub_hor5
										INNER JOIN  alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
										INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
										WHERE id_sub_hor5 = '$id_sub_hor' AND est_alu_hor = 'Activo'

									";

									//echo  $sqlCompaneros;
									$resultadoCompaneros = mysqli_query($db, $sqlCompaneros);

									while($filaCompaneros = mysqli_fetch_assoc($resultadoCompaneros)){
										
								?>
										<li class="p-2 elementos white lighten-3">
				                          <a class="d-flex  text-left" href="#">
				                          	<?php 
				                          		if ($filaCompaneros['foto'] == NULL) {
				                          	?>
													<img src="../uploads/huevito.jpg" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">

				                          	<?php
				                          		}else{
				                          	?>
													<img src="../uploads/<?php echo $filaCompaneros['foto']; ?>" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
				                          	<?php
				                          		}
				                          	?>
				                            
				                            <div class="text-small usuarios" id="<?php echo $filaCompaneros['id']; ?>" tipoUsuario="<?php echo $filaCompaneros['tipo']; ?>">
				                              <strong>
				                              	<?php  
				                              		echo $filaCompaneros['nombre']." ".$filaCompaneros['apellido1'];
				                              	?>
				                              </strong>
				                              <br>

				                              <?php  
				                              	if ($filaCompaneros['tipo'] == 'Profesor') {
				                              ?>
													<span class="badge badge-primary">
														<?php echo $filaCompaneros['tipo']; ?>
						                            </span>
				                              <?php
				                              	}else{
				                              ?>
													<span class="badge badge-info">
														<?php echo $filaCompaneros['tipo']; ?>
						                            </span>

				                              <?php
				                              	}

				                              ?>
				                              

				                            </div>
				                            <div class="chat-footer">
				                              <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
				                              <!-- <span class="badge badge-danger float-right">1</span> -->
				                            </div>

				             
				                          </a>
				                        </li>
								<?php
									}

		          				?>
		    				
		    				</ul>

		    			</div>
		    		</div>

		    		<div class="col-md-8 col-xl-8 pl-md-3 px-lg-auto px-0">
		    			<div class="chat-message">

					      
					          <ul class="list-unstyled chat-1 scrollbar-light-blue" id="listadoMensajes" style="overflow-x: hidden;">
					            
					              


					          </ul>

					          <form id="formChat" role="form">
					            <div class="white">
					              <div class="form-group basic-textarea" style="position: relative;">

	              					<i class="fas fa-paperclip grey-text waves-effect" style="position: absolute; z-index: 99; top: 30px; right: 10px;" title="Compartir archivo" id="btn_archivo_mensajeria" soy="" sala=""></i>

					                <textarea class="form-control pl-2 my-0" rows="3" placeholder="Escribe un mensaje..." id="msj" soy="" sala="" required=""></textarea>
					               

					              </div>



					            </div>

					            
					            <!-- <button type="button" class="btn btn-outline-pink btn-rounded btn-sm waves-effect waves-dark float-right" id="btn_send">Enviar</button> -->
					            <button class="btn btn-primary float-right btn-sm" id="btn_send">
					            	Enviar  
					            	<i class="fas fa-paper-plane"></i>
					            </button>
					          </form>

					        </div>
		    		</div>   

				</div>
			</div>
		</div>
	</div>
</div>

<!-- FIN MENSAJERIA -->



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
		        <button class="btn btn-info white-text btn-rounded waves-effect btn-sm" type="submit" title="Enviar archivo" id="btn_enviar">
                    Enviar archivo
                </button> 
		      </div>
		  </form>
	    
	    </div>
	  
	  </div>
	
	</div>
	<!-- FIN MODAL ARCHIVO -->





<?php 

	include( 'inc/footer.php' );

?>





<!-- INICIO MODAL -->





<div class="modal fade" id="enviarMensaje">
	<div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
	  <!--Content-->
	  <div class="modal-content">

	    <!--Header-->
	    <div class="modal-header removerSombra">
	      <img id="fotoContacto" class="rounded-circle img-responsive">
	    </div>
	    <!--Body-->
	    <div class="modal-body text-center mb-1">
				<h5 class="mt-1 mb-2" id="nombreContacto">
				</h5>

				<span class="badge badge-info" id="tipoContacto">
				</span>
				
				<div class="md-form" id="divMsj">
		          <i class="fas fa-pencil prefix grey-text"></i>
		          <textarea type="text" id="msjContacto" class="md-textarea form-control" rows="4" destino="" tipo="" autofocus=""></textarea>
		          <label data-error="wrong" data-success="right" for="form8">Escribe un mensaje...</label>
		        </div>

		   		<div id="contenedor_liga_mensajes">
		   			
		   		</div>
	        	
	   
		        <button class="btn btn-success waves-effect waves-light" type="submit" id="btn_send2">
					Enviar  
				</button>
			
	    </div>

	  </div>
	  <!--/.Content-->
	</div>
</div>

<!--FIN MODAL  -->

<script>
	
	$(document).ready(function() { 

		$('.removerSombra').removeClass("grey darken-1");

		var id_sal = <?php echo $id_sal; ?>;
		eliminacion_notificacion( id_sal );
		cargarMensajes(id_sal);

		// DESBLOQUEAR CUANDO ESTE LISTO EL wss
		// socket.onmessage = function (event) {
		// //console.log( event.data );

		// 	var datos = JSON.parse(event.data);
		// 	console.log(datos);

		// 	if ( datos.tipo ) {


		// 		if ( ( datos.tipo == 'Mensajeria' ) ) {

					
		// 			setTimeout(function(){

		// 				if ( id_sal == datos.sala ) {


		// 					cargarMensajes(id_sal);

						
		// 				}
						
					
		// 			}, 500 );
					

		// 		}

		// 	}

		// }
		

		$('#btn_send').on('click', function(event) {
        	event.preventDefault();
        	insertarMensaje();
    	});
        

        $("#msj").emojioneArea({
    	
	    	pickerPosition: "top",

	    	events: {
		      keyup: function(editor, event) {
		      	// catches everything but enter
		        if (event.which == 13) {
		        	// console.log('if');
		        	insertarMensaje();
		          // return false;
		        } else {
		        	console.log('else');
		        }

		      }
		    }
	    
	    });

	    

	    $('.file_upload').file_upload();


	    $('#btn_archivo_mensajeria').on('click', function(event) {
	    	event.preventDefault();
	    	/* Act on the event */

	    	$("#barra_estado_archivo").attr({style: 'width: 0%; height: 20px;'}).text("0").removeClass('').addClass('progress-bar text-center white-text');

	    	$("#btn_enviar").removeClass('light-green accent-4').addClass('btn-info').html('Enviar archivo');

	    	var soy = $("#btn_archivo_mensajeria").attr('soy');
	    	var sala = $("#btn_archivo_mensajeria").attr('sala');

	    	$("#soy2").val( soy );
	    	$('#variable2').val( sala );

	    	$('#modal_archivo').modal('show');

	    });



	    $('#formulario_archivo').on('submit', function(event) {
	        event.preventDefault();

	        if ($("#arc_men")[0].files[0]) {

	            var fileName = $("#arc_men")[0].files[0].name;
	            var fileSize = $("#arc_men")[0].files[0].size;


	            var ext = fileName.split('.').pop();

	            
	            if(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf' || ext == 'xlsx'){
	                if (fileSize < 10000000) {
	                    $("#btn_enviar").removeClass('btn-info').addClass('light-green accent-4').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');
	                    let barra_estado_archivo = $("#barra_estado_archivo");

	                    //Eliminacion de "Listo"
	                    barra_estado_archivo.text("");

	                    //Remueve clase de estatus listo
	                    barra_estado_archivo.removeClass();

	                    //Agrega la clase inicial del progress bar
	                    barra_estado_archivo.addClass('progress-bar text-center white-text');


	                    var formulario_archivo = new FormData( $('#formulario_archivo')[0] );
	                    formulario_archivo.append( 'id_sal', id_sal );

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

	                            toastr.success('¡Subido Correctamente!');
	                          });

	                          return peticion;
	                          },
	                        url: 'server/agregar_mensaje_materia.php',
	                        type: 'POST',
	                        data: formulario_archivo,
	                        processData: false,
	                        contentType: false,
	                        cache: false,
	                        success: function(respuesta){
	                            console.log(respuesta);
	                          	
	                          	$("#btn_enviar").html('<i class="fas fa-check white-text"></i> <span>Subida Exitosa</span>');
	                            swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
	                            then((value) => {

	                            	$('#modal_archivo').modal('hide');
	                          		
	                          		// // DESBLOQUEAR CUANDO ESTE LISTO EL wss
	        //                         var datos = {
									//     tipo: 'Mensajeria',
									//     sala: id_sal

									// };
									
									// DESBLOQUEAR CUANDO ESTE LISTO EL wss
									// socket.send( JSON.stringify( datos ) );

									for( var i = 0; i < $('.usuarios').length; i++ ){
										
										var datos = {
										    tipo: 'Mensajeria',
										    id_usuario: $('.usuarios').eq(i).attr('id')

										};

										
										// DESBLOQUEAR CUANDO ESTE LISTO EL wss
										// socket.send( JSON.stringify( datos ) );
									}

									// 	console.log( 'validador: '+validador+' - datos.sala: '+datos.sala );
									// if (validador == datos.sala) {

									// console.log('validador');
									cargarMensajes(id_sal);


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
	      	

    });

    function insertarMensaje(){
      
        

        var msj = $("#msj").data("emojioneArea").getText();
 

        //RECORDAR VALIDAR SI EL MENSAJE ESTA VACIO
        if (msj != '') {

			var id_sal = <?php echo $id_sal; ?>;

			$.ajax({
				type: 'POST',
				url: 'server/agregar_mensaje_materia.php',
				data: {msj, id_sal},

				success: function(response){

				  	console.log(response);
				  	var el = $("#msj").emojioneArea();//REEMPLAZO DEL CLASICO .val("")
					el[0].emojioneArea.setText(''); // clear input 
				  	

					// DESBLOQUEAR CUANDO ESTE LISTO EL wss
				 //  	var datos = {
					//     tipo: 'Mensajeria',
					//     sala: id_sal

					// };
					

					// socket.send( JSON.stringify( datos ) );

					for( var i = 0; i < $('.usuarios').length; i++ ){
						
						var datos = {
						    tipo: 'Mensajeria',
						    id_usuario: $('.usuarios').eq(i).attr('id')

						};

						
						// DESBLOQUEAR CUANDO ESTE LISTO EL wss
						// socket.send( JSON.stringify( datos ) );
					}

					cargarMensajes(id_sal);
				  
				}

			});
        }

       
    }





    function cargarMensajes(id_sal){
       	
       	$.ajax({
			type: "POST",
			url: "server/listar_mensajes_materia.php",
			data: {id_sal},

			success: function(response){
			  //console.log(response);
			  $('#listadoMensajes').html(response);
			  $('#listadoMensajes p:last-child');
			  var mensajes = $('#aux').attr("value");
			  console.log(mensajes);
			  $("#listadoMensajes").scrollTop($("#listadoMensajes").prop("scrollHeight"));

			  // var altura = $("#listadoMensajes").prop("scrollHeight");
			  // $("#listadoMensajes").scrollTop(altura);


			}

		});
    }
</script>


<script>
  	$('.usuarios').on('click', function(event){
  		event.preventDefault();

  		$("#contenedor_liga_mensajes").html('');

  		var usuario = $(this).attr("id");
  		var tipoUsuario = $(this).attr("tipoUsuario");

  		$.ajax({
  			url: 'server/obtener_usuario.php',
  			type: 'POST',
  			data: {usuario, tipoUsuario},
  			dataType: 'json',

  			success: function(response){
              var datosUsuario = response;
              //console.log(response);
              $('#nombreContacto').html('Para: '+datosUsuario.nombre);
              $('#fotoContacto').attr({src: "../uploads/"+datosUsuario.foto});
              $('#tipoContacto').text(datosUsuario.tipoDestino);

              $('#enviarMensaje').modal('show');
              $(".modal-backdrop").removeClass('modal-backdrop');
              $('#msjContacto').attr({destino: datosUsuario.destino});
              $('#msjContacto').attr({tipo: datosUsuario.tipoDestino});

              $('#btn_send2').on('click', function(event) {
              	event.preventDefault();

              	var mensaje = $('#msjContacto').val();

              	if (mensaje == "") {

              	}else{
              		
              		var idDestino = $('#msjContacto').attr("destino");
              		var tipoDestino = $('#msjContacto').attr("tipo");

              		$.ajax({
              			url: 'server/contacto.php',
              			type: 'POST',
              			data: {idDestino, tipoDestino, mensaje},
              			success: function(response){
              				console.log(response);
              				$('#msjContacto').val("");
              				toastr.info('¡Enviado correctamente!');

              				$("#contenedor_liga_mensajes").html('Ve a tus<a href="mensajes.php" class="animated fadeInDown" target="_blank">'+													' mensajes</a> para continuar...');


              			}
              		});
              	}
              });
            }
  		});

  		
  		
  		
  	});
</script>


<script>

	function eliminacion_notificacion( id_sal ){
		$.ajax({
	        url: 'server/eliminacion_notificacion_mensaje.php',
	        type: 'POST',
	        data: { id_sal },
	        success: function( respuesta ){

	        	obtener_panel_notificaciones_mensajeria();
	          	console.log( respuesta );

	        }
	    }); 
	}
	 
</script>