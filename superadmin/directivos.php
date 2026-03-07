<?php  

	include('inc/header.php');


?>

<!-- BOTON FLOTANTE AGREGAR DIRECTOR-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar Director" id="agregarDirector"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR DIRECTOR-->

<!-- CONTENIDO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Directivos del Plantel">
			<i class="fas fa-bookmark"></i> 
			Directivos
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Directivos</a>
</div>


<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 text-left">
		<?php  

		$sqlDirectivos = "
			SELECT 
			id_pla3,
			id_emp7 AS identificador_empleado,
			id_adm AS identificador,
			nom_adm AS nombre, 
			app_adm AS apellido1, 
			apm_adm AS apellido2, 
			gen_adm AS genero, 
			tel_adm AS telefono, 
			cor_adm AS correo, 
			nac_adm AS nacimiento, 
			ing_adm AS ingreso, 
			fot_emp AS foto, 
			dir_adm AS direccion, 
			cp_adm AS cp,
			pas_adm AS password,
			tip_adm AS tipo
			FROM admin
			INNER JOIN empleado ON empleado.id_emp = admin.id_emp7
            WHERE id_pla3 = '$plantel'
			UNION
			SELECT
            id_pla5,
            id_emp5 AS identificador_empleado,
            id_adc AS identificador,
			nom_adc AS nombre, 
			app_adc AS apellido1, 
			apm_adc AS apellido2, 
			gen_adc AS genero, 
			tel_adc AS telefono, 
			cor_adc AS correo, 
			nac_adc AS nacimiento, 
			ing_adc AS ingreso, 
			fot_emp AS foto, 
			dir_adc AS direccion, 
			cp_adc AS cp,
			pas_adc AS password,
			tip_adc AS tipo
			FROM adminco
			INNER JOIN empleado ON empleado.id_emp = adminco.id_emp5
            WHERE id_pla5 = '$plantel'
			UNION
			SELECT
            id_pla4,
            id_emp6 AS identificador_empleado,
            id_adg AS identificador,
			nom_adg AS nombre, 
			app_adg AS apellido1, 
			apm_adg AS apellido2, 
			gen_adg AS genero, 
			tel_adg AS telefono, 
			cor_adg AS correo, 
			nac_adg AS nacimiento, 
			ing_adg AS ingreso, 
			fot_emp AS foto, 
			dir_adg AS direccion, 
			cp_adg AS cp,
			pas_adg AS password,
			tip_adg AS tipo
			FROM adminge
			INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6
			WHERE id_pla4 = '$plantel'
			UNION
			SELECT
            id_pla9,
            id_emp8 AS identificador_empleado,
            id_cob AS identificador,
			nom_cob AS nombre, 
			app_cob AS apellido1, 
			apm_cob AS apellido2, 
			gen_cob AS genero, 
			tel_cob AS telefono, 
			cor_cob AS correo, 
			nac_cob AS nacimiento, 
			ing_cob AS ingreso, 
			fot_emp AS foto, 
			dir_cob AS direccion, 
			cp_cob AS cp,
			pas_cob AS password,
			tip_cob AS tipo
			FROM cobranza
			INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
            WHERE id_pla9 = '$plantel'
            ORDER BY ingreso DESC
			";
		$resultadoDirectivos = mysqli_query($db, $sqlDirectivos);


	?>
		<div class="table-responsive">
			
			<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
			
				<thead class="bg-info text-white">
					<tr>
						<th class="letraPequena">#</th>
						<th class="letraPequena">Foto</th>
						<th class="letraPequena">Nombre</th>
						<th class="letraPequena">Apellído Paterno</th>
						<th class="letraPequena">Apellído Materno</th>
						<th class="letraPequena">Género</th>
						<th class="letraPequena">Teléfono</th>
						<th class="letraPequena">Correo Electrónico</th>
						<th class="letraPequena">Nacimiento</th>
						<th class="letraPequena">Ingreso</th>
						<th class="letraPequena">Dirección</th>
						<th class="letraPequena">CP</th>
						<th class="letraPequena">Password</th>
						<th class="letraPequena">Tipo</th>
						<th class="letraPequena">Acción</th>
					</tr>
				</thead>

				<?php 
					$i = 1;
					while($filaDirectivos = mysqli_fetch_assoc($resultadoDirectivos)){

				?>
					<tr>
						<td class="letraPequena"><?php echo $i; $i++;?></td>

						<td class="letraPequena">
							
							<a href="<?php echo obtenerValidacionFotoUsuario( $filaDirectivos['foto'] ) ?>" data-lightbox="roadtrip" data-title="Foto de <?php echo $filaDirectivos['nombre']; ?>">
					                    
		                        <img src="<?php echo obtenerValidacionFotoUsuario( $filaDirectivos['foto'] ) ?>" class="img-fluid" style="height: 65px; width: 60px; border-radius: 40px;" title="Haz click para ampliar la foto de <?php echo $nombre; ?>">
		                    
		                    </a>

						</td>

						<td class="letraPequena"><?php echo $filaDirectivos['nombre']; ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['apellido1']; ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['apellido2']; ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['genero']; ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['telefono']; ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['correo']; ?></td>
						<td class="letraPequena"><?php echo fechaFormateadaCompacta($filaDirectivos['nacimiento']); ?></td>
						<td class="letraPequena"><?php echo fechaFormateadaCompacta($filaDirectivos['ingreso']); ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['direccion']; ?></td>
						<td class="letraPequena"><?php echo $filaDirectivos['cp']; ?></td>
						
							
						<td class="letraPequena">
							<?php  
								if ( $filaDirectivos['identificador_empleado'] != $id_emp  ) {
							?>
									<span title="Por cuestiones de seguridad cada usuario de tipo administrador será responsable de la gestión de sus propios datos /// Toda alta, cambio o baja será guardado en el LOG...">
										*********
									</span>
							<?php
								} else {
							?>
									<?php echo $filaDirectivos['password']; ?>
							<?php
								}
							?>
						</td>
						
						
						<td class="letraPequena">
							<?php 
								if($filaDirectivos['tipo'] == 'Admin') {
									echo "Director General";
								}else if($filaDirectivos['tipo'] == 'Adminco'){
									echo "Gerente Comercial";
								}else if($filaDirectivos['tipo'] == 'Adminge'){
									echo "Gestor Escolar";
								}else if ( $filaDirectivos['tipo'] == 'Cobranza' ) {
									echo "Caja";
								}
		 					?>
								
						</td>
						

						<!-- BOTONES DE ACCION -->
						<td class="letraPequena">
							<?php  
								if ( $filaDirectivos['identificador_empleado'] == $id_emp  ) {
							?>
									<a class="chip info-color text-white edicion letraPequena" title="Editar a <?php echo $filaDirectivos['nombre']; ?>" edicion="<?php echo $filaDirectivos['identificador']; ?>" tipo="<?php echo $filaDirectivos['tipo']; ?>">Editar</a>
							<?php
								}
							?>
										
						  


						  <a class="chip danger-color text-white eliminacion letraPequena" title="Eliminar a <?php echo $filaDirectivos['nombre']; ?>" identificador_empleado="<?php echo $filaDirectivos['identificador_empleado']; ?>" nombreDirector="<?php echo $filaDirectivos['nombre']; ?>">Eliminar</a>

												
						</td>
						<!-- FIN BOTONES DE ACCION -->

					</tr>


				<?php
					} 

				?>
			</table>

		</div>
	
		
	</div>
	
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR DIRECTOR -->
<div class="modal fade text-left" id="agregarDirectorModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarDirectorFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Director</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">


	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nom_dir" name="nom_dir" class="form-control validate">
	          <label  for="nom_dir">Nombre</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="app_dir" name="app_dir" class="form-control validate">
	          <label  for="app_dir">Apellído paterno</label>
	        </div>
			

			<div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apm_dir" name="apm_dir" class="form-control validate">
	          <label  for="apm_dir">Apellído materno</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-venus-mars prefix grey-text"></i>
	          <input type="text" id="gen_dir" name="gen_dir" class="form-control validate">
	          <label  for="gen_dir">Género</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-phone prefix grey-text"></i>
	          <input type="text" id="tel_dir" name="tel_dir" class="form-control validate">
	          <label  for="tel_dir">Teléfono</label>
	        </div>



	 		<label id="output"></label>
	        <div class="md-form mb-5">
	          	
	          	<i class="fas fa-envelope prefix grey-text"></i>
	          	<input type="text" id="correo" class="form-control" name="correo">
	          	<label for="correo">Correo electrónico</label>
	          
	        </div>


	      	<div class="md-form mb-5">
	          	
	          	<i class="fas fa-key prefix grey-text"></i>
	          	<input type="text" id="pas_dir" name="pas_dir" class="form-control validate">
	          	<label  for="pas_dir">Contraseña</label>
	        
	        </div>


	        <label for="Ingreso">Fecha de nacimiento</label><br>
	        <div class="md-form mb-2">
	          	
	          	<i class="far fa-calendar-check prefix grey-text"></i>
	          	<input type="date" id="nac_dir" name="nac_dir" class="form-control validate" value="<?php echo gmdate( 'Y-m-d', strtotime ( '- 10 year' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
	        
	        </div>


	        <label for="Ingreso">Fecha de Ingreso</label><br>
	        <div class="md-form mb-2">
	          	
	          	<i class="far fa-calendar-check prefix grey-text"></i>
	          	<input type="date" id="ing_dir" name="ing_dir" class="form-control validate" value="<?php echo date( 'Y-m-d' ); ?>">
	        
	        </div>


	        <div class="md-form mb-5">
			  <div class="file-field">
			    
			    <div class="btn btn-info btn-sm float-left">
			      <span>Sube una foto</span>
			      <input type="file" id="fot_dir" name="fot_dir">
			    </div>
			    
			    <div class="file-path-wrapper">
			      <input class="file-path validate" type="text" placeholder="3MB, JPEG, JPG o PNG">
			    </div>
			  
			  </div>
	    	</div>


	        <div class="md-form mb-3">
				<i class="fas fa-asterisk prefix grey-text"></i>
				<label  for="form34">Tipo de administrador</label>
				<br>
				<!-- Group of material radios - option 1 -->
				<!-- Group of material radios - option 1 -->
				<select class="mdb-select md-form colorful-select dropdown-primary" id="tip_dir" name="tip_dir">
				  	<option value="Admin" selected>Director General</option>
				  	<option value="Adminge">Gestor Escolar</option>
					<option value="Adminco">Gerente Comercial</option>
					<option value="Cobranza">Caja</option>
				</select>
			</div>

			<br>


	      	<div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="dir_dir" name="dir_dir" class="form-control validate">
	          <label  for="dir_dir">Dirección</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="cp_dir" name="cp_dir" class="form-control validate">
	          <label  for="cp_dir">Código postal</label>
	        </div>


			
	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	      <h3 id="validacionCorreo"></h3><br>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR DIRECTOR -->



