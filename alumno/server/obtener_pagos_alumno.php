<?php  
  //ARCHIVO VIA AJAX PARA OBTENER PAGOS DE ALUMNO
  //cobranza_alumno.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');
  //require('../../admin/twilio.php');
  
  $id_alu_ram = $_POST['id_alu_ram'];


  //generadorDescuentosRecargos($id_alu_ram, $whatsappPlantel, $smsPlantel, $emailPlantel, $client);


  $cobrosMostrar = $_POST['cobrosMostrar'];

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
  $saldoAlumno = $filaAlumno['sal_alu'];
  $id_alu = $filaAlumno['id_alu'];

  $est_dil_alu = $filaAlumno['est_dil_alu'];
  
  if ( $est_dil_alu == 'Activo' ) {

    cajaSmartServer( $id_alu_ram, $saldoAlumno, 'Dinero Digital', $nomResponsable );

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
    $saldoAlumno = $filaAlumno['sal_alu'];
    $id_alu = $filaAlumno['id_alu'];

    $est_dil_alu = $filaAlumno['est_dil_alu'];

  }


?>


<!-- MODALES ACCIONES -->

<!-- CONSULTA SALDO ALUMNO MODAL -->
<div class="modal fade" id="modalSaldoAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h6 class="modal-title w-100" id="tituloSaldoAlumno">
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body grey lighten-2" id="contenedorSaldoAlumno">
        
        

      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA SALDO ALUMNO MODAL -->


<!-- CONVENIO DE FECHAS -->
<div class="modal fade" id="modalConvenio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">

        <h4 class="modal-title w-100" id="myModalLabel">
          <span class="fa-stack fa-1x">
            <i class="far fa-calendar-check fa-stack-1x animated rotateIn delay-1s"></i>
            <i class="far fa-circle fa-stack-2x animated pulse infinite" style="color:white"></i>
          </span> 
          Convenio de Fechas
        </h4>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>

        </button>
      </div>

      <div class="modal-body">

        <div class="row">

          <div class="col-md-12 text-center">

            <h6>
              <span class="badge badge-light">
                Concepto    
              </span>
            </h6>

            <h5>
              <span class="badge badge-light" id="conceptoConvenio">    
              </span>
            </h5>
            
            <br>
            
            <h6>
              <span class="badge badge-light">
                Fechas Actuales    
              </span>
            </h6>
              
            <h5>
              <span id="inicioCobroConvenio">    
              </span> al
              
              <span id="finCobroConvenio">    
              </span>

            </h5>
              
            <br>

            <h6>
              <span class="badge badge-light">
                Vista previa
              </span>
            </h6>
              
            <h5>
              <span id="inicioConvenio">    
              </span> al
              
              <span id="finConvenio">    
              </span>

            </h5>


            <form id="formularioConvenio">
              
              <div class="row">
                
                <div class="col-md-2">
                  
                </div>

                <div class="col-md-4">
                  <div class="md-form ml-0 mr-0">
                    <input type="date" name="inicio_convenio" id="inicio_convenio" class="form-control ml-0 letraPequena font-weight-normal" required="" value="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="md-form ml-0 mr-0">
                    <input type="date" name="fin_convenio" id="fin_convenio" class="form-control ml-0 letraPequena font-weight-normal" required="" value="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>

                <div class="col-md-2">
                  
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
                <div class="col-md-2"></div>
                
                <div class="col-md-8" id="contenedor_motivo_convenio">
                  
                  

                </div>
                
                <div class="col-md-2"></div>
              </div>


              <input type="hidden" id="ini_pagConvenio" name="ini_pag">
              <input type="hidden" id="fin_pagConvenio" name="fin_pag">
              <input type="hidden" id="id_pag" name="id_pag">
              

              <div class="text-center mt-4">
                <button class="btn btn-success waves-effect waves-light" type="submit" id="btn_convenio">
                  Convenir  
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


<!-- CONDONACION DEL COBRO -->
<div class="modal fade" id="modalCondonacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">

        <h4 class="modal-title w-100" id="myModalLabel1">
          <span class="fa-stack fa-1x">
            <i class="fas fa-hand-holding-usd fa-stack-1x animated rotateIn delay-1s"></i>
            <!-- <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i> -->
            <i class="far fa-circle fa-stack-2x animated pulse infinite" style="color:white"></i>
          </span> 
          Condonación del Cobro
        </h4>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>

        </button>
      </div>

      <div class="modal-body">
      <!-- PANZA MODAL CONDONACION -->

      <div class="row">
        <div class="col-md-2">
          
        </div>
        <div class="col-md-8 text-center">
          <h5 class="mt-1 mb-2">
            Concepto <br>
            <span id="conceptoCondonacion" class="text-warning"></span>
          </h5>
          
          <p>Saldo</p>
          <h3 class="mt-1 mb-2">
            <span id="montoAdeudo"></span>
          </h3>

          <p>Saldo Final</p>

          <h3 class="mt-1 mb-2">
            <span id="diferenciaCondonacion" class="text-info"></span>
          </h3>
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
            <label  for="form34">Selecciona el tipo de condonación</label>
            <select class="selectorCondonacion md-form colorful-select dropdown-primary" id="tip1_con_pag" name="tip1_con_pag">
              <option value="Monetario" class="active selected">Monetario</option>
              <option value="Porcentual">Porcentual</option>
            </select>

            <div class="md-form" id="contenedor_tipo_condonacion">
            </div>


            <label  for="form34">Selecciona motivo de condonación</label>
            <select class="selectorCondonacion md-form colorful-select dropdown-primary" id="motivoCondonacion" name="motivoCondonacion">
              <option value="motivo1" selected>Motivo 1</option>
              <option value="motivo2">Motivo 2</option>
              <option value="otros">Otros</option>
            </select>
            <!-- FIN TIPO DE CONDONACION Y CANTIDAD -->
            

            <div class="md-form ml-0 mr-0" id="contenedorMotivoCondonacion">
              
            </div>

            <div class="text-center mt-4">
              <button class="btn btn-success waves-effect waves-light" type="submit" id="btn_condonacion">
                Condonar  
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


<!-- ABONO DEL COBRO -->
<div class="modal fade" id="modalAbono" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      
      <div class="modal-header grey darken-1 white-text text-center">

        <h4 class="modal-title w-100" id="myModalLabel1">
          <span class="fa-stack fa-1x">
            <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i>
            <!-- <i class="fas fa-dollar-sign fa-stack-1x animated rotateIn delay-1s"></i> -->
            <i class="far fa-circle fa-stack-2x animated pulse infinite" style="color:white"></i>
          </span> 
          Abonar al Cobro
        </h4>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>

        </button>
      </div>

      <div class="modal-body">
      <!-- PANZA MODAL CONDONACION -->

      <div class="row">
        <div class="col-md-2">
          
        </div>
        <div class="col-md-8 text-center">
          <h5 class="mt-1 mb-2">
            Concepto <br>
            <span id="conceptoAbono" class="text-warning"></span>
          </h5>
          
          <p>Saldo</p>
          <h3 class="mt-1 mb-2">
            <span id="montoAdeudoAbono"></span>
          </h3>

          <p>Saldo Final</p>

          <h3 class="mt-1 mb-2">
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
              <select class="selectorAbono md-form colorful-select dropdown-primary" id="tip2_abo_pag" name="tip2_abo_pag" required="">
                <option value="Efectivo" selected>Efectivo</option>
                <option value="Tarjeta">Tarjeta de Crédito o Débito</option>
                <option value="Depósito">Depósito</option>
                <option value="Dinero Digital">Dinero Digital</option>
                <option value="Otro">Otro</option>
              </select>

            </div>
            

            <div id="contenedor_seleccion_tipo_pago_abono">
              
            </div>
            <!-- Material unchecked -->
            

            <br>


            


            <!-- FIN TIPO DE PAGO Y CANTIDAD -->
            

 

            <div class="text-center mt-4">
              <button class="btn btn-success waves-effect waves-light" type="submit" id="btn_abono_pago">
                Abonar  
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



