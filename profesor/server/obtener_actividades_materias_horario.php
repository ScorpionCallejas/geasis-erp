<?php  
  //ARCHIVO VIA AJAX PARA OBTENER ACTIVIDADES DE MATERIAS PARA CONFIGURACION AVANZADA DE INSCRIPCION
  //alumnos_carrera.php//server/obtener_alumnos_generacion.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  $id_sub_hor = $_POST['id_sub_hor'];

  $sqlMaterias = "
    SELECT *
    FROM sub_hor
    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
    WHERE id_sub_hor = '$id_sub_hor'
  ";

  $resultadoDatosHorario = mysqli_query( $db, $sqlMaterias );

  $filaDatosHorario = mysqli_fetch_assoc( $resultadoDatosHorario );

  // DATOS RAMA
  $nom_ram = $filaDatosHorario['nom_ram'];
  $mod_ram = $filaDatosHorario['mod_ram'];
  $gra_ram = $filaDatosHorario['gra_ram'];
  $per_ram = $filaDatosHorario['per_ram'];
  $cic_ram = $filaDatosHorario['cic_ram'];

  // DATOS CICLO ESCOLAR
  $nom_cic = $filaDatosHorario['nom_cic'];
  $ins_cic = $filaDatosHorario['ins_cic'];
  $ini_cic = $filaDatosHorario['ini_cic'];
  $cor_cic = $filaDatosHorario['cor_cic'];
  $fin_cic = $filaDatosHorario['fin_cic'];


?>


<div class="row">
  <div class="col-md-6 text-left">
    <div class="card">
      <div class="card-header bg-white">
        Datos del Programa
      </div>
      <div class="card-body">
        <label class="letraPequena">
          Programa: <?php echo $nom_ram; ?>
          <br>
          Modalidad: <?php echo $mod_ram; ?>
          <br>
          Nivel Educativo: <?php echo $gra_ram; ?>
          <br>
          Tipo de Periodo: <?php echo $per_ram; ?>
          <br>
          Cantidad de Periodos: <?php echo $cic_ram; ?>

        </label>

      
      </div>
    </div>
  </div>

  <div class="col-md-6 text-left">
    <div class="card">
      <div class="card-header bg-white">
        Datos del Ciclo Escolar
      </div>
      <div class="card-body">
      

          <label class="letraPequena">
          <?php echo $nom_cic; ?>
          <br>
          Inscripción: <?php echo fechaFormateadaCompacta($ins_cic); ?>
          <br>
          Inicio: <?php echo fechaFormateadaCompacta($ini_cic); ?>
          <br>
          Corte: <?php echo fechaFormateadaCompacta($cor_cic); ?>
          <br>
          Fin: <?php echo fechaFormateadaCompacta($fin_cic); ?>
        </label>
      </div>
    </div>
  </div>

</div>

<br>
<div class="row">
  <div class="col-md-12 text-center">
    <?php
  
      $materias = array();
      $contadorMaterias = 0;

      $resultadoMaterias = mysqli_query( $db, $sqlMaterias );

      while( $filaMaterias = mysqli_fetch_assoc( $resultadoMaterias ) ){
        
        $materias[$contadorMaterias] = $filaMaterias['id_sub_hor'];
    ?>
        <div class="chip animated fadeInDown delay-1s letraPequena font-weight-normal" title="La asignatura de <?php echo $filaMaterias['nom_mat']; ?> es impartida por <?php echo $filaMaterias['nom_pro']." ".$filaMaterias['app_pro']; ?>"> 
          <?php echo $filaMaterias['nom_sub_hor'].' - '.$filaMaterias['nom_mat']." - ".$filaMaterias['nom_pro']." ".$filaMaterias['app_pro']; ?>
      
        </div>


    <?php
        $contadorMaterias++;
      }

      //var_dump( $materias );
    ?>
    
  </div>
  

</div>



