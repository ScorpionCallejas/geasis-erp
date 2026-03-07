<?php  

	include('inc/header.php');
	$id_exa = $_GET['id_exa'];

	//CONSULTA DE FORO
	$sqlExamen = "
		SELECT * 
		FROM examen
		INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
	    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
	    INNER JOIN rama ON rama.id_ram = materia.id_ram2  
		WHERE id_exa = '$id_exa'
	";

	//echo $sqlExamen;
	

	$resultadoExamen = mysqli_query($db, $sqlExamen);
	$filaExamen = mysqli_fetch_assoc($resultadoExamen);

	$nom_exa = $filaExamen['nom_exa'];
	$des_exa = $filaExamen['des_exa'];
	$pun_exa = $filaExamen['pun_exa'];
	$ini_exa = $filaExamen['ini_exa'];
	$fin_exa = $filaExamen['fin_exa'];
	$dur_exa = $filaExamen['dur_exa'];
	$id_blo = $filaExamen['id_blo6'];

	$nom_blo = $filaExamen['nom_blo'];
	$nom_mat = $filaExamen['nom_mat'];
	$nom_ram = $filaExamen['nom_ram'];
	$id_mat = $filaExamen['id_mat'];
	$id_ram = $filaExamen['id_ram'];


	


	$sqlExamenPreguntas = "
		SELECT * 
		FROM examen
		INNER JOIN pregunta ON pregunta.id_exa2 = examen.id_exa
		WHERE id_exa = '$id_exa'
	";

	$resultadoValorExamen  = mysqli_query($db, $sqlExamenPreguntas);
	$valorExamen = 0;

	while($filaValorExamen = mysqli_fetch_assoc($resultadoValorExamen)){
		$valorExamen = $valorExamen + $filaValorExamen['pun_pre'];
	}


	$sqlUpdateValorExamen = "UPDATE examen SET pun_exa = '$valorExamen' WHERE id_exa = '$id_exa'";
	$resultadoUpdateValorExamen = mysqli_query($db, $sqlUpdateValorExamen);

	if (!$resultadoUpdateValorExamen) {
		echo "Error, verificar consulta";
	}


?>
<style>


.botonesPregunta {
  position: absolute;
  left: 10px;
  top: 10px;
}

.botonesPreguntaPadre {
  position: relative;
}


.botonesRespuesta {
  position: absolute;
  right: -10px;
  bottom: 10px;
}

.botonesRespuestaPadre {
  position: relative;
}
</style>


<!-- BOTON FLOTANTE AGREGAR CONTENIDO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 45px; right: 24px;" id="agregarExamen"><i class="fas fa-save fa-1x" title="Guardar" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONTENIDO-->

<!-- BOTON FLOTANTE AGREGAR PREGUNTA-->
<a class="btn btn-lg  flotante btn-info lighten-2" style="bottom: 125px; right: 24px;" id="agregarPregunta">
	Agregar pregunta
</a>
<!-- FIN BOTON FLOTANTE AGREGAR PREGUNTA-->

<!-- TITULO -->
<div class="row ">
  <div class="col text-left">
    
    <span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect edicionTitulo" nom_exa="<?php echo $nom_exa; ?>" title="Título">
      <i class="fas fa-bookmark"></i> 
      <?php echo $nom_exa; ?>
    </span>
    <br>
    <br>


    <div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
			<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="materias.php?id_ram=<?php echo $id_ram; ?>" title="Vuelve a Materias">Materias</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="bloques.php?id_mat=<?php echo $id_mat; ?>" title="Estás aquí">Bloques</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="bloque_contenido.php?id_blo=<?php echo $id_blo; ?>" title="Vuelve a Contenido">Contenido</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Examen</a>
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
    <br>
    <br>



    
    
  </div>
  
</div>
<!-- FIN TITULO -->

