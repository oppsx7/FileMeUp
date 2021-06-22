<?php

require_once "config.php";
require_once "session.php";

include('./html/index.html');
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
					$uuid = $row['id'];
					$_SESSION['superhero'] = "batman";
					header("location: user_uploads.php");
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
}

?>