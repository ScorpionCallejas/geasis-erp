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
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Clases">
			<i class="fas fa-bookmark"></i> 
			Clases de <?php echo $filaMateria['nom_mat']; ?>
		</span>
		
		<br>
		
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Clases</a>
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

	.claseHijoClaseMateria {
	  position: absolute;
	  right: 0px;
	  top: 0px;
	  z-index: 1;
	}

	.clasePadreClaseMateria {
	  position: relative;
	}

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

<a href="#" class="btn-link claseHijo text-primary p-1 " id="btn_ordenar_clases">
	<i class="fas fa-lock" id="icono_candado"></i>
	Activar ordenamiento
</a>


<!-- CLASES -->

	<?php

		// VALIDACION DE SI EXISTE ORDENAMIENTO PREVIO
		$sqlClasesValidacionOrden = "
			SELECT *
			FROM bloque
			INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
			WHERE id_sub_hor = '$id_sub_hor' AND ord_blo IS NOT NULL
		";

		$resultadoClasesValidacionOrden = mysqli_query( $db, $sqlClasesValidacionOrden );

		$validacionOrden = mysqli_num_rows( $resultadoClasesValidacionOrden );

		if ( $validacionOrden > 0 ) {
			
			$sqlClases = "
				SELECT *
				FROM bloque
				INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
				WHERE id_sub_hor = '$id_sub_hor'
				ORDER BY ord_blo ASC
			";


		} else {

			$sqlClases = "
				SELECT *
				FROM bloque
				INNER JOIN sub_hor ON sub_hor.id_mat1 = bloque.id_mat6
				WHERE id_sub_hor = '$id_sub_hor'
			";

		}

		


		// echo $sqlClases;

		

		$resultadoClases = mysqli_query( $db, $sqlClases );
		
		$contador = 1;
	?>
	
	<div class="row contenedor_elementos_ordenables" estatus="Inactivo" id="example1">
	<?php  
		while( $filaClases = mysqli_fetch_assoc( $resultadoClases ) ){
			$id_blo = $filaClases['id_blo'];
	?>

		<div class="col-md-4 p-3 listaBloques" id="bloque<?php echo $id_blo; ?>" id_blo="<?php echo $id_blo; ?>">
			
			<div class="card elementosOrdenables "  style="border-radius: 10px;" >
				

				<?php  
					if ( $filaClases['img_blo'] == NULL ) {
				?>
				

					<div class="card-header border " style="border-radius: 10px;
						background-image: url('../fondos_clase/img_backtoschool.jpg'); height: 100px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; ">

				<?php	
					} else {
				?>

						<div class="card-header border " style="border-radius: 10px;
						background-image: url('../fondos_clase/<?php echo $filaClases['img_blo']; ?>'); height: 100px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; ">

				<?php
					}

				?>
				
					

					<div class="row">
						<div class="col-md-9">

								<h5 class="font-weight-normal">
									
									<a class="white-text btn-link waves-effect" href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo $id_blo; ?>" target="_blank">
										<i class="fas fa-circle"></i> <?php echo $filaClases['nom_blo']; ?>
									</a>
									
								</h5>


							

							<p class="white-text font-weight-normal letraPequena">

								<?php echo $filaClases['des_blo']; ?>	

							</p>

						</div>

						<div class="col-md-3 text-right">
							<!--Dropdown primary-->
							<div class="dropdown clasePadreClaseMateria">

							  <!--Trigger-->

								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>


							  <!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect" href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo $id_blo; ?>" target="_blank">
										Revisar
									</a>
						
									<?php  
										if ( $estatusUsuario == 'Activo' ) {
									?>
											<a class="dropdown-item waves-effect eliminacionBloque" nom_blo="<?php echo $filaClases['nom_blo']; ?>" id_blo="<?php echo $filaClases['id_blo']; ?>">
												Eliminar
											</a>

									<?php
										}
									?>
									
									
									

								</div>


								<?php  
		            
					                if ( obtenerTotalNotificacionesBloqueGrupo( $id, $id_sub_hor, $id_blo ) > 0 ) {
					            
					            ?>
					                	<span class="badge badge-danger claseHijoClaseMateria" title="Tienes <?php echo obtenerTotalNotificacionesBloqueGrupo( $id, $id_sub_hor, $id_blo ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesBloqueGrupo( $id, $id_sub_hor, $id_blo ); ?></span>

					            <?php
					            
					                }
					            
					            ?>

							</div>
							<!--/Dropdown primary-->
							
							
						</div>

					</div>

					
					
				</div>

		      	<div class="card-body text-left">
			        

			        <div class="row">
			        	<div class="col-md-6">
			        		<p class="grey-text font-weight-bold letraMediana">
								<i class="fas fa-circle grey-text"></i>

								<?php
									

									$totalActividades = obtenerTotalActividadesGrupo( $id_sub_hor, $id_blo ); 
									echo $totalActividades;
								?> actividades
							
							</p>
			        	</div>

			        	<div class="col-md-6">
			        		<p class="grey-text font-weight-bold letraMediana">
								<i class="fas fa-circle grey-text"></i>

								<?php
									

									$totalRecursosTeoricos = contadorRecursosTeoricos( $id_blo ); 
									echo $totalRecursosTeoricos;
								?> recursos teóricos
							
							</p>
			        		
			        	</div>
			        </div>


			        
			        <!-- DESPLIEGUE DE ACTIVIDADES -->
			        <div style="overflow-y: auto; height: 50px;">
			        	<?php  
					        $sql = "
					        	SELECT id_for AS identificador, id_for_cop AS identificador_copia, nom_for AS titulo, des_for AS descripcion, fec_for AS fecha, tip_for AS tipo, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin
					        	FROM foro_copia
					        	INNER JOIN foro ON foro.id_for = foro_copia.id_for1
					        	WHERE id_sub_hor2 = '$id_sub_hor' AND id_blo4 = '$id_blo'
								UNION
								SELECT id_ent AS identificador, id_ent_cop AS identificador_copia, nom_ent AS titulo, des_ent AS descripcion, fec_ent AS fecha, tip_ent AS tipo, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin
								FROM entregable_copia
								INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
								WHERE id_sub_hor3 = '$id_sub_hor' AND id_blo5 = '$id_blo'
								UNION
								SELECT id_exa AS identificador, id_exa_cop AS identificador_copia, nom_exa AS titulo, des_exa AS descripcion, fec_exa AS fecha, tip_exa AS tipo, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin
								FROM examen_copia
								INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
								WHERE id_sub_hor4 = '$id_sub_hor' AND id_blo6 = '$id_blo'
					        	ORDER BY fecha DESC

					        ";


					        // echo $sql;
					        $resultado = mysqli_query( $db, $sql );
					        $i = 1;
					        while( $fila = mysqli_fetch_assoc( $resultado ) ) {

					        	$identificador = $fila['identificador_copia'];
					    		$tipo = $fila['tipo'];

					    		$datos = obtenerPorcentajeParticipacionActividad( $tipo, $identificador );

					    ?>


					    		<span class="letraPequena grey-text">
					    			<?php echo $fila['titulo']; ?> <strong><?php echo $datos['alumnos_responsables'].'/'.$datos['alumnos_totales']; ?></strong>
					    		</span>

					    		<br>


					    <?php
					    	}
					    ?>
			        </div>
			        <!-- FIN DESPLIEGUE DE ACTIVIDADES -->
			        

		      	</div>

		    </div>

			
		</div>



	<?php  
		if ( $contador % 3 == 0 ) {
	?>


		
		
	<?php
		}
	?>


<?php
	$contador++;
	}

