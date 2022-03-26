<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../classes/BlogLogic.php';

require_once '../functions.php';

// ログインしているか判定し、していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if (!$result) {
  $_SESSION['login_err'] = 'ユーザを登録してログインしてください！';
  header('Location: signup_form.php');
  return;
}
$login_user = $_SESSION['login_user'];

// $blog = $_SESSION['blog_detail']['id'];
// $result = BlogLogic::getById($blog);

if(isset($_SESSION['err_msgs'])){
  $err_msgs = $_SESSION['err_msgs'];
  $_SESSION['err_msgs'] = array();
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

  <title>プロフィール編集</title>
</head>

<body>

  <div class="container">

    <div class="inner">
      <div class="boad edit_profile new_post">
        <!-- ヘッダー -->
        <header>
          <img src="images/logo_small.png" alt="ideal" class="logo">
        </header>

        
        
        <div class="left glass">
          
          
          <!-- ログインユーザー情報 -->
          <div class="person">
           <img src="member_picture/<?php echo h($login_user['picture']) ?>" alt="アイコン" class="my_icon">
            <p class="my_name">
              <?php echo h($login_user['name']) ?>
            </p>
          </div>
          
          <!-- ナビゲーション -->
          <nav>
            <a class="nav-link" href="home.php">
              <i class="fas fa-home"></i>
              HOME
            </a>
            
            <a class="nav-link" href="serch.php">
              <i class="fas fa-search"></i>
              SERCH
            </a>
              
            <a class="nav-link" href="./new_post.php">
              <i class="fas fa-feather"></i>
              WRITE
            </a>
            
            <a class="nav-link active glass" href="mypage.php">
                <i class="fas fa-user-circle"></i>
              MYPAGE
            </a>
         </nav>
        
        </div>

        <div class="cards">

          
          <form enctype="multipart/form-data" action="./profile_update.php" method="post" class="form">
           <h2>プロフィール編集</h2>

          
            <div class="file-up glass">
              <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
              <input type="file" id="myicon" name = "image" accept="image/*">
              <img src="member_picture/<?php echo h($login_user['picture']) ?>" id="preview_icon">
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
              <p>ユーザー名</p>
              <input type="text" name="name" size="35" maxlength="255" placeholder="ユーザー名を入力してください。" value="<?php echo h($login_user['name']) ?>">
              <?php if(isset($err_msgs['name'])): ?>
                <p class="err"><?php echo h($err_msgs['name']); ?></p>
              <?php endif; ?>
            </div>

            <div class="input_box intro">
             <p>自己紹介</p>
              <textarea name="intro" id="intro" cols="30" rows="10" placeholder="紹介文を入力してください。(100文字以内)"><?php echo str_replace("&amp;#13;&amp;#10;","\n",nl2br(h($login_user['introduction']))); ?></textarea>
              <?php if(isset($err_msgs['intro'])): ?>
                <p class="err"><?php echo h($err_msgs['intro']); ?></p>
                <?php endif; ?>
            </div>

                
            <div class="input">
              <input type="submit" value="プロフィール変更">
            </div>
              
          </form>
        
        </div>
      </div>
    
  </div>

  <script> </script>

</body>

</html>