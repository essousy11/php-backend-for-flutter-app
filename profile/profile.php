<?php
include "../connect.php";



$userId = filterRequest("user_id");
$name = filterRequest("name");
$email = filterRequest("email");
$num_phone = filterRequest("N_telephone");
$address = filterRequest("address");



// Vérification de l'ancien mot de passe
$stmt = $con->prepare("SELECT * FROM users WHERE user_id = ? ");
$stmt->execute(array($userId));

$count = $stmt->rowCount();

if ($count > 0) {
    
    // Mettre à jour le mot de passe dans la base de données
    $updateStmt = $con->prepare("UPDATE users SET name = ?, email = ?, N_telephone = ?, address = ?   WHERE user_id = ?");
    $updateStmt->execute(array($name, $email, $num_phone, $address, $userId));
    // Metrre à jour le nouveau code de vérification 
    $data = $updateStmt-> fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "error", "message" => "Error"));
}
?>
