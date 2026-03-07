<?php  
		

  	include('inc/header.php');

 
  	if ( !isset( $_GET['id_gru'] ) ) {

  		header('location: not_found_404_page.php');
  
  	} else {

  		$id_gru = $_GET['id_gru'];


		$sqlGrupo = "
			SELECT * 
			FROM grupo
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1 
			WHERE id_gru = '$id_gru'
		";

		$resultadoGrupo = mysqli_query($db, $sqlGrupo);

		$filaGrupo = mysqli_fetch_assoc($resultadoGrupo);

		// DATOS RAMA
		$id_ram =  $filaGrupo['id_ram'];
		$nom_ram =  $filaGrupo['nom_ram'];
		$mod_ram = $filaGrupo['mod_ram'];

		// DATOS CICLO	
		$id_cic =  $filaGrupo['id_cic'];
		$nom_cic =  $filaGrupo['nom_cic'];
		$ini_cic = $filaGrupo['ini_cic'];

		// DATOS GRUPO
		$nom_gru =  $filaGrupo['nom_gru'];	

  	}



	$sqlEdicionHorario = "
		SELECT * 
    	FROM sub_hor
        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_gru1 = '$id_gru'

	";

	$resultadoEdicionHorarioDatos = mysqli_query( $db, $sqlEdicionHorario );

	$filaEdicionHorarioDatos = mysqli_fetch_assoc( $resultadoEdicionHorarioDatos );
	// DATOS RAMA
	$nom_ram = $filaEdicionHorarioDatos['nom_ram'];
	$mod_ram = $filaEdicionHorarioDatos['mod_ram'];
	$gra_ram = $filaEdicionHorarioDatos['gra_ram'];
	$per_ram = $filaEdicionHorarioDatos['per_ram'];
	$cic_ram = $filaEdicionHorarioDatos['cic_ram'];

	// DATOS CICLO ESCOLAR
	$nom_cic = $filaEdicionHorarioDatos['nom_cic'];
	$ins_cic = $filaEdicionHorarioDatos['ins_cic'];
	$ini_cic = $filaEdicionHorarioDatos['ini_cic'];
	$cor_cic = $filaEdicionHorarioDatos['cor_cic'];
	$fin_cic = $filaEdicionHorarioDatos['fin_cic'];

	// DATOS GRUPO
	$nom_gru = $filaEdicionHorarioDatos['nom_gru'];

?>



<!-- TITULO -->