<!-- CONTENIDO MODAL EDITAR DIRECTOR -->
<div class="modal fade text-left" id="editarDirectorModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarDirectorFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Director</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="nombre">Nombre</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apellido1" name="apellido1" class="form-control validate">
	          <label  for="apellido1">Apellído paterno</label>
	        </div>
			

			<div class="md-form mb-5">
	          <i class="far fa-address-card prefix grey-text"></i>
	          <input type="text" id="apellido2" name="apellido2" class="form-control validate">
	          <label  for="apellido2">Apellído materno</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-venus-mars prefix grey-text"></i>
	          <input type="text" id="genero" name="genero" class="form-control validate">
	          <label  for="genero">Género</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-phone prefix grey-text"></i>
	          <input type="text" id="telefono" name="telefono" class="form-control validate">
	          <label  for="telefono">Teléfono</label>
	        </div>



	        <label id="outputEdicion"></label>
	        <div class="md-form mb-5">
	          <i class="fas fa-envelope prefix grey-text"></i>
	          <input type="text" id="correoEdicion" class="form-control" name="correoEdicion">
	          <label for="correoEdicion">Correo electrónico</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-key prefix grey-text"></i>
	          <input type="text" id="password" name="password" class="form-control validate">
	          <label  for="password">Contraseña</label>
	        </div>


	        <label for="Nacimiento">Fecha de nacimiento</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="nacimiento" name="nacimiento" class="form-control validate">
	        </div>


	        <label for="Ingreso">Fecha de ingreso</label><br>
	        <div class="md-form mb-2">
	          <i class="far fa-calendar-check prefix grey-text"></i>
	          <input type="date" id="ingreso" name="ingreso" class="form-control validate">
	        </div>



	    	<div class="md-form mb-3" id="divTipo">
				<i class="fas fa-asterisk prefix grey-text"></i>
				<label  for="form34">Tipo de administrador</label>
				<br>
				<!-- Group of material radios - option 1 -->
				<select class="mdb-select md-form colorful-select dropdown-primary" id="tipo" name="tipo">
				  	<option value="Admin">Director General</option>
				  	<option value="Adminge">Gestor Escolar</option>
					<option value="Adminco">Gerente Comercial</option>
					<option value="Cobranza">Caja</option>
				</select>
			</div>

			<br>


	      	<div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="direccion" name="direccion" class="form-control validate">
	          <label  for="direccion">Dirección</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-map-marker-alt prefix grey-text"></i>
	          <input type="text" id="codigo" name="codigo" class="form-control validate">
	          <label  for="codigo">Código postal</label>
	        </div>
		
		

	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">
	        </div>



	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-success" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	      <h3 id="validacionCorreoEdicion"></h3><br>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR DIRECTOR -->




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
		

	
	});