<!-- FIN MODALES -->
<!--  ROW FILTROS Y CARD -->
<div class="row animated fadeIn">
  
  <div id="contenedorSelector" >
    <!-- <select class="mdb-select md-form d-print-inline-flex"  multiple id="selectorColumnas">
      <option class="letraPequena font-weight-normal" value="" disabled selected>Ocultar/Mostrar </option>
      

      <option class="letraPequena font-weight-normal">#</option>
      <option class="letraPequena font-weight-normal">Acción</option>
      <option class="letraPequena font-weight-normal">Fecha de Alta</option>
      <option class="letraPequena font-weight-normal">Responsable del Alta</option>
      <option class="letraPequena font-weight-normal">Folio</option>
      <option class="letraPequena font-weight-normal">Concepto</option>
      <option class="letraPequena font-weight-normal">Estatus</option>
      <option class="letraPequena font-weight-normal">Fecha de Liquidación</option>

      <option class="letraPequena font-weight-normal">Saldo Original</option>
      <option class="letraPequena font-weight-normal">Saldo Pendiente</option>
      <option class="letraPequena font-weight-normal">Saldo Pagado</option>

      <option class="letraPequena font-weight-normal">Fecha de Descuento por Pronto Pago</option> 
      <option class="letraPequena font-weight-normal">Fecha de Inicio</option> 
      <option class="letraPequena font-weight-normal">Fecha de Vencimiento</option>

      <option class="letraPequena font-weight-normal">Prioridad</option>

      <option class="letraPequena font-weight-normal">Tipo de Descuento</option>
      <option class="letraPequena font-weight-normal">Descuento</option>

      <option class="letraPequena font-weight-normal">Periodicidad del Cargo</option>
      <option class="letraPequena font-weight-normal">Tipo de Cargo</option>
      <option class="letraPequena font-weight-normal">Cargo</option>

      <option class="letraPequena font-weight-normal">Condonación de Cobro</option>
      <option class="letraPequena font-weight-normal">Convenio de Fechas</option>
      <option class="letraPequena font-weight-normal">Observaciones</option>
      <option class="letraPequena font-weight-normal">WhatsApp</option>
      <option class="letraPequena font-weight-normal">SMS</option>
      <option class="letraPequena font-weight-normal">Email</option>
      <option class="letraPequena font-weight-normal">Historial</option>
      <option class="letraPequena font-weight-normal">Saldo Condonado</option>

    </select>
 -->
  </div>



  <!-- FILTROS 1 Y CARD -->
  <!-- COL -->
  <div class="col-md-6" style="height: 35vh;">
    <!-- CARD -->
    <div class="card bg-light" style="height: 32vh;">
      <div class="card-body bg-light" style="max-height: 100%;">
        
        <h4>
          <span class="badge badge-info" id="contenedor_datos_filtrados">
          
          </span>
        </h4>

        <!-- ROW -->
        <div class="row">
          <!-- ESTATUS DEL COBRO -->
          <div class="col-md-4">
            <label class="letraPequena font-weight-normal badge badge-pill badge-info">Estatus del Cobro</label>
            
            <div class="form-check">

              <input type="checkbox" class="form-check-input checador1" id="materialChecked2" value="Pagado" columna="6">
              <label class="form-check-label letraPequena font-weight-normal" for="materialChecked2">
              
                <span id="totalPagados">
                </span>
                Pagados

              </label>
            </div>
            
            <div class="form-check">
              <input type="checkbox" class="form-check-input checador1" id="materialChecked3" value="Pendiente" columna="6">
              <label class="form-check-label letraPequena font-weight-normal" for="materialChecked3">
              
                <span id="totalPendientes">
                </span>
                Pendientes

              </label>
            </div>

            <div class="form-check">
              <input type="checkbox" class="form-check-input checador1" id="materialChecked4" value="Vencido" columna="6">
              <label class="form-check-label letraPequena font-weight-normal" for="materialChecked4">

                <span id="totalVencidos">
                </span>
                Vencidos

              </label>
            </div>

          </div>
          <!-- FIN ESTATUS DEL COBRO -->

          
          <!-- PRIORIDAD -->
          <div class="col-md-2 text-center">
              <label class="letraPequena font-weight-normal badge badge-pill badge-info">Prioridad</label>
              <input type="number" min="0" max="10" class="form-control input-sm checador2" columna="14" value="0" style="background: #e0e0e0; font-size: 15px; text-align: center; width: 80px;">
              

              
              <label class="letraPequena font-weight-normal badge badge-pill badge-info" title="SMS enviados">SMS</label>
            
              <h5>
                <span class="badge badge-info" id="totalSms">
                  
                </span>
              </h5>
            
              


          </div>
          <!-- FIN PRIORIDAD -->


          <!-- ESTATUS DE CONDONACION -->
          <div class="col-md-3 text-center">
            <label class="letraPequena font-weight-normal badge badge-pill badge-info">Condonaciones</label>
            
            <h5>
              <span class="badge badge-info" id="totalCondonaciones">
                
              </span>
            </h5>


            <label class="letraPequena font-weight-normal badge badge-pill badge-info" title="WhatsApps enviados">WhatsApps</label>
            
            <h5>
              <span class="badge badge-info" id="totalWhats">
                
              </span>
            </h5>

          </div>
          <!-- FIN ESTATUS DE CONDONACION -->


          <!-- ESTATUS DE CONVENIO -->
          <div class="col-md-3 text-center">
            <label class="letraPequena font-weight-normal badge badge-pill badge-info">Convenios de fecha</label>
            
            <h5>
              <span class="badge badge-info" id="totalConvenios">
            
              </span>
            </h5>

            <label class="letraPequena font-weight-normal badge badge-pill badge-info" title="Emails enviados">Emails</label>
            
            <h5>
              <span class="badge badge-info" id="totalEmails">
                
              </span>
            </h5>
          </div>
          <!-- FIN ESTATUS DE CONVENIO -->
        </div>
        <!-- ROW -->

        <!-- ROW 2 -->
        <!-- LABEL DATOS DE DATATABLE -->
        <div class="row">

          <div class="col-md-12 text-right" >
              
          


            <div class="row">

              <div class="col-md-3">
                <label class="form-check-label letraPequena font-weight-normal" style="line-height: 100%;">
                  Saldo
                </label>
                <h5>
                  <span class="badge badge-info" id="saldoAdeudo">
                    
                  </span>
                </h5>
              </div>

              <div class="col-md-3">
                <label class="form-check-label letraPequena font-weight-normal" style="line-height: 100%;">
                  Abonado 
                </label>
                <h5>
                  <span class="badge badge-info" id="saldoPagado">
                    
                  </span>
                </h5>
              </div>


              <div class="col-md-3">
                <label class="form-check-label letraPequena font-weight-normal" style="line-height: 100%;">
                  Condonado
                </label>  
                <h5>
                  <span class="badge badge-info" id="saldoCondonado">
                    
                  </span>
                </h5>
              </div>


              <div class="col-md-3">
                <label class="form-check-label letraPequena font-weight-normal" style="line-height: 100%;">
                  Dinero Digital
                </label>
                <h5>
                  <span class="badge badge-info" id="saldoAlumno">
                    $
                    <?php
                      if ($saldoAlumno == NULL) {
                        echo "0";
                      }else{
                        echo $saldoAlumno;
                      }
                       
                    ?>
                  </span>
                </h5>
              </div>
              


              
            </div>
            
            
          </div>
        </div>
        <!-- FIN LABEL DATOS DE DATATABLE -->
        <!-- FIN ROW  2-->

        <!-- ROW 3 -->
        <div class="row" id="contenedor_botones">
          
        </div>
        <!-- FIN ROW 3 -->


      </div>
    </div>
    <!-- FIN CARD -->
    
    


  </div>
  <!-- COL -->
  <!-- FIN FILTROS Y CARD -->



  <!-- FILTROS 2 -->
  <div class="col-md-6" >
    
    <!-- CARD -->
    <div class="card bg-light" style="position: relative; top: 2vh;">
      <div class="card-body bg-light">
        
        <!-- ROW  -->
        <div class="row">
          
          <div class="col-md-6" id="col2">
            <div class="md-form mb-2">
                <input type="date" id="min-date" class="date-range-filter form-control validate letraPequena font-weight-normal" title="Inicio del Rango">
            </div>
            
            <!-- ROW DE RADIO BUTTONS -->
            <div class="row">
              <div class="col-md-6">

                <div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
                  <input type="radio" class="form-check-input columna" id="registroCobro" columna="2" name="inlineMaterialRadiosExample" checked>
                  <label class="form-check-label letraPequena font-weight-normal" for="registroCobro" style="line-height: 100%;">Fecha <br>de Alta</label>
                </div>
                
                <div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
                  <input type="radio" class="form-check-input columna" id="fechaCobro" columna="6" name="inlineMaterialRadiosExample">
                  <label class="form-check-label letraPequena font-weight-normal" for="fechaCobro" style="line-height: 100%;">Fecha <br>de Liquidación</label>
                </div>

                
              </div>


              <div class="col-md-6">
               
                <!-- Material inline 2 -->
                <div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
                  <input type="radio" class="form-check-input columna" id="inicioCobro" columna="12" name="inlineMaterialRadiosExample">
                  <label class="form-check-label letraPequena font-weight-normal" for="inicioCobro" style="line-height: 100%;">Fecha<br>de Inicio</label>
                </div>

                <div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
                  <input type="radio" class="form-check-input columna" id="finCobro" columna="13" name="inlineMaterialRadiosExample">
                  <label class="form-check-label letraPequena font-weight-normal" for="finCobro" style="line-height: 100%;">Fecha <br>de Vencimiento</label>
                </div>

             
              </div>
            </div>
            <!-- FIN DE RADIO BUTTONS -->
          
          </div>

          <div class="col-md-6" id="col3">
            <div class="md-form mb-2">
              <input type="date" id="max-date" class="date-range-filter form-control validate letraPequena font-weight-normal" title="Fin del Rango">
            </div>
              
          </div>
        </div>
        <!-- FIN ROW -->


      </div>
    </div>
    <!-- FIN CARD -->

    
  </div>
  <!-- FIN FILTROS 2 -->

