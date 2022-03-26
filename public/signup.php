<?php
require_once ('../dbconnect.php');

session_start();


if(!$name = filter_input(INPUT_POST, 'name')){
  echo $err['name'] = 'ユーザー名を記入してください';
}else{
  $_SESSION['keep']['name'] = $name;
}

if(!$email = filter_input(INPUT_POST, 'email')){
  $err['email'] = 'メールアドレスを記入してください';
}else{
  $_SESSION['keep']['email'] = $email;
}

$password = filter_input(INPUT_POST, 'password');

if (!preg_match("/\A[a-z\d]{8,100}+\z/i",$password)) {
  $err['password'] = 'パスワードは英数字8文字以上100文字以下にしてください。';
}else{
  $_SESSION['keep']['password'] = $password;
}

$password_conf = filter_input(INPUT_POST, 'password_conf');
if ($password !== $password_conf) {
  $err['password_conf'] = '確認用パスワードと異なっています';
}else{
  $_SESSION['keep']['password_conf'] = $password_conf;
}

//重複アカウントのチェック
if($email){
  $member = connect()->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=? ');
  $member->execute(array($email));
  $record = $member->fetch();
  if($record['cnt'] > 0){
    $err['email_dup'] = '指定されたメールアドレスはすでに登録されています';
  }
}

// ファイルのバリデーション
$file = $_FILES['image'];
$filename = basename($file['name']);
$file_err = $file['error'];
$filesize = $file['size'];

// ファイルサイズ
if($filesize > 1048576 || $file_err == 2){
  $err['file_size'] = 'ファイルサイズは1MB未満にしてください。';
}

//拡張子
$allow_ext = ['jpg', 'jpeg', 'png'];
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if(!in_array(strtolower($file_ext), $allow_ext)){
  $err['file_ext'] = '画像ファイルを添付してください。';
}

if(count($err) > 0 && !(isset($err['file_size']) || isset($err['file_ext']))){
  $err['file_rewrite'] = '恐れ入りますが、画像を改めて指定してください。';
}

if(count($err) > 0) {
  //エラーがあった場合は戻す
  $_SESSION['err'] = $err;
  header('Location: signup_form.php');
  return;
}


if(count($err) === 0){
  
  // 画像をアップロードする
  $tmp_path = $file['tmp_name'];
  $upload_dir = 'member_picture/';
  $save_filename = date('YmdHis') . $filename;
  $save_path = $upload_dir . $save_filename;
  move_uploaded_file($tmp_path, $save_path);
  

  // ユーザーを登録する
  $_SESSION['join'] = $_POST;
  $_SESSION['join']['image'] = $save_filename;
  header('Location: check.php');
  exit();
  
}

//書き直し
if ($_REQUEST['action'] === 'rewrite'){
  $_POST = $_SESSION['join'];
  $err['rewrite'] = true;
}
?>
