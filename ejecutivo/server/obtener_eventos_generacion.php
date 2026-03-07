<?php  

// obtener_eventos_generacion.php

require('../inc/cabeceras.php');
require('../inc/funciones.php');

$id_gen = mysqli_real_escape_string($db, $_POST['id_gen']);

// 🔥 OBTENER gra_ram DE LA GENERACIÓN
$sqlGeneracion = "SELECT g.nom_gen, g.ini_gen, r.gra_ram 
                  FROM generacion g
                  INNER JOIN rama r ON r.id_ram = g.id_ram5
                  WHERE g.id_gen = '$id_gen'";
$resultadoGeneracion = mysqli_query($db, $sqlGeneracion);
$datosGeneracion = mysqli_fetch_assoc($resultadoGeneracion);
$ini_gen = $datosGeneracion['ini_gen'];
$gra_ram = $datosGeneracion['gra_ram'];
$nom_gen = $datosGeneracion['nom_gen'];

// 🔥 CATÁLOGO DE PLANTILLAS CON FECHAS EXACTAS
// Para "Examen-Único": ini_gen es el día de BIENVENIDA (ej: 6 de marzo)
// Los eventos previos son días ESPECÍFICOS del mes anterior
$catalogoEventos = [
    "Examen-Único" => [
        [
            "orden" => 1,
            "concepto" => "VALIDACIÓN DN",
            "descripcion" => "VALIDACIÓN DN",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior", // Día 20 del mes anterior
            "dia_especifico" => 20
        ],
        [
            "orden" => 2,
            "concepto" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "descripcion" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior", // Día 22 del mes anterior
            "dia_especifico" => 22
        ],
        [
            "orden" => 3,
            "concepto" => "COLEGIATURA 27",
            "descripcion" => "COLEGIATURA 27",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior", // Día 27 del mes anterior
            "dia_especifico" => 27
        ],
        [
            "orden" => 4,
            "concepto" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "descripcion" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior", // Día 27 del mes anterior
            "dia_especifico" => 27
        ],
        [
            "orden" => 5,
            "concepto" => "BIENVENIDA/PLÁTICA DE INICIO",
            "descripcion" => "BIENVENIDA/PLÁTICA DE INICIO",
            "semana_texto" => "SEMANA 1",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 0 // ini_gen
        ],
        [
            "orden" => 6,
            "concepto" => "CLASE 2",
            "descripcion" => "CLASE 2",
            "semana_texto" => "SEMANA 2",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 7
        ],
        [
            "orden" => 7,
            "concepto" => "CLASE 3",
            "descripcion" => "CLASE 3",
            "semana_texto" => "SEMANA 3",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 14
        ],
        [
            "orden" => 8,
            "concepto" => "CLASE 4 / CEREMONIA DE CIERRE",
            "descripcion" => "HACER PLÁTICA DE SALIDA Y RECORDAR LA CLASE #5. NOTIFICARLES QUE HAGAN EL PAGO DE CERTIFICACIÓN, MANDAR LA CUENTA, UNA VEZ REALIZANDO ESE PAGO, NOSOTROS NOS NOTIFICAREMOS PARA AVISARLES EN QUE FECHA ENTRÓ SU CERTIFICACIÓN Y QUE A PARTIR DE ESA FECHA SE CUENTAN LOS 120 DÍAS HÁBILES PARA LA ENTREGA. DANIEL MAQUEDA Y ENTRAN DIRECTORES, PE",
            "semana_texto" => "SEMANA 4",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 21
        ],
        [
            "orden" => 9,
            "concepto" => "PLÁTICA EL VERDADERO VALOR DE LA CERTIFICACIÓN",
            "descripcion" => "ESTA PLÁTICA TIENE CÓMO FINALIDAD HACER LA TRANSICIÓN DE EXAMEN ÚNICO A DIPLOMADO \"EMPRENDE\". AQUÍ SE HACE EL LLENADO DE SOLICITUD DE INSCRIPCIÓN Y SE LE DA FECHA AL DIPLOMADO EN EMPRENDIMIENTO. LA PLÁTICA LA DA EL DIRECTOR COMERCIAL, VOLVIENDO A COBRAR INSCRIPCIÓN DE ACUERDO A CATALOGO DE COSTOS, COLEGIATURA A CATALOGO DE COSTOS, EL DIPLOMA AL CATALOGO DE COSTOS Y CUENTAN COMO REGISTROS NUEVOS, TODOS LOS QUE SE CIERRAN EN ESTA PLÁTICA, ES IMPORTANTE MENCIONAR QUE SI SE INSCRIBEN AL DIPLOMADO DE EMPRENDIMIENTO, CON ESA MISMA INSCRIPCIÓN TIENEN EL PASE DIRECTO A LA UNIVERSIDAD SIN HACER EXAMEN, AL HABER HECHO YA EL DIPLOMADO.",
            "semana_texto" => "SEMANA 5",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 28
        ],
        [
            "orden" => 10,
            "concepto" => "PAGO DE CERTIFICADO",
            "descripcion" => "DÍA LUNES",
            "semana_texto" => "SEMANA 8",
            "tipo_calculo" => "dia_mes_siguiente", // Día 24 de abril (mes +1)
            "mes_offset" => 1,
            "dia_especifico" => 24
        ],
        [
            "orden" => 11,
            "concepto" => "INGRESO DE CERTIFICACIÓN",
            "descripcion" => "LLENAR FORMATO DE EXAMEN ÚNICO. TENER EXPEDIENTES COMPLETOS (SEM1). PAGOS COMPLETOS DE LA SEMANA 8, PARA SABER QUE LÍDERES INGRESAN AL PROCESO. SE LE NOTIFICA A LOS LÍDERES QUE EL TRÁMITE HA SIDO INGRESADO DESPUÉS DE HABER HECHO EL PAGO Y QUE SE HAN ENVIADO Y QUE ENTRARÁN LA FECHA INMEDIATA AL CALENDARIO DE CERTIFICACIÓN OFICIAL QUE SON FEBRERO-MAYO-AGOSTO-NOVIEMBRE. 120 DÍAS HÁBILES",
            "semana_texto" => "SEMANA 9",
            "tipo_calculo" => "dia_mes_siguiente", // Día 1 de mayo (mes +2)
            "mes_offset" => 2,
            "dia_especifico" => 1
        ]
    ],
    "Licenciatura" => [
        [
            "orden" => 1,
            "concepto" => "VALIDACIÓN DN",
            "descripcion" => "VALIDACIÓN DN",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 18
        ],
        [
            "orden" => 2,
            "concepto" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "descripcion" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 22
        ],
        [
            "orden" => 3,
            "concepto" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "descripcion" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 25
        ],
        [
            "orden" => 4,
            "concepto" => "COLEGIATURA 27",
            "descripcion" => "COLEGIATURA 27",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 27
        ],
        [
            "orden" => 5,
            "concepto" => "BIENVENIDA",
            "descripcion" => "BIENVENIDA",
            "semana_texto" => "SEMANA 1",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 0
        ],
        [
            "orden" => 6,
            "concepto" => "PLÁTICA DE INICIO",
            "descripcion" => "PLÁTICA DE INICIO",
            "semana_texto" => "SEMANA 2",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 7
        ],
        [
            "orden" => 7,
            "concepto" => "FIN DE PERIODO 1",
            "descripcion" => "FIN DE PERIODO 1",
            "semana_texto" => "SEMANA 8",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 49
        ],
        [
            "orden" => 8,
            "concepto" => "VÍDEOACADÉMICO",
            "descripcion" => "VÍDEOACADÉMICO",
            "semana_texto" => "SEMANA 13",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 84
        ],
        [
            "orden" => 9,
            "concepto" => "FIN DE PERIODO/PAGO DE REINSCRIPCIÓN",
            "descripcion" => "FIN DE PERIODO/PAGO DE REINSCRIPCIÓN",
            "semana_texto" => "SEMANA 15",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 98
        ],
        [
            "orden" => 10,
            "concepto" => "REALIZACIÓN DE EXTRAORDINARIOS",
            "descripcion" => "REALIZACIÓN DE EXTRAORDINARIOS",
            "semana_texto" => "SEMANA 16",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 105
        ],
        [
            "orden" => 11,
            "concepto" => "INICIO SERVICIO SOCIAL",
            "descripcion" => "INICIO SERVICIO SOCIAL",
            "semana_texto" => "2 AÑOS - ENERO",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 112
        ]
    ],
    "Diplomado" => [
        [
            "orden" => 1,
            "concepto" => "VALIDACIÓN DN",
            "descripcion" => "VALIDACIÓN DN",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 17
        ],
        [
            "orden" => 2,
            "concepto" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "descripcion" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 22
        ],
        [
            "orden" => 3,
            "concepto" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "descripcion" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 24
        ],
        [
            "orden" => 4,
            "concepto" => "COLEGIATURA 27",
            "descripcion" => "COLEGIATURA 27",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 27
        ],
        [
            "orden" => 5,
            "concepto" => "BIENVENIDA",
            "descripcion" => "BIENVENIDA",
            "semana_texto" => "SEMANA 1",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 0
        ],
        [
            "orden" => 6,
            "concepto" => "PLÁTICA DE INICIO",
            "descripcion" => "PLÁTICA DE INICIO",
            "semana_texto" => "SEMANA 2",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 7
        ],
        [
            "orden" => 7,
            "concepto" => "PAGO DE DIPLOMA",
            "descripcion" => "PAGO DE DIPLOMA",
            "semana_texto" => "SEMANA 11",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 70
        ],
        [
            "orden" => 8,
            "concepto" => "ENVÍO DE PROCESO",
            "descripcion" => "ENVÍO DE PROCESO",
            "semana_texto" => "SEMANA 12",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 77
        ],
        [
            "orden" => 9,
            "concepto" => "VÍDEO ACADÉMICO",
            "descripcion" => "EL LÍDER GRABARÁ UN VÍDEO DE UN TEMA ESPECÍFICO DE CONTENIDO DE VALOR, EL CUAL SUBIRÁ A SUS REDES SOCIALES.",
            "semana_texto" => "SEMANA 13",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 84
        ],
        [
            "orden" => 10,
            "concepto" => "CEREMONIA DE CIERRE",
            "descripcion" => "ONLINE: ZOOM. PRESENCIAL: CONFERENCIA",
            "semana_texto" => "SEMANA 14",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 91
        ],
        [
            "orden" => 11,
            "concepto" => "¿Por qué el licenciado maneja un Uber?",
            "descripcion" => "LA DA EL DIRECTOR COMERCIAL.",
            "semana_texto" => "SEMANA 15",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 98
        ]
    ],
    "Preparatoria" => [
        [
            "orden" => 1,
            "concepto" => "VALIDACIÓN DN",
            "descripcion" => "VALIDACIÓN DN",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 21
        ],
        [
            "orden" => 2,
            "concepto" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "descripcion" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 22
        ],
        [
            "orden" => 3,
            "concepto" => "COLEGIATURA 27",
            "descripcion" => "COLEGIATURA 27",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 27
        ],
        [
            "orden" => 4,
            "concepto" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "descripcion" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "semana_texto" => "1 SEMANA ANTES",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 28
        ],
        [
            "orden" => 5,
            "concepto" => "BIENVENIDA",
            "descripcion" => "BIENVENIDA",
            "semana_texto" => "SEMANA 1",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 0
        ],
        [
            "orden" => 6,
            "concepto" => "PLÁTICA DE INICIO",
            "descripcion" => "PLÁTICA DE INICIO",
            "semana_texto" => "SEMANA 2",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 7
        ],
        [
            "orden" => 7,
            "concepto" => "REVISIÓN DE DESEMPEÑO",
            "descripcion" => "REVISIÓN DE DESEMPEÑO",
            "semana_texto" => "SEMANA 7",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 42
        ],
        [
            "orden" => 8,
            "concepto" => "ENTREGA DE CARTA ASPIRANTE / TRÁMITE 1",
            "descripcion" => "ENTREGA DE CARTA ASPIRANTE / TRÁMITE 1",
            "semana_texto" => "SEMANA 10",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 63
        ],
        [
            "orden" => 9,
            "concepto" => "VÍDEOACADÉMICO",
            "descripcion" => "EL LÍDER GRABARÁ UN VÍDEO DE UN TEMA ESPECÍFICO DE CONTENIDO DE VALOR, EL CUAL SUBIRÁ A SUS REDES SOCIALES.",
            "semana_texto" => "SEMANA 14",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 91
        ],
        [
            "orden" => 10,
            "concepto" => "INGRESO DE CERTIFICACIÓN",
            "descripcion" => "SE LE NOTIFICA A LOS LÍDERES QUE EL TRÁMITE HA SIDO INGRESADO DESPUÉS DE HABER HECHO EL PAGO Y QUE SE HAN ENVIADO Y QUE ENTRARÁN LA FECHA INMEDIATA AL CALENDARIO DE CERTIFICACIÓN OFICIAL QUE SON FEBRERO-MAYO-AGOSTO-NOVIEMBRE. 120 DÍAS HÁBILES",
            "semana_texto" => "SEMANA 15",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 98
        ],
        [
            "orden" => 11,
            "concepto" => "REVISIÓN DE DESEMPEÑO",
            "descripcion" => "REVISIÓN DE DESEMPEÑO",
            "semana_texto" => "SEMANA 16",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 105
        ],
        [
            "orden" => 12,
            "concepto" => "TRÁMITE 2",
            "descripcion" => "TRÁMITE 2",
            "semana_texto" => "SEMANA 18",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 119
        ],
        [
            "orden" => 13,
            "concepto" => "CONOCER SI APROBARON",
            "descripcion" => "CONOCER SI APROBARON",
            "semana_texto" => "SEMANA 20",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 133
        ],
        [
            "orden" => 14,
            "concepto" => "FIN DE CURSO APROBADO/NO APROBADO",
            "descripcion" => "FIN DE CURSO APROBADO/NO APROBADO",
            "semana_texto" => "SEMANA 25",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 168
        ],
        [
            "orden" => 15,
            "concepto" => "APLICACIÓN DE EXAMEN O ENTREGA DE MEMBRESÍA A DIPLOMADOS",
            "descripcion" => "APLICACIÓN DE EXAMEN O ENTREGA DE MEMBRESÍA A DIPLOMADOS",
            "semana_texto" => "SEMANA 27",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 182
        ],
        [
            "orden" => 16,
            "concepto" => "GRADUACIÓN",
            "descripcion" => "GRADUACIÓN",
            "semana_texto" => "SEMANA 28",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 189
        ],
        [
            "orden" => 17,
            "concepto" => "ARRANQUE DE DIPLOMADO",
            "descripcion" => "ONLINE: ZOOM. PRESENCIAL: SE VA A RETOMAR EL DIPLOMADO DE NEGOCIOS COMO CIERRE",
            "semana_texto" => "SEMANA 29",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 196
        ]
    ],
    "Seminario" => [
        [
            "orden" => 1,
            "concepto" => "VALIDACIÓN DN",
            "descripcion" => "VALIDACIÓN DN",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 21
        ],
        [
            "orden" => 2,
            "concepto" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VIDEO POR DN DE BIENVENIDA",
            "descripcion" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VIDEO POR DN DE BIENVENIDA",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 22
        ],
        [
            "orden" => 3,
            "concepto" => "COLEGIATURA 27",
            "descripcion" => "COLEGIATURA 27",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 27
        ],
        [
            "orden" => 4,
            "concepto" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "descripcion" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 28
        ],
        [
            "orden" => 5,
            "concepto" => "BIENVENIDA / PLÁTICA DE INICIO",
            "descripcion" => "SE DEBERÁ LLENAR EL FORMATO DE INCORPORACIÓN A DEPENDENCIA, DE ACUERDO A LA MODALIDAD QUE SE TENGA. APLICA EN TODOS LOS PROGRAMAS.",
            "semana_texto" => "SEMANA 1",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 0
        ],
        [
            "orden" => 6,
            "concepto" => "VIDEO ACADÉMICO",
            "descripcion" => "EL LÍDER CUMPLIRÁ CON LA ETAPA #5 DEL SEMINARIO Y SE LE SOLICITARÁ SUBIR SU VIDEO A SUS REDES SOCIALES. AL FINALIZAR LA SESIÓN 5 SE LES RECUERDA QUE PARA EL ACCESO A LA SIGUIENTE Y ÚLTIMA SESIÓN, DEBERÁN REALIZAR Y ENVIAR EL COMPROBANTE DE SU PAGO DE INCORPORACIÓN.",
            "semana_texto" => "SEMANA 27",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 182
        ],
        [
            "orden" => 7,
            "concepto" => "PAGO DE INCORPORACIÓN / CEREMONIA DE CIERRE",
            "descripcion" => "SE REALIZA LA CEREMONIA DE CIERRE, MISMO DÍA Y HORARIO YA COMPROMETIDO. EL PAGO SE REALIZA 1 DÍA ANTES A CUENTA BBVA. SUJETO A PARTICIPAR EN CEREMONIA.",
            "semana_texto" => "SEMANA 31",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 210
        ],
        [
            "orden" => 8,
            "concepto" => "# DE CUENTA DE DEPENDENCIA",
            "descripcion" => "EL DN COMPARTIRÁ DIRECTAMENTE AL LÍDER EL NÚMERO DE CUENTA DE LA DEPENDENCIA.",
            "semana_texto" => "SEMANA 32",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 217
        ],
        [
            "orden" => 9,
            "concepto" => "PAGO DE TITULACIÓN",
            "descripcion" => "SE REALIZA EL DÍA 15 DEL MES (MES 7). EL DÍA 16 SE ENVÍAN FORMATOS Y COMPROBANTES DE TODOS LOS LÍDERES.",
            "semana_texto" => "MES 7 DÍA - 15",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 222
        ],
        [
            "orden" => 10,
            "concepto" => "DESCARGA Y ENTREGA DE TÍTULOS",
            "descripcion" => "SE ENTREGA TODO EN UN SOLO DÍA. EVENTO CON GRABACIÓN DE CONTENIDO Y REDES.",
            "semana_texto" => "SEMANA 37",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 252
        ]
    ],
    "Bachillerato" => [
        [
            "orden" => 1,
            "concepto" => "VALIDACIÓN DN",
            "descripcion" => "VALIDACIÓN DN",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 20
        ],
        [
            "orden" => 2,
            "concepto" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "descripcion" => "CONFIRMACIÓN DÍA 22 Y ENVÍO DE VÍDEO POR DN DE BIENVENIDA",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 22
        ],
        [
            "orden" => 3,
            "concepto" => "COLEGIATURA 27",
            "descripcion" => "COLEGIATURA 27",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 27
        ],
        [
            "orden" => 4,
            "concepto" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "descripcion" => "RECEPCIÓN DE DOCUMENTOS LÍDER",
            "semana_texto" => "PREVIO",
            "tipo_calculo" => "dia_mes_anterior",
            "dia_especifico" => 27
        ],
        [
            "orden" => 5,
            "concepto" => "BIENVENIDA",
            "descripcion" => "BIENVENIDA",
            "semana_texto" => "SEMANA 1",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 0
        ],
        [
            "orden" => 6,
            "concepto" => "PLÁTICA DE INICIO",
            "descripcion" => "PLÁTICA DE INICIO",
            "semana_texto" => "SEMANA 2",
            "tipo_calculo" => "offset_dias",
            "offset_dias" => 7
        ]
    ]
];

