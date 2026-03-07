<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');
include "../../fpdf/fpdf.php";

$id_gen = $_POST['id_gen'];

// Obtener datos de la generación
$sqlGeneracion = "
    SELECT * 
    FROM generacion 
    INNER JOIN rama ON rama.id_ram = generacion.id_ram5
    INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
    WHERE id_gen = $id_gen
";
$datosGeneracion = obtener_datos_consulta($db, $sqlGeneracion)['datos'];

// Obtener datos de los alumnos
$sqlAlumnos = "
    SELECT * 
    FROM vista_alumnos  
    WHERE id_gen1 = $id_gen 
    ORDER BY estatus_general ASC, nom_alu ASC
";
$resultadoAlumnos = mysqli_query($db, $sqlAlumnos);

// Agrupar datos por estatus
$alumnosPorEstatus = array();
$totalGeneral = 0;
while($filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos)){
    $estatus = $filaAlumnos['estatus_general'];
    if (!isset($alumnosPorEstatus[$estatus])) {
        $alumnosPorEstatus[$estatus] = array();
    }
    $alumnosPorEstatus[$estatus][] = $filaAlumnos;
    $totalGeneral++;
}

// =================== CONFIGURACIÓN DE MÁRGENES ===================
$factor = 1.0; 
$margen_horizontal = 10; // Márgenes laterales mínimos
$margen_vertical = 12;   // Márgenes verticales mínimos
$ancho_disponible = 279.4 - ($margen_horizontal * 2); // Máximo ancho disponible
$alto_disponible = 215.9 - ($margen_vertical * 2);

// Definir colores y datos del programa
$fillR = 210;
$fillG = 230;
$fillB = 255;  // Azul claro original

$borderR = 0;
$borderG = 0;
$borderB = 255; // Azul para bordes

$lightFillR = 210;
$lightFillG = 230;
$lightFillB = 255; // El mismo azul claro para fondos

$titulo_escuela = strtoupper($datosGeneracion['nom_pla'] . ' - ' . $datosGeneracion['des_pla']);
$logo_archivo = "../img/" . $datosGeneracion['fot_pla'];

// Crear PDF en orientación horizontal con clase personalizada para footer
class PDF extends FPDF
{
    private $ancho_disponible;
    private $logo_archivo;
    
    function __construct($ancho, $logo) {
        parent::__construct('L', 'mm', 'Letter');
        $this->ancho_disponible = $ancho;
        $this->logo_archivo = $logo;
    }
    
    function Header()
    {
        // El header se maneja manualmente en el contenido principal
        // para mejor control de posicionamiento del logo
    }
    
    function Footer()
    {
        global $nomResponsable;
        
        // Posición a 15 mm del bottom
        $this->SetY(-15);
        
        $fecha_formateada = fechaFormateadaCompacta4(date('Y-m-d')) . ' ' . date('H:i');
        
        // Footer con cuatro columnas que ocupan todo el ancho
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(128, 128, 128);
        
        // Columna 1 - Página actual
        $pagina_actual = $this->PageNo();
        $this->Cell($this->ancho_disponible / 4, 4, "Pagina: " . $pagina_actual, 0, 0, 'L');
        
        // Columna 2 - Fecha de impresión
        $this->Cell($this->ancho_disponible / 4, 4, "Impreso: " . strtoupper($fecha_formateada), 0, 0, 'L');
        
        // Columna 3 - Responsable
        $responsable_texto = isset($nomResponsable) ? $nomResponsable : 'Sistema';
        $this->Cell($this->ancho_disponible / 4, 4, "Por: " . strtoupper(limpiarTexto($responsable_texto)), 0, 0, 'L');
        
        // Columna 4 - URL
        $this->SetTextColor(0, 0, 255); // Azul estándar
        $this->SetFont('Arial', '', 7);
        $url = 'https://ahjende.com';
        $this->Cell($this->ancho_disponible / 4, 4, $url, 0, 1, 'R');
    }
}

$pdf = new PDF($ancho_disponible, $logo_archivo);
$pdf->AddPage();
$pdf->SetMargins($margen_horizontal, $margen_vertical, $margen_horizontal);
$pdf->SetAutoPageBreak(true, 20); // 20mm para dejar espacio al footer

