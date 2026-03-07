<?php 
    require_once(  __DIR__."/../../includes/links_js.php");
?>
<!-- Select2 JS (después de jQuery) -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script>
  function obtener_tabla_alumnos2( id_gen = '', palabra = '', inicio = '', fin = '', estatus = '', tipo_estatus = '', pageLength = '' ){

        // console.log( id_gen.length );

        $('#contenedor_botones_accion').html('<div class="col-md-12"> <div class="row"> <div class="col-md-12"> <div class="form-check"> <input type="checkbox" class="form-check-input" id="seleccionTotal"> <label class="form-check-label letraPequena font-weight-normal" for="seleccionTotal"> <span class="badge badge-pill badge-danger letraMediana font-weight-normal" id="contador_alumnos_seleccionados"></span> Seleccionar/Deseleccionar </label> </div></div></div><br><div class="row"> <div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Inscribir materias" id="btn_inscripcion" style="width: 100%;"> Inscripción </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Dar de baja materias" id="btn_baja" style="width: 100%;"> Cancelación de inscr. </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Envía un mensaje a los alumnos seleccionados" id="btn_mensaje_alumnos" style="width: 100%;"> Enviar mensaje </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Genera un pago para los alumnos seleccionados" id="btn_pago_alumnos" style="width: 100%;"> Crear pago </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Cambiar de grupo" id="btn_cambio" style="width: 100%;"> Cambiar grupo </a> </div> <div class="col-md-3"><a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Agregar a otro programa o curso" id="btn_programa" style="width: 100%;"> Agregar a programa </a></div></div></div>');
                
        $('#tabla_alumnos').DataTable().destroy();

        $('#contenedor_visualizacion3').html('<div class="table-responsive"> <table id="tabla_alumnos" class="table table-striped"> <thead> <tr> <th class="letraPequena" style="background-color: #F5F5F5 !important;">Foto</th> <th class="letraPequena" style="background-color: #F5F5F5 !important;">Nombre</th> <th class="letraPequena">Acción</th> <th class="letraPequena">Selección</th> <th class="letraPequena">Ingreso</th>  <th class="letraPequena">Matrícula</th> <th class="letraPequena">Teléfono</th> <th class="letraPequena">Cuenta de acceso</th> <th class="letraPequena">Contraseña</th> <th class="letraPequena">Grupo</th> <th class="letraPequena">Programa</th> <th class="letraPequena">Estatus general</th> <th class="letraPequena">Estatus académico</th><th class="letraPequena">Carga alumno</th> <th class="letraPequena">Estatus de pagos</th> <th class="letraPequena">Meses adeudo</th> <th class="letraPequena">Adeudo</th> <th class="letraPequena">Pagado</th> <th class="letraPequena">Estatus de cuenta</th> <th class="letraPequena">Subestatus</th> <th class="letraPequena">Estatus de actividad</th> <th class="letraPequena">Estatus de documentación</th> </tr></thead> </table> </div>');

            // OBTENER RESPUESTA DATATABLE IMPRIMIR IMPRESION
            // length = 10;
            // start = 0;
            // draw = 10;
            // $.ajax({
            //     url: 'server/obtener_alumnos_generaciones.php',
            //     type: 'POST',
            //     data: { id_gen, palabra, inicio, fin, estatus, tipo_estatus, length, start, draw },
            //     success: function( respuesta ){

            //         console.log( respuesta );
            //         // $('#contenedor_visualizacion').html( respuesta );
            //     }
                
            // });

            var dataTable = $('#tabla_alumnos').DataTable({


                dom: 'Bfrtpli',
                
                scrollX: true,
                scrollY: true,
                scrollCollapse: true,
                fixedColumns: {     
                  leftColumns: [2]
                },
                buttons: [

                    {
                        extend: 'excelHtml5',
                        messageTop: 'Listado de Alumnos del Plantel',
                        exportOptions: {
                            columns: ':visible'
                        },
                        className: 'btn btn-info btn-sm waves-effect'
                    }                  

                ],
                "pageLength" : pageLength,
                "columnDefs": [
                  { "orderable": false, "targets": [ 0, 2, 3 ] }
                ],
                "processing" : true,
                "serverSide" : true,
                "order" : [],
                "searching" : false,


                "ajax" : {
                    url:"server/obtener_alumnos_generaciones.php",
                    type:"POST",
                    data:{
                        id_gen, palabra, inicio, fin, estatus, tipo_estatus
                    } 
                },

                "fnDrawCallback": function( oSettings ) {



                  // NUEVA CONSULTA ALUMNO
                  $('.consultaGeneralAlumno').on('click', function(event) {
                    event.preventDefault();
                    /* Act on the event */
                    var id_alu_ram = $(this).attr('id_alu_ram');

                    $('#contenedor_visualizacion6').html('<div class="row"><div class="col-md-12"><a href="#" id="btn_volver_alumnos" class="btn-link text-primary waves"><h5>< Volver a alumnos</h5></a></div></div><hr>');

            $('#contenedor_visualizacion2').css( 'display', 'none' );
            $('#contenedor_visualizacion5').css( 'display', '' );

            $('#contenedor_visualizacion7').html('<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

            $.ajax({
                      url: 'server/obtener_consulta_general_alumno.php',
                      type: 'POST',
                      data: { id_alu_ram },
                      success: function( respuesta ){
                        // console.log( respuesta );
                        $('#contenedor_visualizacion7').html( respuesta );
                      }
                    });
            
            // console.log('hehe');

            $('#btn_volver_alumnos').on('click', function(event) {
              event.preventDefault();
              /* Act on the event */

              error.play();
              $('#contenedor_visualizacion2').css( 'display', '' );
              $('#contenedor_visualizacion5').css( 'display', 'none' );

            });

                    
                  
                  });

                  // FIN NUEVA CONSULTA ALUMNO


                    pegarSeleccionAlumno();
                    validarPaginacionSeleccionada();

                    // SELECCION
                    function validarPaginacionSeleccionada(){
                        var booleano = true;
                        for(  var i = 0; i < $('.seleccionAlumno').length; i++ ){
                            
                            if ( $('.seleccionAlumno')[i].checked == false ) {
                                booleano = false;
                                break; break;
                            }
                            
                        }

                        if ( booleano == true ) {

                            $('#seleccionTotal').prop({checked: true});

                        } else {
                            $('#seleccionTotal').prop({checked: false});
                        }
                    }   

                    $('.seleccionAlumno').on('click', function(event) {
                        // event.preventDefault();

                        burbuja.play();

                        var id_alu_ram = $(this).attr( 'id_alu_ram' );
                        var checkbox = $(this)[0].checked;
                        var path_foto = $(this).attr( 'path_foto' );
                        var nombre_alumno = $(this).attr( 'nombre_alumno' );
                        var id_ram = $(this).attr( 'id_ram' );
                        var estatus_pago = $(this).attr( 'estatus_pago' );

                        console.log(estatus_pago);



                        pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, id_ram, estatus_pago );
                        

                    });
                    // PREVIEW SELECCION
                    

                    
                    // FUNCION PARA QUE EN CADA PAGINACION SE CHECKEEN ALUMNOS PREVI. SELECCIONADOS
                    function pegarSeleccionAlumno(){
                        
                        if ( $('.pegadoSeleccionAlumno').length > 0 ) {

                            for( var i = 0; i < $('.seleccionAlumno').length; i++ ){
                            
                                for( var j = 0; j < $('.pegadoSeleccionAlumno').length; j++ ){

                                    if ( $('.pegadoSeleccionAlumno').eq( j ).attr( 'id_alu_ram' ) == $(".seleccionAlumno").eq( i ).attr( 'id_alu_ram' ) ) {

                                        $(".seleccionAlumno").eq( i ).prop({checked: true});
                                    
                                    }
                                    
                                
                                }
                            
                            }

                        }
                    
                    }


                    function pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, id_ram, estatus_pago ) {

                        console.log( 'id_ram: '+id_ram+' - estatus_pago: '+estatus_pago );
                        var array_id_alu_ram = [];

                        if ( $('.pegadoSeleccionAlumno').length == 0 ) {
                            

                            if ( checkbox == true ) {
                                // console.log('accedi');

                                setTimeout(function(){


                                    $("#contenedor_seleccion_alumnos").html( '<div class="chip pegadoSeleccionAlumno" id_alu_ram="'+id_alu_ram+'" path_foto="'+path_foto+'" id_ram="'+id_ram+'" nombre_alumno="'+nombre_alumno+'" estatus_pago="'+estatus_pago+'"><img src="'+path_foto+'"><span>'+nombre_alumno+'</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>' );

                                    contadorAlumnosSeleccionados();

                                }, 200);
                                
                            
                            }

                            

                        } else {

                            for( var i = 0 ; i < $('.pegadoSeleccionAlumno').length ; i++ ){

                                if ( id_alu_ram != $('.pegadoSeleccionAlumno').eq(i).attr('id_alu_ram') ) {
                                    // console.log('if');
                                    if ( checkbox == true ) {

                                            $("#contenedor_seleccion_alumnos").append( '<div class="chip pegadoSeleccionAlumno" id_alu_ram='+id_alu_ram+' id_ram="'+id_ram+'" nombre_alumno="'+nombre_alumno+'" estatus_pago="'+estatus_pago+'"><img src="'+path_foto+'"><span>'+nombre_alumno+'</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>' );
                                            break; break; break;

                                    }
                                    

                                } else {
                                    // console.log('else');
                                    $('.pegadoSeleccionAlumno').eq( i ).remove();
                                    break; break;

                                }

                            }


                            for( var i = 0; i < $('.pegadoSeleccionAlumno').length; i++ ){

                                array_id_alu_ram[i] = ( $('.pegadoSeleccionAlumno').eq(i).attr('id_alu_ram') );

                            }



                            function onlyUnique(value, index, self) {
                                return self.indexOf(value) === index;
                            }



                            var array_id_alu_ram_purgado = array_id_alu_ram.filter(onlyUnique);
                            
                            
                            $("#contenedor_seleccion_alumnos").html('');

                            for( var i = 0; i < array_id_alu_ram_purgado.length; i++ ){


                                for( var j = 0; j < $('.seleccionAlumno').length ; j++ ){
                                    
                                    if( $('.seleccionAlumno').eq( j ).attr( 'id_alu_ram' ) == array_id_alu_ram_purgado[i] ){
                                        nombre_alumno = $('.seleccionAlumno').eq( j ).attr( 'nombre_alumno' );
                                        path_foto = $('.seleccionAlumno').eq( j ).attr( 'path_foto' );
                                        estatus_pago = $('.seleccionAlumno').eq( j ).attr( 'estatus_pago' );
                                        id_ram = $('.seleccionAlumno').eq( j ).attr( 'id_ram' );

                                        break; break;
                                    }

                                }

                                
                                // console.log( seleccion[i] );
                                $("#contenedor_seleccion_alumnos").append( '<div class="chip pegadoSeleccionAlumno" id_alu_ram="'+array_id_alu_ram_purgado[i]+'" path_foto="'+path_foto+'" id_ram="'+id_ram+'" nombre_alumno="'+nombre_alumno+'" estatus_pago="'+estatus_pago+'"><img src="'+path_foto+'"><span>'+nombre_alumno+'</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>' );

                            }

                            contadorAlumnosSeleccionados();

                            

                        }

                        function contadorAlumnosSeleccionados(){
                            if ( $('.pegadoSeleccionAlumno').length > 0 ) {
                                $('#contador_alumnos_seleccionados').text( $('.pegadoSeleccionAlumno').length );
                            } else {
                                $('#contador_alumnos_seleccionados').text('');
                            }
                        }

                    }



                    // FIN SELECCION


                    // SELECCION SOMBRA X NOMBRE
                    $( '.seleccionNombre' ).on('click', function( event ) {
                        // event.preventDefault();
                        /* Act on the event */
                        var elemento = $( this ).parent().parent().children();
                        var indice = $( this ).parent().parent().index();

                        //     burbuja.play();
                        //     elemento.addClass('grey lighten-2');

                        //     $('#tabla_alumnos tr').eq( ++indice ).addClass('grey lighten-2');
                        // console.log( $( this ).parent().hasClass('grey lighten-2') );
                        
                        burbuja.play();
                        elemento.addClass('grey lighten-2');

                        $('#tabla_alumnos tr').eq( ++indice ).addClass('grey lighten-2');

//                            if ( $( this ).parent().hasClass('grey lighten-2') == false ) {
                            

//                                console.log('2');
//                                // alert('nada');
//                                burbuja.play();
//                                elemento.addClass('grey lighten-2');

//                                $('#tabla_alumnos tr').eq( ++indice ).addClass('grey lighten-2');

//                            } else {
// error.play();
//                                elemento.removeClass('grey lighten-2');

//                                $('#tabla_alumnos tr').eq( ++indice ).removeClass('grey lighten-2');
                            
//                                console.log('1');
                            
//                            // alert( $(this).index() );
                            
//                            }
                        

                        
                    });


                    //SELECCION
                    $("#seleccionTotal").on('click', function() {
                        //event.preventDefault();
                        /* Act on the event */

                        //console.log( $(this)[0].checked );

                        if ( $(this)[0].checked == true ) {
                          // console.log("checkeado");

                            $('.seleccionAlumno').prop({checked: true});
                                i = 0;

                                
                                obtenerDatos1();
                                async function obtenerDatos1(){
                                    while (  $('.seleccionAlumno').length  > i){
                                        var id_alu_ram = $('.seleccionAlumno').eq(i).attr( 'id_alu_ram' );
                                        var checkbox = $('.seleccionAlumno')[i].checked;

                                        console.log( $('.seleccionAlumno')[i].checked );
                                        var path_foto = $('.seleccionAlumno').eq(i).attr( 'path_foto' );
                                        var nombre_alumno = $('.seleccionAlumno').eq(i).attr( 'nombre_alumno' );
                                        var id_ram = $('.seleccionAlumno').eq(i).attr( 'id_ram' );
                                        var estatus_pago = $('.seleccionAlumno').eq(i).attr( 'estatus_pago' );
                                        
                                        
                                        await new Promise( resolve => setTimeout( resolve, 300 ) )
                                        pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, estatus_pago );
                                         
                                        
                                        // if ( i == 1 ) {
                                        //     $('.pegadoSeleccionAlumno').eq(0).remove();
                                        // }
                                        
                                        i++;
                                        
                                    }
                                }

                            
                            

                            
                            // pegadoSeleccionAlumnos();
                            

                        }else{ 
                            

                            $('.seleccionAlumno').prop({checked: false});

                            i = 0;

                                
                            obtenerDatos2();
                            async function obtenerDatos2(){
                                while (  $('.seleccionAlumno').length  > i){
                                    var id_alu_ram = $('.seleccionAlumno').eq(i).attr( 'id_alu_ram' );
                                    var checkbox = $('.seleccionAlumno')[i].checked;

                                    console.log( $('.seleccionAlumno')[i].checked );
                                    var path_foto = $('.seleccionAlumno').eq(i).attr( 'path_foto' );
                                    var nombre_alumno = $('.seleccionAlumno').eq(i).attr( 'nombre_alumno' );
                                    var id_ram = $('.seleccionAlumno').eq(i).attr( 'id_ram' );
                                    var estatus_pago = $('.seleccionAlumno').eq(i).attr( 'estatus_pago' );
                                    
                                    
                                    await new Promise( resolve => setTimeout( resolve, 300 ) )
                                    pegadoSeleccionAlumno( id_alu_ram, checkbox, path_foto, nombre_alumno, estatus_pago );
                                     
                                    
                                    // if ( i == 1 ) {
                                    //     $('.pegadoSeleccionAlumno').eq(0).remove();
                                    // }
                                    
                                    i++;
                                    
                                }
                            }
                        
                        }

                        //$('.seleccionAlumno').prop({checked: false});
                    });


                    // FIN SELECCION

                    $('.eliminacionAlumno').on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        var alumno = $(this).attr("eliminacion");
                        var nombreAlumno = $(this).attr("alumno");

                        // console.log(alumno);

                        // VALIDACION PERMISOS

                        swal({
                          title: "¡Acceso Restringido!",
                          icon: "warning",
                          text: 'Necesitas permisos de administrador para continuar',
                          content: {
                            element: "input",
                            attributes: {
                              placeholder: "Ingresa una contraseña...",
                              type: "password",
                            },
                          },

                          button: {
                            text: "Validar",
                            closeModal: false,
                          },
                        })
                        .then(name => {
                          if (name){
                            //console.log(name);
                            var password = name;
                            $.ajax({
                                    
                                url: 'server/validacion_permisos.php',
                                type: 'POST',
                                data: {password},
                                success: function(respuesta){
                                    //console.log(respuesta);

                                    if (respuesta == 'True') {

                                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                        then((value) => {
                                            //
                                            //console.log("Existe el password");
                                            // CODIGO

                                            swal({
                                              title: "¿Deseas eliminar a "+nombreAlumno+"?",
                                              text: "¡Una vez eliminado se perderán todos los datos relacionados a esa persona!",
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
                                                url: 'server/eliminacion_alumno.php',
                                                type: 'POST',
                                                data: {alumno},
                                                success: function( respuesta ){

                                                    console.log( respuesta );
                                                  
                                                  // if (respuesta == "true") {
                                                    console.log("Exito en consulta");
                                                    swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                      // window.location.reload();


                                                      // obtenerAlumnosGeneraciones();
                                                      reloadTableGeneral();


                                                    });
                                                  // }else{
                                                  //   console.log(respuesta);

                                                  // }

                                                }
                                              });
                                                
                                              }
                                            });

                                            // FIN CODIGO
                                        });
                                        
                                    }else{
                                        // LA CONTRASENA NO EXISTE
                                        
                                        swal({
                                          title: "¡Datos incorrectos!",
                                          text: 'No existe la contraseña...',
                                          icon: "error",
                                          button: "Aceptar",
                                        });
                                        swal.stopLoading();
                                        swal.close();

                                        
                                    }
                                }
                            });

                          }else{
                            // DATOS VACIOS
                            swal({
                              title: "¡Datos vacíos!",
                              text: 'Necesitas ingresar una contraseña...',
                              icon: "error",
                              button: "Aceptar",
                            });
                            swal.stopLoading();
                            swal.close();
                          }
                          
                        });

                        // FIN VALIDACION PERMISOS

                    });
                    // FIN ELIMINACION


                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


                    //EDICION DE ALUMNO

                    $('#btn_limpiar_formulario_edicion').on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        error.play();
                        $('#formulario_editar_alumno input').val('');
                        $('#contenedor_datos_secundarios_edicion label').removeClass('active');
                        $('#contenedor_imagen_edicion').removeAttr('src').attr('src', '../img/usuario.jpg');

                    });


                    // // FORMULARIO DATOS SECUNDARIOS
                    // obtener_checkbox_datos_secundarios_edicion();
                    // $("#checkboxAlumnoDatosSecundariosEdicion").on('click', function() {

                    //     obtener_checkbox_datos_secundarios_edicion();

                    // });

                    // function obtener_checkbox_datos_secundarios_edicion(){

                    //     if ( $('#checkboxAlumnoDatosSecundariosEdicion')[0].checked == true ) {
                    //         // console.log("checkeado");
                    //       $('#contenedor_datos_secundarios_edicion label').addClass('active');
                    //       $('#beca_alu_ram').val(0);
                    //       $('#beca2_alu_ram').val(0);
                    //       $('#nacimiento').val('<?php echo date('Y-m-d'); ?>');
                    //       $('#genero').val('PENDIENTE');
                    //       $('#curp').val('PENDIENTE');
                    //       $('#procedencia').val('PENDIENTE');
                    //       $('#direccion').val('PENDIENTE');
                    //       $('#codigo').val('PENDIENTE');
                    //       $('#colonia').val('PENDIENTE');
                    //       $('#delegacion').val('PENDIENTE');
                    //       $('#entidad').val('PENDIENTE');
                    //       $('#tutor').val('PENDIENTE');
                    //       $('#telefono2').val('PENDIENTE');

                    //     }else{
                          
                    //       $('#contenedor_datos_secundarios_edicion label').removeClass('active');
                    //       $('#beca_alu_ram').val('');
                    //       $('#beca2_alu_ram').val('');
                    //       $('#nacimiento').val('');
                    //       $('#genero').val('');
                    //       $('#curp').val('');
                    //       $('#procedencia').val('');
                    //       $('#direccion').val('');
                    //       $('#codigo').val('');
                    //       $('#colonia').val('');
                    //       $('#delegacion').val('');
                    //       $('#entidad').val('');
                    //       $('#tutor').val('');
                    //       $('#telefono2').val('');

                    //     }
                    // }


                    $('#foto2').on('change', function(event) {
                        event.preventDefault();

                        readURL2(this);

                    });


                    function readURL2(input) {
                        if (input.files && input.files[0]) {

                            var reader = new FileReader();
                            reader.onload = function (e) {
                            $('#contenedor_imagen_edicion2')
                              .attr('src', e.target.result);
                            };
                            reader.readAsDataURL(input.files[0]);
                        
                        }
                    }


                    // $('#correoEdicion').on('keyup', function(event) {
                    //     /* Act on the event */

                    //     var correo = $('#correoEdicion').val();
                    //     //validacionCorreoTiempoRealEdicion( correo );

                    // });

                    // function validacionCorreoTiempoRealEdicion1( correoEdicion ){
                    
                              
                    // }

                    
                    // CAMBIO DE PROGRAMA
                      $(".programas_edicion_alumno").off('click');

                      $(".programas_edicion_alumno").on('click', function() {
                        // event.preventDefault();
                        /* Act on the event */
                        obtener_generaciones2();
                        

                        var id_destino = $(".programas_edicion_alumno:checked").val();
                        var id_origen = $('#identificadorRama').val();

                        if ( id_origen != id_destino ) {

                            toastr.warning('¡Los pagos y calificaciones del programa actual se migrarán al grupo destino al ejecutar esta acción!');
                            toastr.error('¡Estás a punto de cambiar el programa!');

                        } else {

                            toastr.success('Programa original');
                        
                        }
                        

                      });

                      function obtener_generaciones2(){
                        // console.log('ejec de funcion obtener_generaciones2');
                        var id_alu_ram = $('#identificadorAlumnoRama').val();

                        // alert( id_alu_ram );
                        var ramas = [];
                        for(var j = 0; j < $(".programas_edicion_alumno").length; j++){
                          if ($(".programas_edicion_alumno")[j].checked == true) {
                            ramas.push($(".programas_edicion_alumno").eq(j).val());
                          }
                        }

                        // // alert( ramas.length );
                        // console.log(  );

                        $.ajax({
                          url: 'server/obtener_generaciones_programa_formulario.php',
                          type: 'POST',
                          data: { ramas, id_alu_ram },
                          success: function( respuesta ){
                            
                            // console.log( 'contenedor_generaciones: '+respuesta );
                            $("#contenedor_generaciones_modal2").html( respuesta );
                            
                            obtener_pagos_generacion2();
                          }
                        });
                      }
                    

                      

                      function obtener_pagos_generacion2(){

                        var id_gen = $('.generaciones2 option:selected').val();
                        var id_alu_ram = $('#identificadorAlumnoRama').val();

                        $.ajax({
                        
                          url: 'server/obtener_pagos_generacion.php',
                          type: 'POST',
                          data: { id_gen, id_alu_ram },
                          success: function( respuesta ){

                            $('#contenedor_pagos_generacion2').html( respuesta );

                          }
                        
                        });
                        
                      }
                    // FIN CAMBIO DE PROGRAMA

                    $('.edicionAlumno').on('click', function(event){
                        event.preventDefault();

                        var edicionAlumno = $(this).attr("edicion");
                        var rama = $(this).attr("rama");
                        $('#formulario_editar_alumno label').addClass('active');
                        $('#formulario_editar_alumno i').addClass('active');

                        $('#modal_editar_alumno').modal('show');


                        console.log('edicion alumno');

                        $.ajax({
                          url: 'server/obtener_alumno.php',
                          type: 'POST',
                          dataType: 'json',
                          data: {edicionAlumno, rama},
                          success: function(datos){

                            // console.log(datos);

                            $('#fotoText2').removeAttr('placeholder').val('');
                            
                            $('#nombre').val( datos.nom_alu);

                            $('#apellido1').val( datos.app_alu);
                            $('#apellido2').val( datos.apm_alu);
                            
                            $('#correo1_alumno').val( datos.cor1_alu);
                            $('#boleta').val( datos.bol_alu);
                            
                            // $('.generoAlumno2').val( datos.gen_alu);
                            for( var i = 0; i < $('.generoAlumno2').length; i++ ){
                                if ( $('.generoAlumno2').eq( i ).val() == datos.gen_alu ) {
                                    $('.generoAlumno2').eq( i ).prop({checked: true});
                                }
                            }
                            

                            $('#telefono').val( datos.tel_alu);
                            $('#curp').val( datos.cur_alu);
                            $('#procedencia').val( datos.pro_alu);
                            $('#correoEdicion').val( datos.cor_alu);
                            $('#password').val( datos.pas_alu);
                            $('#nacimiento').val( datos.nac_alu);
                     
                            $('#beca_alu_ram').val( datos.bec_alu_ram*100);
                            $('#beca2_alu_ram').val( datos.bec2_alu_ram*100);
                            $('#carga').val( datos.car_alu_ram);

                            if ( datos.fot_alu == null ) {

                                $('#contenedor_imagen_edicion2').removeAttr('src').attr( 'src', '../img/usuario.jpg' ); 
                                $('#fotoText2').removeAttr('placeholder').attr('placeholder', 'Sube un archivo en JPG, JPEG o PNG');

                            } else {
                            
                                $('#contenedor_imagen_edicion2').removeAttr('src').attr( 'src', '../uploads/'+datos.fot_alu ); 
                                $('#fotoText2').removeAttr('placeholder').attr('placeholder', datos.fot_alu);
                            
                            }
                            
                            $('#identificador').attr({value: datos.id_alu});
                            $('#identificadorAlumnoRama').val( datos.id_alu_ram );
                            $('#identificadorRama').val( datos.id_ram3 );
                            //AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL ALUMNO



                            // SELECCION DE PROGRAMA
                            for( var i = 0; i < $('.programas_edicion_alumno').length; i++ ){
                                if ( $('.programas_edicion_alumno').eq( i ).val() == datos.id_ram3 ) {
                                    $('.programas_edicion_alumno').eq( i ).prop({checked: true});
                                }
                            }
                            
                            // SELECCION GENERACION
                            obtener_generaciones2();
                            // FIN SELECCION GENERACION

                            // FIN SELECCION DE PROGRAMA
                            $('#formulario_editar_alumno').off('submit');
                            $('#formulario_editar_alumno').on('submit', function(event) {
                              
                              event.preventDefault();

                              $("#btn_guardar_alumno_edicion").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando');

                              var correoEdicion = $('#correoEdicion').val();
                              var identificador = $('#identificador').val();
                              var tipo = "Alumno";

                              $.ajax({
                                url: 'server/validacion_correo.php',
                                type: 'POST',
                                data: {correoEdicion, identificador, tipo},
                                success: function(respuesta){
                                  console.log(respuesta);

                                  if (respuesta == "disponible" || respuesta == "mio") {
                                        

                                        var formulario_editar_alumno = new FormData($("#formulario_editar_alumno")[0]);
                                        formulario_editar_alumno.append('genero', $('.generoAlumno2:checked').val());

                                        formulario_editar_alumno.append( 'id_ram', $('.programas_edicion_alumno:checked').val() );
                                        formulario_editar_alumno.append( 'id_gen', $('.generaciones2 option:selected').val() );

                                        // ESTATUS DE PAGOS SEGUN MARCADO DE CHECKBOX
                                        for( var i = 0; i < $('.estatus_pago_alumno').length; i++ ){
                                            
                                            if ( $('.estatus_pago_alumno:checked').eq(i).val() == 'on' ) {
                                            
                                                var est_gen_pag = 'Pagado';
                                            
                                            } else {
                                            
                                                var est_gen_pag = 'Pendiente';
                                            
                                            }
                                        
                                            formulario_editar_alumno.append( 'est_gen_pag[]', est_gen_pag );
                                        
                                        }
                                        // FIN ESTATUS DE PAGOS SEGUN MARCADO DE CASILLA

                                        if ($("#foto2")[0].files[0]) {

                                          var fileName = $("#foto2")[0].files[0].name;
                                          var fileSize = $("#foto2")[0].files[0].size;

                                          var ext = fileName.split('.').pop();

                                          
                                          if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
                                            if (fileSize < 3000000) {

                                              $.ajax({
                                                url: 'server/editar_alumno.php',
                                                type: 'POST',
                                                data: formulario_editar_alumno,
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                success: function(respuesta){
                                                console.log(respuesta);

                                                    
                                                    $("#btn_guardar_alumno_edicion").removeAttr('disabled').html('<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info').addClass('light-green accent-4');

                                                    swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                      //window.location.reload();
                                                        // obtenerAlumnosGeneraciones();
                                                        reloadTableGeneral();

                                                        setTimeout(function(){
                                                            $("#btn_guardar_alumno_edicion").html('Guardar').removeClass('light-green accent-4').addClass('btn-info');
                                                        }, 2000 );

                                                        $('#modal_editar_alumno').modal('hide');

                                                    });
                                                  
                                                }
                                              });
                                            }else{
                                              swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
                                            }
                                            
                                          }else{
                                            swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
                                          }

                                        }else{
                                            $.ajax({
                                          
                                                url: 'server/editar_alumno.php',
                                                type: 'POST',
                                                data: formulario_editar_alumno, 
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                success: function(respuesta){
                                                  console.log(respuesta);


                                                    $("#btn_guardar_alumno_edicion").removeAttr('disabled').html('<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info').addClass('light-green accent-4');

                                                    swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                    then((value) => {
                                                        
                                                        //window.location.reload();
                                                        // obtenerAlumnosGeneraciones();
                                                        reloadTableGeneral();
                                                        //console.log("exito sin foto");
                                                        
                                                        setTimeout(function(){
                                                            $("#btn_guardar_alumno_edicion").html('Guardar').removeClass('light-green accent-4').addClass('btn-info');
                                                        }, 2000 );

                                                        $('#modal_editar_alumno').modal('hide');

                                                    });
                                                    
                                                  
                                                }
                                            });

                                        }            

                                    
                                  }else{

                                    $('#validacionCorreoEdicion').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

                                  }
                                }
                              });   
                            });
                          }
                        });
                    });

                    //FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE ALUMNO



                    // CAMBIO DE GENERACION

                    $("#btn_cambio").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        console.log('cambio gen');
                        // VARIABLES
                        //ARREGLO DE ALUMNOS POR INSCRIBIR
                        var alumnos = [];

                        for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                contador++;
                            
                        }

                        if ( alumnos.length == 0 ) {
                          swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                        }else{

                            $("#modal_cambio_generacion").modal('show');

                            $.ajax({
                                url: 'server/obtener_cambio_generacion_alumnos.php',
                                type: 'POST',
                                data: { alumnos },
                                success: function(respuesta){
                                  $("#contenedor_cambio_generacion").html(respuesta);
                                }
                            });
                          
                        }
                    });
                    

                    // FIN CAMBIO DE GENERACION



                    // AGREGAR PROGRAMA
                    $("#btn_programa").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        console.log('cambio prog');
                        // VARIABLES
                        //ARREGLO DE ALUMNOS POR INSCRIBIR
                        var alumnos = [];

                        for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                contador++;
                            
                        }

                        if ( alumnos.length == 0 ) {
                          swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                          
                        }else{

                            $("#modal_agregar_programa").modal('show');

                            $.ajax({
                                url: 'server/obtener_agregar_programa_alumnos.php',
                                type: 'POST',
                                data: { alumnos },
                                success: function(respuesta){
                                  $("#contenedor_agregar_programa").html(respuesta);
                                }
                            });
                          
                        }
                    });

                    // FIN AGREGAR PROGRAMA

                    // CONSULTA DOCUMENTACION
                    $('.consultaDocumentacion').on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        var id_alu_ram = $(this).attr("id_alu_ram");
                        var nombreAlumno = $(this).attr("alumno");

                        // console.log(alumno);

                        // VALIDACION PERMISOS

                        swal({
                          title: "¡Acceso Restringido!",
                          icon: "warning",
                          text: 'Necesitas permisos de administrador para continuar',
                          content: {
                            element: "input",
                            attributes: {
                              placeholder: "Ingresa una contraseña...",
                              type: "password",
                            },
                          },

                          button: {
                            text: "Validar",
                            closeModal: false,
                          },
                        })
                        .then(name => {
                          if (name){
                            //console.log(name);
                            var password = name;
                            $.ajax({
                                    
                                url: 'server/validacion_permisos.php',
                                type: 'POST',
                                data: {password},
                                success: function(respuesta){
                                    //console.log(respuesta);

                                    if (respuesta == 'True') {

                                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                        then((value) => {
                                            //
                                            //console.log("Existe el password");

                                            $('#modal_consulta_documentacion').modal('show');

                                            $('#titulo_consulta_documentacion').html( '<i class="fas fa-file-archive"></i> Alumno: '+nombreAlumno );


                                            $.ajax({
                                                url: 'server/obtener_documentacion_alumno.php',
                                                type: 'POST',
                                                data: { id_alu_ram },
                                                success: function( respuesta ){

                                                    $('#contenedor_consulta_documentacion').html( respuesta );

                                                }
                                            });
                                            
 
                                        });
                                        
                                    }else{
                                        // LA CONTRASENA NO EXISTE
                                        
                                        swal({
                                          title: "¡Datos incorrectos!",
                                          text: 'No existe la contraseña...',
                                          icon: "error",
                                          button: "Aceptar",
                                        });
                                        swal.stopLoading();
                                        swal.close();

                                        
                                    }
                                }
                            });

                          }else{
                            // DATOS VACIOS
                            swal({
                              title: "¡Datos vacíos!",
                              text: 'Necesitas ingresar una contraseña...',
                              icon: "error",
                              button: "Aceptar",
                            });
                            swal.stopLoading();
                            swal.close();
                          }
                          
                        });

                        // FIN VALIDACION PERMISOS

                    });


                    

                    // FIN CONSULTA DOCUMENTACION



                      $("#btn_seleccion_alumnos").on('click', function(event) {
                        event.preventDefault();
                        /* Act on the event */

                        for( var i = 0, contador = 0, contador2 = 0; i < $(".seleccionAlumno").length ; i++ ){

                          if ( $(".seleccionAlumno")[i].checked == true ) {

                            //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                            toastr.info( $(".seleccionAlumno").eq(i).attr("nombre_alumno") );
                            contador++;
                           
                          }

                        }

                      });

                      // FIN PREVIEW SELECCION


                      // ENVIO DE MENSAJES
                        $("#btn_mensaje_alumnos").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            console.log('clicl');
                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnos = [];

                            for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                    //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                    alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                    contador++;
                                
                            }

                            if ( alumnos.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                                $("#modal_mensaje_alumnos").modal('show');

                                

                                obtener_seleccion_envio();

                                $('.radiosSeleccionEnvio').on('change', function(event) {
                                    event.preventDefault();
                                    /* Act on the event */

                                    obtener_seleccion_envio();
                                });
                                function obtener_seleccion_envio() {
                               
                                    radiosSeleccionEnvio = $(".radiosSeleccionEnvio:checked").val();
                                
                                    if ( radiosSeleccionEnvio == 'Mensaje' ) {
                                        
                                        $.ajax({
                                            url: 'server/obtener_mensaje_alumnos.php',
                                            type: 'POST',
                                            data: { alumnos },
                                            success: function(respuesta){
                                                // console.log( respuesta );
                                                $("#contenedor_principal_mensaje_alumnos").html(respuesta);

                                                $('#contenedor_tipo_mensaje').html('<div class="form-group basic-textarea" style="position: relative;"><textarea class="form-control pl-2 my-0" rows="3" placeholder="Escribe un mensaje..." id_usuario="" id="mensaje_alumnos" soy="" sala="" required=""></textarea> </div>');

                                                $("#mensaje_alumnos").emojioneArea({
                                            
                                                    pickerPosition: "top",

                                                    events: {
                                                      keyup: function(editor, event) {
                                                        // catches everything but enter
                                                        if (event.which == 13) {
                                                            // console.log('if');
                                                            enviar_mensaje_alumnos();
                                                          // return false;
                                                        } else {
                                                            console.log('else');
                                                        }

                                                      }
                                                    }
                                                
                                                });

                                                $('#btn_enviar_mensaje_alumnos').on('click', function(event) {
                                                    event.preventDefault();
                                                    /* Act on the event */
                                                    enviar_mensaje_alumnos();
                                                });

                                            }
                                        });
                                        
                                        


                                    } else if ( radiosSeleccionEnvio == 'Archivo' ) {
                                        // alert( 'Archivo' );

                                        // $('#contenedor_btn_enviar_mensaje_alumnos');

                                      

                                        // $('#barra_estado_mensaje').removeAttr('tipo').attr('tipo', 'archivo');

                                        // $.ajax({
                                        //     url: 'server/obtener_formulario_archivo.php',
                                        //     type: 'POST',
                                        //     data: { alumnos },
                                        //     success: function( respuesta ){
                                        //         // console.log( respuesta );
                                        //         $("#contenedor_principal_mensaje_alumnos").html(respuesta);
                                        //         $('.file_upload').file_upload();


                                        //     }
                                        // });
                                        

                                    }

                                }

                                // ENVIAR MENSAJE
                                function enviar_mensaje_alumnos(){

                                    // 
                                        swal({
                                          title: "¿Deseas enviar este mensaje a estos "+$(".seleccionAlumnoFinal").length+" alumnos?",
                                          text: "¡Podrás revisarlo en el área de mensajería más tarde!",
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

                                            $(".eliminacionSeleccionAlumnoFinal").remove();
                                            let barra_estado_mensaje = $("#barra_estado_mensaje");
                                            var porcentaje;
                                            var contador;

                                            radiosSeleccionEnvio = $(".radiosSeleccionEnvio:checked").val();
                                    
                                            if ( radiosSeleccionEnvio == 'Mensaje' ) {

                                                var mensaje = $("#mensaje_alumnos").data("emojioneArea").getText();

                                                for(var i = 0, tipo_usuario = 'Alumno'; i < $(".seleccionAlumnoFinal").length; i++){
                                                
                                                    var id_usuario = $('.seleccionAlumnoFinal').eq(i).attr("id_alu");
                                                    var id_sal = $('.seleccionAlumnoFinal').eq(i).attr("id_sal");
                                                    
                                                    $.ajax({
                                                        ajaxContador: i,
                                                        url: 'server/agregar_mensaje.php',
                                                        type: 'POST',
                                                        data: { id_usuario, tipo_usuario, id_sal, mensaje },
                                                        beforeSend: function(){

                                                            $("#btn_enviar_mensaje_alumnos").removeClass('btn-info').addClass('btn-secondary disabled').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');

                                                        }
                                                    }).done(function(respuesta) {
                                                        //console.log(respuesta);

                                                        if ( $(".seleccionAlumnoFinal").eq(this.ajaxContador).attr("id_alu") == respuesta ) {
                                                            $(".seleccionAlumnoFinal").eq(this.ajaxContador).addClass('light-green accent-4 white-text');
                                                        }

                                                        contador = this.ajaxContador + 1;
                                                        porcentaje = Math.floor( contador*(100/$(".seleccionAlumnoFinal").length), 2 );
                                                        

                                                        if (porcentaje <= 100) {
                                                            
                                                            barra_estado_mensaje.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
                                                                
                                                            barra_estado_mensaje.text(porcentaje+'%');
                                                            
                                                            if (porcentaje == 100) {
                                                                barra_estado_mensaje.removeClass();
                                                                barra_estado_mensaje.addClass('progress-bar text-center white-text bg-success');
                                                                barra_estado_mensaje.text("Listo");
                                                                $(".seleccionAlumnoFinal").eq(i).addClass('light-green accent-4 white-text');

                                                                $("#btn_enviar_mensaje_alumnos").removeClass('btn-secondary').addClass('light-green accent-4 white-text').html('<i class="fas fa-check white-text"></i> <span>Enviar</span>');

                                                                swal("Envío de mensaje exitoso", "Continuar", "success", {button: "Aceptar",}).
                                                                then((value) => {
                                                                  
                                                                  $("#btn_enviar_mensaje_alumnos").removeClass('disabled light-green accent-4 white-text').addClass('btn-info');

                                                                    var el = $("#mensaje_alumnos").emojioneArea();//REEMPLAZO DEL CLASICO .val("")
                                                                    el[0].emojioneArea.setText(''); // clear input 

                                                                  // $("#modal_mensaje_alumnos").modal("hide");




                                                                });
                                                            }

                                                        }
                                                        
                                                    });


                                                }
                                                // BUCLE FOR

                                                obtener_seleccion_envio();

                                            }
                                            
                                          }
                                        });
                                    // 
                                    

                                    

                                }
                                // FIN ENVIAR MENSAJE
                                
                                
                              
                            }
                        });

                      // FIN DE ENVIO DE MENSAJE

                        $("#btn_pago_alumnos").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            console.log('pagos');
                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnos = [];

                            for( var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++ ){

                                    //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                    alumnos[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                    contador++;
                                
                            }

                            if ( alumnos.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                                $("#modal_pago_alumnos").modal('show');

                                $.ajax({
                                    url: 'server/obtener_pago_alumnos.php',
                                    type: 'POST',
                                    data: { alumnos },
                                    success: function(respuesta){
                                      $("#contenedor_pago_alumnos").html(respuesta);
                                    }
                                });
                              
                            }
                        });





                        // INSCRIPCION

                        $("#btn_inscripcion").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            
                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnosInscripcion = [];
                            var alumnosValidador = [];
                            var alumnosValidadorAdeudo = [];
                            var alumnosDeudores = [];
                            var alumnosNombresInscripcion = [];

                            
                            for( var i = 0, contador = 0, contador2 = 0; i < $(".pegadoSeleccionAlumno").length ; i++ ){


                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnosInscripcion[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                alumnosValidador[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_ram");
                                alumnosValidadorAdeudo[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("estatus_pago");
                                alumnosNombresInscripcion[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("nombre_alumno");

                                contador++;
                               

                            }





                            if ( alumnosInscripcion.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                              var validador = true;
                              for ( var i = 0 ; i < alumnosValidador.length ; i++ ) {
                                for ( var j = 0 ; j < alumnosValidador.length ; j++ ) {

                                  if ( alumnosValidador[j] != alumnosValidador[i] ) {
                                        // console.log( alumnosValidador[j] != alumnosValidador[i] );
                                    validador = false;
                                    break;
                                    break;
                                    break;
                                  }
                                }
                              }


                              var validadorAdeudo = true;
                              for ( var i = 0, j = 0 ; i < alumnosValidadorAdeudo.length ; i++ ) {
                                
                                if ( ( alumnosValidadorAdeudo[i] == 'Con adeudo' )  && ( validadorAdeudo == true ) ) {
                                  // console.log( alumnosValidador[j] != alumnosValidador[i] );
                                  validadorAdeudo = false; 
                                }

                                if ( alumnosValidadorAdeudo[i] == 'Con adeudo' ) {
                                  alumnosDeudores[j] = alumnosNombresInscripcion[i];
                                  j++;
                                }
                              }

                              if ( validador == true ) {


                                if ( validadorAdeudo == true ) {

                                  var id_ram = alumnosValidador[0];
                                  var validadorGlobal = 1;

                                  $("#modalInscripcion").modal('show');

                                  $("#panzaModalInscripcion").html( '<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>' );

                                  $.ajax({
                                    url: 'server/obtener_inscripcion_alumnos.php',
                                    type: 'POST',
                                    data: { alumnosInscripcion, id_ram, validadorGlobal },
                                    success: function( respuesta ) {
                                      
                                      $("#panzaModalInscripcion").html(respuesta);
                                    }
                                  });

                                } else if ( validadorAdeudo == false ) {

                                    error.play();
                                  for ( var i = 0 ; i < alumnosDeudores.length ; i++ ) {
                                    toastr.warning(alumnosDeudores[i]+' presenta adeudo, NO se puede inscribir');
                                  }
                                  swal("¡Error en selección de alumnos!", "Para continuar, asegúrate de que los alumnos no adeuden pagos", "info", {button: "Aceptar",});
                                }
                                


                              } else if ( validador == false ) {
                                swal("¡Error en selección de alumnos!", "Para continuar, asegúrate de que los alumnos pertenezcan al mismo programa", "info", {button: "Aceptar",});  
                              }

                              
                            }

                            

                        });


                        // BAJA
                        $("#btn_baja").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            // VARIABLES
                            //ARREGLO DE ALUMNOS POR INSCRIBIR
                            var alumnosInscripcion = [];

                            for(var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno").length; i++){

                                //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
                                alumnosInscripcion[contador] = $(".pegadoSeleccionAlumno").eq(i).attr("id_alu_ram");
                                contador++;

                            }

                            if ( alumnosInscripcion.length == 0 ) {
                              swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
                            }else{

                              $("#modalBaja").modal('show');
                              var validadorGlobal = 1;

                              $.ajax({
                                url: 'server/obtener_alumnos_baja_materias.php',
                                type: 'POST',
                                data: {alumnosInscripcion, validadorGlobal},
                                success: function(respuesta){
                                  $("#panzaModalBaja").html(respuesta);
                                }
                              });
                              
                            }
                        });


                        // MODAL ALUMNO
                        $(".consultaAlumno").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            var id_alu_ram = $(this).attr("id_alu_ram");
                            $.ajax({
                              url: 'server/obtener_consulta_alumno.php',
                              type: 'POST',
                              data: { id_alu_ram },
                              success: function( respuesta ){
                                $("#modalConsultaAlumno").modal( 'show' );
                                $("#contenedorConsultaAlumno").html( respuesta );
                              }
                            });
                            
                        });
                        // FIN MODAL ALUMNO


                        //CONSULTA DE HORARIO ALUMNO

                        $(".horarioAlumno").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            var edicionAlumno = $(this).attr("id_alu");
                            var rama = $(this).attr("id_ram");
                            var id_alu_ram = $(this).attr("id_alu_ram");

                            $.ajax({
                              url: 'server/obtener_alumno.php',
                              type: 'POST',
                              dataType: 'json',
                              data: {edicionAlumno, rama},
                              success: function(datos){

                                $("#modalHorarioAlumno").modal('show');
                                $('#tituloModalHorarioAlumno').html('<img src="../uploads/'+datos.fot_alu+'" class="img-fluid avatar rounded-circle" width="40px" height="40px"> '+"Horario de "+datos.nom_alu+" "+datos.app_alu);

                                $.ajax({
                                  url: 'server/obtener_horario_alumno.php',
                                  type: 'POST',
                                  data: {id_alu_ram},
                                  success: function(respuesta){
                                    $("#panzaModalHorarioAlumno").html(respuesta);
                                  }
                                });
                                
                              }
                            });

                        });


                          // CONSULTA DE ACTIVIDADES
                            // ACTIVIDADES ALUMNO
                          $(".actividadesAlumno").on('click', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            console.log('click');

                            var id_alu_ram = $(this).attr("id_alu_ram");
                            
                            $.ajax({
                              url: 'server/obtener_actividades_alumno.php',
                              type: 'POST',
                              data: { id_alu_ram },
                              success: function( respuesta ){
                                $("#modalActividadesAlumno").modal( 'show' );
                                $("#contenedorActividadesAlumno").html( respuesta );
                              }
                            });
                            
                          });
                
                // FIN FUNCION LOAD REGISTROS NUEVOS
                },





                


                
             // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

             "language": {
                    "sProcessing": '<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>',
                      "sLengthMenu":     "Mostrar _MENU_ registros",
                      "sZeroRecords":    "No se encontraron resultados",
                      "sEmptyTable":     "Ningún dato disponible en esta tabla",
                      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                      "sInfoPostFix":    "",
                      "sSearch":         "Buscar:",
                      "sUrl":            "",
                      "sInfoThousands":  ",",
                      "sLoadingRecords": "Cargando...",
                      "oPaginate": {
                          "sFirst":    "Primero",
                          "sLast":     "Último",
                          "sNext":     "Siguiente",
                          "sPrevious": "Anterior"
                         },
                     "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                     }
                 }


         });
        
        



        // setTimeout(function(){
        //     $('#tabla_alumnos tr').find('td').each(function () {
        //         $(this).addClass('letraPequena');
        //     });
        // }, 500 );

        // SCROLL TOP AND BOTTOM
        $('.dataTables_scrollHead').css({
            'overflow-x':'scroll'
        }).on('scroll', function(e){
            var scrollBody = $(this).parent().find('.dataTables_scrollBody').get(0);
            scrollBody.scrollLeft = this.scrollLeft;
            $(scrollBody).trigger('scroll');
        });
        // SCROLL TOP AND BOTTOM
        
    }
