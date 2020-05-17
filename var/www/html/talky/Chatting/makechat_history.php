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
    $chat_time=$_POST['chat_time'];
    $chat_message=$_POST['chat_message'];
    /**실험 성공*/
    $chat_message = addslashes($chat_message);// 홑따옴표 저장하는 방법 뿌릴때 stripslashes()해야 한다
    /** */
    $chat_useridx=$_POST['chat_useridx'];
    $chatroom_idx=$_POST['chatroom_idx'];
    $chat_readpeople=$_POST['chat_readpeople'];
    $chat_readpeople_list=$_POST['chat_readpeople_list'];
    $no = "no";

    $json_decoded = json_decode($chat_readpeople_list,true);
    $elementCount  = count($json_decoded);
    $chat_readpeople = $elementCount;
//    $chat_message = esc_sql($chat_message);

    require_once('config.php');

    if(true){
        $sql = "INSERT INTO 
                ChatHistory (ChatRoom_idx,ChatHistory_useridx,ChatHistory_time,ChatHistory_content,ChatHistory_readpeople,ChatHistory_readpeople_list,ChatHistory_isfile) 
                VALUES 
                ('$chatroom_idx','$chat_useridx','$chat_time','$chat_message','$chat_readpeople','$chat_readpeople_list','$no')
                
                ";
        if(mysqli_query($con,$sql)){
            $room_idx=$chatroom_idx;
            $useridx=$chat_useridx;
            $time=$chat_time;
            $content=$chat_message;
            $readpeople = $chat_readpeople;
            $value = 1;
            $message = "message save success";
        }else{
            $room_idx="null";
            $useridx="null";
            $time="null";
            $content="null";
            $readpeople="null";
            $value= 0;
            $message = "message save fail";
        }
    }

    mysqli_close($con);
}else{
    $room_idx="null";
    $useridx="null";
    $time="null";
    $content="null";
    $readpeople="null";
    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
$response["ChatRoom_idx"] = $room_idx;
$response["ChatHistory_useridx"] = $useridx;
$response["ChatHistory_content"] = $content;
$response["ChatHistory_time"] = $time;
$response['ChatHistory_readpeople'] = $readpeople;
echo json_encode($response);
?>