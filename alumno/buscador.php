<?php  

	include('inc/header.php');

?>

<!-- titulo -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Todas tus calificaciones"><i class="fas fa-bookmark"></i> Buscador</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Buscador</a>
		</div>
	</div>
</div>
<!-- fin titulo -->
<br>

<!-- Jumbotron -->
<div class="jumbotron text-center mdb-color  grey lighten-4  black-text mx-2 mb-5">


	
	<!-- BUSCADOR -->
	<div class="row text-left">


		<!-- parte del estilo del parrafo-->
		<div class="col-md-6">
			<i class="fas fa-users fa-3x text-info"></i>
				
			<p>¡Encuentra a cualquier persona del plantel por medio del correo electrónico o su nombre y contáctalo!</p>
			<br>
			<form class="form-inline md-form form-sm mt-0" method="GET" action="buscador_resultado.php">

	            <input type="text" autofocus class="form-control mr-sm-2" name="palabra"  placeholder="Buscar..." value="">
	            <br>
	            <button type="submit" class="btn btn-info btn-sm" name="buscador" value=' Buscar '><i class="fas fa-search fa-2x"></i></button>
	        </form>
	    
		</div>
	
	</div>

	</div>

	<!-- FIN BUSCADOR -->
</div>
<!-- Fin Jumbotron -->


<?php  

	include('inc/footer.php');

?>