</script>


<script>
    $(".button-collapse").sideNav();
    new WOW().init();


    function generarAlerta( mensaje ){

      $( 'body' ).append(

        '<div class="animated fadeInDown alerta" style=" z-index: 100000; background: black; border-radius: 10px; width: 250px; height: 35px; position: fixed; right: 0%; bottom: 5%; ">' +
          '<p style=" color: white; padding: 8px; font-size: 13px; text-align: center; ">' +
            '<i class="fas fa-check"></i> ' + mensaje +
          '</p>' +
        '</div>'
      );


      $( '.alerta' ).removeClass( 'animated fadeInDown' ).addClass( 'animated fadeOut' );

    }

</script>

<?php  
	if ( $tipo != 'Super' ) {
?>

<style>
  #formulario_agregar_alumno input{
    font-size: 12px;

    color: #4B515D;
  }

  #formulario_agregar_alumno label{
    font-size: 12px;
  }
</style>

<!-- EGRESOS -->

  <!-- MODALES -->

  <!-- Full Height Modal Subida de aviso -->
<div class="modal fade right" id="modal_aviso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right" role="document">


      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Sube un aviso</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span style="color:black;"><p>Sube una imagen para que el alumno vea en su inicio y este enterado de lo que necesites.</p></span>
          <span style="color:red;"><p><strong>NOTA: Esta función está limitada a SOLO UN AVISO.</strong></p></span>

                <form enctype="multipart/form-data" id="upload_aviso">
                            <br>

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="md-form">
                                <i class="fas fa-info prefix"></i>
                                  <label for="imagen_aviso" class="form-label" style="top: -1.75rem;">Sube el aviso</label>
                                  <input class="form-control form-control-sm" id="imagen_aviso" type="file" />
                              </div>
                                </div>
                              </div>
                              <div class="row">
                                
                                <div class="col-md-12">
                                  <!-- Material input -->
                              <div class="md-form">
                                <i class="fas fa-info prefix"></i>
                                  <input type="text" id="descripcion" name="descripcion" class="form-control">
                                  <label for="descripcion">Dale una descripción <strong>BREVE</strong> a tu imagen</label>
                              </div>
                              <?php if ($tipo == 'Adminge'){ ?>
                                <div class="md-form">
                                  <i class="fas fa-info prefix"></i>
                                    <input type="text" id="liga" name="liga" class="form-control">
                                    <label for="liga">Agrega un<strong>LINK</strong> a tu imagen</label>
                                </div>
                              <?php }?>
                                </div>
                              </div>                      
                    </form>

                    <div class="row">     
                                <div class="col-md-12">
                                  <h4><span id = "mensaje" class="text-success" style="display: none;"></span></h4>                            
                                </div>
                              </div> 
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="carga_aviso">Subir Aviso</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Full Height Modal Subida de aviso-->

  <!-- MODAL -->
  <!-- MODAL PAGOS -->

  <div class="modal fade" id="modal_pago_alumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">


    <div class="modal-dialog modal-lg" role="document">


      <div class="modal-content">
        <div class="modal-header grey darken-1 white-text text-center">
          <h4 class="modal-title w-100">Creación de pago</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


          <div id="contenedor_pago_alumnos" class="modal-body">
            
          </div>

            
          
          
          
      </div>
    </div>
  </div>

  <!-- FIN MODAL PAGOS -->
  <!-- FIN MODAL -->


  <!-- INSCRIPCION MULTIPLE MODAL -->

    <!-- Central Modal Small -->
    <div class="modal fade" id="modalInscripcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">

      <!-- Change class .modal-sm to change the size of the modal -->
      <div class="modal-dialog modal-lg" role="document">


        <div class="modal-content">
          <div class="modal-header grey darken-1 white-text text-center">
            <h4 class="modal-title w-100" id="myModalLabel">Inscripción</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="panzaModalInscripcion">
            


            


          </div>
          <div class="modal-footer">
                <button type="button" class="btn btn-info btn-sm btn-rounded" title="Concluye el proceso de inscripción" id="btn_finalizar">Finalizar</button>
                <button type="button" class="btn grey white-text btn-sm btn-rounded" data-dismiss="modal">Salir</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Central Modal Small -->

    <!-- FIN INSCRIPCION MULTIPLE MODAL -->
  
  <div class="modal fade" id="modal_egresos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
    aria-hidden="true">

      <form id="formulario_egresos" class="form-control">

        <div class="modal-dialog" role="document">

          <div class="modal-content" style="border-radius: 20px;">
              <div class="modal-header text-center">
                
                <h4 class="modal-title w-100" id="myModalLabel2">
                  <span class="fa-stack fa-1x">

                    <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i>
                    <i class="far fa-circle fa-stack-2x animated pulse infinite"></i>
                  </span> 
                  Agregar egreso/fondeo
                </h4>


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

              </div>

              <br>

              <div class="modal-body">
                
                <div class="alert alert-warning" role="alert">
                 
                  <span>
                    Puedes consultar todos los egresos del plantel haciendo click <a href="egresos.php" target="_blank" class="btn-link text-primary">aquí</a> 
                  </span>
                </div>


                <input type="hidden" id="res2_egr" name="res2_egr">
                
                <div class="row">
                    <div class="col-md-12">
                        <select class="browser-default custom-select" id="tip_egr" name="tip_egr">
                            <option value="Egreso" selected="">Egreso</option>
                            <option value="Fondeo">Fondeo</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="md-form">
                  <i class="fas fa-info prefix"></i>
                    <input type="text" id="con_egr" name="con_egr" class="form-control">
                    <label for="con_egr">Ingresa concepto...</label>
                </div>
                  </div>

                  <div class="col-md-6">
                    <!-- Material input -->
                <div class="md-form">
                  <i class="fas fa-dollar-sign prefix"></i>
                    <input type="number" id="mon_egr" name="mon_egr" class="form-control">
                    <label for="mon_egr">Ingresa cantidad...</label>
                </div>
                  </div>
                </div>



                <div class="row">
                  <div class="col-md-12">
                    <div class="md-form">
                      <i class="fas fa-user-shield prefix"></i>
                    <input type="text" id="res_egr" name="res_egr" class="form-control">
                    <label for="res_egr">Nombre del solicitante...</label>
                </div>
                  </div>

                  
                </div>
                



            
              

              </div>

              
              
              <div class="modal-footer d-flex justify-content-center">
              
                <button class="btn btn-primary white-text btn-rounded btn-sm" type="submit" title="Guardar cliente" id="btn_formulario_egresos">
                  Guardar
                </button>
                
                <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                    Cancelar
                </a>

            </div>

          </div>
        
        </div>

      </form>
      
  
  </div>

  <!-- FIN MODALES -->
