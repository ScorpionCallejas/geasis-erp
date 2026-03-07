<style>
  .dropdown-toggle::after
  {
    font-size: 2.2vh ;
    margin-left:  0em !important; 
  }
</style>

<?php  

  include('inc/header.php');

  $id_alu_ram = $_GET['id_alu_ram'];

  
  
  $sqlAlumno = "
    SELECT * 
		FROM alu_ram
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_alu_ram = '$id_alu_ram'
  ";

  $resultadoAlumno = mysqli_query($db, $sqlAlumno);
  $filaAlumno = mysqli_fetch_assoc($resultadoAlumno);

  // DATOS ALUMNO
  $nombreAlumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu']." ".$filaAlumno['apm_alu'];
  $tipo = $filaAlumno['tip_alu'];
  $ingreso = $filaAlumno['ing_alu'];
  $id_alu = $filaAlumno['id_alu'];
  
  // DATOS ALUMNO
  $fotoAlumno = $filaAlumno['fot_alu'];
  $ingresoAlumno = $filaAlumno['ing_alu'];
  $direccionAlumno = $filaAlumno['dir_alu'];
  $cpAlumno = $filaAlumno['cp_alu'];
  $coloniaAlumno = $filaAlumno['col_alu'];
  $delegacionAlumno = $filaAlumno['del_alu'];
  $entidadAlumno = $filaAlumno['ent_alu'];
  $saldoAlumno = $filaAlumno['sal_alu'];
  $bec_alu_ram = $filaAlumno['bec_alu_ram'];
  $matriculaAlumno = $filaAlumno['bol_alu'];

  $telefonoAlumno = $filaAlumno['tel_alu'];
  $telefono2Alumno = $filaAlumno['tel2_alu'];
  $correoAlumno = $filaAlumno['cor_alu'];
  $passwordAlumno = $filaAlumno['pas_alu'];

  // DATOS CARRERA
  $id_ram = $filaAlumno['id_ram3'];
  $nom_ram = $filaAlumno['nom_ram'];


?>


