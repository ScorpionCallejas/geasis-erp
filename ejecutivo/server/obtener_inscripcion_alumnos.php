<?php  
  	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

  	$alumnos = $_POST['alumnos'];
  
	$sqlRama = "
		SELECT *
		FROM rama
		INNER JOIN alu_ram ON alu_ram.id_ram3 = rama.id_ram
		WHERE id_alu_ram = '$alumnos[0]'
	";

  	$resultadoRama = mysqli_query($db, $sqlRama);

	if ($resultadoRama) {
		$filaRama = mysqli_fetch_assoc($resultadoRama);
		$mod_ram = $filaRama['mod_ram'];
		$id_ram = $filaRama['id_ram'];
	}else{
		echo $sqlRama;
	}
?>

<!-- 🎨 ESTILOS MINIMALISTAS PARA INSCRIPCIÓN -->
<style>
    /* ==========================================
       BARRA DE PROGRESO - ESTILO COMPACTO
       ========================================== */
    #barra_inscripcion {
        height: 16px !important;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    #barra_estado {
        height: 16px !important;
        font-size: 9px;
        font-weight: 600;
        line-height: 16px;
        transition: width 0.4s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* ==========================================
       HEADER RAMA - MINIMALISTA
       ========================================== */
    .header-rama {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 12px;
    }

    /* ==========================================
       CONTADOR ALUMNOS - BADGE STYLE
       ========================================== */
    .contador-alumnos {
        font-size: 10px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 8px;
        display: block;
    }

    #alumnosSeleccionados {
        background: #007bff;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-weight: 700;
        margin-right: 4px;
    }

    /* ==========================================
       BADGES ALUMNOS - COMPACTO Y LIMPIO
       ========================================== */
    .seleccionAlumnoFinal {
        font-size: 9px !important;
        padding: 3px 8px !important;
        margin: 2px 3px !important;
        background-color: #f8f9fa !important;
        color: #495057 !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 3px !important;
        display: inline-block;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        transition: all 0.15s ease;
        cursor: default;
    }

    .seleccionAlumnoFinal:hover {
        background-color: #e9ecef !important;
        border-color: #adb5bd !important;
        transform: translateY(-1px);
    }

    /* ==========================================
       TIPO DE INSCRIPCIÓN - RADIO BUTTONS
       ========================================== */
    .tipo-inscripcion-container {
        background-color: #f8f9fa;
        padding: 10px 16px;
        border-radius: 4px;
        border: 1px solid #e9ecef;
        margin: 12px 0;
    }

    .tipo-inscripcion-container .form-check {
        margin-bottom: 0;
    }

    .tipo-inscripcion-container .form-check-input {
        width: 16px;
        height: 16px;
        margin-top: 0;
        accent-color: #007bff;
        cursor: pointer;
    }

    .tipo-inscripcion-container .form-check-label {
        font-size: 10px;
        line-height: 1.4;
        cursor: pointer;
        user-select: none;
        margin-left: 6px;
    }

    .tipo-inscripcion-container .form-check-label strong {
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.2px;
    }

    .tipo-inscripcion-container .grey-text {
        color: #6c757d !important;
        font-size: 8px !important;
        display: block;
        margin-top: 2px;
        font-style: italic;
    }

    .tipo-inscripcion-container .form-check-input:checked + .form-check-label strong {
        color: #007bff;
    }

    /* ==========================================
       STEPPER - ESTILO MINIMALISTA
       ========================================== */
    .stepper .step-title {
        font-size: 11px;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 3px;
        margin-bottom: 10px;
        border-left: 3px solid #dee2e6;
        transition: all 0.2s ease;
    }

    .stepper .step.active .step-title {
        background: #e7f3ff;
        border-left-color: #007bff;
        color: #007bff;
    }

    .stepper .step-new-content {
        padding: 12px 0;
    }

    /* ==========================================
       SELECTOR DE CICLO - ESTILO MINIMALISTA
       ========================================== */
    .label-ciclo {
        font-size: 9px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        display: block;
    }

    #selectorCiclo {
        font-size: 10px !important;
        padding: 6px 10px !important;
        border: 1px solid #ced4da !important;
        border-radius: 3px !important;
        background-color: white;
        color: #495057;
        font-weight: 500;
        transition: all 0.2s ease;
        height: 34px;
    }

    #selectorCiclo:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15) !important;
        outline: none;
    }

    /* ==========================================
       MENSAJES Y HINTS
       ========================================== */
    .mensaje-hint {
        font-size: 9px;
        color: #6c757d;
        font-style: italic;
        margin: 8px 0;
        display: block;
    }

    .mensaje-error {
        font-size: 10px;
        color: #dc3545;
        font-weight: 500;
        padding: 6px 10px;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 3px;
        display: inline-block;
    }

    .mensaje-error i {
        margin-right: 4px;
    }

    /* ==========================================
       TABLA HORARIO - ESTILO EXCEL COMPACTO
       ========================================== */
    #horarioAlumno,
    #horarioAlumnoFinal {
        font-size: 9px;
        margin-top: 12px;
        border-collapse: collapse;
        width: 100%;
    }

    #horarioAlumno thead,
    #horarioAlumnoFinal thead {
        background: #e9ecef;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    #horarioAlumno th,
    #horarioAlumnoFinal th {
        font-size: 9px !important;
        font-weight: 700;
        text-align: center;
        padding: 4px 6px !important;
        border: 1px solid #dee2e6;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        color: #495057;
        background-color: #e9ecef;
    }

    #horarioAlumno td,
    #horarioAlumnoFinal td {
        font-size: 9px;
        padding: 4px 6px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        color: #495057;
        text-align: center;
    }

    #horarioAlumno tbody tr:hover,
    #horarioAlumnoFinal tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    /* ==========================================
       BOTONES - ESTILO COMPACTO
       ========================================== */
    .btn-sm.btn-inscripcion {
        font-size: 10px !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 6px 16px !important;
        border-radius: 3px;
        transition: all 0.2s ease;
    }

    #btn_paso_2 {
        background: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
    }

    #btn_paso_2:hover {
        background: #0056b3 !important;
        border-color: #0056b3 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
    }

    #btn_finalizar {
        font-size: 11px !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 8px 20px !important;
        border-radius: 3px;
        transition: all 0.2s ease;
    }

    /* ==========================================
       CHECKBOX CONSERVAR AVANCE
       ========================================== */
    #contenedor_abc1 {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 3px;
        padding: 8px 12px;
        margin: 12px 0;
    }

    #contenedor_abc1 .form-check-label {
        font-size: 10px;
        font-weight: 600;
        color: #856404;
        cursor: pointer;
    }

    #contenedor_abc1 .form-check-input {
        width: 16px;
        height: 16px;
        accent-color: #ffc107;
        cursor: pointer;
    }

    /* ==========================================
       SEPARADORES
       ========================================== */
    hr {
        border: 0;
        border-top: 1px solid #e9ecef;
        margin: 16px 0;
    }

    hr.my-4 {
        margin: 20px 0;
    }

    /* ==========================================
       TÍTULOS DE SECCIÓN
       ========================================== */
    h4.grey-text,
    h5.grey-text {
        font-size: 12px;
        font-weight: 600;
        color: #495057 !important;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 8px;
    }

    /* ==========================================
       RESPONSIVE
       ========================================== */
    @media (max-width: 768px) {
        .tipo-inscripcion-container {
            padding: 8px 12px;
        }

        .tipo-inscripcion-container .form-check-label {
            font-size: 9px;
        }

        .tipo-inscripcion-container .grey-text {
            font-size: 7px !important;
        }

        .seleccionAlumnoFinal {
            font-size: 8px !important;
            padding: 2px 6px !important;
        }

        #horarioAlumno th,
        #horarioAlumnoFinal th,
        #horarioAlumno td,
        #horarioAlumnoFinal td {
            font-size: 8px;
            padding: 3px 4px;
        }

        .stepper .step-title {
            font-size: 10px;
            padding: 6px 10px;
        }
    }

    /* ==========================================
       ANIMACIONES SUTILES
       ========================================== */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .animated.fadeInDown {
        animation: fadeInDown 0.3s ease-out;
    }

    .animated.fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>

