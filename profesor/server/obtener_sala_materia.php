<?php  
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');

	$id_sub_hor = $_POST['id_sub_hor'];	
	
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

<div class="container animated fadeInDown">

	<div class="row grey text-center">
		<h3 class="white-text p-2">
			<i class="fas fa-comments"></i>
			<?php  
				echo $nom_sal;
			?>
		<h3/>
	</div>

	<br>
	
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
									WHERE id_sub_hor5 = '$id_sub_hor'
									UNION
									SELECT nom_alu AS nombre, app_alu AS apellido1, fot_alu AS foto, tip_alu AS tipo, id_alu AS id
									FROM alu_hor
									INNER JOIN sub_hor ON  sub_hor.id_sub_hor = alu_hor.id_sub_hor5
									INNER JOIN  alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
									INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
									WHERE id_sub_hor5 = '$id_sub_hor'

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
				              <div class="form-group basic-textarea">

				                <textarea class="form-control pl-2 my-0" rows="3" placeholder="Escribe un mensaje..." id="msj" soy="" sala="" required="">
				                	
				                </textarea>
				               

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
		$("#msj").emojioneArea({
			pickerPosition: "top"
		});
	});
</script>


<script>
	
	$(document).ready(function() { 
		var el = $("#msj").emojioneArea();//REEMPLAZO DEL CLASICO .val("")
		el[0].emojioneArea.setText(''); // clear input 
        
        insertarMensaje();
        var id_sal = <?php echo $id_sal; ?>;
        
        cargarMensajesAuxiliar(id_sal);
        cargarMensajes(id_sal);
      	

    });

    function insertarMensaje(){
      $('#btn_send').on('click', function(event) {
        event.preventDefault();
        var msj = $('#msj').val();
 

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
				  
				  
				}

			});
        }

       
      });
    }


	
    function cargarMensajesAuxiliar(id_sal){
       	var aux = 0;
     	var temporizador = setInterval(function(){
        $.ajax({
          type: "POST",
          url: "server/listar_mensajes_materia.php",
          data: {id_sal, aux},
          
			success: function(respuesta){
				  //console.log(response);
				  
				  var mensajes = respuesta;
				  console.log(mensajes);
				  if (mensajes > aux) {
				  	
				  	aux = mensajes;
				  	cargarMensajes(id_sal);
				  }

				  // var altura = $("#listadoMensajes").prop("scrollHeight");
				  // $("#listadoMensajes").scrollTop(altura);
			}

      	});

      	}, 3000);

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


				$(".materias").on('click', function(event) {
					// $(".materias").off('click');
					clearInterval(temporizador);

				});

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