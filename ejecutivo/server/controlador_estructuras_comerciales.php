<?php
/**
 * ============================================================================
 * CONTROLADOR MAESTRO DE ESTRUCTURAS COMERCIALES
 * ============================================================================
 * 
 * VERSIÓN OPTIMIZADA CON CARGA POR PLANTEL INDIVIDUAL
 * Incluye métricas de tiempo para identificar cuellos de botella
 * 
 * COLORES DE BADGES:
 * - CONTACTOS:       Naranja claro (casi amarillo) #FFD700 (texto negro)
 * - CITAS:           Naranja #FF9800 (texto blanco)
 * - CITAS EFECTIVAS: Rosa #FFC0CB (texto rojo #FF0000)
 * - REGISTROS:       Cyan #00FFFF (texto negro)
 * 
 * ORDEN: PAC → CONTACTOS → CITAS → CITAS EFECTIVAS → REGISTROS
 */

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Añade el header para JSON
header('Content-Type: application/json');

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// ============================================================================
// ============================================================================
// 🆕 OBTENER PLANTEL INDIVIDUAL (CARGA ASÍNCRONA)
// ============================================================================
// ============================================================================
if ($accion == 'ObtenerPlantelIndividual') {
    
    $tiempos = array();
    $tiempo_inicio_total = microtime(true);
    
    $id_pla = mysqli_real_escape_string($db, $_POST['id_pla']);
    $inicio = mysqli_real_escape_string($db, $_POST['inicio']);
    $fin = mysqli_real_escape_string($db, $_POST['fin']);
    
    // ========================================================================
    // VALIDAR PERMISOS
    // ========================================================================
    $sqlPermiso = "SELECT COUNT(*) as total FROM planteles_ejecutivo WHERE id_eje = '$id'";
    $resultadoPermiso = obtener_datos_consulta($db, $sqlPermiso);
    $puedeVerPlanteles = ($resultadoPermiso['datos']['total'] > 1);
    
    $sqlTotalPlanteles = "SELECT COUNT(*) as total FROM plantel WHERE id_cad1 = '$cadena'";
    $totalPlanteles = obtener_datos_consulta($db, $sqlTotalPlanteles)['datos']['total'];
    
    $sqlPlantelesUsuario = "SELECT COUNT(*) as total FROM planteles_ejecutivo WHERE id_eje = '$id_eje'";
    $plantelesUsuario = obtener_datos_consulta($db, $sqlPlantelesUsuario)['datos']['total'];
    
    $puedeGestionarPlanteles = ($totalPlanteles == $plantelesUsuario);
    
    // ========================================================================
    // OBTENER NOMBRE DEL PLANTEL
    // ========================================================================
    $sqlPlantel = "SELECT nom_pla FROM plantel WHERE id_pla = '$id_pla'";
    $datosPlantel = obtener_datos_consulta($db, $sqlPlantel);
    $nom_pla = $datosPlantel['datos']['nom_pla'];
    
    // ========================================================================
    // 1. PRECARGA DE DATOS (CACHE GLOBAL)
    // ========================================================================
    $tiempo_precarga_inicio = microtime(true);
    
    // Limpiar cache anterior
    $GLOBALS['cache_ejecutivos'] = array();
    $GLOBALS['cache_metricas'] = array();
    $GLOBALS['cache_metas'] = array();
    $GLOBALS['cache_planteles_eje'] = array();
    $GLOBALS['cache_hijos'] = array();
    
    // 1.1 TODOS LOS EJECUTIVOS DEL PLANTEL
    $tiempo_q1 = microtime(true);
    $sqlEjecutivos = "
        SELECT *
        FROM ejecutivo 
        WHERE id_pla = '$id_pla' 
        AND eli_eje = 'Activo'
        AND tip_eje = 'Ejecutivo'
    ";
    $result = mysqli_query($db, $sqlEjecutivos);
    
    $ids_ejecutivos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $id_eje_row = $row['id_eje'];
        $GLOBALS['cache_ejecutivos'][$id_eje_row] = $row;
        $ids_ejecutivos[] = $id_eje_row;
        
        $id_padre = ($row['id_padre'] === null) ? 'root' : $row['id_padre'];
        if (!isset($GLOBALS['cache_hijos'][$id_padre])) {
            $GLOBALS['cache_hijos'][$id_padre] = array();
        }
        $GLOBALS['cache_hijos'][$id_padre][] = $id_eje_row;
    }
    $tiempos['query_ejecutivos'] = round((microtime(true) - $tiempo_q1) * 1000, 2);
    
    $totalPAC = count($GLOBALS['cache_ejecutivos']);
    
    if (!empty($ids_ejecutivos)) {
        $ids_str = implode(',', $ids_ejecutivos);
        
        // 1.2 MÉTRICAS DE TODOS LOS EJECUTIVOS (INCLUYENDO CONTACTOS)
        $tiempo_q2 = microtime(true);
        $sqlMetricas = "
            SELECT 
                id_eje,
                obtener_contactos_ejecutivo(id_eje, '$inicio', '$fin') as contactos,
                obtener_citas_ejecutivo(id_eje, '$inicio', '$fin') as citas,
                obtener_citas_efectivas_ejecutivo(id_eje, '$inicio', '$fin') as citas_efectivas,
                obtener_registros_ejecutivo(id_eje, '$inicio', '$fin') as registros,
                obtener_conteo_recursivo_registros_ejecutivo(id_eje, '$inicio', '$fin') as total_registros
            FROM ejecutivo
            WHERE id_eje IN ($ids_str)
        ";
        $resultMetricas = mysqli_query($db, $sqlMetricas);
        
        while ($row = mysqli_fetch_assoc($resultMetricas)) {
            $GLOBALS['cache_metricas'][$row['id_eje']] = array(
                'contactos' => intval($row['contactos']),
                'citas' => intval($row['citas']),
                'citas_efectivas' => intval($row['citas_efectivas']),
                'registros' => intval($row['registros']),
                'total_registros' => intval($row['total_registros'])
            );
        }
        $tiempos['query_metricas'] = round((microtime(true) - $tiempo_q2) * 1000, 2);
        
        // 1.3 METAS DE TODOS LOS EJECUTIVOS
        $tiempo_q3 = microtime(true);
        $sqlMetas = "
            SELECT id_met, id_eje5, rub_met, can_met, reg_met
            FROM meta
            WHERE id_eje5 IN ($ids_str)
            AND DATE(reg_met) BETWEEN '$inicio' AND '$fin'
        ";
        $resultMetas = mysqli_query($db, $sqlMetas);
        
        while ($row = mysqli_fetch_assoc($resultMetas)) {
            $id_eje_meta = $row['id_eje5'];
            if (!isset($GLOBALS['cache_metas'][$id_eje_meta])) {
                $GLOBALS['cache_metas'][$id_eje_meta] = array();
            }
            $rubro = $row['rub_met'];
            if (!isset($GLOBALS['cache_metas'][$id_eje_meta][$rubro])) {
                $GLOBALS['cache_metas'][$id_eje_meta][$rubro] = array(
                    'id_met' => $row['id_met'],
                    'cantidad' => 0
                );
            }
            $GLOBALS['cache_metas'][$id_eje_meta][$rubro]['cantidad'] += intval($row['can_met']);
        }
        $tiempos['query_metas'] = round((microtime(true) - $tiempo_q3) * 1000, 2);
        
        // 1.4 PLANTELES ASIGNADOS A CADA EJECUTIVO
        $tiempo_q4 = microtime(true);
        $sqlPlanteles = "
            SELECT pe.id_eje, p.nom_pla
            FROM planteles_ejecutivo pe
            INNER JOIN plantel p ON pe.id_pla = p.id_pla
            WHERE pe.id_eje IN ($ids_str)
            ORDER BY p.nom_pla
        ";
        $resultPlanteles = mysqli_query($db, $sqlPlanteles);
        
        while ($row = mysqli_fetch_assoc($resultPlanteles)) {
            $id_eje_pla = $row['id_eje'];
            if (!isset($GLOBALS['cache_planteles_eje'][$id_eje_pla])) {
                $GLOBALS['cache_planteles_eje'][$id_eje_pla] = array();
            }
            $GLOBALS['cache_planteles_eje'][$id_eje_pla][] = $row['nom_pla'];
        }
        $tiempos['query_planteles_asignados'] = round((microtime(true) - $tiempo_q4) * 1000, 2);
    }
    
    $tiempos['precarga_total'] = round((microtime(true) - $tiempo_precarga_inicio) * 1000, 2);
    
    // ========================================================================
    // 2. TOTALES DEL PLANTEL
    // ========================================================================
    $tiempo_totales_inicio = microtime(true);
    
    // Metas totalizadas
    $metasPlantel = obtener_metas_totalizadas_plantel_cache($id_pla, $inicio, $fin);
    
    // CONTACTOS del plantel
    $tiempo_contactos_pla = microtime(true);
    $sqlContactosPlantel = "SELECT obtener_contactos_plantel($id_pla, '$inicio', '$fin') AS total";
    $contactosPlantel = obtener_datos_consulta($db, $sqlContactosPlantel)['datos']['total'];
    $tiempos['query_contactos_plantel'] = round((microtime(true) - $tiempo_contactos_pla) * 1000, 2);
    
    // CITAS del plantel
    $tiempo_citas_pla = microtime(true);
    $sqlCitasPlantel = "SELECT obtener_citas_plantel($id_pla, '$inicio', '$fin') AS total";
    $citasPlantel = obtener_datos_consulta($db, $sqlCitasPlantel)['datos']['total'];
    $tiempos['query_citas_plantel'] = round((microtime(true) - $tiempo_citas_pla) * 1000, 2);
    
    // CITAS EFECTIVAS del plantel
    $tiempo_citas_efe_pla = microtime(true);
    $sqlCitasEfectivasPlantel = "SELECT obtener_citas_efectivas_plantel($id_pla, '$inicio', '$fin') AS total";
    $citasEfectivasPlantel = obtener_datos_consulta($db, $sqlCitasEfectivasPlantel)['datos']['total'];
    $tiempos['query_citas_efectivas_plantel'] = round((microtime(true) - $tiempo_citas_efe_pla) * 1000, 2);
    
    // REGISTROS del plantel
    $tiempo_reg_pla = microtime(true);
    $sqlRegistrosPlantel = "SELECT obtener_registros_plantel($id_pla, '$inicio', '$fin') AS total";
    $registrosPlantel = obtener_datos_consulta($db, $sqlRegistrosPlantel)['datos']['total'];
    $tiempos['query_registros_plantel'] = round((microtime(true) - $tiempo_reg_pla) * 1000, 2);
    
    $tiempos['totales_plantel'] = round((microtime(true) - $tiempo_totales_inicio) * 1000, 2);
    
    // ========================================================================
    // 3. RENDERIZAR HTML DEL ÁRBOL
    // ========================================================================
    $tiempo_render_inicio = microtime(true);
    
    ob_start();
    renderizarArbolPlantel($id_pla, $nom_pla, $totalPAC, $metasPlantel, $contactosPlantel, $citasPlantel, $citasEfectivasPlantel, $registrosPlantel, $inicio, $fin, $puedeVerPlanteles);
    $html = ob_get_clean();
    
    $tiempos['renderizado'] = round((microtime(true) - $tiempo_render_inicio) * 1000, 2);
    
    // ========================================================================
    // 4. RESPUESTA FINAL
    // ========================================================================
    $tiempos['total'] = round((microtime(true) - $tiempo_inicio_total) * 1000, 2);
    
    echo json_encode(array(
        'success' => true,
        'html' => $html,
        'tiempos' => $tiempos,
        'debug' => array(
            'id_pla' => $id_pla,
            'nom_pla' => $nom_pla,
            'total_ejecutivos' => $totalPAC,
            'inicio' => $inicio,
            'fin' => $fin
        )
    ));
    exit();
}

