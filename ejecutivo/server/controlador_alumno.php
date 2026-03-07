<?php  
    // CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    // ========== SECCIÓN 1: ELIMINACIÓN POR ID_CIT ==========
    if( isset( $_POST['id_cit'] ) ){
        $id_cit = $_POST['id_cit'];
        $sql = "DELETE FROM alumno WHERE id_cit1 = '$id_cit'";
        $resultado = mysqli_query( $db, $sql );

        $response = array();
        if ($resultado) {
            $response['status'] = 200;
            $response['message'] = "Actualización exitosa";
        } else {
            $response['status'] = 500;
            $response['message'] = "Error en la actualización";
            $response['query'] = $sql;
        }
        echo json_encode($response);
        exit;
    }
    
    // ========== SECCIÓN 2: OBTENER ALUMNO ==========
    else if( isset( $_POST['obtener_alumno'] ) ){
        $id_alu_ram = $_POST['id_alu_ram'];
        $sql = "
            SELECT * 
            FROM alumno
            INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
            WHERE id_alu_ram = $id_alu_ram
        ";
        $resultado = mysqli_query($db, $sql);
        $datos = mysqli_fetch_assoc($resultado);
        echo json_encode($datos);
        exit;
    } 
    
    // ========== SECCIÓN 3: ELIMINAR ALUMNO ==========
    else if( isset( $_POST['eliminar_alumno'] ) ) {
        $id_alu_ram = $_POST['id_alu_ram'];
        $sqlAlumno = "SELECT * FROM alu_ram WHERE id_alu_ram = $id_alu_ram";
        $id_alu = obtener_datos_consulta( $db, $sqlAlumno )['datos']['id_alu1'];

        $sql = "DELETE FROM alumno WHERE id_alu = '$id_alu'";
        $resultado = mysqli_query($db, $sql);

        $response = array();
        if ($resultado) {
            $response['status'] = 200;
            $response['message'] = "Actualización exitosa";
        } else {
            $response['status'] = 500;
            $response['message'] = "Error en la actualización";
            $response['query'] = $sql;
        }
        echo json_encode($response);
        exit;
    } 
    
    // ========== SECCIÓN 4: EDITAR ALUMNO ==========
    else if( isset( $_POST['editar_alumno'] ) ) {
        $id_alu = $_POST['id_alu'];
        $nom_alu = $_POST['nom_alu'];
        $app_alu = $_POST['app_alu'];
        $apm_alu = $_POST['apm_alu'];
        $tel_alu = $_POST['tel_alu'];
        $gen_alu = $_POST['gen_alu'];
        $nac_alu = $_POST['nac_alu'];
        $cur_alu = $_POST['cur_alu'];
        $tut_alu = $_POST['tut_alu'];
        $tel2_alu = $_POST['tel2_alu'];
        $ocu_alu = $_POST['ocu_alu'];
        $dir_alu = $_POST['direccion'];
        $cp_alu = $_POST['cp_alu'];
        $cor_alu = $_POST['correo'];
        $pas_alu = $_POST['pas_alu'];
        $mon_alu_ram = $_POST['mon_alu_ram'];
        
        if( $_POST['tie_alu_ram'] == 0 ){
            $tie_alu_ram = NULL;
        } else {
            $tie_alu_ram = $_POST['tie_alu_ram'];
        }
        $id_alu_ram = $_POST['id_alu_ram'];

        // UPDATE DE alu_ram
        $sql2 = "
            UPDATE alu_ram
            SET
                tie_alu_ram = '$tie_alu_ram',
                mon_alu_ram = '$mon_alu_ram'
            WHERE id_alu_ram = '$id_alu_ram'
        ";
        $resultado2 = mysqli_query( $db, $sql2 );

        // UPDATE DE alumno
        $sql = "
            UPDATE alumno
            SET
                nom_alu = '$nom_alu',
                app_alu = '$app_alu',
                apm_alu = '$apm_alu',
                tel_alu = '$tel_alu',
                gen_alu = '$gen_alu',
                nac_alu = '$nac_alu',
                cur_alu = '$cur_alu',
                tut_alu = '$tut_alu',
                tel2_alu = '$tel2_alu',
                ocu_alu = '$ocu_alu',
                dir_alu = '$dir_alu',
                cp_alu = '$cp_alu',
                cor_alu = '$cor_alu',
                pas_alu = '$pas_alu'
            WHERE id_alu = '$id_alu'
        ";
        $resultado = mysqli_query( $db, $sql );

        $response = array();
        if ($resultado) {
            $response['status'] = 200;
            $response['message'] = "Actualización exitosa";
        } else {
            $response['status'] = 500;
            $response['message'] = "Error en la actualización";
            $response['query'] = $sql;
        }
        echo json_encode($response);
        exit;
    } 
    
    // ========== SECCIÓN 5: BÚSQUEDA Y LISTADO DE ALUMNOS ==========
    else {
        
        // 🔥 OBTENER FECHAS PARA FILTRO DE ADEUDOS
        // 🔥 OBTENER FECHAS SOLO SI EL FILTRO ESTÁ HABILITADO
		$fecha_inicio = '';
		$fecha_fin = '';
		$filtroHabilitado = isset($_POST['filtro_periodo_habilitado']) && $_POST['filtro_periodo_habilitado'] === 'true';

		if($filtroHabilitado && isset($_POST['fecha_inicio_mes']) && isset($_POST['fecha_fin_mes']) 
		&& !empty($_POST['fecha_inicio_mes']) && !empty($_POST['fecha_fin_mes'])) {
            $fecha_inicio = mysqli_real_escape_string($db, $_POST['fecha_inicio_mes']);
            $fecha_fin = mysqli_real_escape_string($db, $_POST['fecha_fin_mes']);
        }
        
        // ===== 5.1 VERIFICAR SI HAY FILTRO MAESTRO (ID_GEN) =====
        if(isset($_POST['id_gen_maestro']) && !empty($_POST['id_gen_maestro'])) {
            // 🎯 FILTRO MAESTRO: Solo buscar por generación específica
            $id_gen = intval($_POST['id_gen_maestro']);
            
            
            $sql = "
                SELECT 
                    alu_ram.id_alu_ram,
                    CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu) AS nom_alu,
                    alumno.bol_alu,
                    alumno.tel_alu,
                    alumno.ing_alu,
                    alumno.tel2_alu,
                    generacion.nom_gen,
                    generacion.nom_gen AS estado_grupo,
                    CASE 
                        WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' THEN
                            obtener_adeudo_alumno_periodo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin')
                        ELSE
                            OBTENER_ADEUDO_ALUMNO(alu_ram.id_alu_ram)
                    END AS adeudo_alumno,
                    OBTENER_ESTATUS_GENERAL(alu_ram.id_alu_ram, generacion.fin_gen, alu_ram.est1_alu_ram) AS estatus_general,
                    alumno.cor_alu,
                    alumno.pas_alu,
                    alu_ram.id_gen1,
                    OBTENER_CARGA_ALUMNO(alu_ram.id_alu_ram) AS carga_alumno,
                    OBTENER_NOMBRE_EJECUTIVO_ALUMNO(alu_ram.id_alu1) AS nom_eje,
                    obtener_documento_pendiente(alu_ram.id_alu_ram) AS documento_pendiente, 
                    obtener_actividades_vencidas(alu_ram.id_alu_ram) AS actividades_vencidas 
                FROM alu_ram
                JOIN alumno ON (alumno.id_alu = alu_ram.id_alu1)
                JOIN rama ON (rama.id_ram = alu_ram.id_ram3)
                JOIN plantel ON (plantel.id_pla = rama.id_pla1)
                JOIN generacion ON (generacion.id_gen = alu_ram.id_gen1)
                WHERE alu_ram.id_gen1 = $id_gen
				ORDER BY alu_ram.id_alu_ram DESC
            ";
        } 
        
        // ===== 5.2 BÚSQUEDA NORMAL CON FILTROS =====
        else {
            
            // 📋 DETECTAR TIPO DE BÚSQUEDA
            $esBusquedaNormal = false;
            $datosAlumno = '';
            
            if(isset($_POST['obtener_todos']) && $_POST['obtener_todos'] == true) {
                $datosAlumno = '';
                $esBusquedaNormal = false;
            } else {
                $datosAlumno = isset($_POST['datosAlumno']) ? trim(preg_replace('!\s+!', ' ', $_POST['datosAlumno'])) : '';
                $esBusquedaNormal = true;
            }
            
            // 🏢 MANEJO DE PLANTELES
            $plantelesCondicion = "";
            $plantelesArray = array();
            
            if(isset($_POST['planteles_seleccionados']) && !empty($_POST['planteles_seleccionados'])) {
                $plantelesArray = $_POST['planteles_seleccionados'];
            }
            elseif(isset($_POST['planteles_ajax']) && !empty($_POST['planteles_ajax'])) {
                $plantelesArray = $_POST['planteles_ajax'];
            }
            else {
                $plantelesArray = array($plantel);
            }
            
            if(!empty($plantelesArray)) {
                $plantelesLimpios = array();
                foreach($plantelesArray as $p) {
                    $plantelesLimpios[] = intval($p);
                }
                $plantelesStr = implode(',', $plantelesLimpios);
                $plantelesCondicion = " AND plantel.id_pla IN ($plantelesStr)";
            } else {
                $plantelesCondicion = " AND plantel.id_pla = '$plantel'";
            }
            
            // 📊 MANEJO DE ESTATUS
            $estatusArray = array();
            if(isset($_POST['estatus_ajax']) && !empty($_POST['estatus_ajax'])) {
                $estatusArray = $_POST['estatus_ajax'];
            }
            
            // 📚 MANEJO DE GRUPOS
            $gruposArray = array();
            if(isset($_POST['grupos_ajax']) && !empty($_POST['grupos_ajax'])) {
                $gruposArray = $_POST['grupos_ajax'];
            }
            
            $gruposCondicion = "";
            if(!empty($gruposArray)) {
                $hoy = date('Y-m-d');
                $condicionesGrupo = array();
                
                foreach($gruposArray as $grupo) {
                    switch($grupo) {
                        case 'EN CURSO':
                            $condicionesGrupo[] = "(generacion.ini_gen <= '$hoy' AND generacion.fin_gen >= '$hoy')";
                            break;
                        case 'POR COMENZAR':
                            $condicionesGrupo[] = "(generacion.ini_gen > '$hoy')";
                            break;
                        case 'VENCIDOS':
                            $condicionesGrupo[] = "(generacion.fin_gen < '$hoy')";
                            break;
                    }
                }
                
                if(!empty($condicionesGrupo)) {
                    $gruposCondicion = " AND (" . implode(' OR ', $condicionesGrupo) . ")";
                }
            }
            
            
            // 🔍 QUERY PRINCIPAL
            $hoy = date('Y-m-d');
            $sql = "
                SELECT 
                    alu_ram.id_alu_ram,
                    CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu) AS nom_alu,
                    alumno.bol_alu,
                    alumno.tel_alu,
                    alumno.ing_alu,
                    alumno.tel2_alu,
                    generacion.nom_gen,
                    CASE 
                        WHEN generacion.ini_gen > '$hoy' THEN 'POR COMENZAR'
                        WHEN generacion.ini_gen <= '$hoy' AND generacion.fin_gen >= '$hoy' THEN 'EN CURSO'
                        ELSE 'VENCIDOS'
                    END AS estado_grupo,
                    CASE 
                        WHEN '$fecha_inicio' != '' AND '$fecha_fin' != '' THEN
                            obtener_adeudo_alumno_periodo(alu_ram.id_alu_ram, '$fecha_inicio', '$fecha_fin')
                        ELSE
                            OBTENER_ADEUDO_ALUMNO(alu_ram.id_alu_ram)
                    END AS adeudo_alumno,
                    OBTENER_ESTATUS_GENERAL(alu_ram.id_alu_ram, generacion.fin_gen, alu_ram.est1_alu_ram) AS estatus_general,
                    alumno.cor_alu,
                    alumno.pas_alu,
                    alu_ram.id_gen1,
                    OBTENER_CARGA_ALUMNO(alu_ram.id_alu_ram) AS carga_alumno,
                    OBTENER_NOMBRE_EJECUTIVO_ALUMNO(alu_ram.id_alu1) AS nom_eje,
                    obtener_documento_pendiente(alu_ram.id_alu_ram) AS documento_pendiente, 
                    obtener_actividades_vencidas(alu_ram.id_alu_ram) AS actividades_vencidas 
                FROM alu_ram
                JOIN alumno ON (alumno.id_alu = alu_ram.id_alu1)
                JOIN rama ON (rama.id_ram = alu_ram.id_ram3)
                JOIN plantel ON (plantel.id_pla = rama.id_pla1)
                JOIN generacion ON (generacion.id_gen = alu_ram.id_gen1)
                WHERE 1=1 
				$plantelesCondicion 
				$gruposCondicion
            ";
        
            // 🔎 Agregar filtro de búsqueda por texto
            if ($esBusquedaNormal && isset($_POST['datosAlumno']) && $_POST['datosAlumno'] != '') {
                $sql .= " AND 
                    ( alu_ram.id_alu_ram LIKE '%$datosAlumno%' OR  
                      alumno.bol_alu LIKE '%$datosAlumno%' OR  
                      UPPER(REPLACE(CONCAT_WS(' ', alumno.nom_alu, alumno.app_alu, alumno.apm_alu), '  ', ' ')) LIKE UPPER(_utf8 '%$datosAlumno%') COLLATE utf8_general_ci OR 
                      UPPER(generacion.nom_gen) LIKE UPPER(_utf8 '%$datosAlumno%') COLLATE utf8_general_ci OR 
                      alumno.tel_alu LIKE '%$datosAlumno%' OR  
                      UPPER(alumno.cor_alu) LIKE UPPER('%$datosAlumno%') ) 
                ";
            }
            
            // 🎯 Filtro de estatus con HAVING
            if(!empty($estatusArray)) {
                $estatusLimpios = array();
                foreach($estatusArray as $e) {
                    $estatusLimpios[] = "'" . mysqli_real_escape_string($db, $e) . "'";
                }
                $estatusStr = implode(',', $estatusLimpios);
                $sql .= " HAVING estatus_general IN ($estatusStr)";
            }
        
            $sql .= ' ORDER BY alu_ram.id_alu_ram DESC';
        }
    
        // ===== 5.3 EJECUTAR CONSULTA =====
        $resultado = mysqli_query($db, $sql);
        
        if (!$resultado) {
            $error = array(
                'error' => true,
                'mensaje' => mysqli_error($db),
                'sql' => $sql
            );
            echo json_encode($error);
            exit;
        }
        
        // ===== 5.4 PROCESAR RESULTADOS =====
        $alumnos = array();
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $alumnos[] = array(
                "ID" => $fila['id_alu_ram'],
                "F. INGRESO" => fechaFormateadaCompacta4($fila['ing_alu']),
                "NOMBRE" => $fila['nom_alu'],
                "MATRICULA" => $fila['bol_alu'],
                "TELÉFONOS" => $fila['tel_alu'] . ' / ' . $fila['tel2_alu'],
                "GPO" => $fila['nom_gen'] . ' (' . $fila['estado_grupo'] . ')',
                "ADEUDOS" => formatearDinero($fila['adeudo_alumno']),
                "ESTATUS" => $fila['estatus_general'],
                "CORREO" => $fila['cor_alu'],
                "CONTRASEÑA" => $fila['pas_alu'],
                "EXPEDIENTE" => $fila['documento_pendiente'],
                "ACT VENCIDAS" => $fila['actividades_vencidas'],
                "ID." => $fila['id_gen1'],
                "CARGA" => $fila['carga_alumno'],
                "CONSULTOR" => $fila['nom_eje']
            );
        }
         
        echo json_encode($alumnos);
    }
?>