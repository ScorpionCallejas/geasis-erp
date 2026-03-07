<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">



                            <i class="pe-7s-key icon-gradient bg-premium-dark"></i>
                        </div>
                        <div>
                            TOKENS
                            <div class="page-title-subheading">Consulta de tokens</div>

                        </div>
                    </div>
                </div>
            </div>

            <div>
                
                <div class="row">

                    <div class="col-md-12 element-block-example">


                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">


                                <select class="form-control" id="selectorAnnio">
                                <!--  -->
                                    <?php
                                        

                                        $annioActual = date('Y');
                                        $i = 2018;
                                        $annioFuturo = $annioActual+2;
                                        
                                        while( $i < $annioFuturo ) {

                                            
                                    ?>

                                        <?php  
                                            if ( $i == $annioActual ) {
                                        ?>
                                        
                                                <option selected value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        
                                        <?php
                                            } else {
                                        ?>

                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

                                        <?php
                                            }
                                        ?>

                                                     

                                    <?php
                                                        
                                            $i++;

                                        }
                                    ?>
                                <!--  -->
                                </select>


                                <select class="form-control" id="selectorMes">
                                <!--  -->
                                    <?php
                                        
                                        $mesActualEntero = date('m');
                                        $mesActualTexto = getMonth( $mesActualEntero );

                                        $meses = 12;
                                        $i = 1;
                                        
                                        while( $i <= $meses ) {

                                            
                                    ?>

                                        <?php  
                                            if ( $i == $mesActualEntero ) {
                                        ?>
                                        
                                                <option selected value="<?php echo $i; ?>" inicio="1" fin="30"><?php echo getMonth( $i ); ?></option>
                                        
                                        <?php
                                            } else {
                                        ?>

                                                <option value="<?php echo $i; ?>" inicio="1" fin="30"><?php echo getMonth( $i ); ?></option>

                                        <?php
                                            }
                                        ?>

                                                     

                                    <?php
                                                        
                                            $i++;

                                            
                                        }
                                    ?>
                                <!--  -->
                                </select>
                                
                                
                        
                                <div class="card-body">
                                    <div class="input-group">
                                        <a href="#" class="btn btn-primary" id="btn_crear_token"><i class="fas fa-lock"></i> Generar Token</a><input type="text" class="form-control" disabled="disabled" id="input_token" value="...">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="btn_copiar" title="Copiar al portapapeles">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        


                        
                        <div class="table-responsive ">
                            
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>

                                        <th>Fecha</th>
                                        <th>CDE</th>
                                        <th>Token</th>
                                        <th>Tipo</th>
                                        <th>Usuario</th>
                                        <th>Estatus</th>
                                        <th>Movimiento</th>
                                    </tr>
                                </thead>

                                <tbody>
                                </tbody>
                            </table>    
                        
                        </div>

                    </div>
                	
	            </div>
                
            </div>
        </div>
        
    </div>


<?php  

	include('inc/footer.php');

?>


<script>
    // BUSCADOR
    $('#formulario_buscador').on('submit', function() {
        event.preventDefault();
        /* Act on the event */
        var valor = $('#palabra').val();

        if ( valor.length >= 3 ) {

            obtener_datos();
        
        }
        
    });

    $('#selectorMes').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos();
        

    });


    $('#selectorAnnio').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos();
        

    });



    // 
    $('#selectorSemana').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos();
        

    });


    $('.filtrosFecha').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos();
        // alert( radioReporte );

    });
    // 


    $('#palabra').on('keyup', function(event) {
        event.preventDefault();
        /* Act on the event */

        var valor = $('#palabra').val();

        if ( valor == '' ) {

            obtener_datos();
        
        }



    });

    // FIN BUSCADOR

    $('.radioPeriodo').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos();
        // alert( radioReporte );

    });

</script>

<script>
    // DATATABLE

    obtener_datos();

    function obtener_datos(){        

        $('#contenedor_mes_annio').css( 'display', '' );
        $('#contenedor_fecha').css('display', 'none');
        $('#contenedor_semana').css( 'display', 'none' );

        var diaInicio = $('#selectorMes option:selected').attr('inicio');
        var diaFin = $('#selectorMes option:selected').attr('fin');
        var mes = $('#selectorMes option:selected').val();
        var annio = $('#selectorAnnio option:selected').val();
        
        var inicio = annio+'-'+mes+'-'+diaInicio;
        var fin = annio+'-'+mes+'-'+diaFin;


        $('#myTable').DataTable().destroy();
        $('#myTable').DataTable({
        
            dom: 'Bpfrtl',
                            
            scrollX: true,
            scrollY: true,
            
            // scrollCollapse: true,
            // fixedColumns: {     
            //   leftColumns: [2]
            // },
            buttons: [

                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-info btn-rounded btn-sm',
                        messageTop: 'Listado de Alumnos del Plantel',
                        exportOptions: {
                            columns: ':visible'
                        },
                    }

            ],
            "pageLength" : 10,
            // "columnDefs": [
            //   { 
            //      "orderable": false, 
            //      "targets": [ 0, 2, 3 ] 
            //   }
            // ],
            "processing" : true,
            "serverSide" : true,
            "order" : [],
            "searching" : false,

            "ajax" : {
                url:"server/obtener_tokens.php",
                type:"POST",
                data: { inicio, fin }
            },

            // LANGUAGE
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
            // FIN LANGUAGE        
            
                
        });
    }
    
</script>

<script>
    $('#btn_crear_token').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_validacion_superadmin( crear_token );
        
    });


    const crear_token = () => {
        $.ajax({
            url: 'server/agregar_token.php',
            type: 'POST',
            success: function( respuesta ){
                console.log( respuesta );

                $('#input_token').val( respuesta );
                obtener_datos();
            }
        });
    }
</script>

<script>
    
    $('#btn_copiar').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        
        var copyText = document.getElementById("input_token");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);
          
        toastr.success('Copiado');
    });
</script>


<script>
    // // OBTENER RESPUESTA DATATABLE
    // var diaInicio = $('#selectorMes option:selected').attr('inicio');
    // var diaFin = $('#selectorMes option:selected').attr('fin');
    // var mes = $('#selectorMes option:selected').val();
    // var annio = $('#selectorAnnio option:selected').val();
    
    // var inicio = annio+'-'+mes+'-'+diaInicio;
    // var fin = annio+'-'+mes+'-'+diaFin;

    // length = 10;
    // start = 0;
    // draw = 10;
    // palabra = '';
    // $.ajax({
    //     url: 'server/obtener_tokens.php',
    //     type: 'POST',
    //     data: { length, start, draw, inicio, fin },
    //     success: function( respuesta ){

    //         console.log( respuesta );
    //         // $('#contenedor_visualizacion').html( respuesta );

    //     }
        
    // });
</script>