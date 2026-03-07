<?php  

	require('../inc/cabeceras.php');
 	require('../inc/funciones.php');

 	$id_sal = $_POST['id_sal'];

 	$sqlMensajes = "
 		SELECT *
 		FROM mensaje
 		WHERE id_sal4 = '$id_sal'
 		ORDER BY hor_men ASC
 	";

 	// echo $sqlMensajes;

 	$resultadoMensajes = mysqli_query( $db, $sqlMensajes );
 	
 	$fecha_aux = '';
 	$contador = 1;
 	while( $filaMensajes = mysqli_fetch_assoc( $resultadoMensajes ) ){
 		
 		

 		$datosContactoUltimoMensaje = obtener_datos_contacto_mensajeria_server( $filaMensajes['tip_men'], $filaMensajes['use_men'] );


 		if ( $datosContactoUltimoMensaje['tipo'] == 'Admin' ) {

          $datosContactoUltimoMensaje['tipo'] = 'Directivo';
        
        } else if ( $datosContactoUltimoMensaje['tipo'] == 'Profesor' ) {
          
          $datosContactoUltimoMensaje['tipo'] = 'Capacitador';

        } else if ( $datosContactoUltimoMensaje['tipo'] == 'Adminge' ) {
          
          $datosContactoUltimoMensaje['tipo'] = 'Coordinador';

        }
?>
		<?php  
			if ( $contador == 1 ) {
				$fecha_aux = fechaFormateadaCompacta2($filaMensajes['hor_men']);
		?>

				<div class="row">
					<div class="col-md-12 text-center">
						<div class="badge badge-pill badge-success letraMediana font-weight-normal">
							<?php echo $fecha_aux; ?>
						</div>
					</div>
				</div>	
		<?php
			} else {

				if ( $fecha_aux != fechaFormateadaCompacta2($filaMensajes['hor_men']) ) {

					$fecha_aux = fechaFormateadaCompacta2($filaMensajes['hor_men']);
		?>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="badge badge-pill badge-success letraMediana font-weight-normal">
								<?php echo $fecha_aux; ?>
							</div>
						</div>
					</div>
		<?php
				}
			}

			$contador++;

			
		?>

		

		<?php  
			if ( ( $filaMensajes['use_men'] == $id ) && ( $filaMensajes['tip_men'] == $tipo ) ) {

		?>
				<!-- EMISOR -->
				
				<div class=" p-1 rounded bg-white shadow-sm message-item" style="position: relative; max-width: 50%; margin-left:50%; background-color: #dcf8c6!important;">

					<div class="small font-weight-bold text-primary" >
						<div class="badge badge-warning badge-pill small font-weight-normal" >
							<?php echo $datosContactoUltimoMensaje['tipo']; ?></div>
	        				<span><?php echo $datosContactoUltimoMensaje['nombre']; ?></span>
					</div>

					<a href="#"><i class="fas fa-times text-muted px-2 eliminacionMensaje" id_men="<?php echo $filaMensajes['id_men']; ?>" title="Elimina este mensaje para todos" style="position: absolute; right: 5px; top: 5px;"></i></a>
					
					<div class="d-flex flex-row">

							
							  <?php  
		                        if ( $filaMensajes['arc_men'] != '' ) {

		                        	$formatoArchivo = obtenerFormatoArchivo( $filaMensajes['arc_men'] );
		                      ?>

		                          <a href="../archivos/<?php echo $filaMensajes['arc_men']; ?>" download class="btn-link" title="Descargar: <?php echo $filaMensajes['arc_men']; ?>">
		                              <?php  
		                                if ( $formatoArchivo == 'docx' || $formatoArchivo == 'doc' ) {
		                              ?>
		                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

		                              <?php
		                                } else if ( $formatoArchivo == 'pptx' || $formatoArchivo == 'ppt' ) {
		                              ?>
		                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

		                              <?php 
		                                } else if ( $formatoArchivo == 'pdf' ) {
		                              ?>
		                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

		                              <?php 
		                                } else if ( $formatoArchivo == 'xlsx' || $formatoArchivo == 'xlx' ){
		                              ?>

		                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

		                              <?php
		                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
		                              ?>  <!-- 
		                                  <img src="../archivos/<?php echo $filaMensajes['arc_men']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
		                                  <br> -->
		                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
		                                  

		                                  <a href="../archivos/<?php echo $filaMensajes['arc_men']; ?>" data-lightbox="roadtrip">
		                                    <img src="../archivos/<?php echo $filaMensajes['arc_men']; ?>" class="img-fluid" style=" width: 15vw;">
		                                  </a>

		                                  

		                              <?php
		                                }
		                              ?>
		                              
		                          </a>
		                      <?php
		                        } else{
		                      ?>
		                          <span class="p-2"><?php echo $filaMensajes['men_men']; ?></span>

		                      <?php
		                        }
		                      ?>
							
							<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:75px;">
								<?php echo horaFormateadaCompacta2($filaMensajes['hor_men']); ?>

								<?php  
									echo obtener_estatus_mensaje_server( $filaMensajes['id_men'] );
								?>
								
							</div>
							
						
					</div>
				</div>

				<div style="height: 8px;"></div>
				<!-- FUN EMISOR -->

				

		<?php
			} else {
		?>

				<!-- RECEPTOR -->
				
				<div class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item" style="position: relative; max-width: 50%;">
					
					
					<div class="small font-weight-bold text-primary">
						<div class="badge badge-warning badge-pill small font-weight-normal" ><?php echo $datosContactoUltimoMensaje['tipo']; ?></div>
	        			<span><?php echo $datosContactoUltimoMensaje['nombre']; ?></span>
					</div>
					
					<div class="d-flex flex-row">

						  <?php  
	                        if ( $filaMensajes['arc_men'] != '' ) {

	                        	$formatoArchivo = obtenerFormatoArchivo( $filaMensajes['arc_men'] );
	                      ?>

	                          <a href="../archivos/<?php echo $filaMensajes['arc_men']; ?>" download class="btn-link" title="Descargar: <?php echo $filaMensajes['arc_men']; ?>">
	                              <?php  
	                                if ( $formatoArchivo == 'docx' || $formatoArchivo == 'doc' ) {
	                              ?>
	                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

	                              <?php
	                                } else if ( $formatoArchivo == 'pptx' || $formatoArchivo == 'ppt' ) {
	                              ?>
	                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

	                              <?php 
	                                } else if ( $formatoArchivo == 'pdf' ) {
	                              ?>
	                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

	                              <?php 
	                                } else if ( $formatoArchivo == 'xlsx' || $formatoArchivo == 'xlx' ){
	                              ?>

	                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

	                              <?php
	                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
	                              ?>  <!-- 
	                                  <img src="../archivos/<?php echo $filaMensajes['arc_men']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
	                                  <br> -->
	                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
	                                  <br>
	                                  <a href="../archivos/<?php echo $filaMensajes['arc_men']; ?>" data-lightbox="roadtrip">
	                                    <img src="../archivos/<?php echo $filaMensajes['arc_men']; ?>" class="img-fluid" style=" width: 15vw;">
	                                  </a>

	                                  

	                              <?php
	                                }
	                              ?>
	                              
	                          </a>
	                      <?php
	                        } else{
	                      ?>
	                        	<span class=""><?php echo $filaMensajes['men_men']; ?></span>
	                      <?php
	                        }
	                      ?>
						
						<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:75px;">
							<?php echo horaFormateadaCompacta2($filaMensajes['hor_men']); ?>
							
						</div>
					</div>
				</div>
				<!-- FIN RECEPTOR -->
				

		<?php
			}
		?>
		
		
		
<?php
 	}
?>

<script>
	$('.eliminacionMensaje').on('click', function(event) {
      	event.preventDefault();
      	/* Act on the event */
      	var id_sal = <?php echo $id_sal; ?>;
      	
      	var id_men = $(this).attr('id_men');

      	swal({
		  title: "¿Deseas eliminar este mensaje?",
		  text: "¡Una vez confirmes, se borrará el mensaje para todos!",
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
				url: 'server/eliminacion_mensaje.php',
				type: 'POST',
				data: { id_men },
				success: function(respuesta){
					
					if (respuesta == "true") {
						
						obtener_mensajes_sala( id_sal );

						var datos = {
							    
						    modulo : 'Mensaje',
						    id_sal : id_sal

						};

						socket.send( JSON.stringify( datos ) );
						
						obtener_datos_sala( id_sal );

					}else{
						console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
</script>