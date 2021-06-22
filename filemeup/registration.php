<?php
    require_once "session.php";
    require_once "config.php";
    include('./html/registration.html');
    
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
