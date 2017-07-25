<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "disentangle";

    // Create connection
    $link = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $search = "%".$_POST["courseName"]."%";
    $stmt = $link->prepare("SELECT * FROM courses WHERE ID LIKE ? OR Name LIKE ? ;");
    $stmt->bindParam(1,$search);
    $stmt->bindParam(2,$search);
                           
    if($stmt->execute()) {
        $rows = $stmt->fetchAll();
        if($rows) {
            echo   "<table cellspacing='0' cellpadding='0'>
                <tr>
                    <td style='text-align: center' width='20%'>
                        <b><br />Course ID<br /><br />
                    </td>
                    <td width='45%'>
                        <b><br />Course Name<br /><br />
                    </td>
                    <td width='10%' style='text-align: center'>
                        <b><br />Enjoyability<br /><br />
                    </td>   
                    <td width='10%' style='text-align: center'>
                        <b><br />Difficulty<br /><br />
                    </td>
                    <td width='10%' style='text-align: center'>
                        <b><br />Skippability<br /><br />
                    </td>
                    <td width='5%'><td>
                </tr>";
            foreach($rows as $row) {
                echo   "<tr class='results' onclick='showDetails(\"" . $row["ID"] . "\")'>
                            <td style='text-align: center' width='20%'>" . $row["ID"] . "</td>
                            <td width='45%'>" . $row["Name"] . "</td>
                            <td width='10%' style='text-align: center'>N/A%</td>
                            <td width='10%' style='text-align: center'>N/A%</td>
                            <td width='10%' style='text-align: center'>N/A%</td>
                            <td width='5%'><td>
                        </tr>";
            }
        } else {
            echo "<br /><br /><center>No Results Found</center>";
        }
    } else {
        echo "<br /><br /><center>No Results Found</center>";
    }
?>