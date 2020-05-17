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
    $user_idx=$_POST['user_idx'];
    $chatroom_idx=$_POST['chatroom_idx'];
    $unread_count_list=$_POST['unreadcount_list'];
//    $unread_count_list = json_encode($unread_count_list);
    require_once('config.php');

    $sql_1 = "UPDATE ChatRoom 
                        SET ChatUnreadList = '$unread_count_list'
                        WHERE idx ='$chatroom_idx'
                        ";

            if(mysqli_query($con,$sql_1)){
                $useridx = $user_idx;
                $roomidx=$chatroom_idx;
                $unreadcount = $unread_count_list;
                $value = 1;
                $message = "unreadcount update success";
            }else{
                $useridx = $user_idx;
                $roomidx= $chatroom_idx;
                $unreadcount =  $unread_count_list;
                $value= 0;
                $message = "unreadcount update fail";
            }


    mysqli_close($con);
}else{
    $useridx = "null";
    $roomidx= "null";
    $unreadcount =  "null";
    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
$response["user_idx"] = $useridx;
$response['chatroom_idx'] = $roomidx;
$response['unreadcount_list'] = $unreadcount;
echo json_encode($response);
?>