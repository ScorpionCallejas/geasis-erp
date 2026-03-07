<?php  
	
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
  	
  	$column = array(
    	
    	'Accion',

		'fol_pag',
		'est_pag',
		'con_pag',
		'mon_pag',
		'cobrado_pago',
		'fin_pag',
	    'tip_abo_pag',

	    // 'bol_alu',
	    // 'fot_alu',
	    // 'nom_alu',
	    // 'tel_alu',
	    // 'nom_gen',
	    // 'nom_ram',

	    // 'obs_pag',
	    // 'tip_pag',
	    // 'fac_pag'
	    
  	);
  	
 	
 	if ( $_POST['id_alu_ram'] != '' ) {
 		
 		$id_alu_ram = $_POST['id_alu_ram'];

 		$sql = "

			SELECT * 
			FROM vista_pagos
			WHERE id_alu_ram = '$id_alu_ram' AND est_pag = 'Pendiente'
      	";

    } else if ( $_POST['id_gen'] != '' ) {
    	
    	$id_gen = $_POST['id_gen'];

 		$sql = "

			SELECT * 
			FROM vista_pagos
			WHERE id_gen1 = '$id_gen'

      	";
    
 	} else {
 		// 
 		if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {
      
	      	$sql = "

				SELECT * 
				FROM vista_pagos
				WHERE

	      	";

	      	// PALABRA
	      	// if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

		        $palabra = $_POST['palabra'];
		        
		        $sql .= "
		        	
	        		( CAST(UPPER(nom_alu) AS CHAR CHARACTER SET utf8) LIKE CAST(UPPER('%$palabra%') AS CHAR CHARACTER SET utf8) 		COLLATE utf8_general_ci )
			        OR
			        ( CAST(UPPER(bol_alu) AS CHAR CHARACTER SET utf8) LIKE CAST(UPPER('%$palabra%') AS CHAR CHARACTER SET utf8) 		COLLATE utf8_general_ci )
			        OR
			        ( CAST(UPPER(tel_alu) AS CHAR CHARACTER SET utf8) LIKE CAST(UPPER('%$palabra%') AS CHAR CHARACTER SET utf8) 		COLLATE utf8_general_ci )
		        	
		        ";



		        $sql .= "
		        	AND ( id_pla8 = '$plantel' ) AND ( estatus_general != 'Baja definitiva' AND estatus_general != 'Suspendido' )
		        ";

		        // $sql .= "
		        // 	AND ( 
		        // 		ini_pag BETWEEN '$inicio' AND '$fin'
		        //   	)
		        // ";


		        // tipo_pago
			    if ( ( isset( $_POST['tipo_pago'] ) ) && ( sizeof( $_POST['tipo_pago'] ) > 0 ) ) {

			        $tipo_pago = $_POST['tipo_pago'];

			        $sql .= " AND ";
			        for ( $i = 0 ;  $i < sizeof( $tipo_pago )  ;  $i++ ) { 
			          
			          	if ( sizeof( $tipo_pago ) == 1 ) {
			              
			                $sql .= " 
			                	( tip_pag = '$tipo_pago[$i]' )
			                ";

			              	break;
			              	break;

			            } else if ( $i == ( sizeof( $tipo_pago ) -1 ) ) {

			              	$sql .= " 
		                    	tip_pag = '$tipo_pago[$i]' )
		                    ";
			                  

			            } else {
			                  
			            	
			            	if ( $i == 0 ) {
			              
			                	$sql .= " ( ";
			              
			              	}

			              	$sql .= "tip_pag = '$tipo_pago[$i]' OR ";

		                    
			                   

						}

			        }

			    }
			    // FIN tipo_pago
	        
	      	// }
	      	// FIN PALABRA

	    } else {
	    // VISTA COMPLETA

	    	$sql = "
		          SELECT * 
		          FROM vista_pagos
		          WHERE ( id_pla8 = '$plantel' ) AND ( est_pag = 'Pendiente' ) AND ( estatus_general != 'Baja definitiva' AND estatus_general != 'Suspendido' ) AND ( ini_pag BETWEEN '$inicio' AND '$fin' )
		    ";


		    // tipo_pago
		    if ( ( isset( $_POST['tipo_pago'] ) ) && ( sizeof( $_POST['tipo_pago'] ) > 0 ) ) {

		        $tipo_pago = $_POST['tipo_pago'];

		        $sql .= " AND ";
		        for ( $i = 0 ;  $i < sizeof( $tipo_pago )  ;  $i++ ) { 
		          
		          	if ( sizeof( $tipo_pago ) == 1 ) {
		              
		                $sql .= " 
		                	( tip_pag = '$tipo_pago[$i]' )
		                ";

		              	break;
		              	break;

		            } else if ( $i == ( sizeof( $tipo_pago ) -1 ) ) {

		              	$sql .= " 
	                    	tip_pag = '$tipo_pago[$i]' )
	                    ";
		                  

		            } else {
		                  
		            	
		            	if ( $i == 0 ) {
		              
		                	$sql .= " ( ";
		              
		              	}

		              	$sql .= "tip_pag = '$tipo_pago[$i]' OR ";

					}

		        }

		    }
		    // FIN tipo_pago


	    // FIN VISTA COMPLETA 
	    }


 		// 
 	}
 	


