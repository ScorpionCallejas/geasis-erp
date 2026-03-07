<?php
/**
 * HISTORIAL DE PAGOS POR MESES - PDF LANDSCAPE
 * ejecutivo/historial_meses_pdf.php
 * Vista tipo calendario horizontal agrupada por tipo de pago
 * VERSIÓN 2.0 - SIMETRÍA PERFECTA - SIN CONTADOR
 * 24/Nov/2025
 */

ob_start();

require('inc/cabeceras.php');
require('inc/funciones.php');
include "../fpdf/fpdf.php";

// ==================== VALIDACIÓN ====================
if(!isset($_GET['id_alu_ram']) || empty($_GET['id_alu_ram'])) {
    ob_clean();
    die('ERROR: NO SE PROPORCIONO ID DE ALUMNO');
}

$id_alu_ram = intval($_GET['id_alu_ram']);

// ==================== CONSULTA ALUMNO ====================
$sqlAlumno = "
    SELECT 
        alumno.id_alu,
        alumno.nom_alu,
        alumno.app_alu,
        alumno.apm_alu,
        CONCAT(alumno.nom_alu, ' ', alumno.app_alu, ' ', alumno.apm_alu) as nombre_completo,
        alumno.fot_alu,
        alumno.tel_alu,
        alumno.tel2_alu,
        alumno.ing_alu,
        alumno.cor1_alu,
        alumno.cor_alu,
        alumno.dir_alu,
        alumno.cp_alu,
        alumno.cur_alu,
        alumno.nac_alu,
        alumno.gen_alu,
        generacion.nom_gen,
        generacion.ini_gen,
        generacion.fin_gen,
        rama.nom_ram,
        rama.id_ram,
        plantel.nom_pla,
        plantel.fot_pla,
        plantel.dir_pla,
        plantel.tel_pla,
        alu_ram.id_alu_ram,
        OBTENER_ESTATUS_GENERAL(alu_ram.id_alu_ram, generacion.fin_gen, alu_ram.est1_alu_ram) AS estatus_general
    FROM alu_ram 
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
    INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
    INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
    WHERE alu_ram.id_alu_ram = $id_alu_ram
";

$resultadoAlumno = mysqli_query($db, $sqlAlumno);

if (!$resultadoAlumno) {
    ob_clean();
    die('ERROR CONSULTA ALUMNO: ' . mysqli_error($db));
}

if (mysqli_num_rows($resultadoAlumno) === 0) {
    ob_clean();
    die('ALUMNO NO ENCONTRADO');
}

$alumno = mysqli_fetch_assoc($resultadoAlumno);

// ==================== DETERMINAR SI ES PROGRAMA ESPECIAL ====================
$programas_especiales = [364, 363, 361, 360, 359, 357];
$es_programa_especial = in_array($alumno['id_ram'], $programas_especiales);

// ==================== CONSULTA PAGOS ====================
$sqlPagos = "
    SELECT 
        pago.*
    FROM pago 
    WHERE pago.id_alu_ram10 = $id_alu_ram
    ORDER BY pago.ini_pag ASC
";

$resultadoPagos = mysqli_query($db, $sqlPagos);

if (!$resultadoPagos) {
    ob_clean();
    die('ERROR CONSULTA PAGOS: ' . mysqli_error($db));
}

// ==================== PROCESAR Y AGRUPAR PAGOS ====================
$pagos = array();
$fechaHoy = date('Y-m-d');

while($pago = mysqli_fetch_assoc($resultadoPagos)) {
    $pagos[] = $pago;
}