<!-- TITULO -->
<div class="row animated fadeIn">
  <div class="col-md-6 text-left">
    <span class="tituloPagina  badge blue-grey darken-4 hoverable" title="Información de pagos"><i class="fas fa-bookmark"></i> Información de Pagos: <?php echo $nombreAlumno; ?></span>
    <br>

    <span class="animated fadeIn badge blue-grey darken-4 hoverable" title="Títulos">
     Programa: <?php echo $nom_ram; ?></span>
    <br>

    <div class=" badge badge-warning animated fadeInUp delay-3s text-white">
      <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
      <i class="fas fa-angle-double-right"></i>
      <a class="text-white" href="reporte_cobranza.php" title="Vuelve a Cobranza del Plantel">Cobranza</a>
      <i class="fas fa-angle-double-right"></i>
      <a style="color: black;" href="" title="Estás aquí">Historial de Pagos</a>
    </div>
  
    <br>
    <br>
    <br>
    <br>

    <div class="card bg-light">
      <div class="card-body bg-light">
        <div class="row">
          

          <div class="col-md-4 text-center">
            <h4>
              <span class="badge badge-info">
                Cobros
              </span>
            </h4>
          </div>

          <div class="col-md-4 text-center mt-1">
            <div class="form-check form-check-inline" title="Listar todos los cobros">
              <input type="radio" class="form-check-input cobrosMostrar" id="todosCobros" name="inlineMaterialRadiosExample66" valor="todos">
              <label class="form-check-label letraPequena py-2" for="todosCobros" style="line-height: 100%;">Todos</label>
            </div>
          </div>

          <div class="col-md-4 text-center mt-1">
            <div class="form-check form-check-inline" title="Listar cobros al día de hoy" >
              <input type="radio" class="form-check-input cobrosMostrar" id="diaCobros"  name="inlineMaterialRadiosExample66" checked valor="hoy">
              <label class="form-check-label letraPequena py-2" for="diaCobros" style="line-height: 100%;">Al día de hoy</label>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
  

  <div class="col-md-3">
    
  </div>
  <!-- CARD ALUMNO -->
  <div class="col-md-3">
    <div class="card bg-light mb-3" style="max-width: 20rem;">
      <div class="card-header bg-light">
        
        <div class="row">
          <div class="col-md-3">
            <img src="../uploads/<?php echo $fotoAlumno; ?>" class="rounded-circle img-responsive" style="border-radius: 50%;" height="40px" width="40px">
          </div>
          <div class="col-md-9">
            <a class="consultaAlumno text-info" id_alu_ram="<?php echo $id_alu_ram; ?>">
              <?php echo $nombreAlumno; ?>
            </a>
            
          </div>
        </div>
        
        
      </div>
      <div class="card-body">
        <p class="card-text text-justify" style="font-size: 10px; !important">
          <strong>Matrícula:</strong> <?php echo $matriculaAlumno; ?>
          <br>
          <strong>Teléfono:</strong> <?php echo $telefonoAlumno; ?>
          <br>
          <strong>Ingreso:</strong> <?php echo fechaFormateadaCompacta($matriculaAlumno); ?>
          <br>
          <strong>Estatus de Pagos: </strong><?php echo obtenerEstatusPagoAlumno($id_alu_ram); ?>
          <br>
          <strong>Estatus Académico:</strong> <?php echo estatusAlumno($id_alu_ram, $id_ram); ?>
          <br>
          <!-- VALIDACION SI TIENE ESTATUS ACADEMICO INSCRITO -->
          <?php  
            $estatusAcademico = estatusAlumno($id_alu_ram, $id_ram);

            if ( $estatusAcademico == 'Inscrito' ) {
          ?>
              <strong>Carga:</strong> 

              <span class="btn btn-info btn-sm waves-effect horarioAlumno" id_alu="<?php echo $id_alu; ?>" id_alu_ram="<?php echo $id_alu_ram; ?>" title="Haz clic para ver horario de <?php echo $nombreAlumno; ?>">
                <?php echo estatusAlumnoTotalCarga($id_alu_ram, $id_ram); ?>  
              </span>
          <?php
            }
          ?>
        </p>
      </div>
    </div> 

  </div>
  <!-- FIN CARD ALUMNO -->
</div>
<!-- FIN TITULO -->



<!-- BOTON FLOTANTE AGREGAR PAGO-->
<a class="btn-floating btn-lg  flotante btn-info" title="Agregar Pago" id="agregarPago">
	<i class="fas fa-plus" ></i>
</a>
<!-- FIN BOTON FLOTANTE AGREGAR PAGO-->


<!-- BOTON FLOTANTE AGREGAR ABONO-->

<a type="button" class="btn-floating btn-lg  flotante btn-success waves-effect waves-light" id="btn_caja" style="bottom: 125px;" title="Abonar a cuenta...">
	<i class="fas fa-cash-register"></i>
</a>
<!-- FIN BOTON FLOTANTE AGREGAR ABONO-->




<!-- MODALES ACCIONES COBROS -->


<!-- CONSULTAS -->

<!-- HORARIO ALUMNO MODAL -->

<!-- Central Modal Small -->
<div class="modal fade" id="modalHorarioAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog modal-fluid" role="document">

    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="tituloModalHorarioAlumno"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="panzaModalHorarioAlumno">
        
        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Central Modal Small -->

<!-- FIN HORARIO ALUMNO MODAL -->

<!-- CONSULTA DE ABONOS -->
<div class="modal fade" id="modalAbonos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="myModalLabel">Historial de Abonos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="panzaModalAbonos">
        


        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA DE ABONOS -->


<!-- CONSULTA DE HISTORIAL DE PAGO -->
<div class="modal fade" id="modalHistorialPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="myModalLabel">Historial de Pago <span id="titulo_medio_historial"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="panzaModalHistorialPago">
        


        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA DE HISTORIAL DE PAGO -->



