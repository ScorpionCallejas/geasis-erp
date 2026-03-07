<?php  
    include('inc/header.php');
    
    if( ( $tipoUsuario == 'Ejecutivo' && $foto == NULL ) || ( $estatusUsuario == 'Inactivo' ) ){
        header('location: perfil.php');
    }
    obtenerLoader();
?>

<!-- MODAL CITA -->
<div id="modal_cita" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <form id="formulario_nueva_cita">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Nueva cita</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">    
                    <span class="letraPequena">Todos los campos con * son obligatorios</span>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="letraPequena">*NOMBRE</span>
                            <input type="text" id="nom_cit_for" name="nom_cit_for" class="form-control" value="" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="letraPequena">*TELÉFONO</span>
                            <input type="text" id="tel_cit_for" name="tel_cit_for" class="form-control" value="" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="letraPequena">*EDAD</span>
                            <input type="text" id="eda_cit_for" name="eda_cit_for" class="form-control" value="" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="letraPequena">*MODALIDAD</span>
                            <select id="pro_cit_for" name="pro_cit_for" class="form-control">
                                <option value="PREPA-6-MESES" selected>PREPA-6-MESES</option>
                                <option value="PREPA-EMPRENDE">PREPA-EMPRENDE</option>
                                <option value="DIPLOMADO">DIPLOMADO</option>
                                <!-- <option value="EXAMEN ÚNICO">EXAMEN ÚNICO</option> -->
                                <option value="BACH-NEGOCIOS">BACH-NEGOCIOS</option>
                                <option value="LICENCIATURA">LICENCIATURA</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <span class="letraPequena">*FEC. CITA</span>
                            <?php
                                $manana = date('Y-m-d', strtotime('+1 day'));
                            ?>
                            <input type="date" id="cit_cit_for" name="cit_cit_for" class="form-control" value="<?php echo $manana; ?>" required="">
                        </div>

                        <div class="col-md-6">
                            <label for="horaInput" class="letraPequena">*HORARIO</label>
                            <?php
                                $defaultTime = '15:00'; // 3:00 PM en formato de 24 horas
                            ?>
                            <input type="time" id="hor_cit_for" name="hor_cit_for" class="form-control" step="60" value="<?php echo $defaultTime; ?>" required="">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="letraPequena">*TIP. DE CITA</span>
                            <select id="tip_cit_for" name="tip_cit_for" class="form-control">
                                <option value="Videoconferencia" selected>Videoconferencia</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Llamada">Llamada</option>
                                <option value="Mensaje">Mensaje</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <span class="letraPequena">OBSERVACIONES</span>
                            <textarea id="obs_cit_for" name="obs_cit_for" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btn_guardar_cita">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.FIN MODAL CITA -->




