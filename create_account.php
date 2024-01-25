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

    $sql = 'SELECT `is_admin` FROM `user_tb` WHERE `username` = :user_name';
    $sth = $dbh->prepare($sql); //SQLの準備
    $sth->bindValue(':user_name', $_SESSION['name'], PDO::PARAM_STR); //プレースホルダーに値をバインド
    $sth->execute(); //SQLの実行
    $buff = $sth->fetch(PDO::FETCH_ASSOC); //結果の取得
}
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
                    <!-- ['admin_flag']が1の時だけshow_messageのリンクを表示 -->
                    <?php
                    if ($_SESSION['is_admin'] == 1) {
                        //管理者権限あり
                        echo '<a class="nav-item nav-link" href="delete_message.php">メッセージの管理</a> <br>';
                    }
                    ?>
                    <a class="nav-item nav-link" href="message.php">メッセージを書く</a> <br>
                    <a class="nav-item nav-link" href="show_message.php">メッセージを読む</a> <br>
                    <a class="nav-item nav-link" href="search_message.php">メッセージを探す</a> <br>
                    <a class="nav-item nav-link" href="account_list.php">アカウント一覧</a> <br>
                </div>
            </nav>

        </div>

        <!-- 条件参照用メモ -->
        <!-- 管理者権限があるユーザーの場合、アカウントの作成ができる -->
        <!-- データベースの「user_tb」に新規ユーザーを追加する -->
        <!-- レコードの項目は「login_name」「login_password」「user_name」「admin_flag」の4つ -->
        <!-- 「admin_flag」はラジオボタンで切り替える -->
        <div>
            <?php
            if ($_SESSION['is_admin'] == 1) {
                //管理者権限あり
                echo '<h2>▪️アカウント作成</h2>';
                echo '<form action="insert_account.php" method="POST">';
                echo '<div class="form-group">';
                echo '<label for="login_name">ログイン名</label>';
                echo '<input type="text" class="form-control" id="login_name" name="login_name" placeholder="ログイン名を入力してください">';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="login_password">パスワード</label>';
                echo '<input type="password" class="form-control" id="login_password" name="login_password" placeholder="パスワードを入力してください">';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="user_name">ユーザー名</label>';
                echo '<input type="text" class="form-control" id="user_name" name="user_name" placeholder="ユーザー名を入力してください">';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="is_admin">管理者権限</label>';
                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="radio" name="admin_flag" id="admin_flag" value="1">';
                echo '<label class="form-check-label" for="admin_flag">あり</label>';
                echo '</div>';
                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="radio" name="admin_flag" id="admin_flag" value="0" checked>';
                echo '<label class="form-check-label" for="admin_flag">なし</label>';
                echo '</div>';
                echo '</div>';
                echo '<button type="submit" class="btn btn-primary">アカウントを作成する</button>';
                echo '</form>';
            }
            ?>
        </div>


        <div class="logout">
            <a class="btn btn-primary" href="logout.php">【ログアウト】</a> <br>
        </div>
        <hr>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>