// Configuración
$MESES_NOMBRES = array('ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
$ORDEN_TIPOS = array('Colegiatura', 'Otros', 'Reinscripción', 'Inscripción', 'Varios');
$LABELS_TIPOS = array(
    'Colegiatura' => 'COLEGIATURA',
    'Otros' => 'TRAMITES',
    'Reinscripción' => 'REINSCRIPCION',
    'Inscripción' => 'INSCRIPCION',
    'Varios' => 'VARIOS'
);

// Obtener rango de años
$anos = array();
foreach($pagos as $pago) {
    $fecha = new DateTime($pago['ini_pag']);
    $ano = intval($fecha->format('Y'));
    if(!in_array($ano, $anos) && $ano > 2000) {
        $anos[] = $ano;
    }
}
sort($anos);

if(count($anos) === 0) {
    $anos = array(date('Y'));
}

// Crear estructura de datos
$estructura = array();
foreach($ORDEN_TIPOS as $tipo) {
    $estructura[$tipo] = array();
    foreach($anos as $ano) {
        $estructura[$tipo][$ano] = array();
        for($mes = 0; $mes < 12; $mes++) {
            $estructura[$tipo][$ano][$mes] = array();
        }
    }
}

// Poblar estructura con pagos
foreach($pagos as $pago) {
    $tipo = $pago['tip_pag'];
    if(!in_array($tipo, $ORDEN_TIPOS)) continue;
    
    $fecha = new DateTime($pago['ini_pag']);
    $ano = intval($fecha->format('Y'));
    $mes = intval($fecha->format('n')) - 1; // 0-11
    
    if(isset($estructura[$tipo][$ano][$mes])) {
        $estructura[$tipo][$ano][$mes][] = $pago;
    }
}

// ==================== CALCULAR TOTALES ====================
$totales = array(
    'general' => 0,
    'pagado' => 0,
    'pendiente' => 0,
    'colegiaturas' => 0,
    'tramites' => 0,
    'cantidad_colegiaturas' => 0
);

foreach($anos as $ano) {
    foreach($ORDEN_TIPOS as $tipo) {
        for($mes = 0; $mes < 12; $mes++) {
            $pagosEnMes = $estructura[$tipo][$ano][$mes];
            
            if($tipo === 'Colegiatura' && count($pagosEnMes) > 0) {
                $totales['cantidad_colegiaturas']++;
            }
            
            foreach($pagosEnMes as $pago) {
                $monto = floatval($pago['mon_ori_pag'] ?: $pago['mon_pag']);
                $totales['general'] += $monto;
                
                if($pago['est_pag'] === 'Pagado') {
                    $totales['pagado'] += $monto;
                } else {
                    $totales['pendiente'] += $monto;
                }
                
                if($tipo === 'Colegiatura') $totales['colegiaturas'] += $monto;
                if($tipo === 'Otros') $totales['tramites'] += $monto;
            }
        }
    }
}

// ==================== FUNCIONES AUXILIARES ====================
function limpiarTexto($texto) {
    return utf8_decode($texto);
}

function formatearMontoPDF($monto) {
    $numero = floatval($monto);
    if($numero == 0) return '-';
    return '$' . number_format($numero, 0);
}

// ==================== CLASE PDF PERSONALIZADA ====================
class PDF extends FPDF {
    private $es_especial;
    
    function setEsEspecial($valor) {
        $this->es_especial = $valor;
    }
    
    function Footer() {
        $this->SetY(-12);
        
        // Mensaje ecológico
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(34, 139, 34);
        $mensaje = 'Cuida el medio ambiente consultando tu estado de cuenta en la APP o plataforma web.';
        $this->Cell(0, 3, utf8_decode($mensaje), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 5);
        $this->SetTextColor(120);
        $this->Cell(90, 3, utf8_decode('Pág. ') . $this->PageNo(), 0, 0, 'L');
        $this->Cell(80, 3, strtoupper(date('d/M/Y H:i')), 0, 0, 'C');
        
        if($this->es_especial) {
            $this->SetTextColor(0, 164, 176);
        } else {
            $this->SetTextColor(0, 100, 200);
        }
        $this->Cell(90, 3, 'https://ahjende.com', 0, 0, 'R');
        $this->SetTextColor(0);
    }
}

// ==================== CONSTANTES DE LAYOUT ====================
$MARGEN = 10;
$ANCHO_PAGINA = 279.4; // Letter landscape
$ANCHO_UTIL = $ANCHO_PAGINA - ($MARGEN * 2); // 259.4mm

// ==================== CREAR PDF ====================
$pdf = new PDF('L', 'mm', 'Letter'); // LANDSCAPE
$pdf->setEsEspecial($es_programa_especial);
$pdf->AddPage();
$pdf->SetMargins($MARGEN, $MARGEN, $MARGEN);
$pdf->SetAutoPageBreak(true, 15);

// Definir colores según programa
if ($es_programa_especial) {
    $fillR = 0; $fillG = 164; $fillB = 176;
    $borderR = 0; $borderG = 134; $borderB = 146;
    $lightFillR = 230; $lightFillG = 245; $lightFillB = 248;
    $logo_a_usar = "../img/logo-competencias.png";
    $titulo_escuela = 'COMPETENCIAS EDUCATIVAS Y PROFESIONALES';
} else {
    $fillR = 41; $fillG = 128; $fillB = 185;
    $borderR = 30; $borderG = 100; $borderB = 160;
    $lightFillR = 230; $lightFillG = 240; $lightFillB = 250;
    $logo_a_usar = "../img/logoColor.png";
    $titulo_escuela = 'ESCUELA DE NEGOCIOS Y DESARROLLO EMPRESARIAL';
}

// ==================== ENCABEZADO PRINCIPAL ====================
$pdf->SetFillColor($fillR, $fillG, $fillB);
$pdf->SetDrawColor($borderR, $borderG, $borderB);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($ANCHO_UTIL, 8, 'ESTADO DE CUENTA POR MESES', 1, 1, 'C', 1);
$pdf->SetTextColor(0);

// ==================== FILA: ESCUELA + PLANTEL + LOGO ====================
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($ANCHO_UTIL - 45, 5, limpiarTexto(strtoupper($titulo_escuela)), 0, 0, 'L');

// Logo en la esquina derecha (sin superponerse)
if(file_exists($logo_a_usar)) {
    $pdf->Image($logo_a_usar, $ANCHO_PAGINA - $MARGEN - 32, $pdf->GetY() - 6, 30);
}
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 7);
$pdf->SetTextColor(80);
$pdf->Cell($ANCHO_UTIL - 45, 4, limpiarTexto('PLANTEL: ' . strtoupper($alumno['nom_pla'])), 0, 1, 'L');
$pdf->SetTextColor(0);

