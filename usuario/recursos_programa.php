<?php  

	include('inc/header.php');
	$id_ram = $_GET['id_ram'];



	$sqlRama = "SELECT * FROM rama WHERE id_ram = '$id_ram'";
	$resultadoRama = mysqli_query($db, $sqlRama);
	$filaRama = mysqli_fetch_assoc($resultadoRama);

	$nom_ram = $filaRama['nom_ram'];

?>

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Recursos teóricos">
			<i class="fas fa-bookmark"></i> 
			Recursos teóricos
		</span>
		<br>
		<div class="badge badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Recursos teóricos</a>
		</div>
		
	</div>

	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Recursos de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
		
	</div>
	
</div>
<!-- FIN TITULO -->
		

<!-- ROW TABLA -->

		<?php  

			$sqlBloques = "
				SELECT nom_vid AS actividad, tip_vid AS tipo, nom_mat AS materia, nom_ram AS programa, nom_pla AS plantel, nom_blo AS bloque, id_vid AS identificador, cic_mat AS nivel, '' AS archivo
				FROM video
				INNER JOIN bloque ON bloque.id_blo = video.id_blo1
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_ram = '$id_ram'
				UNION
				SELECT nom_wik AS actividad, tip_wik AS tipo, nom_mat AS materia, nom_ram AS programa, nom_pla AS plantel, nom_blo AS bloque, id_wik AS identificador, cic_mat AS nivel, '' AS archivo
				FROM wiki
				INNER JOIN bloque ON bloque.id_blo = wiki.id_blo2
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_ram = '$id_ram'
				UNION
				SELECT nom_arc AS actividad, tip_arc AS tipo, nom_mat AS materia, nom_ram AS programa, nom_pla AS plantel, nom_blo AS bloque, id_arc AS identificador, cic_mat AS nivel, arc_arc AS archivo
				FROM archivo
				INNER JOIN bloque ON bloque.id_blo = archivo.id_blo3
				INNER JOIN materia ON materia.id_mat = bloque.id_mat6
				INNER JOIN rama ON rama.id_ram = materia.id_ram2
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_ram = '$id_ram'
				ORDER BY tipo ASC
			";

			// echo $sqlBloques;
			$resultadoBloques = mysqli_query($db, $sqlBloques);

		?>

	
				<?php 
					$i = 1;
					while($filaBloques = mysqli_fetch_assoc($resultadoBloques)){

				?>

					<div class="card m-3" style="border-radius: 20px;">
						<div class="card-body" >

							<table class="table">
								
								<tbody>

									<td>
										<?php echo $i; $i++;?>
									</td>

									<td title="<?php echo $filaBloques['actividad']; ?>">

										<span class="letraPequena">
											<?php echo $filaBloques['tipo']; ?>
										</span>
										
										<br>


										<?php

											if ( ( $filaBloques['tipo'] == 'Archivo' ) ) {
												
												$archivo = $filaBloques['archivo'];

												if ( $archivo != NULL ) {
													$path = "../uploads/$archivo";

													$validacionEliminacion = file_exists($path);
													
													if ( $validacionEliminacion == false ) {
													
										?>
														<a href="#" class="btn-link text-danger sinArchivo" identificador="<?php echo $filaBloques['identificador']; ?>" nombre_archivo="<?php echo $filaBloques['archivo']; ?>" tipo="<?php echo $filaBloques['tipo']; ?>">
															<?php echo comprimirTexto( $filaBloques['actividad'] ); ?>
														</a>
										<?php
													
													} else {
										?>
														<a href="../uploads/<?php echo $filaBloques['archivo']; ?>" download class="btn-link text-primary" identificador="<?php echo $filaBloques['identificador']; ?>" tipo="<?php echo $filaBloques['tipo']; ?>">
															<i class="fas fa-file-download fa-1x"></i> <?php echo comprimirTexto( $filaBloques['actividad'] ); ?>
														</a>

										<?php
													}
													
												}
											
											} else {
										?>
												<a href="#" class="btn-link text-primary  conArchivo" identificador="<?php echo $filaBloques['identificador']; ?>" tipo="<?php echo $filaBloques['tipo']; ?>">
													<?php echo comprimirTexto( $filaBloques['actividad'] ); ?>
												</a>
										<?php
											}


										?>
										
										
										<br>
										
										<?php
											if ( $filaBloques['tipo'] == 'Video' ) {
										?>
											<a href="video_bloque.php?id_vid=<?php echo $filaBloques['identificador']; ?>" class="btn-link" target="_blank">

										<?php
											} else if ( $filaBloques['tipo'] == 'Wiki' ) {
										?>
											<a href="wiki_bloque.php?id_wik=<?php echo $filaBloques['identificador']; ?>" class="btn-link" target="_blank">

										<?php	
											} else if ( $filaBloques['tipo'] == 'Archivo' ) {
										?>
											<a href="archivo_bloque.php?id_arc=<?php echo $filaBloques['identificador']; ?>" class="btn-link" target="_blank">
										<?php	
											}
										?>

									</td>

									<td>
										<?php echo $filaBloques['materia']; ?>
										<br>
										<?php echo $filaBloques['bloque']; ?>
										
									</td>


									<td>
										Estatus
									
										<br>

										<?php

											if ( ( $filaBloques['tipo'] == 'Archivo' ) ) {
												
												$archivo = $filaBloques['archivo'];

												if ( $archivo != NULL ) {
													
													$path = "../uploads/$archivo";

													$validacionEliminacion = file_exists($path);
													
													if ( $validacionEliminacion == false ) {
										?>
											<span class="text-danger">
												No existe
											</span>
										<?php
														
													
													} else {
												?>
														<span class="text-success">
															Disponible
														</span>	
												<?php
													}
													
												}
											
											} else {
												echo 'N/A';
											}


										?>
									</td>



								</tbody>
							
							</table>
							


						</div>
					</div>

				<?php
					} 

				?>
	
		



