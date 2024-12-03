<?php
//セッションの開始
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
    header('Location: login.html');
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');
//DBへの接続
$dbh = connectDB();
if ($dbh) {
    //「user_name」が$_SESSION['name']と一致するレコード群の情報を取得
    $sql = 'SELECT `is_admin` FROM `user_tb` WHERE `username` = :user_name';
    $sth = $dbh->prepare($sql); //SQLの準備
    $sth->bindValue(':user_name', $_SESSION['name'], PDO::PARAM_STR); //プレースホルダーに値をバインド
    $sth->execute(); //SQLの実行
    $buff = $sth->fetch(PDO::FETCH_ASSOC); //結果の取得
}

$_SESSION['team_id'] = $_GET['team_id'];
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

        <div class="form">
            <form action="change_icon_confirm.php?team_id=<?= $_GET['team_id'] ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="team_id" value="<?= $_GET['team_id'] ?>">
                <div class="upload-area">
                    <p>クリック、またはD&Dでファイルを追加</p>
                    <input type="file" name="file" id="file">
                </div>

                <input type="submit" value="変更">
            </form>
        </div>


    </div>

    <div class="logout">
        <a class="btn btn-primary" href="logout.php">【ログアウト】</a> <br>
    </div>
    <hr>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>

<style>
    form {
        margin: 50px auto;
        width: 800px;
        height: 400px;
        box-shadow: 0 0 2px #3e3e3e;
        padding: 30px;
        text-align: center;
    }

    .upload-area {
        margin: auto;
        width: 85%;
        height: 300px;
        position: relative;
        border: 1rem dotted rgba(0, 0, 0, 4)
    }

    .upload-area p {
        width: 100%;
        position: absolute;
        top: 7rem;
        opacity: .8;
    }

    #file {
    top: 0;
    left: 0;
    opacity: 0;
    position: absolute;
    width: 100%;
    height: 100%;
}
</style>