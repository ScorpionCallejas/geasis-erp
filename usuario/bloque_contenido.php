<?php  

	include('inc/header.php');
	$id_blo = $_GET['id_blo'];

	$sqlBloque = "
		SELECT * 
		FROM bloque 
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_blo = '$id_blo'";
	$resultadoBloque = mysqli_query($db, $sqlBloque);
	$filaBloque = mysqli_fetch_assoc($resultadoBloque);

	$nom_blo = $filaBloque['nom_blo'];
	$des_blo = $filaBloque['des_blo'];
	$con_blo = $filaBloque['con_blo'];	
	$id_mat6 = $filaBloque['id_mat6'];
	$nom_mat = $filaBloque['nom_mat'];
	$nom_ram = $filaBloque['nom_ram'];
	$id_mat = $filaBloque['id_mat'];
	$id_ram = $filaBloque['id_ram'];

?>


<style>
	

.video{
    position: fixed;
      left: -1%;
      bottom: 50%;
      z-index: 100;
}

.wiki{
    position: fixed;
      left: -1%;
      bottom: 57.5%;
      z-index: 100;
}


.archivo{
    position: fixed;
      left: -1%;
      bottom: 65%;
      z-index: 100;
}
.foro{
    position: fixed;
      right: -1%;
      bottom: 50%;
      z-index: 100;
}

.entregable{
    position: fixed;
      right: -1%;
      bottom: 57.5%;
      z-index: 100;
}
.examen{
    position: fixed;
      right: -1%;
      bottom: 65%;
      z-index: 100;
}
</style>



<!-- BOTON FLOTANTE VER DIAGRAMA-->
<a class="btn-floating btn-lg  flotante light-green accent-3" style="bottom: 120px; right: 24px;" id="cargarDiagrama">
	<i class="fas fa-stream fa-1x" title="Diagrama de Gantt (Carga de Actividades)" ></i>
</a>
<!-- FIN BOTON FLOTANTE VER DIAGRAMA-->

<!-- BOTON FLOTANTE AGREGAR CONTENIDO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 45px; right: 24px;" id="agregarContenido"><i class="fas fa-save fa-1x" title="Agregar Bloque" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONTENIDO-->


<!-- BOTON FLOTANTE AGREGAR VIDEO-->
<a class="btn btn-blue-grey  video" id="agregarVideo"><i class="fas fa-video fa-2x" title="Agregar Video" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR VIDEO-->

<!-- BOTON FLOTANTE AGREGAR WIKI-->
<a class="btn btn-light-green  wiki" id="agregarWiki"><i class="fab fa-wikipedia-w fa-2x" title="Agregar Wiki" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR WIKI-->


<!-- BOTON FLOTANTE AGREGAR ARCHIVO-->
<a class="btn btn-purple lighten-1 archivo" id="agregarArchivo"><i class="fas fa-file-alt fa-2x" title="Agregar Archivo"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR ARCHIVO-->





<!-- BOTON FLOTANTE AGREGAR ENTREGABLE-->
<a class="btn  green accent-4  entregable" id="agregarEntregable"><i class="fas fa-file fa-2x white-text" title="Agregar Entregable" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR ENTREGABLE-->

<!-- BOTON FLOTANTE AGREGAR EXAMEN-->
<a class="btn indigo lighten-2  examen" id="agregarExamen"><i class="fas fa-diagnoses fa-2x white-text" title="Agregar Examen"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR EXAMEN-->

<!-- BOTON FLOTANTE AGREGAR FORO-->
<a class="btn btn-light-blue  foro" id="agregarForo"><i class="fas fa-comment-dots fa-2x" title="Agregar Foro" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR FORO-->



<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Contenido de Bloque">
			<i class="fas fa-bookmark"></i> 
			Contenido de Bloque
		</span>
		<br>
		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="materias.php?id_ram=<?php echo $id_ram; ?>" title="Vuelve a Materias">Materias</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="bloques.php?id_mat=<?php echo $id_mat; ?>" title="Vuelave a Bloques">Bloques</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Contenido</a>
		</div>
		
	</div>

	<div class="col text-right">

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materias de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Bloques de <?php echo $nom_mat; ?>">
			<i class="fas fa-certificate"></i>
			Materia: <?php echo $nom_mat; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Contenido de Bloque de <?php echo $nom_blo; ?>">
			<i class="fas fa-certificate"></i>
			Bloque: <?php echo $nom_blo; ?>
		</span>

		
		
	</div>
	
</div>
<!-- FIN TITULO -->

