<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link href="/comedorv2/admin/css/administracion.css" rel="stylesheet">
		<link rel="icon" href="/comedorv2/css/corazon_maristas.jpg" type="image/x-icon">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	</head>
	<body>
		<form action="" method="post" class="card-body">
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<div class="container-fluid">
					<img class="navbar-brand" id="imagen" src="/comedorv2/css/logo_chamberi_def.jpg" alt="Logo">
					<div class="ms-auto">
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse" id="navbarScroll">
						<ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
							<li class="nav-item">
								<input type="submit" name="Cerrar" value="Cerrar Sesion" class="nav-link active" />
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Configuracion
								</a>
								<ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
									<?php
										if(!empty($arrayConfiguracion)){
											foreach ($arrayConfiguracion as $valor) {
												echo "<li><input type='submit' name='configuracion' value='".$valor."' class='dropdown-item' /></li>";
											}
										}
									?>
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Listado
								</a>
								<ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
									<?php
										if(!empty($dropDownListado)){
											foreach ($dropDownListado as $valor) {
												echo "<li><input type='submit' name='listado' value='".$valor."' class='dropdown-item' /></li>";
											}
										}
									?>
								</ul>
							</li>
							<li class="nav-item">
								<input type="submit" name="altas/bajas" value="Alta / Baja" class="nav-link" />
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</form>
	</body>
</html>