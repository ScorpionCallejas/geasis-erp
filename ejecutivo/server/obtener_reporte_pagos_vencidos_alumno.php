<?php  
    //ARCHIVO VIA AJAX PARA OBTENER TABLA DE PAGOS DE ALUMNOS
    //cobranza.php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    // echo $_POST['id_alu_ram'];
?>

<style>
.my-custom-scrollbar {
    overflow: auto;
    max-width: 100%;
}

#myTable_2 tbody tr td {
    height: 65px;
    /* Ajusta la altura deseada aquí */
}
</style>

<!-- MODALES -->

<!-- NOTAS PAGO -->
<div class="modal fade" id="modal_asociar_calendario_pago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
    aria-hidden="true">

    <form id="formulario_asociar_calendario_pago" class="form-control">

        <div class="modal-dialog" role="document">

            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header text-center">

                    <h4 class="modal-title w-100" id="myModalLabel2">
                        <span class="fa-stack fa-1x">
                            <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i>
                            <i class="far fa-circle fa-stack-2x animated pulse infinite"></i>
                        </span>
                        Asociar pago a calendario
                    </h4>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <br>

                <div class="modal-body">
                    <div id="contenedor_asociar_calendario_pago">

                    </div>
                </div>



                <div class="modal-footer d-flex justify-content-center">

                    <button class="btn btn-primary white-text btn-rounded btn-sm" type="submit" title="Guardar cliente"
                        id="btn_formulario_asociar_calendario_pago">
                        Guardar
                    </button>

                    <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..."
                        data-dismiss="modal">
                        Cancelar
                    </a>

                </div>

            </div>

        </div>

    </form>


</div>

<!-- FIN NOTAS PAGO -->



<!-- NOTAS PAGO -->
<div class="modal fade" id="modal_notas_pago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
    aria-hidden="true">

    <form id="formulario_notas_pago" class="form-control">

        <div class="modal-dialog" role="document">

            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header text-center">

                    <h4 class="modal-title w-100" id="myModalLabel2">
                        <span class="fa-stack fa-1x">
                            <i class="fas fa-info-circle fa-stack-1x animated rotateIn delay-1s"></i>
                            <i class="far fa-circle fa-stack-2x animated pulse infinite"></i>
                        </span>
                        Notas de pago
                    </h4>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <br>

                <div class="modal-body">


                    <div id="contenedor_notas_pago" class=" my-custom-scrollbar"
                        style="border: 2px dashed grey; height: 150px">

                    </div>

                    <input type="hidden" id="id_pag6" name="id_pag6">

                    <div class="md-form">
                        <textarea id="con_not_pag" name="con_not_pag" class="md-textarea form-control"
                            rows="3"></textarea>
                        <label for="con_not_pag">Nueva nota..</label>
                    </div>


                </div>



                <div class="modal-footer d-flex justify-content-center">

                    <button class="btn btn-primary white-text btn-rounded btn-sm" type="submit" title="Guardar cliente"
                        id="btn_formulario_notas_pago">
                        Guardar
                    </button>

                    <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..."
                        data-dismiss="modal">
                        Cancelar
                    </a>

                </div>

            </div>

        </div>

    </form>


</div>

<!-- FIN NOTAS PAGO -->


<!-- CONVENIO DE FECHAS -->
<div class="modal fade" id="modalConvenio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog" role="document">


        <div class="modal-content" style="border-radius: 20px;">

            <div class="modal-header text-center">

                <h4 class="modal-title w-100" id="myModalLabel">
                    <span class="fa-stack fa-1x">
                        <i class="far fa-calendar-check fa-stack-1x animated rotateIn delay-1s"></i>
                        <i class="far fa-circle fa-stack-2x animated pulse infinite"></i>
                    </span>
                    Prórroga
                </h4>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12 text-center">
                        <!--  -->

                        <!--  -->
                        <span class="">
                            Concepto <br>
                            <span id="conceptoConvenio" class="badge badge-light font-weight-normal letraGrande"></span>
                        </span>

                        <hr>

                        <span class="">
                            Vencimiento actual <br>

                            <div class="badge badge-light font-weight-normal letraGrande">
                                <span id="inicioCobroConvenio">
                                </span> al

                                <span id="finCobroConvenio">
                                </span>
                            </div>

                        </span>



                        <hr>

                        <span class="">
                            Vencimiento final <br>

                            <div class="font-weight-normal letraGrande">

                                <input id="inicioConvenio" class="disabled grey-text" style="font-size: 20px;"
                                    disabled="" />
                                <input id="finConvenio" class="disabled grey-text" style="font-size: 20px;"
                                    disabled="" />

                            </div>

                        </span>


                        <hr>


                        <form id="formularioConvenio">

                            <div class="row">

                                <div class="col-md-2 col-sm-2">

                                </div>

                                <div class="col-md-4 col-sm-4">
                                    <div class="md-form ml-0 mr-0">
                                        <input type="date" name="inicio_convenio" id="inicio_convenio"
                                            class="form-control ml-0 letraPequena font-weight-normal" required=""
                                            value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-4">
                                    <div class="md-form ml-0 mr-0">
                                        <input type="date" name="fin_convenio" id="fin_convenio"
                                            class="form-control ml-0 letraPequena font-weight-normal" required=""
                                            value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-2">

                                </div>

                            </div>

                            <!-- Material unchecked -->
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="motivoConvenio">
                                <label class="letraPequena font-weight-normal" for="motivoConvenio">
                                    Especificar motivo
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-3"></div>

                                <div class="col-md-6 col-sm-6" id="contenedor_motivo_convenio">



                                </div>

                                <div class="col-md-3 col-sm-3"></div>
                            </div>


                            <input type="hidden" id="ini_pagConvenio" name="ini_pag">
                            <input type="hidden" id="fin_pagConvenio" name="fin_pag">
                            <input type="hidden" id="id_pag" name="id_pag">


                            <div class="text-center mt-4">
                                <button class="btn btn-info waves-effect btn-sm btn-rounded" type="submit"
                                    id="btn_convenio">
                                    Aplicar
                                </button>
                            </div>

                        </form>


                    </div>



                </div>



            </div>

        </div>
    </div>
</div>

<!-- FIN CONVENIO DE FECHAS -->


<!-- CONSULTA ALUMNO MODAL -->
<div class="modal fade" id="modalConsultaAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">


        <div class="modal-content">
            <div class="modal-header grey darken-1 white-text text-center">
                <h6 class="modal-title w-100" id="tituloConsultaAlumno">
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body bg-light" id="contenedorConsultaAlumno">



            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- FIN CONSULTA ALUMNO MODAL -->



