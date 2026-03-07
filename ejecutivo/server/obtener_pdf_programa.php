<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
?>
<!-- Incluimos las librerías necesarias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card z-depth-1 bg-white" style="border-radius: 20px;">
            <div class="card-body">
                <!-- Editor TinyMCE -->
                <textarea id="editor"><?php echo isset($programa['nom_ram']) ? $programa['nom_ram'] : ''; ?></textarea>

                <div class="row mt-3">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-info white-text btn-rounded btn-sm" id="generarPDF">
                            Generar PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Esperamos a que TinyMCE esté disponible
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#editor',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
            setup: function(editor) {
                editor.on('init', function() {
                    // Editor está listo
                    console.log('TinyMCE cargado correctamente');
                });
            }
        });
    } else {
        console.error('TinyMCE no está disponible');
    }

    // Generación del PDF
    $("#generarPDF").on('click', function() {
        if (typeof tinymce === 'undefined') {
            swal("Error", "El editor no está disponible", "error");
            return;
        }

        var editor = tinymce.get('editor');
        if (!editor) {
            swal("Error", "No se pudo acceder al editor", "error");
            return;
        }

        var $btn = $(this).prop('disabled', true);
        var content = editor.getContent();

        if (!content) {
            swal("Advertencia", "El contenido está vacío", "warning");
            $btn.prop('disabled', false);
            return;
        }

        // Agregamos datos del programa
        var programaInfo = `
            ${content}
        `;

        // Creamos contenedor temporal
        var element = document.createElement('div');
        element.innerHTML = programaInfo;
        document.body.appendChild(element);

        var opt = {
            margin: 1,
            filename: 'programa.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(element).set(opt).save()
            .then(() => {
                document.body.removeChild(element);
                $btn.prop('disabled', false);
                swal("¡Éxito!", "PDF generado correctamente", "success");
            })
            .catch(err => {
                console.error('Error generando PDF:', err);
                document.body.removeChild(element);
                $btn.prop('disabled', false);
                swal("Error", "Hubo un problema al generar el PDF", "error");
            });
    });
});
</script>