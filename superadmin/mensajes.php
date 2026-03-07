<?php  

	include('inc/header.php');
	// include('inc/cabeceras.php');

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


<style>
	
/* Styles the thumbnail */

a.lightbox img {
height: 150px;
border: 3px solid white;
box-shadow: 0px 0px 8px rgba(0,0,0,.3);
margin: 94px 20px 20px 20px;
}

/* Styles the lightbox, removes it from sight and adds the fade-in transition */

.lightbox-target {
position: fixed;
top: -100%;
width: 100%;
background: rgba(0,0,0,.7);
width: 100%;
opacity: 0;
-webkit-transition: opacity .5s ease-in-out;
-moz-transition: opacity .5s ease-in-out;
-o-transition: opacity .5s ease-in-out;
transition: opacity .5s ease-in-out;
overflow: hidden;
}

/* Styles the lightbox image, centers it vertically and horizontally, adds the zoom-in transition and makes it responsive using a combination of margin and absolute positioning */

.lightbox-target img {
margin: auto;
position: absolute;
top: 0;
left:0;
right:0;
bottom: 0;
max-height: 0%;
max-width: 0%;
border: 3px solid white;
box-shadow: 0px 0px 8px rgba(0,0,0,.3);
box-sizing: border-box;
-webkit-transition: .5s ease-in-out;
-moz-transition: .5s ease-in-out;
-o-transition: .5s ease-in-out;
transition: .5s ease-in-out;
}

/* Styles the close link, adds the slide down transition */

a.lightbox-close {
display: block;
width:50px;
height:50px;
box-sizing: border-box;
background: white;
color: black;
text-decoration: none;
position: absolute;
top: -80px;
right: 0;
-webkit-transition: .5s ease-in-out;
-moz-transition: .5s ease-in-out;
-o-transition: .5s ease-in-out;
transition: .5s ease-in-out;
}

/* Provides part of the "X" to eliminate an image from the close link */

a.lightbox-close:before {
content: "";
display: block;
height: 30px;
width: 1px;
background: black;
position: absolute;
left: 26px;
top:10px;
-webkit-transform:rotate(45deg);
-moz-transform:rotate(45deg);
-o-transform:rotate(45deg);
transform:rotate(45deg);
}

/* Provides part of the "X" to eliminate an image from the close link */

a.lightbox-close:after {
content: "";
display: block;
height: 30px;
width: 1px;
background: black;
position: absolute;
left: 26px;
top:10px;
-webkit-transform:rotate(-45deg);
-moz-transform:rotate(-45deg);
-o-transform:rotate(-45deg);
transform:rotate(-45deg);
}

/* Uses the :target pseudo-class to perform the animations upon clicking the .lightbox-target anchor */

.lightbox-target:target {
opacity: 1;
top: 0;
bottom: 0;
}

.lightbox-target:target img {
max-height: 100%;
max-width: 100%;
}

.lightbox-target:target a.lightbox-close {
top: 0px;
}

</style>

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Menssenger"><i class="fas fa-bookmark"></i> Messenger</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Messenger</a>
		</div>
		
	</div>
	
