<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_alu_ram = $_POST['id_alu_ram'];
$fechaHoy = date('Y-m-d');

// =============================================================================
// CONSULTA 1: ALUMNO (INDEPENDIENTE DE PAGOS)
// 🆕 CON TODOS LOS CAMPOS NECESARIOS PARA SWITCH_ALUMNO.PHP
// INCLUYENDO: nom_alu, app_alu, apm_alu, ent2_alu, val_cur_alu, gen_alu (GÉNERO)
// =============================================================================
$sqlAlumno = "
    SELECT 
        alumno.id_alu,
        alumno.nom_alu,
        alumno.app_alu,
        alumno.apm_alu,
        alumno.ent2_alu,
        alumno.gen_alu,
        CONCAT(alumno.nom_alu, ' ', alumno.app_alu, ' ', alumno.apm_alu) as nombre_completo,
        alumno.est_alu,
        alumno.fot_alu,
        alumno.cor1_alu,
        alumno.cor_alu,
        alumno.pas_alu,
        alumno.ing_alu,
        alumno.tel_alu,
        alumno.tel2_alu,
        alumno.cur_alu,
        alumno.val_cur_alu,
        alumno.nac_alu,
        alu_ram.mon_alu_ram,
        alu_ram.val_alu_ram,
        generacion.nom_gen,
        generacion.id_gen,
        rama.nom_ram,
        token_reciente.token
    FROM alu_ram 
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
    INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
    LEFT JOIN (
        SELECT alumno, token
        FROM alumno_token 
        WHERE alumno = (SELECT id_alu1 FROM alu_ram WHERE id_alu_ram = $id_alu_ram)
        ORDER BY id DESC 
        LIMIT 1
    ) token_reciente ON token_reciente.alumno = alumno.id_alu
    WHERE alu_ram.id_alu_ram = $id_alu_ram
";

$resultadoAlumno = mysqli_query($db, $sqlAlumno);

if (!$resultadoAlumno || mysqli_num_rows($resultadoAlumno) === 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Alumno no encontrado']);
    exit;
}

$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
$datosAlumno = [
    'id_alu' => $filaAlumno['id_alu'],
    'id_alu_ram' => $id_alu_ram,
    'est_alu' => $filaAlumno['est_alu'],
    'val_alu_ram' => $filaAlumno['val_alu_ram'],
    'token' => $filaAlumno['token'],
    'tiene_app' => !empty($filaAlumno['token']) ? 'SI' : 'NO',
    'nombre_completo' => $filaAlumno['nombre_completo'],
    'nom_alu' => $filaAlumno['nom_alu'],
    'app_alu' => $filaAlumno['app_alu'],
    'apm_alu' => $filaAlumno['apm_alu'],
    'ent2_alu' => $filaAlumno['ent2_alu'],
    'gen_alu' => $filaAlumno['gen_alu'],  // ⬅️ GÉNERO
    'cur_alu' => $filaAlumno['cur_alu'],
    'val_cur_alu' => $filaAlumno['val_cur_alu'],
    'nac_alu' => $filaAlumno['nac_alu'],
    'foto' => obtenerValidacionFotoUsuarioServer($filaAlumno['fot_alu']),
    'correo_personal' => $filaAlumno['cor1_alu'],
    'cor1_alu' => $filaAlumno['cor1_alu'],
    'cor_alu' => $filaAlumno['cor_alu'],
    'pas_alu' => $filaAlumno['pas_alu'],
    'mon_alu_ram' => $filaAlumno['mon_alu_ram'],
    'ing_alu' => $filaAlumno['ing_alu'],
    'ing_alu_formateada' => fechaFormateadaCompacta2($filaAlumno['ing_alu']),
    'nom_gen' => $filaAlumno['nom_gen'],
    'id_gen' => $filaAlumno['id_gen'],
    'nom_ram' => $filaAlumno['nom_ram'],
    'tel_alu' => $filaAlumno['tel_alu'],
    'tel2_alu' => $filaAlumno['tel2_alu']
];

