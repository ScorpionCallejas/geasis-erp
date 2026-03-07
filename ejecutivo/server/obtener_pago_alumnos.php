<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$alumnos = $_POST['alumnos'];
	// $alumnos = array_unique( $alumnos );
  //var_dump($alumnos);
?>
<style>
#formulario_pago_alumnos input {
    font-size: 12px;
    color: #4B515D;
}

#formulario_pago_alumnos label {
    font-size: 12px;
}
</style>

<!-- ALUMNOS SELECCIONADOS -->
<div class="row">

    <div class="col-md-12">
        <span class="font-weight-normal">

            <span id="alumnosSeleccionados">
                <?php echo sizeof($alumnos); ?>
            </span> alumnos seleccionados
        </span>
    </div>


    <div class="progress md-progress" style="height: 20px" id="barra_baja">
        <div class="progress-bar text-center white-text bg-info" role="progressbar" style="width: 0%; height: 20px;"
            aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-4 scrollspy-example" style=" height: 300px;">


        <?php  
			$validador = 'v';
			for( $i = 0; $i < sizeof( $alumnos ); $i++ ){
				$sqlAlumno = "
					SELECT *
					FROM alu_ram
					INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
					INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
					INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
					WHERE id_alu_ram = '$alumnos[$i]'
				";

				//echo $sqlAlumno;

				$resultadoAlumno = mysqli_query($db, $sqlAlumno);

				$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
				$alumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu'];
				$programa = $filaAlumno['nom_ram'];

				$id_gen = $filaAlumno['id_gen'];

				if ( sizeof( $alumnos ) > 1 ) {
					// 
					if ( $i == 0 ) {
				
						$id_gen_aux = $filaAlumno['id_gen'];
					
					}
					
					if ( $i > 0 ) {

						if ( $id_gen_aux != $id_gen ) {

							$validador = 'f';

						}

						$id_gen_aux = $id_gen;

					}
					// 
				
				}

		?>
        <div class="badge bg-light text-dark rounded-pill seleccionAlumnoFinal"
            id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>" cor_alu="<?php echo $filaAlumno['cor_alu']; ?>"
            title="Alumno generación: <?php echo $filaAlumno['nom_gen']; ?>">
            <?php echo $filaAlumno['nom_alu']." ".$filaAlumno['app_alu']; ?>
        </div>

        <?php
			}

			// echo '<h1>'.$validador.'</h1>';
		?>


    </div>

    <div class="col-md-8">

        <div class="row">
            <div class="col-md-12">

                <form id="formulario_pago_alumnos">
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6">

                                <p class="grey-text letraPequena">
                                    ¡Todos los campos con * son obligatorios!
                                </p>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <span class="letraPequena grey-text">
                                    Tipo de pago
                                </span>
                                <select class="form-control" id="tip_pag">

                                <option selected value="Colegiatura">COLEGIATURA</option>
                                <option value="Inscripción">INSCRIPCIÓN/REINSCRIPCIÓN</option>
                                <option value="Otros">TRÁMITES</option>
                                <option value="Varios">OTROS CONCEPTOS</option>

                                </select>

                                <div id="contenedor_catalogo"></div>

                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="md-form mb-5">
                                    <input type="text" id="con_pag" name="con_pag" class="form-control"
                                        placeholder="Concepto" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="md-form mb-5">
                                    <input type="number" min="0" step=".5" id="mon_ori_pag" name="mon_ori_pag"
                                        class="form-control" placeholder="$ Cantidad:" >
                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col-md-6">
                                <label class="grey-text" for="ini_pag">*Inicio del cobro</label>
                                <div class="md-form mb-5">
                                    <input type="date" id="ini_pag" name="ini_pag" class="form-control validate "
                                         required="" value="<?php echo date('Y-m-d'); ?>">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <label class="grey-text" for="fin_pag">*Fin del cobro</label>
                                <div class="md-form mb-5">
                                    <input type="date" id="fin_pag" name="fin_pag" class="form-control validate "
                                         required=""
                                        value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 5 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
                                </div>

                            </div>
                        </div>


                        <!-- METADATOS -->
                        <div style="display: none;">
                            <div class="row" style="display: none;">
                                <div class="col-md-12">

                                    <div class="form-check"
                                        title="Otorga un beneficio a los alumnos que realicen su pago antes de la fecha designada...">
                                        <input type="checkbox" class="form-check-input" id="checkboxDescuento">
                                        <label class="form-check-label grey-text letraPequena" for="checkboxDescuento">
                                            Descuento por pronto pago
                                        </label>
                                    </div>

                                </div>
                            </div>




                            <div class="" id="contenedor_descuento" style="display: none;">



                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="md-form mb-5">

                                            <i class="fas fa-dollar-sign prefix grey-text" id="icono_descuento"></i>


                                            <input type="number" min="0" step=".1" id="des_pag" name="des_pag"
                                                class="form-control validate" value="0">
                                            <label class="" for="des_pag">Monto descuento</label>
                                        </div>

                                    </div>

                                    <div class="col-md-4" style="position: relative;">

                                        <span class="letraPequena grey-text" style="position: absolute;">Si paga antes
                                            de:</span>
                                        <div class="md-form mb-5">

                                            <input type="date" id="pro_pag" name="pro_pag" class="form-control validate"
                                                required
                                                value="<?php echo gmdate( 'Y-m-d', strtotime ( '- 5 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">

                                        </div>

                                    </div>


                                    <div class="col-md-4" style="position: relative;">
                                        <span class="grey-text letraPequena" style="position: absolute; ">Tipo de
                                            descuento</span>
                                        <div class="md-form mb-5">


                                            <!-- Group of material radios - option 1 -->
                                            <select class="selectorPago browser-default custom-select " id="tip1_pag"
                                                name="tip1_pag">

                                                <option value="Monetario" selected="">Monetario</option>
                                                <option value="Porcentual">Porcentual</option>
                                            </select>
                                        </div>

                                    </div>


                                </div>
                            </div>


                            <!-- FIN DESCUENTO -->


                            <!-- RECARGO -->
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-check"
                                        title="Realiza un recargo por pago tardío cuando la vigencia concluya...">
                                        <input type="checkbox" class="form-check-input" id="checkboxRecargo">
                                        <label class="form-check-label grey-text letraPequena" for="checkboxRecargo">
                                            Recargo por pago atemporal <span id="fecha_vencimiento"></span>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <br>




                            <div class="" id="contenedor_recargo">

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="md-form mb-5">

                                            <i class="fas fa-dollar-sign prefix grey-text" id="icono_recargo"></i>


                                            <input type="number" min="0" step=".1" id="car_pag" name="car_pag"
                                                class="form-control validate" value="0">
                                            <label class="" for="car_pag">Monto recargo</label>
                                        </div>

                                    </div>

                                    <div class="col-md-4" style="position: relative; display: none;"
                                        title="Define si el recargo será aplicado una vez, o bien, será recurrente.">

                                        <label class="letraPequena grey-text" style="position: absolute;">Periodicidad
                                            del recargo ( Único / Recurrente )</label>
                                        <select class="selectorPago browser-default custom-select" id="int_pag"
                                            name="int_pag">
                                            <option value="Única" selected>Única</option>
                                            <option value="Recurrente">Recurrente</option>
                                        </select>

                                    </div>


                                    <div class="col-md-6" style="position: relative;">
                                        <span class="grey-text letraPequena" style="position: absolute; ">Tipo de
                                            recargo</span>
                                        <div class="md-form mb-5">


                                            <select class="selectorPago browser-default custom-select disabled"
                                                id="tip2_pag" name="tip2_pag">

                                                <option value="Monetario">Monetario</option>
                                                <option value="Porcentual">Porcentual</option>
                                            </select>
                                        </div>

                                    </div>


                                </div>

                            </div>






                            <!-- FIN RECARGO -->
                        </div>
                        <!-- FIN METADATOS -->

                    </div>

                    <div class="modal-footer">



                        <button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar pago..."
                            type="submit" id="btn_enviar_pago_alumnos">
                            Guardar
                        </button>

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>

                    </div>

                </form>
            </div>


        </div>

    </div>