// Verificar si existe plantilla para este gra_ram
$tienePlantilla = isset($catalogoEventos[$gra_ram]) && !empty($catalogoEventos[$gra_ram]);
$plantillaJSON = $tienePlantilla ? json_encode($catalogoEventos[$gra_ram]) : '[]';

// 🔥 OBTENER REGISTROS EXISTENTES
$sqlEventos = "SELECT id_gru_pag, con_gru_pag, sem_gru_pag, des_gru_pag, ini_gru_pag, val_gru_pag
               FROM grupo_pago 
               WHERE id_gen15 = '$id_gen' AND tip_gru_pag = 'Fecha'
               ORDER BY ini_gru_pag ASC, id_gru_pag DESC";
$resultadoEventos = mysqli_query($db, $sqlEventos);
$registrosExistentes = [];
if ($resultadoEventos) {
    while ($fila = mysqli_fetch_assoc($resultadoEventos)) {
        $registrosExistentes[] = $fila;
    }
}
$cantidadExistentes = count($registrosExistentes);
$completados = 0;
foreach ($registrosExistentes as $reg) {
    if ($reg['val_gru_pag'] == 'Resuelto') $completados++;
}
?>

<style>
/* HEADER COMPACTO */
.modal-eventos-header {
    background: #f8f9fa;
    padding: 8px 12px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-info {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    font-size: 11px;
}

.header-label {
    font-size: 10px;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
}

.header-value {
    font-size: 11px;
    color: #212529;
    font-weight: 500;
}

.badge-inline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
    height: 18px;
    padding: 0 6px;
    font-size: 10px;
    font-weight: 600;
    border-radius: 3px;
}

