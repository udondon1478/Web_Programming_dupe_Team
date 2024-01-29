<?php
//セッションの開始
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
    header('Location: login.html');
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container">

        <div class="loginfo-container">
            <div class="loginfo">
                <?php
                //ログイン画面
                if ((isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
                    //ログイン成功
                    echo 'Login: ' . $_SESSION['name'] . '<br>' . '<hr>';
                } else {
                    //ログイン失敗
                    echo 'ログインしてください.';
                }

                ?>
            </div>
        </div>

        <div class="navbar navbar-expand-sm">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="top_page.php">【メニュー】</a>
                <a class="nav-item nav-link" href="logout.php">【ログアウト】</a> <br>
            </div>

        </div>

        <hr>

        <form action="force_add_account_to_table.php" method="post">
            <?php
            $dbh = connectDB();
            if ($dbh) {
                $sql = 'SELECT `id`,`username` FROM `user_tb`';
                $sth = $dbh->query($sql); //SQLの実行
                $result = $sth->fetchAll();
                foreach ($result as $row) {
                    echo '<input type="checkbox" name="access_users[]" value="' . $row['id'] . '">' . $row['username'] . '<br>';
                }
            } else {
                echo 'データベースに接続できません。';
            }
            ?>

            <button type="submit" class="btn btn-primary">アカウントを追加</button>
        </form>
        <hr>


        <a class="btn btn-primary" href="show_message.php">メッセージを読む
        </a>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>