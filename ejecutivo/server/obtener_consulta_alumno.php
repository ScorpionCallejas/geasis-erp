<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE ALUMNO
	//alumnos_carrera.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];

	

	$sql = "
		SELECT * 
		FROM alu_ram
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
		WHERE id_alu_ram = '$id_alu_ram'
	";

	$resultado = mysqli_query($db, $sql);

	$fila = mysqli_fetch_assoc($resultado);


	$id_gen = $fila['id_gen'];
	// DATOS ALUMNO
	$id_alu = $fila['id_alu'];
	$id_alu_ram = $fila['id_alu_ram'];

	$nombre = $fila['nom_alu']." ".$fila['app_alu']." ".$fila['apm_alu'];
	$fot_alu = $fila['fot_alu'];
	$bol_alu = $fila['bol_alu'];
	$tel_alu = $fila['tel_alu'];
	$ing_alu = $fila['ing_alu'];
	$cor_alu = $fila['cor_alu'];

	$cor1_alu = $fila['cor1_alu'];

	$pas_alu = $fila['pas_alu'];
	$est_alu = $fila['est_alu'];

	$nac_alu = $fila['nac_alu'];
	$cur_alu = $fila['cur_alu'];
	$pro_alu = $fila['pro_alu'];
	$gen_alu = $fila['gen_alu'];

	$dir_alu = $fila['dir_alu'];
	$cp_alu = $fila['cp_alu'];
	$col_alu = $fila['col_alu'];
	$del_alu = $fila['del_alu'];
	$ent_alu = $fila['ent_alu'];


	$tut_alu = $fila['tut_alu'];
	$tel2_alu = $fila['tel2_alu'];

	// DATOS alu_ram
	$bec_alu_ram = $fila['bec_alu_ram'];
	$bec2_alu_ram = $fila['bec2_alu_ram'];
	$car_alu_ram = $fila['car_alu_ram'];
	$est1_alu_ram = $fila['est1_alu_ram'];

	// DATOS CARRERA
	$id_ram = $fila['id_ram'];
	$programa = $fila['nom_ram'];
	$gra_ram = $fila['gra_ram'];
	$mod_ram = $fila['mod_ram'];
	$per_ram = $fila['per_ram'];
	$cic_ram = $fila['cic_ram'];

	//DATOS GENERACION
	$nom_gen = $fila['nom_gen'];
	$ini_gen = $fila['ini_gen'];
	$fin_gen = $fila['fin_gen'];

	$promedioPrograma = obtenerEvaluacionServer( $id_alu_ram );
	$avancePrograma = obtenerAvanceAlumnoCarreraServer( $id_alu_ram ); 

	$estatusAcademico = estatusAlumnoServer($id_alu_ram, $id_ram);
	$materiasAprobadas = obtenerMateriasAprobadasAlumnoServer( $id_alu_ram );
	$materiasPrograma = obtenerMateriasProgramaServer( $id_ram );

	$estatusDocumentacion = obtenerTextoEstatusDocumentacionAlumnoServer( $id_alu_ram );




             


	// DATOS PAGOS
	$estatusPago = obtenerEstatusPagoAlumnoServer( $id_alu_ram );

	$sal_alu = $fila['sal_alu'];
	if ( $sal_alu == NULL ) {
		$sal_alu = 0;
	} else {
		$sal_alu = round($fila['sal_alu'], 2);
	}
	$saldoHoy = obtenerSaldoAlumnoFechaHoyServer ( $id_alu_ram );
	$saldoGlobal = obtenerSaldoAlumnoGlobalServer ( $id_alu_ram );
	$pagadoHoy = obtenerPagadoAlumnoFechaHoyServer ( $id_alu_ram );
	$pagadoGlobal = obtenerPagadoAlumnoGlobalServer ( $id_alu_ram );
	$registrosPendientesFechaHoy = obtenerRegistrosPendientesFechaHoyServer ( $id_alu_ram );
	$registrosPendientesGlobal = obtenerRegistrosPendientesGlobalServer ( $id_alu_ram );

	$registrosPagadosFechaHoy = obtenerRegistrosPagadosFechaHoyServer ( $id_alu_ram );
	$registrosPagadosGlobal = obtenerRegistrosPagadosGlobalServer ( $id_alu_ram );
?>
		   				
