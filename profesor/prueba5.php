<?php  

    include('inc/header.php');

?>
    
    <button id="switcher">Activar ordenamiento</button>


    <div id="example1" class="list-group col">
        <div class="list-group-item">Item 1</div>
        <div class="list-group-item">Item 2</div>
        <div class="list-group-item">Item 3</div>
        <div class="list-group-item">Item 4</div>
        <div class="list-group-item">Item 5</div>
        <div class="list-group-item">Item 6</div>
    </div>



<?php  

    include('inc/footer.php');

?>

<script>
    var example1 = document.getElementById('example1');
    
    sort = new Sortable(example1, {
        animation: 150,
        ghostClass: 'blue-background-class'
    });


    $('#switcher').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var state = sort.option("disabled"); // get

        sort.option("disabled", !state); // set
      
        switcher.innerHTML = state ? 'Desactivar ordenamiento' : 'Activar ordenamiento';

    });



</script>