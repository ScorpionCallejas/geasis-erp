<?php  
	//ARCHIVO VIA AJAX PARA OBTENER RECURSOS TEORICOS
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_blo = $_POST['id_blo'];


	$totalRecursos = contadorRecursosTeoricosServer( $id_blo );

	if ( $totalRecursos > 0 ) {
		// HAY RECURSOS
?>
		
		<?php  
	        $sql = "
	        	SELECT id_vid AS identificador, nom_vid AS titulo, des_vid AS descripcion, fec_vid AS fecha, 'Video' AS tipo
	        	FROM video
	        	WHERE id_blo1 = '$id_blo'
				UNION
				SELECT id_wik AS identificador, nom_wik AS titulo, des_wik AS descripcion, fec_wik AS fecha, 'Wiki' AS tipo
				FROM wiki
				WHERE id_blo2 = '$id_blo'
				UNION
				SELECT id_arc AS identificador, nom_arc AS titulo, des_arc AS descripcion, fec_arc AS fecha, 'Archivo' AS tipo
				FROM archivo
				WHERE id_blo3 = '$id_blo'
	        	ORDER BY fecha DESC

	        ";


	        // echo $sql;
	        $resultado = mysqli_query( $db, $sql );
	        $i = 1;
	        while( $fila = mysqli_fetch_assoc( $resultado ) ){
	    		// echo $sql;
	    ?>

	    	<div class="row">
				
				<?php


    				if ( $fila['tipo'] == 'Video' ) {
    					$filaVideo = obtenerDatosActividadServer( $fila['tipo'], $fila['identificador'] );
    			?>
    					<div class="col-md-8 ">

			    			<a class="btn btn-rounded btn-block white btn-sm recursoVideo waves-effect" vid_vid="<?php echo $filaVideo['vid_vid']; ?>" des_vid="<?php echo $fila['descripcion']; ?>" nom_vid="<?php echo $fila['titulo']; ?>" url_vid="<?php echo $filaVideo['url_vid']; ?>" title="Visualizar <?php echo $fila["titulo"]; ?>">

			    				<div class="row">
			    					<div class="col-md-3 text-left" >
			    						<?php  
			    							if ( $filaVideo['url_vid'] != NULL ) {
			    						?>
												<i class="fab fa-youtube fa-2x red-text"></i>
			    						<?php
			    							} else {
			    						?>
												<i class="fas fa-video fa-2x black-text"></i>
			    						<?php
			    							}
			    						?>
			    						
			    					</div>

			    					<div class="col-md-9 text-left">
			    						<label class="font-weight-normal">
			    							<?php echo substr( $fila["titulo"], 0, 20 ); ?>	
			    						</label>
			    						
			    					</div>
			    				</div>
					          
					        </a>
			
			    		</div>


			    		<div class="col-md-4">

			    			<!--Dropdown primary-->
							<div class="dropdown">

							  <!--Trigger-->

								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;position: relative; top: -15%;right: -45%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>


							  <!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect recursoVideo" vid_vid="<?php echo $filaVideo['vid_vid']; ?>" des_vid="<?php echo $fila['descripcion']; ?>" nom_vid="<?php echo $fila['titulo']; ?>" url_vid="<?php echo $filaVideo['url_vid']; ?>" href="#">
										Visualizar
									</a>
									
									<!-- <a class="dropdown-item waves-effect" href="#">Editar</a> -->
									<?php  
										if ( $estatusUsuario == 'Activo' ) {
									?>
											<a class="dropdown-item waves-effect  eliminacionVideo" eliminacionVideo="<?php echo $fila['identificador']; ?>" video="<?php echo $fila['titulo']; ?> " href="#">Eliminar</a>
									<?php
										}
									?>
									
								</div>
							</div>
							<!--/Dropdown primary-->
							
							

			    		</div>

    			<?php	
    				} else if ( $fila['tipo'] == 'Wiki' ) {
    			?>	
						
						<div class="col-md-8 ">

			    			<a class="btn btn-rounded btn-block white btn-sm recursoWiki waves-effect" id_wik="<?php echo $fila['identificador']; ?>" title="Visualizar <?php echo $fila["titulo"]; ?>">

			    				<div class="row">
			    					<div class="col-md-3 text-left" >
			    						
			    						<i class="fab fa-wikipedia-w fa-2x green-text"></i>
			    						
			    					</div>

			    					<div class="col-md-9 text-left">
			    						<label class="font-weight-normal">
			    							<?php echo substr( $fila["titulo"], 0, 20 ); ?>	
			    						</label>
			    						
			    					</div>
			    				</div>
					          
					        </a>
			
			    		</div>


			    		<div class="col-md-4">

			    			<!--Dropdown primary-->
							<div class="dropdown">

							  <!--Trigger-->

								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;position: relative; top: -15%;right: -45%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>


							  <!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect recursoWiki" id_wik="<?php echo $fila['identificador']; ?>" href="#">
										Visualizar
									</a>
									

									<?php  
										if ( $estatusUsuario == 'Activo' ) {
									?>
											<a class="dropdown-item waves-effect edicion" edicion="<?php echo $fila['identificador']; ?>">
												Editar
											</a>

											<a class="dropdown-item waves-effect  eliminacion" eliminacion="<?php echo $fila['identificador']; ?>" wiki="<?php echo $fila['titulo']; ?>" nombreWiki="<?php echo $fila['titulo']; ?> " href="#">Eliminar</a>

									<?php
										}
									?>
									
								</div>
							</div>
							<!--/Dropdown primary-->
							
			    		</div>
				<?php
    				} else if ( $fila['tipo'] == 'Archivo' ) {
    					$filaArchivo = obtenerDatosActividadServer( $fila['tipo'], $fila['identificador'] );
    					
    					$formatoArchivo = obtenerFormatoArchivo($filaArchivo['arc_arc']);
    			?>
				
    					<div class="col-md-8 ">

			    			<a class="btn btn-rounded btn-block white btn-sm waves-effect recursoArchivo" arc_arc="<?php echo $filaArchivo['arc_arc']; ?>" des_arc="<?php echo $filaArchivo['des_arc']; ?>" nom_arc="<?php echo $fila['titulo']; ?>" title="Visualizar <?php echo $fila["titulo"]; ?>">

			    				<div class="row">
			    					<div class="col-md-3 text-left" >
			    						
			    						<?php  
			    							if ( $formatoArchivo == 'docx' ) {
			    						?>
			    								<i class="fas fa-file-word fa-2x blue-text"></i>

			    						<?php
			    							} else if ( $formatoArchivo == 'pptx' ) {
			    						?>
												<i class="fas fa-file-powerpoint fa-2x orange-text"></i>

			    						<?php	
			    							} else if ( $formatoArchivo == 'pdf' ) {
			    						?>
			    								<i class="fas fa-file-pdf fa-2x red-text"></i>

			    						<?php	
			    							} else if ( ( $formatoArchivo == 'xls' ) || ( $formatoArchivo == 'xlsx' ) ) {
			    						?>

			    								<i class="fas fa-file-excel fa-2x green-text"></i>

			    						<?php
			    							}
			    						?>
			    						
			    					</div>

			    					<div class="col-md-9 text-left">
			    						<label class="font-weight-normal">
			    							<?php echo substr( $fila["titulo"], 0, 20 ); ?>	
			    						</label>
			    						
			    					</div>
			    				</div>
					          
					        </a>
			
			    		</div>


			    		<div class="col-md-4">

			    			<!--Dropdown primary-->
							<div class="dropdown">

							  <!--Trigger-->

								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;position: relative; top: -15%;right: -45%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>


							  <!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect recursoArchivo" arc_arc="<?php echo $filaArchivo['arc_arc']; ?>" des_arc="<?php echo $filaArchivo['des_arc']; ?>" nom_arc="<?php echo $fila['titulo']; ?>" title="Visualizar <?php echo $fila["titulo"]; ?>" href="#">
										Visualizar
									</a>
									
									<!-- <a class="dropdown-item waves-effect" href="#">Editar</a> -->
									<?php  
										if ( $estatusUsuario == 'Activo' ) {
									?>
											<a class="dropdown-item waves-effect  eliminacionArchivo" eliminacionArchivo="<?php echo $filaArchivo['id_arc']; ?>" archivo="<?php echo $fila['titulo']; ?> " href="#">Eliminar</a>
									<?php
										}
									?>
									
								</div>
							</div>
							<!--/Dropdown primary-->
							
							

			    		</div>


    			<?php	
    				} else {
    			?>
    					<div class="col-md-8 ">

			    			<a class="btn btn-rounded btn-block white btn-sm waves-effect recursoArchivo" arc_arc="<?php echo $filaArchivo['arc_arc']; ?>" des_arc="<?php echo $filaArchivo['des_arc']; ?>" nom_arc="<?php echo $fila['titulo']; ?>" title="Visualizar <?php echo $fila["titulo"]; ?>">

			    				<div class="row">
			    					<div class="col-md-3 text-left " >
			    						
			    						<i class="fas fa-file-download fa-2x blue-text"></i>
			    						
			    						
			    					</div>

			    					<div class="col-md-9 text-left">
			    						<label class="font-weight-normal">
			    							<?php echo substr( $fila["titulo"], 0, 20 ); ?>	
			    						</label>
			    						
			    					</div>
			    				</div>
					          
					        </a>
			
			    		</div>


			    		<div class="col-md-4">

			    			<!--Dropdown primary-->
							<div class="dropdown">

							  <!--Trigger-->

								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;position: relative; top: -15%;right: -45%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>


							  <!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect recursoArchivo" arc_arc="<?php echo $filaArchivo['arc_arc']; ?>" des_arc="<?php echo $filaArchivo['des_arc']; ?>" nom_arc="<?php echo $fila['titulo']; ?>" title="Visualizar <?php echo $fila["titulo"]; ?>" href="#">
										Visualizar
									</a>
									
									<!-- <a class="dropdown-item waves-effect" href="#">Editar</a> -->
									<?php  
										if ( $estatusUsuario == 'Activo' ) {
									?>
											<a class="dropdown-item waves-effect  eliminacionArchivo" eliminacionArchivo="<?php echo $filaArchivo['id_arc']; ?>" archivo="<?php echo $fila['titulo']; ?> " href="#">Eliminar</a>
									<?php
										}
									?>
									
								</div>
							</div>
							<!--/Dropdown primary-->
							
							

			    		</div>

    			<?php
    				}
    			?>
	    		

	    	
	    	</div>

	    	
			
			<hr>
	        

        <?php  
	    	}
	    ?>


	    

<?php
	} else {
?>
		

		<br>
		<?php  
			if ( $estatusUsuario == 'Activo' ) {
		?>
				<div class="row animated fadeIn">
					<div class="col-md-12 text-center">
						
						<h4>
							<span class="badge badge-warning">
								¡No hay recursos teóricos!
							</span>
						</h4>
						
						<img src="../img/pensativo.gif" width="40%" class="animated tada delay-2s">
						
						
						<br>
						<br>


						<h5>
							<span class="badge badge-warning">
								¡Agrega uno!
							</span>
						</h5>

						<br>
						<br>

					</div>
				</div>

		<?php
			} else {
		?>
				<div class="row animated fadeIn">
					<div class="col-md-12 text-center">
						
						<h4>
							<span class="badge badge-warning">
								¡No hay recursos teóricos!
							</span>
						</h4>
						
						<img src="../img/pensativo.gif" width="40%" class="animated tada delay-2s">
						
						
						<br>
						<br>


						<h5>
							<span class="badge badge-warning">
								¡Solicita permisos a la administración para subir contenido!
							</span>
						</h5>

						<br>
						<br>

					</div>
				</div>

		<?php
			}
		?>
		
		
		


<?php
	}
