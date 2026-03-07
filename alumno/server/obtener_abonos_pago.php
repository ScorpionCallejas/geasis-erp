<?php  

    //ARCHIVO VIA AJAX PARA OBTENER TODOS LOS ABONOS ASOCIADOS A UN PAGO
    //entregable.php/
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

    // DATOS ALUMNO
    $nombreAlumno = $filaPago['app_alu']." ".$filaPago['apm_alu']." ".$filaPago['nom_alu'];


    //
    

?>

<!-- INDICADORES -->
<div class="card bg-light text-center">
	<div class="card-body bg-light">
		Concepto: <?php echo $con_pag; ?>
		<br>

		<div class="row">

			<div class="col-md-4">
				<label class="form-check-label letraPequena" style="line-height: 100%;">
					Saldo Original
				</label>  
				<h5>
					<span class="badge badge-info">
					  $<?php echo round($mon_ori_pag, 2); ?>
					</span>
				</h5>
			</div>

			<div class="col-md-4">
				<label class="form-check-label letraPequena" style="line-height: 100%;">
					Saldo Pendiente
				</label>  
				<h5>
					<span class="badge badge-info">
					  $<?php echo round($mon_pag, 2); ?>
					</span>
				</h5>
			</div>

			<div class="col-md-4">
				<label class="form-check-label letraPequena" style="line-height: 100%;">
					Saldo Pagado 
				</label>  
				<h5>
					<span class="badge badge-info" id="saldoPagadoModalAbono">
					  
					</span>
				</h5>
			</div>
		</div>
		
		

		


		

	</div>
</div>
<!-- FIN INDICADORES -->

<table id="myTableAbonos" class="table table-hover table-bordered table-sm text-center" cellspacing="0" width="100%">
	<thead class="bg-info text-white">
		<tr>
			<th class="letraPequena">#</th>
			<th class="letraPequena">Monto Abonado</th>
			<th class="letraPequena">Fecha de Alta</th>
			<th class="letraPequena">Tipo de Pago</th>
			<th class="letraPequena">Responsable</th>
		</tr>
	</thead>

	<?php

		$sqlAbonos = "
			SELECT *
			FROM abono_pago
			WHERE id_pag1 = '$id_pag'
		";

		$resultadoAbonos = mysqli_query($db, $sqlAbonos);
		$i = 1;

		while($filaAbonos = mysqli_fetch_assoc($resultadoAbonos)){

	?>
		<tr>
			<td class="letraPequena"><?php echo $i; $i++; ?></td>
			<td class="letraPequena">$ <?php echo $filaAbonos['mon_abo_pag']; ?></td>
			<td class="letraPequena"><?php echo fechaFormateadaCompacta($filaAbonos['fec_abo_pag']); ?></td>
			<td class="letraPequena"><?php echo $filaAbonos['tip_abo_pag']; ?></td>
			<td class="letraPequena"><?php echo $filaAbonos['res_abo_pag']; ?></td>
			
		</tr>


	<?php
		} 

	?>
</table>

<script>
    $(document).ready(function () {


        $('#myTableAbonos').DataTable({
            
        
            dom: 'Bfrtlip',
            
            buttons: [

            
                    'copy',
		            {
		                extend: 'excel',
		                messageTop: "<?php echo 'Historial de abonos de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>"
		            },
		            {
                        extend: 'print',
                        messageTop: "<?php echo 'Historial de abonos de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo 'Historial de abonos de '.$con_pag.' del Alumno: '.$nombreAlumno; ?>",
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
        $('#myTableAbonos_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableAbonos_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableAbonos_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableAbonos_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableAbonos_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableAbonos_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableAbonos_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableAbonos_wrapper select').addClass('mdb-select');
        $('#myTableAbonos_wrapper .mdb-select').materialSelect();
        $('#myTableAbonos_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableAbonos_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);

        var myTableAbonos = $('#myTableAbonos').DataTable();

        $("#saldoPagadoModalAbono").text('$'+myTableAbonos.column( 1, {filter: 'applied'} ).data().sum().toFixed(2) );
	    



	    myTableAbonos.on('draw', function(){
	      $("#saldoPagadoModalAbono").text('$'+myTableAbonos.column( 1, {filter: 'applied'} ).data().sum().toFixed(2) );
	          
	      
	    });

    
    });
</script>