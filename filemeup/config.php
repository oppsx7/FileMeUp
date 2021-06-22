<?php
	$host = "localhost";
	$dbname = "filemeup";
	$username = "root";
	$password = "";

	$dsn = "mysql:host=$host;dbname=$dbname";
	// $connection = mysqli_connect('localhost','root','','');
	// mysqli_select_db($conn, 'filemeup');
	$connection = new PDO($dsn, $username, $password);
?>