// ============================================================================
// FUNCIONES AUXILIARES PARA EL RENDERIZADO
// ============================================================================

/**
 * Obtener metas totalizadas desde el cache global
 */
function obtener_metas_totalizadas_plantel_cache($id_pla, $inicio, $fin) {
    $resultado = array(
        'Contacto' => array('meta' => 0),
        'Cita' => array('meta' => 0),
        'CitaEfectiva' => array('meta' => 0),
        'Registro' => array('meta' => 0)
    );
    
    // Sumar metas desde el cache
    foreach ($GLOBALS['cache_metas'] as $id_eje => $metas) {
        foreach ($metas as $rubro => $data) {
            if (isset($resultado[$rubro])) {
                $resultado[$rubro]['meta'] += $data['cantidad'];
            }
        }
    }
    
    return $resultado;
}

/**
 * Obtener indicadores desde cache
 * ============================================================================
 * COLORES:
 * - CONTACTOS:       Naranja claro (casi amarillo) #FFD700 (texto negro)
 * - CITAS:           Naranja #FF9800 (texto blanco)
 * - CITAS EFECTIVAS: Rosa #FFC0CB (texto rojo #FF0000)
 * - REGISTROS:       Cyan #00FFFF (texto negro)
 * 
 * ORDEN: CONTACTOS → CITAS → CITAS EFECTIVAS → REGISTROS
 * ============================================================================
 */