// =================== DIMENSIONES QUE OCUPAN TODO EL ANCHO ===================
$dim = array(
    'ancho_total' => $ancho_disponible,
    
    // Información del grupo - OCUPA TODO EL ANCHO
    'grupo_label' => round($ancho_disponible * 0.10),     // 10%
    'grupo_valor' => round($ancho_disponible * 0.40),     // 40%
    'programa_label' => round($ancho_disponible * 0.12),  // 12%
    'programa_valor' => round($ancho_disponible * 0.38),  // 38%
    
    'horario_label' => round($ancho_disponible * 0.10),   // 10%
    'horario_valor' => round($ancho_disponible * 0.18),   // 18%
    'dias_label' => round($ancho_disponible * 0.08),      // 8%
    'dias_valor' => round($ancho_disponible * 0.15),      // 15%
    'inicio_label' => round($ancho_disponible * 0.10),    // 10%
    'inicio_valor' => round($ancho_disponible * 0.39),    // 39%
    
    // Tabla - OCUPA TODO EL ANCHO SIN DESPERDICIAR ESPACIO
    'tabla_num' => round($ancho_disponible * 0.03),       // 3%
    'tabla_matricula' => round($ancho_disponible * 0.08), // 8%
    'tabla_nombre' => round($ancho_disponible * 0.42),    // 42% - MUCHO MÁS ANCHA
    'tabla_dia' => round($ancho_disponible * 0.018),      // 1.8% cada día (más compacto)
    'tabla_estatus' => round($ancho_disponible * 0.08),   // 8%
    'tabla_telefonos' => round($ancho_disponible * 0.165) // 16.5% - MÁS PEQUEÑA
);

// Función para convertir caracteres especiales
function limpiarTexto($texto) {
    $texto = utf8_decode($texto);
    $texto = str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'),
        array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N'),
        $texto
    );
    return $texto;
}

// =================== ESPACIADO UNIFORME ===================
$espacio_base = 3; // Unidad base para espaciado más compacto

// ENCABEZADO DEL DOCUMENTO - OCUPA TODO EL ANCHO
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetFillColor($lightFillR, $lightFillG, $lightFillB);
$pdf->SetDrawColor($borderR, $borderG, $borderB);

// Crear el título del reporte PRIMERO
$pdf->Cell($dim['ancho_total'], 9, 'REPORTE GRUPAL DE ALUMNOS', 1, 1, 'C', 1);

// AHORA agregar el logo en la esquina superior derecha SIN tapar el texto
if (file_exists($logo_archivo)) {
    $logo_size = 28; // 30% más pequeño (40mm - 30% = 28mm)
    // Posición ajustada según tus instrucciones
    $x_logo = $dim['ancho_total'] - $logo_size + 10; // Mantener la misma posición horizontal
    $y_logo = $pdf->GetY() - 4; // 10px más arriba (era -1, ahora -4)
    
    $pdf->Image($logo_archivo, $x_logo, $y_logo, $logo_size, $logo_size);
}

$pdf->Ln($espacio_base);

// Información de la escuela - ALINEADA A LA IZQUIERDA
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell($dim['ancho_total'], 5, $titulo_escuela, 0, 1, 'L');

$pdf->SetFont('Arial', '', 9);
$texto_plantel = strtoupper(limpiarTexto($datosGeneracion['nom_pla']));
$pdf->Cell($dim['ancho_total'], 4, $texto_plantel, 0, 1, 'L');

$texto_direccion = strtoupper(limpiarTexto($datosGeneracion['dir_pla']));
$pdf->Cell($dim['ancho_total'], 4, $texto_direccion, 0, 1, 'L');

$pdf->Ln($espacio_base);

// Información del grupo - OCUPA TODO EL ANCHO DISPONIBLE
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor($lightFillR, $lightFillG, $lightFillB);

