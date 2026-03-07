<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_pla = isset($_POST['id_pla']) ? $_POST['id_pla'] : null;

// Verificar si el usuario tiene acceso a todos los planteles de la cadena
$sqlTotalPlanteles = "SELECT COUNT(*) as total_planteles FROM plantel WHERE id_cad1 = 1";
$resultadoTotal = mysqli_query($db, $sqlTotalPlanteles);
$totalPlanteles = mysqli_fetch_assoc($resultadoTotal)['total_planteles'];

$sqlPlantelesEjecutivo = "SELECT COUNT(*) as planteles_asignados FROM planteles_ejecutivo WHERE id_eje = '$id'";
$resultadoAsignados = mysqli_query($db, $sqlPlantelesEjecutivo);
$plantelesAsignados = mysqli_fetch_assoc($resultadoAsignados)['planteles_asignados'];

$tieneAccesoNacional = ($totalPlanteles == $plantelesAsignados);

// Estilos CSS específicos para la vista
?>
<style>
    /* Estilos generales */
    .file-explorer {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Contenedor de carpetas */
    .folders-grid {
        display: flex;
        flex-wrap: wrap;
        margin: -10px; /* Compensar el padding de las columnas */
    }
    
    .folder-column {
        width: 33.333%;
        padding: 10px;
        box-sizing: border-box;
    }
    
    @media (max-width: 992px) {
        .folder-column {
            width: 50%;
        }
    }
    
    @media (max-width: 576px) {
        .folder-column {
            width: 100%;
        }
    }
    
    .folder-container {
        padding: 15px;
        margin-bottom: 5px;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        height: 100%;
    }
    
    .folder-container:hover {
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    
    /* Encabezado de carpeta */
    .folder-header {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .folder-header:hover {
        background-color: rgba(0,0,0,0.05);
    }
    
    .folder-header i {
        font-size: 16px;
        margin-right: 10px;
    }
    
    .folder-header .folder-name {
        font-weight: 500;
        font-size: 16px;
        flex: 1;
    }
    
    .folder-header .document-count {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 11px;
        color: #6c757d;
        margin-left: 8px;
    }
    
    /* Contenido de carpeta */
    .folder-content {
        padding: 10px;
        margin-top: 8px;
        display: none; /* Carpetas cerradas por defecto */
    }
    
    /* Archivos */
    .file-item {
        display: flex;
        align-items: center;
        padding: 6px 8px;
        margin: 2px 0;
        border-radius: 4px;
        transition: all 0.15s ease-in-out;
        position: relative;
        cursor: pointer;
        background-color: transparent;
    }
    
    .file-item:hover {
        background-color: rgba(0,123,255,0.05);
    }
    
    .file-item .file-icon {
        font-size: 16px;
        margin-right: 8px;
        min-width: 20px;
        text-align: center;
    }
    
    .file-item .file-name {
        font-size: 13px;
        font-weight: normal;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }
    
    .file-item .file-actions {
        opacity: 0;
        transition: opacity 0.2s;
        margin-left: 8px;
    }
    
    .file-item:hover .file-actions {
        opacity: 1;
    }
    
    .file-item .file-date {
        font-size: 11px;
        color: #6c757d;
        margin-left: 10px;
        white-space: nowrap;
    }
    
    /* Colores para tipos de archivos */
    .file-word { color: #4285F4; }
    .file-excel { color: #0F9D58; }
    .file-powerpoint { color: #F4B400; }
    .file-pdf { color: #DB4437; }
    .file-generic { color: #607D8B; }
    
    /* Contenedores principales */
    .corporate-container {
        background-color: rgba(255, 193, 7, 0.08);
        border-left: 4px solid #ffc107;
    }
    
    .cde-container {
        background-color: rgba(13, 110, 253, 0.08);
        border-left: 4px solid #0d6efd;
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 36px;
        margin-bottom: 10px;
        opacity: 0.5;
    }
    
    /* Tooltips personalizados */
    .custom-tooltip {
        position: relative;
    }
    
    .tooltip-content {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(33, 37, 41, 0.9);
        color: white;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        width: 200px;
        z-index: 100;
        pointer-events: none;
    }
    
    .custom-tooltip:hover .tooltip-content {
        opacity: 1;
        visibility: visible;
        bottom: calc(100% + 10px);
    }
    
    /* Breadcrumb personalizado */
    .explorer-breadcrumb {
        background-color: #f8f9fa;
        padding: 8px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }
    
    .explorer-breadcrumb .separator {
        color: #6c757d;
        margin: 0 8px;
    }
    
    .btn-icon {
        padding: 0.25rem 0.5rem;
        line-height: 1;
    }
    
    /* Subfolder */
    .subfolder-container {
        margin-bottom: 10px;
    }
    
    .subfolder-header {
        display: flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        background-color: rgba(0,0,0,0.02);
        margin-bottom: 5px;
    }
    
    .subfolder-header:hover {
        background-color: rgba(0,0,0,0.05);
    }
    
    .subfolder-header i {
        margin-right: 8px;
    }
    
    .subfolder-header .subfolder-name {
        flex: 1;
    }
    
    .subfolder-header .document-count {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 11px;
        color: #6c757d;
    }
    
    .subfolder-content {
        padding-left: 20px;
        display: none; /* Subcarpetas cerradas por defecto */
    }
    
    /* Sección de título con conteo */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .section-header h4 {
        margin: 0;
        flex: 1;
    }
    
    .section-header .badge {
        font-size: 14px;
        padding: 5px 10px;
    }
</style>

<div class="file-explorer">
<!-- SECCIÓN CORPORATIVA -->
    <?php
        if ($id_pla == 'Nacional' && $tieneAccesoNacional) {
            // Obtener el total de archivos corporativos
            $sql_total_corp = "
                SELECT COUNT(*) as total
                FROM planteles_archivo
                WHERE id_cad = 1
                AND id_pla IS NULL
                AND est_pla_arc = 'Activo'
            ";
            $result_total_corp = mysqli_query($db, $sql_total_corp);
            $total_arch_corp = mysqli_fetch_assoc($result_total_corp)['total'];
            ?>
            
            <!-- Cabecera de la sección corporativa -->
            <div class="section-header">
                <h4>🏢 CORPORATIVO</h4>
                <span class="badge bg-warning text-dark"><?php echo $total_arch_corp; ?> documentos</span>
            </div>
            
            <!-- Contenedor principal de carpetas corporativas -->
            <div class="folders-grid">
                <?php
                // Obtener las carpetas distintas de los archivos corporativos
                $sql_carpetas = "
                    SELECT DISTINCT car_pla_arc, 
                    (SELECT COUNT(*) FROM planteles_archivo WHERE id_cad = 1 AND id_pla IS NULL AND car_pla_arc = pa.car_pla_arc AND est_pla_arc = 'Activo') as doc_count
                    FROM planteles_archivo pa
                    WHERE id_cad = 1 
                    AND id_pla IS NULL 
                    AND est_pla_arc = 'Activo'
                    ORDER BY car_pla_arc ASC
                ";
                
                $result_carpetas = mysqli_query($db, $sql_carpetas);
                $count_carpetas = mysqli_num_rows($result_carpetas);
                
                if ($count_carpetas == 0) {
                    // No hay carpetas corporativas
                    ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No hay documentos corporativos disponibles</p>
                            <small>Utilice el botón "Agregar archivo" seleccionando tipo "Corporativo"</small>
                        </div>
                    </div>
                    <?php
                } else {
                    // Mostrar documentos corporativos por carpeta
                    while ($carpeta = mysqli_fetch_assoc($result_carpetas)) {
                        $nombre_carpeta = ucfirst($carpeta['car_pla_arc']);
                        $doc_count = $carpeta['doc_count'];
                        ?>
                        <div class="folder-column">
                            <div class="folder-container corporate-container">
                                <div class="folder-header">
                                    <i class="fas fa-folder text-warning"></i>
                                    <span class="folder-name"><?php echo htmlspecialchars($nombre_carpeta); ?></span>
                                    <span class="document-count"><?php echo $doc_count; ?></span>
                                </div>
                                <div class="folder-content">
                                    <?php
                                    // Consultar archivos de esta carpeta corporativa
                                    $sql_archivos = "
                                        SELECT *
                                        FROM planteles_archivo
                                        WHERE id_cad = 1
                                        AND id_pla IS NULL
                                        AND car_pla_arc = '{$carpeta['car_pla_arc']}'
                                        AND est_pla_arc = 'Activo'
                                        ORDER BY fec_pla_arc DESC
                                    ";
                                    
                                    $result_archivos = mysqli_query($db, $sql_archivos);
                                    $total_archivos = mysqli_num_rows($result_archivos);
                                    
                                    // Si no hay archivos en esta carpeta, mostrar mensaje
                                    if ($total_archivos == 0) {
                                        ?>
                                        <div class="empty-state">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay documentos en esta carpeta.
                                        </div>
                                        <?php
                                    } else {
                                        // Mostrar cada archivo corporativo
                                        while ($archivo = mysqli_fetch_assoc($result_archivos)) {
                                            // Determinar clase e ícono según tipo de archivo
                                            $icono_clase = 'file-generic';
                                            $icono = 'fas fa-file';
                                            
                                            switch (strtolower($archivo['for_pla_arc'])) {
                                                case 'docx':
                                                case 'doc':
                                                    $icono_clase = 'file-word';
                                                    $icono = 'fas fa-file-word';
                                                    break;
                                                case 'xlsx':
                                                case 'xls':
                                                    $icono_clase = 'file-excel';
                                                    $icono = 'fas fa-file-excel';
                                                    break;
                                                case 'pptx':
                                                case 'ppt':
                                                    $icono_clase = 'file-powerpoint';
                                                    $icono = 'fas fa-file-powerpoint';
                                                    break;
                                                case 'pdf':
                                                    $icono_clase = 'file-pdf';
                                                    $icono = 'fas fa-file-pdf';
                                                    break;
                                            }
                                            
                                            // Formatear la fecha
                                            $fecha = date('d/m/Y H:i', strtotime($archivo['fec_pla_arc']));
                                            
                                            // Preparar descripción para tooltip
                                            $descripcion = !empty($archivo['des_pla_arc']) ? $archivo['des_pla_arc'] : 'Sin descripción';
                                            ?>
                                            <!-- Archivo en vista de lista compacta -->
                                            <div class="file-item custom-tooltip">
                                                <!-- Tooltip con info detallada -->
                                                <div class="tooltip-content">
                                                    <div><strong><?php echo htmlspecialchars($archivo['nom_pla_arc']); ?></strong></div>
                                                    <div>Tipo: <?php echo strtoupper($archivo['for_pla_arc']); ?></div>
                                                    <div>Fecha: <?php echo $fecha; ?></div>
                                                    <div>Descripción: <?php echo htmlspecialchars($descripcion); ?></div>
                                                </div>
                                                
                                                <!-- Ícono del archivo -->
                                                <div class="file-icon <?php echo $icono_clase; ?>">
                                                    <i class="<?php echo $icono; ?>"></i>
                                                </div>
                                                
                                                <!-- Nombre del archivo -->
                                                <div class="file-name">
                                                    <?php echo htmlspecialchars($archivo['nom_pla_arc']); ?>
                                                </div>
                                                
                                                <!-- Fecha del archivo -->
                                                <div class="file-date">
                                                    <?php echo $fecha; ?>
                                                </div>
                                                
                                                <!-- Acciones del archivo -->
                                                <div class="file-actions">
                                                    <a href="../img/archivos_plantel/<?php echo htmlspecialchars($archivo['arc_pla_arc']); ?>" 
                                                    target="_blank" class="btn btn-sm btn-outline-primary btn-icon">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger btn-icon btn-eliminar-archivo" 
                                                            data-id="<?php echo htmlspecialchars($archivo['id_pla_arc']); ?>" 
                                                            data-nombre="<?php echo htmlspecialchars($archivo['nom_pla_arc']); ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    ?>

    <!-- SECCIÓN DE PLANTELES/CDE -->
    <?php
    // Obtener planteles asignados al ejecutivo
    if ($id_pla == 'Nacional') {
        $sql_planteles = "
            SELECT p.id_pla, p.nom_pla,
            (SELECT COUNT(*) FROM planteles_archivo WHERE id_pla = p.id_pla AND est_pla_arc = 'Activo') as doc_count
            FROM planteles_ejecutivo pe
            JOIN plantel p ON p.id_pla = pe.id_pla
            WHERE pe.id_eje = '$id'
            ORDER BY p.nom_pla ASC
        ";
    } else {
        $sql_planteles = "
            SELECT id_pla, nom_pla,
            (SELECT COUNT(*) FROM planteles_archivo WHERE id_pla = plantel.id_pla AND est_pla_arc = 'Activo') as doc_count
            FROM plantel
            WHERE id_pla = '$id_pla'
        ";
    }

    $result_planteles = mysqli_query($db, $sql_planteles);
    $count_planteles = mysqli_num_rows($result_planteles);

    if ($count_planteles == 0) {
        ?>
        <div class="empty-state">
            <i class="fas fa-exclamation-triangle"></i>
            <p>No se encontraron CDE asignados a su cuenta</p>
            <small>Por favor contacte al administrador del sistema</small>
        </div>
        <?php
    } else {
        // Calcular el total de documentos en todos los CDE
        $total_docs_cde = 0;
        mysqli_data_seek($result_planteles, 0);
        while ($row = mysqli_fetch_assoc($result_planteles)) {
            $total_docs_cde += $row['doc_count'];
        }
        mysqli_data_seek($result_planteles, 0);
        ?>
        
        <!-- Cabecera de la sección CDE -->
        <div class="section-header mt-4">
            <h4>🕋 CENTRO DE DESARROLLO EMPRESARIAL</h4>
            <span class="badge bg-primary"><?php echo $total_docs_cde; ?> documentos</span>
        </div>
        
        <!-- Contenedor principal de CDE -->
        <div class="folders-grid">
            <?php
            // Iterar por cada plantel
            while ($plantel = mysqli_fetch_assoc($result_planteles)) {
                $id_plantel = $plantel['id_pla'];
                $nombre_plantel = $plantel['nom_pla'];
                $doc_count = $plantel['doc_count'];
                ?>
                <div class="folder-column">
                    <div class="folder-container cde-container">
                        <div class="folder-header">
                            <i class="fas fa-folder text-primary"></i>
                            <span class="folder-name"><?php echo htmlspecialchars($nombre_plantel); ?></span>
                            <span class="document-count"><?php echo $doc_count; ?></span>
                        </div>
                        <div class="folder-content">
                            <?php
                            // Obtener carpetas únicas de este plantel
                            $sql_carpetas = "
                                SELECT DISTINCT car_pla_arc,
                                (SELECT COUNT(*) FROM planteles_archivo WHERE id_pla = '$id_plantel' AND car_pla_arc = pa.car_pla_arc AND est_pla_arc = 'Activo') as folder_count
                                FROM planteles_archivo pa
                                WHERE id_pla = '$id_plantel'
                                AND est_pla_arc = 'Activo'
                                ORDER BY car_pla_arc ASC
                            ";
                            
                            $result_carpetas = mysqli_query($db, $sql_carpetas);
                            $count_carpetas = mysqli_num_rows($result_carpetas);
                            
                            // Verificar si hay documentos sin carpeta asignada (legacy)
                            $sql_archivos_sin_carpeta = "
                                SELECT COUNT(*) as sin_carpeta
                                FROM planteles_archivo
                                WHERE id_pla = '$id_plantel'
                                AND (car_pla_arc IS NULL OR car_pla_arc = '')
                                AND est_pla_arc = 'Activo'
                            ";
                            
                            $result_sin_carpeta_count = mysqli_query($db, $sql_archivos_sin_carpeta);
                            $sin_carpeta_count = mysqli_fetch_assoc($result_sin_carpeta_count)['sin_carpeta'];
                            
                            // Si no hay carpetas ni archivos legacy
                            if ($count_carpetas == 0 && $sin_carpeta_count == 0) {
                                ?>
                                <div class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>No hay documentos disponibles para este CDE</p>
                                    <small>Utilice el botón "Agregar archivo" para subir documentos</small>
                                </div>
                                <?php
                            } else {
                                // Si hay documentos sin carpeta (legacy), mostrarlos
                                if ($sin_carpeta_count > 0) {
                                    ?>
                                    <div class="subfolder-container mb-3">
                                        <div class="subfolder-header">
                                            <i class="fas fa-folder text-secondary"></i>
                                            <span class="subfolder-name">Sin categoría</span>
                                            <span class="document-count"><?php echo $sin_carpeta_count; ?></span>
                                        </div>
                                        <div class="subfolder-content">
                                            <?php
                                            $sql_archivos_sin_cat = "
                                                SELECT *
                                                FROM planteles_archivo
                                                WHERE id_pla = '$id_plantel'
                                                AND (car_pla_arc IS NULL OR car_pla_arc = '')
                                                AND est_pla_arc = 'Activo'
                                                ORDER BY fec_pla_arc DESC
                                            ";
                                            $result_sin_carpeta = mysqli_query($db, $sql_archivos_sin_cat);
                                            
                                            while ($archivo = mysqli_fetch_assoc($result_sin_carpeta)) {
                                                // Determinar clase e ícono según tipo de archivo
                                                $icono_clase = 'file-generic';
                                                $icono = 'fas fa-file';
                                                
                                                switch (strtolower($archivo['for_pla_arc'])) {
                                                    case 'docx':
                                                    case 'doc':
                                                        $icono_clase = 'file-word';
                                                        $icono = 'fas fa-file-word';
                                                        break;
                                                    case 'xlsx':
                                                    case 'xls':
                                                        $icono_clase = 'file-excel';
                                                        $icono = 'fas fa-file-excel';
                                                        break;
                                                    case 'pptx':
                                                    case 'ppt':
                                                        $icono_clase = 'file-powerpoint';
                                                        $icono = 'fas fa-file-powerpoint';
                                                        break;
                                                    case 'pdf':
                                                        $icono_clase = 'file-pdf';
                                                        $icono = 'fas fa-file-pdf';
                                                        break;
                                                }
                                                
                                                // Formatear la fecha
                                                $fecha = date('d/m/Y H:i', strtotime($archivo['fec_pla_arc']));
                                                
                                                // Preparar descripción para tooltip
                                                $descripcion = !empty($archivo['des_pla_arc']) ? $archivo['des_pla_arc'] : 'Sin descripción';
                                                ?>
                                                <div class="file-item custom-tooltip">
                                                    <!-- Tooltip con info detallada -->
                                                    <div class="tooltip-content">
                                                        <div><strong><?php echo htmlspecialchars($archivo['nom_pla_arc']); ?></strong></div>
                                                        <div>Tipo: <?php echo strtoupper($archivo['for_pla_arc']); ?></div>
                                                        <div>Fecha: <?php echo $fecha; ?></div>
                                                        <div>Descripción: <?php echo htmlspecialchars($descripcion); ?></div>
                                                    </div>
                                                    
                                                    <!-- Ícono del archivo -->
                                                    <div class="file-icon <?php echo $icono_clase; ?>">
                                                        <i class="<?php echo $icono; ?>"></i>
                                                    </div>
                                                    
                                                    <!-- Nombre del archivo -->
                                                    <div class="file-name">
                                                        <?php echo htmlspecialchars($archivo['nom_pla_arc']); ?>
                                                    </div>
                                                    
                                                    <!-- Fecha del archivo -->
                                                    <div class="file-date">
                                                        <?php echo $fecha; ?>
                                                    </div>
                                                    
                                                    <!-- Acciones del archivo -->
                                                    <div class="file-actions">
                                                        <a href="../img/archivos_plantel/<?php echo htmlspecialchars($archivo['arc_pla_arc']); ?>" 
                                                           target="_blank" class="btn btn-sm btn-outline-primary btn-icon">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger btn-icon btn-eliminar-archivo" 
                                                                data-id="<?php echo htmlspecialchars($archivo['id_pla_arc']); ?>" 
                                                                data-nombre="<?php echo htmlspecialchars($archivo['nom_pla_arc']); ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                
                                // Mostrar carpetas con sus documentos
                                if ($count_carpetas > 0) {
                                    while ($carpeta = mysqli_fetch_assoc($result_carpetas)) {
                                        $nombre_carpeta = ucfirst($carpeta['car_pla_arc']);
                                        $folder_count = $carpeta['folder_count'];
                                        ?>
                                        <div class="subfolder-container mb-3">
                                            <div class="subfolder-header">
                                                <i class="fas fa-folder text-primary"></i>
                                                <span class="subfolder-name"><?php echo htmlspecialchars($nombre_carpeta); ?></span>
                                                <span class="document-count"><?php echo $folder_count; ?></span>
                                            </div>
                                            <div class="subfolder-content">
                                                <?php
                                                // Consultar archivos de esta carpeta
                                                $sql_archivos = "
                                                    SELECT *
                                                    FROM planteles_archivo
                                                    WHERE id_pla = '$id_plantel'
                                                    AND car_pla_arc = '{$carpeta['car_pla_arc']}'
                                                    AND est_pla_arc = 'Activo'
                                                    ORDER BY fec_pla_arc DESC
                                                ";
                                                
                                                $result_archivos = mysqli_query($db, $sql_archivos);
                                                $total_archivos = mysqli_num_rows($result_archivos);
                                                
                                                // Si no hay archivos en esta carpeta
                                                if ($total_archivos == 0) {
                                                    ?>
                                                    <div class="empty-state">
                                                        <i class="fas fa-file-alt"></i>
                                                        <p>No hay documentos en esta carpeta</p>
                                                    </div>
                                                    <?php
                                                } else {
                                                    // Mostrar cada archivo
                                                    while ($archivo = mysqli_fetch_assoc($result_archivos)) {
                                                        // Determinar clase e ícono según tipo de archivo
                                                        $icono_clase = 'file-generic';
                                                        $icono = 'fas fa-file';
                                                        
                                                        switch (strtolower($archivo['for_pla_arc'])) {
                                                            case 'docx':
                                                            case 'doc':
                                                                $icono_clase = 'file-word';
                                                                $icono = 'fas fa-file-word';
                                                                break;
                                                            case 'xlsx':
                                                            case 'xls':
                                                                $icono_clase = 'file-excel';
                                                                $icono = 'fas fa-file-excel';
                                                                break;
                                                            case 'pptx':
                                                            case 'ppt':
                                                                $icono_clase = 'file-powerpoint';
                                                                $icono = 'fas fa-file-powerpoint';
                                                                break;
                                                            case 'pdf':
                                                                $icono_clase = 'file-pdf';
                                                                $icono = 'fas fa-file-pdf';
                                                                break;
                                                        }
                                                        
                                                        // Formatear la fecha
                                                        $fecha = date('d/m/Y H:i', strtotime($archivo['fec_pla_arc']));
                                                        
                                                        // Preparar descripción para tooltip
                                                        $descripcion = !empty($archivo['des_pla_arc']) ? $archivo['des_pla_arc'] : 'Sin descripción';
                                                        ?>
                                                        <div class="file-item custom-tooltip">
                                                            <!-- Tooltip con info detallada -->
                                                            <div class="tooltip-content">
                                                                <div><strong><?php echo htmlspecialchars($archivo['nom_pla_arc']); ?></strong></div>
                                                                <div>Tipo: <?php echo strtoupper($archivo['for_pla_arc']); ?></div>
                                                                <div>Fecha: <?php echo $fecha; ?></div>
                                                                <div>Descripción: <?php echo htmlspecialchars($descripcion); ?></div>
                                                            </div>
                                                            
                                                            <!-- Ícono del archivo -->
                                                            <div class="file-icon <?php echo $icono_clase; ?>">
                                                                <i class="<?php echo $icono; ?>"></i>
                                                            </div>
                                                            
                                                            <!-- Nombre del archivo -->
                                                            <div class="file-name">
                                                                <?php echo htmlspecialchars($archivo['nom_pla_arc']); ?>
                                                            </div>
                                                            
                                                            <!-- Fecha del archivo -->
                                                            <div class="file-date">
                                                                <?php echo $fecha; ?>
                                                            </div>
                                                            
                                                            <!-- Acciones del archivo -->
                                                            <div class="file-actions">
                                                                <a href="../img/archivos_plantel/<?php echo htmlspecialchars($archivo['arc_pla_arc']); ?>" 
                                                                   target="_blank" class="btn btn-sm btn-outline-primary btn-icon">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger btn-icon btn-eliminar-archivo" 
                                                                        data-id="<?php echo htmlspecialchars($archivo['id_pla_arc']); ?>" 
                                                                        data-nombre="<?php echo htmlspecialchars($archivo['nom_pla_arc']); ?>">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>

<script>
    $(document).ready(function() {
        // Las carpetas vienen cerradas por defecto
        
        // Evento para abrir/cerrar carpetas principales
        $('.folder-header').click(function() {
            var folderContent = $(this).siblings('.folder-content');
            var folderIcon = $(this).find('i.fas');
            
            folderContent.slideToggle(200, function() {
                // Cuando termine la animación, verificar si está abierta o cerrada
                if (folderContent.is(':visible')) {
                    folderIcon.removeClass('fa-folder').addClass('fa-folder-open');
                } else {
                    folderIcon.removeClass('fa-folder-open').addClass('fa-folder');
                }
            });
        });
        
        // Evento para abrir/cerrar subcarpetas
        $('.subfolder-header').click(function() {
            var subfolderContent = $(this).siblings('.subfolder-content');
            var subfolderIcon = $(this).find('i.fas');
            
            subfolderContent.slideToggle(200, function() {
                // Cuando termine la animación, verificar si está abierta o cerrada
                if (subfolderContent.is(':visible')) {
                    subfolderIcon.removeClass('fa-folder').addClass('fa-folder-open');
                } else {
                    subfolderIcon.removeClass('fa-folder-open').addClass('fa-folder');
                }
            });
        });
        
        // Abrir archivo al hacer clic en él
        $('.file-item').click(function(e) {
            // Solo abrir si no se hizo clic en los botones de acciones
            if (!$(e.target).closest('.file-actions').length) {
                // Encontrar el enlace y abrirlo
                const link = $(this).find('a').attr('href');
                if (link) {
                    window.open(link, '_blank');
                }
            }
        });
        
        // Manejo de eliminación de archivos
        $(document).on('click', '.btn-eliminar-archivo', function(event) {
            event.preventDefault();
            event.stopPropagation(); // Evitar que se propague al padre
            
            const idArchivo = $(this).data('id');
            const nombreArchivo = $(this).data('nombre');
            
            swal({
                title: "¿Estás seguro?",
                text: `¿Deseas eliminar el archivo "${nombreArchivo}"? Esta acción no se puede deshacer.`,
                icon: "warning",
                buttons: ["Cancelar", "Sí, eliminar"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#loader').removeClass('hidden');
                    
                    $.ajax({
                        url: 'server/controlador_planteles_archivo.php',
                        type: 'POST',
                        data: {
                            accion: 'eliminar',
                            id_pla_arc: idArchivo
                        },
                        success: function(response) {
                            $('#loader').addClass('hidden');
                            
                            try {
                                var data = typeof response === 'string' ? JSON.parse(response) : response;
                                
                                if (data.estatus === 1) {
                                    swal("Eliminado", data.mensaje, "success", {
                                        button: "Aceptar",
                                    }).then(() => {
                                        obtener_datos();
                                    });
                                } else {
                                    swal("Error", data.mensaje, "error", {
                                        button: "Aceptar",
                                    });
                                }
                            } catch (e) {
                                console.error('Error al parsear respuesta:', e);
                                swal("Error", "Error en el formato de respuesta del servidor", "error", {
                                    button: "Aceptar",
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loader').addClass('hidden');
                            console.error('Error AJAX:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                            swal("Error", "Error en la comunicación con el servidor", "error", {
                                button: "Aceptar",
                            });
                        }
                    });
                }
            });
        });
    });
</script>