<?php
    ob_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../../PHPMailer/src/Exception.php';
    require '../../PHPMailer/src/SMTP.php';
    require '../../PHPMailer/src/PHPMailer.php';

    include "../../config/db.php";
    include "../../config/base_url.php";
    include "./middleware/validateString.php";

    if(
        isset($_POST["name"], $_POST["mail"], $_POST["password1"], $_POST["password2"])
        && 
        strlen($_POST["name"]) > 0 && 
        strlen($_POST["mail"]) > 0 && 
        strlen($_POST["password1"]) > 0 &&
        strlen($_POST["password2"]) > 0
    )
    {
        if($_POST["password1"] != $_POST["password2"]) {
            header("Location: $BASE_URL/signup?error=2");
            exit();
        }

        if(!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
            header("Location: $BASE_URL/signup?error=3");
            exit();
        }

        $name = strtolower($_POST["name"]);
        $email = $_POST["mail"];
        $password = $_POST["password1"];

        $prep = mysqli_prepare($con, "SELECT id FROM users WHERE name=? OR email=?");
        mysqli_stmt_bind_param($prep, "ss", $name, $email);
        mysqli_stmt_execute($prep);
        $query = mysqli_stmt_get_result($prep);

        if(mysqli_num_rows($query) > 0) {
            header("Location: $BASE_URL/signup?error=4");
            exit();
        }

        if(!validateLatin($name)){
            header("Location: $BASE_URL/setting?error=5");
            exit();
        }

        $ver_code = mt_rand(100000,999999);
        $hash = sha1($password);
        // $keys = openssl_pkey_new(array('digest_alg' => 'sha1', 'private_key_type' => OPENSSL_KEYTYPE_RSA, 'private_key_bits' => 2048));
        // if ($keys === false) die('Failed to generate key pair.'."\n");
        // if (!openssl_pkey_export($keys, $privateKey)) die('Failed to retrieve private key.'."\n");
        // file_put_contents('private_key.pem', $privateKey); 
        
        // $public_key_pem = openssl_pkey_get_details($keys)['key'];
        // $public_key = openssl_pkey_get_public($public_key_pem);

        $image = 'default.jpg';
        $prep1 = mysqli_prepare($con, "INSERT INTO users (name, email, password, ver_code, image) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($prep1, "sssss", $name, $email, $hash, $ver_code, $image);
        mysqli_stmt_execute($prep1);

        $mail = new PHPMailer(true);

    
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPDebug   = 2;
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->SMTPSecure  = "tls"; //Secure conection
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->Username   = 'alisamla202216@gmail.com';                     //SMTP username
        $mail->Password   = 'arqrqlyfxqoqvvub';                               //SMTP password
        $mail->Priority    = 1;
        $mail->CharSet = 'UTF-8';
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
 
        //Recipients
        $mail->setFrom('alisamla@gmail.com', 'Mailer');
        $mail->addAddress($email);
        $mail->addReplyTo('info@example.com', 'Information');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Код верификации FrontChat';
        $mail->Body    = 'Здравствуйте, ваш код верификации: '.$ver_code;

        $mail->send();
        //echo 'Message has been sent';



        header("Location: $BASE_URL/signin");
    } else {
        header("Location: $BASE_URL/signup?error=1");
    }
?>