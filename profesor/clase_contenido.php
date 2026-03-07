<?php  

	include('inc/header.php');
	$id_blo = $_GET['id_blo'];

	$id_sub_hor = $_GET['id_sub_hor'];
	// echo $id_blo;

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

	$sqlGrupo = "
		SELECT *
		FROM sub_hor
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_sub_hor = '$id_sub_hor'
	";


	// echo $sqlGrupo;

	$resultadoGrupo = mysqli_query( $db, $sqlGrupo );

	$filaGrupo = mysqli_fetch_assoc( $resultadoGrupo );


?>


<style>

	.claseSeleccionPuntado{
		border: 3px solid black;
		border-style: dashed;
		border-radius: 20px;
		margin: 5px;
	}

	.claseHijoActividad {
	  position: absolute;
	  left: 60px;
	  top: 5px;
	  z-index: 100;
	  
	}

	.clasePadreActividad {
	  position: relative;
	}

	.claseHijo {
		position: absolute;
		right: -3%;
		bottom: -20%;
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




</style>


<div class="fixed-action-btn smooth-scroll" style="bottom: 45px; right: 24px;">
  <a href="#mainBody" class="btn-floating btn-large btn-info" title="Ir hasta arriba">
    <i class="fas fa-arrow-up"></i>
  </a>
</div>




<!-- TITULO -->

<?php  
	
	if ( $img_blo == NULL ) {
?>
		<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
			background-image: url('../fondos_clase/img_backtoschool.jpg'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; top: -40px; position: relative; 

		">


<?php
	} else {
?>
		
		<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
			background-image: url('../fondos_clase/<?php echo $img_blo; ?>'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; top: -40px; position: relative; 

		">

<?php

	}

?>


	<a href="#" class="btn btn-link claseHijo" id="btn_cambiar_fondo">Cambiar fondo</a>
	
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

			
			<a class="text-white" href="clases_materia.php?id_sub_hor=<?php echo $id_sub_hor; ?>" title="Vuelve a clases">Clases</a>
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
			Grupo: <?php echo $filaGrupo['nom_gru']; ?>
		</span>
		
		
	</div>
	
</div>
<!-- FIN TITULO -->



<div class="row">
	<div class="col-md-4">

		<div class="card claseSticky z-depth-1"  style="border-radius: 20px;">
			

			<div class="card-header bg-white black-text"  style="border-radius: 20px;">
				

				<a href="video_clase.php?id_sub_hor=<?php echo $id_sub_hor; ?>&validador" class="btn-floating btn-sm  grey darken-2
					white-text  dropdown-toggle" target="_blank" onClick="window.open(this.href, this.target, 'width=600, height=500'); return false;">
					<i class="fas fa-video"></i>
	                
				</a>Video-clase				
			</div>


			<div class="card-header bg-white black-text"  style="border-radius: 20px;">

				<!--Dropdown primary-->
				<div class="dropdown" style="width: 200px;">

				  <!--Trigger-->
		
				  	<?php  
				  		if ( $estatusUsuario == 'Activo' ) {
				  	?>
							<a class="btn-floating btn-sm btn-info btn_recurso  dropdown-toggle" type="button" id="dropdownMenuTipo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Agregar un recurso teórico" data-step="1" data-intro="Comparte diversos tipos de contenidos con tu clase, entre videos, wikis o archivos" data-position='right'>
								<i class="fas fa-plus"></i>
							</a>

				  	<?php
				  		}
				  	?>
				  	<span>
				  		Recursos teóricos
				  	</span>
	    			 


				  <!--Menu-->
					<div class="dropdown-menu dropdown-info">
						<a class="dropdown-item waves-effect letraPequena" href="#" id="agregarVideo">
							Agregar video
						</a>
						<a class="dropdown-item waves-effect letraPequena" href="#" id="agregarWiki">
							Agregar wiki
						</a>
						<a class="dropdown-item waves-effect letraPequena" href="#" id="agregarArchivo">
							Agregar archivo
						</a>
					</div>
				</div>
				<!--/Dropdown primary-->

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
				
				<?php  
					if ( $estatusUsuario != 'Activo' ) {
				?>
						<?php echo $con_blo; ?>

				<?php
					} else {
				?>
						<?php 
							if( $con_blo != "" ) {
						?>

								<div id="box">
									<div id="editor">
										<?php echo $con_blo; ?>

					      
					        		</div>
									
								</div>

						<?php
							} else {
						?>	


								<div id="box">
									<div id="editor">
										
										
										
										
										
										
										
										<h4 style="text-align: center;"><u><em><span style="color: red;">NOTA: ¡esto es un ejemplo!</span></em></u></h4><h4 style="text-align: center;"><span style="font-size: 16px;">BIENVENIDOS</span></h4><h4 style="text-align: center;"><?php echo $nom_blo; ?></h4><h4 style="text-align: center;">								<span style="font-size: 16px;"><?php echo $des_blo; ?></span></h4><p style="text-align: center;"><br></p><h4 style="text-align: left;"><u>Objetivos</u></h4><ol><li><span style="font-size: 18px;">Objetivo A</span></li><li><span style="font-size: 18px;">Objetivo B</span></li><li><span style="font-size: 18px;">Objetivo C</span></li></ol><h4 style="text-align: left;"><br></h4><h4 style="text-align: left;">

					      
					        		

					      
					        		</h4>

					      
					        		

					      
					        		

					      
					        		

					      
					        		</h4>

					      
					        		

					      
					        		</div>
									
								</div>
							
						<?php
							}
						?>

							<div class="row">
								<div class="col-md-12 text-right">
									<!-- BOTON AGREGAR -->
									<a class="btn btn-info white-text btn-rounded btn-sm" title="Guardar contenido de mi clase" id="agregarContenido">
							        	Guardar
							        </a>
									<!-- FIN BOTON AGREGAR -->
								</div>
							</div>

				<?php
					}
				?>		
				


					
					


				<!-- FIN CONTENEDOR CONTENIDO -->
			</div>



		</div>


		<br>
		
		<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
			
			<div class="card-header bg-white"  style="border-radius: 20px;">
				

				<!--Dropdown primary-->
				<div class="dropdown" style="width: 200px;">
	
				  <!--Trigger-->

					<?php  
						if ( $estatusUsuario == 'Activo' ) {
					?>
							<a class="btn-floating btn-sm btn-info btn_actividad  dropdown-toggle" type="button" id="dropdownMenuActividad" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Agregar una actividad" data-step="1" data-intro="Agrega actividades a tu clase, entre foros, tareas o exámenes" data-position='right'>
								<i class="fas fa-plus"></i>
							</a> Agregar actividad

					<?php
						} else {
					?>
							Actividades

					<?php
						}
					?>
	    			


				  <!--Menu-->
					<div class="dropdown-menu dropdown-info">
						<a class="dropdown-item waves-effect letraPequena" href="#" id="agregarForo">
							Agregar foro
						</a>
						<a class="dropdown-item waves-effect letraPequena" href="#" id="agregarEntregable">
							Agregar Tarea
						</a>
						<a class="dropdown-item waves-effect letraPequena" href="#" id="agregarExamen">
							Agregar cuestionario
						</a>
					</div>
				</div>
				<!--/Dropdown primary-->

			</div>


		</div>

		<hr>

		<div id="contenedor_actividades">
			
		</div>
	
	</div>


</div>



<!-- COPIAR ACTIVIDAD -->
<div class="modal fade text-left " id="modal_copiar_actividad">
 
  	<div class="modal-dialog modal-lg" role="document">
    
 
      	<div class="modal-content">
	        <div class="modal-header text-center">
	          
	          	<h4 class="modal-title w-100 white-text">
	          		Copiar actividad <span id="titulo_copiar_actividad"></span>
	          	</h4>

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>
	        <div class="modal-body" id="contenedor_copiar_actividad">

	        	

	          


	        </div>

	        <div class="modal-footer d-flex justify-content-center">

	        	<a class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." id="btn_copiar_actividad">
		            Confirmar
		        </a>

	          	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>
	        </div>

	      </div>


  		</div>
	</div>
</div>
<!-- FIN COPIAR ACTIVIDAD -->


<!-- FONDO CLASE -->
<div class="modal fade text-left " id="modal_cambiar_fondo">
  	<div class="modal-dialog modal-lg" role="document">
    
  
      	<div class="modal-content">
	        <div class="modal-header text-center">
	          
	          <h4 class="modal-title w-100 white-text">
	            Selecciona un fondo
	          </h4>

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>
	        <div class="modal-body mx-3">

	        	<h5>
	        		Haz click sobre el fondo deseado
	        	</h5>


	        	<div class="row">

	        	<?php  
	        		$directorio = '../fondos_clase';

					$ficheros  = scandir( $directorio );
					$contador = 1;
	        		
					for ( $i = 0;  $i < sizeof( $ficheros );  $i++ ) { 
						if ( $i > 1 ) {
				?>
							<div class="col-md-6">

								<div class="view seleccionImagen" style="border-radius: 20px;" imagen="<?php echo $ficheros[$i]; ?>">
								  	<img src="../fondos_clase/<?php echo $ficheros[$i]; ?>" class="img-fluid" alt="placeholder">
								  	<div class="mask flex-center waves-effect waves-light">
								  	</div>
								</div>

							</div>



						<?php
				          if ( $contador % 2 == 0 ) {
				        ?>
				            </div>
				            <hr>
				            <div class="row">
				          
				        <?php
				          }
				        ?>

				<?php
							$contador++;
						} //FIN IF
					}// FIN for
	        	?>

	          


	        </div>

	        <div class="modal-footer d-flex justify-content-center">
	          	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>
	        </div>

	      </div>


  		</div>
	</div>
</div>

<!-- FIN FONDO CLASE -->



<!-- MODALES RECURSOS -->


<!-- VIDEO -->
<!-- CONTENIDO MODAL AGREGAR VIDEO -->
<div class="modal fade text-left " id="agregarVideoModal">
  <div class="modal-dialog" role="document">
    
	<form id="formularioVideo" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 white-text">
	        	Agregar video
	        </h4>
	        <!-- COPIA -->

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      	<p class="letraPequena grey-text">
	      		NOTA: Los campos con * son obligatorios
	      	</p>

	      	<div class="md-form mb-5">
	      	  <i class="fas fa-info prefix grey-text"></i>
	      	  
	          <input type="text" id="nom_vid" name="nom_vid" class="form-control validate" required="">
	          <label  for="nom_vid">* Título del Video</label>
	        </div>


	        <div class="md-form mb-5">
			  <i class="fas fa-file-video prefix grey-text"></i>
	          <input type="text" id="des_vid" name="des_vid" class="form-control validate">
	          <label  for="des_vid">Descripción del Video</label>
	        </div>

			
			<div class="md-form mb-3">
				<i class="fas fa-asterisk prefix grey-text"></i>
				<label  for="form34">Define dónde estará el video</label>
				<br>
		        <select class="mdb-select md-form colorful-select dropdown-primary" id="select_fuente">
				  <option value="sistema">Sistema</option>
				  <option value="youtube" selected>Youtube</option>
				</select>

			</div>
			<br>


			<div class="md-form mb-5" id="opcion_select">
			  
	        </div>
			
			

			<div class="progress md-progress" style="height: 20px" id="barra_video">
			    <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado">
			    	
			    
			    </div>
			</div>
			

			<div id="player">
				
			</div>

	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        
	        <button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar video" id="btn_formulario_video">
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
<!-- FIN CONTENIDO MODAL AGREGAR VIDEO <-->

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
<!-- CONTENIDO MODAL AGREGAR WIKI -->
<div class="modal fade text-left " id="agregarWikiModal">
  <div class="modal-dialog modal-lg" role="document">
    
	<form >
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        


	        <h4 class="modal-title w-100 white-text">
				
	        	<i class="fab fa-wikipedia-w" title="Agregar wiki"></i>
	        	Agregar wiki
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
			
			<p class="letraPequena grey-text">
	      		NOTA: Los campos con * son obligatorios
	      	</p>

	      	<div class="md-form mb-5">


	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="tituloWiki" class="form-control validate">
	          <label  for="tituloWiki">* Asigna un título</label>
	        </div>

	


	         
	          <div id="boxWiki" class="border">
				<div id="editorWiki">
					<br><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
        		</div>
				
			</div>


	      </div>

	      <div class="modal-footer d-flex justify-content-center">

	      	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar video" id="agregarWikiFormulario">
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
<!-- FIN CONTENIDO MODAL AGREGAR WIKI -->



<!-- CONTENIDO MODAL EDITAR WIKI -->
<div class="modal fade text-left " id="editarWikiModal">
  	<div class="modal-dialog modal-lg" role="document">
    
		<form >
	    	<div class="modal-content">
	      		<div class="modal-header text-center">
	        
			        <h4 class="modal-title w-100 white-text">
			        	Editar wiki
			        </h4>

			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
	      		</div>
	      		
	      		<div class="modal-body mx-3">
			
					<p class="letraPequena grey-text">
			      		NOTA: Los campos con * son obligatorios
			      	</p>

			      	<div class="md-form mb-5">
						<i class="fas fa-info prefix grey-text"></i>
		          		<input type="text" id="tituloWikiEdicion" class="form-control validate">
		          		<label  for="tituloWikiEdicion" id="labelEdicion">* Edita el título</label>
	        	
			      	</div>

		          	
					<div id="boxWikiEdicion">
						<div id="editorWikiEdicion">

							
		        		</div>
					</div>
	        	

	        	</div>


	        	<div class="modal-footer d-flex justify-content-center">

			      	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar video" id="editarWikiFormulario">
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
<!-- FIN CONTENIDO MODAL EDITAR WIKI -->
<!-- FIN MODAL EDICION WIKI -->



<!-- VISTA DE WIKI -->
<!-- CONTENIDO MODAL AGREGAR WIKI -->
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
<!-- FIN CONTENIDO MODAL AGREGAR WIKI -->

<!-- FIN VISTA DE WIKI -->
<!-- FIN WIKI -->


<!-- ARCHIVO -->
<!-- CONTENIDO MODAL AGREGAR ARCHIVO -->
<div class="modal fade text-left " id="agregarArchivoModal">
  <div class="modal-dialog" role="document">
    
  <form id="formularioArchivo" enctype="multipart/form-data" method="POST">
      <div class="modal-content">
        <div class="modal-header text-center">
          
          	<h4 class="modal-title w-100 white-text">
	        	Agregar archivo
	        </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

        	<p class="letraPequena grey-text">
	      		NOTA: Los campos con * son obligatorios
	      	</p>

          	<div class="md-form mb-5">
	            <i class="fas fa-info prefix grey-text"></i>
	            
	            <input type="text" id="nom_arc" name="nom_arc" class="form-control validate" required="">
	            <label  for="nom_arc">* Título del Archivo</label>
          	</div>


	        <div class="md-form mb-5">
	            <i class="fas fa-file prefix grey-text"></i>
	            <input type="text" id="des_arc" name="des_arc" class="form-control validate">
	            <label  for="des_arc">Descripción del Archivo</label>
	        </div>

        <div class="md-form mb-5">
        
        <div class="file-field">
          <div class="btn btn-info btn-sm float-left">
            <span>Sube un archivo</span>
            <input type="file" id="arc_arc" name="arc_arc">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate letraPequena disabled" type="text" placeholder="* Peso Máximo: 50MB - Word, Power Point, Excel o PDF">
          </div>
        </div>
        </div>
      

      <div class="progress md-progress" style="height: 20px" id="contenedor_barra_estado_archivo">
          
      </div>
      

      <div id="file" class="text-center">
        
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
<!-- FIN CONTENIDO MODAL AGREGAR ARCHIVO <-->
<!-- FIN ARCHIVO  -->





<!-- CONTENIDO MODAL AGREGAR ARCHIVO -->
<!-- Button trigger modal -->


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


<!-- MODALES ACTIVIDADES -->


<!-- FORO -->
<!-- CONTENIDO MODAL AGREGAR FORO -->
<div class="modal fade text-left " id="agregarForoModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form >
      <div class="modal-content">
        <div class="modal-header text-center">
          
          	<h4 class="modal-title w-100 white-text">
	        	Agregar foro
	        </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

        	<p class="letraPequena grey-text">
	      		NOTA: Todos los campos con * son obligatorios
	      	</p>

          <div class="md-form mb-5">

            <i class="fas fa-info prefix grey-text"></i>
            <input type="text" id="tituloForo" class="form-control validate">
            <label  for="tituloForo">* Asigna un título</label>
          </div>


          	<div class="row">
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna un puntaje
			      	</p>

					<div class="md-form mb-2">
						<i class="fas fa-award prefix grey-text"></i>
						<input type="number" id="pun_for" min="0" step=".1" class="form-control validate" value="10">
					</div>

				</div>

				<div class="col-md-4">

					<p class="letraPequena grey-text">
			      		* Asigna una fecha de inicio
			      	</p>



					<div class="md-form mb-2">

						<i class="fas fa-minus-circle prefix grey-text"></i>
						<input type="date" id="ini_for" min="0" step="1" class="form-control validate" value="<?php echo date( 'Y-m-d' ); ?>">
						
					</div>
			    
			  	</div>
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna una fecha de vencimiento
			      	</p>
			        
			        <div class="md-form mb-2">
				        <i class="fas fa-plus-circle prefix grey-text"></i> 
				        <input type="date" id="fin_for"  class="form-control validate" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 2 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
			        </div>
			    
			  	</div>
			
			</div>


			<div class="row">
				<div class="col-md-12">

				<br>
					<div id="boxForo">
						<p class="letraPequena grey-text">
				      		* Asigna las intrucciones
				      	</p>

						<div id="des_for">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							      
		        		</div>
						
					</div>
				
				</div>

			</div>





        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar archivo" id="agregarForoFormulario">
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
<!-- FIN CONTENIDO MODAL AGREGAR FORO -->





<!-- FIN FORO -->



<!-- ENTREGABLE -->
<!-- CONTENIDO MODAL AGREGAR ENTREGABLE -->
<div class="modal fade text-left " id="agregarEntregableModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form >
      <div class="modal-content">
        <div class="modal-header text-center">
          
          	<h4 class="modal-title w-100 white-text">
	        	Agregar tarea
	        </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

        	<p class="letraPequena grey-text">
	      		NOTA: Todos los campos con * son obligatorios
	      	</p>

          <div class="md-form mb-5">

            <i class="fas fa-info prefix grey-text"></i>
            <input type="text" id="tituloEntregable" class="form-control validate">
            <label  for="tituloEntregable">* Asigna un título</label>
          </div>


          	<div class="row">
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna un puntaje
			      	</p>

					<div class="md-form mb-2">
						<i class="fas fa-award prefix grey-text"></i>
						<input type="number" id="pun_ent" min="0" step=".1" class="form-control validate" value="10">
					</div>

				</div>

				<div class="col-md-4">

					<p class="letraPequena grey-text">
			      		* Asigna una fecha de inicio
			      	</p>



					<div class="md-form mb-2">

						<i class="fas fa-minus-circle prefix grey-text"></i>
						<input type="date" id="ini_ent" min="0" step="1" class="form-control validate" value="<?php echo date( 'Y-m-d' ); ?>">
						
					</div>
			    
			  	</div>
			  	
			  	<div class="col-md-4">

			  		<p class="letraPequena grey-text">
			      		* Asigna una fecha de vencimiento
			      	</p>
			        
			        <div class="md-form mb-2">
				        <i class="fas fa-plus-circle prefix grey-text"></i> 
				        <input type="date" id="fin_ent"  class="form-control validate" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 2 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
			        </div>
			    
			  	</div>
			
			</div>


			<div class="row">
				<div class="col-md-12">

				<br>
					<div id="boxEntregable">
						<p class="letraPequena grey-text">
				      		* Asigna las intrucciones
				      	</p>

						<div id="des_ent">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							      
		        		</div>
						
					</div>
				
				</div>

			</div>





        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar tarea" id="agregarEntregableFormulario">
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
<!-- FIN CONTENIDO MODAL AGREGAR ENTREGABLE -->



<!-- FIN ENTREGABLE -->




<!-- EXAMEN -->
<!-- CONTENIDO MODAL AGREGAR EXAMEN -->
<div class="modal fade text-left " id="agregarExamenModal" estatus_examen='falso'>
  <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
        <div class="modal-header text-center">
          
          	<h4 class="modal-title w-100 white-text">
	        	Cuestionario
	        </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

        	<!--Modal cascading tabs-->
			<div class="modal-c-tabs">

				<!-- Nav tabs -->
				<ul class="nav md-pills nav-justified pills-info" role="tablist">
					<li class="nav-item">
						<a class="nav-link active font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#panelExamen" role="tab">
							Cuestionario
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link  font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#panelPreguntas" role="tab">
							Preguntas
						</a>
					</li>
				
				</ul>

				<!-- Tab panels -->
				<div class="tab-content pt-3">
					<!--Panel 1-->
					<div class="tab-pane fade in show active" id="panelExamen" role="tabpanel">

						<p class="letraPequena grey-text">
				      		NOTA: Todos los campos con * son obligatorios
				      	</p>

			          <div class="md-form mb-5">

			            <i class="fas fa-info prefix grey-text"></i>
			            <input type="text" id="tituloExamen" class="form-control validate">
			            <label  for="tituloExamen">* Asigna un título</label>
			          </div>





			          	<div class="row">
						  	
						  	<div class="col-md-4">

						  		<p class="letraPequena grey-text">
						      		* Tu puntaje lo definen las preguntas
						      	</p>

								<div class="md-form mb-2">
									<i class="fas fa-award prefix grey-text"></i>
									<input type="text" id="pun_exa" class="form-control validate disabled" value="No disponible">
								</div>

							</div>

							<div class="col-md-4">

								<p class="letraPequena grey-text">
						      		* Asigna una fecha de inicio
						      	</p>



								<div class="md-form mb-2">

									<i class="fas fa-minus-circle prefix grey-text"></i>
									<input type="date" id="ini_exa" min="0" step="1" class="form-control validate" value="<?php echo date( 'Y-m-d' ); ?>">
									
								</div>
						    	

						  	</div>
						  	
						  	<div class="col-md-4">

						  		<p class="letraPequena grey-text">
						      		* Asigna una fecha de vencimiento
						      	</p>
						        
						        <div class="md-form mb-2">
							        <i class="fas fa-plus-circle prefix grey-text"></i> 
							        <input type="date" id="fin_exa"  class="form-control validate" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 2 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
						        </div>
						    
						  	</div>
						
						</div>

						<div class="row">
							<div class="col-md-4">
								
							</div>

							<div class="col-md-4">
								<p class="letraPequena grey-text">
						      		* Asigna una duración en minutos
						      	</p>

								<div class="md-form mb-2">
									<i class="fas fa-clock prefix grey-text"></i>
									<input type="number" id="dur_exa" min="1" step="1" class="form-control validate" value="30">
						        </div>

							</div>

							<div class="col-md-4">
								
							</div>
						</div>


						<div class="row">
							<div class="col-md-12">

							<br>
								<div id="boxExamen">
									<p class="letraPequena grey-text">
							      		* Asigna las intrucciones
							      	</p>
									

									<div id="des_exa">
										<h6 style="text-align: center;">
											Resuelve el siguiente cuestionario. Recuerda que tienes un tiempo específico para terminar. </h6><h3 style="text-align: center;">¡Éxito!
										</h3>
					        		</div>
									
								</div>
							
							</div>

						</div>
					</div>
					<!--/.Panel 1-->

					<!--Panel 2-->
					<div class="tab-pane fade" id="panelPreguntas" role="tabpanel">
						<div class="row">
							<div class="col-md-12 text-center">

								<div class="alert alert-warning alert-dismissible fade show font-weight-normal letraMediana" role="alert">
									<i class="fas fa-exclamation-circle warning-text fa-2x"></i>
						      		<br> 
						      		
									<strong>NOTA:</strong> 
									<!-- <p class="letraMediana grey-text "> -->
										Las preguntas son de opción múltiple. Así que, una vez agregues la pregunta,
										debes añadir las opciones y definir si es verdadero o falso. De esta forma el cuestionario se calificará automáticamente. Tampoco coloques numeración ni orden, el despliegue de preguntas y respuestas siempre es aleatorio para los alumnos.

										<br>

										<strong>EDICIÓN:</strong>

										Puedes editar preguntas y sus puntajes. Para continuar agregando preguntas, solamente guarda los cambios.


									
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
							</div>
						</div>

						<div class="row">
							
							<div class="col-md-12">

								<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
									
									<div class="card-body z-depth-1 bg-white" style="border-radius: 20px;">
										
										<div class="row">
											
											<div class="col-md-6">
												<a class="btn btn-info white-text btn-rounded waves-effect btn-sm" id="agregarPregunta" estatus="Agregar">
													Guardar
												</a>
											</div>

											<div class="col-md-6">

												<p class="letraPequena grey-text">
										      		* Define el puntaje
										      	</p>

								          		<div class="md-form mb-2">
								          			<i class="fas fa-check prefix grey-text"></i>
								            		<input type="number" id="puntaje" min="0" step=".1" class="form-control validate" value="1">
								          		</div>

											</div>
										</div>

										
											
										<p class="letraPequena grey-text">
								      		* Define tu pregunta
								      	</p>

										<div class="md-form mb-2">
											
											<div id="pregunta">    
							        			Tu pregunta va aquí...
								        	</div>
						          		
						          		</div>

									</div>
								</div>
							</div>
						</div>

						
						<br>

						
						
	
						
						<div id="contenedor_preguntas">
						</div>


						
					</div>
					<!--/.Panel 2-->
				</div>

			</div>

        </div>

        <div class="modal-footer d-flex justify-content-center">

        	<a class="btn btn-info white-text btn-rounded btn-sm" title="Guardar cuestionario..." id="agregarExamenFormulario">
	        	Guardar
	        </a>
	      	
	      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
	            Cancelar
	        </a>

        </div>

      </div>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR EXAMEN -->



<!-- FIN EXAMEN -->

<!-- FIN ACTIVIDADES -->



	<!-- MODAL CLASES EDICION CLASE -->
    
    <div class="modal fade" id="modal_clase_edicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info" role="document">
         <!--Content-->
         <div class="modal-content">
           <!--Header-->
            <div class="modal-header">
                <p class="heading lead" >Editar clase</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            
            <form id="formularioClaseEdicion">
           <!--Body-->
                <div class="modal-body">
                
                    <!-- Material input -->
                    <div class="md-form">
                        <i class="fas fa-chalkboard prefix grey-text"></i>
                        
                        <input type="text" id="nom_blo_edicion" class="form-control validate" name="nom_blo_edicion" required="">
                        <label for="nom_blo">Título de la clase</label>
                    </div>


                    <div class="md-form" id="des_blo_edicion_contenedor">
                        <i class="fas fa-info-circle  prefix grey-text"></i>

                        <input type="text" id="des_blo_edicion" class="form-control validate" name="des_blo_edicion" required="">
                        <label for="des_blo">Descripción de la clase</label>
                    </div>


                    
                
                </div>

               <!--Footer-->
               <div class="modal-footer justify-content-center">
                 
                <button type="submit" class="btn btn-info btn-rounded waves-effect btn-sm" title="Crear clase" id="btn_editar_clase">
                    Guardar
                </button>
                
                <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                    Cancelar
                </a>
               </div>

            </form>

         </div>
         <!--/.Content-->
        </div>
    </div>
    <!-- FIN MODAL CLASES CREACION CLASE -->



<br>
<!-- FIN CONTENIDO -->
<?php  

	include('inc/footer.php');

?>



<script>


	$( '.edicionTitulo' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var nom_blo = $( this ).attr( 'nom_blo' );
		var des_blo = $( this ).attr( 'des_blo' );

		$( '#modal_clase_edicion' ).modal( 'show' );

		$('#des_blo_edicion_contenedor label').addClass('active');
	    $('#des_blo_edicion_contenedor i').addClass('active');

		setTimeout( function(){
			$( '#nom_blo_edicion' ).focus();
		}, 1000 );

		$( '#nom_blo_edicion' ).val( nom_blo );
		$( '#des_blo_edicion' ).val( des_blo );

	});

	$( '#formularioClaseEdicion' ).on('submit', function(event) {
        event.preventDefault();
        /* Act on the event */

        
        var formularioClaseEdicion = new FormData( $('#formularioClaseEdicion')[0] );
        formularioClaseEdicion.append( 'id_blo', '<?php echo $id_blo ?>' );

        $.ajax({

            url: 'server/editar_clase.php',
            type: 'POST',
            data: formularioClaseEdicion, 
            processData: false,
            contentType: false,
            cache: false,
            success: function( respuesta ){
            
                // console.log(respuesta);



                if ( respuesta == 'true' ) {

                    swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {

                    	$( '.edicionTitulo' ).eq( 0 ).html( '<i class="fas fa-bookmark"></i> ' + $( '#nom_blo_edicion' ).val() ).removeAttr( 'nom_blo' ).removeAttr( 'des_blo' ).attr( 'nom_blo', $( '#nom_blo_edicion' ).val() ).attr( 'des_blo', $( '#des_blo_edicion' ).val() );

                    	$( '.edicionTitulo' ).eq( 1 ).html( '<i class="fas fa-bookmark"></i> ' + $( '#des_blo_edicion' ).val() ).removeAttr( 'nom_blo' ).removeAttr( 'des_blo' ).attr( 'nom_blo', $( '#nom_blo_edicion' ).val() ).attr( 'des_blo', $( '#des_blo_edicion' ).val() );


                        $( '#modal_clase_edicion' ).modal( 'hide' );
                        // alert( 'el id creado de clase es: '+respuesta );
                        


                    });
                      
                    

                } else {
                    // console.log( respuesta );
                }
            
            }
        });

    });
</script>

<script>
	//EXAMEN
	var des_exa = new Jodit("#des_exa", {
        "language": "es",
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });


    //INICIALIZACION DE EDITORES
    var pregunta = new Jodit("#pregunta", {
        "language": "es",
        "uploader": {
			"insertImageAsBase64URI": true
		}

    });



	//AGREGADO DE PREGUNTA
	$('#agregarPregunta').on('click', function(event) {
		event.preventDefault();

		var estatus = $(this).attr('estatus');
		// console.log( estatus );
		if ( estatus == 'Agregar' ) {
			console.log( 'agregarPregunta' );

			$("#agregarPregunta").attr( 'disabled', 'disabled' );
			var estatus_examen = $( '#agregarExamenModal' ).attr( 'estatus_examen' );

			var pre_pre = pregunta.value;
			var puntaje = $("#puntaje").val();

			if ( estatus_examen == 'falso' ) {
				// EXAMEN NO CREADO

				var id_blo = '<?php echo $id_blo; ?>';
				var id_sub_hor = '<?php echo $_GET['id_sub_hor']; ?>';

				var nom_exa = $( '#tituloExamen' ).val();
				var ini_exa = $( '#ini_exa' ).val();
			    var	fin_exa = $( '#fin_exa' ).val();
			    var	dur_exa = $( '#dur_exa' ).val();
			    var	descripcionExamen = des_exa.value;


				

				$.ajax({

				    url: 'server/agregar_pregunta.php',
				    type: 'POST',
				    data: {  id_blo, id_sub_hor, nom_exa, ini_exa, fin_exa, dur_exa, descripcionExamen, pre_pre, puntaje, estatus_examen },
				    success: function( respuesta ){

				      	console.log(respuesta);

				      	var id_exa_cop = parseInt( respuesta );

				      	if ( !isNaN( id_exa_cop ) ) {
					        
					        swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
					        then(( value ) => {
					        	
					        	$("#agregarPregunta").removeAttr( 'disabled' );
					        	$( '#agregarExamenModal' ).removeAttr( 'estatus_examen' ).attr( 'estatus_examen', 'verdadero' ).attr( 'id_exa', id_exa_cop );
					        	obtener_preguntas_examen( id_exa_cop );
								pregunta.value = '';
								obtenerActividades();



					        });

				      	} else {
				      		console.log( 'error, comunicalo a sistemas' );
				      	}
				    }

				});


				// FIN EXAMEN NO CREADO
			} else if ( estatus_examen == 'verdadero' ) {
				// EXAMEN CREADO

				var id_exa_cop = $( '#agregarExamenModal' ).attr( 'id_exa' );

				$.ajax({
				    url: 'server/agregar_pregunta.php',
				    type: 'POST',
				    data: { pre_pre, puntaje, id_exa_cop },
				    success: function(respuesta){

				      console.log(respuesta);
				      if (respuesta == "Exito") {
				        console.log("Guardado Exitosamente");
				        swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
				        then((value) => {

				        	$("#agregarPregunta").removeAttr( 'disabled' );
					        obtener_preguntas_examen( id_exa_cop );
							pregunta.value = '';
							obtenerActividades();


				        });
				      }
				    } 
				});

				// FIN EXAMEN CREADO
			}

		} else if ( estatus == 'Editar' ) {
			// EDICION PREGUNTA

			console.log( 'edicionPregunta' );
			var id_exa_cop = $( '#agregarExamenModal' ).attr( 'id_exa' );

			var id_pre = $(this).attr('id_pre');
			var id_exa = $(this).attr('id_exa');

			var pre_pre = pregunta.value;
			var puntaje = $("#puntaje").val();

			$.ajax({
				    url: 'server/editar_pregunta.php',
				    type: 'POST',
				    data: { pre_pre, puntaje, id_pre, id_exa },
				    success: function(respuesta){

				      	console.log(respuesta+'ajax edicion pregunta');
				      	if (respuesta == "Exito") {
				        	console.log("Guardado Exitosamente");
				        	swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
				        	then((value) => {

				        		$("#agregarPregunta").removeAttr( 'disabled' ).removeAttr('estatus').attr('estatus', 'Agregar');
					        	obtener_preguntas_examen( id_exa_cop );
								pregunta.value = '';
								obtenerActividades();

								generarAlerta("Cambios guardados");


				        	});
				      	}
				    } 
				});


			// FIN EDICION PREGUNTA
		}

		

	});

	


	//DESPLIEGUE DE MODAL
	$('#agregarExamen').on('click', function(event) {
		event.preventDefault();
		// console.log("examen");

		
		$('#agregarExamenModal').modal('show');


		setTimeout( function(){
			
			$( '#tituloExamen' ).focus();
			$( '#ini_exa' );
			$( '#fin_exa' );

		}, 1000 );
		

		$("#agregarExamenFormulario").removeAttr( 'disabled' );
		$("#agregarPregunta").removeAttr( 'disabled' );
		$( '#agregarExamenModal' ).removeAttr( 'estatus_examen' ).attr( 'estatus_examen', 'falso' ).removeAttr('id_exa');

		$( '#tituloExamen' ).val( 'Cuestionario sin título' );

		$( '#pun_exa' ).val( 'No disponible' );
		$( '#dur_exa' ).val( 30 );
		$( '#ini_exa' ).val( '<?php echo date('Y-m-d'); ?>' );
		$( '#fin_exa' ).val( '<?php echo gmdate( 'Y-m-d', strtotime ( '+ 2 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>' );
		
		des_exa.value = '<h6 style="text-align: center;">Resuelve el siguiente cuestionario. Recuerda que tienes un tiempo específico para terminar. </h6><h3 style="text-align: center;">¡Éxito!</h3>';

		pregunta.value = 'Tu pregunta va aquí...';

		$( '#contenedor_preguntas' ).html( '<div class="row"><div class="col-md-12 text-center"><div class="card z-depth-1 bg-white" style="border-radius: 20px;"><div class="card-body z-depth-1 bg-white" style="border-radius: 20px;"><h5><span class="badge badge-warning">¡No hay preguntas!</span></h5><!-- <br>--><img src="../img/acostado.gif" width="15%" class="animated tada delay-3s"></div></div></div></div>' );

	});


	
	//AGREGADO DE EXAMEN
	$('#agregarExamenFormulario').on('click', function(event) {
		    event.preventDefault();


		$("#agregarExamenFormulario").attr( 'disabled', 'disabled' );
	    var estatus_examen = $( '#agregarExamenModal' ).attr( 'estatus_examen' );

	    var nom_exa = $( '#tituloExamen' ).val();
		var ini_exa = $( '#ini_exa' ).val();
	    var	fin_exa = $( '#fin_exa' ).val();
	    var	dur_exa = $( '#dur_exa' ).val();
	    var	descripcionExamen = des_exa.value;

	    if ( estatus_examen == 'falso' ) {

	    	var id_blo = '<?php echo $id_blo; ?>';
			var id_sub_hor = '<?php echo $_GET['id_sub_hor']; ?>';

			$.ajax({
				url: 'server/agregar_examen.php',
				type: 'POST',
				data: { id_blo, id_sub_hor, nom_exa, ini_exa, fin_exa, dur_exa, descripcionExamen },
				success: function( respuesta ){
					console.log( respuesta );

					var id_exa = parseInt( respuesta );

			      	if ( !isNaN( id_exa ) ) {
				        
				        swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
				        then(( value ) => {
				        	
				        	$( '#agregarExamenModal' ).removeAttr( 'estatus_examen' ).attr( 'estatus_examen', 'verdadero' ).attr( 'id_exa', id_exa );
				        	$("#agregarExamenFormulario").removeAttr( 'disabled' );
				        	obtenerActividades();
							

				        });

			      	} else {

			      		// console.log( respuesta );
			      		// alert( 'error, comunicalo a sistemas' );
			      	}

				}

			});
			


	    } else if ( estatus_examen == 'verdadero' ) {

	    	var id_exa_cop = $( '#agregarExamenModal' ).attr( 'id_exa' );

	    	$.ajax({
				url: 'server/editar_examen.php',
				type: 'POST',
				data: { nom_exa, ini_exa, fin_exa, dur_exa, descripcionExamen, id_exa_cop },
				success: function( respuesta ){
					console.log( respuesta );

			      	if ( respuesta == 'Exito' ) {
				        
				        swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
				        then(( value ) => {
				        	
				        	
				        	$("#agregarExamenFormulario").removeAttr( 'disabled' );
							

				        });

			      	} else {

			      		// console.log( respuesta );
			      		// alert( 'error, comunicalo a sistemas' );
			      	}

				}

			});

	    }


	     
  	});


  	function obtener_preguntas_examen( id_exa_cop ){
		$.ajax({
			url: 'server/obtener_preguntas_examen.php',
			// dataType: 'text/plain',
			type: 'POST',
			data: { id_exa_cop },
			success: function( respuesta ){
				$( '#contenedor_preguntas' ).html( respuesta );
			}
		});

	
		
	}
</script>


<script>




	//INICIALIZACION DE EDITORES
    var editor = new Jodit("#editor", {
        "language": "es",
        toolbarStickyOffset: 50,
        "autofocus": true,
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });



    //INICIALIZACION DE EDITORES
    



	$('.mdb-select').materialSelect();

	$( '#btn_cambiar_fondo' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( '#modal_cambiar_fondo' ).modal( 'show' );

		$( '.seleccionImagen' ).on('click', function(event) {
			event.preventDefault();
			/* Act on the event */

			var img_blo = $( this ).attr( 'imagen' );
			var id_blo = '<?php echo $id_blo; ?>';

			// alert( img_blo );

			$.ajax({
				url: 'server/editar_fondo_clase.php',
				type: 'POST',
				data: { img_blo, id_blo },
				success: function( respuesta ){
					// console.log( respuesta );
					if ( respuesta == 'true' ) {
						$( '#contenedor_fondo_clase' ).css(
							"background-image", "url(../fondos_clase/"+img_blo+")"
						);


						generarAlerta( 'Cambios guardados' );


					}


				}
			});
			
		});
	});



	$("#agregarContenido").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var contenido = editor.value;

		//console.log(contenido);
		//SE HACE UPDATE Y SE AGREGA CONTENIDO
		$.ajax({
			url: 'server/agregar_contenido.php?id_blo=<?php echo $id_blo; ?>',
			type: 'POST',
			data: {contenido},


			success: function(respuesta){

				//console.log(respuesta);
				if (respuesta == "Exito") {
					swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",});
					obtenerRecursosTeoricos();
					generarAlerta( 'Cambios guardados' );
					console.log("Guardado Exitosamente");
				}
			}	
		});
	

	});