function obtener_indicadores_ejecutivo_cache($id_eje, $fin) {
    $html = '';
    
    $metricas = isset($GLOBALS['cache_metricas'][$id_eje]) ? $GLOBALS['cache_metricas'][$id_eje] : array();
    $metas = isset($GLOBALS['cache_metas'][$id_eje]) ? $GLOBALS['cache_metas'][$id_eje] : array();
    
    $contactos = isset($metricas['contactos']) ? $metricas['contactos'] : 0;
    $citas = isset($metricas['citas']) ? $metricas['citas'] : 0;
    $citasEfectivas = isset($metricas['citas_efectivas']) ? $metricas['citas_efectivas'] : 0;
    $registros = isset($metricas['registros']) ? $metricas['registros'] : 0;
    
    if (count($metas) == 0 && $contactos == 0 && $citas == 0 && $citasEfectivas == 0 && $registros == 0) {
        return $html;
    }
    
    // ========================================================================
    // 1. BADGE CONTACTOS - Naranja claro (casi amarillo) #FFD700 (texto negro)
    // ========================================================================
    if ($contactos > 0 || isset($metas['Contacto'])) {
        if (isset($metas['Contacto'])) {
            $clase = obtener_clase_estado_meta($contactos, $metas['Contacto']['cantidad'], $fin);
            $emoji = ($contactos >= $metas['Contacto']['cantidad']) ? '✅' : '🎯';
            $html .= '<span class="badge badge-meta-clickable ' . $clase . '" style="background-color: #FFD700; color: black; font-size: 11px; padding: 2px 5px; cursor: pointer;" data-id-eje="' . $id_eje . '" data-id-met="' . $metas['Contacto']['id_met'] . '">' . $emoji . $contactos . '/' . $metas['Contacto']['cantidad'] . '</span>';
        } else {
            $html .= '<span class="badge" style="background-color: #FFD700; color: black; font-size: 11px; padding: 2px 5px;">' . $contactos . '</span>';
        }
    }
    
    // ========================================================================
    // 2. BADGE CITAS - Naranja #FF9800 (texto blanco)
    // ========================================================================
    if ($citas > 0 || isset($metas['Cita'])) {
        if (isset($metas['Cita'])) {
            $clase = obtener_clase_estado_meta($citas, $metas['Cita']['cantidad'], $fin);
            $emoji = ($citas >= $metas['Cita']['cantidad']) ? '✅' : '🎯';
            $html .= '<span class="badge badge-meta-clickable ' . $clase . '" style="background-color: #FF9800; color: white; font-size: 11px; padding: 2px 5px; cursor: pointer;" data-id-eje="' . $id_eje . '" data-id-met="' . $metas['Cita']['id_met'] . '">' . $emoji . $citas . '/' . $metas['Cita']['cantidad'] . '</span>';
        } else {
            $html .= '<span class="badge" style="background-color: #FF9800; color: white; font-size: 11px; padding: 2px 5px;">' . $citas . '</span>';
        }
    }
    
    // ========================================================================
    // 3. BADGE CITAS EFECTIVAS - Rosa #FFC0CB (texto rojo #FF0000)
    // ========================================================================
    if ($citasEfectivas > 0 || isset($metas['CitaEfectiva'])) {
        if (isset($metas['CitaEfectiva'])) {
            $clase = obtener_clase_estado_meta($citasEfectivas, $metas['CitaEfectiva']['cantidad'], $fin);
            $emoji = ($citasEfectivas >= $metas['CitaEfectiva']['cantidad']) ? '✅' : '🎯';
            $html .= '<span class="badge badge-meta-clickable ' . $clase . '" style="background-color: #FFC0CB; color: #FF0000; font-size: 11px; padding: 2px 5px; cursor: pointer;" data-id-eje="' . $id_eje . '" data-id-met="' . $metas['CitaEfectiva']['id_met'] . '">' . $emoji . $citasEfectivas . '/' . $metas['CitaEfectiva']['cantidad'] . '</span>';
        } else {
            $html .= '<span class="badge" style="background-color: #FFC0CB; color: #FF0000; font-size: 11px; padding: 2px 5px;">' . $citasEfectivas . '</span>';
        }
    }
    
    // ========================================================================
    // 4. BADGE REGISTROS - Cyan #00FFFF (texto negro)
    // ========================================================================
    if ($registros > 0 || isset($metas['Registro'])) {
        if (isset($metas['Registro'])) {
            $clase = obtener_clase_estado_meta($registros, $metas['Registro']['cantidad'], $fin);
            $emoji = ($registros >= $metas['Registro']['cantidad']) ? '✅' : '🎯';
            $html .= '<span class="badge badge-meta-clickable ' . $clase . '" style="background-color: #00FFFF; color: black; font-size: 11px; padding: 2px 5px; cursor: pointer;" data-id-eje="' . $id_eje . '" data-id-met="' . $metas['Registro']['id_met'] . '">' . $emoji . $registros . '/' . $metas['Registro']['cantidad'] . '</span>';
        } else {
            $html .= '<span class="badge" style="background-color: #00FFFF; color: black; font-size: 11px; padding: 2px 5px;">' . $registros . '</span>';
        }
    }
    
    return $html;
}

/**
 * Generar nodos hijos recursivamente desde cache
 */
