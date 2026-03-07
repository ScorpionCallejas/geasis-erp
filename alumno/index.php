<?php  

include('inc/header.php');

$id_enc = obtener_validacion_alumno_encuesta($id, $cadena, $plantel);

if ($id_enc > 0) {
    header('location: encuesta.php?id_enc='.$id_enc);
}

// ========================================
// 🔥 FUNCIÓN DE VALIDACIÓN DE PAGOS CRÍTICOS
// ========================================
function validar_pagos_alumno_criticos($id_alu_ram, $db) {
    
    // Obtener datos del alumno
    $sqlAlumno = "
        SELECT 
            g.id_gen,
            g.ini_gen, 
            g.fin_gen,
            ar.val_alu_ram,
            ar.mon_alu_ram,
            r.gra_ram,
            r.cic_ram,
            r.per_ram,
            r.nom_ram
        FROM alu_ram ar
        INNER JOIN generacion g ON g.id_gen = ar.id_gen1
        INNER JOIN rama r ON r.id_ram = ar.id_ram3
        WHERE ar.id_alu_ram = '$id_alu_ram'
    ";
    
    $resultAlumno = mysqli_query($db, $sqlAlumno);
    $filaAlumno = mysqli_fetch_assoc($resultAlumno);
    
    // 🔥 CRÍTICO: Solo aplica si val_alu_ram = 1
    if ($filaAlumno['val_alu_ram'] != 1) {
        return array(
            'bloqueado' => false,
            'motivo' => '',
            'detalles' => array(),
            'programa' => '',
            'periodo' => '',
            'meses_transcurridos' => 0,
            'pagos_registrados' => array()
        );
    }
    
    $idGeneracion = $filaAlumno['id_gen'];
    $fechaInicio = $filaAlumno['ini_gen'];
    $fechaActual = date('Y-m-d');
    $grado = $filaAlumno['gra_ram'];
    $montoMensual = $filaAlumno['mon_alu_ram'];
    $ciclos = $filaAlumno['cic_ram'];
    $periodo = $filaAlumno['per_ram'];
    $nombrePrograma = $filaAlumno['nom_ram'];
    
    $mesesTranscurridos = floor((strtotime($fechaActual) - strtotime($fechaInicio)) / (30 * 24 * 60 * 60));
    
    $detallesFaltantes = array();
    $pagosRegistrados = array();
    
    // ========================================
    // OBTENER PAGOS REGISTRADOS
    // ========================================
    $sqlPagosRegistrados = "
        SELECT tip_pag, COUNT(*) as cantidad
        FROM pago
        WHERE id_alu_ram10 = '$id_alu_ram'
        AND est_pag IN ('Pagado', 'Pendiente', 'Validado')
        GROUP BY tip_pag
    ";
    $resultPagosReg = mysqli_query($db, $sqlPagosRegistrados);
    while($filaPago = mysqli_fetch_assoc($resultPagosReg)) {
        $pagosRegistrados[$filaPago['tip_pag']] = $filaPago['cantidad'];
    }
    
    // ========================================
    // VALIDACIÓN 1: REINSCRIPCIONES
    // ========================================
    if (in_array($grado, array('Bachillerato', 'Licenciatura'))) {
        
        // Verificar catálogo
        $sqlCatalogo = "
            SELECT COUNT(*) as total_catalogo
            FROM grupo_pago
            WHERE id_gen15 = '$idGeneracion'
            AND tip_gru_pag = 'Pago'
            AND tip_pag_gru_pag = 'Reinscripción'
        ";
        
        $resultCatalogo = mysqli_query($db, $sqlCatalogo);
        $filaCatalogo = mysqli_fetch_assoc($resultCatalogo);
        $tieneCatalogo = ($filaCatalogo['total_catalogo'] > 0);
        
        if ($tieneCatalogo) {
            $reinscripcionesEsperadas = $filaCatalogo['total_catalogo'];
        } else {
            $mesesPorPeriodo = 0;
            switch($periodo) {
                case 'Semestral': $mesesPorPeriodo = 6; break;
                case 'Cuatrimestral': $mesesPorPeriodo = 4; break;
                case 'Trimestral': $mesesPorPeriodo = 3; break;
                default: $mesesPorPeriodo = 6;
            }
            
            $ciclosCompletados = floor($mesesTranscurridos / $mesesPorPeriodo);
            $reinscripcionesEsperadas = ($ciclos > 1) ? ($ciclos - 1) : 0;
            $reinscripcionesEsperadas = ($ciclosCompletados >= 1) ? min($reinscripcionesEsperadas, $ciclosCompletados) : 0;
        }
        
        if ($reinscripcionesEsperadas > 0) {
            $sqlReinsAlumno = "
                SELECT COUNT(*) as total_alumno
                FROM pago
                WHERE id_alu_ram10 = '$id_alu_ram'
                AND tip_pag = 'Reinscripción'
                AND est_pag IN ('Pagado', 'Pendiente')
            ";
            
            $resultReinsAlumno = mysqli_query($db, $sqlReinsAlumno);
            $filaReinsAlumno = mysqli_fetch_assoc($resultReinsAlumno);
            $reinscripcionesAlumno = $filaReinsAlumno['total_alumno'];
            
            if ($reinscripcionesAlumno == 0) {
                $detallesFaltantes[] = array(
                    'tipo' => 'REINSCRIPCIONES',
                    'esperado' => $reinscripcionesEsperadas,
                    'registrado' => 0,
                    'faltante' => $reinscripcionesEsperadas,
                    'critico' => true
                );
            } elseif ($reinscripcionesAlumno < ($reinscripcionesEsperadas - 1)) {
                $detallesFaltantes[] = array(
                    'tipo' => 'REINSCRIPCIONES',
                    'esperado' => $reinscripcionesEsperadas,
                    'registrado' => $reinscripcionesAlumno,
                    'faltante' => ($reinscripcionesEsperadas - $reinscripcionesAlumno),
                    'critico' => true
                );
            }
        }
    }
    
    // ========================================
    // VALIDACIÓN 2: COLEGIATURAS
    // ========================================
    if ($mesesTranscurridos >= 2 && $montoMensual > 0) {
        
        $sqlColegiaturas = "
            SELECT 
                COUNT(*) as total_registrados,
                SUM(CASE WHEN est_pag IN ('Pagado', 'Validado') THEN 1 ELSE 0 END) as total_pagados
            FROM pago
            WHERE id_alu_ram10 = '$id_alu_ram'
            AND tip_pag = 'Colegiatura'
            AND ini_pag >= '$fechaInicio'
        ";
        
        $resultCol = mysqli_query($db, $sqlColegiaturas);
        $filaCol = mysqli_fetch_assoc($resultCol);
        
        $colegiaturasPagadas = $filaCol['total_pagados'];
        $colegiaturasRegistradas = $filaCol['total_registrados'];
        
        if ($mesesTranscurridos >= 4 && $colegiaturasPagadas == 0) {
            $detallesFaltantes[] = array(
                'tipo' => 'COLEGIATURAS',
                'esperado' => $mesesTranscurridos,
                'registrado' => 0,
                'faltante' => $mesesTranscurridos,
                'critico' => true
            );
        } elseif ($colegiaturasRegistradas > 0) {
            $porcentajePagado = ($colegiaturasPagadas / $colegiaturasRegistradas) * 100;
            
            if ($porcentajePagado < 30) {
                $detallesFaltantes[] = array(
                    'tipo' => 'COLEGIATURAS',
                    'esperado' => $colegiaturasRegistradas,
                    'registrado' => $colegiaturasPagadas,
                    'faltante' => ($colegiaturasRegistradas - $colegiaturasPagadas),
                    'critico' => true,
                    'porcentaje' => round($porcentajePagado, 0)
                );
            }
        }
    }
    
    // ========================================
    // VALIDACIÓN 3: PAGOS VENCIDOS
    // ========================================
    $fechaCritica = date('Y-m-d', strtotime('-2 months'));
    
    $sqlVencidos = "
        SELECT COUNT(*) as total_vencidos_criticos
        FROM pago
        WHERE id_alu_ram10 = '$id_alu_ram'
        AND est_pag = 'Pendiente'
        AND fin_pag < '$fechaCritica'
        AND fin_pag IS NOT NULL
    ";
    
    $resultVenc = mysqli_query($db, $sqlVencidos);
    $filaVenc = mysqli_fetch_assoc($resultVenc);
    
    if ($filaVenc['total_vencidos_criticos'] >= 3) {
        $detallesFaltantes[] = array(
            'tipo' => 'PAGOS VENCIDOS',
            'esperado' => 0,
            'registrado' => $filaVenc['total_vencidos_criticos'],
            'faltante' => $filaVenc['total_vencidos_criticos'],
            'critico' => true
        );
    }
    
    // Resultado final
    $bloqueado = !empty($detallesFaltantes);
    
    return array(
        'bloqueado' => $bloqueado,
        'motivo' => $bloqueado ? 'pagos_criticos_faltantes' : '',
        'detalles' => $detallesFaltantes,
        'programa' => $nombrePrograma,
        'periodo' => $periodo,
        'meses_transcurridos' => $mesesTranscurridos,
        'pagos_registrados' => $pagosRegistrados
    );
}