</div>
<!-- FIN ALUMNOS SELECCIONADOS -->


<!-- JS -->

<script>
//obtener_catalogo();
$("#tip_pag").change(function() {
    obtener_catalogo();

});

function obtener_catalogo() {
    var tip_pag = $("#tip_pag").val();
    if (tip_pag == 'Varios') {
        $('#contenedor_catalogo').html(
            '<hr><select class="form-control" id="selector_catalogo">' +
            '<option monto="500" concepto="BAJA DE PREPA EMPRENDE">BAJA DE PREPA EMPRENDE - $500.00</option>' +
            '<option monto="800" concepto="BAJA DE BACH DE NEGOCIOS">BAJA DE BACH DE NEGOCIOS - $800.00</option>' +
            '<option monto="800" concepto="BAJA DE LICENCIATURA">BAJA DE LICENCIATURA - $800.00</option>' +
            '<option monto="500" concepto="EXAMEN EXTRAORDINARIO POR ASIGNATURA">EXAMEN EXTRAORDINARIO POR ASIGNATURA - $500.00</option>' +
            '<option monto="500" concepto="ACTIVIDAD DE RECUPERACION 2 A 4 MESES">ACTIVIDAD DE RECUPERACION 2 A 4 MESES - $500.00</option>' +
            '<option monto="1000" concepto="ACTIVIDAD DE RECUPERACION MAYOR A 4 MESES">ACTIVIDAD DE RECUPERACION MAYOR A 4 MESES - $1,000.00</option>' +
            '<option monto="500" concepto="ACTIVACION DE MATRICULA">ACTIVACION DE MATRICULA - $500.00</option>' +
            '<option monto="1000" concepto="ACTIVACION DE MATRICULA MAYOR A 4 MESES">ACTIVACION DE MATRICULA MAYOR A 4 MESES - $1,000.00</option>' +
            '<option monto="300" concepto="CONSTANCIAS DE ESTUDIO BACH DE NEGOCIOS Y LICENCIATURAS">CONSTANCIAS DE ESTUDIO BACH DE NEGOCIOS Y LICENCIATURAS - $300.00</option>' +
            '<option monto="500" concepto="CARTA DE AUTENTICACIÓN">CARTA DE AUTENTICACIÓN - $500.00</option>' +
            '<option monto="1000" concepto="LEGALIZACIONES">LEGALIZACIONES - $1,000.00</option>' +
            '<option monto="2800" concepto="REPOSICION DE CERTIFICADO">REPOSICION DE CERTIFICADO - $2,800.00</option>' +
            '<option monto="200" concepto="CREDENCIAL">CREDENCIAL - $200.00</option>' +
            '<option monto="18000" concepto="TITULACION">TITULACION - $18,000.00</option>' +
            '</select>'
        );
        obtener_pago_catalogo();
    } else {
        $('#contenedor_catalogo').html('');
        $('#mon_ori_pag').val('');
        $('#con_pag').val('');
    }

    obtener_pago_catalogo()
    $("#selector_catalogo").off('change');
    $("#selector_catalogo").change(function() {
        obtener_pago_catalogo();
    });

    function obtener_pago_catalogo() {
        var monto = $("#selector_catalogo option:selected").attr('monto');
        var concepto = $("#selector_catalogo option:selected").attr('concepto');

        $('#mon_ori_pag').val(monto);
        $('#con_pag').val(concepto);
    }

}

