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
    $chatpeoplenum=$_POST['ChatPeopleNum'];
    $chatpeople=$_POST['ChatPeople']; // 제이슨어레이 이다
    $MY_IDX = $_POST['MY_IDX'];

    $chatroomname = "그룹채팅".$chatpeoplenum;
    error_log("chatroomname1_".$chatroomname);
    $chatpeople_json= json_decode($chatpeople,true); //$chatpeople 는 제이슨어레이 이다

    $jsonarray_withoutmine = array_search($MY_IDX, $chatpeople_json);
    unset($chatpeople_json[$jsonarray_withoutmine]);// 제이슨 어레이속  자신의 idx를 삭제하는 코드


    $a = $chatpeople_json[0];
    error_log("chatpeople ".$chatpeople);
    error_log("chatpeople_json[] ".$chatpeople_json[0]);
    foreach ($chatpeople_json as $chatpeople_json){
        error_log("chatpeople_json_".$chatpeople_json);
        // 제이슨 어레이 속 값을 풀어헤치는 방법
        $a = $chatpeople_json;
    }
    if($chatpeoplenum>2){// 그룹채팅일때
        $sql1 = mq("SELECT * FROM UserInfo WHERE idx = '$a'");
        if($row = mysqli_fetch_array($sql1)){
            $username = $row['Username'];
            $userphoto = $row['Photo'];
            $useremail = $row2['Email'];
            error_log("useremail".$useremail);
        }
    }else {// 채팅인원 1명
        $sql2 = mq("SELECT * FROM UserInfo WHERE idx = '$a'");
        if($row2 = mysqli_fetch_array($sql2)){
            $username2 = $row2['Username'];
            $userphoto = $row2['Photo'];
            $useremail = $row2['Email'];
            $chatroomname = $username2;//채팅방 이름을 사람이름으로
            error_log("useremail".$useremail);
        }
    }
    error_log("chatroomname2_".$chatroomname);


    require_once('config.php');

    $sql3 = "SELECT * FROM ChatRoom WHERE ChatPeople = '$chatpeople'";
    $check = mysqli_fetch_array(mysqli_query($con,$sql3));

    if(isset($check)){
        $value= 0;
        $message = "already existing room";
    }else{

        $sql4 = "INSERT INTO ChatRoom (ChatPeopleNum,ChatPeople,ChatRoomName,ChatRoomPhoto) 
                VALUES ('$chatpeoplenum','$chatpeople','$chatroomname','$userphoto')";
        if(mysqli_query($con,$sql4)){
            $sql5 =mq("SELECT * FROM ChatRoom WHERE ChatPeople = '$chatpeople'");
            if($row5 = mysqli_fetch_array($sql5)){
                $username2 = $row5['idx'];
                $userphoto = $row5['ChatPeople'];
                $useremail = $row5['Email'];
                $chatroomname = $username2;//채팅방 이름을 사람이름으로
                error_log("useremail".$useremail);
            }

            $value = 1;
            $message = "make room success";
        }else{
            $value = 0;
            $message = "make room fail";
        }
    }
    mysqli_close($con);
}else{
    $value = 0;
    $message = "server connection fail";
}

$response["value"] = $value;
$response["message"] = $message;
$response["idx"] = $idx;
$response["ChatPeopleNum"] = $chatpeoplenum2;
$response["ChatPeople"] = $chatpeople2;
echo json_encode($response);
?>