<!-- Standard modal content -->
<div id="modal_registro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <form id="formulario_agregar_alumno">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Nuevo registro</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Datos académicos</h6>

                    <span class="letraPequena">Selecciona CDE</span>

                    <select id="selectorPlantel" class="form-control">
                        <?php
                            $sqlPlantel = "
                                SELECT * 
                                FROM plantel 
                                WHERE id_cad1 = '$cadena'
                                ORDER BY id_pla = '$plantel' DESC 
                            ";
                            $resultadoPlantel = mysqli_query($db, $sqlPlantel);
                            $bool = true;
                            while ($filaPlantel = mysqli_fetch_assoc($resultadoPlantel)) {
                                ?>
                                <option value="<?php echo $filaPlantel['id_pla']; ?>" <?php echo ($bool == true) ? 'selected="selected"' : ''; ?>>
                                    <?php echo $filaPlantel['nom_pla']; ?>
                                </option>
                                <?php
                                $bool = false;
                            }
                        ?>
                    </select>
                    
                    <span class="letraPequena">Selecciona Programa</span>
                    <div id="contenedor_programas">

                    </div>


                    <span class="letraPequena">Selcciona Grupo</span>
                    <div id="contenedor_grupos">

                    </div>
                        

                    <div>
                        <hr>
                        <div id="contenedor_tiempo">
                        </div>
                    </div>


					<div class="row" style="scroll-behavior: auto; display: none;">
                        <div class="col-12">
                            <div id="contenedor_forma_titulacion"></div>
                        </div>
                    </div>

                    <div class="row" style="scroll-behavior: auto; display: none;">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <div id="contenedor_documentacion_programa"></div>
                        </div>
                    </div>



                    <hr style="display: none;">

                    <h6>Datos generales</h6>
                    <!---------------------- FILA DE NOMBRE COMPLETO ---------------------------------------------------->
                    <div class="row" id="nombre_completo">
                        <div class="col-sm-4">
                            <span class="letraPequena">Nombre</span>
                            <input type="text" id="nom_alu" name="nom_alu" class="form-control correoCompuesto"
                                required>
                        </div>
                        <div class="col-sm-4">
                            <span class="letraPequena">Apellído paterno</span>
                            <input type="text" id="app_alu" name="app_alu" class="form-control correoCompuesto"
                                required>
                        </div>
                        <div class="col-sm-4">
                            <span class="letraPequena">Apellído materno</span>
                            <input type="text" id="apm_alu" name="apm_alu" class="form-control" required>
                        </div>

                    </div>
                    <!---------------------- FILA DE TELEFONO Y GENERO ---------------------------------------------------->
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="letraPequena">Teléfono</span>
                            <input type="text" id="tel_alu" name="tel_alu" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <span class="letraPequena">Género</span>
                            <select class="form-control" id="gen_alu" name="gen_alu">
                                <option>Hombre</option>
                                <option>Mujer</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <span class="letraPequena">Nacimiento</span>
                            <?php
                                $fecha_nacimiento = '2000-01-01';
                            ?>
                            <input type="date" id="nac_alu" name="nac_alu" class="form-control" value="<?php echo $fecha_nacimiento; ?>" required>
                        </div>

                    </div>
                    <!---------------------- FILA DE CURP,TUTOR Y CONTACTO ---------------------------------------------------->
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <span class="letraPequena">CURP</span>
                            <input type="text" id="cur_alu" name="cur_alu" class="form-control" required value="">
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6 col-sm-6">
                            <span class="letraPequena">Tutor</span>
                            <input type="text" id="tut_alu" name="tut_alu" class="form-control" required>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <span class="letraPequena">Teléfono 2</span>
                            <input type="text" id="tel2_alu" name="tel2_alu" class="form-control" required value="">
                        </div>
                    </div>
                    <!---------------------- FILA DE DIRECCION,CP Y OCUPACION ---------------------------------------------------->
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <span class="letraPequena">Ocupación</span>
                            <input type="text" id="ocu_alu" name="ocu_alu" class="form-control" required value="">
                        </div>
                        
                        <div class="col-md-6 col-sm-6">
                            <span class="letraPequena">C.P.</span>
                            <input type="text" id="cp_alu" name="cp_alu" class="form-control" required value="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <span class="letraPequena">Dirección</span>
                            <input type="text" id="direccion" name="direccion" class="form-control" required>
                        </div>
                        
                    </div>
                    <!---------------------- FILA DE CORREO Y CONTRASEÑA ---------------------------------------------------->
                    <div class="row">
                        <div class="col-md-8 col-sm-8">
                            <span class="letraPequena" style="text-transform:uppercase;">Correo</span>
                            <input type="text" id="correo" name="correo" class="form-control" required>

                        </div>
                        <div class="col-md-4 col-sm-4">
                            <span class="letraPequena">Contraseña</span>
                            <input type="text" id="pas_alu" name="pas_alu" class="form-control" required value="1234">
                        </div>
                    </div>
                    <!---------------------- FIN DE DATOS GENERALES ---------------------------------------------------->
                    <hr>
                    <h6>Datos de inscripción</h6>
                    <span class="letraPequena">Cantidad de inscripción</span>
                    <input type="text" id="inscripcion" name="inscripcion" class="form-control" required value="1000">
                    <br>
                    <span class="letraPequena">Selecciona el importe de la colegiatura</span>
                    <input type="text" id="colegiatura" name="colegiatura" class="form-control" required value="1500">

                    <div style="display: none;">
                        <!--  -->
                        <hr>
                        <h6>Referidos</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="letraPequena">Referido 1</span>
                                <input type="text" id="nom_ref1" name="nom_ref1" class="form-control" value="">
                            </div>

                            <div class="col-md-6">
                                <span class="letraPequena">Teléfono 1</span>
                                <input type="text" id="tel_ref1" name="tel_ref1" class="form-control" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <span class="letraPequena">Referido 2</span>
                                <input type="text" id="nom_ref2" name="nom_ref2" class="form-control" value="">
                            </div>

                            <div class="col-md-6">
                                <span class="letraPequena">Teléfono 2</span>
                                <input type="text" id="tel_ref2" name="tel_ref2" class="form-control" value="">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <span class="letraPequena">Referido 3</span>
                                <input type="text" id="nom_ref3" name="nom_ref3" class="form-control" value="">
                            </div>

                            <div class="col-md-6">
                                <span class="letraPequena">Teléfono 3</span>
                                <input type="text" id="tel_ref3" name="tel_ref3" class="form-control" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <span class="letraPequena">Referido 4</span>
                                <input type="text" id="nom_ref4" name="nom_ref4" class="form-control" value="">
                            </div>

                            <div class="col-md-6">
                                <span class="letraPequena">Teléfono 4</span>
                                <input type="text" id="tel_ref4" name="tel_ref4" class="form-control" value="">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <span class="letraPequena">Referido 5</span>
                                <input type="text" id="nom_ref5" name="nom_ref5" class="form-control" value="">
                            </div>

                            <div class="col-md-6">
                                <span class="letraPequena">Teléfono 5</span>
                                <input type="text" id="tel_ref5" name="tel_ref5" class="form-control" value="">
                            </div>
                        </div>
                        <!--  -->
                    </div>
                    




                    <input type="hidden" name="id_cit" id="id_cit">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btn_guardar_alumno">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<!-- start page title -->
<div class="row">
    <div class="col-12">

        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">CITAS</li>
                </ol>
            </div>
            <h4 class="page-title">CITAS</h4>
        </div>

    </div>
</div>
<!-- end page title -->


