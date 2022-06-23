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
            <form action="api/users/password" method="post" class="sign" enctype="multipart/form-data">
                <label for="old">Старый пароль:</label>
                <input type="text" name="old" id="name" class="number">
                <label for="new1">Новый пароль:</label>
                <input type="text" name="new1" id="name" class="number">
                <label for="new2">Повторите новый пароль:</label>
                <input type="text" name="new2" id="name" class="number">
                <button type="submit">Сохранить</button>
                <a href="setting">
                    Изменить профиль
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
                            echo "Новые пароли не совпадают";
                            break;
                        case 4:
                            echo "Неверный старый пароль";
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