<!-- ABONO DEL COBRO -->
<div class="modal fade" id="modalAbono" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

    <div class="modal-dialog" role="document">


        <div class="modal-content" style="border-radius: 20px;">

            <div class="modal-header text-center">

                <h4 class="modal-title w-100" id="myModalLabel1">
                    <span class="fa-stack fa-1x">
                        <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i>
                        <!-- <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i> -->
                        <i class="far fa-circle fa-stack-2x animated pulse infinite"></i>
                    </span>
                    Pagar
                </h4>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- PANZA MODAL CONDONACION -->

                <div class="row">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-8 text-center">
                        <h6 class="">
                            Concepto <br>
                            <span id="conceptoAbono" class="grey-text"></span>
                        </h6>

                        <p>Adeudo</p>
                        <h3 class="">
                            <span id="montoAdeudoAbono"></span>
                        </h3>

                        <p>Adeudo final</p>

                        <h3 class="">
                            <span id="diferenciaAbono" class="text-info"></span>
                        </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-8">


                        <form id="formularioAbonoPago">

                            <input type="hidden" id="id_pag1" name="id_pag">
                            <input type="hidden" id="mon_pag_abono" name="mon_pag">
                            <input type="hidden" id="his_pag_abono" name="his_pag">

                            <!-- TIPO DE PAGO Y CANTIDAD -->

                            <div class="md-form mb-5">
                                <select class="selectorAbono form-control" id="tip2_abo_pag" name="tip2_abo_pag"
                                    required="">
                                    <option value="Efectivo" selected>Efectivo</option>
                                    <option value="Tarjeta">Tarjeta de Crédito o Débito</option>
                                    <option value="Depósito">Depósito</option>

                                    <option value="Otro">Otro</option>
                                </select>

                            </div>


                            <div id="contenedor_seleccion_tipo_pago_abono">

                            </div>
                            <!-- Material unchecked -->


                            <br>





                            <!-- FIN TIPO DE PAGO Y CANTIDAD -->




                            <div class="text-center mt-4">
                                <button class="btn btn-info waves-effect btn-sm btn-rounded" type="submit"
                                    id="btn_abono_pago">
                                    Guardar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>


                <!-- FIN PANZA MODAL ABONO   -->
            </div>

        </div>
    </div>
</div>

<!-- FIN ABONO DEL COBRO -->




<!-- CONDONACION DEL COBRO -->
<div class="modal fade" id="modalCondonacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">

    <div class="modal-dialog" role="document">


        <div class="modal-content" style="border-radius: 20px;">

            <div class="modal-header text-center">

                <h4 class="modal-title w-100" id="myModalLabel1">
                    <span class="fa-stack fa-1x">
                        <i class="fas fa-hand-holding-usd fa-stack-1x animated rotateIn delay-1s"></i>
                        <!-- <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i> -->
                        <i class="far fa-circle fa-stack-2x animated pulse infinite"></i>
                    </span>
                    Descuento
                </h4>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- PANZA MODAL CONDONACION -->

                <div class="row">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-8 text-center">

                        <span class="">
                            Concepto <br>
                            <span id="conceptoCondonacion"
                                class="badge badge-light font-weight-normal letraGrande"></span>
                        </span>

                        <hr>

                        <span class="">
                            Adeudo actual<br>
                            <span id="montoAdeudo" class="badge badge-light font-weight-normal letraGrande"></span>
                        </span>

                        <hr>

                        <span class="">
                            Adeudo final <br>
                            <input id="diferenciaCondonacion" class="disabled grey-text" style="font-size: 20px;"
                                disabled="" />
                        </span>

                        <hr>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-8">


                        <form id="formularioCondonacion">

                            <input type="hidden" id="id_pag2" name="id_pag2">
                            <input type="hidden" id="mon_pag" name="mon_pag">
                            <input type="hidden" id="his_pag" name="his_pag">


                            <!-- TIPO DE CONDONACION Y CANTIDAD -->
                            <label for="form34">Tipo de descuento:</label>
                            <select class="selectorCondonacion form-control" id="tip1_con_pag" name="tip1_con_pag">
                                <option value="Monetario" class="active selected">Monetario</option>
                                <option value="Porcentual">Porcentual</option>
                            </select>

                            <div class="md-form" id="contenedor_tipo_condonacion">
                            </div>


                            <div style="display: none;">
                                <label for="form34">Selecciona motivo de condonación</label>
                                <select class="selectorCondonacion form-control" id="motivoCondonacion"
                                    name="motivoCondonacion">
                                    <option value="motivo1" selected>Motivo 1</option>
                                    <option value="motivo2">Motivo 2</option>
                                    <option value="otros">Otros</option>
                                </select>
                            </div>

                            <!-- FIN TIPO DE CONDONACION Y CANTIDAD -->


                            <div class="md-form ml-0 mr-0" id="contenedorMotivoCondonacion">

                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-info waves-effect btn-rounded btn-sm" type="submit"
                                    id="btn_condonacion">
                                    Aplicar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>





                <!-- FIN PANZA MODAL CONDONACION   -->
            </div>

        </div>
    </div>
</div>

<!-- FIN CONDONACION DEL COBRO -->



<!-- CONSULTA DE HISTORIAL DE PAGO -->
<div class="modal fade" id="modalHistorialPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Change class .modal-sm to change the size of the modal -->
    <div class="modal-dialog" role="document">


        <div class="modal-content" style="border-radius: 20px;">

            <div class="modal-header text-center">
                <h4 class="modal-title w-100" id="myModalLabel">Consulta de pago <span
                        id="titulo_medio_historial"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>

            <div class="modal-body" id="panzaModalHistorialPago">






            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded waves-effect"
                    data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<!-- FIN CONSULTA DE HISTORIAL DE PAGO -->

<!-- FIN MODALES -->


