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


//投稿成功メッセージ
if(isset($_SESSION['suc'])) { 
  $suc=$_SESSION['suc'];
}
else {
  $suc = NULL;
}
$_SESSION['suc']=array();


$login_user = $_SESSION['login_user'];

$blogs = BlogLogic::getAllBlog();
$users = BlogLogic::getAllUser();


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

  <title>ホーム</title>
</head>

<body>

  <div class="container">
    <div class="inner">


      <div class="boad home">
        <!-- ヘッダー -->
        <header>
          <img src="images/logo_small.png" alt="ideal" class="logo">
        </header>
        
        <!-- ログアウト -->
        <form action="logout.php" method="POST">
          <div class="input_box">
            <input type="submit" name="logout" value="ログアウト" onclick="return confirm('ログアウトしますか？')">
          </div>
        </form>

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
            <a class="nav-link active glass" href="#">
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
        
        
        <div class="right">
         <!-- 投稿完了メッセージ -->
          <?php if ($suc != NULL) : ?>
            <div class="suc">
              <i class="fas fa-check"></i>
              <p>
                  <?php echo h($suc);?>
              </p>
            </div>
          <?php endif; ?>
            
          <!-- 記事 -->
          <div class="cards">
            
            <h2 class="glass article" >Article</h2>
            <?php foreach($blogs as $blog): ?>
              <a href="detail.php?id=<?php echo h($blog['id']) ?>" class="blog_detail">
                <div class="card">
                  <img src="<?php echo h("{$blog['file_path']}"); ?>" alt="サムネ">
                  <div class="tags">
                    <div class="tag category<?php echo h("{$blog['category']}") ?>"><?php echo h(BlogLogic::setCategoryName("{$blog['category']}")) ?></div>
                  </div>
                  <h3><?php echo h("{$blog['title']}") ?></h3>
                  <div class="posted_person">
                    <img src="member_picture/<?php echo h("{$blog['picture']}") ?>" alt="プロフィール画像">
                    <p><?php echo h("{$blog['name']}") ?></p>
                  </div>
                  <p class="date"><?php echo h("{$blog['modified']}") ?></p>
                </div>
              </a>
            <?php endforeach; ?>

            
          </div>
          <div class="users glass">
            
          <h2 class="glass members" >Members</h2>
            <?php foreach($users as $user): ?>
              <a href="userpage.php?id=<?php echo h("{$user['id']}") ?>">
                <div class="user glass">
                  <img src="member_picture/<?php echo h("{$user['picture']}") ?>" alt="">
                  <p><?php echo h("{$user['name']}") ?></p>
                </div>
              </a>
           <?php endforeach; ?>

          </div>
        
        </div>

      </div>
    </div>
  </div>

  <script> </script>
</body>