<div class="row fadeInDown">
  <div class="col-md-8">

        <table id="tablaActividadesMaterias" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
          <thead class="grey text-white" id="theadRebelde">
            <tr>
              <th class="letraPequena font-weight-normal">#</th>
              <th class="letraPequena font-weight-normal">Selección</th>

              <th class="letraPequena font-weight-normal">Clave</th>

              <th class="letraPequena font-weight-normal">Materia</th>
              <th class="letraPequena font-weight-normal">Bloque</th>
              <th class="letraPequena font-weight-normal">Actividad</th>
              <th class="letraPequena font-weight-normal">Tipo</th>
              <th class="letraPequena font-weight-normal">Puntos</th>
              <th class="letraPequena font-weight-normal">Inicio</th>
              <th class="letraPequena font-weight-normal">Fin</th>
            </tr>
          </thead>
          
          <tbody>
            <?php
              for ( $i = 0, $contador = 1 ; $i < sizeof( $materias ) ; $i++) { 
                $sqlActividadesMaterias = "
                  SELECT nom_sub_hor AS clave, id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, tip_for AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_for_cop AS inicio_copia, fin_for_cop AS fin_copia
                  FROM foro_copia
                  INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
                  INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                  INNER JOIN foro ON foro.id_for = foro_copia.id_for1
                  INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
                  WHERE id_sub_hor2 = '$materias[$i]'
                  UNION
                  SELECT nom_sub_hor AS clave, id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, tip_ent AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_ent_cop AS inicio, fin_ent_cop AS fin
                  FROM entregable_copia
                  INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
                  INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                  INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
                  INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
                  WHERE id_sub_hor3 = '$materias[$i]'
                  UNION
                  SELECT nom_sub_hor AS clave, id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, tip_exa AS tipo, nom_blo AS bloque, id_blo AS id_blo, ini_exa_cop AS inicio, fin_exa_cop AS fin
                  FROM examen_copia
                  INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
                  INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                  INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
                  INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
                  WHERE id_sub_hor4 = '$materias[$i]'
                  ORDER BY materia ASC, id_blo ASC, tipo DESC
                ";

                //echo $sqlActividadesMaterias;

                $resultadoActividadesMaterias = mysqli_query( $db, $sqlActividadesMaterias );

                while( $filaActividadesMaterias = mysqli_fetch_assoc( $resultadoActividadesMaterias ) ){ 

            ?>
                  <tr>
                    <td class="letraPequena font-weight-normal"><br><br><?php echo $contador; ?></td>
                    <td class="letraPequena font-weight-normal">
                      <br><br>
                      <?php  
                        if ( $filaActividadesMaterias['tipo'] == 'Foro' ) {
                      ?>
                          
                          <input type="checkbox" class="form-check-input checkboxActividadesTodos checkboxActividadesForos" id="checkboxActividadesTodos<?php echo $contador; ?>" puntos="<?php echo $filaActividadesMaterias['puntaje']; ?>" tipo="<?php echo $filaActividadesMaterias['tipo']; ?>" identificador="<?php echo $filaActividadesMaterias['id']; ?>" inicio="<?php echo $filaActividadesMaterias['inicio_copia']; ?>" fin="<?php echo $filaActividadesMaterias['fin_copia']; ?>">
                          <label class="form-check-label letraPequena font-weight-normal" for="checkboxActividadesTodos<?php echo $contador; $contador++; ?>"></label>

                      <?php
                        }else if ( $filaActividadesMaterias['tipo'] == 'Entregable' ) {
                      ?>
                          
                          <input type="checkbox" class="form-check-input checkboxActividadesTodos checkboxActividadesEntregables" id="checkboxActividadesTodos<?php echo $contador; ?>" puntos="<?php echo $filaActividadesMaterias['puntaje']; ?>"  tipo="<?php echo $filaActividadesMaterias['tipo']; ?>" identificador="<?php echo $filaActividadesMaterias['id']; ?>" inicio="<?php echo $filaActividadesMaterias['inicio_copia']; ?>" fin="<?php echo $filaActividadesMaterias['fin_copia']; ?>">
                          <label class="form-check-label letraPequena font-weight-normal" for="checkboxActividadesTodos<?php echo $contador; $contador++; ?>"></label>

                      <?php 
                        }else if ( $filaActividadesMaterias['tipo'] == 'Examen' ) {
                      ?>

                          <input type="checkbox" class="form-check-input checkboxActividadesTodos checkboxActividadesExamenes" id="checkboxActividadesTodos<?php echo $contador; ?>" puntos="<?php echo $filaActividadesMaterias['puntaje']; ?>"  tipo="<?php echo $filaActividadesMaterias['tipo']; ?>" identificador="<?php echo $filaActividadesMaterias['id']; ?>" inicio="<?php echo $filaActividadesMaterias['inicio_copia']; ?>" fin="<?php echo $filaActividadesMaterias['fin_copia']; ?>">
                          <label class="form-check-label letraPequena font-weight-normal" for="checkboxActividadesTodos<?php echo $contador; $contador++; ?>"></label>

                      <?php
                        }
                      ?>
                      
                    </td>

                    <td class="letraPequena font-weight-normal"><br><br><?php echo $filaActividadesMaterias['clave']; ?></td>
                    
                    <td class="letraPequena font-weight-normal"><br><br><?php echo $filaActividadesMaterias['materia']; ?></td>
                    <td class="letraPequena font-weight-normal"><br><br><?php echo $filaActividadesMaterias['bloque']; ?></td>
                    <td class="letraPequena font-weight-normal"><br><br><?php echo $filaActividadesMaterias['actividad']; ?></td>
                    <td class="letraPequena font-weight-normal"><br><br><?php echo $filaActividadesMaterias['tipo']; ?></td>
                    <td class="letraPequena font-weight-normal"><br><br><?php echo $filaActividadesMaterias['puntaje']; ?></td>
                    
                    
                    <td class="letraPequena font-weight-normal">
                      <div class="md-form mb-2">
                        <input type="date" class="form-control validate letraPequena font-weight-normal fechaInicioActividad" style="width: 100px;" value="<?php echo $filaActividadesMaterias['inicio_copia']; ?>" fechaInicio="<?php echo $filaActividadesMaterias['inicio_copia']; ?>">
                      </div> 
                      
                    </td>
                    

                    <td class="letraPequena font-weight-normal">
                      <div class="md-form mb-2">
                        <input type="date" class="form-control validate letraPequena font-weight-normal fechaFinActividad" style="width: 100px;" value="<?php echo $filaActividadesMaterias['fin_copia']; ?>" fechaFin="<?php echo $filaActividadesMaterias['fin_copia']; ?>">
                      </div> 
                    </td>
                  </tr>


              <?php
                } 

              ?>

            <?php 
              }

            ?>
          </tbody>
        </table>  
          
  </div>

  <div class="col-md-4">
    
    <div class="card">
      <div class="card-body">
        
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-5 text-left">
                  <br>
                  <p class="letraPequena font-weight-normal">Segmenta actividades por fecha</p>
                  
                  <div class="md-form">
                    <input type="date" class="form-control validate letraPequena font-weight-normal segmentaFecha" value="" id="segmentaFecha">
                  </div>
              </div>


              <div class="col-md-6 text-left">
                <br>

                <div class="form-check">
                  <input type="radio" class="form-check-input segmentaFecha" id="materialGroupExample1" name="radiosPolaridad" value="atras" checked>
                  <label class="form-check-label letraPequena font-weight-normal" for="materialGroupExample1">Atrás</label>
                </div>
                
                <br>

                <div class="form-check">
                  <input type="radio" class="form-check-input segmentaFecha" id="materialGroupExample2" name="radiosPolaridad" value="adelante">
                  <label class="form-check-label letraPequena font-weight-normal" for="materialGroupExample2">Adelante</label>
                </div>

              </div>

          </div>
        
      </div>

    </div>


    <div class="card">
      <div class="card-body">
        
          <div class="row">
              <div class="col-md-12 text-center">
                  <p class="letraPequena font-weight-normal">Incrementa/Decrementa días</p>
                  <a href="#" id="btn_columna_negativa" class="btn btn-info btn-sm">
                      <i class="fas fa-minus"></i>
                  </a>

                  <span id="numeroColumna" numeroColumna="0">
                      0
                  </span>
                  
                  <a href="#" id="btn_columna_positiva" class="btn btn-info btn-sm">
                      <i class="fas fa-plus"></i>
                  </a>
              </div>
          </div>

      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <label for="">
          Seleccionar Todos
          <span id="contadorActividadesTodos" class="badge badge-primary"></span>
          <span id="contadorPuntosActividadesTodos" class="badge badge-success"></span>
        </label>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="seleccionActividadesTodos">
          <label class="form-check-label letraPequena font-weight-normal" for="seleccionActividadesTodos">Seleccionar/Deseleccionar</label>
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <label for="">Seleccionar Foros 
          <span id="contadorActividadesForos" class="badge badge-primary"></span>
          <span id="contadorPuntosActividadesForos" class="badge badge-success"></span>
        </label>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="seleccionActividadesForos">
          <label class="form-check-label letraPequena font-weight-normal" for="seleccionActividadesForos">Seleccionar/Deseleccionar</label>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <label for="">
          Seleccionar Entregables
          <span id="contadorActividadesEntregables" class="badge badge-primary"></span>
          <span id="contadorPuntosActividadesEntregables" class="badge badge-success"></span>
        </label>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="seleccionActividadesEntregables">
          <label class="form-check-label letraPequena font-weight-normal" for="seleccionActividadesEntregables">Seleccionar/Deseleccionar</label>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <label for="">
          Seleccionar Exámenes
          <span id="contadorActividadesExamenes" class="badge badge-primary"></span>
          <span id="contadorPuntosActividadesExamenes" class="badge badge-success"></span>
        </label>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="seleccionActividadesExamenes">
          <label class="form-check-label letraPequena font-weight-normal" for="seleccionActividadesExamenes">Seleccionar/Deseleccionar</label>
        </div>
      </div>
    </div>


    <a class="btn btn-lg btn-info white-text waves-effect" id="btn_finalizar_configuracion_avanzada" title="Guardar">
      <strong>Finalizar</strong>
    </a>
    
  </div>