?>

<!-- FIN CLASES -->


</div>


<?php 

	include( 'inc/footer.php' );

?>



<script>
	//ELIMINACION DE BLOQUE
	$('.eliminacionBloque').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var id_blo = $(this).attr("id_blo");
		var nombreBloque = $(this).attr("nom_blo");
		// console.log(BLOQUE);

		swal({
		  title: "¿Deseas eliminar "+nombreBloque+"?",
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
				url: 'server/eliminacion_bloque.php',
				type: 'POST',
				data: {id_blo},
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


<script>
	
	var r = 254;
	var porcentajeAvance = 0;
	var limite2 = <?php echo $porcentajeAvance; ?>;
	iniciarCambioBarra( r, porcentajeAvance, limite2 );




	function iniciarCambioBarra( r, porcentajeAvance ){
		if( r > 50 || porcentajeAvance < limite2 ) {
		    setTimeout(function(){
		      	r = r - 2;
		      	$( '#barra_estado' ).css({
					background: 'rgb( '+r+', 255, 50)',
					width : porcentajeAvance+'%'
				}).text( porcentajeAvance+'%' );

				if ( porcentajeAvance < limite2 ) {
					porcentajeAvance++;
				}
				
		      	iniciarCambioBarra( r, porcentajeAvance, limite2 );
		    }, 50 );
	  	}
	}
	
</script>

<script>
	toastr.info('Semana <?php $fechaHoy=date( 'Y-m-d' );$diferenciaDias=obtenerDiferenciaFechas( $fechaHoy, $ini_cic );echo floor( $diferenciaDias / 7 );$diasCiclo=obtenerDiferenciaFechas( $fin_cic, $ini_cic );$porcentajeAvance=floor( ( ( $diferenciaDias * 100 ) / $diasCiclo ) );// echo $porcentajeAvance;?> de trabajo');
</script>

<script>

	var example1 = document.getElementById('example1');

    var sort = new Sortable(example1, {
        animation: 150,
        ghostClass: 'blue-background-class',
        onEnd: function (evt) {
	        		
	    	// NUEVA POSICION
	    	var elementoOrden = evt.item;
	    	// var elementoDestino = evt.to;
	    	var elementoOperador = evt.to;

			var id_blo = $('#'+[elementoOrden][0].id+'').attr('id_blo');
			// var posicion = evt.newIndex;


			var id_ope = $('#'+[elementoOperador][0].id+' .listaBloques');

			console.log( id_blo );

			// alert( id_ope.length );

			for( var i = 0; i < id_ope.length ; i++ ){

				// console.log( id_ope.eq( i ).attr( 'id_ord' ) );

				id_blo = id_ope.eq( i ).attr( 'id_blo' );
				
				posicion = i;

				// console.log('id_blo:' +id_blo+ 'posicion: ' +posicion)
				$.ajax({
		    		url: 'server/editar_orden_bloque.php',
		    		type: 'POST',
		    		data: { id_blo, posicion },
		    		success: function( respuesta ){

		    			console.log( respuesta );

		    			// $('#'+[elementoDestino][0].id+'').find('.ordenesPendientes .estatusOrden').text('Pendiente').removeClass('badge-dark').addClass('badge-warning');

		    			// obtenerOrdenesOperador( id_ope );

		    			// obtenerContadorEstatus();    		
		    		}
		    	});

			}

			generarAlerta('Cambios guardados');

			// FIN NUEVA POSICION

    	}

    });


    var state = sort.option("disabled"); // get

    sort.option("disabled", !state);


    

</script>


<script>

	

	$('#btn_ordenar_clases').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var state = sort.option("disabled"); // get
		var switcher = $('#btn_ordenar_clases');

        sort.option("disabled", !state); // set
      
        switcher.innerHTML = state ? $('#btn_ordenar_clases').html('<i class="fas fa-lock-open"></i> Desactivar ordenamiento') : $('#btn_ordenar_clases').html('<i class="fas fa-lock"></i> Activar ordenamiento');


		// $('.elementosOrdenables').addClass('list-group-item');
		var contenedor_ordenable = $('.contenedor_elementos_ordenables');
		var estatus = contenedor_ordenable.attr('estatus');
		
		if ( estatus == 'Inactivo' ) {
	
			contenedor_ordenable.removeAttr('estatus').attr('estatus', 'Activo');
			
			$('.listaBloques').addClass('animated headShake infinite');

		} else if( estatus == 'Activo' ) {

			contenedor_ordenable.removeAttr('estatus').attr('estatus', 'Inactivo');
			// obtener_elementos_ordenables();
			
			$('.listaBloques').removeClass('animated headShake infinite');
		
		}
		

	});
</script>