<!-- PARAMETROS EXAMEN -->
<div class="row">
	<div class="col-md-4">

	      	<div class="md-form mb-2">

				<i class="fas fa-award prefix grey-text"></i>
				<input type="number" id="pun_exa" min="1" step=".1" class="form-control validate disabled" value="<?php echo $valorExamen; ?>">
				<label  for="pun_exa">Tu puntaje lo definen las preguntas</label>
	        </div>
	</div>
	<div class="col-md-4">

	      	<div class="md-form mb-2">
	      		<i class="fas fa-minus-circle prefix grey-text"></i>
				<input type="number" id="ini_exa" min="1" step="1" class="form-control validate segmentaFecha" value="<?php echo $ini_exa; ?>">
				<label  for="ini_exa">Asigna un Inicio</label>
	        </div>
		
	</div>
	<div class="col-md-4">

	      	<div class="md-form mb-2">
				<i class="fas fa-plus-circle prefix grey-text"></i>
				<input type="number" id="fin_exa" min="1" step="1" class="form-control validate segmentaFecha" value="<?php echo $fin_exa; ?>">
				<label  for="fin_exa">Asigna un Fin</label>
	        </div>
		
	</div>
</div>

<!-- TIEMPO -->
<div class="row">
	<div class="col"></div>
	<div class="col">
		<div class="modal-body mx-3">

	      	<div class="md-form mb-2">
				<i class="fas fa-clock prefix grey-text"></i>
				<input type="number" id="dur_exa" min="1" step="1" class="form-control validate" value="<?php echo $dur_exa; ?>">
				<label  for="dur_exa">Duración para el examen</label>
	        </div>
		</div>
	</div>
	<div class="col"></div>
</div>
<!-- FIN TIEMPO -->


<!-- EJEMPLO -->
<div class="row">
  
  <div class="col-md-4">
      <h6>Inicio de ciclo ( ejemplo )</h6>
      <div class="md-form mb-2">

        <i class="fas fa-info prefix grey-text"></i>
        <input type="date" class="form-control validate letraPequena font-weight-normal segmentaFecha" value="<?php echo date( 'Y-m-d' ); ?>" id="segmentaFecha">
      </div>
    

  </div>
  
  <div class="col-md-4 text-center">
    <h6>Fecha de inicio tentativa</h6>
    <br>
    <p id="prevInicio" style="text-decoration: underline;"></p>
  </div>
  
  <div class="col-md-4 text-center">
    <h6>Fecha de fin tentativa</h6>
    <br>
    <p id="prevFin" style="text-decoration: underline;"></p>
  </div>

</div>
<!-- FIN EJEMPLO -->

<!-- FIN PARAMETROS EXAMEN -->



	<!-- CONTENIDO -->

	<!-- EDITOR -->
	<div class="row">
		<div class="col-md-12">

		
		<br>
				<?php 

				//VALIDACION DE QUE EXISTE CONTENIDO
					if($des_exa!="") {
				?>

				<div id="box">
					<div id="des_exa">
						<?php echo $des_exa; ?>

	      
	        		</div>
					
				</div>

				<?php
					} else {
				?>	


				<div id="box">
					<div id="des_exa">
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
				<?php
					}
				?>



<!-- FIN EDITOR -->



<br>

<!-- EXAMEN -->
	<!-- MODAL PREGUNTA -->
	<div class="modal fade text-left " id="agregarPreguntaModal">
	  <div class="modal-dialog modal-lg" role="document">
	    
	  <form >
	      <div class="modal-content">
	        <div class="modal-header text-center">
	          
	          <h4 class="modal-title w-100 font-weight-bold">
	             Agrega tu pregunta
	          </h4>

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>
	        <div class="modal-body mx-3">

	          <div class="md-form mb-5">

	            <i class="fas fa-question-circle prefix grey-text"></i>
	            <input type="text" id="pregunta" class="form-control validate">
	            
	          </div>


	          <div class="md-form mb-5">
	            <i class="fas fa-sort-numeric-up prefix grey-text"></i>
	            <input type="number" id="puntaje" min="0" step=".1" class="form-control validate">
	            <label  for="form34">Agrega un puntaje</label>
	          </div>


	        </div>

	        <div class="modal-footer d-flex justify-content-center">
	          <button class="btn btn-info" id="agregarPreguntaFormulario">Crear <i class="fas fa-paper-plane-o ml-1"></i></button>
	        </div>

	      </div>
	  </form>

	  </div>
	</div>
<!-- FIN MODAL PREGUNTA -->


