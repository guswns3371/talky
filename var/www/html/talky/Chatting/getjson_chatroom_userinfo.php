<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');


//$stmt = $con->prepare('select * from UserInfo');
//$stmt->execute();
//
//if ($stmt->rowCount() > 0)
//{
//    $data['userinfo'] = array();
//
//    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
//    {
//        extract($row);
//
//        $data['userinfo'][]=
//            array(
//                'idx'=>$idx,
//                'Email'=>$Email,
//                'Password'=>$Password,
//                'Username'=>$Username,
//                'Photo'=>$Photo,
//                'Birthday'=>$Birthday,
//                'Session'=>$Session,
//                'Introduce'=>$Introduce
//
//            );
//    }
//
//    header('Content-Type: application/json; charset=utf8');
//
//}

$stmt2 = $con->prepare("select * from ChatRoom");
$stmt2->execute();

if ($stmt2->rowCount() > 0) {
    $data['chatroomlist'] = array();

    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {

        extract($row2);

//        $data['chatroomlist'][] =
//            array(
//                'idx' => $idx,
//                'ChatPeopleNum' => $ChatPeopleNum,
//                'ChatPeople' => $ChatPeople,
//                'ChatRoomName' => $ChatRoomName,
//                'ChatRoomPhoto' => $ChatRoomPhoto,
//                'ChatRoomTime' => $ChatRoomTime,
//                'ChatRoomDes' => $ChatRoomDes
//            );
        $chatroom_idx=$idx;
        //여기서 $idx는 ChatRoom의 idx이다
        // ChatRoom의 컬럼명 idx와 같은 이름으로 변수를 설정하면
        // ChatRoom의 idx컬럼의 값을 가져올수 있다
        $json_chatpeople=json_decode($ChatPeople);
        $IDX=$json_chatpeople[0];
        //error_log("IDX_".$IDX);
        foreach ($json_chatpeople as $json_chatpeople){
                //error_log("innerdata_".$json_chatpeople);
//
//            $stmt = $con->prepare("select * from ChatUnreadCount where Unread_User_idx = '$json_chatpeople'");
//            $stmt->execute();
//
//            if ($stmt->rowCount() > 0)
//            {
//                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
//                {
//                    extract($row);
//
//                    $data3[]=
//                        array(
//                            'idx'=>$idx, //여기서 $idx는 UserInfo의 idx 이다.
//                            'Unread_User_idx'=>$Unread_User_idx,
//                            'Unread_ChatRoom_idx'=>$Unread_ChatRoom_idx,
//                            'Unread_Count'=>$Unread_Count
//                        );
//                }
//            }

            $stmt = $con->prepare("select * from UserInfo where idx = '$json_chatpeople'");
            $stmt->execute();

            if ($stmt->rowCount() > 0)
            {
//                $data2['info'][] = array();

                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);

                    $data2[]=
                        array(
                            'idx'=>$idx, //여기서 $idx는 UserInfo의 idx 이다.
                            'Email'=>$Email,
                            'Password'=>$Password,
                            'Username'=>$Username,
                            'Photo'=>$Photo,
                            'Birthday'=>$Birthday,
                            'Token'=>$Token,
                            'Introduce'=>$Introduce
//                            'ChatUnreadCount'=>$data3

                        );
                }
            }

//            $data['chatroomlist'][]['ChatRoomPeopleInfos'][] =
//                array(
//                    'chatroomidx'=>$chatroom_idx,
//                    'useridx'=>$json_chatpeople,
//                    'useridx_info'=>$data2
//                );

        }
        $data['chatroomlist'][] =
            array(
                'ChatRoomIdx'=>$chatroom_idx,
                'ChatPeopleNum' => $ChatPeopleNum,
                'ChatPeople' => $ChatPeople,
                'ChatRoomName' => $ChatRoomName,
                'ChatRoomPhoto' => $ChatRoomPhoto,
                'ChatRoomOutTime'=>$ChatRoomOutTime,
                'ChatRoomOutMessage'=>$ChatRoomOutMessage,
                'ChatUnreadList'=>$ChatUnreadList,
                'UserIdx_Info'=>$data2
            );
        if($chatroom_idx!=$idx){
            //$idx 는 new ChatRoomidx 이고
            //$chatroom_idx는 old ChatRoomidx 이다.
            $data2=null;
        }
    }
}

header('Content-Type: application/json; charset=utf8');
$json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
echo $json;
?>