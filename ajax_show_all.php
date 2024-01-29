<?php
//セッション開始

session_start();
//ログイン確認
if (!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))) {
    header('Location: login.html');
    exit();
}
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');

//リンクから受け取ったメッセージID

$teamId = 1;
$dbh = connectDB(); //DBへの接続
if($dbh) {
    //データベースへの問い合わせSQL文(文字列)
    $sql = "SELECT * FROM `post_tb` WHERE channel_id = :channel_id";
    $sth = $dbh->prepare($sql); //SQLの準備
    $sth->bindValue(':channel_id', $_SESSION['channel_id'], PDO::PARAM_INT); //プレースホルダーに値をバインド
    $sth->execute(); //SQLの実行
    $res = $dbh->query($sql); //SQL文の実行
    //取得したデータを配列に格納
    $data = $res->fetchAll(PDO::FETCH_ASSOC);

    //ヘッダの指定でjsonの動作を安定させる
    header('Content-type: application/json');
    $json = json_encode($data); //json形式に変換
    echo $json; //json形式のデータを出力
    $dbh = null; //DB切断
}