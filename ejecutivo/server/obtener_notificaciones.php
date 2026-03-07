<?php
// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$sqlNotificaciones = "
    SELECT * FROM notificacion_pago WHERE est_not_pag = 'Pendiente2'
";
$totalNotificaciones = obtener_datos_consulta($db, $sqlNotificaciones)['total'];

if( $totalNotificaciones == 0 ){
	echo '<h3>Sin notificaciones</h3>';
} else {
	// 
	$resultadoNotificaciones = mysqli_query($db, $sqlNotificaciones);

	while ($filaNotificaciones = mysqli_fetch_assoc($resultadoNotificaciones)) {
		$id_not_pag = $filaNotificaciones['id_not_pag']; // Asegúrate de que este es el nombre correcto del campo ID
		$motivo = $filaNotificaciones['mot_not_pag'];
?>

		<!-- Contenido de la notificación -->
		<div class="notificacion-item text-center">
			<p><?php echo $motivo; ?></p>
			<!-- Botones de acción -->
			<div class="notificacion-acciones">
				<button class="btn btn-info btn-rounded respuestaNotificacion btn-sm" data-id="<?php echo $id_not_pag; ?>" data-action="Aprobar">Aprobar</button>
				<button class="btn btn-danger btn-rounded respuestaNotificacion btn-sm" data-id="<?php echo $id_not_pag; ?>" data-action="Rechazar">Rechazar</button>
			</div>
		</div>
		<hr>

<?php
	}
	// 
}
?>

<script>
	$('.respuestaNotificacion').on('click', function() {
        var id_not_pag = $(this).data('id');
        var est_not_pag = $(this).data('action');
        var elemento = $(this).closest('.notificacion-item');

        var actionText = est_not_pag === 'aprobar' ? 'Aprobar' : 'Rechazar';

        swal({
            title: "¿Estás seguro?",
            text: "¿Deseas realizar este cambio?",
            icon: "warning",
            buttons: ["Cancelar", 'Confirmar'],
            dangerMode: true,
        })
        .then((willDo) => {
            if (willDo) {
                $.ajax({
                    url: 'server/controlador_notificacion_pago.php',
                    type: 'POST',
                    data: {
                        est_not_pag,
                        id_not_pag
                    },
                    success: function(resp) {

						console.log(resp);
                       
						swal("¡Notificación " + actionText.toLowerCase() + "!", "Continuar", {
							icon: "success",
							button: "Aceptar",
						});
						obtener_notificaciones();
						
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la petición AJAX:', textStatus, errorThrown);
                        swal("Error en la comunicación con el servidor.", {
                            icon: "error",
                            button: "Aceptar",
                        });
                    }
                });
            } else {
                swal("La notificación no ha sido modificada.", {
                    button: "Aceptar",
                });
            }
        });
    });
</script>