<?php  

    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_alu_ram = $_POST['id_alu_ram'];

?>
<div class="text-center">


    <div class="row">
        <div class="col-md-12">
            <!-- FORMULARIO -->

            <div class="md-form">

                <textarea id="obs_obs_alu_ram" class="md-textarea form-control" rows="1"></textarea>
                <label for="obs_obs_alu_ram">Agregar seguimiento</label>
            </div>

            <a class="btn btn-info btn-rounded waves-effect btn-sm" title="Agregar nueva..."
                id="btn_agregar_observacion">
                Agregar
            </a>
            <!-- FIN FORMULARIO -->
        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

            <?php  
                $sqlObservaciones = "
                    SELECT *
                    FROM observacion_alu_ram
                    WHERE id_alu_ram16 = '$id_alu_ram'
                    ORDER BY id_obs_alu_ram DESC
                ";

                $totalObservaciones = obtener_datos_consulta( $db, $sqlObservaciones )['total'];


                if ( $totalObservaciones == 0 ) {
            ?>
            <h3>
                Sin seguimientos...
            </h3>
            <?php   
                } else {
            ?>

            <?php
                        $resultadoObservaciones = mysqli_query( $db, $sqlObservaciones );
                        $i = 1;

                        while( $filaObservaciones = mysqli_fetch_assoc( $resultadoObservaciones ) ){
                    ?>
            <div class="card" style="border-radius: 20px;">

                <?php  
                                    echo $i.' - '.$filaObservaciones['obs_obs_alu_ram'];
                                    echo '<br>';
                                    echo '<span class="grey-text letraPequena">'.fechaFormateadaCompacta2( $filaObservaciones['fec_obs_alu_ram'] ).'<br><strong>'.$filaObservaciones['res_obs_alu_ram'].'</strong></span>'
                                ?>

            </div>


            <hr>
            <?php
                            $i++;
                        }
                    ?>

            <?php
                }
            ?>

        </div>

    </div>

</div>

<script>
setTimeout(function() {
    $('#obs_obs_alu_ram').focus();
}, 300);


$('#btn_agregar_observacion').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    var obs_obs_alu_ram = $('#obs_obs_alu_ram').val();
    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/agregar_observacion_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram,
            obs_obs_alu_ram
        },
        success: function(respuesta) {

            console.log(respuesta);
            $('#contenedor_observaciones_alumno').html(
                '<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');

            obtener_observaciones_alumno();

        }
    })

});
</script>