<!-- CONSULTA DE HISTORIAL DE ACCIONES AL PAGO -->
<div class="modal fade" id="modalAccionPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="myModalLabel">Historial <span id="titulo_tipo_accion"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="panzaModalAccionPago">
        


        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA DE HISTORIAL DE ACCIONES AL PAGO -->




<!-- FIN CONSULTAS -->



<!-- ACCIONES -->
<!-- CAJA INTELIGENTE -->
<div class="modal fade" id="modalCaja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="myModalLabel">
          <span class="fa-stack fa-1x">
            <i class="fas fa-cash-register fa-stack-1x animated rotateIn delay-1s"></i>
            <i class="far fa-circle fa-stack-2x animated pulse infinite" style="color:white"></i>
          </span> 
          Caja Inteligente
        </h4>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
        <form id="formularioAbono">

          <div class="row">
            
            <div class="col-md-6" id="contenedor_seleccion_tipo_pago_abono2">
              
              
            </div>

            <div class="col-md-6">
              <div class="md-form mb-5">
                <select class="selectorModalAbono md-form colorful-select dropdown-primary" id="tip_abo_pag" name="tip_abo_pag" required="">
                  <option value="Efectivo">Efectivo</option>
                  <option value="Tarjeta">Tarjeta de Crédito o Débito</option>
                  <option value="Depósito">Depósito</option>
                  <option value="Dinero Digital">Dinero Digital</option>
                  <option value="Otro">Otro</option>
                </select>

              </div>
            </div>





          </div>

          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 text-center" id="contenedor_proximo_cobro">
              
            </div>
            <div class="col-md-2"></div>
          </div>
          

          

          <div class="text-center mt-4">
            <button class="btn btn-success waves-effect waves-light" type="submit">
              Abonar  
            </button>
          </div>

        </form>

        


      </div>

    </div>
  </div>
</div>

<!-- FIN CAJA INTELIGENTE -->







