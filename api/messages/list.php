<?php
    ob_start();
    include "../../config/db.php";
    include "../../config/base_url.php";
    define('AES_256_CBC', 'aes-256-cbc');

    session_start();

    if(!isset($_GET["id"]) || !intval($_GET["id"])) {
        exit();
    }

    $id = $_GET["id"];
    $user = $_SESSION["user_id"];


    $prep = mysqli_prepare($con,
    "SELECT text, received_user_id 
    FROM messages 
    WHERE received_user_id IN (?, ?) 
    AND sent_user_id IN (?, ?) ORDER BY send_date ASC");
    mysqli_stmt_bind_param($prep, "ssss", $id, $user, $id, $user);
    mysqli_stmt_execute($prep);
    $query_messages = mysqli_stmt_get_result($prep);

    $prep_key = mysqli_prepare($con,
    "SELECT session_key, iv
    FROM sessions
    WHERE 
    user_a_id IN (?, ?)
    AND
    user_b_id IN (?, ?)");
    mysqli_stmt_bind_param($prep_key, "iiii", $id, $user, $id, $user);
    mysqli_stmt_execute($prep_key);
    $query_key = mysqli_stmt_get_result($prep_key);
    $row_key = mysqli_fetch_assoc($query_key);

    $messages = array();
    if(mysqli_num_rows($query_messages) == 0) {
        echo json_encode($messages);
        exit();
    }

    while($message = mysqli_fetch_assoc($query_messages)) {
        $parts = explode(':', $message['text']);
        //$message['text'] = 'lol';
        $message['text'] = openssl_decrypt($parts[0], AES_256_CBC, $row_key['session_key'], 0, $row_key['iv']);

        $messages[] =  $message;
    }

    echo json_encode($messages);

?>
