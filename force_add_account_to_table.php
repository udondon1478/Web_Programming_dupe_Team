<?php
//セッションの開始
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
    header('Location: login.html');
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');
?>

<?php
//postで受け取った値を変数に格納
//name="access_users"は配列
$team_id = $_SESSION['team_id'];
$user_id = $_POST['access_users'];
?>

<?php
//DBへの接続
$dbh = connectDB();

$sql = 'SELECT `team_id`,`user_id` FROM `team_users_tb` WHERE `team_id` = :team_id';
$sth = $dbh->prepare($sql);
$sth->bindValue(':team_id', $team_id, PDO::PARAM_INT);
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

//別々のレコードに対してINSERT
//DB内に多重にアカウントが追加されてしまう、時間に余裕があればif文でチェック
foreach ($user_id as $id) {
    $sql = 'INSERT INTO `team_users_tb` (`team_id`, `user_id`) SELECT :team_id, :user_id FROM DUAL WHERE NOT EXISTS (SELECT * FROM `team_users_tb` WHERE `team_id` = :team_id AND `user_id` = :user_id)';
    $sth = $dbh->prepare($sql);
    $sth->bindValue(':team_id', $team_id, PDO::PARAM_INT);
    $sth->bindValue(':user_id', $id, PDO::PARAM_INT);
    $sth->execute();
}

$dbh = null; //DB切断
?>
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
        
        ▪️アカウントを登録しました <br>



        <a class="btn btn-primary" href="show_message.php">メッセージを読む
        </a>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>