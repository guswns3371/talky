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
    $chat_readpeople_list=$_POST['chat_readpeople_list'];
//    $json_decoded = json_decode($chat_readpeople_list,true);


//    error_log("1_chat_readpeople_list_1 ".$chat_readpeople_list);
//    error_log("chat_readpeople_list_2 ".$json_decoded."/".$elementCount);

    require_once('config.php');

        $sql_1 = mq("SELECT * FROM ChatHistory where ChatRoom_idx ='$chatroom_idx'");
        while($row = mysqli_fetch_array($sql_1)){
            $old_readpeople_list = $row['ChatHistory_readpeople_list'];
            $chathistory_idx = $row['idx'];
            //error_log("1_old_readpeople_list ".$old_readpeople_list);

            $json_decoded = json_decode($old_readpeople_list,true);
            $elementCount  = count($json_decoded);
            $readpeoplenum = $elementCount;


            $array = json_decode($old_readpeople_list);
            $isMe=0; //지상 세계의 변수

            for($i=0;$i<$readpeoplenum;$i++){ //내가 있나 검사
                $element = $array[$i];
                if($element == $user_idx){
                  global $isMe; //벙커 속 변수 그래서 global키워드를 통해 지상세계의 변수의 값을 변경 할수 있다
                  $isMe++;
                }
                //error_log("2_element/user_idx/isMe  ".$element."/".$user_idx."/".$isMe);
            }

            //error_log("3_final_isMe/before_readpeoplenum ".$isMe."/".$readpeoplenum);
            //error_log("-------------------I_was_there-------------------".$user_idx);

            if ($isMe == 0){//내가 읽지 않았을떄
                    global $old_readpeople_list;
                    //error_log("4_before_readpeoplenum ".$readpeoplenum);
                    array_push($array,$user_idx);
                    $old_readpeople_list = json_encode($array);

                $json_decoded = json_decode($old_readpeople_list,true);
                $elementCount  = count($json_decoded);
                global $readpeoplenum;
                $readpeoplenum = $elementCount; //다시 해줘야한다 왜냐면 새로운 element를 추가했기때문에

               // error_log("5_After_readpeoplenum ".$readpeoplenum);
               // error_log("6_new_readpeople_list ".$chathistory_idx.": ".$old_readpeople_list);
               // error_log("------------Im_new_here-------------".$user_idx);



                $sql = "UPDATE ChatHistory 
                SET ChatHistory_readpeople = '$readpeoplenum',
                     ChatHistory_readpeople_list = '$old_readpeople_list'
                WHERE ChatRoom_idx = '$chatroom_idx' and idx = '$chathistory_idx'
                ";
                if(mysqli_query($con,$sql)){
                    $room_idx="success";
                    $readpeople = "success";
                    $value = 1;
                    $message = "message update success";
                }else{
                    $room_idx="null";
                    $readpeople="null";
                    $value= 0;
                    $message = "message update fail";
                }
            }else{//내가 읽었을때
                $room_idx="cant";
                $readpeople="cant";
                $value= 1;
                $message = "message cant update";
            }


        }

    mysqli_close($con);
}else{
    $room_idx="null";
    $readpeople="null";
    $value = 0;
    $message = "server connect fail";
}


$response["value"] = $value;
$response["message"] = $message;
$response["ChatRoom_idx"] = $room_idx;
$response['ChatHistory_readpeople'] = $readpeople;
echo json_encode($response);
?>