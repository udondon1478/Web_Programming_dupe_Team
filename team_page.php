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

        <!-- channelを表示 -->
        <div class="utilies">
            <a href="create_channel_at_team.php?team_id=<?php echo $_GET['team_id'] ?>" class="btn btn-primary">チャンネルを作成</a>
            <!-- アカウントをチームに追加-->
            <a href="force_add_account_at_team.php?team_id=<?php echo $_GET['team_id'] ?>" class="btn btn-primary">アカウントを追加</a>

            <h2>チャンネル一覧</h2>
            <!-- channel_tbからchannel_nameを取得、リンク先はchannel_page.php -->
            <!-- team_idが$_GET['team_id']と一致するレコード群の情報を取得 -->
            <?php
            $sql = 'SELECT * FROM `channel_tb` WHERE `team_id` = :team_id';
            $sth = $dbh->prepare($sql); //SQLの準備
            $sth->bindValue(':team_id', $_GET['team_id'], PDO::PARAM_STR); //プレースホルダーに値をバインド
            $sth->execute(); //SQLの実行


            foreach ($sth as $row) {
                echo '<a class="btn btn-primary btn_channel-' . $row['id'] . '" href="channel_page.php?channel_id=' . $row['id'] . '">' . $row['channel_name'] . '</a> <br>';
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

<style>
    /*リセットCSS*/
    * {
        font-weight: normal;
    }

    /* post_tbのcreated_atとusers_channelsのaccessed_atを比較 */
    /* {channel_id}がcreated_at > accessed_at ならbtn_channel-[id]を太字に */
    <?php
    $sql = "SELECT * FROM `post_tb` WHERE `team_id` = :team_id";
    $sth = $dbh->prepare($sql); //SQLの準備
    $sth->bindValue(':team_id', $_GET['team_id'], PDO::PARAM_INT); //プレースホルダーに値をバインド
    //$sth=$dbh->query($sql);の場合、SQL文の中で変数を使えないらしい
    $sth->execute(); //SQLの実行
    $result = $sth->fetchAll(PDO::FETCH_ASSOC); //結果の取得

    $sql = 'SELECT * FROM `users_channels` WHERE `user_id` = :user_id';
    $sth = $dbh->prepare($sql); //SQLの準備
    $sth->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT); //プレースホルダーに値をバインド
    $sth->execute(); //SQLの実行
    $result2 = $sth->fetchAll(PDO::FETCH_ASSOC); //結果の取得

    foreach ($result as $row) {
        foreach ($result2 as $row2) {
            //$rowのchannel_idと$row2のchannel_idが一致していてcreated_at > accessed_atならbtn_channel-[id]を太字に
            if ($row['channel_id'] == $row2['channel_id'] && $row['created_at'] > $row2['accessed_at']) {
                echo '.btn_channel-' . $row['channel_id'] . ' {font-weight: bold;}';
            }
        }
    }
    /*
    if ($row['created_at'] > $row2['accessed_at']) {
                echo '.btn_channel-' . $row['channel_id'] . ' {
                    font-weight: 900;
                }';
            } */
    ?>
</style>