<!-- FIN EGRESOS -->

    <!-- MODAL ALUMNO -->
    <div class="modal fade text-left " id="modal_agregar_alumno">
        
        <div class="modal-dialog modal-lg" role="document">

          <div class="modal-content " style="border-radius: 20px;">
            
            <div class="modal-header " style="position: relative;">
              
              <div class="row">
                <div class="col-md-12">
                
                  <span class="grey-text letraGrande">  
                      Solicitud de inscripción
                  </span>


                </div>


              </div>
            


              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
                <span aria-hidden="true">&times;</span>
              </button>

            </div>
            
            <form id="formulario_agregar_alumno">
              
              <div class="modal-body mx-3">

                <!-- DATOS GENERALES -->
                <div style="background: grey; height: 30px;">
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Datos Generales
                  </span>
                </div>


                <!-- DATOS PERSONALES -->
                <div class="row">
                  
                  <!-- FOTO -->
                  <div class="text-center col-md-3">

                    <br>

                    <img src="../img/usuario.jpg" alt="avatar" class="rounded-circle img-fluid" style="border-style: solid; width: 105px; height: 105px;" id="contenedor_imagen">
                    
                    <div class="md-form" > 
                      <div class="file-field">
                        

                        <div class="file-path-wrapper"> 
                          <input class="file-path  letraPequena disabled" type="text" style="font-size: 8px;" placeholder="Sube un archivo en JPG, JPEG o PNG"> 
                        </div>

                        <br>

                        <div class="btn btn-info btn-sm float-left btn-block btn-rounded waves-effect">
                          <span>Elige un archivo</span>
                          <input type="file" id="fot_alu" name="fot_alu"> 
                        </div>
                        
                      </div>
                    </div>


                    <br>

                    <div class="text-left" style="display: none;">
            
                      <div class="md-form mb-5"> 
                        <div class="form-check"> 
                          <input type="radio" class="form-check-input radio_pro_alu" id="checkbox_origen_alumno" name="pro_alu" checked value="Población general"> 
                          <label class="form-check-label" for="checkbox_origen_alumno">Población general</label>
                        </div>


                        <div class="form-check"> 
                          <input type="radio" class="form-check-input radio_pro_alu" id="checkbox_origen_alumno2" name="pro_alu" value="Empresa"> 
                          <label class="form-check-label" for="checkbox_origen_alumno2">Empresa</label>
                        </div>





                      </div>
                      <!-- ORIGEN ALUMNO -->
                      <div id="contenedor_origen_alumno">
                        
                        

                      </div>
                      <!-- FIN ORIGEN ALUMNO -->
                    </div>

                  
                  </div>
                  <!-- FIN FOTO -->


                  <!-- DATOS PERSONALES -->
                  <div class="col-md-9">

                    <div class="row">
                      
                      <div class="col-md-6">
                        
                        <p class="grey-text letraPequena">
                          ¡Todos los campos con * son obligatorios! 
                        </p>
                      
                      </div>

                    </div>


                    
                    
                    
                    <!-- NOMBRE -->
                    <div class="row">

                      <div class="col-md-4">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="nom_alu" name="nom_alu" class="form-control correoCompuesto" required="">
                          <label for="nom_alu">*Nombre</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="app_alu" name="app_alu" class="form-control correoCompuesto" required="">
                          <label  for="app_alu">*Apellído paterno</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="apm_alu" name="apm_alu" class="form-control" required="">
                          <label  for="apm_alu">*Apellído materno</label>
                        
                        </div>

                      </div>

                    </div>
                    <!-- FIN NOMBRE -->

                    <!-- FECHA DE NACIMIENTO Y GENERO -->
                    <div class="row">
                      
                      <div class="col-md-6" style="position: relative;">

                        <label style="position: absolute; top: 10px;" class="grey-text">Fecha de Nacimiento</label>
                        <div class="md-form mb-5">
                          <input type="date" id="nac_alu" name="nac_alu" class="form-control " required>
                        </div>
                        
                      </div>


                      <div class="col-md-6">
                        <br>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input generoAlumno" id="materialInline1" name="generoAlumno" value="Mujer" checked>
                            <label class="form-check-label" for="materialInline1">Mujer</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input generoAlumno" id="materialInline2" name="generoAlumno" value="Hombre">
                            <label class="form-check-label" for="materialInline2">Hombre</label>
                        </div>

                      </div>

                    </div>
                    <!-- FIN FECHA DE NACIMIENTO Y GENERO -->

                    <!-- CURP Y LUGAR DE NACIMIENTO -->
                    <div class="row">
                      
                      <div class="col-md-4" style="display: none;">
                        
                        <div class="md-form mb-5">
                            
                          <input type="text" id="lug_alu" name="lug_alu" class="form-control" value="Pendiente">
                          <label  for="lug_alu">Lugar de nacimiento</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        
                        <div class="md-form">

                          <input type="text" id="tel_alu" name="tel_alu" maxlength="30" class="form-control " required="">
                          <label id="tel_alu"  for="tel_alu">*Teléfono</label>
                        
                        </div>

                      </div>

                      <div class="col-md-8">
                        
                        <div class="row">
                        
                          <div class="col-md-12" style="position: relative;">
                        
                            <div class="md-form mb-5">
                            
                              <input type="text" id="cur_alu" name="cur_alu" class="form-control ">
                              <label  for="cur_alu">CURP</label>
                            
                            </div>

                            <span id="validacion_curp_frontend" class="grey-text letraPequena" style="position: absolute; bottom: 30px;">
                              
                            </span>
                          </div>

                        
                        </div>
                      
                      </div>
                      
                    
                    </div>
                    <!-- FIN CURP Y LUGAR DE NACIMIENTO  -->


                    <!-- ESTADO CIVIL -->
                    <div class="row" style="height: 50px; position: relative; display: none;">
                      <span class="grey-text letraMediana" style="position: absolute; top: -15px;">
                        Estado civil
                      </span>

                      <div class="col-md-2 border">

                        <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input estadoCivilAlumno" id="estadoCivilAlumno1" name="estadoCivilAlumno" checked>
                          <label class="form-check-label" style="font-size: 8px;" for="estadoCivilAlumno1">SOLTERA(O)</label>
                        </div>
                       
                      </div>


                      <div class="col-md-2 border">

                        <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input estadoCivilAlumno" id="estadoCivilAlumno2" name="estadoCivilAlumno">
                          <label class="form-check-label" style="font-size: 8px;" for="estadoCivilAlumno2">CASADA(O)</label>
                        </div>
                       
                      </div>


                      <div class="col-md-3 border">

                        <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input estadoCivilAlumno" id="estadoCivilAlumno3" name="estadoCivilAlumno">
                          <label class="form-check-label" style="font-size: 8px;" for="estadoCivilAlumno3">UNIÓN LIBRE</label>
                        </div>
                       
                      </div>



                      <div class="col-md-3 border">

                        <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input estadoCivilAlumno" id="estadoCivilAlumno4" name="estadoCivilAlumno">
                          <label class="form-check-label" style="font-size: 8px;" for="estadoCivilAlumno4">DIVORCIADA(O)</label>
                        </div>
                       
                      </div>


                      <div class="col-md-2 border">

                        <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input estadoCivilAlumno" id="estadoCivilAlumno5" name="estadoCivilAlumno">
                          <label class="form-check-label" style="font-size: 8px;" for="estadoCivilAlumno5">VIUDA(O)</label>
                        </div>
                       
                      </div>

                    </div>

                    <!-- FIN ESTADO CIVIL -->


                    <!-- OCUPACION -->
                    <div class="row" style="display: none;">
                      
                      <div class="col-md-12">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="ocu_alu" name="ocu_alu" class="form-control" value="PENDIENTE">
                          <label id="ocu_alu"  for="ocu_alu">Ocupación</label>
                        
                        </div>
                      
                      </div>
                      
                    </div>
                    <!-- FIN OCUPACION -->

                    <!-- TELEFONOS -->

                    <div class="row" style="display: none;">
                      
                      

                      <!-- CONTACTO TUTOR -->
                      <div class="col-md-6">
                        <div class="md-form">
                          <input type="text" id="tel2_alu" name="tel2_alu" class="form-control" value="PENDIENTE">
                          <label for="tel2_alu">Celular</label>
                        </div>
                      </div>
                      <!-- FIN CONTACTO TUTOR -->



                    </div>
                    <!-- FIN TELEFONOS -->
                   
                    <!-- CORREO ORIGINAL -->
                    <div class="row">

                      


                      <div class="col-md-8">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="cor1_alu" name="cor1_alu" class="form-control " required="">
                          <label id="cor1_alu"  for="cor1_alu">*Correo electrónico</label>
                        
                        </div>

                      </div>


                      <div class="col-md-4">
                        <br>
                        <a class="btn btn-info waves-effect btn-sm btn-rounded" title="Enviar un correo de prueba al correo electrónico que ingresaste" id="btn_correo_demo" href="#">
                          Enviar correo prueba
                        </a>

                      </div>

                    </div>

                    <!-- FIN CORREO ORIGINAL -->

                    <br>
                    <!-- CUENTA -->

                    <div class="row">

                      
                      <div class="col-md-4" style="position: relative;">
                          
                          <div style="position: absolute; top: -25px; ">

                            <input type="checkbox" class="form-check-input " id="checkboxMatriculaCompuesta" checked>
                            <label class="letraPequena font-weight-normal active grey-text" for="checkboxMatriculaCompuesta" title="Matrícula Compuesta (Formato: mmyy000000)">
                              Matrícula Compuesta
                            </label>
                            
                          </div>
                          

                          <div class="md-form mb-5" id="matriculaAlumno">

                            <input type="text" id="bol_alu" name="bol_alu" class="form-control  " value="">
                            <label  for="bol_alu" class="">*Matrícula</label>
                          </div>
                        
                      </div>


                      <div class="col-md-5" style="position: relative;">
                
                        <label id="output" style="position: absolute; top: -30px; "></label>
                            
                        <div class="md-form mb-5" id="contenedor_correo">
                          <input type="text" id="correo" class="form-control" name="correo">
                          <label for="correo">*Cuenta de acceso</label>
                        </div>

                      </div>


                      


                      <div class="col-md-3">
                        
                        <div class="md-form mb-5">

                          <input type="text" id="pas_alu" name="pas_alu" class="form-control " required="">
                          <label for="pas_alu" id="label_pas_alu">*Contraseña</label>
                        
                        </div>

                      </div>


                      

                    </div>
                    <!-- FIN CUENTA -->


                    
                  
                  </div>



                
                </div>
                <!-- FIN DATOS PERSONALES -->

                <!-- FIN DATOS GENERALES -->
                
                <hr>


                <!-- DOMICILIO -->
                <div style="background: grey; height: 30px; display: none;">
                  
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Domicilio
                  </span>

                </div>
                <div id="contenedor_datos_secundarios" style="display: none;">
                <!--  -->

                  <div class="form-check" title="Estos son datos secundarios, tú o el alumno pueden llenarlos más tarde...">
                      <input type="checkbox" class="form-check-input" id="checkboxAlumnoDatosSecundarios" checked>
                      <label class="form-check-label grey-text" for="checkboxAlumnoDatosSecundarios">
                      Llenar estos datos después
                    </label>
                  </div>

                  <!-- DIRECCION -->
                  <div class="row">

                    <div class="col-md-12">

                      <div class="md-form mb-5">
                        <input type="text" id="dir_alu" name="dir_alu" class="form-control ">
                        <label for="dir_alu">Dirección</label>
                      </div>
                      
                    </div>

                  </div>
                  
                  <!-- FIN DIRECCION -->

                  <!-- COLONIA -->
                  <div class="row">
                    
                    <div class="col-md-8">
                      
                      <div class="md-form mb-5">
                        <input type="text" id="col_alu" name="col_alu" class="form-control">
                        <label for="col_alu">Colonia</label>
                      </div>
                    
                    </div>


                    <!-- CP -->
                    <div class="col-md-4">
                      
                      <div class="md-form mb-5">
                        <input type="text" id="cp_alu" name="cp_alu" class="form-control">
                        <label  for="cp_alu">Código Postal</label>
                      </div>
                      
                    </div>
                    <!-- FIN CP -->

                 </div>
                  <!-- COLONIA -->


                  <div class="row">
                    
                    <!-- DELEGACION -->
                    <div class="col-md-6">

                      <div class="md-form mb-5">
                        <input type="text" id="del_alu" name="del_alu" class="form-control">
                        <label  for="del_alu">Delegación</label>
                      </div>

                    </div>
                    <!-- FIN DELEGACION -->

                    <!-- ENTIDAD -->
                    <div class="col-md-6">
                      
                      <div class="md-form mb-5">
                        <input type="text" id="ent_alu" name="ent_alu" class="form-control">
                        <label for="ent_alu">Entidad</label>
                      </div>

                    </div>
                    <!-- FIN ENTIDAD -->

                  </div>

                  
                  <!-- DISCAPACIDAD -->

                  <div class="row" style="height: 50px; position: relative; display: none;">
                    
                    <span class="grey-text letraMediana" style="position: absolute; top: -15px;">
                      Tiene alguna discapacidad
                    </span>

                    <div class="col-md-2 border">

                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input discapacidadAlumno" id="discapacidadAlumno6" name="discapacidadAlumno" checked>
                        <label class="form-check-label" style="font-size: 8px;" for="discapacidadAlumno6">NINGUNA</label>
                      </div>
                     
                    </div>

                    <div class="col-md-2 border">

                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input discapacidadAlumno" id="discapacidadAlumno1" name="discapacidadAlumno">
                        <label class="form-check-label" style="font-size: 8px;" for="discapacidadAlumno1">CAMINAR O MOVERSE</label>
                      </div>
                     
                    </div>


                    <div class="col-md-2 border">

                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input discapacidadAlumno" id="discapacidadAlumno2" name="discapacidadAlumno">
                        <label class="form-check-label" style="font-size: 8px;" for="discapacidadAlumno2">ESCUCHAR</label>
                      </div>
                     
                    </div>


                    <div class="col-md-2 border">

                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input discapacidadAlumno" id="discapacidadAlumno3" name="discapacidadAlumno">
                        <label class="form-check-label" style="font-size: 8px;" for="discapacidadAlumno3">VER</label>
                      </div>
                     
                    </div>



                    <div class="col-md-2 border">

                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input discapacidadAlumno" id="discapacidadAlumno4" name="discapacidadAlumno">
                        <label class="form-check-label" style="font-size: 8px;" for="discapacidadAlumno4">HABLAR O COMUNICARSE</label>
                      </div>
                     
                    </div>


                    <div class="col-md-2 border">

                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input discapacidadAlumno" id="discapacidadAlumno5" name="discapacidadAlumno">
                        <label class="form-check-label" style="font-size: 8px;" for="discapacidadAlumno5">PONER ATENCIÓN</label>
                      </div>
                     
                    </div>


                    


                  </div>
                  <!-- FIN DISCAPACIDAD -->



                  <!-- FIN DOMICILIO -->
                <!--  -->
                </div>
                
                <hr style="display: none;">

                
                <!-- ACADEMICO -->
                <div style="background: grey; height: 30px;">
                  
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Programa académico
                  </span>

                </div>

                <div class="row">

                  <!-- DATOS PROGRAMA -->
                  <div class="col-md-4">

                    <span>
                      Programa académico
                    </span>
                    
                    <div class="scrollspy-example" style=" height: 200px;">

                      <?php  
                        $sqlProgramas = "
                          SELECT *
                          FROM rama
                          WHERE id_pla1 = '$plantel' AND est_ram = 'Activo'
                          ORDER BY id_ram ASC
                        ";

                        $resultadoProgramas = mysqli_query( $db, $sqlProgramas );


                        $contadorProgramas = 1;

                   
                          while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                        ?>

                          <?php  
                            if ( $contadorProgramas == 1 ) {
                          ?>

                              <div class="form-check">
                                  <input type="radio" class="form-check-input programas" id="programaModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram[]" checked>
                                  <label class="form-check-label letraPequena font-weight-normal" for="programaModal<?php echo $contadorProgramas; ?>">
                                
                                    <?php echo $filaProgramas['nom_ram']; ?>

                                  </label>
                        
                              </div>

                          <?php
                            } else {
                          ?>
                              <div class="form-check">
                                  <input type="radio" class="form-check-input programas" id="programaModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram[]">
                                  <label class="form-check-label letraPequena font-weight-normal" for="programaModal<?php echo $contadorProgramas; ?>">
                                
                                    <?php echo $filaProgramas['nom_ram']; ?>

                                  </label>
                        
                              </div>

                          <?php
                            }
                          ?>
                          

                      <?php
                          $contadorProgramas++;
                        }
                        // FIN while
                      ?>
                    </div>
                    
                  </div>
                  <!-- FIN DATOS PROGRAMA -->


                  <!-- DATOS GENERACION -->
                  <div class="col-md-8">

                      Grupos
                      <div class="text-left scrollspy-example" style=" height: 200px;" id="contenedor_generaciones_modal">
                        
                      </div>
                    

                  </div>
                  <!-- DATOS ALUMNO FIN -->
                  

                </div>
                <!-- FIN ACADEMICO -->


                <hr style="display: none;">


                <!-- PAGOS -->
                <div style="background: grey; height: 30px;">
                  
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Calendario de inversiones  
                  </span>

                </div>


                <div id="contenedor_pagos_generacion">
                  
                </div>
                <!-- FIN PAGOS -->

                <hr style="display: none;">

                <!-- DOCUMENTACION -->
                <div style="background: grey; height: 30px; display: none;">
                  
                  <span class="white-text letraMediana font-weight-normal p-2">
                    Expediente del cliente
                  </span>

                </div>


                <div id="contenedor_documentacion_programa" style="display: none;">
                  
                </div>
                <!-- FIN DOCUMENTACION -->
                <hr style="display: none;">


                




                <div class="modal-footer d-flex justify-content-center">
                  
                  <button class="btn btn-info btn-rounded waves-effect btn-sm" title="Agregar alumno..." type="submit" id="btn_guardar_alumno">
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

    </div>

    <!-- FIN MODAL ALUMNO -->


    

    
    

    </div>
    <!-- FIN JUMBROTRON -->


    <!-- FORMULARIO ALUMNO -->


    <script>
      $('.radio_pro_alu').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */
        var origen = $('.radio_pro_alu:checked').val();

        if ( origen == 'Empresa' ) {
        
          $('#contenedor_origen_alumno').html('<div class="md-form mb-5"> <input type="text" id="pro_alu_input" class="form-control" required="" value="PENDIENTE"> <label for="pro_alu_input">*Nombre de la empresa</label> </div>');

          setTimeout(function(){
            $('#pro_alu_input').focus();
          }, 200);

        } else {
          
          $('#contenedor_origen_alumno').html('');
        
        }

        // if (  ) {}
        
      });
    </script>

    <script>
      // $('#modal_agregar_alumno').draggable();
      $('#btn_limpiar_formulario').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        error.play();
        $('#formulario_agregar_alumno input').val('');
        $('#contenedor_datos_secundarios label').removeClass('active');
        $('#contenedor_imagen').removeAttr('src').attr('src', '../img/usuario.jpg');

      });
      

      $('#btn_agregar_alumno').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $('#modal_agregar_alumno').modal('show');
        $("#contenedor_generaciones_modal").html("");
        obtener_generaciones();
        obtener_documentacion_programa();


        // MATRICULA COMPUESTA

        
        obtenerMatriculaCompuesta();

        $("#checkboxMatriculaCompuesta").on('change', function(event) {
          event.preventDefault();
          /* Act on the event */

          obtenerMatriculaCompuesta();
          
        });

        function obtenerMatriculaCompuesta(){
          if ($("#checkboxMatriculaCompuesta")[0].checked == true) {
            
            $("#bol_alu").val( '<?php $valor = generarMatriculaCompuestaServer( $plantel ); echo $valor; ?>' ).focus();

            var str = $("#bol_alu").val();
            var res = str.substr( (str.length-4), str.length );

            $('#pas_alu').val( res );

            $('#matriculaAlumno label').addClass('active');
            $('#label_pas_alu').addClass('active');
            
          }else{
            
            $("#bol_alu").val( '' ).focus();
            $('#pas_alu').val( '' );

            $('#matriculaAlumno label').removeClass('active');
            $('#label_pas_alu').removeClass('active');

          }
        }


      });
    </script>


    <script>

      $(".programas").on('click', function() {
        // event.preventDefault();
        /* Act on the event */

        obtener_generaciones();
        obtener_documentacion_programa();

        
      });

      function obtener_generaciones(){
        var ramas = [];
        for(var j = 0; j < $(".programas").length; j++){
          if ($(".programas")[j].checked == true) {
            ramas.push($(".programas").eq(j).val());
          }
        }

        // alert( ramas.length );


        $.ajax({
          url: 'server/obtener_generaciones_programa_formulario.php',
          type: 'POST',
          data: { ramas },
          success: function( respuesta ){
            
            $("#contenedor_generaciones_modal").html( respuesta );
            
            obtener_pagos_generacion();
          }
        });
      }


      

      function obtener_pagos_generacion(){

        var id_gen = $('.generaciones option:selected').val();

        $.ajax({
        
          url: 'server/obtener_pagos_generacion.php',
          type: 'POST',
          data: { id_gen },
          success: function( respuesta ){

            $('#contenedor_pagos_generacion').html( respuesta );

          }
        
        });
        
      }
    </script>


    <!-- DOCUMENTACION -->
    <script>
      function obtener_documentacion_programa(){
        var ramas = [];
        for(var j = 0; j < $(".programas").length; j++){
          if ($(".programas")[j].checked == true) {
            ramas.push($(".programas").eq(j).val());
          }
        }

        // alert( ramas.length );


        $.ajax({

          url: 'server/obtener_documentacion_programa.php',
          type: 'POST',
          data: { ramas },
          success: function( respuesta ){
            
            $("#contenedor_documentacion_programa").html( respuesta );
          
          }

        });
      
      }
    </script>
    <!-- FIN DOCUMENTACION -->

    <script>
    // FORMULARIO DATOS SECUNDARIOS
    obtener_checkbox_datos_secundarios();
      $("#checkboxAlumnoDatosSecundarios").on('click', function() {

          obtener_checkbox_datos_secundarios();

      });

      function obtener_checkbox_datos_secundarios(){

        if ( $('#checkboxAlumnoDatosSecundarios')[0].checked == true ) {
            // console.log("checkeado");
          $('#contenedor_datos_secundarios label').addClass('active');
          
          $('#nac_alu').val('<?php echo date('Y-m-d'); ?>');
          
          $('#dir_alu').val('PENDIENTE');
          $('#cp_alu').val('PENDIENTE');
          $('#col_alu').val('PENDIENTE');
          $('#del_alu').val('PENDIENTE');
          $('#ent_alu').val('PENDIENTE');
          
        }else{
          
          $('#contenedor_datos_secundarios label').removeClass('active');
          
          $('#nac_alu').val('');
          
          $('#dir_alu').val('');
          $('#cp_alu').val('');
          $('#col_alu').val('');
          $('#del_alu').val('');
          $('#ent_alu').val('');
          
        }
      }


    </script>


    <script>
      $('#fot_alu').on('change', function(event) {
        event.preventDefault();

        readURL(this);

      });


      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#contenedor_imagen')
              .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }
    </script>


    <script>
      $('.correoCompuesto').on('keyup', function(event) {
        /* Act on the event */

        var correo = obtenerCorreoCompuesto();
        
        $('#contenedor_correo label').addClass('active');
        
        validacionCorreoTiempoReal( correo );
        
      });


      function obtenerCorreoCompuesto(){

        return $('#correo').val( remove_accents( $('#nom_alu').val().trim()[0].replace(' ', '-').toLowerCase() ) +'.'+ remove_accents( $('#app_alu').val().trim().replace(' ', '').toLowerCase() ) +'@<?php echo $folioPlantel; ?>.com' );

      }



      function remove_accents(str){
        const map = {
          '-' : ' ',
          'a' : 'á|à|ã|â|ä|À|Á|Ã|Â|Ä',
          'e' : 'é|è|ê|ë|É|È|Ê|Ë',
          'i' : 'í|ì|î|ï|Í|Ì|Î|Ï',
          'o' : 'ó|ò|ô|õ|ö|Ó|Ò|Ô|Õ|Ö',
          'u' : 'ú|ù|û|ü|Ú|Ù|Û|Ü',
          'c' : 'ç|Ç',
          'n' : 'ñ|Ñ'
        };

        for (var pattern in map) {
          str = str.replace(new RegExp(map[pattern], 'g'), pattern);
        }

        return str;
      }


    </script>



    <script>
      
      $('#correo').on('keyup', function(event) {
        /* Act on the event */

        var correo = $('#correo').val();
        validacionCorreoTiempoReal( correo );

      });

      function validacionCorreoTiempoReal( correo ){
        console.log( correo );

        if (correo != '') {
          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: { correo },
            success: function(response){
              // console.log(  response );
              var respuesta = response; 


              if (respuesta == 'disponible') {
                
                $('#output').attr({
                  class: 'text-info letraPequena font-weight-normal'
                });
                $('#output').text("¡El correo electrónico está disponible!");

              }else{
                // correo = correo+'1';
                
                correo = correo.substring(0, correo.indexOf("@"))+'1';

                correo = correo+'@<?php echo $folioPlantel; ?>.com';
                $('#correo').val( correo );

                validacionCorreoTiempoReal( correo );
                
                // $('#output').attr({
                //   class: 'text-danger letraPequena font-weight-normal'
                // });
                // $('#output').text("¡El correo electrónico está ocupado!");

              }
            }
          })

        }else{
          $('#output').attr({class: 'text-warning letraPequena font-weight-normal'});
          $('#output').text("¡Ingresa un Correo Electrónico!");
        }
              
      }
    </script>





    <script>
      
      $('#btn_correo_demo').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        var correo = $('#cor1_alu').val();

        if (correo != '') {
          $.ajax({
            url: 'server/enviar_correo_demo.php',
            type: 'POST',
            data: { correo },
            success: function( respuesta ){
              // console.log(  response );

              generarAlerta('Correo enviado');


            }

          })

        }else{

          toastr.error('¡Correo vacío!');

        }


      });

    </script>


    <script>
      //VALIDACION CURP
      // $('#cur_alu').on('keyup', function(event) {
      //   event.preventDefault();
      //   /* Act on the event */

      //   var curp = $(this).val();

      //   var resultadoValidacionCurp = validarInput( curp );
      //   $('#validacion_curp_frontend').text("Formato: " + resultadoValidacionCurp );

      //   resultadoValidacionCurp = 'Valido';
      //   if ( resultadoValidacionCurp == 'Valido' ) {
          
      //     burbuja.play();
      //     $('#validacion_curp_backend').html( '<i class="fas fa-check fa-2x mb-3 animated rotateIn text-success"></i>' );

      //     // 

      //     validacionCurpTiempoReal( curp );

      //     function validacionCurpTiempoReal( curp ){
      //       console.log( correo );

      //       if (correo != '') {
      //         $.ajax({
      //           url: 'server/validacion_curp.php',
      //           type: 'POST',
      //           data: { curp },
      //           success: function(response){
      //             // console.log(  response );
      //             var respuesta = response; 


      //             if (respuesta == 'disponible') {
                    
      //               toastr.success('La CURP es válida');
      //               $("#btn_guardar_alumno").removeAttr('disabled').html('Guardar');
      //             }else{
      //               // correo = correo+'1';
                    
      //               toastr.error('La CURP ya existe');

      //               $("#btn_guardar_alumno").attr('disabled','disabled').html('Error en CURP...');               

      //             }

                
      //           }
              
      //         })

      //       }else{
      //         $('#output').attr({class: 'text-warning letraPequena font-weight-normal'});
      //         $('#output').text("¡Ingresa un Correo Electrónico!");
      //       }
                  
      //     }
      //     // 
        
      //   } else {

      //     error.play();
      //     $('#validacion_curp_backend').html( '<i class="fas fa-times fa-2x mb-3 animated rotateIn text-danger"></i>' );
      //     $("#btn_guardar_alumno").attr('disabled','disabled').html('Error en CURP...');

      //   }
        
        
      // });





      function curpValida(curp) {
        var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
            validado = curp.match(re);
      
        if (!validado)  //Coincide con el formato general?
          return false;
        
        //Validar que coincida el dígito verificador
        function digitoVerificador(curp17) {
            //Fuente https://consultas.curp.gob.mx/CurpSP/
            var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                lngSuma      = 0.0,
                lngDigito    = 0.0;
            for(var i=0; i<17; i++)
                lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
            lngDigito = 10 - lngSuma % 10;
            if (lngDigito == 10) return 0;
            return lngDigito;
        }
      
        if (validado[2] != digitoVerificador(validado[1])) 
          return false;
            
        return true; //Validado
      }


      function validarInput( curp ) {
        var curp = curp.toUpperCase();

          valido = "Valido";
        

            
        return valido;
      }

      // FIN VALIDACION CURP
    </script>


    <!-- SUBMIT -->
    <script>
      
      $('#formulario_agregar_alumno').on('submit', function(event) {
        event.preventDefault();

        console.log('click');


        var formulario_agregar_alumno = new FormData( $('#formulario_agregar_alumno')[0] );


        // PROCEDENCIA ALUMNO
        var origen = $('.radio_pro_alu:checked').val();

        if ( origen == 'Empresa' ) {
        
          formulario_agregar_alumno.append('pro_alu', $('#pro_alu_input').val() );

        } else {
          
          formulario_agregar_alumno.append('pro_alu', origen );

        }


        // GENERO ALUMNO
        var gen_alu = $('.generoAlumno:checked').val();

        formulario_agregar_alumno.append( 'gen_alu', gen_alu );

        var civ_alu = $('.estadoCivilAlumno:checked').val();

        formulario_agregar_alumno.append( 'civ_alu', civ_alu );


        var lim_alu = $('.discapacidadAlumno:checked').val();

        formulario_agregar_alumno.append( 'lim_alu', lim_alu );


        var id_doc_ram1 = [];
        var est_doc_alu_ram = [];
        
        for( var i = 0; i < $('.documentacionPrograma').length; i++ ){

          id_doc_ram1[i] = $('.documentacionPrograma').eq(i).val();
          
          if ( $('.documentacionPrograma')[i].checked == true ) {

            est_doc_alu_ram[i] = 'Entregado';

          } else {

            est_doc_alu_ram[i] = 'Pendiente';

          }

        }

        formulario_agregar_alumno.append( 'id_doc_ram1[]', id_doc_ram1 );
        formulario_agregar_alumno.append( 'est_doc_alu_ram[]', est_doc_alu_ram );
        

        // VALIDADOR CHECKBOX PROGRAMAS
        var validador_programas = 'falso';
        for ( var i = 0; i < $(".programas").length ; i++ ) {

          if ( ($(".programas")[i].checked == true) && ( $('.generaciones').length > 0 ) ) {
            // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxProgramas').eq(i).attr("annio")+" esta seleccionado"  );

            validador_programas = 'verdadero';
            break;
            break;
            
          }
        
        }
        // FIN VALIDADOR PROGRAMAS


        if ( validador_programas == 'verdadero' ) {

          $("#btn_guardar_alumno").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando');

          if ( $("#fot_alu")[0].files[0] ) {

            var fileName = $("#fot_alu")[0].files[0].name;
            var fileSize = $("#fot_alu")[0].files[0].size;

            var ext = fileName.split('.').pop();

            if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
              if (fileSize < 3000000) {

                $.ajax({
            
                  url: 'server/agregar_alumno.php',
                  type: 'POST',
                  data: formulario_agregar_alumno, 
                  processData: false,
                  contentType: false,
                  cache: false,
                  success: function(respuesta){
                  console.log(respuesta);

                    // if (respuesta == 'Exito') {
                      $("#btn_guardar_alumno").removeAttr('disabled').html('<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info').addClass('light-green accent-4');

                      swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                      then((value) => {

                        // obtenerAnnios();
                        // alert('Guardado');


                          
                        reloadTableGeneral();

                      
                      
                        

                        setTimeout(function(){
                          $("#btn_guardar_alumno").html('Guardar').removeClass('light-green accent-4').addClass('btn-info');
                        }, 2000 );

                        var correo = $('#correo').val();
                        validacionCorreoTiempoReal( correo );

                        $('#modal_agregar_alumno').modal('hide');

                      });
                      
                    
                  }
                });
              }else{
                swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
              }
              
            }else{
              swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
            }

          }else{

            //VALIDACION SI MANDA FOTO, EN CASO DE MANDAR VALIDA, SI NO, ACCEDE DIRECTAMENTE
            $.ajax({
            
              url: 'server/agregar_alumno.php',
              type: 'POST',
              data: formulario_agregar_alumno, 
              processData: false,
              contentType: false,
              cache: false,
              success: function(respuesta){
                console.log(respuesta);

                // if (respuesta == 'Exito') {
                  $("#btn_guardar_alumno").removeAttr('disabled').html('<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info').addClass('light-green accent-4');

                  swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                  then((value) => {
                    
                    // obtenerAnnios();

                    reloadTableGeneral();


                    setTimeout(function(){
                      $("#btn_guardar_alumno").html('Guardar').removeClass('light-green accent-4').addClass('btn-info');
                    }, 2000 );

                    var correo = $('#correo').val();
                    validacionCorreoTiempoReal( correo );

                    $('#modal_agregar_alumno').modal('hide');


                  });
                  
                // }
              }
            });

          }

        } else if ( validador_programas == 'falso' ) {
          swal ( "¡No hay programa académico o generación!" ,  "¡Asegúrate de elegir un programa académico y una generación!" ,  "error" );
          error.play();
        }

        
           

      });
    </script>
    <!-- FIN SUBMIT -->



    <!-- FIN FORMULARIO ALUMNO -->

    <!-- ******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************** -->



    
    
    <!-- MODAL -->
    <!-- Central Modal Medium Info -->
    <div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Usuarios</p>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="white-text">&times;</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <div class="text-center" id="contenedorUsuariosPlanteles">
              
            </div>
          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>
    <!-- Central Modal Medium Info-->



    <!-- FIN MODAL -->


    <script>
        //CALCULADORA
        function s(v) { document.getElementById('res').value = v }
        function a(v) { document.getElementById('res').value += v }
        function e() { try { s(eval(document.getElementById('res').value)) } catch(e) { s('Error') } }
    </script>

    <script>
        $( function() {



          $('#selectorRespaldo').materialSelect();


          $( '#selectorRespaldo' ).on('change', function(event) {
            event.preventDefault();
            /* Act on the event */


            var respaldo = $( '#selectorRespaldo option:selected' ).val();

            // alert( respaldo );

            

            $( "body" ).html('<h3 class="text-center grey-text" style=" position:fixed; left:40%; top:45%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando respaldo...</h3>');

            $.ajax({
              url: 'prueba3.php',
              type: 'POST',
              data: { respaldo },
              success: function( r ){
                console.log( r );

                window.location.reload();

              }
            });
            
          });


          $( '#btn_salida' ).on('click', function(event) {
            event.preventDefault();
            /* Act on the event */

            // $( "body" ).html('<h3 class="text-center grey-text" style=" position:fixed; left:40%; top:45%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando respaldo...</h3>');
            var estatus = '';
            
            $.ajax({
              url: 'prueba3.php',
              type: 'POST',
              data: { estatus },
              success: function( r ){
                console.log( r );

                window.location.reload();

              }
            });



          });




            
            


            $( "#calculadora").draggable();


            $("#btn_calculadora").on('click', function(event) {
                event.preventDefault();
                
                    $("#calculadora").removeAttr("style");   
                
            });


            $("#btn_calculadora_cerrar").on('click', function(event) {
                event.preventDefault();
                $("#calculadora").attr({"style": "display: none;"})
            });


            // ANIMACION * ICONOS ON HOVER

           $('i').on('mouseover', function(){
                $(this).addClass('animated rubberBand');

            });
            $('i').on('mouseleave', function(){
                $(this).removeClass('animated rubberBand');
            });

            $.fn.dataTable.moment( 'DD/MM/YYYY' );

            // VALIDACION SUPERADMIN
            $(document).bind('keydown', 'ctrl+m', function(){
                
                swal({
                    title: "¡Acceso Restringido!",
                    icon: "warning",
                    text: 'Necesitas permisos de SúperAdministrador para continuar',
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Ingresa un correo...",
                            type: "text",
                        },
                    },
                    button: {
                        text: "Validar",
                        closeModal: false,
                    },
                })
                .then(correo => {
                    if (correo){
                        //console.log(name);
                        var correo = correo;
                        $.ajax({
                                
                            url: '../../server/validacion_permisos_superadmin.php',
                            type: 'POST',
                            data: {correo},
                            success: function(respuesta){
                                console.log(respuesta);

                                if (respuesta == 'True') {
                                
                                    console.log("Existe el correo");
                                    // CORREO VALIDADO 
                                    swal({
                                        title: "¡Acceso Restringido!",
                                        icon: "warning",
                                        text: 'Necesitas permisos de SúperAdministrador para continuar',
                                        content: {
                                            element: "input",
                                            attributes: {
                                                placeholder: "Ingresa un password...",
                                                type: "password",
                                            },
                                        },
                                        button: {
                                            text: "Validar",
                                            closeModal: false,
                                        },
                                    })
                                    .then(password => {
                                        if (password){
                                            //console.log(name);
                                            var password = password;
                                            $.ajax({
                                                    
                                                url: '../../server/validacion_permisos_superadmin.php',
                                                type: 'POST',
                                                data: {password, correo},
                                                success: function(respuesta){
                                                    console.log(respuesta);

                                                    if (respuesta == 'True') {
                                                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                                        then((value) => {
                                                            //
                                                            console.log("Existe correo y password");
                                                            // PASSWORD Y CORREO VALIDADO 
                                                            $('#modalUsuarios').modal('show');
                                                            $.ajax({
                                                                url: '../../server/obtener_usuarios_superadmin.php',
                                                                type: 'POST',
                                                                success: function(respuesta){
                                                                    $("#contenedorUsuariosPlanteles").html(respuesta);
                                                                }
                                                            });

                                                            // FIN PASSWORD Y CORREO VALIDADO
                                                        });
                                                        
                                                    }else{
                                                        // PASSWORD NO EXISTE
                                                        swal({
                                                            title: "¡Datos incorrectos!",
                                                            text: 'No existe el password...',
                                                            icon: "error",
                                                            button: "Aceptar",
                                                        });
                                                        swal.stopLoading();
                                                        swal.close();
                                                    }
                                                }
                                            });


                                        }else{
                                        // DATOS VACIOS
                                            swal({
                                                title: "¡Datos vacíos!",
                                                text: 'Necesitas ingresar un password...',
                                                icon: "error",
                                                button: "Aceptar",
                                            });
                                            swal.stopLoading();
                                            swal.close();
                                        }
                                    });

                                    // FIN CORREO VALIDADO
                                
                                    
                                }else{
                                    // CORREO NO EXISTE
                                    swal({
                                        title: "¡Datos incorrectos!",
                                        text: 'No existe el correo...',
                                        icon: "error",
                                        button: "Aceptar",
                                    });
                                    swal.stopLoading();
                                    swal.close();
                                }
                            }
                        });


                    }else{
                    // DATOS VACIOS
                        swal({
                            title: "¡Datos vacíos!",
                            text: 'Necesitas ingresar un correo...',
                            icon: "error",
                            button: "Aceptar",
                        });
                        swal.stopLoading();
                        swal.close();
                    }
                });
            });
            // FIN VALIDACION SUPERADMIN


        // FIN DOCUMENT.READY
        });



        
    </script>

 <script>
  var overlay = document.getElementById("overlay");

  window.addEventListener('load', function(){
    overlay.style.display = 'none';
    
  });
  
  var el = document.querySelector('#barra-side');
  Ps.initialize(el);

