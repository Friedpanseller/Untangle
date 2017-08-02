<?php
    require 'sqlDetails.php';

    $sql = new sqlDetails;

    $userIP = $_SERVER['REMOTE_ADDR'];
    $userBrowser = $_POST['userBrowser'];

    $sessionID = $_POST['sessionID'];
    if($sessionID == "" || !sessionID) {

        $username = $_POST['username'];
        //echo "E|" . $username . "***";
        $password = $_POST['password'];
        //echo "password:" . $password . "***";
        $hPassword = openssl_digest("tomatoCatus" . $password . "tomatoCatus", 'sha512');
        //echo "hpassword:" . $hPassword . "***";
        $keepMeLoggedIn = $_POST['keepMeLoggedIn'];

        $sessionID = bin2hex(openssl_random_pseudo_bytes(20));
        $hash = openssl_digest("ASaltyBlueberryBagel" . $userBrowser . $sessionID . $userIP . "ASaltyBlueberryBagel", 'sha512');

        $link = new PDO("mysql:host=$sql->server;dbname=$sql->database", $sql->username, $sql->password);
        $stmt = $link->prepare("SELECT password, verified FROM users WHERE zID = ?;");
        $stmt->bindParam(1,$username);

        if($stmt->execute()) {
            $rows = $stmt->fetchAll();
            //var_dump($rows);
            if($rows) {
                foreach($rows as $row) {
                    //echo "if " . $row["password"] . "=" . $hPassword;
                    if($row["verified"] == 1) {
                        if($row["password"] == $hPassword) {
                            $stmt = $link->prepare("UPDATE users SET session=? WHERE zID=?;");
                            $stmt->bindParam(1,$hash);
                            $stmt->bindParam(2,$username);
                            if($stmt->execute()) {
                                echo "S|" . $username . "|" . $sessionID;
                            } else {
                                echo "E|SQL UPDATE Statement did not execute";
                            }
                        } else {
                            echo "E|Username or Password is incorrect";
                        }
                    } else {
                        echo "E|Please verify your email before logging in";
                    }
                    break;
                }
            } else {
                //echo "ZID NOT FOUND***";
                echo "E|Username or Password is incorrect";
            }
        } else {
            echo "E|SQL Statement did not execute";
        }

    } else {
        $hash = openssl_digest("ASaltyBlueberryBagel" . $userBrowser . $sessionID . $userIP . "ASaltyBlueberryBagel", 'sha512');
        
        $link = new PDO("mysql:host=$sql->server;dbname=$sql->database", $sql->username, $sql->password);
        $stmt = $link->prepare("SELECT zID FROM users WHERE session = ?;");
        $stmt->bindParam(1,$hash);

        if($stmt->execute()) {
            $rows = $stmt->fetchAll();
            if($rows) {
                foreach($rows as $row) {
                    echo "S|" . $row["zID"] . "|" . $sessionID;
                    break;
                }
            } else {
                //echo "E|Unable to find session ID " . $sessionID . "***hash " . $hash . " in database";
            }
        } else {
            //echo "E|SQL Execution Error";
        }
    }
?>