<!-- FILTROS -->
<div class="row">

    <div class="col-md-4">
        <br>
        <form id="formulario_buscar_cita">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control form-control-sm"
                    placeholder="Buscar por folio, nombre o teléfono" aria-label="Buscar cita" value="">

                <div class="input-group-append">
                    <button class="btn btn-dark waves-effect waves-light btn-sm" type="submit"
                        id="btnBuscar">Buscar</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <div style="">

            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample22"
                    name="seleccionPeriodo" value="Mes">
                <label class="form-check-label" for="materialGroupExample22">Mensual</label>
            </div>

            <?php   
                if( isset($_GET['inicio']) && isset($_GET['fin']) ){
            ?>
                    <!-- Group of material radios - option 1 -->
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11"
                            name="seleccionPeriodo" value="Fecha" checked="">
                        <label class="form-check-label" for="materialGroupExample11">Día(s)</label>
                    </div>

                    <!-- Group of material radios - option 2 -->
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample23"
                            name="seleccionPeriodo" value="Semana" >
                        <label class="form-check-label" for="materialGroupExample23">Semanal</label>
                    </div>
            <?php 
                } else {
            ?>
                    <!-- Group of material radios - option 1 -->
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample11"
                            name="seleccionPeriodo" value="Fecha" checked="">
                        <label class="form-check-label" for="materialGroupExample11">Día(s)</label>
                    </div>

                    <!-- Group of material radios - option 2 -->
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample23"
                            name="seleccionPeriodo" value="Semana" >
                        <label class="form-check-label" for="materialGroupExample23">Semanal</label>
                    </div>

            <?php
                }
            ?>

            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input radioPeriodo" id="materialGroupExample24"
                    name="seleccionPeriodo" value="Rango">
                <label class="form-check-label" for="materialGroupExample24">Rango</label>
            </div>
        </div>

        <!--  -->

        <!--  -->

        <div id="contenedor_mes_annio" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <select class="form-control filtros letraPequena" id="selectorMes">
                        <?php
                        function obtenerSemanasDelMes($mes, $año) {
                            $primerDia = new DateTime("$año-$mes-01");
                            $ultimoDia = clone $primerDia;
                            $ultimoDia->modify('last day of this month');
                            
                            $primerLunes = clone $primerDia;
                            while ($primerLunes->format('N') != 1) {
                                $primerLunes->modify('-1 day');
                            }
                            
                            if ($primerLunes->format('m') != $mes) {
                                $primerLunes->modify('+1 week');
                            }
                            
                            $ultimoLunes = clone $ultimoDia;
                            while ($ultimoLunes->format('N') != 1) {
                                $ultimoLunes->modify('-1 day');
                            }
                            
                            if ($ultimoLunes->format('m') != $mes) {
                                $ultimoLunes->modify('-1 week');
                            }
                            
                            $finUltimaSemana = clone $ultimoLunes;
                            $finUltimaSemana->modify('+6 days');
                            
                            return array(
                                'inicio' => $primerLunes->format('Y-m-d'),
                                'fin' => $finUltimaSemana->format('Y-m-d'),
                                'inicio_formato' => $primerLunes->format('d/m/Y'),
                                'fin_formato' => $finUltimaSemana->format('d/m/Y'),
                                'primera_semana' => $primerLunes->format('W'),
                                'ultima_semana' => $ultimoLunes->format('W')
                            );
                        }

                        // Generar opciones para 2024
                        for ($i = 1; $i <= 12; $i++) {
                            $mes = str_pad($i, 2, "0", STR_PAD_LEFT);
                            $rangoMes = obtenerSemanasDelMes($mes, 2024);
                            
                            echo "<option value='{$mes}' " .
                                "inicio='{$rangoMes['inicio']}' " .
                                "fin='{$rangoMes['fin']}'>" .
                                getMonth($mes) . " 2024 - Semanas " . $rangoMes['primera_semana'] . "-" . 
                                $rangoMes['ultima_semana'] . 
                                " (" . $rangoMes['inicio_formato'] . " al " . $rangoMes['fin_formato'] . ")" .
                                "</option>";
                        }

                        // Generar opciones para 2025
                        $mesActualEntero = date('m');
                        for ($i = 1; $i <= 12; $i++) {
                            $mes = str_pad($i, 2, "0", STR_PAD_LEFT);
                            $rangoMes = obtenerSemanasDelMes($mes, 2025);
                            
                            $selected = ($mes == $mesActualEntero) ? 'selected' : '';
                            echo "<option {$selected} value='{$mes}' " .
                                "inicio='{$rangoMes['inicio']}' " .
                                "fin='{$rangoMes['fin']}'>" .
                                getMonth($mes) . " 2025 - Semanas " . $rangoMes['primera_semana'] . "-" . 
                                $rangoMes['ultima_semana'] . 
                                " (" . $rangoMes['inicio_formato'] . " al " . $rangoMes['fin_formato'] . ")" .
                                "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- SEMANA Y LIBRE -->
        <div id="contenedor_fecha" style="display: none;">

            <?php   
                    if( isset($_GET['inicio']) && isset($_GET['fin']) ){
                ?>
            <div class="row">
                <div class="col-md-6">
                    <input type="date" class="form-control filtros letraPequena" id="inicio"
                        value="<?php echo isset($_GET['inicio']) ? date('Y-m-d', strtotime($_GET['inicio'])) : ''; ?>">

                </div>

                <div class="col-md-6">
                    <input type="date" class="form-control filtros letraPequena" id="fin"
                        value="<?php echo isset($_GET['fin']) ? date('Y-m-d', strtotime($_GET['fin'])) : ''; ?>">

                </div>
            </div>
            <?php 
                    } else {
                ?>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="date" class="form-control filtros letraPequena" id="inicio"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <!-- <div class="col-md-6">
                                <input type="date" class="form-control filtros" id="fin"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div> -->
                        </div>

            <?php
                    }
                ?>
        </div>


        <?php echo obtener_contenedor_semana(); ?>
        <!-- FIN SEMANA Y LIBRE -->


        <!-- RANGO LIBRE -->
        <div id="contenedor_rango" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <input type="date" class="form-control filtros letraPequena" id="inicio_rango"
                        value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6">
                    <input type="date" class="form-control filtros letraPequena" id="fin_rango"
                        value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
        </div>
        <!-- FIN RANGO LIBRE -->

    </div>

    <div class="col-md-4">
        <!-- ASESORES -->
        <?php $aux = 'undefined'; ?>
        <span class="letraPequena">Selecciona asesor</span>
        <select class="filtros form-control letraPequena" id="selector_ejecutivo">
            

            <!-- CASO PLANTEL -->
            <?php
                if ( isset( $_GET['escala'] ) && $_GET['escala'] == 'plantel' ) {
                    $aux = 'caso plantel';
                    $id_pla = $_GET['id_pla'];

                    $sqlPla = "
                        SELECT nom_pla FROM plantel WHERE id_pla = '$id_pla' 
                    ";
                    $datosPla = obtener_datos_consulta( $db, $sqlPla )['datos'];
            ?>
                    <option value="Todos" selected=""><?php echo strtoupper($datosPla['nom_pla']); ?></option>
                    <?php
                        $sqlPlantel = "
                            SELECT *
                            FROM ejecutivo
                            INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                            WHERE plantel.id_pla = '$id_pla' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                        ";

                        //echo $sqlPlantel;
                        $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

                        while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                    ?>

                            <option value="<?php echo $filaPlantel['id_eje']; ?>">
                                <?php echo obtener_rango_usuario($filaPlantel['ran_eje']).' '.$filaPlantel['nom_eje'].' - '.$filaPlantel['nom_pla']; ?>
                            </option>
                    <?php
                        }
                    ?>
            <?php
                } else {
            ?>
                    <!--  -->
                    <!--  -->
                    <?php
                        if( $permisos == 2 ){
                            $aux = 'permisos 2';
                    ?>
                            <!-- PERMISOS AHJ ENDE -->
                            <option value="Todos" selected="">TODOS</option>
                            <?php
                                $sqlPlantel = "
                                    SELECT *
                                    FROM ejecutivo
                                    INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                                    WHERE plantel.id_cad1 = '1' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                ";
                                //echo $sqlPlantel;
                                $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

                                while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                            ?>

                                    <option <?php //echo ( $filaPlantel['id_eje'] == $id_eje )? 'selected': ''; ?>
                                        value="<?php echo $filaPlantel['id_eje']; ?>">
                                        <?php echo obtener_rango_usuario($filaPlantel['ran_eje']).' '.$filaPlantel['nom_eje'].' - '.$filaPlantel['nom_pla']; ?>
                                    </option>
                            <?php
                                }
                            ?>
                            <!-- F PERMISOS AHJ ENDE -->
                    <?php
                        } else if ( $permisos == 1 ) {
                            $aux = 'permisos 1';
                    ?>
                            <!-- PERMISOS CDE -->
                            <option value="Todos" selected="">TODOS</option>
                            <?php
                                $sqlPlantel = "
                                    SELECT *
                                    FROM ejecutivo
                                    INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                                    WHERE ejecutivo.id_pla = '$plantel' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                ";

                                //echo $sqlPlantel;
                                $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

                                while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                            ?>

                                <option value="<?php echo $filaPlantel['id_eje']; ?>">
                                    <?php echo obtener_rango_usuario($filaPlantel['ran_eje']).' '.$filaPlantel['nom_eje'].' - '.$filaPlantel['nom_pla']; ?>
                                </option>
                            <?php
                                }
                            ?>
                            <!-- F PERMISOS CDE -->
                    <?php
                        } else {
                    ?>
                            <!-- SIN PERMISOS -->
                            <?php
                                if( 
                                    ( $rangoUsuario != 'GC' && ( $permisos == 0 || $permisos == NULL ) ) &&
                                    ( $rangoUsuario != 'DC' && ( $permisos == 0 || $permisos == NULL ) )
                                ){
                            ?>
                                    <?php
                                        $aux = 'direccion';

                                        $sqlPlantel = "
                                            SELECT *
                                            FROM ejecutivo
                                            INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                                            WHERE ejecutivo.id_eje = '$id_eje' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                        ";

                                        //echo $sqlPlantel;
                                        $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

                                        while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                                    ?>

                                            <option <?php echo ( $filaPlantel['id_eje'] == $id_eje )? 'selected': ''; ?>
                                                value="<?php echo $filaPlantel['id_eje']; ?>">
                                                <?php echo obtener_rango_usuario($filaPlantel['ran_eje']).' '.$filaPlantel['nom_eje'].' - '.$filaPlantel['nom_pla']; ?>
                                            </option>
                                    <?php
                                        }
                                    ?>
                            <?php
                                } else {
                            ?>
                                    <!--  -->
                                    <?php 
                                        if( !isset( $_GET['escala'] ) ){

                                            $aux = 'sin estructura';
                                            
                                    ?>
                                        <!-- CASO SIN ESTRUCTURA -->

                                        <option value="Todos" selected="">TODOS</option>

                                        <?php
                                            $sqlPlantel = "
                                                SELECT *
                                                FROM ejecutivo
                                                INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                                                WHERE ejecutivo.id_pla = '$plantel' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                            ";

                                            //echo $sqlPlantel;
                                            $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

                                            while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                                        ?>

                                            <option value="<?php echo $filaPlantel['id_eje']; ?>">
                                                <?php echo obtener_rango_usuario($filaPlantel['ran_eje']).' '.$filaPlantel['nom_eje'].' - '.$filaPlantel['nom_pla']; ?>
                                            </option>
                                        <?php
                                            }
                                        ?>

                                        
                                        <!-- F CASO SIN ESTRUCTURA -->

                                            
                                    
                                    <?php
                                        } else if ( isset( $_GET['escala'] ) && $_GET['escala'] == 'ejecutivo' ) {
                                            $aux = 'caso ejecutivo';
                                            $id_eje = $_GET['id_eje'];
                                            $id_pla = $_GET['id_pla'];
                                    ?>
                                        <!-- CASO EJECUTIVO -->
                                        <option value="Todos" selected="">TODOS</option>
                                        <?php
                                            $sqlPlantel = "
                                                SELECT *
                                                FROM ejecutivo
                                                INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                                                WHERE ejecutivo.id_pla = '$id_pla' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                            ";

                                            //echo $sqlPlantel;
                                            $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

                                            while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                                        ?>

                                                <option <?php echo ( $filaPlantel['id_eje'] == $id_eje )? 'selected': ''; ?>
                                                    value="<?php echo $filaPlantel['id_eje']; ?>">
                                                    <?php echo obtener_rango_usuario($filaPlantel['ran_eje']).' '.$filaPlantel['nom_eje'].' - '.$filaPlantel['nom_pla']; ?>
                                                </option>
                                        <?php
                                            }
                                        ?>

                                    
                                            
                                        <!-- F CASO EJECUTIVO -->
                                    <?php
                                        } else {
                                            $aux = 'estructura';
                                    ?>
                                        <!-- CASO ESTRUCTURA -->
                                        <?php  
                                            $id_eje = $_GET['id_eje'];

                                            $sqlEstructura = "
                                                SELECT * FROM ejecutivo 
                                                INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
                                                WHERE id_eje = '$id_eje' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
                                            ";

                                            $datosEstructura = obtener_datos_consulta( $db, $sqlEstructura );
                                            $validacionEstructura = $datosEstructura['total'];
                                        

                                            if ( $validacionEstructura > 0 ) {
                                        ?>
                                                <option selected value="<?php echo $id_eje; ?>">
                                                    <?php echo obtener_rango_usuario($datosEstructura['datos']['ran_eje']).' '.$datosEstructura['datos']['nom_eje'].' - '.$datosEstructura['datos']['nom_pla']; ?></option>
                                                <?php obtener_options_estructura( $id_eje, $db ); ?>

                                        <?php
                                            }
                                        ?>

                                        <!-- F CASO ESTRUCTURA -->
                                    <?php
                                        }
                                    ?>
                                    <!--  -->
                            <?php
                                }
                            ?>

                            <!-- F SIN PERMISOS -->
                    <?php
                        }
                    ?>
                    <!--  -->
                    <!--  -->
            <?php
                }
            ?>
            <!-- F CASO PLANTEL -->
            

            
            


            
        </select>
        <?php 
            // echo $aux;
            // echo $sqlPlantel;
        ?>
    </div>


