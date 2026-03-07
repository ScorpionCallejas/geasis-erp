<?php  

  include('inc/header.php');
  $id_ent = $_GET['id_ent'];

  //CONSULTA DE ENTREGABLE
  $sqlEntregable = "
    SELECT * 
    FROM entregable
    INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    INNER JOIN rama ON rama.id_ram = materia.id_ram2 
    WHERE id_ent = '$id_ent'";
  $resultadoEntregable = mysqli_query($db, $sqlEntregable);
  $filaEntregable = mysqli_fetch_assoc($resultadoEntregable);

  $nom_ent = $filaEntregable['nom_ent'];
  $des_ent = $filaEntregable['des_ent'];
  $pun_ent = $filaEntregable['pun_ent'];
  $ini_ent = $filaEntregable['ini_ent'];
  $fin_ent = $filaEntregable['fin_ent'];
  $id_blo = $filaEntregable['id_blo5'];

  $nom_blo = $filaEntregable['nom_blo'];
  $nom_mat = $filaEntregable['nom_mat'];
  $nom_ram = $filaEntregable['nom_ram'];
  $id_mat = $filaEntregable['id_mat'];
  $id_ram = $filaEntregable['id_ram'];


?>


<!-- BOTON FLOTANTE AGREGAR CONTENIDO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 45px; right: 24px;" id="agregarEntregable"><i class="fas fa-save fa-1x" title="Guardar" ></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR CONTENIDO-->

<!-- TITULO -->
<div class="row ">

  <div class="col text-left">


    <span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect edicionTitulo" nom_ent="<?php echo $nom_ent; ?>" title="Título">
      <i class="fas fa-bookmark"></i> 
      <?php echo $nom_ent; ?>
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
        <a style="color: black;" href="" title="Estás aquí">Entregable</a>
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


<div class="row">
  <div class="col">


          <div class="md-form mb-2">

        <i class="fas fa-award prefix grey-text"></i>
        <input type="number" id="pun_ent" min="0" step=".1" class="form-control validate" value="<?php echo $pun_ent; ?>">
        <label  for="pun_ent">Asigna un puntaje</label>
          </div>

  </div>
  <div class="col">


          <div class="md-form mb-2">
            <i class="fas fa-minus-circle prefix grey-text"></i>
        <input type="number" id="ini_ent" min="0" step="1" class="form-control validate segmentaFecha" value="<?php echo $ini_ent; ?>">
        <label  for="ini_ent">Asigna un Inicio</label>
          </div>
    
  </div>
  <div class="col">


          <div class="md-form mb-2">
        <i class="fas fa-plus-circle prefix grey-text"></i>
        <input type="number" id="fin_ent" min="0" step="1" class="form-control validate segmentaFecha" value="<?php echo $fin_ent; ?>">
        <label  for="fin_ent">Asigna un Fin</label>
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
          if($des_ent!="") {
        ?>

        <div id="box">
          <div id="des_ent">
            <?php echo $des_ent; ?>

        
              </div>
          
        </div>

        <?php
          } else {
        ?>  


        <div id="box">
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
        <?php
          }
        ?>
        <br>

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
                        
                        <input type="text" id="nom_ent" class="form-control validate" name="nom_ent" required="">
                        <label for="nom_ent">Título de la clase</label>
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
    var des_ent = new Jodit("#des_ent", {
        "language": "es",
        toolbarStickyOffset: 50

    });



    $("#agregarEntregable").on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var ini_ent = parseInt($("#ini_ent").val());
      var fin_ent = parseInt($("#fin_ent").val());
      var pun_ent = parseFloat($("#pun_ent").val());
      var contenido = des_ent.value;

      console.log(ini_ent, fin_ent, pun_ent, contenido);


      if ((ini_ent!="") && (fin_ent!="")) {
        if (ini_ent < fin_ent) {
          $.ajax({
          url: 'server/editar_entregable.php?id_ent=<?php echo $id_ent; ?>',
          type: 'POST',
          data: {ini_ent, fin_ent, pun_ent, contenido},

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
        $.ajax({
        url: 'server/editar_entregable.php?id_ent=<?php echo $id_ent; ?>',
        type: 'POST',
        data: {ini_ent, fin_ent, pun_ent, contenido},

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

    var ini_ent = parseInt( $( '#ini_ent' ).val() ) + 1;
    var fin_ent = parseInt( $( '#fin_ent' ).val() ) + 1;
    // alert(ini_ent);
    // var ini_ent = $( '#ini_ent' ).val();

    var fechaPrev1 = new Date( $( "#segmentaFecha" ).val() );

    var prevInicio = new Date( fechaPrev1.setDate( fechaPrev1.getDate() + ini_ent ) );
    $( '#prevInicio' ).html( moment( prevInicio ).format( "DD/MM/YYYY" ) );

    var fechaPrev2 = new Date( $( "#segmentaFecha" ).val() );
    var prevFin = new Date( fechaPrev2.setDate( fechaPrev2.getDate() + fin_ent ) );
    $( '#prevFin' ).html( moment( prevFin ).format( "DD/MM/YYYY" ) );

    // moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' )

  });
</script>



<script>


  $( '.edicionTitulo' ).on('click', function(event) {
    event.preventDefault();
    /* Act on the event */


    var nom_ent = $( this ).attr( 'nom_ent' );

    $( '#modal_clase_edicion' ).modal( 'show' );

    setTimeout( function(){
      $( '#nom_ent' ).focus();
    }, 500 );

    $( '#nom_ent' ).val( nom_ent );

  });

  $( '#formularioClaseEdicion' ).on('submit', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('click en submit');
        var formularioClaseEdicion = new FormData( $('#formularioClaseEdicion')[0] );
        formularioClaseEdicion.append( 'id_ent', '<?php echo $id_ent ?>' );

        $.ajax({

            url: 'server/editar_entregable.php?id_ent=<?php echo $id_ent; ?>',
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

                      $( '.edicionTitulo' ).eq( 0 ).html( '<i class="fas fa-bookmark"></i> ' + $( '#nom_ent' ).val() ).removeAttr( 'nom_ent' ).attr( 'nom_ent', $( '#nom_ent' ).val() );

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