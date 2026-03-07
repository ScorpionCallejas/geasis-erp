<?php
	require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];
	$datosAlumno = obtener_datos_vista_alumno_server( $id_alu_ram );

?>



<div class="row">
	<div class="col-md-12 text-center">
		<?php

			// VALIDACION estatus_general Baja definitiva
		    if ( $datosAlumno['estatus_general'] != 'Baja definitiva' ) {
		          
		        echo'
		          
		          <a class="btn btn-danger btn-rounded waves-effect bajaAlumno letraGrande" id_alu_ram="'.$datosAlumno['id_alu_ram'].'" title="Baja definitiva para el alumno '.$datosAlumno['nom_alu'].'" alumno="'.$datosAlumno['nom_alu'].'">
		              <i class="fas fa-arrow-alt-circle-down fa-1x animated fadeInDown"></i> Baja definitiva
		          </a>
		        
		        ';

		    } else {

		        echo'
		          
		          <a class="btn btn-info btn-rounded btn-sm waves-effect reingresoAlumno" id_alu_ram="'.$datosAlumno['id_alu_ram'].'" title="Reingreso para el alumno '.$datosAlumno['nom_alu'].'" alumno="'.$datosAlumno['nom_alu'].'">
		              Reingreso
		          </a>
		        
		        ';

		    }
		?>

	</div>
</div>

<!-- BAJAS Y REINGRESOS -->
<table id="myTableBajasReingresos" class="table table-sm">
		
		<thead class="letraPequena font-weight-normal">
        
        <tr>
            
            <th class="letraMediana">
                #
            </th>
            <th class="letraMediana">
                Fecha de movimiento
            </th>
            <th class="letraMediana">
                Tipo
            </th>

            <th class="letraMediana">
                Responsable
            </th>

            <th class="letraMediana">
                Motivo
            </th>

            <th class="letraMediana">
            	Fecha original
            </th>

        </tr>
        
    </thead>

    <tbody>

    	<?php  
    		$sqlBR = "
    			SELECT *
    			FROM ingreso_alu_ram
    			WHERE id_alu_ram14 = '$id_alu_ram'
    			ORDER BY fec_ing_alu_ram DESC
    		";

    		$resultadoBR = mysqli_query( $db, $sqlBR );
    		$contador = 1;

    		while( $datosAlumnoBR = mysqli_fetch_assoc( $resultadoBR ) ){
    	?>

    			<tr>
            
                    <td class="letraPequena">
                    	<?php echo $contador; ?>
                    </td>
                    <td class="letraPequena">
                        <?php echo fechaFormateadaCompacta2( $datosAlumnoBR['fec_ing_alu_ram'] ); ?>
                    </td>
                    <td class="letraPequena">
                        <?php echo $datosAlumnoBR['tip_ing_alu_ram']; ?>
                    </td>

                    <td class="letraPequena">
                        <?php echo $datosAlumnoBR['res_ing_alu_ram']; ?>
                    </td>

                    <td class="letraPequena">
                        <?php echo $datosAlumnoBR['mot_ing_alu_ram']; ?>
                    </td>

                    <td class="letraPequena">
                        <?php echo fechaFormateadaCompacta2( $datosAlumnoBR['ori_ing_alu_ram'] ); ?>
                    </td>

                </tr>
    	<?php
    			$contador++;
    		}
    	?>


    </tbody>
</table>
<!-- FIN BAJAS Y REINGRESOS -->