</div>
<!-- FIN FILTROS -->


<!--  -->
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body" >

                <!--  -->
                <div class="row">
                    <!-- Columna Izquierda para el Botón -->
                    <div class="col-md-2 d-flex align-items-center">
                        <a href="#" id="btn_agregar_cita" class="btn-info btn btn-sm" style="width: 150px;">Agregar cita</a>
                    </div>

                    <!-- Columna Central para los Badges -->
                    <div class="col-md-8 d-flex justify-content-center align-items-center">
                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 5px;" id="badges-container">
                        </div>
                    </div>

                    <!-- Columna Derecha para los Nuevos Badges -->
                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                        <div style="display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 5px;">
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <span class="badge" style="background-color: #6c757d; color: #fff; padding: 6px; clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);">
                                    TOTAL: <span id="conteo_total">0</span>
                                </span>
                                <span class="badge" style="background-color: #FFC0CB; color: #FF0000; padding: 6px; clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);">
                                    CITA EFECTIVA: <span id="total_cita_efectiva">0</span>
                                </span>
                                <span class="badge" style="background-color: #00FFFF; color: #000000; padding: 6px; clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);">
                                    REGISTROS: <span id="conteo_registros">0</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="position: relative;">
                        <!--  -->
                        <div style="width: 100%; background-color: #e0e0e0; border-radius: 4px; position: relative; height: 17px; overflow: hidden;">
                            <div id="barra_total" style="height: 100%; background-color: #6c757d; border-radius: 4px; position: absolute; top: 0; left: 0; z-index: 1;">
                                <span id="texto_total" style="position: absolute; right: 10px; color: #fff; font-size: 12px; z-index: 2; white-space: nowrap;">0</span>
                            </div>
                            <div id="barra_cita_efectiva" style="height: 100%; background-color: #FFC0CB; border-radius: 4px; position: absolute; top: 0; left: 0; z-index: 2;">
                                <span id="texto_cita_efectiva" style="position: absolute; right: 10px; color: #FF0000; font-size: 12px; z-index: 3; white-space: nowrap;">0%</span>
                            </div>
                            <div id="barra_registros" style="height: 100%; background-color: #00FFFF; border-radius: 4px; position: absolute; top: 0; left: 0; z-index: 3;">
                                <span id="texto_registros" style="position: absolute; right: 10px; color: #000000; font-size: 12px; z-index: 4; white-space: nowrap;">0%</span>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                    







                    <!-- <div class="progress mb-0">
                        <div class="progress-bar" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-success" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">yyy</div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>
 -->


                    
                </div>





                    
                <!--  -->
            </div>
        </div>



    </div>