<!-- CONTENIDO MODAL AGREGAR COBRO -->
<div class="modal fade text-left" id="agregarPagoModal">
  <div class="modal-dialog cascading-modal" role="document">
    <!--Content-->
    <div class="modal-content">

      <!--Modal cascading tabs-->
      <div class="modal-c-tabs">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs md-tabs tabs-2 grey darken-1 white-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#panel7" role="tab" title="Agregar cobro básico">
         
              
              Cobro Global
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#panel8" role="tab" title="Agregar cobro avanzado">


                Cobro Recurrente
            </a>
          </li>
        </ul>

        <!-- Tab panels -->
        <div class="tab-content">
          <!--Panel 7-->
          <div class="tab-pane fade in show active" id="panel7" role="tabpanel">

            <!--Body-->
            <form id="agregarPagoFormularioBasico" enctype="multipart/form-data" method="POST">
            <div class="modal-content">
    

              <div class="modal-body mx-3">
                <div class="row">
                  <div class="col-md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-info-circle prefix grey-text"></i>
                      <input type="text" id="con_pag1" name="con_pag" class="form-control validate estilo_input">
                      <label class="estilo_input" for="con_pag1">Concepto de Cobro</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-dollar-sign prefix grey-text"></i>
                      <input type="number" min="0" step=".5" id="mon_ori_pag1" name="mon_ori_pag" class="form-control validate estilo_input">
                      <label class="estilo_input" for="mon_ori_pag1">Monto</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-level-up-alt prefix grey-text"></i>
                      <input type="number" min="1" max="10" id="pri_pag1" name="pri_pag" class="form-control validate estilo_input" >
                      <label class="estilo_input" for="pri_pag1">Prioridad</label>
                    </div>
                  </div>

                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="md-form mb-5">
                      <i class="fas fa-sticky-note prefix grey-text"></i>
                      <input type="text" id="obs_pag1" name="obs_pag" class="form-control validate">
                      <label class="estilo_input" for="obs_pag1">Observaciones</label>
                    </div>
                  </div>
                </div>

              </div>
              <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-info" type="submit" id="btn_pago_basico">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
              </div>

            </div>
        </form>

          </div>
          <!--/.Panel 7-->

            <!--Panel 8-->
        <div class="tab-pane fade" id="panel8" role="tabpanel">

            <!--Body-->
              <form id="agregarPagoFormularioAvanzado" enctype="multipart/form-data" method="POST">
              <div class="modal-content">
      

                <div class="modal-body mx-3">

                  <div class="row">
                    <div class="col-md-4">
                      <div class="md-form mb-5">
                        <i class="fas fa-info-circle prefix grey-text"></i>
                        <input type="text" id=" con_pag" name=" con_pag" class="form-control validate">
                        <label class="estilo_input" for=" con_pag">Concepto de Cobro</label>
                      </div>

                    </div>
                    <div class="col-md-4">
                      <div class="md-form mb-5">
                        <i class="fas fa-dollar-sign prefix grey-text"></i>
                        <input type="number" min="0" step=".5" id="mon_ori_pag" name="mon_ori_pag" class="form-control validate estilo_input">
                        <label class="estilo_input" for="mon_ori_pag">Monto</label>
                      </div>

                    </div>
                    <div class="col-md-4">
                      <div class="md-form mb-5">

                        <i class="fas fa-level-up-alt prefix grey-text"></i>
                        <input type="number" min="1" max="10" id="pri_pag" name="pri_pag" class="form-control validate estilo_input" >
                        <label class="estilo_input" for="pri_pag">Prioridad</label>
                      </div>
                      
                    </div>
                  </div>

                  

                  <div class="row">
                    <div class="col-md-6">
                      <label class="estilo_input" for="ini_pag">Inicio del cobro</label><br>
                        <div class="md-form mb-2">
                          <i class="fas fa-calendar-check prefix grey-text"></i>
                          <input type="date" id="ini_pag" name="ini_pag" class="form-control validate estilo_input" required="">
                        </div>

                    </div>
                    <div class="col-md-6">
                      <label class="estilo_input" for="fin_pag">Fin del cobro</label><br>
                        <div class="md-form mb-2">
                          <i class="fas fa-calendar-check prefix grey-text"></i>
                          <input type="date" id="fin_pag" name="fin_pag" class="form-control validate estilo_input" required="">
                        </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div id="ocultar">
                        <span class="estilo_input">Fecha de Descuento</span>
                        <div class="md-form mb-2">
                          <i class="far fa-calendar-check prefix grey-text"></i>
                          <input type="date" id="pro_pag" name="pro_pag" class="form-control validate estilo_input" required>
                        </div>
                      </div>
                    </div>
                  </div>  

                  
                  <br>
                  
                  <div class="row">
                    <div class="col-md-12" style="top: -4vh;">
                      <label class="estilo_input">Periodicidad del Recargo ( Único / Recurrente )</label>     
                      <select class="selectorPago md-form colorful-select dropdown-primary estilo_input" id="int_pag" name="int_pag">
                          <option value="Única" selected>Única</option>
                          <option value="Recurrente">Recurrente</option>
                      </select>
                    </div>
                  </div>   
                              

                  <div class="row">
                    <div class="col-md-6" style="top: -8vh;">
                      <div class="md-form mb-3" id="divClase">
                        <i style="top: 6vh; position: relative;" class="fas fa-asterisk prefix grey-text"></i>
                        <label class="estilo_input" for="tip1_pqg"style="top: 6vh; position: relative;">Tipo de Descuento</label>
                        <br>
                        <!-- Group of material radios - option 1 -->
                        <select class="selectorPago md-form colorful-select dropdown-primary estilo_input" id="tip1_pag" name="tip1_pag">
                            
                            <option value="Monetario">Monetario</option> 
                          <option value="Porcentual">Porcentual</option>
                        </select>
                      </div>

                    </div>
                    <div class="col-md-6">
                      <div class="md-form mb-5">
                        <i id="icono_des" class="fas fa-dollar-sign prefix grey-text"></i>
                        <input type="number" min="0" step=".1" id="des_pag" name="des_pag" class="form-control validate estilo_input" >
                        <label class="estilo_input" for="des_pag">Cantidad de Descuento</label>
                      </div>

                    </div>
                  </div>
         
                  <div class="row">
                    <div class="col-md-6" style="top: -8vh;">
                      <div class="md-form mb-3" id="divClase">
                        <i style="top: 6vh; position: relative;" class="fas fa-asterisk prefix grey-text"></i>
                        <label class="estilo_input" for="tip2_pag" style="top: 6vh; position: relative;">Tipo de Cargo</label>
                        <br>
                        <!-- Group of material radios - option 1 -->
                        <select class="selectorPago md-form colorful-select dropdown-primary" id="tip2_pag" name="tip2_pag">
                            
                            <option value="Monetario">Monetario</option> 
                          <option value="Porcentual">Porcentual</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="md-form mb-5">
                        <i id="icono" class="fas fa-dollar-sign prefix grey-text"></i>
                        <input type="number" min="0" step=".1" id="car_pag" name="car_pag" class="form-control validate" >
                        <label  for="car_pag">Cantidad de Cargo</label>
                      </div>
                    </div>
                  </div>

                                


                  <div class="md-form mb-5">
                    <i class="fas fa-sticky-note prefix grey-text"></i>
                    <input type="text" id="obs_pag" name="obs_pag" class="form-control validate">
                    <label  for="obs_pag">Observaciones</label>
                  </div>


                </div>
                <div class="modal-footer d-flex justify-content-center">
                  <button class="btn btn-info" type="submit" id="btn_pago_avanzado">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
                </div>

              </div>
          </form>

            </div>
          <!--/.Panel 8-->
        </div>

      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR COBRO -->

