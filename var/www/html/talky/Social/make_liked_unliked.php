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
    $isLiked=$_POST['isLiked'];
    $myidx=$_POST['MY_IDX'];
    $cliked_idx=$_POST['cliked_idx'];

    require_once('config.php');

    if ($isLiked == "yes"){
        $sql = "INSERT INTO 
                SocialHistoryLike (Social_Liked_myidx,Social_Liked_historyidx) 
                VALUES 
                ('$myidx','$cliked_idx')
                ";
    }else if ($isLiked == "no"){
        $sql = "DELETE FROM 
                SocialHistoryLike 
                WHERE Social_Liked_myidx = '$myidx'
                AND Social_Liked_historyidx = '$cliked_idx'
                ";
    }

        if(mysqli_query($con,$sql)){
            $ISFOLLOW=$isLiked;
            $value = 1;
            $message = "like or unlike save success";
        }else{
            $ISFOLLOW="null";
            $value= 0;
            $message = "like or unlike save fail";
        }


    mysqli_close($con);
}else{
    $ISFOLLOW="null";
    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
$response["Social_isLiked"] = $ISFOLLOW;

echo json_encode($response);
?>