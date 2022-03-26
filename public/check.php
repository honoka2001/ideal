<?php
session_start();
require_once '../functions.php';
// require_once '../dbconnect.php';
require_once '../classes/UserLogic.php';



if(!isset($_SESSION['join'])){
  header('Location: signup_form.php');
  exit();
}

if(!empty($_POST)){
  //登録処理
  UserLogic::createUser($_POST);
  unset($_SESSION['join']);
  header('Location: login_form.php');

}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/reset.css">
  <link rel="stylesheet" type="text/css" href="css/common.css?v=2">
  <script src="https://kit.fontawesome.com/73ee5ba027.js" crossorigin="anonymous"></script>

  <title>ユーザー登録確認</title>
</head>

<body class="signup">

  <div class="container">

    <div class="inner">

      <div class="box">

        <form action="" method="post">
          <input type="hidden" name="action" value="submit" />

          <img class = "member_picture" src="member_picture/<?php echo h($_SESSION['join']['image']); ?>">

          <div class="session">
            <h2>ユーザー名</h2>
            <p><?php echo h($_SESSION['join']['name']); ?></p> 
            <h2>メールアドレス</h2>
            <p><?php echo h($_SESSION['join']['email']); ?></p>
          </div>

          <p class="change"><a href="signup_form.php?action=rewrite">&laquo;&nbsp;書き直す<br>(アイコンのファイルデータは保持されません。)</a> </p>

          <div class="input_box">
            <input type="submit" value="アカウント登録">
          </div>

        </form>
      </div>

    </div>

  </div>

  <script> </script>

</body>

</html>