function generarNodosHijosCache($idPadre, $fin, $puedeVerPlanteles) {
    if (!isset($GLOBALS['cache_hijos'][$idPadre])) {
        return;
    }
    
    $hijos = $GLOBALS['cache_hijos'][$idPadre];
    
    if (count($hijos) > 0) {
        echo '<ul>';
        
        foreach ($hijos as $id_eje) {
            $filaHijos = $GLOBALS['cache_ejecutivos'][$id_eje];
            
            echo "<li data-jstree='{\"opened\":true, \"icon\":false}' 
                  style=\"width: 20px; height: 25px; border-radius: 35px;\" 
                  id='" . $filaHijos['id_eje'] . "' 
                  est_eje='" . $filaHijos['est_eje'] . "' 
                  per_eje='" . $filaHijos['per_eje'] . "'
                  id_pla='" . $filaHijos['id_pla'] . "'
                  data-ejecutivo-nombre='" . htmlspecialchars($filaHijos['nom_eje'], ENT_QUOTES, 'UTF-8') . "'
            >";
            
            echo obtener_semaforo_ejecutivo($filaHijos['ult_eje']);
            
            if ($puedeVerPlanteles && isset($GLOBALS['cache_planteles_eje'][$id_eje])) {
                foreach ($GLOBALS['cache_planteles_eje'][$id_eje] as $nom_pla) {
                    echo '<span title="' . $nom_pla . '">🕋</span>';
                }
            }
            
            echo "<img class=\"imagenGrande\" loading=\"lazy\" width=\"20\" height=\"25\" 
                  src='" . obtenerValidacionFotoUsuarioServer($filaHijos['fot_eje']) . "' 
                  style=\"border-radius: 35px;\">";
            
            if ($filaHijos['per_eje'] == 1) {
                echo '<span class="badge bg-success">Permisos CDE</span>';
            } elseif ($filaHijos['per_eje'] == 2) {
                echo '<span class="badge bg-success">Permisos AHJ ENDE</span>';
            }
            
            if ($filaHijos['usu_eje'] == null) {
                echo obtener_rango_usuario_badge($filaHijos['ran_eje']);
            } else {
                echo obtener_usuario_ejecutivo($filaHijos['usu_eje'], $filaHijos['est_eje']);
            }
            
            $claseInactivo = ($filaHijos['est_eje'] == 'Inactivo') ? 'text-danger' : '';
            echo '<span title="' . $filaHijos['nom_eje'] . '" class="ejecutivo-nombre ' . $claseInactivo . '">' . $filaHijos['nom_eje'] . '</span>';
            
            echo obtener_indicadores_ejecutivo_cache($id_eje, $fin);
            
            generarNodosHijosCache($id_eje, $fin, $puedeVerPlanteles);
            
            echo "</li>";
        }
        
        echo '</ul>';
    }
}

/**
 * Renderizar el árbol completo del plantel
 * ORDEN DE BADGES: PAC → CONTACTOS → CITAS → CITAS EFECTIVAS → REGISTROS
 */
function renderizarArbolPlantel($id_pla, $nom_pla, $totalPAC, $metasPlantel, $contactosPlantel, $citasPlantel, $citasEfectivasPlantel, $registrosPlantel, $inicio, $fin, $puedeVerPlanteles) {
    ?>
    <h4 class="header-title mt-0 mb-3">
        🕋<?php echo $nom_pla; ?>
        
        <?php // PAC ?>
        <span class="badge badge-pac">
            <strong>PAC: <?php echo $totalPAC; ?></strong>
        </span>

        <?php // 1. CONTACTOS - Naranja claro (casi amarillo) #FFD700 (texto negro) ?>
        <?php if($metasPlantel['Contacto']['meta'] > 0): ?>
            <a href="<?php echo "referidos.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge badge-meta-clickable <?php echo obtener_clase_estado_meta($contactosPlantel, $metasPlantel['Contacto']['meta'], $fin); ?>" style="background-color: #FFD700; color: black;">
                    CON: <?php echo ($contactosPlantel >= $metasPlantel['Contacto']['meta']) ? '✅' : '🎯'; ?><?php echo $contactosPlantel; ?>/<?php echo $metasPlantel['Contacto']['meta']; ?>
                </span>
            </a>
        <?php else: ?>
            <a href="<?php echo "referidos.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge" style="background-color: #FFD700; color: black;">
                    CON: <?php echo $contactosPlantel; ?>
                </span>
            </a>
        <?php endif; ?>

        <?php // 2. CITAS - Naranja #FF9800 (texto blanco) ?>
        <?php if($metasPlantel['Cita']['meta'] > 0): ?>
            <a href="<?php echo "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge badge-meta-clickable <?php echo obtener_clase_estado_meta($citasPlantel, $metasPlantel['Cita']['meta'], $fin); ?>" style="background-color: #FF9800; color: white;">
                    CIT: <?php echo ($citasPlantel >= $metasPlantel['Cita']['meta']) ? '✅' : '🎯'; ?><?php echo $citasPlantel; ?>/<?php echo $metasPlantel['Cita']['meta']; ?>
                </span>
            </a>
        <?php else: ?>
            <a href="<?php echo "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge" style="background-color: #FF9800; color: white;">
                    CIT: <?php echo $citasPlantel; ?>
                </span>
            </a>
        <?php endif; ?>

        <?php // 3. CITAS EFECTIVAS - Rosa #FFC0CB (texto rojo #FF0000) ?>
        <?php if($metasPlantel['CitaEfectiva']['meta'] > 0): ?>
            <a href="<?php echo "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge badge-meta-clickable <?php echo obtener_clase_estado_meta($citasEfectivasPlantel, $metasPlantel['CitaEfectiva']['meta'], $fin); ?>" style="background-color: #FFC0CB; color: #FF0000;">
                    CIT EFE: <?php echo ($citasEfectivasPlantel >= $metasPlantel['CitaEfectiva']['meta']) ? '✅' : '🎯'; ?><?php echo $citasEfectivasPlantel; ?>/<?php echo $metasPlantel['CitaEfectiva']['meta']; ?>
                </span>
            </a>
        <?php else: ?>
            <a href="<?php echo "citas.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge" style="background-color: #FFC0CB; color: #FF0000;">
                    CIT EFE: <?php echo $citasEfectivasPlantel; ?>
                </span>
            </a>
        <?php endif; ?>

        <?php // 4. REGISTROS - Cyan #00FFFF (texto negro) ?>
        <?php if($metasPlantel['Registro']['meta'] > 0): ?>
            <a href="<?php echo "registros.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge badge-meta-clickable <?php echo obtener_clase_estado_meta($registrosPlantel, $metasPlantel['Registro']['meta'], $fin); ?>" style="background-color: #00FFFF; color: black;">
                    REG: <?php echo ($registrosPlantel >= $metasPlantel['Registro']['meta']) ? '✅' : '🎯'; ?><?php echo $registrosPlantel; ?>/<?php echo $metasPlantel['Registro']['meta']; ?>
                </span>
            </a>
        <?php else: ?>
            <a href="<?php echo "registros.php?escala=plantel&id_pla=$id_pla&inicio=$inicio&fin=$fin"; ?>" target="_blank">
                <span class="badge" style="background-color: #00FFFF; color: black;">
                    REG: <?php echo $registrosPlantel; ?>
                </span>
            </a>
        <?php endif; ?>
    </h4>
    
    <!-- ÁRBOL jsTree -->
    <div id="dragTree<?php echo $id_pla; ?>">
        <?php  
            $raices = isset($GLOBALS['cache_hijos']['root']) ? $GLOBALS['cache_hijos']['root'] : array();
            
            foreach ($raices as $id_eje_raiz) {
                $filaRaices = $GLOBALS['cache_ejecutivos'][$id_eje_raiz];
                
                if ($filaRaices['ran_eje'] == 'DC') continue;
        ?>
        <ul>
            <li data-jstree='{"opened":true, "icon":false}' style="width: 25px; height: 30px; border-radius: 35px;" 
                id="<?php echo $filaRaices['id_eje']; ?>"
                est_eje="<?php echo $filaRaices['est_eje']; ?>"
                per_eje="<?php echo $filaRaices['per_eje']; ?>"
                id_pla="<?php echo $filaRaices['id_pla']; ?>"
                data-ejecutivo-nombre="<?php echo htmlspecialchars($filaRaices['nom_eje'], ENT_QUOTES, 'UTF-8'); ?>"
            >
                <?php 
                    echo obtener_semaforo_ejecutivo($filaRaices['ult_eje']); 
                    
                    if($puedeVerPlanteles && isset($GLOBALS['cache_planteles_eje'][$id_eje_raiz])) {
                        foreach($GLOBALS['cache_planteles_eje'][$id_eje_raiz] as $nom_pla_eje) {
                            echo '<span title="'.$nom_pla_eje.'">🕋</span>';
                        }
                    }
                ?>

                <img src="<?php echo obtenerValidacionFotoUsuarioServer($filaRaices['fot_eje']); ?>" 
                loading="lazy"
                width="20" 
                height="25" 
                style="border-radius: 35px;" 
                class="imagenGrande">

                <?php
                    if($filaRaices['usu_eje'] == null){
                        echo obtener_rango_usuario_badge($filaRaices['ran_eje']);
                    } else {
                        echo obtener_usuario_ejecutivo($filaRaices['usu_eje'], $filaRaices['est_eje']);
                    }
                ?>

                <?php 
                    echo ($filaRaices['per_eje'] == 1) ? '<span class="badge bg-success">Permisos CDE</span>' : 
                        (($filaRaices['per_eje'] == 2) ? '<span class="badge bg-success">Permisos AHJ ENDE</span>' : '');
                ?>
                
                <span title="<?php echo $filaRaices['nom_eje']; ?>" class="<?php if ($filaRaices['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
                    <?php echo obtenerPrimerasDosPalabras($filaRaices['nom_eje']); ?>
                </span>

                <?php 
                    echo obtener_indicadores_ejecutivo_cache($id_eje_raiz, $fin);
                ?>

                <?php generarNodosHijosCache($id_eje_raiz, $fin, $puedeVerPlanteles); ?>
            </li>
        </ul>
        <?php } ?>
    </div>
    <?php
}

