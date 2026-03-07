<?php  

	include('inc/header.php');

	$sql_errores = "SELECT * FROM ticket WHERE alumno = '$id_alumno'";
	//echo $sql_errores;
	$try_errores = mysqli_query($db, $sql_errores);
	$get_errores = 'si';
	if (!$try_errores) {
		$get_errores = 'no';
	}

?>


<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Sección de ayuda"><i class="fas fa-bookmark"></i> Ayuda</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Bugs</a>
		</div>
		
	</div>
		
</div>
<!-- FIN TITULO -->

<!-- Jumbotron -->
<div class="jumbotron text-center mdb-color  grey lighten-4  black-text mx-2 mb-5">


	<div class="row text-center">

		<div class="col-md-12">
			<p class="h2">
				Listado de problemas reportados
			</p>

			<br>


			<p class="h4 text-primary">
				Aquí toda la información necesaria acerca de los errores que nos haces llegar. Gracias por tu apoyo!
			</p>

			<br>
		</div>
		
	</div>
	<div class="col-12 col-md-12 col-sm-12">
		
		<table id="tabla_bugs" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th class="text-light bg-dark">Fecha</th>
                <th class="text-light bg-dark">Area de reporte</th>
                <th class="text-light bg-dark">Descripción del error</th>
                <th class="text-light bg-dark">Solución</th>
                <th class="text-light bg-dark">Fecha de Solución</th>
            </tr>
        </thead>
        <tbody>
        	<?php 
        		if ($get_errores != 'no') {
        			while ( $get_errores = mysqli_fetch_assoc($try_errores) ) {

        	 ?>
	            <tr class="hoverable">
	                <td><?php echo $get_errores['fecha']; ?></td>
	                <td><?php echo $get_errores['area']; ?></td>
	                <td><?php echo $get_errores['detalle']; ?></td>
	                <td><?php if ($get_errores['solucion'] == null) {
	                	echo '<span class = "text-danger">Pendiente por resolver</span>';
	                	}else{
	                		echo'<span class= "text-muted">'. $get_errores['solucion'].'</span>';
	                	}
	                ?>
	                	
	                </td>
	                <td><?php if ($get_errores['fecha_solucion'] == null) {
	                	echo '<span class = "text-danger">Pendiente por resolver</span>';
	                	}else{
	                		echo '<span class= "text-muted">'. $get_errores['fecha_solucion'].'</span>';
	                	}
	                ?>
	                	</td>
	            </tr>
	            <?php        				
	        			}
	        		}
	             ?>
	        </tbody>
	        <tfoot>
	            <tr>
	                <th class="text-light bg-dark">Fecha</th>
	                <th class="text-light bg-dark">Area de reporte</th>
	                <th class="text-light bg-dark">Descripción del error</th>
	                <th class="text-light bg-dark">Solución</th>
	                <th class="text-light bg-dark">Fecha de Solución</th>
	            </tr>
	        </tfoot>
	    </table>


	</div>



</div>
<!-- Fin Jumbotron -->



<?php  

	include('inc/footer.php');

?>
<script>
	$(document).ready(function () {
    $('#tabla_bugs').DataTable({
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
});
</script>