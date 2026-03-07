<?php  

    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

?>

<!-- MODAL CONSULTA ACTIVIDADES ALUMNO -->

<div class="modal fade" id="modalActividadesAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h5 class="modal-title w-100" id="tituloActividadesAlumno"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="contenedorActividadesAlumno">
        
        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- fin MODAL CONSULTA ACTIVIDADES ALUMNO -->


<style>

    

    #tabla_alumnos>th:nth-child(0), #tabla_alumnos_wrapper    td:nth-child(0) {  
      background-color:#E9E9E9;
      font-size: 12px;
      position: relative;
      top: 17px;
    }

    #tabla_alumnos>th:nth-child(1), #tabla_alumnos_wrapper td:nth-child(1) {  
      background-color:#E9E9E9;
      font-size: 12px;
      position: relative;
      top: 17px;

    }


    #tabla_alumnos>th:nth-child(2), #tabla_alumnos_wrapper td:nth-child(2) {  
      background-color:#E9E9E9;
      font-size: 12px;
      position: relative;
      top: 17px;
    }


/*    #tabla_alumnos th:nth-child(2)  {  
      background-color: red !important;
      font-size: 12px;
    }
*/




</style>

<style>
    .dropdown-menu {
        max-height: 10vw;
        overflow-y: auto;
    }
</style>
<!-- GENERACIONES -->


<div class=" scrollspy-example" style=" height: 200px;">
    
    <div class="row">

        <div class="col-md-12">

            <input type="checkbox" class="form-check-input" id="seleccionGeneraciones" checked="checked">
            <label class="form-check-label" for="seleccionGeneraciones" style="font-size: 10px;">
                Todo
            </label>
    
        </div>
    
    </div>


    <?php

        $id_ram = $_POST['id_ram'];

        $sqlGeneraciones = "
            SELECT *
            FROM generacion
            WHERE
        ";

        for ( $i = 0 ;  $i < sizeof( $id_ram ) ;  $i++ ) {

            if ( sizeof( $id_ram ) == 1 ) {
            
                $sqlGeneraciones .= " 
                    ( id_ram5 = '$id_ram[$i]' ) 
                    ORDER BY id_gen DESC
                ";
                break;
                break;

            } else {

                if ( $i < ( sizeof( $id_ram ) -1 ) ) {
                    
                    $sqlGeneraciones .= " ( id_ram5 = '$id_ram[$i]' ) OR ";

                } else if ( $i == ( sizeof( $id_ram ) -1 ) ) {
                    
                    $sqlGeneraciones .= " 
                        ( id_ram5 = '$id_ram[$i]' )
                        ORDER BY id_gen DESC
                    ";

                }

            }
            
        }

        $resultadoGeneraciones = mysqli_query( $db, $sqlGeneraciones );
        $contadorGeneraciones = 1;

        while( $filaGeneraciones = mysqli_fetch_assoc( $resultadoGeneraciones ) ) {
    ?>
            <div class="row">

                <div class="col-md-12">


                    <?php  

                        if ( $contadorGeneraciones < 4 ) {
                    ?>
                            <input type="checkbox" class="form-check-input checkboxGeneraciones" id="generacion<?php echo $contadorGeneraciones; ?>" value="<?php echo $filaGeneraciones['id_gen']; ?>" checked="checked" total_alumnos="<?php echo obtenerTotalAlumnosGeneracionServer( $filaGeneraciones['id_gen'] ); ?>">
                    <?php
                        } else {
                    ?>

                            <input type="checkbox" class="form-check-input checkboxGeneraciones" id="generacion<?php echo $contadorGeneraciones; ?>" value="<?php echo $filaGeneraciones['id_gen']; ?>" total_alumnos="<?php echo obtenerTotalAlumnosGeneracionServer( $filaGeneraciones['id_gen'] ); ?>">

                    <?php
                        }

                    ?>
                    

                    <label class="form-check-label " for="generacion<?php echo $contadorGeneraciones; ?>" style="font-size: 10px;">

                        <?php echo $filaGeneraciones['nom_gen']; ?>

                    </label>


                </div>
    <?php
        $contadorGeneraciones++;
                      // }
    ?>
                    
            </div>

    <?php
        }
        // FIN while
    ?>
    
