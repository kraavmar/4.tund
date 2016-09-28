<?php
	//functions.php
	
	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid
	session_start(); //sessioon töötab, isegi kui kasutaja pole veel sisse logind
	
	//********************
	//****** SIGNUP ******
	//********************
	//GLOBALSI SEES kõik GET, POST, COOKIE ja FILES andmed, näeme kõiki muutujaid, mis pole funktsioonides
	//var_dump($GLOBALS);
	//$GLOBALS is a PHP super global variable which is used to access global variables from anywhere in the PHP script (also from within functions or methods).
	
	$database = "if16_marikraav"; //database väljapoole nähtav
	function signup ($firstName, $lastName, $email, $password){
		//selle sees muutujad pole väljapoole nähtavad
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO user_sample(firstName, lastName, email, password) VALUES(?,?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ssss", $firstName, $lastName, $email, $password); //$signupEmail emailiks lihtsalt
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function login ($email, $password){
		
		$error = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
			SELECT id, email, password, created
			FROM user_sample
			WHERE email = ?
		");
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email); //s-string
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created); //Db-database
		$stmt->execute(); //päring läheb läbi executiga, isegi kui ühtegi vastust ei tule
		
		if($stmt->fetch()) { //fetch küsin rea andmeid
			//oli rida
			//võrdlen paroole 
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				echo "kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDb;
				
				//suunaks uuele lehele
				header("Location: data.php");
				
			} else {
				$error = "parool vale";
			}
		} else {
			//ei olnud
			$error = "sellise emailiga ".$email." kasutajat ei olnud.";
		}
		
		return $error;
	}
	
	
	
	/*function sum($x, $y){
		return $x + $y;
	}
	
	echo sum(34,10);
	echo "<br>";
	//võid hoida meeles ka muutujas $answer = sum(10,15);
	//echo $answer;
	
	//arvude liitmine +, stringide liitmine .
	function hello ($firstName, $lastName) {
		return "Tere tulemast ".$firstName." ".$lastName."!";
	}
	
	echo hello("Mariann", "Kraav");*/
?>