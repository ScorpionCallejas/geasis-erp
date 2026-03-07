<?php  
	//ARCHIVO VIA AJAX PARA OBTENER ALUMNOS DE GENERACION
	//alumnos_carrera.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$alumnosInscripcion = $_POST['alumnosInscripcion'];
	
	if ( !isset( $_POST['validadorGlobal'] ) ) {
		$id_ram = $_POST['id_ram'];

		if ( isset( $_POST['id_gen'] ) ) {
			$id_gen = $_POST['id_gen'];

		}	
	}

	
  //var_dump($alumnosInscripcion);
?>

<!-- ALUMNOS SELECCIONADOS -->
<div class="row">

	<div class="col-md-1">
	
	</div>

	<div class="col-md-10">

		<p class="">
	
	      <span id="alumnosSeleccionados">
	      	<?php echo sizeof($alumnosInscripcion); ?>
	      </span> alumnos seleccionados
	    </p><br>

		<?php  
			for( $i = 0; $i < sizeof( $alumnosInscripcion ); $i++ ){
				$sqlAlumno = "
					SELECT *
					FROM alu_ram
					INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
					INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
					WHERE id_alu_ram = '$alumnosInscripcion[$i]'
				";

				//echo $sqlAlumno;

				$resultadoAlumno = mysqli_query($db, $sqlAlumno);

				$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
				$alumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu'];
				$programa = $filaAlumno['nom_ram'];
		?>
				<div class="chip seleccionAlumnoFinal" id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>" title="Dar de baja horario a <?php echo $alumno; ?> del programa <?php echo $programa; ?>">
				  <img src="../uploads/<?php echo $filaAlumno['fot_alu']; ?>"> 
				  <?php echo $filaAlumno['nom_alu']." ".$filaAlumno['app_alu']; ?>
				  <i class="close fas fa-times eliminacionSeleccionAlumnoFinal" title="Eliminar a <?php echo $filaAlumno['nom_alu']; ?> de la selección"></i>
				</div>

		<?php
			}
		?>

		<div class="progress md-progress" style="height: 20px" id="barra_baja">
		    <div class="progress-bar text-center white-text bg-info" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado">
		    	
		    
		    </div>
		</div>
	</div>


	<div class="col-md-1">
	
	</div>

	
</div>
<!-- FIN ALUMNOS SELECCIONADOS -->

<!-- JS -->


<script>
	//DESELECCION DE ALUMNOS A INSCRIBIR
	$(".eliminacionSeleccionAlumnoFinal").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var alumnosSeleccionados = $(".seleccionAlumnoFinal").length-1;
		$("#alumnosSeleccionados").text(alumnosSeleccionados);
		
		if ( alumnosSeleccionados < 1 ) {
			$("#modalInscripcion").modal('hide');
		}

	});

	// PEGADO DE BOTONES DE ACCION
	$("#footerModalBaja").html('<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" id="baja_materias" title="Eliminar carga de materias a estos alumnos">Dar de baja</button><button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Salir</button>');

	//BAJA DE MATERIAS
	$("#baja_materias").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		swal({
          title: "¿Deseas remover las materias de estos "+$(".seleccionAlumnoFinal").length+" alumnos?",
          text: "¡Una vez eliminados los alumnos perderán las actividades que ya realizaron!",
          icon: "warning",
          buttons:  {
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

            $(".eliminacionSeleccionAlumnoFinal").remove();
			let barra_estado = $("#barra_estado");
			var porcentaje;
			var contador;

		    for(var i = 0 ; i < $(".seleccionAlumnoFinal").length; i++){
		    	
		    	var id_alu_ram = $(".seleccionAlumnoFinal").eq(i).attr("id_alu_ram");

				//alert(porcentaje);

		    	$.ajax({
					ajaxContador: i,
			    	url: 'server/eliminacion_horarios_alumnos.php',
			    	type: 'POST',
			    	data: {id_alu_ram},
			    	beforeSend: function(){

						$("#baja_materias").removeClass('btn-danger').addClass('btn-secondary disabled').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');
					}
			    }).done(function(respuesta) {
		    		//console.log(respuesta);

		    		if ( $(".seleccionAlumnoFinal").eq(this.ajaxContador).attr("id_alu_ram") == respuesta ) {
		    			$(".seleccionAlumnoFinal").eq(this.ajaxContador).addClass('light-green accent-4 white-text');
		    		}

		    		contador = this.ajaxContador + 1;
	    			porcentaje = Math.floor( contador*(100/$(".seleccionAlumnoFinal").length), 2 );
					

					if (porcentaje <= 100) {
						
						barra_estado.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
				    		
				    	barra_estado.text(porcentaje+'%');
						
						if (porcentaje == 100) {
							barra_estado.removeClass();
							barra_estado.addClass('progress-bar text-center white-text bg-success');
							barra_estado.text("Listo");
				    		$(".seleccionAlumnoFinal").eq(i).addClass('light-green accent-4 white-text');

				    		$("#baja_materias").removeClass('btn-secondary').addClass('light-green accent-4 white-text').html('<i class="fas fa-check white-text"></i> <span>Baja Exitosa</span>');

				    		swal("Baja de Materias Exitosa", "Continuar", "success", {button: "Aceptar",}).
							then((value) => {
							  

							 	// obtenerAlumnosGeneraciones(); 
							 	reloadTableGeneral();
								reloadTable();
							 	$('#modalBaja').modal('hide');
							  


							});
						}

					}
		    		
			    });


		    }
		    // BUCLE FOR

            
            //FIN IF ELIMINACION ACEPTADA
          }
        });
		
		
		
	});
</script>
<!-- FIN JS -->