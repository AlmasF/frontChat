<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include "config/base_url.php";
        include "common/head.php";
        include "config/db.php";
        if(!isset($_SESSION["user_id"])) {
            header("Location: $BASE_URL/chat?error=access_forbidden");
            exit();
        }
    ?>
    <title>Редактирование</title>
</head>
<body>
    <div class="container">
        <?php
            $prep = mysqli_prepare($con,
            "SELECT * 
            FROM users
            WHERE id = ?");
            mysqli_stmt_bind_param($prep, "s", $_SESSION["user_id"]);
            mysqli_stmt_execute($prep);
            $query = mysqli_stmt_get_result($prep);

            if(mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_assoc($query);
        ?>
            <form action="api/users/update" method="post" class="sign" enctype="multipart/form-data">
                <label for="name">Никнейм:</label>
                <input type="text" name="name" id="name" class="number" value="<?=$row["name"]?>">
                <label for="image">Аватар:</label>
                <div class="button input-file">
                    <img src="<?=$row["image"]?>" alt="" srcset="">
                    <input type="file" name="image">	
                    Выберите картинку
                </div>
                <button type="submit" class="setting">Сохранить</button>
                <a href="password">
                    Изменить пароль
                </a>
                <a href="chat">
                    Вернуться в чат
                </a>
                <?php
                if(isset($_GET["error"])){
                    switch($_GET["error"]) {
                        case 1:
                            echo "Все поля должны быть заполнены";
                            break;
                        case 2:
                            echo "Вводите исключительно латинские буквы";
                            break;
                        case 3:
                            echo "Пользователь с таким именем уже существует";
                            break;
                    }
                }
                ?>
            </form>
        <?php
            } else {
                header("Location: $BASE_URL/chat?error=no_user&user_id=".$_SESSION["user_id"]."&rows=".mysqli_num_rows($query));
                exit();
            }
        ?>
    </div>
</body>
</html>