// ============================================================================
// ============================================================================
// RESTO DE ACCIONES ORIGINALES (SIN CAMBIOS)
// ============================================================================
// ============================================================================

// ============================================================================
// CAMBIO - MOVER NODOS EN EL ÁRBOL
// ============================================================================
if ($accion == 'Cambio' && isset($_POST['idHijo'])) {

    $idHijo = mysqli_real_escape_string($db, $_POST['idHijo']);
    $idPadre = mysqli_real_escape_string($db, $_POST['idPadre']);

    if ($idPadre == 0 || $idPadre == '#') {
        $sql = "UPDATE ejecutivo SET id_padre = NULL WHERE id_eje = '$idHijo'";
    } else {
        $sql = "UPDATE ejecutivo SET id_padre = '$idPadre' WHERE id_eje = '$idHijo'";
    }

    $resultado = mysqli_query($db, $sql);

    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
    } else {
        echo json_encode(array('success' => true));
    }

// ============================================================================
// BAJA - ELIMINAR EJECUTIVO (BORRADO VISUAL)
// ============================================================================
} else if ($accion == 'Baja') {
    
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);

    $sql = "UPDATE ejecutivo SET eli_eje = 'Inactivo' WHERE id_eje = '$id_eje'";

    $resultado = mysqli_query($db, $sql);

    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
    } else {
        echo json_encode(array('success' => true));
    }

// ============================================================================
// ALTA - CREAR NUEVO EJECUTIVO
// ============================================================================
} else if ($accion == 'Alta') {
    
    $nom_eje = mysqli_real_escape_string($db, $_POST['nom_eje']);
    $ran_eje = mysqli_real_escape_string($db, $_POST['ran_eje']);
    $cor_eje = mysqli_real_escape_string($db, $_POST['cor_eje']);
    $pas_eje = mysqli_real_escape_string($db, $_POST['pas_eje']);
    $tel_eje = mysqli_real_escape_string($db, $_POST['tel_eje']);
    $obs_eje = mysqli_real_escape_string($db, $_POST['obs_eje']);
    $id_pla = mysqli_real_escape_string($db, $_POST['id_pla']);

    $sql = "
        INSERT INTO ejecutivo 
        (nom_eje, ran_eje, cor_eje, pas_eje, tel_eje, obs_eje, id_pla, id_can1, ult_cam_pas_eje, req_cam_pas_eje, tip_eje) 
        VALUES 
        ('$nom_eje', '$ran_eje', '$cor_eje', '$pas_eje', '$tel_eje', '$obs_eje', '$id_pla', NULL, CURDATE(), 0, 'Ejecutivo')
    ";

    $resultado = mysqli_query($db, $sql);

    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
    } else {
        $id_eje_insertado = mysqli_insert_id($db);
        
        if (isset($_POST['planteles'])) {
            $planteles = json_decode($_POST['planteles'], true);
            
            if (is_array($planteles) && count($planteles) > 0) {
                foreach ($planteles as $id_pla_checkbox) {
                    $id_pla_checkbox = mysqli_real_escape_string($db, $id_pla_checkbox);
                    
                    $sqlPlantel = "
                        INSERT INTO planteles_ejecutivo (id_eje, id_pla) 
                        VALUES ('$id_eje_insertado', '$id_pla_checkbox')
                    ";
                    mysqli_query($db, $sqlPlantel);
                }
            }
        } else {
            if ($ran_eje == 'GC') {
                $sqlPlantel = "
                    INSERT INTO planteles_ejecutivo (id_eje, id_pla) 
                    VALUES ('$id_eje_insertado', '$id_pla')
                ";
                mysqli_query($db, $sqlPlantel);
            }
        }
        
        if ($ran_eje == 'GR') {
            $sqlPermisos = "
                UPDATE ejecutivo 
                SET per_eje = '1'
                WHERE id_eje = '$id_eje_insertado'
            ";
            mysqli_query($db, $sqlPermisos);
        }
        
        echo json_encode(array('success' => true, 'id_eje' => $id_eje_insertado));
    }

