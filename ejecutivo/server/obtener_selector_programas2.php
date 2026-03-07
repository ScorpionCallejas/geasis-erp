<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pla = $_POST['id_pla'];
	$booleano_liga = isset($_POST['booleano_liga']) ? $_POST['booleano_liga'] : false;
?>

<select id="selectorProgramas" class="form-control">
	<?php
		$sqlProgramas = "
			SELECT *
            FROM rama
            WHERE id_pla1 = $id_pla
			ORDER BY nom_ram ASC
		";

		$resultadoProgramas = mysqli_query( $db, $sqlProgramas );

		$bool = true;
		while ($filaProgramas = mysqli_fetch_assoc($resultadoProgramas)) {
			?>
			<option value="<?php echo $filaProgramas['id_ram']; ?>" 
			        gra_ram="<?php echo $filaProgramas['gra_ram']; ?>" 
			        <?php echo ($bool == true) ? 'selected="selected"' : ''; ?>>
				<?php echo strtoupper($filaProgramas['nom_ram']); ?>
			</option>
			<?php
			$bool = false;
		}
	?>
</select>

<?php if (!$booleano_liga): ?>
<!-- SCRIPTS NORMALES (sin booleano_liga) -->
<script>
	obtener_forma_titulacion();

	function obtener_forma_titulacion() {
		// Programas que requieren forma de titulación
		var programas_especiales = [364, 363, 361, 360, 359, 357];
		
		// Obtener el id_ram del programa seleccionado - CAMBIO AQUÍ:
		var id_ram = parseInt($('#selectorProgramas option:selected').val());
		
		// Limpiar el contenedor primero
		$('#contenedor_forma_titulacion').empty();
		
		// Verificar si el programa seleccionado está en la lista de programas especiales
		if ($.inArray(id_ram, programas_especiales) !== -1) {
			// Mostrar el contenedor que está oculto por defecto
			$('#contenedor_forma_titulacion').parent().parent().show();
			
			// Crear el HTML para el selector de forma de titulación
			var html_selector = '<div class="form-group">' +
				'<label for="selector_forma_titulacion" class="letraPequena greyText">Forma de Certificación:</label>' +
				'<select id="selector_forma_titulacion" name="forma_titulacion" class="form-control">' +
				'<option value="POR DIPLOMADO">POR DIPLOMADO</option>' +
				'<option value="POR EVALUACIÓN">POR EVALUACIÓN</option>' +
				'</select>' +
				'</div> <hr>';
			
			// Agregar el selector al contenedor
			$('#contenedor_forma_titulacion').html(html_selector);
		} else {
			// Ocultar el contenedor si no es un programa especial
			$('#contenedor_forma_titulacion').parent().parent().hide();
		}
	}
</script>

<script>
	obtener_selector_grupos();

    $('#selectorProgramas').on('change', function(){
        obtener_selector_grupos();
		obtener_forma_titulacion(); // Añadir esta línea
    });

    function obtener_selector_grupos(){
        var id_ram = $('#selectorProgramas option:selected').val();
        $.ajax({
            url: 'server/obtener_selector_grupos2.php',
            type: 'POST',
            data: { id_ram },
            success: function(resp) {
                // console.log( resp );
                $('#contenedor_grupos').html( resp );
            },
        });
    }
</script>

<?php else: ?>
<!-- SCRIPTS PARA LIGA DE PAGO (con booleano_liga) -->
<script>
	obtener_selector_grupos_liga();

    $('#selectorProgramas').on('change', function(){
        obtener_selector_grupos_liga();
    });

    function obtener_selector_grupos_liga(){
		var id_ram = $('#selectorProgramas option:selected').val();
		var gra_ram = $('#selectorProgramas option:selected').attr('gra_ram');
		var booleano_liga = true;
		
		console.log('id_ram:', id_ram);
		console.log('gra_ram:', gra_ram);
		
		// AGREGAR ESTA LÍNEA:
		cargarPaquetesSegunPrograma(gra_ram);
		
		$.ajax({
			url: 'server/obtener_selector_grupos2.php',
			type: 'POST',
			data: { id_ram, gra_ram, booleano_liga },
			success: function(resp) {
				$('#contenedor_grupos_liga').html( resp );
			},
		});
	}
</script>
<?php endif; ?>