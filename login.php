<?php
	//v�tab ja kopeerib faili sisu
	require("../../config.php");
	
	//var_dump - n�itab k�ike, mis muutuja sees
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//MUUTUJAD
	$loginEmailError = "";
	$loginPasswordError = "";
	$firstNameError = "";
	$lastNameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$phoneNumberError = "";
	
	$signupEmail = "";
	$gender = "";
	
	if (isset ($_POST["loginEmail"]) ){
		if (empty ($_POST["loginEmail"]) ){
			$loginEmailError = "Palun sisesta e-post!";		
		}
	}
	
	if (isset ($_POST["loginPassword"]) ){ 
		if (empty ($_POST["loginPassword"]) ){ 
			$loginPasswordError = "Palun sisesta parool!";		
		}
	}
	
	if (isset ($_POST["firstName"]) ){
		if (empty ($_POST["firstName"]) ){
			$firstNameError = "See v�li on kohustuslik!";		
		} else {
			//The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
			if (!preg_match("/^[a-zA-Z ������-]*$/",$_POST["firstName"])) { 
				$firstNameError = "Pole nimi!"; 
			}
		}
	}
	
	if (isset ($_POST["lastName"]) ){
		if (empty ($_POST["lastName"]) ){
			$lastNameError = "See v�li on kohustuslik!";		
		} else {
			if (!preg_match("/^[a-zA-Z ������-]*$/",$_POST["lastName"])) { 
				$lastNameError = "Pole nimi!"; 
			}
		}
	}
	
	//kas e-post oli olemas
	if (isset ($_POST["signupEmail"]) ){ //kas keegi nuppu vajutas, kas signupEmail tekkis
		if (empty ($_POST["signupEmail"]) ){ //oli email, kuid see oli t�hi
			//echo "email oli t�hi";
			$signupEmailError = "See v�li on kohustuslik!";		
		} else {
			//email on �ige, salvestan v��rtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	if (isset ($_POST["signupPassword"]) ){ 
		if (empty ($_POST["signupPassword"]) ){ 
			$signupPasswordError = "See v�li on kohustuslik!";		
		} else {
			//tean, et oli parool ja see ei olnud t�hi
			if (strlen($_POST["signupPassword"]) < 8){ //strlen- stringi pikkus
				$signupPasswordError = "Parool peab olema v�hemalt 8 t�hem�rki pikk!";
			}
		}
	}
	
	if (isset ($_POST["gender"]) ){ 
		if (empty ($_POST["gender"]) ){ 
			$genderError = "";
		} else {
			$gender = $_POST["gender"];
		}
	}
	
	if (isset ($_POST["phoneNumber"]) ){
		if (empty ($_POST["phoneNumber"]) ){ 
			$phoneNumberError = "";
		} else {
			if (ctype_digit($_POST["phoneNumber"])){ //ctype_digit- checks if all of the characters in the Provided string, text, are numerical.
				$phoneNumberError = "";		
			} else {
				$phoneNumberError = "Ainult numbrid on lubatud!";
			}
		}
	}
	
	//Kus tean, et �htegi viga ei olnud ja saan kasutaja andmed salvestada.
	if (isset ($_POST["signupPassword"])
		&& isset($_POST["signupEmail"])
		&& empty($signupEmailError) 
		&& empty($signupPasswordError) ){
			
		echo "Salvestan...<br>";
		echo "email ".$signupEmail."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]); //hash(algoritm,parool)
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "r�si".$password."<br>";
		
		//echo $serverPassword;
		
		$database = "if16_marikraav";
		$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
		
		//k�sk
		$stmt = $mysqli->prepare("INSERT INTO user_sample(email, password) VALUES(?,?)"); //stmt- statement, prepare'i sisse mysqli lause
		//INSERT jms ei pea suurega olema, aga lihtsustab arusaamist, kus on sqli pool ja kus see, mis sina kirjutasid
		echo $mysqli->error; //n�itab kui viga andmebaasi sisestamisel, muidu ei n�ita midagi
		
		//asendan k�sim�rgii v��rtustega
		//iga muutuja kohta 1 t�ht, mis t��pi muutuja on
		//s-string (nt ka date ja boolean on string) (k�ik muud arvud va 2 alumist)
		//i-integer (k�ik t�isarvud)
		//d-double/float (k�ik komakohaga arvud)
		$stmt->bind_param("ss", $signupEmail, $password); //password- r�si, bind_param asendab muutujaid
		
		if($stmt->execute()) { //if'iga vaatame, kas salvestamine andmebaasi �nnestus
			echo "salvestamine �nnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sisselogimise lehek�lg</title>
	</head>
	<body>
		<h1>Logi sisse</h1>	
		
		<form method="POST">
			<input name="loginEmail" type="email" placeholder="E-post"> <?php echo $loginEmailError; ?>
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool"> <?php echo $loginPasswordError; ?>
			<br><br>
			
			<input type="submit" value = "Logi sisse">
		</form>
		
		<h1>Loo kasutaja</h1>	
		
		<form method="POST">
			<label>Eesnimi</label>
			<br>
			<input name="firstName" type="text"> <?php echo $firstNameError; ?>
			<br><br>
			
			<label>Perekonnanimi</label>
			<br>
			<input name="lastName" type="text"> <?php echo $lastNameError; ?>
			<br><br>
		
			<label>E-Post</label>
			<br>
			<input name="signupEmail" type="email" value= "<?=$signupEmail;?>" > <?php echo $signupEmailError; ?> <!--j�tab signupEmaili meelde v�ljale-->
			<br><br>
			
			<label>Parool</label>
			<br>
			<input name="signupPassword" type="password"> <?php echo $signupPasswordError; ?>
			<br><br>
			
			
			<label>Sugu:</label> <!--J�tan vabatahtlikuks v�ljaks-->
			<?php if($gender == "female") { ?>
			<input type="radio" name="gender" value="female" checked>Naine
			<?php } else { ?>
			<input type="radio" name="gender" value="female">Naine
			<?php } ?>
			
			<?php if($gender == "male") { ?>
			<input type="radio" name="gender" value="male" checked>Mees
			<?php } else { ?>
			<input type="radio" name="gender" value="male">Mees
			<?php } ?>
			<br><br>
			
			<label>Telefoni number</label> <!--J�tan vabatahtlikuks v�ljaks-->
			<br>
			<input name="phoneNumber" type="text"> <?php echo $phoneNumberError; ?>
			<br><br>
			
			<input type="submit" value = "Loo kasutaja">
		</form>
		 <!--Mvp ideeks on �ldine foorum, kuhu saab postitada erinevaid teemasid ning kommenteerida olemasolevaid. Vastates teiste kasutajate teemadele saab koguda punkte ning neid kasutada oma teemadele "high priority" m�rkimisel v�i toodete/autasude lunastamisel. "High priority" eest saab oma teema t�sta teiste seast esile/ettepoole ning sellele motiveerib rohkem vastama, kuna v�imalus on teenida rohkem punkte. V�ga originaalset ideed hetkel ei ole, aga ehk tuleb teostamise k�igus ning v�ib-olla idee ka muutub natukene.-->
	</body>
</html>