<table class="table" id="myTableConsultaAlumno">
    <thead class="font-weight-normal">
        <tr>
            <th class="letraGrande">
                #
            </th>
            <th class="letraGrande">
                Clave
            </th>
            <th class="letraGrande">
                Valor
            </th>
        </tr>
        
    </thead>
    <tbody>
        <!-- GENERAL -->

        <tr>
            <td class="letraMediana font-weight-normal">
                1
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Nombre:</strong>
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $nombre; ?>
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                2
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Cuenta de acceso:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $cor_alu; ?>
            </td>
            
        </tr>
    
        <tr>
            <td class="letraMediana font-weight-normal">
                3
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Contraseña:</strong>
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $pas_alu; ?>
            </td>

            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                4
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Teléfono:</strong>
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $tel_alu; ?>
  
            </td>

            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                5
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Ingreso:</strong>
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo fechaFormateadaCompacta2($ing_alu); ?>
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                6
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Nacimiento:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo fechaFormateadaCompacta2($nac_alu); ?>
 
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                7
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>CURP:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $cur_alu; ?>
  
            </td>
            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                8
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Procedencia:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $pro_alu; ?>
  
            </td>

            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                9
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Dirección:</strong> 
  
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $dir_alu; ?>
  
            </td>

            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                10
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>CP:</strong>  
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $cp_alu; ?>
  
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                11
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Delegación/Municipio:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $del_alu; ?>
  
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                12
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Entidad:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $ent_alu; ?>
 
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                13
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Tutor:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $tut_alu; ?>

  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                14
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Contacto del tutor:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $tel2_alu; ?>
                
            </td>
            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                15
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Correo electrónico:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $cor1_alu; ?>
                
            </td>
            
        </tr>

        <!-- FIN GENERAL -->



        <tr>
            <td class="letraMediana font-weight-normal">
                16
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Programa:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $programa; ?>

            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                17
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Estatus académico:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $estatusAcademico; ?>


            </td>
            
        </tr>
    
        <tr>
            <td class="letraMediana font-weight-normal">
                18
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Matrícula:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $bol_alu; ?>
            </td>

            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                19
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Periodo educativo:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $gra_ram; ?>
  
            </td>

            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                20
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Grupo:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $nom_gen; ?>
  
            </td>

            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                21
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Inicio y fin del grupo:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo fechaFormateadaCompacta2($ini_gen)." al ".fechaFormateadaCompacta2($fin_gen); ?>
  
            </td>

            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                22
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Modalidad:</strong> 


            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $mod_ram; ?>
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                23
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Tipo de periodos:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $per_ram; ?>
 
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                24
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Cantidad de periodos:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $cic_ram; ?>
  
            </td>
            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                25
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Promedio:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $promedioPrograma; ?>
  
            </td>

            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                26
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Avance del programa:</strong> 

  
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $avancePrograma; ?>
  
            </td>

            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                27
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Materias del programa:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $materiasPrograma; ?>
  
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                28
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Materias aprobadas del programa:</strong> 

            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $materiasAprobadas; ?>
  
  
            </td>
            
        </tr>

        <tr>
            <td class="letraMediana font-weight-normal">
                29
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Estatus de documentación:</strong> 
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo $estatusDocumentacion; ?>
 
  
            </td>
            
        </tr>


        <tr>
            <td class="letraMediana font-weight-normal">
                30
            </td>
            <td class="letraMediana font-weight-normal">
                <strong>Avance del grupo:</strong>
            </td>
            <td class="letraMediana font-weight-normal">
                <?php echo obtener_datos_generacion_server( $id_gen )['porcentaje_generacion']; ?>%
 
            </td>
            
        </tr>
        

        <?php  
            if ( $estatusAcademico == 'Activo' ) {
        ?>
            <tr>
                <td class="letraMediana font-weight-normal">
                    31
                </td>

                <td class="letraMediana font-weight-normal">
                    <strong>Carga:</strong>
                </td>

                <td class="letraMediana font-weight-normal">
                    <?php echo estatusAlumnoCargaServer($id_alu_ram, $id_ram); ?>
                
                </td>
            </tr>
                
                
        <?php
            } else if ( $estatusAcademico == 'Inactivo' ) {
        ?>
        	<tr>
                <td class="letraMediana font-weight-normal">
                    31
                </td>

                <td class="letraMediana font-weight-normal">
                    <strong>Motivo :</strong>
                </td>

                <td class="letraMediana font-weight-normal">
                    <?php echo $fila['est2_alu_ram']; ?>
                
                </td>
            </tr>

        <?php
            }
        ?>
        
    </tbody>
    
</table>
<!-- FIN TABLA -->
					        

<script>

    $('#myTableConsultaAlumno').DataTable({
        
    
        dom: 'Bft',     
        "pageLength": -1,
        buttons: [

        

	            {
	                extend: 'excel',
	                messageTop: "<?php echo 'Información General del Alumno: '.$nombre; ?>"
	            },

        ],

        "language": {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Buscar:",
                        "sUrl":            "",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
    });
    $('#myTableConsultaAlumno_wrapper').find('label').each(function () {
        $(this).parent().append($(this).children());
    });
    $('#myTableConsultaAlumno_wrapper .dataTables_filter').find('input').each(function () {
        $('#myTableConsultaAlumno_wrapper input').attr("placeholder", "Buscar...");
        $('#myTableConsultaAlumno_wrapper input').removeClass('form-control-sm');
    });
    $('#myTableConsultaAlumno_wrapper .dataTables_length').addClass('d-flex flex-row');
    $('#myTableConsultaAlumno_wrapper .dataTables_filter').addClass('md-form');
    $('#myTableConsultaAlumno_wrapper select').removeClass(
    'custom-select custom-select-sm form-control form-control-sm');
    $('#myTableConsultaAlumno_wrapper select').addClass('mdb-select');
    
    $('#myTableConsultaAlumno_wrapper .mdb-select').materialSelect();
    $('#myTableConsultaAlumno_wrapper .dataTables_filter').find('label').remove();
    var botones = $('#myTableConsultaAlumno_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
</script>