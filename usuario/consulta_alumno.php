<?php  
 
	include('inc/header.php');

	$id_alu_ram = $_GET['id_alu_ram'];

	$datos_alumno = obtenerDatosAlumnoPrograma( $id_alu_ram );

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
			<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Alumno <?php echo $datos_alumno['nom_alu'].' '.$datos_alumno['app_alu'].' '.$datos_alumno['apm_alu']; ?>">
				<i class="fas fa-bookmark"></i> <?php echo $datos_alumno['nom_alu'].' '.$datos_alumno['app_alu'].' '.$datos_alumno['apm_alu']; ?>
			</span>
			<br>
			<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
				<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: white;" href="alumnos.php" title="Alumnos">Alumnos</a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: black;" href="" title="Estás aquí"><?php echo $datos_alumno['nom_alu'].' '.$datos_alumno['app_alu'].' '.$datos_alumno['apm_alu']; ?></a>
			</div>
			
		</div>
		
	</div>
	<!-- FIN TITULO -->
	<hr>
	

	<div class="card p-2" style="border-radius: 20px;">
		<div id="contenedor_visualizacion8">
		</div>
	</div>
	


<?php  
	include('inc/footer.php');
?>

<script>
	obtener_consulta_alumno();
	function obtener_consulta_alumno(){
		var id_alu_ram = <?php echo $id_alu_ram; ?>;
		$.ajax({
			url: 'server/obtener_consulta_general_alumno.php',
			type: 'GET',
			data: { id_alu_ram },
			success: function( respuesta ){
				// console.log( respuesta );
				$('#contenedor_visualizacion8').html( respuesta );
			}
		});
	}
</script>


<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - <?php echo $datos_alumno['nom_alu'].' '.$datos_alumno['app_alu'].' '.$datos_alumno['apm_alu']; ?>');
</script>