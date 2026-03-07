<?php
	require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	
	if ( isset( $_POST['id_alu_ram'] ) ) {
	
		$id_alu_ram = $_POST['id_alu_ram'];
		// echo 'parcial';
	} else {
	
		$id_alu_ram = $_GET['id_alu_ram'];
		// echo 'completo';
	}
	$datosAlumno = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
	$id_ram = $datosAlumno['id_ram'];

	$fechaHoy = date('Y-m-d');

	//echo $fechaHoy;
?>

<style>
.letraGrande {
    font-size: 14px;
}
</style>

<hr>

<!-- CARD PRINCIPAL -->
<div class="row">
    <!-- FOTO -->
    <div class="col-md-3 text-center p-2 hoverable" style="position: relative;">

        <div class="p-2 pegadoSeleccionAlumno2" id_alu_ram="<?php echo $id_alu_ram; ?>">
            <!--  -->
            <?php
				if ( isset( $_POST['id_alu_ram'] ) ) {
					// $id_alu_ram = $_POST['id_alu_ram'];
					// echo 'parcial';
			?>
            <a class="btn-link text-primary" target="_blank"
                href="consulta_alumno.php?alumno=<?php echo $datosAlumno['nom_alu']; ?>&id_alu_ram=<?php echo $datosAlumno['id_alu_ram']; ?>"><i
                    class="fas fa-expand"></i>&nbsp; Vista completa</a>
            <hr>

            <?php
				}
			?>

            <br>

            <!-- VALIDACION -->
            <?php
            // Obtener datos de validación
            $sqlValidacion = "
                SELECT val_alu_ram, fec_alu_ram, eje_alu_ram 
                FROM alu_ram 
                WHERE id_alu_ram = '$id_alu_ram'
            ";
            $resultadoValidacion = mysqli_query($db, $sqlValidacion);
            $datosValidacion = mysqli_fetch_assoc($resultadoValidacion);

            $estaValidado = ($datosValidacion['val_alu_ram'] == 'Validado');
            ?>

            <?php if ($estaValidado): ?>
                <!-- MOSTRAR ESTATUS VALIDADO COMPACTO -->
                <div class="validado-compacto">
                    <span class="letraPequena text-success">
                        <i class="fas fa-check-circle"></i> 
                        Validado <?php echo fechaFormateadaCompacta2($datosValidacion['fec_alu_ram']); ?> 
                        por <?php echo comprimirTexto($datosValidacion['eje_alu_ram']); ?>
                    </span>
                </div>
            <?php else: ?>
                <!-- MOSTRAR BOTÓN VALIDAR -->
                <div class="validacion-boton">
                    <button class="btn btn-validar" id="btn_validar_alumno" onclick="validarAlumno(<?php echo $id_alu_ram; ?>)">
                        <i class="fas fa-check-circle"></i> VALIDAR
                    </button>
                </div>
            <?php endif; ?>

            <style>
            .validado-compacto {
                text-align: center;
                margin-bottom: 8px;
                padding: 6px 10px;
                background: #d4edda;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                font-size: 11px;
            }

            .validacion-boton {
                text-align: center;
                margin-bottom: 8px;
            }

            .btn-validar {
                background: #17a2b8;
                border: none;
                color: white;
                padding: 6px 16px;
                border-radius: 20px;
                font-weight: bold;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
            }

            .btn-validar:hover {
                background: #138496;
                color: white;
                box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
            }
            </style>
            <!-- F VALIDACION -->

            <span class="letraPequena grey-text">
                Alumno
            </span>
            <br>

            <a href="<?php echo obtenerValidacionFotoUsuarioServer( $datosAlumno['fot_alu'] ); ?>"
                data-lightbox="roadtrip">

                <img src="<?php echo obtenerValidacionFotoUsuarioServer( $datosAlumno['fot_alu'] ); ?>"
                    class="img-fluid " style="height: 105px; width: 100px; border-radius: 40px;"
                    title="Haz click para ampliar la foto de <?php echo $datosAlumno['nom_alu']; ?>">

            </a>

            <h5>
                <?php echo $datosAlumno['nom_alu']; ?>
            </h5>

            <h6><?php echo $datosAlumno['nom_pla']; ?></h6>
            <!-- <h6>CDE ORIGEN: <?php echo $datosAlumno['nom_pla_eje']; ?></h6>
            <h6>CONSULTOR: <?php echo $datosAlumno['nom_eje']; ?></h6> -->
            <div class="documento-links">
                <!-- Menú desplegable -->
                <div class="dropdown mt-sm-0 mt-2">
                    <a class="btn-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        DESCARGAR <i class="mdi mdi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="">
                        <a class="dropdown-item" href="solicitud_inscripcion.php?id_alu_ram=<?php echo $id_alu_ram; ?>" target="_blank">
                            SOLICITUD DE INSCRIPCIÓN
                        </a>
                        <a class="dropdown-item" href="carta_tramites.php?id_alu_ram=<?php echo $id_alu_ram; ?>" target="_blank">
                            CARTA DE TRÁMITES
                        </a>
                        <a class="dropdown-item" href="carta_expediente.php?id_alu_ram=<?php echo $id_alu_ram; ?>" target="_blank">
                            CARTA DE EXPEDIENTE
                        </a>
                    </div>
                </div>
                <!-- Fin del menú desplegable -->
            </div>

            <hr>
            <style>
            .btn-descarga {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                margin-top: 10px;
                transition: background-color 0.3s ease;
            }

            .btn-descarga:hover {
                background-color: #0056b3;
            }
            </style>

            <span class="letraPequena grey-text">
                Fecha de alta: <?php echo fechaFormateadaCompacta2($datosAlumno['ing_alu']); ?>
            </span>

            <br>

            <?php

				$sqlVista = "
					SELECT *
					FROM vista_alumnos
					WHERE id_alu_ram = '$id_alu_ram'
				";

				// echo $sqlVista;

				$resultadoVista = mysqli_query( $db, $sqlVista );

				$filaVista = mysqli_fetch_assoc( $resultadoVista );

				// echo $filaVista['estatus_general'];
				echo obtenerBadgeEstatusEjecutivo( $filaVista['estatus_general'] );

			?>
            <br>
            <span class="badge badge-dark font-weight-normal"
                title="<?php echo $datosAlumno['nom_ram']; ?>"><?php echo comprimirTextoVariable($datosAlumno['nom_ram'], 20); ?></span>

            <br>
            <span class="badge badge-info font-weight-normal" title="<?php echo $datosAlumno['nom_gen']; ?>">
                <?php echo comprimirTexto($datosAlumno['nom_gen']); ?>
            </span>

            <br>

            <!-- METADATOS -->
            <!-- BARRA PROGRESO -->
            <?php
				$diferenciaDias = obtenerDiferenciaFechas($fechaHoy, $datosAlumno['ini_gen']);

				$dias = obtenerDiferenciaFechas($datosAlumno['fin_gen'], $datosAlumno['ini_gen']);

				if ($dias == 0) {
					$estatusGeneracion = 'Fin curso';
					$porcentajeAvance = 100;
				} else {
					$estatusGeneracion = '';
					
					if ($dias > 0) {
						// ACTIVO
						$estatusGeneracion = 'En curso';
						$porcentajeAvance = floor(($diferenciaDias * 100) / $dias);
						
						if ($porcentajeAvance < 0) {
							$estatusGeneracion = 'Por comenzar';
							$semana = 'N/A';
							// PENDIENTE 
							$porcentajeAvance = 0;
						} else if ($porcentajeAvance > 100) {
							$estatusGeneracion = 'Fin curso';
							// FINALIZADO
							$porcentajeAvance = 100;
							$semana = 'Fin curso';
						} else {
							$semana = floor($diferenciaDias / 7);
						}
					} else {
						// PENDIENTE
						$estatusGeneracion = 'Por comenzar';
						$porcentajeAvance = 0;
						$semana = 'Por comenzar';
					}
				}

			// Ahora puedes usar $estatusGeneracion, $porcentajeAvance y $semana según sea necesario.
				//echo 'porcentaje: '.$porcentajeAvance;
			?>

            <strong class="letraMediana" title="Semana de trabajo en curso...">
                Semana <?php echo $semana; ?>
            </strong>

            <br>
            <span class="grey-text letraPequena">Inicio y fin grupal:
                <?php echo fechaFormateadaCompacta2( $datosAlumno['ini_gen'] ).' al '.fechaFormateadaCompacta2( $datosAlumno['fin_gen'] ); ?></span>



            <?php
		        	if ( $porcentajeAvance < 0 ) {
		        ?>


            <div class="progress md-progress"
                title="<?php echo $porcentajeAvance; ?>% - Progreso del alumno respecto al inicio de su curso y la fecha en curso...">
                <div class="progress-bar grey" role="progressbar" style="width: <?php echo $porcentajeAvance; ?>%"
                    aria-valuenow="<?php echo $porcentajeAvance; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <?php
		        	} else {
		        ?>
            <div class="progress md-progress"
                title="<?php echo $porcentajeAvance; ?>% - Progreso del alumno respecto al inicio de su curso y la fecha en curso..."">

		        			
						    <div class=" progress-bar bg-info" role="progressbar" style="width: <?php echo $porcentajeAvance; ?>%"
                aria-valuenow="<?php echo $porcentajeAvance; ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <?php
		        	}
		        ?>

        <!-- ESTATUS -->
        <?php  
		        	if ( $estatusGeneracion == 'Activo' ) {
		        ?>
        <span class="badge badge-info font-weight-normal"><?php echo $estatusGeneracion; ?></span>
        <?php
		        	} else {
		        ?>
        <span class="badge bg-light font-weight-normal"><?php echo $estatusGeneracion; ?></span>

        <?php
		        	}
		        ?>
        <!-- FIN ESTATUS -->







        <!-- FIN BARRA PROGRESO -->
        <!-- FIN METADATOS -->
        <br>


        <!-- --- -->
        <div class="border accesos-container">
            <span class="grey-text accesos-titulo">
                Accesos a plataforma
            </span>
            
            <div class="accesos-grid">
                <!-- CORREO -->
                <div class="acceso-row">
                    <div class="acceso-icon">
                        <i class="fas fa-envelope text-primary"></i>
                    </div>
                    <div class="acceso-content" id="correo-content">
                        <span class="acceso-text"><?php echo $datosAlumno['cor_alu']; ?></span>
                    </div>
                    <div class="acceso-copy">
                        <button class="btn-copy" onclick="copiarTexto('<?php echo $datosAlumno['cor_alu']; ?>', 'correo')" title="Copiar correo">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <!-- CONTRASEÑA -->
                <div class="acceso-row">
                    <div class="acceso-icon">
                        <i class="fas fa-key text-warning"></i>
                    </div>
                    <div class="acceso-content" id="password-content">
                        <span class="acceso-text acceso-password"><?php echo $datosAlumno['pas_alu']; ?></span>
                    </div>
                    <div class="acceso-copy">
                        <button class="btn-copy" onclick="copiarTexto('<?php echo $datosAlumno['pas_alu']; ?>', 'password')" title="Copiar contraseña">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- TELÉFONOS MEJORADOS -->
            <?php
            // Función para validar teléfonos
            function validarTelefono($telefono) {
                $telefono_limpio = preg_replace('/[^0-9+\-()]/', '', trim($telefono));
                $solo_numeros = preg_replace('/[^0-9]/', '', $telefono_limpio);
                return (strlen($solo_numeros) >= 10) ? $telefono_limpio : false;
            }

            $tel_principal = validarTelefono($datosAlumno['tel_alu']);
            $tel_secundario = !empty($datosAlumno['tel2_alu']) ? validarTelefono($datosAlumno['tel2_alu']) : false;
            $mostrar_tel2 = ($tel_secundario && $tel_secundario != $tel_principal);
            ?>

            <div class="telefono-container">
                <?php if ($tel_principal): ?>
                <div class="telefono-item">
                    <a href="tel:<?php echo $tel_principal; ?>" class="tel-link">
                        <i class="fas fa-phone text-primary"></i> <?php echo $tel_principal; ?>
                    </a>
                </div>
                
                <?php if ($mostrar_tel2): ?>
                <div class="telefono-separador">|</div>
                <div class="telefono-item">
                    <a href="tel:<?php echo $tel_secundario; ?>" class="tel-link">
                        <i class="fas fa-phone-alt text-secondary"></i> <?php echo $tel_secundario; ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <?php else: ?>
                <div class="telefono-item text-muted">
                    <i class="fas fa-phone-slash"></i> Sin teléfono válido
                </div>
                <?php endif; ?>

                <!-- APP VINCULADA -->
                <hr>

                <?php 
                    $sqlApp = "
                        SELECT * 
                        FROM alu_ram
                        INNER JOIN alumno_token ON alumno_token.alumno = alu_ram.id_alu1
                        WHERE id_alu_ram = $id_alu_ram;
                    ";
                    $validacionApp = obtener_datos_consulta( $db, $sqlApp )['total'];
                    if( $validacionApp > 0 ){
                ?>
                        <br>
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> APP - VINCULADA
                        </span>
                <?php
                    }
                ?>
                <!-- F APP VINCULADA -->
            </div>
        </div>

        <style>
        .accesos-container {
            padding: 15px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #dee2e6 !important;
            margin: 10px 0;
        }

        .accesos-titulo {
            font-weight: bold;
            text-transform: uppercase !important;
            letter-spacing: 0.5px;
            display: block;
            text-align: center;
            margin-bottom: 12px;
        }

        .accesos-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .acceso-row {
            display: grid;
            grid-template-columns: 30px 1fr 35px;
            align-items: center;
            gap: 8px;
            min-height: 32px;
        }

        .acceso-icon {
            text-align: center;
            font-size: 16px;
        }

        .acceso-content {
            min-height: 24px;
            display: flex;
            align-items: center;
        }

        .acceso-text {
            font-size: 14px;
            font-weight: bold;
            color: #333 !important;
            text-transform: none !important;
            word-break: break-word;
            width: 100%;
            user-select: all;
            cursor: text;
        }

        .acceso-password {
            background: #fff3cd;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ffeaa7;
            font-family: 'Courier New', monospace;
            display: inline-block;
        }

        .acceso-copy {
            text-align: center;
        }

        .btn-copy {
            background: #6c757d;
            border: none;
            color: white;
            padding: 6px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 12px;
        }

        .btn-copy:hover {
            background: #5a6268;
        }

        .btn-copy.copiado {
            background: #28a745;
        }

        /* TELÉFONOS */
        .telefono-container {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #dee2e6;
        }

        .telefono-item {
            font-weight: bold;
            font-size: 16px;
        }

        .tel-link {
            text-decoration: none;
            color: #333 !important;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .tel-link:hover {
            color: #007bff !important;
            text-decoration: underline;
        }

        .telefono-separador {
            color: #6c757d;
            font-weight: bold;
            font-size: 18px;
        }
        </style>

        <script>
        function copiarTexto(texto, tipo) {
            navigator.clipboard.writeText(texto).then(function() {
                // Cambiar el botón temporalmente
                const botones = document.querySelectorAll('.btn-copy');
                botones.forEach(btn => {
                    if (btn.getAttribute('onclick').includes(texto)) {
                        btn.classList.add('copiado');
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        
                        setTimeout(() => {
                            btn.classList.remove('copiado');
                            btn.innerHTML = '<i class="fas fa-copy"></i>';
                        }, 1500);
                    }
                });
                
                // Mostrar notificación
                if (typeof toastr !== 'undefined') {
                    toastr.success('¡' + (tipo === 'correo' ? 'Correo' : 'Contraseña') + ' copiado!');
                }
            }).catch(function(err) {
                console.error('Error al copiar: ', err);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error al copiar');
                }
            });
        }
        </script>
        <!-- F---- -->



        <hr>


        <a class="btn btn-info btn-rounded waves-effect btn-sm" style="width: 170px;" title="Edición de alumno"
            id="btn_edicion_alumno"> Editar </a>
        <hr>
        <a class="btn btn-danger btn-rounded waves-effect btn-sm" style="width: 170px;" title="Eliminación de alumno"
            id="btn_eliminacion_alumno"> Eliminar </a>
        <hr>
        <a class="btn btn-info btn-rounded waves-effect btn-sm" style="width: 170px;"
            title="Genera un pago para el alumno" id="btn_pago_alumnos2"> Crear pago </a>

        <hr>

        <?php  
            if ( $datosAlumno['est_alu'] == 'Activo' ) {
        ?>
                
                        <a class="btn btn-sm btn-info switchAlumno waves-effect btn-sm" id_alu_ram="<?php echo $id_alu_ram; ?>" id_alu="<?php echo $datosAlumno['id_alu']; ?>" estatus="<?php echo $datosAlumno['est_alu']; ?>" title="Activa/Desactiva la cuenta...">
                            <i class="fas fa-power-off"></i>
                        </a>
        <?php
            } else if ( $datosAlumno['est_alu'] == 'Inactivo' ) {
        ?>


                        <a class="btn btn-sm btn-secondary switchAlumno waves-effect btn-sm" id_alu_ram="<?php echo $id_alu_ram; ?>" id_alu="<?php echo $datosAlumno['id_alu']; ?>" estatus="<?php echo $datosAlumno['est_alu']; ?>" title="Activa/Desactiva la cuenta...">
                            <i class="fas fa-power-off"></i>
                        </a>

        <?php
            } else if ( $datosAlumno['est_alu'] == NULL ) {
        ?>

                <a class="btn btn-sm grey text-black switchAlumno waves-effect btn-sm" id_alu_ram="<?php echo $id_alu_ram; ?>" id_alu="<?php echo $datosAlumno['id_alu']; ?>" estatus="nulo" title="Activa la cuenta del alumno...">
                    <i class="fas fa-arrow-alt-circle-up"></i>
                </a>

        <?php
            }
        ?>

        <!-- <hr> -->

        <!-- <a class="btn btn-info btn-rounded waves-effect btn-sm"  style="width: 170px;" title="Inscribe al alumno" id="btn_inscripcion2"> Inscribir </a> -->
        <!--  -->
    </div>



