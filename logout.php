<?php
    $DBservername = "localhost";
    $DBusername = "friedpan_admin";
    $DBpassword = "0*0*Leon";
    $DBname = "friedpan_disentangle";

    $userIP = $_SERVER['REMOTE_ADDR'];
    $userBrowser = $_POST["userBrowser"];

    $sessionID = $_POST['sessionID'];
    $sessionID = openssl_digest("ASaltyBlueberryBagel" . $userBrowser . $sessionID . $userIP . "ASaltyBlueberryBagel", 'sha512');

    $link = new PDO("mysql:host=$DBservername;dbname=$DBname", $DBusername, $DBpassword);
    $stmt = $link->prepare("UPDATE users SET session = NULL WHERE session=?;");
    $stmt->bindParam(1,$sessionID);

    if($stmt->execute()) {
        echo "S|Successfully logged out";
    } else {
        echo "E|SQL Statement failed to execute";
    }
?>