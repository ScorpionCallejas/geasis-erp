<?php  
		

  	include('inc/header.php');

 
  	$id_alu_ram = $_GET['id_alu_ram'];	

	$sqlMaterias = "
	    SELECT *
	    FROM proyecto_alu_ram
	    INNER JOIN proyecto ON proyecto.id_pro = proyecto_alu_ram.id_pro1
	    INNER JOIN grupo ON grupo.id_gru = proyecto.id_gru2
	    INNER JOIN alu_ram ON alu_ram.id_alu_ram = proyecto_alu_ram.id_alu_ram15
	    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	    WHERE id_alu_ram = '$id_alu_ram'
	    GROUP BY id_cic
	";


	// echo $sqlMaterias;

	$resultadoDatosHorario = mysqli_query( $db, $sqlMaterias );

	$filaDatosHorario = mysqli_fetch_assoc( $resultadoDatosHorario );

	// DATOS RAMA
	$nom_ram = $filaDatosHorario['nom_ram'];
	$mod_ram = $filaDatosHorario['mod_ram'];
	$gra_ram = $filaDatosHorario['gra_ram'];
	$per_ram = $filaDatosHorario['per_ram'];
	$cic_ram = $filaDatosHorario['cic_ram'];

	// DATOS CICLO ESCOLAR
	$nom_cic = $filaDatosHorario['nom_cic'];
	$ins_cic = $filaDatosHorario['ins_cic'];
	$ini_cic = $filaDatosHorario['ini_cic'];
	$cor_cic = $filaDatosHorario['cor_cic'];
	$fin_cic = $filaDatosHorario['fin_cic'];

	$nom_gru = $filaDatosHorario['nom_gru'];

	$resultadoTotal = mysqli_query( $db, $sqlMaterias );
	$total = mysqli_num_rows( $resultadoTotal );


	// if ( $total > 0  ) {
	
	// 	header('location: not_found_404_page.php');
	// 	// echo $sqlHorario;
	// }
?>


<!-- TITULO -->

<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
	background-image: url('../fondos_clase/trabajo_especial.jpg'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; top: -40px; position: relative; 

">
	
	<div class="col text-left col-sm-6">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" style="font-size: 1.5vw;">
			<i class="fas fa-bookmark"></i> 
			Trabajos especiales
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Grupo: <?php echo $nom_gru; ?>
		</span>

		
	</div>

	<div class="col text-right col-sm-6">

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Programa: <?php echo $nom_ram.' '.$mod_ram; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Ciclo escolar: <?php echo $nom_cic; ?>
		</span>
		<br>
		<br>

		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable waves-effect" style="font-size: 1.5vw;">
			<i class="fas fa-circle"></i>
			Del <?php echo fechaFormateadaCompacta2( $ini_cic ).' al '.fechaFormateadaCompacta2( $fin_cic ); ?>
		</span>
	
		
		
	</div>
	
</div>
<!-- FIN TITULO -->

<!-- MODALES -->


<!-- OBTENER PROYECTO -->
<div class="modal fade text-left " id="modal_obtener_proyecto">
  	
  	<div class="modal-dialog modal-lg" role="document">
    

      	<div class="modal-content" style="border-radius: 20px;">
	        <div class="modal-header text-center">
	          
	          	<h4 class="modal-title w-100" id="titulo_obtener_proyecto">
		        	
		        </h4>

	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>

	        <div class="modal-body mx-3" id="contenedor_obtener_proyecto">

	       	</div>

	        <div class="modal-footer d-flex justify-content-center">

	 
		      	
		      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>

	          
	        </div>

      	</div>

  	</div>

</div>
<!-- FIN OBTENER PROYECTO -->


<!-- CONTENEDOR REPORTE -->
<div class="row">

	<div class="col-md-12">

		<div class="card" style="border-radius: 20px;" id="contenedor_trabajos_especiales">
		<!--  -->



			
			

			
		<!--  -->
		</div>
	</div>
</div>



<?php 
	include('inc/footer.php');
?>


<script>
	obtener_trabajos_especiales();

	function obtener_trabajos_especiales(){

		var id_alu_ram = <?php echo $id_alu_ram; ?>;
		var id_pro_alu_ram = getParameterByName('id_pro_alu_ram');

		if ( id_pro_alu_ram != undefined ) {

			$.ajax({
				url: 'server/obtener_trabajos_especiales.php',
				type: 'POST',
				data: { id_alu_ram, id_pro_alu_ram },
				success: function( respuesta ){

					$('#contenedor_trabajos_especiales').html( respuesta );

				}
			});

		} else {

			$.ajax({
				url: 'server/obtener_trabajos_especiales.php',
				type: 'POST',
				data: { id_alu_ram },
				success: function( respuesta ){

					$('#contenedor_trabajos_especiales').html( respuesta );

				}
			});


		}

		
		
	}
</script>