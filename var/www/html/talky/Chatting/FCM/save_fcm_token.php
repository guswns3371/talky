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
    $useridx = $_POST['Useridx'];
    $token = $_POST['Token'];

    require_once('config.php');

    if (isset($useridx) && isset($token)){
        $sql = "UPDATE  
                    UserInfo 
                    SET Token = '$token'
                    WHERE idx = '$useridx'";
        if(mysqli_query($con,$sql)){
            $value = 1;
            $message = "useridx and token are saved";
            $useridx_res = $useridx;
            $token_res = $token;
        }else{
            $value = 0;
            $message = "useridx and token are fail to be saved";
            $useridx_res = "null";
            $token_res = "null";
        }

    }else{
        $value = 0;
        $message = "useridx or token is missing";
        $useridx_res = "null";
        $token_res = "null";
    }
    mysqli_close($con);
} else{
    $value = 0;
    $message = "server connect fail";
    $useridx_res = "null";
    $token_res = "null";
}
$response['value'] = $value;
$response['message'] = $message;
$response['useridx'] = $useridx_res;
$response['token'] = $token_res;
echo $response;
?>