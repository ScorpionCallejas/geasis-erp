<?php  

    //ARCHIVO VIA AJAX PARA OBTENER TODO EL HISTORIAL ASOCIADO A UN PAGO
    //cobranza_alumno.php/
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_pag = $_POST['id_pag'];

    $sqlPago = "
		SELECT *
		FROM pago
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		WHERE id_pag = '$id_pag'
    ";    

    $resultadoPago = mysqli_query( $db, $sqlPago );

    $filaPago = mysqli_fetch_assoc( $resultadoPago );


    // DATOS PAGO
    $con_pag = $filaPago['con_pag'];
    $mon_pag = $filaPago['mon_pag'];
    $mon_ori_pag = $filaPago['mon_ori_pag'];
    $est_pag = $filaPago['est_pag'];

    // DATOS ALUMNO
    $nombreAlumno = $filaPago['app_alu']." ".$filaPago['apm_alu']." ".$filaPago['nom_alu'];


    //
    
?>


<!-- LAYOUT TAB -->
<div class="modal-c-tabs">




    <!-- Nav tabs -->
    <ul class="nav md-pills nav-justified pills-info mt-4 mx-4" role="tablist" style="font-size: 10px;">
      
	  <li class="nav-item">
	    <a class="nav-link active" data-toggle="tab" href="#panel1" role="tab">
	      Resumen
	    </a>
	  </li>


	  <li class="nav-item">
	    <a class="nav-link" data-toggle="tab" href="#panel2" role="tab">
	        Historial de transacciones
	    </a>
	  </li>


    </ul>

    <!-- TAB PANELS -->
    <div class="tab-content pt-3">
      
      <!-- PANEL 1-->
      <div class="tab-pane fade in show active" id="panel1" role="tabpanel">


        
        <!--BODY-->
        <div class="modal-body mb-1 border">
      	<!-- CODIGO -->

			<!--Accordion wrapper-->
			<div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">


				<!-- CONCEPTO PAGO -->
				<div class="card">

				  	<!-- CONCEPTO PAGO -->
				  	<div class="card-header" role="tab" id="headingTwo1">
				      <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="con_pag"
				        aria-expanded="false" aria-controls="con_pag">


				        <div class="row">
				        	<div class="col-md-12 text-center" id="botonesFormatoDigital">
				        		
				        	</div>
				        	
				        </div>
				        <br>

						<!-- FORMATOS DIGITALES -->
				        <div class="row">
				        	<div class="col-md-12" style="display: none;">
				        		<table id="myTableFormatoDigital">
									<thead>
										<tr>
											<th>--</th>
											<th><?php echo $con_pag; ?></th>
											<th>--</th>
											<th>$ <?php echo $mon_ori_pag; ?></th>
										</tr>
									</thead>
									<tbody>

										<!-- RECARGO -->
										<tr>
											<td></td>
											<td>Recargos</td>
											<td></td>
											<td>
												$ 
										        <?php 
										        	if ( obtenerTotalRecargoPagoServer( $id_pag ) != NULL ) {

										        		echo round( obtenerTotalRecargoPagoServer( $id_pag ), 2 );
										        	
										        	} else {
										        	
										        		echo "0";
										        	
										        	}
										          	 
										        ?>
											</td>
										</tr>


										<?php  
											$sqlRecargo = "
												SELECT *
												FROM recargo_pago
												WHERE id_pag5 = '$id_pag'
												ORDER BY id_rec_pag DESC
											";

											$resultadoRecargo = mysqli_query( $db, $sqlRecargo );

											if ( $resultadoRecargo ) {
												$i = 1;
												while( $filaRecargo = mysqli_fetch_assoc( $resultadoRecargo ) ) {

										?>
													<tr>
														<td>
															<?php  
																echo $i; $i++;
															?>
														</td>

														<td>
															Recargo
														</td>


														<td>
															<?php echo fechaFormateadaCompacta( $filaRecargo['fec_rec_pag'] ); ?>
														</td>


														<td>
															$ <?php 
																echo round( $filaRecargo['mon_rec_pag'], 2 ); 
															?>

														</td>


													</tr>

											<?php
												}	
											?>

									<?php

											} else {
												echo $sqlRecargo;
											}
									?>
										<!-- FIN RECARGO -->

										<!-- ABONADO -->
										<tr>
											<td></td>
											<td>Abonado</td>
											<td></td>
											<td>
												$
										        <?php 
										        	if ( obtenerTotalAbonadoPagoServer( $id_pag ) != NULL ) {

										        		echo round( obtenerTotalAbonadoPagoServer( $id_pag ), 2);
										        	
										        	} else {
										        	
										        		echo "0";
										        	
										        	}
										          	 
										        ?> 
											</td>
										</tr>

										<?php  
											$sqlAbonado = "
												SELECT *
												FROM abono_pago
												WHERE id_pag1 = '$id_pag'
												ORDER BY id_abo_pag DESC
											";

											$resultadoAbonado = mysqli_query( $db, $sqlAbonado );

											if ( $resultadoAbonado ) {
												$i = 1;
												while( $filaAbonado = mysqli_fetch_assoc( $resultadoAbonado ) ) {

										?>
													<tr>
														<td>
															<?php  
																echo $i; $i++;
															?>
														</td>

														<td>
															<?php echo $filaAbonado['tip_abo_pag']; ?>
														</td>

														

														<td>
															<?php echo fechaFormateadaCompacta( $filaAbonado['fec_abo_pag'] ); ?>
														</td>


														<td>
															$ <?php echo round( $filaAbonado['mon_abo_pag'], 2 ); ?>
														</td>


													</tr>

											<?php
												}	
											?>

									<?php

											} else {
												echo $sqlAbonado;
											}
									?>
										<!-- FIN ABONADO -->
										
										<!-- CONDONADO -->
										<tr>
											<td></td>
											<td>Condonado</td>
											<td></td>
											<td>
										        $
										        <?php 
										        	if ( obtenerMontoCondonadoPagoServer( $id_pag ) != NULL ) {

										        		echo round( obtenerMontoCondonadoPagoServer( $id_pag ), 2 );
										        	
										        	} else {
										        	
										        		echo "0";
										        	
										        	}
										          	 
										        ?>
											</td>
										</tr>

										<?php  
											$sqlCondonado = "
												SELECT *
												FROM condonacion_pago
												WHERE id_pag2 = '$id_pag'
												ORDER BY id_con_pag DESC
											";

											$resultadoCondonado = mysqli_query( $db, $sqlCondonado );

											if ( $resultadoCondonado ) {
												$i = 1;
												while( $filaCondonado = mysqli_fetch_assoc( $resultadoCondonado ) ) {

										?>
													<tr>
														<td>
															<?php  
																echo $i; $i++;
															?>
														</td>

														<td>
															<?php echo $filaCondonado['est_con_pag']; ?>
														</td>


														<td>
															<?php echo fechaFormateadaCompacta( $filaCondonado['fec_con_pag'] ); ?>
														</td>


														<td>
															$ <?php echo round( $filaCondonado['can_con_pag'], 2); ?>
														</td>


													</tr>

											<?php
												}	
											?>

									<?php

											} else {
												echo $sqlCondonado;
											}
									?>
										

										<!-- FIN CONDONADO -->

									</tbody>
				        			
				        		</table>
				        	</div>
				        </div>
				        <!-- FIN FORMATOS DIGITALES -->

				        <div class="row">
				        	<div class="col-md-1">

				        	</div>

				        	<div class="col-md-5 text-left">
				        		<h6 class="mb-0 font-weight-normal black-text">
						          <?php echo $con_pag; ?>
						        </h6>
				        	</div>

				        	<div class="col-md-6 text-right">
				        		<h6 class="mb-0 font-weight-normal black-text">
						          $ <?php echo $mon_ori_pag; ?>
						        </h6>
				        	</div>
				        </div>
				        
				      </a>
				    </div>
				  	<!-- FIN CONCEPTO PAGO -->
				</div>
				<!-- FIN CONCEPTO PAGO -->
				
				<!-- RECARGO -->
				<div class="card">

				    <!-- RECARGO HEADER -->
				    <div class="card-header" role="tab" id="headingTwo1">
				      <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#totalRecargo"
				        aria-expanded="false" aria-controls="totalRecargo">

				        <div class="row">
				        	<div class="col-md-1" title="Haz click para ver los detalles...">
				        		<i class="fas fa-angle-down rotate-icon black-text"></i>
				        	</div>
				        	
				        	<div class="col-md-5 text-left">
				        		
				        		<h6 class="mb-0 font-weight-normal  black-text">
						          Recargos
						        </h6>
				        	</div>

				        	<div class="col-md-6 text-right">
				        		<h6 class="mb-0 font-weight-normal  black-text">
						        $ 
						        <?php 
						        	if ( obtenerTotalRecargoPagoServer( $id_pag ) != NULL ) {

						        		echo round( obtenerTotalRecargoPagoServer( $id_pag ), 2 );
						        	
						        	} else {
						        	
						        		echo "0";
						        	
						        	}
						          	 
						        ?> 
						        </h6>
				        	</div>
				        </div>

				      </a>
				    </div>
				    <!-- FIN RECARGO HEADER -->

				    <!-- CONTENIDO RECARGO -->
				    <div id="totalRecargo" class="collapse" role="tabpanel" aria-labelledby="headingTwo1"
				      data-parent="#accordionEx1">
						<div class="card-body">
						<!-- TABLA RECARGO -->
						<table id="myTableRecargo" class="table table-hover table-striped  table-sm text-center" cellspacing="0" width="100%">
						

							<tbody>
								<?php  
									$sqlRecargo = "
										SELECT *
										FROM recargo_pago
										WHERE id_pag5 = '$id_pag'
										ORDER BY id_rec_pag DESC
									";

									$resultadoRecargo = mysqli_query( $db, $sqlRecargo );

									if ( $resultadoRecargo ) {
										$i = 1;
										while( $filaRecargo = mysqli_fetch_assoc( $resultadoRecargo ) ) {

								?>
											<tr class="black-text">
												<td class="letraPequena font-weight-normal text-left">
													<?php  
														echo $i; $i++;
													?>
												</td>

												<td class="letraPequena font-weight-normal">
													Recargo
												</td>


												<td class="letraPequena font-weight-normal ">
													<?php echo fechaFormateadaCompacta( $filaRecargo['fec_rec_pag'] ); ?>
												</td>


												<td class="letraPequena font-weight-normal text-right">
													$ <?php 
														echo round( $filaRecargo['mon_rec_pag'], 2 ); 
													?>

												</td>


											</tr>

									<?php
										}	
									?>

							<?php

									} else {
										echo $sqlRecargo;
									}
							?>
								
							</tbody>
						</table>
						<!-- FIN TABLA RECARGO -->
						</div>
				    </div>
				    <!-- FIN CONTENIDO RECARGO -->

			  	</div>
			  	<!-- FIN RECARGO -->





				<!-- ABONADO -->
				<div class="card">

				    <!-- ABONADO HEADER -->
				    <div class="card-header" role="tab" id="headingTwo1">
				      <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#totalAbonado"
				        aria-expanded="false" aria-controls="totalAbonado">

				        <div class="row">
				        	<div class="col-md-1" title="Haz click para ver los detalles...">
				        		<i class="fas fa-angle-down rotate-icon black-text"></i>
				        	</div>
				        	
				        	<div class="col-md-5 text-left">
				        		
				        		<h6 class="mb-0 font-weight-normal  black-text">
						          Abonado
						        </h6>
				        	</div>

				        	<div class="col-md-6 text-right">
				        		<h6 class="mb-0 font-weight-normal  black-text">
						        $
						        <?php 
						        	if ( obtenerTotalAbonadoPagoServer( $id_pag ) != NULL ) {

						        		echo round( obtenerTotalAbonadoPagoServer( $id_pag ), 2);
						        	
						        	} else {
						        	
						        		echo "0";
						        	
						        	}
						          	 
						        ?> 
						        </h6>
				        	</div>
				        </div>

				      </a>
				    </div>
				    <!-- FIN ABONADO HEADER -->

				    <!-- CONTENIDO ABONADO -->
				    <div id="totalAbonado" class="collapse" role="tabpanel" aria-labelledby="headingTwo1"
				      data-parent="#accordionEx1">
						<div class="card-body">
						<!-- TABLA ABONADO -->
						<table id="myTableAbonado" class="table table-hover table-striped  table-sm text-center" cellspacing="0" width="100%">
						

							<tbody>
								<?php  
									$sqlAbonado = "
										SELECT *
										FROM abono_pago
										WHERE id_pag1 = '$id_pag'
										ORDER BY id_abo_pag DESC
									";

									$resultadoAbonado = mysqli_query( $db, $sqlAbonado );

									if ( $resultadoAbonado ) {
										$i = 1;
										while( $filaAbonado = mysqli_fetch_assoc( $resultadoAbonado ) ) {

								?>
											<tr class="black-text">
												<td class="letraPequena font-weight-normal text-left">
													<?php  
														echo $i; $i++;
													?>
												</td>

												<td class="letraPequena font-weight-normal ">
													<?php echo $filaAbonado['tip_abo_pag']; ?>
												</td>

												

												<td class="letraPequena font-weight-normal ">
													<?php echo fechaFormateadaCompacta( $filaAbonado['fec_abo_pag'] ); ?>
												</td>


												<td class="letraPequena font-weight-normal text-right">
													$ <?php echo round( $filaAbonado['mon_abo_pag'], 2 ); ?>
												</td>


											</tr>

									<?php
										}	
									?>

							<?php

									} else {
										echo $sqlAbonado;
									}
							?>
								
							</tbody>
						</table>
						<!-- FIN TABLA ABONADO -->
						</div>
				    </div>
				    <!-- FIN CONTENIDO ABONADO -->

			  	</div>
			  	<!-- FIN ABONADO -->
				

				<!-- CONDONADO -->
				<div class="card">

				    <!-- CONDONADO HEADER -->
				    <div class="card-header" role="tab" id="headingTwo1">
				      <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#totalCondonado"
				        aria-expanded="false" aria-controls="totalCondonado">

				        <div class="row">
				        	<div class="col-md-1" title="Haz click para ver los detalles...">
				        		<i class="fas fa-angle-down rotate-icon black-text"></i>
				        	</div>
				        	
				        	<div class="col-md-5 text-left">
				        		
				        		<h6 class="mb-0 font-weight-normal  black-text">
						          Condonado
						        </h6>
				        	</div>

				        	<div class="col-md-6 text-right">
				        		<h6 class="mb-0 font-weight-normal  black-text">
						        $
						        <?php 
						        	if ( obtenerMontoCondonadoPagoServer( $id_pag ) != NULL ) {

						        		echo round( obtenerMontoCondonadoPagoServer( $id_pag ), 2 );
						        	
						        	} else {
						        	
						        		echo "0";
						        	
						        	}
						          	 
						        ?>

						        </h6>
				        	</div>
				        </div>

				      </a>
				    </div>
				    <!-- FIN CONDONADO HEADER -->

				    <!-- CONTENIDO CONDONADO -->
				    <div id="totalCondonado" class="collapse" role="tabpanel" aria-labelledby="headingTwo1"
				      data-parent="#accordionEx1">
						<div class="card-body">
						<!-- TABLA CONDONADO -->
						<table id="myTableCondonado" class="table table-hover table-striped  table-sm text-center" cellspacing="0" width="100%">
						

							<tbody>
								<?php  
									$sqlCondonado = "
										SELECT *
										FROM condonacion_pago
										WHERE id_pag2 = '$id_pag'
										ORDER BY id_con_pag DESC
									";

									$resultadoCondonado = mysqli_query( $db, $sqlCondonado );

									if ( $resultadoCondonado ) {
										$i = 1;
										while( $filaCondonado = mysqli_fetch_assoc( $resultadoCondonado ) ) {

								?>
											<tr class="black-text">
												<td class="letraPequena font-weight-normal text-left">
													<?php  
														echo $i; $i++;
													?>
												</td>

												<td class="letraPequena font-weight-normal ">
													<?php echo $filaCondonado['est_con_pag']; ?>
												</td>


												<td class="letraPequena font-weight-normal ">
													<?php echo fechaFormateadaCompacta( $filaCondonado['fec_con_pag'] ); ?>
												</td>


												<td class="letraPequena font-weight-normal text-right">
													$ <?php echo round( $filaCondonado['can_con_pag'], 2); ?>
												</td>


											</tr>

									<?php
										}	
									?>

							<?php

									} else {
										echo $sqlCondonado;
									}
							?>
								
							</tbody>
						</table>
						<!-- FIN TABLA CONDONADO -->
						</div>
				    </div>
				    <!-- FIN CONTENIDO CONDONADO -->

			  	</div>
			  	<!-- FIN CONDONADO -->


			  	<!-- CONCEPTO PAGO -->
				<div class="card">

				  	<!-- CONCEPTO PAGO -->
				  	<div class="card-header" role="tab" id="headingTwo1">
				      <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="con_pag"
				        aria-expanded="false" aria-controls="con_pag">
				        
				        <div class="row">
				        	<div class="col-md-1">

				        	</div>
							

				        	<div class="col-md-5 text-left">
				  				<h6 class="mb-0 font-weight-normal  black-text">
						          <?php echo obtenerEstatusPago( $id_pag ); ?>
						        </h6>
				        	</div>

				        	<div class="col-md-6 text-right">

				        		<?php  
				        			if ( $est_pag == 'Pendiente' ) {
				        		?>
										<h6 class="mb-0 font-weight-normal  text-danger">
								          $ <?php echo round( $mon_pag, 2 ); ?>
								        </h6>
				        		<?php
				        			} else {
				        		?>
				        				<h6 class="mb-0 font-weight-normal  text-success">
								          $ <?php echo round( obtenerTotalAbonadoPagoServer( $id_pag ), 2 ); ?>
								        </h6>

				        		<?php
				        			}
				        		?>
				        		
				        	</div>
				        </div>
				        
				      </a>
				    </div>
				  	<!-- FIN CONCEPTO PAGO -->
				</div>
				<!-- FIN CONCEPTO PAGO -->
			  

			</div>
			<!-- Accordion wrapper -->
      		


    	<!-- FIN CODIGO -->
        </div>
        <!--FIN BODY-->

      </div>

      <!--/.FIN PANEL 1-->

      <!--PANEL 2-->
      <div class="tab-pane fade" id="panel2" role="tabpanel">

        <!--BODY-->
        <div class="modal-body">  
        <!-- CODIGO -->

			<!-- TABLA -->
			<table id="myTableHistorial" class="table table-hover table-striped table-bordered table-sm text-center table-responsive" cellspacing="0" width="100%">
				<thead class="bg-info text-white">
					<tr>
						<th class="letraPequena font-weight-normal ">#</th>
						<th class="letraPequena font-weight-normal ">Resumen</th>
						<th class="letraPequena font-weight-normal ">Fecha de Alta</th>
						<th class="letraPequena font-weight-normal ">Tipo de Movimiento</th>
						<th class="letraPequena font-weight-normal ">Medio de Notificación</th>
						<th class="letraPequena font-weight-normal ">Estatus en Sistema</th>
						<th class="letraPequena font-weight-normal ">Responsable</th>
					</tr>
				</thead>

				<?php


					if ( isset( $_POST['medio'] ) ) {
				    // SI EXISTE MEDIO SE ESPECIFICA EL MISMO
				    	$medio = $_POST['medio'];

				    	if ( $medio == 'Correo' ) {

				    		$sqlHistorial = "
								SELECT *
								FROM historial_pago
								WHERE id_pag4 = '$id_pag' AND med_his_pag = '$medio'
							";
				    		
				    	}else if ( $medio == 'SMS' ) {

				    		$sqlHistorial = "
								SELECT *
								FROM historial_pago
								WHERE id_pag4 = '$id_pag' AND med_his_pag = '$medio'
							";
				    		
				    	}else if ( $medio == 'Whatsapp' ) {
				    		
				    		$sqlHistorial = "
								SELECT *
								FROM historial_pago
								WHERE id_pag4 = '$id_pag' AND med_his_pag = '$medio'
							";
				    	}

				    }else{
				   	// SI NO, ES EL GLOBAL
				    	$sqlHistorial = "
							SELECT *
							FROM historial_pago
							WHERE id_pag4 = '$id_pag'
							ORDER BY id_his_pag DESC
						";
				    	

				    }

					

					$resultadoHistorial = mysqli_query($db, $sqlHistorial);
					$i = 1;

					while($filaHistorial = mysqli_fetch_assoc($resultadoHistorial)){

				?>
					<tr>
						<td class="letraPequena font-weight-normal "><?php echo $i; $i++; ?></td>
						<td class="letraPequena font-weight-normal "><?php echo $filaHistorial['con_his_pag']; ?></td>
						<td class="letraPequena font-weight-normal "><?php echo fechaFormateadaCompacta($filaHistorial['fec_his_pag']); ?></td>
						<td class="letraPequena font-weight-normal "><?php echo $filaHistorial['tip_his_pag']; ?></td>
						<td class="letraPequena font-weight-normal "><?php echo $filaHistorial['med_his_pag']; ?></td>
						<td class="letraPequena font-weight-normal "><?php echo $filaHistorial['est_his_pag']; ?></td>
						<td class="letraPequena font-weight-normal "><?php echo $filaHistorial['res_his_pag']; ?></td>

					</tr>


				<?php
					} 

				?>
			</table>

			<!-- FIN TABLA -->
    
    	<!-- FIN CODIGO -->
        </div>
        <!-- FIN BODY -->
      </div>

      <!--/.FIN PANEL 2-->


      

    </div>
    <!-- FIN TAB PANNELS -->