</div>


<!-- FIN FOTO -->

<div class="col-md-9">
    <!--  -->


    <!-- NAVEGACION -->
    <div class="scrollNavegacionTelefonos">


        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_informacion_alumno();">
            Datos generales
        </span>


        <span
            class="badge bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_pagos_alumno();">
            Detalle de pagos
        </span>


        <span
            class="badge  bg-info font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Activo" onclick="obtener_pagos_vencidos_alumno();">
            Pagos pendientes
        </span>


        <span
            class="badge bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_observaciones_alumno();">
            Seguimiento
        </span>


        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_documentacion_alumno();">
            Documentación alumno
        </span>


        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_calificaciones_alumno();">
            Calificaciones finales
        </span>


        <!-- <span class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion" estatus="Inactivo" onclick="obtener_json_alumno();">
		      	<i class="fab fa-js"></i>
		      	JSON
			</span> -->


        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_edicion_calificaciones_alumno();">
            Editar calificaciones finales
        </span>


        <?php  
				if ( $filaVista['estatus_academico'] == 'Activo' ) {
			?>

        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_horario_alumno();">
            Consulta de horario
        </span>


        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_actividades_alumno();">
            Actividades
        </span>
        <?php
				}
			?>

        <span
            class="badge  bg-light font-weight-normal hoverable letraBadgesNavegacion text-black letraGrande waves-effect elementos_navegacion"
            estatus="Inactivo" onclick="obtener_bajas_reingresos_alumno();">
            Bajas y reingresos
        </span>

    </div>

    <!-- FIN NAVEGACION -->

    <hr>

    <!-- CONTENEDOR NAVEGACION -->
    <div class="row">
        <div class="col-md-12">
            <div id="contenedor_navegacion">

            </div>
        </div>
    </div>
    <!-- FIN CONTENEDOR NAVEGACION -->

    <!--  -->
