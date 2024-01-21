<?php
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');

//DBへの接続
$dbh = connectDB();

//データベースの接続確認
if (!$dbh) {
    //接続できていない場合
    echo 'DBに接続できていません';
    exit();
}

//テーブルが存在するかを確認するSQL文
$sql = "show tables";
$sth = $dbh->query($sql); //SQLの実行
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
if (0 < count($result)) {
    //データベース構築済み
    echo "データベース構築済みです。<br>";
    echo "<a href='db_drop.php'>DBの削除</a>";
    exit();
}

echo "データベースを構築します。。。<br>";

$sql = "CREATE TABLE `user_tb` (`id` INT AUTO_INCREMENT PRIMARY KEY,`email` VARCHAR(255) NOT NULL UNIQUE,`password` VARCHAR(255) NOT NULL,`username` VARCHAR(255) NOT NULL,`is_admin` BOOLEAN DEFAULT FALSE,`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";
$dbh->exec($sql); //SQLの実行
echo "user_tbを作成しました<br>";


//テスト用管理者ユーザの登録
$sql = "INSERT INTO `user_tb` (`email`,`password`,`name`,`is_admin`)
VALUES('x22004xx@aitech.ac.jp','".password_hash('webphp',PASSWORD_DEFAULT)."','あん',TRUE);";
$dbh->exec($sql);//SQLの実行
echo "テスト用管理者ユーザを登録しました<br>";

//テスト用一般ユーザの登録
$sql = "INSERT INTO `user_tb` (`email`,`password`,`name`,`is_admin`)
VALUES('x22999xx@aitech.ac.jp','".password_hash('webphp',PASSWORD_DEFAULT)."','ゆう',FALSE);";
$dbh->exec($sql);//SQLの実行
echo "テスト用一般ユーザを登録しました<br>";

//チームテーブルの作成
$sql = "CREATE TABLE `team_tb` (`id` INT AUTO_INCREMENT PRIMARY KEY, `team_name` VARCHAR(255) NOT NULL, access_users JSON NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);";
$dbh->exec($sql);//SQLの実行
echo "team_tbを作成しました<br>";


//チャンネルテーブルの作成
$sql = "CREATE TABLE `channel_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`channel_name` VARCHAR(255) NOT NULL,`status` INT NOT NULL);";    //status: 0=private, 1=public, 2=share
$dbh->exec($sql);//SQLの実行
echo "チャンネルを登録しました<br>";

//一般の登録
$sql = "INSERT INTO `channel_tb` (`name`,`is_public`)
VALUES('一般',TRUE);";
$dbh->exec($sql);//SQLの実行
echo "一般チャンネルを登録しました<br>";

//雑談(private)の登録
$sql = "INSERT INTO `channel_tb` (`name`,`is_public`)
VALUES('雑談',FALSE);";
$dbh->exec($sql);//SQLの実行
echo "雑談チャンネルを登録しました<br>";

//投稿の登録
$sql = "CREATE TABLE `post_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`channel_id` INT NOT NULL,`user_id` INT NOT NULL,`name` VARCHAR(255) NOT NULL,`content` TEXT,`image_path` VARCHAR(255),`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (`channel_id`) REFERENCES `channel_tb`(`id`),FOREIGN KEY (`user_id`) REFERENCES `user_tb`(`id`));";
$dbh->exec($sql);//SQLの実行
echo "投稿を登録しました<br>";

echo "<a href='db_drop.php'>DBの削除<br></a>";
?>