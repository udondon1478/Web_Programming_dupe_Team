<?php
//接続用関数の呼び出し
require_once(__DIR__ . '/functions.php');

//セッションの生成
session_start();
if (!(isset($_POST['email']) && isset($_POST['password']))) {
    header('Location:login.html');
}
//ユーザ名 / パスワード
$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
$password = htmlspecialchars($_POST['password'], ENT_QUOTES);
//DBへの接続
$dbh = connectDB(); 

if ($dbh) {
    //データベースへの問い合わせSQL文(文字列)
    $sql = "SELECT * FROM `user_tb` WHERE `email` = :email";
    $sth = $dbh->prepare($sql); //SQLの準備
    $sth->bindValue(':email',$email,PDO::PARAM_STR); //値のバインド
    $sth->execute(); //SQLの実行
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);//結果の取得
    //パスワードの比較
    if(count($result) == 1) {//配列数が唯一の場合
        if(password_verify($password,$result[0]['password'])){
            $login = 'OK'; //ログイン成功
            $_SESSION['name'] = $result[0]['username']; //ユーザ名をセッション変数に保存
            $_SESSION['id'] = $result[0]['id']; //ユーザIDをセッション変数に保存
            $_SESSION['admin_flag'] = $result[0]['is_admin'];   //admin_flagをセッション変数に保存
        }else {
            $login = 'ERROR'; //ログイン失敗
        }
    }else{
        $login = 'ERROR';//ログイン失敗
    }
}


//認証
//if (($user == 'x22004') && ($pass == 'webphp')) {
    if(count($result) == 1){//配列数が唯一の場合
    //ログイン成功
    $login = 'OK';
    //表示用ユーザ名をセッション変数に保存
    $_SESSION['name'] = $result[0]['name'];
} else {
    //ログイン失敗
    $login = 'Error';
}
$sth = null; //データの消去
$dbh = null; //DBを閉じる

//セッション変数に代入
$_SESSION['login'] = $login;
$_SESSION['result'] = $result;

//移動
if ($login == 'OK') {
    //ログイン成功 : 掲示板メニュー画面へ
    header('Location: top_page.php');
    exit();
} else {
    //ログイン失敗 : ログインフォーム画面へ
    header('Location: login.html');
    exit();
}
?>