</script>


<script>
	//VALIDACION EN TEMPO REAL DESDE EL INPUT DEL CORREO
	validacionCorreoTiempoReal();
	function validacionCorreoTiempoReal(){
		$('#correo').keyup(function(event) {
	        var correo = $('#correo').val();

	        console.log($('#correo').val());

	        if (correo != '') {
	          $.ajax({
	            url: 'server/validacion_correo.php',
	            type: 'POST',
	            data: {correo},
	            success: function(response){
	            	var respuesta = response; 


	              if (respuesta == 'disponible') {
	                
	                $('#output').attr({
	                	class: 'text-info'
	                });
	                $('#output').text("¡El correo electrónico está disponible!");

	              }else{
	              	$('#output').attr({
	                	class: 'text-danger'
	                });
	                $('#output').text("¡El correo electrónico está ocupado!");

	              }
	            }
	          })

	        }else{
	          $('#output').attr({class: 'text-warning'});
	          $('#output').text("¡Ingresa un Correo Electrónico!");
	        }
	        
	    });
	}



	validacionCorreoTiempoRealEdicion();
	function validacionCorreoTiempoRealEdicion(){
		$('#correoEdicion').keyup(function(event) {
	        var correoEdicion = $('#correoEdicion').val();
	        var identificador = $('#identificador').val();
	        var tipo = $('#tipo').attr("value");


	        if (correoEdicion != '') {
	          $.ajax({
	            url: 'server/validacion_correo.php',
	            type: 'POST',
	            data: {correoEdicion, identificador, tipo},
	            success: function(response){
	            	var respuesta = response; 


	              if (respuesta == 'disponible') {
	                
	                $('#outputEdicion').attr({
	                	class: 'text-info'
	                });
	                $('#outputEdicion').text("¡El correo electrónico está disponible!");

	              } else if (respuesta == 'mio') {
	                
	                $('#outputEdicion').attr({
	                	class: 'text-warning'
	                });
	                $('#outputEdicion').text("¡El correo electrónico es el mismo!");

	              } else{
	              	$('#outputEdicion').attr({
	                	class: 'text-danger'
	                });
	                $('#outputEdicion').text("¡El correo electrónico está ocupado!");

	              }
	            }
	          })

	        }else{
	          $('#outputEdicion').attr({class: 'text-warning'});
	          $('#outputEdicion').text("¡Ingresa un Correo Electrónico!");
	        }
	        
	    });
	}