</div>
<!--  -->

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                <div id="contenedor_datos">
                    
                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>


                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>


                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>

                </div>
            </div>
        </div>


    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


<?php  
    include('inc/footer.php');
?>

<script>
    // BUSCADOR
    $("#formulario_buscar_cita").submit(function(event) {
        event.preventDefault();

        $('#loader').removeClass('hidden');
        $('#btnBuscar').html(
            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Buscando...'
        ).attr('disabled', 'disabled');
        
        var palabra = $("#searchInput").val();
        var id_eje = $('#selector_ejecutivo option:selected').val();
        var radioPeriodo = $(".radioPeriodo:checked").val();
        
        // Obtener fechas según el periodo seleccionado
        var inicio, fin;
        if (radioPeriodo == 'Fecha') {
            inicio = $('#inicio').val();
            fin = $('#inicio').val();  // o fin según el caso
        } else if (radioPeriodo == 'Semana') {
            inicio = $('#selectorSemana option:selected').attr('inicio');
            fin = $('#selectorSemana option:selected').attr('fin');
        } else if (radioPeriodo == 'Mes') {
            inicio = $('#selectorMes option:selected').attr('inicio'); 
            fin = $('#selectorMes option:selected').attr('fin');
        } else if (radioPeriodo == 'Rango') {
            inicio = $('#inicio_rango').val();
            fin = $('#fin_rango').val();
        }

        // Enviar todo junto
        if(palabra == '') {
            $('#btnBuscar').html('Buscar').removeAttr('disabled');
            obtener_datos();
        } else {
            $.ajax({
                url: 'server/obtener_citas5.php',
                type: 'POST',
                data: {
                    palabra,
                    inicio,
                    fin,
                    id_eje,
                    escala: '<?php echo isset($_GET["escala"]) ? $_GET["escala"] : ""; ?>',
                    <?php if(isset($_GET["escala"]) && $_GET["escala"] == "plantel"): ?>
                    id_pla: <?php echo $_GET["id_pla"]; ?>,
                    <?php endif; ?>
                },
                success: function(respuesta) {
                    $('#contenedor_datos').html(respuesta);
                    $('#loader').addClass('hidden');
                    $('#btnBuscar').html('Buscar').removeAttr('disabled');
                }
            });
        }
    });
    // F BUSCADOR
