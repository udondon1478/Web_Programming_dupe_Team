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
$sql = "INSERT INTO `user_tb` (`email`,`password`,`username`,`is_admin`)
VALUES('x22004xx@aitech.ac.jp','".password_hash('webphp',PASSWORD_DEFAULT)."','あん',TRUE);";
$dbh->exec($sql);//SQLの実行
echo "テスト用管理者ユーザを登録しました<br>";

//テスト用一般ユーザの登録
$sql = "INSERT INTO `user_tb` (`email`,`password`,`username`,`is_admin`)
VALUES('x22999xx@aitech.ac.jp','".password_hash('webphp',PASSWORD_DEFAULT)."','ゆう',FALSE);";
$dbh->exec($sql);//SQLの実行
echo "テスト用一般ユーザを登録しました<br>";

//チームテーブルの作成
$sql = "CREATE TABLE `team_tb` (`id` INT AUTO_INCREMENT PRIMARY KEY, `team_name` VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);";
$dbh->exec($sql);//SQLの実行
echo "team_tbを作成しました<br>";

//チームメンバー中間テーブルの作成
$sql = "CREATE TABLE `team_users_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`team_id` INT NOT NULL,`user_id` INT NOT NULL,`is_owner` BOOLEAN DEFAULT FALSE,FOREIGN KEY (`team_id`) REFERENCES `team_tb`(`id`),FOREIGN KEY (`user_id`) REFERENCES `user_tb`(`id`));";
$dbh->exec($sql);//SQLの実行
echo "team_users_tbを作成しました<br>";

//チャンネルテーブルの作成
$sql = "CREATE TABLE `channel_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`team_id` INT NOT NULL,`channel_name` VARCHAR(255) NOT NULL, `status` BOOLEAN DEFAULT TRUE,FOREIGN KEY (`team_id`) REFERENCES `team_tb`(`id`));";    //status: 0=private, 1=public
$dbh->exec($sql);//SQLの実行
echo "channel_tbを作成しました<br>";

//チャンネルとチームの中間テーブルの作成
$sql = "CREATE TABLE `team_channels_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`team_id` INT NOT NULL,`channel_id` INT NOT NULL,FOREIGN KEY (`team_id`) REFERENCES `team_tb`(`id`),FOREIGN KEY (`channel_id`) REFERENCES `channel_tb`(`id`));";
$dbh->exec($sql);//SQLの実行
echo "team_channels_tbを作成しました<br>";

//中間テーブルusers_channelsの作成
//accessed_at: チャンネルに対してユーザーが最終アクセスをした日時
$sql = "CREATE TABLE `users_channels`(`id` INT AUTO_INCREMENT PRIMARY KEY,`user_id` INT NOT NULL,`channel_id` INT NOT NULL,`accessed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (`user_id`) REFERENCES `user_tb`(`id`),FOREIGN KEY (`channel_id`) REFERENCES `channel_tb`(`id`));";
$dbh->exec($sql);//SQLの実行
echo "users_channelsを作成しました<br>";

//投稿テーブルの作成
//nameはuser_idが一致するuser_tbのusernameを格納
$sql = "CREATE TABLE `post_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`team_id` INT NOT NULL,`channel_id` INT NOT NULL,`user_id` INT NOT NULL,`name` VARCHAR(255) NOT NULL,`title` VARCHAR(255) NOT NULL,`content` VARCHAR(255) NOT NULL,`image_path` VARCHAR(255),`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (`team_id`) REFERENCES `team_tb`(`id`),FOREIGN KEY (`channel_id`) REFERENCES `channel_tb`(`id`),FOREIGN KEY (`user_id`) REFERENCES `user_tb`(`id`));";
$dbh->exec($sql);//SQLの実行
echo "post_tbを作成しました<br>";

//返信テーブルの作成
$sql = "CREATE TABLE `reply_tb`(`id` INT AUTO_INCREMENT PRIMARY KEY,`post_id` INT NOT NULL,`user_id` INT NOT NULL,`name` VARCHAR(255) NOT NULL,`content` TEXT,`image_path` VARCHAR(255),`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (`post_id`) REFERENCES `post_tb`(`id`),FOREIGN KEY (`user_id`) REFERENCES `user_tb`(`id`));";
$dbh->exec($sql);//SQLの実行
echo "reply_tbを作成しました<br>";

echo "<a href='db_drop.php'>DBの削除<br></a>";
?>