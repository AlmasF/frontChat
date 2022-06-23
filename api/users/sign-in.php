<?php
    ob_start();
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(
        isset($_POST["name"], $_POST["password"])
        && 
        strlen($_POST["name"]) > 0 && 
        strlen($_POST["password"]) > 0)
    {
        $name = $_POST["name"];
        $pass = $_POST["password"];
        $hash = sha1($pass);
    
        $prep = mysqli_prepare($con, 
        "SELECT id, name, verified
        FROM users
        WHERE 
        (name=? OR email=?) 
        AND 
        password=?");
        mysqli_stmt_bind_param($prep, "sss", $name, $name, $hash);
        mysqli_stmt_execute($prep);
        $query = mysqli_stmt_get_result($prep);
        if(mysqli_num_rows($query) != 1) {
            header("Location: $BASE_URL/signin?error=2");
            exit();
        }

        $row = mysqli_fetch_assoc($query);
        if(!$row["verified"]){
            header("Location: $BASE_URL/signver");
            exit();
        }

        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["name"] = $row["name"];

        header("Location: $BASE_URL/chat?name=".$row["name"]);

    } else {
        header("Location: $BASE_URL/signin?error=1");
    }
?>