<!-- FIN ACCIONES -->





<!-- FIN MODALES ACCIONES COBROS -->


  <!-- CONTENEDOR TABLA INDICADORES -->
  <div id="contenedor_pagos_alumno">
    
  </div>
  <!-- FIN CONTENEDOR TABLA INDICADORES -->







<?php  

  include('inc/footer.php');

?>

<script>
  // TABLA E INDICADORES

  var id_alu_ram = <?php echo $id_alu_ram; ?>;
  
  for(var i = 0; i < $('.cobrosMostrar').length; i++){
    if($('.cobrosMostrar').eq(i).prop("checked") == true){
      //console.log($('.checador1').eq(i).val());
      
      var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
    }
  }


  obtener_pagos_alumno(id_alu_ram, cobrosMostrar);


  $(".cobrosMostrar").on('change', function(event) {
    event.preventDefault();
    /* Act on the event */
    for(var i = 0; i < $('.cobrosMostrar').length; i++){
      if($('.cobrosMostrar').eq(i).prop("checked") == true){
        //console.log($('.checador1').eq(i).val());
        
        var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
      }
    }

    obtener_pagos_alumno(id_alu_ram, cobrosMostrar);

  });



  function obtener_pagos_alumno(id_alu_ram, cobrosMostrar){

    $("#contenedor_pagos_alumno").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
    
    $.ajax({
    
      url: 'server/obtener_pagos_alumno.php',
      type: 'POST',
      data: {id_alu_ram, cobrosMostrar},
      success: function(respuesta){
        $("#contenedor_pagos_alumno").html(respuesta);
        $(".modal-backdrop").removeClass('modal-backdrop');
      }

    });
  }
  
  
  


</script>




<script>
  // CONSULTA ABONOS
  $(".abonos").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_pag = $(this).attr("id_pag");

    $.ajax({
      url: 'server/obtener_abonos_pago.php',
      type: 'POST',
      data: {id_pag},
      success: function(respuesta){

        $("#modalAbonos").modal('show');
        $("#panzaModalAbonos").html(respuesta);
      }
    });
    

  });
</script>



<script>
  //CONSULTA DE HORARIO ALUMNO

  $(".horarioAlumno").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var edicionAlumno = $(this).attr("id_alu");
    var rama = <?php echo $id_ram; ?>;
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
</script>


