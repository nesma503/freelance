<?php

if(isset($_GET['path']))
{
//Read the filename and username
$newFileName = $fileName = $_GET['path']; 
if(isset($_GET['username']))
    {
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = $_GET['username'] .'-CV.'.$fileExtension;
    }
//Check the file exists or not
if(file_exists($fileName)) {

//Define header information
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header('Content-Disposition: attachment; filename="'.basename($newFileName).'"');
header('Content-Length: ' . filesize($fileName));
header('Pragma: public');

//Clear system output buffer
flush();

//Read the size of the file
readfile($fileName);

//Terminate from the script
die();
}
else{
echo "File does not exist.";
}
}
else
echo "Filename is not defined."
?>