// ========================================
// 🔥 EJECUTAR VALIDACIÓN
// ========================================
$resultadoValidacion = validar_pagos_alumno_criticos($alumno_rama, $db);

$acceso_bloqueado_pagos = $resultadoValidacion['bloqueado'];
$motivo_bloqueo_pagos = $resultadoValidacion['motivo'];
$detalles_pagos_faltantes = $resultadoValidacion['detalles'];
$pagos_registrados = $resultadoValidacion['pagos_registrados'];

?>

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Inicio"><i class="fas fa-bookmark"></i> Inicio</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Estás aquí"><span class="text-white">Inicio</span></a>
		</div>
	</div>
</div>
<!-- FIN TITULO -->

<!-- Jumbotron -->
<div class="row">
    <!-- ========================================= -->
    <!-- 📄 VISOR PDF SOLICITUD DE INSCRIPCIÓN -->
    <!-- ========================================= -->
    <div class="col-md-4">
        <div class="card animated fadeIn">
            <div class="card-body elegant-color white-text">
                <h5 class="card-title">
                    <i class="fas fa-file-pdf"></i> Solicitud de Inscripción
                </h5>
                <hr class="hr-light">
                
                <!-- Canvas para renderizar el PDF -->
                <div id="pdf-viewer-container">
                    <div class="text-center" id="pdf-loading">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Cargando PDF...</p>
                    </div>
                    <canvas id="pdf-canvas"></canvas>
                </div>
                
                <!-- Controles -->
                <div class="mt-3 text-center">
                    <div class="btn-group btn-group-sm mb-2" role="group">
                        <button id="pdf-prev" class="btn btn-sm btn-light" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-light" disabled style="cursor: default;">
                            <span id="pdf-page-num">1</span> / <span id="pdf-page-count">--</span>
                        </button>
                        <button id="pdf-next" class="btn btn-sm btn-light" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <br>
                    <a href="https://plataforma.ahjende.com/solicitud_inscripcion.php?id_alu_ram=<?php echo $filaConsultaAlumno['id_alu_ram']; ?>" 
                       target="_blank" 
                       class="btn btn-info btn-sm waves-effect">
                        <i class="fas fa-external-link-alt"></i> Ver completo
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN VISOR PDF -->

    <div class="col-md-8">
    
    <!-- ========================================= -->
    <!-- 🎨 SECCIÓN CARRUSEL DE BANNERS -->
    <!-- ========================================= -->
    <div id="contenedor_carrusel_alumno" style="margin-bottom: 20px;">
        <!-- Aquí se cargará dinámicamente el carrusel -->
    </div>
    <!-- FIN SECCIÓN CARRUSEL -->
    
    <!-- Botón Tutorial -->
    <button type="button" class="btn btn-info btn-lg btn-block animated pulse infinite mb-4" data-toggle="modal" data-target="#tutorialModal" style="font-size: 1.2rem; font-weight: bold; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <i class="fas fa-play-circle mr-2"></i> ¿Cómo usar mi plataforma?
    </button>

    <!-- Modal Tutorial -->
    <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="tutorialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color white-text">
                    <h5 class="modal-title" id="tutorialModalLabel">Tutorial de la Plataforma</h5>
                    <button type="button" class="close white-text" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <video id="tutorialVideo" class="w-100" controls>
                        <source src="../img/video_explicacion.MP4" type="video/mp4">
                        Tu navegador no soporta el elemento de video.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <!-- Saludo personalizado -->
    <?php 
        if( $plantel == 2 ){
    ?>
            <div class="alert alert-info animated fadeIn" role="alert">
                <h4 class="alert-heading">Estimado líder, <?php echo $filaConsultaAlumno['nom_alu']; ?></h4>
                <hr>
                <p class="mb-0">
                    <?php  
                    if ( $fotoUsuario == NULL ) {
                    ?>
                        Recomendamos que coloques una foto tuya para mejorar tu experiencia en plataforma. 
                        <br>
                        También recordarte que puedes verificar si tus datos son correctos haciendo click <a href="perfil.php" class="text-info btn-link">aquí</a>
                    <?php    
                    } else {
                    ?>
                        Te recordamos que puedes verificar si tus datos son correctos haciendo click <a href="perfil.php" class="text-info btn-link">aquí</a>
                    <?php
                    }
                    ?>
                </p>
            </div>
    <?php
        } else {
    ?>
            <div class="alert alert-info animated fadeIn" role="alert">
                <h4 class="alert-heading">Bienvenido, <?php echo $filaConsultaAlumno['nom_alu']; ?></h4>
                <hr>
                <p class="mb-0">
                    <?php  
                    if ( $fotoUsuario == NULL ) {
                    ?>
                        Recomendamos que coloques una foto tuya para mejorar tu experiencia en plataforma. 
                        <br>
                        También recordarte que puedes verificar si tus datos son correctos haciendo click <a href="perfil.php" class="text-info btn-link">aquí</a>
                    <?php    
                    } else {
                    ?>
                        Te recordamos que puedes verificar si tus datos son correctos haciendo click <a href="perfil.php" class="text-info btn-link">aquí</a>
                    <?php
                    }
                    ?>
                </p>
            </div>
    <?php
        }
    ?>