</script>

<script>

	//FORMULARIO DE CREACION DE DIRECTOR
	//CODIGO PARA AGREGAR DIRECTOR NUEVO ABRIENDO MODAL


	$('#agregarDirector').on('click', function(event) {
		event.preventDefault();
		$('#agregarDirectorModal').modal('show');
		$('#agregarDirectorFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		$("#tip_dir").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
	});


	$('#agregarDirectorFormulario').on('submit', function(event) {
		event.preventDefault();


		var correo = $('#correo').val();


		$.ajax({
			url: 'server/validacion_correo.php',
			type: 'POST',
			data: {correo},
			success: function(respuesta){
				if (respuesta == "disponible") {

					$('#validacionCorreo').attr({class: 'text-info text-center'}).text("¡Correcto!");
					if ($("#fot_dir")[0].files[0]) {

						var fileName = $("#fot_dir")[0].files[0].name;
						var fileSize = $("#fot_dir")[0].files[0].size;


						var ext = fileName.split('.').pop();

						
						if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
							if (fileSize < 3000000) {
								$.ajax({
						
									url: 'server/agregar_directivo.php',
									type: 'POST',
									data: new FormData(agregarDirectorFormulario), 
									processData: false,
									contentType: false,	
									cache: false,
									success: function(respuesta){
									console.log(respuesta);

										if (respuesta == 'Exito') {
											swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
											then((value) => {
											  window.location.reload();
											});
											
										}
									}
								});
							}else{
								swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
							}
							
						}else{
							swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
						}

					}else{

						//VALIDACION SI MANDA FOTO, EN CASO DE MANDAR VALIDA, SI NO, ACCEDE DIRECTAMENTE
						$.ajax({
						
							url: 'server/agregar_directivo.php',
							type: 'POST',
							data: new FormData(agregarDirectorFormulario), 
							processData: false,
							contentType: false,
							cache: false,
							success: function(respuesta){
								console.log(respuesta);

								if (respuesta == 'Exito') {
									swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
									then((value) => {
									  window.location.reload();
									});
									
								}
							}
						});

					}

					
				}else{

					$('#validacionCorreo').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

				}
			}
		});	
			



		
			
			
		
	});


	
