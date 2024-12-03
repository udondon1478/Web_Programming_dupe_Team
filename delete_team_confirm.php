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

        <div class="menu container">
            <h1>▪️掲示板メニュー <br></h1>
            <nav class="navbar navbar-expand-sm">
                <div class="navbar-nav">
                    <!-- ['is_admin']がTRUEの時だけshow_messageのリンクを表示 -->
                    <?php
                    if ($_SESSION['is_admin'] == 1) {

                        echo '<a class="nav-item nav-link" href="delete_message.php">メッセージの管理</a> <br>';

                        //アカウント追加
                        echo '<a class="nav-item nav-link" href="add_account.php">アカウントの追加</a> <br>';
                    }
                    ?>
                    <a class="nav-item nav-link" href="top_page.php">トップページ</a> <br>
                    <a class="nav-item nav-link" href="message.php">メッセージを書く</a> <br>
                    <a class="nav-item nav-link" href="show_message.php">メッセージを読む</a> <br>
                    <a class="nav-item nav-link" href="search_message.php">メッセージを探す</a> <br>
                    <a class="nav-item nav-link" href="account_list.php">アカウント一覧</a> <br>
                    <a class="nav-item nav-link" href="create_team.php">チームを作成</a>
                </div>
            </nav>
        </div>

        <hr>

        <h1>本当にチームを削除しますか？</h1>
        <form action="delete_team.php" method="post">
            <input type="hidden" name="team_id" value="<?php echo $_GET['team_id']; ?>">
            <input type="submit" value="削除">


        <div class="logout">
            <a class="btn btn-primary" href="logout.php">【ログアウト】</a> <br>
        </div>
        <hr>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>