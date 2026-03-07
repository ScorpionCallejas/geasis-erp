<?php  

    include('inc/header.php');



$sql_foros = "SELECT cal_act.id_for_cop2, cal_act.id_ent_cop2, cal_act.id_exa_cop2,cal_act.id_alu_ram4, foro.nom_for from cal_act inner join foro_copia on foro_copia.id_for_cop = cal_act.id_for_cop2 INNER JOIN foro on foro.id_for = foro_copia.id_for1 where cal_act.id_alu_ram4 = 5866";
//echo $sql_foros;
$sql_respuesta = mysqli_query($db, $sql_foros);
//$foros = mysqli_fetch_array($sql_respuesta);

?>

<!-- TITULO -->
<div class="row ">
    <div class="col text-left">
        <span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Inicio"><i class="fas fa-bookmark"></i> Inicio</span>
        <br>
        <div class=" badge badge-warning animated fadeInUp delay-3s text-white">
            <a href="index.php" title="Estás aquí"><span class="text-white">Inicio</span></a>
            <?php 
                echo 'kakakakakakakakaka<br>';
                

             ?>

        </div>
        
    </div>
    <?php 

    while ($finfo = mysqli_fetch_field($sql_respuesta)) {

           // echo $result->fetch_field().'<br>';
                echo $finfo['nom_for'].'<br>';
                //echo $finfo[3]['nom_for'].'<br>';
                //var_dump($sql_respuesta);
    }

     ?>
        
</div>
<!-- FIN TITULO -->



<?php  

    include('inc/footer.php');

?>