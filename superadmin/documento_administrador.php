<?php  

	include('inc/header.php');

?>

<!-- ARCHIVO PARA VISUALIZAR DOCUMENTOS A TRAVES DE LA LIGA, CIFRAR ID EN URL -->


<!-- BOTON FLOTANTE OBTENER DOCUMENTO-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-folder-open fa-1x" title="Abrir Documento" id="obtenerDocumentos"></i></a>
<!-- FIN BOTON FLOTANTE OBTENER DOCUMENTO-->





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

		
	</div>
</div>

<!-- FIN ROW CABECERA EDITOR -->



<!-- CONTENIDO EDITOR-->
<div class="row">


		<div class="modal-body mx-3 bg-white">

			
					
					
					
					
					<h1>
					Primer&nbsp;ensayo acerca de las mariposas monarcas</h1><p><br></p><p>Erase una vez, un pequeno gusano, fin.<img src="https://cdn.shopify.com/s/files/1/0367/6021/products/Butterfly_Detail_1_grande.jpg?v=1521724181" alt="" style="width: 140px; height: 140px;"><br><br>
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
        		</p>        		        		        		        		        		

	    </div>
</div>
<!-- FIN CONTENIDO EDITOR -->




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
	//CODIGO PARA AGREGAR DOCUMENTO NUEVO ABRIENDO MODAL
	$('#obtenerDocumentos').on('click', function(event) {
		event.preventDefault();
		//console.log("documento");
		$('#cargarDocumentosModal').modal('show');
		$('#cargarDocumentosModal').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR

		$.ajax({
			url: 'server/obtener_documentos.php',
			type: 'POST',
			success: function(respuesta){
				console.log(respuesta);

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


<script>
	//BUSCADOR DEL DROPDOWN DE WIKI
	$('#your-custom-id').mdbDropSearch();
</script>


<script>
	//ELIMINACION DE WIKI
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var wiki = $(this).attr("eliminacion");
		var nombreWiki = $(this).attr("wiki");

		// console.log(WIKI);

		swal({
		  title: "¿Deseas eliminar "+nombreWiki+"?",
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
				url: 'server/eliminacion_wiki.php',
				type: 'POST',
				data: {wiki},
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
