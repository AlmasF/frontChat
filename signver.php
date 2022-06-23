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
    <title>Подтвердите аккаунт</title>
</head>
<body>
    <div class="container">
        <form action="api/users/sign-ver" method="post" class="sign" id='form'>
            <div class="sign-menu">
                <p class="in-use">Подтверждение</p>
            </div>
            <label for="name">Почта или никнейм:</label>
            <input type="text" name="name" id="name" class="number">
            <label for="code">Код подтверждения:</label>
            <input type="text" name="code" id="code" class="number">
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" class="number">
            <button type="submit">Подтвердить</button>

            <?php
            if(isset($_GET["error"])){
                switch($_GET["error"]) {
                    case 1:
                        echo "Все поля должны быть заполнены";
                        break;
                    case 2:
                        echo "Пользователя с таким именем или номером нет";
                        break;
                }
            }
            ?>
        </form>
    </div>
    <script src="js/isFormFilled.js"></script>
</body>
</html>