<?php
    session_start();
    include('./html/user_search.html');
    

    
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
    
    
    if(isset($_POST["submit"])) {
        $searchFolderName = $_POST["search"];
        $uid = $_SESSION["userid"];
        $query = "SELECT path From folders Where userID='$uid' AND name like '%$searchFolderName%'";
        $instance = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($instance);
        $output = '
        <div class="container">
            <div class="row1">
                <div style="margin: auto 0;">
                <form method="post" id="search_form" action="user_search.php">
                        <label>Search</label>
                        <input type="text" name="search" id = search>
                        <input type="submit" name="submit" id = "search_submit_button">
                    </form>
                </div>
                <div>
                    <button type="button" name="create_folder" id="create_folder" class="btn btn-success">Create Folder</button>
                </div>
            </div>
            <div id="folder_table" class="table-responsive">
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
    
            if($result) 
            {
                foreach($result as $name) 
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
            $output .= '</table></div></div>';

            ob_end_clean();
            echo $output;
            
    }
?>