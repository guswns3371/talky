<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');

if(isset($_GET['replyroom_idx'])){
    $replyroom_idx = $_GET['replyroom_idx'];
}else{
    $replyroom_idx = $_POST['replyroom_idx'];
}

$stmt2 = $con->prepare("select * from SocialReplyHistory where Social_Reply_roomidx = '$replyroom_idx' order by idx desc");
$stmt2->execute();

if ($stmt2->rowCount() > 0) {
    $data['social_replylist'] = array();

    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {

        extract($row2);

        $socialreplyhistory_idx=$idx;
        $useridx = $Social_Reply_useridx;
        //여기서 $idx는 ChatRoom의 idx이다
        // ChatRoom의 컬럼명 idx와 같은 이름으로 변수를 설정하면
        // ChatRoom의 idx컬럼의 값을 가져올수 있다


        $stmt3 = $con->prepare("select * from UserInfo where idx = '$useridx'");
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
            $idx = null;
        }

        /**대댓글 정보*/
        $stmt4 = $con->prepare("select * from SocialReReply where Rere_contentidx = '$socialreplyhistory_idx' and Rere_roomidx = '$replyroom_idx' order by idx desc");
        $stmt4->execute();
        if ($stmt4->rowCount() > 0)
        {
            while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
            {
                extract($row4);
                $rere_idx = $idx;
                /**대댓글 유저 정보*/
                $stmt5 = $con->prepare("select * from UserInfo where idx = '$Rere_useridx'");
                $stmt5->execute();
                if ($stmt5->rowCount() > 0)
                {
                    if($row5=$stmt5->fetch(PDO::FETCH_ASSOC))
                    {
                        extract($row5);
                        $data4=
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
                }else{
                    $data4 = array('idx' =>"null") ;
                }

                $data3[]=
                    array(
                        'idx'=>$rere_idx, //여기서 $idx는
                        'Rere_contentidx'=>$Rere_contentidx,
                        'Rere_useridx'=>$Rere_useridx,
                        'Rere_roomidx'=>$Rere_roomidx,
                        'Rere_content'=>$Rere_content,
                        'Rere_time'=>$Rere_time,
                        'Rere_UserIdx_Info'=>$data4
                    );
                $data4=null;
            }
        }else{
            $data3[] =array('idx' =>"null") ;
        }

        $a="1";
        $b="yes history";
        $data['social_replylist'][] =
            array(
                'value'=>$a,
                'message'=>$b,
                'idx'=>$socialreplyhistory_idx,
                'Social_Reply_useridx'=>$Social_Reply_useridx,
                'Social_Reply_roomidx'=>$Social_Reply_roomidx,
                'Social_Reply_content'=>$Social_Reply_content,
                'Social_Reply_time'=>$Social_Reply_time,
                'UserIdx_Info'=>$data2,
                'ReReply_info'=>$data3
            );
        $data2=null;
        $data3=null;
    }
}else{
    $a="0";
    $b="no history";
    $data['social_replylist'][] =
        array(
            'value'=>$a,
            'message'=>$b
        );
}

header('Content-Type: application/json; charset=utf8');
$json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
echo $json;
?>