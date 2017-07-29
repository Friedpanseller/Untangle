<?php
    $servername = "localhost";
    $DBusername = "friedpan_admin";
    $DBpassword = "0*0*Leon";
    $dbname = "friedpan_disentangle";

    // Create connection
    $link = mysqli_connect($servername, $DBusername, $DBpassword, $dbname);
    // Check connection
    if ($link === false) {
        die("Connection failed: " . mysqli_connect_error());
    } 
    
    /*$query = "SELECT IF( EXISTS(
             SELECT *
             FROM `users`
             WHERE `zID` =  '" . $_GET["zID"] . "'), 1, 0)";

    $result = mysqli_query($link, $query);
    var_dump($result);

    if($result) {*/
    $zID = $_POST["zID"];
    $email = $zID . "@unsw.edu.au";
    //$email = "friedpanseller@gmail.com";
    $password = openssl_digest("tomatoCatus" . $_POST["password"] . "tomatoCatus", 'sha512');
    $code = bin2hex(openssl_random_pseudo_bytes(25));

    $query = "
        INSERT INTO `users` 
        (`zID`, `email`, `password`, `code`) VALUES
        ('" . $zID . "', '" . $email . "', '" . $password . "', '" . $code . "');
    ";

    //echo "E|Query is " . $query . "<br />, password is " . $_POST["password"] . "***";

    if(mysqli_query($link, $query)) {
        echo "Sending email to " . $email . "...<br />";
        
        require 'PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.friedpanseller.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'donotreply@friedpanseller.com';                 // SMTP username
        $mail->Password = '0*0*Leon';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('donotreply@friedpanseller.com', 'Untangle Account');
        $mail->addAddress($email, $zID);     // Add a recipient
        $mail->addReplyTo('donotreply@friedpanseller.com', 'Do Not Reply');

        $mail->isHTML(true);                                  // Set email format to HTML
        
        //$mail->AddEmbeddedImage('logo/logo0white.png', 'logo');

        $mail->Subject = 'Untangle Account Verification';
        $verifylink = "https://untangle.friedpanseller.com/verify.php?code=" . $code . "&user=" . $zID;
        $mail->Body    = '
            <html>
                <head>
                    <style>
                        #header {
                            width: 100%;
                            padding-top: 10px;
                            padding-bottom: 10px;
                            background-color: crimson;
                        }
                        #content {
                            width: 80%;
                            margin: 0 auto;
                            font-size: 18px;
                            font-family: "Helvetica";
                        }
                        #footer {
                            width: 100%;
                            height: 100px;
                            background-color: crimson;
                            margin-top: 50px;
                        }
                        #title {
                            font-size: 30px;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <center><div id="header"><img src="https://untangle.friedpanseller.com/logo/logo0white.png" height="128" /></div></center>
                    <div id="content">
                        <br />
                        <br />
                        <center><span id="title">Welcome to Untangle!</span></center>
                        <br />
                        Hi ' . $zID . '!
                        <br />
                        <br />
                        Thanks for creating an account with us. In order to confirm your identity, please click the link to verify your email.
                        With an Untangle account, you will be able to write reviews for courses, upvote or downvote existing reviews, or give the courses suitable ratings.
                        <br /><br />
                        <a href=' . $verifylink . '>Verify Email</a>
                        <br /><br />
                        Link does not work? Copy paste this into your URL bar:
                        <br />
                        ' . $verifylink . '
                    </div>
                    <div id="footer"></div>
                </body>
            </html>
        ';
        $mail->AltBody = '
            Welcome to Untangle!\n
            Hi ' . $zID . '!\n\n
            Thanks for creating an account with us. In order to confirm your identity, please copy the link to verify your email.
            With an Untangle account, you will be able to write reviews for courses, upvote or downvote existing reviews, or give the courses suitable ratings.
            \n\n
            Copy paste this into your website\'s URL bar:
            \n
            ' . $verifylink
        ;

        if(!$mail->send()) {
            echo 'E|Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'E|Message has been sent';
        }
    } else {
        echo "E|" . mysqli_error($link);
    }
    mysqli_close($link);
?> 