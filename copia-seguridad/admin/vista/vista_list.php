<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
		<link href="/admin/css/list.css" rel="stylesheet">
		<script src="/admin/js/list.js" type="text/javascript"></script>
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<div id="contenedor">
				<?php
					if($cookie == "Listado Mensual"){
						echo "<div class='btn-group btn-group-toggle' data-toggle='buttons' id='etapas'>";
						foreach ($array as $id => $nombre) {
							echo "<input type='submit' name='Etapa' value=\"$nombre\" class='btn btn-primary btn-lg' />";
						}
						echo "</div>";
						if(!empty($clases)){
							echo "<div class='btn-group btn-group-toggle' data-toggle='buttons' id='clases'>";
							foreach ($clases as $id => $nombre) {
								echo "<input type='submit' name='Clase' id='clase' value=\"$nombre\" autocomplete='off' class='btn btn-primary'/>";
							}
							echo "</div>";
						}
						echo $listado;
					}elseif($cookie == "Resumen Mensual"){
						echo "<div id='tablas'>";
						echo $listado2;
						echo "</div>";
					}elseif($cookie == "Resumen Asistencias"){
						echo "<div id='tablas'>";
						echo $listado3;
						echo "</div>";
					}
				?>
			</div>
			<div id="meses">
				<input type="submit" name="anterior" value="&#129152;" class="btn btn-primary btn-lg" />
				<input type="month" name="mes" id="mes" value="<?php echo $meses->format('Y-m'); ?>" class="btn btn-primary btn-lg"/>
				<input type="submit" name="siguiente" value="&#129154;" class="btn btn-primary btn-lg" />
			</div>
			<input type="submit" value="Volver" name="Volver" class="btn btn-success btn-lg" />
		</form>
	</div>
</body>
</html>