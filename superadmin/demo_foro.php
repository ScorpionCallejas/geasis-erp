<style>
#flecha {
  position: absolute;
  right: -150px;
  width: 300px;
  padding: 10px;
  z-index: 99;
}
</style>
<?php   

	include('inc/header.php');
	$id_for = $_GET['id_for'];

	$sqlForo = "
    SELECT * 
    FROM foro 
    INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    INNER JOIN rama ON rama.id_ram = materia.id_ram2
    WHERE id_for = '$id_for'";
  $resultadoForo = mysqli_query($db, $sqlForo);
  $filaForo = mysqli_fetch_assoc($resultadoForo);

  $nom_for = $filaForo['nom_for'];
  $des_for = $filaForo['des_for'];
  $des_blo = $filaForo['des_blo'];
  $pun_for = $filaForo['pun_for'];
  $ini_for = $filaForo['ini_for'];
  $fin_for = $filaForo['fin_for'];
  $id_blo = $filaForo['id_blo4'];
  $nom_blo = $filaForo['nom_blo'];
  $nom_mat = $filaForo['nom_mat'];
  $nom_ram = $filaForo['nom_ram'];
  $id_mat = $filaForo['id_mat'];
  $id_ram = $filaForo['id_ram'];



?>



<!-- TITULO -->
<div class="row ">
  <div class="col text-left">
    <span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Creación de Foro">
      <i class="fas fa-bookmark"></i> 
      Creación de Foro
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
        <a style="color: black;" href="" title="Estás aquí">Foro</a>
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
      Foro: <?php echo $nom_for; ?>
    </span>

    
    
  </div>
  
</div>
<!-- FIN TITULO -->
<!-- FIN DE DESPLIEGUE DE BANDAGE TITULO DE BLOQUE Y DEL FORO-->


<!-- DETALLES DEL FORO -->
<div class="row">
	<div class="col">
		<div class="card border-warning mb-3" style="max-width: 20rem;">
		  <div class="card-header bg-white text-center">
		  	<i class="fas fa-award prefix blue-text fa-2x"></i> 
		  	Puntos
		  </div>
		  <div class="card-body text-center">
		    <h5 class="card-title">+ <?php echo $pun_for; ?></h5>
		   
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
		    <h5 class="card-title"> <?php echo date(''.$ini_for.'-m-Y'); ?></h5>
		   
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
		    <h5 class="card-title"> <?php echo date(''.$fin_for.'-m-Y'); ?></h5>
		   
		  </div>
		</div>
	</div>

</div>
<!-- FIN DETALLES DEL FORO -->


	<!-- CONTENIDO -->
	<div class="row">
		<div class="col-md-12">
	
		
				<div id="box" class="bg-white">
						<?php echo $des_for; ?>
				</div>
		</div>
	</div>
<!-- Reply section (logged in user) -->
<section class="my-5 bg-white">

  <div class="card-header border-0 font-weight-bold bg-info">Mi comentario</div>

  <div class="d-md-flex flex-md-fill px-1">
    <div class="d-flex justify-content-center mr-md-5 mt-md-5 mt-4">
      <img class="card-img-100 z-depth-1 rounded-circle" src="https://mdbootstrap.com/img/Photos/Avatars/img (32).jpg"
        alt="avatar">
    </div>
    <div class="md-form w-100">
      <textarea class="form-control md-textarea pt-0" id="exampleFormControlTextarea1" rows="5" placeholder="Deja un comentario"></textarea>
    </div>
  </div>
  <div class="text-center">
    <button class="btn btn-default btn-rounded btn-md">Enviar</button>
  </div>

</section>
<!-- Reply section (logged in user) -->
	<!--Section: Comments-->
<section class="my-5 bg-white">

  <!-- Card header -->
  <div class="card-header border-0 font-weight-bold bg-info">4 comentarios</div>

  <div class="media d-block d-md-flex mt-4">
    <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="https://mdbootstrap.com/img/Photos/Avatars/img (20).jpg"
      alt="Generic placeholder image">
    <div class="media-body text-center text-md-left ml-md-3 ml-0">
      <h5 class="font-weight-bold mt-0">
        <a class="text-default" href="#">Miley Steward</a>
        <a href="#" class="pull-right text-default">
          <i class="fas fa-reply"></i>
        </a>
      </h5>
      Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
      Excepteur sint occaecat
      cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      <div class="media d-block d-md-flex mt-4">
        <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="https://mdbootstrap.com/img/Photos/Avatars/img (27).jpg"
          alt="Generic placeholder image">
        <div class="media-body text-center text-md-left ml-md-3 ml-0">
          <h5 class="font-weight-bold mt-0">
            <a class="text-default" href="#">Tommy Smith</a>
            <a href="#" class="pull-right text-default">
              <i class="fas fa-reply"></i>
            </a>
          </h5>
          Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
          totam rem aperiam, eaque
          ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
        </div>
      </div>

      <!-- Quick Reply -->
      <div class="md-form mt-4">
        <label for="quickReplyFormComment">Replica</label>
        <textarea class="form-control md-textarea" id="quickReplyFormComment" rows="3"></textarea>

        <div class="text-center my-4">
          <button class="btn btn-default btn-sm btn-rounded" type="submit">Replicar</button>
        </div>
      </div>

      <div class="media d-block d-md-flex mt-3">
        <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="https://mdbootstrap.com/img/Photos/Avatars/img (21).jpg"
          alt="Generic placeholder image">
        <div class="media-body text-center text-md-left ml-md-3 ml-0">
          <h5 class="font-weight-bold mt-0">
            <a class="text-default" href="#">Sylvester the Cat</a>
            <a href="#" class="pull-right text-default">
              <i class="fas fa-reply"></i>
            </a>
          </h5>
          Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed
          quia non numquam eius modi
          tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
        </div>
      </div>
    </div>
  </div>
  <div class="media d-block d-md-flex mt-3">
    <img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="https://mdbootstrap.com/img/Photos/Avatars/img (30).jpg"
      alt="Generic placeholder image">
    <div class="media-body text-center text-md-left ml-md-3 ml-0">
      <h5 class="font-weight-bold mt-0">
        <a class="text-default" href="#">Caroline Horwitz</a>
        <a href="#" class="pull-right text-default">
          <i class="fas fa-reply"></i>
        </a>
      </h5>
      At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti
      atque corrupti
      quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
      officia deserunt mollitia animi, id est laborum et dolorum fuga.
    </div>
  </div>

	<br>
	<br>
</section>

<br>

				

<!-- FIN CONTENIDO -->
<?php  

	include('inc/footer.php');

?>