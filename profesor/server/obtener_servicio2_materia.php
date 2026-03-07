
<style>
 @media (max-width: 400px) {
    .logo_video {
        width: 27vw !important; 
        position: absolute;  
        left: 10px !important;  
        top: 10px;
    }
}
</style>

<?php  
	//ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];

	// $descarga = $_POST['descarga'];
 //    $subida = $_POST['subida'];
 //    $latencia = $_POST['latencia'];

 //    // $sqlUpdate = "
 //    //     UPDATE alumno
 //    //     SET
 //    //     dow_alu = '$descarga',
 //    //     upl_alu = '$subida',
 //    //     pin_alu = '$latencia'
 //    //     WHERE id_alu = '$id'
 //    // ";

 //    // $resultadoUpdate = mysqli_query( $db, $sqlUpdate );

 //    // if ( !$resultadoUpdate ) {
 //    //     echo $sqlUpdate;
 //    // } else {

 //        $des_log = obtenerDescripcionInternetUsuarioLogServer( $tipo, $nombreCompleto, $descarga, $subida, $latencia  );
 //        logServer( 'Cambio', $tipoUsuario, $id, 'Internet', $des_log, $plantel );
 //    // }
	
	$sqlSala = "
		SELECT * 
		FROM sala
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = sala.id_sub_hor6
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
    	WHERE id_sub_hor = '$id_sub_hor'
	";

	// echo $sqlSala;

	$resultadoValidacionSala = mysqli_query($db, $sqlSala);

	$totalValidacionSala = mysqli_num_rows($resultadoValidacionSala);

	if ($totalValidacionSala == 0) {
		// NO EXISTE LA SALA
		//SE CREA LA SALA

		$sqlSubhor = "
			SELECT *
			FROM sub_hor
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			WHERE id_sub_hor = '$id_sub_hor'
		";


		$resultadoSubhor = mysqli_query($db, $sqlSubhor);

		if ($resultadoSubhor) {
			
			$filaSubhor = mysqli_fetch_assoc($resultadoSubhor);

			$nom_sal = "Sala de ".$filaSubhor['nom_mat'];
		
			$sqlInsercionSala = "
				INSERT INTO sala( nom_sal, id_sub_hor6, id_pla6 ) VALUES('$nom_sal', $id_sub_hor, '$plantel')
			";

			$resultadoInsercionSala = mysqli_query($db, $sqlInsercionSala);

			if ($resultadoInsercionSala) {
			// VALIDACION DE INSERCION
				$sqlMaximaSala = "
					SELECT MAX(id_sal) AS maxima 
					FROM sala
				";

				$resultadoMaximaSala = mysqli_query($db, $sqlMaximaSala);

				if ($resultadoMaximaSala) {
					// VALIDACION DE EXTRACCION DEL MAXIMO SALA
					$filaMaximaSala = mysqli_fetch_assoc($resultadoMaximaSala);

					$id_sal = $filaMaximaSala['maxima'];

					$sqlUltimaSala = "
						SELECT *
						FROM sala
						WHERE id_sal = '$id_sal'
					";

					$resultadoUltimaSala = mysqli_query($db, $sqlUltimaSala);

					if ($resultadoUltimaSala) {
						
						$filaUltimaSala = mysqli_fetch_assoc($resultadoUltimaSala);

						$nom_sal = $filaUltimaSala['nom_sal'];
					}


				}else{
					echo $sqlMaximaSala;
				}

				
			}else{
				echo $sqlInsercionSala;
			}



		}else{
			echo $sqlSubhor;
		}



	}else{
		
		$resultadoSalaMateria = mysqli_query($db, $sqlSala);

		$filaSalaMateria = mysqli_fetch_assoc($resultadoSalaMateria);

		//DATOS SALA
		$nom_sal = $filaSalaMateria['nom_sal'];
		$id_sal = $filaSalaMateria['id_sal'];


		//echo $sqlCompaneros;
	}


	$fechaHoy = date( 'Y-m-d H:i:s' );
	$des_log = "Registro de video-clase en ".$nom_sal." del profesor ".$nombreCompleto.". Registrado ".fechaHoraFormateadaCompactaServer( $fechaHoy ).".";
    logServer( 'Alta', $tipoUsuario, $id, 'Video-clase', $des_log, $plantel );

	
