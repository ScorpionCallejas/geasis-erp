<?php  
  include 'inc/header.php';
?>

  
<?php 
  // FUNCION QUE PERMITE COPIAR MATERIAS DE PROGRAMA_A A PROGRAMA_B HACIENDO LOS REGISTROS EN LA TABLA calificación
  // ADVERTENCIA -> ¡¡¡¡FUNCIONA CUANDO SON TODAS LAS MATERIAS!!!!
  function duplicarContenidoProgramaAlumnos( $emisor, $receptor ) {		
    require( '../includes/conexion.php' );
    
    $id_ram1 = $emisor;
    $id_ram2 = $receptor;

    $sqlMaterias1 = "
      SELECT *
      FROM materia
      WHERE id_ram2 = '$id_ram1'
    ";
    
    

    $resultadoMaterias1 = mysqli_query( $db, $sqlMaterias1 );

    while( $filaMaterias1 = mysqli_fetch_assoc( $resultadoMaterias1 ) ) {

      // EXTRACCION DE DATOS DE MATERIA DE P1
      $nom_mat1 = $filaMaterias1['nom_mat'];
      $cic_mat = $filaMaterias1['cic_mat'];
      $id_mat1 = $filaMaterias1['id_mat'];



      // INSERCION DE MATERIAS EN PROGRAMA RECEPTOR
      $sqlMaterias2 = "
      INSERT INTO materia ( nom_mat, cic_mat, id_ram2 )
      VALUES ( '$nom_mat1', '$cic_mat', '$id_ram2' )
      ";

      $resultadoMaterias2 = mysqli_query( $db, $sqlMaterias2 );

      if ( !$resultadoMaterias2 ) {
        echo $sqlMaterias2;
      }

      $sqlMaximoMateria = "
      SELECT MAX( id_mat ) AS maximo
      FROM materia
      WHERE id_ram2 = '$id_ram2'
      ";

      $resultadoMaximoMateria = mysqli_query( $db, $sqlMaximoMateria );

      $filaMaximoMateria = mysqli_fetch_assoc( $resultadoMaximoMateria );

      $id_mat2 = $filaMaximoMateria['maximo'];
      
      // ALUMNOS
      $sqlAlumnos = "
        SELECT * 
        FROM alu_ram 
        WHERE id_ram3 = $id_ram2;
      ";
      
      $resultadoAlumnos = mysqli_query( $db, $sqlAlumnos );
      
      while( $filaAlumnos = mysqli_fetch_assoc( $resultadoAlumnos ) ){
        $id_alu_ram2 = $filaAlumnos['id_alu_ram'];
        $sql_inserta_materia = "INSERT INTO calificacion (id_mat4, id_alu_ram2) VALUES ('$id_mat2', '$id_alu_ram2')";
        
        $resultado_inserta_materia = mysqli_query( $db, $sql_inserta_materia );
        
        if( !$resultado_inserta_materia ){
          echo $sql_inserta_materia;
        }
      }
      // ALUMNOS
      
      

      // BLOQUES

      $sqlBloques = "
      SELECT *
      FROM bloque
      WHERE id_mat6 = '$id_mat1'
      ";

      $resultadoBloques = mysqli_query( $db, $sqlBloques );

      while( $filaBloques = mysqli_fetch_assoc( $resultadoBloques ) ) {
        // DATOS DEL BLOQUE
        $id_blo = $filaBloques['id_blo'];
        $nom_blo = $filaBloques['nom_blo'];
        $des_blo = $filaBloques['des_blo'];
        $con_blo = $filaBloques['con_blo'];
        $img_blo = $filaBloques['img_blo'];
        
        // INSERCION DEL BLOQUE A MATERIAS DE P2

        $sqlInsercionBloque = "
        INSERT INTO bloque ( nom_blo, des_blo, con_blo, img_blo, id_mat6 )
        VALUES ( '$nom_blo', '$des_blo', '$con_blo', '$img_blo', '$id_mat2' )
        ";

        $resultadoInsercionBloque = mysqli_query( $db, $sqlInsercionBloque );

        if ( $resultadoInsercionBloque ) {
          
          // OBTENCION id_blo MAXIMO

          $sqlMaximoBloque = "
          SELECT MAX( id_blo ) AS maximo FROM bloque
          ";

          $resultadoMaximoBloque = mysqli_query( $db, $sqlMaximoBloque );

          $filaMaximoBloque = mysqli_fetch_assoc( $resultadoMaximoBloque );

          $id_blo_max = $filaMaximoBloque['maximo'];



          // DUPLICIDAD DE CONTENIDOS DE BLOQUE

          // VIDEOS
          // CONSULTA
          $sqlVideos = "
          SELECT *
          FROM video
          WHERE id_blo1 = '$id_blo'
          ";

          $resultadoVideos = mysqli_query( $db, $sqlVideos );

          while ( $filaVideos = mysqli_fetch_assoc( $resultadoVideos ) ) {
            $nom_vid = $filaVideos['nom_vid'];
            $des_vid = $filaVideos['des_vid'];
            $vid_vid = $filaVideos['vid_vid'];
            $url_vid = $filaVideos['url_vid'];
            $tip_vid = $filaVideos['tip_vid'];

            // INSERCION

            $sqlInsercionVideo = "
            INSERT INTO video ( nom_vid, des_vid, vid_vid, url_vid, tip_vid, id_blo1 ) 
            VALUES ( '$nom_vid', '$des_vid', '$vid_vid', '$url_vid', '$tip_vid', '$id_blo_max' )
            ";

            $resultadoInsercionVideo = mysqli_query( $db, $sqlInsercionVideo );

            if ( !$resultadoInsercionVideo ) {
              echo $sqlInsercionVideo;

              // break; break;
            }

          }
          

          // WIKIS
          // CONSULTA
          $sqlWikis = "
          SELECT *
          FROM wiki
          WHERE id_blo2 = '$id_blo'
          ";

          $resultadoWikis = mysqli_query( $db, $sqlWikis );

          while ( $filaWikis = mysqli_fetch_assoc( $resultadoWikis ) )  {

            $nom_wik = $filaWikis['nom_wik'];
            $des_wik = $filaWikis['des_wik'];
            $tip_wik = $filaWikis['tip_wik'];

            // INSERCION

            $sqlInsercionWiki = "
            INSERT INTO wiki ( nom_wik, des_wik, tip_wik, id_blo2 ) 
            VALUES ( '$nom_wik', '$des_wik', '$tip_wik', '$id_blo_max' )
            ";

            $resultadoInsercionWiki = mysqli_query( $db, $sqlInsercionWiki );
          }

          


          // ARCHIVOS
          // CONSULTA
          $sqlArchivos = "
          SELECT *
          FROM archivo
          WHERE id_blo3 = '$id_blo'
          ";

          $resultadoArchivos = mysqli_query( $db, $sqlArchivos );

          

          while ( $filaArchivos = mysqli_fetch_assoc( $resultadoArchivos ) ) {
            $nom_arc = $filaArchivos['nom_arc'];
            $des_arc = $filaArchivos['des_arc'];
            $arc_arc = $filaArchivos['arc_arc'];
            $tip_arc = $filaArchivos['tip_arc'];

            // INSERCION

            $sqlInsercionArchivo = "
            INSERT INTO archivo ( nom_arc, des_arc, arc_arc, tip_arc, id_blo3 ) 
            VALUES ( '$nom_arc', '$des_arc', '$arc_arc', '$tip_arc', '$id_blo_max' )
            ";

            $resultadoInsercionArchivo = mysqli_query( $db, $sqlInsercionArchivo );
          }


          // FOROS
          // CONSULTA
          $sqlForos = "
          SELECT *
          FROM foro
          WHERE id_blo4 = '$id_blo'
          ";

          $resultadoForos = mysqli_query( $db, $sqlForos );

          while ( $filaForos = mysqli_fetch_assoc( $resultadoForos ) ) {

            $nom_for = $filaForos['nom_for'];
            $des_for = $filaForos['des_for'];
            $tip_for = $filaForos['tip_for'];
            $pun_for = $filaForos['pun_for'];
            $ini_for = $filaForos['ini_for'];
            $fin_for = $filaForos['fin_for'];
            // INSERCION

            $sqlInsercionForo = "
            INSERT INTO foro ( nom_for, des_for, tip_for, pun_for, ini_for, fin_for, id_blo4 ) 
            VALUES ( '$nom_for', '$des_for', '$tip_for', '$pun_for', '$ini_for', '$fin_for', '$id_blo_max' )
            ";

            $resultadoInsercionForo = mysqli_query( $db, $sqlInsercionForo );
          }




          // ENTREGABLES
          // CONSULTA
          $sqlEntregables = "
          SELECT *
          FROM entregable
          WHERE id_blo5 = '$id_blo'
          ";

          $resultadoEntregables = mysqli_query( $db, $sqlEntregables );

          while ( $filaEntregables = mysqli_fetch_assoc( $resultadoEntregables ) ) {

            $nom_ent = $filaEntregables['nom_ent'];
            $des_ent = $filaEntregables['des_ent'];
            $tip_ent = $filaEntregables['tip_ent'];
            $pun_ent = $filaEntregables['pun_ent'];
            $ini_ent = $filaEntregables['ini_ent'];
            $fin_ent = $filaEntregables['fin_ent'];
            // INSERCION

            $sqlInsercionEntregable = "
            INSERT INTO entregable ( nom_ent, des_ent, tip_ent, pun_ent, ini_ent, fin_ent, id_blo5 ) 
            VALUES ( '$nom_ent', '$des_ent', '$tip_ent', '$pun_ent', '$ini_ent', '$fin_ent', '$id_blo_max' )
            ";

            $resultadoInsercionEntregable = mysqli_query( $db, $sqlInsercionEntregable );
          }

          


          // EXAMEN
        // CONSULTA
        $sqlExamenes = "
          SELECT *
          FROM examen
          WHERE id_blo6 = '$id_blo'
        ";

        $resultadoExamenes = mysqli_query( $db, $sqlExamenes );

        while ( $filaExamenes = mysqli_fetch_assoc( $resultadoExamenes ) )  {

          $id_exa = $filaExamenes['id_exa'];
          $nom_exa = $filaExamenes['nom_exa'];
          $des_exa = $filaExamenes['des_exa'];
          $tip_exa = $filaExamenes['tip_exa'];
          $pun_exa = $filaExamenes['pun_exa'];
          $ini_exa = $filaExamenes['ini_exa'];
          $fin_exa = $filaExamenes['fin_exa'];
          $dur_exa = $filaExamenes['dur_exa'];

          // INSERCION

          $sqlInsercionExamen = "
            INSERT INTO examen ( nom_exa, des_exa, tip_exa, pun_exa, ini_exa, fin_exa,  dur_exa, id_blo6 ) 
            VALUES ( '$nom_exa', '$des_exa', '$tip_exa', '$pun_exa', '$ini_exa', '$fin_exa', '$dur_exa', '$id_blo_max' )
          ";

          $resultadoInsercionExamen = mysqli_query( $db, $sqlInsercionExamen );

          if ( $resultadoInsercionExamen ) {
            // OBTENCION DE id_exa MAXIMO
            $sqlMaximoExamen = "
              SELECT MAX( id_exa ) AS maximo FROM examen
            ";

            $resultadoMaximoExamen = mysqli_query( $db, $sqlMaximoExamen );

            $filaMaximoExamen = mysqli_fetch_assoc( $resultadoMaximoExamen );

            $id_exa_max = $filaMaximoExamen['maximo'];

            // CONSULTA DE PREGUNTAS EN TABLA pregunta
            $sqlPreguntas = "
              SELECT *
              FROM pregunta
              WHERE id_exa2 = '$id_exa'
            ";

            $resultadoPreguntas = mysqli_query( $db, $sqlPreguntas );

            while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ) {
              // DATOS DE pregunta
              $id_pre = $filaPreguntas['id_pre'];
              $pre_pre = $filaPreguntas['pre_pre'];
              $pun_pre = $filaPreguntas['pun_pre'];


              $sqlInsercionPregunta = "
                INSERT INTO pregunta ( pre_pre, pun_pre, id_exa2 )
                VALUES ( '$pre_pre', '$pun_pre', '$id_exa_max' )
              ";

              $resultadoInsercionPregunta = mysqli_query( $db, $sqlInsercionPregunta );

              if ( $resultadoInsercionPregunta ) {
                // OBTENCION DE RESPUESTAS EN TABLA respuesta
                $sqlMaximoRespuesta = "
                  SELECT MAX( id_pre ) AS maximo FROM pregunta
                ";

                $resultadoMaximoRespuesta = mysqli_query( $db, $sqlMaximoRespuesta );

                $filaMaximoRespuesta = mysqli_fetch_assoc( $resultadoMaximoRespuesta );

                $id_pre_max = $filaMaximoRespuesta['maximo'];

                // DATOS DE respuesta
                $sqlRespuesta = "
                  SELECT *
                  FROM respuesta
                  WHERE id_pre1 = '$id_pre'
                ";

                $resultadoRespuesta = mysqli_query( $db, $sqlRespuesta );

                while( $filaRespuesta = mysqli_fetch_assoc( $resultadoRespuesta ) ) {
                  // CONSULTA
                  $res_res = $filaRespuesta['res_res'];
                  $val_res = $filaRespuesta['val_res'];

                  // INSERCION

                  $sqlInsercionRespuesta = "
                    INSERT INTO respuesta ( res_res, val_res, id_pre1 )
                    VALUES ( '$res_res', '$val_res', '$id_pre_max' )
                  ";

                  $resultadoInsercionRespuesta = mysqli_query( $db, $sqlInsercionRespuesta );

                }

              }

            }

          }

        }
        
        }

      }



    // FIN WHILE MATERIAS P1
    }

  }

  $emisor = 87;
  $receptor = 102;
  duplicarContenidoProgramaAlumnos( $emisor, $receptor )
?>
  

<?php 
  include 'inc/footer.php';
?>