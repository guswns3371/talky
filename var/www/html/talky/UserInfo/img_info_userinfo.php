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
    //$Userid=$_POST['Userid'];
    //$Password=$_POST['Password'];

    $Username=$_POST['Username'];
    $Email=$_POST['Email'];
    $Birthday=$_POST['Birthday'];
//    $Photo=$_POST['Photo'];
    $target_dir = "/var/www/html/talky/uploads/userimg/";
    $_FILES["file"]["name"] = $Email."_".$_FILES["file"]["name"];
    $target_dir = $target_dir .basename($_FILES["file"]["name"]);
    $Photo = $_FILES["file"]["name"];
    $Photo = "/uploads/userimg/".$Photo;

    $Introduce2=$_POST['Introduce'];
    $Introduce = addslashes($Introduce2);
    // addslashes 가 있어야 홑따옴표를 처리해준다 자동 ' => \'
    // stripslashes 로 \' 를 '로 바꿔줄수 있다
    require_once('config.php');

    $sql = "SELECT * FROM UserInfo WHERE Email = '$Email'";
    //$check = mysqli_fetch_array(mysqli_query($con,$sql));

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir)){
        $path2 = $target_dir;
        $data2 = file_get_contents($path2);
        $photo_base = base64_encode($data2);
    }

    if(isset($_FILES["file"])){

        $sql_2 = "UPDATE `UserInfo` 
                  SET Username='$Username',
                  Birthday='$Birthday',
                  Photo='$Photo',
                  Introduce='$Introduce'
                  WHERE Email ='$Email'";

        if(mysqli_query($con,$sql_2)){
            $sql_3 = mq($sql);
            if($row = mysqli_fetch_array($sql_3)){
                $username_ = $row['Username'];
                $photo_ = $row['Photo'];
                $birthday_ = $row['Birthday'];
                $introduce2_ = $row['Introduce'];
                $introduce_ = stripslashes($introduce2_);
            }
            $value = 1;
            $message = "userinfo Edit success / img upload not yet ";
            $username = $username_;
            $photo = $photo_;
            $birthday= $birthday_;
            $introduce = $introduce_;

//            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir))
//            {
            if(isset($photo_base)){
                $value = 1;
                $message = "userinfo Edit success / img upload success".$target_dir;

                $path = $target_dir;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            else
            {
                $value = 0;
                $message = "userinfo Edit success / img upload fail ". $target_dir;
            }

        }else{

            $value = 0;
            $message = "userinfo Edit fail";
            $username = "null";
            $photo = "null";
            $birthday = "null";
            $introduce = "null";

        }
    }else{
        $value = 0;
        $message= "Required Field Missing";
        $username = "null";
        $photo = "null";
        $birthday = "null";
        $introduce = "null";
    }

    mysqli_close($con);
}else{

    $value = 0;
    $message= "server connection fail";
    $username = "null";
    $photo = "null";
    $birthday = "null";
    $introduce = "null";

}


$response["value"] = $value;
$response["message"] = $message;
$response["username"] = $username;
$response["photo"] = $photo;
$response["birthday"] = $birthday;
$response["introduce"] = $introduce;
echo json_encode($response);
?>