<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE ALUMNO
	//alumnos_carrera.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pag = $_POST['id_pag'];

	$sql = "
		SELECT * 
		FROM pago
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_pag = '$id_pag'
	";

	// echo $sql;

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

	$fec_pag = $fila['fec_pag'];
	$res_pag = $fila['res_pag'];
	$fol_pag = $fila['fol_pag'];
	$con_pag = $fila['con_pag'];
	$est_pag = $fila['est_pag'];
	
	if ( $fila['pag_pag'] == NULL ) {
		$pag_pag = "Pendiente";
	} else {
		$pag_pag = $fila['pag_pag'];
	}

	$mon_ori_pag = $fila['mon_ori_pag'];
	$mon_pag = $fila['mon_pag'];
	$totalAbonado = obtenerTotalAbonadoPagoServer( $id_pag );
	$pro_pag = $fila['pro_pag'];
	$ini_pag = $fila['ini_pag'];
	$fin_pag = $fila['fin_pag'];
	$pri_pag = $fila['pri_pag'];
	$tip1_pag = $fila['tip1_pag'];
	$des_pag = $fila['des_pag'];

	$int_pag = $fila['int_pag'];

	$tip2_pag = $fila['tip2_pag'];
	$car_pag = $fila['car_pag'];
	$totalCondonaciones = obtenerTotalCondonacionesPagoServer( $id_pag );
	$totalConvenios = obtenerTotalConveniosPagoServer( $id_pag );

	if ( $fila['obs_pag'] == NULL ) {
		$obs_pag = "Nulo";
	} else {
		$obs_pag = $fila['obs_pag'];
	}
	
	$totalWhatsapps = obtenerTotalWhatsappPagoServer( $id_pag );
	$totalSms = obtenerTotalSmsPagoServer( $id_pag );
	$totalEmails = obtenerTotalEmailPagoServer( $id_pag );
	$totalHistorial = obtenerTotalHistorialPagoServer( $id_pag );
	$totalCondonado = obtenerMontoCondonadoPagoServer( $id_pag );


	// $sal_alu = $fila['sal_alu'];
	// if ( $sal_alu == NULL ) {
	// 	$sal_alu = 0;
	// } else {
	// 	$sal_alu = round($fila['sal_alu'], 2);
	// }


	// $saldoHoy = obtenerSaldoAlumnoFechaHoyServer ( $id_alu_ram );
	// $saldoGlobal = obtenerSaldoAlumnoGlobalServer ( $id_alu_ram );
	// $pagadoHoy = obtenerPagadoAlumnoFechaHoyServer ( $id_alu_ram );
	// $pagadoGlobal = obtenerPagadoAlumnoGlobalServer ( $id_alu_ram );
	// $registrosPendientesFechaHoy = obtenerRegistrosPendientesFechaHoyServer ( $id_alu_ram );
	// $registrosPendientesGlobal = obtenerRegistrosPendientesGlobalServer ( $id_alu_ram );

	// $registrosPagadosFechaHoy = obtenerRegistrosPagadosFechaHoyServer ( $id_alu_ram );
	// $registrosPagadosGlobal = obtenerRegistrosPagadosGlobalServer ( $id_alu_ram );