</div>
</div>
<!-- FIN CARD PRINCIPAL -->

<script>
$('.elementos_navegacion').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    $('.elementos_navegacion').removeClass('bg-info').addClass('bg-light');
    $('.elementos_navegacion').removeAttr('estatus').attr('estatus', 'Inactivo');

    $(this).removeAttr('estatus').attr('estatus', 'Activo');

    if ($(this).hasClass('bg-light')) {

        $(this).removeClass('bg-light').addClass('bg-info');

    } else if ($(this).hasClass('bg-info')) {

        $(this).removeClass('bg-info').addClass('bg-light');

    }



});
</script>


<!-- AQUÍ PEGAS EL NUEVO SCRIPT: -->
<script>
// FUNCIÓN PARA VALIDAR ALUMNO
// FUNCIÓN PARA VALIDAR ALUMNO
// FUNCIÓN PARA VALIDAR ALUMNO
// FUNCIÓN PARA VALIDAR ALUMNO
function validarAlumno(id_alu_ram) {
    swal({
        title: "¿Validar alumno?",
        text: "¿Estás seguro de que quieres validar este alumno?",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn-secondary",
                closeModal: true,
            },
            confirm: {
                text: "Sí, validar",
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false
            }
        }
    }).then((willValidate) => {
        if (willValidate) {
            // Deshabilitar el botón mientras procesa
            $('#btn_validar_alumno').attr('disabled', 'disabled').html('<i class="fas fa-cog fa-spin"></i> Validando...');
            
            // Hacer la petición AJAX para validar
            $.ajax({
                url: 'server/editar_estatus_alumno.php',
                type: 'POST',
                data: {
                    id_alu_ram: id_alu_ram,
                    validar_alumno: 1
                },
                success: function(respuesta) {
                    console.log(respuesta);
                    
                    swal("¡Validado!", "El alumno ha sido validado correctamente", "success", {
                        button: "Aceptar",
                    }).then((value) => {
                        // Recargar la consulta del alumno
                        obtener_consulta_alumno1();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    
                    // Rehabilitar el botón
                    $('#btn_validar_alumno').removeAttr('disabled').html('<i class="fas fa-check-circle"></i> VALIDAR');
                    
                    swal("Error", "Hubo un problema al validar el alumno", "error");
                }
            });
        }
    });
}

// CSS para arreglar los colores de SweetAlert
$(document).ready(function() {
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .swal-button--confirm {
                background-color: #28a745 !important;
                border-color: #28a745 !important;
            }
            .swal-button--confirm:hover {
                background-color: #218838 !important;
                border-color: #1e7e34 !important;
            }
            .swal-button--cancel {
                background-color: #6c757d !important;
                border-color: #6c757d !important;
            }
            .swal-button--cancel:hover {
                background-color: #5a6268 !important;
                border-color: #545b62 !important;
            }
        `)
        .appendTo('head');
});
</script>


<script>
obtener_pagos_vencidos_alumno();

function obtener_pagos_vencidos_alumno() {
    // console.log('func');
    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_reporte_pagos_vencidos_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });

}

function obtener_pagos_alumno() {

    // console.log('func');
    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_reporte_cobranza_alumnos.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });

}


function obtener_consulta_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_consulta_general_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {
            // console.log( respuesta );
            $('#contenedor_visualizacion7').html(respuesta);
        }
    });
}


function obtener_informacion_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_consulta_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });

}



function obtener_json_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_json_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });

}


// DOCUMENTACION ALUMNO

function obtener_documentacion_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_documentacion_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });
}
// FIN DOCUMENTACION ALUMNO


// CALIFICACIONES ALUMNO
function obtener_calificaciones_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_calificaciones_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });
}
// FIN CALIFICACIONES ALUMNO


// EDITAR CALIFICACIONES ALUMNO

function obtener_edicion_calificaciones_alumno() {
    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_edicion_calificaciones_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });
}
// FIN EDITAR CALIFICACIONES ALUMNO


// CALIFICACIONES ALUMNO
function obtener_actividades_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_actividades_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });
}
// FIN CALIFICACIONES ALUMNO


// HORARIO ALUMNO
function obtener_horario_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_horario_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });
}
// FIN HORARIO ALUMNO

// ALTAS Y BAJAS
function obtener_bajas_reingresos_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_bajas_reingresos_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });
}
// FIN ALTAS Y BAJAS

// OBSERVACIONES ALUMNO
function obtener_observaciones_alumno() {

    var id_alu_ram = <?php echo $id_alu_ram; ?>;

    $.ajax({
        url: 'server/obtener_observaciones_alumno.php',
        type: 'POST',
        data: {
            id_alu_ram
        },
        success: function(respuesta) {

            $('#contenedor_navegacion').html(respuesta);
            // console.log('id_alu_ram enviado...');
        }
    });

}
// FIN OBSERVACIONES ALUMNO
</script>


<script>
$(".switchAlumno").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    var elemento = $(this);
    var id_alu = $(this).attr("id_alu");
    var estatus = $(this).attr("estatus");
    // alert( id_alu );

    if (estatus == 'nulo') {

        // VALIDACION PERMISOS
        // 
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
                            //console.log(respuesta);

                            if (respuesta == 'True') {

                                swal("Validado correctamente", "Continuar", "success", {
                                    button: "Aceptar",
                                }).
                                then((value) => {
                                    //

                                    $.ajax({
                                        url: 'server/editar_estatus_alumno.php',
                                        type: 'POST',
                                        data: {
                                            id_alu,
                                            estatus
                                        },

                                        success: function(respuesta) {

                                            console.log(respuesta);
                                            // if ( respuesta == 'Exito' ) {

                                            obtener_consulta_alumno();
                                            // }
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

        // FIN VALIDACION PERMISOS
        // 

    } else {

        $.ajax({
            url: 'server/editar_estatus_alumno.php',
            type: 'POST',
            data: {
                id_alu,
                estatus
            },

            success: function(respuesta) {

                console.log(respuesta);
                // if ( respuesta == 'Exito' ) {

                if (estatus == 'Activo') {

                    elemento.removeClass('btn-info').addClass('btn-secondary').removeAttr(
                        'estatus').attr('estatus', 'Inactivo');

                    toastr.error('La cuenta de <?php echo $nombre; ?> ha sido desactivada');

                    // obtenerAlumnosGeneraciones();
                    reloadTableGeneral();
                    reloadTable();

                } else if (estatus == 'Inactivo') {

                    elemento.removeClass('btn-secondary').addClass('btn-info').removeAttr(
                        'estatus').attr('estatus', 'Activo');
                    toastr.success('La cuenta de <?php echo $nombre; ?> ha sido activada');

                    // obtenerAlumnosGeneraciones();
                    reloadTableGeneral();
                    reloadTable();
                }
                // }
            }
        });
    }



});
</script>



<!-- CREAR PAGO -->
<script>
$("#btn_pago_alumnos2").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    console.log('pagos');
    // VARIABLES
    //ARREGLO DE ALUMNOS POR INSCRIBIR
    var alumnos = [];

    for (var i = 0, contador = 0; i < $(".pegadoSeleccionAlumno2").length; i++) {

        //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
        alumnos[contador] = $(".pegadoSeleccionAlumno2").eq(i).attr("id_alu_ram");
        contador++;

    }

    if (alumnos.length == 0) {
        swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {
            button: "Aceptar",
        });
    } else {

        $("#modal_pago_alumnos").modal('show');

        $.ajax({
            url: 'server/obtener_pago_alumnos.php',
            type: 'POST',
            data: {
                alumnos
            },
            success: function(respuesta) {
                $("#contenedor_pago_alumnos").html(respuesta);
            }
        });

    }
});
</script>
<!-- FIN CREAR PAGO -->


<!-- INSCRIPCION -->
<script>
// INSCRIPCION
$("#btn_inscripcion2").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    // VARIABLES
    //ARREGLO DE ALUMNOS POR INSCRIBIR
    var alumnosInscripcion = [];
    var alumnosValidador = [];
    var alumnosValidadorAdeudo = [];
    var alumnosDeudores = [];
    var alumnosNombresInscripcion = [];


    for (var i = 0, contador = 0, contador2 = 0; i < $(".pegadoSeleccionAlumno2").length; i++) {


        //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
        alumnosInscripcion[contador] = $(".pegadoSeleccionAlumno2").eq(i).attr("id_alu_ram");
        alumnosValidador[contador] = $(".pegadoSeleccionAlumno2").eq(i).attr("id_ram");
        alumnosValidadorAdeudo[contador] = $(".pegadoSeleccionAlumno2").eq(i).attr("estatus_pago");
        alumnosNombresInscripcion[contador] = $(".pegadoSeleccionAlumno2").eq(i).attr("nombre_alumno");

        contador++;

    }





    if (alumnosInscripcion.length == 0) {
        swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {
            button: "Aceptar",
        });
    } else {

        var validador = true;
        for (var i = 0; i < alumnosValidador.length; i++) {
            for (var j = 0; j < alumnosValidador.length; j++) {

                if (alumnosValidador[j] != alumnosValidador[i]) {
                    // console.log( alumnosValidador[j] != alumnosValidador[i] );
                    validador = false;
                    break;
                    break;
                    break;
                }
            }
        }


        var validadorAdeudo = true;
        for (var i = 0, j = 0; i < alumnosValidadorAdeudo.length; i++) {

            if ((alumnosValidadorAdeudo[i] == 'Con adeudo') && (validadorAdeudo == true)) {
                // console.log( alumnosValidador[j] != alumnosValidador[i] );
                validadorAdeudo = false;
            }

            if (alumnosValidadorAdeudo[i] == 'Con adeudo') {
                alumnosDeudores[j] = alumnosNombresInscripcion[i];
                j++;
            }
        }

        if (validador == true) {


            if (validadorAdeudo == true) {

                var id_ram = <?php echo $id_ram; ?>;
                var validadorGlobal = 1;

                $("#modalInscripcion").modal('show');

                $("#panzaModalInscripcion").html(
                    '<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

                $.ajax({
                    url: 'server/obtener_inscripcion_alumnos.php',
                    type: 'POST',
                    data: {
                        alumnosInscripcion,
                        id_ram,
                        validadorGlobal
                    },
                    success: function(respuesta) {

                        $("#panzaModalInscripcion").html(respuesta);
                    }
                });

            } else if (validadorAdeudo == false) {

                error.play();
                for (var i = 0; i < alumnosDeudores.length; i++) {
                    toastr.warning(alumnosDeudores[i] + ' presenta adeudo, NO se puede inscribir');
                }
                swal("¡Error en selección de alumnos!",
                    "Para continuar, asegúrate de que los alumnos no adeuden pagos", "info", {
                        button: "Aceptar",
                    });
            }



        } else if (validador == false) {
            swal("¡Error en selección de alumnos!",
                "Para continuar, asegúrate de que los alumnos pertenezcan al mismo programa", "info", {
                    button: "Aceptar",
                });
        }


    }



});
</script>
<!-- FIN INSCRIPCION -->


<!-- EDICION -->
<script>
$('#btn_edicion_alumno').on('click', function(event) {
    event.preventDefault();

    //alert('jaja');
    var id_alu_ram = <?php echo $id_alu_ram; ?>;
    var obtener_alumno = 1;

    $.ajax({
        url: 'server/controlador_alumno.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id_alu_ram,
            obtener_alumno
        },
        success: function(data) {

            console.log('datos:' + data);
            $('#id_alu').val(data.id_alu); // Asigna el valor de id_alu al campo oculto
            $('#nom_alu').val(data.nom_alu);
            $('#app_alu').val(data.app_alu);
            $('#apm_alu').val(data.apm_alu);
            $('#tel_alu').val(data.tel_alu);
            $('#gen_alu').val(data.gen_alu);
            $('#nac_alu').val(data.nac_alu);
            $('#cur_alu').val(data.cur_alu);
            $('#tut_alu').val(data.tut_alu);
            $('#tel2_alu').val(data.tel2_alu);
            $('#ocu_alu').val(data.ocu_alu);
            $('#direccion').val(data.dir_alu);
            $('#cp_alu').val(data.cp_alu);
            $('#correo').val(data.cor_alu);
            $('#pas_alu').val(data.pas_alu);

            // setTimeout(() => {
                $('#mon_alu_ram2').val(data.mon_alu_ram);
            // }, 200);
            

            // alert( data.mon_alu_ram );

        }
    });
    $('#modal_edicion_alumno').modal('show');

});
</script>
<!-- FIN EDICION -->


<!-- ELIMINACION -->
<script>
$('#btn_eliminacion_alumno').on('click', function(event) {
    event.preventDefault();

    //alert('jaja');
    var id_alu_ram = <?php echo $id_alu_ram; ?>;
    var eliminar_alumno = 1;


    // VALIDACION
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
                        //console.log(respuesta);

                        if (respuesta == 'True') {

                            swal("Validado correctamente", "Continuar", "success", {
                                button: "Aceptar",
                            }).
                            then((value) => {
                                // CODIGO

                                $.ajax({
                                    url: 'server/controlador_alumno.php',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        id_alu_ram,
                                        eliminar_alumno
                                    },
                                    success: function(data) {
                                        window.location.href =
                                            "alumnos.php";
                                    }
                                });

                                // FIN CODIGO
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

    // FIN VALIDACION PERMISOS
    // 
    // FIN VALIDACION


});
</script>
<!-- FIN ELIMINACION -->