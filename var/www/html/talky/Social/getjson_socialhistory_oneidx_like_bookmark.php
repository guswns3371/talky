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
    $myidx=$_GET['idx'];
}else{
    $myidx = $_POST['idx'];
}
if(isset($_GET['like_or_bookmark'])){
    $like_or_bookmark=$_GET['like_or_bookmark'];
}else{
    $like_or_bookmark = $_POST['like_or_bookmark'];
}


if ($like_or_bookmark == "like") {
    $sql = mq("select * from SocialHistoryLike where Social_Liked_myidx = '$myidx' ORDER BY idx DESC ");
    $data["SocialHistory"] = array();
    while ($row = mysqli_fetch_array($sql)) {
        $social_idx = $row['Social_Liked_historyidx'];
        $stmt = $con->prepare("select * from SocialHistory where idx ='$social_idx'");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
//            $data["SocialHistory"] = array();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $useridx = $Social_useridx;

                $stmt2 = $con->prepare("select * from UserInfo where idx = '$useridx'");
                $stmt2->execute();

                if ($stmt2->rowCount() > 0) {

                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        extract($row2);

                        $data2[] =
                            array(
                                'idx' => $idx, //여기서 $idx는 UserInfo의 idx 이다.
                                'Email' => $Email,
                                'Password' => $Password,
                                'Username' => $Username,
                                'Photo' => $Photo,
                                'Birthday' => $Birthday,
                                'Token' => $Token,
                                'Introduce' => $Introduce
                            );
                    }
                }

                $stmt3 = $con->prepare("select * from SocialHistoryLike where Social_Liked_historyidx = '$social_idx'");
                $stmt3->execute();

                if ($stmt3->rowCount() > 0) {

                    while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                        extract($row3);

                        $data3[] =
                            array(
                                'Social_Liked_myidx' => $Social_Liked_myidx
                            );
                    }
                } else {
                    $data3[] =
                        array(
                            'Social_Liked_myidx' => "null"
                        );
                }

                $stmt4 = $con->prepare("select * from SocialHistoryMarked where Social_Marked_historyidx = '$social_idx'");
                $stmt4->execute();

                if ($stmt4->rowCount() > 0) {

                    while ($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)) {
                        extract($row4);

                        $data4[] =
                            array(
                                'Social_Marked_myidx' => $Social_Marked_myidx
                            );
                    }
                } else {
                    $data4[] =
                        array(
                            'Social_Marked_myidx' => "null"
                        );
                }

                $data["SocialHistory"][] =
                    array(
                        'idx' => $social_idx,
                        'Social_useridx' => $Social_useridx,
                        'UserInfo' => $data2,
                        'Social_username' => $Social_username,
                        'Social_time' => $Social_time,
                        'Social_content' => stripslashes($Social_content),
                        'Social_likecnt' => $Social_likecnt,
                        'Social_location' => $Social_location,
                        'Social_imagepath_list' => $Social_imagepath_list,
                        'Social_Liked_List' => $data3,
                        'Social_Marked_List' => $data4
                    );
                $data2 = null;
                $data3 = null;
                $data4 = null;
            }

        }
    }

} else if ($like_or_bookmark =="bookmark"){
    $sql = mq("select * from SocialHistoryMarked where Social_Marked_myidx = '$myidx' ORDER BY idx DESC");
    $data["SocialHistory"] = array();
    while ($row = mysqli_fetch_array($sql)) {
        $social_idx = $row['Social_Marked_historyidx'];
        $stmt = $con->prepare("select * from SocialHistory where idx ='$social_idx'");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
//            $data["SocialHistory"] = array();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $useridx = $Social_useridx;

                $stmt2 = $con->prepare("select * from UserInfo where idx = '$useridx'");
                $stmt2->execute();

                if ($stmt2->rowCount() > 0) {

                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        extract($row2);

                        $data2[] =
                            array(
                                'idx' => $idx, //여기서 $idx는 UserInfo의 idx 이다.
                                'Email' => $Email,
                                'Password' => $Password,
                                'Username' => $Username,
                                'Photo' => $Photo,
                                'Birthday' => $Birthday,
                                'Token' => $Token,
                                'Introduce' => $Introduce
                            );
                    }
                }

                $stmt3 = $con->prepare("select * from SocialHistoryLike where Social_Liked_historyidx = '$social_idx'");
                $stmt3->execute();

                if ($stmt3->rowCount() > 0) {

                    while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                        extract($row3);

                        $data3[] =
                            array(
                                'Social_Liked_myidx' => $Social_Liked_myidx
                            );
                    }
                } else {
                    $data3[] =
                        array(
                            'Social_Liked_myidx' => "null"
                        );
                }

                $stmt4 = $con->prepare("select * from SocialHistoryMarked where Social_Marked_historyidx = '$social_idx'");
                $stmt4->execute();

                if ($stmt4->rowCount() > 0) {

                    while ($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)) {
                        extract($row4);

                        $data4[] =
                            array(
                                'Social_Marked_myidx' => $Social_Marked_myidx
                            );
                    }
                } else {
                    $data4[] =
                        array(
                            'Social_Marked_myidx' => "null"
                        );
                }

                $data["SocialHistory"][] =
                    array(
                        'idx' => $social_idx,
                        'Social_useridx' => $Social_useridx,
                        'UserInfo' => $data2,
                        'Social_username' => $Social_username,
                        'Social_time' => $Social_time,
                        'Social_content' => stripslashes($Social_content),
                        'Social_likecnt' => $Social_likecnt,
                        'Social_location' => $Social_location,
                        'Social_imagepath_list' => $Social_imagepath_list,
                        'Social_Liked_List' => $data3,
                        'Social_Marked_List' => $data4
                    );
                $data2 = null;
                $data3 = null;
                $data4 = null;
            }

        }
    }
  }

