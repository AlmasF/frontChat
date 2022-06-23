<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include "config/base_url.php";
        include "common/head.php";
        if(isset($_SESSION["user_id"])) {
            header("Location: $BASE_URL/chat?error=wtf");
            exit();
        }
    ?>
    <title>Войдите</title>
</head>
<body>
    <div class="container">
        <form action="api/users/sign-in" method="post" class="sign" id='form'>
            <div class="sign-menu">
                <p class="in-use">Вход</p>
                <a href="signup"><p>Регистрация</p></a>
            </div>
            <label for="name">Почта или никнейм:</label>
            <input type="text" name="name" id="name" class="number">
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" class="number">
            <button type="submit">Войти</button>

            <?php
            if(isset($_GET["error"])){
                switch($_GET["error"]) {
                    case 1:
                        echo "Все поля должны быть заполнены";
                        break;
                    case 2:
                        echo "Пользователя с таким именем или эмейлом нет";
                        break;
                }
            }
            ?>
        </form>
    </div>
    <script src="js/isFormFilled.js"></script>
</body>
</html>