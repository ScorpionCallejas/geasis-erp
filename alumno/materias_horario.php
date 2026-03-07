<?php  

  include('inc/header.php');
  $id_alu_ram = $_GET['id_alu_ram'];

  obtenerEstatusPagoAlumnoGlobal( $id );


  $sqlValidacionModalidad = "
		SELECT *
		FROM alu_ram
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_alu_ram = '$id_alu_ram'
 	";

 	$resultadoValidacionModalidad = mysqli_query($db, $sqlValidacionModalidad);

 	$filaValidacionModalidad = mysqli_fetch_assoc($resultadoValidacionModalidad);

 	if ($filaValidacionModalidad['mod_ram'] == 'Online') {
 		//echo "Online";
 		$sqlHorario = "
			SELECT *
			FROM sub_hor
			INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id' AND est_alu_hor = 'Activo'
			GROUP BY id_sub_hor
			ORDER BY id_mat DESC
		";






 	}else if($filaValidacionModalidad['mod_ram'] == 'Presencial'){
 		//echo "Presencial";

 		$sqlHorario = "
			SELECT *
			FROM sub_hor
			INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
			INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id'  AND est_alu_hor = 'Activo'
			GROUP BY id_sub_hor
			ORDER BY id_mat DESC
		";
 	}else{
 		echo "otros";
 	}

	
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	$resultadoValidacion = mysqli_query($db, $sqlHorario);
	$filaRama = mysqli_fetch_assoc($resultadoValidacion);
	$rama = $filaRama['nom_ram'];

	//echo $sqlHorario;


	// VALIDACCION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

	//echo $totalValidacion;
	
	if ($totalValidacion == 0) {
		header('location: not_found_404_page.php');
	}
?>

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Estás aquí"><i class="fas fa-bookmark"></i> Materias y Actividades</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" title="Estás aquí">Materias y Actividades</a>
		</div>		
	</div>
</div>
<!-- FIN TITULO -->


  <div class="card">
    
    <div class="card-body grey lighten-3">
      
      <div class="row">

        <div class="col-md-4 text-center" id="contenedor_datos">

          
        </div>

        <div class="col-md-4 text-center" id="contenedor_profesor">
      
        
          
        </div>


        
        
        <div class="col-md-4 text-right">
          <?php  
            $sqlProgramasAlumno = "
              SELECT *
              FROM alu_ram
              INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
              INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
              WHERE id_alu = '$id' AND id_alu_ram = '$id_alu_ram'
            ";

            $resultadoProgramasAlumno = mysqli_query( $db, $sqlProgramasAlumno );

          ?>

          <!-- SELECTOR PROGRAMA -->
          <select class="mdb-select md-form colorful-select dropdown-primary" id="selectorPrograma">
            <?php

              while( $filaProgramasAlumno = mysqli_fetch_assoc( $resultadoProgramasAlumno ) ) {
            ?>  
                <option value="<?php echo $filaProgramasAlumno['id_alu_ram']; ?>"><?php echo substr( $filaProgramasAlumno["nom_ram"], 0, 35 )."..."; ?></option>
                
            <?php        
              }

            ?>
          </select>
          <!-- FIN SELECTOR PROGRAMA -->

          <div id="contenedor_selector_materia">
            
          </div>




          
        </div>

      </div>
      
      <div class="row">

        <div class="col-md-12" id="contenedor_actividades">

          
        </div>

      </div>


      
    </div>
  </div>

  <br>
    




<?php  

  include('inc/footer.php');

?>


<script>
  $(document).ready(function() {

    $( '#selectorPrograma' ).materialSelect();

    obtenerSelectorPrograma();
    $( "#selectorPrograma" ).on( 'change', function() {
    
      obtenerSelectorPrograma();
      
    });

    function obtenerSelectorPrograma() {
        var id_alu_ram = $( '#selectorPrograma option:selected' ).val();
        
        // alert( materia );
        $( "#contenedor_selector_materia" ).html( '<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>' );
        $.ajax({
          url: 'server/obtener_materias_horario.php',
          type: 'POST',
          data: { id_alu_ram },
          success: function ( respuesta ) {

            $( "#contenedor_selector_materia" ).html( respuesta );

          }
        });
    }

  });
</script>