</script>


<script>
  var burbuja = new Audio('../audio/burbuja.mp3');
  var error = new Audio('../audio/error.mp3');

  $('#btn_modo').on('click', function() {
    // event.preventDefault();
    /* Act on the event */
    burbuja.play();

    // console.log('modo tal');
    obtenerModo();

      

      
    

  });


  $('.form-check-input').on('click', function() {
    // event.preventDefault();
    /* Act on the event */
    burbuja.play();

    

  });


  <?php  
    if ( $mod_adm == 'dark' ) {
  ?>
      

    

  <?php
    } else {
  ?>
      
      

  <?php
    }
  ?>


  function obtenerModo(){
    
    if ( $( '#mainBody' ).hasClass('grey-skin elegant-color') ) {
  
      var modo = 'claro';

      setTimeout(function(){

        $( '#mainBody' ).removeClass('grey-skin elegant-color').addClass('white-skin bg-light');
        $('.card').removeClass('grey darken-3').addClass('grey lighten-4');
        $( '.modal-content' ).removeClass('white-text elegant-color').addClass('bg-light');
        $( '#mainContainer' ).removeClass('white-text elegant-color').addClass('bg-light');
        $( '#mainNabvar' ).removeClass('grey darken-3').addClass('grey lighten-4');
      
      }, 200 );


      $.ajax({
          url: 'server/editar_modo_usuario.php',
          type: 'POST',
          data: { modo },
          success: function( respuesta ){
            generarAlerta('Cambios guardados');
          }
      });
    
    } else {

      var modo = 'oscuro';

      setTimeout(function(){

        $( '#mainBody' ).removeClass('white-skin bg-light').addClass('grey-skin elegant-color');
        $('.card').removeClass('grey lighten-4').addClass('grey darken-3');
        $('.modal-content').removeClass('bg-light').addClass('white-text elegant-color');
        $( '#mainContainer' ).removeClass('bg-light').addClass('white-text elegant-color');
        $( '#mainNabvar' ).removeClass('grey lighten-4').addClass('grey darken-3');

      }, 200 );


      $.ajax({
          url: 'server/editar_modo_usuario.php',
          type: 'POST',
          data: { modo },
          success: function( respuesta ){
            generarAlerta('Cambios guardados');
          }
      });

      

    }
  }
  
  