</script>

<script type="text/javascript">
<?php   
    if( isset($_GET['inicio']) && isset($_GET['fin']) && isset( $_GET['escala']) && $_GET['escala'] == 'ejecutivo' ){
?>

    // Función para actualizar el selector
    function actualizarSelectorEjecutivo() {

        var id_eje = <?php echo $_GET['id_eje']; ?>;
        // Remover el atributo 'selected' de todas las opciones
        $('#selector_ejecutivo option').removeAttr('selected');

        // Buscar y seleccionar la opción con el valor predefinido
        $('#selector_ejecutivo').val(id_eje);
    }
    actualizarSelectorEjecutivo();
<?php   
    }
?>

$('.filtros').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */
    // console.log(inicio + fin);
    obtener_datos();
    // alert( radioReporte );

});

function obtener_citas(inicio, fin, id_eje) {
    var escala = '';

    <?php 
        if( isset( $_GET['escala'] ) ){
    ?>
            var escala = "<?php echo $_GET['escala']; ?>";
    <?php
        }
    ?>

    // Mostrar el loader antes de la petición AJAX
    $('#loader').removeClass('hidden');

    <?php 
        if( isset( $_GET['escala'] ) && ( $_GET['escala'] == 'plantel' )  ){
    ?>
            var id_pla = <?php echo $_GET['id_pla']; ?>;
            $.ajax({
                url: 'server/obtener_citas5.php',
                type: 'POST',
                data: {
                    inicio,
                    fin,
                    id_eje,
                    escala,
                    id_pla
                    
                },
                success: function(respuesta) {
                    $('#contenedor_datos').html(respuesta);
                    // Ocultar el loader después de cargar los datos
                    $('#loader').addClass('hidden');
                }
            });
    <?php
        } else {
    ?>
            $.ajax({
                url: 'server/obtener_citas5.php',
                type: 'POST',
                data: {
                    inicio,
                    fin,
                    id_eje,
                    escala
                },
                success: function(respuesta) {
                    $('#contenedor_datos').html(respuesta);
                    // Ocultar el loader después de cargar los datos
                    $('#loader').addClass('hidden');
                }
            });

    <?php
        }
    ?>
    
}

$(window).on('load', function() {
    $('#loader').addClass('hidden');
});
</script>


<script type="text/javascript">
$('.radioPeriodo').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_datos();
    // alert( radioReporte );

});


$('.filtros').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */

    obtener_datos();
    // alert( radioReporte );

});

obtener_datos();

function obtener_datos( fila='', columna='' ) {

    setTimeout(function() {

        // alert( reporte );

        var radioPeriodo = $(".radioPeriodo:checked").val();

        // FECHAS
        if (radioPeriodo == 'Fecha') {


            <?php 
                if( isset( $_GET['escala'] ) &&  ( $_GET['escala'] == 'estructura' || $_GET['escala'] == 'plantel' || $_GET['escala'] == 'ejecutivo' ) ){
            ?>
                    var inicio = $('#inicio').val();
                    var fin = $('#fin').val();
            <?php
                } else if ( isset( $_GET['escala'] ) && $_GET['escala'] == 'ejecutivo' ){

                    // alert('get ejecutivo');
            ?>
                    var inicio = $('#inicio').val();
                    var fin = $('#fin').val();
            <?php
                } else {
            ?>
                    var inicio = $('#inicio').val();
                    var fin = $('#inicio').val();
            <?php
                }
            ?>

            $('#contenedor_fecha').css('display', '');
            $('#contenedor_semana').css('display', 'none');
            $('#contenedor_mes_annio').css('display', 'none');
            $('#contenedor_rango').css('display', 'none');


        } else if (radioPeriodo == 'Semana') {

            var inicio = $('#selectorSemana option:selected').attr('inicio');
            var fin = $('#selectorSemana option:selected').attr('fin');

            $('#contenedor_mes_annio').css('display', 'none');
            $('#contenedor_fecha').css('display', 'none');
            $('#contenedor_semana').css('display', '');
            $('#contenedor_rango').css('display', 'none');


        } else if (radioPeriodo == 'Mes') {


            $('#contenedor_mes_annio').css('display', '');
            $('#contenedor_fecha').css('display', 'none');
            $('#contenedor_semana').css('display', 'none');
            $('#contenedor_rango').css('display', 'none');

            var inicio = $('#selectorMes option:selected').attr('inicio');
            var fin = $('#selectorMes option:selected').attr('fin');
            var mes = $('#selectorMes option:selected').val();
            var annio = $('#selectorAnnio option:selected').val();


        } else if (radioPeriodo == 'Rango') {
            $('#contenedor_mes_annio').css('display', 'none');
            $('#contenedor_fecha').css('display', 'none');
            $('#contenedor_semana').css('display', 'none');
            $('#contenedor_rango').css('display', '');

            var inicio = $('#inicio_rango').val();
            var fin = $('#fin_rango').val();
        }
        // FIN FECHAS

        var id_eje = $('#selector_ejecutivo option:selected').val();

        // console.log('id_ejeeeee: '+id_eje);
        //obtener_conteos_citas( inicio, fin );
        obtener_citas(inicio, fin, id_eje);
    }, 500);
}
</script>