$pdf->Ln(1);

// ==================== INFORMACIÓN DEL ALUMNO ====================
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($lightFillR, $lightFillG, $lightFillB);
$pdf->SetDrawColor($borderR, $borderG, $borderB);
$pdf->Cell($ANCHO_UTIL, 4, limpiarTexto('INFORMACIÓN DEL ALUMNO'), 1, 1, 'L', 1);

// Calcular anchos proporcionales
$col1 = 18; // Label
$col2 = 55; // Valor
$col3 = 18; // Label
$col4 = 28; // Valor
$col5 = 14; // Label
$col6 = 38; // Valor
$col7 = 16; // Label
$col8 = 22; // Valor
$col9 = 16; // Label
$col10 = 34.4; // Valor (ajuste para completar ANCHO_UTIL)

// Fila 1: NOMBRE | MATRÍCULA | GRUPO | ESTATUS | INGRESO
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col1, 4, 'NOMBRE:', 'LTB', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0);
$pdf->Cell($col2, 4, limpiarTexto(strtoupper($alumno['nombre_completo'])), 'TB', 0, 'L');

$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col3, 4, limpiarTexto('MATRÍCULA:'), 'TB', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0);
$pdf->Cell($col4, 4, $alumno['id_alu_ram'], 'TB', 0, 'L');

$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col5, 4, 'GRUPO:', 'TB', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0);
$pdf->Cell($col6, 4, limpiarTexto(strtoupper(substr($alumno['nom_gen'], 0, 20))), 'TB', 0, 'L');

