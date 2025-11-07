<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/comedorv2/css/corazon_maristas.jpg" type="image/x-icon">
		<link href="/comedorv2/css/comun.css" rel="stylesheet">
		<link href="/comedorv2/user/css/menu.css" rel="stylesheet">
		<link href="/comedorv2/css/responsive_v2.css" rel="stylesheet">
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<div class='botones'>
			<?php
				foreach($array as $id => $etapa){
					echo "<div><input type='submit' name='etapa' value=\"$etapa\" class='btn btn-primary btn-lg' /></div>";
				}
			?>
			</div>
			<input type="submit" value="Cerrar" name="Cerrar" class="btn btn-warning btn-lg" id="center" />
		</form>
	</div>
</body>
</html>