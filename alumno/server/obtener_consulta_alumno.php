<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE ALUMNO
	//alumnos_carrera.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];

	$sql = "
		SELECT * 
		FROM alu_ram
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_alu_ram = '$id_alu_ram'
	";

	$resultado = mysqli_query($db, $sql);

	$fila = mysqli_fetch_assoc($resultado);

	// DATOS ALUMNO
	$id_alu = $fila['id_alu'];
	$id_alu_ram = $fila['id_alu_ram'];

	$nombre = $fila['nom_alu']." ".$fila['app_alu']." ".$fila['apm_alu'];
	$fot_alu = $fila['fot_alu'];
	$bol_alu = $fila['bol_alu'];
	$tel_alu = $fila['tel_alu'];
	$ing_alu = $fila['ing_alu'];
	$cor_alu = $fila['cor_alu'];
	$pas_alu = $fila['pas_alu'];

	$nac_alu = $fila['nac_alu'];
	$cur_alu = $fila['cur_alu'];
	$pro_alu = $fila['pro_alu'];
	$gen_alu = $fila['gen_alu'];

	$dir_alu = $fila['dir_alu'];
	$cp_alu = $fila['cp_alu'];
	$col_alu = $fila['col_alu'];
	$del_alu = $fila['del_alu'];
	$ent_alu = $fila['ent_alu'];

	$tut_alu = $fila['tut_alu'];
	$tel2_alu = $fila['tel2_alu'];

	// DATOS alu_ram
	$bec_alu_ram = $fila['bec_alu_ram'];
	$bec2_alu_ram = $fila['bec2_alu_ram'];
	$car_alu_ram = $fila['car_alu_ram'];
	$est1_alu_ram = $fila['est1_alu_ram'];

	// DATOS CARRERA
	$id_ram = $fila['id_ram'];
	$programa = $fila['nom_ram'];
	$gra_ram = $fila['gra_ram'];
	$mod_ram = $fila['mod_ram'];
	$per_ram = $fila['per_ram'];
	$cic_ram = $fila['cic_ram'];

	$promedioPrograma = obtenerEvaluacionServer( $id_alu_ram );
	$avancePrograma = obtenerAvanceAlumnoCarreraServer( $id_alu_ram ); 

	$estatusAcademico = estatusAlumnoServer($id_alu_ram, $id_ram);
	$materiasAprobadas = obtenerMateriasAprobadasAlumnoServer( $id_alu_ram );
	$materiasPrograma = obtenerMateriasProgramaServer( $id_ram );

	$estatusDocumentacion = obtenerTextoEstatusDocumentacionAlumnoServer( $id_alu_ram );




             


	// DATOS PAGOS
	$estatusPago = obtenerEstatusPagoAlumnoServer( $id_alu_ram );

	$sal_alu = $fila['sal_alu'];
	if ( $sal_alu == NULL ) {
		$sal_alu = 0;
	} else {
		$sal_alu = round($fila['sal_alu'], 2);
	}
	$saldoHoy = obtenerSaldoAlumnoFechaHoyServer ( $id_alu_ram );
	$saldoGlobal = obtenerSaldoAlumnoGlobalServer ( $id_alu_ram );
	$pagadoHoy = obtenerPagadoAlumnoFechaHoyServer ( $id_alu_ram );
	$pagadoGlobal = obtenerPagadoAlumnoGlobalServer ( $id_alu_ram );
	$registrosPendientesFechaHoy = obtenerRegistrosPendientesFechaHoyServer ( $id_alu_ram );
	$registrosPendientesGlobal = obtenerRegistrosPendientesGlobalServer ( $id_alu_ram );

	$registrosPagadosFechaHoy = obtenerRegistrosPagadosFechaHoyServer ( $id_alu_ram );
	$registrosPagadosGlobal = obtenerRegistrosPagadosGlobalServer ( $id_alu_ram );
