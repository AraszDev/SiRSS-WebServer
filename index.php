<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width", initial-scale=1> <!-- Polskie znaki i skalowanie-->
		<title>SiRSS WebServer</title>
		<link rel="stylesheet" href="w3.css">
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<div class="w3-topnav w3-green w3-center w3-card-4">
			<a href="index.php">Interfejs mastera</a>
		</div>
		<div id="container">
		
		<?php
		
		if (isset($_POST['s1'])) {
			$num = 0;
		}
		if (isset($_POST['s2'])) {
			$num = 1;
		}
		if (isset($_POST['s3'])) {
			$num = 2;
		}
		
		if (isset($_GET['analogSlider'])) //gdy wyslano form
		{
			$analog = $_GET['analogSlider'];//utrzymanie wartosci slidera w formularzu
			$przeliczone = 0;
			for ($i = 0 ; $i < 8 ; $i++) {
				$zapytanie = 'dwa' . $i;
				if (isset($_GET[$zapytanie])) {
					$ch[$i] = $_GET[$zapytanie]; //utrzymanie stanu checkboxow w formularzu
					$przeliczone = $przeliczone + pow(2,$i); //do wyslania do DB
				}
			}
			
			//DB config
			$servername = "localhost";
			$username = "root";
			$password = "ziomeczek";
			$dbname = "interfejs";
			$conn = new mysqli($servername, $username, $password, $dbname);
			
			if ($conn->connect_error) {
				echo "Connection failed";
			}
			else {
				switch($_GET['slaveNum']) {
					case 0:
					$sql = "SELECT * FROM slavestany
						WHERE id=12";		
					break;
					case 1:
					$sql = "SELECT * FROM slavestany
						WHERE id=27";
					break;
					case 2:
					$sql = "SELECT * FROM slavestany
						WHERE id=255";
					break;
				}
				
				$result = $conn->query($sql);
				
				if ($result->num_rows > 0) {
					//cos juz istnieje w bazie danych - UPDATE
					switch($_GET['slaveNum']) {
						case 0:
						$sql = "UPDATE slavestany SET di=$przeliczone, ai=$_GET[analogSlider] WHERE id = 12";
						break;
						case 1:
						$sql = "UPDATE slavestany SET di=$przeliczone, ai=$_GET[analogSlider] WHERE id = 27";
						break;
						case 2:
						$sql = "UPDATE slavestany SET di=$przeliczone, ai=$_GET[analogSlider] WHERE id = 255";
						break;
					}
					
					if ($conn->query($sql) === TRUE) {
						echo "Uaktualniono rekordy";
					}
					else {
						echo "Blad: " . $sql . "<br>" . $conn->error;
					}
				}
				else {
					//nic nie ma w bazie danych - INSERT INTO
					switch($_GET['slaveNum']) 
					{
						case 0:
						//slave 1 record
						$sql = "INSERT INTO slavestany (id, di, ai)
						VALUES (12, $przeliczone, $_GET[analogSlider])";
						break;
						case 1:
						//slave 2 record
						$sql = "INSERT INTO slavestany (id, di, ai)
						VALUES (27, $przeliczone, $_GET[analogSlider])";
						break;
						case 2:
						//broadcast record
						$sql = "INSERT INTO slavestany (id, di, ai)
						VALUES (255, $przeliczone, $_GET[analogSlider])";
						break;
					}
					//check for error
					if ($conn->multi_query($sql) === TRUE) {
						echo "Stworzono nowe rekordy";
					}
					else {
						echo "Blad: " . $sql . "<br>" . $conn->error;
					}
				}
			}
			$num = $_GET['slaveNum'];
		}
		
			$servername = "localhost";
			$username = "root";
			$password = "ziomeczek";
			$dbname = "interfejs";
			$conn = new mysqli($servername, $username, $password, $dbname);
			$result = $conn->query("SELECT id, di, ai FROM slavestany WHERE id BETWEEN 0 and 255;");
		 
			// kolejne rzedy do tablicy
			$slavearray = array();
			while($row = mysqli_fetch_assoc($result)) 
			{
				$slavearray[] = $row;
			}
			for ($i = 0 ; $i < 3 ; $i++) {
				if ($i != 2) {
					$format = "Slave %d";
					$final = sprintf($format, $i+1);
					$slavearray[$i]["name"] = $final;
				}
				else 
					$slavearray[$i]["name"] = "Broadcast";
			}
			if (isset($num)) {
				$binstring = decbin($slavearray[$num]["di"]);
				$index = 0;
				for ($i=strlen($binstring)-1 ; $i >= 0 ; $i--) {
					if ($binstring[$i] == 1)
						$ch[$index] = 1;
					$index++;
				}
			}
			
			// zamknij resultset
			$result->close();
		 
			// odlacz od db
			$conn->close();
		
		?>
		
			<div class="w3-card-2 w3-margin-32  w3-border" >
				<header class="w3-container w3-center w3-light-green">
					<h3>Formularz</h3>
				</header>
				<div class="w3-container w3-center">
					<h3 class="w3-text-theme" id="tytul"><?php if (isset($num)) echo $slavearray[$num]["name"]; else echo "Nie wybrano slave'a"; ?><h3>
				</div>

				<div class="w3-border-top w3-container ">
					<div class="w3-row">
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" id="mainForm" name="formularz" method="get">
					<div class="w3-col l6">
					<p class="w3-center">Digital I/O</p>
					<div id="bity">
					
					
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa0" <?php if (isset($ch[0])) echo "checked";?> onclick="checkClick(1);" />
					<div class="w3-checkmark"></div> 2<sup>0</sup>
					</label>
					<label class="w3-checkbox" >
					<input type="checkbox" name="dwa1" <?php if (isset($ch[1])) echo "checked";?> onclick="checkClick(2);" />
					<div class="w3-checkmark"></div> 2<sup>1</sup>
					</label>
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa2" <?php if (isset($ch[2])) echo "checked";?> onclick="checkClick(3);" />
					<div class="w3-checkmark"></div> 2<sup>2</sup>
					</label>
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa3" <?php if (isset($ch[3])) echo "checked";?> onclick="checkClick(4);" />
					<div class="w3-checkmark"></div> 2<sup>3</sup>
					</label>
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa4" <?php if (isset($ch[4])) echo "checked";?> onclick="checkClick(5);" />
					<div class="w3-checkmark"></div> 2<sup>4</sup>
					</label>
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa5" <?php if (isset($ch[5])) echo "checked";?> onclick="checkClick(6);" />
					<div class="w3-checkmark"></div> 2<sup>5</sup>
					</label>
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa6" <?php if (isset($ch[6])) echo "checked";?> onclick="checkClick(7);" />
					<div class="w3-checkmark"></div> 2<sup>6</sup>
					</label>
					<label class="w3-checkbox">
					<input type="checkbox" name="dwa7" <?php if (isset($ch[7])) echo "checked";?> onclick="checkClick(8);" />
					<div class="w3-checkmark"></div> 2<sup>7</sup>
					</label>
					</div>
					</div>
					<div class="w3-col l6 w3-center">
					<p>Analog I/O</p>
					<input type="range" name="analogSlider" value="<?php if (isset($num)) echo $slavearray[$num]["ai"]; ?>"/>
					</div>
					
					</div>
					
					<!-- Poza responsywnymi kolumnami -->
					<div class="w3-center w3-margin-top w3-margin-bottom">
						<input type="number" name="slaveNum" value="<?php if (isset($num)) echo $num;?>" style="visibility: hidden;" />
						<br>
						<input type="submit" class="w3-btn w3-light-green" value="Prześlij" <?php if (!isset($num)) echo "disabled"; ?>/>
						</form>
						<br>
						<br>
						<div class="rameczka ">
							<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
							<input type="submit" class="w3-btn w3-light-green button" id="slave1" name="s1" value="Slave 1" />
							</form>
						</div>
						<div class="rameczka ">
							<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
							<input type="submit" class="w3-btn w3-light-green button" id="slave2" name="s2" value="Slave 2" />
							</form>
						</div>
						<div class="rameczka ">
							<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
							<input type="submit" class="w3-btn w3-light-green button" id="slave3" name="s3" value="Broadcast mode" />
							</form>
						</div>
					</div>
					
					
						

				</div>
				
					
				
			</div>
		</div>
		
		
		
		<div id="footer" class="w3-green">
			<p class="w3-center w3-small w3-margin-2">Wykonanie: Rafał Araszkiewicz, Przemysław Nikratowicz, Paulina Sadowska</p>
		</div>
	
	</body>
</html>