<?php
    ob_start();
    $user_a_id = $_SESSION["user_id"];
    $user_b_id = $receiver;
    $encryption_key = openssl_random_pseudo_bytes(32);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
    // $encryption_key = openssl_dh_compute_key($public_key, OpenSSLAsymmetricKey $private_key);
    $prep = mysqli_prepare($con, 
    "INSERT INTO sessions (user_a_id, user_b_id, session_key, iv) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($prep, "iibb", $user_a_id, $user_b_id, $encryption_key, $iv);
    mysqli_stmt_execute($prep);
?>