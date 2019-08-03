<?php
//upload.php
if($_FILES["upload_file"]["name"] != '')
{
	$data = explode(".", $_FILES["upload_file"]["name"]);
	$name = $data[0];
	$extension = $data[1];
	$allowed_extension = array("jpg", "png", "gif", "txt", "odt", "ods", "odp");
	if(in_array($extension, $allowed_extension))
	{
		$new_file_name = $name . '.' . $extension;
		$path = $_POST["hidden_folder_name"] . '/' . $new_file_name;
		if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path))
		{
			echo 'File Uploaded';
		}
		else
		{
			echo 'There is some error';
		}
	}
	else
	{
		echo 'Invalid File Type';
	}

}
else
{
	echo 'Please Select A File';
}
?>