// ============================================================================
// CAMBIO - MODIFICAR EJECUTIVO EXISTENTE
// ============================================================================
} else if ($accion == 'Cambio' && isset($_POST['id_eje']) && isset($_POST['nom_eje'])) {
    
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $nom_eje = mysqli_real_escape_string($db, $_POST['nom_eje']);
    $ran_eje = mysqli_real_escape_string($db, $_POST['ran_eje']);
    $cor_eje = mysqli_real_escape_string($db, $_POST['cor_eje']);
    $pas_eje = mysqli_real_escape_string($db, $_POST['pas_eje']);
    $tel_eje = mysqli_real_escape_string($db, $_POST['tel_eje']);
    $obs_eje = mysqli_real_escape_string($db, $_POST['obs_eje']);
    $id_pla = mysqli_real_escape_string($db, $_POST['id_pla']);
    
    $sql = "
        UPDATE ejecutivo 
        SET 
            nom_eje = '$nom_eje', 
            ran_eje = '$ran_eje', 
            cor_eje = '$cor_eje', 
            pas_eje = '$pas_eje', 
            tel_eje = '$tel_eje', 
            obs_eje = '$obs_eje',
            id_pla = '$id_pla'
        WHERE id_eje = '$id_eje'
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
    } else {
        
        if (isset($_POST['planteles'])) {
            $planteles = json_decode($_POST['planteles'], true);
            
            $sqlEliminar = "DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje'";
            mysqli_query($db, $sqlEliminar);
            
            if (is_array($planteles) && count($planteles) > 0) {
                foreach ($planteles as $id_pla_checkbox) {
                    $id_pla_checkbox = mysqli_real_escape_string($db, $id_pla_checkbox);
                    
                    $sqlPlantel = "
                        INSERT INTO planteles_ejecutivo (id_eje, id_pla) 
                        VALUES ('$id_eje', '$id_pla_checkbox')
                    ";
                    mysqli_query($db, $sqlPlantel);
                }
            }
        } else {
            if ($ran_eje == 'GC') {
                $sqlCheck = "SELECT COUNT(*) as total FROM planteles_ejecutivo WHERE id_eje = '$id_eje' AND id_pla = '$id_pla'";
                $resultCheck = obtener_datos_consulta($db, $sqlCheck);
                
                if ($resultCheck['datos']['total'] == 0) {
                    $sqlPlantel = "
                        INSERT INTO planteles_ejecutivo (id_eje, id_pla) 
                        VALUES ('$id_eje', '$id_pla')
                    ";
                    mysqli_query($db, $sqlPlantel);
                }
            } else {
                $sqlPlantelDelete = "
                    DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje'
                ";
                mysqli_query($db, $sqlPlantelDelete);
            }
        }
        
        echo json_encode(array('success' => true));
    }

// ============================================================================
// SWITCH - ACTIVAR/DESACTIVAR EJECUTIVO
// ============================================================================
} else if ($accion == 'Switch') {
    
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $est_eje = mysqli_real_escape_string($db, $_POST['est_eje']);
    
    $sql = "
        UPDATE ejecutivo 
        SET est_eje = '$est_eje'
        WHERE id_eje = '$id_eje'
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
    } else {
        echo json_encode(array('success' => true));
    }

// ============================================================================
// PERMISOS - GESTIONAR PERMISOS CDE / AHJ ENDE
// ============================================================================
} else if ($accion == 'Permisos') {
    
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $per_eje = mysqli_real_escape_string($db, $_POST['per_eje']);
    
    $sql = "
        UPDATE ejecutivo 
        SET per_eje = '$per_eje'
        WHERE id_eje = '$id_eje'
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
        exit();
    }
    
    if ($per_eje == 0) {
        
        $sqlEliminacionPlanteles = "
            DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje'
        ";
        
        $resultadoEliminacionPlanteles = mysqli_query($db, $sqlEliminacionPlanteles);
        
        if (!$resultadoEliminacionPlanteles) {
            echo json_encode(array('error' => $sqlEliminacionPlanteles));
            exit();
        }
        
        $sqlEjecutivo = "
            SELECT id_pla FROM ejecutivo WHERE id_eje = '$id_eje'
        ";
        $datosEjecutivo = obtener_datos_consulta($db, $sqlEjecutivo);
        $id_pla_aux = $datosEjecutivo['datos']['id_pla'];

        $sqlInsercionPlanteles = "
            INSERT INTO planteles_ejecutivo (id_pla, id_eje) 
            VALUES ($id_pla_aux, $id_eje)
        ";
        $resultadoInsercionPlanteles = mysqli_query($db, $sqlInsercionPlanteles);
        
        if (!$resultadoInsercionPlanteles) {
            echo json_encode(array('error' => $sqlInsercionPlanteles));
            exit();
        }
    }

    if ($per_eje == 2) {
        
        $sqlEliminacionPlanteles = "
            DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje' 
        ";
        $resultadoEliminacionPlanteles = mysqli_query($db, $sqlEliminacionPlanteles);

        if (!$resultadoEliminacionPlanteles) {
            echo json_encode(array('error' => $sqlEliminacionPlanteles));
            exit();
        }
        
        $sqlEjecutivo = "SELECT id_pla FROM ejecutivo WHERE id_eje = '$id_eje'";
        $datosEjecutivo = obtener_datos_consulta($db, $sqlEjecutivo);
        $id_pla_ejecutivo = $datosEjecutivo['datos']['id_pla'];
        
        $sqlCadena = "SELECT id_cad1 FROM plantel WHERE id_pla = '$id_pla_ejecutivo'";
        $datosCadena = obtener_datos_consulta($db, $sqlCadena);
        $id_cad = $datosCadena['datos']['id_cad1'];
        
        $sqlPlanteles = "
            SELECT * FROM plantel WHERE id_cad1 = '$id_cad'
        ";
        $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

        if (!$resultadoPlanteles) {
            echo json_encode(array('error' => $sqlPlanteles));
            exit();
        }
        
        while ($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
            $id_pla = $filaPlanteles['id_pla'];
            $sqlInsercionPlanteles = "
                INSERT INTO planteles_ejecutivo (id_pla, id_eje) 
                VALUES ($id_pla, $id_eje)
            ";
            $resultadoInsercionPlanteles = mysqli_query($db, $sqlInsercionPlanteles);
            
            if (!$resultadoInsercionPlanteles) {
                echo json_encode(array('error' => $sqlInsercionPlanteles));
                exit();
            }
        }
    }
    
    echo json_encode(array('success' => true));

