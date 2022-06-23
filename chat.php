<!DOCTYPE html>
<html lang="en">
<head>
    
    <?php
        include "config/db.php";
        include "config/base_url.php";
        include "common/head.php";
        if(!isset($_SESSION["user_id"])) {
            header("Location: $BASE_URL/signin?error=access_forbidden");
            exit();
        }
    ?>
    <title>Front Chat</title>
</head>
<body data-baseurl="<?=$BASE_URL;?>" data-user_id="<?=$_SESSION['user_id'];?>">
    <div class="container">
        <div class="chat">
            <div class="chat-list">
                <div class="chat-user">
                    <img src="img/chat-list/menu.png" alt="" id="menu">
                    <a href="<?=$BASE_URL?>/chat">
                        <p>
                            <?=$_SESSION["name"]?>
                        </p>
                    </a>
                </div>
                <form class="chat-search">
                    <div class="search-block">
                        <input type="text" name="search" id="" placeholder="Поиск юзера...">
                        <button>
                            <img src="img/menu/search.png" alt="" srcset="">
                        </button>
                    </div>
                </form>
                <div class="chat-list--items">
                    <?php
                        if(isset($_GET["search"]) &&
                        strlen($_GET["search"]) > 0) {
                            echo "<p class='search-results'>Результаты поиска</p>";
                            $q = '%'.$_GET["search"].'%';
                            $prep = mysqli_prepare($con, "SELECT id, name, image FROM users WHERE name LIKE ? AND id != ?");
                            mysqli_stmt_bind_param($prep, "ss", $q, $_SESSION["user_id"]);
                            mysqli_stmt_execute($prep);
                            $query = mysqli_stmt_get_result($prep);

                            if(mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_assoc($query)) {
                    ?>
                    <div class="chat-list--item" onclick='openChat("<?=$row["name"]?>", <?=$row["id"]?>)'>
                        <img src="<?=$row["image"]?>" alt="">
                        <p><?=$row["name"]?></p>
                    </div>

                    <?php
                                }
                            }
                        } else {
                            $prep = mysqli_prepare($con,
                            "SELECT id, name, image
                            FROM users 
                            WHERE
                            id IN 
                            (SELECT received_user_id
                            FROM messages
                            WHERE sent_user_id = ?)
                            OR
                            id IN
                            (SELECT sent_user_id
                            FROM messages
                            WHERE received_user_id = ?)
                            GROUP by id, name");
                            mysqli_stmt_bind_param($prep, "ss", $_SESSION["user_id"], $_SESSION["user_id"]);
                            mysqli_stmt_execute($prep);
                            $query = mysqli_stmt_get_result($prep);

                            if(mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_assoc($query)) {
                    ?>

                    <div class="chat-list--item" onclick='openChat("<?=$row["name"]?>", <?=$row["id"]?>)'>
                        <img src="<?=$row["image"]?>" alt="">
                        <p><?=$row["name"]?></p>
                    </div>
                    <?php
                                }
                            }
                        }
                    ?>
                </div>
            </div>


            <div class="chat-window" id="chat-window">
            </div>

            <div class="slider-menu" id='slider-menu'>
                <?php
                    $prep = mysqli_prepare($con,
                    "SELECT name, image
                    FROM users 
                    WHERE id = ?");
                    mysqli_stmt_bind_param($prep, "s", $_SESSION["user_id"]);
                    mysqli_stmt_execute($prep);
                    $query = mysqli_stmt_get_result($prep);

                    if(mysqli_num_rows($query) > 0) {
                        $row = mysqli_fetch_assoc($query);
                ?>
                    <img class="close" id='close' src="img/menu/close.png" alt="">
                    <div class="slider-menu-items">
                        <img src="<?=$row["image"]?>" alt="">
                        <p class="slider-menu--item user"><?=$row["name"]?></p>
                        <a href="setting"><p class="slider-menu--item setting">Настройки</p></a>
                        <a href="api/users/sign-out"><p class="slider-menu--item exit">Выйти</p></a>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>

    <script src="js/axios.js"></script>
    <script src="js/sliderMenu.js"></script>
    <script src="js/chat.js"></script>
</body>
</html>