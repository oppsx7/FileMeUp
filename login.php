<?php

require_once "config.php";
require_once "session.php";

$username = '';
$password = '';
$error = '';
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	
	$username = trim($_POST['user-name']);
	$password = trim($_POST['password']);
	
	if(empty($password)) {
		$error = '<p class = "error"> Please enter password.</p>';
	}
	if(empty($username)) {
		$error = '<p class = "error"> Please enter username.</p>';
	}
	$queryString = "SELECT * FROM users WHERE username = :username";
	if(empty($error)) {
		if($query = $connection->prepare($queryString)) {
			$query->execute(["username" => $username]);
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
				$username = "";
				$error .= '<p class = "error"> This username is not valid </p>';
			}
		}
	}
	//mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./styles/login_style.css">
	<title>FileMeUp</title>
</head>
	<div class="wrapper fadeInDown">
	  <div id="formContent">
		<!-- Tabs Titles -->
		<h2 class="active"> Sign In </h2>
		<h2 class="inactive underlineHover"><a href = "./registration.php">Sign Up </a></h2> 

		<!-- Icon -->
		<div class="fadeIn first">
		  <img src="http://w16ref.w3c.fmi.uni-sofia.bg/img/logo.png" id="icon" alt="User Icon" />
		</div>
		
		<!-- Login Form -->
		<form class = "login" id = "login" action = "" method = "post">
			<input type="text" id="username" class="fadeIn second" name="user-name" placeholder="Username" value= <?php echo $username; ?>>
			<input type="password" id="password" class="fadeIn third" name="password" placeholder="Password">
			<?php echo $error ?>
			<input name="submit" type="submit" id = "login" class="fadeIn fourth" value="Log In">
			<a style="word-wrap: break-word;"></a>
		</form>

		<!-- Remind Passowrd -->
		<div id="formFooter">
			<a class="underlineHover">@Copyrights: Valio & Toshko</a>
		</div>
	  </div>
	</div>
</html>