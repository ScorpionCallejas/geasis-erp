<?php  

	include( 'inc/header.php' );
	$id_sub_hor = $_GET['id_sub_hor'];

	$sqlMateria = "
		SELECT *
		FROM sub_hor
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_sub_hor = '$id_sub_hor' AND id_pro1 = '$id'
	";

	$resultadoValidacion = mysqli_query( $db, $sqlMateria );

	$validacion = mysqli_num_rows( $resultadoValidacion );

	if ( $validacion == 0 ) {
	
		header('location: not_found_404_page.php');
	
	}

	$resultadoMateria = mysqli_query( $db, $sqlMateria );

	$filaMateria = mysqli_fetch_assoc( $resultadoMateria );

?>

<!-- NAVEGACION INTERNA -->
<?php  
	echo obtenerNavegacionGrupo( $id_sub_hor, $id );
?>
<!-- FIN NAVEGACION INTERNA -->

<!-- TITULO -->
<div class="row ">
	<div class="col-md-6 text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Video-clase">
			<i class="fas fa-bookmark"></i> 
			Alumnos de <?php echo $filaMateria['nom_mat']; ?>
		</span>
		
		<br>
		
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Alumnos</a>
		</div>
	</div>


	<div class="col-md-6 text-right">
		<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Grupo">
			<i class="fas fa-circle"></i>
			<?php echo $filaMateria['nom_gru']; ?>
		</span>
	</div>
	
</div>
<!-- FIN TITULO -->
<?php

	$sqlMaterias = "
	    SELECT *
	    FROM sub_hor
	    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	    WHERE id_sub_hor = '$id_sub_hor'
	";

	$resultadoDatosHorario = mysqli_query( $db, $sqlMaterias );

	$filaDatosHorario = mysqli_fetch_assoc( $resultadoDatosHorario );

	// DATOS RAMA
	$nom_ram = $filaDatosHorario['nom_ram'];
	$mod_ram = $filaDatosHorario['mod_ram'];
	$gra_ram = $filaDatosHorario['gra_ram'];
	$per_ram = $filaDatosHorario['per_ram'];
	$cic_ram = $filaDatosHorario['cic_ram'];

	// DATOS CICLO ESCOLAR
	$nom_cic = $filaDatosHorario['nom_cic'];
	$ins_cic = $filaDatosHorario['ins_cic'];
	$ini_cic = $filaDatosHorario['ini_cic'];
	$cor_cic = $filaDatosHorario['cor_cic'];
	$fin_cic = $filaDatosHorario['fin_cic'];


?>