</script>


<!--NOTIFICACIONES MENSAJES -->




<script>

  obtener_panel_notificaciones_mensajeria();
  function obtener_panel_notificaciones_mensajeria(){

    $.ajax({
      url: 'server/obtener_panel_notificaciones_mensajeria.php',
      type: 'POST',
      success: function( respuesta ){
        $('#contenedor_notificaciones_mensajeria').html( respuesta );

        $( '#dropdownMenuLinkMensajeria' ).on('click', function(event) {
          event.preventDefault();
          /* Act on the event */

          if ( $( '#icono_mensajeria' ).hasClass('fas fa-envelope') ) {

              $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope' ).addClass( 'fas fa-envelope-open' );
          
          } else {
          
              $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope-open' ).addClass( 'fas fa-envelope' );
          
          }
          

          
        });
      }
    });
    
  }

</script>
<!-- FIN NOTIFICACIONES MENSAJES -->


<!-- EGRESOS -->

<script>
  $('#btn_egreso').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */


    // VALIDACION DE ADMINISTRACION
      swal({
          title: "¡Acceso Restringido!",
          icon: "warning",
          text: 'Necesitas permisos de Administrador para continuar',
          content: {
              element: "input",
              attributes: {
                  placeholder: "Ingresa tu correo de acceso...",
                  type: "text",
              },
          },
          button: {
              text: "Validar",
              closeModal: false,
          },
      })
      .then(correo => {
          if (correo){
              //console.log(name);
              var correo = correo;
              $.ajax({
                      
                  url: 'server/validacion_doble.php',
                  type: 'POST',
                  data: {correo},
                  success: function(respuesta){
                      console.log(respuesta);

                      if (respuesta == 'True') {
                      
                          console.log("Existe el correo");
                          // CORREO VALIDADO 
                          swal({
                              title: "¡Acceso Restringido!",
                              icon: "warning",
                              text: 'Necesitas permisos de Administrador para continuar',
                              content: {
                                  element: "input",
                                  attributes: {
                                      placeholder: "Ingresa tu contraseña...",
                                      type: "password",
                                  },
                              },
                              button: {
                                  text: "Validar",
                                  closeModal: false,
                              },
                          })
                          .then(password => {
                              if (password){
                                  //console.log(name);
                                  var password = password;
                                  $.ajax({
                                          
                                      url: 'server/validacion_doble.php',
                                      type: 'POST',
                                      data: {password, correo},
                                      success: function(respuesta){
                                          console.log(respuesta);

                                          if (respuesta == 'True') {
                                              swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                              then((value) => {
                                                  //
                                                  // VALIDACION CORRECTA
                                                  $('#modal_egresos').modal('show');
                                                  // setTimeout(function(){
                                                  //  $('#con_egr').focus();
                                                  // }, 250 );
                                                  $('#res2_egr').val( correo );
                                                  // FIN VALIDACION CORRECTA
                                              });
                                              
                                          }else{
                                              // PASSWORD NO EXISTE
                                              swal({
                                                  title: "¡Datos incorrectos!",
                                                  text: 'No existe la contraseña...',
                                                  icon: "error",
                                                  button: "Aceptar",
                                              });
                                              swal.stopLoading();
                                              swal.close();

                                              $("#btn_formulario_egresos").removeAttr('disabled');
                                          }
                                      }
                                  });


                              }else{
                              // DATOS VACIOS
                                  swal({
                                      title: "¡Datos vacíos!",
                                      text: 'Necesitas ingresar una contraseña...',
                                      icon: "error",
                                      button: "Aceptar",
                                  });
                                  swal.stopLoading();
                                  swal.close();

                                  $("#btn_formulario_egresos").removeAttr('disabled');
                              }
                          });

                          // FIN CORREO VALIDADO
                      
                          
                      }else{
                          // CORREO NO EXISTE
                          swal({
                              title: "¡Datos incorrectos!",
                              text: 'No existe el correo de administrador...',
                              icon: "error",
                              button: "Aceptar",
                          });
                          swal.stopLoading();
                          swal.close();

                          $("#btn_formulario_egresos").removeAttr('disabled');
                      }
                  }
              });


          }else{
          // DATOS VACIOS
              swal({
                  title: "¡Datos vacíos!",
                  text: 'Necesitas ingresar un correo de administrador...',
                  icon: "error",
                  button: "Aceptar",
              });
              swal.stopLoading();
              swal.close();

              $("#btn_formulario_egresos").removeAttr('disabled');
          }
      });
      // FIN VALIDACION DE ADMINIOSTRACION
    

  });