</script>




<script>
	//VIDEO

	//CODIGO PARA AGREGAR VIDEO NUEVO ABRIENDO MODAL
	$('#agregarVideo').on('click', function(event) {
		event.preventDefault();
		//console.log("video");
		
		$('#agregarVideoModal').modal('show');

		$("#btn_formulario_video").removeAttr('disabled');
		$('#barra_video').html('<div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado"></div>');


		setTimeout( function(){
            $( '#nom_vid' ).focus();
        }, 1500 );

		if( $('#select_fuente').val() == 'youtube' ){
			
			$('#barra_video').css('display','none');
			$("#opcion_select").html(
				'<i class="fab fa-youtube prefix grey-text"></i>'+
				'<input type="text" id="url_vid" name="url_vid" class="form-control validate" required="">'+
				'<label  for="url_vid">* URL del Video </label>'
			);
		}else{

			$('#barra_video').css('display','');
			$("#opcion_select").html(
				'<div class="file-field">'+
				    '<div class="btn btn-info btn-sm float-left">'+
				      	'<span>Sube un video</span>'+
				    	'<input type="file" id="vid_vid" name="vid_vid">'+
				    '</div>'+
				    '<div class="file-path-wrapper">'+
				      	'<input class="file-path validate letraPequena disabled" type="text" placeholder="* Peso Máximo: 200MB - Formato Válido: MP4">'+
				    '</div>'+
				'</div>'
			);
		}

		$("#select_fuente").on('change', function(event) {
			event.preventDefault();
			/* Act on the event */

			if($('#select_fuente').val() == 'youtube'){
				
				$('#barra_video').css('display','none');
				$("#opcion_select").html(
					'<i class="fab fa-youtube prefix grey-text"></i>'+
					'<input type="text" id="url_vid" name="url_vid" class="form-control validate">'+
					'<label  for="url_vid">* URL del Video </label>'
				);
			}else{

				$('#barra_video').css('display','');
				$("#opcion_select").html(
					'<div class="file-field">'+
					    '<div class="btn btn-info btn-sm float-left">'+
					      	'<span>Sube un video</span>'+
					    	'<input type="file" id="vid_vid" name="vid_vid">'+
					    '</div>'+
					    '<div class="file-path-wrapper">'+
					      	'<input class="file-path validate letraPequena disabled" type="text" placeholder="* Peso Máximo: 200MB - Formato Válido: MP4">'+
					    '</div>'+
					'</div>'
				);
			}
		});
		
	});

	$("#formularioVideo").on("submit", function(event){
		event.preventDefault();

		$("#btn_formulario_video").attr('disabled','disabled');

		if($('#select_fuente').val() == 'youtube'){
			$.ajax({
						
			
				url: 'server/agregar_video.php?id_blo=<?php echo $id_blo; ?>',
				type: 'POST',
				data: new FormData(formularioVideo),
				processData: false,
				contentType: false,
				cache: false,
				success: function(respuesta){
					if (respuesta == "Exito") {
						console.log("Guardado Exitosamente");
						swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
							obtenerRecursosTeoricos();
							$("#formularioVideo input").val('');
							$('#agregarVideoModal').modal('hide');
							  
						});
					}
				}
			});

		}else{
			if ($("#vid_vid")[0].files[0]) {

				var fileName = $("#vid_vid")[0].files[0].name;
				var fileSize = $("#vid_vid")[0].files[0].size;

				var ext = fileName.split('.').pop();

				
				if(ext == 'mp4'){
					if (fileSize < 200000000) {
						let barra_estado = $("#barra_estado");
						//Eliminacion de "Listo"
						barra_estado.text("");

						//Remueve clase de estatus listo
						barra_estado.removeClass();

						//Agrega la clase inicial del progress bar
						barra_estado.addClass('progress-bar text-center white-text');


						$.ajax({
						
							xhr: function() {
								
							    var peticion = new window.XMLHttpRequest();

							    peticion.upload.addEventListener("progress", (event)=>{
									let porcentaje = Math.round((event.loaded / event.total) *100);
									//console.log(porcentaje);

									barra_estado.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
									barra_estado.text(porcentaje+'%');

								});

								peticion.addEventListener("load", ()=>{
									barra_estado.removeClass();
									barra_estado.addClass('progress-bar text-center white-text bg-success');
									barra_estado.text("Listo");

									toastr.success('¡Subido Correctamente!');


									
								});

								return peticion;
						  	},
							url: 'server/agregar_video.php?id_blo=<?php echo $id_blo; ?>',
							type: 'POST',
							data: new FormData(formularioVideo),
							processData: false,
							contentType: false,
							cache: false,
							success: function(respuesta){
								if (respuesta == "Exito") {
									console.log("Guardado Exitosamente");
									swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
									then((value) => {
									  obtenerRecursosTeoricos();
									  $("#formularioVideo input").val('');
									  $('#agregarVideoModal').modal('hide');
									  


									});
								}
							}
						});
					}else{
						swal ( "Video inválido" ,  "¡Te recordamos que el peso no debe exceder los 200MB!" ,  "error" )
					}
					
				}else{
					swal ( "Video inválido" ,  "¡Te recordamos que el formato aceptado es MP4!" ,  "error" )
				}

			}

		}

		
	});


	
