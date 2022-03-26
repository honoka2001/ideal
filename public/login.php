<?php
session_start();
require_once '../functions.php';
require_once '../classes/UserLogic.php';


if(!$email = filter_input(INPUT_POST, 'email')){
  $err['email'] = 'メールアドレスを記入してください。';
}else{
  $_SESSION['keep']['email'] = $email;
  var_dump($_SESSION['keep']['email']);
}

if(!$password = filter_input(INPUT_POST, 'password')){
  $err['password'] = 'パスワードを記入してください。';
}else{
  $_SESSION['keep']['password'] = $password;
}

if(count($err) > 0){
  //エラーがあった場合は戻す
  $_SESSION['err'] = $err;
  header('Location: login_form.php');
  return;
}

//ログイン成功時の処理
$result = UserLogic::login($email, $password);
header('Location: home.php');

//ログイン失敗時の処理
if (!$result) {
  header('Location: login_form.php');
  return;
}
