<?php  
    //ARCHIVO VIA AJAX PARA OBTENER DOCUMENTACION DEL ALUMNO
    //alumnos_carrera.php//obtener_alumnos_generacion.php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');
    
    $id_alu_ram = $_POST['id_alu_ram'];

    $sqlAlumno = "
        SELECT * 
        FROM alu_ram
        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
        WHERE id_alu_ram = '$id_alu_ram'
    ";
    
    $resultadoAlumno = mysqli_query( $db, $sqlAlumno );
    
    if ( $resultadoAlumno ) {
        
        $filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

        // DATOS ALUMNO
        $fot_alu = $filaAlumno['fot_alu'];
        $nom_alu = $filaAlumno['nom_alu'];
        $app_alu = $filaAlumno['app_alu'];
        $apm_alu = $filaAlumno['apm_alu'];
        $nombreAlumno = $nom_alu." ".$app_alu." ".$apm_alu;

    } else {
        echo $sqlAlumno;
    }
    
?>

<!-- IMAGENES -->


<div class="row">
    <div class="col-md-12 p-4">
        

        <?php
            $sqlDocumentacion = "
                SELECT *
                FROM documento_alu_ram
                INNER JOIN documento_rama ON documento_rama.id_doc_ram = documento_alu_ram.id_doc_ram1
                WHERE id_alu_ram11 = '$id_alu_ram'
                ORDER BY arc_doc_alu_ram DESC
            ";

            $resultadoDocumentacion = mysqli_query($db, $sqlDocumentacion);
            $contador = 1;
            while($filaDocumentacion = mysqli_fetch_assoc($resultadoDocumentacion)){

        ?>
                
                <?php  
                    if ( ( $filaDocumentacion['est_doc_ram'] == 'Activo' ) && ( $filaDocumentacion['arc_doc_alu_ram'] != NULL ) && ( $filaDocumentacion['arc_doc_alu_ram'] != 'Rechazado' ) ) {
                ?>

                        
                        <a href="../uploads/<?php echo $filaDocumentacion['arc_doc_alu_ram']; ?>" data-lightbox="roadtrip" data-title="Documento: <?php echo $filaDocumentacion['nom_doc_ram']; ?>" >

                            

                            <img src="../uploads/<?php echo $filaDocumentacion['arc_doc_alu_ram']; ?>" class="img-fluid" style="width: 100px; height: 80px; border-radius: 10px;" title="Haz click para ampliar el documento: <?php echo $filaDocumentacion['nom_doc_ram']; ?>">

                        
                        </a>

                <?php
                    }
                ?>
                
                
                        

                
                
                


        <?php
                $contador++;
            }
        ?>

    </div>
</div>

    

</div>

</div>
<!-- FIN IMAGENES -->

<hr>

<div class="alert alert-info alert-dismissible fade show letraMediana" role="alert">
    Marca la casilla del documento en la columna "Aprobación del documento" para definir como "Entregado"
</div>



<?php
    $sqlDocumentacion = "
        SELECT *
        FROM documento_alu_ram
        INNER JOIN documento_rama ON documento_rama.id_doc_ram = documento_alu_ram.id_doc_ram1
        WHERE id_alu_ram11 = '$id_alu_ram'
        ORDER BY arc_doc_alu_ram DESC
    ";

    $resultadoDocumentacion = mysqli_query($db, $sqlDocumentacion);
   
    $i = 1;

    $columnas = 3;
?>


<div class="row">
    <?php  
        while( $filaDocumentacion = mysqli_fetch_assoc( $resultadoDocumentacion ) ){
    ?>

        <div class="col-md-4">
            

            <div class="card letraMediana p-2" style="border-radius: 10px; height: 70px;">

                <?php
                    if ( $filaDocumentacion['est_doc_alu_ram'] == 'Pendiente' ) {
                ?>
                        <input type="checkbox" class="form-check-input checkboxDocumentacion" id="checkboxDocumentacion<?php echo $i; ?>" value="Entregado" id_doc_alu_ram="<?php echo $filaDocumentacion['id_doc_alu_ram']; ?>" nom_doc_ram="<?php echo $filaDocumentacion['nom_doc_ram']; ?>">

                <?php
                    }else {
                ?>
                        <input type="checkbox" class="form-check-input checkboxDocumentacion" id="checkboxDocumentacion<?php echo $i; ?>" value="Pendiente" nom_doc_ram="<?php echo $filaDocumentacion['nom_doc_ram']; ?>" id_doc_alu_ram="<?php echo $filaDocumentacion['id_doc_alu_ram']; ?>" checked>
                        
                <?php
                    }
                ?>

                <label class="form-check-label letraPequena" for="checkboxDocumentacion<?php echo $i; ?>">
                    <i class="far fa-file fa-2x"></i> <span class="p-2"><?php echo $filaDocumentacion['nom_doc_ram']; ?></span>
                </label>

            </div>

        </div> 
           

    <?php  
        if ( $i % $columnas == 0 ) {
    ?>

        </div>

        <hr>
        <div class="row">
    <?php
        }
    ?>


<?php
    $i++;
    }

?>

     


                



        

        
       
       




