<?php  

	include( 'inc/header.php' );
	$id_sub_hor = $_GET['id_sub_hor'];
	$id_alu_ram = $_GET['id_alu_ram'];

	if ( ( ( $estatus_general == 'Baja definitiva' ) || ( $estatus_general == 'Suspendido' ) || ( $estatus_general == 'Bloqueado' ) ) || ( $estatus == 'Inactivo' ) ) {
		header('location: index.php');
	}

	$sqlMateria = "
		SELECT *
		FROM alu_hor
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		WHERE id_sub_hor5 = '$id_sub_hor' AND id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo' AND est_sub_hor = 'Activo' AND id_alu = '$id'
	";


	// echo $sqlMateria;

	$resultadoValidacion = mysqli_query( $db, $sqlMateria );

	$validacion = mysqli_num_rows( $resultadoValidacion );

	if ( $validacion == 0 ) {
	
		header('location: not_found_404_page.php');
	
	}

	$resultadoMateria = mysqli_query( $db, $sqlMateria );

	$filaMateria = mysqli_fetch_assoc( $resultadoMateria );

	$id_pro = $filaMateria['id_pro1'];
	$nom_pro = $filaMateria['nom_pro'];
	$fot_emp = $filaMateria['fot_emp'];

	$nom_mat = $filaMateria['nom_mat'];


?>