?>



<script>
	// VISUALIZACION VIDEO MODAL
	$(".recursoVideo").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		console.log("click");

		$("#contenidoModalVideo").html("");
		var vid_vid = $(this).attr("vid_vid");
		var nom_vid = $(this).attr("nom_vid");
		var des_vid = $(this).attr("des_vid");
		var url_vid = $(this).attr("url_vid");


		//ATRIBUTO A URL DE YOUTUBE PARA INCRUSTAR VIDEOS EN IFRAMES
		url_vid = url_vid.replace("watch?v=", "embed/");

		$("#tituloVideo").text(nom_vid);
		$("#descripcionVideo").text(des_vid);

		
		$("#modalVideoPlayer").modal('show');

		if (url_vid == "") {
			$("#contenidoModalVideo").append(
				'<div class="embed-responsive embed-responsive-16by9 z-depth-1-half">'+
	              '<iframe class="embed-responsive-item" src="../uploads/'+vid_vid+'" allowfullscreen controls autoplay></iframe>'+
	            '</div>'


			);
		}else{
			$("#contenidoModalVideo").append(
				'<div class="embed-responsive embed-responsive-16by9 z-depth-1-half">'+
				  '<iframe class="embed-responsive-item" src="'+url_vid+'" allowfullscreen allow="accelerometer; autoplay; encrypted-media; '+'gyroscope; picture-in-picture"></iframe>'+
				'</div>');

		}
		
	});

	$("#limpiarVideos").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$("#contenidoModalVideo").html("");
	});


	$("#modalVideoPlayer").draggable();
	// FIN VISUALIZACION VIDEO MODAL


	// ELIMINACION VIDEO
	//ELIMINACION DE VIDEO
	$('.eliminacionVideo').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var video = $(this).attr("eliminacionVideo");
		var nombreVideo = $(this).attr("video");

		// console.log(VIDEO);

		swal({
		  title: "¿Deseas eliminar "+nombreVideo+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
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
				url: 'server/eliminacion_video.php',
				type: 'POST',
				data: {video},
				success: function(respuesta){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							
							obtenerRecursosTeoricos();
							generarAlerta( 'Cambios guardados' );
						});
					}else{
						// console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
	// DIN ELIMINACION VIDEO
</script>


<script>
	// WIKI
	//EDICION DE WIKI

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE WIKI

	$('.edicion').on('click', function(){
		
		var edicionWiki = $(this).attr("edicion");
		$('#labelEdicion').addClass('active');

		//console.log(edicionWiki);

		$.ajax({
			url: 'server/obtener_wiki.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionWiki},
			success: function(datos){

				//console.log(datos);


				$('#editarWikiModal').modal('show');
				editorWikiEdicion.value = datos.des_wik;
				$('#tituloWikiEdicion').attr({value: datos.nom_wik});

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL WIKI
				$('#editarWikiFormulario').on('click', function(event) {
					event.preventDefault();

					var wikiContenidoEdicion = editorWikiEdicion.value;
					var wikiTituloEdicion = $('#tituloWikiEdicion').val();
					var id_wik = datos.id_wik;

					
					$.ajax({
					
						url: 'server/editar_wiki.php',
						type: 'POST',
						data: {id_wik, wikiContenidoEdicion, wikiTituloEdicion},
						success: function(respuesta){
							// console.log(respuesta);

							if (respuesta == 'Exito') {
								swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
								then((value) => {
									obtenerRecursosTeoricos();
									$('#editarWikiModal').modal('hide');
									generarAlerta( 'Cambios guardados' );
								});
								
							}
						}
					});

				});
			}
		});
	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE WIKI


	//ELIMINACION DE WIKI
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var wiki = $(this).attr("eliminacion");
		var nombreWiki = $(this).attr("wiki");

		// console.log(WIKI);

		swal({
		  title: "¿Deseas eliminar "+nombreWiki+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
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
				url: 'server/eliminacion_wiki.php',
				type: 'POST',
				data: {wiki},
				success: function(respuesta){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							
							obtenerRecursosTeoricos();
							generarAlerta( 'Cambios guardados' );
						
						});
					
					}else{
						// console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});



	$(".recursoWiki").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    console.log("click");

	    $("#tituloWikiVista").text("");
	    $("#contenidoWikiVista").html("");

	    var edicionWiki = $(this).attr("id_wik");


	    $.ajax({
			url: 'server/obtener_wiki.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionWiki},
			success: function(datos){
		
				$("#modalWiki").modal('show');


				console.log( datos.nom_wik );
			    
			    $('#tituloWikiVista').html( datos.nom_wik );
			    // $("#tituloWikiVista").text(datos.nom_wik);
			    $("#contenidoWikiVista").html(datos.des_wik);

				

			}
		});	    
	    
	  });

	// FIN WIKI
</script>


<script>
	//ARCHIVO
	//ELIMINACION DE ARCHIVO
	  $('.eliminacionArchivo').on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    var archivo = $(this).attr("eliminacionArchivo");
	    var nombreArchivo = $(this).attr("archivo");

	    // console.log(ARCHIVO);

	    swal({
	      title: "¿Deseas eliminar "+nombreArchivo+"?",
	      text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
	      icon: "warning",
	      buttons:  {
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
	        url: 'server/eliminacion_archivo.php',
	        type: 'POST',
	        data: {archivo},
	        success: function(respuesta){
	          
	          if (respuesta == "true") {
	            console.log("Exito en consulta");
	            swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
	            then((value) => {
	            	obtenerRecursosTeoricos();
					generarAlerta( 'Cambios guardados' );
	            });
	          }else{
	            // console.log(respuesta);

	          }

	        }
	      });
	        
	      }
	    });
	  });
	  

	  $(".recursoArchivo").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    console.log("click");

	    $("#contenidoModalArchivo").html("");
	    var arc_arc = $(this).attr("arc_arc");
	    var nom_arc = $(this).attr("nom_arc");
	    var des_arc = $(this).attr("des_arc");

	    //console.log(des_arc);


	    $("#tituloArchivo").text(nom_arc);
	    $("#descripcionArchivo").text(des_arc);

	    
	    $("#modalArchivo").modal('show');
	    $("#contenidoModalArchivo").append('<a href="../uploads/'+arc_arc+'" download class="btn-link"><i class="fas fa-file-download fa-2x"></i> Descargar</a>');
	  });
	// FIN ARCHIVO
</script>