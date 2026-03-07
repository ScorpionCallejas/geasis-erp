<?php  
	//ARCHIVO VIA AJAX PARA REPLICAR ACTIVIDAD
	//clase_contenido.php > obtener_actividades.php

	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];

	//CONSULTA ALUMNOS ASOCIADOS AL PROFESOR Y A LA MATERIA QUE ESTE IMPARTE
	
?>



<!-- STEPPER -->
<ul class="stepper parallel" id="custom-validation">

    <li class="step active">
        <div class="step-title waves-effect waves-dark">Paso 1 ( Elige una materia )</div>
        <div class="step-new-content">


            <div class="row">

                <div class="col-md-12">


                    <!-- BUSCADOR -->
                    <span  class="letraMediana">
                        Buscador una materia
                    </span>

                    <!-- Material input -->
                    <div class="md-form">
                        
                        <i class="fas fa-search prefix"></i>
                        <input type="text" id="palabra_materia" class="form-control grey-text" style="font-size:10px;">
                        <label for="palabra_materia">Buscar materia...</label>

                    </div>



                    <div id="contenedor_materias">
                        <div style="height: 200px;"></div>
                    </div>
                    
                </div>

    
            </div>

          <!--   <div class="step-actions">
                <button class="waves-effect waves-dark btn btn-sm btn-info next-step btn-rounded" data-feedback="validationFunction" id="btn_paso_1">Continuar
                </button>
            </div> -->

        </div>
    </li>

    <!-- PASO 2 -->
    <li class="step">
        <div class="step-title waves-effect waves-dark">Paso 2 ( Elige la clase )</div>
        <div class="step-new-content">

            <!-- BLOQUES -->
            <div  id="contenedor_bloques">
            </div>
            <!-- FIN BLOQUES -->


            

            <!-- <div class="step-actions">
                <button class="waves-effect waves-dark btn btn-sm btn-info btn-rounded next-step" data-feedback="validationFunction" id="btn_paso_2">
                    Continuar
                </button>
                <button class="waves-effect waves-dark btn btn-sm grey white-text btn-rounded previous-step" id="btn_volver_paso2">
                    Volver
                </button>
            </div> -->

        </div>
    </li>
    <!-- FIN PASO 2 -->
    
    <li class="step">
        <div class="step-title waves-effect waves-dark">Paso 3 (Confirmación y guardado)</div>
        <div class="step-new-content">


            <!--  CONFIRMACION -->
            <h4 class="grey-text">
                Confirmación
            </h4>

            <p class="letraPequena grey-text">
                Para concluir, presiona el botón de "Confirmar" 
            </p>

            <hr>
            
            <div>
                
                Programa destino: <span id="programa_destino"></span>
                <hr>
                Materia destino: <span id="materia_destino"></span>
                <hr>
                Clase destino: <span id="bloque_destino"></span>
                <input type="hidden" id="input_bloque_destino">

                <hr>


            </div>


            

            <div id="contenedor_actividades_copiadas">
                
            </div>
            

        

            <!-- FIN  CONFIRMACION -->
            
        </div>
    </li>
</ul>
<!-- FIN STEPPER -->




<script>
    $('.stepper').mdbStepper();


    setTimeout(function(){

        $('#palabra_materia').focus();
    
    }, 500);

    $('#btn_copiar_actividad').attr('disabled', 'disabled');
</script>


<script>
    $('#palabra_materia').on('keyup', function(event) {
        event.preventDefault();
        /* Act on the event */
        var palabra_materia = $(this).val();
        
        if ( palabra_materia != '' ) {
            
            console.log('texto');

            $.ajax({
            
                url: 'server/obtener_materia_buscada.php',
                type: 'POST',
                data: { palabra_materia },
                success: function( respuesta ){

                    $('#contenedor_materias').html( respuesta );
                
                }
            
            });
            
        
        } else {
            
            $('#contenedor_materias').html( '<div style="height: 200px;"></div>' );
                        
        }
        

    });
</script>


<script>
    
    $('#btn_copiar_actividad').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        $('#btn_copiar_actividad').attr('disabled', 'disabled');

        
        var identificador = '<?php echo $identificador; ?>';
        var tipo = '<?php echo $tipo; ?>';
        var id_sub_hor = '<?php echo $_POST['id_sub_hor']; ?>';
        
        var inicio_copia = '<?php echo $_POST['inicio_copia']; ?>';
        var fin_copia = '<?php echo $_POST['fin_copia']; ?>';

        var id_blo = $('#input_bloque_destino').val();

        swal({
          title: "¿Deseas agregar esta actividad a "+$('#bloque_destino').text()+"?",
          text: "¡Una vez agregado se copiará la actividad a la clase seleccionada!",
          icon: "info",
          buttons:  {
                      cancel: {
                        text: "Cancelar",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                      },
                      confirm: {
                        text: "Confirmar",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                      }
                    },
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            //ELIMINACION ACEPTADA


            $.ajax({
                url: 'server/agregar_actividad_copia.php',
                type: 'POST',
                data: { identificador, tipo, id_blo, id_sub_hor, inicio_copia, fin_copia },
                success: function( respuesta ){
                    
                    console.log( respuesta );
                    swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {

                        $('#btn_copiar_actividad').removeAttr('disabled', 'disabled');
                        
                        $('#contenedor_actividades_copiadas').html( respuesta );
                        generarAlerta( 'Cambios guardados' );
                        
                    });

                }
            });
            
          }
        });

        
        
    });
</script>