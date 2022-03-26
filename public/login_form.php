<?php
session_start();
require_once '../functions.php';

require_once '../classes/UserLogic.php';

// ログイン済みの時ログイン画面を表示しない
$result = UserLogic::checkLogin();
if($result) {
  header('Location: home.php');
  return;
}

//値の保持
if(isset($_SESSION['keep'])) { 
  $keep = $_SESSION['keep'];
}
else {
  $keep = NULL;
}
$_SESSION['keep'] = array();

//バリデーションエラー
if(isset($_SESSION['err'])) { 
  $err = $_SESSION['err'];
}
else {
  $err = NULL;
}
$_SESSION['err'] = array();

//バリデーションエラー
if(isset($_SESSION['msg'])) { 
  $msg = $_SESSION['msg'];
}
else {
  $msg = NULL;
}
$_SESSION['msg'] = array();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/reset.css">
  <link rel="stylesheet" type="text/css" href="css/common.css">

  <title>ログイン</title>
</head>

<body class="login">

  <div class="container">

    <div class="inner">

      <div class="box start">

        <!-- <div class="square_box">
          <div class="square"></div>
          <div class="square"></div>
          <div class="square"></div>
          <div class="square"></div>
          <div class="square"></div>
          <div class="square"></div>
        </div> -->

        <img src="images/ideal.png" class="logo" alt="logo">

          <?php if (isset($msg['pass'])) : ?>
            <p><?php echo h($msg['pass']); ?></p>
          <?php endif; ?>

          <?php if (isset($msg['email'])) : ?>
            <p><?php echo h($msg['email']); ?></p>
          <?php endif; ?>

        <form action="login.php" method="post">

          <div class="input_box">
            <input type="text" name="email" placeholder="メールアドレス"  value="<?php 
              if(isset($keep['email'])){
                echo h($keep['email']);
              }?>">
            <!-- メールアドレスエラーがあったら -->
            <?php if (isset($err['email'])) : ?>
              <p><?php echo h($err['email']); ?></p>
            <?php endif; ?>
          </div>

          <div class="input_box">
            <input type="password" name="password" placeholder="パスワード" value="<?php 
              if(isset($keep['password'])){
                echo h($keep['password']);
              }?>">
            <!-- パスワードエラーがあったら -->
            <?php if (isset($err['password'])) : ?>
              <p><?php echo h($err['password']); ?></p>
            <?php endif; ?>
          </div>

          <p class="change">
            <a href="signup_form.php">アカウント登録する</a>
          </p>

          <div class="input_box">
            <input type="submit" value="ログイン">
          </div>

        </form>
        <form action="login.php" method="post">
            <input type="hidden" name="email" value='gest@gmail.com'>
            <input type="hidden" name="password" value='gestgest'>
            <!-- <input type="submit" class="gest" value='ゲストとしてログイン'> -->
        </form>

      </div>

    </div>

  </div>

  <script> </script>
</body>

</html>