<script type="text/javascript">
$('#formulario_agregar_alumno').on('submit', function(event) {
    event.preventDefault();

    //alert('submit');
    $("#btn_guardar_alumno").attr('disabled', 'disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando');
    var formulario_agregar_alumno = new FormData($('#formulario_agregar_alumno')[0]);

    //alert( $('#selector_generacion option:selected').attr('id_ram') );

    setTimeout(function() {
        $("#btn_guardar_alumno").html('Guardar').removeClass('btn-success').addClass('btn-info');
    }, 500);

    var id_ram = $('#selector_generacion option:selected').attr('id_ram');
    var id_gen = $('#selector_generacion option:selected').attr('id_gen');
    var id_pla = $('#selectorPlantel option:selected').val();

    if ($('#tie_alu_ram')) {
        var tie_alu_ram = $('#tie_alu_ram').val();
    } else {
        var tie_alu_ram = 0;
    }

    formulario_agregar_alumno.append('id_ram', id_ram);
    formulario_agregar_alumno.append('id_gen', id_gen);
    formulario_agregar_alumno.append('id_pla', id_pla);
    
    formulario_agregar_alumno.append('tie_alu_ram', tie_alu_ram);
    
    // Verificar si existe el selector de forma de titulación y, si existe, añadir su valor
    if ($('#selector_forma_titulacion').length > 0) {
        var forma_titulacion = $('#selector_forma_titulacion option:selected').val();
        formulario_agregar_alumno.append('forma_titulacion', forma_titulacion);
    } else {
        // Si no existe el selector, enviar un valor por defecto
        formulario_agregar_alumno.append('forma_titulacion', '');
    }

    $.ajax({
        url: 'server/agregar_alumno2.php',
        type: 'POST',
        data: formulario_agregar_alumno,
        processData: false,
        contentType: false,
        cache: false,
        success: function(id_alu_ram) {
            console.log(id_alu_ram);

            $("#btn_guardar_alumno").removeAttr('disabled').html(
                    '<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info')
                .addClass('btn-success');

            swal("Agregado correctamente", "Continuar", "success", {
                button: "Aceptar",
            }).then((value) => {
                // Redirección en una nueva pestaña usando window.open
                obtener_datos();
                $('#modal_registro').modal('hide');
                window.open("solicitud_inscripcion.php?id_alu_ram=" + id_alu_ram, "_blank");
            });
        }

    });
});
</script>

<script type="text/javascript">
$('#selector_generacion').on('change', function(e) {
    //alert('cambio');
    obtener_documentacion_programa();
    obtener_tiempo_alumno();
    obtener_colegiatura_grupo();

});

obtener_tiempo_alumno();
obtener_colegiatura_grupo();
obtener_documentacion_programa();


function obtener_documentacion_programa() {
    var id_ram = $('#selector_generacion option:selected').attr('id_ram');

    $.ajax({
        url: 'server/obtener_documentacion_programa.php',
        type: 'POST',
        data: {
            id_ram
        },
        success: function(respuesta) {
            $('#contenedor_documentacion_programa').html(respuesta);
        }
    });
}


function obtener_colegiatura_grupo() {

    console.log('obtener_cole_grupo..');
    var id_gen = $('#selector_generacion option:selected').attr('id_gen');
    //console.log('obtener_colegiatura_grupo');
    $.ajax({
        url: 'server/controlador_grupo.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id_gen
        },
        success: function(response) {
            console.log('r: '+response.data.mon_gen);
            if( response.data.mon_gen == null ){
                toastr.error('¡Debes colocar colegiatura!'); toastr.info('¡Debes colocar colegiatura!');
            } else {
                $('#colegiatura').val(response.data.mon_gen);
            }
            
          
        },
    });
}

function obtener_tiempo_alumno() {
    var prepa_emprende = $('#selector_generacion option:selected').attr('prepa_emprende');

    if (prepa_emprende == 1) {
        $('#contenedor_tiempo').html(
            '<span class="letraPequena">Define la periodicidad</span><select class="form-control" id="tie_alu_ram"><option value="4 meses">4 meses</option><option value="8 meses">8 meses</option><option value="12 meses">12 meses</option></select>'
        );
    } else {
        // Si no es 1, vaciar el contenido del contenedor
        $('#contenedor_tiempo').html('');
    }
}
</script>

<script>
    obtener_selector_programas();

    $('#selectorPlantel').on('change', function(){
        obtener_selector_programas();
    });

    function obtener_selector_programas(){

        //console.log('obtener_planteles func!!!');
        var id_pla = $('#selectorPlantel option:selected').val();

        //console.log( 'id_pla :D: '+id_pla );
        $.ajax({
            url: 'server/obtener_selector_programas.php',
            type: 'POST',
            data: { id_pla },
            success: function(resp) {
                //console.log( 'selector_plantel: '+resp );
                $('#contenedor_programas').html( resp );
        
            },
        });
    }