// ============================================================================
// DESPLIEGUE - OBTENER DATOS DE EJECUTIVO
// ============================================================================
} else if ($accion == 'Despliegue') {
    
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    $sql = "SELECT * FROM ejecutivo WHERE id_eje = '$id_eje'";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
    } else {
        $datos = mysqli_fetch_assoc($resultado);
        echo json_encode($datos);
    }

// ============================================================================
// OBTENER PLANTELES DEL EJECUTIVO (PARA CHECKBOXES EN MODAL)
// ============================================================================
} else if ($accion == 'ObtenerPlantelesEjecutivo') {
    
    $id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
    
    $sqlEjecutivo = "SELECT id_pla FROM ejecutivo WHERE id_eje = '$id_eje'";
    $datosEjecutivo = obtener_datos_consulta($db, $sqlEjecutivo);
    
    if (!isset($datosEjecutivo['datos']['id_pla'])) {
        echo json_encode(array(
            'success' => false,
            'error' => 'No se pudo obtener el plantel del ejecutivo'
        ));
        exit();
    }
    
    $id_pla_ejecutivo = $datosEjecutivo['datos']['id_pla'];
    
    $sqlCadena = "SELECT id_cad1 FROM plantel WHERE id_pla = '$id_pla_ejecutivo'";
    $datosCadena = obtener_datos_consulta($db, $sqlCadena);
    
    if (!isset($datosCadena['datos']['id_cad1'])) {
        echo json_encode(array(
            'success' => false,
            'error' => 'No se pudo obtener la cadena del plantel'
        ));
        exit();
    }
    
    $id_cad = $datosCadena['datos']['id_cad1'];
    
    $sqlPlanteles = "
        SELECT id_pla, nom_pla
        FROM plantel
        WHERE id_cad1 = '$id_cad'
        ORDER BY nom_pla ASC
    ";
    
    $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
    
    if (!$resultadoPlanteles) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al consultar planteles: ' . mysqli_error($db)
        ));
        exit();
    }
    
    $planteles = array();
    
    while ($fila = mysqli_fetch_assoc($resultadoPlanteles)) {
        $sqlCheck = "
            SELECT COUNT(*) as total
            FROM planteles_ejecutivo
            WHERE id_eje = '$id_eje' AND id_pla = '{$fila['id_pla']}'
        ";
        $resultCheck = obtener_datos_consulta($db, $sqlCheck);
        
        $planteles[] = array(
            'id_pla' => $fila['id_pla'],
            'nom_pla' => $fila['nom_pla'],
            'asignado' => ($resultCheck['datos']['total'] > 0)
        );
    }
    
    echo json_encode(array(
        'success' => true,
        'planteles' => $planteles
    ));

// ============================================================================
// PURGAR INACTIVOS - DESACTIVAR CUENTAS CON >10 DÍAS SIN CONEXIÓN
// ============================================================================
} else if ($accion == 'PurgarInactivos') {
    
    $sqlInactivos = "
        SELECT id_eje, nom_eje, ult_eje
        FROM ejecutivo
        WHERE DATEDIFF(NOW(), ult_eje) > 10
        AND eli_eje = 'Activo'
        AND tip_eje = 'Ejecutivo'
    ";
    
    $resultadoInactivos = mysqli_query($db, $sqlInactivos);
    
    if (!$resultadoInactivos) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al consultar inactivos: ' . mysqli_error($db)
        ));
        exit();
    }
    
    $contador = 0;
    $errores = array();
    
    while ($fila = mysqli_fetch_assoc($resultadoInactivos)) {
        $id_eje = $fila['id_eje'];
        
        $sqlPurgar = "
            UPDATE ejecutivo 
            SET eli_eje = 'Inactivo'
            WHERE id_eje = '$id_eje'
        ";
        
        $resultadoPurgar = mysqli_query($db, $sqlPurgar);
        
        if (!$resultadoPurgar) {
            $errores[] = "Error al purgar ejecutivo {$fila['nom_eje']} (ID: $id_eje)";
        } else {
            $contador++;
        }
    }
    
    if (count($errores) > 0) {
        echo json_encode(array(
            'success' => false,
            'error' => implode(', ', $errores),
            'purgados' => $contador
        ));
    } else {
        echo json_encode(array(
            'success' => true,
            'purgados' => $contador,
            'mensaje' => "Se desactivaron $contador cuentas inactivas"
        ));
    }
    
// ============================================================================
// OBTENER TODOS LOS PLANTELES (PARA ALTA SIN ID_EJE)
// ============================================================================
} else if ($accion == 'ObtenerTodosPlanteles') {
        
    $sqlCadena = "SELECT id_pla FROM ejecutivo WHERE id_eje = '$id_eje'";
    $datosCadena = obtener_datos_consulta($db, $sqlCadena);
    $id_pla_usuario = $datosCadena['datos']['id_pla'];
    
    $sqlCadenaFinal = "SELECT id_cad1 FROM plantel WHERE id_pla = '$id_pla_usuario'";
    $datosCadenaFinal = obtener_datos_consulta($db, $sqlCadenaFinal);
    $id_cad = $datosCadenaFinal['datos']['id_cad1'];
    
    $sqlPlanteles = "
        SELECT id_pla, nom_pla
        FROM plantel
        WHERE id_cad1 = '$id_cad'
        ORDER BY nom_pla ASC
    ";
    
    $resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
    
    if (!$resultadoPlanteles) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al consultar planteles: ' . mysqli_error($db)
        ));
        exit();
    }
    
    $planteles = array();
    
    while ($fila = mysqli_fetch_assoc($resultadoPlanteles)) {
        $planteles[] = array(
            'id_pla' => $fila['id_pla'],
            'nom_pla' => $fila['nom_pla']
        );
    }
    
    echo json_encode(array(
        'success' => true,
        'planteles' => $planteles
    ));

