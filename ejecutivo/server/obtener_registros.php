<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$escala = $_POST['escala'];
	if( $escala != 'grupo' ){
		$inicio = $_POST['inicio'];
		$fin = $_POST['fin'];
		$id_eje = $_POST['id_eje'];
		//echo 'jeje';
	}
	
?>

<?php
/*
 * ===============================================================
 * FUNCIÓN: obtener_ejecutivo_paternal
 * ===============================================================
 * Obtiene el ejecutivo paternal (Team Leader o Sales Manager)
 * basado en la jerarquía definida por el id_padre y ran_eje.
 * 
 * Parámetros:
 * - $db: Conexión a la base de datos
 * - $id_eje: ID del ejecutivo para el que buscamos el superior
 * - $tipo: Tipo de superior ('TL' o 'GC')
 * 
 * Retorna:
 * - Array con datos del ejecutivo o NULL si no existe
 * ===============================================================
 */
function obtener_ejecutivo_paternal($db, $id_eje, $tipo = 'TL') {
    // Si no hay id_eje válido, retornar NULL
    if (!$id_eje || $id_eje <= 0) {
        return null;
    }
    
    // Primero, verificar si el ejecutivo mismo es del tipo solicitado
    $sql_self = "SELECT id_eje, nom_eje, ran_eje FROM ejecutivo 
                WHERE id_eje = '$id_eje' AND ran_eje = '$tipo' LIMIT 1";
    $resultado_self = mysqli_query($db, $sql_self);
    
    if ($resultado_self && mysqli_num_rows($resultado_self) > 0) {
        // Si el ejecutivo ya es del tipo solicitado, se retorna a sí mismo
        return mysqli_fetch_assoc($resultado_self);
    }
    
    // Obtener info del ejecutivo para verificar jerarquía
    $sql_ejecutivo = "SELECT id_eje, id_padre, ran_eje, id_pla FROM ejecutivo 
                      WHERE id_eje = '$id_eje' LIMIT 1";
    $resultado_ejecutivo = mysqli_query($db, $sql_ejecutivo);
    
    if (!$resultado_ejecutivo || mysqli_num_rows($resultado_ejecutivo) == 0) {
        return null;
    }
    
    $ejecutivo = mysqli_fetch_assoc($resultado_ejecutivo);
    $id_padre = $ejecutivo['id_padre'];
    $id_plantel = $ejecutivo['id_pla'];
    
    // Buscar padre directo que sea del tipo solicitado
    if ($id_padre) {
        $sql_padre = "SELECT id_eje, nom_eje, ran_eje FROM ejecutivo 
                      WHERE id_eje = '$id_padre' AND ran_eje = '$tipo' LIMIT 1";
        $resultado_padre = mysqli_query($db, $sql_padre);
        
        if ($resultado_padre && mysqli_num_rows($resultado_padre) > 0) {
            return mysqli_fetch_assoc($resultado_padre);
        }
        
        // Si el padre no es del tipo pero estamos buscando GC, buscar el padre del padre
        if ($tipo == 'GC') {
            $sql_padre_info = "SELECT id_padre FROM ejecutivo WHERE id_eje = '$id_padre' LIMIT 1";
            $resultado_padre_info = mysqli_query($db, $sql_padre_info);
            
            if ($resultado_padre_info && mysqli_num_rows($resultado_padre_info) > 0) {
                $padre_info = mysqli_fetch_assoc($resultado_padre_info);
                $id_abuelo = $padre_info['id_padre'];
                
                if ($id_abuelo) {
                    $sql_abuelo = "SELECT id_eje, nom_eje, ran_eje FROM ejecutivo 
                                  WHERE id_eje = '$id_abuelo' AND ran_eje = 'GC' LIMIT 1";
                    $resultado_abuelo = mysqli_query($db, $sql_abuelo);
                    
                    if ($resultado_abuelo && mysqli_num_rows($resultado_abuelo) > 0) {
                        return mysqli_fetch_assoc($resultado_abuelo);
                    }
                }
            }
        }
    }
    
    // Si no se encontró en la jerarquía directa, buscar por plantel
    if ($id_plantel) {
        $sql_plantel = "SELECT id_eje, nom_eje, ran_eje FROM ejecutivo 
                        WHERE id_pla = '$id_plantel' AND ran_eje = '$tipo' 
                        LIMIT 1";
        $resultado_plantel = mysqli_query($db, $sql_plantel);
        
        if ($resultado_plantel && mysqli_num_rows($resultado_plantel) > 0) {
            return mysqli_fetch_assoc($resultado_plantel);
        }
    }
    
    // No se encontró ningún superior del tipo solicitado
    return null;
}

