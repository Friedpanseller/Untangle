<?php
    require 'sqlDetails.php';

    $sql = new sqlDetails;

    $userIP = $_SERVER['REMOTE_ADDR'];
    $userBrowser = $_POST["userBrowser"];

    $sessionID = $_POST['sessionID'];
    $sessionID = openssl_digest("ASaltyBlueberryBagel" . $userBrowser . $sessionID . $userIP . "ASaltyBlueberryBagel", 'sha512');

    // Create connection
    $link = new PDO("mysql:host=$sql->server;dbname=$sql->database", $sql->username, $sql->password);
    $stmt = $link->prepare("UPDATE users SET session = NULL WHERE session=?;");
    $stmt->bindParam(1,$sessionID);

    if($stmt->execute()) {
        echo "S|Successfully logged out";
    } else {
        echo "E|SQL Statement failed to execute";
    }
?>