</div>
</div>
<!-- Jumbotron -->

<!-- MODAL OBTENER ACTIVIDAD -->
<div class="modal fade text-left " id="modal_contrato">
  <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content white-text elegant-color">
        <div class="modal-header">
          	
          	<div class="row">
          		<div class="col-md-3">
          			<img src="../uploads/<?php echo $fotoPlantel; ?>" style="width: 40px; height: 40px;">
          		</div>

          		<div class="col-md-9 p-2">
          			<span class="letraGrande">
          				Bienvenido a ENDE
			        </span>
          		</div>
          	</div>
          	

          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            	<span aria-hidden="true">&times;</span>
          	</button>
        </div>
        
        <div class="modal-body dark lighten-2">
        	<div class="row">
        		<div class="col-md-12 text-center">
        			<h4>
		        		Bienvenido a <?php echo $nombrePlantel; ?>
		        	</h4>

		        	<h6>
		        		<?php echo $esloganPlantel; ?>
		        	</h6>
        		</div>
        	</div>
       		
			<div class="row">
				<div class="col-md-12 text-center">
					<?php if ($plantel == 3): ?>
						<span>
							🏆 Bienvenid@ a  Escuela de Negocios y Desarrollo Empresarial convenio con Corporativo de Universidades de competencias Educativas Profesionales 🕋
							<br>
							🎯 Queremos agradecerte por tu decisión de contemplarnos para poder terminar tu nivel medio superior. 
							<br>
							⏳Durante este proceso estaremos al pendiente de tu avance académico para que termines en tiempo y forma.
						</span>
						<br><br>
						<video controls class="w-100" style="max-height: 400px;">
							<source src="../img/video_ecatepec.mp4" type="video/mp4">
							Tu navegador no soporta el elemento de video.
						</video>
					<?php elseif ($plantel == 6): ?>
						<video controls class="w-100" style="max-height: 400px;">
							<source src="../img/video_cuautitlan.mp4" type="video/mp4">
							Tu navegador no soporta el elemento de video.
						</video>
					<?php else: ?>
						<span>
							SOMOS AHJ-ENDE 😉 Nos renovamos, crecemos constantemente para llevar tu potencial a las nubes, en nuestros nuevos Centros de Desarrollo Empresarial, podrás darle rienda suelta a tu potencial, te guiaremos en el proceso para que alcances tus objetivos. 🚀 Centros de Desarrollo Empresarial AHJ-ENDE
						</span>
						<br><br>
						<iframe src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fanthjasso%2Fvideos%2F304342234516689%2F&show_text=false&width=560" width="560" height="314" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
					<?php endif; ?>
				</div>
			</div>

        </div>

        <div class="modal-footer d-flex justify-content-center">
	      	
	      	<a class="btn btn-info white-text btn-rounded waves-effect" title="Aceptar y continuar..." data-dismiss="modal" id="btn_contrato">
	            Aceptar y continuar
	        </a>


	        <a class="btn btn-danger white-text btn-rounded waves-effect" title="Cancelar..." data-dismiss="modal" id="btn_cancelar">
	            No acepto
	        </a>

        
        </div>

      </div>

  </div>
</div>
<!-- FIN MODAL OBTENER ACTIVIDAD -->


