<?php

include "connect.php";

$stmt = $con->prepare("select * from users where First_connection= true");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$myJSON = json_encode($users);
print_r($myJSON);