<style>

	.claseHijoIzquierda {
		position: absolute;
		left: 15px;
		bottom: -40px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.claseHijoDerecha {
		position: absolute;
		right: 15px;
		bottom: -40px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.clasePadre {
		position: relative;
	}




</style>

<!-- DATOS PROGRAMA Y CICLO -->
<div class="row">
  
  	<div class="col-md-4 text-left">
	    <div class="card">
	      <div class="card-header bg-white">
	        Semana
			<?php  
				$fechaHoy = date( 'Y-m-d' );

				$diferenciaDias = obtenerDiferenciaFechas( $fechaHoy, $ini_cic );

				echo floor( $diferenciaDias / 7 );

				$diasCiclo = obtenerDiferenciaFechas( $fin_cic, $ini_cic );

				$porcentajeAvance = floor( ( ( $diferenciaDias * 100 ) / $diasCiclo ) );

				// echo $porcentajeAvance;
			?>
	      </div>
	      <div class="card-body">
	      

	          <label class="letraMediana">
	          Inicio: <?php echo mb_strtolower( obtenerFechaGuapa( $ini_cic ) ); ?>
	          <br>
	          Finaliza: <?php echo mb_strtolower( obtenerFechaGuapa( $fin_cic ) ); ?>
	          <br>
	          <?php echo $diferenciaDias; ?> días transcurridos
	          <br>
	          Duración del ciclo escolar de <?php echo $diasCiclo; ?> días
	          <br>
	          Semana <?php echo floor( $diferenciaDias / 7 )." de  ".floor( $diasCiclo / 7 )." semanas"; ?> 
	        </label>
	      </div>
	    </div>
	</div>

	  <div class="col-md-4 text-left">
	    <div class="card">
	      <div class="card-header bg-white">
	        Datos del Ciclo Escolar
	      </div>
	      <div class="card-body">
	      

	          <label class="letraMediana">
	          <?php echo $nom_cic; ?>
	          <br>
	          Inscripción: <?php echo fechaFormateadaCompacta($ins_cic); ?>
	          <br>
	          Inicio: <?php echo fechaFormateadaCompacta($ini_cic); ?>
	          <br>
	          Corte: <?php echo fechaFormateadaCompacta($cor_cic); ?>
	          <br>
	          Fin: <?php echo fechaFormateadaCompacta($fin_cic); ?>
	        </label>
	      </div>
	    </div>
	  </div>

	<div class="col-md-4 text-left">
	    <div class="card">
	      <div class="card-header bg-white">
	        Datos del Programa
	      </div>
	      <div class="card-body">
	        <label class="letraMediana">
	          Programa: <?php echo $nom_ram; ?>
	          <br>
	          Modalidad: <?php echo $mod_ram; ?>
	          <br>
	          Nivel Educativo: <?php echo $gra_ram; ?>
	          <br>
	          Tipo de Periodo: <?php echo $per_ram; ?>
	          <br>
	          Cantidad de Periodos: <?php echo $cic_ram; ?>

	        </label>

	      
	      </div>
	    </div>
	</div>

  	

</div>
<!-- FIN DATOS PROGRAMA Y CICLO -->

<br>

<!-- BARRA -->
<div class="row">

	<div class="col-md-12 clasePadre">
		
		<div class="progress md-progress" style="height: 20px" id="barra_video">
		    
		    <div class="progress-bar text-center white-text" role="progressbar" style="height: 20px; " aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado" title="Esta barra representa el avance del ciclo escolar">
		    	
		    </div>
			
			
		</div>

		<p class="claseHijoIzquierda letraPequena font-weight-normal">
			Inicio de ciclo
			<br>
			<?php echo fechaFormateadaCompacta($ini_cic); ?>
		</p>


		<p class="claseHijoDerecha letraPequena font-weight-normal">
			Fin de ciclo
			<br>
			<?php echo fechaFormateadaCompacta($fin_cic); ?>
		</p>
	
	</div>

</div>
<!-- FIN BARRA -->


<br>
<br>

<!-- LISTADO ALUMNOS -->
<div class="row">
	
	<div class="col-md-12">
		
		<!-- LAYOUT TAB -->
		<div class="modal-c-tabs">

		    <!-- Nav tabs -->
		    <ul class="nav md-pills nav-justified pills-info mt-4 mx-4" role="tablist">
		      <li class="nav-item">
		        <a class="nav-link active letraMediana waves-effect btn-block" data-toggle="tab" href="#panel1" role="tab">
		            
		            Listado grupal de alumnos
		        </a>
		      </li>


		      <li class="nav-item">
		        <a class="nav-link letraMediana waves-effect btn-block" data-toggle="tab" href="#panel2" role="tab" id="btn_evaluar">
		            Calificaciones finales
		        </a>
		      </li>


		      	

		    </ul>

		    <!-- TAB PANELS -->
		    <div class="tab-content pt-3">
		      <!-- PANEL 1-->
		      <div class="tab-pane fade in show active" id="panel1" role="tabpanel">

		        <!--BODY-->
		        <div class="modal-body mb-1">


		            <!-- TABLA LISTADO ALUMNMOS -->
		            <table id="myTableAlumnos" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
		                <thead class="grey text-white">
		                    <tr>
		                        <th class="letraPequena font-weight-normal">#</th>
		                        <th class="letraPequena font-weight-normal">Matrícula</th>
		                        <th class="letraPequena font-weight-normal">Alumno</th>
		                        <th class="letraPequena font-weight-normal">CDE</th>
		                    </tr>
		                </thead>


		                <?php

		                    $sqlAlumnos = "
		                        SELECT *
		                        FROM alu_hor 
		                        INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		                        INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
		                        INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		                        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		                        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		                     
		                        WHERE id_sub_hor = '$id_sub_hor' AND est_alu_hor = 'Activo'
		                        ORDER BY app_alu ASC
		                    ";

		                    // echo $sqlAlumnos;

		                    $resultadoAlumnos = mysqli_query($db, $sqlAlumnos);
		                    $i = 1;

		                    while($filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos)){
		                    	$id_alu_ram = $filaAlumnos['id_alu_ram'];

		                ?>
		                    <tr>
		                        <td class="letraPequena font-weight-normal"><?php echo $i; $i++;?></td>

		                		
		                		<td class="letraPequena font-weight-bold"><?php echo $filaAlumnos['bol_alu']; ?></td>
		                        <td class="letraPequena font-weight-normal"><?php echo $filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']." ".$filaAlumnos['nom_alu']; ?></td>
		                        <td class="letraPequena font-weight-normal">
		                        	<?php
		                        		echo obtener_datos_vista_alumno( $id_alu_ram )['nom_pla']; 
		                        	?>
		                        </td>
		                        

		                    </tr>

		                <?php
		                    } 

		                ?>
		            </table>
		            <!-- FIN TABLA LISTADO ALUMNOS -->
		            
		        </div>
		        <!--FIN BODY-->

		      </div>
		      <!--/.FIN PANEL 1-->

		      <!--PANEL 2-->
		      <div class="tab-pane fade" id="panel2" role="tabpanel">

		        <!--BODY-->
		        <div class="modal-body" id="contenedor_evaluacion_alumnos">



		            
		          
		            



		        </div>
		        <!-- FIN BODY -->
		      </div>
		      <!--/.FIN PANEL 2-->


		    </div>
		    <!-- FIN TAB PANNELS -->

		</div>
	</div>
</div>


<!-- TITULOS A DATATABLE -->
<?php  

    $resultadoTitulo = mysqli_query($db, $sqlAlumnos);

    $filaTitulo = mysqli_fetch_assoc($resultadoTitulo);

    $materia = $filaTitulo['nom_mat'];
    $grupo = $filaTitulo['nom_gru'];


?>
<!-- FIN TITULOS A DATATABLE -->


<!-- FIN LISTADO ALUMNOS -->




<?php 

	include( 'inc/footer.php' );

?>



<script>

	<?php  

        $reemplazoAcentos = array(    
         "'"=>'`', '"'=>'`' 
        );
        
    ?>
    $(document).ready(function () {


        $('#myTableAlumnos').DataTable({
            
        
            dom: 'Bfrtlip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            
            "pageLength": -1,
            
            buttons: [

            
                    'copy',
                    {
                        extend: 'excel',
                        messageTop: "<?php echo strtr( $grupo, $reemplazoAcentos ).' - '.$materia; ?>"
                    },
                    {
                        extend: 'print',
                        messageTop: "<?php echo strtr( $grupo, $reemplazoAcentos ).' - '.$materia; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        messageTop: "<?php echo strtr( $grupo, $reemplazoAcentos ).' - '.$materia; ?>",
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
        $('#myTableAlumnos_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#myTableAlumnos_wrapper .dataTables_filter').find('input').each(function () {
            $('#myTableAlumnos_wrapper input').attr("placeholder", "Buscar...");
            $('#myTableAlumnos_wrapper input').removeClass('form-control-sm');
        });
        $('#myTableAlumnos_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#myTableAlumnos_wrapper .dataTables_filter').addClass('md-form');
        $('#myTableAlumnos_wrapper select').removeClass(
        'custom-select custom-select-sm form-control form-control-sm');
        $('#myTableAlumnos_wrapper select').addClass('mdb-select');
        $('#myTableAlumnos_wrapper .mdb-select').materialSelect();
        $('#myTableAlumnos_wrapper .dataTables_filter').find('label').remove();
        var botones = $('#myTableAlumnos_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
        //console.log(botones);



        $('#myTableAlumnosActividades').DataTable({
            
        	"pageLength": -1,

            dom: 'Brt',
             buttons: [

            
                    'copy',
                    {
                        extend: 'excel',
                        messageTop: "<?php echo strtr( $grupo, $reemplazoAcentos ).' - '.$materia; ?>"
                    },
                    {
                        extend: 'print',
                        messageTop: "<?php echo strtr( $grupo, $reemplazoAcentos ).' - '.$materia; ?>",
                        exportOptions: {
                            columns: ':visible'
                        },
                    }


            ],

            
           

     
        });
        var botones = $('#myTableAlumnosActividades_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
    
    });
</script>


<script>
    // TAB2
    var id_sub_hor = <?php echo $id_sub_hor; ?>;
    
    obtener_evaluacion_alumnos( id_sub_hor );

    function obtener_evaluacion_alumnos( id_sub_hor ) {
        $.ajax({
            
            url: 'server/obtener_calificaciones_alumnos_materia.php',
            type: 'POST',
            data: { id_sub_hor },
            success: function( respuesta ) {
                $("#contenedor_evaluacion_alumnos").html( respuesta );

            }
        });
        
    }

</script>



<script>
	
	var r = 254;
	var porcentajeAvance = 0;
	var limite = <?php echo $porcentajeAvance; ?>;
	iniciarCambioBarra( r, porcentajeAvance, limite );




	function iniciarCambioBarra( r, porcentajeAvance ){
		if( r > 50 || porcentajeAvance < limite ) {
		    setTimeout(function(){
		      	r = r - 2;
		      	$( '#barra_estado' ).css({
					background: 'rgb( '+r+', 255, 50)',
					width : porcentajeAvance+'%'
				}).text( porcentajeAvance+'%' );

				if ( porcentajeAvance < limite ) {
					porcentajeAvance++;
				}
				
		      	iniciarCambioBarra( r, porcentajeAvance, limite );
		    }, 50 );
	  	}
	}
	
</script>