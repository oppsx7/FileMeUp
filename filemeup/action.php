<?php
session_start();

$conn = mysqli_connect('localhost','root','','');
mysqli_select_db($conn, 'filemeup');

function format_folder_size($size)
{
    if($size >= 1073741824)
    {
        $size = number_format($size / 1073741824, 2) . ' GB';
    }
    elseif($size >= 1048576)
    {
        $size = number_format($size / 1048576, 2) . ' MB';
    }
    elseif($size >=1024)
    {
        $size = number_format($size / 1024, 2) . ' KB';
    }
    elseif($size >1)
    {
        $size = $size . ' bytes';
    }
    else
    {
        $size = '0 bytes';
    }
    return $size;
}

function get_folder_size($folder_name)
{
    $total_size = 0;
    $file_data = scandir($folder_name);
    foreach($file_data as $file)
    {
        if($file === '.' OR $file === '..')
        {
            continue;
        }
        else
        {
            $path = $folder_name . '/' . $file;
            $total_size = $total_size + filesize($path);
        }
    }

    return format_folder_size($total_size); 
}

if(isset($_POST["action"]))
{
    if($_POST["action"] =="fetch")
    {
        $folder = array_filter(glob('folders/*'), 'is_dir');
        $output = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>Folder Name</th>
                <th>Total File</th>
                <th>Size</th>
                <th>Update</th>
                <th>Delete</th>
                <th>Upload File</th>
                <th>View Uploaded File</th>
            </tr>
        ';

        if(count($folder) > 0) 
        {
            foreach($folder as $name) 
            {
                $basename = basename($name);
                $output .= '
                    <tr>
                        <td>'.$basename.'</td>
                        <td>'.(count(scandir($name)) - 2).'</td>
                        <td>'.get_folder_size($name).'</td>
                        <td><button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs">Update</button></td>
                        <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Delete</button></td>
                        <td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-info btn-xs">Upload File</button></td>
                        <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">View Files</button></td>
                    </tr>
                ';
            }
        }
        else 
        {
            $output .= '
            <tr>
                <td colspan="6">No Folder Found</td>
            </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }

    if($_POST["action"] == "create")
    {
        if(!file_exists($_POST["folder_name"]))
        {
            $userID = $_SESSION['userid'];
            $folderName = $_POST["folder_name"];
            $path =  "folders" . '/' . $folderName;
            $query = "INSERT INTO folders(name, shared, userID, path) VALUES('$folderName', '1', '$userID', '$path')";
            $run = mysqli_query($conn,$query);
            if($run) {
                mkdir($path, 0777, true);
                echo 'Folder Created';
		    } else {
                echo $folderName;
                echo $path;
            }
        }
        else
        {
            echo 'Folder Already Created';
        }
    }

    if($_POST["action"] == "change")
    {
        if(!file_exists($_POST["folder_name"]))
        {
            $folderName = $_POST["folder_name"];
            $oldFolderName = $_POST["old_name"];
            $query = "UPDATE folders SET name = '$folderName' WHERE name = '$oldFolderName'";
            $run = mysqli_query($conn,$query);
            if ($run) {

                rename($_POST["old_name"], $_POST["folder_name"]);
                echo 'Folder Name Changed';
            }
        }
        else
        {
            echo 'Folder Already Created';
        }
    }

    if($_POST["action"] == "fetch_files")
    {
        $folder_name = $_POST["folder_name"];
        $query = "Select name FROM files WHERE folderName = '$folder_name'";
        $result = $conn->query($query);
        $file_data = scandir($_POST["folder_name"]);
        $output = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>Image</th>
                <th>File Name</th>
                <th>Delete</th>
            </tr>
        ';

        foreach($file_data as $file)
        {
            if($file === '.' OR $file === '..')
            {
                continue;
            }
            else
            {
                $path = $_POST["folder_name"] . '/' . $file;
                $output .= '
                <tr>
                    <td><a href="'.$path.'"><img src="'.$path.'" class="img-thumbnail" height="50" width="50" /></a></td>
                    <td contenteditable="true" data-folder_name="'.$_POST["folder name"].'" data-file_name ="'.$file.'" class="change_file_name">'.$file.'</td>
                    <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="'.$path.'">Remove</button></td>
                </tr>
                ';
            }
        }
        $output .= '</table';
        echo $output;
    }

    if($_POST["action"] =="remove_file")
    {  

        $fileN = end(explode("/", $_POST["path"]));
        $query = "DELETE FROM files WHERE  name = '$fileN'";
        $run = mysqli_query($conn,$query);
        if(file_exists($_POST["path"]))
        {
            unlink($_POST["path"]);
            echo 'File Deleted';
            
        }
    }

    if($_POST["action"] == "delete")
    {
        $files = scandir($_POST["folder_name"]);
        foreach($files as $file)
        {
            if($file == '.' || $file === '..')
            {
                continue;
            }
            else
            {
                unlink($_POST["folder_name"] . '/' . $file);
            }
        }
        if(rmdir($_POST["folder_name"]))
        {
            echo 'Folder Deleted';
        } 
    }
    
    if($_POST["action"] == "change_file_name")
    {
        $old_name = $_POST["folder_name"] . '/' . $_POST["old_file_name"];
        $new_name = $_POST["folder_name"] . '/' . $_POST["new_file_name"];
        $old_name_wo_path = $_POST["old_file_name"];
        $new_name_wo_path = $_POST["new_file_name"];
        $query = "UPDATE files SET name = '$new_name_wo_path' WHERE name = '$old_name_wo_path'";
        $run = mysqli_query($conn,$query);
        if ($run){
            if(rename($old_name, $new_name))
        {
            echo 'File name change successfully';
        }
        else
        {   
            echo 'There is an error';
        }
        }
        
        else
        {
            echo 'There is an error';
        }
    }
}
?>