<!-- CONTENEDOR DE DROPDOWNS -->
<div class="row">
	<div class="col"><i class="file-signature"></i>
		<div class="row badge badge cyan darken-2 hoverable">
				<h4><i class="fab fa-accusoft fa-2x"></i></h4>
			<br>
			<h4>Recursos Teóricos</h4>
			<br>



			<div class="row">

				<!--DROPDOWN ARCHIVO-->
				<div class="dropdown dropright">
				    <!--Trigger-->
				  <button class="btn btn-purple lighten-1 dropdown-toggle" type="button" id="dropdownMenu1-1" data-toggle="dropdown">
				    <i class="fas fa-file-alt fa-1x" title="Archivo"></i>
				  </button>

				  <!--Menu-->
				  <div class="dropdown-menu dropdown-default scrollspy-example z-depth-1 mt-4 btn-purple" id="buscadorArchivo">
				      <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search">

				      <?php  
				        $sqlArchivo = "SELECT * FROM archivo WHERE id_blo3 = '$id_blo'";
				        $resultadoArchivo = mysqli_query($db, $sqlArchivo);

				        while($filaArchivo = mysqli_fetch_assoc($resultadoArchivo)){
				      ?>    

				        <a class="dropdown-item">

				          <button class="btn btn-sm btn-danger eliminacionArchivo" eliminacionArchivo="<?php echo $filaArchivo['id_arc']; ?>" archivo="<?php echo $filaArchivo['nom_arc']; ?> " ><i class="fas fa-times"></i>
				          </button>
				      

					      <button class="btn btn-sm btn-warning recursoArchivo" arc_arc="<?php echo $filaArchivo['arc_arc']; ?>" des_arc="<?php echo $filaArchivo['des_arc']; ?>" nom_arc="<?php echo $filaArchivo['nom_arc']; ?>">
					        <i class="fas fa-eye"></i>
					      </button>
				          
				          <span>
				            <?php echo $filaArchivo["nom_arc"]; ?>
				          </span>
				        </a>

				    <?php  
				      }
				    ?>
				  </div>
				</div>
				<!--/DROPDOWN ARCHIVO-->



				<!--DROPDOWN WIKI-->
				<div class="dropdown dropright">
				  	<!--Trigger-->
					<button class="btn btn-light-green dropdown-toggle" type="button" id="dropdownMenu1-1" data-toggle="dropdown">
						<i class="fab fa-wikipedia-w fa-1x" title="Wiki"></i>
					</button>

					<!--Menu-->
					<div class="dropdown-menu dropdown-default scrollspy-example z-depth-1 mt-4 btn-light-green" id="buscadorWiki">
					    <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search">

					    <?php  
					    	$sqlWiki = "SELECT * FROM wiki WHERE id_blo2 = '$id_blo'";
					    	$resultadoWiki = mysqli_query($db, $sqlWiki);

					    	while($filaWiki = mysqli_fetch_assoc($resultadoWiki)){
					    ?>		

								<a class="dropdown-item  mdb-dropdownLink-1" href="#">
									
									<button class="btn btn-sm btn-primary edicion" edicion="<?php echo $filaWiki['id_wik']; ?>"><i class="fas fa-edit"></i>
									</button>

									<button class="btn btn-sm btn-danger eliminacion" eliminacion="<?php echo $filaWiki['id_wik']; ?>" wiki="<?php echo $filaWiki['nom_wik']; ?> "><i class="fas fa-times"></i>
									</button> 


									<button class="btn btn-sm btn-warning recursoWiki" id_wik="<?php echo $filaWiki['id_wik']; ?>">
									<i class="fas fa-eye"></i>
									
									</button>

									<span>
										<?php echo $filaWiki["nom_wik"]; ?>
									</span>
								</a>



						<?php  
							}
						?>
					</div>
				</div>
				<!--/DROPDOWN WIKI-->




				<!--DROPDOWN VIDEO-->
				<div class="dropdown dropright">
				    <!--Trigger-->
				  <button class="btn btn-blue-grey dropdown-toggle" type="button" id="dropdownMenu1-1" data-toggle="dropdown">
				    <i class="fas fa-video fa-1x" title="Video"></i>
				  </button>

				  <!--Menu-->
				  <div class="dropdown-menu dropdown-default scrollspy-example z-depth-1 mt-4 btn-blue-grey" id="buscadorVideo">
				      <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search">

				      <?php  
				        $sqlVideo = "SELECT * FROM video WHERE id_blo1 = '$id_blo'";
				        $resultadoVideo = mysqli_query($db, $sqlVideo);

				        while($filaVideo = mysqli_fetch_assoc($resultadoVideo)){
				      ?>    

				        <a class="dropdown-item  mdb-dropdownLink-1 ">

				          <button class="btn btn-sm btn-danger eliminacionVideo" eliminacionVideo="<?php echo $filaVideo['id_vid']; ?>" video="<?php echo $filaVideo['nom_vid']; ?> " ><i class="fas fa-times"></i>
				          </button> 
						  

						  <button class="btn btn-sm btn-warning recursoVideo" vid_vid="<?php echo $filaVideo['vid_vid']; ?>" des_vid="<?php echo $filaVideo['des_vid']; ?>" nom_vid="<?php echo $filaVideo['nom_vid']; ?>" url_vid="<?php echo $filaVideo['url_vid']; ?>">
						  	<i class="fas fa-eye"></i>
						  </button>
				          
				          <span>
				            <?php echo $filaVideo["nom_vid"]; ?>
				          </span>
				        </a>



				    <?php  
				      }
				    ?>
				  </div>
				</div>
				<!--/DROPDOWN VIDEO-->





			</div>
		</div>
	</div>
	
		

	<div class="col text-right">
		<div class="row badge badge-default hoverable">
			<h4><i class="fas fa-file-signature fa-2x"></i></h4>
      <br>
      <h4>Recursos Prácticos</h4>
      <br>


      <div class="row">

        <!--DROPDOWN FORO-->
        <div  class="dropdown dropleft">
            <!--Trigger-->
          <button class="btn btn-light-blue dropdown-toggle " type="button" id="dropdownMenu1-1" data-toggle="dropdown">
            <i class="fas fa-comment-dots fa-1x" title="Foro"></i>
          </button>

          <!--Menu-->
          <div class="dropdown-menu dropdown-default scrollspy-example z-depth-1 mt-4 btn-light-blue" id="buscadorForo">
              <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search">

              <?php  
                $sqlForo = "SELECT * FROM foro WHERE id_blo4 = '$id_blo'";
                $resultadoForo = mysqli_query($db, $sqlForo);

                while($filaForo = mysqli_fetch_assoc($resultadoForo)){
              ?>    

                <a class="dropdown-item  mdb-dropdownLink-1 ">

                  <button class="btn btn-sm btn-danger eliminacionForo" eliminacionForo="<?php echo $filaForo['id_for']; ?>" foro="<?php echo $filaForo['nom_for']; ?> " ><i class="fas fa-times"></i>
                  </button>

			      <button class="btn btn-sm btn-primary edicionForo" edicionForo="<?php echo $filaForo['id_for']; ?>">
			      	<i class="fas fa-edit"></i>
				  </button>


                  
                  <span>
                    <?php echo $filaForo["nom_for"]; ?>
                  </span>
                </a>

            <?php  
              }
            ?>
          </div>
        </div>
        <!--/DROPDOWN FORO-->


        <!--DROPDOWN ENTREGABLE-->
        <div  class="dropdown dropleft">
            <!--Trigger-->
          <button class="btn green accent-4 dropdown-toggle white-text" type="button" id="dropdownMenu1-1" data-toggle="dropdown">
            <i class="fas fa-file fa-1x" title="Entregable"></i>
          </button>

          <!--Menu-->
          <div class="dropdown-menu dropdown-default scrollspy-example z-depth-1 mt-4  green accent-4 white-text" id="buscadorEntregable">
              <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search">

              <?php  
                $sqlEntregable = "SELECT * FROM entregable WHERE id_blo5 = '$id_blo'";
                $resultadoEntregable = mysqli_query($db, $sqlEntregable);

                while($filaEntregable = mysqli_fetch_assoc($resultadoEntregable)){
              ?>    

                <a class="dropdown-item  mdb-dropdownLink-1 ">

                  <button class="btn btn-sm btn-danger eliminacionEntregable" eliminacionEntregable="<?php echo $filaEntregable['id_ent']; ?>" entregable="<?php echo $filaEntregable['nom_ent']; ?> " ><i class="fas fa-times"></i>
                  </button>

            <button class="btn btn-sm btn-primary edicionEntregable" edicionEntregable="<?php echo $filaEntregable['id_ent']; ?>">
              <i class="fas fa-edit"></i>
          </button>


         
                  
                  <span>
                    <?php echo $filaEntregable["nom_ent"]; ?>
                  </span>
                </a>

            <?php  
              }
            ?>
          </div>
        </div>
        <!--/DROPDOWN ENTREGABLE-->


        <!--DROPDOWN EXAMEN-->
        <div  class="dropdown dropleft">
            <!--Trigger-->
          <button class="btn indigo lighten-2 dropdown-toggle white-text" type="button" id="dropdownMenu1-1" data-toggle="dropdown">
            <i class="fas fa-diagnoses" title="Examen"></i>
          </button>

          <!--Menu-->
          <div class="dropdown-menu dropdown-default scrollspy-example z-depth-1 mt-4 indigo lighten-2 white-text" id="buscadorExamen">
              <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search">

              <?php  
                $sqlExamen = "SELECT * FROM examen WHERE id_blo6 = '$id_blo'";
                $resultadoExamen = mysqli_query($db, $sqlExamen);

                while($filaExamen = mysqli_fetch_assoc($resultadoExamen)){
              ?>    

                <a class="dropdown-item  mdb-dropdownLink-1 ">

                  <button class="btn btn-sm btn-danger eliminacionExamen" eliminacionExamen="<?php echo $filaExamen['id_exa']; ?>" examen="<?php echo $filaExamen['nom_exa']; ?> " ><i class="fas fa-times"></i>
                  </button>

					<button class="btn btn-sm btn-primary edicionExamen" edicionExamen="<?php echo $filaExamen['id_exa']; ?>">
					  <i class="fas fa-edit"></i>
					</button>



                  
                  <span>
                    <?php echo $filaExamen["nom_exa"]; ?>
                  </span>
                </a>

            <?php  
              }
            ?>
          </div>
        </div>
        <!--/DROPDOWN EXAMEN-->


      </div>
    </div>
		
	</div>
