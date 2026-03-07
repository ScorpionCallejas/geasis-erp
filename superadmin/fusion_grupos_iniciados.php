<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-users icon-gradient bg-premium-dark"></i>
                        </div>
                        <div>
                            Fusión de grupos iniciados
                            <div class="page-title-subheading">En este apartado se pueden fusionar grupos de uno o más CDEs.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div>

            	<!-- PROFESOR Y GPO -->
                <div class="row">

                	<div class="col-md-6">

	                	<span class="grey-text">Nombre del grupo fusionado</span>
	                    <input type="text" class="form-control" id="nom_fus" name="nom_fus" placeholder="Nombre del grupo..." value="Gpo-fusionado">
	                </div>
	                
	                <div class="col-md-6">
	                    
	                    <span class="grey-text">Selección profesor</span>
	                    <select class="seleccionProfesor form-control">

	                        <?php  
	                            $sqlProfesores = "
	                                SELECT *
	                                FROM profesor
	                                INNER JOIN plantel ON plantel.id_pla = profesor.id_pla2
	                                INNER JOIN cadena ON cadena.id_cad = plantel.id_cad1
	                                WHERE id_cad = '$cadena'
	                                ORDER BY nom_pro ASC
	                            ";

	                            $resultadoProfesores = mysqli_query($db, $sqlProfesores);
	                            $i = 1;
	                            while($filaProfesores = mysqli_fetch_assoc($resultadoProfesores)){
	                                if ($id_pro == $filaProfesores['id_pro']) {
	                        ?>
	                                    <option value="<?php echo $filaProfesores['id_pro']; ?>" selected nom_pro="<?php echo $filaProfesores['nom_pro']." ".$filaProfesores['app_pro']; ?>"><?php echo $filaProfesores['nom_pro']." ".$filaProfesores['app_pro'].' - '.$filaProfesores['nom_pla']; ?>
	                                    </option>

	                        <?php
	                                }else{
	                        ?>
	                                    <option value="<?php echo $filaProfesores['id_pro']; ?>" nom_pro="<?php echo $filaProfesores['nom_pro']." ".$filaProfesores['app_pro']; ?>"><?php echo $filaProfesores['nom_pro']." ".$filaProfesores['app_pro'].' - '.$filaProfesores['nom_pla']; ?>
	                                    </option>
	                        <?php
	                                }
	                        ?>
	                            
	                        <?php
	                            }


	                        ?>
	                        
	                        
	                    </select>
	                </div>

	                

	            </div>
                <!-- FIN PROFESOR -->

                <hr>

                <!-- CICLO -->
                <div class="row">
                	
                	<div class="col-md-4">
                		<span>Nombre del ciclo</span>
	                    <input type="text" class="form-control form-control-sm" id="cic_fus" name="cic_fus" required="" placeholder="Ciclo escolar..." value="Ciclo-fusionado">
	                      
	                </div>


	                <div class="col-md-2">
	                	<span>Inscripción</span>
	                    <input type="date" id="ins_fus" name="ins_fus" class="form-control" required="" value="<?php echo date('Y-m-d'); ?>">
	                </div>

	                <div class="col-md-2">
	                    <span>Inicio</span>
	                    <input type="date" id="ini_fus" name="ini_fus" class="form-control" required="" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 5 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
	                </div>

	                <div class="col-md-2">
	                    <span>Corte</span>
	                    <input type="date" id="cor_fus" name="cor_fus" class="form-control" required="" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 10 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
	                </div>

	                <div class="col-md-2">
	                    <span>Fin</span>
	                	<input type="date" id="fin_fus" name="fin_fus" class="form-control" required="" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 20 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
	                    
	                </div>

                
                </div>
                <!-- FIN CICLO -->

                <hr>	

            	<!-- MATERIAS -->
                <div class="row">
                    <div class="col-md-6">

                        <!--  -->
	                    <table class="table table-hover" id="tabla_seleccion_estructura">
	                        <thead>
	                            <tr>
	                                <th>Horarios</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            <?php 
	                                $sqlMateria = "
	                                    SELECT *
                                        FROM sub_hor
                                        INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                                        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                                        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                                        INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                                        INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
                                        INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                                        INNER JOIN cadena ON cadena.id_cad = plantel.id_cad1
                                        WHERE id_cad = '$cadena' AND ini_cic <= CURDATE() AND CURDATE() <= fin_cic AND ( id_fus2 IS NULL )
                                        ORDER BY id_sub_hor DESC
	                                ";

	                                // echo $sqlMateria;

	                                $resultadoMateria = mysqli_query($db, $sqlMateria);
	                                $i = 1;
	                                while( $filaMateria = mysqli_fetch_assoc( $resultadoMateria ) ){

	                                    $id_mat_aux = $filaMateria['id_mat'];

	                            ?>
	                                    <tr>

	                                        <td id_mat="<?php echo $filaMateria['id_mat']; ?>">

	                                            <div class="row " >
	                                                
	                                                <div class="col-md-12">
	                                              		
	                                              		<input type="checkbox" class="custom-control-input checkbox_seleccion_estructura" id="checkbox_seleccion_estructura<?php echo $i; ?>" nom_mat="<?php echo $filaMateria['nom_mat']; ?>" nom_ram="<?php echo $filaMateria['nom_ram']; ?>" nom_pla="<?php echo $filaMateria['nom_pla']; ?>" id_sub_hor="<?php echo $filaMateria['id_sub_hor']; ?>" fechas_ciclo="<?php echo fechaFormateadaCompacta2($filaMateria['ini_cic']).' al '.fechaFormateadaCompacta2($filaMateria['fin_cic']); ?>" nom_cic="<?php echo $filaMateria['nom_cic']; ?>" nom_gru="<?php echo $filaMateria['nom_gru']; ?>" nom_pro="<?php echo $filaMateria['nom_pro'].' '.$filaMateria['app_pro']; ?>" fin_cic="<?php echo $filaMateria['fin_cic']; ?>" ini_cic="<?php echo $filaMateria['ini_cic']; ?>">
                                                        <label class="form-check-label" for="checkbox_seleccion_estructura<?php echo $i; ?>">
                                                        	<i class="fas fa-book"></i>

		                                                    <strong><?php echo $filaMateria['nom_mat']; ?></strong>
		                                                    <span class="letraPequena grey-text">
		                                                        <br>
		                                                        <?php echo $filaMateria['nom_ram'].' - '.$filaMateria['nom_pla']; ?>
		                                                    </span>

                                                            <br>
                                                            <span>
                                                                Capacitador: <strong><?php echo $filaMateria['nom_pro'].' '.$filaMateria['app_pro']; ?></strong>
                                                            </span>
                                                            <br>
                                                            <span>
                                                                <strong><?php echo $filaMateria['nom_cic']; ?></strong>
                                                                <span>
                                                                    (<?php echo fechaFormateadaCompacta2($filaMateria['ini_cic']).' al '.fechaFormateadaCompacta2($filaMateria['fin_cic']); ?>)
                                                                </span>

                                                            </span>

                                                            <br>
                                                            <span>
                                                                <?php echo $filaMateria['nom_gru']; ?>
                                                            </span>


                                                        </label>

	                                              		
															                                                    
	                                                </div>

                                                   

	                                            </div>
	                                            


	                                        </td>
	                                    </tr>



	                            <?php
	                                    $i++;
	                                }
	                            ?>
	                        </tbody>
	                        
	                    </table>


                    </div>

                    <div class="col-md-6" style="border-style: dotted;">

                    	<div id="contenedor_seleccion" ><span>Selección de materias</span><br></div>
                        
                    </div>
                </div>
                <!-- FIN MATERIAS -->

                <hr>

                <div class="row">
                	<div class="col-md-12" style="text-align: right;">
                		<a href="#" id="btn_agregar_grupo_fusionado" class="btn btn-sm btn-info">
			                Guardar 
			            </a>
                	</div>
                </div>
                
                 

	            <hr>

	            <br>
	            <br>
	            <br>
             
            </div>
        </div>
        
    </div>

