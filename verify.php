<?php
    $servername = "localhost";
    $username = "friedpan_admin";
    $password = "0*0*Leon";
    $dbname = "friedpan_disentangle";

    $link = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $search = $_GET["user"];
    $stmt = $link->prepare("SELECT code FROM users WHERE ID = ?;");
    $stmt->bindParam(1,$search);

    if($stmt->execute()) {
        $rows = $stmt->fetchAll();
        if($rows) {
            echo $rows["code"]
        } else {
            echo "<br /><br /><center>No Results Found</center>";
        }
    } else {
        echo "<br /><br /><center>No Results Found</center>";
    }
?>