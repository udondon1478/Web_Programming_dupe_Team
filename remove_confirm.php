<?php
//セッションの開始
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
    header('Location: login.html');
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');
?>

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

        <div class="menu container">
            <h1>▪️掲示板メニュー <br></h1>
            <nav class="navbar navbar-expand-sm">
                <div class="navbar-nav">

                    <?php

                    echo '<a class="nav-item nav-link" href="create_account.php">アカウントの作成</a> <br>';

                    ?>

                    <a class="nav-item nav-link" href="top_page.php">トップページ</a> <br>
                    <a class="nav-item nav-link" href="create_team.php">チームを作成</a>
                </div>
            </nav>

        </div>

        <?php
        // デバッグ用POSTで送信された内容を全て書き出す

        ?>

        <!--POSTの中に含まれているキーの数をもとに役割が変更されたユーザーidを格納する配列を作成-->
        <?php
        $count = count($_POST);

        //デバッグ用


        //user_idの中には$_POST[role"n"]というnameに変数を持つキーを入れる
        $user_id = [];

        //$_POST[role"n"]のキーの値ではなくnameの変数部分の値を入れなければならない
        //だいぶ面倒

        foreach ($_POST as $key => $value) {
            if ($key == "team_id") {
                continue;
            }
            $parts = explode("role", $key);
            //デバッグ用


            $user_id[] = $parts[1];
        }

        //デバッグ用$user_id

        ?>

        <!--DBの処理-->
        <?php
        //DBへの接続
        $dbh = connectDB();
        if ($dbh) {
            //DB接続成功
            //配列上でのuser_idは0から始まっているため、$iは0から始まる
            //$user_idに含まれるidだけ一致するレコードを削除
            for ($i = 0; $i < $count - 1; $i++) {
                $sql = "DELETE FROM `team_users_tb` WHERE `user_id` = :user_id AND `team_id` = :team_id";
                $sth = $dbh->prepare($sql);
                $sth->bindValue(':user_id', $user_id[$i], PDO::PARAM_INT);
                $sth->bindValue(':team_id', $_POST['team_id'], PDO::PARAM_INT);
                $sth->execute();
            }
            echo 'チームからユーザーを削除しました';
        }

        ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>