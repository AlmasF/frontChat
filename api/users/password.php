<?php
    ob_start();
    include "../../config/db.php";
    include "../../config/base_url.php";
    include "./middleware/validateString.php";

    if(isset($_POST["old"], $_POST["new1"], $_POST["new2"]) && 
        strlen($_POST["old"]) > 0 &&
        strlen($_POST["new1"]) > 0 &&
        strlen($_POST["new2"]) > 0)
        {
            $old = $_POST["old"];
            $new1 = $_POST["new1"];
            $new2 = $_POST["new2"];

            if(!validateLatin($new1)){
                header("Location: $BASE_URL/password?error=2");
                exit();
            }

            if($new1 != $new2){
                header("Location: $BASE_URL/password?error=3");
                exit();
            }

            session_start();
            $user_id = $_SESSION["user_id"];

            $prep = mysqli_prepare($con, "SELECT password FROM users WHERE id=?");
            mysqli_stmt_bind_param($prep, "s", $user_id);
            mysqli_stmt_execute($prep);
            $query = mysqli_stmt_get_result($prep);
            $row = mysqli_fetch_assoc($query);

            if($row["password"] != sha1($old)){
                header("Location:  $BASE_URL/password?error=4");
                exit();
            }

            $hash = sha1($new1);
            $prep = mysqli_prepare($con, "UPDATE users SET password=? WHERE id=?");
            mysqli_stmt_bind_param($prep, "ss", $hash, $user_id);
            mysqli_stmt_execute($prep);
            $_SESSION["name"] = $name;
            
            header("Location: $BASE_URL/chat");
    } else {
        header("Location: $BASE_URL/password?error=1");
    }
?>