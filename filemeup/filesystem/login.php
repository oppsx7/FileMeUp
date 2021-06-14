<?php

require_once "config.php";
require_once "session.php";


$error = '';
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	
	$username = trim($_POST['user-name']);
	$password = trim($_POST['password']);
	
	if(empty($username)) {
		$error .= '<p class = "error"> Please enter username.</p>';
	}
	
	if(empty($password)) {
		$error .= '<p class = "error"> Please enter password.</p>';
	}
	$queryString = "SELECT * FROM users WHERE username = :username AND password = :password";
	if(empty($error)) {
		if($query = $connection->prepare($queryString)) {
			$query->execute(["username" => $username, "password" => $password]);
			$row = $query->fetch();
			if($row) {
				if($password === $row['password']) {
					$_SESSION['userid'] = $row['id'];
					$_SESSION['user'] = $row;
					
					header("location: index.php");
					exit;
				} else {
					$error .= '<p class = "error"> The password is not valid.</p>';
				} 
			} else {
				$error .= '<p class = "error"> No user exist with that username. </p>';
			}
		}
	}
	echo $error;
	//mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./login_style.css">
	<link rel="stylesheet" href="./style.css">
	<script defer src="./sendRequestUtility.js"></script>
    <script defer src="./login.js"></script>
	<title>FileMeUp</title>
</head>
	<div class="wrapper fadeInDown">
	  <div id="formContent">
		<!-- Tabs Titles -->
		<h2 class="active"> Sign In </h2>
		<h2 class="inactive underlineHover"><a href = "./registration.html">Sign Up </a></h2> 

		<!-- Icon -->
		<div class="fadeIn first">
		  <img src="http://w16ref.w3c.fmi.uni-sofia.bg/img/logo.png" id="icon" alt="User Icon" />
		</div>
		
		<!-- Login Form -->
		<form class = "login" action = "" method = "post">
			<input type="text" id="username" class="fadeIn second" name="user-name" placeholder="Username">
			<input type="text" id="password" class="fadeIn third" name="password" placeholder="Password">
			<input name="submit" type="submit" id = "login" class="fadeIn fourth" value="Log In">
			<a id = "errors"></a>
		</form>

		<!-- Remind Passowrd -->
		<div id="formFooter">
		  <a class="underlineHover" href="#">Forgot Password?</a>
		</div>
	  </div>
	</div>
</html>