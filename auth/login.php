<?php

include "../connect.php";

//$id = filterRequest("id");
$username = filterRequest("username");
$password = filterRequest("password");

//$stmt = $con->prepare("select * from users where id=? and password=? and username=?");
$stmt = $con->prepare("select * from users where `password`=? and `username`=?");

$stmt->execute(array($password, $username));

$data = $stmt-> fetch(PDO::FETCH_ASSOC);

$count = $stmt-> rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success", "data" => $data));
    //echo json_encode(array("status" => "success"));
    
}else {
    echo json_encode(array("status" => "fail"));
}