<!-- ========================================= -->
<!-- 🔥🔥🔥 MODAL DE PAGOS FALTANTES - CON SECCIÓN QUÉ HACER -->
<!-- ========================================= -->
<?php if ($acceso_bloqueado_pagos) { ?>
<div class="modal fade" id="modalPagosFaltantes" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 900px; margin: 20px auto;">
        <div class="modal-content" style="border: 1px solid #cbd5e1; border-radius: 8px; font-family: Inter, system-ui, -apple-system, sans-serif;">
            
            <!-- Header Ultra Compacto -->
            <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 8px 12px; border-bottom: 1px solid #cbd5e1; border-radius: 8px 8px 0 0; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <div style="flex: 1;">
                    <h5 style="margin: 0; font-size: 14px; font-weight: 700; color: #ffffff; line-height: 1.3;">ACCESO SUSPENDIDO</h5>
                    <p style="margin: 0; font-size: 11px; color: #cbd5e1; line-height: 1.3;">Registro de Pagos Incompleto</p>
                </div>
            </div>
            
            <!-- Body Ultra Compacto -->
            <div style="background: #f8fafc; padding: 12px;">
                
                <!-- Mensaje Principal -->
                <div style="background: #fef2f2; border-left: 3px solid #ef4444; padding: 10px 12px; border-radius: 4px; border: 1px solid #fecaca; margin-bottom: 10px;">
                    <p style="margin: 0; font-size: 12px; color: #991b1b; line-height: 1.5; font-weight: 600;">
                        <strong><?php echo $nombreCompleto; ?></strong>, tu acceso está <strong style="color: #dc2626;">SUSPENDIDO</strong> porque tu área administrativa <strong>NO ha registrado correctamente</strong> los siguientes conceptos de pago. <strong style="color: #dc2626;">Sin este registro completo, NO podrás continuar</strong> y tu proceso de <strong>certificación se verá afectado</strong>.
                    </p>
                </div>

                <!-- Info del Programa -->
                <div style="background: #f1f5f9; padding: 10px 12px; border-radius: 4px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        <div>
                            <div style="font-size: 9px; color: #64748b; font-weight: 600; margin-bottom: 2px;">PROGRAMA</div>
                            <div style="font-size: 11px; color: #1e293b; font-weight: 700; line-height: 1.3;"><?php echo substr($resultadoValidacion['programa'], 0, 35); ?></div>
                        </div>
                        <div>
                            <div style="font-size: 9px; color: #64748b; font-weight: 600; margin-bottom: 2px;">PERIODICIDAD</div>
                            <div style="font-size: 11px; color: #1e293b; font-weight: 700;"><?php echo $resultadoValidacion['periodo']; ?></div>
                        </div>
                        <div>
                            <div style="font-size: 9px; color: #64748b; font-weight: 600; margin-bottom: 2px;">MESES CURSADOS</div>
                            <div style="font-size: 11px; color: #1e293b; font-weight: 700;"><?php echo $resultadoValidacion['meses_transcurridos']; ?> meses</div>
                        </div>
                    </div>
                </div>

                <!-- ¿QUÉ DEBE HACER TU ÁREA ADMINISTRATIVA? -->
                <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 10px 12px; border-radius: 4px; margin-bottom: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>
                        <div style="flex: 1;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 700; color: #92400e;">¿QUÉ DEBE HACER TU ÁREA ADMINISTRATIVA?</p>
                            <p style="margin: 0; font-size: 11px; color: #78350f; line-height: 1.5;">
                                Para que puedas continuar, tu área administrativa <strong>DEBE CAPTURAR EN EL SISTEMA</strong> los siguientes conceptos faltantes:
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tabla Compacta - CONCEPTOS FALTANTES -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 10px;">
                    <div style="background: #fef2f2; padding: 6px 10px; border-bottom: 1px solid #fecaca;">
                        <span style="font-size: 11px; font-weight: 700; color: #991b1b;">❌ CONCEPTOS QUE FALTAN (DEBEN CAPTURARSE)</span>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="padding: 6px 8px; text-align: left; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px;">CONCEPTO</th>
                                <th style="padding: 6px 8px; text-align: center; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px;">ESPERADO</th>
                                <th style="padding: 6px 8px; text-align: center; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px;">REGISTRADO</th>
                                <th style="padding: 6px 8px; text-align: center; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px;">FALTANTE</th>
                                <th style="padding: 6px 8px; text-align: center; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px;">URGENCIA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles_pagos_faltantes as $index => $detalle) { 
                                $bgColor = ($index % 2 == 0) ? '#ffffff' : '#f8fafc';
                            ?>
                            <tr style="background: <?php echo $bgColor; ?>;">
                                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #1e293b; font-size: 11px;">
                                    <?php echo $detalle['tipo']; ?>
                                    <?php if (isset($detalle['porcentaje'])) { ?>
                                        <div style="font-size: 9px; color: #ef4444; font-weight: 600; margin-top: 2px;">Solo <?php echo $detalle['porcentaje']; ?>% pagado</div>
                                    <?php } ?>
                                </td>
                                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center; font-weight: 700; color: #10b981; font-size: 12px;">
                                    <?php echo $detalle['esperado']; ?>
                                </td>
                                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center; font-weight: 700; color: #3b82f6; font-size: 12px;">
                                    <?php echo $detalle['registrado']; ?>
                                </td>
                                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center; font-weight: 700; color: #ef4444; font-size: 13px;">
                                    <?php echo $detalle['faltante']; ?>
                                </td>
                                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center;">
                                    <?php if ($detalle['critico']) { ?>
                                        <span style="background: #fef2f2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: 700; border: 1px solid #fecaca;">
                                            🔴 CRÍTICO
                                        </span>
                                    <?php } else { ?>
                                        <span style="background: #fffbeb; color: #92400e; padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: 700; border: 1px solid #fde68a;">
                                            ⚠️ PENDIENTE
                                        </span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- LO QUE SÍ TIENES REGISTRADO -->
                <?php if (!empty($pagos_registrados)) { ?>
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 4px; margin-bottom: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <div style="flex: 1;">
                            <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; color: #065f46;">✅ LO QUE SÍ TIENES REGISTRADO:</p>
                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                <?php foreach ($pagos_registrados as $tipo => $cantidad) { ?>
                                    <span style="font-size: 10px; color: #047857; background: #d1fae5; padding: 2px 8px; border-radius: 4px; border: 1px solid #a7f3d0;">
                                        <strong><?php echo ($tipo === 'Otros' ? 'Trámite' : $tipo); ?>:</strong> <?php echo $cantidad; ?>

                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Link al PDF de Historial -->
                <div style="background: #dbeafe; border: 1px solid #93c5fd; padding: 10px 12px; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" style="flex-shrink: 0;">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <div style="flex: 1;">
                        <p style="margin: 0; font-size: 11px; color: #1e40af; line-height: 1.4; font-weight: 600;">
                            📄 Ver historial completo de pagos (PDF detallado)
                        </p>
                        <p style="margin: 2px 0 0 0; font-size: 10px; color: #64748b;">
                            Descarga el PDF con todos los detalles para enviar a administración
                        </p>
                    </div>
                    <a href="historial_meses_pdf.php?id_alu_ram=<?php echo $alumno_rama; ?>" 
                       target="_blank" 
                       style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: 600; white-space: nowrap; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: all 0.2s;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Ver PDF
                    </a>
                </div>

                <!-- Instrucciones -->
                <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 10px 12px; border-radius: 4px; margin-bottom: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <div style="flex: 1;">
                            <p style="margin: 0 0 6px 0; font-size: 11px; font-weight: 700; color: #92400e;">🔔 ACCIONES INMEDIATAS:</p>
                            <ol style="margin: 0; padding-left: 16px; font-size: 11px; color: #78350f; line-height: 1.5;">
                                <li style="margin-bottom: 4px;">Toma un <strong>screenshot</strong> (captura de pantalla) de esta ventana</li>
                                <li style="margin-bottom: 4px;">Descarga el <strong>PDF del historial</strong> usando el botón de arriba</li>
                                <li style="margin-bottom: 4px;">Envía <strong>AMBOS</strong> al <strong>Director</strong> y <strong>Área Administrativa</strong> de tu plantel</li>
                                <li>Comunícate URGENTEMENTE al: <strong style="color: #dc2626;"><?php echo $telefonoPlantel; ?></strong></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Aviso Final -->
                <div style="background: #f0fdf4; border-left: 3px solid #10b981; padding: 10px 12px; border-radius: 4px; border: 1px solid #d1fae5;">
                    <p style="margin: 0; font-size: 11px; color: #065f46; line-height: 1.5;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <strong>Es fundamental que tus pagos aparezcan completos.</strong> Tu acceso será restablecido automáticamente una vez que tu área administrativa registre correctamente: <strong>COLEGIATURAS, REINSCRIPCIONES, TRÁMITES</strong> y demás movimientos en el sistema.
                    </p>
                </div>

            </div>
            
            <!-- Footer Ultra Compacto -->
            <div style="background: #f1f5f9; padding: 10px 12px; border-top: 1px solid #e2e8f0; border-radius: 0 0 8px 8px; text-align: right;">
                <button type="button" id="btn_cerrar_pagos" style="background: #64748b; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<!-- ========================================= -->
<!-- 🎨 ESTILOS DEL CARRUSEL Y PDF -->
<!-- ========================================= -->
<style>
/* ========== ESTILOS DEL VISOR PDF ========== */
#pdf-viewer-container {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

#pdf-canvas {
    width: 100%;
    height: auto;
    display: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#pdf-loading {
    color: #666;
}

/* ========== ESTILOS DEL CARRUSEL ========== */
.carrusel-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    margin-bottom: 20px;
    background: #000;
}

.carrusel-slides {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.carrusel-slide {
    min-width: 100%;
    position: relative;
    height: 400px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    cursor: pointer;
}

.carrusel-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85), transparent);
    padding: 30px 20px 20px;
    pointer-events: none;
}

