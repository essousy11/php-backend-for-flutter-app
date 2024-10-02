<?php
// require 'paypal_config.php';

// use PayPal\Api\Payment;
// use PayPal\Api\PaymentExecution;



// if (isset($_GET['success']) && $_GET['success'] == 'true') {
//     $paymentId = $_GET['paymentId'];
//     $payerId = $_GET['PayerID'];

//     $payment = Payment::get($paymentId, $paypal);

//     $execution = new PaymentExecution();
//     $execution->setPayerId($payerId);

//     try {
//         $result = $payment->execute($execution, $paypal);
//         echo "Payment successful. Transaction ID: " . $result->getId();
//     } catch (Exception $ex) {
//         die($ex);
//     }
// } else {
//     echo "User cancelled the payment.";
// }



////////////////////////////////
//////////////////////////////////////////
////////////////////////////////////////////////////
require 'paypal_config.php';
//require '../connect.php'; // Include your database connection

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

//$userId = filterRequest("user_id");
// $payment_plan = filterRequest("payment_plan");
// $payment_type = filterRequest("payment_type");
// $date_payment = filterRequest("date_payment");
// $username = filterRequest("username");



if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $paymentId = $_GET['paymentId'];
    $payerId = $_GET['PayerID'];
    $userId = $_GET['user_id'];

    $paypal = getPayPalApiContext();
    $payment = Payment::get($paymentId, $paypal);

    $execution = new PaymentExecution();
    $execution->setPayerId($payerId);

    try {
        $result = $payment->execute($execution, $paypal);

        // Payment successful, save details to the database
        $sql = "INSERT INTO payment (payment_plan, payment_type, user_id, date_payment, date_expired) 
                VALUES (:payment_plan, :payment_type, :user_id, :date_payment, :date_expired)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':payment_plan' => $payment_plan, // Replace with actual plan name
            ':payment_type' => $payment_type,
            ':user_id' => $userId,
            ':date_payment' => date('Y-m-d'),
            ':date_expired' => date('Y-m-d', strtotime('+9 month')),
        ]);

        //header("Location: http://your-flutter-app.com/payment-success"); // Redirect to Flutter success page

        echo json_encode(array("status" => "success"));
    } catch (Exception $ex) {
        //header("Location: http://your-flutter-app.com/payment-failure"); // Redirect to Flutter failure page

        echo json_encode(array("status" => "error"));
    }
} else {
    //header("Location: http://your-flutter-app.com/payment-failure"); // Redirect to Flutter failure page
    echo json_encode(array("status" => "error"));
}
