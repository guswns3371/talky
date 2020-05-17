<?php
$db = new mysqli("localhost","root","Wnsgus123*","test");
$db->set_charset("utf8mb4");
function mq($sql)
{
    global $db;
    return $db->query($sql);
}

require_once('config.php');



if($_SERVER['REQUEST_METHOD']== 'POST'){
    $response = array();


    $count = $_POST['count'];
    $useridx = $_POST['MY_IDX'];
    $username = $_POST['MY_NAME'];
    $content = $_POST['content'];
    $content = addslashes($content);
    $location = $_POST['location'];
    $time = $_POST['time'];
    error_log("posts ".$count."_".$useridx."_".$username."_".$time);
    $target_dir = "/var/www/html/talky/uploads/socialupload/";

    $file = "file";
    $filter = $_POST["filter"];//제이슨 어레이
    $mime = $_POST["mime"];//제이슨 어레이

    //error_log("1_filter ".$filter);
    $filter = json_decode($filter,true);
    $mime = json_decode($mime,true);
    //error_log("2_filter ".json_encode($filter["values"][0]["nameValuePairs"]));
    $filter = $filter["values"][0]["nameValuePairs"]; //ok 성공여기까진
    $mime = $mime["values"][0]["nameValuePairs"]; //ok 성공여기까진
    // error_log("3_filter ".$filter);
    foreach ($filter as $key => $value){
        //error_log("4_filter ".$key."_ ".$value);
    }//ok 성공
    foreach ($mime as $key => $value){
        error_log("4_mime ".$key."_ ".$value);
    }//ok 성공

    error_log("1_count ". $count);
        if (true){// 이미지 여러개 또는 한개
            global $file,$filter,$mime;
            $path_list = array();
            $isMoved = true;

            error_log("2_count ".(int)$count);
            for ($i=0;$i<(int)$count;$i++){

                global $isMoved,$target_dir,$path_list;
                $filename = $file.$i;
                error_log("filter ".$i."_".$filter[$filename]);
                $mime[$filename] = substr($mime[$filename], 0, strpos($mime[$filename], "/"));
                error_log("mime ".$i."_".$mime[$filename]);
                //error_log("filename ".$i."_".$filename);
                $filename_2 = "";
                $filename_2 = $useridx."_".$time."_".$filter[$filename]."_".$_FILES[$filename]["name"];

                if(strpos($filename_2,"'") !== false){
                    $filename_2 = str_replace("'","",$filename_2); //문자열에 ' 문자가 있으면 mysql 신택스 문제가 생긴다
                }
                $target_dir2 = $target_dir .basename($filename_2);
                if(move_uploaded_file($_FILES[$filename]["tmp_name"],$target_dir2)){
                    error_log("move_uploaded_file_SUCCESS ".$i." Success");
                }else{
                    error_log("move_uploaded_file_SUCCESS ".$i." Failed");
                    $isMoved = false;
                }

                $img = "/talky/uploads/socialupload/".$filename_2;


                $path_list[] =  array(
                    $img=>array($filter[$filename] , $mime[$filename]."")
                );/** addslash 해도 안됨*/
            }
            $converted_isMoved = ($isMoved) ? 'true' : 'false';
            error_log("isMoved_".$converted_isMoved);
            error_log("path_list ".json_encode($path_list));

            if ($isMoved === true){
                global $path_list;
                $path_list= json_encode($path_list);


//                $sql2 = "INSERT INTO
//                SocialHistory
//                (Social_userdix,Social_username,Social_time,Social_imagepath_list,Social_content,Social_location)
//                VALUES
//                ('$useridx','$username','$time','$path_list','$content','$location')
//                ";
                if (count($path_list) != 0){
                    $sql = "INSERT INTO 
                SocialHistory 
                (Social_useridx,Social_username,Social_time,Social_imagepath_list,Social_content) 
                VALUES 
                ('$useridx','$username','$time','$path_list','$content')
                ";
                    if(mysqli_query($con,$sql)){
                        $value  = 1;
                        $message = "multiple image upload success";
                        $social_useridx = $useridx;
                        $social_username = $username;
                        $social_time = $time;
                        $social_imagepath_list = $path_list;
                        $social_location = $location;
                        $social_content = $content;
                    }else{
                        error_log("err ".mysqli_error($con));
                        $value  = 0;
                        $message = "multiple image upload failed ".mysqli_error($con);
                        $social_useridx = "null";
                        $social_username = "null";
                        $social_time = "null";
                        $social_imagepath_list = "null";
                        $social_location = "null";
                        $social_content = "null";
                    }
                }

            }else{
                $value  = 0;
                $message = "multiple move_uploaded_file  failed";
                $social_useridx = "null";
                $social_username = "null";
                $social_time = "null";
                $social_imagepath_list = "null";
                $social_location = "null";
                $social_content = "null";
            }

        }
        /**  */
    mysqli_close($con);
}else{
    $value  = 0;
    $message= "server connection fail";
    $social_useridx = "null";
    $social_username = "null";
    $social_time = "null";
    $social_imagepath_list = "null";
    $social_location = "null";
    $social_content = "null";
}


$response["value"] = $value;
$response["message"] = $message;
$response["Social_useridx"] = $social_useridx;
$response["Social_username"] = $social_username;
$response["Social_content"] = $social_content;
$response["Social_time"] = $social_time;
$response["Social_location"] = $social_location;
$response["Social_imagepath_list"] = $social_imagepath_list;
echo json_encode($response);
?>