// Consultas SQL según la escala
if( $escala == 'plantel' ){

    $id_pla = $_POST['id_pla'];

    $sqlEjecutivosPlantel = "
        SELECT * FROM ejecutivo WHERE id_pla = '$id_pla' AND tip_eje = 'Ejecutivo'
    ";

    $resultadoEjecutivosPlantel = mysqli_query( $db, $sqlEjecutivosPlantel );

    $contador = 0;
    $sqlEjecutivosAdicion = 'AND ( ';
    while($filaEjecutivosPlantel = mysqli_fetch_assoc($resultadoEjecutivosPlantel)) {
        if ($contador > 0) {
            $sqlEjecutivosAdicion .= ' OR ';
        }
        $sqlEjecutivosAdicion .= 'ej.id_eje = '.$filaEjecutivosPlantel['id_eje'];
        $contador++;
    }
    $sqlEjecutivosAdicion = $sqlEjecutivosAdicion." ) ";

    $sql = "
        SELECT
        ci.id_cit,
        ej.id_eje,
        obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
        ej.nom_eje,
        ej_agendo.nom_eje AS nom_eje_agendo,
        ej.cor_eje,
        pl.id_pla AS id_pla,
        pl.nom_pla,
        ra.id_ram,
        al.id_alu,
        ge.id_gen,
        ar.id_alu_ram,
        al.ing_alu,
        CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
        al.tel_alu,
        ge.nom_gen,
        ra.nom_ram,
        ge.ini_gen,
        ge.fin_gen,
        ar.est1_alu_ram,
        ar.est_alu_ram,
        obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
        obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
        obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
        obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
        ci.tip_cit AS tipo_cita,
        obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
        FROM alu_ram ar
        INNER JOIN alumno al ON al.id_alu = ar.id_alu1
        INNER JOIN cita ci ON ci.id_cit = al.id_cit1
        INNER JOIN ejecutivo ej ON ej.id_eje = ci.id_eje3
        LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
        INNER JOIN rama ra ON ra.id_ram = ar.id_ram3
        INNER JOIN plantel pl ON pl.id_pla = ra.id_pla1
        INNER JOIN generacion ge ON ge.id_gen = ar.id_gen1
        WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
        AND ej.tip_eje = 'Ejecutivo'
        AND al.plantel_beneficiado = '$id_pla'
        UNION ALL
        SELECT
        ci.id_cit,
        ej.id_eje,
        obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
        ej.nom_eje,
        ej_agendo.nom_eje AS nom_eje_agendo,
        ej.cor_eje,
        pl.id_pla AS id_pla,
        pl.nom_pla,
        ra.id_ram,
        al.id_alu,
        ge.id_gen,
        ar.id_alu_ram,
        al.ing_alu,
        CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
        al.tel_alu,
        ge.nom_gen,
        ra.nom_ram,
        ge.ini_gen,
        ge.fin_gen,
        ar.est1_alu_ram,
        ar.est_alu_ram,
        obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
        obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
        obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
        obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
        ci.tip_cit AS tipo_cita,
        obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
        FROM alu_ram ar
        INNER JOIN alumno al ON al.id_alu = ar.id_alu1
        INNER JOIN cita ci ON ci.id_cit = al.id_cit1
        INNER JOIN ejecutivo ej ON ej.id_eje = ci.id_eje3
        LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
        INNER JOIN rama ra ON ra.id_ram = ar.id_ram3
        INNER JOIN plantel pl ON pl.id_pla = ra.id_pla1
        INNER JOIN generacion ge ON ge.id_gen = ar.id_gen1
        WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
        AND ej.tip_eje = 'Ejecutivo'
        AND al.plantel_beneficiado IS NULL
        AND ej.id_pla = '$id_pla'
    ";

    $sql .= $sqlEjecutivosAdicion;
    // echo 'plantel';
}
?>

