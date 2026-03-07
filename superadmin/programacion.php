<?php  

    include 'inc/header.php';
    /////////////////////


    // DECLARAR Y GUARDAR VALOR
    // VARIABLE
    // $billete_20 = 20;

    // $chetos = 15;

    // echo $billete_20 - $chetos;

    // VARIABLES
    // $erick_edad = 30;
    // $gris_edad = 11;
    // echo $erick_edad + $gris_edad;


    // CONDICIONANTES
    // $gris_edad = 11;


    // if ( $gris_edad < 10 ) {
    //     // BLOQUE DE CODIGO 1
    //     echo 'Ganaste un poni';

    // } else {
        
    //     // BLOQUE DE CODIGO 2
    //     echo 'Ganaste carbon';
    
    // }



// $profesor_dejo_tarea = '1';

// SI SE CUMPLE CONDICION 
// if ( $profesor_dejo_tarea == '1' ){
//     // BLOQUE DE CODIGO 1
//     echo 'Si dejo tarea';
// } else {
//     // BLOQUE DE CODIGO 2
//     echo 'No dejo tarea';
// }





// BUCLES
    // VASO VACIO 0
    // $gris_edad = 11;

    // // VASO LLENO 10
    // $mayoria_de_edad = 18;

    // while( $gris_edad < $mayoria_de_edad ){

    //     echo $gris_edad."<br>";
    //     $gris_edad = $gris_edad + 1;

    // }


    $gris_edad = 10;

    // VASO LLENO 10
    $mayoria_de_edad = 1000;

    while( $gris_edad <= $mayoria_de_edad ){

        echo $gris_edad."-";
        // SUMA
        $gris_edad = $gris_edad + 10;

    }

    
    ////////////////
	include 'inc/footer.php'; 

?>