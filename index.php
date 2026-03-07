<?php  
	include('inc/header.php');

    $sqlSesion = "
        UPDATE ejecutivo
        SET
        ult_eje = CURDATE()
        WHERE id_eje = $id
    ";
    $resultadoSesion = mysqli_query( $db, $sqlSesion );
    if( !$resultadoSesion ){
        echo $sqlSesion;
    }


    if( $eli_eje == 'Inactivo' ){
        $sql = "
            UPDATE ejecutivo
            SET
            eli_eje = 'Activo'
            WHERE id_eje = '$id'
        ";

        $resultado = mysqli_query( $db, $sql );
        if( !$resultado ){
            echo $sql;
        }
    }

    if( $permisos != 'Autorizado' ){
        if( $tipoUsuario != 'Ejecutivo' ){
            header('location: alumnos.php');
        } else {
            header('location: citas_admisiones.php');
        }
    }

?>	
	<!-- start page title -->
    <div class="row">
        <div class="col-12">
            
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inicio</a></li>
                        <li class="breadcrumb-item active">Permisos</li>
                    </ol>
                </div>
                <h4 class="page-title">Inicio</h4>
            </div>

        </div>
    </div>     
    <!-- end page title --> 


    <div class="row">
        <div class="col-md-6">
            <span class="letraPequena">
                Selecciona/deselecciona los CDEs para consultar información (SCOPE):
            </span>

            <hr>
        <?php
            $sqlPlanteles = "
                SELECT *
                FROM plantel
                WHERE id_cad1 = 1
            ";

            $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

            $printedIds = array(); // Array para llevar un registro de los id_pla ya impresos

            while ($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
                $id_pla = $filaPlanteles['id_pla'];
                
                $sqlPlanEje = "
                    SELECT *
                    FROM planteles_ejecutivo
                    WHERE id_eje = '$id'
                ";

                $resultadoPlanEje = mysqli_query($db, $sqlPlanEje);
                $checked = ''; // Inicialmente, el checkbox no estará marcado

                while ($filaPlaEje = mysqli_fetch_assoc($resultadoPlanEje)) {
                    $id_pla2 = $filaPlaEje['id_pla'];

                    if ($id_pla == $id_pla2) {
                        $checked = 'checked'; // Si encuentra una coincidencia, marca el checkbox
                        break; // Sal del bucle ya que encontraste una coincidencia
                    }
                }

                echo '
                <div class="form-check mb-2 form-check-primary">
                    <input class="form-check-input" type="checkbox" value="' . $filaPlanteles['id_pla'] . '" id="customckeck' . $filaPlanteles['id_pla'] . '" ' . $checked . '>
                    <label class="form-check-label" for="customckeck' . $filaPlanteles['id_pla'] . '"> <i class="fas fa-university"></i> ' . $filaPlanteles['nom_pla'] . '</label>
                </div>';
            }
            ?>
            
        </div>
    </div>

<?php  
	include('inc/footer.php');
?>

<script type="text/javascript">
    $('.form-check-input').on('click', function () {
        var id_pla = $(this).val();
        if (this.checked) {
            // alert('Se ha marcado el checkbox con id_pla: ' + id_pla);
            var estatus = 1;
        } else {
            // alert('Se ha desmarcado el checkbox con id_pla: ' + id_pla);
            var estatus = 0;
        }

        $.ajax({
            url: 'server/controlador_superusuario.php',
            type: 'POST',
            dataType: 'json',
            data: { estatus, id_pla },
            success: function (respuesta) {
                console.log( respuesta );

                toastr.info('Cambios guardados');
            }
        });
    });
</script>