<?php
if( $escala == 'grupo' ){
    
    $id_gen = $_POST['id_gen'];
    $sql = "
        SELECT
        ci.id_cit,
        ej.id_eje,
        obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
        ej.nom_eje,
        ej_agendo.nom_eje AS nom_eje_agendo,
        ej.cor_eje,
        pl.id_pla AS id_pla,
        pl.nom_pla,
        ra.id_ram,
        al.id_alu,
        ge.id_gen,
        ar.id_alu_ram,
        al.ing_alu,
        CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
        al.tel_alu,
        ge.nom_gen,
        ra.nom_ram,
        ge.ini_gen,
        ge.fin_gen,
        ar.est1_alu_ram,
        ar.est_alu_ram,
        obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
        obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
        obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
        obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
        ci.tip_cit AS tipo_cita,
        obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
        FROM alu_ram ar
        INNER JOIN alumno al ON al.id_alu = ar.id_alu1
        INNER JOIN cita ci ON ci.id_cit = al.id_cit1
        INNER JOIN ejecutivo ej ON ej.id_eje = ci.id_eje3
        LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
        INNER JOIN rama ra ON ra.id_ram = ar.id_ram3
        INNER JOIN plantel pl ON pl.id_pla = ra.id_pla1
        INNER JOIN generacion ge ON ge.id_gen = ar.id_gen1
        WHERE ej.tip_eje = 'Ejecutivo'
        AND ar.id_gen1 = '$id_gen'
    ";
} else {
    // 
    
    if ( $id_eje == 'Todos' ) {
        //echo 'TOOODOS';
        $sqlPlanteles = "
            SELECT *
            FROM planteles_ejecutivo
            INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
            WHERE id_eje = '$id'
        ";

        $totalValidacion = obtener_datos_consulta( $db, $sqlPlanteles )['total'];

        if( $totalValidacion == 0 || $totalValidacion == 1 ){

            $id_pla = $plantel;
            $sqlEjecutivosPlantel = "
                SELECT * FROM ejecutivo WHERE id_pla = '$id_pla' AND tip_eje = 'Ejecutivo'
            ";

            $resultadoEjecutivosPlantel = mysqli_query( $db, $sqlEjecutivosPlantel );
        
            $contador = 0;
            $sqlEjecutivosAdicion = 'AND ( ';
            while($filaEjecutivosPlantel = mysqli_fetch_assoc($resultadoEjecutivosPlantel)) {
                if ($contador > 0) {
                    $sqlEjecutivosAdicion .= ' OR ';
                }
                $sqlEjecutivosAdicion .= 'ej.id_eje = '.$filaEjecutivosPlantel['id_eje'];
                $contador++;
            }
            $sqlEjecutivosAdicion = $sqlEjecutivosAdicion." ) ";

        // CASO GC
            $sql = "
                SELECT
                ci.id_cit,
                ej.id_eje,
                obtener_plantel_ejecutivo(ej.id_eje) AS plantel_ejecutivo,
                ej.nom_eje,
                ej_agendo.nom_eje AS nom_eje_agendo,
                ej.cor_eje,
                pl.id_pla AS id_pla,
                pl.nom_pla,
                ra.id_ram,
                al.id_alu,
                ge.id_gen,
                ar.id_alu_ram,
                al.ing_alu,
                CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
                al.tel_alu,
                ge.nom_gen,
                ra.nom_ram,
                ge.ini_gen,
                ge.fin_gen,
                ar.est1_alu_ram,
                ar.est_alu_ram,
                obtener_fin_colegiatura(ar.id_alu_ram) AS fin_colegiatura,
                obtener_monto_colegiatura(ar.id_alu_ram) AS monto_colegiatura,
                obtener_forma_inscripcion(ar.id_alu_ram) AS forma_inscripcion,
                obtener_monto_inscripcion(ar.id_alu_ram) AS monto_inscripcion,
                ci.tip_cit AS tipo_cita,
                obtener_estatus_general(ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram) AS estatus_general
                FROM alu_ram AS ar
                INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
                INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
                INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
                LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
                INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
                INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
                INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
                WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
                AND ej.tip_eje = 'Ejecutivo'
                AND al.plantel_beneficiado = '$id_pla'
            
                UNION ALL
            
                SELECT
                ci.id_cit,
                ej.id_eje,
                obtener_plantel_ejecutivo(ej.id_eje) AS plantel_ejecutivo,
                ej.nom_eje,
                ej_agendo.nom_eje AS nom_eje_agendo,
                ej.cor_eje,
                pl.id_pla AS id_pla,
                pl.nom_pla,
                ra.id_ram,
                al.id_alu,
                ge.id_gen,
                ar.id_alu_ram,
                al.ing_alu,
                CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
                al.tel_alu,
                ge.nom_gen,
                ra.nom_ram,
                ge.ini_gen,
                ge.fin_gen,
                ar.est1_alu_ram,
                ar.est_alu_ram,
                obtener_fin_colegiatura(ar.id_alu_ram) AS fin_colegiatura,
                obtener_monto_colegiatura(ar.id_alu_ram) AS monto_colegiatura,
                obtener_forma_inscripcion(ar.id_alu_ram) AS forma_inscripcion,
                obtener_monto_inscripcion(ar.id_alu_ram) AS monto_inscripcion,
                ci.tip_cit AS tipo_cita,
                obtener_estatus_general(ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram) AS estatus_general
                FROM alu_ram AS ar
                INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
                INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
                INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
                LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
                INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
                INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
                INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
                WHERE DATE(al.ing_alu) BETWEEN '$inicio' AND '$fin'
                AND ej.tip_eje = 'Ejecutivo'
                AND ej.id_pla = '$id_pla'
            ";

            $sql .= $sqlEjecutivosAdicion;

            // echo "CASO GC";

        // CASO GC
        } else {
        // DC
            $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

            $contador = 0;
            $sqlPlanteles = '';
            while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
                if ($contador > 0) {
                    $sqlPlanteles .= ' OR ';
                }
                $sqlPlanteles .= 'ej.id_pla = '.$filaPlanteles['id_pla'];
                $contador++;
            }

            $sqlPlanteles = $sqlPlanteles." ) ";

            $sql = "
                SELECT
                obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
                ci.id_cit,
                ej.id_eje,
                ej.nom_eje,
                ej_agendo.nom_eje AS nom_eje_agendo,
                ej.cor_eje,
                pl.id_pla AS id_pla,
                pl.nom_pla,
                ra.id_ram,
                al.id_alu,
                ge.id_gen,
                ar.id_alu_ram,
                al.id_alu,
                al.ing_alu,
                CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
                al.tel_alu, 
                ge.nom_gen,
                ra.nom_ram,
                ge.ini_gen,
                ge.fin_gen,
                ar.est1_alu_ram,
                ar.est_alu_ram,
                obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
                obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,

                obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
                obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,

                ci.tip_cit AS tipo_cita,
                obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general

                FROM alu_ram AS ar
                INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
                INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
                INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
                LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
                INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
                INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
                INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
                WHERE DATE( al.ing_alu ) BETWEEN '$inicio' AND '$fin' AND ej.tip_eje = 'Ejecutivo'
                AND (
            ";


            $sql .= $sqlPlanteles;
            
            // echo "CASO DC";
            
            //echo $sql;
            
        // DC
        }

    } else {
        // ASESOR
        $sql = "
            SELECT
            obtener_plantel_ejecutivo( ej.id_eje ) AS plantel_ejecutivo,
            ci.id_cit,
            ej.id_eje,
            ej.nom_eje,
            ej_agendo.nom_eje AS nom_eje_agendo,
            ej.cor_eje,
            pl.id_pla AS id_pla,
            pl.nom_pla,
            ra.id_ram,
            al.id_alu,
            ge.id_gen,
            ar.id_alu_ram,
            al.ing_alu,
            CONCAT_WS(' ', al.nom_alu, al.app_alu, al.apm_alu) AS nom_alu,
            al.tel_alu, 
            ge.nom_gen,
            ra.nom_ram,
            ge.ini_gen,
            ge.fin_gen,
            ar.est1_alu_ram,
            ar.est_alu_ram,
            obtener_fin_colegiatura( ar.id_alu_ram ) AS fin_colegiatura,
            obtener_monto_colegiatura( ar.id_alu_ram ) AS monto_colegiatura,
            obtener_forma_inscripcion( ar.id_alu_ram ) AS forma_inscripcion,
            obtener_monto_inscripcion( ar.id_alu_ram ) AS monto_inscripcion,
            ci.tip_cit AS tipo_cita,
            obtener_estatus_general( ar.id_alu_ram, ge.fin_gen, ar.est1_alu_ram ) AS estatus_general
            FROM alu_ram AS ar
            INNER JOIN alumno AS al ON al.id_alu = ar.id_alu1
            INNER JOIN cita AS ci ON ci.id_cit = al.id_cit1
            INNER JOIN ejecutivo AS ej ON ej.id_eje = ci.id_eje3
            LEFT JOIN ejecutivo ej_agendo ON ej_agendo.id_eje = ci.id_eje_agendo
            INNER JOIN rama AS ra ON ra.id_ram = ar.id_ram3
            INNER JOIN plantel AS pl ON pl.id_pla = ra.id_pla1
            INNER JOIN generacion AS ge ON ge.id_gen = ar.id_gen1
            WHERE ej.id_eje = '$id_eje' AND DATE( al.ing_alu ) BETWEEN '$inicio' AND '$fin' AND ej.tip_eje = 'Ejecutivo'
        ";
        // ASESOR
    }
    // 
}

