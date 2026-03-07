<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	// $id_pla = $_POST['id_pla'];

	$id_pla = $_POST['id_pla'];

  // echo "backend: ".$id_pla;
	//fechaDia( $fecha );


?>
  
  <style>

    .eliminacionEgreso {
        position: absolute;
        top: 5px;
        right: 5px;
        color: red;
        cursor: pointer;
        font-size: 18px;
    }

    .table td, .table th {
      padding: 1;
      margin: 0;
    }

    .textoEnde{
      color: #05B6DA;
    }

    .textoNegro{
      color: black;
    }

    .textoNegrito{
      font-weight: bold
    }

    .fondoEnde{
      background: #05B6DA;
    }

    .textoBlanco{
      color: white;
    }

    .fondoNegro{
      background: black;
    }

    .fondoBlanco{
      background: white;
    }

    
  </style>

  <?php 
    function generarColumnasTabla($tipo, $cantidad, $color = 'white') {
      // Iniciamos una cadena vacía para almacenar las celdas
      $columnas = '';
  
      // Generamos las celdas según la cantidad y el tipo
      for ($i = 0; $i < $cantidad; $i++) {
          $columnas .= "<$tipo class='letraPequena' style='background: $color;'>--</$tipo>";
      }
  
      echo $columnas;
  }

    $sql = "SELECT obtener_tramite_deposito( '$inicio', '$fin', $id_pla ) AS total";
    //echo $sql;
    $tramite_deposito = obtener_datos_consulta($db, $sql)['datos']['total'];
  ?>

  
  <div class="row">
    <div class="col-md-12">
      <!--  -->
  
      
      <div class="table-responsive">
  
        <table id="tabla_reporte_azul" class="table table-sm table-bordered" >
          <!-- TITULOS -->
          <thead>
            <tr style="width:100%;">
              
              <th class="letraPequena textoEnde textoNegrito" style="background: black;">SEMANA</th>
              <th style="background: black;"></th>
              <?php 
                generarColumnasTabla( "th", 11 );
              ?>
            </tr>
          </thead>
          <!-- F TITULOS -->

          <!-- CONTENIDO TABLA -->
          <tbody>
            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoBlanco" style="background: black">Cobranza Semanal (Cob.total)</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                    $sql = "SELECT obtener_cobranza_semanal( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>
            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoNegro" style="background: white">Trámites semanal total</td>
              <td class="letraPequena textoNegro" style="background: white">
                <?php 
                    $sql = "SELECT obtener_tramites_semanal( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>
            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoBlanco" style="background: black">Regalías</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                    $sql = "SELECT obtener_gastos_regalias( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>
            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoNegro" style="background: white">Cobranza efectivo</td>
              <td class="letraPequena textoNegro" style="background: white">
                <?php 
                    $sql = "SELECT obtener_cobranza_efectivo( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>
            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoBlanco" style="background: black">Gastos en efectivo</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                    $sql = "SELECT obtener_gastos_efectivo( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>

            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoNegro" style="background: white">Sobrante</td>
              <td class="letraPequena textoNegro" style="background: white">
                <?php 
                    echo formatearDinero(0);
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>

            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoBlanco" style="background: black">Cobranza en la cuenta empresarial</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                    $sql = "SELECT obtener_cobranza_cuenta( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $cobranza_cuenta_empresarial = obtener_datos_consulta($db, $sql)['datos']['total'];
                    echo formatearDinero( $cobranza_cuenta_empresarial );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>

            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoNegro" style="background: white">Gastos en la cuenta empresarial</td>
              <td class="letraPequena textoNegro" style="background: white">
                <?php 
                    $sql = "SELECT obtener_gastos_cuenta( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $gastos_cuenta_empresarial = obtener_datos_consulta($db, $sql)['datos']['total'];
                    echo formatearDinero( $gastos_cuenta_empresarial );
                ?>
              </td>
              <?php 
                generarColumnasTabla( "td", 11 );
              ?>
            </tr>

            
           
            <!--  -->
            <tr style="color: black; font-weight: bold;">
              <td class="letraPequena textoBlanco" style="background: black">Cuenta empresarial</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $cuenta_empresarial = ( $cobranza_cuenta_empresarial + $tramite_deposito ) - $gastos_cuenta_empresarial; 
                  echo formatearDinero($cuenta_empresarial);
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 2, "black" );
              ?>

              <td class="letraPequena textoBlanco" style="background: black">DIA</td>
              <td class="letraPequena textoBlanco" style="background: black">Lunes</td>
              <td class="letraPequena textoBlanco" style="background: black">Martes</td>
              <td class="letraPequena textoBlanco" style="background: black">Miércoles</td>
              <td class="letraPequena textoBlanco" style="background: black">Jueves</td>
              <td class="letraPequena textoBlanco" style="background: black">Viernes</td>
              <td class="letraPequena textoBlanco" style="background: black">Sábado</td>
              <td class="letraPequena textoBlanco" style="background: black">Domingo</td>

              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>
            </tr>
            <tr style="color: black; font-weight: bold;">

              
              <td class="letraPequena textoNegro" style="background: white">Tramite en efectivo</td>
              <td class="letraPequena textoNegro" style="background: white">
                <?php 
                    $sql = "SELECT obtener_tramite_efectivo( '$inicio', '$fin', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>  
              </td>

              <?php 
                generarColumnasTabla( "td", 2, "black" );
              ?>

              <?php 
                $dia_1 = $inicio;
                $dia_2 = sumarDias($inicio, 1);
                $dia_3 = sumarDias($inicio, 2);
                $dia_4 = sumarDias($inicio, 3);
                $dia_5 = sumarDias($inicio, 4);
                $dia_6 = sumarDias($inicio, 5);
                $dia_7 = sumarDias($inicio, 6);
              ?>
              <td class="letraPequena textoNegro" style="background: white">FECHA</td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_1); ?></td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_2); ?></td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_3); ?></td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_4); ?></td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_5); ?></td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_6); ?></td>
              <td class="letraPequena textoNegro" style="background: white"><?php echo fechaFormateadaCompacta($dia_7); ?></td>

              <?php 
                generarColumnasTabla( "td", 1 );
              ?>
            </tr>

            <!-- COBRANZA EN EFECTIVO -->
            <tr style="color: black; font-weight: bold;">

              
              <td class="letraPequena textoBlanco" style="background: black">Tramite en depósito</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                    echo formatearDinero( $tramite_deposito );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 2, "black" );
              ?>

              <td class="letraPequena textoBlanco" style="background: black">COBRANZA EN EFECTIVO</td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_1', '$dia_1', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_2', '$dia_2', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_3', '$dia_3', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_4', '$dia_4', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_5', '$dia_5', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_6', '$dia_6', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              <td class="letraPequena textoBlanco" style="background: black">
                <?php 
                  $sql = "SELECT obtener_cobranza_efectivo( '$dia_7', '$dia_7', $id_pla ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>
              
              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>
            </tr>
            <!-- F COBRANZA EN EFECTIVO -->

            <!--  -->

            <!-- TITULOS COBRANZA Y GTO EN EFECTIVO -->
            <tr>
              <td class="letraPequena textoBlanco textoNegrito" style="background: black">Cobranza</td>
              <?php 
                generarColumnasTabla( "td", 5, "black" );
              ?>

              <td class="letraPequena textoBlanco textoNegrito" style="background: black">Gastos en efectivo</td>
              <?php 
                generarColumnasTabla( "td", 6, "black" );
              ?>
            </tr>
            <!-- F TITULOS COBRANZA Y GTO EN EFECTIVO -->
            
            <!-- TITULOS DE 3 TABLAS -->
            <tr>
              
              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Trámites en efectivo</td>

              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Colegiaturas en efectivo</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Concepto del gasto</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Lunes</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Martes</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Miércoles</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Jueves</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Viernes</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Sábado</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Domingo</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Observaciones</td>
              
            </tr>

            <!-- LUNES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Lunes</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Lunes</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F LUNES -->

            <!-- MARTES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Martes</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Martes</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F MARTES -->

            <!-- MIÉRCOLES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Miércoles</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Miércoles</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F MIÉRCOLES -->

            <!-- JUEVES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Jueves</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Jueves</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F JUEVES -->

            <!-- VIERNES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Viernes</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Viernes</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F VIERNES -->

            <!-- SÁBADO -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Sábado</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Sábado</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F SÁBADO -->

            <!-- DOMINGO -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Domingo</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_efectivo_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Domingo</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_efectivo_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F DOMINGO -->

            <!-- TRAMITES EN DEPOSITO Y COLEGIATURAS EN DEPOSITO -->
            <tr>
              
              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Trámites en depósito</td>

              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Colegiaturas en depósito</td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
              
            </tr>

            <!-- LUNES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Lunes</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Lunes</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_1', '$dia_1' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F LUNES -->

            <!-- MARTES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Martes</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Martes</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_2', '$dia_2' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F MARTES -->

            <!-- MIÉRCOLES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Miércoles</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Miércoles</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_3', '$dia_3' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F MIÉRCOLES -->

            <!-- JUEVES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Jueves</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Jueves</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_4', '$dia_4' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F JUEVES -->

            <!-- VIERNES -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Viernes</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Viernes</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_5', '$dia_5' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F VIERNES -->

            <!-- SÁBADO -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Sábado</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Sábado</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_6', '$dia_6' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F SÁBADO -->

            <!-- DOMINGO -->
            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Domingo</td>
              
              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_tramite_deposito_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Domingo</td>

              <td class="letraPequena textoNegro textoNegrito" style="background: white">
                <?php 
                  $sql = "SELECT obtener_abonado_colegiatura_deposito_plantel( $id_pla, '$dia_7', '$dia_7' ) AS total";
                    //echo $sql;
                    $datos = obtener_datos_consulta($db, $sql);
                    echo formatearDinero( $datos['datos']['total'] );
                ?>
              </td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>
            <!-- F DOMINGO -->

            <!-- F TRAMITES EN DEPOSITO Y COLEGIATURAS EN DEPOSITO -->


            <!-- GASTOS EN LA CUENTA EMPRESARIAL -->
            <tr>
              
              <?php 
                generarColumnasTabla( "td", 1, "black" );
              ?>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Gastos en la cuenta empresarial</td>

              <?php 
                generarColumnasTabla( "td", 2, "black" );
              ?>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
              
            </tr>

            <tr>
              <td class="letraPequena textoEnde textoNegrito" style="background: black">Fecha</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Concepto / Referencia</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Cargo</td>

              <td class="letraPequena textoEnde textoNegrito" style="background: black">Observación</td>

              <?php 
                generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
              ?>
            </tr>

              <!-- TABLA DE GASTOS EN LA CUENTA EMPRESARIAL -->
              <?php 
                $sqlEgresos = "
                  SELECT * 
                  FROM egreso 
                  WHERE 
                  ( DATE(fec_egr) BETWEEN DATE('$inicio') AND DATE('$fin') ) AND 
                  id_pla13 = $id_pla AND 
                  for_egr = 'Gasto en cuenta empresarial'
                ";

                $sumatoriaEgresos = 0;
                $resultadoEgresos = mysqli_query( $db, $sqlEgresos );
                while( $filaEgresos = mysqli_fetch_assoc( $resultadoEgresos ) ){
                  $id_egr = $filaEgresos['id_egr'];
              ?>
                  <tr>
                    <td class="letraPequena textoNegro textoNegrito" style="background: #05B6DA">
                      <?php echo fechaFormateadaCompacta( $filaEgresos['fec_egr'] ); ?>
                    </td>

                    <td class="letraPequena textoNegro textoNegrito" style="background: #05B6DA; position: relative;">

                      <span class="eliminacionEgreso" id_egr="<?php echo $id_egr; ?>">&times;</span>
                      <?php echo $filaEgresos['con_egr']; ?>
                    </td>

                    <td class="letraPequena textoNegro textoNegrito" style="background: white">
                      <?php echo formatearDinero($filaEgresos['mon_egr']); ?>
                    </td>

                    <td class="letraPequena textoNegro textoNegrito" style="background: white">
                      <?php echo $filaEgresos['obs_egr']; ?>
                    </td>

                    <?php 
                      generarColumnasTabla( "td", 1, "#05B6DA" ); generarColumnasTabla( "td", 8 );
                    ?>
                  </tr>
              <?php
                  $sumatoriaEgresos += $filaEgresos['mon_egr'];
                }
              ?>
              <!-- F TABLA DE GASTOS EN LA CUENTA EMPRESARIAL -->

            <!-- F GASTOS EN LA CUENTA EMPRESARIAL -->


            <!-- T5 GASTOS EN EFECTIVO -->

            <?php
              $sqlEgresosEfectivo = "
                SELECT * 
                FROM egreso 
                WHERE 
                ( DATE(fec_egr) BETWEEN '$inicio' AND '$fin' ) AND 
                id_pla13 = '$id_pla' AND 
                for_egr = 'Gasto en efectivo'
                GROUP BY con_egr
              ";

              $resultadoEgresosEfectivo = mysqli_query( $db, $sqlEgresosEfectivo );
              while( $filaEgresosEfectivo = mysqli_fetch_assoc( $resultadoEgresosEfectivo ) ){
                $id_egr = $filaEgresosEfectivo['id_egr'];
                $con_egr = $filaEgresosEfectivo['con_egr'];
                $obs_egr = $filaEgresosEfectivo['obs_egr'];
            ?>
              <tr>
                <td class="letraPequena textoNegro textoNegrito" style="background: #05B6DA">
                </td>

                <td class="letraPequena textoNegro textoNegrito" style="background: #05B6DA">
                </td>

                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                </td>

                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                </td>

                <!-- CONCEPTO -->
                <td class="letraPequena textoNegro textoNegrito" style="background: #05B6DA; position: relative;">
                  <span class="eliminacionEgreso" id_egr="<?php echo $id_egr; ?>">&times;</span>
                  <?php echo $filaEgresosEfectivo['con_egr']; ?>
                </td>
                <!-- F CONCEPTO -->

                <!-- LUNES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoLunes = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_1' AND '$dia_1' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoLunes = obtener_datos_consulta( $db, $sqlEgresosEfectivoLunes )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoLunes ); 
                  ?>
                </td>
                <!-- F LUNES -->

                <!-- MARTES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoMartes = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_2' AND '$dia_2' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoMartes = obtener_datos_consulta( $db, $sqlEgresosEfectivoMartes )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoMartes ); 
                  ?>
                </td>
                <!-- F MARTES -->

                <!-- MIÉRCOLES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoMiercoles = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_3' AND '$dia_3' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoMiercoles = obtener_datos_consulta( $db, $sqlEgresosEfectivoMiercoles )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoMiercoles ); 
                  ?>
                </td>
                <!-- F MIÉRCOLES -->

                <!-- JUEVES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoJueves = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_4' AND '$dia_4' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoJueves = obtener_datos_consulta( $db, $sqlEgresosEfectivoJueves )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoJueves ); 
                  ?>
                </td>
                <!-- F JUEVES -->

                <!-- VIERNES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoViernes = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_5' AND '$dia_5' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoViernes = obtener_datos_consulta( $db, $sqlEgresosEfectivoViernes )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoViernes ); 
                  ?>
                </td>
                <!-- F VIERNES -->

                <!-- SÁBADO -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoSabado = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_6' AND '$dia_6' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoSabado = obtener_datos_consulta( $db, $sqlEgresosEfectivoSabado )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoSabado ); 
                  ?>
                </td>
                <!-- F SÁBADO -->

                <!-- DOMINGO -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php
                    $sqlEgresosEfectivoDomingo = "
                      SELECT *, SUM( mon_egr ) AS total
                      FROM egreso 
                      WHERE
                      con_egr = '$con_egr' AND
                      ( DATE(fec_egr) BETWEEN '$dia_7' AND '$dia_7' ) AND 
                      id_pla13 = '$id_pla' AND 
                      for_egr = 'Gasto en efectivo'
                    ";

                    $datosEgresosEfectivoDomingo = obtener_datos_consulta( $db, $sqlEgresosEfectivoDomingo )['datos']['total'];

                    echo formatearDinero( $datosEgresosEfectivoDomingo ); 
                  ?>
                </td>
                <!-- F DOMINGO -->

                <!-- OBSERVACIONES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white;">
                  <?php echo $obs_egr; ?>
                </td>
                <!-- F OBSERVACIONES -->

                



              </tr>
            <?php 
              }
            ?>
            <!-- F T5 GASTOS EN EFECTIVO -->

            <!-- CLAUDE -->
              <!-- TOTALES DE GASTOS EN LA CUENTA EMPRESARIAL -->
              <tr>
                <?php 
                  generarColumnasTabla("td", 3, "black");
                ?>
                

                <?php 
                  generarColumnasTabla("td", 1, "black");
                  generarColumnasTabla("td", 1, "#05B6DA");
                  generarColumnasTabla("td", 8);
                ?>
              </tr>

              <!-- TOTALES DE GASTOS EN EFECTIVO POR DÍA -->
              <tr>
                <td class="letraPequena textoBlanco textoNegrito" style="background: black"></td>
                <td class="letraPequena textoBlanco textoNegrito" style="background: black"></td>
                <td class="letraPequena textoBlanco textoNegrito" style="background: black"></td>
                <td class="letraPequena textoBlanco textoNegrito" style="background: black"></td>

                <td class="letraPequena textoBlanco textoNegrito" style="background: black">Total de Gastos</td>

                <!-- TOTAL LUNES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalLunes = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_1' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalLunes = obtener_datos_consulta($db, $sqlTotalLunes)['datos']['total'];
                    echo formatearDinero($totalLunes);
                  ?>
                </td>

                <!-- TOTAL MARTES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalMartes = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_2' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalMartes = obtener_datos_consulta($db, $sqlTotalMartes)['datos']['total'];
                    echo formatearDinero($totalMartes);
                  ?>
                </td>

                <!-- TOTAL MIÉRCOLES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalMiercoles = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_3' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalMiercoles = obtener_datos_consulta($db, $sqlTotalMiercoles)['datos']['total'];
                    echo formatearDinero($totalMiercoles);
                  ?>
                </td>

                <!-- TOTAL JUEVES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalJueves = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_4' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalJueves = obtener_datos_consulta($db, $sqlTotalJueves)['datos']['total'];
                    echo formatearDinero($totalJueves);
                  ?>
                </td>

                <!-- TOTAL VIERNES -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalViernes = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_5' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalViernes = obtener_datos_consulta($db, $sqlTotalViernes)['datos']['total'];
                    echo formatearDinero($totalViernes);
                  ?>
                </td>

                <!-- TOTAL SÁBADO -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalSabado = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_6' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalSabado = obtener_datos_consulta($db, $sqlTotalSabado)['datos']['total'];
                    echo formatearDinero($totalSabado);
                  ?>
                </td>

                <!-- TOTAL DOMINGO -->
                <td class="letraPequena textoNegro textoNegrito" style="background: white">
                  <?php
                    $sqlTotalDomingo = "
                      SELECT SUM(mon_egr) AS total
                      FROM egreso 
                      WHERE DATE(fec_egr) = '$dia_7' 
                      AND id_pla13 = '$id_pla'
                      AND for_egr = 'Gasto en efectivo'
                    ";
                    $totalDomingo = obtener_datos_consulta($db, $sqlTotalDomingo)['datos']['total'];
                    echo formatearDinero($totalDomingo);
                  ?>
                </td>

                <td class="letraPequena textoNegro textoNegrito" style="background: white"></td>
              </tr>

              <!-- SALDO EN EFECTIVO (DIFERENCIA ENTRE COBRANZA Y GASTOS) -->
              <tr>
                <td class="letraPequena textoBlanco textoNegrito" style="background: black"></td>
                <td class="letraPequena textoBlanco textoNegrito" style="background: black"></td>

                <td class="letraPequena textoBlanco textoNegrito" style="background: black">Total de Gastos</td>
                
                <td class="letraPequena textoBlanco textoNegrito" style="background: black">
                  <?php echo formatearDinero($sumatoriaEgresos); ?>
                </td>

                

                <td class="letraPequena textoBlanco textoNegrito" style="background: black">Saldo en efectivo</td>

                <?php
                  // Obtener cobranza en efectivo por día
                  $diasSemana = [$dia_1, $dia_2, $dia_3, $dia_4, $dia_5, $dia_6, $dia_7];
                  $totalesGastos = [$totalLunes, $totalMartes, $totalMiercoles, $totalJueves, $totalViernes, $totalSabado, $totalDomingo];
                  
                  foreach($diasSemana as $index => $dia) {
                    $sqlCobranza = "SELECT obtener_cobranza_efectivo('$dia', '$dia', $id_pla) AS total";
                    $cobranza = obtener_datos_consulta($db, $sqlCobranza)['datos']['total'];
                    $saldo = $cobranza - $totalesGastos[$index];
                ?>
                    <td class="letraPequena textoBlanco textoNegrito" style="background: black">
                      <?php echo formatearDinero($saldo); ?>
                    </td>
                <?php
                  }
                ?>

                <td class="letraPequena textoBlanco textoNegrito" style="background: black;"></td>
              </tr>
            <!-- F CLAUDE -->

            <!-- F TITULOS DE 3 TABLAS -->
          </tbody>
          <!-- F CONTENIDO TABLA -->
          
        </table>

      </div>
      
      <!--  -->
    </div>
  
  </div>
  
  <hr>
  
  <br><br>
  <br><br><br><br><br><br><br><br><br>

  <script src="../js/jszip.min.js"></script>
  <script src="../js/pdfmake.min.js"></script>
  <script src="../js/vfs_fonts.js"></script>
  <script src="../js/buttons.html5.min.js"></script>
  <script src="../js/buttons.print.min.js"></script>
  <script src="../js/buttons.colVis.min.js"></script>

