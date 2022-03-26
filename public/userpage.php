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
$login_user_id = $_SESSION['login_user']['id'];
$user_id = $_GET['id'];
$user = UserLogic::getById($user_id);
$blogs = BlogLogic::getMyBlog($user_id);
$cntAllBlog= BlogLogic::cntBlog($user_id);

if($login_user_id == $user_id){
  header('Location: mypage.php');
  return;
}

//カテゴリ別の投稿数を取得
$stack=array();
for ($category = 1; $category < 5 ; $category++){
  // 実行する処理
  $cntCategoryBlog = BlogLogic::cntCategoryBlog($user_id, $category);
  array_push($stack , $cntCategoryBlog);
}

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

  <title>個人ページ</title>
</head>

<body>

  <div class="container">
    <div class="inner">


      <div class="boad mypage home">
        <!-- ヘッダー -->
        <header>
          <img src="images/logo_small.png" alt="ideal" class="logo">
        </header>

        <!-- ログアウト -->
        <!-- <form action="logout.php" method="POST">
          <div class="input_box">
            <input type="submit" name="logout" value="ログアウト">
          </div>
        </form> -->


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

        
        <div class="right">
          
          
          <div class="plofile_area">
            <a href="home.php">
              <i class="fas fa-arrow-left"></i>
            </a>
            <div class="posted_person user">
              <img src="member_picture/<?php echo h($user['picture']) ?>" alt="アイコン" class="my_icon">
              <h2><?php echo h($user['name']) ?></h2>
              <p><?php echo h($user['introduction']) ?></p>
           </div>

            <div class="count">
              <h4><?php echo h($cntAllBlog) ?></h4>
              <p>投稿</p>
            </div>

            <div class="box_cnt">
              
              <div class="category_cnt">
                <h4 class="category1">Make</h4>
                <p><?php echo h($stack[0]) ?></p>
              </div>
              
              <div class="category_cnt">
                <h4 class="category2">Skin</h4>
                <p><?php echo h($stack[1]) ?></p>

              </div>
              
              <div class="category_cnt">
                <h4 class="category3">Hair</h4>
                <p><?php echo h($stack[2]) ?></p>

              </div>
              
              <div class="category_cnt">
                <h4 class="category4">Other</h4>
                <p><?php echo h($stack[3]) ?></p>

              </div>

            </div>

          </div>


          
          <div class="cards">
          <h2 class="glass article" >Article</h2>

          <div class="container">

            <?php foreach($blogs as $blog): ?>
              <a href="detail_userpage.php?id=<?php echo $blog['id'] ?>" class="blog_detail">
                <div class="card">
                  <img src="<?php echo h("{$blog['file_path']}"); ?>" alt="サムネ">
                  <div class="tags">
                    <div class="tag category<?php echo h("{$blog['category']}") ?>"><?php echo h(BlogLogic::setCategoryName("{$blog['category']}")) ?></div>
                  </div>
                  <h3><?php echo h("{$blog['title']}") ?></h3>
                  <p class="date"><?php echo h("{$blog['modified']}") ?></p>
                </div>
              </a>
              <?php endforeach; ?>
            </div>
              
          </div>
          
        </div>
        
      </div>
    </div>
  </div>
  
  <script> </script>
</body>