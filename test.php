<?php
    echo openssl_digest("tomatoCatus" . $_GET["password"] . "tomatoCatus", 'sha512');
?>