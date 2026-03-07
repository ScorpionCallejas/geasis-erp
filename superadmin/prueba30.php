<?php  
 
	include('inc/header.php');

?>

<div class="container box">
   	<span id="consulta">
   	
   	</span>
   	<br />
   	<div class="row">
    <div class="col-md-4">


    	<div class="card-body bg-light scrollspy-example" data-spy="scroll" style=" height: 235px; ">
							
			<div class="row">
				<div class="col-md-12">

		            <div class="form-check">
		              <input type="checkbox" class="form-check-input" id="seleccionTodos">
		              <label class="form-check-label letraPequena" for="seleccionTodos">
						Todos
		              </label>
		            </div>
				</div>
			</div>


			<?php
				$sqlProgramas = "
					SELECT *
					FROM rama
					WHERE id_pla1 = '$plantel'
					ORDER BY id_ram DESC
				";

				$resultadoProgramas = mysqli_query( $db, $sqlProgramas );
				$resultadoTotalProgramas = mysqli_query( $db, $sqlProgramas );

				$contadorProgramas = 1;

				$totalProgramas = mysqli_num_rows( $resultadoTotalProgramas );

		                for ( $i = 0 ; $i < $totalProgramas / 1 ; $i++ ) {
		    ?>
		                  <div class="row">
		    <?php  
		                      while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
		    ?>
		                  <div class="col-md-12">
		                    <div class="form-check">
		                              <input type="checkbox" class="form-check-input checador5" id="programa<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['nom_ram']; ?>" columna="7">
		                              <label class="form-check-label letraPequena " for="programa<?php echo $contadorProgramas; ?>">
		                            
		                                <?php echo $filaProgramas['nom_ram']; ?>

		                              </label>
		                            </div>
		                  </div>
		    <?php
		                        $contadorProgramas++;
		                      }
		    ?>
		                    
		                  </div>

		    <?php
		                }
		              // FIN for
		    ?>
			
		</div>
    </div>
    <div class="col-md-4"></div>

    <div class="col-md-4"></div>
   
   </div>

   <div class="table-responsive">
	    <table id="customer_data" class="table table-bordered table-striped">
	     	<thead>
		      	<tr>
			       	<th class="letraPequena font-weight-normal">#</th>
			        <th class="letraPequena font-weight-normal">Nombre</th>
			        <th class="letraPequena font-weight-normal">Programa</th>
		      	</tr>

	     	</thead>
	    </table>
    
   	</div>

</div>


<?php  

	include('inc/footer.php');

?>


<script type="text/javascript" language="javascript" >
 // 	$(document).ready(function(){
  
	//   	fill_datatable();


	  
	//   	function fill_datatable(filter_gender = '', filter_country = ''){


	//    		var dataTable = $('#customer_data').DataTable({
	// 		    // dom: 'Bfrtpli',
			    
	// 		    "processing" : true,
	// 		    "serverSide" : true,
	// 		    "order" : [],
	// 		    "searching" : false,
	// 		    "ajax" : {
	// 		    	url:"server/listar_alumnos.php",
	// 		    	type:"POST",
	// 		    	data:{
	// 		    		filter_gender, filter_country
	// 		    	}
	// 	    	},
	// 	    	// "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

	// 	    	"language": {
	// 		        "sProcessing": '<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>',
	// 		          "sLengthMenu":     "Mostrar _MENU_ registros",
	// 		          "sZeroRecords":    "No se encontraron resultados",
	// 		          "sEmptyTable":     "Ningún dato disponible en esta tabla",
	// 		          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	// 		          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
	// 		          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	// 		          "sInfoPostFix":    "",
	// 		          "sSearch":         "Buscar:",
	// 		          "sUrl":            "",
	// 		          "sInfoThousands":  ",",
	// 		          "sLoadingRecords": "Cargando...",
	// 		          "oPaginate": {
	// 		              "sFirst":    "Primero",
	// 		              "sLast":     "Último",
	// 		              "sNext":     "Siguiente",
	// 		              "sPrevious": "Anterior"
	// 	          		},
	// 	          	"oAria": {
	// 	              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
	// 	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	// 	          	}
	// 	      	}


	//    		});

	   		


	   		
	//   	}



  
	// 	$('#filter').click(function(){
	// 		var filter_gender = $('#filter_gender').val();
	// 	   	var filter_country = $('#filter_country').val();
	// 	   	if(filter_gender != '' && filter_country != '') {
	// 	    	$('#customer_data').DataTable().destroy();
	// 	    	fill_datatable(filter_gender, filter_country);
	// 	   	} else {
	// 	    	alert('Select Both filter option');
	// 	    	$('#customer_data').DataTable().destroy();
	// 	    	fill_datatable();
	// 	   	}
	// 	});
  
  
	// });
 
