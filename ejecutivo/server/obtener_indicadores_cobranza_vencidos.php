<?php  

    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    

    if ( $_POST['id_alu_ram'] != '' ) {

    	$datosPagosAlumno = obtener_datos_pagos_vencidos_alumno_server( $_POST['id_alu_ram'] );

    	$potencial = $datosPagosAlumno['potencial'];
    	$adeudo = $datosPagosAlumno['adeudo'];
    	$cobrado = $datosPagosAlumno['cobrado'];
    	$porcentaje = $datosPagosAlumno['porcentaje'];

    	$efectivo = $datosPagosAlumno['efectivo'];
    	$tarjeta = $datosPagosAlumno['tarjeta'];
    	$deposito = $datosPagosAlumno['deposito'];
		$otros = $datosPagosAlumno['otros'];

		// echo 'if';

    } else if ( $_POST['id_gen'] ) {
        
        $datosPagoGeneracion = obtener_datos_pagos_generacion_server( $_POST['id_gen'] );

        $potencial = $datosPagoGeneracion['potencial'];
        $adeudo = $datosPagoGeneracion['adeudo'];
        $cobrado = $datosPagoGeneracion['cobrado'];
        $porcentaje = $datosPagoGeneracion['porcentaje'];

        $efectivo = $datosPagoGeneracion['efectivo'];
        $tarjeta = $datosPagoGeneracion['tarjeta'];
        $deposito = $datosPagoGeneracion['deposito'];
        $otros = $datosPagoGeneracion['otros'];
		
    } else {
    	// 

    	$inicio = $_POST['inicio'];
	    $fin = $_POST['fin'];

	    $palabra = $_POST['palabra'];

    	$datos_cobranza = obtener_indicadores_cobranza_server( $palabra );

    	$potencial = $datos_cobranza['potencial'];
    	$adeudo = $datos_cobranza['adeudo'];
    	$cobrado = $datos_cobranza['cobrado'];
    	$porcentaje = $datos_cobranza['porcentaje'];

    	$efectivo = $datos_cobranza['efectivo'];
    	$tarjeta = $datos_cobranza['tarjeta'];
    	$deposito = $datos_cobranza['deposito'];
		$otros = $datos_cobranza['otros'];
    }
    

	// FIN TIPOS DE PAGO EN ABONO
	$data = array(

		'potencial' => formatearDinero( $potencial ),
		'adeudo' => formatearDinero( $adeudo ),
		'cobrado' => formatearDinero( $cobrado ),
		'porcentaje' => $porcentaje,
		'efectivo' => formatearDinero( $efectivo ),
		'tarjeta' => formatearDinero( $tarjeta ),
		'deposito' => formatearDinero( $deposito ),
		'otros' => formatearDinero( $otros ),
		'cuenta' => formatearDinero( $tarjeta + $otros + $deposito )
	);


 	echo json_encode( $data );

?>