</div>



<!-- CONTENIDO -->
<div class="row">
	<div class="col-md-12">
		<br>
				<?php 
				if($con_blo!="")
				{
				?>

				<div id="box">
					<div id="editor">
						<?php echo $con_blo; ?>

	      
	        		</div>
					
				</div>

				<?php
				}
				else
				{
				?>	


			<div id="box">
				<div id="editor">
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
					<br>

      
        		</div>
				
			</div>
		

		
	</div>

</div>
<?php
}
?>

<!-- ROW TABLA -->

<!-- WIKI -->
<!-- CONTENIDO MODAL AGREGAR WIKI -->
<div class="modal fade text-left " id="agregarWikiModal">
  <div class="modal-dialog modal-lg" role="document">
    
	<form >
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 font-weight-bold">
	        	<i class="fab fa-wikipedia-w fa-2x grey-text" title="Agregar Wiki" id="agregarWiki"></i>
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      	<div class="md-form mb-5">


	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="tituloWiki" class="form-control validate">
	          <label  for="tituloWiki">Asigna un título</label>
	        </div>

	


	         
	          <div id="boxWiki">
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
					<br>
        		</div>
				
			</div>


	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" id="agregarWikiFormulario">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR WIKI -->

<!-- MODAL EDICION WIKI -->