.carrusel-title {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 6px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
    line-height: 1.3;
}

.carrusel-description {
    color: #e8e8e8;
    font-size: 14px;
    margin: 0 0 8px 0;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
    line-height: 1.4;
}

.carrusel-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
    pointer-events: all;
}

.carrusel-link-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}

.carrusel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 10;
    backdrop-filter: blur(4px);
}

.carrusel-nav:hover {
    background: rgba(0, 0, 0, 0.7);
    transform: translateY(-50%) scale(1.1);
}

.carrusel-nav:focus {
    outline: none;
}

.carrusel-nav-prev {
    left: 12px;
}

.carrusel-nav-next {
    right: 12px;
}

.carrusel-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 5;
}

.carrusel-dot {
    width: 8px;
    height: 8px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.carrusel-dot.active {
    width: 20px;
    background: #fff;
}

.carrusel-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    background: #f5f5f5;
    border-radius: 12px;
    color: #999;
    font-size: 14px;
}

.lightbox-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.95);
    z-index: 99999;
    padding: 20px;
    overflow-y: auto;
}

.lightbox-content {
    position: relative;
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 60px 20px 40px;
}

.lightbox-close {
    position: fixed;
    top: 20px;
    right: 20px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: none;
    color: #fff;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-close:hover {
    background: rgba(255,255,255,0.25);
    transform: rotate(90deg);
}

.lightbox-image-container {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 24px;
}

.lightbox-image {
    max-width: 100%;
    max-height: 70vh;
    border-radius: 12px;
    box-shadow: 0 12px 48px rgba(0,0,0,0.5);
    object-fit: contain;
}

.lightbox-info {
    width: 100%;
    max-width: 800px;
    text-align: center;
    color: #fff;
}

.lightbox-titulo {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 12px;
    line-height: 1.3;
}

.lightbox-descripcion {
    font-size: 16px;
    line-height: 1.6;
    color: #e0e0e0;
    margin-bottom: 20px;
    white-space: pre-wrap;
}

.lightbox-link-btn {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 12px 28px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.lightbox-link-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: #fff;
    text-decoration: none;
}

@keyframes fadeInCarrusel {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.carrusel-wrapper {
    animation: fadeInCarrusel 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .carrusel-slide {
        height: 280px;
    }
    
    .carrusel-title {
        font-size: 17px;
    }
    
    .carrusel-description {
        font-size: 13px;
    }
    
    .carrusel-nav {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .carrusel-nav-prev {
        left: 8px;
    }
    
    .carrusel-nav-next {
        right: 8px;
    }
    
    .carrusel-loading {
        height: 280px;
    }
    
    .lightbox-titulo {
        font-size: 22px;
    }
    
    .lightbox-descripcion {
        font-size: 14px;
    }
    
    .lightbox-image {
        max-height: 60vh;
    }
    
    #pdf-viewer-container {
        min-height: 350px;
    }
}

.btn-info {
    background-color: #33b5e5 !important;
    transition: all 0.3s ease;
}

.btn-info:hover {
    background-color: #0099cc !important;
    transform: translateY(-2px);
}

.modal-content {
    border-radius: 8px;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
}

.close:focus {
    outline: none;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.animated.pulse.infinite {
    animation: pulse 2s infinite;
}

#tutorialVideo {
    display: block;
    max-height: 80vh;
    object-fit: contain;
}

.alert {
    transition: all 0.3s ease;
}

.alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Hover del botón PDF */
a[href*="historial_meses_pdf.php"]:hover {
    background: #2563eb !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
}

/* Hover del botón cerrar */
#btn_cerrar_pagos:hover {
    background: #475569 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
}
</style>


