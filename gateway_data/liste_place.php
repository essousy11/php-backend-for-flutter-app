<?php

include "../connect.php";

$userId = filterRequest("user_id");

$stmt = $con->prepare("select * from gateway where user_id =?");
$stmt->execute(array($userId));


$count = $stmt->rowCount();
if($count > 0){
    $data = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
}else{
    echo json_encode(array("status" => "fail"));
}