<?php  

	include('inc/footer.php');

?>

<script>
    $('#tabla_seleccion_estructura').DataTable({
            
        dom: 'frt',
        pageLength: -1,
        "scrollY": "300px",
        "scrollCollapse": true,
        "ordering": false,
        buttons: [

            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
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
      
</script>
<!--  -->


<script>

	$('.checkbox_seleccion_estructura').on('click', function(event) {
        // event.preventDefault();
        /* Act on the event */

        console.log('selc materia');


        var elemento = $(this);
        console.log( elemento );
        // console.log( $(this).find('.checkbox_seleccion_estructura')[0].checked );
        if ( elemento[0].checked == true ) {

            // burbuja.play();
            obtener_seleccion_estructura( elemento );

            

        } else { 
          
            // elemento.prop({checked: false});
            // error.play();   
            obtener_seleccion_estructura( elemento );
        
        }

        obtenerFechaMaxima();

        // $('#contenedor_vista2').css('display', '');
        // obtener_visibilidad_contenedor();

        // var id_eje = $(this).attr('id_eje');

        // $.ajax({
        //  url: 'server/obtener_consulta_ejecutivo.php',
        //  type: 'POST',
        //  data: { id_eje },
        //  success: function( respuesta ){
        //      $("#contenedor_vista3").html( respuesta );
        //  }
        // });
        
        
    });
	
    function obtener_seleccion_estructura( elemento ){

        // $('#contenedor_seleccion_estructura').html('');
        
        // for( var i = 0; i < $('.seleccion_materia').length; i++ ) {
            // console.log('func');
            var id_sub_hor = elemento.attr('id_sub_hor');
            var nom_mat = elemento.attr('nom_mat');
            var nom_ram = elemento.attr('nom_ram');
            var nom_pla = elemento.attr('nom_pla');
            var nom_pro = elemento.attr('nom_pro');
            var nom_cic = elemento.attr('nom_cic');
            var nom_gru = elemento.attr('nom_gru');

            var fin_cic = elemento.attr('fin_cic');

            var fechas_ciclo = elemento.attr('fechas_ciclo');
            // alert( nom_mat );

            // console.log('id_sub_hor'+id_sub_hor);

            if ( elemento[0].checked == true ) {

                $('#contenedor_seleccion').append(
                    '<div class="main-card mb-3 card p-2 materia_seleccionada" id="materia_seleccionada'+id_sub_hor+'" fin_cic="'+fin_cic+'" id_sub_hor="'+id_sub_hor+'"><strong>'+nom_mat+'</strong><small class="text-muted">'+nom_ram+' - '+nom_pla+'</small><span> Capacitador: <strong>'+nom_pro+'</strong> </span><span> <strong>'+nom_cic+'</strong> <span> ('+fechas_ciclo+') </span> </span><span>'+nom_gru+'</span></div>'
                );

                
            } else {

                $('#materia_seleccionada'+id_sub_hor+'').remove();

            }


            if ( $(".materia_seleccionada").length > 0 ) {
                $('#btn_paso2').css('display', '');
            } else{
                $('#btn_paso2').css('display', 'none');
            }
            // alert();

        // }
    
    }
</script>


<script>

    function obtenerFechaMaxima(){
        if ( $('.materia_seleccionada').length > 1 ) {
            console.log( 'hay mas de 1' );

            var dates=[];
            
            var maxDate = '';            
            $('.materia_seleccionada').map((index)=>{
                dates.push( new Date ($('.materia_seleccionada').eq(index).attr('fin_cic') ) );

            });

            var maxima = new Date(Math.max.apply(null, dates));
            
            var maximaFormateada = maxima.toISOString().split('T')[0];

            $('#fin_fus').val(maximaFormateada);


        } else {
            console.log("solo hay uno");
            console.log($('.materia_seleccionada').attr('fin_cic'));
        }
    }
</script>

<!-- FORMULARIO -->
<script>

    $('#btn_agregar_grupo_fusionado').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        

        if ( $('.materia_seleccionada').length < 2 ) {
        
            swal("Error en fusión grupal", "¡Debes elegir al menos 2 materias!", "error", {button: "Aceptar",});
        
        } else {

            $(this).html('<i class="fas fa-cog fa-spin"></i> Guardando...').removeClass('btn-info').addClass('btn-warning disabled');

            var id_sub_hor = [];
            
            for( var i = 0; i < $('.materia_seleccionada').length; i++ ){

                id_sub_hor[i] = $('.materia_seleccionada').eq( i ).attr( 'id_sub_hor' );

            }

            // console.log(id_sub_hor.length);

            var nom_fus = $('#nom_fus').val();
            var cic_fus = $('#cic_fus').val();

            var ins_fus = $('#ins_fus').val();
            var ini_fus = $('#ini_fus').val();
            var cor_fus = $('#cor_fus').val();
            var fin_fus = $('#fin_fus').val();

            var id_pro = $('.seleccionProfesor option:selected').val();

            $.ajax({
                url: 'server/agregar_grupo_fusionado.php',
                type: 'POST',
                data: { id_sub_hor, nom_fus, cic_fus, ins_fus, ini_fus, cor_fus, fin_fus, id_pro },
                success: function( respuesta ){
                
                    console.log( respuesta );

                    swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {
                        
                        window.location.reload();
                    
                    });
                    
                }
            
            });

        }
        
        

    });
</script>
<!-- FIN FORMULARIO -->