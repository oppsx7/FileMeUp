<?php
    require_once "session.php";
    require_once "config.php";

    $error = '';
    $success = '';
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $faculty = trim($_POST['faculty_number']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST["confirm_password"]);
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $queryString = "SELECT * FROM users WHERE username = :username";
        if($query = $connection->prepare($queryString)) {
            $error = '';

            $query->execute(["username" => $username]);

           //$query->store_result();
            $row = $query->fetch();
            if($row) {
                $error .= '<p class="error"> This username is already registered!</p>';
            } else {
                // Validate password
                if(strlen($password) < 6) {
                    $error .= '<p class = "error">Password must have at least 6 characters. </p>';
                }
                if(empty($confirmPassword)) {
                    $error .= '<p class = "error">Please enter confirm password.</p>';
                } else {
                    if(empty($error) && ($password != $confirmPassword)) {
                        $error .= '<p class = "error"> Password did not match. </p>';
                    }
                }
                if(empty($error)) {
                    $insertQuery = $connection->prepare("INSERT into users(username, email,faculty_number, password) VALUES(:username, :email,:faculty_number, :password);");
                    $result = $insertQuery->execute(["username" => $username , "email" => $email,"faculty_number" => $faculty, "password" => $password]);
                    if($result) {
                        $error .= '<p class = "success"> Your registration was successful!</p>';
                        header("location: index.php");
                    } else {
                        $error .= '<p class = "error">Something went wrong!</p>';
                    }
                }
            }
        }
        //$query->close();
        //$insertQuery->close();
    }
?>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./login_style.css">
	<title>FileMeUp</title>
</head>
	<div class="wrapper fadeInDown">
	  <div id="formContent">
		<!-- Tabs Titles -->
		<h2 class="inactive underlineHover"><a href = "./login.php"> Sign In </a></h2>
		<h2 class="active">Sign Up </h2>

		<!-- Icon -->
		<div class="fadeIn first">
		  <img src="http://w16ref.w3c.fmi.uni-sofia.bg/img/logo.png" id="icon" alt="User Icon" />
		</div>
		
		<!-- Login Form -->
        <?php echo $success ?>
        
		<form class = "register" action = "" method = "post">
			<input type="text" id="username" class="fadeIn first" name="username" placeholder="Username">
			<input type = "email" id = "email" name="email" class = "fadeIn second" placeholder="Email">
            <input type = "text" id = "faculty_number" name = "faculty_number" class = "fadeIn second" placeholder="Faculty number">
			<input type="password" id="password" class="fadeIn third" name="password" placeholder="Password">
            <input type="password" id="confirm_password" class="fadeIn third" name="confirm_password" placeholder="Confirm Password">
            <?php echo $error ?>
			<input type="submit" name = "submit" id = "submit" class="fadeIn fourth" value="Register">
		</form>

		<!-- Remind Passowrd -->
		<div id="formFooter">
		  <a class="underlineHover">@Copyrights: Valio & Toshko</a>
		</div>
	  </div>
	</div>
</html>