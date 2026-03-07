<?php  
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_ram = $_POST['id_ram'];
	$gra_ram = isset($_POST['gra_ram']) ? $_POST['gra_ram'] : '';
	$booleano_liga = isset($_POST['booleano_liga']) ? $_POST['booleano_liga'] : false;

	$sqlGrupos = "
		SELECT g.*
		FROM generacion g
		INNER JOIN rama ON rama.id_ram = g.id_ram5
		WHERE g.id_ram5 = '$id_ram' 
		AND (g.est_gen = '1' OR g.est_gen = '2')
		ORDER BY g.ini_gen DESC
	";

?>

<select id="selector_generacion" class="form-control">
    <?php
        

		

        $resultadoGrupos = mysqli_query($db, $sqlGrupos);

        while ($filaGrupos = mysqli_fetch_assoc($resultadoGrupos)) {
			// Obtener trámites para esta generación
			$id_gen = $filaGrupos['id_gen'];
			$sqlTramites = "
				SELECT 
					id_gru_pag,
					con_gru_pag as concepto,
					mon_gru_pag as monto,
					ini_gru_pag as fecha_inicio,
					fin_gru_pag as fecha_fin
				FROM grupo_pago 
				WHERE id_gen15 = '$id_gen' 
				AND tip_gru_pag = 'Pago' 
				AND tip_pag_gru_pag = 'Otros'
				ORDER BY ini_gru_pag ASC, id_gru_pag ASC
			";
			$resultadoTramites = mysqli_query($db, $sqlTramites);
			$tramites = [];
			while ($filaTramite = mysqli_fetch_assoc($resultadoTramites)) {
				$fecha_inicio = '';
				if (!empty($filaTramite['fecha_inicio'])) {
					$fecha_inicio = date('Y-m-d', strtotime($filaTramite['fecha_inicio']));
				}
				$tramites[] = [
					'id_gru_pag' => $filaTramite['id_gru_pag'],
					'concepto' => $filaTramite['concepto'],
					'monto' => floatval($filaTramite['monto']),
					'fecha_inicio' => $fecha_inicio
				];
			}

			// Contar reinscripciones para esta generación (solo conteo)
			$sqlCountReinscripciones = "
				SELECT COUNT(*) as total_reinscripciones
				FROM grupo_pago 
				WHERE id_gen15 = '$id_gen' 
				AND tip_gru_pag = 'Pago' 
				AND tip_pag_gru_pag = 'Reinscripción'
			";
			$resultadoCountReinscripciones = mysqli_query($db, $sqlCountReinscripciones);
			$totalReinscripciones = mysqli_fetch_assoc($resultadoCountReinscripciones)['total_reinscripciones'];
			
			echo '<option 
				id_gen="' . $filaGrupos['id_gen'] . '" 
				id_ram="' . $id_ram . '"
				mon_ins_gen="' . $filaGrupos['mon_ins_gen'] . '"
				mon_col_gen="' . $filaGrupos['mon_col_gen'] . '"
				mon_rei_gen="' . $filaGrupos['mon_rei_gen'] . '"
				can_rei_gen="' . $totalReinscripciones . '"
				data-tramites="' . htmlspecialchars(json_encode($tramites)) . '"
			>' . 
				strtoupper($filaGrupos['nom_gen']) . ' ( ' . fechaFormateadaCompacta3($filaGrupos['ini_gen']) . ' )
			</option>';
		}
    ?>
</select>

