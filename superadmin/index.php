<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">

                            <i class="pe-7s-home icon-gradient bg-premium-dark"></i>

                        </div>
                        <div>
                            DASHBOARD PRINCIPAL
                            <div class="page-title-subheading">Bienvenido a <?php echo "<strong>".$nombreCadena."</strong> estimado <strong>".$nombreUsuario."</strong>"; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
            <!--  -->
                <h4>
                    Filtros
                </h4>

                <div class="row">
                    <div class="col-md-12">
                    <!--  -->
                        <div id="contenedor_mes_annio">
                        <!--  -->
                            <div class="row">
                            
                                <!--  -->
                                <div class="col-md-8">

                                    <select class="form-control letraPequena" id="selectorMes">

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
                                            
                                                    <option selected value="<?php echo $i; ?>" inicio="1" fin="<?php echo cal_days_in_month( CAL_GREGORIAN, $i, date('Y')); ?>"><?php echo getMonth( $i ); ?></option>
                                            
                                            <?php
                                                } else {
                                            ?>

                                                    <option value="<?php echo $i; ?>" inicio="1" fin="<?php echo cal_days_in_month( CAL_GREGORIAN, $i, date('Y')); ?>"><?php echo getMonth( $i ); ?></option>

                                            <?php
                                                }
                                            ?>

                                                         

                                        <?php
                                                            
                                                $i++;

                                                
                                            }
                                        ?>
                                    <!--  -->
                                    </select>
                                    
                                </div>


                                <div class="col-md-4">
                                    
                                    <select class="form-control letraPequena" id="selectorAnnio">

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

                                </div>
                                <!--  -->
                            </div>
                        <!--  -->
                        </div>
                    <!--  -->
                    </div>
                </div>

                <hr>
                
                <h4>
                    Cadena <?php echo $nombreCadena; ?>
                </h4>
                <!-- DATOS CADENA -->
                <div class="card no-shadow bg-transparent no-border rm-borders mb-3">
                    <div class="card">
                        <div class="g-0 row">
                            <div class="col-md-12 col-lg-6">
                                <ul class="list-group list-group-flush">
                                    <li class="bg-transparent list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-outer">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total registros</div>

                                                    </div>
                                                    <div class="widget-content-right">
                                                        <div class="widget-numbers " id="total_registros">700</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="bg-transparent list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-outer">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total citas</div>

                                                    </div>
                                                    <div class="widget-content-right">
                                                        <div class="widget-numbers" id="total_citas">1,200</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <ul class="list-group list-group-flush">
                                    <li class="bg-transparent list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-outer">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total cobrado</div>

                                                    </div>
                                                    <div class="widget-content-right">
                                                        <div class="widget-numbers " id="total_cobrado">$200,453</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="bg-transparent list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-outer">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total potencial</div>

                                                    </div>
                                                    <div class="widget-content-right">
                                                        <div class="widget-numbers " id="total_adeudo">$50,000</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        

                        </div>
                    </div>
                </div>
                <!-- FIN DATOS CADENA -->

                <hr>


                
                <!-- DATOS PLANTELES -->
               

                <?php  
                    $sqlPlanteles = "
                        SELECT *
                        FROM plantel 
                        WHERE id_cad1 = '$cadena'
                    ";

                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
                    $contador = 1;
                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                

                ?>
                        <h5>
                            <?php echo $filaPlanteles['nom_pla']; ?>
                        </h5>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <h6 class="widget-subheading">Total registros</h6>
                                            <div class="widget-chart-flex">
                                                <div class="widget-numbers mb-0 w-100">
                                                    <div class="widget-chart-flex">
                                                        <div class="fsize-1">
                                                            <small class="opacity-5">$</small>
                                                            5,456
                                                        </div>
                                                        <div class="ms-auto">
                                                            <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                                <span class=" ps-2">+14%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <h6 class="widget-subheading">Total citas</h6>
                                            <div class="widget-chart-flex">
                                                <div class="widget-numbers mb-0 w-100">
                                                    <div class="widget-chart-flex">
                                                        <div class="fsize-1 ">
                                                            <small class="opacity-5 text-muted">$</small>
                                                            4,764
                                                        </div>
                                                        <div class="ms-auto">
                                                            <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                                <span class=" ps-2">
                                                                    <span class="pe-1">
                                                                        <i class="fa fa-angle-up"></i>
                                                                    </span>
                                                                    8%
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <h6 class="widget-subheading">Total cobrado</h6>
                                            <div class="widget-chart-flex">
                                                <div class="widget-numbers mb-0 w-100">
                                                    <div class="widget-chart-flex">
                                                        <div class="fsize-1">
                                                            <span class=" pe-2">
                                                                <i class="fa fa-angle-down"></i>
                                                            </span>
                                                            <small class="opacity-5">$</small>
                                                             

                                                        </div>
                                                        <!-- <div class="ms-auto">
                                                            <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                                <span class=" ps-2">
                                                                    <span class="pe-1">
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </span>
                                                                    15%
                                                                </span>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <h6 class="widget-subheading">Total adeudo</h6>
                                            <div class="widget-chart-flex">
                                                <div class="widget-numbers mb-0 w-100">
                                                    <div class="widget-chart-flex">
                                                        <div class="fsize-1">
                                                            <small class="opacity-5">$</small>
                                                            
                                                        </div>
                                                        <!-- <div class="ms-auto">
                                                            <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                                <span class=" ps-2">+76%</span>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                <?php

                        $contador++;
                    }
                ?>
                <!-- FIN DATOS PLANTELES -->


                <hr>
                
                <?php  
                    /**
                
                <!-- DATOS PROGRAMAS -->
                <div class="row">
                    <h4>
                        Programas
                    </h4>
                    <?php  
                        $sqlProgramas = "
                            SELECT *
                            FROM rama
                            INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                            WHERE id_cad1 = '$cadena'
                        ";

                        $resultadoProgramas = mysqli_query( $db, $sqlProgramas );
                        $contador = 1;
                        while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                    

                    ?>
                            <div class="col-md-3 border">
                                

                                <div class="card-hover-shadow-2x widget-chart widget-chart2 bg-premium-dark text-start mt-3 card">
                                    <div class="widget-chart-content text-white">
                                        <div class="widget-chart-flex">
                                            <div class="widget-title letraPequena"><?php echo $filaProgramas['nom_ram']; ?></div>
                                            <div class="widget-subtitle opacity-7 letraPequena"><?php echo $filaProgramas['nom_pla']; ?></div>
                                        </div>
                                        <div class="widget-chart-flex">
                                            <div class="widget-numbers ">
                                                <small>$</small>
                                                <span>976</span>
                                                <small class="opacity-8 ps-2">
                                                    <i class="fa fa-angle-up"></i>
                                                </small>
                                            </div>
                                            <div class="widget-description ms-auto opacity-7">
                                                <i class="fa fa-angle-up"></i>
                                                <span class="ps-1">175%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>


                            <?php
                                    if ( $contador % 4 == 0 ) {
                                     
                            ?>
                                    </div>

                                    <div class="row">

                            <?php
                                    }
                            ?>

                    <?php

                            $contador++;
                        }
                    ?>
                </div>
                <!-- FIN DATOS PROGRAMAS -->
                **/
                ?>


            <!--  -->
            </div>
        </div>
        
    </div>

<?php  

	include('inc/footer.php');

?>

<script>
    
    obtener_datos_dashboard();

    $('#selectorMes').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos_dashboard();
        

    });


    $('#selectorAnnio').on('change', function(event) {
        event.preventDefault();
        /* Act on the event */

        obtener_datos_dashboard();
        

    });

    function obtener_datos_dashboard(){

        var diaInicio = $('#selectorMes option:selected').attr('inicio');
        var diaFin = $('#selectorMes option:selected').attr('fin');
        var mes = $('#selectorMes option:selected').val();
        var annio = $('#selectorAnnio option:selected').val();
        
        var inicio = annio+'-'+mes+'-'+diaInicio;
        var fin = annio+'-'+mes+'-'+diaFin;

        // alert( inicio+' - '+fin );
        // $.ajax({
        //     url: 'server/obtener_datos_dashboard.php',
        //     type: 'POST',
        //     dataType: 'json',
        //     data: { inicio, fin },
        //     success: function( data ){

        //         $('#total_adeudo').text( data.total_adeudo );

        //     }
        // });
    }    

    
    
</script>