</script>




<script>
	//WIKI

	var editorWiki = new Jodit("#editorWiki", {
        
        "language": "es",
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });

    var editorWikiEdicion = new Jodit("#editorWikiEdicion", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });

	//FORMULARIO DE CREACION DE WIKI
	//CODIGO PARA AGREGAR WIKI NUEVO ABRIENDO MODAL
	$('#agregarWiki').on('click', function(event) {
		event.preventDefault();
		console.log("wiki");
		$('#agregarWikiModal').modal('show');

		setTimeout( function(){
            $( '#tituloWiki' ).focus();
        }, 1500 );

		$('#agregarWikiFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarWikiFormulario').on('click', function(event) {
		event.preventDefault();


			
		var tituloWiki = $("#tituloWiki").val();
		var contenidoWiki = editorWiki.value;


		// console.log(contenidoWiki, tituloWiki);

		$.ajax({
			url: 'server/agregar_wiki.php?id_blo=<?php echo $id_blo; ?>',
			type: 'POST',
			data: {contenidoWiki, tituloWiki},


			success: function(respuesta){

				// console.log(respuesta);
				if (respuesta == "Exito") {
					console.log("Guardado Exitosamente");
					swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
						obtenerRecursosTeoricos();
						editorWiki.value = '';
						$( '#tituloWiki' ).val('');
						$('#agregarWikiModal').modal('hide');
					});
				}
			}	
		});
	});


	



	