.badge-examen {
    background: #cfe2ff;
    color: #084298;
}

.badge-total {
    background: #e9ecef;
    color: #495057;
}

.badge-completados {
    background: #d1e7dd;
    color: #0f5132;
}

.badge-pendientes {
    background: #fff3cd;
    color: #664d03;
}

.header-actions {
    display: flex;
    gap: 6px;
}

.btn-plantilla {
    background: #5a6268;
    color: white;
    border: none;
    padding: 3px 8px;
    border-radius: 2px;
    font-size: 10px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    line-height: 1;
    white-space: nowrap;
}

.btn-plantilla:hover {
    background: #4e555b;
}

.btn-plantilla i {
    font-size: 11px;
}

.btn-agregar {
    background: white;
    color: #0d6efd;
    border: 1px solid #dee2e6;
    padding: 3px 8px;
    border-radius: 2px;
    font-size: 10px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    line-height: 1;
    white-space: nowrap;
}

.btn-agregar:hover {
    background: #f8f9fa;
    border-color: #0d6efd;
}

/* BODY */
.eventos-body {
    padding: 12px;
    max-height: 60vh;
    overflow-y: auto;
}

.empty-eventos {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.3;
}

.empty-text {
    font-size: 12px;
    color: #495057;
    margin: 0 0 6px 0;
    font-weight: 600;
}

