<html>
<head></head>
<body>
<?php



	//db conf
	$dbserwer = "localhost";
	$dbuser = "root";
	$dbpassword = "ziomeczek";
	$dbnazwa = "interfejs";
 
	$conn = new mysqli($dbserwer, $dbuser, $dbpassword, $dbnazwa);
	
	// proba polaczenia
	if ($conn->connect_error) 
	{
		echo "Connection failed";
		exit();
	}
	
	if (!empty($_POST['slaves'])) //odbior od mastera
	{
		$odebraneDane = json_decode($_POST['slaves'], true); //gdy true to konwertuje $odebraneDane do associative array
		
		//echo(print_r($odebraneDane,true));
		
		foreach ($odebraneDane as $value ) {
			//echo(print_r($value,true));
			$di = intval($value["di"]);
			$ai = intval($value["ai"]);
			$id = intval($value["id"]);

			$sql = "UPDATE slavestany SET di=$di, ai=$ai WHERE id=$id";
			$result = $conn->query($sql);
			if ($result === FALSE) {
				echo "Connection failed";
				exit();
			}
		}
	}
	else //wysylka do mastera
	{
		// zwracam oba slave'y z db
		$result = $conn->query("SELECT id, di, ai FROM slavestany WHERE id BETWEEN 0 and 255;");
	 
		// kolejne rzedy do tablicy
		$rows = array();
		while($row = mysqli_fetch_assoc($result)) 
		{
			$rows[] = $row;
		}
		
		// zamknij resultset
		$result->close();
	 
		// odlacz od db
		$conn->close();
		
		// zwracam json
		print(json_encode($rows, JSON_NUMERIC_CHECK)); //numeric check - zadnych stringow tylko inty
	}
	
?>
</body>
</html>