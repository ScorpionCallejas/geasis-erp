<?php  
 
	include('inc/header.php');

?>


<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Panel de Seguridad">
			<i class="fas fa-lock"></i>
			Panel de Seguridad
		</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Panel de Seguridad</a>
		</div>
	</div>
</div>
<!-- FIN TITULO -->



<!-- ROW FILTROS -->
<div class="row animated fadeInDown delay-1s">


  <!-- INDICADORES -->
  
  <div class="col-md-3 text-center">

  	<div class="card-header grey darken-1 text-center" role="tab" id="headingTwo1">
				      
      	<a data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1"
        	aria-controls="collapseTwo1" acordeon="aco_fec_fil">

        <h5 class="letraMediana white-text">
        	Filtrado y Búsqueda
        </h5>
      </a>
    </div>

    <div class="card bg-light p-2" style="height: 230px;">
      <div class="row">
        
        <div class="col-md-12 text-center">

          <h5>
            <span class="badge badge-info" id="contenedor_registros">
            
            </span>
          </h5>

        </div>
        
      </div>

      <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
          <div class="md-form mb-2" id="contenedor_buscador">

          </div>
        

          <div class="md-form mb-2">
            <input type="date" id="min-date" class="date-range-filter form-control validate" title="Inicio del Rango" style="font-size:10px;" value="<?php echo date('Y-m-d'); ?>">
          </div>
          
          <div class="md-form mb-2" style="position: relative; top: -10%;">
            <input type="date" id="max-date" class="date-range-filter form-control validate" title="Fin del Rango" style="font-size:10px;" value="<?php echo date('Y-m-d'); ?>">
          </div>  
        </div>

        <div class="col-md-1"></div>
      </div>



    </div>
  </div>
  <!-- FIN INDICADORES -->
 
  <!-- USUARIOS -->
  <div class="col-md-3">

  	<div class="card-header grey darken-1 text-center" role="tab" id="headingTwo1">
				      
      	<a data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1"
        	aria-controls="collapseTwo1" acordeon="aco_fec_fil">

        <h5 class="letraMediana white-text">
        	Tipo de Usuario
        </h5>
      </a>
    </div>


    <div class="card bg-light p-2" style="height: 230px;">

      

      <div class="row">

        <div class="col-md-10 text-left">

          <br>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario1" value="Administrador" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario1">Administrador</label>
          </div>


          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario2" value="Gestor Escolar" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario2">Gestor Escolar</label>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario3" value="Cobranza" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario3">Cobranza</label>
          </div>


          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario4" value="Administrador_Comercial" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario4">Administrador Comercial</label>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario5" value="Profesor" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario5">Profesor</label>
          </div>


          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario6" value="Alumno" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario6">Alumno</label>
          </div>


          <div class="form-check">
            <input type="checkbox" class="form-check-input checador1" id="usuario7" value="Ejecutivo" columna="1">
            <label class="form-check-label letraPequena font-weight-normal" for="usuario7">Ejecutivo</label>
          </div>
          

        </div>



      </div>

    </div>
  </div>
  <!-- FIN USUARIOS -->



  <!-- ACCIONES -->
  <div class="col-md-3">

  	<div class="card-header grey darken-1 text-center" role="tab" id="headingTwo1">
				      
      	<a data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1"
        	aria-controls="collapseTwo1" acordeon="aco_fec_fil">

        <h5 class="letraMediana white-text">
        	Tipo de Movimiento
        </h5>
      </a>
    </div>

    <div class="card bg-light p-2" style="height: 230px;">
      
    
      

      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10 text-left">

          <br>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador2" id="movimiento1" value="Alta" columna="3">
            <label class="form-check-label letraPequena font-weight-normal" for="movimiento1">Alta</label>
          </div>


          <div class="form-check">
            <input type="checkbox" class="form-check-input checador2" id="movimiento2" value="Baja" columna="3">
            <label class="form-check-label letraPequena font-weight-normal" for="movimiento2">Baja</label>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador2" id="movimiento3" value="Cambio" columna="3">
            <label class="form-check-label letraPequena font-weight-normal" for="movimiento3">Cambio</label>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador2" id="movimiento5" value="Inicio" columna="3">
            <label class="form-check-label letraPequena font-weight-normal" for="movimiento5">Inicio de sesión</label>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input checador2" id="movimiento6" value="Fin" columna="3">
            <label class="form-check-label letraPequena font-weight-normal" for="movimiento6">Fin de sesión</label>
          </div>





        </div>



      

      </div>
    </div>
  </div>
  <!-- FIN ACCIONES -->

  <!-- FILTROS 2 -->
  <div class="col-md-3">
    <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo1">
				      
      	<a data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1"
        	aria-controls="collapseTwo1" acordeon="aco_fec_fil">

        <h5 class="letraMediana white-text">
        	Subtipo de Movimiento
        </h5>
      </a>
    </div>
    <div class="card bg-light p-1 scrollspy-example" data-spy="scroll" style=" height: 230px; " >
		
		

		<div class="card-body">
			<div class="row text-center">

		        <div class="col-md-1"></div>
		        <div class="col-md-10 text-left">

					<?php  

						$sqlAgrupacion = "
							SELECT *
							FROM log
							WHERE id_pla10 = '$plantel'
							GROUP BY ent_log
							ORDER BY id_log DESC
						";

						$contadorAgrupacion = 1;

						$resultadoAgrupacion = mysqli_query( $db, $sqlAgrupacion );


						while( $filaAgrupacion = mysqli_fetch_assoc( $resultadoAgrupacion ) ){
					?>
							<div class="form-check">
					            <input type="checkbox" class="form-check-input checadorColumna" id="agrupacion<?php echo $contadorAgrupacion; ?>" columna="6" value="<?php echo $filaAgrupacion['ent_log']; ?>">
					            <label class="form-check-label letraPequena font-weight-normal" for="agrupacion<?php echo $contadorAgrupacion; ?>">
									<?php echo $filaAgrupacion['ent_log']; ?>
					            </label>
					        </div>



					<?php
							$contadorAgrupacion++;
						}


					?>


		            
		          
		        
		        </div> 
		        <div class="col-md-1"></div>
		      </div>
		</div>

      

    </div>
 
  </div>
  <!-- FIN FILTROS 2 -->


</div>
<!-- FIN ROW FILTROS -->


	<!-- LOG -->
	<div class="card">


		<div class="card-body">

			<div id="contenedor_log">
				
			</div>
			
		</div>

	</div>
	<!-- FIN LOG -->



<?php

	include('inc/footer.php');

?>

<script>
	
	obtener_log();


	$( '.date-range-filter' ).on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_log();
	});




	function obtener_log() {
		var inicio = $( '#min-date').val();
		var fin = $( '#max-date').val();

		$("#contenedor_log").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
		$.ajax({
			url: 'server/obtener_log.php',
			type: 'POST',
			data: { inicio, fin },
			success: function(respuesta){
				$(".modal-backdrop").removeClass('modal-backdrop');
				$("#contenedor_log").html(respuesta);
				$("#contenedor_buscador").html( '' );
				
                          
			}
		});
	}

</script>