</div>

<script>
  $(document).ready(function () {


    $('#tablaActividadesMaterias').DataTable({
      
    
      dom: 'frtlip',
            "scrollY": "300px",
            "scrollCollapse": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "pageLength": -1,
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

                    {
                        extend: 'pdf',
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
    $('#tablaActividadesMaterias_wrapper').find('label').each(function () {
      $(this).parent().append($(this).children());
    });
    $('#tablaActividadesMaterias_wrapper .dataTables_filter').find('input').each(function () {
      $('#tablaActividadesMaterias_wrapper input').attr("placeholder", "Buscar...");
      $('#tablaActividadesMaterias_wrapper input').removeClass('form-control-sm');
    });
    $('#tablaActividadesMaterias_wrapper .dataTables_length').addClass('d-flex flex-row');
    $('#tablaActividadesMaterias_wrapper .dataTables_filter').addClass('md-form');
    $('#tablaActividadesMaterias_wrapper select').removeClass(
    'custom-select custom-select-sm form-control form-control-sm');
    $('#tablaActividadesMaterias_wrapper select').addClass('mdb-select');
    $('#tablaActividadesMaterias_wrapper .mdb-select').materialSelect();
    $('#tablaActividadesMaterias_wrapper .dataTables_filter').find('label').remove();
    var botones = $('#tablaActividadesMaterias_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
    //console.log(botones);

  
  });
</script>

<script>

  // POR DEFAULT TODOS ESTAN SELECCIONADOS
  $("#seleccionActividadesTodos").prop({ checked: true });
  $('.checkboxActividadesTodos').prop({ checked: true });
  contadorTodos();
  
  
  $("#seleccionActividadesForos").prop({ checked: true });
  contadorForos();
  $("#seleccionActividadesEntregables").prop({ checked: true });
  contadorEntregables();
  $("#seleccionActividadesExamenes").prop({ checked: true });
  contadorExamenes();

  //SELECCION DE TODOS
  $("#seleccionActividadesTodos").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

    //console.log( $(this)[0].checked );

    if ( $(this)[0].checked == true ) {
      // console.log("checkeado");
      $('.checkboxActividadesTodos').prop({ checked: true });
      
      $("#seleccionActividadesForos").prop({ checked: true });
      $("#seleccionActividadesEntregables").prop({ checked: true });
      $("#seleccionActividadesExamenes").prop({ checked: true });
      toastr.info('Selección de todas las actividades correcta');
    }else{ 
      $('.checkboxActividadesTodos').prop({checked: false});

      $("#seleccionActividadesForos").prop({ checked: false });
      $("#seleccionActividadesEntregables").prop({ checked: false });
      $("#seleccionActividadesExamenes").prop({ checked: false });
      toastr.warning('Deselección de todas las actividades correcta');
    }

    contadorTodos();
    //$('.seleccionAlumno').prop({checked: false});
  });


  //SELECCION DE FOROS
  $("#seleccionActividadesForos").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

    //console.log( $(this)[0].checked );

    if ( $(this)[0].checked == true ) {
      // console.log("checkeado");
      $('.checkboxActividadesForos').prop({ checked: true });
      
      toastr.info('Selección de todos los foros correcta');
    }else{ 
      $('.checkboxActividadesForos').prop({checked: false});
      toastr.warning('Deselección de todos los foros correcta');
    }

    contadorForos();
    contadorTodos();
  });

  $('.checkboxActividadesForos').on('click', function(event) {
    // event.preventDefault();
    /* Act on the event */

    contadorForos();
    contadorTodos();

  });


  //SELECCION DE ENTREGABLES
  $("#seleccionActividadesEntregables").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

    //console.log( $(this)[0].checked );

    if ( $(this)[0].checked == true ) {
      // console.log("checkeado");
      $('.checkboxActividadesEntregables').prop({checked: true});
      toastr.info('Selección de todos los entregables correcta');
    }else{ 
      $('.checkboxActividadesEntregables').prop({checked: false});
      toastr.warning('Deselección de todos los entregables correcta');
    }

    contadorEntregables();
    contadorTodos();
  });

  $('.checkboxActividadesEntregables').on('click', function(event) {
    // event.preventDefault();
    /* Act on the event */

    contadorEntregables();
    contadorTodos();

  });


  //SELECCION DE EXAMENES
  $("#seleccionActividadesExamenes").on('click', function() {
    //event.preventDefault();

    //console.log( $(this)[0].checked );

    if ( $(this)[0].checked == true ) {
      // console.log("checkeado");
      $('.checkboxActividadesExamenes').prop({checked: true});
      toastr.info('Selección de todos los exámenes correcta');
    }else{ 
      $('.checkboxActividadesExamenes').prop({checked: false});
      toastr.warning('Deselección de todos los exámenes correcta');
    }

    contadorExamenes();
    contadorTodos();
  });

  $('.checkboxActividadesExamenes').on('click', function(event) {
    // event.preventDefault();
    /* Act on the event */

    contadorExamenes();
    contadorTodos();

  });




  // FUNCIONES CONTADORES
  
  function contadorForos(){
    for( var i = 0, contadorForos = 0, contadorPuntosForos = 0 ; i < $('.checkboxActividadesForos').length ; i++  ){
      if ( $('.checkboxActividadesForos')[i].checked == true ) {
        // console.log("checkeado");
        contadorForos++;
        if ( $('.checkboxActividadesForos').eq(i).attr('puntos') == "" ) {
          contadorPuntosForos = contadorPuntosForos + 0;
        }else{
          contadorPuntosForos = contadorPuntosForos + parseFloat($('.checkboxActividadesForos').eq(i).attr('puntos'));
        }
        
      }
    }

    $('#contadorActividadesForos').text(contadorForos);
    $('#contadorPuntosActividadesForos').text(contadorPuntosForos);

    return contadorForos;
  }


  function contadorEntregables(){
    for( var i = 0, contadorEntregables = 0, contadorPuntosEntregables = 0 ; i < $('.checkboxActividadesEntregables').length ; i++  ){
      if ( $('.checkboxActividadesEntregables')[i].checked == true ) {
        // console.log("checkeado");
        contadorEntregables++;
        if ( $('.checkboxActividadesEntregables').eq(i).attr('puntos') == "" ) {
          contadorPuntosEntregables = contadorPuntosEntregables + 0;
        }else{
          contadorPuntosEntregables = contadorPuntosEntregables + parseFloat($('.checkboxActividadesEntregables').eq(i).attr('puntos'));
        }
        
      }
    }

    $('#contadorActividadesEntregables').text(contadorEntregables);
    $('#contadorPuntosActividadesEntregables').text(contadorPuntosEntregables);
    
    return contadorEntregables;
  }


  function contadorExamenes(){
    for( var i = 0, contadorExamenes = 0, contadorPuntosExamenes = 0 ; i < $('.checkboxActividadesExamenes').length ; i++  ){
      if ( $('.checkboxActividadesExamenes')[i].checked == true ) {
        // console.log("checkeado");
        contadorExamenes++;
        if ( $('.checkboxActividadesExamenes').eq(i).attr('puntos') == "" ) {
          contadorPuntosExamenes = contadorPuntosExamenes + 0;
        }else{
          contadorPuntosExamenes = contadorPuntosExamenes + parseFloat($('.checkboxActividadesExamenes').eq(i).attr('puntos'));
        }
        
      }
    }

    $('#contadorActividadesExamenes').text(contadorExamenes);
    $('#contadorPuntosActividadesExamenes').text(contadorPuntosExamenes);
    
    return contadorExamenes;
  }

  function contadorTodos(){
    
    contadorForos();
    contadorEntregables();
    contadorExamenes();

    $('#contadorActividadesTodos').text( (contadorForos() + contadorEntregables() + contadorExamenes() ) );
    $('#contadorPuntosActividadesTodos').text( parseFloat($('#contadorPuntosActividadesForos').text()) + parseFloat($('#contadorPuntosActividadesEntregables').text()) + parseFloat($('#contadorPuntosActividadesExamenes').text()) );
  }