?>
<!-- LAYOUT ALUMNO -->
<div class="row">
	<div class="col-md-12">
	  	<div class="card bg-light p-2">
			<!-- LAYOUT TAB -->
		    <div class="modal-c-tabs">

		        <!-- Nav tabs -->
		        <ul class="nav md-pills nav-justified pills-info mt-4 mx-4" role="tablist" style="font-size: 15px;">
		          
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#panel1" role="tab">
							Información General
						</a>
					</li>


					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#panel2" role="tab">
						  	Información Académica
						</a>
					</li>


					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#panel3" role="tab">
						  	Información de Pagos
						</a>
					</li>

		        </ul>

		        <!-- TAB PANELS -->
		        <div class="tab-content pt-3">
		          
		          <!-- PANEL 1-->
		          <!-- GENERAL -->
		          <div class="tab-pane fade in show active" id="panel1" role="tabpanel">

		            <!--BODY-->
		            <div class="modal-body mb-1">

		            	<?php  
				    		if ( $tipoUsuario == 'Admin' || $tipoUsuario == 'Adminge' ) {
				    	?>
								<a href="alumnos_carrera.php?id_ram=<?php echo $id_ram; ?>">
						            Información General
						        </a>
				    	<?php
				    		} else {
				    	?>
								<span>
						            Información General
						        </span>
				    	<?php
				    		}

				    	?>
		   				<!-- CODIGO -->

		   				<table class="table table-bordered table-sm table-hover table-striped" id="myTableConsultaAlumno">
				            <thead class="letraMediana">
				                <tr>
				                    <th>
				                        #
				                    </th>
				                    <th>
				                        Campo
				                    </th>
				                    <th>
				                        Dato
				                    </th>
				                </tr>
				                
				            </thead>
				            <tbody>
				                <!-- GENERAL -->

				                <tr>
				                    <td class="letraMediana">
				                        1
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Nombre:</strong>
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $nombre; ?>
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        2
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Correo electrónico:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $cor_alu; ?>
				                    </td>
				                    
				                </tr>
				            
				                <tr>
				                    <td class="letraMediana">
				                        3
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Contraseña:</strong>
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $pas_alu; ?>
				                    </td>

				                    
				                </tr>


				                <tr>
				                    <td class="letraMediana">
				                        4
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Teléfono:</strong>
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $tel_alu; ?>
				          
				                    </td>

				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        5
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Ingreso:</strong>
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo fechaFormateadaCompacta($ing_alu); ?>
				          
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        6
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Nacimiento:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo fechaFormateadaCompacta($nac_alu); ?>
				         
				          
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        7
				                    </td>
				                    <td class="letraMediana">
				                        <strong>CURP:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $cur_alu; ?>
				          
				                    </td>
				                    
				                </tr>


				                <tr>
				                    <td class="letraMediana">
				                        8
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Procedencia:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $pro_alu; ?>
				          
				                    </td>

				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        9
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Dirección:</strong> 
				          
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $dir_alu; ?>
				          
				                    </td>

				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        10
				                    </td>
				                    <td class="letraMediana">
				                        <strong>CP:</strong>  
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $cp_alu; ?>
				          
				          
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        11
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Delegación/Municipio:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $del_alu; ?>
				          
				          
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        12
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Entidad:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $ent_alu; ?>
				         
				          
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        13
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Tutor:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $tut_alu; ?>
				    
				          
				                    </td>
				                    
				                </tr>

				                <tr>
				                    <td class="letraMediana">
				                        14
				                    </td>
				                    <td class="letraMediana">
				                        <strong>Contacto del tutor:</strong> 
				                    </td>
				                    <td class="letraMediana">
				                        <?php echo $tel2_alu; ?>
				                        
				                    </td>
				                    
				                </tr>

				                <!-- FIN GENERAL -->
				                
				            </tbody>
				            
				        </table>
				        <!-- FIN TABLA -->

						<!-- FIN CODIGO -->
		            </div>
		            <!--FIN BODY-->

		          </div>
		          <!-- FIN GENERAL -->
		          <!--/.FIN PANEL 1-->

		          <!--PANEL 2-->
		          <!-- ACADEMICO -->
		          <div class="tab-pane fade" id="panel2" role="tabpanel">

		            <!--BODY-->
		            <div class="modal-body">

		            	<?php  
			        		if ( $tipoUsuario == 'Admin' || $tipoUsuario == 'Adminge' ) {
			        	?>
								<a href="alumnos_carrera.php?id_ram=<?php echo $id_ram; ?>">
						            Información Académica
						        </a>
			        	<?php
			        		} else {
			        	?>
								<span>
						            Información Académica
						        </span>
			        	<?php
			        		}

			        	?>
		            	<!-- CODIGO -->
		            	<table class="table table-bordered table-sm table-hover table-striped" id="myTableConsultaAlumnoAcademico">
                            <thead class="letraMediana">
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Campo
                                    </th>
                                    <th>
                                        Dato
                                    </th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <!-- GENERAL -->

                                <tr>
                                    <td class="letraMediana">
                                        1
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Programa:</strong> 
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $programa; ?>
            
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        2
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Estatus Académico:</strong> 
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $estatusAcademico; ?>
    
            
                                    </td>
                                    
                                </tr>
                            
                                <tr>
                                    <td class="letraMediana">
                                        3
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Matrícula:</strong> 
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $bol_alu; ?>
                                    </td>

                                    
                                </tr>


                                <tr>
                                    <td class="letraMediana">
                                        4
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Nivel Educativo:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $gra_ram; ?>
                          
                                    </td>

                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        5
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Modalidad:</strong> 
    
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $mod_ram; ?>
                          
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        6
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Tipo de Periodos:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $per_ram; ?>
                         
                          
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        7
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Cantidad de Periodos:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $cic_ram; ?>
                          
                                    </td>
                                    
                                </tr>


                                <tr>
                                    <td class="letraMediana">
                                        8
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Promedio:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $promedioPrograma; ?>
                          
                                    </td>

                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        9
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Avance del Programa:</strong> 
            
                          
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $avancePrograma; ?>
                          
                                    </td>

                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        10
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Materias del Programa:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $materiasPrograma; ?>
                          
                          
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        11
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Materias Aprobadas del Programa:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $materiasAprobadas; ?>
                          
                          
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        12
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Estatus de documentación:</strong> 
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $estatusDocumentacion; ?>
                         
                          
                                    </td>
                                    
                                </tr>


                                <tr>
                                    <td class="letraMediana">
                                        13
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Condición del alumno :</strong> 
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $est1_alu_ram; ?>
                         
                          
                                    </td>
                                    
                                </tr>


                                <?php  
                                    if ( $estatusAcademico == 'Inscrito' ) {
                                ?>
                                    <tr>
                                        <td class="letraMediana">
                                            14
                                        </td>

                                        <td class="letraMediana">
                                            <strong>Carga:</strong>
                                        </td>

                                        <td class="letraMediana">
                                            <?php echo estatusAlumnoCargaServer($id_alu_ram, $id_ram); ?>
                                        
                                        </td>
                                    </tr>
                                        
                                        
                                <?php
                                    } else if ( $estatusAcademico == 'Pendiente' ) {
                                ?>
                                	<tr>
                                        <td class="letraMediana">
                                            14
                                        </td>

                                        <td class="letraMediana">
                                            <strong>Motivo :</strong>
                                        </td>

                                        <td class="letraMediana">
                                            <?php echo $fila['est2_alu_ram']; ?>
                                        
                                        </td>
                                    </tr>

                                <?php
                                    }
                                ?>

                                <!-- FIN GENERAL -->
                                
                            </tbody>
                            
                        </table>
                        <!-- FIN TABLA -->
						
						<!-- FIN CODIGO -->
		            </div>
		            <!-- FIN BODY -->
		          </div>
		          <!-- FIN ACADEMICO -->
		          <!--/.FIN PANEL 2-->


		          <!--PANEL 3-->
		          <!-- PAGOS -->
		          <div class="tab-pane fade" id="panel3" role="tabpanel">

		            <!--BODY-->
		            <div class="modal-body">
		            	<?php  
			        		if ( $tipoUsuario == 'Admin' || $tipoUsuario == 'Cobranza' ) {
			        	?>
								<a href="cobranza_alumno.php?id_alu_ram=<?php echo $id_alu_ram; ?>">
						            Información de Pagos
						        </a>
			        	<?php
			        		} else {
			        	?>
								<span>
						            Información de Pagos
						        </span>
			        	<?php
			        		}

			        	?>
		            	<!-- CODIGO -->
		            	<table class="table table-bordered table-sm table-hover table-striped" id="myTableConsultaAlumnoPagos">
                            <thead class="letraMediana">
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Campo
                                    </th>
                                    <th>
                                        Dato
                                    </th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <!-- GENERAL -->

                                <tr>
                                    <td class="letraMediana">
                                        1
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Estatus de Pago:</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $estatusPago; ?>
            
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        2
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Saldo a favor:</strong>
            
                                    </td>
                                    <td class="letraMediana">
                                        $<?php echo $sal_alu; ?>
    
            
                                    </td>
                                    
                                </tr>
                            
                                <tr>
                                    <td class="letraMediana">
                                        3
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Total registros pendientes ( Al día de hoy ):</strong> 
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $registrosPendientesFechaHoy; ?>
                                    </td>

                                    
                                </tr>


                                <tr>
                                    <td class="letraMediana">
                                        4
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Total registros pagados ( Al día de hoy ): </strong>
            
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $registrosPagadosFechaHoy; ?>
                          
                                    </td>

                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        5
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Saldo pendiente ( Al día de hoy ):</strong>
            
    
            
                                    </td>
                                    <td class="letraMediana">
                                        $<?php echo $saldoHoy; ?>
                          
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        6
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Saldo abonado ( Al día de hoy ):</strong>

            
                                    </td>
                                    <td class="letraMediana">
                                        $<?php echo $pagadoHoy; ?>
                         
                          
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        7
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Total registros pendientes ( General ): </strong>
            
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $registrosPendientesGlobal; ?>
                          
                                    </td>
                                    
                                </tr>


                                <tr>
                                    <td class="letraMediana">
                                        8
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Total registros pagados ( General ): </strong>
            
            
                                    </td>
                                    <td class="letraMediana">
                                        <?php echo $registrosPagadosGlobal; ?>
                          
                                    </td>

                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        9
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Saldo pendiente ( General ): </strong>
            
            
                          
                                    </td>
                                    <td class="letraMediana">
                                        $<?php echo $saldoGlobal; ?>
                          
                                    </td>

                                    
                                </tr>

                                <tr>
                                    <td class="letraMediana">
                                        10
                                    </td>
                                    <td class="letraMediana">
                                        <strong>Saldo abonado ( General ): </strong> 

            
                                    </td>
                                    <td class="letraMediana">
                                        $<?php echo $pagadoGlobal; ?>
                          
                          
                                    </td>
                                    
                                </tr>


                                <!-- FIN GENERAL -->
                                
                            </tbody>
                            
                        </table>
                        <!-- FIN TABLA -->
						
						<!-- FIN CODIGO -->
		            </div>
		            <!-- FIN BODY -->
		          </div>
		          <!-- FIN PAGOS -->
		          <!--/.FIN PANEL 3-->

		        </div>
		        <!-- FIN TAB PANNELS -->

		    </div>
		    <!-- FIN LAYOUT TAB -->

	  </div>


	</div>

