<?php
include "../connect.php";


// Vérification de l'ancien mot de passe
$stmt = $con->prepare("SELECT * FROM price WHERE date order by date desc limit 1");
$stmt->execute();

$count = $stmt->rowCount();

if ($count > 0) {
    
    
    // Metrre à jour le nouveau code de vérification 
    $data = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "error", "message" => "Error"));
}
?>