$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col7, 4, 'ESTATUS:', 'TB', 0, 'L');

// Color según estatus
$estatusUpper = strtoupper($alumno['estatus_general']);
if(in_array($estatusUpper, array('REINGRESO', 'ACTIVO'))) {
    $pdf->SetTextColor(40, 167, 69);
} elseif(in_array($estatusUpper, array('BAJA', 'DESERCION', 'NP'))) {
    $pdf->SetTextColor(220, 53, 69);
} else {
    $pdf->SetTextColor(0);
}
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell($col8, 4, limpiarTexto($estatusUpper), 'TB', 0, 'L');

$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col9, 4, 'INGRESO:', 'TB', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0);
$pdf->Cell($col10, 4, strtoupper(fechaFormateadaCompacta2($alumno['ing_alu'])), 'RTB', 1, 'L');

// Fila 2: PROGRAMA | PERÍODO
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col1, 4, 'PROGRAMA:', 'LB', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0);
$anchoPrograma = $col2 + $col3 + $col4 + $col5 + $col6;
$pdf->Cell($anchoPrograma, 4, limpiarTexto(strtoupper(substr($alumno['nom_ram'], 0, 75))), 'B', 0, 'L');

$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);
$pdf->Cell($col7, 4, limpiarTexto('PERÍODO:'), 'B', 0, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0);
$periodoTexto = strtoupper(fechaFormateadaCompacta2($alumno['ini_gen']) . ' - ' . fechaFormateadaCompacta2($alumno['fin_gen']));
$anchoPeriodo = $col8 + $col9 + $col10;
$pdf->Cell($anchoPeriodo, 4, $periodoTexto, 'RB', 1, 'L');

$pdf->Ln(2);

// ==================== DASHBOARD DE TOTALES ====================
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(30, 30, 30);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(20, 20, 20);

// 6 columnas iguales
$anchoDash = $ANCHO_UTIL / 6;

// Headers del dashboard
$pdf->Cell($anchoDash, 4, 'TOTAL GENERAL', 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 4, 'PAGADO', 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 4, 'PENDIENTE', 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 4, 'COLEGIATURAS', 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 4, '# COLEGIATURAS', 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 4, 'TRAMITES', 1, 1, 'C', 1);

// Valores del dashboard
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(45, 45, 45);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($anchoDash, 6, '$' . number_format($totales['general'], 0), 1, 0, 'C', 1);

$pdf->SetTextColor(100, 220, 100);
$pdf->Cell($anchoDash, 6, '$' . number_format($totales['pagado'], 0), 1, 0, 'C', 1);

$pdf->SetTextColor(255, 100, 100);
$pdf->Cell($anchoDash, 6, '$' . number_format($totales['pendiente'], 0), 1, 0, 'C', 1);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($anchoDash, 6, '$' . number_format($totales['colegiaturas'], 0), 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 6, $totales['cantidad_colegiaturas'], 1, 0, 'C', 1);
$pdf->Cell($anchoDash, 6, '$' . number_format($totales['tramites'], 0), 1, 1, 'C', 1);

$pdf->SetTextColor(0);
$pdf->Ln(3);

// ==================== TABLA DE MESES POR AÑO ====================
// Calcular anchos de columnas para que sumen ANCHO_UTIL
$anchoTipo = 28;
$anchoTotal = 22;
$anchoMesesTotal = $ANCHO_UTIL - $anchoTipo - $anchoTotal; // Lo que queda para 12 meses
$anchoMes = $anchoMesesTotal / 12;

