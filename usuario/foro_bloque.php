<?php  

	include('inc/header.php');
	$id_for = $_GET['id_for'];

	//CONSULTA DE FORO
	$sqlForo = "
		SELECT * 
		FROM foro 
		INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_for = '$id_for'";
	$resultadoForo = mysqli_query($db, $sqlForo);
	$filaForo = mysqli_fetch_assoc($resultadoForo);

	$nom_for = $filaForo['nom_for'];
	$des_for = $filaForo['des_for'];
	$pun_for = $filaForo['pun_for'];
	$ini_for = $filaForo['ini_for'];
	$fin_for = $filaForo['fin_for'];
	$id_blo = $filaForo['id_blo4'];
	$nom_blo = $filaForo['nom_blo'];
	$nom_mat = $filaForo['nom_mat'];
	$nom_ram = $filaForo['nom_ram'];
	$id_mat = $filaForo['id_mat'];
	$id_ram = $filaForo['id_ram'];



?>

<!-- BOTON FLOTANTE AGREGAR CONTENIDO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 45px; right: 24px;" id="agregarForo"><i class="fas fa-save fa-1x" title="Guardar" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONTENIDO-->


<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		
		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect edicionTitulo" nom_for="<?php echo $nom_for; ?>" title="Título">
	      <i class="fas fa-bookmark"></i> 
	      <?php echo $nom_for; ?>
	    </span>
	    <br>
	    <br>

		<div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
				<a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
				<i class="fas fa-angle-double-right"></i>
				<a class="text-white" href="ramas.php" title="Vuelve a ramas">Programas</a>
				<i class="fas fa-angle-double-right"></i>
				<a class="text-white" href="materias.php?id_ram=<?php echo $id_ram; ?>" title="Vuelve a Materias">Materias</a>
				<i class="fas fa-angle-double-right"></i>
				<a class="text-white" href="bloques.php?id_mat=<?php echo $id_mat; ?>" title="Estás aquí">Bloques</a>
				<i class="fas fa-angle-double-right"></i>
				<a class="text-white" href="bloque_contenido.php?id_blo=<?php echo $id_blo; ?>" title="Vuelve a Contenido">Contenido</a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: black;" href="" title="Estás aquí">Foro</a>
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
		

		
		
	</div>
	
</div>
<!-- FIN TITULO -->


<div class="row">
  <div class="col-md-4">

          <div class="md-form mb-2">

        <i class="fas fa-award prefix grey-text"></i>
        <input type="number" id="pun_for" min="0" step=".1" class="form-control validate" value="<?php echo $pun_for; ?>">
        <label  for="pun_for">Asigna un puntaje</label>
          </div>
  </div>
  <div class="col-md-4">

          <div class="md-form mb-2">
            <i class="fas fa-minus-circle prefix grey-text"></i>
        <input type="number" id="ini_for" min="0" step="1" class="form-control validate segmentaFecha" value="<?php echo $ini_for; ?>">
        <label  for="ini_for">Asigna un Inicio</label>
          </div>
    
  </div>
  <div class="col-md-4">
          <div class="md-form mb-2">
        <i class="fas fa-plus-circle prefix grey-text"></i>
        <input type="number" id="fin_for" min="0" step="1" class="form-control validate segmentaFecha" value="<?php echo $fin_for; ?>">
        <label  for="fin_for">Asigna un Fin</label>
          </div>
    
  </div>
</div>



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


	<!-- CONTENIDO -->
	<div class="row">
		<div class="col-md-12">
		<br>
				<?php 

				//VALIDACION DE QUE EXISTE CONTENIDO
					if($des_for!="") {
				?>

				<div id="box">
					<div id="des_for">
						<?php echo $des_for; ?>

	      
	        		</div>
					
				</div>

				<?php
					} else {
				?>	


				<div id="box">
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
				<?php
					}
				?>

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
                        
                        <input type="text" id="nom_for" class="form-control validate" name="nom_for" required="">
                        <label for="nom_for">Título de la clase</label>
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

	//INICIALIZACION DE EDITORES
    var des_for = new Jodit("#des_for", {
        "language": "es",
        toolbarStickyOffset: 50

    });



    $("#agregarForo").on('click', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	var ini_for = parseInt($("#ini_for").val());
    	var fin_for = parseInt($("#fin_for").val());
    	var pun_for = parseFloat($("#pun_for").val());

    	//console.log(isNaN(ini_for));

    	var contenido = des_for.value;

    	//console.log(ini_for, fin_for, pun_for);


    	if ((ini_for!="") && (fin_for!="")) {
    		if (ini_for < fin_for) {
    			$.ajax({
					url: 'server/editar_foro.php?id_for=<?php echo $id_for; ?>',
					type: 'POST',
					data: {ini_for, fin_for, pun_for, contenido},

					success: function(respuesta){

						console.log(respuesta);
						if (respuesta == "Exito") {
							swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",})
							
							console.log("Guardado Exitosamente");
						}
					}	
				});
    		}else{
    			swal ( "Datos Incorrectos" ,  "¡Te recordamos que el inicio debe ser menor al fin!" ,  "error" )
    		}
    	}else{
    		///console.log("test activo");
    		$.ajax({
				url: 'server/editar_foro.php?id_for=<?php echo $id_for; ?>',
				type: 'POST',
				data: {ini_for, fin_for, pun_for, contenido},

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






</script>


<script>
  $( ".segmentaFecha" ).on( 'change', function( event ) {
    event.preventDefault();
    /* Act on the event */

    var ini_for = parseInt( $( '#ini_for' ).val() ) + 1;
    var fin_for = parseInt( $( '#fin_for' ).val() ) + 1;
    // alert(ini_for);
    // var ini_for = $( '#ini_for' ).val();

    var fechaPrev1 = new Date( $( "#segmentaFecha" ).val() );

    var prevInicio = new Date( fechaPrev1.setDate( fechaPrev1.getDate() + ini_for ) );
    $( '#prevInicio' ).html( moment( prevInicio ).format( "DD/MM/YYYY" ) );

    var fechaPrev2 = new Date( $( "#segmentaFecha" ).val() );
    var prevFin = new Date( fechaPrev2.setDate( fechaPrev2.getDate() + fin_for ) );
    $( '#prevFin' ).html( moment( prevFin ).format( "DD/MM/YYYY" ) );

    // moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' )

  });
</script>


<script>


  $( '.edicionTitulo' ).on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var nom_for = $( this ).attr( 'nom_for' );

    $( '#modal_clase_edicion' ).modal( 'show' );

    setTimeout( function(){
      $( '#nom_for' ).focus();
    }, 500 );

    $( '#nom_for' ).val( nom_for );

  });

  $( '#formularioClaseEdicion' ).on('submit', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('click en submit');
        var formularioClaseEdicion = new FormData( $('#formularioClaseEdicion')[0] );
        formularioClaseEdicion.append( 'id_for', '<?php echo $id_for ?>' );

        $.ajax({

            url: 'server/editar_foro.php?id_for=<?php echo $id_for; ?>',
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

                      $( '.edicionTitulo' ).eq( 0 ).html( '<i class="fas fa-bookmark"></i> ' + $( '#nom_for' ).val() ).removeAttr( 'nom_for' ).attr( 'nom_for', $( '#nom_for' ).val() );

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