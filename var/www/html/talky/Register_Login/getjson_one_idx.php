<?php

$db = new mysqli("localhost","root","Wnsgus123*","test");
$db->set_charset("utf8mb4");
function mq($sql)
{
    global $db;
    return $db->query($sql);
}

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');

if(isset($_GET['idx'])){
    $friendidx=$_GET['idx'];
}else{
    $friendidx = $_POST['idx'];
}

$stmt = $con->prepare("select * from UserInfo where idx = '$friendidx'");
$stmt->execute();

if ($stmt->rowCount() > 0)
{


    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

        $stmt2 = $con->prepare("select * from UserFollow where Followed_idx = '$friendidx'");
        $stmt2->execute();

        if ($stmt2->rowCount() > 0)
        {
            while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
            {
                extract($row2);
                $data2[]=
                    array(
                        'Follower_idx'=>$Follower_idx
                    );
            }
        }else{
            $data2[]=
                array(
                    'idx'=>"null",
                    'Follower_idx'=>"null",
                    'Followed_idx'=>"null"
                );
        }


        $stmt3 = $con->prepare("select * from UserFollow where Follower_idx = '$friendidx'");
        $stmt3->execute();

        if ($stmt3->rowCount() > 0)
        {
            while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
            {
                extract($row3);
                $data3[]=
                    array(
                        'Followed_idx'=>$Followed_idx
                    );
            }
        }else{
            $data3[]=
                array(
                    'idx'=>"null",
                    'Follower_idx'=>"null",
                    'Followed_idx'=>"null"
                );
        }

        $sql = mq("SELECT * FROM SocialHistory WHERE Social_useridx = '$friendidx'");
        $num_rows = mysqli_num_rows($sql);

       $data =
            array(
                'idx'=>$friendidx,
                'Email'=>$Email,
                'Password'=>$Password,
                'Username'=>$Username,
                'Photo'=>$Photo,
                'Token'=>$Token,
                'Birthday'=>$Birthday,
                'Introduce'=>$Introduce,
                'UserFollower'=>$data2,
                'UserFollowing'=>$data3,
                'SocialHistoryCount'=>$num_rows

            );
    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
}

?>