</script>


<script>
	//EDICION DE DIRECTOR

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DE EDICION DE DIRECTOR

	$('.edicion').on('click', function(){
		$('#editarDirectorFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionDirector = $(this).attr("edicion");
		var tipoDirector = $(this).attr("tipo");
		$('#editarDirectorFormulario label').addClass('active');
		$('#editarDirectorFormulario i').addClass('active');

		//console.log(edicionDirector);

		$.ajax({
			url: 'server/obtener_directivo.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionDirector, tipoDirector},
			success: function(datos){

				$('#editarDirectorModal').modal('show');


				$('#nombre').attr({value: datos.nom_dir});
				$('#apellido1').attr({value: datos.app_dir});
				$('#apellido2').attr({value: datos.apm_dir});
				$('#genero').attr({value: datos.gen_dir});
				$('#telefono').attr({value: datos.tel_dir});
				$('#correoEdicion').attr({value: datos.cor_dir});
				$('#password').attr({value: datos.pas_dir});
				$('#nacimiento').attr({value: datos.nac_dir});
				$('#ingreso').attr({value: datos.ing_dir});


				$("#tipo").materialSelect();//INICIALIZACION DE SELECT PORQUE AL CARGAR NO APARECE FUERA EL SELECTED**??
				$('#tipo').children().removeAttr('selected');//SE REMUEVE PARA QUE NO CHOQUE CON EL NUEVO A SELECCIONAR
				//AGREGADO A SELECT DEL DATO OBTENIDO MEDIANTE COMPARACION
				for(var i = 0 ; i < $('#tipo').children().length; i++){
					//console.log($('#tipo').children().eq(i).attr("value") );

					if ($('#tipo').children().eq(i).attr("value") == datos.tip_dir) {
						$('#tipo').children().eq(i).attr({"selected": ""});			
					}
				}

				$('#tipo').attr({value: datos.tip_dir});//AGREGADO DE VALUE PARA VALIDACIONDECORREOEDICIONREALTIME
				$('#divTipo').hide();
				
				$('#direccion').attr({value: datos.dir_dir});
				$('#codigo').attr({value: datos.cp_dir});				
				$('#identificador').attr({value: datos.id_dir});
				

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL DIRECTOR
				$('#editarDirectorFormulario').on('submit', function(event) {
					event.preventDefault();

					var correoEdicion = $('#correoEdicion').val();
					var identificador = $('#identificador').val();
					var tipo = $('#tipo').attr("value");


					$.ajax({
					  url: 'server/validacion_correo.php',
					  type: 'POST',
					  data: {correoEdicion, identificador, tipo},
					  success: function(respuesta){
					    console.log(respuesta);

					    if (respuesta == "disponible" || respuesta == "mio") {

					     	$('#validacionCorreoEdicion').attr({class: 'text-info text-center'}).text("¡Correcto!");
			     
					      	$.ajax({
									
								url: 'server/editar_directivo.php',
								type: 'POST',
								data: new FormData(editarDirectorFormulario),
								processData: false,
								contentType: false,
								cache: false,
								success: function(respuesta){
								console.log(respuesta);

									if (respuesta == 'Exito') {
										console.log("condicion");
										swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
										then((value) => {
										  window.location.reload();
										});
									}
								}
							});
					      
					    }else{

					      $('#validacionCorreoEdicion').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

					    }
					  }
					}); 
						
				});
				
			}
		});
		

	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE DIRECTOR	
</script>


<script>
	//ELIMINACION DE EMPLEADO
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var empleado = $(this).attr("identificador_empleado");
		var nombreDirector = $(this).attr("nombreDirector");

		// console.log(empleado);

		swal({
		  title: "¿Deseas eliminar a "+nombreDirector+"?",
		  text: "¡Una vez eliminado se perderán todos los datos relacionados a esa persona!",
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
				url: 'server/eliminacion_empleado.php',
				type: 'POST',
				data: {empleado},
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