</script>

<script>
    $('#btn_agregar_cita').on('click', function(event) {
        event.preventDefault();
        $('#modal_cita').modal('show');
        // obtener_colegiatura_grupo();
    });
</script>


<!--  -->

<script type="text/javascript">
$('#formulario_nueva_cita').on('submit', function(event) {
    event.preventDefault();

    //alert('submit');
    $("#btn_guardar_cita").attr('disabled', 'disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando');
    var formulario_nueva_cita = new FormData($('#formulario_nueva_cita')[0]);


    setTimeout(function() {
        $("#btn_guardar_cita").html('Guardar').removeClass('btn-success').addClass('btn-info');
    }, 500);

    var tip_cit_for = $('#tip_cit_for option:selected').val();
    var pro_cit_for = $('#pro_cit_for option:selected').val();

    formulario_nueva_cita.append('tip_cit_for', tip_cit_for);
    formulario_nueva_cita.append('pro_cit_for', pro_cit_for);

    $.ajax({

        url: 'server/agregar_cita.php',
        type: 'POST',
        data: formulario_nueva_cita,
        processData: false,
        contentType: false,
        cache: false,
        success: function(respuesta) {
            console.log(respuesta);

            // if (respuesta == 'Exito') {
            $("#btn_guardar_cita").removeAttr('disabled').html(
                    '<i class="fas fa-check"></i> ¡Guardado exitosamente!').removeClass('btn-info')
                .addClass('btn-success');

            swal("Agregado correctamente", "Continuar", "success", {
                button: "Aceptar",
            }).
            then((value) => {

                // obtenerAnnios();
                obtener_datos();
                $('#formulario_nueva_cita').trigger("reset");

                setTimeout(function() {
                    $("#btn_guardar_cita").html('Guardar').removeClass(
                        'btn-success').addClass('btn-info');
                        $('#modal_cita').modal('hide');
                }, 500);

            });

            // }
        }
    });

});
</script>
<!--  -->



<script>
    // ESTATUS CITA OBJETO
	const statusConfig = {
		statuses: [
            {
                id: 'cita_agendada',
                label: 'CITA AGENDADA',
                displayName: 'CITA AGENDADA',
                color: '#FF9800',
                badgeTextColor: '#FFFFFF',
                order: 1,
                active: true
            },
            {
                id: 'invasion_ciclo',
                label: 'INVASIÓN DE CICLO',
                displayName: 'INVASIÓN DE CICLO',
                color: '#FFFF00',
                badgeTextColor: '#000000',
                order: 2,
                active: true
            },
            {
                id: 'cita_no_atendida',
                label: 'CITA NO ATENDIDA',
                displayName: 'CITA NO ATENDIDA',
                color: '#FF6666',
                badgeTextColor: '#FFFFFF',
                order: 3,
                active: true
            },
            {
                id: 'pago_esperado',
                label: 'PAGO ESPERADO',
                displayName: 'PAGO ESPERADO',
                color: '#FF00FF',
                badgeTextColor: '#FFFFFF',
                order: 4,
                active: true
            },
            {
                id: 'perdido_precio',
                label: 'PERDIDO POR PRECIO',
                displayName: 'PERDIDO POR PRECIO',
                color: '#AABBCC',
                badgeTextColor: '#FFFFFF',
                order: 5,
                active: true
            },
            {
                id: 'perdido_horario',
                label: 'PERDIDO POR HORARIO',
                displayName: 'PERDIDO POR HORARIO',
                color: '#336699',
                badgeTextColor: '#FFFFFF',
                order: 6,
                active: true
            },
            {
                id: 'registro',
                label: 'REGISTRO',
                displayName: 'REGISTRO',
                color: '#00FFFF',
                badgeTextColor: '#000000',
                order: 7,
                active: true
            },
            {
                id: 'no_interesa',
                label: 'NO LE INTERESA',
                displayName: 'NO LE INTERESA',
                color: '#CC0000',
                badgeTextColor: '#FFFFFF',
                order: 8,
                active: true
            },
            {
                id: 'asesoria_realizada',
                label: 'ASESORÍA REALIZADA',
                displayName: 'ASESORÍA REALIZADA',
                color: '#00FF00', // Verde
                badgeTextColor: '#000000',
                order: 9,
                active: true
            },
            {
                id: 'cita_confirmada',
                label: 'CITA CONFIRMADA',
                displayName: 'CITA CONFIRMADA',
                color: '#FFFF00', // Amarillo
                badgeTextColor: '#000000',
                order: 10,
                active: true
            }
        ],

		getActiveStatuses() {
			return this.statuses
				.filter(status => status.active)
				.sort((a, b) => a.order - b.order);
		},

		getStatusColor(statusId) {
			const status = this.statuses.find(s => s.id === statusId);
			return status ? status.color : '';
		},

		getStatusLabel(statusId) {
			const status = this.statuses.find(s => s.id === statusId);
			return status ? status.label : '';
		},

		getStatusByLabel(label) {
			return this.statuses.find(s => s.label === label);
		},

		generateStatusBadgesHTML() {
			return this.getActiveStatuses()
				.map(status => `
					<span class="badge" 
							style="background-color: ${status.color}; color: ${status.badgeTextColor}">
						${status.displayName}: 
						<span id="conteo_${status.id}">
							<strong>0</strong>
						</span>
					</span>
				`).join('');
		}
	};
	// F ESTATUS CITA OBJETO
</script>

<script>
$("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - Citas');
</script>