</script>




<script>
  //ARCHIVO

  //CODIGO PARA AGREGAR ARCHIVO NUEVO ABRIENDO MODAL
 	$('#agregarArchivo').on('click', function(event) {
		event.preventDefault();
		console.log("archivo");
		$('#agregarArchivoModal').modal('show');

		$("#btn_formulario_archivo").removeAttr('disabled');
		
		$('#contenedor_barra_estado_archivo').html('<div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo"></div>');

		setTimeout( function(){
            $( '#nom_arc' ).focus();
        }, 1500 );
    
  	});


  $("#formularioArchivo").on("submit", function(event){
    	event.preventDefault();

    	$("#btn_formulario_archivo").attr( 'disabled', 'disabled' );



    if ($("#arc_arc")[0].files[0]) {

      var fileName = $("#arc_arc")[0].files[0].name;
      var fileSize = $("#arc_arc")[0].files[0].size;

      var ext = fileName.split('.').pop();

      
      if( ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf' || ext == 'xls' || ext == 'xlsx' ){
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
            url: 'server/agregar_archivo.php?id_blo=<?php echo $id_blo; ?>',
            type: 'POST',
            data: new FormData(formularioArchivo),
            processData: false,
            contentType: false,
            cache: false,
            success: function(respuesta){
            	// console.log(respuesta);
              if (respuesta == "Exito") {
                console.log("Guardado Exitosamente");
                swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {
                	
                	obtenerRecursosTeoricos();
                	$("#formularioArchivo input").val('');
					$('#agregarArchivoModal').modal('hide');

                });
              }
            }
          });
        }else{
          swal ( "Archivo inválido" ,  "¡Te recordamos que el peso no debe exceder los 50MB!" ,  "error" )
        }
        
      }else{
        swal ( "Archivo inválido" ,  "¡Te recordamos que los formatos aceptados son Word, PowerPoint, Excel y PDF!" ,  "error" )
      }

    }

    
  });


  $("#modalArchivo").draggable();
