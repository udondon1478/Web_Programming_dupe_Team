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
$_SESSION['channel_id'] = $_GET['channel_id'];
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

        <?php
        $sql = "INSERT INTO `post_tb` (`team_id`, `channel_id`, `user_id`, `name`, `title`, `content`, `created_at`) VALUES (:team_id, :channel_id, :user_id, :name, :title, :content, :created_at)";

        $sth = $dbh->prepare($sql); //SQLの準備
        $sth->bindValue(':team_id', $_SESSION['team_id'], PDO::PARAM_INT); //プレースホルダーに値をバインド
        $sth->bindValue(':channel_id', $_SESSION['channel_id'], PDO::PARAM_INT); //プレースホルダーに値をバインド
        $sth->bindValue(':user_id', "1", PDO::PARAM_INT); //プレースホルダーに値をバインド
        //user_idが1のusernameをuser_tbから取得
        $sth->bindValue(':name', "udon", PDO::PARAM_STR);
        $sth->bindValue(':title', "TEST", PDO::PARAM_STR); //プレースホルダーに値をバインド
        $sth->bindValue(':content', "sampletext", PDO::PARAM_STR); //プレースホルダーに値をバインド
        $sth->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR); //プレースホルダーに値をバインド
        $sth->execute(); //SQLの実行
        ?>

        <div class="show_post">
            <h2>チャンネル:<?php echo $_SESSION['channel_name']; ?></h2>
            <h2>メッセージ一覧</h2>
            <!-- チャンネルidが1と一致するレコードを全て取得し、テーブルで表示 -->
            <?php
            $sql = "SELECT * FROM `post_tb` WHERE channel_id = :channel_id";
            $sth = $dbh->prepare($sql); //SQLの準備
            $sth->bindValue(':channel_id', $_SESSION['channel_id'], PDO::PARAM_INT); //プレースホルダーに値をバインド
            $sth->execute(); //SQLの実行
            $result = $sth->fetchAll(PDO::FETCH_ASSOC); //結果の取得
            foreach ($result as $row) {
                echo '<p class="post">' . $row['name'] . ' :' . $row['id'] . '">' . $row['title'] . $row['content'] . '</p><br>';
            }
            ?>
        </div>

        <div class="container">
            <table class="table" border="1" id="all_show_result">
                <thread>
                    <tr bgcolor="#cccccc">
                        <div class="prep">
                            <th scope="col">ID</th>
                            <th class="col">タイトル</th>
                            <th class="col">メッセージ</th>
                            <th class="col">ユーザ</th>
                            <th class="col">投稿日時</th>
                        </div>
                    </tr>
                </thread>
            </table>
            <hr>
        </div>

        <div class="logout">
            <a class="btn btn-primary" href="logout.php">【ログアウト】</a> <br>
        </div>
        <hr>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="js/get_post.js"></script>
</body>

</html>