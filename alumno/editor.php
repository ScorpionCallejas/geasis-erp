<?php  

	include('inc/header.php');
	if (!$_GET) {
		//DOCUMENTO NUEVO
			
	
?>
<!--  ARCHIVO NUEVO -->



<!-- BOTON FLOTANTE OBTENER DOCUMENTO-->
<a class="btn-floating btn-lg  flotante btn-info" title="Abrir Documento" id="obtenerDocumentos"><i class="fas fa-folder-open fa-1x" ></i></a>
<!-- FIN BOTON FLOTANTE OBTENER DOCUMENTO-->


<!-- BOTON FLOTANTE NUEVO DOCUMENTO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 35px; left: 27px;" href="editor.php" target="_blank"><i class="fas fa-file fa-1x" title="Crear nuevo documento"></i></a>
<!-- FIN BOTON FLOTANTE NUEVO DOCUMENTO-->





<!-- ROW CABECERA EDITOR -->
<div class="row">
	<div class="col">
		<h2><i class="fas fa-pen-square fa-2x"></i> Editor </h2>

		
	</div>
	<div class="col text-right">
		

		<button id="btn_pdf" class="btn btn-red" title="Exportar a PDF">
        	<i class="fas fa-file-pdf fa-2x"></i>
        </button>

		<button id="btn_word" class="btn btn-blue" title="Exportar a Word">
        	<i class="fas fa-file-word fa-2x"></i>
        </button>

		<button class="btn btn-success" id="agregarDocumentoFormulario" title="Guardar Documento">
        	<i class="fas fa-save fa-2x"></i>
        </button>
		
	</div>
</div>

<!-- FIN ROW CABECERA EDITOR -->


<!-- CONTENIDO EDITOR-->
<div class="row">


		<div class="modal-body mx-3">

	      	<div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="tituloDocumento" class="form-control validate">
	          <label  for="form34">Asigna un título</label>
	        </div>


	         
	          <div id="boxDocumento">
				<div id="editorDocumento">
					<br><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
        		</div>
				
			</div>



	    </div>
</div>
<!-- FIN CONTENIDO EDITOR -->
<!-- FIN ARCHIVO NUEVO -->






<!-- ARCHIVO CARGADO -->
<?php  
	}else{
		//DOCUMENTO CARGADO
		$id_doc = $_GET['id_doc'];
		$sqlDocumento = "SELECT * FROM documento WHERE id_doc = '$id_doc'";
		$resultadoDocumento = mysqli_query($db, $sqlDocumento);
		$fila = mysqli_fetch_assoc($resultadoDocumento);

		$nom_doc = $fila['nom_doc'];
		$des_doc = $fila['des_doc'];
?>
	
<!-- BOTON FLOTANTE OBTENER DOCUMENTO-->
<a class="btn-floating btn-lg  flotante btn-info" title="Abrir Documento" id="obtenerDocumentos"><i class="fas fa-folder-open fa-1x" ></i></a>
<!-- FIN BOTON FLOTANTE OBTENER DOCUMENTO-->


<!-- BOTON FLOTANTE NUEVO DOCUMENTO-->
<a class="btn-floating btn-lg  flotante btn-info" style="bottom: 35px; left: 27px;" href="editor.php" target="_blank"><i class="fas fa-file fa-1x" title="Crear nuevo documento"></i></a>
<!-- FIN BOTON FLOTANTE NUEVO DOCUMENTO-->


<!-- ROW CABECERA EDITOR -->
<div class="row">
	<div class="col">
		<h2><i class="fas fa-pen-square fa-2x"></i> Editor </h2>

		
	</div>
	<div class="col text-right">
		

		<button id="btn_pdf" class="btn btn-red" title="Exportar a PDF">
        	<i class="fas fa-file-pdf fa-2x"></i>
        </button>

		<button id="btn_word" class="btn btn-blue" title="Exportar a Word">
        	<i class="fas fa-file-word fa-2x"></i>
        </button>

		<button class="btn btn-success" id="actualizarDocumentoFormulario" title="Actualizar Documento">
        	<i class="fas fa-save fa-2x"></i>
        </button>
		
	</div>
</div>

<!-- FIN ROW CABECERA EDITOR -->


<!-- CONTENIDO EDITOR-->
<div class="row">


		<div class="modal-body mx-3">

	      	<div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="tituloDocumento" class="form-control validate" value="<?php echo $nom_doc;?>">
	          <label  for="form34" class="active">Asigna un título</label>
	        </div>


	         
	          <div id="boxDocumento">
				<div id="editorDocumento">
					<?php  
						echo $des_doc;
					?>
        		</div>
				
			</div>



	    </div>
</div>


<?php  

	}
?>
<!-- FIN ARCHIVO CARGADO -->


