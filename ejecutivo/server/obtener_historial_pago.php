<?php
// ARCHIVO VIA AJAX PARA OBTENER TODO EL HISTORIAL ASOCIADO A UN PAGO
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_pag = $_POST['id_pag'];

$sqlPago = "
    SELECT *
    FROM pago
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
    INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
    WHERE id_pag = '$id_pag'
";
$resultadoPago = mysqli_query($db, $sqlPago);
$filaPago = mysqli_fetch_assoc($resultadoPago);

// DATOS PAGO
$con_pag = $filaPago['con_pag'];
$mon_pag = $filaPago['mon_pag'];
$mon_ori_pag = $filaPago['mon_ori_pag'];
$est_pag = $filaPago['est_pag'];

// DATOS ALUMNO
$nombreAlumno = $filaPago['app_alu'] . " " . $filaPago['apm_alu'] . " " . $filaPago['nom_alu'];
$fot_alu = $filaPago['fot_alu'];
$nom_gen = $filaPago['nom_gen'];
$nom_ram = $filaPago['nom_ram'];
$fin_pag = $filaPago['fin_pag'];
$fac_pag = $filaPago['fac_pag'];
$tip_pag = $filaPago['tip_pag'];
$id_alu_ram = $filaPago['id_alu_ram10'];
$id_gen_pag = $filaPago['id_gen_pag2'];
?>

<!-- Minimalista y limpio -->
<div class="card border-0 shadow-sm mb-4" style="max-width: 400px; margin: auto;">
    <div class="card-body text-center">
        <!-- Foto del alumno -->
        <div>
            <img src="<?php echo obtenerValidacionFotoUsuario($fot_alu); ?>" 
                 class="rounded-circle mb-3" 
                 style="width: 70px; height: 70px; border: 2px solid #007bff;">
        </div>
        <!-- Nombre del alumno -->
        <h5 class="text-dark font-weight-bold mb-1"><?php echo $nombreAlumno; ?></h5>
        <p class="text-secondary small mb-2"><?php echo $nom_ram; ?> | <?php echo $nom_gen; ?></p>

        <!-- Detalles del pago -->
        <div class="text-left small">
            <div class="d-flex justify-content-between">
                <span class="text-secondary">Vencimiento:</span>
                <span class="text-dark font-weight-bold"><?php echo $con_pag; ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-secondary">Fecha:</span>
                <span class="text-dark font-weight-bold"><?php echo fechaFormateadaCompacta2($fin_pag); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-secondary">Monto:</span>
                <span class="text-success font-weight-bold">$<?php echo number_format($mon_ori_pag, 2); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Accordion wrapper -->
