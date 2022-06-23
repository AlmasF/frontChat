<?php
    ob_start();
include "../../config/db.php";
define('AES_256_CBC', 'aes-256-cbc');

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data["text"], $data["receiver"]) &&
intval($data["receiver"]) && strlen($data["text"]) > 0) {

    $text = $data["text"];
    $receiver = $data["receiver"];
    session_start();
    $user_id = $_SESSION['user_id'];

    $query = mysqli_query($con,
    "SELECT received_user_id, text
    FROM messages 
    WHERE 
    received_user_id IN ($user_id, $receiver) 
    AND 
    sent_user_id IN ($user_id, $receiver) 
    ORDER BY send_date DESC");

    if(mysqli_num_rows($query) == 0) {
        include "../sessions/create.php";
        $query = mysqli_query($con,
        "SELECT received_user_id, text
        FROM messages 
        WHERE 
        received_user_id IN ($user_id, $receiver) 
        AND 
        sent_user_id IN ($user_id, $receiver) 
        ORDER BY send_date DESC");
    }

    $prep_key = mysqli_prepare($con,
    "SELECT session_key, iv
    FROM sessions
    WHERE 
    user_a_id IN (?, ?)
    AND
    user_b_id IN (?, ?)");
    mysqli_stmt_bind_param($prep_key, "iiii", $_SESSION['user_id'], $receiver, $_SESSION['user_id'], $receiver);
    mysqli_stmt_execute($prep_key);
    $query_key = mysqli_stmt_get_result($prep_key);
    $row_key = mysqli_fetch_assoc($query_key);
    // $query_private_key = mysqli_query($con,
    // "SELECT private_key
    // FROM users
    // WHERE id = $user_id"
    // );
    // $row_private_key = mysqli_fetch_assoc($query_private_key);
    
    // $query_public_key = mysqli_query($con,
    // "SELECT public_key
    // FROM users
    // WHERE id = $receiver"
    // );
    // $row_public_key = mysqli_fetch_assoc($query_public_key);

    // $remote_public_key = $row_public_key['public_key'];
    // $local_private_key = $row_private_key['private_key'];
    // $session_key = openssl_dh_compute_key($remote_public_key, $local_private_key);
    // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
    $encrypted_text = openssl_encrypt($text, AES_256_CBC, $row_key['session_key'], 0, $row_key['iv']);
    //$encrypted_text = openssl_encrypt($text, AES_256_CBC, $session_key, 0, $iv);
    $encrypted_text = $encrypted_text.':'.$row_key['iv'];

    $prep = mysqli_prepare($con, "INSERT INTO messages (text, received_user_id, sent_user_id) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($prep, "sii", $encrypted_text, $receiver, $_SESSION["user_id"]);
    mysqli_stmt_execute($prep);

    if(mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $parts = explode(':', $row['text']);
        //$row['text'] = 'lol';
        $row['text'] = openssl_decrypt($parts[0], AES_256_CBC, $row_key['session_key'], 0, $row_key['iv']);
        echo json_encode($row);
    }
} else {
    echo "ERROR";
}
?>