<!-- CONTENIDO MODAL EDITAR WIKI -->
<div class="modal fade text-left " id="editarWikiModal">
  <div class="modal-dialog modal-lg" role="document">
    
	<form >
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 font-weight-bold">
	        	<i class="fab fa-wikipedia-w fa-2x grey-text" title="Agregar Wiki"></i>
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      	<div class="md-form mb-5">


	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="tituloWikiEdicion" class="form-control validate">
	          <label  for="tituloWikiEdicion" id="labelEdicion">Edita el título</label>
	        </div>



	


	         
	          <div id="boxWikiEdicion">
				<div id="editorWikiEdicion">

					
        		</div>
				
			</div>


	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" id="editarWikiFormulario">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
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
	        
	        <h4 class="modal-title w-100 font-weight-bold">
	        	<i class="fab fa-wikipedia-w fa-2x grey-text" title="Agregar Wiki" ></i>
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      

	        <h3 id="tituloWikiVista"></h3>
	      

	


	         
			<div id="contenidoWikiVista">


			</div>


	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR WIKI -->

<!-- FIN VISTA DE WIKI -->
<!-- FIN WIKI -->



<!-- VIDEO -->
<!-- CONTENIDO MODAL AGREGAR VIDEO -->
<div class="modal fade text-left " id="agregarVideoModal">
  <div class="modal-dialog modal-lg" role="document">
    
	<form id="formularioVideo" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 font-weight-bold grey-text">
				
	        	<i class="fas fa-video fa-2x" title="Agregar Video"></i>
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      	<div class="md-form mb-5">
	      	  <i class="fas fa-info prefix grey-text"></i>
	      	  
	          <input type="text" id="nom_vid" name="nom_vid" class="form-control validate">
	          <label  for="nom_vid">Título del Video</label>
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
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR VIDEO <-->
<!-- FIN VIDEO  -->