<!-- BARRA DE PROGRESO -->
<div id="barra_inscripcion" class="progress md-progress">
    <div class="progress-bar text-center white-text bg-info" role="progressbar" 
         style="width: 0%;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" 
         id="barra_estado">
    </div>
</div>

<!-- HEADER RAMA -->
<div class="row">
    <div class="col-md-12">
        <h5 class="header-rama">
            <?php echo $filaRama['nom_ram']." (".$filaRama['mod_ram'].")"; ?>
        </h5>
    </div>
</div>

<!-- ALUMNOS SELECCIONADOS -->
<div class="row" style="margin-top: 12px;">
    <div class="col-md-12">
        <span class="contador-alumnos">
            <span id="alumnosSeleccionados"><?php echo sizeof($alumnos); ?></span> 
            alumnos seleccionados
        </span>

        <?php  
			for( $i = 0; $i < sizeof( $alumnos ); $i++ ){
				$sqlAlumno = "
					SELECT *
					FROM vista_alumnos
					WHERE id_alu_ram = '$alumnos[$i]'
				";

				$resultadoAlumno = mysqli_query($db, $sqlAlumno);
				$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
		?>
        <div class="badge seleccionAlumnoFinal"
            id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>" 
            title="<?php echo $filaAlumno['nom_alu']; ?>"
            carga_alumno="<?php echo $filaAlumno['carga_alumno']; ?>" 
            id_ram="<?php echo $filaAlumno['id_ram3']; ?>">
            <?php echo comprimirTextoVariable($filaAlumno['nom_alu'], 15); ?>
        </div>
        <?php
			}
		?>
    </div>