</div>
<!-- FIN TITULO -->


      
<!-- Jumbotron Main Content-->

	
	<div class="card chat-room" style="background-image: url('../img/white.png'); background-repeat: no-repeat; background-size: cover; background-position: center center;">
	  <div class="card-body " >

	    <!-- Grid row -->
	    <div class="row px-lg-2 px-2">

	      <!-- Grid column -->
	      <div class="col-md-6 col-xl-4 px-0">
				<div class="col-md-4 text-center badge badge-info hoverable">
					<div class="row justify-content-center text-white">
						<h5 style="font-family: 'Libre Franklin', sans-serif;">Contactos</h5>		
					</div>
				</div>
	        <div class="white z-depth-1 px-2 pt-3 pb-0 members-panel-1" id="contenedor_contactos">
	          
	          
	        </div>

	      </div>
	      <!-- Grid column -->

	      <!-- Grid column -->
	      <div class="col-md-6 col-xl-8 pl-md-3 px-lg-auto px-0">
	    
	        <div class="chat-message">

	        	<!--Navbar -->
				<!-- <nav class="navbar navbar-expand-lg navbar-dark" style="background-image: url('../img/bannerWhite.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center center;">
				  	<ul class="navbar-nav mr-auto nav-flex-icons">
				      <li class="nav-item avatar">
				        <a class="nav-link p-0" href="#">
				          <img src="https://mdbootstrap.com/img/Photos/Avatars/avatar-5.jpg" class="rounded-circle z-depth-0"
				            alt="avatar image" height="35">

				            Juan Pedro
				        </a>
				      </li>
				    </ul>
				</nav> -->
				<!--/.Navbar -->
	      
	        	<ul class="list-unstyled chat-1 scrollbar-light-blue" id="listadoMensajes" style="overflow-x: hidden;">
	       	
	              


	          	</ul>

	          <form id="formChat" role="form">
	            <div class="white">
	              <div class="form-group basic-textarea" style="position: relative;">

	              	<i class="fas fa-paperclip grey-text waves-effect" style="position: absolute; z-index: 99; top: 30px; right: 10px;" title="Compartir archivo" id="btn_archivo_mensajeria" soy="" sala=""></i>

	              	<a style="position: absolute; z-index: 99; top: 50px; right: 7px;" title="Videoconferencia" id="btn_video_mensajeria" soy="" sala="">

	              		<i class="fas fa-video grey-text waves-effect" ></i>
	              	
	              	</a>


	              	<a style="position: absolute; z-index: 99; top: 70px; right: 7px;" title="Pizarra" id="btn_pizarra_mensajeria" soy="" sala="">

	              		<i class="fas fa-paint-brush grey-text waves-effect"></i>
	              	
	              	</a>
	              	

					<textarea class="form-control pl-2 my-0" rows="3" placeholder="Escribe un mensaje..." id_usuario="" id="msj" soy="" sala="" required=""></textarea>
	               

	              </div>

	            </div>

	            
	            <!-- <button type="button" class="btn btn-outline-pink btn-rounded btn-sm waves-effect waves-dark float-right" id="btn_send">Enviar</button> -->
	            <button class="btn btn-primary float-right btn-sm" id="btn_send">Enviar  <i class="fas fa-paper-plane"></i></button>
	          </form>

	        </div>

	      </div>
	      <!-- Grid column -->

	    </div>
	    <!-- Grid row -->

	  </div>
	</div>



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
		                        <input type="file" id="arc_con" name="arc_con" class="file_upload" placeholder="Sube Archivo"  required="" />

		                        <input type="hidden" id="soy2" name="soy2">
		                        <input type="hidden" id="variable2" name="variable2">

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

	include('inc/footer.php');

?>


