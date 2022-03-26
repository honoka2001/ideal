<?php
session_start();
require_once '../functions.php';
require_once '../classes/UserLogic.php';

// ログイン済みの時登録画面を表示しない
$result = UserLogic::checkLogin();
if($result) {
  header('Location: home.php');
  return;
}

// ログインエラー
$login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null;
unset($_SESSION['login_err']);

//バリデーションエラー
if(isset($_SESSION['err'])) { 
  $err = $_SESSION['err'];
}
else {
  $err = NULL;
}
$_SESSION['err'] = array();

//値の保持
if(isset($_SESSION['keep'])) { 
  $keep = $_SESSION['keep'];
}
else {
  $keep = NULL;
}
$_SESSION['keep'] = array();


?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/reset.css">
  <link rel="stylesheet" type="text/css" href="css/common.css?v=2">
  <script src="https://kit.fontawesome.com/73ee5ba027.js" crossorigin="anonymous"></script>

  <title>ユーザー登録</title>
</head>

<body class="signup">

  <div class="container">

    <div class="inner">

      <div class="box start">

        <img src="images/ideal.png" class="logo" alt="logo">

        <form enctype="multipart/form-data" action="signup.php" method="post">

          <div class="file-up glass">
            <i class="fas fa-user-circle"></i>
            <p>アイコン</p>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" id="myicon" name = "image" accept="image/*">
            <img id="preview_icon">
          </div>

          <script>
          document.getElementById('myicon').addEventListener('change', function (e) {
          // 1枚だけ表示する
          var file = e.target.files[0];

          // ファイルのブラウザ上でのURLを取得する
          var blobUrl = window.URL.createObjectURL(file);

          // img要素に表示
          var img = document.getElementById('preview_icon');
          img.src = blobUrl;
          });
          </script>

           <?php if(isset($err['file_size'])): ?>
              <p class = "err"><?php echo h($err['file_size']); ?></p>
           <?php endif; ?>
           <?php if(isset($err['file_ext'])): ?>
              <p class = "err"><?php echo h($err['file_ext']); ?></p>
           <?php endif; ?>
           <?php if(isset($err['file_rewrite'])): ?>
              <p class = "err"><?php echo h($err['file_rewrite']); ?></p>
           <?php endif; ?>

          <div class="input_box name">
            <input type="text" name="name" size="35" maxlength="255" placeholder="ユーザー名" value="<?php 
              if(isset($keep['name'])){
                echo h($keep['name']);
              }?>">
          <?php if(isset($err['name'])): ?>
              <p><?php echo h($err['name']); ?></p>
            <?php endif; ?>
          </div>

          <div class="input_box">
            <input type="email" name="email" size="35" maxlength="255" placeholder="メールアドレス"value="<?php 
              if(isset($keep['email'])){
                echo h($keep['email']);
              }?>">
            <?php if(isset($err['email'])): ?>
              <p><?php echo h($err['email']); ?></p>
            <?php endif; ?>
            <?php if(isset($err['email_dup'])): ?>
              <p><?php echo h($err['email_dup']); ?></p>
            <?php endif; ?>
          </div>

          <div class="input_box">
            <input type="password" name="password" size="10" maxlength="255" placeholder="パスワード(8文字以上100文字以下)" value="<?php 
              if(isset($keep['password'])){
                echo h($keep['password']);
              }?>">
            <?php if(isset($err['password'])): ?>
              <p><?php echo h($err['password']); ?></p>
            <?php endif; ?>
          </div>

          <div class="input_box">
            <input type="password" name="password_conf" size="10" maxlength="255" placeholder="パスワード確認" value="<?php 
              if(isset($keep['password_conf'])){
                echo h($keep['password_conf']);
              }?>">
            <?php if (isset($err['password_conf'])) : ?>
              <p><?php echo $err['password_conf']; ?></p>
            <?php endif; ?>
          </div>

          <p class="change"><a href="login_form.php">ログインする</a> </p>

          <div class="input_box">
            <input type="submit" value="アカウント登録確認">
          </div>

        </form>
      </div>

    </div>

  </div>

  <script> </script>

</body>

</html>