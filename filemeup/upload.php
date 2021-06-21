<?php
session_start();

$conn = mysqli_connect('localhost','root','','');
mysqli_select_db($conn, 'filemeup');

if($_FILES["upload_file"]["name"] != '')
{   
    $data = explode(".", $_FILES["upload_file"]["name"]);
    $extension = $data[1];
    $new_file_name = $data[0] . '.' . $extension;
    $userID = $_SESSION['userid'];
    $folder_name = $_POST["hidden_folder_name"] . '/';
    $path = $_POST["hidden_folder_name"] . '/' . $new_file_name;
    $query = "INSERT INTO files(name, type, userID, folderName, path) VALUES('$new_file_name', '$extension', '$userID', '$folder_name', '$path')";
    $run = mysqli_query($conn,$query);
    if ($run){
        
        if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path))
        {
            echo 'Image Uploaded';
        }
        else
        {
            echo 'There is some error';
        }
    }
    
}
else
{
    echo 'Please Select a File';
}
?>