<script>

        // $('#myTableDocumentacion').DataTable().destroy();

        // $('#myTableDocumentacion').DataTable({
            
        
        //     dom: 'frtlip',
            
        //     buttons: [

            
        //             'copy',
              //       {
              //           extend: 'excel',
              //           messageTop: "<?php echo 'Documentacion del Alumno: '.$nombreAlumno; ?>"
              //       },
              //       {
        //                 extend: 'print',
        //                 messageTop: "<?php echo 'Documentacion del Alumno: '.$nombreAlumno; ?>",
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 },
        //             },

        //             {
        //                 extend: 'pdf',
        //                 messageTop: "<?php echo 'Documentacion del Alumno: '.$nombreAlumno; ?>",
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 },
        //             },

        //     ],

        //     "language": {
        //                     "sProcessing":     "Procesando...",
        //                     "sLengthMenu":     "Mostrar _MENU_ registros",
        //                     "sZeroRecords":    "No se encontraron resultados",
        //                     "sEmptyTable":     "Ningún dato disponible en esta tabla",
        //                     "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        //                     "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        //                     "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        //                     "sInfoPostFix":    "",
        //                     "sSearch":         "Buscar:",
        //                     "sUrl":            "",
        //                     "sInfoThousands":  ",",
        //                     "sLoadingRecords": "Cargando...",
        //                     "oPaginate": {
        //                         "sFirst":    "Primero",
        //                         "sLast":     "Último",
        //                         "sNext":     "Siguiente",
        //                         "sPrevious": "Anterior"
        //                     },
        //                     "oAria": {
        //                         "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        //                         "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        //                     }
        //                 }
        // });
        // $('#myTableDocumentacion_wrapper').find('label').each(function () {
        //     $(this).parent().append($(this).children());
        // });
        // $('#myTableDocumentacion_wrapper .dataTables_filter').find('input').each(function () {
        //     $('#myTableDocumentacion_wrapper input').attr("placeholder", "Buscar...");
        //     $('#myTableDocumentacion_wrapper input').removeClass('form-control-sm');
        // });
        // $('#myTableDocumentacion_wrapper .dataTables_length').addClass('d-flex flex-row');
        // $('#myTableDocumentacion_wrapper .dataTables_filter').addClass('md-form');
        // $('#myTableDocumentacion_wrapper select').removeClass(
        // 'custom-select custom-select-sm form-control form-control-sm');
        // $('#myTableDocumentacion_wrapper select').addClass('mdb-select');
        // $('#myTableDocumentacion_wrapper .mdb-select').materialSelect();
        // $('#myTableDocumentacion_wrapper .dataTables_filter').find('label').remove();
        // var botones = $('#myTableDocumentacion_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        // //console.log(botones);

    
</script>


<script>
    // TITULO MODAL DOCUMENTACION ALUMNO
    var fot_alu = '<?php echo $fot_alu; ?>';
    var nom_alu = '<?php echo $nom_alu; ?>';
    var app_alu = '<?php echo $app_alu; ?>';

    $('#tituloDocumentacionAlumno').html('<img src="../uploads/'+fot_alu+'" class="img-fluid avatar rounded-circle" width="30px" height="30px"> '+"Documentación Entregada de "+nom_alu+" "+app_alu);


    
</script>


<script>
    $('.file_upload').file_upload();
</script>



<script>
    //eliminacionDocumentacion DE DOCUMENTO
    $('.eliminacionDocumentacion').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        

        var id_doc_alu_ram = $(this).attr("id_doc_alu_ram");
        var documento = $(this).attr('documento');

        // console.log(DOCUMENTO);

        swal({
          title: "¿Deseas eliminar el documento: "+documento+"?",
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
            //eliminacionDocumentacion ACEPTADA

            $.ajax({
                url: 'server/edicion_imagen_documentacion_alumno.php',
                type: 'POST',
                data: { id_doc_alu_ram },
                success: function(respuesta){
                    
                    if (respuesta == "Exito") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                          
                            var id_alu_ram = <?php echo $id_alu_ram; ?>;
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
                        console.log(respuesta);

                    }

                }
            });
            
          }
        });
    });


</script>


<script>
    $('.checkboxDocumentacion').on('change', function(event) {
        // event.preventDefault();
        /* Act on the event */

        var id_doc_alu_ram = $(this).attr('id_doc_alu_ram');
        var est_doc_alu_ram = $(this).val();

        if ( $(this)[0].checked == true ) {
                                      // console.log("checkeado");
                                        
            

            $.ajax({
                url: 'server/editar_estatus_documentacion.php',
                type: 'POST',
                data: { id_doc_alu_ram, est_doc_alu_ram },
                success: function( respuesta ){

                    console.log( respuesta );
                    toastr.success('El documento ha sido marcado como "Entregado"');
                }
            });
            

        
        } else { 
          
            // console.log('unchecked');
            
            $.ajax({
                url: 'server/editar_estatus_documentacion.php',
                type: 'POST',
                data: { id_doc_alu_ram, est_doc_alu_ram },
                success: function( respuesta ){

                    console.log( respuesta );
                    // toastr.info('El documento ha sido marcado como "Entregado"');
                    toastr.warning('El documento ha sido devuelto');
                }
            });

        }


        reloadTableGeneral();

        
    });
</script>