<?php
session_start();
//Getting user uploaded file
$file = $_FILES["file"];
$parts = explode(".", $file["name"]);
$type = strval(end($parts));


$conn = mysqli_connect('localhost','root','','');
mysqli_select_db($conn, 'filemeup');


if(isset($_POST['submit'])) {
    $fileName = $_FILES['file']['name'];
    $fileTempName = $_FILES['file']['tmp_name'];
    $path = "files/".$fileName;

    $query = "INSERT INTO files(name, type) VALUES('$fileName', '$type')";
    $run = mysqli_query($conn,$query);

    if ($run){
        //Saving file in uploads folder
        move_uploaded_file($file["tmp_name"], "uploads/" . $file["name"]);
        echo "success";
        //Redirecting back to home
        header("Location: upload.php");  
    }
}

?>
<head>
    <link rel="stylesheet" href="./style.css"/>
</head>
<header>
    <nav class="nav_bar">
        <a href="upload.php">My Uploads</a> |
        <a href="index.php">All Uploads</a> |
        <a href="about.php">About</a> |
        <a href="login.php">LogOut</a>
    </nav>
</header>
<body>
    <div class="main_container">
        <form method="POST" enctype="multipart/form-data" action="./upload.php">
            <input type="file" name="file">
            <button type="submit" name="submit">Upload</button>
        </form> 
        <?php 

$files = scandir("uploads");
for ($a = 2; $a < count($files); $a++) {
    //Displaying links to download
    ?>
    <p>
        <a download="<?php echo $files[$a] ?>" href="uploads/<?php echo $files[$a] ?>"><?php echo $files[$a] ?></a>
    </p>
    <?php
    }
    ?>
    </div>
</body>


