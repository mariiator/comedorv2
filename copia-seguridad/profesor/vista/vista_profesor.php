<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
		<link href="/profesor/css/profesor.css" rel="stylesheet">
		<script src="/profesor/js/profesor.js" type="text/javascript"></script>
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<div id="meses">
				<input type="submit" name="anterior" value="&#129152;" class="btn btn-primary btn-lg" />
				<input type="month" name="mes" id="mes" value="<?php echo $meses->format('Y-m'); ?>" class="btn btn-primary btn-lg"/>
				<input type="submit" name="siguiente" value="&#129154;" class="btn btn-primary btn-lg" />
			</div>
			<div id="contenedor">
				<?php
					echo $tabla;
				?>
			</div>
			<input type="submit" value="Volver" name="Volver" class="btn btn-success btn-lg" />
			<a href="<?php echo $pdf; ?>" class="btn btn-primary btn-lg" target="_blank" id="pdf">Ver Menus</a>
		</form>
	</div>
</body>
</html>