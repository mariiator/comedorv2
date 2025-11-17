<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link href="/comedorv2/admin/css/altabaja - copia.css" rel="stylesheet">
		<link rel="icon" href="/comedorv2/css/corazon_maristas.jpg" type="image/x-icon">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	</head>
	<style>
		.table-container{
			margin: 10px;
      overflow-x: auto;
		}
		table, th, td {
    	border: 1px solid;
		}
		nav{
			background-image: linear-gradient(180deg, #CD3997 0%, #92065F 100%);
		}
		#imagen{
			height: 60px;
			margin: 0px 0px 0px 10px;
		}
		body{
	    background-image: url('/css/fondo_administrador.jpg');
	    background-size: cover;
	    color: white;
	    background-position: center center;
	    background-repeat: no-repeat;
	    background-attachment: fixed;
	    background-color: blue;
		}
	</style>
	<body>
    <nav class="navbar navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="https://comedor.maristaschamberi.com/comedorv2/admin/controlador/mejora/controlador_administracion%20-%20copia.php">
          <img id="imagen" src="/comedorv2/css/logo_chamberi_def.jpg" alt="Logo">
        </a>

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

    <div class="container text-center mt-4">
      <form method="post" action="">
        <input type="submit" name="nuevo" value="Nueva entrada" class="btn btn-info btn-lg px-5 py-3" />
      </form>
    </div>

    <div class="table-container">
      <form method="post">
        <?php
          echo $tabla;
        ?>
      </form>
    </div>
  </body>
</html>