<?php  
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];

    $id_pla = $_POST['id_pla'];

    $semanas = obtenerSemanasPeriodo($inicio, $fin);
    $semanaActual = date('W');
?>
    
<style>
    .bg-orange {
        background-color: #F8C851 !important;
        color: #fff !important;
    }
    .bg-light-green {
        background-color: #90EE90 !important;
        color: #fff !important;
    }
    .bg-light-blue {
        background-color: #ADD8E6 !important;
        color: #fff !important;
    }
    .text-red {
        color: red !important;
    }
    .table td, .table th {
        padding: 0;
    }
</style>

<div class="row">
    <div class="col-12">
        <span class="letraMonday">REPORTE SEMANAL</span>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm m-0" id="tabla_reporte_semanal">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-center">Semana</th>
                                <th class="text-center">Cobranza</th>
                                <th class="text-center">Alumnos Inicial</th>
                                <th class="text-center">Reingresos</th>
                                <th class="text-center">Inicios</th>
                                <th class="text-center">Bajas</th>
                                <th class="text-center">Deserción</th>
                                <th class="text-center">Fin de curso</th>
                                <th class="text-center">Graduados</th>
                                <th class="text-center">Alumnos final</th>
                                <th class="text-center">Registros</th>
                                <th class="text-center">PROM 4 SEM</th>
                            </tr>
                        </thead>
                        <tbody>
                          <!--  -->
                          <?php
                            $totalCobranza = 0;
                            foreach($semanas as $numSemana => $semana): 
                                $valores = array();
                                
                                // Obtener la semana actual real usando la fecha de inicio y fin
                                $fecha_actual = date('Y-m-d');
                                $es_semana_actual = ($fecha_actual >= $semana['inicio'] && $fecha_actual <= $semana['fin']);
                                
                                if ($es_semana_actual) {
                                    // Para la semana actual, usar la función en tiempo real
                                    $sql = "SELECT obtener_reporte_semanal('{$semana['inicio']}', '{$semana['fin']}', {$id_pla}) as valores;";
                                    $result = mysqli_query($db, $sql);
                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        $valores = json_decode($row['valores'], true);
                                    }
                                } else {
                                    // Para semanas anteriores, consultar la tabla reporte_semanal
                                    $sql = "SELECT dat_rep_sem as valores 
                                          FROM reporte_semanal 
                                          WHERE id_pla = {$id_pla} 
                                          AND ini_rep_sem = '{$semana['inicio']}'
                                          AND fin_rep_sem = '{$semana['fin']}'
                                          ORDER BY id_rep_sem DESC
                                          LIMIT 1";

                                    // echo $sql;
                                    $result = mysqli_query($db, $sql);
                                    if ($result && $row = mysqli_fetch_assoc($result)) {
                                        $valores = json_decode($row['valores'], true);
                                    }
                                }

                                // Extraer valores con validación
                                $cobranza = isset($valores['cobranza']) ? $valores['cobranza'] : 0;
                                $alumnosInicial = isset($valores['alumnos_inicial']) ? $valores['alumnos_inicial'] : 0;
                                $reingresos = isset($valores['reingresos']) ? $valores['reingresos'] : 0;
                                $inicios = isset($valores['inicios']) ? $valores['inicios'] : 0;
                                $bajas = isset($valores['bajas']) ? $valores['bajas'] : 0;
                                $desercion = isset($valores['deserciones']) ? $valores['deserciones'] : 0;
                                $finCursos = isset($valores['fin_cursos']) ? $valores['fin_cursos'] : 0;
                                $graduados = isset($valores['graduados']) ? $valores['graduados'] : 0;
                                $alumnosFinal = isset($valores['alumnos_final']) ? $valores['alumnos_final'] : 0;
                                $registros = isset($valores['registros']) ? $valores['registros'] : 0;
                                $promedio = isset($valores['promedio']) ? $valores['promedio'] : 0;
                                
                                $totalCobranza += $cobranza;
                            ?>
                                <tr <?php echo ($es_semana_actual) ? 'class="table-active"' : ''; ?>>
                                    <td class="text-center" title="<?php echo $semana['inicio_formato'] . ' al ' . $semana['fin_formato']; ?>">
                                        <?php echo $numSemana; ?> -
                                        <small style="display: '';"><?php echo $semana['inicio_formato'] . ' al ' . $semana['fin_formato']; ?></small>
                                    </td>
                                    <td class="text-center">
                                      <a target="_blank" href="<?php echo obtenerLigaCobranza($id_pla, $semana['inicio'], $semana['fin'], null, 'Colegiatura'); ?>" class="custom-link">
                                          $ <?php echo number_format($cobranza, 2); ?>
                                      </a>
                                    </td>
                                    <td class="text-center"><?php echo $alumnosInicial; ?></td>
                                    <td class="text-center"><?php echo $reingresos; ?></td>
                                    <td class="text-center"><?php echo $inicios; ?></td>
                                    <td class="text-center"><?php echo $bajas; ?></td>
                                    <td class="text-center"><?php echo $desercion; ?></td>
                                    <td class="text-center"><?php echo $finCursos; ?></td>
                                    <td class="text-center"><?php echo $graduados; ?></td>
                                    <td class="text-center"><?php echo $alumnosFinal; ?></td>
                                    <td class="text-center"><?php echo $registros; ?></td>
                                    <td class="text-center bg-success text-white">
                                        $ <?php echo number_format($promedio, 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <tr class="table-primary">
                              <td class="text-center">TOTAL</td>
                              <td class="text-center">
                                  <?php 
                                      // Obtener keys del array
                                      $keys = array_keys($semanas);
                                      // Primer y último elemento
                                      $inicio_mes = $semanas[reset($keys)]['inicio'];
                                      $fin_mes = $semanas[end($keys)]['fin'];
                                      //echo "Inicio: " . $inicio_mes . " Fin: " . $fin_mes;
                                  ?>
                                  <a target="_blank" href="<?php 
                                      echo obtenerLigaCobranza($id_pla, $inicio_mes, $fin_mes, null, 'Colegiatura'); 
                                  ?>" class="custom-link">
                                      $ <?php echo number_format($totalCobranza, 2); ?>
                                  </a>
                              </td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center">-</td>
                              <td class="text-center bg-success text-white">$ -</td>
                            </tr>
                          <!-- F -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<!-- DATATABLE -->
<script>
  $('#tabla_reporte_semanal').DataTable({
      paging: false, // Desactiva la paginación
      searching: false, // Desactiva el buscador
      ordering: false, // Desactiva la ordenación
      // dom: 'Bfrtip',
    dom: 'Bfrtip',
      buttons: [
          {
              extend: 'excelHtml5',
              title: 'REPORTE SEMANAL',
              className: 'btn-sm btn-success'
          },
      ],
      language: {
          search: 'Buscar' // Cambia el texto del buscador
      },
      info: false // Esto desactiva la información del pie de la tabla

  });
</script>
<!-- F DATATABLE -->