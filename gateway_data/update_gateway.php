<?php

include "../connect.php";

$userId = filterRequest("user_id");
$moduleGtwID = filterRequest("module_gtw_id");
$GtwID = filterRequest("gwt_id");

$long = filterRequest("longitude");
$lat = filterRequest("latitude");
$sim = filterRequest("num_Sim");

$stmt = $con->prepare("select * from gateway where user_id =? and module_gtw_id =?");
$stmt->execute(array($userId, $moduleGtwID));


// $count = $stmt->rowCount();
// if($count > 0){
//     $data = $stmt-> fetchAll(PDO::FETCH_ASSOC);
//     $upd = $con->prepare("update gateway set longitude=? , latitude=?, num_Sim=? where module_gtw_id =?");
//     $upd->execute(array($long, $lat, $sim, $moduleGtwID));
//     echo json_encode(array("status" => "success", "data" => $data));
// }else{
//     echo json_encode(array("status" => "fail"));
// }

$count = $stmt->rowCount();
if($count > 0){
    $data = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $upd = $con->prepare("update gateway set num_Sim=?, gwt_id =? where module_gtw_id =?");
    $upd->execute(array($sim,$GtwID, $moduleGtwID));
    echo json_encode(array("status" => "success", "data" => $data));
}else{
    echo json_encode(array("status" => "fail"));
}