<?php
session_start();

require_once ('../dbconnect.php');
require_once '../classes/BlogLogic.php';



$login_user = $_SESSION['login_user']['id'];

//入力の取得
// $blogs = $_POST;
// $id = $_SESSION['blog_detail']['id'];

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);

// バリデーション
if(empty($title)){
  $err_msgs['title'] = 'タイトルを入力してください。';
}
if(mb_strlen($title) > 30){
  $err_msgs['title_cnt'] = 'タイトルは30文字以内で入力してください。';
  
}
if(empty($content)){
  $err_msgs['content'] = '本文を入力してください。';
}
if(empty($category)){
  $err_msgs['category'] = 'カテゴリーを入力してください。';
}



if(empty($err_msgs)){

  BlogLogic::fileUpdate();
  $msg = '編集完了';
  $_SESSION['suc']=$msg;
  header ('Location: mypage.php');


}else{

    $_SESSION['err_msgs'] = $err_msgs;
    header ('Location: blog_update_form.php');
  }

?>
<a href="./mypage.php">戻る</a>