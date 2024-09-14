<html>
<script>
//you will Have to set the cooldown that you want in 2 Different places. one right below here and the other on line 50
var cooldownTime = 1200;


// This checks when the last faucet submit was in order to see how long the cooldown is 
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
<title>Kats Duco Faucet🐱</title>
<h1>Get free DUCOS!</h1>
<h2>Enter your username below to get freeee Duinocoin! Claim DUCO Every 30 minutes!</h2>


// 
<form action="" method="post" onsubmit="return validateForm()">
    <label for="username">Enter your username:</label>
    <input type="text" id="username" name="username" required>
    <input type="submit" value="Get DUCOS">
</form>
<h3>Join our discord Server!</h3>
<h4>When you join Make sure to thank Elapt1c for hosting this faucet!</h4>

<h5>if you want a copy of this faucet for a template DM Kat on Discord!</h5>
<a href="https://discord.gg/HUbHqUQUD2">Clickhere!</a>
<h4> Ignore this wacky stuff down here. only important thing is the "transaction successful that SHOULD show up down there under all the junk.</h4>


</body>
</html>


<?php
// Start the session
session_start();

// Here is the second place to make sure to set the cooldown.
$cooldownTime = 1200;

// Check if the cooldown list is set in the session
if (!isset($_SESSION['cooldownList'])) {
    $_SESSION['cooldownList'] = array();
}

// Remove expired usernames from the cooldown list
foreach ($_SESSION['cooldownList'] as $username => $timestamp) {
    if (time() - $timestamp > $cooldownTime) {
        unset($_SESSION['cooldownList'][$username]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    // Check if the username is in the cooldown list
    if (array_key_exists($username, $_SESSION['cooldownList'])) {
        echo "Please wait for the cooldown period to expire.";
        exit;
    }

    // Send the request to the Duinocoin API
  //Enter your credits here 
    $username = "YOUR_USERNAME_HERE";
    $password = "ENTER_YA_PASSWORD";
    $recipient = $_POST["username"];
    $memo = "Faucet Claim!";
    // Enter the amount of duco you want the claim to be here
    $amount = "2";

    $url = "https://server.duinocoin.com/transaction/?username=$username&password=$password&recipient=$recipient&amount=$amount&memo=$memo";

    $response = file_get_contents($url);

    if ($response === false) {
        echo "Error: Failed to send transaction request.";
    } else {
        $transactionData = json_decode($response, true);

        if ($transactionData['success']) {
            echo "Transaction successful." . $transactionData['transaction'];
            // Update the last submit time in the session
            $_SESSION['lastSubmitTime'] = time();
            // Store the last submit time in local storage for client-side validation
            echo '<script>localStorage.setItem("lastSubmitTime", ' . time() . ');</script>';
        } else {
            echo "Error: Transaction failed. Reason: " . $transactionData['error'];
        }
    }

    // After successful transaction, add the username to the cooldown list
    $_SESSION['cooldownList'][$username] = time();
}
?>
