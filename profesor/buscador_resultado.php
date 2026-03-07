<?php  

	include('inc/header.php');

?>



<!-- titulo -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Todas tus calificaciones"><i class="fas fa-bookmark"></i> Buscador</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Buscador</a>
		</div>
	</div>
</div>
<!-- fin titulo -->
<br>


<!-- BUSCADOR -->
<div class="row text-left">


	<!-- parte del estilo del parrafo-->
	<div class="col-md-6">
		<i class="fas fa-users fa-3x text-info"></i>
			
		<p>¡Encuentra a cualquier persona del plantel por medio del correo electrónico o su nombre y contáctalo!</p>
		<br>
		<form class="form-inline md-form form-sm mt-0" method="GET" action="buscador_resultado.php">

            <input type="text" autofocus class="form-control mr-sm-2" name="palabra"  placeholder="Buscar..." value="<?php echo ($_GET['palabra']); ?>">
            <br>
            <button type="submit" class="btn btn-info btn-sm" name="buscador" value=' Buscar '><i class="fas fa-search fa-2x"></i></button>
        </form>
    
	</div>

</div>
<!-- FIN BUSCADOR -->



<!-- RESULTADO BUSQUEDA -->
<div class="row text-center">

	<div class="col-md-12">
		<?php 

		if (isset($_GET['buscador'])){

		  $buscar = $_GET['palabra'];

		  if (empty($buscar)){
		    echo '
			        <div class="alert alert-danger" role="alert">
					  ¡Ingresa un correo electrónico o un nombre!
					</div>

		        ';
		  } else {

		    $sql = "
				SELECT cor_eje AS correo, pas_eje AS password, tip_eje AS tipo, nom_eje AS nombre, id_eje AS id, fot_emp AS foto
				FROM ejecutivo
				INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4
				WHERE nom_eje LIKE '%$buscar%' OR cor_eje LIKE '%$buscar%' AND id_pla6 = '$plantel'
		    	UNION
		    	SELECT cor_adm AS correo, pas_adm AS password, tip_adm AS tipo, nom_adm AS nombre, id_adm AS id, fot_emp AS foto
				FROM admin
				INNER JOIN empleado ON empleado.id_emp = admin.id_emp7
				WHERE nom_adm LIKE '%$buscar%' OR cor_adm LIKE '%$buscar%' AND id_pla6 = '$plantel'
				UNION
				SELECT cor_adg AS correo, pas_adg AS password, tip_adg AS tipo, nom_adg AS nombre, id_adg AS id, fot_emp AS foto
				FROM adminge
				INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6
				WHERE nom_adg LIKE '%$buscar%' OR cor_adg LIKE '%$buscar%' AND id_pla6 = '$plantel'
				UNION
				SELECT cor_adc AS correo, pas_adc AS password, tip_adc AS tipo, nom_adc AS nombre, id_adc AS id, fot_emp AS foto
				FROM adminco 
				INNER JOIN empleado ON empleado.id_emp = adminco.id_emp5
				WHERE nom_adc LIKE '%$buscar%' OR cor_adc LIKE '%$buscar%' AND id_pla6 = '$plantel'
				UNION
				SELECT cor_pro AS correo, pas_pro AS password, tip_pro AS tipo, nom_pro AS nombre, id_pro AS id, fot_emp AS foto
				FROM profesor 
				INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
				WHERE nom_pro LIKE '%$buscar%' OR cor_pro LIKE '%$buscar%' AND id_pla6 = '$plantel'
				UNION
				SELECT cor_cob AS correo, pas_cob AS password, tip_cob AS tipo, nom_cob AS nombre, id_cob AS id, fot_emp AS foto
				FROM cobranza 
				INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
				WHERE nom_cob LIKE '%$buscar%' OR cor_cob LIKE '%$buscar%' AND id_pla6 = '$plantel'
				UNION
				SELECT cor_alu AS correo, pas_alu AS password, tip_alu AS tipo, nom_alu AS nombre, id_alu AS id, fot_alu AS foto
				FROM alumno 
				WHERE nom_alu LIKE '%$buscar%' OR cor_alu LIKE '%$buscar%' AND id_pla8 = '$plantel'
			";
		    $result = mysqli_query($db, $sql);

		    $total = mysqli_num_rows($result);

		    if ($fila = mysqli_fetch_assoc($result)) {
		    $i = 1;
		    echo "<br>";
		    echo "<h3>Total de resultados: $total</h3>";
		    echo '<table class="table table-hover small">
		              <tr class="bg-info">
		                            

		                
		               	<th>#</th>
		               	<th>Foto</th>
		                <th>Nombre</th>
		                <th>Tipo</th>
		                <th><i class="glyphicon glyphicon-wrench"></i> Acción</th>
		                
		              </tr>';

		      do {

		        echo 
		          ' 
		            <tr>

		               	<td>'.$i.'</td>
		               	<td><img src="../uploads/'.$fila["foto"].'" width="40px" height="40px" class="avatar rounded-circle mr-0 ml-3 z-depth-1"></td>
		                <td>'.$fila["nombre"].'</td>
		                <td>'.$fila["tipo"].'</td>
		                
		                ';
		                $i++;

		                     
		        echo '     
						
						<td class="text-center">
		                    <a href="" class="btn btn-primary btn-sm botonesModales" id="'.$fila['id'].'" tipoUsuario="'.$fila['tipo'].'" data-toggle="modal" data-target="#modalContactForm"><i class="far fa-envelope fa-2x"></i></a>
		                
		                </td>

		              
		            </tr>';

		             
		          
		      } while ($fila = mysqli_fetch_assoc($result));
		    } else {
		      echo '
		        
				<div class="alert alert-danger" role="alert">
				  ¡No se encontraron coincidencias!
				</div>
		        ';
		    }
		  }
		}
		?>
	</div>
