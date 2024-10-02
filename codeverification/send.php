<?php

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Twilio\Rest\Client;

require __DIR__ . "/vendor/autoload.php";
include "../connect.php";


$username = filterRequest("username");

$stmt = $con->prepare("SELECT N_telephone FROM users WHERE username = :username");
// Bind the parameter
$stmt->bindParam(':username', $username, PDO::PARAM_STR);

// Execute the query
$stmt->execute();

// Fetch the result
$number = $stmt->fetch(PDO::FETCH_ASSOC);
function generateVerificationCode() {
    return mt_rand(10000, 99999);
}
function sendVerificationCode($numberphone, $codeverification) {
    $message = "Your verification code is: " . $codeverification;
        $base_url = "https://your-base-url.api.infobip.com";
        $api_key = "a96f0db264d748904dbf61f8ca72a69c-cdc7aa1d-5119-4a2d-8d19-080b0b3f2704";

        $configuration = new Configuration(host: $base_url, apiKey: $api_key);

        $api = new SmsApi(config: $configuration);

        $destination = new SmsDestination(to: $numberphone);

        $smsMessage = new SmsTextualMessage(
            destinations : [$destination],
            text : $message,
            from : "daveh",
        );

        $request = new SmsAdvancedTextualRequest(
            messages : [$smsMessage],
        );

        try {
            $response = $api->sendSmsMessage($request);
            echo "code de verification ".$codeverification." au numero de tele ".$numberphone;
        } catch (Exception $e) {
            echo "Error sending message via Infobip: " . $e->getMessage();
        }
    }



// Check if a number was found
if ($number) {
    // Access the telephone number
    $telephoneNumber = $number['N_telephone'];
    //echo "Telephone number found: $telephoneNumber";
    $verificationCode = generateVerificationCode();

    // Prepare the SQL statement to insert verification code
    $stmt_insert = $con->prepare("UPDATE users SET verify_code = :verifycode WHERE username = :username");

    // Bind parameters for insert statement
    $stmt_insert->bindParam(':verifycode', $verificationCode, PDO::PARAM_INT);
    $stmt_insert->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute the insert query
    $stmt_insert->execute(array(
        ":verifycode" =>  $verificationCode,
        ":username" => $username
    ));
    sendVerificationCode($telephoneNumber, $verificationCode);
    if(sendVerificationCode($telephoneNumber, $verificationCode)){
        echo json_encode(array("status" => "success", "message" => "Le code est envoyé"));
    }else{
        echo json_encode(array("status" => "fail", "message" => "Le code n' est pas envoyé"));
    }

    //echo "Verification code '$verificationCode' inserted for username '$username'.\n";
} else {
    echo "No telephone number found for username '$username'.\n";
}

//if ($_POST["provider"] === "infobip") {


    





//} else {   // Twilio

    // $account_id = "your account SID";
    // $auth_token = "your auth token";

    // $client = new Client($account_id, $auth_token);

    // $twilio_number = "+ your outgoing Twilio phone number";

    // $client->messages->create(
    //     $number,
    //     [
    //         "from" => $twilio_number,
    //         "body" => $message
    //     ]
    // );

//}

//echo "Message sent.";