</script>



<script>
	// SELECCION DE TODOS LOS ANNIOS
	$("#seleccionTodos").on('click', function() {
	//event.preventDefault();
	/* Act on the event */

		//console.log( $(this)[0].checked );

		if ( $(this)[0].checked == true ) {
		  // console.log("checkeado");
		  $('.seleccionAnniosMeses').prop({checked: true});
		  obtenerAnnios();
		  
		}else{ 
		  
		  $('.seleccionAnniosMeses').prop({checked: false});
		  obtenerAnnios();

		}

	//$('.seleccionAnniosMeses').prop({checked: false});
	});


	function obtenerAnnios() {


		var annios = [];
		var meses = [];

		for ( var i = 0, j = 0 ; i < $(".checkboxAnnio").length ; i++ ) {

			if ( $(".checkboxAnnio")[i].checked == true ) {
				// alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxAnnio').eq(i).attr("annio")+" esta seleccionado"  );

				annios[j] = $('.checkboxAnnio').eq(i).attr("annio");
				meses[j] = $('.checkboxAnnio').eq(i).attr("mes");
				j++;

			}
		}

		if ( annios.length == 0 ) {

			swal("¡No hay años seleccionados!", "Selecciona al menos un año para continuar", "info", {button: "Aceptar",});
			$("#contenedor_alumnos").html("");

		} else {
			obtener_alumnos( annios, meses );
		}

		
	}


	obtenerAnnios();


	$('.checkboxAnnio').on('click', function() {
		//event.preventDefault();
		/* Act on the event */

		obtenerAnnios();
		

	});




	// var total = obtenerAnnios();

	//alert(total[0]);


	function obtener_alumnos( annios, meses ) {


		$.ajax({
			url: 'server/listar_alumnos.php',
			type: 'POST',
			data: { annios, meses },
			success: function( respuesta ){
				$('#consulta').html( respuesta );
			}
		});

		// var dataTable = $('#customer_data').DataTable({
		    
		//     "processing" : true,
		//     "serverSide" : true,
		//     "order" : [],
		//     "searching" : false,

		//     "ajax" : {
		//     	url:"server/listar_alumnos.php",
		//     	type:"POST",
		//     	data:{
		//     		annios, meses
		//     	},

	 //    	},

	    	
	 //    	// "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

	 //    	"language": {
		//         "sProcessing": '<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>',
		//           "sLengthMenu":     "Mostrar _MENU_ registros",
		//           "sZeroRecords":    "No se encontraron resultados",
		//           "sEmptyTable":     "Ningún dato disponible en esta tabla",
		//           "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		//           "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
		//           "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		//           "sInfoPostFix":    "",
		//           "sSearch":         "Buscar:",
		//           "sUrl":            "",
		//           "sInfoThousands":  ",",
		//           "sLoadingRecords": "Cargando...",
		//           "oPaginate": {
		//               "sFirst":    "Primero",
		//               "sLast":     "Último",
		//               "sNext":     "Siguiente",
		//               "sPrevious": "Anterior"
	 //          		},
	 //          	"oAria": {
	 //              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
	 //              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	 //          	}
	 //      	}


  //  		});



  //  		$('#customer_data_wrapper').find('label').each(function () {
		// 		$(this).parent().append($(this).children());
		// 	});
		// 	$('#customer_data_wrapper .dataTables_filter').find('input').each(function () {
		// 		$('#customer_data_wrapper input').attr("placeholder", "Buscar...");
		// 		$('#customer_data_wrapper input').removeClass('form-control-sm');
		// 	});
		// 	$('#customer_data_wrapper .dataTables_length').addClass('d-flex flex-row');
		// 	$('#customer_data_wrapper .dataTables_filter').addClass('md-form');
		// 	$('#customer_data_wrapper select').removeClass(
		// 	'custom-select custom-select-sm form-control form-control-sm');
		// 	$('#customer_data_wrapper .mdb-select').materialSelect('destroy');
		// 	$('#customer_data_wrapper select').addClass('mdb-select');
		// 	$('#customer_data_wrapper .mdb-select').materialSelect();
		// 	$('#customer_data_wrapper .dataTables_filter').find('label').remove();
		// 	var botones = $('#customer_data_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');

  //  		console.log( dataTable.ajax.json().data );
   		// var json = table.ajax.json();
	}
</script>