</div>
<!--  FIN LAYOUT ALUMNO -->


<script>

	$('#tituloConsultaAlumno').html('<img src="../uploads/'+'<?php echo $fot_alu; ?>'+'" class="img-fluid avatar rounded-circle" width="30px" height="30px"> '+'<?php echo $nombre; ?>');

	
</script>


<script>
    $(document).ready(function () {


        $('#myTableConsultaAlumno').DataTable({
            
        
            dom: 'Bfrtlip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
            "pageLength": -1,
            buttons: [

            
                    'copy',
		            {
		                extend: 'excel',
		                messageTop: "<?php echo 'Información General del Alumno: '.$nombre; ?>"
		            },
		            {
                        extend: 'print',
                        messageTop: "<?php echo 'Información General del Alumno: '.$nombre; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo 'Información General del Alumno: '.$nombre; ?>",
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
        $('#myTableConsultaAlumno_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableConsultaAlumno_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableConsultaAlumno_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableConsultaAlumno_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableConsultaAlumno_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableConsultaAlumno_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableConsultaAlumno_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableConsultaAlumno_wrapper select').addClass('mdb-select');
        
        $('#myTableConsultaAlumno_wrapper .mdb-select').materialSelect();
        $('#myTableConsultaAlumno_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableConsultaAlumno_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);


        $('#myTableConsultaAlumnoAcademico').DataTable({
            
        
            dom: 'Bfrtlip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
            "pageLength": -1,
            buttons: [

            
                    'copy',
		            {
		                extend: 'excel',
		                messageTop: "<?php echo 'Información Académica del Alumno: '.$nombre; ?>"
		            },
		            {
                        extend: 'print',
                        messageTop: "<?php echo 'Información Académica del Alumno: '.$nombre; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo 'Información Académica del Alumno: '.$nombre; ?>",
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
        $('#myTableConsultaAlumnoAcademico_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableConsultaAlumnoAcademico_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableConsultaAlumnoAcademico_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableConsultaAlumnoAcademico_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableConsultaAlumnoAcademico_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableConsultaAlumnoAcademico_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableConsultaAlumnoAcademico_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableConsultaAlumnoAcademico_wrapper select').addClass('mdb-select');
        $('#myTableConsultaAlumnoAcademico_wrapper .mdb-select').materialSelect();
        $('#myTableConsultaAlumnoAcademico_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableConsultaAlumnoAcademico_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);


        $('#myTableConsultaAlumnoPagos').DataTable({
            
        
            dom: 'Bfrtlip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
            "pageLength": -1,
            buttons: [

            
                    'copy',
		            {
		                extend: 'excel',
		                messageTop: "<?php echo 'Información de Pagos del Alumno: '.$nombre; ?>"
		            },
		            {
                        extend: 'print',
                        messageTop: "<?php echo 'Información de Pagos del Alumno: '.$nombre; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo 'Información de Pagos del Alumno: '.$nombre; ?>",
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
        $('#myTableConsultaAlumnoPagos_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableConsultaAlumnoPagos_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableConsultaAlumnoPagos_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableConsultaAlumnoPagos_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableConsultaAlumnoPagos_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableConsultaAlumnoPagos_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableConsultaAlumnoPagos_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableConsultaAlumnoPagos_wrapper select').addClass('mdb-select');
        $('#myTableConsultaAlumnoPagos_wrapper .mdb-select').materialSelect();
        $('#myTableConsultaAlumnoPagos_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableConsultaAlumnoPagos_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);
     

    
    });
</script>