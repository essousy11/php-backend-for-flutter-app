<?php

include "../connect.php";

$userId = filterRequest("user_id");
$endDate = filterRequest("end_date");
$startDate = filterRequest("start_date");
//$choix = filterRequest("choix");

$stmt = $con->prepare("SELECT md.weight, md.temperature, md.humidity, md.date
FROM module_data md
JOIN module m ON md.module_id = m.module_id
JOIN USERS u ON m.user_id = u.user_id
WHERE u.user_id = ? and md.date between ? and ?
");
$stmt->execute(array($userId, $startDate, $endDate));


$count = $stmt->rowCount();
if($count > 0){
    $data = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
}else{
    echo json_encode(array("status" => "fail"));
}