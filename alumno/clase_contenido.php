<?php  

	include('inc/header.php');
	$id_blo = $_GET['id_blo'];

	$id_sub_hor = $_GET['id_sub_hor'];
	$id_alu_ram = $_GET['id_alu_ram'];
	// echo $id_blo;

	$sqlProfesor = "
		SELECT *
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_sub_hor = '$id_sub_hor'
	";

	$resultadoProfesor = mysqli_query( $db, $sqlProfesor );

	$filaProfesor = mysqli_fetch_assoc( $resultadoProfesor );

	$id_pro = $filaProfesor['id_pro1'];
	$nom_pro = $filaProfesor['nom_pro'];
	$fot_emp = $filaProfesor['fot_emp'];
	$nom_gru = $filaProfesor['nom_gru'];

	$sqlBloque = "
		SELECT * 
		FROM bloque 
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_blo = '$id_blo'
	";

	$resultadoBloque = mysqli_query($db, $sqlBloque);
	$filaBloque = mysqli_fetch_assoc($resultadoBloque);

	$nom_blo = $filaBloque['nom_blo'];
	$des_blo = $filaBloque['des_blo'];
	$con_blo = $filaBloque['con_blo'];
	$img_blo = $filaBloque['img_blo'];

	$id_mat6 = $filaBloque['id_mat6'];
	$nom_mat = $filaBloque['nom_mat'];
	$nom_ram = $filaBloque['nom_ram'];
	$id_mat = $filaBloque['id_mat'];
	$id_ram = $filaBloque['id_ram'];


?>