<div class="accordion custom-accordion" id="custom-accordion-one">
    <!-- RECARGO -->
    <div class="card mb-0">
        <div class="card-header" id="headingRecargo">
            <h5 class="m-0 position-relative">
                <a class="custom-accordion-title text-reset d-block collapsed" data-bs-toggle="collapse" href="#collapseRecargo" aria-expanded="false" aria-controls="collapseRecargo">
                    Recargos <span class="float-right">$<?php echo obtenerTotalRecargoPagoServer($id_pag) ? number_format(obtenerTotalRecargoPagoServer($id_pag), 2) : "0.00"; ?></span> <i class="mdi mdi-chevron-down accordion-arrow"></i>
                </a>
            </h5>
        </div>
        <div id="collapseRecargo" class="collapse" aria-labelledby="headingRecargo" data-bs-parent="#custom-accordion-one">
            <div class="card-body">
                <table id="myTableRecargo" class="table table-hover table-borderless table-sm" cellspacing="0" width="100%">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-left small text-muted font-weight-bold">#</th>
                            <th class="text-center small text-muted font-weight-bold">Concepto</th>
                            <th class="text-center small text-muted font-weight-bold">Fecha</th>
                            <th class="text-right small text-muted font-weight-bold">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlRecargo = "
                            SELECT *
                            FROM recargo_pago
                            WHERE id_pag5 = '$id_pag'
                            ORDER BY id_rec_pag DESC
                        ";
                        $resultadoRecargo = mysqli_query($db, $sqlRecargo);

                        if ($resultadoRecargo) {
                            $i = 1;
                            while ($filaRecargo = mysqli_fetch_assoc($resultadoRecargo)) {
                        ?>
                                <tr>
                                    <td class="text-left small font-weight-normal"><?php echo $i; $i++; ?></td>
                                    <td class="text-center small font-weight-normal">Recargo</td>
                                    <td class="text-center small font-weight-normal">
                                        <?php echo fechaFormateadaCompacta2($filaRecargo['fec_rec_pag']); ?>
                                    </td>
                                    <td class="text-right small font-weight-normal">
                                        $<?php echo number_format($filaRecargo['mon_rec_pag'], 2); ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center small text-muted'>No hay recargos registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ABONADO -->
    <div class="card mb-0">
        <div class="card-header" id="headingAbonado">
            <h5 class="m-0 position-relative">
                <a class="custom-accordion-title text-reset d-block collapsed" data-bs-toggle="collapse" href="#collapseAbonado" aria-expanded="false" aria-controls="collapseAbonado">
                    Abonado <span class="float-right">$<?php echo obtenerTotalAbonadoPagoServer($id_pag) ? number_format(obtenerTotalAbonadoPagoServer($id_pag), 2) : "0.00"; ?></span> <i class="mdi mdi-chevron-down accordion-arrow"></i>
                </a>
            </h5>
        </div>
        <div id="collapseAbonado" class="collapse" aria-labelledby="headingAbonado" data-bs-parent="#custom-accordion-one">
            <div class="card-body">
                <table id="myTableAbonado" class="table table-hover table-borderless table-sm" cellspacing="0" width="100%">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-left small text-muted font-weight-bold">#</th>
                            <th class="text-center small text-muted font-weight-bold">Tipo</th>
                            <th class="text-center small text-muted font-weight-bold">Fecha</th>
                            <th class="text-right small text-muted font-weight-bold">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlAbonado = "
                            SELECT *
                            FROM abono_pago
                            WHERE id_pag1 = '$id_pag'
                            ORDER BY id_abo_pag DESC
                        ";
                        $resultadoAbonado = mysqli_query($db, $sqlAbonado);

                        if ($resultadoAbonado) {
                            $i = 1;
                            while ($filaAbonado = mysqli_fetch_assoc($resultadoAbonado)) {
                        ?>
                                <tr>
                                    <td class="text-left small font-weight-normal"><?php echo $i; $i++; ?></td>
                                    <td class="text-center small font-weight-normal"><?php echo $filaAbonado['tip_abo_pag']; ?></td>
                                    <td class="text-center small font-weight-normal">
                                        <?php echo fechaFormateadaCompacta2($filaAbonado['fec_abo_pag']); ?>
                                    </td>
                                    <td class="text-right small font-weight-normal">
                                        $<?php echo number_format($filaAbonado['mon_abo_pag'], 2); ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center small text-muted'>No hay abonos registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CONDONADO -->
    <div class="card mb-0">
        <div class="card-header" id="headingCondonado">
            <h5 class="m-0 position-relative">
                <a class="custom-accordion-title text-reset d-block collapsed" data-bs-toggle="collapse" href="#collapseCondonado" aria-expanded="false" aria-controls="collapseCondonado">
                    Descontado <span class="float-right">$<?php echo obtenerMontoCondonadoPagoServer($id_pag) ? number_format(obtenerMontoCondonadoPagoServer($id_pag), 2) : "0.00"; ?></span> <i class="mdi mdi-chevron-down accordion-arrow"></i>
                </a>
            </h5>
        </div>
        <div id="collapseCondonado" class="collapse" aria-labelledby="headingCondonado" data-bs-parent="#custom-accordion-one">
            <div class="card-body">
                <table id="myTableCondonado" class="table table-hover table-borderless table-sm" cellspacing="0" width="100%">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-left small text-muted font-weight-bold">#</th>
                            <th class="text-center small text-muted font-weight-bold">Motivo</th>
                            <th class="text-center small text-muted font-weight-bold">Fecha</th>
                            <th class="text-right small text-muted font-weight-bold">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlCondonado = "
                            SELECT *
                            FROM condonacion_pago
                            WHERE id_pag2 = '$id_pag'
                            ORDER BY id_con_pag DESC
                        ";
                        $resultadoCondonado = mysqli_query($db, $sqlCondonado);

                        if ($resultadoCondonado) {
                            $i = 1;
                            while ($filaCondonado = mysqli_fetch_assoc($resultadoCondonado)) {
                        ?>
                                <tr>
                                    <td class="text-left small font-weight-normal"><?php echo $i; $i++; ?></td>
                                    <td class="text-center small font-weight-normal"><?php echo $filaCondonado['est_con_pag'] . ' por ' . $filaCondonado['res_con_pag']; ?></td>
                                    <td class="text-center small font-weight-normal">
                                        <?php echo fechaFormateadaCompacta2($filaCondonado['fec_con_pag']); ?>
                                    </td>
                                    <td class="text-right small font-weight-normal">
                                        $<?php echo number_format($filaCondonado['can_con_pag'], 2); ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center small text-muted'>No hay descuentos registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Accordion wrapper -->

<?php
if (($tip_pag == 'Inscripción') || ($plantel != 9 && $tip_pag == 'Colegiatura')) {
?>
    <a class="btn btn-danger white-text btn-block letraGrande waves-effect" title="Exportar" id="btn_pdf" target="_blank" href="ticket_pago.php?id_pag=<?php echo $id_pag; ?>">
        <i class="far fa-file-pdf"></i> Ticket PDF
    </a>
<?php
}
?>