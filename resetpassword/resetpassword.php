<?php
include "../connect.php";



$username = filterRequest("username");
$newpass = filterRequest("newpassword");
$confirmpass = filterRequest("confirmpassword");
$verifyCode = filterRequest("verify_code");

/////////////////

// +Select password,N_telephone,user_id,First_connection from USER when user_name=USER NAME
// +If user_name exist, send code to N_telephone
// +Send password & username to the next interface 


// Vérifier que tous les paramètres sont fournis
if (empty(trim($username)) || empty(trim($newpass)) || empty(trim($confirmpass)) || empty(trim($verifyCode)) ) {
    echo json_encode(array("status" => "error", "message" => "Tous les champs sont obligatoires."));
    exit;
}

// Vérifier si les nouveaux mots de passe correspondent
if ($newpass !== $confirmpass) {
    
        echo json_encode(array("status" => "error", "message" => "Les nouveaux mots de passe ne correspondent pas."));
        exit;
    
}

// Vérification de l'ancien mot de passe
$stmt = $con->prepare("SELECT * FROM users WHERE username = ? ");
$stmt->execute(array($username)); // Assume que les mots de passe sont stockés en clair pour cet exemple

$count = $stmt->rowCount();

if ($count > 0) {
    
    // Mettre à jour le mot de passe dans la base de données
    $updateStmt = $con->prepare("UPDATE users SET password = ? WHERE username = ?");
    $updateStmt->execute(array($newpass, $username));
    // Metrre à jour le nouveau code de vérification 
    $query = $con->prepare("select * from users where username = ? and verify_code= ?");
    $query->execute(array($username, $verifyCode));
    $data = $query-> fetch(PDO::FETCH_ASSOC);
    // Update First_connection to false
    // $updateFirstConnectionStmt = $con->prepare("UPDATE users SET First_connection = true WHERE user_id = ?");
    // $updateFirstConnectionStmt->execute(array($userId));
    // $data = $updateFirstConnectionStmt-> fetch(PDO::FETCH_ASSOC);
    //echo $data;
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "error", "message" => "L'ancien mot de passe est incorrect."));
}
?>