</div>
<!-- FIN LAYOUT TAB -->



<script>
    $(document).ready(function () {


        $('#myTableHistorial').DataTable({
            
        
            dom: 'Bfrtlip',
            
            buttons: [

            
                    'copy',
		            {
		                extend: 'excel',
		                messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>"
		            },
		            {
                        extend: 'print',
                        messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

            ],

            "language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
        });
        $('#myTableHistorial_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableHistorial_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableHistorial_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableHistorial_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableHistorial_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableHistorial_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableHistorial_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableHistorial_wrapper select').addClass('mdb-select');
        $('#myTableHistorial_wrapper .mdb-select').materialSelect();
        $('#myTableHistorial_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableHistorial_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);

     

    	$('#myTableResumen').DataTable({
            
        
            dom: 'Bfrtlip',
            
            buttons: [

            
                    'copy',
                {
                    extend: 'excel',
                    messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>"
                },
                {
                        extend: 'print',
                        messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

            ],

            "language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
        });
        $('#myTableResumen_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableResumen_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableResumen_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableResumen_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableResumen_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableResumen_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableResumen_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableResumen_wrapper select').addClass('mdb-select');
        $('#myTableResumen_wrapper .mdb-select').materialSelect();
        $('#myTableResumen_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableResumen_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);

        
    });



	$('#myTableFormatoDigital').DataTable({
            
        
        dom: 'B',
        "ordering": false,
        
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],        
        "pageLength": -1,

        buttons: [

           
            {
                extend: 'excel',
                messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>"
            },
            {
                    extend: 'print',
                    messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                    exportOptions: {
	                    columns: [ 0, 1, 2, 3 ]
	                }
                },

                {
                    extend: 'pdf',
                    messageTop: "<?php echo 'Historial de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                    exportOptions: {
	                    columns: [ 0, 1, 2, 3 ]
	                }
                },

        ],

        "language": {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Buscar:",
                        "sUrl":            "",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
    });
 
    var botones = $('#myTableFormatoDigital_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
    //console.log(botones);

    $( '#botonesFormatoDigital' ).html( botones );


    

</script>