<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');


$stmt = $con->prepare('select * from UserInfo');
$stmt->execute();

if ($stmt->rowCount() > 0)
{
    $data['userinfo'] = array();

    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

        $useridx = $idx;
        //팔로우 하는 사람 정보
        $stmt2 = $con->prepare("select * from UserFollow where Follower_idx = '$useridx'");
        $stmt2->execute();
        $data2 = array();
        if ($stmt2->rowCount() > 0)
        {

            while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
            {
                extract($row2);
                $data2[]=
                    array(
                        'Followed_idx' => $Followed_idx
                    );
            }

        }else{
            $data2[]=
                array(
                    'Followed_idx' => "null"
                );
        }
        //팔로우 하는 사람 정보


        //팔로우 하는 사람 정보
        $stmt3 = $con->prepare("select * from UserFollow where Followed_idx = '$useridx'");
        $stmt3->execute();
        $data3 = array();
        if ($stmt3->rowCount() > 0)
        {

            while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
            {
                extract($row3);
                $data3[]=
                    array(
                        'Follower_idx' => $Follower_idx
                    );
            }

        }else{
            $data3[]=
                array(
                    'Follower_idx' => "null"
                );
        }
        //팔로우 하는 사람 정보


        $data['userinfo'][]=
            array(
                'idx'=>$useridx,
                'Email'=>$Email,
                'Password'=>$Password,
                'Username'=>$Username,
                'Photo'=>$Photo,
                'Birthday'=>$Birthday,
                'Token'=>$Token,
                'Introduce'=>$Introduce,
                'UserFollower'=>$data3,
                'UserFollowing' =>$data2
            );

    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
}

?>