<?php
	include('inc/footer.php');
?>

<!-- ========================================= -->
<!-- 📚 PDF.JS CDN -->
<!-- ========================================= -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<!-- ========================================= -->
<!-- 🚀 SCRIPT DEL VISOR PDF -->
<!-- ========================================= -->
<script>
$(document).ready(function() {
    
    // Configurar PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    var pdfDoc = null;
    var pageNum = 1;
    var pageRendering = false;
    var pageNumPending = null;
    var scale = 1.0;
    var canvas = document.getElementById('pdf-canvas');
    var ctx = canvas.getContext('2d');
    
    // URL del PDF
    var pdfUrl = 'https://plataforma.ahjende.com/solicitud_inscripcion.php?id_alu_ram=<?php echo $filaConsultaAlumno['id_alu_ram']; ?>';
    
    function renderPage(num) {
        pageRendering = true;
        
        pdfDoc.getPage(num).then(function(page) {
            var viewport = page.getViewport({scale: scale});
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            
            var renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            
            var renderTask = page.render(renderContext);
            
            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });
        
        document.getElementById('pdf-page-num').textContent = num;
    }
    
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }
    
    function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
        updateButtons();
    }
    
    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
        updateButtons();
    }
    
    function updateButtons() {
        $('#pdf-prev').prop('disabled', pageNum <= 1);
        $('#pdf-next').prop('disabled', pageNum >= pdfDoc.numPages);
    }
    
    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('pdf-page-count').textContent = pdfDoc.numPages;
        
        $('#pdf-loading').hide();
        $('#pdf-canvas').show();
        
        renderPage(pageNum);
        updateButtons();
        
    }).catch(function(error) {
        $('#pdf-loading').html(
            '<i class="fas fa-exclamation-triangle fa-2x text-danger"></i>' +
            '<p class="text-danger mt-2 small">Error al cargar el PDF</p>'
        );
    });
    
    $('#pdf-prev').on('click', onPrevPage);
    $('#pdf-next').on('click', onNextPage);
});
</script>

<!-- ========================================= -->
<!-- 🚀 SCRIPT DEL CARRUSEL -->
<!-- ========================================= -->
<script>
$(document).ready(function() {
    
    var currentIndex = 0;
    var totalSlides = 0;
    var autoplayInterval = null;
    var autoplayDelay = 5000;
    var carruselesData = [];
    
    function cargarCarruselAlumno() {
        $.ajax({
            url: 'server/controlador_carrusel.php',
            type: 'POST',
            data: { accion: 'obtener_carruseles_alumno' },
            dataType: 'json',
            beforeSend: function() {
                $('#contenedor_carrusel_alumno').html(
                    '<div class="carrusel-loading">' +
                    '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> Cargando banners...' +
                    '</div>'
                );
            },
            success: function(respuesta) {
                if (respuesta.success && respuesta.carruseles && respuesta.carruseles.length > 0) {
                    carruselesData = respuesta.carruseles;
                    renderizarCarrusel(respuesta.carruseles);
                } else {
                    $('#contenedor_carrusel_alumno').html('');
                }
            },
            error: function(xhr, status, error) {
                $('#contenedor_carrusel_alumno').html('');
            }
        });
    }
    
    function renderizarCarrusel(carruseles) {
        totalSlides = carruseles.length;
        var html = '<div class="carrusel-wrapper">';
        
        html += '<div class="carrusel-slides" id="carrusel_slides">';
        
        carruseles.forEach(function(banner, index) {
            var urlImagen = '../img/' + banner.img_car;
            var titulo = banner.tit_car || '';
            var descripcion = banner.des_car || '';
            var link = banner.url_car || '';
            
            html += '<div class="carrusel-slide" style="background-image: url(\'' + urlImagen + '\');" data-index="' + index + '" data-img-url="' + urlImagen + '">';
            
            if (titulo || descripcion || link) {
                html += '<div class="carrusel-overlay">';
                
                if (titulo) {
                    html += '<h3 class="carrusel-title">' + titulo + '</h3>';
                }
                
                if (descripcion) {
                    html += '<p class="carrusel-description">' + descripcion + '</p>';
                }
                
                if (link) {
                    html += '<a href="' + link + '" target="_blank" class="carrusel-link-btn" onclick="event.stopPropagation();">' +
                            '<i class="fas fa-external-link-alt"></i> Más información' +
                            '</a>';
                }
                
                html += '</div>';
            }
            
            html += '</div>';
        });
        
        html += '</div>';
        
        if (totalSlides > 1) {
            html += '<button class="carrusel-nav carrusel-nav-prev" id="carrusel_prev">' +
                    '<i class="fas fa-chevron-left"></i>' +
                    '</button>';
            html += '<button class="carrusel-nav carrusel-nav-next" id="carrusel_next">' +
                    '<i class="fas fa-chevron-right"></i>' +
                    '</button>';
            
            html += '<div class="carrusel-dots" id="carrusel_dots">';
            for (var i = 0; i < totalSlides; i++) {
                html += '<div class="carrusel-dot' + (i === 0 ? ' active' : '') + '" data-index="' + i + '"></div>';
            }
            html += '</div>';
        }
        
        html += '</div>';
        
        $('#contenedor_carrusel_alumno').html(html);
        
        if (totalSlides > 1) {
            inicializarEventosCarrusel();
            iniciarAutoplay();
        }
        
        inicializarLightbox();
    }
    
    function inicializarLightbox() {
        $('.carrusel-slide').off('dblclick').on('dblclick', function(e) {
            if ($(e.target).closest('.carrusel-link-btn').length > 0) {
                return;
            }
            
            detenerAutoplay();
            
            var index = $(this).data('index');
            var banner = carruselesData[index];
            var urlImagen = '../img/' + banner.img_car;
            var titulo = banner.tit_car || '';
            var descripcion = banner.des_car || '';
            var link = banner.url_car || '';
            
            var lightboxHTML = '<div class="lightbox-overlay" id="carrusel_lightbox_overlay">';
            
            lightboxHTML += '<button class="lightbox-close" id="cerrar_lightbox_carrusel">';
            lightboxHTML += '<i class="fas fa-times"></i>';
            lightboxHTML += '</button>';
            
            lightboxHTML += '<div class="lightbox-content">';
            
            lightboxHTML += '<div class="lightbox-image-container">';
            lightboxHTML += '<img src="' + urlImagen + '" alt="' + titulo + '" class="lightbox-image">';
            lightboxHTML += '</div>';
            
            if (titulo || descripcion || link) {
                lightboxHTML += '<div class="lightbox-info">';
                
                if (titulo) {
                    lightboxHTML += '<div class="lightbox-titulo">' + titulo + '</div>';
                }
                
                if (descripcion) {
                    lightboxHTML += '<div class="lightbox-descripcion">' + descripcion + '</div>';
                }
                
                if (link) {
                    lightboxHTML += '<a href="' + link + '" target="_blank" class="lightbox-link-btn">';
                    lightboxHTML += '<i class="fas fa-external-link-alt"></i> Más información';
                    lightboxHTML += '</a>';
                }
                
                lightboxHTML += '</div>';
            }
            
            lightboxHTML += '</div>';
            lightboxHTML += '</div>';
            
            $('body').append(lightboxHTML);
            
            setTimeout(function() {
                $('#carrusel_lightbox_overlay').fadeIn(300);
            }, 10);
            
            $('#cerrar_lightbox_carrusel').on('click', function() {
                cerrarLightbox();
            });
            
            $('#carrusel_lightbox_overlay').on('click', function(e) {
                if (e.target === this) {
                    cerrarLightbox();
                }
            });
            
            $(document).on('keydown.lightbox', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    cerrarLightbox();
                }
            });
        });
    }
    
    function cerrarLightbox() {
        $('#carrusel_lightbox_overlay').fadeOut(300, function() {
            $(this).remove();
        });
        $(document).off('keydown.lightbox');
        iniciarAutoplay();
    }
    
    function inicializarEventosCarrusel() {
        $('#carrusel_prev').on('click', function() {
            navegarCarrusel('prev');
        });
        
        $('#carrusel_next').on('click', function() {
            navegarCarrusel('next');
        });
        
        $('.carrusel-dot').on('click', function() {
            var index = $(this).data('index');
            irASlide(index);
        });
        
        $('.carrusel-wrapper').on('mouseenter', function() {
            detenerAutoplay();
        });
        
        $('.carrusel-wrapper').on('mouseleave', function() {
            iniciarAutoplay();
        });
    }
    
    function navegarCarrusel(direccion) {
        if (direccion === 'next') {
            currentIndex = (currentIndex + 1) % totalSlides;
        } else {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        }
        
        actualizarCarrusel();
    }
    
    function irASlide(index) {
        currentIndex = index;
        actualizarCarrusel();
    }
    
    function actualizarCarrusel() {
        var offset = -currentIndex * 100;
        $('#carrusel_slides').css('transform', 'translateX(' + offset + '%)');
        
        $('.carrusel-dot').removeClass('active');
        $('.carrusel-dot[data-index="' + currentIndex + '"]').addClass('active');
    }
    
    function iniciarAutoplay() {
        detenerAutoplay();
        
        autoplayInterval = setInterval(function() {
            navegarCarrusel('next');
        }, autoplayDelay);
    }
    
    function detenerAutoplay() {
        if (autoplayInterval) {
            clearInterval(autoplayInterval);
            autoplayInterval = null;
        }
    }
    
    cargarCarruselAlumno();
});
</script>

