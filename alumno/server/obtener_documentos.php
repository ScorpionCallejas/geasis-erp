<?php  
	//ARCHIVO VIA AJAX PARA OTENER TODOS LOS DOCUMENTOS
	//editor.php
	require('../inc/cabeceras.php');


	$sql = "SELECT * FROM documento WHERE id_alu2 = '$id' ORDER BY id_doc DESC";
	$resultado = mysqli_query($db, $sql);

	if ($resultado) {

		$contador = 1;
		$totalFilas = mysqli_num_rows($resultado);

	

		
		//$fila = mysqli_fetch_array($resultado);

		//var_dump($fila);


		for ($i=0; $i < $totalFilas/4; $i++) { 
			echo '
				<div class="row">';
			while($fila = mysqli_fetch_array($resultado)){
      
		      echo '
				<!-- Grid column -->
				    <div class="col-sm-3 text-center">
					
				      <!-- Card -->
				      <div class="card hoverable">


				        <!-- Content -->
				        <div class="card-body">

				          <!-- Offer -->
				          <h5 class="mb-4">'.$fila['nom_doc'].'</h5>
				     		<a class="btn btn-light-blue btn-rounded" href="editor.php?id_doc='.$fila['id_doc'].'" target="_blank"><i class="far fa-file-alt fa-2x" title="Abrir '.$fila['nom_doc'].'"></i></a>

				          <!-- Price -->
				          
				          <p class="grey-text">'.$fila['fec_doc'].'</p>

						  	

				        </div>
				        <!-- Content -->
				        <div class="row">
						    <div class="col"></div>
						    <div class="col"></div>
						    <div class="col">
								<a class="btn btn-danger btn-sm eliminacion" title="Eliminar '.$fila['nom_doc'].'" eliminacion="'.$fila['id_doc'].'" documento="'.$fila['nom_doc'].'"><i class="fas fa-times"></i></a>
						    </div>
						</div>
					  
				      </div>
				      <!-- Card -->

				    </div>
				    <!-- Grid column -->




				    
		      ';
		      if ($contador%4 == 0) {
		      	echo '
				</div><hr><div class="row">
				
				

				';
		      }
		      $contador++;
		    }
		     

		    	
		}

		echo '
		<script>
			  //ELIMINACION DE DOCUMENTO
			  $(".eliminacion").on("click", function(event) {
			    event.preventDefault();

			    console.log("borrar");
			    /* Act on the event */
			    var documento = $(this).attr("eliminacion");
			    var nombreDocumento = $(this).attr("documento");

			  	

			    swal({
			      title: "¿Deseas eliminar "+nombreDocumento+"?",
			      text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
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

			        $.ajax({
			        url: "server/eliminacion_documento.php",
			        type: "POST",
			        data: {documento},
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
		';


	}else{
		echo "error, verificar en consulta";
		//echo $sql;
	}


?>