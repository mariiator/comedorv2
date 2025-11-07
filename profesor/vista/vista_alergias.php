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
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<div id="contenidoAlergias">
				<div class="form-floating mb-3" id="alergias">
					<textarea id="floatingInput" name="alergias" placeholder="Introduzca aqui tus alergias/intolerancias:"  class="form-control"><?php echo $alergias;?></textarea>
					<label for="floatingInput">Introduzca aqui tus alergias/intolerancias:</label>
				</div>
				<script type="text/javascript">
					const textarea = document.getElementById('floatingInput');
					textarea.addEventListener('input', function() {
						autoResizeTextarea(this);
					});

					function autoResizeTextarea(textarea) {
						textarea.style.height = 'auto';
						textarea.style.height = textarea.scrollHeight + 'px';
					}
					autoResizeTextarea(textarea);

					function enviarDatos(){
						const elemento = document.getElementById('floatingInput').value;
						var xhr = new XMLHttpRequest();
						xhr.open('POST', '/profesor/controlador/controlador_enviarAlergias.php', true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
						xhr.onload = function() {
						    if (xhr.status === 200) {
						        //console.log(xhr.responseText);
						    } else {
						        //console.error('Error en la solicitud. Estado:', xhr.status);
						    }
						};
						xhr.send('alergias=' + encodeURIComponent(elemento));
					}
			</script>
			<input type="button" id="boton" class="btn btn-primary btn-lg" value="Guardar" onclick="enviarDatos()" />
			<input type="submit" value="Volver" name="Volver" class="btn btn-success btn-lg" />
			</div>
		</form>
	</div>
</body>
</html>