<script>
  // CAJA
  $("#btn_caja").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    $('#abono').off('change');
    $('#abono').off('keyup');

    $('#formularioAbono').trigger("reset");
    $("#modalCaja").modal('show');

    $(".selectorModalAbono").materialSelect('destroy');
    $('.selectorModalAbono').materialSelect();

    var id_alu_ram = <?php echo $id_alu_ram; ?>;
    

    $("#contenedor_proximo_cobro").html('<h3 class="grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

    //  ON CHANGE SELECTOR
    seleccionTipoPagoAbonoCaja ();
    $("#tip_abo_pag").on('change', function(event) {
      event.preventDefault();
      /* Act on the event */
      seleccionTipoPagoAbonoCaja();
    });

    function seleccionTipoPagoAbonoCaja() {

      var tip_abo_pag = $("#tip_abo_pag").val();
      if ( tip_abo_pag == 'Dinero Digital' ) {

        var id_alu = '<?php echo $id_alu; ?>';
        var cajaSmart = true;

        $.ajax({
          url: 'server/obtener_datos_saldo_alumno.php',
          type: 'POST',
          data: { id_alu, cajaSmart },
          success: function ( respuesta ) {

            $("#contenedor_seleccion_tipo_pago_abono2").html(respuesta);
            cajaSmartPreview();

          }
        });
        
      } else {

        $("#contenedor_seleccion_tipo_pago_abono2").html('<div class="md-form mb-5"> <i class="fas fa-dollar-sign prefix grey-text"></i> <input type="number" class="form-control" id="abono" name="abono" min="0" step=".01" required="" value="0"><label for="form29" class="active">Abono...</label></div>');

        $("#abono").focus();
        cajaSmartPreview();
      
      }  
    }
    
    // FIN ONCHANGE SELECTOR

    function cajaSmartPreview() { 
      $.ajax({
        url: 'server/obtener_proximo_cobro.php',
        type: 'POST',
        data: {id_alu_ram},
        success: function(respuesta){

          $("#contenedor_proximo_cobro").html(respuesta);
        }
      });

      $("#abono").on('keyup change', function(event) {
        event.preventDefault();
        /* Act on the event */
        $("#contenedor_proximo_cobro").html('<h3 class="grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
        var abono = $("#abono").val(); 
        if ( abono == "") {

          $.ajax({
            url: 'server/obtener_proximo_cobro.php',
            type: 'POST',
            data: {id_alu_ram},
            success: function(respuesta){

              $("#contenedor_proximo_cobro").html(respuesta);
            }
          });

        }else {

          $.ajax({
            url: 'server/obtener_proximo_cobro.php',
            type: 'POST',
            data: {id_alu_ram, abono},
            success: function(respuesta){

              $("#contenedor_proximo_cobro").html(respuesta);
            }
          });

        }

      });
    }



    

  });

  $("#formularioAbono").on('submit', function(event) {

    event.preventDefault();
    /* Act on the event */

    var abono = $("#abono").val();
    var tip_abo_pag = $("#tip_abo_pag").val();

    $.ajax({
      url: 'server/agregar_abono.php',
      type: 'POST',
      data: {id_alu_ram, abono, tip_abo_pag},
      success: function(respuesta){
        console.log(respuesta);

        for(var i = 0; i < $('.cobrosMostrar').length; i++){
          if($('.cobrosMostrar').eq(i).prop("checked") == true){
            //console.log($('.checador1').eq(i).val());
            
            var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
          }
        }

        obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
        $("#modalCaja").modal('hide');
      }
    });

  });

</script>

