<?php  

    include 'inc/header.php';

    include 'inc/footer.php';

?>

<script type="text/javascript">
	
	datos = {

	    emisor: 'admin',
	    destino: 'Trafico',
	    mensaje: 'hello world!'
	
	};


	setTimeout(function(){
		
		socket.send( JSON.stringify( datos ) );

	}, 10000 );
	
	

</script>