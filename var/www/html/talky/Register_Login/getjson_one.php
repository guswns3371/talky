<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');
//$email = $_POST['Email'];
$email = $_GET['Email'];

$stmt = $con->prepare("select * from UserInfo where Email = '$email'");
$stmt->execute();

if ($stmt->rowCount() > 0)
{


    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

       $data =
            array(
                'idx'=>$idx,
                'Email'=>$Email,
                'Password'=>$Password,
                'Username'=>$Username,
                'Photo'=>$Photo,
                'Token'=>$Token,
                'Birthday'=>$Birthday,
                'Introduce'=>$Introduce

            );
    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
}

?>