//$stmt = $con->prepare( "select * from SocialHistory where Social_useridx ='$myidx' ORDER BY idx DESC");
//$stmt->execute();
//
//if ($stmt->rowCount() > 0)
//{
//    $data["SocialHistory"] = array();
//
//    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
//    {
//
//        extract($row);
//        $socialidx= $idx;
//        $useridx = $Social_useridx;
//
//        $stmt2 = $con->prepare("select * from UserInfo where idx = '$useridx'");
//        $stmt2->execute();
//
//        if ($stmt2->rowCount() > 0)
//        {
//
//            while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
//            {
//                extract($row2);
//
//                $data2[]=
//                    array(
//                        'idx'=>$idx, //여기서 $idx는 UserInfo의 idx 이다.
//                        'Email'=>$Email,
//                        'Password'=>$Password,
//                        'Username'=>$Username,
//                        'Photo'=>$Photo,
//                        'Birthday'=>$Birthday,
//                        'Token'=>$Token,
//                        'Introduce'=>$Introduce
//                    );
//            }
//        }
//
//        $stmt3 = $con->prepare("select * from SocialHistoryLike where Social_Liked_historyidx = '$socialidx'");
//        $stmt3->execute();
//
//        if ($stmt3->rowCount() > 0)
//        {
//
//            while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
//            {
//                extract($row3);
//
//                $data3[]=
//                    array(
//                        'Social_Liked_myidx'=>$Social_Liked_myidx
//                    );
//            }
//        }else{
//            $data3[]=
//                array(
//                    'Social_Liked_myidx'=>"null"
//                );
//        }
//
//        $stmt4 = $con->prepare("select * from SocialHistoryMarked where Social_Marked_historyidx = '$socialidx'");
//        $stmt4->execute();
//
//        if ($stmt4->rowCount() > 0)
//        {
//
//            while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
//            {
//                extract($row4);
//
//                $data4[]=
//                    array(
//                        'Social_Marked_myidx'=>$Social_Marked_myidx
//                    );
//            }
//        }else{
//            $data4[]=
//                array(
//                    'Social_Marked_myidx'=>"null"
//                );
//        }
//
//        $data["SocialHistory"][]=
//            array(
//                'idx'=>$socialidx,
//                'Social_useridx'=>$Social_useridx,
//                'UserInfo'=>$data2,
//                'Social_username'=>$Social_username,
//                'Social_time'=>$Social_time,
//                'Social_content'=>stripslashes($Social_content),
//                'Social_likecnt'=>$Social_likecnt,
//                'Social_location'=>$Social_location,
//                'Social_imagepath_list'=>$Social_imagepath_list,
//                'Social_Liked_List'=>$data3,
//                'Social_Marked_List'=>$data4
//            );
//        $data2=null;
//        $data3=null;
//        $data4=null;
//    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;


?>