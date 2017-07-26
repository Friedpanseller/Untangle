<?php
    $servername = "localhost";
    $username = "friedpan_admin";
    $password = "0*0*Leon";
    $dbname = "friedpan_disentangle";

    // Create connection
    $link = mysqli_connect($servername, $username, $password, $dbname);
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
    $email = $zID . "@student.unsw.edu.au";
    //$email = "friedpanseller@gmail.com";
    $password = openssl_digest($_POST["password"] . "tomatoCatus", 'sha512');
    $code = bin2hex(openssl_random_pseudo_bytes(25));

    $query = "
        INSERT INTO `users` 
        (`zID`, `email`, `password`, `code`) VALUES
        ('" . $zID . "', '" . $email . "', '" . $password . "', '" . $code . "');
    ";

    echo "Query is " . $query . "<br />";

    if(mysqli_query($link, $query)) {
        echo "Sending email to " . $email . "...<br />";
        
        require 'PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.friedpanseller.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'accounts@friedpanseller.com';                 // SMTP username
        $mail->Password = '0*0*Leon';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('accounts@friedpanseller.com', 'Untangle Account');
        $mail->addAddress($email, $zID);     // Add a recipient
        $mail->addReplyTo('donotreply@friedpanseller.com', 'DoNotReply');

        $mail->isHTML(true);                                  // Set email format to HTML
        
        $mail->AddEmbeddedImage('logo/logo0white.png', 'logo');

        $mail->Subject = 'Untangle Account Verification';
        $verifylink = "https://untangle.friedpanseller.com/verify.php?code=' . $code . '&user=' . $zID . '";
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
                            margin-top: 40px;
                            margin: 0 auto;
                            font-size: 18px;
                            font-family: "Helvetica";
                        }
                        #footer {
                            width: 100%;
                            height: 80px;
                            background-color: crimson;
                            margin-top: 80px;
                        }
                        #title {
                            font-size: 30px;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <center><div id="header"><img src="cid:logo" height="128" /></div></center>
                    <div id="content">
                        <br />
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
                        Link doesn\'t work? Copy paste this into your website\'s URL bar:
                        <br />
                        ' . $verifylink . '
                    </div>
                    <div id="footer"></div>
                </body>
            </html>
        ';
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    } else {
        echo "E|" . mysqli_error($link);
    }
    /*} else {
        echo "E|MySQL Error. There is already an account with that ID. Result = " . $result;
    }*/
    mysqli_close($link);
?> 