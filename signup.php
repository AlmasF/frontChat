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
    <title>Зарегистрируйтесь</title>
</head>
<body>
    <div class="container">
        <form action="api/users/sign-up" method="post" class="sign" id='form'>
            <div class="sign-menu">
                <a href="signin"><p>Вход</p></a>
                <p class="in-use">Регистрация</p>
            </div>
            <label for="mail">Электронная почта:</label>
            <input id="phone" class="number" type="mail" name="mail" placeholder="user@gmail.com" required>
            <label for="nickname">Имя:</label>
            <input type="text" name="name" id="nickname" class="number" required>
            <label for="password1">Пароль:</label>
            <input type="password" name="password1" id="password1" class="number" required>
            <label for="password2">Повторите пароль:</label>
            <input type="password" name="password2" id="password2" class="number" required>
            <button type="submit">Зарегистрироваться</button>

            <?php
            if(isset($_GET["error"])){
                switch($_GET["error"]) {
                    case 1:
                        echo "Все поля должны быть заполнены";
                        break;
                    case 2:
                        echo "Пароли не совпадают";
                        break;
                    case 3:
                        echo "Формат почты не верный";
                        break;
                    case 4:
                        echo "Пользователь с такой почтой или ником уже существует";
                        break;
                    case 5:
                        echo "Вводите исключительно латинские буквы";
                        break;
                }
            }
            ?>
        </form>
    </div>
    <script src="js/isFormFilled.js"></script>
</body>
</html>