?>
<!-- LAYOUT ALUMNO -->
<div class="row">
	<div class="col-md-12">
	  	<div class="card bg-light p-2">

				
				
				<span>
		            Información del cobro "<?php echo $con_pag; ?>"
		        </span>
		    	
				<table class="table table-bordered table-sm table-hover table-striped" id="myTableConsultaPago">
		            <thead class="letraMediana font-weight-normal">
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
		                    <td class="letraMediana font-weight-normal">
		                        1
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Nombre:</strong>
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $nombre; ?>
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        2
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Programa</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $programa; ?>
		                    </td>
		                    
		                </tr>
		            
		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        3
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Modalidad:</strong>
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $mod_ram; ?>
		                    </td>

		                    
		                </tr>


		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        4
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Estatus Académico:</strong>
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $estatusAcademico; ?>
		          
		                    </td>

		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        5
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Fecha de Alta:</strong>
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo fechaFormateadaCompacta($fec_pag); ?>
		          
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        6
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Responsable del Alta:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $res_pag; ?>
		         
		          
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        7
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Folio:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $fol_pag; ?>
		          
		                    </td>
		                    
		                </tr>


		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        8
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Procedencia:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $pro_alu; ?>
		          
		                    </td>

		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        9
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Concepto:</strong> 
		          
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $con_pag; ?>
		          
		                    </td>

		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        10
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Estatus del pago:</strong>  
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $est_pag; ?>
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        11
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Fecha de liquidación:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $pag_pag; ?>
		          
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        12
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Saldo original:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        $<?php echo $mon_ori_pag; ?>
		          
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        13
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Saldo:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        $<?php echo $mon_pag; ?>
		                    </td>
		                    
		                </tr>

		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        14
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Abonado:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        $<?php echo $totalAbonado; ?>
		                        
		                    </td>
		                    
		                </tr>


		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        15
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Fecha de descuento por pronto pago:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo fechaFormateadaCompacta($pro_pag); ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        16
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Fecha de inicio:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo fechaFormateadaCompacta($ini_pag); ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        17
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Fecha de vencimiento:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo fechaFormateadaCompacta($fin_pag); ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        18
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Prioridad:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $pri_pag; ?>
		                        
		                    </td>
		                    
		                </tr>




		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        19
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Descuento:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php
		                        	if ( $tip1_pag == 'Porcentual' ) {

		                        		echo $des_pag." %"; 
		                        	
		                        	} else if ( $tip1_pag == 'Monetario' ) {
		                        	
		                        		echo "$ ".$des_pag; 
		                        	
		                        	}
		                        	
		                        ?>
		                        
		                    </td>
		                    
		                </tr>


		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        20
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Periodicidad:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $int_pag; ?>
		                        
		                    </td>
		                    
		                </tr>





		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        21
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Cargo:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php
		                        	if ( $tip2_pag == 'Porcentual' ) {

		                        		echo $car_pag." %"; 
		                        	
		                        	} else if ( $tip2_pag == 'Monetario' ) {
		                        	
		                        		echo "$ ".$car_pag; 
		                        	
		                        	}
		                        	
		                        ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        22
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Condonación de cobro:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $totalCondonaciones; ?>
		                        
		                    </td>
		                    
		                </tr>


		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        23
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Convenios de fecha:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $totalConvenios; ?>
		                        
		                    </td>
		                    
		                </tr>


		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        24
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Observaciones:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $obs_pag; ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        25
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Whatsapps:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $totalWhatsapps; ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        26
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>SMS:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $totalSms; ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        27
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Emails:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $totalEmails; ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        28
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Historial:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <?php echo $totalHistorial; ?>
		                        
		                    </td>
		                    
		                </tr>



		                <tr>
		                    <td class="letraMediana font-weight-normal">
		                        29
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        <strong>Monto condonado:</strong> 
		                    </td>
		                    <td class="letraMediana font-weight-normal">
		                        $<?php echo $totalCondonado; ?>
		                        
		                    </td>
		                    
		                </tr>



		                <!-- FIN GENERAL -->
		                
		            </tbody>
		            
		        </table>
		        <!-- FIN TABLA -->


	  </div>


	</div>

</div>
<!--  FIN LAYOUT ALUMNO -->

<img id="img" style="display: none;">


<script>

	$('#tituloConsultaPago').html('<img src="../uploads/'+'<?php echo $fot_alu; ?>'+'" class="img-fluid avatar rounded-circle" width="30px" height="30px"> '+'<?php echo $nombre; ?>');

	function toDataURL(url, callback) {
	  var xhr = new XMLHttpRequest();
	  xhr.onload = function() {
	    var reader = new FileReader();
	    reader.onloadend = function() {
	      callback(reader.result);
	    }
	    reader.readAsDataURL(xhr.response);
	  };
	  xhr.open('GET', url);
	  xhr.responseType = 'blob';
	  xhr.send();
	}

	toDataURL('../uploads/<?php echo $fotoPlantel; ?>', function( dataUrl ) {
	  $("#img").attr( "src", dataUrl );

	});

	// var logoPlantel = $("#img").attr("src");



	// // var logoPlantel = obtenerBlob();

	// alert(logoPlantel);

	

	
</script>


<script>
    $(document).ready(function () {

    	<?php 
    		$metadatosPlantel = fechaFormateada( date('Y-m-d') )." / ".$direccionPlantel." / ".$telefonoPlantel." / ".$nombrePlantel." / ".$esloganPlantel;
    	?>
        $('#myTableConsultaPago').DataTable({
            
        
            dom: 'Bfrtlip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
            "pageLength": -1,
            buttons: [

            
                    'copy',
		            {
		                extend: 'excel',
		               
		                messageTop: "<?php echo $metadatosPlantel; ?>",
		                customize: function(xlsx) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];
							//All cells
							$('row c', sheet).attr('s', '25');
						
						}
		                
		            },
		            {
                        extend: 'print',
                        messageTop: "<?php echo $metadatosPlantel; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        download: 'open',
                        alignment: 'right',
                        messageTop: "<?php echo $metadatosPlantel; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function ( doc ) {
		                    doc.content.splice( 1, 0, {
		                        margin: [ 0, 0, 0, 12 ],
		                        alignment: 'left',
		                        image: $("#img").attr("src"),
		                        fit: [40, 40]
		                    });
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
        $('#myTableConsultaPago_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableConsultaPago_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableConsultaPago_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableConsultaPago_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableConsultaPago_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableConsultaPago_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableConsultaPago_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableConsultaPago_wrapper select').addClass('mdb-select');
        $('#myTableConsultaPago_wrapper .mdb-select').materialSelect();
        $('#myTableConsultaPago_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableConsultaPago_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);

        // $("#myTableConsultaPago").css({
        // 	property1: 'value1',
        // 	property2: 'value2'
        // });


    
    });
</script>