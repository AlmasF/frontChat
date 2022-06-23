<?php
    ob_start();
    include "../../config/db.php";
    include "../../config/base_url.php";
    include "./middleware/validateString.php";
    session_start();

    if(isset($_POST["name"]) && 
        strlen($_POST["name"]) > 0)
        {
            $name = $_POST["name"];

            if(!validateLatin($name)){
                header("Location: $BASE_URL/setting?error=2");
                exit();
            }

            $prep = mysqli_prepare($con, "SELECT id FROM users WHERE name=? AND name!=?");
            mysqli_stmt_bind_param($prep, "ss", $name, $_SESSION["name"]);
            mysqli_stmt_execute($prep);
            $query = mysqli_stmt_get_result($prep);

            if(mysqli_num_rows($query) > 0) {
                header("Location: $BASE_URL/setting?error=3");
                exit();
            }

            session_start();
            $user_id = $_SESSION["user_id"];

            if(isset($_FILES["image"]) && isset($_FILES["image"]["name"]) 
            && strlen($_FILES["image"]["name"]) > 0) {
                $query = mysqli_query($con, "SELECT image FROM users WHERE id=$user_id");
                if(mysqli_num_rows($query) > 0) {
                    $row = mysqli_fetch_assoc($query);
                    $old_path = __DIR__."/../../".$row["image"];
                    echo $old_path;
                    if(file_exists($old_path)){
                        unlink($old_path);
                    }
                }

                $ext = end(explode(".", $_FILES["image"]["name"]));
                $image_name = time().".".$ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], "../../img/avatars/$image_name");

                $prep = mysqli_prepare($con, "UPDATE users SET name=?, image=? WHERE id=?");
                $path = "img/avatars/".$image_name;
                mysqli_stmt_bind_param($prep, "sss", $name, $path, $user_id);
                mysqli_stmt_execute($prep);
                $_SESSION["name"] = $name;
            } else {
                $prep = mysqli_prepare($con, "UPDATE users SET name=? WHERE id=?");
                mysqli_stmt_bind_param($prep, "ss", $name, $user_id);
                mysqli_stmt_execute($prep);
                $_SESSION["name"] = $name;
            }
            header("Location: $BASE_URL/chat");
    } else {
        header("Location: $BASE_URL/setting?error=1");
    }
?>