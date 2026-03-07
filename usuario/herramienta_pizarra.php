<?php  
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('inc/cabeceras.php');
	require('inc/funciones.php');
	require_once(  __DIR__."/../includes/links_estilos.php");
	require_once(  __DIR__."/../includes/links_js.php");

	$id_sal = $_GET['id_sal'];
	
?>
	<title>
      <?php
        echo "Herramienta pizarra"; 
      ?>
    </title>

	<link rel="icon" href="../uploads/<?php echo $fotoPlantel; ?>">


	<div class="row">
		<div class="col-md-12 text-center" style="width: 100%; height: 600px;">
			<iframe src="https://wbo.ophir.dev/boards/pizarra_<?php echo $id_sal; ?>" frameborder="0" style="height: 700px; width: 100%;"></iframe>
		</div>
	</div>