// Primera fila - ANCHO COMPLETO
$pdf->Cell($dim['grupo_label'], 5, 'GRUPO:', 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($dim['grupo_valor'], 5, strtoupper(limpiarTexto($datosGeneracion['nom_gen'])), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($dim['programa_label'], 5, 'PROGRAMA:', 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($dim['programa_valor'], 5, strtoupper(limpiarTexto($datosGeneracion['nom_ram'])), 1, 1, 'L');

// Segunda fila - ANCHO COMPLETO
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($dim['horario_label'], 5, 'HORARIO:', 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($dim['horario_valor'], 5, strtoupper(limpiarTexto($datosGeneracion['hor_gen'])), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($dim['dias_label'], 5, 'DIAS:', 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($dim['dias_valor'], 5, strtoupper(limpiarTexto($datosGeneracion['dia_gen'])), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($dim['inicio_label'], 5, 'FECHAS:', 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$fechas_formateadas = fechaFormateadaCompacta4($datosGeneracion['ini_gen']) . ' AL ' . fechaFormateadaCompacta4($datosGeneracion['fin_gen']);
$pdf->Cell($dim['inicio_valor'], 5, $fechas_formateadas, 1, 1, 'L');

$pdf->Ln($espacio_base * 2);

// TABLA DE ASISTENCIA - OCUPA TODO EL ANCHO DISPONIBLE
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($lightFillR, $lightFillG, $lightFillB);

// Verificar que las dimensiones sumen el ancho total
$suma_columnas = $dim['tabla_num'] + $dim['tabla_matricula'] + $dim['tabla_nombre'] + 
                ($dim['tabla_dia'] * 14) + $dim['tabla_estatus'] + $dim['tabla_telefonos'];

// Ajustar la última columna si hay diferencia
if ($suma_columnas != $dim['ancho_total']) {
    $diferencia = $dim['ancho_total'] - $suma_columnas;
    $dim['tabla_telefonos'] += $diferencia;
}

// Función para repetir encabezados en nueva página
function repetirEncabezados($pdf, $dim, $lightFillR, $lightFillG, $lightFillB) {
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetFillColor($lightFillR, $lightFillG, $lightFillB);
    
    $pdf->Cell($dim['tabla_num'], 5, '#', 1, 0, 'C', 1);
    $pdf->Cell($dim['tabla_matricula'], 5, 'MATRICULA', 1, 0, 'C', 1);
    $pdf->Cell($dim['tabla_nombre'], 5, 'NOMBRE', 1, 0, 'C', 1);
    
    // Días de la semana - más compactos
    $dias = ['L','M','M','J','V','S','D'];
    for ($i = 0; $i < 14; $i++) {
        $pdf->Cell($dim['tabla_dia'], 5, $dias[$i % 7], 1, 0, 'C', 1);
    }
    
    $pdf->Cell($dim['tabla_estatus'], 5, 'ESTATUS', 1, 0, 'C', 1);
    $pdf->Cell($dim['tabla_telefonos'], 5, 'TELEFONOS', 1, 1, 'C', 1);
}

// Encabezados de la tabla - ANCHO COMPLETO
$pdf->Cell($dim['tabla_num'], 5, '#', 1, 0, 'C', 1);
$pdf->Cell($dim['tabla_matricula'], 5, 'MATRICULA', 1, 0, 'C', 1);
$pdf->Cell($dim['tabla_nombre'], 5, 'NOMBRE', 1, 0, 'C', 1);

// Semanas 1 y 2 - más compactas
$dias = ['L','M','M','J','V','S','D'];
for ($i = 0; $i < 14; $i++) {
    $pdf->Cell($dim['tabla_dia'], 5, $dias[$i % 7], 1, 0, 'C', 1);
}

$pdf->Cell($dim['tabla_estatus'], 5, 'ESTATUS', 1, 0, 'C', 1);
$pdf->Cell($dim['tabla_telefonos'], 5, 'TELEFONOS', 1, 1, 'C', 1);

// Datos de los alumnos
$contador = 1;
$pdf->SetFont('Arial', '', 6);

foreach ($alumnosPorEstatus as $estatus => $alumnos) {
    foreach ($alumnos as $filaAlumnos) {
        // Verificar si necesitamos una nueva página
        if ($pdf->GetY() > $alto_disponible - 25) { // Ajustado para dejar espacio al footer
            $pdf->AddPage();
            repetirEncabezados($pdf, $dim, $lightFillR, $lightFillG, $lightFillB);
            $pdf->SetFont('Arial', '', 6);
        }
        
        // Calcular caracteres máximos para el nombre (ahora es mucho más ancha)
        $max_chars_nombre = 65; // Aumentado significativamente
        $max_chars_telefono = 22; // Reducido
        
        // Fila de datos - ANCHO COMPLETO
        $pdf->Cell($dim['tabla_num'], 4, $contador, 1, 0, 'C');
        $pdf->Cell($dim['tabla_matricula'], 4, $filaAlumnos['id_alu_ram'], 1, 0, 'C');
        $pdf->Cell($dim['tabla_nombre'], 4, strtoupper(limpiarTexto(substr($filaAlumnos['nom_alu'], 0, $max_chars_nombre))), 1, 0, 'L');
        
        // Columnas de días (14 columnas para asistencia)
        for ($i = 0; $i < 14; $i++) {
            $pdf->Cell($dim['tabla_dia'], 4, '', 1, 0, 'C');
        }
        
        $pdf->Cell($dim['tabla_estatus'], 4, strtoupper(limpiarTexto($filaAlumnos['estatus_general'])), 1, 0, 'C');
        $telefonos = $filaAlumnos['tel_alu'] . ' / ' . $filaAlumnos['tel2_alu'];
        $pdf->Cell($dim['tabla_telefonos'], 4, substr($telefonos, 0, $max_chars_telefono), 1, 1, 'C');
        
        $contador++;
    }
    
    // Fila de total por estatus - ANCHO COMPLETO
    $totalEstatus = count($alumnos);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetFillColor(233, 236, 239);
    
    $pdf->Cell($dim['tabla_num'], 4, '', 1, 0, 'C', 1);
    $pdf->Cell($dim['tabla_matricula'], 4, '', 1, 0, 'C', 1);
    $pdf->Cell($dim['tabla_nombre'], 4, '', 1, 0, 'C', 1);
    for ($i = 0; $i < 14; $i++) {
        $pdf->Cell($dim['tabla_dia'], 4, '', 1, 0, 'C', 1);
    }
    $pdf->Cell($dim['tabla_estatus'], 4, 'TOTAL:', 1, 0, 'R', 1);
    $pdf->Cell($dim['tabla_telefonos'], 4, $totalEstatus, 1, 1, 'C', 1);
    
    // Fila de separación - ANCHO COMPLETO
    $pdf->Cell($dim['tabla_num'], 2, '', 1, 0, 'C');
    $pdf->Cell($dim['tabla_matricula'], 2, '', 1, 0, 'C');
    $pdf->Cell($dim['tabla_nombre'], 2, '', 1, 0, 'C');
    for ($i = 0; $i < 14; $i++) {
        $pdf->Cell($dim['tabla_dia'], 2, '', 1, 0, 'C');
    }
    $pdf->Cell($dim['tabla_estatus'], 2, '', 1, 0, 'C');
    $pdf->Cell($dim['tabla_telefonos'], 2, '', 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetFillColor(255, 255, 255);
}

// Total general - ANCHO COMPLETO
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(108, 117, 125);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell($dim['tabla_num'], 5, '', 1, 0, 'C', 1);
$pdf->Cell($dim['tabla_matricula'], 5, '', 1, 0, 'C', 1);
$pdf->Cell($dim['tabla_nombre'], 5, '', 1, 0, 'C', 1);
for ($i = 0; $i < 14; $i++) {
    $pdf->Cell($dim['tabla_dia'], 5, '', 1, 0, 'C', 1);
}
$pdf->Cell($dim['tabla_estatus'], 5, 'TOTAL GENERAL:', 1, 0, 'R', 1);
$pdf->Cell($dim['tabla_telefonos'], 5, $totalGeneral, 1, 1, 'C', 1);

// Resetear colores
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

$nombre_archivo = 'reporte_grupal_' . str_replace(' ', '_', strtolower(limpiarTexto($datosGeneracion['nom_gen']))) . '.pdf';
$pdf->Output('I', $nombre_archivo);
?>