<script>
	// BAJA DEFINITIVA
    $('.bajaAlumno').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var id_alu_ram = $(this).attr("id_alu_ram");
        var nombreAlumno = $(this).attr("alumno");

        // console.log(alumno);

        // VALIDACION PERMISOS

        swal({
          title: "¡Acceso Restringido!",
          icon: "warning",
          text: 'Necesitas permisos de administrador para continuar',
          content: {
            element: "input",
            attributes: {
              placeholder: "Ingresa una contraseña...",
              type: "password",
            },
          },

          button: {
            text: "Validar",
            closeModal: false,
          },
        })
        .then(name => {
          if (name){
            //console.log(name);
            var password = name;
            $.ajax({
                    
                url: 'server/validacion_permisos.php',
                type: 'POST',
                data: {password},
                success: function(respuesta){
                    //console.log(respuesta);

                    if (respuesta == 'True') {

                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                            //
                            //console.log("Existe el password");

                            $('#modal_baja_alumno').modal('show');
                            $('#nombre_baja_alumno').html( '<i class="fas fa-user-times"></i> Baja definitiva: '+nombreAlumno );
                            $('#alerta_baja_alumno').html('<strong>¡Atención!</strong> Los datos del alumno se preservarán en la plataforma. Sin embargo el alumno no podrá acceder a su cuenta sin que antes sean reestablecidos los permisos a través de un reingreso. <hr>Se te solicita un motivo por el cual el alumno es dado de baja.');

                            $("#label_baja_alumno").text( "baja definitiva" );

                            $("#btn_baja_alumno").removeAttr('disabled');
                            setTimeout( function(){
                                $('#mot_ing_alu_ram').focus();
                            }, 300 );

                            $('#id_alu_ram_baja_alumno').val( id_alu_ram );
                            $('#tip_ing_alu_ram').val( 'Baja definitiva' );
                            
                        });
                        
                    }else{
                        // LA CONTRASENA NO EXISTE
                        
                        swal({
                          title: "¡Datos incorrectos!",
                          text: 'No existe la contraseña...',
                          icon: "error",
                          button: "Aceptar",
                        });
                        swal.stopLoading();
                        swal.close();

                        
                    }
                }
            });

          }else{
            // DATOS VACIOS
            swal({
              title: "¡Datos vacíos!",
              text: 'Necesitas ingresar una contraseña...',
              icon: "error",
              button: "Aceptar",
            });
            swal.stopLoading();
            swal.close();
          }
          
        });

        // FIN VALIDACION PERMISOS

    });


    

    // FIN BAJA DEFINITIVA


    // REINGRESO
    $('.reingresoAlumno').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var id_alu_ram = $(this).attr("id_alu_ram");
        var nombreAlumno = $(this).attr("alumno");

        // console.log(alumno);

        // VALIDACION PERMISOS

        swal({
          title: "¡Acceso Restringido!",
          icon: "warning",
          text: 'Necesitas permisos de administrador para continuar',
          content: {
            element: "input",
            attributes: {
              placeholder: "Ingresa una contraseña...",
              type: "password",
            },
          },

          button: {
            text: "Validar",
            closeModal: false,
          },
        })
        .then(name => {
          if (name){
            //console.log(name);
            var password = name;
            $.ajax({
                    
                url: 'server/validacion_permisos.php',
                type: 'POST',
                data: {password},
                success: function(respuesta){
                    //console.log(respuesta);

                    if (respuesta == 'True') {

                        swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                            //
                            //console.log("Existe el password");

                            $('#modal_baja_alumno').modal('show');
                            $('#nombre_baja_alumno').html( '<i class="fas fa-user-plus"></i> Reingreso: '+nombreAlumno );
                            $('#alerta_baja_alumno').html('<strong>¡Atención!</strong> A partir de ahora el alumno tendrá acceso nuevamente a la plataforma. <hr>Su fecha de ingreso será reescrita, sin embargo podrás consultar su historico de movimientos de bajas definitivas y reingresos en la consulta de alumno. Para concluir, se solicita un motivo de reingreso');

                            $("#label_baja_alumno").text( "reingreso" );

                            $("#btn_baja_alumno").removeAttr('disabled');
                            setTimeout( function(){
                                $('#mot_ing_alu_ram').focus();
                            }, 300 );

                            $('#id_alu_ram_baja_alumno').val( id_alu_ram );
                            $('#tip_ing_alu_ram').val( 'Reingreso' );
                            
                        });
                        
                    }else{
                        // LA CONTRASENA NO EXISTE
                        
                        swal({
                          title: "¡Datos incorrectos!",
                          text: 'No existe la contraseña...',
                          icon: "error",
                          button: "Aceptar",
                        });
                        swal.stopLoading();
                        swal.close();

                        
                    }
                }
            });

          }else{
            // DATOS VACIOS
            swal({
              title: "¡Datos vacíos!",
              text: 'Necesitas ingresar una contraseña...',
              icon: "error",
              button: "Aceptar",
            });
            swal.stopLoading();
            swal.close();
          }
          
        });

        // FIN VALIDACION PERMISOS

    });


    

    // FIN REINGRESO
</script>