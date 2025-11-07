<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link href="/admin/css/altabaja.css" rel="stylesheet">
		<script src="/admin/js/altabaja.js" type="text/javascript"></script>
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<?php
				if(!empty($array)){
					echo '<div id="meses">';
					foreach ($array as $valor) {
						echo "<input type='submit' name='mes' value=".$valor." class='btn btn-primary btn-lg' />";
					}
					echo "</div>";
					if (!empty($etapas)) {
						echo "<div class='btn-group btn-group-toggle' data-toggle='buttons' id='etapas'>";
						foreach ($etapas as $valor) {
							echo "<input type='submit' name='etapa' value=".$valor." class='btn btn-primary btn-lg' />";
						}
						echo "</div>";
						if (!empty($clases)) {
							echo "<div class='btn-group btn-group-toggle' data-toggle='buttons' id='clases'>";
							foreach ($clases as $valor) {
								echo "<input type='submit' id='clase' name='clase' value=".$valor." class='btn btn-primary' />";
							}
							echo "</div>";
							if (!empty($tabla)) {
								echo $tabla;
								echo "<input type='submit' name='Resumen' value='".$boton."' class='btn btn-link btn-lg'/>";
							}
						}
					}elseif(empty($etapas) && $mesElegido != 0){
						echo "<div id='contenedor'>";
						echo "<div><p>El mes seleccionado no tiene datos</p></div>";
						echo "<div id='boton'><input type='submit' name='mesAnterior' value='Copiar Mes Anterior' class='btn btn-success btn-lg' /></div>";
						echo "</div>";
					}
				}
			?>
			<div id="boton">
				<input type="submit" value="Volver" name="Volver" class="btn btn-success btn-lg" />
			</div>
		</form>
	</div>
</body>
</html>