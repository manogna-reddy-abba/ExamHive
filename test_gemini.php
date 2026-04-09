<?php
$apiKey = "AIzaSyAXdA0Gyhm9Bwi_iBGoqrL5_J_Eh7dz9eU"; // <--- PASTE YOUR KEY HERE

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;
// $data = array("contents" => array(array("parts" => array(array("text" => "Say the word 'Hello'")))));

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// THESE TWO LINES FIX THE MAC SSL BLOCK
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "<h3 style='color:red;'>Connection Failed! Reason: $error</h3>";
} else {
    echo "<h3 style='color:green;'>Connection Successful! Gemini says:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}
?>