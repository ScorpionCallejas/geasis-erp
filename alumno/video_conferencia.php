<?php  
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('inc/cabeceras.php');
	require('inc/funciones.php');
	require_once(  __DIR__."/../includes/links_estilos.php");
	require_once(  __DIR__."/../includes/links_js.php");

	$id_sal = $_GET['id_sal'];
	
?>
	<title>
      <?php
        echo "Video-conferencia"; 
      ?>
    </title>

	<link rel="icon" href="../uploads/<?php echo $fotoPlantel; ?>">

	<style>
		body{
			background: #212121;
		}
	</style>

	<div class="row">
		<div class="col-md-12 text-center" style="width: 100%; height: 600px;">
			<div id="meet">
				
			</div>
		</div>
	</div>



<script>

	// console.log( 'js de video sala' );
	var sala = '<?php echo 'sala-'.$id_sal; ?>';

    var domain = '<?php echo $dominioVideo; ?>';
    var options = {
        roomName: sala,
        height: 600,
        parentNode: document.querySelector('#meet'),
        userInfo: {
            email: '<?php echo $correoUsuario; ?>',
            displayName: '<?php echo $tipoUsuario." - ".$nombreUsuario; ?>'
        },

       

        configOverwrite: { 

            defaultLanguage: 'es',
            remoteVideoMenu: {
                disableKick: true
            },
            // PROFESOR
            disableAudioLevels: true




        },

        interfaceConfigOverwrite: { 
     		
     		HIDE_DEEP_LINKING_LOGO: false,

            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            SHOW_BRAND_WATERMARK: false,
            DEFAULT_REMOTE_DISPLAY_NAME: 'Cargando...',
            DEFAULT_LOCAL_DISPLAY_NAME: 'Yo',
            SET_FILMSTRIP_ENABLED: false,
            DISABLE_FOCUS_INDICATOR: true,
            DISABLE_DOMINANT_SPEAKER_INDICATOR: true,
            DISABLE_VIDEO_BACKGROUND: true

            // JITSI_WATERMARK_LINK: 'https://google.com',
        }



    };

    var api = new JitsiMeetExternalAPI(domain, options);


    setTimeout( function(){

		$( '#meet' ).css({
			position: 'relative'
		});
        $( '#meet' ).append( '<img src="../uploads/<?php echo $fotoPlantel; ?>" width="10%" style="position: absolute;  right: 85%;  top: 3%; opacity: .3;">' );

        
    }, 7000);

</script>