<script>
	obtener_contactos();

	function obtener_contactos( id_sal = '' ){
		
		$.ajax({
            type: 'POST',
            url: 'server/obtener_contactos_mensajeria.php',
            success: function( r ){
            	// console.log( r );
            	$('#contenedor_contactos').html( r );

            	if ( id_sal ) {
            		$('#'+id_sal+'').parent().removeClass('white lighten-3').addClass('cyan lighten-5');
            		burbuja.play();
            	}
            	
            }
        });
	}

	
	
          
			
	// socket.onmessage = function (event) {
	// //console.log( event.data );

	// 	var datos = JSON.parse(event.data);
	// 	console.log(datos);

	// 	if ( datos.tipo ) {


	// 		if ( ( datos.tipo == 'Mensajeria' ) ) {

	// 			variable = $('.friend-list').find('.cyan').children().attr('soy');
	// 			validador = $('.friend-list').find('.cyan').children().attr('id');
				
	// 			setTimeout(function(){

	// 					console.log( 'validador: '+validador+' - datos.sala: '+datos.sala );
	// 				if (validador == datos.sala) {

	// 					console.log('validador');

	// 					cargarMensajes(variable, datos.sala );
	// 					obtener_contactos( datos.sala );
	// 				}
					
				
	// 			}, 500 );
				


	// 		}

	// 	}

	// }

    



	$('#btn_send').on('click', function(event) {
        event.preventDefault();
        burbuja.play();  
      	insertarMensaje();
    });

    function insertarMensaje( fuente = '' ){

    	if ( fuente != '' ) {
    		var msj = $("#msj").data("emojioneArea").getText();

    	} else {
    		var msj = $('#msj').val();
    	}
        

        //RECORDAR VALIDAR SI EL MENSAJE ESTA VACIO
        if ( msj != '' ) {
			var soy = $('#msj').attr("soy");
          	var id_sal = $('#msj').attr("sala");

          	var id_usuario = $('#msj').attr("id_usuario");

          	$.ajax({
	            type: 'POST',
	            url: 'server/enviar_mensaje.php',
	            data: {msj, soy, id_sal},

	            success: function(response){
	              	console.log(response);
	              	var el = $("#msj").emojioneArea();//REEMPLAZO DEL CLASICO .val("")
					el[0].emojioneArea.setText(''); // clear input 


					// var datos = {
					//     tipo: 'Mensajeria',
					//     sala: id_sal,
					//     id_usuario: id_usuario

					// };

					// socket.send( JSON.stringify( datos ) );


					variable = $('.friend-list').find('.cyan').children().attr('soy');
					validador = $('.friend-list').find('.cyan').children().attr('id');
					
					
					// 	console.log( 'validador: '+validador+' - datos.sala: '+datos.sala );
					// if (validador == datos.sala) {

					// console.log('validador');

					cargarMensajes(variable, validador );
					obtener_contactos( validador );
					// }
						
					
	              
	              
	            }

          	});
        }

      
    }



    function cargarMensajes(variable, id_sal){
    	var aux = 0;
     // var temporizador = setInterval(function(){

        $.ajax({
          type: "POST",
          url: "server/listar_mensajes.php",
          data: {id_sal},
          

          success: function(response){
              //console.log(response);
              $('#listadoMensajes').html(response);
              $('#listadoMensajes p:last-child');
              $('#msj').attr({"soy": variable});
              $('#msj').attr({"sala": id_sal});

              lightbox.option({
		      'resizeDuration': 200,
		      'wrapAround': true,
		      'albumLabel': "Imagen %1 de %2"
		    })


              $('#btn_archivo_mensajeria').attr({"sala": id_sal}).attr({"soy": variable});

              $('#btn_video_mensajeria').attr({"sala": id_sal}).attr({"soy": variable});

              $('#btn_pizarra_mensajeria').attr({"sala": id_sal}).attr({"soy": variable});


              var mensajes = $('#aux').attr("value");
              

              if (mensajes > aux) {
              	var altura = $("#listadoMensajes").prop("scrollHeight");
              	$("#listadoMensajes").scrollTop(altura);

              	aux = mensajes;	
              }


              console.log(id_sal);

        }

      });
      // }, 3000);

    }
</script>


<script type="text/javascript">
  $(document).ready(function() {
    



    $("#msj").emojioneArea({
    	
    	pickerPosition: "top",

    	events: {
	      keyup: function(editor, event) {
	      	// catches everything but enter
	        if (event.which == 13) {
	        	// console.log('if');
	        	insertarMensaje( true );
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

    $('#btn_video_mensajeria').on('click', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	var soy = $("#btn_video_mensajeria").attr('soy');
    	var sala = $("#btn_video_mensajeria").attr('sala');

    	$(this).removeAttr('href').attr( 'href', 'video_conferencia.php?id_sal='+sala ).removeAttr('target').attr('target', '_blank');

    	window.open(this.href, this.target, 'width=600, height=500'); return false;

    });




    $('#btn_pizarra_mensajeria').on('click', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	var soy = $("#btn_pizarra_mensajeria").attr('soy');
    	var sala = $("#btn_pizarra_mensajeria").attr('sala');

    	$(this).removeAttr('href').attr( 'href', 'herramienta_pizarra.php?id_sal='+sala ).removeAttr('target').attr('target', '_blank');

    	window.open(this.href, this.target, 'width=1000, height=800'); return false;

    });




    $('#formulario_archivo').on('submit', function(event) {
        event.preventDefault();

        if ($("#arc_con")[0].files[0]) {

            var fileName = $("#arc_con")[0].files[0].name;
            var fileSize = $("#arc_con")[0].files[0].size;


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
                        url: 'server/enviar_mensaje.php',
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
                                
                                variable = $('.friend-list').find('.cyan').children().attr('soy');
								validador = $('.friend-list').find('.cyan').children().attr('id');

        //                         var datos = {
								//     tipo: 'Mensajeria',
								//     sala: validador

								// };

								

								// socket.send( JSON.stringify( datos ) );

								// 	console.log( 'validador: '+validador+' - datos.sala: '+datos.sala );
								// if (validador == datos.sala) {

								// console.log('validador');

								cargarMensajes(variable, validador );
								obtener_contactos( validador );

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
</script>



