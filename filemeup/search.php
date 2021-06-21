<?php
session_start();

$conn = mysqli_connect('localhost','root','','');
mysqli_select_db($conn, 'filemeup');

if($_FILES["upload_file"]["name"] != '')
{   
    
    
}
else
{
    echo 'Please Select a File';
}
?>