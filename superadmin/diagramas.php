<?php  

  include('inc/header.php');
?>


<!-- DIAGRAMA -->
<?php
  
   $sqlDiagrama = "
   SELECT nom_for AS titulo, ini_for AS inicio, fin_for AS fin, tip_for AS tipo
    FROM `foro` 
    INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    WHERE id_mat6 = '7'
    UNION
    SELECT nom_ent AS titulo, ini_ent AS inicio, fin_ent AS fin, tip_ent AS tipo
    FROM `entregable` 
    INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    WHERE id_mat6 = '7'
    UNION
    SELECT nom_exa AS titulo, ini_exa AS inicio, fin_exa AS fin, tip_exa AS tipo
    FROM `examen` 
    INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    WHERE id_mat6 = '7'

   ";

    $resultadoDiagrama = mysqli_query($db, $sqlDiagrama);

    $totalFilas = mysqli_num_rows($resultadoDiagrama);

    //echo $totalFilas;



    $sqlMaximoEntero = "
    SELECT MAX(fin_for) AS maximo
    FROM foro 
    INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    WHERE id_mat6 = '7'
    UNION
    SELECT MAX(fin_ent) AS maximo
    FROM entregable
    INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    WHERE id_mat6 = '7'
    UNION
    SELECT MAX(fin_exa) AS maximo
    FROM examen
    INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    WHERE id_mat6 = '7'

    ";


    $resultadoMaximoEntero = mysqli_query($db, $sqlMaximoEntero);

    $maximoEntero = 0;

    while ($filaMaximoEntero = mysqli_fetch_assoc($resultadoMaximoEntero)) {
      if ($filaMaximoEntero['maximo'] > $maximoEntero) {
        $maximoEntero = $filaMaximoEntero['maximo'];
      }
    }

    //echo $maximoEntero;

?>

<!-- CONTENIDO -->


<table class="table table-hover table-sm table-striped ui-widget-content" id="html-content-holder">
  <thead class="teal darken-1 white-text text-center">
    
    <tr>
      <th>#</th>
      <th>Tipo</th>
      <th>Actividad</th>

      <?php

        for ($i=1; $i < $maximoEntero; $i++) { 
      ?>
        <th>
          <?php echo $i; ?>
        </th>
      <?php
        }

      ?>
      
      
    </tr>
  </thead>

  <tbody>
    <?php
      $contador = 1;
      while($fila = mysqli_fetch_assoc($resultadoDiagrama)){
    ?>
      <tr>
        <td class="teal darken-2 white-text">
          <?php echo $contador; $contador++; ?>
        </td>

        <td class="teal darken-1 white-text">
          <?php  
            echo $fila['tipo'];
          ?>
        </td>
        <td class="teal lighten-1 white-text">
          <?php  
            echo $fila['titulo'];
          ?>
        </td>

        <?php  
          for ($i=1; $i < $maximoEntero; $i++) { 
            if ($i>=$fila['inicio'] && $i<= $fila['fin']) {
        ?>

          <td class="light-green accent-3">
          </td>

        <?php
            }else{
        ?>
          <td class="">
          </td>
        <?php
            }
          }

        ?>
        


      </tr>


    <?php        
      }

    ?>

    
  </tbody>
</table>

<!-- FIN DIAGRAMA -->
<!-- PRUEBA -->

    <input id="btn-Preview-Image" type="button" value="Preview" />
    <br />
    <h3>Preview :</h3>

    <div id="previewImage" class="div">
    </div>

<?php  

  include('inc/footer.php');

?>

<script>
  var element = $("#html-content-holder"); // global variable
  var getCanvas; // global variable

  html2canvas(element, {
    onrendered: function (canvas) {
        $("#previewImage").append(canvas);
        getCanvas = canvas;
     }
  });

</script>