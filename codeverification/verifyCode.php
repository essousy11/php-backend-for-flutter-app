<?php

include "../connect.php";

$verifyCode = filterRequest("verify_code");
$username = filterRequest("username");

$stmt = $con->prepare("select * from users where username = '$username' and verify_code='$verifyCode'");
$stmt->execute();


$count = $stmt->rowCount();
if($count > 0){
    echo json_encode(array("status" => "success"));
}else{
    echo json_encode(array("status" => "fail"));
}