setTimeout(function() {
    $('#con_pag').focus();
}, 500);


obtenerFechaVencimiento();
$('#fin_pag').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */
    obtenerFechaVencimiento();
});

function obtenerFechaVencimiento() {
    var vencimiento = $('#fin_pag').val();

    if (vencimiento != '') {
        $('#fecha_vencimiento').text('( ' + vencimiento + ' )');
    } else {
        $('#fecha_vencimiento').text('( Sin fecha asignada )');
    }
}

// CALENDARIO

<?php  
    	// VALIDAR QUE LOS ALUMNOS SELECCIONADOS SON DEL MISMO PROGRAMA
    	if ( $validador == 'v' ) {
    		
    	}
?>


// DESCUENTO
obtenerCheckboxDescuento();

$('#checkboxDescuento').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */
    obtenerCheckboxDescuento();

});

function obtenerCheckboxDescuento() {
    if ($('#checkboxDescuento')[0].checked == true) {
        // console.log("checkeado");
        $('#contenedor_descuento').css({
            display: ''
        });



        setTimeout(function() {
            $('#des_pag').focus();
        }, 300);


    } else {

        $('#contenedor_descuento').css({
            display: 'none'
        });

        $('#des_pag').val(0);

    }
}



$("#tip1_pag").change(function() {
    var valor = $("#tip1_pag").val();
    if (valor == "Porcentual") {

        $("#icono_descuento").removeClass("fa-dollar-sing animated fadeIn").addClass(
            "fa-percent animated fadeIn");

    }

    if (valor == "Monetario") {

        $("#icono_descuento").removeClass("fa-percent animated fadeIn").addClass(
            "fa-dollar-sing animated fadeIn");
    }

});

