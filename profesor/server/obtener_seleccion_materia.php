<?php  
  //ARCHIVO VIA AJAX PARA OBTENER SALA DE UNA MATERIA
  //materias_horario.php
  require('../inc/cabeceras.php');

  $id_sub_hor = $_POST['id_sub_hor'];

  $sqlSubhor = "
    SELECT * 
    FROM sub_hor
    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    WHERE id_sub_hor = '$id_sub_hor'
  ";

  //echo $sqlSubhor;


  $resultadoSubhor = mysqli_query($db, $sqlSubhor);

  $filaSubhor = mysqli_fetch_assoc($resultadoSubhor);

  $nom_mat = $filaSubhor['nom_mat'];

?>

<!-- TITULO -->
<div class="row ">
  <div class="col text-left">
  </div>

  <div class="col text-right">
    <span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Acciones para <?php echo $nom_mat; ?>">
      <i class="fas fa-certificate"></i> Materia: <?php echo $nom_mat; ?>
    </span><br>
  </div>
</div>


<!-- FIN TITULO -->

<ul class="nav nav-tabs nav-justified md-tabs bg-info animated fadeInUp delay-1s" id="myTabJust" role="tablist">
  <li class="nav-item" title="Bloques de <?php echo $nom_mat; ?>">
    <a class="nav-link active" id="btn_bloques" data-toggle="tab" href="#bloques" role="tab" aria-controls="bloques"
      aria-selected="true"><i class="fas fa-dot-circle"></i> Bloques</a>
  </li>
  <li class="nav-item" title="Sala de mensajería para <?php echo $nom_mat; ?>">
    <a class="nav-link" id="btn_sala" data-toggle="tab" href="#sala" role="tab" aria-controls="sala"
      aria-selected="false"><i class="fas fa-comments"></i> Sala de Mensajería</a>
  </li>


  <li class="nav-item" title="Clase en vivo para <?php echo $nom_mat; ?>">
    <a class="nav-link" id="btn_video" data-toggle="tab" href="#video" role="tab" aria-controls="video"
      aria-selected="false"><i class="fas fa-video"></i> Clase en vivo</a>
  </li>

</ul>

<div class="tab-content card pt-5 grey lighten-3 animated fadeInDown delay-1s" id="myTabContentJust">
	<div class="tab-pane fade show active" id="bloques" role="tabpanel" aria-labelledby="home-tab-just">
	


	</div>
	<div class="tab-pane fade" id="sala" role="tabpanel" aria-labelledby="profile-tab-just">
	

	</div>


  <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="profile-tab-just">
  

  </div>



</div>


<script>
  var id_sub_hor = <?php echo $id_sub_hor; ?>;

  $.ajax({
    url: '../profesor/server/obtener_bloques_materia.php',
    type: 'POST',
    data: {id_sub_hor},
    success: function(respuesta){
      $("#bloques").html(respuesta);
    }
  });


  $("#btn_bloques").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    $.ajax({
      url: '../profesor/server/obtener_bloques_materia.php',
      type: 'POST',
      data: {id_sub_hor},
      success: function(respuesta){
        $("#bloques").html(respuesta);
      }
    });


  });



  $("#btn_sala").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    $.ajax({
      url: '../profesor/server/obtener_sala_materia.php',
      type: 'POST',
      data: {id_sub_hor},
      success: function(respuesta){
        $("#sala").html(respuesta);
      }
    });

    
  });



  $("#btn_video").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    $.ajax({
      url: '../profesor/prueba5.php',
      type: 'POST',
      data: { id_sub_hor },
      success: function(respuesta){
        $("#video").html(respuesta);
      }
    });

    
  });






</script>