// =============================================================================
// CONSULTA 2: PAGOS (SEPARADA E INDEPENDIENTE)
// =============================================================================
$sqlPagos = "
    SELECT 
        pago.id_pag,
        pago.con_pag,
        pago.mon_pag,
        pago.mon_ori_pag,
        pago.ini_pag,
        pago.fin_pag,
        pago.pag_pag,
        pago.est_pag,
        pago.tip_pag,
        pago.obs_pag
    FROM pago 
    WHERE id_alu_ram10 = $id_alu_ram
    ORDER BY 
        CASE 
            WHEN est_pag = 'Pendiente' AND '$fechaHoy' > fin_pag THEN 1
            WHEN est_pag = 'Pendiente' THEN 2
            WHEN est_pag = 'Pagado' THEN 3
        END ASC,
        ini_pag DESC
";

$sqlPagos = str_replace('$fechaHoy', $fechaHoy, $sqlPagos);
$resultadoPagos = mysqli_query($db, $sqlPagos);

// =============================================================================
// PROCESAR PAGOS (SI EXISTEN) + CALCULAR TOTALES VENCIDOS
// =============================================================================
$pagos = [];
$totalPagos = 0;
$primerPago = null;
$totalCobradoVencido = 0;
$totalAdeudoVencido = 0;

if ($resultadoPagos && mysqli_num_rows($resultadoPagos) > 0) {
    $contador = 1;
    
    while($filaPagos = mysqli_fetch_assoc($resultadoPagos)) {
        if($filaPagos['est_pag'] !== 'Pendiente' && $filaPagos['est_pag'] !== 'Pagado') {
            continue;
        }
        
        $esVencido = ($fechaHoy > $filaPagos['fin_pag']);
        $estadoParaMostrar = $filaPagos['est_pag'];
        if ($filaPagos['est_pag'] === 'Pendiente' && $esVencido) {
            $estadoParaMostrar = 'Vencido';
        }
        
        // CALCULAR TOTALES DE VENCIDOS
        if ($esVencido) {
            if ($filaPagos['est_pag'] === 'Pagado') {
                $totalCobradoVencido += floatval($filaPagos['mon_ori_pag']);
            } else {
                $totalAdeudoVencido += floatval($filaPagos['mon_pag']);
            }
        }
        
        $fechaPagoFormateada = null;
        if ($filaPagos['pag_pag'] && $filaPagos['pag_pag'] !== '0000-00-00') {
            $fechaPagoFormateada = fechaFormateadaCompacta2($filaPagos['pag_pag']);
        }
        
        // =============================================================================
        // SUBCONSULTA: OBTENER ABONOS DE ESTE PAGO
        // =============================================================================
        $abonos = [];
        $sqlAbonos = "
            SELECT 
                id_abo_pag,
                fec_abo_pag,
                mon_abo_pag,
                tip_abo_pag,
                res_abo_pag
            FROM abono_pago
            WHERE id_pag1 = {$filaPagos['id_pag']}
            ORDER BY fec_abo_pag ASC
        ";
        
        $resultadoAbonos = mysqli_query($db, $sqlAbonos);
        
        if ($resultadoAbonos && mysqli_num_rows($resultadoAbonos) > 0) {
            while($filaAbono = mysqli_fetch_assoc($resultadoAbonos)) {
                $abonos[] = [
                    'id_abo_pag' => $filaAbono['id_abo_pag'],
                    'fec_abo_pag' => $filaAbono['fec_abo_pag'],
                    'fec_abo_pag_formateada' => fechaFormateadaCompacta2($filaAbono['fec_abo_pag']),
                    'mon_abo_pag' => $filaAbono['mon_abo_pag'],
                    'tip_abo_pag' => $filaAbono['tip_abo_pag'],
                    'res_abo_pag' => $filaAbono['res_abo_pag']
                ];
            }
        }
        
        $pago = [
            'contador' => $contador,
            'id_pag' => $filaPagos['id_pag'],
            'con_pag' => $filaPagos['con_pag'],
            'mon_pag' => $filaPagos['mon_pag'],
            'mon_ori_pag' => $filaPagos['mon_ori_pag'],
            'ini_pag' => $filaPagos['ini_pag'],
            'ini_pag_formateada' => fechaFormateadaCompacta2($filaPagos['ini_pag']),
            'fin_pag' => $filaPagos['fin_pag'],
            'fin_pag_formateada' => fechaFormateadaCompacta2($filaPagos['fin_pag']),
            'pag_pag' => $filaPagos['pag_pag'],
            'pag_pag_formateada' => $fechaPagoFormateada,
            'est_pag' => $estadoParaMostrar,
            'est_pag_bd' => $filaPagos['est_pag'],
            'tip_pag' => $filaPagos['tip_pag'],
            'obs_pag' => $filaPagos['obs_pag'],
            'vencido' => $esVencido,
            'seleccionable' => ($filaPagos['est_pag'] === 'Pendiente'),
            'tiene_abonos' => (count($abonos) > 0),
            'abonos' => $abonos
        ];
        
        if($contador === 1 && $pago['seleccionable']) {
            $primerPago = $pago;
        }
        
        $pagos[] = $pago;
        $contador++;
    }
    $totalPagos = count($pagos);
}

