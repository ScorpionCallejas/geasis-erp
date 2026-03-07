<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_ent_cop = $_POST['id_ent_cop'];
    $id_alu_ram = $_POST['id_alu_ram'];
?>


<?php

    $sqlValidacionTarea = "
        SELECT * 
        FROM entregable_copia
        INNER JOIN tarea ON tarea.id_ent_cop1 = entregable_copia.id_ent_cop
        WHERE id_ent_cop = '$id_ent_cop' AND id_alu_ram6 = '$id_alu_ram'
        ORDER BY id_tar DESC
        LIMIT 1
    
    ";

    // echo $sqlValidacionTarea;

    $resultadoValidacionTarea = mysqli_query($db, $sqlValidacionTarea);
    $filaValidacionTarea = mysqli_fetch_assoc($resultadoValidacionTarea);

    $totalTareas = mysqli_num_rows($resultadoValidacionTarea);

    // VALIDACION SI ENTREGO O NO TAREA POR USUARIO
    if ($totalTareas == 1) {
?>  
    <div class="jumbotron black-text mx-2 mb-5 text-center botonPadre" style="border-radius: 20px;">
        
		<!-- DESCRIPCION -->
		<p class="note note-success">
			<!-- <button type="button" class="close" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button> -->
			<i class="fas fa-check fa-2x delay-2s mb-3 animated rotateIn text-success"></i>
			
			<br>
			La tarea se ha subido correctamente. Presiona el botón rojo para removerla y podrás subirla de nuevo.
			
		</p>
		<!-- FIN DESCRIPCION -->

        <a href="../uploads/<?php echo $filaValidacionTarea['doc_tar']; ?>" download class="btn-link" title="Descargar: <?php echo $filaValidacionTarea['doc_tar']; ?>">
            <h5>
                <i class="fas fa-file-alt fa-2x"></i>
                <br>
                <?php echo $filaValidacionTarea['doc_tar']; ?>
            </h5>
            
        </a>

        <a href="#" class="btn btn-danger white-text btn-rounded waves-effect btn-sm eliminacionTarea" tarea="<?php echo $filaValidacionTarea['id_tar']; ?>" title="Eliminar mi tarea...">
        	Eliminar tarea
        </a>


 
    </div>


<?php
    }else{
?>

    <!-- DRAG AND DROP FORMULARIO -->
    <div class="jumbotron mx-2 mb-5 text-center" style="border-radius: 20px;" data-step="9" data-intro="La tarea su sube aquí, solo un archivo, el sistema acepta diversos formatos como imágenes (jpg, jpeg o png), videos (de android y iphone), word, pdf, excel, power point... ¡entre muchos más!" data-position='right'>


		<!-- DESCRIPCION -->
		<p class="note note-info">
			<!-- <button type="button" class="close" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button> -->
            
			La tarea se sube aquí, SOLO UN ARCHIVO, la plataforma acepta diversos formatos como imágenes (jpg, jpeg o png), videos (de android y iphone), word, pdf, excel y power point. No canceles mientras se sube el archivo.
			
		</p>
		<!-- FIN DESCRIPCION -->

        <div class="row">
            <div class="col-md-12">
                <section class="my-5">
                    <form id="agregarTareaFormulario" enctype="multipart/form-data" method="POST">
                                                
                        <div class="file-upload-wrapper">
                          <div class="input-group mb-3 border border-success">
                            <input type="file" id="doc_tar" name="doc_tar" class="file_upload " placeholder="Sube Archivo"  required="" />
                          </div>
                        </div>

                        <div class="progress md-progress" style="height: 20px">
                            <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo">
                                
                              
                            </div>
                        </div>

                        <button class="btn btn-info white-text btn-rounded waves-effect btn-sm" type="submit" title="Subir Archivo" id="btn_enviar" data-step="10" data-intro="Cuando tengas tu tarea lista, arrastra, suelta y presiona este botón para subir" data-position='right'>
                            Subir tarea
                        </button>

                    </form>
                  
                </section>
            </div>
        </div>
    </div>  
    <!-- FIN DRAG AND DROP FORMULARIO -->

<?php
    }

?>
<!-- FIN INTERACCION -->



<script>
	$('.file_upload').file_upload();
</script>