$sqlReingresos = "AND est1_alu_ram IS NULL";
$sql .= $sqlReingresos;

// echo $sql;
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<!-- Modal estructura -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SELECCIONA EL PDF QUE NECESITAS DEL REGISTRO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" id="btnSolicitud">
                        <i class="fas fa-file-alt me-2"></i> Solicitud de Inscripción
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" id="btnTramites">
                        <i class="fas fa-file-import me-2"></i> Carta de Trámites  
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" id="btnExpediente">
                        <i class="fas fa-folder-open me-2"></i> Carta de Expediente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
    moment.locale('es');

    var container = document.querySelector('#data-sheet');
    // Modificamos la definición de las columnas para incluir TEAM LEADER y SALES MANAGER
    var colHeaders = ['FECHA', 'CDE ORIGEN', 'CDE DESTINO', 'CONSULTOR', 'EJECUTIVO', 'TEAM LEADER', 'SALES MANAGER', 'PROGRAMA', 'GRUPO', 'NOMBRE', 'TELÉFONO', 'FECHA LÍMITE COLEG.', 'MONTO COLEG.', 'TIPO DE CITA', 'FORMA. PAGO', 'MONTO INSCRIP.', 'ESTATUS', 'PRESENTACION', 'ID' ];
    // Ajustamos el tamaño del array para que coincida con el número de columnas (19 en total)
    var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]);

    function nameColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
        // Get the ID from the last column (ahora índice 18)
        const id_alu_ram = instance.getDataAtCell(row, 18);
        
        if (value && id_alu_ram) {
            // Create wrapper element for the clickable name
            const wrapper = document.createElement('div');
            wrapper.innerHTML = value;
            wrapper.style.cursor = 'pointer';
            
            // Agregamos las clases de estilo en lugar de estilos inline
            wrapper.classList.add('text-primary', 'custom-link');
            
            // Add click event to open modal
            wrapper.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                abrirModal(id_alu_ram);
            });
            
            // Clear the cell and append the wrapper
            td.innerHTML = '';
            td.appendChild(wrapper);
        } else {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
        }
        
        td.style.backgroundColor = '#E3E6E7';
    }

    function abrirModal(id_alu_ram) {
        const modal = new bootstrap.Modal(document.getElementById('documentModal'));
        const modalEl = document.getElementById('documentModal');

        // Configurar listeners de botones
        const btnSolicitud = modalEl.querySelector('#btnSolicitud');
        const btnTramites = modalEl.querySelector('#btnTramites');
        const btnExpediente = modalEl.querySelector('#btnExpediente');

        btnSolicitud.onclick = (e) => {
            e.preventDefault();
            window.open(`solicitud_inscripcion.php?id_alu_ram=${id_alu_ram}`, '_blank');
            modal.hide();
        };

        btnTramites.onclick = (e) => {
            e.preventDefault();
            window.open(`carta_tramites.php?id_alu_ram=${id_alu_ram}`, '_blank');
            modal.hide();
        };

        btnExpediente.onclick = (e) => {
            e.preventDefault();
            window.open(`carta_expediente.php?id_alu_ram=${id_alu_ram}`, '_blank');
            modal.hide();
        };

        modal.show();
    }
