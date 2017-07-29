<?php header('Content-type: text/html; charset=utf-8'); ?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="script.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="circle.css" />
        <link rel="stylesheet" type="text/css" href="staticStyle.css" />
        <link rel="stylesheet" type="text/css" href="mediaScreens.css" />
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        
        <title>Untangle</title>
    </head>
    <body>
        <div id="header" class="noselect">You are not logged in. <span class="login" onclick="showLoginScreen()">Log in</span>.</div>
        <div id="login"></div>
        <div id="search">
            <center>
                <span id="home" class="noselect">
                    &nbsp;Untangle
                </span>
                <br />
                <hr id="homeUnderline" />
            </center>
                <input type="text" id="bar" value="🔎&#xFE0E; search courses" />
        </div>
        <div id="content">
            <div id="buffer"></div>
            <div id="results"></div>
            <div id="details"></div>
            <div id="buffer"></div>
        </div>
        <div id="footer" style="height: 10vh; width: 99vw;"></div>
        <div id="creator" class="noselect" style="position: absolute; bottom: 0; left: 25vw; width: 50vw; color: rgba(255,255,255,0.3);">
            <center>The University of New South Wales | Created by Leo Liu z5080336 &Cfr; student &#8226; unsw &#8226; edu &#8226; au | Computer Science and Engineering</center>
        </div>
    </body>
</html>