foreach($anos as $ano) {
    // Verificar si hay pagos en este año
    $hayPagosEnAno = false;
    foreach($ORDEN_TIPOS as $tipo) {
        for($mes = 0; $mes < 12; $mes++) {
            if(count($estructura[$tipo][$ano][$mes]) > 0) {
                $hayPagosEnAno = true;
                break 2;
            }
        }
    }
    
    if(!$hayPagosEnAno) continue;
    
    // Verificar espacio en página
    if($pdf->GetY() > 155) {
        $pdf->AddPage();
    }
    
    // Header del año
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetFillColor(50, 50, 50);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetDrawColor(30, 30, 30);
    
    $pdf->Cell($anchoTipo, 5, $ano, 1, 0, 'C', 1);
    foreach($MESES_NOMBRES as $mesNombre) {
        $pdf->Cell($anchoMes, 5, $mesNombre, 1, 0, 'C', 1);
    }
    $pdf->Cell($anchoTotal, 5, 'TOTAL', 1, 1, 'C', 1);
    
    $pdf->SetTextColor(0);
    
    // Filas por tipo de pago
    foreach($ORDEN_TIPOS as $tipo) {
        // Verificar si hay pagos de este tipo en este año
        $hayPagosTipo = false;
        $totalFilaTipo = 0;
        
        for($mes = 0; $mes < 12; $mes++) {
            if(count($estructura[$tipo][$ano][$mes]) > 0) {
                $hayPagosTipo = true;
                foreach($estructura[$tipo][$ano][$mes] as $pago) {
                    $totalFilaTipo += floatval($pago['mon_ori_pag'] ?: $pago['mon_pag']);
                }
            }
        }
        
        if(!$hayPagosTipo) continue;
        
        // Nombre del tipo
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->SetFillColor(245, 245, 248);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Cell($anchoTipo, 5, $LABELS_TIPOS[$tipo], 1, 0, 'L', 1);
        
        // Celdas por mes - SIN CONTADOR
        $pdf->SetFont('Arial', '', 6);
        
        for($mes = 0; $mes < 12; $mes++) {
            $pagosEnMes = $estructura[$tipo][$ano][$mes];
            
            if(count($pagosEnMes) === 0) {
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetTextColor(180, 180, 180);
                $pdf->Cell($anchoMes, 5, '-', 1, 0, 'C', 1);
                $pdf->SetTextColor(0);
            } else {
                // Calcular total del mes
                $totalMes = 0;
                $todosPagados = true;
                $algunVencido = false;
                
                foreach($pagosEnMes as $pago) {
                    $totalMes += floatval($pago['mon_ori_pag'] ?: $pago['mon_pag']);
                    if($pago['est_pag'] !== 'Pagado') $todosPagados = false;
                    if($pago['est_pag'] === 'Pendiente' && $pago['fin_pag'] < $fechaHoy) $algunVencido = true;
                }
                
                // Color según estado
                if($todosPagados) {
                    $pdf->SetFillColor(212, 237, 218); // Verde claro
                    $pdf->SetTextColor(21, 87, 36);
                } elseif($algunVencido) {
                    $pdf->SetFillColor(248, 215, 218); // Rojo claro
                    $pdf->SetTextColor(114, 28, 36);
                } else {
                    $pdf->SetFillColor(255, 255, 255); // Blanco
                    $pdf->SetTextColor(0, 0, 0);
                }
                
                // MONTO SIN CONTADOR
                $pdf->Cell($anchoMes, 5, formatearMontoPDF($totalMes), 1, 0, 'C', 1);
                $pdf->SetTextColor(0);
            }
        }
        
        // Total de la fila
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->SetFillColor(235, 235, 240);
        $pdf->SetTextColor(0);
        $pdf->Cell($anchoTotal, 5, '$' . number_format($totalFilaTipo, 0), 1, 1, 'R', 1);
    }
    
    // Fila de totales del año
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetFillColor(220, 220, 228);
    $pdf->SetDrawColor(180, 180, 180);
    
    $pdf->Cell($anchoTipo, 5, 'TOTAL ' . $ano, 1, 0, 'R', 1);
    
    $totalAno = 0;
    for($mes = 0; $mes < 12; $mes++) {
        $totalMesGeneral = 0;
        foreach($ORDEN_TIPOS as $tipo) {
            foreach($estructura[$tipo][$ano][$mes] as $pago) {
                $totalMesGeneral += floatval($pago['mon_ori_pag'] ?: $pago['mon_pag']);
            }
        }
        $totalAno += $totalMesGeneral;
        
        if($totalMesGeneral > 0) {
            $pdf->Cell($anchoMes, 5, formatearMontoPDF($totalMesGeneral), 1, 0, 'C', 1);
        } else {
            $pdf->SetTextColor(180, 180, 180);
            $pdf->Cell($anchoMes, 5, '-', 1, 0, 'C', 1);
            $pdf->SetTextColor(0);
        }
    }
    
    $pdf->SetFillColor(200, 200, 210);
    $pdf->Cell($anchoTotal, 5, '$' . number_format($totalAno, 0), 1, 1, 'R', 1);
    
    $pdf->Ln(3);
}

