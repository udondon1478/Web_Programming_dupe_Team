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

        <div class="menu container">
            <h1>▪️掲示板メニュー <br></h1>
            <nav class="navbar navbar-expand-sm">
                <div class="navbar-nav">

                    <?php

                    echo '<a class="nav-item nav-link" href="create_account.php">アカウントの作成</a> <br>';

                    ?>

                    <a class="nav-item nav-link" href="top_page.php">トップページ</a> <br>
                    <a class="nav-item nav-link" href="create_team.php">チームを作成</a>
                </div>
            </nav>

        </div>

        <!--チェックでユーザー選択-->
        <div class="checks">
            <div class="owners">
                <h2>チームのユーザー</h2>
                <?php
                //DBへの接続
                $dbh = connectDB();
                if ($dbh) {
                    //DB接続成功
                    $sql = 'SELECT * FROM `channel_users_tb`';
                    $sth = $dbh->prepare($sql);
                    $sth->execute();
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);





                    //ユーザー名を取得するためにはこっちもデータ取ってこないとダメ
                    $sql = 'SELECT * FROM `user_tb`';
                    $sth = $dbh->query($sql);
                    $result2 = $sth->fetchAll(PDO::FETCH_ASSOC);

                ?>
                    <form action="add_to_private_confirm.php?channel_id=<?php echo $_GET['channel_id']; ?>" method="post">
                        <?php
                        foreach ($result as $row) {
                            foreach ($result2 as $row2) {

                                echo '<input type="checkbox" name="id' . $row2['id'] . '"' .  'value="' . $row2['id'] . '">' . $row2['username'] . '<br>';
                            }
                        }
                        ?>
                        <input type="hidden" name="team_id" value="<?php echo $_SESSION['team_id']; ?>">
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