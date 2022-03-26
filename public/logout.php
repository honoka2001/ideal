<?php
session_start();
require_once '../classes/UserLogic.php';

if (!$logout = filter_input(INPUT_POST, 'logout')){
  exit('不正なリクエストです');
}

// ログインしているか判定し、セッションが切れていたらログインしてくださいとメッセージを出す。
$result = UserLogic::checkLogin();

if (!$result) {
  exit('セッションが切れましたので、ログインし直してください。');
}

//ログアウトする
UserLogic::logout();
header ('Location: login_form.php');

?>