</script>



<script>
	//FORO


	//DESPLIEGUE DE MODAL
	$('#agregarForo').on('click', function(event) {
		event.preventDefault();
		console.log("foro");
		$('#agregarForoModal').modal('show');
		$('#agregarForoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR

		setTimeout( function(){
			$( '#tituloForo' ).focus();
		}, 1000 );
		

		$("#agregarForoFormulario").removeAttr( 'disabled' );
	});


	var des_for = new Jodit("#des_for", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });



	


	//AGREGADO DE FORO
	$('#agregarForoFormulario').on('click', function(event) {
	    event.preventDefault();


	    $("#agregarForoFormulario").attr('disabled','disabled');
	      
	    var tituloForo = $("#tituloForo").val();
	    var pun_for = $( '#pun_for' ).val();
	    var ini_for = $( '#ini_for' ).val();
	    var fin_for =$( '#fin_for' ).val();
	    var descripcionForo = des_for.value;
	    var id_sub_hor = '<?php echo $_GET['id_sub_hor']; ?>';




	    // console.log(tituloForo);

	    $.ajax({
	      url: 'server/agregar_foro.php?id_blo=<?php echo $id_blo; ?>',
	      type: 'POST',
	      data: { tituloForo, pun_for, ini_for, fin_for, descripcionForo, id_sub_hor },
	      success: function( respuesta ){

	        // console.log(respuesta);
	        if (respuesta == "Exito") {
	          console.log("Guardado Exitosamente");
	          swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
	          then((value) => {
	            
	          	obtenerActividades();
				des_for.value = '';
				$( '#tituloForo' ).val( '' );

				// $( '#pun_for' ).val( '' );
				// $( '#ini_for' ).val( '' );
				// $( '#fin_for' ).val( '' );
				$( '#agregarForoModal' ).modal('hide');

	          });
	        }
	      } 
	    });
  	});
