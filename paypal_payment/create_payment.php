<?php
// require 'paypal_config.php';

// use PayPal\Api\Payer;
// use PayPal\Api\Amount;
// use PayPal\Api\Transaction;
// use PayPal\Api\RedirectUrls;
// use PayPal\Api\Payment;


// // Set up payment details
// $payer = new Payer();
// $payer->setPaymentMethod('paypal');

// $amount = new Amount();
// $amount->setTotal($price);
// $amount->setCurrency('USD');

// $transaction = new Transaction();
// $transaction->setAmount($amount);
// $transaction->setDescription('Payment description');

// $redirectUrls = new RedirectUrls();
// $redirectUrls->setReturnUrl('http://localhost:3000/xampp/htdocs/php_traitement/paypal_payment/execute_payment.php?success=true')
//              ->setCancelUrl('http://localhost:3000/xampp/htdocs/php_traitement/paypal_payment/execute_payment.php?success=false');

// $payment = new Payment();
// $payment->setIntent('sale')
//         ->setPayer($payer)
//         ->setTransactions([$transaction])
//         ->setRedirectUrls($redirectUrls);

// try {
//     $payment->create($paypal);
// } catch (Exception $ex) {
//     die($ex);
// }

// $approvalUrl = $payment->getApprovalLink();

// header("Location: {$approvalUrl}");

//////////////////////////////////////
///////////////////////////////////////
/////////////////////////////////////

require 'paypal_config.php';

use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $planId = filterRequest('plan_id');
    $userId = filterRequest('user_id');
    

    // Set payment details based on planId
    $amount = $_POST['price'];; // Example amount
    $currency = 'USD';
    $description = 'Payment for Plan ' . $planId;
    $returnUrl = 'http://localhost:3000/xampp/htdocs/php_traitement/paypal_payment/execute_payment.php?success=true&user_id=' . $userId;
    $cancelUrl = 'http://localhost:3000/xampp/htdocs/php_traitement/paypal_payment/execute_payment.php?success=false&user_id=' . $userId;

    $paypal = getPayPalApiContext();

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    $amountObj = new Amount();
    $amountObj->setTotal($amount);
    $amountObj->setCurrency($currency);

    $transaction = new Transaction();
    $transaction->setAmount($amountObj);
    $transaction->setDescription($description);

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($returnUrl)
                 ->setCancelUrl($cancelUrl);

    $payment = new Payment();
    $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

    try {
        $payment->create($paypal);
        $approvalUrl = $payment->getApprovalLink();
        echo $approvalUrl; // Send the approval URL back to the client
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