<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
	background-image: url('../fondos_clase/trabajo_especial.jpg'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; top: -40px; position: relative; 

">
	
	<div class="col text-left col-sm-6">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" style="font-size: 1.5vw;">
			<i class="fas fa-bookmark"></i> 
			Trabajos especiales
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Grupo: <?php echo $nom_gru; ?>
		</span>

		
	</div>

	<div class="col text-right col-sm-6">

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Programa: <?php echo $nom_ram.' '.$mod_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Ciclo escolar: <?php echo $nom_cic; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Del <?php echo fechaFormateadaCompacta2( $ini_cic ).' al '.fechaFormateadaCompacta2( $fin_cic ); ?>
		</span>
	
		
		
	</div>
	
</div>
<!-- FIN TITULO -->

<!-- MODALES -->


<!-- OBTENER PROYECTO -->
<div class="modal fade text-left " id="modal_obtener_proyecto">
  	<div class="modal-dialog modal-lg" role="document">
    

      	<div class="modal-content" style="border-radius: 20px;">
	        <div class="modal-header text-center">
	          
	          	<h4 class="modal-title w-100" id="titulo_obtener_proyecto">
		        	
		        </h4>

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>

	        <div class="modal-body mx-3" id="contenedor_obtener_proyecto">

	       	</div>

	        <div class="modal-footer d-flex justify-content-center">

	 
		      	
		      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>

	          
	        </div>

      	</div>

  	</div>
</div>
<!-- FIN OBTENER PROYECTO -->

<!-- archivo -->
<!-- CONTENIDO MODAL AGREGAR archivo -->
<div class="modal fade text-left " id="agregarArchivoModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form id="formularioArchivo" enctype="multipart/form-data" method="POST">
      <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header text-center">
          
          	<h4 class="modal-title w-100">
	        	Trabajo especial
	        </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

        	<p class="letraPequena grey-text">
	      		NOTA: Los campos con * son obligatorios
	      	</p>

	      	<div class="row">
	      		
	      		<div class="col-md-12">
	      			
	      			<div class="md-form mb-2">

			          	<i class="fas fa-info prefix grey-text"></i>
			          	<input type="text" id="nom_pro" name="nom_pro" class="form-control validate">
			          	<label  for="nom_pro">* Asigna un título</label>
			        
			        </div>
	      		
	      		</div>
	      	
	      	</div>

          	<div class="row">
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna un puntaje
			      	</p>

					<div class="md-form mb-2">
						<i class="fas fa-award prefix grey-text"></i>
						<input type="number" id="pun_pro" min="0" name="pun_pro" step=".1" class="form-control validate" value="10">
					</div>

				</div>

				<div class="col-md-4">

					<p class="letraPequena grey-text">
			      		* Asigna una fecha de inicio
			      	</p>



					<div class="md-form mb-2">

						<i class="fas fa-minus-circle prefix grey-text"></i>
						<input type="date" id="ini_pro" min="0" step="1" name="ini_pro" class="form-control validate">
						
					</div>
			    
			  	</div>
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna una fecha de vencimiento
			      	</p>
			        
			        <div class="md-form mb-2">
				        <i class="fas fa-plus-circle prefix grey-text"></i> 
				        <input type="date" id="fin_pro"  class="form-control validate" name="fin_pro">
			        </div>
			    
			  	</div>
			
			</div>

			<!-- ARCHIVO -->
			<div class="md-form mb-5">
        
		        <div class="file-field">
		          	
		          	<div class="btn btn-info btn-sm float-left">
		            	<span>Sube un archivo</span>
		            	<input type="file" id="arc_pro" name="arc_pro" required="">
		          	</div>
		          	
		          	<div class="file-path-wrapper">
		            	<input class="file-path validate letraPequena disabled" type="text" placeholder="* Peso Máximo: 50MB - Imagen ( JPG, JPEG o PNG ), Word, Power Point, Excel o PDF">
		          	</div>
		        
		        </div>
		    
		    </div>
		      

		      
		      

		      <div id="file" class="text-center">
		        
		      </div>
			<!-- FIN ARCHIVO -->


			<div class="row">
				<div class="col-md-12">

				<br>
					<div id="boxEntregable">
						<p class="letraPequena grey-text">
				      		* Asigna las intrucciones
				      	</p>

						<div id="des_pro">
							<p style="text-align: center;"><span style="font-family: Tahoma, Geneva, sans-serif; font-size: 24px;">Estimado Alumno </span></p><p style="text-align: center;"><span style="font-family: Tahoma, Geneva, sans-serif; font-size: 24px;">Recuerda realizar tu archivo especial en tiempo y forma. Así como presentarlo en las fechas establecidas.</span><br>
							<br>
							<br>
							      
			        		</p>
		        		</div>
						
					</div>
				
				</div>

			</div>

        
			<div class="progress md-progress" style="height: 20px" id="contenedor_barra_estado">
	          
	      	</div>
        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar archivo" id="btn_formulario_archivo">
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
<!-- FIN CONTENIDO MODAL AGREGAR archivo <-->
<!-- FIN archivo  -->




<!-- EDITAR PROYECTO -->
<div class="modal fade text-left " id="editarArchivoModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form id="formularioEditarArchivo" enctype="multipart/form-data" method="POST">
      <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header text-center">
          
          	<h4 class="modal-title w-100">
	        	Trabajo especial
	        </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

        	<p class="letraPequena grey-text">
	      		NOTA: Los campos con * son obligatorios
	      	</p>


	      	<div class="row">
	      		
	      		<div class="col-md-12">
	      			
	      			<div class="md-form mb-2">

			          	<i class="fas fa-info prefix grey-text"></i>
			          	<input type="text" id="nom_pro_edicion" name="nom_pro_edicion" class="form-control validate">
			          	<label  for="nom_pro_edicion">* Asigna un título</label>
			        
			        </div>
	      		
	      		</div>
	      	
	      	</div>

          	<div class="row">
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna un puntaje
			      	</p>

					<div class="md-form mb-2">
						<i class="fas fa-award prefix grey-text"></i>
						<input type="number" id="pun_pro_edicion" min="0" name="pun_pro_edicion" step=".1" class="form-control validate">
					</div>

				</div>

				<div class="col-md-4">

					<p class="letraPequena grey-text">
			      		* Asigna una fecha de inicio
			      	</p>



					<div class="md-form mb-2">

						<i class="fas fa-minus-circle prefix grey-text"></i>
						<input type="date" id="ini_pro_edicion" min="0" step="1" name="ini_pro_edicion" class="form-control validate">
						
					</div>
			    
			  	</div>
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna una fecha de vencimiento
			      	</p>
			        
			        <div class="md-form mb-2">
				        <i class="fas fa-plus-circle prefix grey-text"></i> 
				        <input type="date" id="fin_pro_edicion"  class="form-control validate" name="fin_pro_edicion">
			        </div>
			    
			  	</div>
			
			</div>

			
			<!-- ARCHIVO -->
			<div class="md-form mb-5">
        
		        <div class="file-field">
		          	
		          	<div class="btn btn-info btn-sm float-left">
		            	<span>Sube un archivo</span>
		            	<input type="file" id="arc_pro_edicion" name="arc_pro_edicion">
		          	</div>
		          	
		          	<div class="file-path-wrapper">
		            	<input class="file-path validate letraPequena disabled" type="text" id="arc_pro_edicion_placeholder">
		          	</div>
		        
		        </div>
		    
		    </div>
		      

		      
		      

		    <div id="file_edicion" class="text-center">
		       
		    </div>
			<!-- FIN ARCHIVO -->


			<div class="row">
				<div class="col-md-12">

				<br>
					<div id="boxEntregable">
						<p class="letraPequena grey-text">
				      		* Asigna las intrucciones
				      	</p>

						<div id="des_pro_edicion">
		        		</div>
						
					</div>
				
				</div>

			</div>


			<input type="hidden" id="id_pro_edicion" name="id_pro_edicion">

        
        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar cambios" id="btn_formulario_editar_archivo">
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

<!-- FIN EDITAR PROYECTO -->



<!-- FIN MODALES -->





<a class="btn-info btn btn-rounded btn-sm" href="#" title="Agrega un nuevo archivo especial asociado a este grupo" id="agregarArchivo">
  	<i class="fas fa-plus"></i>
  	Agregar trabajo especial

</a>




<!-- CONTENEDOR REPORTE -->
<div class="row">

	<div class="col-md-12">

		<div class="card" style="border-radius: 20px;" id="contenedor_trabajos_especiales">
		<!--  -->


			

			
			

			
		<!--  -->
		</div>
	</div>
</div>



<?php 
	include('inc/footer.php');
?>


<script>
	obtener_trabajos_especiales();

	function obtener_trabajos_especiales(){
		var id_gru = <?php echo $id_gru; ?>;
		$.ajax({
			url: 'server/obtener_trabajos_especiales.php',
			type: 'POST',
			data: { id_gru },
			success: function( respuesta ){

				$('#contenedor_trabajos_especiales').html( respuesta );

			}
		});
		
	}
</script>

<script>
  //archivo

  	var des_pro = new Jodit("#des_pro", {
	    "language": "es",
	    toolbarStickyOffset: 50,
	    "uploader": {
		    "insertImageAsBase64URI": true
		}

	});

	var des_pro_edicion = new Jodit("#des_pro_edicion", {
	    "language": "es",
	    toolbarStickyOffset: 50,
	    "uploader": {
		    "insertImageAsBase64URI": true
		}

	});

  //CODIGO PARA AGREGAR archivo NUEVO ABRIENDO MODAL
 	$('#agregarArchivo').on('click', function(event) {
		event.preventDefault();
		console.log("archivo");
		$('#agregarArchivoModal').modal('show');

		$("#btn_formulario_archivo").removeAttr('disabled');
		
		$('#contenedor_barra_estado').html('<div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo"></div>');

		setTimeout( function(){
            
            $( '#nom_pro' ).focus();
            $( '#nom_pro' ).val('Trabajo especial');
            $( '#pun_pro' ).val(10);

            $( '#ini_pro' ).val("<?php echo date( 'Y-m-d' ); ?>");
            $( '#fin_pro' ).val("<?php echo gmdate( 'Y-m-d', strtotime ( '+ 2 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>");


        }, 200 );
    
  	});


  $("#formularioArchivo").on("submit", function(event){
    	event.preventDefault();

    	$("#btn_formulario_archivo").attr( 'disabled', 'disabled' );

    	if ($("#arc_pro")[0].files[0]) {

      		var fileName = $("#arc_pro")[0].files[0].name;
      		var fileSize = $("#arc_pro")[0].files[0].size;

      		var ext = fileName.split('.').pop();

      
  			if( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf' || ext == 'xls' || ext == 'xlsx' ){
    			
    			if ( fileSize < 50000000 ) {
      				
      				let barra_estado_archivo = $("#barra_estado_archivo");
					//Eliminacion de "Listo"
					barra_estado_archivo.text("");

					//Remueve clase de estatus listo
					barra_estado_archivo.removeClass();

					//Agrega la clase inicial del progress bar
					barra_estado_archivo.addClass('progress-bar text-center white-text');


					var formularioArchivo = new FormData( $('#formularioArchivo')[0] );

					formularioArchivo.append( 'id_gru', <?php echo $id_gru; ?> );
					formularioArchivo.append( 'des_pro', des_pro.value );

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
								generarAlerta('Cambios guardados');
							});

          					return peticion;
          				},
			            
			            url: 'server/agregar_trabajo_especial.php',
			            type: 'POST',
			            data: formularioArchivo,
			            processData: false,
			            contentType: false,
			            cache: false,
			            success: function( respuesta ) {
			            	console.log(respuesta);
			              	if (respuesta == "Exito") {
			                	console.log("Guardado Exitosamente");
			                	swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
			                	then((value) => {
			                		
			                		$("#btn_formulario_archivo").removeAttr( 'disabled' );
			                		
			                		$("#formularioArchivo input").val('');
									
									$('#agregarArchivoModal').modal('hide');
									obtener_trabajos_especiales();


			                	});
			              	}
			            }
      				
      				});
    			
    			} else {
		          	
		          	$("#btn_formulario_archivo").removeAttr( 'disabled' );
		          	swal ( "Archivo inválido" ,  "¡Te recordamos que el peso no debe exceder los 50MB!" ,  "error" )
		        }
    
  			} else {
    			
    			$("#btn_formulario_archivo").removeAttr( 'disabled' );
    			swal ( "Archivo inválido" ,  "¡Te recordamos que los formatos aceptados son Imágenes( jpeg, jpg o png ), Word, PowerPoint, Excel y PDF!" ,  "error" )
  			}

    	}

 	});


  	// $("#modalarchivo").draggable();
</script>