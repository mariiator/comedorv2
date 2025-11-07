<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>LOGIN COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/comedorv2/css/corazon_maristas.jpg" type="image/x-icon">
		<link href="/comedorv2/css/comun.css" rel="stylesheet">
		<link href="/comedorv2/user/css/comedor.css" rel="stylesheet">
		<link href="/comedorv2/css/responsive_v2.css" rel="stylesheet">
	</head>
<body>
    <div class="container">
		<form action="" method="post" class="card-body" id="tuFormulario">
			<div class="botones">
			<?php
				foreach($arrayClases as $id => $clase){
					echo "<input type='submit' name='clase' value=\"$clase\" class='btn btn-primary btn-lg' id='boton' />";
				}
			?>
			</div>
			<div class="botones">
				<input type="submit" value="Volver" name="Volver" class="btn btn-success btn-lg" />
			</div>
		</form>
	</div>
</body>
</html>