<?php  
 
	include('inc/header.php');

?>

	<style>
	    .dropdown-menu {
	        max-height: 10vw;
	        overflow-y: auto;
	    }
	</style>

	<!-- TITULO -->
	<div class="row ">
		<div class="col text-left">
			<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Efectividad">
				<i class="fas fa-bookmark"></i> Efectividad
			</span>
			<br>
			<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
				<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: white;" href="#" title="Efectividad">Efectividad</a>
			</div>
			
		</div>
		
	</div>
	<!-- FIN TITULO -->
	<hr>
	

	<div class="card p-2" style="border-radius: 20px;">
		

		<span>
			CDE
		</span>

		<select class="browser-default custom-select filtrosReporte" disabled="" id="selector_plantel">
			
			<?php  
				$sqlPlanteles = "
					SELECT *
					FROM plantel
					WHERE id_cad1 = '$identificadorCadena'	            
				";

				// echo $sqlPlanteles;

				$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
				$contador = 1;
				while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
			?>
					<?php  
						if ( $contador == 1 ) {
					?>
							<option value="Todos">Todos</option>
					<?php
						}
					?>

					<?php  
						if ( $filaPlanteles['id_pla'] == $plantel ) {
					?>
							<option selected value="<?php echo $filaPlanteles['id_pla']; ?>"><?php echo $filaPlanteles['nom_pla']; ?></option>
					<?php
						} else {
					?>
							<option value="<?php echo $filaPlanteles['id_pla']; ?>"><?php echo $filaPlanteles['nom_pla']; ?></option>
					<?php
						}
					?>
					
					

			<?php
					$contador++;
				}
			?>
			
		</select>


		
									
		<div class="row mt-1">
			<div class="col-md-6">

					<span>Inicio</span>
		        	<input type="date" class="form-control filtrosReporte letraMediana" id="inicio" value="<?php echo date('Y-m-d'); ?>">
		        
		        
			</div>

			<div class="col-md-6">

					<span>Fin</span>
					<input type="date" class="form-control filtrosReporte letraMediana" id="fin" value="<?php echo date('Y-m-d'); ?>">
		        
			</div>
		</div>
	</div>



	<div id="contenedor_reporte_datos"></div>
	


<?php  
	include('inc/footer.php');
?>


<script>
	obtener_reporte_datos();

	$('.filtrosReporte').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_reporte_datos();

	});

	function obtener_reporte_datos(){
		var inicio = $('#inicio').val();
		var fin = $('#fin').val();

		var id_pla = $('#selector_plantel option:selected').val();

		$.ajax({
			url: 'server/obtener_reporte_datos.php',
			type: 'POST',
			data: { inicio, fin, id_pla },
			success: function( respuesta ){
				console.log( respuesta );

				$('#contenedor_reporte_datos').html( respuesta );
			}
		});
		

	}
</script>