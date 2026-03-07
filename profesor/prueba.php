<?php
    

    function obtenerDatosTablaTareasServer( $id_ent_cop ){
        require('../../includes/conexion.php');

        $datos = array();
        $datos['#'] = '';
        $datos['nombre'] = '';
        $datos['fecha_de_entrega'] = '';
        $datos['tarea'] = '';
        $datos['retroalimentacion'] = '';
        $datos['puntos'] = '';

        $sqlTareas = "
            SELECT *
            FROM sub_hor
            INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
            INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
            INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
            INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
            INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
            INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
            WHERE id_ent_cop = '$id_ent_cop' AND est_alu_hor = 'Activo'
            ORDER BY app_alu, apm_alu ASC
        ";

            //echo $sqlTareas;

        $resultadoTareas = mysqli_query( $db, $sqlTareas );
        $i = 1;
        while ($filaTareas = mysqli_fetch_assoc($resultadoTareas)) {

            $id_alu_ram6 = $filaTareas['id_alu_ram'];
            $id_ent_cop1 = $filaTareas['id_ent_cop'];

            $sqlArchivoTarea = "

                SELECT *
                FROM tarea 
                INNER JOIN alu_ram ON alu_ram.id_alu_ram = tarea.id_alu_ram6
                WHERE id_alu_ram6 = '$id_alu_ram6' AND id_ent_cop1 = '$id_ent_cop1'
            ";

            $resultadoTareaValidacion = mysqli_query($db, $sqlArchivoTarea);

            $filaTareaValidacion = mysqli_fetch_assoc($resultadoTareaValidacion);
            // echo obtenerValidacionAlumnoActividadServer( 'Entregable', $id_ent_cop, $id_alu_ram6 );
            if ( obtenerValidacionAlumnoActividadServer( 'Entregable', $id_ent_cop, $id_alu_ram6 ) > 0 ) {
            // VALIDACION CON SPAN DE ANIMATED animated pulse delay-1s light-green accent-1

            }else{
            // 
            }

            
            $datos['#'] = $i;
            

            $datos['nombre'] = $filaTareas['app_alu']." ".$filaTareas['apm_alu']." ".$filaTareas['nom_alu'];
            

            if ($filaTareaValidacion['fec_tar'] != NULL) {

                $datos['fecha_de_entrega'] = fechaHoraFormateadaCompactaServer($filaTareaValidacion['fec_tar']); 
            
            }else{
            
                $datos['fecha_de_entrega'] = "Pendiente";
            
            }
                

            $resultadoArchivoTarea = mysqli_query($db, $sqlArchivoTarea);

            $filaArchivoTarea = mysqli_fetch_assoc($resultadoArchivoTarea);

            if ($filaArchivoTarea['doc_tar'] == NULL) {
        
                    $datos['fecha_de_entrega'] =  'Nulo';
                
            }else{
        
                $datos['fecha_de_entrega'] = 'Descargar tarea de '.$filaTareas['app_alu']." ".$filaTareas['apm_alu']." ".$filaTareas['nom_alu'];
                
            }

            $id_alu_ram = $filaTareas['id_alu_ram'];
            $id_ent_cop = $filaTareas['id_ent_cop'];

            $sqlCalificacionTarea = "

                SELECT *
                FROM cal_act 
                WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop2 = '$id_ent_cop'
            ";


            $resultadoCalificacionTarea = mysqli_query($db, $sqlCalificacionTarea);

            $filaConsultaCalificacion = mysqli_fetch_assoc($resultadoCalificacionTarea);

            if ($filaConsultaCalificacion['ret_cal_act'] == NULL) {
                
                $datos['retroalimentacion'] = '';
    
            }else{

                $datos['retroalimentacion'] = $filaConsultaCalificacion['ret_cal_act'];

            }

        

            if ($filaConsultaCalificacion['pun_cal_act'] == NULL) {
        
                $datos['puntos'] = '0';

            }else{

                $datos['puntos'] = $filaConsultaCalificacion['pun_cal_act'];

            }

            $i++;
        }
    }
?>  