<!-- ========================================= -->
<!-- 🚀 SCRIPT MODAL TUTORIAL -->
<!-- ========================================= -->
<script>
$(document).ready(function() {
    $('#tutorialModal').on('hidden.bs.modal', function () {
        $("#tutorialVideo")[0].pause();
    });

    $('#tutorialModal').on('shown.bs.modal', function () {
        $("#tutorialVideo")[0].play();
    });

    $(window).resize(function() {
        var modalBody = $('#tutorialModal .modal-body');
        var video = $('#tutorialVideo');
        video.width(modalBody.width());
    });
});
</script>


<!-- ========================================= -->
<!-- 🔥 SCRIPT MODAL PAGOS FALTANTES -->
<!-- ========================================= -->
<?php if ($acceso_bloqueado_pagos) { ?>
<script>
$(document).ready(function() {
    
    $('#modalPagosFaltantes').modal('show');
    
    $('#modalPagosFaltantes').on('hide.bs.modal', function(e) {
        e.preventDefault();
    });
    
    $('#btn_cerrar_pagos').on('click', function() {
        window.location.href = 'cerrar_sesion.php';
    });
});
</script>
<?php } ?>

<!-- ========================================= -->
<!-- 🚀 SCRIPT MODAL CONTRATO -->
<!-- ========================================= -->
<?php
	if ( ( $presentacion == NULL ) && ( $ingresoUsuario > '2023-12-01' ) && !$acceso_bloqueado_pagos ) {
?>

		<script>
			$('#modal_contrato').modal('show');


			$('#modal_contrato').on('hide.bs.modal', function(e){
                
	            e.preventDefault();
	        
	        });

	        $('#btn_contrato').on('click', function(event) {
	        	event.preventDefault();

	        	swal({
				  title: "¿Estás seguro que deseas aceptar y continuar?",
				  text: "Podrás visualizar el acuerdo de términos y condiciones después",
				  icon: "info",
				  buttons: 	{
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
							    className: "btn-info",
							    closeModal: true
							  }
							},
				  dangerMode: true,
				}).then((willDelete) => {
				  if (willDelete) {

				    $.ajax({
		        		url: 'server/editar_estatus_contrato.php',
		        		type: 'POST',
		        		success: function( respuesta ){
		        			
		        			if ( respuesta == 'Exito' ) {
		        				swal("Guardado correctamente", "Puedes continuar", "success", {button: "Aceptar",}).then((willDelete) => {
				  					if ( willDelete ) {
				  						window.location.href = 'index.php';
				  					};
				  				});
		        			}
		        			
		        		}
		        	});
				    
				  }
				});
	        });



	        $('#btn_cancelar').on('click', function(event) {
	        	event.preventDefault();

	        	$('#modal_contrato').off('hide.bs.modal');

	        	window.location.href = 'cerrar_sesion.php';
	        });



		</script>

