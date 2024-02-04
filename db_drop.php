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

$tables = ['user_tb','team_tb','team_users_tb','channel_tb', 'channel_users_tb','team_channels_tb','users_channels','post_tb','reply_tb','team_icon_tb'];//テーブルの配列

//外部キー制約を一時的に無効にする
$dbh->exec('SET foreign_key_checks=0;');

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS $table";
    $dbh->exec($sql);
}

//外部キー制約を再度有効にする
$dbh->exec('SET foreign_key_checks = 1;');
echo 'テーブルを削除しました。<br>';
?>
<a href="db_setup.php">DBの構築</a>