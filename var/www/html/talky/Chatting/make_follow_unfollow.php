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
    $isfollow=$_POST['isfollow'];
    $follow_er_idx=$_POST['follow_er_idx'];
    $follow_ed_idx=$_POST['follow_ed_idx'];

    require_once('config.php');

    if ($isfollow == "follow"){
        $sql = "INSERT INTO 
                UserFollow (Follower_idx,Followed_idx) 
                VALUES 
                ('$follow_er_idx','$follow_ed_idx')
                ";
    }else if ($isfollow == "unfollow"){
        $sql = "DELETE FROM 
                UserFollow 
                WHERE Follower_idx = '$follow_er_idx'
                AND Followed_idx = '$follow_ed_idx'
                ";
    }

        if(mysqli_query($con,$sql)){
            $ISFOLLOW=$isfollow;
            $FOLLOW_ER=$follow_er_idx;
            $FOLLOW_ED=$follow_ed_idx;
            $value = 1;
            $message = "follow or unfollow save success";
        }else{
            $ISFOLLOW="null";
            $FOLLOW_ER="null";
            $FOLLOW_ED="null";
            $value= 0;
            $message = "follow or unfollow save fail";
        }


    mysqli_close($con);
}else{
    $ISFOLLOW="null";
    $FOLLOW_ER="null";
    $FOLLOW_ED="null";
    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
$response["isfollow"] = $ISFOLLOW;
$response["Follower_idx"] = $FOLLOW_ER;
$response["followed_idx"] = $FOLLOW_ED;
echo json_encode($response);
?>