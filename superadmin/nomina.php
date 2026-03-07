<?php  

	include('inc/header.php');

?>


<!-- CONTENIDO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Nómina">
			<i class="fas fa-bookmark"></i> 
			Reportes de Nómina
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-2s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Nómina</a>
</div>
<br>
<div class="row justify-content-md-center">
	<div class="col-md-3">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
		  <div class="card-header bg-info">Total Bruto</div>
		  <div class="card-body">
		    <h2 class="card-title">$ <span id="bruto"></span></h2>
		  </div>
		</div>
	</div>

	<div class="col-md-3">

		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
		  <div class="card-header bg-info">Total Descuento</div>
		  <div class="card-body">
		    <h2 class="card-title">$ <span id="descuento"></span></h2>
		  </div>
		</div>
		
	</div>


	<div class="col-md-3">

		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
		  <div class="card-header bg-info">Total Neto</div>
		  <div class="card-body">
		    <h2 class="card-title">$ <span id="neto"></span></h2>
		  </div>
		</div>
		
	</div>

</div>


<!-- ROW TABLA -->

<div class="row">

	
  	<!-- SEGUNDA COL DATATABLE -->
	<!-- SEGUNDA COL 8 DATATABLE -->
  	<div class="col-md-12">
		<?php
			$sqlNomina = "	
							SELECT *
							FROM nomina
							INNER JOIN empleado ON nomina.id_emp2 = empleado.id_emp
							WHERE id_pla6 = '$plantel'
						";  

			$resultadoNomina = mysqli_query($db, $sqlNomina);

			//nom_emp, app_emp, apm_emp, bru_nom, des_nom, net_nom, fec_nom, est_nom, tip_emp
		?>

		<!-- INPUTS DE FECHAS -->
		


		<div class="row">
		        <div class="md-form mb-2">
		          <i class="fas fa-calendar-minus prefix grey-text"></i>
		          <input type="date" id="min-date" class="date-range-filter form-control validate" title="Inicio del Rango">
		        </div>

				
		        <div class="md-form mb-2">
		          <i class="fas fa-calendar-plus prefix grey-text"></i>
		          <input type="date" id="max-date" class="date-range-filter form-control validate" title="Fin del Rango">
		        </div>

		</div>
		


		<!-- FIN INPUTS DE FECHAS -->

		<table id="myTable" class="table table-hover table-bordered table-sm hoverable" cellspacing="0" width="100%">
            <thead class="white-text">
				<tr>
					<th>#</th>
					<th>Foto</th>
					<th>Nombre</th>
					<th>Apellído Paterno</th>
					<th>Apellído Materno</th>
					<th>Tipo de Empleado</th>
					<th>Folio de Pago</th>
					<th>Concepto</th>
					<th>Fecha de Pago</th>
					<th>Bruto</th>
					<th>Descuentos</th>
					<th>Neto</th>
					<th>Estatus de Pago</th>
					<th>Acción</th>
				</tr>
			</thead>


			<?php  
				$i = 1;
				while ($filaNomina = mysqli_fetch_assoc($resultadoNomina)) {
			?>

				<tr>	
						<td><?php echo $i; $i++;?></td>
						<td><img src="../uploads/<?php echo $filaNomina['fot_emp']; ?>" class="avatar rounded-circle" height="40px" width="40px"></td>
						<td><?php echo $filaNomina['nom_emp'];?></td>
						<td><?php echo $filaNomina['app_emp'];?></td>
						<td><?php echo $filaNomina['apm_emp'];?></td>
						<td><?php echo $filaNomina['tip_emp'];?></td>
						<td><?php echo $filaNomina['id_nom'];?></td>
						<td>
							<a href="#" class="btn btn-link detallesNomina" id_nom="<?php echo $filaNomina['id_nom']; ?>" title="Detalles de <?php echo $filaNomina['con_nom']; ?>">
								<?php echo $filaNomina['con_nom'];?>
							</a>	
						</td>
						<td><?php echo $filaNomina['fec_nom'];?></td>
						<td>
							<?php echo $filaNomina['bru_nom'];?>
						</td>
						<td><?php echo $filaNomina['des_nom'];?></td>
						<td><?php echo $filaNomina['net_nom'];?></td>
						<td><?php echo $filaNomina['est_nom'];?></td>

						<td>
							<a class="btn btn-danger btn-sm eliminacion" title="Eliminar <?php echo $filaNomina['con_nom']; ?>" eliminacion="<?php echo $filaNomina['id_nom']; ?>" nomina="<?php echo $filaNomina['con_nom']; ?> "><i class="fas fa-trash-alt fa-1x" ></i></a>
							
						</td>
						
				</tr>

			<?php
				}
			?>
		</table>
	
	</div>
	<!-- FIN SEGUNDA COL 8 DATATABLE -->
	