<script>
    $('#tabla_reporte_azul').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        ordering: false, // Desactiva la ordenación
        // dom: 'Bfrtip',
      dom: 'Bfrtip',
          buttons: [
              {
                  extend: 'excelHtml5',
                  title: 'REPORTE POTENCIAL',
                  className: 'btn-sm btn-success'
              },
          ],
          language: {
              search: 'Buscar' // Cambia el texto del buscador
          },
          info: false // Esto desactiva la información del pie de la tabla

      });
  </script>


  <script>
    $('.eliminacionEgreso').on('click', function() {
        var id_egr = $(this).attr('id_egr');
        var elemento = $(this).closest('tr'); // Asumiendo que quieres eliminar la fila completa

        swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrás recuperar este registro.",
            icon: "warning",
            buttons: ["Cancelar", "Eliminar"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                
              // 
              $.ajax({
                url: 'server/controlador_egreso.php',
                type: 'POST',
                dataType: 'json', // Importante para que jQuery parsee la respuesta como JSON
                data: {
                    accion: 'Eliminar',
                    id_egr: id_egr
                },
                success: function(resp) {
                    if (resp.resultado == 'exito') {
                        swal("¡Registro eliminado!", "Continuar", {
                            icon: "success",
                            button: "Aceptar",
                        });
                        obtener_datos();
                    } else {
                        swal("Error al eliminar el registro.", {
                            icon: "error",
                            button: "Aceptar",
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en la petición AJAX:', textStatus, errorThrown);
                    swal("Error en la comunicación con el servidor.", {
                        icon: "error",
                        button: "Aceptar",
                    });
                }
            });

              // 
            } else {
                swal("El registro no ha sido eliminado.", {
                    button: "Aceptar",
                });
            }
        });
    });

</script>