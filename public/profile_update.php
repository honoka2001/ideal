<?php
session_start();

require_once ('../dbconnect.php');
// require_once '../classes/BlogLogic.php';
require_once '../classes/UserLogic.php';

$login_user = $_SESSION['login_user'];
$login_user_id = $_SESSION['login_user']['id'];

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

$intro = filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_SPECIAL_CHARS);

$err_msgs = array();

// バリデーション
if(empty($name)){
  $name = $login_user['name'];
}
if(mb_strlen($intro) > 100){
  $err_msgs['intro'] = '100文字以内で入力してください。';
}

// ファイルのバリデーション
// $file = $login_user_id['picture']
$file = $_FILES['image'];
$filename = basename($file['name']);
$file_err = $file['error'];
$filesize = $file['size'];


//画像が更新されていないとき
if(!empty($filename)){

  // ファイルサイズ
  if($filesize > 1048576 || $file_err == 2){
    $err_msgs['file_size'] = 'ファイルサイズは1MB未満にしてください。';
  }
  
  //拡張子
  $allow_ext = ['jpg', 'jpeg', 'png'];
  $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
  
  if(!in_array(strtolower($file_ext), $allow_ext)){
    $err_msgs['file_ext'] = '画像ファイルを添付してください。';
  }
  
  if(count($err_msgs) > 0 && !(isset($err_msgs['file_size']) || isset($err_msgs['file_ext']))){
    $err_msgs['file_rewrite'] = '恐れ入りますが、画像を改めて指定してください。';
  }
}else{
  $picture = $login_user['picture'];
}

if(count($err_msgs) > 0) {
  //エラーがあった場合は戻す
  $_SESSION['err_msgs'] = $err_msgs;
  header('Location: profile_update_form.php');
  return;
}

//エラーがない時
if(count($err_msgs) === 0){

  //画像の変更があった時
  if(!empty($filename)){
    
    // 画像をアップロードする
    $tmp_path = $file['tmp_name'];
    $upload_dir = 'member_picture/';
    $save_filename = date('YmdHis') . $filename;
    $save_path = $upload_dir . $save_filename;
    move_uploaded_file($tmp_path, $save_path);

    $picture = $save_filename;
  }
  
  //アップロードする
  $update_user =  UserLogic::profUpdate($name, $intro, $picture, $login_user_id);
  if($update_user){
    $login_user = UserLogic::getById($login_user_id);
    $_SESSION['login_user'] = $login_user;
    $msg = '編集完了';
    $_SESSION['suc']=$msg;
    header ('Location: mypage.php');

  }else{
    echo '編集に失敗しました。';
  }
  
}else{
    $_SESSION['err_msgs'] = $err_msgs;
    var_dump($err_msgs);
    header ('Location: profile_update_form.php');
  }

?>
<a href="./mypage.php">戻る</a>