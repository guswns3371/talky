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
    $chat_message=$_POST['chat_message']; //fileUri
    $chat_useridx=$_POST['chat_useridx'];
    $chatroom_idx=$_POST['chatroom_idx'];
    $chat_readpeople=$_POST['chat_readpeople'];
    $chat_readpeople_list=$_POST['chat_readpeople_list'];
    $yes="yes";

    $target_dir = "/var/www/html/uploads/chatupload/";
    $_FILES["file"]["name"] = $chatroom_idx."_".$chat_useridx."_".$_FILES["file"]["name"];
    $target_dir = $target_dir .basename($_FILES["file"]["name"]);
    $imagefilepath = "/uploads/chatupload/".$_FILES["file"]["name"];

    $json_decoded = json_decode($chat_readpeople_list,true);
    $elementCount  = count($json_decoded);
    $chat_readpeople = $elementCount;
//    $chat_message = esc_sql($chat_message);

    require_once('config.php');

    if(isset($_FILES["file"])){
        $sql = "INSERT INTO 
                ChatHistory (ChatRoom_idx,ChatHistory_useridx,ChatHistory_time,ChatHistory_content,ChatHistory_readpeople,ChatHistory_readpeople_list,ChatHistory_isfile) 
                VALUES 
                ('$chatroom_idx','$chat_useridx','$chat_time','$imagefilepath','$chat_readpeople','$chat_readpeople_list','$yes')
                
                ";
        if(mysqli_query($con,$sql)){
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir)){
                $room_idx=$chatroom_idx;
                $useridx=$chat_useridx;
                $time=$chat_time;
                $content=$chat_message;
                $readpeople = $chat_readpeople;
                $readpeoplelist =$chat_readpeople_list;
                $isfile = $yes;
                $value = 1;
                $message = "message and image save success";
            }else{
                $room_idx=$chatroom_idx;
                $useridx=$chat_useridx;
                $time=$chat_time;
                $content=$chat_message;
                $readpeople = $chat_readpeople;
                $readpeoplelist =$chat_readpeople_list;
                $isfile = $yes;
                $value = 0;
                $message = "message save success but image fuck";
            }

        }else{
            $room_idx="null";
            $useridx="null";
            $time="null";
            $content="null";
            $readpeople="null";
            $readpeoplelist = "null";
            $isfile = "null";
            $value= 0;
            $message = "message save fail";
        }
    }else{
        $room_idx="null";
        $useridx="null";
        $time="null";
        $content="null";
        $readpeople="null";
        $readpeoplelist = "null";
        $isfile = "null";
        $value= 0;
        $message = "theres no image file";
    }

    mysqli_close($con);
}else{
    $room_idx="null";
    $useridx="null";
    $time="null";
    $content="null";
    $readpeople="null";
    $readpeoplelist = "null";
    $isfile = "null";
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
$response['ChatHistory_readpeople_list'] = $readpeoplelist;
$response['ChatHistory_isfile'] = $isfile;
echo json_encode($response);
?>