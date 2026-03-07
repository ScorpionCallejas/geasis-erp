<?php 
	//ARCHIVO VIA AJAX PARA OBTENER TRABAJOS ESPECIALES
	//obtener_trabajos_especiales.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pro_alu_ram = $_POST['id_pro_alu_ram'];

	$sqlProyectos = "
		SELECT *
		FROM proyecto_alu_ram
		INNER JOIN proyecto ON proyecto.id_pro = proyecto_alu_ram.id_pro1
		WHERE id_pro_alu_ram = '$id_pro_alu_ram'
	";

	$resultadoProyectos = mysqli_query( $db, $sqlProyectos );

	$filaProyectos = mysqli_fetch_assoc( $resultadoProyectos );

	$id_pro_alu_ram = $filaProyectos['id_pro_alu_ram'];

	$estatus = obtenerEstatusProyectoAlumnoServer( $id_pro_alu_ram );
?>


<span class="badge badge-pill badge-light letraPequena font-weight-normal" style="position: absolute; right: 10px; top: 1px;" title="Este es un trabajo especial"> <i class="fas fa-star text-warning"></i> Trabajo especial</span>

		

<?php  
	$formatoArchivo = obtenerFormatoArchivo( $filaProyectos['arc_pro'] );
?>

<div class="row">
	
	<div class="col-md-6">
		
		<h6>
			<i class="fas fa-circle"></i> Puntos: <?php echo $filaProyectos['pun_pro']; ?> / 
			<?php  
				if ( $estatus == 'Entregada' ) {
					
					echo $filaProyectos['pun_pro_alu_ram'];

				} else {

					echo 'Sin entregar';
				
				}
			?>
		</h6>

		<span class="letraGrande grey-text">
			Del <?php echo fechaFormateadaCompacta2( $filaProyectos['ini_pro'] ).' al '.fechaFormateadaCompacta2( $filaProyectos['fin_pro'] ); ?>
		</span>

	</div>

	<div class="col-md-6">
		
		<div style="" class="text-right">
	
			<a href="../archivos/<?php echo $filaProyectos['arc_pro']; ?>" download class="btn-link" title="Descargar: <?php echo $filaProyectos['arc_pro']; ?>">
		          
		        <?php  
		            	if ( $formatoArchivo == 'docx' ) {
		        ?>
		              		<i class="fas fa-file-word fa-3x blue-text"></i>

		        <?php
		            	} else if ( $formatoArchivo == 'pptx' ) {
		        ?>
		            		<i class="fas fa-file-powerpoint fa-3x orange-text"></i>

		        <?php 
		            	} else if ( $formatoArchivo == 'pdf' ) {
		        ?>
		              		<i class="fas fa-file-pdf fa-3x red-text"></i>

		        <?php 
		            	} else if ( ( $formatoArchivo == 'xls' ) || ( $formatoArchivo == 'xlsx' ) ){
		        ?>

		              		<i class="fas fa-file-excel fa-3x green-text"></i>

		        <?php
		            	} else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
		        ?>  

		              		<i class="fas fa-image fa-3x orange-text"></i>

		        <?php
		            	}
		        ?>

		        <br>
		        <span class="letraMediana">
		        	Descargar	
		        </span>
		        
		    </a>

			

		</div>
			
	</div>
</div>

<hr>

<div>
	<?php echo $filaProyectos['des_pro']; ?>
</div>

<?php  
		if ( $formatoArchivo == 'pdf' ) {
?>				


<!-- cadena = 'hello hello my friend, how are you!'; -->
<!-- 1 - hello -->


      		<!-- 
				LOCAL
      		<iframe
			    src="../pdfjs/web/viewer.html?file=http://localhost/geasis2/archivos/<?php echo $filaProyectos['arc_pro']; ?>"
			    width="100%"
			    height="500px"
			    style="border: none;" /> -->



				<!-- ONLINE -->
      		<iframe
			    src="../pdfjs/web/viewer.html?file=https://saesinstitutodck.com/archivos/<?php echo $filaProyectos['arc_pro']; ?>"
			    width="100%"
			    height="500px"
			    style="border: none;" />
<?php
    	} else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
?>  

      		<a href="../archivos/<?php echo $filaProyectos['arc_pro']; ?>" data-lightbox="roadtrip">
            	<img src="../archivos/<?php echo $filaProyectos['arc_pro']; ?>" class="img-fluid" style="height: 500px; width: 100%;">
          	</a>

<?php
    	}
?>