</div>

<div class="row animated fadeIn">
  <div class="col-md-12">

  <?php
    $fechaHoy = date('Y-m-d');
    
    // VALIDACION DE ACUERDO A LOS REGISTROS A MOSTRAR
    if ( $cobrosMostrar == 'hoy' ) {
      
      $sqlPago = "
        SELECT * 
        FROM pago 
        WHERE id_alu_ram10 = '$id_alu_ram' AND (ini_pag<='$fechaHoy')
        ORDER BY est_pag DESC, ini_pag ASC, pri_pag ASC, id_pag ASC
      ";


    }else if( $cobrosMostrar == 'todos' ) {
      $sqlPago = "
        SELECT * 
        FROM pago 
        WHERE id_alu_ram10 = '$id_alu_ram'
        ORDER BY est_pag DESC, ini_pag ASC, pri_pag ASC, id_pag ASC
      ";

    }


    // echo $sqlPago;

    $resultadoPago = mysqli_query($db, $sqlPago);

    //METER VALIDACION
  ?>
  

    <table id="tablaCobros" class="table table-hover table-bordered table-sm table-striped" cellspacing="0" width="100%">
      <thead class="white-text grey">
        <tr>
          

          <th class="letraPequena font-weight-normal">#</th>
          <th class="letraPequena font-weight-normal">Acción</th>
          <th class="letraPequena font-weight-normal">Fecha de Alta</th>
          <th class="letraPequena font-weight-normal">Responsable del Alta</th>
          <th class="letraPequena font-weight-normal">Folio</th>
          <th class="letraPequena font-weight-normal">Concepto</th>
          <th class="letraPequena font-weight-normal">Estatus</th>
          <th class="letraPequena font-weight-normal">Fecha de Liquidación</th>
          <th class="letraPequena font-weight-normal">Saldo Original</th>
          <th class="letraPequena font-weight-normal">Saldo</th>
          <th class="letraPequena font-weight-normal">Abonado</th>
          <th class="letraPequena font-weight-normal">Fecha de Descuento por Pronto Pago</th> 
          <th class="letraPequena font-weight-normal">Fecha de Inicio</th> 
          <th class="letraPequena font-weight-normal">Fecha de Vencimiento</th>
          <th class="letraPequena font-weight-normal">Prioridad</th>
          <th class="letraPequena font-weight-normal">Tipo de Descuento</th>
          <th class="letraPequena font-weight-normal">Descuento</th>
          <th class="letraPequena font-weight-normal">Periodicidad del Cargo</th>
          <th class="letraPequena font-weight-normal">Tipo de Cargo</th>
          <th class="letraPequena font-weight-normal">Cargo</th>
          <th class="letraPequena font-weight-normal">Condonación de Cobro</th>
          <th class="letraPequena font-weight-normal">Convenio de Fechas</th>
          <th class="letraPequena font-weight-normal">Observaciones</th>
          <th class="letraPequena font-weight-normal">WhatsApp</th>
          <th class="letraPequena font-weight-normal">SMS</th>
          <th class="letraPequena font-weight-normal">Email</th>
          <th class="letraPequena font-weight-normal">Recargo</th>
          <th class="letraPequena font-weight-normal">Condonado</th>


          


            
        </tr>
      </thead>


      <?php  
        $i = 1;
        while ($filaPago = mysqli_fetch_assoc($resultadoPago)) {
          // VARIABLES RELEVANTES
          $id_pag = $filaPago['id_pag'];
      ?>
        
        <?php  
          $estatusPago = obtenerEstatusPagoSimpleServer( $id_pag );
          if ( $estatusPago == 'Vencido' ) {
        ?>
            <tr class="text-danger pagoVencido">

        <?php
          }else if ( $estatusPago == 'Pendiente' ) {
        ?>
            <tr class="pagoPendiente">

        <?php
          }else if ( $estatusPago == 'Pagado' ) {
        ?>
            <tr class="pagoPagado">

        <?php
          }
        ?>
          

          <td class="letraPequena font-weight-normal"><?php echo $i; $i++;?></td>

          <td class="text-center">

            <!-- VALIDACION DE ACCIONES, FUNCIONAN SI EL COBRO SIGUE CON ESTATUS DE PENDIENTE -->

            <!--Dropdown primary-->
              <div class="dropdown clasePadreClaseMateria">


              <a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
                <i class="fas fa-ellipsis-v grey-text"></i>
                </a>


              <!--Menu-->
              <div class="dropdown-menu dropdown-info">
                
                <a class="dropdown-item consultaPago" id_pag="<?php echo $id_pag; ?>" title="Ticket del cobro">
                  Ver datos del cobro
                </a>



                <a class="dropdown-item historialPago" id_pag="<?php echo $id_pag; ?>" title="Haz clic para ver el historial de movimientos de <?php echo $filaPago['con_pag']; ?>">
                  Historial
                </a>
                
                

              </div>

            </div>
           

            <!-- FIN VALIDACION DE ACCIONES, FUNCIONAN SI EL COBRO SIGUE CON ESTATUS DE PENDIENTE -->
            
            

          </td>
          
          <td class="letraPequena font-weight-normal"><?php echo fechaFormateadaCompacta($filaPago['fec_pag']);?></td>
          
          <td class="letraPequena font-weight-normal"><?php echo $filaPago['res_pag'];?></td>

          <td class="letraPequena font-weight-normal"><?php echo $filaPago['fol_pag'];?></td>
          
          <td class="letraPequena font-weight-normal"><?php echo $filaPago['con_pag'];?></td>

          <td class="text-center">
            <?php echo obtenerEstatusPago( $id_pag ); ?>
          </td>
          
          <?php  
            if ($filaPago['pag_pag'] == NULL) {
          ?>
            <td class="letraPequena font-weight-normal"></td>
          <?php
            }else{
          ?>
            <td class="letraPequena font-weight-normal"><?php echo fechaFormateadaCompacta($filaPago['pag_pag']);?></td>
          <?php
            }
          ?>

          <td class="letraPequena font-weight-normal"><?php echo "$ ".round($filaPago['mon_ori_pag'], 2);?></td>
          
          <td class="letraPequena font-weight-normal"><?php echo "$ ".round($filaPago['mon_pag'], 2);?></td>
          
          <?php

            $id_pag = $filaPago['id_pag'];

            $sqlValidacionPagado = "
              SELECT *
              FROM abono_pago
              WHERE id_pag1 = '$id_pag'
            ";

            $resultadoValidacionPagado = mysqli_query( $db, $sqlValidacionPagado );

            if ( $resultadoValidacionPagado ) {
              
              $validacionPagado = mysqli_num_rows( $resultadoValidacionPagado );

              if ( $validacionPagado > 0 ) {
                
                $sqlTotalPagado = "
                  SELECT SUM(mon_abo_pag) AS totalPagado
                  FROM abono_pago
                  WHERE id_pag1 = '$id_pag'
                ";

                //echo $sqlTotalPagado;

                $resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );

                if ( $resultadoTotalPagado ) {
                  $filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );

                  $totalPagado = $filaTotalPagado['totalPagado'];
                
                ?>
                <!-- MONTO PAGADO -->
                <td class="waves-effect abonos" id_pag="<?php echo $filaPago['id_pag']; ?>">
                  <a class="badge badge-success">
                    $ <?php echo round($totalPagado, 2); ?>
                  </a>
                </td>
                <!-- FIN MONTO PAGADO -->
                <?php
                  
                }else {
                  echo $sqlTotalPagado;
                }

              }else{
            ?>
                <!-- MONTO PAGADO -->
                <td>
                  <a class="badge badge-warning">
                    $ 0
                  </a>
                </td>
                <!-- FIN MONTO PAGADO -->
            <?php
              }
            }

          ?>


          
  
          <!-- PRONTO PAGO -->
          <?php  
            if ( $filaPago['pro_pag'] == NULL ) {
          ?>
            <td class="letraPequena font-weight-normal"></td>
          <?php  
            }else{
          ?>  
            <td class="letraPequena font-weight-normal"><?php echo fechaFormateadaCompacta($filaPago['pro_pag']); ?></td>

          <?php
            }
          ?>
          <!-- FIN PRONTO PAGO -->

          <?php  
            if ( $filaPago['ini_pag'] == NULL ) {
          ?>
            <td class="letraPequena font-weight-normal"></td>
          <?php  
            }else{
          ?>  
            <td class="letraPequena font-weight-normal"><?php echo fechaFormateadaCompacta($filaPago['ini_pag']);?></td>

          <?php
            }
          ?>

          <?php  
            if ( $filaPago['fin_pag'] == NULL ) {
          ?>
            <td class="letraPequena font-weight-normal"></td>
          <?php  
            }else{
          ?>  
            <td class="letraPequena font-weight-normal"><?php echo fechaFormateadaCompacta($filaPago['fin_pag']);?></td>

          <?php
            }
          ?>

          <td class="letraPequena font-weight-normal"><?php echo $filaPago['pri_pag'];?></td>
          
          <td class="letraPequena font-weight-normal"><?php echo $filaPago['tip1_pag'];?></td>
          <?php 
            if( $filaPago['tip1_pag'] == NULL ){
          ?>
              <td class="letraPequena font-weight-normal"></td>
          <?php  
            }else if ($filaPago['tip1_pag'] == 'Porcentual') {
          ?>
            <td class="letraPequena font-weight-normal">
              <?php echo $filaPago['des_pag']; ?> %
            </td>
          <?php
            }else if($filaPago['tip1_pag'] == 'Monetario'){
          ?>
            <td class="letraPequena font-weight-normal">
              $ <?php echo $filaPago['des_pag']; ?>
            </td>
          <?php
            }
          ?>

          <td class="letraPequena font-weight-normal">
            <?php echo $filaPago['int_pag']; ?>
          </td>
          
          <td class="letraPequena font-weight-normal"><?php echo $filaPago['tip2_pag'];?></td>

          <?php 
            if( $filaPago['tip2_pag'] == NULL ){
          ?>
              <td class="letraPequena font-weight-normal"></td>
          <?php  
            }else if ($filaPago['tip2_pag'] == 'Porcentual') {
          ?>
            <td class="letraPequena font-weight-normal">
              <?php echo $filaPago['car_pag']; ?> %
            </td>
          <?php
            }else if($filaPago['tip2_pag'] == 'Monetario'){
          ?>
            <td class="letraPequena font-weight-normal">
              $ <?php echo $filaPago['car_pag']; ?>
            </td>
          <?php
            }
          ?>

          <!-- HISTORIAL CONDONACION -->
          
          
            <?php

              $sqlValidacionCondonacionPago = "
                SELECT *
                FROM condonacion_pago
                WHERE id_pag2 = '$id_pag'
              ";

              $resultadoValidacionCondonacionPago = mysqli_query( $db, $sqlValidacionCondonacionPago );

              if ( $resultadoValidacionCondonacionPago ) {
                $totalValidacionCondonacionPago = mysqli_num_rows( $resultadoValidacionCondonacionPago );

                if ( $totalValidacionCondonacionPago > 0 ) {
            ?>
                <td class="text-white waves-effect obtenerAccionPago font-weight-bold" id_pag="<?php echo $id_pag; ?>" title="Haz clic para ver el historial condonaciones de <?php echo $filaPago['con_pag']; ?>"  tipo="Condonación">
                  <a class="chip info-color letraPequena">
                  
                      <?php echo $totalValidacionCondonacionPago; ?>
                  </a>
                </td>
            <?php
                }else {
            ?>
                <td class="font-weight-bold" title="No hay registros de historial para <?php echo $filaPago['con_pag']; ?>">
                  <a class="chip light-color letraPequena" >
                    0
                  </a>
                </td>
            <?php
                }
              }else {
                echo $sqlValidacionCondonacionPago;
              }
            ?>
          </td>
          <!-- FIN HISTORIAL CONDONACION -->

          <!-- HISTORIAL CONVENIO -->
          
          
            <?php

              $sqlValidacionConvenioPago = "
                SELECT *
                FROM convenio_pago
                WHERE id_pag3 = '$id_pag'
              ";

              $resultadoValidacionConvenioPago = mysqli_query( $db, $sqlValidacionConvenioPago );

              if ( $resultadoValidacionConvenioPago ) {
                $totalValidacionConvenioPago = mysqli_num_rows( $resultadoValidacionConvenioPago );

                if ( $totalValidacionConvenioPago > 0 ) {
            ?>
                <td class="waves-effect obtenerAccionPago text-white font-weight-bold" id_pag="<?php echo $id_pag; ?>" title="Haz clic para ver el historial convenios de <?php echo $filaPago['con_pag']; ?>" tipo="Convenio">

                  <a class="chip info-color letraPequena">
                      <?php echo $totalValidacionConvenioPago; ?>
                  </a>

                </td>
            <?php
                }else {
            ?>
                <td  class=" font-weight-bold" title="No hay registros de historial para <?php echo $filaPago['con_pag']; ?>">
                  <a class="chip light-color letraPequena">
                    0
                  </a>

                </td>
                  
                  
            <?php
                }
              }else {
                echo $sqlValidacionConvenioPago;
              }
            ?>
          
          <!-- FIN HISTORIAL CONVENIO -->



          <td class="letraPequena font-weight-normal"><?php echo $filaPago['obs_pag'];?></td>
          
          <!-- HISTORIAL WHATSAPP -->
          
          
            <?php

              $sqlValidacionHistorialWhatsapp = "
                SELECT *
                FROM historial_pago
                WHERE id_pag4 = '$id_pag' AND med_his_pag = 'Whatsapp'
              ";

              $resultadoValidacionHistorialWhatsapp = mysqli_query( $db, $sqlValidacionHistorialWhatsapp );

              if ( $resultadoValidacionHistorialWhatsapp ) {
                $totalValidacionHistorialWhatsapp = mysqli_num_rows( $resultadoValidacionHistorialWhatsapp );

                if ( $totalValidacionHistorialWhatsapp > 0 ) {
            ?>
                  <td class="waves-effect historialPagoWhatsapp  text-white  font-weight-bold" id_pag="<?php echo $id_pag; ?>" title="Haz clic para ver el historial de SMS de <?php echo $filaPago['con_pag']; ?>" medio="Whatsapp">
                    <a class="chip info-color letraPequena">
                    
                        <?php echo $totalValidacionHistorialWhatsapp; ?>
                    </a>
                  </td>
            <?php
                }else {
            ?>  
                <td class=" font-weight-bold" title="No hay registros de historial para <?php echo $filaPago['con_pag']; ?>">
                  <a class="chip light-color letraPequena">
                    0
                  </a>
                </td>
                  
                  
            <?php
                }
              }else {
                echo $sqlValidacionHistorialWhatsapp;
              }
            ?>
          <!-- FIN HISTORIAL WHATSAPP -->
          

          <!-- HISTORIAL SMS -->
          
            <?php

              $sqlValidacionHistorialSms = "
                SELECT *
                FROM historial_pago
                WHERE id_pag4 = '$id_pag' AND med_his_pag = 'SMS'
              ";

              $resultadoValidacionHistorialSms = mysqli_query( $db, $sqlValidacionHistorialSms );

              if ( $resultadoValidacionHistorialSms ) {
                $totalValidacionHistorialSms = mysqli_num_rows( $resultadoValidacionHistorialSms );

                if ( $totalValidacionHistorialSms > 0 ) {
            ?>
                <td class=" waves-effect historialPagoSms  text-white font-weight-bold" id_pag="<?php echo $id_pag; ?>" title="Haz clic para ver el historial de SMS de <?php echo $filaPago['con_pag']; ?>" medio="SMS">
                  <a class="chip info-color letraPequena">
                  
                      <?php echo $totalValidacionHistorialSms; ?>
                  </a>
                </td>
            <?php
                }else {
            ?>
                <td class=" font-weight-bold"  title="No hay registros de historial para <?php echo $filaPago['con_pag']; ?>">
                  <a class="chip light-color letraPequena">
                    0
                  </a>
                </td> 
            <?php
                }
              }else {
                echo $sqlValidacionHistorialSms;
              }
            ?>
          <!-- FIN HISTORIAL SMS -->
          

          <!-- HISTORIAL CORREO -->

            <?php

              $sqlValidacionHistorialCorreo = "
                SELECT *
                FROM historial_pago
                WHERE id_pag4 = '$id_pag' AND med_his_pag = 'Correo'
              ";

              $resultadoValidacionHistorialCorreo = mysqli_query( $db, $sqlValidacionHistorialCorreo );

              if ( $resultadoValidacionHistorialCorreo ) {
                $totalValidacionHistorialCorreo = mysqli_num_rows( $resultadoValidacionHistorialCorreo );

                if ( $totalValidacionHistorialCorreo > 0 ) {
            ?>

                <td class="font-weight-bold text-white waves-effect historialPagoCorreo" id_pag="<?php echo $id_pag; ?>" title="Haz clic para ver el historial de correos de <?php echo $filaPago['con_pag']; ?>" medio="Correo">

                  <a class="chip info-color letraPequena">
                  
                      <?php echo $totalValidacionHistorialCorreo; ?>
                  </a>

                </td>
            <?php
                }else {
            ?>
                <td class=" font-weight-bold"  title="No hay registros de historial para <?php echo $filaPago['con_pag']; ?>">
                  <a class="chip light-color letraPequena">
                    0
                  </a>
                </td>  
            <?php
                }
              }else {
                echo $sqlValidacionHistorialCorreo;
              }
            ?>
          <!-- FIN HISTORIAL CORREO -->
          
          <!-- RECARGO -->
          <td class="letraPequena font-weight-normal">
            $
            <?php
              if ( obtenerTotalRecargoPagoServer( $id_pag ) > 0  ) {
                echo obtenerTotalRecargoPagoServer( $id_pag );
              } else {
                echo "0";
              }

            ?>
            
            
          </td>
          <!-- FIN RECARGO -->
          
          <!-- MONTO CONDONADO -->
          <td class="letraPequena font-weight-normal">
            $<?php  
              if ( obtenerMontoCondonadoPagoServer( $id_pag ) > 0 ) {

                echo obtenerMontoCondonadoPagoServer( $id_pag );
              
              } else {
              
                echo "0";
              
              }
              

            ?>
          </td>
          <!-- FIN MONTO CONDONADO -->
            
            
            
        </tr>

      <?php
        }
      ?>
    </table>

  </div>
  <!-- FIN SEGUNDA COL 12 DATATABLE -->

