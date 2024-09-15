<html>
<head>

</head>
<script>
// Set the cooldown time in seconds
var cooldownTime = 7200;

// Function to check if the form can be submitted
function validateForm() {
    var currentTime = new Date().getTime() / 1000;
    var lastSubmitTime = localStorage.getItem('lastSubmitTime');

    if (lastSubmitTime && currentTime - lastSubmitTime < cooldownTime) {
        var remainingTime = Math.ceil(cooldownTime - (currentTime - lastSubmitTime));
        alert("Please wait for " + remainingTime + " seconds before claiming again.");
        return false;
    }

    return true;
}
</script>
<body>
<title>KatFaucet🐱</title>
<link rel="stylesheet" type="text/css" href="dark-mode.css">
<h1>Katfaucet😻</h1>
<h2>Enter your username below to get 2 free Duinocoin Every 2 Hours!</h2>

<img src="https://catfaucet.alwaysdata.net/photos/cat4.jpg" alt="Cute cat!">

<img src="https://catfaucet.alwaysdata.net/photos/cat.jpg" alt="Cute cat!">

<form action="" method="post" onsubmit="return validateForm()">
    <label for="username">Enter your username:</label>
    <input type="text" id="username" name="username" required>
    <input type="submit" value="Get DUCOS">
</form>

<?php
// File path to store the cooldown data
$cooldownFile = 'cooldown.txt';
// Initialize the cooldown data array
$cooldownData = array();
$cooldownTime = 7200;
// Read the cooldown data from the file if it exists
if (file_exists($cooldownFile)) {
    $cooldownData = json_decode(file_get_contents($cooldownFile), true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "katfaucet";
    $recipient = $_POST["username"];
    $password = "Your_Password_here";
    $memo = "Faucet CLAIM";
    //set the amount
    $amount = "2";
    $currentTime = time(); // Get the current time
     // Check if the cooldown data array is not null
     if ($cooldownData !== null) {
        // Check if the recipient's username is in the cooldown list
        if (array_key_exists($recipient, $cooldownData) && $currentTime - $cooldownData[$recipient] < $cooldownTime) {
            echo "Please wait for the cooldown period to expire.";
            exit;
        }
    }

   
    // Your existing code for sending transaction request...

    $url = "https://server.duinocoin.com/transaction/?username=$username&password=$password&recipient=$recipient&amount=$amount&memo=$memo";

    $response = file_get_contents($url);

    if ($response === false) {
        echo "Error: Failed to send transaction request.";
    } else {
        $transactionData = json_decode($response, true);

        if (isset($transactionData['success']) && $transactionData['success']) {
            echo "Transaction successful." . $transactionData['transaction'];
            // Update the last submit time in the cooldown data
            $cooldownData[$recipient] = $currentTime;
            // Save the updated cooldown data to the file
            file_put_contents($cooldownFile, json_encode($cooldownData));
        } else {
            echo "Error: Transaction failed. Reason: " . (isset($transactionData['error']) ? $transactionData['error'] : 'Unknown');
        }
    }
}
?>
<h2> If you want to donate send ducos to katfaucet!</h2>
</body>
</html>
