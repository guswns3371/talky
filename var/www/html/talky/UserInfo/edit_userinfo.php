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
    //$Photo=$_POST['Photo'];
    $Introduce2=$_POST['Introduce'];
    $Introduce = addslashes($Introduce2);
    // addslashes 가 있어야 홑따옴표를 처리해준다 자동 ' => \'
    // stripslashes 로 \' 를 '로 바꿔줄수 있다
    require_once('config.php');

    $sql = "SELECT * FROM UserInfo WHERE Email = '$Email'";
    //$check = mysqli_fetch_array(mysqli_query($con,$sql));

    if(true){

        $sql_2 = "UPDATE `UserInfo` 
                  SET Username='$Username',
                  Birthday='$Birthday',
                  Introduce='$Introduce'
                  WHERE Email ='$Email'";
        if(mysqli_query($con,$sql_2)){
            $sql_3 = mq($sql);
            if($row = mysqli_fetch_array($sql_3)){
                $username = $row['Username'];
                $photo = $row['Photo'];
                $birthday = $row['Birthday'];
                $introduce2 = $row['Introduce'];
                $introduce = stripslashes($introduce2);
            }
            $response["value"] = 1;
            $response["message"] = "회원정보 Edit 성공";
            $response["username"] = $username;
            $response["photo"] = $photo;
            $response["birthday"] = $birthday;
            $response["introduce"] = $introduce;
            echo json_encode($response);
        }else{

            $response["value"] = 2;
            $response["message"] = "회원정보 Edit 실패";
            $response["username"] = "null";
            $response["photo"] = "null";
            $response["birthday"] = "null";
            $response["introduce"] = "null";
            echo json_encode($response);
        }
    }

    mysqli_close($con);
}else{

    $response["value"] = 3;
    $response["message"] = "서버연결 실패";
    $response["username"] = "null";
    $response["photo"] = "null";
    $response["birthday"] = "null";
    $response["introduce"] = "null";
    echo json_encode($response);
}



?>