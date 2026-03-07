<?php  

	include('inc/header.php');

?>



         
<!-- Jumbotron -->
<div class="jumbotron text-center mdb-color  grey lighten-4  black-text mx-2 mb-5">

	<div id="calendario">
		
	</div>


</div>
<!-- Jumbotron -->


<!-- CONTENIDO MODAL AGREGAR EVENTOS -->
<div class="modal fade text-left" id="agregarEventoModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarEventoFormulario">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Agregar Evento</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>


	      <div class="modal-body mx-3">

	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="nom_eve" name="nom_eve" class="form-control validate" required="">
	          <label  for="form34">Evento</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="des_eve" name="des_eve" class="form-control validate">
	          <label  for="form34">Descripción</label>
	        </div>

	        <label for="Ingreso">Fecha</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-alt prefix grey-text"></i>
	          <input type="date" id="fec_eve" name="fec_eve" class="form-control validate" required="">
	        </div>

			
	        <label for="Color">Selecciona un Color</label><br>
	        <center>
	        	<div class="md-form mb-2">
		          <input type='text' id="custom" name="col_eve" />
		        </div>
	        	
	        </center>
	        

			

 


	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR EVENTOS -->


<?php  

	include('inc/footer.php');

?>


<script>
	$(document).ready(function(){




		$("#calendario").fullCalendar({

			height: 500,
			
			header: {
				left: 'today, prev, next, month',
				center: 'title',
				right: 'agregarEvento'
			},
			customButtons: {

				//AGREGADO DE EVENTO INASISTENCIA POR MODAL FORMULARIO
				agregarEvento: {
					text: "Agregar Evento",
					click: function(){
						$("#agregarEventoModal").modal('show');
						$("#agregarEventoFormulario")[0].reset(); //BORRADO DE INPUTS CON DISPARADOR
						$("#agregarEventoFormulario").on('submit', function(event) {
							event.preventDefault();
							/* Act on the event */
							var nom_eve = $("#nom_eve").val();
							var des_eve = $("#des_eve").val();
							var fec_eve = $("#fec_eve").val();
							var col_eve = $("#custom").val();

							$.ajax({
								url: 'server/agregar_evento.php?id_cic=<?php echo $_GET['id_cic']; ?>',
								data: new FormData(agregarEventoFormulario),
								cache: false, 
								processData: false,
								contentType: false,
								type: 'POST',
								success: function(respuesta){
									//$('#agregarEventoFormulario').trigger("reset");
									console.log(respuesta);
									

									
									if (respuesta != 'Error'){
										$('#calendario').fullCalendar('renderEvent', {
											id: respuesta,
											title: nom_eve,
											descripcion: des_eve,
											start: fec_eve,
											color: col_eve
										});
									$("#agregarEventoModal").modal('hide');

											// swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",})
											// .
											// then((value) => {
											//   window.location.reload();
											// });
									}

								}
							});
							
						});
					}
				}
			},
			//AGREGADO DE EVENTO INASISTENCIA POR EVENTO CLICK
			dayClick: function(date, jsEvent, view){
				
				var fechaEvento = date.format();
				var tipoEvento = 'Falta';

				$.ajax({
					url: 'server/agregar_evento.php?id_cic=<?php echo $_GET['id_cic']; ?>',
					type: 'POST',
					data: {fechaEvento, tipoEvento},
					success: function(respuesta){
						console.log(respuesta);
						
						if (respuesta != 'Error' && respuesta != 'false') {
							$('#calendario').fullCalendar('renderEvent', {
								id: respuesta,
								title: 'Sin clases',
								descripcion: 'No hay actividad académica',
								start: fechaEvento,
								color: '#f44336'
							});

							//swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",})
							// .
							// then((value) => {
							//   window.location.reload();
							// });
							
						}else if(respuesta == 'false'){
							swal("¡Acción Inválida!", "Ya guardaste una inasistencia en esta fecha", "warning", {button: "Aceptar",})
						}

					}
				});


			},
			//JSON DATA
			events: 'server/listar_eventos.php?id_cic=<?php echo $_GET['id_cic']; ?>',

			eventClick: function (calEvent, jsEvent, view) {   
				console.log(calEvent.id);   

				var evento = calEvent.id;
			    

			    $.ajax({
					url: 'server/eliminacion_evento.php',
					type: 'POST',
					data: {evento},
					success: function(respuesta){
						
						if (respuesta == "true") {
							console.log("Exito en consulta");


							$(".popover").remove();//DOLOR DE HUEVOS INTENSIFICADO A NIVEL DIOS
							//REMUEVE EL CINTILLO
						    $('#calendario').fullCalendar('removeEvents' , function(ev){  
							    return (ev._id == calEvent._id);


							});
						}else{
							console.log(respuesta);

						}

					}
				});
			    
				
			},




			//POPOVER
			eventRender: function(eventObj, $el) {
		      $el.popover({
		        title: eventObj.title,
		        content: eventObj.descripcion,
		        trigger: 'hover',
		        placement: 'top',
		        container: 'body'
		      });

		      // $el.on('click', function(event) {
		      // 	event.preventDefault();
		      // 	 Act on the event 
		      // 	console.log("click popover");
		      // 	//$(this).remove();
		      // });
		    }
		    
			
		});

		//UPDATE ** PINTAR CALENDARIO PANZA Y HEADER
		// $(".fc-head").addClass('blue');
		// $(".fc-day").addClass('yellow lighten-3');
	});






	//

</script>


<script>
$("#custom").spectrum({
	preferredFormat: "hex",
    color: "#0099CC"
});
</script>