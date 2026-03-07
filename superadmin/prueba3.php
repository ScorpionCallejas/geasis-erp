<?php  

    include 'inc/header.php';

    // $sql = "
    //     DELETE FROM materia WHERE ( id_ram2 = '36' ) AND ( id_ram2 = '37' ) AND ( id_ram2 = '38' ) AND ( id_ram2 = '39' ) AND ( id_ram2 = '40' )
    // ";

    // $emisor = 6;
    // $receptor = 36;

    // duplicarContenidoPrograma( $emisor, $receptor );


    // $emisor = 6;
    // $receptor = 37;

    // duplicarContenidoPrograma( $emisor, $receptor );

    // $emisor = 6;
    // $receptor = 38;

    // duplicarContenidoPrograma( $emisor, $receptor );


    // $emisor = 6;
    // $receptor = 39;

    // duplicarContenidoPrograma( $emisor, $receptor );

    // $emisor = 6;
    // $receptor = 40;

    // duplicarContenidoPrograma( $emisor, $receptor );

    // echo phpinfo();



?>


<?php  
    // $directorio = '../uploads/';

    // $ficheros  = scandir( $directorio );
    // $contador = 1;
    
    // for ( $i = 0;  $i < sizeof( $ficheros );  $i++ ) { 
    //     if ( $i > 1 ) {

    //         echo $contador." - ".$ficheros[$i]." - ".date( "Y-m-d H:i:s.", filectime( $ficheros[$i] ) )."<br>";
    //         $contador++;
    //     } //FIN IF
    // }// FIN for


    $files = array();
    $i = 0;
    $memoria = 0;
    $total = 0;

    foreach (new DirectoryIterator('../uploads/') as $fileInfo) {    
        // $files[$fileInfo->getFileName()] = date( "Y-m-d", $fileInfo->getCTime() );
        if ( $i > 1 ) {

            if ( 
                ( strpos( $fileInfo->getFileName(), 'archivo-recurso' ) !== false ) || 
                ( strpos( $fileInfo->getFileName(), 'foto-alumno' ) !== false ) || 
                ( strpos( $fileInfo->getFileName(), 'foto-empleado' ) !== false ) ||
                ( strpos( $fileInfo->getFileName(), 'video-recurso' ) !== false )
            ) {

                // echo ( $i + 1 )." - ".$fileInfo->getFileName().' - '.date( "Y-m-d", $fileInfo->getMTime() )."<br>";
            } else {

                if ( date( "Y-m-d", $fileInfo->getMTime() ) > '2020-01-01' ) {

                    $memoria = ( $memoria + $fileInfo->getSize() / 1024 ) / 1024;
                    echo ( $i + 1 )." - ".$fileInfo->getFileName().' - '.round( ( ( $fileInfo->getSize() /1024 ) / 1024 ), 2 ).'MB - '.date( "Y-m-d", $fileInfo->getMTime() )."<br>";
                    // unlink( "../uploads/".$fileInfo->getFileName() );
                    $total = $total + $memoria;
                
                }
                
            }
            
        }
        
        $i++;
    }


    echo round( $total , 2)." MB";
    // arsort($files);

    
?>






<?php  

  include 'inc/footer.php';
?>