</script>

<?php  
    $data = [];
    $resultado = mysqli_query($db, $sql);
    
    if( $escala != '' && $escala == 'estructura' ){
        $data = obtener_tabla_estructura_registros($id_eje, $inicio, $fin, $db, $data);
    }

    $resultado = mysqli_query($db, $sql);
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $data[] = $fila;
    }
    
    if (sizeof($data) > 0) {
        // Los datos ya están en $data, no es necesario realizar otra consulta SQL
        foreach ($data as $fila) {
            // Obtener Team Leader y Sales Manager para este ejecutivo
            $team_leader = obtener_ejecutivo_paternal($db, $fila['id_eje'], 'TL');
            $sales_manager = obtener_ejecutivo_paternal($db, $fila['id_eje'], 'GC');
            
            // Nombres de Team Leader y Sales Manager (o valores vacíos si no existen)
            $nom_team_leader = ($team_leader) ? $team_leader['nom_eje'] : '';
            $nom_sales_manager = ($sales_manager) ? $sales_manager['nom_eje'] : '';
            
            // Asignamos y codificamos las variables en orden
            $ing_alu = json_encode(fechaFormateadaCompacta2($fila['ing_alu'])); // 0
            $plantel_origen = json_encode($fila['plantel_ejecutivo']); // 1
            $plantel_destino = json_encode($fila['nom_pla']); // 2
            $nom_eje = json_encode($fila['nom_eje']); // 3
            $nom_eje_agendo = json_encode($fila['nom_eje_agendo']); // 4
            
            // Nuevos campos para Team Leader y Sales Manager
            $team_leader_nombre = json_encode($nom_team_leader); // 5
            $sales_manager_nombre = json_encode($nom_sales_manager); // 6
            
            $nom_ram = json_encode($fila['nom_ram']); // 7 (antes 5)
            $nom_gen = json_encode($fila['nom_gen']); // 8 (antes 6)
            $nom_alu = json_encode($fila['nom_alu']); // 9 (antes 7)
            $tel_alu = json_encode($fila['tel_alu']); // 10 (antes 8)
            $fin_colegiatura = json_encode(fechaFormateadaCompacta2($fila['fin_colegiatura'])); // 11 (antes 9)
            $monto_colegiatura = json_encode($fila['monto_colegiatura']); // 12 (antes 10)
            $tipo_cita = json_encode($fila['tipo_cita']); // 13 (antes 11)
            $forma_inscripcion = json_encode($fila['forma_inscripcion']); // 14 (antes 12)
            $monto_inscripcion = json_encode($fila['monto_inscripcion']); // 15 (antes 13)
            $estatus_general = json_encode($fila['estatus_general']); // 16 (antes 14)

            $id_alu_ram_aux = $fila['id_alu_ram'];
            $sqlPresentacion = "SELECT obtener_estatus_presentacion( $id_alu_ram_aux ) AS estatus_presentacion";
            $estatus_presentacion = obtener_datos_consulta( $db, $sqlPresentacion )['datos']['estatus_presentacion'];

            $est_alu_ram = json_encode( $estatus_presentacion ); // 17 (antes 15)
            $id_alu_ram = json_encode($fila['id_alu_ram']); // 18 (antes 16)
    
            echo "data.push([$ing_alu, $plantel_origen, $plantel_destino, $nom_eje, $nom_eje_agendo, $team_leader_nombre, $sales_manager_nombre, $nom_ram, $nom_gen, $nom_alu, $tel_alu, $fin_colegiatura, $monto_colegiatura, $tipo_cita, $forma_inscripcion, $monto_inscripcion, $estatus_general, $est_alu_ram, $id_alu_ram]);\n";
        }
    } else {
        // Ajustamos el tamaño del array a 19 elementos para que coincida con el número de columnas
        echo 'data = Array(19).fill(0).map(() => ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]);';
    }
    
    // Sección de los dropdown (sin cambios)
    if( $permisos == 1 ){
?>
        var dropdownEjecutivo = [
            <?php
                $sqlCerrador = "
                    SELECT *
                    FROM ejecutivo
                    WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
                    ORDER BY nom_eje ASC
                ";

                $resultadoCerrador = mysqli_query( $db, $sqlCerrador );

                while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
                    echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
                }
            ?>
        ];
<?php
    } else if( $permisos == 2 ){
?>
        var dropdownEjecutivo = [
            <?php
                $sqlCerrador = "
                    SELECT *
                    FROM ejecutivo
                    INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                    WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_cad1 = 1 AND id_eje != 2311
                    ORDER BY nom_eje ASC
                ";

                $resultadoCerrador = mysqli_query( $db, $sqlCerrador );

                while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
                    echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
                }
            ?>
        ];

<?php
    } else {
?>
        var dropdownEjecutivo = [
            <?php
                $sqlCerrador = "
                    SELECT *
                    FROM ejecutivo
                    WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
                    ORDER BY nom_eje ASC
                ";

                $resultadoCerrador = mysqli_query( $db, $sqlCerrador );

                while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
                    echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
                }
            ?>
        ];

<?php
    }
?>