</script>
<!-- GENERACION TOKEN -->
<?php   if ($tipo == 'Admin') {
?>
  <script>
    $(".revision_token").click(function() {
      usuario = <?php echo $id; ?>;
      $.ajax({
          type: 'post', 
          url: 'server/get_token.php',
          data: {
              'id_usr': usuario,
          },
          success: function(data) {  
              //console.log(data);
              alert(data);
             
          }
      });


    })
  </script>
<?php 
} ?>
<!-- FIN TOKEN -->

<script>

  // FORMULARIO NOTAS PAGO
  
  $("#btn_formulario_egresos").removeAttr('disabled');
  $('#formulario_egresos').on('submit', function(event) {
    event.preventDefault();

    $("#btn_formulario_egresos").attr('disabled','disabled');

    
    var formulario_egresos = new FormData($('#formulario_egresos')[0]);

    $.ajax({
    
      url: 'server/agregar_egreso.php',
      type: 'POST',
      data: formulario_egresos,
      processData: false,
      contentType: false,
      cache: false,
      success: function(respuesta){
        console.log(respuesta);


        swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
        then((value) => {
       
          $("#modal_egresos").modal('hide');

            $("#btn_formulario_egresos").removeAttr('disabled');
            ejecutar_2_funciones();


        $.ajax({
          url: 'server/obtener_egresos.php',
          type: 'POST',
          data: { id_pag },
          success: function(respuesta){

            // $("#modal_egresos").modal('show');
            $("#contenedor_egresos").html(respuesta);
            
            
          }
        });
          // $("#modal_egresos").modal('hide');
        });

      }
    });

  });     
  // FIN FORMULARIO ABONO


  // 
