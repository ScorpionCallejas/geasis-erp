<?php  

	include('inc/header.php');



	$id_alu_ram= $_GET['id_alu_ram'];
	$sqlCiclo = "
			
		

		SELECT *
		FROM sub_hor
		INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_alu_ram1 = '$id_alu_ram'
		";
	$resultadoCiclo = mysqli_query($db, $sqlCiclo);


	$totalCiclos = mysqli_num_rows($resultadoCiclo);

	if ($totalCiclos == 0) {
		header('location: not_found_404_page.php');
	}

	$filaCiclo = mysqli_fetch_assoc($resultadoCiclo);

	$id_cic = $filaCiclo['id_cic1'];
	$nom_ram = $filaCiclo['nom_ram'];

?>

 <!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Calendario Escolar</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Ramas">Ramas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Calendario</a>
		</div>		
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Calendario de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Carrera: <?php echo $nom_ram; ?>
		</span>	
	</div>
</div>
<!-- FIN TITULO -->


         
<!-- Jumbotron -->
<div class="jumbotron text-center mdb-color  grey lighten-4  black-text mx-2 mb-5">

	<div id="calendario">
		
	</div>


</div>
<!-- Jumbotron -->


<?php  

	include('inc/footer.php');

?>


<script>
	$(document).ready(function(){




		$("#calendario").fullCalendar({

			height: 500,
			
			header: {
				left: 'today, prev, next, month',
				center: 'title',
				right: ''
			},
			
		
			//JSON DATA
			events: 'server/listar_eventos.php?id_cic=<?php echo $id_cic; ?>',


			//POPOVER
			eventRender: function(eventObj, $el) {
		      $el.popover({
		        title: eventObj.title,
		        content: eventObj.descripcion,
		        trigger: 'hover',
		        placement: 'top',
		        container: 'body'
		      });

		      // $el.on('click', function(event) {
		      // 	event.preventDefault();
		      // 	 Act on the event 
		      // 	console.log("click popover");
		      // 	//$(this).remove();
		      // });
		    }
		    
			
		});

		//UPDATE ** PINTAR CALENDARIO PANZA Y HEADER
		// $(".fc-head").addClass('blue');
		// $(".fc-day").addClass('yellow lighten-3');
	});

	//

</script>