<!-- MODAL GENERACION -->

<!-- AGREGAR -->
<div class="modal fade" id="modal_archivo">
  <div class="modal-dialog modal-lg" role="document">


  	<form id="formulario_archivo" enctype="multipart/form-data" method="POST">
        

        <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
	      	<div class="modal-header text-center">
	        	<h5 class="modal-title">Subir archivo</h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true">&times;</span>
	        	</button>
	      	</div>
	      
	      	<div class="modal-body mx-3">
	      		<p class="grey-text letraPequena">
	      			*Peso Máximo: 50MB - Word, Power Point y PDF
	      		</p>
	      		<div class="row">
	      			
	      			<div class="col-md-12">

	      				<div class="file-upload-wrapper">
					      <div class="input-group mb-3 border border-success">
					        
					        <input type="hidden" id="id_arc" name="id_arc">

					        <input type="hidden" id="nombre_archivo" name="nombre_archivo">

					        <input type="file" id="arc_arc" name="arc_arc" class="file_upload " placeholder="Sube Archivo"  required="" />

					      </div>
					    </div>

					    <div class="progress md-progress" style="height: 20px">
					        <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo">
					            
					          
					        </div>
					    </div>

	      			</div>
	      		</div>
	      	</div>


	      	<div class="modal-footer d-flex justify-content-center">
	    	
		    	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_formulario_archivo">
	                Guardar
	            </button>

	            <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
	                Cancelar
	            </a>    


		    </div>

	    </div>

	    

	     

	</form>

  </div>
</div>
<!-- FIN AGREGAR -->
<!-- FIN MODAL GENERACION -->

<?php  

	include('inc/footer.php');

?>

<script>
	$('.file_upload').file_upload();
</script>


<script>
	$("#formulario_archivo").on("submit", function(event){
    	event.preventDefault();

    	$("#btn_formulario_archivo").attr( 'disabled', 'disabled' );


    if ($("#arc_arc")[0].files[0]) {

      var fileName = $("#arc_arc")[0].files[0].name;
      var fileSize = $("#arc_arc")[0].files[0].size;

      var ext = fileName.split('.').pop();

      
      if(ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf'){
        if ( fileSize < 50000000 ) {
          let barra_estado_archivo = $("#barra_estado_archivo");
          //Eliminacion de "Listo"
          barra_estado_archivo.text("");

          //Remueve clase de estatus listo
          barra_estado_archivo.removeClass();

          //Agrega la clase inicial del progress bar
          barra_estado_archivo.addClass('progress-bar text-center white-text');

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
            url: 'server/editar_archivo.php',
            type: 'POST',
            data: new FormData(formulario_archivo),
            processData: false,
            contentType: false,
            cache: false,
            success: function(respuesta){
            	console.log(respuesta);
              if (respuesta == "Exito") {
                console.log("Guardado Exitosamente");
                swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {
                	
                	window.location.reload();

                });
              }
            }
          });
        }else{
          swal ( "Archivo inválido" ,  "¡Te recordamos que el peso no debe exceder los 50MB!" ,  "error" )
        }
        
      }else{
        swal ( "Archivo inválido" ,  "¡Te recordamos que los formatos aceptados son Word, Excel y PDF!" ,  "error" )
      }

    }

    
  });

</script>


<script>
	$('.sinArchivo').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var tipo = $(this).attr('tipo');
		var identificador = $(this).attr('identificador');
		var nombre_archivo = $(this).attr('nombre_archivo');


		$('#modal_archivo').modal('show');

		$('#id_arc').val( identificador );
		$('#nombre_archivo').val( nombre_archivo );


		console.log( identificador );

	});
</script>