<?php if (!$booleano_liga): ?>
<!-- SCRIPTS NORMALES (sin booleano_liga) -->
<script>
	function obtener_datos_pago_grupo() { 
		console.log('obtener_datos_pago_grupo...');
		
		var $selected = $('#selector_generacion option:selected');
		var id_gen = $selected.attr('id_gen');
		var mon_ins_gen = parseFloat($selected.attr('mon_ins_gen')) || 1000; // Default $1000
		var mon_col_gen = parseFloat($selected.attr('mon_col_gen')) || 1500; // Default $1500
		var mon_rei_gen = parseFloat($selected.attr('mon_rei_gen')) || 0;
		var can_rei_gen = parseInt($selected.attr('can_rei_gen')) || 0;
		
		// Obtener trámites del atributo data-tramites
		var tramitesData = $selected.attr('data-tramites');
		var tramites = [];
		
		try {
			if (tramitesData) {
				tramites = JSON.parse(tramitesData);
			}
		} catch(e) {
			console.error('Error al parsear datos de trámites:', e);
			tramites = [];
		}

		console.log('Datos obtenidos:', {
			mon_ins_gen, mon_col_gen, tramites, mon_rei_gen, can_rei_gen
		});

		// Construir HTML dinámico
		var html = '';

		// FILA 1: INSCRIPCIÓN Y COLEGIATURA (EDITABLES)
		html += '<div class="row mb-2">';
		html += '<div class="col-md-6">';
		html += '<span class="letraPequena"><strong>INSCRIPCIÓN</strong></span>';
		html += '<div class="input-group input-group-sm">';
		html += '<span class="input-group-text">$</span>';
		html += '<input type="number" id="inscripcion" name="monto_inscripcion" class="form-control" value="' + mon_ins_gen + '" step="0.01" min="0">';
		html += '</div>';
		html += '</div>';
		html += '<div class="col-md-6">';
		html += '<span class="letraPequena"><strong>COLEGIATURA</strong></span>';
		html += '<div class="input-group input-group-sm">';
		html += '<span class="input-group-text">$</span>';
		html += '<input type="number" id="colegiatura" name="monto_colegiatura" class="form-control" value="' + mon_col_gen + '" step="0.01" min="0">';
		html += '</div>';
		html += '</div>';
		html += '</div>';

		// FILAS DE TRÁMITES (Dinámicas según grupo_pago)
		if (tramites && tramites.length > 0) {
			console.log('🎯 Generando ' + tramites.length + ' trámite(s) dinámico(s)');
			
			tramites.forEach(function(tramite, index) {
				console.log('Generando trámite:', tramite);
				
				html += '<div class="row mb-2">';
				html += '<div class="col-md-6">';
				html += '<span class="letraPequena"><strong>' + tramite.concepto.toUpperCase() + '</strong></span>';
				html += '<div class="input-group input-group-sm">';
				html += '<span class="input-group-text">$</span>';
				html += '<input type="number" name="monto_tramite_' + tramite.id_gru_pag + '" class="form-control monto-tramite" value="' + tramite.monto + '" step="0.01" min="0" data-tramite-id="' + tramite.id_gru_pag + '">';
				html += '</div>';
				html += '</div>';
				html += '<div class="col-md-6">';
				html += '<span class="letraPequena"><strong>FECHA</strong></span>';
				html += '<input type="date" name="fecha_tramite_' + tramite.id_gru_pag + '" class="form-control form-control-sm fecha-tramite" value="' + tramite.fecha_inicio + '" data-tramite-id="' + tramite.id_gru_pag + '">';
				html += '</div>';
				html += '</div>';
				
				// Hidden inputs para el concepto (necesario para el submit)
				html += '<input type="hidden" name="concepto_tramite_' + tramite.id_gru_pag + '" value="' + tramite.concepto + '">';
			});
		} else {
			console.log('ℹ️ Esta generación no tiene trámites configurados');
		}

		// FILA REINSCRIPCIÓN: Solo mostrar resumen (monto base + conteo dinámico)
		if (mon_rei_gen > 0 && can_rei_gen > 0) {
			html += '<div class="row mb-2">';
			html += '<div class="col-md-6">';
			html += '<span class="letraPequena"><strong>REINSCRIPCIÓN</strong></span>';
			html += '<div class="input-group input-group-sm">';
			html += '<span class="input-group-text">$</span>';
			html += '<input type="number" name="monto_reinscripcion" class="form-control" value="' + mon_rei_gen + '" step="0.01" min="0" readonly style="background-color: #f8f9fa;">';
			html += '</div>';
			html += '</div>';
			html += '<div class="col-md-6">';
			html += '<span class="letraPequena"><strong>CANT. REINSCRIPCIONES</strong></span>';
			html += '<div class="alert alert-info py-1 px-2 mb-0 text-center" style="font-size: 12px; border: 1px solid #b8daff;">';
			html += '<strong>' + can_rei_gen + ' pago' + (can_rei_gen > 1 ? 's' : '') + '</strong>';
			html += '</div>';
			html += '<input type="hidden" name="cantidad_reinscripcion" value="' + can_rei_gen + '">';
			html += '</div>';
			html += '</div>';
		}

		// Inputs ocultos para compatibilidad (mantener los IDs originales)
		html += '<input type="hidden" id="cantidad_tramites" value="' + (tramites ? tramites.length : 0) + '">';
		html += '<input type="hidden" id="mon_rei_gen" value="' + mon_rei_gen + '">';
		html += '<input type="hidden" id="can_rei_gen" value="' + can_rei_gen + '">';

		// Actualizar el contenedor
		$('#contenedor_datos_pago').html(html);

		// Validaciones
		if (mon_col_gen == 0) {
			toastr.warning('Usando colegiatura por defecto: $1,500');
		}

		// Log de información
		if (tramites && tramites.length > 0) {
			console.log('✅ Se cargaron ' + tramites.length + ' trámite(s) para esta generación');
			console.log('📝 Campos generados con clase .monto-tramite listos para submit');
		} else {
			console.log('ℹ️ No se encontraron trámites para esta generación (es normal)');
		}

		if (can_rei_gen > 0) {
			console.log('✅ Se encontraron ' + can_rei_gen + ' reinscripción(es) configuradas para esta generación');
		} else {
			console.log('ℹ️ No se encontraron reinscripciones para esta generación');
		}
	}

	// Función auxiliar para formatear números
	function number_format(number, decimals) {
		return parseFloat(number).toLocaleString('es-MX', {
			minimumFractionDigits: decimals,
			maximumFractionDigits: decimals
		});
	}

	// Ejecutar al cargar y al cambiar
	obtener_datos_pago_grupo();
	$('#selector_generacion').on('change', function(){
		console.log('🔄 Cambio de generación detectado, recargando datos de pago...');
		obtener_datos_pago_grupo();
	});
</script>

<?php else: ?>
<!-- SCRIPTS PARA LIGA DE PAGO (con booleano_liga) -->
<script>
	// EJECUTAR LA FUNCIÓN PARA CARGAR PAQUETES INMEDIATAMENTE
	$(document).ready(function() {
		var gra_ram = '<?php echo $gra_ram; ?>';
		console.log('gra_ram recibido en grupos:', gra_ram);
		
		// Verificar que la función existe antes de ejecutarla
		if (typeof cargarPaquetesSegunPrograma === 'function') {
			// Convertir a mayúsculas para que coincida con la configuración
			var modalidadMayuscula = gra_ram.toUpperCase();
			console.log('Modalidad convertida a mayúsculas:', modalidadMayuscula);
			cargarPaquetesSegunPrograma(modalidadMayuscula);
		} else {
			console.error('La función cargarPaquetesSegunPrograma no está disponible');
		}
	});
</script>
<?php endif; ?>