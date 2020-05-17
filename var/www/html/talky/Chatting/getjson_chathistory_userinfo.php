<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');

if(isset($_GET['chatroom_idx'])){
    $ChatRoomIDX = $_GET['chatroom_idx'];
}else{
    $ChatRoomIDX = $_POST['chatroom_idx'];
}

$stmt2 = $con->prepare("select * from ChatHistory where ChatRoom_idx = '$ChatRoomIDX'");
$stmt2->execute();

if ($stmt2->rowCount() > 0) {
    $data['chathistorylist'] = array();

    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {

        extract($row2);

        $chathistory_idx=$idx;
        //여기서 $idx는 ChatRoom의 idx이다
        // ChatRoom의 컬럼명 idx와 같은 이름으로 변수를 설정하면
        // ChatRoom의 idx컬럼의 값을 가져올수 있다


        $stmt3 = $con->prepare("select * from UserInfo where idx = '$ChatHistory_useridx'");
        $stmt3->execute();
        if ($stmt3->rowCount() > 0)
        {
            while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
            {
                extract($row3);
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

                    );


            }
        }

//        $json_chatpeople=json_decode($ChatPeople);
//        $IDX=$json_chatpeople[0];
//        //error_log("IDX_".$IDX);
//        foreach ($json_chatpeople as $json_chatpeople){
//                //error_log("innerdata_".$json_chatpeople);
//
//            $stmt = $con->prepare("select * from UserInfo where idx = '$json_chatpeople'");
//            $stmt->execute();
//
//            if ($stmt->rowCount() > 0)
//            {
////                $data2['info'][] = array();
//
//                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
//                {
//                    extract($row);
//
//                    $data2[]=
//                        array(
//                            'idx'=>$idx, //여기서 $idx는 UserInfo의 idx 이다.
//                            'Email'=>$Email,
//                            'Password'=>$Password,
//                            'Username'=>$Username,
//                            'Photo'=>$Photo,
//                            'Birthday'=>$Birthday,
//                            'Token'=>$Token,
//                            'Introduce'=>$Introduce
//
//                        );
//                }
//            }
//
//        }
        $a="1";
        $b="yes history";
        $data['chathistorylist'][] =
            array(
                'value'=>$a,
                'message'=>$b,
                'ChatHistoryIdx'=>$chathistory_idx,
                'ChatRoom_idx' => $ChatRoom_idx,
                'ChatHistory_useridx' => $ChatHistory_useridx,
                'ChatHistory_time' => $ChatHistory_time,
                'ChatHistory_content' => stripslashes($ChatHistory_content),
                'ChatHistory_readpeople'=>$ChatHistory_readpeople,
                'ChatHistory_readpeople_list'=>$ChatHistory_readpeople_list,
                'ChatHistory_isfile'=>$ChatHistory_isfile,
                'UserIdx_Info'=>$data2
            );
        $data2=null;
    }
}else{
    $a="0";
    $b="no history";
    $data['chathistorylist'][] =
        array(
            'value'=>$a,
            'message'=>$b
        );
}

header('Content-Type: application/json; charset=utf8');
$json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
echo $json;
?>