// FIN DESCUENTO

obtenerCheckboxRecargo();

$('#checkboxRecargo').on('change', function(event) {
    event.preventDefault();
    /* Act on the event */
    obtenerCheckboxRecargo();

});

function obtenerCheckboxRecargo() {
    if ($('#checkboxRecargo')[0].checked == true) {
        // console.log("checkeado");
        $('#contenedor_recargo').css({
            display: ''
        });

        setTimeout(function() {
            $('#car_pag').focus();
        }, 300);


    } else {

        $('#contenedor_recargo').css({
            display: 'none'
        });


        $('#car_pag').val(0);

    }
}



$("#tip2_pag").change(function() {
    var valor = $("#tip2_pag").val();
    if (valor == "Porcentual") {

        $("#icono_recargo").removeClass("fa-dollar-sing animated fadeIn").addClass(
            "fa-percent animated fadeIn");

    }

    if (valor == "Monetario") {

        $("#icono_recargo").removeClass("fa-percent animated fadeIn").addClass(
            "fa-dollar-sing animated fadeIn");
    }

});
// RECARGO


// FIN RECARGO


//DESELECCION DE ALUMNOS A INSCRIBIR
$(".eliminacionSeleccionAlumnoFinal").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    var alumnosSeleccionados = $(".seleccionAlumnoFinal").length - 1;
    $("#alumnosSeleccionados").text(alumnosSeleccionados);

    if (alumnosSeleccionados < 1) {
        $("#modal_pago_alumnos").modal('hide');
    }

});



$('#formulario_pago_alumnos').on('submit', function(event) {
    event.preventDefault();


    if ($("#ini_pag").val() <= $("#fin_pag").val()) {

        $("#btn_enviar_pago_alumnos").attr('disabled', 'disabled');

        // alert( $('#con_pag').val() );
        enviar_pago_alumnos();

    } else {
        swal("Datos Incorrectos", "¡Te recordamos que el inicio debe ser menor o igual al fin!", "error");
    }

});