.empty-hint {
    font-size: 10px;
    color: #6c757d;
}

/* EVENTO CARD COMPACTO */
.evento-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 8px;
    margin-bottom: 8px;
}

.evento-card.completado {
    background: #f8f9fa;
    opacity: 0.8;
}

.evento-card.nuevo {
    background: #f0f9ff;
    border-color: #b6d4fe;
}

.evento-top {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}

.evento-check {
    flex-shrink: 0;
}

.evento-check input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
    margin-top: 4px;
}

.evento-main {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 100px 120px;
    gap: 8px;
}

.evento-field {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.evento-label {
    font-size: 9px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
}

.evento-input {
    border: 1px solid #ced4da;
    border-radius: 3px;
    padding: 4px 6px;
    font-size: 11px;
    color: #212529;
}

.evento-input:focus {
    outline: none;
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.evento-concepto {
    font-weight: 600;
}

.evento-actions {
    flex-shrink: 0;
}

.btn-eliminar {
    background: none;
    border: none;
    color: #adb5bd;
    cursor: pointer;
    padding: 2px;
    width: 20px;
    height: 20px;
    border-radius: 3px;
    font-size: 18px;
}

.btn-eliminar:hover {
    background: #f8d7da;
    color: #dc3545;
}

.evento-desc {
    margin-top: 8px;
    padding-left: 24px;
}

.evento-textarea {
    min-height: 50px;
    resize: vertical;
    font-family: inherit;
    line-height: 1.3;
}
</style>

<!-- HEADER DEL MODAL -->
<div class="modal-eventos-header">
    <div class="header-info">
        <div>
            <span class="header-label">Eventos del grupo</span>
            <span class="badge-inline badge-examen"><?php echo htmlspecialchars($gra_ram); ?></span>
        </div>
        <div>
            <span class="header-label">Inicio:</span>
            <span class="header-value"><?php echo date('d/m/Y', strtotime($ini_gen)); ?></span>
        </div>
        <div>
            <span class="header-label">Total:</span>
            <span class="badge-inline badge-total" id="total-eventos"><?php echo $cantidadExistentes; ?></span>
        </div>
        <div>
            <span class="header-label">Completados:</span>
            <span class="badge-inline badge-completados" id="completados-eventos"><?php echo $completados; ?></span>
        </div>
        <div>
            <span class="header-label">Pendientes:</span>
            <span class="badge-inline badge-pendientes" id="pendientes-eventos"><?php echo $cantidadExistentes - $completados; ?></span>
        </div>
    </div>
    
    <div class="header-actions">
        <?php if ($tienePlantilla && $cantidadExistentes == 0) { ?>
            <button type="button" class="btn-plantilla" id="btn-cargar-plantilla">
                <i class="mdi mdi-star"></i>
                <span>CARGAR PLANTILLA</span>
            </button>
        <?php } ?>
        <button type="button" class="btn-agregar" id="btn-agregar-evento">
            <i class="mdi mdi-plus"></i>
            <span>AGREGAR</span>
        </button>
    </div>
</div>

<!-- BODY CON EVENTOS -->
<div class="eventos-body" id="contenedor-eventos">
    <?php if (empty($registrosExistentes)) { ?>
        <div class="empty-eventos">
            <div class="empty-icon">📅</div>
            <p class="empty-text">NO HAY EVENTOS REGISTRADOS</p>
            <p class="empty-hint">
                <?php if ($tienePlantilla) { ?>
                    USA "CARGAR PLANTILLA" PARA EVENTOS PREDEFINIDOS O "AGREGAR" PARA CREAR MANUALMENTE
                <?php } else { ?>
                    HAZ CLIC EN "AGREGAR" PARA COMENZAR
                <?php } ?>
            </p>
        </div>
    <?php } else { ?>
        <?php foreach ($registrosExistentes as $registro) { 
            $claseCompletado = ($registro['val_gru_pag'] == 'Resuelto') ? 'completado' : '';
        ?>
            <div class="evento-card <?php echo $claseCompletado; ?>" data-tipo="existente" data-id="<?php echo $registro['id_gru_pag']; ?>">
                <div class="evento-top">
                    <div class="evento-check">
                        <input type="checkbox" class="check-validacion" 
                               <?php echo ($registro['val_gru_pag'] == 'Resuelto') ? 'checked' : ''; ?>>
                    </div>
                    
                    <div class="evento-main">
                        <div class="evento-field">
                            <label class="evento-label">Título del Evento</label>
                            <input type="text" class="evento-input evento-concepto campo-concepto" 
                                   value="<?php echo htmlspecialchars($registro['con_gru_pag']); ?>"
                                   placeholder="VALIDACIÓN DN, ENTREGA DOCS...">
                        </div>
                        
                        <div class="evento-field">
                            <label class="evento-label">Semana</label>
                            <input type="text" class="evento-input campo-semana" 
                                   value="<?php echo htmlspecialchars($registro['sem_gru_pag']); ?>"
                                   placeholder="SEMANA 1">
                        </div>
                        
                        <div class="evento-field">
                            <label class="evento-label">Fecha</label>
                            <input type="date" class="evento-input campo-fecha" 
                                   value="<?php echo $registro['ini_gru_pag']; ?>">
                        </div>
                    </div>
                    
                    <div class="evento-actions">
                        <button type="button" class="btn-eliminar" title="Eliminar">×</button>
                    </div>
                </div>
                
                <div class="evento-desc">
                    <div class="evento-field">
                        <label class="evento-label">Descripción</label>
                        <textarea class="evento-input evento-textarea campo-descripcion" 
                                  placeholder="Descripción detallada..."><?php echo htmlspecialchars($registro['des_gru_pag']); ?></textarea>
                    </div>
                </div>
                
                <input type="hidden" class="id-evento" value="<?php echo $registro['id_gru_pag']; ?>">
            </div>
        <?php } ?>
    <?php } ?>
</div>

<script>
// 🔥 DATOS GLOBALES
window.eventosData = {
    idGen: <?php echo $id_gen; ?>,
    fechaInicio: '<?php echo $ini_gen; ?>',
    graRam: '<?php echo $gra_ram; ?>',
    plantilla: <?php echo $plantillaJSON; ?>,
    tienePlantilla: <?php echo $tienePlantilla ? 'true' : 'false'; ?>
};

console.log('📋 Eventos Data:', window.eventosData);

// 🔥 CALCULAR FECHA SEGÚN TIPO
function calcularFechaEvento(fechaInicio, evento) {
    const fecha = new Date(fechaInicio + 'T00:00:00');
    
    if (evento.tipo_calculo === 'offset_dias') {
        // Sumar días desde ini_gen
        fecha.setDate(fecha.getDate() + evento.offset_dias);
    } else if (evento.tipo_calculo === 'dia_mes_anterior') {
        // Día específico del mes anterior
        fecha.setMonth(fecha.getMonth() - 1);
        fecha.setDate(evento.dia_especifico);
    } else if (evento.tipo_calculo === 'dia_mes_siguiente') {
        // Día específico de un mes futuro
        fecha.setMonth(fecha.getMonth() + evento.mes_offset);
        fecha.setDate(evento.dia_especifico);
    }
    
    return fecha.toISOString().split('T')[0];
}

// 🔥 CARGAR PLANTILLA
function cargarPlantilla() {
    if (!window.eventosData.plantilla || window.eventosData.plantilla.length === 0) {
        swal("Error", "No hay plantilla disponible", "error");
        return;
    }
    
    swal({
        title: "¿Cargar plantilla?",
        text: `Se cargarán ${window.eventosData.plantilla.length} eventos para ${window.eventosData.graRam}`,
        icon: "info",
        buttons: ["Cancelar", "Sí, cargar"]
    }).then((willLoad) => {
        if (willLoad) {
            $('.empty-eventos').fadeOut(300);
            
            window.eventosData.plantilla.forEach((evento) => {
                const fechaCalculada = calcularFechaEvento(window.eventosData.fechaInicio, evento);
                agregarEventoDOM(evento.concepto, evento.semana_texto, fechaCalculada, evento.descripcion, true);
            });
            
            $('#btn-cargar-plantilla').fadeOut(300);
            actualizarContadores();
            swal("¡Listo!", "Plantilla cargada correctamente", "success");
        }
    });
}

// 🔥 AGREGAR EVENTO AL DOM
function agregarEventoDOM(concepto, semana, fecha, descripcion, esNuevo) {
    const html = `
        <div class="evento-card ${esNuevo ? 'nuevo' : ''}" data-tipo="nuevo" style="display: none;">
            <div class="evento-top">
                <div class="evento-check">
                    <input type="checkbox" class="check-validacion">
                </div>
                
                <div class="evento-main">
                    <div class="evento-field">
                        <label class="evento-label">Título del Evento</label>
                        <input type="text" class="evento-input evento-concepto campo-concepto" 
                               value="${concepto}" placeholder="VALIDACIÓN DN, ENTREGA DOCS...">
                    </div>
                    
                    <div class="evento-field">
                        <label class="evento-label">Semana</label>
                        <input type="text" class="evento-input campo-semana" 
                               value="${semana}" placeholder="SEMANA 1">
                    </div>
                    
                    <div class="evento-field">
                        <label class="evento-label">Fecha</label>
                        <input type="date" class="evento-input campo-fecha" value="${fecha}">
                    </div>
                </div>
                
                <div class="evento-actions">
                    <button type="button" class="btn-eliminar" title="Eliminar">×</button>
                </div>
            </div>
            
            <div class="evento-desc">
                <div class="evento-field">
                    <label class="evento-label">Descripción</label>
                    <textarea class="evento-input evento-textarea campo-descripcion" 
                              placeholder="Descripción detallada...">${descripcion}</textarea>
                </div>
            </div>
            
            <input type="hidden" class="id-evento" value="">
        </div>
    `;
    
    $('#contenedor-eventos').append(html);
    $('.evento-card.nuevo').last().fadeIn(300);
}

// 🔥 AGREGAR EVENTO MANUAL
function agregarEventoManual() {
    $('.empty-eventos').fadeOut(300);
    const total = $('.evento-card').length;
    const semana = `SEMANA ${total + 1}`;
    const fecha = calcularFechaEvento(window.eventosData.fechaInicio, {
        tipo_calculo: 'offset_dias',
        offset_dias: total * 7
    });
    agregarEventoDOM('', semana, fecha, '', true);
    actualizarContadores();
}

// 🔥 ACTUALIZAR CONTADORES
function actualizarContadores() {
    const total = $('.evento-card').length;
    const completados = $('.evento-card.completado').length;
    $('#total-eventos').text(total);
    $('#completados-eventos').text(completados);
    $('#pendientes-eventos').text(total - completados);
}

// 🔥 HANDLERS
$(document).ready(function() {
    $(document).off('click', '#btn-cargar-plantilla').on('click', '#btn-cargar-plantilla', function(e) {
        e.preventDefault();
        cargarPlantilla();
    });
    
    $(document).off('click', '#btn-agregar-evento').on('click', '#btn-agregar-evento', function(e) {
        e.preventDefault();
        agregarEventoManual();
    });
    
    $(document).off('click', '.btn-eliminar').on('click', '.btn-eliminar', function() {
        const $card = $(this).closest('.evento-card');
        const titulo = $card.find('.campo-concepto').val() || 'este evento';
        
        swal({
            title: "¿Eliminar evento?",
            text: `Se eliminará "${titulo}"`,
            icon: "warning",
            buttons: ["Cancelar", "Eliminar"],
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                $card.fadeOut(300, function() {
                    $(this).remove();
                    actualizarContadores();
                    
                    if ($('.evento-card').length === 0) {
                        const msg = `
                            <div class="empty-eventos">
                                <div class="empty-icon">📅</div>
                                <p class="empty-text">NO HAY EVENTOS REGISTRADOS</p>
                                <p class="empty-hint">HAZ CLIC EN "AGREGAR" PARA COMENZAR</p>
                            </div>
                        `;
                        $('#contenedor-eventos').html(msg);
                        if (window.eventosData.tienePlantilla) {
                            $('#btn-cargar-plantilla').fadeIn(300);
                        }
                    }
                });
            }
        });
    });
    
    $(document).off('change', '.check-validacion').on('change', '.check-validacion', function() {
        const $card = $(this).closest('.evento-card');
        if ($(this).is(':checked')) {
            $card.addClass('completado');
        } else {
            $card.removeClass('completado');
        }
        actualizarContadores();
    });
});
</script>