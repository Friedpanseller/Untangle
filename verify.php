<?php
    require 'sqlDetails.php';

    $sql = new sqlDetails;

    // Create connection
    $link = new PDO("mysql:host=$sql->server;dbname=$sql->database", $sql->username, $sql->password);

    $search = $_GET["user"];
    $stmt = $link->prepare("SELECT code FROM users WHERE zID = ?;");
    $stmt->bindParam(1,$search);

    if($stmt->execute()) {
        $rows = $stmt->fetchAll();
        if($rows) {
            foreach($rows as $row) {
                if($row["code"] == $_GET["code"]) {
                    echo "Account Verified!";
                    $stmt = $link->prepare("UPDATE users SET verified=1 WHERE zID=?;");
                    $stmt->bindParam(1,$search);
                    $stmt->execute();
                }
                break;
            }
        } else {
            echo "<br /><br /><center>No Results Found</center>";
        }
    } else {
        echo "<br /><br /><center>No Results Found</center>";
    }
?>