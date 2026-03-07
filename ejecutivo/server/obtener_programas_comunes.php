<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$planteles = json_decode($_POST['planteles'], true);

if (empty($planteles)) {
    echo '<div class="border rounded p-2" style="max-height: 300px; overflow-y: auto; background: #f8f9fa;">
            <small class="text-muted" style="font-size: 10px;">Selecciona planteles primero</small>
          </div>';
    exit;
}

// Obtener programas agrupados por plantel
$plantelesInfo = [];
foreach ($planteles as $id_pla) {
    $sqlPlantel = "SELECT nom_pla FROM plantel WHERE id_pla = ?";
    $stmtPlantel = mysqli_prepare($db, $sqlPlantel);
    mysqli_stmt_bind_param($stmtPlantel, 'i', $id_pla);
    mysqli_stmt_execute($stmtPlantel);
    $resultPlantel = mysqli_stmt_get_result($stmtPlantel);
    $nombrePlantel = mysqli_fetch_assoc($resultPlantel)['nom_pla'];
    
    $sqlProgramas = "SELECT id_ram, nom_ram FROM rama WHERE id_pla1 = ? ORDER BY nom_ram ASC";
    $stmtProgramas = mysqli_prepare($db, $sqlProgramas);
    mysqli_stmt_bind_param($stmtProgramas, 'i', $id_pla);
    mysqli_stmt_execute($stmtProgramas);
    $resultProgramas = mysqli_stmt_get_result($stmtProgramas);
    
    $programas = [];
    while ($fila = mysqli_fetch_assoc($resultProgramas)) {
        $programas[] = $fila;
    }
    
    $plantelesInfo[] = [
        'id_pla' => $id_pla,
        'nombre' => $nombrePlantel,
        'programas' => $programas
    ];
}
?>

<style>
.plantel-header {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 8px 12px;
    margin-bottom: 6px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
    font-size: 11px;
    font-weight: 600;
}

.plantel-header:hover {
    background: #e9ecef;
    border-color: #0d6efd;
}

.plantel-header.active {
    background: #d1e7ff;
    border-color: #0d6efd;
}

.plantel-content {
    display: none;
    padding: 8px 12px 12px 12px;
    background: white;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 4px 4px;
    margin-top: -6px;
    margin-bottom: 6px;
}

.plantel-content.show {
    display: block;
}

.plantel-icon {
    transition: transform 0.2s ease;
    display: inline-block;
}

.plantel-header.active .plantel-icon {
    transform: rotate(90deg);
}
</style>

<div style="max-height: 300px; overflow-y: auto;">
    <?php if (empty($plantelesInfo)) { ?>
        <div class="text-center text-muted py-3" style="font-size: 11px;">
            No hay programas disponibles
        </div>
    <?php } else { ?>
        <?php foreach ($plantelesInfo as $index => $plantel) { ?>
            <div class="plantel-group">
                <!-- HEADER COLAPSABLE -->
                <div class="plantel-header" onclick="togglePlantel(this)">
                    <div>
                        <span class="plantel-icon">▶</span>
                        <strong style="margin-left: 8px;">
                            <?php echo strtoupper($plantel['nombre']); ?>
                        </strong>
                    </div>
                    <span class="badge bg-info" style="font-size: 9px;">
                        <?php echo count($plantel['programas']); ?> programas
                    </span>
                </div>
                
                <!-- CONTENIDO COLAPSABLE -->
                <div class="plantel-content <?php echo ($index === 0) ? 'show' : ''; ?>">
                    <?php if (empty($plantel['programas'])) { ?>
                        <small class="text-muted" style="font-size: 10px;">Sin programas disponibles</small>
                    <?php } else { ?>
                        <?php foreach ($plantel['programas'] as $prog) { ?>
                            <div class="form-check mb-1" style="font-size: 11px;">
                                <input class="form-check-input checkbox-programa" 
                                       type="checkbox" 
                                       value="<?php echo $prog['id_ram']; ?>" 
                                       data-plantel="<?php echo $plantel['id_pla']; ?>"
                                       data-plantel-nombre="<?php echo htmlspecialchars($plantel['nombre']); ?>"
                                       id="programa_<?php echo $prog['id_ram']; ?>_<?php echo $plantel['id_pla']; ?>">
                                <label class="form-check-label" 
                                       for="programa_<?php echo $prog['id_ram']; ?>_<?php echo $plantel['id_pla']; ?>">
                                    <?php echo $prog['nom_ram']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<script>
function togglePlantel(header) {
    const content = $(header).next('.plantel-content');
    const isActive = $(header).hasClass('active');
    
    // Cerrar todos los demás
    $('.plantel-header').removeClass('active');
    $('.plantel-content').removeClass('show').slideUp(200);
    
    // Toggle el actual
    if (!isActive) {
        $(header).addClass('active');
        content.addClass('show').slideDown(200);
    }
}

// Abrir el primer plantel por defecto
$(document).ready(function() {
    $('.plantel-header').first().addClass('active');
});
</script>