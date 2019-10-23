# internship_project
  
The project I did during my internship at Bimser Cozum Yazilim Tic. A.S. in Summer 2019.

## About
  
It is about creating a simple cloud web app using CephFS as the storage location and using HMTL5, Bootstrap, AJAX JQuery and PHP to create the web app which uploads documents to the directory, displays and deletes documents in the directory.

I will add code snippets to illustrate how AJAX JQuery requests are made and how PHP accomplishes the requested tasks.

## Listing the Contents of a Directory

This the main skeleton of the application. If you are wonking on any OS with Linux kernel, you need to give PHP permission to format the destination folder so that it can display the folders, documents and files, and also upload or delete documents from that directory.

### JQuery Request

Following is the request made from the main HTML page to display the contents of the directory. This is done every time you reload the page, and after every action call you make.

```
<script>
$(document).ready(function(){

    load_folder_list();

    function load_folder_list()
    {
        var action = "fetch";
        $.ajax({
            url : "action.php",
            method : "POST",
            data:{action:action},
            success:function(data)
            {
                $('#folder_table').html(data);
            }
        })
    }
});
</script>
```

As the document (the HTML page we are displaying in this case) is loaded up and becomes ready, the script will be called to display the contents of the directory we are using as the Cloud Storage Area. Note that, you should be requesting this web application from that directory. Otherwise, PHP and HTML does not have access to a client's documents, therefore you won't be able to have a working cloud directory application.

After this call, the following PHP code snippet iterates through the main cloud directory to output the folders and files stored.

```
<?php
if(isset($_POST["action"]))
{
    if($_POST["action"] == "fetch")
    {
        $folder = array_filter(glob('*'), 'is_dir');
        $files = array_filter(scandir('.'), 'is_file');
        $output = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>Folder Name</th>
                <th>Total File</th>
                <th>Size</th>
                <th>Update</th>
                <th>Delete</th>
                <th>Upload File</th>
                <th>View Files</th>
            </tr>
        ';

        $table = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>File Name</th>
                <th>Size</th>
                <th>Update</th>
                <th>Delete</th>
                <th>View File</th>
            </tr>
        ';

        if(count($folder) > 0)
        {
            foreach($folder as $name)
            {
                $output .= '
                    <tr>
                        <td>'.$name.'</td>
                        <td>'.(count(scandir($name)) - 2).'</td>
                        <td>'.get_folder_size($name).'</td>
                        <td><button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs">Update</button></td>
                        <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Delete</button></td>
                        <td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-info btn-xs">Upload File</button></td>
                        <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">View Files</button></td>
                    </tr>
                ';
            }
            foreach ($files as $file) {
                $data = explode(".", $file);
                $extension = $data[1];
                if($extension == "php" || $extension == "html" || $extension == "css")
                {
                    continue;
                }
                else
                {
                    $table .= '
                    <tr>
                        <td>'.$file.'</td>
                        <td>'.get_file_size($file).'</td>
                        <td><button type="button" name="update" data-name="'.$file.'" class="update btn btn-warning btn-xs">Update</button></td>
                        <td><button type="button" name="delete_file" data-name="'.$file.'" class="delete_file btn btn-danger btn-xs">Delete</button></td>
                        <td><button type="button" name="view_file" data-name="'.$file.'" class="view_file btn btn-default btn-xs">View File</button></td>
                    </tr>
                    ';
                }
            }
        }
        else
        {
            $output .= '
            <tr>
                <td colspan="6">No Folder Found</td>
            </tr>
            ';
            $table .= '
            <tr>
                <td colspan="4">No Files Found</td>
            </tr>
            ';
        }
        $output .= '</table>';
        $table .= '</table>';
        echo $output;
        echo $table;
    }
}
?>
```