</script>


<script>
  $(".fechaInicioActividad").on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    var indice = $('.fechaInicioActividad').index(this);
    var inicio = $(this).val();
    $(".checkboxActividadesTodos").eq(indice).attr({"inicio": inicio});

  });


  $(".fechaFinActividad").on('change', function(event) {
    event.preventDefault();
    /* Act on the event */
    var indice = $('.fechaFinActividad').index(this);
    var inicio = $(this).val();
    $(".checkboxActividadesTodos").eq(indice).attr({"fin": inicio});

  });
</script>



<!-- CONTADOR DIAS -->
<script>
    $("#btn_columna_positiva").on('click', function(event) {
        event.preventDefault();


        var numeroColumna = $("#numeroColumna").attr("numeroColumna");
        numeroColumna++;

        $("#numeroColumna").removeAttr('numeroColumna').attr("numeroColumna", numeroColumna);
        $("#numeroColumna").html(numeroColumna);

        for ( var i = 0 ; i < $(".fechaInicioActividad").length ; i++ ) {

          var dias = numeroColumna+1; // Número de días a agregar

          var fechaInicio = new Date( $('.fechaInicioActividad').eq( i ).attr('fechaInicio') );     
          var fechaInicioFormateada = new Date( fechaInicio.setDate(fechaInicio.getDate() + dias) );
          $('.fechaInicioActividad').eq( i ).val( moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' ) );
          $(".checkboxActividadesTodos").eq( i ).attr({"inicio": moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' ) });


          var fechaFin = new Date( $('.fechaFinActividad').eq( i ).attr('fechaFin') );     
          var fechaFinFormateada = new Date( fechaFin.setDate(fechaFin.getDate() + dias) );
          $('.fechaFinActividad').eq( i ).val( moment( fechaFinFormateada ).format( 'YYYY-MM-DD' ) );
          $(".checkboxActividadesTodos").eq( i ).attr({"fin": moment( fechaFinFormateada ).format( 'YYYY-MM-DD' ) });

        }
        
    });


    $("#btn_columna_negativa").on('click', function(event) {
        event.preventDefault();


        var numeroColumna = $("#numeroColumna").attr("numeroColumna");
        numeroColumna--;



        $("#numeroColumna").removeAttr('numeroColumna').attr("numeroColumna", numeroColumna);
        $("#numeroColumna").html(numeroColumna);

        for ( var i = 0 ; i < $(".fechaInicioActividad").length ; i++ ) {

          var dias = numeroColumna+1; // Número de días a agregar

          var fechaInicio = new Date( $('.fechaInicioActividad').eq( i ).attr('fechaInicio') );     
          var fechaInicioFormateada = new Date( fechaInicio.setDate(fechaInicio.getDate() + dias) );
          $('.fechaInicioActividad').eq( i ).val( moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' ) );
          $(".checkboxActividadesTodos").eq( i ).attr({"inicio": moment( fechaInicioFormateada ).format( 'YYYY-MM-DD' ) });


          var fechaFin = new Date( $('.fechaFinActividad').eq( i ).attr('fechaFin') );     
          var fechaFinFormateada = new Date( fechaFin.setDate(fechaFin.getDate() + dias) );
          $('.fechaFinActividad').eq( i ).val( moment( fechaFinFormateada ).format( 'YYYY-MM-DD' ) );
          $(".checkboxActividadesTodos").eq( i ).attr({"fin": moment( fechaFinFormateada ).format( 'YYYY-MM-DD' ) });


        }

        

    });



</script>
<!-- FIN CONTADOR DIAS -->

<!-- SEGMENTA FECHAS -->
<script>
  $( ".segmentaFecha" ).on( 'change', function( event ) {
    event.preventDefault();
    /* Act on the event */

    var fechaPivote = new Date( $( "#segmentaFecha" ).val() );
    var fechaPivoteFormateada = new Date( fechaPivote.setDate( fechaPivote.getDate() + 1 ) );

    var polaridad = $( 'input[name=radiosPolaridad]:checked' ).val();

    if ( moment( fechaPivote ).isValid() == true ) {
    // FECHA VALIDA
      // alert( 'La fecha es: '+fechaPivote+'. y la polaridad es: '+polaridad );

      for ( var i = 0 ; i < $( ".checkboxActividadesTodos" ).length ; i++ ) {

        var fechaInicio = new Date( $( ".checkboxActividadesTodos" ).eq( i ).attr( 'inicio' ) );
        var fechaInicioFormateada = new Date( fechaInicio.setDate( fechaInicio.getDate() + 1 ) );

        if ( polaridad == 'atras' ) {

          if ( i == 1 ) {
            toastr.warning( 'Se deseleccionaron actividades menores o igual a '+moment( fechaPivoteFormateada ).format( 'DD/MM/YYYY' ) );
          }

          if ( fechaPivoteFormateada >= fechaInicioFormateada ) {            

            // FOROS
            if ( ( $("#seleccionActividadesForos")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Foro'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: false } );

              }
              

            }
            // FIN FOROS



            // ENTREGABLES
            if ( ( $("#seleccionActividadesEntregables")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Entregable'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: false } );

              }
              

            }
            // FIN ENTREGABLES


            // EXAMENES
            if ( ( $("#seleccionActividadesExamenes")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Examen'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: false } );

              }
              

            }
            // FIN EXAMENES
            
            contadorTodos();
            
            
          } else {
            

            // FOROS
            if ( ( $("#seleccionActividadesForos")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Foro'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: true } );
                
              }
            }
            // FIN FOROS


            // ENTREGABLES
            if ( ( $("#seleccionActividadesEntregables")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Entregable'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: true } );

              }
              

            }
            // FIN ENTREGABLES


            // EXAMENES
            if ( ( $("#seleccionActividadesExamenes")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Examen'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: true } );

              }
              

            }
            // FIN EXAMENES
            
            contadorTodos();
            // alert( 'La fecha pivote: '+fechaPivoteFormateada+ ' NO es mayor que la fecha inicio: '+fechaInicioFormateada );
          
          }

        } else if ( polaridad == 'adelante' ) {

          if ( i == 1 ) {
            toastr.warning( 'Se deseleccionaron actividades mayores o igual a '+moment( fechaPivoteFormateada ).format( 'DD/MM/YYYY' ) );
          }
          

          if ( fechaPivoteFormateada <= fechaInicioFormateada ) {

            // FOROS
            if ( ( $("#seleccionActividadesForos")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Foro'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: false } );

              }
              

            }
            // FIN FOROS



            // ENTREGABLES
            if ( ( $("#seleccionActividadesEntregables")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Entregable'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: false } );

              }
              

            }
            // FIN ENTREGABLES


            // EXAMENES
            if ( ( $("#seleccionActividadesExamenes")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Examen'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: false } );

              }
              

            }
            // FIN EXAMENES
            
            
            contadorTodos();
            
          } else {
            

            // FOROS
            if ( ( $("#seleccionActividadesForos")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Foro'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: true } );
                
              }
            }
            // FIN FOROS


            // ENTREGABLES
            if ( ( $("#seleccionActividadesEntregables")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Entregable'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: true } );

              }
              

            }
            // FIN ENTREGABLES


            // EXAMENES
            if ( ( $("#seleccionActividadesExamenes")[0].checked == true ) ||  ( $("#seleccionActividadesTodos")[0].checked == true ) ) {

              if ( $( ".checkboxActividadesTodos" ).eq( i ).attr('tipo') == 'Examen'  ) {

                $( ".checkboxActividadesTodos" ).eq( i ).prop( { checked: true } );

              }
              

            }
            // FIN EXAMENES

            contadorTodos();
            
        
            // alert( 'La fecha pivote: '+fechaPivoteFormateada+ ' NO es mayor que la fecha inicio: '+fechaInicioFormateada );
          
          }

        }

        

        

      }
    // FIN FECHA VALIDA
    } else {

      $('.checkboxActividadesTodos').prop({ checked: true });
      
      $("#seleccionActividadesForos").prop({ checked: true });
      $("#seleccionActividadesEntregables").prop({ checked: true });
      $("#seleccionActividadesExamenes").prop({ checked: true });
      toastr.info('Selección de todas las actividades correcta');
      contadorTodos();
      toastr.error( 'Ingresa una fecha válida' );
    
    }

    
  });
</script>
<!-- FIN SEGMENTA FECHAS -->