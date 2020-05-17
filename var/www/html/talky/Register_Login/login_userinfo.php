<?php
session_start();
$db = new mysqli("localhost","root","Wnsgus123*","test");
$db->set_charset("utf8mb4");

function mq($sql)
{
    global $db;
    return $db->query($sql);
}
if($_SERVER['REQUEST_METHOD']== 'POST'){
    $response = array();

    $Password=$_POST['Password'];
    $Email=$_POST['Email'];
    $Token = $_POST['Token'];
//    $Idx = $_POST['Idx'];
    require_once('config.php');

    $sql = "SELECT * FROM UserInfo WHERE Email = '$Email'";
    $check = mysqli_fetch_array(mysqli_query($con,$sql));

    if(isset($check)){
        // 이메일은 존재함

        // 비밀번호 확인하는 부분
        $sql_pw = "SELECT * FROM UserInfo WHERE Email = '$Email' AND Password = '$Password'";
        $check_pw = mysqli_fetch_array(mysqli_query($con,$sql_pw));
        if(isset($check_pw)){
            //맞는 패스워드
            $sql_pw2 = mq($sql_pw);
            if($row = mysqli_fetch_array($sql_pw2)){
                $username = $row['Username'];
                $photo = $row['Photo'];
                $birthday = $row['Birthday'];
                $idx = $row['idx'];
            }
            $value = 1;
            $message = "로그인 성공";
            $username_res = $username;
            $email_res = $Email;
            $birthday_res = $birthday;
            $idx_res= $idx;


            $sql = "UPDATE  
                    UserInfo 
                    SET Token = '$Token'
                    WHERE idx = '$idx'";
            if(mysqli_query($con,$sql)){
                // 토큰갑을 업데이트 했을때
                $value = 1;
                $message = "로그인 성공!! 토큰값 업데이트 성공";
                $username_res = $username;
                $email_res = $Email;
                $birthday_res = $birthday;
                $idx_res= $idx;
                $photo_res = $photo;
            }else{
                $value = 1;
                $message = "로그인 성공 !! but 토큰값 업데이트 실패";
                $username_res = $username;
                $email_res = $Email;
                $birthday_res = $birthday;
                $idx_res= $idx;
                $photo_res = $photo;
            }
        }else{
            //틀린 패스워드
            $value = 2;
            $message = "비밀번호가 틀립니다";
            $username_res = "null";
            $email_res ="null";
            $birthday_res = "null";
            $idx_res= "null";
            $photo_res = "null";
        }
    }else{
        //이메일 존재하지 않음
        $value = 3;
        $message = "존재하지 않은 이메일입니다";
        $username_res = "null";
        $email_res ="null";
        $birthday_res = "null";
        $idx_res= "null";
        $photo_res = "null";
//        $sql = "INSERT INTO UserInfo (Password,Username,Email) VALUES ('$Password','$Username','$Email')";
//        if(mysqli_query($con,$sql)){
//
//            $response["value"] = 1;
//            $response["message"] = "회원가입 성공";
//            echo json_encode($response);
//        }else{
//
//            $response["value"] = 0;
//            $response["message"] = "회원가입 실패";
//            echo json_encode($response);
//        }
    }

    mysqli_close($con);
}else{

    $value = 0;
    $message = "서버연결 실패";
    $username_res = "null";
    $email_res ="null";
    $birthday_res = "null";
    $idx_res= "null";
    $photo_res = "null";
}

$response["value"] = $value;
$response["message"] = $message;
$response["username"] = $username_res;
$response["email"] = $email_res;
$response["birthday"] = $birthday_res;
$response["idx"]= $idx_res;
$response["photo"]= $photo_res;
echo json_encode($response);

?>