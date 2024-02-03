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

        <div class="navbar navbar-expand-sm">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="top_page.php">【メニュー】</a>
                <a class="nav-item nav-link" href="logout.php">【ログアウト】</a> <br>
            </div>

        </div>

        <hr>
        
        <div>
            <h2>チャンネル作成</h2>
            <form action="insert_channel_to_team.php" method="post">
                <div class="form-group">
                    <form action="insert_channel_to_team.php" method="post">
                        <div class="form-group">
                            <!-- team_idを渡す -->
                            <input type="hidden" class="form-control" id="team_id" name="team_id" value="<?php echo $_GET['team_id']; ?>">

                            <label for="channel_name">チャンネル名</label>
                            <input type="text" class="form-control" id="channel_name" name="channel_name">
                            <label for="status">公開状態の選択</label>
                            <input type="radio" name="status" value="1">公開
                            <input type="radio" name="status" value="0">非公開
                            <button type="submit" class="btn btn-primary">作成</button>
                    </form>
                </div>
            </form>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>