<script>
    function obtenerNotificacionesActividadesMateria(){

        var id_alu_ram = parseInt( '<?php echo $id_alu_ram; ?>' );
        var id_sub_hor = parseInt( '<?php echo $id_sub_hor; ?>' );

        for( var i = 0; i < $( '.claseHijoMateria' ).length; i++ ){

            if ( $( '.claseHijoMateria' ).eq( i ).attr( 'id_sub_hor' ) == id_sub_hor ) {

                $.ajax({
                    ajaxContador: i,
                    url: 'server/obtener_total_notificaciones_grupo.php',
                    type: 'POST',
                    data: { id_sub_hor, id_alu_ram },
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



    function obtenerNotificacionesActividadesPrograma(){

        var id_alu_ram = parseInt( '<?php echo $id_alu_ram; ?>' );

        for( var i = 0; i < $( '.claseHijoPrograma' ).length; i++ ){

            if ( $( '.claseHijoPrograma' ).eq( i ).attr( 'id_alu_ram' ) == id_alu_ram ) {

                $.ajax({
                    ajaxContador: i,
                    url: 'server/obtener_total_notificaciones_programa.php',
                    type: 'POST',
                    data: { id_alu_ram },
                    success: function( respuesta ){

                        $( '.claseHijoPrograma' ).eq( this.ajaxContador ).html( respuesta );

                    }
                });

            }

        }

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



<script>

    //FORMULARIO DE CREACION DE ALUMNO
    //CODIGO PARA AGREGAR ALUMNO NUEVO ABRIENDO MODAL

    $('#agregarTareaFormulario').on('submit', function(event) {
        event.preventDefault();

        if ($("#doc_tar")[0].files[0]) {

            var fileName = $("#doc_tar")[0].files[0].name;
            var fileSize = $("#doc_tar")[0].files[0].size;
            var ext = fileName.split('.').pop();

            if(
                ext == 'jpg' ||
                ext == 'jpeg' ||
                ext == 'png' ||
                ext == 'doc' ||
                ext == 'docx' ||
                ext == 'ppt' ||
                ext == 'pptx' ||
                ext == 'pdf' ||
                ext == 'xlsx' ||
                ext == 'mp4' ||   // Formato de video MP4
                ext == 'mov' ||   // Formato de video MOV (iOS)
                ext == '3gp' ||   // Formato de video 3GP (Android)
                ext == 'mkv' ||   // Formato de video MKV (Android)
                ext == 'webm' ||  // Formato de video WEBM (Android)
                ext == 'av1'     // Formato de video AV1 (Android)
            ){
                if ( 
                    ( 
                        (
                            ext == 'jpg' ||
                            ext == 'jpeg' ||
                            ext == 'png' ||
                            ext == 'doc' ||
                            ext == 'docx' ||
                            ext == 'ppt' ||
                            ext == 'pptx' ||
                            ext == 'pdf' ||
                            ext == 'xlsx' 
                        ) 
                        &&
                        ( fileSize < 50000000 ) 
                    ) 
                    || 
                    ( 
                        (
                            ext == 'mp4' ||   // Formato de video MP4
                            ext == 'mov' ||   // Formato de video MOV (iOS)
                            ext == '3gp' ||   // Formato de video 3GP (Android)
                            ext == 'mkv' ||   // Formato de video MKV (Android)
                            ext == 'webm' ||  // Formato de video WEBM (Android)
                            ext == 'av1' 
                        ) 
                        &&
                        ( fileSize < 50000000 ) 
                    ) 
                ) {
                    $("#btn_enviar").removeClass('btn-info').addClass('light-green accent-4').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');
                    let barra_estado_archivo = $("#barra_estado_archivo");

                    //Eliminacion de "Listo"
                    barra_estado_archivo.text("");

                    //Remueve clase de estatus listo
                    barra_estado_archivo.removeClass();

                    //Agrega la clase inicial del progress bar
                    barra_estado_archivo.addClass('progress-bar text-center white-text');

                    var agregarTareaFormulario = new FormData( $('#agregarTareaFormulario')[0] );

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
                        url: 'server/agregar_tarea.php?id_alu_ram=<?php echo $id_alu_ram."&id_ent_cop=".$id_ent_cop; ?>',
                        type: 'POST',
                        data: agregarTareaFormulario,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(respuesta){
                            
                            console.log(respuesta);
                        
                        if (respuesta == "Exito") {
                            console.log("Guardado Exitosamente");

                            $("#btn_enviar").html('<i class="fas fa-check white-text"></i> <span>Subida Exitosa</span>');
                            swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                            then((value) => {
                                
                                obtener_tarea();
                                $( '#modal_obtener_actividad' ).on('hidden.bs.modal', function () {
                                    
                                    if ( window.location.pathname != '/geasis/alumno/historial_actividades.php' ) {
                                        removeParam("identificador_copia");
                                        removeParam("tipo_actividad");

                                        obtenerActividades();
                                    } else {
                                        obtenerTablaHistorialActividades();
                                    }
                                    
                                    

                                    // alert( id_sub_hor );
                                    obtenerNotificacionesActividadesMateria();

                                    obtenerNotificacionesActividadesNavbar();

                                    obtenerNotificacionesActividadesPrograma();

                                    
                                });
                            });
                        } else {

                            swal ( "¡Error de archivo!" ,  "¡No se subió tu tarea correctamente, inténtalo otra vez o súbela más tarde. Si el error persiste, repórtalo a la dirección!" ,  "error" ).
                            then((value) => {
                                
                                obtener_tarea();
                                
                            });

                        }
                        
                        }
                    });
                }else{
                    swal ( "¡Archivo inválido!" ,  "¡Te recordamos que el peso no debe exceder los 50MB!" ,  "error" );
                }
                
            }else{
                swal ( "¡Archivo inválido!" , "Te recordamos que los formatos aceptados son Word (doc, docx), Excel (xlsx), Power Point (ppt, pptx), PDF, JPEG, JPG, PNG, MP4, MOV (iOS), 3GP (Android), MKV (Android), WEBM (Android) y AV1." ,  "error" );
            }
        }
    });




    //ELIMINACION TAREA
    // ELIMINACION DE COMENTARIOS
    $('.eliminacionTarea').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var tarea = $(this).attr("tarea");
        // console.log(BLOQUE);

        swal({
          title: "¿Deseas eliminar tu tarea?",
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
                url: 'server/eliminacion_tarea.php',
                type: 'POST',
                data: {tarea},
                success: function(respuesta){
                    
                    if (respuesta == "true") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                          obtener_tarea();
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