// MONTH( ini_pag ) = 10 AND YEAR( ini_pag ) = 2021

    $dia = date( 'd' );
  	if ( isset( $_POST['order'] ) ) {
// , ini_pag DESC
    	$sql .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
  
  	} else {

    	$sql .= 'ORDER BY est_pag DESC, ini_pag ASC, DAY( ini_pag )= '.$dia.' DESC ';

  	}

  	$sql1 = '';

  	if ( $_POST["length"] != -1 ) {
    
    	$sql1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
  
 	}

	// echo $sql;

	$resultado = mysqli_query( $db, $sql );

	$totalFilas = mysqli_num_rows( $resultado );

	$sql .= $sql1;

	$resultado1 = mysqli_query( $db, $sql );

	$data = array();

	$i = 1;
  
  	// echo $sql;

	while( $fila = mysqli_fetch_assoc( $resultado1 ) ) {
		
		$sub_array = array();
		$id_pag = $fila['id_pag'];

		$id_gen1 = $fila['id_gen1'];

		$sqlCalendario = "
			SELECT *
			FROM generacion_pago
			WHERE id_gen2 = '$id_gen1'
		";

		$datos_calendario = obtener_datos_consulta( $db, $sqlCalendario );

		$calendario = '';
		
		if ( $datos_calendario['total'] > 0 ) {

			if ( $fila['id_gen_pag2'] == NULL ) {

				$calendario = '
					
					<a class="dropdown-item calendarioPago" href="#" title="Asociar a calendario de pagos" id_pag="'.$fila['id_pag'].'">
	                  Asociar a calendario de pagos
	                </a>
					
                ';

			} else {

				$calendario = '
					<a class="dropdown-item calendarioPago" href="#" title="Desasociar de calendario de pagos" id_pag="'.$fila['id_pag'].'">
	                  Desasociar de calendario de pagos
	                </a>
                ';
			}
		}

		if ( $fila['est_pag'] == 'Pagado' ) {
	    	
	    	$acciones = '

                <a class="dropdown-item historialPago" id_pag="'.$id_pag.'" title="Haz clic para ver el historial de movimientos de '.$fila['con_pag'].'">
                  Consultar
                </a>


                <a class="dropdown-item eliminacionCobro" href="#" title="Eliminar cobro de '.$fila['con_pag'].' ( Requiere permisos de Administrador )" id_pag="'.$fila['id_pag'].'" con_pag="'.$fila['con_pag'].'">
                  Eliminar
                </a>

	    	';


	    } else {

			if( $fila['not_pag'] == 'Pendiente' ){
				$acciones = '
					<a class="dropdown-item eliminacionCobro" href="#" title="Eliminar cobro de '.$fila['con_pag'].' ( Requiere permisos de Administrador )" id_pag="'.$fila['id_pag'].'" con_pag="'.$fila['con_pag'].'">
					Eliminar
					</a>
					'.$calendario.'
				';
			} else {
				$acciones = '
					<a class="dropdown-item abonarCobro" href="#" title="Pagar: '.$fila['con_pag'].' ( Requiere permisos de Administrador )" id_pag="'.$fila['id_pag'].'">
					Pagar
					</a>

					<a class="dropdown-item historialPago" id_pag="'.$id_pag.'" title="Haz clic para ver el historial de movimientos de '.$fila['con_pag'].'">
					Consultar
					</a>

					<a class="dropdown-item condonarCobro" href="#" title="Aplicar descuento '.$fila['con_pag'].' ( Requiere validación de Administrador )" id_pag="'.$fila['id_pag'].'">
						Descuento
					</a>

					<a class="dropdown-item convenirCobro" href="#" title="Recalendarizar fechas de '.$fila['con_pag'].' ( Requiere validación de Administrador )" id_pag="'.$fila['id_pag'].'">
						Prorrogar
					</a>

					<a class="dropdown-item eliminacionCobro" href="#" title="Eliminar cobro de '.$fila['con_pag'].' ( Requiere permisos de Administrador )" id_pag="'.$fila['id_pag'].'" con_pag="'.$fila['con_pag'].'">
					Eliminar
					</a>
					'.$calendario.'
				';
			}
	    	

	    }
		
		if( $fila['not_pag'] == 'Pendiente' ){
			$sub_array[] = '
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					Pendiente de aprobación por PRESIDENCIA EJECUTIVA
				</div>

				<div class="dropdown">

					<!--Trigger-->

					<a class="btn-link dropdown-toggle" href="#" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:10px; " >
						Acciones
					</a>


					<!--Menu-->
					<div class="dropdown-menu prueba_posicion" aria-labelledby="dropdownMenuButton" style="font-size:10px; position: inherit !important; z-index: 99999;">
						'.$acciones.'
					</div>


				</div>
			';
		} else {
			$sub_array[] = '
				<div class="dropdown">

					<!--Trigger-->

					<a class="btn-link dropdown-toggle" href="#" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:10px; " >
						Acciones
					</a>


					<!--Menu-->
					<div class="dropdown-menu prueba_posicion" aria-labelledby="dropdownMenuButton" style="font-size:10px; position: inherit !important; z-index: 99999;">
						'.$acciones.'
					</div>


				</div>
			';
		}
		

		$estatus_pago = obtenerEstatusPago2( $fila['id_pag'] );
		
		$text_color = 'text-black';
		if ( $estatus_pago == 'Vencido' ) {
			$text_color = 'text-danger';
		} else if ( $estatus_pago == 'Pendiente' ) {
			$text_color = 'text-black';
		}

		// DATOS PAGO
		// CON Y SIN BUSQUEDA fol_pag
		$id_pag = htmlspecialchars( $fila['id_pag'] );

		if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

	      	$palabra = $_POST['palabra'];

	      	if ( stripos($id_pag, $palabra) !== false ) {
	                //echo 'hay coincidencia';
	          	$first_pos = stripos($id_pag, $palabra);
	          	$last_pos = strlen ($palabra) + $first_pos - 1;
	          	$longitudCadena = strlen($id_pag);

	          	if ( $first_pos == 0 ) {

	            	$sub_array[] = "<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($id_pag, $first_pos, $last_pos+1)."</span>".substr($id_pag, $last_pos+1, $longitudCadena)."</span>";

	          	} else {
	        
	            	$sub_array[] = substr($id_pag, 0, $first_pos)."<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($id_pag, $first_pos, $last_pos-$first_pos+1)."</span>".substr($id_pag, $last_pos+1, $longitudCadena-$last_pos)."</span>";
	       
	          // substr($id_pag, 0, $first_pos)."<span class='bg-info'>".
	            
	          	}
	          

	      	} else {

	        	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$id_pag.'</span></span>';
	      
	      	}
	    
		} else {

			$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$id_pag.'</span></span>';
	    	
	    }
		// $sub_array[] = 'fol_pag';

		// $sub_array[] = $fila['fol_pag'];

		// FIN CON Y SIN BUSQUEDA fol_pag

	    $sub_array[] = obtenerEstatusPago( $fila['id_pag'] );


	    // CON Y SIN BUSQUEDA con_pag
		$con_pag = htmlspecialchars( $fila['con_pag'] );

		if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

	      	$palabra = $_POST['palabra'];

	      	if ( stripos($con_pag, $palabra) !== false ) {
	                //echo 'hay coincidencia';
	          	$first_pos = stripos($con_pag, $palabra);
	          	$last_pos = strlen ($palabra) + $first_pos - 1;
	          	$longitudCadena = strlen($con_pag);

	          	if ( $first_pos == 0 ) {

	            	$sub_array[] = "<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($con_pag, $first_pos, $last_pos+1)."</span>".substr($con_pag, $last_pos+1, $longitudCadena)."</span>";

	          	} else {
	        
	            	$sub_array[] = substr($con_pag, 0, $first_pos)."<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($con_pag, $first_pos, $last_pos-$first_pos+1)."</span>".substr($con_pag, $last_pos+1, $longitudCadena-$last_pos)."</span>";
	       
	          // substr($con_pag, 0, $first_pos)."<span class='bg-info'>".
	            
	          	}
	          

	      	} else {

	        	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$con_pag.'</span></span>';
	      
	      	}
	    
		} else {

			$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$con_pag.'</span></span>';
	    
	    }
		// $sub_array[] = 'con_pag';

		// $sub_array[] = $fila['con_pag'];

		// FIN CON Y SIN BUSQUEDA con_pag

	    $sub_array[] = '<span class="'.$text_color.'">'.formatearDinero( $fila['mon_pag'] )."</span>";


	    $sub_array[] = '<span class="'.$text_color.'">'.formatearDinero( $fila['cobrado_pago'] )."</span>";


	    $sub_array[] = '<span class="'.$text_color.'">'.fechaFormateadaCompacta2( $fila['fin_pag'] )."</span>";
		// FIN DATOS PAGO

		$sub_array[] = '<span class="'.$text_color.'">'.$fila['tip_abo_pag']."</span>";

		// CON Y SIN BUSQUEDA bol_alu
		// $bol_alu = htmlspecialchars( $fila['bol_alu'] );

		// if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

	    //   	$palabra = $_POST['palabra'];

	    //   	if ( stripos($bol_alu, $palabra) !== false ) {
	    //             //echo 'hay coincidencia';
	    //       	$first_pos = stripos($bol_alu, $palabra);
	    //       	$last_pos = strlen ($palabra) + $first_pos - 1;
	    //       	$longitudCadena = strlen($bol_alu);

	    //       	if ( $first_pos == 0 ) {

	    //         	$sub_array[] = "<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($bol_alu, $first_pos, $last_pos+1)."</span>".substr($bol_alu, $last_pos+1, $longitudCadena)."</span>";

	    //       	} else {
	        
	    //         	$sub_array[] = substr($bol_alu, 0, $first_pos)."<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($bol_alu, $first_pos, $last_pos-$first_pos+1)."</span>".substr($bol_alu, $last_pos+1, $longitudCadena-$last_pos)."</span>";
	       
	    //       // substr($bol_alu, 0, $first_pos)."<span class='bg-info'>".
	            
	    //       	}
	          

	    //   	} else {

	    //     	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$bol_alu.'</span></span>';
	      
	    //   	}
	    
		// } else {

		// 	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$bol_alu.'</span></span>';
	    
	    // }
		// // $sub_array[] = 'bol_alu';

		// // $sub_array[] = $fila['bol_alu'];

		// // FIN CON Y SIN BUSQUEDA bol_alu
		
		// $sub_array[] = '<a style="position: relative;" class="link-chido" target="_blank" href="consulta_alumno.php?alumno='.$fila['nom_alu'].'&id_alu_ram='.$fila['id_alu_ram'].'" id_alu_ram="'.$fila['id_alu_ram'].'" title="Resumen General de '.$fila['nom_alu'].'"><img width="50px" height="55px" src="'.obtenerValidacionFotoUsuarioServer( $fila['fot_alu'] ).'" alt="avatar" class="avatar rounded-circle mr-0 ml-3 z-depth-1 waves-effect">'.obtenerBadgeEstatusEjecutivoPosicion( $fila['estatus_general'] ).'</a>';


		// // CON Y SIN BUSQUEDA nom_alu
		// $nom_alu = htmlspecialchars( $fila['nom_alu'] );

		// if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

	    //   	$palabra = $_POST['palabra'];

	    //   	if ( stripos($nom_alu, $palabra) !== false ) {
	    //             //echo 'hay coincidencia';
	    //       	$first_pos = stripos($nom_alu, $palabra);
	    //       	$last_pos = strlen ($palabra) + $first_pos - 1;
	    //       	$longitudCadena = strlen($nom_alu);

	    //       	if ( $first_pos == 0 ) {

	    //         	$sub_array[] = "<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($nom_alu, $first_pos, $last_pos+1)."</span>".substr($nom_alu, $last_pos+1, $longitudCadena)."</span>";

	    //       	} else {
	        
	    //         	$sub_array[] = substr($nom_alu, 0, $first_pos)."<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($nom_alu, $first_pos, $last_pos-$first_pos+1)."</span>".substr($nom_alu, $last_pos+1, $longitudCadena-$last_pos)."</span>";
	       
	    //       // substr($nom_alu, 0, $first_pos)."<span class='bg-info'>".
	            
	    //       	}
	          

	    //   	} else {

	    //     	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$nom_alu.'</span></span>';
	      
	    //   	}
	    
		// } else {

		// 	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$nom_alu.'</span></span>';
	    
	    // }
		// // $sub_array[] = 'nom_alu';

		// // $sub_array[] = $fila['nom_alu'];

		// // FIN CON Y SIN BUSQUEDA nom_alu


	    // // CON Y SIN BUSQUEDA tel_alu
		// $tel_alu = htmlspecialchars( $fila['tel_alu'] );
		// if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

	    //   	$palabra = $_POST['palabra'];

	    //   	if ( stripos($tel_alu, $palabra) !== false ) {
	    //             //echo 'hay coincidencia';
	    //       	$first_pos = stripos($tel_alu, $palabra);
	    //       	$last_pos = strlen ($palabra) + $first_pos - 1;
	    //       	$longitudCadena = strlen($tel_alu);

	    //       	if ( $first_pos == 0 ) {

	    //         	$sub_array[] = "<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($tel_alu, $first_pos, $last_pos+1)."</span>".substr($tel_alu, $last_pos+1, $longitudCadena)."</span>";

	    //       	} else {
	        
	    //         	$sub_array[] = substr($tel_alu, 0, $first_pos)."<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($tel_alu, $first_pos, $last_pos-$first_pos+1)."</span>".substr($tel_alu, $last_pos+1, $longitudCadena-$last_pos)."</span>";
	       
	    //       // substr($tel_alu, 0, $first_pos)."<span class='bg-info'>".
	            
	    //       	}
	          

	    //   	} else {

	    //     	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$tel_alu.'</span></span>';
	      
	    //   	}

	    
		// } else {

		// 	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$tel_alu.'</span></span>';
	    
	    // }

		// $sub_array[] = 'tel_alu';
  		// FIN CON Y SIN BUSQUEDA tel_alu


	    // CON Y SIN BUSQUEDA nom_gen
		// $nom_gen = htmlspecialchars( $fila['nom_gen'] );

		// if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {

	    //   	$palabra = $_POST['palabra'];

	    //   	if ( stripos($nom_gen, $palabra) !== false ) {
	    //             //echo 'hay coincidencia';
	    //       	$first_pos = stripos($nom_gen, $palabra);
	    //       	$last_pos = strlen ($palabra) + $first_pos - 1;
	    //       	$longitudCadena = strlen($nom_gen);

	    //       	if ( $first_pos == 0 ) {

	    //         	$sub_array[] = "<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($nom_gen, $first_pos, $last_pos+1)."</span>".substr($nom_gen, $last_pos+1, $longitudCadena)."</span>";

	    //       	} else {
	        
	    //         	$sub_array[] = substr($nom_gen, 0, $first_pos)."<span class='".$text_color."'><span class='bg-info white-text font-weight-normal'>".substr($nom_gen, $first_pos, $last_pos-$first_pos+1)."</span>".substr($nom_gen, $last_pos+1, $longitudCadena-$last_pos)."</span>";
	            
	    //       	}
	          

	    //   	} else {

	    //     	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$nom_gen.'</span></span>';
	      
	    //   	}
	    
		// } else {

		// 	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$nom_gen.'</span></span>';
	    
	    // }
		// $sub_array[] = 'nom_gen';

		// $sub_array[] = $fila['nom_gen'];

		// FIN CON Y SIN BUSQUEDA nom_gen


		// $sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">'.$fila['nom_ram'].'</span></span>';


		

	    // // VALIDACION NOTAS PAGO
	    //  $sqlNotas = "
	    // 	SELECT *
	    // 	FROM nota_pago
	    // 	WHERE id_pag6 = '$id_pag'
	    // 	ORDER BY id_not_pag DESC
	    // ";

	    // $resultadoNotas = mysqli_query( $db, $sqlNotas );

	    // if ( !$resultadoNotas ) {
	    // 	echo $sqlNotas;
	    // } else {
	    	
	    // 	$resultadoTotal = mysqli_query( $db, $sqlNotas );

	    // }

	    // $total = mysqli_num_rows( $resultadoTotal );

	    // if ( $total == 0 ) {

	    // 	$sub_array[] = '<span class="'.$text_color.'"><span class="font-weight-normal">Sin notas</span></span>';
	    
	    // } else {
	    // 	$cadena = '';
	    // 	while( $filaNotas = mysqli_fetch_assoc( $resultadoNotas ) ){
	    // 		$cadena .= $filaNotas['con_not_pag']."<br>";
	    // 	}
	    // 	$sub_array[] = $cadena;

	    // }
	    // // FIN VALIDACION NOTAS PAGO

	    


	    // $sub_array[] = '<span class="'.$text_color.'">'.$fila['tip_pag']."</span>";



	    // $accion_factura = '';

	      
    	// if ( $fila['fac_pag'] == 'Activo' ) {
    
    	// 	$accion_factura = '<div class="form-check">
    	// 					  	<input type="checkbox" class="form-check-input checkboxFacturacionPago" id_pag="'.$id_pag.'" id="checkboxFacturacionPago'.$id_pag.'" checked value="Inactivo">
    	// 					  	<label class="form-check-label" for="checkboxFacturacionPago'.$id_pag.'"></label>
    	// 					</div>';
    
     
    	// } else if ( $fila['fac_pag'] == 'Inactivo' ) {
    

    	// 	$accion_factura = '<div class="form-check">
		// 	  	<input type="checkbox" class="form-check-input checkboxFacturacionPago" id_pag="'.$id_pag.'" id="checkboxFacturacionPago'.$id_pag.'" value="Activo">
		// 	  	<label class="form-check-label" for="checkboxFacturacionPago'.$id_pag.'"></label>
		// 	</div>';
    
    	// }
    
	    // $sub_array[] = $accion_factura;


		

	    

		// $sub_array[] = $sql;

    	$data[] = $sub_array;
    	$i++;
  
  	}


  	$resultadoTotal = mysqli_query( $db, $sql );

 	$total = mysqli_num_rows( $resultadoTotal ); 

  	$output = array(
   		"draw"       =>  intval($_POST["draw"]),
   		"recordsTotal"   =>  $total,
   		"recordsFiltered"  =>  $totalFilas,
   		"data"       =>  $data
  	);

 	echo json_encode( $output );

 	// var_dump( $data );

            
?>