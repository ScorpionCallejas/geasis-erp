<?php  
	//ARCHIVO VIA AJAX PARA OBTENER GRUPOS  DE CICLO EN ESTATUS ACTIVO PARA INSCRIPCION
	//inscripcion.php
	require('../inc/cabeceras.php');

	$id_cic = $_POST['ciclo'];

	

	$sqlConsultaGrupos = "
    	SELECT * 
    	FROM grupo
    	INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
    	WHERE id_cic = '$id_cic'
    ";

    //echo $sqlConsultaGrupos;
	$resultadoConsultaGrupos = mysqli_query($db, $sqlConsultaGrupos);

	$resultadoConsultaCiclo = mysqli_query($db, $sqlConsultaGrupos);
	$filaCiclo = mysqli_fetch_assoc($resultadoConsultaCiclo);

    echo '
    	<!-- Jumbotron -->
		<div class="jumbotron text-center grey lighten-1">
		<div class="container">
			<h4 class="mb-2 h4 white-text">Ciclo Seleccionado</h4>
			<div class="card  light-green accent-4 mb-3 waves-effect ciclos hoverable white-text" style="max-width: 20rem;">
			  <div class="card-header  grey darken-1">
			  	'.$filaCiclo["nom_cic"].'
			  </div>

			  <div class="card-body ">
			    <p class="card-text white-text">
			    	<small>
			    		Inscripción: '.$filaCiclo["ins_cic"].'<br>
			    		Inicia: '.$filaCiclo["ini_cic"].'<br>
			    		Corte: '.$filaCiclo["cor_cic"].'<br>
			    		Finaliza: '.$filaCiclo["fin_cic"].'
					</small>
			    </p>
			  </div>
			 </div>
			</div>
			  
			   
			
		    
			<div class="col-12 ml-auto">
				<div class="d-flex w-100 justify-content-between">
			      <h5 class="mb-2 h5 white-text">Selecciona un grupo</h5>
			    </div>
			</div>
	';
    
	$i = 1;

	while($filaGrupos = mysqli_fetch_assoc($resultadoConsultaGrupos)){
		echo '


			<div class="card   grey lighten-1 mb-3 waves-effect grupos hoverable white-text" grupo="'.$filaGrupos["id_gru"].'" style="max-width: 20rem;">
			  <div class="card-header  grey darken-1">
			  	'.$i.' - '.$filaGrupos["nom_gru"].'
			  </div>
			 
			</div>
		';

		$i++;
	}


	echo '
			
	
			<hr class="my-4 pb-2">
		</div>
		<!-- FIN Jumbotron -->

	';

?>