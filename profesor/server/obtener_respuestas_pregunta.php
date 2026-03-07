<?php 
	//ARCHIVO VIA AJAX PARA OBTENER  PREGUNTAS DE EXAMEN
	//examen_bloque.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pre1 = $_POST['id_pre'];

	$selector = $_POST['selector'];


	$sql = "
		SELECT *
		FROM respuesta
		WHERE id_pre1 = '$id_pre1'
		ORDER BY id_res DESC
	";

	$resultado = mysqli_query( $db, $sql );
	$resultadoTotal = mysqli_query( $db, $sql );

	$total = mysqli_num_rows( $resultadoTotal );
	// $i = 1;
	


	if ( $total > 0 ) {

		while( $fila = mysqli_fetch_assoc( $resultado ) ){
?>
			<div class="row p-2">
				

				<div class="col-md-1"></div>
				<div class="col-md-10">

					<div class="card z-depth-1 bg-white" style="border-radius: 20px;">
						
						<div class="card-body z-depth-1 bg-white" style="border-radius: 20px;">


							<div class="row clasePadre">
								<div class="claseHijoNumeracion font-weight-bold">
									<div class="claseTextoHijoNumeracion">
										<?php echo $total; ?>
									</div>
										
								</div>
								
								<div class="col-md-10">
									<?php 
										echo $fila['res_res']." <br>(".$fila['val_res'].")"; 
									?>
								</div>


								<div class="col-md-2">
									
									<!--Dropdown primary-->
									<div class="dropdown">

									  <!--Trigger-->

										<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
											<i class="fas fa-ellipsis-v grey-text"></i>
						    			</a>


									  <!--Menu-->
										<div class="dropdown-menu dropdown-info">
								
											<a class="dropdown-item waves-effect eliminacionRespuesta" id_res="<?php echo $fila['id_res']; ?>" selector="<?php echo $selector; ?>" id_pre="<?php echo $fila['id_pre1']; ?>" href="#">
												Eliminar respuesta
											</a>

										</div>
									</div>
									<!--/Dropdown primary-->

								</div>

							</div>
							
						</div>

					</div>

				</div>
				<div class="col-md-1"></div>
			</div>


<?php
		$total--;
		}
		// FIN  WHILE
	} else {
?>
		<div class="row p-2">
															
			<div class="col-md-12 text-center">
						
				<br>
				<br>
				<h5>
					<span class="badge badge-warning">
						¡No hay respuestas!
					</span>
				</h5>
				
				<img src="../img/sentado.gif" width="10%" class="animated tada delay-2s">
				
				
				<br>
				<br>


				<h6>
					<span class="badge badge-warning">
						¡Agrega al menos dos!
					</span>
				</h6>

			</div>

		</div>

<?php
	}
?>
	

<script>
	//ELIMINACION DE RESPUESTA
  $('.eliminacionRespuesta').on('click', function(event) {
      event.preventDefault();
      /* Act on the event */
      var id_res = $( this ).attr( "id_res" );
      var selector = $( this ).attr( 'selector' );
      var id_pre = $( this ).attr( 'id_pre' );
      // console.log(RESPUESTA);

    swal({
          title: "¿Deseas eliminar esta respuesta?",
          text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
          icon: "warning",
          buttons:  {
                cancel: {
                  text: "Cancelar",
                  value: null,
                  visible: true,
                  className: "",
                  closeModal: true,
                },
                confirm: {
                  text: "Confirmar",
                  value: true,
                  visible: true,
                  className: "",
                  closeModal: true
                }
              },
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            //ELIMINACION ACEPTADA

            $.ajax({
            url: 'server/eliminacion_respuesta.php',
            type: 'POST',
            data: {id_res},
            success: function(respuesta){
              
              if (respuesta == "true") {
                console.log("Exito en consulta");
                swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                then((value) => {

                	generarAlerta( 'Cambios guardados' );
					obtenerRespuestasPregunta( selector, id_pre );
                
                });
              }else{
                console.log(respuesta);

              }

            }
          });
            
          }
      });
  });
</script>