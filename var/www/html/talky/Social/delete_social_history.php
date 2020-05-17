<?php
$db = new mysqli("localhost","root","Wnsgus123*","test");
$db->set_charset("utf8mb4");
function mq($sql)
{
    global $db;
    return $db->query($sql);
}

if($_SERVER['REQUEST_METHOD']== 'POST'){
    $response = array();
    $historyidx = $_POST['historyidx'];
    $MY_IDX = $_POST['MY_IDX'];
    require_once('config.php');

    if(true){
        $sql = "DELETE FROM 
                SocialHistory 
                WHERE idx = '$historyidx' and  Social_useridx = '$MY_IDX'
                ";
        if(mysqli_query($con,$sql)){
            $value = 1;
            $message = "SocialHistory delete success";
        }else{

            $value= 0;
            $message = "SocialHistory delete fail";
        }
    }

    mysqli_close($con);
}else{

    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
echo json_encode($response);
?>