</script>



<script>
	//ENTREGABLE


	//DESPLIEGUE DE MODAL
	$('#agregarEntregable').on('click', function(event) {
		event.preventDefault();
		console.log("foro");
		$('#agregarEntregableModal').modal('show');
		$('#agregarEntregableFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR

		setTimeout( function(){
			$( '#tituloEntregable' ).focus();
		}, 1000 );
		

		$("#agregarEntregableFormulario").removeAttr( 'disabled' );
	});


	var des_ent = new Jodit("#des_ent", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
		    "insertImageAsBase64URI": true
		}

    });



	


	//AGREGADO DE ENTREGABLE
	$('#agregarEntregableFormulario').on('click', function(event) {
	    event.preventDefault();


	    $("#agregarEntregableFormulario").attr('disabled','disabled');
	      
	    var tituloEntregable = $("#tituloEntregable").val();
	    var pun_ent = $( '#pun_ent' ).val();
	    var ini_ent = $( '#ini_ent' ).val();
	    var fin_ent =$( '#fin_ent' ).val();
	    var descripcionEntregable = des_ent.value;
	    var id_sub_hor = '<?php echo $_GET['id_sub_hor']; ?>';




	    console.log(tituloEntregable);

	    $.ajax({
	      url: 'server/agregar_entregable.php?id_blo=<?php echo $id_blo; ?>',
	      type: 'POST',
	      data: { tituloEntregable, pun_ent, ini_ent, fin_ent, descripcionEntregable, id_sub_hor },
	      success: function( respuesta ){

	        // console.log(respuesta);
	        if (respuesta == "Exito") {
	          console.log("Guardado Exitosamente");
	          swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
	          then((value) => {
	            
	          	obtenerActividades();
				des_ent.value = '';
				$( '#tituloEntregable' ).val( '' );

				// $( '#pun_ent' ).val( '' );
				// $( '#ini_ent' ).val( '' );
				// $( '#fin_ent' ).val( '' );
				$( '#agregarEntregableModal' ).modal('hide');

	          });
	        }
	      } 
	    });
  	});
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


		var tipo_actividad = getParameterByName('tipo_actividad'); // "lorem"
		var identificador_copia = getParameterByName('identificador_copia');
		var titulo_actividad = getParameterByName('titulo_actividad');

		if ( ( identificador_copia != undefined )  && ( tipo_actividad != undefined ) ) {

			$.ajax({
				url: 'server/obtener_actividades.php',
				type: 'POST',
				data: { id_blo, id_sub_hor, tipo_actividad, identificador_copia, titulo_actividad },
				success: function( respuesta ){

					$( '#contenedor_actividades' ).html( respuesta );

				}
			});
		} else {

			$.ajax({
				url: 'server/obtener_actividades.php',
				type: 'POST',
				data: { id_blo, id_sub_hor },
				success: function( respuesta ){

					$( '#contenedor_actividades' ).html( respuesta );

				}
			});

		}
		
		
	}


	function obtenerNotificacionesActividadesMateria( id_sub_hor ){

		for( var i = 0; i < $( '.claseHijoMateria' ).length; i++ ){

            if ( $( '.claseHijoMateria' ).eq( i ).attr( 'id_sub_hor' ) == id_sub_hor ) {

                $.ajax({
                    ajaxContador: i,
                    url: 'server/obtener_total_notificaciones_grupo.php',
                    type: 'POST',
                    data: { id_sub_hor },
                    success: function( respuesta ){

                        $( '.claseHijoMateria' ).eq( this.ajaxContador ).html( respuesta );
                        $( '.claseHijoClase' ).eq( this.ajaxContador ).html( respuesta );

                    }
                });

            }

        }

	}


	function obtenerNotificacionesActividadesNavbar(){
		$.ajax({
            url: 'server/obtener_notificaciones_actividades.php',
            type: 'POST',
            success: function( respuesta ){
                $( '#contenedor_notificaciones_actividades' ).html( respuesta );
            }
        });
	}


	// alert(  );

	function getParameterByName(name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, '\\$&');
	    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, ' '));
	}



	function removeParam(parameter)
	{
	  var url=document.location.href;
	  var urlparts= url.split('?');

	 if (urlparts.length>=2)
	 {
	  var urlBase=urlparts.shift(); 
	  var queryString=urlparts.join("?"); 

	  var prefix = encodeURIComponent(parameter)+'=';
	  var pars = queryString.split(/[&;]/g);
	  for (var i= pars.length; i-->0;)               
	      if (pars[i].lastIndexOf(prefix, 0)!==-1)   
	          pars.splice(i, 1);
	  url = urlBase+'?'+pars.join('&');
	  window.history.pushState('',document.title,url); // added this line to push the new url directly to url bar .

	}
	return url;
	}



</script>