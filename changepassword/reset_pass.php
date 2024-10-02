<?php
include "../connect.php";


$username = filterRequest("username");
$oldpass = filterRequest("oldpassword");
$newpass = filterRequest("newpassword");
$confirmpass = filterRequest("confirmpassword");


// Vérifier que tous les paramètres sont fournis
if (empty(trim($username)) || empty(trim($oldpass)) || empty(trim($newpass)) || empty(trim($confirmpass))) {
    echo json_encode(array("status" => "error", "message" => "Tous les champs sont obligatoires."));
    exit;
}

// Vérifier si les nouveaux mots de passe correspondent
if ($newpass !== $confirmpass) {
    
        echo json_encode(array("status" => "error", "message" => "Les nouveaux mots de passe ne correspondent pas."));
        exit;
    
}

// Vérification de l'ancien mot de passe
$stmt = $con->prepare("SELECT * FROM users WHERE username = ? and password = ?");
$stmt->execute(array($username, $oldpass)); // Assume que les mots de passe sont stockés en clair pour cet exemple

$count = $stmt->rowCount();

if ($count > 0) {
    
    // Mettre à jour le mot de passe dans la base de données
    $updateStmt = $con->prepare("UPDATE users SET password = ? WHERE username = ?");
    $updateStmt->execute(array($newpass, $username));

    // Update First_connection to false
    $updateFirstConnectionStmt = $con->prepare("UPDATE users SET First_connection = true WHERE username = ?");
    $updateFirstConnectionStmt->execute(array($username));
    $data = $updateFirstConnectionStmt-> fetch(PDO::FETCH_ASSOC);
    //echo $data;
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "error", "message" => "L'ancien mot de passe est incorrect."));
}
?>