<!-- PREGUNTAS -->

<div class="row text-center">
	<div class="col-md-12 text-center">
		<?php  
			$sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa'";
			$resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
			$i = 1;
			while($filaPreguntas = mysqli_fetch_assoc($resultadoPreguntas)){
		?>
			<!-- Jumbotron -->
			<div class="jumbotron text-center mdb-color blue-grey lighten-1 white-text mx-2 mb-5 botonesPreguntaPadre hoverable">
				<div class="botonesPregunta">
					<a href="#" class="btn-floating btn-info btn-sm pregunta" pregunta="<?php echo $filaPreguntas['id_pre']; ?> ">
						<i class="fas fa-plus-circle fa-2x" title="Agregar Respuesta"></i>
					</a>

					<a href="#" class="btn-floating btn-danger btn-sm eliminacionPregunta" title="Eliminar esta pregunta" eliminacionPregunta="<?php echo $filaPreguntas['id_pre']; ?>">
						<i class="fas fa-times-circle fa-2x"></i>
					</a>
				</div>
				


			  <!-- Title -->
			<div>
				<?php echo $i.".- ".$filaPreguntas['pre_pre']; $i++;?>
				
				
			</div>
			  

			  <!-- Grid row -->
			  <div class="row d-flex justify-content-center">


			    <!-- Grid column -->
			    <div class="col-xl-7 pb-2">

			      <p class="card-text text-warning">
			      	Valor del reactivo: <?php echo $filaPreguntas['pun_pre']; ?> puntos
			      </p>

			    </div>
			    <!-- Grid column -->

			  </div>
			  <!-- Grid row -->

			  <hr class="my-4 rgba-white-light">
			  

			  <!-- SECCION DE RESPUESTAS -->
			  <div class="pt-2">
			  	<?php
			  		$id_pre = $filaPreguntas['id_pre'];
			  		$sqlRespuestas = "SELECT * FROM respuesta WHERE id_pre1 = '$id_pre'";
			  		$resultadoRespuesta = mysqli_query($db, $sqlRespuestas);
			  		
			  		while($filaRespuestas = mysqli_fetch_assoc($resultadoRespuesta)){
			  	?>
			  		<div class="form-check form-check-inline botonesRespuestaPadre">
			  			<div class="botonesRespuesta">

						  <a href="#" class="eliminacionRespuesta" title="Eliminar esta respuesta" eliminacionRespuesta="<?php echo $filaRespuestas['id_res']; ?>">

						    <i class="fas fa-times fa-1x red-text"></i>
						  </a>
						</div>

					  <input type="radio" class="form-check-input" id="respuesta<?php echo $filaPreguntas['id_pre'].$filaRespuestas['id_res'];?>" name="respuesta<?php echo $filaPreguntas['id_pre'].$filaRespiestas['id_res'];?>">
					  <label class="form-check-label" for="respuesta<?php echo $filaPreguntas['id_pre'].$filaRespuestas['id_res'];?>">
					  	<?php 
							echo $filaRespuestas['res_res']." (".$filaRespuestas['val_res'].")"; 
						?> 
					  </label>
					</div>


			  	<?php
			  			

			  		}

			  	?>
			   <!-- **************************************************************************************************************************************************************************************************************************************************************************RESPUESTAS**************************************************************************************************************************************************** -->
			  </div>
			  <!-- FIN SECCION DE RESPUESTAS -->

			</div>
			<!-- Jumbotron -->


		<?php

			}

		?>
		
	</div>
	


	<!-- RESPUESTA -->
	<!-- CONTENIDO MODAL AGREGAR RESPUESTA -->
	<div class="modal fade text-left " id="agregarRespuestaModal">
	  <div class="modal-dialog modal-md" role="document">
	    
	  <form >
	      <div class="modal-content">
	        <div class="modal-header text-center">
	          <i class="far fa-check-circle fa-2x grey-text" title="Agregar Respuesta"></i>
	          <h4 class="modal-title w-100 font-weight-bold white-text" id="preguntaDescripcion">

	            
	          </h4>

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>
	        <div class="modal-body mx-3">

	          <div class="md-form mb-5">


	            <i class="fas fa-info prefix grey-text"></i>
	            <input type="text" id="respuesta" class="form-control validate">
	          </div>

	          

	          <div class="md-form mb-3">
	          		<i class="fas fa-asterisk prefix grey-text"></i>
					<label  for="form34">Define su valor (Verdadero/Falso)</label>
					<br>
					<!-- Group of material radios - option 1 -->
					<select class="mdb-select md-form colorful-select dropdown-primary" id="valor">
					  <option value="Verdadero">Verdadero</option>
					  <option value="Falso">Falso</option>
					</select>
	            
	          </div>


	        </div>

	        <div class="modal-footer d-flex justify-content-center">
	          <button class="btn btn-info" id="agregarRespuestaFormulario">Agregar <i class="fas fa-paper-plane-o ml-1"></i></button>
	        </div>

	      </div>
	  </form>

	  </div>
	</div>
	<!-- FIN CONTENIDO MODAL AGREGAR RESPUESTA -->

	<!-- FIN RESPUESTA -->

	


	
