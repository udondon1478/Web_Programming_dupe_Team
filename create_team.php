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

        <!-- 条件参照用メモ -->
        <!-- 適宜notionの設計図を確認すべし -->
        <!-- 管理者権限は関係なし -->
        <!-- データベース「team_tb」にチームを追加 -->
        <!-- フィールドの項目は「team_name」「access_users」 -->
        <div>
            <h2>チーム作成</h2>
            <form action="insert_team.php" method="post">
                <div class="form-group">
                    <label for="team_name">チーム名</label>
                    <input type="text" class="form-control" id="team_name" name="team_name" placeholder="チーム名を入力してください">
                </div>
                <div class="form-group">
                    
                    <!-- アクセス可能なユーザーをデータベースから選択、user_tbのフィールド「username」から選択できるようにする -->
                    <!-- JSONでの複数保存、一番めんどくさそうな部分、notionの設計図を確認 -->

                    <!--
                    // データベースに接続
                    $dbh = connectDB();
                    if ($dbh) {
                        // データベースに接続成功
                        $sql = 'SELECT username FROM user_tb';
                        $sth = $dbh->prepare($sql);
                        $sth->execute();
                        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            echo '<input type="checkbox" name="access_users[]" value="' . $row['username'] . '">' . $row['username'] . '<br>';
                        }
                    } else {
                        // データベースに接続失敗
                        echo 'データベースに接続できません。';
                    }
                -->

                    <button type="submit" class="btn btn-primary">作成</button>
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