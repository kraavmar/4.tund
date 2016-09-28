<?php
	//võtab ja kopeerib faili sisu
	require("../../config.php");
	require("functions.php"); //võid ka configi panna functionsisse, vahet pole
	
	//var_dump - näitab kõike, mis muutuja sees
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
			$firstNameError = "See väli on kohustuslik!";		
		} else {
			//The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["firstName"])) { 
				$firstNameError = "Pole nimi!"; 
			}
		}
	}
	
	if (isset ($_POST["lastName"]) ){
		if (empty ($_POST["lastName"]) ){
			$lastNameError = "See väli on kohustuslik!";		
		} else {
			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["lastName"])) { 
				$lastNameError = "Pole nimi!"; 
			}
		}
	}
	
	//kas e-post oli olemas
	if (isset ($_POST["signupEmail"]) ){ //kas keegi nuppu vajutas, kas signupEmail tekkis
		if (empty ($_POST["signupEmail"]) ){ //oli email, kuid see oli tühi
			//echo "email oli tühi";
			$signupEmailError = "See väli on kohustuslik!";		
		} else {
			//email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	if (isset ($_POST["signupPassword"]) ){ 
		if (empty ($_POST["signupPassword"]) ){ 
			$signupPasswordError = "See väli on kohustuslik!";		
		} else {
			//tean, et oli parool ja see ei olnud tühi
			if (strlen($_POST["signupPassword"]) < 8){ //strlen- stringi pikkus
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk!";
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
	
	//Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada.
	if (isset ($_POST["signupPassword"])
		&& isset($_POST["signupEmail"])
		&& empty($signupEmailError) 
		&& empty($signupPasswordError) ){
			
		echo "Salvestan...<br>";
		echo "email ".$signupEmail."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]); //hash(algoritm,parool)
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi".$password."<br>";
		
		//echo $serverPassword;
		
		signup($signupEmail, $password);
		
	}
	
	//Kontrollin, et kasutaja täitis välja ja võib sisse logida 
	if (isset ($_POST["loginEmail"]) && 
		isset ($_POST["loginPassword"]) && 
		!empty ($_POST["loginEmail"]) && 
		!empty ($_POST["loginPassword"])
		){
			//login sisse
			login($_POST["loginEmail"], $_POST["loginPassword"]);
		}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sisselogimise lehekülg</title>
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
			<input name="signupEmail" type="email" value= "<?=$signupEmail;?>" > <?php echo $signupEmailError; ?> <!--jätab signupEmaili meelde väljale-->
			<br><br>
			
			<label>Parool</label>
			<br>
			<input name="signupPassword" type="password"> <?php echo $signupPasswordError; ?>
			<br><br>
			
			
			<label>Sugu:</label> <!--Jätan vabatahtlikuks väljaks-->
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
			
			<label>Telefoni number</label> <!--Jätan vabatahtlikuks väljaks-->
			<br>
			<input name="phoneNumber" type="text"> <?php echo $phoneNumberError; ?>
			<br><br>
			
			<input type="submit" value = "Loo kasutaja">
		</form>
		 <!--Mvp ideeks on üldine foorum, kuhu saab postitada erinevaid teemasid ning kommenteerida olemasolevaid. Vastates teiste kasutajate teemadele saab koguda punkte ning neid kasutada oma teemadele "high priority" märkimisel või toodete/autasude lunastamisel. "High priority" eest saab oma teema tõsta teiste seast esile/ettepoole ning sellele motiveerib rohkem vastama, kuna võimalus on teenida rohkem punkte. Väga originaalset ideed hetkel ei ole, aga ehk tuleb teostamise käigus ning võib-olla idee ka muutub natukene.-->
	</body>
</html>