<script>

  //FORMULARIO DE CREACION DE PAGO

  $("#ocultar").hide();
  $( "#ini_pag" ).change(function() {
      $("#ocultar").show();
        var fecha= $( "#ini_pag" ).val();
        var fecha_anticipo = new Date(fecha);
      fecha_anticipo = fecha_anticipo.setDate(fecha_anticipo.getDate() - 6);
      fecha_anticipo=new Date(fecha_anticipo);
      fecha_anticipo = new Date(fecha_anticipo.getTime() - (fecha_anticipo.getTimezoneOffset() * 60000 ))
                    .toISOString()
                    .split("T")[0];
      //alert('the new date is '+pago_anticipo);
      $( "#pro_pag" ).val(fecha_anticipo);
  });
  
  $("#tip2_pag").change(function() {
    var valor= $("#tip2_pag").val();
    if(valor=="Porcentual")
    {
      $("#icono").removeClass("fa-dollar-sing animated fadeIn").addClass("fa-percent animated fadeIn");
    }
    if(valor=="Monetario"){
      $("#icono").removeClass("fa-percent animated fadeIn").addClass("fa-dollar-sing animated fadeIn"); 
    }

  });
  $("#tip1_pag").change(function() {
    var valor= $("#tip1_pag").val();
    if(valor=="Porcentual")
    {
      $("#icono_des").removeClass("fa-dollar-sing animated fadeIn").addClass("fa-percent animated fadeIn");
    }
    if(valor=="Monetario"){
      $("#icono_des").removeClass("fa-percent animated fadeIn").addClass("fa-dollar-sing animated fadeIn"); 
    }

  });

  //CODIGO PARA AGREGAR PAGO NUEVO ABRIENDO MODAL
  $('#agregarPago').on('click', function(event) {
    event.preventDefault();
    $('#agregarPagoModal').modal('show');
    $('#agregarPagoFormulario').trigger("reset");
    $('#agregarPagoFormularioBasico').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
    $('.selectorPago').materialSelect('destroy');
    $('.selectorPago').materialSelect();
    $("#btn_pago_basico").removeAttr('disabled','disabled');
    $("#btn_pago_avanzado").removeAttr('disabled','disabled');

    

  });


  $('#agregarPagoFormularioBasico').on('submit', function(event) {
    event.preventDefault();
    $("#btn_pago_basico").attr('disabled','disabled');
    $.ajax({
      //PASAR VARIABLE POR URL PARA TOMAR POR GET EN EL SERVER AUNADO A LOS DATOS DEL FORMULARIO
      url: 'server/agregar_pago.php?id_alu_ram=<?php echo $id_alu_ram;?> ',
      type: 'POST',
      data: new FormData(agregarPagoFormularioBasico), 
      processData: false,
      contentType: false,
      cache: false,
      success: function(respuesta){
        console.log(respuesta);

        if (respuesta == 'Exito') {
          swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
          then((value) => {
            for(var i = 0; i < $('.cobrosMostrar').length; i++){
              if($('.cobrosMostrar').eq(i).prop("checked") == true){
                //console.log($('.checador1').eq(i).val());
                
                var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
              }
            }

            obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
            $("#agregarPagoModal").modal('hide');
          });
          
        }
      }
    });
  });


  $('#agregarPagoFormularioAvanzado').on('submit', function(event) {
    event.preventDefault();
    

    if ( $("#ini_pag").val() <= $("#fin_pag").val() ) {
      $("#btn_pago_avanzado").attr('disabled','disabled');
      $.ajax({
        //PASAR VARIABLE POR URL PARA TOMAR POR GET EN EL SERVER AUNADO A LOS DATOS DEL FORMULARIO
        url: 'server/agregar_pago.php?id_alu_ram=<?php echo $id_alu_ram;?> ',
        type: 'POST',
        data: new FormData(agregarPagoFormularioAvanzado), 
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta){
          console.log(respuesta);

          if (respuesta != '') {
            swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
            then((value) => {
              for(var i = 0; i < $('.cobrosMostrar').length; i++){
                if($('.cobrosMostrar').eq(i).prop("checked") == true){
                  //console.log($('.checador1').eq(i).val());
                  
                  var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
                }
              }

              obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
              $("#agregarPagoModal").modal('hide');
            });
            
          }
        }
      });
    }else{
      swal ( "Datos Incorrectos" ,  "¡Te recordamos que el inicio debe ser menor o igual al fin!" ,  "error" );
    }
    
  });

</script>