</div>
<!--  FIN ROW TABLA-->


<!-- CONSULTA ALUMNO MODAL -->
<div class="modal fade" id="modalConsultaAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


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



<!-- CONSULTA PAGO MODAL -->
<div class="modal fade" id="modalConsultaPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h6 class="modal-title w-100" id="tituloConsultaPago">
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body bg-light" id="contenedorConsultaPago">
        
        

      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA PAGO MODAL -->



<script>
  $(document).ready(function () {
    $('.removerSombra').removeClass("grey darken-1");



    //$.fn.dataTable.ext.search.pop();

    $('#tablaCobros').DataTable({
      
      "retrieve": true,
      dom: 'Bfritlp',
      colReorder: true,
      "pageLength": '50',
      "columnDefs": [
              {
                  "targets": [ 3,11,12,14,15,16,17,18,19,20,21,22,23,24,25 ],
                  "visible": false
              }],

            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },


            ],


      "language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Registros encontrados: _TOTAL_",
                            "sInfoEmpty":      "No se encontraron registros",
                            "sInfoFiltered":   "",
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
    $('#tablaCobros_wrapper').find('label').each(function () {
      $(this).parent().append($(this).children());
    });
    $('#tablaCobros_wrapper .dataTables_filter').find('input').each(function () {
      $('#tablaCobros_wrapper input').attr("placeholder", "Buscar...").addClass('letraPequena font-weight-normal');
      $('#tablaCobros_wrapper input').removeClass('form-control-sm');
    });
    $('#tablaCobros_wrapper .dataTables_length').addClass('d-flex flex-row');
    $('#tablaCobros_wrapper .dataTables_filter').addClass('md-form');
    $('#tablaCobros_wrapper select').removeClass(
    'custom-select custom-select-sm form-control form-control-sm');
    $('#tablaCobros_wrapper select').addClass('mdb-select');
    $('#tablaCobros_wrapper .mdb-select').materialSelect();
    $('#tablaCobros_wrapper .dataTables_filter').find('label').remove();
    var botones = $('#tablaCobros_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
    //console.log(botones);


    var entrada = $('#tablaCobros_filter');
    $('#selectorColumnas').materialSelect();

    var tablaCobros = $('#tablaCobros').DataTable();

    // CONTENEDOR DATOS ENCONTRADOS
    $("#contenedor_datos_filtrados").html($("#tablaCobros_info"));

    // SELECT DE COLUMNAS PARA VISUALIZAR
    // var j = 0;
    // for(var i = 0; i < $("#contenedorSelector li").length; i++){
    //   //console.log(i);
    //   if ($("#contenedorSelector li").eq(i).hasClass("")) {
    //     $("#contenedorSelector li").eq(i).attr("class", "toggle-vis").attr("data-column", j);
    //     j++;
    //   }else{
    //     $("#contenedorSelector li").eq(i).remove();
    //   }
    // }


    $(".select-dropdown").on('click', function(event) {
      event.preventDefault();
      //Act on the event 
      
      setInterval(function(){
        $('.multiple-select-dropdown').css({
          height: '120px'
        });
      }, 100);
      //alert('hi');
       
      
    });

    $(".select-toggle-all").remove();


    $('.toggle-vis').on( 'click', function (e) {
      e.preventDefault();

      console.log("click");

      // Get the column API object
      var column = tablaCobros.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
    });

    // DATOS DE DATATABLE LAYOUT
    $("#col3").append(entrada).append($("#contenedorSelector"));
    //$("#con").append(botones);
    $("#contenedor_datos_filtrados").html($("#tablaCobros_info"));




    // FUNCION ADICIONAL DE FILTROS POR FECHA
    $.fn.dataTable.ext.search.push(

      
        function fechas( settings, data, dataIndex ) {
          var min  = $('#min-date').val();
          var max  = $('#max-date').val();
          
          for(var i = 0; i < $(".columna").length; i++){
            if ($(".columna")[i].checked == true) {
              var columna = $(".columna").eq(i).attr("columna");
            }
          }
          
          //console.log(columna);

          var arregloFechas = moment(data[columna] || 0,"DD/MM/YYYY").format("YYYY-MM-DD"); 
            // Our date column in the tablaCobros
            //console.log(moment(arregloFechas).isValid());

          if  ( 
                  ( min == "" || max == "" )
                  || 
                  ( moment(arregloFechas).isSameOrAfter(min) && 
                    moment(arregloFechas).isSameOrBefore(max))
              )
          {
              return true;
          }
          return false;
        }
    );

    // Re-draw the tablaCobros when the a date range filter changes
    $('.date-range-filter').change( function() {
        tablaCobros.draw();

    });

    // Re-draw the tablaCobros when the radio buttons change
    $('.columna').change( function() {
        tablaCobros.draw();

    });




    //INDICADORES
    $("#saldoAdeudo").text('$'+tablaCobros.column( 9, {filter: 'applied'}).data().sum().toFixed(2));
    $("#saldoPagado").text('$'+(-1)*(tablaCobros.column( 10, {filter: 'applied'} ).data().sum().toFixed(2)) );
    $("#saldoCondonado").text('$'+tablaCobros.column( 27, {filter: 'applied'}).data().sum().toFixed(2));




    tablaCobros.on('draw', function(){
      $("#saldoAdeudo").text('$'+tablaCobros.column( 9, {filter: 'applied'}).data().sum().toFixed(2));
      $("#saldoPagado").text('$'+(-1)*(tablaCobros.column( 10, {filter: 'applied'} ).data().sum().toFixed(2)) );
      $("#saldoCondonado").text('$'+tablaCobros.column( 27, {filter: 'applied'} ).data().sum().toFixed(2));
          
      
    });


    $('.checador1').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador1').length; i++){
        if($('.checador1').eq(i).prop("checked") == true){
          //console.log($('.checador1').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador1').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador1').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          tablaCobros
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
          tablaCobros
          .columns( columna )
          .search('')
          .draw();
      }
  
    });


    $('.checador2').on( 'keyup change', function () {
      //console.log($(this));
      var busqueda = [];
      
      busqueda = $('.checador2').val();
      
      var columna = $(this).attr("columna");

      // SI ES 0 APARECE TODO, O SEGUN LA PRIORIDAD ELEGIDA
      //console.log(busqueda);
      if (busqueda != 0) {
          tablaCobros
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
        tablaCobros
          .columns( columna )
          .search('')
          .draw();
      }
      
    });

    $('.checador3').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador3').length; i++){
        if($('.checador3').eq(i).prop("checked") == true){
          //console.log($('.checador3').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador3').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador3').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          tablaCobros
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
          tablaCobros
          .columns( columna )
          .search('')
          .draw();
      }
  
    });


    $('.checador4').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador4').length; i++){
        if($('.checador4').eq(i).prop("checked") == true){
          //console.log($('.checador4').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador4').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador4').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          tablaCobros
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
          tablaCobros
          .columns( columna )
          .search('')
          .draw();
      }
  
    });


    // CONTADOR DE CONVENIOS Y CONDONACIONES

    $("#totalCondonaciones").text( (-1)*( tablaCobros.column( 20, {filter: 'applied'}).data().sum() ) );
    
    $("#totalConvenios").text( (-1)*( tablaCobros.column( 21, {filter: 'applied'}).data().sum() ) );


    tablaCobros.on('draw', function(){
      $("#totalCondonaciones").text( (-1)*( tablaCobros.column( 20, {filter: 'applied'}).data().sum() ) );
      $("#totalConvenios").text( (-1)*( tablaCobros.column( 21, {filter: 'applied'}).data().sum() ) );
      
    });

    // CONTADOR DE NOTIFICACIONES
    $("#totalWhats").text( (-1)*( tablaCobros.column( 23, {filter: 'applied'}).data().sum() ) );
    $("#totalSms").text( (-1)*( tablaCobros.column( 24, {filter: 'applied'}).data().sum() ) );
    $("#totalEmails").text( (-1)*( tablaCobros.column( 25, {filter: 'applied'}).data().sum() ) );

    tablaCobros.on('draw', function(){
      $("#totalWhats").text( (-1)*( tablaCobros.column( 23, {filter: 'applied'}).data().sum() ) );
      $("#totalSms").text( (-1)*( tablaCobros.column( 24, {filter: 'applied'}).data().sum() ) );
      $("#totalEmails").text( (-1)*( tablaCobros.column( 25, {filter: 'applied'}).data().sum() ) );
      
    });



    // CONTADOR DE ESTATUS DE PAGO
    $("#totalPagados").text(tablaCobros.rows( '.pagoPagado' ).count());
    $("#totalPendientes").text(tablaCobros.rows( '.pagoPendiente' ).count());
    $("#totalVencidos").text(tablaCobros.rows( '.pagoVencido' ).count());

    //INDICADORES DINAMICOS
    tablaCobros.on('draw', function(){
      $("#totalPagados").text(tablaCobros.rows( ['.pagoPagado'], { filter: 'applied' }).count());
      $("#totalPendientes").text(tablaCobros.rows( ['.pagoPendiente'], { filter: 'applied' }).count());
      $("#totalVencidos").text(tablaCobros.rows( ['.pagoVencido'], { filter: 'applied' }).count()); 

    });

  });
