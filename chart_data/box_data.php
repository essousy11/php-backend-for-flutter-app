<?php

include "../connect.php";

$userId = filterRequest("user_id");
$moduleGtwId = filterRequest("module_gtw_id");


//$endDate = filterRequest("end_date");
//$startDate = filterRequest("start_date");
//$choix = filterRequest("choix");

// $stmt = $con->prepare("SELECT md.weight, md.temperature, md.humidity, md.date
// FROM module_data md
// JOIN module m ON md.module_id = m.module_id
// JOIN users u ON m.user_id = u.user_id
// WHERE u.user_id = ?
//   AND md.date >= CURDATE()
//   AND md.date < CURDATE() + INTERVAL 1 DAY;

// ");
$stmt= $con-> prepare("SELECT m.module_id, m.henhouse_id, md.weight, md.temperature, md.humidity
FROM module m
JOIN (
    SELECT md_inner.module_id, md_inner.weight, md_inner.temperature, md_inner.humidity
    FROM module_data md_inner
    WHERE md_inner.module_id IN (
        SELECT m_inner.module_id
        FROM module m_inner
        JOIN users u ON m_inner.user_id = u.user_id
        WHERE u.user_id = ?
    )
    ORDER BY md_inner.date DESC
    LIMIT 1
) md ON m.module_id = md.module_id
JOIN users u ON m.user_id = u.user_id
WHERE u.user_id = ?
  AND m.module_gtw_id = ?;

");
$stmt->execute(array($userId, $userId, $moduleGtwId));


$count = $stmt->rowCount();
if($count > 0){
    $data = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
}else{
    echo json_encode(array("status" => "fail"));
}