<?php  

	require('../inc/cabeceras.php');
 	require('../inc/funciones.php');

 	// OBTENCION DE id_sal
 	$id_sal = $_POST['id_sal'];

 	// SQL PARA LISTAR MENSAJES
 	$sqlMensajes = "
 		SELECT *
 		FROM mensaje
 		WHERE id_sal4 = '$id_sal'
 		ORDER BY hor_men ASC
 	";

 	// EJECUCION DE QUERY
 	$resultadoMensajes = mysqli_query( $db, $sqlMensajes );

 	// ITERACION DE MENSAJES
 	while( $filaMensajes = mysqli_fetch_assoc( $resultadoMensajes ) ){

?>
		
		<?php  
			// if QUE DEFINE SI SON MIS MENSAJES PARA IDENTARLOS DE LADO DERECHO
			if ( ( $filaMensajes['use_men'] == $id ) && ( $filaMensajes['tip_men'] == $tipo ) ) {

		?>
				<!-- EMISOR -->
				<div class="card bg-info rounded w-75 float-right z-depth-0 mb-1">
                    <div class="card-body p-2">
		              <p class="card-text text-white">
		                <?php echo $filaMensajes['men_men']; ?>
		              </p>
		            </div>
		        </div>
				<!-- FUN EMISOR -->

				

		<?php
			} else {
			// SI NO SON MIOS LOS LANZA DE LADO OPUESTO 
		?>

				<!-- RECEPTOR -->
				<div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        	<?php echo $filaMensajes['men_men']; ?>
                      </p>
                    </div>
                </div>
				<!-- FIN RECEPTOR -->
				

		<?php
			}
		?>
		
		
<?php
 	}
?>