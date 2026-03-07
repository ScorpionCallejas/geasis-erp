<?php  

  include('inc/header.php');
  $id_ent = $_GET['id_ent'];

  //CONSULTA DE ENTREGABLE
  $sqlEntregable = "SELECT * FROM entregable WHERE id_ent = '$id_ent'";
  $resultadoEntregable = mysqli_query($db, $sqlEntregable);
  $filaEntregable = mysqli_fetch_assoc($resultadoEntregable);

  $nom_ent = $filaEntregable['nom_ent'];
  $des_ent = $filaEntregable['des_ent'];
  $pun_ent = $filaEntregable['pun_ent'];
  $ini_ent = $filaEntregable['ini_ent'];
  $fin_ent = $filaEntregable['fin_ent'];
  $id_blo = $filaEntregable['id_blo5'];

  //CONSULTA DE BLOQUE
  $sqlBloque = "SELECT * FROM bloque
                  INNER JOIN materia ON materia.id_mat = bloque.id_mat6
                  INNER JOIN rama ON rama.id_ram = materia.id_ram2
                  WHERE id_blo = '$id_blo'";
  $resultadoBloque = mysqli_query($db, $sqlBloque);
  $filaBloque = mysqli_fetch_assoc($resultadoBloque);

  $nom_blo = $filaBloque['nom_blo'];
  $des_blo = $filaBloque['des_blo'];
  $con_blo = $filaBloque['con_blo']; 
  $id_ram =  $filaBloque['id_ram'];
  $nom_ram =  $filaBloque['nom_ram'];
  $nom_mat =  $filaBloque['nom_mat'];
  $nom_blo =  $filaBloque['nom_blo'];
  $id_mat =  $filaBloque['id_mat'];


?>

<style>
#flecha {
  position: absolute;
  right: -150px;
  width: 300px;
  padding: 10px;
  z-index: 99;
}
</style>



<!-- TITULO -->
<div class="row ">
  <div class="col text-left">
    <span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Creación de Foro">
      <i class="fas fa-bookmark"></i> 
      Vista del Entregable
    </span>
    <br>
    <div class="badge badge-pill badge-warning animated fadeInUp delay-3s text-white">
        <a class="text-white" href="index.php" title="Vuelve al Inicio">Inicio</a>
        <i class="fas fa-angle-double-right"></i>
        <a class="text-white" href="ramas.php" title="Vuelve a Programas">Programas</a>
        <i class="fas fa-angle-double-right"></i>
        <a class="text-white" href="materias.php?id_ram=<?php echo $id_ram; ?>" title="Vuelve a Materias">Materias</a>
        <i class="fas fa-angle-double-right"></i>
        <a class="text-white" href="bloques.php?id_mat=<?php echo $id_mat; ?>" title="Estás aquí">Bloques</a>
        <i class="fas fa-angle-double-right"></i>
        <a class="text-white" href="bloque_contenido.php?id_blo=<?php echo $id_blo; ?>" title="Vuelve a Contenido">Contenido</a>
        <i class="fas fa-angle-double-right"></i>
        <a style="color: black;" href="" title="Estás aquí">Entregable</a>
    </div>
    
  </div>

  <div class="col text-right">

    <span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materias de <?php echo $nom_ram; ?>">
      <i class="fas fa-certificate"></i>
      Programa: <?php echo $nom_ram; ?>
    </span>
    <br>
    <br>

    <span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Bloques de <?php echo $nom_mat; ?>">
      <i class="fas fa-certificate"></i>
      Materia: <?php echo $nom_mat; ?>
    </span>
    <br>
    <br>

    <span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Contenido de Bloque de <?php echo $nom_blo; ?>">
      <i class="fas fa-certificate"></i>
      Bloque: <?php echo $nom_blo; ?>
    </span>
    <br>
    <br>


    <span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Creación de Foro">
      <i class="fas fa-certificate"></i>
      Entregable: <?php echo $nom_ent; ?>
    </span>

    
    
  </div>
  
</div>
<!-- FIN TITULO -->
<!-- DETALLES DEL FORO -->
<div class="row">
  <div class="col">
    <div class="card border-warning mb-3" style="max-width: 20rem;">
      <div class="card-header bg-white text-center">
        <i class="fas fa-award prefix blue-text fa-2x"></i> 
        Puntos
      </div>
      <div class="card-body text-center">
        <h5 class="card-title">+ <?php echo $pun_ent; ?></h5>
       
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card border-warning mb-3" style="max-width: 20rem;">
      <div class="card-header bg-white text-center">
        <i class="fas fa-minus-circle prefix orange-text fa-2x"></i> 
        Fecha Inicio
      </div>
      <div class="card-body text-center">
        <h5 class="card-title"> <?php echo date(''.$ini_ent.'-m-Y'); ?></h5>
       
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card border-warning mb-3" style="max-width: 20rem;">
      <div class="card-header bg-white text-center">
        <i class="fas fa-plus-circle prefix orange-text fa-2x"></i> 
        Fecha Fin
      </div>
      <div class="card-body text-center">
        <h5 class="card-title"> <?php echo date(''.$fin_ent.'-m-Y'); ?></h5>
       
      </div>
    </div>
  </div>

</div>
<!-- FIN DETALLES DEL FORO -->



  <!-- CONTENIDO -->
  <div class="row">
    <div class="col-md-12">
    <br>


        <div id="box" class="bg-white">
          <div id="des_ent">
            <?php echo $des_ent; ?>

        
          </div>
          
        </div>

        

        <h3>Subir Archivo</h3>
        <div class="file-upload-wrapper">
          <div class="input-group mb-3 border border-success">
            <input type="file" id="input-file-now" class="file_upload " placeholder="Sube Archivo" />
          </div>
        </div>

    </div>

  </div>
       
  <br>

<!-- FIN CONTENIDO -->
<?php  

  include('inc/footer.php');

?>


<script>
    $('.file_upload').file_upload();

</script>