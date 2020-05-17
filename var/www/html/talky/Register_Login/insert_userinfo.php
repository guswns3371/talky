<?php

if($_SERVER['REQUEST_METHOD']== 'POST'){
    $response = array();
    //$Userid=$_POST['Userid'];
    $Password=$_POST['Password'];
    $Username=$_POST['Username'];
    $Email=$_POST['Email'];
    //$Birthday=$_POST['Birthday'];

    require_once('config.php');

        $sql = "SELECT * FROM UserInfo WHERE Email = '$Email'";
        $check = mysqli_fetch_array(mysqli_query($con,$sql));

        if(isset($check)){
//           // $response["Userid"] = "error1";
//            $response["Password"] = "error1";
//            $response["Username"] = "error";
//            $response["Email"] = "error";
//            //$response["Birthday"] = "error";
//            $errMSG = json_encode($response);
//            echo $errMSG;
            $response["value"] = 0;
            $response["message"] = "이미 등록된 이메일입니다";
            echo json_encode($response);
        }else{
            $sql = "INSERT INTO UserInfo (Password,Username,Email) VALUES ('$Password','$Username','$Email')";
            if(mysqli_query($con,$sql)){
//               // $response["Userid"] = $Userid;
//                $response["Password"] = $Password;
//                $response["Username"] = $Username;
//                $response["Email"] = $Email;
//              //  $response["Birthday"] = $Birthday;
//                $successMSG = json_encode($response);
//                echo $successMSG;
                $response["value"] = 1;
                $response["message"] = "회원가입 성공";
                echo json_encode($response);
            }else{
//               // $response["Userid"] = "error2";
//                $response["Password"] = "error2";
//                $response["Username"] = "error";
//                $response["Email"] = "error";
//              //  $response["Birthday"] = "error";
//                $errMSG = json_encode($response);
//                echo $errMSG;
                $response["value"] = 0;
                $response["message"] = "회원가입 실패";
                echo json_encode($response);
            }
        }

        mysqli_close($con);
    }else{
//       // $response["Userid"] = "error3";
//        $response["Password"] = "error3";
//        $response["Username"] = "error";
//        $response["Email"] = "error";
//      //  $response["Birthday"] = "error";
//        $errMSG = json_encode($response);
//        echo $errMSG;
    $response["value"] = 0;
    $response["message"] = "서버연결 실패";
    echo json_encode($response);
    }



?>