<!--  -->
<div class="row" <?php
				// echo $_GET['id_alu_ram'];
				if ( ( isset( $_POST['id_alu_ram'] ) ) || ( isset( $_POST['id_gen'] ) ) ) {
					// echo 'if';
			?> style="display: none;" <?php
				}
			?>>
    <div class="col-md-12">

        <form id="formulario_buscador">

            <div class="md-form">

                <div class="row">

                    <div class="col-md-3"></div>
                    <div class="col-md-6">

                        <div class="card" style="border-radius: 30px;">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-10 col-sm-10">

                                        <i class="fas fa-search prefix"></i>
                                        <input type="text" id="palabra" class="form-control">
                                        <label for="palabra" id="placeholderPalabra">Buscar...</label>

                                    </div>

                                    <div class="col-md-2 col-sm-2" style="position: relative;">

                                        <button class="btn btn-rounded btn-block btn-sm grey-text waves-effect"
                                            type="submit" id="btn_buscar"
                                            style="position: absolute; top: 5px; left: -5px;">
                                            <i class="fas fa-search"></i>
                                        </button>

                                    </div>
                                </div>


                                <div class="row">

                                    <div class="col-md-12">


                                        <div style="">

                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input radioPeriodo"
                                                    id="materialGroupExample22" name="seleccionPeriodo" value="Mes"
                                                    checked>
                                                <label class="form-check-label letraPequena"
                                                    for="materialGroupExample22">Por mes</label>
                                            </div>


                                            <!-- Group of material radios - option 1 -->
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input radioPeriodo"
                                                    id="materialGroupExample11" name="seleccionPeriodo" value="Fecha">
                                                <label class="form-check-label letraPequena"
                                                    for="materialGroupExample11">Por fechas</label>
                                            </div>

                                            <!-- Group of material radios - option 2 -->
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input radioPeriodo"
                                                    id="materialGroupExample23" name="seleccionPeriodo" value="Semana">
                                                <label class="form-check-label letraPequena"
                                                    for="materialGroupExample23">Por semanas</label>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <br>

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="form-check form-check-inline">

                                            <input type="checkbox" class="form-check-input checkboxTipoPago"
                                                id="tipoPago1" value="Colegiatura" checked="checked">
                                            <label class="form-check-label letraPequena" for="tipoPago1"> Colegiatura
                                            </label>

                                        </div>


                                        <div class="form-check form-check-inline">

                                            <input type="checkbox" class="form-check-input checkboxTipoPago"
                                                id="tipoPago2" value="Inscripción" checked="checked">
                                            <label class="form-check-label letraPequena" for="tipoPago2">
                                                Inscripción
                                            </label>

                                        </div>


                                        <div class="form-check form-check-inline">

                                            <input type="checkbox" class="form-check-input checkboxTipoPago"
                                                id="tipoPago3" value="Otros" checked="checked">
                                            <label class="form-check-label letraPequena" for="tipoPago3"> Otros
                                            </label>

                                        </div>

                                    </div>

                                </div>

                                <br>


                                <div id="contenedor_mes_annio" style="display: none;">
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

                                                <option selected value="<?php echo $i; ?>" inicio="1" fin="30">
                                                    <?php echo getMonth( $i ); ?></option>

                                                <?php
																} else {
															?>

                                                <option value="<?php echo $i; ?>" inicio="1" fin="30">
                                                    <?php echo getMonth( $i ); ?></option>

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


                                <!-- SEMANA Y LIBRE -->
                                <div id="contenedor_fecha" style="display: none;">


                                    <div class="row mt-1">
                                        <div class="col-md-6">

                                            <span class="letraPequena">Inicio</span>
                                            <input type="date" class="form-control filtrosFecha letraMediana"
                                                id="inicio" value="<?php echo date('Y-m-d'); ?>">


                                        </div>

                                        <div class="col-md-6">

                                            <span class="letraPequena">Fin</span>
                                            <input type="date" class="form-control filtrosFecha letraMediana" id="fin"
                                                value="<?php echo date('Y-m-d'); ?>">

                                        </div>
                                    </div>

                                </div>

                                <div id="contenedor_semana" style="display: none;">

                                    <span class="letraPequena">Selecciona una semana</span>
                                    <select class="form-control letraPequena" id="selectorSemana">

                                        <!--  -->
                                        <?php
											  		$fechaHoy = date( 'Y-m-d' );
													$i = 0;
													$semanas = obtenerDiferenciaFechasSemanas( $fechaHoy, date('Y').'-01-01' );
													$lunes = date("j");
													$periodo = 6;
												    $periodicidad = $periodo+1;
												    
												    do {


												        if ( $i == 0 ) {

												            if ( $lunes != 6 ) {
												              //echo 'if';
												              $domingo_proximo =  $fechaHoy;
												              $lunes_proximo = date("N");
												              $lunes_proximo = $lunes_proximo-1;
												              $inicio = date('Y-m-d', strtotime($fechaHoy));
												              $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

												              // $semanas = $semanas + 1;

												            } else {
												              //echo 'else';

												                if ( $lunes == 6 ) {
												                    $domingo_proximo =  $fechaHoy;
												                    $lunes_proximo = date("N");
												                    $lunes_proximo = $lunes_proximo-1;
												                    $inicio = date('Y-m-d', strtotime($fechaHoy));
												                    $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
												                
												                } else {


												                    $domingo_proximo = date("N"); //domingo = 7
												                    $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
												                    $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
												                    $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

												                }

												            }
												        

												        } else {

												   
												            $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
												            $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
												            

												        }
												?>


                                        <?php
												        // echo $inicio;
												        if ( $fin < date('Y').'-01-01' ) {
												            // echo 'ok';
												            break; break; break;
												        }
												?>

                                        <?php  
															if ( $i == 0 ) {
														?>
                                        <option selected class="letraPequena" inicio="<?php echo $fin; ?>"
                                            fin="<?php echo $inicio; ?>">Semana
                                            <?php echo $semanas.' - '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?>
                                        </option>
                                        <?php
															} else {
														?>

                                        <option class="letraPequena" inicio="<?php echo $fin; ?>"
                                            fin="<?php echo $inicio; ?>">Semana
                                            <?php echo $semanas.' - '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?>
                                        </option>
                                        <?php
															}
														?>

                                        <?php

									                    $i++;
									                    $semanas--;

												        
												    } while( (date('Y').'-01-01' < $fin) );
												?>
                                        <!--  -->
                                    </select>
                                </div>
                                <!-- FIN SEMANA Y LIBRE -->



                            </div>
                        </div>


                    </div>




                </div>





            </div>

        </form>
        <!-- FIN BUSCADOR -->
    </div>
</div>
<!--  -->



<!-- INDICADORES -->
<div class="row">

    <div class="col-md-12">

        <div class="card" style="border-radius: 20px; display: none;">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <span class="letraMediana">Cobrado <br><input id="cobrado" disabled=""
                                style="width: 100px;"></span>
                        <br>
                        <span class="letraMediana">Adeudo <br><input id="adeudo" disabled=""
                                style="width: 100px;"></span>
                    </div>

                    <div class="col-md-3">
                        <span class="letraMediana">% Cobrado <br><input id="porcentaje" disabled=""
                                style="width: 100px;"></span>
                        <br>
                        <span class="letraMediana">Potencial <br><input id="potencial" disabled=""
                                style="width: 100px;"></span>
                    </div>

                    <div class="col-md-3">
                        <span class="letraMediana">Efectivo <br><input id="efectivo" disabled=""
                                style="width: 100px;"></span>
                        <br>
                        <span class="letraMediana">A cuenta <br><input id="cuenta" disabled=""
                                style="width: 100px;"></span>
                        <!-- <span class="letraMediana">Tarjeta <br><input id="tarjeta" disabled="" style="width: 100px;"></span> -->
                    </div>

                    <div class="col-md-3">
                        <!-- <span class="letraMediana">Depósito <br><input id="deposito" disabled="" style="width: 100px;"></span>
						<br>
						<span class="letraMediana">Otros <br><input id="otros" disabled="" style="width: 100px;"></span> -->
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- FIN INDICADORES -->

<div class="table-responsive">
    <table class="table table-hover table-striped" id="myTable_2" width="100%">
        <thead>

            <th class="letraMediana">Acción</th>

            <th class="letraMediana">Folio de pago</th>
            <th class="letraMediana">Estatus</th>
            <th class="letraMediana">Concepto</th>
            <th class="letraMediana">Adeudo</th>
            <th class="letraMediana">Cobrado</th>
            <th class="letraMediana">Vencimiento</th>
            <th class="letraMediana">Forma de pago</th>
            <!-- 		
			<th class="letraMediana">Matrícula</th>
			<th class="letraMediana">Foto</th>
			<th class="letraMediana">Alumno</th>
			<th class="letraMediana">Teléfonos</th>

			<th class="letraMediana">Grupo</th>
			<th class="letraMediana">Programa</th>

				
			<th class="letraMediana">Notas</th>

			<th class="letraMediana">Tipo</th>

			<th class="letraMediana">Requiere factura</th> -->


        </thead>
        <tbody>

        </tbody>
    </table>

</div>


<script>
// BUSCADOR

$('#formulario_buscador').on('submit', function() {
    event.preventDefault();
    /* Act on the event */
    var valor = $('#palabra').val();

    if (valor.length >= 3) {

        obtener_tabla_pagos();

    } else if (valor == 0) {

        obtener_tabla_pagos();

    }

});



$('.checkboxTipoPago').on('change', function() {
    //event.preventDefault();
    /* Act on the event */
    obtener_tabla_pagos();


});

$('#selectorMes').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_tabla_pagos();


});