</div>
<!-- PREGUNTAS -->

<!-- FIN EXAMEN -->

<!-- FIN CONTENIDO -->


	<!-- MODAL CLASES EDICION CLASE -->
    <div class="modal fade" id="modal_clase_edicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info" role="document">
         <!--Content-->
         <div class="modal-content">
           <!--Header-->
            <div class="modal-header">
                <p class="heading lead" >Editar título</p>

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
                        
                        <input type="text" id="nom_exa" class="form-control validate" name="nom_exa" required="">
                        <label for="nom_exa">Título de la clase</label>
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
<?php  

	include('inc/footer.php');

?>

<script>
	//INICIALIZACION DE SELECTS
	$('.mdb-select').materialSelect();
</script>


<script>

	//INICIALIZACION DE EDITORES
    var des_exa = new Jodit("#des_exa", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
			"insertImageAsBase64URI": true
		}        

    });



    //INICIALIZACION DE EDITORES
    var pregunta = new Jodit("#pregunta", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
			"insertImageAsBase64URI": true
		}

    });


    //INICIALIZACION DE EDITORES
    var respuesta = new Jodit("#respuesta", {
        "language": "es",
        toolbarStickyOffset: 50,
        "uploader": {
			"insertImageAsBase64URI": true
		}

    });

    // DATOS DE EXAMEN, INICIO, FIN, DURACION Y CONTENIDO

    $("#agregarExamen").on('click', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	var ini_exa = parseInt($("#ini_exa").val());
    	var fin_exa = parseInt($("#fin_exa").val());
    	var dur_exa = parseInt($("#dur_exa").val());
    	//console.log(isNaN(ini_exa));

    	var contenido = des_exa.value;

    	console.log(ini_exa, fin_exa);


    	if ((ini_exa!="") && (fin_exa!="")) {
    		if (ini_exa <= fin_exa) {
    			$.ajax({
					url: 'server/editar_examen.php?id_exa=<?php echo $id_exa; ?>',
					type: 'POST',
					data: {ini_exa, fin_exa, dur_exa, contenido},

					success: function(respuesta){

						console.log(respuesta);
						if (respuesta == "Exito") {
							swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",})
							
							console.log("Guardado Exitosamente");
						}
					}
				});
    		}else{
    			swal ( "Datos Incorrectos" ,  "¡Te recordamos que el inicio debe ser menor o igual al fin!" ,  "error" )
    		}
    	}else{
    		console.log("test activo");
    		$.ajax({
				url: 'server/editar_examen.php?id_exa=<?php echo $id_exa; ?>',
				type: 'POST',
				data: {ini_exa, fin_exa, dur_exa, contenido},

				success: function(respuesta){

					console.log(respuesta);
					if (respuesta == "Exito") {
						swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",})
						
						console.log("Guardado Exitosamente");
					}
				}	
			});
    	}

    	

    });

    //DESPLIEGUE DE MODAL
  $('#agregarPregunta').on('click', function(event) {
    event.preventDefault();
    console.log("pregunta nueva");
    $('#agregarPreguntaModal').modal('show');
    //$('#agregarExamenFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
  });


  //AGREGADO DE PREGUNTA
  $('#agregarPreguntaFormulario').on('click', function(event) {
      event.preventDefault();


        
      var pregunta = $("#pregunta").val();
      var puntaje = $("#puntaje").val();


      //console.log(pregunta);

      $.ajax({
        url: 'server/agregar_pregunta.php?id_exa=<?php echo $id_exa; ?>',
        type: 'POST',
        data: {pregunta, puntaje},
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


  //ELIMINACION DE PREGUNTA
  $('.eliminacionPregunta').on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var pregunta = $(this).attr("eliminacionPregunta");

      // console.log(PREGUNTA);

    swal({
          title: "¿Deseas eliminar esta pregunta?",
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
            url: 'server/eliminacion_pregunta.php',
            type: 'POST',
            data: {pregunta},
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




  // RESPUESTA
  $(".pregunta").on('click', function(event) {
  	event.preventDefault();
  	/* Act on the event */
  	$('#agregarRespuestaModal').modal('show');
  	$("#preguntaDescripcion").text("Agrega una respuesta a esta pregunta");

  	console.log($(this).attr("pregunta"));
  	var pregunta = $(this).attr("pregunta");

  $('#agregarRespuestaFormulario').on('click', function(event) {
      event.preventDefault();


        
      var respuesta = $("#respuesta").val();
      var valor = $("#valor").val();


      console.log(respuesta, valor, pregunta);

		$.ajax({
			url: 'server/agregar_respuesta.php',
			type: 'POST',
			data: {pregunta, valor, respuesta},
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

  });


  //ELIMINACION DE RESPUESTA
  $('.eliminacionRespuesta').on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var respuesta = $(this).attr("eliminacionRespuesta");
      // console.log(RESPUESTA);

    swal({
          title: "¿Deseas eliminar esta respuesta?",
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
            url: 'server/eliminacion_respuesta.php',
            type: 'POST',
            data: {respuesta},
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
  $( ".segmentaFecha" ).on( 'change', function( event ) {
    event.preventDefault();
    /* Act on the event */

    var ini_exa = parseInt( $( '#ini_exa' ).val() ) + 1;
    var fin_exa = parseInt( $( '#fin_exa' ).val() ) + 1;
    // alert(ini_exa);
    // var ini_exa = $( '#ini_exa' ).val();

    var fechaPrev1 = new Date( $( "#segmentaFecha" ).val() );

    var prevInicio = new Date( fechaPrev1.setDate( fechaPrev1.getDate() + ini_exa ) );
    $( '#prevInicio' ).html( moment( prevInicio ).format( "DD/MM/YYYY" ) );

    var fechaPrev2 = new Date( $( "#segmentaFecha" ).val() );
    var prevFin = new Date( fechaPrev2.setDate( fechaPrev2.getDate() + fin_exa ) );
    $( '#prevFin' ).html( moment( prevFin ).format( "DD/MM/YYYY" ) );

    // moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' )

  });
</script>


<script>


  $( '.edicionTitulo' ).on('click', function(event) {
    event.preventDefault();
    /* Act on the event */


    var nom_exa = $( this ).attr( 'nom_exa' );

    $( '#modal_clase_edicion' ).modal( 'show' );

    setTimeout( function(){
      $( '#nom_exa' ).focus();
    }, 500 );

    $( '#nom_exa' ).val( nom_exa );

  });

  $( '#formularioClaseEdicion' ).on('submit', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('click en submit');
        var formularioClaseEdicion = new FormData( $('#formularioClaseEdicion')[0] );
        formularioClaseEdicion.append( 'id_exa', '<?php echo $id_exa ?>' );

        $.ajax({

            url: 'server/editar_examen.php?id_exa=<?php echo $id_exa; ?>',
            type: 'POST',
            data: formularioClaseEdicion, 
            processData: false,
            contentType: false,
            cache: false,
            success: function( respuesta ){
            
                console.log(respuesta);

                if ( respuesta == 'Exito' ) {

                    swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {

                      $( '.edicionTitulo' ).eq( 0 ).html( '<i class="fas fa-bookmark"></i> ' + $( '#nom_exa' ).val() ).removeAttr( 'nom_exa' ).attr( 'nom_exa', $( '#nom_exa' ).val() );

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