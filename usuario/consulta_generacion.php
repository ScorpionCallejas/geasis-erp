<?php  
 
	include('inc/header.php');

	$id_gen = $_GET['id_gen'];

	$sqlGeneracion = "
		SELECT *
		FROM vista_generaciones
		WHERE id_gen = '$id_gen'
	";

	$datos_generacion = obtener_datos_consulta( $db, $sqlGeneracion )['datos'];

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
			<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable">
				<i class="fas fa-bookmark"></i> <?php echo $datos_generacion['nom_gen']; ?>
			</span>
			<br>
			<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
				<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: white;" href="alumnos.php" title="Alumnos">Alumnos</a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: black;" href="" title="Estás aquí"><?php echo $datos_generacion['nom_gen']; ?></a>
			</div>
			
		</div>
		
	</div>
	<!-- FIN TITULO -->
	<hr>
	

	<div class="card p-2" style="border-radius: 20px;">
		<div id="contenedor_visualizacion2">
		</div>
	</div>
	


<?php  
	include('inc/footer.php');
?>

<script>
	obtener_consulta_generacion();
	function obtener_consulta_generacion(){
		var id_gen = <?php echo $id_gen; ?>;
		$.ajax({
			url: 'server/obtener_consulta_generacion.php',
			type: 'GET',
			data: { id_gen },
			success: function( respuesta ){
				// console.log( respuesta );
				$('#contenedor_visualizacion2').html( respuesta );
			}
		});
	}
</script>


<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - <?php echo $datos_generacion['nom_gen']; ?>');
</script>