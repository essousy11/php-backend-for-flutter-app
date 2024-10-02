<?php

include "../connect.php";

$userId = filterRequest("user_id");
$moduleID = filterRequest("module_id");
$moduleGtwID = filterRequest("module_gtw_id");
$henhouseId = filterRequest("henhouse_id");
$date = filterRequest("date");

$stmt = $con->prepare("INSERT INTO module (user_id, module_id, module_gtw_id, henhouse_id, `date`, `status` ) VALUES (?, ?, ?, ?, ?, 0)");

// Execute the statement with the provided values
$stmt->execute(array($userId, $moduleID, $moduleGtwID, $henhouseId, $date));

$count = $stmt->rowCount();
if($count > 0){
    
    $insertInModuleData = $con->prepare("INSERT INTO module_data (`date`, module_id, module_gtw_id_sender) VALUES (?, ?, ?)");
    $insertInModuleData->execute(array($date,$moduleID, $moduleGtwID));
    echo json_encode(array("status" => "success"));
}else{
    echo json_encode(array("status" => "fail"));
}




// INSERT INTO `module_data` 
// (`id`, `date`, `module_id`, `type`, `weight`, `voltage`, `baterry_life`, `batery_level`, `health`, `humidity`, `temperature`, `pressure`, `gas_resistance`, `status_bme`, `gas_index`, `meas_index`, `stability`, `ax`, `ay`, `az`, `gx`, `gy`, `gz`, `module_gtw_id_sender`) 
// VALUES ('7', '2024-07-14', 'M001', 'sensor1', '19.75', '1.3', '34', '2.3', 'good', '11', '3.45', '12.4753', '98.476', '0', '12.47', '5.7', '1', '1.2', '2.3', '3.4', '4.5', '5.6', '6.7', 'AZERTY');


// To insert and let others null
// INSERT INTO `module_data` (
//     `id`, 
//     `date`, 
//     `module_id`, 
//     `module_gtw_id_sender`
// ) VALUES (
//     '7',                          -- id
//     '2024-07-14',                 -- date
//     'M001',                       -- module_id
//     'AZERTY'                      -- module_gtw_id_sender
// );
