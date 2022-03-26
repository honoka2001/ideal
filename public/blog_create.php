<?php
session_start();

require_once ('../dbconnect.php');
require_once '../classes/BlogLogic.php';


$login_user = $_SESSION['login_user']['id'];

//ファイル関連の取得
$file = $_FILES['photo'];

$filename = basename($file['name']);
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
$upload_dir = 'post_picture/';
$save_filename = date('YmdHis') . $filename;
$err_msgs = array();
$save_path = $upload_dir . $save_filename;

//入力の取得

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);

// バリデーション
if(empty($title)){
  $err_msgs['title'] = 'タイトルを入力してください。';
}else{
  $_SESSION['keep']['title'] = $title;
}

if(mb_strlen($title) > 30){
  $err_msgs['title_cnt'] = 'タイトルは30文字以内で入力してください。';
}else{
  $_SESSION['keep']['title'] = $title;
}

if(empty($content)){
  $err_msgs['content'] = '本文を入力してください。';
}else{
  $_SESSION['keep']['content'] = $content;
}

if(empty($category)){
  $err_msgs['category'] = 'カテゴリーを入力してください。';
}else{
  $_SESSION['keep']['category'] = $category;
}


//ファイルのバリデーション
//ファイルサイズが1MB未満か
if($filesize > 1048576 || $file_err == 2){
  $err_msgs['filesize'] = 'ファイルサイズは1MB未満にしてください。';
}

//拡張は画像形式か
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if(!in_array(strtolower($file_ext), $allow_ext)){
  $err_msgs['file_ext'] = '画像ファイルを添付してください';
}

if(count($err_msgs) > 0 && !(isset($err_msgs['file_size']) || isset($err_msgs['file_ext']))){
  $err_msgs['file_rewrite'] = '恐れ入りますが、画像を改めて指定してください。';
}

if(count($err_msgs) === 0){

  //ファイルはあるかどうか？
  if (is_uploaded_file($tmp_path)){
    if(move_uploaded_file($tmp_path, $save_path)){
      // echo $filename . 'を' . $upload_dir . 'アップしました。';

      //DBに保存
      $result =  BlogLogic::fileSave($filename, $save_path, $title, $content, $category, $login_user);
      
      if($result){
        $msg = '投稿完了';
        $_SESSION['suc']=$msg;
        $_SESSION['keep']=array();

        header ('Location: home.php');
        // echo 'データベースにほぞんしました！';
      }else{
        // echo 'データベースへの保存が失敗しました！';
       header ('Location: new_post.php');

      }
    }else{
      // echo 'ファイルが保存できませんでした。';
      header ('Location: new_post.php');
    }
  } else {
    $err_msgs['file'] = 'ファイルが選択されていません。';
  }
}else{
    $_SESSION['err_msgs'] = $err_msgs;
    header ('Location: new_post.php');
  }

?>
<a href="./new_post.php">戻る</a>