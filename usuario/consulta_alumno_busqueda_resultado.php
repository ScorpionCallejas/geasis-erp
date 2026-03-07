<?php  

	include('inc/header.php');

?>

<hr>

<!-- BUSCADOR -->
<div class="row">
    <div class="col-md-3">
        <form method="GET" action="consulta_alumno_busqueda_resultado.php">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar alumno" aria-label="Buscar alumno" name="palabra" value="<?php echo ($_GET['palabra']); ?>">
                <button class="btn input-group-text btn-primary waves-effect waves-light" type="submit">Buscar</button>
            </div>
        </form>
    </div>
</div>
<!-- FIN BUSCADOR -->

<?php
	include('inc/footer.php');
?>

<!-- RESULTADO BUSQUEDA -->
<?php 

    if ( isset( $_GET['palabra'] ) ){

        $palabra = $_GET['palabra'];

        if (!empty($palabra)){
            $sql = "
                SELECT * 
                FROM vista_alumnos
                WHERE ( id_pla8 = '$plantel' ) AND ( ( id_alu_ram = '$palabra' ) OR ( tel_alu = '$palabra' ) OR ( UPPER( cor_alu ) = UPPER('$palabra') ) )
            ";

            //echo $sql;
            $result = mysqli_query($db, $sql);

            $total = mysqli_num_rows($result);

            if ( $fila = mysqli_fetch_assoc($result) ) {
                $id_alu_ram = $fila['id_alu_ram'];
                do {
?>
                    <div class="card p-2" style="border-radius: 20px;">
                        <div id="contenedor_visualizacion8">
                        </div>
                    </div>

                    <script>
                        obtener_consulta_alumno();
                        function obtener_consulta_alumno(){
                            var id_alu_ram = <?php echo $id_alu_ram; ?>;
                            $.ajax({
                                url: 'server/obtener_consulta_general_alumno.php',
                                type: 'GET',
                                data: { id_alu_ram },
                                success: function( respuesta ){
                                    // console.log( respuesta );
                                    $('#contenedor_visualizacion8').html( respuesta );
                                }
                            });
                        }
                    </script>
<?php
                } while ($fila = mysqli_fetch_assoc($result));
            } else {
                // USAR DE NO HALLAR COINCIDENCIAS   
            }
        }
    }
?>
<!-- FIN RESULTADO BUSQUEDA -->