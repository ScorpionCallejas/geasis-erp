<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO EMPLEADO
	//empleados.php
	require('../inc/cabeceras.php');
    require('../inc/funciones.php');


    if ( isset( $_POST['id_gen'] ) ) {
        
        $id_alu = $_POST['id_alu'];
        $id_gen = $_POST['id_gen'];
        $id_ram = $_POST['id_ram'];

        // ELIMINACIÓN PROGRAMA PREVIO
        $sqlDelete = "
            DELETE FROM alu_ram WHERE id_alu1 = $id_alu
        ";
        $resultadoDelete = mysqli_query( $db, $sqlDelete );

        if( !$resultadoDelete ){
            echo $resultadoDelete;
        }
        // F ELIMINACION PROGRAMA PREVIO

        $sqlRama = "
                SELECT *
                FROM rama
                WHERE id_ram = '$id_ram'
        ";

        $resultadoRama = mysqli_query($db, $sqlRama);

        $filaRama = mysqli_fetch_assoc($resultadoRama);

        $car_alu_ram = $filaRama['car_reg_ram'];

        $bec_alu_ram = 0;
        $bec2_alu_ram = 0;

        $act_alu_ram = date('Y-m-d');

        $sql = "INSERT INTO alu_ram (est2_alu_ram, id_alu1, id_gen1, id_ram3, act_alu_ram, bec_alu_ram, bec2_alu_ram, car_alu_ram ) VALUES ('Inactivo', '$id_alu', '$id_gen', '$id_ram', '$act_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$car_alu_ram' )";

        $resultado = mysqli_query($db, $sql);

        if ($resultado) {
            // ADICION DE CALIFICACIONES Y PARCIALES

            $sqlAluRam = "SELECT MAX(id_alu_ram) AS ultimo FROM alu_ram";
            $resultadoAluRam = mysqli_query($db, $sqlAluRam);

            $filaAluRam = mysqli_fetch_assoc($resultadoAluRam);
            $maxAluRam = $filaAluRam['ultimo'];

            //TOTAL DE MATERIAS Y ADICION
            $sqlMateria = "
                SELECT * 
                FROM materia
                WHERE id_ram2 = '$id_ram'
            ";

            $resultadoMateria = mysqli_query($db, $sqlMateria);

            $temp = array();
            $i = 0;

            while ($filaMateriasAux = mysqli_fetch_array($resultadoMateria)) {
                 $temp[$i] = $filaMateriasAux["id_mat"];
                 $i++;

            }

            // EXTRACCION DE PARCIALES eva_ram DE rama
            
            $eva_ram = $filaRama['eva_ram'];
            // SE NECESITA PARA FIJAR CONDICION DE LAS EVALUACIONES POR CICLO Y GENERAR LOS REGISTROS NULOS

            //ADICION DE REGISTROS NULOS PARA EXISTENCIA DE CARGAS EN MATERIAS POR EVALUAR
            for ($i = 0 ; $i < count($temp) ; $i++ ) { 
                $sqlInsercionCalificacion = "INSERT INTO calificacion (id_alu_ram2, id_mat4) VALUES($maxAluRam, $temp[$i])";
                //echo $sqlInsercionCalificacion;
                mysqli_query($db, $sqlInsercionCalificacion);

                for ($j = 0; $j < $eva_ram; $j++) { 
                    // ADICION DE REGISTROS NULOS PARA PARCIALES ACORDE A MATERIAS
                    $sqlInsercionParcial = "INSERT INTO parcial (id_alu_ram9, id_mat3) VALUES($maxAluRam, $temp[$i])";
                    mysqli_query($db, $sqlInsercionParcial);
                }
                
            }

            // ADICION  DE CARGA DE DOCUMENTACION DE PROGRAMA
            $sqlDocumentosRama = "
                SELECT *
                FROM documento_rama
                WHERE id_ram6 = '$id_ram'
            ";

            $resultadoDocumentosRama = mysqli_query( $db, $sqlDocumentosRama );

            while( $filaDocumentosRama = mysqli_fetch_assoc( $resultadoDocumentosRama )){
                
                $id_doc_ram1 = $filaDocumentosRama['id_doc_ram'];

                $sqlInsercionDocumento = "
                    INSERT INTO documento_alu_ram ( est_doc_alu_ram, id_doc_ram1, id_alu_ram11 )
                    VALUES ( 'Pendiente', $id_doc_ram1, $maxAluRam )
                ";

                $resultadoInsercionDocumento = mysqli_query($db, $sqlInsercionDocumento);

                if ( !$resultadoInsercionDocumento ) {
                    
                    echo $sqlInsercionDocumento;
                
                }
            }

            echo "Exito";

        }else{
            echo $sql;
        }
    } else {
        echo 'Exito';
    }
	
		
?>