<?php
	}

?>

<!-- ========================================= -->
<!-- 🚀 SCRIPT MODAL ESTATUS (CUENTA DESACTIVADA) -->
<!-- ========================================= -->
<?php
	
	if ($acceso_bloqueado && !$acceso_bloqueado_pagos) {
?>
		<div class="modal fade" id="modalEstatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	      aria-hidden="true">

	      <div class="modal-dialog" role="document">


	        <div class="modal-content">
	          
	          <div class="modal-header bg-danger white-text text-center">
	            <h4 class="modal-title w-100" id="myModalLabel">
	            	<?php 
	            		if ($motivo_bloqueo == 'cuenta_desactivada') {
	            			echo 'Alumno Desactivado';
	            		} else {
	            			echo 'Acceso Suspendido';
	            		}
	            	?>
	            </h4>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	              <span aria-hidden="true">&times;</span>
	            </button>
	          </div>
	          
	          <div class="modal-body">
	            <div class="row">
	              <div class="col-2">
	                <p></p>
	                
	                <p class="text-center"><i class="fas fa-info fa-4x text-danger animated wobble infinite"></i></p>
	              </div>

	              <div class="col-10 text-left text-danger">
	                <p>
	                	Excelente día, <?php echo $nombreCompleto; ?>, lamentamos informarte que no puedes seguir realizando tus actividades 

	                	<?php  
	                		if ($motivo_bloqueo == 'cuenta_desactivada') {
	                	?>
	                			debido a que tu cuenta ha sido desactivada por parte de la administración del plantel.
	                			
	                			<br><br>
	                	<?php
	                		} elseif ($motivo_bloqueo == 'pagos_vencidos') {
	                	?>
	                			debido a que tienes pagos vencidos pendientes.
	                			
	                			<br><br>
	                			
	                			<strong>Detalle de pagos vencidos:</strong>
	                			<br>
	                			<?php 
	                				foreach ($pagos_debe as $pago) {
	                			?>
	                				• <?php echo $pago['concepto']; ?>: $<?php echo number_format($pago['monto'], 0, '.', ','); ?> (Vencido: <?php echo $pago['fecha_vencimiento_formateada']; ?> - <?php echo $pago['dias_vencido']; ?> días)
	                				<br>
	                			<?php } ?>
	                			
	                			<br>
	                			<strong>Total adeudado: $<?php echo number_format($monto_adeudo, 0, '.', ','); ?></strong>
	                			
	                			<br><br>
	                	<?php
	                		}
	                	?>

	                  Para continuar, agradeceríamos que te comunicaras al <span class="text-primary"><?php echo $telefonoPlantel; ?></span> a la brevedad, con la finalidad de resolver el problema. Gracias.
	                </p>

	              </div>
	            </div>
	          </div>
	          

	          <div class="modal-footer">
	            
	        	<a class="btn grey white-text btn-rounded waves-effect" title="Aceptar y continuar..." data-dismiss="modal" id="btn_salir">
		            Aceptar
		        </a>
	          </div>
	        </div>
	      </div>
	    </div>

	    
	    <script>
	      	$("#modalEstatus").modal('show');

	      	$('#modalEstatus').on('hide.bs.modal', function(e){
                
	            e.preventDefault();
	        
	        });

	    	$('#btn_salir').on('click', function(event) {
	        	event.preventDefault();

	        	$('#modalEstatus').off('hide.bs.modal');

	        	window.location.href = 'cerrar_sesion.php';
	        });
	    </script>



<?php
	} else {
?>

		<?php  
			if ( ( $cor1_alu == NULL ) AND ( $estatus2Alumno == 'Activo' ) && !$acceso_bloqueado_pagos ) {
		?>
				<div class="modal fade" id="modal_formulario_alumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
			      aria-hidden="true">

			      <div class="modal-dialog" role="document">


			        <div class="modal-content">
			          
			          <div class="modal-header bg-danger white-text text-center">
			            <h4 class="modal-title w-100" id="myModalLabel">Alumno</h4>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			              <span aria-hidden="true">&times;</span>
			            </button>
			          </div>
			        
			        	<form id="formulario_alumno">
				          	<div class="modal-body">

				          		<div class="alert alert-warning alert-dismissible fade show letraMediana" role="alert">
								  	<i class="fas fa-exclamation-triangle"></i> Antes de continuar, proporciona tu verdadero correo electrónico.
									
									<br>

								  	Gracias.
								  
								</div>

								<div class="md-form mb-5">

									<i class="far fa-envelope prefix grey-text"></i>
									<input type="email" id="cor1_alu" name="cor1_alu" class="form-control validate">
									<label  for="cor1_alu">Correo electrónico</label>
						        
						        </div>
					        </div>
			          

					        <div class="modal-footer">
					            
					        	<button type="submit" class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar y continuar..."  id="btn_formulario_alumno">
						            Guardar
						        </button>

					        </div>
					    </form>

			        </div>

			      </div>
			    
			    </div>

			    
			    <script>
			      	$("#modal_formulario_alumno").modal('show');

			      	setTimeout(function(){
			      		$('#cor1_alu').focus();
			      	}, 500 );

			      	$('#modal_formulario_alumno').on('hide.bs.modal', function(e){
		                
			            e.preventDefault();
			        
			        });


			        $('#formulario_alumno').on('submit', function(event) {
			        	event.preventDefault();
			        	
			        	var formulario_alumno = new FormData( $('#formulario_alumno')[0] );

			        	$.ajax({
							
							url: 'server/editar_alumno_inicio.php',
							type: 'POST',
							data: formulario_alumno, 
							processData: false,
							contentType: false,
							cache: false,
							success: function(respuesta){
							console.log(respuesta);

								if (respuesta == 'Exito') {
									swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
									then((value) => {
										if ( value ) {
											$('#modal_formulario_alumno').off('hide.bs.modal');
											$("#modal_formulario_alumno").modal('hide');
										}
									});
									
								}
							}
						});


			        });

			    </script>

		<?php
			}
		?>

<?php
	}
?>