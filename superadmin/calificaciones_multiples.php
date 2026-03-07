<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">

                            <i class="pe-7s-home icon-gradient bg-premium-dark"></i>

                        </div>
                        <div>
                            DASHBOARD PRINCIPAL
                            <div class="page-title-subheading">Bienvenido a <?php echo "<strong>".$nombreCadena."</strong> estimado <strong>".$nombreUsuario."</strong>"; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div>

                <div class="row">

                    <div class="col-md-12">
                        
                        <table class="mb-0 table table-sm display nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>fin_cal</th>
                                    <th>id_mat4</th>
                                    <th>id_alu_ram2</th>
                                    <th>repetidos</th>

                                </tr>
                            </thead>
                            <tbody>

                            	<?php 

									$sql = "
										SELECT *, count(id_mat4) as total_repetidos
										FROM calificacion
										group by id_alu_ram2, id_mat4
										HAVING total_repetidos > 1
									";

									$res = mysqli_query( $db, $sql );
									$i = 1;

									while( $fila = mysqli_fetch_assoc( $res ) ){
										$id_alu_ram2 = $fila['id_alu_ram2'];
										$id_mat4 = $fila['id_mat4'];


								?>

										<tr>
		                                    <th scope="row"><?php echo $i; ?></th>
		                                    <td><?php echo $fila['fin_cal']; ?></td>
		                                    <td><?php echo $fila['id_mat4']; ?></td>
		                                    <td><?php echo $fila['id_alu_ram2']; ?></td>
		                                    <td><?php echo $fila['total_repetidos']; ?></td>

		                                </tr>


								<?php
										$i++;
										

										$sql2 = "
											SELECT *
											FROM calificacion
											WHERE id_alu_ram2 = '$id_alu_ram2' AND id_mat4 = '$id_mat4'
											ORDER BY fin_cal DESC
										";

										$res2 = mysqli_query( $db, $sql2 );
										$contador2 = 1;

										while( $fila2 = mysqli_fetch_assoc( $res2 ) ){

											if ( $contador2 == 1 ) {
								?>
												<tr style='color: green; font-family:  "Lucida Console", "Courier New", monospace;'>
				                                    <th scope="row"><?php echo $contador2; ?></th>
				                                    <td><?php echo $fila['fin_cal']; ?></td>
				                                    <td><?php echo $fila['id_mat4']; ?></td>
				                                    <td><?php echo $fila['id_alu_ram2']; ?></td>
				                                    <td>###</td>

				                                </tr>
								<?php
											} else {

												$id_cal = $fila2['id_cal'];


								?>
												<tr style='color: red; font-family:  "Lucida Console", "Courier New", monospace;'>
				                                    <th scope="row"><?php echo $contador2; ?></th>
				                                    <td><?php echo $fila['fin_cal']; ?></td>
				                                    <td><?php echo $fila['id_mat4']; ?></td>
				                                    <td><?php echo $fila['id_alu_ram2']; ?></td>
				                                    <td>
				                                    	<?php  
				                                    	
				           //                          		$sqlEliminar = "
															// 	DELETE FROM calificacion WHERE id_cal = '$id_cal'
															// ";

															// $resultadoEliminar = mysqli_query( $db, $sqlEliminar );

															// if ( !$resultadoEliminar ) {
															// 	echo $sqlEliminar;
															// } else {
															// 	echo 'Eliminado :(';
															// }
				                                    	?>
				                                    </td>

				                                </tr>



								<?php

											}

								?>

											
								<?php
											$contador2++;
										}

									}
                            	?>
                                
                            

                            </tbody>
                        </table>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-12 text-center">
                        
                        <button type="button" class="btn me-2 mb-2 btn-primary"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Basic Modal
                        </button>


                    </div>
                </div>
                
            </div>
        </div>
        
    </div>

<?php  

	include('inc/footer.php');

?>


<script>
    // $(document).ready(function () {
        $('#myTable').DataTable({
            scrollY: 200,
            scrollX: true,
        });
    // });
</script>