// ==================== RESUMEN POR TIPO DE PAGO ====================
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($lightFillR, $lightFillG, $lightFillB);
$pdf->SetDrawColor($borderR, $borderG, $borderB);
$pdf->Cell($ANCHO_UTIL, 4, 'RESUMEN POR TIPO DE PAGO', 1, 1, 'L', 1);

// Calcular totales por tipo
$totalesPorTipo = array();
foreach($ORDEN_TIPOS as $tipo) {
    $totalesPorTipo[$tipo] = array('total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cantidad' => 0);
    foreach($anos as $ano) {
        for($mes = 0; $mes < 12; $mes++) {
            foreach($estructura[$tipo][$ano][$mes] as $pago) {
                $monto = floatval($pago['mon_ori_pag'] ?: $pago['mon_pag']);
                $totalesPorTipo[$tipo]['total'] += $monto;
                $totalesPorTipo[$tipo]['cantidad']++;
                if($pago['est_pag'] === 'Pagado') {
                    $totalesPorTipo[$tipo]['pagado'] += $monto;
                } else {
                    $totalesPorTipo[$tipo]['pendiente'] += $monto;
                }
            }
        }
    }
}

// Anchos para resumen (6 columnas)
$anchoResumen = $ANCHO_UTIL / 6;

// Header de la tabla resumen
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(235, 235, 238);
$pdf->SetDrawColor(200, 200, 200);
$pdf->Cell($anchoResumen, 4, 'TIPO DE PAGO', 1, 0, 'C', 1);
$pdf->Cell($anchoResumen, 4, 'CANTIDAD', 1, 0, 'C', 1);
$pdf->Cell($anchoResumen, 4, 'TOTAL', 1, 0, 'C', 1);
$pdf->Cell($anchoResumen, 4, 'PAGADO', 1, 0, 'C', 1);
$pdf->Cell($anchoResumen, 4, 'PENDIENTE', 1, 0, 'C', 1);
$pdf->Cell($anchoResumen, 4, '% CUMPLIMIENTO', 1, 1, 'C', 1);

// Datos del resumen
$pdf->SetFont('Arial', '', 6);
foreach($ORDEN_TIPOS as $tipo) {
    if($totalesPorTipo[$tipo]['total'] == 0) continue;
    
    $porcentajeTipo = $totalesPorTipo[$tipo]['total'] > 0 
        ? ($totalesPorTipo[$tipo]['pagado'] / $totalesPorTipo[$tipo]['total']) * 100 
        : 0;
    
    $pdf->SetFillColor(252, 252, 254);
    $pdf->SetTextColor(0);
    $pdf->Cell($anchoResumen, 4, $LABELS_TIPOS[$tipo], 1, 0, 'L', 1);
    $pdf->Cell($anchoResumen, 4, $totalesPorTipo[$tipo]['cantidad'], 1, 0, 'C', 1);
    $pdf->Cell($anchoResumen, 4, '$' . number_format($totalesPorTipo[$tipo]['total'], 2), 1, 0, 'R', 1);
    
    $pdf->SetTextColor(40, 167, 69);
    $pdf->Cell($anchoResumen, 4, '$' . number_format($totalesPorTipo[$tipo]['pagado'], 2), 1, 0, 'R', 1);
    
    $colorPendiente = $totalesPorTipo[$tipo]['pendiente'] > 0 ? array(220, 53, 69) : array(0, 0, 0);
    $pdf->SetTextColor($colorPendiente[0], $colorPendiente[1], $colorPendiente[2]);
    $pdf->Cell($anchoResumen, 4, '$' . number_format($totalesPorTipo[$tipo]['pendiente'], 2), 1, 0, 'R', 1);
    
    $colorPorcentaje = $porcentajeTipo >= 80 ? array(40, 167, 69) : ($porcentajeTipo >= 50 ? array(255, 153, 0) : array(220, 53, 69));
    $pdf->SetTextColor($colorPorcentaje[0], $colorPorcentaje[1], $colorPorcentaje[2]);
    $pdf->Cell($anchoResumen, 4, number_format($porcentajeTipo, 1) . '%', 1, 1, 'C', 1);
    
    $pdf->SetTextColor(0);
}