</div>

<hr>

<!-- TIPO DE INSCRIPCIÓN -->
<div class="row">
    <div class="col-md-12">
        <div class="tipo-inscripcion-container">
            <div class="row" style="margin: 0;">
                <div class="col-md-6 col-sm-6" style="padding: 4px 8px;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_inscripcion"
                               id="nueva_carga" value="nueva" checked>
                        <label class="form-check-label" for="nueva_carga">
                            <strong>Nueva carga</strong>
                            <span class="grey-text">(Reemplaza materias actuales)</span>
                        </label>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6" style="padding: 4px 8px;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_inscripcion"
                               id="adicionar_carga" value="adicionar">
                        <label class="form-check-label" for="adicionar_carga">
                            <strong>Adicionar carga</strong>
                            <span class="grey-text">(Agrega sin eliminar actuales)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<!-- STEPPER -->
<ul class="stepper parallel" id="custom-validation">

    <!-- PASO 1: SELECCIÓN DE CICLO -->
    <li class="step active">
        <div class="step-title waves-effect waves-dark">
            Paso 1 - Selección de ciclo escolar
        </div>
        <div class="step-new-content">
            <div class="row">
                <div class="col-md-12">
                    <span class="label-ciclo">Selecciona un ciclo escolar</span>
                    
                    <?php
						$sqlCiclos = "SELECT * FROM ciclo WHERE id_ram1 = '$id_ram' ORDER BY id_cic ASC";
						$resultadoCiclos = mysqli_query($db, $sqlCiclos);
						$resultadoTotalCiclos = mysqli_query( $db, $sqlCiclos );
						$totalCiclos = mysqli_num_rows( $resultadoTotalCiclos );
					?>

                    <?php if ( $totalCiclos > 0 ) { ?>
                    
                    <select id="selectorCiclo" class="form-control">
                        <?php
							$validadorCiclo = true;
							while($filaCiclos = mysqli_fetch_assoc($resultadoCiclos)){
								$selected = $validadorCiclo ? 'selected' : '';
						?>
                        <option value="<?php echo $filaCiclos['id_cic']; ?>" <?php echo $selected; ?>>
                            <?php echo $filaCiclos["nom_cic"]; ?> 
                            (del <?php echo fechaFormateadaCompacta2($filaCiclos['ini_cic']); ?> 
                            al <?php echo fechaFormateadaCompacta2($filaCiclos['fin_cic']); ?>)
                        </option>
                        <?php
								$validadorCiclo = false;
							}
						?>
                    </select>

                    <?php } else { ?>
                    
                    <span class="mensaje-error">
                        <i class="fas fa-exclamation-triangle"></i> 
                        No hay ciclos escolares disponibles
                    </span>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </li>

    <hr>

    <!-- PASO 2: CARGA DE MATERIAS -->
    <li class="step">
        <div class="step-title waves-effect waves-dark">
            Paso 2 - Carga de materias
        </div>
        <div class="step-new-content">

            <!-- GRUPOS -->
            <div class="row">
                <div class="col-md-12" id="contenedor_grupos"></div>
            </div>

            <span class="mensaje-hint">
                Agrega las materias haciendo click en el botón de más (+)
            </span>

            <!-- MATERIAS -->
            <div class="row">
                <div class="col-md-12" id="contenedor_materias"></div>
            </div>

            <hr>

            <!-- PREVISUALIZACIÓN DE HORARIO -->
            <div class="row">
                <div class="col-md-12">
                    <h5 class="grey-text">Previsualización de horario</h5>
                    
                    <span class="mensaje-hint">
                        Previsualiza el horario de acuerdo a tus elecciones y presiona "Continuar" 
                        para seguir el proceso
                    </span>

                    <table class="table table-sm text-center table-hover animated fadeInDown" 
                           cellspacing="0" width="99%" id="horarioAlumno">
                        <?php if ( $mod_ram == 'Online' ) { ?>
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Profesor</th>
                                <th>Alumnos</th>
                                <th>Materia</th>
                            </tr>
                        </thead>
                        <?php } else if( $mod_ram == 'Presencial' ) { ?>
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Profesor</th>
                                <th>Alumnos</th>
                                <th>Materia</th>
                                <th>Salón</th>
                                <th>Lun</th>
                                <th>Mar</th>
                                <th>Mié</th>
                                <th>Jue</th>
                                <th>Vie</th>
                                <th>Sáb</th>
                                <th>Dom</th>
                            </tr>
                        </thead>
                        <?php } ?>
                        <tbody id="panzaHorarioAlumno"></tbody>
                    </table>
                </div>
            </div>

            <button class="waves-effect waves-dark btn btn-sm btn-info btn-rounded btn-inscripcion next-step"
                    data-feedback="validationFunction" id="btn_paso_2">
                Continuar
            </button>
        </div>
    </li>

    <hr>

    <!-- PASO 3: CONFIRMACIÓN -->
    <li class="step">
        <div class="step-title waves-effect waves-dark">
            Paso 3 - Confirmación y guardado
        </div>
        <div class="step-new-content">

            <h4 class="grey-text">Horario final</h4>

            <span class="mensaje-hint">
                Para confirmar, presiona el botón de finalizar
            </span>

            <hr>

            <table class="table table-sm text-center table-hover animated fadeIn" 
                   cellspacing="0" width="99%" id="horarioAlumnoFinal">
                <?php if ( $mod_ram == 'Online' ) { ?>
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Profesor</th>
                        <th>Alumnos</th>
                        <th>Materia</th>
                    </tr>
                </thead>
                <?php } else if( $mod_ram == 'Presencial' ) { ?>
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Profesor</th>
                        <th>Alumnos</th>
                        <th>Materia</th>
                        <th>Salón</th>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mié</th>
                        <th>Jue</th>
                        <th>Vie</th>
                        <th>Sáb</th>
                        <th>Dom</th>
                    </tr>
                </thead>
                <?php } ?>
                <tbody id="panzaHorarioAlumnoFinal"></tbody>
            </table>
            
            <!-- CHECKBOX CONSERVAR AVANCE (OCULTO POR DEFECTO) -->
            <div style="display: none;">
                <div class="form-check" id="contenedor_abc1">
                    <input type="checkbox" class="form-check-input" id="abc1">
                    <label class="form-check-label" for="abc1">
                        *Conservar avance académico
                    </label>
                </div>
            </div>

            <hr class="my-4 pb-2">
        </div>
    </li>
</ul>

<!-- ==========================================
     SCRIPTS
     ========================================== -->

<script>
$('#btn_finalizar').attr('disabled', 'disabled');
$('#btn_finalizar').html('Finalizar').removeClass('light-green accent-4').addClass('btn-info');
</script>

<script>
// VARIABLE GLOBAL PARA TIPO DE INSCRIPCIÓN
var tipo_inscripcion = $('input[name="tipo_inscripcion"]:checked').val();

// LISTENER PARA CAMBIOS EN TIPO DE INSCRIPCIÓN
$('input[name="tipo_inscripcion"]').on('change', function() {
    tipo_inscripcion = $(this).val();
    console.log('Tipo inscripción seleccionado: ' + tipo_inscripcion);
});

id_cic = $("#selectorCiclo option:selected").val();
console.log('primer id_cic: ' + id_cic);

obtener_grupos_ciclo(id_cic);

// SELECTOR CICLO
$("#selectorCiclo").on('change', function(event) {
    event.preventDefault();
    id_cic = $("#selectorCiclo option:selected").val();

    console.log('change id_cic: ' + id_cic);
    $('#panzaHorarioAlumno').html('');
    $("#panzaHorarioAlumnoFinal").html("");

    obtener_grupos_ciclo(id_cic);
});

function obtener_grupos_ciclo(id_cic) {
    $.ajax({
        url: 'server/obtener_grupos_ciclo_inscripcion.php',
        type: 'POST',
        data: { id_cic },
        success: function(respuesta) {
            $("#contenedor_grupos").html(respuesta);
        }
    });
}
</script>

<!-- VALIDACIÓN HORARIOS -->
<script>
$('#btn_paso_2').on('click', function(event) {
    event.preventDefault();

    if ($("#panzaHorarioAlumno .filasHorario").length == 0) {
        error.play();
        $('#btn_paso_2').removeClass('next-step');

        swal("¡No agregaste ninguna materia!", "Agrega al menos una para continuar", "error", {
            button: "Aceptar",
        });
        $('#btn_finalizar').attr('disabled', 'disabled');

    } else {
        $('#btn_paso_2').addClass('next-step');
        $("#panzaHorarioAlumnoFinal").html($("#panzaHorarioAlumno").children().clone());
        $("#panzaHorarioAlumnoFinal .removerHorario").remove();
        $('#btn_finalizar').removeAttr('disabled');
    }
});
</script>

<!-- PASO 3: CONSERVAR AVANCE -->
<script>
$("#abc1").prop("checked", false);
$('#contenedor_abc1').css('display', 'none');

if ($('.seleccionAlumnoFinal').length == 1 && 
    $('.seleccionAlumnoFinal').eq(0).attr('carga_alumno') > 0) {
    $("#abc1").prop("checked", false);
    $('#contenedor_abc1').css('display', '');
}
</script>

<!-- FUNCIONES DE VALIDACIÓN -->
<script>
function validationFunction() {
    setTimeout(function() {
        $('#custom-validation').nextStep();
    }, 500);
}

function someTrueFunction() {
    return true;
}
</script>

<!-- VERIFICACIÓN DE CONSISTENCIA DE PROGRAMA -->
<script>
verificarConsistenciaPrograma();

function verificarConsistenciaPrograma() {
    var idRamConsistente = true;
    var idRamReferencia = null;

    $('.seleccionAlumnoFinal').each(function() {
        var idRamActual = $(this).attr('id_ram');

        if (idRamReferencia === null) {
            idRamReferencia = idRamActual;
        } else if (idRamActual !== idRamReferencia) {
            idRamConsistente = false;
            return false;
        }
    });

    if (!idRamConsistente) {
        setTimeout(() => {
            toastr.error('Error: Alumnos de diferentes programas seleccionados :(');
            $('#modal_inscripcion').modal('hide');
        }, 2000);
    }
}
</script>