<style>

	.claseHijoActividad {
	  position: absolute;
	  left: 70px;
	  top: 5px;
	  z-index: 100;
	  
	}

	.clasePadreActividad {
	  position: relative;
	}



	.claseHijoNumeracion {
		position: absolute;
		left: -1px;
		top: -20px;
		background-color: lightgray;
		border-radius: 50%;
		height: 25px;
		width: 25px;
		z-index: 99;
	}

	.claseTextoHijoNumeracion{

		font-size: 18px;
		color: white;
		text-align: center;

	}



	.clasePadre {
		position: relative;
	}


	.claseSticky{

		position: -webkit-sticky;
		position: sticky;
		top: 50px;

	}

	.ventanaRespuestaDraggableManejador{
		cursor: all-scroll;
	}


	/*.botonesRespuestaPadre {
		position: relative;
	}

	.botonesRespuestaHijo {
		position: absolute;
		right: -10px;
		bottom: 10px;
	}*/

	/*SMALL CHAT*/
	.chat-room.small-chat {
	  /* position: fixed; */
	  /* bottom: 0; */
	  position: fixed;
	  right: 0%;
	  bottom: 0%;
	  z-index: 100;
	  border-bottom-left-radius: 0;
	  border-bottom-right-radius: 0;
	  width: 20rem; }
	  .chat-room.small-chat.slim {
	  height: 3rem; }
	  .chat-room.small-chat.slim .icons .feature {
	    display: none; }
	  .chat-room.small-chat.slim .my-custom-scrollbar {
	    display: none; }
	  .chat-room.small-chat.slim .card-footer {
	    display: none; }
	  .chat-room.small-chat .profile-photo img.avatar {
	    height: 2rem;
	    width: 2rem; }
	  .chat-room.small-chat .profile-photo .state {
	    position: relative;
	    display: block;
	    background-color: #007E33;
	    height: .65rem;
	    width: .65rem;
	    z-index: 2;
	    margin-left: 1.35rem;
	    left: auto;
	    top: -.5rem;
	    border-radius: 50%;
	    border: .1rem solid #fff; }
	  .chat-room.small-chat .profile-photo.message-photo {
	    margin-top: 2.7rem; }
	  .chat-room.small-chat .heading {
	    height: 2.1rem; }
	    .chat-room.small-chat .heading .data {
	      line-height: 1.5; }
	      .chat-room.small-chat .heading .data .name {
	        font-size: .8rem; }
	      .chat-room.small-chat .heading .data .activity {
	        font-size: .75rem; }
	  .chat-room.small-chat .icons {
	    padding-top: .45rem; }
	  .chat-room.small-chat .my-custom-scrollbar {
	    position: relative;
	    height: 18rem;
	    overflow: auto; }
	    .chat-room.small-chat .my-custom-scrollbar > .card-body {
	      height: 18rem; }
	      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .media img {
	        width: 3rem; }
	      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .media .media-body p {
	        font-size: .7rem; }
	      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .message-text {
	        margin-left: .1rem; }
	  .chat-room.small-chat .card-footer .form-control {
	    border: none;
	    padding: .375rem 0 .43rem 0;
	    font-size: .9rem; }
	    .chat-room.small-chat .card-footer .form-control:focus {
	      box-shadow: none; }

	  .bcg-preview {
	    height: 535px;
	  }




</style>


<!-- TITULO -->
<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
	background-image: url('../fondos_clase/<?php echo $img_blo; ?>'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; top: -40px; position: relative; 

">

	
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect edicionTitulo" nom_blo="<?php echo $nom_blo; ?>" des_blo="<?php echo $des_blo; ?>" title="Título de clase">
			<i class="fas fa-bookmark"></i> 
			<?php echo $nom_blo; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect edicionTitulo" nom_blo="<?php echo $nom_blo; ?>" des_blo="<?php echo $des_blo; ?>" title="Descripción de clase">
			<i class="fas fa-circle"></i>
			<?php echo $des_blo; ?>
		</span>
		<br>
		<br>
		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>

			
			<a class="text-white" href="clases_materia.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_alu_ram=<?php echo $id_alu_ram; ?>" title="Vuelve a clases">Clases</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">
				<?php echo $nom_blo; ?>
			</a>
			
		</div>
		
	</div>

	<div class="col text-right">

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materias de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Clases de <?php echo $nom_mat; ?>">
			<i class="fas fa-certificate"></i>
			Materia: <?php echo $nom_mat; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Contenido de clase de <?php echo $nom_blo; ?>">
			<i class="fas fa-certificate"></i>
			Clase: <?php echo $nom_blo; ?>
		</span>
		
		
	</div>
	
</div>
<!-- FIN TITULO -->


<?php  
	/**
	<!-- Central Modal Medium Danger -->
 <div class="modal fade" id="modal_aviso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-frame modal-bottom modal-notify modal-danger" role="document">
     <!--Content-->
     <div class="modal-content">
       <!--Header-->
       <div class="modal-header">
         <p class="heading lead">Aviso del equipo de PLATAFORMA</p>

         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true" class="white-text">&times;</span>
         </button>
       </div>

       <!--Body-->
       <div class="modal-body">
         <div class="text-center">

           <i class="fas fa-exclamation-triangle fa-4x mb-3 animated rotateIn"></i>
           <p>
           	Atención, estimado alumno. Te comentamos que si presentas complicaciones para subir tareas accedas más tarde. Estamos trabajando en solucionar algunos errores que nos han reportado.
           	
           	<br>

           	Por tu comprensión, gracias.
           </p>
         </div>
       </div>

       <!--Footer-->
       <div class="modal-footer justify-content-center">
         <a type="button" class="btn btn-sm btn-rounded btn-secondary waves-effect" data-dismiss="modal">Entendido</a>
       </div>
     </div>
     <!--/.Content-->
   </div>
 </div>
 <!-- Central Modal Medium Danger-->
	**/
?>


<div class="row">
	<div class="col-md-4">

		<div class="card claseSticky z-depth-1"  style="border-radius: 20px;">
			

			<!-- <div class="card-header bg-white black-text"  style="border-radius: 20px;">
				

				<a href="video_clase.php?id_sub_hor=<?php echo $id_sub_hor; ?>&validador" class="btn-floating btn-sm  grey darken-2
					white-text  dropdown-toggle" target="_blank" onClick="window.open(this.href, this.target, 'width=600, height=500'); return false;">
					<i class="fas fa-video"></i>
	                
				</a>Video-clase				
			</div>
 -->

			<div class="card-header bg-white black-text"  style="border-radius: 20px;">

				<span>
			  		Recursos teóricos
			  	</span>

			</div>

			<div class="body bg-white scrollspy-example" data-spy="scroll" id="contenedor_recursos_teoricos" style="height: 400px; border-radius: 20px;">
				
				
			</div>


			<div class="card-footer">
				
				<?php  
					// echo contadorRecursosTeoricos( $id_blo )." recursos";
				?>
				
			</div>
		</div>
		
	</div>


	<div class="col-md-8 rounded mb-0">


		<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
		
			<div class="card-body" >
				<!-- CONTENEDOR CONTENIDO -->
				
				<?php echo $con_blo; ?>	


				<!-- FIN CONTENEDOR CONTENIDO -->
			</div>



		</div>


		<br>
		
		<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
			
			<div class="card-header bg-white"  style="border-radius: 20px;">
				
				Actividades

			</div>


		</div>

		<hr>

		<div id="contenedor_actividades">
			
		</div>
	
	</div>


</div>






<!-- MODALES RECURSOS -->



<!-- VISTA VIDEO -->
<div class="modal fade right" id="modalVideoPlayer" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-side modal-top-right" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloVideo"></h5>
        <button id="limpiarVideos" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contenidoModalVideo">
      	
      </div>
      <div class="modal-footer">
       	<span id="descripcionVideo"></span>
      </div>
    </div>
  </div>
</div>
<!-- FIN  VISTA VIDEO -->



<!-- FIN VIDEO  -->


<!-- WIKI -->

<!-- VISTA DE WIKI -->
<div class="modal fade text-left " id="modalWiki">
  <div class="modal-dialog modal-lg" role="document">
    
	<form >
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 white-text" id="tituloWikiVista"></h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
			

	         
			<div id="contenidoWikiVista">


			</div>
			
			<div class="modal-footer d-flex justify-content-center">
  	
		      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>
		        
		    </div>


	      </div>

	    </div>
	</form>

  </div>
</div>

<!-- FIN VISTA DE WIKI -->
<!-- FIN WIKI -->


<!-- ARCHIVO -->

<!-- Modal -->
<div class="modal fade right" id="modalArchivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-side modal-top-right" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloArchivo"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center" id="contenidoModalArchivo">
 
      </div>
      <div class="modal-footer">
        <span id="descripcionArchivo"></span>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<!-- FIN CONTENIDO MODAL AGREGAR ARCHIVO -->


<!-- FIN MODALES RECURSOS -->

<br>



<?php /** 
<!-- MENSAJES -->
<div class="container mt-5">

  <!-- Grid row -->
  <div class="row d-flex flex-row-reverse">

    <!-- Grid column -->
    <div class="col-md-6 mb-4 d-flex flex-row-reverse">

      <div class="card chat-room small-chat wide" id="myForm">
        <div class="card-header white d-flex justify-content-between p-2">
          <div class="heading d-flex justify-content-start">
            
            <div class="profile-photo">
              <img src="../uploads/<?php echo $fot_emp; ?>" alt="profesor" class="avatar rounded-circle mr-2 ml-0">
         
            </div>
            <div class="data">
              <p class="name mb-0">
              	<strong>
              		<?php echo $nom_pro; ?>
              	</strong>
              </p>
              <p class="activity text-muted mb-0">Profesor</p>
            </div>
          </div>
          <div class="icons grey-text">
            
            <a id="toggle" style="cursor: pointer;">
            	<!-- <i class="fas fa-times "></i> -->
            	<i class="fas fa-window-minimize mr-2"></i>
            </a>
          </div>
        </div>

        <div class="my-custom-scrollbar" id="message">
          <div class="card-body p-3">
            <div class="chat-message">
              
              <div class="media mb-3">
                <img class="d-flex rounded mr-2" src="../uploads/<?php echo $fot_emp; ?>" alt="profesor">
                <div class="media-body">
                  <p class="my-0">Soy tu profesor de <?php echo $nom_mat; ?>, bienvenido a <?php echo $nombrePlantel; ?></p>
                  <p class="mb-0 text-muted">Cualquier duda, mándame un mensaje y a la brevedad te contesto ;)</p>
                </div>
              </div>
				
              
				<div id="contenedor_mensajes_sala">
					
				</div>
            </div>
          </div>
        </div>


        <div class="card-footer text-muted white pt-1 pb-2 px-3">
          <input type="text" id="input_mensaje" class="form-control" placeholder="Escribe al profesor...">
         
        </div>
      </div>

    </div>
    <!-- Grid column -->

  </div>
  <!-- Grid row -->

</div>
<!-- FIN MENSAJES -->
*/?>

<!-- FIN CONTENIDO -->
<?php  

	include('inc/footer.php');

?>


<!-- PROLONGACION DE SESION ACTIVA -->
<script>
	const sesion_activa = () =>{
		setInterval(function(){

			console.log('sesion activa');
	    	$.ajax({
		    	url: 'server/obtener_sesion_activa.php',
		    	type: 'POST'
		    	
		    });
	    }, 5000 );	
	}


	sesion_activa();
    
  
</script>
<!-- FIN PROLONGACION DE SESION ACTIVA -->


<script>

	$("#modalArchivo").draggable();

</script>



<script>

	obtenerRecursosTeoricos();

	function obtenerRecursosTeoricos(){
		var id_blo = parseInt( '<?php echo $id_blo; ?>' );

		$.ajax({
			url: 'server/obtener_recursos_teoricos.php',
			type: 'POST',
			data: { id_blo },
			success: function( respuesta ){
				$( '#contenedor_recursos_teoricos' ).html( respuesta );

			}
		});
		
	}



	obtenerActividades();

	function obtenerActividades(){
		var id_blo = parseInt( '<?php echo $id_blo; ?>' );
		var id_sub_hor = parseInt( '<?php echo $_GET['id_sub_hor']; ?>' );
		var id_alu_ram = parseInt( '<?php echo $_GET['id_alu_ram']; ?>' );

		var tipo_actividad = getParameterByName('tipo_actividad'); // "lorem"
		var identificador_copia = getParameterByName('identificador_copia');
		var titulo_actividad = getParameterByName('titulo_actividad');

		if ( ( identificador_copia != undefined )  && ( tipo_actividad != undefined ) ) {

			$.ajax({
				url: 'server/obtener_actividades.php',
				type: 'POST',
				data: { id_blo, id_sub_hor, id_alu_ram, tipo_actividad, identificador_copia, titulo_actividad },
				success: function( respuesta ){
					console.log(respuesta);
					$( '#contenedor_actividades' ).html( respuesta );

				}
			});
		} else {

			$.ajax({
				url: 'server/obtener_actividades.php',
				type: 'POST',
				data: { id_blo, id_sub_hor, id_alu_ram },
				success: function( respuesta ){

					$( '#contenedor_actividades' ).html( respuesta );

				}
			});

		}
		
		
	}


	// alert(  );

	



</script>

<!-- MENSAJES -->
<script>
	obtener_scroll();

	function obtener_mensajes_sala( id_sal ){

	    obtener_estatus_sala( id_sal );

	    $.ajax({
	      url: 'server/obtener_mensajes_minichat.php',
	      type: 'POST',
	      data: { id_sal },
	      success: function( respuesta ){

	        // console.log( respuesta );
	        $('#contenedor_mensajes_sala').html( respuesta );

	        $('#btn_enviar').attr('id_sal', id_sal);
	        $('#btn_consulta_usuarios').attr('id_sal', id_sal);
	        
	        obtener_scroll();

	      }
	    });

	}



	function obtener_estatus_sala( id_sal ){

	    $.ajax({
	      url: 'server/estatus_mensajes_sala.php',
	      type: 'POST',
	      data: { id_sal },
	      success: function( respuesta ){

	        console.log( respuesta );

	      }
	    
	    });

	}


	function obtener_scroll(){
		setTimeout(function(){
			var altura = $("#message").prop("scrollHeight") + 1000;
			$("#message").scrollTop(altura);
		}, 200);
	}




</script>


<script>
	obtener_mensaje_socket();
	function obtener_mensaje_socket(){

		socket.onmessage = function (event) {
	//console.log( event.data );

			var datos = JSON.parse(event.data);
			console.log(datos);

			if ( datos.modulo ) {


				if ( ( datos.modulo == 'Mensaje' ) ) {

					obtener_mensajes_sala( datos.id_sal );
					
				}

			}

		}
	
	}

	
</script>

<script>
//MENSAJES



// VALIDACION DE QUE EXISTE LA SALA
//CASO VERDADERO SE CARGAN LOS MENSAJES
// CASO FALSO NO ENTRA A LA CONDICION

// setTimeout(function(){
// 	$('#input_mensaje').focus();
// }, 300);


id_usuario = <?php echo $id_pro; ?>;
tipo_usuario = 'Profesor';



<?php  
	
	$existencia_sala = obtener_existencia_sala( $id, $tipo, $id_pro, 'Profesor' );

	// echo 'existencia_sala: '.$existencia_sala.' // id: '.$id.'// tipo: '.$tipo.' // id2: '.$id_pro.' // tipo2: Profesor'."<hr>";

	if ( $existencia_sala != 'Falso' ) {
?>
		
		obtener_mensajes_sala( <?php echo $existencia_sala; ?> );

<?php
	}
?>


	id_sal = '<?php echo $existencia_sala; ?>';




//CREACION DE SALA Y ENVIO DE MENSAJES
$("#input_mensaje").on("keypress", function(e) {
	  //const $eTargetVal = $(e.target).val();
	if (e.keyCode === 13 && $(this).val().length > 0) {
		
		var mensaje = $(this).val();
		

		$.ajax({
			url: 'server/agregar_mensaje.php',
			type: 'POST',
			data: { id_sal, id_usuario, tipo_usuario, mensaje },
			success: function( respuesta ){
				console.log( respuesta );

				if ( !isNaN( respuesta ) ) {

					var id_sal = respuesta;

					var datos = {
							    
					    modulo : 'Mensaje',
					    id_sal : id_sal

					};

					socket.send( JSON.stringify( datos ) );
					
					obtener_mensajes_sala( id_sal );

				}

				$('#input_mensaje').val("");
				//toastr.info('¡Enviado correctamente!');

			}
		});

	}else{
		console.log("Mensaje vacio");
	}
});
	
</script>
<!-- FIN MENSAJES -->