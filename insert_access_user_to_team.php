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
$is_owner = $_POST['is_owner'];
$count = count($is_owner);
for ($i = 0; $i<$count; $i++){
    if(is_null($is_owner[$i])){
        $is_owner[$i] = 0;
    }else{
        $is_owner[$i] = 1;
    }
}
?>

<?php
//DBへの接続
$dbh = connectDB();

//別々のレコードに対してINSERT

if(isset($is_owner)){
    $count = count($user_id);
    for ($i = 0; $i < $count; $i++) {
        $value = $user_id[$i];
        $owner = $is_owner[$i];
        $sql = "INSERT INTO `team_users_tb` (`team_id`, `user_id`, `is_owner`) VALUES (:team_id, :user_id, :is_owner)";
        $sth = $dbh->prepare($sql);
        $sth->bindValue(':team_id', $team_id, PDO::PARAM_INT);
        $sth->bindValue(':user_id', $value, PDO::PARAM_INT);
        $sth->bindValue(':is_owner', $owner, PDO::PARAM_INT);
        $sth->execute();
    }
}else{
    foreach ($user_id as $value){
        $sql = "INSERT INTO `team_users_tb` (`team_id`, `user_id`) VALUES (:team_id, :user_id)";
        $sth = $dbh->prepare($sql);
        $sth->bindValue(':team_id', $team_id, PDO::PARAM_INT);
        $sth->bindValue(':user_id', $value, PDO::PARAM_INT);
        $sth->execute();
    }
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

        <div class="navbar navbar-expand-sm">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="top_page.php">【メニュー】</a>
                <a class="nav-item nav-link" href="logout.php">【ログアウト】</a> <br>
            </div>

        </div>

        <hr>
        
        ▪️アカウントを登録しました <br>

        ▪️テンプレートチャンネルを作成しました

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>