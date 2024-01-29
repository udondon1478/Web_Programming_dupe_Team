<?php
//セッションの開始
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
    header('Location: login.html');
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');
?>

<!-- 条件参照用メモ -->
<!-- 適宜notionの設計図を確認すべし -->
<!-- 管理者権限は関係なし -->
<!-- データベース「team_tb」にチームを追加 -->
<!-- フィールドの項目は「team_name」 -->


<!-- postで受け取った値を変数に格納 -->
<?php
$team_name = $_POST['team_name'];
?>

<!-- team_tbに書き込むSQL文 -->
<?php
$dbh = connectDB();

if ($dbh) {
    $sql = 'INSERT INTO `team_tb`(`team_name`)
    VALUES("' . $team_name . '")';
    $sth = $dbh->query($sql); //SQLの実行
    //team_nameを書き込んだidを取得
    $team_id = $dbh->lastInsertId();
    $_SESSION['team_id'] = $team_id;
} else {
    echo 'DB接続に失敗しました。';
}
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

        <div class="navbar navbar-expand-sm">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="top_page.php">【メニュー】</a>
                <a class="nav-item nav-link" href="logout.php">【ログアウト】</a> <br>
            </div>

        </div>

        <hr>

        ▪️チームを登録しました <br>
        <div>
            <h2>アカウント追加</h2>
            <form action="insert_access_user_to_team.php" method="post">
                <div class="form-group">
                    <label for="access_users">アクセスユーザー</label><br>
                    <!-- アクセス可能なユーザーをデータベースから選択、user_tbのフィールド「username」から選択できるようにする -->
                    <?php
                    // データベースに接続
                    $dbh = connectDB();
                    if ($dbh) {
                        // データベースに接続成功
                        //表示はusername、送信するものはid
                        $sql = 'SELECT `id`,`username` FROM `user_tb`';
                        $sth = $dbh->query($sql); //SQLの実行
                        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            echo '<input type="checkbox" name="access_users[]" value="' . $row['id'] . '">' . $row['username'] . '<br>';
                        }
                    } else {
                        // データベースに接続失敗
                        echo 'データベースに接続できません。';
                    }
                    ?>
                    <button type="submit" class="btn btn-primary">アカウントを追加</button>
                </div>

                <div class="make_template">
                    <?php
                    // データベースに接続
                    $dbh = connectDB();
                    if ($dbh) {
                        $sql = "INSERT INTO `channel_tb`(`team_id`,`channel_name`,`status`)
                        VALUES('" . $_SESSION['team_id'] . "','" . "雑談" . "','1')";
                        $sth = $dbh->query($sql); //SQLの実行
                    }

                    // channel_tbの最新のidを取得し、team_channels_tbに紐づけ
                    // team_idはteam_tbのid
                    $channel_id = $dbh->lastInsertId();
                    $sql = "INSERT INTO `team_channels_tb`(`team_id`,`channel_id`)
                    VALUES('" . $_SESSION['team_id'] . "','" . $channel_id . "')";
                    $sth = $dbh->query($sql); //SQLの実行
                    ?>
                </div>
            </form>
        </div>

        <a class="btn btn-primary" href="show_message.php">メッセージを読む
        </a>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>