<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_ram = $_POST['id_ram'];

$sql = "
    SELECT COUNT(*) as total 
    FROM rama_pago
    WHERE id_ram15 = $id_ram
";
$resultado_count = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($resultado_count);
$total = $row['total'];
?>

<!-- Botón para agregar un nuevo registro -->
<button type="button" id="btn-agregar" class="btn btn-sm btn-primary">Agregar Registro</button>
<br>
<span class="letraPequena">*FECHAS DE TRÁMITES Y EVENTOS</span>
<br>
<div id="contenedor-pagos" class="row">
    <?php
    if($total > 0) {
        $sql = "
            SELECT * 
            FROM rama_pago
            WHERE id_ram15 = $id_ram ORDER BY tip_ram_pag DESC
        ";
        $resultado = mysqli_query($db, $sql);
        $counter = 0;
        
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $counter++;
    ?>
        <div class="col-md-6 mb-3">
            <div class="position-relative pago-container" style="padding: 15px; border: 1px solid #e9ecef; border-radius: 5px;">
                <div class="input-group mb-2">
                    <div class="input-group-text bg-light text-muted">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <input type="input" 
                           value="<?php echo $fila['con_ram_pag']; ?>" 
                           name="con_gru_pag[]" 
                           class="form-control letraPequena bg-light text-muted"
                           readonly>
                    <!-- Ya no es required porque es readonly -->
                </div>

                <?php if($fila['tip_ram_pag'] === 'Pago') { ?>
                    <div class="input-group mb-2">
                        <div class="input-group-text bg-light text-muted">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <input type="number" 
                               class="form-control letraPequena bg-light text-muted" 
                               name="mon_gru_pag[]" 
                               readonly
                               value="<?php echo $fila['mon_ram_pag']; ?>">
                        <!-- Ya no es required porque es readonly -->
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="mon_gru_pag[]" value="">
                <?php } ?>

                <div class="input-group">
                    <div class="input-group-text">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <input type="date" 
                           name="ini_gru_pag[]"
                           class="form-control letraPequena" 
                           required>
                    <input 
                           value="<?php echo $fila['tip_ram_pag']; ?>"
                           name="tip_gru_pag[]"
                           type="hidden">
                    <!-- Hidden sin required -->
                </div>
            </div>
        </div>
        
        <?php
            if ($counter % 2 == 0) {
                echo '<div class="w-100"></div>'; // Para cerrar el row de a pares
            }
        }
    } // Si no hay registros, no se agrega nada al contenedor. 
    ?>
</div>

<!-- Template para nuevo registro -->
<style>
.pago-container {
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 5px;
}

.btn-eliminar {
    border: none;
    background: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    right: -5px;
    top: 5px;
    z-index: 10;
    position: absolute;
}

.btn-eliminar:hover {
    background: #f8f9fa;
}
</style>

<template id="template-nuevo-pago">
    <div class="col-md-6 mb-3">
        <div class="pago-container position-relative">
            <button type="button" class="btn-eliminar position-absolute" style="right: -5px; top: -5px; z-index: 10;">
                <i class="fas fa-times text-danger"></i>
            </button>

            <div class="input-group mb-2">
                <div class="input-group-text">
                    <i class="fas fa-file-alt"></i>
                </div>
                <input type="text" 
                       name="con_gru_pag[]" 
                       class="form-control letraPequena"
                       placeholder="Concepto"
                       required>
            </div>

            <div class="input-group mb-2">
                <div class="input-group-text">
                    <i class="fas fa-tag"></i>
                </div>
                <select name="tip_gru_pag[]" class="form-control letraPequena tipo-select" required>
                    <option value="Pago">Pago</option>
                    <option value="Fecha">Fecha</option>
                </select>
            </div>

            <div class="input-group mb-2 monto-container" style="display:none;">
                <div class="input-group-text">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <input type="number" 
                       class="form-control letraPequena"
                       name="mon_gru_pag[]"
                       placeholder="Monto">
            </div>

            <div class="input-group">
                <div class="input-group-text">
                    <i class="fas fa-calendar"></i>
                </div>
                <input type="date"
                       name="ini_gru_pag[]"
                       class="form-control letraPequena"
                       required>
            </div>
        </div>
    </div>
</template>

<script>
// Función para agregar un nuevo registro desde la plantilla
document.getElementById('btn-agregar').addEventListener('click', function() {
    var template = document.getElementById('template-nuevo-pago');
    var clone = template.content.cloneNode(true);
    var contenedor = document.getElementById('contenedor-pagos');
    contenedor.appendChild(clone);
    actualizarEventos();
});

function actualizarEventos() {
    // Evento para el botón de eliminar
    var btnsEliminar = document.querySelectorAll('#contenedor-pagos .btn-eliminar');
    btnsEliminar.forEach(function(btn) {
        btn.removeEventListener('click', eliminarRegistro);
        btn.addEventListener('click', eliminarRegistro);
    });
    
    // Evento para el cambio de tipo que muestre/oculte el monto
    var selectsTipo = document.querySelectorAll('#contenedor-pagos .tipo-select');
    selectsTipo.forEach(function(select) {
        select.removeEventListener('change', toggleMonto);
        select.addEventListener('change', toggleMonto);
    });
}

function eliminarRegistro(e) {
    var col = e.target.closest('.col-md-6');
    col.remove();
}

function toggleMonto(e) {
    var select = e.target;
    var container = select.closest('.pago-container');
    var montoInput = container.querySelector('.monto-container input[type="number"]');
    var montoGroup = montoInput.parentElement;

    if (select.value === 'Pago') {
        montoInput.required = true;
        montoGroup.style.display = 'flex';
    } else {
        montoInput.required = false;
        montoInput.value = '';
        montoGroup.style.display = 'none';
    }
}

// Actualizar eventos iniciales (en caso de que haya registros)
actualizarEventos();
</script>