// AGREGAR TOTALES AL ARRAY DE ALUMNO
$datosAlumno['total_cobrado_vencido'] = $totalCobradoVencido;
$datosAlumno['total_adeudo_vencido'] = $totalAdeudoVencido;

// =============================================================================
// CONSULTA 3: MOVIMIENTOS (TABLA PLANA DE ABONOS)
// DATA-FIRST: Todos los datos necesarios en una sola consulta
// =============================================================================
$sqlMovimientos = "
    SELECT 
        ap.id_abo_pag,
        ap.fec_abo_pag,
        ap.hor_abo_pag,
        ap.mon_abo_pag,
        ap.tip_abo_pag,
        ap.res_abo_pag,
        p.id_pag,
        p.con_pag,
        p.fin_pag,
        CASE 
            WHEN ap.fec_abo_pag < p.fin_pag THEN 'Adelantada'
            ELSE 'Normal'
        END as movimiento
    FROM abono_pago ap
    INNER JOIN pago p ON ap.id_pag1 = p.id_pag
    WHERE p.id_alu_ram10 = $id_alu_ram
    ORDER BY ap.fec_abo_pag DESC, ap.hor_abo_pag DESC
";

$resultadoMovimientos = mysqli_query($db, $sqlMovimientos);

$movimientos = [];
if ($resultadoMovimientos && mysqli_num_rows($resultadoMovimientos) > 0) {
    while($filaMov = mysqli_fetch_assoc($resultadoMovimientos)) {
        $movimientos[] = [
            // Columnas de la tabla en orden
            'folio' => $filaMov['id_pag'],
            'movimiento' => $filaMov['movimiento'],
            'descripcion' => $filaMov['con_pag'],
            'importe' => $filaMov['mon_abo_pag'],
            'fecha' => $filaMov['fec_abo_pag'],
            'fecha_formateada' => fechaFormateadaCompacta2($filaMov['fec_abo_pag']),
            'hora' => $filaMov['hor_abo_pag'],
            'atendio' => $filaMov['res_abo_pag'] ?: 'N/A',
            'mov' => $filaMov['id_abo_pag'],
            
            // Datos extra para lógica
            'tipo_pago' => $filaMov['tip_abo_pag'],
            'fin_pag' => $filaMov['fin_pag']
        ];
    }
}

// AGREGAR MOVIMIENTOS AL ARRAY DE ALUMNO
$datosAlumno['movimientos'] = $movimientos;
$datosAlumno['total_movimientos'] = count($movimientos);

// =============================================================================
// RESPUESTA FINAL - DATA-FIRST
// =============================================================================
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'alumno' => $datosAlumno,
    'totalPagos' => $totalPagos,
    'primerPago' => $primerPago,
    'pagos' => $pagos
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>