// Fila de totales generales
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(220, 220, 228);
$porcentajeGeneral = $totales['general'] > 0 ? ($totales['pagado'] / $totales['general']) * 100 : 0;

$totalCantidad = 0;
foreach($totalesPorTipo as $t) $totalCantidad += $t['cantidad'];

$pdf->SetTextColor(0);
$pdf->Cell($anchoResumen, 5, 'TOTAL GENERAL', 1, 0, 'R', 1);
$pdf->Cell($anchoResumen, 5, $totalCantidad, 1, 0, 'C', 1);
$pdf->Cell($anchoResumen, 5, '$' . number_format($totales['general'], 2), 1, 0, 'R', 1);

$pdf->SetTextColor(40, 167, 69);
$pdf->Cell($anchoResumen, 5, '$' . number_format($totales['pagado'], 2), 1, 0, 'R', 1);

$colorPendienteGen = $totales['pendiente'] > 0 ? array(220, 53, 69) : array(0, 0, 0);
$pdf->SetTextColor($colorPendienteGen[0], $colorPendienteGen[1], $colorPendienteGen[2]);
$pdf->Cell($anchoResumen, 5, '$' . number_format($totales['pendiente'], 2), 1, 0, 'R', 1);

$colorPorcentajeGen = $porcentajeGeneral >= 80 ? array(40, 167, 69) : ($porcentajeGeneral >= 50 ? array(255, 153, 0) : array(220, 53, 69));
$pdf->SetTextColor($colorPorcentajeGen[0], $colorPorcentajeGen[1], $colorPorcentajeGen[2]);
$pdf->Cell($anchoResumen, 5, number_format($porcentajeGeneral, 1) . '%', 1, 1, 'C', 1);

$pdf->SetTextColor(0);

// ==================== LEYENDA ====================
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(100);

// Leyenda de colores
$pdf->Cell(18, 3, 'LEYENDA:', 0, 0, 'L');

$pdf->SetFillColor(212, 237, 218);
$pdf->SetDrawColor(180, 180, 180);
$pdf->Cell(4, 3, '', 1, 0, 'C', 1);
$pdf->Cell(18, 3, ' PAGADO', 0, 0, 'L');

$pdf->SetFillColor(248, 215, 218);
$pdf->Cell(4, 3, '', 1, 0, 'C', 1);
$pdf->Cell(18, 3, ' VENCIDO', 0, 0, 'L');

$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(4, 3, '', 1, 0, 'C', 1);
$pdf->Cell(22, 3, ' PENDIENTE', 0, 1, 'L');

$pdf->SetTextColor(0);

// ==================== OUTPUT ====================
ob_end_clean();
$nombreArchivo = str_replace(' ', '_', limpiarTexto(strtoupper($alumno['nombre_completo']))) . '_HISTORIAL_MESES_' . date('Ymd') . '.pdf';
$pdf->Output('I', $nombreArchivo);
?>