</div>

<!-- FIN GENERACIONES -->



<script>
    // SELECCION DE TODOS LOS ANNIOS
    $("#seleccionGeneraciones").on('click', function() {

        if ( $(this)[0].checked == true ) {
          // console.log("checkeado");
            $('.checkboxGeneraciones').prop({checked: true});
            obtenerAlumnosGeneraciones();
          
        }else{ 
          
            $('.checkboxGeneraciones').prop({checked: false});
            obtenerAlumnosGeneraciones();

        }

    //$('.seleccionAnniosMeses').prop({checked: false});
    });


    obtenerAlumnosGeneraciones();

    $('#palabra').on('keyup', function(event) {
        event.preventDefault();
        /* Act on the event */

        var valor = $('#palabra').val();

        if ( valor == '' ) {

            obtenerAlumnosGeneraciones();
        
        }



    });



    $('.checkboxGeneraciones').on('click', function() {
        //event.preventDefault();
        /* Act on the event */
        obtenerAlumnosGeneraciones();
        

    });

    // BUSCADOR

    $('#formulario_alumno').on('submit', function() {
        event.preventDefault();
        /* Act on the event */

        obtenerAlumnosGeneraciones();
        // alert('hi');
        
    });


    // FIN BUSCADOR

    
    function obtener_modal_generacion_pagos( id_gen ){

        $.ajax({
            url: 'server/obtener_generacion.php',
            type: 'POST',
            dataType: 'json',
            data: { id_gen },
            success: function(datos){
                console.log(datos.nom_gen);
                // $('#modal_generacion_edicion').modal('show');
                $('#titulo_generacion_pagos').text( datos.nom_gen );
                // $('#ini_gen_edicion').val( datos.ini_gen );
                // $('#fin_gen_edicion').val( datos.fin_gen );
                // $('#id_gen_edicion').val( datos.id_gen );

                            
            }
        });


        $.ajax({
            url: 'server/obtener_generacion_pagos.php',
            type: 'POST',
            data: { id_gen },
            success: function( respuesta ){

                $('#contenedor_generacion_pagos').html( respuesta );
            
            }
        
        });


        $( '#checkbox_generacion_pagos' ).prop({checked: false});
        
    }

    function obtenerAlumnosGeneraciones() {
        
        // GENERACION PAGOS
        if ( $( '#checkbox_generacion_pagos' )[0].checked == true ) {

            $('#modal_generacion_pagos').modal('show');
            
            var id_gen = $( '#checkbox_generacion_pagos' ).val();
            
            obtener_modal_generacion_pagos( id_gen );
            


            

        }
        // FIN GENERACION PAGOS

        var palabra = $('#palabra').val();
        var inicio = $('#inicio').val();
        var fin = $('#fin').val();

        radiosVisualizacion = $(".radiosVisualizacion:checked").val();
   
        
        var tipo_estatus = [];
        var estatus = [];

        for ( var i = 0, j = 0 ; i < $(".seleccionEstatus").length ; i++ ) {

            if ( $(".seleccionEstatus").eq(i).attr('switch') == 'verdadero' ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

                tipo_estatus[j] = $('.seleccionEstatus').eq(i).attr('tipo_estatus');
                estatus[j] = $('.seleccionEstatus').eq(i).attr('estatus');

                j++;

            }
        }


        var id_gen = [];
        var total_alumnos = [];

        for ( var i = 0, j = 0 ; i < $(".checkboxGeneraciones").length ; i++ ) {

            if ( $(".checkboxGeneraciones")[i].checked == true ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

                id_gen[j] = $('.checkboxGeneraciones').eq(i).val();
                total_alumnos[j] = $('.checkboxGeneraciones').eq(i).attr('total_alumnos');

                j++;

            }
        }



        if ( 
            ( ( palabra != '' ) && ( id_gen.length == 0 ) ) || 
            ( id_gen.length > 0 ) ||  
            ( ( palabra != '' ) && ( id_gen.length > 0 ) )
        ){

            if ( palabra != '' ) {
                
                var pageLength = -1;

            } else {
               
                if ( id_gen.length == 1 ) {

                    var pageLength = total_alumnos[0];
                    
                } else {

                    var pageLength = 10;
                
                }
            }

            

            // OBTENER RESPUESTA DE JSON
            // $.ajax({
            //     url: 'server/obtener_contenedor_dashboard.php',
            //     type: 'POST',
            //     data: { id_gen, palabra, inicio, fin, estatus, tipo_estatus },
            //     success: function( respuesta ){

            //         console.log( respuesta );
            //         // $('#contenedor_visualizacion').html( respuesta );

            //     }
                
            // });

            // $.ajax({
            //     url: 'server/obtener_contenedor_dashboard.php',
            //     type: 'POST',
            //     dataType: 'json',
            //     data: { id_gen, palabra, inicio, fin, estatus, tipo_estatus },
            //     success: function( datos ){


            //         // $('#contenedor_datos').html( datos );
            //         console.log( datos );

            //         $('#est_gen_cer').html( datos.est_gen_cer ); 
            //         $('#est_gen_gra').html( datos.est_gen_gra );
            //         $('#est_gen_fin').html( datos.est_gen_fin );
            //         $('#est_gen_act').html( datos.est_gen_act );
            //         $('#est_gen_reg').html( datos.est_gen_reg );
            //         $('#est_gen_ant').html( datos.est_gen_ant );
            //         $('#est_gen_apa').html( datos.est_gen_apa );
            //         $('#est_gen_np').html( datos.est_gen_np );
            //         $('#est_gen_blo').html( datos.est_gen_blo );
            //         $('#est_gen_sus').html( datos.est_gen_sus );
            //         $('#est_gen_baj').html( datos.est_gen_baj );
            //         $('#est_gen_rei').html( datos.est_gen_rei );


            //         $('#est_aca_act').html( datos.est_aca_act );
            //         $('#est_aca_ina').html( datos.est_aca_ina );
            //         $('#est_aca_egr').html( datos.est_aca_egr );
            //         $('#est_pag_con').html( datos.est_pag_con );
            //         $('#est_pag_sin').html( datos.est_pag_sin );
            //         $('#est_mes_1me').html( datos.est_mes_1me );
            //         $('#est_mes_2me').html( datos.est_mes_2me );
            //         $('#est_mes_mas').html( datos.est_mes_mas );
            //         $('#est_tot_pag').html( datos.est_tot_pag );
            //         // alert( datos.est_tot_pag );
            //         $('#est_tot_ade').html( datos.est_tot_ade );
            //         $('#est_cue_act').html( datos.est_cue_act );
            //         $('#est_cue_ina').html( datos.est_cue_ina );
            //         $('#est_sub_alt').html( datos.est_sub_alt );
            //         $('#est_sub_n').html( datos.est_sub_n  );
            //         $('#est_sub_r').html(datos.est_sub_r );
            //         $('#est_sub_rec').html( datos.est_sub_rec );
            //         $('#est_act_ade').html( datos.est_act_ade ); 
            //         $('#est_act_na').html( datos.est_act_na );
            //         $('#est_doc_ent').html( datos.est_doc_ent );
            //         $('#est_doc_pen').html( datos.est_doc_pen );

            //         $('#est_doc_ent2').html( datos.est_doc_ent );
            //         $('#est_doc_pen2').html( datos.est_doc_pen );


            //     }
            // });
            
            if ( radiosVisualizacion == 'Generaciones' ) {

                // $('#contenedor_dashboard').css({
                //     display: 'none'
                // });
                
                $('#contenedor_botones_accion').html('');
                

                obtenerListadoGeneraciones( id_gen, palabra, inicio, fin );

                function obtenerListadoGeneraciones( id_gen, palabra, inicio, fin ){

                    $('#contenedor_select').html('');
                    $('#contenedor_paginacion').html('');
                    $('#contenedor_info').html('');

                    $('#contenedor_visualizacion').html('<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
                    
                    $.ajax({
                        url: 'server/obtener_listado_generaciones.php',
                        type: 'POST',
                        data: { id_gen, palabra, inicio, fin },
                        success: function( respuesta ){

                            // console.log( respuesta );
                            $('#contenedor_visualizacion').html( respuesta );

                        }
                        
                    });
                }

            } else if ( radiosVisualizacion == 'Alumnos' ) {

                // $('#contenedor_dashboard').css({
                //     display: ''
                // });

                $('#contenedor_botones_accion').html('<div class="col-md-12"> <div class="row"> <div class="col-md-12"> <div class="form-check"> <input type="checkbox" class="form-check-input" id="seleccionTotal"> <label class="form-check-label letraPequena font-weight-normal" for="seleccionTotal"> <span class="badge badge-pill badge-danger letraMediana font-weight-normal" id="contador_alumnos_seleccionados"></span> Seleccionar/Deseleccionar </label> </div></div></div><br><div class="row"> <div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Inscribir materias" id="btn_inscripcion" style="width: 100%;"> Inscripción </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Dar de baja materias" id="btn_baja" style="width: 100%;"> Cancelación de inscr. </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Envía un mensaje a los alumnos seleccionados" id="btn_mensaje_alumnos" style="width: 100%;"> Enviar mensaje </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Genera un pago para los alumnos seleccionados" id="btn_pago_alumnos" style="width: 100%;"> Crear pago </a> </div><div class="col-md-3"> <a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Cambiar de grupo" id="btn_cambio" style="width: 100%;"> Cambiar grupo </a> </div> <div class="col-md-3"><a class="btn btn-info letraPequena font-weight-normal waves-effect" title="Agregar a otro programa o curso" id="btn_programa" style="width: 100%;"> Agregar a programa </a></div></div></div>');

                
                $('#tabla_alumnos').DataTable().destroy();
            // alert( pageLength );

                    $('#contenedor_visualizacion').html('<div class=""> <table id="tabla_alumnos" class="table table-striped"> <thead> <tr> <th class="letraPequena" style="background-color: #F5F5F5 !important;">Foto</th> <th class="letraPequena" style="background-color: #F5F5F5 !important;">Nombre</th> <th class="letraPequena">Acción</th> <th class="letraPequena">Selección</th> <th class="letraPequena">Ingreso</th>  <th class="letraPequena">Matrícula</th> <th class="letraPequena">Teléfono</th> <th class="letraPequena">Cuenta de acceso</th> <th class="letraPequena">Contraseña</th> <th class="letraPequena">Grupo</th> <th class="letraPequena">Programa</th> <th class="letraPequena">Estatus general</th> <th class="letraPequena">Estatus académico</th><th class="letraPequena">Carga alumno</th> <th class="letraPequena">Estatus de pagos</th> <th class="letraPequena">Meses adeudo</th> <th class="letraPequena">Adeudo</th> <th class="letraPequena">Pagado</th> <th class="letraPequena">Estatus de cuenta</th> <th class="letraPequena">Subestatus</th> <th class="letraPequena">Estatus de actividad</th> <th class="letraPequena">Estatus de documentación</th> </tr></thead> </table> </div>');
                    


                    // OBTENER RESPUESTA DATATABLE IMPRIMIR
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
                        
                        scrollX:        true,
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

                                    // $('#contenedor_seleccion_alumnos').html('<div class="chip pegadoSeleccionAlumno" id_alu_ram="0" id_ram="Sin adeudo" nombre_alumno="rocio maldonado rosas" estatus_pago="undefined"><img src="../uploads/foto-alumno002134.png"><span>rocio maldonado rosas</span><i class="close fas fa-times eliminacionPegadoSeleccionAlumno" title="Remover de la selección"></i></div>');

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


                            $('#foto').on('change', function(event) {
                                event.preventDefault();

                                readURL(this);

                            });


                            function readURL(input) {
                                if (input.files && input.files[0]) {

                                    var reader = new FileReader();
                                    reader.onload = function (e) {
                                    $('#contenedor_imagen_edicion')
                                      .attr('src', e.target.result);
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                
                                }
                            }


                            $('#correoEdicion').on('keyup', function(event) {
                                /* Act on the event */

                                var correo = $('#correoEdicion').val();
                                validacionCorreoTiempoRealEdicion( correo );

                            });

                            function validacionCorreoTiempoRealEdicion( correoEdicion ){
                                console.log( correoEdicion );

                                var identificador = $('#identificador').val();
                                var tipo = "Alumno";

                                if (correoEdicion != '') {
                                  $.ajax({
                                    url: 'server/validacion_correo.php',
                                    type: 'POST',
                                    data: { correoEdicion, tipo, identificador },
                                    success: function(response){
                                      console.log(  response );
                                      var respuesta = response; 

                                      if (respuesta == 'disponible') {
                                        
                                        $('#outputEdicion').attr({
                                          class: 'text-info letraPequena font-weight-normal'
                                        });
                                        $('#outputEdicion').text("¡El correo electrónico está disponible!");

                                      } else if ( respuesta == 'mio' ) {


                                        $('#outputEdicion').attr({
                                          class: 'text-info letraPequena font-weight-normal'
                                        });
                                        $('#outputEdicion').text("¡El correo electrónico es el mismo!");


                                      } else {
                                        // correo = correo+'1';
                                        
                                        correoEdicion = correoEdicion.substring(0, correoEdicion.indexOf("@"))+'1';

                                        correoEdicion = correoEdicion+'@<?php echo $folioPlantel; ?>.com';
                                        $('#correoEdicion').val( correoEdicion );

                                        validacionCorreoTiempoRealEdicion( correoEdicion );
                                        
                                        // $('#outputEdicion').attr({
                                        //   class: 'text-danger letraPequena font-weight-normal'
                                        // });
                                        // $('#outputEdicion').text("¡El correo electrónico está ocupado!");

                                      }
                                    }
                                  })

                                }else{
                                  $('#outputEdicion').attr({class: 'text-warning letraPequena font-weight-normal'});
                                  $('#outputEdicion').text("¡Ingresa un Correo Electrónico!");
                                }
                                      
                            }

                            
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

                                    console.log(datos);

                                    $('#fotoText').removeAttr('placeholder').val('');
                                    
                                    $('#nombre').attr({value: datos.nom_alu});

                                    $('#apellido1').attr({value: datos.app_alu});
                                    $('#apellido2').attr({value: datos.apm_alu});
                                    
                                    $('#correo1_alumno').attr({value: datos.cor1_alu});
                                    $('#boleta').attr({value: datos.bol_alu});
                                    $('#genero').attr({value: datos.gen_alu});
                                    $('#telefono').attr({value: datos.tel_alu});
                                    $('#curp').attr({value: datos.cur_alu});
                                    $('#procedencia').attr({value: datos.pro_alu});
                                    $('#correoEdicion').attr({value: datos.cor_alu});
                                    $('#password').attr({value: datos.pas_alu});
                                    $('#nacimiento').attr({value: datos.nac_alu});
                             
                                    $('#beca_alu_ram').attr({value: datos.bec_alu_ram*100});
                                    $('#beca2_alu_ram').attr({value: datos.bec2_alu_ram*100});
                                    $('#carga').attr({value: datos.car_alu_ram});


                                    if ( datos.fot_alu == null ) {

                                        $('#contenedor_imagen_edicion').removeAttr('src').attr( 'src', '../img/usuario.jpg' ); 
                                        $('#fotoText').removeAttr('placeholder').attr('placeholder', 'Sube un archivo en JPG, JPEG o PNG');

                                    } else {
                                    
                                        $('#contenedor_imagen_edicion').removeAttr('src').attr( 'src', '../uploads/'+datos.fot_alu ); 
                                        $('#fotoText').removeAttr('placeholder').attr('placeholder', datos.fot_alu);
                                    
                                    }
                                    
                                    

                                    $('#direccion').attr({value: datos.dir_alu});
                                    $('#codigo').attr({value: datos.cp_alu});
                                    $('#colonia').attr({value: datos.col_alu});
                                    $('#delegacion').attr({value: datos.del_alu});
                                    $('#entidad').attr({value: datos.ent_alu});
                                    $('#tutor').attr({value: datos.tut_alu});
                                    $('#telefono2').attr({value: datos.tel2_alu});
                                    $('#identificador').attr({value: datos.id_alu});
                                    $('#identificadorAlumnoRama').attr({value: datos.id_alu_ram});
                                    //AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL ALUMNO
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
                                                
                                                if ($("#foto")[0].files[0]) {

                                                  var fileName = $("#foto")[0].files[0].name;
                                                  var fileSize = $("#foto")[0].files[0].size;

                                                  var ext = fileName.split('.').pop();

                                                  
                                                  if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
                                                    if (fileSize < 3000000) {
                                                      $.ajax({
                                                  
                                                        url: 'server/editar_alumno.php',
                                                        type: 'POST',
                                                        data: new FormData( formulario_editar_alumno ),
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
                                                        data: new FormData( formulario_editar_alumno ), 
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


                            // BAJA DEFINITIVA
                            $('.bajaAlumno').on('click', function(event) {
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

                                                    $('#modal_baja_alumno').modal('show');
                                                    $('#nombre_baja_alumno').html( '<i class="fas fa-user-times"></i> Baja definitiva: '+nombreAlumno );
                                                    $('#alerta_baja_alumno').html('<strong>¡Atención!</strong> Los datos del alumno se preservarán en la plataforma. Sin embargo el alumno no podrá acceder a su cuenta sin que antes sean reestablecidos los permisos a través de un reingreso. <hr>Se te solicita un motivo por el cual el alumno es dado de baja.');

                                                    $("#label_baja_alumno").text( "baja definitiva" );

                                                    $("#btn_baja_alumno").removeAttr('disabled');
                                                    setTimeout( function(){
                                                        $('#mot_ing_alu_ram').focus();
                                                    }, 300 );

                                                    $('#id_alu_ram_baja_alumno').val( id_alu_ram );
                                                    $('#tip_ing_alu_ram').val( 'Baja definitiva' );
                                                    
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


                            

                            // FIN BAJA DEFINITIVA


                            // REINGRESO
                            $('.reingresoAlumno').on('click', function(event) {
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

                                                    $('#modal_baja_alumno').modal('show');
                                                    $('#nombre_baja_alumno').html( '<i class="fas fa-user-plus"></i> Reingreso: '+nombreAlumno );
                                                    $('#alerta_baja_alumno').html('<strong>¡Atención!</strong> A partir de ahora el alumno tendrá acceso nuevamente a la plataforma. <hr>Su fecha de ingreso será reescrita, sin embargo podrás consultar su historico de movimientos de bajas definitivas y reingresos en la consulta de alumno. Para concluir, se solicita un motivo de reingreso');

                                                    $("#label_baja_alumno").text( "reingreso" );

                                                    $("#btn_baja_alumno").removeAttr('disabled');
                                                    setTimeout( function(){
                                                        $('#mot_ing_alu_ram').focus();
                                                    }, 300 );

                                                    $('#id_alu_ram_baja_alumno').val( id_alu_ram );
                                                    $('#tip_ing_alu_ram').val( 'Reingreso' );
                                                    
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


                            

                            // FIN REINGRESO



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


                            // REENVIO CORREO
                            $('.reenviarCorreo').on('click', function(event) {
                                event.preventDefault();
                                /* Act on the event */

                                console.log('reenviarCorreo');
                                var id_alu_ram = $(this).attr('id_alu_ram');
                                var validador = '1';

                                //
                                swal({
                                  title: "¿Deseas reenviar el correo de bienvenida?",
                                  text: "Continuar",
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
                                    url: 'server/enviar_correo_demo.php',
                                    type: 'POST',
                                    data: { id_alu_ram, validador },
                                    success: function(respuesta){
                                      console.log( respuesta );
                                      // if (respuesta == "true") {
                                        console.log("Exito en consulta");
                                        swal("Reenviado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                        then((value) => {
                                          // window.location.reload();

                                          generarAlerta('¡Correo reenviado!');
                                          reloadTable();

                                        });
                                      // }else{
                                        // console.log(respuesta);

                                      // }

                                    }
                                  });
                                    
                                  }
                                });
                                // 

                                
                            });
                            // FIN REENVIO CORREO
                            





                              

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

                                              

                                                $('#barra_estado_mensaje').removeAttr('tipo').attr('tipo', 'archivo');

                                                $.ajax({
                                                    url: 'server/obtener_formulario_archivo.php',
                                                    type: 'POST',
                                                    data: { alumnos },
                                                    success: function( respuesta ){
                                                        // console.log( respuesta );
                                                        $("#contenedor_principal_mensaje_alumnos").html(respuesta);
                                                        $('.file_upload').file_upload();


                                                    }
                                                });


                                          //    $('#btn_enviar_mensaje_alumnos').on('click', function(event) {
                                                //  event.preventDefault();
                                                //  /* Act on the event */
                                                //  $("#btn_archivo_mensajeria").trigger('click');
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

                                                        for(var i = 0, tipoDestino = 'Alumno'; i < $(".seleccionAlumnoFinal").length; i++){
                                                        
                                                            var idDestino = $('.seleccionAlumnoFinal').eq(i).attr("id_alu");
                                                            
                                                            $.ajax({
                                                                ajaxContador: i,
                                                                url: 'server/contacto.php',
                                                                type: 'POST',
                                                                data: {idDestino, tipoDestino, mensaje},
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

                                                                            // DESCOMENTAR wss
                                                                            // var datos = {
                                                                            //     tipo: 'Mensajeria',
                                                                            //     id_usuario: idDestino

                                                                            // };

                                                                        
                                                                            // DESCOMENTAR wss
                                                                            // socket.send( JSON.stringify( datos ) );

                                                                          
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
                
                



                $('#tabla_alumnos_wrapper').find('label').each(function () {
                    $(this).parent().append($(this).children());
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
                
                $('#tabla_alumnos_wrapper .dataTables_filter').find('input').each(function () {
                    $('#tabla_alumnos_wrapper input').attr("placeholder", "Buscar...");
                    $('#tabla_alumnos_wrapper input').removeClass('form-control-sm');
                });
                $('#tabla_alumnos_wrapper .dataTables_length').addClass('d-flex flex-row');
                $('#tabla_alumnos_wrapper .dataTables_filter').addClass('md-form');
                $('#tabla_alumnos_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
                $('#tabla_alumnos_wrapper .mdb-select').materialSelect('destroy');
                $('#tabla_alumnos_wrapper select').addClass('mdb-select');
                $('#tabla_alumnos_wrapper .mdb-select').materialSelect();
                $('#tabla_alumnos_wrapper .dataTables_filter').find('label').remove();
                var botones = $('#tabla_alumnos_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');

                $('#contenedor_select').html('');
                $('#contenedor_paginacion').html('');
                $('#contenedor_info').html('');

                $('#contenedor_select').html($('#tabla_alumnos_length'));
                $('#tabla_alumnos_length').find('label');
                $('#contenedor_paginacion').html($('#tabla_alumnos_paginate'));
                $('#contenedor_info').html($('#tabla_alumnos_info').addClass('letraPequena'));
            }
            


            
        } else if ( id_gen.length == 0 ) {

            swal("¡No hay generaciones seleccionadas!", "Selecciona al menos una para continuar", "info", {button: "Aceptar",});
            
            // $("#contenedor_principal").html("");

        }

        
    }

    

</script>

<script>
    function reloadTableGeneral(){
        
        $('#tabla_alumnos').DataTable().ajax.reload();

    }
</script>