<!-- VISTA VIDEO -->
<!-- Button trigger modal -->


<!-- Modal -->
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
<!-- Modal -->
<!-- FIN  VISTA VIDEO -->


<!-- FIN VIDEO  -->





<!-- CONTENIDO MODAL AGREGAR ARCHIVO Y VERLO-->
<!-- Button trigger modal -->


<!-- ARCHIVO -->
<!-- CONTENIDO MODAL AGREGAR ARCHIVO -->
<div class="modal fade text-left " id="agregarArchivoModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form id="formularioArchivo" enctype="multipart/form-data" method="POST">
      <div class="modal-content">
        <div class="modal-header text-center">
          
          <h4 class="modal-title w-100 font-weight-bold grey-text">
            <i class="fas fa-file-alt fa-2x" title="Agregar Archivo"></i>
          </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

          <div class="md-form mb-5">
            <i class="fas fa-info prefix grey-text"></i>
            
            <input type="text" id="nom_arc" name="nom_arc" class="form-control validate">
            <label  for="nom_arc">Título del Archivo</label>
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
            <input class="file-path validate" type="text" placeholder="Peso Máximo: 50MB - Formatos Válidos: Word, Power Point y PDF">
          </div>
        </div>
        </div>
      

      <div class="progress md-progress" style="height: 20px">
          <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo">
            
          
          </div>
      </div>
      

      <div id="file" class="text-center">
        
      </div>

        </div>

        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
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
<!-- FIN CONTENIDO MODAL AGREGAR ARCHIVO -->





<!-- FORO -->
<!-- CONTENIDO MODAL AGREGAR FORO -->
<div class="modal fade text-left " id="agregarForoModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form >
      <div class="modal-content">
        <div class="modal-header text-center">
          
          <h4 class="modal-title w-100 font-weight-bold">
            <i class="fas fa-comment-dots fa-2x grey-text" title="Agregar Foro"></i>
          </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

          <div class="md-form mb-5">


            <i class="fas fa-info prefix grey-text"></i>
            <input type="text" id="tituloForo" class="form-control validate">
            <label  for="tituloForo">Asigna un título al Foro</label>
          </div>


        </div>

        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-info" id="agregarForoFormulario">Crear <i class="fas fa-paper-plane-o ml-1"></i></button>
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
          
          <h4 class="modal-title w-100 font-weight-bold">
            <i class="fas fa-file fa-2x grey-text" title="Agregar Entregable"></i>
          </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

          <div class="md-form mb-5">


            <i class="fas fa-info prefix grey-text"></i>
            <input type="text" id="tituloEntregable" class="form-control validate">
            <label  for="tituloEntregable">Asigna un título al Entregable</label>
          </div>


        </div>

        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-info" id="agregarEntregableFormulario">Crear <i class="fas fa-paper-plane-o ml-1"></i></button>
        </div>

      </div>
  </form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR ENTREGABLE -->

<!-- FIN ENTREGABLE -->




<!-- EXAMEN -->
<!-- CONTENIDO MODAL AGREGAR EXAMEN -->
<div class="modal fade text-left " id="agregarExamenModal">
  <div class="modal-dialog modal-lg" role="document">
    
  <form >
      <div class="modal-content">
        <div class="modal-header text-center">
          
          <h4 class="modal-title w-100 font-weight-bold">
            <i class="fas fa-diagnoses fa-2x grey-text" title="Agregar Examen"></i>
          </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

          <div class="md-form mb-5">


            <i class="fas fa-info prefix grey-text"></i>
            <input type="text" id="tituloExamen" class="form-control validate">
            <label  for="tituloExamen">Asigna un título al Examen</label>
          </div>


        </div>

        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-info" id="agregarExamenFormulario">Crear <i class="fas fa-paper-plane-o ml-1"></i></button>
        </div>

      </div>
  </form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR EXAMEN -->

<!-- FIN EXAMEN -->


