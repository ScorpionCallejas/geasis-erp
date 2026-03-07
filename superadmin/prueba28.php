<?php  
 
	include('inc/header.php');

	include('database_connection.php');

	$country = '';
	$query = "SELECT DISTINCT Country FROM tbl_customer ORDER BY Country ASC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
	 $country .= '<option value="'.$row['Country'].'">'.$row['Country'].'</option>';
	}
?>

<div class="container box">
   <h3 align="center">Custom Search in jQuery Datatables using PHP Ajax</h3>
   <br />
   <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
     <div class="form-group">
      <select name="filter_gender" id="filter_gender" class="browser-default custom-select" required>
       <option value="">Select Gender</option>
       <option value="Male">Male</option>
       <option value="Female">Female</option>
      </select>
     </div>
     <div class="form-group">
      <select name="filter_country" id="filter_country" class="browser-default custom-select" required>
       <option value="">Select Country</option>
       <?php echo $country; ?>
      </select>
     </div>
     <div class="form-group" align="center">
      <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
     </div>
    </div>
    <div class="col-md-4"></div>
   </div>
   <div class="table-responsive">
    <table id="customer_data" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th width="20%">Customer Name</th>
       <th width="10%">Gender</th>
       <th width="25%">Address</th>
       <th width="15%">City</th>
       <th width="15%">Postal Code</th>
       <th width="15%">Country</th>
      </tr>
     </thead>
    </table>
    <br />
    <br />
    <br />
   </div>
</div>


<?php  

	include('inc/footer.php');

?>


<script type="text/javascript" language="javascript" >
 	$(document).ready(function(){
  
	  	fill_datatable();


	  
	  	function fill_datatable(filter_gender = '', filter_country = ''){


	   		var dataTable = $('#customer_data').DataTable({
			    // dom: 'Bfrtpli',
			    
			    "processing" : true,
			    "serverSide" : true,
			    "order" : [],
			    "searching" : false,
			    "ajax" : {
			    	url:"fetch.php",
			    	type:"POST",
			    	data:{
			    		filter_gender, filter_country
			    	}
		    	},
		    	// "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

		    	"language": {
			        "sProcessing": '<h3 class="text-center grey-text" style="position: fixed; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>',
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


	   		$('#customer_data_wrapper').find('label').each(function () {
				$(this).parent().append($(this).children());
			});
			$('#customer_data_wrapper .dataTables_filter').find('input').each(function () {
				$('#customer_data_wrapper input').attr("placeholder", "Buscar...");
				$('#customer_data_wrapper input').removeClass('form-control-sm');
			});
			$('#customer_data_wrapper .dataTables_length').addClass('d-flex flex-row');
			$('#customer_data_wrapper .dataTables_filter').addClass('md-form');
			$('#customer_data_wrapper select').removeClass(
			'custom-select custom-select-sm form-control form-control-sm');
			$('#customer_data_wrapper select').addClass('mdb-select');
			$('#customer_data_wrapper .mdb-select').materialSelect();
			$('#customer_data_wrapper .dataTables_filter').find('label').remove();
			var botones = $('#customer_data_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
			//console.log(botones);

	   		


	   		
	  	}



  
		$('#filter').click(function(){
			var filter_gender = $('#filter_gender').val();
		   	var filter_country = $('#filter_country').val();
		   	if(filter_gender != '' && filter_country != '') {
		    	$('#customer_data').DataTable().destroy();
		    	fill_datatable(filter_gender, filter_country);
		   	} else {
		    	alert('Select Both filter option');
		    	$('#customer_data').DataTable().destroy();
		    	fill_datatable();
		   	}
		});
  
  
	});
 
</script>