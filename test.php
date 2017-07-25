<html><h2>HELLO</h2></html>

<?php
    ob_start();
    echo '<html>
              <head>
                  <style>
                      body {background-color: black; color: white}
                  </style>
              </head>
              <body>
                  <h2 id="greeting">Wait for page load</h2>
              </body>
          </html>';
    ob_flush();
    flush();
    sleep(1);
    echo '<script>document.getElementByID("greeting").innerHTML = "Page loaded!";</script>';
    ob_flush();
    flush();
?>