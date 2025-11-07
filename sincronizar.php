<?php

$cursoACT = 2025; // Curso 24-25

$DBIhost = "intranet.maristaschamberi.com";
$DBIuser = "admbup";
$DBIpass = "pjbelfer";
$DBIname = "mi010";

$DBChost = "localhost";
$DBCuser = "pablo";
$DBCpass = "Torci2024@";
$DBCname = "comedor";


try{

  $DBIcon = new PDO("mysql:host=$DBIhost;dbname=$DBIname",$DBIuser,$DBIpass);
  $DBIcon->exec("set names utf8");
  $DBIcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  }catch(PDOException $ex){
  
  die($ex->getMessage());
}

try{

  $DBCcon = new PDO("mysql:host=$DBChost;dbname=$DBCname",$DBCuser,$DBCpass);
  $DBCcon->exec("set names utf8");
  $DBCcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  }catch(PDOException $ex){
  
  die($ex->getMessage());
}

// Listar todos los alumnos del curso actual

$query = "SELECT
				persona.nombre,
				persona.apellido1,
				persona.apellido2,
				persona.id as idalumno,
				clase.id AS idclase,
				clase.nombre as clase,
				etapa.id as idetapa,
				etapa.nombre as etapa
				FROM
				persona
				INNER JOIN cambio_clase ON cambio_clase.alumno_id = persona.id
				INNER JOIN clase ON cambio_clase.clase_id = clase.id
				INNER JOIN etapa ON clase.etapa_id = etapa.id 
				WHERE cambio_clase.curso = '$cursoACT'";
$stmt = $DBIcon->prepare($query);
echo $query . "<br>";
$stmt->execute(); 


while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	$idAlumno = $row['idalumno'];
	$idEtapa = $row['idetapa'];
	$idClase = $row['idclase'];
	// Comprobar si el alumno existe en la base de datos del comedor
	$query2 = "SELECT * FROM Persona WHERE id=$idAlumno";
	$stmt2 = $DBCcon->prepare($query2);
	echo $query2 . "<br>";
	$stmt2->execute();
	
	if (!($stmt2->fetch(PDO::FETCH_ASSOC))) {
		// El alumno no existe. Comprobar si existen la etapa y la clase
	    $query3 = "SELECT * FROM Etapa WHERE id = $idEtapa";
		$stmt3 = $DBCcon->prepare($query3);
		echo $query3 . "<br>";
		$stmt3->execute();
		
		if (!($stmt3->fetch(PDO::FETCH_ASSOC))) {
			// Añadimos la etapa 
			$nombreEtapa = $row['etapa'];
			$queryI = "INSERT INTO Etapa VALUES ('$idEtapa','$nombreEtapa')";
			$stmtI = $DBCcon->prepare($queryI);
			echo $queryI . "<br>";
			$stmtI->execute();
			
		}
		$query4 = "SELECT * FROM Clase WHERE id = $idClase";
		$stmt4 = $DBCcon->prepare($query4);
		$stmt4->execute();
		echo $query4 . "<br>";
		if (!($stmt4->fetch(PDO::FETCH_ASSOC))) {
			// Añadimos la clase 
			$nombreClase = $row['clase'];
			$queryI = "INSERT INTO Clase VALUES ('$idClase','$idEtapa','$nombreClase')";
			$stmtI = $DBCcon->prepare($queryI);
			echo $queryI . "<br>";
			$stmtI->execute();
			
		}
		// Ahora hay que añadir al alumno
		$nombreAlumno = $row['nombre'];
		$apellido1Alumno = str_replace("'","\'",$row['apellido1']);
		$apellido2Alumno = str_replace("'","\'",$row['apellido2']);
		$queryI = "INSERT INTO Persona VALUES ('$idAlumno','$idClase','$nombreAlumno','$apellido1Alumno','$apellido2Alumno')";
		$stmtI = $DBCcon->prepare($queryI);
		echo $queryI . "<br>";
		$stmtI->execute();
		
		// Hay que descargar la foto
		
		$queryF = "SELECT foto,id FROM foto WHERE alumno_id='$idAlumno' ORDER BY anyo ASC LIMIT 1";
		$stmtF = $DBIcon->prepare($queryF);
		echo $queryF . "<br>";
		$stmtF->execute();
		if ($rowF = $stmtF->fetch(PDO::FETCH_ASSOC)) {			
			$foto = $rowF['foto'];
			$idfoto = $rowF['id'];
			$queryI = "INSERT INTO Foto VALUES ('$idfoto','$idAlumno','$foto')";
			$stmtI = $DBCcon->prepare($queryI);
			echo $queryI . "<br>";
			$stmtI->execute();
		}
		
		// Finalmente configurar inicialmente la tabla de asistencias vacía para los meses existentes.
		// Hay que ver que meses ya se han creado en el sistema para insertarlos en este usuario
		
		$query5 = "SELECT DISTINCT Asistencia.anyo, Asistencia.mes FROM Asistencia";
		$stmt5 = $DBCcon->prepare($query5);
		echo $query5 . "<br>";
		$stmt5->execute();
		
		if ($stmt5->rowCount() == 0) {
			$queryI = "INSERT INTO Asistencia VALUES (default,'$idAlumno','$cursoACT','8','0','0','0','0','0')";
			$stmtI = $DBCcon->prepare($queryI);
			echo $queryI . "<br>";
			$stmtI->execute();
		}
		
		while ($row5 = $stmt5->fetch(PDO::FETCH_ASSOC)) {
			$anyo = $row5['anyo'];
			$mes = $row5['mes'];
			$queryI = "INSERT INTO Asistencia VALUES (default,'$idAlumno','$anyo','$mes','0','0','0','0','0')";
			$stmtI = $DBCcon->prepare($queryI);
			echo $queryI . "<br>";
			$stmtI->execute();
		}
		
	}
	else {

		
		// El alumno existe, pero hay que actualizar la foto o la clase.
		
		$queryU = "UPDATE Persona SET clase_id='$idClase' WHERE id='$idAlumno'";
		$stmtU = $DBCcon->prepare($queryU);
		echo $queryU . "<br>";
		$stmtU->execute();
		
		$queryF = "SELECT foto,id FROM foto WHERE alumno_id='$idAlumno' ORDER BY anyo ASC LIMIT 1";
		$stmtF = $DBIcon->prepare($queryF);
		//echo $queryF . "<br>";
		$stmtF->execute();
		if ($rowF = $stmtF->fetch(PDO::FETCH_ASSOC)) {			
			$foto = $rowF['foto'];
			$idfoto = $rowF['id'];
			
			// Hay que ver si es INSERT O UPDATE
			$queryQ = "SELECT * FROM Foto WHERE id='$idfoto'";
			$stmtQ = $DBCcon->prepare($queryQ);
			$stmtQ->execute();
			
			if ($rowX = $stmtQ->fetch(PDO::FETCH_ASSOC)) {
				$queryU = "UPDATE Foto SET foto='$foto' WHERE id='$idfoto'";
				$stmtU = $DBCcon->prepare($queryU);
				echo "ACtualizar foto<br>";
				$stmtU->execute();
			}
			else {			
				$queryI = "INSERT INTO Foto VALUES ('$idfoto','$idAlumno','$foto')";
				$stmtI = $DBCcon->prepare($queryI);
				echo "Insertar foto<br>";
				$stmtI->execute();
			}
		}
		
		
	}
		
	
}



?>