<!-- NAVEGACION INTERNA -->
<?php  
	//echo obtenerNavegacionGrupo( $id_sub_hor, $id );
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
	    FROM alu_hor
	    INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
	    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
	    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	    WHERE id_alu_ram = '$id_alu_ram' AND est_alu_hor = 'Activo'
	    GROUP BY id_cic
	";


	// echo $sqlMaterias;

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
	  right: 5px;
	  top: 0px;
	  z-index: 2;
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



	/*SMALL CHAT*/
	.chat-room.small-chat {
	  /* position: fixed; */
	  /* bottom: 0; */
	  position: fixed;
	  right: 0%;
	  bottom: 0%;
	  z-index: 100;
	  border-bottom-left-radius: 0;
	  border-bottom-right-radius: 0;
	  width: 20rem; }
	  .chat-room.small-chat.slim {
	  height: 3rem; }
	  .chat-room.small-chat.slim .icons .feature {
	    display: none; }
	  .chat-room.small-chat.slim .my-custom-scrollbar {
	    display: none; }
	  .chat-room.small-chat.slim .card-footer {
	    display: none; }
	  .chat-room.small-chat .profile-photo img.avatar {
	    height: 2rem;
	    width: 2rem; }
	  .chat-room.small-chat .profile-photo .state {
	    position: relative;
	    display: block;
	    background-color: #007E33;
	    height: .65rem;
	    width: .65rem;
	    z-index: 2;
	    margin-left: 1.35rem;
	    left: auto;
	    top: -.5rem;
	    border-radius: 50%;
	    border: .1rem solid #fff; }
	  .chat-room.small-chat .profile-photo.message-photo {
	    margin-top: 2.7rem; }
	  .chat-room.small-chat .heading {
	    height: 2.1rem; }
	    .chat-room.small-chat .heading .data {
	      line-height: 1.5; }
	      .chat-room.small-chat .heading .data .name {
	        font-size: .8rem; }
	      .chat-room.small-chat .heading .data .activity {
	        font-size: .75rem; }
	  .chat-room.small-chat .icons {
	    padding-top: .45rem; }
	  .chat-room.small-chat .my-custom-scrollbar {
	    position: relative;
	    height: 18rem;
	    overflow: auto; }
	    .chat-room.small-chat .my-custom-scrollbar > .card-body {
	      height: 18rem; }
	      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .media img {
	        width: 3rem; }
	      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .media .media-body p {
	        font-size: .7rem; }
	      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .message-text {
	        margin-left: .1rem; }
	  .chat-room.small-chat .card-footer .form-control {
	    border: none;
	    padding: .375rem 0 .43rem 0;
	    font-size: .9rem; }
	    .chat-room.small-chat .card-footer .form-control:focus {
	      box-shadow: none; }

	  .bcg-preview {
	    height: 535px;
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

		

		$resultadoClases = mysqli_query( $db, $sqlClases );
		
		$contador = 1;
	?>
	
	<div class="row">
	<?php  
		while( $filaClases = mysqli_fetch_assoc( $resultadoClases ) ){
			$id_blo = $filaClases['id_blo'];
	?>

		<div class="col-md-4">
			
			<div class="card" style="border-radius: 10px;">
				
				<div class="card-header border " style="border-radius: 10px;
					background-image: url('../fondos_clase/<?php echo $filaClases['img_blo']; ?>'); height: 100px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; ">
					

					<div class="row">
						<div class="col-md-9">

								<h5 class="font-weight-normal">
									<a class="white-text btn-link waves-effect" href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo $id_blo; ?>&id_alu_ram=<?php echo $id_alu_ram; ?>" target="_blank">
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


								<?php  
		            
					                if ( obtenerTotalNotificacionesBloqueGrupo( $id_alu_ram, $id_sub_hor, $id_blo ) > 0 ) {
					            
					            ?>
					                	<span class="badge badge-danger font-weight-normal claseHijoClaseMateria" title="Tienes <?php echo obtenerTotalNotificacionesBloqueGrupo( $id_alu_ram, $id_sub_hor, $id_blo ); ?> actividades pendientes por revisar"><?php echo obtenerTotalNotificacionesBloqueGrupo( $id_alu_ram, $id_sub_hor, $id_blo ); ?></span>

					            <?php
					            
					                }
					            
					            ?>

							  <!--Trigger-->

								<a class="btn-floating white btn-sm waves-effect dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; top: -15%;">
									<i class="fas fa-ellipsis-v grey-text"></i>
				    			</a>


							  <!--Menu-->
								<div class="dropdown-menu dropdown-info">
									
									<a class="dropdown-item waves-effect" href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo $id_blo; ?>&id_alu_ram=<?php echo $id_alu_ram; ?>" target="_blank">
										Revisar
									</a>
									
									

								</div>


								

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
									// ✅ HARDCODEADO ALV - Cuenta actividades visibles del alumno
									$fechaHoy = date('Y-m-d');
									
									$sqlTotalAct = "
									  SELECT id_for_cop AS id
									  FROM foro_copia
									  INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
									  INNER JOIN foro ON foro.id_for = foro_copia.id_for1
									  INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
									  WHERE ( id_blo4 = '$id_blo' ) 
									    AND ( id_sub_hor2 = '$id_sub_hor' )
									    AND ( id_alu_ram4 = '$id_alu_ram' )
									    AND ( ini_cal_act <= '$fechaHoy' )
									  GROUP BY id_for_cop
									  UNION
									  SELECT id_ent_cop AS id
									  FROM entregable_copia
									  INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
									  INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
									  INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
									  WHERE ( id_blo5 = '$id_blo' ) 
									    AND ( id_sub_hor3 = '$id_sub_hor' )
									    AND ( id_alu_ram4 = '$id_alu_ram' )
									    AND ( ini_cal_act <= '$fechaHoy' )
									  GROUP BY id_ent_cop
									  UNION
									  SELECT id_exa_cop AS id
									  FROM examen_copia
									  INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
									  INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
									  INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
									  WHERE ( id_blo6 = '$id_blo' ) 
									    AND ( id_sub_hor4 = '$id_sub_hor' )
									    AND ( id_alu_ram4 = '$id_alu_ram' )
									    AND ( ini_cal_act <= '$fechaHoy' )
									  GROUP BY id_exa_cop
									";
									
									$resultadoTotalAct = mysqli_query( $db, $sqlTotalAct );
									$totalActividades = mysqli_num_rows( $resultadoTotalAct );
									
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
			        

		      	</div>

		    </div>

			
		</div>

	<?php  
		if ( $contador % 3 == 0 ) {
	?>

		</div>

		<hr>
		<div class="row">
	<?php
		}
	?>


<?php
	$contador++;
	}

?>

<!-- FIN CLASES -->





<?php /** 
<!-- MENSAJES -->
<div class="container mt-5">

  <!-- Grid row -->
  <div class="row d-flex flex-row-reverse">

    <!-- Grid column -->
    <div class="col-md-6 mb-4 d-flex flex-row-reverse">

      <div class="card chat-room small-chat wide" id="myForm">
        <div class="card-header white d-flex justify-content-between p-2">
          <div class="heading d-flex justify-content-start">
            
            <div class="profile-photo">
              <img src="../uploads/<?php echo $fot_emp; ?>" alt="profesor" class="avatar rounded-circle mr-2 ml-0">
         
            </div>
            <div class="data">
              <p class="name mb-0">
              	<strong>
              		<?php echo $nom_pro; ?>
              	</strong>
              </p>
              <p class="activity text-muted mb-0">Profesor</p>
            </div>
          </div>
          <div class="icons grey-text">
            
            <a id="toggle" style="cursor: pointer;">
            	<!-- <i class="fas fa-times "></i> -->
            	<i class="fas fa-window-minimize mr-2"></i>
            </a>
          </div>
        </div>

        <div class="my-custom-scrollbar" id="message">
          <div class="card-body p-3">
            <div class="chat-message">
              
              <div class="media mb-3">
                <img class="d-flex rounded mr-2" src="../uploads/<?php echo $fot_emp; ?>" alt="profesor">
                <div class="media-body">
                  <p class="my-0">Soy tu profesor de <?php echo $nom_mat; ?>, bienvenido a <?php echo $nombrePlantel; ?></p>
                  <p class="mb-0 text-muted">Cualquier duda, mándame un mensaje y a la brevedad te contesto ;)</p>
                </div>
              </div>
				
              
				<div id="contenedor_mensajes_sala">
					
				</div>
            </div>
          </div>
        </div>


        <div class="card-footer text-muted white pt-1 pb-2 px-3">
          <input type="text" id="input_mensaje" class="form-control" placeholder="Escribe al profesor...">
         
        </div>
      </div>

    </div>
    <!-- Grid column -->

  </div>
  <!-- Grid row -->

</div>

<!-- FIN MENSAJES -->
*/?>

<?php 

	include( 'inc/footer.php' );

?>


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



<!-- MENSAJES -->
<script>
	obtener_scroll();

	function obtener_mensajes_sala( id_sal ){

	    obtener_estatus_sala( id_sal );

	    $.ajax({
	      url: 'server/obtener_mensajes_minichat.php',
	      type: 'POST',
	      data: { id_sal },
	      success: function( respuesta ){

	        // console.log( respuesta );
	        $('#contenedor_mensajes_sala').html( respuesta );

	        $('#btn_enviar').attr('id_sal', id_sal);
	        $('#btn_consulta_usuarios').attr('id_sal', id_sal);
	        
	        obtener_scroll();

	      }
	    });

	}



	function obtener_estatus_sala( id_sal ){

	    $.ajax({
	      url: 'server/estatus_mensajes_sala.php',
	      type: 'POST',
	      data: { id_sal },
	      success: function( respuesta ){

	        console.log( respuesta );

	      }
	    
	    });

	}


	function obtener_scroll(){
		setTimeout(function(){
			var altura = $("#message").prop("scrollHeight") + 1000;
			$("#message").scrollTop(altura);
		}, 200);
	}




</script>


<script>
	obtener_mensaje_socket();
	function obtener_mensaje_socket(){

		socket.onmessage = function (event) {
	//console.log( event.data );

			var datos = JSON.parse(event.data);
			console.log(datos);

			if ( datos.modulo ) {


				if ( ( datos.modulo == 'Mensaje' ) ) {

					obtener_mensajes_sala( datos.id_sal );
					
				}

			}

		}
	
	}

	
</script>

<script>
//MENSAJES



// VALIDACION DE QUE EXISTE LA SALA
//CASO VERDADERO SE CARGAN LOS MENSAJES
// CASO FALSO NO ENTRA A LA CONDICION

// setTimeout(function(){
// 	$('#input_mensaje').focus();
// }, 300);


id_usuario = <?php echo $id_pro; ?>;
tipo_usuario = 'Profesor';



<?php  
	
	$existencia_sala = obtener_existencia_sala( $id, $tipo, $id_pro, 'Profesor' );

	// echo 'existencia_sala: '.$existencia_sala.' // id: '.$id.'// tipo: '.$tipo.' // id2: '.$id_pro.' // tipo2: Profesor'."<hr>";

	if ( $existencia_sala != 'Falso' ) {
?>
		
		obtener_mensajes_sala( <?php echo $existencia_sala; ?> );

<?php
	}
?>


	id_sal = '<?php echo $existencia_sala; ?>';




//CREACION DE SALA Y ENVIO DE MENSAJES
$("#input_mensaje").on("keypress", function(e) {
	  //const $eTargetVal = $(e.target).val();
	if (e.keyCode === 13 && $(this).val().length > 0) {
		
		var mensaje = $(this).val();
		

		$.ajax({
			url: 'server/agregar_mensaje.php',
			type: 'POST',
			data: { id_sal, id_usuario, tipo_usuario, mensaje },
			success: function( respuesta ){
				console.log( respuesta );

				if ( !isNaN( respuesta ) ) {

					var id_sal = respuesta;

					var datos = {
							    
					    modulo : 'Mensaje',
					    id_sal : id_sal

					};

					socket.send( JSON.stringify( datos ) );
					
					obtener_mensajes_sala( id_sal );

				}

				$('#input_mensaje').val("");
				//toastr.info('¡Enviado correctamente!');

			}
		});

	}else{
		console.log("Mensaje vacio");
	}
});
	
</script>
<!-- FIN MENSAJES -->