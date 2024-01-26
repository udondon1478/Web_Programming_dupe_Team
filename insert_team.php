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
<!-- フィールドの項目は「team_name」「access_users」 -->

<!-- アクセス可能なユーザーをデータベースから選択、user_tbのフィールド「id」をteam_tbのフィールド「access_users」に追加できるようにする -->
<!-- JSONでの複数保存、一番めんどくさそうな部分、notionの設計図を確認 -->

<!-- postで受け取った値を変数に格納 -->
<?php
$team_name = $_POST['team_name'];
//access_usersは配列として受け取る
//$_POST['access_users']の要素数分繰り返す
for ($i = 0; $i < count($_POST['access_users']); $i++) {
    $access_users[] = $_POST['access_users'][$i];
}
?>

<!-- データベースに接続 -->
<?php
$dbh = connectDB();

if ($dbh) {

    // データベースへ書き込むSQL文
    //access_usersはupdateステートメントのSET句に、JSON_ARRAY_APPEND関数を使って配列を追加
    $sql = "UPDATE team_tb SET access_users = JSON_ARRAY_APPEND(access_users, '$', ?) WHERE team_name = ?";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(" 1", $access_users, PDO::PARAM_STR); // " 1", $team_name, PDO::
    $sth->bindParam(" 2", $team_name, PDO::PARAM_STR);
    $sth->execute();
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row["access_users"] == "[]") {
        echo "チーム名またはアクセスユーザーを入力してください";
    } else {
        echo "チームを作成しました";
    }


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

        <a class="btn btn-primary" href="show_message.php">メッセージを読む
        </a>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>