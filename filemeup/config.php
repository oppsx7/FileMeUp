<?php
	$host = "localhost";
	$dbname = "filemeup";
	$username = "root";
	$password = "";

	$dsn = "mysql:host=$host;dbname=$dbname";

	$connection = new PDO($dsn, $username, $password);
?>