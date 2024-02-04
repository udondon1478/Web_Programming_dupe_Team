<?php
//セッションの開始
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
    header('Location: login.html');
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');
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

        <!--ドロップダウンによる役割設定-->
        <div class="dropdown">
            <div class="owners">
                <h2>チームの所有者</h2>
                <?php
                //DBへの接続
                $dbh = connectDB();
                if ($dbh) {
                    //DB接続成功
                    $sql = 'SELECT * FROM `team_users_tb` WHERE `team_id` = :team_id';
                    $sth = $dbh->prepare($sql);
                    $sth->bindValue(':team_id', $_GET['team_id'], PDO::PARAM_INT);
                    $sth->execute();
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

                    //ユーザー名を取得するためにはこっちもデータ取ってこないとダメ
                    $sql = 'SELECT * FROM `user_tb`';
                    $sth = $dbh->query($sql);
                    $result2 = $sth->fetchAll(PDO::FETCH_ASSOC);

                    //team_idが$_SESSION['team_id']と一致していて、役割が「owner」の場合
                    ?>
                    <form action="change_role.php" method="post">
                    <?php
                    foreach ($result as $row) {
                        foreach ($result2 as $row2) {
                            if ($row['user_id'] == $row2['id'] && $row['is_owner'] == '1') {
                                echo "現在の所有者: " . $row2['username'] . '<br>';
                                //役割のプルダウンメニューを表示
                                echo "<select name='role{$row['user_id']}'>";
                                echo '<option value="1">管理者</option>';
                                echo '<option value="0">一般ユーザー</option>';
                                echo '</select><br>';
                                //後からクラス挿入して整形予定
                            }else if($row['user_id'] == $row2['id'] && $row['is_owner'] != '1'){
                                echo "一般ユーザー: " . $row2['username'] . '<br>';
                                //役割のプルダウンメニューを表示
                                echo "<select name='role{$row['user_id']}'>";
                                echo '<option value="1">管理者</option>';
                                echo '<option value="0">一般ユーザー</option>';
                                echo '</select><br>';
                                //後からクラス挿入して整形予定
                            }
                            
                        }
                    }
                    ?>
                    <input type="hidden" name="team_id" value="<?php echo $_GET['team_id']; ?>">
                    <input type="submit" class="btn btn-primary" value="変更">
                    </form>

                    <?php
                }
                ?>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>