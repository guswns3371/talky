<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');


$stmt = $con->prepare('select * from ChatRoom');
$stmt->execute();

if ($stmt->rowCount() > 0)
{
    $data['chatroomlist'] = array();

    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {

        extract($row);

        $data['chatroomlist'][]=
            array(
                'idx'=>$idx,
                'ChatPeopleNum'=>$ChatPeopleNum,
                'ChatPeople'=>$ChatPeople,
                'ChatRoomName'=>$ChatRoomName,
                'ChatRoomPhoto'=>$ChatRoomPhoto,
                'ChatRoomOutTime'=>$ChatRoomOutTime,
                'ChatRoomOutMessage'=>$ChatRoomOutMessage,
                'ChatUnreadList'=>$ChatUnreadList
            );
    }



    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
}

?>