<!-- DIAGRAMA -->
<!-- CONTENIDO MODAL AGREGAR DIAGRAMA -->
<div class="modal fade text-left " id="cargarDiagramaModal">
  <div class="modal-dialog modal-lg" role="document">
    
 
      <div class="modal-content">
        <div class="modal-header text-center">
          
          <h4 class="modal-title w-100 font-weight-bold">
          	<i class="fas fa-stream fa-1x fa-2x grey-text" title="Diagrama de Gantt (Carga de Actividades)" ></i>
          </h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3 text-center ">
          <!-- DIAGRAMA -->
          <?php
            
             $sqlDiagrama = "
             SELECT nom_for AS titulo, ini_for AS inicio, fin_for AS fin, tip_for AS tipo
              FROM `foro` 
              INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
              INNER JOIN materia ON materia.id_mat = bloque.id_mat6
              WHERE id_mat6 = '$id_mat6'
              UNION
              SELECT nom_ent AS titulo, ini_ent AS inicio, fin_ent AS fin, tip_ent AS tipo
              FROM `entregable` 
              INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
              INNER JOIN materia ON materia.id_mat = bloque.id_mat6
              WHERE id_mat6 = '$id_mat6'
              UNION
              SELECT nom_exa AS titulo, ini_exa AS inicio, fin_exa AS fin, tip_exa AS tipo
              FROM `examen` 
              INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
              INNER JOIN materia ON materia.id_mat = bloque.id_mat6
              WHERE id_mat6 = '$id_mat6'

             ";

              $resultadoDiagrama = mysqli_query($db, $sqlDiagrama);

              $totalFilas = mysqli_num_rows($resultadoDiagrama);

              //echo $totalFilas;



              $sqlMaximoEntero = "
              SELECT MAX(fin_for) AS maximo
              FROM foro 
              INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
              INNER JOIN materia ON materia.id_mat = bloque.id_mat6
              WHERE id_mat6 = '$id_mat6'
              UNION
              SELECT MAX(fin_ent) AS maximo
              FROM entregable
              INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
              INNER JOIN materia ON materia.id_mat = bloque.id_mat6
              WHERE id_mat6 = '$id_mat6'
              UNION
              SELECT MAX(fin_exa) AS maximo
              FROM examen
              INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
              INNER JOIN materia ON materia.id_mat = bloque.id_mat6
              WHERE id_mat6 = '$id_mat6'

              ";


              $resultadoMaximoEntero = mysqli_query($db, $sqlMaximoEntero);

              $maximoEntero = 0;


              while ($filaMaximoEntero = mysqli_fetch_assoc($resultadoMaximoEntero)) {
                if ($filaMaximoEntero['maximo'] > $maximoEntero) {
                  $maximoEntero = $filaMaximoEntero['maximo'];
                }
              }

              //echo $maximoEntero;

          ?>

          <!-- CONTENIDO -->


          <table class="table table-hover table-sm table-striped table-responsive">
            <thead class="teal darken-1 white-text text-center">
              
              <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Actividad</th>

                <?php

                  for ($i=1; $i <= $maximoEntero; $i++) { 
                ?>
                  <th>
                    <?php echo $i; ?>
                  </th>
                <?php
                  }

                ?>
                
                
              </tr>
            </thead>

            <tbody>
              <?php
                $contador = 1;
                while($fila = mysqli_fetch_assoc($resultadoDiagrama)){
              ?>
                <tr>
                  <td class="teal darken-2 white-text">
                    <?php echo $contador; $contador++; ?>
                  </td>

                  <td class="teal darken-1 white-text">
                    <?php  
                      echo $fila['tipo'];
                    ?>
                  </td>
                  <td class="teal lighten-1 white-text">
                    <?php  
                      echo $fila['titulo'];
                    ?>
                  </td>

                  <?php  
                    for ($i=1; $i <= $maximoEntero; $i++) { 
                      if ($i>=$fila['inicio'] && $i<= $fila['fin']) {
                  ?>

                    <td class="light-green accent-3">
                    </td>

                  <?php
                      }else{
                  ?>
                    <td class="">
                    </td>
                  <?php
                      }
                    }

                  ?>
                  


                </tr>


              <?php        
                }

              ?>

              
            </tbody>
          </table>

          <!-- FIN DIAGRAMA -->

          


        </div>

        <div class="modal-footer d-flex justify-content-center">
        
        </div>

      </div>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR DIAGRAMA -->

<!-- FIN DIAGRAMA -->










<br>
<!-- FIN CONTENIDO -->
<?php  

	include('inc/footer.php');

?>


<script>

		//INICIALIZACION DE EDITORES
        var editor = new Jodit("#editor", {
            "language": "es",
            toolbarStickyOffset: 50

        });


        var editorWiki = new Jodit("#editorWiki", {
            "language": "es",
            toolbarStickyOffset: 50

        });


        var editorWikiEdicion = new Jodit("#editorWikiEdicion", {
            "language": "es",
            toolbarStickyOffset: 50

        });
</script>