$('#selectorAnnio').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_tabla_pagos();


});



// 
$('#selectorSemana').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_tabla_pagos();


});


$('.filtrosFecha').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_tabla_pagos();
    // alert( radioReporte );

});
// 


// $('#palabra').on('keyup', function(event) {
//     event.preventDefault();
//     /* Act on the event */

//     var valor = $('#palabra').val();

//     if ( valor == '' ) {

//         obtener_tabla_pagos();

//     }



// });

// FIN BUSCADOR
</script>

<script>
// FORMULARIO DE CALENDARIO DE PAGOS
$('#formulario_asociar_calendario_pago').on('submit', function(event) {
    event.preventDefault();

    $("#btn_formulario_asociar_calendario_pago").attr('disabled', 'disabled');

    var formulario_asociar_calendario_pago = new FormData($('#formulario_asociar_calendario_pago')[0]);

    var formulario_asociar_calendario_pago = new FormData($('#formulario_pago_alumnos')[0]);
    formulario_asociar_calendario_pago.append('id_gen_pag', $('#selector_calendario_pago2 option:selected')
        .val());
    formulario_asociar_calendario_pago.append('id_pag', $('#id_pag_calendario_pago2').val());

    $.ajax({

        url: 'server/editar_pago.php',
        type: 'POST',
        data: formulario_asociar_calendario_pago,
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta) {
            console.log(respuesta);

            swal("Guardado correctamente", "Continuar", "success", {
                button: "Aceptar",
            }).
            then((value) => {

                $("#btn_formulario_asociar_calendario_pago").removeAttr('disabled');
                obtener_tabla_pagos();

                $("#modal_asociar_calendario_pago").modal('hide');
            });

        }
    });

});
</script>

<script>
// FORMULARIO NOTAS PAGO

$("#btn_formulario_notas_pago").removeAttr('disabled');
$('#formulario_notas_pago').on('submit', function(event) {
    event.preventDefault();

    $("#btn_formulario_notas_pago").attr('disabled', 'disabled');

    var id_pag = $('#id_pag6').val();

    var formulario_notas_pago = new FormData($('#formulario_notas_pago')[0]);

    $.ajax({

        url: 'server/agregar_nota_pago.php',
        type: 'POST',
        data: formulario_notas_pago,
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta) {
            console.log(respuesta);

            swal("Agregado correctamente", "Continuar", "success", {
                button: "Aceptar",
            }).
            then((value) => {

                for (var i = 0; i < $('.cobrosMostrar').length; i++) {
                    if ($('.cobrosMostrar').eq(i).prop("checked") == true) {
                        //console.log($('.checador1').eq(i).val());

                        var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor");
                    }
                }

                $("#btn_formulario_notas_pago").removeAttr('disabled');
                obtener_tabla_pagos();



                $.ajax({
                    url: 'server/obtener_notas_pago.php',
                    type: 'POST',
                    data: {
                        id_pag
                    },
                    success: function(respuesta) {

                        // $("#modal_notas_pago").modal('show');
                        $("#contenedor_notas_pago").html(respuesta);
                        setTimeout(function() {
                            $('#con_not_pag').val('').focus();
                        }, 200);

                    }
                });
                // $("#modal_notas_pago").modal('hide');
            });

        }
    });

});
// FIN FORMULARIO ABONO

//FORMULARIO ABONO
$("#btn_abono_pago").removeAttr('disabled');
$('#formularioAbonoPago').on('submit', function(event) {
    event.preventDefault();

    $("#btn_abono_pago").attr('disabled', 'disabled');

    var formularioAbonoPago = new FormData($('#formularioAbonoPago')[0]);

    $.ajax({

        url: 'server/agregar_abono_pago.php',
        type: 'POST',
        data: formularioAbonoPago,
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta) {
            console.log(respuesta);

            swal("Agregado correctamente", "Continuar", "success", {
                button: "Aceptar",
            }).
            then((value) => {

                for (var i = 0; i < $('.cobrosMostrar').length; i++) {
                    if ($('.cobrosMostrar').eq(i).prop("checked") == true) {
                        //console.log($('.checador1').eq(i).val());

                        var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor");
                    }
                }

                $("#btn_abono_pago").removeAttr('disabled');
                $("#modalAbono").modal('hide');

                obtener_tabla_pagos();

                $('#tabla_alumnos').DataTable().ajax.reload();

                // 
                var palabra = $('#palabra').val();
                var inicio = $('#inicio').val();
                var fin = $('#fin').val();

                var id_gen = [];

                for (var i = 0, j = 0; i < $(".checkboxGeneraciones").length; i++) {

                    if ($(".checkboxGeneraciones")[i].checked == true) {
                        // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

                        id_gen[j] = $('.checkboxGeneraciones').eq(i).val();

                        j++;

                    }
                }
                // 
                $.ajax({
                    url: 'server/obtener_listado_generaciones.php',
                    type: 'POST',
                    data: {
                        id_gen,
                        palabra,
                        inicio,
                        fin
                    },
                    success: function(respuesta) {

                        // console.log( respuesta );
                        $('#contenedor_visualizacion').html(respuesta);

                    }

                });

            });

        }
    });

});
// FIN FORMULARIO ABONO


// FORMULARIO CONDONACION
$("#btn_condonacion").removeAttr('disabled');
$('#formularioCondonacion').on('submit', function(event) {
    event.preventDefault();

    $("#btn_condonacion").attr('disabled', 'disabled');

    var formularioCondonacion = new FormData($('#formularioCondonacion')[0]);

    $.ajax({

        url: 'server/agregar_condonacion_pago.php',
        type: 'POST',
        data: formularioCondonacion,
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta) {
            console.log(respuesta);

            if (respuesta == 'Exito') {
                swal("Agregado correctamente", "Continuar", "success", {
                    button: "Aceptar",
                }).
                then((value) => {




                    for (var i = 0; i < $('.cobrosMostrar').length; i++) {
                        if ($('.cobrosMostrar').eq(i).prop("checked") == true) {
                            //console.log($('.checador1').eq(i).val());

                            var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor");
                        }
                    }

                    // obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
                    $("#btn_condonacion").removeAttr('disabled');
                    obtener_tabla_pagos();

                    $("#modalCondonacion").modal('hide');
                });

            }

        }
    });

});
// FIN FORMULARIO CONDONACION


