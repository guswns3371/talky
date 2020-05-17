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
    $isMarked=$_POST['isMarked'];
    $myidx=$_POST['MY_IDX'];
    $cliked_idx=$_POST['cliked_idx'];

    require_once('config.php');

    if ($isMarked == "yes"){
        $sql = "INSERT INTO 
                SocialHistoryMarked (Social_Marked_myidx,Social_Marked_historyidx) 
                VALUES 
                ('$myidx','$cliked_idx')
                ";
    }else if ($isMarked == "no"){
        $sql = "DELETE FROM 
                SocialHistoryMarked 
                WHERE Social_Marked_myidx = '$myidx'
                AND Social_Marked_historyidx = '$cliked_idx'
                ";
    }

        if(mysqli_query($con,$sql)){
            $ISFOLLOW=$isMarked;
            $value = 1;
            $message = "mark or unmark save success";
        }else{
            $ISFOLLOW="null";
            $value= 0;
            $message = "mark or unmark save fail";
        }


    mysqli_close($con);
}else{
    $ISFOLLOW="null";
    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
$response["Social_isBookMarked"] = $ISFOLLOW;

echo json_encode($response);
?>