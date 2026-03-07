<?php  

    include 'inc/header.php';

    $plantel_emisor = 8;

    $plantel_receptor = 9;


   	$sqlProgramas = "
        SELECT * 
        FROM rama 
        WHERE id_pla1 = '$plantel_emisor'
      ";

      // echo $sqlProgramas;

      $resultadoProgramas = mysqli_query( $db, $sqlProgramas );

      $id_pla1 = $plantel_receptor;

      while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){

        $id_ram = $filaProgramas['id_ram'];
        $nom_ram = $filaProgramas['nom_ram'];
        $cic_ram = $filaProgramas['cic_ram'];
        $per_ram = $filaProgramas['per_ram'];
        $cos_ram = $filaProgramas['cos_ram'];
        $eva_ram = $filaProgramas['eva_ram'];
        $mod_ram = $filaProgramas['mod_ram'];
        $car_reg_ram = $filaProgramas['car_reg_ram'];
        $gra_ram = $filaProgramas['gra_ram'];
        $com_ram = $filaProgramas['com_ram'];
        $pag_ram = $filaProgramas['pag_ram'];

        $sqlInsertarPrograma = "
          INSERT INTO rama ( nom_ram, cic_ram, per_ram, cos_ram, eva_ram, mod_ram, car_reg_ram, gra_ram, id_pla1, com_ram, pag_ram ) VALUES ( '$nom_ram', '$cic_ram', '$per_ram', '$cos_ram', '$eva_ram', '$mod_ram', '$car_reg_ram', '$gra_ram', '$id_pla1', '$com_ram', '$pag_ram' )
        ";

        $resultadoInsertarPrograma = mysqli_query( $db, $sqlInsertarPrograma );

        if ( !$resultadoInsertarPrograma ) {
          echo $sqlInsertarPrograma."ERROR <br>";
        } else {

          $emisor = $id_ram;
          $receptor = obtenerUltimoIdentificador('rama', 'id_ram');
          duplicarContenidoPrograma( $emisor, $receptor );

          echo "PROGRAMA: ".$nom_ram.". Copiado correctamente. <br>";

        }


      }

  	include 'inc/footer.php';
?>