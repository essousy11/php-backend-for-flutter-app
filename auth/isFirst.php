<?php

include "../connect.php";

$userId = filterRequest("user_id");
$username = filterRequest("username");

$stmt = $con->prepare("SELECT First_connection FROM users WHERE user_id=? AND username=?");
$stmt->execute(array($userId, $username));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    if ($row['First_connection'] != 1) { // Assuming First_connection is boolean or integer in database
        echo json_encode(array("status" => "success", "message" => "First connection is true"));
    } else {
        echo json_encode(array("status" => "fail", "message" => "First connection is false"));
    }
} else {
    echo json_encode(array("status" => "fail"));
}
?>
