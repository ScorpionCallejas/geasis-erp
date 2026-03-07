<?php  
 
	include('inc/header.php');

?>
	<div class="row">
		<div class="col-md-3">
			<form method="GET" action="consulta_alumno_busqueda_resultado.php">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Buscar alumno" aria-label="Buscar alumno" name="palabra">
					<button class="btn input-group-text btn-primary waves-effect waves-light" type="submit">Buscar</button>
				</div>
			</form>
		</div>
	</div>

<?php  
	include('inc/footer.php');
?>