// FORMULARIO PRORROGA
$("#btn_convenio").removeAttr('disabled');
$('#formularioConvenio').on('submit', function(event) {

    // CODIGO
    event.preventDefault();
    $("#btn_convenio").attr('disabled', 'disabled');

    var formularioConvenio = new FormData($('#formularioConvenio')[0]);

    $.ajax({

        url: 'server/agregar_convenio_pago.php',
        type: 'POST',
        data: formularioConvenio,
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta) {
            console.log(respuesta);

            if (respuesta == 'Exito') {
                swal("Agregado correctamente", "Continuar", "success", {
                    button: "Aceptar",
                }).
                then((value) => {


                    for (var i = 0; i < $('.cobrosMostrar').length; i++) {
                        if ($('.cobrosMostrar').eq(i).prop("checked") == true) {
                            //console.log($('.checador1').eq(i).val());

                            var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor");
                        }
                    }

                    $("#btn_convenio").removeAttr('disabled');
                    obtener_tabla_pagos();
                    // obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
                    $("#modalConvenio").modal('hide');


                });

            }

        }
    });

    // FIN CODIGO
});
// FIN FORMULARIO PRORROGA	

// 
</script>

<script>
$('.radioPeriodo').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_tabla_pagos();
    // alert( radioReporte );

});




obtener_tabla_pagos();