<script>

	//FUNCION PARA EXPORTAR A WORD, SIN DEPENDENCIAS
	function exportHTML(){
       var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
            "xmlns='http://www.w3.org/TR/REC-html40'>";
       
       var sourceHTML = header+editor.value;
       
       var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
       var fileDownload = document.createElement("a");
       document.body.appendChild(fileDownload);
       fileDownload.href = source;
       fileDownload.download = 'document.doc';
       fileDownload.click();
       document.body.removeChild(fileDownload);
    }

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
										
					console.log("Guardado Exitosamente");
				}
			}	
		});
		



	});
	
</script>


<script>
	
	//FORMULARIO DE CREACION DE WIKI
	//CODIGO PARA AGREGAR WIKI NUEVO ABRIENDO MODAL
	$('#agregarWiki').on('click', function(event) {
		event.preventDefault();
		console.log("wiki");
		$('#agregarWikiModal').modal('show');
		$('#agregarWikiFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
	});


	$('#agregarWikiFormulario').on('click', function(event) {
		event.preventDefault();


			
		var tituloWiki = $("#tituloWiki").val();
		var contenidoWiki = editorWiki.value;


		console.log(contenidoWiki, tituloWiki);

		$.ajax({
			url: 'server/agregar_wiki.php?id_blo=<?php echo $id_blo; ?>',
			type: 'POST',
			data: {contenidoWiki, tituloWiki},


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
	});


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
							console.log(respuesta);

							if (respuesta == 'Exito') {
								swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
								then((value) => {
								  window.location.reload();
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
						  window.location.reload();
						});
					}else{
						console.log(respuesta);

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
			    $("#tituloWikiVista").text(datos.nom_wik);
			    $("#contenidoWikiVista").html(datos.des_wik);

				

			}
		});	    
	    
	  });



	
</script>


<script>
	//INICIALIZACION DE SELECTS
	$('.mdb-select').materialSelect();
</script>

<script>
	//BUSCADOR DE LOS DROPDOWN
	$('#buscadorWiki').mdbDropSearch();

	$("#buscadorVideo").mdbDropSearch();

	$("#buscadorArchivo").mdbDropSearch();

	$("#buscadorForo").mdbDropSearch();

	$("#buscadorEntregable").mdbDropSearch();

	$("#buscadorExamen").mdbDropSearch();
</script>


<script>
	//VIDEO

	//CODIGO PARA AGREGAR VIDEO NUEVO ABRIENDO MODAL
	$('#agregarVideo').on('click', function(event) {
		event.preventDefault();
		//console.log("video");
		$('#agregarVideoModal').modal('show');
		if($('#select_fuente').val() == 'youtube'){
			
			$('#barra_video').css('display','none');
			$("#opcion_select").html(
				'<i class="fab fa-youtube prefix grey-text"></i>'+
				'<input type="text" id="url_vid" name="url_vid" class="form-control validate">'+
				'<label  for="url_vid">URL del Video </label>'
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
				      	'<input class="file-path validate" type="text" placeholder="Peso Máximo: 200MB - Formato Válido: MP4">'+
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
					'<label  for="url_vid">URL del Video </label>'
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
					      	'<input class="file-path validate" type="text" placeholder="Peso Máximo: 200MB - Formato Válido: MP4">'+
					    '</div>'+
					'</div>'
				);
			}
		});
		
	});

	$("#formularioVideo").on("submit", function(event){
		event.preventDefault();


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
						  window.location.reload();
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
									  window.location.reload();
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
						  window.location.reload();
						});
					}else{
						console.log(respuesta);

					}

				}
			});
		    
		  }
		});
	});
	

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



</script>



<script>
  //ARCHIVO

  //CODIGO PARA AGREGAR ARCHIVO NUEVO ABRIENDO MODAL
  $('#agregarArchivo').on('click', function(event) {
    event.preventDefault();
    console.log("archivo");
    $('#agregarArchivoModal').modal('show');
    
  });

  $("#formularioArchivo").on("submit", function(event){
    event.preventDefault();


    if ($("#arc_arc")[0].files[0]) {

      var fileName = $("#arc_arc")[0].files[0].name;
      var fileSize = $("#arc_arc")[0].files[0].size;

      var ext = fileName.split('.').pop();

      
      if(ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf'){
        if (fileSize < 50000000) {
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
              window.location.reload();
            });
          }else{
            console.log(respuesta);

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

  $("#limpiarArchivos").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    $("#contenidoModalArchivo").html("");
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
	});



	//AGREGADO DE FORO
	$('#agregarForoFormulario').on('click', function(event) {
	    event.preventDefault();


	      
	    var tituloForo = $("#tituloForo").val();


	    console.log(tituloForo);

	    $.ajax({
	      url: 'server/agregar_foro.php?id_blo=<?php echo $id_blo; ?>',
	      type: 'POST',
	      data: {tituloForo},
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
  	});


  	// REDIRECCION  A bloque_foro.php con ID
  	$(".edicionForo").on('click', function(event) {
  		event.preventDefault();
  		/* Act on the event */
  		var id_for = $(this).attr("edicionForo");
  		window.open("foro_bloque.php?id_for="+id_for, '_blank');



  	});

  	//ELIMINACION DE FORO
  $('.eliminacionForo').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    var foro = $(this).attr("eliminacionForo");
    var nombreForo = $(this).attr("foro");

    // console.log(FORO);
 
    swal({
      title: "¿Deseas eliminar "+nombreForo+"?",
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
        url: 'server/eliminacion_foro.php',
        type: 'POST',
        data: {foro},
        success: function(respuesta){
          
          if (respuesta == "true") {
            console.log("Exito en consulta");
            swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
            then((value) => {
              window.location.reload();
            });
          }else{
            console.log(respuesta);

          }

        }
      });
        
      }
    });
  });