<!-- CONTENIDO MODAL OBTENER DOCUMENTOS -->
<div class="modal fade text-left " id="cargarDocumentosModal">
  <div class="modal-dialog modal-lg" role="document">
    
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 font-weight-bold">
	        	<i class="far fa-folder-open fa-1x"></i> Mis Documentos
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      

		<!-- CUERPO DEL MODAL -->
	      <div class="modal-body mx-3" id="cuerpoModal">
	      	 <!-- SE CARGA ARCHIVO AJAX -->
				 


	      </div>
	      <!-- FIN CUERPO DEL MODAL -->

	      <div class="modal-footer d-flex justify-content-center">
	        
	      </div>

	    </div>


  </div>
</div>
<!-- FIN CONTENIDO MODAL OBTENER DOCUMENTOS -->





<?php  

	include('inc/footer.php');

?>
<script>

        var editorDocumento = new Jodit("#editorDocumento", {
            "language": "es",
            toolbarStickyOffset: 50

        });
</script>

<script>

	//FUNCION PARA EXPORTAR A WORD, SIN DEPENDENCIAS

	$("#btn_word").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtenerWord();
	});


	function obtenerWord(){
       var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
            "xmlns='http://www.w3.org/TR/REC-html40'>";
       
       var sourceHTML = header+editorDocumento.value;
       

       var tituloDocumento = $("#tituloDocumento").val();
       var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
       var fileDownload = document.createElement("a");
       document.body.appendChild(fileDownload);
       fileDownload.href = source;

       if (tituloDocumento != "") {
       		fileDownload.download = tituloDocumento+'.doc';
       }else{
       	fileDownload.download = 'documento.doc';
       }
       
       fileDownload.click();
       document.body.removeChild(fileDownload);
    }



    ///FUNCION PARA EXPORTAR A PDF, CON DEPENDENCIAS 


    $("#btn_pdf").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtenerPdf();
	});

    function obtenerPdf(){
	var pdf = new jsPDF('p', 'pt', 'letter');
	var contenidoDocumento = editorDocumento.value;
	specialElementHandlers = {
		'#bypassme': function(element, renderer){
			return true
		}
	}
	margins = {
	    top: 50,
	    left: 60,
	    width: 545
	  };
	pdf.fromHTML(
	  	contenidoDocumento // HTML string or DOM elem ref.
	  	, margins.left // x coord
	  	, margins.top // y coord
	  	, {
	  		'width': margins.width // max width of content on PDF
	  		, 'elementHandlers': specialElementHandlers
	  	},
	  	function (dispose) {
	  	  // dispose: object with X, Y of the last line add to the PDF
	  	  //          this allow the insertion of new lines after html
	        
			var tituloDocumento = $("#tituloDocumento").val();
		

			if (tituloDocumento != "") {
					pdf.save(tituloDocumento+'.pdf');
					fileDownload.download = tituloDocumento+'.doc';
			}else{
				pdf.save('documento.pdf');
			}
	      }
	  )		
	}

	
</script>



<script>

	//FORMULARIO DE CREACION DE DOCUMENTO
	//CODIGO PARA OBTENCION DE DOCUMENTO
	$('#obtenerDocumentos').on('click', function(event) {
		event.preventDefault();
		//console.log("documento");
		$('#cargarDocumentosModal').modal('show');
		

		$.ajax({
			url: 'server/obtener_documentos.php',
			type: 'POST',
			success: function(respuesta){
				//console.log(respuesta);
				$("#cuerpoModal").html("");

				$("#cuerpoModal").append(respuesta);
			}
		});
		
		
	});


	$('#agregarDocumentoFormulario').on('click', function(event) {
		event.preventDefault();


			
		var tituloDocumento = $("#tituloDocumento").val();
		var contenidoDocumento = editorDocumento.value;


		console.log(contenidoDocumento, tituloDocumento);

		$.ajax({
			url: 'server/agregar_documento.php',
			type: 'POST',
			data: {contenidoDocumento, tituloDocumento},


			success: function(respuesta){

				//console.log(respuesta);
				if (respuesta == "Exito") {
					console.log("Guardado Exitosamente");
					swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
					  // window.location.reload();
					});
				}
			}	
		});
	});


	


	
</script>


<?php  
	
	if ($_GET) {
?>

<script>
	
	$('#actualizarDocumentoFormulario').on('click', function(event) {
		event.preventDefault();


			
		var tituloDocumento = $("#tituloDocumento").val();
		var contenidoDocumento = editorDocumento.value;


		console.log(contenidoDocumento, tituloDocumento);

		$.ajax({
			url: 'server/editar_documento.php?id_doc=<?php echo $id_doc; ?>',
			type: 'POST',
			data: {contenidoDocumento, tituloDocumento},


			success: function(respuesta){

				//console.log(respuesta);
				if (respuesta == "Exito") {
					console.log("Guardado Exitosamente");
					swal("Actualizado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
					  // window.location.reload();
					});
				}
			}	
		});
	});
</script>

<?php
	}


?>