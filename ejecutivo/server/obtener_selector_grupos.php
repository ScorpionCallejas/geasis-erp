<?php 
	require('../inc/cabeceras.php'); 
	require('../inc/funciones.php');

	$id_ram = $_POST['id_ram'];
	$gra_ram = isset($_POST['gra_ram']) ? $_POST['gra_ram'] : '';
	$booleano_liga = isset($_POST['booleano_liga']) ? $_POST['booleano_liga'] : false;
?>

<select id="selector_generacion" class="form-control">
    <?php
        $sqlGrupos = "
            SELECT *
            FROM generacion
            INNER JOIN rama ON rama.id_ram = generacion.id_ram5
            WHERE id_ram5 = '$id_ram' 
            AND (est_gen = '1' OR est_gen = '2')  -- Estados donde comercial puede ver (1=todos, 2=solo comercial)
            ORDER BY id_gen DESC
        ";
        $resultadoGrupos = mysqli_query($db, $sqlGrupos);

        while ($filaGrupos = mysqli_fetch_assoc($resultadoGrupos)) {
			echo '<option 
				id_gen="' . $filaGrupos['id_gen'] . '" 
				id_ram="' . $id_ram . '"
			>' . 
				strtoupper($filaGrupos['nom_gen']) . ' ( ' . fechaFormateadaCompacta3($filaGrupos['ini_gen']) . ' )
			</option>';
		}
    ?>
</select>

<?php if (!$booleano_liga): ?>
<!-- SCRIPTS NORMALES (sin booleano_liga) -->
<script>
	function obtener_colegiatura_grupo2() { 
		console.log('obtener_cole_grupo2..');
		var id_gen = $('#selector_generacion option:selected').attr('id_gen');
		$.ajax({
			url: 'server/controlador_grupo.php',
			type: 'POST',
			dataType: 'json',
			data: { id_gen },
			success: function(response) {
				console.log('r: '+response.data.mon_gen);
				if( response.data.mon_gen == null ){
					toastr.error('¡Debes colocar colegiatura!'); 
					toastr.info('¡Debes colocar colegiatura!');
				} else {
					$('#colegiatura').val(response.data.mon_gen);
				}
			},
		});
	}

	obtener_colegiatura_grupo2();
	$('#selector_generacion').on('change', function(){
        obtener_colegiatura_grupo2();
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