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
$result = BlogLogic::getById($_GET['id']);



?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/reset.css">
  <link rel="stylesheet" type="text/css" href="css/common.css?v=2">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/73ee5ba027.js" crossorigin="anonymous"></script>

  <title>詳細</title>
</head>

<body class="detail">

  <div class="container">
    <div class="inner">


      <div class="boad">
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
            <a class="nav-link active glass" href="home.php">
              <i class="fas fa-home"></i>
              HOME
            </a>
                        
            <a class="nav-link" href="serch.php">
              <i class="fas fa-search"></i>
              SERCH
            </a>


            <a class="nav-link" href="new_post.php">
              <i class="fas fa-feather"></i>
              WRITE
            </a>
            
            <a class="nav-link" href="mypage.php">
              <i class="fas fa-user-circle"></i>
              MYPAGE
            </a>

          </nav>

        </div>


        <div class="cards">
          <div class="card">
            
            <p class="date"><?php echo h("{$result['modified']}"); ?></p>
            <div class="tags">
              <div class="tag category<?php echo h("{$result['category']}") ?>"><?php echo h(BlogLogic::setCategoryName("{$result['category']}")) ?></div>
            </div>
            <a href="userpage.php?id=<?php echo h("{$result['member_id']}") ?>">
              <div class="posted_person">
                <img src="member_picture/<?php echo h("{$result['picture']}") ?>" alt="プロフィール画像">
                <p><?php echo h("{$result['name']}"); ?></p>
              </div>
            </a>
            <img src="<?php echo h("{$result['file_path']}"); ?>" alt="サムネ">
            <h3><?php echo h($result['title']); ?></h3>
            <p class="detail_content"><?php echo str_replace("&amp;#13;&amp;#10;","<br>",nl2br(h($result['content']))); ?>
            </p>
            <p class="btn"><a href="userpage.php?id=<?php echo h($result['member_id']) ?>">戻る</a></p>

          </div>

        </div>




      </div>

    </div>
  </div>

  <script> </script>
</body>