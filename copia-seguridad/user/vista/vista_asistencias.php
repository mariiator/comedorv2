<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
		<link href="/user/css/asistencias.css" rel="stylesheet">
	</head>
<body>
    <div class="container ">
    	<div class="centrado">
			<form action="" method="post" class="card-body">
				<label class="texto">Alumnos que tienen que asistir hoy al comedor: </label><label><?php echo $asistir;?></label><input type="submit" name="asistir" value="(Ver listado)" class="btn btn-link" /><br/>
				<label class="texto">Alumnos que han asistido al comedor: </label><label><?php echo $asistido;?></label><input type="submit" name="asistido" value="(Ver listado)" class="btn btn-link" /><br/>
				<label class="texto">Alumnos que faltan por entrar al comedor: </label><label><?php echo $faltan;?></label><input type="submit" name="faltan" value="(Ver listado)" class="btn btn-link" /><br/>
				<div class="botones">
					<div><input type="submit" value="Pasar Lista" name="Lista" class="btn btn-primary btn-lg" /></div>
					<div><input type="submit" value="Volver" name="Atras" class="btn btn-success btn-lg" /></div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>