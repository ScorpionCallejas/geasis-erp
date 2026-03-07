<?php
	require('inc/cabeceras.php');
	require('inc/funciones.php');
	include "../fpdf/fpdf.php";

	$id_pag = $_GET['id_pag'];
	$tipo_pago;
	$sql_tipo_pago = "SELECT tip_abo_pag, res_abo_pag,mon_abo_pag,id_alu_ram10 FROM abono_pago WHERE id_pag1 = '$id_pag'";
	$resultado_tipo_pago = mysqli_query($db, $sql_tipo_pago);
	$fila_tipo_pago = mysqli_fetch_assoc($resultado_tipo_pago);
	$responsable = $nombreUsuario.' '.$appUsuario.' '.$apmUsuario;
	$espacio_y= 0;
	if($fila_tipo_pago['tip_abo_pag'] == null)
	{
		$tipo_pago = 'N/A';
	}
	else{
		$tipo_pago = $fila_tipo_pago['tip_abo_pag'];
	}

	 $sqlPago = "
		SELECT *
		FROM vista_pagos
		WHERE id_pag = '$id_pag'
    ";    

    $resultadoPago = mysqli_query( $db, $sqlPago );

    $fila = mysqli_fetch_assoc( $resultadoPago );
    $alumno = $fila_tipo_pago['id_alu_ram10'];

    $sql_tramites_pendientes = "SELECT * FROM vista_pagos WHERE vista_pagos.id_alu_ram = '$alumno' AND ( vista_pagos.tip_pag = 'Otros' AND vista_pagos.est_pag = 'Pendiente' ) ";
    $respuesta_tramites = mysqli_query($db, $sql_tramites_pendientes);



    // DATOS PAGO
    $con_pag = $fila['con_pag'];
    $mon_pag = $fila['mon_pag'];
    $mon_ori_pag = $fila['mon_ori_pag'];
    $est_pag = $fila['est_pag'];

    // DATOS ALUMNO
    $nombreAlumno = $fila['nom_alu'];
    $fot_alu = $fila['fot_alu'];

    $nom_gen = $fila['nom_gen'];
    $nom_ram = $fila['nom_ram'];
    $fin_pag = $fila['fin_pag'];

    $fac_pag = $fila['fac_pag'];
	//echo $sql;


	$tamaño_ticket =  400;
	

	$pdf = new FPDF($orientation='P',$unit='mm', array(45, $tamaño_ticket) );
	$pdf->AddPage();
	$pdf->SetFont('Courier','B',8);    //Letra Courier, negrita (Bold), tam. 20
	$textypos = 5;
	$pdf->setY(2);
	$pdf->setX(20);
	$pdf ->Image( "../uploads/$fotoPlantel", 35, 2, 8 ); // imagen, x, y, tamanio
	$pdf->setY(2);
	$pdf->setX(20);
	$pdf->ln();
	$pdf->setX(1);
	$pdf->SetFont('Courier','',8);
	$pdf->Cell(0,8, "Folio: ".$fila['fol_pag'] );
	//$pdf->ln();
	$pdf->setX(1);

	$pdf->SetFont('Courier','',5);
	$pdf->Cell(0,12, fechaFormateadaCompacta2( date( 'Y-m-d' ) ).' '.date('H:i') );
	
	$pdf->setX(1);
	$pdf->Cell(0,16,"CDE: ".iconv('UTF-8', 'windows-1252', $nombrePlantel ) );
	$pdf->setY(5);

	$pdf->setX(0);
	$pdf->SetY(11);    //define cell height
	$pdf->setX(1);
	$pdf->MultiCell(35,2,"Alumno: ".iconv('UTF-8', 'windows-1252', $fila['nom_alu'] ) );
	$pdf->setX(2);
	$pdf->SetFont('Courier','',5);    //Letra Courier, negrita (Bold), tam. 20
	$textypos+=6;
	$textypos1= $textypos+12;

	$pdf->setX(0);
	$pdf->SetY(19);    //define cell height
	$pdf->setX(1);
	$pdf->Cell(40,2,"Matricula: ".iconv('UTF-8', 'windows-1252', $fila['bol_alu'] ) );
	$pdf->setX(2);
	$pdf->SetFont('Courier','',5);    //Letra Courier, negrita (Bold), tam. 20
	$textypos+=6;
	$espacio_y+=3;
	$textypos1= $textypos+12;

	$pdf->setX(1);
	$pdf->setX(1);
	$pdf->setY($espacio_y+17);
	$pdf->Cell( 1, $textypos,'Grupo: '.iconv('UTF-8', 'windows-1252', $fila['nom_gen']) );

	$pdf->setX(1);
	$pdf->setX(0);
	$pdf->setY(15);
	$pdf->setX(1);
	$pdf->MultiCell(40,2,'Programa: '.iconv('UTF-8', 'windows-1252', $fila['nom_ram'] ) );
	$espacio_y = $espacio_y+15;
	$pdf->setX(1);
	$pdf->setX(0);
	$pdf->setY($espacio_y+4);
	$pdf->setX(1);
	$pdf->MultiCell(45,2,'Responsable: '.iconv('UTF-8', 'windows-1252', $fila_tipo_pago['res_abo_pag'] ) );
	$espacio_y = $espacio_y+4;
	
	

	
	$pdf->setY($espacio_y-3);
	$pdf->setX(0);
	$pdf->Cell(1,$espacio_y,'---------------------------------------------------------------------------------------------');
	$espacio_y = $espacio_y+3;
	$textypos= $espacio_y+6;
		

	$total =0;
	$off = $textypos+15;
	//$pdf->cell(20,50, $textypos);

	
	$textypos=$off+6;
	$pdf->setY(5);
	$pdf->setX(1);
	$pdf->Cell(5,$textypos,"Concepto: " );
	$pdf->setX(32);
	// $pdf->Cell(5,$textypos,"$ ".number_format($total,2,".",","),0,0,"R");
	$pdf->getY(20);
	$pdf->Cell(-20,$textypos," " );
	$pdf->Cell(10, $textypos, iconv('UTF-8', 'windows-1252', $fila['con_pag'] ) );

	///.//////////////////////////////////////////////////////////////////////////////
	$pdf->setY(8);
	$pdf->setX(1);
	$pdf->Cell(5,$textypos,"Monto: " );
	$pdf->setX(32);
	// $pdf->Cell(5,$textypos,"$ ".number_format($total,2,".",","),0,0,"R");
	$pdf->getY(20);
	$pdf->Cell(-20,$textypos," " );
	$pdf->Cell(10, $textypos,formatearDinero( $fila['mon_ori_pag'] ) );

	///.//////////////////////////////////////////////////////////////////////////////

	if ( obtenerTotalAbonadoPago( $id_pag ) != NULL ) {

		$abonado = round( obtenerTotalAbonadoPago( $id_pag ), 2);
	
	} else {
	
		$abonado = 0;
	
	}
	$pdf->setY(11);
	$pdf->setX(1);
	$pdf->Cell(5,$textypos,"Pagado: " );
	$pdf->setX(32);
	// $pdf->Cell(5,$textypos,"$ ".number_format($total,2,".",","),0,0,"R");
	$pdf->getY(20);
	$pdf->Cell(-20,$textypos," " );
	$pdf->Cell(10, $textypos,formatearDinero( $abonado ) );


	///.//////////////////////////////////////////////////////////////////////////////

	$adeudo = $fila['mon_ori_pag'] - $abonado;

	$pdf->setY(14);
	$pdf->setX(1);
	$pdf->Cell(5,$textypos,"Adeudo: " );
	$pdf->setX(32);
	// $pdf->Cell(5,$textypos,"$ ".number_format($total,2,".",","),0,0,"R");
	$pdf->getY(20);
	$pdf->Cell(-20,$textypos," " );
	$pdf->Cell(10, $textypos,formatearDinero( $adeudo ) );



	///.//////////////////////////////////////////////////////////////////////////////


	$pdf->setY(18);
	$pdf->setX(1);
	$pdf->Cell(5,$textypos,"Tipo de Pago: " );
	$pdf->setX(18);
	// $pdf->Cell(5,$textypos,"$ ".number_format($total,2,".",","),0,0,"R");
	$pdf->getY(20);
	$pdf->Cell(18, $textypos, $tipo_pago  );

	///.//////////////////////////////////////////////////////////////////////////////

	$pdf->setY($espacio_y+10);
	$pdf->setX(0);
	$pdf->Cell(1,$espacio_y,'---------------------------------------------------------------------------------------------');
	$espacio_y = $espacio_y+3;
	$textypos= $espacio_y+6;

	$pdf->setY($espacio_y+20);
	$espacio_y+=20;
	$pdf->setX(0);
	$pdf->MultiCell(45,2,'Tramites Pendienes por Cubrir:');
	$espacio_y+=2;
	$pdf->setY($espacio_y+3);
	$i=1;
	while ($detalles_tramite = mysqli_fetch_assoc($respuesta_tramites)) { 
		$pdf->setY($espacio_y+3);
		$tramite = $i.' '.$detalles_tramite['con_pag'].': '.'('.$detalles_tramite['mon_pag'].')'.".No olvides cubrirlo antes de: ".fechaFormateadaCompacta($detalles_tramite['fin_pag']);
		$espacio_y+=3;
		$pdf->setX(0);
		$pdf->MultiCell(45,2,$tramite);
		$espacio_y+=2;
	}

	$pdf->setY($espacio_y-($espacio_y/2)+5);
	$pdf->setX(0);
	$pdf->Cell(1,$espacio_y,'---------------------------------------------------------------------------------------------');
	$espacio_y = $espacio_y+1;
	$textypos= $espacio_y+6;



	$pdf->setY($espacio_y+10);
	$pdf->setX(2);
	$pdf->MultiCell(45,2, iconv('UTF-8', 'windows-1252', $esloganPlantel) );
	$espacio_y+=2;
	$pdf->setY($espacio_y+4);
	$pdf->setX(2);
	$pdf->MultiCell(35,2, iconv('UTF-8', 'windows-1252', $direccionPlantel) );
	$espacio_y+=6;
	$pdf->setY($espacio_y-18);
	$pdf->setX(2);
	$pdf->Cell(5,$espacio_y+4, 'Telefono: '.iconv('UTF-8', 'windows-1252', $telefonoPlantel) );
	$pdf->setY($espacio_y);
	$pdf->setX(5);
	$pdf->Cell(1,$espacio_y, iconv('UTF-8', 'windows-1252', 'Visítanos en: ahjende.com') );
	$pdf->setY(0);
	$pdf->setY(0);
	$pdf->setY(0);
	$pdf->setY(0);
	$pdf->setX(0);
	$pdf->output();

?>