function obtener_tabla_pagos() {
    // $('#myTable_2').DataTable().ajax.reload();

    var palabra = $('#palabra').val();


    var radioPeriodo = $(".radioPeriodo:checked").val();

    if (radioPeriodo == 'Fecha') {

        var inicio = $('#inicio').val();
        var fin = $('#fin').val();

        $('#contenedor_fecha').css('display', '');
        $('#contenedor_semana').css('display', 'none');
        $('#contenedor_mes_annio').css('display', 'none');


    } else if (radioPeriodo == 'Semana') {

        var inicio = $('#selectorSemana option:selected').attr('inicio');
        var fin = $('#selectorSemana option:selected').attr('fin');

        $('#contenedor_mes_annio').css('display', 'none');
        $('#contenedor_fecha').css('display', 'none');
        $('#contenedor_semana').css('display', '');


    } else if (radioPeriodo == 'Mes') {


        $('#contenedor_mes_annio').css('display', '');
        $('#contenedor_fecha').css('display', 'none');
        $('#contenedor_semana').css('display', 'none');

        var diaInicio = $('#selectorMes option:selected').attr('inicio');
        var diaFin = $('#selectorMes option:selected').attr('fin');
        var mes = $('#selectorMes option:selected').val();
        var annio = $('#selectorAnnio option:selected').val();

        var inicio = annio + '-' + mes + '-' + diaInicio;
        var fin = annio + '-' + mes + '-' + diaFin;


    }


    var tipo_pago = [];
    var j = 0;

    if ($("#tipoPago1")[0].checked == true) {
        // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

        tipo_pago[j] = $("#tipoPago1").val();
        j++;

    }


    if ($("#tipoPago2")[0].checked == true) {
        // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );

        tipo_pago[j] = $("#tipoPago2").val();
        j++;

    }



    if ($("#tipoPago3")[0].checked == true) {
        // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxGeneraciones').eq(i).attr("annio")+" esta seleccionado"  );
        tipo_pago[j] = $("#tipoPago3").val();

    }


    // for( var i = 0; i < tipo_pago.length; i++ ){
    // 	console.log( tipo_pago[i] );	
    // }

    // PETICION POR POST
    var id_alu_ram = '';
    var id_gen = '';

    <?php  
        	if ( isset( $_POST['id_alu_ram'] ) ) {
        ?>
    var id_alu_ram = <?php echo $_POST['id_alu_ram']; ?>;
    <?php
        	} else if ( isset( $_POST['id_gen'] ) ) {
        ?>
    var id_gen = <?php echo $_POST['id_gen']; ?>;
    <?php
        	}
        ?>
    // FIN PETICION POR POST

    setTimeout(function() {

        // INDICADORES
        $.ajax({
            url: 'server/obtener_indicadores_cobranza_vencidos.php',
            type: 'POST',
            dataType: 'json',
            data: {
                palabra,
                inicio,
                fin,
                tipo_pago,
                id_alu_ram,
                id_gen
            },
            success: function(datos) {

                // $('#contenedor_datos').val( datos );
                // console.log( datos );

                $('#cobrado').val(datos.cobrado);
                $('#adeudo').val(datos.adeudo);
                $('#porcentaje').val(datos.porcentaje + '%');
                $('#potencial').val(datos.potencial);
                $('#efectivo').val(datos.efectivo);
                $('#tarjeta').val(datos.tarjeta);
                $('#deposito').val(datos.deposito);
                $('#otros').val(datos.otros);

                $('#cuenta').val(datos.cuenta);

            }
        });

    }, 200);


    // DATATABLE
    $('#myTable_2').DataTable().destroy();
    $('#myTable_2').DataTable({

        dom: 'Bfrtpi',

        scrollX: true,
        scrollY: true,

        buttons: [

            {
                extend: 'excelHtml5',
                className: 'btn btn-info btn-rounded btn-sm',
                messageTop: 'Historial de pagos',
                title: 'Historial de pagos',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6]
                }
            }

        ],
        "pageLength": -1,
        // "columnDefs": [
        //   { 
        //   	"orderable": false, 
        //   	"targets": [ 0, 2, 3 ] 
        //   }
        // ],
        "processing": true,
        "serverSide": true,
        "order": [],
        "searching": false,

        "ajax": {
            url: "server/obtener_pagos_vencidos_alumno.php",
            type: "POST",
            data: {
                palabra,
                inicio,
                fin,
                tipo_pago,
                id_alu_ram,
                id_gen
            }
        },


        "fnDrawCallback": function(oSettings) {
            //ACCIONES
            $('.checkboxFacturacionPago').on('change', function(event) {
                // event.preventDefault();
                /* Act on the event */

                console.log('check');
                var elemento = $(this);
                var id_pag = $(this).attr('id_pag');
                var fac_pag = $(this).val();
                // alert( fac_pag );

                if (fac_pag == 'Activo') {

                    elemento.val('Inactivo');

                } else {

                    elemento.val('Activo');
                }

                $.ajax({
                    url: 'server/editar_pago.php',
                    type: 'POST',
                    data: {
                        id_pag,
                        fac_pag
                    },

                    success: function(respuesta) {

                        console.log(respuesta);
                        generarAlerta('Cambios guardados');
                        // obtener_tabla_pagos();

                    }
                });

            });
            // MODAL ALUMNO
            $(".consultaAlumno").on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                var id_alu_ram = $(this).attr("id_alu_ram");
                $.ajax({
                    url: 'server/obtener_consulta_alumno.php',
                    type: 'POST',
                    data: {
                        id_alu_ram
                    },
                    success: function(respuesta) {
                        $("#modalConsultaAlumno").modal('show');
                        $("#contenedorConsultaAlumno").html(respuesta);
                    }
                });

            });
            // FIN MODAL ALUMNO



            //ABONO DEL COBRO
            $('.abonarCobro').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                $('#mon_abo_pag').off('change');
                $('#mon_abo_pag').off('keyup');

                id_pag = $(this).attr("id_pag");

                // 
                $.ajax({
                    url: 'server/obtener_pago.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { id_pag },
                    success: function(datos) {
                        var montoAdeudo = parseFloat(datos.mon_pag);

                        $('#modalAbono').modal('show');

                        $('#id_pag1').attr({ value: datos.id_pag });
                        $('#mon_pag_abono').attr({ value: datos.mon_pag });
                        $('#his_pag_abono').attr({ value: datos.his_pag });

                        $('#conceptoAbono').text(datos.con_pag);
                        $('#montoAdeudoAbono').text("$" + datos.mon_pag);
                        $("#diferenciaAbono").text("$" + datos.mon_pag);

                        seleccionTipoPagoAbono();

                        // OBTENCIÓN DEL VALOR DEL SELECT POR CONFLICTOS DE SELECTS CON AJAX
                        $(".selectorAbono").eq(0).find('input').attr({
                            "value": $('#tip2_abo_pag').val()
                        });

                        $("#tip2_abo_pag").on('change keyup', function(event) {
                            event.preventDefault();
                            seleccionTipoPagoAbono();
                        });

                        function seleccionTipoPagoAbono() {
                            var tip2_abo_pag = $("#tip2_abo_pag").val();

                            if (tip2_abo_pag === 'Dinero Digital') {
                                // Acciones específicas para "Dinero Digital"
                            } else {
                                $("#contenedor_seleccion_tipo_pago_abono").html(`
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="saldarAbono">
                                        <label class="letraPequena font-weight-normal" for="saldarAbono">Saldar</label>
                                    </div>
                                    <div class="md-form">
                                        <i class="fas fa-dollar-sign prefix"></i>
                                        <input type="number" name="mon_abo_pag" id="mon_abo_pag" min="0" step="1" required class="form-control" value="0">
                                        <label for="mon_abo_pag" data-error="wrong" class="active" data-success="right">Cantidad...</label>
                                    </div>
                                `);

                                $("#mon_abo_pag").focus();

                                obtener_seleccion_tipo_condonacion();

                                $("#saldarAbono").on('change', function(event) {
                                    event.preventDefault();
                                    if ($("#saldarAbono")[0].checked) {
                                        $("#mon_abo_pag").val(datos.mon_pag).focus();
                                    } else {
                                        $("#mon_abo_pag").val(0).focus();
                                    }
                                    obtener_seleccion_tipo_condonacion();
                                });

                                $("#mon_abo_pag").on('change keyup', function(event) {
                                    event.preventDefault();
                                    obtener_seleccion_tipo_condonacion();
                                });

                                function obtener_seleccion_tipo_condonacion() {
                                    var mon_abo_pag = $("#mon_abo_pag").val();
                                    var diferenciaAbono = montoAdeudo - mon_abo_pag;

                                    $("#diferenciaAbono").text("$" + diferenciaAbono.toFixed(2));

                                    if (mon_abo_pag > montoAdeudo) {
                                        $("#diferenciaAbono")
                                            .text("Error")
                                            .removeClass('text-info')
                                            .addClass('text-danger');

                                        swal("Error en las cantidades", "¡La cantidad NO puede exceder el monto original!", "error", {
                                            button: "Aceptar",
                                        }).then((value) => {
                                            if (value) {
                                                $("#diferenciaAbono")
                                                    .text("$" + montoAdeudo)
                                                    .removeClass('text-danger')
                                                    .addClass('text-info');
                                                $("#mon_abo_pag").val(0);
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    }
                });
                // 



            });
            // FIN ABONO DEL COBRO




            //CONDONACION DEL PAGO
            $('.condonarCobro').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                $('#cantidadCondonacion').off('change');
                $('#cantidadCondonacion').off('keyup');

                id_pag = $(this).attr("id_pag");
                $.ajax({
                    url: 'server/obtener_pago.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_pag
                    },
                    success: function(datos) {
                        //console.log(datos);

                        var montoAdeudo = 0;
                        montoAdeudo = parseFloat(datos.mon_pag);

                        //console.log("montocondonacion: "+montoAdeudo);

                        $('#modalCondonacion').modal('show');


                        $('#id_pag2').attr({
                            value: datos.id_pag
                        });
                        $('#mon_pag').attr({
                            value: datos.mon_pag
                        });
                        $('#his_pag').attr({
                            value: datos.his_pag
                        });

                        $('#formularioCondonacion').trigger("reset");
                        // shit
                        $("#btn_condonacion").removeAttr('disabled', 'disabled');

                        $('#conceptoCondonacion').text(datos.con_pag);

                        $('#montoAdeudo').text("$" + datos.mon_pag);
                        // $('#identificador').attr({value: datos.id_blo});
                        $("#diferenciaCondonacion").val("$" + datos.mon_pag);

                        // ASIGNACION DE VALORES EN SELECTS EN ARCHIVOS AJAX NO AGARRA POR DEFAULT
                        $(".selectorCondonacion ").eq(0).find('input').attr({
                            "value": $('#tip1_con_pag').val()
                        });
                        $(".selectorCondonacion ").eq(2).find('input').attr({
                            "value": $('#motivoCondonacion').val()
                        });

                        obtener_seleccion_tipo_condonacion();

                        $('#tip1_con_pag').on('change', function(event) {
                            event.preventDefault();
                            /* Act on the event */
                            obtener_seleccion_tipo_condonacion();

                        });

                        // SELECTOR DEL TIPO DE CONDONACION
                        function obtener_seleccion_tipo_condonacion() {
                            if ($('#tip1_con_pag').val() == 'Monetario') {
                                // MONETARIO
                                $("#contenedor_tipo_condonacion").html(
                                    '<i class="fas fa-dollar-sign prefix"></i> <input type="number" name="cantidadCondonacion" id="cantidadCondonacion" min="0" step=".001" required="" class="form-control"> <label for="cantidadCondonacion" data-error="wrong" data-success="right">Cantidad...</label>'
                                );

                                $("#diferenciaCondonacion").val("$" + datos.mon_pag);


                                $("#cantidadCondonacion").on('change keyup', function(
                                    event) {
                                    event.preventDefault();
                                    /* Act on the event */

                                    //console.log(datos);
                                    var diferenciaCondonacion = 0;

                                    var cantidadCondonacion = $(
                                        "#cantidadCondonacion").val();
                                    //var montoAdeudo = datos.mon_pag;
                                    diferenciaCondonacion = montoAdeudo -
                                        cantidadCondonacion;

                                    $("#diferenciaCondonacion").val("$" +
                                        diferenciaCondonacion.toFixed(2));

                                    if (cantidadCondonacion > montoAdeudo) {


                                        //console.log("condicionActiva montoAdeudo: "+datos.mon_pag);


                                        $("#diferenciaCondonacion").val("Error")
                                            .removeClass('text-info').addClass(
                                                'text-danger');

                                        swal("Error en el descuento",
                                            "¡La cantidad condonada NO puede exceder el monto original!",
                                            "error", {
                                                button: "Aceptar",
                                            }).then((value) => {
                                            if (value) {
                                                //console.log(id_pag);
                                                // console.log("json: "+datos.mon_pag);
                                                // console.log("montocondonacion: "+montoAdeudo);
                                                $("#diferenciaCondonacion")
                                                    .val("$" +
                                                        montoAdeudo)
                                                    .removeClass(
                                                        'text-danger')
                                                    .addClass(
                                                        'text-info');
                                                $("#cantidadCondonacion")
                                                    .val(0);
                                            }
                                        });
                                    }

                                });

                                // FIN MONETARIO
                            } else {
                                // PORCENTUAL
                                $("#diferenciaCondonacion").val("$" + datos.mon_pag);



                                $("#contenedor_tipo_condonacion").html(
                                    '<i class="fas fa-percentage prefix"></i> <input type="number" name="cantidadCondonacion" id="cantidadCondonacion" min="1" max="100" step=".1" required="" class="form-control"> <label for="cantidadCondonacion" data-error="wrong" data-success="right">Cantidad...</label>'
                                );


                                $("#cantidadCondonacion").on('change keyup', function(
                                    event) {
                                    event.preventDefault();
                                    /* Act on the event */

                                    //console.log(datos);
                                    var diferenciaCondonacion = 0;

                                    var cantidadCondonacion = $(
                                        "#cantidadCondonacion").val();
                                    //var montoAdeudo = datos.mon_pag;




                                    if (cantidadCondonacion <= 100) {
                                        // VALIDADOR QUE CANTIDAD INGRESADA SEA MENOR O IGUAL A 100% 

                                        diferenciaCondonacion = montoAdeudo - ((
                                                cantidadCondonacion / 100) *
                                            (montoAdeudo));

                                        $("#diferenciaCondonacion").val("$" +
                                            diferenciaCondonacion.toFixed(2)
                                        );
                                        //console.log("condicionActiva montoAdeudo: "+datos.mon_pag);

                                    } else {
                                        $("#diferenciaCondonacion").val("Error")
                                            .removeClass('text-info').addClass(
                                                'text-danger');

                                        swal("Error en el descuento",
                                            "¡La cantidad porcentual condonada NO puede exceder el 100%!",
                                            "error", {
                                                button: "Aceptar",
                                            }).then((value) => {
                                            if (value) {
                                                //console.log(id_pag);
                                                $("#diferenciaCondonacion")
                                                    .val("$" +
                                                        montoAdeudo)
                                                    .removeClass(
                                                        'text-danger')
                                                    .addClass(
                                                        'text-info');
                                                $("#cantidadCondonacion")
                                                    .val(0);
                                            }
                                        });
                                    }

                                });

                                // FIN PORCENTUAL
                            }
                        }




                        // SELECCION OTROS Y ADICION DE INPUT
                        $("#motivoCondonacion").on('change', function(event) {
                            event.preventDefault();
                            /* Act on the event */

                            if ($('#motivoCondonacion').val() == 'otros') {

                                $("#contenedorMotivoCondonacion").html(
                                    '<div class="md-form"><i class="fas fa-info-circle prefix"></i><textarea name="motivoCondonacionOtros" id="motivoCondonacionOtros" class="md-textarea form-control" rows="3" autofocus required=""></textarea><label for="form10">Asigna un motivo...</label></div>'
                                );
                            } else {

                                $("#contenedorMotivoCondonacion").html('');
                            }
                        });

                    }
                });


            });
            // FIN CONDONACION DEL COBRO





            // CONDONACION NO DISPONIBLE
            $(".condonarCobroInvalido").on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                swal("¡Ya existe una solicitud de condonación pendiente!",
                    "Solicita al Administrador que atienda las peticiones de condonación",
                    "info", {
                        button: "Aceptar",
                    });

            });




            //ELIMINACION DE PAGO
            $('.eliminacionCobro').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                var pago = $(this).attr("id_pag");
                var nombrePago = $(this).attr("con_pag");


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
                        if (name) {
                            //console.log(name);
                            var password = name;
                            $.ajax({

                                url: 'server/validacion_permisos.php',
                                type: 'POST',
                                data: {
                                    password
                                },
                                success: function(respuesta) {
                                    console.log(respuesta);

                                    if (respuesta == 'True') {
                                        swal("Validado correctamente", "Continuar",
                                            "success", {
                                                button: "Aceptar",
                                            }).
                                        then((value) => {
                                            console.log(
                                                "Existe el password");
                                            swal({
                                                title: "¿Deseas eliminar " +
                                                    nombrePago +
                                                    "?",
                                                text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
                                                icon: "warning",
                                                buttons: {
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
                                                        url: 'server/eliminacion_pago.php',
                                                        type: 'POST',
                                                        data: {
                                                            pago
                                                        },
                                                        success: function(
                                                            respuesta
                                                        ) {

                                                            if (respuesta ==
                                                                "true"
                                                            ) {
                                                                console
                                                                    .log(
                                                                        "Exito en consulta"
                                                                    );
                                                                swal("Eliminado correctamente",
                                                                        "Continuar",
                                                                        "success", {
                                                                            button: "Aceptar",
                                                                        }
                                                                    )
                                                                    .
                                                                then((value) => {
                                                                    for (
                                                                        var i =
                                                                            0; i <
                                                                        $(
                                                                            '.cobrosMostrar'
                                                                        )
                                                                        .length; i++
                                                                    ) {
                                                                        if ($(
                                                                                '.cobrosMostrar'
                                                                            )
                                                                            .eq(
                                                                                i
                                                                            )
                                                                            .prop(
                                                                                "checked"
                                                                            ) ==
                                                                            true
                                                                        ) {
                                                                            //console.log($('.checador1').eq(i).val());

                                                                            var cobrosMostrar =
                                                                                $(
                                                                                    ".cobrosMostrar"
                                                                                )
                                                                                .eq(
                                                                                    i
                                                                                )
                                                                                .attr(
                                                                                    "valor"
                                                                                );
                                                                        }
                                                                    }

                                                                    obtener_tabla_pagos
                                                                        ();
                                                                    // 
                                                                    // obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
                                                                    // $("#modalConvenio").modal('hide');
                                                                });
                                                            } else {
                                                                console
                                                                    .log(
                                                                        respuesta
                                                                    );

                                                            }

                                                        }
                                                    });

                                                }
                                            });




                                        });

                                    } else {
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


                        } else {
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


            });




            //CONVENIOS DE FECHA

            $(".convenirCobro").on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                //alert("convenio");
                $('#formularioConvenio').trigger("reset");

                $('#inicioConvenio').off('change');
                $('#inicioConvenio').off('keyup');
                $('#finConvenio').off('change');
                $('#finConvenio').off('keyup');

                id_pag = $(this).attr("id_pag");

                //alert(id_pag);
                $.ajax({
                    url: 'server/obtener_pago.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_pag
                    },
                    success: function(datos) {
                        console.log(datos);

                        if (datos.est_pag == 'Pendiente') {
                            // IF QUE VALIDA EL PAGO PENDIENTE

                            $('#modalConvenio').modal('show');

                            $('#id_pag').attr({
                                value: datos.id_pag
                            });
                            $('#ini_pagConvenio').attr({
                                value: datos.ini_pag
                            });
                            $('#fin_pagConvenio').attr({
                                value: datos.fin_pag
                            });

                            $("#inicioCobroConvenio").text(moment(datos.ini_pag).format(
                                "DD/MM/YYYY"));
                            $("#finCobroConvenio").text(moment(datos.fin_pag).format(
                                "DD/MM/YYYY"));

                            $("#conceptoConvenio").text(datos.con_pag);

                            $("#inicioConvenio").val(moment(
                                '<?php echo date('Y-m-d'); ?>').format(
                                "DD/MM/YYYY"));
                            $("#finConvenio").val(moment('<?php echo date('Y-m-d'); ?>')
                                .format("DD/MM/YYYY"));

                            $("#inicio_convenio").on('change keyup', function(event) {
                                event.preventDefault();
                                /* Act on the event */
                                var hoy = moment(new Date()).format(
                                    "YYYY-MM-DD");

                                var inicioConvenio = $(this).val();

                                var finConvenio = $('#fin_convenio').val();

                                console.log(finConvenio);

                                // console.log("hoy :"+hoy);
                                // console.log("inicioConvenio :"+inicioConvenio);

                                if (moment(inicioConvenio).isSameOrAfter(hoy)) {
                                    $("#inicioConvenio").val(moment($(
                                            "#inicio_convenio").val())
                                        .format("DD/MM/YYYY"));
                                } else {

                                    $("#inicioConvenio").val("Error").addClass(
                                        'text-danger');

                                    swal("Error en las fechas del convenio",
                                        "¡La fecha de inicio debe ser mayor o igual a la fecha de hoy!",
                                        "error", {
                                            button: "Aceptar",
                                        }).then((value) => {
                                        if (value) {

                                            $("#inicioConvenio").val(
                                                    moment(hoy).format(
                                                        "DD/MM/YYYY"))
                                                .removeClass(
                                                    'text-danger');
                                            $("#inicio_convenio").val(
                                                hoy);
                                        }
                                    });
                                }

                                if (moment(inicioConvenio).isSameOrBefore(
                                        finConvenio)) {
                                    $("#inicioConvenio").val(moment($(
                                            "#inicio_convenio").val())
                                        .format("DD/MM/YYYY"));
                                } else {

                                    $("#inicioConvenio").val("Error").addClass(
                                        'text-danger');

                                    swal("Error en las fechas del convenio",
                                        "¡La fecha de inicio debe ser menor o igual a la fecha de fin!",
                                        "error", {
                                            button: "Aceptar",
                                        }).then((value) => {
                                        if (value) {

                                            $("#inicioConvenio").val(
                                                    moment(hoy).format(
                                                        "DD/MM/YYYY"))
                                                .removeClass(
                                                    'text-danger');
                                            $("#inicio_convenio").val(
                                                hoy);
                                        }
                                    });
                                }

                            });



                            $("#fin_convenio").on('change keyup', function(event) {
                                event.preventDefault();
                                /* Act on the event */
                                //var hoy =  moment( new Date() ).format("YYYY-MM-DD");

                                var inicioConvenio = $('#inicio_convenio')
                                    .val();

                                var finConvenio = $(this).val();

                                // console.log("finConvenio: "+finConvenio);

                                // // console.log("hoy :"+hoy);
                                // console.log("inicioConvenio :"+inicioConvenio);

                                if (moment(finConvenio).isSameOrAfter(
                                        inicioConvenio)) {
                                    $("#finConvenio").val(moment($(
                                        "#fin_convenio").val()).format(
                                        "DD/MM/YYYY"));
                                } else {

                                    $("#finConvenio").val("Error").removeClass(
                                        'text-info').addClass('text-danger');

                                    swal("Error en las fechas del convenio",
                                        "¡La fecha de fin debe ser mayor o igual a la fecha de inicio!",
                                        "error", {
                                            button: "Aceptar",
                                        }).then((value) => {
                                        if (value) {

                                            $("#finConvenio").val(
                                                    moment(
                                                        inicioConvenio)
                                                    .format(
                                                        "DD/MM/YYYY"))
                                                .removeClass(
                                                    'text-danger');
                                            $("#fin_convenio").val(
                                                inicioConvenio);
                                        }
                                    });
                                }


                            });

                            // MOTIVO CONVENIO
                            $("#motivoConvenio").on('change', function(event) {
                                event.preventDefault();
                                /* Act on the event */
                                if ($("#motivoConvenio")[0].checked == true) {
                                    $("#contenedor_motivo_convenio").html(
                                        '<div class="md-form"><textarea id="textarea-char-counter" name="mot_acu_pag" class="form-control md-textarea" length="1000" rows="3"></textarea><label for="textarea-char-counter" class="active">Agrega un motivo (Opcional)</label></div>'
                                    );

                                    $("#textarea-char-counter").focus();

                                } else {

                                    $("#contenedor_motivo_convenio").html('');
                                }
                            });



                            // FIN IF QUE VALIDA PAGO PENDIENTE
                        } else {

                            swal("Error en las fechas del convenio",
                                "¡No se puede convenir debido a que ya fue pagado!",
                                "error", {
                                    button: "Aceptar",
                                });
                        }



                    }

                });



            });
            // FIN CONVENIOS DE FECHA

            // CONSULTA A HISTORIAL DE PAGO
            $(".historialPago").on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                var id_pag = $(this).attr("id_pag");

                $.ajax({
                    url: 'server/obtener_historial_pago.php',
                    type: 'POST',
                    data: {
                        id_pag
                    },
                    success: function(respuesta) {

                        $("#modalHistorialPago").modal('show');
                        $("#panzaModalHistorialPago").html(respuesta);
                    }
                });


            });
            // FIN HISTORIAL DE PAGO





            // NOTA DE PAGO
            $(".notaPago").on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                var id_pag = $(this).attr("id_pag");

                $.ajax({
                    url: 'server/obtener_notas_pago.php',
                    type: 'POST',
                    data: {
                        id_pag
                    },
                    success: function(respuesta) {

                        $('#id_pag6').val(id_pag);
                        $("#modal_notas_pago").modal('show');
                        setTimeout(function() {
                            $('#con_not_pag').focus();
                        }, 200);

                        $("#contenedor_notas_pago").html(respuesta);
                    }
                });


            });
            // FIN NOTA DE PAGO


            // CALENDARIO DE PAGO
            // NOTA DE PAGO
            $(".calendarioPago").on('click', function(event) {
                event.preventDefault();
                /* Act on the event */

                var id_pag = $(this).attr("id_pag");

                $.ajax({
                    url: 'server/obtener_asociar_calendario_pago.php',
                    type: 'POST',
                    data: {
                        id_pag
                    },
                    success: function(respuesta) {

                        $('#id_pag6').val(id_pag);
                        $("#modal_asociar_calendario_pago").modal('show');
                        setTimeout(function() {
                            $('#con_not_pag').focus();
                        }, 200);

                        $("#contenedor_asociar_calendario_pago").html(respuesta);
                    }
                });


            });
            // FIN NOTA DE PAGO

            // FIN CALENDARIO DE PAGO
            // 


            // FIN ACCIONES 
        },

        // LANGUAGE
        "language": {
            "sProcessing": '<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>',
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
        // FIN LANGUAGE        


    });


}
</script>