<?php  
   // FORZAR JSON HEADER DESDE EL INICIO
   header('Content-Type: application/json; charset=utf-8');
   
   require('../inc/cabeceras.php');
   require('../inc/funciones.php');

   // ==================== 🔐 FUNCIÓN PARA VERIFICAR SI ES SUPER ADMIN ====================
   function esSuperAdmin($db, $id_ejecutivo) {
       try {
           // 1️⃣ Obtener los planteles del ejecutivo
           $sqlPlantelesEjecutivo = "
               SELECT id_pla 
               FROM planteles_ejecutivo 
               WHERE id_eje = '$id_ejecutivo'
           ";
           $resultPlantelesEje = mysqli_query($db, $sqlPlantelesEjecutivo);
           
           if(!$resultPlantelesEje || mysqli_num_rows($resultPlantelesEje) == 0) {
               // Intentar con plantel por defecto del ejecutivo
               $sqlPlantelDefault = "SELECT id_pla FROM ejecutivo WHERE id_eje = '$id_ejecutivo'";
               $resultDefault = mysqli_query($db, $sqlPlantelDefault);
               
               if(!$resultDefault || mysqli_num_rows($resultDefault) == 0) {
                   return false;
               }
               
               $ejecutivo = mysqli_fetch_assoc($resultDefault);
               $primerPlantel = $ejecutivo['id_pla'];
               
           } else {
               // Obtener el primer plantel del ejecutivo
               $primerPlantel = mysqli_fetch_assoc($resultPlantelesEje)['id_pla'];
           }
           
           // 2️⃣ Obtener la cadena del plantel
           $sqlCadena = "SELECT id_cad1 FROM plantel WHERE id_pla = '$primerPlantel'";
           $resultCadena = mysqli_query($db, $sqlCadena);
           
           if(!$resultCadena || mysqli_num_rows($resultCadena) == 0) {
               return false;
           }
           
           $plantel = mysqli_fetch_assoc($resultCadena);
           $id_cadena = $plantel['id_cad1'];
           
           // 3️⃣ Contar planteles TOTALES de la cadena
           $sqlTotalCadena = "SELECT COUNT(*) as total FROM plantel WHERE id_cad1 = '$id_cadena'";
           $resultTotalCadena = mysqli_query($db, $sqlTotalCadena);
           $totalCadena = mysqli_fetch_assoc($resultTotalCadena)['total'];
           
           // 4️⃣ Contar planteles que tiene el ejecutivo EN ESA CADENA
           $sqlCoincidencias = "
               SELECT COUNT(*) as coincidencias
               FROM plantel p
               INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
               WHERE p.id_cad1 = '$id_cadena' AND pe.id_eje = '$id_ejecutivo'
           ";
           $resultCoincidencias = mysqli_query($db, $sqlCoincidencias);
           $coincidencias = mysqli_fetch_assoc($resultCoincidencias)['coincidencias'];
           
           // Si no tiene en planteles_ejecutivo, verificar si su plantel default cuenta
           if($coincidencias == 0) {
               $sqlDefaultEnCadena = "
                   SELECT COUNT(*) as coincidencias
                   FROM plantel p
                   INNER JOIN ejecutivo e ON p.id_pla = e.id_pla
                   WHERE p.id_cad1 = '$id_cadena' AND e.id_eje = '$id_ejecutivo'
               ";
               $resultDefault = mysqli_query($db, $sqlDefaultEnCadena);
               $coincidencias = mysqli_fetch_assoc($resultDefault)['coincidencias'];
           }
           
           // 5️⃣ ES SUPER ADMIN si tiene TODOS los planteles de la cadena
           return ($totalCadena > 0 && $coincidencias == $totalCadena);
           
       } catch (Exception $e) {
           error_log("Error verificando Super Admin: " . $e->getMessage());
           return false;
       }
   }

   // ==================== FUNCIÓN PARA REGISTRAR AUDITORÍA DE PAGOS ====================
   function registrarAuditoriaPago($db, $id_pag, $mensaje, $responsable) {
       // Obtener id_alu_ram del pago
       $sqlAlumno = "SELECT id_alu_ram10 FROM pago WHERE id_pag = '$id_pag'";
       $resultadoAlumno = mysqli_query($db, $sqlAlumno);
       
       if($resultadoAlumno && mysqli_num_rows($resultadoAlumno) > 0) {
           $datoAlumno = mysqli_fetch_assoc($resultadoAlumno);
           $id_alu_ram = $datoAlumno['id_alu_ram10'];
           
           $mensaje_escapado = mysqli_real_escape_string($db, $mensaje);
           $responsable_escapado = mysqli_real_escape_string($db, $responsable);
           
           $sqlAuditoria = "
               INSERT INTO observacion_alu_ram (obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram)
               VALUES ('$mensaje_escapado', '$id_alu_ram', '$responsable_escapado')
           ";
           
           mysqli_query($db, $sqlAuditoria);
       }
   }

   // ==================== 🔥 FUNCIÓN PARA REGISTRAR AUDITORÍA DE ABONOS ====================
   function registrarAuditoriaAbono($db, $id_abo_pag, $mensaje, $responsable) {
       // Obtener id_pag del abono
       $sqlPago = "SELECT id_pag1 FROM abono_pago WHERE id_abo_pag = '$id_abo_pag'";
       $resultadoPago = mysqli_query($db, $sqlPago);
       
       if($resultadoPago && mysqli_num_rows($resultadoPago) > 0) {
           $datoPago = mysqli_fetch_assoc($resultadoPago);
           $id_pag = $datoPago['id_pag1'];
           
           // Usar la función de auditoría de pagos
           registrarAuditoriaPago($db, $id_pag, $mensaje, $responsable);
       }
   }

   // ==================== 🔥 FUNCIÓN PARA REMOVER ACENTOS ====================
   function removerAcentos($texto) {
       $acentos = array(
           'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
           'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
           'Ñ' => 'N', 'ñ' => 'n'
       );
       return strtr($texto, $acentos);
   }

   // ==================== 📤🆕 PROCESAR CSV DE ABONOS (VERSIÓN CON SOPORTE CONCEPTOS SIN ABONOS) ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'procesar_csv_abonos') {
       
       // ============================================================
       // FASE 1: VALIDACIONES INICIALES
       // ============================================================
       
       // Validar que se haya enviado un archivo
       if(!isset($_FILES['archivo_csv']) || $_FILES['archivo_csv']['error'] !== UPLOAD_ERR_OK) {
           http_response_code(400);
           echo json_encode([
               'success' => false,
               'mensaje' => 'No se recibió ningún archivo o hubo un error en la carga'
           ]);
           exit;
       }
       
       // Validar que sea un CSV
       $nombreArchivo = $_FILES['archivo_csv']['name'];
       $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
       
       if($extension !== 'csv') {
           http_response_code(400);
           echo json_encode([
               'success' => false,
               'mensaje' => 'El archivo debe ser un CSV'
           ]);
           exit;
       }
       
       // Validar id_alu_ram
       if(!isset($_POST['id_alu_ram']) || empty($_POST['id_alu_ram'])) {
           http_response_code(400);
           echo json_encode([
               'success' => false,
               'mensaje' => 'ID de alumno no especificado'
           ]);
           exit;
       }
       
       $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
       
       // Validar que el alumno existe
       $sqlValidarAlumno = "SELECT id_alu_ram FROM alu_ram WHERE id_alu_ram = '$id_alu_ram'";
       $resultadoValidar = mysqli_query($db, $sqlValidarAlumno);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false,
               'mensaje' => 'Alumno no encontrado'
           ]);
           exit;
       }
       
       // Leer archivo CSV
       $archivoTemporal = $_FILES['archivo_csv']['tmp_name'];
       $handle = fopen($archivoTemporal, 'r');
       
       if(!$handle) {
           http_response_code(500);
           echo json_encode([
               'success' => false,
               'mensaje' => 'No se pudo abrir el archivo CSV'
           ]);
           exit;
       }
       
       // ============================================================
       // FASE 2: VALIDAR ESTRUCTURA DEL CSV
       // ============================================================
       
       // ENCABEZADO ESPERADO (EXACTO)
       $encabezadoEsperado = array(
           'FECHA',
           'HORA',
           'MONTO',
           'ATENDIO',
           'FORMA DE PAGO',
           'BOLSA',
           'CONCEPTO',
           'MES',
           'AÑO',
           'IMPORTE'
       );
       
       // Leer primera línea (encabezado)
       $encabezadoCSV = fgetcsv($handle);
       
       // Limpiar espacios en blanco del encabezado
       $encabezadoCSV = array_map('trim', $encabezadoCSV);
       
       // Validar que el encabezado coincida EXACTAMENTE
       if($encabezadoCSV !== $encabezadoEsperado) {
           fclose($handle);
           http_response_code(400);
           echo json_encode([
               'success' => false,
               'mensaje' => 'Estructura de CSV inválida. El encabezado no coincide con el formato esperado.',
               'encabezado_esperado' => implode(', ', $encabezadoEsperado),
               'encabezado_recibido' => implode(', ', $encabezadoCSV)
           ]);
           exit;
       }
       
       // ============================================================
       // MAPEOS Y CONSTANTES
       // ============================================================
       
       // MAPEO DE MESES (sin acentos)
       $meses = array(
           'ENERO' => '01', 'FEBRERO' => '02', 'MARZO' => '03',
           'ABRIL' => '04', 'MAYO' => '05', 'JUNIO' => '06',
           'JULIO' => '07', 'AGOSTO' => '08', 'SEPTIEMBRE' => '09',
           'OCTUBRE' => '10', 'NOVIEMBRE' => '11', 'DICIEMBRE' => '12'
       );
       
       // MAPEO BOLSA → TIP_PAG
       $mapaBolsa = array(
           'C' => 'Colegiatura',
           'R' => 'Reinscripción',
           'T' => 'Otros',
           'V' => 'Varios'
       );
       
       // ============================================================
       // FASE 3: LEER Y AGRUPAR CSV (CON DETECCIÓN DE CONCEPTOS SOLO)
       // ============================================================
       
       $linea = 0;
       $erroresValidacion = array();
       $conceptosAgrupados = array();
       
       while(($datos = fgetcsv($handle)) !== false) {
           $linea++;
           
           // Validar que tenga exactamente 10 columnas
           if(count($datos) !== 10) {
               $erroresValidacion[] = array(
                   'linea' => $linea,
                   'error' => 'La fila tiene ' . count($datos) . ' columnas, se esperaban 10'
               );
               continue;
           }
           
           // Limpiar espacios
           $datos = array_map('trim', $datos);
           
           // Extraer datos
           $fecha_csv = $datos[0];
           $hora_csv = $datos[1];
           $monto_str = $datos[2];
           $atendio = $datos[3];
           $forma_pago = $datos[4];
           $bolsa = strtoupper($datos[5]);
           $concepto = $datos[6];
           $mes_raw = $datos[7];
           $anio = $datos[8];
           $importe_str = $datos[9];
           
           // ✅ LIMPIAR Y NORMALIZAR MES (uppercase + sin acentos)
           $mes = strtoupper(removerAcentos($mes_raw));
           
           // ============================================================
           // 🔍 DETECCIÓN DE TIPO DE FILA
           // ============================================================
           
           $esConceptoSolo = (
               empty($fecha_csv) &&
               empty($hora_csv) &&
               empty($monto_str) &&
               empty($atendio) &&
               empty($forma_pago)
           );
           
           if($esConceptoSolo) {
               // ============================================================
               // ✅ VALIDAR SOLO: BOLSA, CONCEPTO, MES, AÑO, IMPORTE
               // ============================================================
               
               $erroresFila = array();
               
               // BOLSA
               if(empty($bolsa)) {
                   $erroresFila[] = "BOLSA vacía";
               } elseif(!isset($mapaBolsa[$bolsa])) {
                   $erroresFila[] = "BOLSA inválida: '$bolsa' (válidas: C, R, T, V)";
               }
               
               // CONCEPTO
               if(empty($concepto)) {
                   $erroresFila[] = "CONCEPTO vacío";
               } elseif(strlen($concepto) > 100) {
                   $erroresFila[] = "CONCEPTO excede 100 caracteres";
               }
               
               // MES
               if(empty($mes)) {
                   $erroresFila[] = "MES vacío";
               } elseif(!isset($meses[$mes])) {
                   $erroresFila[] = "MES inválido: '$mes' (debe ser MAYÚSCULAS sin acentos)";
               }
               
               // AÑO
               if(empty($anio)) {
                   $erroresFila[] = "AÑO vacío";
               } elseif(!preg_match('/^\d{4}$/', $anio)) {
                   $erroresFila[] = "AÑO inválido: '$anio' (4 dígitos)";
               } else {
                   $anio_num = intval($anio);
                   if($anio_num < 2000 || $anio_num > 2099) {
                       $erroresFila[] = "AÑO fuera de rango: '$anio' (2000-2099)";
                   }
               }
               
               // IMPORTE
               $importe_limpio = str_replace(',', '', $importe_str);
               if(empty($importe_limpio)) {
                   $erroresFila[] = "IMPORTE vacío";
               } elseif(!is_numeric($importe_limpio)) {
                   $erroresFila[] = "IMPORTE no es número: '$importe_str'";
               } else {
                   $importe_num = floatval($importe_limpio);
                   if($importe_num <= 0) {
                       $erroresFila[] = "IMPORTE debe ser > 0: '$importe_str'";
                   }
               }
               
               if(count($erroresFila) > 0) {
                   $erroresValidacion[] = array(
                       'linea' => $linea,
                       'error' => "CONCEPTO SOLO - " . implode(', ', $erroresFila)
                   );
                   continue;
               }
               
               // ✅ FILA VÁLIDA - CONCEPTO SIN ABONOS
               $claveUnica = $concepto . '_' . $mes . '_' . $anio . '_' . $bolsa;
               
               if(!isset($conceptosAgrupados[$claveUnica])) {
                   $conceptosAgrupados[$claveUnica] = array(
                       'concepto' => $concepto,
                       'mes' => $mes,
                       'anio' => $anio,
                       'bolsa' => $bolsa,
                       'tip_pag' => $mapaBolsa[$bolsa],
                       'importe_total' => floatval($importe_limpio),
                       'mes_numero' => $meses[$mes],
                       'es_concepto_solo' => true,
                       'abonos' => array()
                   );
               }
               
           } else {
               // ============================================================
               // ✅ VALIDAR TODAS LAS 10 COLUMNAS (ABONO COMPLETO)
               // ============================================================
               
               $erroresFila = array();
               
               // FECHA
               if(!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fecha_csv)) {
                   $erroresFila[] = "FECHA inválida: '$fecha_csv'";
               } else {
                   // Validar que la fecha exista
                   $partes = explode('/', $fecha_csv);
                   $dia = intval($partes[0]);
                   $mes_fecha = intval($partes[1]);
                   $anio_fecha = intval($partes[2]);
                   
                   if(!checkdate($mes_fecha, $dia, $anio_fecha)) {
                       $erroresFila[] = "FECHA no existe: '$fecha_csv'";
                   }
               }
               
               // HORA
               if(stripos($hora_csv, 'a. m.') === false && 
                  stripos($hora_csv, 'p. m.') === false &&
                  stripos($hora_csv, 'a.m.') === false && 
                  stripos($hora_csv, 'p.m.') === false) {
                   $erroresFila[] = "HORA sin formato: '$hora_csv'";
               }
               
               // MONTO
               $monto_limpio = str_replace(',', '', $monto_str);
               if(!is_numeric($monto_limpio) || floatval($monto_limpio) <= 0) {
                   $erroresFila[] = "MONTO inválido: '$monto_str'";
               }
               
               // ATENDIO
               if(empty($atendio)) {
                   $erroresFila[] = "ATENDIO vacío";
               } elseif(strlen($atendio) > 100) {
                   $erroresFila[] = "ATENDIO excede 100 caracteres";
               }
               
               // FORMA DE PAGO
               $formas_validas = array('Efectivo','Depósito','Tarjeta','Otro','Transferencia','Crédito','Débito','Efectivo STP','TPV');
               if(empty($forma_pago)) {
                   $erroresFila[] = "FORMA DE PAGO vacía";
               } elseif(!in_array($forma_pago, $formas_validas)) {
                   $erroresFila[] = "FORMA DE PAGO inválida: '$forma_pago'";
               }
               
               // BOLSA
               if(empty($bolsa)) {
                   $erroresFila[] = "BOLSA vacía";
               } elseif(!isset($mapaBolsa[$bolsa])) {
                   $erroresFila[] = "BOLSA inválida: '$bolsa'";
               }
               
               // CONCEPTO
               if(empty($concepto)) {
                   $erroresFila[] = "CONCEPTO vacío";
               } elseif(strlen($concepto) > 100) {
                   $erroresFila[] = "CONCEPTO excede 100 caracteres";
               }
               
               // MES
               if(empty($mes)) {
                   $erroresFila[] = "MES vacío";
               } elseif(!isset($meses[$mes])) {
                   $erroresFila[] = "MES inválido: '$mes'";
               }
               
               // AÑO
               if(empty($anio)) {
                   $erroresFila[] = "AÑO vacío";
               } elseif(!preg_match('/^\d{4}$/', $anio)) {
                   $erroresFila[] = "AÑO inválido: '$anio'";
               } else {
                   $anio_num = intval($anio);
                   if($anio_num < 2000 || $anio_num > 2099) {
                       $erroresFila[] = "AÑO fuera de rango: '$anio'";
                   }
               }
               
               // IMPORTE
               $importe_limpio = str_replace(',', '', $importe_str);
               if(!is_numeric($importe_limpio) || floatval($importe_limpio) <= 0) {
                   $erroresFila[] = "IMPORTE inválido: '$importe_str'";
               }
               
               if(count($erroresFila) > 0) {
                   $erroresValidacion[] = array(
                       'linea' => $linea,
                       'error' => "ABONO - " . implode(', ', $erroresFila)
                   );
                   continue;
               }
               
               // ✅ FILA VÁLIDA - ABONO COMPLETO
               $claveUnica = $concepto . '_' . $mes . '_' . $anio . '_' . $bolsa;
               
               // Si el concepto ya existe como "solo concepto", cambiar flag
               if(isset($conceptosAgrupados[$claveUnica]) && $conceptosAgrupados[$claveUnica]['es_concepto_solo']) {
                   $conceptosAgrupados[$claveUnica]['es_concepto_solo'] = false;
               }
               
               if(!isset($conceptosAgrupados[$claveUnica])) {
                   $conceptosAgrupados[$claveUnica] = array(
                       'concepto' => $concepto,
                       'mes' => $mes,
                       'anio' => $anio,
                       'bolsa' => $bolsa,
                       'tip_pag' => $mapaBolsa[$bolsa],
                       'importe_total' => floatval($importe_limpio),
                       'mes_numero' => $meses[$mes],
                       'es_concepto_solo' => false,
                       'abonos' => array()
                   );
               }
               
               // Agregar abono al grupo
               $conceptosAgrupados[$claveUnica]['abonos'][] = array(
                   'linea' => $linea,
                   'fecha_csv' => $fecha_csv,
                   'hora_csv' => $hora_csv,
                   'monto' => floatval($monto_limpio),
                   'atendio' => $atendio,
                   'forma_pago' => $forma_pago
               );
           }
       }
       
       fclose($handle);
       
       // ============================================================
       // VALIDAR QUE NO HAYA ERRORES
       // ============================================================
       
       if(count($erroresValidacion) > 0) {
           http_response_code(400);
           echo json_encode([
               'success' => false,
               'mensaje' => 'El CSV contiene ' . count($erroresValidacion) . ' error(es) de validación',
               'errores_validacion' => $erroresValidacion,
               'conceptos_validos' => count($conceptosAgrupados)
           ]);
           exit;
       }
       
       if(count($conceptosAgrupados) == 0) {
           http_response_code(400);
           echo json_encode([
               'success' => false,
               'mensaje' => 'No hay conceptos válidos para procesar'
           ]);
           exit;
       }
       
       // ============================================================
       // FASE 4: ELIMINAR ABONOS EXISTENTES
       // ============================================================
       
       // 1️⃣ Obtener todos los conceptos de pago del alumno
       $sqlPagos = "
           SELECT id_pag, con_pag 
           FROM pago 
           WHERE id_alu_ram10 = '$id_alu_ram'
       ";
       $resultPagos = mysqli_query($db, $sqlPagos);
       
       $totalAbonosEliminados = 0;
       $conceptosAfectados = array();
       
       while($pago = mysqli_fetch_assoc($resultPagos)) {
           $id_pag = $pago['id_pag'];
           $con_pag = $pago['con_pag'];
           
           // Contar cuántos abonos tiene este concepto
           $sqlCount = "SELECT COUNT(*) as total FROM abono_pago WHERE id_pag1 = '$id_pag'";
           $resultCount = mysqli_query($db, $sqlCount);
           $totalAbonos = mysqli_fetch_assoc($resultCount)['total'];
           
           if($totalAbonos > 0) {
               // ELIMINAR todos los abonos del concepto
               $sqlDelete = "DELETE FROM abono_pago WHERE id_pag1 = '$id_pag'";
               mysqli_query($db, $sqlDelete);
               
               $totalAbonosEliminados += $totalAbonos;
               $conceptosAfectados[] = "$con_pag ($totalAbonos abonos)";
               
               // 📝 AUDITORÍA: Abonos eliminados
               $mensaje = "🗑️ CSV IMPORT - Eliminados $totalAbonos abono(s) previo(s) del concepto [$con_pag]";
               registrarAuditoriaPago($db, $id_pag, $mensaje, $nombreCompleto);
           }
       }
       
       // 2️⃣ RESETEAR todas las deudas a su monto original
       $sqlReset = "
           UPDATE pago 
           SET mon_pag = mon_ori_pag,
               est_pag = 'Pendiente',
               pag_pag = NULL
           WHERE id_alu_ram10 = '$id_alu_ram'
       ";
       mysqli_query($db, $sqlReset);
       
       // 📝 AUDITORÍA GENERAL: Proceso de eliminación
       $totalAbonosNuevos = 0;
       $totalConceptosSolo = 0;
       
       foreach($conceptosAgrupados as $grupo) {
           $totalAbonosNuevos += count($grupo['abonos']);
           if($grupo['es_concepto_solo']) {
               $totalConceptosSolo++;
           }
       }
       
       $sqlAuditoriaGeneral = "
           INSERT INTO observacion_alu_ram (obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram)
           VALUES (
               '🗑️ CSV IMPORT - ELIMINACIÓN MASIVA: $totalAbonosEliminados abono(s) eliminado(s). Preparando importación: " . count($conceptosAgrupados) . " concepto(s) | $totalAbonosNuevos abono(s) | $totalConceptosSolo concepto(s) sin abonos.',
               '$id_alu_ram',
               '$nombreCompleto'
           )
       ";
       mysqli_query($db, $sqlAuditoriaGeneral);
       
       // ============================================================
       // FASE 5: CREAR CONCEPTOS + INSERTAR ABONOS (CON SOPORTE CONCEPTOS SOLO)
       // ============================================================
       
       $conceptosCreados = 0;
       $conceptosSoloCreados = 0;
       $abonosInsertados = 0;
       $errores = 0;
       $detalles = array();
       
       foreach($conceptosAgrupados as $claveUnica => $grupo) {
           // ============================================================
           // A. CREAR PAGO (CONCEPTO)
           // ============================================================
           
           $concepto = mysqli_real_escape_string($db, $grupo['concepto']);
           $tip_pag = $grupo['tip_pag'];
           $importe_total = $grupo['importe_total'];
           $mes_numero = $grupo['mes_numero'];
           $anio = $grupo['anio'];
           
           // Construir fechas: ini_pag y fin_pag
           $ini_pag = "$anio-$mes_numero-01";
           $fin_pag = "$anio-$mes_numero-05";
           $fec_pag = date('Y-m-d'); // Fecha actual
           
           // Insertar concepto en pago (SIEMPRE PENDIENTE INICIALMENTE)
           $sqlCrearPago = "
               INSERT INTO pago (
                   fec_pag,
                   id_alu_ram10,
                   con_pag,
                   tip_pag,
                   mon_pag,
                   mon_ori_pag,
                   ini_pag,
                   fin_pag,
                   est_pag,
                   pag_pag,
                   obs_pag
               ) VALUES (
                   '$fec_pag',
                   '$id_alu_ram',
                   '$concepto',
                   '$tip_pag',
                   $importe_total,
                   $importe_total,
                   '$ini_pag',
                   '$fin_pag',
                   'Pendiente',
                   NULL,
                   'Importado desde CSV'
               )
           ";
           
           $resultadoCrearPago = mysqli_query($db, $sqlCrearPago);
           
           if(!$resultadoCrearPago) {
               $errores++;
               $detalles[] = array(
                   'concepto' => $concepto,
                   'estado' => 'error',
                   'mensaje' => 'Error al crear concepto: ' . mysqli_error($db)
               );
               continue;
           }
           
           // Obtener ID del pago recién creado
           $id_pag_nuevo = mysqli_insert_id($db);
           $conceptosCreados++;
           
           // ============================================================
           // B. INSERTAR ABONOS (SOLO SI EXISTEN)
           // ============================================================
           
           if(count($grupo['abonos']) > 0) {
               // TIENE ABONOS - PROCESARLOS
               $total_abonado = 0;
               $ultima_fecha_abono = null;
               
               foreach($grupo['abonos'] as $abono) {
                   // 1️⃣ CONVERTIR FECHA (dd/mm/yyyy → yyyy-mm-dd)
                   $fecha_partes = explode('/', $abono['fecha_csv']);
                   $dia = str_pad($fecha_partes[0], 2, '0', STR_PAD_LEFT);
                   $mes_fecha = str_pad($fecha_partes[1], 2, '0', STR_PAD_LEFT);
                   $anio_fecha = $fecha_partes[2];
                   $fec_abo_pag = "$anio_fecha-$mes_fecha-$dia";
                   
                   // 2️⃣ CONVERTIR HORA (12h → 24h)
                   $hora_limpia = str_replace(array('a. m.', 'p. m.', 'a.m.', 'p.m.'), '', $abono['hora_csv']);
                   $hora_limpia = trim($hora_limpia);
                   
                   $es_pm = (stripos($abono['hora_csv'], 'p. m.') !== false || stripos($abono['hora_csv'], 'p.m.') !== false);
                   
                   $hora_partes = explode(':', $hora_limpia);
                   if(count($hora_partes) === 3) {
                       $hora = intval($hora_partes[0]);
                       $minutos = $hora_partes[1];
                       $segundos = $hora_partes[2];
                       
                       // Convertir a 24h
                       if($es_pm && $hora !== 12) {
                           $hora += 12;
                       } elseif(!$es_pm && $hora === 12) {
                           $hora = 0;
                       }
                       
                       $hor_abo_pag = str_pad($hora, 2, '0', STR_PAD_LEFT) . ":$minutos:$segundos";
                   } else {
                       $hor_abo_pag = '00:00:00';
                   }
                   
                   // 3️⃣ INSERTAR ABONO
                   $monto_abono = $abono['monto'];
                   $atendio_escapado = mysqli_real_escape_string($db, $abono['atendio']);
                   $forma_pago_escapada = mysqli_real_escape_string($db, $abono['forma_pago']);
                   
                   $sqlInsertarAbono = "
                       INSERT INTO abono_pago (
                           id_pag1,
                           fec_abo_pag,
                           hor_abo_pag,
                           mon_abo_pag,
                           tip_abo_pag,
                           res_abo_pag
                       ) VALUES (
                           '$id_pag_nuevo',
                           '$fec_abo_pag',
                           '$hor_abo_pag',
                           $monto_abono,
                           '$forma_pago_escapada',
                           '$atendio_escapado'
                       )
                   ";
                   
                   $resultadoInsertarAbono = mysqli_query($db, $sqlInsertarAbono);
                   
                   if(!$resultadoInsertarAbono) {
                       $errores++;
                       $detalles[] = array(
                           'linea' => $abono['linea'],
                           'estado' => 'error',
                           'mensaje' => 'Error al insertar abono: ' . mysqli_error($db)
                       );
                       continue;
                   }
                   
                   $abonosInsertados++;
                   $total_abonado += $monto_abono;
                   $ultima_fecha_abono = $fec_abo_pag;
               }
               
               // ============================================================
               // C. ACTUALIZAR DEUDA DEL PAGO (SOLO SI TIENE ABONOS)
               // ============================================================
               
               $deuda_restante = $importe_total - $total_abonado;
               
               if($deuda_restante <= 0) {
                   // PAGO LIQUIDADO
                   $sqlActualizar = "
                       UPDATE pago 
                       SET mon_pag = 0,
                           est_pag = 'Pagado',
                           pag_pag = '$ultima_fecha_abono'
                       WHERE id_pag = '$id_pag_nuevo'
                   ";
               } else {
                   // PAGO PARCIAL
                   $sqlActualizar = "
                       UPDATE pago 
                       SET mon_pag = $deuda_restante
                       WHERE id_pag = '$id_pag_nuevo'
                   ";
               }
               
               mysqli_query($db, $sqlActualizar);
               
               // 📝 AUDITORÍA
               $estado_final = ($deuda_restante <= 0) ? 'LIQUIDADO' : 'PARCIAL';
               $mensaje_auditoria = "📤 CSV IMPORT - Concepto con abonos: [$concepto] | Tipo: $tip_pag | Importe: $" . number_format($importe_total, 2) . 
                                   " | Abonos: " . count($grupo['abonos']) . " ($" . number_format($total_abonado, 2) . ")" .
                                   " | Estado: $estado_final";
               
               registrarAuditoriaPago($db, $id_pag_nuevo, $mensaje_auditoria, $nombreCompleto);
               
               $detalles[] = array(
                   'concepto' => $concepto,
                   'estado' => 'ok',
                   'tipo' => 'con_abonos',
                   'mensaje' => "Concepto creado con " . count($grupo['abonos']) . " abono(s) - Total: $" . number_format($total_abonado, 2) . " de $" . number_format($importe_total, 2)
               );
               
           } else {
               // ============================================================
               // NO TIENE ABONOS - ES CONCEPTO SOLO (PENDIENTE)
               // ============================================================
               
               $conceptosSoloCreados++;
               
               // 📝 AUDITORÍA
               $mensaje_auditoria = "📋 CSV IMPORT - Concepto sin abonos (PENDIENTE): [$concepto] | Tipo: $tip_pag | Importe: $" . number_format($importe_total, 2) . " | Estado: Pendiente | Deuda completa";
               
               registrarAuditoriaPago($db, $id_pag_nuevo, $mensaje_auditoria, $nombreCompleto);
               
               $detalles[] = array(
                   'concepto' => $concepto,
                   'estado' => 'ok',
                   'tipo' => 'concepto_solo',
                   'mensaje' => "Concepto pendiente creado sin abonos - Deuda: $" . number_format($importe_total, 2)
               );
           }
       }
       
       // ============================================================
       // FASE 6: RESPUESTA FINAL
       // ============================================================
       
       http_response_code(200);
       echo json_encode([
           'success' => true,
           'mensaje' => "Procesamiento completado: $conceptosCreados concepto(s) creado(s), $abonosInsertados abono(s) insertado(s), $conceptosSoloCreados concepto(s) sin abonos",
           'conceptos_creados' => $conceptosCreados,
           'conceptos_solo_creados' => $conceptosSoloCreados,
           'abonos_insertados' => $abonosInsertados,
           'errores' => $errores,
           'total_conceptos_procesados' => count($conceptosAgrupados),
           'abonos_eliminados' => $totalAbonosEliminados,
           'conceptos_reseteados' => count($conceptosAfectados),
           'detalles' => $detalles
       ]);
       exit;
   }

   // ==================== CREAR NUEVO CONCEPTO "VARIOS" (DEFAULT) ====================
   if(isset($_POST['id_alu_ram']) && !isset($_POST['id_pag']) && !isset($_POST['campo']) && !isset($_POST['accion'])) {
       $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
       
       // Validar que el alumno existe
       $sqlValidar = "SELECT id_alu_ram FROM alu_ram WHERE id_alu_ram = '$id_alu_ram'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Alumno no encontrado'
           ]);
           exit;
       }
       
       // Calcular fechas
       $fechaHoy = date('Y-m-d');
       $fechaInicio = date('Y-m-d', strtotime('-5 days'));
       
       // Crear nuevo concepto "Varios"
       $sqlCrear = "INSERT INTO pago (
           fec_pag,
           id_alu_ram10, 
           con_pag, 
           tip_pag, 
           mon_pag, 
           mon_ori_pag,
           ini_pag, 
           fin_pag, 
           est_pag,
           obs_pag
       ) VALUES (
           '$fechaHoy',
           '$id_alu_ram',
           'Sin definir',
           'Varios',
           1,
           1,
           '$fechaInicio',
           '$fechaHoy',
           'Pendiente',
           '---'
       )";
       
       $resultadoCrear = mysqli_query($db, $sqlCrear);
       
       if($resultadoCrear) {
           $id_pago_nuevo = mysqli_insert_id($db);
           
           // 📝 AUDITORÍA: Nuevo concepto creado
           $mensaje_auditoria = "➕ NUEVO CONCEPTO PAGO - Tipo: Varios | Monto: $1.00 | Vigencia: " . fechaFormateadaCompacta2($fechaInicio) . " - " . fechaFormateadaCompacta2($fechaHoy) . " | Estado: Pendiente";
           registrarAuditoriaPago($db, $id_pago_nuevo, $mensaje_auditoria, $nombreCompleto);
           
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Nuevo concepto creado exitosamente',
               'id_pago_nuevo' => $id_pago_nuevo
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al crear concepto: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== CREAR CONCEPTO DESDE CATÁLOGO ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'crear_concepto_catalogo') {
       $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
       $concepto = mysqli_real_escape_string($db, $_POST['concepto']);
       $monto = floatval($_POST['monto']);
       $tipo_pago = mysqli_real_escape_string($db, $_POST['tipo_pago']);
       
       // Validar alumno
       $sqlValidar = "SELECT id_alu_ram FROM alu_ram WHERE id_alu_ram = '$id_alu_ram'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Alumno no encontrado'
           ]);
           exit;
       }
       
       // Validar monto
       if($monto <= 0) {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'El monto debe ser mayor a cero'
           ]);
           exit;
       }
       
       // Calcular fechas
       $fechaHoy = date('Y-m-d');
       $fechaInicio = date('Y-m-d', strtotime('-5 days'));
       
       // Crear concepto
       $sqlCrear = "INSERT INTO pago (
           fec_pag,
           id_alu_ram10, 
           con_pag, 
           tip_pag, 
           mon_pag, 
           mon_ori_pag,
           ini_pag, 
           fin_pag, 
           est_pag,
           obs_pag
       ) VALUES (
           '$fechaHoy',
           '$id_alu_ram',
           '$concepto',
           '$tipo_pago',
           $monto,
           $monto,
           '$fechaInicio',
           '$fechaHoy',
           'Pendiente',
           '---'
       )";
       
       $resultadoCrear = mysqli_query($db, $sqlCrear);
       
       if($resultadoCrear) {
           $id_pago_nuevo = mysqli_insert_id($db);
           
           // 📝 AUDITORÍA: Concepto desde catálogo
           $mensaje_auditoria = "➕ CONCEPTO CATÁLOGO - [$concepto] | Tipo: $tipo_pago | Monto: $" . number_format($monto, 2) . " | Vigencia: " . fechaFormateadaCompacta2($fechaInicio) . " - " . fechaFormateadaCompacta2($fechaHoy) . " | Estado: Pendiente";
           registrarAuditoriaPago($db, $id_pago_nuevo, $mensaje_auditoria, $nombreCompleto);
           
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Concepto creado exitosamente',
               'id_pago_nuevo' => $id_pago_nuevo,
               'concepto' => $concepto,
               'monto' => $monto
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al crear concepto: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== 🔥 ACTUALIZAR PAGO EXISTENTE (PERMITE PAGADOS) ====================
   if(isset($_POST['id_pag']) && isset($_POST['campo']) && isset($_POST['valor']) && !isset($_POST['accion'])) {
       $id_pag = mysqli_real_escape_string($db, $_POST['id_pag']);
       $campo = mysqli_real_escape_string($db, $_POST['campo']);
       $valor = mysqli_real_escape_string($db, $_POST['valor']);
       $valor_anterior = isset($_POST['valor_anterior']) ? mysqli_real_escape_string($db, $_POST['valor_anterior']) : '';
       
       // Validar que el pago existe
       $sqlValidar = "SELECT est_pag, con_pag, tip_pag, mon_pag, ini_pag, fin_pag, obs_pag FROM pago WHERE id_pag = '$id_pag'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Pago no encontrado'
           ]);
           exit;
       }
       
       $datoPago = mysqli_fetch_assoc($resultadoValidar);
       
       // Identificar si es un pago PAGADO para auditoría especial
       $esPagoPagado = ($datoPago['est_pag'] === 'Pagado');
       $prefijo_auditoria = $esPagoPagado ? "⚠️ EDICIÓN PAGO PAGADO - " : "";
       
       // Mapear campos y validaciones
       $camposBD = [
           'concepto' => 'con_pag',
           'monto' => 'mon_pag',
           'inicio' => 'ini_pag',
           'vigencia' => 'fin_pag',
           'fecha_pago' => 'pag_pag',
           'tipo' => 'tip_pag',
           'observaciones' => 'obs_pag',
           'estado' => 'est_pag'
       ];
       
       if(!isset($camposBD[$campo])) {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Campo no válido'
           ]);
           exit;
       }
       
       $campoBD = $camposBD[$campo];
       $mensaje_auditoria = "";
       
       // Validaciones específicas y construcción de query + mensaje de auditoría
       if($campo === 'monto') {
           if(!is_numeric($valor) || floatval($valor) < 0) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'El monto debe ser un número positivo'
               ]);
               exit;
           }
           // También actualizar mon_ori_pag cuando se cambia mon_pag
           $sqlActualizar = "UPDATE pago SET 
               $campoBD = '$valor', 
               mon_ori_pag = '$valor' 
               WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de monto
           $mensaje_auditoria = $prefijo_auditoria . "💰 CAMBIO MONTO PAGO - De: $" . number_format($valor_anterior, 2) . " → A: $" . number_format($valor, 2) . " | Concepto: [{$datoPago['con_pag']}]";
               
       } elseif($campo === 'inicio') {
           if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Formato de fecha inválido'
               ]);
               exit;
           }
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de fecha inicio
           $mensaje_auditoria = $prefijo_auditoria . "📅 CAMBIO FECHA INICIO PAGO - De: " . fechaFormateadaCompacta2($valor_anterior) . " → A: " . fechaFormateadaCompacta2($valor) . " | Concepto: [{$datoPago['con_pag']}]";
           
       } elseif($campo === 'vigencia') {
           if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Formato de fecha inválido'
               ]);
               exit;
           }
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de vigencia
           $mensaje_auditoria = $prefijo_auditoria . "📅 CAMBIO VIGENCIA PAGO - De: " . fechaFormateadaCompacta2($valor_anterior) . " → A: " . fechaFormateadaCompacta2($valor) . " | Concepto: [{$datoPago['con_pag']}]";
           
       } elseif($campo === 'fecha_pago') {
           // Permitir vacío para limpiar fecha de pago
           if($valor !== '' && $valor !== '0000-00-00' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Formato de fecha inválido'
               ]);
               exit;
           }
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de fecha de pago
           $fecha_anterior_formateada = ($valor_anterior && $valor_anterior !== '0000-00-00') ? fechaFormateadaCompacta2($valor_anterior) : 'Sin fecha';
           $fecha_nueva_formateada = ($valor && $valor !== '0000-00-00') ? fechaFormateadaCompacta2($valor) : 'Sin fecha';
           $mensaje_auditoria = $prefijo_auditoria . "📅 CAMBIO FECHA PAGO - De: $fecha_anterior_formateada → A: $fecha_nueva_formateada | Concepto: [{$datoPago['con_pag']}]";
           
       } elseif($campo === 'tipo') {
           $tiposValidos = ['Colegiatura', 'Inscripción', 'Reinscripción', 'Varios', 'Otros'];
           if(!in_array($valor, $tiposValidos)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Tipo de pago no válido'
               ]);
               exit;
           }
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de tipo
           $mensaje_auditoria = $prefijo_auditoria . "🏷️ CAMBIO TIPO PAGO - De: {$valor_anterior} → A: {$valor} | Concepto: [{$datoPago['con_pag']}]";
           
       } elseif($campo === 'concepto') {
           if(empty($valor)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'El concepto no puede estar vacío'
               ]);
               exit;
           }
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de concepto
           $mensaje_auditoria = $prefijo_auditoria . "✏️ CAMBIO CONCEPTO PAGO - De: [{$valor_anterior}] → A: [{$valor}]";
           
       } elseif($campo === 'observaciones') {
           // Las observaciones pueden estar vacías
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de observaciones
           $obs_anterior = empty($valor_anterior) ? '(vacío)' : $valor_anterior;
           $obs_nueva = empty($valor) ? '(vacío)' : $valor;
           $mensaje_auditoria = $prefijo_auditoria . "📋 CAMBIO OBSERVACIONES PAGO - De: {$obs_anterior} → A: {$obs_nueva} | Concepto: [{$datoPago['con_pag']}]";
           
       } elseif($campo === 'estado') {
           // Solo permitir Pagado o Pendiente (Vencido es calculado por fecha)
           $estadosValidos = ['Pagado', 'Pendiente'];
           if(!in_array($valor, $estadosValidos)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Estado no válido. Solo se permite: Pagado o Pendiente'
               ]);
               exit;
           }
           $sqlActualizar = "UPDATE pago SET $campoBD = '$valor' WHERE id_pag = '$id_pag'";
           
           // 📝 AUDITORÍA: Cambio de estado
           $mensaje_auditoria = $prefijo_auditoria . "🔄 CAMBIO ESTADO PAGO - De: {$valor_anterior} → A: {$valor} | Concepto: [{$datoPago['con_pag']}] | Monto: $" . number_format($datoPago['mon_pag'], 2);
           
       } else {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Operación no soportada'
           ]);
           exit;
       }
       
       // Ejecutar actualización
       $resultadoActualizar = mysqli_query($db, $sqlActualizar);
       
       if($resultadoActualizar) {
           // Registrar auditoría
           registrarAuditoriaPago($db, $id_pag, $mensaje_auditoria, $nombreCompleto);
           
           // Preparar respuesta con fecha formateada si aplica
           $respuesta = [
               'success' => true, 
               'mensaje' => 'Campo actualizado correctamente',
               'campo' => $campo,
               'valor_nuevo' => $valor,
               'valor_anterior' => $valor_anterior
           ];
           
           // Agregar valor formateado para fechas
           if($campo === 'vigencia' || $campo === 'inicio') {
               $respuesta['valor_formateado'] = fechaFormateadaCompacta2($valor);
           } elseif($campo === 'fecha_pago') {
               $respuesta['valor_formateado'] = ($valor && $valor !== '0000-00-00') ? fechaFormateadaCompacta2($valor) : null;
           }
           
           http_response_code(200);
           echo json_encode($respuesta);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al actualizar: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== REGISTRAR ABONO ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'registrar_abono') {
       $id_pag = mysqli_real_escape_string($db, $_POST['id_pag']);
       $monto_abono = floatval($_POST['monto_abono']);
       $tipo_pago = mysqli_real_escape_string($db, $_POST['tipo_pago']);
       $fecha_abono = mysqli_real_escape_string($db, $_POST['fecha_abono']);
       
       // Validar que el pago existe
       $sqlValidar = "SELECT mon_pag, con_pag FROM pago WHERE id_pag = '$id_pag'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Pago no encontrado'
           ]);
           exit;
       }
       
       $datoPago = mysqli_fetch_assoc($resultadoValidar);
       
       // Validar monto
       if($monto_abono <= 0) {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'El monto del abono debe ser mayor a cero'
           ]);
           exit;
       }
       
       // Calcular total de abonos existentes
       $sqlSumaAbonos = "SELECT COALESCE(SUM(mon_abo_pag), 0) as total_abonos FROM abono_pago WHERE id_pag1 = '$id_pag'";
       $resultadoSuma = mysqli_query($db, $sqlSumaAbonos);
       $totalAbonos = mysqli_fetch_assoc($resultadoSuma)['total_abonos'];
       
       // Calcular nuevo total
       $nuevoTotal = $totalAbonos + $monto_abono;
       
       // Validar que no exceda el monto total
       if($nuevoTotal > $datoPago['mon_pag']) {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'El abono excede el monto total del pago'
           ]);
           exit;
       }
       
       // Insertar abono
       $sqlAbono = "INSERT INTO abono_pago (
           id_pag1,
           fec_abo_pag,
           mon_abo_pag,
           tip_abo_pag,
           res_abo_pag
       ) VALUES (
           '$id_pag',
           '$fecha_abono',
           $monto_abono,
           '$tipo_pago',
           '$nombreCompleto'
       )";
       
       $resultadoAbono = mysqli_query($db, $sqlAbono);
       
       if($resultadoAbono) {
           // Si el nuevo total iguala el monto, marcar como pagado
           if($nuevoTotal == $datoPago['mon_pag']) {
               $sqlActualizarEstado = "UPDATE pago SET est_pag = 'Pagado', pag_pag = '$fecha_abono' WHERE id_pag = '$id_pag'";
               mysqli_query($db, $sqlActualizarEstado);
               
               // 📝 AUDITORÍA: Pago completado con abonos
               $mensaje_auditoria = "💰 PAGO COMPLETADO CON ABONOS - Total: $" . number_format($nuevoTotal, 2) . " | Concepto: [{$datoPago['con_pag']}] | Último abono: $" . number_format($monto_abono, 2);
           } else {
               // 📝 AUDITORÍA: Abono registrado
               $mensaje_auditoria = "💵 ABONO REGISTRADO - Monto: $" . number_format($monto_abono, 2) . " | Tipo: $tipo_pago | Total abonado: $" . number_format($nuevoTotal, 2) . " de $" . number_format($datoPago['mon_pag'], 2) . " | Concepto: [{$datoPago['con_pag']}]";
           }
           
           registrarAuditoriaPago($db, $id_pag, $mensaje_auditoria, $nombreCompleto);
           
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Abono registrado correctamente',
               'pago_completado' => ($nuevoTotal == $datoPago['mon_pag'])
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al registrar abono: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== ELIMINAR PAGO ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
       $id_pag = mysqli_real_escape_string($db, $_POST['id_pag']);
       
       // Validar que el pago existe
       $sqlValidar = "SELECT est_pag, con_pag, mon_pag FROM pago WHERE id_pag = '$id_pag'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Pago no encontrado'
           ]);
           exit;
       }
       
       $datoPago = mysqli_fetch_assoc($resultadoValidar);
       
       // Validar permisos: Solo Super Admin puede eliminar pagos PAGADOS
       if($datoPago['est_pag'] === 'Pagado') {
           $esSuperAdminActual = esSuperAdmin($db, $id);
           if(!$esSuperAdminActual) {
               http_response_code(403);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => '🔑 Solo Super Admin puede eliminar pagos PAGADOS'
               ]);
               exit;
           }
       }
       
       // Primero eliminar abonos asociados
       $sqlEliminarAbonos = "DELETE FROM abono_pago WHERE id_pag1 = '$id_pag'";
       mysqli_query($db, $sqlEliminarAbonos);
       
       // 📝 AUDITORÍA antes de eliminar
       $mensaje_auditoria = "🗑️ PAGO ELIMINADO - Concepto: [{$datoPago['con_pag']}] | Monto: $" . number_format($datoPago['mon_pag'], 2) . " | Estado: {$datoPago['est_pag']}";
       registrarAuditoriaPago($db, $id_pag, $mensaje_auditoria, $nombreCompleto);
       
       // Eliminar pago
       $sqlEliminar = "DELETE FROM pago WHERE id_pag = '$id_pag'";
       $resultadoEliminar = mysqli_query($db, $sqlEliminar);
       
       if($resultadoEliminar) {
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Pago eliminado permanentemente',
               'es_super_admin' => ($datoPago['est_pag'] === 'Pagado')
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al eliminar: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

    // ==================== 🔥 ELIMINAR TODOS LOS PAGOS DE UN ALUMNO ====================
    if(isset($_POST['accion']) && $_POST['accion'] === 'eliminar_todos_pagos_alumno') {
        $id_alu_ram = mysqli_real_escape_string($db, $_POST['id_alu_ram']);
        
        // Validar que el alumno existe
        $sqlValidarAlumno = "SELECT id_alu_ram FROM alu_ram WHERE id_alu_ram = '$id_alu_ram'";
        $resultadoValidarAlumno = mysqli_query($db, $sqlValidarAlumno);
        
        if(!$resultadoValidarAlumno || mysqli_num_rows($resultadoValidarAlumno) == 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Alumno no encontrado'
            ]);
            exit;
        }
        
        // Obtener TODOS los pagos del alumno EXCEPTO Inscripciones
        $sqlPagos = "SELECT id_pag, con_pag, mon_pag, est_pag, tip_pag FROM pago WHERE id_alu_ram10 = '$id_alu_ram' AND tip_pag != 'Inscripción'";
        $resultadoPagos = mysqli_query($db, $sqlPagos);
        
        if(!$resultadoPagos) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error al consultar pagos: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        $totalPagos = mysqli_num_rows($resultadoPagos);
        
        if($totalPagos == 0) {
            http_response_code(200);
            echo json_encode([
                'success' => true, 
                'mensaje' => 'El alumno no tiene pagos eliminables (solo tiene Inscripciones o ningún pago)',
                'pagos_eliminados' => 0
            ]);
            exit;
        }
        
        // Verificar si hay pagos PAGADOS -> requiere Super Admin
        $hayPagosPagados = false;
        $pagosArray = [];
        
        while($pago = mysqli_fetch_assoc($resultadoPagos)) {
            $pagosArray[] = $pago;
            if($pago['est_pag'] === 'Pagado') {
                $hayPagosPagados = true;
            }
        }
        
        if($hayPagosPagados) {
            $esSuperAdminActual = esSuperAdmin($db, $id);
            if(!$esSuperAdminActual) {
                http_response_code(403);
                echo json_encode([
                    'success' => false, 
                    'mensaje' => '🔑 Solo Super Admin puede eliminar pagos PAGADOS. Este alumno tiene pagos con estado PAGADO.'
                ]);
                exit;
            }
        }
        
        // ELIMINAR TODOS LOS PAGOS (excepto Inscripciones)
        $pagosEliminados = 0;
        $abonosEliminados = 0;
        $errores = [];
        
        foreach($pagosArray as $pago) {
            $id_pag = $pago['id_pag'];
            
            // Contar abonos antes de eliminar
            $sqlContarAbonos = "SELECT COUNT(*) as total FROM abono_pago WHERE id_pag1 = '$id_pag'";
            $resultadoContarAbonos = mysqli_query($db, $sqlContarAbonos);
            $totalAbonosPago = mysqli_fetch_assoc($resultadoContarAbonos)['total'];
            
            // Eliminar abonos primero
            $sqlEliminarAbonos = "DELETE FROM abono_pago WHERE id_pag1 = '$id_pag'";
            $resultadoAbonos = mysqli_query($db, $sqlEliminarAbonos);
            
            if($resultadoAbonos) {
                $abonosEliminados += $totalAbonosPago;
            }
            
            // Registrar auditoría ANTES de eliminar el pago
            $mensaje_auditoria = "🗑️💥 ELIMINACIÓN MASIVA - Pago eliminado: [{$pago['con_pag']}] | Tipo: {$pago['tip_pag']} | Monto: $" . number_format($pago['mon_pag'], 2) . " | Estado: {$pago['est_pag']} | Abonos eliminados: $totalAbonosPago | Ejecutivo: $nombreCompleto";
            registrarAuditoriaPago($db, $id_pag, $mensaje_auditoria, $nombreCompleto);
            
            // Eliminar pago
            $sqlEliminarPago = "DELETE FROM pago WHERE id_pag = '$id_pag'";
            $resultadoPago = mysqli_query($db, $sqlEliminarPago);
            
            if($resultadoPago) {
                $pagosEliminados++;
            } else {
                $errores[] = "Error al eliminar pago ID $id_pag: " . mysqli_error($db);
            }
        }
        
        // Registrar auditoría GENERAL de la operación masiva
        $mensaje_general = "🗑️💥 ELIMINACIÓN MASIVA COMPLETADA (Inscripciones protegidas) - Total pagos eliminados: $pagosEliminados | Total abonos eliminados: $abonosEliminados | Ejecutivo: $nombreCompleto";
        
        $sqlAuditoriaGeneral = "
            INSERT INTO observacion_alu_ram (obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram)
            VALUES ('" . mysqli_real_escape_string($db, $mensaje_general) . "', '$id_alu_ram', '" . mysqli_real_escape_string($db, $nombreCompleto) . "')
        ";
        mysqli_query($db, $sqlAuditoriaGeneral);
        
        // Respuesta
        if(count($errores) > 0) {
            http_response_code(207); // Multi-Status
            echo json_encode([
                'success' => false, 
                'mensaje' => "Se eliminaron $pagosEliminados de $totalPagos pagos. Algunos fallaron. ⚠️ Inscripciones protegidas.",
                'pagos_eliminados' => $pagosEliminados,
                'abonos_eliminados' => $abonosEliminados,
                'errores' => $errores
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'success' => true, 
                'mensaje' => "✅ Eliminación masiva exitosa: $pagosEliminados pagos y $abonosEliminados abonos eliminados (Inscripciones protegidas)",
                'pagos_eliminados' => $pagosEliminados,
                'abonos_eliminados' => $abonosEliminados
            ]);
        }
        exit;
    }

   // ==================== 🆕 ACTUALIZAR ABONO (EDICIÓN INLINE) ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_abono') {
       $id_abo_pag = mysqli_real_escape_string($db, $_POST['id_abo_pag']);
       $campo = mysqli_real_escape_string($db, $_POST['campo']);
       $valor = mysqli_real_escape_string($db, $_POST['valor']);
       
       // Validar que el abono existe
       $sqlValidar = "SELECT * FROM abono_pago WHERE id_abo_pag = '$id_abo_pag'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Abono no encontrado'
           ]);
           exit;
       }
       
       $abonoAnterior = mysqli_fetch_assoc($resultadoValidar);
       
       // Validaciones según campo
       if($campo === 'fec_abo_pag') {
           if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Formato de fecha inválido'
               ]);
               exit;
           }
       } elseif($campo === 'mon_abo_pag') {
           if(!is_numeric($valor) || floatval($valor) <= 0) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Monto inválido'
               ]);
               exit;
           }
       } elseif($campo === 'tip_abo_pag') {
           $tiposPermitidos = ['Efectivo STP', 'Débito', 'Crédito', 'Depósito', 'TPV', 'Transferencia'];
           if(!in_array($valor, $tiposPermitidos)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Tipo de pago no válido'
               ]);
               exit;
           }
       }
       
       // Guardar valor anterior para auditoría
       $valor_anterior = $abonoAnterior[$campo];
       
       // Actualizar abono
       $sqlActualizar = "UPDATE abono_pago SET $campo = '$valor' WHERE id_abo_pag = '$id_abo_pag'";
       $resultadoActualizar = mysqli_query($db, $sqlActualizar);
       
       if($resultadoActualizar) {
           // 📝 AUDITORÍA: Abono editado
           $mensaje_auditoria = "✏️ ABONO EDITADO - Campo: $campo | Valor anterior: $valor_anterior | Valor nuevo: $valor | Ejecutivo: $nombreCompleto";
           registrarAuditoriaAbono($db, $id_abo_pag, $mensaje_auditoria, $nombreCompleto);
           
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Abono actualizado correctamente',
               'campo' => $campo,
               'valor_nuevo' => $valor,
               'valor_anterior' => $valor_anterior
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al actualizar abono: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== 🔥 ELIMINAR ABONO ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'eliminar_abono') {
       $id_abo_pag = mysqli_real_escape_string($db, $_POST['id_abo_pag']);
       
       // Validar que el abono existe y obtener datos para auditoría
       $sqlValidar = "SELECT * FROM abono_pago WHERE id_abo_pag = '$id_abo_pag'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Abono no encontrado'
           ]);
           exit;
       }
       
       $datoAbono = mysqli_fetch_assoc($resultadoValidar);
       $id_pag_padre = $datoAbono['id_pag1'];
       
       // 📝 AUDITORÍA ANTES de eliminar (con datos completos del abono)
       $mensaje_auditoria = "🗑️ ABONO ELIMINADO - Monto: $" . number_format($datoAbono['mon_abo_pag'], 2) . 
                           " | Fecha: " . fechaFormateadaCompacta2($datoAbono['fec_abo_pag']) . 
                           " | Tipo: {$datoAbono['tip_abo_pag']}" . 
                           " | Responsable: {$datoAbono['res_abo_pag']}" . 
                           " | Ejecutivo eliminador: $nombreCompleto";
       registrarAuditoriaPago($db, $id_pag_padre, $mensaje_auditoria, $nombreCompleto);
       
       // Eliminar abono
       $sqlEliminar = "DELETE FROM abono_pago WHERE id_abo_pag = '$id_abo_pag'";
       $resultadoEliminar = mysqli_query($db, $sqlEliminar);
       
       if($resultadoEliminar) {
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Abono eliminado correctamente',
               'id_pag_padre' => $id_pag_padre
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al eliminar abono: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== 🆕 ACTUALIZAR PAGO (EDICIÓN INLINE - PAG_PAG) ====================
   if(isset($_POST['accion']) && $_POST['accion'] === 'actualizar_pago') {
       $id_pag = mysqli_real_escape_string($db, $_POST['id_pag']);
       $campo = mysqli_real_escape_string($db, $_POST['campo']);
       $valor = mysqli_real_escape_string($db, $_POST['valor']);
       
       // Validar que el pago existe
       $sqlValidar = "SELECT * FROM pago WHERE id_pag = '$id_pag'";
       $resultadoValidar = mysqli_query($db, $sqlValidar);
       
       if(!$resultadoValidar || mysqli_num_rows($resultadoValidar) == 0) {
           http_response_code(404);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Pago no encontrado'
           ]);
           exit;
       }
       
       $pagoAnterior = mysqli_fetch_assoc($resultadoValidar);
       
       // Por ahora, solo permitir edición de pag_pag (fecha de pago)
       if($campo !== 'pag_pag') {
           http_response_code(400);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Solo se permite editar la fecha de pago (pag_pag)'
           ]);
           exit;
       }
       
       // Validar formato de fecha (puede ser vacío para limpiar la fecha)
       if($valor !== '' && $valor !== '0000-00-00') {
           if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
               http_response_code(400);
               echo json_encode([
                   'success' => false, 
                   'mensaje' => 'Formato de fecha inválido'
               ]);
               exit;
           }
       }
       
       // Guardar valor anterior para auditoría
       $valor_anterior = $pagoAnterior[$campo];
       
       // Actualizar pago
       $sqlActualizar = "UPDATE pago SET $campo = '$valor' WHERE id_pag = '$id_pag'";
       $resultadoActualizar = mysqli_query($db, $sqlActualizar);
       
       if($resultadoActualizar) {
           // 📝 AUDITORÍA: Fecha de pago editada
           $mensaje_auditoria = "📅 FECHA DE PAGO EDITADA - [{$pagoAnterior['con_pag']}] | Fecha anterior: " . ($valor_anterior && $valor_anterior !== '0000-00-00' ? fechaFormateadaCompacta2($valor_anterior) : 'Sin fecha') . " | Fecha nueva: " . ($valor && $valor !== '0000-00-00' ? fechaFormateadaCompacta2($valor) : 'Sin fecha') . " | Ejecutivo: $nombreCompleto";
           registrarAuditoriaPago($db, $id_pag, $mensaje_auditoria, $nombreCompleto);
           
           http_response_code(200);
           echo json_encode([
               'success' => true, 
               'mensaje' => 'Fecha de pago actualizada correctamente',
               'campo' => $campo,
               'valor_nuevo' => $valor,
               'valor_anterior' => $valor_anterior,
               'valor_formateado' => ($valor && $valor !== '0000-00-00') ? fechaFormateadaCompacta2($valor) : null
           ]);
       } else {
           http_response_code(500);
           echo json_encode([
               'success' => false, 
               'mensaje' => 'Error al actualizar fecha de pago: ' . mysqli_error($db)
           ]);
       }
       exit;
   }

   // ==================== RESPUESTA PARA REQUESTS NO VÁLIDOS ====================
   http_response_code(400);
   echo json_encode([
       'success' => false, 
       'mensaje' => 'Parámetros no válidos'
   ]);
?>