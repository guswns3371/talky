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

    require_once('config.php');

    $sql2 = mq("SELECT ChatUnreadList FROM ChatRoom WHERE idx ='$chatroom_idx'");
    if($row = mysqli_fetch_array($sql2)) {
        $old_ChatUnreadList = $row['ChatUnreadList']; // 그냥 제이슨 형태를 띈 String이다
        $data_string = json_decode(json_encode($old_ChatUnreadList)); // 스트링
        error_log("old_ChatUnreadList " . $data_string);
        // String -> json_encode 하면 모든 특수문자에 \\가 붙는다
        //그래서 다시 String ->json_encode->json_decode 를 해줘야 제대로 된 스트링이 나온다

        $data_object = json_decode($old_ChatUnreadList); // 제이슨 오브젝트
        // $data = json_decode($old_ChatUnreadList)= 제이슨 오브젝트
        // json_encode($data) = 스트링


        $array = array();
        foreach ($data_object as $key => $value) {
            if ($key === $user_idx) {
                error_log("key_value_" . $key . "_" . $value);
                $data_object->$key = 0; // 이렇게 해야지 제이슨 오브젝트의 value 를 변경할 수 있다
                // 저 의미는 $data_object의 키값에 해당하는 value 를 0으로 바꿔주겠다는 의미다.
                //즉 $data_object->$key 는 $value이다 그렇다고 $value=0; 이렇게 해버리면 ㄴㄴ
            }
            $unread_count_list = json_encode($data_object);// 스트링
            error_log("new_ChatUnreadList " . $unread_count_list);
        }
    }

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