</div>
<!--  FIN ROW TABLA-->



<!-- ROW 2 -->
<div class="row" id="fila2Col1">
	
</div>
<!-- FIN ROW 2 -->
<!-- FIN CONTENIDO -->


<?php

	include('inc/footer.php');

?>


<script>
	$(document).ready(function () {


		$('#myTable').DataTable({

			
		
			dom: 'Bfrtlip',
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
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
		$('#myTable_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTable_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTable_wrapper input').attr("placeholder", "Buscar...");
			$('#myTable_wrapper input').removeClass('form-control-sm');
		});
		$('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTable_wrapper .dataTables_filter').addClass('md-form');
		$('#myTable_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTable_wrapper select').addClass('mdb-select');
		$('#myTable_wrapper .mdb-select').materialSelect();
		$('#myTable_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

		var table = $('#myTable').DataTable();
		var bruto = table.column( 9).data().sum();
		var descuento = table.column( 10 ).data().sum();
		var neto = table.column( 11 ).data().sum();



		$('#bruto').text(bruto);
		$('#neto').text(neto);		
		$('#descuento').text(descuento);



		// Extend dataTables search
		$.fn.dataTable.ext.search.push(
		    function( settings, data, dataIndex ) {
		        var min  = $('#min-date').val();
		        var max  = $('#max-date').val();
		        var createdAt = data[8] || 0; // Our date column in the table

		        if  ( 
		                ( min == "" || max == "" )
		                || 
		                ( moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max) ) 
		            )
		        {
		            return true;
		        }
		        return false;
		    }
		);

		// Re-draw the table when the a date range filter changes
		$('.date-range-filter').change( function() {

		    table.draw();

		} );


		//SUMATORIA EN TIEMPO REAL DE ACUERDO A FILTROS
		table.on('draw', function(){
	    	$("#bruto").text(table.column(9,{filter: 'applied'}).data().sum());
	    	$("#descuento").text(table.column(10,{filter: 'applied'}).data().sum());
	    	$("#neto").text(table.column(11,{filter: 'applied'}).data().sum());
	    	
	    });

	});
</script>


<script>
	//DETALLES DE NOMINA

	$(".detallesNomina").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var id_nom = $(this).attr("id_nom");

		//console.log(id_nom);

		$.ajax({
			url: 'server/obtener_detalles_nomina.php',
			type: 'POST',
			data: {id_nom},
 			success: function(respuesta){
 				//console.log(respuesta);
 				$("#fila2Col1").html(respuesta);

			}
		});

 
	});
</script>



<script>
	//ELIMINACION DE NOMINA
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var nomina = $(this).attr("eliminacion");
		var nombreNomina = $(this).attr("nomina");

		swal({
		  title: "¿Deseas eliminar "+nombreNomina+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
		  icon: "warning",
		  buttons: 	{
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
				url: 'server/eliminacion_nomina.php',
				type: 'POST',
				data: {nomina},
				success: function(respuesta){
					
					if (respuesta == "true") {
						console.log("Exito en consulta");
						swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {

							window.location.reload();
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