</script>





<script>
  //ENTREGABLE


  //DESPLIEGUE DE MODAL
  $('#agregarEntregable').on('click', function(event) {
    event.preventDefault();
    console.log("entregable");
    $('#agregarEntregableModal').modal('show');
    $('#agregarEntregableFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
  });



  //AGREGADO DE ENTREGABLE
  $('#agregarEntregableFormulario').on('click', function(event) {
      event.preventDefault();


        
      var tituloEntregable = $("#tituloEntregable").val();


      console.log(tituloEntregable);

      $.ajax({
        url: 'server/agregar_entregable.php?id_blo=<?php echo $id_blo; ?>',
        type: 'POST',
        data: {tituloEntregable},
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
    });


    // REDIRECCION  A bloque_entregable.php con ID
    $(".edicionEntregable").on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var id_ent = $(this).attr("edicionEntregable");
      window.open("entregable_bloque.php?id_ent="+id_ent, '_blank');



    });

    //ELIMINACION DE ENTREGABLE
  $('.eliminacionEntregable').on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    var entregable = $(this).attr("eliminacionEntregable");
	    var nombreEntregable = $(this).attr("entregable");

	    // console.log(ENTREGABLE);

		swal({
		      title: "¿Deseas eliminar "+nombreEntregable+"?",
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
		        url: 'server/eliminacion_entregable.php',
		        type: 'POST',
		        data: {entregable},
		        success: function(respuesta){
		          
		          if (respuesta == "true") {
		            console.log("Exito en consulta");
		            swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
		            then((value) => {
		              window.location.reload();
		            });
		          }else{
		            console.log(respuesta);

		          }

		        }
		      });
		        
		      }
	    });
	});




</script>



<script>
  //EXAMEN


  //DESPLIEGUE DE MODAL
  $('#agregarExamen').on('click', function(event) {
    event.preventDefault();
    console.log("examen");
    $('#agregarExamenModal').modal('show');
    $('#agregarExamenFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
  });



  //AGREGADO DE EXAMEN
  $('#agregarExamenFormulario').on('click', function(event) {
      event.preventDefault();


        
      var tituloExamen = $("#tituloExamen").val();


      console.log(tituloExamen);

      $.ajax({
        url: 'server/agregar_examen.php?id_blo=<?php echo $id_blo; ?>',
        type: 'POST',
        data: {tituloExamen},
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
    });


    // REDIRECCION  A bloque_examen.php con ID
    $(".edicionExamen").on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var id_exa = $(this).attr("edicionExamen");
      window.open("examen_bloque.php?id_exa="+id_exa, '_blank');



    });

    //ELIMINACION DE EXAMEN
  $('.eliminacionExamen').on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var examen = $(this).attr("eliminacionExamen");
      var nombreExamen = $(this).attr("examen");

      // console.log(EXAMEN);

    swal({
          title: "¿Deseas eliminar "+nombreExamen+"?",
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
            url: 'server/eliminacion_examen.php',
            type: 'POST',
            data: {examen},
            success: function(respuesta){
              
              if (respuesta == "true") {
                console.log("Exito en consulta");
                swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {
                  window.location.reload();
                });
              }else{
                console.log(respuesta);

              }

            }
          });
            
          }
      });
  });


  // DEMO DEL EXAMEN
    $(".recursoExamen").on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var id_exa = $(this).attr("id_exa");
      window.open("demo_examen.php?id_exa="+id_exa, '_blank');
    });



</script>



<script>
	//DIAGRAMA DE GANTT

	$("#cargarDiagrama").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$('#cargarDiagramaModal').modal('show');
	});


</script>