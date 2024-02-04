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

                        //アカウント追加
                        echo '<a class="nav-item nav-link" href="add_account.php">アカウントの追加</a> <br>';
                    }
                    ?>
                    <a class="nav-item nav-link" href="top_page.php">トップページ</a> <br>
                    <!--プライベートチャンネルへのアクセス権の付与-->
                    <a href="add_account_to_private_channel.php?channel_id=<?php echo $_GET['channel_id'] ?>" class="btn btn-primary">アカウントをプライベートチャンネルに追加</a>

                </div>
            </nav>
        </div>

        <div class="file_upload">
            <?php
            //ファイル名の取り出し
            $file_name = $_FILES['file']['name'];
            //ファイル(MIME)タイプの取り出し
            $file_type = $_FILES['file']['type'];
            //一時ファイル名の取り出し
            $temp_name = $_FILES['file']['tmp_name'];

            //保存先のディレクトリ
            $dir = 'uploads/';
            //保存先のファイル名
            $upload_name = $dir . $file_name;
            //サムネイル画像の保存先ディレクトリ
            $dir_s = 'uploads/s/';
            //サムネイル画像の保存先のファイル名
            $upload_name_s = $dir_s . $file_name;
            //画像の拡張子ならファイルをアップロード
            if ($file_type == 'image/jpeg' || $file_type == 'image/png' || $file_type == 'image/gif' || $file_type == 'image/webp') {
                $result = move_uploaded_file($temp_name, $upload_name);
                if ($result) {
                    echo $file_name . 'をアップロードしました。';

                    $image_size = getimagesize($upload_name);
                    $width = $image_size[0];
                    $height = $image_size[1];

                    //サムネイルのサイズ
                    $width_s = 120;
                    $height_s = round($width_s * $height / $width);

                    //アプロードされた画像を取り出す
                    $image = imagecreatefromjpeg($upload_name);
                    //サムネイルの大きさの画像を新規作成
                    $image_s = imagecreatetruecolor($width_s, $height_s);
                    //アップロードされた画像からサムネイル画像を作成
                    $result_s = imagecopyresampled($image_s, $image, 0, 0, 0, 0, $width_s, $height_s, $width, $height);

                    if ($result_s) {
                        //サムネイル画像の新規成功
                        //サムネイル画像の保存関数. (画像,ファイル名)
                        if (imagejpeg($image_s, $upload_name_s)) {
                            echo 'サムネイル画像をアップロードしました。';
                            //画像の破棄
                        } else {
                            echo 'サムネイル画像のアップロードに失敗しました。';
                        }
                    } else {
                        echo 'サムネイル画像の作成に失敗しました。';
                    }
                } else {
                    echo 'アップロード失敗';
                }
            } else {
                echo '画像ファイルをアップロードしてください。';
            }
            imagedestroy($image);
            imagedestroy($image_s);
            ?>

            <img src="<?php echo $upload_name; ?>" alt="">
            <img src="<?php echo $upload_name_s; ?>" alt="">
        </div>

        <div class="insert">
            <?php
            //データベースへの問い合わせSQL文(文字列)
            $sql = "INSERT INTO `post_tb` (`team_id`,`channel_id`,`user_id`,`name`,`title`,`content`,`image_path`,`thumbnail_path`) VALUES (:team_id,:channel_id,:user_id,:name,:title,:content,:image_path,:thumbnail_path)";
            $sth = $dbh->prepare($sql); //SQLの準備
            $sth->bindValue(':team_id', $_SESSION['team_id'], PDO::PARAM_INT); //値のバインド
            $sth->bindValue(':channel_id', $_POST['channel_id'], PDO::PARAM_INT); //値のバインド
            $sth->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT); //値のバインド
            $sth->bindValue(':name', $_SESSION['name'], PDO::PARAM_STR); //値のバインド
            $sth->bindValue(':title', $_POST['title'], PDO::PARAM_STR); //値のバインド
            $sth->bindValue(':content', $_POST['content'], PDO::PARAM_STR); //値のバインド
            $sth->bindValue(':image_path', $upload_name, PDO::PARAM_STR); //値のバインド
            $sth->bindValue(':thumbnail_path', $upload_name_s, PDO::PARAM_STR); //値のバインド
            $sth->execute(); //SQLの実行
            echo '投稿しました';
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>