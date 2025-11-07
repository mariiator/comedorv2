<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="/comedorv2/admin/js/altabaja.js" type="text/javascript"></script>
		<link href="/comedorv2/admin/css/altabaja - copia.css" rel="stylesheet">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	</head>
<body>
	<form action="" method="post" class="card-body">
		<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
			<div class="container-fluid">
				<img class="navbar-brand" id="imagen" src="/comedorv2/css/logo_chamberi_def.jpg" alt="Logo">
				<div class="ms-auto">
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="navbarScroll">
					<ul class="nav navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll justify-content-start" style="--bs-scroll-height: 100px;">
						<li class="nav-item">
							<input type="submit" name="Cerrar" value="Cerrar Sesion" class="nav-link active" />
						</li>
						<li class="nav-item">			
							<a class="nav-separator"></a>
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
									if(!empty($arrayListado)){
										foreach ($arrayListado as $valor) {
											echo "<li><input type='submit' name='listado' value='".$valor."' class='dropdown-item' /></li>";
										}
									}
								?>
							</ul>
						</li>
						<li class="nav-item">			
							<a class="nav-separator"></a>
						</li>
						
						<li class="nav-item">
							<input type="submit" name="altas/bajas" value="Alta / Baja alumnos" class="nav-link" />
						</li>
						
						<?php
						if(!empty($array)){
							echo '<li class="nav-item"><a class="nav-separator"></a></li>';	
							foreach ($array as $valor) {								
								echo "<li class='nav-item justify-content-end'><input type='submit' name='mes' value=".$valor." class='nav-link' /></li>";
							}
						}
						?>
						
						<?php
							if(!empty($array)){
								if (!empty($etapas)) {
									echo '<li class="nav-item"><a class="nav-separator"></a></li>';						
									echo '<li class="nav-item dropdown">';
									echo '<a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Etapas</a><ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">';
									foreach ($etapas as $valor) {
										echo "<li><input type='submit' name='etapa' value='".$valor."' class='dropdown-item' /></li>";
									}
									echo "</ul></li>";
									if (!empty($clases)) {
										echo '<li class="nav-item dropdown">';
										echo '<a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Clases</a><ul class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="navbarScrollingDropdown">';
										foreach ($clases as $valor) {
											echo "<li><input type='submit' name='clase' value='".$valor."' class='dropdown-item' /></li>";
										}
										echo "</ul></li>";
									}
								}
							}
						?>
					</ul>
					<ul class="nav navbar-nav navbar-nav-scroll justify-content-end">
					
					</ul>
				</div>
			</div>
		</nav>
		<?php
			if(!empty($etapas) && !empty($clases) && !empty($tabla)){
				$etapaNavegador = $_COOKIE['etapa'];
				$mes = $_COOKIE['meses'];
				//DECLARO UN ARRAY CON LOS NOMBRES DE LOS MESES EN ORDEN PARA QUE LUEGO SE VEA EN LA VISTA
				$nombreMeses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				$nombremes = $nombreMeses[$mes-1];
				echo '<h1>'.$etapaNavegador." &#129046; ".$claseNavegador." &#129046; ".$nombremes.'</h1>';
				echo "<div id='contenedor'>";
				echo $tabla;
				echo "<input type='submit' name='Resumen' value='".$boton."' class='btn btn-link btn-lg'/>";
				echo "</div>";
			}elseif(empty($etapas) && $mesElegido != 0){
				echo "<div id='copiar'>";
				echo "<div><p>El mes seleccionado no tiene datos</p></div>";
				echo "<div id='boton'><input type='submit' name='mesAnterior' value='Copiar Mes Anterior' class='btn btn-success btn-lg' /></div>";
				echo "</div>";
			}
		?>
	</form>
</body>
</html>