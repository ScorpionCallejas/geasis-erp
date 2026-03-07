<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
?>


<span class="letraPequena">
    Puedes cambiar los datos que requieras de cualquier esquema de comisión
</span>

<!-- ESQUEMAS COMISION -->
<div class="row">
    
    <div class="col-md-6">
        <!-- ESQUEMA CONSULTOR -->
        <div class="card shadow">
            <div class="card-body" style="border-radius: 20px;">
                <h5 class="card-title">ESQUEMA CONSULTOR</h5>
                

                <table class=" ">
                    <thead class="letraPequena">
                        <tr>
                            <th>Mínima Cant Registros</th>
                            <th>Máxima Cant Registros</th>
                            <th>Pago 1</th>
                            <th>Comisión Pago 1</th>
                            <th>Pago 2</th>
                            <th>Comisión Pago 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $sqlSales = "SELECT * FROM esquema_consultor";
                            $resultadoSales = mysqli_query( $db, $sqlSales );
                            $i = 1;
                            while ($filaSales = mysqli_fetch_assoc($resultadoSales)) {
                        ?>
                        
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;"></span>
                                        <input class="form-control esquema_consultor" campo="min_esq_con" type="number" value="<?php echo $filaSales['min_esq_con']; ?>" id_esq_con="<?php echo $filaSales['id_esq_con']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;"></span>
                                        <input class="form-control esquema_consultor" campo="max_esq_con" type="number" value="<?php echo $filaSales['max_esq_con']; ?>" id_esq_con="<?php echo $filaSales['id_esq_con']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_consultor" campo="pag1_esq_con" type="number" value="<?php echo $filaSales['pag1_esq_con']; ?>" id_esq_con="<?php echo $filaSales['id_esq_con']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_consultor" campo="com1_esq_con" type="number" value="<?php echo $filaSales['com1_esq_con']; ?>" id_esq_con="<?php echo $filaSales['id_esq_con']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_consultor" campo="pag2_esq_con" type="number" value="<?php echo $filaSales['pag2_esq_con']; ?>" id_esq_con="<?php echo $filaSales['id_esq_con']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_consultor" campo="com2_esq_con" type="number" value="<?php echo $filaSales['com2_esq_con']; ?>" id_esq_con="<?php echo $filaSales['id_esq_con']; ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- F ESQUEMA CONSULTOR -->

        <!--  -->
        <!-- ESQUEMA SALES MANAGER -->
        <div class="card shadow">
            <div class="card-body" style="border-radius: 20px;">
                <h5 class="card-title">ESQUEMA SALES MANAGER</h5>
                

                <table class=" ">
                    <thead class="letraPequena">
                        <tr>
                            
                            <th>Comisión semanal</th>
                            <th>Pago por registro del equipo</th>
                            <th>Pago por inicio</th>
                            <th>Beneficio 45</th>
                            <th>Beneficio 60</th>
                            <th>Beneficio 75</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sqlSales = "SELECT * FROM esquema_sales";
                            $resultadoSales = mysqli_query($db, $sqlSales);
                            while ($filaSales = mysqli_fetch_assoc($resultadoSales)) {
                        ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_sales" campo="com_esq_sal" type="number" value="<?php echo $filaSales['com_esq_sal']; ?>" id_esq_sal="<?php echo $filaSales['id_esq_sal']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_sales" campo="equ_esq_sal" type="number" value="<?php echo $filaSales['equ_esq_sal']; ?>" id_esq_sal="<?php echo $filaSales['id_esq_sal']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_sales" campo="ini_esq_sal" type="number" value="<?php echo $filaSales['ini_esq_sal']; ?>" id_esq_sal="<?php echo $filaSales['id_esq_sal']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_sales" campo="b45_esq_sal" type="number" value="<?php echo $filaSales['b45_esq_sal']; ?>" id_esq_sal="<?php echo $filaSales['id_esq_sal']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_sales" campo="b60_esq_sal" type="number" value="<?php echo $filaSales['b60_esq_sal']; ?>" id_esq_sal="<?php echo $filaSales['id_esq_sal']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_sales" campo="b75_esq_sal" type="number" value="<?php echo $filaSales['b75_esq_sal']; ?>" id_esq_sal="<?php echo $filaSales['id_esq_sal']; ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- F ESQUEMA SALES MANAGER -->
        <!--  -->

        <!-- ESQUEMA EJECUTIVO/RVT -->
        <div class="card shadow">
            <div class="card-body" style="border-radius: 20px;">
                <h5 class="card-title">ESQUEMA EJECUTIVO/RVT</h5>
                

                <table class=" ">
                    <thead class="letraPequena">
                        <tr>
                            <th>Citas efec 1</th>
                            <th>Comisión citas efec 1</th>
                            <th>Citas efec 2</th>
                            <th>Comisión citas efec 2</th>
                            <th>Cita registrada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $sqlRvt = "SELECT * FROM esquema_rvt";
                            $resultadoRvt = mysqli_query( $db, $sqlRvt );
                            $i = 1;
                            while ($filaRvt = mysqli_fetch_assoc($resultadoRvt)) {
                        ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;"></span>
                                        <input class="form-control esquema_rvt" campo="cit1_esq_rvt" type="number" value="<?php echo $filaRvt['cit1_esq_rvt']; ?>" id_esq_rvt="<?php echo $filaRvt['id_esq_rvt']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_rvt" campo="com1_esq_rvt" type="number" value="<?php echo $filaRvt['com1_esq_rvt']; ?>" id_esq_rvt="<?php echo $filaRvt['id_esq_rvt']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;"></span>
                                        <input class="form-control esquema_rvt" campo="cit2_esq_rvt" type="number" value="<?php echo $filaRvt['cit2_esq_rvt']; ?>" id_esq_rvt="<?php echo $filaRvt['id_esq_rvt']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_rvt" campo="com2_esq_rvt" type="number" value="<?php echo $filaRvt['com2_esq_rvt']; ?>" id_esq_rvt="<?php echo $filaRvt['id_esq_rvt']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 5px;">$</span>
                                        <input class="form-control esquema_rvt" campo="reg_esq_rvt" type="number" value="<?php echo $filaRvt['reg_esq_rvt']; ?>" id_esq_rvt="<?php echo $filaRvt['id_esq_rvt']; ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- F ESQUEMA EJECUTIVO/RVT -->


        
    </div>
    
    <div class="col-md-6">
        <!-- PERIODO COMISION -->
        <div class="card shadow">
            <div class="card-body" style="border-radius: 20px;">
                <h5 class="card-title">CÁLCULO COMISIONES</h5>

                <!-- TABLA -->
                <table class="table-bordered table" style="padding: 0;">
                    <thead>
                        <tr style="padding: 0;">
                            <th style="padding: 0;">#</th>
                            <th style="padding: 0;">CONCEPTO</th>
                            <th style="padding: 0;">FECHAS</th>
                            <th style="padding: 0;">ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody style="padding: 0;">
                        <?php 
                            $sqlPeriodos = "
                                SELECT * FROM periodo WHERE id_pla = '$plantel' ORDER BY id_per DESC
                            ";

                            // echo $sqlPeriodos;
                            $contador = 1;
                            $resultadoPeriodos = mysqli_query($db, $sqlPeriodos);
                            while($filaPeriodos = mysqli_fetch_assoc($resultadoPeriodos)) {
                        ?>
                            <tr style="padding: 0;">
                                <td style="padding: 0;"><?php echo $contador; ?></td>
                                <td style="padding: 0;"><?php echo $filaPeriodos['con_per']; ?></td>
                                <td style="padding: 0;"><?php echo fechaFormateadaCompacta2($filaPeriodos['ini_per']).' '.$filaPeriodos['fin_per']; ?></td>
                                <td style="padding: 0;">####</td>
                            </tr>
                        <?php
                                $contador++;
                            }
                        ?>
                    </tbody>
                </table>

                <!-- F TABLA -->

            </div>
        </div>
        <!-- F PERIODO COMISION -->
    </div>
    
</div>
<!-- F ESQUEMAS COMISION -->

<script>
    function handleEsquemaChange(esquema, idAttr) {
        return function(e) {
            e.preventDefault();

            var identificador = $(this).attr(idAttr);
            var campo = $(this).attr('campo');
            var valor = $(this).val();

            $.ajax({
                url: 'server/controlador_esquema.php',
                type: 'POST',
                data: { identificador, campo, valor, esquema },
                success: function(respuesta) {
                    console.log(respuesta);
                    toastr.success('Guardado correctamente');
                }
            });
        };
    }

    $('.esquema_consultor').on('change keyup', handleEsquemaChange('esquema_consultor', 'id_esq_con'));
    $('.esquema_sales').on('change keyup', handleEsquemaChange('esquema_sales', 'id_esq_sal'));
    $('.esquema_rvt').on('change keyup', handleEsquemaChange('esquema_rvt', 'id_esq_rvt'));
</script>