function enviar_pago_alumnos() {

    swal({
        title: "¿Deseas crear este pago a estos " + $(".seleccionAlumnoFinal").length + " alumnos?",
        text: "¡Podrás revisarlo en el área de cobranza más tarde!",
        icon: "warning",
        buttons: {
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

            $(".eliminacionSeleccionAlumnoFinal").remove();
            let barra_estado = $("#barra_estado");
            var porcentaje;
            var contador;



            var formulario_pago_alumnos = new FormData($('#formulario_pago_alumnos')[0]);
            formulario_pago_alumnos.append('con_pag', $('#con_pag').val());
            formulario_pago_alumnos.append('mon_ori_pag', $('#mon_ori_pag').val());
            formulario_pago_alumnos.append('tip_pag', $('#tip_pag option:selected').val());
            formulario_pago_alumnos.append('tip1_pag', $('#tip1_pag option:selected').val());

            <?php  
            	// VALIDAR QUE LOS ALUMNOS SELECCIONADOS SON DEL MISMO PROGRAMA
            	if ( $validador == 'v' ) {
            		
            
            	}
            ?>


            for (var i = 0, tipoDestino = 'Alumno'; i < $(".seleccionAlumnoFinal").length; i++) {

                var id_alu_ram = $('.seleccionAlumnoFinal').eq(i).attr("id_alu_ram");


                $.ajax({
                    //PASAR VARIABLE POR URL PARA TOMAR POR GET EN EL SERVER AUNADO A LOS DATOS DEL FORMULARIO
                    ajaxContador: i,
                    url: 'server/agregar_pago.php?id_alu_ram=' + id_alu_ram,
                    type: 'POST',
                    data: formulario_pago_alumnos,
                    beforeSend: function() {

                        $("#btn_enviar_pago_alumnos").removeClass('btn-info').addClass(
                            'btn-secondary disabled').html(
                            '<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>'
                        );

                    },
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(respuesta) {

                        console.log(respuesta);


                        if ($(".seleccionAlumnoFinal").eq(this.ajaxContador).attr("id_alu_ram") ==
                            respuesta) {
                            $(".seleccionAlumnoFinal").eq(this.ajaxContador).addClass(
                                'light-green accent-4 white-text');
                        }

                        contador = this.ajaxContador + 1;
                        porcentaje = Math.floor(contador * (100 / $(".seleccionAlumnoFinal")
                            .length), 2);


                        if (porcentaje <= 100) {

                            barra_estado.attr({
                                style: 'width:' + porcentaje + '%; height: 20px;'
                            });

                            barra_estado.text(porcentaje + '%');

                            if (porcentaje == 100) {
                                barra_estado.removeClass();
                                barra_estado.addClass(
                                    'progress-bar text-center white-text bg-success');
                                barra_estado.text("Listo");
                                $(".seleccionAlumnoFinal").eq(i).addClass(
                                    'light-green accent-4 white-text');

                                $("#btn_enviar_pago_alumnos").removeClass('btn-secondary').addClass(
                                    'light-green accent-4 white-text').html(
                                    '<i class="fas fa-check white-text"></i> <span>Guardar</span>'
                                );

                                swal("Creación de pago exitoso", "Continuar", "success", {
                                    button: "Aceptar",
                                }).
                                then((value) => {


                                    $("#btn_enviar_pago_alumnos").removeClass(
                                            'disabled light-green accent-4 white-text')
                                        .addClass('btn-info');


                                    $("#modal_pago_alumnos").modal("hide");

                                    // obtenerAlumnosGeneraciones();
                                    obtener_consulta_alumno1();


                                });
                            }

                        }



                    }


                });






            }
            // BUCLE FOR







        }
    });

}
</script>
<!-- FIN JS -->

<script>
setTimeout(function() {

    $('#mainBody').removeClass('white-skin bg-light').addClass('grey-skin elegant-color');
    $('.card').removeClass('grey lighten-4').addClass('grey darken-3');
    $('.modal-content').removeClass('bg-light').addClass('white-text elegant-color');
    $('#mainContainer').removeClass('bg-light').addClass('white-text elegant-color');
    $('#mainNabvar').removeClass('grey lighten-4').addClass('grey darken-3');

}, 200);
</script>