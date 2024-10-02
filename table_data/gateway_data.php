<?php
include '../connect.php';
// Function to fetch data from tables
$user_id = filterRequest("user_id");

getUserGatewayData($user_id, $con);

// Example usage:
// $user_id = "your_user_id_here";
// $userData = getUserGatewayData($user_id);

// // Output the data (you can format this as per your needs)
// if (!empty($userData)) {
//     foreach ($userData as $row) {
//         echo "Place: " . $row['place'] . ", Henhouse Count: " . $row['nb_henhouse'] . ", Total Weight: " . $row['total_weight'] . "<br>";
//     }
// } else {
//     echo "No data found.";
// }
?>