</script>
<!-- FIN EGRESOS -->


<script>
  $("#subir_aviso").click(function() {
    //alert("quiere subir aviso");
    $("#modal_aviso").modal("toggle");
    
  });
  $("#carga_aviso").click(function() {
     aviso = $('#upload_aviso');
     imagen = $("#imagen_aviso")[0].files[0];
     notas = $("#descripcion").val();
     url = $('#liga').val();
     plantel = <?php echo $plantel; ?>;
     tipo_usr = 'Admin';
    //console.log("notas: "+notas);
    //console.log("plantel: "+plantel);
    //console.log("imagen: "+imagen);
    var formulario = new FormData();
    formulario.append('imagen',imagen);
    formulario.append('descripcion',notas);
    formulario.append('plantel',plantel);
    <?php if ($tipo == 'Adminge') { ?>
    formulario.append('liga',url);
  <?php   } ?>
    formulario.append('usr', tipo_usr);
    formulario.append('aviso',aviso);
    $.ajax({
          type: 'post', 
          contentType: false,
          processData: false,
          url: 'server/agregar_aviso.php',
          data: formulario,
          success: function(data) {  
              console.log(data);
              if (data =='Done') {
                
                $("#mensaje").toggle().removeClass("danger-text").addClass("success-text").text("El aviso se subió correctamente");
                setTimeout(function(){
                    $("#mensaje").toggle();
                    $("#modal_aviso").find('form').trigger('reset');
                    $("#modal_aviso").modal("toggle");
                },1500); 
              }
              else{
                $("#mensaje").toggle().removeClass("success-text").addClass("danger-text").text(data);
              }
              //alert(data);
             
          }
      });
  });
</script>


<?php
	}
?>



</body>
  </div>
  <!-- Copyright -->
      </main>
    <!--/Main layout--> 
<footer style="position: absolute; width: 100%; bottom: 0;" id="footer">
    <!--Footer-->
</footer>

</html>