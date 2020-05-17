<?php
$db = new mysqli("localhost","root","Wnsgus123*","test");
$db->set_charset("utf8mb4");

function mq($sql)
{
    global $db;
    return $db->query($sql);
}

$Email=$_POST['Email'];
$target_dir = "/var/www/html/talky/uploads/userimg/";
$_FILES["file"]["name"] = $Email."_".$_FILES["file"]["name"];
$target_dir = $target_dir .basename($_FILES["file"]["name"]);
$Photo = "/uploads/userimg/".$_FILES["file"]["name"];
$response = array();
require_once('config.php');



// Check if image file is a actual image or fake image
if (isset($_FILES["file"]))
{


    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir))
    {

       // $message = "Successfully Uploaded";
        $path = $target_dir;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $message2 = base64_encode($data);

        $sql_2 = "UPDATE `UserInfo` 
                  SET 
                  Photo='$Photo'
                  WHERE Email ='$Email'";
        if(mysqli_query($con,$sql_2)){
            $success = true;
            $message = $Photo;
        }
    }
    else
    {
        $success = false;
        $message = "Error while uploading ". $target_dir;
    }
}
else
{
    $success = false;
    $message = "Required Field Missing";
}

$response["success"] = $success;
$response["message"] = $message;
echo json_encode($response);

?>