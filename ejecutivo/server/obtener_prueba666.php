<?php  
    // CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	  $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];

    $id_pla = $_POST['id_pla'];

    // echo 'PLANTEL: '.$id_pla;


    // echo 'fechas inicio y fin: '.$inicio.' '.$fin;

?>

<style>
	.bg-orange {
		background-color: #F8C851 !important; /* Color naranja */
		color: #fff !important;
	}
	.bg-light-green {
		background-color: #90EE90 !important; /* Verde tenue */
		color: #fff !important;
	}
	.bg-light-blue {
		background-color: #ADD8E6 !important; /* Azul tenue */
		color: #fff !important;
	}

	.text-red {
		color: red !important;
	}
	
</style>


<!-- PHP -->
  <?php 
    function obtenerSemanasPeriodo($fechaInicio, $fechaFin) {
      // Convertir strings a objetos DateTime
      $inicio = new DateTime($fechaInicio);
      $fin = new DateTime($fechaFin);
      
      // Obtener el primer día del mes
      $primerDiaMes = new DateTime($inicio->format('Y-m-01'));
      
      // Ajustar fecha inicial al lunes más cercano
      $inicioTemp = clone $inicio;
      while ($inicioTemp->format('N') != 1) {
        $inicioTemp->modify('-1 day');
      }
      
      $semanas = array();
      $numeroSemana = $inicioTemp->format('W');
      
      while ($inicioTemp->format('Y-m-d') <= $fin->format('Y-m-d')) {
        // Solo incluir la semana si su fecha de inicio está dentro del mes
        if ($inicioTemp->format('m') == $inicio->format('m')) {
          $finSemana = clone $inicioTemp;
          $finSemana->modify('+6 days');
          
          $semanas[$numeroSemana] = array(
            'inicio' => $inicioTemp->format('Y-m-d'),
            'fin' => $finSemana->format('Y-m-d'),
            'inicio_formato' => $inicioTemp->format('d/m/Y'),
            'fin_formato' => $finSemana->format('d/m/Y')
          );
        }
        
        $inicioTemp->modify('+7 days');
        $numeroSemana++;
      }
      
      return $semanas;
    }
    
    // 2. Obtenemos las semanas para el período
    $semanas = obtenerSemanasPeriodo($inicio, $fin);
    $semanaActual = date('W');
  ?>

  <?php
    // Arrays para almacenar los datos por semana
    $cobranzas_cole_efec = array();
    $gastos_cole_efec = array();
    $cobranzas_cole_cuenta = array();
    $gastos_cole_cuenta = array();
    $cobranzas_trm_efec = array();
    $gastos_trm_efec = array();
    $cobranzas_trm_cuenta = array();
    $gastos_trm_cuenta = array();

    // Inicializar arrays para cada semana
    foreach($semanas as $numSemana => $semana) {
      // Colegiaturas Efectivo
      $query = "SELECT obtener_abonado_colegiatura_efectivo_plantel($id_pla, '{$semana['inicio']}', '{$semana['fin']}') AS total";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $cobranzas_cole_efec[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
          WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
          AND id_pla13 = '$id_pla' 
          AND for_egr = 'colegiatura_efectivo'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $gastos_cole_efec[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      // Colegiaturas Cuenta
      $query = "SELECT obtener_abonado_colegiatura_deposito_plantel($id_pla, '{$semana['inicio']}', '{$semana['fin']}') AS total";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $cobranzas_cole_cuenta[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
          WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
          AND id_pla13 = '$id_pla' 
          AND for_egr = 'colegiatura_deposito'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $gastos_cole_cuenta[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      // Trámites Efectivo
      $query = "SELECT obtener_abonado_tramite_efectivo_plantel($id_pla, '{$semana['inicio']}', '{$semana['fin']}') AS total";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $cobranzas_trm_efec[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
          WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
          AND id_pla13 = '$id_pla' 
          AND for_egr = 'tramite_efectivo'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $gastos_trm_efec[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      // Trámites Cuenta
      $query = "SELECT obtener_abonado_tramite_deposito_plantel($id_pla, '{$semana['inicio']}', '{$semana['fin']}') AS total";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $cobranzas_trm_cuenta[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);

      $query = "SELECT COALESCE(SUM(mon_egr), 0) as total FROM egreso 
          WHERE (DATE(fec_egr) BETWEEN '{$semana['inicio']}' AND '{$semana['fin']}') 
          AND id_pla13 = '$id_pla' 
          AND for_egr = 'tramite_deposito'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $gastos_trm_cuenta[$numSemana] = empty($row['total']) ? 0 : floatval($row['total']);
    }
  ?>
<!-- F PHP -->


<!--  -->
<div class="row">
	<div class="col-12">
		<div class="row">
		  <div class="col-lg-4">

        <table class="table table-bordered table-sm m-0">
          <thead>
              <tr class="bg-primary text-white">
                  <th colspan="4" class="text-center p-0">COLE.EFEC</th>
              </tr>
              <tr>
                  <th class="p-0">SEM</th>
                  <th class="p-0">COBRANZA</th>
                  <th class="p-0">GASTOS</th>
                  <th class="p-0">SOBRANTE</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($semanas as $numSemana => $semana): 
                  $cobranza = isset($cobranzas_cole_efec[$numSemana]) ? $cobranzas_cole_efec[$numSemana] : 0;
                  $gasto = isset($gastos_cole_efec[$numSemana]) ? $gastos_cole_efec[$numSemana] : 0;
                  $sobrante = $cobranza - $gasto;
              ?>
              <tr>
                  <td class="text-center p-0"><?php echo $numSemana; ?></td>
                  <td class="text-end p-0">$ <?php echo number_format($cobranza, 2); ?></td>
                  <td class="text-end p-0">$ <?php echo number_format($gasto, 2); ?></td>
                  <td class="text-end p-0">$ <?php echo number_format($sobrante, 2); ?></td>
              </tr>
              <?php endforeach; ?>
              <tr class="table-primary">
                  <td class="text-center p-0">TOTAL</td>
                  <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_cole_efec), 2); ?></td>
                  <td class="text-end p-0">$ <?php echo number_format(array_sum($gastos_cole_efec), 2); ?></td>
                  <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_cole_efec) - array_sum($gastos_cole_efec), 2); ?></td>
              </tr>
          </tbody>
        </table>
      
        <br>

        <table class="table table-bordered table-sm m-0">
            <thead>
                <tr class="bg-primary text-white">
                    <th colspan="4" class="text-center p-0">TRM.EFEC</th>
                </tr>
                <tr>
                    <th class="p-0">SEM</th>
                    <th class="p-0">COBRANZA</th>
                    <th class="p-0">GASTOS</th>
                    <th class="p-0">SOBRANTE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($semanas as $numSemana => $semana): 
                    $cobranza = isset($cobranzas_trm_efec[$numSemana]) ? $cobranzas_trm_efec[$numSemana] : 0;
                    $gasto = isset($gastos_trm_efec[$numSemana]) ? $gastos_trm_efec[$numSemana] : 0;
                    $sobrante = $cobranza - $gasto;
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $numSemana; ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($cobranza, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($gasto, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($sobrante, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-primary">
                    <td class="text-center p-0">TOTAL</td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_trm_efec), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($gastos_trm_efec), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_trm_efec) - array_sum($gastos_trm_efec), 2); ?></td>
                </tr>
            </tbody>
        </table>

      </div>
			<div class="col-lg-4">
        <table class="table table-bordered table-sm m-0">
            <thead>
                <tr class="bg-primary text-white">
                    <th colspan="4" class="text-center p-0">COLE.CUENTA</th>
                </tr>
                <tr>
                    <th class="p-0">SEM</th>
                    <th class="p-0">COBRANZA</th>
                    <th class="p-0">GASTOS</th>
                    <th class="p-0">SOBRANTE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($semanas as $numSemana => $semana): 
                    $cobranza = isset($cobranzas_cole_cuenta[$numSemana]) ? $cobranzas_cole_cuenta[$numSemana] : 0;
                    $gasto = isset($gastos_cole_cuenta[$numSemana]) ? $gastos_cole_cuenta[$numSemana] : 0;
                    $sobrante = $cobranza - $gasto;
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $numSemana; ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($cobranza, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($gasto, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($sobrante, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-primary">
                    <td class="text-center p-0">TOTAL</td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_cole_cuenta), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($gastos_cole_cuenta), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_cole_cuenta) - array_sum($gastos_cole_cuenta), 2); ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="table table-bordered table-sm m-0">
            <thead>
                <tr class="bg-primary text-white">
                    <th colspan="4" class="text-center p-0">TRM.CUENTA</th>
                </tr>
                <tr>
                    <th class="p-0">SEM</th>
                    <th class="p-0">COBRANZA</th>
                    <th class="p-0">GASTOS</th>
                    <th class="p-0">SOBRANTE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($semanas as $numSemana => $semana): 
                    $cobranza = isset($cobranzas_trm_cuenta[$numSemana]) ? $cobranzas_trm_cuenta[$numSemana] : 0;
                    $gasto = isset($gastos_trm_cuenta[$numSemana]) ? $gastos_trm_cuenta[$numSemana] : 0;
                    $sobrante = $cobranza - $gasto;
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $numSemana; ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($cobranza, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($gasto, 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format($sobrante, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-primary">
                    <td class="text-center p-0">TOTAL</td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_trm_cuenta), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($gastos_trm_cuenta), 2); ?></td>
                    <td class="text-end p-0">$ <?php echo number_format(array_sum($cobranzas_trm_cuenta) - array_sum($gastos_trm_cuenta), 2); ?></td>
                </tr>
            </tbody>
        </table>
      </div>
			<div class="col-lg-4">
				<button class="btn btn-sm btn-primary w-100" id="btn-new-event"><i class="fa fa-plus me-1"></i> AGREGAR EVENTO</button>

				<div class="card" >
					<div class="card-body">
					<div class="d-flex justify-content-center">
						<div class="me-4">
							<span class="badge bg-orange text-white p-2">Administrativo</span>
						</div>
						<div class="me-4">
							<span class="badge bg-light-green text-white p-2">Comercial</span>
						</div>
						<div>
							<span class="badge bg-light-blue text-white p-2">Académico</span>
						</div>
					</div>


						<div id="calendar"></div>

					</div> <!-- end card body-->
				</div> <!-- end card -->

			</div> <!-- end col-->

			

		</div>  <!-- end row -->
		

		<!-- Add New Event MODAL -->
		<div class="modal fade" id="event-modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header py-3 px-4 border-bottom-0 d-block">
					<button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
					<h5 class="modal-title" id="modal-title">Evento</h5>
				</div>
				<div class="modal-body px-4 pb-4 pt-0">
					<form class="needs-validation" name="event-form" id="form-event" novalidate>
					<!-- Campo oculto para el ID del evento (usado para edición) -->
					<input type="hidden" name="id_eve" id="id_eve" value="" />
					<div class="row">
						<!-- Título del Evento -->
						<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Título del Evento</label>
							<input class="form-control" placeholder="EJEM: REUNIÓN DE SOCIOS..."
							type="text" name="eve_eve" id="eve_eve" required />
							<div class="invalid-feedback">Por favor, proporcione un título válido.</div>
						</div>
						</div>
						<!-- Inicia -->
						<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Inicia</label>
							<input class="form-control" type="date" name="ini_eve" id="ini_eve" value="<?php echo date('Y-m-d'); ?>" required/>
							<div class="invalid-feedback">Por favor, seleccione una fecha de inicio.</div>
						</div>
						</div>
						<!-- Termina -->
						<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Termina</label>
							<input class="form-control" type="date" name="fin_eve" id="fin_eve" value="<?php echo date('Y-m-d'); ?>" required />
							<div class="invalid-feedback">Por favor, seleccione una fecha de término.</div>
						</div>
						</div>
						<!-- Área -->
						<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Área</label>
							<select class="form-select" name="tip_eve" id="tip_eve" required>
								<option value="Administrativo">🟠 Administrativo</option>
								<option value="Comercial">🟢 Comercial</option>
								<option value="Académico">🔵 Académico</option>
							</select>
							<div class="invalid-feedback">Por favor, seleccione un área válida.</div>
							</div>

						</div>
					</div>
					<!-- Botones de acción -->
					<div class="row mt-2">
						<div class="col-md-6 col-4">
						<button type="button" class="btn btn-danger" id="btn-delete-event">Eliminar</button>
						</div>
						<div class="col-md-6 col-8 text-end">
						<button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-info" id="btn-save-event">Guardar</button>
						</div>
					</div>
					</form>
				</div>
				</div> <!-- end modal-content-->
			</div> <!-- end modal dialog-->
		</div>
		<!-- end modal-->

	</div>
	<!-- end col-12 -->
</div> 

<!-- end row -->
<!--  -->

<script>
  "use strict";

  <?php 
    // Supongamos que ya tienes una conexión a la base de datos en $db

    $sqlEventos = "
      SELECT *
      FROM evento
      WHERE id_pla = '$id_pla'
    ";
    $resultadoEventos = mysqli_query($db, $sqlEventos);

    // Obtener la fecha y hora actual
    $currentDate = date('Y-m-d H:i:s');

    // Array para almacenar los eventos
    echo "var dbEvents = [";
    $first = true;
    while($filaEventos = mysqli_fetch_assoc($resultadoEventos)) {
      if (!$first) echo ",";

      // Determinar el color según el tipo de evento (tip_eve)
      $className = "";
      switch($filaEventos['tip_eve']) {
        case 'Comercial':
          $className = "bg-orange"; // Clase CSS personalizada para naranja-amarillo
          break;
        case 'Administrativo':
          $className = "bg-light-green"; // Clase CSS personalizada para verde tenue
          break;
        case 'Académico':
          $className = "bg-light-blue"; // Clase CSS personalizada para azul tenue
          break;
      }

      // Determinar el estatus (est_eve) y asignar ícono
      $est_eve = $filaEventos['est_eve'];
      // Verificar si el evento está vencido
      if ($est_eve != 'Completado' && $filaEventos['fin_eve'] < $currentDate) {
        $est_eve = 'Vencido';
      }

      // // Determinar el ícono según el estatus
      $icon = "";
      // switch($est_eve) {
      //   case 'Pendiente':
      //     $icon = "⚠️"; // Ícono para Pendiente
      //     break;
      //   case 'Completado':
      //     $icon = "✅"; // Ícono para Completado
      //     break;
      //   case 'Vencido':
      //     $icon = "❌"; // Ícono para Vencido
      //     break;
      // }

      // Si el evento está vencido, agregar clase para texto rojo
      if ($est_eve == 'Vencido') {
        $className .= ' text-red'; // Agregar clase para texto rojo
      }

      echo "{";
      echo "id: '" . $filaEventos['id_eve'] . "',"; // Agregamos el ID del evento
      echo "title: '" . $icon . " " . addslashes($filaEventos['eve_eve']) . "',";
      echo "start: '" . $filaEventos['ini_eve'] . "',";
      echo "end: '" . $filaEventos['fin_eve'] . "',";
      echo "className: '" . $className . "',";
      echo "extendedProps: {";
      echo "  id_pla: '" . $filaEventos['id_pla'] . "',";
      echo "  id_eje: '" . $filaEventos['id_eje'] . "',";
      echo "  tipo: '" . $filaEventos['tip_eve'] . "',";
      echo "  estatus: '" . $est_eve . "'";
      echo "}";
      echo "}";
      $first = false;
    }
    echo "];";
  ?>

  (function($) {
    function CalendarApp() {
      this.$body = $("body");
      this.$modalEl = document.getElementById("event-modal");
      this.$modal = new bootstrap.Modal(this.$modalEl, { keyboard: false });
      this.$calendar = $("#calendar");
      this.$formEvent = $("#form-event");
      this.$btnNewEvent = $("#btn-new-event");
      this.$btnDeleteEvent = $("#btn-delete-event");
      this.$modalTitle = $("#modal-title");
      this.$calendarObj = null;
      this.$selectedEvent = null;
    }

    CalendarApp.prototype.onEventClick = function(info) {
      var event = info.event;
      this.$formEvent[0].reset();
      this.$formEvent.removeClass("was-validated");
      this.$selectedEvent = event;

      // Obtener el título sin el ícono y espacio inicial
      var titleWithoutIcon = event.title.replace(/^[^\s]+\s/, '');
      $("#id_eve").val(event.id);
      $("#eve_eve").val(titleWithoutIcon);
      $("#ini_eve").val(event.startStr.split('T')[0]);

      // Ajustar la fecha de fin restando un día para mostrarla correctamente en el formulario
      var finEveDate = event.end ? new Date(event.end) : new Date(event.start);
      finEveDate.setDate(finEveDate.getDate() - 1);
      var finEveAdjusted = finEveDate.toISOString().split('T')[0];

      $("#fin_eve").val(finEveAdjusted);
      $("#tip_eve").val(event.extendedProps.tipo);
      $("#est_eve").val(event.extendedProps.estatus);

      this.$btnDeleteEvent.show();
      this.$modalTitle.text("Editar Evento");
      this.$modal.show();
    };

    CalendarApp.prototype.onSelect = function(selectionInfo) {
      this.$formEvent[0].reset();
      this.$formEvent.removeClass("was-validated");
      this.$selectedEvent = null;

      // Rellenar el formulario con las fechas seleccionadas
      var startStr = selectionInfo.startStr || selectionInfo.dateStr || new Date().toISOString().split('T')[0];
      var endStr = selectionInfo.endStr || selectionInfo.dateStr || new Date().toISOString().split('T')[0];

      $("#ini_eve").val(startStr);
      $("#fin_eve").val(endStr);
      $("#est_eve").val('Pendiente'); // Valor por defecto

      this.$btnDeleteEvent.hide();
      this.$modalTitle.text("Agregar Evento");
      this.$modal.show();
    };

    CalendarApp.prototype.init = function() {
      var self = this;

      // Inicializar el calendario
      var calendarEl = this.$calendar[0];
      this.$calendarObj = new FullCalendar.Calendar(calendarEl, {
        locale: "es",
        themeSystem: "bootstrap",
        initialView: "dayGridMonth",
        buttonText: { today: "Hoy", month: "Mes", prev: "<", next: ">" },
        headerToolbar: { 
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth"
        },
        events: dbEvents,
        eventDataTransform: function(eventData) {
          // Determinar el ícono según el estatus del evento (est_eve)
          var estatus = eventData.extendedProps ? eventData.extendedProps.estatus : eventData.estatus;
          var icon = '';
          switch(estatus) {
            case 'Pendiente':
              icon = '⚠️';
              break;
            case 'Completado':
              icon = '✅';
              break;
            case 'Vencido':
              icon = '❌';
              break;
            default:
              icon = '';
          }
          // Concatenar el ícono al título del evento
          eventData.title = icon + ' ' + eventData.title;

          // No ajustar la fecha de fin aquí
          return eventData;
        },
        editable: true,
        selectable: true,
        dateClick: function(info) {
          self.onSelect(info);
        },
        eventClick: function(info) {
          self.onEventClick(info);
        },
        eventDrop: function(info) {
          self.updateEvent(info.event);
        },
        eventResize: function(info) {
          self.updateEvent(info.event);
        }
      });
      this.$calendarObj.render();

      // Agregamos el manejador de eventos para el botón "AGREGAR EVENTO"
      this.$btnNewEvent.on("click", function(e) {
        e.preventDefault();
        self.onSelect({
          dateStr: new Date().toISOString().split('T')[0]
        });
      });

      // Manejo del envío del formulario
      this.$formEvent.on("submit", function(e) {
        e.preventDefault();
        var form = self.$formEvent[0];
        if (form.checkValidity()) {
          var id_eve = $("#id_eve").val();
          var ini_eve = $("#ini_eve").val();
          var fin_eve = $("#fin_eve").val();

          // Ajustar la fecha de fin sumando un día antes de enviarla al servidor
          var finEveDate = new Date(fin_eve);
          finEveDate.setDate(finEveDate.getDate() + 1);
          var finEveAdjusted = finEveDate.toISOString().split('T')[0];

          var eventData = {
            id_eve: id_eve,
            eve_eve: $("#eve_eve").val(),
            ini_eve: ini_eve,
            fin_eve: finEveAdjusted,
            tip_eve: $("#tip_eve").val(),
            est_eve: $("#est_eve").val()
          };

          // Determinar si es agregar o editar
          var action = id_eve ? 'edit' : 'add';

          console.log("action:" + action);
          $.ajax({
            url: 'server/controlador_evento.php',
            type: 'POST',
            data: { action: action, data: eventData },
            success: function(response) {
              console.log("response add:" + response);
              var event = JSON.parse(response);

              // Determinar el ícono según el estatus del evento (est_eve)
              var icon = '';
              switch(event.estatus) {
                case 'Pendiente':
                  icon = '⚠️';
                  break;
                case 'Completado':
                  icon = '✅';
                  break;
                case 'Vencido':
                  icon = '❌';
                  break;
                default:
                  icon = '';
              }

              var eventTitle = icon + ' ' + event.title;

              // No ajustar la fecha de fin aquí
              // var endDate = new Date(event.end);
              // endDate.setDate(endDate.getDate() - 1);
              // event.end = endDate.toISOString().split('T')[0];

              if (action == 'add') {
                // Agregar evento al calendario
                self.$calendarObj.addEvent({
                  id: event.id_eve,
                  title: eventTitle,
                  start: event.start,
                  end: event.end,
                  allDay: true,
                  className: event.className,
                  extendedProps: {
                    tipo: event.tipo,
                    estatus: event.estatus
                  }
                });
              } else {
                // Actualizar evento existente
                self.$selectedEvent.title = eventTitle;
                self.$selectedEvent.start = event.start;
                self.$selectedEvent.end = event.end;
                self.$selectedEvent.allDay = true;
                self.$selectedEvent.className = event.className;
                self.$selectedEvent.extendedProps.tipo = event.tipo;
                self.$selectedEvent.extendedProps.estatus = event.estatus;

                // Actualizar el evento en el calendario
                self.$calendarObj.updateEvent(self.$selectedEvent);
              }
              self.$modal.hide();
            },
            error: function(xhr, status, error) {
              console.error('Error al guardar el evento: ' + error);
            }
          });
        } else {
          e.stopPropagation();
          form.classList.add("was-validated");
        }
      });

      // Manejo del botón eliminar
      this.$btnDeleteEvent.on("click", function(e) {
        if (self.$selectedEvent) {
          var id_eve = self.$selectedEvent.id;
          $.ajax({
            url: 'server/controlador_evento.php',
            type: 'POST',
            data: { action: 'delete', id_eve: id_eve },
            success: function(response) {
              // Remover el evento del calendario
              self.$selectedEvent.remove();
              self.$modal.hide();
            },
            error: function(xhr, status, error) {
              console.error('Error al eliminar el evento: ' + error);
            }
          });
        }
      });
    };

    CalendarApp.prototype.updateEvent = function(event) {
      // Obtener las fechas de inicio y fin
      var iniEve = event.startStr.split('T')[0];
      var finEve = event.endStr ? event.endStr.split('T')[0] : iniEve;

      // Ajustar la fecha de fin sumando un día antes de enviarla al servidor
      var finEveDate = new Date(finEve);
      finEveDate.setDate(finEveDate.getDate() + 1);
      var finEveAdjusted = finEveDate.toISOString().split('T')[0];

      var eventData = {
        id_eve: event.id,
        ini_eve: iniEve,
        fin_eve: finEveAdjusted
      };

      $.ajax({
        url: 'server/controlador_evento.php',
        type: 'POST',
        data: { action: 'updateDate', data: eventData },
        success: function(response) {
          // Opcional: mostrar mensaje de éxito
        },
        error: function(xhr, status, error) {
          console.error('Error al actualizar el evento: ' + error);
        }
      });
    };

    // Inicializar la aplicación del calendario
    $(document).ready(function() {
      var app = new CalendarApp();
      app.init();
    });

  })(jQuery);


</script>