// ============================================================================
// ============================================================================
// ACCIONES DE METAS
// ============================================================================
// ============================================================================

// ============================================================================
// CREAR META
// ============================================================================
} else if ($accion == 'CrearMeta') {
    
    $id_eje5 = mysqli_real_escape_string($db, $_POST['id_eje5']);
    $can_met = mysqli_real_escape_string($db, $_POST['can_met']);
    $rub_met = mysqli_real_escape_string($db, $_POST['rub_met']);
    $reg_met = mysqli_real_escape_string($db, $_POST['reg_met']);
    
    if (empty($id_eje5) || empty($can_met) || empty($rub_met) || empty($reg_met)) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Todos los campos son requeridos'
        ));
        exit();
    }
    
    $rubrosValidos = array('Registro', 'Cita', 'CitaEfectiva', 'Contacto');
    if (!in_array($rub_met, $rubrosValidos)) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Rubro inválido'
        ));
        exit();
    }
    
    $sql = "
        INSERT INTO meta (id_eje5, can_met, rub_met, reg_met, fec_met)
        VALUES ('$id_eje5', '$can_met', '$rub_met', '$reg_met', NOW())
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al crear meta: ' . mysqli_error($db)
        ));
    } else {
        echo json_encode(array(
            'success' => true,
            'id_met' => mysqli_insert_id($db)
        ));
    }

// ============================================================================
// OBTENER META (para edición)
// ============================================================================
} else if ($accion == 'ObtenerMeta') {
    
    $id_met = mysqli_real_escape_string($db, $_POST['id_met']);
    
    $sql = "SELECT * FROM meta WHERE id_met = '$id_met'";
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado || mysqli_num_rows($resultado) == 0) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Meta no encontrada'
        ));
        exit();
    }
    
    $meta = mysqli_fetch_assoc($resultado);
    $id_eje5 = $meta['id_eje5'];
    $rub_met = $meta['rub_met'];
    $reg_met = substr($meta['reg_met'], 0, 10);
    
    $logrado = 0;
    
    switch($rub_met) {
        case 'Registro':
            $sqlLogrado = "SELECT obtener_registros_ejecutivo($id_eje5, '$reg_met', '$reg_met') AS total";
            break;
        case 'Cita':
            $sqlLogrado = "SELECT obtener_citas_ejecutivo($id_eje5, '$reg_met', '$reg_met') AS total";
            break;
        case 'CitaEfectiva':
            $sqlLogrado = "SELECT obtener_citas_efectivas_ejecutivo($id_eje5, '$reg_met', '$reg_met') AS total";
            break;
        case 'Contacto':
            $sqlLogrado = "SELECT obtener_contactos_ejecutivo($id_eje5, '$reg_met', '$reg_met') AS total";
            break;
        default:
            $sqlLogrado = "SELECT 0 AS total";
    }
    
    $resultLogrado = mysqli_query($db, $sqlLogrado);
    if ($resultLogrado) {
        $filaLogrado = mysqli_fetch_assoc($resultLogrado);
        $logrado = isset($filaLogrado['total']) ? $filaLogrado['total'] : 0;
    }
    
    echo json_encode(array(
        'success' => true,
        'meta' => $meta,
        'logrado' => $logrado
    ));

// ============================================================================
// ACTUALIZAR META
// ============================================================================
} else if ($accion == 'ActualizarMeta') {
    
    $id_met = mysqli_real_escape_string($db, $_POST['id_met']);
    $can_met = mysqli_real_escape_string($db, $_POST['can_met']);
    $rub_met = mysqli_real_escape_string($db, $_POST['rub_met']);
    $reg_met = mysqli_real_escape_string($db, $_POST['reg_met']);
    
    if (empty($id_met) || empty($can_met) || empty($rub_met) || empty($reg_met)) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Todos los campos son requeridos'
        ));
        exit();
    }
    
    $rubrosValidos = array('Registro', 'Cita', 'CitaEfectiva', 'Contacto');
    if (!in_array($rub_met, $rubrosValidos)) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Rubro inválido'
        ));
        exit();
    }
    
    $sql = "
        UPDATE meta 
        SET can_met = '$can_met', 
            rub_met = '$rub_met', 
            reg_met = '$reg_met'
        WHERE id_met = '$id_met'
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al actualizar meta: ' . mysqli_error($db)
        ));
    } else {
        echo json_encode(array('success' => true));
    }

// ============================================================================
// ELIMINAR META
// ============================================================================
} else if ($accion == 'EliminarMeta') {
    
    $id_met = mysqli_real_escape_string($db, $_POST['id_met']);
    
    if (empty($id_met)) {
        echo json_encode(array(
            'success' => false,
            'error' => 'ID de meta requerido'
        ));
        exit();
    }
    
    $sql = "DELETE FROM meta WHERE id_met = '$id_met'";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al eliminar meta: ' . mysqli_error($db)
        ));
    } else {
        echo json_encode(array('success' => true));
    }

// ============================================================================
// OBTENER METAS DE EJECUTIVO (para listar en periodo)
// ============================================================================
} else if ($accion == 'ObtenerMetasEjecutivo') {
    
    $id_eje5 = mysqli_real_escape_string($db, $_POST['id_eje5']);
    $inicio = mysqli_real_escape_string($db, $_POST['inicio']);
    $fin = mysqli_real_escape_string($db, $_POST['fin']);
    
    $sql = "
        SELECT *
        FROM meta
        WHERE id_eje5 = '$id_eje5'
        AND DATE(reg_met) BETWEEN '$inicio' AND '$fin'
        ORDER BY reg_met ASC
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if (!$resultado) {
        echo json_encode(array(
            'success' => false,
            'error' => 'Error al consultar metas: ' . mysqli_error($db)
        ));
        exit();
    }
    
    $metas = array();
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $metas[] = $fila;
    }
    
    echo json_encode(array(
        'success' => true,
        'metas' => $metas
    ));

// ============================================================================
// ACCIÓN NO RECONOCIDA
// ============================================================================
} else {

    echo json_encode(array(
        'error' => 'Acción no reconocida',
        'accion_recibida' => $accion,
        'post_completo' => $_POST
    ));
}
?>