?>
	
	<div class="row">
		<div class="col-md-12 text-center" style="width: 100%; height: 600px;">
			<div id="meet">
				
			</div>
		</div>
	</div>


<script>

	// console.log( 'js de video sala' );
	var sala = '<?php echo $nom_sal.$id_sub_hor.$nombrePlantel; ?>';

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
            disableAudioLevels: true,

            backgroundImageUrl: '../uploads/<?php echo $fotoPlantel; ?>',
         // The anchor url used when clicking the logo image
	         logoClickUrl: '../uploads/<?php echo $fotoPlantel; ?>',
	         // The url used for the image used as logo
	         logoImageUrl: '../uploads/<?php echo $fotoPlantel; ?>',


        },

        interfaceConfigOverwrite: { 
     		
     		APP_NAME: 'Jitsi Meet',
    AUDIO_LEVEL_PRIMARY_COLOR: 'rgba(255,255,255,0.4)',
    AUDIO_LEVEL_SECONDARY_COLOR: 'rgba(255,255,255,0.2)',

    /**
     * A UX mode where the last screen share participant is automatically
     * pinned. Valid values are the string "remote-only" so remote participants
     * get pinned but not local, otherwise any truthy value for all participants,
     * and any falsy value to disable the feature.
     *
     * Note: this mode is experimental and subject to breakage.
     */
    AUTO_PIN_LATEST_SCREEN_SHARE: 'remote-only',
    BRAND_WATERMARK_LINK: '',

    CLOSE_PAGE_GUEST_HINT: false, // A html text to be shown to guests on the close page, false disables it
    /**
     * Whether the connection indicator icon should hide itself based on
     * connection strength. If true, the connection indicator will remain
     * displayed while the participant has a weak connection and will hide
     * itself after the CONNECTION_INDICATOR_HIDE_TIMEOUT when the connection is
     * strong.
     *
     * @type {boolean}
     */
    CONNECTION_INDICATOR_AUTO_HIDE_ENABLED: true,

    /**
     * How long the connection indicator should remain displayed before hiding.
     * Used in conjunction with CONNECTION_INDICATOR_AUTOHIDE_ENABLED.
     *
     * @type {number}
     */
    CONNECTION_INDICATOR_AUTO_HIDE_TIMEOUT: 5000,

    /**
     * If true, hides the connection indicators completely.
     *
     * @type {boolean}
     */
    CONNECTION_INDICATOR_DISABLED: false,

    DEFAULT_BACKGROUND: '#474747',
    DEFAULT_LOCAL_DISPLAY_NAME: 'me',
    DEFAULT_LOGO_URL: 'images/e.svg',
    DEFAULT_REMOTE_DISPLAY_NAME: 'Fellow Jitster',
    DEFAULT_WELCOME_PAGE_LOGO_URL: 'images/eqeq.svg',

    DISABLE_DOMINANT_SPEAKER_INDICATOR: false,

    DISABLE_FOCUS_INDICATOR: false,

    /**
     * If true, notifications regarding joining/leaving are no longer displayed.
     */
    DISABLE_JOIN_LEAVE_NOTIFICATIONS: false,

    /**
     * If true, presence status: busy, calling, connected etc. is not displayed.
     */
    DISABLE_PRESENCE_STATUS: false,

    /**
     * Whether the ringing sound in the call/ring overlay is disabled. If
     * {@code undefined}, defaults to {@code false}.
     *
     * @type {boolean}
     */
    DISABLE_RINGING: false,

    /**
     * Whether the speech to text transcription subtitles panel is disabled.
     * If {@code undefined}, defaults to {@code false}.
     *
     * @type {boolean}
     */
    DISABLE_TRANSCRIPTION_SUBTITLES: false,

    /**
     * Whether or not the blurred video background for large video should be
     * displayed on browsers that can support it.
     */
    DISABLE_VIDEO_BACKGROUND: false,

    DISPLAY_WELCOME_FOOTER: true,
    DISPLAY_WELCOME_PAGE_ADDITIONAL_CARD: false,
    DISPLAY_WELCOME_PAGE_CONTENT: false,
    DISPLAY_WELCOME_PAGE_TOOLBAR_ADDITIONAL_CONTENT: false,

    ENABLE_DIAL_OUT: true,

    ENABLE_FEEDBACK_ANIMATION: false, // Enables feedback star animation.

    FILM_STRIP_MAX_HEIGHT: 120,

    GENERATE_ROOMNAMES_ON_WELCOME_PAGE: true,

    /**
     * Hide the logo on the deep linking pages.
     */
    HIDE_DEEP_LINKING_LOGO: true,

    /**
     * Hide the invite prompt in the header when alone in the meeting.
     */
    HIDE_INVITE_MORE_HEADER: false,

    INITIAL_TOOLBAR_TIMEOUT: 20000,
    JITSI_WATERMARK_LINK: '',

    LANG_DETECTION: true, // Allow i18n to detect the system language
    LIVE_STREAMING_HELP_LINK: 'https://jitsi.org/live', // Documentation reference for the live streaming feature.
    LOCAL_THUMBNAIL_RATIO: 16 / 9, // 16:9

    /**
     * Maximum coefficient of the ratio of the large video to the visible area
     * after the large video is scaled to fit the window.
     *
     * @type {number}
     */
    MAXIMUM_ZOOMING_COEFFICIENT: 1.3,

    /**
     * Whether the mobile app Jitsi Meet is to be promoted to participants
     * attempting to join a conference in a mobile Web browser. If
     * {@code undefined}, defaults to {@code true}.
     *
     * @type {boolean}
     */
    MOBILE_APP_PROMO: true,

    /**
     * Specify custom URL for downloading android mobile app.
     */
    MOBILE_DOWNLOAD_LINK_ANDROID: 'https://play.google.com/store/apps/details?id=org.jitsi.meet',

    /**
     * Specify custom URL for downloading f droid app.
     */
    MOBILE_DOWNLOAD_LINK_F_DROID: 'https://f-droid.org/en/packages/org.jitsi.meet/',

    /**
     * Specify URL for downloading ios mobile app.
     */
    MOBILE_DOWNLOAD_LINK_IOS: 'https://itunes.apple.com/us/app/jitsi-meet/id1165103905',

    NATIVE_APP_NAME: 'Jitsi Meet',

    // Names of browsers which should show a warning stating the current browser
    // has a suboptimal experience. Browsers which are not listed as optimal or
    // unsupported are considered suboptimal. Valid values are:
    // chrome, chromium, edge, electron, firefox, nwjs, opera, safari
    OPTIMAL_BROWSERS: [ 'chrome', 'chromium', 'firefox', 'nwjs', 'electron', 'safari' ],

    POLICY_LOGO: null,
    PROVIDER_NAME: 'Jitsi',

    /**
     * If true, will display recent list
     *
     * @type {boolean}
     */
    RECENT_LIST_ENABLED: true,
    REMOTE_THUMBNAIL_RATIO: 1, // 1:1

    SETTINGS_SECTIONS: [ 'devices', 'language', 'moderator', 'profile', 'calendar', 'sounds' ],

    /**
     * Specify which sharing features should be displayed. If the value is not set
     * all sharing features will be shown. You can set [] to disable all.
     */
    // SHARING_FEATURES: ['email', 'url', 'dial-in', 'embed'],

    SHOW_BRAND_WATERMARK: false,

    /**
    * Decides whether the chrome extension banner should be rendered on the landing page and during the meeting.
    * If this is set to false, the banner will not be rendered at all. If set to true, the check for extension(s)
    * being already installed is done before rendering.
    */
    SHOW_CHROME_EXTENSION_BANNER: false,

    SHOW_DEEP_LINKING_IMAGE: false,
    SHOW_JITSI_WATERMARK: false,
    SHOW_POWERED_BY: false,
    SHOW_PROMOTIONAL_CLOSE_PAGE: false,

    /*
     * If indicated some of the error dialogs may point to the support URL for
     * help.
     */
    SUPPORT_URL: 'https://community.jitsi.org/',

    TOOLBAR_ALWAYS_VISIBLE: false,

    /**
     * DEPRECATED!
     * This config was moved to config.js as `toolbarButtons`.
     */
    // TOOLBAR_BUTTONS: [
    //     'microphone', 'camera', 'closedcaptions', 'desktop', 'embedmeeting', 'fullscreen',
    //     'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
    //     'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
    //     'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
    //     'tileview', 'select-background', 'download', 'help', 'mute-everyone', 'mute-video-everyone', 'security'
    // ],

    TOOLBAR_TIMEOUT: 4000,

    // Browsers, in addition to those which do not fully support WebRTC, that
    // are not supported and should show the unsupported browser page.
    UNSUPPORTED_BROWSERS: [],

    /**
     * Whether to show thumbnails in filmstrip as a column instead of as a row.
     */
    VERTICAL_FILMSTRIP: true,

    // Determines how the video would fit the screen. 'both' would fit the whole
    // screen, 'height' would fit the original video height to the height of the
    // screen, 'width' would fit the original video width to the width of the
    // screen respecting ratio.
    VIDEO_LAYOUT_FIT: 'both',

    /**
     * If true, hides the video quality label indicating the resolution status
     * of the current large video.
     *
     * @type {boolean}
     */
    VIDEO_QUALITY_LABEL_DISABLED: false,

    /**
     * How many columns the tile view can expand to. The respected range is
     * between 1 and 5.
     */
    // TILE_VIEW_MAX_COLUMNS: 5,

    /**
     * Specify Firebase dynamic link properties for the mobile apps.
     */
    // MOBILE_DYNAMIC_LINK: {
    //    APN: 'org.jitsi.meet',
    //    APP_CODE: 'w2atb',
    //    CUSTOM_DOMAIN: undefined,
    //    IBI: 'com.atlassian.JitsiMeet.ios',
    //    ISI: '1165103905'
    // },

    /**
     * Specify mobile app scheme for opening the app from the mobile browser.
     */
    // APP_SCHEME: 'org.jitsi.meet',

    /**
     * Specify the Android app package name.
     */
    // ANDROID_APP_PACKAGE: 'org.jitsi.meet',

    /**
     * Override the behavior of some notifications to remain displayed until
     * explicitly dismissed through a user action. The value is how long, in
     * milliseconds, those notifications should remain displayed.
     */
    // ENFORCE_NOTIFICATION_AUTO_DISMISS_TIMEOUT: 15000,

    // List of undocumented settings
    /**
     INDICATOR_FONT_SIZES
     PHONE_NUMBER_REGEX
    */

    // Allow all above example options to include a trailing comma and
    // prevent fear when commenting out the last value.
    // eslint-disable-next-line sort-keys
    makeJsonParserHappy: 'even if last key had a trailing comma'

        }





    };

    var api = new JitsiMeetExternalAPI(domain, options);


    setTimeout( function(){

		$( '#meet' ).css({
			position: 'relative'
		});
        $( '#meet' ).append( '<img class="logo_video" src="../uploads/<?php echo $fotoPlantel; ?>" style="width: 15vw; position: absolute;  left: 10px;  top: 10px;">' );

        
    }, 2000);

</script>