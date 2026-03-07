<?php  

	include('inc/header.php');

?>

<style>
	#myTable td{
	
		font-size: 10px;
	
	}

	.dropdown-menu {
        max-height: 10vw;
        overflow-y: auto;
    }
</style>



<!-- TITULO -->
<div class="row ">
	
	<div class="col text-left">
		
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Cobranza">
			<i class="fas fa-bookmark"></i> 
			Servicios Empresariales
		</span>
	
	</div>

</div>

<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Cobranza</a>
</div>
<!-- FIN TITULO -->





<!-- CONTENEDOR REPORTE -->
<div class="row">

	<div class="col-md-12">

		<div class="card" style="border-radius: 20px;">

			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4 text-center">
					
					<div class="card" style="border-radius: 20px;">
						<div class="card-body">
							
							<span  class="letraMediana">
								Tipo de visualización
							</span>
							<br>
							<!-- Group of material radios - option 1 -->
							<div class="form-check form-check-inline">
							  <input type="radio" class="form-check-input radioReporte" id="materialGroupExample1" name="seleccionReporte" value="Alumnos" checked>
							  <label class="form-check-label letraPequena" for="materialGroupExample1">Alumnos</label>
							</div>

							<!-- Group of material radios - option 2 -->
							<div class="form-check form-check-inline">
							  <input type="radio" class="form-check-input radioReporte" id="materialGroupExample2" name="seleccionReporte" value="Grupos">
							  <label class="form-check-label letraPequena" for="materialGroupExample2">Grupos</label>
							</div>

							<!-- Group of material radios - option 3 -->
							<div class="form-check form-check-inline">
							  <input type="radio" class="form-check-input radioReporte" id="materialGroupExample3" name="seleccionReporte" value="Programas">
							  <label class="form-check-label letraPequena" for="materialGroupExample3">Programas</label>
							</div>

						</div>
					</div>
					


				</div>
				
				

			</div>

			<div class="card-body" id="contenedor_reporte_cobranza">
				
				


			</div>
			
		</div>
	</div>
</div>
<!-- FIN CONTENEDOR REPORTE -->






<?php  

	include('inc/footer.php');

?>


<script>
	$('.radioReporte').on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_reporte_cobranza();
		// alert( radioReporte );

	});
</script>



<script>
	
	obtener_reporte_cobranza();

	function obtener_reporte_cobranza(){
	// INICIO FUNCION
		

		var radioReporte = $(".radioReporte:checked").val();

		if ( radioReporte == 'Alumnos' ) {

			url = 'server/obtener_reporte_cobranza_alumnos.php';

		} else if ( radioReporte == 'Programas' ) {

			url = 'server/obtener_reporte_cobranza_programas.php';

		} else if ( radioReporte == 'Grupos' ) {

			url = 'server/obtener_reporte_cobranza_grupos.php';

		}



		$.ajax({
			url: url,
			type: 'POST',
			// data: {  },
			success: function(respuesta){

				// console.log( respuesta );
				$("#contenedor_reporte_cobranza").html(respuesta);

				// setTimeout( function(){
				// 	$('#contenedor_prueba').html( 'hello world' );
				// }, 5000 );

			}
		});
		



	// FIN FUNCION
	}
</script>






<script>

  // FUNCTION REAL-TIME AL CONDONAR O CONVENIR FECHAS
  function obtener_notificaciones_cobros_header(){
    $.ajax({
      url: 'server/obtener_notificaciones_cobros.php',
      success: function( respuesta ){
        $("#contenedor_notificaciones_cobros_header").html( respuesta );
      }
    });
  }
</script>