</div>
<!-- FIN RESULTADO BUSQUEDA -->

<!-- INICIO MODAL -->
<div class="modal fade" id="enviarMensaje">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<img src="" width="40px" height="40px" class="avatar rounded-circle mr-0 ml-3 z-depth-1" id="fotoContacto">		      	
        <h5 class="modal-title w-100 font-weight-bold" id="nombreContacto"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
       

        <div class="md-form" id="divMsj">
          <i class="fas fa-pencil prefix grey-text"></i>
          <textarea type="text" id="msjContacto" class="md-textarea form-control" rows="4" destino="" tipo=""></textarea>
          <label data-error="wrong" data-success="right" for="form8">Escribe un mensaje...</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary float-right btn-sm" id="btn_send">Enviar  <i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>
<!--FIN MODAL  -->


<?php  

	include('inc/footer.php');

?>

<script>
  	$(document).on('click', '.botonesModales', function(){

  		var usuario = $(this).attr("id");
  		var tipoUsuario = $(this).attr("tipoUsuario");

  		$.ajax({
  			url: 'server/obtener_usuario.php',
  			type: 'POST',
  			data: {usuario, tipoUsuario},
  			dataType: 'json',

  			success: function(response){
              var datosUsuario = response;
              //console.log(response);
              $('#nombreContacto').html('Para: '+datosUsuario.nombre);
              $('#fotoContacto').attr({src: "../uploads/"+datosUsuario.foto});
              $('#enviarMensaje').modal('show');
              $('#msjContacto').attr({destino: datosUsuario.destino});
              $('#msjContacto').attr({tipo: datosUsuario.tipoDestino});

              $('#btn_send').on('click', function(event) {
              	event.preventDefault();

              	var mensaje = $('#msjContacto').val();

              	if (mensaje == "") {

              	}else{
              		
              		var idDestino = $('#msjContacto').attr("destino");
              		var tipoDestino = $('#msjContacto').attr("tipo");

              		$.ajax({
              			url: 'server/contacto.php',
              			type: 'POST',
              			data: {idDestino, tipoDestino, mensaje},
              			success: function(response){
              				console.log(response);
              				$('#msjContacto').val("");
              				toastr.info('¡Enviado correctamente!');


              			}
              		});
              		

              	}
              	

              });

            }
  		});

  		
  		
  		
  	});
</script>