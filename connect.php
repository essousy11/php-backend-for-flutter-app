<?php

$dsn = "mysql:host=localhost;dbname=revofeed1";
$user = "root";
$pass = "";

try {
    $con = new PDO($dsn, $user, $pass);
    include "functions.php";
    //$con-> setAttribute(PDO::ATTR_ARRMDE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo $e->getMessage();
}