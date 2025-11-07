<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
	</head>
	<style>
		.form-group{
			margin: 10px;
		}
		.botones{
			margin: 100px;
		}
		.botones > div{
			margin: 20px 0px 0px 0px;
		}
		table, th, td {
		  	border: 1px solid;
		}
	</style>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<?php
				echo $tabla;
			?>
		<input type="submit" name="nuevo" value="Nueva entrada" class="btn btn-primary btn-lg" />
		<input type="submit" value="Volver" name="Volver" class="btn btn-success btn-lg" />
		</form>
	</div>
</body>
</html>