<script type="text/javascript">
    var hot;  // Declaración al inicio del script o función

    if (hot) {
        hot.destroy();
    }

    function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = '#E3E6E7'; // Cambia el color de fondo
    }

    hot = new Handsontable(container, {
        language: 'es-MX',
        data,
        
        height: 'auto',
        width: '100%',
        hiddenColumns: {
            columns: [18, 11, 14], // Actualizado: ID ahora es 18, FECHA LÍMITE es 11, FORMA PAGO es 14
            indicators: false // Esto oculta el indicador de columnas ocultas
        },
        stretchH: 'all',
        colHeaders: colHeaders,
        rowHeaders: true,
        fixedRowsTop: 1,
        
        cells: function deshabilitarFila(row, col) {
            var cellProperties = {};

            // Actualización de los índices para incluir las nuevas columnas y modificar el índice de NOMBRE
            if (col === 0 || col === 1 || col === 2 || col === 3 || col === 4 || col === 5 || col === 6 || col === 7 || col === 8 || col === 9 || col === 10 || col === 11 || col === 12 || col === 13 || col === 14 || col === 15 <?php echo ($rangoUsuario == 'Asesor')? ' || col === 16 ' : ''; ?>) {
                if (col === 9) { // Si es la columna NOMBRE (ahora en el índice 9)
                    cellProperties.renderer = nameColumnRenderer;
                } else {
                    cellProperties.renderer = firstColumnRenderer;
                }
            }

            return cellProperties;
        },

        manualColumnResize: true,
        minRows: 20,
        minSpareRows: 1,
        licenseKey: 'non-commercial-and-evaluation',
        afterChange: function(changes, source) {
            // console.log("Cambio detectado");
            if (source === 'loadData' || source === 'populateFromArray') {
                // Si la fuente es loadData o populateFromArray, no hacer nada
                return;
            }
            if (changes) {
                changes.forEach(([row, prop, oldValue, newValue]) => {
                    // Ignora los cambios en las columnas 0 y 2
                    // POR EJEMPLO ID Y FEC CAPTURA QUE SON DATOS FIJOS
                    // if (prop === 0 || prop === 1) {
                    //     return;
                    // }

                    if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
                        // Si es una fila nueva
                        let rowData = hot.getDataAtRow(row);
                        adicionarFila(rowData);
                    } else {
                        // Si es una fila existente
                        guardarCelda(hot, row, prop, newValue);
                        //toastr.success('Cambios guardados');
                    }
                });
            }
            //toastr.success('Cambios guardados');
            // if (changes) {
            //     changes.forEach(function([row, col, oldValue, newValue]) {
            //         console.log(`Cambio en la fila ${row}, columna ${col}. Antiguo: ${oldValue}, Nuevo: ${newValue}`);
            //         if (col === 5) { // Suponiendo que el dropdown está en la columna 5
            //             var selectedID = dropdownSource.find(item => item.label === newValue)?.value;
            //             console.log('Nuevo ID seleccionado:', selectedID);
            //         }
            //     });
            // }
        },

        filters: true,
        dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
        height: 'auto',
        columnSorting: true,

        contextMenu: {
            items: {
                "row_above": {
                    name: 'Insertar fila arriba',
                    disabled: function() {
                        return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
                    }
                },
                "row_below": {
                    name: 'Insertar fila debajo'
                },
                <?php if($rangoUsuario == 'GC' || $rangoUsuario == 'DC'){ ?>
                "custom_option": {
                    name: 'ELIMINAR REGISTRO',
                    callback: function() {
                        try {
                            var selection = this.getSelected();
                            if (selection && selection.length > 0) {
                                var selectedRow = selection[0][0];
                                var id_alu_ram = this.getDataAtCell(selectedRow, 18); // Actualizado: ID ahora es 18
                                var eliminar_alumno = true;

                                swal({
                                    title: "Continuar",
                                    text: "¿Estás seguro que deseas eliminar el registro?",
                                    icon: "warning",
                                    buttons: ["Cancelar", "Aceptar"],
                                    dangerMode: true,
                                })
                                .then((willContinue) => {
                                    if (willContinue) {
                                        swal("Continuar", "Confirmado correctamente", "success").then((confirmation) => {
                                            $.ajax({
                                                type: "POST",
                                                dataType: 'json',
                                                url: "server/controlador_alumno.php",
                                                data: { id_alu_ram, eliminar_alumno },
                                                success: function(response) {
                                                    console.log(response);
                                                    obtener_datos();
                                                },
                                            });
                                        });
                                    }
                                });
                            }
                        } catch (error) {
                            console.error("Se produjo un error:", error);
                        }
                    }
                }
                <?php } ?>
            }
        },

        columns: [
            { readOnly: true }, // FECHA
            { readOnly: true }, // CDE ORIGEN
            { readOnly: true }, // CDE DESTINO
            { readOnly: true }, // CONSULTOR
            { readOnly: true }, // EJECUTIVO
            { readOnly: true }, // TEAM LEADER (nueva)
            { readOnly: true }, // SALES MANAGER (nueva)
            { readOnly: true }, // PROGRAMA
            { readOnly: true }, // GRUPO
            { readOnly: true }, // NOMBRE
            { readOnly: true }, // TELÉFONO
            { readOnly: true }, // FECHA LÍMITE COLEG.
            { readOnly: true }, // MONTO COLEG.
            { readOnly: true }, // TIPO DE CITA
            { readOnly: true }, // FORMA. PAGO
            { readOnly: true }, // MONTO INSCRIP.
            { readOnly: true }, // ESTATUS
            { readOnly: true }, // PRESENTACION
            { readOnly: true }, // ID
        ]
    });

    obtenerConteosEstatus(hot);
    
    function obtenerConteosEstatus(hot) {
        var posiblesEstados = {
            'REGISTRO': 0,
            'REGISTRADO': 0,
            'ACTIVO': 0,
            'NP': 0,
            'BAJA': 0,
            'DESERCION': 0,
            'REINGRESO': 0,
            'FIN CURSO': 0,
            'GRADUADO': 0
        };

        // El índice de la columna ESTATUS ahora es 16 en lugar de 14
        var statusColumnData = hot.getDataAtCol(16);

        // Cuenta las ocurrencias de cada estado
        statusColumnData.forEach(function(status) {
            if (status && posiblesEstados.hasOwnProperty(status)) {
                posiblesEstados[status]++;
            }
        });

        var conteoTotal = Object.values(posiblesEstados).reduce(function(total, currentValue) {
            return total + currentValue;
        }, 0);

        // Actualiza los elementos HTML con los conteos
        $('#total_registros').text(conteoTotal);
        $('#total_registro').text(posiblesEstados['REGISTRO']);
        $('#total_registrado').text(posiblesEstados['REGISTRADO']);
        $('#total_activo').text(posiblesEstados['ACTIVO']);
        $('#total_np').text(posiblesEstados['NP']);
        $('#total_baja').text(posiblesEstados['BAJA']);
        $('#total_desercion').text(posiblesEstados['DESERCION']);
        $('#total_reingreso').text(posiblesEstados['REINGRESO']);
        $('#total_fin_curso').text(posiblesEstados['FIN CURSO']);
        $('#total_graduado').text(posiblesEstados['GRADUADO']);
    }

    container.classList.add('dark-mode');

    var exportPlugin = hot.getPlugin('exportFile');

    var button = document.querySelector('#export-file');
    
    button.addEventListener('click', () => {
        const data = [colHeaders, ...hot.getData()];
        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Hoja1');

        const fileName = 'Reporte_registros.xlsx';
        const wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' });

        function s2ab(s) {
            const buf = new ArrayBuffer(s.length);
            const view = new Uint8Array(buf);
            for (let i = 0; i < s.length; i++) {
            view[i] = s.charCodeAt(i) & 0xff;
            }
            return buf;
        }

        const blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });

        if (navigator.msSaveBlob) {
            navigator.msSaveBlob(blob, fileName);
        } else {
            const link = document.createElement('a');
            if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', fileName);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            }
        }
    });
