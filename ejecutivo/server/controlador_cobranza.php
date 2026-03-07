<?php
// CONTROLADOR DE REPORTERIA COBRANZA
require('../inc/cabeceras.php');
require('../inc/funciones.php');

// OBTENER FECHAS PARA FILTRO
$fecha_inicio = '';
$fecha_fin = '';

if(isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) 
   && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
    $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio']);
    $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin']);
}

// DETECTAR TIPO DE BUSQUEDA
$esBusquedaNormal = false;
$datosCobranza = '';

if(isset($_POST['datosCobranza'])) {
    $datosCobranza = trim(preg_replace('!\s+!', ' ', $_POST['datosCobranza']));
    $esBusquedaNormal = true;
}

// MANEJO DE PLANTELES
$plantelesCondicion = "";
$plantelesArray = array();

if(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
    $plantelesArray = $_POST['planteles_ajax'];
}

if(!empty($plantelesArray)) {
    $plantelesLimpios = array();
    foreach($plantelesArray as $p) {
        $plantelesLimpios[] = intval($p);
    }
    $plantelesStr = implode(',', $plantelesLimpios);
    $plantelesCondicion = " AND alumno.id_pla8 IN ($plantelesStr)";
}

// MANEJO DE BOLSA DE PAGO (MULTISELECCION - CHECKBOXES)
$bolsaCondicion = "";
$bolsaArray = array();

if(isset($_POST['bolsa_ajax']) && !empty($_POST['bolsa_ajax'])) {
    $bolsaArray = $_POST['bolsa_ajax'];
}

if(!empty($bolsaArray)) {
    $condicionesBolsa = array();
    
    foreach($bolsaArray as $bolsa) {
        $bolsaLimpia = mysqli_real_escape_string($db, $bolsa);
        
        switch($bolsaLimpia) {
            case 'Colegiatura':
                $condicionesBolsa[] = "pago.tip_pag = 'Colegiatura'";
                break;
            case 'Inscripcion':
                $condicionesBolsa[] = "pago.tip_pag = 'Inscripción'";
                break;
            case 'Reinscripcion':
                $condicionesBolsa[] = "pago.tip_pag = 'Reinscripción'";
                break;
            case 'Tramite':
            case 'Otros':
                $condicionesBolsa[] = "pago.tip_pag = 'Otros'";
                break;
            case 'Varios':
                $condicionesBolsa[] = "pago.tip_pag = 'Varios'";
                break;
        }
    }
    
    if(!empty($condicionesBolsa)) {
        $bolsaCondicion = " AND (" . implode(' OR ', $condicionesBolsa) . ")";
    }
}

// MANEJO DE FORMAS DE PAGO (MULTISELECCION)
$formasCondicion = "";
$formasArray = array();

if(isset($_POST['formas_ajax']) && !empty($_POST['formas_ajax'])) {
    $formasArray = $_POST['formas_ajax'];
}

if(!empty($formasArray)) {
    $condicionesFormas = array();
    
    foreach($formasArray as $forma) {
        $formaLimpia = mysqli_real_escape_string($db, $forma);
        $condicionesFormas[] = "OBTENER_TIPO_ABONO(pago.id_pag) = '$formaLimpia'";
    }
    
    if(!empty($condicionesFormas)) {
        $formasCondicion = " AND (" . implode(' OR ', $condicionesFormas) . ")";
    }
}

// MANEJO DE FECHAS
$fechasCondicion = "";

