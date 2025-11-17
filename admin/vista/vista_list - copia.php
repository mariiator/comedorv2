<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="icon" href="/comedorv2/css/corazon_maristas.jpg" type="image/x-icon">
		<link href="/comedorv2/admin/css/list.css" rel="stylesheet">
		<link href="/comedorv2/admin/css/tablasCalendario.css" rel="stylesheet">
		<script src="/comedorv2/admin/js/list.js" type="text/javascript"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	</head>
	<body>
    <nav class="navbar navbar-dark bg-primary">
      <div class="container-fluid">
        <a href="https://comedor.maristaschamberi.com/comedorv2/admin/controlador/mejora/controlador_administracion%20-%20copia.php">
          <img class="navbar-brand" id="imagen" src="/comedorv2/css/logo_chamberi_def.jpg" alt="Logo">
        </a>
        
        <div class="navbar-nav d-flex justify-content-center align-items-center flex-grow-1">
          <form method="post" class="d-flex align-items-center gap-2 mb-0">
            <input type="submit" name="anterior" value="&laquo" class="nav-link" />
            <input type="month" name="mes" id="mes" value="<?php echo $meses->format('Y-m'); ?>" class="nav-link" />
            <input type="submit" name="siguiente" value="&raquo" class="nav-link" />
          </form>
        </div>

        <div class="ms-auto">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
  
        <div class="collapse navbar-collapse" id="navbarScroll">
  
          <div class="container d-flex justify-content-center align-items-center" style="min-height: 50vh;">
  
            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="dropdown d-grid">
                  <button class="btn btn-primary btn-lg dropdown-toggle p-3" type="button" id="configuracionDropdown" data-bs-toggle="dropdown" aria-expanded="false">Configuracion</button>
                  <ul class="dropdown-menu" aria-labelledby="configuracionDropdown">
                    <?php
                      if(!empty($arrayConfiguracion)){
                        foreach ($arrayConfiguracion as $valor) {
                          echo "<li><form method='post'><input type='submit' name='configuracion' value='".$valor."' class='dropdown-item' /></form></li>";
                        }
                      } else {
                        echo "<li><span class='dropdown-item disabled'>No hay opciones de configuración</span></li>";
                      }
                    ?>
                  </ul>
                </div>
              </div>
        
              <div class="col-md-6 mb-4">
                <div class="dropdown d-grid">
                  <button class="btn btn-success btn-lg dropdown-toggle p-3" type="button" id="listadoDropdown" data-bs-toggle="dropdown" aria-expanded="false">Listado</button>
                  <ul class="dropdown-menu" aria-labelledby="listadoDropdown">
                    <?php
                      if(!empty($dropDownListado)){
                        foreach ($dropDownListado as $valor) {
                          echo "<li><form method='post'><input type='submit' name='listado' value='".$valor."' class='dropdown-item' /></form></li>";
                        }
                      } else {
                        echo "<li><span class='dropdown-item disabled'>No hay opciones de listado</span></li>";
                      }
                    ?>
                  </ul>
                </div>
              </div>
                
              <div class="col-md-6 mb-4">
                <form method='post' action=''>
                  <input type="submit" name="altas/bajas" value="Alta / Baja" class="btn btn-warning btn-lg w-100 p-3" />
                </form>
              </div>
              
              <div class="col-md-6 mb-4">
                <form method="post" action="">
                  <input type="submit" name="Cerrar" value="Cerrar Sesion" class="btn btn-danger btn-lg w-100 p-3" />
                </form>
              </div>
        
            </div>
          </div>
        </div>
      </div>
    </nav>
    
    <?php
      if ($cookie == "Listado Mensual") {
        $etapaSeleccionada = $_POST['Etapa'] ?? null;
        $claseSeleccionada = $_POST['Clase'] ?? null;
        echo '
        <div class="mb-3">
          <span class="badge bg-success">Asisti&oacute</span>
          <span class="badge bg-danger">No asisti&oacute (lectivo)</span>
          <span class="badge bg-secondary">No asisti&oacute (fin de semana y festivos)</span>
        </div>
        
        <div class="container-fluid mt-2 pt-3">
          <ul class="nav nav-pills d-flex justify-content-start gap-3">';
          
            echo '
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle btn btn-info text-white" href="#" id="etapasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Etapas</a>
              <ul class="dropdown-menu" aria-labelledby="etapasDropdown">
                <form method="post" class="p-0">';
                  foreach ($array as $id => $nombre) {
                    echo "<li><button type='submit' name='Etapa' value='$nombre' class='dropdown-item'>$nombre</button></li>";
                  }
                echo '</form>
              </ul>
            </li>';
            
            if (!empty($clases)) {
              echo '<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn btn-info text-white" href="#" id="clasesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Clases</a>
                <ul class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="clasesDropdown">
                  <form method="post" class="p-0">';
                    foreach ($clases as $id => $nombre) {
                      echo "<li><button type='submit' name='Clase' value='$nombre' class='dropdown-item'>$nombre</button></li>";
                    }
                  echo '</form>
                </ul>
              </li>';
            }
            
          echo '</ul>
        </div>';
      }
    ?>
    
    <div id="contenedor" class='table-responsive'>
			<?php
				if($cookie == "Listado Mensual"){
          echo "<h1>Listado Mensual</h1>";
					echo "<div id='tablas'>";
					echo $listado;
					echo "</div>";
				}elseif($cookie == "Resumen Mensual"){
          echo "<h1>Resumen Mensual</h1>";
					echo "<div id='tablas'>";
					echo $listado2;
					echo "</div>";
				}elseif($cookie == "Resumen Asistencias"){
          echo "<h1>Resumen Asistencias</h1>";
					echo "<div id='tablas'>";
					echo $listado3;
					echo "</div>";
				}
			?>
		</div>

	</body>
</html>