</script>



<script>
  // CONSULTA A HISTORIAL DE PAGO
  $(".historialPago").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_pag = $(this).attr("id_pag");

    $.ajax({
      url: 'server/obtener_historial_pago.php',
      type: 'POST',
      data: {id_pag},
      success: function(respuesta){

        $("#modalHistorialPago").modal('show');
        $("#panzaModalHistorialPago").html(respuesta);
      }
    });
    

  });


  // CONSULTA DE ABONOS

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
        $("#titulo_medio_historial").html('');
        $("#panzaModalAbonos").html(respuesta);
      }
    });

  });




  // // CONSULTA DE RECARGOS

  // $(".recargos").on('click', function(event) {
  //   event.preventDefault();
  //   /* Act on the event */

  //   var id_pag = $(this).attr("id_pag");

  //   $.ajax({
  //     url: 'server/obtener_recargos_pago.php',
  //     type: 'POST',
  //     data: {id_pag},
  //     success: function(respuesta){

  //       $("#modalAbonos").modal('show');
  //       $("#titulo_medio_historial").html('');
  //       $("#panzaModalAbonos").html(respuesta);
  //     }
  //   });

  // });



  // // CONSULTA DE CONDONADOS

  // $(".condonaciones").on('click', function(event) {
  //   event.preventDefault();
  //   /* Act on the event */

  //   var id_pag = $(this).attr("id_pag");

  //   $.ajax({
  //     url: 'server/obtener_condonaciones_pago.php',
  //     type: 'POST',
  //     data: {id_pag},
  //     success: function(respuesta){

  //       $("#modalCondonaciones").modal('show');
  //       $("#titulo_medio_historial").html('');
  //       $("#panzaModalAbonos").html(respuesta);
  //     }
  //   });

  // });


  // CONSULTA DE CORREOS

  // CONSULTA A HISTORIAL DE PAGO
  $(".historialPagoCorreo").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_pag = $(this).attr("id_pag");
    var medio = $(this).attr("medio");

    $.ajax({
      url: 'server/obtener_historial_pago.php',
      type: 'POST',
      data: {id_pag, medio},
      success: function(respuesta){

        $("#modalHistorialPago").modal('show');
        $("#titulo_medio_historial").html(' de Correo');
        $("#panzaModalHistorialPago").html(respuesta);
      }
    });
  });


  // CONSULTA A HISTORIAL DE PAGO
  $(".historialPagoSms").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_pag = $(this).attr("id_pag");
    var medio = $(this).attr("medio");

    $.ajax({
      url: 'server/obtener_historial_pago.php',
      type: 'POST',
      data: {id_pag, medio},
      success: function(respuesta){

        $("#modalHistorialPago").modal('show');
        $("#titulo_medio_historial").html(' de SMS');
        $("#panzaModalHistorialPago").html(respuesta);
      }
    });
  });

  // CONSULTA A HISTORIAL DE PAGO
  $(".historialPagoWhatsapp").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_pag = $(this).attr("id_pag");
    var medio = $(this).attr("medio");

    $.ajax({
      url: 'server/obtener_historial_pago.php',
      type: 'POST',
      data: {id_pag, medio},
      success: function(respuesta){

        $("#modalHistorialPago").modal('show');
        $("#titulo_medio_historial").html(' de Whatsapp');
        $("#panzaModalHistorialPago").html(respuesta);
      }
    });
  });



  // CONSULTA A CONDONACION O CONVENIO
  $(".obtenerAccionPago").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */


    var id_pag = $(this).attr("id_pag");
    var tipo = $(this).attr("tipo");

    $.ajax({
      url: 'server/obtener_accion_pago.php',
      type: 'POST',
      data: {id_pag, tipo},
      success: function(respuesta){

        $("#modalAccionPago").modal('show');
        
        if ( tipo == 'Convenio' ) {
          $("#titulo_tipo_accion").html(' de Convenios');
        }else if( tipo == 'Condonación' ){
          $("#titulo_tipo_accion").html(' de Condonaciones');
        }

        $("#panzaModalAccionPago").html(respuesta);
      }
    });

  });