if(!empty($fecha_inicio) && !empty($fecha_fin)) {
    $fechasCondicion = " AND abono_pago.fec_abo_pag BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

// CONSTRUIR CONSULTA BASE
$sql = "
   SELECT 
   pago.id_pag AS fol_pag,
   obtener_plantel_ejecutivo( vista_alumnos.id_eje3 ) AS nom_pla_eje,
   vista_alumnos.nom_eje AS nom_eje,
   pago.est_pag AS est_pag,
   pago.con_pag AS con_pag,
   abono_pago.mon_abo_pag AS mon_abo_pag,
   OBTENER_TIPO_ABONO(pago.id_pag) AS tip_abo_pag,
   pago.ini_pag AS ini_pag,
   pago.fin_pag AS fin_pag,
   pago.obs_pag AS obs_pag,
   pago.tip_pag AS tip_pag,
   pago.fac_pag AS fac_pag,
   pago.mon_ori_pag AS mon_ori_pag,
   pago.mon_pag AS mon_pag,
   pago.id_pag AS id_pag,
   pago.fec_pag AS fec_pag,
   abono_pago.res_abo_pag AS res_abo_pag,
   abono_pago.fec_abo_pag AS fec_abo_pag,
   abono_pago.hor_abo_pag AS hor_abo_pag,
   pago.id_alu_ram10 AS id_alu_ram10,
   pago.id_gen_pag2 AS id_gen_pag2,
   alu_ram.id_ram3 AS id_ram3,
   rama.nom_ram AS nom_ram,
   CONCAT_WS(' ',
           alumno.nom_alu,
           alumno.app_alu,
           alumno.apm_alu) AS nom_alu,
   alumno.id_pla8 AS id_pla8,
   generacion.nom_gen AS nom_gen,
   generacion.id_gen AS id_gen,
   plantel.nom_pla AS nom_pla
   FROM alumno
   JOIN alu_ram ON alumno.id_alu = alu_ram.id_alu1
   JOIN vista_alumnos ON vista_alumnos.id_alu1 = alumno.id_alu
   JOIN pago ON alu_ram.id_alu_ram = pago.id_alu_ram10
   JOIN abono_pago ON pago.id_pag = abono_pago.id_pag1
   JOIN generacion ON alu_ram.id_gen1 = generacion.id_gen
   JOIN rama ON alu_ram.id_ram3 = rama.id_ram
   JOIN plantel ON alumno.id_pla8 = plantel.id_pla
   WHERE 1=1
";

// AGREGAR CONDICIONES
$sql .= $plantelesCondicion;
$sql .= $bolsaCondicion;
$sql .= $formasCondicion;
$sql .= $fechasCondicion;

// AGREGAR BUSQUEDA POR TEXTO
if($esBusquedaNormal && !empty($datosCobranza)) {
    $busqueda = mysqli_real_escape_string($db, $datosCobranza);
    $sql .= " AND (
        pago.id_pag LIKE '%$busqueda%' OR
        pago.con_pag LIKE '%$busqueda%' OR
        pago.id_alu_ram10 LIKE '%$busqueda%' OR
        CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu) LIKE '%$busqueda%' OR
        abono_pago.res_abo_pag LIKE '%$busqueda%' OR
        generacion.nom_gen LIKE '%$busqueda%'
    )";
}

// AGRUPAR Y ORDENAR
$sql .= ' ORDER BY abono_pago.fec_abo_pag DESC, abono_pago.hor_abo_pag DESC';
// $sql .= ' GROUP BY pago.id_pag ORDER BY pago.id_pag DESC';

// EJECUTAR CONSULTA
$resultado = mysqli_query($db, $sql);

if(!$resultado) {
    $error = array(
        'error' => true,
        'mensaje' => mysqli_error($db),
        'sql' => $sql
    );
    echo json_encode($error);
    exit;
}

// PROCESAR RESULTADOS CON VALIDACION DE RESPONSABLE
$cobranza = array();
while($fila = mysqli_fetch_assoc($resultado)) {
    // Procesar tipo de pago
    $tip_pag_procesado = $fila['tip_pag'];
    if($fila['tip_pag'] == 'Otros'){
        $tip_pag_procesado = 'Tramite';
    }
    
    // VALIDAR Y PROCESAR res_abo_pag
    $responsable_procesado = $fila['res_abo_pag'];
    
    if (empty($responsable_procesado) || 
        $responsable_procesado === '0' || 
        $responsable_procesado === 0 || 
        trim($responsable_procesado) === '' ||
        strtolower(trim($responsable_procesado)) === 'null') {
        $responsable_procesado = 'APP-STRIPE';
    }
    
    $cobranza[] = array(
        "FOLIO" => $fila['fol_pag'],                                               // 0
        "ESTATUS" => $fila['est_pag'],                                            // 1
        "CONCEPTO" => $fila['con_pag'],                                           // 2
        "TIPO" => $tip_pag_procesado,                                             // 3
        "FECHA_MOVIMIENTO" => fechaFormateadaCompacta2($fila['fec_abo_pag']),    // 4
        "HORA" => substr($fila['hor_abo_pag'], 0, 5),                            // 5 - NUEVA (solo HH:MM)
        "RESPONSABLE" => $responsable_procesado,                                  // 6 - OCULTA (antes 5)
        "ADEUDO" => formatearDinero($fila['mon_pag']),                           // 7 (antes 6)
        "COBRADO" => formatearDinero($fila['mon_abo_pag']),                      // 8 (antes 7)
        "FORMA_PAGO" => $fila['tip_abo_pag'],                                    // 9 (antes 9)
        "MATRICULA" => $fila['id_alu_ram10'],                                    // 10 (antes 10)
        "ALUMNO" => $fila['nom_alu'],                                            // 11 (antes 11)
        "GPO" => $fila['nom_gen'],                                               // 12 (antes 12)
        "ID_GEN" => $fila['id_gen'],                                             // 13 - OCULTA (antes 13)
        "PROGRAMA" => $fila['nom_ram'],                                          // 14 (antes 14)
        "CDE" => $fila['nom_pla'],                                               // 15 (antes 15)
        "CDE_ORIGEN" => $fila['nom_pla'],                                        // 16 (antes 16)
        "CONSULTOR" => $fila['nom_eje'],                                         // 17 (antes 17)
        "ATENDIO" => $responsable_procesado,                                      // 18 (antes 18)
        "VENCIMIENTO" => fechaFormateadaCompacta2($fila['fin_pag'])              // 19 - MOVIDA AL FINAL (antes 8)
    );
}

echo json_encode($cobranza);
exit;
?>