</script>


<script type="text/javascript">
    function adicionarFila(rowData) {
        alert("Datos de la fila: " + JSON.stringify(rowData));

        // $.ajax({
        //     type: "POST",
        //     url: "adicionar_fila.php",
        //     data: { data: rowData },
        //     success: function(response) {
        //         console.log("Fila añadida con éxito!");
        //     },
        //     error: function(error) {
        //         console.log("Hubo un problema al añadir la fila.");
        //     }
        // });
    }

    // Función guardarCelda con índices actualizados
    function guardarCelda(hot, row, column, value) {
        // console.log('guardarCelda');

        // PASO 10 - Actualización de índices
        // YA CASI TERMINA LA PUTIZA :3 SE DEBE REMOVER EL INDICE DE id, nombre Y telefono SEGÚN EL CASO
        var id = hot.getDataAtCell(row, 18); // Actualizado: índice 18 para ID
        var nombre = hot.getDataAtCell(row, 9); // Actualizado: índice 9 para NOMBRE
        var telefono = hot.getDataAtCell(row, 10); // Actualizado: índice 10 para TELÉFONO

        console.log(hot);

        var id_eje = $('#selector_ejecutivo option:selected').val();

        //alert( id_eje );
        var valor = value;

        // if( column == 8 ){
        //     valor = validarNumeroTelefonico( valor );
        // }

        // if( column == 1 && value == '' ){
        //     var valor = hot.getDataAtCell(row, 16);
        // }

        // '18:00:00'

        // console.log('value: '+valor);

        // PASO 11
        // *SI SE ADICIONÓ NUEVO DROPDOWN, ES NECESARIO CAPTAR EL VALOR TAL CUAL DESCRIBE EL CÓDIGO DEBAJO

        <?php 
            if( $rangoUsuario == 'GC' || $permisos == 1 || $permisos == 2 ){
        ?>
                var id_eje_ejecutivo;
                if (column === 3) {
                    
                    var dropdownValue = hot.getDataAtCell(row, column); // Obtener el valor del dropdown
                    var selectedOption = dropdownEjecutivo.find(function(option) {
                        return option.label === dropdownValue;
                    });
                    id_eje_ejecutivo = selectedOption ? selectedOption.value : null;

                    //console.log( 'id_eje_cerrador'+id_eje_cerrador );
                    valor = id_eje_ejecutivo;

                    // console.log('atendio/cerro');
                    // console.log( 'id_eje_cerrador: '+id_eje_cerrador );
                }
        <?php
            }
        ?>

        if ( id == "" || id == null || id == undefined || id == '--' ) {
            // ALTA
            //alert("no hay datos en esta fila");

            var accion = "Alta";
            var campo = obtenerCampoValor( column );
            
            console.log('alta');
            // console.log( 'ALTA ---- campo: '+campo+' valor: '+valor );

            // PASO 13
            // *SI LA COLUMNA SERÁ MENOR QUE LA INDICE 1, DADO QUE SE MOVERÁ LA 1, HAY QUE CAMBIARLA
            if ( ( column == 1 && validarFormatoHora(value) ) || ( column != 1 ) ) {
                // VALIDADOR DADO QUE LA COLUMNA DE HORARIO REALIZA DOBLE ESCRITURA EN LA CAPTACIÓN DE HORARIO PARA CORRGIR AL FORMATO DEFINIDO Y SE GENERA DOBLE REGISTRO
                // ADD
                if(valor){

                    console.log('ajax alta');
                    if( column == 1 ){
                        var data_json = { campo, valor, accion, id_eje };
                    } else {
                        var hor_cit = hot.getDataAtCell(row, 17);
                        var data_json = { campo, valor, accion, id_eje, hor_cit };
                        console.log('hor_cit :D');
                    }
                    
                    $.ajax({
                        url: 'server/controlador_cita2.php',
                        type: 'POST',
                        data: data_json,
                        dataType: 'json',
                        success: function( data ){
                        //success

                            console.log( 'response: '+data );

                            // PASO 14
                            // // Actualización de índices por las nuevas columnas
                            hot.setDataAtCell(row, 17, data.id_cit); // Folio de la cita (ahora 17)
                            hot.setDataAtCell(row, 0, '--');
                            hot.setDataAtCell(row, 1, data.hor_cit);
                            hot.setDataAtCell(row, 2, data.cit_cit); // Fecha formateada de la cita

                            hot.setDataAtCell(row, 3, data.nom_eje_agendo); // Estado de la cita
                            hot.setDataAtCell(row, 4, data.nom_eje_cerrador); // Estado de la cita
                            hot.setDataAtCell(row, 5, data.nom_eje); // Estado de la cita

                            // Aquí irían los campos para Team Leader y Sales Manager si fueran necesarios
                            hot.setDataAtCell(row, 6, ''); // Team Leader
                            hot.setDataAtCell(row, 7, ''); // Sales Manager

                            hot.setDataAtCell(row, 8, data.tip_cit); // Fecha formateada de la cita (antes 6)
                            
                            hot.setDataAtCell(row, 9, data.nom_cit); // Nombre de la cita (antes 7)
                            hot.setDataAtCell(row, 10, data.eda_cit); // Nombre de la cita (antes 8)

                            hot.setDataAtCell(row, 11, data.tel_cit); // Teléfono de la cita (antes 9)

                            hot.setDataAtCell(row, 12, data.pro_cit); // Modalidad de interes (antes 10)
                            hot.setDataAtCell(row, 13, data.can_cit); // Mercado de citas (antes 11)
                            hot.setDataAtCell(row, 14, data.est_cit); // (antes 12)

                            hot.setDataAtCell(row, 15, data.efe_cit); // (antes 13)

                            hot.setDataAtCell(row, 16, data.obs_cit); // (antes 14)
                            
                            obtenerConteosEstatus( hot );
                            // hot.alter('insert_row', 0);

                            
                            // alert( data.sql );
                            // SELECCION DE CELDA
                            //hot.selectCell(1, 2);

                        }

                    });

                }
                //
            }

        } else {

            //UPDATE
            //console.log( ':D -- COLUMN: '+column );

            var accion = "Cambio";

            var campo = obtenerCampoValor( column );
            var id_alu_ram = id;

            // alert(valor);

            // console.log('edicion columna: '+column);
            // console.log('edicion campo: '+campo);
            // if( column != 1 && column != 13 ){
                //
                // --

                ////////// 
                // // REGISTRO
                $.ajax({
                    url: 'server/controlador_cita2.php',
                    type: 'POST',
                    data: { campo, valor, accion, id_alu_ram },
                    dataType: 'json',
                    success: function( data ){
                        console.log( 'edicion :DDDD ---->'+data );
                        
                    }
                });
                // // F NO REGISTRO
                // // --
                ////////

                
                //

            // }
            
        }

        // PASO 17
        // Actualización de los nombres de columnas según sus nuevos índices
        function obtenerCampoValor(column) {
            let columnName;
            if (column == 1) {
                columnName = "hor_cit";
            } else if (column == 2) {
                columnName = "cit_cit";
            } else if (column == 3) {
                columnName = "id_eje_ejecutivo";
            } else if (column == 4) {
                columnName = "id_eje_ejecutivo";
            } else if (column == 5) {
                columnName = "id_eje_teamleader"; // Nueva columna Team Leader
            } else if (column == 6) {
                columnName = "id_eje_salesmanager"; // Nueva columna Sales Manager
            } else if (column == 7) {
                columnName = "tip_cit";
            } else if (column == 8) {
                columnName = "nom_cit";
            } else if (column == 9) {
                columnName = "eda_cit";
            } else if (column == 10) {
                columnName = "tel_cit";
            } else if (column == 11) {
                columnName = "pro_cit";
            } else if (column == 12) {
                columnName = "can_cit";
            } else if (column == 13) {
                columnName = "est_cit";
            } else if (column == 14) {
                columnName = "efe_cit";
            } else if (column == 15) {
                columnName = "obs_cit";
            } else if (column == 16) {
                columnName = "estatus_general";
            }
            return columnName;
        }

        //toastr.success('Cambios guardados');
    }
</script>