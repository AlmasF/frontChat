<?php
    ob_start();
    include "../../config/base_url.php";
    echo $BASE_URL;
    session_start();
    session_destroy();
    header("Location: $BASE_URL/signin");
?>