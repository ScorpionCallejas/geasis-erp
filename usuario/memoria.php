<?php  
    include 'inc/header.php';
?>


<?php
    $archivos_sql = array();

    $sql = "
        select doc_tar as nombre from tarea
        union
        select  arc_arc as nombre from archivo
        union
        select arc_pro as nombre from proyecto
        union
        select fot_emp as nombre from empleado
        union
        select fot_alu as nombre from alumno
        union
        select arc_men as nombre from mensaje
        union
        select arc_doc_alu_ram as nombre from documento_alu_ram
        union
        select fot_pla as nombre from plantel
    ";
    $resultado = mysqli_query( $db, $sql );

    $k = 0;
    while( $fila = mysqli_fetch_assoc( $resultado ) ){
        $archivos_sql[$k] = $fila['nombre'];
        $k++; 
    }

?>

<?php  
    $archivos_ende = array();
    $files = array();
    $i = 0;
    $memoria = 0;
    $total = 0;
    $j = 0;

    foreach (new DirectoryIterator('../uploads/') as $fileInfo) {    
        // $files[$fileInfo->getFileName()] = date( "Y-m-d", $fileInfo->getCTime() );
        if ( $i > 1 ) {

            if ( 
                ( strpos( $fileInfo->getFileName(), 'archivo-recurso' ) !== false ) || 
                ( strpos( $fileInfo->getFileName(), 'foto-alumno' ) !== false ) || 
                ( strpos( $fileInfo->getFileName(), 'foto-empleado' ) !== false ) ||
                ( strpos( $fileInfo->getFileName(), 'proyecto-recurso-programa00' ) !== false ) ||
                ( strpos( $fileInfo->getFileName(), 'video-recurso' ) !== false )

            ) {

                // echo ( $i + 1 )." - ".$fileInfo->getFileName().' - '.date( "Y-m-d", $fileInfo->getMTime() )."<br>";
            } else {

                if ( date( "Y-m-d", $fileInfo->getMTime() ) > '2020-01-01' ) {

                    $memoria = ( $memoria + $fileInfo->getSize() / 1024 ) / 1024;
                    // echo ( $i + 1 )." - ".$fileInfo->getFileName().' - '.round( ( ( $fileInfo->getSize() /1024 ) / 1024 ), 2 ).'MB - '.date( "Y-m-d", $fileInfo->getMTime() )."<br>";
                    // unlink( "../uploads/".$fileInfo->getFileName() );
                    $total = $total + $memoria;

                    $archivos_ende[$j] = $fileInfo->getFileName();
                    $j++;
                }
                
            }
            
        }
        
        $i++;
    }


    // echo round( $total , 2)." MB totales en uploads";
    // arsort($files);


    $files = array();
    $i = 0;
    $memoria = 0;
    $total = 0;

    foreach (new DirectoryIterator('../archivos/') as $fileInfo) {    
        // $files[$fileInfo->getFileName()] = date( "Y-m-d", $fileInfo->getCTime() );
        if ( $i > 1 ) {

            if ( 
                ( strpos( $fileInfo->getFileName(), 'archivo-recurso' ) !== false ) || 
                ( strpos( $fileInfo->getFileName(), 'foto-alumno' ) !== false ) || 
                ( strpos( $fileInfo->getFileName(), 'foto-empleado' ) !== false ) ||
                ( strpos( $fileInfo->getFileName(), 'proyecto-recurso-programa00' ) !== false ) ||
                ( strpos( $fileInfo->getFileName(), 'video-recurso' ) !== false )

            ) {

                // echo ( $i + 1 )." - ".$fileInfo->getFileName().' - '.date( "Y-m-d", $fileInfo->getMTime() )."<br>";
            } else {

                if ( date( "Y-m-d", $fileInfo->getMTime() ) > '2020-01-01' ) {

                    $memoria = ( $memoria + $fileInfo->getSize() / 1024 ) / 1024;
                    // echo ( $i + 1 )." - ".$fileInfo->getFileName().' - '.round( ( ( $fileInfo->getSize() /1024 ) / 1024 ), 2 ).'MB - '.date( "Y-m-d", $fileInfo->getMTime() )."<br>";
                    // unlink( "../uploads/".$fileInfo->getFileName() );
                    $total = $total + $memoria;

                    $archivos_ende[$j] = $fileInfo->getFileName();
                    $j++;
                
                }
                
            }
            
        }
        
        $i++;
    }


    // echo round( $total , 2)." MB totales en archivos";

    // COMPARACION 

    $contador_existen = 0;
    $contador_no_existen = 0;
    foreach ($archivos_ende as $archivo) {
        $clave = array_search($archivo, $archivos_sql);
        if ($clave !== false) {
            // echo "El archivo $archivo se encuentra en ambos arreglos.\n";
            $contador_existen++;
        } else {
            $contador_no_existen++;
            // echo "El archivo $archivo no se encuentra en el arreglo \$archivos_ende.\n";
        }
    }

    echo "archivos_ende: ".sizeof( $archivos_ende );
    echo "<br>";
    echo "archivos_sql: ".sizeof( $archivos_sql );

    echo "total existen: ".$contador_existen;
    echo "<br>";
    echo "total no existen: ".$contador_no_existen;
    // FIN COMPARACION
?>






<?php  

  include 'inc/footer.php';
?>