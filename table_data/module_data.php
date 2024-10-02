<?php
include '../connect.php';
// Function to fetch data from tables
$user_id = filterRequest("user_id");
$place = filterRequest("place");


ModuleCardData($user_id, $place, $con);



?>