</script>




<script>

  //ACCIONES 

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
      data: {id_pag},
      success: function(datos){
        console.log(datos);

        if (datos.est_pag == 'Pendiente') {
          // IF QUE VALIDA EL PAGO PENDIENTE
          $('#modalConvenio').draggable();

          $('#modalConvenio').modal('show');

          $('#id_pag').attr({value: datos.id_pag});
          $('#ini_pagConvenio').attr({value: datos.ini_pag});
          $('#fin_pagConvenio').attr({value: datos.fin_pag});

          $("#inicioCobroConvenio").text( moment(datos.ini_pag).format("DD/MM/YYYY") );
          $("#finCobroConvenio").text( moment(datos.fin_pag).format("DD/MM/YYYY") );

          $("#conceptoConvenio").text(datos.con_pag);

          $("#inicioConvenio").text( moment('<?php echo date('Y-m-d'); ?>').format("DD/MM/YYYY") );
          $("#finConvenio").text( moment('<?php echo date('Y-m-d'); ?>').format("DD/MM/YYYY") );

          $("#inicio_convenio").on('change keyup', function(event) {
            event.preventDefault();
            /* Act on the event */
            var hoy =  moment( new Date() ).format("YYYY-MM-DD");

            var inicioConvenio = $(this).val();

            var finConvenio = $('#fin_convenio').val();

            console.log(finConvenio);

            // console.log("hoy :"+hoy);
            // console.log("inicioConvenio :"+inicioConvenio);

            if ( moment(inicioConvenio).isSameOrAfter(hoy) ) {
              $("#inicioConvenio").text( moment($("#inicio_convenio").val()).format("DD/MM/YYYY") );
            }else{

              $("#inicioConvenio").text("Error").addClass('text-danger');
              
              swal("Error en las fechas del convenio", "¡La fecha de inicio debe ser mayor o igual a la fecha de hoy!", "error", {button: "Aceptar",}).then((value) => {
                  if (value) {

                    $("#inicioConvenio").text( moment(hoy).format("DD/MM/YYYY") ).removeClass('text-danger');
                    $("#inicio_convenio").val(hoy);
                  }
              });
            }

            if ( moment(inicioConvenio).isSameOrBefore(finConvenio) ) {
              $("#inicioConvenio").text( moment($("#inicio_convenio").val()).format("DD/MM/YYYY") );
            }else{

              $("#inicioConvenio").text("Error").addClass('text-danger');
              
              swal("Error en las fechas del convenio", "¡La fecha de inicio debe ser menor o igual a la fecha de fin!", "error", {button: "Aceptar",}).then((value) => {
                  if (value) {

                    $("#inicioConvenio").text( moment(hoy).format("DD/MM/YYYY") ).removeClass('text-danger');
                    $("#inicio_convenio").val( hoy );
                  }
              });
            }
            
          });



          $("#fin_convenio").on('change keyup', function(event) {
            event.preventDefault();
            /* Act on the event */
            //var hoy =  moment( new Date() ).format("YYYY-MM-DD");

            var inicioConvenio = $('#inicio_convenio').val();

            var finConvenio = $(this).val();

            // console.log("finConvenio: "+finConvenio);

            // // console.log("hoy :"+hoy);
            // console.log("inicioConvenio :"+inicioConvenio);

            if ( moment(finConvenio).isSameOrAfter(inicioConvenio) ) {
              $("#finConvenio").text( moment($("#fin_convenio").val()).format("DD/MM/YYYY") );
            }else{

              $("#finConvenio").text("Error").removeClass('text-info').addClass('text-danger');
              
              swal("Error en las fechas del convenio", "¡La fecha de fin debe ser mayor o igual a la fecha de inicio!", "error", {button: "Aceptar",}).then((value) => {
                  if (value) {

                    $("#finConvenio").text( moment( inicioConvenio ).format("DD/MM/YYYY") ).removeClass('text-danger');
                    $("#fin_convenio").val(inicioConvenio);
                  }
              });
            }
   
            
          });

          // MOTIVO CONVENIO
          $("#motivoConvenio").on('change', function(event) {
            event.preventDefault();
            /* Act on the event */
            if ($("#motivoConvenio")[0].checked == true) {
              $("#contenedor_motivo_convenio").html('<div class="md-form"><textarea id="textarea-char-counter" name="mot_acu_pag" class="form-control md-textarea" length="1000" rows="3"></textarea><label for="textarea-char-counter" class="active">Agrega un motivo (Opcional)</label></div>');

              $("#textarea-char-counter").focus();

            }else{
              
              $("#contenedor_motivo_convenio").html('');
            }
          });
          

          
          // FIN IF QUE VALIDA PAGO PENDIENTE
        }else{

          swal("Error en las fechas del convenio", "¡No se puede convenir debido a que ya fue pagado!", "error", {button: "Aceptar",});
        }
        


      }

    });



  });
  // FIN CONVENIOS DE FECHA


  //FORMULARIO 
  $('#formularioConvenio').on('submit', function(event) {
    
    // CODIGO
    event.preventDefault();

    var formularioConvenio = new FormData($('#formularioConvenio')[0]);

    $.ajax({
    
      url: 'server/agregar_convenio_pago.php',
      type: 'POST',
      data: formularioConvenio,
      processData: false,
      contentType: false,
      cache: false,
      success: function(respuesta){
        console.log(respuesta);

        if (respuesta == 'Exito') {
          swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
          then((value) => {

            obtener_notificaciones_cobros_header();

            for(var i = 0; i < $('.cobrosMostrar').length; i++){
              if($('.cobrosMostrar').eq(i).prop("checked") == true){
                //console.log($('.checador1').eq(i).val());
                
                var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
              }
            }

            obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
            $("#modalConvenio").modal('hide');


          });
          
        }

      }
    });

    // FIN CODIGO
  });     
  // FIN FORMULARIO 


  // CONVENIO NO DISPONIBLE
  $(".convenirCobroInvalido").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    swal("¡Ya existe una solicitud de convenio de fechas pendiente!", "Solicita al Administrador que atienda las peticiones de convenios de fechas", "info", {button: "Aceptar",});

  });


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
      data: {id_pag},
      success: function(datos){
        //console.log(datos);

        var montoAdeudo = 0;
        montoAdeudo = parseFloat(datos.mon_pag);

        //console.log("montocondonacion: "+montoAdeudo);

        $('#modalCondonacion').draggable();

        $('#modalCondonacion').modal('show');


        $('#id_pag2').attr({value: datos.id_pag});
        $('#mon_pag').attr({value: datos.mon_pag});
        $('#his_pag').attr({value: datos.his_pag});

        $('.selectorCondonacion').materialSelect('destroy');
        $('.selectorCondonacion').materialSelect();

        $('#formularioCondonacion').trigger("reset");
        // shit
        $("#btn_condonacion").removeAttr('disabled','disabled');

        $('#conceptoCondonacion').text(datos.con_pag);

        $('#montoAdeudo').text("$"+datos.mon_pag);
        // $('#identificador').attr({value: datos.id_blo});
        $("#diferenciaCondonacion").text("$"+datos.mon_pag);

        // ASIGNACION DE VALORES EN SELECTS EN ARCHIVOS AJAX NO AGARRA POR DEFAULT
        $(".selectorCondonacion ").eq(0).find('input').attr( { "value": $('#tip1_con_pag').val() } );
        $(".selectorCondonacion ").eq(2).find('input').attr( { "value": $('#motivoCondonacion').val() } );

        obtener_seleccion_tipo_condonacion();

        $('#tip1_con_pag').on('change', function(event) {
          event.preventDefault();
          /* Act on the event */
          obtener_seleccion_tipo_condonacion();

        });

        // SELECTOR DEL TIPO DE CONDONACION
        function obtener_seleccion_tipo_condonacion(){
          if ( $('#tip1_con_pag').val() == 'Monetario' ) {
          // MONETARIO
            $("#contenedor_tipo_condonacion").html('<i class="fas fa-dollar-sign prefix"></i> <input type="number" name="cantidadCondonacion" id="cantidadCondonacion" min="0" step=".001" required="" class="form-control"> <label for="cantidadCondonacion" data-error="wrong" data-success="right">Cantidad...</label>');

            $("#diferenciaCondonacion").text("$"+datos.mon_pag);


            $("#cantidadCondonacion").on('change keyup', function(event) {
              event.preventDefault();
              /* Act on the event */

              //console.log(datos);
              var diferenciaCondonacion = 0;
              
              var cantidadCondonacion = $("#cantidadCondonacion").val();
              //var montoAdeudo = datos.mon_pag;
              diferenciaCondonacion = montoAdeudo-cantidadCondonacion;

              $("#diferenciaCondonacion").text("$"+diferenciaCondonacion.toFixed(3));

              if( cantidadCondonacion >  montoAdeudo){


                //console.log("condicionActiva montoAdeudo: "+datos.mon_pag);


                $("#diferenciaCondonacion").text("Error").removeClass('text-info').addClass('text-danger');

                swal("Error en la condonación", "¡La cantidad condonada NO puede exceder el monto original!", "error", {button: "Aceptar",}).then((value) => {
                    if (value) {
                      //console.log(id_pag);
                      // console.log("json: "+datos.mon_pag);
                      // console.log("montocondonacion: "+montoAdeudo);
                      $("#diferenciaCondonacion").text("$"+montoAdeudo).removeClass('text-danger').addClass('text-info');
                      $("#cantidadCondonacion").val(0);
                    }
                });
              }

            });

          // FIN MONETARIO
          }else{
          // PORCENTUAL
            $("#diferenciaCondonacion").text("$"+datos.mon_pag);



            $("#contenedor_tipo_condonacion").html('<i class="fas fa-percentage prefix"></i> <input type="number" name="cantidadCondonacion" id="cantidadCondonacion" min="1" max="100" step=".1" required="" class="form-control"> <label for="cantidadCondonacion" data-error="wrong" data-success="right">Cantidad...</label>');


            $("#cantidadCondonacion").on('change keyup', function(event) {
              event.preventDefault();
              /* Act on the event */

              //console.log(datos);
              var diferenciaCondonacion = 0;
              
              var cantidadCondonacion = $("#cantidadCondonacion").val();
              //var montoAdeudo = datos.mon_pag;
              

              

              if( cantidadCondonacion <=  100){
              // VALIDADOR QUE CANTIDAD INGRESADA SEA MENOR O IGUAL A 100% 

                diferenciaCondonacion = montoAdeudo - ( (cantidadCondonacion/100 ) * ( montoAdeudo ) );

                $("#diferenciaCondonacion").text("$"+diferenciaCondonacion.toFixed(3));
                //console.log("condicionActiva montoAdeudo: "+datos.mon_pag);

              }else{
                $("#diferenciaCondonacion").text("Error").removeClass('text-info').addClass('text-danger');

                swal("Error en la condonación", "¡La cantidad porcentual condonada NO puede exceder el 100%!", "error", {button: "Aceptar",}).then((value) => {
                    if (value) {
                      //console.log(id_pag);
                      $("#diferenciaCondonacion").text("$"+montoAdeudo).removeClass('text-danger').addClass('text-info');
                      $("#cantidadCondonacion").val(0);
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

          if($('#motivoCondonacion').val() == 'otros'){
          
            $("#contenedorMotivoCondonacion").html(
              '<div class="md-form"><i class="fas fa-info-circle prefix"></i><textarea name="motivoCondonacionOtros" id="motivoCondonacionOtros" class="md-textarea form-control" rows="3" autofocus required=""></textarea><label for="form10">Asigna un motivo...</label></div>'
            );
          }else{

            $("#contenedorMotivoCondonacion").html('');
          }
        });

      }
    });
    

  });
  // FIN CONDONACION DEL COBRO


  //FORMULARIO 
  $('#formularioCondonacion').on('submit', function(event) {
    event.preventDefault();

    $("#btn_condonacion").attr('disabled','disabled');

    var formularioCondonacion = new FormData($('#formularioCondonacion')[0]);

    $.ajax({
    
      url: 'server/agregar_condonacion_pago.php',
      type: 'POST',
      data: formularioCondonacion,
      processData: false,
      contentType: false,
      cache: false,
      success: function(respuesta){
        console.log(respuesta);

        if (respuesta == 'Exito') {
          swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
          then((value) => {

            obtener_notificaciones_cobros_header();
            
            
            for(var i = 0; i < $('.cobrosMostrar').length; i++){
              if($('.cobrosMostrar').eq(i).prop("checked") == true){
                //console.log($('.checador1').eq(i).val());
                
                var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
              }
            }

            obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
            $("#modalCondonacion").modal('hide');
          });
          
        }

      }
    });

  });     
  // FIN FORMULARIO 


  // CONDONACION NO DISPONIBLE
  $(".condonarCobroInvalido").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    swal("¡Ya existe una solicitud de condonación pendiente!", "Solicita al Administrador que atienda las peticiones de condonación", "info", {button: "Aceptar",});

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
      if (name){
        //console.log(name);
        var password = name;
        $.ajax({
          
        url: 'server/validacion_permisos.php',
        type: 'POST',
        data: {password},
        success: function(respuesta){
          console.log(respuesta);

          if (respuesta == 'True') {
            swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
            then((value) => {
              console.log("Existe el password");
              swal({
                title: "¿Deseas eliminar "+nombrePago+"?",
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
                  url: 'server/eliminacion_pago.php',
                  type: 'POST',
                  data: {pago},
                  success: function(respuesta){
                    
                    if (respuesta == "true") {
                      console.log("Exito en consulta");
                      swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                      then((value) => {
                        for(var i = 0; i < $('.cobrosMostrar').length; i++){
                          if($('.cobrosMostrar').eq(i).prop("checked") == true){
                            //console.log($('.checador1').eq(i).val());
                            
                            var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
                          }
                        }

                        obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
                        $("#modalConvenio").modal('hide');
                      });
                    }else{
                      console.log(respuesta);

                    }

                  }
                });
                  
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

    
  });
</script>


<script>
  //ABONO DEL COBRO
  $('.abonarCobro').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    $('#mon_abo_pag').off('change');
    $('#mon_abo_pag').off('keyup');             
    
    id_pag = $(this).attr("id_pag");

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
          console.log(respuesta);

          if (respuesta == 'True') {
            swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
            then((value) => {
              console.log("Existe el password");
              
              // CODIGO

              $.ajax({
                url: 'server/obtener_pago.php',
                type: 'POST',
                dataType: 'json',
                data: {id_pag},
                success: function(datos){
                  //console.log(datos);

                  var montoAdeudo = 0;
                  montoAdeudo = parseFloat(datos.mon_pag);

                  //console.log("montocondonacion: "+montoAdeudo);

                  //$('#modalAbono').draggable();

                  $('#modalAbono').modal('show');


                  $('#id_pag1').attr({value: datos.id_pag});
                  $('#mon_pag_abono').attr({value: datos.mon_pag});
                  $('#his_pag_abono').attr({value: datos.his_pag});
                  
                  $('.selectorAbono').materialSelect('destroy');
                  $('.selectorAbono').materialSelect();

                  $('#conceptoAbono').text(datos.con_pag);

                  $('#montoAdeudoAbono').text("$"+datos.mon_pag);
                  // $('#identificador').attr({value: datos.id_blo});
                  $("#diferenciaAbono").text("$"+datos.mon_pag);

                  seleccionTipoPagoAbono();


                  // OBTENCIOPN DE VALOR DEL SELECT POR CONFLICTOS DE SELECTS CON AJAX
                  $(".selectorAbono").eq(0).find('input').attr( { "value": $('#tip2_abo_pag').val() } );

                  $("#tip2_abo_pag").on('change keyup', function(event) {
                    event.preventDefault();
                    /* Act on the event */
                    // alert();
                    seleccionTipoPagoAbono ();



                  });



                  function seleccionTipoPagoAbono () {
                    var tip2_abo_pag = $("#tip2_abo_pag").val();

                    if ( tip2_abo_pag == 'Dinero Digital' ) {
                      var id_alu = '<?php echo $id_alu; ?>';
                      $.ajax({
                        url: 'server/obtener_datos_saldo_alumno.php',
                        type: 'POST',
                        data: { id_alu, montoAdeudo },
                        success: function ( respuesta ) {

                          $("#contenedor_seleccion_tipo_pago_abono").html(respuesta);

                        }
                      });
                      

                    } else {

                      $("#contenedor_seleccion_tipo_pago_abono").html('<div class="form-check"> <input type="checkbox" class="form-check-input" id="saldarAbono"> <label class="letraPequena font-weight-normal" for="saldarAbono"> Saldar </label> </div><div class="md-form"> <i class="fas fa-dollar-sign prefix"></i> <input type="number" name="mon_abo_pag" id="mon_abo_pag" min="0" step=".001" required="" class="form-control" value="0"> <label for="mon_abo_pag" data-error="wrong" class="active" data-success="right">Cantidad...</label> </div>');

                      $("#mon_abo_pag").focus();
                      
                      obtener_seleccion_tipo_condonacion();

                      $("#saldarAbono").on('change', function(event) {
                        event.preventDefault();
                        /* Act on the event */
                        if ($("#saldarAbono")[0].checked == true) {
                          
                          $("#mon_abo_pag").val(datos.mon_pag).focus();
                          obtener_seleccion_tipo_condonacion();
                        }else{
                          
                          $("#mon_abo_pag").val(0).focus();
                          obtener_seleccion_tipo_condonacion();
                        }
                      });


                      $("#mon_abo_pag").on('change keyup', function(event) {
                          event.preventDefault();
                          /* Act on the event */
                          obtener_seleccion_tipo_condonacion();

                      });

                      // SELECTOR DEL TIPO DE CONDONACION
                      function obtener_seleccion_tipo_condonacion(){
                        

                        var diferenciaAbono = 0;
                          
                        var mon_abo_pag = $("#mon_abo_pag").val();
                        //var montoAdeudo = datos.mon_pag;
                        diferenciaAbono = montoAdeudo-mon_abo_pag;

                        $("#diferenciaAbono").text("$"+diferenciaAbono.toFixed(3));

                        if( mon_abo_pag >  montoAdeudo){


                          //console.log("condicionActiva montoAdeudo: "+datos.mon_pag);


                          $("#diferenciaAbono").text("Error").removeClass('text-info').addClass('text-danger');

                          swal("Error en las cantidades", "¡La cantidad NO puede exceder el monto original!", "error", {button: "Aceptar",}).then((value) => {
                              if (value) {
                                //console.log(id_pag);
                                // console.log("json: "+datos.mon_pag);
                                // console.log("montocondonacion: "+montoAdeudo);
                                $("#diferenciaAbono").text("$"+montoAdeudo).removeClass('text-danger').addClass('text-info');
                                $("#mon_abo_pag").val(0);
                              }
                          });
                        }
                      }


                    }
                  }
                  


                }
                // FIN success
              });
              //  FIN ajax
             


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
  // FIN ABONO DEL COBRO


  //FORMULARIO 
  $('#formularioAbonoPago').on('submit', function(event) {
    event.preventDefault();

    $("#btn_abono_pago").attr('disabled','disabled');

    var formularioAbonoPago = new FormData($('#formularioAbonoPago')[0]);

    $.ajax({
    
      url: 'server/agregar_abono_pago.php',
      type: 'POST',
      data: formularioAbonoPago,
      processData: false,
      contentType: false,
      cache: false,
      success: function(respuesta){
        console.log(respuesta);

        swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
        then((value) => {
          
          for(var i = 0; i < $('.cobrosMostrar').length; i++){
            if($('.cobrosMostrar').eq(i).prop("checked") == true){
              //console.log($('.checador1').eq(i).val());
              
              var cobrosMostrar = $(".cobrosMostrar").eq(i).attr("valor"); 
            }
          }

          obtener_pagos_alumno(id_alu_ram, cobrosMostrar);
          $("#modalAbono").modal('hide');
        });

      }
    });

  });     
  // FIN FORMULARIO 
</script>



<script>

  // FUNCTION REAL-TIME AL CONDONAR O CONVENIR FECHAS
  function obtener_notificaciones_cobros_header(){
    $.ajax({
      url: 'server/obtener_notificaciones_cobros.php',
      success: function( respuesta ){
        $("#contenedor_notificaciones_cobros_header").html( respuesta );
      }
    });
  }
</script>



<script>
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
</script>



<script>
  // MODAL PAGO CONSULTA
  $(".consultaPago").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_pag = $(this).attr("id_pag");
    $.ajax({
      url: 'server/obtener_consulta_pago.php',
      type: 'POST',
      data: { id_pag },
      success: function( respuesta ){
        $("#modalConsultaPago").modal( 'show' );
        $("#contenedorConsultaPago").html( respuesta );
      }
    });
    
  });
  // FIN MODAL PAGO CONSULTA
</script>


<script>
  //SALDO A FAVOR

  $("#agregarSaldo").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    // console.log("click");


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
          console.log(respuesta);

          if (respuesta == 'True') {
            swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
            then((value) => {
              console.log("Existe el password");
              
              // CODIGO

              var id_alu = <?php echo $id_alu; ?>;
              $.ajax({
                url: 'server/obtener_saldo_alumno.php',
                type: 'POST',
                data: { id_alu },
                success: function( respuesta ){
                  $("#modalSaldoAlumno").modal( 'show' );
                  
                  $("#contenedorSaldoAlumno").html( respuesta );
                }
              });
              //alert();


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
</script>