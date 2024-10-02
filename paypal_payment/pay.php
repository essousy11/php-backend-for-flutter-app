<?php
header('Content-Type: application/json');
include '../connect.php';

// Configuration PayPal
$clientId = 'AS97aTfO38WuAejQGNkawFv93ydYpOPwsMJ1O0weEFSy3PDHJWQqF43o3aVZKcYteSVsNiPVeWmFaSKq';
$clientSecret = 'EGS-mbo92F1Tq55feM64_q-7TTyI5Coohnxm4AtVBJgLD-9oCg-KNdIAZ_wf4HDmbevGqcKE-kvLaKVA';
$url = "https://api.sandbox.paypal.com/v1/payments/payment";

// $plan = $_POST['plan'];
// $amount = $_POST['amount'];
// $user_id = $_POST['user_id'];

$plan = filterRequest('plan');
$amount = filterRequest('amount');
$user_id = filterRequest('user_id');

$paymentType = 'paypal';
$datePayment = date('Y-m-d');
$dateExpired = $plan === 'monthly' ? date('Y-m-d', strtotime('+1 month')) : date('Y-m-d', strtotime('+1 year'));

// Créer le paiement avec PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Basic " . base64_encode("$clientId:$clientSecret")
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'intent' => 'sale',
    'payer' => [
        'payment_method' => 'paypal'
    ],
    'transactions' => [
        [
            'amount' => [
                'total' => $amount,
                'currency' => 'USD'
            ],
            'description' => $plan . ' plan payment'
        ]
    ],
    'redirect_urls' => [
        'return_url' => 'http://localhost/php_traitement/paypal_payment/success.php',
        'cancel_url' => 'http://localhost/php_traitement/paypal_payment/cancel.php'
    ]
]));

$result = curl_exec($ch);
curl_close($ch);

// Insertion dans la base de données après le succès du paiement
// $db = new mysqli('your_database_host', 'your_database_user', 'your_database_password', 'your_database_name');

// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// $stmt = $con->prepare("INSERT INTO payment (payment_plan, payment_type, user_id, date_payment, date_expired) VALUES (?, ?, ?, ?, ?)");
// $stmt->bind_param("ssiss", $plan, $paymentType, $user_id, $datePayment, $dateExpired);

// Préparer la requête
$stmt = $con->prepare("UPDATE payment SET payment_plan = :payment_plan, payment_type = :payment_type, date_payment = :date_payment, date_expired = :date_expired WHERE user_id = :user_id");

// Lier les paramètres
$stmt->bindParam(':payment_plan', $plan);
$stmt->bindParam(':payment_type', $paymentType);
$stmt->bindParam(':date_payment', $datePayment);
$stmt->bindParam(':date_expired', $dateExpired);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);



///////////////////////////////////////////

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

// $stmt->close();
// $con->close();
?>
